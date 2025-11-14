<?php
/**
 * Template part for displaying device sessions chart
 * Tracks visitor device types and stores in WordPress options
 *
 * @package Visitor_Management_System
 */

defined( 'ABSPATH' ) || exit;

// Track current visitor's device
function vms_track_visitor_device() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		error_log( '[VMS_DEVICE] Skipping device tracking (admin, AJAX, or CRON context).' );
		return;
	}

	// Check if already tracked this month
	$month_key   = date( 'Y-m' );
	$cookie_name = 'vms_device_tracked_' . $month_key;

	if ( isset( $_COOKIE[ $cookie_name ] ) ) {
		error_log( "[VMS_DEVICE] Device already tracked for this month ({$month_key}). Skipping." );
		return;
	}

	// Detect device type
	$user_agent  = $_SERVER['HTTP_USER_AGENT'] ?? '';
	$device_type = 'desktop';

	if ( preg_match( '/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $user_agent ) ) {
		$device_type = 'tablet';
	} elseif ( preg_match( '/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $user_agent ) ) {
		$device_type = 'mobile';
	}

	error_log( "[VMS_DEVICE] Detected device type: {$device_type}" );
	error_log( "[VMS_DEVICE] User Agent: {$user_agent}" );

	try {
		// Get existing data
		$device_data = get_option( 'vms_device_sessions', array() );
		error_log( '[VMS_DEVICE] Retrieved existing device data: ' . print_r( $device_data, true ) );

		// Initialize if needed
		if ( ! isset( $device_data[ $month_key ] ) ) {
			$device_data[ $month_key ] = array(
				'desktop' => 0,
				'mobile'  => 0,
				'tablet'  => 0,
			);
			error_log( "[VMS_DEVICE] Initialized new month data for {$month_key}." );
		}

		// Increment device count
		++$device_data[ $month_key ][ $device_type ];
		error_log( "[VMS_DEVICE] Incremented {$device_type} count for {$month_key}. Current data: " . print_r( $device_data[ $month_key ], true ) );

		// Save data
		$update_result = update_option( 'vms_device_sessions', $device_data );
		error_log( '[VMS_DEVICE] Updated option result: ' . var_export( $update_result, true ) );

		// Set cookie for entire month (expires at end of month)
		$expire_time = strtotime( 'last day of this month 23:59:59' );
		setcookie( $cookie_name, '1', $expire_time, '/' );
		error_log( "[VMS_DEVICE] Cookie '{$cookie_name}' set to expire on " . date( 'Y-m-d H:i:s', $expire_time ) );

	} catch ( Throwable $e ) {
		error_log( '[VMS_DEVICE] Exception during device tracking: ' . $e->getMessage() );
		error_log( '[VMS_DEVICE] Stack trace: ' . $e->getTraceAsString() );
	}

	error_log( '[VMS_DEVICE] Device tracking completed.' );
}

// Hook to track visitors
add_action( 'wp_login', 'vms_track_visitor_device' );


// ================== DEVICE REPORT ===================

$month_key   = date( 'Y-m' );
$device_data = get_option( 'vms_device_sessions', array() );
error_log( '[VMS_DEVICE_REPORT] Loaded device data: ' . print_r( $device_data, true ) );

// Get current month data or use defaults
if ( isset( $device_data[ $month_key ] ) ) {
	$desktop_visits = (int) $device_data[ $month_key ]['desktop'];
	$mobile_visits  = (int) $device_data[ $month_key ]['mobile'];
	$tablet_visits  = (int) $device_data[ $month_key ]['tablet'];
	error_log( "[VMS_DEVICE_REPORT] Found data for {$month_key}: Desktop={$desktop_visits}, Mobile={$mobile_visits}, Tablet={$tablet_visits}" );
} else {
	$desktop_visits = 45;
	$mobile_visits  = 65;
	$tablet_visits  = 25;
	error_log( "[VMS_DEVICE_REPORT] No data for {$month_key}. Using default values." );
}

$total_visits = $desktop_visits + $mobile_visits + $tablet_visits;
error_log( "[VMS_DEVICE_REPORT] Total visits this month: {$total_visits}" );

$desktop_percent = $total_visits > 0 ? round( ( $desktop_visits / $total_visits ) * 100 ) : 33;
$mobile_percent  = $total_visits > 0 ? round( ( $mobile_visits / $total_visits ) * 100 ) : 48;
$tablet_percent  = $total_visits > 0 ? round( ( $tablet_visits / $total_visits ) * 100 ) : 19;

error_log( "[VMS_DEVICE_REPORT] Percentages — Desktop: {$desktop_percent}%, Mobile: {$mobile_percent}%, Tablet: {$tablet_percent}%" );

?>

<div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
	<div class="flex items-center justify-between mb-8">
		<h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
			<?php esc_html_e( 'Sessions By Device', 'vms' ); ?>
		</h3>
		<div x-data="{openDropDown: false}" class="relative h-fit">
			<button @click="openDropDown = !openDropDown"
				:class="openDropDown ? 'text-gray-700 dark:text-white' : 'text-gray-400 hover:text-gray-700 dark:hover:text-white'">
				<svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
					xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd"
						d="M10.2441 6C10.2441 5.0335 11.0276 4.25 11.9941 4.25H12.0041C12.9706 4.25 13.7541 5.0335 13.7541 6C13.7541 6.9665 12.9706 7.75 12.0041 7.75H11.9941C11.0276 7.75 10.2441 6.9665 10.2441 6ZM10.2441 18C10.2441 17.0335 11.0276 16.25 11.9941 16.25H12.0041C12.9706 16.25 13.7541 17.0335 13.7541 18C13.7541 18.9665 12.9706 19.75 12.0041 19.75H11.9941C11.0276 19.75 10.2441 18.9665 10.2441 18ZM11.9941 10.25C11.0276 10.25 10.2441 11.0335 10.2441 12C10.2441 12.9665 11.0276 13.75 11.9941 13.75H12.0041C12.9706 13.75 13.7541 12.9665 13.7541 12C13.7541 11.0335 12.9706 10.25 12.0041 10.25H11.9941Z"
						fill="" />
				</svg>
			</button>
			<div x-show="openDropDown" @click.outside="openDropDown = false"
				class="absolute right-0 z-40 w-40 p-2 space-y-1 bg-white border border-gray-200 top-full rounded-2xl shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark">
				<button onclick="window.location.href='<?php echo admin_url( 'admin.php?page=vms-device-stats' ); ?>'"
					class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
					<?php esc_html_e( 'View Device Stats', 'vms' ); ?>
				</button>
				<button onclick="vmsResetDeviceData()"
					class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
					<?php esc_html_e( 'Reset This Month', 'vms' ); ?>
				</button>
				<button onclick="vmsExportDeviceData()"
					class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
					<?php esc_html_e( 'Export Data', 'vms' ); ?>
				</button>
			</div>
		</div>
	</div>
	<div class="">
		<div id="chartSeven" class="flex justify-center mx-auto chartDarkStyle" style="min-height: 326px;"
			data-desktop="<?php echo esc_attr( $desktop_visits ); ?>"
			data-mobile="<?php echo esc_attr( $mobile_visits ); ?>" data-tablet="<?php echo esc_attr( $tablet_visits ); ?>">
		</div>
	</div>
	<!-- <div class="flex items-center justify-center gap-5 mt-6 sm:gap-8">
		<div class="text-center">
			<p class="mb-1 text-gray-500 text-theme-xs dark:text-gray-400 sm:text-sm">
				<?php esc_html_e( 'Desktop', 'vms' ); ?>
			</p>
			<p class="text-base font-semibold text-gray-800 dark:text-white/90 sm:text-lg">
				<?php echo $desktop_percent; ?>%
			</p>
			<p class="text-xs text-gray-400 dark:text-gray-500">
				<?php echo $desktop_visits; ?> <?php esc_html_e( 'visits', 'vms' ); ?>
			</p>
		</div>

		<div class="w-px bg-gray-200 h-12 dark:bg-gray-800"></div>

		<div class="text-center">
			<p class="mb-1 text-gray-500 text-theme-xs dark:text-gray-400 sm:text-sm">
				<?php esc_html_e( 'Mobile', 'vms' ); ?>
			</p>
			<p class="text-base font-semibold text-gray-800 dark:text-white/90 sm:text-lg">
				<?php echo $mobile_percent; ?>%
			</p>
			<p class="text-xs text-gray-400 dark:text-gray-500">
				<?php echo $mobile_visits; ?> <?php esc_html_e( 'visits', 'vms' ); ?>
			</p>
		</div>

		<div class="w-px bg-gray-200 h-12 dark:bg-gray-800"></div>

		<div class="text-center">
			<p class="mb-1 text-gray-500 text-theme-xs dark:text-gray-400 sm:text-sm">
				<?php esc_html_e( 'Tablet', 'vms' ); ?>
			</p>
			<p class="text-base font-semibold text-gray-800 dark:text-white/90 sm:text-lg">
				<?php echo $tablet_percent; ?>%
			</p>
			<p class="text-xs text-gray-400 dark:text-gray-500">
				<?php echo $tablet_visits; ?> <?php esc_html_e( 'visits', 'vms' ); ?>
			</p>
		</div>
	</div> -->
</div>

<script>
// Pass data to global scope for chart initialization
window.vmsDeviceChartData = {
	desktop: <?php echo $desktop_visits; ?>,
	mobile: <?php echo $mobile_visits; ?>,
	tablet: <?php echo $tablet_visits; ?>
};

// Reset device data for current month
function vmsResetDeviceData() {
	if (!confirm('<?php esc_html_e( 'Reset device data for this month? This cannot be undone.', 'vms' ); ?>')) {
		return;
	}

	fetch('<?php echo admin_url( 'admin-ajax.php' ); ?>', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: 'action=vms_reset_device_data&nonce=<?php echo wp_create_nonce( 'vms_device_data' ); ?>'
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				location.reload();
			} else {
				alert('<?php esc_html_e( 'Failed to reset data', 'vms' ); ?>');
			}
		});
}

// Export device data
function vmsExportDeviceData() {
	window.location.href =
		'<?php echo admin_url( 'admin-ajax.php?action=vms_export_device_data&nonce=' . wp_create_nonce( 'vms_device_export' ) ); ?>';
}
</script>

<?php
// AJAX handler for resetting device data
function vms_reset_device_data_ajax() {
	check_ajax_referer( 'vms_device_data', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( 'Unauthorized' );
	}

	$month_key   = date( 'Y-m' );
	$device_data = get_option( 'vms_device_sessions', array() );

	$device_data[ $month_key ] = array(
		'desktop' => 0,
		'mobile'  => 0,
		'tablet'  => 0,
	);

	update_option( 'vms_device_sessions', $device_data );

	wp_send_json_success();
}
add_action( 'wp_ajax_vms_reset_device_data', 'vms_reset_device_data_ajax' );

// AJAX handler for exporting device data
function vms_export_device_data_ajax() {
	check_ajax_referer( 'vms_device_export', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}

	$device_data = get_option( 'vms_device_sessions', array() );

	header( 'Content-Type: text/csv' );
	header( 'Content-Disposition: attachment; filename="device-sessions-' . date( 'Y-m-d' ) . '.csv"' );

	$output = fopen( 'php://output', 'w' );
	fputcsv( $output, array( 'Month', 'Desktop', 'Mobile', 'Tablet', 'Total' ) );

	foreach ( $device_data as $month => $data ) {
		$total = $data['desktop'] + $data['mobile'] + $data['tablet'];
		fputcsv(
			$output,
			array(
				$month,
				$data['desktop'],
				$data['mobile'],
				$data['tablet'],
				$total,
			)
		);
	}

	fclose( $output );
	exit;
}
add_action( 'wp_ajax_vms_export_device_data', 'vms_export_device_data_ajax' );
?>