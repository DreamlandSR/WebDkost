node {
    checkout scm

    stage("Build") {
        docker.image('php:8.2-cli').inside('-u root') {
            sh '''
                apt-get update -qq && apt-get install -y -qq \
                    libzip-dev libpng-dev libonig-dev libxml2-dev libsqlite3-dev sqlite3
                docker-php-ext-install pdo_mysql pdo_sqlite mbstring gd zip bcmath
                curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
                composer install --no-interaction --prefer-dist --optimize-autoloader
            '''
        }
    }

    stage("Build Frontend") {
        docker.image('node:18').inside('-u root') {
            sh '''
                npm install
                npm run build
            '''
        }
    }

    stage("Testing") {
        docker.image('php:8.2-cli').inside('-u root') {
            sh '''
                apt-get update -qq && apt-get install -y -qq \
                    libzip-dev libpng-dev libonig-dev libxml2-dev libsqlite3-dev sqlite3
                docker-php-ext-install pdo_mysql pdo_sqlite mbstring gd zip bcmath
                curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
                composer install --no-interaction --prefer-dist
                cp .env.example .env
                php artisan key:generate
                php artisan test
            '''
        }
    }

    stage("Deploy") {
        def branch = env.GIT_BRANCH ?: env.BRANCH_NAME ?: ''
        echo "Current branch: ${branch}"

        def projectDir = '/var/www/html/WebDkost'

        if (branch.contains('main') || branch.contains('develop')) {
            sh """
                cd ${projectDir}

                # Pastikan .env ada
                if [ ! -f .env ]; then
                    cp .env.example .env
                fi

                # Stop & rebuild image dengan file terbaru
                docker compose down
                docker compose build --no-cache
                docker compose up -d

                # Jalankan migrate setelah container up
                sleep 5
                docker exec laravel_app php artisan migrate --force
                docker exec laravel_app php artisan config:cache
                docker exec laravel_app php artisan route:cache
            """
            echo "Deploy berhasil ke branch: ${branch}"
        } else {
            echo "Branch ${branch} - skip deploy"
        }
    }

    stage("Deploy Prod") {
        echo "Skip - production server belum dikonfigurasi"
    }
}
