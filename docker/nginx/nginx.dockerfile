FROM nginx:stable-alpine
ARG FPM_HOST="127.0.0.1"
RUN echo "$FPM_HOST"

ADD default.conf  /etc/nginx/conf.d/default.conf
RUN sed -i "s/fastcgi_pass_network_address/${FPM_HOST}/g" /etc/nginx/conf.d/default.conf

ARG UID
ARG GID
ENV UID=${UID}
ENV GID=${GID}

RUN addgroup -g ${GID} laravel \
 && adduser -u ${UID} -G laravel -s /bin/sh -D laravel
