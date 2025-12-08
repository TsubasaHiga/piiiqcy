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
		<section class="p-content-section">
			<!-- 共通プロジェクトスタイル: p-content-section -->
			<?php
			$getterm = Category_Helper::get_current_term();

			echo '<h1>「' . esc_html( $getterm->name ) . '」のアーカイブ</h1>';

			if ( have_posts() ) {
				echo '<div class="p-archive-list">';
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/post-item' );
				}
				echo '</div>';
			} else {
				get_template_part( 'template-parts/no-posts' );
			}

			get_pagination( $wp_query->max_num_pages, get_query_var( 'paged' ) );

			wp_reset_postdata();
			?>
		</section>
	</div>
</div>

<?php get_template_part( 'template-parts/footer' ); ?>
