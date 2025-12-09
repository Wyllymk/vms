<?php
/**
 * The template for displaying the Employee details page
 *
 * @package Visitor_Management_System
 */
use WyllyMk\VMS\VMS_Employee;
use WyllyMk\VMS\VMS_SMS;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Check if the current user is an Administrator or Manager or Advocate
if ( ! ( current_user_can( 'administrator' ) || current_user_can( 'general_manager' ) || current_user_can( 'chairman' ) ) ) {
	// Redirect unauthorized users to the front page
	wp_redirect( home_url() );
	exit;
}

$wp_users_table = $wpdb->users;

// Process form submissions if user_id is set
if ( isset( $_GET['user_id'] ) && intval( $_GET['user_id'] ) ) {
	$user_id      = intval( $_GET['user_id'] );
	$current_user = wp_get_current_user();
	$is_allowed   = in_array( 'administrator', $current_user->roles ) || in_array( 'general_manager', $current_user->roles ) || in_array( 'chairman', $current_user->roles );

	// Get user data
	$user_data           = get_userdata( $user_id );
	$user_avatar         = get_avatar_url( $user_id );
	$user_phone_number   = get_user_meta( $user_id, 'phone_number', true );
	$member_number       = get_user_meta( $user_data->ID, 'member_number', true );
	$current_role        = ! empty( $user_data->roles ) ? $user_data->roles[0] : '';
	$receive_messages    = get_user_meta( $user_id, 'receive_messages', true );
	$receive_emails      = get_user_meta( $user_id, 'receive_emails', true );
	$registration_status = get_user_meta( $user_id, 'registration_status', true );
	$disabled            = ( ! $is_allowed ) ? 'disabled' : '';
	$initialJSMessage    = ( $receive_messages === 'yes' ) ? 'true' : 'false';
	$initialJSEmail      = ( $receive_emails === 'yes' ) ? 'true' : 'false';

}

$page_name = 'Employee-Details'; // default

$status_classes = array(
	'approved'   => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
	'unapproved' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
	'suspended'  => 'bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500',
	'banned'     => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
	'cancelled'  => 'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80',
);

// Handle employee export
if ( isset( $_POST['export_employee'] ) && isset( $_POST['user_id'] ) ) {

	// Verify nonce
	if ( ! isset( $_POST['export_employee_nonce'] ) ||
		! wp_verify_nonce( $_POST['export_employee_nonce'], 'export_employee_action' ) ) {
		wp_die( 'Security check failed' );
	}

	$export_type    = sanitize_text_field( $_POST['export_employee'] );
	$export_user_id = absint( $_POST['user_id'] );

	if ( $export_user_id !== $user_id ) {
		wp_die( 'Invalid user ID' );
	}

	// Check user permissions
	if ( ! current_user_can( 'administrator' ) &&
		! current_user_can( 'general_manager' ) &&
		! current_user_can( 'chairman' ) &&
		! current_user_can( 'reception' ) ) {
		wp_die( 'You do not have permission to export employee details' );
	}

	// Process export based on type
	if ( $export_type === 'pdf' ) {
		VMS_Export_Handler::export_employee_pdf( $export_user_id );
	}

	exit;
}

get_header();
?>

<section id="primary" x-data="{ page: 'employee-details'}">
    <main id="main">
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
                        <!-- Breadcrumb Start -->
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                            <a href="<?php echo esc_url( home_url( '/employees' ) ); ?>"
                                class="inline-flex items-center text-sm text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                <svg class="stroke-current" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none">
                                    <path d="M12.7083 5L7.5 10.2083L12.7083 15.4167" stroke="" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <?php esc_html_e( 'Back to Staff', 'vms' ); ?>
                            </a>

                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                                <?php esc_html_e( 'Staff Details', 'vms' ); ?>
                            </h2>
                        </div>
                        <!-- Breadcrumb End -->

                        <!-- Personal Information Section Start -->
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

                                            <form id="employee-update-form" action="" method="post"
                                                enctype="multipart/form-data">
                                                <div class="flex flex-col items-center mb-4">
                                                    <!-- Profile Photo -->
                                                    <div class="relative">
                                                        <img id="profile-preview"
                                                            class="object-cover w-24 h-24 border-2 border-gray-200 rounded-full dark:border-gray-700"
                                                            src="<?php echo esc_url( get_user_meta( $user_id, 'profile_picture', true ) ?: get_avatar_url( $user_id ) ); ?>"
                                                            alt="Profile Picture">
                                                        <!-- File selector -->
                                                        <div
                                                            class="absolute flex items-center justify-center p-0.5 backdrop-blur-md rounded-full bottom-3 right-3">
                                                            <label for="profile_picture"
                                                                class="text-white cursor-pointer dark:text-black">
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
                                                    <!-- Indicator badge -->
                                                    <div id="photo-selected"
                                                        class="hidden px-2 py-1 text-xs text-white rounded-lg shadow bg-brand-500">
                                                        <?php esc_html_e( 'Please update details to save photo', 'vms' ); ?>
                                                    </div>
                                                </div>
                                                <div class="-mx-2.5 flex flex-wrap gap-y-4">
                                                    <!-- User Name field -->
                                                    <div class="w-full px-2.5 md:w-1/2">
                                                        <label for="uname"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            <?php esc_html_e( 'User Name:', 'vms' ); ?>
                                                        </label>
                                                        <input type="text"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            id="uname"
                                                            value="<?php echo esc_attr( $user_data->user_login ); ?>"
                                                            name="user_name" disabled>
                                                        <small
                                                            class="text-gray-600 text-sx md:text-sm dark:text-gray-400">
                                                            <?php esc_html_e( 'Usernames cannot be changed.', 'vms' ); ?>
                                                        </small>
                                                    </div>
                                                    <!-- Email field -->
                                                    <div class="w-full px-2.5 md:w-1/2">
                                                        <label for="email"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            <?php esc_html_e( 'Email (required):', 'vms' ); ?>
                                                        </label>
                                                        <input type="text"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            id="email"
                                                            value="<?php echo esc_attr( $user_data->user_email ); ?>"
                                                            name="email" <?php echo ! $is_allowed ? 'disabled' : ''; ?>>
                                                        <small
                                                            class="text-xs text-gray-600 md:text-sm dark:text-gray-400">
                                                            <?php esc_html_e( 'If you change this, an email will be sent to confirm it.', 'vms' ); ?>
                                                        </small>
                                                    </div>
                                                    <!-- First Name field -->
                                                    <div class="w-full px-2.5 md:w-1/2">
                                                        <label for="fname"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            <?php esc_html_e( 'First Name:', 'vms' ); ?>
                                                        </label>
                                                        <input type="text"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            id="fname"
                                                            value="<?php echo esc_attr( $user_data->first_name ); ?>"
                                                            name="first_name"
                                                            <?php echo ! $is_allowed ? 'disabled' : ''; ?>>
                                                    </div>
                                                    <!-- Last Name field -->
                                                    <div class="w-full px-2.5 md:w-1/2">
                                                        <label for="lname"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            <?php esc_html_e( 'Last Name:', 'vms' ); ?>
                                                        </label>
                                                        <input type="text" id="lname"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            value="<?php echo esc_attr( $user_data->last_name ); ?>"
                                                            name="last_name"
                                                            <?php echo ! $is_allowed ? 'disabled' : ''; ?>>
                                                    </div>
                                                    <!-- Phone Number field -->
                                                    <div class="w-full px-2.5 md:w-1/2">
                                                        <label for="number"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            <?php esc_html_e( 'Phone Number:', 'vms' ); ?>
                                                        </label>
                                                        <input type="tel"
                                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                            id="number"
                                                            value="<?php echo esc_attr( $user_phone_number ); ?>"
                                                            name="pnumber"
                                                            <?php echo ! $is_allowed ? 'disabled' : ''; ?>>
                                                    </div>
                                                    <!-- Role -->
                                                    <div class="w-full px-2.5 md:w-1/2">
                                                        <label for="role"
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            <?php esc_html_e( 'Role:', 'vms' ); ?>
                                                        </label>

                                                        <?php
														// Get current role of the user
														$current_role = ! empty( $user_data->roles ) ? $user_data->roles[0] : '';
														?>

                                                        <div x-data="{ isOptionSelected: true }"
                                                            class="relative z-20 bg-transparent">
                                                            <select name="user_role" id="role"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90            dark:placeholder:text-white/30"
                                                                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                                                @change="isOptionSelected = true"
                                                                <?php echo $disabled; ?>>

                                                                <?php
																// List of roles you want to display
																$allowed_roles = array( 'general_manager', 'gate', 'reception' );

																global $wp_roles;
																foreach ( $allowed_roles as $role_key ) {
																	if ( isset( $wp_roles->roles[ $role_key ] ) ) {
																		$role_name = $wp_roles->roles[ $role_key ]['name'];

																		// Select if matches POST or current role
																		$selected = '';
																		if ( ( isset( $_POST['user_role'] ) && $_POST['user_role'] === $role_key ) || $current_role === $role_key ) {
																			$selected = 'selected';
																		}

																		echo '<option value="' . esc_attr( $role_key ) . '" ' . $selected . '>'
																			. esc_html( $role_name )
																			. '</option>';
																	}
																}
																?>
                                                            </select>

                                                            <span
                                                                class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-4 dark:text-gray-400">
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

                                                    <!-- Status -->
                                                    <div class="w-full px-2.5 md:w-1/2">
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
														$disabled = ( ! $is_allowed ) ? 'disabled' : '';
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
                                                                class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none top-1/2 right-4 dark:text-gray-400">
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
                                                    <div class="w-full px-2.5">
                                                        <hr class="my-4 border-gray-300 dark:border-gray-600">
                                                        <div class="flex items-center justify-between mb-4"
                                                            x-data="{ checkboxToggle: <?php echo $initialJSMessage; ?> }">
                                                            <label for="message"
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                                <?php esc_html_e( 'Receive Communication Messages?', 'vms' ); ?>
                                                            </label>

                                                            <!-- Real checkbox (hidden but still submitted in form) -->
                                                            <input type="checkbox" id="message" name="receive_messages"
                                                                value="yes" class="sr-only" x-model="checkboxToggle"
                                                                <?php echo $disabled; ?>>

                                                            <!-- Custom styled checkbox -->
                                                            <div @click="checkboxToggle = !checkboxToggle"
                                                                :class="checkboxToggle ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                                                                class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] cursor-pointer transition-colors">
                                                                <span
                                                                    :class="checkboxToggle ? 'opacity-100' : 'opacity-0'"
                                                                    class="transition-opacity">
                                                                    <svg width="14" height="14" viewBox="0 0 14 14"
                                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7"
                                                                            stroke="white" stroke-width="1.94437"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></path>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <hr class="my-4 border-gray-300 dark:border-gray-600">

                                                        <div class="flex items-center justify-between mb-4"
                                                            x-data="{ checkboxToggle: <?php echo $initialJSEmail; ?> }">
                                                            <label for="email_comm"
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                                <?php esc_html_e( 'Receive Communication Emails?', 'vms' ); ?>
                                                            </label>


                                                            <!-- Real checkbox (hidden but still submitted in form) -->
                                                            <input type="checkbox" id="email_comm" name="receive_emails"
                                                                value="yes" class="sr-only" x-model="checkboxToggle"
                                                                <?php echo $disabled; ?>>

                                                            <!-- Custom styled checkbox -->
                                                            <div @click="checkboxToggle = !checkboxToggle"
                                                                :class="checkboxToggle ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                                                                class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] cursor-pointer transition-colors">
                                                                <span
                                                                    :class="checkboxToggle ? 'opacity-100' : 'opacity-0'"
                                                                    class="transition-opacity">
                                                                    <svg width="14" height="14" viewBox="0 0 14 14"
                                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7"
                                                                            stroke="white" stroke-width="1.94437"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></path>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <hr class="my-4 border-gray-300 dark:border-gray-600">
                                                    </div>
                                                </div>
                                                <?php if ( $is_allowed ) : ?>
                                                <div class="flex flex-col justify-center gap-2 mt-4 md:flex-row">
                                                    <button type="submit" id="update-employee-btn"
                                                        class="inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg cursor-pointer bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                                                        <?php esc_html_e( 'Update Employee', 'vms' ); ?>
                                                    </button>
                                                    <?php if ( ( current_user_can( 'administrator' ) ) ) : ?>
                                                    <button type="reset"
                                                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03] cursor-pointer">
                                                        <?php esc_html_e( 'Reset', 'vms' ); ?>
                                                    </button>
                                                    <button type="button" id="delete-employee-btn"
                                                        data-member-name="<?php echo esc_attr( $user_data->first_name ); ?>"
                                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 text-white transition rounded-lg cursor-pointer bg-error-500 hover:bg-error-600 shadow-theme-xs">
                                                        <?php esc_html_e( 'Delete Employee', 'vms' ); ?>
                                                    </button>
                                                    <?php endif; ?>
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
                </main>
                <!-- ===== Main Content End ===== -->

                <!-- ===== Footer Start ===== -->
                <?php get_template_part( 'template-parts/content/content', 'footer' ); ?>
                <!-- ===== Footer End ===== -->
            </div>
            <!-- ===== Content Area End ===== -->
        </div>
    </main>
</section>

<?php
get_footer();