# ---- Base PHP + Apache ----
FROM php:8.2-apache

# ---- Installer dépendances système ----
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libonig-dev libxml2-dev curl bash \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring xml zip gd exif \
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

# ---- Copier tout d'un coup (simplifié pour Render) ----
WORKDIR /var/www/html
COPY . .

# ---- Installer dépendances ----
RUN composer install --no-dev --optimize-autoloader --no-scripts \
    && npm ci --no-audit

# ---- Build des assets avec config minimale ----
RUN echo "APP_URL=http://localhost" > .env \
    && echo "VITE_APP_URL=http://localhost" >> .env \
    && npm run build \
    && rm .env

# ---- Créer la structure de stockage ----
RUN mkdir -p storage/app/public \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# ---- Copier le script de démarrage ----
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 10000

CMD ["/usr/local/bin/start.sh"]
