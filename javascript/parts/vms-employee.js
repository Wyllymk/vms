export function initEmployee() {
	const $ = jQuery;

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
}
