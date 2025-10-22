export function initSupplier() {
	const $ = jQuery;

	// Supplier Form Submit (Create/Update)
	$('.supplier-form').on('submit', function (e) {
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
}
