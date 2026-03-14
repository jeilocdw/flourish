# Installation Guide

## Local Development (XAMPP/WAMP)

### Step 1: Install Dependencies

Make sure you have PHP 8.2+, Composer, and Node.js installed.

### Step 2: Clone and Install

```bash
git clone <repository-url> flourish
cd flourish
composer install
npm install
```

### Step 3: Configure Environment

```bash
cp .env.example .env
```

Edit `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flourish_pos
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 4: Generate Key and Migrate

```bash
php artisan key:generate
php artisan migrate
php artisan storage:link
```

### Step 5: Start Server

```bash
php artisan serve
```

Visit `http://localhost:8000`

---

## Shared Hosting (cPanel)

### Step 1: Upload Files

1. Create a MySQL database and user
2. Upload all files to `public_html` or your domain's web directory

### Step 2: Configure .env

Edit `.env` file with your database credentials

### Step 3: Run Migrations

Access your site via browser and migrations should run automatically, or run via SSH:

```bash
php artisan migrate
php artisan storage:link
```

### Step 4: Set Permissions

- `storage/` - 755
- `bootstrap/cache/` - 755

---

## Vercel Deployment

### Option 1: Git Integration

1. Push your code to GitHub
2. Go to [Vercel Dashboard](https://vercel.com/dashboard)
3. Import your repository
4. Add environment variables:
   - `DB_CONNECTION`
   - `DB_HOST`
   - `DB_PORT`
   - `DB_DATABASE`
   - `DB_USERNAME`
   - `DB_PASSWORD`
5. Deploy!

### Option 2: Vercel CLI

```bash
npm i -g vercel
vercel
```

### Note for Vercel

Vercel is serverless, so database must be hosted separately (e.g., MySQL databases on cloud providers like DigitalOcean, AWS, or MySQL services like PlanetScale).

---

## DigitalOcean App Platform

1. Connect your GitHub repository
2. Configure:
   - Build Command: `composer install --no-dev && npm run build`
   - Run Command: `php artisan serve --host=0.0.0.0 --port=$PORT`
3. Add environment variables for database
4. Create a MySQL database in DigitalOcean

---

## Docker Deployment

Create `Dockerfile`:

```dockerfile
FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    git \
    && docker-php-ext-configure gd \
    && docker-php-ext-install gd pdo pdo_mysql zip

COPY . /var/www/html
COPY .env.example .env

WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader
RUN php artisan key:generate
RUN php artisan storage:link

RUN chmod -R 755 storage bootstrap/cache

EXPOSE 8080
```

Build and run:

```bash
docker build -t flourish-pos .
docker run -p 8080:80 flourish-pos
```

---

## Database Seeding (Optional)

```bash
php artisan db:seed
```

Creates default admin user:
- Email: admin@example.com
- Password: admin123

---

## Troubleshooting

### 500 Error on Shared Hosting

1. Check `storage/logs/` for errors
2. Ensure `.env` is properly configured
3. Run `php artisan config:clear`
4. Set `APP_DEBUG=true` temporarily to see errors

### Storage Link Error

```bash
php artisan storage:link
```

Or manually create symbolic link in `public/` pointing to `storage/app/public`

### Permission Denied

```bash
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage
```
