<?php
/**
 * Template Name: sample2
 *
 * 固定ページ「sample2」です
 *
 * @since 0.0.1
 * @package piiiQcy
 */

$page_name = 'sample2';

require_once dirname( __FILE__ ) . '/../inc/common.php';
get_header();

// パンくず.
$page_relation_list = [
	'sample2' => $page_name,
];
?>

<!-- main -->
<main class="l-page" data-barba="wrapper">
	<div class="l-container" data-barba="container" data-barba-namespace="<?php echo esc_html($page_name); ?>">

		<?php echo c_gen_breadcrumbs( $page_relation_list ); ?>

		<!-- contents -->
		sample2

	</div>
</main>
<?php get_footer(); ?>
