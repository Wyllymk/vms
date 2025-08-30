<?php
/**
 * Template part for displaying address modal
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visitor_Management_System
 */
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<div x-show="isPasswordModal" class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto z-99999">
    <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
    <div @click.outside="isPasswordModal = false"
        class="no-scrollbar relative flex w-full max-w-lg flex-col overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-11">
        <!-- close btn -->
        <a @click="isPasswordModal = false"
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
                <?php esc_html_e( 'Change Password', 'vms' ); ?>
            </h4>
            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                <?php esc_html_e( 'Update your password to keep your account secure.', 'vms' ); ?>
            </p>
        </div>
        <form id="password-form" class="flex flex-col" method="post">
            <div class="-mx-2.5 flex flex-wrap gap-y-5">
                <!-- Current Password Field -->
                <div class="w-full px-2.5">
                    <label for="current_password"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Password</label>
                    <div x-data="{ showPassword: false }" class="relative">
                        <span class="absolute top-1/2 left-4 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.6252 13.9582C10.6252 13.613 10.3453 13.3332 10.0002 13.3332C9.65498 13.3332 9.37516 13.613 9.37516 13.9582V15.2082C9.37516 15.5533 9.65498 15.8332 10.0002 15.8332C10.3453 15.8332 10.6252 15.5533 10.6252 15.2082V13.9582Z"
                                    fill="#667085"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10.0002 1.6665C7.58392 1.6665 5.62516 3.62526 5.62516 6.0415V7.604H4.5835C3.54796 7.604 2.7085 8.44347 2.7085 9.479V16.4578C2.7085 17.4933 3.54796 18.3328 4.5835 18.3328H15.4168C16.4524 18.3328 17.2918 17.4933 17.2918 16.4578V9.479C17.2918 8.44347 16.4524 7.604 15.4168 7.604H14.3752V6.0415C14.3752 3.62526 12.4164 1.6665 10.0002 1.6665ZM13.1252 6.0415V7.604H6.87516V6.0415C6.87516 4.31561 8.27427 2.9165 10.0002 2.9165C11.7261 2.9165 13.1252 4.31561 13.1252 6.0415ZM4.5835 8.854C4.23832 8.854 3.9585 9.13383 3.9585 9.479V16.4578C3.9585 16.8029 4.23832 17.0828 4.5835 17.0828H15.4168C15.762 17.0828 16.0418 16.8029 16.0418 16.4578V9.479C16.0418 9.13383 15.762 8.854 15.4168 8.854H4.5835Z"
                                    fill=""></path>
                            </svg>
                        </span>
                        <input :type="showPassword ? 'text' : 'password'" placeholder="Enter current password"
                            name="current_password" id="current_password"
                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-11 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                            required type="password">
                        <span @click="showPassword = !showPassword"
                            class="absolute z-30 text-gray-500 -translate-y-1/2 cursor-pointer right-4 top-1/2 dark:text-gray-400">
                            <svg x-show="!showPassword" class="fill-current" width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C13.5184 15.3619 16.5055 13.0957 17.5849 9.94555C17.6389 9.78797 17.6389 9.6169 17.5849 9.45932C16.5055 6.30919 13.5184 4.04297 10.0002 4.04297ZM9.99151 7.84413C8.96527 7.84413 8.13333 8.67606 8.13333 9.70231C8.13333 10.7286 8.96527 11.5605 9.99151 11.5605H10.0064C11.0326 11.5605 11.8646 10.7286 11.8646 9.70231C11.8646 8.67606 11.0326 7.84413 10.0064 7.84413H9.99151Z"
                                    fill="#98A2B3"></path>
                            </svg>
                            <svg x-show="showPassword" class="fill-current" width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.63803 3.57709C4.34513 3.2842 3.87026 3.2842 3.57737 3.57709C3.28447 3.86999 3.28447 4.34486 3.57737 4.63775L4.85323 5.91362C3.74609 6.84199 2.89363 8.06395 2.4155 9.45936C2.3615 9.61694 2.3615 9.78801 2.41549 9.94558C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z"
                                    fill="#98A2B3"></path>
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- New Password Field -->
                <div class="w-full px-2.5">
                    <label for="new_password"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                    <div x-data="{ showPassword: false }" class="relative">
                        <span class="absolute top-1/2 left-4 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.6252 13.9582C10.6252 13.613 10.3453 13.3332 10.0002 13.3332C9.65498 13.3332 9.37516 13.613 9.37516 13.9582V15.2082C9.37516 15.5533 9.65498 15.8332 10.0002 15.8332C10.3453 15.8332 10.6252 15.5533 10.6252 15.2082V13.9582Z"
                                    fill="#667085"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10.0002 1.6665C7.58392 1.6665 5.62516 3.62526 5.62516 6.0415V7.604H4.5835C3.54796 7.604 2.7085 8.44347 2.7085 9.479V16.4578C2.7085 17.4933 3.54796 18.3328 4.5835 18.3328H15.4168C16.4524 18.3328 17.2918 17.4933 17.2918 16.4578V9.479C17.2918 8.44347 16.4524 7.604 15.4168 7.604H14.3752V6.0415C14.3752 3.62526 12.4164 1.6665 10.0002 1.6665ZM13.1252 6.0415V7.604H6.87516V6.0415C6.87516 4.31561 8.27427 2.9165 10.0002 2.9165C11.7261 2.9165 13.1252 4.31561 13.1252 6.0415ZM4.5835 8.854C4.23832 8.854 3.9585 9.13383 3.9585 9.479V16.4578C3.9585 16.8029 4.23832 17.0828 4.5835 17.0828H15.4168C15.762 17.0828 16.0418 16.8029 16.0418 16.4578V9.479C16.0418 9.13383 15.762 8.854 15.4168 8.854H4.5835Z"
                                    fill=""></path>
                            </svg>
                        </span>
                        <input :type="showPassword ? 'text' : 'password'" placeholder="Enter new password"
                            name="new_password" id="new_password"
                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-11 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                            required type="password" minlength="8">
                        <span @click="showPassword = !showPassword"
                            class="absolute z-30 text-gray-500 -translate-y-1/2 cursor-pointer right-4 top-1/2 dark:text-gray-400">
                            <svg x-show="!showPassword" class="fill-current" width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C13.5184 15.3619 16.5055 13.0957 17.5849 9.94555C17.6389 9.78797 17.6389 9.6169 17.5849 9.45932C16.5055 6.30919 13.5184 4.04297 10.0002 4.04297ZM9.99151 7.84413C8.96527 7.84413 8.13333 8.67606 8.13333 9.70231C8.13333 10.7286 8.96527 11.5605 9.99151 11.5605H10.0064C11.0326 11.5605 11.8646 10.7286 11.8646 9.70231C11.8646 8.67606 11.0326 7.84413 10.0064 7.84413H9.99151Z"
                                    fill="#98A2B3"></path>
                            </svg>
                            <svg x-show="showPassword" class="fill-current" width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.63803 3.57709C4.34513 3.2842 3.87026 3.2842 3.57737 3.57709C3.28447 3.86999 3.28447 4.34486 3.57737 4.63775L4.85323 5.91362C3.74609 6.84199 2.89363 8.06395 2.4155 9.45936C2.3615 9.61694 2.3615 9.78801 2.41549 9.94558C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z"
                                    fill="#98A2B3"></path>
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div class="w-full px-2.5">
                    <label for="confirm_password"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm New
                        Password</label>
                    <div x-data="{ showPassword: false }" class="relative">
                        <span class="absolute top-1/2 left-4 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.6252 13.9582C10.6252 13.613 10.3453 13.3332 10.0002 13.3332C9.65498 13.3332 9.37516 13.613 9.37516 13.9582V15.2082C9.37516 15.5533 9.65498 15.8332 10.0002 15.8332C10.3453 15.8332 10.6252 15.5533 10.6252 15.2082V13.9582Z"
                                    fill="#667085"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10.0002 1.6665C7.58392 1.6665 5.62516 3.62526 5.62516 6.0415V7.604H4.5835C3.54796 7.604 2.7085 8.44347 2.7085 9.479V16.4578C2.7085 17.4933 3.54796 18.3328 4.5835 18.3328H15.4168C16.4524 18.3328 17.2918 17.4933 17.2918 16.4578V9.479C17.2918 8.44347 16.4524 7.604 15.4168 7.604H14.3752V6.0415C14.3752 3.62526 12.4164 1.6665 10.0002 1.6665ZM13.1252 6.0415V7.604H6.87516V6.0415C6.87516 4.31561 8.27427 2.9165 10.0002 2.9165C11.7261 2.9165 13.1252 4.31561 13.1252 6.0415ZM4.5835 8.854C4.23832 8.854 3.9585 9.13383 3.9585 9.479V16.4578C3.9585 16.8029 4.23832 17.0828 4.5835 17.0828H15.4168C15.762 17.0828 16.0418 16.8029 16.0418 16.4578V9.479C16.0418 9.13383 15.762 8.854 15.4168 8.854H4.5835Z"
                                    fill=""></path>
                            </svg>
                        </span>
                        <input :type="showPassword ? 'text' : 'password'" placeholder="Confirm new password"
                            name="confirm_password" id="confirm_password"
                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-11 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                            required type="password" minlength="8">
                        <span @click="showPassword = !showPassword"
                            class="absolute z-30 text-gray-500 -translate-y-1/2 cursor-pointer right-4 top-1/2 dark:text-gray-400">
                            <svg x-show="!showPassword" class="fill-current" width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C13.5184 15.3619 16.5055 13.0957 17.5849 9.94555C17.6389 9.78797 17.6389 9.6169 17.5849 9.45932C16.5055 6.30919 13.5184 4.04297 10.0002 4.04297ZM9.99151 7.84413C8.96527 7.84413 8.13333 8.67606 8.13333 9.70231C8.13333 10.7286 8.96527 11.5605 9.99151 11.5605H10.0064C11.0326 11.5605 11.8646 10.7286 11.8646 9.70231C11.8646 8.67606 11.0326 7.84413 10.0064 7.84413H9.99151Z"
                                    fill="#98A2B3"></path>
                            </svg>
                            <svg x-show="showPassword" class="fill-current" width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.63803 3.57709C4.34513 3.2842 3.87026 3.2842 3.57737 3.57709C3.28447 3.86999 3.28447 4.34486 3.57737 4.63775L4.85323 5.91362C3.74609 6.84199 2.89363 8.06395 2.4155 9.45936C2.3615 9.61694 2.3615 9.78801 2.41549 9.94558C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z"
                                    fill="#98A2B3"></path>
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Password Requirements -->
                <div class="w-full px-2.5">
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-300 mb-2">Password Requirements:
                        </h4>
                        <ul class="text-sm text-blue-800 dark:text-blue-300 space-y-1">
                            <li>• Minimum 8 characters long</li>
                            <li>• Include uppercase and lowercase letters</li>
                            <li>• Include at least one number</li>
                            <li>• Include at least one special character</li>
                        </ul>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="w-full px-2.5">
                    <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                        <button @click="isPasswordModal = false" type="button"
                            class="cursor-pointer flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                            Close
                        </button>
                        <button type="submit" id="password-submit-button"
                            class="cursor-pointer flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                            Change Password
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>