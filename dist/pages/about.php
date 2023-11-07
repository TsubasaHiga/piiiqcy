<?php
/**
 * Template Name: about
 *
 * 固定ページ「about」です
 *
 * @since 0.0.1
 * @package piiiQcy
 */

$page_name = 'about';

require_once dirname( __FILE__ ) . '/../inc/common.php';

// パンくず.
$page_relation_list = array(
	'about' => $page_name,
);
?>

<div class="l-container l-spacer">
	<div class="l-page">
		<?php get_breadcrumbs( $page_relation_list ); ?>
		<section class="bg-gray-200 rounded-lg p-10 my-10">

			<!-- contents -->
			about
		</section>
	</div>
</div>
<?php get_footer(); ?>
