FROM php:8.3-fpm-alpine
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN docker-php-ext-install opcache
ADD opcache.ini $PHP_INI_DIR/conf.d/
FROM php:8.1-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www

# Other Dockerfile instructions...
