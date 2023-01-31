<?php

/**
 * Create Custom Post Type and it's meta boxes for Listing Alert Notifications
 *
 * @package	Directory Box
 */
// Direct access not allowed.
if ( ! defined('ABSPATH') ) {
	exit;
}

if ( ! function_exists('create_plugin_options') ) {

	/**
	 * Create Plugin Options
	 */
	function create_plugin_options($wp_dp_setting_options = array()) {

		if ( ! is_array($wp_dp_setting_options) ) {
			$wp_dp_setting_options = array();
		}
		$on_off_option = array( 'yes' => wp_dp_plugin_text_srt('wp_dp_yes'), 'no' => wp_dp_plugin_text_srt('wp_dp_no') );

		$wp_dp_setting_options[] = array(
			"name" => wp_dp_plugin_text_srt('wp_dp_notifications_listing_alerts'),
			"fontawesome" => 'icon-bell-o',
			"id" => 'tab-listing-alert-settings',
			"std" => "",
			"type" => "main-heading",
			"options" => ''
		);
		$wp_dp_setting_options[] = array(
			"name" => wp_dp_plugin_text_srt('wp_dp_notifications_listing_alerts'),
			"id" => "tab-listing-alert-settings",
			"extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_notifications_listing_alerts') . '"',
			"type" => "sub-heading"
		);
		$wp_dp_setting_options[] = array(
			"name" => wp_dp_plugin_text_srt('wp_dp_notifications_alert_frequencies'),
			"id" => "tab-user-alert-frequency",
			"std" => wp_dp_plugin_text_srt('wp_dp_notifications_frequency'),
			"type" => "section",
			"options" => ""
		);
		$wp_dp_setting_options[] = array(
			"name" => wp_dp_plugin_text_srt('wp_dp_notifications_annually'),
			"desc" => "",
			"hint_text" => '',
			"label_desc" => wp_dp_plugin_text_srt('wp_dp_notifications_annually_hint'),
			"id" => "frequency_annually",
			"std" => "",
			"type" => "checkbox",
			"options" => $on_off_option
		);
		$wp_dp_setting_options[] = array(
			"name" => wp_dp_plugin_text_srt('wp_dp_notifications_biannually'),
			"desc" => "",
			"hint_text" => '',
			"label_desc" => wp_dp_plugin_text_srt('wp_dp_notifications_biannually_hint'),
			"id" => "frequency_biannually",
			"std" => "",
			"type" => "checkbox",
			"options" => $on_off_option
		);
		$wp_dp_setting_options[] = array(
			"name" => wp_dp_plugin_text_srt('wp_dp_notifications_monthly'),
			"desc" => "",
			"hint_text" => '',
			"label_desc" => wp_dp_plugin_text_srt('wp_dp_notifications_monthly_hint'),
			"id" => "frequency_monthly",
			"std" => "",
			"type" => "checkbox",
			"options" => $on_off_option
		);
		$wp_dp_setting_options[] = array(
			"name" => wp_dp_plugin_text_srt('wp_dp_notifications_fortnightly'),
			"desc" => "",
			"hint_text" => '',
			"label_desc" => wp_dp_plugin_text_srt('wp_dp_notifications_fortnightly_hint'),
			"id" => "frequency_fortnightly",
			"std" => "",
			"type" => "checkbox",
			"options" => $on_off_option
		);
		$wp_dp_setting_options[] = array(
			"name" => wp_dp_plugin_text_srt('wp_dp_notifications_weekly'),
			"desc" => "",
			"hint_text" => '',
			"label_desc" => wp_dp_plugin_text_srt('wp_dp_notifications_weekly_hint'),
			"id" => "frequency_weekly",
			"std" => "",
			"type" => "checkbox",
			"options" => $on_off_option
		);
		$wp_dp_setting_options[] = array(
			"name" => wp_dp_plugin_text_srt('wp_dp_notifications_daily'),
			"desc" => "",
			"hint_text" => '',
			"label_desc" => wp_dp_plugin_text_srt('wp_dp_notifications_daily_hint'),
			"id" => "frequency_daily",
			"std" => "",
			"type" => "checkbox",
			"options" => $on_off_option
		);

		$wp_dp_setting_options[] = array(
			"col_heading" => wp_dp_plugin_text_srt('wp_dp_notifications_listing_alerts'),
			"type" => "col-right-text",
			"help_text" => ""
		);

		return $wp_dp_setting_options;
	}

}
// Add Plugin Options
add_filter('wp_dp_notification_plugin_settings', 'create_plugin_options', 10, 1);


if ( ! function_exists('wp_dp_listings_shortcode_admin_fields_callback') ) {

	/**
	 * Add Option to enable/disable 
	 * 'Email me listing like these' button 
	 * 'Listing Options Shortcode Element Settings'
	 */
	function wp_dp_listings_shortcode_admin_fields_callback($attrs = array()) {
		global $wp_dp_html_fields;

		$wp_dp_opt_array = array(
			'name' => wp_dp_plugin_text_srt('wp_dp_notifications_listing_alert_shortcode'),
			'desc' => '',
			'hint_text' => '',
			'label_desc' => wp_dp_plugin_text_srt('wp_dp_notifications_listing_alert_shortcode_hint'),
			'echo' => true,
			'field_params' => array(
				'std' => $attrs['listing_alert_button'],
				'id' => 'listing_alert_button[]',
				'cust_name' => 'listing_alert_button[]',
				'classes' => 'dropdown chosen-select',
				'options' => array(
					'enable' => wp_dp_plugin_text_srt('wp_dp_notifications_listing_alert_shortcode_enable'),
					'disable' => wp_dp_plugin_text_srt('wp_dp_notifications_listing_alert_shortcode_disable'),
				),
				'return' => true,
			),
		);

		$wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
	}

}
// Add Option to enable/disable 'Email me listing like these' button 'Listing Options Shortcode Element Settings'
add_action('wp_dp_listings_shortcode_admin_fields', 'wp_dp_listings_shortcode_admin_fields_callback', 10, 1);


if ( ! function_exists('wp_dp_save_listings_shortcode_admin_fields_callback') ) {

	/**
	 * Save Option to enable/disable 'Email me listing like these' button 'Listing Options Shortcode Element Settings'
	 */
	function wp_dp_save_listings_shortcode_admin_fields_callback($shortcode, $data, $wp_dp_counter_listing) {

		if ( isset($data['listing_alert_button'][$wp_dp_counter_listing]) && $data['listing_alert_button'][$wp_dp_counter_listing] != '' ) {
			$shortcode .= 'listing_alert_button="' . htmlspecialchars($data['listing_alert_button'][$wp_dp_counter_listing]) . '" ';
		}
		return $shortcode;
	}

}
// Add Plugin Options
add_filter('wp_dp_save_listings_shortcode_admin_fields', 'wp_dp_save_listings_shortcode_admin_fields_callback', 10, 3);


if ( ! function_exists('wp_dp_listings_shortcode_admin_default_attributes_callback') ) {

	/**
	 * Set default Option to enable/disable 'Email me listing like these' button 'Listing Options Shortcode Element Settings'
	 */
	function wp_dp_listings_shortcode_admin_default_attributes_callback($defaults) {
		$defaults['listing_alert_button'] = 'enable';
		return $defaults;
	}

}
// Register default variable on backend
add_filter('wp_dp_listings_shortcode_admin_default_attributes', 'wp_dp_listings_shortcode_admin_default_attributes_callback', 10, 1);
// Register default variable on frontend
add_filter('wp_dp_listings_shortcode_frontend_default_attributes', 'wp_dp_listings_shortcode_admin_default_attributes_callback', 10, 1);
