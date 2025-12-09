<?php
/**
 * Initializes the essential configurations for WordPress.
 *
 * This file is responsible for setting up the core parameters required to
 * properly initialize and run the WordPress environment. Adjust these
 * configurations as needed when updating or customizing the startup process.
 *
 * @since 1.0.0
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
 * Set post thumbnail size.
 *
 * @source Twenty Twenty 1.0 .
 */
set_post_thumbnail_size( 1200, 9999 );

/*
 * Switch default core markup for search form, comment form, and comments to output valid HTML5.
 *
 * @source Twenty Twenty 1.0 .
 */
add_theme_support(
	'html5',
	array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'script',
		'style',
	)
);


/**
 * Add support for responsive embeds.
 *
 * @source Twenty Twenty 1.0 .
 */
add_theme_support( 'responsive-embeds' );


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
 * @param object $query Description.
 */
add_action( 'pre_get_posts', 'change_posts_per_page' );
function change_posts_per_page( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_archive() ) {
		$query->set( 'posts_per_page', POST_PER_PAGE );
	}
}


/**
 * Change excerpt length
 *
 * @param number $length .
 * @return number
 */
add_filter( 'excerpt_length', 'change_excerpt_length', 999 );
function change_excerpt_length( $length ) {
	if ( 'event' === get_post_type() ) {
		$length = EXCERPT_LENGTH_EVENT;
	} else {
		$length = EXCERPT_LENGTH;
	}
	return $length;
}


/**
 * Change excerpt more
 *
 * @param string $more .
 * @return string
 */
add_filter( 'excerpt_more', 'change_excerpt_more' );
function change_excerpt_more() {
	return '...';
}
