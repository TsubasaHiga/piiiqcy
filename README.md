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

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Quick Start](#quick-start)
- [Commands](#commands)
- [Configuration](#configuration)
- [Architecture](#architecture)
- [Advanced Topics](#advanced-topics)
- [License](#license)

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
│
├── wp-plugins/                   # WordPressプラグイン（外部 + ビルド出力）
├── app/WordPress/                # WordPressコアファイル
├── project.config.ts             # プロジェクト設定
└── docker-compose.yml            # Docker設定
```

## Quick Start

### Required Environment

| Tool     | Version   |
| -------- | --------- |
| Node.js  | `v23.4.0` |
| pnpm     | `v9.15.4` |
| PHP      | `8.x`     |
| Composer | `2.8.4+`  |
| Docker   | `27.4.0+` |

> [!NOTE]
> **Windowsユーザー向け**: [WSL2](https://learn.microsoft.com/ja-jp/windows/wsl/install) での実行を推奨します（Git Bash等は動作未確認）。

### Setup

```bash
# 1. クローン
git clone https://github.com/TsubasaHiga/piiiqcy.git my-project
cd my-project

# 2. プロジェクト初期化（プロジェクト名・ポート設定・.env作成）
./init.sh

# 3. セットアップ（依存関係 + Docker + WordPress）
make setup

# 4. 開発サーバーを起動
pnpm dev
```

### URLs

| Service         | URL                             |
| --------------- | ------------------------------- |
| WordPress       | http://localhost:8000           |
| WordPress Admin | http://localhost:8000/wp-admin/ |
| phpMyAdmin      | http://localhost:8080           |
| Vite Dev Server | http://localhost:3000           |

> [!NOTE]
> `./init.sh`にてポート番号を変更した場合は適宜読み替えてください。

> [!TIP]
>
> - デフォルトのWordPress管理者アカウントは `test` / `test` です。
> - テーマは `make setup` で自動的に有効化されます。

## Commands

### Development

| Command                   | Description                                 |
| ------------------------- | ------------------------------------------- |
| `pnpm dev`                | Vite開発サーバー + 画像変換ウォッチを起動   |
| `pnpm build`              | TypeScriptチェック + 本番ビルド + 画像変換  |
| `pnpm build-stg`          | ステージング環境用ビルド（dist-stg/に出力） |
| `pnpm dev:plugins`        | 全プラグインをウォッチモードでビルド        |
| `pnpm build:plugins`      | 全プラグインを本番ビルド                    |
| `pnpm dev:all`            | テーマとプラグインを同時に開発              |
| `pnpm build:all-packages` | テーマと全プラグインをビルド                |

### Linting & Formatting

| Command             | Description                         |
| ------------------- | ----------------------------------- |
| `pnpm lint:scripts` | TypeScriptファイルのESLintチェック  |
| `pnpm lint:styles`  | CSS/SCSSファイルのStylelintチェック |
| `pnpm lint:php`     | PHPファイルのWordPress規約チェック  |
| `pnpm phpstan`      | PHPStan静的解析（レベル5）          |
| `pnpm format`       | Prettierによるフォーマット          |

### Docker

| Command          | Description                              |
| ---------------- | ---------------------------------------- |
| `make setup`     | **推奨**: 初回セットアップを一括実行     |
| `make up`        | Dockerコンテナを起動                     |
| `make restart`   | コンテナを再作成（.env変更の反映に使用） |
| `make stop`      | Dockerコンテナを停止                     |
| `make down`      | コンテナを停止し、ボリュームと共に削除   |
| `make dbdump`    | データベースをdump.sqlにエクスポート     |
| `make db-export` | URL変換付きDBエクスポート（対話式）      |

### Utilities

| Command     | Description                  |
| ----------- | ---------------------------- |
| `./init.sh` | プロジェクト名を対話式で変更 |

## Configuration

### .env 設定項目

```apache
# プロジェクト名（Docker コンテナとテーマディレクトリに使用）
PREFIX=piiiqcy

# ポート設定
WP_PORT=8000      # WordPress
PMA_PORT=8080     # phpMyAdmin
VITE_PORT=3000    # Vite開発サーバー

# WordPress デバッグモード
# "true": Vite開発サーバーを使用 / "": ビルド済みアセットを使用
WORDPRESS_DEBUG="true"

# Vite 開発サーバー（LAN内デバイスからアクセスする場合は IP を指定）
VITE_API_URL=localhost

# 環境URL（DBエクスポート用）
URL_LOCAL=http://localhost:8000
URL_STG=https://stg.example.com
URL_PROD=https://example.com
```

> [!NOTE]
> ポート設定は`.env`で一元管理されています。変更は自動的にVite、PHP、Dockerに反映されます。

### project.config.ts

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

## Architecture

### PHP Classes (`packages/theme/dist/lib/`)

| Class             | Description                      |
| ----------------- | -------------------------------- |
| `Theme_Cache`     | カテゴリ・タームのキャッシュ管理 |
| `Category_Helper` | カテゴリ関連ユーティリティ       |
| `Query_Optimizer` | WP_Query引数の構築               |

### TypeScript Path Aliases

`packages/theme/tsconfig.json`で定義：

| Alias | Path    |
| ----- | ------- |
| `@/*` | `src/*` |

```typescript
import '@/styles/main.scss'
import InView from '@/scripts/modules/InView'
```

### Coding Standards

**PHP**

- [WordPress Coding Standards](https://make.wordpress.org/core/handbook/coding-standards/)
- [phpstan-wordpress](https://github.com/szepeviktor/phpstan-wordpress)

**TypeScript**

- ESLint with TypeScript plugin
- Import sorting via `eslint-plugin-simple-import-sort`

**SCSS**

- Stylelint with `standard-scss` config
- Property ordering via `recess-order`

## Advanced Topics

### ローカル環境で本番モードを確認する

ローカル環境でVite開発サーバーを介さない本番と同様の動作を確認できます。

```bash
# 1. 本番ビルド
pnpm build

# 2. .envのデバッグモードを無効化
# WORDPRESS_DEBUG=""  または行をコメントアウト

# 3. コンテナを再起動
make restart
```

> [!CAUTION]
> `WORDPRESS_DEBUG="false"`は**機能しません**。PHPでは空でない文字列は`true`に評価されます。空文字列またはコメントアウトで無効化してください。

### 本番/ステージング環境用のDBエクスポート

ローカルDBを本番やステージング環境用に変換してエクスポートできます。

```bash
# 対話式（環境を選択）
make db-export

# 引数で直接指定
make db-export ENV=prod   # → dump-prod.sql
make db-export ENV=stg    # → dump-stg.sql
```

> [!NOTE]
> エクスポート後、ローカルDBは自動的に元のURLに復元されます。

### 同一ネットワーク内の他デバイスからWordPressにアクセス

WordPressはサイトURLをデータベースに保存するため、`localhost`でインストールすると他デバイスからアクセスできません。

```bash
# IPアドレスを確認
ifconfig | grep "inet " | grep -v 127.0.0.1

# URLを置換（例: 192.168.1.100）
docker compose run --rm wpcli wp search-replace 'http://localhost:8000' 'http://192.168.1.100:8000' --allow-root

# localhostに戻す場合
docker compose run --rm wpcli wp search-replace 'http://192.168.1.100:8000' 'http://localhost:8000' --allow-root
```

### トラブルシューティング

**ポートが使用中の場合**

`.env`でポート番号を変更するか、使用中のプロセスを停止：

```bash
lsof -i :8000  # 使用中のポートを確認
docker stop $(docker ps -aq)  # 古いDockerコンテナを停止
```

**Dockerネットワークエラーの場合**

```bash
docker network rm <PREFIX>_network
docker network create --driver bridge <PREFIX>_network
```

### 新しいプラグインを作成する

1. **ディレクトリを作成**

```bash
mkdir -p packages/plugins/my-custom-block/src/blocks/my-block
```

2. **package.jsonを作成**

```json
{
  "name": "@piiiqcy/my-custom-block",
  "version": "1.0.0",
  "private": true,
  "type": "module",
  "scripts": {
    "dev": "vite build --watch",
    "build": "vite build"
  }
}
```

3. **vite.config.tsを作成**

`packages/plugins/sample-block/vite.config.ts`を参考に、`OUTPUT_DIR`を`wp-plugins/`配下に設定。

4. **ソースファイルを作成**

- `src/index.tsx` - エントリーポイント
- `src/blocks/my-block/edit.tsx` - エディタコンポーネント
- `src/blocks/my-block/save.tsx` - 保存コンポーネント
- `src/blocks/my-block/block.json` - ブロック定義
- `index.php` - プラグインメインファイル

5. **ビルド**

```bash
pnpm --filter @piiiqcy/my-custom-block build
```

## License

This project is licensed under the MIT License.
