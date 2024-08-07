FROM php:7.2-apache
ARG BUILD_VERSION

RUN a2enmod rewrite && a2enmod ssl

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libpq-dev \
        vim \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql 

RUN useradd apache2
RUN usermod -u 69690 apache2
RUN groupmod -g 69690 apache2

RUN mkdir -p /home/apache2
RUN chown apache2:apache2 /home/apache2

ENV APACHE_RUN_USER apache2
ENV APACHE_RUN_GROUP apache2

RUN echo ': ${PORT:=80}' >> /etc/apache2/envvars
RUN echo 'export PORT' >> /etc/apache2/envvars
COPY ports.conf /etc/apache2

# Allow create .phar file
RUN echo 'phar.readonly=0' >> /usr/local/etc/php/conf.d/docker-php-phar-readonly.ini

#####

RUN mkdir -p /wis/system/bin
RUN mkdir -p /wis/windows
RUN chown apache2:apache2 /wis/system /wis/system/bin /wis/windows
RUN mkdir -p /wis/data/sessions /wis/data/wip /wis/data/locked /wis/data/storage
RUN chown apache2:apache2 /wis/data/sessions /wis/data/wip /wis/data/locked /wis/data/storage

WORKDIR /build/lib_wis_core_framework
COPY lib_wis_core_framework/ .
RUN mkdir -p build
RUN php onix_core_framework_build.php
RUN cp build/onix_core_framework.phar /wis/system/bin

WORKDIR /build/lib_wis_erp_framework
COPY lib_wis_erp_framework/ .
RUN mkdir -p build
RUN php onix_erp_framework_build.php
RUN cp build/onix_erp_framework.phar /wis/system/bin

COPY onix_server_scripts/* /wis/system/bin/
RUN sed -i "s#VERSION_HERE#${BUILD_VERSION}#g" /wis/system/bin/build.php
RUN ls -al /wis/system/bin; cat /wis/system/bin/build.php

COPY alias.conf /tmp
RUN cat /tmp/alias.conf >> /etc/apache2/apache2.conf

ENV WIS_CORE_ENCRYPTED=false

ENTRYPOINT ["/wis/system/bin/start.bash"]
