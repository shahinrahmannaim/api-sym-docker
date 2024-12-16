# Use the official PHP 8.3 FPM image based on Alpine Linux for a smaller image size
FROM php:8.3-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk --no-cache add \
    libpng-dev \
    libjpeg-turbo-dev \
    libfreetype6-dev \
    bash \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd opcache mysqli pdo pdo_mysql

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory inside the container
WORKDIR /var/www/html

# Copy the application files into the container
COPY . /var/www/html/

# Set the appropriate permissions
RUN chown -R www-data:www-data /var/www/html

# Install Composer dependencies (without dev dependencies and optimized for production)
RUN composer install --no-dev --optimize-autoloader

# Expose the port that Symfony will run on
EXPOSE 80

# Create the necessary directories for Symfony (cache, logs, etc.)
RUN mkdir -p /var/www/html/var/cache /var/www/html/var/logs /var/www/html/var/sessions \
    && chown -R www-data:www-data /var/www/html/var

# Use the PHP-FPM server to serve the application
CMD ["php-fpm"]

