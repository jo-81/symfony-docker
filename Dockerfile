FROM php:8.4-fpm AS base

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq-dev \
        libicu-dev \
        libzip-dev \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        intl \
        zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-enable opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html


FROM base AS dev

COPY . .

RUN composer install \
    --no-interaction \
    --prefer-dist


FROM base AS prod

COPY . .

RUN composer install \
    --no-interaction \
    --prefer-dist \
    --no-dev \
    --optimize-autoloader \
    --no-scripts

RUN mkdir -p var/cache var/log

RUN APP_ENV=prod php bin/console cache:clear

RUN chown -R www-data:www-data var