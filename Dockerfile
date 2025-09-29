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
    apt-get install -y git unzip libicu-dev \
        libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
        libzip-dev zip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j"$(nproc)" intl pdo_mysql gd zip && \
    docker-php-ext-enable intl && \
    mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache && \
    rm -rf /var/lib/apt/lists/*docke


# Build frontend
FROM base AS node
COPY package.json ./
COPY package-lock.json ./
COPY postcss.config.js tailwind.config.js vite.config.js ./
COPY resources resources/
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && \
    apt-get install -y nodejs && \
    npm install --legacy-peer-deps && \
    npm run build && \
    apt-get autopurge -y nodejs && \
    rm -rf /var/lib/apt/lists/*

# Add nginx, Supervisor, PHP-FPM config and built frontend assets
FROM base AS final
# Keep Composer also in runtime container for dev usage
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN apt-get update && \
    apt-get install -y nginx supervisor && \
    rm -rf /var/lib/apt/lists/* && \
    chmod 777 /var/lib/nginx /var/run

COPY --from=node /app/public public/
COPY docker/fpm.conf /usr/local/etc/php-fpm.d/zz-docker.conf
COPY docker/nginx.conf /etc/nginx/
COPY docker/start.sh /usr/local/bin/start.sh
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Normalize line endings and ensure executability
RUN sed -i 's/\r$//' /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

COPY public/img public/img/
COPY public/index.php public/
COPY resources/views resources/views/

CMD ["start.sh"]
