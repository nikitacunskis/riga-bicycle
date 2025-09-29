#!/bin/sh
set -e

# allow git in bind-mounted /app
git config --global --add safe.directory /app || true

# ensure required dirs (including logs)
mkdir -p storage/{app,framework/{cache,sessions,views},logs} bootstrap/cache
# create (or truncate) the log file so Laravel can append
: > storage/logs/laravel.log

# permissions (best-effort in dev)
if [ "$(id -u)" -eq 0 ]; then
  chown -R www-data:www-data storage bootstrap/cache || true
  chmod -R ug+rwX storage bootstrap/cache || true
fi

# .env + APP_KEY bootstrap
[ -f .env ] || cp .env.example .env || true
if ! grep -q '^APP_KEY=' .env || grep -q '^APP_KEY=$' .env; then
  echo "Generating application key..."
  php artisan key:generate || true
fi

# install vendors on boot if missing (bind mount means host gets /vendor)
if [ ! -d vendor ]; then
  composer install --no-interaction --prefer-dist
fi

# clear caches (ok if app not fully ready yet)
php artisan config:clear || true
php artisan route:clear  || true
php artisan view:clear   || true

# start services (matches Dockerfile COPY destination)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
