<?php
/**
 * The template for displaying the dashboard page
 *
 * @package Visitor_Management_System
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Check if the current user has appropriate permissions
if ( ! ( current_user_can( 'administrator' ) || current_user_can( 'general_manager' ) || 
         current_user_can( 'chairman' ) || current_user_can( 'reception' ) || 
         current_user_can( 'gate' ) ) ) {
    wp_redirect( home_url() );
    exit;
}

// Get the current user info
$current_user = wp_get_current_user();

$first_name = $current_user->first_name;
$last_name  = $current_user->last_name;
$user_login = $current_user->user_login;

if ( !empty($first_name) || !empty($last_name) ) {
    $user_name = trim(ucwords($first_name . ' ' . $last_name));
} else {
    $user_name = ucwords($user_login);
}

// Set Nairobi timezone
date_default_timezone_set('Africa/Nairobi');

// Get time-based greeting
$hour = (int) date('H');
if ($hour < 12) {
    $greeting = __('Good Morning', 'vms');
    $emoji = '🌅';
} elseif ($hour < 18) {
    $greeting = __('Good Afternoon', 'vms');
    $emoji = '☀️';
} else {
    $greeting = __('Good Evening', 'vms');
    $emoji = '🌙';
}

get_header();

global $wpdb;

// Get today's statistics
$today = current_time('Y-m-d');
$guests_table = \WyllyMk\VMS\VMS_Config::get_table_name(\WyllyMk\VMS\VMS_Config::GUESTS_TABLE);
$guest_visits_table = \WyllyMk\VMS\VMS_Config::get_table_name(\WyllyMk\VMS\VMS_Config::GUEST_VISITS_TABLE);
$a_guests_table = \WyllyMk\VMS\VMS_Config::get_table_name(\WyllyMk\VMS\VMS_Config::A_GUESTS_TABLE);
$a_guest_visits_table = \WyllyMk\VMS\VMS_Config::get_table_name(\WyllyMk\VMS\VMS_Config::A_GUEST_VISITS_TABLE);
$suppliers_table = \WyllyMk\VMS\VMS_Config::get_table_name(\WyllyMk\VMS\VMS_Config::SUPPLIERS_TABLE);
$supplier_visits_table = \WyllyMk\VMS\VMS_Config::get_table_name(\WyllyMk\VMS\VMS_Config::SUPPLIER_VISITS_TABLE);
$recip_members_table = \WyllyMk\VMS\VMS_Config::get_table_name(\WyllyMk\VMS\VMS_Config::RECIP_MEMBERS_TABLE);
$recip_visits_table = \WyllyMk\VMS\VMS_Config::get_table_name(\WyllyMk\VMS\VMS_Config::RECIP_MEMBERS_VISITS_TABLE);

// Today's visits count
$today_visits = (int) $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM (
        SELECT id FROM {$guest_visits_table} WHERE visit_date = %s
        UNION ALL
        SELECT id FROM {$a_guest_visits_table} WHERE visit_date = %s
        UNION ALL
        SELECT id FROM {$supplier_visits_table} WHERE visit_date = %s
        UNION ALL
        SELECT id FROM {$recip_visits_table} WHERE visit_date = %s
    ) AS combined",
    $today, $today, $today, $today
));

// Currently signed in COUNT (for the stats card) - REMOVED DUPLICATE
$currently_signed_in = (int) $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM (
        SELECT id FROM {$guest_visits_table} 
        WHERE visit_date = %s AND sign_in_time IS NOT NULL AND sign_out_time IS NULL
        UNION ALL
        SELECT id FROM {$a_guest_visits_table} 
        WHERE visit_date = %s AND sign_in_time IS NOT NULL AND sign_out_time IS NULL
        UNION ALL
        SELECT id FROM {$supplier_visits_table} 
        WHERE visit_date = %s AND sign_in_time IS NOT NULL AND sign_out_time IS NULL
        UNION ALL
        SELECT id FROM {$recip_visits_table} 
        WHERE visit_date = %s AND sign_in_time IS NOT NULL AND sign_out_time IS NULL
    ) AS signed_in",
    $today, $today, $today, $today
));

// Get currently signed in visitors with details - LIMITED TO 4 MOST RECENT
// FIXED: Removed prepare() since no user input and using direct date variable
$currently_signed_in_visitors = $wpdb->get_results(
    "SELECT * FROM (
        SELECT 
            g.first_name, 
            g.last_name, 
            gv.sign_in_time, 
            'Guest' as type
        FROM {$guest_visits_table} gv
        INNER JOIN {$guests_table} g ON gv.guest_id = g.id
        WHERE gv.visit_date = '{$today}' AND gv.sign_in_time IS NOT NULL AND gv.sign_out_time IS NULL
        
        UNION ALL
        
        SELECT 
            ag.first_name, 
            ag.last_name, 
            agv.sign_in_time, 
            'Accommodation Guest' as type
        FROM {$a_guest_visits_table} agv
        INNER JOIN {$a_guests_table} ag ON agv.guest_id = ag.id
        WHERE agv.visit_date = '{$today}' AND agv.sign_in_time IS NOT NULL AND agv.sign_out_time IS NULL
        
        UNION ALL
        
        SELECT 
            s.first_name, 
            s.last_name, 
            sv.sign_in_time, 
            'Supplier' as type
        FROM {$supplier_visits_table} sv
        INNER JOIN {$suppliers_table} s ON sv.guest_id = s.id
        WHERE sv.visit_date = '{$today}' AND sv.sign_in_time IS NOT NULL AND sv.sign_out_time IS NULL
        
        UNION ALL
        
        SELECT 
            r.first_name, 
            r.last_name, 
            rv.sign_in_time, 
            'Reciprocating Member' as type
        FROM {$recip_visits_table} rv
        INNER JOIN {$recip_members_table} r ON rv.member_id = r.id
        WHERE rv.visit_date = '{$today}' AND rv.sign_in_time IS NOT NULL AND rv.sign_out_time IS NULL
    ) AS all_visitors
    ORDER BY sign_in_time DESC
    LIMIT 4"
);

// Debug: Check what we're getting
error_log("Total currently signed in: " . $currently_signed_in);
error_log("Recent visitors count: " . count($currently_signed_in_visitors));
if (!empty($currently_signed_in_visitors)) {
    foreach ($currently_signed_in_visitors as $index => $visitor) {
        error_log("Visitor {$index}: {$visitor->first_name} {$visitor->last_name} - {$visitor->sign_in_time} - {$visitor->type}");
    }
}

// Get the count for the stats card
$currently_signed_in_count = count($currently_signed_in_visitors);

// This week's visits
$week_start = date('Y-m-d', strtotime('monday this week'));
$week_end = date('Y-m-d', strtotime('sunday this week'));
$week_visits = (int) $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM (
        SELECT id FROM {$guest_visits_table} WHERE visit_date BETWEEN %s AND %s
        UNION ALL
        SELECT id FROM {$a_guest_visits_table} WHERE visit_date BETWEEN %s AND %s
        UNION ALL
        SELECT id FROM {$supplier_visits_table} WHERE visit_date BETWEEN %s AND %s
        UNION ALL
        SELECT id FROM {$recip_visits_table} WHERE visit_date BETWEEN %s AND %s
    ) AS combined",
    $week_start, $week_end, $week_start, $week_end, $week_start, $week_end, $week_start, $week_end
));

// This month's visits
$month_start = date('Y-m-01');
$month_end = date('Y-m-t');
$month_visits = (int) $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM (
        SELECT id FROM {$guest_visits_table} WHERE visit_date BETWEEN %s AND %s
        UNION ALL
        SELECT id FROM {$a_guest_visits_table} WHERE visit_date BETWEEN %s AND %s
        UNION ALL
        SELECT id FROM {$supplier_visits_table} WHERE visit_date BETWEEN %s AND %s
        UNION ALL
        SELECT id FROM {$recip_visits_table} WHERE visit_date BETWEEN %s AND %s
    ) AS combined",
    $month_start, $month_end, $month_start, $month_end, $month_start, $month_end, $month_start, $month_end
));

// Get comparison data for trends
$yesterday = date('Y-m-d', strtotime('-1 day'));
$yesterday_visits = (int) $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM (
        SELECT id FROM {$guest_visits_table} WHERE visit_date = %s
        UNION ALL
        SELECT id FROM {$a_guest_visits_table} WHERE visit_date = %s
        UNION ALL
        SELECT id FROM {$supplier_visits_table} WHERE visit_date = %s
        UNION ALL
        SELECT id FROM {$recip_visits_table} WHERE visit_date = %s
    ) AS combined",
    $yesterday, $yesterday, $yesterday, $yesterday
));

$last_week_start = date('Y-m-d', strtotime('monday last week'));
$last_week_end = date('Y-m-d', strtotime('sunday last week'));
$last_week_visits = (int) $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM (
        SELECT id FROM {$guest_visits_table} WHERE visit_date BETWEEN %s AND %s
        UNION ALL
        SELECT id FROM {$a_guest_visits_table} WHERE visit_date BETWEEN %s AND %s
        UNION ALL
        SELECT id FROM {$supplier_visits_table} WHERE visit_date BETWEEN %s AND %s
        UNION ALL
        SELECT id FROM {$recip_visits_table} WHERE visit_date BETWEEN %s AND %s
    ) AS combined",
    $last_week_start, $last_week_end, $last_week_start, $last_week_end, $last_week_start, $last_week_end, $last_week_start, $last_week_end
));

$last_month_start = date('Y-m-01', strtotime('-1 month'));
$last_month_end = date('Y-m-t', strtotime('-1 month'));
$last_month_visits = (int) $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM (
        SELECT id FROM {$guest_visits_table} WHERE visit_date BETWEEN %s AND %s
        UNION ALL
        SELECT id FROM {$a_guest_visits_table} WHERE visit_date BETWEEN %s AND %s
        UNION ALL
        SELECT id FROM {$supplier_visits_table} WHERE visit_date BETWEEN %s AND %s
        UNION ALL
        SELECT id FROM {$recip_visits_table} WHERE visit_date BETWEEN %s AND %s
    ) AS combined",
    $last_month_start, $last_month_end, $last_month_start, $last_month_end, $last_month_start, $last_month_end, $last_month_start, $last_month_end
));

// Calculate percentage changes
$today_change = $yesterday_visits > 0 ? (($today_visits - $yesterday_visits) / $yesterday_visits) * 100 : ($today_visits > 0 ? 100 : 0);
$week_change = $last_week_visits > 0 ? (($week_visits - $last_week_visits) / $last_week_visits) * 100 : ($week_visits > 0 ? 100 : 0);
$month_change = $last_month_visits > 0 ? (($month_visits - $last_month_visits) / $last_month_visits) * 100 : ($month_visits > 0 ? 100 : 0);

// Get hourly data for heatmap
$hourly_data = $wpdb->get_results($wpdb->prepare(
    "SELECT 
        HOUR(sign_in_time) as hour,
        COUNT(*) as count
    FROM (
        SELECT sign_in_time FROM {$guest_visits_table} 
        WHERE visit_date BETWEEN %s AND %s AND sign_in_time IS NOT NULL
        UNION ALL
        SELECT sign_in_time FROM {$a_guest_visits_table} 
        WHERE visit_date BETWEEN %s AND %s AND sign_in_time IS NOT NULL
        UNION ALL
        SELECT sign_in_time FROM {$supplier_visits_table} 
        WHERE visit_date BETWEEN %s AND %s AND sign_in_time IS NOT NULL
        UNION ALL
        SELECT sign_in_time FROM {$recip_visits_table} 
        WHERE visit_date BETWEEN %s AND %s AND sign_in_time IS NOT NULL
    ) AS all_visits
    GROUP BY HOUR(sign_in_time)
    ORDER BY hour",
    $week_start, $week_end, $week_start, $week_end, 
    $week_start, $week_end, $week_start, $week_end
));

// Prepare hourly data in 4-hour blocks
$hour_blocks = array_fill(0, 6, 0);
$block_labels = [
    'Early Morning<br>00:00-03:59',
    'Morning<br>04:00-07:59', 
    'Late Morning<br>08:00-11:59',
    'Afternoon<br>12:00-15:59',
    'Evening<br>16:00-19:59',
    'Night<br>20:00-23:59'
];

foreach ($hourly_data as $data) {
    $block = floor($data->hour / 4);
    $hour_blocks[$block] += (int)$data->count;
}

$max_visits = max($hour_blocks);
if ($max_visits == 0) {
    $max_visits = 1;
}

// Get visitor type breakdown
$type_breakdown = array(
    'guests' => (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$guest_visits_table} WHERE visit_date = %s",
        $today
    )),
    'accommodation' => (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$a_guest_visits_table} WHERE visit_date = %s",
        $today
    )),
    'suppliers' => (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$supplier_visits_table} WHERE visit_date = %s",
        $today
    )),
    'reciprocating' => (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$recip_visits_table} WHERE visit_date = %s",
        $today
    ))
);
$total_today = array_sum($type_breakdown);

?>

<section x-data="{ page: 'dashboard'}">
    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex overflow-hidden h-svh">
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

                    <!-- Welcome Banner -->
                    <div class="p-6 mb-6 text-white shadow-lg rounded-2xl bg-gradient-to-r from-brand-500 to-brand-600">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <h1 class="mb-2 text-2xl font-bold md:text-3xl">
                                    <?php echo esc_html( $greeting ); ?>, <?php echo esc_html( ucwords($user_name) ); ?>!
                                    <?php echo $emoji; ?>
                                </h1>
                                <p class="text-sm text-white/90 md:text-base">
                                    <?php echo esc_html( date( 'l, F j, Y' ) ); ?> •
                                    <span class="font-semibold"><?php echo esc_html( $currently_signed_in ); ?></span>
                                    <?php esc_html_e( 'visitors currently on premises', 'vms' ); ?>
                                </p>
                            </div>
                            <div class="flex gap-3">
                                <a href="<?php echo esc_url( home_url( '/reports' ) ); ?>"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-brand-600 rounded-lg font-medium hover:bg-white/90 transition shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                        </path>
                                    </svg>
                                    <?php esc_html_e( 'View Reports', 'vms' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                        <!-- Today's Visits -->
                        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php esc_html_e( "Today's Visits", 'vms' ); ?>
                            </p>

                            <div class="flex items-end justify-between mt-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-xl dark:bg-blue-900/20">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                                            <?php echo esc_html( $today_visits ); ?>
                                        </h4>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1">
                                    <?php if ( $today_change >= 0 ) : ?>
                                    <span class="flex items-center gap-1 rounded-full bg-success-50 px-2 py-0.5 text-theme-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                                        +<?php echo number_format( $today_change, 1 ); ?>%
                                    </span>
                                    <?php else : ?>
                                    <span class="flex items-center gap-1 rounded-full bg-error-50 px-2 py-0.5 text-theme-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">
                                        <?php echo number_format( $today_change, 1 ); ?>%
                                    </span>
                                    <?php endif; ?>

                                    <span class="text-gray-500 text-theme-xs dark:text-gray-400">
                                        <?php esc_html_e( 'Vs yesterday', 'vms' ); ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Currently Signed In -->
                        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php esc_html_e( 'Currently On Site', 'vms' ); ?>
                            </p>

                            <div class="flex items-end justify-between mt-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-xl dark:bg-green-900/20">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                                            <?php echo esc_html( $currently_signed_in ); ?>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- This Week -->
                        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php esc_html_e( 'This Week', 'vms' ); ?>
                            </p>

                            <div class="flex items-end justify-between mt-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-xl dark:bg-purple-900/20">
                                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                                            <?php echo esc_html( $week_visits ); ?>
                                        </h4>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1">
                                    <?php if ( $week_change >= 0 ) : ?>
                                    <span class="flex items-center gap-1 rounded-full bg-success-50 px-2 py-0.5 text-theme-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                                        +<?php echo number_format( $week_change, 1 ); ?>%
                                    </span>
                                    <?php else : ?>
                                    <span class="flex items-center gap-1 rounded-full bg-error-50 px-2 py-0.5 text-theme-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">
                                        <?php echo number_format( $week_change, 1 ); ?>%
                                    </span>
                                    <?php endif; ?>

                                    <span class="text-gray-500 text-theme-xs dark:text-gray-400">
                                        <?php esc_html_e( 'Vs last week', 'vms' ); ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- This Month -->
                        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php esc_html_e( 'This Month', 'vms' ); ?>
                            </p>

                            <div class="flex items-end justify-between mt-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-10 h-10 bg-orange-100 rounded-xl dark:bg-orange-900/20">
                                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                                            <?php echo esc_html( $month_visits ); ?>
                                        </h4>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1">
                                    <?php if ( $month_change >= 0 ) : ?>
                                    <span class="flex items-center gap-1 rounded-full bg-success-50 px-2 py-0.5 text-theme-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                                        +<?php echo number_format( $month_change, 1 ); ?>%
                                    </span>
                                    <?php else : ?>
                                    <span class="flex items-center gap-1 rounded-full bg-error-50 px-2 py-0.5 text-theme-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">
                                        <?php echo number_format( $month_change, 1 ); ?>%
                                    </span>
                                    <?php endif; ?>

                                    <span class="text-gray-500 text-theme-xs dark:text-gray-400">
                                        <?php esc_html_e( 'Vs last month', 'vms' ); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Grid -->
                    <div class="grid grid-cols-12 gap-4 md:gap-6">

                        <!-- Metrics Overview -->
                        <div class="col-span-12">
                            <?php get_template_part( 'template-parts/content/content', 'metric' ); ?>
                        </div>

                        <!-- Visitor Activity Heatmap -->
                        <div class="col-span-12 xl:col-span-8">
                            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] h-full">
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                            <?php esc_html_e('Activity Heatmap', 'vms'); ?>
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                            <?php esc_html_e('Peak hours this week', 'vms'); ?>
                                        </p>
                                    </div>
                                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                                    </svg>
                                </div>

                                <!-- Heatmap Grid - Responsive 2 Rows -->
                                <div class="mb-6">
                                    <div class="grid grid-cols-3 gap-2 md:grid-cols-6 md:gap-3">
                                        <?php for ($block = 0; $block < 6; $block++): ?>
                                            <div class="text-center">
                                                <div class="mb-1 text-xs text-gray-500 md:mb-2 dark:text-gray-400">
                                                    <?php echo $block_labels[$block]; ?>
                                                </div>
                                                <?php 
                                                $block_visits = $hour_blocks[$block];
                                                $intensity = ($block_visits / $max_visits) * 100;
                                                $color_class = 'bg-gray-100 dark:bg-gray-800';
                                                
                                                if ($intensity > 75) {
                                                    $color_class = 'bg-red-500';
                                                } elseif ($intensity > 50) {
                                                    $color_class = 'bg-orange-500';
                                                } elseif ($intensity > 25) {
                                                    $color_class = 'bg-yellow-500';
                                                } elseif ($intensity > 0) {
                                                    $color_class = 'bg-green-500';
                                                }
                                                ?>
                                                <div class="relative group">
                                                    <div class="h-16 md:h-20 rounded-lg <?php echo $color_class; ?> transition-all duration-200 hover:scale-105 hover:shadow-lg cursor-pointer flex items-center justify-center">
                                                        <span class="text-sm font-semibold <?php echo $intensity > 0 ? 'text-white drop-shadow-md' : 'text-gray-500 dark:text-gray-400'; ?>">
                                                            <?php echo $block_visits; ?>
                                                        </span>
                                                    </div>
                                                    <div class="absolute z-10 hidden mb-2 transform -translate-x-1/2 bottom-full left-1/2 group-hover:block">
                                                        <div class="px-3 py-2 text-xs text-white bg-gray-900 rounded-lg shadow-lg whitespace-nowrap">
                                                            <div class="font-semibold"><?php echo str_replace('<br>', ' ', $block_labels[$block]); ?></div>
                                                            <div><?php echo $block_visits; ?> visits</div>
                                                            <div class="text-gray-300">Intensity: <?php echo round($intensity, 1); ?>%</div>
                                                        </div>
                                                        <div class="absolute transform -translate-x-1/2 border-4 border-transparent top-full left-1/2 border-t-gray-900"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                    
                                    <!-- Legend -->
                                    <div class="flex flex-wrap items-center justify-center gap-2 mt-4 text-xs text-gray-600 md:gap-3 dark:text-gray-400">
                                        <div class="flex items-center gap-1">
                                            <div class="w-3 h-3 bg-gray-100 rounded dark:bg-gray-800"></div>
                                            <span>None (0%)</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-3 h-3 bg-green-500 rounded"></div>
                                            <span>Low (1-25%)</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-3 h-3 bg-yellow-500 rounded"></div>
                                            <span>Medium (26-50%)</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-3 h-3 bg-orange-500 rounded"></div>
                                            <span>High (51-75%)</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-3 h-3 bg-red-500 rounded"></div>
                                            <span>Peak (76-100%)</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Visitor Type Breakdown -->
                                <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <h4 class="mb-4 text-sm font-semibold text-gray-800 dark:text-white/90">
                                        <?php esc_html_e('Today\'s Visitor Types', 'vms'); ?>
                                    </h4>
                                    
                                    <div class="space-y-3">
                                        <!-- Guests -->
                                        <div class="flex items-center justify-between p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20">
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                                <span class="text-sm text-gray-700 dark:text-gray-300">Guests</span>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                <?php echo $type_breakdown['guests']; ?>
                                            </span>
                                        </div>

                                        <!-- Accommodation -->
                                        <div class="flex items-center justify-between p-3 rounded-lg bg-green-50 dark:bg-green-900/20">
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                                <span class="text-sm text-gray-700 dark:text-gray-300">Accommodation</span>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                <?php echo $type_breakdown['accommodation']; ?>
                                            </span>
                                        </div>

                                        <!-- Suppliers -->
                                        <div class="flex items-center justify-between p-3 rounded-lg bg-purple-50 dark:bg-purple-900/20">
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                                <span class="text-sm text-gray-700 dark:text-gray-300">Suppliers</span>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                <?php echo $type_breakdown['suppliers']; ?>
                                            </span>
                                        </div>

                                        <!-- Reciprocating -->
                                        <div class="flex items-center justify-between p-3 rounded-lg bg-orange-50 dark:bg-orange-900/20">
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                                                <span class="text-sm text-gray-700 dark:text-gray-300">Reciprocating</span>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                <?php echo $type_breakdown['reciprocating']; ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Total -->
                                    <div class="flex items-center justify-between p-3 mt-4 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Today</span>
                                        <span class="text-lg font-bold text-gray-900 dark:text-white">
                                            <?php echo $total_today; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions Card -->
                        <div class="col-span-12 xl:col-span-4">
                            <div
                                class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] h-full">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                        <?php esc_html_e( 'Quick Actions', 'vms' ); ?>
                                    </h3>
                                    <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <!-- Register Guest -->
                                    <a href="<?php echo esc_url( home_url( '/guests' ) ); ?>"
                                        class="flex flex-col items-center justify-center p-4 transition-all border border-blue-100 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl hover:shadow-md group dark:border-blue-800/30">
                                        <div
                                            class="flex items-center justify-center w-12 h-12 mb-3 text-white transition-transform bg-blue-500 rounded-full group-hover:scale-110">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                                </path>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-center text-gray-800 dark:text-white">
                                            <?php esc_html_e( 'Guests', 'vms' ); ?>
                                        </span>
                                    </a>

                                    <!-- Accommodation -->
                                    <a href="<?php echo esc_url( home_url( '/accommodation' ) ); ?>"
                                        class="flex flex-col items-center justify-center p-4 transition-all border border-green-100 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl hover:shadow-md group dark:border-green-800/30">
                                        <div
                                            class="flex items-center justify-center w-12 h-12 mb-3 text-white transition-transform bg-green-500 rounded-full group-hover:scale-110">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                                </path>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-center text-gray-800 dark:text-white">
                                            <?php esc_html_e( 'Accommodation', 'vms' ); ?>
                                        </span>
                                    </a>

                                    <!-- Suppliers -->
                                    <a href="<?php echo esc_url( home_url( '/suppliers' ) ); ?>"
                                        class="flex flex-col items-center justify-center p-4 transition-all border border-purple-100 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl hover:shadow-md group dark:border-purple-800/30">
                                        <div
                                            class="flex items-center justify-center w-12 h-12 mb-3 text-white transition-transform bg-purple-500 rounded-full group-hover:scale-110">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                </path>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-center text-gray-800 dark:text-white">
                                            <?php esc_html_e( 'Suppliers', 'vms' ); ?>
                                        </span>
                                    </a>

                                    <!-- Reciprocating Members -->
                                    <a href="<?php echo esc_url( home_url( '/reciprocating-members' ) ); ?>"
                                        class="flex flex-col items-center justify-center p-4 transition-all border border-orange-100 bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl hover:shadow-md group dark:border-orange-800/30">
                                        <div
                                            class="flex items-center justify-center w-12 h-12 mb-3 text-white transition-transform bg-orange-500 rounded-full group-hover:scale-110">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-center text-gray-800 dark:text-white">
                                            <?php esc_html_e( 'Reciprocating', 'vms' ); ?>
                                        </span>
                                    </a>

                                    <!-- Members -->
                                    <a href="<?php echo esc_url( home_url( '/members' ) ); ?>"
                                        class="flex flex-col items-center justify-center p-4 transition-all border border-pink-100 bg-gradient-to-br from-pink-50 to-pink-100 dark:from-pink-900/20 dark:to-pink-800/20 rounded-xl hover:shadow-md group dark:border-pink-800/30">
                                        <div
                                            class="flex items-center justify-center w-12 h-12 mb-3 text-white transition-transform bg-pink-500 rounded-full group-hover:scale-110">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-center text-gray-800 dark:text-white">
                                            <?php esc_html_e( 'Members', 'vms' ); ?>
                                        </span>
                                    </a>

                                    <!-- Clubs -->
                                    <a href="<?php echo esc_url( home_url( '/clubs' ) ); ?>"
                                        class="flex flex-col items-center justify-center p-4 transition-all border border-indigo-100 bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 rounded-xl hover:shadow-md group dark:border-indigo-800/30">
                                        <div
                                            class="flex items-center justify-center w-12 h-12 mb-3 text-white transition-transform bg-indigo-500 rounded-full group-hover:scale-110">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-center text-gray-800 dark:text-white">
                                            <?php esc_html_e( 'Clubs', 'vms' ); ?>
                                        </span>
                                    </a>
                                </div>

                                <!-- System Status -->
                                <div class="pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            <?php esc_html_e( 'System Status', 'vms' ); ?>
                                        </span>
                                        <div class="flex items-center gap-2">
                                            <span class="flex w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                            <span class="text-sm font-medium text-green-600 dark:text-green-400">
                                                <?php esc_html_e( 'Online', 'vms' ); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            <?php esc_html_e( 'Last Updated', 'vms' ); ?>
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-500">
                                            <?php echo esc_html( current_time( 'g:i A' ) ); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity Card -->
                        <div class="col-span-12 xl:col-span-8">
                            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                        <?php esc_html_e( 'Currently On Site', 'vms' ); ?>
                                    </h3>
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            <?php echo esc_html( $currently_signed_in ); ?> <?php esc_html_e( 'total', 'vms' ); ?>
                                        </span>
                                        <?php if ( $currently_signed_in > 5 ) : ?>
                                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                                (<?php esc_html_e( 'showing 5 most recent', 'vms' ); ?>)
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if ( ! empty( $currently_signed_in_visitors ) ) : ?>
                                    <div class="space-y-3">
                                        <?php foreach ( $currently_signed_in_visitors as $visitor ) : ?>
                                            <div class="flex items-center justify-between p-3 transition rounded-lg bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex items-center justify-center w-10 h-10 font-semibold rounded-full bg-brand-100 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400">
                                                        <?php echo esc_html( strtoupper( substr( $visitor->first_name, 0, 1 ) ) ); ?>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                            <?php echo esc_html( $visitor->first_name . ' ' . $visitor->last_name ); ?>
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            <?php echo esc_html( $visitor->type ); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-xs font-medium text-gray-900 dark:text-white">
                                                        <?php echo esc_html( date( 'g:i A', strtotime( $visitor->sign_in_time ) ) ); ?>
                                                    </p>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                        <?php esc_html_e( 'On Site', 'vms' ); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>                                  
                                    
                                <?php else : ?>
                                    <div class="py-8 text-center">
                                        <svg class="w-16 h-16 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400">
                                            <?php esc_html_e( 'No visitors currently on site', 'vms' ); ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Activity Summary Card -->
                        <div class="col-span-12 xl:col-span-4">
                            <div
                                class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                                <h3 class="mb-6 text-lg font-semibold text-gray-800 dark:text-white/90">
                                    <?php esc_html_e( 'Today\'s Summary', 'vms' ); ?>
                                </h3>

                                <div class="space-y-4">
                                    <!-- Total Visits Progress -->
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                <?php esc_html_e( 'Total Visits', 'vms' ); ?>
                                            </span>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                <?php echo esc_html( $today_visits ); ?>
                                            </span>
                                        </div>
                                        <div class="w-full h-2 bg-gray-200 rounded-full dark:bg-gray-700">
                                            <div class="h-2 bg-blue-500 rounded-full"
                                                style="width: <?php echo min( ( $today_visits / 50 ) * 100, 100 ); ?>%">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Currently On Site Progress -->
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                <?php esc_html_e( 'Currently On Site', 'vms' ); ?>
                                            </span>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                <?php echo esc_html( $currently_signed_in ); ?>
                                            </span>
                                        </div>
                                        <div class="w-full h-2 bg-gray-200 rounded-full dark:bg-gray-700">
                                            <div class="h-2 bg-green-500 rounded-full"
                                                style="width: <?php echo $today_visits > 0 ? min( ( $currently_signed_in / $today_visits ) * 100, 100 ) : 0; ?>%">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Weekly Progress -->
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                <?php esc_html_e( 'Weekly Target', 'vms' ); ?>
                                            </span>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                <?php echo esc_html( $week_visits ); ?>/200
                                            </span>
                                        </div>
                                        <div class="w-full h-2 bg-gray-200 rounded-full dark:bg-gray-700">
                                            <div class="h-2 bg-purple-500 rounded-full"
                                                style="width: <?php echo min( ( $week_visits / 200 ) * 100, 100 ); ?>%">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Monthly Progress -->
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                <?php esc_html_e( 'Monthly Target', 'vms' ); ?>
                                            </span>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                <?php echo esc_html( $month_visits ); ?>/800
                                            </span>
                                        </div>
                                        <div class="w-full h-2 bg-gray-200 rounded-full dark:bg-gray-700">
                                            <div class="h-2 bg-orange-500 rounded-full"
                                                style="width: <?php echo min( ( $month_visits / 800 ) * 100, 100 ); ?>%">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Link -->
                                <div class="pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                                    <a href="<?php echo esc_url( home_url( '/reports' ) ); ?>"
                                        class="flex items-center justify-center w-full px-4 py-2.5 bg-brand-500 hover:bg-brand-600 text-white rounded-lg font-medium transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <?php esc_html_e( 'View Detailed Reports', 'vms' ); ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity Table (Full Width) -->
                        <div class="col-span-12">
                            <?php get_template_part( 'template-parts/content/content', 'table' ); ?>
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