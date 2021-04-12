FROM php:7.4-apache

RUN apt-get update && \
    apt-get upgrade -y && \ 
    apt-get install -y --no-install-recommends \
    apt-utils \
    nano \
    libzip-dev \
    libpng-dev \
    zlib1g-dev \
    build-essential \
    libssl-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libicu-dev \
    libxml2-dev \
    libwebp-dev \
    zip && \
    apt-get update && \
    apt-get upgrade -y 

RUN docker-php-ext-install zip gd mysqli pdo pdo_mysql

# Clear cache
RUN apt-get clean

# # Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer