FROM php:7.4-fpm

MAINTAINER admarkov <admarkov@admarkov.com>

RUN apt-get update \
    && apt-get install -y \
	libpq-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
     && docker-php-ext-configure gd \
     && docker-php-ext-install gd \
     && docker-php-ext-install pgsql pdo_pgsql

COPY chess.pool.conf /usr/local/etc/

RUN usermod -u 1000 www-data

EXPOSE 9000
CMD ["php-fpm", "-F"]

