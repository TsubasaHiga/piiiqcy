# FROM wordpress:latest

# RUN apt-get update
# RUN apt-get -y install wget unzip

# WORKDIR /tmp/wp-plugins
# # 使いたいプラグインは適当に定義する
# RUN wget https://downloads.wordpress.org/plugin/wordpress-importer.0.6.4.zip

# RUN unzip './*.zip' -d /usr/src/wordpress/wp-content/plugins

# RUN apt-get clean
# RUN rm -rf /tmp/*

# RUN chown -R www-data:www-data /usr/src/wordpress/wp-content

# WORKDIR /var/www/html
