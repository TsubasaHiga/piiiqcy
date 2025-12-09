<?php
/**
 * Includes a file loaded across all pages.
 *
 * This file contains common utilities, configurations, and functions that
 * ensure consistent behavior and shared functionality across the entire site.
 *
 * @since 1.0.0
 */

// env読み込み.
require_once 'env.php';

// Vite dev server URL
require_once 'init-vite.php';

// toolsを読み込む.
require_once 'tools.php';

// head読み込み.
require_once 'head.php';
