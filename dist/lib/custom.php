<?php
/**
 * Contains custom functions and utilities designed specifically for WordPress usage.
 *
 * This file provides WordPress-specific features, encapsulating various enhancements and
 * custom behaviors to extend the core functionality of the platform.
 *
 * @since 1.0.0
 */

/**
 * Post_typeと件数指定のクエリarguments
 * Post_typeと件数を指定すると$argsを返します。先頭固定表示指定のある記事は省かれます
 *
 * @param string $post_type 取得したいpost_typeを指定します.
 * @param int    $posts_per_page 取得する件数を指定します.
 * @param number $_paged 取得する投稿のページ送り.
 * @param number|false $year 取得する投稿年月日で絞ります.
 * @param string $orderby orderby.
 * @param string $order order.
 * @param string|false $meta_key meta_key.
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

	if ( $year ) {
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
 * 特定のタクソノミー一覧を取得
 */
function get_taxsonomy_list( $name ) {
	$terms = get_terms(
		$name,
	);
	return $terms;
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

	if ( $pages > 1 ) {
		$html = '<div class="c-pager">';

		$prev_class = $current_page > 1 ? '' : 'is-disabled';
		$next_class = $current_page < $pages ? '' : 'is-disabled';

		$arrow        = '<svg width="5.97" height="10" viewBox="0 0 5.97 10"><path d="M5.98 4.99h-.01l.01.01-.75.72v-.01l-4.48 4.3-.74-.72L4.49 5 .01.7l.75-.71 4.47 4.29.01-.01z" fill="currentcolor" fill-rule="evenodd"></path></svg>';
		$arrow_double = '<svg width="9.97" height="10" viewBox="0 0 9.97 10"><path d="M9.98 4.99h-.01l.01.01-.73.72-.01-.01-4.37 4.3-.73-.72L8.52 5 4.14.7l.73-.71 4.38 4.29.01-.01zm-4.85-.72l.73.72h-.01V5l-.73.72v-.01l-4.38 4.3-.73-.72L4.39 5 .01.7l.73-.71 4.38 4.29z" fill="currentcolor" fill-rule="evenodd"></path></svg>';

		// 「最初へ」 の表示
		$html .= '<a href="' . get_pagenum_link( 1 ) . '" class="arrow first double ' . esc_attr( $prev_class ) . '" title="一番最初へ">' . $arrow_double . '</a>';
		// 「前へ」 の表示
		$html .= '<a href="' . get_pagenum_link( $current_page - 1 ) . '" class="arrow prev single ' . esc_attr( $prev_class ) . '" title="前へ">' . $arrow . '</a>';

		for ( $i = 1; $i <= $pages; $i++ ) {
			if ( $i <= $current_page + $range && $i >= $current_page - $range ) {
				// $current_page +- $range 以内であればページ番号を出力
				if ( $current_page === $i ) {
					$html .= '<span class="is-current text">' . esc_html( $i ) . '</span>';
				} else {
					$html .= '<a href="' . get_pagenum_link( $i ) . '" class="text" title="' . esc_attr( $i ) . 'ページへ">' . esc_html( $i ) . '</a>';
				}
			} elseif ( $i === $pages - 1 ) {
				$html .= '<span class="dotted">…</span>';
			} elseif ( $i === $pages ) {
				$html .= '<a href="' . get_pagenum_link( $i ) . '" class="text">' . esc_html( $i ) . '</a>';
			}
		}

		// 「次へ」 の表示
		$html .= '<a href="' . get_pagenum_link( $current_page + 1 ) . '" class="arrow next single ' . esc_attr( $next_class ) . '" title="次へ">' . $arrow . '</a>';
		// 「最後へ」 の表示
		$html .= '<a href="' . get_pagenum_link( $pages ) . '" class="arrow last double ' . esc_attr( $next_class ) . '" title="一番最後へ">' . $arrow_double . '</a>';

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
	echo esc_html($_txt);
	// @codingStandardsIgnoreEnd
}

/**
 * Get txt
 *
 * @param string $txt .
 * @param number $length .
 * @return string $_txt
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
	} elseif ( $is_noimage ) {
			$image = NOIMAGE;
	} else {
		$image = null;
	}
	// @codingStandardsIgnoreStart
	return $image;
	// @codingStandardsIgnoreEnd
}

/**
 * パンくず用にすべての親ページのタイトルとpage_nameを取得して配列にした変数を返します
 */
function get_page_relation_list() {
	$post_data = get_post();

	// 自身のタイトルとpage_nameを取得
	$page_relation_list = array(
		get_the_title() => $post_data ? get_post()->post_name : '',
	);

	// すべての親ページのIDを取得.
	$parent_page_ids = get_post_ancestors( get_the_ID() );

	// すべての親ページのタイトルとpage_nameを追加していく.
	foreach ( $parent_page_ids as $parent_page_id ) {
		$page_relation_list = array_merge(
			array(
				get_post( $parent_page_id )->post_title => get_post( $parent_page_id )->post_name,
			),
			$page_relation_list
		);
	}

	return $page_relation_list;
}

/**
 * 特定のmeta_keyを含んでいるかどうかを判定します
 */
function has_meta_key( $post_id, $meta_key ) {
	$meta = get_post_meta( $post_id, $meta_key, true );
	if ( $meta ) {
		return true;
	} else {
		return false;
	}
}
