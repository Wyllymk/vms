<?php
/**
 * The template for displaying the front page
 *
 * @package Visitor_Management_System
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Check if the current user is an Administrator or Manager or Advocate
if ( ! ( current_user_can( 'administrator' ) || current_user_can( 'general_manager' ) || current_user_can( 'chairman' ) || current_user_can( 'reception' ) || current_user_can( 'gate' ) ) ) {
	// Redirect unauthorized users to the front page
	wp_redirect( home_url() );
	exit;
}

/**
 * Display a dynamic greeting message based on the time of day and the user's name.
 */

// Get the current user info
$current_user = wp_get_current_user(); // Get the current logged-in user's data
$user_name    = $current_user->display_name; // You can also use $current_user->user_login or $current_user->first_name if you want

get_header();
?>

<section id="primary" x-data="{ page: 'dashboard'}">
    <main id="main">

        <!-- ===== Page Wrapper Start ===== -->
        <div class="flex h-svh overflow-hidden">
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
                        <div class="grid grid-cols-12 gap-4 md:gap-6">
                            <div class="col-span-12">
                                <!-- Metric Group One -->
                                <?php get_template_part( 'template-parts/content/content', 'metric' ); ?>
                                <!-- Metric Group One -->
                            </div>
                            <div class="col-span-12 xl:col-span-6">
                                <!-- ====== Impression & Visitor Traffic Start -->
                                <?php get_template_part( 'template-parts/content/content', 'chart' ); ?>
                                <!-- ====== Impression & Visitor Traffic End -->
                            </div>
                            <div class="col-span-12 xl:col-span-6">
                                <!-- ====== Monthly Visits Target Start -->
                                <?php get_template_part( 'template-parts/content/content', 'chart-02' ); ?>
                                <!-- ====== Monthly Visits Target End -->
                            </div>

                            <div class="col-span-12 ">
                                <!-- ====== Table One Start -->
                                <?php get_template_part( 'template-parts/content/content', 'table' ); ?>
                                <!-- ====== Table One End -->
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


    </main><!-- #main -->
</section><!-- #primary -->

<?php
get_footer();