<?php
/**
 * Directory Box Email Templates Module
 */

// Direct access not allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define constants
define( 'WP_DP_EMAIL_TEMPLATES_CORE_DIR', WP_PLUGIN_DIR . '/wp-directorybox-manager/modules/wp-dp-email-templates' );
define( 'WP_DP_EMAIL_TEMPLATES_INCLUDES_DIR', WP_DP_EMAIL_TEMPLATES_CORE_DIR . '/includes' );
define( 'WP_DP_EMAIL_TEMPLATES_PLUGIN_URL', WP_PLUGIN_URL . '/wp-directorybox-manager/modules/wp-dp-email-templates' );

require_once( WP_DP_EMAIL_TEMPLATES_INCLUDES_DIR . '/class-email-templates.php');

if ( ! function_exists( 'wp_dp_check_if_template_exists' ) ) {

	function wp_dp_check_if_template_exists( $slug, $type ) {
		global $wpdb;
		$post = $wpdb->get_row( "SELECT ID FROM " . $wpdb->prefix . "posts WHERE post_name = '" . $slug . "' && post_type = '" . $type . "'", 'ARRAY_A' );

		if ( isset( $post ) && isset( $post['ID'] ) ) {
			return $post['ID'];
		} else {
			return false;
		}
	}

}
