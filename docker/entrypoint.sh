#!/bin/sh
set -e

echo "Starting application setup..."

# Copy env only if not exists
if [ ! -f .env ]; then
  echo "Creating .env file"
  cp .env.example .env
fi

# Install dependencies if vendor not exists
if [ ! -d vendor ]; then
  echo "Installing Composer dependencies"
  composer install --no-interaction
fi

# Generate key if not exists
if ! grep -q "APP_KEY=base64" .env; then
  echo "Generating app key"
  php artisan key:generate
fi

# Wait for DB
echo "Waiting for database..."
until php -r "
try {
  new PDO(
    'mysql:host=db;dbname=record_db',
    'root',
    'root'
  );
} catch (Exception \$e) {
  exit(1);
}
"; do
  sleep 2
done


# Run migrations + seed only once
if [ ! -f storage/.migrated ]; then
  echo "Running migrations & seeders"
  php artisan migrate --seed --force
  touch storage/.migrated
fi

echo "Setup completed"

exec apache2-foreground
