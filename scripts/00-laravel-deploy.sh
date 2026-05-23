#!/usr/bin/env bash

echo "Starting Laravel deploy script..."

composer install --optimize-autoloader --no-interaction --ignore-platform-reqs

php artisan optimize:clear || true
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

php artisan migrate --force || true
php artisan storage:link || true

chmod -R 775 storage bootstrap/cache || true

echo "Laravel deploy script done."