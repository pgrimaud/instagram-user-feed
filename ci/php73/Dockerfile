FROM php:7.3

RUN apt-get update && apt-get install -y libc-client-dev libkrb5-dev zip git && rm -r /var/lib/apt/lists/*

RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
