compose = docker compose
artisan = $(compose) exec app php artisan
php     = $(compose) exec app php

up:
	$(compose) up -d --build

ps:
	$(compose) ps

migrate:
	$(artisan) migrate --force

seed:
	$(artisan) db:seed --force

cache-clear:
	$(artisan) config:clear && $(artisan) cache:clear

tinker:
	$(artisan) tinker

pdo:
	$(php) -m | grep -i pdo_mysql || (echo "pdo_mysql missing" && exit 1)
