export function initMember() {
	const $ = jQuery;

	// Member Update FORM
	$('#member-update-form1').on('submit', function (e) {
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
}
