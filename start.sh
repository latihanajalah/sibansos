#!/bin/bash
set -e

echo "Fixing permissions..."

chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache

chmod -R 775 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache

echo "Storage link..."
php artisan storage:link || true

echo "Migrating..."
php artisan migrate --force || true

echo "Optimizing..."
php artisan optimize

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx..."
exec nginx -g "daemon off;"
