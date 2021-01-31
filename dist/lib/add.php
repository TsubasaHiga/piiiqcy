<?php
/**
 * Add.php
 *
 * WordPressのデフォルトに無い機能を追加する為の指定を行います。
 *
 * @since 0.0.1
 * @package piiiQcy
 */

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
 * 再利用ブロックへのリンクを追加
 *
 * @link https://qiita.com/tbshiki/items/14e733e44f9266c8baf7
 */
function add_reuse() {
	add_menu_page( '再利用ブロック', '再利用ブロック', 'manage_options', 'edit.php?post_type=wp_block', '', 'dashicons-controls-repeat', '98.9' );
}
add_action( 'admin_menu', 'add_reuse' );
