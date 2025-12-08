<?php
/**
 * Category Helper
 *
 * カテゴリー関連の処理を集約したヘルパークラスです。
 * 表示名の変換、階層構造の取得などを提供します。
 *
 * @since 1.0.0
 */

/**
 * カテゴリーヘルパークラス
 *
 * @since 1.0.0
 */
class Category_Helper {

	/**
	 * カテゴリーの表示名を取得します
	 *
	 * news カテゴリーの場合は「お知らせ」、それ以外はカテゴリー名を返します。
	 *
	 * @param WP_Term|WP_Error|false|null $category カテゴリーオブジェクト.
	 * @param WP_Term|false|null          $news_category news カテゴリーオブジェクト（省略時は自動取得）.
	 * @return string カテゴリーの表示名.
	 * @since 1.0.0
	 */
	public static function get_display_name( $category, $news_category = null ): string {
		// カテゴリーが存在しない、または WP_Error の場合は空文字を返す.
		if ( ! $category || is_wp_error( $category ) ) {
			return '';
		}

		if ( null === $news_category ) {
			$news_category = Theme_Cache::get_category( 'news' );
		}

		// news カテゴリーが存在し、かつ一致する場合は「お知らせ」を返す.
		if ( $news_category && $category->term_id === $news_category->term_id ) {
			return 'お知らせ';
		}

		return $category->name;
	}

	/**
	 * アーカイブページでの現在のタームを取得します
	 *
	 * @return WP_Term|null 現在のターム、または null.
	 * @since 1.0.0
	 */
	public static function get_current_term(): ?WP_Term {
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
			if ( $term && ! is_wp_error( $term ) ) {
				$id = $term->term_id;
			}
		}

		if ( null === $id || null === $tax_slug ) {
			return null;
		}

		$term = get_term( $id, $tax_slug );
		return ( $term && ! is_wp_error( $term ) ) ? $term : null;
	}
}
