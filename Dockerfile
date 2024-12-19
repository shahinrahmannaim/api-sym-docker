# Stage 1: Composer Build
FROM composer:2.8.4 AS composer_build

# Set the working directory
WORKDIR /app

# Copy the source code into the container
COPY . /app

# Install Composer dependencies
RUN composer install --optimize-autoloader --no-dev --ignore-platform-reqs --no-interaction --no-scripts --prefer-dist

# Ensure the annotations package is installed (if required)
RUN composer require annotations

# Stage 2: PHP-Apache Server
FROM php:8.1.8-apache

# Set the application home environment variable
ENV APP_HOME /var/www/html

# Copy the built application from the previous stage
COPY --from=composer_build /app/ /var/www/html/

# Update the Apache configuration
RUN sed -i -e "s/html/html\/public/g" /etc/apache2/sites-enabled/000-default.conf \
    && usermod -u 1000 www-data && groupmod -g 1000 www-data \
    && chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite \
    && sed -i "s/80/\${PORT}/g" /etc/apache2/sites-enabled/000-default.conf /etc/apache2/ports.conf

# Clear Symfony cache manually for production
RUN php bin/console cache:clear --env=prod --no-debug

# Set the entrypoint to be empty
ENTRYPOINT []

# Run Apache server in the foreground
CMD ["docker-php-entrypoint", "apache2-foreground"]
