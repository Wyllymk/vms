<?php
/**
 * The template for displaying the reports page
 *
 * @package Visitor_Management_System
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Check if the current user has appropriate permissions
if ( ! ( current_user_can( 'administrator' ) || current_user_can( 'reception' ) || current_user_can( 'general_manager' ) || current_user_can( 'chairman' ) ) ) {
	wp_redirect( home_url() );
	exit;
}

get_header();

// Get today's date and calculate default date ranges
$today     = current_time( 'Y-m-d' );
$week_ago  = date( 'Y-m-d', strtotime( '-7 days' ) );
$month_ago = date( 'Y-m-d', strtotime( '-30 days' ) );
$year_ago  = date( 'Y-m-d', strtotime( '-1 year' ) );

?>

<section x-data="{ page: 'reports'}">
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
					<div x-data="{ pageName: `Reports & Analytics` }">
						<?php get_template_part( 'template-parts/content/content', 'breadcrumb' ); ?>
					</div>

					<!-- Alert Container -->
					<div id="alert-container" class="mb-4"></div>

					<!-- Main content -->
					<div class="content-page">
						<div class="text-gray-800 content-page dark:text-gray-100">
							<div class="py-6 mx-auto">
								<!-- Date Range Filter Section -->
								<div
									class="rounded-2xl shadow-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] mb-6">
									<div
										class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
										<h4 class="text-lg font-semibold flex items-center">
											<svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor"
												viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
													d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
												</path>
											</svg>
											<?php esc_html_e( 'Report Filters', 'vms' ); ?>
										</h4>
									</div>
									<div class="px-6 py-4">
										<form id="report-filter-form" class="space-y-4">
											<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
												<!-- Quick Filter Buttons -->
												<div class="md:col-span-4">
													<label
														class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
														<?php esc_html_e( 'Quick Filters', 'vms' ); ?>
													</label>
													<div class="flex flex-wrap gap-2">
														<button type="button" data-range="today"
															class="quick-filter-btn px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
															<?php esc_html_e( 'Today', 'vms' ); ?>
														</button>
														<button type="button" data-range="week"
															class="quick-filter-btn px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
															<?php esc_html_e( 'Last 7 Days', 'vms' ); ?>
														</button>
														<button type="button" data-range="month"
															class="quick-filter-btn px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
															<?php esc_html_e( 'Last 30 Days', 'vms' ); ?>
														</button>
														<button type="button" data-range="year"
															class="quick-filter-btn px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
															<?php esc_html_e( 'Last Year', 'vms' ); ?>
														</button>
													</div>
												</div>

												<!-- From Date -->
												<div>
													<label for="from_date"
														class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
														<?php esc_html_e( 'From Date', 'vms' ); ?>
													</label>
													<input type="date" id="from_date" name="from_date"
														value="<?php echo esc_attr( $week_ago ); ?>"
														class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800">
												</div>

												<!-- To Date -->
												<div>
													<label for="to_date"
														class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
														<?php esc_html_e( 'To Date', 'vms' ); ?>
													</label>
													<input type="date" id="to_date" name="to_date"
														value="<?php echo esc_attr( $today ); ?>"
														class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800">
												</div>

												<!-- Apply Button -->
												<div class="flex items-end">
													<button type="submit" id="apply-filter"
														class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
														<svg class="w-4 h-4" fill="none" stroke="currentColor"
															viewBox="0 0 24 24">
															<path stroke-linecap="round" stroke-linejoin="round"
																stroke-width="2"
																d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
															</path>
														</svg>
														<?php esc_html_e( 'Apply Filter', 'vms' ); ?>
													</button>
												</div>

												<!-- Export Button -->
												<div class="flex items-end">
													<button type="button" id="export-pdf"
														class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white transition rounded-lg bg-red-500 shadow-theme-xs hover:bg-red-600">
														<svg class="w-4 h-4" fill="none" stroke="currentColor"
															viewBox="0 0 24 24">
															<path stroke-linecap="round" stroke-linejoin="round"
																stroke-width="2"
																d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
															</path>
														</svg>
														<?php esc_html_e( 'Export PDF', 'vms' ); ?>
													</button>
												</div>
											</div>
										</form>
									</div>
								</div>

								<!-- Statistics Overview Cards -->
								<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6"
									id="stats-overview">
									<!-- Total Guests Card -->
									<div
										class="rounded-2xl shadow-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
										<div class="flex items-center justify-between mb-4">
											<div
												class="flex items-center justify-center w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/20">
												<svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor"
													viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round"
														stroke-width="2"
														d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
													</path>
												</svg>
											</div>
										</div>
										<h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1"
											id="total-guests-count">0</h3>
										<p class="text-sm text-gray-600 dark:text-gray-400">
											<?php esc_html_e( 'Total Guests', 'vms' ); ?></p>
										<div class="mt-2 text-xs text-gray-500 dark:text-gray-500">
											<span id="total-guests-visited">0</span>
											<?php esc_html_e( 'visited', 'vms' ); ?>
										</div>
									</div>

									<!-- Accommodation Guests Card -->
									<div
										class="rounded-2xl shadow-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
										<div class="flex items-center justify-between mb-4">
											<div
												class="flex items-center justify-center w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/20">
												<svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor"
													viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round"
														stroke-width="2"
														d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
													</path>
												</svg>
											</div>
										</div>
										<h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1"
											id="total-accommodation-count">0</h3>
										<p class="text-sm text-gray-600 dark:text-gray-400">
											<?php esc_html_e( 'Accommodation Guests', 'vms' ); ?></p>
										<div class="mt-2 text-xs text-gray-500 dark:text-gray-500">
											<span id="total-accommodation-visited">0</span>
											<?php esc_html_e( 'visited', 'vms' ); ?>
										</div>
									</div>

									<!-- Suppliers Card -->
									<div
										class="rounded-2xl shadow-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
										<div class="flex items-center justify-between mb-4">
											<div
												class="flex items-center justify-center w-12 h-12 rounded-lg bg-purple-100 dark:bg-purple-900/20">
												<svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor"
													viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round"
														stroke-width="2"
														d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
													</path>
												</svg>
											</div>
										</div>
										<h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1"
											id="total-suppliers-count">0</h3>
										<p class="text-sm text-gray-600 dark:text-gray-400">
											<?php esc_html_e( 'Suppliers', 'vms' ); ?></p>
										<div class="mt-2 text-xs text-gray-500 dark:text-gray-500">
											<span id="total-suppliers-visited">0</span>
											<?php esc_html_e( 'visited', 'vms' ); ?>
										</div>
									</div>

									<!-- Reciprocating Members Card -->
									<div
										class="rounded-2xl shadow-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
										<div class="flex items-center justify-between mb-4">
											<div
												class="flex items-center justify-center w-12 h-12 rounded-lg bg-orange-100 dark:bg-orange-900/20">
												<svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor"
													viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round"
														stroke-width="2"
														d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
													</path>
												</svg>
											</div>
										</div>
										<h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1"
											id="total-reciprocating-count">0</h3>
										<p class="text-sm text-gray-600 dark:text-gray-400">
											<?php esc_html_e( 'Reciprocating Members', 'vms' ); ?></p>
										<div class="mt-2 text-xs text-gray-500 dark:text-gray-500">
											<span id="total-reciprocating-visited">0</span>
											<?php esc_html_e( 'visited', 'vms' ); ?>
										</div>
									</div>
								</div>

								<!-- Charts Section -->
								<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
									<!-- Visitor Trends Chart -->
									<div
										class="rounded-2xl shadow-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
										<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
											<h4 class="text-lg font-semibold flex items-center">
												<svg class="w-5 h-5 mr-2 text-blue-500" fill="none"
													stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round"
														stroke-width="2"
														d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
													</path>
												</svg>
												<?php esc_html_e( 'Visitor Trends', 'vms' ); ?>
											</h4>
										</div>
										<div class="p-6">
											<canvas id="visitor-trends-chart" height="250"></canvas>
										</div>
									</div>

									<!-- Visitor Distribution Chart -->
									<div
										class="rounded-2xl shadow-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
										<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
											<h4 class="text-lg font-semibold flex items-center">
												<svg class="w-5 h-5 mr-2 text-green-500" fill="none"
													stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round"
														stroke-width="2"
														d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
													<path stroke-linecap="round" stroke-linejoin="round"
														stroke-width="2"
														d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
												</svg>
												<?php esc_html_e( 'Visitor Distribution', 'vms' ); ?>
											</h4>
										</div>
										<div class="p-6">
											<canvas id="visitor-distribution-chart" height="250"></canvas>
										</div>
									</div>
								</div>

								<!-- Detailed Reports Section -->
								<div class="grid grid-cols-1 gap-6">
									<!-- Guests Report -->
									<div
										class="rounded-2xl shadow-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
										<div
											class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
											<h4 class="text-lg font-semibold">
												<?php esc_html_e( 'Guests Report', 'vms' ); ?></h4>
											<button type="button"
												class="export-section-btn text-sm text-brand-500 hover:text-brand-600"
												data-section="guests">
												<?php esc_html_e( 'Export PDF', 'vms' ); ?>
											</button>
										</div>
										<div class="px-6 py-4">
											<div class="overflow-x-auto">
												<table class="w-full" id="guests-table">
													<thead class="bg-gray-50 dark:bg-gray-800/50">
														<tr>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Name', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Phone', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Visit Date', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Sign In', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Sign Out', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Status', 'vms' ); ?></th>
														</tr>
													</thead>
													<tbody class="divide-y divide-gray-200 dark:divide-gray-700"
														id="guests-tbody">
														<tr>
															<td colspan="6"
																class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
																<?php esc_html_e( 'Loading data...', 'vms' ); ?>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>

									<!-- Accommodation Guests Report -->
									<div
										class="rounded-2xl shadow-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
										<div
											class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
											<h4 class="text-lg font-semibold">
												<?php esc_html_e( 'Accommodation Guests Report', 'vms' ); ?></h4>
											<button type="button"
												class="export-section-btn text-sm text-brand-500 hover:text-brand-600"
												data-section="accommodation">
												<?php esc_html_e( 'Export PDF', 'vms' ); ?>
											</button>
										</div>
										<div class="px-6 py-4">
											<div class="overflow-x-auto">
												<table class="w-full" id="accommodation-table">
													<thead class="bg-gray-50 dark:bg-gray-800/50">
														<tr>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Name', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Phone', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Visit Date', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Sign In', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Sign Out', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Status', 'vms' ); ?></th>
														</tr>
													</thead>
													<tbody class="divide-y divide-gray-200 dark:divide-gray-700"
														id="accommodation-tbody">
														<tr>
															<td colspan="6"
																class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
																<?php esc_html_e( 'Loading data...', 'vms' ); ?>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>

									<!-- Suppliers Report -->
									<div
										class="rounded-2xl shadow-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
										<div
											class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
											<h4 class="text-lg font-semibold">
												<?php esc_html_e( 'Suppliers Report', 'vms' ); ?></h4>
											<button type="button"
												class="export-section-btn text-sm text-brand-500 hover:text-brand-600"
												data-section="suppliers">
												<?php esc_html_e( 'Export PDF', 'vms' ); ?>
											</button>
										</div>
										<div class="px-6 py-4">
											<div class="overflow-x-auto">
												<table class="w-full" id="suppliers-table">
													<thead class="bg-gray-50 dark:bg-gray-800/50">
														<tr>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Name', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Phone', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Visit Date', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Sign In', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Sign Out', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Status', 'vms' ); ?></th>
														</tr>
													</thead>
													<tbody class="divide-y divide-gray-200 dark:divide-gray-700"
														id="suppliers-tbody">
														<tr>
															<td colspan="6"
																class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
																<?php esc_html_e( 'Loading data...', 'vms' ); ?>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>

									<!-- Reciprocating Members Report -->
									<div
										class="rounded-2xl shadow-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
										<div
											class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
											<h4 class="text-lg font-semibold">
												<?php esc_html_e( 'Reciprocating Members Report', 'vms' ); ?></h4>
											<button type="button"
												class="export-section-btn text-sm text-brand-500 hover:text-brand-600"
												data-section="reciprocating">
												<?php esc_html_e( 'Export PDF', 'vms' ); ?>
											</button>
										</div>
										<div class="px-6 py-4">
											<div class="overflow-x-auto">
												<table class="w-full" id="reciprocating-table">
													<thead class="bg-gray-50 dark:bg-gray-800/50">
														<tr>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Name', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Club', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Phone', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Visit Date', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Sign In', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Sign Out', 'vms' ); ?></th>
															<th
																class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
																<?php esc_html_e( 'Status', 'vms' ); ?></th>
														</tr>
													</thead>
													<tbody class="divide-y divide-gray-200 dark:divide-gray-700"
														id="reciprocating-tbody">
														<tr>
															<td colspan="7"
																class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
																<?php esc_html_e( 'Loading data...', 'vms' ); ?>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
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
</section>

<?php
get_footer();