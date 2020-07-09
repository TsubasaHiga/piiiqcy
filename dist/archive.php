<?php
/**
 * Archive.php
 *
 * アーカイブの表示を行います
 *
 * @since 0.0.1
 * @package piiiQcy
 */

$page_name = 'archive';

require_once 'inc/common.php';
get_header();
?>

<main id="pagetop" class="l-page">
	<div class="l-container">

		<div class="u-temp__wrap">
			<p class="u-temp__wrap--tit">archive取得</p>
			<?php
			$getterm = c_get_current_term();

			echo '<h1>「' . esc_html( $getterm->name ) . '」のアーカイブ</h1>';

			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template/template-title' );
				}
			} else {
				get_template_part( 'inc/parts-nopost' );
			}

			if ( function_exists( 'pagination' ) ) {
				pagination( $wp_query->max_num_pages, get_query_var( 'paged' ) );
			}

			wp_reset_postdata();
			?>
		</div>

	</div>
</main>

<?php get_footer(); ?>
