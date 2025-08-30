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

$errors           = array();
$success_messages = array();
$current_user     = wp_get_current_user();
$user_id          = $current_user->ID;
$user_data        = get_userdata( $user_id );
$user_avatar      = get_avatar_url( $user_id );
$user_phone       = get_user_meta( $user_id, 'phone_number', true );
$user_bio         = get_user_meta( $user_id, 'description', true );
$receive_messages = get_user_meta( $user_id, 'receive_messages', true );
$receive_emails   = get_user_meta( $user_id, 'receive_emails', true );
?>

<div x-show="isProfileInfoModal" class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto z-99999">
    <div class="fixed inset-0 w-full h-full modal-close-btn bg-gray-400/50 backdrop-blur-sm"></div>
    <div @click.outside="isProfileInfoModal = false"
        class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
        <!-- close btn -->
        <a @click="isProfileInfoModal = false"
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
                <?php esc_html_e( 'Edit Personal Information', 'vms' ); ?>
            </h4>
            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                <?php esc_html_e( 'Update your details to keep your profile up-to-date.', 'vms' ); ?>
            </p>
        </div>
        <form id="profile-form" class="flex flex-col" method="post" enctype="multipart/form-data">
            <input type="hidden" name="update_user" value="1">
            <div class="custom-scrollbar h-[450px] overflow-y-auto px-2">
                <div class="mt-7">
                    <h5 class="mb-5 text-lg font-medium text-gray-800 dark:text-white/90 lg:mb-6">
                        <?php esc_html_e( 'Personal Information', 'vms' ); ?>
                    </h5>

                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                        <div class="col-span-2 lg:col-span-1">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                <?php esc_html_e( 'First Name', 'vms' ); ?>
                            </label>
                            <input type="text" name="first_name"
                                value="<?php echo esc_attr( $current_user->first_name ); ?>"
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                        </div>

                        <div class="col-span-2 lg:col-span-1">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                <?php esc_html_e( 'Last Name', 'vms' ); ?>
                            </label>
                            <input type="text" name="last_name"
                                value="<?php echo esc_attr( $current_user->last_name ); ?>"
                                class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                        </div>

                        <div class="col-span-2 lg:col-span-1">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                <?php esc_html_e( 'Email address', 'vms' ); ?>
                            </label>
                            <input type="email" name="email"
                                value="<?php echo esc_attr( $current_user->user_email ); ?>"
                                class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                        </div>

                        <div class="col-span-2 lg:col-span-1">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                <?php esc_html_e( 'Phone', 'vms' ); ?>
                            </label>
                            <input type="number" name="phone_number" value="<?php echo esc_attr( $user_phone ); ?>"
                                class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                        </div>

                        <div class="col-span-2">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                <?php esc_html_e( 'Bio', 'vms' ); ?>
                            </label>
                            <textarea name="description"
                                class="w-full px-3 py-2 text-sm text-gray-800 border border-gray-300 rounded dark:bg-gray-900 dark:text-white/90 dark:border-gray-700"><?php echo esc_textarea( $user_bio ); ?></textarea>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="receive_messages" value="yes"
                                    <?php checked( $receive_messages, 'yes' ); ?>
                                    class="border-gray-300 rounded shadow-sm text-brand-500 focus:border-brand-300 focus:ring focus:ring-brand-200 focus:ring-opacity-50" />
                                <span
                                    class="ml-2 text-sm text-gray-700 dark:text-gray-300"><?php esc_html_e( 'Receive messages', 'vms' ); ?></span>
                            </label>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="receive_emails" value="yes"
                                    <?php checked( $receive_emails, 'yes' ); ?>
                                    class="border-gray-300 rounded shadow-sm text-brand-500 focus:border-brand-300 focus:ring focus:ring-brand-200 focus:ring-opacity-50" />
                                <span
                                    class="ml-2 text-sm text-gray-700 dark:text-gray-300"><?php esc_html_e( 'Receive emails', 'vms' ); ?></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                <button @click="isProfileInfoModal = false" type="button"
                    class="cursor-pointer flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                    <?php esc_html_e( 'Close', 'vms' ); ?>
                </button>
                <button type="submit" id="submit-button"
                    class="cursor-pointer flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                    <?php esc_html_e( 'Save Changes', 'vms' ); ?>
                </button>
            </div>
        </form>
    </div>
</div>