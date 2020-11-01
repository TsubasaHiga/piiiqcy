<?php
/**
 * Template-title
 *
 * タイトルループ用テンプレート
 *
 * @since 0.0.1
 * @package piiiQcy
 */

?>

<ul>
	<li>
		<p><span>date</span><?php echo get_the_date( 'Y.m.d', $post->ID ); ?></p>
		<p>
			<a href="<?php echo esc_html( get_the_permalink( $post->ID ) ); ?>">
				<span>title</span><?php the_title_attribute(); ?>
			</a>
		</p>
	</li>
</ul>
