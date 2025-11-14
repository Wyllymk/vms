<?php
/**
 * Template part for displaying maps
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visitor_Management_System
 */
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
	<div class="flex justify-between">
		<div>
			<h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
				Client Demographics
			</h3>
			<p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
				Distribution of clients by jurisdiction
			</p>
		</div>

		<div x-data="{openDropDown: false}" class="relative h-fit">
			<button @click="openDropDown = !openDropDown"
				:class="openDropDown ? 'text-gray-700 dark:text-white' : 'text-gray-400 hover:text-gray-700 dark:hover:text-white'">
				<svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
					xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd"
						d="M10.2441 6C10.2441 5.0335 11.0276 4.25 11.9941 4.25H12.0041C12.9706 4.25 13.7541 5.0335 13.7541 6C13.7541 6.9665 12.9706 7.75 12.0041 7.75H11.9941C11.0276 7.75 10.2441 6.9665 10.2441 6ZM10.2441 18C10.2441 17.0335 11.0276 16.25 11.9941 16.25H12.0041C12.9706 16.25 13.7541 17.0335 13.7541 18C13.7541 18.9665 12.9706 19.75 12.0041 19.75H11.9941C11.0276 19.75 10.2441 18.9665 10.2441 18ZM11.9941 10.25C11.0276 10.25 10.2441 11.0335 10.2441 12C10.2441 12.9665 11.0276 13.75 11.9941 13.75H12.0041C12.9706 13.75 13.7541 12.9665 13.7541 12C13.7541 11.0335 12.9706 10.25 12.0041 10.25H11.9941Z"
						fill="" />
				</svg>
			</button>
			<div x-show="openDropDown" @click.outside="openDropDown = false"
				class="absolute right-0 z-40 w-40 p-2 space-y-1 bg-white border border-gray-200 top-full rounded-2xl shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark">
				<button
					class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
					View Details
				</button>
				<button
					class="flex w-full px-3 py-2 font-medium text-left text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
					Export Data
				</button>
			</div>
		</div>
	</div>
	<div
		class="px-4 py-6 my-6 overflow-hidden border border-gary-200 rounded-2xl bg-gray-50 dark:border-gray-800 dark:bg-gray-900 sm:px-6">
		<div id="legalClientsMap"
			class="legal-clients-map map-btn -mx-4 -my-6 h-[212px] w-[252px] 2xsm:w-[307px] xsm:w-[358px] sm:-mx-6 md:w-[668px] lg:w-[634px] xl:w-[393px] 2xl:w-[554px]">
		</div>
	</div>

	<div class="space-y-5">
		<div class="flex items-center justify-between">
			<div class="flex items-center gap-3">
				<div class="items-center w-full rounded-full max-w-8">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/country/country-01.svg"
						alt="usa" />
				</div>
				<div>
					<p class="font-semibold text-gray-800 text-theme-sm dark:text-white/90">
						United States
					</p>
					<span class="block text-gray-500 text-theme-xs dark:text-gray-400">
						1,842 Clients
					</span>
				</div>
			</div>

			<div class="flex w-full max-w-[140px] items-center gap-3">
				<div class="relative block h-2 w-full max-w-[100px] rounded-sm bg-gray-200 dark:bg-gray-800">
					<div
						class="absolute left-0 top-0 flex h-full w-[68%] items-center justify-center rounded-sm bg-brand-500 text-xs font-medium text-white">
					</div>
				</div>
				<p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
					68%
				</p>
			</div>
		</div>

		<div class="flex items-center justify-between">
			<div class="flex items-center gap-3">
				<div class="items-center w-full rounded-full max-w-8">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/country/country-02.svg"
						alt="france" />
				</div>
				<div>
					<p class="font-semibold text-gray-800 text-theme-sm dark:text-white/90">
						France
					</p>
					<span class="block text-gray-500 text-theme-xs dark:text-gray-400">
						792 Clients
					</span>
				</div>
			</div>

			<div class="flex w-full max-w-[140px] items-center gap-3">
				<div class="relative block h-2 w-full max-w-[100px] rounded-sm bg-gray-200 dark:bg-gray-800">
					<div
						class="absolute left-0 top-0 flex h-full w-[29%] items-center justify-center rounded-sm bg-brand-500 text-xs font-medium text-white">
					</div>
				</div>
				<p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
					29%
				</p>
			</div>
		</div>

		<div class="flex items-center justify-between">
			<div class="flex items-center gap-3">
				<div class="items-center w-full rounded-full max-w-8">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/country/country-03.svg"
						alt="eu" />
				</div>
				<div>
					<p class="font-semibold text-gray-800 text-theme-sm dark:text-white/90">
						European Union
					</p>
					<span class="block text-gray-500 text-theme-xs dark:text-gray-400">
						421 Clients
					</span>
				</div>
			</div>

			<div class="flex w-full max-w-[140px] items-center gap-3">
				<div class="relative block h-2 w-full max-w-[100px] rounded-sm bg-gray-200 dark:bg-gray-800">
					<div
						class="absolute left-0 top-0 flex h-full w-[15%] items-center justify-center rounded-sm bg-brand-500 text-xs font-medium text-white">
					</div>
				</div>
				<p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
					15%
				</p>
			</div>
		</div>
	</div>
</div>