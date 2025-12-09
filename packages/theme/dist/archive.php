<?php
/**
 * Displays the archive.
 *
 * This template handles the rendering of archive pages including:
 * - Category archives
 * - Tag archives
 * - Date archives (year, month, day)
 * - Author archives
 *
 * @since 1.0.0
 */

global $wp_query;

$page_name = 'archive';
require_once 'inc/common.php';

// Get page relation list for breadcrumbs.
$page_relation_list = get_page_relation_list();
?>
<?php get_header(); ?>

<div class="l-container l-spacer">
	<div class="l-page">
		<?php get_breadcrumbs( $page_relation_list ); ?>
		<section class="c-section">
			<h1 class="c-section__title">
				<?php the_archive_title(); ?>
			</h1>
			<?php if ( get_the_archive_description() ) : ?>
				<div class="c-section__description">
					<?php the_archive_description(); ?>
				</div>
			<?php endif; ?>
			<div class="c-section__content">
				<?php
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();
						get_template_part( 'template/post-item' );
					}
				} else {
					get_template_part( 'template/no-posts' );
				}
				?>
			</div>
		</section>
		<?php get_pagination( $wp_query->max_num_pages, get_query_var( 'paged' ) ); ?>
	</div>
</div>

<?php get_footer(); ?>
