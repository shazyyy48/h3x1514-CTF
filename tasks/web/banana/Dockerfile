# Базовый образ с Apache + PHP
FROM php:8.2-apache

# Включаем нужные модули Apache
RUN a2enmod rewrite

# Копируем весь сайт в директорию Apache
COPY . /var/www/html/

# Права (чтобы не было проблем с сессиями)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Указываем рабочую директорию
WORKDIR /var/www/html

# Открываем порт Apache
EXPOSE 80
