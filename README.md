# piiiQcy
boilerplate for WP theme

![logo](docs/assets/images/logo.png)

piiiQcy（ピィキュー）はWordPressコーディング規約に則ったWordPress用ボイラープレートです。Theme内で完結する簡素な作りでありつつ、モダンな構築を素早く可能にする目的で開発を行っています。

姉妹ボイラープレート
- [Quicint（クイント）](https://github.com/TsubasaHiga/Quicint)：EJSを用いた静的開発用ボイラープレート
- [Percolator（パーコレーター）](https://github.com/TsubasaHiga/Percolator)：PHPを用いた静的開発用ボイラープレート

## 推奨プラグイン
- Admin Menu Editor
- Advanced Custom Fields PRO
- Custom Post Type UI
- WordPress Popular Posts
- WP Mail SMTP
- WPS Hide Login
- Yoast SEO

# Docker and WordPress

## docker コマンド

``` bash
# docker image一覧確認
docker image ls

# docker container 起動済みimageの一覧確認
docker container ls

# docker container 起動していないimageも含め一覧確認
docker container ls -a

# docker container 削除
docker rm {containerのハッシュ値}

# docker container 一括削除
docker container prune
```

## docker compose コマンド

``` bash
# docker compose build
docker-compose build

# docker compose build（キャッシュなし）
docker-compose build --no-cache

# docker compose起動
docker-compose up -d

# docker composeの停止
docker-compose stop

# docker composeの再起動
docker-compose restart

# docker composeのステータス確認
docuker-compose ps

# docker composeの削除
docker-compose down

# docker composeの削除（ボリュームも削除）
docker-compose down --volumes
```

## docker compose コマンド network

``` bash
# docker network一覧
docker network ls
```

## docker compose コマンド host操作

``` bash
# docker exec -it bash
docker exec -it {container名} bash
```

## docker compose wo-cli操作

``` bash
# 初期設定
docker-compose run --rm wpcli wp core install --url='http://localhost' --title='test' --admin_user='test' --admin_password='test' --admin_email='info@example.com' --allow-root

# 日本語設定
docker-compose run --rm wpcli wp language core install ja --activate --allow-root 

# wp-cli plugin一覧確認
docker-compose run --rm wpcli plugin list

# 推奨pluginの一括インストール＆有効化
docker-compose run --rm wpcli plugin install admin-menu-editor advanced-custom-fields custom-post-type-ui wordPress-popular-posts siteguard --activate --allow-root 
```

| wp-cli上のPlugin名 | 必須 / 要件に応じて |
| --- | --- |
| admin-menu-editor | 必須 |
| advanced-custom-fields | 必須 |
| custom-post-type-ui | 必須 |
| wordPress-popular-posts | 要件に応じて |
| siteguard | 要件に応じて |
