#!/bin/bash
set -e

echo "▶ Setting up environment..."

# Créer .env s'il n'existe pas
if [ ! -f /var/www/html/.env ]; then
    echo "▶ Creating .env from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Injecter les variables d'environnement de Render dans .env
echo "▶ Injecting environment variables from Render..."
[ -n "$APP_NAME" ] && sed -i "s|APP_NAME=.*|APP_NAME=$APP_NAME|g" /var/www/html/.env
[ -n "$APP_ENV" ] && sed -i "s|APP_ENV=.*|APP_ENV=$APP_ENV|g" /var/www/html/.env
[ -n "$APP_DEBUG" ] && sed -i "s|APP_DEBUG=.*|APP_DEBUG=$APP_DEBUG|g" /var/www/html/.env
[ -n "$APP_URL" ] && sed -i "s|APP_URL=.*|APP_URL=$APP_URL|g" /var/www/html/.env
[ -n "$APP_KEY" ] && sed -i "s|APP_KEY=.*|APP_KEY=$APP_KEY|g" /var/www/html/.env
[ -n "$DB_CONNECTION" ] && sed -i "s|DB_CONNECTION=.*|DB_CONNECTION=$DB_CONNECTION|g" /var/www/html/.env
[ -n "$DB_HOST" ] && sed -i "s|DB_HOST=.*|DB_HOST=$DB_HOST|g" /var/www/html/.env
[ -n "$DB_PORT" ] && sed -i "s|DB_PORT=.*|DB_PORT=$DB_PORT|g" /var/www/html/.env
[ -n "$DB_DATABASE" ] && sed -i "s|DB_DATABASE=.*|DB_DATABASE=$DB_DATABASE|g" /var/www/html/.env
[ -n "$DB_USERNAME" ] && sed -i "s|DB_USERNAME=.*|DB_USERNAME=$DB_USERNAME|g" /var/www/html/.env
[ -n "$DB_PASSWORD" ] && sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|g" /var/www/html/.env
[ -n "$SESSION_DRIVER" ] && sed -i "s|SESSION_DRIVER=.*|SESSION_DRIVER=$SESSION_DRIVER|g" /var/www/html/.env
[ -n "$CACHE_STORE" ] && sed -i "s|CACHE_STORE=.*|CACHE_STORE=$CACHE_STORE|g" /var/www/html/.env
[ -n "$QUEUE_CONNECTION" ] && sed -i "s|QUEUE_CONNECTION=.*|QUEUE_CONNECTION=$QUEUE_CONNECTION|g" /var/www/html/.env

# Ajouter les variables FedaPay si elles existent
if [ -n "$FEDAPAY_SECRET_KEY" ]; then
    grep -q "FEDAPAY_SECRET_KEY" /var/www/html/.env || echo "FEDAPAY_SECRET_KEY=$FEDAPAY_SECRET_KEY" >> /var/www/html/.env
    grep -q "FEDAPAY_PUBLIC_KEY" /var/www/html/.env || echo "FEDAPAY_PUBLIC_KEY=$FEDAPAY_PUBLIC_KEY" >> /var/www/html/.env
    grep -q "FEDAPAY_ENV" /var/www/html/.env || echo "FEDAPAY_ENV=$FEDAPAY_ENV" >> /var/www/html/.env
fi

echo "▶ Setting permissions..."
mkdir -p /var/www/html/storage/logs
touch /var/www/html/storage/logs/laravel.log
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Générer APP_KEY seulement si non défini dans les variables d'environnement
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:placeholder" ]; then
    echo "▶ Generating application key..."
    NEW_KEY=$(php artisan key:generate --show)
    if [ -n "$NEW_KEY" ]; then
        sed -i "s|APP_KEY=.*|APP_KEY=$NEW_KEY|g" /var/www/html/.env
        echo "   APP_KEY generated successfully"
    fi
else
    echo "▶ Using existing APP_KEY from environment"
fi

echo "▶ Creating storage link..."
php artisan storage:link || echo "⚠ Storage link already exists"

echo "▶ Clearing all caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "▶ Caching config..."
php artisan config:cache
php artisan view:cache

echo "▶ Final permissions fix..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

echo "▶ Running migrations..."
php artisan migrate --force || echo "⚠ Migration error (ignored)"

echo "▶ Starting Apache on port ${PORT:-10000}..."
sed -i "s/80/${PORT:-10000}/g" /etc/apache2/sites-available/000-default.conf
sed -i "s/Listen 80/Listen ${PORT:-10000}/g" /etc/apache2/ports.conf
apache2-foreground
