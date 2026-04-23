# Dockerfile for the application

FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    libonig-dev \
    libicu-dev \
    zlib1g-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif \
    && docker-php-ext-install intl zip

# Install Composer
COPY --from=composer:2.2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# Copy application code
COPY . .

# Install application dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Expose metrics port
EXPOSE 9112

# Set default command
CMD ["php-fpm"]