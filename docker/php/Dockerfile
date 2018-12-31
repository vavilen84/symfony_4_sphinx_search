FROM php:7.1-fpm-jessie

ENV PHPREDIS_VERSION 3.1.6


# Mcrypt
RUN apt-get update && apt-get install -y libmcrypt-dev && docker-php-ext-install -j$(nproc) mcrypt

# GD
RUN apt-get update && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev
RUN docker-php-ext-configure gd  \
    --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

# PDO
RUN docker-php-ext-install -j$(nproc) pdo

# PDO Mysql
RUN docker-php-ext-install -j$(nproc) pdo_mysql

# Postgres PDO
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo pdo_pgsql

# Redis
RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis

# INTL
RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++ \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl

# OPCACHE
RUN docker-php-ext-install -j$(nproc) opcache

# APCu
RUN pecl install apcu && docker-php-ext-enable apcu

#composer
RUN apt-get update && \
    apt-get install -y --no-install-recommends git zip unzip

#RUN curl --silent --show-error https://getcomposer.org/installer | php
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini.tmp
RUN cat /usr/local/etc/php/conf.d/xdebug.ini.tmp >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN rm /usr/local/etc/php/conf.d/xdebug.ini.tmp

#cron
RUN apt-get install -y cron
ADD crontab /etc/crontab
RUN chmod 0644 /etc/crontab
RUN touch /var/log/cron.log

EXPOSE 9000

ADD entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

CMD /entrypoint.sh