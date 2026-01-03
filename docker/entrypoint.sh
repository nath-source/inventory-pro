#!/bin/bash
set -e

# Cache Laravel Configs
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run Database Migrations (Auto-create tables)
php artisan migrate --force

# Start Apache
apache2-foreground