<?php
/**
 * URL Helper Functions
 *
 * URL 処理関連のヘルパー関数を提供します。
 *
 * @since 1.0.0
 */

/**
 * 現在のページ URL を取得します
 *
 * @param bool $has_home_url ホーム URL を含めるか.
 * @return string 現在のページ URL.
 * @since 1.0.0
 */
function get_current_url( bool $has_home_url = false ): string {
	$request = add_query_arg( array(), $GLOBALS['wp']->request );

	if ( $has_home_url ) {
		return home_url( $request );
	}

	return $request;
}
