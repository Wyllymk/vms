<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Visitor_Management_System
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Prevent WordPress from converting 'paged' into /page/{num}/
add_filter('redirect_canonical', function($redirect_url, $requested_url) {
    if (strpos($requested_url, 'paged=') !== false) {
        return false; // disable WP’s pagination redirect
    }
    return $redirect_url;
}, 10, 2);

/**
 * Update Checker
 * https://github.com/YahnisElsts/plugin-update-checker
 */
require get_template_directory() . '/inc/update/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myThemeUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/Wyllymk/vms/',
    get_theme_file_path('functions.php'),
    'vms'
);

// Same thing: point to branch if needed
$myThemeUpdateChecker->setBranch('main');
$myThemeUpdateChecker->setAuthentication('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');
// Tell PUC to use the release asset (vms.zip) instead of auto-generated zips
$myThemeUpdateChecker->getVcsApi()->enableReleaseAssets();