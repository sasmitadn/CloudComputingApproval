FROM php:8.2-fpm-alpine

# 1. Install system dependencies, PostgreSQL driver, & Composer
RUN apk add --no-cache nginx wget postgresql-dev supervisor git \
    && docker-php-ext-install pdo pdo_pgsql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 2. Set working directory
COPY . /app
WORKDIR /app

# 3. Jalankan Composer Install untuk membuat folder vendor
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 4. Atur permission untuk Laravel agar tidak Access Denied
RUN chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# 5. Copy konfigurasi Nginx & Supervisor
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisord.conf

# 6. Jalankan via Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]