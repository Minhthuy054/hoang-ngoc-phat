<?php

/**
 * Plugin Name: JobHunt Best Jobs
 * Plugin URI: http://themeforest.net/user/Chimpstudio/
 * Description: Job Best Jobs Customization Add on
 * Version: 1.0
 * Author: ChimpStudio
 * Author URI: http://themeforest.net/user/Chimpstudio/
 * @package Job Hunt
 * Text Domain: jobhunt-bestjobs
 */
// Direct access not allowed.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Jobhunt_Best_Jobs class.
 */
class Jobhunt_Best_Jobs {

    public $admin_notices;

    /**
     * construct function.
     */
    public function __construct() {

        // Define constants
        define( 'JOBHUNT_BEST_JOBS_PLUGIN_VERSION', '1.0' );
        define( 'JOBHUNT_BEST_JOBS_PLUGIN_DOMAIN', 'jobhunt-bestjobs' );
        define( 'JOBHUNT_BEST_JOBS_PLUGIN_URL', WP_PLUGIN_URL . '/jobhunt-bestjobs' );
        define( 'JOBHUNT_BEST_JOBS_CORE_DIR', WP_PLUGIN_DIR . '/jobhunt-bestjobs' );
        define( 'JOBHUNT_BEST_JOBS_LANGUAGES_DIR', JOBHUNT_BEST_JOBS_CORE_DIR . '/languages' );
        define( 'JOBHUNT_BEST_JOBS_INCLUDES_DIR', JOBHUNT_BEST_JOBS_CORE_DIR . '/includes' );
        $this->admin_notices = array();
        //admin notices
        add_action( 'admin_notices', array( $this, 'jobhunt_best_jobs_notices_callback' ) );
        if ( ! $this->check_dependencies() ) {
            return false;
        }

        // Initialize Addon
        add_action( 'init', array( $this, 'init' ) );
    }

    /**
     * Initialize application, load text domain, enqueue scripts, include classes and add actions
     */
    public function init() {
		// Add Plugin textdomain
        $locale = apply_filters('plugin_locale', get_locale(), 'jobhunt-bestjobs');
        load_textdomain('jobhunt-bestjobs', JOBHUNT_BEST_JOBS_LANGUAGES_DIR.'/jobhunt-bestjobs' . "-" . $locale . '.mo');
        load_plugin_textdomain( 'jobhunt-bestjobs', false, JOBHUNT_BEST_JOBS_LANGUAGES_DIR );

        // include Classes
        require_once ( JOBHUNT_BEST_JOBS_INCLUDES_DIR . '/class-shared-job-frontend.php' );
	}

    /**
     * Check plugin dependencies (JobHunt), nag if missing.
     *
     * @param boolean $disable disable the plugin if true, defaults to false.
     */
    public function check_dependencies( $disable = false ) {
        $result = true;
        $active_plugins = get_option( 'active_plugins', array() );
        if ( is_multisite() ) {
            $active_sitewide_plugins = get_site_option( 'active_sitewide_plugins', array() );
            $active_sitewide_plugins = array_keys( $active_sitewide_plugins );
            $active_plugins = array_merge( $active_plugins, $active_sitewide_plugins );
        }
        $jobhunt_is_active = in_array( 'wp-jobhunt/wp-jobhunt.php', $active_plugins );
        if ( ! $jobhunt_is_active ) {
            $this->admin_notices = '<div class="error">' . __( '<em><b>Job Hunt Best Jobs</b></em> needs the <b>Job Hunt</b> plugin. Please install and activate it.', 'jobhunt-bestjobs' ) . '</div>';
        }
        if ( ! $jobhunt_is_active ) {
            if ( $disable ) {
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                deactivate_plugins( array( __FILE__ ) );
            }
            $result = false;
        }
        return $result;
    }

    public function jobhunt_best_jobs_notices_callback() {
        if ( isset( $this->admin_notices ) && ! empty( $this->admin_notices ) ) {
            foreach ( $this->admin_notices as $value ) {
                echo $value;
            }
        }
    }

}

new Jobhunt_Best_Jobs();




