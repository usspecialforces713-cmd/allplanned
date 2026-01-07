FROM php:8.2-apache

# Installer extensions PHP nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Activer mod_rewrite (important pour PHP)
RUN a2enmod rewrite

# Copier le site dans le dossier Apache
COPY . /var/www/html

# Donner les bons droits
RUN chown -R www-data:www-data /var/www/html

# Exposer le port Apache
EXPOSE 80
