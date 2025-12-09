<?php
/**
 * Contains core WordPress functions for the project.
 *
 * This file implements essential functionality and customizations integrated within WordPress.
 *
 * @since 1.0.0
 */

// Vite dev server URL.
require_once 'inc/init-vite.php';

// 定数読み込み.
require_once 'inc/consts.php';

// 初期設定.
require_once 'lib/init.php';

// クリーンアップ系.
require_once 'lib/cleanup.php';

// 機能の追加.
require_once 'lib/add.php';

// クラスファイル（カスタム関数より先に読み込む）.
require_once 'lib/class-theme-cache.php';
require_once 'lib/class-category-helper.php';
require_once 'lib/class-query-optimizer.php';

// ヘルパー関数.
require_once 'lib/helpers/breadcrumb.php';
require_once 'lib/helpers/image.php';
require_once 'lib/helpers/pagination.php';
require_once 'lib/helpers/text.php';
require_once 'lib/helpers/url.php';
