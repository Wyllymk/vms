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
import 'dropzone/dist/dropzone.css';
import 'flatpickr/dist/flatpickr.min.css';

// Import JavaScript libraries
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import flatpickr from 'flatpickr';
import Dropzone from 'dropzone';

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

	// Initialize flatpickr
	initDatePicker();

	// Initialize Dropzone
	initDropzone();
});

/**
 * Initialize flatpickr date picker
 */
function initDatePicker() {
	flatpickr('.datepicker', {
		mode: 'range',
		static: true,
		monthSelectorType: 'static',
		dateFormat: 'M j, Y',
		defaultDate: [new Date().setDate(new Date().getDate() - 6), new Date()],
		prevArrow:
			'<svg class="stroke-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.25 6L9 12.25L15.25 18.5" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		nextArrow:
			'<svg class="stroke-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.75 19L15 12.75L8.75 6.5" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		onReady: (selectedDates, dateStr, instance) => {
			instance.element.value = dateStr.replace('to', '-');
			const customClass = instance.element.getAttribute('data-class');
			if (customClass) {
				instance.calendarContainer.classList.add(customClass);
			}
		},
		onChange: (selectedDates, dateStr, instance) => {
			instance.element.value = dateStr.replace('to', '-');
		},
	});
}

/**
 * Initialize Dropzone file upload
 */
function initDropzone() {
	const dropzoneElement = document.getElementById('demo-upload');

	if (dropzoneElement) {
		// Disable auto-discover to prevent conflicts
		Dropzone.autoDiscover = false;

		new Dropzone(dropzoneElement, {
			url: '/file/post', // Your upload endpoint
			paramName: 'file', // The name that will be used to transfer the file
			maxFilesize: 5, // MB
			acceptedFiles: 'image/*,.pdf,.doc,.docx,.xls,.xlsx',
			addRemoveLinks: true,
			dictDefaultMessage: 'Drop files here or click to upload',
			dictFallbackMessage:
				'Your browser does not support drag and drop file uploads.',
			dictFileTooBig:
				'File is too big ({{filesize}}MB). Max filesize: {{maxFilesize}}MB.',
			dictInvalidFileType:
				'Invalid file type. Only images, PDFs, and Office documents are allowed.',
			dictResponseError: 'Server responded with {{statusCode}} code.',
			dictCancelUpload: 'Cancel upload',
			dictUploadCanceled: 'Upload canceled',
			dictRemoveFile: 'Remove file',
			dictMaxFilesExceeded: 'You can only upload {{maxFiles}} files.',
			init: function () {
				this.on('success', function (file, response) {
					console.log('File uploaded successfully:', file, response);
				});
				this.on('error', function (file, errorMessage) {
					console.error('Upload error:', errorMessage);
				});
			},
		});
	}
}

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
		searchInput.focus();
	}

	// Add click event listener to the search button
	searchButton.addEventListener('click', focusSearchInput);

	// Add keyboard event listener for Cmd+K (Mac) or Ctrl+K (Windows/Linux)
	document.addEventListener('keydown', function (event) {
		if ((event.metaKey || event.ctrlKey) && event.key === 'k') {
			event.preventDefault(); // Prevent the default browser behavior
			focusSearchInput();
		}
	});

	// Add keyboard event listener for "/" key
	document.addEventListener('keydown', function (event) {
		if (event.key === '/' && document.activeElement !== searchInput) {
			event.preventDefault(); // Prevent the "/" character from being typed
			focusSearchInput();
		}
	});
});

const draggables = document.querySelectorAll('.task');
const droppables = document.querySelectorAll('.swim-lane');
draggables.forEach((task) => {
	task.addEventListener('dragstart', () => {
		task.classList.add('is-dragging');
	});
	task.addEventListener('dragend', () => {
		task.classList.remove('is-dragging');
	});
});
droppables.forEach((zone) => {
	zone.addEventListener('dragover', (e) => {
		e.preventDefault();
		const bottomTask = insertAboveTask(zone, e.clientY);
		const curTask = document.querySelector('.is-dragging');
		if (!bottomTask) {
			zone.appendChild(curTask);
		} else {
			zone.insertBefore(curTask, bottomTask);
		}
	});
});
const insertAboveTask = (zone, mouseY) => {
	const els = zone.querySelectorAll('.task:not(.is-dragging)');
	let closestTask = null;
	let closestOffset = Number.NEGATIVE_INFINITY;
	els.forEach((task) => {
		const { top } = task.getBoundingClientRect();
		const offset = mouseY - top;
		if (offset < 0 && offset > closestOffset) {
			closestOffset = offset;
			closestTask = task;
		}
	});
	return closestTask;
};
