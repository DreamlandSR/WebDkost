#!/bin/sh

# Cek .env ada, kalau tidak copy dari .env.example
if [ ! -f /var/www/.env ]; then
    echo ".env not found, copying from .env.example..."
    cp /var/www/.env.example /var/www/.env

    if [ -z "$APP_KEY" ]; then
        cd /var/www && php artisan key:generate --ansi
    fi
fi

# Sinkronkan .env dengan environment dari Kubernetes jika tersedia
if [ -f /var/www/.env ]; then
    echo "Syncing .env config from environment..."

    if grep -q "^APP_URL=" /var/www/.env; then
        sed -i "s|^APP_URL=.*|APP_URL=${APP_URL:-http://103.157.27.229:30080}|" /var/www/.env
    else
        echo "APP_URL=${APP_URL:-http://103.157.27.229:30080}" >> /var/www/.env
    fi

    if grep -q "^ASSET_URL=" /var/www/.env; then
        sed -i "s|^ASSET_URL=.*|ASSET_URL=${ASSET_URL:-http://103.157.27.229:30080}|" /var/www/.env
    else
        echo "ASSET_URL=${ASSET_URL:-http://103.157.27.229:30080}" >> /var/www/.env
    fi

    sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=${DB_CONNECTION:-mysql}/" /var/www/.env
    sed -i "s/^DB_HOST=.*/DB_HOST=${DB_HOST:-mysql-service}/" /var/www/.env
    sed -i "s/^DB_PORT=.*/DB_PORT=${DB_PORT:-3306}/" /var/www/.env
    sed -i "s/^DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE:-webdkost}/" /var/www/.env
    sed -i "s/^DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME:-root}/" /var/www/.env
    sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD:-root}/" /var/www/.env

    if grep -q "^MAIL_MAILER=" /var/www/.env; then
        sed -i "s/^MAIL_MAILER=.*/MAIL_MAILER=${MAIL_MAILER:-smtp}/" /var/www/.env
    else
        echo "MAIL_MAILER=${MAIL_MAILER:-smtp}" >> /var/www/.env
    fi

    if grep -q "^MAIL_HOST=" /var/www/.env; then
        sed -i "s/^MAIL_HOST=.*/MAIL_HOST=${MAIL_HOST:-smtp.gmail.com}/" /var/www/.env
    else
        echo "MAIL_HOST=${MAIL_HOST:-smtp.gmail.com}" >> /var/www/.env
    fi

    if grep -q "^MAIL_PORT=" /var/www/.env; then
        sed -i "s/^MAIL_PORT=.*/MAIL_PORT=${MAIL_PORT:-587}/" /var/www/.env
    else
        echo "MAIL_PORT=${MAIL_PORT:-587}" >> /var/www/.env
    fi

    if grep -q "^MAIL_USERNAME=" /var/www/.env; then
        sed -i "s/^MAIL_USERNAME=.*/MAIL_USERNAME=${MAIL_USERNAME:-}/" /var/www/.env
    else
        echo "MAIL_USERNAME=${MAIL_USERNAME:-}" >> /var/www/.env
    fi

    if grep -q "^MAIL_PASSWORD=" /var/www/.env; then
        sed -i "s/^MAIL_PASSWORD=.*/MAIL_PASSWORD=${MAIL_PASSWORD:-}/" /var/www/.env
    else
        echo "MAIL_PASSWORD=${MAIL_PASSWORD:-}" >> /var/www/.env
    fi

    if grep -q "^MAIL_ENCRYPTION=" /var/www/.env; then
        sed -i "s/^MAIL_ENCRYPTION=.*/MAIL_ENCRYPTION=${MAIL_ENCRYPTION:-tls}/" /var/www/.env
    else
        echo "MAIL_ENCRYPTION=${MAIL_ENCRYPTION:-tls}" >> /var/www/.env
    fi

    if grep -q "^MAIL_FROM_ADDRESS=" /var/www/.env; then
        sed -i "s/^MAIL_FROM_ADDRESS=.*/MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS:-$MAIL_USERNAME}/" /var/www/.env
    else
        echo "MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS:-$MAIL_USERNAME}" >> /var/www/.env
    fi

    if grep -q "^MAIL_FROM_NAME=" /var/www/.env; then
        sed -i "s/^MAIL_FROM_NAME=.*/MAIL_FROM_NAME=\"${MAIL_FROM_NAME:-D'Kost}\"/" /var/www/.env
    else
        echo "MAIL_FROM_NAME=\"${MAIL_FROM_NAME:-D'Kost}\"" >> /var/www/.env
    fi

    if grep -q "^CACHE_STORE=" /var/www/.env; then
        sed -i "s/^CACHE_STORE=.*/CACHE_STORE=${CACHE_STORE:-file}/" /var/www/.env
    else
        echo "CACHE_STORE=${CACHE_STORE:-file}" >> /var/www/.env
    fi

    if grep -q "^CACHE_DRIVER=" /var/www/.env; then
        sed -i "s/^CACHE_DRIVER=.*/CACHE_DRIVER=${CACHE_DRIVER:-file}/" /var/www/.env
    else
        echo "CACHE_DRIVER=${CACHE_DRIVER:-file}" >> /var/www/.env
    fi

    if [ -n "$APP_KEY" ]; then
        if grep -q "^APP_KEY=" /var/www/.env; then
            sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" /var/www/.env
        else
            echo "APP_KEY=${APP_KEY}" >> /var/www/.env
        fi
    fi
    
    if grep -q "^SESSION_DRIVER=" /var/www/.env; then
        sed -i "s/^SESSION_DRIVER=.*/SESSION_DRIVER=${SESSION_DRIVER:-file}/" /var/www/.env
    else
        echo "SESSION_DRIVER=${SESSION_DRIVER:-file}" >> /var/www/.env
    fi

    if grep -q "^SESSION_DOMAIN=" /var/www/.env; then
        sed -i "s/^SESSION_DOMAIN=.*/SESSION_DOMAIN=${SESSION_DOMAIN:-}/" /var/www/.env
    else
        echo "SESSION_DOMAIN=${SESSION_DOMAIN:-}" >> /var/www/.env
    fi

    if grep -q "^SESSION_SECURE_COOKIE=" /var/www/.env; then
        sed -i "s/^SESSION_SECURE_COOKIE=.*/SESSION_SECURE_COOKIE=${SESSION_SECURE_COOKIE:-false}/" /var/www/.env
    else
        echo "SESSION_SECURE_COOKIE=${SESSION_SECURE_COOKIE:-false}" >> /var/www/.env
    fi
fi

# Install vendor jika belum ada
if [ ! -d /var/www/vendor ]; then
    echo "Installing composer dependencies..."
    cd /var/www && composer install --no-interaction --optimize-autoloader --no-dev
fi

# Buat storage link jika belum ada
if [ ! -d /var/www/public/storage ]; then
    echo "Creating storage link..."
    cd /var/www && php artisan storage:link
fi

# Pastikan folder cache Laravel tersedia
mkdir -p /var/www/storage/framework/cache/data
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/bootstrap/cache

# Fix permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Tunggu database siap
echo "Waiting for database..."

until php -r "
    try {
        \$host = getenv('DB_HOST') ?: 'mysql-service';
        \$port = getenv('DB_PORT') ?: '3306';
        \$database = getenv('DB_DATABASE') ?: 'webdkost';
        \$username = getenv('DB_USERNAME') ?: 'root';
        \$password = getenv('DB_PASSWORD') ?: 'root';

        new PDO(
            'mysql:host=' . \$host . ';port=' . \$port . ';dbname=' . \$database,
            \$username,
            \$password
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
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# Jalankan scheduler
php artisan schedule:work &

exec "$@"