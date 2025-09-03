#!/bin/sh
set -e

# Generate application encryption key if not present
if [ "$(tail -c 1 .env | wc -l)" -eq 0 ]; then
    echo >>.env
fi
if ! grep -q ^APP_KEY= .env; then
    echo APP_KEY= >>.env
fi
if grep -q '^APP_KEY=$' .env; then
    echo "Generating application key..."
    ./artisan key:generate
fi

# Ensure storage directories exist and have correct ownership
mkdir -p storage/framework/views
if [ "$(id -u)" -eq 0 ]; then
    chown -R www-data storage
fi

exec "$@"
