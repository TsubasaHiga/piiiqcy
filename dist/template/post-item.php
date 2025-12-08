<?php
/**
 * Post Item Template
 *
 * 投稿アイテムコンポーネント。ループ内で使用する。
 *
 * @since 1.0.0
 */
?>

<a class="c-post-item" href="<?php the_permalink(); ?>">
	<time class="c-post-item__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
		<?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?>
	</time>
	<span class="c-post-item__title"><?php the_title(); ?></span>
</a>
