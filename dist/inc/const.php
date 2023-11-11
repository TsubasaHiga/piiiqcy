<?php
/**
 * Const.php
 *
 * 定数関連を記述します
 *
 * @since 0.0.1
 * @package piiiQcy
 */

// 本番環境のドメイン.
define( 'DOMAIN_PRODUCTION', 'example.com' );

// 開発環境のドメイン.
define( 'DOMAIN_DEV', 'localhost' );

// breadcrumbs.
define( 'BREADCRUMBS_TOP', 'トップ' );

// BLANKIMAGE.
define( 'BLANKIMAGE', 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' );

// NOIMAGE.
define( 'NOIMAGE', get_template_directory_uri() . '/assets/images/common/img__noimage.png' );
define( 'NOIMAGE_2X', get_template_directory_uri() . '/assets/images/common/img__noimage@2x.png' );

// OGPIMAGE.
define( 'OGPIMAGE', get_template_directory_uri() . '/assets/images/ogpimg.jpg' );

// アーカイブページの表示件数を指定.
define( 'POST_PER_PAGE', 5 );

// デフォルトのexcerpt_length.
define( 'EXCERPT_LENGTH', 50 );

// EVENTのexcerpt_length.
define( 'EXCERPT_LENGTH_EVENT', 80 );

// テーマディレクトリpath.
define( 'THEMEROOTPATH', get_template_directory_uri() );

// テーマディレクトリpath.
define( 'THEMEROOTFULLPATH', get_template_directory() );
