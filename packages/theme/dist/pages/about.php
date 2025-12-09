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
<?php get_header(); ?>

<div class="l-container l-spacer">
	<div class="l-page">
		<?php get_breadcrumbs( $page_relation_list ); ?>

		<section class="about-hero">
			<h1 class="about-hero__title">About</h1>
			<p class="about-hero__description">このページは固定ページテンプレートのサンプルです。</p>
		</section>

		<section class="c-section">
			<h2 class="c-section__title">レスポンシブ画像</h2>
			<p class="about-text">generate_responsive_picture() 関数を使用したレスポンシブ画像の表示例です。</p>
			<div class="about-image">
				<?php
				$image_src_sm = get_assetspath( '/assets/images/img__sm.webp', true );
				$image_src_lg = get_assetspath( '/assets/images/img__lg.webp', true );
				generate_responsive_picture(
					array(
						'class'       => 'about-image__picture',
						'small_img'   => $image_src_sm,
						'large_img'   => $image_src_lg,
						'default_img' => $image_src_lg,
						'alt'         => 'Sample Image',
						'width'       => '300',
						'height'      => '250',
						'loading'     => 'lazy',
					)
				);
				?>
			</div>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="c-section__more c-button c-button--secondary">トップページへ戻る</a>
		</section>
	</div>
</div>

<?php get_footer(); ?>
