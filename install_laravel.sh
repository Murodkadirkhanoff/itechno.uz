#!/bin/bash

# Build and start Docker containers
docker-compose up -d --build

# Check if Laravel is already installed
if [ ! -f "/var/www/.env" ]; then
    echo "Laravel not installed. Proceeding with installation..."

    # Run Composer install to set up Laravel
    docker-compose exec app composer create-project --prefer-dist laravel/laravel .

    # Set permissions for the Laravel directory
    docker-compose exec app chown -R www-data:www-data /var/www

    # Generate Laravel key
    docker-compose exec app php artisan key:generate

    # Run migrations
    docker-compose exec app php artisan migrate

    echo "Laravel installation complete."
else
    echo "Laravel is already installed."
fi