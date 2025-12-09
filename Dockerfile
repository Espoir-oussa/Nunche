# ---- Base PHP + Apache ----
FROM php:8.2-apache

# ---- Installer dépendances système ----
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libonig-dev libxml2-dev curl bash \
    && docker-php-ext-install pdo_mysql mbstring xml zip \
    && a2enmod rewrite

# ---- Config Apache pour pointer vers public/ ----
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

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

# ---- Installer dépendances frontend et build ----
RUN npm ci
RUN npm run build

# ---- Donner les droits pour Laravel ----
RUN chown -R www-data:www-data storage bootstrap/cache

# ---- Copier le script de démarrage ----
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 10000

CMD ["start.sh"]
