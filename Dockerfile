FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    zip \
    exif \
    bcmath \
    intl \
    pcntl \
    gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files first (Docker cache)
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

# Copy project
COPY . .

# Finish composer
RUN composer dump-autoload --optimize

# Permissions
RUN mkdir -p storage/framework/{cache,sessions,views} \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Laravel optimizations
RUN php artisan storage:link || true

# Nginx config
COPY nginx.conf /etc/nginx/sites-available/default

# Startup script
RUN printf '#!/bin/bash\n\
set -e\n\
\n\
php artisan key:generate --force || true\n\
php artisan migrate --force || true\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
php artisan event:cache || true\n\
\n\
php-fpm -D\n\
nginx -g "daemon off;"\n' > /start.sh

RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]