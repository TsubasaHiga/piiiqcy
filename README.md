# piiiQcy
boilerplate for WP theme

![logo](docs/assets/images/logo.png)

piiiQcy（ピィキュー）はWordPressコーディング規約に則ったWordPress用ボイラープレートです。Theme内で完結する簡素な作りでありつつ、モダンな構築を素早く可能にする目的で開発を行っています。

姉妹ボイラープレート
- [Quicint（クイント）](https://github.com/TsubasaHiga/Quicint)：EJSを用いた静的開発用ボイラープレート
- [Percolator（パーコレーター）](https://github.com/TsubasaHiga/Percolator)：PHPを用いた静的開発用ボイラープレート

## 環境について

| 項目 | 詳細 |
| --- | --- |
| node.js | 12.x required |
| JavaScript Package manager | yarn |
| PHP Package manager | composer |
| Build system | Gulp v4 |
| Module bundler | webpack |
| ECMAScript | ES6 |
| CSS design | FLOCSS |
| Lint | ESlint & Stylelint & phpcs |
| CMS | WordPress latest / Docker |
| phpcs | [WordPress Coding Standards](https://make.wordpress.org/core/handbook/coding-standards/)（dist配下のphpファイルのみ適応）|

``` console
$ sw_vers
ProductName:    Mac OS X
ProductVersion: 10.15.7
BuildVersion:   19H2

$ node -v
v12.18.4

$ yarn -v
1.22.0

$ composer -V
Composer version 1.10.7 2020-06-03 10:03:56
```

## ディレクトリ構成について

ルート配下2階層までのディレクトリ一覧を示します。

``` console
.
|-- app
|   `-- WordPress
|-- db-data
|   `-- wordpress.sql.gz
|-- dist
|   |-- assets
|   |-- inc
|   |-- lib
|   |-- pages
|   |-- parts
|   |-- template
|   |-- archive.php
|   |-- footer.php
|   |-- functions.php
|   |-- header.php
|   |-- index.php
|   |-- screenshot.png
|   |-- single.php
|   `-- style.css
|-- docker
|   |-- php
|   `-- phpmyadmin
|-- src
|   `-- assets
|-- utility
|-- vendor
|-- wp-uploads
|   `-- 2020
|-- Makefile
|-- README.md
|-- composer.json
|-- composer.lock
|-- docker-compose.yml
|-- gulpfile.js
|-- package.json
|-- setting.json
|-- webpack.config.js
|-- webpack.production.config.js
`-- yarn.lock
```

## 環境構築について事前準備が必要な作業

- Dockerのインストール
- Composerのインストール
- Dockerの`setting > Resources > FILE SHARING`よりプロジェクト配下のパスを登録
- wp-uploads配下を`./wp-uploads`へ設置
- DBを`./db-data/`へ設置

## 始め方

### phpcsとwp-coding-standardsのインストール

以下よりphpcsとWordPress用コーディング規約（wp-coding-standards）をインストールします。

``` console
composer install
```

wp-coding-standardsをセットします。

``` console
./vendor/bin/phpcs --config-set installed_paths "`pwd`/vendor/wp-coding-standards/wpcs"
```

この時点でエディターにvscodeを使用している場合は、`./.vscode/settings.json`にて`phpcs`と`phpcbf`のPATHがデフォルトで指定されているため、エディター上でのリアルタイムチェックが行われるようになります。（行われない場合は`command + shift + p`より`Reload Window`を選択し再読み込みを実施）

その他のエディターを使われる場合はそれぞれに合わせた設定を行います。

### Dockerコンテナーの立ち上げ

初回時は以下よりDockerコンテナーを用意します。初回時以降は`make up`で始めます。

``` console
make first
```

以下よりWordPressと必須プラグインのインストールを行います。

``` console
make wpinstall
```

この段階で`localhost:8000`にてページの表示が可能になります。

#### インストールするプラグインのリスト

なんらかの理由でwpcli経由でのインストールが出来ない場合は以下を手動でインストールします。

- admin-menu-editor
- advanced-custom-fields
- classic-editor
- custom-post-type-ui
- all-in-one-seo-pack

### 各種タスクの立ち上げ

各種タスクも以下より同時に始めます。

``` console
yarn install
```

インストール後は以下コマンドより初期`assetsファイル`の生成を行います。

``` console
yarn run development
```

以上でファイルの生成は完了です。ローカルにてサーバーを起動させる場合は以下コマンドより起動します。同時に各種コンパイルも可能になります。

``` console
yarn run serve # http://localhost:3000 or http://local-ipaddress:3000
```

## URL

### WordPress

- URL：http://localhost:8000/wp-admin/

※プラグインのインストールやWP本体の更新でFTP接続が求められる場合、[こちら](https://gist.github.com/dianjuar/1b2c43d38d76e68cf21971fc86eaac8e)が参考になります。本番環境ではコメントアウトが必要です。

### phpMyAdmin

DBクライアントにphpMyAdminがインストールされています。

- URL：http://localhost:8080
- ログイン情報：`./env`

## DB Dump

DBのDumpは以下より可能です。※`make run`済みである必要があります。

``` console
make dbdump
```

## 終了の仕方

Dockerコンテナーは以下で終了します。

``` console
make stop
```

続いて各種タスクは`Ctrl + C`で終了します。

## Dockerコンテナーとボリュームの削除

``` console
make down
```

## コマンド一覧

`package.json`にて利用可能なscriptsの抜粋です。

### 一般系

``` console
# 各種コンパイルタスクを利用出来ます。通常はこちらで制作を行います
yarn run serve

# productionビルドを行います
yarn run production

# 画像再圧縮タスクです。`src`と`dist`で画像数が合わなくなった場合にリセット目的で使用します
yarn run resetImg

# 各種jsonファイルのチェックタスクです
yarn run json-check
```

### Lint系

``` console
# SCSSファイルのlintタスクです
yarn run lint:css

# SCSSファイルの自動修正タスクです
yarn run fix:css

# JSファイルのlintタスクです
yarn run lint:js

# JSファイルの自動修正タスクです
yarn run fix:js
```

## コーディング規約について

- 全体：`.editorconfig`をご参照ください。
- JavaScript：`.eslintrc.json`をご参照ください。
- Sass：`.stylelintrc.json`をご参照ください。
- PHP：[WordPress Coding Standards](https://make.wordpress.org/core/handbook/coding-standards/)（dist配下のphpファイルのみ適応）

## その他

### 注意事項

コミット時には`pre-commit`にて事前チェックが行われます。チェックに通らない場合は各種コーディング規約をご確認ください。
