# ---- Base PHP + Apache ----
FROM php:8.2-apache

# ---- Installer dépendances système ----
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libonig-dev libxml2-dev curl bash \
    && docker-php-ext-install pdo_mysql mbstring xml zip \
    && a2enmod rewrite

# ---- Installer Composer ----
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# ---- Installer Node.js et npm ----
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# ---- Copier le projet ----
WORKDIR /var/www/html
COPY . /var/www/html

# ---- Installer dépendances PHP ----
RUN composer install --no-dev --optimize-autoloader

# ---- Installer dépendances frontend et builder Vue ----
RUN npm install
RUN npm run build

# ---- Donner les droits pour Laravel ----
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# ---- Copier le script de démarrage ----
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# ---- Exposer le port Apache ----
EXPOSE 10000

# ---- Commande de démarrage ----
CMD ["start.sh"]
