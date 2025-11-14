<?php
/**
 * Template part for displaying footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visitor_Management_System
 */
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>

<footer
	class="z-30 sticky bottom-0 flex w-full bg-white border-t border-gray-200 shadow-[0_-2px_6px_rgba(0,0,0,0.05)] dark:bg-gray-900 dark:border-gray-800 dark:shadow-[0_-2px_6px_rgba(0,0,0,0.6)]">
	<div class="flex flex-col items-center justify-center w-full py-2 md:py-4 px-6 lg:flex-row lg:justify-between">

		<p class="text-xs md:text-sm font-medium text-gray-700 dark:text-white/80 flex items-center gap-1">
			<?php esc_html_e( 'Created with', 'vms' ); ?>
			<span class="text-red-500">❤️</span>
			<?php esc_html_e( 'by', 'vms' ); ?>
			<a href="https://wilsondevops.com" target="_blank" rel="noopener" class="text-brand-500 hover:underline">
				<?php esc_html_e( 'Wilson DevOps', 'vms' ); ?>
			</a>
		</p>

		<p class="text-sm font-medium text-gray-700 dark:text-white/80 mt-1 lg:mt-0">
			© <?php echo date( 'Y' ); ?>
			<strong>
				<?php esc_html_e( 'Nyeri Club VMS.', 'vms' ); ?>
			</strong>
			<?php esc_html_e( 'All Rights Reserved.', 'vms' ); ?>
		</p>

	</div>
</footer>