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

# Fonction pour injecter une variable
inject_env() {
    local key="$1"
    local value="$2"

    if [ -n "$value" ]; then
        # Échapper les caractères spéciaux pour sed
        local escaped_value=$(echo "$value" | sed 's/[\/&]/\\&/g')

        if grep -q "^$key=" "$ENV_FILE"; then
            sed -i "s|^$key=.*|$key=$escaped_value|g" "$ENV_FILE"
        else
            echo "$key=$escaped_value" >> "$ENV_FILE"
        fi
        echo "   ✅ $key mis à jour"
    fi
}

echo "🔧 Injection des variables..."

# ---- VARIABLES CRITIQUES ----

# 1. Application
inject_env "APP_NAME" "$APP_NAME"
inject_env "APP_ENV" "$APP_ENV"
inject_env "APP_DEBUG" "$APP_DEBUG"
inject_env "APP_URL" "$APP_URL"
inject_env "APP_KEY" "$APP_KEY"

# 2. Database
inject_env "DB_CONNECTION" "$DB_CONNECTION"
inject_env "DB_HOST" "$DB_HOST"
inject_env "DB_PORT" "$DB_PORT"
inject_env "DB_DATABASE" "$DB_DATABASE"
inject_env "DB_USERNAME" "$DB_USERNAME"
inject_env "DB_PASSWORD" "$DB_PASSWORD"

# 3. CLOUDINARY (TRÈS IMPORTANT !!!)
inject_env "CLOUDINARY_URL" "$CLOUDINARY_URL"
inject_env "FILESYSTEM_DRIVER" "$FILESYSTEM_DRIVER"

# Si CLOUDINARY_URL n'est pas définie mais que FILESYSTEM_DRIVER=cloudinary
if [ "$FILESYSTEM_DRIVER" = "cloudinary" ] && [ -z "$CLOUDINARY_URL" ]; then
    echo "⚠️  ATTENTION: FILESYSTEM_DRIVER=cloudinary mais CLOUDINARY_URL non définie!"
    echo "   Les uploads ne fonctionneront pas sans Cloudinary!"
fi

# 4. URLs pour Vite/Assets (toujours synchroniser avec APP_URL)
if [ -n "$APP_URL" ]; then
    inject_env "VITE_APP_URL" "$APP_URL"
    inject_env "ASSET_URL" "$APP_URL"
fi

# ---- 3. Configuration Cloudinary ----
echo "☁️  Configuration Cloudinary..."

if [ "$FILESYSTEM_DRIVER" = "cloudinary" ] && [ -n "$CLOUDINARY_URL" ]; then
    echo "   ✅ Cloudinary configuré"

    # Extraire les infos de CLOUDINARY_URL pour les variables séparées (si besoin)
    # cloudinary://API_KEY:API_SECRET@CLOUD_NAME

    # S'assurer que le disque est bien configuré
    if ! grep -q "FILESYSTEM_DISK=" "$ENV_FILE"; then
        echo "FILESYSTEM_DISK=cloudinary" >> "$ENV_FILE"
    fi
else
    echo "   ⚠️  Utilisation du stockage local (images perdues au redéploiement)"
    inject_env "FILESYSTEM_DISK" "public"
fi

# ---- 4. Stockage (toujours créer le lien pour compatibilité) ----
echo "📁 Configuration du stockage..."
php artisan storage:link --force 2>/dev/null || true

# Permissions minimales
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ---- 5. Cache et optimisation ----
echo "⚡ Optimisation..."

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Cache de production
php artisan config:cache
php artisan view:cache

# ---- 6. Vérification des assets ----
echo "🎨 Vérification des assets..."

if [ -f "public/build/manifest.json" ]; then
    echo "   ✅ Manifest Vite trouvé"

    # Corriger les URLs si nécessaire
    if [ -n "$APP_URL" ]; then
        APP_URL_CLEAN=$(echo "$APP_URL" | sed 's|https\?://||')

        # Remplacer localhost par l'URL réelle
        sed -i "s|http://localhost|$APP_URL|g" public/build/manifest.json 2>/dev/null || true
        sed -i "s|//localhost|//$APP_URL_CLEAN|g" public/build/manifest.json 2>/dev/null || true
    fi
else
    echo "   ⚠️  Manifest Vite non trouvé - vérifiez le build"
fi

# ---- 7. Démarrer ----
echo "✅ Application prête !"
echo "🌐 URL: $(grep 'APP_URL=' "$ENV_FILE" | cut -d= -f2 2>/dev/null || echo 'localhost')"
echo "🔌 Port: ${PORT:-10000}"
echo "☁️  Stockage: $(grep 'FILESYSTEM_DISK=' "$ENV_FILE" | cut -d= -f2 2>/dev/null || echo 'public')"

exec apache2-foreground
