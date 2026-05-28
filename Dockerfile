FROM dunglas/frankenphp:latest-php8.3

RUN install-php-extensions pdo_pgsql mbstring curl zip

WORKDIR /app
COPY . .

RUN curl -sS https://getcomposer.org/installer | php && \
    COMPOSER_ALLOW_SUPERUSER=1 php composer.phar install --no-dev --optimize-autoloader

EXPOSE 8080
ENV SERVER_NAME=":8080"
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
