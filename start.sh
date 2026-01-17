#!/bin/bash
set -e

echo "🚀 Démarrage de l'application Laravel..."

# ---- 1. Port Apache ----
echo "🔧 Configuration du port ${PORT:-10000}..."
sed -i "s/80/${PORT:-10000}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# ---- 2. Fichier .env ----
ENV_FILE="/var/www/html/.env"

if [ ! -f "$ENV_FILE" ]; then
    echo "📄 Création de .env..."
    cp /var/www/html/.env.example "$ENV_FILE"

    # Clé d'application obligatoire
    php artisan key:generate --force
fi

# ---- 3. Fonction pour injecter les variables ----
inject_env() {
    local key="$1"
    local value="$2"

    if [ -n "$value" ]; then
        local escaped_value=$(echo "$value" | sed 's/[\/&]/\\&/g')
        if grep -q "^$key=" "$ENV_FILE"; then
            sed -i "s|^$key=.*|$key=$escaped_value|g" "$ENV_FILE"
        else
            echo "$key=$escaped_value" >> "$ENV_FILE"
        fi
        echo "   ✅ $key mis à jour"
    fi
}

echo "🔧 Injection des variables critiques..."
inject_env "APP_NAME" "$APP_NAME"
inject_env "APP_ENV" "$APP_ENV"
inject_env "APP_DEBUG" "$APP_DEBUG"
inject_env "APP_URL" "$APP_URL"
inject_env "APP_KEY" "$APP_KEY"

inject_env "DB_CONNECTION" "$DB_CONNECTION"
inject_env "DB_HOST" "$DB_HOST"
inject_env "DB_PORT" "$DB_PORT"
inject_env "DB_DATABASE" "$DB_DATABASE"
inject_env "DB_USERNAME" "$DB_USERNAME"
inject_env "DB_PASSWORD" "$DB_PASSWORD"

inject_env "CACHE_STORE" "$CACHE_STORE"
inject_env "QUEUE_CONNECTION" "$QUEUE_CONNECTION"

inject_env "CLOUDINARY_URL" "$CLOUDINARY_URL"
inject_env "FILESYSTEM_DRIVER" "$FILESYSTEM_DRIVER"

if [ "$FILESYSTEM_DRIVER" = "cloudinary" ] && [ -z "$CLOUDINARY_URL" ]; then
    echo "⚠️  ATTENTION: FILESYSTEM_DRIVER=cloudinary mais CLOUDINARY_URL non définie!"
fi

if [ -n "$APP_URL" ]; then
    inject_env "VITE_APP_URL" "$APP_URL"
    inject_env "ASSET_URL" "$APP_URL"
fi

if [ "$FILESYSTEM_DRIVER" = "cloudinary" ] && [ -n "$CLOUDINARY_URL" ]; then
    echo "   ✅ Cloudinary configuré"
    if ! grep -q "FILESYSTEM_DISK=" "$ENV_FILE"; then
        echo "FILESYSTEM_DISK=cloudinary" >> "$ENV_FILE"
    fi
else
    inject_env "FILESYSTEM_DISK" "public"
fi

# ---- 4. Stockage ----
echo "📁 Création des dossiers de stockage et liens..."
php artisan storage:link --force 2>/dev/null || true
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ---- 5. Clear config pour prendre les bonnes variables DB ----
echo "🧹 Nettoyage du cache de configuration..."
php artisan config:clear

# ---- 6. Migration + Seeders ----
echo "🗄️  Migration de la base et exécution des seeders..."
php artisan migrate --force
php artisan db:seed --force || true

# ---- 7. Optimisations ----
echo "⚡ Optimisation et cache de production..."
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:cache
php artisan view:cache

# ---- 8. Vérification des assets ----
echo "🎨 Vérification des assets Vite..."
if [ -f "public/build/manifest.json" ]; then
    echo "   ✅ Manifest Vite trouvé"
    APP_URL_CLEAN=$(echo "$APP_URL" | sed 's|https\?://||')
    sed -i "s|http://localhost|$APP_URL|g" public/build/manifest.json 2>/dev/null || true
    sed -i "s|//localhost|//$APP_URL_CLEAN|g" public/build/manifest.json 2>/dev/null || true
else
    echo "   ⚠️ Manifest Vite non trouvé - vérifiez le build"
fi

# ---- 9. Démarrage Apache ----
echo "✅ Application prête !"
echo "🌐 URL: $(grep 'APP_URL=' "$ENV_FILE" | cut -d= -f2 2>/dev/null || echo 'localhost')"
echo "🔌 Port: ${PORT:-10000}"
echo "☁️  Stockage: $(grep 'FILESYSTEM_DISK=' "$ENV_FILE" | cut -d= -f2 2>/dev/null || echo 'public')"

exec apache2-foreground
