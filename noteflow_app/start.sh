#!/bin/bash
# Don't exit on error - we want to see ALL errors
set +e

echo "========================================="
echo "=== NoteFlow Startup ==="
echo "========================================="

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    GENERATED_KEY=$(php -r 'echo base64_encode(random_bytes(32));')
    export APP_KEY="base64:${GENERATED_KEY}"
    echo "✓ Generated new APP_KEY"
else
    echo "✓ Using existing APP_KEY"
fi

# Write .env from environment variables
cat > .env << ENVEOF
APP_NAME=${APP_NAME:-NoteFlow}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=true
APP_URL=${APP_URL:-https://noteflow-orqw.onrender.com}
DB_CONNECTION=${DB_CONNECTION:-pgsql}
DB_HOST=${DB_HOST:-localhost}
DB_PORT=${DB_PORT:-5432}
DB_DATABASE=${DB_DATABASE:-noteflow}
DB_USERNAME=${DB_USERNAME:-postgres}
DB_PASSWORD=${DB_PASSWORD:-}
LOG_CHANNEL=stderr
LOG_LEVEL=debug
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
ENVEOF

echo "✓ .env written"
echo ""
echo "--- .env contents (secrets hidden) ---"
grep "=" .env | grep -v "APP_KEY\|DB_PASSWORD"
echo "--------------------------------------"
echo ""

# Clear ALL caches first
echo ">>> Clearing caches..."
php artisan config:clear 2>&1
php artisan route:clear 2>&1
php artisan view:clear 2>&1
php artisan cache:clear 2>&1
echo ""

# Verify critical files exist
echo ">>> Checking critical files..."
if [ -f "public/build/manifest.json" ]; then
    echo "✓ Vite manifest exists"
else
    echo "✗ WARNING: Vite manifest NOT found at public/build/manifest.json"
    ls -la public/build/ 2>/dev/null || echo "  public/build/ directory does not exist!"
fi

if [ -d "vendor" ]; then
    echo "✓ vendor/ directory exists"
else
    echo "✗ ERROR: vendor/ directory NOT found!"
fi

echo ""

# Try to cache config/routes (these can fail if routes have issues)
echo ">>> Caching config and routes..."
php artisan config:cache 2>&1 || echo "⚠ config:cache failed (continuing anyway)"
php artisan route:cache 2>&1 || echo "⚠ route:cache failed (continuing anyway)"
echo ""

# Publish Scribe assets
echo ">>> Publishing Scribe assets..."
php artisan vendor:publish --provider="Knuckles\\Scribe\\ScribeServiceProvider" --tag="scribe-assets" --force 2>&1 || echo "⚠ vendor:publish failed"
echo ""

# Check if Scribe assets were published
echo ">>> Checking Scribe assets..."
if [ -d "public/vendor/scribe" ]; then
    echo "✓ Scribe assets directory exists"
    ls -la public/vendor/scribe/ 2>&1 | head -10
else
    echo "✗ Scribe assets directory NOT found"
fi
echo ""

# Run migrations
echo ">>> Running migrations..."
php artisan migrate --force 2>&1 || echo "⚠ Migration failed (DB might not be ready yet)"
echo ""

# Generate API docs
echo ">>> Generating Scribe docs..."
php artisan scribe:generate --force 2>&1 || echo "⚠ Scribe generate failed"
echo ""

# Check generated docs
echo ">>> Checking generated docs..."
if [ -f "storage/app/scribe/.scribe.md" ]; then
    echo "✓ Scribe docs generated"
else
    echo "⚠ Scribe docs may not be generated yet"
fi
if [ -f "public/docs/openapi.yaml" ] || [ -f "storage/app/scribe/openapi.yaml" ]; then
    echo "✓ OpenAPI spec exists"
else
    echo "⚠ OpenAPI spec NOT found"
fi
echo ""

echo "========================================="
echo "=== Starting Nginx + PHP-FPM ==="
echo "========================================="

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
