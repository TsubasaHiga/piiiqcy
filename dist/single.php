<?php
/**
 * single.php
 *
 * 記事の詳細です
 *
 * @since 0.0.1
 * @package piiiQcy
 */

$page_name = 'single';

require_once 'inc/common.php';
get_header();
?>

<main id="pagetop" class="l-page">
	<div class="l-container">

		<div class="u-temp__wrap">
			<p class="u-temp__wrap--tit">post_type=postのsingleページ</p>
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
		</div>

	</div>
</main>

<?php get_footer(); ?>
