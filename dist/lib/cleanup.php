<?php
/**
 * cleanup.php
 *
 * WordPressのデフォルトの機能を削除、またはクリーンアップする為の指定を行います。
 *
 * @since 0.0.1
 * @package piiiQcy
 */

?>

<?php
/** -----------------------------------------------------------
 *
 * ダッシュボード系
 *
 * -----------------------------------------------------------*/
/** WordPressへようこそ!を削除 */
remove_action( 'welcome_panel', 'wp_welcome_panel' );
/** ウィジェットを削除 */
add_action( 'wp_dashboard_setup', 'remove_dashboard_widget' );
/** アクティビティ、クイックドラフト、WordPressニュースの削除 */
function remove_dashboard_widget() {
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
}



/** -----------------------------------------------------------
 *
 * WordPressデフォルト機能をOFF
 *
 * -----------------------------------------------------------*/
/** コメント機能 */
add_filter( 'comments_open', '__return_false' );
/** 管理画面のadminbarの表示 */
add_filter( 'show_admin_bar', '__return_false' );



/** -----------------------------------------------------------
 *
 * 自動ソースの吐き出しを抑制
 *
 * -----------------------------------------------------------*/
/** Emoji系 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
/** OEmbed系 */
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );
/** Wp-json系 */
remove_action( 'wp_head', 'rest_output_link_wp_head' );
/** EditURI */
remove_action( 'wp_head', 'rsd_link' );
/** Wlwmanifest */
remove_action( 'wp_head', 'wlwmanifest_link' );
/** WordPressバージョン出力metaタグ非表示 */
remove_action( 'wp_head', 'wp_generator' );
/** Rel linkの削除 */
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
/** Canonical */
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );
/** DNSプリフェッチコード挿入を削除 */
add_filter( 'wp_resource_hints', 'remove_dns_prefetch', 10, 2 );
/** Gutenbergの読み込みスタイルを削除 */
add_action( 'wp_enqueue_scripts', 'remove_block_library_style' );
/** Remove_block_library_style */
function remove_block_library_style() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
}



/** -----------------------------------------------------------
 *
 * アクティビティ、クイックドラフト、WordPressニュースの削除
 *
 * @param string $hints Description.
 * @param string $relation_type Description.
 * @return $hints
 * -----------------------------------------------------------*/
function remove_dns_prefetch( $hints, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		return array_diff( wp_dependencies_unique_hosts(), $hints );
	}
	return $hints;
}



/** -----------------------------------------------------------
 *
 * 管理画面の左メニューの一部を削除
 *
 * -----------------------------------------------------------*/
function remove_menus() {
	/** コメント */
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'remove_menus' );
