<?php
/**
 * The template for displaying Front Page
 *
 * This is the template that displays front page by default. Please note that
 * this is the WordPress construct of pages: specifically, posts with a post
 * type of `page`.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Funded_Way
 */
// Exit if accessed directly
defined('ABSPATH') || exit();

get_header();
?>

<section id="primary" class="z-20 -mt-16 text-white bg-black why-2 why">
    <main id="main">

        <!-- Hero Section -->
        <section id="home"
            class="relative z-10 flex flex-col justify-center w-full px-4 overflow-hidden bg-center bg-no-repeat bg-cover hero h-520 lg:h-320 xl:h-300 flex-nowrap align-center md:min-h-screen bg-fw-hero will-change-auto">
            <div
                class="flex flex-col items-center justify-center space-y-4 text-center text-white md:p-8 mt-30 lg:mt-20 xl:space-y-4">
                <!-- Hero Content -->
                <div class="w-fit">
                    <div class="relative flex justify-center gap-2 mb-4">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/star.png" alt="star" class="">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/star.png" alt="star" class="">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/star.png" alt="star" class="">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/star.png" alt="star" class="">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/star.png" alt="star" class="">
                    </div>
                    <div class="relative flex justify-center gap-2 mb-4">
                        <p class="text-base font-normal md:text-lg font-space-mono">
                            <?php esc_html_e('TRUST PILOT:', 'funded-way'); ?>
                            <span class="text-lg font-semibold">
                                <?php esc_html_e('4.45/5.00 REVIEWS', 'funded-way'); ?>
                            </span>
                        </p>
                    </div>
                </div>
                <h1 class="text-3xl font-bold leading-tight lg:text-5xl">
                    <?php esc_html_e('Get funded up to $100,000. No hidden rules.', 'funded-way'); ?><br>
                    <?php esc_html_e('No risk to your own capital. Payouts in 24–48 hours.', 'funded-way'); ?>
                </h1>
                <p class="text-base md:text-lg xl:w-2/3 text-white/75">
                    <?php esc_html_e(
                    	'Get funded, trade with freedom, and scale without restrictions.',
                    	'funded-way',
                    ); ?>
                </p>
                <div class="flex items-center space-x-4">
                    <a href="<?php echo is_front_page() ? '#pricing' : esc_url(site_url('/')) . '#pricing'; ?>"
                        class="flex items-center justify-between px-8 py-3 text-base font-medium text-black transition duration-300 ease-in-out bg-white rounded-full whitespace-nowrap md:text-lg hover:drop-shadow-white-glow">
                        <?php esc_html_e('Start Challenge Now', 'funded-way'); ?>
                    </a>
                </div>
            </div>
            <!-- Middle cards section -->
            <div
                class="flex flex-col items-center justify-center w-full gap-6 mt-10 hero-card-section lg:flex-row lg:items-start lg:mt-0">
                <div class="flex flex-col w-full max-w-sm space-y-4">
                    <div
                        class="relative flex items-center self-stretch justify-between gap-6 px-4 py-3 overflow-hidden border rounded-lg card-left-top border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                        <div class="flex flex-col">
                            <span class="text-base font-medium text-white">
                                <?php esc_html_e('Payment Made', 'funded-way'); ?>
                            </span>
                            <span class="text-sm text-white/75">
                                <?php esc_html_e('Your received your payout', 'funded-way'); ?>
                            </span>
                        </div>
                        <div
                            class="flex items-center self-stretch gap-6 px-4 py-2 text-white border rounded-md whitespace-nowrap border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                            <?php esc_html_e('+$5,000', 'funded-way'); ?>
                        </div>
                        <!-- Glowing centered bottom border -->
                        <div
                            class="absolute left-0 top-1/2 -translate-y-1/2 h-1/2 w-0.5 bg-white/80 rounded-full shadow-[0_2px_20px_5px_rgba(255,255,255,0.4)]">
                        </div>
                    </div>
                    <div
                        class="flex flex-col items-center self-stretch justify-between gap-6 border rounded-lg card-left-bottom h-50 border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                        <div class="flex items-center justify-between w-full px-4 py-3 pb-1 border-b border-white/10">
                            <span class="text-base font-medium text-white">
                                <?php esc_html_e('Profit Target', 'funded-way'); ?>
                            </span>
                            <div
                                class="flex items-center px-2 py-0.5 gap-3 rounded-full text-white border border-white/10 bg-gradient-to-b from-white/25 to-white/20 backdrop-blur-md">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/check-circle.png"
                                    alt="check" class="">
                                <?php esc_html_e('Completed', 'funded-way'); ?>
                            </div>
                        </div>
                        <div class="w-full max-w-md gap-3 px-4 py-2 mb-5">
                            <div class="flex justify-between mb-1 text-sm text-white">
                                <span>
                                    <p class="font-semibold text-white">
                                        <?php esc_html_e('$3500', 'funded-way'); ?>
                                    </p>
                                    <p class="text-zinc-400">
                                        <?php esc_html_e('/$5000', 'funded-way'); ?>
                                    </p>
                                </span>
                                <p class="text-white/80">
                                    <?php esc_html_e('+12', 'funded-way'); ?>
                                </p>
                            </div>
                            <div class="w-full overflow-hidden rounded-full h-7 bg-white/10">
                                <div class="h-full bg-white rounded-full" style="width: 80%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="relative flex items-start w-full max-w-sm gap-6 px-4 py-3 overflow-hidden border rounded-lg card-middle h-70 border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                    <div class="flex flex-col">
                        <div class="flex items-center px-2 py-0.5 gap-3 text-white/75">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/banknotes.png"
                                alt="banknotes" class="">
                            <?php esc_html_e('Account Balance', 'funded-way'); ?>
                        </div>
                        <h5 class="text-2xl font-medium text-white px-2 py-0.5">
                            <?php esc_html_e('$102,485.59', 'funded-way'); ?>
                        </h5>
                    </div>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/account-balance.png"
                        alt="account-balance" class="absolute bottom-0">
                </div>
                <div class="flex flex-col w-full max-w-sm space-y-4">
                    <div class="flex items-center self-stretch w-full gap-4">
                        <div
                            class="relative flex flex-col items-center justify-between w-full px-4 py-3 pb-1 overflow-hidden border-b rounded-lg card-right-top-1 border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                            <h5 class="text-3xl font-semibold text-white px-2 py-0.5">
                                <?php esc_html_e('190+', 'funded-way'); ?>
                            </h5>
                            <p class="text-base font-medium text-white/75 px-2 py-0.5">
                                <?php esc_html_e('Countries', 'funded-way'); ?>
                            </p>
                            <!-- Glowing centered bottom border -->
                            <div
                                class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1/3 h-0.5 bg-white/80 rounded-full shadow-[0_1px_20px_10px_rgba(255,255,255,0.3)]">
                            </div>
                        </div>
                        <div
                            class="relative flex flex-col items-center justify-between w-full px-4 py-3 pb-1 overflow-hidden border-b rounded-lg card-right-top-2 border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                            <h5 class="text-3xl font-semibold text-white px-2 py-0.5">
                                <?php esc_html_e('24+', 'funded-way'); ?>
                            </h5>
                            <p class="text-base font-medium text-white/75 px-2 py-0.5 whitespace-nowrap">
                                <?php esc_html_e('Traded Assets', 'funded-way'); ?>
                            </p>
                            <!-- Glowing centered bottom border -->
                            <div
                                class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1/3 h-0.5 bg-white/80 rounded-full shadow-[0_1px_20px_10px_rgba(255,255,255,0.3)]">
                            </div>
                        </div>
                    </div>
                    <div
                        class="self-stretch w-full overflow-hidden border rounded-lg card-right-bottom h-45 border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/graph.png" alt="graph"
                            class="">
                    </div>
                </div>
            </div>
            <!-- Bottom Section -->
            <div
                class="flex flex-col items-center justify-center w-full gap-4 mt-10 lg:flex-row lg:mt-40 cards-section">
                <div
                    class="flex w-full max-w-sm overflow-hidden bg-center bg-no-repeat bg-cover card rounded-xl backdrop-blur-md bg-fw-card-bg will-change-auto">
                    <div class="flex flex-col items-start justify-center gap-2 px-4 py-2 text-white">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/security.png" alt="security"
                            class="">
                        <h6 class="mt-1 text-lg font-semibold text-white font-space-grotesk">
                            <?php esc_html_e('Guaranteed Payouts', 'funded-way'); ?>
                        </h6>
                        <p class="text-xs text-white/75">
                            <?php esc_html_e(
                            	'With us, you’ll receive stable rewards for your stable trading',
                            	'funded-way',
                            ); ?>
                        </p>
                    </div>
                </div>
                <div
                    class="flex w-full max-w-sm overflow-hidden bg-center bg-no-repeat bg-cover card rounded-xl bg-fw-card-bg will-change-auto">
                    <div class="flex flex-col items-start justify-center gap-2 px-4 py-2 text-white">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/calendar.png" alt="calendar"
                            class="">
                        <h6 class="mt-1 text-lg font-semibold text-white font-space-grotesk">
                            <?php esc_html_e('Fast Payout', 'funded-way'); ?>
                        </h6>
                        <p class="text-xs text-white/75">
                            <?php esc_html_e('It takes up to 24 hours to process a payment.', 'funded-way'); ?>
                        </p>
                    </div>
                </div>
                <div
                    class="flex w-full max-w-sm overflow-hidden bg-center bg-no-repeat bg-cover card rounded-xl bg-fw-card-bg will-change-auto">
                    <div class="flex flex-col items-start justify-center gap-2 px-4 py-2 text-white">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/wallet.png" alt="wallet"
                            class="">
                        <h6 class="mt-1 text-lg font-semibold text-white font-space-grotesk">
                            <?php esc_html_e('Up to $100,000 in funding', 'funded-way'); ?>
                        </h6>
                        <p class="text-xs text-white/75">
                            <?php esc_html_e('We provide financing for you up to $100,000', 'funded-way'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing"
            class="flex justify-center w-full p-4 mx-auto my-20 overflow-hidden max-w-7xl align-center">
            <div
                class="flex flex-col items-center justify-center w-full p-2 mx-auto space-y-10 shadow-lg max-w-7xl bg-fw-slate-blue backdrop-blur-lg rounded-xl md:p-6">
                <!-- Headings -->
                <div class="flex flex-col items-center justify-center w-full space-y-4">
                    <div class="w-fit mb-5 border border-white/10 px-2 py-0.5 rounded-full bg-white/10">
                        <h6 class="text-sm text-center text-white font-space-grotesk">
                            <?php esc_html_e('PRICING', 'funded-way'); ?>
                        </h6>
                    </div>
                    <div class="flex flex-col items-center justify-center w-full gap-2">
                        <h2 class="text-4xl font-bold leading-tight text-center md:text-5xl font-space-grotesk">
                            <?php esc_html_e('Find a Challenge Fast', 'funded-way'); ?>
                        </h2>
                        <p class="text-xs text-center text-white/75 md:text-base">
                            <?php esc_html_e('Use the filter to find the right challenge instantly', 'funded-way'); ?>
                        </p>
                    </div>
                </div>
                <!-- Plans section -->
                <div class="flex flex-col w-full space-y-10" x-data="plans" x-init="init()">
                    <!-- Updated buttons section with click handlers -->
                    <div class="flex flex-col items-center justify-center w-full">
                        <!-- Challenge Type -->
                        <div class="flex flex-col w-full flex-nowrap">
                            <div class="flex items-center justify-center flex-1">
                                <div
                                    class="w-fit flex font-space-grotesk border border-black/5 bg-black/10 rounded-full p-0.5 mt-2 shadow-[inset_0px_-2px_13px_rgba(132,187,255,0.1),0px_10px_50px_rgba(0,0,0,0.05)] backdrop-blur-md">
                                    <!-- 1 STEP Button -->
                                    <a @click="setActive(1, 'one-step')"
                                        :class="isActive(1, 'one-step') ? 'bg-white text-black' : 'text-white hover:bg-white hover:text-black'"
                                        class="flex items-center justify-center flex-grow px-4 py-2 text-sm transition-colors duration-300 ease-in-out rounded-full cursor-pointer md:px-8 font-space-grotesk whitespace-nowrap md:text-base">
                                        1-STEP
                                    </a>
                                    <!-- 2 STEP Button -->
                                    <a @click="setActive(1, 'two-step')"
                                        :class="isActive(1, 'two-step') ? 'bg-white text-black' : 'text-white hover:bg-white hover:text-black'"
                                        class="flex items-center justify-center flex-grow px-4 py-2 text-sm transition-colors duration-300 ease-in-out rounded-full cursor-pointer md:px-8 font-space-grotesk whitespace-nowrap md:text-base">
                                        2-STEP
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Account Type -->
                        <div class="flex flex-col w-full flex-nowrap">
                            <div class="flex items-center justify-center flex-1">
                                <div
                                    class="w-fit flex flex-wrap md:flex-nowrap items-center justify-center font-space-grotesk border border-black/5 bg-black/10 rounded-full p-0.5 mt-2 shadow-[inset_0px_-2px_13px_rgba(132,187,255,0.1),0px_10px_50px_rgba(0,0,0,0.05)] backdrop-blur-md">
                                    <!-- Dynamic Account Type Buttons -->
                                    <template x-for="accountType in getAccountTypes()" :key="accountType">
                                        <div class="relative">
                                            <!-- Badge for 'Starter Way' only -->
                                            <template x-if="accountType === 'Starter Way'">
                                                <div
                                                    class="whitespace-nowrap absolute -top-2 left-1/2 -translate-x-1/2 bg-blue-600 text-white text-[10px] px-2 py-[1px] rounded-full z-10">
                                                    Best for Beginners
                                                </div>
                                            </template>

                                            <!-- Original Button -->
                                            <a @click="setActive(2, accountType)"
                                                :class="isActive(2, accountType) ? 'bg-white text-black' : 'text-white hover:bg-white hover:text-black'"
                                                class="flex items-center justify-center flex-grow px-4 py-2 text-sm transition-colors duration-300 ease-in-out rounded-full cursor-pointer md:px-8 font-space-grotesk whitespace-nowrap md:text-base"
                                                x-text="accountType">
                                            </a>
                                        </div>
                                    </template>

                                </div>
                            </div>
                        </div>

                        <!-- Account Size -->
                        <div class="flex flex-col w-full flex-nowrap">
                            <div class="flex items-center justify-center flex-1">
                                <div
                                    class="w-fit flex-wrap md:flex-nowrap flex font-space-grotesk border border-black/5 bg-black/10 rounded-full p-0.5 mt-2 shadow-[inset_0px_-2px_13px_rgba(132,187,255,0.1),0px_10px_50px_rgba(0,0,0,0.05)] backdrop-blur-md">
                                    <!-- $10K Button -->
                                    <a @click="activeAmount = '$10,000'"
                                        :class="activeAmount === '$10,000' ? 'bg-white text-black' : 'text-white hover:bg-white hover:text-black'"
                                        x-show="isAmountVisible('$10,000')"
                                        class="flex items-center justify-center flex-grow px-3 py-2 text-sm transition-colors duration-300 ease-in-out rounded-full cursor-pointer md:px-8 font-space-grotesk whitespace-nowrap md:text-base">
                                        $10,000
                                    </a>
                                    <!-- $25K Button -->
                                    <a @click="activeAmount = '$25,000'"
                                        :class="activeAmount === '$25,000' ? 'bg-white text-black' : 'text-white hover:bg-white hover:text-black'"
                                        x-show="isAmountVisible('$25,000')"
                                        class="flex items-center justify-center flex-grow px-3 py-2 text-sm transition-colors duration-300 ease-in-out rounded-full cursor-pointer md:px-8 font-space-grotesk whitespace-nowrap md:text-base">
                                        $25,000
                                    </a>
                                    <!-- $50K Button -->
                                    <a @click="activeAmount = '$50,000'"
                                        :class="activeAmount === '$50,000' ? 'bg-white text-black' : 'text-white hover:bg-white hover:text-black'"
                                        x-show="isAmountVisible('$50,000')"
                                        class="flex items-center justify-center flex-grow px-3 py-2 text-sm transition-colors duration-300 ease-in-out rounded-full cursor-pointer md:px-8 font-space-grotesk whitespace-nowrap md:text-base">
                                        $50,000
                                    </a>
                                    <!-- $100K Button -->
                                    <a @click="activeAmount = '$100,000'"
                                        :class="activeAmount === '$100,000' ? 'bg-white text-black' : '!text-white hover:bg-white hover:text-black'"
                                        x-show="isAmountVisible('$100,000')"
                                        class="flex items-center justify-center flex-grow px-3 py-2 text-sm transition-colors duration-300 ease-in-out rounded-full cursor-pointer md:px-8 font-space-grotesk whitespace-nowrap md:text-base">
                                        $100,000
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Pricing Display -->
                    <div class="flex flex-col items-center justify-between bg-black/10 rounded-2xl">
                        <h2 class="my-4 text-2xl font-medium leading-tight text-center md:text-4xl font-space-grotesk">
                            <?php esc_html_e('Conditions of passage', 'funded-way'); ?>
                        </h2>
                        <!-- Dynamic Plan Columns -->
                        <div
                            class="flex flex-col items-stretch justify-center w-full max-w-6xl p-3 space-x-0 space-y-4 duration-300 ease-in-out dynamic-plan-columns md:flex-row md:space-x-4 h-fit backdrop-blur-lg md:w-full">
                            <template x-for="column in getColumnsToDisplay()" :key="column">
                                <div
                                    class="w-full p-6 space-y-4 plan-card h-fit md:w-1/3 backdrop-blur-md bg-white/10 rounded-xl">
                                    <h3 x-text="column === 'step1' ? 'Phase 1' : column === 'step2' ? 'Phase 2' : column.charAt(0).toUpperCase() + column.slice(1)"
                                        class="text-center"></h3>
                                    <template x-for="[key, value] in Object.entries(getColumnData(column))">
                                        <div class="flex flex-col space-y-2">
                                            <div class="flex items-center justify-between space-x-2">
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-fit">
                                                        <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M14.6666 8.50004C14.6666 12.182 11.6819 15.1667 7.99992 15.1667C4.31792 15.1667 1.33325 12.182 1.33325 8.50004C1.33325 4.81804 4.31792 1.83337 7.99992 1.83337C11.6819 1.83337 14.6666 4.81804 14.6666 8.50004ZM7.33325 5.83337C7.33325 6.01018 7.40349 6.17975 7.52851 6.30478C7.65354 6.4298 7.82311 6.50004 7.99992 6.50004H8.00525C8.18206 6.50004 8.35163 6.4298 8.47666 6.30478C8.60168 6.17975 8.67192 6.01018 8.67192 5.83337C8.67192 5.65656 8.60168 5.48699 8.47666 5.36197C8.35163 5.23695 8.18206 5.16671 8.00525 5.16671H7.99992C7.82311 5.16671 7.65354 5.23695 7.52851 5.36197C7.40349 5.48699 7.33325 5.65656 7.33325 5.83337ZM7.99992 11.8334C8.17673 11.8334 8.3463 11.7631 8.47132 11.6381C8.59635 11.5131 8.66658 11.3435 8.66658 11.1667V7.83337C8.66658 7.65656 8.59635 7.48699 8.47132 7.36197C8.3463 7.23695 8.17673 7.16671 7.99992 7.16671C7.82311 7.16671 7.65354 7.23695 7.52851 7.36197C7.40349 7.48699 7.33325 7.65656 7.33325 7.83337V11.1667C7.33325 11.3435 7.40349 11.5131 7.52851 11.6381C7.65354 11.7631 7.82311 11.8334 7.99992 11.8334Z"
                                                                fill="white" />
                                                        </svg>
                                                    </div>
                                                    <h3 class="text-base" x-text="key"></h3>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <h3 class="text-base" x-text="value"></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                    <!-- Cards section -->
                    <div class="flex flex-col items-center justify-between w-full gap-5 md:flex-row">
                        <div class="flex flex-col w-full gap-2 p-3 md:w-2/3 bg-black/10 rounded-2xl">
                            <h2
                                class="mb-2 text-xl font-medium leading-tight text-center md:text-2xl font-space-grotesk">
                                <?php esc_html_e('Basic Conditions', 'funded-way'); ?>
                            </h2>
                            <div class="flex flex-wrap justify-center w-full gap-2 md:flex-nowrap">
                                <!-- Card -->
                                <div class="flex w-full gap-2 p-2 max-w-3xs bg-white/10 rounded-3xl">
                                    <div class="flex flex-col items-center justify-center bg-white w-15 rounded-2xl">
                                        <p class="text-fw-indigo font-space-grotesk">
                                            <?php esc_html_e('24%', 'funded-way'); ?>
                                        </p>
                                    </div>
                                    <div class="flex flex-col w-full space-y-2">
                                        <div class="flex flex-row items-center w-full space-x-4">
                                            <p class="text-white font-space-grotesk">
                                                <?php esc_html_e('6% profit target', 'funded-way'); ?>
                                            </p>
                                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M14.6666 8.50004C14.6666 12.182 11.6819 15.1667 7.99992 15.1667C4.31792 15.1667 1.33325 12.182 1.33325 8.50004C1.33325 4.81804 4.31792 1.83337 7.99992 1.83337C11.6819 1.83337 14.6666 4.81804 14.6666 8.50004ZM7.33325 5.83337C7.33325 6.01018 7.40349 6.17975 7.52851 6.30478C7.65354 6.4298 7.82311 6.50004 7.99992 6.50004H8.00525C8.18206 6.50004 8.35163 6.4298 8.47666 6.30478C8.60168 6.17975 8.67192 6.01018 8.67192 5.83337C8.67192 5.65656 8.60168 5.48699 8.47666 5.36197C8.35163 5.23695 8.18206 5.16671 8.00525 5.16671H7.99992C7.82311 5.16671 7.65354 5.23695 7.52851 5.36197C7.40349 5.48699 7.33325 5.65656 7.33325 5.83337ZM7.99992 11.8334C8.17673 11.8334 8.3463 11.7631 8.47132 11.6381C8.59635 11.5131 8.66658 11.3435 8.66658 11.1667V7.83337C8.66658 7.65656 8.59635 7.48699 8.47132 7.36197C8.3463 7.23695 8.17673 7.16671 7.99992 7.16671C7.82311 7.16671 7.65354 7.23695 7.52851 7.36197C7.40349 7.48699 7.33325 7.65656 7.33325 7.83337V11.1667C7.33325 11.3435 7.40349 11.5131 7.52851 11.6381C7.65354 11.7631 7.82311 11.8334 7.99992 11.8334Z"
                                                    fill="white" />
                                            </svg>
                                        </div>
                                        <p class="text-xs text-white/75 font-space-grotesk">
                                            <?php esc_html_e('$6,000 profit target to pass', 'funded-way'); ?>
                                        </p>
                                    </div>
                                </div>
                                <!-- Card -->
                                <div class="flex w-full gap-2 p-2 max-w-3xs bg-white/10 rounded-3xl">
                                    <div class="flex flex-col items-center justify-center bg-white w-15 rounded-2xl">
                                        <p class="text-fw-indigo font-space-grotesk">
                                            <?php esc_html_e('24%', 'funded-way'); ?>
                                        </p>
                                    </div>
                                    <div class="flex flex-col w-full space-y-2">
                                        <div class="flex flex-row items-center w-full space-x-4">
                                            <p class="text-white font-space-grotesk">
                                                <?php esc_html_e('6% profit target', 'funded-way'); ?>
                                            </p>
                                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M14.6666 8.50004C14.6666 12.182 11.6819 15.1667 7.99992 15.1667C4.31792 15.1667 1.33325 12.182 1.33325 8.50004C1.33325 4.81804 4.31792 1.83337 7.99992 1.83337C11.6819 1.83337 14.6666 4.81804 14.6666 8.50004ZM7.33325 5.83337C7.33325 6.01018 7.40349 6.17975 7.52851 6.30478C7.65354 6.4298 7.82311 6.50004 7.99992 6.50004H8.00525C8.18206 6.50004 8.35163 6.4298 8.47666 6.30478C8.60168 6.17975 8.67192 6.01018 8.67192 5.83337C8.67192 5.65656 8.60168 5.48699 8.47666 5.36197C8.35163 5.23695 8.18206 5.16671 8.00525 5.16671H7.99992C7.82311 5.16671 7.65354 5.23695 7.52851 5.36197C7.40349 5.48699 7.33325 5.65656 7.33325 5.83337ZM7.99992 11.8334C8.17673 11.8334 8.3463 11.7631 8.47132 11.6381C8.59635 11.5131 8.66658 11.3435 8.66658 11.1667V7.83337C8.66658 7.65656 8.59635 7.48699 8.47132 7.36197C8.3463 7.23695 8.17673 7.16671 7.99992 7.16671C7.82311 7.16671 7.65354 7.23695 7.52851 7.36197C7.40349 7.48699 7.33325 7.65656 7.33325 7.83337V11.1667C7.33325 11.3435 7.40349 11.5131 7.52851 11.6381C7.65354 11.7631 7.82311 11.8334 7.99992 11.8334Z"
                                                    fill="white" />
                                            </svg>
                                        </div>
                                        <p class="text-xs text-white/75 font-space-grotesk">
                                            <?php esc_html_e('$6,000 profit target to pass', 'funded-way'); ?>
                                        </p>
                                    </div>
                                </div>
                                <!-- Card -->
                                <div class="flex w-full gap-2 p-2 max-w-3xs bg-white/10 rounded-3xl">
                                    <div class="flex flex-col items-center justify-center bg-white w-15 rounded-2xl">
                                        <p class="text-fw-indigo font-space-grotesk">
                                            <?php esc_html_e('24%', 'funded-way'); ?>
                                        </p>
                                    </div>
                                    <div class="flex flex-col w-full space-y-2">
                                        <div class="flex flex-row items-center w-full space-x-4">
                                            <p class="text-white font-space-grotesk">
                                                <?php esc_html_e('6% profit target', 'funded-way'); ?>
                                            </p>
                                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M14.6666 8.50004C14.6666 12.182 11.6819 15.1667 7.99992 15.1667C4.31792 15.1667 1.33325 12.182 1.33325 8.50004C1.33325 4.81804 4.31792 1.83337 7.99992 1.83337C11.6819 1.83337 14.6666 4.81804 14.6666 8.50004ZM7.33325 5.83337C7.33325 6.01018 7.40349 6.17975 7.52851 6.30478C7.65354 6.4298 7.82311 6.50004 7.99992 6.50004H8.00525C8.18206 6.50004 8.35163 6.4298 8.47666 6.30478C8.60168 6.17975 8.67192 6.01018 8.67192 5.83337C8.67192 5.65656 8.60168 5.48699 8.47666 5.36197C8.35163 5.23695 8.18206 5.16671 8.00525 5.16671H7.99992C7.82311 5.16671 7.65354 5.23695 7.52851 5.36197C7.40349 5.48699 7.33325 5.65656 7.33325 5.83337ZM7.99992 11.8334C8.17673 11.8334 8.3463 11.7631 8.47132 11.6381C8.59635 11.5131 8.66658 11.3435 8.66658 11.1667V7.83337C8.66658 7.65656 8.59635 7.48699 8.47132 7.36197C8.3463 7.23695 8.17673 7.16671 7.99992 7.16671C7.82311 7.16671 7.65354 7.23695 7.52851 7.36197C7.40349 7.48699 7.33325 7.65656 7.33325 7.83337V11.1667C7.33325 11.3435 7.40349 11.5131 7.52851 11.6381C7.65354 11.7631 7.82311 11.8334 7.99992 11.8334Z"
                                                    fill="white" />
                                            </svg>
                                        </div>
                                        <p class="text-xs text-white/75 font-space-grotesk">
                                            <?php esc_html_e('$6,000 profit target to pass', 'funded-way'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap justify-center w-full gap-2 md:flex-nowrap">
                                <!-- Card -->
                                <div class="flex w-full gap-2 p-2 max-w-3xs bg-white/10 rounded-3xl">
                                    <div class="flex flex-col items-center justify-center bg-white w-15 rounded-2xl">
                                        <p class="text-fw-indigo font-space-grotesk">
                                            <?php esc_html_e('24%', 'funded-way'); ?>
                                        </p>
                                    </div>
                                    <div class="flex flex-col w-full space-y-2">
                                        <div class="flex flex-row items-center w-full space-x-4">
                                            <p class="text-white font-space-grotesk">
                                                <?php esc_html_e('6% profit target', 'funded-way'); ?>
                                            </p>
                                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M14.6666 8.50004C14.6666 12.182 11.6819 15.1667 7.99992 15.1667C4.31792 15.1667 1.33325 12.182 1.33325 8.50004C1.33325 4.81804 4.31792 1.83337 7.99992 1.83337C11.6819 1.83337 14.6666 4.81804 14.6666 8.50004ZM7.33325 5.83337C7.33325 6.01018 7.40349 6.17975 7.52851 6.30478C7.65354 6.4298 7.82311 6.50004 7.99992 6.50004H8.00525C8.18206 6.50004 8.35163 6.4298 8.47666 6.30478C8.60168 6.17975 8.67192 6.01018 8.67192 5.83337C8.67192 5.65656 8.60168 5.48699 8.47666 5.36197C8.35163 5.23695 8.18206 5.16671 8.00525 5.16671H7.99992C7.82311 5.16671 7.65354 5.23695 7.52851 5.36197C7.40349 5.48699 7.33325 5.65656 7.33325 5.83337ZM7.99992 11.8334C8.17673 11.8334 8.3463 11.7631 8.47132 11.6381C8.59635 11.5131 8.66658 11.3435 8.66658 11.1667V7.83337C8.66658 7.65656 8.59635 7.48699 8.47132 7.36197C8.3463 7.23695 8.17673 7.16671 7.99992 7.16671C7.82311 7.16671 7.65354 7.23695 7.52851 7.36197C7.40349 7.48699 7.33325 7.65656 7.33325 7.83337V11.1667C7.33325 11.3435 7.40349 11.5131 7.52851 11.6381C7.65354 11.7631 7.82311 11.8334 7.99992 11.8334Z"
                                                    fill="white" />
                                            </svg>
                                        </div>
                                        <p class="text-xs text-white/75 font-space-grotesk">
                                            <?php esc_html_e('$6,000 profit target to pass', 'funded-way'); ?>
                                        </p>
                                    </div>
                                </div>
                                <!-- Card -->
                                <div class="flex w-full gap-2 p-2 max-w-3xs bg-white/10 rounded-3xl">
                                    <div class="flex flex-col items-center justify-center bg-white w-15 rounded-2xl">
                                        <p class="text-fw-indigo font-space-grotesk">
                                            <?php esc_html_e('24%', 'funded-way'); ?>
                                        </p>
                                    </div>
                                    <div class="flex flex-col w-full space-y-2">
                                        <div class="flex flex-row items-center w-full space-x-4">
                                            <p class="text-white font-space-grotesk">
                                                <?php esc_html_e('6% profit target', 'funded-way'); ?>
                                            </p>
                                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M14.6666 8.50004C14.6666 12.182 11.6819 15.1667 7.99992 15.1667C4.31792 15.1667 1.33325 12.182 1.33325 8.50004C1.33325 4.81804 4.31792 1.83337 7.99992 1.83337C11.6819 1.83337 14.6666 4.81804 14.6666 8.50004ZM7.33325 5.83337C7.33325 6.01018 7.40349 6.17975 7.52851 6.30478C7.65354 6.4298 7.82311 6.50004 7.99992 6.50004H8.00525C8.18206 6.50004 8.35163 6.4298 8.47666 6.30478C8.60168 6.17975 8.67192 6.01018 8.67192 5.83337C8.67192 5.65656 8.60168 5.48699 8.47666 5.36197C8.35163 5.23695 8.18206 5.16671 8.00525 5.16671H7.99992C7.82311 5.16671 7.65354 5.23695 7.52851 5.36197C7.40349 5.48699 7.33325 5.65656 7.33325 5.83337ZM7.99992 11.8334C8.17673 11.8334 8.3463 11.7631 8.47132 11.6381C8.59635 11.5131 8.66658 11.3435 8.66658 11.1667V7.83337C8.66658 7.65656 8.59635 7.48699 8.47132 7.36197C8.3463 7.23695 8.17673 7.16671 7.99992 7.16671C7.82311 7.16671 7.65354 7.23695 7.52851 7.36197C7.40349 7.48699 7.33325 7.65656 7.33325 7.83337V11.1667C7.33325 11.3435 7.40349 11.5131 7.52851 11.6381C7.65354 11.7631 7.82311 11.8334 7.99992 11.8334Z"
                                                    fill="white" />
                                            </svg>
                                        </div>
                                        <p class="text-xs text-white/75 font-space-grotesk">
                                            <?php esc_html_e('$6,000 profit target to pass', 'funded-way'); ?>
                                        </p>
                                    </div>
                                </div>
                                <!-- Card -->
                                <div class="flex w-full gap-2 p-2 max-w-3xs bg-white/10 rounded-3xl">
                                    <div class="flex flex-col items-center justify-center bg-white w-15 rounded-2xl">
                                        <p class="text-fw-indigo font-space-grotesk">
                                            <?php esc_html_e('24%', 'funded-way'); ?>
                                        </p>
                                    </div>
                                    <div class="flex flex-col w-full space-y-2">
                                        <div class="flex flex-row items-center w-full space-x-4">
                                            <p class="text-white font-space-grotesk">
                                                <?php esc_html_e('6% profit target', 'funded-way'); ?>
                                            </p>
                                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M14.6666 8.50004C14.6666 12.182 11.6819 15.1667 7.99992 15.1667C4.31792 15.1667 1.33325 12.182 1.33325 8.50004C1.33325 4.81804 4.31792 1.83337 7.99992 1.83337C11.6819 1.83337 14.6666 4.81804 14.6666 8.50004ZM7.33325 5.83337C7.33325 6.01018 7.40349 6.17975 7.52851 6.30478C7.65354 6.4298 7.82311 6.50004 7.99992 6.50004H8.00525C8.18206 6.50004 8.35163 6.4298 8.47666 6.30478C8.60168 6.17975 8.67192 6.01018 8.67192 5.83337C8.67192 5.65656 8.60168 5.48699 8.47666 5.36197C8.35163 5.23695 8.18206 5.16671 8.00525 5.16671H7.99992C7.82311 5.16671 7.65354 5.23695 7.52851 5.36197C7.40349 5.48699 7.33325 5.65656 7.33325 5.83337ZM7.99992 11.8334C8.17673 11.8334 8.3463 11.7631 8.47132 11.6381C8.59635 11.5131 8.66658 11.3435 8.66658 11.1667V7.83337C8.66658 7.65656 8.59635 7.48699 8.47132 7.36197C8.3463 7.23695 8.17673 7.16671 7.99992 7.16671C7.82311 7.16671 7.65354 7.23695 7.52851 7.36197C7.40349 7.48699 7.33325 7.65656 7.33325 7.83337V11.1667C7.33325 11.3435 7.40349 11.5131 7.52851 11.6381C7.65354 11.7631 7.82311 11.8334 7.99992 11.8334Z"
                                                    fill="white" />
                                            </svg>
                                        </div>
                                        <p class="text-xs text-white/75 font-space-grotesk">
                                            <?php esc_html_e('$6,000 profit target to pass', 'funded-way'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Updated pricing display section -->
                        <div
                            class="flex flex-col items-center justify-center w-full p-3 md:w-1/3 bg-black/10 rounded-2xl">
                            <h2 class="my-4 text-xl font-semibold leading-tight text-center md:text-4xl font-space-grotesk"
                                x-text="getCurrentPrice()">
                                $499
                            </h2>
                            <p class="text-lg text-center text-white/75 font-space-grotesk"
                                x-text="getCurrentDescription()">
                                2-STEP - Pro Way - $100,000 account
                            </p>
                            <a :href="getCurrentLink()"
                                class="flex items-center justify-center w-full px-5 py-2 my-4 text-base font-medium text-center text-black transition duration-300 ease-in-out bg-white rounded-full whitespace-nowrap md:text-lg hover:drop-shadow-white-glow">
                                Buy Challenge
                            </a>
                        </div>
                    </div>

                </div>

        </section>

        <!-- Trust Section -->
        <section class="relative flex justify-center w-full py-10 mx-auto align-center min-h-fit md:py-20 max-w-7xl">
            <div class="flex flex-col justify-center w-full p-3 space-y-8 md:p-10 align-center md:space-y-0">
                <div class="flex flex-col items-center justify-start w-full space-y-10">
                    <!-- Heading Content -->
                    <div class="flex flex-col items-center justify-center w-fit">
                        <div
                            class="text-center w-fit text-sm mb-5 border border-fw-lavender/10 px-2 py-0.5 rounded-full text-fw-lavender bg-fw-lavender/10 font-space-grotesk">
                            <?php esc_html_e('WHY TRUST US?', 'funded-way'); ?>
                        </div>
                        <h2 class="custom-gradient-title">
                            <?php esc_html_e('Traders Trust The Funded Way', 'funded-way'); ?>
                        </h2>
                    </div>
                </div>

                <!-- Body -->
                <div class="flex flex-col items-center justify-between w-full space-y-6">
                    <div class="grid w-full max-w-5xl grid-cols-1 gap-6 mt-6 custom-section-1 md:grid-cols-3 lg:mt-10">
                        <!-- 1 -->
                        <div
                            class="flex flex-col justify-between space-y-4 overflow-hidden bg-center bg-no-repeat bg-cover border feature-left h-150 rounded-xl border-white/20 bg-fw-payouts will-change-auto">
                            <!-- Body -->
                            <div class="z-20 flex flex-col justify-start p-3 space-y-4 md:p-6">
                                <div class="flex flex-col justify-center space-y-2">
                                    <h4 class="text-base">
                                        <?php esc_html_e('Payouts in 24–48 Hours', 'funded-way'); ?>
                                    </h4>
                                    <p class="text-sm text-white/75">
                                        <?php esc_html_e(
                                        	'Your profits are yours to enjoy, we process withdrawals within 1–2 business days, so you can focus on trading, not waiting.',
                                        	'funded-way',
                                        ); ?>
                                    </p>
                                </div>
                            </div>
                            <!-- Bottom Section -->
                            <div class="relative w-full p-2 overflow-hidden md:p-4">
                                <!-- Fade Top -->
                                <div
                                    class="pointer-events-none absolute left-0 top-0 w-full h-16 bg-gradient-to-b from-transparent from-0% via-[#1a1a1a] via-25% to-transparent to-100% z-10">
                                </div>
                                <!-- Fade Bottom -->
                                <div
                                    class="pointer-events-none absolute left-0 bottom-0 w-full h-16 bg-gradient-to-t from-[#1a1a1a] from-15% to-transparent to-100% z-10">
                                </div>
                                <div class="relative w-full h-64 overflow-hidden">
                                    <div class="flex flex-col gap-2 flow-vertical">
                                        <!-- 1 -->
                                        <div
                                            class="relative flex items-center self-stretch justify-between gap-6 px-4 py-3 overflow-hidden border rounded-lg border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                                            <div class="flex flex-col">
                                                <span class="text-base font-medium text-white">
                                                    <?php esc_html_e('Payment Made', 'funded-way'); ?>
                                                </span>
                                                <span class="text-xs text-white/75">
                                                    <?php esc_html_e('Your received your payout', 'funded-way'); ?>
                                                </span>
                                            </div>
                                            <div
                                                class="flex items-center self-stretch gap-6 px-2 py-2 text-white border rounded-md whitespace-nowrap border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                                                <?php esc_html_e('+$5,000', 'funded-way'); ?>
                                            </div>
                                            <!-- Glowing vertical divider -->
                                            <div
                                                class="absolute left-0 top-1/2 -translate-y-1/2 h-1/2 w-0.5 bg-white/80 rounded-full shadow-[0_2px_20px_5px_rgba(255,255,255,0.4)]">
                                            </div>
                                        </div>
                                        <!-- 2 -->
                                        <div
                                            class="relative flex items-center self-stretch justify-between gap-6 px-4 py-3 overflow-hidden border rounded-lg border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                                            <div class="flex flex-col">
                                                <span class="text-base font-medium text-white">
                                                    <?php esc_html_e('Payment Made', 'funded-way'); ?>
                                                </span>
                                                <span class="text-xs text-white/75">
                                                    <?php esc_html_e('Your received your payout', 'funded-way'); ?>
                                                </span>
                                            </div>
                                            <div
                                                class="flex items-center self-stretch gap-6 px-2 py-2 text-white border rounded-md whitespace-nowrap border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                                                <?php esc_html_e('+$5,000', 'funded-way'); ?>
                                            </div>
                                            <div
                                                class="absolute left-0 top-1/2 -translate-y-1/2 h-1/2 w-0.5 bg-white/80 rounded-full shadow-[0_2px_20px_5px_rgba(255,255,255,0.4)]">
                                            </div>
                                        </div>
                                        <!-- 3 -->
                                        <div
                                            class="relative flex items-center self-stretch justify-between gap-6 px-4 py-3 overflow-hidden border rounded-lg border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                                            <div class="flex flex-col">
                                                <span class="text-base font-medium text-white">
                                                    <?php esc_html_e('Payment Made', 'funded-way'); ?>
                                                </span>
                                                <span class="text-xs text-white/75">
                                                    <?php esc_html_e('Your received your payout', 'funded-way'); ?>
                                                </span>
                                            </div>
                                            <div
                                                class="flex items-center self-stretch gap-6 px-2 py-2 text-white border rounded-md whitespace-nowrap border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                                                <?php esc_html_e('+$5,000', 'funded-way'); ?>
                                            </div>
                                            <div
                                                class="absolute left-0 top-1/2 -translate-y-1/2 h-1/2 w-0.5 bg-white/80 rounded-full shadow-[0_2px_20px_5px_rgba(255,255,255,0.4)]">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- 2 -->
                        <div class="grid grid-rows-2 gap-6">
                            <!-- 1 -->
                            <div
                                class="relative flex flex-col justify-center overflow-hidden bg-center bg-no-repeat bg-cover border feature-middle-top h-45 rounded-xl border-white/10 0 bg-fw-transparent will-change-auto">
                                <!-- Top Section -->
                                <div class="relative w-full px-2 mt-6 overflow-hidden">
                                    <!-- Fade Left -->
                                    <div
                                        class="pointer-events-none absolute left-0 top-0 h-full w-16 bg-gradient-to-r from-[#1a1a1a] from-15% to-transparent to-100% z-10">
                                    </div>
                                    <!-- Fade Right -->
                                    <div
                                        class="pointer-events-none absolute right-0 top-0 h-full w-16 bg-gradient-to-l from-[#1a1a1a] from-15% to-transparent to-100% z-10">
                                    </div>
                                    <div class="relative w-full overflow-hidden">
                                        <div class="flex items-center gap-2 flow min-w-max">
                                            <div
                                                class="border border-white/10 rounded-full px-4 py-1 bg-[#313131] flex-shrink-0">
                                                <h6 class="text-base text-center whitespace-nowrap">
                                                    <?php esc_html_e('Max Drawdown', 'funded-way'); ?>
                                                </h6>
                                            </div>
                                            <div
                                                class="border border-white/10 rounded-full px-4 py-1 bg-[#313131] flex-shrink-0">
                                                <h6 class="text-base text-center whitespace-nowrap">
                                                    <?php esc_html_e('Profit Target', 'funded-way'); ?>
                                                </h6>
                                            </div>
                                            <div
                                                class="border border-white/10 rounded-full px-4 py-1 bg-[#313131] flex-shrink-0">
                                                <h6 class="text-base text-center whitespace-nowrap">
                                                    <?php esc_html_e('Daily Drawdown', 'funded-way'); ?>
                                                </h6>
                                            </div>
                                            <div
                                                class="border border-white/10 rounded-full px-4 py-1 bg-[#313131] flex-shrink-0">
                                                <h6 class="text-base text-center whitespace-nowrap">
                                                    <?php esc_html_e('Time limit', 'funded-way'); ?>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <!-- Body -->
                                <div class="z-20 flex flex-col justify-end w-full p-3 space-y-2">
                                    <h4 class="text-base text-center">
                                        <?php esc_html_e('Transparent Drawdowns', 'funded-way'); ?>
                                    </h4>
                                    <p class="text-xs text-center text-white/75">
                                        <?php esc_html_e(
                                        	'Operating from one of the world’s leading financial hubs, we meet high international standards for transparency, credibility,',
                                        	'funded-way',
                                        ); ?>
                                    </p>
                                </div>
                            </div>
                            <!-- 2 -->
                            <div
                                class="relative flex flex-col justify-center overflow-hidden bg-center bg-no-repeat bg-cover border feature-middle-middle h-45 rounded-xl border-white/10 bg-fw-conditions will-change-auto">
                                <!-- Top Image -->
                                <div class="flex items-center justify-center w-full mt-6">
                                    <div
                                        class="w-fit rounded-lg border border-white/10 px-5 py-1 bg-gradient-radial from-white/10 to-white/5 shadow-[0_2px_40px_0_#05021A] backdrop-blur-lg">
                                        <h6 class="text-base text-center whitespace-nowrap custom-gradient-title">
                                            <?php esc_html_e('Clear Terms & Conditions', 'funded-way'); ?>
                                        </h6>
                                    </div>
                                </div>
                                <!-- Body -->
                                <div class="z-20 flex flex-col justify-end w-full p-3 space-y-2 md:p-6">
                                    <h4 class="text-base text-center">
                                        <?php esc_html_e('No Hidden Conditions', 'funded-way'); ?>
                                    </h4>
                                    <p class="text-xs text-center text-white/75">
                                        <?php esc_html_e(
                                        	'Operating from one of the world’s leading financial hubs, we meet high international standards for transparency, credibility, ',
                                        	'funded-way',
                                        ); ?>
                                    </p>
                                </div>
                            </div>
                            <!-- 3 -->
                            <div
                                class="relative flex flex-col justify-center overflow-hidden bg-center bg-no-repeat bg-cover border feature-middle-bottom h-45 rounded-xl border-white/10 bg-fw-telegram will-change-auto">
                                <!-- Top Image -->
                                <div class="flex items-center justify-center w-full mt-12">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/telegram.png"
                                        alt="top" class="h-8">
                                </div>
                                <!-- Body -->
                                <div class="z-20 flex flex-col justify-end w-full p-3 space-y-2 md:p-6">
                                    <h4 class="text-base text-center">
                                        <?php esc_html_e('Telegram Support', 'funded-way'); ?>
                                    </h4>
                                    <p class="text-xs text-center text-white/75">
                                        <?php esc_html_e(
                                        	'Operating from one of the world’s leading financial hubs, we meet high international standards for transparency, credibility, ',
                                        	'funded-way',
                                        ); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- 3 -->
                        <div
                            class="relative flex flex-col justify-between space-y-4 overflow-hidden bg-center bg-no-repeat bg-cover border feature-right h-150 rounded-xl border-white/10 bg-fw-dubai will-change-auto">
                            <!-- Body -->
                            <div class="z-20 flex flex-col justify-end p-3 space-y-4 md:p-6">
                                <div class="flex flex-col justify-center space-y-2">
                                    <h4 class="text-base">
                                        <?php esc_html_e('Registered in Dubai', 'funded-way'); ?>
                                    </h4>
                                    <p class="text-sm text-white/75">
                                        <?php esc_html_e(
                                        	'Operating from one of the world’s leading financial hubs, we meet high international standards for transparency, credibility, and compliance',
                                        	'funded-way',
                                        ); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="relative flex justify-center w-full py-10 mx-auto align-center min-h-fit md:py-20 max-w-7xl">
            <div class="flex flex-col justify-center w-full p-3 space-y-8 md:p-10 align-center md:space-y-0">
                <div class="flex flex-col items-center justify-start w-full space-y-10">
                    <!-- Heading Content -->
                    <div class="flex flex-col items-center justify-center w-fit">
                        <div
                            class="text-center w-fit text-sm mb-5 border border-fw-lavender/10 px-2 py-0.5 rounded-full text-fw-lavender bg-fw-lavender/10 font-space-grotesk">
                            <?php esc_html_e('HOW IT WORKS?', 'funded-way'); ?>
                        </div>
                        <h2 class="custom-gradient-title">
                            <?php esc_html_e('How to start earning', 'funded-way'); ?>
                        </h2>
                    </div>
                </div>

                <div x-data="{ hovered: '', selected: 'reg' }"
                    class="flex flex-col items-center justify-around w-full gap-6 p-2 mt-10 overflow-hidden md:flex-row">
                    <div class="flex flex-col">
                        <!-- Registration Card -->
                        <div class="relative flex items-center justify-start w-full max-w-md gap-6 px-4 py-3 overflow-hidden transition-all rounded-lg cursor-pointer"
                            @mouseenter="hovered = 'reg'" @mouseleave="hovered = ''" @click="selected = 'reg'"
                            :class="{ 'bg-white/10': hovered === 'reg' || selected === 'reg' }">
                            <div class="overflow-hidden rounded-lg">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/jigsaw.png"
                                    alt="jigsaw" class="">
                            </div>
                            <div class="flex flex-col">
                                <h6 class="text-2xl font-medium text-white font-space-grotesk">
                                    <?php esc_html_e('Registration', 'funded-way'); ?>
                                </h6>
                                <p class="text-sm text-white/75">
                                    <?php esc_html_e(
                                    	'Register on our platform and choose one of our ready-made challenges designed for you',
                                    	'funded-way',
                                    ); ?>
                                </p>
                            </div>
                        </div>
                        <!-- Test Card -->
                        <div class="relative flex items-center justify-start w-full max-w-md gap-6 px-4 py-3 overflow-hidden transition-all rounded-lg cursor-pointer"
                            @mouseenter="hovered = 'test'" @mouseleave="hovered = ''" @click="selected = 'test'"
                            :class="{ 'bg-white/10': hovered === 'test' || selected === 'test' }">
                            <div class="overflow-hidden rounded-lg">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/jigsaw.png"
                                    alt="jigsaw" class="">
                            </div>
                            <div class="flex flex-col">
                                <h6 class="text-2xl font-medium text-white font-space-grotesk">
                                    <?php esc_html_e('Passing the test', 'funded-way'); ?>
                                </h6>
                                <p class="text-sm text-white/75">
                                    <?php esc_html_e(
                                    	'Demonstrate your ability to manage your risks and profits by meeting account objectives',
                                    	'funded-way',
                                    ); ?>
                                </p>
                            </div>
                        </div>
                        <!-- Payments Card -->
                        <div class="relative flex items-center justify-start w-full max-w-md gap-6 px-4 py-3 overflow-hidden transition-all rounded-lg cursor-pointer"
                            @mouseenter="hovered = 'payments'" @mouseleave="hovered = ''" @click="selected = 'payments'"
                            :class="{ 'bg-white/10': hovered === 'payments' || selected === 'payments' }">
                            <div class="overflow-hidden rounded-lg">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/jigsaw.png"
                                    alt="jigsaw" class="">
                            </div>
                            <div class="flex flex-col">
                                <h6 class="text-2xl font-medium text-white font-space-grotesk">
                                    <?php esc_html_e('Receiving payments', 'funded-way'); ?>
                                </h6>
                                <p class="text-sm text-white/75">
                                    <?php esc_html_e(
                                    	'Get access to a financing account to earn up to 90% profit using your strategy.',
                                    	'funded-way',
                                    ); ?>
                                </p>
                            </div>
                        </div>
                        <!-- Account Card -->
                        <div class="relative flex items-center justify-start w-full max-w-md gap-6 px-4 py-3 overflow-hidden transition-all rounded-lg cursor-pointer"
                            @mouseenter="hovered = 'account'" @mouseleave="hovered = ''" @click="selected = 'account'"
                            :class="{ 'bg-white/10': hovered === 'account' || selected === 'account' }">
                            <div class="overflow-hidden rounded-lg">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/jigsaw.png"
                                    alt="jigsaw" class="">
                            </div>
                            <div class="flex flex-col">
                                <h6 class="text-2xl font-medium text-white font-space-grotesk">
                                    <?php esc_html_e('Scale your account', 'funded-way'); ?>
                                </h6>
                                <p class="text-sm text-white/75">
                                    <?php esc_html_e(
                                    	'Trade profitably and get an individual contract with a real account and customized terms.',
                                    	'funded-way',
                                    ); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Image Viewer -->
                    <div class="overflow-hidden transition-all duration-300 rounded-lg">
                        <template x-if="hovered === '' && selected === 'reg'">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/reg.png" alt="reg-default">
                        </template>
                        <template x-if="hovered === '' && selected === 'test'">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/test.png"
                                alt="test-default">
                        </template>
                        <template x-if="hovered === '' && selected === 'payments'">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/payments.png"
                                alt="payments-default">
                        </template>
                        <template x-if="hovered === '' && selected === 'account'">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/account.png"
                                alt="account-default">
                        </template>

                        <!-- Hover overrides -->
                        <template x-if="hovered === 'reg'">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/reg.png" alt="reg-hover">
                        </template>
                        <template x-if="hovered === 'test'">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/test.png" alt="test-hover">
                        </template>
                        <template x-if="hovered === 'payments'">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/payments.png"
                                alt="payments-hover">
                        </template>
                        <template x-if="hovered === 'account'">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/account.png"
                                alt="account-hover">
                        </template>
                    </div>
                </div>
            </div>
        </section>

        <!-- Worldwide Traders Section -->
        <section
            class="relative inset-x-0 flex flex-col items-center justify-center px-2 py-4 pt-10 mx-auto text-center bg-black h-200 max-w-7xl">
            <div
                class="relative flex flex-col items-center justify-around w-full h-auto p-5 mx-auto md:w-11/12 rounded-xl md:p-10 bg-fw-slate-blue">
                <div class="z-20 flex flex-col items-center justify-center gap-4 mb-4">
                    <h2 class="text-5xl font-bold text-white px-2 py-0.5 font-space-grotesk">
                        <?php esc_html_e('$120K+ Earned', 'funded-way'); ?>
                    </h2>
                    <h3 class="text-2xl text-white px-2 py-0.5 font-space-grotesk">
                        <?php esc_html_e('By our traders worldwide', 'funded-way'); ?>
                    </h3>
                </div>

                <div x-data="slider()" x-init="init()" class="relative z-20 flex flex-col w-full overflow-hidden">

                    <!-- Scrollable track -->
                    <div x-ref="track"
                        class="flex items-center justify-center gap-4 transition-transform duration-300 ease-in-out will-change-transform"
                        :style="`transform: translateX(-${cardWidth * currentIndex}px)`">

                        <!-- United Kingdom -->
                        <div
                            class="flex flex-col items-start gap-2 p-1 text-white border rounded-md border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                            <div
                                class="flex items-center gap-6 px-4 py-2 text-white border rounded-md border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                                <h4 class="text-3xl text-white px-2 py-0.5 font-space-mono">
                                    <?php esc_html_e('$12,000', 'funded-way'); ?>
                                </h4>
                            </div>
                            <div class="flex items-center justify-center gap-2">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/us.png" alt="us"
                                    class="h-5">
                                <p class="text-sm text-white text-start">
                                    <?php esc_html_e('United Kingdom', 'funded-way'); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Australia -->
                        <div
                            class="flex flex-col items-start gap-2 p-1 text-white border rounded-md border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                            <div
                                class="flex items-center gap-6 px-4 py-2 text-white border rounded-md border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                                <h4 class="text-3xl text-white px-2 py-0.5 font-space-mono">
                                    <?php esc_html_e('$15,000', 'funded-way'); ?>
                                </h4>
                            </div>
                            <div class="flex items-center justify-center gap-2">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/us.png" alt="us"
                                    class="h-5">
                                <p class="text-sm text-white text-start">
                                    <?php esc_html_e('Australia', 'funded-way'); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Australia -->
                        <div
                            class="flex flex-col items-start gap-2 p-1 text-white border rounded-md border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                            <div
                                class="flex items-center gap-6 px-4 py-2 text-white border rounded-md border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                                <h4 class="text-3xl text-white px-2 py-0.5 font-space-mono">
                                    <?php esc_html_e('$15,000', 'funded-way'); ?>
                                </h4>
                            </div>
                            <div class="flex items-center justify-center gap-2">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/us.png" alt="us"
                                    class="h-5">
                                <p class="text-sm text-white text-start">
                                    <?php esc_html_e('Australia', 'funded-way'); ?>
                                </p>
                            </div>
                        </div>
                        <!-- Canada -->
                        <div
                            class="flex flex-col items-start gap-2 p-1 text-white border rounded-md border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                            <div
                                class="flex items-center gap-6 px-4 py-2 text-white border rounded-md border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                                <h4 class="text-3xl text-white px-2 py-0.5 font-space-mono">
                                    <?php esc_html_e('$10,000', 'funded-way'); ?>
                                </h4>
                            </div>
                            <div class="flex items-center justify-center gap-2">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/us.png" alt="us"
                                    class="h-5">
                                <p class="text-sm text-white text-start">
                                    <?php esc_html_e('Canada', 'funded-way'); ?>
                                </p>
                            </div>
                        </div>
                        <!-- Australia -->
                        <div
                            class="flex flex-col items-start gap-2 p-1 text-white border rounded-md border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                            <div
                                class="flex items-center gap-6 px-4 py-2 text-white border rounded-md border-white/10 bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-md">
                                <h4 class="text-3xl text-white px-2 py-0.5 font-space-mono">
                                    <?php esc_html_e('$15,000', 'funded-way'); ?>
                                </h4>
                            </div>
                            <div class="flex items-center justify-center gap-2">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/us.png" alt="us"
                                    class="h-5">
                                <p class="text-sm text-white text-start">
                                    <?php esc_html_e('Australia', 'funded-way'); ?>
                                </p>
                            </div>
                        </div>

                    </div>

                    <!-- Navigation buttons -->
                    <div class="z-20 flex items-center justify-center gap-4 mt-5 md:mt-10">
                        <button @click="prev()" class="cursor-pointer">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/left.png" alt="left"
                                class="h-6">
                        </button>
                        <button @click="next()" class="cursor-pointer">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/right.png" alt="right"
                                class="h-6">
                        </button>
                    </div>
                </div>

                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/globe.png" alt="globe"
                    class="absolute z-10 object-cover h-full -top-20 md:-top-30 md:w-1/2">
            </div>
        </section>

        <!-- Reviews Section -->
        <section x-data="scrollItem"
            class="relative flex justify-center w-full py-10 overflow-hidden align-center min-h-fit md:py-20">
            <div x-ref="scrollContainer"
                class="flex flex-col justify-center w-full space-y-4 overflow-hidden align-center md:space-y-0">
                <div class="flex flex-col items-center justify-start w-full p-3 mx-auto space-y-10 md:p-5 max-w-7xl">
                    <!-- Heading Content -->
                    <div class="flex flex-col items-center justify-center w-full md:items-start">
                        <div
                            class="text-center w-fit text-sm mb-5 border border-fw-lavender/10 px-2 py-0.5 rounded-full text-fw-lavender bg-fw-lavender/10 font-space-grotesk">
                            <?php esc_html_e('REVIEWS', 'funded-way'); ?>
                        </div>
                        <h2 class="custom-gradient-title">
                            <?php esc_html_e('Inside the Minds of', 'funded-way'); ?>
                        </h2>
                        <h2 class="custom-gradient-title">
                            <?php esc_html_e('Our Traders', 'funded-way'); ?>
                        </h2>
                    </div>
                </div>
                <div
                    class="flex flex-col items-center justify-center w-full gap-6 p-5 mx-auto mt-0 overflow-hidden md:flex-row max-w-7xl">
                    <div class="flex justify-center gap-2">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot.png"
                            alt="trustpilot" class="h-8">
                        <p class="text-3xl font-bold text-start">
                            <?php esc_html_e('Trustpilot', 'funded-way'); ?>
                        </p>
                    </div>
                </div>
                <div x-ref="scrollInnerWrapper"
                    class="flex flex-row items-stretch justify-center w-full gap-6 mt-5 overflow-hidden">
                    <!-- First Review -->
                    <div
                        class="flex flex-shrink-0 w-full overflow-hidden rounded-lg scroll-item max-w-3xs bg-white/10 backdrop-blur-xs">
                        <div class="flex flex-col items-center justify-center w-full gap-2 px-4 py-2 text-white">
                            <h6 class="mt-1 text-lg font-semibold text-white font-space-grotesk">
                                <?php esc_html_e('Excellent', 'funded-way'); ?>
                            </h6>
                            <div class="flex justify-center gap-2">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                    alt="trustpilot" class="h-7">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                    alt="trustpilot" class="h-7">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                    alt="trustpilot" class="h-7">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                    alt="trustpilot" class="h-7">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                    alt="trustpilot" class="h-7">
                            </div>
                            <div class="flex justify-center gap-2">
                                <p class="text-sm text-white/75 text-start">
                                    <?php esc_html_e('Based on', 'funded-way'); ?>
                                </p>
                                <p class="text-sm text-white underline text-start">
                                    <?php esc_html_e('456 reviews', 'funded-way'); ?>
                                </p>
                            </div>
                            <div class="flex justify-center gap-2">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot.png"
                                    alt="trustpilot" class="h-6">
                                <p class="text-lg font-semibold text-start">
                                    <?php esc_html_e('Trustpilot', 'funded-way'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Second Review -->
                    <div
                        class="flex flex-shrink-0 w-full overflow-hidden rounded-lg scroll-item max-w-3xs bg-white/10 backdrop-blur-xs">
                        <div class="flex flex-col items-start justify-center w-full gap-2 px-4 py-4 text-white">
                            <div class="flex items-center justify-between w-full">
                                <div class="flex justify-center gap-1">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                </div>
                                <p class="text-sm text-white/75 text-start">
                                    <?php esc_html_e('2 days ago', 'funded-way'); ?>
                                </p>
                            </div>
                            <h6 class="mt-1 text-lg font-semibold text-white">
                                <?php esc_html_e('Best on the market', 'funded-way'); ?>
                            </h6>
                            <div class="flex justify-center gap-2">
                                <p class="text-sm text-white/75 text-start">
                                    <?php esc_html_e(
                                    	'I love this product because the support is great. Please...',
                                    	'funded-way',
                                    ); ?>
                                </p>
                            </div>
                            <hr class="w-1/3">
                            <div class="flex justify-center gap-2">
                                <p class="text-sm text-start">
                                    <?php esc_html_e('Worldtraveller', 'funded-way'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Third Review -->
                    <div
                        class="flex flex-shrink-0 w-full overflow-hidden rounded-lg scroll-item max-w-3xs bg-white/10 backdrop-blur-xs">
                        <div class="flex flex-col items-start justify-center w-full gap-2 px-4 py-4 text-white">
                            <div class="flex items-center justify-between w-full">
                                <div class="flex justify-center gap-1">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                </div>
                                <p class="text-sm text-white/75 text-start">
                                    <?php esc_html_e('2 days ago', 'funded-way'); ?>
                                </p>
                            </div>
                            <h6 class="mt-1 text-lg font-semibold text-white">
                                <?php esc_html_e('Best on the market', 'funded-way'); ?>
                            </h6>
                            <div class="flex justify-center gap-2">
                                <p class="text-sm text-white/75 text-start">
                                    <?php esc_html_e(
                                    	'I love this product because the support is great. Please...',
                                    	'funded-way',
                                    ); ?>
                                </p>
                            </div>
                            <hr class="w-1/3">
                            <div class="flex justify-center gap-2">
                                <p class="text-sm text-start">
                                    <?php esc_html_e('Worldtraveller', 'funded-way'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Forth Review -->
                    <div
                        class="flex flex-shrink-0 w-full overflow-hidden rounded-lg scroll-item max-w-3xs bg-white/10 backdrop-blur-xs">
                        <div class="flex flex-col items-start justify-center w-full gap-2 px-4 py-4 text-white">
                            <div class="flex items-center justify-between w-full">
                                <div class="flex justify-center gap-1">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                </div>
                                <p class="text-sm text-white/75 text-start">
                                    <?php esc_html_e('2 days ago', 'funded-way'); ?>
                                </p>
                            </div>
                            <h6 class="mt-1 text-lg font-semibold text-white">
                                <?php esc_html_e('Best on the market', 'funded-way'); ?>
                            </h6>
                            <div class="flex justify-center gap-2">
                                <p class="text-sm text-white/75 text-start">
                                    <?php esc_html_e(
                                    	'I love this product because the support is great. Please...',
                                    	'funded-way',
                                    ); ?>
                                </p>
                            </div>
                            <hr class="w-1/3">
                            <div class="flex justify-center gap-2">
                                <p class="text-sm text-start">
                                    <?php esc_html_e('Worldtraveller', 'funded-way'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Fifth Review -->
                    <div
                        class="flex flex-shrink-0 w-full overflow-hidden rounded-lg scroll-item max-w-3xs bg-white/10 backdrop-blur-xs">
                        <div class="flex flex-col items-start justify-center w-full gap-2 px-4 py-4 text-white">
                            <div class="flex items-center justify-between w-full">
                                <div class="flex justify-center gap-1">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/trustpilot-inverse.png"
                                        alt="trustpilot" class="h-5">
                                </div>
                                <p class="text-sm text-white/75 text-start">
                                    <?php esc_html_e('2 days ago', 'funded-way'); ?>
                                </p>
                            </div>
                            <h6 class="mt-1 text-lg font-semibold text-white">
                                <?php esc_html_e('Best on the market', 'funded-way'); ?>
                            </h6>
                            <div class="flex justify-center gap-2">
                                <p class="text-sm text-white/75 text-start">
                                    <?php esc_html_e(
                                    	'I love this product because the support is great. Please...',
                                    	'funded-way',
                                    ); ?>
                                </p>
                            </div>
                            <hr class="w-1/3">
                            <div class="flex justify-center gap-2">
                                <p class="text-sm text-start">
                                    <?php esc_html_e('Worldtraveller', 'funded-way'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Blog Section -->
        <section class="relative flex justify-center w-full py-10 overflow-hidden align-center min-h-fit md:py-20">
            <div class="flex flex-col justify-center w-full space-y-4 overflow-hidden align-center md:space-y-0">
                <div class="flex flex-col items-center justify-start w-full p-3 mx-auto space-y-10 md:p-5 max-w-7xl">
                    <!-- Heading Content -->
                    <div class="flex flex-col items-center justify-between w-full gap-4 md:flex-row">

                        <div class="flex flex-col items-center md:items-start">
                            <div
                                class="text-center w-fit text-sm mb-5 border border-fw-lavender/10 px-2 py-0.5 rounded-full text-fw-lavender bg-fw-lavender/10 font-space-grotesk">
                                <?php esc_html_e('BLOG', 'funded-way'); ?>
                            </div>
                            <h2 class="custom-gradient-title">
                                <?php esc_html_e('News on our blog', 'funded-way'); ?>
                            </h2>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="#"
                                class="flex items-center justify-between px-4 py-1 text-base font-medium text-black transition duration-300 ease-in-out bg-white rounded-full whitespace-nowrap md:text-lg hover:drop-shadow-white-glow">
                                <?php esc_html_e('Visit Our Blog', 'funded-way'); ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/right.png" alt="right"
                                    class="h-8">
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Blogs -->
                <div
                    class="flex flex-col items-stretch justify-center w-full gap-6 p-5 mx-auto mt-0 overflow-hidden blog-section md:flex-row max-w-7xl">
                    <!-- Blog 1 -->
                    <div
                        class="flex flex-col justify-center w-full max-w-sm gap-2 p-4 transition duration-300 ease-in-out border cursor-pointer blog-card border-white/10 bg-white/5 rounded-2xl hover:bg-fw-lavender/20">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/blog-1.png" alt="trustpilot"
                            class="">
                        <p class="text-sm font-light text-start">
                            <?php esc_html_e('An overview of the next trading week on EUR and XAU...', 'funded-way'); ?>
                        </p>
                        <div class="flex items-center justify-start gap-2 mt-4">
                            <p class="text-xs text-start text-white/75">
                                <?php esc_html_e('September 20, 2025', 'funded-way'); ?>
                            </p>
                            <span class="text-xs text-start text-white/75">
                                -
                            </span>
                            <p class="text-xs text-start text-white/75">
                                <?php esc_html_e('27 minutes ago', 'funded-way'); ?>
                            </p>
                        </div>
                    </div>
                    <!-- Blog 2 -->
                    <div
                        class="flex flex-col justify-center w-full max-w-sm gap-2 p-4 transition duration-300 ease-in-out border cursor-pointer blog-card border-white/10 bg-white/5 rounded-2xl hover:bg-fw-lavender/20">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/blog-2.png" alt="trustpilot"
                            class="">
                        <p class="text-sm font-light text-start">
                            <?php esc_html_e('Why is it necessary to set a stop on the market?', 'funded-way'); ?>
                        </p>
                        <div class="flex items-center justify-start gap-2 mt-4">
                            <p class="text-xs text-start text-white/75">
                                <?php esc_html_e('September 20, 2025', 'funded-way'); ?>
                            </p>
                            <span class="text-xs text-start text-white/75">
                                -
                            </span>
                            <p class="text-xs text-start text-white/75">
                                <?php esc_html_e('27 minutes ago', 'funded-way'); ?>
                            </p>
                        </div>
                    </div>
                    <!-- Blog 3 -->
                    <div
                        class="flex flex-col justify-center w-full max-w-sm gap-2 p-4 transition duration-300 ease-in-out border cursor-pointer blog-card border-white/10 bg-white/5 rounded-2xl hover:bg-fw-lavender/20">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/blog-3.png" alt="trustpilot"
                            class="">
                        <p class="text-sm font-light text-start">
                            <?php esc_html_e(
                            	"Which indicators should be used in trading? I'll give you an example...",
                            	'funded-way',
                            ); ?>
                        </p>
                        <div class="flex items-center justify-start gap-2 mt-4">
                            <p class="text-xs text-start text-white/75">
                                <?php esc_html_e('September 20, 2025', 'funded-way'); ?>
                            </p>
                            <span class="text-xs text-start text-white/75">
                                -
                            </span>
                            <p class="text-xs text-start text-white/75">
                                <?php esc_html_e('27 minutes ago', 'funded-way'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq"
            class="relative flex justify-center w-full py-10 mx-auto faq align-center min-h-fit md:py-20 max-w-7xl">
            <div class="flex flex-col justify-center w-full p-3 space-y-8 md:p-10 align-center md:space-y-0">
                <div class="flex flex-col items-center justify-start w-full space-y-10">
                    <!-- Heading Content -->
                    <div class="flex flex-col items-center justify-center w-fit">
                        <div
                            class="text-center w-fit text-sm mb-5 border border-fw-lavender/10 px-2 py-0.5 rounded-full text-fw-lavender bg-fw-lavender/10 font-space-grotesk">
                            <?php esc_html_e('FAQ', 'funded-way'); ?>
                        </div>
                        <h2 class="custom-gradient-title">
                            <?php esc_html_e('Trading with FundedWay:', 'funded-way'); ?>
                        </h2>
                        <h2 class="custom-gradient-title">
                            <?php esc_html_e('What You Need to Know', 'funded-way'); ?>
                        </h2>
                    </div>
                </div>

                <div
                    class="flex flex-col items-center justify-center w-full p-2 mx-auto mt-10 space-y-4 overflow-hidden md:w-3/5">
                    <!-- FAQ 1 -->
                    <div x-data="{ open: false }"
                        class="card w-full rounded-lg bg-white/5 hover:shadow-[0_4px_10px_rgba(255,255,255,0.1)] border border-white/10 overflow-hidden">
                        <div @click="open = !open"
                            class="flex items-center justify-start w-full gap-6 px-4 py-3 rounded-md cursor-pointer">
                            <span x-text="open ? '-' : '+'"></span>
                            <h3 class="text-lg font-medium ">
                                <?php esc_html_e('What is Funded Way?', 'funded-way'); ?>
                            </h3>
                        </div>
                        <div x-show="open" x-transition class="p-4 rounded-md ms-8 text-white/50">
                            <p>
                                <?php esc_html_e(
                                	'Lorem ipsum dolor sit amet consectetur. Phasellus pulvinar ornare scelerisque ultricies facilisi feugiat ullamcorper sagittis pulvinar.',
                                	'funded-way',
                                ); ?>
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div x-data="{ open: false }"
                        class="w-full rounded-lg bg-white/5 hover:shadow-[0_4px_10px_rgba(255,255,255,0.1)] border border-white/10 overflow-hidden">
                        <div @click="open = !open"
                            class="flex items-center justify-start w-full gap-6 px-4 py-3 rounded-md cursor-pointer">
                            <span x-text="open ? '-' : '+'"></span>
                            <h3 class="text-lg font-medium ">
                                <?php esc_html_e('What accounts do we offer?', 'funded-way'); ?>
                            </h3>
                        </div>
                        <div x-show="open" x-transition class="p-4 rounded-md ms-8 text-white/50">
                            <p>
                                <?php esc_html_e(
                                	'Lorem ipsum dolor sit amet consectetur. Phasellus pulvinar ornare scelerisque ultricies facilisi feugiat ullamcorper sagittis pulvinar.',
                                	'funded-way',
                                ); ?>
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div x-data="{ open: false }"
                        class="w-full rounded-lg bg-white/5 hover:shadow-[0_4px_10px_rgba(255,255,255,0.1)] border border-white/10 overflow-hidden">
                        <div @click="open = !open"
                            class="flex items-center justify-start w-full gap-6 px-4 py-3 rounded-md cursor-pointer">
                            <span x-text="open ? '-' : '+'"></span>
                            <h3 class="text-lg font-medium ">
                                <?php esc_html_e('How does the DLL work?', 'funded-way'); ?>
                            </h3>
                        </div>
                        <div x-show="open" x-transition class="p-4 rounded-md ms-8 text-white/50">
                            <p>
                                <?php esc_html_e(
                                	'Lorem ipsum dolor sit amet consectetur. Phasellus pulvinar ornare scelerisque ultricies facilisi feugiat ullamcorper sagittis pulvinar.',
                                	'funded-way',
                                ); ?>
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div x-data="{ open: false }"
                        class="w-full rounded-lg bg-white/5 hover:shadow-[0_4px_10px_rgba(255,255,255,0.1)] border border-white/10 overflow-hidden">
                        <div @click="open = !open"
                            class="flex items-center justify-start w-full gap-6 px-4 py-3 rounded-md cursor-pointer">
                            <span x-text="open ? '-' : '+'"></span>
                            <h3 class="text-lg font-medium ">
                                <?php esc_html_e('What are the criteria to be eligible for a payout?', 'funded-way'); ?>
                            </h3>
                        </div>
                        <div x-show="open" x-transition class="p-4 rounded-md ms-8 text-white/50">
                            <p>
                                <?php esc_html_e(
                                	'Lorem ipsum dolor sit amet consectetur. Phasellus pulvinar ornare scelerisque ultricies facilisi feugiat ullamcorper sagittis pulvinar.',
                                	'funded-way',
                                ); ?>
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div x-data="{ open: false }"
                        class="w-full rounded-lg bg-white/5 hover:shadow-[0_4px_10px_rgba(255,255,255,0.1)] border border-white/10 overflow-hidden">
                        <div @click="open = !open"
                            class="flex items-center justify-start w-full gap-6 px-4 py-3 rounded-md cursor-pointer">
                            <span x-text="open ? '-' : '+'"></span>
                            <h3 class="text-lg font-medium">
                                <?php esc_html_e('What is the process for submitting a claim?', 'funded-way'); ?>
                            </h3>
                        </div>
                        <div x-show="open" x-transition class="p-4 rounded-md ms-8 text-white/50">
                            <p>
                                <?php esc_html_e(
                                	'Lorem ipsum dolor sit amet consectetur. Phasellus pulvinar ornare scelerisque ultricies facilisi feugiat ullamcorper sagittis pulvinar.',
                                	'funded-way',
                                ); ?>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- #main -->
</section><!-- #primary -->

<?php get_footer();