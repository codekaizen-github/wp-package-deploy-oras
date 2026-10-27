# Dockerfile for WordPress Package Registry ORAS Package Deploy
# PHP 8.2+ use official Hub images. PHP 8.1 uses packages.sury.org (Ondřej Surý)
ARG PHP_VERSION=8.4

FROM php:8.2-cli AS php-8.2
FROM php:8.3-cli AS php-8.3
FROM php:8.4-cli AS php-8.4

FROM debian:bookworm-slim AS php-8.1
RUN set -eux; \
	apt-get update; \
	apt-get install -y --no-install-recommends ca-certificates curl gnupg lsb-release; \
	curl -fsSL https://packages.sury.org/php/apt.gpg -o /usr/share/keyrings/deb.sury.org-php.gpg; \
	echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" \
		> /etc/apt/sources.list.d/php-sury.list; \
	apt-get update; \
	apt-get install -y --no-install-recommends \
		php8.1-cli php8.1-curl php8.1-mbstring php8.1-xml php8.1-zip; \
	rm -rf /var/lib/apt/lists/*

ARG PHP_VERSION
FROM php-${PHP_VERSION} AS dependencies
ARG ORAS_VERSION=1.2.2
ARG ARCH=amd64
ENV PHP_MEMORY_LIMIT=512M

USER root

RUN apt-get update && apt-get install -y curl unzip zip \
	&& curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
	&& if command -v docker-php-ext-install >/dev/null; then \
		apt-get install -y libzip-dev && docker-php-ext-install zip; \
	fi \
	&& curl -LO https://github.com/oras-project/oras/releases/download/v${ORAS_VERSION}/oras_${ORAS_VERSION}_linux_${ARCH}.tar.gz \
	&& tar -xzf oras_${ORAS_VERSION}_linux_${ARCH}.tar.gz oras \
	&& mv oras /usr/local/bin/oras \
	&& rm oras_${ORAS_VERSION}_linux_${ARCH}.tar.gz \
	&& rm -rf /var/lib/apt/lists/*

WORKDIR /wp-package-deploy-oras

FROM dependencies AS dev-dependencies

USER root

COPY xdebug.ini /tmp/xdebug.ini
RUN set -eux; \
	apt-get update; \
	apt-get install -y git jq; \
	if command -v docker-php-ext-install >/dev/null; then \
		pecl install xdebug; \
		docker-php-ext-enable xdebug; \
	else \
		apt-get install -y --no-install-recommends php8.1-xdebug; \
	fi; \
	conf_dir="$(php --ini | awk -F': *' '/Scan for additional/{print $2}')"; \
	mkdir -p "${conf_dir}"; \
	cp /tmp/xdebug.ini "${conf_dir}/99-xdebug-settings.ini"; \
	rm -f /tmp/xdebug.ini; \
	rm -rf /var/lib/apt/lists/*

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
