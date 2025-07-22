FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# ✅ Pindahkan document root ke public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

# Tambah konfigurasi override
RUN echo '<Directory /var/www/html>\n\
    AllowOverride All\n\
</Directory>' >> /etc/apache2/apache2.conf

EXPOSE 80

CMD ["apache2-foreground"]
