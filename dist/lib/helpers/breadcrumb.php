<?php
/**
 * Breadcrumb Helper Functions
 *
 * パンくずリスト関連のヘルパー関数を提供します。
 *
 * @since 1.0.0
 */

/**
 * パンくず用にすべての親ページのタイトルと post_name を取得して配列にした変数を返します
 *
 * @return array<string, string> タイトルをキー、post_name を値とする連想配列.
 * @since 1.0.0
 */
function get_page_relation_list(): array {
	$post_data = get_post();

	// 自身のタイトルと post_name を取得.
	$page_relation_list = array(
		get_the_title() => $post_data ? get_post()->post_name : '',
	);

	// すべての親ページの ID を取得.
	$parent_page_ids = get_post_ancestors( get_the_ID() );

	// すべての親ページのタイトルと post_name を追加していく.
	foreach ( $parent_page_ids as $parent_page_id ) {
		$parent_post        = get_post( $parent_page_id );
		$page_relation_list = array_merge(
			array(
				$parent_post->post_title => $parent_post->post_name,
			),
			$page_relation_list
		);
	}

	return $page_relation_list;
}
