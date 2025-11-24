/**
 * Front-end JavaScript
 *
 * The JavaScript code you place here will be processed by esbuild. The output
 * file will be created at `../theme/js/script.min.js` and enqueued in
 * `../theme/functions.php`.
 *
 * For esbuild documentation, please see:
 * https://esbuild.github.io/
 */
// Import CSS files
import 'jsvectormap/dist/jsvectormap.min.css';
import 'flatpickr/dist/flatpickr.min.css';

// Import JavaScript libraries
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import flatpickr from 'flatpickr';

// Import chart modules
import chart02 from './charts/chart-02';
import chart07 from './charts/chart-07';
import './image-resize';

// Initialize Alpine.js with persist plugin
// ✅ Start Alpine once
document.addEventListener('DOMContentLoaded', () => {
	// Initialize Alpine
	window.Alpine = Alpine;
	Alpine.plugin(persist);
	Alpine.start();

	// Initialize Flatpickr date pickers
	initDatePickers();
});

function initDatePickers() {
	const datePickers = document.querySelectorAll('.datepickerTwo');

	datePickers.forEach((picker) => {
		flatpickr(picker, {
			dateFormat: 'Y-m-d',
			minDate: 'today',
			defaultDate: 'today',
			monthSelectorType: 'static',
			clickOpens: true,
			allowInput: false,
			onReady: function (selectedDates, dateStr, instance) {
				instance.calendarContainer.style.zIndex = '999999';
			},
			onOpen: function (selectedDates, dateStr, instance) {
				instance.calendarContainer.style.zIndex = '999999';
			},
		});
	});

	const visitDatePicker = document.getElementById('visit-date-picker');
	if (visitDatePicker) {
		flatpickr(visitDatePicker, {
			dateFormat: 'Y-m-d',
			minDate: 'today',
			defaultDate: 'today',
			monthSelectorType: 'static',
			clickOpens: true,
			allowInput: false,
			onReady: function (selectedDates, dateStr, instance) {
				instance.calendarContainer.style.zIndex = '999999';
			},
			onOpen: function (selectedDates, dateStr, instance) {
				instance.calendarContainer.style.zIndex = '999999';
			},
			onChange: function (selectedDates, dateStr, instance) {
				// Calendar closes automatically after selection
			},
		});
	}
}

window.initDatePickers = initDatePickers;
window.flatpickr = flatpickr;

// Document Loaded
document.addEventListener('DOMContentLoaded', () => {
	// Initialize charts
	chart02();
	chart07();

	// Update copyright year
	const year = document.getElementById('year');
	if (year) {
		year.textContent = new Date().getFullYear();
	}
});

// For Copy//
document.addEventListener('DOMContentLoaded', () => {
	const copyInput = document.getElementById('copy-input');
	if (copyInput) {
		// Select the copy button and input field
		const copyButton = document.getElementById('copy-button');
		const copyText = document.getElementById('copy-text');
		const websiteInput = document.getElementById('website-input');

		// Event listener for the copy button
		copyButton.addEventListener('click', () => {
			// Copy the input value to the clipboard
			navigator.clipboard.writeText(websiteInput.value).then(() => {
				// Change the text to "Copied"
				copyText.textContent = 'Copied';

				// Reset the text back to "Copy" after 2 seconds
				setTimeout(() => {
					copyText.textContent = 'Copy';
				}, 2000);
			});
		});
	}
});

document.addEventListener('DOMContentLoaded', function () {
	const searchInput = document.getElementById('search-input');
	const searchButton = document.getElementById('search-button');

	// Function to focus the search input
	function focusSearchInput() {
		if (searchInput) {
			searchInput.focus();
		}
	}

	// Add click event listener to the search button (only if it exists)
	if (searchButton) {
		searchButton.addEventListener('click', focusSearchInput);
	}

	// Cmd+K or Ctrl+K shortcut
	document.addEventListener('keydown', function (event) {
		if ((event.metaKey || event.ctrlKey) && event.key === 'k') {
			event.preventDefault();
			focusSearchInput();
		}
	});

	// "/" shortcut
	document.addEventListener('keydown', function (event) {
		if (event.key === '/' && document.activeElement !== searchInput) {
			event.preventDefault();
			focusSearchInput();
		}
	});
});

const fileInput = document.getElementById('profile_picture');
const preview = document.getElementById('profile-preview');
const badge = document.getElementById('photo-selected');

if (fileInput && preview && badge) {
	fileInput.addEventListener('change', function (e) {
		const file = e.target.files[0];

		if (file) {
			// Show image preview
			const reader = new FileReader();
			reader.onload = function (ev) {
				preview.src = ev.target.result;
			};
			reader.readAsDataURL(file);

			// Show "selected" badge
			badge.classList.remove('hidden');
		} else {
			badge.classList.add('hidden');
		}
	});
}

document.addEventListener('alpine:init', () => {
	Alpine.store('clubModal', {
		isClubEditModal: false,
		open() {
			this.isClubEditModal = true;
		},
		close() {
			this.isClubEditModal = false;
		},
	});
});

/**
 * Initialize contact picker for phone input fields
 * @param {string} inputSelector - CSS selector for phone input
 * @param {string} buttonSelector - CSS selector for picker button
 */
function initContactPicker(inputSelector, buttonSelector) {
	const phoneInput = document.querySelector(inputSelector);
	const pickBtn = document.querySelector(buttonSelector);

	if (!pickBtn || !phoneInput) return;

	const isSupported = 'contacts' in navigator && 'ContactsManager' in window;

	if (!isSupported) {
		pickBtn.style.display = 'none';
		return;
	}

	pickBtn.addEventListener('click', async () => {
		try {
			const props = ['tel', 'name'];
			const opts = { multiple: false };
			const contacts = await navigator.contacts.select(props, opts);

			if (
				Array.isArray(contacts) &&
				contacts.length > 0 &&
				contacts[0].tel &&
				Array.isArray(contacts[0].tel) &&
				contacts[0].tel.length > 0
			) {
				phoneInput.value = contacts[0].tel[0];
			} else {
				console.warn('No valid phone number found.');
			}
		} catch (err) {
			console.warn('Contact selection skipped or denied:', err);
		}
	});
}

// Auto-initialize on DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
	// Find all phone inputs with contact picker buttons
	const phoneInputs = document.querySelectorAll('[data-contact-picker]');

	phoneInputs.forEach((input) => {
		const inputId = input.id;
		const buttonId = input.dataset.contactPicker;

		if (inputId && buttonId) {
			initContactPicker(`#${inputId}`, `#${buttonId}`);
		}
	});
});
