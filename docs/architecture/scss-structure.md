# SCSS ディレクトリ構造

このドキュメントでは、本プロジェクトの SCSS ディレクトリ構造と命名規則について説明します。

## 概要

本プロジェクトの CSS 設計は [FLOCSS](https://github.com/hiloki/flocss) を**参考に**しつつ、独自のカスタマイズを加えた **FLOCSS ライク**な構成です。

Sass のモジュールシステム（`@use` / `@forward`）に適した構成にしており、完全な FLOCSS 準拠ではありません。

## ディレクトリ構造

```
src/styles/
├── abstracts/          # Sass ヘルパー層（小文字）
│   ├── config/         # 設定値・デザイントークン
│   ├── functions/      # Sass 関数
│   └── mixins/         # Sass ミックスイン
├── base/               # 基盤スタイル（小文字）
│   ├── _index.scss
│   └── _reset.scss
├── Components/         # FLOCSS: Object/Component（大文字）
├── Extends/            # プレースホルダーセレクタ（大文字）
├── Layouts/            # FLOCSS: Layout（大文字）
├── Pages/              # ページ固有スタイル（大文字）
├── Projects/           # FLOCSS: Object/Project（大文字）
├── Utilities/          # FLOCSS: Object/Utility（大文字）
└── main.scss           # エントリーポイント
```

## 命名規則

### 大文字/小文字の使い分け

| ディレクトリ | ケース | 理由 |
|-------------|--------|------|
| `abstracts` | 小文字 | FLOCSS レイヤー外。Sass のヘルパー機能（変数・関数・mixin）を格納 |
| `base` | 小文字 | FLOCSS レイヤー外。リセットやベーススタイルなど基盤となるスタイル |
| `Components` | 大文字 | FLOCSS の Object/Component レイヤー |
| `Extends` | 大文字 | プレースホルダーセレクタ。FLOCSS 拡張 |
| `Layouts` | 大文字 | FLOCSS の Layout レイヤー |
| `Pages` | 大文字 | ページ固有スタイル。FLOCSS 拡張 |
| `Projects` | 大文字 | FLOCSS の Object/Project レイヤー |
| `Utilities` | 大文字 | FLOCSS の Object/Utility レイヤー |

### 設計思想

- **小文字ディレクトリ**: FLOCSS の正式なレイヤーではない「ヘルパー層」や「基盤層」
- **大文字ディレクトリ**: FLOCSS に基づく CSS 設計レイヤー

この区別により、ディレクトリ名を見ただけで「CSS を出力するレイヤー」と「Sass のみで使用されるヘルパー」を識別できます。

## クロスプラットフォーム対応

### 重要: 大文字/小文字の厳密な一致

Linux / WSL2 環境ではファイルシステムが大文字/小文字を**区別します**。一方、macOS（デフォルト設定）や Windows は区別しません。

そのため、`@use` でインポートする際は**ディレクトリ名と完全に一致**させる必要があります。

```scss
// ✅ 正しい
@use './Components/c-button';
@use './abstracts' as *;

// ❌ 間違い（Linux/WSL2 でエラー）
@use './components/c-button';  // 大文字/小文字が一致しない
@use './Abstracts' as *;       // 大文字/小文字が一致しない
```

### Git の設定

クロスプラットフォームでの問題を防ぐため、Git で大文字/小文字を区別する設定を推奨します：

```bash
git config --global core.ignorecase false
```

## ファイル命名規則

各レイヤーのファイルは、FLOCSS の接頭辞規則に従います：

| レイヤー | 接頭辞 | 例 |
|---------|--------|-----|
| Layouts | `l-` | `_l-header.scss`, `_l-footer.scss` |
| Components | `c-` | `_c-button.scss`, `_c-card.scss` |
| Projects | `p-` | `_p-common.scss` |
| Utilities | `u-` | `_u-etc.scss`, `_u-mqw.scss` |

## FLOCSS との違い

本プロジェクトは FLOCSS を参考にしていますが、以下の点で異なります：

| 項目 | FLOCSS | 本プロジェクト |
|------|--------|----------------|
| Foundation 層 | `foundation/` | `base/`（名称変更） |
| ヘルパー層 | 規定なし | `abstracts/`（独自追加） |
| ページ固有スタイル | 規定なし | `Pages/`（独自追加） |
| プレースホルダー | 規定なし | `Extends/`（独自追加） |
| ディレクトリ命名 | 小文字 | FLOCSS レイヤーは大文字 |

### 独自追加レイヤーの役割

- **`abstracts/`**: Sass 変数・関数・mixin など、CSS を出力しないヘルパー
- **`Pages/`**: 特定ページでのみ使用するスタイル
- **`Extends/`**: `@extend` で使用するプレースホルダーセレクタ

## 参考リンク

- [FLOCSS](https://github.com/hiloki/flocss) - 本プロジェクトが参考にした CSS 設計手法
- [Sass @use](https://sass-lang.com/documentation/at-rules/use) - モジュールシステム
