<?php
/**
 * Template part for displaying guests table with pagination and role-based filtering
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visitor_Management_System
 */
use WyllyMk\VMS\VMS_Config;

defined( 'ABSPATH' ) || exit;

global $wpdb;
$guests_table       = VMS_Config::get_table_name(VMS_Config::A_GUESTS_TABLE);
$guest_visits_table = VMS_Config::get_table_name(VMS_Config::A_GUEST_VISITS_TABLE);

// Get current user and their role
$current_user = wp_get_current_user();
$current_user_id = $current_user->ID;
$user_roles = $current_user->roles;

// Determine role-based filtering
$role_filter = '';
$role_filter_count = '';
$is_member_or_chairman = in_array('member', $user_roles) || in_array('chairman', $user_roles) || in_array('general_manager', $user_roles);
$is_gate = in_array('gate', $user_roles);

if ($is_member_or_chairman) {
    // Show visits where current user is the host OR where no host is assigned
    $role_filter = $wpdb->prepare(
        " AND (v.host_member_id = %d OR v.host_member_id IS NULL OR v.host_member_id = 0)",
        $current_user_id
    );
    $role_filter_count = $wpdb->prepare(
        " AND (v.host_member_id = %d OR v.host_member_id IS NULL OR v.host_member_id = 0)",
        $current_user_id
    );
} elseif ($is_gate) {
    // Show only today's visits
    $today = current_time('Y-m-d');
    $role_filter = $wpdb->prepare(" AND DATE(v.visit_date) = %s", $today);
    $role_filter_count = $wpdb->prepare(" AND DATE(v.visit_date) = %s", $today);
}
// For other roles (admin, etc.), no additional filter is applied (show all)

// Pagination
$guests_per_page = isset($_GET['per_page']) ? max(1, intval($_GET['per_page'])) : 25;
$current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$offset = ($current_page - 1) * $guests_per_page;

// Search functionality
$search_term = '';
$where_clause = '';
$where_visits_clause = '';

if (isset($_GET['search_users']) && !empty($_GET['user_search'])) {
    $search_term = sanitize_text_field($_GET['user_search']);
    $like = '%' . $wpdb->esc_like($search_term) . '%';
    
    // For guests table search
    $where_clause = $wpdb->prepare(
        " WHERE (g.first_name LIKE %s OR g.last_name LIKE %s OR g.id_number LIKE %s OR g.email LIKE %s OR g.phone_number LIKE %s)",
        $like, $like, $like, $like, $like
    );
    
    // For visits count search - need to join with guests table
    $where_visits_clause = $wpdb->prepare(
        " WHERE (g.first_name LIKE %s OR g.last_name LIKE %s OR g.id_number LIKE %s OR g.email LIKE %s OR g.phone_number LIKE %s)",
        $like, $like, $like, $like, $like
    );
}

// Build the complete WHERE clause for role filtering
$complete_where_clause = $where_visits_clause;
if (!empty($role_filter_count)) {
    if (!empty($complete_where_clause)) {
        $complete_where_clause .= $role_filter_count;
    } else {
        $complete_where_clause = " WHERE 1=1" . $role_filter_count;
    }
}

// Count total guest visits with role filtering
$count_visits_query = "SELECT COUNT(DISTINCT v.id) 
                      FROM {$guest_visits_table} v 
                      LEFT JOIN {$guests_table} g ON v.guest_id = g.id" . $complete_where_clause;

$total_visits = $wpdb->get_var($count_visits_query);
$total_pages = ceil($total_visits / $guests_per_page);

// Build the complete WHERE clause for main query
$complete_main_where = $where_visits_clause;
if (!empty($role_filter)) {
    if (!empty($complete_main_where)) {
        $complete_main_where .= $role_filter;
    } else {
        $complete_main_where = " WHERE 1=1" . $role_filter;
    }
}

// Fetch guest visits with guest details and role filtering
$query = "
    SELECT 
        g.*, 
        v.id AS visit_id, 
        v.visit_date, 
        v.sign_in_time, 
        v.sign_out_time, 
        v.status AS visit_status
    FROM {$guest_visits_table} v
    LEFT JOIN {$guests_table} g ON v.guest_id = g.id
    {$complete_main_where}
    ORDER BY v.visit_date DESC, v.id DESC
    LIMIT {$guests_per_page} OFFSET {$offset}
";


$guests = $wpdb->get_results($query);

$status_classes = [
    'approved'   => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
    'unapproved' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
    'suspended'  => 'bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500',
    'banned'     => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
    'cancelled'  => 'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80'
];
?>

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
    x-data="{ perPage: localStorage.getItem('guests_per_page') || '25' }"
    x-init="$watch('perPage', value => localStorage.setItem('guests_per_page', value))">

    <!-- Per Page Controls -->
    <div
        class="mb-4 flex flex-col gap-2 px-4 py-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-800">
        <div class="flex items-center gap-3">
            <span class="text-gray-500 dark:text-gray-400">Show</span>
            <div class="relative z-20 bg-transparent">
                <select x-model="perPage"
                    @change="window.location.href = updateUrlParameter(window.location.href, 'per_page', $event.target.value)"
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-9 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none py-2 pr-8 pl-3 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    <option value="25" <?php selected($guests_per_page, 25); ?>>25</option>
                    <option value="50" <?php selected($guests_per_page, 50); ?>>50</option>
                    <option value="100" <?php selected($guests_per_page, 100); ?>>100</option>
                </select>
                <span class="absolute top-1/2 right-2 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                    <svg class="stroke-current" width="16" height="16" viewBox="0 0 16 16" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.8335 5.9165L8.00016 10.0832L12.1668 5.9165" stroke="" stroke-width="1.2"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
            </div>
            <span class="text-gray-500 dark:text-gray-400">entries</span>
        </div>
        <!-- Show total entries info -->
        <div class="text-sm text-gray-500 dark:text-gray-400">
            <?php
            $start = $total_visits > 0 ? $offset + 1 : 0;
            $end = min($offset + $guests_per_page, $total_visits);
            
            if (!empty($search_term)) {                
                printf(
                    /* translators: %1$d: start entry number, %2$d: end entry number, %3$d: total filtered entries */
                    esc_html__('Showing %1$d to %2$d of %3$d entries (filtered from total)', 'vms'),
                    $start,
                    $end,
                    $total_visits
                );
            } else {                
                printf(
                    /* translators: %1$d: start entry number, %2$d: end entry number, %3$d: total entries */
                    esc_html__('Showing %1$d to %2$d of %3$d entries', 'vms'),
                    $start,
                    $end,
                    $total_visits
                );
            }
            ?>
        </div>
    </div>

    <div class="max-w-full overflow-x-auto" id="guests-table"
        data-guest-details-url="<?php echo esc_url(home_url('/guest-details')); ?>">
        <table class="min-w-full">
            <!-- table header start -->
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( '#', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'First Name', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'Last Name', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'Status', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'ID Number', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'Visit Date', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'Actions', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                </tr>
            </thead>
            <!-- table header end -->
            <!-- table body start -->
            <tbody id="guests-table-body" class="divide-y divide-gray-100 dark:divide-gray-800">
                <?php
                $counter = $offset + 1;
                if (!empty($guests)) :
                    foreach ($guests as $guest) :
                        $visit_date = !empty($guest->visit_date) ? date('M j, Y', strtotime($guest->visit_date)) : 'N/A';
                        $sign_in_time = !empty($guest->sign_in_time) ? date('g:i a', strtotime($guest->sign_in_time)) : null;
                        $sign_out_time = !empty($guest->sign_out_time) ? date('g:i a', strtotime($guest->sign_out_time)) : null;

                        // Determine visit status
                        $current_date = current_time('Y-m-d');
                        $normalized_visit_date = substr($guest->visit_date ?? '', 0, 10);
                        $is_button_disabled = false;
                        $visit_status = strtolower($guest->status ?? 'approved'); // fallback to approved                        

                        if ($normalized_visit_date && $normalized_visit_date > $current_date) {
                            $visit_status = 'scheduled';
                        } elseif ($normalized_visit_date && $normalized_visit_date === $current_date) {
                            $visit_status = !empty($guest->sign_in_time) ? (!empty($guest->sign_out_time) ? 'completed' : 'signout') : 'signin';
                        } elseif ($normalized_visit_date && $normalized_visit_date < $current_date) {
                            $visit_status = !empty($guest->sign_in_time) ? (!empty($guest->sign_out_time) ? 'completed' : 'signout') : 'missed';
                        }

                        $current_role = $current_user->roles;
                        $is_member_or_chairman = in_array('member', $current_role) || in_array('chairman', $current_role);
                ?>
                <tr data-guest-id="<?php echo esc_attr($guest->id); ?>"
                    data-visit-id="<?php echo esc_attr($guest->visit_id); ?>">
                    <td class="px-3 py-4 sm:px-6">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                            <?php echo $counter++; ?>
                        </p>
                    </td>
                    <td class="px-3 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-800 text-theme-sm dark:text-white/90">
                                <?php echo esc_html($guest->first_name); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-3 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-800 text-theme-sm dark:text-white/90">
                                <?php echo esc_html($guest->last_name); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-3 py-4 sm:px-6">
                        <div class="flex items-center">
                            <span
                                class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium capitalize <?php echo $status_classes[$guest->visit_status] ?? $status_classes['approved']; ?>">
                                <?php echo esc_html($guest->visit_status); ?>
                            </span>
                        </div>
                    </td>
                    <td class="px-3 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="id_number text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php echo !empty($guest->id_number) ? esc_html($guest->id_number) : 'N/A'; ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-3 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php echo esc_html($visit_date); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-3 py-4 sm:px-6">
                        <div class="flex items-center gap-2">
                            <button id="edit-accommodation-guest-button-<?php echo $guest->id; ?>"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700"
                                data-guest-id="<?php echo $guest->id; ?>"
                                data-visit-id="<?php echo $guest->visit_id; ?>">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                <?php esc_html_e( 'Edit', 'vms' ); ?>
                            </button>

                            <?php

                            if ($guest->visit_status === 'cancelled') : ?>
                            <span
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg dark:bg-white/5 dark:text-white/80">
                                <?php esc_html_e('Cancelled', 'vms'); ?>
                            </span>

                            <?php elseif ($guest->visit_status === 'unapproved') : ?>
                            <span
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-warning-600 bg-warning-50 rounded-lg dark:bg-warning-500/15 dark:text-orange-500">
                                <?php esc_html_e('Unapproved', 'vms'); ?>
                            </span>

                            <?php elseif ($guest->visit_status === 'suspended') : ?>
                            <span
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-light-500 bg-blue-light-50 rounded-lg dark:bg-blue-light-500/15 dark:text-blue-light-500">
                                <?php esc_html_e('Suspended', 'vms'); ?>
                            </span>

                            <?php elseif ($guest->visit_status === 'banned') : ?>
                            <span
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-error-600 bg-error-50 rounded-lg dark:bg-error-500/15 dark:text-error-500">
                                <?php esc_html_e('Banned', 'vms'); ?>
                            </span>

                            <?php elseif ($guest->visit_status === 'approved') : ?>
                            <?php if ($visit_status === 'missed') : ?>
                            <span
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-warning-600 bg-warning-50 rounded-lg dark:bg-warning-500/15 dark:text-orange-500"><?php esc_html_e('Missed', 'vms'); ?></span>

                            <?php elseif ($visit_status === 'scheduled') : ?>
                            <span
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-light-500 bg-blue-light-50 rounded-lg dark:bg-blue-light-500/15 dark:text-blue-light-500"><?php esc_html_e('Scheduled', 'vms'); ?></span>

                            <?php elseif ($visit_status === 'signin') : ?>
                            <button id="sign-in-accommodation-button-<?php echo esc_attr($guest->id); ?>"
                                class="whitespace-nowrap inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-brand-500 rounded-lg cursor-pointer hover:bg-brand-600 <?php echo $is_member_or_chairman ? 'opacity-50 !cursor-not-allowed' : ''; ?>"
                                data-visit-id="<?php echo esc_attr($guest->visit_id); ?>"
                                <?php echo $is_member_or_chairman ? 'disabled' : ''; ?>>
                                <?php esc_html_e('Sign In', 'vms'); ?>
                            </button>

                            <?php elseif ($visit_status === 'signout') : ?>
                            <button id="sign-out-accommodation-button-<?php echo esc_attr($guest->id); ?>"
                                class="whitespace-nowrap inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-purple-500 rounded-lg cursor-pointer hover:bg-purple-600 <?php echo $is_member_or_chairman ? 'opacity-50 !cursor-not-allowed' : ''; ?>"
                                data-visit-id="<?php echo esc_attr($guest->visit_id); ?>"
                                <?php echo $is_member_or_chairman ? 'disabled' : ''; ?>>
                                <?php esc_html_e('Sign Out', 'vms'); ?>
                            </button>

                            <?php elseif ($visit_status === 'completed') : ?>
                            <div class="flex flex-col items-center justify-center text-xs px-4">
                                <span
                                    class="text-green-600 dark:text-green-400"><?php echo esc_html($sign_in_time); ?></span>
                                <span
                                    class="text-red-600 dark:text-red-400"><?php echo esc_html($sign_out_time); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </td>

                </tr>
                <?php
                    endforeach;
                else:
                    echo '<tr id="no-guests-row"><td colspan="8" class="px-4 py-4 text-center text-gray-600 dark:text-white">No guests found.</td></tr>';
                endif;
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Section -->
    <?php if ($total_pages > 1): ?>
    <div
        class="flex items-center justify-between gap-8 px-6 py-4 sm:justify-normal border-t border-gray-200 dark:border-gray-800">
        <!-- Previous Button -->
        <?php if ($current_page > 1): ?>
        <a href="<?php echo esc_url(add_query_arg('paged', $current_page - 1)); ?>"
            class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5 sm:py-2.5">
            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M2.58203 9.99868C2.58174 10.1909 2.6549 10.3833 2.80152 10.53L7.79818 15.5301C8.09097 15.8231 8.56584 15.8233 8.85883 15.5305C9.15183 15.2377 9.152 14.7629 8.85921 14.4699L5.13911 10.7472L16.6665 10.7472C17.0807 10.7472 17.4165 10.4114 17.4165 9.99715C17.4165 9.58294 17.0807 9.24715 16.6665 9.24715L5.14456 9.24715L8.85919 5.53016C9.15199 5.23717 9.15184 4.7623 8.85885 4.4695C8.56587 4.1767 8.09099 4.17685 7.79819 4.46984L2.84069 9.43049C2.68224 9.568 2.58203 9.77087 2.58203 9.99715C2.58203 9.99766 2.58203 9.99817 2.58203 9.99868Z">
                </path>
            </svg>
            <span class="hidden sm:inline"><?php esc_html_e('Previous', 'vms'); ?></span>
        </a>
        <?php else: ?>
        <button disabled
            class="flex items-center gap-2 rounded-lg border border-gray-300 bg-gray-100 px-2 py-2 text-sm font-medium text-gray-400 shadow-theme-xs dark:border-gray-600 dark:bg-gray-700 dark:text-gray-500 sm:px-3.5 sm:py-2.5">
            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M2.58203 9.99868C2.58174 10.1909 2.6549 10.3833 2.80152 10.53L7.79818 15.5301C8.09097 15.8231 8.56584 15.8233 8.85883 15.5305C9.15183 15.2377 9.152 14.7629 8.85921 14.4699L5.13911 10.7472L16.6665 10.7472C17.0807 10.7472 17.4165 10.4114 17.4165 9.99715C17.4165 9.58294 17.0807 9.24715 16.6665 9.24715L5.14456 9.24715L8.85919 5.53016C9.15199 5.23717 9.15184 4.7623 8.85885 4.4695C8.56587 4.1767 8.09099 4.17685 7.79819 4.46984L2.84069 9.43049C2.68224 9.568 2.58203 9.77087 2.58203 9.99715C2.58203 9.99766 2.58203 9.99817 2.58203 9.99868Z">
                </path>
            </svg>
            <span class="hidden sm:inline"><?php esc_html_e('Previous', 'vms'); ?></span>
        </button>
        <?php endif; ?>

        <!-- Mobile page indicator -->
        <span class="block text-sm font-medium text-gray-700 dark:text-gray-400 sm:hidden">
            <?php 
            /* translators: %1$d: current page number, %2$d: total pages */ 
            printf(esc_html__('Page %1$d of %2$d', 'vms'), $current_page, $total_pages); 
            ?>
        </span>

        <!-- Desktop page numbers -->
        <ul class="hidden items-center gap-0.5 sm:flex">
            <?php
            // Calculate page range to display
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);

            // Show first page if not in range
            if ($start_page > 1) {
                echo '<li><a href="' . esc_url(add_query_arg('paged', 1)) . '" class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 hover:bg-brand-500 hover:text-white dark:text-gray-400 dark:hover:text-white">1</a></li>';
                if ($start_page > 2) {
                    echo '<li><span class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 dark:text-gray-400">...</span></li>';
                }
            }

            // Display page numbers in range
            for ($i = $start_page; $i <= $end_page; $i++) {
                if ($i == $current_page) {
                    echo '<li><span class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-500 text-sm font-medium text-white">' . $i . '</span></li>';
                } else {
                    echo '<li><a href="' . esc_url(add_query_arg('paged', $i)) . '" class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 hover:bg-brand-500 hover:text-white dark:text-gray-400 dark:hover:text-white">' . $i . '</a></li>';
                }
            }

            // Show last page if not in range
            if ($end_page < $total_pages) {
                if ($end_page < $total_pages - 1) {
                    echo '<li><span class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 dark:text-gray-400">...</span></li>';
                }
                echo '<li><a href="' . esc_url(add_query_arg('paged', $total_pages)) . '" class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 hover:bg-brand-500 hover:text-white dark:text-gray-400 dark:hover:text-white">' . $total_pages . '</a></li>';
            }
            ?>
        </ul>

        <!-- Next Button -->
        <?php if ($current_page < $total_pages): ?>
        <a href="<?php echo esc_url(add_query_arg('paged', $current_page + 1)); ?>"
            class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5 sm:py-2.5">
            <span class="hidden sm:inline"><?php esc_html_e('Next', 'vms'); ?></span>
            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M17.4165 9.9986C17.4168 10.1909 17.3437 10.3832 17.197 10.53L12.2004 15.5301C11.9076 15.8231 11.4327 15.8233 11.1397 15.5305C10.8467 15.2377 10.8465 14.7629 11.1393 14.4699L14.8594 10.7472L3.33203 10.7472C2.91782 10.7472 2.58203 10.4114 2.58203 9.99715C2.58203 9.58294 2.91782 9.24715 3.33203 9.24715L14.854 9.24715L11.1393 5.53016C10.8465 5.23717 10.8467 4.7623 11.1397 4.4695C11.4327 4.1767 11.9075 4.17685 12.2003 4.46984L17.1578 9.43049C17.3163 9.568 17.4165 9.77087 17.4165 9.99715C17.4165 9.99763 17.4165 9.99812 17.4165 9.9986Z">
                </path>
            </svg>
        </a>
        <?php else: ?>
        <button disabled
            class="flex items-center gap-2 rounded-lg border border-gray-300 bg-gray-100 px-2 py-2 text-sm font-medium text-gray-400 shadow-theme-xs dark:border-gray-600 dark:bg-gray-700 dark:text-gray-500 sm:px-3.5 sm:py-2.5">
            <span class="hidden sm:inline"><?php esc_html_e('Next', 'vms'); ?></span>
            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M17.4165 9.9986C17.4168 10.1909 17.3437 10.3832 17.197 10.53L12.2004 15.5301C11.9076 15.8231 11.4327 15.8233 11.1397 15.5305C10.8467 15.2377 10.8465 14.7629 11.1393 14.4699L14.8594 10.7472L3.33203 10.7472C2.91782 10.7472 2.58203 10.4114 2.58203 9.99715C2.58203 9.58294 2.91782 9.24715 3.33203 9.24715L14.854 9.24715L11.1393 5.53016C10.8465 5.23717 10.8467 4.7623 11.1397 4.4695C11.4327 4.1767 11.9075 4.17685 12.2003 4.46984L17.1578 9.43049C17.3163 9.568 17.4165 9.77087 17.4165 9.99715C17.4165 9.99763 17.4165 9.99812 17.4165 9.9986Z">
                </path>
            </svg>
        </button>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<script>
// Function to update URL parameters
function updateUrlParameter(url, param, paramVal) {
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";
    if (additionalURL) {
        tempArray = additionalURL.split("&");
        for (var i = 0; i < tempArray.length; i++) {
            if (tempArray[i].split('=')[0] != param) {
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }
    }

    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}

// Initialize per page selection from localStorage on page load
document.addEventListener('DOMContentLoaded', function() {
    const savedPerPage = localStorage.getItem('guests_per_page') || '25';
    const selectElement = document.querySelector('select[x-model="perPage"]');
    if (selectElement) {
        selectElement.value = savedPerPage;
        // Trigger Alpine.js to update
        selectElement.dispatchEvent(new Event('change'));
    }
});
</script>