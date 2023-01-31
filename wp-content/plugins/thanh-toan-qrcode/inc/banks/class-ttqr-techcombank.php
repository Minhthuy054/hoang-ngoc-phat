<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 
 *
 *
 * @author   ttqr Team
 * @since    
 *
 */

require_once('class-ttqr-base.php');
class WC_Gateway_Ttqr_Techcombank extends WC_Base_Ttqr {
	public function __construct() {
		$this->bank_id 			  = 'techcombank';
		$this->bank_name		  = 	"Techcombank";

		$this->has_fields         = false;
		$this->method_title       = sprintf(__('Payment via %s', 'ttqr'), $this->bank_name);
		$this->method_description = __('Payment by bank transfer', 'ttqr');
		$this->title        = sprintf(__('Payment via %s', 'ttqr'), $this->bank_name);
		parent::__construct();
	}
	public function configure_payment()
	{
		$this->method_title       = sprintf(__('Payment via %s', 'ttqr'), $this->bank_name);
		$this->method_description = __('Make payment by bank transfer.', 'ttqr');
	}
}