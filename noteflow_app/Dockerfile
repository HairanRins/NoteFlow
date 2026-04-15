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
    libpq-dev \
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

# Create a minimal .env for Vite build (needed for Ziggy routes)
RUN printf "APP_NAME=NoteFlow\nAPP_ENV=production\nAPP_KEY=base64:dGVzdGtleXRlc3RrZXl0ZXN0a2V5dGVzdGtleXRlc3Q=\nAPP_DEBUG=false\nAPP_URL=http://localhost\nDB_CONNECTION=sqlite\n" > .env

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

# Copy built frontend assets (from Vite build)
COPY --from=frontend /app/public/build ./public/build

# Install Scribe for production documentation
RUN composer require knuckleswtf/scribe --no-interaction --no-scripts

# Install dependencies WITHOUT scripts (scripts fail without real DB/.env)
RUN composer install --optimize-autoloader --no-scripts

# Set permissions for storage (PostgreSQL uses external DB)
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Create Startup Script that uses Render env vars directly
RUN printf '#!/bin/bash\nset -e\n\necho "=== NoteFlow Startup ==="\n\n# Generate APP_KEY if not set\nif [ -z "$APP_KEY" ]; then\n    export APP_KEY="base64:$(php -r \"echo base64_encode(random_bytes(32));\")"\n    echo "Generated new APP_KEY"\nelse\n    echo "Using existing APP_KEY"\nfi\n\n# Write complete .env from environment variables (no sed replacements)\ncat > .env << ENVEOF\nAPP_NAME=${APP_NAME:-NoteFlow}\nAPP_ENV=${APP_ENV:-production}\nAPP_KEY=${APP_KEY}\nAPP_DEBUG=${APP_DEBUG:-false}\nAPP_URL=${APP_URL:-https://noteflow-orqw.onrender.com}\nDB_CONNECTION=${DB_CONNECTION:-pgsql}\nDB_HOST=${DB_HOST:-localhost}\nDB_PORT=${DB_PORT:-5432}\nDB_DATABASE=${DB_DATABASE:-noteflow}\nDB_USERNAME=${DB_USERNAME:-postgres}\nDB_PASSWORD=${DB_PASSWORD:-}\nLOG_CHANNEL=${LOG_CHANNEL:-stderr}\nLOG_LEVEL=${LOG_LEVEL:-error}\nCACHE_DRIVER=${CACHE_DRIVER:-file}\nSESSION_DRIVER=${SESSION_DRIVER:-file}\nQUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}\nENVEOF\n\necho ".env written successfully"\ncat .env | grep -v "APP_KEY\\|DB_PASSWORD" | grep "="\n\n# Clear and cache configurations\nphp artisan config:clear\nphp artisan route:clear\nphp artisan view:clear\nphp artisan config:cache\nphp artisan route:cache\n\necho "Publishing Scribe assets..."\nphp artisan vendor:publish --provider="Knuckles\\\\Scribe\\\\ScribeServiceProvider" --tag="scribe-assets" --force || echo "Scribe assets publish failed but continuing..."\n\necho "Running Migrations..."\nphp artisan migrate --force || echo "Migration failed but continuing..."\necho "Generating API Docs..."\nphp artisan scribe:generate --force || echo "Docs generation failed but continuing..."\necho "Starting Supervisor..."\n/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf\n' > /start.sh \
    && chmod +x /start.sh

# Nginx configuration
RUN printf 'server {\n    listen 80;\n    root /var/www/html/public;\n    index index.php index.html;\n\n    location / {\n        try_files $uri $uri/ /index.php?$query_string;\n    }\n\n    location ~ \.php$ {\n        fastcgi_pass 127.0.0.1:9000;\n        fastcgi_index index.php;\n        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n        include fastcgi_params;\n    }\n\n    # Cache static assets\n    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {\n        expires 1y;\n        add_header Cache-Control "public, immutable";\n    }\n}\n' > /etc/nginx/sites-available/default

# Supervisor config
RUN printf '[supervisord]\nnodaemon=true\n\n[program:php-fpm]\ncommand=php-fpm -F\nautorestart=true\nstdout_logfile=/dev/stdout\nstdout_logfile_maxbytes=0\nstderr_logfile=/dev/stderr\nstderr_logfile_maxbytes=0\n\n[program:nginx]\ncommand=nginx -g "daemon off;"\nautorestart=true\nstdout_logfile=/dev/stdout\nstdout_logfile_maxbytes=0\nstderr_logfile=/dev/stderr\nstderr_logfile_maxbytes=0\n' > /etc/supervisor/conf.d/supervisord.conf

# Expose port 80
EXPOSE 80

# Run the startup script
CMD ["/start.sh"]
