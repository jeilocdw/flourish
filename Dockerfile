# Dockerfile for Render deployment
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    git \
    curl \
    libpq-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache to serve from public directory
RUN echo "<VirtualHost *:80>" > /etc/apache2/sites-available/000-default.conf
RUN echo "    ServerName localhost" >> /etc/apache2/sites-available/000-default.conf
RUN echo "    DocumentRoot /var/www/html/public" >> /etc/apache2/sites-available/000-default.conf
RUN echo "" >> /etc/apache2/sites-available/000-default.conf
RUN echo "    <Directory /var/www/html/public>" >> /etc/apache2/sites-available/000-default.conf
RUN echo "        Options -Indexes +FollowSymLinks" >> /etc/apache2/sites-available/000-default.conf
RUN echo "        AllowOverride All" >> /etc/apache2/sites-available/000-default.conf
RUN echo "        Require all granted" >> /etc/apache2/sites-available/000-default.conf
RUN echo "    </Directory>" >> /etc/apache2/sites-available/000-default.conf
RUN echo "</VirtualHost>" >> /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Copy production env file (with fixed APP_KEY for session persistence)
RUN cat > /var/www/html/.env <<'EOF'
APP_NAME="Flourish Supermarket"
APP_ENV=production
APP_KEY=base64:vmNgffQozcNVgi4ByvQdcK39pxEOZw3CudqlXq1nNa8=
APP_DEBUG=true
APP_URL=https://flourish-mtgv.onrender.com

DB_CONNECTION=pgsql
DB_HOST=dpg-d6qomga4d50c73bh5b5g-a
DB_PORT=5432
DB_DATABASE=flourish-pos
DB_USERNAME=flourish_pos_user
DB_PASSWORD=wGcnEzkOGfX6jwTbB4yv6RTMP445p9dk

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
EOF

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Run migrations (NOT fresh - preserve existing data)
RUN php /var/www/html/artisan migrate --force

# Create session table if not exists
RUN php /var/www/html/artisan session:table || true
RUN php /var/www/html/artisan migrate --force || true

# Create storage directories
RUN php /var/www/html/artisan storage:link
RUN mkdir -p /var/www/html/storage/framework/sessions /var/www/html/storage/framework/views /var/www/html/storage/logs

# Set proper permissions for storage and bootstrap
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port
EXPOSE 8080

# Start Apache
CMD ["apache2-foreground"]
