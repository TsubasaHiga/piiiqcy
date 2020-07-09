<?php
/**
 * Cleanup.php
 *
 * WordPressのデフォルトの機能を削除、またはクリーンアップする為の指定を行います。
 *
 * @since 0.0.1
 * @package piiiQcy
 */

/**
 * ダッシュボード系
 */
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


/**
 * WordPressデフォルト機能をOFF
 */
/** コメント機能 */
add_filter( 'comments_open', '__return_false' );
/** 管理画面のadminbarの表示 */
add_filter( 'show_admin_bar', '__return_false' );
/** Xmlrpc.phpの無効化 */
add_filter( 'xmlrpc_enabled', '__return_false' );


/**
 * Remove x-pingback
 *
 * @param object $headers headers.
 * @return $headers
 */
function remove_x_pingback( $headers ) {
	unset( $headers['X - Pingback'] );
	return $headers;
}
add_filter( 'wp_headers', 'remove_x_pingback' );


/**
 * 自動ソースの吐き出しを抑制
 */
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


/**
 * アクティビティ、クイックドラフト、WordPressニュースの削除
 *
 * @param string $hints Description.
 * @param string $relation_type Description.
 * @return $hints
 */
function remove_dns_prefetch( $hints, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		return array_diff( wp_dependencies_unique_hosts(), $hints );
	}
	return $hints;
}


/**
 * 管理画面の左メニューの一部を削除
 */
function remove_menus() {
	/** コメント */
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'remove_menus' );


/**
 * サムネイル自動生成停止.
 *
 * @param string $sizes .
 * @return $sizes
 */
function remove_image_sizes( $sizes ) {
	unset( $sizes['medium'] );
	unset( $sizes['large'] );

	return $sizes;
}
add_filter( 'intermediate_image_sizes_advanced', 'remove_image_sizes' );
update_option( 'medium_large_size_w', 0 );


/**
 * The_post_thumbnailのクラスを削除.
 *
 * @param string $output .
 * @return $output
 */
function wps_post_thumbnail_remove_class( $output ) {
	$output = preg_replace( '/class=".*?"/', '', $output );
	return $output;
}
add_filter( 'post_thumbnail_html', 'wps_post_thumbnail_remove_class' );


/**
 * WpのURL自動補完機能を停止.
 *
 * @link https://raw.githubusercontent.com/markmercedes/disable-canonical-redirect/master/disable-canonical-redirect.php
 */
add_filter(
	'redirect_canonical',
	function( $redirect_url ) {
		return false;
	}
);


/**
 * DisableRestApi.
 *
 * @param string $result .
 * @param string $wp_rest_server .
 * @param string $request .
 * @return WP_Error.
 */
function disable_rest_api( $result, $wp_rest_server, $request ) {

	return new WP_Error( 'rest_disabled', __( 'The REST API on this site has been disabled.' ), array( 'status' => rest_authorization_required_code() ) );
}

add_filter( 'rest_pre_dispatch', 'disable_rest_api', 10, 3 );
