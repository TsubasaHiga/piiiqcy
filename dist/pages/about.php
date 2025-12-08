<?php
/**
 * Template Name: about
 *
 * @since 1.0.0
 */

$page_name = 'about';
require_once __DIR__ . '/../inc/common.php';

// パンくず.
$page_relation_list = get_page_relation_list();
?>
<?php get_template_part( 'template-parts/header' ); ?>

<div class="l-container l-spacer">
	<div class="l-page">
		<?php get_breadcrumbs( $page_relation_list ); ?>
		<section class="p-content-section">
			<!-- contents -->
			about
		</section>
	</div>
</div>

<?php get_template_part( 'template-parts/footer' ); ?>
