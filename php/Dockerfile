FROM php:8.1-fpm-alpine as app


ENV RUN_DEPS \
    zlib \
    libzip \
    libpng \
    libjpeg-turbo \

ENV BUILD_DEPS \
    zlib-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \

ENV PHP_EXTENSIONS \
    opcache \
    zip \
    gd \
    bcmath \

RUN apk add --no-cache --virtual .build-deps $BUILD_DEPS \
    && docker-php-ext-install -j "$(nproc)" $PHP_EXTENSIONS \
    && apk del .build-deps

RUN echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

# Install zip extension
RUN apk add --no-cache libzip-dev \
    && docker-php-ext-install zip

# Useful PHP extension installer image, copy binary into your container
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer /usr/bin/composer /usr/bin/composer
# Copy composer files
COPY ./app/composer.* ./

# Install dependencies
RUN composer install

# Copy application files to the working directory
COPY ./app .

# Run composer dump-autoload --optimize
RUN composer dump-autoload --optimize


ENV PANTHER_NO_SANDBOX 1
# Not mandatory, but recommended
ENV PANTHER_CHROME_ARGUMENTS='--disable-dev-shm-usage'
RUN apk add --no-cache chromium chromium-chromedriver

# Firefox and GeckoDriver (optional)
ARG GECKODRIVER_VERSION=0.28.0
RUN apk add --no-cache firefox libzip-dev; \
    docker-php-ext-install zip
RUN wget -q https://github.com/mozilla/geckodriver/releases/download/v$GECKODRIVER_VERSION/geckodriver-v$GECKODRIVER_VERSION-linux64.tar.gz; \
    tar -zxf geckodriver-v$GECKODRIVER_VERSION-linux64.tar.gz -C /usr/bin; \
    rm geckodriver-v$GECKODRIVER_VERSION-linux64.tar.gz

