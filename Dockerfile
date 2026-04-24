FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
        libpng-dev \
        libjpeg-turbo-dev \
        libwebp-dev \
        libxpm-dev \
        oniguruma-dev \
        zip \
        unzip \
        git \
        nodejs npm

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-progress --prefer-dist

# Install JS dependencies (for asset building & Playwright)
COPY package.json package-lock.json ./
RUN npm install

# Copy application
COPY . .

# Build frontend assets
RUN npm run dev

EXPOSE 9000
CMD ["php-fpm"]