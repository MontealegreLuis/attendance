SHELL = /bin/bash

.PHONY: install

install:
	@echo "Generating auth.json file for composer"
	@sed -e s/{{GITHUB_TOKEN}}/$(GITHUB_TOKEN)/ applications/setup/config/auth.dist.json > applications/setup/config/auth.json
    @echo "Copying development .env file to the console application"
    @cp build/templates/.env.dev applications/console/.env
