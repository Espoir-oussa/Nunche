# ---- Base PHP + Apache ----
FROM php:8.2-apache

# ---- Installer dépendances système ----
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libonig-dev libxml2-dev curl bash \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring xml zip gd \
    && a2enmod rewrite headers

# ---- Config Apache pour pointer vers public/ et AllowOverride ----
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf \
    && sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf

# ---- Installer Composer ----
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# ---- Installer Node.js et npm ----
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# ---- Copier le projet ----
WORKDIR /var/www/html
COPY . .

# ---- Installer dépendances PHP ----
RUN composer install --no-dev --optimize-autoloader

# ---- Installer dépendances frontend MAIS NE PAS BUILD ICI ----
RUN npm ci

# ---- Créer .env temporaire pour le build (optionnel) ----
RUN if [ -f ".env.example" ]; then \
    cp .env.example .env && \
    echo "APP_URL=http://localhost" >> .env && \
    echo "VITE_APP_URL=http://localhost" >> .env; \
fi

# ---- Build avec config temporaire ----
RUN npm run build

# ---- Nettoyer .env temporaire ----
RUN rm -f .env

# ---- Donner les droits pour Laravel ----
RUN chown -R www-data:www-data storage bootstrap/cache public/build
RUN chmod -R 775 storage bootstrap/cache public/build

# ---- Copier le script de démarrage ----
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 10000

CMD ["/usr/local/bin/start.sh"]
