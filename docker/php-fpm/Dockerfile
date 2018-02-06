# BEGINNING OF THE COMMON PART

FROM php:7.0-fpm

RUN curl -sL https://deb.nodesource.com/setup_6.x | bash - \
    && apt-get update \
    && apt-get install -y \
        git \
        libicu-dev \
        zlib1g-dev \
        libxrender1 \
        libfontconfig \
        curl \
        wget \
        zip \
        libcurl4-gnutls-dev \
        libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer \
    && composer global require "hirak/prestissimo:^0.3" \
    && curl -L -o /usr/local/bin/docker-php-pecl-install https://raw.githubusercontent.com/helderco/docker-php/master/template/bin/docker-php-pecl-install \
    && chmod +x /usr/local/bin/docker-php-pecl-install

RUN docker-php-pecl-install APCu-5.1.5 \
    && docker-php-ext-enable apcu

RUN docker-php-ext-install -j$(nproc) \
    curl \
    mbstring \
    pdo \
    pdo_mysql \
    bcmath \
    zip

# END OF THE COMMON PART
RUN apt-get update && apt-get install -y \
            libfreetype6-dev \
            libjpeg62-turbo-dev \
            libmcrypt-dev \
            libpng12-dev \
    && docker-php-ext-install -j$(nproc) iconv mcrypt \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install -j$(nproc) \
    intl \
    mysqli

RUN usermod -u 1000 www-data
RUN chown www-data:www-data -R /var/www

USER www-data

WORKDIR /var/www/
