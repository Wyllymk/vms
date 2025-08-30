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

// Import JavaScript libraries
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

// Import chart modules
import chart01 from './charts/chart-01';
import chart02 from './charts/chart-02';
import chart03 from './charts/chart-03';
import './image-resize';

// Initialize Alpine.js with persist plugin
Alpine.plugin(persist);

window.Alpine = Alpine;

// ✅ Start Alpine once
document.addEventListener('DOMContentLoaded', () => {
	Alpine.start();
});

// Document Loaded
document.addEventListener('DOMContentLoaded', () => {
	// Initialize charts
	chart01();
	chart02();
	chart03();

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

document
	.getElementById('profile_picture')
	.addEventListener('change', function (e) {
		const file = e.target.files[0];
		const preview = document.getElementById('profile-preview');
		const badge = document.getElementById('photo-selected');

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
