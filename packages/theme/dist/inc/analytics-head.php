<?php
/**
 * Inserts analytics tracking code into the <head> section.
 *
 * This file contains analytics integration code, ensuring that tracking scripts
 * are embedded appropriately within the head for optimal performance.
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
