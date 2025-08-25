<?php
/**
 * The template for displaying the Employee details page
 *
  * @package Visitor_Management_System
 */
use WyllyMk\VMS\VMS_CoreManager;

// Exit if accessed directly
defined('ABSPATH') || exit;

// Check if the current user is an Administrator or Manager or Advocate
if ( ! ( current_user_can( 'administrator' ) || current_user_can( 'general_manager' ) )  ) {
	// Redirect unauthorized users to the front page
	wp_redirect( home_url() );
	exit;
}

// Initialize message arrays
$lawyer_u_success = [];
$lawyer_u_error = [];
$lawyer_d_error = [];

// Process form submissions if user_id is set
if (isset($_GET['user_id']) && intval($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    $current_user = wp_get_current_user();
    $is_admin_or_manager = in_array('administrator', $current_user->roles) || in_array('general_manager', $current_user->roles);

    // Update User Data          
    if (isset($_POST['update_user']) && check_admin_referer('update_user_data', '_wpnonce_update_user_data')) {
        $user_data = get_userdata($user_id);

        // Update email if changed
        $new_email = sanitize_email($_POST['email']);
        if ($new_email !== $user_data->user_email) {
            $user_data->user_email = $new_email;
            $update_user_result = wp_update_user($user_data);
            if (!is_wp_error($update_user_result)) {
                $lawyer_u_success[] = 'Email updated successfully.';
            } else {
                $lawyer_u_error[] = 'User update error: ' . $update_user_result->get_error_message();
            }
        }

        // Update first name if changed
        $new_first_name = sanitize_text_field($_POST['first_name']);
        if ($new_first_name !== $user_data->first_name) {
            $user_data->first_name = $new_first_name;
            wp_update_user($user_data);
            $lawyer_u_success[] = 'First name updated successfully.';
        }

        // Update last name if changed
        $new_last_name = sanitize_text_field($_POST['last_name']);
        if ($new_last_name !== $user_data->last_name) {
            $user_data->last_name = $new_last_name;
            wp_update_user($user_data);
            $lawyer_u_success[] = 'Last name updated successfully.';
        }

        // Update display name if changed
        $new_display_name = sanitize_text_field($_POST['display_name']);
        if ($new_display_name !== $user_data->display_name) {
            $user_data->display_name = $new_display_name;
            wp_update_user($user_data);
            $lawyer_u_success[] = 'Display name updated successfully.';
        }

        // Update phone number if changed
        if (isset($_POST['pnumber'])) {
            $new_phone_number = sanitize_text_field($_POST['pnumber']);
            if ($new_phone_number !== get_user_meta($user_id, 'phone_number', true)) {
                update_user_meta($user_id, 'phone_number', $new_phone_number);
                $lawyer_u_success[] = 'Phone number updated successfully.';
            }
        }

        // Update registration status
        if (isset($_POST['registration_status'])) {
            $new_registration_status = sanitize_text_field($_POST['registration_status']);
            $current_registration_status = get_user_meta($user_id, 'registration_status', true);

            if ($new_registration_status !== $current_registration_status) {
                switch ($new_registration_status) {
                    case 'pending':
                        $lawyer_u_success[] = 'This account is now marked as pending.';
                        break;

                    case 'active':
                        $lawyer_u_success[] = 'This account has been activated and the user can now login successfully.';
                        break;

                    case 'suspended':
                        $lawyer_u_error[] = 'This account has been suspended and the user cannot login until reactivated.';
                        break;

                    case 'banned':
                        $lawyer_u_error[] = 'This account has been banned and the user cannot login permanently.';
                        break;

                    default:
                        $lawyer_u_error[] = 'Invalid account status provided.';
                        $new_registration_status = $current_registration_status; // fallback
                        break;
                }

                update_user_meta($user_id, 'registration_status', $new_registration_status);
            }
        }

                // Update receive messages preference
        $new_receive_messages = isset($_POST['receive_messages']) ? 'yes' : 'no';
        $current_receive_messages = get_user_meta($user_id, 'receive_messages', true);
        if ($new_receive_messages !== $current_receive_messages) {
            if ($new_receive_messages === 'no' && $current_receive_messages === 'yes') {
                $lawyer_u_error[] = 'This lawyer will no longer receive messages.';
            } elseif ($new_receive_messages === 'yes' && $current_receive_messages === 'no') {
                $lawyer_u_success[] = 'This lawyer will now receive messages.';
            }
            update_user_meta($user_id, 'receive_messages', $new_receive_messages);
        }

        // Update receive emails preference
        $new_receive_emails = isset($_POST['receive_emails']) ? 'yes' : 'no';
        $current_receive_emails = get_user_meta($user_id, 'receive_emails', true);
        if ($new_receive_emails !== $current_receive_emails) {
            if ($new_receive_emails === 'no' && $current_receive_emails === 'yes') {
                $lawyer_u_error[] = 'This lawyer will no longer receive emails.';
            } elseif ($new_receive_emails === 'yes' && $current_receive_emails === 'no') {
                $lawyer_u_success[] = 'This lawyer will now receive emails.';
            }
            update_user_meta($user_id, 'receive_emails', $new_receive_emails);
        }


        // Handle avatar upload
        if (isset($_FILES['profile_picture']) && !empty($_FILES['profile_picture']['name'])) {
            if (!function_exists('wp_handle_upload')) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }

            $uploadedfile = $_FILES['profile_picture'];
            $upload_overrides = ['test_form' => false];
            $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

            if ($movefile && !isset($movefile['error'])) {
                $avatar_url = esc_url($movefile['url']);
                update_user_meta($user_id, 'profile_picture', $avatar_url);
                
                $avatar_id = attachment_url_to_postid($avatar_url);
                if ($avatar_id) {
                    update_user_meta($user_id, '_wp_attachment_metadata', get_post_meta($avatar_id, '_wp_attachment_metadata', true));
                }
                $lawyer_u_success[] = 'User Profile Picture Updated successfully';
            }
        }

        // Store messages in transient
        set_transient('lawyer_u_success_' . get_current_user_id(), $lawyer_u_success, 60);
        set_transient('lawyer_u_error_' . get_current_user_id(), $lawyer_u_error, 60);

        wp_safe_redirect(add_query_arg(['user_id' => $user_id], site_url('/employee-details/')));
        exit;
    }

    // Delete User
    if (isset($_POST['delete_user']) && check_admin_referer('delete_user', '_wpnonce_delete_user')) {
        if ($user_id === get_current_user_id() || $is_admin_or_manager) {
            $first_name = get_user_meta($user_id, 'first_name', true);

            require_once ABSPATH . 'wp-admin/includes/user.php';
            wp_delete_user($user_id);

            $lawyers_error = [$first_name . "'s account has been deleted permanently"];
            set_transient('lawyers_error_' . get_current_user_id(), $lawyers_error, 60);

            if ($user_id === get_current_user_id()) {
                wp_logout();
            }

            wp_safe_redirect(site_url('/employees/'));
            exit;
        }
    }    

    // Get user data
    $user_data = get_userdata($user_id);
    $user_avatar = get_avatar_url($user_id);
    $user_phone_number = get_user_meta($user_id, 'phone_number', true);
    $member_number = get_user_meta($user_data->ID, 'member_number', true);
    $receive_messages = get_user_meta($user_id, 'receive_messages', true);
    $receive_emails = get_user_meta($user_id, 'receive_emails', true);
    $registration_status = get_user_meta($user_id, 'registration_status', true);

    // Get messages from transients
    $lawyer_u_success = get_transient('lawyer_u_success_' . get_current_user_id()) ?: [];
    $lawyer_u_error = get_transient('lawyer_u_error_' . get_current_user_id()) ?: [];
    $lawyer_d_error = get_transient('lawyer_d_error_' . get_current_user_id()) ?: [];

    // Clear the transients after displaying
    delete_transient('lawyer_u_success_' . get_current_user_id());
    delete_transient('lawyer_u_error_' . get_current_user_id());
    delete_transient('lawyer_d_error_' . get_current_user_id());
}

$guests = VMS_CoreManager::get_guests_by_host($user_id);

// Get pagination params
$paged     = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$per_page  = isset($_GET['per_page']) ? intval($_GET['per_page']) : 10;
$offset    = ($paged - 1) * $per_page;

// Count total visits for this member
global $wpdb;
$total_visits = (int) $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM {$wpdb->prefix}vms_visits WHERE member_id = %d",
    $member_id
));

$total_pages = ceil($total_visits / $per_page);

// Fetch paginated visits
$member_visits = $wpdb->get_results($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}vms_visits WHERE member_id = %d ORDER BY visit_date DESC LIMIT %d OFFSET %d",
    $member_id,
    $per_page,
    $offset
));

// Calculate row numbers for display
$current_start = $total_visits > 0 ? ($paged - 1) * $per_page + 1 : 0;
$row_number = $current_start;

// Build compact pagination array (1, ..., current-1, current, current+1, ..., last)
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

if (isset($_POST['delete_visit']) && isset($_POST['visit_id'])) {
    if (check_admin_referer('delete_visit_action', 'delete_visit_nonce')) {
        $visit_id = intval($_POST['visit_id']);
        global $wpdb;
        $wpdb->delete("{$wpdb->prefix}vms_visits", ['id' => $visit_id], ['%d']);
        wp_safe_redirect(remove_query_arg('paged')); // reload without pagination param
        exit;
    }
}

get_header();
?>

<section id="primary" x-data="{ page: 'employee-details', 'isVisitInfoModal': false}"
    @close-guest-modal.window="isVisitInfoModal = false">
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
                        <div x-data="{ pageName: `Employee-Details`}">
                            <?php get_template_part( 'template-parts/content/content', 'breadcrumb' ); ?>
                        </div>
                        <!-- Breadcrumb End -->
                        <div class="py-8 mx-auto">
                            <div class="flex justify-center">
                                <div class="w-full lg:w-5/6 xl:4/5 2xl:3/4">
                                    <!-- Personal Information -->
                                    <div x-data="{ open: localStorage.getItem('infoOpen') !== null ? localStorage.getItem('infoOpen') === 'true' : true }"
                                        class="rounded-2xl shadow-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                                        <!-- Header with click toggle functionality -->
                                        <div @click="open = !open; localStorage.setItem('infoOpen', open)"
                                            class="flex items-center justify-between px-6 py-4 cursor-pointer">
                                            <h3
                                                class="text-lg font-semibold font-oswald text-regal-blue dark:text-white">
                                                <?php esc_html_e( 'Personal Information', 'vms' ); ?>
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
                                            <!-- Success Alert -->
                                            <?php if (!empty($lawyer_u_success)) : ?>
                                            <?php foreach ((array)$lawyer_u_success as $message) : ?>
                                            <div class="flex items-center justify-between p-4 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500 rounded"
                                                role="alert">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    <div>
                                                        <p class="font-medium">
                                                            <?php esc_html_e('Success!', 'easy_manage'); ?></p>
                                                        <p class="text-sm"><?php echo esc_html($message); ?></p>
                                                    </div>
                                                </div>
                                                <button type="button" class="text-green-700 hover:text-green-900"
                                                    onclick="this.parentElement.style.display='none';">
                                                    <span
                                                        class="sr-only"><?php esc_html_e('Close', 'easy_manage'); ?></span>
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <?php endforeach; ?>
                                            <?php endif; ?>

                                            <!-- Error Alert -->
                                            <?php if (!empty($lawyer_u_error)) : ?>
                                            <?php foreach ((array)$lawyer_u_error as $message) : ?>
                                            <div class="flex items-center justify-between p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500 rounded"
                                                role="alert">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    <div>
                                                        <p class="font-medium">
                                                            <?php esc_html_e('Error!', 'easy_manage'); ?></p>
                                                        <p class="text-sm"><?php echo esc_html($message); ?></p>
                                                    </div>
                                                </div>
                                                <button type="button" class="text-red-700 hover:text-red-900"
                                                    onclick="this.parentElement.style.display='none';">
                                                    <span
                                                        class="sr-only"><?php esc_html_e('Close', 'easy_manage'); ?></span>
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <?php endforeach; ?>
                                            <?php endif; ?>

                                            <form action="" method="post" enctype="multipart/form-data">
                                                <div class="flex flex-col items-center mb-4">
                                                    <div class="relative">
                                                        <img class="object-cover w-24 h-24 border-2 border-gray-200 rounded-full dark:border-gray-700"
                                                            src="<?php echo esc_url(get_user_meta($user_id, 'profile_picture', true) ?: get_avatar_url($user_id)); ?>"
                                                            alt="Profile Picture">
                                                        <div
                                                            class="absolute flex items-center justify-center p-1 bg-opacity-50 rounded-full cursor-pointer bottom-2 right-2 hover:bg-opacity-75">
                                                            <label for="profile_picture"
                                                                class="text-black cursor-pointer">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 24 24" width="20" height="20"
                                                                    fill="currentColor" class="text-xl">
                                                                    <path
                                                                        d="M16.585 3.414a2 2 0 0 0-2.828 0l-10 10a2 2 0 0 0-.484.797l-2 6a2 2 0 0 0 2.397 2.397l6-2a2 2 0 0 0 .797-.484l10-10a2 2 0 0 0 0-2.828l-2-2a2 2 0 0 0-2.828 0L14 7.586 16.585 3.414zM14 7.586l-2 2-1-1 2-2a1 1 0 0 1 1.414 1.414l-2 2zM4 19l1.5-4.5L14 7.586l3.414 3.414-10 10L4 19z" />
                                                                </svg>
                                                            </label>
                                                            <input class="hidden" id="profile_picture" type="file"
                                                                accept="image/*" name="profile_picture">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                                    <!-- User Name field -->
                                                    <div class="mb-4">
                                                        <label for="uname"
                                                            class="block text-gray-700 dark:text-gray-300">
                                                            <?php esc_html_e( 'User Name:', 'vms' ); ?>
                                                        </label>
                                                        <input type="text"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            id="uname"
                                                            value="<?php echo esc_attr($user_data->user_login); ?>"
                                                            name="user_name" disabled>
                                                        <small class="text-sm text-gray-600 dark:text-gray-400">
                                                            <?php esc_html_e( 'Usernames cannot be changed.', 'vms' ); ?>
                                                        </small>
                                                    </div>
                                                    <!-- Email field -->
                                                    <div class="mb-4">
                                                        <label for="email"
                                                            class="block text-gray-700 dark:text-gray-300">
                                                            <?php esc_html_e( 'Email (required):', 'vms' ); ?>
                                                        </label>
                                                        <input type="text"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            id="email"
                                                            value="<?php echo esc_attr($user_data->user_email); ?>"
                                                            name="email"
                                                            <?php echo !$is_admin_or_manager ? 'disabled' : ''; ?>>
                                                        <small class="text-sm text-gray-600 dark:text-gray-400">
                                                            <?php esc_html_e( 'If you change this, an email will be sent to confirm it.', 'vms' ); ?>
                                                        </small>
                                                    </div>
                                                    <!-- First Name field -->
                                                    <div class="mb-4">
                                                        <label for="fname"
                                                            class="block text-gray-700 dark:text-gray-300">
                                                            <?php esc_html_e( 'First Name:', 'vms' ); ?>
                                                        </label>
                                                        <input type="text"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            id="fname"
                                                            value="<?php echo esc_attr($user_data->first_name); ?>"
                                                            name="first_name"
                                                            <?php echo !$is_admin_or_manager ? 'disabled' : ''; ?>>
                                                    </div>
                                                    <!-- Last Name field -->
                                                    <div class="mb-4">
                                                        <label for="lname"
                                                            class="block text-gray-700 dark:text-gray-300">
                                                            <?php esc_html_e( 'Last Name:', 'vms' ); ?>
                                                        </label>
                                                        <input type="text"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            id="lname"
                                                            value="<?php echo esc_attr($user_data->last_name); ?>"
                                                            name="last_name"
                                                            <?php echo !$is_admin_or_manager ? 'disabled' : ''; ?>>
                                                    </div>
                                                    <!-- Member Number -->
                                                    <div class="mb-4">
                                                        <label for="member_number"
                                                            class="block text-gray-700 dark:text-gray-300">
                                                            <?php esc_html_e( 'Member Number', 'vms' ); ?>
                                                        </label>
                                                        <input type="text"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            id="member_number"
                                                            value="<?php echo esc_attr($member_number); ?>"
                                                            name="member_number"
                                                            <?php echo !$is_admin_or_manager ? 'disabled' : ''; ?>>
                                                    </div>
                                                    <!-- Phone Number field -->
                                                    <div class="mb-4">
                                                        <label for="number"
                                                            class="block text-gray-700 dark:text-gray-300">
                                                            <?php esc_html_e( 'Phone Number:', 'vms' ); ?>
                                                        </label>
                                                        <input type="number"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            id="number"
                                                            value="<?php echo esc_attr($user_phone_number); ?>"
                                                            name="pnumber"
                                                            <?php echo !$is_admin_or_manager ? 'disabled' : ''; ?>>
                                                    </div>
                                                    <!-- Status -->
                                                    <div class="mb-4">
                                                        <label for="registration_status"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            <?php esc_html_e( 'Account Status:', 'vms' ); ?>
                                                        </label>

                                                        <?php
                                                        // Get current registration_status from usermeta
                                                        $registration_status = get_user_meta( $user_id, 'registration_status', true );

                                                        // If none found (new member), default to 'pending'
                                                        if ( empty( $registration_status ) ) {
                                                            $registration_status = 'pending';
                                                        }

                                                        // Disable if not admin/manager
                                                        $disabled = ( ! $is_admin_or_manager ) ? 'disabled' : '';
                                                        ?>

                                                        <div x-data="{ isOptionSelected: true }"
                                                            class="relative z-20 bg-transparent">
                                                            <select id="registration_status" name="registration_status"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90            dark:placeholder:text-white/30"
                                                                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                                                @change="isOptionSelected = true"
                                                                <?php echo $disabled; ?>>

                                                                <option value="pending"
                                                                    <?php selected( $registration_status, 'pending' ); ?>>
                                                                    Pending</option>
                                                                <option value="active"
                                                                    <?php selected( $registration_status, 'active' ); ?>>
                                                                    Active</option>
                                                                <option value="suspended"
                                                                    <?php selected( $registration_status, 'suspended' ); ?>>
                                                                    Suspended</option>
                                                                <option value="banned"
                                                                    <?php selected( $registration_status, 'banned' ); ?>>
                                                                    Banned</option>
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

                                                    <!-- Message Email, Deactivate fields -->
                                                    <div class="col-span-1 md:col-span-2">
                                                        <hr class="my-4 border-gray-300 dark:border-gray-600">
                                                        <div class="flex items-center justify-between mb-4">
                                                            <label for="message"
                                                                class="block text-gray-700 dark:text-gray-300">
                                                                <?php esc_html_e( 'Receive Communication Messages?', 'vms' ); ?>
                                                            </label>
                                                            <?php
                                                            $receive_messages = get_user_meta($user_id, 'receive_messages', true);
                                                            $checked = ($receive_messages === 'yes') ? 'checked' : '';
                                                            $disabled = (!$is_admin_or_manager) ? 'disabled' : '';
                                                            ?>
                                                            <input type="checkbox"
                                                                class="px-4 py-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                                id="message" name="receive_messages" value="yes"
                                                                <?php echo $checked; ?> <?php echo $disabled; ?>>
                                                        </div>
                                                        <hr class="my-4 border-gray-300 dark:border-gray-600">
                                                        <div class="flex items-center justify-between mb-4">
                                                            <label for="email_comm"
                                                                class="block text-gray-700 dark:text-gray-300">
                                                                <?php esc_html_e( 'Receive Communication Emails?', 'vms' ); ?>
                                                            </label>
                                                            <?php
                                                            $receive_emails = get_user_meta($user_id, 'receive_emails', true);
                                                            $checked = ($receive_emails === 'yes') ? 'checked' : '';
                                                            $disabled = (!$is_admin_or_manager) ? 'disabled' : '';
                                                            ?>
                                                            <input type="checkbox"
                                                                class="px-4 py-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                                id="email_comm" name="receive_emails" value="yes"
                                                                <?php echo $checked; ?> <?php echo $disabled; ?>>

                                                        </div>
                                                        <hr class="my-4 border-gray-300 dark:border-gray-600">
                                                    </div>
                                                </div>
                                                <?php if ($is_admin_or_manager) : ?>
                                                <?php wp_nonce_field('update_user_data', '_wpnonce_update_user_data'); ?>
                                                <?php wp_nonce_field('delete_user', '_wpnonce_delete_user'); ?>
                                                <div class="flex justify-center mt-4 space-x-2">
                                                    <button type="submit" name="update_user"
                                                        class="px-4 py-2 font-semibold text-white bg-blue-600 rounded cursor-pointer hover:bg-blue-700">
                                                        <?php esc_html_e( 'Update Details', 'vms' ); ?>
                                                    </button>
                                                    <button type="reset"
                                                        class="px-4 py-2 font-semibold text-white bg-gray-600 rounded cursor-pointer hover:bg-gray-700">
                                                        <?php esc_html_e( 'Reset', 'vms' ); ?>
                                                    </button>
                                                    <button type="submit" name="delete_user"
                                                        class="px-4 py-2 font-semibold text-white bg-red-600 rounded cursor-pointer hover:bg-red-700"
                                                        onclick="return confirm('Are you sure you want to delete your account? This action is irreversible.')">
                                                        <?php esc_html_e( 'Delete Account', 'vms' ); ?>
                                                    </button>
                                                </div>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- End of Personal Information Section -->
                                    <!-- Guests Section -->
                                    <div class="mt-10" id="guests-section">
                                        <div
                                            class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                                            <div class="flex items-center justify-between px-5 py-4 sm:px-6 sm:py-5">
                                                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                                                    <?php esc_html_e('Invited Guests', 'vms'); ?>
                                                </h3>
                                                <div class="flex items-center justify-end w-full md:w-1/2">
                                                    <a @click="isVisitInfoModal = true"
                                                        class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg cursor-pointer bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                                                        <?php esc_html_e('Register New Visit', 'vms'); ?>
                                                    </a>
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
                                                                    onchange="window.location.href = '<?php echo VMS_CoreManager::build_per_page_url(''); ?>' + this.value">
                                                                    <option value="10"
                                                                        <?php selected($per_page, 10); ?>>10</option>
                                                                    <option value="8" <?php selected($per_page, 8); ?>>8
                                                                    </option>
                                                                    <option value="5" <?php selected($per_page, 5); ?>>5
                                                                    </option>
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
                                                                            stroke-linejoin="round" />
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                            <span
                                                                class="text-gray-500 dark:text-gray-400">entries</span>
                                                        </div>
                                                    </div>

                                                    <!-- Table -->
                                                    <div class="max-w-full overflow-x-auto">
                                                        <div id="guests-table" class="min-w-[1102px]">

                                                            <!-- Table Header -->
                                                            <div id="guests-header"
                                                                class="grid grid-cols-12 border-t border-gray-200 dark:border-gray-800">
                                                                <!-- # -->
                                                                <div
                                                                    class="col-span-1 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                        #</p>
                                                                </div>
                                                                <div
                                                                    class="col-span-2 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                        Guest Name</p>
                                                                </div>
                                                                <div
                                                                    class="col-span-2 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                        Visit Date</p>
                                                                </div>
                                                                <div
                                                                    class="col-span-1 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                        Sign In</p>
                                                                </div>
                                                                <div
                                                                    class="col-span-1 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
                                                                    <p
                                                                        class="text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                        Sign Out</p>
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
                                                                <div class="col-span-1 flex items-center px-4 py-3">
                                                                    <p
                                                                        class="text-theme-xs font-medium text-gray-700 dark:text-gray-400">
                                                                        Action</p>
                                                                </div>
                                                            </div>

                                                            <!-- Table Body -->
                                                            <div id="guests-body">
                                                                <?php if (!empty($guests)): ?>
                                                                <?php foreach ($guests as $guest): ?>
                                                                <div id="guest-div-<?php echo esc_attr($guest->id); ?>"
                                                                    class="grid grid-cols-12 border-t border-gray-100 dark:border-gray-800">
                                                                    <!-- Row Number -->
                                                                    <div
                                                                        class="col-span-1 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                        <p
                                                                            class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                                            <?php echo esc_html($row_number++); ?>
                                                                        </p>
                                                                    </div>
                                                                    <!-- Guest Name -->
                                                                    <div
                                                                        class="col-span-2 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                        <p
                                                                            class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                                            <?php echo esc_html($guest->first_name . ' ' . $guest->last_name); ?>
                                                                        </p>
                                                                    </div>

                                                                    <!-- Visit Date -->
                                                                    <div
                                                                        class="col-span-2 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                        <p
                                                                            class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                                            <?php echo esc_html(VMS_CoreManager::format_date($guest->visit_date)); ?>
                                                                        </p>
                                                                    </div>

                                                                    <!-- Sign In -->
                                                                    <div
                                                                        class="col-span-1 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                        <p
                                                                            class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                                            <?php echo esc_html(VMS_CoreManager::format_time($guest->sign_in_time)); ?>
                                                                        </p>
                                                                    </div>

                                                                    <!-- Sign Out -->
                                                                    <div
                                                                        class="col-span-1 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                        <p
                                                                            class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                                            <?php echo esc_html(VMS_CoreManager::format_time($guest->sign_out_time)); ?>
                                                                        </p>
                                                                    </div>

                                                                    <!-- Duration -->
                                                                    <div
                                                                        class="col-span-2 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
                                                                        <p
                                                                            class="text-theme-sm text-gray-700 dark:text-gray-400">
                                                                            <?php echo esc_html(VMS_CoreManager::calculate_duration($guest->sign_in_time, $guest->sign_out_time)); ?>
                                                                        </p>
                                                                    </div>

                                                                    <!-- Status -->
                                                                    <div class="col-span-2 flex items-center px-4 py-3">
                                                                        <?php
                                                                        $status = VMS_CoreManager::get_visit_status($guest->visit_date, $guest->sign_in_time, $guest->sign_out_time);
                                                                        $status_class = VMS_CoreManager::get_status_class($status);
                                                                        $status_text  = VMS_CoreManager::get_status_text($status);
                                                                        ?>
                                                                        <span
                                                                            class="<?php echo esc_attr($status_class); ?> inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-sm font-medium">
                                                                            <?php echo esc_html($status_text); ?>
                                                                        </span>
                                                                    </div>

                                                                    <!-- Action -->
                                                                    <div class="col-span-1 px-4 py-3">
                                                                        <form method="post"
                                                                            onsubmit="return confirm('Are you sure you want to delete this visit?');">
                                                                            <input type="hidden" name="visit_id"
                                                                                value="<?php echo esc_attr($guest->id); ?>">
                                                                            <?php wp_nonce_field('delete_visit_action', 'delete_visit_nonce'); ?>
                                                                            <button type="submit" name="delete_visit"
                                                                                class="px-3 py-1 text-xs font-medium text-white bg-red-500 rounded-lg hover:bg-red-600">
                                                                                Delete
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <?php endforeach; ?>
                                                                <?php else: ?>
                                                                <div id="no-guests-div"
                                                                    class="border-t border-gray-100 px-4 py-8 text-center dark:border-gray-800">
                                                                    <p class="text-gray-500 dark:text-gray-400">No
                                                                        guests found</p>
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
                                                                if ($last_shown && ($page_num - $last_shown) > 1): ?>
                                                            <li>
                                                                <span
                                                                    class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-500 dark:text-gray-500 pointer-events-none">...</span>
                                                            </li>
                                                            <?php endif;
                                                            $last_shown = $page_num;
                                                            $is_current = ($page_num === $paged);
                                                            ?>
                                                            <li>
                                                                <?php if ($is_current): ?>
                                                                <span
                                                                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-500 text-sm font-medium text-white">
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
                                                    <?php else: ?>
                                                    <!-- No pagination needed, but show entry count -->
                                                    <div class="px-6 py-4">
                                                        <div
                                                            class="text-sm text-gray-500 dark:text-gray-400 text-center">
                                                            <?php if ($total_visits > 0): ?>
                                                            Showing all <?php echo esc_html($total_visits); ?> entries
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of Guests Section -->

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BEGIN MODAL -->
                    <?php get_template_part( 'template-parts/content/content', 'visit-modal' ); ?>
                    <!-- END MODAL -->

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