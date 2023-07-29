FROM php:7.3-fpm-alpine

# mysqli, pdo, pdo_mysql, pdo_pgsql
RUN apk add --update --no-cache postgresql-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql pdo_pgsql

# mcrypt, gd, iconv
RUN apk add --update --no-cache \
        freetype-dev \
        libjpeg-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j"$(getconf _NPROCESSORS_ONLN)" gd

# gmp
RUN apk add --update --no-cache gmp gmp-dev \
    && docker-php-ext-install gmp

# imagick
# RUN apk add --update --no-cache autoconf g++ imagemagick-dev libtool make pcre-dev \
#     && pecl install imagick \
#     && docker-php-ext-enable imagick \
#     && apk del autoconf g++ libtool make pcre-dev

# install bcmath extension
RUN docker-php-ext-install bcmath

#zip
RUN apk add --update --no-cache \
        libzip-dev \
        zip \
  && docker-php-ext-configure zip --with-libzip \
  && docker-php-ext-install zip