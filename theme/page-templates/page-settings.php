<?php
/**
 * The template for displaying the settings page
 *
 * @package Visitor_Management_System
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Check if the current user is an Administrator or Manager or Advocate
if ( ! ( current_user_can( 'administrator' ) || current_user_can( 'managing_partner' ) || current_user_can( 'senior_partner' ) ) ) {
	// Redirect unauthorized users to the front page
	wp_redirect( home_url() );
	exit;
}

get_header();

// Retrieve the saved SMS balance and last checked time
$sms_balance       = get_option( 'mobilesasa_sms_balance', 'N/A' );
$last_checked_time = get_option( 'mobilesasa_last_check', 'N/A' );

?>

<section x-data="{ page: 'settings'">
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
                    <div x-data="{ pageName: `Settings`}">
                        <?php get_template_part( 'template-parts/content/content', 'breadcrumb' ); ?>
                    </div>
                    <!-- Main content here grows to fill the available space -->
                    <div class="content-page">
                        <!-- Wrapper for light/dark mode support -->
                        <div class="content-page text-gray-800 dark:text-gray-100">
                            <div class="mx-auto py-6">
                                <div class="flex flex-col">
                                    <div
                                        class="rounded-2xl shadow-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                                        <!-- Card Header -->
                                        <div
                                            class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                            <h4 class="text-lg font-semibold">Settings</h4>
                                        </div>

                                        <!-- Card Body -->
                                        <div class="px-6 py-4">

                                            <?php                                                
                                                // Display success messages
                                                if ( isset( $_SESSION['settings_success'] ) && ! empty( $_SESSION['settings_success'] ) ) :
                                                    foreach ( $_SESSION['settings_success'] as $success_message ) :
                                                ?>
                                            <div class="flex items-center justify-between bg-green-500 border-l-4 border-green-700 text-white p-4 mb-4 rounded"
                                                role="alert">
                                                <div>
                                                    <strong>Success!</strong>
                                                    <p class="text-sm">
                                                        <?php echo ucwords( esc_html( $success_message ) ); ?></p>
                                                </div>
                                                <button type="button"
                                                    class="cursor-pointer float-right text-white hover:text-gray-300"
                                                    onclick="this.parentElement.style.display='none';">×</button>
                                            </div>
                                            <?php
                                                    endforeach;
                                                    unset( $_SESSION['settings_success'] ); // Clear success messages after displaying
                                                endif;                                                
                                                ?>

                                            <!-- Update Account Info -->
                                            <div class="overflow-auto">
                                                <h3
                                                    class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4 border-b border-gray-200 dark:border-gray-700">
                                                    Update SMS Information
                                                </h3>
                                                <form action="" method="post" enctype="multipart/form-data">
                                                    <?php wp_nonce_field( 'update_account_data', '_wpnonce_update_account_data' ); ?>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <!-- Sender ID -->
                                                        <div class="mb-4">
                                                            <div class="flex space-x-4">
                                                                <!-- Sender ID Input -->
                                                                <div class="w-1/2">
                                                                    <label for="uname"
                                                                        class="block text-gray-700 dark:text-gray-300">
                                                                        Sender ID:
                                                                    </label>
                                                                    <input type="text"
                                                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                                        id="uname"
                                                                        value="<?php echo esc_attr( get_option( 'mobilesasa_sender_id', '' ) ); ?>"
                                                                        name="sender_id" placeholder="Enter Sender ID">
                                                                    <small
                                                                        class="text-sm text-gray-600 dark:text-gray-400">
                                                                        Please enter a valid Sender ID by
                                                                        MobileSasa.
                                                                    </small>
                                                                </div>

                                                                <!-- SMS Balance Input -->
                                                                <div class="w-1/2">
                                                                    <label for="sms_balance"
                                                                        class="block text-gray-700 dark:text-gray-300">
                                                                        SMS Balance:
                                                                    </label>
                                                                    <input type="text"
                                                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                                        value="<?php echo esc_attr( $sms_balance ); ?>"
                                                                        readonly>
                                                                    <small
                                                                        class="text-sm text-gray-600 dark:text-gray-400">
                                                                        Last Checked:
                                                                        <?php echo esc_attr( $last_checked_time ); ?>
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- End of Sender ID -->
                                                        <!-- API Token -->
                                                        <div class="mb-4">
                                                            <label for="api_token"
                                                                class="block text-gray-700 dark:text-gray-300">
                                                                API Token:
                                                            </label>
                                                            <input type="text"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                                id="api_token"
                                                                value="<?php echo esc_attr( get_option( 'mobilesasa_api_token', '' ) ); ?>"
                                                                name="api_token" placeholder="Enter API Token">
                                                            <small class="text-sm text-gray-600 dark:text-gray-400">
                                                                Please enter your API Token.
                                                            </small>
                                                        </div>
                                                        <!-- End of API Token -->
                                                    </div>
                                                    <div class="mt-4 flex justify-center space-x-2">
                                                        <a type="submit" name="update_details"
                                                            class="cursor-pointer inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                                                            Update Settings
                                                        </a>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- End of Update Account Info -->
                                        </div>
                                    </div>
                                </div>
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
</section>

<?php
get_footer();