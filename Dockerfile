FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    zip unzip git curl \
    libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath gd

# Enable Apache rewrite
RUN a2enmod rewrite

# Set Laravel public directory
RUN sed -i 's|DocumentRoot .*|DocumentRoot /var/www/html/public|' \
    /etc/apache2/sites-available/000-default.conf

# Allow .htaccess (CRITICAL)
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' \
    /etc/apache2/apache2.conf

# Optional cleanup warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
