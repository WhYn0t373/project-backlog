# Dockerfile
# Base image
FROM php:8.3-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
        git \
        unzip \
        libpng-dev \
        libjpeg-turbo-dev \
        libwebp-dev \
        libxpm-dev \
        freetype-dev \
        libxml2-dev \
        oniguruma-dev \
        libzip-dev \
        curl \
        bash \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring xml zip gd exif curl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-progress --no-scripts

# Expose FPM port
EXPOSE 9000

# Default command
CMD ["php-fpm"]