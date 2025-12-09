<?php
/**
 * Constant Definitions
 *
 * This file contains global constant declarations used throughout the application.
 * Modify and extend these definitions as needed to suit the application's configuration and requirements.
 *
 * @since 1.0.0
 */

// 本番環境のドメイン.
define( 'DOMAIN_PRODUCTION', 'example.com' );

// 開発環境のドメイン.
define( 'DOMAIN_DEV', 'localhost' );

// breadcrumbs.
define( 'BREADCRUMBS_TOP', 'TOP' );

// BLANKIMAGE.
define( 'BLANKIMAGE', 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' );

// NOIMAGE.
define( 'NOIMAGE', get_template_directory_uri() . '/assets/images/common/img__noimage.webp' );
define( 'NOIMAGE_2X', get_template_directory_uri() . '/assets/images/common/img__noimage@2x.webp' );

// OGPIMAGE.
define( 'OGPIMAGE', get_template_directory_uri() . '/assets/ogpimg.jpg' );

// アーカイブページの表示件数を指定.
define( 'POST_PER_PAGE', 10 );

// デフォルトのexcerpt_length.
define( 'EXCERPT_LENGTH', 50 );

// EVENTのexcerpt_length.
define( 'EXCERPT_LENGTH_EVENT', 80 );

// ブレイクポイント
define( 'BREAKPOINT', 980 );

// テーマディレクトリpath.
define( 'THEMEROOTPATH', get_template_directory_uri() );

// テーマディレクトリpath.
define( 'THEMEROOTFULLPATH', get_template_directory() );

// テーマディレクトリpath.
define( 'DIST_URI', get_template_directory_uri() . '/' );
define( 'DIST_PATH', get_template_directory() . '/' );
define( 'ASSETS_PATH', 'assets/' );

// Vite.
$vite_port = getenv( 'VITE_PORT' ) ? getenv( 'VITE_PORT' ) : '3000';
define( 'VITE_SERVER', 'http://' . getenv( 'VITE_API_URL' ) . ':' . $vite_port );
define( 'VITE_ENTRY_POINT', '/src/scripts/' );

// タイトルが空の場合のデフォルトタイトル.
define( 'DEFAULT_TITLE', 'タイトルなし' );
