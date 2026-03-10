node {
    checkout scm

    // deploy env dev
    stage("Build"){
        docker.image('php:8.2-cli').inside('-u root') {
            sh 'apt-get update && apt-get install -y libzip-dev libpng-dev'
            sh 'docker-php-ext-install gd zip bcmath'
            sh 'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer'
            sh 'rm -f composer.lock'
            sh 'composer install'
        }
    }

    // Testing
    stage("Testing"){
        docker.image('ubuntu').inside('-u root') {
            sh 'echo "Ini adalah test"'
        }
    }
}
