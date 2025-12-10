#!/bin/bash

echo "🚀 Setting up Epic Blog Application..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker first."
    exit 1
fi

# Copy environment files if they don't exist
if [ ! -f .env ]; then
    echo "📄 Creating root .env file..."
    cp .env.example .env
fi

if [ ! -f backend/app/.env ]; then
    echo "📄 Creating backend .env file..."
    cp backend/.env.example backend/app/.env
fi

if [ ! -f frontend/app/.env ]; then
    echo "📄 Creating frontend .env file..."
    cp frontend/.env.example frontend/app/.env
fi

# Build and start containers
echo "🐳 Building and starting Docker containers..."
docker-compose up -d --build

# Wait for containers to be healthy
echo "⏳ Waiting for database to be ready..."
sleep 10

# Fix permissions
echo "🔧 Fixing Laravel storage permissions..."
docker exec epic-blog-app bash -c "chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache"

# Generate application key
echo "🔑 Generating Laravel application key..."
docker exec epic-blog-app php artisan key:generate

# Run migrations
echo "📊 Running database migrations..."
docker exec epic-blog-app php artisan migrate

# Install Sanctum
echo "🔐 Installing Laravel Sanctum..."
docker exec epic-blog-app composer require laravel/sanctum
docker exec epic-blog-app php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
docker exec epic-blog-app php artisan migrate

# Clear caches
echo "🧹 Clearing caches..."
docker exec epic-blog-app php artisan config:clear
docker exec epic-blog-app php artisan cache:clear
docker exec epic-blog-app php artisan route:clear

# Restart containers
echo "🔄 Restarting containers..."
docker-compose restart

echo ""
echo "✅ Setup complete!"
echo ""
echo "📍 Access your application:"
echo "   Frontend: http://localhost:3000"
echo "   Backend:  http://localhost:8000"
echo "   Database: localhost:3306"
echo ""
echo "📝 Useful commands:"
echo "   View logs:           docker-compose logs -f"
echo "   Stop containers:     docker-compose down"
echo "   Start containers:    docker-compose up -d"
echo "   Laravel shell:       docker exec -it epic-blog-app bash"
echo "   Nuxt shell:          docker exec -it epic-blog-frontend sh"
echo ""
