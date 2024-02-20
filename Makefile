# ------------------------------------------------------------------
# prefix
PREFIX=piiiqcy

# Wordppress url
URL=http://localhost:8000
# Wordppress title
TITLE=test
# Wordppress admin user
ADMIN_USER=test
# Wordppress admin password
ADMIN_PASSWORD=test
# Wordppress admin email
ADMIN_EMAIL=info@example.com
# Wordppress install plugins
INSTALL_PLUGINS=admin-menu-editor \
        custom-post-type-ui \
        wordpress-seo

# search-replace
DOMAIN_FROM=https://example.com
DOMAIN_TO=http://192.168.1.109:8000
# ------------------------------------------------------------------

# docker compose first
first:
	docker network create --driver bridge $(PREFIX)_network && \
  export PREFIX="$(PREFIX)" && docker compose up -d --build

# Wordpress Install
wpinstall:
	export PREFIX="$(PREFIX)" && docker compose run --rm wpcli wp core install --url='$(URL)' --title='$(TITLE)' --admin_user='$(ADMIN_USER)' --admin_password='$(ADMIN_PASSWORD)' --admin_email='$(ADMIN_EMAIL)' --allow-root && \
  export PREFIX="$(PREFIX)" && docker compose run --rm wpcli wp language core install ja --activate --allow-root && \
  export PREFIX="$(PREFIX)" && docker compose run --rm wpcli plugin install $(INSTALL_PLUGINS) --activate --allow-root

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
	docker compose run --rm wpcli search-replace ${DOMAIN_FROM} ${DOMAIN_TO} --all-tables --dry-run

# search-replace
search-replace-run:
	docker compose run --rm wpcli search-replace ${DOMAIN_FROM} ${DOMAIN_TO} --all-tables
