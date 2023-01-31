<?php

/**
 * Plugin Name: Thanh Toán Quét Mã QR Code Tự Động - MoMo, ViettelPay, VNPay và 40 ngân hàng Việt Nam
 * Plugin URI: https://wordpress.org/plugins/ttqr/
 * Description: Tự động xác nhận thanh toán quét mã QR Code MoMo, ViettelPay, VNPay, Vietcombank, Vietinbank, Techcombank, MB, ACB, VPBank, TPBank..
 * Author: Ttqr Team
 * Author URI: https://haibasoft.com/
 * Text Domain: ttqr
 * Domain Path: /languages
 * Version: 1.2.1
 * License: GNU General Public License v3.0
 */

//use TtqrPayment as GlobalTtqrPayment;

if (!defined('ABSPATH')) {
	exit;
}
define('TTQR_DIR', plugin_dir_path(__FILE__));
define('TTQR_URL', plugins_url('/', __FILE__));
define('TTQR_TEST', 0);
//require(__DIR__."/lib/phpqrcode/qrlib.php");
require(__DIR__."/inc/functions.php");

class TtqrPayment
{
	
	static $oauth_settings = array(
		//'email' => '',
	);
	static $default_settings = array(

		'bank_transfer'         =>
		array(
			'case_insensitive' => 'yes',
			'enabled' => 'yes',
			'title' => 'Chuyển khoản ngân hàng 24/7',
			'secure_token' => '',
			'transaction_prefix' => 'ABC',
			'acceptable_difference' => 1000,
			'authorization_code' => '',
			'viet_qr' => 'yes',

		),
		'bank_transfer_accounts' =>
		array(
			/*array(
				'account_name'   => '',
				'account_number' => '',
				'bank_name'      => '',
				'bin'      => 0,
				'connect_status'      => 0,
				'plan_status'      => 0,
				'is_show'      => 'yes',
			),*/
		),
		'order_status' =>
		array(
			'order_status_after_paid'   => 'wc-completed',
			'order_status_after_underpaid' => 'wc-processing',
		),

	);
	
	
	public function __construct()
	{
		// get the settings of the old version
		$this->domain = 'ttqr';
		add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));

		add_action('init', array($this, 'init'));

		$this->settings = self::get_settings();
	}

	
	public function init()
	{
		if (class_exists('WooCommerce')) {
			// Run this plugin normally if WooCommerce is active
			// Load the localization featureUnderpaid

			$this->main();
			// Add "Settings" link when the plugin is active
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_settings_link'));
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
				$settings = array('<a href="https://huong-dan-bck.haibasoft.com/" target="_blank">' . __('Docs', 'woocommerce') . '</a>');
				$links    = array_reverse(array_merge($links, $settings));

				return $links;
			});
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
				#$settings = array('<a href="https://wordpress.org/support/plugin/ttqr/reviews/" target="_blank">' . __('Review', 'woocommerce') . '</a>');
				#$links    = array_reverse(array_merge($links, $settings));
				return $links;
			});
			// Đăng kí thêm trạng thái 
			add_filter('wc_order_statuses', array($this, 'add_order_statuses'));
			register_post_status('wc-paid', array(
				'label'                     => __('Paid', 'ttqr'),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop(__('Paid', 'ttqr') . ' (%s)', __('Paid', 'ttqr') . ' (%s)')
			));
			register_post_status('wc-underpaid', array(
				'label'                     =>  __('Underpaid', 'ttqr'),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop(__('Underpaid', 'ttqr') . ' (%s)', __('Underpaid', 'ttqr') . ' (%s)')
			));
			wp_enqueue_style('ttqr-style', plugins_url('assets/css/style.css', __FILE__), array(), false, 'all');
			wp_enqueue_script('ttqr-qrcode', plugins_url('assets/js/easy.qrcode.js', __FILE__), array('jquery'), '', true);
			if(is_admin() && isset($_GET['page']) && $_GET['page']=='ttqr') {
				wp_enqueue_script('ttqr-js', plugins_url('assets/js/js.js', __FILE__), array('jquery'), '', true);
			}
			/*if(TTQR_TEST && is_dir(__DIR__.'/test/')) {
				wp_enqueue_style('ttqr-test-style', plugins_url('test/test.css', __FILE__), array(), false, 'all');
				wp_enqueue_script('ttqr-test-js', plugins_url('test/test.js', __FILE__), array('jquery'), '', true);
			}*/
			add_action('wp_ajax_nopriv_fetch_order_status_ttqr', array($this, 'fetch_order_status'));
			add_action('wp_ajax_fetch_order_status_ttqr', array($this, 'fetch_order_status'));
			//add_action('wp_ajax_nopriv_fetch_sync_order_ttqr', array($this, 'fetch_sync_order_ttqr'));
			//add_action('wp_ajax_fetch_sync_order_ttqr', array($this, 'fetch_sync_order_ttqr'));
			add_action('wp_ajax_nopriv_paid_order_ttqr', array($this, 'pc_payment_handler'));
			add_action('wp_ajax_paid_order_ttqr', array($this, 'pc_payment_handler'));

			//add_action('wp_ajax_nopriv_auth_app_ttqr', array($this, 'auth_app_ttqr'));
			//add_action('wp_ajax_auth_app_ttqr', array($this, 'auth_app_ttqr'));
			
			add_action('wp_ajax_nopriv_auth_sync_status_ttqr', array($this, 'auth_sync_status_ttqr'));
			add_action('wp_ajax_auth_sync_status_ttqr', array($this, 'auth_sync_status_ttqr'));

		} else {
			// Throw a notice if WooCommerce is NOT active
			add_action('admin_notices', array($this, 'notice_if_not_woocommerce'));
		}
	}

	//health check
	public function auth_sync_status_ttqr() {
		wp_send_json(['oauth_status'=>!empty(self::oauth_get_settings()), 'timestamp'=> time()]);
		die();
	}

	
	public function fetch_order_status()
	{
		if(empty($_REQUEST['order_id']) || !is_numeric($_REQUEST['order_id'])) {
			echo 'wc-pending';die();
		}
		$order = wc_get_order($_REQUEST['order_id']);
		$order_data = $order->get_data();
		$status = esc_attr($order_data['status']);
		echo 'wc-' . esc_html($status);
		die();
	}
	public function add_order_statuses($order_statuses)
	{
		$new_order_statuses = array();
		// add new order status after processing
		foreach ($order_statuses as $key => $status) {
			$new_order_statuses[$key] = $status;
		}
		$new_order_statuses['wc-paid'] = __('Paid', 'ttqr');
		$new_order_statuses['wc-underpaid'] = __('Underpaid', 'ttqr');
		return $new_order_statuses;
	}
	//Hàm này có thể giúp tạo ra một class Bank mới.
	public function gen_payment_gateway($gatewayName)
	{
		// $newClass = new class extends WC_Gateway_Ttqr_Base
		// {
		// }; //create an anonymous class
		// $newClassName = get_class($newClass); //get the name PHP assigns the anonymous class
		// class_alias($newClassName, $gatewayName); //alias the anonymous class with your class name
	}


	public function main()
	{

		if (is_admin()) {
			include(TTQR_DIR . 'inc/class-ttqr-admin-page.php');
			$this->Admin_Page = new Ttqr_Admin_Page();
		}
		$settings = self::get_settings();
		$this->settings = $settings;
		//add_action('woocommerce_api_' . self::$webhook_oauth2, array($this, 'ttqr_oauth2_handler'));
		//add_action('woocommerce_api_' . self::$webhook_route, array($this, 'pc_payment_handler'));

		if ('yes' == $settings['bank_transfer']['enabled'] ) {
			// chỗ này e tách ra ngoài code cho clean mà nó k nhận (gộp woocommerce_payment_gateways)
			
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-acb.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-mbbank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-techcombank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-timoplus.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-vpbank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-vietinbank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-ocb.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-tpbank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-vietcombank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-bidv.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-agribank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-lienviet.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-hdbank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-msb.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-sacombank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-shb.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-vib.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-scb.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-abbank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-bacabank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-eximbank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-namabank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-ncb.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-seabank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-vietcapitalbank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-cake.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-tnex.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-cimbbank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-dongabank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-hsbc.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-baovietbank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-oceanbank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-vietabank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-vietbank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-saigonbank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-kienlongbank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-pvcombank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-pulicbank.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-vrbank.php');
			//require_once(TTQR_DIR . 'inc/banks/class-ttqr-moca.php');
			//require_once(TTQR_DIR . 'inc/banks/class-ttqr-shopeepay.php');
			//require_once(TTQR_DIR . 'inc/banks/class-ttqr-smartpay.php');			
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-vinid.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-vnpay.php');
			//require_once(TTQR_DIR . 'inc/banks/class-ttqr-zalopay.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-momo.php');
			require_once(TTQR_DIR . 'inc/banks/class-ttqr-viettelpay.php');

			#require_once(TTQR_DIR . 'inc/banks/class-ttqr-vnptpay.php');
			//require_once(TTQR_DIR . 'inc/banks/class-ttqr-mobifonepay.php');
			//require_once(TTQR_DIR . 'inc/banks/class-ttqr-vtcpay.php');
			//require_once(TTQR_DIR . 'inc/banks/class-ttqr-vimo.php');

			/*foreach ($settings['bank_transfer_accounts'] as $account) {
				//$bank_name = explode('-',$account);
				//if (isset($account['is_show']) && $account['is_show'] == 'yes') {
					if (strtolower($account['bank_name']) == 'momo')
						
					if (strtolower($account['bank_name']) == 'acb')
						
					if (strtolower($account['bank_name']) == 'mbbank')
						
					if (strtolower($account['bank_name']) == 'techcombank')
						
					if (strtolower($account['bank_name']) == 'timoplus')
						
					if (strtolower($account['bank_name']) == 'vpbank')
						
					if (strtolower($account['bank_name']) == 'vietinbank')
						
					if (strtolower($account['bank_name']) == 'ocb')
						
					if (strtolower($account['bank_name']) == 'tpbank')
						
					if (strtolower($account['bank_name']) == 'vietcombank')
						
					if (strtolower($account['bank_name']) == 'bidv')
						
					if (strtolower($account['bank_name']) == 'agribank')
						
				//}
			}*/
			add_filter('woocommerce_payment_gateways', function ($gateways) {
				$settings = self::get_settings();
				#$gateways[] = 'WC_Gateway_Ttqr_Phone';
				$gateways[] = 'WC_Gateway_Ttqr_ACB';
				$gateways[] = 'WC_Gateway_Ttqr_Mbbank';
				$gateways[] = 'WC_Gateway_Ttqr_Techcombank';
				$gateways[] = 'WC_Gateway_Ttqr_TimoPlus';
				$gateways[] = 'WC_Gateway_Ttqr_Vpbank';
				$gateways[] = 'WC_Gateway_Ttqr_Vietinbank';
				$gateways[] = 'WC_Gateway_Ttqr_OCB';
				$gateways[] = 'WC_Gateway_Ttqr_TPbank';
				$gateways[] = 'WC_Gateway_Ttqr_Vietcombank';
				$gateways[] = 'WC_Gateway_Ttqr_BIDV';
				$gateways[] = 'WC_Gateway_Ttqr_Agribank';
				$gateways[] = 'WC_Gateway_Ttqr_Lienviet';
				$gateways[] = 'WC_Gateway_Ttqr_Hdbank';				
				$gateways[] = 'WC_Gateway_Ttqr_MSB';
				$gateways[] = 'WC_Gateway_Ttqr_Sacombank';
				$gateways[] = 'WC_Gateway_Ttqr_SHB';
				$gateways[] = 'WC_Gateway_Ttqr_SCB';
				$gateways[] = 'WC_Gateway_Ttqr_ABBank';
				$gateways[] = 'WC_Gateway_Ttqr_BacABank';
				$gateways[] = 'WC_Gateway_Ttqr_Eximbank';
				$gateways[] = 'WC_Gateway_Ttqr_NamABank';
				$gateways[] = 'WC_Gateway_Ttqr_NCB';
				$gateways[] = 'WC_Gateway_Ttqr_SeABank';
				$gateways[] = 'WC_Gateway_Ttqr_VietCapitalBank';
				$gateways[] = 'WC_Gateway_Ttqr_Cake';
				$gateways[] = 'WC_Gateway_Ttqr_Tnex';
				$gateways[] = 'WC_Gateway_Ttqr_CIMBBank';
				$gateways[] = 'WC_Gateway_Ttqr_DongABank';
				$gateways[] = 'WC_Gateway_Ttqr_HSBC';
				$gateways[] = 'WC_Gateway_Ttqr_BaovietBank';
				$gateways[] = 'WC_Gateway_Ttqr_OceanBank';
				$gateways[] = 'WC_Gateway_Ttqr_VietABank';
				$gateways[] = 'WC_Gateway_Ttqr_VietBank';
				$gateways[] = 'WC_Gateway_Ttqr_SaigonBank';
				$gateways[] = 'WC_Gateway_Ttqr_Kienlongbank';
				$gateways[] = 'WC_Gateway_Ttqr_PVcomBank';
				$gateways[] = 'WC_Gateway_Ttqr_PulicBank';
				$gateways[] = 'WC_Gateway_Ttqr_VRBank';
				
				$gateways[] = 'WC_Gateway_Ttqr_ViettelPay';
				//$gateways[] = 'WC_Gateway_Ttqr_Moca';
				$gateways[] = 'WC_Gateway_Ttqr_Momo';
				//$gateways[] = 'WC_Gateway_Ttqr_Shopeepay';
				//$gateways[] = 'WC_Gateway_Ttqr_Smartpay';
				$gateways[] = 'WC_Gateway_Ttqr_VIB';
				$gateways[] = 'WC_Gateway_Ttqr_Vinid';
				$gateways[] = 'WC_Gateway_Ttqr_Vnpay';
				//$gateways[] = 'WC_Gateway_Ttqr_Zalopay';

				#$gateways[] = 'WC_Gateway_Ttqr_VNPTPay';
				//$gateways[] = 'WC_Gateway_Ttqr_MobiFonePay';
				//$gateways[] = 'WC_Gateway_Ttqr_Vtcpay';
				//$gateways[] = 'WC_Gateway_Ttqr_Vimo';
				

				/*foreach ($settings['bank_transfer_accounts'] as $account) {
					#if (strtolower($account['bank_name']) == 'momo')
						
					#if (strtolower($account['bank_name']) == 'acb')
						
					#if (strtolower($account['bank_name']) == 'mbbank')
						
					#if (strtolower($account['bank_name']) == 'techcombank')
						
					#if (strtolower($account['bank_name']) == 'timoplus')
						
					#if (strtolower($account['bank_name']) == 'vpbank')
						
					#if (strtolower($account['bank_name']) == 'vietinbank')
						
					#if (strtolower($account['bank_name']) == 'ocb')
						
					#if (strtolower($account['bank_name']) == 'tpbank')
						
					#if (strtolower($account['bank_name']) == 'vietcombank')
						
					#if (strtolower($account['bank_name']) == 'bidv')
						
					#if (strtolower($account['bank_name']) == 'agribank')
						
				}*/
				// print_r ($gateways);
				return $gateways;
			});
		}
	}
	public function notice_if_not_woocommerce()
	{
		$class = 'notice notice-warning';

		$message = __(
			'Ttqr is not running because WooCommerce is not active. Please activate both plugins.',
			'ttqr'
		);
		printf('<div class="%1$s"><p><strong>%2$s</strong></p></div>', $class, $message);
	}
	static function get_settings()
	{
		$settings = get_option('ttqr', self::$default_settings);
		$settings = wp_parse_args($settings, self::$default_settings);
		return $settings;
	}
	static function update_settings(array $data) {
		if(!empty($data)) update_option('ttqr', $data);
	}
	static function oauth_get_settings()
	{
		$settings = get_option('ttqr_oauth', self::$oauth_settings);
		$settings = wp_parse_args($settings, self::$oauth_settings);
		return $settings;
	}
	static function get_bank_icon($name, $img=false) {
		//if(true || is_dir(TTQR_DIR.'/assets/'.$name.'.png')) return; 
		$url = TTQR_URL.'/assets/'.strtolower($name).'.png';
		return $img? '<img class="ttqr-bank-icon" title="'.strtoupper($name).'" src="'.$url.'"/>': $url;
	}
	static function noQRBankLogo($name) {
		return !in_array($name, ['momo','viettelpay']);
	}
	static function get_list_banks()
	{
		$banks = array(
			'acb' => 'ACB',
			'bidv' => 'BIDV',
			'mbbank' => 'MB Bank',
			'momo' => 'Momo',
			'ocb' => 'OCB',
			'timoplus' => 'Timo Plus',
			'tpbank' => 'TPBank',
			'vietcombank' => 'Vietcombank',
			'vpbank' => 'VPBank',
			'vietinbank' => 'Vietinbank',
			'techcombank' => 'Techcombank',
			'agribank' => 'Agribank',
			'viettelpay'=> 'ViettelPay',
			'hdbank'=> 'HDBank',
			'moca'=> 'Moca',
			'msb'=> 'MSB',
			'sacombank'=> 'Sacombank',
			'shb'=> 'SHB',
			'shopeepay'=> 'ShopeePay',
			'smartpay'=> 'SmartPay',
			'vib'=> 'VIB',
			'vinid'=> 'VinID',
			'vnpay'=> 'VNPay',
			'zalopay'=> 'ZaloPay',
		);
		return $banks;
	}

	static function get_list_bin()
	{
		$banks = array(
			'970416' => 'acb',
			'970418' => 'bidv',
			'970422' => 'mbbank',
			'970448' => 'ocb',
			'970454' => 'timoplus',
			'970423' => 'tpbank',
			'970436' => 'vietcombank',
			'970432' => 'vpbank',
			'970415' => 'vietinbank',
			'970407' => 'techcombank',
			'970405' => 'agribank',
			'970449' => 'lvp',
			'970437'=> 'hdbank',
			'970426'=> 'msb',
			'970429'=> 'sacombank',
			'970443'=> 'shb',
			'970441'=> 'vib',
			'970425' => 'abbank',
			'970409' => 'bacabank',
			'970438' => 'baovietbank',
			'422589' => 'cimbbank',
			'970406' => 'dongabank',
			'970431' => 'eximbank',
			'458761' => 'hsbc',
			'970452' => 'kienlongbank',
			'970422' => 'mbbank',
			'970428' => 'namabank',
			'970419' => 'ncb',
			'970414' => 'oceanbank',
			'970439' => 'pulicbank',
			'970412' => 'pvcombank',
			'970400' => 'saigonbank',
			'970429' => 'scb',
			'970440' => 'seabank',
			'970423' => 'tpbank',
			'970427' => 'vietabank',
			'970433' => 'vietbank',
			'970454' => 'vietcapitalbank',
			'970421' => 'vrbank',
		);
		return $banks;
	}
	static function connect_status_banks()
	{
		$status = array(
			'0' => __('Inactive', 'ttqr'),
			'1' =>  array(
				'0' => __('Active', 'ttqr'),
				'1' => __('Trial', 'ttqr'),
				'2' => __('Out of money', 'ttqr')
			)
		);
		return $status;
	}
	static function transaction_text($code, $settings) {
		if($settings==null) $settings = self::get_settings();
		$texts = !empty($settings['bank_transfer']['extra_text'])? $settings['bank_transfer']['extra_text']: '';
		if($texts) {
			$texts = array_filter(explode("\n", $texts));
			if(count($texts)) {
				return $texts[array_rand($texts)].' '. $code;
				//return (array_rand([1,0])==1)? $text. ' '. $code : $code.' '.$text;
			}
		}
		return $code;
	}

	public function add_settings_link($links)
	{
		$settings = array('<a href="' . admin_url('admin.php?page=ttqr') . '">' . __('Settings', 'ttqr') . '</a>');
		$links    = array_reverse(array_merge($links, $settings));

		return $links;
	}

	//run by webhook
	public function pc_payment_handler()
	{
		$txtBody = file_get_contents('php://input');
		$jsonBody = json_decode($txtBody); //convert JSON into array
		if (!$txtBody || !$jsonBody) {
			wp_send_json(['error'=>"Missing body"]) ;
			die();
		}
		if (isset($jsonBody->error) && $jsonBody->error != 0) {
			wp_send_json(['error'=> "An error occurred"]);
			die();
		}
		$header = ttqr_getHeader();
		$token = isset($header["Secure-Token"])? $header["Secure-Token"]: '';
		if (strcasecmp($token, $this->settings['bank_transfer']['secure_token']) !== 0) {
			wp_send_json(['error'=> "Missing secure_token or wrong secure_token"]);
			die();
		}
		$result = ['msg'=>[],'error'=>1,'rawInput'=> $txtBody];

		if(!empty($jsonBody->data))
		foreach ($jsonBody->data as $key => $transaction) {
			$result['_ok']=1;	//detect webhook ok
			$des = $transaction->description;
			if(ttqr_is_JSON($des)) {
				$desJson = is_string($des)? json_decode($des, true): $des;
				if(is_array($desJson)) {
					if(isset($desJson['code'])) {
						$des = $desJson['code'];
						//$update['bank_transfer']['code'] = $desJson['code'];
					}
					//if(isset($desJson['app'])) $update['bank_transfer']['app'] = $desJson['app'];
				}
			}
			$order_id = ttqr_parse_order_id($des, $this->settings['bank_transfer']['transaction_prefix'], $this->settings['bank_transfer']['case_insensitive']);
			if (is_null($order_id)) {
				wp_send_json (['error'=>"Order ID not found from transaction content: " . $des . "\n"]);
				continue;
			}
			//echo ("Start processing orders with transaction code " . $order_id . "...\n");
			$order = wc_get_order($order_id);
			if (!$order) {
				continue;
			}
			if($order->get_status()=='completed') {
				$result['error']=0;
				$result['msg'][]= ("Transaction processed before " . $order_id . " success\n");
				break;
			}
			//echo(var_dump(wc_get_order_statuses()));
			$money = $order->get_total();
			$paid = $transaction->amount;
			/*$today = date_create(date("Y-m-d"));
			$date_transaction = date_create($transaction->when);
			$interval = date_diff($today, $date_transaction);
			if ($interval->format('%R%a') < -2) {
				# code...Giao dịch quá cũ, không xử lý
				wp_send_json (['error'=>__('Transaction is too old, not processed', 'ttqr')]);
				die();
			}*/
			$total = number_format($transaction->amount, 0);
			//$order_note = sprintf(__('Ttqr announces received <b>%s</b> VND, content <B>%s</B> has been moved to <b>Account number %s</b>', 'ttqr'), $total, $des, $transaction->subAccId);
			$order_note = "Ttqr thông báo nhận <b>{$total}</b> VND, nội dung <B>{$des}</B> chuyển vào <b>STK {$transaction->subAccId}</b>";
			$order->add_order_note($order_note);
			$order->update_meta_data('ttqr_ndck', $des);

			// $order_note_overpay = "ttqr thông báo <b>{$total}</b> VND, nội dung <b>$des</b> chuyển khoản dư vào <b>STK {$transaction->subAccId}</b>";
			$acceptable_difference = abs($this->settings['bank_transfer']['acceptable_difference']);
			if ($paid < ($money  - $acceptable_difference>0? $money  - $acceptable_difference: $money )) {
				$order->add_order_note(__('The order is underpaid so it is not completed', 'ttqr'));
				$status_after_underpaid = $this->settings['order_status']['order_status_after_underpaid'];

				if ($status_after_underpaid && $status_after_underpaid != "wc-default") {
					$status = substr($this->settings['order_status']['order_status_after_underpaid'], 3);
					$order->update_status($status);
				}
				$result['error']=1;
				$result['msg'][] = __('The order is underpaid so it is not completed', 'ttqr');

			} else {
				$order->payment_complete();
				wc_reduce_stock_levels($order_id);
				$status_after_paid = $this->settings['order_status']['order_status_after_paid'];

				if ($status_after_paid && $status_after_paid != "wc-default") {
					$order->update_status($status_after_paid);
				}
				//NEU THANH TOAN DU THI GHI THEM 1 cai NOTE 
				if ($paid > $money + $acceptable_difference) {
					$order->add_order_note(__('Order has been overpaid', 'ttqr'));
					$result['msg'][] = __('Order has been overpaid', 'ttqr');
				}
				$result['error']=0;
				$result['msg'][]= ("Transaction processing  " . $order_id . " success\n");
			}
			
			//$result['success']=1;
			$order->save();
			if(empty($result['error'])) break;
		}
		$result['msg'] = join(". ", $result['msg']);
		wp_send_json($result);
		die();
		//TODO: Nghiên cứu việc gửi mail thông báo đơn hàng thanh toán hoàn tất.
	}
	
	function load_plugin_textdomain()
	{
		load_plugin_textdomain($this->domain, false, dirname(plugin_basename(__FILE__))  . '/languages');
	}
}
new TtqrPayment();
