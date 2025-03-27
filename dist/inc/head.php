<?php
/**
 * Consolidates all elements related to the <head> section.
 *
 * @since 1.0.0
 */

global $page_name;
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width">
		<meta name="format-detection" content="telephone=no">
		<meta name="theme-color" content="#000">
		<link rel="icon" type="image/svg+xml" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/favicon.svg">
		<link rel="icon" type="image/png" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/favicon.png">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<?php
		// @codingStandardsIgnoreStart
		wp_enqueue_style( 'googlefonts', 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap', array(), null );
		// @codingStandardsIgnoreEnd
		?>
		<?php wp_head(); ?>
		<?php require_once 'analytics-head.php'; ?>
	</head>

	<body class="<?php echo esc_attr( $page_name ); ?>">
		<?php require_once 'analytics-body.php'; ?>
		<?php require_once __DIR__ . '/../parts/common/icons.php'; ?>
		<main class="l-main">
