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
if ( ! ( current_user_can( 'administrator' ) || current_user_can( 'managing_partner' ) || current_user_can( 'senior_partner' ) || current_user_can( 'advocate' ) || current_user_can( 'pupil' ) ) ) {
	// Redirect unauthorized users to the front page
	wp_redirect( home_url() );
	exit;
}

// Initialize message arrays
$guest_u_success = [];
$guest_u_error = [];
$guest_d_error = [];
$guest_p_success = [];
$guest_p_error = [];
$is_admin_or_manager;

// Process form submissions if user_id is set
if (isset($_GET['user_id']) && intval($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    $current_user = wp_get_current_user();
    $is_admin_or_manager = in_array('administrator', $current_user->roles) || in_array('manager', $current_user->roles);

    // Update User Data          
    if (isset($_POST['update_user']) && check_admin_referer('update_user_data', '_wpnonce_update_user_data')) {
        $user_data = get_userdata($user_id);

        // Update email if changed
        $new_email = sanitize_email($_POST['email']);
        if ($new_email !== $user_data->user_email) {
            $user_data->user_email = $new_email;
            $update_user_result = wp_update_user($user_data);
            if (!is_wp_error($update_user_result)) {
                $guest_u_success[] = 'Email updated successfully.';
            } else {
                $guest_u_error[] = 'User update error: ' . $update_user_result->get_error_message();
            }
        }

        // Update first name if changed
        $new_first_name = sanitize_text_field($_POST['first_name']);
        if ($new_first_name !== $user_data->first_name) {
            $user_data->first_name = $new_first_name;
            wp_update_user($user_data);
            $guest_u_success[] = 'First name updated successfully.';
        }

        // Update last name if changed
        $new_last_name = sanitize_text_field($_POST['last_name']);
        if ($new_last_name !== $user_data->last_name) {
            $user_data->last_name = $new_last_name;
            wp_update_user($user_data);
            $guest_u_success[] = 'Last name updated successfully.';
        }

        // Update display name if changed
        $new_display_name = sanitize_text_field($_POST['display_name']);
        if ($new_display_name !== $user_data->display_name) {
            $user_data->display_name = $new_display_name;
            wp_update_user($user_data);
            $guest_u_success[] = 'Display name updated successfully.';
        }

        // Update phone number if changed
        if (isset($_POST['pnumber'])) {
            $new_phone_number = sanitize_text_field($_POST['pnumber']);
            if ($new_phone_number !== get_user_meta($user_id, 'phone_number', true)) {
                update_user_meta($user_id, 'phone_number', $new_phone_number);
                $guest_u_success[] = 'Phone number updated successfully.';
            }
        }

        // Update receive messages preference
        $new_receive_messages = isset($_POST['receive_messages']) ? 'yes' : 'no';
        $current_receive_messages = get_user_meta($user_id, 'receive_messages', true);
        if ($new_receive_messages !== $current_receive_messages) {
            if ($new_receive_messages === 'no' && $current_receive_messages === 'yes') {
                $guest_u_error[] = 'This guest will no longer receive messages.';
            } elseif ($new_receive_messages === 'yes' && $current_receive_messages === 'no') {
                $guest_u_success[] = 'This guest will now receive messages.';
            }
            update_user_meta($user_id, 'receive_messages', $new_receive_messages);
        }

        // Update receive emails preference
        $new_receive_emails = isset($_POST['receive_emails']) ? 'yes' : 'no';
        $current_receive_emails = get_user_meta($user_id, 'receive_emails', true);
        if ($new_receive_emails !== $current_receive_emails) {
            if ($new_receive_emails === 'no' && $current_receive_emails === 'yes') {
                $guest_u_error[] = 'This guest will no longer receive emails.';
            } elseif ($new_receive_emails === 'yes' && $current_receive_emails === 'no') {
                $guest_u_success[] = 'This guest will now receive emails.';
            }
            update_user_meta($user_id, 'receive_emails', $new_receive_emails);
        }

        // Update registration status
        $new_registration_status = isset($_POST['registration_status']) && $_POST['registration_status'] === 'inactive' ? 'inactive' : 'active';
        $current_registration_status = get_user_meta($user_id, 'registration_status', true);
        if ($new_registration_status !== $current_registration_status) {
            if ($new_registration_status === 'inactive' && $current_registration_status === 'active') {
                $guest_u_error[] = 'This account has been deactivated and the user can no longer login.';
            } elseif ($new_registration_status === 'active' && $current_registration_status === 'inactive') {
                $guest_u_success[] = 'This account has been activated and the user can now login successfully.';
            }
            update_user_meta($user_id, 'registration_status', $new_registration_status);
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
                $guest_u_success[] = 'User Profile Picture Updated successfully';
            }
        }

        // Store messages in session
        $_SESSION['guest_u_success'] = $guest_u_success;
        $_SESSION['guest_u_error'] = $guest_u_error;

        wp_safe_redirect(add_query_arg(['user_id' => $user_id], site_url('/guest-details')));
        exit;
    }

    // Delete User
    if (isset($_POST['delete_user']) && check_admin_referer('delete_user', '_wpnonce_delete_user')) {
        if ($user_id === get_current_user_id() || $is_admin_or_manager) {
            $first_name = get_user_meta($user_id, 'first_name', true);

            require_once ABSPATH . 'wp-admin/includes/user.php';
            wp_delete_user($user_id);

            $_SESSION['guests_error'] = [$first_name . "'s account has been deleted permanently"];

            if ($user_id === get_current_user_id()) {
                wp_logout();
            }

            wp_safe_redirect(site_url('/guests'));
            exit;
        }
    }

    // Handle PDF deletion
    if (isset($_POST['delete_document']) && check_admin_referer('delete_documents', '_wpnonce_delete_documents')) {
        $pdf_id_to_delete = intval($_POST['delete_pdf']);

        $uploaded_pdfs = get_user_meta($user_id, 'uploaded_pdfs', true);
        $uploaded_pdfs = maybe_unserialize($uploaded_pdfs);

        if (is_array($uploaded_pdfs) && !empty($uploaded_pdfs)) {
            $pdf_entry_key = null;
            foreach ($uploaded_pdfs as $key => $entry) {
                if (is_array($entry) && isset($entry['pdf_id']) && $entry['pdf_id'] == $pdf_id_to_delete) {
                    $pdf_entry_key = $key;
                    break;
                }
            }

            if ($pdf_entry_key !== null) {
                $pdf_url = wp_get_attachment_url($pdf_id_to_delete);
                $filename = basename($pdf_url);

                if (wp_delete_attachment($pdf_id_to_delete, true)) {
                    unset($uploaded_pdfs[$pdf_entry_key]);
                    $uploaded_pdfs = array_values($uploaded_pdfs);
                    update_user_meta($user_id, 'uploaded_pdfs', $uploaded_pdfs);

                    delete_post_meta($pdf_id_to_delete, 'uploaded_on');
                    delete_post_meta($pdf_id_to_delete, 'uploaded_by');

                    $guest_d_success[] = 'Title Document: ' . esc_html($filename) . ' successfully deleted.';
                } else {
                    $guest_d_error[] = 'Error: Failed to delete the document attachment.';
                }
            } else {
                $guest_d_error[] = 'Error: Could not find the document to delete.';
            }
        } else {
            $guest_d_error[] = 'Error: No uploaded documents found for this guest.';
        }

        $_SESSION['guest_d_success'] = $guest_d_success;
        $_SESSION['guest_d_error'] = $guest_d_error;
        
        wp_safe_redirect(add_query_arg(['user_id' => $user_id], site_url('/guest-details')));
        exit;
    }

    // Handle document uploads
    if (isset($_POST['upload_documents']) && check_admin_referer('upload_documents', '_wpnonce_upload_documents')) {
        // Update guest status
        if (isset($_POST['guest_status'])) {
            $new_status = sanitize_text_field($_POST['guest_status']);
            update_user_meta($user_id, 'guest_status', $new_status);
            $guest_d_success[] = 'Guest status updated successfully.';
        }

        // Handle PDF uploads
        if (!empty($_FILES['pdf_files']['name'][0])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            
            $uploaded_pdfs = get_user_meta($user_id, 'uploaded_pdfs', true) ?: array();
            
            foreach ($_FILES['pdf_files']['name'] as $key => $value) {
                if ($_FILES['pdf_files']['name'][$key]) {
                    $file = array(
                        'name'     => $_FILES['pdf_files']['name'][$key],
                        'type'     => $_FILES['pdf_files']['type'][$key],
                        'tmp_name' => $_FILES['pdf_files']['tmp_name'][$key],
                        'error'    => $_FILES['pdf_files']['error'][$key],
                        'size'     => $_FILES['pdf_files']['size'][$key]
                    );
                    
                    $upload_overrides = array('test_form' => false);
                    $movefile = wp_handle_upload($file, $upload_overrides);
                    
                    if ($movefile && !isset($movefile['error'])) {
                        $wp_upload_dir = wp_upload_dir();
                        $attachment = array(
                            'guid'           => $wp_upload_dir['url'] . '/' . basename($movefile['file']),
                            'post_mime_type' => $movefile['type'],
                            'post_title'     => preg_replace('/\.[^.]+$/', '', basename($movefile['file'])),
                            'post_content'   => '',
                            'post_status'    => 'inherit'
                        );
                        
                        $attach_id = wp_insert_attachment($attachment, $movefile['file']);
                        
                        if (!is_wp_error($attach_id)) {
                            $attach_data = wp_generate_attachment_metadata($attach_id, $movefile['file']);
                            wp_update_attachment_metadata($attach_id, $attach_data);
                            
                            $upload_entry = array(
                                'pdf_id' => $attach_id,
                                'timestamp' => current_time('mysql'),
                                'uploaded_by' => get_current_user_id()
                            );
                            
                            array_push($uploaded_pdfs, $upload_entry);
                            $guest_d_success[] = 'Document ' . esc_html($file['name']) . ' uploaded successfully.';
                        }
                    } else {
                        $guest_d_error[] = 'Error uploading ' . esc_html($file['name']) . ': ' . $movefile['error'];
                    }
                }
            }
            
            update_user_meta($user_id, 'uploaded_pdfs', $uploaded_pdfs);
        }
        
        $_SESSION['guest_d_success'] = $guest_d_success;
        $_SESSION['guest_d_error'] = $guest_d_error;
        
        wp_safe_redirect(add_query_arg(['user_id' => $user_id], site_url('/guest-details')));
        exit;
    }

    // Handle payment saving
    if (isset($_POST['save_payment']) && check_admin_referer('mpesa_payment', '_wpnonce_mpesa_payment')) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'transactions';
        
        $amount = sanitize_text_field( $_POST['mpesa_amount'] );
        $user_phone_number = get_user_meta($user_id, 'phone_number', true);

        
        if ($amount > 0) {
            $wpdb->insert(
                $table_name,
                array(
                    'user_id' => $user_id,
                    'amount' => $amount,
                    'phone_number' => $user_phone_number,
                    'created_at' => current_time('mysql')
                ),
                array('%d', '%f', '%d', '%s')
            );
            
            if ($wpdb->insert_id) {
                $guest_p_success[] = 'Payment of ' . number_format($amount, 2) . ' saved successfully.';
            } else {
                $guest_p_error[] = 'Error saving payment. Please try again.';
            }
        } else {
            $guest_p_error[] = 'Invalid payment amount. Please enter a positive number.';
        }
        
        $_SESSION['guest_p_success'] = $guest_p_success;
        $_SESSION['guest_p_error'] = $guest_p_error;
        
        wp_safe_redirect(add_query_arg(['user_id' => $user_id], site_url('/guest-details')));
        exit;
    }   
    
    // Get messages from session if they exist
    if (isset($_SESSION['guest_u_success'])) {
        $guest_u_success = $_SESSION['guest_u_success'];
        unset($_SESSION['guest_u_success']);
    }
    if (isset($_SESSION['guest_u_error'])) {
        $guest_u_error = $_SESSION['guest_u_error'];
        unset($_SESSION['guest_u_error']);
    }
    if (isset($_SESSION['guest_d_success'])) {
        $guest_d_success = $_SESSION['guest_d_success'];
        unset($_SESSION['guest_d_success']);
    }
    if (isset($_SESSION['guest_d_error'])) {
        $guest_d_error = $_SESSION['guest_d_error'];
        unset($_SESSION['guest_d_error']);
    }
    if (isset($_SESSION['guest_p_success'])) {
        $guest_p_success = $_SESSION['guest_p_success'];
        unset($_SESSION['guest_p_success']);
    }
    if (isset($_SESSION['guest_p_error'])) {
        $guest_p_error = $_SESSION['guest_p_error'];
        unset($_SESSION['guest_p_error']);
    }

    // Get user data
    $user_data = get_userdata($user_id);
    $user_avatar = get_avatar_url($user_id);
    $user_phone_number = get_user_meta($user_id, 'phone_number', true);
    $guest_status = get_user_meta($user_id, 'guest_status', true);
    $receive_messages = get_user_meta($user_id, 'receive_messages', true);
    $receive_emails = get_user_meta($user_id, 'receive_emails', true);
    $registration_status = get_user_meta($user_id, 'registration_status', true);
    $uploaded_pdfs = get_user_meta($user_id, 'uploaded_pdfs', true) ?: array();
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

                                            <form action="" method="post" enctype="multipart/form-data">
                                                <div class="flex flex-col items-center mb-4">
                                                    <div class="relative">
                                                        <!-- Display the uploaded profile picture if available, or fall back to default avatar -->
                                                        <img class="object-cover w-24 h-24 border-2 border-gray-200 rounded-full dark:border-gray-700"
                                                            src="<?php echo esc_url(get_user_meta($user_id, 'profile_picture', true) ?: get_avatar_url($user_id)); ?>"
                                                            alt="Profile Picture">
                                                        <!-- Positioned at the bottom-right inside the profile picture -->
                                                        <div
                                                            class="absolute flex items-center justify-center p-1 bg-opacity-50 rounded-full cursor-pointer bottom-2 right-2 hover:bg-opacity-75">
                                                            <label for="profile_picture"
                                                                class="text-black cursor-pointer">
                                                                <!-- Edit icon (SVG for pencil) -->
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
                                                            User Name:
                                                        </label>
                                                        <input type="text"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            id="uname"
                                                            value="<?php echo esc_attr($user_data->user_login); ?>"
                                                            name="user_name" disabled>
                                                        <small class="text-sm text-gray-600 dark:text-gray-400">
                                                            Usernames cannot be changed.
                                                        </small>
                                                    </div>
                                                    <!-- End of User Name field -->
                                                    <!-- Email field -->
                                                    <div class="mb-4">
                                                        <label for="email"
                                                            class="block text-gray-700 dark:text-gray-300">
                                                            Email (required):
                                                        </label>
                                                        <input type="text"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            id="email"
                                                            value="<?php echo esc_attr($user_data->user_email); ?>"
                                                            name="email"
                                                            <?php echo !$is_admin_or_manager ? 'disabled' : ''; ?>>
                                                        <small class="text-sm text-gray-600 dark:text-gray-400">
                                                            If you change this, an email will be sent to confirm it.
                                                        </small>
                                                    </div>
                                                    <!-- End of Email field -->
                                                    <!-- First Name field -->
                                                    <div class="mb-4">
                                                        <label for="fname"
                                                            class="block text-gray-700 dark:text-gray-300">
                                                            First Name:
                                                        </label>
                                                        <input type="text"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            id="fname"
                                                            value="<?php echo esc_attr($user_data->first_name); ?>"
                                                            name="first_name"
                                                            <?php echo !$is_admin_or_manager ? 'disabled' : ''; ?>>
                                                    </div>
                                                    <!-- End of First Name field -->
                                                    <!-- Last Name field -->
                                                    <div class="mb-4">
                                                        <label for="lname"
                                                            class="block text-gray-700 dark:text-gray-300">
                                                            Last Name:
                                                        </label>
                                                        <input type="text"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            id="lname"
                                                            value="<?php echo esc_attr($user_data->last_name); ?>"
                                                            name="last_name"
                                                            <?php echo !$is_admin_or_manager ? 'disabled' : ''; ?>>
                                                    </div>
                                                    <!-- End of Last Name field -->
                                                    <!-- Display name field -->
                                                    <div class="mb-4">
                                                        <label for="display_name"
                                                            class="block text-gray-700 dark:text-gray-300">
                                                            Display Name (required):
                                                        </label>
                                                        <input type="text"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            id="display_name"
                                                            value="<?php echo esc_attr($user_data->display_name); ?>"
                                                            name="display_name"
                                                            <?php echo !$is_admin_or_manager ? 'disabled' : ''; ?>>
                                                    </div>
                                                    <!-- End of Display name field -->
                                                    <!-- Phone Number field -->
                                                    <div class="mb-4">
                                                        <label for="number"
                                                            class="block text-gray-700 dark:text-gray-300">
                                                            Phone Number:
                                                        </label>
                                                        <input type="number"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            id="number"
                                                            value="<?php echo esc_attr($user_phone_number); ?>"
                                                            name="pnumber"
                                                            <?php echo !$is_admin_or_manager ? 'disabled' : ''; ?>>
                                                    </div>
                                                    <!-- End of Phone Number field -->
                                                    <!-- Message Email, Deactivate fields -->
                                                    <div class="col-span-1 md:col-span-2">
                                                        <!-- Checkbox for receiving communication messages -->
                                                        <hr class="my-4 border-gray-300 dark:border-gray-600">
                                                        <!-- Horizontal line -->
                                                        <div class="flex items-center justify-between mb-4">
                                                            <label for="message"
                                                                class="block text-gray-700 dark:text-gray-300">
                                                                Receive Communication Messages?
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
                                                        <!-- Horizontal line -->

                                                        <!-- Checkbox for receiving communication emails -->
                                                        <hr class="my-4 border-gray-300 dark:border-gray-600">
                                                        <!-- Horizontal line -->
                                                        <div class="flex items-center justify-between mb-4">
                                                            <label for="email_comm"
                                                                class="block text-gray-700 dark:text-gray-300">
                                                                Receive Communication Emails?
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
                                                        <!-- Horizontal line -->

                                                        <!-- Checkbox for Deactivate Guest -->
                                                        <hr class="my-4 border-gray-300 dark:border-gray-600">
                                                        <!-- Horizontal line -->
                                                        <div class="flex items-center justify-between mb-4">
                                                            <label for="registration_status"
                                                                class="block text-gray-700 dark:text-gray-300">
                                                                Deactivate Guest?
                                                            </label>
                                                            <?php
                                                            $registration_status = get_user_meta($user_id, 'registration_status', true);
                                                            $checked = ($registration_status === 'inactive') ? 'checked' : '';
                                                            $disabled = (!$is_admin_or_manager) ? 'disabled' : '';
                                                            ?>
                                                            <input type="checkbox"
                                                                class="px-4 py-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                                id="registration_status" name="registration_status"
                                                                value="inactive" <?php echo $checked; ?>
                                                                <?php echo $disabled; ?>>
                                                        </div>
                                                        <hr class="my-4 border-gray-300 dark:border-gray-600">
                                                        <!-- Horizontal line -->
                                                    </div>
                                                    <!-- End of Message Email, Deactivate fields -->
                                                </div>
                                                <?php if ($is_admin_or_manager) : ?>
                                                <?php wp_nonce_field('update_user_data', '_wpnonce_update_user_data'); ?>
                                                <?php wp_nonce_field('delete_user', '_wpnonce_delete_user'); ?>
                                                <div class="flex justify-center mt-4 space-x-2">
                                                    <button type="submit" name="update_user"
                                                        class="px-4 py-2 font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                                                        Update Details
                                                    </button>
                                                    <button type="reset"
                                                        class="px-4 py-2 font-semibold text-white bg-gray-600 rounded hover:bg-gray-700">
                                                        Reset
                                                    </button>
                                                    <button type="submit" name="delete_user"
                                                        class="px-4 py-2 font-semibold text-white bg-red-600 rounded hover:bg-red-700"
                                                        onclick="return confirm('Are you sure you want to delete this account? This action is irreversible.')">
                                                        Delete Account
                                                    </button>
                                                </div>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- End of Personal Information Section -->
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