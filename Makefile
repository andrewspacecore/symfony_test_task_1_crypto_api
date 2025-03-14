CONTAINER_PHP=app_dev

# cp docker-compose.dev.yml docker-compose.dev.dist.yml
help: ## Print help
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n\nTargets:\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2 }' $(MAKEFILE_LIST)
build-dev: ## Build and start all containers (dev)
	@docker compose -f docker-compose.dev.yml up --force-recreate -d --build
start-dev: ## Start all containers (dev)
	@docker compose -f docker-compose.dev.yml up --force-recreate -d
stop-dev: ## Stop all containers
	@docker compose -f docker-compose.dev.yml stop
restart-dev: ## Restart all containers
	@make stop-dev && make start-dev
clean-dev: ## Stop and remove containers, networks, and volumes (dev)
	@docker compose -f docker-compose.dev.yml down -v
logs: ## Tail logs of all containers
	@docker compose -f docker-compose.dev.yml logs -f
bash: ## Bash into PHP container
	docker exec -it ${CONTAINER_PHP} bash
migrate-run: ## Migrate run
	bin/console doctrine:migrations:migrate
fixture-run: ## Fixture run
	bin/console doctrine:fixtures:load
command-crypto-price: ## Start command crypto price
	sh ./docker/dev/cron/check-crypto-price-command.sh

