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
$guest_visits_table  = \WyllyMk\VMS\VMS_Config::get_table_name(\WyllyMk\VMS\VMS_Config::GUEST_VISITS_TABLE);
$recip_visits_table  = \WyllyMk\VMS\VMS_Config::get_table_name(\WyllyMk\VMS\VMS_Config::RECIP_MEMBERS_VISITS_TABLE);

// Target
$visit_target = 300;

// Current month visits (guests + recip)
$current_month_visits = (int) $wpdb->get_var("
    SELECT COUNT(*) FROM (
        SELECT id, visit_date FROM $guest_visits_table 
        WHERE MONTH(visit_date) = MONTH(CURDATE()) AND YEAR(visit_date) = YEAR(CURDATE())
        UNION ALL
        SELECT id, visit_date FROM $recip_visits_table 
        WHERE MONTH(visit_date) = MONTH(CURDATE()) AND YEAR(visit_date) = YEAR(CURDATE())
    ) AS combined_month_visits
");

// Previous month visits
$previous_month_visits = (int) $wpdb->get_var("
    SELECT COUNT(*) FROM (
        SELECT id, visit_date FROM $guest_visits_table 
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
    FROM $guest_visits_table 
    WHERE MONTH(visit_date) = MONTH(CURDATE()) AND YEAR(visit_date) = YEAR(CURDATE())
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
            <div x-data="{openDropDown: false}" class="relative h-fit">
                <button @click="openDropDown = !openDropDown"
                    :class="openDropDown ? 'text-gray-700 dark:text-white' : 'text-gray-400 hover:text-gray-700 dark:hover:text-white'">
                    <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M10.2441 6C10.2441 5.0335 11.0276 4.25 11.9941 4.25H12.0041C12.9706 4.25 13.7541 5.0335 13.7541 6C13.7541 6.9665 12.9706 7.75 12.0041 7.75H11.9941C11.0276 7.75 10.2441 6.9665 10.2441 6ZM10.2441 18C10.2441 17.0335 11.0276 16.25 11.9941 16.25H12.0041C12.9706 16.25 13.7541 17.0335 13.7541 18C13.7541 18.9665 12.9706 19.75 12.0041 19.75H11.9941C11.0276 19.75 10.2441 18.9665 10.2441 18ZM11.9941 10.25C11.0276 10.25 10.2441 11.0335 10.2441 12C10.2441 12.9665 11.0276 13.75 11.9941 13.75H12.0041C12.9706 13.75 13.7541 12.9665 13.7541 12C13.7541 11.0335 12.9706 10.25 12.0041 10.25H11.9941Z"
                            fill="" />
                    </svg>
                </button>
                <div x-show="openDropDown" @click.outside="openDropDown = false"
                    class="absolute right-0 z-40 w-40 p-2 space-y-1 bg-white border border-gray-200 top-full rounded-2xl shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark">
                    <button
                        class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                        <?php esc_html_e('View Visitor Details', 'vms'); ?>
                    </button>
                    <button
                        class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                        <?php esc_html_e('Adjust Visit Targets', 'vms'); ?>
                    </button>
                    <button
                        class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                        <?php esc_html_e('Export Visit Report', 'vms'); ?>
                    </button>
                </div>
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