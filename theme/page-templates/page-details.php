<?php
/**
 * The template for displaying the Member details page
 *
 * @package Visitor_Management_System
 */
use WyllyMk\VMS\VMS_Member;
use WyllyMk\VMS\VMS_Guest;
use WyllyMk\VMS\VMS_SMS;
use WyllyMk\VMS\VMS_Core;
use WyllyMk\VMS\VMS_Export_Handler;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Check if the current user is an Administrator or Manager or Advocate
if ( ! ( current_user_can( 'administrator' ) || current_user_can( 'general_manager' ) || current_user_can( 'chairman' ) ) ) {
	// Redirect unauthorized users to the front page
	wp_redirect( home_url() );
	exit;
}

// WordPress table prefix
$guests_table       = $wpdb->prefix . 'vms_guests';
$guest_visits_table = $wpdb->prefix . 'vms_guest_visits';
$wp_users_table     = $wpdb->users;

// Process form submissions if user_id is set
if ( isset( $_GET['user_id'] ) && intval( $_GET['user_id'] ) ) {
	$user_id      = intval( $_GET['user_id'] );
	$current_user = wp_get_current_user();
	$is_allowed   = in_array( 'administrator', $current_user->roles ) || in_array( 'general_manager', $current_user->roles ) || in_array( 'reception', $current_user->roles ) || in_array( 'chairman', $current_user->roles );

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

// Get pagination params - per_page now supports 10, 25, or 50
$valid_per_page = array( 10, 25, 50 );
$per_page       = isset( $_GET['per_page'] ) && in_array( intval( $_GET['per_page'] ), $valid_per_page ) ? intval( $_GET['per_page'] ) : 10;
$paged          = isset( $_GET['paged'] ) ? max( 1, intval( $_GET['paged'] ) ) : 1;
$offset         = ( $paged - 1 ) * $per_page;

// Get total visits count and paginated visits
$total_visits = VMS_Guest::count_guest_visits( $user_id );
$total_pages  = ceil( $total_visits / $per_page );
$guests       = VMS_Guest::get_paginated_guest_visits( $user_id, $per_page, $offset );

// Calculate row numbers for display
$current_start = $total_visits > 0 ? ( $paged - 1 ) * $per_page + 1 : 0;
$row_number    = $current_start;

// Build compact pagination array
$pages_to_show = array();
if ( $total_pages > 0 ) {
	$pages_to_show = array( 1 );

	// Add current page and surrounding pages
	for ( $i = max( 1, $paged - 1 ); $i <= min( $total_pages, $paged + 1 ); $i++ ) {
		$pages_to_show[] = $i;
	}

	// Add last page if it's not already included
	if ( $total_pages > 1 ) {
		$pages_to_show[] = $total_pages;
	}

	// Remove duplicates and sort
	$pages_to_show = array_values( array_unique( $pages_to_show ) );
	sort( $pages_to_show );
}

$page_name = 'Member-Details';

$status_classes = array(
	'approved'   => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
	'unapproved' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
	'suspended'  => 'bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500',
	'banned'     => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
	'cancelled'  => 'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80',
);

// Handle canceling a guest visit
if ( isset( $_POST['cancel_visit'] ) && isset( $_POST['visit_id'] ) ) {

	// Verify nonce for security
	if ( ! isset( $_POST['cancel_visit_nonce'] ) ||
		! wp_verify_nonce( $_POST['cancel_visit_nonce'], 'cancel_visit_action' ) ) {
		wp_die( __( 'Security check failed. Please try again.', 'vms' ) );
	}

	$visit_id = intval( $_POST['visit_id'] );

	if ( $visit_id > 0 ) {
		global $wpdb;

		// Get the visit details before cancelling
		$visit = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT guest_id, host_member_id, visit_date FROM $guest_visits_table WHERE id = %d",
				$visit_id
			)
		);

		if ( ! $visit ) {
			wp_die( __( 'Visit not found.', 'vms' ) );
		}

		// Update visit status to cancelled
		$updated = $wpdb->update(
			$guest_visits_table,
			array( 'status' => 'cancelled' ),
			array( 'id' => $visit_id ),
			array( '%s' ),
			array( '%d' )
		);

		if ( $updated !== false ) {
			// Trigger automatic status recalculation for the guest
			VMS_Guest::recalculate_guest_visit_statuses( $visit->guest_id );

			// Also recalculate host's daily limits for that date
			if ( $visit->host_member_id ) {
				VMS_Guest::recalculate_host_daily_limits( $visit->host_member_id, $visit->visit_date );
			}

			// Success message or redirect
			wp_safe_redirect( add_query_arg( 'visit_cancelled', '1', wp_get_referer() ) );
			exit;
		} else {
			wp_die( __( 'Failed to cancel visit. Please try again.', 'vms' ) );
		}
	} else {
		wp_die( __( 'Invalid visit ID.', 'vms' ) );
	}
}

// Handle member export
if ( isset( $_POST['export_member'] ) && isset( $_POST['user_id'] ) ) {

	// Verify nonce
	if ( ! isset( $_POST['export_member_nonce'] ) ||
		! wp_verify_nonce( $_POST['export_member_nonce'], 'export_member_action' ) ) {
		wp_die( 'Security check failed' );
	}

	$export_type    = sanitize_text_field( $_POST['export_member'] );
	$export_user_id = absint( $_POST['user_id'] );

	if ( $export_user_id !== $user_id ) {
		wp_die( 'Invalid user ID' );
	}

	// Check user permissions
	if ( ! current_user_can( 'administrator' ) &&
		! current_user_can( 'general_manager' ) &&
		! current_user_can( 'chairman' ) &&
		! current_user_can( 'reception' ) ) {
		wp_die( 'You do not have permission to export member details' );
	}

	// Process export based on type
	if ( $export_type === 'pdf' ) {
		VMS_Export_Handler::export_member_pdf( $export_user_id );
	}

	exit;
}
get_header();
?>

<section id="primary" x-data="{ page: 'details', 'isGuestInfoModal': false}"
	@close-guest-modal.window="isGuestInfoModal = false">
	<main id="main">
		<!-- ===== Page Wrapper Start ===== -->
		<div class="flex h-svh overflow-hidden">
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
							<a href="<?php echo esc_url( home_url( '/members' ) ); ?>"
								class="inline-flex items-center text-sm text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
								<svg class="stroke-current" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
									viewBox="0 0 20 20" fill="none">
									<path d="M12.7083 5L7.5 10.2083L12.7083 15.4167" stroke="" stroke-width="1.5"
										stroke-linecap="round" stroke-linejoin="round" />
								</svg>
								<?php esc_html_e( 'Back to Members', 'vms' ); ?>
							</a>

							<h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
								<?php esc_html_e( 'Member Details', 'vms' ); ?>
							</h2>
						</div>
						<!-- Breadcrumb End -->

						<!-- Export Actions Section -->
						<div
							class="w-full lg:w-5/6 xl:4/5 2xl:3/4 mx-auto flex flex-wrap items-center justify-between gap-3 mb-6 p-4 rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
							<div class="flex items-center gap-2">
								<svg class="w-5 h-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
									fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round"
										d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
								</svg>
								<span class="text-sm font-medium text-gray-700 dark:text-gray-400">
									Export Member Details
								</span>
							</div>
							<div class="flex flex-wrap gap-2">
								<!-- PDF Export Button -->
								<form method="post" action="" class="inline-block">
									<input type="hidden" name="export_member" value="pdf">
									<input type="hidden" name="user_id" value="<?php echo esc_attr( $user_id ); ?>">
									<?php wp_nonce_field( 'export_member_action', 'export_member_nonce' ); ?>
									<button type="submit"
										class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-error-500 border border-error-500 rounded-lg shadow-theme-xs hover:bg-error-600 transition">
										<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
											viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round"
												d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
										</svg>
										Export PDF
									</button>
								</form>
							</div>
						</div>
						<!-- Export Actions Section End -->

						<!-- Personal Information Section -->
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

											<form id="member-update-form" action="" method="post"
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
																class="text-white dark:text-black cursor-pointer">
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
														class="hidden bg-brand-500 text-white text-xs px-2 py-1 rounded-lg shadow">
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
															class="text-sx md:text-sm text-gray-600 dark:text-gray-400">
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
															class="text-xs md:text-sm text-gray-600 dark:text-gray-400">
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
														<input type="text"
															class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
															id="lname"
															value="<?php echo esc_attr( $user_data->last_name ); ?>"
															name="last_name"
															<?php echo ! $is_allowed ? 'disabled' : ''; ?>>
													</div>

													<!-- Member Number -->
													<div class="w-full px-2.5 md:w-1/2">
														<label for="member_number"
															class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
															<?php esc_html_e( 'Member Number:', 'vms' ); ?>
														</label>
														<input type="text"
															class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
															id="member_number"
															value="<?php echo esc_attr( $member_number ); ?>"
															name="member_number"
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
																$allowed_roles = array( 'member', 'chairman' );

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
												<div class="flex flex-col md:flex-row justify-center mt-4 gap-2">
													<button type="submit" id="update-member-btn"
														class="inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 cursor-pointer">
														<?php esc_html_e( 'Update Member', 'vms' ); ?>
													</button>
													<button type="reset"
														class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03] cursor-pointer">
														<?php esc_html_e( 'Reset', 'vms' ); ?>
													</button>
													<button type="button" id="delete-member-btn"
														data-member-name="<?php echo esc_attr( $user_data->first_name ); ?>"
														class="px-4 py-2 text-white bg-error-500 rounded-lg hover:bg-error-600 inline-flex items-center justify-center gap-2 shadow-theme-xs transition cursor-pointer">
														<?php esc_html_e( 'Delete Member', 'vms' ); ?>
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
													<?php esc_html_e( 'Guest Visits', 'vms' ); ?>
												</h3>
												<?php
												$registration_status = get_user_meta( $user_id, 'registration_status', true );
												$is_active           = ( $registration_status === 'active' );
												?>
												<div class="flex items-center justify-end w-full md:w-1/2">
													<a 
													<?php
													if ( $is_active ) :
														?>
														@click="isGuestInfoModal = true"
														<?php endif; ?>
														class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg shadow-theme-xs bg-brand-500 hover:bg-brand-600 <?php echo $is_active ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed hover:bg-brand-500'; ?>">
														<?php esc_html_e( 'Register New Visit', 'vms' ); ?>
													</a>
												</div>
											</div>

											<!-- Display delete messages -->
											<?php if ( ! empty( $delete_success ) ) : ?>
											<div
												class="mb-4 mx-5 rounded-lg bg-success-50 p-4 text-success-800 dark:bg-success-500/15 dark:text-success-400">
												<?php foreach ( $delete_success as $message ) : ?>
												<p><?php echo esc_html( $message ); ?></p>
												<?php endforeach; ?>
											</div>
											<?php endif; ?>

											<?php if ( ! empty( $delete_error ) ) : ?>
											<div
												class="mb-4 mx-5 rounded-lg bg-error-50 p-4 text-error-800 dark:bg-error-500/15 dark:text-error-400">
												<?php foreach ( $delete_error as $message ) : ?>
												<p><?php echo esc_html( $message ); ?></p>
												<?php endforeach; ?>
											</div>
											<?php endif; ?>

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
																	onchange="window.location.href = '
																	<?php
																	echo esc_url(
																		add_query_arg(
																			array(
																				'user_id'  => $user_id,
																				'per_page' => '',
																			),
																			remove_query_arg( array( 'paged' ), $_SERVER['REQUEST_URI'] )
																		)
																	);
																	?>
																	' + this.value">
																	<option value="10"
																		<?php selected( $per_page, 10 ); ?>>10</option>
																	<option value="25"
																		<?php selected( $per_page, 25 ); ?>>25</option>
																	<option value="50"
																		<?php selected( $per_page, 50 ); ?>>50</option>
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
																<div
																	class="col-span-2 flex items-center border-r border-gray-200 px-4 py-3 dark:border-gray-800">
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
																<?php if ( ! empty( $guests ) ) : ?>
																	<?php foreach ( $guests as $guest ) : ?>
																<div id="guest-div-<?php echo esc_attr( $guest->visit_id ); ?>"
																	class="grid grid-cols-12 border-t border-gray-100 dark:border-gray-800">
																	<!-- Row Number -->
																	<div
																		class="col-span-1 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
																		<p
																			class="text-theme-sm text-gray-700 dark:text-gray-400">
																			<?php echo esc_html( $row_number++ ); ?>
																		</p>
																	</div>

																	<!-- Guest Name -->
																	<div
																		class="col-span-2 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
																		<p
																			class="text-theme-sm text-gray-700 dark:text-gray-400">
																			<?php echo esc_html( ( $guest->first_name ?: 'N/A' ) . ' ' . ( $guest->last_name ?: '' ) ); ?>
																		</p>
																	</div>

																	<!-- Visit Date -->
																	<div
																		class="col-span-2 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
																		<p
																			class="text-theme-sm text-gray-700 dark:text-gray-400">
																			<?php echo esc_html( VMS_Core::format_date( $guest->visit_date ) ); ?>
																		</p>
																	</div>

																	<!-- Sign In -->
																	<div
																		class="col-span-1 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
																		<p
																			class="text-theme-sm text-gray-700 dark:text-gray-400">
																			<?php echo esc_html( VMS_Core::format_time( $guest->sign_in_time ) ); ?>
																		</p>
																	</div>

																	<!-- Sign Out -->
																	<div
																		class="col-span-1 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
																		<p
																			class="text-theme-sm text-gray-700 dark:text-gray-400">
																			<?php echo esc_html( VMS_Core::format_time( $guest->sign_out_time ) ); ?>
																		</p>
																	</div>

																	<!-- Duration -->
																	<div
																		class="col-span-2 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
																		<p
																			class="text-theme-sm text-gray-700 dark:text-gray-400">
																			<?php echo esc_html( VMS_Core::calculate_duration( $guest->sign_in_time, $guest->sign_out_time ) ); ?>
																		</p>
																	</div>

																	<!-- Status -->
																	<div
																		class="col-span-2 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
																		<span
																			class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium capitalize <?php echo $status_classes[ $guest->status ] ?? $status_classes['approved']; ?>">
																			<?php echo esc_html( $guest->status ); ?>
																		</span>
																	</div>

																	<!-- Action -->
																	<div
																		class="col-span-1 flex items-center border-r border-gray-100 px-4 py-3 dark:border-gray-800">
																		<form method="post"
																			onsubmit="return confirm('Are you sure you want to cancel this visit?');">
																			<input type="hidden" name="visit_id"
																				value="<?php echo esc_attr( $guest->visit_id ); ?>">
																			<?php wp_nonce_field( 'cancel_visit_action', 'cancel_visit_nonce' ); ?>
																			<button type="submit" name="cancel_visit"
																				class="px-3 py-1 text-xs font-medium text-white bg-red-500 rounded-lg hover:bg-red-600">
																				<?php esc_html_e( 'Cancel', 'vms' ); ?>
																			</button>
																		</form>
																	</div>
																</div>
																<?php endforeach; ?>
																<?php else : ?>
																<div id="no-guests-div"
																	class="border-t border-gray-100 px-4 py-8 text-center dark:border-gray-800">
																	<p class="text-gray-500 dark:text-gray-400">No guest
																		visits found</p>
																</div>
																<?php endif; ?>
															</div>
														</div>
													</div>

													<!-- Pagination -->
													<?php if ( $total_pages > 1 ) : ?>
													<div
														class="flex items-center justify-between gap-8 px-6 py-4 sm:justify-normal">
														<!-- Previous Button -->
														<?php if ( $paged > 1 ) : ?>
														<a href="
															<?php
															echo esc_url(
																add_query_arg(
																	array(
																		'user_id'  => $user_id,
																		'paged'    => $paged - 1,
																		'per_page' => $per_page,
																	),
																	remove_query_arg( array( 'paged' ), $_SERVER['REQUEST_URI'] )
																)
															);
															?>
																	"
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
														<?php else : ?>
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
															Page <?php echo esc_html( $paged ); ?> of
															<?php echo esc_html( $total_pages ); ?>
														</span>

														<!-- Desktop: Page numbers -->
														<ul class="hidden items-center gap-0.5 sm:flex">
															<?php
															$last_shown = 0;
															foreach ( $pages_to_show as $page_num ) :
																if ( $last_shown && ( $page_num - $last_shown ) > 1 ) :
																	?>
															<li>
																<span
																	class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-500 dark:text-gray-500 pointer-events-none">...</span>
															</li>
																	<?php
															endif;
																$last_shown = $page_num;
																$is_current = ( $page_num === $paged );
																?>
															<li>
																<?php if ( $is_current ) : ?>
																<span
																	class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-500 text-sm font-medium text-white">
																	<?php echo esc_html( $page_num ); ?>
																</span>
																<?php else : ?>
																<a href="
																	<?php
																	echo esc_url(
																		add_query_arg(
																			array(
																				'user_id'  => $user_id,
																				'paged'    => $page_num,
																				'per_page' => $per_page,
																			),
																			remove_query_arg( array( 'paged' ), $_SERVER['REQUEST_URI'] )
																		)
																	);
																	?>
																			"
																	class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 hover:bg-brand-500 hover:text-white dark:text-gray-400 dark:hover:text-white">
																	<?php echo esc_html( $page_num ); ?>
																</a>
																<?php endif; ?>
															</li>
															<?php endforeach; ?>
														</ul>

														<!-- Next Button -->
														<?php if ( $paged < $total_pages ) : ?>
														<a href="
															<?php
															echo esc_url(
																add_query_arg(
																	array(
																		'user_id'  => $user_id,
																		'paged'    => $paged + 1,
																		'per_page' => $per_page,
																	),
																	remove_query_arg( array( 'paged' ), $_SERVER['REQUEST_URI'] )
																)
															);
															?>
																	"
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
														<?php else : ?>
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
													<?php else : ?>
													<!-- No pagination needed, but show entry count -->
													<div class="px-6 py-4">
														<div
															class="text-sm text-gray-500 dark:text-gray-400 text-center">
															<?php if ( $total_visits > 0 ) : ?>
															Showing all <?php echo esc_html( $total_visits ); ?> entries
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
				</main>
				<!-- ===== Main Content End ===== -->

				<!-- BEGIN MODAL -->
				<?php get_template_part( 'template-parts/content/content', 'guest-modal' ); ?>
				<!-- END MODAL -->

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