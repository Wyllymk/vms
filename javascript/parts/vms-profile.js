export function initProfile() {
	const $ = jQuery;

	$('#profile-form').on('submit', function (e) {
		e.preventDefault();

		$('#submit-button')
			.prop('disabled', true)
			.html(
				'<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> Processing...'
			);

		$('.alert-message').remove();

		const formData = new FormData(this);
		formData.append('action', 'update_user_profile');
		formData.append('nonce', vms_script_ajax.nonce);

		const profilePicture = $('#profile_picture')[0].files[0];
		if (profilePicture) {
			formData.append('profile_picture', profilePicture);
		}

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

					const message =
						response.data.messages[0] ||
						'Changes saved successfully';

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

					$('#profile-form').append(successModal);

					$(document).on('click', '#ok-success-btn', function (e) {
						e.preventDefault();
						$('#success-modal-overlay').fadeOut(300, function () {
							$(this).remove();
						});
						window.dispatchEvent(new Event('close-info-modal'));
					});

					const user = response.data.userData;

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

					if (user.avatar) {
						$('#profile-avatar').attr('src', user.avatar);
					}

					function getYesIcon() {
						return `<svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
					}

					function getNoIcon() {
						return `<svg class="w-4 h-4 text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;
					}

					$('#profile-receive-messages-icon').html(
						user.receive_messages === 'yes'
							? getYesIcon()
							: getNoIcon()
					);
					$('#profile-receive-emails-icon').html(
						user.receive_emails === 'yes'
							? getYesIcon()
							: getNoIcon()
					);
					$('.profile-display-name').text(user.display_name || '');
				} else {
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
				$('#submit-button')
					.prop('disabled', false)
					.text('Save Changes');
				$('html, body').animate({ scrollTop: 0 }, 500);
			},
		});
	});
}
