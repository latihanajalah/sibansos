# ==========================================
# Stage 1 - Composer
# ==========================================
FROM composer:2 AS composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-scripts

COPY . .

RUN composer dump-autoload --optimize


# ==========================================
# Stage 2 - Node / Vite
# ==========================================
FROM node:22 AS node

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

RUN npm run build


# ==========================================
# Stage 3 - Production
# ==========================================
FROM php:8.4-fpm

# Install packages
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    unzip \
    zip \
    curl \
    supervisor \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP Extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    bcmath \
    exif \
    intl \
    pcntl \
    zip \
    gd

WORKDIR /var/www

# Copy application
COPY . .

# Copy vendor dari composer stage
COPY --from=composer /app/vendor ./vendor

# Copy Vite build
COPY --from=node /app/public/build ./public/build

# Permissions
RUN mkdir -p storage/framework/cache \
    storage/framework/views \
    storage/framework/sessions \
    storage/logs \
    bootstrap/cache

RUN chown -R www-data:www-data storage bootstrap/cache

RUN chmod -R 775 storage bootstrap/cache

# Laravel optimize
RUN php artisan package:discover --ansi

RUN php artisan storage:link || true

COPY nginx.conf /etc/nginx/sites-available/default

COPY start.sh /start.sh

RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]
