#!/bin/bash
set -e

echo "Storage link..."
php artisan storage:link || true

echo "Migrating database..."
php artisan migrate --force || true

echo "Optimizing Laravel..."
php artisan optimize

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx..."
exec nginx -g "daemon off;"