/** @type {import('tailwindcss').Config} */
export default {
	content: [
		// Root level PHP files
		'./*.php',

		// Theme folder and all subfolders
		'./theme/**/*.php',

		// Specific subfolders in theme
		'./theme/page-templates/**/*.php',
		'./theme/template-parts/**/*.php',
		'./theme/template/**/*.php',
		'./theme/parts/**/*.php',
		'./theme/content/**/*.php',

		// JavaScript files
		'./javascript/**/*.js',
		'./theme/**/*.js',

		// Any other PHP files in root
		'./**/*.php',
	],
	theme: {
		extend: {
			// Your customizations here
		},
	},
	plugins: [],
};
