# 1. Use PHP 8.2 with Apache
FROM php:8.2-apache

# 2. Install system packages (git, zip, etc.)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# 3. Install PHP Extensions (pdo_mysql is vital for Aiven)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 4. Apache Configuration
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf || true
RUN a2enmod rewrite

# 5. Set Working Directory
WORKDIR /var/www/html

# 6. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. Copy Project Files
COPY . .

# 8. Install Dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN npm install
RUN npm run build

# 9. Set Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Setup Entrypoint Script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# 11. Start Server
EXPOSE 80
ENTRYPOINT ["entrypoint.sh"]