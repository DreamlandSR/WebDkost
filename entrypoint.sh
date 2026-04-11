#!/bin/sh

# Cek .env ada, kalau tidak copy dari .env.example
if [ ! -f /var/www/.env ]; then
    echo ".env not found, copying from .env.example..."
    cp /var/www/.env.example /var/www/.env
    cd /var/www && php artisan key:generate --ansi
fi

# Install vendor jika belum ada
if [ ! -d /var/www/vendor ]; then
    echo "Installing composer dependencies..."
    cd /var/www && composer install --no-interaction --optimize-autoloader --no-dev
fi

# Install npm dependencies & build jika belum ada
if [ ! -d /var/www/node_modules ]; then
    echo "Installing npm dependencies..."
    cd /var/www && npm install
    npm run build
fi

# Buat storage link jika belum ada
if [ ! -d /var/www/public/storage ]; then
    echo "Creating storage link..."
    cd /var/www && php artisan storage:link
fi

# Fix permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Tunggu database siap
echo "Waiting for database..."
until php -r "
    try {
        \$pdo = new PDO(
            'mysql:host=' . trim(shell_exec('grep ^DB_HOST /var/www/.env | cut -d= -f2')) . ';port=3306;dbname=' . trim(shell_exec('grep ^DB_DATABASE /var/www/.env | cut -d= -f2')),
            trim(shell_exec('grep ^DB_USERNAME /var/www/.env | cut -d= -f2')),
            trim(shell_exec('grep ^DB_PASSWORD /var/www/.env | cut -d= -f2'))
        );
        exit(0);
    } catch (Exception \$e) {
        exit(1);
    }
" 2>/dev/null; do
    echo "Database not ready, waiting 2s..."
    sleep 2
done
echo "Database ready!"

# Migrate
php artisan migrate --force

# Clear semua cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Jalankan scheduler
php artisan schedule:work &

exec "$@"
