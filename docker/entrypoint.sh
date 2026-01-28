#!/bin/sh
set -e

echo "Starting application setup..."

# Fail fast if .env is missing
if [ ! -f .env ]; then
  echo ".env file not found. Please create it from .env.example"
  exit 1
fi

# Install dependencies if needed
if [ ! -d vendor ]; then
  echo "Installing Composer dependencies..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Generate APP_KEY if missing
if ! grep -q "APP_KEY=base64" .env; then
  echo "Generating APP_KEY..."
  php artisan key:generate --force
fi

echo "Waiting for database connection..."

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

# Run migrations only once
if [ ! -f storage/.migrated ]; then
  echo "Running migrations and seeders..."
  php artisan migrate --seed --force
  touch storage/.migrated
fi

echo "Setup completed successfully"

exec apache2-foreground
