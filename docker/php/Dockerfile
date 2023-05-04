FROM php:8.2-fpm as dev

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

RUN groupadd -g ${GID} laravel \
    && useradd -u ${UID} -g laravel -s /bin/sh -m laravel \
    && mkdir -p /home/laravel/.config/psysh \
    && chown -R laravel:laravel /home/laravel \
    && chmod -R 700 /home/laravel


RUN apt-get update \
    && apt-get install -y --no-install-recommends  \
    apt-transport-https libpng-dev libzip-dev libxml2-dev libonig-dev zip unzip \
    && rm -rf /var/lib/apt/lists/*


# Retrieve the script used to install PHP extensions from the source container.
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/install-php-extensions

# Install required PHP extensions and all their prerequisites available via apt.
RUN chmod uga+x /usr/bin/install-php-extensions \
    && sync \
    && install-php-extensions pdo_mysql mysqli

RUN mkdir -p /var/www
ADD ../.. .
FROM dev as debug
RUN install-php-extensions  @composer xdebug ## some extensions require composer to be inside the php. We are enabling this only for dev.
USER laravel
EXPOSE 9000
CMD ["php-fpm"]
