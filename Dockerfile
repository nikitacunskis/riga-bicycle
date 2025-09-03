# Prepare base backend
FROM php:8.3-fpm AS base
WORKDIR /app
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY app app/
COPY artisan composer.json ./
COPY bootstrap bootstrap/
COPY config config/
COPY routes routes/
RUN apt-get update && \
    apt-get install -y unzip && \
    docker-php-ext-install pdo_mysql && \
    composer install && \
    rm -fr /usr/local/bin/composer /var/lib/apt/lists/*

# Build frontend
FROM node AS node
WORKDIR /app
COPY --from=base /app/vendor vendor/
COPY package.json ./
COPY postcss.config.js tailwind.config.js vite.config.js ./
COPY resources resources/
RUN npm install && \
    npm run build

# Add nginx, Supervisor, PHP MySQL extension and built frontend assets to backend
FROM base
RUN apt-get update && \
    apt-get install -y nginx supervisor && \
    chmod 777 /var/lib/nginx /var/run && \
    ln -fs /dev/fd/1 /var/log/nginx/access.log && \
    ln -fs /dev/fd/2 /var/log/nginx/error.log && \
    rm -fr /var/lib/apt/lists/*
COPY --from=node /app/public public/
COPY docker/entrypoint.sh ./
COPY docker/nginx-server.conf /etc/nginx/sites-enabled/default
COPY docker/nginx.conf /etc/nginx/conf.d/
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/zz-docker.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY public/img public/img/
COPY public/index.php public/
COPY resources/views resources/views/
ENTRYPOINT [ "./entrypoint.sh" ]
CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
