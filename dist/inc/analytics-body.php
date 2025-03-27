<?php
/**
 * Analytics-body.php
 *
 * <body>内に入れるanalytics関連を記述します
 *
 * @since 1.0.0
 */

if ( getenv( 'APPLICATION_ENV' ) === 'production' ) :
// @codingStandardsIgnoreStart
?>
<!-- ここにGoogle Analytics等の解析タグを入れる -->
<?php
// @codingStandardsIgnoreEnd
endif;
?>
