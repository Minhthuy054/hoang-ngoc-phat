<?php
/**
 * Main plugin class (boots the plugin conditionally).
 *
 * @since 1.0
 * @package	Directory Box
 */
// Direct access not allowed.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin class (boots the plugin conditionally).
 */
if(!class_exists('Wp_dp_Email_Templates')){
class Wp_dp_Email_Templates {

    /**
     * Keep all admin messages
     *
     * @var array Array Containing all admin email_templates.
     */
    private $admin_messages = array();

    public function __construct() {
        add_action('admin_notices', array($this, 'admin_notices'));
        require_once( WP_DP_EMAIL_TEMPLATES_INCLUDES_DIR . '/class-templates-post-type.php' );
        require_once( WP_DP_EMAIL_TEMPLATES_INCLUDES_DIR . '/class-email-templates-data.php' );
        require_once( WP_DP_EMAIL_TEMPLATES_INCLUDES_DIR . '/class-templates-functions.php' );
    }

    /**
     * Prints admin notices.
     */
    public function admin_notices() {
        if (!empty($this->admin_messages)) {
            foreach ($this->admin_messages as $msg) {
                echo ($msg);
            }
        }
    }	
	

}

$obj = new Wp_dp_Email_Templates();
}