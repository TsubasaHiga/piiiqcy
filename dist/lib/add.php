<?php
/**
 * Add.php
 *
 * WordPressのデフォルトに無い機能を追加する為の指定を行います。
 *
 * @since 0.0.1
 * @package piiiQcy
 */

/** 管理画面用のオリジナルCSSファイルを追加 */
function custom_enqueue() {
	wp_enqueue_style( 'custom_css', get_template_directory_uri() . '/assets/css/wp_admin.css', array(), true );
}
add_action( 'admin_enqueue_scripts', 'custom_enqueue' );


/**
 * メディアアップロード時にファイル名をMD5にリネーム.
 *
 * @package wp_template
 *
 * @link https://github.com/Sydsvenskan/sds-md5-on-upload/blob/master/sds-md5-on-upload.php
 * @param string $file ファイル.
 */
function rename_upload_file( $file ) {

	$name = $file['name'];
	$md5  = md5( "$name{$file['size']}{$file['tmp_name']}" . time() );

	/**
	 * Get file suffix
	 */
	$pos = strrpos( $name, '.' );

	if ( ! $pos ) {
		$md5_name = basename( "{$md5}" );
	} else {
		$md5_name = basename( "{$md5}" . substr( $name, $pos ) );
	}

	$file['name'] = $md5_name;

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'rename_upload_file', 1, 1 );


/**
 * アーカイブページにmetaタグを追加
 */
function add_archivespage_metatag() {

	if ( is_post_type_archive() || is_tax() ) {
		// All In One SEO Pack.
		global $aioseop_options;

		$title         = wp_get_document_title();
		$site_name     = get_bloginfo( 'name' );
		$home_ogpimage = isset( $aioseop_options ) ? $aioseop_options['modules']['aiosp_opengraph_options']['aiosp_opengraph_homeimage'] : OGPIMAGE;

		// Posttype archives.
		if ( is_post_type_archive() ) {
			$post_type = c_get_archive_slug();
			$url       = get_home_url( null, '/' ) . $post_type . '/';
			if ( 'news' === $post_type ) {
				$desc = 'ニュースのdescriptionを設定します。';
			} elseif ( 'event' === $post_type ) {
				$desc = 'イベントのdescriptionを設定します。';
			}
		}

		// Taxonomy archives.
		if ( is_tax() ) {
			$term      = c_get_current_term();
			$term_name = $term->name;
			$term_slug = $term->slug;
			$term_tax  = $term->taxonomy;
			$url       = get_tag_link( $term->term_id );

			if ( 'news_tax' === $term_tax ) {
				$desc = $term_slug . 'のdescriptionを設定します。';
			} elseif ( 'event_tax' === $term_tax ) {
				$desc = $term_slug . 'のdescriptionを設定します。';
			}
		}

		$meta = <<< EOM
		<meta name="description" content="{$desc}">
		<meta property="og:type" content="website">
		<meta property="og:title" content="{$title}">
		<meta property="og:description" content="{$desc}">
		<meta property="og:url" content="{$url}">
		<meta property="og:site_name" content="{$site_name}">
		<meta property="og:image" content="{$home_ogpimage}">
		<meta property="og:image:width" content="1200">
		<meta property="og:image:height" content="630">
		<meta name="twitter:card" content="summary_large_image">
EOM;

	// @codingStandardsIgnoreStart
	echo $meta;
	// @codingStandardsIgnoreEnd
	}
}
add_action( 'wp_head', 'add_archivespage_metatag' );


/**
 * All In One SEO Packのタイトルをpagedで変更
 *
 * @param string $title .
 * @return $title
 */
function custom_aioseop_title( $title ) {
	if ( is_paged() ) {
		$title = wp_get_document_title();
	}
	return $title;
}
add_filter( 'aioseop_title', 'custom_aioseop_title' );
