<?php
/**
 * The template for displaying reciprocating member details page
 *
 * @package Visitor_Management_System
 */
use WyllyMk\VMS\VMS_CoreManager;

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

// ===============================================
// MAIN PAGE LOGIC
// ===============================================

global $wpdb;

// WordPress table prefix
$recip_members_table = \WyllyMk\VMS\VMS_Config::get_table_name(\WyllyMk\VMS\VMS_Config::RECIP_MEMBERS_TABLE);
$recip_visits_table = \WyllyMk\VMS\VMS_Config::get_table_name(\WyllyMk\VMS\VMS_Config::RECIP_MEMBERS_VISITS_TABLE);
$recip_clubs_table = \WyllyMk\VMS\VMS_Config::get_table_name(\WyllyMk\VMS\VMS_Config::RECIP_CLUBS_TABLE);

// Get member_id from URL
$member_id = isset($_GET['member_id']) ? absint($_GET['member_id']) : 0;
if (!$member_id) {
    wp_die('Invalid member ID.');
}

// Get member details
$member = $wpdb->get_row(
    $wpdb->prepare("SELECT rm.*, rc.club_name FROM $recip_members_table rm LEFT JOIN $recip_clubs_table rc ON rm.reciprocating_club_id = rc.id WHERE rm.id = %d", $member_id)
);

if (!$member) {
    wp_die('Member not found.');
}

// Pagination parameters with proper validation
$per_page = isset($_GET['per_page']) ? absint($_GET['per_page']) : 10;
if (!in_array($per_page, [10, 25, 50])) {
    $per_page = 10;
}
$paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$offset = ($paged - 1) * $per_page;

// Count total visits for this member
$total_visits = $wpdb->get_var(
    $wpdb->prepare("SELECT COUNT(*) FROM $recip_visits_table WHERE member_id = %d", $member_id)
);

// Ensure we have a valid total_visits count
$total_visits = $total_visits ? (int) $total_visits : 0;

// Calculate total pages
$total_pages = $total_visits > 0 ? (int) ceil($total_visits / $per_page) : 1;

// Ensure current page doesn't exceed total pages
if ($paged > $total_pages) {
    $paged = $total_pages;
    $offset = ($paged - 1) * $per_page;
}

// Get paged visits with proper ordering
$visits = [];
if ($total_visits > 0) {
    $visits = $wpdb->get_results(
        $wpdb->prepare("
            SELECT rmv.*, rmv.id as visit_id
            FROM $recip_visits_table rmv
            WHERE rmv.member_id = %d
            ORDER BY rmv.visit_date DESC, rmv.created_at DESC
            LIMIT %d OFFSET %d
        ", $member_id, $per_page, $offset)
    );
}

// Calculate row numbers for display
$current_start = $total_visits > 0 ? ($paged - 1) * $per_page + 1 : 0;
$row_number = $current_start;

// Build compact pagination array
$pages_to_show = [];
if ($total_pages > 0) {
    $pages_to_show = [1];
   
    // Add current page and surrounding pages
    for ($i = max(1, $paged - 1); $i <= min($total_pages, $paged + 1); $i++) {
        $pages_to_show[] = $i;
    }
   
    // Add last page if it's not already included
    if ($total_pages > 1) {
        $pages_to_show[] = $total_pages;
    }
   
    // Remove duplicates and sort
    $pages_to_show = array_values(array_unique($pages_to_show));
    sort($pages_to_show);
}

$status_classes = [
    'approved'   => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
    'unapproved' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
    'suspended'  => 'bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500',
    'banned'     => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
    'cancelled'  => 'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80'
];

// Handle AJAX requests
if (wp_doing_ajax()) {    
    // Update member details
    if (isset($_POST['action']) && $_POST['action'] === 'update_recip_member') {
        check_ajax_referer('update_recip_member_nonce', 'nonce');
        
        $member_id = intval($_POST['member_id']);
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);
        $email = sanitize_email($_POST['email']);
        $phone_number = sanitize_text_field($_POST['phone_number']);
        $id_number = sanitize_text_field($_POST['id_number']);
        $member_status = sanitize_text_field($_POST['member_status']);
        $receive_messages = isset($_POST['receive_messages']) ? 'yes' : 'no';
        $receive_emails = isset($_POST['receive_emails']) ? 'yes' : 'no';
        
        // Validation
        $errors = [];
        if (empty($first_name)) $errors[] = 'First name is required';
        if (empty($last_name)) $errors[] = 'Last name is required';
        if (empty($id_number)) $errors[] = 'ID number is required';
        if (!in_array($member_status, ['active', 'suspended', 'banned'])) $errors[] = 'Invalid status';
        
        if (!empty($errors)) {
            wp_send_json_error(['message' => implode(', ', $errors)]);
        }
        
        $updated = $wpdb->update(
            $recip_members_table,
            [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone_number' => $phone_number,
                'id_number' => $id_number,
                'member_status' => $member_status,
                'receive_messages' => $receive_messages,
                'receive_emails' => $receive_emails
            ],
            ['id' => $member_id],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'],
            ['%d']
        );
        
        if ($updated !== false) {
            wp_send_json_success(['message' => 'Member updated successfully']);
        } else {
            wp_send_json_error(['message' => 'Failed to update member']);
        }
    }
    
    // Delete member
    if (isset($_POST['action']) && $_POST['action'] === 'delete_recip_member') {
        check_ajax_referer('delete_recip_member_nonce', 'nonce');
        
        $member_id = intval($_POST['member_id']);
        
        $deleted = $wpdb->delete($recip_members_table, ['id' => $member_id], ['%d']);
        
        if ($deleted) {
            wp_send_json_success(['message' => 'Member deleted successfully', 'redirect' => admin_url('admin.php?page=reciprocating-members')]);
        } else {
            wp_send_json_error(['message' => 'Failed to delete member']);
        }
    }  
}

// Handle canceling a reciprocating member visit
if ( isset($_POST['cancel_recip_visit']) && isset($_POST['visit_id']) ) {
   
    // Verify nonce for security
    if ( ! isset($_POST['cancel_recip_visit_nonce']) ||
         ! wp_verify_nonce($_POST['cancel_recip_visit_nonce'], 'cancel_recip_visit_action') ) {
        wp_die(__('Security check failed. Please try again.', 'vms'));
    }
   
    $visit_id = intval($_POST['visit_id']);
   
    if ( $visit_id > 0 ) {
        global $wpdb;
        
        $recip_visits_table = \WyllyMk\VMS\VMS_Config::get_table_name(\WyllyMk\VMS\VMS_Config::RECIP_MEMBERS_VISITS_TABLE);
       
        // Get the visit details before cancelling
        $visit = $wpdb->get_row($wpdb->prepare(
            "SELECT member_id, visit_date FROM $recip_visits_table WHERE id = %d",
            $visit_id
        ));
       
        if (!$visit) {
            wp_die(__('Visit not found.', 'vms'));
        }
       
        // Update visit status to cancelled
        $updated = $wpdb->update(
            $recip_visits_table,
            array( 'status' => 'cancelled' ),
            array( 'id' => $visit_id ),
            array( '%s' ),
            array( '%d' )
        );
       
        if ( $updated !== false ) {
            // Trigger automatic status recalculation for the member
            VMS_CoreManager::recalculate_member_visit_statuses($visit->member_id);            
           
            // Success message or redirect
            wp_safe_redirect( add_query_arg('visit_cancelled', '1', wp_get_referer()) );
            exit;
        } else {
            wp_die(__('Failed to cancel visit. Please try again.', 'vms'));
        }
    } else {
        wp_die(__('Invalid visit ID.', 'vms'));
    }
}

get_header();
?>

<section id="primary" x-data="{ page: 'reciprocating-member-details', 'isVisitInfoModal': false}"
    @close-visit-modal.window="isVisitInfoModal = false">
    <main id="main">
        <!-- Page Wrapper Start -->
        <div class="flex h-svh overflow-hidden">
            <!-- Sidebar Start -->
            <?php get_template_part('template-parts/content/content', 'sidebar'); ?>
            <!-- Sidebar End -->

            <!-- Content Area Start -->
            <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
                <!-- Small Device Overlay Start -->
                <?php get_template_part('template-parts/content/content', 'overlay'); ?>
                <!-- Small Device Overlay End -->

                <!-- Header Start -->
                <?php get_template_part('template-parts/content/content', 'header'); ?>
                <!-- Header End -->

                <!-- Main Content Start -->
                <main>
                    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) min-h-screen md:p-6">
                        <!-- Breadcrumb Start -->
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                            <a href="<?php echo esc_url( home_url( '/reciprocating-members' ) ); ?>"
                                class="inline-flex items-center text-sm text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                <svg class="stroke-current" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none">
                                    <path d="M12.7083 5L7.5 10.2083L12.7083 15.4167" stroke="" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <?php esc_html_e( 'Back to Reciprocating Members', 'vms' ); ?>
                            </a>

                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                                <?php esc_html_e( 'Reciprocating Member Details', 'vms' ); ?>
                            </h2>
                        </div>
                        <!-- Breadcrumb End -->

                        <!-- Success/Error Messages -->
                        <div id="message-container" class="hidden mb-4">
                            <div id="success-message"
                                class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                <span id="success-text"></span>
                            </div>
                            <div id="error-message"
                                class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <span id="error-text"></span>
                            </div>
                        </div>

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
                                        <div x-show="open" x-transition
                                            class="p-6 border-t border-gray-200 dark:border-gray-700">
                                            <form id="member-update-form">
                                                <div class="-mx-2.5 flex flex-wrap gap-y-4">

                                                    <!-- First Name -->
                                                    <div class="w-full px-2.5 md:w-1/2">
                                                        <label for="fname"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            First Name:
                                                        </label>
                                                        <div class="relative">
                                                            <span
                                                                class="absolute top-1/2 left-0 -translate-y-1/2 border-r border-gray-200 px-3.5 py-3 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                                                <svg class="fill-current" width="20" height="20"
                                                                    viewBox="0 0 20 20" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M8.0254 6.17845C8.0254 4.90629 9.05669 3.875 10.3289 3.875C11.601 3.875 12.6323 4.90629 12.6323 6.17845C12.6323 7.45061 11.601 8.48191 10.3289 8.48191C9.05669 8.48191 8.0254 7.45061 8.0254 6.17845ZM10.3289 2.375C8.22827 2.375 6.5254 4.07786 6.5254 6.17845C6.5254 8.27904 8.22827 9.98191 10.3289 9.98191C12.4294 9.98191 14.1323 8.27904 14.1323 6.17845C14.1323 4.07786 12.4294 2.375 10.3289 2.375ZM8.92286 11.03C5.7669 11.03 3.2085 13.5884 3.2085 16.7444V17.0333C3.2085 17.4475 3.54428 17.7833 3.9585 17.7833C4.37271 17.7833 4.7085 17.4475 4.7085 17.0333V16.7444C4.7085 14.4169 6.59533 12.53 8.92286 12.53H11.736C14.0635 12.53 15.9504 14.4169 15.9504 16.7444V17.0333C15.9504 17.4475 16.2861 17.7833 16.7004 17.7833C17.1146 17.7833 17.4504 17.4475 17.4504 17.0333V16.7444C17.4504 13.5884 14.8919 11.03 11.736 11.03H8.92286Z"
                                                                        fill=""></path>
                                                                </svg>
                                                            </span>
                                                            <input type="text" id="fname" name="first_name"
                                                                placeholder="First Name"
                                                                value="<?php echo esc_attr($member->first_name ?? ''); ?>"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-20 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                                        </div>
                                                    </div>

                                                    <!-- Last Name -->
                                                    <div class="w-full px-2.5 md:w-1/2">
                                                        <label for="lname"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            Last Name:
                                                        </label>
                                                        <div class="relative">
                                                            <span
                                                                class="absolute top-1/2 left-0 -translate-y-1/2 border-r border-gray-200 px-3.5 py-3 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                                                <svg class="fill-current" width="20" height="20"
                                                                    viewBox="0 0 20 20" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M8.0254 6.17845C8.0254 4.90629 9.05669 3.875 10.3289 3.875C11.601 3.875 12.6323 4.90629 12.6323 6.17845C12.6323 7.45061 11.601 8.48191 10.3289 8.48191C9.05669 8.48191 8.0254 7.45061 8.0254 6.17845ZM10.3289 2.375C8.22827 2.375 6.5254 4.07786 6.5254 6.17845C6.5254 8.27904 8.22827 9.98191 10.3289 9.98191C12.4294 9.98191 14.1323 8.27904 14.1323 6.17845C14.1323 4.07786 12.4294 2.375 10.3289 2.375ZM8.92286 11.03C5.7669 11.03 3.2085 13.5884 3.2085 16.7444V17.0333C3.2085 17.4475 3.54428 17.7833 3.9585 17.7833C4.37271 17.7833 4.7085 17.4475 4.7085 17.0333V16.7444C4.7085 14.4169 6.59533 12.53 8.92286 12.53H11.736C14.0635 12.53 15.9504 14.4169 15.9504 16.7444V17.0333C15.9504 17.4475 16.2861 17.7833 16.7004 17.7833C17.1146 17.7833 17.4504 17.4475 17.4504 17.0333V16.7444C17.4504 13.5884 14.8919 11.03 11.736 11.03H8.92286Z"
                                                                        fill=""></path>
                                                                </svg>
                                                            </span>
                                                            <input type="text" id="lname" name="last_name"
                                                                placeholder="Last Name"
                                                                value="<?php echo esc_attr($member->last_name ?? ''); ?>"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-20 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                                        </div>
                                                    </div>

                                                    <!-- Email -->
                                                    <div class="w-full px-2.5 md:w-1/2">
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
                                                            <input type="email" id="email" name="email"
                                                                placeholder="info@example.com"
                                                                value="<?php echo esc_attr($member->email ?? ''); ?>"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-20 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                                        </div>
                                                    </div>

                                                    <!-- Phone Number -->
                                                    <div class="w-full px-2.5 md:w-1/2">
                                                        <label for="pnumber"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            Phone Number:
                                                        </label>
                                                        <div class="relative">
                                                            <span
                                                                class="absolute top-1/2 left-0 -translate-y-1/2 border-r border-gray-200 px-3 md:px-3.5 py-3 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="1"
                                                                    stroke="currentColor" class="size-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                                                </svg>
                                                            </span>
                                                            <input id="pnumber" name="phone_number" type="tel"
                                                                value="<?php echo esc_attr($member->phone_number ?? ''); ?>"
                                                                placeholder="+254 703 000 000"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-3 pr-4 pl-20 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                                        </div>
                                                    </div>

                                                    <!-- ID Number -->
                                                    <div class="w-full px-2.5 md:w-1/2">
                                                        <label for="id_number"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            ID Number:
                                                        </label>
                                                        <div class="relative">
                                                            <span
                                                                class="absolute top-1/2 left-0 -translate-y-1/2 border-r border-gray-200 px-3.5 py-3 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                                                <svg class="fill-current" width="20" height="20"
                                                                    viewBox="0 0 24 24" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <rect x="3" y="4" width="18" height="16" rx="2"
                                                                        ry="2" stroke="currentColor" stroke-width="2"
                                                                        fill="none" />
                                                                    <circle cx="9" cy="12" r="2" stroke="currentColor"
                                                                        stroke-width="2" fill="none" />
                                                                    <path d="M15 10h4M15 14h4" stroke="currentColor"
                                                                        stroke-width="2" stroke-linecap="round" />
                                                                </svg>
                                                            </span>
                                                            <input type="text" id="id_number" name="id_number"
                                                                placeholder="33612365"
                                                                value="<?php echo esc_attr($member->id_number ?? ''); ?>"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-20 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                                        </div>
                                                    </div>

                                                    <!-- Member Number -->
                                                    <div class="w-full px-2.5 md:w-1/2">
                                                        <label for="member_number"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            Member Number:
                                                        </label>
                                                        <div class="relative">
                                                            <span
                                                                class="absolute top-1/2 left-0 -translate-y-1/2 border-r border-gray-200 px-3.5 py-3 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                                                <svg class="fill-current" width="20" height="20"
                                                                    viewBox="0 0 24 24" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        fill="none" stroke-linecap="round"
                                                                        stroke-linejoin="round" />
                                                                </svg>
                                                            </span>
                                                            <input type="text" id="member_number"
                                                                name="reciprocating_member_number" placeholder="RM001"
                                                                value="<?php echo esc_attr($member->reciprocating_member_number ?? ''); ?>"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-20 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                                        </div>
                                                    </div>

                                                    <!-- Club -->
                                                    <div class="w-full px-2.5 md:w-1/2">
                                                        <label for="club_name"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            Club:
                                                        </label>
                                                        <div class="relative">
                                                            <span
                                                                class="absolute top-1/2 left-0 -translate-y-1/2 border-r border-gray-200 px-3.5 py-3 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                                                <svg class="fill-current" width="20" height="20"
                                                                    viewBox="0 0 24 24" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2M16 3.13a4 4 0 010 7.75M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        fill="none" stroke-linecap="round"
                                                                        stroke-linejoin="round" />
                                                                </svg>
                                                            </span>
                                                            <input type="text" id="club_name" name="club_name"
                                                                placeholder="Club Name" readonly
                                                                value="<?php echo esc_attr($member->club_name ?? ''); ?>"
                                                                class="dark:bg-dark-900 shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 pl-20 text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" />
                                                        </div>
                                                    </div>

                                                    <!-- Status -->
                                                    <div class="w-full px-2.5 md:w-1/2">
                                                        <label for="member_status"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            Status:
                                                        </label>
                                                        <div x-data="{ isOptionSelected: false }"
                                                            class="relative z-20 bg-transparent">
                                                            <span
                                                                class="absolute top-1/2 left-0 -translate-y-1/2 border-r border-gray-200 px-3.5 md:px-4 py-3 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                                                <svg class="stroke-current" width="20" height="20"
                                                                    viewBox="0 0 24 24" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round" />
                                                                </svg>
                                                            </span>
                                                            <select id="member_status" name="member_status"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none pl-20 pr-11 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                                                @change="isOptionSelected = true">
                                                                <option value="active"
                                                                    <?php selected($member->member_status ?? '', 'active'); ?>>
                                                                    Active
                                                                </option>
                                                                <option value="suspended"
                                                                    <?php selected($member->member_status ?? '', 'suspended'); ?>>
                                                                    Suspended
                                                                </option>
                                                                <option value="banned"
                                                                    <?php selected($member->member_status ?? '', 'banned'); ?>>
                                                                    Banned
                                                                </option>
                                                            </select>
                                                            <!-- Dropdown arrow -->
                                                            <span
                                                                class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                                                <svg class="stroke-current" width="20" height="20"
                                                                    viewBox="0 0 20 20" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396"
                                                                        stroke-width="1.5" stroke-linecap="round"
                                                                        stroke-linejoin="round" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <!-- Communication Preferences -->
                                                    <div class="w-full px-2.5">
                                                        <hr class="my-4 border-gray-300 dark:border-gray-600">
                                                        <div class="mb-4"
                                                            x-data="{ switcherToggle: <?php echo ($member->receive_messages ?? 'no') === 'yes' ? 'true' : 'false'; ?> }">
                                                            <label for="receive_messages"
                                                                class="flex cursor-pointer items-center gap-3 text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                                                                <div class="relative">
                                                                    <input type="checkbox" id="receive_messages"
                                                                        name="receive_messages" value="yes"
                                                                        class="sr-only"
                                                                        <?php checked($member->receive_messages ?? 'no', 'yes'); ?>
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
                                                            x-data="{ switcherToggle: <?php echo ($member->receive_emails ?? 'no') === 'yes' ? 'true' : 'false'; ?> }">
                                                            <label for="receive_emails"
                                                                class="flex cursor-pointer items-center gap-3 text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                                                                <div class="relative">
                                                                    <input type="checkbox" id="receive_emails"
                                                                        name="receive_emails" value="yes"
                                                                        class="sr-only"
                                                                        <?php checked($member->receive_emails ?? 'no', 'yes'); ?>
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

                                                <div class="flex flex-col md:flex-row justify-center mt-4 gap-2">
                                                    <input type="hidden" name="member_id"
                                                        value="<?php echo esc_attr($member_id); ?>">
                                                    <button type="submit" id="update-member-btn"
                                                        class="inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 cursor-pointer">
                                                        Update Member
                                                    </button>
                                                    <button type="reset"
                                                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03] cursor-pointer">
                                                        Reset
                                                    </button>
                                                    <button type="button" id="delete-member-btn"
                                                        data-member-name="<?php echo esc_attr($member->first_name); ?>"
                                                        class="px-4 py-2 text-white bg-error-500 rounded-lg hover:bg-error-600 inline-flex items-center justify-center gap-2 shadow-theme-xs transition cursor-pointer">
                                                        Delete Member
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- End of Personal Information Section -->

                                    <!-- Visits Section -->
                                    <div class="mt-10" id="visits-section">
                                        <div
                                            class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                                            <div class="flex items-center justify-between px-5 py-4 sm:px-6 sm:py-5">
                                                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                                                    Member Visits -
                                                    <?php echo esc_html($member->first_name . ' ' . $member->last_name); ?>
                                                </h3>
                                                <div class="flex items-center justify-end w-full md:w-1/2">
                                                    <button type="button" @click="isVisitInfoModal = true"
                                                        class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg cursor-pointer bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                                                        Register New Visit
                                                    </button>
                                                </div>
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
                                                                    onchange="window.location.href = '<?php echo esc_js(VMS_CoreManager::build_per_page_url()); ?>' + this.value">
                                                                    <option value="10"
                                                                        <?php selected($per_page, 10); ?>>10</option>
                                                                    <option value="25"
                                                                        <?php selected($per_page, 25); ?>>25</option>
                                                                    <option value="50"
                                                                        <?php selected($per_page, 50); ?>>50</option>
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

                                                        <!-- Show total entries info -->
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            <?php if ($total_visits > 0): ?>
                                                            Showing <?php echo esc_html($current_start); ?> to
                                                            <?php echo esc_html(min($current_start + count($visits) - 1, $total_visits)); ?>
                                                            of <?php echo esc_html($total_visits); ?> entries
                                                            <?php else: ?>
                                                            No entries found
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>

                                                    <div class="max-w-full overflow-x-auto">
                                                        <div id="visits-table" class="min-w-[900px]">
                                                            <!-- Table Header -->
                                                            <div id="visits-header"
                                                                class="grid grid-cols-10 border-t border-gray-200 dark:border-gray-800">
                                                                <!-- # -->
                                                                <div
                                                                    class="col-span-1 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                        #</p>
                                                                </div>
                                                                <!-- Visit Date -->
                                                                <div
                                                                    class="col-span-2 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                        Visit Date</p>
                                                                </div>
                                                                <!-- Sign In Time -->
                                                                <div
                                                                    class="col-span-2 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="whitespace-nowrap text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                        Sign In</p>
                                                                </div>
                                                                <!-- Sign Out Time -->
                                                                <div
                                                                    class="col-span-2 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="whitespace-nowrap text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                        Sign Out</p>
                                                                </div>
                                                                <!-- Duration -->
                                                                <div
                                                                    class="col-span-1 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                        Duration</p>
                                                                </div>
                                                                <!-- Status -->
                                                                <div
                                                                    class="col-span-1 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                        Status</p>
                                                                </div>
                                                                <!-- Action -->
                                                                <div
                                                                    class="col-span-1 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                        Action</p>
                                                                </div>
                                                            </div>

                                                            <!-- Table Body -->
                                                            <div id="visits-body">
                                                                <?php if (!empty($visits)): ?>
                                                                <?php foreach ($visits as $visit): ?>
                                                                <div id="visit-div-<?php echo esc_attr($visit->id); ?>"
                                                                    class="grid grid-cols-10 border-y border-gray-100 dark:border-gray-800">
                                                                    <!-- Row Number -->
                                                                    <div
                                                                        class="col-span-1 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                        <p
                                                                            class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                                            <?php echo esc_html($row_number++); ?>
                                                                        </p>
                                                                    </div>

                                                                    <!-- Visit Date -->
                                                                    <div
                                                                        class="col-span-2 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                        <p
                                                                            class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                                            <?php echo esc_html(VMS_CoreManager::format_date($visit->visit_date)); ?>
                                                                        </p>
                                                                    </div>

                                                                    <!-- Sign In -->
                                                                    <div
                                                                        class="col-span-2 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                        <p
                                                                            class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                                            <?php echo esc_html(VMS_CoreManager::format_time($visit->sign_in_time)); ?>
                                                                        </p>
                                                                    </div>

                                                                    <!-- Sign Out -->
                                                                    <div
                                                                        class="col-span-2 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                        <p
                                                                            class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                                            <?php echo esc_html(VMS_CoreManager::format_time($visit->sign_out_time)); ?>
                                                                        </p>
                                                                    </div>

                                                                    <!-- Duration -->
                                                                    <div
                                                                        class="col-span-1 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                        <p
                                                                            class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                                            <?php echo esc_html(VMS_CoreManager::calculate_duration($visit->sign_in_time, $visit->sign_out_time)); ?>
                                                                        </p>
                                                                    </div>

                                                                    <!-- Status -->
                                                                    <div
                                                                        class="col-span-1 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                        <span
                                                                            class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium capitalize <?php echo $status_classes[$visit->status] ?? $status_classes['approved']; ?>">
                                                                            <?php echo esc_html($visit->status); ?>
                                                                        </span>
                                                                    </div>

                                                                    <!-- Action -->
                                                                    <div
                                                                        class="col-span-1 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                        <form method="post"
                                                                            onsubmit="return confirm('Are you sure you want to cancel this visit?');">
                                                                            <input type="hidden" name="visit_id"
                                                                                value="<?php echo esc_attr( $visit->id ); ?>">
                                                                            <?php wp_nonce_field( 'cancel_recip_visit_action', 'cancel_recip_visit_nonce' ); ?>
                                                                            <button type="submit"
                                                                                name="cancel_recip_visit"
                                                                                class="px-3 py-1 text-xs font-medium text-white bg-red-500 rounded-lg hover:bg-red-600">
                                                                                <?php esc_html_e( 'Cancel', 'vms' ); ?>
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <?php endforeach; ?>
                                                                <?php else: ?>
                                                                <div id="no-visits-div"
                                                                    class="border-t border-gray-100 px-4 py-8 text-center dark:border-gray-800">
                                                                    <p class="text-gray-500 dark:text-gray-400">No
                                                                        visits found</p>
                                                                </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Pagination -->
                                                    <?php if ($total_pages > 1): ?>
                                                    <div
                                                        class="flex items-center justify-between gap-8 px-6 py-4 sm:justify-normal">
                                                        <!-- Previous Button -->
                                                        <?php if ($paged > 1): ?>
                                                        <a href="<?php echo esc_url(VMS_CoreManager::build_pagination_url($paged - 1)); ?>"
                                                            class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5 sm:py-2.5">
                                                            <svg class="fill-current" width="20" height="20"
                                                                viewBox="0 0 20 20" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M2.58203 9.99868C2.58174 10.1909 2.6549 10.3833 2.80152 10.53L7.79818 15.5301C8.09097 15.8231 8.56584 15.8233 8.85883 15.5305C9.15183 15.2377 9.152 14.7629 8.85921 14.4699L5.13911 10.7472L16.6665 10.7472C17.0807 10.7472 17.4165 10.4114 17.4165 9.99715C17.4165 9.58294 17.0807 9.24715 16.6665 9.24715L5.14456 9.24715L8.85919 5.53016C9.15199 5.23717 9.15184 4.7623 8.85885 4.4695C8.56587 4.1767 8.09099 4.17685 7.79819 4.46984L2.84069 9.43049C2.68224 9.568 2.58203 9.77087 2.58203 9.99715C2.58203 9.99766 2.58203 9.99817 2.58203 9.99868Z"
                                                                    fill=""></path>
                                                            </svg>
                                                            <span class="hidden sm:inline">Previous</span>
                                                        </a>
                                                        <?php else: ?>
                                                        <span
                                                            class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-400 shadow-theme-xs sm:px-3.5 sm:py-2.5 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-600 cursor-not-allowed">
                                                            <svg class="fill-current" width="20" height="20"
                                                                viewBox="0 0 20 20" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M2.58203 9.99868C2.58174 10.1909 2.6549 10.3833 2.80152 10.53L7.79818 15.5301C8.09097 15.8231 8.56584 15.8233 8.85883 15.5305C9.15183 15.2377 9.152 14.7629 8.85921 14.4699L5.13911 10.7472L16.6665 10.7472C17.0807 10.7472 17.4165 10.4114 17.4165 9.99715C17.4165 9.58294 17.0807 9.24715 16.6665 9.24715L5.14456 9.24715L8.85919 5.53016C9.15199 5.23717 9.15184 4.7623 8.85885 4.4695C8.56587 4.1767 8.09099 4.17685 7.79819 4.46984L2.84069 9.43049C2.68224 9.568 2.58203 9.77087 2.58203 9.99715C2.58203 9.99766 2.58203 9.99817 2.58203 9.99868Z"
                                                                    fill=""></path>
                                                            </svg>
                                                            <span class="hidden sm:inline">Previous</span>
                                                        </span>
                                                        <?php endif; ?>

                                                        <!-- Mobile: Page X of Y -->
                                                        <span
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-400 sm:hidden">
                                                            Page <?php echo esc_html($paged); ?> of
                                                            <?php echo esc_html($total_pages); ?>
                                                        </span>

                                                        <!-- Desktop: Page numbers -->
                                                        <ul class="hidden items-center gap-0.5 sm:flex">
                                                            <?php
                                                            $last_shown = 0;
                                                            foreach ($pages_to_show as $page_num):
                                                                if ($last_shown && ($page_num - $last_shown) > 1):
                                                                    ?>
                                                            <li>
                                                                <span
                                                                    class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-500 dark:text-gray-500 pointer-events-none">...</span>
                                                            </li>
                                                            <?php
                                                            endif;
                                                            $last_shown = $page_num;
                                                            $is_current = ($page_num === $paged);
                                                            ?>
                                                            <li>
                                                                <?php if ($is_current): ?>
                                                                <span
                                                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-500 text-sm font-medium text-white hover:bg-brand-500 hover:text-white">
                                                                    <?php echo esc_html($page_num); ?>
                                                                </span>
                                                                <?php else: ?>
                                                                <a href="<?php echo esc_url(VMS_CoreManager::build_pagination_url($page_num)); ?>"
                                                                    class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 hover:bg-brand-500 hover:text-white dark:text-gray-400 dark:hover:text-white">
                                                                    <?php echo esc_html($page_num); ?>
                                                                </a>
                                                                <?php endif; ?>
                                                            </li>
                                                            <?php endforeach; ?>
                                                        </ul>

                                                        <!-- Next Button -->
                                                        <?php if ($paged < $total_pages): ?>
                                                        <a href="<?php echo esc_url(VMS_CoreManager::build_pagination_url($paged + 1)); ?>"
                                                            class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5 sm:py-2.5">
                                                            <span class="hidden sm:inline">Next</span>
                                                            <svg class="fill-current" width="20" height="20"
                                                                viewBox="0 0 20 20" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M17.4165 9.9986C17.4168 10.1909 17.3437 10.3832 17.197 10.53L12.2004 15.5301C11.9076 15.8231 11.4327 15.8233 11.1397 15.5305C10.8467 15.2377 10.8465 14.7629 11.1393 14.4699L14.8594 10.7472L3.33203 10.7472C2.91782 10.7472 2.58203 10.4114 2.58203 9.99715C2.58203 9.58294 2.91782 9.24715 3.33203 9.24715L14.854 9.24715L11.1393 5.53016C10.8465 5.23717 10.8467 4.7623 11.1397 4.4695C11.4327 4.1767 11.9075 4.17685 12.2003 4.46984L17.1578 9.43049C17.3163 9.568 17.4165 9.77087 17.4165 9.99715C17.4165 9.99763 17.4165 9.99812 17.4165 9.9986Z"
                                                                    fill=""></path>
                                                            </svg>
                                                        </a>
                                                        <?php else: ?>
                                                        <span
                                                            class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-400 shadow-theme-xs sm:px-3.5 sm:py-2.5 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-600 cursor-not-allowed">
                                                            <span class="hidden sm:inline">Next</span>
                                                            <svg class="fill-current" width="20" height="20"
                                                                viewBox="0 0 20 20" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M17.4165 9.9986C17.4168 10.1909 17.3437 10.3832 17.197 10.53L12.2004 15.5301C11.9076 15.8231 11.4327 15.8233 11.1397 15.5305C10.8467 15.2377 10.8465 14.7629 11.1393 14.4699L14.8594 10.7472L3.33203 10.7472C2.91782 10.7472 2.58203 10.4114 2.58203 9.99715C2.58203 9.58294 2.91782 9.24715 3.33203 9.24715L14.854 9.24715L11.1393 5.53016C10.8465 5.23717 10.8467 4.7623 11.1397 4.4695C11.4327 4.1767 11.9075 4.17685 12.2003 4.46984L17.1578 9.43049C17.3163 9.568 17.4165 9.77087 17.4165 9.99715C17.4165 9.99763 17.4165 9.99812 17.4165 9.9986Z"
                                                                    fill=""></path>
                                                            </svg>
                                                        </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php endif; ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of Visits Section -->
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <!-- Main Content End -->

                <!-- BEGIN MODAL -->
                <?php get_template_part( 'template-parts/content/content', 'recip-visit-modal' ); ?>
                <!-- END MODAL -->

                <!-- ===== Footer Start ===== -->
                <?php get_template_part( 'template-parts/content/content', 'footer' ); ?>
                <!-- ===== Footer End ===== -->
            </div>
            <!-- Content Area End -->
        </div>
    </main>
</section>

<?php
get_footer();