<?php
/**
 * Template-single.php
 *
 * 記事ループ用テンプレート
 *
 * @since 1.0.0
 */

?>

<?php
	echo '<a>' . esc_html( get_the_permalink( $post->ID ) ) . '</a>';
	echo '<p>' . get_the_date( 'Y.m.d', $post->ID ) . '</p>';
	the_title( '<h1>', '</h1>' );
	the_content();
