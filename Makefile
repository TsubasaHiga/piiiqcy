# docker-compose first
first:
	docker network create --driver bridge network_piiiqcy && \
  docker-compose up -d --build

# Wordpress Install
wpinstall:
	docker-compose run --rm wpcli wp core install --url='http://localhost:8000' --title='test' --admin_user='test' --admin_password='test' --admin_email='info@example.com' --allow-root && \
  docker-compose run --rm wpcli wp language core install ja --activate --allow-root && \
  docker-compose run --rm wpcli plugin install \
  admin-menu-editor \
  custom-post-type-ui \
  wordpress-seo \
  ./wp-content/plugins/advanced-custom-fields-pro.zip \
  --activate --allow-root && \
  docker-compose run --rm wpcli plugin uninstall hello akismet

# docker-compose up
up:
	docker-compose up -d

# docker-compose stop
stop:
	docker-compose stop

# docker-compose down (container remove and volumes delete)
down:
	docker-compose stop && \
  docker-compose down --volumes && \
  docker network rm network_piiiqcy

# mysqldump
dbdump:
	docker exec -it piiiqcy_db sh -c 'mysqldump wordpress -u wordpress -pwordpress 2> /dev/null' > dump.sql
