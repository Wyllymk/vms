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