<?php
/**
 * Inserts analytics-related code into the <body> tag.
 *
 * This file comprises the necessary scripts and HTML elements for analytics tracking,
 * ensuring they are properly embedded within the page's body.
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
