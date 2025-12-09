<?php
/**
 * Template part for displaying sidebar
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visitor_Management_System
 */
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
$current_user = wp_get_current_user();
$user_id      = $current_user->ID;
$user_data    = get_userdata( $user_id );
$user_avatar  = get_avatar_url( $user_id );

// Get first and last name
$first_name = get_user_meta( $user_id, 'first_name', true );
$last_name  = get_user_meta( $user_id, 'last_name', true );

// Or fallback to display_name if empty
$full_name = trim( $first_name . ' ' . $last_name );
if ( empty( $full_name ) ) {
	$full_name = $user_data->display_name;
}
?>
<?php if ( ( current_user_can( 'administrator' ) || current_user_can( 'chairman' ) || current_user_can( 'general_manager' ) || current_user_can( 'reception' ) || current_user_can( 'gate' ) ) ) : ?>

<aside :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-99999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0">
    <!-- SIDEBAR HEADER -->
    <div :class="sidebarToggle ? 'justify-center' : 'justify-between'"
        class="flex items-center gap-2 pt-8 sidebar-header pb-7">
        <a href="<?php echo esc_url( home_url() ); ?>">
            <span class="flex items-center justify-center gap-4 logo" :class="sidebarToggle ? 'hidden' : ''">
                <img class="h-12" loading="lazy"
                    src="<?php echo get_template_directory_uri(); ?>/assets/images/logo/logo.png" alt="Logo" />
                <h2 class="text-2xl font-bold text-black dark:text-white font-satisfy">
                    <?php esc_html_e( 'Nyeri Club', 'vms' ); ?>
                </h2>
            </span>
            <img class="h-10 logo-icon" :class="sidebarToggle ? 'lg:block hidden' : 'hidden'" loading="lazy"
                src="<?php echo get_template_directory_uri(); ?>/assets/images/logo/logo.png" alt="Logo" />
        </a>
    </div>
    <!-- SIDEBAR HEADER -->
    <!-- SIDEBAR BODY -->
    <div class="flex flex-col mt-5 overflow-y-auto duration-300 ease-linear no-scrollbar lg:mt-0">
        <!-- Sidebar Menu -->
        <nav x-data="{selected: $persist('Dashboard')}">
            <!-- Menu Group -->
            <div>
                <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                    <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
                        <?php esc_html_e( 'MENU', 'vms' ); ?>
                    </span>

                    <svg :class="sidebarToggle ? 'lg:block hidden' : 'hidden'"
                        class="mx-auto fill-current menu-group-icon" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                            fill="" />
                    </svg>
                </h3>

                <ul class="flex flex-col gap-2 mb-6 md:gap-4">
                    <!-- Menu Item Dashboard -->
                    <?php if ( ( current_user_can( 'administrator' ) || current_user_can( 'chairman' ) || current_user_can( 'general_manager' ) || current_user_can( 'reception' ) ) ) : ?>
                    <li>
                        <a href="<?php echo esc_url( home_url( '/dashboard' ) ); ?>"
                            @click="selected = (selected === 'Dashboard' ? '':'Dashboard')"
                            class="<?php echo ( is_page( 'dashboard' ) ) ? 'menu-item group menu-item-active' : 'menu-item group menu-item-inactive'; ?>">
                            <svg class="<?php echo ( is_page( 'Dashboard' ) ) ? 'menu-item-icon-active' : 'menu-item-icon-inactive'; ?>"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z"
                                    fill="" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                <?php esc_html_e( 'Dashboard', 'vms' ); ?>
                            </span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <!-- Menu Item Dashboard -->

                    <!-- Menu Item Profile -->
                    <?php if ( ( current_user_can( 'administrator' ) || current_user_can( 'chairman' ) || current_user_can( 'general_manager' ) || current_user_can( 'reception' ) ) ) : ?>
                    <li>
                        <a href="<?php echo esc_url( home_url( '/profile' ) ); ?>"
                            @click="selected = (selected === 'Profile' ? '':'Profile')"
                            class="<?php echo ( is_page( 'profile' ) ) ? 'menu-item group menu-item-active' : 'menu-item group menu-item-inactive'; ?>">
                            <svg class="<?php echo ( is_page( 'profile' ) ) ? 'menu-item-icon-active' : 'menu-item-icon-inactive'; ?>"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z"
                                    fill="" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                <?php esc_html_e( 'User Profile', 'vms' ); ?>
                            </span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <!-- Menu Item Profile -->

                    <!-- Menu Item Members -->
                    <?php if ( ( current_user_can( 'administrator' ) || current_user_can( 'chairman' ) || current_user_can( 'general_manager' ) || current_user_can( 'reception' ) ) ) : ?>
                    <li>
                        <a href="<?php echo esc_url( home_url( '/members' ) ); ?>"
                            @click="selected = (selected === 'Members' ? '' : 'Members')"
                            class="<?php echo ( is_page( array( 'members', 'details' ) ) ) ? 'menu-item group menu-item-active' : 'menu-item group menu-item-inactive'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                <?php esc_html_e( 'Members', 'vms' ); ?>
                            </span>
                        </a>
                    </li>

                    <?php endif; ?>
                    <!-- Menu Item Members -->

                    <!-- Menu Item Staff -->
                    <?php if ( ( current_user_can( 'administrator' ) || current_user_can( 'general_manager' ) || current_user_can( 'chairman' ) ) ) : ?>
                    <li>
                        <a href="<?php echo esc_url( home_url( '/employees' ) ); ?>"
                            @click="selected = (selected === 'Employees' ? '':'Employees')"
                            class="<?php echo ( is_page( array( 'employees', 'employee-details' ) ) ) ? 'menu-item group menu-item-active' : 'menu-item group menu-item-inactive'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" fill=""></path>
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                <?php esc_html_e( 'Staff', 'vms' ); ?>
                            </span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <!-- Menu Item Staff -->

                    <!-- Menu Item Guests -->
                    <?php if ( ( current_user_can( 'administrator' ) || current_user_can( 'general_manager' ) || current_user_can( 'chairman' ) || current_user_can( 'reception' ) || current_user_can( 'gate' ) ) ) : ?>
                    <li>
                        <a href="<?php echo esc_url( home_url( '/guests' ) ); ?>"
                            @click="selected = (selected === 'Guests' ? '':'Guests')"
                            class="<?php echo ( is_page( array( 'guests', 'guest-details' ) ) ) ? 'menu-item group menu-item-active' : 'menu-item group menu-item-inactive'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                <?php esc_html_e( 'Guests', 'vms' ); ?>
                            </span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <!-- Menu Item Guests -->

                    <!-- Menu Item Suppliers -->
                    <?php if ( ( current_user_can( 'administrator' ) || current_user_can( 'general_manager' ) || current_user_can( 'chairman' ) || current_user_can( 'reception' ) || current_user_can( 'gate' ) ) ) : ?>
                    <li>
                        <a href="<?php echo esc_url( home_url( '/suppliers' ) ); ?>"
                            @click="selected = (selected === 'Suppliers' ? '':'Suppliers')"
                            class="<?php echo ( is_page( array( 'suppliers', 'supplier-details' ) ) ) ? 'menu-item group menu-item-active' : 'menu-item group menu-item-inactive'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="1" y="3" width="15" height="13"></rect>
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                            </svg>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                <?php esc_html_e( 'Suppliers', 'vms' ); ?>
                            </span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <!-- Menu Item Suppliers -->

                    <!-- Menu Item Accommodation -->
                    <?php if ( ( current_user_can( 'administrator' ) || current_user_can( 'general_manager' ) || current_user_can( 'chairman' ) || current_user_can( 'reception' ) || current_user_can( 'gate' ) ) ) : ?>
                    <li>
                        <a href="<?php echo esc_url( home_url( '/accommodation' ) ); ?>"
                            @click="selected = (selected === 'Accommodation' ? '':'Accommodation')"
                            class="<?php echo ( is_page( array( 'accommodation', 'accommodation-details' ) ) ) ? 'menu-item group menu-item-active' : 'menu-item group menu-item-inactive'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M2 4v16"></path>
                                <path d="M2 8h18a2 2 0 0 1 2 2v10"></path>
                                <path d="M2 17h20"></path>
                                <path d="M6 8V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v4"></path>
                            </svg>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                <?php esc_html_e( 'Accommodation', 'vms' ); ?>
                            </span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <!-- Menu Item Accommodation -->

                    <!-- Menu Item Reciprocating Members -->
                    <?php if ( ( current_user_can( 'administrator' ) || current_user_can( 'general_manager' ) || current_user_can( 'chairman' ) || current_user_can( 'reception' ) || current_user_can( 'gate' ) ) ) : ?>
                    <li>
                        <a href="<?php echo esc_url( home_url( '/reciprocating-members' ) ); ?>"
                            @click="selected = (selected === 'Guests' ? '':'Guests')"
                            class="<?php echo ( is_page( array( 'reciprocating-members', 'reciprocating-member-details' ) ) ) ? 'menu-item group menu-item-active' : 'menu-item group menu-item-inactive'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                <?php esc_html_e( 'Reciprocating Members', 'vms' ); ?>
                            </span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <!-- Menu Item Reciprocating Members -->

                    <!-- Menu Item Clubs -->
                    <?php if ( ( current_user_can( 'administrator' ) || current_user_can( 'general_manager' ) || current_user_can( 'chairman' ) || current_user_can( 'reception' ) ) ) : ?>
                    <li>
                        <a href="<?php echo esc_url( home_url( '/clubs' ) ); ?>"
                            @click="selected = (selected === 'Clubs' ? '':'Clubs')"
                            class="<?php echo ( is_page( array( 'clubs' ) ) ) ? 'menu-item group menu-item-active' : 'menu-item group menu-item-inactive'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                            </svg>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                <?php esc_html_e( 'Clubs', 'vms' ); ?>
                            </span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <!-- Menu Item Clubs -->
                </ul>
            </div>
            <!-- Support Group -->
            <?php if ( ( current_user_can( 'administrator' ) || current_user_can( 'chairman' ) || current_user_can( 'general_manager' ) ) ) : ?>
            <div>
                <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                    <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
                        <?php esc_html_e( 'Support', 'vms' ); ?>
                    </span>

                    <svg :class="sidebarToggle ? 'lg:block hidden' : 'hidden'"
                        class="mx-auto fill-current menu-group-icon" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                            fill="" />
                    </svg>
                </h3>

                <ul class="flex flex-col gap-2 mb-6 md:gap-4">

                    <!-- Menu Item Reports -->
                    <li>
                        <a href="<?php echo esc_url( home_url( '/reports' ) ); ?>"
                            @click="selected = (selected === 'Reports' ? '':'Reports')"
                            class="<?php echo ( is_page( 'reports' ) ) ? 'menu-item group menu-item-active' : 'menu-item group menu-item-inactive'; ?>">
                            <svg class="<?php echo ( is_page( 'reports' ) ) ? 'menu-item-icon-active' : 'menu-item-icon-inactive'; ?>"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M3 3C3 2.44772 3.44772 2 4 2H9C9.55228 2 10 2.44772 10 3V12C10 12.5523 9.55228 13 9 13H4C3.44772 13 3 12.5523 3 12V3ZM5 4V11H8V4H5ZM14 10C14 9.44772 14.4477 9 15 9H20C20.5523 9 21 9.44772 21 10V21C21 21.5523 20.5523 22 20 22H15C14.4477 22 14 21.5523 14 21V10ZM16 11V20H19V11H16ZM3 16C3 15.4477 3.44772 15 4 15H9C9.55228 15 10 15.4477 10 16V21C10 21.5523 9.55228 22 9 22H4C3.44772 22 3 21.5523 3 21V16ZM5 17V20H8V17H5ZM15 2C14.4477 2 14 2.44772 14 3V6C14 6.55228 14.4477 7 15 7H20C20.5523 7 21 6.55228 21 6V3C21 2.44772 20.5523 2 20 2H15ZM16 4V5H19V4H16Z"
                                    fill="" />
                            </svg>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                <?php esc_html_e( 'Reports', 'vms' ); ?>
                            </span>
                        </a>
                    </li>
                    <!-- Menu Item Reports -->

                    <?php if ( ( current_user_can( 'administrator' ) ) ) : ?>
                    <!-- Menu Item Settings -->
                    <li>
                        <a href="<?php echo esc_url( home_url( '/settings' ) ); ?>"
                            @click="selected = (selected === 'Settings' ? '':'Settings')"
                            class="<?php echo ( is_page( 'settings' ) ) ? 'menu-item group menu-item-active' : 'menu-item group menu-item-inactive'; ?>">
                            <svg class="<?php echo ( is_page( 'settings' ) ) ? 'menu-item-icon-active' : 'menu-item-icon-inactive'; ?>"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10.4858 3.5L13.5182 3.5C13.9233 3.5 14.2518 3.82851 14.2518 4.23377C14.2518 5.9529 16.1129 7.02795 17.602 6.1682C17.9528 5.96567 18.4014 6.08586 18.6039 6.43667L20.1203 9.0631C20.3229 9.41407 20.2027 9.86286 19.8517 10.0655C18.3625 10.9253 18.3625 13.0747 19.8517 13.9345C20.2026 14.1372 20.3229 14.5859 20.1203 14.9369L18.6039 17.5634C18.4013 17.9142 17.9528 18.0344 17.602 17.8318C16.1129 16.9721 14.2518 18.0471 14.2518 19.7663C14.2518 20.1715 13.9233 20.5 13.5182 20.5H10.4858C10.0804 20.5 9.75182 20.1714 9.75182 19.766C9.75182 18.0461 7.88983 16.9717 6.40067 17.8314C6.04945 18.0342 5.60037 17.9139 5.39767 17.5628L3.88167 14.937C3.67903 14.586 3.79928 14.1372 4.15026 13.9346C5.63949 13.0748 5.63946 10.9253 4.15025 10.0655C3.79926 9.86282 3.67901 9.41401 3.88165 9.06303L5.39764 6.43725C5.60034 6.08617 6.04943 5.96581 6.40065 6.16858C7.88982 7.02836 9.75182 5.9539 9.75182 4.23399C9.75182 3.82862 10.0804 3.5 10.4858 3.5ZM13.5182 2L10.4858 2C9.25201 2 8.25182 3.00019 8.25182 4.23399C8.25182 4.79884 7.64013 5.15215 7.15065 4.86955C6.08213 4.25263 4.71559 4.61859 4.0986 5.68725L2.58261 8.31303C1.96575 9.38146 2.33183 10.7477 3.40025 11.3645C3.88948 11.647 3.88947 12.3531 3.40026 12.6355C2.33184 13.2524 1.96578 14.6186 2.58263 15.687L4.09863 18.3128C4.71562 19.3814 6.08215 19.7474 7.15067 19.1305C7.64015 18.8479 8.25182 19.2012 8.25182 19.766C8.25182 20.9998 9.25201 22 10.4858 22H13.5182C14.7519 22 15.7518 20.9998 15.7518 19.7663C15.7518 19.2015 16.3632 18.8487 16.852 19.1309C17.9202 19.7476 19.2862 19.3816 19.9029 18.3134L21.4193 15.6869C22.0361 14.6185 21.6701 13.2523 20.6017 12.6355C20.1125 12.3531 20.1125 11.647 20.6017 11.3645C21.6701 10.7477 22.0362 9.38152 21.4193 8.3131L19.903 5.68667C19.2862 4.61842 17.9202 4.25241 16.852 4.86917C16.3632 5.15138 15.7518 4.79856 15.7518 4.23377C15.7518 3.00024 14.7519 2 13.5182 2ZM9.6659 11.9999C9.6659 10.7103 10.7113 9.66493 12.0009 9.66493C13.2905 9.66493 14.3359 10.7103 14.3359 11.9999C14.3359 13.2895 13.2905 14.3349 12.0009 14.3349C10.7113 14.3349 9.6659 13.2895 9.6659 11.9999ZM12.0009 8.16493C9.88289 8.16493 8.1659 9.88191 8.1659 11.9999C8.1659 14.1179 9.88289 15.8349 12.0009 15.8349C14.1189 15.8349 15.8359 14.1179 15.8359 11.9999C15.8359 9.88191 14.1189 8.16493 12.0009 8.16493Z"
                                    fill="" />
                            </svg>
                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                <?php esc_html_e( 'Settings', 'vms' ); ?>
                            </span>
                        </a>
                    </li>
                    <!-- Menu Item Settings -->
                    <?php endif; ?>
                </ul>
            </div>
            <?php endif; ?>
        </nav>
        <!-- Sidebar Menu -->
    </div>
    <!-- SIDEBAR BODY -->
    <!-- SIDEBAR FOOTER -->
    <div class="fixed bottom-0 flex flex-col mt-10 overflow-hidden duration-300 ease-linear w-65 no-scrollbar lg:mt-0">

        <!-- Gradient divider line -->
        <div class="w-full h-px bg-gradient-to-r from-transparent via-black/30 to-transparent dark:via-white/30">
        </div>

        <div class="mt-2 text-center">
            <span class="block font-medium text-gray-700 text-theme-sm dark:text-gray-400">
                <?php echo esc_html( $full_name ); ?>
            </span>
            <span class="text-theme-xs mt-0.5 block text-brand-500">
                <?php esc_html_e( 'Version 1.0.0', 'vms' ); ?>
            </span>
        </div>
    </div>
    <!-- SIDEBAR FOOTER -->

</aside>

<?php endif; ?>