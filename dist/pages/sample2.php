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
$page_relation_list = array(
	'sample2' => $page_name,
);
?>

<!-- main -->
<main class="l-page">
	<div class="l-container">

		<?php echo c_gen_breadcrumbs( $page_relation_list ); ?>

		<!-- contents -->
		sample2

	</div>
</main>
<?php get_footer(); ?>
