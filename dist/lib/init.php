<?php
/**
 * Init.php
 *
 * 初期設定に関する設定を行います
 *
 * @since 0.0.1
 * @package piiiQcy
 */

/**
 * Enable head <title> tag
 * */
add_theme_support( 'title-tag' );


/**
 * Enable post-thumbnails
 * */
add_theme_support( 'post-thumbnails' );


/**
 * 管理画面からのテーマとプラグインの編集を無効
 */
define( 'DISALLOW_FILE_EDIT', true );


/**
 * メジャーバージョンリリース時でも自動アップデートを有効化
 * ※デフォルトではマイナーバージョンのみ
 */
define( 'WP_AUTO_UPDATE_CORE', true );


/**
 * 画像縮小処理の無効化
 *
 * @link https://qiita.com/Fujix/items/7e99b28144c20cb5eb59
 */
add_filter( 'big_image_size_threshold', '__return_false' );


/**
 * アーカイブページでの表示件数の制限
 *
 * @param string $query Description.
 */
function change_posts_per_page( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_archive() ) {
		$query->set( 'posts_per_page', POST_PER_PAGE );
	}
}
add_action( 'pre_get_posts', 'change_posts_per_page' );


/**
 * Change excerpt length
 *
 * @param number $length .
 * @return number
 */
function change_excerpt_length( $length ) {
	if ( 'event' === get_post_type() ) {
		$length = EXCERPT_LENGTH_EVENT;
	} else {
		$length = EXCERPT_LENGTH;
	}
	return $length;
}
add_filter( 'excerpt_length', 'change_excerpt_length', 999 );


/**
 * Change excerpt more
 *
 * @param string $more .
 * @return string
 */
function change_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'change_excerpt_more' );


/**
 * Change title separator
 *
 * @link https://www.webdesignleaves.com/pr/wp/wp_func_title_tag.html
 * @param string $sep .
 * @return $sep
 */
function change_title_separator( $sep ) {
	$sep = '|';
	return $sep;
}
add_filter( 'document_title_separator', 'change_title_separator' );
