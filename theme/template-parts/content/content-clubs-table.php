<?php
/**
 * Template part for displaying clubs table with client-side pagination and filtering
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visitor_Management_System
 */
defined( 'ABSPATH' ) || exit;

global $wpdb;
$clubs_table = \WyllyMk\VMS\VMS_Config::get_table_name( \WyllyMk\VMS\VMS_Config::RECIP_CLUBS_TABLE );

// Fetch ALL clubs - no pagination or search in PHP since we'll do it client-side
$query = "SELECT * FROM {$clubs_table} ORDER BY created_at DESC";
$all_clubs = $wpdb->get_results( $query );

$status_classes = array(
    'active'    => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
    'suspended' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
    'banned'    => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
);

// Prepare data for JavaScript
$clubs_data = array();
foreach ( $all_clubs as $club ) {
    $creation_date = ! empty( $club->created_at ) ? date( 'M j, Y', strtotime( $club->created_at ) ) : 'N/A';
    $update_date   = ! empty( $club->updated_at ) ? date( 'M j, Y', strtotime( $club->updated_at ) ) : 'N/A';
    
    $clubs_data[] = array(
        'id'              => $club->id,
        'club_name'       => $club->club_name,
        'status'          => $club->status,
        'status_class'    => $status_classes[ $club->status ] ?? $status_classes['active'],
        'is_reciprocating' => $club->is_reciprocating,
        'created_at'      => $creation_date,
        'updated_at'      => $update_date,
        'full_status'     => ucfirst( $club->status ),
        'full_recip'      => ucwords( strtolower( $club->is_reciprocating ) ),
    );
}
?>

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
    x-data="clubsTable()" x-init="init()" @search-clubs-updated.window="searchTerm = $event.detail; performSearch()"
    @clear-clubs-search.window="clearSearch()">

    <!-- Per Page Controls -->
    <div
        class="flex flex-col gap-2 px-4 py-4 mb-4 border-b border-gray-200 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800">
        <div class="flex items-center gap-3">
            <span class="text-gray-500 dark:text-gray-400">Show</span>
            <div class="relative z-20 bg-transparent">
                <select x-model="perPage" @change="updatePerPage()"
                    class="w-full py-2 pl-3 pr-8 text-sm text-gray-800 bg-transparent border border-gray-300 rounded-lg appearance-none shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-9 bg-none placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="absolute z-30 text-gray-500 -translate-y-1/2 top-1/2 right-2 dark:text-gray-400">
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

    <div x-show="filteredClubs.length > 0" class="max-w-full overflow-x-auto" id="clubs-table">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">#</p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Club Name</p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status</p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Reciprocating</p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Creation Date</p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Update Date</p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Actions</p>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody id="clubs-table-body" class="divide-y divide-gray-100 dark:divide-gray-800">
                <template x-for="(club, index) in paginatedClubs" :key="club.id">
                    <tr :data-club-id="club.id">
                        <td class="px-3 py-4 sm:px-6">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="getRowNumber(index)"></p>
                        </td>
                        <td class="px-3 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-800 text-theme-sm dark:text-white/90" x-text="club.club_name"></p>
                            </div>
                        </td>
                        <td class="px-3 py-4 sm:px-6">
                            <div class="flex items-center">
                                <span
                                    class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium capitalize"
                                    :class="club.status_class" x-text="club.full_status"></span>
                            </div>
                        </td>
                        <td class="px-3 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-800 text-theme-sm dark:text-gray-400" x-text="club.full_recip"></p>
                            </div>
                        </td>
                        <td class="px-3 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="club.created_at"></p>
                            </div>
                        </td>
                        <td class="px-3 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="club.updated_at"></p>
                            </div>
                        </td>
                        <td class="px-3 py-4 sm:px-6">
                            <div class="flex items-center gap-2">
                                <button :id="'edit-club-button-' + club.id"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer edit-club-btn whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700"
                                    :data-club-id="club.id">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit
                                </button>
                                <button :id="'delete-club-button-' + club.id"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-red-500 border border-red-500 rounded-lg cursor-pointer delete-club-btn whitespace-nowrap hover:bg-red-600 dark:hover:bg-red-600"
                                    :data-club-id="club.id">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Empty state message -->
    <div x-show="filteredClubs.length === 0" class="p-8 text-center">
        <template x-if="searchTerm">
            <div class="py-8">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No clubs found</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    No clubs match your search for "<span x-text="searchTerm" class="font-medium"></span>".
                </p>
                <button @click="clearSearch()"
                    class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-white bg-brand-500 rounded-lg hover:bg-brand-600">
                    Clear Search
                </button>
            </div>
        </template>

        <template x-if="!searchTerm && allClubs.length === 0">
            <div class="py-8">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No clubs yet</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Get started by adding a new club.
                </p>
            </div>
        </template>
    </div>

    <!-- Pagination - Only show if there are results -->
    <div x-show="filteredClubs.length > 0 && totalPages > 1"
        class="flex items-center justify-between gap-8 px-6 py-4 border-t border-gray-200 sm:justify-normal dark:border-gray-800">

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
function clubsTable() {
    return {
        allClubs: <?php echo !empty($clubs_data) ? json_encode($clubs_data) : '[]'; ?>,
        perPage: 20,
        currentPage: 1,
        searchTerm: '',

        init() {
            const savedPerPage = localStorage.getItem('clubs_per_page');
            const savedPage = localStorage.getItem('clubs_current_page');

            if (savedPerPage) {
                this.perPage = parseInt(savedPerPage);
            }

            if (savedPage) {
                this.currentPage = parseInt(savedPage);
            }

            const urlParams = new URLSearchParams(window.location.search);
            const searchParam = urlParams.get('club_search');
            if (searchParam) {
                this.searchTerm = searchParam;
                const searchInput = document.getElementById('search-clubs-input');
                if (searchInput) {
                    searchInput.value = searchParam;
                }
            }

            if (!Array.isArray(this.allClubs)) {
                this.allClubs = [];
            }
        },

        get filteredClubs() {
            if (!Array.isArray(this.allClubs)) {
                return [];
            }
            if (!this.searchTerm) {
                return this.allClubs;
            }

            const searchTerm = this.searchTerm.toLowerCase();
            return this.allClubs.filter(club => {
                if (!club) return false;

                return (
                    (club.club_name && club.club_name.toLowerCase().includes(searchTerm)) ||
                    (club.full_status && club.full_status.toLowerCase().includes(searchTerm)) ||
                    (club.full_recip && club.full_recip.toLowerCase().includes(searchTerm)) ||
                    (club.created_at && club.created_at.toLowerCase().includes(searchTerm)) ||
                    (club.updated_at && club.updated_at.toLowerCase().includes(searchTerm))
                );
            });
        },

        get totalPages() {
            return Math.ceil(this.filteredClubs.length / this.perPage);
        },

        get paginatedClubs() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            return this.filteredClubs.slice(start, end);
        },

        get pageRange() {
            if (this.totalPages <= 0) return {
                pages: [],
                showFirst: false,
                showFirstEllipsis: false,
                showLast: false,
                showLastEllipsis: false
            };

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
            localStorage.setItem('clubs_per_page', this.perPage);
            this.currentPage = 1;
            localStorage.setItem('clubs_current_page', this.currentPage);
        },

        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                localStorage.setItem('clubs_current_page', this.currentPage);
            }
        },

        getRowNumber(index) {
            return ((this.currentPage - 1) * this.perPage) + index + 1;
        },

        getEntriesText() {
            const total = this.filteredClubs.length;
            if (total === 0) {
                return 'No entries found';
            }

            const start = total > 0 ? ((this.currentPage - 1) * this.perPage) + 1 : 0;
            const end = Math.min(this.currentPage * this.perPage, total);

            if (this.searchTerm) {
                return `Showing ${start} to ${end} of ${total} filtered entries`;
            }
            return `Showing ${start} to ${end} of ${total} entries`;
        },

        performSearch() {
            this.currentPage = 1;
            localStorage.setItem('clubs_current_page', this.currentPage);

            const url = new URL(window.location);
            if (this.searchTerm) {
                url.searchParams.set('club_search', this.searchTerm);
                url.searchParams.set('search_clubs', 'true');
            } else {
                url.searchParams.delete('club_search');
                url.searchParams.delete('search_clubs');
            }
            window.history.pushState({}, '', url);
        },

        clearSearch() {
            this.searchTerm = '';
            this.currentPage = 1;
            localStorage.setItem('clubs_current_page', this.currentPage);

            const url = new URL(window.location);
            url.searchParams.delete('club_search');
            url.searchParams.delete('search_clubs');
            window.history.pushState({}, '', url);

            // Clear the search input field if it exists
            const searchInput = document.getElementById('search-clubs-input');
            if (searchInput) {
                searchInput.value = '';
            }
        }
    }
}

// Keyboard shortcuts
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('keydown', function(e) {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.getElementById('search-clubs-input');
            if (searchInput) {
                searchInput.focus();
            }
        }
        if (e.key === 'Escape') {
            const searchInput = document.getElementById('search-clubs-input');
            if (searchInput && searchInput.value) {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
            }
        }
    });
});
</script>