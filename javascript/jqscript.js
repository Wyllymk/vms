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
					console.log('Guest creation response:', response);
					console.log('New guest data:', response.data.guestData);

					const guest = response.data.guestData;

					// Format the visit date with fallback
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

					const statusClasses = {
						approved:
							'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
						unapproved:
							'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
						suspended:
							'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
						banned: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
					};

					// Generate action buttons based on sign-in/out status
					const actionButtons = () => {
						if (!guest.sign_in_time) {
							return `
                            <button id="sign-in-button-${guest.id}"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg cursor-pointer whitespace-nowrap bg-blue-500 shadow-theme-xs hover:bg-blue-600">
                                Sign In
                            </button>
                        `;
						} else if (!guest.sign_out_time) {
							return `
                            <button id="sign-out-button-${guest.id}"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg cursor-pointer whitespace-nowrap bg-purple-500 shadow-theme-xs hover:bg-purple-600">
                                Sign Out
                            </button>
                        `;
						} else {
							return `<span class="text-xs text-gray-500">Completed</span>`;
						}
					};

					// Create the new row HTML
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
                                    ${guest.host_name || 'N/A'}
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

					// Remove "no guests" row if it exists
					if ($('#no-guests-row').length) {
						$('#no-guests-row').remove();
					}

					// Prepend the new row to the table
					$('tbody').prepend(newRow);

					// Update row numbers
					$('tbody tr').each(function (index) {
						$(this)
							.find('td:first p')
							.text(index + 1);
					});

					// Show success message
					const message =
						response.data.messages[0] ||
						'Guest registered successfully';

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
									// Trigger Alpine to close guest modal
									window.dispatchEvent(
										new Event('close-guest-modal')
									);
									// Reset form
									$('#guest-form')[0].reset();
								}
							);
						});
				} else {
					// Show error messages in a single modal
					const errorMessages = response.data.messages || [
						'An error occurred during guest registration',
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
				$('#submit-guest-form')
					.prop('disabled', false)
					.text('Create Guest');
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
					console.log('Guest creation response:', response);
					console.log('New guest data:', response.data.guestData);

					const guest = response.data.guestData;

					// Format the visit date with fallback
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

					const statusClasses = {
						approved:
							'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
						unapproved:
							'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
						suspended:
							'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
						banned: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
					};

					// Format time for display
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

					// Generate action buttons or time display based on sign-in/out status
					const actionButtons = () => {
						if (!guest.sign_in_time) {
							return `
                            <button id="sign-in-button-${guest.id}" data-visit-id="${guest.visit_id}"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg cursor-pointer whitespace-nowrap bg-blue-500 shadow-theme-xs hover:bg-blue-600">
                                Sign In
                            </button>
                        `;
						} else if (!guest.sign_out_time) {
							return `
                            <button id="sign-out-button-${guest.id}" data-visit-id="${guest.visit_id}"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg cursor-pointer whitespace-nowrap bg-purple-500 shadow-theme-xs hover:bg-purple-600">
                                Sign Out
                            </button>
                        `;
						} else {
							return `
                            <div class="flex flex-col text-xs">
                                <span class="text-green-600 dark:text-green-400">${formatTime(guest.sign_in_time)}</span>
                                <span class="text-red-600 dark:text-red-400">${formatTime(guest.sign_out_time)}</span>
                            </div>
                        `;
						}
					};

					// Create the new row HTML
					const newRow = `
                    <tr data-guest-id="${guest.id}">
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
                                    ${guest.host_name || 'N/A'}
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

					// Remove "no guests" row if it exists
					if ($('#no-guests-row').length) {
						$('#no-guests-row').remove();
					}

					// Prepend the new row to the table
					$('tbody').prepend(newRow);

					// Update row numbers
					$('tbody tr').each(function (index) {
						$(this)
							.find('td:first p')
							.text(index + 1);
					});

					// Show success message
					const message =
						response.data.messages[0] ||
						'Guest registered successfully';
					showSuccessModal(message, () => {
						// Trigger Alpine to close guest modal
						window.dispatchEvent(new Event('close-guest-modal'));
						// Reset form
						$('#guest-form')[0].reset();
					});
				} else {
					// Show error messages in a single modal
					const errorMessages = response.data.messages || [
						'An error occurred during guest registration',
					];
					showErrorModal(errorMessages.join('<br>'));
				}
			},
			error: function (xhr, status, error) {
				// Show error message
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;
				showErrorModal(errorMessage);
			},
			complete: function () {
				// Reset button
				$('#submit-guest-form')
					.prop('disabled', false)
					.text('Create Guest');
			},
		});
	});

	// Sign In Guest Button Handler
	$(document).on('click', '[id^="sign-in-button-"]', function (e) {
		e.preventDefault();

		// Extract visit ID from button's data attribute
		const visitId = $(this).data('visit-id');
		const button = $(this);
		const originalText = button.text();

		// Show confirmation
		if (!confirm('Are you sure you want to sign in this guest?')) {
			return;
		}

		// Show loading state
		button
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-6 h-6 border-4 border-current border-t-transparent rounded-full"></span>'
			);

		// AJAX request
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
				console.log('Sign in response:', response);
				if (response.success && response.data.guestData) {
					const guest = response.data.guestData;

					// Format time for display
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

					// Update the table row
					const row = $(`tr[data-guest-id="${guest.id}"]`);
					const statusCell = row.find('td:nth-child(4) span');
					const actionCell = row.find('td:last .flex');

					// Update status
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

					// Update action buttons
					actionCell
						.find(
							'[id^="sign-in-button-"], [id^="sign-out-button-"], .flex'
						)
						.remove();
					actionCell.append(`
                    <button id="sign-out-button-${guest.id}" data-visit-id="${guest.visit_id}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg cursor-pointer whitespace-nowrap bg-purple-500 shadow-theme-xs hover:bg-purple-600">
                        Sign Out
                    </button>
                `);

					// Show success modal
					const successMessage =
						response.data.messages[0] ||
						'Guest signed in successfully';
					showSuccessModal(successMessage);
				} else {
					// Show error modal
					const errorMessage =
						response.data.messages.join('<br>') ||
						'Error signing in guest';
					showErrorModal(errorMessage);
				}
			},
			error: function (xhr, status, error) {
				console.error('Sign in error:', error);
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;
				showErrorModal(errorMessage);
			},
			complete: function () {
				// Reset button if there was an error
				if (button.is(':visible')) {
					button.prop('disabled', false).text(originalText);
				}
			},
		});
	});

	// Sign Out Guest Button Handler
	$(document).on('click', '[id^="sign-out-button-"]', function (e) {
		e.preventDefault();

		// Extract visit ID from button's data attribute
		const visitId = $(this).data('visit-id');
		const button = $(this);
		const originalText = button.text();

		// Show confirmation
		if (!confirm('Are you sure you want to sign out this guest?')) {
			return;
		}

		// Show loading state
		button
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-6 h-6 border-4 border-current border-t-transparent rounded-full"></span>'
			);

		// AJAX request
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

					// Format time for display
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

					// Update the table row
					const row = $(`tr[data-guest-id="${guest.id}"]`);
					const statusCell = row.find('td:nth-child(4) span');
					const actionCell = row.find('td:last .flex');

					// Update status
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

					// Replace action buttons with sign-in/out times
					actionCell
						.find(
							'[id^="sign-in-button-"], [id^="sign-out-button-"], .flex'
						)
						.remove();
					actionCell.append(`
                    <div class="flex flex-col items-center justify-center w-full text-xs">
                        <span class="text-green-600 dark:text-green-400">${formatTime(guest.sign_in_time)}</span>
                        <span class="text-red-600 dark:text-red-400">${formatTime(guest.sign_out_time)}</span>
                    </div>
                `);

					// Show success modal
					const successMessage =
						response.data.messages[0] ||
						'Guest signed out successfully';
					showSuccessModal(successMessage);
				} else {
					// Show error modal
					const errorMessage =
						response.data.messages.join('<br>') ||
						'Error signing out guest';
					showErrorModal(errorMessage);
				}
			},
			error: function (xhr, status, error) {
				console.error('Sign out error:', error);
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;
				showErrorModal(errorMessage);
			},
			complete: function () {
				// Reset button if there was an error
				if (button.is(':visible')) {
					button.prop('disabled', false).text(originalText);
				}
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
