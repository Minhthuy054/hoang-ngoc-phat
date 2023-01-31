<?php

/**
 * File Type: Notifications Settings for Directory Box Plugin
 */
if ( ! class_exists('Wp_dp_Activity_Notifications_Plugin_Settings') ) {

	class Wp_dp_Activity_Notifications_Plugin_Settings {

		/**
		 * Start Contructer Function
		 */
		public function __construct() {
			add_filter('wp_dp_activity_notification_plugin_settings', array( &$this, 'wp_dp_activity_notification_plugin_settings_callback' ));
		}

		/**
		 * Add Notification Options in wp_dp plugin backend
		 * @ $wp_dp_setting_options contains the current settings
		 */
		public function wp_dp_activity_notification_plugin_settings_callback($wp_dp_setting_options = array()) {

			if ( ! is_array($wp_dp_setting_options) ) {
				$wp_dp_setting_options = array();
			}
			$on_off_option = array( "show" => "on", "hide" => "off" );


			$wp_dp_setting_options[] = array(
				'name' => wp_dp_plugin_text_srt('wp_dp_activity_notifications_settings'),
				'id' => 'activity-notifications-settings',
				'std' => wp_dp_plugin_text_srt('wp_dp_activity_notifications_settings'),
				'type' => 'section',
				'options' => ''
			);
			$wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_activity_notifications_heading'),
				"desc" => "",
				"hint_text" => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_activity_notifications_heading_hint'),
				"id" => "activity_notifications_switch",
				"std" => "on",
				"type" => "checkbox",
				"options" => $on_off_option
			);

			return $wp_dp_setting_options;
		}

		// End of class	
	}

	// Initialize Object
	$wp_dp_activity_notifications_plugin_settings = new Wp_dp_Activity_Notifications_Plugin_Settings();
}