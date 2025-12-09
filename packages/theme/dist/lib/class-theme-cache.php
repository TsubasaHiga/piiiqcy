<?php
/**
 * Theme Cache Manager
 *
 * 同一リクエスト内でのデータキャッシュを一元管理します。
 * カテゴリー、ターム、投稿データなどの重複取得を防ぎ、パフォーマンスを最適化します。
 *
 * @since 1.0.0
 */

/**
 * テーマ用キャッシュマネージャークラス
 *
 * @since 1.0.0
 */
class Theme_Cache {

	/**
	 * カテゴリーキャッシュ（スラッグをキーとする）
	 *
	 * @var array<string, WP_Term|false>
	 */
	private static array $categories = array();

	/**
	 * タームの子要素キャッシュ（ターム ID をキーとする）
	 *
	 * @var array<int, array<int>>
	 */
	private static array $term_children = array();

	/**
	 * スラッグでカテゴリーを取得します（キャッシュ付き）
	 *
	 * @param string $slug カテゴリースラッグ.
	 * @return WP_Term|false カテゴリーオブジェクト、存在しない場合は false.
	 * @since 1.0.0
	 */
	public static function get_category( string $slug ) {
		if ( ! isset( self::$categories[ $slug ] ) ) {
			$category = get_category_by_slug( $slug );
			if ( ! $category || is_wp_error( $category ) ) {
				self::$categories[ $slug ] = false;
			} else {
				self::$categories[ $slug ] = $category;
			}
		}

		return self::$categories[ $slug ];
	}

	/**
	 * タームの子要素 ID を取得します（キャッシュ付き）
	 *
	 * @param int    $term_id ターム ID.
	 * @param string $taxonomy タクソノミー名（デフォルト: category）.
	 * @return array<int> 子ターム ID の配列.
	 * @since 1.0.0
	 */
	public static function get_term_children( int $term_id, string $taxonomy = 'category' ): array {
		$cache_key = $term_id . '_' . $taxonomy;

		if ( ! isset( self::$term_children[ $cache_key ] ) ) {
			$children = get_term_children( $term_id, $taxonomy );
			if ( is_wp_error( $children ) ) {
				self::$term_children[ $cache_key ] = array();
			} else {
				self::$term_children[ $cache_key ] = $children;
			}
		}

		return self::$term_children[ $cache_key ];
	}

	/**
	 * カテゴリーとその全子孫の ID を取得します
	 *
	 * @param int $category_id カテゴリー ID.
	 * @return array<int> カテゴリー ID の配列（自身を含む）.
	 * @since 1.0.0
	 */
	public static function get_category_with_descendants( int $category_id ): array {
		$ids       = array( $category_id );
		$child_ids = self::get_term_children( $category_id, 'category' );

		if ( ! empty( $child_ids ) ) {
			$ids = array_merge( $ids, $child_ids );
		}

		return $ids;
	}
}
