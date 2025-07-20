# Dockerfile for WordPress Plugin Registry ORAS Plugin Deploy
FROM php:8.2-cli

# Install unzip, git, curl, composer, and oras
RUN apt-get update \
    && apt-get install -y unzip git curl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && curl -LO https://github.com/oras-project/oras/releases/download/v1.1.0/oras_1.1.0_linux_amd64.tar.gz \
    && tar -xzf oras_1.1.0_linux_amd64.tar.gz oras \
    && mv oras /usr/local/bin/oras \
    && rm oras_1.1.0_linux_amd64.tar.gz

# Create app directory
WORKDIR /app

# Copy entrypoint script
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Install wp-package-parser
RUN composer require renventura/wp-package-parser

ENTRYPOINT ["/entrypoint.sh"]
