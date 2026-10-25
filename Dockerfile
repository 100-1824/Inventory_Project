FROM php:8.3-apache

# System deps + PHP extensions Laravel + MySQL need
RUN apt-get update && apt-get install -y \
        git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP deps first (better layer caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# App code
COPY . .

RUN composer dump-autoload --optimize --no-dev

# Apache should serve Laravel's public/ as webroot
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf

# Debian's default apache2.conf sets AllowOverride None for /var/www/, which
# makes Laravel's public/.htaccess rewrite rules (needed for every route
# except "/") silently do nothing, producing Apache's own 404 instead of
# reaching index.php. Allow overrides for the webroot explicitly.
RUN printf '<Directory /var/www/html/public>\n\tAllowOverride All\n\tRequire all granted\n</Directory>\n' \
    > /etc/apache2/conf-available/laravel-htaccess.conf \
    && a2enconf laravel-htaccess

# Writable dirs Laravel needs at runtime
RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

# Run migrations then start Apache. Render's shell/console can also run
# `php artisan migrate --force` manually if you'd rather control timing.
CMD php artisan config:cache && php artisan route:cache && php artisan view:cache \
    && php artisan migrate --force \
    && apache2-foreground
