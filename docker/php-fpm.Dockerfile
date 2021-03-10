FROM php:7.3.25-fpm-alpine

RUN apk update && apk add curl git wget 1> /dev/null

RUN docker-php-ext-install pdo pdo_mysql mysqli 1> /dev/null

RUN docker-php-ext-install bcmath sockets 1> /dev/null

RUN apk add --update --no-cache --virtual .build-dependencies $PHPIZE_DEPS 1> /dev/null \
        && pecl install apcu 1> /dev/null \
        && docker-php-ext-enable apcu 1> /dev/null \
        && pecl clear-cache 1> /dev/null \
        && apk del .build-dependencies 1> /dev/null

WORKDIR /etc

COPY docker/config/php-fpm/php.ini php.ini.dist

RUN cat /etc/php.ini.dist >> /etc/php.ini

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html

COPY codeigniter/. .

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

RUN php composer-setup.php

RUN php -r "unlink('composer-setup.php');"

RUN php composer.phar install --ignore-platform-reqs

RUN php -r "unlink('composer.phar');"