FROM composer:2 AS composer

FROM php:8.4-cli-bookworm AS app

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libonig-dev libsqlite3-dev libzip-dev \
    && docker-php-ext-install -j"$(nproc)" mbstring pcntl pdo_sqlite zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

RUN useradd --uid 1000 --create-home app \
    && mkdir -p /var/www/html/vendor /var/lib/laravel \
    && chown -R app:app /var/www/html /var/lib/laravel

WORKDIR /var/www/html
USER app

FROM node:24-bookworm-slim AS frontend

RUN mkdir -p /var/www/html/node_modules \
    && chown -R node:node /var/www/html

WORKDIR /var/www/html
USER node
