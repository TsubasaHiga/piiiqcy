<?php
/**
 * Tools.php
 *
 * WP経由しない場合でも用いるfunction類を記述します
 *
 * @since 0.0.1
 * @package piiiQcy
 */

/**
 * パンくずリスト生成
 *
 * @param array $page_relation_list 兄弟関係のページが入った連想配列.
 */
function get_breadcrumbs( $page_relation_list ) {
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
		$html .= '</a>';
		$html .= "<meta property='position' content='{$i}'>";
		$html .= '</span>';
		$i ++;
	}
	$html .= "</div></div>\n";

	// @codingStandardsIgnoreStart
	echo $html;
	// @codingStandardsIgnoreEnd
}


/**
 * Assets filepath and query string
 *
 * @param string $file_path .
 */
function get_assetspath( $file_path ) {
	$path      = THEMEROOTPATH . $file_path;
	$path_full = THEMEROOTFULLPATH . $file_path;
	$query     = '?rev=' . gmdate( 'YmdGis', filemtime( $path_full ) );

	echo esc_url( $path . $query );
}
