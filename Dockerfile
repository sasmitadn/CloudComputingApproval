FROM php:8.2-fpm-alpine

# Install system dependencies & PostgreSQL driver
RUN apk add --no-cache nginx wget postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copy aplikasi
COPY . /app
RUN chown -R www-data:www-data /app

# Setup Nginx & Workdir
WORKDIR /app
RUN ln -sf /usr/share/zoneinfo/Asia/Jakarta /etc/localtime

# Jalankan skrip startup
ENTRYPOINT ["sh", "/app/docker-entrypoint.sh"]