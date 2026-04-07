FROM php:8.2-apache

# Installation des dépendances système et PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Installation des extensions PHP pour Laravel et MySQL
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Activation du mod_rewrite d'Apache (Crucial pour les routes Laravel)
RUN a2enmod rewrite

# Copie du projet
COPY . /var/www/html

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Port par défaut
EXPOSE 80

CMD ["apache2-foreground"]