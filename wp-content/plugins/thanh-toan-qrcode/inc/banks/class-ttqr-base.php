<?php
/*
*
* WC Base Payment Gateway
*
*/

if (!defined('ABSPATH')) exit;

if (!class_exists('WC_Payment_Gateway')) return;


abstract class WC_Base_Ttqr extends WC_Payment_Gateway
{
	abstract public function configure_payment();

	/**
	 * Array of locales
	 *
	 * @var array
	 */
	public $locale;

	/**
	 * Constructor for the gateway.
	 */
	public function __construct()
	{

		$this->id                 = 'ttqr_up_' . $this->bank_id;
		$this->icon =  apply_filters('woocommerce_icon_' . $this->bank_id, plugins_url('../../assets/' . $this->bank_id . '.png', __FILE__));
		$this->has_fields         = false;
		$this->init_form_fields();
		$this->init_settings();
		// Define user set variables.
		$this->title        = $this->get_option('title');
		$this->description  = $this->get_option('description');
		$this->instructions = $this->get_option('instructions');
		$this->order_content = '';
		global $wp_session;
		// handling cache and order information
		if (true || !isset($wp_session['ttqr_banks_setting'])) {
			$this->plugin_settings = TtqrPayment::get_settings();
			$this->oauth_settings = TtqrPayment::oauth_get_settings();
			#$wp_session['ttqr_banks_setting'] = $this->plugin_settings;
		} else {
			//$this->plugin_settings = $wp_session['ttqr_banks_setting'];
		}
		// BACS account fields shown on the thanks page and in emails.
		$this->account_details = !empty($this->plugin_settings['bank_transfer_accounts'][$this->bank_id])? $this->plugin_settings['bank_transfer_accounts'][$this->bank_id]: array();
			/*array_filter(isset($this->plugin_settings['bank_transfer_accounts'])?$this->plugin_settings['bank_transfer_accounts']:[], function ($account, $k) {
				return $account['bank_name'] == $this->bank_id ;//&& $account['is_show'] == 'yes';
			}, ARRAY_FILTER_USE_BOTH);*/
		#if($this->bank_id=='acb')_print($this->account_details);
		// Actions.
		add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'save_account_details' ) );	
		#if(strpos($this->id,'momo')!==false)_print('woocommerce_thankyou_' .$this->id);
		add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'),100);
		add_action('woocommerce_email_before_order_table', array($this, 'email_instructions'), 10, 3);

		//add_action('admin_footer', array($this, 'print_footer'));
		// Customer Emails.

	}
	public function init_form_fields()
	{
		$this->form_fields = array(
			'enabled'         => array(
				'title'   => __('Enable/Disable', 'woocommerce'),
				'type'    => 'checkbox',
				'label'   => __('Enable bank transfer', 'woocommerce'),
				'default' => 'no',	//no,yes
			),
			'title'           => array(
				'title'       => __('Title', 'woocommerce'),
				'type'        => 'text',
				'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
				'default'     => sprintf(__('Transfer %s', 'ttqr'),$this->bank_name),
				'desc_tip'    => true,
			),
			'description'     => array(
				'title'       => __('Description', 'woocommerce'),
				'type'        => 'textarea',
				'description' => __('Payment method description that the customer will see on your checkout.', 'woocommerce'),
				'default'     => sprintf(__("Transfer money to our account<b> %s</b>. The order will be confirmed immediately after the transfer", 'ttqr'), $this->bank_name),
				//'default'     => __('Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.', 'woocommerce'),
				'desc_tip'    => true,
			),
			'instructions'    => array(
				'title'       => __('Instructions', 'woocommerce'),
				'type'        => 'textarea',
				'description' => __('Instructions that will be added to the thank you page and emails.', 'woocommerce'),
				'default'     => '',
				'desc_tip'    => true,
			),
			'account_details' => array(
				'type' => 'account_details',
			),
		);
	}

	/**
	 * Generate account details html.
	 *
	 * @return string
	 */
	public function generate_account_details_html()
	{
		#die;
		ob_start();
		$country = WC()->countries->get_base_country();
		$locale  = $this->get_country_locale();
		// Get sortcode label in the $locale array and use appropriate one.
		$sortcode = isset($locale[$country]['sortcode']['label']) ? $locale[$country]['sortcode']['label'] : __('Sort code', 'woocommerce');

?>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php esc_html_e('Account details:', 'woocommerce'); ?></th>
			<td class="forminp" id="bacs_accounts">
				<div class="wc_input_table_wrapper">
					<table class="widefat wc_input_table sortable" cellspacing="0">
						<thead>
							<tr>
								<th class="sort">&nbsp;</th>
								<th><?php esc_html_e('Account name', 'woocommerce'); ?></th>
								<th><?php esc_html_e('Account number', 'woocommerce'); ?></th>
								<!-- <th><?php #esc_html_e('Bank name', 'woocommerce'); ?></th> -->
							</tr>
						</thead>
						<tbody class="accounts">
							<?php
							$i = -1;
							if ($this->account_details) {
								foreach ($this->account_details as $account) {
									$i++;
									echo '<tr class="account">
										<td class="sort"></td>
										<td><input type="text" value="' . esc_attr(wp_unslash($account['account_name'])) . '" name="bacs_account_name[' . esc_attr($i) . ']" /></td>
										<td><input type="text" value="' . esc_attr($account['account_number']) . '" name="bacs_account_number[' . esc_attr($i) . ']" /></td>
										
									</tr>';
									//<td><input type="text" value="' . esc_attr(wp_unslash($account['bank_name'])) . '" name="bacs_bank_name[' . esc_attr($i) . ']" /></td>
								}
							}
							?>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="7"><a href="#" class="add button"><?php esc_html_e('+ Add account', 'woocommerce'); ?></a> <a href="#" class="remove_rows button"><?php esc_html_e('Remove selected account(s)', 'woocommerce'); ?></a></th>
							</tr>
						</tfoot>
					</table>
				</div>
				<script type="text/javascript">
					jQuery(function() {
						jQuery('#bacs_accounts').on('click', 'a.add', function() {

							var size = jQuery('#bacs_accounts').find('tbody .account').length;

							jQuery('<tr class="account">\
									<td class="sort"></td>\
									<td><input type="text" name="bacs_account_name[' + size + ']" /></td>\
									<td><input type="text" name="bacs_account_number[' + size + ']" /></td>\
								</tr>').appendTo('#bacs_accounts table tbody');
							//<td><input type="text" name="bacs_bank_name[' + size + ']" /></td>\

							return false;
						});
					});
				</script>
			</td>
		</tr>
<?php
		return ob_get_clean();
	}

	/**
	 * Save account details table.
	 */
	public function save_account_details() {

		$accounts = array();

		// pttqrs:disable WordPress.Security.NonceVerification.Missing -- Nonce verification already handled in WC_Admin_Settings::save()
		if ( isset( $_POST['bacs_account_name'] ) && isset( $_POST['bacs_account_number'] )
			  ) { //&& isset( $_POST['bacs_bank_name'] && isset( $_POST['bacs_sort_code'] ) && isset( $_POST['bacs_iban'] ) && isset( $_POST['bacs_bic'] )

			$account_names   = wc_clean( wp_unslash( $_POST['bacs_account_name'] ) );
			$account_numbers = wc_clean( wp_unslash( $_POST['bacs_account_number'] ) );
			#$bank_names      = wc_clean( wp_unslash( $_POST['bacs_bank_name'] ) );
			#$sort_codes      = wc_clean( wp_unslash( $_POST['bacs_sort_code'] ) );
			#$ibans           = wc_clean( wp_unslash( $_POST['bacs_iban'] ) );
			#$bics            = wc_clean( wp_unslash( $_POST['bacs_bic'] ) );

			foreach ( $account_names as $i => $name ) {
				if ( ! isset( $account_names[ $i ] ) ) {
					continue;
				}
				//$account_numbers[ $i ] 
				$accounts[ ] = array(
					'account_name'   => $account_names[ $i ],
					'account_number' => $account_numbers[ $i ],
					'bank_name'      => $this->bank_id,
					#'sort_code'      => $sort_codes[ $i ],
					#'iban'           => $ibans[ $i ],
					#'bic'            => $bics[ $i ],
				);
			}
		}
		
		// pttqrs:enable
		if(!empty($accounts)) {
			$this->plugin_settings['bank_transfer_accounts'][$this->bank_id] = $accounts;
			
			TtqrPayment::update_settings($this->plugin_settings);
			
		}
		#update_option( 'woocommerce_bacs_accounts', $accounts );
	}
	
	/**
	 * Output for the order received page.
	 *
	 * @param int $order_id Order ID.
	 */
	public function thankyou_page($order_id)
	{
		
		if ($this->instructions) {
			echo wp_kses_post(wpautop(wptexturize(wp_kses_post($this->instructions))));
		}
		#ttqr_console_log($this->account_details);
		global $wp_session;
		if (0&& isset($wp_session['input_thank'])) {
		} else {
			//$wp_session['input_thank'] = true;
			$this->bank_details($order_id, false);
		}
	}

	/**
	 * Add content to the WC emails.
	 *
	 * @param WC_Order $order Order object.
	 * @param bool     $sent_to_admin Sent to admin.
	 * @param bool     $plain_text Email format: plain text or HTML.
	 */
	public function email_instructions($order, $sent_to_admin, $plain_text = false)
	{
		if (!$sent_to_admin && $this->id === $order->get_payment_method() && $order->has_status('on-hold')) {
			if ($this->instructions) {
				echo wp_kses_post(wpautop(wptexturize($this->instructions)) . PHP_EOL);
			}
			global $wp_session;
			if (0&& isset($wp_session['input_thank'])) {
			} else {
				//$wp_session['input_thank'] = true;
				$this->bank_details($order->get_id(), true);
			}
		}
	}

	/**
	 * Get bank details and place into a list format.
	 *
	 * @param int $order_id Order ID.
	 */
	private function bank_details($order_id = '', $is_sent_email = false)
	{
		/*if (!$is_sent_email) {
			ttqr_console_log($this->account_details);
		}*/
		if (empty($this->account_details)) {
			return;
		}
		// Get order and store in $order.
		$order = wc_get_order($order_id);
		$order_status  = $order->get_status();
		$to = $order->get_billing_email();
		$subject = 'Thanh Toán đơn hàng';
		// Get the order country and country $locale.
		$country = $order->get_billing_country();
		$locale  = $this->get_country_locale();
		// Get sortcode label in the $locale array and use appropriate one.
		$sortcode = isset($locale[$country]['sortcode']['label']) ? $locale[$country]['sortcode']['label'] : __('Sort code', 'woocommerce');
		$bacs_accounts = apply_filters( 'woocommerce_'.$this->bank_id.'_accounts', $this->account_details, $order_id );
		$is_payment = false;
		if ("wc-{$order_status}" ==  $this->plugin_settings['order_status']['order_status_after_paid']) {
			$is_payment = true;
		}
		$auto_check = !empty($this->plugin_settings['auto_check_status']) && (int)$this->plugin_settings['auto_check_status'];

		if (!empty($bacs_accounts)) {
			$account_html = '';
			$has_details  = false;
			$bin_list = array_flip(TtqrPayment::get_list_bin());

			$output = '';
			if(count($bacs_accounts)>1) $output.= '<p>Thanh toán một trong các tài khoản dưới đây</p>';
			if(!$is_payment) {
				$output .= "<style>#image_loading{margin-left:auto;margin-right:auto;width:35%}#btnDownloadQR{width:100%;border-radius:0;padding-left:10px!important;padding-right:10px!important;border-color:#0274be;background-color:#0274be;color:#fff;line-height:1}td{width:25%}#qrcode canvas{border:2px solid #ccc;padding:20px}.woocommerce-ttqr-ttqr-qr-scan{text-align:center;margin-top:0}.woocommerce-ttqr-ttqr-bank-details{text-align:center;margin-top:10px}.woocommerce-ttqr-ttqr-qr-scan img{margin:auto}.qhtp-timer{font-size:25px;}";
				
				if(count($bacs_accounts)<=1) $output.= '#banks_details >h3{display:none;}';
				$output.= '</style>';
			}
$output.= '<div id="banks_details">';

if($is_payment) {
	$output .= '<div class="ttqr-success"><img src="' . TTQR_URL.'/assets/success-icon.png' . '"  style = "max-width: 100px; margin: 20px" id =""/>';
	$output.='<h2>Bạn đã thanh toán</h2>';
	$output.= '<p>Chúng tôi đã nhận được đơn hàng của bạn và sẽ sớm liên hệ với bạn.</p></div>';
	//$output.= '</div>';
}
else {
	$payment_gateways = WC()->payment_gateways->payment_gateways();
			foreach ($bacs_accounts as $i=> $bacs_account) {
				$bacs_account = (object) $bacs_account;

				if ( $bacs_account->account_name) {
					$account_html .= '<h3 class="wc-bacs-bank-details-account-name">' . wp_kses_post(wp_unslash($bacs_account->account_name)) . ':</h3>' . PHP_EOL;
				}
				
				$bacs_account->bin = isset($bin_list[$this->bank_id])? $bin_list[$this->bank_id]: $this->bank_id;

				$account_html .= '<ul class="wc-bacs-bank-details order_details bacs_details">' . PHP_EOL;
				// BACS account fields shown on the thanks page and in emails.
				$account_fields = apply_filters(
					'woocommerce_ttqr_account_fields',
					array(
						'bank_name'      => array(
							'label' => __('Bank', 'ttqr'),
							'value' => !empty($payment_gateways['ttqr_up_'.$bacs_account->bank_name])? $payment_gateways['ttqr_up_'.$bacs_account->bank_name]->bank_name: strtoupper($bacs_account->bank_name),
						),
						'account_number' => array(
							'label' => __('Account number', 'ttqr'),
							'value' => $bacs_account->account_number,
						),
						'account_name' => array(
							'label' => __('Account name', 'ttqr'),
							'value' => $bacs_account->account_name,
						),
						'bin' => array(
							'label' => __('Bin', 'ttqr'),
							'value' => $bacs_account->bin,
						),
						'amount'            => array(
							'label' => __('Amount', 'ttqr'),
							'value' => number_format($order->get_total(), 0),
						),
						'content'            => array(
							'label' => __('Content', 'ttqr'),
							'value' =>	TtqrPayment::transaction_text($this->plugin_settings['bank_transfer']['transaction_prefix'] . '' . $order_id, $this->plugin_settings),
						),
					),
					$order_id
				);
				$qrcode_url = "";
				$qrcode_page = "";
				$disabled = '';	#_print($account_fields['bank_name']['value']);
				//check đã thah toán chưa
				if (!$is_payment) {


					// if (!$is_sent_email) {
					// 	$dataQR = $this->get_qrcode_vietqr($account_fields);
					// 	if (isset($dataQR)) {
					// 		$dataQR = json_decode($dataQR);
					// 		$qrcode_url = $dataQR->data->qrDataURL;
					// 	}
					// } else {
					// 	$data = $this->get_qrcode_vietqr_img_url($account_fields);
					// 	$qrcode_url  = $data['img_url'];
					// 	$qrcode_page = $data['pay_url'];
					// }


					if (false && !$is_sent_email) {
						$dataQR = $this->get_qrcode_vietqr($account_fields);
						if (isset($dataQR)) {
							$dataQR = json_decode($dataQR);
							$qrcode_url = !empty($dataQR->data)? $dataQR->data->qrDataURL: '';
						}
					} else {
						$data = $this->get_qrcode_vietqr_img_url($account_fields);
						$qrcode_url  = $data['img_url'];
						$qrcode_page = $data['pay_url'];
					}
					if (empty($this->order_content)) {
						$order_content = __('I have already paid', 'ttqr');
					} else {
						$order_content = $this->order_content;
					}
				} else {
					$order_content = __('You paid', 'ttqr');
					$disabled .= 'disabled';
				}
				$banks_list = TtqrPayment::get_list_banks();
				$account_fields['bank_name']['value'] = isset($banks_list[$account_fields['bank_name']['value']])? $banks_list[$account_fields['bank_name']['value']]: $account_fields['bank_name']['value'];
				foreach ($account_fields as $field) {
					if (!empty($field['value'])) {
						$has_details   = true;
					}
				}
				$account_html .= '</ul>';

				//hiển thị nút tải trên điện thoại và ko phải email
				$show_download  = wp_is_mobile();;
				if ($has_details) {
					if(false) $showPayment = '<h3>'.wp_kses_post(wp_unslash($bacs_account->account_name)).'</h3>';
					//&& (( !empty($dataQR) && $is_sent_email == false) || $is_sent_email == true) 
					$showPayment = '<div>';
					if (!$is_payment && $qrcode_url) {	//$this->plugin_settings['bank_transfer']['viet_qr'] == 'yes' && 
						$showPayment .= '
					<section class="woocommerce-ttqr-ttqr-qr-scan">
						'.sprintf('<img src="%s" class="bank-logo" style="%s"/>',TtqrPayment::get_bank_icon($bacs_account->bank_name), TtqrPayment::noQRBankLogo($bacs_account->bank_name)? 'display:none':'display:inline-block').'
					<h2 class="wc-ttqr-ttqr-bank-details-heading" style="text-align: center; margin-top: 20px">' . __('Bank transfer QR code', 'ttqr')  . '</h2>

					<div style="">
						<div id="qrcode" style="text-align: center;">
							<img src="' . esc_html($qrcode_url) . '" onerror="qrcode_fallback(this)"  alt="ttqr-ttqr QR Image" width="400px" />
						</div>

					</div>	
					</section>';
					}
					else {
						$showPayment .= sprintf('<img src="%s" class="bank-logo"/>',TtqrPayment::get_bank_icon($bacs_account->bank_name));
					}
					$showPayment .= '<section class="woocommerce-ttqr-ttqr-bank-details">
						<!-- BANK DETAIL TITLE-->
						<h2 class="wc-ttqr-ttqr-bank-details-heading" style="text-align: center;">' . esc_html__('Our bank details', 'ttqr') . '</h2>';
					if (!$is_payment)
						$showPayment .= '<div><h4 style="color: #856404; max-width: 750px; margin: auto; margin-bottom: 20px; background-color: #ffeeba; padding: 15px; border-radius: 7px;">' .  sprintf(__("Please transfer the correct content <b style='font-size: 20px;'>%s</b> for we can confirm the payment", 'ttqr'), esc_html($account_fields['content']['value'])) . '</h4></div>

								';
					else {
						$showPayment .= '<img src="' . plugins_url('../../assets/success-icon.png', __FILE__) . '"  style = "width: 100px; margin: 20px" id =""/>';
					}
					$showPayment .= '
						<!-- BANK DETAIL INFO TABLE-->
						<table class="table table-bordered" style="font-size: 15px;max-width: 800px;margin-left: auto;margin-right: auto;">
						<tbody>
						<tr class="" >
								<td class="text-right"  style="text-align: right;">
									<strong style="color: black;">' . __('Account name', 'ttqr') . ':</strong>
									<br>
								</td>
								<td class="text-left payment-instruction" style="text-align: left;">
									<div>
										<span style="color: black;">' . esc_html($account_fields['account_name']['value']) . '</span>
										<br>
									</div>
								</td>
							</tr>
							<tr class="" style="background-color:#FBFBFB;">
								<td class="text-right"  style="text-align: right;">
									<strong style="color: black;">' . __('Account number', 'ttqr') . ':</strong>
								</td>
								<td class="text-left payment-instruction" style="text-align: left;">
									<span style="color: black;">' . esc_html($account_fields['account_number']['value']) . '</span>
								</td>
							</tr>
							<tr class="" style="">
								<td class="text-right" style="text-align: right;">
									<strong style="color: black;">' . __('Bank', 'ttqr') . ':</strong>
									<br>
								</td>
								<td class="text-left payment-instruction" style="text-align: left;">
									<div>
										<span style="color: black;">' . esc_html($account_fields['bank_name']['value']) . '</span>
										<br>
									</div>
								</td>
							</tr>
							<tr class="" style="">
								<td class="text-right"  style="text-align: right;">
									<strong style="color: black;">' . __('Amount', 'ttqr') . ':</strong>
									<br>
								</td>
								<td class="text-left payment-instruction" style="text-align: left;">
									<div ng-switch-when="vcb" class="ng-scope">
										<span style="color: black;">' . esc_html($account_fields['amount']['value']) . ' <sup>vnđ</sup></span>
										<br>
									</div>
								</td>
							</tr>
							<tr class="" >
								<td class="text-right" style="text-align: right;">
									<strong style="color: black;">' . __('Content', 'ttqr') . '*:</strong>
								</td>
								<td class="text-left payment-instruction" style="text-align: left;">
									<strong style="font-size: 20px;">
									' . esc_html($account_fields['content']['value']) . '
									</strong>
								</td>
							</tr>
						</tbody>
						</table>
						<center style="margin-top: 20px;font-size: 15px;">
						<form method="post" id = "form-submit-pay">
						<input name="bank_id" type="hidden" value="' . $account_fields['account_number']['value'] . '">
						<input name="order_id" type="hidden" value="' . $order_id . '">

						<!-- <img src="' . plugins_url('../../assets/clock.gif', __FILE__) . '"  style = "width: 100px; display:none;margin: auto;" id ="image_loading_'.$i.'"/> -->
						<div><span class="qhtp-timer" style="display:none" id="timer_'.$i.'">03:00</span></div>
						<button  name="submit_paid" id="input_ttqr" class="submit_paid_'.$i.'" style="margin-bottom: 20px;" '.($disabled? '': 'onclick="fetchStatus('.$i.')"').' type="button"
						class="button '.$disabled.'"  >' . $order_content  . '</button>
						</form>
						</center>
						<h5 style="color: red; display: none;" id="noTransaction_'.$i.'">' .  __("No matching transfers were found. The system is still checking the transaction", 'ttqr') . '</h5>
					</section>
					
					<style>					  	
						#downloadQR{
							z-index:333;
							position: fixed;
							left: 0;
							right: 0;
							bottom: 0;
							display:' . ($show_download ? 'block' : 'none') . '
						}						
					</style>';

					if(strpos($output,'function fetchStatus')===false)//'.time().'
					$showPayment.= '<script>
					function startTimer(duration, display, cb) {
					    var timer = duration, minutes, seconds,_t;
					    _t = setInterval(function () {
					        minutes = parseInt(timer / 60, 10);
					        seconds = parseInt(timer % 60, 10);

					        minutes = minutes < 10 ? "0" + minutes : minutes;
					        seconds = seconds < 10 ? "0" + seconds : seconds;

					        display.textContent = minutes + ":" + seconds;

					        if (--timer < 0) {
					        	clearInterval(_t);if(cb)cb();
					            timer = duration;
					        }
					    }, 1000);
					    display.style.display="inline-block";
					}
						function fetchStatus(i)
						{
							if("wc-' . $order_status . '" == "' . $this->plugin_settings['order_status']['order_status_after_paid'] . '"){
								return;
							}
							startTimer(60*3, document.querySelector("#timer_"+i), function(){
								document.getElementById("noTransaction_"+i).style.display = "block";
									//document.getElementById("image_loading_"+i).style.display = "none";
								document.querySelector("#timer_"+i).style.display = "none";
									document.querySelector(".submit_paid_"+i).style.display="inline-block";
									clearInterval(timer);
							});
							//document.getElementById("image_loading_"+i).style.display = "block";
							document.querySelector(".submit_paid_"+i).style.display="none";
							document.getElementById("noTransaction_"+i).style.display = "none";
							let timeTemp = 0, timer;
							
							timer = setInterval(function(){
								jQuery.ajax({
									url : "' . site_url() . '/wp-admin/admin-ajax.php?__tm="+(+new Date),
									type : "post",    
									data: {action: "fetch_order_status_ttqr", order_id: '.$order_id.'},  
									error : function(response){
									},
									success : function( response ){
										if(response == "' . $this->plugin_settings['order_status']['order_status_after_paid'] . '"){
											window.location.reload(false);
										}
									}
								});
								if(timeTemp == 60000){ 
									;
								}
								if(timeTemp == 120000){ 
									return;
								}
								timeTemp = timeTemp + 3000;
							}, 3000);
						}
						function qrcode_fallback(e) {
							jQuery(e).closest("#qrcode").hide();
							jQuery(e).closest(".woocommerce-ttqr-ttqr-qr-scan").find(".wc-ttqr-ttqr-bank-details-heading").hide();
							jQuery("img.bank-logo").css("display","inline-block");
						}
						jQuery(document).ready(function(){
							if('.($auto_check? 'true':'false').') fetchStatus('.$i.');
						});
					</script>';
					
					//echo $showPayment;
					if ($is_sent_email && strpos($output, '.table-bordered')===false) {
						$showPayment.= '
					<style>
						.table-bordered {
							border: 1px solid rgba(0,0,0,.1);
						}
					</style>
					';
					}
					$output.= $showPayment;//.'</div>';
				}
			}
		}
			$output .= '</div>';
			//echo wp_kses_post($output);
			echo $output;
		}

	}
	
	/**
	 * Process the payment and return the result.
	 *
	 * @param int $order_id Order ID.
	 * @return array
	 */
	public function process_payment($order_id)
	{

		$order = wc_get_order($order_id);

		if ($order->get_total() > 0) {
			// Mark as on-hold (we're awaiting the payment).
			$order->update_status(apply_filters('woocommerce_bacs_process_payment_order_status', 'on-hold', $order), __('Awaiting BACS payment', 'woocommerce'));
		} else {
			$order->payment_complete();
		}
		// Remove cart.
		WC()->cart->empty_cart();

		// Return thankyou redirect.
		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url($order),
		);
	}
	/**
	 * Get country locale if localized.
	 *
	 * @return array
	 */
	public function get_country_locale()
	{

		if (empty($this->locale)) {

			// Locale information to be used - only those that are not 'Sort Code'.
			$this->locale = apply_filters(
				'woocommerce_get_bacs_locale',
				array(
					'AU' => array(
						'sortcode' => array(
							'label' => __('BSB', 'woocommerce'),
						),
					),
					'CA' => array(
						'sortcode' => array(
							'label' => __('Bank transit number', 'woocommerce'),
						),
					),
					'IN' => array(
						'sortcode' => array(
							'label' => __('IFSC', 'woocommerce'),
						),
					),
					'IT' => array(
						'sortcode' => array(
							'label' => __('Branch sort', 'woocommerce'),
						),
					),
					'NZ' => array(
						'sortcode' => array(
							'label' => __('Bank code', 'woocommerce'),
						),
					),
					'SE' => array(
						'sortcode' => array(
							'label' => __('Bank code', 'woocommerce'),
						),
					),
					'US' => array(
						'sortcode' => array(
							'label' => __('Routing number', 'woocommerce'),
						),
					),
					'ZA' => array(
						'sortcode' => array(
							'label' => __('Branch code', 'woocommerce'),
						),
					),
				)
			);
		}

		return $this->locale;
	}
	public function get_qrcode_vietqr_img_url($account_fields)
	{
		$bank = $account_fields['bin']['value'];
		$accountNo = $account_fields['account_number']['value'];
		$accountName = $account_fields['account_name']['value'];
		$acqId = $account_fields['bin']['value'];
		$addInfo = $account_fields['content']['value'];
		$amount = (int)preg_replace("/([^0-9\\.])/i", "", $account_fields['amount']['value']);
			
		if(is_numeric($acqId)) {
			$format = "vietqr_net_2";
			$img_url = "https://api.vietqr.io/{$acqId}/{$accountNo}/{$amount}/{$addInfo}/{$format}.jpg";
			$pay_url = "https://api.vietqr.io/{$acqId}/{$accountNo}/{$amount}/{$addInfo}";
		}
		else {
			$img_url = "";
			$pay_url = "";
			if($bank=='momo') {
				$img_url = get_rest_url(null, "ttqr/v1/qrcode?app=momo&phone={$accountNo}&price={$amount}");
			}
			else if($bank=='viettelpay') {
				$img_url = get_rest_url(null, "ttqr/v1/qrcode?app=viettelpay&phone={$accountNo}&price={$amount}&content=".urlencode($addInfo));
			}
		}
		return array(
			"img_url" => $img_url,
			"pay_url" => $pay_url,
		);
	}
	public function get_qrcode_vietqr($account_fields)
	{
		global $wp;
		$url = 'https://api.vietqr.io/v1/generate';
		$body = array(
			"accountNo" => $account_fields['account_number']['value'],
			"accountName" => $account_fields['account_name']['value'] ?: 'QUACH QUANG HUY',
			"acqId" => $account_fields['bin']['value'],
			"addInfo" => $account_fields['content']['value'],
			"amount" => (int)preg_replace("/([^0-9\\.])/i", "", $account_fields['amount']['value']),
			"format" => "vietqr_net_2"
		);
		$args = array(
			'body'        => json_encode($body),
			'headers' => array(
				'x-api-key' => 'we-l0v3-v1et-qr',
				"x-client-id" => get_site_url(),
				"content-type" => "application/json",
				"referer" => home_url(add_query_arg(array(!empty($_GET)? $_GET:array()), $wp->request))
			)
		);
		$response = wp_remote_post($url, $args);
		#$body     = wp_remote_retrieve_body($response);
		if (is_wp_error($response)) {
			return null;
		}
		if ($response['response']['code'] == 200 || $response['response']['code'] == 201) {
			$body     = wp_remote_retrieve_body($response);
			return $body;
		}
		return null;
	}
	public function get_description()
	{
		$des = apply_filters('woocommerce_gateway_description', $this->description, $this->id);
		#if ($this->bank_id != "momo")
		#	$des .= __(" <div class='power_by'>Power by PaidChecker</div>", 'ttqr');
		return $des;
	}
	public static function payment_name($name) {
		return str_replace('ttqr_up_', '', $name);
	}
}
