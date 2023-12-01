<?php
/**
 * Head.php
 *
 * <head>内関連をまとめて記述します
 *
 * @since 0.0.1
 * @package piiiQcy
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
		<link rel="shortcut icon" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/favicon.ico">
		<?php wp_head(); ?>
		<?php require_once 'analytics-head.php'; ?>
	</head>

	<body class="<?php echo esc_html( $page_name ); ?>">
		<?php require_once 'analytics-body.php'; ?>
		<main class="l-main">
			<?php get_header(); ?>
