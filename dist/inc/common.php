<?php
/**
 * Common.php
 *
 * 全ページで共通して読み込むファイルを記述します
 *
 * @since 0.0.1
 * @package piiiQcy
 */

?>

<?php

// Remove X-Powered-By.
header_register_callback(
	function() {
		header_remove( 'X-Powered-By' );
	}
);

// 定数読み込み.
require_once 'const.php';

// env読み込み.
require_once 'env.php';

// toolsを読み込む.
require_once 'tools.php';

// head読み込み.
require_once 'head.php';
