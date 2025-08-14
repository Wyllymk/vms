<?php
/**
 * The header for our theme
 *
 * This is the template that displays the `head` element and everything up
 * until the `#content` element.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Visitor_Management_System
 */
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="icon" type="image/svg+xml"
        href="<?php echo get_template_directory_uri(); ?>/assets/favicon/favicon.svg" />
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180"
        href="<?php echo get_template_directory_uri(); ?>/assets/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Nyeri Club" />
    <meta name="application-name" content="Nyeri Club" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/assets/favicon/site.webmanifest" />
    <?php wp_head(); ?>
</head>

<body x-data="{ 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark bg-gray-900': darkMode === true}" <?php body_class(); ?>>

    <?php wp_body_open(); ?>

    <div id="page">
        <a href="#content" class="sr-only"><?php esc_html_e( 'Skip to content', 'vms' ); ?></a>

        <!-- ===== Preloader Start ===== -->
        <div x-show="loaded"
            x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})"
            class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
            <div
                class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent">
            </div>
        </div>
        <!-- ===== Preloader End ===== -->

        <div id="content">