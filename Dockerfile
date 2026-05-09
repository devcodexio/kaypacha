# Usamos PHP 8.2 con Apache como base
FROM php:8.2-apache

# Instalamos dependencias del sistema y extensiones de PHP para PostgreSQL y utilidades comunes
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilitamos mod_rewrite de Apache
RUN a2enmod rewrite

# Configuramos el directorio de trabajo
WORKDIR /var/www/html

# Copiamos el código de la aplicación
COPY . .

# Establecemos los permisos correctos para el servidor web
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Configuramos PHP para que envíe los errores a los logs de Render (stderr)
RUN echo "log_errors = On" >> /usr/local/etc/php/conf.d/docker-php-logging.ini \
    && echo "error_log = /dev/stderr" >> /usr/local/etc/php/conf.d/docker-php-logging.ini

# Exponemos el puerto 80
EXPOSE 80
