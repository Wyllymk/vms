<?php
/**
 * Template part for displaying reciprocation modal
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visitor_Management_System
 */
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<div x-show="isReciprocationModal" class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto z-99999">
    <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
    <div @click.outside="isReciprocationModal = false"
        class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
        <!-- close btn -->
        <a @click="isReciprocationModal = false"
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
                <?php esc_html_e( 'Register Reciprocation Member', 'vms' ); ?>
            </h4>
            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                <?php esc_html_e( 'Create a Reciprocation Member Account.', 'vms' ); ?>
            </p>
        </div>
        <form id="reciprocation-form" class="flex flex-col" method="post" enctype="multipart/form-data">
            <input type="hidden" name="register_reciprocation_member" value="1">

            <div class="custom-scrollbar h-xl overflow-y-auto px-2">
                <div class="">
                    <h5 class="mb-5 text-lg font-medium text-gray-800 dark:text-white/90 lg:mb-6">
                        <?php esc_html_e('Personal Information', 'vms'); ?>
                    </h5>

                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                        <!-- First Name -->
                        <div class="col-span-2 lg:col-span-1">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                <?php esc_html_e('First Name', 'vms'); ?>
                                <span class="text-error-500">*</span>
                            </label>
                            <input type="text" name="first_name" required
                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                        </div>

                        <!-- Last Name -->
                        <div class="col-span-2 lg:col-span-1">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                <?php esc_html_e('Last Name', 'vms'); ?>
                                <span class="text-error-500">*</span>
                            </label>
                            <input type="text" name="last_name" required
                                class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                        </div>

                        <!-- Email -->
                        <div class="col-span-2 lg:col-span-1">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                <?php esc_html_e('Email address', 'vms'); ?>
                                <span class="text-error-500">*</span>
                            </label>
                            <input type="email" name="email" required
                                class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                        </div>

                        <!-- Phone -->
                        <div class="col-span-2 lg:col-span-1">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                <?php esc_html_e('Phone', 'vms'); ?>
                                <span class="text-error-500">*</span>
                            </label>
                            <input type="tel" name="phone_number" required
                                class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                <?php esc_html_e('Format: +254700123456 or 0700123456', 'vms'); ?>
                            </p>
                        </div>

                        <!-- Member Number -->
                        <div class="col-span-2 lg:col-span-1">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                <?php esc_html_e('Member Number', 'vms'); ?>
                                <span class="text-error-500">*</span>
                            </label>
                            <input type="text" name="member_number" required
                                class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                        </div>

                        <!-- Reciprocating Club Dropdown -->
                        <div class="col-span-2 lg:col-span-1">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                <?php esc_html_e('Reciprocating Club', 'vms'); ?>
                                <span class="text-error-500">*</span>
                            </label>
                            <select name="host_member_id" required
                                class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                                <option value=""><?php esc_html_e('Select Reciprocating Club', 'vms'); ?></option>
                                <?php
                                // Get reciprocating clubs table
                                global $wpdb;
                                $clubs_table = \WyllyMk\VMS\VMS_Config::get_table_name( \WyllyMk\VMS\VMS_Config::RECIP_CLUBS_TABLE );

                                
                                $clubs = $wpdb->get_results("
                                    SELECT id, club_name, club_email 
                                    FROM $clubs_table 
                                    WHERE status = 'active' 
                                    ORDER BY club_name ASC
                                ");

                                foreach ($clubs as $club) {
                                    $selected = isset($_POST['host_member_id']) && $_POST['host_member_id'] == $club->id ? 'selected' : '';
                                    echo '<option value="' . esc_attr($club->id) . '" ' . $selected . '>'
                                        . esc_html($club->club_name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Visit Date -->
                        <div class="col-span-2">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                <?php esc_html_e('Visit Date', 'vms'); ?>
                                <span class="text-error-500">*</span>
                            </label>
                            <input type="date" name="visit_date" required min="<?php echo date('Y-m-d'); ?>"
                                class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                        </div>

                        <!-- Preferences -->
                        <div x-data="{ checkboxToggle: true }">
                            <label for="receive_messages"
                                class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                                <div class="relative">
                                    <!-- Real checkbox -->
                                    <input type="checkbox" id="receive_messages" name="receive_messages" value="yes"
                                        checked class="sr-only" @change="checkboxToggle = !checkboxToggle">

                                    <!-- Custom styled checkbox -->
                                    <div :class="checkboxToggle ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                                        class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] transition">
                                        <span :class="checkboxToggle ? '' : 'opacity-0'">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white"
                                                    stroke-width="1.94437" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                                <!-- Label text -->
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    <?php esc_html_e('Receive messages', 'vms'); ?>
                                </span>
                            </label>
                        </div>

                        <div x-data="{ checkboxToggle: true }">
                            <label for="receive_emails"
                                class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                                <div class="relative">
                                    <!-- Real checkbox -->
                                    <input type="checkbox" id="receive_emails" name="receive_emails" value="yes" checked
                                        class="sr-only" @change="checkboxToggle = !checkboxToggle">

                                    <!-- Custom styled checkbox -->
                                    <div :class="checkboxToggle ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                                        class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] transition">
                                        <span :class="checkboxToggle ? '' : 'opacity-0'">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white"
                                                    stroke-width="1.94437" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                                <!-- Label text -->
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    <?php esc_html_e('Receive emails', 'vms'); ?>
                                </span>
                            </label>
                        </div>

                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                <button type="reset"
                    class="cursor-pointer flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                    <?php esc_html_e('Reset', 'vms'); ?>
                </button>
                <button type="submit" id="submit-reciprocating-form"
                    class="cursor-pointer flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                    <?php esc_html_e('Create Reciprocating Member', 'vms'); ?>
                </button>
            </div>
        </form>
    </div>
</div>