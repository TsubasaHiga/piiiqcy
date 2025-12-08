<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @since 1.0.0
 */

$page_name = 'top';
require_once 'inc/common.php';
?>
<?php get_header(); ?>

<div class="l-container l-spacer">
	<section class="top-hero">
		<p class="top-hero__badge">WordPress Theme Boilerplate</p>
		<h1 class="top-hero__title">piiiQcy</h1>
		<p class="top-hero__tagline">Vite + TypeScript + Docker</p>
		<p class="top-hero__description">モダンな WordPress テーマ開発を、もっと快適に。</p>
		<div class="top-hero__actions">
			<a href="https://github.com/TsubasaHiga/piiiqcy" class="c-button" target="_blank" rel="noopener noreferrer">Get Started</a>
			<a href="https://github.com/TsubasaHiga/piiiqcy/blob/master/README.md" class="c-button c-button--secondary" target="_blank" rel="noopener noreferrer">Documentation</a>
		</div>
		<ul class="top-hero__tech">
			<li>Vite 7</li>
			<li>TypeScript</li>
			<li>SCSS</li>
			<li>Tailwind CSS v4</li>
			<li>Docker</li>
			<li>phpcs / phpstan</li>
		</ul>
	</section>
	<div class="l-page">
		<div class="c-section-group">
			<div class="c-section-group__header">
				<p class="c-section-group__title">WordPress Contents</p>
			</div>
			<div class="c-section-group__content">
				<section class="c-section">
					<h2 class="c-section__title">最新の投稿</h2>
					<div class="c-section__content">
						<?php
						$query = new WP_Query( Query_Optimizer::build_query_args( 'post', 4 ) );
						if ( $query->have_posts() ) {
							while ( $query->have_posts() ) {
								$query->the_post();
								get_template_part( 'template/post-item' );
							}
						} else {
							get_template_part( 'template/no-posts' );
						}
						wp_reset_postdata();
						?>
					</div>
				</section>

				<section class="c-section">
					<h2 class="c-section__title">固定ページ</h2>
					<div class="c-section__content">
						<?php
						$pages_query = new WP_Query(
							array(
								'post_type'      => 'page',
								'posts_per_page' => -1,
								'orderby'        => 'menu_order',
								'order'          => 'ASC',
								'post__not_in'   => array( get_option( 'page_on_front' ) ),
							)
						);
						if ( $pages_query->have_posts() ) {
							while ( $pages_query->have_posts() ) {
								$pages_query->the_post();
								get_template_part( 'template/post-item' );
							}
						} else {
							get_template_part( 'template/no-posts' );
						}
						wp_reset_postdata();
						?>
					</div>
				</section>

				<section class="c-section">
					<h2 class="c-section__title">カテゴリー</h2>
					<ul class="c-list">
						<?php
						$categories = get_terms( 'category' );
						if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
							foreach ( $categories as $category ) {
								$term_link = get_term_link( $category->slug, 'category' );
								echo '<li>';
								echo '<a class="c-text-link" href="' . esc_url( $term_link ) . '">' . esc_html( $category->name ) . '</a>';
								echo ' <span class="c-list__count">(' . esc_html( $category->count ) . ')</span>';
								echo '</li>';
							}
						}
						?>
					</ul>
				</section>

				<section class="c-section">
					<h2 class="c-section__title">タグ</h2>
					<ul class="c-list">
						<?php
						$tags = get_terms(
							array(
								'taxonomy'   => 'post_tag',
								'hide_empty' => true,
								'number'     => 10,
							)
						);
						if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
							foreach ( $tags as $tag_item ) {
								$tag_link = get_term_link( $tag_item->slug, 'post_tag' );
								echo '<li>';
								echo '<a class="c-tag" href="' . esc_url( $tag_link ) . '">' . esc_html( $tag_item->name ) . '</a>';
								echo '</li>';
							}
						} else {
							echo '<li><span class="c-list__empty">タグはまだありません</span></li>';
						}
						?>
					</ul>
				</section>

				<section class="c-section">
					<h2 class="c-section__title">アーカイブ</h2>
					<ul class="c-list">
						<?php
						$archives = wp_get_archives(
							array(
								'type'            => 'monthly',
								'limit'           => 6,
								'format'          => 'custom',
								'before'          => '<li>',
								'after'           => '</li>',
								'echo'            => false,
								'show_post_count' => true,
							)
						);
						if ( ! empty( $archives ) ) {
							echo $archives; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							echo '<li><span class="c-list__empty">アーカイブはまだありません</span></li>';
						}
						?>
					</ul>
				</section>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
