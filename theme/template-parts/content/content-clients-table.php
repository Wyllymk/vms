<?php
/**
 * Template part for displaying clients table
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visitor_Management_System
 */
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="max-w-full overflow-x-auto">
        <table class="min-w-full">
            <!-- table header start -->
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( '#', 'cyber-wakili' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'User Name', 'cyber-wakili' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'First Name', 'cyber-wakili' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'Last Name', 'cyber-wakili' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'Email', 'cyber-wakili' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'Phone Number', 'cyber-wakili' ); ?>
                            </p>
                        </div>
                    </th>
                    <th class="px-5 py-3 sm:px-6">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                <?php esc_html_e( 'View Details', 'cyber-wakili' ); ?>
                            </p>
                        </div>
                    </th>
                </tr>
            </thead>
            <!-- table header end -->
            <!-- table body start -->
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <?php
                    // Initialize counter
                    $counter = 1;

                    // Check if search form submitted
                    if (isset($_GET['search_users']) && !empty($_GET['user_search'])) {
                        // Handle search
                        $user_search = $_GET['user_search'];
                        $args = array(
                            'role' => 'client',
                            'search' => '*' . esc_attr($user_search) . '*',
                            'search_columns' => array('user_login', 'user_nicename'),
                        );
                        $users = get_users($args);

                        if (!empty($users)) {
                            foreach ($users as $user) {
                                // User data fetching logic
                                $user_id = $user->ID;
                                $username = $user->user_login;
                                $email = $user->user_email;
                                $user_register = $user->user_registered;
                                $first_name = get_user_meta($user_id, 'first_name', true);
                                $last_name = get_user_meta($user_id, 'last_name', true);
                                $user_phone_number = get_user_meta($user_id, 'phone_number', true);
                                ?>
                <tr>
                    <td class="px-5 py-4 sm:px-6">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                            <?php echo $counter++; ?>
                        </p>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <div class="flex items-center gap-3">
                                <div>
                                    <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                        <?php echo esc_html($username); ?>
                                    </span>
                                    <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
                                        Web Designer
                                    </span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php echo esc_html($first_name); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php echo esc_html($last_name); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php echo esc_html($email); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php echo esc_html($user_phone_number); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <form action="<?php echo esc_url(site_url('/client-details/')); ?>" method="get">
                            <input type="hidden" name="user_id" value="<?php echo esc_attr($user_id); ?>">
                            <button type="submit"
                                class="cursor-pointer inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-60">
                                <?php esc_html_e( 'View Details', 'cyber-wakili' ); ?>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php
                            }
                        } else {
                            echo '<tr><td colspan="10" class="px-4 py-4 text-white text-center">No clients found.</td></tr>';
                        }
                    } else {
                        // Display all users if no search
                        $users = get_users(array('role__in' => array('client')));
                        if (!empty($users)) {
                            foreach ($users as $user) {
                                // Same as above
                                $user_id = $user->ID;
                                $username = $user->user_login;
                                $email = $user->user_email;
                                $user_register = $user->user_registered;
                                $first_name = get_user_meta($user_id, 'first_name', true);
                                $last_name = get_user_meta($user_id, 'last_name', true);
                                $user_phone_number = get_user_meta($user_id, 'phone_number', true);
                ?>
                <tr>
                    <td class="px-5 py-4 sm:px-6">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                            <?php echo $counter++; ?>
                        </p>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <div class="flex items-center gap-3">
                                <div>
                                    <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                        <?php echo esc_html($username); ?>
                                    </span>
                                    <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
                                        Client
                                    </span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php echo esc_html($first_name); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php echo esc_html($last_name); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php echo esc_html($email); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                <?php echo esc_html($user_phone_number); ?>
                            </p>
                        </div>
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <form action="<?php echo esc_url(site_url('/client-details/')); ?>" method="get">
                            <input type="hidden" name="user_id" value="<?php echo esc_attr($user_id); ?>">
                            <button type="submit"
                                class="cursor-pointer inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-60">
                                <?php esc_html_e( 'View Details', 'cyber-wakili' ); ?>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php
                        }
                    } else {
                        echo '<tr><td colspan="10" class="px-4 py-4 text-gray-600 dark:text-white text-center">No clients found.</td></tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>