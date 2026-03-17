node {
    checkout scm

    stage("Build"){
        docker.image('php:8.2-cli').inside('-u root') {
            sh 'apt-get update && apt-get install -y libzip-dev libpng-dev libonig-dev libxml2-dev'
            sh 'docker-php-ext-install pdo_mysql pdo_sqlite mbstring gd zip bcmath'
            sh 'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer'
            sh 'composer install --no-interaction --prefer-dist'
        }
    }

    stage("Build Frontend"){
        docker.image('node:18').inside('-u root') {
            sh 'npm install'
            sh 'npm run build'
        }
    }

    stage("Testing"){
        docker.image('php:8.2-cli').inside('-u root') {
            sh 'apt-get update && apt-get install -y libzip-dev libpng-dev libonig-dev libxml2-dev libsqlite3-dev'
            sh 'docker-php-ext-install pdo_mysql pdo_sqlite mbstring gd zip bcmath'
            sh 'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer'
            sh 'composer install --no-interaction --prefer-dist'
            sh 'cp .env.example .env'
            sh 'php artisan key:generate'
            sh 'php artisan test'
        }
    }

    stage("Deploy"){
        def branch = env.GIT_BRANCH ?: env.BRANCH_NAME
        if (branch == 'origin/main' || branch == 'main') {
            sh 'docker compose down'
            sh 'docker compose build --no-cache'
            sh 'docker compose up -d'
        } else if (branch == 'origin/develop' || branch == 'develop') {
            sh 'docker compose down'
            sh 'docker compose up -d --build'
        } else {
            echo "Branch ${branch} - skip deploy"
        }
    }
}
