<?php
/**
 * The template for displaying the surveyors page
 *
 * @package Visitor_Management_System
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Check if the current user is an Administrator or Manager or Advocate
if ( ! ( current_user_can( 'administrator' ) || current_user_can( 'managing_partner' ) || current_user_can( 'senior_partner' ) || current_user_can( 'advocate' ) || current_user_can( 'pupil' ) ) ) {
	// Redirect unauthorized users to the front page
	wp_redirect( home_url() );
	exit;
}

get_header();

?>

<section x-data="{ page: 'employees'}">
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
                    <div x-data="{ pageName: `Employees`}">
                        <?php get_template_part( 'template-parts/content/content', 'breadcrumb' ); ?>
                    </div>
                    <!-- Breadcrumb End -->

                    <div class="space-y-5 sm:space-y-6">
                        <div
                            class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                            <div class="px-5 py-4 sm:px-6 sm:py-5">
                                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                                    <?php esc_html_e( 'Employees List', 'cyber-wakili' ); ?>
                                </h3>
                            </div>
                            <div class="flex flex-wrap w-full justify-between mb-4 px-5 sm:px-6 ">
                                <!-- Search Form -->
                                <div class="flex w-full items-center  md:w-1/2 mb-4 md:mb-0">
                                    <form action="" method="get">
                                        <div class="relative">
                                            <span class="absolute top-1/2 left-4 -translate-y-1/2">
                                                <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20"
                                                    viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z"
                                                        fill="" />
                                                </svg>
                                            </span>
                                            <input type="text" placeholder="Filter employees by username..."
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
                                <div class="flex items-center justify-end w-full md:w-1/2">
                                    <a href="<?php echo esc_url( site_url( '/register-employee/' ) ); ?>"
                                        class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                                        <?php esc_html_e( 'Register Employee', 'cyber-wakili' ); ?>
                                    </a>
                                </div>
                            </div>
                            <?php
                            // Get messages and clear from session
                            $surveyors_error = isset( $_SESSION['surveyors_error'] ) ? $_SESSION['surveyors_error'] : array();
                            $surveyors_success = isset( $_SESSION['surveyors_success'] ) ? $_SESSION['surveyors_success'] : array();
                            unset( $_SESSION['surveyors_error'], $_SESSION['surveyors_success'] );
                            ?>
                            <!-- Success Alert -->
                            <?php if ( ! empty( $surveyors_success ) ) : ?>
                            <?php foreach ( $surveyors_success as $surveyor_success ) : ?>
                            <div class="flex items-center justify-between bg-green-500 border-l-4 border-green-700 text-white p-4 mb-4 rounded"
                                role="alert">
                                <div>
                                    <strong>Success!</strong>
                                    <p class="text-sm"><?php echo esc_html( $surveyor_success ); ?>
                                    </p>
                                </div>
                                <button type="button" class="float-right text-white hover:text-gray-300"
                                    onclick="this.parentElement.style.display='none';">×</button>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>

                            <!-- Error Alert -->
                            <?php if ( ! empty( $surveyors_error ) ) : ?>
                            <?php foreach ( $surveyors_error as $surveyor_error ) : ?>
                            <div class="flex items-center justify-between bg-red-500 border-l-4 border-red-700 text-white p-4 mb-4 rounded"
                                role="alert">
                                <div>
                                    <strong>Warning!</strong>
                                    <p class="text-sm">
                                        <?php echo esc_html( $surveyor_error ); ?></p>
                                </div>
                                <button type="button" class="float-right text-white hover:text-gray-300"
                                    onclick="this.parentElement.style.display='none';">×</button>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                                <!-- ====== Table Six Start -->
                                <?php get_template_part( 'template-parts/content/content', 'employees-table' ); ?>
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
</section>


<?php
get_footer();