FROM nginx:1.20
ENV TZ=Asia/Tokyo
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/prod/default.conf /etc/nginx/conf.d/default.conf
COPY ./src /var/www/html
EXPOSE 80
