FROM php:8.2

RUN apt-get update && apt-get install -y unzip libzip-dev
RUN docker-php-ext-install pdo pdo_mysql zip
RUN COMPOSER_ALLOW_SUPERUSER=1 curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

WORKDIR /var/www/html
COPY . .

RUN composer install --prefer-dist --no-plugins

CMD ["php", "artisan", "serve", "--host=0.0.0.0"]
