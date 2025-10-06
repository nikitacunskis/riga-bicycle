# Prepare base backend
FROM php:8.1-fpm AS base
WORKDIR /app

# Keep Composer available in all stages
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Copy application sources needed for composer install and build
COPY app app/
COPY artisan composer.json ./
COPY bootstrap bootstrap/
COPY config config/
COPY routes routes/
COPY database database/
COPY public public/
COPY resources resources/
COPY composer.lock .

# PHP extensions + tools Composer relies on
# PHP extensions + tools Composer relies on
RUN apt-get update && \
    apt-get install -y git unzip libicu-dev libxml2-dev \
        libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
        libzip-dev zip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j"$(nproc)" intl pdo_mysql gd zip dom && \
    docker-php-ext-enable intl && \
    mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache && \
    rm -rf /var/lib/apt/lists/*


# Build frontend
FROM base AS node
COPY package.json ./
COPY package-lock.json ./
COPY postcss.config.js tailwind.config.js vite.config.js ./
COPY resources resources/
RUN apt-get update && apt-get install -y curl && \
    curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && \
    apt-get install -y nodejs && \
    npm install --legacy-peer-deps && \
    npm run build && \
    apt-get autopurge -y nodejs && \
    rm -rf /var/lib/apt/lists/*

# Add nginx, Supervisor, PHP-FPM config and built frontend assets
FROM base AS final
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN apt-get update && \
    apt-get install -y nginx supervisor && \
    rm -rf /var/lib/apt/lists/* && \
    chmod 777 /var/lib/nginx /var/run

# Bring built assets and configs
COPY --from=node /app/public public/
COPY docker/fpm.conf /usr/local/etc/php-fpm.d/zz-docker.conf
COPY docker/nginx.conf /etc/nginx/
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# --- NEW: runtime entrypoint created from Dockerfile ---
# This runs INSIDE the container every start, even with your bind/volume mounts.
RUN printf '%s\n' \
  '#!/bin/sh' \
  'set -e' \
  'cd /app' \
  '# ensure storage tree exists even when volume is empty' \
  'mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache' \
  'touch storage/logs/laravel.log' \
  'chown -R www-data:www-data storage bootstrap/cache' \
  'chmod -R 775 storage bootstrap/cache || true' \
  '# optional: ensure env + key if dev' \
  '[ -f .env ] || cp .env.example .env 2>/dev/null || true' \
  'php -v >/dev/null 2>&1 || true' \
  'php artisan config:cache 2>/dev/null || true' \
  'php artisan route:cache 2>/dev/null || true' \
  'exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf' \
  > /usr/local/bin/entrypoint.sh && \
  chmod +x /usr/local/bin/entrypoint.sh

# Normalize line endings if needed (Windows checkouts)
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh

# Use the entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
