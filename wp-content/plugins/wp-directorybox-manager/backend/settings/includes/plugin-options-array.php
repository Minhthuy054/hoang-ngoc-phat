<?php
global $wp_dp_settings_init;

require_once ABSPATH . '/wp-admin/includes/file.php';

// Home Demo
$wp_dp_demo = wp_dp_get_settings_demo('demo.json');

$wp_dp_settings_init = array(
	"plugin_options" => $wp_dp_demo,
);