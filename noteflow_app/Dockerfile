FROM php:8.4-fpm

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
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy app files
COPY . .

# 1. Create dummy .env to prevent build crashes during composer install
RUN echo "APP_KEY=" > .env

# 2. Install dependencies WITHOUT scripts (scripts fail without real DB/.env)
RUN composer install --optimize-autoloader --no-dev --no-scripts

# 3. Set permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# 4. Create Startup Script (Run Migrations then Start Server)
# This runs every time the container starts, ensuring DB is up to date.
RUN printf '#!/bin/bash\necho "Running Migrations..."\nphp artisan migrate --force || echo "Migration skipped"\necho "Generating Docs..."\nphp artisan scribe:generate || echo "Docs skipped"\necho "Starting Supervisor..."\n/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf\n' > /start.sh \
    && chmod +x /start.sh

# 5. Nginx configuration
RUN printf 'server {\n    listen 80;\n    root /var/www/html/public;\n    index index.php;\n    location / {\n        try_files $uri $uri/ /index.php?$query_string;\n    }\n    location ~ \.php$ {\n        fastcgi_pass 127.0.0.1:9000;\n        fastcgi_index index.php;\n        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n        include fastcgi_params;\n    }\n}\n' > /etc/nginx/sites-available/default

# 6. Supervisor config
RUN printf '[supervisord]\nnodaemon=true\n\n[program:php-fpm]\ncommand=php-fpm\nautorestart=true\n\n[program:nginx]\ncommand=nginx -g "daemon off;"\nautorestart=true\n' > /etc/supervisor/conf.d/supervisord.conf

# Expose port 80
EXPOSE 80

# 7. Run the startup script
CMD ["/start.sh"]
