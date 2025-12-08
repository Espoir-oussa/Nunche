#!/bin/bash

# 1️⃣ Lancer les migrations et seeders (une seule fois si nécessaire)
php artisan migrate --force
php artisan db:seed --force

# 2️⃣ Lancer Apache
apache2-foreground
