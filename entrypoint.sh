#!/bin/sh

# Copy file ke named volume jika masih kosong
if [ ! -f /var/www/public/index.php ]; then
    cp -r /var/www_src/. /var/www/
fi

# Fix permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

exec "$@"
