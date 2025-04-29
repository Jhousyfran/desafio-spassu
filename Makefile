# Makefile for managing Docker Compose and Laravel commands

# Default target
DOCKER_COMPOSE := $(shell command -v docker-compose || echo "docker compose")

.PHONY: up
build-app:
	$(DOCKER_COMPOSE) up -d
	$(DOCKER_COMPOSE) exec app-api cp .env.example .env
	$(DOCKER_COMPOSE) exec app-api composer install
	$(DOCKER_COMPOSE) exec app-api php artisan key:generate
	sleep 8
	$(DOCKER_COMPOSE) exec app-api php artisan migrate
	$(DOCKER_COMPOSE) exec app-api php artisan db:seed
up:
	$(DOCKER_COMPOSE) up -d

.PHONY: down
down:
	$(DOCKER_COMPOSE) down

.PHONY: restart
restart:
	$(DOCKER_COMPOSE) down && $(DOCKER_COMPOSE) up -d

.PHONY: artisan
artisan:
	$(DOCKER_COMPOSE) exec app-api php artisan $(cmd)

.PHONY: migrate
migrate:
	$(DOCKER_COMPOSE) exec app-api php artisan migrate

.PHONY: seed
seed:
	$(DOCKER_COMPOSE) exec app-api php artisan db:seed

.PHONY: composer-install
composer-install:
	$(DOCKER_COMPOSE) exec app-api composer install

.PHONY: test
test:
	$(DOCKER_COMPOSE) exec app-api php artisan test