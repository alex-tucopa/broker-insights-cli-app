FROM php:8.2-cli

COPY . /usr/src/brokerapp
WORKDIR /usr/src/brokerapp

RUN apt-get update \
    && apt-get install -y zip

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install
RUN composer dump-autoload

RUN touch database/app.sqlite

RUN php command app:create-database-tables

RUN mv .env.demo .env

CMD ["php", "-a"]
