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

/**
 * the_content()で出力される画像にlazyloadを追加
 */
add_filter( 'the_content', 'add_lazyload', 10 );
function add_lazyload( $content ) {
	$content = str_replace( '<img', '<img loading="lazy"', $content );
	return $content;
}

/**
 * コンテンツ内のh2タグにidを付与する
 */
add_filter( 'the_content', 'add_unique_id_to_h2' );
function add_unique_id_to_h2( $content ) {
	$index = 0;
	return preg_replace_callback(
		'/<h2(.*?)>(.*?)<\/h2>/i',
		function ( $matches ) use ( &$index ) {
			$index++;
			$id = sanitize_title( $matches[2] ) . '-' . $index;
			return '<h2 id="' . $id . '"' . $matches[1] . '>' . $matches[2] . '</h2>';
		},
		$content
	);
}

/**
 * 外部リンクに target="_blank" と rel="noopener noreferrer" を追加
 *
 * 投稿・固定ページ内の外部リンクに対して、新しいタブで開く属性を自動付与する。
 * 内部リンク、アンカーリンク、mailto、tel リンクはスキップする。
 *
 * @since 1.0.0
 * @param string $content 投稿コンテンツ
 * @return string 処理後のコンテンツ
 */
add_filter( 'the_content', 'add_target_blank_to_external_links' );
function add_target_blank_to_external_links( $content ) {
	if ( empty( $content ) ) {
		return $content;
	}

	$site_url = home_url();

	return preg_replace_callback(
		'/<a\s+([^>]*href=["\']([^"\']+)["\'][^>]*)>/i',
		function ( $matches ) use ( $site_url ) {
			$full_tag   = $matches[0];
			$attributes = $matches[1];
			$href       = $matches[2];

			// 内部リンク、アンカー、mailto、tel はスキップ
			if (
				strpos( $href, $site_url ) === 0 ||
				strpos( $href, '/' ) === 0 ||
				strpos( $href, '#' ) === 0 ||
				strpos( $href, 'mailto:' ) === 0 ||
				strpos( $href, 'tel:' ) === 0
			) {
				return $full_tag;
			}

			// 既に target 属性がある場合はスキップ
			if ( preg_match( '/\btarget\s*=/i', $attributes ) ) {
				return $full_tag;
			}

			// target="_blank" と rel="noopener noreferrer" を追加
			$new_attributes = $attributes . ' target="_blank" rel="noopener noreferrer"';
			return '<a ' . $new_attributes . '>';
		},
		$content
	);
}
