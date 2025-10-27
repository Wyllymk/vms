<?php
/**
 * Template part for displaying charts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visitor_Management_System
 */
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

global $wpdb;

// Table names
$guest_visits_table   = \WyllyMk\VMS\VMS_Config::get_table_name(\WyllyMk\VMS\VMS_Config::GUEST_VISITS_TABLE);
$a_guest_visits_table = \WyllyMk\VMS\VMS_Config::get_table_name(\WyllyMk\VMS\VMS_Config::A_GUEST_VISITS_TABLE);
$recip_visits_table   = \WyllyMk\VMS\VMS_Config::get_table_name(\WyllyMk\VMS\VMS_Config::RECIP_MEMBERS_VISITS_TABLE);

// Target
$visit_target = 300;

// Current month visits (guests + a_guests + recip)
$current_month_visits = (int) $wpdb->get_var("
    SELECT COUNT(*) FROM (
        SELECT id, visit_date FROM $guest_visits_table 
        WHERE MONTH(visit_date) = MONTH(CURDATE()) AND YEAR(visit_date) = YEAR(CURDATE())
        UNION ALL
        SELECT id, visit_date FROM $a_guest_visits_table 
        WHERE MONTH(visit_date) = MONTH(CURDATE()) AND YEAR(visit_date) = YEAR(CURDATE())
        UNION ALL
        SELECT id, visit_date FROM $recip_visits_table 
        WHERE MONTH(visit_date) = MONTH(CURDATE()) AND YEAR(visit_date) = YEAR(CURDATE())
    ) AS combined_month_visits
");

// Previous month visits (guests + a_guests + recip)
$previous_month_visits = (int) $wpdb->get_var("
    SELECT COUNT(*) FROM (
        SELECT id, visit_date FROM $guest_visits_table 
        WHERE MONTH(visit_date) = MONTH(CURDATE() - INTERVAL 1 MONTH)
          AND YEAR(visit_date) = YEAR(CURDATE() - INTERVAL 1 MONTH)
        UNION ALL
        SELECT id, visit_date FROM $a_guest_visits_table 
        WHERE MONTH(visit_date) = MONTH(CURDATE() - INTERVAL 1 MONTH)
          AND YEAR(visit_date) = YEAR(CURDATE() - INTERVAL 1 MONTH)
        UNION ALL
        SELECT id, visit_date FROM $recip_visits_table 
        WHERE MONTH(visit_date) = MONTH(CURDATE() - INTERVAL 1 MONTH)
          AND YEAR(visit_date) = YEAR(CURDATE() - INTERVAL 1 MONTH)
    ) AS combined_prev_month_visits
");

// Growth percentage vs last month
$growth = $previous_month_visits > 0 
    ? round((($current_month_visits - $previous_month_visits) / $previous_month_visits) * 100) 
    : 100;

// New visitors (distinct guests this month)
$new_visitors = (int) $wpdb->get_var("
    SELECT COUNT(DISTINCT guest_id) 
    FROM (
        SELECT guest_id, visit_date FROM $guest_visits_table
        WHERE MONTH(visit_date) = MONTH(CURDATE()) AND YEAR(visit_date) = YEAR(CURDATE())
        UNION ALL
        SELECT guest_id, visit_date FROM $a_guest_visits_table
        WHERE MONTH(visit_date) = MONTH(CURDATE()) AND YEAR(visit_date) = YEAR(CURDATE())
    ) AS combined_guests
");

?>

<div class="rounded-2xl border border-gray-200 bg-gray-100 dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="px-5 pt-5 bg-white shadow-default rounded-2xl pb-11 dark:bg-gray-900 sm:px-6 sm:pt-6">
        <div class="flex justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    <?php esc_html_e('Monthly Visit Targets', 'vms'); ?>
                </h3>
                <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
                    <?php esc_html_e('Track visitor activity and performance goals', 'vms'); ?>
                </p>
            </div>
        </div>
        <div class="relative max-h-[195px]">
            <div id="chartTwo" class="h-full"
                data-progress="<?php echo round(($current_month_visits / $visit_target) * 100, 2); ?>"></div>

            <span
                class="absolute left-1/2 top-[85%] -translate-x-1/2 -translate-y-[85%] rounded-full bg-success-50 px-3 py-1 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                <?php echo ($growth >= 0 ? '+' : '') . $growth; ?>%
            </span>
        </div>
        <p class="mx-auto mt-1.5 w-full max-w-[380px] text-center text-sm text-gray-500 sm:text-base">
            <?php esc_html_e('This month has recorded', 'vms'); ?>
            <?php echo $current_month_visits; ?>
            <?php esc_html_e('visits', 'vms'); ?>
            <?php echo $current_month_visits >= $visit_target ? ', exceeding the set target. Great performance!' : ', with a target of ' . $visit_target . '.'; ?>
        </p>
    </div>

    <div class="flex items-center justify-center gap-5 px-6 py-3.5 sm:gap-8 sm:py-5">
        <div>
            <p class="mb-1 text-center text-gray-500 text-theme-xs dark:text-gray-400 sm:text-sm">
                <?php esc_html_e('Visit Target', 'vms'); ?>
            </p>
            <p
                class="flex items-center justify-center gap-1 text-base font-semibold text-gray-800 dark:text-white/90 sm:text-lg">
                <?php echo $visit_target; ?>
            </p>
        </div>

        <div class="w-px bg-gray-200 h-7 dark:bg-gray-800"></div>

        <div>
            <p class="mb-1 text-center text-gray-500 text-theme-xs dark:text-gray-400 sm:text-sm">
                <?php esc_html_e('Visits Logged', 'vms'); ?>
            </p>
            <p
                class="flex items-center justify-center gap-1 text-base font-semibold text-gray-800 dark:text-white/90 sm:text-lg">
                <?php echo $current_month_visits; ?>
            </p>
        </div>

        <div class="w-px bg-gray-200 h-7 dark:bg-gray-800"></div>

        <div>
            <p class="mb-1 text-center text-gray-500 text-theme-xs dark:text-gray-400 sm:text-sm">
                <?php esc_html_e('New Visitors', 'vms'); ?>
            </p>
            <p
                class="flex items-center justify-center gap-1 text-base font-semibold text-gray-800 dark:text-white/90 sm:text-lg">
                <?php echo $new_visitors; ?>
            </p>
        </div>
    </div>
</div>