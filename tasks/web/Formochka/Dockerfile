FROM php:8.2-apache

RUN docker-php-ext-install mysqli

COPY index.html /var/www/html/index.html
COPY login.php /var/www/html/login.php

RUN chown -R www-data:www-data /var/www/html
