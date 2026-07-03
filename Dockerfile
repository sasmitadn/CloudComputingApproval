FROM php:8.2-fpm-alpine

# Install system dependencies & PostgreSQL driver
RUN apk add --no-cache nginx wget postgresql-dev supervisor \
    && docker-php-ext-install pdo pdo_pgsql

# Copy semua file aplikasi
COPY . /app
WORKDIR /app

# Atur permission untuk Laravel
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app/storage

# Copy konfigurasi Nginx khusus untuk Cloud Run
COPY nginx.conf /etc/nginx/nginx.conf

# Jalankan startup script
RUN chmod +x /app/docker-entrypoint.sh
ENTRYPOINT ["/app/docker-entrypoint.sh"]