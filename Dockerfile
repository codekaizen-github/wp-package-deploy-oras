# Dockerfile for WordPress Package Registry ORAS Package Deploy
ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-cli AS dependencies
ARG ORAS_VERSION=1.2.2
ARG ARCH=amd64
ENV PHP_MEMORY_LIMIT=512M

USER root

# Install unzip, git, curl, composer, and oras
RUN apt-get update && apt-get install -y curl unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create app directory
WORKDIR /wp-package-deploy-oras

# Install additional dependencies and oras
RUN apt-get install -y zip libzip-dev \
    && docker-php-ext-install zip \
    && curl -LO https://github.com/oras-project/oras/releases/download/v${ORAS_VERSION}/oras_${ORAS_VERSION}_linux_${ARCH}.tar.gz \
    && tar -xzf oras_${ORAS_VERSION}_linux_${ARCH}.tar.gz oras \
    && mv oras /usr/local/bin/oras \
    && rm oras_${ORAS_VERSION}_linux_${ARCH}.tar.gz

FROM dependencies AS dev-dependencies

USER root

# Install development dependencies
RUN apt-get update && apt-get install -y git jq \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

FROM dev-dependencies AS dev

FROM dev-dependencies AS test

FROM dependencies AS final

COPY composer.json composer.lock ./
# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy files
COPY . ./

# Set working directory to /package - this is where the package files will be mounted
WORKDIR /package

ENTRYPOINT ["/usr/bin/env", "bash", "/wp-package-deploy-oras/entrypoint.sh"]
