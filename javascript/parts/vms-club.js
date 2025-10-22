export function initClub() {
	const $ = jQuery;

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
}
