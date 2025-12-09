<?php
/**
 * Plugin Name:       Sample Block
 * Plugin URI:        https://example.com/sample-block
 * Description:       A sample custom block for demonstration purposes.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            Your Name
 * Author URI:        https://example.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sample-block
 * Domain Path:       /languages
 *
 * @package SampleBlock
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin constants.
 */
define( 'SAMPLE_BLOCK_VERSION', '1.0.0' );
define( 'SAMPLE_BLOCK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SAMPLE_BLOCK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it also registers all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 * @return void
 */
function sample_block_init(): void {
	// Check if build directory exists.
	$build_dir = SAMPLE_BLOCK_PLUGIN_DIR . 'build';

	if ( ! file_exists( $build_dir ) ) {
		return;
	}

	// Register the block.
	register_block_type( $build_dir );

	// Register block script.
	if ( file_exists( $build_dir . '/index.js' ) ) {
		wp_register_script(
			'sample-block-editor',
			SAMPLE_BLOCK_PLUGIN_URL . 'build/index.js',
			array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
			SAMPLE_BLOCK_VERSION,
			true
		);
	}

	// Register block styles.
	if ( file_exists( $build_dir . '/style.css' ) ) {
		wp_register_style(
			'sample-block-style',
			SAMPLE_BLOCK_PLUGIN_URL . 'build/style.css',
			array(),
			SAMPLE_BLOCK_VERSION
		);
	}
}
add_action( 'init', 'sample_block_init' );
