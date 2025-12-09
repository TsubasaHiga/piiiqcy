<?php
/**
 * Image Helper Functions
 *
 * 画像処理関連のヘルパー関数を提供します。
 *
 * @since 1.0.0
 */

/**
 * 画像が存在しない場合は NOIMAGE を返します
 *
 * @param string|null $url 画像 URL.
 * @param bool        $is_noimage URL が空の場合に NOIMAGE を返すか.
 * @return string|null 画像 URL または null.
 * @since 1.0.0
 */
function get_image( ?string $url, bool $is_noimage ): ?string {
	if ( $url ) {
		return $url;
	}

	if ( $is_noimage ) {
		return NOIMAGE;
	}

	return null;
}

/**
 * 投稿サムネイルを取得します
 *
 * @param int    $id 投稿 ID.
 * @param string $size サイズ.
 * @param bool   $is_noimage サムネイルがない場合に NOIMAGE を返すか.
 * @return string|null 画像 URL または null.
 * @since 1.0.0
 */
function get_thumb( int $id, string $size, bool $is_noimage ): ?string {
	if ( has_post_thumbnail( $id ) ) {
		return get_the_post_thumbnail_url( $id, $size );
	}

	if ( $is_noimage ) {
		return NOIMAGE;
	}

	return null;
}
