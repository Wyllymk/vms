<?php
/**
 * The template for displaying the Reciprocating Members page
 *
 * @package Visitor_Management_System
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

// Check if the current user is an Administrator or Manager or Advocate
if ( ! ( current_user_can( 'administrator' ) || current_user_can( 'general_manager' ) || current_user_can( 'chairman' ) || current_user_can( 'reception' ) || current_user_can( 'gate' ) ) ) {
	// Redirect unauthorized users to the front page
	wp_redirect( home_url() );
	exit;
}

get_header();
?>

<section x-data="{ page: 'reciprocating-members', 'isReciprocationModal': false }"
    @close-guest-modal.window="isReciprocationModal = false">
    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex h-screen overflow-hidden">
        <!-- ===== Sidebar Start ===== -->
        <?php get_template_part( 'template-parts/content/content', 'sidebar' ); ?>
        <!-- ===== Sidebar End ===== -->

        <!-- ===== Content Area Start ===== -->
        <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Small Device Overlay Start -->
            <?php get_template_part( 'template-parts/content/content', 'overlay' ); ?>
            <!-- Small Device Overlay End -->

            <!-- ===== Header Start ===== -->
            <?php get_template_part( 'template-parts/content/content', 'header' ); ?>
            <!-- ===== Header End ===== -->

            <!-- ===== Main Content Start ===== -->
            <main>
                <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                    <!-- Breadcrumb Start -->
                    <div x-data="{ pageName: `Reciprocating Members` }">
                        <?php get_template_part( 'template-parts/content/content', 'breadcrumb' ); ?>
                    </div>
                    <!-- Breadcrumb End -->

                    <div class="space-y-5 sm:space-y-6">
                        <div
                            class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                            <div class="px-5 py-4 sm:px-6 sm:py-5">
                                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                                    <?php esc_html_e( 'Reciprocating Members List', 'vms' ); ?>
                                </h3>
                            </div>
                            <div class="flex flex-wrap justify-between w-full px-5 mb-4 sm:px-6 ">
                                <!-- Search Form -->
                                <div class="flex items-center w-full mb-4 md:w-1/2 md:mb-0">
                                    <form action="" method="get">
                                        <div class="relative">
                                            <span class="absolute -translate-y-1/2 top-1/2 left-4">
                                                <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20"
                                                    viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z"
                                                        fill="" />
                                                </svg>
                                            </span>
                                            <input type="text" placeholder="Filter members by name..."
                                                name="user_search"
                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pr-14 pl-12 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[430px] dark:border-gray-800 dark:bg-gray-900 dark:bg-white/[0.03] dark:text-white/90 dark:placeholder:text-white/30" />

                                            <button name="search_users" type="submit"
                                                class="absolute top-1/2 right-2.5 inline-flex -translate-y-1/2 items-center gap-0.5 rounded-lg border border-gray-200 bg-gray-50 px-[7px] py-[4.5px] text-xs -tracking-[0.2px] text-gray-500 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400">
                                                <span> ⌘ </span>
                                                <span> K </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Register Button -->
                                <div
                                    class="flex items-center justify-between md:justify-end w-full md:w-1/2 gap-2 md:gap-4">
                                    <a @click="isReciprocationModal = true"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg cursor-pointer bg-brand-500 shadow-theme-xs hover:bg-brand-600 whitespace-nowrap">
                                        <?php esc_html_e( 'Register Members', 'vms' ); ?>
                                    </a>
                                </div>
                            </div>
                            <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                                <?php if (!empty($guests_success)) : ?>
                                <?php foreach ((array)$guests_success as $guest_success) : ?>
                                <div class="flex items-center justify-between p-4 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500 rounded"
                                    role="alert">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <p class="font-medium"><?php esc_html_e('Success!', 'vms'); ?></p>
                                            <p class="text-sm"><?php echo esc_html($guest_success); ?></p>
                                        </div>
                                    </div>
                                    <button type="button" class="text-green-700 hover:text-green-900"
                                        onclick="this.parentElement.style.display='none';">
                                        <span class="sr-only"><?php esc_html_e('Close', 'vms'); ?></span>
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>

                                <?php if (!empty($guests_error)) : ?>
                                <?php foreach ((array)$guests_error as $guest_error) : ?>
                                <div class="flex items-center justify-between p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500 rounded"
                                    role="alert">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <p class="font-medium"><?php esc_html_e('Error!', 'vms'); ?></p>
                                            <p class="text-sm"><?php echo esc_html($guest_error); ?></p>
                                        </div>
                                    </div>
                                    <button type="button" class="text-red-700 hover:text-red-900"
                                        onclick="this.parentElement.style.display='none';">
                                        <span class="sr-only"><?php esc_html_e('Close', 'vms'); ?></span>
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>

                                <!-- Rest of your content -->
                            </div>
                            <div class="p-1 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                                <!-- ====== Table Six Start -->
                                <?php get_template_part( 'template-parts/content/content', 'reciprocating-table' ); ?>
                                <!-- ====== Table Six End -->
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- ===== Main Content End ===== -->
        </div>
        <!-- ===== Content Area End ===== -->
    </div>
    <!-- ===== Page Wrapper End ===== -->

    <!-- BEGIN MODAL -->
    <?php get_template_part( 'template-parts/content/content', 'reciprocation-modal' ); ?>
    <!-- END MODAL -->
</section>

<?php
get_footer();