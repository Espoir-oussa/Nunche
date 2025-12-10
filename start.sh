#!/bin/bash
set -e

echo "▶ Setting up environment..."

# Créer .env s'il n'existe pas
if [ ! -f /var/www/html/.env ]; then
    echo "▶ Creating .env from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

echo "▶ Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "▶ Generating application key..."
php artisan key:generate --force

echo "▶ Creating storage link..."
php artisan storage:link || echo "⚠ Storage link already exists"

echo "▶ Clearing and caching config..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "▶ Running migrations..."
php artisan migrate --force || echo "⚠ Migration error (ignored)"

echo "▶ Running seeders..."
php artisan db:seed --force || echo "⚠ Seeder error (ignored)"

echo "▶ Starting Apache on port ${PORT:-10000}..."
sed -i "s/80/${PORT:-10000}/g" /etc/apache2/sites-available/000-default.conf
sed -i "s/Listen 80/Listen ${PORT:-10000}/g" /etc/apache2/ports.conf
apache2-foreground
