<?php
/**
 * Template part for displaying guests table
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visitor_Management_System
 */
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

global $wpdb;
$guests_table = $wpdb->prefix . 'vms_guests'; 
$guest_visits_table = $wpdb->prefix . 'vms_guest_visits'; 
?>

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="max-w-full overflow-x-auto" id="guests-table"
        data-guest-details-url="<?php echo esc_url(site_url('/guest-details')); ?>">
        <table class="min-w-full">
            <!-- table header start -->
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( '#', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'First Name', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'Last Name', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'Status', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'ID Number', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'Host Member', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'Visit Date', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
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
                    // Initialize counter
                    $counter = 1;

                    // Base query - join guests with guest_visits and users tables
                    $query = "SELECT g.*, v.id as visit_id, v.visit_date, v.sign_in_time, v.sign_out_time, 
                                    u.display_name as host_name 
                            FROM {$guests_table} g 
                            LEFT JOIN {$guest_visits_table} v ON g.id = v.guest_id
                            LEFT JOIN {$wpdb->users} u ON g.host_member_id = u.ID";
                    // Check if search form submitted
                    if (isset($_GET['search_users']) && !empty($_GET['user_search'])) {
                        $search_term = sanitize_text_field($_GET['user_search']);
                        $query .= $wpdb->prepare(" WHERE (g.first_name LIKE %s 
                                                OR g.last_name LIKE %s                                               
                                                OR g.id_number LIKE %s
                                                OR g.email LIKE %s
                                                OR g.phone_number LIKE %s
                                                OR u.display_name LIKE %s)",
                                                '%' . $wpdb->esc_like($search_term) . '%',
                                                '%' . $wpdb->esc_like($search_term) . '%',                                              
                                                '%' . $wpdb->esc_like($search_term) . '%',
                                                '%' . $wpdb->esc_like($search_term) . '%',
                                                '%' . $wpdb->esc_like($search_term) . '%',
                                                '%' . $wpdb->esc_like($search_term) . '%');
                    }

                    // Add ordering by visit date (most recent first)
                    $query .= " ORDER BY v.visit_date ASC, g.id DESC";

                    // Get guests from custom tables
                    $guests = $wpdb->get_results($query);
                    
                    $status_classes = [
                        'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                        'unapproved' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                        'suspended' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                        'banned' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                    ];                

                    if (!empty($guests)) {
                        foreach ($guests as $guest) {
                            $visit_date = !empty($guest->visit_date) ? date('M j, Y', strtotime($guest->visit_date)) : 'N/A';
                            $sign_in_time = !empty($guest->sign_in_time) ? date('g:i a', strtotime($guest->sign_in_time)) : null;
                            $sign_out_time = !empty($guest->sign_out_time) ? date('g:i a', strtotime($guest->sign_out_time)) : null;
                            $status = isset($guest->status) ? $guest->status : 'approved';
                            ?>
                <tr data-guest-id="<?php echo esc_attr($guest->id); ?>"
                    data-visit-id="<?php echo esc_attr($guest->visit_id); ?>">
                    <td class="px-5 py-4 sm:px-6">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                            <?php echo $counter++; ?>
                        </p>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-800 text-theme-sm dark:text-white/90">
                                <?php echo esc_html($guest->first_name); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-800 text-theme-sm dark:text-white/90">
                                <?php echo esc_html($guest->last_name); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full <?php echo $status_classes[$status]; ?>">
                                <?php echo ucfirst($status); ?>
                            </span>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php echo esc_html($guest->id_number); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php echo $guest->host_name ? esc_html($guest->host_name) : 'N/A'; ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php echo esc_html($visit_date); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center gap-2">
                            <button id="edit-guest-button-<?php echo $guest->id; ?>"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700"
                                data-guest-id="<?php echo $guest->id; ?>"
                                data-visit-id="<?php echo $guest->visit_id; ?>">
                                <?php esc_html_e( 'Edit', 'vms' ); ?>
                            </button>
                            <?php
                            // Get current date in WordPress timezone (EAT)
                            $current_date = current_time('Y-m-d');

                            // Validate guest data
                            if (!isset($guest->visit_date) || !isset($guest->status)) {
                                error_log("Guest table error: Missing visit_date or status for guest ID {$guest->id}");
                                $is_button_disabled = true; // Disable buttons if data is missing
                            } else {
                                // Normalize visit_date to YYYY-MM-DD
                                $normalized_visit_date = substr($guest->visit_date, 0, 10); // Extract YYYY-MM-DD from YYYY-MM-DD HH:MM:SS
                                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $normalized_visit_date)) {
                                    error_log("Guest table error: Invalid visit_date format for guest ID {$guest->id}: {$guest->visit_date}");
                                    $is_button_disabled = true;
                                } else {
                                    // Disable buttons if current date is before visit_date or status is not approved
                                    $is_button_disabled = $current_date < $normalized_visit_date || $guest->status !== 'approved';                                    
                                }
                            }

                            // Common button classes
                            $base_button_classes = 'inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg whitespace-nowrap shadow-theme-xs';
                            $disabled_classes = 'opacity-50 cursor-not-allowed';
                            ?>

                            <?php if (empty($guest->sign_in_time)): ?>
                            <button id="sign-in-button-<?php echo esc_attr($guest->id); ?>"
                                class="<?php echo esc_attr($base_button_classes . ' bg-blue-500 ' . ($is_button_disabled ? $disabled_classes : 'cursor-pointer hover:bg-blue-600')); ?>"
                                data-visit-id="<?php echo esc_attr($guest->visit_id); ?>"
                                <?php echo $is_button_disabled ? 'disabled' : ''; ?>>
                                <?php esc_html_e('Sign In', 'vms'); ?>
                            </button>
                            <?php elseif (empty($guest->sign_out_time)): ?>
                            <button id="sign-out-button-<?php echo esc_attr($guest->id); ?>"
                                class="<?php echo esc_attr($base_button_classes . ' bg-purple-500 ' . ($is_button_disabled ? $disabled_classes : 'cursor-pointer hover:bg-purple-600')); ?>"
                                data-visit-id="<?php echo esc_attr($guest->visit_id); ?>"
                                <?php echo $is_button_disabled ? 'disabled' : ''; ?>>
                                <?php esc_html_e('Sign Out', 'vms'); ?>
                            </button>
                            <?php else: ?>
                            <div class="flex flex-col items-center justify-center w-full text-xs">
                                <span
                                    class="text-green-600 dark:text-green-400"><?php echo esc_html($sign_in_time); ?></span>
                                <span
                                    class="text-red-600 dark:text-red-400"><?php echo esc_html($sign_out_time); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php
        }
    } else {
        echo '<tr id="no-guests-row"><td colspan="11" class="px-4 py-4 text-center text-gray-600 dark:text-white">No guests found.</td></tr>';
    }
    ?>
            </tbody>
        </table>
    </div>
</div>