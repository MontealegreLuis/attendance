SHELL = /bin/bash

.PHONY: install start

install:
	@echo "Generating auth.json file for composer"
	@sed -e s/{{GITHUB_TOKEN}}/$(GITHUB_TOKEN)/ applications/setup/config/auth.dist.json > applications/setup/config/auth.json
	@echo "Copying development configuration to the console application"
	@cp build/templates/.env.dev applications/console/.env
	@cp build/templates/config.dev.php applications/console/config.php
	@echo "Building console container"
	@docker-compose -f applications/console/docker-compose.yml up
	@echo "Building web container"
	@docker-compose -f applications/web/docker-compose.yml up -d
	@echo "Building setup container"
	@docker-compose -f applications/setup/docker-compose.yml up

start:
	@echo "Starting database containers..."
	@docker-compose -f build/containers/docker-compose.yml up -d
	@echo "Load aliases"
	@source .alias
