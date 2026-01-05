FROM php:8.2-apache

# Activer Apache + PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copier le site dans le serveur web
COPY . /var/www/html/

# Donner les bons droits
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
