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
?>

<div class="l-container l-spacer">
	<div class="l-page">
		<section class="bg-gray-200 rounded-lg p-10 my-10">
			<?php
			$getterm = get_current_term();

			echo '<h1>「' . esc_html( $getterm->name ) . '」のアーカイブ</h1>';

			if ( have_posts() ) {
				echo '<ul>';
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template/template-title' );
				}
				echo '</ul>';
			} else {
				get_template_part( 'inc/parts-nopost' );
			}

			if ( function_exists( 'get_pagination' ) ) {
				get_pagination( $wp_query->max_num_pages, get_query_var( 'paged' ) );
			}

			wp_reset_postdata();
			?>
		</section>
	</div>
</div>

<?php get_footer(); ?>
