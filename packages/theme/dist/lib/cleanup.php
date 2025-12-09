<?php
/**
 * Configures the removal or cleanup of WordPress default functionalities.
 *
 * This file specifies settings to disable or streamline features provided by WordPress out-of-the-box.
 *
 * @since 1.0.0
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
add_filter( 'wp_headers', 'remove_x_pingback' );
function remove_x_pingback( $headers ) {
	unset( $headers['X - Pingback'] );
	return $headers;
}

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
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
/** Canonical */
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );
/** DNSプリフェッチコード挿入を削除 */
add_filter( 'wp_resource_hints', 'remove_dns_prefetch', 10, 2 );
/** Remove_block_library_style */
function remove_block_library_style() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
}

/**
 * アクティビティ、クイックドラフト、WordPressニュースの削除
 *
 * @param array  $hints Description.
 * @param string $relation_type Description.
 * @return array $hints
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
add_action( 'admin_menu', 'remove_menus' );
function remove_menus() {
	/** コメント */
	remove_menu_page( 'edit-comments.php' );
}

/**
 * サムネイル自動生成停止.
 *
 * @param string $sizes .
 * @return $sizes
 */
update_option( 'medium_large_size_w', 0 );
function remove_image_sizes( $sizes ) {
	unset( $sizes['medium'] );
	unset( $sizes['large'] );

	return $sizes;
}

/**
 * The_post_thumbnailのクラスを削除.
 *
 * @param string $output .
 * @return $output
 */
add_filter( 'post_thumbnail_html', 'wps_post_thumbnail_remove_class' );
function wps_post_thumbnail_remove_class( $output ) {
	$output = preg_replace( '/class=".*?"/', '', $output );
	return $output;
}

/**
 * WpのURL自動補完機能を停止.
 *
 * @link https://raw.githubusercontent.com/markmercedes/disable-canonical-redirect/master/disable-canonical-redirect.php
 */
add_filter(
	'redirect_canonical',
	function () {
		return false;
	}
);

/**
 * 投稿画面の不要な項目を非表示
 */
add_action( 'init', 'remove_block_editor_options' );
function remove_block_editor_options() {
	remove_post_type_support( 'post', 'excerpt' );
	remove_post_type_support( 'post', 'comments' );
	remove_post_type_support( 'post', 'trackbacks' );
}

/**
 * Remove src wp ver
 *
 * @link https://webjin.work/hide-version-of-wordpress/
 * @param [type] $dep .
 * @return void
 */
add_action( 'wp_default_scripts', 'remove_src_wp_ver' );
add_action( 'wp_default_styles', 'remove_src_wp_ver' );
function remove_src_wp_ver( $dep ) {
	$dep->default_version = '';
}

/**
 * Remove All Yoast HTML Comments
 *
 * @link https://gist.github.com/paulcollett/4c81c4f6eb85334ba076
 */
add_filter( 'wpseo_debug_markers', '__return_false' );
