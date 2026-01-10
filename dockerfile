FROM php:8.2-apache

# Installer les dépendances nécessaires à PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev

# Installer extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_pgsql

# Activer mod_rewrite
RUN a2enmod rewrite

# Copier le site dans Apache
COPY . /var/www/html

# Donner les bons droits
RUN chown -R www-data:www-data /var/www/html

# Exposer le port Apache
EXPOSE 80
