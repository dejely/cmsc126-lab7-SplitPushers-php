FROM php:8.3-apache

COPY . /var/www/html/

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

CMD ["apache2-foreground"]
