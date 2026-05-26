FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    nodejs \
    npm

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install pdo pdo_mysql zip gd

# FIX APACHE MPM CONFLICT
RUN a2dismod mpm_event
RUN a2dismod mpm_worker || true
RUN a2enmod mpm_prefork

# Enable rewrite
RUN a2enmod rewrite

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Workdir
WORKDIR /var/www/html

# Copy project
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Install npm dependencies
RUN npm install

# Build frontend
RUN npm run build

# Laravel cache clear
RUN php artisan config:clear || true
RUN php artisan cache:clear || true
RUN php artisan route:clear || true
RUN php artisan view:clear || true

# Permission
RUN chmod -R 775 storage bootstrap/cache

# Apache public folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf

EXPOSE 80