<?php
/**
 * Displays the archive.
 *
 * This script handles the rendering of archive pages, ensuring that content
 * is properly organized and presented.
 *
 * @since 1.0.0
 */

global $wp_query;

$page_name = 'archive';
require_once 'inc/common.php';

// パンくず.
$page_relation_list = get_page_relation_list();
?>
<?php get_template_part( 'template-parts/header' ); ?>

<div class="l-container l-spacer">
	<div class="l-page">
		<?php get_breadcrumbs( $page_relation_list ); ?>
		<section class="bg-gray-200 rounded-lg p-10 my-10">
			<?php
			$getterm = get_current_term();

			echo '<h1>「' . esc_html( $getterm->name ) . '」のアーカイブ</h1>';

			if ( have_posts() ) {
				echo '<ul>';
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/template-title' );
				}
				echo '</ul>';
			} else {
				get_template_part( 'template-parts/no-posts' );
			}

			if ( function_exists( 'get_pagination' ) ) {
				get_pagination( $wp_query->max_num_pages, get_query_var( 'paged' ) );
			}

			wp_reset_postdata();
			?>
		</section>
	</div>
</div>

<?php get_template_part( 'template-parts/footer' ); ?>
