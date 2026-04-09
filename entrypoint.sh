#!/bin/sh

# Fix permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Tunggu database siap
echo "Waiting for database..."
until php artisan db:show --no-interaction > /dev/null 2>&1; do
    echo "Database not ready, waiting 2s..."
    sleep 2
done
echo "Database ready!"

# Migrate
php artisan migrate --force

# Clear semua cache - JANGAN gunakan config:cache di Docker dengan volume mount
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Jalankan scheduler
php artisan schedule:work &

exec "$@"
