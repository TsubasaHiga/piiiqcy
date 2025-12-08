<?php
/**
 * Template Name: TOP
 *
 * @since 1.0.0
 */

$page_name = 'top';
require_once 'inc/common.php';
?>
<?php get_template_part( 'template-parts/header' ); ?>

<div class="l-container l-spacer">
	<div class="l-page">
		<!-- トップページ固有: ヒーローセクション -->
		<div class="top-hero">
			<h1 class="top-hero__title">piiiQcy WordPress Boilerplate</h1>
			<p class="top-hero__description">Vite + TypeScript + SCSS で構築されたモダンな WordPress テーマ開発用ボイラープレート</p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>about-page/" class="c-button">About</a>
		</div>

		<!-- トップページ固有: 投稿一覧セクション -->
		<section class="top-posts">
			<h2 class="top-posts__title">最新の投稿</h2>
			<div class="top-posts__list">
				<?php
				$query = new WP_Query( Query_Optimizer::build_query_args( 'post', 4 ) );
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						get_template_part( 'template-parts/post-item' );
					}
				} else {
					get_template_part( 'template-parts/no-posts' );
				}
				wp_reset_postdata();
				?>
			</div>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ); ?>" class="c-button c-button--secondary c-button--sm">すべての投稿を見る</a>
		</section>

		<!-- トップページ固有: カテゴリーセクション -->
		<section class="top-categories">
			<h2 class="top-categories__title">カテゴリー</h2>
			<ul class="top-categories__list">
				<?php
				$taxonomies = 'category';
				$terms      = get_terms( $taxonomies );
				foreach ( $terms as $value ) {
					echo '<li>';
					$term_link = get_term_link( $value->slug, $taxonomies );
					echo '<a class="c-text-link" href="' . esc_html( $term_link ) . '">' . esc_html( $value->name ) . '</a>';
					echo ' (' . esc_html( $value->count ) . ')';
					echo '</li>';
				}
				?>
			</ul>
		</section>
	</div>
</div>

<?php get_template_part( 'template-parts/footer' ); ?>
