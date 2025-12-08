<?php
/**
 * Text Helper Functions
 *
 * テキスト処理関連のヘルパー関数を提供します。
 *
 * @since 1.0.0
 */

/**
 * テキストを指定した長さに切り詰めて表示します
 *
 * @param string $txt テキスト.
 * @param int    $length 最大文字数.
 * @return void
 * @since 1.0.0
 */
function show_txt( string $txt, int $length ): void {
	if ( mb_strlen( $txt, 'utf-8' ) > $length ) {
		$_txt = mb_substr( $txt, 0, $length, 'utf-8' ) . '…';
	} else {
		$_txt = $txt;
	}

	echo esc_html( $_txt );
}

/**
 * テキストを指定した長さに切り詰めて取得します
 *
 * @param string $txt テキスト.
 * @param int    $length 最大文字数.
 * @return string 切り詰められたテキスト.
 * @since 1.0.0
 */
function get_txt( string $txt, int $length ): string {
	if ( mb_strlen( $txt, 'utf-8' ) > $length ) {
		return mb_substr( $txt, 0, $length, 'utf-8' ) . '…';
	}

	return $txt;
}
