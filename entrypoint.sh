#!/bin/sh

# Copy file ke named volume jika masih kosong
if [ ! -f /var/www/public/index.php ]; then
    cp -r /var/www_src/. /var/www/
fi

# Fix permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Paksa session & cache pakai file
sed -i 's/SESSION_DRIVER=database/SESSION_DRIVER=file/' /var/www/.env
sed -i 's/CACHE_STORE=database/CACHE_STORE=file/' /var/www/.env

php artisan migrate --force
php artisan config:clear

exec "$@"
