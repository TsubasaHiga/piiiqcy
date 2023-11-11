<?php
/**
 * inc.vite.php
 *
 * @since 0.0.1
 * @package piiiQcy
 */

//define
define( 'DIST_URI', get_template_directory_uri() . '/' );
define( 'DIST_PATH', get_template_directory() . '/' );
define( 'ASSETS_PATH', 'assets/' );

define( 'VITE_SERVER', 'http://localhost:3000' );
define( 'VITE_ENTRY_POINT', '/src/scripts/main.ts' );

function add_scripts() {
	if ( defined( 'IS_VITE_DEVELOPMENT' ) && IS_VITE_DEVELOPMENT === true ) {
		//develop mode
		function cors_http_header() {
			header( 'Access-Control-Allow-Origin: *' );
		}
		add_action( 'send_headers', 'cors_http_header' );

		// add vite dev server
		wp_enqueue_script(
			'vite-script',
			VITE_SERVER . VITE_ENTRY_POINT,
			'',
			true,
			true
		);

	} else {
		// production mode, 'npm run build' must be executed in order to generate assets

		// read manifest.json to figure out what to enqueue
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$manifest = json_decode( file_get_contents( esc_url_raw( DIST_PATH . ASSETS_PATH . 'manifest.json' ) ), true );

		// is ok
		if ( is_array( $manifest ) ) {

			// get first key, by default is 'main.js'
			$manifest_key = array_keys( $manifest );
			if ( isset( $manifest_key[0] ) ) {
				// enqueue CSS files
				foreach ( $manifest['src/scripts/main.css'] as $css_file ) {
					wp_enqueue_style(
						'main',
						DIST_URI . ASSETS_PATH . $css_file,
						'',
						true
					);
				}
				// enqueue main JS file
				$js_file = $manifest['src/scripts/main.ts']['file'];
				if ( ! empty( $js_file ) ) {
					wp_enqueue_script(
						'main',
						DIST_URI . ASSETS_PATH . $js_file,
						'',
						true,
						true
					);
				}
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'add_scripts' );
