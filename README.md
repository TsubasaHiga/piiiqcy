# piiiQcy

WordPress Theme Boilerplate with Monorepo Structure

![logo](docs/assets/images/logo.png)

piiiQcy（ピィキュー）はWordPressコーディング規約に則ったWordPress用ボイラープレートです。**モノレポ構成**を採用し、テーマとカスタムプラグインを同時に開発できます。

> [!CAUTION]
>
> ## Breaking Changes (v2.0.0)
>
> モノレポ構成への移行に伴い、以下の破壊的変更があります：
>
> **ディレクトリ構成の変更**
> | 変更前 | 変更後 |
> |--------|--------|
> | `src/` | `packages/theme/src/` |
> | `dist/` | `packages/theme/dist/` |
> | `vite.config.ts` | `packages/theme/vite.config.ts` |
>
> **パッケージ管理**
>
> - テーマ専用パッケージのインストールは `pnpm add <pkg> --filter @piiiqcy/theme` を使用
> - ルートの `package.json` には共通ツール（lint, format等）のみ配置
>
> **削除されたコマンド**
>
> - `pnpm archive` - 配布用アーカイブ生成機能を削除
>
> **WordPressテーマのシンボリックリンク**
>
> - テーマパスが `packages/theme/dist` に変更されたため、シンボリックリンクの再作成が必要

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
| Utilities       | umaki, sharp, svgo                |

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

## Quick Start

```bash
# 1. クローン
git clone https://github.com/TsubasaHiga/piiiqcy.git my-project
cd my-project

# 2. プロジェクト名を変更（オプション）
./init.sh

# 3. 環境設定ファイルを作成
cp .env.example .env

# 4. セットアップ（依存関係 + Docker + WordPress）
make setup

# 5. 開発サーバーを起動
pnpm dev
```

| Service         | URL                             |
| --------------- | ------------------------------- |
| WordPress       | http://localhost:8000           |
| WordPress Admin | http://localhost:8000/wp-admin/ |
| phpMyAdmin      | http://localhost:8080           |
| Vite Dev Server | http://localhost:3000           |

> [!TIP]
> デフォルトのWordPress管理者アカウントは `test` / `test` です。
> テーマは `make setup` で自動的に有効化されます。

> [!NOTE]
> `./init.sh`は対話式でプロジェクト名を変更します。Node依存なしで実行可能なため、クローン直後に実行できます。

## Setup Details

### .env 設定項目

```apache
# プロジェクト名（Docker コンテナとテーマディレクトリに使用）
PREFIX=piiiqcy

# MySQL 設定
MYSQL_DATABASE=wordpress
MYSQL_USER=wordpress
MYSQL_PASSWORD=wordpress

# WordPress 設定
WORDPRESS_DEBUG="true"    # 開発環境では true（Vite HMR が有効になる）

# Vite 開発サーバー（LAN内デバイスからアクセスする場合は IP を指定）
VITE_API_URL=localhost
```

### 本番環境向けビルド

```bash
# 本番ビルド
pnpm build

# ステージングビルド
pnpm build-stg
```

本番環境では `.env` の `WORDPRESS_DEBUG` を `"false"` に設定してください。

### トラブルシューティング

**ポートが使用中の場合**

`.env`でポート番号を変更できます：

```bash
# .env
WP_PORT=8001    # WordPress (デフォルト: 8000)
PMA_PORT=8081   # phpMyAdmin (デフォルト: 8080)
VITE_PORT=3001  # Vite開発サーバー (デフォルト: 3000)
```

> [!NOTE]
> ポート設定は`.env`で一元管理されています。変更は自動的にVite、PHP、Dockerに反映されます。

または使用中のプロセスを停止：

```bash
# 使用中のポートを確認
lsof -i :8000  # WordPress
lsof -i :8080  # phpMyAdmin

# 古いDockerコンテナを停止
docker stop $(docker ps -aq)
```

**Dockerネットワークエラーの場合**

```bash
# 既存のネットワークを削除して再作成
docker network rm <PREFIX>_network
docker network create --driver bridge <PREFIX>_network
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

| Command          | Description                                     |
| ---------------- | ----------------------------------------------- |
| `make setup`     | **推奨**: 初回セットアップを一括実行            |
| `make up`        | Dockerコンテナを起動                            |
| `make stop`      | Dockerコンテナを停止                            |
| `make down`      | コンテナを停止し、ボリュームと共に削除          |
| `make first`     | Docker初期設定のみ（ネットワーク作成 + ビルド） |
| `make wpinstall` | WordPressインストールのみ                       |
| `make dbdump`    | データベースをdump.sqlにエクスポート            |

### Utilities

| Command     | Description                  |
| ----------- | ---------------------------- |
| `./init.sh` | プロジェクト名を対話式で変更 |

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
  }
}
```

> [!NOTE]
> Vite開発サーバーのポートは`.env`の`VITE_PORT`で設定します（デフォルト: 3000）。

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
