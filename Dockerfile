FROM php:7.2-cli

RUN docker-php-ext-install pdo_mysql

ADD app/ /app
WORKDIR /app

CMD [ "php", "-S", "127.0.0.1:80", "-t", "public" ]