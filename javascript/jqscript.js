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
			const disabledClasses = 'bg-blue-500 opacity-50 cursor-not-allowed';
			const signInEnabledClasses =
				'bg-blue-500 cursor-pointer hover:bg-blue-600';
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
                                <button id="edit-guest-button-${guest.id}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">Edit</button>
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
									window.location.reload();
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
						active: 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
						inactive:
							'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
					};

					// Build new table row
					const newRow = `
                    <tr>
                        <td class="px-3 py-4 sm:px-6">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">${$('tbody tr').length + 1}</p>
                        </td>
                        <td class="px-3 py-4 sm:px-6">
                            <p class="text-gray-800 text-theme-sm dark:text-white/90">${employee.first_name || 'N/A'}</p>
                        </td>
                        <td class="px-3 py-4 sm:px-6">
                            <p class="text-gray-800 text-theme-sm dark:text-white/90">${employee.last_name || 'N/A'}</p>
                        </td>
                        <td class="px-3 py-4 sm:px-6">
                            <p class="text-gray-800 text-theme-sm dark:text-white/90">${employee.email || 'N/A'}</p>
                        </td>
                        <td class="px-3 py-4 sm:px-6">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">${employee.phone_number || 'N/A'}</p>
                        </td>
                        <td class="px-3 py-4 sm:px-6">
                            <span class="inline-flex items-center justify-center px-2.5 gap-1 py-0.5 text-sm font-medium capitalize rounded-full ${statusClasses[employee.registration_status] || statusClasses.active}">
                                ${employee.registration_status ? employee.registration_status.charAt(0).toUpperCase() + employee.registration_status.slice(1) : 'Active'}
                            </span>
                        </td>
                        <td class="px-3 py-4 sm:px-6">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">${roleNames[employee.user_role] || employee.user_role || 'N/A'}</p>
                        </td>
                        <td class="px-3 py-4 sm:px-6">
                            <div class="flex items-center gap-2">
                                <button id="edit-employee-button-${employee.id}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    Edit
                                </button>
                                <button id="delete-employee-button-${employee.id}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-red-500 rounded-lg cursor-pointer whitespace-nowrap hover:bg-red-600">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                	`;

					// Remove "no employees" row if it exists
					$('#no-employees-row').remove();

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
			const disabledClasses = 'bg-blue-500 opacity-50 cursor-not-allowed';
			const signInEnabledClasses =
				'bg-blue-500 cursor-pointer hover:bg-blue-600';
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
                                <button id="edit-guest-button-${guest.id}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">Edit</button>
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

									window.location.reload();
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

		if (
			!confirm(
				'Are you sure you want to delete this guest? This action is irreversible.'
			)
		) {
			return;
		}

		// Show loading indicator
		$(this)
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-6 h-6 border-4 border-current border-t-transparent rounded-full mr-2"></span> Deleting...'
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
				'<span class="animate-spin inline-block w-6 h-6 border-4 border-current border-t-transparent rounded-full mr-2"></span> Registering...'
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
		const originalText = button.text();

		if (!confirm('Are you sure you want to sign in this guest?')) return;

		button
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-6 h-6 border-4 border-current border-t-transparent rounded-full"></span>'
			);

		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: {
				action: 'sign_in_guest',
				visit_id: visitId,
				nonce: vms_script_ajax.nonce,
			},
			dataType: 'json',
			success: function (response) {
				// console.log('Sign in response:', response);

				if (response.success && response.data.guestData) {
					const guest = response.data.guestData;

					// Update status badge in the same row
					const row = button.closest('tr');
					const statusCell = row.find('td:nth-child(4) span');

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

					// keep the "Approved" badge intact
					statusCell.text('Approved');

					// Build Sign Out button; fallback to current data if API didn't return visit_id
					const newVisitId =
						guest.visit_id || button.data('visit-id');
					const signOutBtn = `
					<button id="sign-out-button-${guest.id}" data-visit-id="${newVisitId}"
						class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg cursor-pointer whitespace-nowrap bg-purple-500 shadow-theme-xs hover:bg-purple-600">
						Sign Out
					</button>
					`;

					// Swap only the clicked button (keeps the Edit button intact)
					button.replaceWith(signOutBtn);

					const successMessage =
						response.data.messages?.[0] ||
						'Guest signed in successfully';
					showSuccessModal(successMessage);
				} else {
					const errorMessage =
						response.data?.messages?.join('<br>') ||
						'Error signing in guest';
					showErrorModal(errorMessage);
					// restore original button text/state on error
					button.prop('disabled', false).text(originalText);
				}
			},
			error: function (xhr, status, error) {
				console.error('Sign in error:', error);
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;
				showErrorModal(errorMessage);
				button.prop('disabled', false).text(originalText);
			},
		});
	});

	// Sign Out Guest Button Handler
	$(document).on('click', '[id^="sign-out-button-"]', function (e) {
		e.preventDefault();

		const visitId = $(this).data('visit-id');
		const button = $(this);
		const originalText = button.text();

		if (!confirm('Are you sure you want to sign out this guest?')) return;

		button
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-6 h-6 border-4 border-current border-t-transparent rounded-full"></span>'
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

					const successMessage =
						response.data.messages?.[0] ||
						'Guest signed out successfully';
					showSuccessModal(successMessage);
				} else {
					const errorMessage =
						response.data?.messages?.join('<br>') ||
						'Error signing out guest';
					showErrorModal(errorMessage);
					button.prop('disabled', false).text(originalText);
				}
			},
			error: function (xhr, status, error) {
				console.error('Sign out error:', error);
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;
				showErrorModal(errorMessage);
				button.prop('disabled', false).text(originalText);
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
