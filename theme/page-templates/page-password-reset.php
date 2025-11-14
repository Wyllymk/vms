<?php
/**
 * The template for displaying the password reset page.
 *
 * @package Visitor_Management_System
 */

defined( 'ABSPATH' ) || exit;

ob_start();
get_header();

$errors  = array();
$success = '';

// Capture and sanitize query parameters
$key   = isset( $_GET['key'] ) ? html_entity_decode( $_GET['key'] ) : '';
$login = isset( $_GET['login'] ) ? html_entity_decode( $_GET['login'] ) : '';

error_log( 'Reset Debug: key=' . print_r( $key, true ) );
error_log( 'Reset Debug: login=' . print_r( $login, true ) );


if ( ! empty( $key ) && ! empty( $login ) ) {
	$user = check_password_reset_key( $key, $login );
	if ( is_wp_error( $user ) ) {
		error_log( 'Reset Debug: error=' . $user->get_error_message() );
		$errors[] = $user->get_error_message();
	} elseif ( isset( $_POST['reset_password_nonce'] ) && wp_verify_nonce( $_POST['reset_password_nonce'], 'reset_password_action' ) ) {
		$password         = $_POST['password'] ?? '';
		$confirm_password = $_POST['confirm_password'] ?? '';

		if ( $password === $confirm_password ) {
			reset_password( $user, $password );
			$success = 'Your password has been reset successfully. You can now <a href="' . esc_url( site_url( '/login/' ) ) . '">log in</a>.';
		} else {
			$errors[] = 'Passwords do not match.';
		}
	}
} else {
	$errors[] = 'Invalid or expired password reset link.';
}
?>

<div class="wrapper min-h-screen bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
	<section class="reset-content">
		<div class="container mx-auto px-4 py-8">
			<div class="flex flex-col items-center justify-center min-h-screen">
				<div class="w-full max-w-lg">
					<!-- Display errors or success message -->
					<?php if ( ! empty( $errors ) ) : ?>
						<?php foreach ( $errors as $error ) : ?>
					<div class="bg-red-500 text-white p-4 mb-4 rounded"><?php echo esc_html( $error ); ?></div>
					<?php endforeach; ?>
					<?php elseif ( $success ) : ?>
					<div class="bg-green-500 text-white p-4 mb-4 rounded"><?php echo wp_kses_post( $success ); ?></div>
					<?php endif; ?>

					<?php if ( empty( $success ) ) : ?>
					<!-- Password Reset Form -->
					<div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
						<h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6 text-center">
							<?php esc_html_e( 'Reset Your Password', 'vms' ); ?>
						</h2>
						<form method="post">
							<?php wp_nonce_field( 'reset_password_action', 'reset_password_nonce' ); ?>
							<div class="mb-6">
								<label class="block text-gray-700 dark:text-white">
									<?php esc_html_e( 'New Password', 'vms' ); ?>
								</label>
								<input type="password" name="password"
									class="w-full px-4 py-3 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
									required>
							</div>
							<div class="mb-6">
								<label class="block text-gray-700 dark:text-white">
									<?php esc_html_e( 'Confirm New Password', 'vms' ); ?>
								</label>
								<input type="password" name="confirm_password"
									class="w-full px-4 py-3 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
									required>
							</div>
							<button type="submit"
								class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 w-full">
								<?php esc_html_e( 'Reset Password', 'vms' ); ?>
							</button>
						</form>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
</div>

<?php
get_footer();
ob_end_flush();
?>