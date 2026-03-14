# Render Deployment Guide

Render is a cloud platform that offers free tiers and easy Laravel deployment with integrated MySQL database.

## Prerequisites

- GitHub, GitLab, or Bitbucket account
- Render account

## Step 1: Prepare Your Laravel Application

### 1.1 Update composer.json

Add the Render-compatible scripts:

```json
"scripts": {
    "post-install-cmd": [
        "Illuminate\\Foundation\\ComposerScripts::postInstall",
        "@php artisan key:generate --force",
        "@php artisan config:cache --force"
    ],
    "post-autoload-dump": [
        "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
        "@php artisan package:discover --ansi"
    ]
}
```

### 1.2 Update .env for Production

In `config/database.php`, ensure the connection uses `env()`:

```php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    // ...
],
```

### 1.3 Create render.yaml

Create `render.yaml` in project root:

```yaml
services:
  - type: web
    name: flourish-pos
    env: php
    buildCommand: |
      composer install --no-dev --optimize-autoloader
      npm install && npm run build
    startCommand: |
      php artisan migrate --force
      php artisan config:cache
      php artisan serve --host=0.0.0.0 --port=$PORT
    envVars:
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: false
      - key: APP_KEY
        generateValue: true
      - key: DB_CONNECTION
        value: mysql
      - key: DB_HOST
        fromDatabase:
          name: flourish-db
          property: host
      - key: DB_PORT
        fromDatabase:
          name: flourish-db
          property: port
      - key: DB_DATABASE
        fromDatabase:
          name: flourish-db
          property: name
      - key: DB_USERNAME
        fromDatabase:
          name: flourish-db
          property: username
      - key: DB_PASSWORD
        fromDatabase:
          name: flourish-db
          property: password

databases:
  - name: flourish-db
    plan: free
    mysqlVersion: "8.0"
```

## Step 2: Push to GitHub

```bash
git add .
git commit -m "Prepare for Render deployment"
git push origin main
```

## Step 3: Deploy on Render

### Option A: Using render.yaml (Recommended)

1. Go to [Render Dashboard](https://dashboard.render.com)
2. Click "New" → "Blueprint"
3. Connect your GitHub repository
4. Select the `render.yaml` file
5. Click "Apply Blueprint"

### Option B: Manual Setup

1. Go to [Render Dashboard](https://dashboard.render.com)
2. Click "New" → "Web Service"
3. Connect your GitHub repository
4. Configure:
   - **Name**: flourish-pos
   - **Environment**: PHP
   - **Build Command**: `composer install --no-dev --optimize-autoloader && npm install && npm run build`
   - **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`
5. Click "Create Web Service"

### Step 4: Create Database

1. In Render Dashboard, click "New" → "PostgreSQL" or "MySQL"
2. Configure:
   - **Name**: flourish-db
   - **Plan**: Free (or paid for more features)
3. Copy the connection details

### Step 5: Connect Database

1. Go to your web service
2. Click "Environment"
3. Add database environment variables:
   - `DB_CONNECTION`: mysql
   - `DB_HOST`: (from database connection string)
   - `DB_PORT`: 3306
   - `DB_DATABASE`: (database name)
   - `DB_USERNAME`: (username)
   - `DB_PASSWORD`: (password)

### Step 6: Run Migrations

1. Go to your web service
2. Click "Shell"
3. Run:

```bash
php artisan migrate --force
php artisan storage:link
```

## Step 7: Configure Custom Domain (Optional)

1. Go to your web service settings
2. Click "Custom Domains"
3. Add your domain
4. Update DNS records as shown

## Troubleshooting

### 500 Error After Deployment

1. Check logs: Click "Logs" in Render dashboard
2. Common fixes:

```bash
# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Rebuild cache
php artisan config:cache
php artisan route:cache
```

### Database Connection Error

Ensure environment variables are correctly set:
- `DB_HOST` should be the internal hostname (e.g., `flourish-db.internal`)
- Don't use localhost - must be Render's internal network

### Asset Loading Issues

Update `config/app.php`:

```php
'url' => env('APP_URL', 'https://your-app.onrender.com'),
'asset_url' => env('ASSET_URL', 'https://your-app.onrender.com'),
```

## Free Tier Limitations

- **Web Service**: Sleeps after 15 minutes of inactivity (can take ~30s to wake up)
- **Database**: Free tier sleeps after 90 days of inactivity
- **Bandwidth**: 100GB/month

## Quick Deploy Commands

If you need to run commands on Render:

```bash
# Via Render Shell
php artisan migrate --force
php artisan db:seed
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Environment Variables Reference

| Variable | Value |
|----------|-------|
| APP_ENV | production |
| APP_DEBUG | false |
| APP_URL | https://your-app.onrender.com |
| DB_CONNECTION | mysql |
| LOG_CHANNEL | stderr |
| SESSION_DRIVER | database |

## Security Checklist

Before going live:

- [ ] Set `APP_DEBUG=false`
- [ ] Use strong `APP_KEY`
- [ ] Configure HTTPS
- [ ] Set up database backups
- [ ] Configure CORS if needed
- [ ] Review all environment variables
