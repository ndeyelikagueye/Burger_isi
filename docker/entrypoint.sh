#!/bin/bash

# Wait for database to be ready (if using external DB)
# while ! nc -z db 3306; do
#   echo "Waiting for MySQL Database to start..."
#   sleep 1
# done

# Run migrations if needed
php artisan migrate --force

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Set proper permissions for storage and cache
chmod -R 775 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache

# Start supervisord
exec supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
