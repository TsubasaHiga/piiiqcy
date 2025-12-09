<?php
/**
 * Query Optimizer
 *
 * WP_Query 用のクエリ引数を効率的に生成するクラスです。
 *
 * @since 1.0.0
 */

/**
 * クエリ最適化クラス
 *
 * @since 1.0.0
 */
class Query_Optimizer {

	/**
	 * WP_Query 用の基本引数を生成します
	 *
	 * @param string      $post_type 投稿タイプ.
	 * @param int         $posts_per_page 取得件数.
	 * @param int         $paged ページ番号.
	 * @param string      $orderby 並び順のキー.
	 * @param string      $order 並び順（DESC または ASC）.
	 * @param bool        $exclude_sticky 先頭固定投稿を除外するか.
	 * @param int|null    $year 年で絞り込む場合.
	 * @param string|null $meta_key メタキーで絞り込む場合.
	 * @return array<string, mixed> WP_Query 用の引数配列.
	 * @since 1.0.0
	 */
	public static function build_query_args(
		string $post_type,
		int $posts_per_page,
		int $paged = 1,
		string $orderby = 'date',
		string $order = 'DESC',
		bool $exclude_sticky = true,
		?int $year = null,
		?string $meta_key = null
	): array {
		$args = array(
			'post_type'      => $post_type,
			'posts_per_page' => $posts_per_page,
			'orderby'        => $orderby,
			'order'          => $order,
			'paged'          => $paged,
			'post_status'    => 'publish',
		);

		if ( $exclude_sticky ) {
			$args['post__not_in'] = get_option( 'sticky_posts' );
		}

		if ( $year ) {
			$args['year'] = $year;
		}

		if ( $meta_key ) {
			$args['meta_key'] = $meta_key;
		}

		return $args;
	}
}
