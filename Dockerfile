# Dockerfile for WordPress Plugin Registry ORAS Plugin Deploy
FROM php:8.2-cli AS base
ARG ORAS_VERSION=1.2.2
ARG ARCH=amd64
ENV PHP_MEMORY_LIMIT=512M

# Install unzip, git, curl, composer, and oras
RUN apt-get update \
    && apt-get install -y unzip git curl libzip-dev jq \
    && docker-php-ext-install zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && curl -LO https://github.com/oras-project/oras/releases/download/v${ORAS_VERSION}/oras_${ORAS_VERSION}_linux_${ARCH}.tar.gz \
    && tar -xzf oras_${ORAS_VERSION}_linux_${ARCH}.tar.gz oras \
    && mv oras /usr/local/bin/oras \
    && rm oras_${ORAS_VERSION}_linux_${ARCH}.tar.gz


# Create app directory
WORKDIR /wordpress-plugin-registry-oras-plugin-deploy

COPY composer.json composer.lock ./
# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy entrypoint script and src directory
COPY src ./src
COPY entrypoint.sh ./entrypoint.sh
RUN chmod +x ./entrypoint.sh

ENTRYPOINT ["/wordpress-plugin-registry-oras-plugin-deploy/entrypoint.sh"]
