FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    cron curl zip unzip git \
    libpq-dev libzip-dev libonig-dev libjpeg-dev libpng-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip bcmath

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug
ENV PHP_IDE_CONFIG "serverName=docker_crypto-api"

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy custom configuration files
COPY ./docker/dev/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/dev/php/php-fpm.ini /usr/local/etc/php-fpm.d/zzz_custom.ini

# Set working directory
WORKDIR /var/www/html

# Copy files from current folder to container current folder (set in workdir).
COPY . .

# Install Laravel dependencies
RUN composer install

# Add cron job for Laravel schedule:run
COPY ./docker/dev/cron/crontab /etc/cron.d/service
RUN chmod 0644 /etc/cron.d/service && crontab /etc/cron.d/service

# Start cron service
CMD ["sh", "-c", "cron && php-fpm"]
