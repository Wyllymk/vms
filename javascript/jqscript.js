jQuery(document).ready(function ($) {
	// Settings page functionality
	if ($('#alert-container').length) {
		const alertContainer = $('#alert-container')[0];
		const refreshButton = $('#refresh-balance')[0];
		const testButton = $('#test-connection')[0];
		const saveButton = $('#save-settings')[0];
		const settingsForm = $('#settings-form')[0];

		// Show alert function
		function showAlert(message, type = 'success') {
			const alertClass =
				type === 'success'
					? 'bg-green-500 border-green-700'
					: 'bg-red-500 border-red-700';
			const iconPath =
				type === 'success'
					? 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
					: 'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z';

			const alertHtml = `
                <div class="flex items-center justify-between p-4 mb-4 text-white ${alertClass} border-l-4 rounded" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="${iconPath}" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong>
                            <p class="text-sm">${message}</p>
                        </div>
                    </div>
                    <button type="button" class="text-white hover:text-gray-300" onclick="this.parentElement.style.display='none';">×</button>
                </div>
            `;

			$(alertContainer).html(alertHtml);

			// Auto hide success messages after 5 seconds
			if (type === 'success') {
				setTimeout(() => {
					const alert = $(alertContainer).find('[role="alert"]');
					if (alert.length) {
						alert.fadeOut();
					}
				}, 5000);
			}
		}

		// Handle refresh balance
		if (refreshButton) {
			$(refreshButton).on('click', async function () {
				const originalHtml = this.innerHTML;
				const spinner = `<svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Refreshing...</span>`;

				this.disabled = true;
				this.innerHTML = spinner;

				try {
					const formData = new FormData();
					formData.append('action', 'vms_ajax_refresh_balance');
					formData.append('nonce', vms_script_ajax.nonce);

					const response = await fetch(vms_script_ajax.ajaxurl, {
						method: 'POST',
						body: formData,
					});

					if (!response.ok) {
						throw new Error(
							`HTTP error! status: ${response.status}`
						);
					}

					const data = await response.json();
					console.log('Response data:', data);

					if (data.success) {
						const message =
							data.data?.message ||
							'Balance refreshed successfully';
						showAlert(message, 'success');

						// Update balance dynamically
						if (data.data?.balance !== undefined) {
							$('#balance-amount').text(
								'KES ' +
									Number(data.data.balance).toLocaleString(
										undefined,
										{ minimumFractionDigits: 2 }
									)
							);
						}

						// Update last updated dynamically
						if (data.data?.last_checked !== undefined) {
							$('#last-updated').text(data.data.last_checked);
						}
					} else {
						let errorMsg = 'Failed to refresh balance';
						if (
							data.data?.errors &&
							Array.isArray(data.data.errors)
						) {
							errorMsg = data.data.errors.join(', ');
						} else if (typeof data.data === 'string') {
							errorMsg = data.data;
						} else if (data.message) {
							errorMsg = data.message;
						}
						showAlert(errorMsg, 'error');
					}
				} catch (error) {
					console.error('Error details:', error);
					showAlert('Network error: ' + error.message, 'error');
				} finally {
					this.disabled = false;
					this.innerHTML = originalHtml;
				}
			});
		}

		// Handle settings form submission
		if (settingsForm) {
			$(settingsForm).on('submit', async function (e) {
				e.preventDefault();

				const originalHtml = $(saveButton).html();
				const spinner = `<svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Saving...</span>`;

				$(saveButton).prop('disabled', true).html(spinner);

				try {
					const formData = new FormData(this);
					formData.append('action', 'vms_ajax_save_settings');
					formData.append('nonce', vms_script_ajax.nonce);

					const response = await fetch(vms_script_ajax.ajaxurl, {
						method: 'POST',
						body: formData,
					});

					const data = await response.json();

					if (data.success) {
						const message =
							data.data?.message || 'Settings saved successfully';
						showAlert(message, 'success');
					} else {
						let errorMsg = 'Failed to save settings';
						if (
							data.data?.errors &&
							Array.isArray(data.data.errors)
						) {
							errorMsg = data.data.errors.join(', ');
						} else if (typeof data.data === 'string') {
							errorMsg = data.data;
						} else if (data.message) {
							errorMsg = data.message;
						}
						showAlert(errorMsg, 'error');
					}
				} catch (error) {
					showAlert('Network error: ' + error.message, 'error');
				} finally {
					$(saveButton).prop('disabled', false).html(originalHtml);
				}
			});
		}

		// Handle connection test
		if (testButton) {
			$(testButton).on('click', async function () {
				const originalHtml = this.innerHTML;
				const apiKey = $('#api_key').val();
				const apiSecret = $('#api_secret').val();

				if (!apiKey || !apiSecret) {
					showAlert(
						'Please enter both API Key and API Secret first.',
						'error'
					);
					return;
				}

				const spinner = `<svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Testing...</span>`;

				this.disabled = true;
				this.innerHTML = spinner;

				try {
					const formData = new FormData();
					formData.append('action', 'vms_ajax_test_connection');
					formData.append('api_key', apiKey);
					formData.append('api_secret', apiSecret);
					formData.append('nonce', vms_script_ajax.nonce);

					const response = await fetch(vms_script_ajax.ajaxurl, {
						method: 'POST',
						body: formData,
					});

					const data = await response.json();

					if (data.success) {
						const message =
							data.data?.message || 'Connection test successful!';
						showAlert(message, 'success');
					} else {
						let errorMsg = 'Connection test failed';
						if (
							data.data?.errors &&
							Array.isArray(data.data.errors)
						) {
							errorMsg = data.data.errors.join(', ');
						} else if (typeof data.data === 'string') {
							errorMsg = data.data;
						} else if (data.message) {
							errorMsg = data.message;
						}
						showAlert(errorMsg, 'error');
					}
				} catch (error) {
					showAlert('Network error: ' + error.message, 'error');
				} finally {
					this.disabled = false;
					this.innerHTML = originalHtml;
				}
			});
		}
	}

	// PROFILE FORM
	$('#profile-form').on('submit', function (e) {
		e.preventDefault();

		// Show loading indicator
		$('#submit-button')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Processing...'
			);

		// Clear previous messages
		$('.alert-message').remove();

		// Collect form data
		var formData = new FormData(this);
		formData.append('action', 'update_user_profile');
		formData.append('nonce', vms_script_ajax.nonce);

		// Handle file uploads
		var profilePicture = $('#profile_picture')[0].files[0];
		if (profilePicture) {
			formData.append('profile_picture', profilePicture);
		}

		// AJAX request
		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function (response) {
				console.log('Profile update response:', response);
				if (response.success && response.data.userData) {
					console.log(
						'Updated profile data:',
						response.data.userData
					);

					// Show success messages
					const message =
						response.data.messages[0] ||
						'Changes saved successfully';

					// Create and show success animation modal
					const successModal = `
					<div id="success-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
						<div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
							<div class="check_mark mx-auto mb-4">
								<div class="sa-icon sa-success animate">
									<span class="sa-line sa-tip animateSuccessTip"></span>
									<span class="sa-line sa-long animateSuccessLong"></span>
									<div class="sa-placeholder"></div>
									<div class="sa-fix"></div>
								</div>
							</div>
							<p class="text-lg font-medium text-gray-700 dark:text-white">${message}</p>
							<button id="ok-success-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
								OK
							</button>
						</div>
					</div>
					`;

					// Inject modal into body
					$('#profile-form').append(successModal);

					// Handle OK button click
					$(document).on('click', '#ok-success-btn', function (e) {
						e.preventDefault();

						// Remove success modal
						$('#success-modal-overlay').fadeOut(300, function () {
							$(this).remove();
						});

						// Trigger Alpine to close info modal
						window.dispatchEvent(new Event('close-info-modal'));
					});

					const user = response.data.userData;

					// Update name, email, phone, bio
					$('#profile-first-name').text(
						user.first_name || 'Not provided'
					);
					$('#profile-last-name').text(
						user.last_name || 'Not provided'
					);
					$('#profile-email').text(user.email || 'Not provided');
					$('#profile-phone').text(
						user.phone_number || 'Not provided'
					);
					$('#profile-bio').text(
						user.description || 'No bio provided'
					);

					// Update avatar
					if (user.avatar) {
						$('#profile-avatar').attr('src', user.avatar);
					}

					function getYesIcon() {
						return `
							<svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor"
								viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M5 13l4 4L19 7"></path>
							</svg>
						`;
					}

					function getNoIcon() {
						return `
							<svg class="w-4 h-4 text-warning-400" fill="none" stroke="currentColor"
								viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M6 18L18 6M6 6l12 12"></path>
							</svg>
						`;
					}

					// Replace icon for receive_messages
					$('#profile-receive-messages-icon').html(
						user.receive_messages === 'yes'
							? getYesIcon()
							: getNoIcon()
					);

					// Replace icon for receive_emails
					$('#profile-receive-emails-icon').html(
						user.receive_emails === 'yes'
							? getYesIcon()
							: getNoIcon()
					);

					// You can also update the display name in the header or breadcrumb if needed
					$('.profile-display-name').text(user.display_name || '');
				} else {
					// Show error messages
					response.data.messages.forEach(function (message) {
						$('#profile-form').before(
							'<div class="alert-message error-alert">' +
								message +
								'</div>'
						);
					});
				}
			},
			error: function (xhr, status, error) {
				$('#profile-form').before(
					'<div class="alert-message error-alert">An error occurred: ' +
						error +
						'</div>'
				);
			},
			complete: function () {
				// Reset button
				$('#submit-button')
					.prop('disabled', false)
					.text('Save Changes');

				// Scroll to top to show messages
				$('html, body').animate(
					{
						scrollTop: 0,
					},
					500
				);
			},
		});
	});

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

	// Guest FORM
	$('#guest-form').on('submit', function (e) {
		e.preventDefault();

		// Show loading indicator
		$('#submit-guest-form')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Registering...'
			);

		// Clear previous messages and modals
		$('.alert-message').remove();
		$('#success-modal-overlay, #guest-error-modal-overlay').remove();

		// Collect form data
		var formData = new FormData(this);
		formData.append('action', 'guest_registration');
		formData.append('nonce', vms_script_ajax.nonce);

		// Action buttons generator
		const actionButtons = (guest) => {
			if (!guest) return '';

			const today = new Date().toISOString().split('T')[0];
			const normalizedVisitDate = guest.visit_date
				? guest.visit_date.substring(0, 10)
				: null;

			const isButtonDisabled =
				!normalizedVisitDate ||
				!guest.status ||
				!/^\d{4}-\d{2}-\d{2}$/.test(normalizedVisitDate) ||
				guest.status !== 'approved';

			const isFuture = normalizedVisitDate > today;
			const isPast = normalizedVisitDate < today;
			const isToday = normalizedVisitDate === today;

			const isMissed = isPast && !guest.sign_in_time;
			const isScheduled = isFuture;
			const isCompleted = guest.sign_in_time && guest.sign_out_time;

			const baseButtonClasses =
				'inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg whitespace-nowrap shadow-theme-xs';
			const disabledClasses =
				'bg-brand-500 opacity-50 cursor-not-allowed';
			const signInEnabledClasses =
				'bg-brand-500 cursor-pointer hover:bg-brand-600';
			const signOutEnabledClasses =
				'bg-purple-500 cursor-pointer hover:bg-purple-600';

			if (isMissed) {
				return `<span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-warning-600 bg-warning-50 rounded-lg dark:bg-warning-500/15 dark:text-orange-500">Missed</span>`;
			}

			if (isScheduled) {
				return `<span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-light-500 bg-blue-light-50 rounded-lg dark:bg-blue-light-500/15 dark:text-blue-light-500">Scheduled</span>`;
			}

			if (isToday) {
				if (isCompleted) {
					const signInTime = new Date(
						guest.sign_in_time
					).toLocaleTimeString([], {
						hour: 'numeric',
						minute: '2-digit',
					});
					const signOutTime = new Date(
						guest.sign_out_time
					).toLocaleTimeString([], {
						hour: 'numeric',
						minute: '2-digit',
					});
					return `<div class="flex flex-col items-center justify-center text-xs px-4"><span class="text-green-600 dark:text-green-400">${signInTime}</span><span class="text-red-600 dark:text-red-400">${signOutTime}</span></div>`;
				} else if (!guest.sign_in_time) {
					return `<button id="sign-in-button-${guest.id}" class="${baseButtonClasses} ${isButtonDisabled ? disabledClasses : signInEnabledClasses}" data-visit-id="${guest.visit_id}" ${isButtonDisabled ? 'disabled' : ''}>Sign In</button>`;
				} else if (!guest.sign_out_time) {
					return `<button id="sign-out-button-${guest.id}" class="${baseButtonClasses} ${isButtonDisabled ? disabledClasses : signOutEnabledClasses}" data-visit-id="${guest.visit_id}" ${isButtonDisabled ? 'disabled' : ''}>Sign Out</button>`;
				}
			}

			return '';
		};

		// AJAX request
		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function (response) {
				if (response.success && response.data.guestData) {
					const guest = response.data.guestData;

					// Format visit date
					let formattedDate = 'N/A';
					if (guest.visit_date) {
						const visitDate = new Date(guest.visit_date);
						if (!isNaN(visitDate)) {
							formattedDate = visitDate.toLocaleDateString(
								'en-US',
								{
									month: 'short',
									day: 'numeric',
									year: 'numeric',
								}
							);
						}
					}

					// Host name
					let hostName = guest.host_name || 'N/A';
					if (guest.host_name) {
						// Split display_name "wilson.mbuthia" → "Wilson Mbuthia"
						hostName = guest.host_name
							.split('.')
							.map(
								(part) =>
									part.charAt(0).toUpperCase() + part.slice(1)
							)
							.join(' ');
					}

					// Status classes
					const statusClasses = {
						approved:
							'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
						unapproved:
							'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
						suspended:
							'bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500',
						banned: 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
						cancelled:
							'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80',
					};

					// Build row
					const newRow = `
                    <tr>
                        <td class="px-3 py-4 sm:px-6"><p class="text-gray-500 text-theme-sm dark:text-gray-400">${$('tbody tr').length + 1}</p></td>
                        <td class="px-3 py-4 sm:px-6"><p class="text-gray-800 text-theme-sm dark:text-white/90">${guest.first_name || 'N/A'}</p></td>
                        <td class="px-3 py-4 sm:px-6"><p class="text-gray-800 text-theme-sm dark:text-white/90">${guest.last_name || 'N/A'}</p></td>
                        <td class="px-3 py-4 sm:px-6"><span class="inline-flex items-center justify-center px-2.5 gap-1 py-0.5 text-sm font-medium capitalize rounded-full ${statusClasses[guest.status] || ''}">${guest.status ? guest.status.charAt(0).toUpperCase() + guest.status.slice(1) : 'N/A'}</span></td>
                        <td class="px-3 py-4 sm:px-6"><p class="text-gray-500 text-theme-sm dark:text-gray-400">${guest.id_number || 'N/A'}</p></td>
                        <td class="px-3 py-4 sm:px-6"><p class="text-gray-500 text-theme-sm dark:text-gray-400">${hostName}</p></td>
                        <td class="px-3 py-4 sm:px-6"><p class="text-gray-500 text-theme-sm dark:text-gray-400">${formattedDate}</p></td>
                        <td class="px-3 py-4 sm:px-6">
                            <div class="flex items-center gap-2">
                                <button id="edit-guest-button-${guest.id}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
								<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
								Edit
								</button>
                                ${actionButtons(guest)}
                            </div>
                        </td>
                    </tr>
                	`;

					// Remove "no guests" row
					$('#no-guests-row').remove();

					// Prepend row
					$('tbody').prepend(newRow);

					// Re-number rows
					$('tbody tr').each(function (index) {
						$(this)
							.find('td:first p')
							.text(index + 1);
					});

					// Show success modal
					const message =
						response.data.messages?.[0] ||
						'Guest registered successfully';
					const successModal = `
                    <div id="success-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                        <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                            <div class="check_mark mx-auto mb-4">
                                <div class="sa-icon sa-success animate">
                                    <span class="sa-line sa-tip animateSuccessTip"></span>
                                    <span class="sa-line sa-long animateSuccessLong"></span>
                                    <div class="sa-placeholder"></div>
                                    <div class="sa-fix"></div>
                                </div>
                            </div>
                            <p class="text-lg font-medium text-gray-700 dark:text-white">${message}</p>
                            <button id="ok-success-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">OK</button>
                        </div>
                    </div>
                `;
					$('body').append(successModal);

					$(document)
						.off('click', '#ok-success-btn')
						.on('click', '#ok-success-btn', function (e) {
							e.preventDefault();
							$('#success-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
									window.dispatchEvent(
										new Event('close-guest-modal')
									);
									$('#guest-form')[0].reset();

									// Reload same URL
									// window.location.reload();
								}
							);
						});
				} else {
					// Show error modal
					const errorMessages = response.data?.messages || [
						'An error occurred during guest registration',
					];
					const errorMessageHtml = errorMessages
						.map(
							(msg) =>
								`<p class="text-lg font-medium text-gray-700 dark:text-white">${msg}</p>`
						)
						.join('');
					const errorModal = `
                    <div id="guest-error-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                        <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                            <div class="check_mark mx-auto mb-4">
                                <div class="sa-icon sa-error animate">
                                    <span class="sa-line sa-left animateXLeft"></span>
                                    <span class="sa-line sa-right animateXRight"></span>
                                    <div class="sa-placeholder"></div>
                                </div>
                            </div>
                            ${errorMessageHtml}
                            <button id="ok-error-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">OK</button>
                        </div>
                    </div>
                `;
					$('body').append(errorModal);
					$(document)
						.off('click', '#ok-error-btn')
						.on('click', '#ok-error-btn', function (e) {
							e.preventDefault();
							$('#guest-error-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
								}
							);
						});
				}
			},
			error: function (xhr, status, error) {
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;
				const errorModal = `
                <div id="guest-error-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                    <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                        <div class="check_mark mx-auto mb-4">
                            <div class="sa-icon sa-error animate">
                                <span class="sa-line sa-left animateXLeft"></span>
                                <span class="sa-line sa-right animateXRight"></span>
                                <div class="sa-placeholder"></div>
                            </div>
                        </div>
                        <p class="text-lg font-medium text-gray-700 dark:text-white">${errorMessage}</p>
                        <button id="ok-error-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">OK</button>
                    </div>
                </div>
            `;
				$('body').append(errorModal);
				$(document)
					.off('click', '#ok-error-btn')
					.on('click', '#ok-error-btn', function (e) {
						e.preventDefault();
						$('#guest-error-modal-overlay').fadeOut(
							300,
							function () {
								$(this).remove();
							}
						);
					});
			},
			complete: function () {
				$('#submit-guest-form')
					.prop('disabled', false)
					.text('Create Guest');
			},
		});
	});

	// Club Form Submit (Create/Update)
	$('.club-form').on('submit', function (e) {
		e.preventDefault();

		const clubId = $('#club_id').val();
		const isEdit = clubId !== '';

		// Show loading indicator
		$('.submit-club-form')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> ' +
					(isEdit ? 'Updating...' : 'Creating...')
			);

		// Clear previous messages and modals
		$('.alert-message').remove();
		$('#success-modal-overlay, #club-error-modal-overlay').remove();

		// Collect form data
		var formData = new FormData(this);
		formData.append('action', isEdit ? 'club_update' : 'club_registration');
		formData.append('nonce', vms_script_ajax.nonce);

		// AJAX request
		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function (response) {
				if (response.success && response.data.clubData) {
					const club = response.data.clubData;

					// Format creation date
					let creationformattedDate = 'N/A';
					if (club.created_at) {
						const creationDate = new Date(club.created_at);
						if (!isNaN(creationDate)) {
							creationformattedDate =
								creationDate.toLocaleDateString('en-US', {
									month: 'short',
									day: 'numeric',
									year: 'numeric',
								});
						}
					}
					let updateformattedDate = 'N/A';
					if (club.updated_at) {
						const updateDate = new Date(club.updated_at);
						if (!isNaN(updateDate)) {
							updateformattedDate = updateDate.toLocaleDateString(
								'en-US',
								{
									month: 'short',
									day: 'numeric',
									year: 'numeric',
								}
							);
						}
					}

					// Status classes
					const statusClasses = {
						active: 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
						suspended:
							'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
						banned: 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
					};

					if (isEdit) {
						// Update existing row
						const row = $(`tr[data-club-id="${club.id}"]`);
						row.find('td:nth-child(2) p').text(club.club_name);
						row.find('td:nth-child(3) span')
							.removeClass()
							.addClass(
								`inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium capitalize ${statusClasses[club.status] || statusClasses.active}`
							)
							.text(
								club.status.charAt(0).toUpperCase() +
									club.status.slice(1)
							);
					} else {
						// Build new row
						const newRow = `
                        <tr data-club-id="${club.id}">
                            <td class="px-3 py-4 sm:px-6"><p class="text-gray-500 text-theme-sm dark:text-gray-400">${$('#clubs-table-body tr').length + 1}</p></td>
                            <td class="px-3 py-4 sm:px-6"><p class="text-gray-800 text-theme-sm dark:text-white/90">${club.club_name}</p></td>
                            <td class="px-3 py-4 sm:px-6"><span class="inline-flex items-center justify-center px-2.5 gap-1 py-0.5 text-sm font-medium capitalize rounded-full ${statusClasses[club.status] || statusClasses.active}">${club.status.charAt(0).toUpperCase() + club.status.slice(1)}</span></td>
                            <td class="px-3 py-4 sm:px-6"><p class="text-gray-500 text-theme-sm dark:text-gray-400">${creationformattedDate}</p></td>
                            <td class="px-3 py-4 sm:px-6"><p class="text-gray-500 text-theme-sm dark:text-gray-400">${updateformattedDate}</p></td>
                            <td class="px-3 py-4 sm:px-6">
                                <div class="flex items-center gap-2">
                                    <button class="edit-club-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700" data-club-id="${club.id}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>Edit
                                    </button>
                                    <button class="delete-club-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-red-500 border border-red-500 rounded-lg cursor-pointer whitespace-nowrap hover:bg-red-600 dark:hover:bg-red-600" data-club-id="${club.id}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;

						// Remove "no clubs" row if exists
						$('#no-clubs-row').remove();

						// Prepend row to table body
						$('#clubs-table-body').prepend(newRow);

						// Re-number rows
						$('#clubs-table-body tr').each(function (index) {
							$(this)
								.find('td:first p')
								.text(index + 1);
						});
					}

					// Show success modal
					const message =
						response.data.messages?.[0] ||
						(isEdit
							? 'Club updated successfully'
							: 'Club created successfully');
					const successModal = `
                    <div id="success-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                        <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                            <div class="check_mark mx-auto mb-4">
                                <div class="sa-icon sa-success animate">
                                    <span class="sa-line sa-tip animateSuccessTip"></span>
                                    <span class="sa-line sa-long animateSuccessLong"></span>
                                    <div class="sa-placeholder"></div>
                                    <div class="sa-fix"></div>
                                </div>
                            </div>
                            <p class="text-lg font-medium text-gray-700 dark:text-white">${message}</p>
                            <button id="ok-success-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">OK</button>
                        </div>
                    </div>
                `;
					$('body').append(successModal);

					$(document)
						.off('click', '#ok-success-btn')
						.on('click', '#ok-success-btn', function (e) {
							e.preventDefault();
							$('#success-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
									$('.club-form')[0].reset();
									$('#club_id').val('');
									// Close modal using Alpine.js
									window.Alpine &&
										window.Alpine.store &&
										window.Alpine.store('clubModal') &&
										(window.Alpine.store(
											'clubModal'
										).isClubEditModal = false);
								}
							);
						});
				} else {
					showErrorClubModal(
						response.data?.messages || [
							'An error occurred during club operation',
						]
					);
				}
			},
			error: function (xhr, status, error) {
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;
				showErrorClubModal([errorMessage]);
			},
			complete: function () {
				$('.submit-club-form')
					.prop('disabled', false)
					.text('Save Club');
			},
		});
	});

	// Reciprocating Member FORM
	$('#reciprocation-form').on('submit', function (e) {
		e.preventDefault();

		// Show loading indicator
		$('#submit-reciprocating-form')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Registering...'
			);

		// Clear previous messages and modals
		$('.alert-message').remove();
		$(
			'#success-modal-overlay, #reciprocating-error-modal-overlay'
		).remove();

		// Collect form data
		var formData = new FormData(this);
		formData.append('action', 'reciprocating_member_registration');
		formData.append('nonce', vms_script_ajax.nonce);

		// Action buttons generator
		const actionButtons = (member) => {
			if (!member) return '';

			const today = new Date().toISOString().split('T')[0];
			const normalizedVisitDate = member.visit_date
				? member.visit_date.substring(0, 10)
				: null;

			const isButtonDisabled =
				!normalizedVisitDate ||
				!member.status ||
				!/^\d{4}-\d{2}-\d{2}$/.test(normalizedVisitDate) ||
				member.status !== 'approved' ||
				member.member_status !== 'active';

			const isFuture = normalizedVisitDate > today;
			const isPast = normalizedVisitDate < today;
			const isToday = normalizedVisitDate === today;

			const isMissed = isPast && !member.sign_in_time;
			const isScheduled = isFuture;
			const isCompleted = member.sign_in_time && member.sign_out_time;

			const baseButtonClasses =
				'inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg whitespace-nowrap shadow-theme-xs';
			const disabledClasses =
				'bg-brand-500 opacity-50 cursor-not-allowed';
			const signInEnabledClasses =
				'bg-brand-500 cursor-pointer hover:bg-brand-600';
			const signOutEnabledClasses =
				'bg-purple-500 cursor-pointer hover:bg-purple-600';

			if (member.status === 'suspended') {
				return `<span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-light-500 bg-blue-light-50 rounded-lg dark:bg-blue-light-500/15 dark:text-blue-light-500">Suspended</span>`;
			}

			if (member.status === 'banned') {
				return `<span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-error-600 bg-error-50 rounded-lg dark:bg-error-500/15 dark:text-error-500">Banned</span>`;
			}

			if (member.status === 'cancelled') {
				return `<span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg dark:bg-white/5 dark:text-white/80">Cancelled</span>`;
			}

			if (isMissed) {
				return `<span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-warning-600 bg-warning-50 rounded-lg dark:bg-warning-500/15 dark:text-orange-500">Missed</span>`;
			}

			if (isScheduled) {
				return `<span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-light-500 bg-blue-light-50 rounded-lg dark:bg-blue-light-500/15 dark:text-blue-light-500">Scheduled</span>`;
			}

			if (isToday) {
				if (isCompleted) {
					const signInTime = new Date(
						member.sign_in_time
					).toLocaleTimeString([], {
						hour: 'numeric',
						minute: '2-digit',
					});
					const signOutTime = new Date(
						member.sign_out_time
					).toLocaleTimeString([], {
						hour: 'numeric',
						minute: '2-digit',
					});
					return `<div class="flex flex-col items-center justify-center text-xs px-4"><span class="text-green-600 dark:text-green-400">${signInTime}</span><span class="text-red-600 dark:text-red-400">${signOutTime}</span></div>`;
				} else if (!member.sign_in_time) {
					return `<button id="reciprocating-sign-in-button-${member.id}" class="${baseButtonClasses} ${isButtonDisabled ? disabledClasses : signInEnabledClasses}" data-visit-id="${member.visit_id}" ${isButtonDisabled ? 'disabled' : ''}>Sign In</button>`;
				} else if (!member.sign_out_time) {
					return `<button id="reciprocating-sign-out-button-${member.id}" class="${baseButtonClasses} ${isButtonDisabled ? disabledClasses : signOutEnabledClasses}" data-visit-id="${member.visit_id}" ${isButtonDisabled ? 'disabled' : ''}>Sign Out</button>`;
				}
			}

			return '';
		};

		// AJAX request
		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function (response) {
				if (response.success && response.data.memberData) {
					const member = response.data.memberData;

					console.log(member);

					// Format visit date
					let formattedDate = 'N/A';
					if (member.visit_date) {
						const visitDate = new Date(member.visit_date);
						if (!isNaN(visitDate)) {
							formattedDate = visitDate.toLocaleDateString(
								'en-US',
								{
									month: 'short',
									day: 'numeric',
									year: 'numeric',
								}
							);
						}
					}

					// Club name formatting
					let clubName = member.club_name || 'N/A';
					if (member.club_name && member.club_name.includes('.')) {
						// Split display_name "wilson.mbuthia" → "Wilson Mbuthia"
						clubName = member.club_name
							.split('.')
							.map(
								(part) =>
									part.charAt(0).toUpperCase() + part.slice(1)
							)
							.join(' ');
					}

					// Status classes
					const statusClasses = {
						approved:
							'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
						unapproved:
							'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
						suspended:
							'bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500',
						banned: 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
						cancelled:
							'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80',
					};

					// Build row
					const newRow = `
						<tr data-member-id="${member.id}" data-visit-id="${member.visit_id}">
							<td class="px-3 py-4 sm:px-6"><p class="text-gray-500 text-theme-sm dark:text-gray-400">${$('tbody tr').length + 1}</p></td>
							<td class="px-3 py-4 sm:px-6"><p class="text-gray-800 text-theme-sm dark:text-white/90">${member.first_name || 'N/A'}</p></td>
							<td class="px-3 py-4 sm:px-6"><p class="text-gray-800 text-theme-sm dark:text-white/90">${member.last_name || 'N/A'}</p></td>
							<td class="px-3 py-4 sm:px-6"><span class="inline-flex items-center justify-center px-2.5 gap-1 py-0.5 text-sm font-medium capitalize rounded-full ${statusClasses[member.status] || ''}">
							${member.status ? member.status.charAt(0).toUpperCase() + member.status.slice(1) : 'N/A'}</span>
							</td>
							<td class="px-3 py-4 sm:px-6"><p class="text-gray-500 text-theme-sm dark:text-gray-400">${clubName}</p></td>
							<td class="px-3 py-4 sm:px-6"><p class="text-gray-500 text-theme-sm dark:text-gray-400">${member.reciprocating_member_number || 'N/A'}</p></td>
							<td class="px-3 py-4 sm:px-6"><p class="text-gray-500 text-theme-sm dark:text-gray-400">${formattedDate}</p></td>
							<td class="px-3 py-4 sm:px-6">
								<div class="flex items-center gap-2">
									<button id="edit-reciprocating-member-button-${member.id}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700" data-member-id="${member.id}" data-visit-id="${member.visit_id}">
										<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
										</svg>
										Edit
									</button>
									${actionButtons(member)}
								</div>
							</td>
						</tr>
						`;

					// Remove "no members" row if exists
					$('#no-reciprocating-members-row').remove();

					// Prepend row to table
					$('#reciprocating-members-table-body').prepend(newRow);

					// Re-number rows
					$('#reciprocating-members-table-body tr').each(
						function (index) {
							$(this)
								.find('td:first p')
								.text(index + 1);
						}
					);

					// Show success modal
					const message =
						response.data.messages?.[0] ||
						'Reciprocating member registered successfully';
					const successModal = `
                <div id="success-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                    <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                        <div class="check_mark mx-auto mb-4">
                            <div class="sa-icon sa-success animate">
                                <span class="sa-line sa-tip animateSuccessTip"></span>
                                <span class="sa-line sa-long animateSuccessLong"></span>
                                <div class="sa-placeholder"></div>
                                <div class="sa-fix"></div>
                            </div>
                        </div>
                        <p class="text-lg font-medium text-gray-700 dark:text-white">${message}</p>
                        <button id="ok-success-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">OK</button>
                    </div>
                </div>
            `;
					$('body').append(successModal);

					$(document)
						.off('click', '#ok-success-btn')
						.on('click', '#ok-success-btn', function (e) {
							e.preventDefault();
							$('#success-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
									window.dispatchEvent(
										new Event('close-reciprocating-modal')
									);
									$('#reciprocation-form')[0].reset();
								}
							);
						});
				} else {
					// Show error modal
					const errorMessages = response.data?.messages || [
						'An error occurred during reciprocating member registration',
					];
					const errorMessageHtml = errorMessages
						.map(
							(msg) =>
								`<p class="text-lg font-medium text-gray-700 dark:text-white">${msg}</p>`
						)
						.join('');
					const errorModal = `
                <div id="reciprocating-error-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                    <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                        <div class="check_mark mx-auto mb-4">
                            <div class="sa-icon sa-error animate">
                                <span class="sa-line sa-left animateXLeft"></span>
                                <span class="sa-line sa-right animateXRight"></span>
                                <div class="sa-placeholder"></div>
                            </div>
                        </div>
                        ${errorMessageHtml}
                        <button id="ok-error-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">OK</button>
                    </div>
                </div>
            	`;
					$('body').append(errorModal);
					$(document)
						.off('click', '#ok-error-btn')
						.on('click', '#ok-error-btn', function (e) {
							e.preventDefault();
							$('#reciprocating-error-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
								}
							);
						});
				}
			},
			error: function (xhr, status, error) {
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;
				const errorModal = `
            <div id="reciprocating-error-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                    <div class="check_mark mx-auto mb-4">
                        <div class="sa-icon sa-error animate">
                            <span class="sa-line sa-left animateXLeft"></span>
                            <span class="sa-line sa-right animateXRight"></span>
                            <div class="sa-placeholder"></div>
                        </div>
                    </div>
                    <p class="text-lg font-medium text-gray-700 dark:text-white">${errorMessage}</p>
                    <button id="ok-error-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">OK</button>
                </div>
            </div>
        `;
				$('body').append(errorModal);
				$(document)
					.off('click', '#ok-error-btn')
					.on('click', '#ok-error-btn', function (e) {
						e.preventDefault();
						$('#reciprocating-error-modal-overlay').fadeOut(
							300,
							function () {
								$(this).remove();
							}
						);
					});
			},
			complete: function () {
				$('#submit-reciprocating-form')
					.prop('disabled', false)
					.text('Create Reciprocating Member');
			},
		});
	});

	// Edit Member Button Handler
	$(document).on(
		'click',
		'[id^="edit-reciprocating-member-button-"]',
		function (e) {
			e.preventDefault();

			// Extract member ID from button ID
			const memberId = $(this)
				.attr('id')
				.replace('edit-reciprocating-member-button-', '');

			// Redirect to edit page
			window.location.href =
				vms_script_ajax.home_url +
				'/reciprocating-member-details/?member_id=' +
				memberId +
				'&paged=1';
		}
	);

	// Handle reciprocating member sign in
	$(document).on(
		'click',
		'[id^="reciprocating-sign-in-button-"]',
		function (e) {
			e.preventDefault();

			const visitId = $(this).data('visit-id');
			const button = $(this);
			const memberName = button
				.closest('tr')
				.find('td:nth-child(2)')
				.text()
				.trim();

			// Confirmation modal (uses classes instead of IDs inside)
			const confirmModal = `
		<div class="sign-in-confirm-modal-overlay fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
			<div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
				<div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
					<svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
					</svg>
				</div>
				<h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">Sign In Reciprocating Member</h3>
				<p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Sign in "${memberName}" to the system?</p>
				<div class="flex gap-3">
					<button type="button" class="cancel-sign-in-btn flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Cancel</button>
					<button type="button" class="confirm-sign-in-btn flex-1 rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-600" data-visit-id="${visitId}">Sign In</button>
				</div>
			</div>
		</div>
	`;
			$('body').append(confirmModal);
		}
	);

	// Cancel Sign In
	$(document).on('click', '.cancel-sign-in-btn', function () {
		$('.sign-in-confirm-modal-overlay').fadeOut(300, function () {
			$(this).remove();
		});
	});

	// Confirm Sign In
	$(document).on('click', '.confirm-sign-in-btn', function () {
		const visitId = $(this).data('visit-id');
		const confirmBtn = $(this);
		const button = $(
			`[id^="reciprocating-sign-in-button-"][data-visit-id="${visitId}"]`
		);

		// Show loading spinner on confirm button
		confirmBtn
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Signing In...'
			);

		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: {
				action: 'reciprocating_member_sign_in',
				visit_id: visitId,
				nonce: vms_script_ajax.nonce,
			},
			dataType: 'json',
			success: function (response) {
				if (response.success) {
					const member = response.data.memberData;
					//console.log('Sign in successful:', response);

					// Update row badge
					const row = button.closest('tr');
					const statusCell = row.find('td:nth-child(4) span');
					statusCell.text('Signed In');

					// Replace sign in button with sign out button
					const signOutBtn = `
                    <button id="reciprocating-sign-out-button-${member.id}" class="sign-out-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg cursor-pointer whitespace-nowrap bg-purple-500 shadow-theme-xs hover:bg-purple-600"
                        data-visit-id="${member.visit_id}" data-member-id="${member.id}">
                        Sign Out
                    </button>
                	`;
					button.replaceWith(signOutBtn);

					// Close modal
					$('.sign-in-confirm-modal-overlay').fadeOut(
						300,
						function () {
							$(this).remove();
						}
					);

					// Show success modal
					const successMessage =
						response.data.message ||
						'Reciprocating Member signed in successfully';
					showSuccessModal(successMessage);
				} else {
					console.error('Sign in failed:', response);
					const errorMessage =
						response.data?.message ||
						'Error signing in Reciprocating Member';
					showErrorModal(errorMessage);
					$('.sign-in-confirm-modal-overlay').fadeOut(
						300,
						function () {
							$(this).remove();
						}
					);
				}
			},
			error: function (xhr, status, error) {
				console.error('Sign in error:', error);
				const errorMessage =
					xhr.responseJSON?.data?.message ||
					'An error occurred: ' + error;
				showErrorModal(errorMessage);
				$('.sign-in-confirm-modal-overlay').fadeOut(300, function () {
					$(this).remove();
				});
			},
			complete: function () {
				confirmBtn.prop('disabled', false).text('Sign In');
			},
		});
	});

	// Handle reciprocating member sign out
	$(document).on(
		'click',
		'[id^="reciprocating-sign-out-button-"]',
		function (e) {
			e.preventDefault();

			const visitId = $(this).data('visit-id');
			const button = $(this);
			const memberName = button
				.closest('tr')
				.find('td:nth-child(2)')
				.text()
				.trim();

			// Show confirmation modal
			const confirmModal = `
				<div id="sign-out-confirm-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
					<div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
						<div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
							<svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
							</svg>
						</div>
						<h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">Sign Out Reciprocating Member</h3>
						<p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Sign out "${memberName}" from the system?</p>
						<div class="flex gap-3">
							<button class="cancel-sign-out-btn flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Cancel</button>
							<button class="confirm-sign-out-btn flex-1 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600" data-visit-id="${visitId}">Sign Out</button>
						</div>
					</div>
				</div>
				`;
			$('body').append(confirmModal);
		}
	);

	// Cancel Sign Out
	$(document).on('click', '.cancel-sign-out-btn', function () {
		$('#sign-out-confirm-modal-overlay').fadeOut(300, function () {
			$(this).remove();
		});
	});

	// Confirm Sign Out
	$(document).on('click', '.confirm-sign-out-btn', function () {
		const visitId = $(this).data('visit-id');
		const button = $(
			`[id^="reciprocating-sign-out-button-"][data-visit-id="${visitId}"]`
		);

		// Show loading
		$(this)
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Signing Out...'
			);

		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: {
				action: 'reciprocating_member_sign_out',
				visit_id: visitId,
				nonce: vms_script_ajax.nonce,
			},
			dataType: 'json',
			success: function (response) {
				if (response.success && response.data.recipData) {
					// Replace button with completed times
					const member = response.data.recipData;

					const formatTime = (time) => {
						if (!time) return '';
						const date = new Date(time);
						if (isNaN(date)) return '';
						return date.toLocaleTimeString('en-US', {
							hour: 'numeric',
							minute: '2-digit',
							hour12: true,
						});
					};

					const parentContainer = button.closest(
						'.flex.items-center.gap-2'
					);
					button.remove();

					parentContainer.append(`
					<div class="flex flex-col items-center justify-center text-xs px-4">
						<span class="text-green-600 dark:text-green-400">${formatTime(member.sign_in_time)}</span>
						<span class="text-red-600 dark:text-red-400">${formatTime(member.sign_out_time)}</span>
					</div>
					`);

					// Close modal
					$('#sign-out-confirm-modal-overlay').fadeOut(
						300,
						function () {
							$(this).remove();
						}
					);

					const successMessage =
						response.data.message ||
						'Reciprocating Member signed out successfully';
					showSuccessModal(successMessage);
				} else {
					console.error('Sign out failed:', response);
					const errorMessage =
						response.data?.messages?.join('<br>') ||
						'Error signing out Reciprocating Member';
					showErrorModal(errorMessage);

					// Close modal on error
					$('#sign-out-confirm-modal-overlay').fadeOut(
						300,
						function () {
							$(this).remove();
						}
					);
				}
			},
			error: function (xhr, status, error) {
				console.error('Sign out error:', error);
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;
				showErrorModal(errorMessage);

				$('#sign-out-confirm-modal-overlay').fadeOut(300, function () {
					$(this).remove();
				});
			},
			complete: function () {
				$('.confirm-sign-out-btn')
					.prop('disabled', false)
					.text('Sign Out');
			},
		});
	});

	// Notification helper function
	function showNotification(type, message) {
		const notificationClass =
			type === 'success'
				? 'bg-success-50 text-success-600 border-success-200'
				: 'bg-error-50 text-error-600 border-error-200';

		const notification = `
        <div class="fixed top-4 right-4 z-[999999] ${notificationClass} border rounded-lg p-4 shadow-lg animate-fade-in-down">
            <p class="text-sm font-medium">${message}</p>
        </div>
    	`;

		$('body').append(notification);

		// Auto remove after 3 seconds
		setTimeout(() => {
			$('.fixed.top-4.right-4').fadeOut(300, function () {
				$(this).remove();
			});
		}, 3000);
	}

	// Edit Club Button Click
	$(document).on('click', '.edit-club-btn', function () {
		const $btn = $(this);
		const clubId = $btn.data('club-id');
		const originalHtml = $btn.html();

		$btn.prop('disabled', true).html(`
        <span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span>
        <span>Loading...</span>
    	`);

		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: {
				action: 'get_club_data',
				nonce: vms_script_ajax.nonce,
				club_id: clubId,
			},
			dataType: 'json',
			success: function (response) {
				if (response.success && response.data.clubData) {
					const club = response.data.clubData;

					// Populate form
					$('#club_id').val(club.id);
					$('#club_name').val(club.club_name);
					$('[name="club_email"]').val(club.club_email);
					$('[name="club_phone"]').val(club.club_phone);
					$('[name="club_address"]').val(club.club_address);
					$('[name="club_website"]').val(club.club_website);
					$('#club_status').val(club.status);
					$('[name="notes"]').val(club.notes);

					// Update modal
					$('#club-modal-title').text('Edit Club');
					$('#club-modal-description').text(
						'Update Club Information.'
					);

					window.Alpine.store('clubModal').open();
				} else {
					showErrorClubModal(
						response.data?.messages || ['Failed to load club data']
					);
				}
			},
			error: function () {
				showErrorClubModal(['Failed to load club data']);
			},
			complete: function () {
				$btn.prop('disabled', false).html(originalHtml);
			},
		});
	});

	// Delete Club Button Click
	$(document).on('click', '.delete-club-btn', function () {
		const clubId = $(this).data('club-id');
		const clubName = $(this).closest('tr').find('td:nth-child(2) p').text();

		// Show confirmation modal
		const confirmModal = `
            <div id="delete-confirm-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">Delete Club</h3>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Are you sure you want to delete "${clubName}"? This action cannot be undone.</p>
                    <div class="flex gap-3">
                        <button id="cancel-delete-btn" type="button" class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Cancel</button>
                        <button id="confirm-delete-btn" type="button" class="flex-1 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600" data-club-id="${clubId}">Delete</button>
                    </div>
                </div>
            </div>
        `;
		$('body').append(confirmModal);
	});

	// Cancel Delete
	$(document).on('click', '#cancel-delete-btn', function () {
		$('#delete-confirm-modal-overlay').fadeOut(300, function () {
			$(this).remove();
		});
	});

	// Confirm Delete
	$(document).on('click', '#confirm-delete-btn', function () {
		const clubId = $(this).data('club-id');

		// Show loading
		$(this)
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Deleting...'
			);

		// Delete club
		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: {
				action: 'delete_club',
				nonce: vms_script_ajax.nonce,
				club_id: clubId,
			},
			dataType: 'json',
			success: function (response) {
				if (response.success) {
					// Remove row from table
					$(`tr[data-club-id="${clubId}"]`).fadeOut(300, function () {
						$(this).remove();

						// Re-number rows
						$('#clubs-table-body tr').each(function (index) {
							$(this)
								.find('td:first p')
								.text(index + 1);
						});

						// Show "no clubs" row if table is empty
						if ($('#clubs-table-body tr').length === 0) {
							$('#clubs-table-body').append(
								'<tr id="no-clubs-row"><td colspan="5" class="px-4 py-4 text-center text-gray-600 dark:text-white">No clubs found.</td></tr>'
							);
						}
					});

					// Close confirm modal
					$('#delete-confirm-modal-overlay').fadeOut(
						300,
						function () {
							$(this).remove();
						}
					);

					// Show success message
					showSuccessMessage(
						response.data.messages?.[0] ||
							'Club deleted successfully'
					);
				} else {
					showErrorClubModal(
						response.data?.messages || ['Failed to delete club']
					);
				}
			},
			error: function () {
				showErrorClubModal(['Failed to delete club']);
			},
			complete: function () {
				$('#confirm-delete-btn').prop('disabled', false).text('Delete');
			},
		});
	});

	// Helper function to show error modal
	function showErrorClubModal(messages) {
		const errorMessageHtml = messages
			.map(
				(msg) =>
					`<p class="text-lg font-medium text-gray-700 dark:text-white">${msg}</p>`
			)
			.join('');
		const errorModal = `
            <div id="club-error-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                    <div class="check_mark mx-auto mb-4">
                        <div class="sa-icon sa-error animate">
                            <span class="sa-line sa-left animateXLeft"></span>
                            <span class="sa-line sa-right animateXRight"></span>
                            <div class="sa-placeholder"></div>
                        </div>
                    </div>
                    ${errorMessageHtml}
                    <button id="ok-error-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">OK</button>
                </div>
            </div>
        `;
		$('body').append(errorModal);

		$(document)
			.off('click', '#ok-error-btn')
			.on('click', '#ok-error-btn', function (e) {
				e.preventDefault();
				$('#club-error-modal-overlay').fadeOut(300, function () {
					$(this).remove();
				});
			});
	}

	// Helper function to show success message
	function showSuccessMessage(message) {
		const successToast = `
            <div id="success-toast" class="fixed top-4 right-4 z-[999999] bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                <p class="text-sm font-medium">${message}</p>
            </div>
        `;
		$('body').append(successToast);

		setTimeout(function () {
			$('#success-toast').fadeOut(300, function () {
				$(this).remove();
			});
		}, 3000);
	}

	// Employee FORM
	$('#employee-form').on('submit', function (e) {
		e.preventDefault();

		// Show loading indicator
		$('#submit-employee-form')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Registering...'
			);

		// Clear previous messages and modals
		$('.alert-message').remove();
		$('#success-modal-overlay, #employee-error-modal-overlay').remove();

		// Collect form data
		var formData = new FormData(this);
		formData.append('action', 'employee_registration');
		formData.append('nonce', vms_script_ajax.nonce);

		// AJAX request
		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function (response) {
				if (response.success && response.data.employeeData) {
					const employee = response.data.employeeData;

					// Format the role name for display
					const roleNames = {
						general_manager: 'General Manager',
						gate: 'Gate',
						reception: 'Reception',
					};

					// Status classes for active/inactive
					const statusClasses = {
						pending:
							'bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500',
						active: 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
						suspended:
							'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
						banned: 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
						inactive:
							'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
					};

					// Get the current row count for numbering
					const rowCount = $('tbody tr').length + 1;

					// Build new table row matching the PHP structure
					const newRow = `
                <tr>
                    <td class="px-5 py-4 sm:px-6">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">${rowCount}</p>
                    </td>                   
					<td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <div class="flex items-center gap-3">                               
                            	<span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                    ${roleNames[employee.user_role] || employee.user_role || 'N/A'}
                                </span>
							</div>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">${employee.first_name || 'N/A'}</p>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">${employee.last_name || 'N/A'}</p>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">${employee.email || 'N/A'}</p>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">${employee.phone_number || 'N/A'}</p>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <span class="inline-flex items-center justify-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium ${statusClasses[employee.registration_status] || statusClasses.active}">
                                ${employee.registration_status ? employee.registration_status.charAt(0).toUpperCase() + employee.registration_status.slice(1) : 'Active'}
                            </span>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <form action="${window.location.origin}/employee-details" method="get">
                            <input type="hidden" name="user_id" value="${employee.id || employee.user_id}">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700"
                                data-user-id="<?php echo $user_id; ?>">
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
            	`;

					// Remove "no employees" row if it exists
					$('#no-employees-row').remove();
					$('tbody tr:contains("No employees found.")').remove();

					// Prepend new row to table
					$('tbody').prepend(newRow);

					// Re-number all rows
					$('tbody tr').each(function (index) {
						$(this)
							.find('td:first p')
							.text(index + 1);
					});

					// Show success modal
					const message =
						response.data.messages?.[0] ||
						'Employee registered successfully';
					const successModal = `
                <div id="success-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                    <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                        <div class="check_mark mx-auto mb-4">
                            <div class="sa-icon sa-success animate">
                                <span class="sa-line sa-tip animateSuccessTip"></span>
                                <span class="sa-line sa-long animateSuccessLong"></span>
                                <div class="sa-placeholder"></div>
                                <div class="sa-fix"></div>
                            </div>
                        </div>
                        <p class="text-lg font-medium text-gray-700 dark:text-white">${message}</p>
                        <button id="ok-success-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">OK</button>
                    </div>
                </div>
            	`;
					$('body').append(successModal);

					// Handle success modal close
					$(document)
						.off('click', '#ok-success-btn')
						.on('click', '#ok-success-btn', function (e) {
							e.preventDefault();
							$('#success-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
									window.dispatchEvent(
										new Event('close-employee-modal')
									);
									$('#employee-form')[0].reset();
									// Optionally reload the page
									// window.location.reload();
								}
							);
						});
				} else {
					// Show error modal
					const errorMessages = response.data?.messages || [
						'An error occurred during employee registration',
					];
					const errorMessageHtml = errorMessages
						.map(
							(msg) =>
								`<p class="text-lg font-medium text-gray-700 dark:text-white">${msg}</p>`
						)
						.join('');

					const errorModal = `
                <div id="employee-error-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                    <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                        <div class="check_mark mx-auto mb-4">
                            <div class="sa-icon sa-error animate">
                                <span class="sa-line sa-left animateXLeft"></span>
                                <span class="sa-line sa-right animateXRight"></span>
                                <div class="sa-placeholder"></div>
                            </div>
                        </div>
                        ${errorMessageHtml}
                        <button id="ok-error-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">OK</button>
                    </div>
                </div>
            	`;
					$('body').append(errorModal);

					$(document)
						.off('click', '#ok-error-btn')
						.on('click', '#ok-error-btn', function (e) {
							e.preventDefault();
							$('#employee-error-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
								}
							);
						});
				}
			},
			error: function (xhr, status, error) {
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;
				const errorModal = `
            <div id="employee-error-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                    <div class="check_mark mx-auto mb-4">
                        <div class="sa-icon sa-error animate">
                            <span class="sa-line sa-left animateXLeft"></span>
                            <span class="sa-line sa-right animateXRight"></span>
                            <div class="sa-placeholder"></div>
                        </div>
                    </div>
                    <p class="text-lg font-medium text-gray-700 dark:text-white">${errorMessage}</p>
                    <button id="ok-error-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">OK</button>
                </div>
            </div>
        	`;
				$('body').append(errorModal);

				$(document)
					.off('click', '#ok-error-btn')
					.on('click', '#ok-error-btn', function (e) {
						e.preventDefault();
						$('#employee-error-modal-overlay').fadeOut(
							300,
							function () {
								$(this).remove();
							}
						);
					});
			},
			complete: function () {
				$('#submit-employee-form')
					.prop('disabled', false)
					.text('Create Employee');
			},
		});
	});

	// Guest FORM
	$('#courtesy-guest-form').on('submit', function (e) {
		e.preventDefault();

		// Show loading indicator
		$('#submit-courtesy-guest-form')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Registering...'
			);

		// Clear previous messages and modals
		$('.alert-message').remove();
		$('#success-modal-overlay, #guest-error-modal-overlay').remove();

		// Collect form data
		var formData = new FormData(this);
		formData.append('action', 'courtesy_guest_registration');
		formData.append('nonce', vms_script_ajax.nonce);

		// Action buttons generator
		const actionButtons = (guest) => {
			if (!guest) return '';

			const today = new Date().toISOString().split('T')[0];
			const normalizedVisitDate = guest.visit_date
				? guest.visit_date.substring(0, 10)
				: null;

			const isButtonDisabled =
				!normalizedVisitDate ||
				!guest.status ||
				!/^\d{4}-\d{2}-\d{2}$/.test(normalizedVisitDate) ||
				guest.status !== 'approved';

			const isFuture = normalizedVisitDate > today;
			const isPast = normalizedVisitDate < today;
			const isToday = normalizedVisitDate === today;

			const isMissed = isPast && !guest.sign_in_time;
			const isScheduled = isFuture;
			const isCompleted = guest.sign_in_time && guest.sign_out_time;

			const baseButtonClasses =
				'inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg whitespace-nowrap shadow-theme-xs';
			const disabledClasses =
				'bg-brand-500 opacity-50 cursor-not-allowed';
			const signInEnabledClasses =
				'bg-brand-500 cursor-pointer hover:bg-brand-600';
			const signOutEnabledClasses =
				'bg-purple-500 cursor-pointer hover:bg-purple-600';

			if (isMissed) {
				return `<span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-warning-600 bg-warning-50 rounded-lg dark:bg-warning-500/15 dark:text-orange-500">Missed</span>`;
			}

			if (isScheduled) {
				return `<span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-light-500 bg-blue-light-50 rounded-lg dark:bg-blue-light-500/15 dark:text-blue-light-500">Scheduled</span>`;
			}

			if (isToday) {
				if (isCompleted) {
					const signInTime = new Date(
						guest.sign_in_time
					).toLocaleTimeString([], {
						hour: 'numeric',
						minute: '2-digit',
					});
					const signOutTime = new Date(
						guest.sign_out_time
					).toLocaleTimeString([], {
						hour: 'numeric',
						minute: '2-digit',
					});
					return `<div class="flex flex-col items-center justify-center text-xs px-4"><span class="text-green-600 dark:text-green-400">${signInTime}</span><span class="text-red-600 dark:text-red-400">${signOutTime}</span></div>`;
				} else if (!guest.sign_in_time) {
					return `<button id="sign-in-button-${guest.id}" class="${baseButtonClasses} ${isButtonDisabled ? disabledClasses : signInEnabledClasses}" data-visit-id="${guest.visit_id}" ${isButtonDisabled ? 'disabled' : ''}>Sign In</button>`;
				} else if (!guest.sign_out_time) {
					return `<button id="sign-out-button-${guest.id}" class="${baseButtonClasses} ${isButtonDisabled ? disabledClasses : signOutEnabledClasses}" data-visit-id="${guest.visit_id}" ${isButtonDisabled ? 'disabled' : ''}>Sign Out</button>`;
				}
			}

			return '';
		};

		// AJAX request
		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function (response) {
				if (response.success && response.data.guestData) {
					const guest = response.data.guestData;

					console.log(guest);

					// Format visit date
					let formattedDate = 'N/A';
					if (guest.visit_date) {
						const visitDate = new Date(guest.visit_date);
						if (!isNaN(visitDate)) {
							formattedDate = visitDate.toLocaleDateString(
								'en-US',
								{
									month: 'short',
									day: 'numeric',
									year: 'numeric',
								}
							);
						}
					}

					// Status classes
					const statusClasses = {
						approved:
							'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
						unapproved:
							'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
						suspended:
							'bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500',
						banned: 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
						cancelled:
							'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80',
					};

					// Build row
					const newRow = `
                    <tr>
                        <td class="px-3 py-4 sm:px-6"><p class="text-gray-500 text-theme-sm dark:text-gray-400">${$('tbody tr').length + 1}</p></td>
                        <td class="px-3 py-4 sm:px-6"><p class="text-gray-800 text-theme-sm dark:text-white/90">${guest.first_name || 'N/A'}</p></td>
                        <td class="px-3 py-4 sm:px-6"><p class="text-gray-800 text-theme-sm dark:text-white/90">${guest.last_name || 'N/A'}</p></td>
                        <td class="px-3 py-4 sm:px-6"><span class="inline-flex items-center justify-center px-2.5 gap-1 py-0.5 text-sm font-medium capitalize rounded-full ${statusClasses[guest.status] || ''}">${guest.status ? guest.status.charAt(0).toUpperCase() + guest.status.slice(1) : 'N/A'}</span></td>
                        <td class="px-3 py-4 sm:px-6"><p class="text-gray-500 text-theme-sm dark:text-gray-400">${guest.id_number || 'N/A'}</p></td>
                        <td class="px-3 py-4 sm:px-6"><span class="inline-flex items-center justify-center px-2.5 gap-1 py-0.5 text-sm font-medium capitalize rounded-full bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500">${guest.courtesy}</span></td>
                        <td class="px-3 py-4 sm:px-6"><p class="text-gray-500 text-theme-sm dark:text-gray-400">${formattedDate}</p></td>
                        <td class="px-3 py-4 sm:px-6">
                            <div class="flex items-center gap-2">
                                <button id="edit-guest-button-${guest.id}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
								<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
								Edit
								</button>
                                ${actionButtons(guest)}
                            </div>
                        </td>
                    </tr>
                	`;

					// Remove "no guests" row
					$('#no-guests-row').remove();

					// Prepend row
					$('tbody').prepend(newRow);

					// Re-number rows
					$('tbody tr').each(function (index) {
						$(this)
							.find('td:first p')
							.text(index + 1);
					});

					// Show success modal
					const message =
						response.data.messages?.[0] ||
						'Guest registered successfully';
					const successModal = `
                    <div id="success-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                        <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                            <div class="check_mark mx-auto mb-4">
                                <div class="sa-icon sa-success animate">
                                    <span class="sa-line sa-tip animateSuccessTip"></span>
                                    <span class="sa-line sa-long animateSuccessLong"></span>
                                    <div class="sa-placeholder"></div>
                                    <div class="sa-fix"></div>
                                </div>
                            </div>
                            <p class="text-lg font-medium text-gray-700 dark:text-white">${message}</p>
                            <button id="ok-success-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">OK</button>
                        </div>
                    </div>
                	`;
					$('body').append(successModal);

					$(document)
						.off('click', '#ok-success-btn')
						.on('click', '#ok-success-btn', function (e) {
							e.preventDefault();
							$('#success-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
									window.dispatchEvent(
										new Event('close-courtesy-guest-modal')
									);
									$('#guest-form')[0].reset();

									// window.location.reload();
								}
							);
						});
				} else {
					// Show error modal
					const errorMessages = response.data?.messages || [
						'An error occurred during guest registration',
					];
					const errorMessageHtml = errorMessages
						.map(
							(msg) =>
								`<p class="text-lg font-medium text-gray-700 dark:text-white">${msg}</p>`
						)
						.join('');
					const errorModal = `
                    <div id="guest-error-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                        <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                            <div class="check_mark mx-auto mb-4">
                                <div class="sa-icon sa-error animate">
                                    <span class="sa-line sa-left animateXLeft"></span>
                                    <span class="sa-line sa-right animateXRight"></span>
                                    <div class="sa-placeholder"></div>
                                </div>
                            </div>
                            ${errorMessageHtml}
                            <button id="ok-error-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">OK</button>
                        </div>
                    </div>
                `;
					$('body').append(errorModal);
					$(document)
						.off('click', '#ok-error-btn')
						.on('click', '#ok-error-btn', function (e) {
							e.preventDefault();
							$('#guest-error-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
								}
							);
						});
				}
			},
			error: function (xhr, status, error) {
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;
				const errorModal = `
                <div id="guest-error-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                    <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                        <div class="check_mark mx-auto mb-4">
                            <div class="sa-icon sa-error animate">
                                <span class="sa-line sa-left animateXLeft"></span>
                                <span class="sa-line sa-right animateXRight"></span>
                                <div class="sa-placeholder"></div>
                            </div>
                        </div>
                        <p class="text-lg font-medium text-gray-700 dark:text-white">${errorMessage}</p>
                        <button id="ok-error-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">OK</button>
                    </div>
                </div>
            `;
				$('body').append(errorModal);
				$(document)
					.off('click', '#ok-error-btn')
					.on('click', '#ok-error-btn', function (e) {
						e.preventDefault();
						$('#guest-error-modal-overlay').fadeOut(
							300,
							function () {
								$(this).remove();
							}
						);
					});
			},
			complete: function () {
				$('#submit-courtesy-guest-form')
					.prop('disabled', false)
					.text('Create Guest');
			},
		});
	});

	// Member Update FORM
	$('#member-update-form').on('submit', function (e) {
		e.preventDefault();

		// Show loading indicator
		$('#update-member-btn')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Updating...'
			);

		// Clear previous messages and modals
		$('.alert-message').remove();
		$('#success-modal-overlay, #member-error-modal-overlay').remove();

		// Collect form data
		var formData = new FormData(this);
		formData.append('action', 'update_member');
		formData.append('nonce', vms_script_ajax.nonce);

		// AJAX request
		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function (response) {
				if (response.success) {
					// Show success message
					const message =
						response.data.message || 'Member updated successfully';

					// Create and show success animation modal
					const successModal = `
                        <div id="success-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                            <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                                <div class="check_mark mx-auto mb-4">
                                    <div class="sa-icon sa-success animate">
                                        <span class="sa-line sa-tip animateSuccessTip"></span>
                                        <span class="sa-line sa-long animateSuccessLong"></span>
                                        <div class="sa-placeholder"></div>
                                        <div class="sa-fix"></div>
                                    </div>
                                </div>
                                <p class="text-lg font-medium text-gray-700 dark:text-white">${message}</p>
                                <button id="ok-success-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                                    OK
                                </button>
                            </div>
                        </div>
                    	`;

					// Inject modal into body
					$('body').append(successModal);

					// Handle OK button click
					$(document)
						.off('click', '#ok-success-btn')
						.on('click', '#ok-success-btn', function (e) {
							e.preventDefault();
							$('#success-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
								}
							);
						});
				} else {
					// Show error messages in a single modal
					const errorMessages = response.data.messages || [
						'An error occurred during member update',
					];
					const errorMessageHtml = errorMessages
						.map(
							(msg) =>
								`<p class="text-lg font-medium text-gray-700 dark:text-white">${msg}</p>`
						)
						.join('');

					// Create and show error animation modal
					const errorModal = `
                        <div id="member-error-modal-overlay"
                            class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                            <div
                                class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                                <div class="check_mark mx-auto mb-4">
                                    <div class="sa-icon sa-error animate">
                                        <span class="sa-line sa-left animateXLeft"></span>
                                        <span class="sa-line sa-right animateXRight"></span>
                                        <div class="sa-placeholder"></div>
                                    </div>
                                </div>
                                ${errorMessageHtml}
                                <button id="ok-error-btn" type="button"
                                    class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">
                                    OK
                                </button>
                            </div>
                        </div>
                    	`;

					// Inject modal into body
					$('body').append(errorModal);

					// Handle OK button click
					$(document)
						.off('click', '#ok-error-btn')
						.on('click', '#ok-error-btn', function (e) {
							e.preventDefault();
							$('#member-error-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
								}
							);
						});
				}
			},
			error: function (xhr, status, error) {
				// Show error message in a modal
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;

				// Create and show error animation modal
				const errorModal = `
                    <div id="member-error-modal-overlay"
                        class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                        <div
                            class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                            <div class="check_mark mx-auto mb-4">
                                <div class="sa-icon sa-error animate">
                                    <span class="sa-line sa-left animateXLeft"></span>
                                    <span class="sa-line sa-right animateXRight"></span>
                                    <div class="sa-placeholder"></div>
                                </div>
                            </div>
                            <p class="text-lg font-medium text-gray-700 dark:text-white">${errorMessage}</p>
                            <button id="ok-error-btn" type="button"
                                class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">
                                OK
                            </button>
                        </div>
                    </div>
                	`;

				// Inject modal into body
				$('body').append(errorModal);

				// Handle OK button click
				$(document)
					.off('click', '#ok-error-btn')
					.on('click', '#ok-error-btn', function (e) {
						e.preventDefault();
						$('#member-error-modal-overlay').fadeOut(
							300,
							function () {
								$(this).remove();
							}
						);
					});
			},
			complete: function () {
				// Reset button
				$('#update-member-btn')
					.prop('disabled', false)
					.text('Update Member');
			},
		});
	});

	// Delete Member Button
	$('#delete-member-btn').on('click', function (e) {
		e.preventDefault();

		const memberName = $(this).data('member-name') || 'this member';

		// Show confirmation modal
		const confirmModal = `
			<div id="delete-member-confirm-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
				<div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
					<div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
						<svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
						</svg>
					</div>
					<h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">Delete Member</h3>
					<p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Are you sure you want to delete "${memberName}"? This action is irreversible.</p>
					<div class="flex gap-3">
						<button id="cancel-delete-member-btn" type="button" class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Cancel</button>
						<button id="confirm-delete-member-btn" type="button" class="flex-1 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600">Delete</button>
					</div>
				</div>
			</div>
		`;
		$('body').append(confirmModal);

		// Handle confirmation modal buttons
		$(document).on('click', '#cancel-delete-member-btn', function (e) {
			e.preventDefault();
			$('#delete-member-confirm-modal-overlay').fadeOut(300, function () {
				$(this).remove();
			});
		});

		$(document).on('click', '#confirm-delete-member-btn', function (e) {
			e.preventDefault();

			// Show loading state
			$(this)
				.prop('disabled', true)
				.html(
					'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Deleting...'
				);

			// Get member ID from the form
			const memberId = $('input[name="member_id"]').val();

			// AJAX request to delete member
			$.ajax({
				url: vms_script_ajax.ajaxurl,
				type: 'POST',
				data: {
					action: 'delete_member',
					member_id: memberId,
					nonce: vms_script_ajax.nonce,
				},
				dataType: 'json',
				success: function (response) {
					// Close confirmation modal
					$('#delete-member-confirm-modal-overlay').remove();

					if (response.success) {
						// Show success modal
						const message =
							response.data.message ||
							'Member deleted successfully';
						const successModal = `
						<div id="success-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
							<div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
								<div class="check_mark mx-auto mb-4">
									<div class="sa-icon sa-success animate">
										<span class="sa-line sa-tip animateSuccessTip"></span>
										<span class="sa-line sa-long animateSuccessLong"></span>
										<div class="sa-placeholder"></div>
										<div class="sa-fix"></div>
									</div>
								</div>
								<p class="text-lg font-medium text-gray-700 dark:text-white">${message}</p>
								<button id="ok-success-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
									OK
								</button>
							</div>
						</div>
						`;
						$('body').append(successModal);

						// Redirect after success
						$(document).on(
							'click',
							'#ok-success-btn',
							function (e) {
								e.preventDefault();
								window.location.href = '/members/'; // Adjust URL as needed
							}
						);
					} else {
						// Show error modal
						const errorMessages = response.data.messages || [
							'Failed to delete member',
						];
						const errorMessageHtml = errorMessages
							.map(
								(msg) =>
									`<p class="text-lg font-medium text-gray-700 dark:text-white">${msg}</p>`
							)
							.join('');

						const errorModal = `
						<div id="member-error-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
							<div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
								<div class="check_mark mx-auto mb-4">
									<div class="sa-icon sa-error animate">
										<span class="sa-line sa-left animateXLeft"></span>
										<span class="sa-line sa-right animateXRight"></span>
										<div class="sa-placeholder"></div>
									</div>
								</div>
								${errorMessageHtml}
								<button id="ok-error-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">
									OK
								</button>
							</div>
						</div>
						`;
						$('body').append(errorModal);

						$(document).on('click', '#ok-error-btn', function (e) {
							e.preventDefault();
							$('#member-error-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
								}
							);
						});
					}
				},
				error: function (xhr, status, error) {
					// Close confirmation modal
					$('#delete-member-confirm-modal-overlay').remove();

					const errorMessage =
						xhr.responseJSON?.data?.messages?.join('<br>') ||
						'An error occurred: ' + error;
					const errorModal = `
					<div id="member-error-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
						<div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
							<div class="check_mark mx-auto mb-4">
								<div class="sa-icon sa-error animate">
									<span class="sa-line sa-left animateXLeft"></span>
									<span class="sa-line sa-right animateXRight"></span>
									<div class="sa-placeholder"></div>
								</div>
							</div>
							<p class="text-lg font-medium text-gray-700 dark:text-white">${errorMessage}</p>
							<button id="ok-error-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">
								OK
							</button>
						</div>
					</div>
					`;
					$('body').append(errorModal);

					$(document).on('click', '#ok-error-btn', function (e) {
						e.preventDefault();
						$('#member-error-modal-overlay').fadeOut(
							300,
							function () {
								$(this).remove();
							}
						);
					});
				},
			});
		});
	});

	// Guest Update FORM
	$('#guest-update-form').on('submit', function (e) {
		e.preventDefault();

		// Show loading indicator
		$('#update-guest-btn')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Updating...'
			);

		// Clear previous messages and modals
		$('.alert-message').remove();
		$('#success-modal-overlay, #guest-error-modal-overlay').remove();

		// Collect form data
		var formData = new FormData(this);
		formData.append('action', 'update_guest');
		formData.append('nonce', vms_script_ajax.nonce);

		// AJAX request
		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function (response) {
				if (response.success) {
					// Show success message
					const message =
						response.data.message || 'Guest updated successfully';

					// Create and show success animation modal
					const successModal = `
                            <div id="success-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                                <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                                    <div class="check_mark mx-auto mb-4">
                                        <div class="sa-icon sa-success animate">
                                            <span class="sa-line sa-tip animateSuccessTip"></span>
                                            <span class="sa-line sa-long animateSuccessLong"></span>
                                            <div class="sa-placeholder"></div>
                                            <div class="sa-fix"></div>
                                        </div>
                                    </div>
                                    <p class="text-lg font-medium text-gray-700 dark:text-white">${message}</p>
                                    <button id="ok-success-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                                        OK
                                    </button>
                                </div>
                            </div>
                        `;

					// Inject modal into body
					$('body').append(successModal);

					// Handle OK button click
					$(document)
						.off('click', '#ok-success-btn')
						.on('click', '#ok-success-btn', function (e) {
							e.preventDefault();
							$('#success-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
								}
							);
						});
				} else {
					// Show error messages in a single modal
					const errorMessages = response.data.messages || [
						'An error occurred during guest update',
					];
					const errorMessageHtml = errorMessages
						.map(
							(msg) =>
								`<p class="text-lg font-medium text-gray-700 dark:text-white">${msg}</p>`
						)
						.join('');

					// Create and show error animation modal
					const errorModal = `
                            <div id="guest-error-modal-overlay"
                                class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                                <div
                                    class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                                    <div class="check_mark mx-auto mb-4">
                                        <div class="sa-icon sa-error animate">
                                            <span class="sa-line sa-left animateXLeft"></span>
                                            <span class="sa-line sa-right animateXRight"></span>
                                            <div class="sa-placeholder"></div>
                                        </div>
                                    </div>
                                    ${errorMessageHtml}
                                    <button id="ok-error-btn" type="button"
                                        class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">
                                        OK
                                    </button>
                                </div>
                            </div>
                        `;

					// Inject modal into body
					$('body').append(errorModal);

					// Handle OK button click
					$(document)
						.off('click', '#ok-error-btn')
						.on('click', '#ok-error-btn', function (e) {
							e.preventDefault();
							$('#guest-error-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
								}
							);
						});
				}
			},
			error: function (xhr, status, error) {
				// Show error message in a modal
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;

				// Create and show error animation modal
				const errorModal = `
                        <div id="guest-error-modal-overlay"
                            class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                            <div
                                class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                                <div class="check_mark mx-auto mb-4">
                                    <div class="sa-icon sa-error animate">
                                        <span class="sa-line sa-left animateXLeft"></span>
                                        <span class="sa-line sa-right animateXRight"></span>
                                        <div class="sa-placeholder"></div>
                                    </div>
                                </div>
                                <p class="text-lg font-medium text-gray-700 dark:text-white">${errorMessage}</p>
                                <button id="ok-error-btn" type="button"
                                    class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">
                                    OK
                                </button>
                            </div>
                        </div>
                    `;

				// Inject modal into body
				$('body').append(errorModal);

				// Handle OK button click
				$(document)
					.off('click', '#ok-error-btn')
					.on('click', '#ok-error-btn', function (e) {
						e.preventDefault();
						$('#guest-error-modal-overlay').fadeOut(
							300,
							function () {
								$(this).remove();
							}
						);
					});
			},
			complete: function () {
				// Reset button
				$('#update-guest-btn')
					.prop('disabled', false)
					.text('Update Guest');
			},
		});
	});

	// Delete Guest Button
	$('#delete-guest-btn').on('click', function (e) {
		e.preventDefault();

		const guestName = $(this).data('guest-name') || 'this guest';

		// Show confirmation modal
		const confirmModal = `
		<div id="delete-guest-confirm-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
			<div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
				<div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
					<svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
					</svg>
				</div>
				<h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">Delete Guest</h3>
				<p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Are you sure you want to delete "${guestName}"? This action is irreversible.</p>
				<div class="flex gap-3">
					<button id="cancel-delete-guest-btn" type="button" class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Cancel</button>
					<button id="confirm-delete-guest-btn" type="button" class="flex-1 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600">Delete</button>
				</div>
			</div>
		</div>
	`;
		$('body').append(confirmModal);
	});

	// Cancel Delete Guest
	$(document).on('click', '#cancel-delete-guest-btn', function () {
		$('#delete-guest-confirm-modal-overlay').fadeOut(300, function () {
			$(this).remove();
		});
	});

	// Confirm Delete Guest
	$(document).on('click', '#confirm-delete-guest-btn', function () {
		// Show loading
		$(this)
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Deleting...'
			);

		// Clear previous messages and modals
		$('.alert-message').remove();
		$('#success-modal-overlay, #guest-error-modal-overlay').remove();

		// Collect form data
		var formData = new FormData();
		formData.append('action', 'delete_guest');
		formData.append('nonce', vms_script_ajax.nonce);
		formData.append('guest_id', $('input[name="guest_id"]').val());

		// AJAX request
		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function (response) {
				if (response.success) {
					// Show success message
					const message =
						response.data.message || 'Guest deleted successfully';

					// Close confirm modal first
					$('#delete-guest-confirm-modal-overlay').fadeOut(
						300,
						function () {
							$(this).remove();
						}
					);

					// Create and show success animation modal
					const successModal = `
					<div id="success-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
						<div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
							<div class="check_mark mx-auto mb-4">
								<div class="sa-icon sa-success animate">
									<span class="sa-line sa-tip animateSuccessTip"></span>
									<span class="sa-line sa-long animateSuccessLong"></span>
									<div class="sa-placeholder"></div>
									<div class="sa-fix"></div>
								</div>
							</div>
							<p class="text-lg font-medium text-gray-700 dark:text-white">${message}</p>
							<button id="ok-success-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
								OK
							</button>
						</div>
					</div>
				`;

					// Inject modal into body
					$('body').append(successModal);

					// Handle OK button click - redirect after deletion
					$(document)
						.off('click', '#ok-success-btn')
						.on('click', '#ok-success-btn', function (e) {
							e.preventDefault();
							$('#success-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
									// Redirect to guests list page
									window.location.href = '/guests';
								}
							);
						});
				} else {
					// Close confirm modal
					$('#delete-guest-confirm-modal-overlay').fadeOut(
						300,
						function () {
							$(this).remove();
						}
					);

					// Show error messages in a single modal
					const errorMessages = response.data.messages || [
						'An error occurred during guest deletion',
					];
					const errorMessageHtml = errorMessages
						.map(
							(msg) =>
								`<p class="text-lg font-medium text-gray-700 dark:text-white">${msg}</p>`
						)
						.join('');

					// Create and show error animation modal
					const errorModal = `
					<div id="guest-error-modal-overlay"
						class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
						<div
							class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
							<div class="check_mark mx-auto mb-4">
								<div class="sa-icon sa-error animate">
									<span class="sa-line sa-left animateXLeft"></span>
									<span class="sa-line sa-right animateXRight"></span>
									<div class="sa-placeholder"></div>
								</div>
							</div>
							${errorMessageHtml}
							<button id="ok-error-btn" type="button"
								class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">
								OK
							</button>
						</div>
					</div>
				`;

					// Inject modal into body
					$('body').append(errorModal);

					// Handle OK button click
					$(document)
						.off('click', '#ok-error-btn')
						.on('click', '#ok-error-btn', function (e) {
							e.preventDefault();
							$('#guest-error-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
								}
							);
						});
				}
			},
			error: function (xhr, status, error) {
				// Close confirm modal
				$('#delete-guest-confirm-modal-overlay').fadeOut(
					300,
					function () {
						$(this).remove();
					}
				);

				// Show error message in a modal
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;

				// Create and show error animation modal
				const errorModal = `
				<div id="guest-error-modal-overlay"
					class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
					<div
						class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
						<div class="check_mark mx-auto mb-4">
							<div class="sa-icon sa-error animate">
								<span class="sa-line sa-left animateXLeft"></span>
								<span class="sa-line sa-right animateXRight"></span>
								<div class="sa-placeholder"></div>
							</div>
						</div>
						<p class="text-lg font-medium text-gray-700 dark:text-white">${errorMessage}</p>
						<button id="ok-error-btn" type="button"
							class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">
							OK
						</button>
					</div>
				</div>
			`;

				// Inject modal into body
				$('body').append(errorModal);

				// Handle OK button click
				$(document)
					.off('click', '#ok-error-btn')
					.on('click', '#ok-error-btn', function (e) {
						e.preventDefault();
						$('#guest-error-modal-overlay').fadeOut(
							300,
							function () {
								$(this).remove();
							}
						);
					});
			},
			complete: function () {
				// Reset button
				$('#delete-guest-btn')
					.prop('disabled', false)
					.text('Delete Guest');
			},
		});
	});

	// VISIT FORM
	$('#visit-form').on('submit', function (e) {
		e.preventDefault();

		// Show loading indicator
		$('#submit-visit-form')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Registering...'
			);

		// Clear previous messages and modals
		$('#alert-message').remove();
		$('#success-modal-overlay, #visit-error-modal-overlay').remove();

		// Collect form data
		var formData = new FormData(this);
		formData.append('action', 'register_visit');
		formData.append('nonce', vms_script_ajax.nonce);

		// AJAX request
		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function (response) {
				if (response.success) {
					// Success modal
					const message =
						response.data.messages?.[0] ||
						'Visit registered successfully';
					const successModal = `
					<div id="success-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
						<div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
							<div class="check_mark mx-auto mb-4">
								<div class="sa-icon sa-success animate">
									<span class="sa-line sa-tip animateSuccessTip"></span>
									<span class="sa-line sa-long animateSuccessLong"></span>
									<div class="sa-placeholder"></div>
									<div class="sa-fix"></div>
								</div>
							</div>
							<p class="text-lg font-medium text-gray-700 dark:text-white">${message}</p>
							<button id="ok-success-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
								OK
							</button>
						</div>
					</div>
				`;
					$('body').append(successModal);

					// Close success modal
					$(document)
						.off('click', '#ok-success-btn')
						.on('click', '#ok-success-btn', function (e) {
							e.preventDefault();
							$('#success-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
									window.dispatchEvent(
										new Event('close-visit-modal')
									);
									$('#visit-form')[0].reset();

									// Reload same URL
									window.location.reload();
								}
							);
						});
				} else {
					// Error messages
					const errorMessages = response.data.messages || [
						'An error occurred during visit registration',
					];
					const errorMessageHtml = errorMessages
						.map(
							(msg) =>
								`<p class="text-lg font-medium text-gray-700 dark:text-white">${msg}</p>`
						)
						.join('');

					const errorModal = `
					<div id="visit-error-modal-overlay"
						class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
						<div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
							<div class="check_mark mx-auto mb-4">
								<div class="sa-icon sa-error animate">
									<span class="sa-line sa-left animateXLeft"></span>
									<span class="sa-line sa-right animateXRight"></span>
									<div class="sa-placeholder"></div>
								</div>
							</div>
							${errorMessageHtml}
							<button id="ok-error-btn" type="button"
								class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">
								OK
							</button>
						</div>
					</div>
				`;
					$('body').append(errorModal);

					// Close error modal
					$(document)
						.off('click', '#ok-error-btn')
						.on('click', '#ok-error-btn', function (e) {
							e.preventDefault();
							$('#visit-error-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
								}
							);
						});
				}
			},
			error: function (xhr, status, error) {
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;

				const errorModal = `
				<div id="visit-error-modal-overlay"
					class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
					<div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
						<div class="check_mark mx-auto mb-4">
							<div class="sa-icon sa-error animate">
								<span class="sa-line sa-left animateXLeft"></span>
								<span class="sa-line sa-right animateXRight"></span>
								<div class="sa-placeholder"></div>
							</div>
						</div>
						<p class="text-lg font-medium text-gray-700 dark:text-white">${errorMessage}</p>
						<button id="ok-error-btn" type="button"
							class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">
							OK
						</button>
					</div>
				</div>
			`;
				$('body').append(errorModal);

				// Close error modal
				$(document)
					.off('click', '#ok-error-btn')
					.on('click', '#ok-error-btn', function (e) {
						e.preventDefault();
						$('#visit-error-modal-overlay').fadeOut(
							300,
							function () {
								$(this).remove();
							}
						);
					});
			},
			complete: function () {
				$('#submit-visit-form')
					.prop('disabled', false)
					.text('Register Visit');
			},
		});
	});

	// RECIPROCATING MEMBER VISIT FORM
	$('#recip-visit-form').on('submit', function (e) {
		e.preventDefault();

		// Show loading indicator
		$('#submit-visit-form')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Registering...'
			);

		// Clear previous messages and modals
		$('#alert-message').remove();
		$('#success-modal-overlay, #visit-error-modal-overlay').remove();

		// Collect form data
		var formData = new FormData(this);
		formData.append('action', 'register_reciprocation_member_visit');
		formData.append('nonce', vms_script_ajax.nonce);

		// AJAX request
		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function (response) {
				if (response.success) {
					// Success modal
					const message =
						response.data.messages?.[0] ||
						'Visit registered successfully';
					const successModal = `
                <div id="success-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                    <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                        <div class="check_mark mx-auto mb-4">
                            <div class="sa-icon sa-success animate">
                                <span class="sa-line sa-tip animateSuccessTip"></span>
                                <span class="sa-line sa-long animateSuccessLong"></span>
                                <div class="sa-placeholder"></div>
                                <div class="sa-fix"></div>
                            </div>
                        </div>
                        <p class="text-lg font-medium text-gray-700 dark:text-white">${message}</p>
                        <button id="ok-success-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                            OK
                        </button>
                    </div>
                </div>
            `;
					$('body').append(successModal);

					// Close success modal
					$(document)
						.off('click', '#ok-success-btn')
						.on('click', '#ok-success-btn', function (e) {
							e.preventDefault();
							$('#success-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
									window.dispatchEvent(
										new Event('close-visit-modal')
									);
									$('#recip-visit-form')[0].reset();

									// Reload same URL
									window.location.reload();
								}
							);
						});
				} else {
					// Error messages
					const errorMessages = response.data.messages || [
						'An error occurred during visit registration',
					];
					const errorMessageHtml = errorMessages
						.map(
							(msg) =>
								`<p class="text-lg font-medium text-gray-700 dark:text-white">${msg}</p>`
						)
						.join('');

					const errorModal = `
                <div id="visit-error-modal-overlay"
                    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                    <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                        <div class="check_mark mx-auto mb-4">
                            <div class="sa-icon sa-error animate">
                                <span class="sa-line sa-left animateXLeft"></span>
                                <span class="sa-line sa-right animateXRight"></span>
                                <div class="sa-placeholder"></div>
                            </div>
                        </div>
                        ${errorMessageHtml}
                        <button id="ok-error-btn" type="button"
                            class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">
                            OK
                        </button>
                    </div>
                </div>
            `;
					$('body').append(errorModal);

					// Close error modal
					$(document)
						.off('click', '#ok-error-btn')
						.on('click', '#ok-error-btn', function (e) {
							e.preventDefault();
							$('#visit-error-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
								}
							);
						});
				}
			},
			error: function (xhr, status, error) {
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;

				const errorModal = `
            <div id="visit-error-modal-overlay"
                class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                    <div class="check_mark mx-auto mb-4">
                        <div class="sa-icon sa-error animate">
                            <span class="sa-line sa-left animateXLeft"></span>
                            <span class="sa-line sa-right animateXRight"></span>
                            <div class="sa-placeholder"></div>
                        </div>
                    </div>
                    <p class="text-lg font-medium text-gray-700 dark:text-white">${errorMessage}</p>
                    <button id="ok-error-btn" type="button"
                        class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">
                        OK
                    </button>
                </div>
            </div>
        `;
				$('body').append(errorModal);

				// Close error modal
				$(document)
					.off('click', '#ok-error-btn')
					.on('click', '#ok-error-btn', function (e) {
						e.preventDefault();
						$('#visit-error-modal-overlay').fadeOut(
							300,
							function () {
								$(this).remove();
							}
						);
					});
			},
			complete: function () {
				$('#submit-visit-form')
					.prop('disabled', false)
					.text('Create Visit');
			},
		});
	});

	// Edit Guest Button Handler
	$(document).on('click', '[id^="edit-guest-button-"]', function (e) {
		e.preventDefault();

		// Extract guest ID from button ID
		const guestId = $(this).attr('id').replace('edit-guest-button-', '');

		// Redirect to edit page
		window.location.href =
			vms_script_ajax.home_url +
			'/guest-details/?guest_id=' +
			guestId +
			'&paged=1';
	});

	// Sign In Guest Button Handler
	$(document).on('click', '[id^="sign-in-button-"]', function (e) {
		e.preventDefault();

		const visitId = $(this).data('visit-id');
		const button = $(this);
		const guestName = button
			.closest('tr')
			.find('td:nth-child(2)')
			.text()
			.trim();

		// Show confirmation modal with ID input
		const confirmModal = `
	<div id="sign-in-confirm-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
		<div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
			<div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
				<svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
				</svg>
			</div>
			<h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">Sign In Guest</h3>
			<p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Enter ID Number for "${guestName}" to continue:</p>
			
			<input type="number" id="guest-id-number" placeholder="Enter ID Number" 
				class="mb-4 dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" 
				min="10000" />

			<div class="flex gap-3">
				<button id="cancel-sign-in-btn" type="button" 
					class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
					Cancel
				</button>
				<button id="confirm-sign-in-btn" type="button" 
					class="flex-1 rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed"
					data-visit-id="${visitId}" disabled>
					Sign In
				</button>
			</div>
		</div>
	</div>
	`;

		$('body').append(confirmModal);
	});

	// Enable Sign In button only when ID is valid (>= 5 digits)
	$(document).on('input', '#guest-id-number', function () {
		const idNumber = $(this).val().trim();
		if (idNumber.length >= 5) {
			$('#confirm-sign-in-btn').prop('disabled', false);
		} else {
			$('#confirm-sign-in-btn').prop('disabled', true);
		}
	});

	// Cancel Sign In
	$(document).on('click', '#cancel-sign-in-btn', function () {
		$('#sign-in-confirm-modal-overlay').fadeOut(300, function () {
			$(this).remove();
		});
	});

	// Confirm Sign In
	$(document).on('click', '#confirm-sign-in-btn', function () {
		const visitId = $(this).data('visit-id');
		const idNumber = $('#guest-id-number').val().trim();
		const button = $(`[id^="sign-in-button-"][data-visit-id="${visitId}"]`);

		// Show loading
		$(this)
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Signing In...'
			);

		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: {
				action: 'sign_in_guest',
				visit_id: visitId,
				id_number: idNumber, // NEW
				nonce: vms_script_ajax.nonce,
			},
			dataType: 'json',
			success: function (response) {
				if (response.success && response.data.guestData) {
					const guest = response.data.guestData;

					// Update status badge in the same row
					const row = button.closest('tr');
					const statusCell = row.find('td:nth-child(4) span');

					// keep the "Approved" badge intact
					statusCell.text('Approved');

					// Build Sign Out button
					const newVisitId = guest.visit_id || visitId;
					const signOutBtn = `
					<button id="sign-out-button-${guest.id}" data-visit-id="${newVisitId}"
						class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg cursor-pointer whitespace-nowrap bg-purple-500 shadow-theme-xs hover:bg-purple-600">
						Sign Out
					</button>
					`;

					// Replace the sign in button
					button.replaceWith(signOutBtn);

					// Close confirm modal
					$('#sign-in-confirm-modal-overlay').fadeOut(
						300,
						function () {
							$(this).remove();
						}
					);

					const successMessage =
						response.data.messages?.[0] ||
						'Guest signed in successfully';
					showSuccessModal(successMessage);
				} else {
					const errorMessage =
						response.data?.messages?.join('<br>') ||
						'Error signing in guest';
					showErrorModal(errorMessage);
					// Close modal on error
					$('#sign-in-confirm-modal-overlay').fadeOut(
						300,
						function () {
							$(this).remove();
						}
					);
				}
			},
			error: function (xhr, status, error) {
				console.error('Sign in error:', error);
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;
				showErrorModal(errorMessage);
				// Close modal on error
				$('#sign-in-confirm-modal-overlay').fadeOut(300, function () {
					$(this).remove();
				});
			},
			complete: function () {
				$('#confirm-sign-in-btn')
					.prop('disabled', false)
					.text('Sign In');
			},
		});
	});

	// Sign Out Guest Button Handler
	$(document).on('click', '[id^="sign-out-button-"]', function (e) {
		e.preventDefault();

		const visitId = $(this).data('visit-id');
		const button = $(this);
		const guestName = button
			.closest('tr')
			.find('td:nth-child(2)')
			.text()
			.trim();

		// Show confirmation modal
		const confirmModal = `
		<div id="sign-out-confirm-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
			<div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
				<div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900/30">
					<svg class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
					</svg>
				</div>
				<h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">Sign Out Guest</h3>
				<p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Sign out "${guestName}" from the system?</p>
				<div class="flex gap-3">
					<button id="cancel-sign-out-btn" type="button" class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Cancel</button>
					<button id="confirm-sign-out-btn" type="button" class="flex-1 rounded-lg bg-orange-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-orange-600" data-visit-id="${visitId}">Sign Out</button>
				</div>
			</div>
		</div>
	`;
		$('body').append(confirmModal);
	});

	// Cancel Sign Out
	$(document).on('click', '#cancel-sign-out-btn', function () {
		$('#sign-out-confirm-modal-overlay').fadeOut(300, function () {
			$(this).remove();
		});
	});

	// Confirm Sign Out
	$(document).on('click', '#confirm-sign-out-btn', function () {
		const visitId = $(this).data('visit-id');
		const button = $(
			`[id^="sign-out-button-"][data-visit-id="${visitId}"]`
		);

		// Show loading
		$(this)
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Signing Out...'
			);

		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: {
				action: 'sign_out_guest',
				visit_id: visitId,
				nonce: vms_script_ajax.nonce,
			},
			dataType: 'json',
			success: function (response) {
				if (response.success && response.data.guestData) {
					const guest = response.data.guestData;

					const formatTime = (time) => {
						if (!time) return '';
						const date = new Date(time);
						if (isNaN(date)) return '';
						return date.toLocaleTimeString('en-US', {
							hour: 'numeric',
							minute: '2-digit',
							hour12: true,
						});
					};

					const parentContainer = button.closest(
						'.flex.items-center.gap-2'
					);
					button.remove();

					parentContainer.append(`
				<div class="flex flex-col items-center justify-center text-xs px-4">
					<span class="text-green-600 dark:text-green-400">${formatTime(guest.sign_in_time)}</span>
					<span class="text-red-600 dark:text-red-400">${formatTime(guest.sign_out_time)}</span>
				</div>
				`);

					// Close confirm modal
					$('#sign-out-confirm-modal-overlay').fadeOut(
						300,
						function () {
							$(this).remove();
						}
					);

					const successMessage =
						response.data.messages?.[0] ||
						'Guest signed out successfully';
					showSuccessModal(successMessage);
				} else {
					const errorMessage =
						response.data?.messages?.join('<br>') ||
						'Error signing out guest';
					showErrorModal(errorMessage);
					// Close modal on error
					$('#sign-out-confirm-modal-overlay').fadeOut(
						300,
						function () {
							$(this).remove();
						}
					);
				}
			},
			error: function (xhr, status, error) {
				console.error('Sign out error:', error);
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;
				showErrorModal(errorMessage);
				// Close modal on error
				$('#sign-out-confirm-modal-overlay').fadeOut(300, function () {
					$(this).remove();
				});
			},
			complete: function () {
				$('#confirm-sign-out-btn')
					.prop('disabled', false)
					.text('Sign Out');
			},
		});
	});

	// Handle balance refresh
	$('#vms-refresh-form').on('submit', function (e) {
		e.preventDefault();

		$.post(
			vms_script_ajax.admin_url,
			$(this).serialize() + '&action=vms_refresh_balance',
			function (response) {
				if (response.success) {
					alert(response.data.message);
					location.reload(); // reload after success
				} else {
					alert('Error: ' + response.data.errors.join(', '));
				}
			}
		);
	});

	// Helper function to show success modal
	function showSuccessModal(message, callback) {
		// Remove existing modals
		$('#success-modal-overlay, #guest-error-modal-overlay').remove();

		const successModal = `
        <div id="success-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
            <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                <div class="check_mark mx-auto mb-4">
                    <div class="sa-icon sa-success animate">
                        <span class="sa-line sa-tip animateSuccessTip"></span>
                        <span class="sa-line sa-long animateSuccessLong"></span>
                        <div class="sa-placeholder"></div>
                        <div class="sa-fix"></div>
                    </div>
                </div>
                <p class="text-lg font-medium text-gray-700 dark:text-white">${message}</p>
                <button id="ok-success-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                    OK
                </button>
            </div>
        </div>
    	`;

		$('body').append(successModal);

		$(document)
			.off('click', '#ok-success-btn')
			.on('click', '#ok-success-btn', function (e) {
				e.preventDefault();
				$('#success-modal-overlay').fadeOut(300, function () {
					$(this).remove();
					if (callback) callback();
				});
			});
	}

	// Helper function to show error modal
	function showErrorModal(message) {
		// Remove existing modals
		$('#success-modal-overlay, #guest-error-modal-overlay').remove();

		const errorModal = `
        <div id="guest-error-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
            <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                <div class="check_mark mx-auto mb-4">
                    <div class="sa-icon sa-error animate">
                        <span class="sa-line sa-left animateXLeft"></span>
                        <span class="sa-line sa-right animateXRight"></span>
                        <div class="sa-placeholder"></div>
                    </div>
                </div>
                <p class="text-lg font-medium text-gray-700 dark:text-white">${message}</p>
                <button id="ok-error-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">
                    OK
                </button>
            </div>
        </div>
    	`;

		$('body').append(errorModal);

		$(document)
			.off('click', '#ok-error-btn')
			.on('click', '#ok-error-btn', function (e) {
				e.preventDefault();
				$('#guest-error-modal-overlay').fadeOut(300, function () {
					$(this).remove();
				});
			});
	}
});
