<?php
/**
 * inc.vite.php
 *
 * @since 1.0.0
 */

/**
 * スクリプトのエンキューラッパー
 */
function enque_script( $handle, $src ) {
	wp_enqueue_script(
		$handle,
		$src,
		'',
		true,
		true
	);
}

/**
 * スタイルのエンキューラッパー
 */
function enque_style( $handle, $src ) {
	wp_enqueue_style(
		$handle,
		$src,
		'',
		true
	);
}

/**
 * ページのスクリプトとスタイルのエンキュー
 */
function add_page_scripts_styles( $manifest, $handle ) {
	$page_manifest = $manifest[ 'src/scripts/' . $handle . '.ts' ];
	if ( ! is_array( $page_manifest ) ) {
		return;
	}

	// enqueue CSS files
	if ( ! empty( $page_manifest['css'] ) ) {
		foreach ( $page_manifest['css'] as $css_file ) {
			enque_style( $handle, DIST_URI . ASSETS_PATH . $css_file );
		}
	}

	// enqueue main JS file
	$js_file = $page_manifest['file'];
	if ( ! empty( $js_file ) ) {
		enque_script( $handle, DIST_URI . ASSETS_PATH . $js_file );
	}
}

/**
 * 各種スクリプトとスタイルのエンキュー
 */
add_action( 'wp_enqueue_scripts', 'add_scripts' );
function add_scripts() {
	global $page_name;

	if ( defined( 'IS_VITE_DEVELOPMENT' ) && IS_VITE_DEVELOPMENT === true ) {
		//develop mode
		add_action( 'send_headers', 'cors_http_header' );
		function cors_http_header() {
			header( 'Access-Control-Allow-Origin: *' );
		}

		// add vite dev server
		enque_script( 'main', VITE_SERVER . VITE_ENTRY_POINT . 'main.ts' );

		// add page specific scripts
		// PAGE_SCRIPTS_STYLES_LISTを参照して、$page_nameと一致する場合にページごとのスクリプトを追加
		foreach ( PAGE_SCRIPTS_STYLES_LIST as $page ) {
			if ( $page['target_page_name'] === $page_name ) {
				$handle = $page['handle'];
				enque_script( $handle, VITE_SERVER . VITE_ENTRY_POINT . $handle . '.ts' );
				break;
			}
		}
	} else {
		// production mode, 'npm run build' must be executed in order to generate assets

		// read manifest.json to figure out what to enqueue
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$manifest = json_decode( file_get_contents( esc_url_raw( DIST_PATH . ASSETS_PATH . '.vite/manifest.json' ) ), true );

		// if there is no manifest file, do nothing
		if ( ! is_array( $manifest ) ) {
			return;
		}

		// get the first key of the manifest
			$manifest_key = array_keys( $manifest );
		if ( ! isset( $manifest_key[0] ) ) {
			return;
		}

		// add main scripts and styles
		add_page_scripts_styles( $manifest, 'main' );

		// add page specific scripts and styles
		// PAGE_SCRIPTS_STYLES_LISTを参照して、$page_nameと一致する場合にページごとのスクリプトとスタイルを追加
		foreach ( PAGE_SCRIPTS_STYLES_LIST as $page ) {
			if ( $page['target_page_name'] === $page_name ) {
				$handle = $page['handle'];
				add_page_scripts_styles( $manifest, $handle );
				break;
			}
		}
	}
}
