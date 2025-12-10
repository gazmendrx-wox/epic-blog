# Epic Blog - Docker Setup

## 📦 Project Structure

```
epic-blog/
├── backend/          # Laravel application
├── frontend/         # Nuxt.js application
├── nginx/            # Nginx configuration
├── docker-compose.yml
└── .env
```

## 🚀 Quick Start

### Option 1: Automated Setup (Recommended)

```bash
# Run the setup script
bash setup.sh
```

### Option 2: Manual Setup

```bash
# 1. Copy environment files
cp .env.example .env
cp backend/.env.example backend/app/.env
cp frontend/.env.example frontend/app/.env

# 2. Start Docker containers
docker-compose up -d --build

# 3. Fix permissions
docker exec epic-blog-app bash -c "chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache"

# 4. Generate Laravel application key
docker exec epic-blog-app php artisan key:generate

# 5. Run migrations
docker exec epic-blog-app php artisan migrate

# 6. Install Sanctum
docker exec epic-blog-app composer require laravel/sanctum
docker exec epic-blog-app php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
docker exec epic-blog-app php artisan migrate
```

### 2. Access the Application

- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000
- **Database**: localhost:3306

## 🛠️ Development Commands

### Docker Commands

```bash
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# View logs
docker-compose logs -f

# View specific service logs
docker-compose logs -f app
docker-compose logs -f frontend

# Rebuild containers
docker-compose up -d --build

# Remove all containers and volumes (fresh start)
docker-compose down -v

# Access container shells
docker exec -it epic-blog-app bash
docker exec -it epic-blog-frontend sh
```

### Laravel Commands

```bash
# Access Laravel container
docker exec -it epic-blog-app bash

# Run migrations
docker exec epic-blog-app php artisan migrate

# Create migration
docker-compose exec app php artisan make:migration create_posts_table

# Run seeders
docker-compose exec app php artisan db:seed

# Clear cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear

# Run queue worker (already running in background)
docker-compose exec app php artisan queue:work

# Create controller
docker-compose exec app php artisan make:controller PostController --api

# Create model
docker-compose exec app php artisan make:model Post -m

# Create service
docker-compose exec app php artisan make:class Services/PostService

# Run tests
docker-compose exec app php artisan test
```

### Nuxt Commands

```bash
# Access Nuxt container
docker-compose exec frontend sh

# Run development server
docker-compose exec frontend npm run dev

# Build for production
docker-compose exec frontend npm run build

# Run linter
docker-compose exec frontend npm run lint
```

## 📋 Services Overview

| Service   | Container Name      | Port | Description                |
|-----------|---------------------|------|----------------------------|
| app       | epic-blog-app       | -    | Laravel PHP-FPM            |
| web       | epic-blog-web       | 8000 | Nginx web server           |
| db        | epic-blog-db        | 3306 | MySQL database             |
| redis     | epic-blog-redis     | 6379 | Redis cache & queue        |
| queue     | epic-blog-queue     | -    | Laravel queue worker       |
| frontend  | epic-blog-frontend  | 3000 | Nuxt.js application        |

## 🔧 Configuration

### Environment Variables

Edit `.env` in the root directory to change:
- Database credentials
- Application ports
- Redis configuration

### Laravel Configuration

Edit `backend/.env` to configure:
- Database connection
- Queue driver
- Mail settings
- Sanctum settings
- CORS settings

### Nuxt Configuration

Edit `frontend/.env` to configure:
- API base URL

## 🐛 Troubleshooting

### Permission Issues (Linux/Mac)

```bash
# Fix storage permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Database Connection Issues

```bash
# Check if database is ready
docker-compose exec db mysql -u epic_user -p epic_blog

# Restart database
docker-compose restart db
```

### Clear Everything and Start Fresh

```bash
# Remove all containers, volumes, and images
docker-compose down -v
docker-compose up -d --build

# Reinstall dependencies
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate:fresh --seed
```

## 📝 Notes

- The queue worker runs automatically in the background
- Redis is used for caching and sessions
- Nginx serves the Laravel API on port 8000
- Nuxt runs in production mode by default
- Database data persists in Docker volumes
- Hot reload is enabled for both Laravel and Nuxt during development

## 🔐 Security

For production:
1. Change all default passwords
2. Set `APP_DEBUG=false` in backend/.env
3. Configure proper CORS settings
4. Use environment-specific .env files
5. Enable HTTPS with proper SSL certificates
