#!/bin/sh

# Configure Laravel
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache

# Start php-fpm
php-fpm
