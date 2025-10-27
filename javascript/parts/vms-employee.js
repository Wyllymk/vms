export function initEmployee() {
	const $ = jQuery;

	/**
	 * Employee Management Handlers
	 * Handles AJAX submission of employee profile updates and deletions
	 */

	// Profile picture preview for employee form
	$('#profile_picture').on('change', function (e) {
		const file = e.target.files[0];
		if (file) {
			const reader = new FileReader();
			reader.onload = function (e) {
				$('#profile-preview').attr('src', e.target.result);
				$('#photo-selected').removeClass('hidden');
			};
			reader.readAsDataURL(file);
			console.log('Employee profile picture selected:', file.name);
		}
	});

	// Employee update form submission
	$('#employee-update-form').on('submit', function (e) {
		e.preventDefault();
		console.log('Employee update form submitted');

		// Show loading indicator
		$('#update-employee-btn')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Updating...'
			);

		// Clear previous messages and modals
		$('.alert-message').remove();
		$('#success-modal-overlay, #employee-error-modal-overlay').remove();

		// Collect form data
		var formData = new FormData(this);

		// Add user_id from the current page context
		const urlParams = new URLSearchParams(window.location.search);
		const userId = urlParams.get('user_id');
		if (userId) {
			formData.append('user_id', userId);
			console.log('Employee User ID:', userId);
		}

		formData.append('action', 'update_employee');
		formData.append('nonce', vms_script_ajax.nonce);

		// Log form data for debugging
		console.log('Form data prepared:', {
			first_name: formData.get('first_name'),
			last_name: formData.get('last_name'),
			email: formData.get('email'),
			status: formData.get('registration_status'),
			role: formData.get('user_role'),
		});

		// AJAX request
		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			success: function (response) {
				console.log('Employee AJAX response received:', response);

				if (response.success) {
					const message =
						response.data.message ||
						'Employee updated successfully';
					console.log('Update successful:', message);

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
                                <button id="ok-success-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">OK</button>
                            </div>
                        </div>`;

					$('body').append(successModal);

					// Handle OK button click
					$(document)
						.off('click', '#ok-success-btn')
						.on('click', '#ok-success-btn', function (e) {
							e.preventDefault();
							console.log(
								'Closing success modal and reloading page'
							);
							$('#success-modal-overlay').fadeOut(
								300,
								function () {
									$(this).remove();
									// location.reload();
								}
							);
						});
				} else {
					// Show error messages in a single modal
					const errorMessages = response.data.messages || [
						'An error occurred during employee update',
					];
					console.error('Update failed:', errorMessages);

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
                        </div>`;

					$('body').append(errorModal);

					$(document)
						.off('click', '#ok-error-btn')
						.on('click', '#ok-error-btn', function (e) {
							e.preventDefault();
							console.log('Closing error modal');
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
				console.error('Employee AJAX error:', {
					status: status,
					error: error,
					response: xhr.responseText,
				});

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
                    </div>`;

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
				console.log('Employee AJAX request completed');
				// Reset button
				$('#update-employee-btn')
					.prop('disabled', false)
					.text('Update Employee');
			},
		});
	});

	// Delete Employee Button Click Handler
	$('#delete-employee-btn').on('click', function (e) {
		e.preventDefault();
		console.log('Delete employee button clicked');

		const employeeName = $(this).data('member-name') || 'this employee';
		console.log('Employee to delete:', employeeName);

		// Show confirmation modal
		const confirmModal = `
            <div id="delete-employee-confirm-modal-overlay" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/40 backdrop-blur-sm p-5">
                <div class="bg-white dark:bg-gray-900 border border-white/10 rounded-2xl p-8 shadow-lg text-center animate-fade-in-up max-w-sm w-full">
                    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">Delete Employee</h3>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Are you sure you want to delete "${employeeName}"? This action is irreversible.</p>
                    <div class="flex gap-3">
                        <button id="cancel-delete-employee-btn" type="button" class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Cancel</button>
                        <button id="confirm-delete-employee-btn" type="button" class="flex-1 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600">Delete</button>
                    </div>
                </div>
            </div>
        `;
		$('body').append(confirmModal);
		console.log('Confirmation modal displayed');
	});

	// Handle Cancel Button
	$(document).on('click', '#cancel-delete-employee-btn', function (e) {
		e.preventDefault();
		console.log('Delete cancelled by user');
		$('#delete-employee-confirm-modal-overlay').fadeOut(300, function () {
			$(this).remove();
		});
	});

	// Handle Confirm Delete Button
	$(document).on('click', '#confirm-delete-employee-btn', function (e) {
		e.preventDefault();
		console.log('Delete confirmed by user');

		// Show loading state
		$(this)
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Deleting...'
			);

		// Get user ID from URL parameters
		const urlParams = new URLSearchParams(window.location.search);
		const userId = urlParams.get('user_id');

		if (!userId) {
			console.error('User ID not found in URL');
			$('#delete-employee-confirm-modal-overlay').remove();

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
                        <p class="text-lg font-medium text-gray-700 dark:text-white">User ID not found</p>
                        <button id="ok-error-btn" type="button" class="mt-6 inline-block w-1/2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">OK</button>
                    </div>
                </div>
            `;
			$('body').append(errorModal);
			return;
		}

		console.log('Sending delete request for Employee User ID:', userId);

		// AJAX request to delete employee
		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: {
				action: 'delete_employee',
				user_id: userId,
				nonce: vms_script_ajax.nonce,
			},
			dataType: 'json',
			success: function (response) {
				console.log('Delete AJAX response received:', response);

				// Close confirmation modal
				$('#delete-employee-confirm-modal-overlay').remove();

				if (response.success) {
					const message =
						response.data.message ||
						'Employee deleted successfully';
					console.log('Delete successful:', message);

					// Show success modal
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

					// Redirect after success
					$(document)
						.off('click', '#ok-success-btn')
						.on('click', '#ok-success-btn', function (e) {
							e.preventDefault();
							console.log('Redirecting to employees list');
							window.location.href = '/employees/';
						});
				} else {
					// Show error modal
					const errorMessages = response.data.messages || [
						'Failed to delete employee',
					];
					console.error('Delete failed:', errorMessages);

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
				console.error('Delete AJAX error:', {
					status: status,
					error: error,
					response: xhr.responseText,
				});

				// Close confirmation modal
				$('#delete-employee-confirm-modal-overlay').remove();

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
		});
	});
}
