# syntax=docker/dockerfile:1

# Stage 1: Build Composer dependencies
FROM composer:2 AS composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Stage 2: Build final image
FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

COPY --from=composer /app /var/www/html

RUN apk add --no-cache \
        icu-dev \
        libzip-dev \
        oniguruma-dev \
        zip \
        unzip \
        git \
        curl \
        nginx \
    && docker-php-ext-install pdo_mysql zip intl opcache

# Copy Nginx configuration
COPY nginx.conf /etc/nginx/conf.d/default.conf

RUN rm -f /var/log/nginx/*

EXPOSE 80

CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]