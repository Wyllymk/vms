<?php
/**
 * The template for displaying the settings page
 *
 * @package Visitor_Management_System
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Check if the current user is an Administrator or Manager or Advocate
if ( ! ( current_user_can( 'administrator' ) || current_user_can( 'reception' ) || current_user_can( 'general_manager' ) || current_user_can( 'chairman' ) ) ) {
    // Redirect unauthorized users to the front page
    wp_redirect( home_url() );
    exit;
}

get_header();

// Retrieve current settings
$api_key = get_option( 'vms_sms_api_key', '' );
$api_secret = get_option( 'vms_sms_api_secret', '' );
$sender_id = get_option( 'vms_sms_sender_id', 'SMS_TEST' );
$status_url = get_option( 'vms_status_url', '' );
$status_secret = get_option( 'vms_status_secret', '' );
$sms_balance = get_option( 'vms_sms_balance', 'N/A' );
$last_checked_time = get_option( 'vms_sms_last_check', 'Never' );

?>

<section x-data="{ page: 'settings'}">
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
                <div class="p-4 mx-auto max-w-(--breakpoint-2xl) min-h-screen md:p-6">
                    <!-- Breadcrumb Start -->
                    <div x-data="{ pageName: `SMS Settings`}">
                        <?php get_template_part( 'template-parts/content/content', 'breadcrumb' ); ?>
                    </div>

                    <!-- Alert Container -->
                    <div id="alert-container" class="mb-4"></div>

                    <!-- Main content here grows to fill the available space -->
                    <div class="content-page">
                        <!-- Wrapper for light/dark mode support -->
                        <div class="text-gray-800 content-page dark:text-gray-100">
                            <div class="py-6 mx-auto">
                                <div class="flex flex-col space-y-6">

                                    <!-- SMS Balance Card -->
                                    <div
                                        class="rounded-2xl shadow-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                                        <div
                                            class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                            <h4 class="text-lg font-semibold flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z">
                                                    </path>
                                                </svg>
                                                <?php esc_html_e( 'SMS Balance', 'vms' ); ?>
                                            </h4>
                                            <button type="button" id="refresh-balance"
                                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg bg-blue-500 shadow-theme-xs hover:bg-blue-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                    </path>
                                                </svg>
                                                <span><?php esc_html_e( 'Refresh', 'vms' ); ?></span>
                                            </button>
                                        </div>
                                        <div class="px-6 py-4">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div
                                                    class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400"
                                                        id="balance-amount">
                                                        KES
                                                        <?php echo is_numeric($sms_balance) ? number_format($sms_balance, 2) : esc_html($sms_balance); ?>
                                                    </div>
                                                    <div class="text-sm text-gray-600 dark:text-gray-400">Current
                                                        Balance</div>
                                                </div>
                                                <div
                                                    class="text-center flex flex-col items-center justify-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                                    <div class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                                        Last Updated</div>
                                                    <div class="text-xs text-gray-600 dark:text-gray-400"
                                                        id="last-updated">
                                                        <?php echo $last_checked_time !== 'Never' ? date('M j, Y g:i A', strtotime($last_checked_time)) : 'Never'; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Settings Card -->
                                    <div
                                        class="rounded-2xl shadow-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                                        <!-- Card Header -->
                                        <div
                                            class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                            <h4 class="text-lg font-semibold flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <?php esc_html_e( 'SMS API Configuration', 'vms' ); ?>
                                            </h4>
                                        </div>

                                        <!-- Card Body -->
                                        <div class="px-6 py-4">
                                            <form id="settings-form" enctype="multipart/form-data">
                                                <?php wp_nonce_field( 'vms_save_settings_nonce', 'security' ); ?>

                                                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                                    <!-- API Credentials Section -->
                                                    <div class="space-y-4">
                                                        <h5
                                                            class="text-md font-medium text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 pb-2">
                                                            <?php esc_html_e( 'API Credentials', 'vms' ); ?>
                                                        </h5>

                                                        <!-- API Key -->
                                                        <div>
                                                            <label for="api_key"
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                <?php esc_html_e( 'API Key', 'vms' ); ?> <span
                                                                    class="text-red-500">*</span>
                                                            </label>
                                                            <input type="text"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                                id="api_key" name="api_key"
                                                                value="<?php echo esc_attr( $api_key ); ?>"
                                                                placeholder="Enter your SMS Leopard API Key" required>
                                                            <small class="text-xs text-gray-600 dark:text-gray-400">
                                                                <?php esc_html_e( 'Your SMS Leopard API key from the dashboard', 'vms' ); ?>
                                                            </small>
                                                        </div>

                                                        <!-- API Secret -->
                                                        <div>
                                                            <label for="api_secret"
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                <?php esc_html_e( 'API Secret', 'vms' ); ?> <span
                                                                    class="text-red-500">*</span>
                                                            </label>
                                                            <input type="password"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                                id="api_secret" name="api_secret"
                                                                value="<?php echo esc_attr( $api_secret ); ?>"
                                                                placeholder="Enter your SMS Leopard API Secret"
                                                                required>
                                                            <small class="text-xs text-gray-600 dark:text-gray-400">
                                                                <?php esc_html_e( 'Your SMS Leopard API secret key', 'vms' ); ?>
                                                            </small>
                                                        </div>
                                                    </div>

                                                    <!-- SMS Settings Section -->
                                                    <div class="space-y-4">
                                                        <h5
                                                            class="text-md font-medium text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 pb-2">
                                                            <?php esc_html_e( 'SMS Configuration', 'vms' ); ?>
                                                        </h5>

                                                        <!-- Sender ID -->
                                                        <div>
                                                            <label for="sender_id"
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                <?php esc_html_e( 'Sender ID', 'vms' ); ?>
                                                            </label>
                                                            <input type="text"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                                id="sender_id" name="sender_id"
                                                                value="<?php echo esc_attr( $sender_id ); ?>"
                                                                placeholder="SMS_TEST">
                                                            <small class="text-xs text-gray-600 dark:text-gray-400">
                                                                <?php esc_html_e( 'Use SMS_TEST for testing or your approved sender ID', 'vms' ); ?>
                                                            </small>
                                                        </div>

                                                        <!-- Status URL -->
                                                        <div>
                                                            <label for="status_url"
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                <?php esc_html_e( 'Status Callback URL', 'vms' ); ?>
                                                            </label>
                                                            <input type="url"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                                id="status_url" name="status_url"
                                                                value="<?php echo esc_url( $status_url ); ?>"
                                                                placeholder="https://yoursite.com/sms/callback">
                                                            <small class="text-xs text-gray-600 dark:text-gray-400">
                                                                <?php esc_html_e( 'Optional URL for delivery reports', 'vms' ); ?>
                                                            </small>
                                                        </div>

                                                        <!-- Status Secret -->
                                                        <div>
                                                            <label for="status_secret"
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                <?php esc_html_e( 'Status Secret', 'vms' ); ?>
                                                            </label>
                                                            <input type="text"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                                id="status_secret" name="status_secret"
                                                                value="<?php echo esc_attr( $status_secret ); ?>"
                                                                placeholder="Enter callback verification secret">
                                                            <small class="text-xs text-gray-600 dark:text-gray-400">
                                                                <?php esc_html_e( 'Required if status URL is provided', 'vms' ); ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Action Buttons -->
                                                <div class="flex justify-center mt-8 space-x-4">
                                                    <button type="submit" id="save-settings"
                                                        class="inline-flex items-center justify-center gap-2 px-4 md:px-8 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 focus:ring-3 focus:ring-brand-500/20 whitespace-nowrap">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        <span><?php esc_html_e( 'Save Settings', 'vms' ); ?></span>
                                                    </button>

                                                    <button type="button" id="test-connection"
                                                        class="inline-flex items-center justify-center gap-2 px-4 md:px-8 py-3 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg shadow-theme-xs hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 whitespace-nowrap">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                        </svg>
                                                        <span><?php esc_html_e( 'Test Connection', 'vms' ); ?></span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- ===== Main Content End ===== -->

            <!-- ===== Footer Start ===== -->
            <?php get_template_part( 'template-parts/content/content', 'footer' ); ?>
            <!-- ===== Footer End ===== -->
        </div>
        <!-- ===== Content Area End ===== -->
    </div>
    <!-- ===== Page Wrapper End ===== -->
</section>

<?php
get_footer();