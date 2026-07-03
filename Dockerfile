# 1. Gunakan PHP 8.4 karena Laravel & Symfony terbaru membutuhkan versi ini
FROM php:8.4-fpm-alpine

# 2. Install package OS dasar & dependensi ekstensi (icu-dev untuk intl, libzip-dev untuk zip)
RUN apk add --no-cache \
    nginx \
    wget \
    postgresql-dev \
    supervisor \
    git \
    icu-dev \
    libzip-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_pgsql intl zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 3. Set direktori kerja dan salin seluruh source code
COPY . /app
WORKDIR /app

# 4. Jalankan Composer Install (Sekarang platform environment sudah memenuhi semua kriteria)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 5. Atur kepemilikan folder sesuai kebutuhan runtime web server
RUN chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# 6. Salin konfigurasi Nginx dan Supervisor
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisord.conf

# 7. Eksekusi Supervisor sebagai entri utama
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]