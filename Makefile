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
        wordpress-seo \
        ./wp-content/plugins/advanced-custom-fields-pro.zip
# Wordppress uninstall plugins
UNINSTALL_PLUGINS=hello \
                  akismet
# ------------------------------------------------------------------

# docker compose first
first:
	docker network create --driver bridge network_$(PREFIX) && \
  docker compose up -d --build

# Wordpress Install
wpinstall:
	docker compose run --rm wpcli wp core install --url='$(URL)' --title='$(TITLE)' --admin_user='$(ADMIN_USER)' --admin_password='$(ADMIN_PASSWORD)' --admin_email='$(ADMIN_EMAIL)' --allow-root && \
  docker compose run --rm wpcli wp language core install ja --activate --allow-root && \
  docker compose run --rm wpcli plugin install $(INSTALL_PLUGINS) --activate --allow-root && \
  docker compose run --rm wpcli plugin uninstall $(UNINSTALL_PLUGINS)

# docker compose up
up:
	docker compose up -d

# docker compose stop
stop:
	docker compose stop

# docker compose down (container remove and volumes delete)
down:
	docker compose stop && \
  docker compose down --volumes && \
  docker network rm network_$(PREFIX)

# mysqldump
dbdump:
	docker exec -it $(PREFIX)_db sh -c 'mysqldump wordpress -u wordpress -pwordpress 2> /dev/null' > dump.sql
