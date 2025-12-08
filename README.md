# piiiQcy

Boilerplate for WP theme

![logo](docs/assets/images/logo.png)

piiiQcy（ピィキュー）はWordPressコーディング規約に則ったWordPress用ボイラープレートです。Theme内で完結する簡素な作りでありつつ、モダンな構築を素早く可能にする目的で開発を行っています。

## Features

- **Vite** - 高速なHMR（Hot Module Replacement）による開発体験
- **TypeScript** - 型安全なJavaScript開発
- **SCSS** - FLOCSS風のCSS設計手法
- **WordPress Coding Standards** - phpcs/phpstanによるPHP品質管理
- **Docker** - 簡単にローカル環境を構築
- **画像最適化** - PNG/JPG→WebP変換、SVG最適化を自動化

## Tech Stack

| Category        | Technology                        |
| --------------- | --------------------------------- |
| Frontend        | TypeScript, SCSS, Vite            |
| Backend         | PHP (WordPress)                   |
| Package Manager | pnpm                              |
| Linting         | ESLint, Stylelint, phpcs, PHPStan |
| Formatter       | Prettier                          |
| Environment     | Docker, Composer                  |
| Utilities       | umaki, dayjs, sharp, svgo         |

## Project Structure

```
piiiqcy/
├── src/                      # フロントエンドソースコード
│   ├── scripts/              # TypeScriptエントリーポイント
│   │   ├── main.ts           # グローバルスクリプト
│   │   ├── pageTop.ts        # トップページ用
│   │   ├── pageAbout.ts      # アバウトページ用
│   │   ├── modules/          # 再利用可能なモジュール
│   │   ├── pages/            # ページ固有のスクリプト
│   │   ├── utils/            # ユーティリティ関数
│   │   └── const/            # 定数定義
│   ├── styles/               # SCSSファイル
│   │   ├── main.scss         # メインエントリー
│   │   ├── abstracts/        # 変数、Mixin、関数
│   │   ├── base/             # ベーススタイル
│   │   ├── Components/       # UIコンポーネント
│   │   ├── Layouts/          # レイアウト構造
│   │   ├── Pages/            # ページ固有スタイル
│   │   ├── Projects/         # プロジェクト固有スタイル
│   │   ├── Extends/          # 拡張スタイル
│   │   └── Utilities/        # ユーティリティクラス
│   └── images/               # ソース画像（ビルド時に処理）
├── dist/                     # WordPressテーマ（ビルド出力）
│   ├── functions.php         # テーマ関数
│   ├── index.php             # メインテンプレート
│   ├── archive.php           # アーカイブテンプレート
│   ├── single.php            # 個別投稿テンプレート
│   ├── inc/                   # コアインクルード
│   ├── lib/                  # テーマ機能
│   │   ├── class-*.php       # クラスベースの機能
│   │   └── helpers/          # ヘルパー関数
│   ├── template-parts/       # 再利用可能なテンプレートパーツ
│   ├── parts/                # パーツテンプレート
│   └── pages/                # 固定ページテンプレート
├── dist-stg/                 # ステージング用ビルド出力
├── scripts/                  # ビルドユーティリティスクリプト
├── app/WordPress/            # WordPressコアファイル
└── wp-plugins/               # カスタムWordPressプラグイン
```

## Required Environment

| Tool     | Version   |
| -------- | --------- |
| Node.js  | `v23.4.0` |
| pnpm     | `v9.15.4` |
| Docker   | `27.4.0+` |
| Composer | `2.8.4+`  |

## Setup

### 1. `.env`ファイルをルートに配置

```apache
MYSQL_RANDOM_ROOT_PASSWORD=yes
MYSQL_DATABASE=wordpress
MYSQL_USER=wordpress
MYSQL_PASSWORD=wordpress

WORDPRESS_DB_HOST=db:3306
WORDPRESS_DB_NAME=wordpress
WORDPRESS_DB_USER=wordpress
WORDPRESS_DB_PASSWORD=wordpress
WORDPRESS_DEBUG="true"

VITE_API_URL=192.168.1.110
```

### 2. プロジェクト名を置換

`piiiqcy`を任意のプロジェクト名に置換します。

```bash
grep -rl piiiqcy . --exclude-dir=".git" --exclude-dir="node_modules" --exclude-dir="vendor" --exclude-dir="wp-plugins" --exclude-dir="wp-uploads" | xargs sed -i '' -e 's/piiiqcy/your-project-name/g'
```

### 3. Docker環境を構築

```bash
make first
```

### 4. `app/WordPress/wp-config.php`を編集

1. `.env`ファイルに基づいてDB接続情報を編集
2. 開発環境でViteのHMRを有効にするため、以下の定数を追加

```diff
  define( 'WP_DEBUG', !!getenv_docker('WORDPRESS_DEBUG', '') );
+ define( 'IS_VITE_DEVELOPMENT', true );
```

### 5. WordPressをインストール

```bash
make wpinstall
```

> **Tip**: テーマを有効化するには、管理画面で`外観` > `テーマ`から*piiiQcy*を有効化してください。

### 6. Composerをインストール

```bash
composer install
```

### 7. WordPress Coding Standardsを設定

```bash
./vendor/bin/phpcs --config-set installed_paths "\
../../cakephp/cakephp-codesniffer,\
../../phpcsstandards/phpcsextra,\
../../phpcsstandards/phpcsutils,\
../../slevomat/coding-standard,\
../../wp-coding-standards/wpcs"
```

### 8. Dockerを起動

```bash
make up
```

| Service         | URL                             |
| --------------- | ------------------------------- |
| WordPress       | http://localhost:8000           |
| WordPress Admin | http://localhost:8000/wp-admin/ |
| phpMyAdmin      | http://localhost:8080           |

### 9. 依存関係をインストール

```bash
pnpm install
```

### 10. 開発サーバーを起動

```bash
pnpm dev
```

## Commands

### Frontend Development

| Command          | Description                                 |
| ---------------- | ------------------------------------------- |
| `pnpm dev`       | Vite開発サーバー + 画像変換ウォッチを起動   |
| `pnpm build`     | TypeScriptチェック + 本番ビルド + 画像変換  |
| `pnpm build-stg` | ステージング環境用ビルド（dist-stg/に出力） |
| `pnpm build-all` | 本番 + ステージングビルドを実行             |
| `pnpm analyze`   | バンドル分析を視覚化                        |
| `pnpm preview`   | ビルド + プレビューサーバーを起動           |

### Linting & Formatting

| Command             | Description                         |
| ------------------- | ----------------------------------- |
| `pnpm lint:scripts` | TypeScriptファイルのESLintチェック  |
| `pnpm lint:styles`  | CSS/SCSSファイルのStylelintチェック |
| `pnpm lint:php`     | PHPファイルのWordPress規約チェック  |
| `pnpm phpstan`      | PHPStan静的解析（レベル5）          |
| `pnpm format`       | Prettierによるフォーマット          |

### Docker Environment

| Command          | Description                                   |
| ---------------- | --------------------------------------------- |
| `make first`     | 初期Docker設定（ネットワーク作成 + ビルド）   |
| `make up`        | Dockerコンテナを起動                          |
| `make stop`      | Dockerコンテナを停止                          |
| `make down`      | コンテナを停止し、ボリュームと共に削除        |
| `make wpinstall` | WordPressとデフォルトプラグインをインストール |
| `make dbdump`    | データベースをdump.sqlにエクスポート          |

### Utilities

| Command              | Description                         |
| -------------------- | ----------------------------------- |
| `pnpm archive`       | ビルド + 配布用アーカイブを作成     |
| `pnpm convertImages` | 画像変換（PNG/JPG→WebP、SVG最適化） |

## Architecture

### PHP Classes (`dist/lib/`)

| Class             | Description                      |
| ----------------- | -------------------------------- |
| `Theme_Cache`     | カテゴリ・タームのキャッシュ管理 |
| `Category_Helper` | カテゴリ関連ユーティリティ       |
| `Query_Optimizer` | WP_Query引数の構築               |

### Helper Functions (`dist/lib/helpers/`)

| File             | Functions                                       |
| ---------------- | ----------------------------------------------- |
| `breadcrumb.php` | `get_page_relation_list()` - パンくずリスト生成 |
| `image.php`      | `get_image()`, `get_thumb()` - 画像取得         |
| `pagination.php` | `get_pagination()` - ページネーション生成       |
| `text.php`       | `show_txt()`, `get_txt()` - テキスト出力        |
| `url.php`        | `get_current_url()` - 現在のURL取得             |

### TypeScript Path Aliases

`tsconfig.json`で定義されたパスエイリアス:

| Alias           | Path                       |
| --------------- | -------------------------- |
| `@/*`           | `src/scripts/*`            |
| `@components/*` | `src/scripts/components/*` |
| `@modules/*`    | `src/scripts/modules/*`    |
| `@pages/*`      | `src/scripts/pages/*`      |
| `@utils/*`      | `src/scripts/utils/*`      |

## Coding Standards

### PHP

- [WordPress Coding Standards](https://make.wordpress.org/core/handbook/coding-standards/)
- [phpstan-wordpress](https://github.com/szepeviktor/phpstan-wordpress)

### TypeScript

- ESLint with TypeScript plugin
- Import sorting via `eslint-plugin-simple-import-sort`
- Strict TypeScript configuration

### SCSS

- Stylelint with `standard-scss` config
- Property ordering via `recess-order`
- Prettier integration

### Formatter

- Prettier for all files
- EditorConfig for basic formatting rules

## Q&A

### トップページのリンクが機能しない場合は？

アバウトページのリンクを機能させるには、固定ページとして登録する必要があります。

## License

This project is licensed under the MIT License.
