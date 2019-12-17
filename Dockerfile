FROM php:7.3

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . /app

RUN apt-get update \
    && apt-get install -y --no-install-recommends git zip unzip libicu-dev \
    && docker-php-ext-install pdo_mysql mysqli intl \
    && mkdir -p ~/app

WORKDIR /app