<?php
/**
 * Contains core WordPress functions for the project.
 *
 * This file implements essential functionality and customizations integrated within WordPress.
 *
 * @since 1.0.0
 */

// Vite dev server URL
require_once 'inc/init-vite.php';

// 定数読み込み.
require_once 'inc/consts.php';

// 初期設定.
require_once 'lib/init.php';

// クリーンアップ系.
require_once 'lib/cleanup.php';

// 機能の追加.
require_once 'lib/add.php';

// 関数の追加.
require_once 'lib/custom.php';
