# Usamos PHP 8.2 con Apache como base
FROM php:8.2-apache

# Instalamos dependencias del sistema y extensiones de PHP para PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilitamos mod_rewrite de Apache (útil para URLs amigables)
RUN a2enmod rewrite

# Configuramos el directorio de trabajo
WORKDIR /var/www/html

# Copiamos el código de la aplicación
COPY . .

# Establecemos los permisos correctos para el servidor web
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exponemos el puerto 80 (estándar de Render)
EXPOSE 80

# El comando por defecto ya es apache2-foreground en esta imagen base
