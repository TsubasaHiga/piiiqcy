<?php
/**
 * Custom.php
 *
 * ループ処理や関数などを指定します
 *
 * @since 0.0.1
 * @package piiiQcy
 */

/**
 * Post_typeと件数指定のクエリarguments
 * Post_typeと件数を指定すると$argsを返します。先頭固定表示指定のある記事は省かれます
 *
 * @param string $post_type 取得したいpost_typeを指定します.
 * @param int    $posts_per_page 取得する件数を指定します.
 * @param number $_paged 取得する投稿のページ送り.
 * @param number $year 取得する投稿年月日で絞ります.
 * @param string $orderby orderby.
 * @param string $order order.
 * @param string $meta_key meta_key.
 * @return array $args
 */
function get_query_args(
	$post_type,
	$posts_per_page,
	$_paged = 1,
	$year = false,
	$orderby = 'data',
	$order = 'DESC',
	$meta_key = false
	) {
	$args = array(
		'post_type'      => $post_type,
		'posts_per_page' => $posts_per_page,
		'orderby'        => $orderby,
		'order'          => $order,
		'paged'          => $_paged,
		'post_status'    => 'publish',
		'post__not_in'   => get_option( 'sticky_posts' ),
	);

	if ( $year && is_numeric( $year ) ) {
		$args['year'] = $year;
	}
	if ( $meta_key ) {
		$args['meta_key'] = $meta_key;
	}
	return $args;
}


/**
 * アーカイブページで$term取得
 * アーカイブページでも$termを取得することが出来ます
 *
 * @link https://goo.gl/De3qpF
 *
 * @return object $args
 */
function get_current_term() {

	$id       = null;
	$tax_slug = null;

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


/**
 * ページネーション出力関数.
 * ループ中で自動でページネーションを出力します.
 *
 * @link https://wemo.tech/978 参考
 *
 * @param int  $pages 全ページ数.
 * @param int  $current_page 現在のページ.
 * @param int  $range 左右に何ページ表示するか.
 * @param bool $show_only 1ページしかない時に表示するかどうか.
 */
function get_pagination( $pages, $current_page, $range = 4, $show_only = false ) {

	// float型で渡ってくるので明示的に int型 へ.
	$pages        = (int) $pages;
	$current_page = $current_page ? $current_page : 1;

	if ( $show_only && 1 === $pages ) {
		// １ページのみで表示設定が true の時.
		return;
	}

	if ( 1 === $pages ) {
		return;    // １ページのみで表示設定もない場合.
	}

	if ( 1 !== $pages ) {
		$html = '<div class="c-pager">';

		$prev_class = $current_page > 1 ? '' : 'is-disabled';
		$next_class = $current_page < $pages ? '' : 'is-disabled';

		$arrow        = '<svg xmlns="http://www.w3.org/2000/svg" width="5.97" height="10" viewBox="0 0 5.97 10"><path d="M5.98 4.99h-.01l.01.01-.75.72v-.01l-4.48 4.3-.74-.72L4.49 5 .01.7l.75-.71 4.47 4.29.01-.01z" fill="#b2b5b8" fill-rule="evenodd"></path></svg>';
		$arrow_double = '<svg xmlns="http://www.w3.org/2000/svg" width="9.97" height="10" viewBox="0 0 9.97 10"><path d="M9.98 4.99h-.01l.01.01-.73.72-.01-.01-4.37 4.3-.73-.72L8.52 5 4.14.7l.73-.71 4.38 4.29.01-.01zm-4.85-.72l.73.72h-.01V5l-.73.72v-.01l-4.38 4.3-.73-.72L4.39 5 .01.7l.73-.71 4.38 4.29z" fill="#b2b5b8" fill-rule="evenodd"></path></svg>';

		// 「最初へ」 の表示
		$html .= '<a href="' . get_pagenum_link( 1 ) . '" class="arrow first double ' . esc_html( $prev_class ) . '" title="一番最初へ">' . $arrow_double . '</a>';
		// 「前へ」 の表示
		$html .= '<a href="' . get_pagenum_link( $current_page - 1 ) . '" class="arrow prev single ' . esc_html( $prev_class ) . '" title="前へ">' . $arrow . '</a>';

		for ( $i = 1; $i <= $pages; $i++ ) {
			if ( $i <= $current_page + $range && $i >= $current_page - $range ) {
				// $current_page +- $range 以内であればページ番号を出力
				if ( $current_page === $i ) {
					$html .= '<span class="is-current text">' . esc_html( $i ) . '</span>';
				} else {
					$html .= '<a href="' . get_pagenum_link( $i ) . '" class="text">' . esc_html( $i ) . '</a>';
				}
			} elseif ( $i === $pages - 1 ) {
				$html .= '<span class="dotted">…</span>';
			} elseif ( $i === $pages ) {
				$html .= '<a href="' . get_pagenum_link( $i ) . '" class="text">' . esc_html( $i ) . '</a>';
			}
		}

		// 「次へ」 の表示
		$html .= '<a href="' . get_pagenum_link( $current_page + 1 ) . '" class="arrow next single ' . esc_html( $next_class ) . '" title="次へ">' . $arrow . '</a>';
		// 「最後へ」 の表示
		$html .= '<a href="' . get_pagenum_link( $pages ) . '" class="arrow last double ' . esc_html( $next_class ) . '" title="一番最後へ">' . $arrow_double . '</a>';

		$html .= '</div>';

		// @codingStandardsIgnoreStart
		echo $html;
		// @codingStandardsIgnoreEnd
	}
}


/**
 * Show txt
 * Esc_htmlを通して文字列を出力します
 *
 * @param string $txt .
 * @param number $length .
 * @return void
 */
function show_txt( $txt, $length ) {
	if ( mb_strlen( $txt, 'utf-8' ) > $length ) {
		$_txt = mb_substr( $txt, 0, $length, 'utf-8' ) . '…';
	} else {
		$_txt = $txt;
	}

	// @codingStandardsIgnoreStart
	echo $_txt;
	// @codingStandardsIgnoreEnd
}


/**
 * Get txt
 *
 * @param string $txt .
 * @param number $length .
 * @return $_txt
 */
function get_txt( $txt, $length ) {
	if ( mb_strlen( $txt, 'utf-8' ) > $length ) {
		$_txt = mb_substr( $txt, 0, $length, 'utf-8' ) . '…';
	} else {
		$_txt = $txt;
	}

	return $_txt;
}


/**
 * 投稿サムネイルを取得します
 *
 * @param number  $id .
 * @param string  $size .
 * @param boolean $is_noimage .
 */
function get_thumb( $id, $size, $is_noimage ) {
	if ( has_post_thumbnail( $id ) ) {
		$image = get_the_post_thumbnail_url( $id, $size );
	} else {
		if ( $is_noimage ) {
			$image = NOIMAGE;
		} else {
			$image = null;
		}
	}
	// @codingStandardsIgnoreStart
	return $image;
	// @codingStandardsIgnoreEnd
}

