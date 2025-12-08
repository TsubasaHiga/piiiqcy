<?php
/**
 * Displays detailed information for an article.
 *
 * This page renders the complete details of a single article, including its content,
 * metadata, and any associated components.
 *
 * @since 1.0.0
 */

$page_name = 'single';
require_once 'inc/common.php';

// パンくず.
$page_relation_list = get_page_relation_list();
?>
<?php get_template_part( 'template-parts/header' ); ?>

<div class="l-container l-spacer">
	<div class="l-page">
		<?php get_breadcrumbs( $page_relation_list ); ?>
		<small>post_type=postのsingleページ</small>
		<article class="p-content-section">
			<!-- 共通プロジェクトスタイル: p-content-section -->
			<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/template-single' );
				}
			} else {
				get_template_part( 'template-parts/no-content' );
			}
			wp_reset_postdata();
			?>
		</article>
	</div>
</div>

<?php get_template_part( 'template-parts/footer' ); ?>
