<?php
/**
 * Embeds analytics tracking functionality within the <footer> element.
 *
 * This file contains code for integrating analytics in the footer section,
 * ensuring that tracking scripts are loaded appropriately.
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
