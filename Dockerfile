# Usar uma imagem oficial do PHP com Apache
FROM php:7.4-apache

# Ativar a extensão MySQLi para o PHP
RUN docker-php-ext-install mysqli

# Copiar o arquivo php.ini (caso queira modificar configurações)
COPY php.ini /usr/local/etc/php/

# Expor a porta 80
EXPOSE 80
