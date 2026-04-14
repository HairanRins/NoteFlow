# Stage 1: Install PHP dependencies first (needed for Ziggy JS bridge)
FROM php:8.4-fpm AS php-deps

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
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-dev

# Stage 2: Build frontend assets
FROM node:20 AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
# Copy vendor from PHP deps stage (includes tightenco/ziggy)
COPY --from=php-deps /app/vendor ./vendor

RUN npm run build

# Stage 3: Production PHP image
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

# Copy app files (excluding vendor and node_modules via .dockerignore)
COPY . .

# Copy vendor from php-deps stage
COPY --from=php-deps /app/vendor ./vendor

# Copy built frontend assets
COPY --from=frontend /app/public/build ./public/build

# 1. Create production .env with PostgreSQL settings
# RUN echo "APP_NAME=NoteFlow\nAPP_ENV=production\nAPP_KEY=base64:$(php -r 'echo base64_encode(random_bytes(32));')\nAPP_DEBUG=false\nAPP_URL=https://noteflow-orqw.onrender.com\nDB_CONNECTION=pgsql\nDB_URL=\${DATABASE_URL}\nDB_HOST=localhost\nDB_PORT=5432\nDB_DATABASE=noteflow\nDB_USERNAME=postgres\nDB_PASSWORD=\${DATABASE_PASSWORD}\nDB_SSLMODE=require\nLOG_CHANNEL=stderr\nLOG_LEVEL=error\nBCRYPT_ROUNDS=12\n" > .env

# 2. Install Scribe for production documentation
RUN composer require knuckleswtf/scribe --no-interaction

# 3. Install dependencies WITHOUT scripts (scripts fail without real DB/.env)
RUN composer install --optimize-autoloader --no-scripts

# 4. Set permissions for storage (PostgreSQL uses external DB)
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# 5. Create Startup Script that uses Render env vars directly
RUN printf '#!/bin/bash\n# Override .env values with environment variables from Render (if set)\nif [ ! -z "$DB_HOST" ]; then\n    sed -i "s/DB_HOST=.*/DB_HOST=$DB_HOST/" .env\nfi\nif [ ! -z "$DB_PORT" ]; then\n    sed -i "s/DB_PORT=.*/DB_PORT=$DB_PORT/" .env\nfi\nif [ ! -z "$DB_DATABASE" ]; then\n    sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" .env\nfi\nif [ ! -z "$DB_USERNAME" ]; then\n    sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" .env\nfi\nif [ ! -z "$DB_PASSWORD" ]; then\n    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env\nfi\n\n# Also support DATABASE_URL format as fallback\nif [ ! -z "$DATABASE_URL" ] && [ -z "$DB_HOST" ]; then\n    DB_HOST=$(echo $DATABASE_URL | sed -n "s/^postgres:\\/\\/[^@]*@\\([^:]*\\):.*/\\1/p")\n    DB_PORT=$(echo $DATABASE_URL | sed -n "s/^postgres:\\/\\/[^@]*@[^:]*:\\([0-9]*\\)\\/.*/\\1/p")\n    DB_NAME=$(echo $DATABASE_URL | sed -n "s/^postgres:\\/\\/[^@]*@[^:]*:[0-9]*\\/\\([^?]*\\).*/\\1/p")\n    DB_USER=$(echo $DATABASE_URL | sed -n "s/^postgres:\\/\\/\\([^:]*\\):.*/\\1/p")\n    DB_PASS=$(echo $DATABASE_URL | sed -n "s/^postgres:\\/\\/[^:]*:\\([^@]*\\)@.*/\\1/p")\n    sed -i "s/DB_HOST=.*/DB_HOST=$DB_HOST/" .env\n    sed -i "s/DB_PORT=.*/DB_PORT=$DB_PORT/" .env\n    sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env\n    sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env\n    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASS/" .env\n    echo "Database configured from DATABASE_URL: $DB_HOST:$DB_PORT/$DB_NAME"\nelse\n    echo "Database configured from env vars: $DB_HOST:$DB_PORT/$DB_DATABASE"\nfi\n\necho "Running Migrations..."\nphp artisan migrate --force || echo "Migration skipped"\necho "Generating Docs..."\nphp artisan scribe:generate || echo "Docs skipped"\necho "Starting Supervisor..."\n/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf\n' > /start.sh \
    && chmod +x /start.sh

# 6. Nginx configuration
RUN printf 'server {\n    listen 80;\n    root /var/www/html/public;\n    index index.php;\n    location / {\n        try_files $uri $uri/ /index.php?$query_string;\n    }\n    location ~ \.php$ {\n        fastcgi_pass 127.0.0.1:9000;\n        fastcgi_index index.php;\n        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n        include fastcgi_params;\n    }\n}\n' > /etc/nginx/sites-available/default

# 7. Supervisor config
RUN printf '[supervisord]\nnodaemon=true\n\n[program:php-fpm]\ncommand=php-fpm\nautorestart=true\n\n[program:nginx]\ncommand=nginx -g "daemon off;"\nautorestart=true\n' > /etc/supervisor/conf.d/supervisord.conf

# Expose port 80
EXPOSE 80

# 8. Run the startup script
CMD ["/start.sh"]
