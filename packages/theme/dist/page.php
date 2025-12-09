<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @since 1.0.0
 */

$page_name = 'page';
require_once 'inc/common.php';

// Get page relation list for breadcrumbs.
$page_relation_list = get_page_relation_list();
?>
<?php get_header(); ?>

<div class="l-container l-spacer">
	<div class="l-page">
		<?php get_breadcrumbs( $page_relation_list ); ?>
		<article class="p-content-section">
			<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					?>
					<header class="c-section">
						<h1 class="c-section__title"><?php the_title(); ?></h1>
					</header>
					<div class="c-section__content">
						<?php the_content(); ?>
					</div>
					<?php
				}
			} else {
				get_template_part( 'template/no-content' );
			}
			wp_reset_postdata();
			?>
		</article>
	</div>
</div>

<?php get_footer(); ?>
