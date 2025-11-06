import Chart from 'chart.js/auto';

export function initReports() {
	const $ = jQuery;
	let visitorTrendsChart = null;
	let visitorDistributionChart = null;

	console.log('[VMS Reports] Initializing reports module');

	/**
	 * Initialize charts for trends and distribution
	 */
	function initCharts() {
		console.log('[VMS Reports] Initializing charts');

		// Visitor Trends Chart (Line Chart)
		const trendsCtx = document.getElementById('visitor-trends-chart');
		if (trendsCtx) {
			console.log('[VMS Reports] Creating visitor trends chart');
			visitorTrendsChart = new Chart(trendsCtx, {
				type: 'line',
				data: {
					labels: [],
					datasets: [
						{
							label: 'Guests',
							data: [],
							borderColor: 'rgb(59, 130, 246)',
							backgroundColor: 'rgba(59, 130, 246, 0.1)',
							tension: 0.4,
						},
						{
							label: 'Accommodation',
							data: [],
							borderColor: 'rgb(34, 197, 94)',
							backgroundColor: 'rgba(34, 197, 94, 0.1)',
							tension: 0.4,
						},
						{
							label: 'Suppliers',
							data: [],
							borderColor: 'rgb(168, 85, 247)',
							backgroundColor: 'rgba(168, 85, 247, 0.1)',
							tension: 0.4,
						},
						{
							label: 'Reciprocating',
							data: [],
							borderColor: 'rgb(249, 115, 22)',
							backgroundColor: 'rgba(249, 115, 22, 0.1)',
							tension: 0.4,
						},
					],
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						legend: {
							position: 'bottom',
						},
					},
					scales: {
						y: {
							beginAtZero: true,
							ticks: {
								stepSize: 1,
							},
						},
					},
				},
			});
		}

		// Visitor Distribution Chart (Doughnut Chart)
		const distCtx = document.getElementById('visitor-distribution-chart');
		if (distCtx) {
			console.log('[VMS Reports] Creating visitor distribution chart');
			visitorDistributionChart = new Chart(distCtx, {
				type: 'doughnut',
				data: {
					labels: [
						'Guests',
						'Accommodation',
						'Suppliers',
						'Reciprocating Members',
					],
					datasets: [
						{
							data: [0, 0, 0, 0],
							backgroundColor: [
								'rgb(59, 130, 246)',
								'rgb(34, 197, 94)',
								'rgb(168, 85, 247)',
								'rgb(249, 115, 22)',
							],
						},
					],
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						legend: {
							position: 'bottom',
						},
					},
				},
			});
		}

		console.log('[VMS Reports] Charts initialized successfully');
	}

	/**
	 * Show alert message
	 * @param {string} message - Alert message
	 * @param {string} type - Alert type ('success' or 'error')
	 */
	function showAlert(message, type = 'success') {
		console.log(`[VMS Reports] Showing ${type} alert: ${message}`);

		const alertHtml = `
            <div class="rounded-lg border ${type === 'success' ? 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800' : 'bg-red-50 border-red-200 dark:bg-red-900/20 dark:border-red-800'} p-4 mb-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 ${type === 'success' ? 'text-green-500' : 'text-red-500'} mr-3" fill="currentColor" viewBox="0 0 20 20">
                        ${
							type === 'success'
								? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'
								: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>'
						}
                    </svg>
                    <span class="${type === 'success' ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200'}">${message}</span>
                </div>
            </div>
        `;

		$('#alert-container').html(alertHtml);

		// Auto-hide after 5 seconds
		setTimeout(() => {
			$('#alert-container').fadeOut(() =>
				$('#alert-container').empty().show()
			);
		}, 5000);
	}

	/**
	 * Show loading state on button
	 * @param {jQuery} button - Button element
	 */
	function showLoading(button) {
		console.log('[VMS Reports] Showing loading state');
		button.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
		const originalText = button.html();
		button.data('original-text', originalText);
		button.html(`
            <svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Processing...</span>
        `);
	}

	/**
	 * Hide loading state on button
	 * @param {jQuery} button - Button element
	 */
	function hideLoading(button) {
		console.log('[VMS Reports] Hiding loading state');
		button
			.prop('disabled', false)
			.removeClass('opacity-50 cursor-not-allowed');
		const originalText = button.data('original-text');
		if (originalText) {
			button.html(originalText);
		}
	}

	/**
	 * Format status badge HTML
	 * @param {string} status - Status value
	 * @returns {string} HTML for status badge
	 */
	function formatStatus(status) {
		const statusMap = {
			approved:
				'<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">Approved</span>',
			unapproved:
				'<span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">Pending</span>',
			cancelled:
				'<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400">Cancelled</span>',
			suspended:
				'<span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400">Suspended</span>',
			banned: '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400">Banned</span>',
		};
		return statusMap[status] || status;
	}

	/**
	 * Format time from datetime
	 * @param {string} datetime - DateTime string
	 * @returns {string} Formatted time
	 */
	function formatTime(datetime) {
		if (!datetime) return '-';
		const date = new Date(datetime);
		return date.toLocaleTimeString('en-US', {
			hour: '2-digit',
			minute: '2-digit',
		});
	}

	/**
	 * Load report data from server
	 */
	function loadReportData() {
		const fromDate = $('#from_date').val();
		const toDate = $('#to_date').val();

		console.log(
			`[VMS Reports] Loading report data for ${fromDate} to ${toDate}`
		);

		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: {
				action: 'vms_get_reports_data',
				nonce: vms_script_ajax.nonce,
				from_date: fromDate,
				to_date: toDate,
			},
			beforeSend: function () {
				console.log(
					'[VMS Reports] Sending AJAX request for report data'
				);
			},
			success: function (response) {
				console.log('[VMS Reports] AJAX response received:', response);

				if (response.success) {
					console.log(
						'[VMS Reports] Report data loaded successfully'
					);
					updateDashboard(response.data);
				} else {
					console.error(
						'[VMS Reports] Failed to load report data:',
						response.data
					);
					showAlert(
						response.data.message || 'Failed to load report data',
						'error'
					);
				}
			},
			error: function (xhr, status, error) {
				console.error(
					'[VMS Reports] AJAX error loading report data:',
					status,
					error,
					xhr
				);
				showAlert(
					'An error occurred while loading report data',
					'error'
				);
			},
		});
	}

	/**
	 * Update dashboard with loaded data
	 * @param {Object} data - Report data
	 */
	function updateDashboard(data) {
		console.log('[VMS Reports] Updating dashboard with data:', data);

		// Update stats cards
		$('#total-guests-count').text(data.stats.guests.total);
		$('#total-guests-visited').text(data.stats.guests.visited);
		$('#total-accommodation-count').text(data.stats.accommodation.total);
		$('#total-accommodation-visited').text(
			data.stats.accommodation.visited
		);
		$('#total-suppliers-count').text(data.stats.suppliers.total);
		$('#total-suppliers-visited').text(data.stats.suppliers.visited);
		$('#total-reciprocating-count').text(data.stats.reciprocating.total);
		$('#total-reciprocating-visited').text(
			data.stats.reciprocating.visited
		);

		console.log('[VMS Reports] Stats cards updated');

		// Update trends chart
		if (visitorTrendsChart && data.trends) {
			console.log('[VMS Reports] Updating trends chart');
			visitorTrendsChart.data.labels = data.trends.labels;
			visitorTrendsChart.data.datasets[0].data = data.trends.guests;
			visitorTrendsChart.data.datasets[1].data =
				data.trends.accommodation;
			visitorTrendsChart.data.datasets[2].data = data.trends.suppliers;
			visitorTrendsChart.data.datasets[3].data =
				data.trends.reciprocating;
			visitorTrendsChart.update();
		}

		// Update distribution chart
		if (visitorDistributionChart && data.distribution) {
			console.log('[VMS Reports] Updating distribution chart');
			visitorDistributionChart.data.datasets[0].data = [
				data.distribution.guests,
				data.distribution.accommodation,
				data.distribution.suppliers,
				data.distribution.reciprocating,
			];
			visitorDistributionChart.update();
		}

		// Update tables
		console.log('[VMS Reports] Updating tables');
		updateTable('guests', data.guests);
		updateTable('accommodation', data.accommodation);
		updateTable('suppliers', data.suppliers);
		updateTable('reciprocating', data.reciprocating);

		console.log('[VMS Reports] Dashboard update complete');
	}

	/**
	 * Update table with data
	 * @param {string} type - Table type
	 * @param {Array} data - Table data
	 */
	function updateTable(type, data) {
		console.log(
			`[VMS Reports] Updating ${type} table with ${data.length} records`
		);

		const tbody = $(`#${type}-tbody`);
		tbody.empty();

		if (data.length === 0) {
			const colspan = type === 'reciprocating' ? 7 : 6;
			tbody.append(`
                <tr>
                    <td colspan="${colspan}" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                        No records found for the selected date range
                    </td>
                </tr>
            `);
			return;
		}

		data.forEach(function (row) {
			let tr = '<tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">';
			tr += `<td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">${row.name}</td>`;

			if (type === 'reciprocating') {
				tr += `<td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">${row.club || '-'}</td>`;
			}

			tr += `<td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">${row.phone}</td>`;
			tr += `<td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">${row.visit_date}</td>`;
			tr += `<td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">${formatTime(row.sign_in_time)}</td>`;
			tr += `<td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">${formatTime(row.sign_out_time)}</td>`;
			tr += `<td class="px-4 py-3">${formatStatus(row.status)}</td>`;
			tr += '</tr>';

			tbody.append(tr);
		});

		console.log(`[VMS Reports] ${type} table updated successfully`);
	}

	/**
	 * Export PDF with proper success/error detection
	 * @param {string} action - AJAX action name
	 * @param {string|null} section - Section name (null for full report)
	 */
	function exportPdf(action, section = null) {
		console.log(
			`[VMS PDF Export] Starting PDF export - Action: ${action}, Section: ${section || 'full'}`
		);

		const button = section
			? $(`.export-section-btn[data-section="${section}"]`)
			: $('#export-pdf');
		const fromDate = $('#from_date').val();
		const toDate = $('#to_date').val();

		showLoading(button);

		// Prepare form data
		const postData = {
			action: action,
			nonce: vms_script_ajax.nonce,
			from_date: fromDate,
			to_date: toDate,
		};

		if (section) {
			postData.section = section;
		}

		console.log('[VMS PDF Export] Request data:', postData);

		// Determine filename
		let filename;
		if (section) {
			const sectionTitles = {
				guests: 'guests-report',
				accommodation: 'accommodation-guests-report',
				suppliers: 'suppliers-report',
				reciprocating: 'reciprocating-members-report',
			};
			filename = `${sectionTitles[section] || 'report'}-${fromDate}-to-${toDate}.pdf`;
		} else {
			filename = `vms-report-${fromDate}-to-${toDate}.pdf`;
		}

		console.log(`[VMS PDF Export] Expected filename: ${filename}`);

		$.ajax({
			url: vms_script_ajax.ajaxurl,
			type: 'POST',
			data: postData,
			xhrFields: {
				responseType: 'blob', // ensure we receive binary
			},
			processData: true, // leave as true, since we send simple data
			success: function (data, textStatus, jqXHR) {
				console.log('[VMS PDF Export] AJAX request completed');
				let blob = data;

				// Defensive check
				if (!blob) {
					console.error(
						'[VMS PDF Export] No blob received — likely server error or wrong response type'
					);
					showAlert(
						'Invalid server response while exporting PDF',
						'error'
					);
					hideLoading(button);
					return;
				}

				console.log('[VMS PDF Export] Response type:', blob.type);
				console.log(
					'[VMS PDF Export] Response size:',
					blob.size,
					'bytes'
				);

				const pdfStatus = jqXHR.getResponseHeader('X-PDF-Status');
				const contentType =
					blob.type || jqXHR.getResponseHeader('content-type');

				if (pdfStatus === 'error') {
					const reader = new FileReader();
					reader.onload = (e) => {
						try {
							const errorData = JSON.parse(e.target.result);
							showAlert(
								errorData.message || 'Failed to export PDF',
								'error'
							);
						} catch (err) {
							showAlert('Failed to export PDF', 'error');
						}
					};
					reader.readAsText(blob);
					hideLoading(button);
					return;
				}

				// Ensure it’s a valid PDF
				if (contentType && contentType.includes('application/pdf')) {
					const url = URL.createObjectURL(blob);
					const a = document.createElement('a');
					a.href = url;
					a.download = filename;
					document.body.appendChild(a);
					a.click();
					setTimeout(() => {
						URL.revokeObjectURL(url);
						a.remove();
					}, 100);
					showAlert('PDF exported successfully');
				} else {
					showAlert('Invalid response: expected PDF', 'error');
				}

				hideLoading(button);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.error('[VMS PDF Export] AJAX error:', {
					textStatus,
					errorThrown,
					status: jqXHR.status,
				});
				showAlert('Failed to export PDF', 'error');
				hideLoading(button);
			},
		});
	}

	// =================================================================
	// EVENT HANDLERS
	// =================================================================

	/**
	 * Quick filter buttons
	 */
	$('.quick-filter-btn').on('click', function () {
		const range = $(this).data('range');
		console.log(`[VMS Reports] Quick filter clicked: ${range}`);

		const today = new Date();
		let fromDate;

		switch (range) {
			case 'today':
				fromDate = today;
				break;
			case 'week':
				fromDate = new Date(today.setDate(today.getDate() - 7));
				break;
			case 'month':
				fromDate = new Date(today.setDate(today.getDate() - 30));
				break;
			case 'year':
				fromDate = new Date(today.setFullYear(today.getFullYear() - 1));
				break;
		}

		const fromDateStr = fromDate.toISOString().split('T')[0];
		const toDateStr = new Date().toISOString().split('T')[0];

		$('#from_date').val(fromDateStr);
		$('#to_date').val(toDateStr);

		// Highlight active button
		$('.quick-filter-btn')
			.removeClass('bg-brand-500 text-white')
			.addClass(
				'bg-white text-gray-700 dark:bg-gray-800 dark:text-gray-300'
			);
		$(this)
			.removeClass(
				'bg-white text-gray-700 dark:bg-gray-800 dark:text-gray-300'
			)
			.addClass('bg-brand-500 text-white');

		loadReportData();
	});

	/**
	 * Apply filter form submission
	 */
	$('#report-filter-form').on('submit', function (e) {
		e.preventDefault();
		console.log('[VMS Reports] Filter form submitted');
		loadReportData();
	});

	/**
	 * Main Export PDF button (full report)
	 */
	$('#export-pdf').on('click', function (e) {
		e.preventDefault();
		console.log('[VMS Reports] Full report export button clicked');
		exportPdf('vms_export_report_pdf');
	});

	/**
	 * Export section PDF buttons (individual sections)
	 */
	$('.export-section-btn').on('click', function (e) {
		e.preventDefault();
		const section = $(this).data('section');
		console.log(`[VMS Reports] Section export button clicked: ${section}`);

		if (section) {
			exportPdf('vms_export_section_pdf', section);
		} else {
			console.error('[VMS Reports] No section specified for export');
			showAlert('Invalid section selected', 'error');
		}
	});

	// =================================================================
	// INITIALIZATION
	// =================================================================

	console.log('[VMS Reports] Starting initialization');
	initCharts();
	loadReportData();
	console.log('[VMS Reports] Initialization complete');
}
