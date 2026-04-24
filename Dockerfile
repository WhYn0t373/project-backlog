# Use the official PHP image with FPM
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Set PHP upload limits
ENV PHP_UPLOAD_MAX_FILESIZE 50M
ENV PHP_POST_MAX_SIZE 50M
ENV PHP_MAX_FILE_UPLOADS 20

# Set timezone
ENV TZ=UTC

# Copy application
COPY . .

# Install dependencies
RUN composer install --prefer-dist --no-interaction --no-progress && \
    npm install && npm run prod

# Generate key if not set
RUN php artisan key:generate

# Expose port 9000
EXPOSE 9000

# Default command
CMD ["php-fpm"]