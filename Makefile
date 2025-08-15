init:
	docker compose up -d --build
	cp src/.env.example src/.env
	docker compose exec php composer install
	docker compose exec php php artisan key:generate
	docker compose exec php php artisan migrate --seed
	docker compose exec php php artisan storage:link

fresh:
	docker compose exec php php artisan migrate:fresh --seed

restart:
	@make down
	@make up

up:
	docker compose up -d

down:
	docker compose down --remove-orphans

cache:
	docker compose exec php php artisan cache:clear
	docker compose exec php php artisan config:cache

stop:
	docker compose stop

set-testing-env:
	cp .env.testing .env
	docker compose exec php php artisan config:clear

restore-env:
	cp .env.backup .env
	docker compose exec php php artisan config:clear
