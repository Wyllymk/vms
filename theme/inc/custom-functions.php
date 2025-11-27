<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Visitor_Management_System
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Prevent WordPress from converting 'paged' into /page/{num}/
add_filter(
	'redirect_canonical',
	function ( $redirect_url, $requested_url ) {
		if ( strpos( $requested_url, 'paged=' ) !== false ) {
			return false; // disable WP’s pagination redirect
		}
		return $redirect_url;
	},
	10,
	2
);

/**
 * Update Checker
 * https://github.com/YahnisElsts/plugin-update-checker
 */
require get_template_directory() . '/inc/update/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myThemeUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/Wyllymk/vms/',
	get_theme_file_path( 'functions.php' ),
	'vms'
);

// Same thing: point to branch if needed
$myThemeUpdateChecker->setBranch( 'main' );
$myThemeUpdateChecker->setAuthentication( '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' );
// Tell PUC to use the release asset (vms.zip) instead of auto-generated zips
$myThemeUpdateChecker->getVcsApi()->enableReleaseAssets();

/**
 * Theme Audit Trail Integration
 * Integrates with VMS Plugin audit trail for theme-side actions
 */
class VMS_Theme_Audit_Trail
{
    /**
     * Singleton instance
     * @var self|null
     */
    private static $instance = null;

    /**
     * Get singleton instance
     * @return self
     */
    public static function get_instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize audit trail
     */
    public function init(): void
    {
        add_action('wp_ajax_log_theme_action', [$this, 'log_theme_action']);
        add_action('wp_ajax_nopriv_log_theme_action', [$this, 'log_theme_action']); // Allow for non-logged-in users if needed
    }

    /**
     * Log theme action via AJAX
     */
    public function log_theme_action(): void
    {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'vms_theme_audit_nonce')) {
            wp_send_json_error(['message' => 'Security check failed']);
        }

        $user_id = get_current_user_id();
        $action_type = sanitize_text_field($_POST['action_type'] ?? '');
        $action_category = sanitize_text_field($_POST['action_category'] ?? 'frontend');
        $entity_type = sanitize_text_field($_POST['entity_type'] ?? '');
        $entity_id = intval($_POST['entity_id'] ?? 0);
        $description = sanitize_textarea_field($_POST['description'] ?? '');
        $old_values = isset($_POST['old_values']) ? json_decode(stripslashes($_POST['old_values']), true) : null;
        $new_values = isset($_POST['new_values']) ? json_decode(stripslashes($_POST['new_values']), true) : null;

        // Get user role
        $user_role = 'guest';
        if ($user_id) {
            $user = get_userdata($user_id);
            if ($user && !empty($user->roles)) {
                $user_role = $user->roles[0];
            }
        }

        // Log the action
        $this->log_action([
            'user_id' => $user_id,
            'user_role' => $user_role,
            'action_type' => $action_type,
            'action_category' => $action_category,
            'entity_type' => $entity_type,
            'entity_id' => $entity_id,
            'description' => $description,
            'old_values' => $old_values,
            'new_values' => $new_values,
            'ip_address' => $this->get_client_ip(),
        ]);

        wp_send_json_success(['message' => 'Action logged successfully']);
    }

    /**
     * Log an action to the audit trail
     */
    public function log_action(array $data): bool
    {
        // Check if VMS Plugin audit trail class exists
        if (!class_exists('\\WyllyMk\\VMS\\VMS_Audit_Trail')) {
            error_log('VMS Audit Trail: Plugin audit trail class not found');
            return false;
        }

        try {
            // Create audit trail data in the expected format
            $audit_data = [
                'action_type' => $data['action_type'] ?? 'unknown',
                'action_category' => $data['action_category'] ?? 'frontend',
                'entity_type' => $data['entity_type'] ?? null,
                'entity_id' => $data['entity_id'] ?? null,
                'old_values' => !empty($data['old_values']) ? wp_json_encode($data['old_values']) : null,
                'new_values' => !empty($data['new_values']) ? wp_json_encode($data['new_values']) : null,
                'metadata' => !empty($data['metadata']) ? wp_json_encode($data['metadata']) : null,
                'ip_address' => $data['ip_address'] ?? $this->get_client_ip(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
                'created_at' => current_time('mysql')
            ];

            // Get current user info
            $current_user = wp_get_current_user();
            $audit_data['user_id'] = $current_user->ID ?: null;
            $audit_data['user_role'] = $data['user_role'] ?? ($current_user->ID ? $current_user->roles[0] : 'guest');

            // Log the action using the plugin's audit trail instance
            global $wpdb;
            $table_name = $wpdb->prefix . 'vms_audit_trail';
            $result = $wpdb->insert($table_name, $audit_data);

            if ($result === false) {
                error_log('[VMS Theme Audit Trail] Failed to log action: ' . $wpdb->last_error);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            error_log('VMS Audit Trail Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get client IP address
     */
    private function get_client_ip(): string
    {
        $ip_headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($ip_headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];

                // Handle comma-separated IPs (like X-Forwarded-For)
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }

                // Validate IP
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    /**
     * Log frontend action (helper method for themes)
     */
    public static function log_frontend_action(string $action_type, string $description = '', array $data = []): bool
    {
        $instance = self::get_instance();

        $user_id = get_current_user_id();
        $user_role = 'guest';

        if ($user_id) {
            $user = get_userdata($user_id);
            if ($user && !empty($user->roles)) {
                $user_role = $user->roles[0];
            }
        }

        return $instance->log_action([
            'user_id' => $user_id,
            'user_role' => $user_role,
            'action_type' => $action_type,
            'action_category' => 'frontend',
            'entity_type' => $data['entity_type'] ?? '',
            'entity_id' => $data['entity_id'] ?? 0,
            'description' => $description,
            'old_values' => $data['old_values'] ?? null,
            'new_values' => $data['new_values'] ?? null,
            'ip_address' => $instance->get_client_ip(),
        ]);
    }
}

// Initialize theme audit trail
add_action('init', function() {
    if (class_exists('VMS_Audit_Trail')) {
        VMS_Theme_Audit_Trail::get_instance()->init();
    }
});

// Add audit trail nonce to theme scripts
add_filter('vms_script_ajax', function($ajax_vars) {
    $ajax_vars['audit_nonce'] = wp_create_nonce('vms_theme_audit_nonce');
    return $ajax_vars;
});