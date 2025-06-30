ARG PHP_VERSION=8.3

FROM php:${PHP_VERSION}-fpm

# To use args inside the Docker script it must be set after tne "FROM" section
# @see https://qmacro.org/blog/posts/2024/05/13/using-arg-in-a-dockerfile-beware-the-gotcha/
ARG SYS_USER_UID=1000
ARG SYS_USER_GID=1000
ARG SYS_USER_NAME=www-data
ARG APP_WORK_DIR=/var/www/app
ARG APP_COMPOSER_DIR=/var/www/.composer

# Add labels for better maintainability
LABEL maintainer="Anton Karpov"
LABEL org.opencontainers.image.source="https://github.com/awd-studio/es-lib-maker-bundle"
LABEL org.opencontainers.image.description="PHP-FPM development environment"
LABEL org.opencontainers.image.version="1.0"

WORKDIR ${APP_WORK_DIR}

# Set www-data to have UID 1000 and ensure the shell is bash
RUN usermod -u 1000 ${SYS_USER_NAME} && \
    chown -R ${SYS_USER_NAME}:${SYS_USER_NAME} ${APP_WORK_DIR} && \
    chmod -R 755 ${APP_WORK_DIR}

# Install system dependencies
RUN apt-get update -y && apt-get install -y --no-install-recommends \
    curl \
    g++ \
    git \
    libxml2-dev \
    libicu-dev \
    libcurl4-gnutls-dev \
    libmcrypt-dev \
    libjpeg-dev \
    libpng-dev \
    libsodium-dev \
    libzip-dev \
    zip \
    unzip \
    zlib1g-dev

# Install PHP extensions properly (configure first, then install)
RUN docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install -j$(nproc) opcache soap intl pcntl zip \
    && docker-php-source extract \
    && pecl install xdebug \
    && docker-php-source delete \
    && docker-php-ext-enable opcache sodium pcntl zip xdebug

# Clean up package cache to reduce image size
RUN rm -rf /var/lib/apt/lists/* /tmp/*

# Install Composer
RUN mkdir ${APP_COMPOSER_DIR} && \
    chown -R ${SYS_USER_NAME}:${SYS_USER_NAME} ${APP_COMPOSER_DIR} && \
    chmod -R 755 ${APP_COMPOSER_DIR}
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN echo "alias c='composer'" >> /etc/bash.bashrc

# Add healthcheck
HEALTHCHECK --interval=30s --timeout=3s --start-period=30s --retries=3 \
    CMD curl -f http://localhost:9000/ping || exit 1

# Configure GIT for the user
RUN git config --global --add safe.directory ${APP_WORK_DIR}
