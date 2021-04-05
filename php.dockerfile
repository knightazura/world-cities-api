FROM php:8.0.3-fpm-alpine

ADD ./php/www.conf /usr/local/etc/php-fpm.d/www.conf

ADD ./php/php.ini /usr/local/etc/php/php.ini

RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

RUN mkdir -p /var/www/html

RUN chown laravel:laravel /var/www/html

WORKDIR /var/www/html

# REDIS
RUN apk add autoconf git g++ make

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && pear config-set php_ini /usr/local/etc/php/php.ini

RUN docker-php-ext-install pdo pdo_mysql
