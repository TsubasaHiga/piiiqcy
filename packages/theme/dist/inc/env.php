<?php
/**
 * Configures environment-specific settings.
 *
 * This file sets up the necessary parameters and settings to ensure the application operates
 * correctly in its designated runtime environment.
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
