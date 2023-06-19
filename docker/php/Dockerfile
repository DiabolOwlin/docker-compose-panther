FROM php:8.1-fpm-alpine3.14

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

# Set COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy the application code
COPY . /app
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install symfony/panther with version constraint and dependencies
RUN composer req symfony/panther:2.1
RUN composer update


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

VOLUME ["/app"]
