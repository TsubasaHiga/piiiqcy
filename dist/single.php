<?php
/**
 * Single.php
 *
 * 記事の詳細です
 *
 * @since 1.0.0
 */

$page_name = 'single';

require_once 'inc/common.php';
?>

<div class="l-container l-spacer">
	<div class="l-page">
		<small>post_type=postのsingleページ</small>
		<article class="bg-gray-200 rounded-lg p-10 my-10">
			<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template/template-single' );
				}
			} else {
				get_template_part( 'inc/parts-nopost' );
			}
			wp_reset_postdata();
			?>
		</article>
	</div>
</div>

<?php get_footer(); ?>
