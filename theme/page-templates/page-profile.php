<?php
/**
 * The template for displaying profile page
 *
 * This is the template that displays profile page by default. Please note that
 * this is the WordPress construct of pages: specifically, posts with a post
 * type of `page`.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visitor_Management_System
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Check if the user is logged in
if ( ! is_user_logged_in() ) {
	// Redirect non-logged-in users to the login page
	wp_redirect( home_url() );
	exit;
}

get_header();

$errors           = array();
$success_messages = array();
$current_user     = wp_get_current_user();
$user_id          = $current_user->ID;
$user_data        = get_userdata( $user_id );
$user_avatar      = get_avatar_url( $user_id );
$user_phone       = get_user_meta( $user_id, 'phone_number', true );
$user_bio         = get_user_meta( $user_id, 'description', true );
$user_country     = get_user_meta( $user_id, 'country', true );
$user_city_state  = get_user_meta( $user_id, 'city_state', true );
$user_postal_code = get_user_meta( $user_id, 'postal_code', true );
$user_tax_id      = get_user_meta( $user_id, 'tax_id', true );
$receive_messages = get_user_meta( $user_id, 'receive_messages', true );
$receive_emails   = get_user_meta( $user_id, 'receive_emails', true );
$user_role        = ! empty( $current_user->roles ) ? $current_user->roles[0] : 'guest';

// Always treat roles as an array
$user_roles = (array) $current_user->roles;

// Member role flag
$is_member = in_array( 'member', $user_roles, true );
?>

<section x-data="{ page: 'profile', 'isProfileInfoModal': false, 'isPasswordModal': false }"
    @close-info-modal.window="isProfileInfoModal = false">
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
                    <?php if ( $is_member ) : ?>
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                        <a href="<?php echo esc_url( home_url( '/guests' ) ); ?>"
                            class="inline-flex items-center text-sm text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                            <svg class="stroke-current" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 20 20" fill="none">
                                <path d="M12.7083 5L7.5 10.2083L12.7083 15.4167" stroke="" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <?php esc_html_e( 'Back to Guests', 'vms' ); ?>
                        </a>

                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                            <?php esc_html_e( 'Profile', 'vms' ); ?>
                        </h2>
                    </div>                    
                    <?php else : ?>                    
                    <div x-data="{ pageName: `Profile`}">
                        <?php get_template_part( 'template-parts/content/content', 'breadcrumb' ); ?>
                    </div>
                    <?php endif; ?>
                    <!-- Breadcrumb End -->

                    <div
                        class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
                        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">
                            <?php esc_html_e( 'Profile', 'vms' ); ?>
                        </h3>

                        <!-- Success Alert -->
                        <?php if ( ! empty( $success_messages ) ) : ?>
                        <?php foreach ( $success_messages as $success_message ) : ?>
                        <div class="relative flex items-center justify-between p-4 mb-4 text-white bg-green-500 rounded"
                            role="alert" x-data="{ show: true }" x-show="show"
                            x-init="setTimeout(() => show = false, 5000)">
                            <div>
                                <strong><?php esc_html_e( 'Success!', 'vms' ); ?></strong>
                                <p class="text-sm"><?php echo esc_html( $success_message ); ?></p>
                            </div>
                            <button type="button"
                                class="float-right text-lg text-white cursor-pointer hover:text-gray-300"
                                @click="show = false">×</button>

                            <!-- Countdown bar container -->
                            <div class="absolute bottom-0 left-0 right-0 h-1 overflow-hidden bg-green-600 rounded-b">
                                <!-- Moving bar - now scales from LEFT to RIGHT -->
                                <div class="h-full w-full bg-green-700 origin-left transition-transform duration-[5000ms] ease-linear"
                                    x-init="$el.style.transform = 'scaleX(1)'; setTimeout(() => { $el.style.transform = 'scaleX(0)' }, 10)">
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Error Alert -->
                        <?php if ( ! empty( $errors ) ) : ?>
                        <?php foreach ( $errors as $error ) : ?>
                        <div class="relative flex items-center justify-between p-4 mb-4 text-white bg-red-500 rounded"
                            role="alert" x-data="{ show: true }" x-show="show"
                            x-init="setTimeout(() => show = false, 5000)">
                            <div>
                                <strong><?php esc_html_e( 'Warning!', 'vms' ); ?></strong>
                                <p class="text-sm"><?php echo esc_html( $error ); ?></p>
                            </div>
                            <button type="button" class="float-right text-white cursor-pointer hover:text-gray-300"
                                @click="show = false">×</button>

                            <!-- Countdown bar container -->
                            <div class="absolute bottom-0 left-0 right-0 h-1 overflow-hidden bg-red-600 rounded-b">
                                <!-- Moving bar - scales from left to right -->
                                <div class="h-full w-full bg-red-700 origin-left transition-transform duration-[5000ms] ease-linear"
                                    x-init="$el.style.transform = 'scaleX(1)'; setTimeout(() => { $el.style.transform = 'scaleX(0)' }, 10)">
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>

                        <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">

                            <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                                <div class="flex flex-col items-center w-full gap-6 xl:flex-row">
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
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20"
                                                    height="20" fill="currentColor" class="text-xl">
                                                    <path
                                                        d="M16.585 3.414a2 2 0 0 0-2.828 0l-10 10a2 2 0 0 0-.484.797l-2 6a2 2 0 0 0 2.397 2.397l6-2a2 2 0 0 0 .797-.484l10-10a2 2 0 0 0 0-2.828l-2-2a2 2 0 0 0-2.828 0L14 7.586 16.585 3.414zM14 7.586l-2 2-1-1 2-2a1 1 0 0 1 1.414 1.414l-2 2zM4 19l1.5-4.5L14 7.586l3.414 3.414-10 10L4 19z" />
                                                </svg>
                                            </label>
                                            <input class="hidden" id="profile_picture" type="file" accept="image/*"
                                                name="profile_picture">
                                        </div>
                                    </div>
                                    <!-- Indicator badge -->
                                    <div id="photo-selected"
                                        class="hidden px-2 py-1 text-xs text-white rounded-lg shadow bg-brand-500">
                                        <?php esc_html_e( 'Please click edit then save to update photo', 'vms' ); ?>
                                    </div>
                                    <div class="order-3 xl:order-2">
                                        <h4
                                            class="mb-2 text-lg font-semibold text-center text-gray-800 dark:text-white/90 xl:text-left">
                                            <?php echo esc_html( $current_user->first_name . ' ' . $current_user->last_name ); ?>
                                        </h4>
                                        <?php
										$role_slug = $user_role ?: '';
										$role_label = $role_slug ? ucwords(str_replace('_', ' ', $role_slug)) : __('No Role Provided', 'vms');
										?>
                                        <div
                                            class="flex flex-col items-center gap-1 text-center xl:flex-row xl:gap-3 xl:text-left">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                <?php echo esc_html($role_label); ?>
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
                            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6">
                                        <?php esc_html_e( 'Personal Information', 'vms' ); ?>
                                    </h4>

                                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                                        <div>
                                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                                <?php esc_html_e( 'First Name', 'vms' ); ?>
                                            </p>
                                            <p id="profile-first-name"
                                                class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                <?php echo ! empty( $current_user->first_name ) ? esc_html( $current_user->first_name ) : esc_html__( 'Not provided', 'vms' ); ?>
                                            </p>
                                        </div>

                                        <div>
                                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                                <?php esc_html_e( 'Last Name', 'vms' ); ?>
                                            </p>
                                            <p id="profile-last-name"
                                                class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                <?php echo ! empty( $current_user->last_name ) ? esc_html( $current_user->last_name ) : esc_html__( 'Not provided', 'vms' ); ?>
                                            </p>
                                        </div>

                                        <div>
                                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                                <?php esc_html_e( 'Email address', 'vms' ); ?>
                                            </p>
                                            <p id="profile-email"
                                                class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                <?php echo ! empty( $current_user->user_email ) ? esc_html( $current_user->user_email ) : esc_html__( 'Not provided', 'vms' ); ?>
                                            </p>
                                        </div>

                                        <div>
                                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                                <?php esc_html_e( 'Phone', 'vms' ); ?>
                                            </p>
                                            <p id="profile-phone"
                                                class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                <?php echo ! empty( $user_phone ) ? esc_html( $user_phone ) : esc_html__( 'Not provided', 'vms' ); ?>
                                            </p>
                                        </div>

                                        <div>
                                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                                <?php esc_html_e( 'Username', 'vms' ); ?>
                                            </p>
                                            <p id="profile-username"
                                                class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                <?php echo ! empty( $current_user->user_login ) ? esc_html( $current_user->user_login ) : esc_html__( 'Not provided', 'vms' ); ?>
                                            </p>
                                        </div>

                                        <div>
                                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                                <?php esc_html_e( 'Status', 'vms' ); ?>
                                            </p>
                                            <p id="profile-status"
                                                class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                <?php
												// Get current registration_status from usermeta
												$registration_status = get_user_meta( $current_user->ID, 'registration_status', true );
												?>
                                                <?php
												echo ! empty( $registration_status )
													? esc_html( ucfirst( $registration_status ) )
													: esc_html__( 'Not provided', 'vms' );
												?>
                                            </p>

                                        </div>

                                        <div>
                                            <label class="flex items-center">
                                                <span class="inline-flex items-center"
                                                    id="profile-receive-messages-icon">
                                                    <?php if ( $receive_messages === 'yes' ) : ?>
                                                    <svg class="w-4 h-4 text-green-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <?php else : ?>
                                                    <svg class="w-4 h-4 text-warning-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    <?php endif; ?>
                                                </span>
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                    <?php esc_html_e( 'Receive messages', 'vms' ); ?>
                                                </span>
                                            </label>
                                        </div>

                                        <div>
                                            <label class="flex items-center">
                                                <span class="inline-flex items-center" id="profile-receive-emails-icon">
                                                    <?php if ( $receive_emails === 'yes' ) : ?>
                                                    <svg class="w-4 h-4 text-green-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <?php else : ?>
                                                    <svg class="w-4 h-4 text-warning-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    <?php endif; ?>
                                                </span>
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                    <?php esc_html_e( 'Receive emails', 'vms' ); ?>
                                                </span>
                                            </label>
                                        </div>

                                        <div>
                                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                                <?php esc_html_e( 'Bio', 'vms' ); ?>
                                            </p>
                                            <p id="profile-bio"
                                                class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                <?php echo ! empty( $user_bio ) ? esc_html( $user_bio ) : esc_html__( 'No bio provided', 'vms' ); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <a @click="isProfileInfoModal = true"
                                    class="cursor-pointer flex w-full items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 lg:inline-flex lg:w-auto">
                                    <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z"
                                            fill="" />
                                    </svg>
                                    <?php esc_html_e( 'Edit', 'vms' ); ?>
                                </a>
                            </div>
                        </div>

                        <div class="p-5 border border-gray-200 rounded-2xl lg:p-6 dark:border-gray-800">
                            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <h4
                                        class="text-lg font-semibold text-center text-gray-800 md:text-start dark:text-white/90">
                                        <?php esc_html_e( 'Change Password', 'vms' ); ?>
                                    </h4>
                                </div>

                                <a @click="isPasswordModal = true"
                                    class="cursor-pointer flex w-full items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 lg:inline-flex lg:w-auto">
                                    <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z"
                                            fill=""></path>
                                    </svg>
                                    Edit
                                </a>
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
    <!-- ===== Page Wrapper End ===== -->
    <!-- BEGIN MODAL -->
    <?php get_template_part( 'template-parts/content/content', 'info-modal' ); ?>

    <?php get_template_part( 'template-parts/content/content', 'password-modal' ); ?>
    <!-- END MODAL -->
</section>

<?php
get_footer();