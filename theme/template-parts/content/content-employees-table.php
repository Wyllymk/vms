<?php
/**
 * Template part for displaying employees table with client-side pagination and filtering
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visitor_Management_System
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Get WordPress roles object
global $wp_roles;

// Get all users with general_manager, reception, or gate role
$users = get_users( array( 'role__in' => array( 'general_manager', 'reception', 'gate' ) ) );

// Search functionality
$search_term = '';
if ( isset( $_GET['search_users'] ) && ! empty( $_GET['user_search'] ) ) {
    $search_term = sanitize_text_field( $_GET['user_search'] );
    
    // Filter users based on search term
    $filtered_users = array();
    foreach ( $users as $user ) {
        $username    = $user->user_login;
        $email       = $user->user_email;
        $first_name  = get_user_meta( $user->ID, 'first_name', true );
        $last_name   = get_user_meta( $user->ID, 'last_name', true );
        
        // Check if search term matches any user field
        if ( stripos( $username, $search_term ) !== false ||
             stripos( $email, $search_term ) !== false ||
             stripos( $first_name, $search_term ) !== false ||
             stripos( $last_name, $search_term ) !== false ) {
            $filtered_users[] = $user;
        }
    }
    $users = $filtered_users;
}

$status_classes = array(
    'pending'   => 'bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500',
    'active'    => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
    'suspended' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
    'banned'    => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
);

// Prepare data for JavaScript
$users_data = array();
foreach ( $users as $user ) {
    $user_id             = $user->ID;
    $email               = $user->user_email;
    $first_name          = get_user_meta( $user_id, 'first_name', true );
    $last_name           = get_user_meta( $user_id, 'last_name', true );
    $registration_status = get_user_meta( $user_id, 'registration_status', true );
    $user_phone_number   = get_user_meta( $user_id, 'phone_number', true );
    
    // Get user role properly
    $user_roles = $user->roles; // returns array of roles
    $user_role  = ! empty( $user_roles ) ? $user_roles[0] : 'guest';
    $role_name  = isset( $wp_roles->roles[ $user_role ]['name'] ) ? $wp_roles->roles[ $user_role ]['name'] : ucfirst( $user_role );
    
    // Fallback for registration status
    if ( empty( $registration_status ) ) {
        $registration_status = 'active';
    }
    
    $users_data[] = array(
        'id'                 => $user_id,
        'role_name'          => $role_name,
        'first_name'         => $first_name,
        'last_name'          => $last_name,
        'email'              => $email,
        'phone_number'       => $user_phone_number,
        'registration_status'=> $registration_status,
        'status_class'       => $status_classes[ $registration_status ] ?? 'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80',
        'details_url'        => esc_url( add_query_arg( 'user_id', $user_id, home_url( '/employee-details' ) ) ),
    );
}
?>

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
    x-data="employeesTable()" x-init="init()">

    <div
        class="mb-4 flex flex-col gap-2 px-4 py-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-800">
        <div class="flex items-center gap-3">
            <span class="text-gray-500 dark:text-gray-400">Show</span>
            <div class="relative z-20 bg-transparent">
                <select x-model="perPage" @change="updatePerPage()"
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-9 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none py-2 pr-8 pl-3 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="absolute top-1/2 right-2 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                    <svg class="stroke-current" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M3.8335 5.9165L8.00016 10.0832L12.1668 5.9165" stroke="" stroke-width="1.2"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
            </div>
            <span class="text-gray-500 dark:text-gray-400">entries</span>
        </div>

        <div class="text-sm text-gray-500 dark:text-gray-400">
            <span x-text="getEntriesText()"></span>
        </div>
    </div>

    <div class="max-w-full overflow-x-auto" id="employees-table">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">#</p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">User Role</p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">First Name</p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Last Name</p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Email</p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Phone Number</p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status</p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">View Details</p>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody id="employees-table-body" class="divide-y divide-gray-100 dark:divide-gray-800">
                <template x-if="paginatedEmployees.length === 0">
                    <tr>
                        <td colspan="8" class="px-4 py-4 text-center text-gray-600 dark:text-white">No employees found.
                        </td>
                    </tr>
                </template>

                <template x-for="(employee, index) in paginatedEmployees" :key="employee.id">
                    <tr>
                        <td class="px-5 py-4 sm:px-6">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="getRowNumber(index)"></p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90"
                                            x-text="employee.role_name"></span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="employee.first_name">
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="employee.last_name">
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="employee.email"></p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400"
                                    x-text="employee.phone_number"></p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <span
                                    class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium capitalize"
                                    :class="employee.status_class" x-text="employee.registration_status"></span>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <form :action="employee.details_url" method="get">
                                <input type="hidden" name="user_id" :value="employee.id">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit
                                </button>
                            </form>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <div x-show="totalPages > 1"
        class="flex items-center justify-between gap-8 px-6 py-4 sm:justify-normal border-t border-gray-200 dark:border-gray-800">

        <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
            :class="currentPage === 1 ? 'border-gray-300 bg-gray-100 text-gray-400 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-500' : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200'"
            class="flex items-center gap-2 rounded-lg border px-2 py-2 text-sm font-medium shadow-theme-xs sm:px-3.5 sm:py-2.5">
            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M2.58203 9.99868C2.58174 10.1909 2.6549 10.3833 2.80152 10.53L7.79818 15.5301C8.09097 15.8231 8.56584 15.8233 8.85883 15.5305C9.15183 15.2377 9.152 14.7629 8.85921 14.4699L5.13911 10.7472L16.6665 10.7472C17.0807 10.7472 17.4165 10.4114 17.4165 9.99715C17.4165 9.58294 17.0807 9.24715 16.6665 9.24715L5.14456 9.24715L8.85919 5.53016C9.15199 5.23717 9.15184 4.7623 8.85885 4.4695C8.56587 4.1767 8.09099 4.17685 7.79819 4.46984L2.84069 9.43049C2.68224 9.568 2.58203 9.77087 2.58203 9.99715C2.58203 9.99766 2.58203 9.99817 2.58203 9.99868Z">
                </path>
            </svg>
            <span class="hidden sm:inline">Previous</span>
        </button>

        <span class="block text-sm font-medium text-gray-700 dark:text-gray-400 sm:hidden"
            x-text="'Page ' + currentPage + ' of ' + totalPages"></span>

        <ul class="hidden items-center gap-0.5 sm:flex">
            <template x-if="pageRange.showFirst">
                <li>
                    <a @click.prevent="goToPage(1)" href="#"
                        class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 hover:bg-brand-500 hover:text-white dark:text-gray-400 dark:hover:text-white">1</a>
                </li>
            </template>

            <template x-if="pageRange.showFirstEllipsis">
                <li><span
                        class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 dark:text-gray-400">...</span>
                </li>
            </template>

            <template x-for="page in pageRange.pages" :key="page">
                <li>
                    <a @click.prevent="goToPage(page)" href="#"
                        :class="page === currentPage ? 'bg-brand-500 text-white' : 'text-gray-700 hover:bg-brand-500 hover:text-white dark:text-gray-400 dark:hover:text-white'"
                        class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium"
                        x-text="page"></a>
                </li>
            </template>

            <template x-if="pageRange.showLastEllipsis">
                <li><span
                        class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 dark:text-gray-400">...</span>
                </li>
            </template>

            <template x-if="pageRange.showLast">
                <li>
                    <a @click.prevent="goToPage(totalPages)" href="#"
                        class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 hover:bg-brand-500 hover:text-white dark:text-gray-400 dark:hover:text-white"
                        x-text="totalPages"></a>
                </li>
            </template>
        </ul>

        <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages"
            :class="currentPage === totalPages ? 'border-gray-300 bg-gray-100 text-gray-400 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-500' : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200'"
            class="flex items-center gap-2 rounded-lg border px-2 py-2 text-sm font-medium shadow-theme-xs sm:px-3.5 sm:py-2.5">
            <span class="hidden sm:inline">Next</span>
            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M17.4165 9.9986C17.4168 10.1909 17.3437 10.3832 17.197 10.53L12.2004 15.5301C11.9076 15.8231 11.4327 15.8233 11.1397 15.5305C10.8467 15.2377 10.8465 14.7629 11.1393 14.4699L14.8594 10.7472L3.33203 10.7472C2.91782 10.7472 2.58203 10.4114 2.58203 9.99715C2.58203 9.58294 2.91782 9.24715 3.33203 9.24715L14.854 9.24715L11.1393 5.53016C10.8465 5.23717 10.8467 4.7623 11.1397 4.4695C11.4327 4.1767 11.9075 4.17685 12.2003 4.46984L17.1578 9.43049C17.3163 9.568 17.4165 9.77087 17.4165 9.99715C17.4165 9.99763 17.4165 9.99812 17.4165 9.9986Z">
                </path>
            </svg>
        </button>
    </div>
</div>

<script>
function employeesTable() {
    return {
        allEmployees: <?php echo json_encode( $users_data ); ?>,
        perPage: 25,
        currentPage: 1,

        init() {
            const savedPerPage = localStorage.getItem('employees_per_page');
            const savedPage = localStorage.getItem('employees_current_page');

            if (savedPerPage) {
                this.perPage = parseInt(savedPerPage);
            }

            if (savedPage) {
                this.currentPage = parseInt(savedPage);
            }
        },

        get totalPages() {
            return Math.ceil(this.allEmployees.length / this.perPage);
        },

        get paginatedEmployees() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            return this.allEmployees.slice(start, end);
        },

        get pageRange() {
            const range = 2;
            const start = Math.max(1, this.currentPage - range);
            const end = Math.min(this.totalPages, this.currentPage + range);

            const pages = [];
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }

            return {
                pages: pages,
                showFirst: start > 1,
                showFirstEllipsis: start > 2,
                showLast: end < this.totalPages,
                showLastEllipsis: end < this.totalPages - 1
            };
        },

        updatePerPage() {
            localStorage.setItem('employees_per_page', this.perPage);
            this.currentPage = 1;
            localStorage.setItem('employees_current_page', this.currentPage);
        },

        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                localStorage.setItem('employees_current_page', this.currentPage);
            }
        },

        getRowNumber(index) {
            return ((this.currentPage - 1) * this.perPage) + index + 1;
        },

        getEntriesText() {
            const start = this.allEmployees.length > 0 ? ((this.currentPage - 1) * this.perPage) + 1 : 0;
            const end = Math.min(this.currentPage * this.perPage, this.allEmployees.length);
            const total = this.allEmployees.length;

            return `Showing ${start} to ${end} of ${total} entries`;
        }
    }
}
</script>