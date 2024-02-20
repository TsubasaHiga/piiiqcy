# piiiQcy

boilerplate for WP theme

![logo](docs/assets/images/logo.png)

piiiQcy（ピィキュー）はWordPressコーディング規約に則ったWordPress用ボイラープレートです。Theme内で完結する簡素な作りでありつつ、モダンな構築を素早く可能にする目的で開発を行っています。

姉妹ボイラープレート

- [Quicint（クイント）](https://github.com/TsubasaHiga/Quicint)：EJSを用いた静的開発用ボイラープレート
- [Percolator（パーコレーター）](https://github.com/TsubasaHiga/Percolator)：PHPを用いた静的開発用ボイラープレート

## 環境について

| 項目                       | 詳細                                                                                     |
| -------------------------- | ---------------------------------------------------------------------------------------- |
| node.js                    | 16.5x required                                                                           |
| JavaScript Package manager | yarn                                                                                     |
| PHP Package manager        | composer                                                                                 |
| Build system               | Vite                                                                                     |
| ECMAScript                 | ES6                                                                                      |
| CSS design                 | FLOCSS                                                                                   |
| Lint                       | ESlint & Stylelint & phpcs                                                               |
| CMS                        | WordPress latest / Docker                                                                |
| phpcs                      | [WordPress Coding Standards](https://make.wordpress.org/core/handbook/coding-standards/) |

```console
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

## 環境構築について事前準備が必要な作業

- Dockerのインストール
- Composerのインストール
- Dockerの`setting > Resources > FILE SHARING`よりプロジェクト配下のパスを登録
- wp-uploads配下を`./wp-uploads`へ設置
- DBを`./db-data/`へ設置
- 置換
  - `piiiqcy` → `プロジェクト名`
  - `example.com` → `ドメイン名`

## Setup

### Init `.env` file

`./.env`は以下のように設定を行います。

```apache
# Database
MYSQL_RANDOM_ROOT_PASSWORD=yes
MYSQL_DATABASE=wordpress
MYSQL_USER=wordpress
MYSQL_PASSWORD=wordpress

# WordPress
WORDPRESS_DB_HOST=db:3306
WORDPRESS_DB_NAME=wordpress
WORDPRESS_DB_USER=wordpress
WORDPRESS_DB_PASSWORD=wordpress
WORDPRESS_DEBUG="true"

# Vite local server
VITE_API_URL=192.168.1.109
```

### Install phpcs

以下よりphpcsとWordPress用コーディング規約（wp-coding-standards）をインストールします。

```bash
composer install
```

wp-coding-standardsをセットします。

```bash
./vendor/bin/phpcs --config-set installed_paths "`pwd`/vendor/wp-coding-standards/wpcs"
```

この時点でエディターにvscodeを使用している場合は、`./.vscode/settings.json`にて`phpcs`と`phpcbf`のPATHがデフォルトで指定されているため、エディター上でのリアルタイムチェックが行われるようになります。（行われない場合は`command + shift + p`より`Reload Window`を選択し再読み込みを実施）

その他のエディターを使われる場合はそれぞれに合わせた設定を行います。

### Init Docker

初回時は以下よりDockerコンテナーを用意します。初回時以降は`make up`で始めます。

```bash
make first
```

以下よりWordPressと必須プラグインのインストールを行います。

```bash
make wpinstall
```

次に以下を実行します。

```bash
make up
```

この段階で`localhost:8000`にてページの表示が可能になります。

### Settings WordPress

開発環境の場合は`app/wp-config.php`にて以下の定数を追加することで、ViteのHMRを利用した開発が可能になります。

```php
// theme development
define( 'IS_VITE_DEVELOPMENT', true );
```

## Scripts

### Dev

```bash
yarn dev
```

### Build

```bash
yarn build
```

その他は`package.json`を参照してください。

## URL

### WordPress

- URL：<http://localhost:8000/wp-admin/>

※プラグインのインストールやWP本体の更新でFTP接続が求められる場合、[こちら](https://gist.github.com/dianjuar/1b2c43d38d76e68cf21971fc86eaac8e)が参考になります。本番環境ではコメントアウトが必要です。

### phpMyAdmin

DBクライアントにphpMyAdminがインストールされています。

- URL：<http://localhost:8080>
- ログイン情報：`./env`

## DB Dump

DBのDumpは以下より可能です。※`make run`済みである必要があります。

```bash
make dbdump
```

## 終了の仕方

Dockerコンテナーは以下で終了します。

```bash
make stop
```

続いて各種タスクは`Ctrl + C`で終了します。

## Dockerコンテナーとボリュームの削除

```bash
make down
```

## コーディング規約について

- 全体：`.editorconfig`をご参照ください。
- JavaScript：`.eslintrc.json`をご参照ください。
- Sass：`.stylelintrc.json`をご参照ください。
- PHP：[WordPress Coding Standards](https://make.wordpress.org/core/handbook/coding-standards/)（dist配下のphpファイルのみ適応）
