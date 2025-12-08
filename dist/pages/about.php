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

		<!-- aboutページ固有: ヒーローセクション -->
		<div class="about-hero">
			<h1 class="about-hero__title">About</h1>
			<p class="about-hero__description">piiiQcy は WordPress テーマ開発のためのモダンなボイラープレートです。Vite、TypeScript、SCSS を使用して効率的な開発環境を提供します。</p>
		</div>

		<!-- aboutページ固有: コンテンツセクション -->
		<div class="about-content">
			<section class="about-content__section">
				<h2 class="about-content__heading">主な機能</h2>
				<p class="about-content__text">
					高速なHMR（Hot Module Replacement）、TypeScript による型安全な開発、SCSS による効率的なスタイリング、自動画像最適化などの機能を提供します。
				</p>
			</section>

			<section class="about-content__section">
				<h2 class="about-content__heading">技術スタック</h2>
				<p class="about-content__text">
					Vite 7、TypeScript、SCSS（モダンコンパイラ）、PHP（WordPress Coding Standards）を採用しています。
				</p>
			</section>

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="c-button">トップページへ戻る</a>
		</div>
	</div>
</div>

<?php get_template_part( 'template-parts/footer' ); ?>
