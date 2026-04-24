# Use official PHP FPM image
FROM php:8.2-fpm

# Install system dependencies and MySQL client
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-interaction --no-dev --prefer-dist

# Start PHP-FPM server
CMD ["php-fpm"]