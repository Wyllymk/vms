export function initAccommodation() {
	const $ = jQuery;

	// Guest FORM
	$('#accommodation-guest-form').on('submit', function (e) {
		e.preventDefault();

		// Show loading indicator
		$('#submit-accommodation-guest-form')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Registering...'
			);

		// Clear previous messages and modals
		$('.alert-message').remove();
		$('#success-modal-overlay, #guest-error-modal-overlay').remove();

		// Collect form data
		var formData = new FormData(this);
		formData.append('action', 'accommodation_guest_registration');
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
					return `<button id="sign-in-accommodation-button-${guest.id}" class="${baseButtonClasses} ${isButtonDisabled ? disabledClasses : signInEnabledClasses}" data-visit-id="${guest.visit_id}" ${isButtonDisabled ? 'disabled' : ''}>Sign In</button>`;
				} else if (!guest.sign_out_time) {
					return `<button id="sign-out-accommodation-button-${guest.id}" class="${baseButtonClasses} ${isButtonDisabled ? disabledClasses : signOutEnabledClasses}" data-visit-id="${guest.visit_id}" ${isButtonDisabled ? 'disabled' : ''}>Sign Out</button>`;
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
                        <td class="px-3 py-4 sm:px-6"><p class="text-gray-500 text-theme-sm dark:text-gray-400">${formattedDate}</p></td>
                        <td class="px-3 py-4 sm:px-6">
                            <div class="flex items-center gap-2">
                                <button id="edit-accommodation-guest-button-${guest.id}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
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
										new Event('close-accommodation-modal')
									);
									$('#accommodation-guest-form')[0].reset();

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
				$('#submit-accommodation-guest-form')
					.prop('disabled', false)
					.text('Create Guest');
			},
		});
	});

	// Guest Update FORM
	$('#accommodation-guest-update-form').on('submit', function (e) {
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
		formData.append('action', 'update_accommodation_guest');
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
	$('#delete-accommodation-guest-btn').on('click', function (e) {
		e.preventDefault();

		const guestName = $(this).data('guest-name') || 'this guest';

		// Show confirmation modal
		const confirmModal = `
			<div id="delete-accommodation-guest-confirm-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
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
						<button id="confirm-delete-accommodation-guest-btn" type="button" class="flex-1 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600">Delete</button>
					</div>
				</div>
			</div>
		`;
		$('body').append(confirmModal);
	});

	// Cancel Delete Guest
	$(document).on('click', '#cancel-delete-guest-btn', function () {
		$('#delete-accommodation-guest-confirm-modal-overlay').fadeOut(
			300,
			function () {
				$(this).remove();
			}
		);
	});

	// Confirm Delete Guest
	$(document).on(
		'click',
		'#confirm-delete-accommodation-guest-btn',
		function () {
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
			formData.append('action', 'delete_accommodation_guest');
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
							response.data.message ||
							'Guest deleted successfully';

						// Close confirm modal first
						$(
							'#delete-accommodation-guest-confirm-modal-overlay'
						).fadeOut(300, function () {
							$(this).remove();
						});

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
										window.location.href = '/accommodation';
									}
								);
							});
					} else {
						// Close confirm modal
						$(
							'#delete-accommodation-guest-confirm-modal-overlay'
						).fadeOut(300, function () {
							$(this).remove();
						});

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
					$(
						'#delete-accommodation-guest-confirm-modal-overlay'
					).fadeOut(300, function () {
						$(this).remove();
					});

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
		}
	);

	// VISIT FORM
	$('#accommodation-visit-form').on('submit', function (e) {
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
		formData.append('action', 'register_accommodation_visit');
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
									$('#accommodation-visit-form')[0].reset();

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
	$(document).on(
		'click',
		'[id^="edit-accommodation-guest-button-"]',
		function (e) {
			e.preventDefault();

			// Extract guest ID from button ID
			const guestId = $(this)
				.attr('id')
				.replace('edit-accommodation-guest-button-', '');

			// Redirect to edit page
			window.location.href =
				vms_script_ajax.home_url +
				'/accommodation-details/?guest_id=' +
				guestId +
				'&paged=1';
		}
	);

	// Sign In Guest Button Handler
	$(document).on(
		'click',
		'[id^="sign-in-accommodation-button-"]',
		function (e) {
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
					
					<input type="number" id="accommodation-guest-id-number" placeholder="Enter ID Number" 
						class="mb-4 dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" 
						min="10000" />

					<div class="flex gap-3">
						<button id="cancel-sign-in-btn" type="button" 
							class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
							Cancel
						</button>
						<button id="confirm-sign-in-accommodation-btn" type="button" 
							class="flex-1 rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed"
							data-visit-id="${visitId}" disabled>
							Sign In
						</button>
					</div>
				</div>
			</div>
			`;

			$('body').append(confirmModal);
		}
	);

	// Enable Sign In button only when ID is valid (>= 5 digits)
	$(document).on('input', '#accommodation-guest-id-number', function () {
		const idNumber = $(this).val().trim();
		if (idNumber.length >= 5) {
			$('#confirm-sign-in-accommodation-btn').prop('disabled', false);
		} else {
			$('#confirm-sign-in-accommodation-btn').prop('disabled', true);
		}
	});

	// Cancel Sign In
	$(document).on('click', '#cancel-sign-in-btn', function () {
		$('#sign-in-confirm-modal-overlay').fadeOut(300, function () {
			$(this).remove();
		});
	});

	// Confirm Sign In
	$(document).on('click', '#confirm-sign-in-accommodation-btn', function () {
		const visitId = $(this).data('visit-id');
		const idNumber = $('#accommodation-guest-id-number').val().trim();
		const button = $(
			`[id^="sign-in-accommodation-button-"][data-visit-id="${visitId}"]`
		);

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
				action: 'sign_in_accommodation_guest',
				visit_id: visitId,
				id_number: idNumber,
				nonce: vms_script_ajax.nonce,
			},
			dataType: 'json',
			success: function (response) {
				if (response.success && response.data.guestData) {
					const guest = response.data.guestData;

					// ✅ Update ID number cell dynamically
					const row = button.closest('tr');
					row.find('.id_number').text(guest.id_number || 'N/A');

					// Build Sign Out button
					const newVisitId = guest.visit_id || visitId;
					const signOutBtn = `
					<button id="sign-out-accommodation-button-${guest.id}" data-visit-id="${newVisitId}"
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

				$('#sign-in-confirm-modal-overlay').fadeOut(300, function () {
					$(this).remove();
				});
			},
			complete: function () {
				$('#confirm-sign-in-accommodation-btn')
					.prop('disabled', false)
					.text('Sign In');
			},
		});
	});

	// Sign Out Guest Button Handler
	$(document).on(
		'click',
		'[id^="sign-out-accommodation-button-"]',
		function (e) {
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
		<div id="sign-out-confirm-accommodation-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
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
					<button id="confirm-accommodation-sign-out-btn" type="button" class="flex-1 rounded-lg bg-orange-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-orange-600" data-visit-id="${visitId}">Sign Out</button>
				</div>
			</div>
		</div>
	`;
			$('body').append(confirmModal);
		}
	);

	// Cancel Sign Out
	$(document).on('click', '#cancel-sign-out-btn', function () {
		$('#sign-out-confirm-accommodation-modal-overlay').fadeOut(
			300,
			function () {
				$(this).remove();
			}
		);
	});

	// Confirm Sign Out
	$(document).on('click', '#confirm-accommodation-sign-out-btn', function () {
		const visitId = $(this).data('visit-id');
		const button = $(
			`[id^="sign-out-accommodation-button-"][data-visit-id="${visitId}"]`
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
				action: 'sign_out_accommodation_guest',
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
					$('#sign-out-confirm-accommodation-modal-overlay').fadeOut(
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
					$('#sign-out-confirm-accommodation-modal-overlay').fadeOut(
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
				$('#sign-out-confirm-accommodation-modal-overlay').fadeOut(
					300,
					function () {
						$(this).remove();
					}
				);
			},
			complete: function () {
				$('#confirm-accommodation-sign-out-btn')
					.prop('disabled', false)
					.text('Sign Out');
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
}
