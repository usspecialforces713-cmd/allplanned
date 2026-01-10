FROM php:8.2-apache

# Dépendances PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev

# Extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_pgsql

# Activer mod_rewrite
RUN a2enmod rewrite

# Copier le site
COPY . /var/www/html

# Droits
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
