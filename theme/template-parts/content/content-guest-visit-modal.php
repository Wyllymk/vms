<?php
/**
 * Template part for displaying info modal
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visitor_Management_System
 */
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<!-- <div x-show="isGuestVisitInfoModal" class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto z-99999">
    <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
    <div @click.outside="if (!$event.target.closest('.flatpickr-calendar')) { isGuestVisitInfoModal = false }"
        class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"> -->

<div x-show="isGuestVisitInfoModal" class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto z-99999">
    <div @click="isGuestVisitInfoModal = false"
        class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
    <div @click.stop
        class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
        <!-- close btn -->
        <a @click="isGuestVisitInfoModal = false"
            class="cursor-pointer transition-color absolute right-5 top-5 z-999 flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-gray-700 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300">
            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z"
                    fill="" />
            </svg>
        </a>
        <div class="px-2 pr-14">
            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                <?php esc_html_e( 'Register Guest', 'vms' ); ?>
            </h4>
            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                <?php esc_html_e( 'Create a Guests Account.', 'vms' ); ?>
            </p>
        </div>
        <form id="guest-form" class="flex flex-col" method="post" enctype="multipart/form-data">
            <?php wp_nonce_field( 'create_user_data', '_wpnonce_create_user_data' ); ?>
            <input type="hidden" name="register_guest" value="1">

            <div class="custom-scrollbar h-2xl overflow-y-auto px-2">
                <div class="">
                    <h5 class="mb-5 text-lg font-medium text-gray-800 dark:text-white/90 lg:mb-6">
                        <?php esc_html_e( 'Personal Information', 'vms' ); ?>
                    </h5>

                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                        <!-- First Name -->
                        <div class="col-span-2 lg:col-span-1">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                <?php esc_html_e( 'First Name', 'vms' ); ?>
                                <span class="text-error-500">*</span>
                            </label>
                            <input type="text" name="first_name"
                                value="<?php echo esc_attr( $_POST['first_name'] ?? '' ); ?>"
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                                required />
                        </div>

                        <!-- Last Name -->
                        <div class="col-span-2 lg:col-span-1">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                <?php esc_html_e( 'Last Name', 'vms' ); ?>
                                <span class="text-error-500">*</span>
                            </label>
                            <input type="text" name="last_name"
                                value="<?php echo esc_attr( $_POST['last_name'] ?? '' ); ?>"
                                class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                                required />
                        </div>

                        <!-- Phone -->
                        <div class="col-span-2 lg:col-span-1 relative">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                <?php esc_html_e( 'Phone', 'vms' ); ?>
                                <span class="text-error-500">*</span>
                            </label>

                            <div class="flex items-center gap-2">
                                <input type="tel" id="guest_phone_number" name="phone_number"
                                    data-contact-picker="guest_pick_contact"
                                    value="<?php echo esc_attr( $_POST['phone_number'] ?? '' ); ?>"
                                    class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                                    required />

                                <button type="button" id="guest_pick_contact" title="Pick from contacts"
                                    class="px-3 py-2 rounded-lg bg-brand-500 text-white text-sm hover:bg-brand-600 transition">
                                    📇
                                </button>
                            </div>

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                <?php esc_html_e( 'Format: +254700123456 or 0700123456', 'vms' ); ?>
                            </p>
                        </div>


                        <!-- Host Member Dropdown -->
                        <div class="col-span-2 lg:col-span-1">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                <?php esc_html_e( 'Host Member', 'vms' ); ?>
                                <span class="text-error-500">*</span>
                            </label>

                            <?php
							// Get the member ID from the URL parameter - this is the member we're viewing
							$current_member_id = isset( $_GET['user_id'] ) ? intval( $_GET['user_id'] ) : 0;
							
							if ( $current_member_id ) {
								// Get the member's data
								$member_data = get_userdata( $current_member_id );
								$first_name = get_user_meta( $current_member_id, 'first_name', true );
								$last_name = get_user_meta( $current_member_id, 'last_name', true );
								$full_name = trim( $first_name . ' ' . $last_name ) ?: $member_data->display_name;
								
								// Check if member is active
								$registration_status = get_user_meta( $current_member_id, 'registration_status', true );
								$is_active = ( $registration_status === 'active' );
							}
							?>

                            <?php if ( $current_member_id && $is_active ) : ?>
                            <!-- Display the member's name as read-only -->
                            <div
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 flex items-center">
                                <?php echo esc_html( $full_name ); ?>
                                <span
                                    class="ml-2 text-xs text-green-600 bg-green-100 px-2 py-1 rounded dark:bg-green-900/30 dark:text-green-400">
                                    <?php esc_html_e( 'Selected Member', 'vms' ); ?>
                                </span>
                            </div>

                            <!-- Hidden field to submit the member ID -->
                            <input type="hidden" name="host_member_id"
                                value="<?php echo esc_attr( $current_member_id ); ?>">

                            <?php elseif ( $current_member_id && ! $is_active ) : ?>
                            <!-- Member is not active -->
                            <div
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-error-300 bg-transparent px-4 py-2.5 text-sm text-error-600 shadow-theme-xs dark:border-error-700 dark:bg-gray-900 dark:text-error-400 flex items-center">
                                <?php echo esc_html( $full_name ); ?>
                                <span class="ml-2 text-xs bg-error-100 px-2 py-1 rounded dark:bg-error-900/30">
                                    <?php esc_html_e( 'Member Not Active', 'vms' ); ?>
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-error-500 dark:text-error-400">
                                <?php esc_html_e( 'Cannot register guests for inactive members.', 'vms' ); ?>
                            </p>

                            <?php else : ?>
                            <!-- Fallback if no user_id is found -->
                            <div
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-error-300 bg-transparent px-4 py-2.5 text-sm text-error-600 shadow-theme-xs dark:border-error-700 dark:bg-gray-900 dark:text-error-400 flex items-center">
                                <?php esc_html_e( 'No member selected', 'vms' ); ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Visit Date -->
                        <div class="col-span-2">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                <?php esc_html_e( 'Visit Date', 'vms' ); ?>
                                <span class="text-error-500">*</span>
                            </label>
                            <div class="relative flatpickr-wrapper">
                                <input type="date" name="visit_date"
                                    value="<?php echo esc_attr( $_POST['visit_date'] ?? date('Y-m-d') ); ?>"
                                    min="<?php echo date('Y-m-d'); ?>"
                                    class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 date-picker-fix"
                                    required id="visit-date-picker" />
                                <span
                                    class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                                            fill=""></path>
                                    </svg>
                                </span>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                <button @click="isGuestVisitInfoModal = false"
                    class="cursor-pointer flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                    <?php esc_html_e( 'Close', 'vms' ); ?>
                </button>
                <button type="submit" id="submit-guest-form"
                    class="cursor-pointer flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                    <?php esc_html_e( 'Create Guest', 'vms' ); ?>
                </button>
            </div>
        </form>
    </div>
</div>