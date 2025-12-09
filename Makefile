# ------------------------------------------------------------------
# PREFIX & PORTS (loaded from .env if available)
# ------------------------------------------------------------------
-include .env
export

PREFIX?=piiiqcy
WP_PORT?=8000
PMA_PORT?=8080

# ------------------------------------------------------------------
# WORDPRESS SETTINGS
# ------------------------------------------------------------------
WP_URL=http://localhost:$(WP_PORT)
WP_TITLE=test
WP_ADMIN_USER=test
WP_ADMIN_PASSWORD=test
WP_ADMIN_EMAIL=info@example.com
WP_INSTALL_PLUGINS=wp-multibyte-patch

# ===========================================
# Quick Setup (recommended for first time)
# ===========================================
# Full setup: dependencies + docker + wordpress
setup:
	@echo "📦 Installing Node dependencies..."
	pnpm install
	@echo "📦 Installing PHP dependencies..."
	composer install
	@echo "🐳 Creating Docker network..."
	-docker network create --driver bridge $(PREFIX)_network 2>/dev/null || true
	@echo "🚀 Starting Docker containers..."
	export PREFIX="$(PREFIX)" && docker compose up -d --build
	@echo "⏳ Waiting for MySQL to be ready..."
	@export PREFIX="$(PREFIX)" && \
		MAX_ATTEMPTS=30 && \
		ATTEMPT=1 && \
		while [ $$ATTEMPT -le $$MAX_ATTEMPTS ]; do \
			if docker compose run --rm wpcli mysql -h db -u wordpress -pwordpress --skip-ssl -s -N -e "SELECT 1" >/dev/null 2>&1; then \
				echo "✅ MySQL is ready!"; \
				break; \
			fi; \
			echo "   Attempt $$ATTEMPT/$$MAX_ATTEMPTS - MySQL not ready, waiting 2s..."; \
			sleep 2; \
			ATTEMPT=$$((ATTEMPT + 1)); \
		done; \
		if [ $$ATTEMPT -gt $$MAX_ATTEMPTS ]; then \
			echo "❌ MySQL failed to start after $$MAX_ATTEMPTS attempts"; \
			exit 1; \
		fi
	@echo "📝 Installing WordPress..."
	export PREFIX="$(PREFIX)" && docker compose run --rm wpcli wp core install --url='$(WP_URL)' --title='$(WP_TITLE)' --admin_user='$(WP_ADMIN_USER)' --admin_password='$(WP_ADMIN_PASSWORD)' --admin_email='$(WP_ADMIN_EMAIL)' --allow-root
	export PREFIX="$(PREFIX)" && docker compose run --rm wpcli wp language core install ja --activate --allow-root
	export PREFIX="$(PREFIX)" && docker compose run --rm wpcli plugin install $(WP_INSTALL_PLUGINS) --activate --allow-root
	@echo "🎨 Activating theme..."
	export PREFIX="$(PREFIX)" && docker compose run --rm wpcli wp theme activate $(PREFIX) --allow-root
	@echo ""
	@echo "✅ Setup complete!"
	@echo ""
	@echo "📍 URLs:"
	@echo "   WordPress:  http://localhost:$(WP_PORT)"
	@echo "   Admin:      http://localhost:$(WP_PORT)/wp-admin/"
	@echo "   phpMyAdmin: http://localhost:$(PMA_PORT)"
	@echo ""
	@echo "🔑 Login: $(WP_ADMIN_USER) / $(WP_ADMIN_PASSWORD)"
	@echo ""
	@echo "🚀 Next: pnpm dev"

# ===========================================
# Docker Commands
# ===========================================

# docker compose up
up:
	export PREFIX="$(PREFIX)" && docker compose up -d

# docker compose restart (recreate containers to apply .env changes)
restart:
	export PREFIX="$(PREFIX)" && docker compose up -d --force-recreate

# docker compose stop
stop:
	export PREFIX="$(PREFIX)" && docker compose stop

# docker compose down (container remove and volumes delete)
down:
	export PREFIX="$(PREFIX)" && docker compose stop && \
  docker compose down --volumes && \
  docker network rm $(PREFIX)_network

# mysqldump (simple dump without URL conversion)
dbdump:
	docker exec -it $(PREFIX)_db sh -c 'mysqldump wordpress -u wordpress -pwordpress 2> /dev/null' > dump.sql

# ===========================================
# Database Export with URL Conversion
# ===========================================
# Usage:
#   make db-export ENV=prod   - Export for production
#   make db-export ENV=stg    - Export for staging
#   make db-export            - Interactive mode
#
# Configure URLs in .env:
#   URL_LOCAL=http://localhost:8000
#   URL_STG=https://stg.example.com
#   URL_PROD=https://example.com

db-export:
	@if [ -z "$(ENV)" ]; then \
		echo ""; \
		echo "📦 Database Export with URL Conversion"; \
		echo ""; \
		echo "Select target environment:"; \
		echo "  1) Production (prod)"; \
		echo "  2) Staging (stg)"; \
		echo ""; \
		read -p "Enter choice [1-2]: " choice; \
		case $$choice in \
			1) ENV_TARGET=prod ;; \
			2) ENV_TARGET=stg ;; \
			*) echo "❌ Invalid choice"; exit 1 ;; \
		esac; \
	else \
		ENV_TARGET=$(ENV); \
	fi; \
	\
	URL_LOCAL=$$(grep -E "^URL_LOCAL=" .env | cut -d'=' -f2-); \
	URL_STG=$$(grep -E "^URL_STG=" .env | cut -d'=' -f2-); \
	URL_PROD=$$(grep -E "^URL_PROD=" .env | cut -d'=' -f2-); \
	\
	if [ -z "$$URL_LOCAL" ]; then \
		echo "❌ URL_LOCAL not set in .env"; exit 1; \
	fi; \
	\
	case $$ENV_TARGET in \
		prod) \
			if [ -z "$$URL_PROD" ]; then echo "❌ URL_PROD not set in .env"; exit 1; fi; \
			TARGET_URL=$$URL_PROD; \
			OUTPUT_FILE=dump-prod.sql; \
			;; \
		stg) \
			if [ -z "$$URL_STG" ]; then echo "❌ URL_STG not set in .env"; exit 1; fi; \
			TARGET_URL=$$URL_STG; \
			OUTPUT_FILE=dump-stg.sql; \
			;; \
		*) \
			echo "❌ Invalid ENV: $$ENV_TARGET (use 'prod' or 'stg')"; exit 1; \
			;; \
	esac; \
	\
	echo ""; \
	echo "🔄 Converting URLs:"; \
	echo "   From: $$URL_LOCAL"; \
	echo "   To:   $$TARGET_URL"; \
	echo ""; \
	\
	echo "⏳ Waiting for database connection..."; \
	MAX_ATTEMPTS=15; \
	ATTEMPT=1; \
	while [ $$ATTEMPT -le $$MAX_ATTEMPTS ]; do \
		if export PREFIX="$(PREFIX)" && docker compose run --rm wpcli mysql -h db -u wordpress -pwordpress --skip-ssl -s -N -e "SELECT 1" >/dev/null 2>&1; then \
			echo "✅ Database is ready!"; \
			break; \
		fi; \
		echo "   Attempt $$ATTEMPT/$$MAX_ATTEMPTS - waiting 2s..."; \
		sleep 2; \
		ATTEMPT=$$((ATTEMPT + 1)); \
	done; \
	if [ $$ATTEMPT -gt $$MAX_ATTEMPTS ]; then \
		echo "❌ Database connection failed"; \
		exit 1; \
	fi; \
	\
	echo ""; \
	echo "📝 Running search-replace (dry-run)..."; \
	export PREFIX="$(PREFIX)" && docker compose run --rm wpcli search-replace "$$URL_LOCAL" "$$TARGET_URL" --all-tables --dry-run --allow-root; \
	\
	echo ""; \
	read -p "Proceed with actual replacement? [y/N]: " confirm; \
	if [ "$$confirm" != "y" ] && [ "$$confirm" != "Y" ]; then \
		echo "❌ Cancelled"; exit 0; \
	fi; \
	\
	echo ""; \
	echo "🔄 Running search-replace..."; \
	export PREFIX="$(PREFIX)" && docker compose run --rm wpcli search-replace "$$URL_LOCAL" "$$TARGET_URL" --all-tables --allow-root; \
	\
	echo ""; \
	echo "💾 Exporting database to $$OUTPUT_FILE..."; \
	docker exec -it $(PREFIX)_db sh -c 'mysqldump wordpress -u wordpress -pwordpress 2> /dev/null' > $$OUTPUT_FILE; \
	\
	echo ""; \
	echo "🔄 Restoring local URLs..."; \
	export PREFIX="$(PREFIX)" && docker compose run --rm wpcli search-replace "$$TARGET_URL" "$$URL_LOCAL" --all-tables --allow-root; \
	\
	echo ""; \
	echo "✅ Export complete!"; \
	echo "   Output: $$OUTPUT_FILE"; \
	echo "   Local database has been restored to original state."

# search-replace (manual)
# Usage: make search-replace FROM=http://old.url TO=http://new.url
search-replace:
	@if [ -z "$(FROM)" ] || [ -z "$(TO)" ]; then \
		echo "Usage: make search-replace FROM=http://old.url TO=http://new.url"; \
		exit 1; \
	fi
	export PREFIX="$(PREFIX)" && docker compose run --rm wpcli search-replace "$(FROM)" "$(TO)" --all-tables --allow-root
