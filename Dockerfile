FROM php:8.2-fpm

# Arguments defined in docker-compose.yml
ARG user=laravel
ARG uid=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    supervisor \
    nginx

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

# Copy the application files
COPY . /var/www
COPY ./docker/nginx/conf.d/app.conf /etc/nginx/conf.d/default.conf
COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/app.ini
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Fix permissions
RUN chown -R $user:$user /var/www
RUN chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Set up the entrypoint script
COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

# Switch to non-root user
USER $user

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Switch back to root for starting services
USER root

# Expose port 80
EXPOSE 80

# Start supervisord
CMD ["/usr/local/bin/entrypoint"]
