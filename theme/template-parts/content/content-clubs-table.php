<?php
/**
 * Template part for displaying clubs table with pagination
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visitor_Management_System
 */
defined( 'ABSPATH' ) || exit;

global $wpdb;
$clubs_table = \WyllyMk\VMS\VMS_Config::get_table_name( \WyllyMk\VMS\VMS_Config::RECIP_CLUBS_TABLE );

// Pagination
$clubs_per_page = isset( $_GET['per_page'] ) ? max( 1, intval( $_GET['per_page'] ) ) : 25;
$current_page   = isset( $_GET['paged'] ) ? max( 1, intval( $_GET['paged'] ) ) : 1;
$offset         = ( $current_page - 1 ) * $clubs_per_page;

// Search functionality
$search_term  = '';
$where_clause = '';

if ( isset( $_GET['search_clubs'] ) && ! empty( $_GET['club_search'] ) ) {
	$search_term = sanitize_text_field( $_GET['club_search'] );
	$like        = '%' . $wpdb->esc_like( $search_term ) . '%';

	$where_clause = $wpdb->prepare(
		' WHERE club_name LIKE %s',
		$like
	);
}

// Count total clubs
$count_query = "SELECT COUNT(*) FROM {$clubs_table}" . $where_clause;
$total_clubs = $wpdb->get_var( $count_query );
$total_pages = ceil( $total_clubs / $clubs_per_page );

// Fetch clubs
$query = "SELECT * FROM {$clubs_table}" . $where_clause . " ORDER BY created_at DESC LIMIT {$clubs_per_page} OFFSET {$offset}";
$clubs = $wpdb->get_results( $query );

$status_classes = array(
	'active'    => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
	'suspended' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
	'banned'    => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
);
?>

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
    x-data="{ perPage: localStorage.getItem('clubs_per_page') || '25' }"
    x-init="$watch('perPage', value => localStorage.setItem('clubs_per_page', value))">

    <!-- Per Page Controls -->
    <div
        class="flex flex-col gap-2 px-4 py-4 mb-4 border-b border-gray-200 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800">
        <div class="flex items-center gap-3">
            <span class="text-gray-500 dark:text-gray-400">Show</span>
            <div class="relative z-20 bg-transparent">
                <select x-model="perPage"
                    @change="window.location.href = updateUrlParameter(window.location.href, 'per_page', $event.target.value)"
                    class="w-full py-2 pl-3 pr-8 text-sm text-gray-800 bg-transparent border border-gray-300 rounded-lg appearance-none shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-9 bg-none placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    <option value="25" <?php selected( $clubs_per_page, 25 ); ?>>25</option>
                    <option value="50" <?php selected( $clubs_per_page, 50 ); ?>>50</option>
                    <option value="100" <?php selected( $clubs_per_page, 100 ); ?>>100</option>
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
        <!-- Show total entries info -->
        <div class="text-sm text-gray-500 dark:text-gray-400">
            <?php
			$start = $total_clubs > 0 ? $offset + 1 : 0;
			$end   = min( $offset + $clubs_per_page, $total_clubs );

			if ( ! empty( $search_term ) ) {
				printf(
					esc_html__( 'Showing %1$d to %2$d of %3$d entries (filtered)', 'vms' ),
					$start,
					$end,
					$total_clubs
				);
			} else {
				printf(
					esc_html__( 'Showing %1$d to %2$d of %3$d entries', 'vms' ),
					$start,
					$end,
					$total_clubs
				);
			}
			?>
        </div>
    </div>

    <div class="max-w-full overflow-x-auto" id="clubs-table">
        <table class="min-w-full">
            <!-- table header start -->
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( '#', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'Club Name', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'Status', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'Reciprocating', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'Creation Date', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'Update Date', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-3 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'Actions', 'vms' ); ?>
                            </p>
                        </div>
                    </th>
                </tr>
            </thead>
            <!-- table header end -->
            <!-- table body start -->
            <tbody id="clubs-table-body" class="divide-y divide-gray-100 dark:divide-gray-800">
                <?php
				$counter = $offset + 1;
				if ( ! empty( $clubs ) ) :
					foreach ( $clubs as $club ) :
						$creation_date = ! empty( $club->created_at ) ? date( 'M j, Y', strtotime( $club->created_at ) ) : 'N/A';
						$update_date   = ! empty( $club->updated_at ) ? date( 'M j, Y', strtotime( $club->updated_at ) ) : 'N/A';
						?>
                <tr data-club-id="<?php echo esc_attr( $club->id ); ?>">
                    <td class="px-3 py-4 sm:px-6">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                            <?php echo $counter++; ?>
                        </p>
                    </td>
                    <td class="px-3 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-800 text-theme-sm dark:text-white/90">
                                <?php echo esc_html( $club->club_name ); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-3 py-4 sm:px-6">
                        <div class="flex items-center">
                            <span
                                class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium capitalize <?php echo $status_classes[ $club->status ] ?? $status_classes['active']; ?>">
                                <?php echo esc_html( ucfirst( $club->status ) ); ?>
                            </span>
                        </div>
                    </td>
                    <td class="px-3 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-800 text-theme-sm dark:text-gray-400">
                                <?php echo esc_html( ucwords( strtolower( $club->is_reciprocating ) ) ); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-3 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php echo esc_html( $creation_date ); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-3 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php echo esc_html( $update_date ); ?>
                            </p>
                        </div>
                    </td>
                    <!-- Updated action buttons section in the clubs table template -->
                    <td class="px-3 py-4 sm:px-6">
                        <div class="flex items-center gap-2">
                            <button
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer edit-club-btn whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700"
                                data-club-id="<?php echo $club->id; ?>">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                <?php esc_html_e( 'Edit', 'vms' ); ?>
                            </button>
                            <button
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-red-500 border border-red-500 rounded-lg cursor-pointer delete-club-btn whitespace-nowrap hover:bg-red-600 dark:hover:bg-red-600"
                                data-club-id="<?php echo $club->id; ?>">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                <?php esc_html_e( 'Delete', 'vms' ); ?>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php
					endforeach;
				else :
					echo '<tr id="no-clubs-row"><td colspan="6" class="px-4 py-4 text-center text-gray-600 dark:text-white">No clubs found.</td></tr>';
				endif;
				?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Section -->
    <?php if ( $total_pages > 1 ) : ?>
    <div
        class="flex items-center justify-between gap-8 px-6 py-4 border-t border-gray-200 sm:justify-normal dark:border-gray-800">
        <!-- Previous Button -->
        <?php if ( $current_page > 1 ) : ?>
        <a href="<?php echo esc_url( add_query_arg( 'paged', $current_page - 1 ) ); ?>"
            class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5 sm:py-2.5">
            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M2.58203 9.99868C2.58174 10.1909 2.6549 10.3833 2.80152 10.53L7.79818 15.5301C8.09097 15.8231 8.56584 15.8233 8.85883 15.5305C9.15183 15.2377 9.152 14.7629 8.85921 14.4699L5.13911 10.7472L16.6665 10.7472C17.0807 10.7472 17.4165 10.4114 17.4165 9.99715C17.4165 9.58294 17.0807 9.24715 16.6665 9.24715L5.14456 9.24715L8.85919 5.53016C9.15199 5.23717 9.15184 4.7623 8.85885 4.4695C8.56587 4.1767 8.09099 4.17685 7.79819 4.46984L2.84069 9.43049C2.68224 9.568 2.58203 9.77087 2.58203 9.99715C2.58203 9.99766 2.58203 9.99817 2.58203 9.99868Z">
                </path>
            </svg>
            <span class="hidden sm:inline"><?php esc_html_e( 'Previous', 'vms' ); ?></span>
        </a>
        <?php else : ?>
        <button disabled
            class="flex items-center gap-2 rounded-lg border border-gray-300 bg-gray-100 px-2 py-2 text-sm font-medium text-gray-400 shadow-theme-xs dark:border-gray-600 dark:bg-gray-700 dark:text-gray-500 sm:px-3.5 sm:py-2.5">
            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M2.58203 9.99868C2.58174 10.1909 2.6549 10.3833 2.80152 10.53L7.79818 15.5301C8.09097 15.8231 8.56584 15.8233 8.85883 15.5305C9.15183 15.2377 9.152 14.7629 8.85921 14.4699L5.13911 10.7472L16.6665 10.7472C17.0807 10.7472 17.4165 10.4114 17.4165 9.99715C17.4165 9.58294 17.0807 9.24715 16.6665 9.24715L5.14456 9.24715L8.85919 5.53016C9.15199 5.23717 9.15184 4.7623 8.85885 4.4695C8.56587 4.1767 8.09099 4.17685 7.79819 4.46984L2.84069 9.43049C2.68224 9.568 2.58203 9.77087 2.58203 9.99715C2.58203 9.99766 2.58203 9.99817 2.58203 9.99868Z">
                </path>
            </svg>
            <span class="hidden sm:inline"><?php esc_html_e( 'Previous', 'vms' ); ?></span>
        </button>
        <?php endif; ?>

        <!-- Mobile page indicator -->
        <span class="block text-sm font-medium text-gray-700 dark:text-gray-400 sm:hidden">
            <?php
			printf( esc_html__( 'Page %1$d of %2$d', 'vms' ), $current_page, $total_pages );
			?>
        </span>

        <!-- Desktop page numbers -->
        <ul class="hidden items-center gap-0.5 sm:flex">
            <?php
			// Calculate page range to display
			$start_page = max( 1, $current_page - 2 );
			$end_page   = min( $total_pages, $current_page + 2 );

			// Show first page if not in range
			if ( $start_page > 1 ) {
				echo '<li><a href="' . esc_url( add_query_arg( 'paged', 1 ) ) . '" class="flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 rounded-lg hover:bg-brand-500 hover:text-white dark:text-gray-400 dark:hover:text-white">1</a></li>';
				if ( $start_page > 2 ) {
					echo '<li><span class="flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 rounded-lg dark:text-gray-400">...</span></li>';
				}
			}

			// Display page numbers in range
			for ( $i = $start_page; $i <= $end_page; $i++ ) {
				if ( $i == $current_page ) {
					echo '<li><span class="flex items-center justify-center w-10 h-10 text-sm font-medium text-white rounded-lg bg-brand-500">' . $i . '</span></li>';
				} else {
					echo '<li><a href="' . esc_url( add_query_arg( 'paged', $i ) ) . '" class="flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 rounded-lg hover:bg-brand-500 hover:text-white dark:text-gray-400 dark:hover:text-white">' . $i . '</a></li>';
				}
			}

			// Show last page if not in range
			if ( $end_page < $total_pages ) {
				if ( $end_page < $total_pages - 1 ) {
					echo '<li><span class="flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 rounded-lg dark:text-gray-400">...</span></li>';
				}
				echo '<li><a href="' . esc_url( add_query_arg( 'paged', $total_pages ) ) . '" class="flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 rounded-lg hover:bg-brand-500 hover:text-white dark:text-gray-400 dark:hover:text-white">' . $total_pages . '</a></li>';
			}
			?>
        </ul>

        <!-- Next Button -->
        <?php if ( $current_page < $total_pages ) : ?>
        <a href="<?php echo esc_url( add_query_arg( 'paged', $current_page + 1 ) ); ?>"
            class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5 sm:py-2.5">
            <span class="hidden sm:inline"><?php esc_html_e( 'Next', 'vms' ); ?></span>
            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M17.4165 9.9986C17.4168 10.1909 17.3437 10.3832 17.197 10.53L12.2004 15.5301C11.9076 15.8231 11.4327 15.8233 11.1397 15.5305C10.8467 15.2377 10.8465 14.7629 11.1393 14.4699L14.8594 10.7472L3.33203 10.7472C2.91782 10.7472 2.58203 10.4114 2.58203 9.99715C2.58203 9.58294 2.91782 9.24715 3.33203 9.24715L14.854 9.24715L11.1393 5.53016C10.8465 5.23717 10.8467 4.7623 11.1397 4.4695C11.4327 4.1767 11.9075 4.17685 12.2003 4.46984L17.1578 9.43049C17.3163 9.568 17.4165 9.77087 17.4165 9.99715C17.4165 9.99763 17.4165 9.99812 17.4165 9.9986Z">
                </path>
            </svg>
        </a>
        <?php else : ?>
        <button disabled
            class="flex items-center gap-2 rounded-lg border border-gray-300 bg-gray-100 px-2 py-2 text-sm font-medium text-gray-400 shadow-theme-xs dark:border-gray-600 dark:bg-gray-700 dark:text-gray-500 sm:px-3.5 sm:py-2.5">
            <span class="hidden sm:inline"><?php esc_html_e( 'Next', 'vms' ); ?></span>
            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M17.4165 9.9986C17.4168 10.1909 17.3437 10.3832 17.197 10.53L12.2004 15.5301C11.9076 15.8231 11.4327 15.8233 11.1397 15.5305C10.8467 15.2377 10.8465 14.7629 11.1393 14.4699L14.8594 10.7472L3.33203 10.7472C2.91782 10.7472 2.58203 10.4114 2.58203 9.99715C2.58203 9.58294 2.91782 9.24715 3.33203 9.24715L14.854 9.24715L11.1393 5.53016C10.8465 5.23717 10.8467 4.7623 11.1397 4.4695C11.4327 4.1767 11.9075 4.17685 12.2003 4.46984L17.1578 9.43049C17.3163 9.568 17.4165 9.77087 17.4165 9.99715C17.4165 9.99763 17.4165 9.99812 17.4165 9.9986Z">
                </path>
            </svg>
        </button>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>