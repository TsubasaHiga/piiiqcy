# ------------------------------------------------------------------
# PREFIX & PORTS
# ------------------------------------------------------------------
PREFIX=piiiqcy
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

# ------------------------------------------------------------------
# SEARCH REPLACE
# Select the source and destination and uncomment.
# ------------------------------------------------------------------
# [Prod] → [STG]
# REPLACE_DOMAIN_FROM=https://example.com
# REPLACE_DOMAIN_TO=https://stg.example.com
# [Prod] → [Local]
REPLACE_DOMAIN_FROM=https://example.com
REPLACE_DOMAIN_TO=http://192.168.1.110:8000

# [STG] → [Prod]
# REPLACE_DOMAIN_FROM=https://stg.example.com
# REPLACE_DOMAIN_TO=https://example.com
# [STG] → [Local]
# REPLACE_DOMAIN_FROM=https://stg.example.com
# REPLACE_DOMAIN_TO=http://192.168.1.110:8000

# [Local] → [Prod]
# REPLACE_DOMAIN_FROM=http://192.168.1.110:8000
# REPLACE_DOMAIN_TO=https://example.com
# [Local] → [STG]
# REPLACE_DOMAIN_FROM=http://192.168.1.110:8000
# REPLACE_DOMAIN_TO=https://stg.example.com

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
			if docker compose run --rm wpcli wp db check --allow-root 2>/dev/null; then \
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
# Individual Commands
# ===========================================

# docker compose first
first:
	docker network create --driver bridge $(PREFIX)_network && \
  export PREFIX="$(PREFIX)" && docker compose up -d --build

# Wordpress Install
wpinstall:
	export PREFIX="$(PREFIX)" && docker compose run --rm wpcli wp core install --url='$(WP_URL)' --title='$(WP_TITLE)' --admin_user='$(WP_ADMIN_USER)' --admin_password='$(WP_ADMIN_PASSWORD)' --admin_email='$(WP_ADMIN_EMAIL)' --allow-root && \
  export PREFIX="$(PREFIX)" && docker compose run --rm wpcli wp language core install ja --activate --allow-root && \
  export PREFIX="$(PREFIX)" && docker compose run --rm wpcli plugin install $(WP_INSTALL_PLUGINS) --activate --allow-root

# docker compose up
up:
	export PREFIX="$(PREFIX)" && docker compose up -d

# docker compose stop
stop:
	export PREFIX="$(PREFIX)" && docker compose stop

# docker compose down (container remove and volumes delete)
down:
	export PREFIX="$(PREFIX)" && docker compose stop && \
  docker compose down --volumes && \
  docker network rm $(PREFIX)_network

# mysqldump
dbdump:
	docker exec -it $(PREFIX)_db sh -c 'mysqldump wordpress -u wordpress -pwordpress 2> /dev/null' > dump.sql

# search-replace --dry-run
search-replace-dry-run:
	export PREFIX="$(PREFIX)" && docker compose run --rm wpcli search-replace ${REPLACE_DOMAIN_FROM} ${REPLACE_DOMAIN_TO} --all-tables --dry-run

# search-replace
search-replace-run:
	export PREFIX="$(PREFIX)" && docker compose run --rm wpcli search-replace ${REPLACE_DOMAIN_FROM} ${REPLACE_DOMAIN_TO} --all-tables
