FROM php:8.2-apache

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    default-mysql-client \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    fonts-dejavu-core \
    && rm -rf /var/lib/apt/lists/*

# Instala extensões do PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) pdo pdo_mysql mysqli gd mbstring zip intl

# Ativa o mod_rewrite
RUN a2enmod rewrite

# Define timezone
ENV TZ=America/Sao_Paulo

# Copia tudo para o container
COPY . /var/www/html

# Define o diretório de trabalho
WORKDIR /var/www/html

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Instala dependências do Composer
RUN composer install --no-interaction --optimize-autoloader

# Corrige permissões
RUN chown -R www-data:www-data /var/www/html

# Copia configuração personalizada do Apache
COPY app/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Expõe a porta 80
EXPOSE 80

# Copia entrypoint e torna executável
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
