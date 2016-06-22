SHELL = /bin/bash

.PHONY: env install

env:
	@echo "Copying default settings for the containers.."
	@cp containers/templates/.env.sh.template containers/.env.sh
	@echo "Do not forget to set your Github token in 'containers/.env.sh'"

install:
	@echo "Generating docker-compose.yml..."
	@source containers/.env.sh; rm -f containers/docker-compose.yml; CONTAINER_VARS='$$CONTAINERS_PREFIX:$$MYSQL_ROOT_PASSWORD:$$MYSQL_USER:$$MYSQL_PASSWORD:$$MYSQL_DATABASE'; envsubst "$$CONTAINER_VARS" < "containers/templates/docker-compose.yml.template" > "containers/docker-compose.yml";
	@echo "Generating configuration for the 'dev' image/container/application..."
	@source containers/.env.sh; rm -f containers/images/dev/Dockerfile; CONTAINER_VARS='$$DEV_USER_ID:$$DEV_GROUP_ID:$$DEV_USER'; envsubst "$$CONTAINER_VARS" < "containers/images/dev/templates/Dockerfile.template" > "containers/images/dev/Dockerfile";
	@source containers/.env.sh; rm -f containers/images/dev/config/group.sh; CONTAINER_VARS='$$DEV_GROUP_ID:$$DEV_USER'; envsubst "$$CONTAINER_VARS" < "containers/images/dev/templates/group.sh.template" > "containers/images/dev/config/group.sh";
	@source containers/.env.sh; rm -f containers/images/dev/config/.bashrc; CONTAINER_VARS='$$DEV_HOSTNAME'; envsubst "$$CONTAINER_VARS" < "containers/images/dev/templates/.bashrc.template" > "containers/images/dev/config/.bashrc";
	@source containers/.env.sh; rm -f containers/images/dev/config/auth.json; CONTAINER_VARS='$$GITHUB_TOKEN'; envsubst "$$CONTAINER_VARS" < "containers/images/dev/templates/auth.json.template" > "containers/images/dev/config/auth.json";
	@source containers/.env.sh; rm -f applications/setup/.env; CONTAINER_VARS='$$APP_ENV:$$MYSQL_USER:$$MYSQL_PASSWORD:$$MYSQL_HOST:$$MYSQL_DATABASE'; envsubst "$$CONTAINER_VARS" < "containers/images/dev/templates/.env.template" > "applications/setup/.env";
	@cp containers/config/php.ini containers/images/dev/config/php.ini
	@echo "Generating configuration for the 'web' image/container/application..."
	@source containers/.env.sh; rm -f containers/images/web/config/entrypoint.sh; CONTAINER_VARS='$$DEV_GROUP_ID:$$DEV_USER'; envsubst "$$CONTAINER_VARS" < "containers/images/web/templates/entrypoint.sh.template" > "containers/images/web/config/entrypoint.sh";
	@source containers/.env.sh; rm -f containers/images/web/config/attendance.conf; CONTAINER_VARS='$$APP_ENV:$$MYSQL_USER:$$MYSQL_PASSWORD:$$MYSQL_HOST'; envsubst "$$CONTAINER_VARS" < "containers/images/web/templates/attendance.conf.template" > "containers/images/web/config/attendance.conf";
	@cp containers/config/php.ini containers/images/web/config/php.ini
	@echo "Generating configuration for the 'console' image/container/application..."
	@source containers/.env.sh; rm -f applications/console/.env; CONTAINER_VARS='$$APP_ENV:$$MYSQL_USER:$$MYSQL_PASSWORD:$$MYSQL_HOST:$$MYSQL_DATABASE'; envsubst "$$CONTAINER_VARS" < "containers/images/console/templates/.env.template" > "applications/console/.env";
	@cp containers/config/php.ini containers/images/console/config/php.ini
	@echo "Building containers..."
	@docker-compose -f containers/docker-compose.yml up -d
