export function initEmployee() {
	const $ = jQuery;

	/**
	 * Employee Management Handlers
	 * Handles AJAX submission of employee profile updates and deletions
	 */
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
