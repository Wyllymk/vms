jQuery(document).ready(function ($) {
	// PROFILE FORM
	$('#profile-form').on('submit', function (e) {
		e.preventDefault();

		// Show loading indicator
		$('#submit-button')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-6 h-6 border-4 border-current border-t-transparent rounded-full mr-2"></span> Processing...'
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

	// Guest FORM
	$('#guest-form').on('submit', function (e) {
		e.preventDefault();

		// Show loading indicator
		$('#submit-guest-form')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-6 h-6 border-4 border-current border-t-transparent rounded-full mr-2"></span> Registering...'
			);

		// Clear previous messages and modals
		$('.alert-message').remove();
		$('#success-modal-overlay, #guest-error-modal-overlay').remove();

		// Collect form data
		var formData = new FormData(this);
		formData.append('action', 'guest_registration');
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
				if (response.success && response.data.guestData) {
					// console.log('Guest creation response:', response);
					// console.log('New guest data:', response.data.guestData);

					const guest = response.data.guestData;

					// Format visit date
					let formattedDate = 'N/A';
					let normalizedVisitDate = '';
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
							normalizedVisitDate =
								guest.visit_date.split(' ')[0];
						}
					}

					// Host name
					let hostName = 'N/A';
					if (
						(guest.host_first_name &&
							guest.host_first_name.trim()) ||
						(guest.host_last_name && guest.host_last_name.trim())
					) {
						hostName =
							`${guest.host_first_name || ''} ${guest.host_last_name || ''}`.trim();
					} else if (guest.display_name) {
						// Split display_name "wilson.mbuthia" → "Wilson Mbuthia"
						hostName = guest.display_name
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
							'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
						unapproved:
							'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
						suspended:
							'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
						banned: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
					};

					// Action buttons / Missed logic
					const actionButtons = () => {
						const currentDate = new Date()
							.toISOString()
							.split('T')[0];

						const isButtonDisabled =
							!guest.visit_date ||
							!guest.status ||
							!/^\d{4}-\d{2}-\d{2}$/.test(normalizedVisitDate) ||
							currentDate < normalizedVisitDate ||
							guest.status !== 'approved';

						const isMissed =
							!guest.sign_in_time &&
							normalizedVisitDate &&
							currentDate > normalizedVisitDate;

						const baseButtonClasses =
							'inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg whitespace-nowrap shadow-theme-xs';
						const disabledClasses = 'opacity-50 cursor-not-allowed';
						const signInEnabledClasses =
							'bg-blue-500 cursor-pointer hover:bg-blue-600';
						const signOutEnabledClasses =
							'bg-purple-500 cursor-pointer hover:bg-purple-600';

						if (isMissed) {
							return `
							<span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-yellow-800 bg-yellow-100 rounded-lg dark:bg-yellow-900 dark:text-yellow-200">
								Missed
							</span>
						`;
						}

						if (!guest.sign_in_time) {
							return `
							<button id="sign-in-button-${guest.id}"
								class="${baseButtonClasses} ${isButtonDisabled ? disabledClasses : signInEnabledClasses}"
								data-visit-id="${guest.visit_id}"
								${isButtonDisabled ? 'disabled' : ''}>
								Sign In
							</button>
						`;
						} else if (!guest.sign_out_time) {
							return `
							<button id="sign-out-button-${guest.id}"
								class="${baseButtonClasses} ${isButtonDisabled ? disabledClasses : signOutEnabledClasses}"
								data-visit-id="${guest.visit_id}"
								${isButtonDisabled ? 'disabled' : ''}>
								Sign Out
							</button>
						`;
						} else {
							const signInTime = guest.sign_in_time
								? new Date(
										guest.sign_in_time
									).toLocaleTimeString([], {
										hour: 'numeric',
										minute: '2-digit',
									})
								: '';
							const signOutTime = guest.sign_out_time
								? new Date(
										guest.sign_out_time
									).toLocaleTimeString([], {
										hour: 'numeric',
										minute: '2-digit',
									})
								: '';
							return `
							<div class="flex flex-col items-center justify-center text-xs px-4">
								<span class="text-green-600 dark:text-green-400">${signInTime}</span>
								<span class="text-red-600 dark:text-red-400">${signOutTime}</span>
							</div>
						`;
						}
					};

					// Build row
					const newRow = `
				<tr>
					<td class="px-5 py-4 sm:px-6">
						<p class="text-gray-500 text-theme-sm dark:text-gray-400">
							${$('tbody tr').length + 1}
						</p>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<p class="text-gray-800 text-theme-sm dark:text-white/90">
								${guest.first_name || 'N/A'}
							</p>
						</div>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<p class="text-gray-800 text-theme-sm dark:text-white/90">
								${guest.last_name || 'N/A'}
							</p>
						</div>
					</td>                    
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<span class="px-2 py-1 text-xs font-medium rounded-full ${statusClasses[guest.status] || ''}">
								${guest.status ? guest.status.charAt(0).toUpperCase() + guest.status.slice(1) : 'N/A'}
							</span>
						</div>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<p class="text-gray-500 text-theme-sm dark:text-gray-400">
								${guest.id_number || 'N/A'}
							</p>
						</div>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<p class="text-gray-500 text-theme-sm dark:text-gray-400">
								${hostName}
							</p>
						</div>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center">
							<p class="text-gray-500 text-theme-sm dark:text-gray-400">
								${formattedDate}
							</p>
						</div>
					</td>
					<td class="px-5 py-4 sm:px-6">
						<div class="flex items-center gap-2">                            
							<button id="edit-guest-button-${guest.id}"
								class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
								Edit
							</button>
							${actionButtons()}
						</div>
					</td>
				</tr>
				`;

					// Remove "no guests" row
					if ($('#no-guests-row').length) {
						$('#no-guests-row').remove();
					}

					// Prepend row
					$('tbody').prepend(newRow);

					// Re-number rows
					$('tbody tr').each(function (index) {
						$(this)
							.find('td:first p')
							.text(index + 1);
					});

					// Success modal
					const message =
						response.data.messages[0] ||
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
						<button id="ok-success-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
							OK
						</button>
					</div>
				</div>
				`;
					$('body').append(successModal);

					// Close modal
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
								}
							);
						});
				} else {
					// Error modal
					const errorMessages = response.data.messages || [
						'An error occurred during guest registration',
					];
					const errorMessageHtml = errorMessages
						.map(
							(msg) =>
								`<p class="text-lg font-medium text-gray-700 dark:text-white">${msg}</p>`
						)
						.join('');

					const errorModal = `
				<div id="guest-error-modal-overlay"
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
			<div id="guest-error-modal-overlay"
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

	// Guest Update FORM
	$('#guest-update-form').on('submit', function (e) {
		e.preventDefault();

		// Show loading indicator
		$('#update-guest-btn')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-6 h-6 border-4 border-current border-t-transparent rounded-full mr-2"></span> Updating...'
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

	// Edit Guest Button Handler
	$(document).on('click', '[id^="edit-guest-button-"]', function (e) {
		e.preventDefault();

		// Extract guest ID from button ID
		const guestId = $(this).attr('id').replace('edit-guest-button-', '');

		// Redirect to edit page
		window.location.href =
			vms_script_ajax.admin_url + 'guest-details/?guest_id=' + guestId;
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
							'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
						unapproved:
							'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
						suspended:
							'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
						banned: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
					};

					statusCell
						.removeClass()
						.addClass(
							`px-2 py-1 text-xs font-medium rounded-full ${statusClasses[guest.status] || ''}`
						)
						.text(
							guest.status
								? guest.status.charAt(0).toUpperCase() +
										guest.status.slice(1)
								: 'N/A'
						);

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

					// Remove the sign-out button only
					button.remove();

					// Append the times block right after the edit button
					parentContainer.append(`
                    <div class="flex flex-col items-center justify-center px-4 text-xs">
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
