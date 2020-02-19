<?php
/**
 * Custom.php
 *
 * ループ処理や関数などを指定します
 *
 * @since 0.0.1
 * @package piiiQcy
 */

?>

<?php
/** -----------------------------------------------------------
 * Summary     | Post_typeと件数指定のクエリarguments
 * Description | Post_typeと件数を指定すると$argsを返します。先頭固定表示指定のある記事は省かれます
 *
 * @param string $post_type 取得したいpost_typeを指定します.
 * @param int    $posts_per_page 取得する件数を指定します.
 * @return array $args
 * -----------------------------------------------------------*/
function c_get_args( $post_type, $posts_per_page ) {
	$args = array(
		'post_type'      => $post_type,
		'posts_per_page' => $posts_per_page,
		'orderby'        => 'deta',
		'order'          => 'DESC',
		'post_status'    => 'publish',
		'post__not_in'   => get_option( 'sticky_posts' ),
	);
	return $args;
}



/** -----------------------------------------------------------
 * Summary     | タクソノミー名からタームの配列を出力
 * Description | 取得したいタクソノミーを指定すると$termsを取得出来ます
 *
 * @param string $taxonomies 取得したいタクソノミーを指定します.
 * @param int    $number 取得する件数を指定します.
 * @return array $args
 * -----------------------------------------------------------*/
function c_get_terms( $taxonomies, $number ) {
	$args  = array(
		'orderby' => 'count',
		'order'   => 'DESC',
		'number'  => $number,
	);
	$terms = get_terms( $taxonomies, $args );
	return $terms;
}



/** -----------------------------------------------------------
 * Summary     | アーカイブページで$term取得
 * Description | アーカイブページでも$termを取得することが出来ます
 *
 * @link https://goo.gl/De3qpF
 *
 * @return array $args
 * -----------------------------------------------------------*/
function c_get_current_term() {

	$id;
	$tax_slug;

	if ( is_category() ) {
		$tax_slug = 'category';
		$id       = get_query_var( 'cat' );
	} elseif ( is_tag() ) {
		$tax_slug = 'post_tag';
		$id       = get_query_var( 'tag_id' );
	} elseif ( is_tax() ) {
		$tax_slug  = get_query_var( 'taxonomy' );
		$term_slug = get_query_var( 'term' );
		$term      = get_term_by( 'slug', $term_slug, $tax_slug );
		$id        = $term->term_id;
	}

	return get_term( $id, $tax_slug );
}



/** -----------------------------------------------------------
 * Summary     | ループ中に指定のタームを種類別に出力
 * Description | ループ中で指定されたタームのname、slug、id等を取得することが出来ます
 *
 * @param string $tax 取得したいタームが属するタクソノミーを指定します.
 * @param string $kind |name|slug|id|の内いづれかを指定します.
 * @param bool   $link aリンクを含めるかの指定を行います.
 * @param int    $number 取得する数を指定します。0で全て取得.
 * @param string $html_tag 文字列を囲むhtmlタグを指定します.
 * @return string $html htmlを返します.
 * -----------------------------------------------------------*/
function c_get_term( $tax, $kind, $link, $number, $html_tag ) {

	global $post;

	$i         = 0;
	$html      = '';
	$tax_array = array();
	$terms     = get_the_terms( $post->ID, $tax );

	if ( false !== $terms ) {
		foreach ( $terms as $key => $value ) {
			if ( 0 !== $number && $i >= $number ) {
				break;
			}
			if ( 'id' === $kind ) {
				$tax_array[ $key ] = array(
					'id' => $value->term_id,
				);
			} elseif ( 'slug' === $kind ) {
				$tax_array[ $key ] = array(
					'slug' => $value->slug,
				);
			} elseif ( 'name' === $kind ) {
				$tax_array[ $key ] = array(
					'name' => $value->name,
					'slug' => $value->slug,
				);
			}
			$i ++;
		}
		if ( false === $link ) {
			foreach ( $tax_array as $key => $value ) {
				$html .= '<' . $html_tag . '>';
				$html .= $value[ $kind ];
				$html .= '</' . $html_tag . '>';
			}
		} elseif ( true === $link ) {
			foreach ( $tax_array as $value ) {
				$html .= '<' . $html_tag . '>';
				$html .= '<a href="' . get_term_link( $value['slug'], $tax ) . '">';
				$html .= $value[ $kind ];
				$html .= '</a>';
				$html .= '</' . $html_tag . '>';
			}
		}
	} else {
		$html .= '';
	}
	return $html;
}



/** -----------------------------------------------------------
 * Summary     | ページネーション出力関数.
 * Description | ループ中で自動でページネーションを出力します.
 *
 * @link https://wemo.tech/978 参考
 *
 * @param int  $pages 全ページ数.
 * @param int  $current_page 現在のページ.
 * @param int  $range 左右に何ページ表示するか.
 * @param bool $show_only 1ページしかない時に表示するかどうか.
 */
function pagination( $pages, $current_page, $range = 2, $show_only = false ) {

	// float型で渡ってくるので明示的に int型 へ.
	$pages        = (int) $pages;
	$current_page = $current_page ? $current_page : 1;

	// 表示テキスト.
	$text_first  = '最初へ';
	$text_before = '前へ';
	$text_next   = '次へ';
	$text_last   = '最後へ';

	if ( $show_only && 1 === $pages ) {
		// １ページのみで表示設定が true の時.
		return;
	}

	if ( 1 === $pages ) {
		return;    // １ページのみで表示設定もない場合.
	}

	if ( 1 !== $pages ) {
		if ( $current_page > $range + 1 ) {
			// 「最初へ」 の表示
			echo '<a href="', esc_url( get_pagenum_link( 1 ) ) ,'" class="first">', esc_html( $text_first ) ,'</a>';
		}
		if ( $current_page > 1 ) {
			// 「前へ」 の表示
			echo '<a href="', esc_url( get_pagenum_link( $current_page - 1 ) ) ,'" class="prev">', esc_html( $text_before ) ,'</a>';
		}
		for ( $i = 1; $i <= $pages; $i++ ) {
			if ( $i <= $current_page + $range && $i >= $current_page - $range ) {
				// $current_page +- $range 以内であればページ番号を出力
				if ( $current_page === $i ) {
					echo '<span class="current pager">', esc_html( $i ) ,'</span>';
				} else {
					echo '<a href="', esc_url( get_pagenum_link( $i ) ) ,'" class="pager">', esc_html( $i ) ,'</a>';
				}
			}
		}
		if ( $current_page < $pages ) {
			// 「次へ」 の表示
			echo '<a href="', esc_url( get_pagenum_link( $current_page + 1 ) ) ,'" class="next">', esc_html( $text_next ) ,'</a>';
		}
		if ( $current_page + $range < $pages ) {
			// 「最後へ」 の表示
			echo '<a href="', esc_url( get_pagenum_link( $pages ) ) ,'" class="last">', esc_html( $text_last ) ,'</a>';
		}
		echo '</div>';
	}
}



/** -----------------------------------------------------------
 * Summary     | wppからwp_query用args取得.
 * Description | wppでもwp_queryと同じ感覚で出力できるようidを取得し、wp_queryで用いる形でargsを出力します.
 *
 * @param strings $post_type カスタム投稿を指定。デフォルトnull.
 * @param strings $range |day|weekly|month|から集計期間を選択.
 * @param int     $limit 取得する件数のリミットを指定.
 * @return $wpp_id idを返す.
 * -----------------------------------------------------------*/
function c_get_wpp_args( $post_type = '', $range = 'weekly', $limit = 5 ) {
	// urlを作成.
	$shortcode  = '[wpp';
	$atts       = '
		post_html      = "{url},"
		wpp_start      = ""
		wpp_end        = ""
		order_by       = "views"
		post_type      = "post"
		stats_comments = 0
		stats_views    = 1
	';
	$atts_2     = ' range=' . $range;
	$atts_3     = ' limit=' . $limit;
	$shortcode .= ' ' . $atts . $atts_2 . $atts_3 . ']';
	$result     = explode( ',', wp_strip_all_tags( do_shortcode( $shortcode ) ) );
	$wpp_id     = array();

	// urlから投稿IDを作成.
	foreach ( $result as $_url ) {
		if ( ! empty( $_url ) && trim( $_url ) !== '' ) {
			$id_string = url_to_postid( $_url );
			array_push( $wpp_id, intval( $id_string ) );
		}
	}

	$args = array(
		'post_type'      => $post_type,
		'posts_per_page' => $limit,
		'orderby'        => 'post__in',
		'order'          => 'DESC',
		'post_status'    => 'publish',
		'post__in'       => $wpp_id,
		'post__not_in'   => get_option( 'sticky_posts' ),
	);

	return $args;
}
