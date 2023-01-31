<?php

/*
  Plugin Name: Directory Box Framework
  Plugin URI: http://themeforest.net/user/Chimpstudio/
  Description: Directory Box Framework.
  Version: 1.9
  Author: ChimpStudio
  Author URI: http://themeforest.net/user/Chimpstudio/
  Text Domain: wp-dp-frame
  Domain Path: /languages/
  Copyright: 2020 chimpgroup (email : info@chimpstudio.co.uk)
  License: GNU General Public License v3.0
  License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('wp_dp_framework')) {

    class wp_dp_framework {

        protected static $_instance = null;
        static $theme_identify = '';
        public $admin_notice = '';

        /**
         * Main Plugin Instance
         *
         */
        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Initiate Plugin Actions
         *
         */
        public function __construct() {
            
            add_action('wp_dp_cs_before_header', array($this, 'under_construction'));
            
            if ( ! defined('THEME_OPTIONS_PAGE_SLUG') ) {
                define('THEME_OPTIONS_PAGE_SLUG', 'wp_dp_settings_page');
            }
            add_action('admin_init', array( $this, 'wp_dp_theme_identifier' ), 0);
            
            define('CSFRAME_DOMAIN', 'frame-wp_dp_cs');
            $this->plugin_actions();
            $this->includes();
            add_action('wp_ajax_wp_dp_cs_admin_dismiss_notice', array($this, 'wp_dp_cs_admin_dismiss_notice'));
        }

        public function wp_dp_theme_identifier() {
            if ( wp_dp_framework::$theme_identify != 'wp-dp-directorybox' ) {
                deactivate_plugins(plugin_basename(__FILE__));
                $this->admin_notice = wp_dp_cs_var_frame_text_srt( 'wp_dp_plugin_error' );
                add_action('admin_notices', array($this, 'wp_dp_admin_notices_callback'));
            }
        }
        
        public function wp_dp_admin_notices_callback(){
            echo '<div class="error">' . $this->admin_notice . '</div>';
        }

        /**
         * Fetch and return version of the current plugin
         *
         * @return	string	version of this plugin
         */
        public static function get_plugin_version() {
            $plugin_data = get_plugin_data(__FILE__);
            return $plugin_data['Version'];
        }

        /**
         * Initiate Plugin 
         * Text Domain
         * @return
         */
        public function load_plugin_textdomain() {
            $locale = apply_filters('plugin_locale', get_locale(), 'wp-dp-frame');
            load_textdomain('wp-dp-frame', WP_LANG_DIR . '/wp-dp-framework/wp-dp-frame-' . $locale . '.mo');
            load_plugin_textdomain('wp-dp-frame', false, plugin_basename(dirname(__FILE__)) . "/languages");
        }

        /**
         * Checking the Request Type
         * string $type ajax, frontend or admin
         * @return bool
         */
        public function is_request($type) {
            switch ($type) {
                case 'admin' :
                    return is_admin();
                case 'ajax' :
                    return defined('DOING_AJAX');
                case 'cron' :
                    return defined('DOING_CRON');
                case 'frontend' :
                    return (!is_admin() || defined('DOING_AJAX') ) && !defined('DOING_CRON');
            }
        }

        /**
         * Include required core files 
         * used in admin and on the frontend.
         */
        public function includes() {

            // Theme Domain Name
            require_once 'includes/cs-framework-config.php';
            require_once 'includes/cs-helpers.php';
            require_once 'assets/translate/cs-strings.php';

            // fonts
            require_once 'cs-fonts/cs-fonts.php';
            require_once 'cs-fonts/cs-fonts-admin.php';
            require_once 'cs-fonts/cs-fonts-admin-frontend.php';
            require_once 'cs-fonts/cs-fonts-frontend.php';

            require_once 'includes/cs-frame-functions.php';
            require_once 'includes/cs-mailchimp/cs-class.php';
            require_once 'includes/cs-mailchimp/cs-functions.php';
            require_once 'includes/cs-page-builder.php';

            // Post and Page Meta Boxes
            require_once 'includes/cs-metaboxes/cs-page-functions.php';
            require_once 'includes/cs-metaboxes/cs-page.php';
            require_once 'includes/cs-metaboxes/cs-post.php';
            require_once 'includes/cs-metaboxes/cs-product.php';
            // Shortcodes
            require_once 'includes/cs-shortcodes/backend/cs-maintain.php';
            require_once 'includes/cs-shortcodes/frontend/cs-maintenance.php';

            require_once 'includes/cs-shortcodes/backend/shortcode-contact-form.php';
            require_once 'includes/cs-shortcodes/frontend/shortcode-contact-form.php';

            // Importer
            require_once 'includes/cs-importer/api-functions.php';
            require_once 'includes/cs-importer/theme-importer.php';
            //  require_once 'includes/cs-importer/class-widget-data.php';
            // Auto Update Theme
            require_once 'includes/cs-importer/auto-update-theme.php';
            // Widgets
            require_once 'includes/cs-widgets/cs-social-media.php';
        }

        /**
         * Set plugin actions.
         * @return
         */
        public function plugin_actions() {

            add_action('init', array($this, 'load_plugin_textdomain'), 0);
            add_action('admin_enqueue_scripts', array($this, 'admin_plugin_files_enqueue'));
            add_action('wp_enqueue_scripts', array($this, 'frontend_files_enqueue'), 6);
            add_filter('wp_dp_cs_maintenance_options', array($this, 'create_wp_dp_cs_maintenance_options'), 10, 1);
        }

        /**
         * Get the plugin url.
         * @return string
         */
        public static function plugin_url() {
            return trailingslashit(plugins_url('/', __FILE__));
        }

        public static function plugin_dir() {
            return plugin_dir_path(__FILE__);
        }

        /**
         * Get the plugin path.
         * @return string
         */
        public static function plugin_path() {
            return untrailingslashit(plugin_dir_path(__FILE__));
        }

        /**
         * Default plugin 
         * admin files enqueue.
         * @return
         */
        public function admin_plugin_files_enqueue() {

            if ($this->is_request('admin')) {
                // admin js files
                $wp_dp_cs_scripts_path = plugins_url('/assets/js/cs-page-builder-functions.js', __FILE__);
                wp_enqueue_script('cs-frame-admin', $wp_dp_cs_scripts_path, array('jquery'));
                wp_localize_script(
                        'cs-frame-admin', 'wp_dp_globals', array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                        )
                );
            }
        }

        /**
         * Default plugin 
         * front files enqueue.
         * @return
         */
        public function frontend_files_enqueue() {

            if ($this->is_request('frontend')) {
                wp_register_script('countdown', plugins_url('/assets/js/jquery.countdown.js', __FILE__), '', '', true);
            }
        }

        public function under_construction() {
            global $wp_dp_cs_var_options;
            if (get_option('wp_dp_cs_underconstruction_redirecting') != 1) {
                if (isset($wp_dp_cs_var_options['wp_dp_cs_var_maintenance_switch']) && $wp_dp_cs_var_options['wp_dp_cs_var_maintenance_switch'] == 'on' && isset($wp_dp_cs_var_options['wp_dp_cs_var_maintinance_mode_page']) && !is_user_logged_in()) {

                    if ($wp_dp_cs_var_options['wp_dp_cs_var_maintinance_mode_page'] != '' && $wp_dp_cs_var_options['wp_dp_cs_var_maintinance_mode_page'] != '0') {
                        update_option('wp_dp_cs_underconstruction_redirecting', '1');
                        wp_redirect(get_permalink($wp_dp_cs_var_options['wp_dp_cs_var_maintinance_mode_page']));
                        exit;
                    } else {
                        
                        echo '
                        <script>
                            alert("' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_please_select_maintinance') . '");
                        </script>';
                    }
                }
            }
            if( $wp_dp_cs_var_options['wp_dp_cs_var_maintenance_switch'] != get_the_ID() ){
                update_option('wp_dp_cs_underconstruction_redirecting', '0');
            }
        }

        public function create_wp_dp_cs_maintenance_options($wp_dp_cs_var_settings) {
            global $wp_dp_cs_var_frame_static_text, $wp_dp_cs_var_options;
            $on_off_option = array(
                "show" => "on",
                "hide" => "off",
            );

            $wp_dp_cs_var_settings[] = array("name" => wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_maintenance_field_name'),
                "fontawesome" => 'icon-gears',
                "id" => "tab-maintenanace-mode",
                "std" => "",
                "type" => "main-heading",
                "options" => ""
            );
            $wp_dp_cs_var_settings[] = array("name" => wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_maintenance_field_name'),
                "id" => "tab-maintenanace-mode",
                "type" => "sub-heading"
            );
            $wp_dp_cs_var_settings[] = array("name" => wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_maintenance_field_name'),
                "id" => "",
                "std" => wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_maintenance_field_name'),
                "type" => "section",
                "options" => ""
            );
            $wp_dp_cs_var_settings[] = array("name" => wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_maintenance_field_name'),
                "desc" => "",
                "hint_text" => '',
                "id" => "wp_dp_cs_maintenance_options",
                "std" => "",
                "type" => "maintenance_mode"
            );
            $wp_dp_cs_var_settings[] = array("name" => wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_maintenance_field_name'),
                "desc" => "",
                "label_desc" => wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_maintenance_field_mode_hint'),
                "id" => "maintenance_switch",
                "std" => "off",
                "type" => "checkbox",
                "options" => $on_off_option
            );

            $wp_dp_cs_var_settings[] = array("name" => wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_maintenance_field_logo'),
                "desc" => "",
                "label_desc" => wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_maintenance_field_logo_hint'),
                "id" => "maintenance_logo_switch",
                "std" => "off",
                "type" => "checkbox",
                "options" => $on_off_option
            );
            $wp_dp_cs_var_settings[] = array("name" => wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_maintenance_field_social'),
                "desc" => "",
                "label_desc" => wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_maintenance_field_social_hint'),
                "id" => "maintenance_social_switch",
                "std" => "off",
                "type" => "checkbox",
                "options" => $on_off_option
            );

            $wp_dp_cs_var_settings[] = array("name" => wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_maintenance_field_newsletter'),
                "desc" => "",
                "label_desc" => wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_maintenance_field_newsletter_hint'),
                "id" => "maintenance_newsletter_switch",
                "std" => "off",
                "type" => "checkbox",
                "options" => $on_off_option
            );

            $args = array(
                'sort_order' => 'asc',
                'sort_column' => 'post_title',
                'hierarchical' => 1,
                'exclude' => '',
                'include' => '',
                'meta_key' => '',
                'meta_value' => '',
                'authors' => '',
                'child_of' => 0,
                'parent' => -1,
                'exclude_tree' => '',
                'number' => '',
                'offset' => 0,
                'post_type' => 'page',
                'post_status' => 'publish'
            );

            $wp_dp_cs_var_pages = get_pages($args);

            $wp_dp_cs_var_options_array = array();
            $wp_dp_cs_var_options_array[] = wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_maintenance_field_select_page');
            $maintinance_mode_page = isset($wp_dp_cs_var_options['wp_dp_cs_var_maintinance_mode_page']) ? $wp_dp_cs_var_options['wp_dp_cs_var_maintinance_mode_page'] : '';
            if ($maintinance_mode_page != '' && is_numeric($maintinance_mode_page) && $maintinance_mode_page > 0) {
                $wp_dp_cs_var_options_array[$maintinance_mode_page] = get_the_title($maintinance_mode_page);
            }

            $wp_dp_cs_var_settings[] = array("name" => wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_maintenance_field_mode_page'),
                "desc" => "",
                "label_desc" => wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_maintenance_field_mode_page_hint'),
                "id" => "maintinance_mode_page",
                "std" => wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_maintenance_select_page'),
                "type" => "custom_page_select",
                'classes' => 'chosen-select',
                "options" => $wp_dp_cs_var_options_array
            );
            $wp_dp_cs_var_settings[] = array("col_heading" => '',
                "type" => "col-right-text",
                "help_text" => ''
            );

            return $wp_dp_cs_var_settings;
        }

        public function wp_dp_cs_admin_dismiss_notice() {
            set_transient('admin_dismiss_notice', '1', 60 * 60 * 24 * 7);
            die;
        }

    }

}

/**
 *
 * @login popup script files
 */
if (!function_exists('wp_dp_cs_google_recaptcha_scripts')) {

    function wp_dp_cs_google_recaptcha_scripts() {
        wp_enqueue_script('google_recaptcha_scripts', wp_dp_cs_server_protocol() . 'www.google.com/recaptcha/api.js?onload=wp_dp_cs_multicap&render=explicit', '', '');
    }

}
/**
 *
 * @social login script
 */
if (!function_exists('wp_dp_cs_socialconnect_scripts')) {

    function wp_dp_cs_socialconnect_scripts() {
        wp_enqueue_script('socialconnect_js', plugins_url('/includes/cs-login/cs-social-login/media/js/cs-connect.js', __FILE__), '', '', true);
    }

}

/**
 * Framework Instance
 * @return
 *
 */
if (!function_exists('wp_dp_cs_var_frame')) {

    function wp_dp_cs_var_frame() {
        return wp_dp_framework::instance();
    }

}

// Global for backwards compatibility.
$GLOBALS['wp_dp_framework'] = wp_dp_cs_var_frame();
