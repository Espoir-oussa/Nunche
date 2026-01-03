#!/bin/bash
set -e

echo "▶ Initialisation de l'application Laravel..."

# ---- 1. Configuration Apache ----
echo "▶ Configuration Apache sur le port ${PORT:-10000}..."
sed -i "s/80/${PORT:-10000}/g" /etc/apache2/sites-available/000-default.conf
sed -i "s/Listen 80/Listen ${PORT:-10000}/g" /etc/apache2/ports.conf

# ---- 2. Fichier .env ----
ENV_FILE="/var/www/html/.env"

# Créer .env s'il n'existe pas
if [ ! -f "$ENV_FILE" ]; then
    echo "▶ Création du fichier .env..."
    cp /var/www/html/.env.example "$ENV_FILE"
fi

# Fonction pour définir une variable seulement si elle n'existe pas
set_env_var() {
    local var_name="$1"
    local env_value="$2"

    if [ -n "$env_value" ]; then
        if grep -q "^$var_name=" "$ENV_FILE"; then
            echo "   $var_name déjà défini, conservation de la valeur"
        else
            # Ajouter ou mettre à jour la variable
            if grep -q "^$var_name=" "$ENV_FILE"; then
                sed -i "s|^$var_name=.*|$var_name=$env_value|g" "$ENV_FILE"
            else
                echo "$var_name=$env_value" >> "$ENV_FILE"
            fi
            echo "   $var_name défini depuis l'environnement"
        fi
    fi
}

echo "▶ Injection des variables d'environnement..."

# Variables essentielles (ne jamais overwrite APP_KEY s'il existe)
if [ -n "$APP_KEY" ] && [ "$APP_KEY" != "base64:placeholder" ]; then
    set_env_var "APP_KEY" "$APP_KEY"
elif ! grep -q "APP_KEY=base64:" "$ENV_FILE"; then
    echo "▶ Génération de la clé d'application..."
    php artisan key:generate --force
fi

# Autres variables
set_env_var "APP_NAME" "$APP_NAME"
set_env_var "APP_ENV" "$APP_ENV"
set_env_var "APP_DEBUG" "$APP_DEBUG"
set_env_var "APP_URL" "$APP_URL"

# Base de données
set_env_var "DB_CONNECTION" "$DB_CONNECTION"
set_env_var "DB_HOST" "$DB_HOST"
set_env_var "DB_PORT" "$DB_PORT"
set_env_var "DB_DATABASE" "$DB_DATABASE"
set_env_var "DB_USERNAME" "$DB_USERNAME"
set_env_var "DB_PASSWORD" "$DB_PASSWORD"

# Vite/Assets - toujours synchroniser avec APP_URL
if [ -n "$APP_URL" ]; then
    set_env_var "VITE_APP_URL" "$APP_URL"
    set_env_var "ASSET_URL" "$APP_URL"
fi

# ---- 3. Gestion du stockage (CRITIQUE pour les images) ----
echo "▶ Configuration du stockage..."

# Recréer TOUJOURS le lien symbolique
echo "   Recréation du lien de stockage..."
rm -f /var/www/html/public/storage
php artisan storage:link --force

# S'assurer que les répertoires existent
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs

# Permissions
echo "   Définition des permissions..."
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# ---- 4. Cache Laravel ----
echo "▶ Nettoyage et cache Laravel..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "▶ Mise en cache de la configuration..."
php artisan config:cache
php artisan view:cache

# ---- 5. Vérification des assets ----
echo "▶ Vérification des assets..."
if [ -f "public/build/manifest.json" ]; then
    echo "   ✓ Manifest Vite trouvé"

    # Vérifier et corriger les URLs si nécessaire
    if [ -n "$APP_URL" ] && grep -q "localhost" public/build/manifest.json; then
        echo "   Correction des URLs dans le manifest..."
        sed -i "s|http://localhost|$APP_URL|g" public/build/manifest.json 2>/dev/null || true
        sed -i "s|//localhost|//$(echo $APP_URL | sed 's|https\?://||')|g" public/build/manifest.json 2>/dev/null || true
    fi
else
    echo "   ⚠ Manifest Vite non trouvé"
fi

# ---- 6. Vérification finale ----
echo "▶ Vérification finale..."
echo "   APP_URL: $(grep 'APP_URL=' "$ENV_FILE" | cut -d= -f2 || echo 'non défini')"
echo "   Stockage: $(ls -la /var/www/html/public/storage 2>/dev/null || echo 'lien non trouvé')"

# ---- 7. Démarrer Apache ----
echo "✅ Application prête ! Démarrage d'Apache..."
echo "📦 URL: $(grep 'APP_URL=' "$ENV_FILE" | cut -d= -f2)"
echo "🔗 Port: ${PORT:-10000}"

exec apache2-foreground
