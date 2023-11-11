<?php
/**
 * Template Name: TOP
 *
 * Index.phpです
 *
 * @since 0.0.1
 * @package piiiQcy
 */

$page_name = 'top';

require_once 'inc/common.php';
?>

<div class="l-container l-spacer">
	<div class="l-page">
		<section class="bg-gray-200 rounded-lg p-10 my-10">
			<h2>post_type=post取得例（4件取得 ※先頭固定表示は例外）</h2>
			<ul>
				<?php
				$query = new WP_Query( get_query_args( 'post', 4 ) );
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						get_template_part( 'template/template-title' );
					}
				} else {
					get_template_part( 'inc/parts-nopost' );
				}
				wp_reset_postdata();
				?>
			</ul>
		</section>

		<section class="bg-gray-200 rounded-lg p-10 my-10">
			<h2>post_type=post / タクソノミー名=categoryのターム取得例</h2>
			<ul style="display: flex; flex-wrap: wrap; gap: 10px">
				<?php
				$taxonomies = 'category';
				$terms      = get_terms( $taxonomies );
				foreach ( $terms as $value ) {
					echo '<li>';
					$term_link = get_term_link( $value->slug, $taxonomies );
					echo '<a class="underline hover:no-underline" href="' . esc_html( $term_link ) . '">' . esc_html( $value->name ) . '</a>';
					echo esc_html( $value->count );
					echo '</li>';
				}
				?>
			</ul>
		</section>
	</div>
</div>

<?php get_footer(); ?>
