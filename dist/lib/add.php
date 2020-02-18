<?php
/**
 * Add.php
 *
 * WordPressのデフォルトに無い機能を追加する為の指定を行います。
 *
 * @since 0.0.1
 * @package piiiQcy
 */

?>

<?php

/** 管理画面用のオリジナルCSSファイルを追加 */
function custom_enqueue() {
	wp_enqueue_style( 'custom_css', get_template_directory_uri() . '/assets/css/wp_admin.css', array(), true );
}
add_action( 'admin_enqueue_scripts', 'custom_enqueue' );
