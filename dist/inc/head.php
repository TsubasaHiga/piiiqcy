<?php
/**
 * Head.php
 *
 * <head>内関連をまとめて記述します
 *
 * @since 0.0.1
 * @package piiiQcy
 */

?>

<?php global $page_name; ?>
<!DOCTYPE html>
<html lang="ja">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width">
		<meta name="format-detection" content="telephone=no">
		<meta name="theme-color" content="<%= siteSetting.themeColor %>">
		<link rel="shortcut icon" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/favicon.ico">
		<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
		<link rel="preload" as="style" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/css/style.css?rev">
		<link rel="preload" as="script" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/bundle.js?rev">
		<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/css/style.css?rev">
		<?php
		wp_head();
		require_once 'analytics-head.php';
		?>
	</head>

	<body id="gotop" class="<?php echo esc_html( $page_name ); ?>">
	<?php require_once 'analytics-body.php'; ?>
