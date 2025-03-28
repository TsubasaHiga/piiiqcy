<?php
/**
 * Registers additional features not available in the default WordPress installation.
 *
 * @since 1.0.0
 */

/**
 * メディアアップロード時にファイル名をMD5にリネーム.
 *
 * @package wp_template
 *
 * @link https://github.com/Sydsvenskan/sds-md5-on-upload/blob/master/sds-md5-on-upload.php
 * @param string $file ファイル.
 */
add_filter( 'wp_handle_upload_prefilter', 'rename_upload_file', 1, 1 );
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

/**
 * wp_enqueue_scriptで読み込んだスクリプトにtype="module"を追加
 * @phpstan-ignore arguments.count
 */
add_filter( 'script_loader_tag', 'add_module_type_attribute', 10, 3 );
function add_module_type_attribute( $tag, $handle ) {
	// PAGE_SCRIPTS_STYLES_LISTからhandleのみの配列を作成
	$allowed_scripts = array_column( PAGE_SCRIPTS_STYLES_LIST, 'handle' );

	// 'main'を先頭に追加
	array_unshift( $allowed_scripts, 'main' );

	if ( in_array( $handle, $allowed_scripts, true ) ) {
		// type属性を追加
		$tag = str_replace( '<script ', '<script type="module" ', $tag );
	}
	return $tag;
}

/**
 * 管理画面にアイコンを追加
 */
add_action( 'admin_head', 'load_icons' );
function load_icons() {
	require_once __DIR__ . '/../parts/common/icons.php';
}

/**
 * get_the_title()で取得したタイトルをフックして空白の場合はデフォルトタイトルを返す
 */
add_filter( 'the_title', 'get_title' );
function get_title( $title ) {
	if ( $title ) {
		return $title;
	} else {
		return DEFAULT_TITLE;
	}
}
