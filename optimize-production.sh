#!/bin/bash

# 🚀 Laravel Production Optimization Script
# This script optimizes Laravel for production deployment

echo "🚀 Starting Laravel Production Optimization..."

# 1. Composer optimizations
echo "📦 Installing production dependencies..."
composer install --no-dev --optimize-autoloader --classmap-authoritative

# 2. Laravel optimizations
echo "⚡ Running Laravel optimizations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 3. Clear unnecessary caches
echo "🧹 Clearing unnecessary caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4. Re-optimize after clearing
echo "🔄 Re-optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 5. Set proper permissions
echo "🔐 Setting proper permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 6. Vite build for production
echo "🎨 Building frontend assets..."
npm run build

echo "✅ Production optimization completed!"
echo "📋 Next steps:"
echo "   - Enable OPcache in php.ini"
echo "   - Set APP_ENV=production in .env"
echo "   - Set APP_DEBUG=false in .env"
echo "   - Configure web server caching headers"
