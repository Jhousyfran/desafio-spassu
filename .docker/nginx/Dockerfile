FROM nginx:1.27.5-alpine-slim
RUN apk update && apk add bash

RUN rm /etc/nginx/conf.d/default.conf
COPY ./nginx.conf /etc/nginx/conf.d