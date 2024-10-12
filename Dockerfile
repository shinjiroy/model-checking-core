FROM php:8.4-rc-cli-bullseye

RUN set -eux && \
    apt-get update && \
    apt-get -y install libzip-dev && \
    docker-php-ext-install zip && \
    apt clean && \
    rm -rf /var/lib/apt/lists/* && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
