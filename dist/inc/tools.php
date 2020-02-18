<?php
/**
 * Tools.php
 *
 * 様々な関数を記述します
 *
 * @since 0.0.1
 * @package piiiQcy
 */

?>

<?php
/** ------------------------------------------------------------
 *
 * パンくずリスト生成
 *
 * @param string $page_relation_list 兄弟関係のページが入った連想配列.
 * @return string htmlテキストを返します.
 *
 * -------------------------------------------------------------
 */
function c_gen_breadcrumbs( $page_relation_list ) {
	$site_url        = home_url( '/' );
	$breadcrumbs_top = BREADCRUMBS_TOP;

	$html_first = <<< EOM
	<span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="{$breadcrumbs_top}" href="{$site_url}" class="home"><span property="name">{$breadcrumbs_top}</span></a><meta property="position" content="1"></span>
EOM;

	$i     = 2;
	$html  = "<div class='l-breadcrumbs' typeof='BreadcrumbList' vocab='https://schema.org/'>";
	$html .= "<div class='l-breadcrumbs__inner'>";
	$html .= $html_first;
	foreach ( $page_relation_list as $page_name => $page_url ) {
		$html .= "<span property='itemListElement' typeof='ListItem'>";
		$html .= "<a property='item' typeof='WebPage' title='{$page_name}' href='{$site_url}{$page_url}/'>";
		$html .= "<span property='name'>{$page_name}</span>";
		$html .= "</a>";
		$html .= "<meta property='position' content='{$i}'>";
		$html .= "</span>";
		$i ++;
	}
	$html .= "</div></div>\n";

	return $html;
}
