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

# ---- Copier UNIQUEMENT les fichiers de dépendances en premier ----
WORKDIR /var/www/html

# Copier les fichiers de configuration des dépendances (optimisation cache)
COPY composer.json composer.lock package.json package-lock.json* ./

# ---- Installer dépendances PHP (avec cache) ----
RUN composer install --no-dev --no-scripts --no-autoloader \
    && composer clear-cache

# ---- Installer dépendances Node (avec cache) ----
RUN npm ci --no-audit --prefer-offline

# ---- Copier le reste de l'application ----
COPY . .

# ---- Autoload et optimisations ----
RUN composer dump-autoload --optimize \
    && composer run-script post-install-cmd --no-interaction

# ---- Build des assets avec APP_URL par défaut ----
RUN echo "APP_URL=http://localhost" > .env \
    && echo "VITE_APP_URL=http://localhost" >> .env \
    && npm run build \
    && rm .env

# ---- Créer la structure de stockage ----
RUN mkdir -p /var/www/html/storage/app/public \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs

# ---- Créer le lien symbolique pour le stockage ----
RUN php artisan storage:link

# ---- Permissions correctes ----
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ---- Copier le script de démarrage ----
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 10000

CMD ["/usr/local/bin/start.sh"]
