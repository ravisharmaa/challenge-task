FROM composer:2
## We need the user so that we can set it in the image which will not create any permission or whatsoever while deploying
ARG UID
ARG GID
ENV UID=${UID}
ENV GID=${GID}

RUN addgroup -g ${GID} laravel \
 && adduser -u ${UID} -G laravel -s /bin/sh -D laravel

USER laravel

