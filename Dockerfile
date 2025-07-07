FROM php:8.2-apache

# Instala extensões PHP necessárias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Ativa mod_rewrite (útil para frameworks, URLs amigáveis)
RUN a2enmod rewrite

# Define timezone (opcional)
ENV TZ=America/Sao_Paulo
