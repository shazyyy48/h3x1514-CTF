FROM php:8.1-apache

# Копирование исходного кода
COPY src/ /var/www/html/

# Настройка прав
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]