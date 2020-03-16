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
function c_get_args(
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
 * タクソノミー名からタームの配列を出力
 * 取得したいタクソノミーを指定すると$termsを取得出来ます
 *
 * @param string $taxonomies 取得したいタクソノミーを指定します.
 * @param int    $number 取得する件数を指定します.
 * @return array $args
 */
function c_get_terms( $taxonomies, $number ) {
	$args  = array(
		'orderby' => 'count',
		'order'   => 'DESC',
		'number'  => $number,
	);
	$terms = get_terms( $taxonomies, $args );
	return $terms;
}



/**
 * アーカイブページで$term取得
 * アーカイブページでも$termを取得することが出来ます
 *
 * @link https://goo.gl/De3qpF
 *
 * @return array $args
 */
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


/**
 * アーカイブページでslug取得
 *
 * @link https://wemo.tech/1161#index_id5
 * @return string
 */
function c_get_archive_slug() {
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
 * ループ中に指定のタームを種類別に出力
 * ループ中で指定されたタームのname、slug、id等を取得することが出来ます
 *
 * @param string $tax 取得したいタームが属するタクソノミーを指定します.
 * @param string $kind |name|slug|id|の内いづれかを指定します.
 * @param bool   $link aリンクを含めるかの指定を行います.
 * @param int    $number 取得する数を指定します。0で全て取得.
 * @param string $html_tag 文字列を囲むhtmlタグを指定します.
 */
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
	// @codingStandardsIgnoreStart
	echo $html;
	// @codingStandardsIgnoreEnd
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
function pagination( $pages, $current_page, $range = 4, $show_only = false ) {

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
 * 画像が無い時にnoimageを出す
 *
 * @param number  $id .
 * @param string  $size .
 * @param boolean $is_noimage .
 */
function show_thumb( $id, $size, $is_noimage ) {
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
 * 曜日取得
 *
 * @param string $date .
 * @param string $language .
 * @return day_name 英字の場合は小文字で返します
 */
function get_dayname( $date, $language = 'en' ) {
	$dates       = DateTime::createFromFormat( 'Ymd', str_replace( '.', '', $date ) );
	$day_name_ja = array( '日', '月', '火', '水', '木', '金', '土' );
	if ( 'en' === $language ) {
		$day_name = mb_strtolower( $dates->format( 'D' ) );
	} elseif ( 'ja' === $language ) {
		$day_name = $day_name_ja[ $dates->format( 'w' ) ];
	}
	return $day_name;
}


/**
 * 日付を特定の書式にフォーマット
 *
 * @param string $date .
 * @param string $format .
 * @return $date
 */
function get_dayformat( $date, $format ) {
	$dates = DateTime::createFromFormat( 'Ymd', str_replace( '.', '', $date ) );
	$date  = $dates->format( $format );
	return $date;
}
