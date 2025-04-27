FROM php:8.4.6-fpm-alpine3.21

ARG HOST_UID=1000
ARG HOST_GID=1000

RUN apk add --no-cache openssl bash mysql-client nodejs npm
RUN docker-php-ext-install pdo pdo_mysql bcmath

WORKDIR /var/www

RUN rm -rf /var/www/html
RUN ln -s public html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 9000

RUN addgroup -g ${HOST_GID} appgroup \
    && adduser -D -u ${HOST_UID} -G appgroup appuser

USER appuser

ENTRYPOINT [ "php-fpm" ]