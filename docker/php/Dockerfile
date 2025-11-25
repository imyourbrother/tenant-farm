FROM php:8.2-fpm

# Install system dependencies
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

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Create a startup script to fix permissions
RUN echo '#!/bin/bash\n\
if [ -d "/var/www/html/storage" ]; then\n\
    chown -R www-data:www-data /var/www/html/storage\n\
    chmod -R 775 /var/www/html/storage\n\
fi\n\
if [ -d "/var/www/html/bootstrap/cache" ]; then\n\
    chown -R www-data:www-data /var/www/html/bootstrap/cache\n\
    chmod -R 775 /var/www/html/bootstrap/cache\n\
fi\n\
exec php-fpm' > /usr/local/bin/docker-php-entrypoint-custom \
    && chmod +x /usr/local/bin/docker-php-entrypoint-custom

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["/usr/local/bin/docker-php-entrypoint-custom"]
