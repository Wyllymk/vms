<?php
/**
 * The template for displaying the guest details page
 *
 * @package Visitor_Management_System
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

// Start the session if not already started
if (!session_id()) {
    session_start();
}

// Check if the current user is an Administrator or Manager or Advocate
if ( ! ( current_user_can( 'administrator' ) || current_user_can( 'general_manager' ) || current_user_can( 'chairman' ) || current_user_can( 'reception' ) || current_user_can( 'member' ) ) ) {
	// Redirect unauthorized users to the front page
	wp_redirect( home_url() );
	exit;
}

// WordPress table prefix
$guests_table       = $wpdb->prefix . 'vms_guests';
$guest_visits_table = $wpdb->prefix . 'vms_guest_visits';
$wp_users_table     = $wpdb->users;

// Get guest_id from URL
$guest_id = isset($_GET['guest_id']) ? absint($_GET['guest_id']) : 0;
if (!$guest_id) {
    wp_die('Invalid guest ID.');
}

// Get guest details
$guest = $wpdb->get_row(
    $wpdb->prepare("SELECT * FROM $guests_table WHERE id = %d", $guest_id)
);

if (!$guest) {
    wp_die('Guest not found.');
}

// Pagination parameters
$per_page = isset($_GET['per_page']) ? absint($_GET['per_page']) : 10;
if (!in_array($per_page, [5, 8, 10])) {
    $per_page = 10;
}

$current_page = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
$offset = ($current_page - 1) * $per_page;

// Sorting parameters
$sort_column = isset($_GET['sort_column']) ? sanitize_text_field($_GET['sort_column']) : 'visit_date';
$sort_direction = isset($_GET['sort_direction']) && $_GET['sort_direction'] === 'asc' ? 'asc' : 'desc';

// Allowed sort columns
$allowed_sort_columns = ['visit_date', 'sign_in_time', 'sign_out_time', 'host_member_name'];
if (!in_array($sort_column, $allowed_sort_columns)) {
    $sort_column = 'visit_date';
}

// Get current date for missed visit calculation
$current_date = current_time('Y-m-d');

// Get total count for pagination
$total_visits = $wpdb->get_var(
    $wpdb->prepare("SELECT COUNT(*) FROM $guest_visits_table WHERE guest_id = %d", $guest_id)
);

// Get visits with host member information
$visits_query = "
    SELECT 
        gv.*,
        CONCAT(g_host.first_name, ' ', g_host.last_name) as host_member_name
    FROM $guest_visits_table gv
    LEFT JOIN $guests_table g_host ON gv.host_member_id = g_host.id
    WHERE gv.guest_id = %d
    ORDER BY ";

// Handle sorting
switch ($sort_column) {
    case 'host_member_name':
        $visits_query .= "CONCAT(g_host.first_name, ' ', g_host.last_name) $sort_direction";
        break;
    case 'visit_date':
        $visits_query .= "gv.visit_date $sort_direction";
        break;
    case 'sign_in_time':
        $visits_query .= "gv.sign_in_time $sort_direction";
        break;
    case 'sign_out_time':
        $visits_query .= "gv.sign_out_time $sort_direction";
        break;
}

$visits_query .= " LIMIT %d OFFSET %d";

$visits = $wpdb->get_results(
    $wpdb->prepare($visits_query, $guest_id, $per_page, $offset)
);

// Calculate pagination values
$total_pages = ceil($total_visits / $per_page);
$start_entry = $total_visits > 0 ? ($current_page - 1) * $per_page + 1 : 0;
$end_entry = min($current_page * $per_page, $total_visits);

// Helper functions
function format_date($date_string) {
    if (!$date_string) return 'N/A';
    return date('M j, Y', strtotime($date_string));
}

function format_time($time_string) {
    if (!$time_string) return 'N/A';
    return date('g:i A', strtotime($time_string));
}

function calculate_duration($sign_in, $sign_out) {
    if (!$sign_in || !$sign_out) return 'N/A';
    
    $in_time = strtotime($sign_in);
    $out_time = strtotime($sign_out);
    $diff = $out_time - $in_time;
    
    $hours = floor($diff / 3600);
    $minutes = floor(($diff % 3600) / 60);
    
    return sprintf('%dh %dm', $hours, $minutes);
}

// Updated status functions to include "Missed" status
function get_visit_status($visit_date, $sign_in_time, $sign_out_time) {
    global $current_date;
    
    // If visit date is in the past and no sign in, it's missed
    if (empty($sign_in_time) && !empty($visit_date) && $visit_date < $current_date) {
        return 'missed';
    }
    
    // Otherwise, use the standard logic
    return !empty($sign_out_time) ? 'completed' : 'active';
}

function get_status_class($status) {
    switch ($status) {
        case 'completed':
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        case 'active':
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
        case 'missed':
            return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
        default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    }
}

function get_status_text($status) {
    switch ($status) {
        case 'completed':
            return 'Completed';
        case 'active':
            return 'Active';
        case 'missed':
            return 'Missed';
        default:
            return 'Unknown';
    }
}

function build_sort_url($column) {
    global $sort_column, $sort_direction, $per_page, $guest_id;
    
    $new_direction = ($sort_column === $column && $sort_direction === 'asc') ? 'desc' : 'asc';
    
    return add_query_arg([
        'guest_id' => $guest_id,
        'sort_column' => $column,
        'sort_direction' => $new_direction,
        'per_page' => $per_page,
        'paged' => 1
    ]);
}

function build_pagination_url($page) {
    global $sort_column, $sort_direction, $per_page, $guest_id;
    
    return add_query_arg([
        'guest_id' => $guest_id,
        'sort_column' => $sort_column,
        'sort_direction' => $sort_direction,
        'per_page' => $per_page,
        'paged' => $page
    ]);
}

function build_per_page_url($new_per_page) {
    global $sort_column, $sort_direction, $guest_id;
    
    return add_query_arg([
        'guest_id' => $guest_id,
        'sort_column' => $sort_column,
        'sort_direction' => $sort_direction,
        'per_page' => $new_per_page,
        'paged' => 1
    ]);
}

get_header();
?>

<section id="primary" x-data="{ page: 'guest-details'}">
    <main id="main">
        <!-- ===== Page Wrapper Start ===== -->
        <div class="flex h-screen overflow-hidden">
            <!-- ===== Sidebar Start ===== -->
            <?php get_template_part('template-parts/content/content', 'sidebar'); ?>
            <!-- ===== Sidebar End ===== -->

            <!-- ===== Content Area Start ===== -->
            <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
                <!-- Small Device Overlay Start -->
                <?php get_template_part('template-parts/content/content', 'overlay'); ?>
                <!-- Small Device Overlay End -->

                <!-- ===== Header Start ===== -->
                <?php get_template_part('template-parts/content/content', 'header'); ?>
                <!-- ===== Header End ===== -->

                <!-- ===== Main Content Start ===== -->
                <main>

                    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                        <!-- Breadcrumb Start -->
                        <div x-data="{ pageName: `Guest-details`}">
                            <?php get_template_part( 'template-parts/content/content', 'breadcrumb' ); ?>
                        </div>
                        <!-- Breadcrumb End -->
                        <div class="py-8 mx-auto">
                            <div class="flex justify-center">
                                <div class="w-full lg:w-5/6 xl:4/5 2xl:3/4">
                                    <!-- Personal Information -->
                                    <div x-data="{ open: localStorage.getItem('infoOpen') !== null ? localStorage.getItem('infoOpen') === 'true' : true }"
                                        class="rounded-2xl shadow-lg border border-gray-200 bg-white sm:p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                                        <!-- Header with click toggle functionality -->
                                        <div @click="open = !open; localStorage.setItem('infoOpen', open)"
                                            class="flex items-center justify-between px-6 py-4 cursor-pointer">
                                            <h3
                                                class="text-lg font-semibold font-oswald text-regal-blue dark:text-white">
                                                Personal Information
                                            </h3>
                                            <!-- SVG arrow icon -->
                                            <svg :class="{'rotate-180': open}"
                                                class="w-5 h-5 text-gray-500 transition-transform duration-300 transform dark:text-gray-300"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                        <!-- Collapsible content section -->
                                        <!-- In the Personal Information section -->
                                        <div x-show="open" x-transition
                                            class="p-6 border-t border-gray-200 dark:border-gray-700">
                                            <!-- Success Alert -->
                                            <?php if (!empty($guest_u_success)) : ?>
                                            <?php foreach ($guest_u_success as $message) : ?>
                                            <div class="flex items-center justify-between p-4 mb-4 text-white bg-green-500 border-l-4 border-green-700 rounded"
                                                role="alert">
                                                <div>
                                                    <strong>Success!</strong>
                                                    <p class="text-sm"><?php echo esc_html($message); ?></p>
                                                </div>
                                                <button type="button" class="float-right text-white hover:text-gray-300"
                                                    onclick="this.parentElement.style.display='none';">×</button>
                                            </div>
                                            <?php endforeach; ?>
                                            <?php endif; ?>

                                            <!-- Error Alert -->
                                            <?php if (!empty($guest_u_error)) : ?>
                                            <?php foreach ($guest_u_error as $message) : ?>
                                            <div class="flex items-center justify-between p-4 mb-4 text-white bg-red-500 border-l-4 border-red-700 rounded"
                                                role="alert">
                                                <div>
                                                    <strong>Error!</strong>
                                                    <p class="text-sm"><?php echo esc_html($message); ?></p>
                                                </div>
                                                <button type="button" class="float-right text-white hover:text-gray-300"
                                                    onclick="this.parentElement.style.display='none';">×</button>
                                            </div>
                                            <?php endforeach; ?>
                                            <?php endif; ?>

                                            <form id="guest-update-form" action="" method="post"
                                                enctype="multipart/form-data">
                                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                                    <!-- First Name -->
                                                    <div class="mb-4">
                                                        <label for="fname"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            First Name:
                                                        </label>
                                                        <input type="text" id="fname" name="first_name"
                                                            placeholder="First Name"
                                                            value="<?php echo esc_attr($guest->first_name ?? ''); ?>"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                                    </div>

                                                    <!-- Last Name -->
                                                    <div class="mb-4">
                                                        <label for="lname"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            Last Name:
                                                        </label>
                                                        <input type="text" id="lname" name="last_name"
                                                            placeholder="Last Name"
                                                            value="<?php echo esc_attr($guest->last_name ?? ''); ?>"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                                    </div>

                                                    <!-- Email -->
                                                    <div class="mb-4">
                                                        <label for="email"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            Email:
                                                        </label>
                                                        <div class="relative">
                                                            <span
                                                                class="absolute top-1/2 left-0 -translate-y-1/2 border-r border-gray-200 px-3.5 py-3 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                                                <svg width="20" height="20" viewBox="0 0 20 20"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M3.04175 7.06206V14.375C3.04175 14.6511 3.26561 14.875 3.54175 14.875H16.4584C16.7346 14.875 16.9584 14.6511 16.9584 14.375V7.06245L11.1443 11.1168C10.457 11.5961 9.54373 11.5961 8.85638 11.1168L3.04175 7.06206ZM16.9584 5.19262C16.9584 5.19341 16.9584 5.1942 16.9584 5.19498V5.20026C16.9572 5.22216 16.946 5.24239 16.9279 5.25501L10.2864 9.88638C10.1145 10.0062 9.8862 10.0062 9.71437 9.88638L3.07255 5.25485C3.05342 5.24151 3.04202 5.21967 3.04202 5.19636C3.042 5.15695 3.07394 5.125 3.11335 5.125H16.8871C16.9253 5.125 16.9564 5.15494 16.9584 5.19262ZM18.4584 5.21428V14.375C18.4584 15.4796 17.563 16.375 16.4584 16.375H3.54175C2.43718 16.375 1.54175 15.4796 1.54175 14.375V5.19498C1.54175 5.1852 1.54194 5.17546 1.54231 5.16577C1.55858 4.31209 2.25571 3.625 3.11335 3.625H16.8871C17.7549 3.625 18.4584 4.32843 18.4585 5.19622C18.4585 5.20225 18.4585 5.20826 18.4584 5.21428Z"
                                                                        fill="#667085" />
                                                                </svg>
                                                            </span>
                                                            <input type="text" id="email" name="email"
                                                                placeholder="info@example.com"
                                                                value="<?php echo esc_attr($guest->email ?? ''); ?>"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-[62px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                                        </div>
                                                    </div>

                                                    <!-- Phone Number -->
                                                    <div class="mb-4">
                                                        <label for="pnumber"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            Phone Number:
                                                        </label>

                                                        <div class="relative">
                                                            <span
                                                                class="absolute top-1/2 left-0 -translate-y-1/2 border-r border-gray-200 px-3.5 py-3 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                                                <!-- Phone SVG -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="1"
                                                                    stroke="currentColor" class="size-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                                                </svg>

                                                            </span>

                                                            <input id="pnumber" name="phone_number" type="tel"
                                                                value="<?php echo esc_attr($guest->phone_number ?? ''); ?>"
                                                                placeholder="+254 703 000 000"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-3 pr-4 pl-[84px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                                        </div>
                                                    </div>

                                                    <!-- ID Number -->
                                                    <div class="mb-4">
                                                        <label for="id_number"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            ID Number:
                                                        </label>
                                                        <input type="number" id="id_number" name="id_number"
                                                            placeholder="33612365"
                                                            value="<?php echo esc_attr($guest->id_number ?? ''); ?>"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                                    </div>

                                                    <!-- Courtesy -->
                                                    <div class="mb-4">
                                                        <label for="courtesy"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            Courtesy:
                                                        </label>
                                                        <input type="text" id="courtesy" name="courtesy"
                                                            placeholder="Chairman"
                                                            value="<?php echo esc_attr($guest->courtesy ?? ''); ?>"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                                    </div>

                                                    <!-- Status -->

                                                    <div class="mb-4">
                                                        <label for="guest_status"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            Status:
                                                        </label>
                                                        <div x-data="{ isOptionSelected: false }"
                                                            class="relative z-20 bg-transparent">
                                                            <select id="guest_status" name="guest_status"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                                                @change="isOptionSelected = true">
                                                                <option value="active"
                                                                    <?php selected($guest->guest_status ?? '', 'active'); ?>>
                                                                    Active
                                                                </option>
                                                                <option value="suspended"
                                                                    <?php selected($guest->guest_status ?? '', 'suspended'); ?>>
                                                                    Suspended
                                                                </option>
                                                                <option value="banned"
                                                                    <?php selected($guest->guest_status ?? '', 'banned'); ?>>
                                                                    Banned
                                                                </option>
                                                            </select>
                                                            <span
                                                                class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                                                <svg class="stroke-current" width="20" height="20"
                                                                    viewBox="0 0 20 20" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396"
                                                                        stroke="" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <!-- Communication Preferences -->
                                                    <div class="col-span-2">
                                                        <hr class="my-4 border-gray-300 dark:border-gray-600">
                                                        <div class="mb-4"
                                                            x-data="{ switcherToggle: <?php echo ($guest->receive_messages ?? 'no') === 'yes' ? 'true' : 'false'; ?> }">

                                                            <label for="receive_messages"
                                                                class="flex cursor-pointer items-center gap-3 text-sm font-medium text-gray-700 select-none dark:text-gray-400">

                                                                <div class="relative">
                                                                    <input type="checkbox" id="receive_messages"
                                                                        name="receive_messages" value="yes"
                                                                        class="sr-only"
                                                                        <?php checked($guest->receive_messages ?? 'no', 'yes'); ?>
                                                                        x-model="switcherToggle" />

                                                                    <!-- Track -->
                                                                    <div class="block h-6 w-11 rounded-full"
                                                                        :class="switcherToggle ? 'bg-brand-500 dark:bg-brand-500' : 'bg-gray-200 dark:bg-white/10'">
                                                                    </div>

                                                                    <!-- Knob -->
                                                                    <div class="shadow-theme-sm absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white duration-300 ease-linear"
                                                                        :class="switcherToggle ? 'translate-x-full' : 'translate-x-0'">
                                                                    </div>
                                                                </div>
                                                                Receive Messages?
                                                            </label>
                                                        </div>

                                                        <div class="mb-4"
                                                            x-data="{ switcherToggle: <?php echo ($guest->receive_emails ?? 'no') === 'yes' ? 'true' : 'false'; ?> }">
                                                            <label for="receive_emails"
                                                                class="flex cursor-pointer items-center gap-3 text-sm font-medium text-gray-700 select-none dark:text-gray-400">

                                                                <div class="relative">
                                                                    <input type="checkbox" id="receive_emails"
                                                                        name="receive_emails" value="yes"
                                                                        class="sr-only"
                                                                        <?php checked($guest->receive_emails ?? 'no', 'yes'); ?>
                                                                        x-model="switcherToggle" />

                                                                    <!-- Track -->
                                                                    <div class="block h-6 w-11 rounded-full"
                                                                        :class="switcherToggle ? 'bg-brand-500 dark:bg-brand-500' : 'bg-gray-200 dark:bg-white/10'">
                                                                    </div>

                                                                    <!-- Knob -->
                                                                    <div class="shadow-theme-sm absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white duration-300 ease-linear"
                                                                        :class="switcherToggle ? 'translate-x-full' : 'translate-x-0'">
                                                                    </div>
                                                                </div>
                                                                Receive Emails?
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex justify-center mt-4 space-x-2">
                                                    <input type="hidden" name="guest_id"
                                                        value="<?php echo esc_attr($guest_id); ?>">
                                                    <button type="submit" name="update_guest" id="update-guest-btn"
                                                        class="px-4 py-2 font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                                                        Update Guest
                                                    </button>
                                                    <button type="reset"
                                                        class="px-4 py-2 font-semibold text-white bg-gray-600 rounded hover:bg-gray-700">
                                                        Reset
                                                    </button>
                                                    <button type="submit" name="delete_guest" id="delete-guest-btn"
                                                        class="px-4 py-2 font-semibold text-white bg-red-600 rounded hover:bg-red-700">
                                                        Delete Guest
                                                    </button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                    <!-- End of Personal Information Section -->
                                    <!-- Visits Section -->
                                    <!-- Collapsible content section -->
                                    <div class="mt-10" id="payment">
                                        <!-- Success Alert -->
                                        <?php if (!empty($client_p_success)) : ?>
                                        <?php foreach ($client_p_success as $message) : ?>
                                        <div class="flex items-center justify-between p-4 mb-4 text-white bg-green-500 border-l-4 border-green-700 rounded"
                                            role="alert">
                                            <div>
                                                <strong>Success!</strong>
                                                <p class="text-sm"><?php echo esc_html($message); ?></p>
                                            </div>
                                            <button type="button" class="float-right text-white hover:text-gray-300"
                                                onclick="this.parentElement.style.display='none';">×</button>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php endif; ?>

                                        <!-- Error Alert -->
                                        <?php if (!empty($client_p_error)) : ?>
                                        <?php foreach ($client_p_error as $message) : ?>
                                        <div class="flex items-center justify-between p-4 mb-4 text-white bg-red-500 border-l-4 border-red-700 rounded"
                                            role="alert">
                                            <div>
                                                <strong>Error!</strong>
                                                <p class="text-sm"><?php echo esc_html($message); ?></p>
                                            </div>
                                            <button type="button" class="float-right text-white hover:text-gray-300"
                                                onclick="this.parentElement.style.display='none';">×</button>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php endif; ?>

                                        <div
                                            class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                                            <div class="px-5 py-4 sm:px-6 sm:py-5">
                                                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                                                    Guest Visits -
                                                    <?php echo esc_html($guest->first_name . ' ' . $guest->last_name); ?>
                                                </h3>
                                            </div>
                                            <div class="border-t border-gray-100 p-5 dark:border-gray-800 sm:p-6">
                                                <div
                                                    class="overflow-hidden rounded-xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

                                                    <!-- Controls -->
                                                    <div
                                                        class="mb-4 flex flex-col gap-2 px-4 sm:flex-row sm:items-center sm:justify-between">
                                                        <div class="flex items-center gap-3">
                                                            <span class="text-gray-500 dark:text-gray-400">Show</span>
                                                            <div class="relative z-20 bg-transparent">
                                                                <select
                                                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-9 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none py-2 pr-8 pl-3 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                                    onchange="window.location.href = '<?php echo build_per_page_url(''); ?>' + this.value">
                                                                    <option value="10" <?php selected($per_page, 10); ?>
                                                                        class="text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                                                                        10</option>
                                                                    <option value="8" <?php selected($per_page, 8); ?>
                                                                        class="text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                                                                        8</option>
                                                                    <option value="5" <?php selected($per_page, 5); ?>
                                                                        class="text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                                                                        5</option>
                                                                </select>
                                                                <span
                                                                    class="absolute top-1/2 right-2 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                                                    <svg class="stroke-current" width="16" height="16"
                                                                        viewBox="0 0 16 16" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M3.8335 5.9165L8.00016 10.0832L12.1668 5.9165"
                                                                            stroke="" stroke-width="1.2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></path>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                            <span
                                                                class="text-gray-500 dark:text-gray-400">entries</span>
                                                        </div>
                                                    </div>

                                                    <div class="max-w-full overflow-x-auto">
                                                        <div class="min-w-[1102px]">
                                                            <!-- Table Header -->
                                                            <div
                                                                class="grid grid-cols-12 border-t border-gray-200 dark:border-gray-800">
                                                                <div
                                                                    class="col-span-2 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
                                                                    <div
                                                                        class="flex w-full cursor-pointer items-center justify-between">
                                                                        <a href="<?php echo build_sort_url('host_member_name'); ?>"
                                                                            class="flex w-full items-center justify-between">
                                                                            <p
                                                                                class="text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                                Host Member</p>
                                                                            <span class="flex flex-col gap-0.5">
                                                                                <svg class="fill-gray-300 dark:fill-gray-700 <?php echo ($sort_column === 'host_member_name' && $sort_direction === 'asc') ? 'fill-blue-500' : ''; ?>"
                                                                                    width="8" height="5"
                                                                                    viewBox="0 0 8 5" fill="none"
                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                    <path
                                                                                        d="M4.40962 0.585167C4.21057 0.300808 3.78943 0.300807 3.59038 0.585166L1.05071 4.21327C0.81874 4.54466 1.05582 5 1.46033 5H6.53967C6.94418 5 7.18126 4.54466 6.94929 4.21327L4.40962 0.585167Z"
                                                                                        fill=""></path>
                                                                                </svg>
                                                                                <svg class="fill-gray-300 dark:fill-gray-700 <?php echo ($sort_column === 'host_member_name' && $sort_direction === 'desc') ? 'fill-blue-500' : ''; ?>"
                                                                                    width="8" height="5"
                                                                                    viewBox="0 0 8 5" fill="none"
                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                    <path
                                                                                        d="M4.40962 4.41483C4.21057 4.69919 3.78943 4.69919 3.59038 4.41483L1.05071 0.786732C0.81874 0.455343 1.05582 0 1.46033 0H6.53967C6.94418 0 7.18126 0.455342 6.94929 0.786731L4.40962 4.41483Z"
                                                                                        fill=""></path>
                                                                                </svg>
                                                                            </span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="col-span-2 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
                                                                    <div
                                                                        class="flex w-full cursor-pointer items-center justify-between">
                                                                        <a href="<?php echo build_sort_url('visit_date'); ?>"
                                                                            class="flex w-full items-center justify-between">
                                                                            <p
                                                                                class="text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                                Visit Date</p>
                                                                            <span class="flex flex-col gap-0.5">
                                                                                <svg class="fill-gray-300 dark:fill-gray-700 <?php echo ($sort_column === 'visit_date' && $sort_direction === 'asc') ? 'fill-blue-500' : ''; ?>"
                                                                                    width="8" height="5"
                                                                                    viewBox="0 0 8 5" fill="none"
                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                    <path
                                                                                        d="M4.40962 0.585167C4.21057 0.300808 3.78943 0.300807 3.59038 0.585166L1.05071 4.21327C0.81874 4.54466 1.05582 5 1.46033 5H6.53967C6.94418 5 7.18126 4.54466 6.94929 4.21327L4.40962 0.585167Z"
                                                                                        fill=""></path>
                                                                                </svg>
                                                                                <svg class="fill-gray-300 dark:fill-gray-700 <?php echo ($sort_column === 'visit_date' && $sort_direction === 'desc') ? 'fill-blue-500' : ''; ?>"
                                                                                    width="8" height="5"
                                                                                    viewBox="0 0 8 5" fill="none"
                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                    <path
                                                                                        d="M4.40962 4.41483C4.21057 4.69919 3.78943 4.69919 3.59038 4.41483L1.05071 0.786732C0.81874 0.455343 1.05582 0 1.46033 0H6.53967C6.94418 0 7.18126 0.455342 6.94929 0.786731L4.40962 4.41483Z"
                                                                                        fill=""></path>
                                                                                </svg>
                                                                            </span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="col-span-2 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
                                                                    <div
                                                                        class="flex w-full cursor-pointer items-center justify-between">
                                                                        <a href="<?php echo build_sort_url('sign_in_time'); ?>"
                                                                            class="flex w-full items-center justify-between">
                                                                            <p
                                                                                class="text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                                Sign In Time</p>
                                                                            <span class="flex flex-col gap-0.5">
                                                                                <svg class="fill-gray-300 dark:fill-gray-700 <?php echo ($sort_column === 'sign_in_time' && $sort_direction === 'asc') ? 'fill-blue-500' : ''; ?>"
                                                                                    width="8" height="5"
                                                                                    viewBox="0 0 8 5" fill="none"
                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                    <path
                                                                                        d="M4.40962 0.585167C4.21057 0.300808 3.78943 0.300807 3.59038 0.585166L1.05071 4.21327C0.81874 4.54466 1.05582 5 1.46033 5H6.53967C6.94418 5 7.18126 4.54466 6.94929 4.21327L4.40962 0.585167Z"
                                                                                        fill=""></path>
                                                                                </svg>
                                                                                <svg class="fill-gray-300 dark:fill-gray-700 <?php echo ($sort_column === 'sign_in_time' && $sort_direction === 'desc') ? 'fill-blue-500' : ''; ?>"
                                                                                    width="8" height="5"
                                                                                    viewBox="0 0 8 5" fill="none"
                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                    <path
                                                                                        d="M4.40962 4.41483C4.21057 4.69919 3.78943 4.69919 3.59038 4.41483L1.05071 0.786732C0.81874 0.455343 1.05582 0 1.46033 0H6.53967C6.94418 0 7.18126 0.455342 6.94929 0.786731L4.40962 4.41483Z"
                                                                                        fill=""></path>
                                                                                </svg>
                                                                            </span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="col-span-2 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
                                                                    <div
                                                                        class="flex w-full cursor-pointer items-center justify-between">
                                                                        <a href="<?php echo build_sort_url('sign_out_time'); ?>"
                                                                            class="flex w-full items-center justify-between">
                                                                            <p
                                                                                class="text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                                Sign Out Time</p>
                                                                            <span class="flex flex-col gap-0.5">
                                                                                <svg class="fill-gray-300 dark:fill-gray-700 <?php echo ($sort_column === 'sign_out_time' && $sort_direction === 'asc') ? 'fill-blue-500' : ''; ?>"
                                                                                    width="8" height="5"
                                                                                    viewBox="0 0 8 5" fill="none"
                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                    <path
                                                                                        d="M4.40962 0.585167C4.21057 0.300808 3.78943 0.300807 3.59038 0.585166L1.05071 4.21327C0.81874 4.54466 1.05582 5 1.46033 5H6.53967C6.94418 5 7.18126 4.54466 6.94929 4.21327L4.40962 0.585167Z"
                                                                                        fill=""></path>
                                                                                </svg>
                                                                                <svg class="fill-gray-300 dark:fill-gray-700 <?php echo ($sort_column === 'sign_out_time' && $sort_direction === 'desc') ? 'fill-blue-500' : ''; ?>"
                                                                                    width="8" height="5"
                                                                                    viewBox="0 0 8 5" fill="none"
                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                    <path
                                                                                        d="M4.40962 4.41483C4.21057 4.69919 3.78943 4.69919 3.59038 4.41483L1.05071 0.786732C0.81874 0.455343 1.05582 0 1.46033 0H6.53967C6.94418 0 7.18126 0.455342 6.94929 0.786731L4.40962 4.41483Z"
                                                                                        fill=""></path>
                                                                                </svg>
                                                                            </span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="col-span-2 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                        Duration</p>
                                                                </div>
                                                                <div class="col-span-2 flex items-center px-4 py-3">
                                                                    <p
                                                                        class="text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                        Status</p>
                                                                </div>
                                                            </div>

                                                            <!-- Table Body -->
                                                            <?php if (!empty($visits)): ?>
                                                            <?php foreach ($visits as $visit): ?>
                                                            <div
                                                                class="grid grid-cols-12 border-t border-gray-100 dark:border-gray-800">
                                                                <div
                                                                    class="col-span-2 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                                        <?php
                                                                        $host_display = 'N/A';

                                                                        if (!empty($visit->host_member_id)) {
                                                                            $host_user = get_userdata($visit->host_member_id);
                                                                            if ($host_user) {
                                                                                $first_name = get_user_meta($visit->host_member_id, 'first_name', true);
                                                                                $last_name  = get_user_meta($visit->host_member_id, 'last_name', true);

                                                                                if (!empty($first_name) || !empty($last_name)) {
                                                                                    $host_display = trim($first_name . ' ' . $last_name);
                                                                                } else {
                                                                                    // fallback to username if no names
                                                                                    $host_display = $host_user->user_login;
                                                                                }
                                                                            }
                                                                        }

                                                                        echo esc_html($host_display);
                                                                        ?>
                                                                    </p>
                                                                </div>
                                                                <div
                                                                    class="col-span-2 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                                        <?php echo esc_html(format_date($visit->visit_date)); ?>
                                                                    </p>
                                                                </div>
                                                                <div
                                                                    class="col-span-2 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                                        <?php echo esc_html(format_time($visit->sign_in_time)); ?>
                                                                    </p>
                                                                </div>
                                                                <div
                                                                    class="col-span-2 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                                        <?php echo esc_html(format_time($visit->sign_out_time)); ?>
                                                                    </p>
                                                                </div>
                                                                <div
                                                                    class="col-span-2 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                                        <?php echo esc_html(calculate_duration($visit->sign_in_time, $visit->sign_out_time)); ?>
                                                                    </p>
                                                                </div>
                                                                <div class="col-span-2 flex items-center px-4 py-3">
                                                                    <?php
                                                                    // Determine the visit status first
                                                                    $status = get_visit_status($visit->visit_date, $visit->sign_in_time, $visit->sign_out_time);
                                                                    $status_class = get_status_class($status);
                                                                    $status_text = get_status_text($status);
                                                                    ?>
                                                                    <div class="col-span-2 flex items-center px-4 py-3">
                                                                        <span
                                                                            class="<?php echo esc_attr($status_class); ?> inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                                                            <?php echo esc_html($status_text); ?>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php endforeach; ?>
                                                            <?php else: ?>
                                                            <div
                                                                class="border-t border-gray-100 px-4 py-8 text-center dark:border-gray-800">
                                                                <p class="text-gray-500 dark:text-gray-400">
                                                                    No visits found
                                                                </p>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>

                                                    <!-- Pagination Controls -->
                                                    <div
                                                        class="border-t border-gray-100 py-4 pr-4 pl-[18px] dark:border-gray-800">
                                                        <div
                                                            class="flex flex-col xl:flex-row xl:items-center xl:justify-between">
                                                            <p
                                                                class="border-b border-gray-100 pb-3 text-center text-sm font-medium text-gray-500 xl:border-b-0 xl:pb-0 xl:text-left dark:border-gray-800 dark:text-gray-400">
                                                                Showing <?php echo $start_entry; ?> to
                                                                <?php echo $end_entry; ?> of
                                                                <?php echo $total_visits; ?> entries
                                                            </p>

                                                            <?php if ($total_pages > 1): ?>
                                                            <div
                                                                class="flex items-center justify-center gap-0.5 pt-4 xl:justify-end xl:pt-0">
                                                                <!-- Previous Button -->
                                                                <a href="<?php echo $current_page > 1 ? build_pagination_url($current_page - 1) : '#'; ?>"
                                                                    class="shadow-theme-xs mr-2.5 flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 <?php echo $current_page === 1 ? 'opacity-50 cursor-not-allowed' : ''; ?> dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                                                    <svg class="fill-current" width="20" height="20"
                                                                        viewBox="0 0 20 20" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                                            d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z"
                                                                            fill=""></path>
                                                                    </svg>
                                                                </a>

                                                                <!-- Page Numbers -->
                                                                <?php
                                                                    $start_page = max(1, $current_page - 2);
                                                                    $end_page = min($total_pages, $current_page + 2);
                                                                    
                                                                    // Show first page if not in range
                                                                    if ($start_page > 1): ?>
                                                                <a href="<?php echo build_pagination_url(1); ?>"
                                                                    class="hover:text-blue-500 dark:hover:text-blue-500 flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium hover:bg-blue-500/[0.08] text-gray-700 dark:text-gray-400">1</a>
                                                                <?php if ($start_page > 2): ?>
                                                                <span
                                                                    class="flex h-10 w-10 items-center justify-center text-gray-700 dark:text-gray-400">...</span>
                                                                <?php endif; ?>
                                                                <?php endif; ?>

                                                                <!-- Page range -->
                                                                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                                                <a href="<?php echo build_pagination_url($i); ?>"
                                                                    class="<?php echo $current_page === $i ? 'bg-blue-500/[0.08] text-blue-500' : 'text-gray-700 dark:text-gray-400'; ?> hover:text-blue-500 dark:hover:text-blue-500 flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium hover:bg-blue-500/[0.08]">
                                                                    <?php echo $i; ?>
                                                                </a>
                                                                <?php endfor; ?>

                                                                <!-- Show last page if not in range -->
                                                                <?php if ($end_page < $total_pages): ?>
                                                                <?php if ($end_page < $total_pages - 1): ?>
                                                                <span
                                                                    class="flex h-10 w-10 items-center justify-center text-gray-700 dark:text-gray-400">...</span>
                                                                <?php endif; ?>
                                                                <a href="<?php echo build_pagination_url($total_pages); ?>"
                                                                    class="hover:text-blue-500 dark:hover:text-blue-500 flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium hover:bg-blue-500/[0.08] text-gray-700 dark:text-gray-400"><?php echo $total_pages; ?></a>
                                                                <?php endif; ?>

                                                                <!-- Next Button -->
                                                                <a href="<?php echo $current_page < $total_pages ? build_pagination_url($current_page + 1) : '#'; ?>"
                                                                    class="shadow-theme-xs ml-2.5 flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 <?php echo $current_page === $total_pages ? 'opacity-50 cursor-not-allowed' : ''; ?> dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                                                    <svg class="fill-current" width="20" height="20"
                                                                        viewBox="0 0 20 20" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                                            d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z"
                                                                            fill=""></path>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of Visits Section -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <?php get_template_part('template-parts/content/content-footer', 'content'); ?>

                </main>
                <!-- ===== Main Content End ===== -->
            </div>
            <!-- ===== Content Area End ===== -->
        </div>
    </main>
</section>

<?php
get_footer();