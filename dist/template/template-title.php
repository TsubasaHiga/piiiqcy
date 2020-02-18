<?php
/**
 * template-title
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
		<p>カテゴリ</p>
		<p><?php echo c_get_term( 'category', 'name', true, 0, 'span' ); ?></p>
		<p>タグ</p>
		<p><?php echo c_get_term( 'post_tag', 'name', true, 0, 'span' ); ?></p>
	</li>
</ul>
