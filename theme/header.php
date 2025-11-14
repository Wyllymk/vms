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
		href="<?php echo get_template_directory_uri(); ?>/assets/favicon/favicon.png" />
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/favicon/favicon.png" />
	<link rel="apple-touch-icon" sizes="180x180"
		href="<?php echo get_template_directory_uri(); ?>/assets/favicon/favicon.png" />
	<meta name="apple-mobile-web-app-title" content="Nyeri Club" />
	<meta name="application-name" content="Nyeri Club" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />

	<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/assets/favicon/site.webmanifest" />
	<?php wp_head(); ?>
</head>

<body x-data="{ loaded: true, darkMode: false, stickyMenu: false, sidebarToggle: false, scrollTop: false 
	}" x-init="
		(() => {
			// Use saved preference if available
			if (localStorage.getItem('darkMode') !== null) {
				darkMode = JSON.parse(localStorage.getItem('darkMode'));
			} else {
				// Otherwise fall back to system preference
				darkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
			}

			// Watch for system changes
			window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
				if (localStorage.getItem('darkMode') === null) {
					darkMode = e.matches;
				}
			});

			// Persist user choice
			$watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)));
		})()
	" :class="{ 'dark bg-gray-900': darkMode }" <?php body_class(); ?>>

	<div id="page">
		<a href="#content" class="sr-only"><?php esc_html_e( 'Skip to content', 'vms' ); ?></a>

		<!-- ===== Preloader Start ===== -->
		<div x-show="loaded"
			x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})"
			class="fixed top-0 left-0 flex items-center justify-center w-screen h-screen bg-white z-999999 dark:bg-black">
			<div
				class="w-16 h-16 border-4 border-solid rounded-full animate-spin border-brand-500 border-t-transparent">
			</div>
		</div>
		<!-- ===== Preloader End ===== -->

		<div id="content">