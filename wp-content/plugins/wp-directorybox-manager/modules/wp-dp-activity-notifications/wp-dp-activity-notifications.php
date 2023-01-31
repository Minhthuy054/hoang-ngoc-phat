<?php

/**
 * Directory Box Activity Notifications Module
 */
// Direct access not allowed.
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Wp_dp_Activity_Notifications')) {

    class Wp_dp_Activity_Notifications {

        public $admin_notices;

        /**
         * Start construct Functions
         */
        public function __construct() {
            // Define constants
            define('WP_DP_ACTIVITY_NOTIFICATIONS_PLUGIN_URL', WP_PLUGIN_DIR . '/wp-directorybox-manager/modules/wp-dp-activity-notifications' );
            define('WP_DP_ACTIVITY_NOTIFICATIONS_CORE_DIR', WP_PLUGIN_DIR . '/wp-directorybox-manager/modules/wp-dp-activity-notifications' );
            add_filter('wp_dp_plugin_text_strings', array($this, 'wp_dp_addon_notification_text_strings_callback'), 10);
            // Initialize Addon
            add_action('init', array($this, 'init'), 1);
        }

        /*
         * Enqueue Files to plugin
         */

        public function wp_dp_enqueue_files_frontend_callback() {
            wp_register_script('wp-dp-notifications-js', plugins_url('/assets/js/functions.js', __FILE__), '', '', true);
        }

        /**
         * Initialize application, load text domain, enqueue scripts and bind hooks
         */
        public function init() {
            wp_register_script('wp-dp-notifications-js', plugins_url('/assets/js/functions.js', __FILE__), '', '', true);
            $this->includes();
            // Add Plugin textdomain
            // Enqueue JS

            wp_localize_script(
                    'wp-dp-notifications-script', 'wp_dp_globals', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'security' => wp_create_nonce('wp-dp-security'),
                    )
            );
        }

        /**
         * Start Function how to add core files used in admin and theme
         */
        public function includes() {
            /*
             * Include notifications post type files
             */
            require_once WP_DP_ACTIVITY_NOTIFICATIONS_CORE_DIR . '/backend/classes/class-post-type-notifications.php';
            require_once WP_DP_ACTIVITY_NOTIFICATIONS_CORE_DIR . '/backend/classes/class-notifications-plugin-settings.php';

            /*
             * Include notifications files for frontend
             */
            require_once WP_DP_ACTIVITY_NOTIFICATIONS_CORE_DIR . '/frontend/classes/class-notifications-submission.php';
            require_once WP_DP_ACTIVITY_NOTIFICATIONS_CORE_DIR . '/frontend/classes/class-notifications-list.php';
        }

        /**
         * Add Notification Add on strings
         */
        public function wp_dp_addon_notification_text_strings_callback($wp_dp_static_text = array()) {

			if ( ! is_array($wp_dp_static_text) ) {
				$wp_dp_static_text = array();
			}
            $wp_dp_static_text['wp_dp_post_type_notification_name'] = wp_dp_plugin_text_srt('wp_dp_activity_notifications_notifications');
            $wp_dp_static_text['wp_dp_post_type_notification_singular_name'] = wp_dp_plugin_text_srt('wp_dp_activity_notifications_notification');
            $wp_dp_static_text['wp_dp_post_type_notification_menu_name'] = wp_dp_plugin_text_srt('wp_dp_activity_notifications_notifications');
            $wp_dp_static_text['wp_dp_post_type_notification_name_admin_bar'] = wp_dp_plugin_text_srt('wp_dp_activity_notifications_notifications');
            $wp_dp_static_text['wp_dp_post_type_notification_add_new'] = wp_dp_plugin_text_srt('wp_dp_activity_notifications_add_notification');
            $wp_dp_static_text['wp_dp_post_type_notification_add_new_item'] = wp_dp_plugin_text_srt('wp_dp_activity_notifications_add_notification');
            $wp_dp_static_text['wp_dp_post_type_notification_new_item'] = wp_dp_plugin_text_srt('wp_dp_activity_notifications_add_notification');
            $wp_dp_static_text['wp_dp_post_type_notification_edit_item'] = wp_dp_plugin_text_srt('wp_dp_activity_notifications_edit_notification');
            $wp_dp_static_text['wp_dp_post_type_notification_view_item'] = wp_dp_plugin_text_srt('wp_dp_activity_notifications_notification');
            $wp_dp_static_text['wp_dp_post_type_notification_all_items'] = wp_dp_plugin_text_srt('wp_dp_activity_notifications_notifications');
            $wp_dp_static_text['wp_dp_post_type_notification_search_items'] = wp_dp_plugin_text_srt('wp_dp_activity_notifications_notification');
            $wp_dp_static_text['wp_dp_post_type_notification_not_found'] = wp_dp_plugin_text_srt('wp_dp_activity_notifications_notifications');
            $wp_dp_static_text['wp_dp_post_type_notification_not_found_in_trash'] = wp_dp_plugin_text_srt('wp_dp_activity_notifications_notifications');
            $wp_dp_static_text['wp_dp_post_type_notification_description'] = wp_dp_plugin_text_srt('wp_dp_activity_notifications_edit_notification');
            $wp_dp_static_text['wp_dp_activity_notifications'] = wp_dp_plugin_text_srt('wp_dp_activity_notifications_notifications');
            $wp_dp_static_text['wp_dp_activity_notifications_hint'] = wp_dp_plugin_text_srt('wp_dp_activity_notifications_turn_on');
            return $wp_dp_static_text;
        }

        public function wp_dp_activity_notifications_notices_callback() {
            if (isset($this->admin_notices) && !empty($this->admin_notices)) {
                foreach ($this->admin_notices as $value) {
                    echo ($value);
                }
            }
        }

    }

    global $wp_dp_activity_notifications;
    $wp_dp_activity_notifications = new Wp_dp_Activity_Notifications();
}