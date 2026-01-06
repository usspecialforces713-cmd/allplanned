FROM php:8.2-apache

# Activer Apache + PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copier le site dans le serveur web
COPY . allplannedapp-com.onrender.com

# Donner les bons droits
RUN chown -R www-data:www-data allplanned.onrender.com

EXPOSE 80
