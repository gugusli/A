FROM php:8.3-apache

RUN apt-get update && apt-get install -y libpq-dev curl zip unzip \
    && docker-php-ext-install pdo pdo_pgsql \
    && a2enmod rewrite

WORKDIR /var/www/html
COPY . .

RUN curl -sS https://getcomposer.org/installer | php && \
    COMPOSER_ALLOW_SUPERUSER=1 php composer.phar install --no-dev --optimize-autoloader

EXPOSE 80
