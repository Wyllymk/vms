<?php
/**
 * Template part for displaying metrics
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visitor_Management_System
 */
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>
<!-- Metric Group Two -->
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4">
    <!-- Guests Start -->
    <?php
    // Get guest statistics
    function get_guest_statistics() {
        global $wpdb;
        
        $guests_table  = $wpdb->prefix . 'vms_guests';
        
        // Get total guests count
        $total_guests = $wpdb->get_var("SELECT COUNT(*) FROM $guests_table WHERE guest_status = 'active'");
        
        // Get current month guests count
        $current_month = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $guests_table 
            WHERE guest_status = 'active' 
            AND MONTH(created_at) = MONTH(CURRENT_DATE()) 
            AND YEAR(created_at) = YEAR(CURRENT_DATE())"
        ));
        
        // Get previous month guests count
        $previous_month = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $guests_table 
            WHERE guest_status = 'active' 
            AND MONTH(created_at) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) 
            AND YEAR(created_at) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))"
        ));
        
        // Calculate percentage change
        $percentage_change = 0;
        if ($previous_month > 0) {
            $percentage_change = (($current_month - $previous_month) / $previous_month) * 100;
        } elseif ($current_month > 0) {
            $percentage_change = 100; // First month with guests
        }
        
        return [
            'total_guests' => $total_guests,
            'current_month' => $current_month,
            'previous_month' => $previous_month,
            'percentage_change' => $percentage_change
        ];
    }
    // Get the statistics
    $stats = get_guest_statistics();
    ?>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-theme-sm text-gray-500 dark:text-gray-400">
            <?php esc_html_e('Total Guests', 'vms'); ?>
        </p>

        <div class="mt-3 flex items-end justify-between">
            <div>
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                    <?php echo number_format($stats['total_guests']); ?>
                </h4>
            </div>

            <div class="flex items-center gap-1">
                <?php if ($stats['percentage_change'] >= 0): ?>
                <span
                    class="flex items-center gap-1 rounded-full bg-success-50 px-2 py-0.5 text-theme-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                    +<?php echo number_format($stats['percentage_change'], 0); ?>%
                </span>
                <?php else: ?>
                <span
                    class="flex items-center gap-1 rounded-full bg-error-50 px-2 py-0.5 text-theme-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">
                    <?php echo number_format($stats['percentage_change'], 0); ?>%
                </span>
                <?php endif; ?>

                <span class="text-theme-xs text-gray-500 dark:text-gray-400">
                    <?php esc_html_e('Vs last month', 'vms'); ?>
                </span>
            </div>
        </div>
    </div>
    <!-- Guests End -->

    <!-- Reciprocating Members Start -->
    <?php
    // Get reciprocating member statistics
    function get_reciprocating_member_statistics() {
        global $wpdb;
        
        $recip_table = $wpdb->prefix . 'vms_reciprocating_members';
        
        // Get total reciprocating members count
        $total_members = $wpdb->get_var("SELECT COUNT(*) FROM $recip_table WHERE member_status = 'active'");
        
        // Get current month members count
        $current_month = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $recip_table 
            WHERE member_status = 'active' 
            AND MONTH(created_at) = MONTH(CURRENT_DATE()) 
            AND YEAR(created_at) = YEAR(CURRENT_DATE())"
        ));
        
        // Get previous month members count
        $previous_month = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $recip_table 
            WHERE member_status = 'active' 
            AND MONTH(created_at) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) 
            AND YEAR(created_at) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))"
        ));
        
        // Calculate percentage change
        $percentage_change = 0;
        if ($previous_month > 0) {
            $percentage_change = (($current_month - $previous_month) / $previous_month) * 100;
        } elseif ($current_month > 0) {
            $percentage_change = 100; // First month with members
        }
        
        return [
            'total_members' => $total_members,
            'current_month' => $current_month,
            'previous_month' => $previous_month,
            'percentage_change' => $percentage_change
        ];
    }
    // Get the statistics
    $member_stats = get_reciprocating_member_statistics();
    ?>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-theme-sm text-gray-500 dark:text-gray-400">
            <?php esc_html_e('Reciprocating Members', 'vms'); ?>
        </p>

        <div class="mt-3 flex items-end justify-between">
            <div>
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                    <?php echo number_format($member_stats['total_members']); ?>
                </h4>
            </div>

            <div class="flex items-center gap-1">
                <?php if ($member_stats['percentage_change'] >= 0): ?>
                <span
                    class="flex items-center gap-1 rounded-full bg-success-50 px-2 py-0.5 text-theme-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                    +<?php echo number_format($member_stats['percentage_change'], 0); ?>%
                </span>
                <?php else: ?>
                <span
                    class="flex items-center gap-1 rounded-full bg-error-50 px-2 py-0.5 text-theme-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">
                    <?php echo number_format($member_stats['percentage_change'], 0); ?>%
                </span>
                <?php endif; ?>

                <span class="text-theme-xs text-gray-500 dark:text-gray-400">
                    <?php esc_html_e('Vs last month', 'vms'); ?>
                </span>
            </div>
        </div>
    </div>
    <!-- Reciprocating Members End -->

    <!-- Members Start -->
    <?php
    // Get member statistics (users with member role)
    function get_member_statistics() {
        // Get all users with 'member' or 'chairman' role
        $member_users = get_users([
            'role__in' => ['member', 'chairman'],
        ]);
        $total_members = count($member_users);
        
        // Get current date info
        $current_month  = date('n');
        $current_year   = date('Y');
        $previous_month = $current_month == 1 ? 12 : $current_month - 1;
        $previous_year  = $current_month == 1 ? $current_year - 1 : $current_year;
        
        // Initialize counts
        $current_month_count  = 0;
        $previous_month_count = 0;
        
        foreach ($member_users as $user) {
            $registered_date = strtotime($user->user_registered);
            $reg_month       = date('n', $registered_date);
            $reg_year        = date('Y', $registered_date);
            
            if ($reg_month == $current_month && $reg_year == $current_year) {
                $current_month_count++;
            }
            
            if ($reg_month == $previous_month && $reg_year == $previous_year) {
                $previous_month_count++;
            }
        }
        
        // Calculate percentage change
        $percentage_change = 0;
        if ($previous_month_count > 0) {
            $percentage_change = (($current_month_count - $previous_month_count) / $previous_month_count) * 100;
        } elseif ($current_month_count > 0) {
            $percentage_change = 100; // First month with members
        }
        
        return [
            'total_members'     => $total_members,
            'current_month'     => $current_month_count,
            'previous_month'    => $previous_month_count,
            'percentage_change' => $percentage_change,
        ];
    }

    // Get the statistics
    $member_stats = get_member_statistics();
    ?>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-theme-sm text-gray-500 dark:text-gray-400">
            <?php esc_html_e('Members', 'vms'); ?>
        </p>

        <div class="mt-3 flex items-end justify-between">
            <div>
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                    <?php echo number_format($member_stats['total_members']); ?>
                </h4>
            </div>

            <div class="flex items-center gap-1">
                <?php if ($member_stats['percentage_change'] >= 0): ?>
                <span
                    class="flex items-center gap-1 rounded-full bg-success-50 px-2 py-0.5 text-theme-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                    +<?php echo number_format($member_stats['percentage_change'], 1); ?>%
                </span>
                <?php else: ?>
                <span
                    class="flex items-center gap-1 rounded-full bg-error-50 px-2 py-0.5 text-theme-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">
                    <?php echo number_format($member_stats['percentage_change'], 1); ?>%
                </span>
                <?php endif; ?>

                <span class="text-theme-xs text-gray-500 dark:text-gray-400">
                    <?php esc_html_e('Vs last month', 'vms'); ?>
                </span>
            </div>
        </div>
    </div>
    <!-- Members End -->

    <!-- Metric Item Start -->
    <?php 
    function vms_get_monthly_guest_visits($year, $month) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'vms_guest_visits';

        $start_date = sprintf('%04d-%02d-01', $year, $month);
        $end_date   = date('Y-m-t', strtotime($start_date));

        $count = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name 
                WHERE visit_date BETWEEN %s AND %s",
                $start_date,
                $end_date
            )
        );

        return (int) $count;
    }

    function vms_get_guest_visit_stats() {
        $year  = date('Y');
        $month = date('n');

        $this_month  = vms_get_monthly_guest_visits($year, $month);

        // last month
        $last_month = ($month == 1) ? 12 : $month - 1;
        $last_year  = ($month == 1) ? $year - 1 : $year;

        $prev_month = vms_get_monthly_guest_visits($last_year, $last_month);

        $percentage = 0;
        if ($prev_month > 0) {
            $percentage = (($this_month - $prev_month) / $prev_month) * 100;
        }

        return [
            'this_month' => $this_month,
            'last_month' => $prev_month,
            'percentage' => round($percentage, 1),
        ];
    }


    $stats = vms_get_guest_visit_stats(); 
    ?>

    <!-- Metric Item Start -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-theme-sm text-gray-500 dark:text-gray-400">Guest Visits (This Month)</p>

        <div class="mt-3 flex items-end justify-between">
            <div>
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                    <?php echo esc_html($stats['this_month']); ?>
                </h4>
            </div>

            <div class="flex items-center gap-1">
                <span class="flex items-center gap-1 rounded-full 
                <?php echo $stats['percentage'] >= 0 
                    ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500' 
                    : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500'; ?>
                px-2 py-0.5 text-theme-xs font-medium">
                    <?php echo ($stats['percentage'] >= 0 ? '+' : '') . esc_html($stats['percentage']); ?>%
                </span>

                <span class="text-theme-xs text-gray-500 dark:text-gray-400">
                    Vs last month (<?php echo esc_html($stats['last_month']); ?>)
                </span>
            </div>
        </div>
    </div>
    <!-- Metric Item End -->

</div>
<!-- Metric Group Two -->