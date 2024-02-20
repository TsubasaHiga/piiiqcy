<?php
/**
 * Tools.php
 *
 * WP経由しない場合でも用いるfunction類を記述します
 *
 * @since 1.0.0
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
	<div class="c-breadcrumbs__item" property="itemListElement" typeof="ListItem">
		<a property="item" typeof="WebPage" title="{$breadcrumbs_top}" href="{$site_url}">
			<span property="name">{$breadcrumbs_top}</span>
		</a>
		<meta property="position" content="1">
	</div>
EOM;

	$i     = 2;
	$html  = "<div class='c-breadcrumbs' typeof='BreadcrumbList' vocab='https://schema.org/'>";
	$html .= "<div class='c-breadcrumbs__inner l-spacer'>";
	$html .= "<div class='c-breadcrumbs__list'>";

	$html .= $html_first;

	$key_last = array_key_last( $page_relation_list );
	$url      = '';
	foreach ( $page_relation_list as $page_name => $page_url ) {
		$url  .= $page_url . '/';
		$html .= "<div class='c-breadcrumbs__item' property='itemListElement' typeof='ListItem'>";

		if ( $key_last !== $page_name ) {
			$html .= "<a property='item' typeof='WebPage' title='{$page_name}' href='{$site_url}{$url}'>";
			$html .= "<span property='name'>{$page_name}</span>";
			$html .= '</a>';
			$html .= "<meta property='position' content='{$i}'>";
		} else {
			$html .= "<span property='name'>{$page_name}</span>";
			$html .= "<meta property='position' content='{$i}'>";
		}

		$html .= '</div>';
		++$i;
	}

	$html .= '</div>';
	$html .= '</div>';
	$html .= "</div>\n";

	// @codingStandardsIgnoreStart
	echo $html;
	// @codingStandardsIgnoreEnd
}


/**
 * Assets filepath and query string
 *
 * @param string $file_path .
 */
function get_assetspath( $file_path, $get_value = false ) {
	$path      = THEMEROOTPATH . $file_path;
	$path_full = THEMEROOTFULLPATH . $file_path;
	$query     = '?rev=' . gmdate( 'YmdGis', filemtime( $path_full ) );

	if ( $get_value ) {
		return $path . $query;
	}

	echo esc_url( $path . $query );
}


/**
 * Picture tag
 */
function generate_responsive_picture( $settings, $get_value = false ) {
	$id           = isset( $settings['id'] ) ? $settings['id'] : '';
	$class        = isset( $settings['class'] ) ? $settings['class'] : '';
	$small_img    = isset( $settings['small_img'] ) ? $settings['small_img'] : '';
	$large_img    = isset( $settings['large_img'] ) ? $settings['large_img'] : '';
	$large_img_2x = isset( $settings['large_img_2x'] ) ? $settings['large_img_2x'] : '';
	$default_img  = isset( $settings['default_img'] ) ? $settings['default_img'] : '';
	$alt          = isset( $settings['alt'] ) ? $settings['alt'] : '';
	$width        = isset( $settings['width'] ) ? $settings['width'] : '';
	$height       = isset( $settings['height'] ) ? $settings['height'] : '';
	$loading      = isset( $settings['loading'] ) ? $settings['loading'] : 'eager';

	$get_const = fn( $const_value ) => $const_value;

	$attr = '';
	if ( ! empty( $id ) ) {
		$attr .= "id=\"$id\" ";
	}
	if ( ! empty( $class ) ) {
		$attr .= "class=\"$class\"";
	}

	$small_source = ! empty( $small_img )
		? "<source media=\"(max-width: {$get_const(BREAKPOINT - 1)}px)\" srcset=\"$small_img\">"
		: '';

	$large_source = ! empty( $large_img_2x )
		? "<source media=\"(min-width: {$get_const(BREAKPOINT)}px)\" srcset=\"$large_img 1x, $large_img_2x 2x\">"
		: ( ! empty( $large_img ) ? "<source media=\"(min-width: {$get_const(BREAKPOINT)}px)\" srcset=\"$large_img\">" : '' );

	$html = <<<EOM
<picture $attr>
    $small_source
    $large_source
    <img src="$default_img" alt="$alt" width="$width" height="$height" loading="$loading">
</picture>
EOM;

	if ( $get_value ) {
		return $html;
	}

	// @codingStandardsIgnoreStart
	echo $html;
	// @codingStandardsIgnoreEnd
}

/**
 * echo target="_blank" rel="noopener noreferrer"
 */
function get_attr_target_blank( $state, $get_value = false ) {
	$attr = '';

	if ( $state ) {
		$attr = ' target="_blank" rel="noopener noreferrer"';
	}

	if ( $get_value ) {
		return $attr;
	}

	// @codingStandardsIgnoreStart
	echo $attr;
	// @codingStandardsIgnoreEnd
}
