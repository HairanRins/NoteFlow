FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy app files
COPY . .

# Install dependencies (ignore scripts that might fail without .env)
RUN composer install --optimize-autoloader --no-dev --no-scripts || true

# Generate app key if not present
RUN if [ ! -f .env ]; then echo "APP_KEY=" > .env; fi
RUN php artisan key:generate --no-interaction || true

# Set permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Nginx configuration
RUN echo 'server { \
    listen 80; \
    root /var/www/html/public; \
    index index.php; \
    location / { \
        try_files $uri $uri/ /index.php?$query_string; \
    } \
    location ~ \.php$ { \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
        include fastcgi_params; \
    } \
}' > /etc/nginx/sites-available/default

# Supervisor config
RUN echo '[supervisord] \
nodaemon=true \
\
[program:php-fpm] \
command=php-fpm \
autorestart=true \
\
[program:nginx] \
command=nginx -g "daemon off;" \
autorestart=true \
' > /etc/supervisor/conf.d/supervisord.conf

# Expose port 80
EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
