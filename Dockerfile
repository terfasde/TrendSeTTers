FROM php:8.1-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Reiniciar Apache
RUN a2enmod rewrite