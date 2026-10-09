FROM php:8.5-cli-alpine

RUN apk add --no-cache git unzip nodejs npm \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && docker-php-ext-install pdo_mysql bcmath \
    && apk del .build-deps

COPY docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# The basename here matters: package.json has no "name" field, so npm
# infers one from the working directory on `npm install` and writes it
# into package-lock.json — it must match the host checkout's directory
# name or every container-side install dirties that file.
WORKDIR /var/www/true9-fictional-payroll

COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8000 5173

ENTRYPOINT ["docker-entrypoint.sh"]
