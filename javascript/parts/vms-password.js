export function initPassword() {
	const $ = jQuery;

	// PASSWORD FORM AJAX
	$('#password-form').on('submit', function (e) {
		e.preventDefault();

		// Get form values
		const currentPassword = $('#current_password').val();
		const newPassword = $('#new_password').val();
		const confirmPassword = $('#confirm_password').val();

		// Clear previous messages
		$('.alert-message').remove();

		// Client-side validation
		if (newPassword !== confirmPassword) {
			showPasswordErrorMessage('New passwords do not match');
			return;
		}

		if (newPassword.length < 8) {
			showPasswordErrorMessage(
				'Password must be at least 8 characters long'
			);
			return;
		}

		// Password strength validation
		if (!isPasswordStrong(newPassword)) {
			showPasswordErrorMessage(
				'Password must contain uppercase, lowercase, number, and special character'
			);
			return;
		}

		// Show loading indicator
		$('#password-submit-button')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Changing Password...'
			);

		// Prepare form data
		const formData = new FormData();
		formData.append('action', 'change_user_password');
		formData.append('nonce', vms_script_ajax.nonce);
		formData.append('current_password', currentPassword);
		formData.append('new_password', newPassword);
		formData.append('confirm_password', confirmPassword);

		// AJAX request
		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function (response) {
				console.log('Password change response:', response);

				if (response.success) {
					// Show success message
					showPasswordSuccessModal(
						response.data.message || 'Password changed successfully'
					);

					// Clear form
					$('#password-form')[0].reset();
				} else {
					// Show error message
					showPasswordErrorMessage(
						response.data.message || 'Failed to change password'
					);
				}
			},
			error: function (xhr, status, error) {
				console.error('AJAX error:', xhr.responseText);
				showPasswordErrorMessage(
					'An error occurred while changing password. Please try again.'
				);
			},
			complete: function () {
				// Reset button
				$('#password-submit-button')
					.prop('disabled', false)
					.text('Change Password');
			},
		});
	});

	// Password strength checker
	function isPasswordStrong(password) {
		const hasUpper = /[A-Z]/.test(password);
		const hasLower = /[a-z]/.test(password);
		const hasNumber = /\d/.test(password);
		const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

		return hasUpper && hasLower && hasNumber && hasSpecial;
	}

	// Show error message
	function showPasswordErrorMessage(message) {
		const errorHtml = `
				<div class="alert-message error-alert bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 dark:bg-red-900/20 dark:border-red-700 dark:text-red-300">
					<div class="flex">
						<div class="flex-shrink-0">
							<svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
								<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
							</svg>
						</div>
						<div class="ml-3">
							<p class="text-sm">${message}</p>
						</div>
					</div>
				</div>
			`;
		$('#password-form').before(errorHtml);

		// Scroll to show message
		$('html, body').animate(
			{
				scrollTop: $('.alert-message').offset().top - 100,
			},
			300
		);
	}

	// Show success modal
	function showPasswordSuccessModal(message) {
		const successModal = `
				<div id="password-success-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
					<div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
						<div class="check_mark mx-auto mb-4">
							<div class="sa-icon sa-success animate">
								<span class="sa-line sa-tip animateSuccessTip"></span>
								<span class="sa-line sa-long animateSuccessLong"></span>
								<div class="sa-placeholder"></div>
								<div class="sa-fix"></div>
							</div>
						</div>
						<p class="text-lg font-medium text-gray-700 dark:text-white mb-2">Password Changed!</p>
						<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">${message}</p>
						<button id="password-ok-btn" type="button" class="inline-block w-1/2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
							OK
						</button>
					</div>
				</div>
			`;

		// Inject modal into body
		$('body').append(successModal);

		// Handle OK button click
		$(document).on('click', '#password-ok-btn', function (e) {
			e.preventDefault();

			// Remove success modal
			$('#password-success-modal-overlay').fadeOut(300, function () {
				$(this).remove();
			});

			// Close password modal if using Alpine.js
			if (typeof window.dispatchEvent === 'function') {
				window.dispatchEvent(new Event('close-password-modal'));
			}

			// Alternative: trigger Alpine close if isPasswordModal exists
			if (window.Alpine) {
				// You might need to adjust this based on your Alpine.js setup
				document.dispatchEvent(new CustomEvent('close-password-modal'));
			}
		});
	}

	// Real-time password validation feedback
	$('#new_password').on('input', function () {
		const password = $(this).val();
		const $field = $(this);

		// Remove existing validation classes
		$field.removeClass('border-red-300 border-green-300');

		if (password.length > 0) {
			if (isPasswordStrong(password) && password.length >= 8) {
				$field.addClass('border-green-300');
			} else {
				$field.addClass('border-red-300');
			}
		}
	});

	// Real-time password match validation
	$('#confirm_password').on('input', function () {
		const password = $('#new_password').val();
		const confirmPassword = $(this).val();
		const $field = $(this);

		// Remove existing validation classes
		$field.removeClass('border-red-300 border-green-300');

		if (confirmPassword.length > 0) {
			if (password === confirmPassword) {
				$field.addClass('border-green-300');
			} else {
				$field.addClass('border-red-300');
			}
		}
	});
}
