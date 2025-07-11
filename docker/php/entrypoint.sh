#!/usr/bin/env bash
set -e

composer install

# --- 1) Ensure .env & APP_KEY
if [ ! -f .env ]; then
  cp .env.example .env
fi
if ! grep -q '^APP_KEY=' .env | grep -vq '^APP_KEY=$'; then
  php artisan key:generate
fi

# --- 2) Install PHP deps
composer install --no-interaction --prefer-dist --optimize-autoloader

# --- 3) Run migrations & seeders
php artisan migrate --force --seed

# --- 4) Launch built-in server
php artisan serve --host=0.0.0.0 --port=8000
