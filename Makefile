# ------------------------------------------------------------------
# PREFIX
# ------------------------------------------------------------------
PREFIX=piiiqcy

# ------------------------------------------------------------------
# WORDPRESS SETTINGS
# ------------------------------------------------------------------
WP_URL=http://localhost:8000
WP_TITLE=test
WP_ADMIN_USER=test
WP_ADMIN_PASSWORD=test
WP_ADMIN_EMAIL=info@example.com
WP_INSTALL_PLUGINS=admin-menu-editor \
        custom-post-type-ui \
        wordpress-seo

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
