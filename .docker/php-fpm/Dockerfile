FROM php:8.0-fpm-buster as php

RUN apt-get update && apt-get install -y --no-install-recommends \
  g++ \
  curl \
  wget \
  git \
  zip \
  unzip \
  cron \
  apt-utils \
  ca-certificates \
  zlib1g-dev \
  libzip-dev \
  libonig-dev \
  libicu-dev \
  libbz2-dev

RUN docker-php-ext-install pdo pdo_mysql intl bz2 zip mbstring opcache
COPY php.ini "$PHP_INI_DIR/php.ini"

RUN usermod -u 1000 www-data

RUN mkdir /run/php

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

RUN ln -snf /usr/share/zoneinfo/Europe/Zagreb /etc/localtime

WORKDIR /var/www/html/intellifit
EXPOSE 9000