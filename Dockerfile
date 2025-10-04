# Dockerfile for WordPress Package Registry ORAS Package Deploy
ARG PHP_VERSION=8.1
FROM php:${PHP_VERSION}-cli AS dependencies
ARG ORAS_VERSION=1.2.2
ARG ARCH=amd64
ENV PHP_MEMORY_LIMIT=512M

# Create app directory
WORKDIR /wp-package-deploy-oras

# Install unzip, git, curl, composer, and oras
RUN apt-get update \
    && apt-get install -y zip unzip git curl libzip-dev jq \
    && docker-php-ext-install zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && curl -LO https://github.com/oras-project/oras/releases/download/v${ORAS_VERSION}/oras_${ORAS_VERSION}_linux_${ARCH}.tar.gz \
    && tar -xzf oras_${ORAS_VERSION}_linux_${ARCH}.tar.gz oras \
    && mv oras /usr/local/bin/oras \
    && rm oras_${ORAS_VERSION}_linux_${ARCH}.tar.gz

FROM dependencies AS dev

FROM dependencies AS test

FROM dependencies AS final

COPY composer.json composer.lock ./
# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy files
COPY . ./

# Set working directory to /package - this is where the package files will be mounted
WORKDIR /package

ENTRYPOINT ["/usr/bin/env", "bash", "/wp-package-deploy-oras/entrypoint.sh"]
