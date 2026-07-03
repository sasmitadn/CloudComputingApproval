#!/bin/sh

# Jalankan PHP-FPM di background (port 9000)
php-fpm -D

# Jalankan Nginx di foreground (port 8080) agar Cloud Run tidak mati
nginx -g "daemon off;"