# Use the official PHP-FPM image as the base image
FROM php:8-fpm

# Use an official PHP image as the base image
#FROM php:8.0-cli

# Set working directory inside the container
WORKDIR /var/www/html

# Install required system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    zip \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the composer.json and composer.lock files to the container
COPY composer.json composer.lock ./

# Install project dependencies using Composer
RUN composer install --no-scripts

# Copy the rest of your application's source code to the container
COPY . .

# Start your application
CMD ["php", "index.php"]
