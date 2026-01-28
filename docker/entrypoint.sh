#!/bin/sh
set -e

echo "Starting application setup..."

# Create .env if missing
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Install dependencies
if [ ! -d vendor ]; then
  composer install --no-interaction --prefer-dist
fi

# Generate app key
if ! grep -q "APP_KEY=base64" .env; then
  php artisan key:generate
fi

echo "Waiting for database..."
until php -r "
try {
  new PDO(
    'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'),
    getenv('DB_USERNAME'),
    getenv('DB_PASSWORD')
  );
} catch (Exception \$e) {
  exit(1);
}
"; do
  sleep 2
done

# Migrate once
if [ ! -f storage/.migrated ]; then
  php artisan migrate --seed --force
  touch storage/.migrated
fi

echo "Setup completed"

exec apache2-foreground
