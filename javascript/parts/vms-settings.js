export function initSettings() {
	const $ = jQuery;

	if (!$('#alert-container').length) return;

	const alertContainer = $('#alert-container')[0];
	const refreshButton = $('#refresh-balance')[0];
	const testButton = $('#test-connection')[0];
	const saveButton = $('#save-settings')[0];
	const settingsForm = $('#settings-form')[0];

	function showAlert(message, type = 'success') {
		const alertClass =
			type === 'success'
				? 'bg-green-500 border-green-700'
				: 'bg-red-500 border-red-700';
		const iconPath =
			type === 'success'
				? 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
				: 'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z';

		const alertHtml = `
      <div class="flex items-center justify-between p-4 mb-4 text-white ${alertClass} border-l-4 rounded" role="alert">
        <div class="flex items-center">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="${iconPath}" clip-rule="evenodd"></path>
          </svg>
          <div>
            <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong>
            <p class="text-sm">${message}</p>
          </div>
        </div>
        <button type="button" class="text-white hover:text-gray-300" onclick="this.parentElement.style.display='none';">×</button>
      </div>
    `;

		$(alertContainer).html(alertHtml);

		if (type === 'success') {
			setTimeout(() => {
				const alert = $(alertContainer).find('[role="alert"]');
				if (alert.length) {
					alert.fadeOut();
				}
			}, 5000);
		}
	}

	if (refreshButton) {
		$(refreshButton).on('click', async function () {
			const originalHtml = this.innerHTML;
			const spinner = `<svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Refreshing...</span>`;

			this.disabled = true;
			this.innerHTML = spinner;

			try {
				const formData = new FormData();
				formData.append('action', 'vms_ajax_refresh_balance');
				formData.append('nonce', vms_script_ajax.nonce);

				const response = await fetch(vms_script_ajax.ajaxurl, {
					method: 'POST',
					body: formData,
				});

				if (!response.ok) {
					throw new Error(`HTTP error! status: ${response.status}`);
				}

				const data = await response.json();
				console.log('Response data:', data);

				if (data.success) {
					const message =
						data.data?.message || 'Balance refreshed successfully';
					showAlert(message, 'success');

					if (data.data?.balance !== undefined) {
						$('#balance-amount').text(
							'KES ' +
								Number(data.data.balance).toLocaleString(
									undefined,
									{ minimumFractionDigits: 2 }
								)
						);
					}

					if (data.data?.last_checked !== undefined) {
						$('#last-updated').text(data.data.last_checked);
					}
				} else {
					let errorMsg = 'Failed to refresh balance';
					if (data.data?.errors && Array.isArray(data.data.errors)) {
						errorMsg = data.data.errors.join(', ');
					} else if (typeof data.data === 'string') {
						errorMsg = data.data;
					} else if (data.message) {
						errorMsg = data.message;
					}
					showAlert(errorMsg, 'error');
				}
			} catch (error) {
				console.error('Error details:', error);
				showAlert('Network error: ' + error.message, 'error');
			} finally {
				this.disabled = false;
				this.innerHTML = originalHtml;
			}
		});
	}

	if (settingsForm) {
		$(settingsForm).on('submit', async function (e) {
			e.preventDefault();

			const originalHtml = $(saveButton).html();
			const spinner = `<svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Saving...</span>`;

			$(saveButton).prop('disabled', true).html(spinner);

			try {
				const formData = new FormData(this);
				formData.append('action', 'vms_ajax_save_settings');
				formData.append('nonce', vms_script_ajax.nonce);

				const response = await fetch(vms_script_ajax.ajaxurl, {
					method: 'POST',
					body: formData,
				});

				const data = await response.json();

				if (data.success) {
					const message =
						data.data?.message || 'Settings saved successfully';
					showAlert(message, 'success');
				} else {
					let errorMsg = 'Failed to save settings';
					if (data.data?.errors && Array.isArray(data.data.errors)) {
						errorMsg = data.data.errors.join(', ');
					} else if (typeof data.data === 'string') {
						errorMsg = data.data;
					} else if (data.message) {
						errorMsg = data.message;
					}
					showAlert(errorMsg, 'error');
				}
			} catch (error) {
				showAlert('Network error: ' + error.message, 'error');
			} finally {
				$(saveButton).prop('disabled', false).html(originalHtml);
			}
		});
	}

	if (testButton) {
		$(testButton).on('click', async function () {
			const originalHtml = this.innerHTML;
			const apiKey = $('#api_key').val();
			const apiSecret = $('#api_secret').val();

			if (!apiKey || !apiSecret) {
				showAlert(
					'Please enter both API Key and API Secret first.',
					'error'
				);
				return;
			}

			const spinner = `<svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Testing...</span>`;

			this.disabled = true;
			this.innerHTML = spinner;

			try {
				const formData = new FormData();
				formData.append('action', 'vms_ajax_test_connection');
				formData.append('api_key', apiKey);
				formData.append('api_secret', apiSecret);
				formData.append('nonce', vms_script_ajax.nonce);

				const response = await fetch(vms_script_ajax.ajaxurl, {
					method: 'POST',
					body: formData,
				});

				const data = await response.json();

				if (data.success) {
					const message =
						data.data?.message || 'Connection test successful!';
					showAlert(message, 'success');
				} else {
					let errorMsg = 'Connection test failed';
					if (data.data?.errors && Array.isArray(data.data.errors)) {
						errorMsg = data.data.errors.join(', ');
					} else if (typeof data.data === 'string') {
						errorMsg = data.data;
					} else if (data.message) {
						errorMsg = data.message;
					}
					showAlert(errorMsg, 'error');
				}
			} catch (error) {
				showAlert('Network error: ' + error.message, 'error');
			} finally {
				this.disabled = false;
				this.innerHTML = originalHtml;
			}
		});
	}

	// Handle balance refresh
	$('#vms-refresh-form').on('submit', function (e) {
		e.preventDefault();

		$.post(
			vms_script_ajax.admin_url,
			$(this).serialize() + '&action=vms_refresh_balance',
			function (response) {
				if (response.success) {
					alert(response.data.message);
					location.reload(); // reload after success
				} else {
					alert('Error: ' + response.data.errors.join(', '));
				}
			}
		);
	});
}
