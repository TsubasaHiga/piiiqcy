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
		<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
		<link rel="preload" as="style" href="<?php get_assetspath( '/assets/css/style.css' ); ?>">
		<link rel="preload" as="script" href="<?php get_assetspath( '/assets/js/bundle.js' ); ?>">
<?php // @codingStandardsIgnoreStart ?>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400;500;600;700&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="<?php get_assetspath( '/assets/css/style.css' ); ?>">
<?php // @codingStandardsIgnoreEnd?>
		<?php
		wp_head();
		require_once 'analytics-head.php';
		?>
	</head>

	<body id="gotop" class="<?php echo esc_html( $page_name ); ?>">
	<?php require_once 'analytics-body.php'; ?>
		<div id="mainwrap" class="mainwrap">
			<div class="mainwrap__inner">
				<?php
				get_header();
				?>
