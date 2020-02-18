<?php
/**
 * Init.php
 *
 * 初期設定に関する設定を行います
 *
 * @since 0.0.1
 * @package piiiQcy
 */

?>

<?php
/** -----------------------------------------------------------
 *
 * アーカイブページでの表示件数の制限
 *
 * @param string $query Description.
 */
function change_posts_per_page( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_archive() ) {
		$query->set( 'posts_per_page', POST_PER_PAGE );
	}
}
add_action( 'pre_get_posts', 'change_posts_per_page' );
