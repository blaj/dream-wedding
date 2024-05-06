# PHP Prod image
FROM composer/composer:2-bin AS composer
FROM mlocati/php-extension-installer:latest AS php_extension_installer

FROM php:8.3-fpm-alpine as php

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

ENV APP_ENV=prod

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY --from=composer /composer /usr/bin/composer

COPY --from=php_extension_installer /usr/bin/install-php-extensions /usr/local/bin/
COPY ./config/php/docker.conf /usr/local/etc/php-fpm.d/docker.conf

RUN apk update
RUN apk add --no-cache \
		acl \
		fcgi \
		file \
		gettext \
		git \
        curl \
        nano \
        icu-dev \
        libsodium-dev \
        zlib-dev \
        libpng-dev \
        libzip-dev \
        rabbitmq-c \
        rabbitmq-c-dev \
        postgresql-dev \
	;

RUN set -eux; \
    install-php-extensions \
    	intl \
    	zip \
    	apcu \
		opcache \
        zip \
        sodium \
        gd \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        amqp \
    ;

WORKDIR /var/www/html

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php/conf.d/app.ini $PHP_INI_DIR/conf.d/
COPY docker/php/conf.d/app.prod.ini $PHP_INI_DIR/conf.d/

COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

RUN addgroup -g ${GID} --system dreamwedding
RUN adduser -G dreamwedding --system -D -s /bin/sh -u ${UID} dreamwedding

RUN sed -i "s/user = www-data/user = dreamwedding/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = dreamwedding/g" /usr/local/etc/php-fpm.d/www.conf

COPY . ./

RUN chown -R dreamwedding:dreamwedding ./

USER dreamwedding

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

# Nginx image
FROM nginx:1.23.3-alpine AS nginx

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

WORKDIR /var/www/html

COPY docker/nginx/conf.d/default.conf /etc/nginx/conf.d

RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

RUN addgroup -g ${GID} --system dreamwedding
RUN adduser -G dreamwedding --system -D -s /bin/sh -u ${UID} dreamwedding

RUN sed -i "s/user www-data/user dreamwedding/g" /etc/nginx/nginx.conf

COPY ./public ./public

RUN chown -R dreamwedding:dreamwedding ./public
