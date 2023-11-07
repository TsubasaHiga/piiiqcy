<?php
/**
 * Functions.php
 *
 * Functionsの中身です
 *
 * @since 0.0.1
 * @package piiiQcy
 */

 // Main switch to get frontend assets from a Vite dev server OR from production built folder
// it is recommended to move it into wp-config.php
define('IS_VITE_DEVELOPMENT', true);

// Vite dev server URL
require_once "inc/inc.vite.php";

// 定数読み込み.
require_once 'inc/const.php';

// 初期設定.
require_once 'lib/init.php';

// クリーンアップ系.
require_once 'lib/cleanup.php';

// 機能の追加.
require_once 'lib/add.php';

// 関数の追加.
require_once 'lib/custom.php';


