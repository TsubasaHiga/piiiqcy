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
 * @return array $args
 */
function get_current_term() {

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


/**
 * アーカイブページでslug取得
 *
 * @link https://wemo.tech/1161#index_id5
 * @return string
 */
function get_archive_slug() {
	// アーカイブページでない場合、false を返す.
	if ( ! is_archive() ) {
		return false;
	}

	// 投稿タイプアーカイブページ.
	if ( is_post_type_archive() ) {
		$post_type = get_query_var( 'post_type' );
		if ( is_array( $post_type ) ) {
			$post_type = reset( $post_type );
		}
		return $post_type;
	}

	// それ以外（カテゴリ・タグ・タクソノミーアーカイブページ）.
	$term = get_queried_object();
	return $term->slug;
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

	// 表示テキスト.
	$text_first = '最初へ';
	$text_prev  = 'BACK';
	$text_next  = 'NEXT';
	$text_last  = '最後へ';

	if ( $show_only && 1 === $pages ) {
		// １ページのみで表示設定が true の時.
		return;
	}

	if ( 1 === $pages ) {
		return;    // １ページのみで表示設定もない場合.
	}

	if ( 1 !== $pages ) {
		echo '<div class="c-pager">';

		$prev_class = $current_page > 1 ? '' : 'is-disabled';
		$next_class = $current_page < $pages ? '' : 'is-disabled';

		// 「前へ」 の表示
		echo '<a href="', esc_url( get_pagenum_link( $current_page - 1 ) ) ,'" class="arrow prev ', esc_html( $prev_class ) ,'">', esc_html( $text_prev ) ,'</a>';

		for ( $i = 1; $i <= $pages; $i++ ) {
			if ( $i <= $current_page + $range && $i >= $current_page - $range ) {
				// $current_page +- $range 以内であればページ番号を出力
				if ( $current_page === $i ) {
					echo '<span class="is-current pager">', esc_html( $i ) ,'</span>';
				} else {
					echo '<a href="', esc_url( get_pagenum_link( $i ) ) ,'" class="pager">', esc_html( $i ) ,'</a>';
				}
			} elseif ( $i === $pages - 1 ) {
				echo '<span class="dotted l-lg">…</span>';
			} elseif ( $i === $pages ) {
				echo '<a href="', esc_url( get_pagenum_link( $i ) ) ,'" class="pager">', esc_html( $i ) ,'</a>';
			}
		}

		$current_number = <<< EOM
		<div class="current-number l-sm">
			<p class="current-number__current">{$current_page}</p>
			<p class="current-number__maxnumber">{$pages}</p>
		</div>
EOM;
		// @codingStandardsIgnoreStart
		echo $current_number;
		// @codingStandardsIgnoreEnd

		// 「次へ」 の表示
		echo '<a href="', esc_url( get_pagenum_link( $current_page + 1 ) ) ,'" class="arrow next ', esc_html( $next_class ) ,'">', esc_html( $text_next ) ,'</a>';

		echo '</div>';
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

	echo esc_html( $_txt );
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
			$image = NOIMAGE_2X;
		} else {
			$image = null;
		}
	}
	// @codingStandardsIgnoreStart
	echo $image;
	// @codingStandardsIgnoreEnd
}


/**
 * 画像のサイズを取得
 *
 * @param number $id .
 */
function get_thumb_size( $id ) {
	if ( has_post_thumbnail( $id ) ) {
		$size = wp_get_attachment_metadata( get_post_thumbnail_id( $id ) );
		// @codingStandardsIgnoreStart
		echo 'height="' . $size['height'] . '" width="' . $size['width'] . '"';
		// @codingStandardsIgnoreEnd
	}
}


/**
 * 小文字で返します
 *
 * @param string $txt .
 * @return $txt
 */
function get_lower_txt( $txt ) {
	return mb_strtolower( $txt );
}
