# Use the official PHP image with the required extensions
FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    cron \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql \
    && pecl install redis \
    && docker-php-ext-enable redis

# Set the working directory
WORKDIR /var/www

# Copy the existing application directory
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Laravel dependencies
RUN composer install --no-interaction --prefer-dist

# Copy the crontab file
COPY crontab /etc/cron.d/laravel-scheduler

# Give execution rights
RUN chmod 0644 /etc/cron.d/laravel-scheduler

# Apply cron job
RUN crontab /etc/cron.d/laravel-scheduler

# Create the log file for cron
RUN touch /var/log/cron.log

# Expose the port your app runs on
EXPOSE 9000

# Start both cron and PHP-FPM
CMD ["sh", "-c", "cron && php-fpm"]
