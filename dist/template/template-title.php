<?php
/**
 * Template-title
 *
 * タイトルループ用テンプレート
 *
 * @since 1.0.0
 */

?>

<li>
	<p><?php echo get_the_date( 'Y.m.d', $post->ID ); ?></p>
	<a class="block leading-6 underline hover:no-underline" href="<?php echo esc_html( get_the_permalink( $post->ID ) ); ?>">
		<?php the_title_attribute(); ?>
	</a>
</li>
