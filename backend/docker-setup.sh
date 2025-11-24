#!/bin/bash

echo "🚀 Starting Laravel setup..."

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 2

# Install dependencies (in case of changes)
echo "📦 Installing/updating Composer dependencies..."
composer install --no-interaction --optimize-autoloader

# Generate app key if not exists
if [ ! -f .env ]; then
    echo "📋 Creating .env file..."
    cp .env.example .env
fi

# Check if app key exists
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

# Set proper permissions
echo "🔒 Setting proper permissions..."
chown -R www-data:www-data /var/www/backend
chmod -R 755 /var/www/backend
chmod -R 775 /var/www/backend/storage
chmod -R 775 /var/www/backend/bootstrap/cache

# Create database if not exists
echo "🗄️  Creating database file if not exists..."
mkdir -p /var/www/backend/database
touch /var/www/backend/database/database.sqlite
chown -R www-data:www-data /var/www/backend/database
chmod 664 /var/www/backend/database/database.sqlite

# Run migrations
echo "📊 Running database migrations..."
php artisan migrate --force

# Run seeders
echo "🌱 Seeding database with initial data..."
php artisan db:seed --force

# Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "✅ Laravel setup completed successfully!"
echo "🌐 Starting Laravel development server..."

# Start the server
php artisan serve --host=0.0.0.0 --port=8000