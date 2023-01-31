<?php

global $gateways;
/**
 *  File Type: Payemnts Base Class
 *
 */
if ( ! class_exists('WP_DP_PAYMENTS') ) {

	class WP_DP_PAYMENTS {

		public $gateways;

		public function __construct() {
			global $gateways;
			$gateways['WP_DP_PAYPAL_GATEWAY'] = 'Paypal';
			$gateways['WP_DP_AUTHORIZEDOTNET_GATEWAY'] = 'Authorize.net';
			$gateways['WP_DP_PRE_BANK_TRANSFER'] = 'Pre Bank Transfer';
			$gateways['WP_DP_SKRILL_GATEWAY'] = 'Skrill-MoneyBooker';
		}

		 // Start function get string length

		public function wp_dp_get_string($length = 3) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randomString = '';
			for ( $i = 0; $i < $length; $i ++ ) {
				$randomString .= $characters[rand(0, strlen($characters) - 1)];
			}
			return $randomString;
		}

		// Start function for add transaction 

		public function wp_dp_add_transaction($fields = array()) {
			global $wp_dp_plugin_options;
			define("DEBUG", 1);
			define("USE_SANDPOX", 1);
			define("LOG_FILE", "./ipn.log");
			include_once('../../../../wp-load.php');
			if ( is_array($fields) ) {
				foreach ( $fields as $key => $value ) {
					update_post_meta((int) $fields['wp_dp_transaction_id'], "$key", $value);
				}
			}
			return true;
		}

	}

}
