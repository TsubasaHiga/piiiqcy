<?php
/**
 * Configuration for loading page-specific scripts and styles.
 *
 * This file establishes settings to include necessary scripts and styles
 * tailored to each page's requirements.
 *
 * @since 1.0.0
 */

$data = array(
	array(
		'target_page_name' => 'top',
		'handle'           => 'pageTop',
	),
	array(
		'target_page_name' => 'about',
		'handle'           => 'pageAbout',
	),
);

define( 'PAGE_SCRIPTS_STYLES_LIST', $data );
