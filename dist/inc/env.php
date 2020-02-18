<?php
/**
 * Env.php
 *
 * Env指定を行います。
 *
 * @since 0.0.1
 * @package piiiQcy
 */

?>

<?php

define( 'DOMAIN_PRODUCTION', 'example.com' ); // 本番環境のドメイン.
define( 'DOMAIN_DEV', 'localhost' ); // 開発環境のドメイン.

$domain_name = $server['SERVER_NAME'];

// ドメインから開発環境と本番環境の設定を振り分け.
if ( preg_match( '/(www.){0,1}' . DOMAIN_PRODUCTION . '{1}+$/', $domain_name ) ) {
	define( 'APPLICATION_ENV', 'production' );
} else {
	define( 'APPLICATION_ENV', 'development' );
}


