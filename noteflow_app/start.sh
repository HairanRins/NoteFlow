#!/bin/bash
set -e

echo "=== NoteFlow Startup ==="

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    GENERATED_KEY=$(php -r 'echo base64_encode(random_bytes(32));')
    export APP_KEY="base64:${GENERATED_KEY}"
    echo "Generated new APP_KEY"
else
    echo "Using existing APP_KEY"
fi

# Write complete .env from environment variables
cat > .env << ENVEOF
APP_NAME=${APP_NAME:-NoteFlow}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-https://noteflow-orqw.onrender.com}
DB_CONNECTION=${DB_CONNECTION:-pgsql}
DB_HOST=${DB_HOST:-localhost}
DB_PORT=${DB_PORT:-5432}
DB_DATABASE=${DB_DATABASE:-noteflow}
DB_USERNAME=${DB_USERNAME:-postgres}
DB_PASSWORD=${DB_PASSWORD:-}
LOG_CHANNEL=${LOG_CHANNEL:-stderr}
LOG_LEVEL=${LOG_LEVEL:-error}
CACHE_DRIVER=${CACHE_DRIVER:-file}
SESSION_DRIVER=${SESSION_DRIVER:-file}
QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}
ENVEOF

echo ".env written successfully"
cat .env | grep -v "APP_KEY\|DB_PASSWORD" | grep "="

# Clear and cache configurations
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache

echo "Publishing Scribe assets..."
php artisan vendor:publish --provider="Knuckles\\Scribe\\ScribeServiceProvider" --tag="scribe-assets" --force || echo "Scribe assets publish failed but continuing..."

echo "Running Migrations..."
php artisan migrate --force || echo "Migration failed but continuing..."
echo "Generating API Docs..."
php artisan scribe:generate --force || echo "Docs generation failed but continuing..."
echo "Starting Supervisor..."
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
