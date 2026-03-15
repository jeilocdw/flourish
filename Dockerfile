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

# Copy production env file (without APP_KEY - we'll generate it)
RUN echo "APP_NAME=\"Flourish Supermarket\"\nAPP_ENV=production\nAPP_KEY=\nAPP_DEBUG=true\nAPP_URL=https://flourish-mtgv.onrender.com\n\nDB_CONNECTION=pgsql\nDB_HOST=dpg-d6qomga4d50c73bh5b5g-a\nDB_PORT=5432\nDB_DATABASE=flourish-pos\nDB_USERNAME=flourish_pos_user\nDB_PASSWORD=wGcnEzkOGfX6jwTbB4yv6RTMP445p9dk\n\nSESSION_DRIVER=file" > /var/www/html/.env

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate APP_KEY using artisan
RUN php /var/www/html/artisan key:generate

# Run migrations
RUN php /var/www/html/artisan migrate --force

# Set proper permissions for storage and bootstrap
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port
EXPOSE 8080

# Start Apache
CMD ["apache2-foreground"]
