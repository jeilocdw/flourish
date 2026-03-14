# Render Deployment Guide

Since Render doesn't support PHP via render.yaml directly, follow these manual deployment steps:

## Option 1: Using Dockerfile (Recommended)

### Step 1: Deploy Database

1. Go to [Render Dashboard](https://dashboard.render.com)
2. Click "New" → "PostgreSQL" (or MySQL if available)
3. Configure:
   - **Name**: flourish-db
   - **Plan**: Free
4. Click "Create Database"

### Step 2: Deploy Web Service

1. In Render Dashboard, click "New" → "Web Service"
2. Connect your GitHub repository
3. Configure:
   - **Name**: flourish-pos
   - **Region**: Choose closest to you
   - **Build Command**: Leave empty
   - **Start Command**: Leave empty
4. Scroll down to "Docker"
5. Enable "Docker"
6. Click "Create Web Service"

The Dockerfile will automatically build and deploy your PHP application.

---

## Option 2: Manual Deployment (Buildpack)

### Step 1: Deploy Database

1. Go to [Render Dashboard](https://dashboard.render.com)
2. Click "New" → "PostgreSQL"
3. Configure and create

### Step 2: Deploy Web Service

1. Click "New" → "Web Service"
2. Connect GitHub repository
3. Configure:
   - **Name**: flourish-pos
   - **Region**: Oregon (or closest)
   - **Environment**: PHP
   - **Build Command**: 
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
   - **Start Command**:
   ```bash   php -S 0.0.0.0:$PORT -t public
   ```

---

## Environment Variables

After deployment, add these environment variables in your web service settings:

| Variable | Value |
|----------|-------|
| APP_ENV | production |
| APP_DEBUG | false |
| LOG_CHANNEL | stderr |
| DB_CONNECTION | pgsql (or mysql) |
| DB_HOST | (from database connection) |
| DB_PORT | 5432 (or 3306 for MySQL) |
| DB_DATABASE | (database name) |
| DB_USERNAME | (username) |
| DB_PASSWORD | (password) |

---

## Alternative: Use Coolify (Easier)

Consider using [Coolify](https://coolify.io) instead - it's a self-hosted alternative that supports Laravel out of the box with easier deployment:

1. Install Coolify on a VPS
2. Add your GitHub repository
3. Deploy with one click!

---

## Troubleshooting

### 500 Error
- Check logs in Render dashboard
- Run `php artisan config:clear` in shell

### Database Connection Error
- Verify DB_* environment variables are correct
- Ensure database is in same region as web service

### Assets Not Loading
- Run `php artisan storage:link` in shell
- Check APP_URL is correct
