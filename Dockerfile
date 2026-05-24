FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        zip \
        gd

# Enable Apache modules
RUN a2enmod rewrite

# Fix Apache MPM conflict
RUN a2dismod mpm_event || true
RUN a2enmod mpm_prefork || true

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Allow Composer as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set Laravel permissions
RUN chmod -R 775 storage bootstrap/cache

# Set Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Update Apache config
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# Clear Laravel cache safely
RUN php artisan config:clear || true
RUN php artisan cache:clear || true
RUN php artisan route:clear || true
RUN php artisan view:clear || true

EXPOSE 80

CMD ["apache2-foreground"]