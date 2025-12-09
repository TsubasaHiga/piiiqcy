# piiiQcy

WordPress Theme Boilerplate with Monorepo Structure

![logo](docs/assets/images/logo.png)

piiiQcy（ピィキュー）はWordPressコーディング規約に則ったWordPress用ボイラープレートです。**モノレポ構成**を採用し、テーマとカスタムプラグインを同時に開発できます。

## Features

- **Monorepo Structure** - pnpm workspacesによるテーマ・プラグインの統合管理
- **Vite** - 高速なHMR（Hot Module Replacement）による開発体験
- **TypeScript** - 型安全なJavaScript開発
- **SCSS** - FLOCSS風のCSS設計手法
- **WordPress Coding Standards** - phpcs/phpstanによるPHP品質管理
- **Docker** - 簡単にローカル環境を構築
- **画像最適化** - PNG/JPG→WebP変換、SVG最適化を自動化
- **プラグイン開発** - カスタムブロックなどのプラグインをViteでビルド

## Tech Stack

| Category        | Technology                        |
| --------------- | --------------------------------- |
| Frontend        | TypeScript, SCSS, Vite            |
| Backend         | PHP (WordPress)                   |
| Package Manager | pnpm (workspaces)                 |
| Linting         | ESLint, Stylelint, phpcs, PHPStan |
| Formatter       | Prettier                          |
| Environment     | Docker, Composer                  |
| Utilities       | umaki, dayjs, sharp, svgo         |

## Project Structure

```
piiiqcy/
├── packages/                     # モノレポパッケージ
│   ├── theme/                    # WordPressテーマ
│   │   ├── src/                  # フロントエンドソースコード
│   │   │   ├── scripts/          # TypeScriptエントリーポイント
│   │   │   ├── styles/           # SCSSファイル
│   │   │   └── images/           # ソース画像
│   │   ├── dist/                 # ビルド出力（WPテーマ）
│   │   └── vite.config.ts        # テーマ用Vite設定
│   │
│   └── plugins/                  # カスタムプラグイン
│       └── sample-block/         # サンプルブロックプラグイン
│           ├── src/              # プラグインソース
│           ├── index.php         # プラグインメインファイル
│           └── vite.config.ts    # プラグイン用Vite設定
│
├── wp-plugins/                   # WordPressプラグイン（外部 + ビルド出力）
├── app/WordPress/                # WordPressコアファイル
├── scripts/                      # ビルドユーティリティスクリプト
├── project.config.ts             # プロジェクト設定（名前変更時に使用）
├── pnpm-workspace.yaml           # ワークスペース設定
└── docker-compose.yml            # Docker設定
```

## Required Environment

| Tool     | Version   |
| -------- | --------- |
| Node.js  | `v23.4.0` |
| pnpm     | `v9.15.4` |
| Docker   | `27.4.0+` |
| Composer | `2.8.4+`  |

## Setup

### 1. リポジトリをクローン

```bash
git clone https://github.com/your-org/piiiqcy.git my-project
cd my-project
```

### 2. プロジェクト名を変更（オプション）

新しいプロジェクトとして使用する場合、プロジェクト名を変更します。

```bash
# 依存関係をインストール
pnpm install

# 対話式でプロジェクト名を変更
pnpm rename-project
```

スクリプトが以下のファイルを自動更新します：

- `project.config.ts`
- 各 `package.json`
- `docker-compose.yml`
- `.env`
- ブロックの `block.json`

### 3. `.env`ファイルを設定

`.env.example`をコピーして編集します。

```bash
cp .env.example .env
```

```apache
# プロジェクト名（Dockerコンテナとテーマディレクトリに使用）
PREFIX=piiiqcy

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

### 4. Docker環境を構築

```bash
make first
```

### 5. `app/WordPress/wp-config.php`を編集

1. `.env`ファイルに基づいてDB接続情報を編集
2. 開発環境でViteのHMRを有効にするため、以下の定数を追加

```diff
  define( 'WP_DEBUG', !!getenv_docker('WORDPRESS_DEBUG', '') );
+ define( 'IS_VITE_DEVELOPMENT', true );
```

### 6. WordPressをインストール

```bash
make wpinstall
```

> **Tip**: テーマを有効化するには、管理画面で`外観` > `テーマ`から選択してください。

### 7. Composerをインストール

```bash
composer install
```

### 8. WordPress Coding Standardsを設定

```bash
./vendor/bin/phpcs --config-set installed_paths "\
../../cakephp/cakephp-codesniffer,\
../../phpcsstandards/phpcsextra,\
../../phpcsstandards/phpcsutils,\
../../slevomat/coding-standard,\
../../wp-coding-standards/wpcs"
```

### 9. Dockerを起動

```bash
make up
```

| Service         | URL                             |
| --------------- | ------------------------------- |
| WordPress       | http://localhost:8000           |
| WordPress Admin | http://localhost:8000/wp-admin/ |
| phpMyAdmin      | http://localhost:8080           |

### 10. 開発サーバーを起動

```bash
# テーマのみ開発
pnpm dev

# テーマとプラグインを同時に開発
pnpm dev:all
```

## Commands

### Theme Development

| Command          | Description                                 |
| ---------------- | ------------------------------------------- |
| `pnpm dev`       | Vite開発サーバー + 画像変換ウォッチを起動   |
| `pnpm build`     | TypeScriptチェック + 本番ビルド + 画像変換  |
| `pnpm build-stg` | ステージング環境用ビルド（dist-stg/に出力） |
| `pnpm build-all` | 本番 + ステージングビルドを実行             |
| `pnpm analyze`   | バンドル分析を視覚化                        |
| `pnpm preview`   | ビルド + プレビューサーバーを起動           |

### Plugin Development

| Command                   | Description                          |
| ------------------------- | ------------------------------------ |
| `pnpm dev:plugins`        | 全プラグインをウォッチモードでビルド |
| `pnpm build:plugins`      | 全プラグインを本番ビルド             |
| `pnpm dev:all`            | テーマとプラグインを同時に開発       |
| `pnpm build:all-packages` | テーマと全プラグインをビルド         |

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

| Command               | Description                     |
| --------------------- | ------------------------------- |
| `pnpm archive`        | ビルド + 配布用アーカイブを作成 |
| `pnpm rename-project` | プロジェクト名を対話式で変更    |

## Creating a New Plugin

新しいカスタムブロックプラグインを作成する手順：

### 1. プラグインディレクトリを作成

```bash
mkdir -p packages/plugins/my-custom-block/src/blocks/my-block
```

### 2. package.jsonを作成

```json
{
  "name": "@piiiqcy/my-custom-block",
  "version": "1.0.0",
  "private": true,
  "type": "module",
  "scripts": {
    "dev": "vite build --watch",
    "build": "vite build"
  },
  "dependencies": {
    "@wordpress/block-editor": "^14.0.0",
    "@wordpress/blocks": "^13.0.0"
  },
  "devDependencies": {
    "@vitejs/plugin-react": "^4.5.2",
    "vite": "^7.2.7"
  }
}
```

### 3. vite.config.tsを作成

`packages/plugins/sample-block/vite.config.ts`を参考にしてください。
重要なのは`OUTPUT_DIR`を`wp-plugins/`配下に設定することです。

### 4. ソースファイルを作成

- `src/index.tsx` - エントリーポイント
- `src/blocks/my-block/edit.tsx` - エディタコンポーネント
- `src/blocks/my-block/save.tsx` - 保存コンポーネント
- `src/blocks/my-block/block.json` - ブロック定義
- `index.php` - プラグインメインファイル

### 5. ビルド

```bash
# 特定のプラグインのみビルド
pnpm --filter @piiiqcy/my-custom-block build

# 全プラグインをビルド
pnpm build:plugins
```

ビルド出力は`wp-plugins/my-custom-block/`に生成されます。

## Project Configuration

プロジェクト全体の設定は`project.config.ts`で管理されています：

```typescript
export const projectConfig = {
  name: 'piiiqcy', // プロジェクト名
  displayName: 'piiiQcy', // 表示名
  scope: '@piiiqcy', // npmスコープ
  theme: {
    name: 'piiiqcy', // テーマディレクトリ名
    displayName: 'piiiQcy Theme'
  },
  docker: {
    prefix: 'piiiqcy', // Dockerコンテナプレフィックス
    network: 'piiiqcy_network'
  },
  dev: {
    port: 3000, // Vite開発サーバーポート
    wpPort: 8000, // WordPressポート
    pmaPort: 8080 // phpMyAdminポート
  }
}
```

## Renaming the Project

このテンプレートを新しいプロジェクトで使用する場合：

```bash
pnpm rename-project
```

対話式プロンプトで以下を入力：

1. 新しいプロジェクト名（例: `my-awesome-site`）
2. 表示名（例: `My Awesome Site`）

スクリプトが自動的に更新するファイル：

- `project.config.ts`
- `package.json`（ルートと各パッケージ）
- `docker-compose.yml`
- `.env`
- `block.json`ファイル

### 手動で必要な作業

名前変更後：

1. Dockerネットワークを再作成：

```bash
docker network rm piiiqcy_network
docker network create my-awesome-site_network
```

2. 依存関係を再インストール：

```bash
pnpm install
```

3. プロジェクトをリビルド：

```bash
pnpm build:all-packages
```

## Architecture

### PHP Classes (`packages/theme/dist/lib/`)

| Class             | Description                      |
| ----------------- | -------------------------------- |
| `Theme_Cache`     | カテゴリ・タームのキャッシュ管理 |
| `Category_Helper` | カテゴリ関連ユーティリティ       |
| `Query_Optimizer` | WP_Query引数の構築               |

### TypeScript Path Aliases

`packages/theme/tsconfig.json`で定義：

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

## License

This project is licensed under the MIT License.
