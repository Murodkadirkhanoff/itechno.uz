pipeline {
    agent any

    environment {
        PROJECT_NAME = "itechno"
        PROJECT_BUILD_ZIP = "staging.itechno-build.tar.gz"
        BUILD_NUMBER = sh(returnStdout: true, script: 'date "+%Y%m%d%H%M%S"').trim()
    }

    stages {
        stage('Prepare') {
            steps {
                script {
                    // Create necessary directories
                    sh 'mkdir -p "/var/www/$PROJECT_NAME/staging/{releases,deploy,current}"'

                    // Testing Stage
                    sh '''
                    pwd
                    ls -a
                    composer install
                    cp .env.example .env
                    php artisan key:generate
                    #php artisan migrate:refresh --seed
                    ./vendor/bin/phpunit
                    truncate -s 0 storage/logs/laravel.log
                    '''
                }
            }
        }

        stage('Deploy') {
            steps {
                script {
                    sh '''
                    pwd
                    cd ..
                    tar -czvf "$PROJECT_BUILD_ZIP" "$PROJECT_NAME/" --exclude="$PROJECT_NAME/*.git*" "$PROJECT_NAME/"
                    
                    ls /var/www/$PROJECT_NAME/staging/releases/
                    ls /var/www/$PROJECT_NAME/staging/
                    ls /var/www/$PROJECT_NAME/
                    mv "$PROJECT_BUILD_ZIP" "/var/www/$PROJECT_NAME/staging/releases/"

                    cd "/var/www/$PROJECT_NAME/staging/releases/"
                    tar -xzvf "$PROJECT_BUILD_ZIP"
                    mv "$PROJECT_NAME" "${PROJECT_NAME}${BUILD_NUMBER}"

                    cd "/var/www/$PROJECT_NAME/staging/releases/${PROJECT_NAME}${BUILD_NUMBER}"
                    composer install

                    cd ..
                    rm -rf "$PROJECT_NAME"
                    rm -rf "/var/www/$PROJECT_NAME/staging/current"
                    ln -s "/var/www/$PROJECT_NAME/staging/releases/${PROJECT_NAME}${BUILD_NUMBER}/" "/var/www/$PROJECT_NAME/staging/current"
                    sudo systemctl restart nginx
                    sudo systemctl restart php8.2-fpm

                    cd ..
                    cp deploy/.env current/.env
                    cd current
                    ls -l
                    php artisan cache:clear
                    php artisan view:clear
                    chmod -R 775 storage bootstrap/cache
                    '''
                }
            }
        }
    }
}
