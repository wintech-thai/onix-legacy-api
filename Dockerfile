FROM php:8.4.27-apache

ARG BUILD_VERSION

# Enable Apache modules
RUN a2enmod rewrite ssl

# Install system dependencies and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libpq-dev \
        vim \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        iconv \
        gd \
        pdo \
        pdo_pgsql \
        pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Create application user and group
RUN groupadd --gid 69690 apache2 \
    && useradd --uid 69690 --gid apache2 --create-home apache2

ENV APACHE_RUN_USER=apache2
ENV APACHE_RUN_GROUP=apache2

# Apache port configuration
RUN echo ': ${PORT:=80}' >> /etc/apache2/envvars \
    && echo 'export PORT' >> /etc/apache2/envvars

COPY ports.conf /etc/apache2/ports.conf

# Allow creating PHAR archives
RUN echo 'phar.readonly=0' \
    > /usr/local/etc/php/conf.d/docker-php-phar-readonly.ini

# Application directories
RUN mkdir -p \
        /wis/system/bin \
        /wis/windows \
        /wis/data/sessions \
        /wis/data/wip \
        /wis/data/locked \
        /wis/data/storage \
    && chown -R apache2:apache2 \
        /wis/system \
        /wis/windows \
        /wis/data \
        /home/apache2

# Build WIS Core Framework
WORKDIR /build/lib_wis_core_framework

COPY lib_wis_core_framework/ ./

RUN mkdir -p build \
    && php onix_core_framework_build.php \
    && cp build/onix_core_framework.phar /wis/system/bin/

# Build WIS ERP Framework
WORKDIR /build/lib_wis_erp_framework

COPY lib_wis_erp_framework/ ./

RUN mkdir -p build \
    && php onix_erp_framework_build.php \
    && cp build/onix_erp_framework.phar /wis/system/bin/

# Copy application scripts
COPY onix_server_scripts/ /wis/system/bin/

RUN sed -i "s#VERSION_HERE#${BUILD_VERSION}#g" \
        /wis/system/bin/build.php \
    && ls -al /wis/system/bin \
    && cat /wis/system/bin/build.php

# Apache aliases
COPY alias.conf /tmp/alias.conf

RUN cat /tmp/alias.conf >> /etc/apache2/apache2.conf \
    && rm /tmp/alias.conf

ENV WIS_CORE_ENCRYPTED=false

WORKDIR /

ENTRYPOINT ["/wis/system/bin/start.bash"]
