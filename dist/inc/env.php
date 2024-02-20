<?php
/**
 * Env.php
 *
 * Env指定を行います。
 *
 * @since 1.0.0
 */

// @codingStandardsIgnoreStart
$domain_name = $_SERVER['SERVER_NAME'];
// @codingStandardsIgnoreEnd

// ドメインから開発環境と本番環境の設定を振り分け.
if ( preg_match( '/(www.){0,1}' . DOMAIN_PRODUCTION . '{1}+$/', $domain_name ) ) {
	define( 'APPLICATION_ENV', 'production' );
} else {
	define( 'APPLICATION_ENV', 'development' );
}
