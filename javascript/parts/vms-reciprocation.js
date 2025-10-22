export function initReciprocation() {
	const $ = jQuery;

	// Reciprocating Member FORM
	$('#reciprocation-form').on('submit', function (e) {
		e.preventDefault();

		$('#submit-reciprocating-form')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Registering...'
			);

		$('.alert-message').remove();
		$(
			'#success-modal-overlay, #reciprocating-error-modal-overlay'
		).remove();

		var formData = new FormData(this);
		formData.append('action', 'reciprocating_member_registration');
		formData.append('nonce', vms_script_ajax.nonce);

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

					// ✅ Normalize status safely
					const status = member.visit_status
						? member.visit_status.toLowerCase()
						: 'unknown';

					// ✅ Define status color classes
					const statusClasses = {
						approved:
							'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
						unapproved:
							'bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500',
						cancelled:
							'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80',
						suspended:
							'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
						banned: 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
						unknown:
							'bg-gray-100 text-gray-500 dark:bg-white/5 dark:text-gray-400',
					};

					const clubDisplay = member.club_name || 'Not Set';
					const memberNumberDisplay =
						member.reciprocating_member_number || 'Not Set';
					const hasClub = member.club_id ? true : false;
					const visitDate = member.visit_date
						? new Date(member.visit_date).toLocaleDateString(
								'en-US',
								{
									year: 'numeric',
									month: 'short',
									day: 'numeric',
								}
							)
						: 'Not Set';

					// ✅ Capitalize status label or fallback
					const statusLabel = member.visit_status
						? member.visit_status.charAt(0).toUpperCase() +
							member.visit_status.slice(1)
						: 'N/A';

					const newRow = `
						<tr data-member-id="${member.id}" data-has-club="${hasClub}">
							<td class="px-3 py-4 sm:px-6">
								<p class="text-gray-500 text-theme-sm dark:text-gray-400">
									${$('tbody tr').length + 1}
								</p>
							</td>
							<td class="px-3 py-4 sm:px-6">
								<p class="text-gray-800 text-theme-sm dark:text-white/90">
									${member.first_name || 'N/A'}
								</p>
							</td>
							<td class="px-3 py-4 sm:px-6">
								<p class="text-gray-800 text-theme-sm dark:text-white/90">
									${member.last_name || 'N/A'}
								</p>
							</td>
							<td class="px-3 py-4 sm:px-6">
								<span class="inline-flex items-center justify-center px-2.5 gap-1 py-0.5 text-sm font-medium capitalize rounded-full ${
									statusClasses[status]
								}">
									${statusLabel}
								</span>
							</td>
							<td class="px-3 py-4 sm:px-6">
								<p class="text-gray-500 text-theme-sm dark:text-gray-400">
									${clubDisplay}
								</p>
							</td>
							<td class="px-3 py-4 sm:px-6">
								<p class="text-gray-500 text-theme-sm dark:text-gray-400">
									${memberNumberDisplay}
								</p>
							</td>
							<td class="px-3 py-4 sm:px-6">
								<p class="text-gray-500 text-theme-sm dark:text-gray-400">
									${visitDate}
								</p>
							</td>
							<td class="px-3 py-4 sm:px-6">
								<div class="flex items-center gap-2">
									<button id="edit-reciprocating-member-button-${member.id}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg cursor-pointer whitespace-nowrap dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700" data-member-id="${member.id}">
										<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
										</svg>
										Edit
									</button>
									<button id="reciprocating-sign-in-button-${member.id}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg cursor-pointer whitespace-nowrap bg-brand-500 shadow-theme-xs hover:bg-brand-600" data-member-id="${member.id}">
										Sign In
									</button>
								</div>
							</td>
						</tr>
					`;

					$('#no-reciprocating-members-row').remove();
					$('#reciprocating-members-table-body').prepend(newRow);

					// ✅ Renumber rows after adding
					$('#reciprocating-members-table-body tr').each(
						function (index) {
							$(this)
								.find('td:first p')
								.text(index + 1);
						}
					);

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
					// ❌ Error response
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
					.text('Create Member');
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
			console.log(
				'[VMS JS] === Reciprocating Sign-In Button Clicked ==='
			);
			e.preventDefault();

			const memberId = $(this).data('member-id');
			const button = $(this);
			const row = button.closest('tr');
			const memberName =
				row.find('td:nth-child(2)').text().trim() +
				' ' +
				row.find('td:nth-child(3)').text().trim();
			const clubText = row.find('td:nth-child(5)').text().trim();
			const hasClub = clubText !== 'Not Set';

			console.log('[VMS JS] Member ID:', memberId);
			console.log('[VMS JS] Member Name:', memberName);
			console.log('[VMS JS] Has Club:', hasClub);

			// Build sign in form with conditional club field
			let clubField = '';

			if (!hasClub) {
				console.log(
					'[VMS JS] Member has NO club — fetching clubs via AJAX...'
				);

				$.ajax({
					url: vms_script_ajax.ajaxurl,
					type: 'POST',
					data: {
						action: 'get_reciprocating_clubs',
						nonce: vms_script_ajax.nonce,
					},
					async: false,
					beforeSend: function () {
						console.log(
							'[VMS JS] Sending AJAX request for clubs...'
						);
					},
					success: function (response) {
						console.log(
							'[VMS JS] AJAX response received:',
							response
						);

						if (response.success && response.data.clubs) {
							console.log(
								`[VMS JS] ${response.data.clubs.length} clubs found. Building dropdown...`
							);

							const clubOptions = response.data.clubs
								.map(
									(club) =>
										`<option value="${club.id}">${club.club_name}</option>`
								)
								.join('');

							clubField = `
						<div class="mb-4">
							<label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
								Reciprocating Club
								<span class="text-error-500">*</span>
							</label>
							<select id="sign-in-club-id" required
								class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800">
								<option value="">Select Club</option>
								${clubOptions}
							</select>
						</div>
					`;
						} else {
							console.warn(
								'[VMS JS] No clubs found or invalid response.'
							);
						}
					},
					error: function (xhr, status, error) {
						console.error(
							'[VMS JS] AJAX error fetching clubs:',
							error,
							xhr
						);
					},
				});
			} else {
				console.log(
					'[VMS JS] Member already has club — skipping club fetch.'
				);
			}

			console.log('[VMS JS] Building sign-in modal...');

			const signInModal = `
		<div class="sign-in-modal-overlay fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
			<div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg animate-fade-in-up max-w-md w-full">
				<div class="mb-6">
					<h3 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">Sign In Member</h3>
					<p class="text-sm text-gray-500 dark:text-gray-400">${memberName}</p>
				</div>

				<form id="sign-in-form">
					${clubField}
					
					<div class="mb-6">
						<label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
							Member Number
							<span class="text-error-500">*</span>
						</label>
						<input type="text" id="sign-in-member-number" required
							class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
							placeholder="Enter member number" />
					</div>

					<div class="flex gap-3">
						<button type="button" class="cancel-sign-in-btn flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
							Cancel
						</button>
						<button type="submit" class="confirm-sign-in-btn flex-1 rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-600" data-member-id="${memberId}">
							Sign In
						</button>
					</div>
				</form>
			</div>
		</div>
	`;

			console.log('[VMS JS] Appending modal to body...');
			$('body').append(signInModal);
			console.log('[VMS JS] Modal appended successfully.');
		}
	);

	// Cancel Sign In
	$(document).on('click', '.cancel-sign-in-btn', function () {
		$('.sign-in-modal-overlay').fadeOut(300, function () {
			$(this).remove();
		});
	});

	// Confirm Sign In
	$(document).on('submit', '#sign-in-form', function (e) {
		e.preventDefault();

		const memberId = $('.confirm-sign-in-btn').data('member-id');
		const memberNumber = $('#sign-in-member-number').val().trim();
		const clubId = $('#sign-in-club-id').val();
		const confirmBtn = $('.confirm-sign-in-btn');
		const button = $(
			`[id^="reciprocating-sign-in-button-"][data-member-id="${memberId}"]`
		);

		if (!memberNumber) {
			showErrorModal('Member number is required');
			return;
		}

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
				member_id: memberId,
				member_number: memberNumber,
				club_id: clubId || null,
				nonce: vms_script_ajax.nonce,
			},
			dataType: 'json',
			success: function (response) {
				if (response.success) {
					const member = response.data.memberData;

					// Update row
					const row = button.closest('tr');

					// Update club name if provided
					if (member.club_name) {
						row.find('td:nth-child(5) p').text(member.club_name);
					}

					// Update member number
					row.find('td:nth-child(6) p').text(
						member.reciprocating_member_number
					);

					// Update status badge
					const statusCell = row.find('td:nth-child(4) span');
					statusCell
						.removeClass()
						.addClass(
							'inline-flex items-center justify-center px-2.5 gap-1 py-0.5 text-sm font-medium capitalize rounded-full bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500'
						);
					statusCell.text('Approved');

					// Store club info in row
					row.data('has-club', true);

					// Replace sign in button with sign out button
					const signOutBtn = `
						<button id="reciprocating-sign-out-button-${member.id}" class="sign-out-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg cursor-pointer whitespace-nowrap bg-purple-500 shadow-theme-xs hover:bg-purple-600"
							data-visit-id="${member.visit_id}" data-member-id="${member.id}">
							Sign Out
						</button>
					`;
					button.replaceWith(signOutBtn);

					// Close modal
					$('.sign-in-modal-overlay').fadeOut(300, function () {
						$(this).remove();
					});

					// Show success
					const successMessage =
						response.data.messages?.[0] || 'Signed in successfully';
					showRecipSuccessModal(successMessage);
				} else {
					const errorMessages = response.data?.messages || [
						'Error signing in',
					];
					showRecipErrorModal(errorMessages.join('<br>'));
					$('.sign-in-modal-overlay').fadeOut(300, function () {
						$(this).remove();
					});
				}
			},
			error: function (xhr, status, error) {
				const errorMessage =
					xhr.responseJSON?.data?.messages?.join('<br>') ||
					'An error occurred: ' + error;
				showRecipErrorModal(errorMessage);
				$('.sign-in-modal-overlay').fadeOut(300, function () {
					$(this).remove();
				});
			},
			complete: function () {
				confirmBtn.prop('disabled', false).text('Sign In');
			},
		});
	});

	// Helper function to show success modal
	function showRecipSuccessModal(message) {
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
				$('#success-modal-overlay').fadeOut(300, function () {
					$(this).remove();
				});
			});
	}

	// Helper function to show error modal
	function showRecipErrorModal(message) {
		const errorModal = `
			<div id="error-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
				<div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
					<div class="check_mark mx-auto mb-4">
						<div class="sa-icon sa-error animate">
							<span class="sa-line sa-left animateXLeft"></span>
							<span class="sa-line sa-right animateXRight"></span>
							<div class="sa-placeholder"></div>
						</div>
					</div>
					<p class="text-lg font-medium text-gray-700 dark:text-white">${message}</p>
					<button id="ok-error-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">OK</button>
				</div>
			</div>
		`;
		$('body').append(errorModal);

		$(document)
			.off('click', '#ok-error-btn')
			.on('click', '#ok-error-btn', function (e) {
				e.preventDefault();
				$('#error-modal-overlay').fadeOut(300, function () {
					$(this).remove();
				});
			});
	}

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
