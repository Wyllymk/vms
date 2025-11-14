import globals from 'globals';
import js from '@eslint/js';
import prettier from 'eslint-config-prettier';

export default [
	{
		ignores: ['**/*.min.js', '**/vendor/'],
	},
	{
		files: ['**/*.js'],
		languageOptions: {
			ecmaVersion: 'latest',
		},
		rules: {
			...js.configs.recommended.rules,
			...prettier.rules,
		},
	},
	{
		files: ['javascript/**/*.js'],
		languageOptions: {
			globals: {
				...globals.browser,
				wp: 'readonly',
				jQuery: 'readonly',
				$: 'readonly',
				// Add your WordPress localized script variables
				vms_script_ajax: 'readonly',
				wpApiSettings: 'readonly',
				vmsAjax: 'readonly',
				ajaxurl: 'readonly', // Common WordPress AJAX variable
			},
		},
	},
	{
		files: ['node_scripts/*.js', 'tailwind/*.js', 'postcss.config.js'],
		languageOptions: {
			globals: {
				...globals.node,
			},
		},
	},
];
