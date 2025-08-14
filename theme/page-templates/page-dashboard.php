<?php
/**
 * The template for displaying the front page
 *
 * @package Visitor_Management_System
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Check if the current user is an Administrator or Manager or Advocate
if ( ! ( current_user_can( 'administrator' ) || current_user_can( 'managing_partner' ) || current_user_can( 'senior_partner' ) || current_user_can( 'advocate' ) || current_user_can( 'pupil' ) || current_user_can( 'client' )) ) {
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

<section id="primary" x-data="{ page: 'ecommerce'}">
    <main id="main">

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
                        <div class="grid grid-cols-12 gap-4 md:gap-6">
                            <div class="col-span-12 space-y-6 xl:col-span-7">
                                <!-- Metric Group One -->
                                <?php get_template_part( 'template-parts/content/content', 'metric' ); ?>
                                <!-- Metric Group One -->

                                <!-- ====== Chart One Start -->
                                <?php get_template_part( 'template-parts/content/content', 'chart' ); ?>
                                <!-- ====== Chart One End -->
                            </div>
                            <div class="col-span-12 xl:col-span-5">
                                <!-- ====== Chart Two Start -->
                                <?php get_template_part( 'template-parts/content/content', 'chart-02' ); ?>
                                <!-- ====== Chart Two End -->
                            </div>

                            <div class="col-span-12">
                                <!-- ====== Chart Three Start -->
                                <!-- <?php get_template_part( 'template-parts/content/content', 'chart-03' ); ?> -->
                                <!-- ====== Chart Three End -->
                            </div>

                            <div class="col-span-12 xl:col-span-5">
                                <!-- ====== Map One Start -->
                                <!-- <?php get_template_part( 'template-parts/content/content', 'map' ); ?> -->
                                <!-- ====== Map One End -->
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
            </div>
            <!-- ===== Content Area End ===== -->
        </div>
        <!-- ===== Page Wrapper End ===== -->


    </main><!-- #main -->
</section><!-- #primary -->

<?php
get_footer();