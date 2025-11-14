<?php
/**
 * Template part for displaying overlay
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visitor_Management_System
 */
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
	<div class="max-w-full overflow-x-auto">
		<table class="min-w-full">
			<!-- table header start -->
			<thead>
				<tr class="border-b border-gray-100 dark:border-gray-800">
					<th class="px-5 py-3 sm:px-6">
						<div class="flex items-center">
							<p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
								<?php esc_html_e( '#', 'vms' ); ?>
							</p>
						</div>
					</th>
					<th class="px-5 py-3 sm:px-6">
						<div class="flex items-center">
							<p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
								<?php esc_html_e( 'User Name', 'vms' ); ?>
							</p>
						</div>
					</th>
					<th class="px-5 py-3 sm:px-6">
						<div class="flex items-center">
							<p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
								<?php esc_html_e( 'First Name', 'vms' ); ?>
							</p>
						</div>
					</th>
					<th class="px-5 py-3 sm:px-6">
						<div class="flex items-center">
							<p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
								<?php esc_html_e( 'Last Name', 'vms' ); ?>
							</p>
						</div>
					</th>
					<th class="px-5 py-3 sm:px-6">
						<div class="flex items-center">
							<p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
								<?php esc_html_e( 'Email', 'vms' ); ?>
							</p>
						</div>
					</th>
					<th class="px-5 py-3 sm:px-6">
						<div class="flex items-center">
							<p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
								<?php esc_html_e( 'Phone Number', 'vms' ); ?>
							</p>
						</div>
					</th>
					<th class="px-5 py-3 sm:px-6">
						<div class="flex items-center">
							<p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
								<?php esc_html_e( 'Status', 'vms' ); ?>
							</p>
						</div>
					</th>
					<th class="px-5 py-3 sm:px-6">
						<div class="flex items-center">
							<p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
								<?php esc_html_e( 'View Details', 'vms' ); ?>
							</p>
						</div>
					</th>
				</tr>
			</thead>
			<!-- table header end -->
			<!-- table body start -->
			<tbody class="divide-y divide-gray-100 dark:divide-gray-800">
				<?php
					// Initialize counter
					$counter = 1;

					// Check if search form submitted
				if ( isset( $_GET['search_users'] ) && ! empty( $_GET['user_search'] ) ) {
					// Handle search
					$user_search = $_GET['user_search'];
					$args        = array(
						'role__in'       => array( 'member', 'chairman' ),
						'search'         => '*' . esc_attr( $user_search ) . '*',
						'search_columns' => array( 'user_login', 'user_nicename' ),
					);

					$users = get_users( $args );

					if ( ! empty( $users ) ) {
						foreach ( $users as $user ) {
							// User data fetching logic
							$user_id             = $user->ID;
							$username            = $user->user_login;
							$email               = $user->user_email;
							$user_register       = $user->user_registered;
							$first_name          = get_user_meta( $user_id, 'first_name', true );
							$last_name           = get_user_meta( $user_id, 'last_name', true );
							$registration_status = get_user_meta( $user_id, 'registration_status', true );
							$user_phone_number   = get_user_meta( $user_id, 'phone_number', true );
							?>
				<tr>
					<td class="px-5 py-4 sm:px-6">
						<p class="text-gray-500 text-theme-sm dark:text-gray-400">
							<?php echo $counter++; ?>
						</p>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<div class="flex items-center gap-3">
								<div>
									<span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
									<?php echo esc_html( $username ); ?>
									</span>
								</div>
							</div>
						</div>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<p class="text-gray-500 text-theme-sm dark:text-gray-400">
								<?php echo esc_html( $first_name ); ?>
							</p>
						</div>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<p class="text-gray-500 text-theme-sm dark:text-gray-400">
								<?php echo esc_html( $last_name ); ?>
							</p>
						</div>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<p class="text-gray-500 text-theme-sm dark:text-gray-400">
								<?php echo esc_html( $email ); ?>
							</p>
						</div>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<p class="text-gray-500 text-theme-sm dark:text-gray-400">
								<?php echo esc_html( $user_phone_number ); ?>
							</p>
						</div>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<?php
							// Map statuses to badge classes
							$status_classes = array(
								'pending'   => 'bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500',
								'active'    => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
								'suspended' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
								'banned'    => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
							);

							// fallback class if status is unknown
							$badge_class = $status_classes[ $registration_status ] ?? 'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80';
							?>

							<span
								class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium <?php echo esc_attr( $badge_class ); ?>">
							<?php echo esc_html( ucfirst( $registration_status ) ); ?>
							</span>

						</div>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<form action="<?php echo esc_url( home_url( '/details' ) ); ?>" method="get">
							<input type="hidden" name="user_id" value="<?php echo esc_attr( $user_id ); ?>">
							<button type="submit"
								class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700"
								data-user-id="<?php echo $user_id; ?>">
								<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
									</path>
								</svg>
							<?php esc_html_e( 'Edit', 'vms' ); ?>
							</button>
						</form>
					</td>
				</tr>
							<?php
						}
					} else {
						echo '<tr><td colspan="10" class="px-4 py-4 text-center text-gray-500 dark:text-white">No employees found.</td></tr>';
					}
				} else {
					// Display all users if no search
					$users = get_users( array( 'role__in' => array( 'member', 'chairman' ) ) );

					if ( ! empty( $users ) ) {
						foreach ( $users as $user ) {
							// Same as above
							$user_id             = $user->ID;
							$username            = $user->user_login;
							$email               = $user->user_email;
							$user_register       = $user->user_registered;
							$first_name          = get_user_meta( $user_id, 'first_name', true );
							$last_name           = get_user_meta( $user_id, 'last_name', true );
							$registration_status = get_user_meta( $user_id, 'registration_status', true );
							$user_phone_number   = get_user_meta( $user_id, 'phone_number', true );
							?>
				<tr>
					<td class="px-5 py-4 sm:px-6">
						<p class="text-gray-500 text-theme-sm dark:text-gray-400">
							<?php echo $counter++; ?>
						</p>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<div class="flex items-center gap-3">
								<div>
									<span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
									<?php echo esc_html( $username ); ?>
									</span>
								</div>
							</div>
						</div>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<p class="text-gray-500 text-theme-sm dark:text-gray-400">
							<?php echo esc_html( $first_name ); ?>
							</p>
						</div>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<p class="text-gray-500 text-theme-sm dark:text-gray-400">
							<?php echo esc_html( $last_name ); ?>
							</p>
						</div>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<p class="text-gray-500 text-theme-sm dark:text-gray-400">
							<?php echo esc_html( $email ); ?>
							</p>
						</div>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<p class="text-gray-500 text-theme-sm dark:text-gray-400">
							<?php echo esc_html( $user_phone_number ); ?>
							</p>
						</div>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<?php
								// Map statuses to badge classes
								$status_classes = array(
									'pending'   => 'bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500',
									'active'    => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
									'suspended' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
									'banned'    => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
								);

								// fallback class if status is unknown
								$badge_class = $status_classes[ $registration_status ] ?? 'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80';
								?>

							<span
								class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium <?php echo esc_attr( $badge_class ); ?>">
							<?php echo esc_html( ucfirst( $registration_status ) ); ?>
							</span>

						</div>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<form action="<?php echo esc_url( home_url( '/details' ) ); ?>" method="get">
							<input type="hidden" name="user_id" value="<?php echo esc_attr( $user_id ); ?>">
							<button type="submit"
								class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700"
								data-user-id="<?php echo $user_id; ?>">
								<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
									</path>
								</svg>
							<?php esc_html_e( 'Edit', 'vms' ); ?>
							</button>
						</form>
					</td>
				</tr>
							<?php
						}
					} else {
						echo '<tr><td colspan="10" class="px-4 py-4 text-center text-gray-500 dark:text-white">No employees found.</td></tr>';
					}
				}
				?>
			</tbody>
		</table>
	</div>
</div>