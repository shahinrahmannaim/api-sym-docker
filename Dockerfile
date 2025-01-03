FROM composer:2.8.4 as composer_build

WORKDIR /app
COPY . /app
RUN composer install --optimize-autoloader --no-dev --ignore-platform-reqs --no-interaction --no-scripts --prefer-dist \
    && composer require annotations
USER root
RUN apt-get update && apt-get install -y docker.io

# Use the official PHP 8.3 FPM image based on Alpine Linux
FROM php:8.3-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk --no-cache add \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    bash \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd opcache mysqli pdo pdo_mysql

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory inside the container
WORKDIR /var/www/html

# Copy the application files from the builder
COPY --from=composer_build /app /var/www/html

# Set the appropriate permissions
RUN chown -R www-data:www-data /var/www/html

# Clear the cache manually
RUN rm -rf var/cache/*

# Expose the port Symfony will run on
EXPOSE 80

# Create necessary directories for Symfony (cache, logs, etc.)
RUN mkdir -p /var/www/html/var/cache /var/www/html/var/logs /var/www/html/var/sessions \
    && chown -R www-data:www-data /var/www/html/var

# Add entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]

# Use the PHP-FPM server to serve the application
CMD ["php-fpm"]
