# Prepare base backend
FROM php:8.1-fpm AS base
WORKDIR /app
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY app app/
COPY artisan composer.json ./
COPY bootstrap bootstrap/
COPY config config/
COPY routes routes/
COPY database database/
COPY public public/
COPY resources resources/
COPY composer.lock .
RUN apt-get update && \
    apt-get install -y unzip && \
    docker-php-ext-install pdo_mysql && \
    composer install && \
    rm -rf /usr/local/bin/composer /var/lib/apt/lists/*

# Build frontend
FROM base AS node
COPY package.json ./
COPY postcss.config.js tailwind.config.js vite.config.js ./
COPY resources resources/
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && \
    apt-get install -y nodejs && \
    npm install && \
    npm run build && \
    apt-get autopurge -y nodejs && \
    rm -rf /var/lib/apt/lists/*

# Add nginx, Supervisor, PHP MySQL extension and built frontend assets to backend
FROM base AS final
RUN apt-get update && \
    apt-get install -y nginx supervisor && \
    rm -rf /var/lib/apt/lists/* && \
    chmod 777 /var/lib/nginx /var/run
COPY --from=node /app/public public/
COPY docker/fpm.conf /usr/local/etc/php-fpm.d/zz-docker.conf
COPY docker/nginx.conf /etc/nginx/
COPY docker/start.sh ./
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY public/img public/img/
COPY public/index.php public/
COPY resources/views resources/views/
CMD ["./start.sh"]
