<?php
/**
 * Pagination Helper Functions
 *
 * ページネーション関連のヘルパー関数を提供します。
 *
 * @since 1.0.0
 */

/**
 * ページネーション出力関数
 *
 * ループ中で自動でページネーションを出力します.
 *
 * @link https://wemo.tech/978 参考.
 *
 * @param int  $pages 全ページ数.
 * @param int  $current_page 現在のページ.
 * @param int  $range 左右に何ページ表示するか.
 * @param bool $show_only 1ページしかない時に表示するかどうか.
 * @return void
 * @since 1.0.0
 */
function get_pagination( int $pages, int $current_page, int $range = 4, bool $show_only = false ): void {
	// float 型で渡ってくるので明示的に int 型へ.
	$current_page = $current_page ? $current_page : 1;

	if ( $show_only && 1 === $pages ) {
		// 1 ページのみで表示設定が true の時.
		return;
	}

	if ( 1 === $pages ) {
		// 1 ページのみで表示設定もない場合.
		return;
	}

	if ( $pages > 1 ) {
		$html = '<div class="c-pager">';

		$prev_class = $current_page > 1 ? '' : 'is-disabled';
		$next_class = $current_page < $pages ? '' : 'is-disabled';

		$arrow        = '<svg width="5.97" height="10" viewBox="0 0 5.97 10"><path d="M5.98 4.99h-.01l.01.01-.75.72v-.01l-4.48 4.3-.74-.72L4.49 5 .01.7l.75-.71 4.47 4.29.01-.01z" fill="currentcolor" fill-rule="evenodd"></path></svg>';
		$arrow_double = '<svg width="9.97" height="10" viewBox="0 0 9.97 10"><path d="M9.98 4.99h-.01l.01.01-.73.72-.01-.01-4.37 4.3-.73-.72L8.52 5 4.14.7l.73-.71 4.38 4.29.01-.01zm-4.85-.72l.73.72h-.01V5l-.73.72v-.01l-4.38 4.3-.73-.72L4.39 5 .01.7l.73-.71 4.38 4.29z" fill="currentcolor" fill-rule="evenodd"></path></svg>';

		// 「最初へ」 の表示.
		$html .= '<a href="' . esc_url( get_pagenum_link( 1 ) ) . '" class="arrow first double ' . esc_attr( $prev_class ) . '" title="一番最初へ">' . $arrow_double . '</a>';
		// 「前へ」 の表示.
		$html .= '<a href="' . esc_url( get_pagenum_link( $current_page - 1 ) ) . '" class="arrow prev single ' . esc_attr( $prev_class ) . '" title="前へ">' . $arrow . '</a>';

		for ( $i = 1; $i <= $pages; $i++ ) {
			if ( $i <= $current_page + $range && $i >= $current_page - $range ) {
				// $current_page +- $range 以内であればページ番号を出力.
				if ( $current_page === $i ) {
					$html .= '<span class="is-current text">' . esc_html( $i ) . '</span>';
				} else {
					$html .= '<a href="' . esc_url( get_pagenum_link( $i ) ) . '" class="text" title="' . esc_attr( $i ) . 'ページへ">' . esc_html( $i ) . '</a>';
				}
			} elseif ( $i === $pages - 1 ) {
				$html .= '<span class="dotted">…</span>';
			} elseif ( $i === $pages ) {
				$html .= '<a href="' . esc_url( get_pagenum_link( $i ) ) . '" class="text">' . esc_html( $i ) . '</a>';
			}
		}

		// 「次へ」 の表示.
		$html .= '<a href="' . esc_url( get_pagenum_link( $current_page + 1 ) ) . '" class="arrow next single ' . esc_attr( $next_class ) . '" title="次へ">' . $arrow . '</a>';
		// 「最後へ」 の表示.
		$html .= '<a href="' . esc_url( get_pagenum_link( $pages ) ) . '" class="arrow last double ' . esc_attr( $next_class ) . '" title="一番最後へ">' . $arrow_double . '</a>';

		$html .= '</div>';

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML は上で構築済み.
		echo $html;
	}
}
