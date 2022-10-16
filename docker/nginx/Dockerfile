FROM nginx:1.20 AS base
ENV TZ=Asia/Tokyo
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf

FROM base AS development
COPY ./docker/nginx/development/default.conf /etc/nginx/conf.d/default.conf

FROM base AS prod
COPY ./docker/nginx/prod/default.conf /etc/nginx/conf.d/default.conf
COPY ./src /var/www/html

WORKDIR /var/www/html
RUN apt update \
    && apt install -y nodejs npm \
    && npm ci \
    && npm run prod

EXPOSE 80
