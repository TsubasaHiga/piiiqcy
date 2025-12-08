<?php
/**
 * Article Loop Template Sample
 *
 * This template demonstrates how to structure and display content within an article loop.
 * It serves as a reference for iterating over and presenting individual articles.
 *
 * @since 1.0.0
 */

?>

<?php
	echo '<p>' . get_the_date( 'Y.m.d', $post->ID ) . '</p>';
	the_title( '<h1>', '</h1>' );
	the_content();
