# Use a imagem oficial do PHP com Apache
FROM php:8.3-apache

# Instalar extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip && \
    docker-php-ext-install pdo_mysql zip

# Habilitar o módulo de reescrita do Apache
RUN a2enmod rewrite

# Copiar as configurações customizadas de PHP
COPY ./docker/php.ini /usr/local/etc/php/

# Configurar o diretório de trabalho
WORKDIR /var/www/html

# Copiar o projeto para o container
COPY . /var/www/html

CMD ["apache2-foreground"]
