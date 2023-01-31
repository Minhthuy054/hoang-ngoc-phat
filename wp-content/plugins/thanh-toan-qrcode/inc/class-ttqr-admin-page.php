<?php

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Create the admin page under wp-admin -> WooCommerce -> Ttqr
 *
 * @author   ttqr Team
 * @since    
 *
 */
class Ttqr_Admin_Page
{

	/**
	 * @var string The message to display after saving settings
	 */
	var $message = '';
	/**
	 *  constructor.
	 */
	public function __construct()
	{

		$this->get_list_banks =  TtqrPayment::get_list_banks();
		$this->get_list_bin =  TtqrPayment::get_list_bin();
		$this->get_status =  TtqrPayment::connect_status_banks();
		$this->settings = TtqrPayment::get_settings();
		$this->oauth_settings = TtqrPayment::oauth_get_settings();
		if (isset($_REQUEST['oauth2_status'])) {
			$this->disconnectOAuth2();
		}
		if (isset($_REQUEST['ttqr_nonce']) && isset($_REQUEST['action']) && 'ttqr_save_settings' == $_REQUEST['action']) {
			$this->save_settings();
		}
		add_action('admin_menu', array($this, 'register_submenu_page'));
	}
	public function reset_oauth() {
		delete_option('ttqr_oauth');
		$this->oauth_settings = array();//TtqrPayment::oauth_get_settings();
	}

	/**
	 * Save settings for the plugin
	 */
	public function save_settings()
	{

		if (wp_verify_nonce($_REQUEST['ttqr_nonce'], 'ttqr_save_settings')) {
			$settings = isset($_POST['settings'])? ttqr_filter_options($_POST['settings']): [];
			if(isset($this->settings['bank_transfer_accounts'])) $settings['bank_transfer_accounts'] = $this->settings['bank_transfer_accounts'];

			if (strlen($this->settings['bank_transfer']['secure_token']) <= 0) {
				$settings['bank_transfer']['secure_token'] = ttqr_generate_random_string(16);
			} else {
				$settings['bank_transfer']['secure_token'] = $this->settings['bank_transfer']['secure_token'];
			}

			#$temp = $_REQUEST['settings']['bank_transfer']['authorization_code_force_delete'];
			#unset($_REQUEST['settings']['bank_transfer']['authorization_code_force_delete']);
			// Xoá kí tự đặc biệt và xóa bớt nếu dài quá, xóa khoảng trắng
			if(!empty($settings['bank_transfer']['transaction_prefix'])) {
				$settings['bank_transfer']['transaction_prefix'] = ttqr_clean_prefix($settings['bank_transfer']['transaction_prefix']);	//$prefix =
			}
			$settings['bank_transfer']['extra_text'] = remove_accents($settings['bank_transfer']['extra_text']);
			//check if prefix changed
			/*if(!empty($this->settings['bank_transfer']['transaction_prefix']) && $prefix!= $this->settings['bank_transfer']['transaction_prefix']) {
				$this->reset_oauth();	//reset
			}*/
			if(!empty($settings) && is_array($settings)) {
				//ttqr_valid_options($settings);
				TtqrPayment::update_settings( $settings);
			}
			$this->message = '<div class="success notice"><p><strong>'.__('Success').'</p></strong></div>';
			/* xử lí webhook!
			$this->message = $this->oauth_process_webhook($_POST['settings']);
			// Message for use
			$this->message .=
				'<div class="updated notice"><p><strong>' .
				__('Settings saved', 'ttqr') .
				'</p></strong></div>';*/
		} else {

			$this->message =
				'<div class="error notice"><p><strong>' .
				__('Can not save settings! Please refresh this page.', 'ttqr') .
				'</p></strong></div>';
		}
	}
	

	/**
	 * Register the sub-menu under "WooCommerce"
	 * Link: http://my-site.com/wp-admin/admin.php?page=ttqr
	 */
	public function register_submenu_page()
	{
		add_submenu_page(
			'woocommerce',
			__('Ttqr Settings', 'ttqr'),
			'Thanh toán Quét Mã QR',
			'manage_options',
			'ttqr',
			array($this, 'admin_page_html')
		);
	}

	/**
	 * Generate the HTML code of the settings page
	 */
	public function admin_page_html()
	{
		
		// check user capabilities
		if (!current_user_can('manage_options')) {
			return;
		}
		/*if(!empty($_REQUEST['pc-reset'])) {
			$this->reset_oauth();			
		}*/
		$settings = TtqrPayment::get_settings();
		
?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<form name="ttqr-setting-form" method="post">
				<p><?php echo wp_kses_post($this->message); ?></p>
				<input type="hidden" id="action" name="action" value="ttqr_save_settings">
				<input type="hidden" id="ttqr_nonce" name="ttqr_nonce" value="<?php echo wp_create_nonce('ttqr_save_settings') ?>">
				<input name="settings[bank_transfer][enabled]" type="hidden" value="yes">
				<input name="settings[bank_transfer][viet_qr]" type="hidden" value="yes">
				<input name="settings[bank_transfer][case_insensitive]" type="hidden" value="no">
				<p><?php echo __('Set up a link', 'ttqr'); ?></p>
				<table class="form-table">
					<tbody>
						<!--
						<tr>
							<th scope="row"><?php echo __('Enable/Disable', 'ttqr'); ?></th>
							<td>
								<input name="settings[bank_transfer][enabled]" type="hidden" value="yes">
							<input name="settings[bank_transfer][enabled]" type="checkbox" id="bank_transfer" value="yes" <?php if('yes' == $settings['bank_transfer']['enabled']) echo 'checked="checked"' ?>>
							<label for="bank_transfer" style="font-size: 13px; font-style: oblique;"><?php echo __('Turn on bank transfer', 'ttqr'); ?></label>
								<br />
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo __('VietQR', 'ttqr'); ?></th>
							<td>
								<input name="settings[bank_transfer][viet_qr]" type="hidden" value="yes">
								<input name="settings[bank_transfer][viet_qr]" type="checkbox" id="bank_transfer" value="yes" <?php if (!empty($settings['bank_transfer']['viet_qr']) && 'yes' == $settings['bank_transfer']['viet_qr'])echo 'checked="checked"' ?>>
								<label for="bank_transfer" style="font-size: 13px; font-style: oblique;"><?php echo __('Enable QR code display mode VietQR', 'ttqr'); ?></label>
								<br />
							</td>
						</tr>
					-->
						<tr style="<?php #if ($this->oauth_settings['login_type'] == 1) echo "display: none;" ?>">
							<th scope="row"><?php echo __('Download mobile app to connect your website', 'ttqr');//Link Ttqr App ?></th>

							<td id="connectqr">
								<?php #if(1||empty($this->oauth_settings['account_type'])){?>
								
								<div class="display:table">
								<span style="display: table-cell;padding-right: 10px;padding-bottom: 10px"><a href="https://play.google.com/store/apps/details?id=com.hoangweb.checkpay" target="_blank" class="ttqr-hidden" id="ttqrapp"><img src="https://lh3.googleusercontent.com/cjsqrWQKJQp9RFO7-hJ9AfpKzbUb_Y84vXfjlP0iRHBvladwAfXih984olktDhPnFqyZ0nu9A5jvFwOEQPXzv7hr3ce3QVsLN8kQ2Ao=s0"/></a></span><span style="display: table-cell; vertical-align: middle;display: none"><?php #echo __('Download mobile app to connect your website','ttqr')?></span></div>
								<?php #} ?>
								<div id="ttqrqrcode" style="margin-bottom: 10px;"></div>

							</td>
						</tr>

						<tr style="display: none">
							<th scope="row"><?php echo __('Banks List', 'ttqr') ?></th>
							<td>
								
								<div id='banks_list_user' style="">
									<?php #if (empty($settings['bank_transfer_accounts'])) __('No banks found', 'ttqr'); 
									/*
									$banks_accepted = isset($settings['bank_transfer_accounts'])? $settings['bank_transfer_accounts']:[];
									$banks_added = get_option('woocommerce_bacs_accounts', array());
									
									?>
									<select multiple="" name="settings[bank_transfer_accounts][]" id="bank_transfer_accounts">
										<?php foreach($banks_added as $bank) {
											$id = $bank['bank_name'].'-'.$bank['account_number'];
											printf('<option %s value="%s">%s</option>', 
												in_array($id, $banks_accepted)? 'selected':'',
												$id,
												$bank['bank_name'].' - '.$bank['account_number'] );
										}?>
									</select>
									<?php */?>
								</div>
							</td>
							<td></td>
						</tr>

						
						<tr>
							<th scope="row"><?php echo __('Transaction prefix', 'ttqr') ?></th>
							<td>
								<input name="settings[bank_transfer][transaction_prefix]" type="text" value="<?php echo esc_attr($settings['bank_transfer']['transaction_prefix']); ?>" id="prefix" maxlength="10">
								<label for="bank_transfer" style="font-size: 13px; font-style: oblique;">
									<ul>
										<li><?php echo __('This prefix goes with the order code.','ttqr')?></li>
										<li><?php echo __('Maximum 10 characters, no spaces and no special characters and no number.', 'ttqr') ?></li>										
									</ul>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo  __('Extra Transaction Prefix (optional)', 'ttqr') ?></th>

							<td>
								<input type="text" name="settings[bank_transfer][extra_text]" id="transaction_extra_text" value="<?php echo !empty($settings['bank_transfer']['extra_text'])? esc_html($settings['bank_transfer']['extra_text']): ''; ?>"/><!--<textarea style="width: 100%;min-height: 200px;" -->
								<p><i><?php echo __("Ex: 'chuyen khoan ABC123' ('chuyen khoan' is the extra content). Maximum 20 characters, no spaces and no special characters and no number.",'ttqr');?></i></p>
							</td>
						</tr>
						<!--<tr>
							<th scope="row"><?php echo __('Turn on Case Sensitivity', 'ttqr') ?></th>
							<td>
								<input name="settings[bank_transfer][case_insensitive]" type="hidden" value="no">
								<input name="settings[bank_transfer][case_insensitive]" type="checkbox" id="bank_transfer" value="yes" <?php if ('yes' == $settings['bank_transfer']['case_insensitive']) echo 'checked="checked"';	?>>
								<label for="bank_transfer" style="font-size: 13px; font-style: oblique;"><?php echo __('Turn on Case Sensitivity', 'ttqr') ?></label>
								<br />
							</td>
						</tr>-->

						<tr>
							<th scope="row"><?php echo __('Acceptance difference', 'ttqr') ?></th>
							<td>
								<input name="settings[bank_transfer][acceptable_difference]" type="number" value="<?php echo  esc_attr($settings['bank_transfer']['acceptable_difference']); ?>">
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo __('Status after full payment or balance', 'ttqr') ?></th>

							<td>
								<select name="settings[order_status][order_status_after_paid]" id="order_status_after_paid">
									<?php
									foreach ($this->get_order_statuses_after_paid() as $key => $value) {
										if ($key == $settings['order_status']['order_status_after_paid'])
											echo '<option value="' . esc_attr($key) . '" selected>' . esc_html($value) . '</option>';
										else echo '<option value="' . esc_attr($key) . '" >' . esc_html($value) . '</option>';
									}
									?>
								</select>
							</td>
						<tr>
						<tr>
							<th scope="row"><?php echo  __('Status if payment is missing', 'ttqr') ?></th>

							<td>
								<select name="settings[order_status][order_status_after_underpaid]" id="order_status_after_underpaid">
									<?php
									foreach ($this->get_order_statuses_after_underpaid() as $key => $value) {
										if ($key == $settings['order_status']['order_status_after_underpaid'])
											echo '<option value="' . esc_attr($key) . '" selected>' . esc_html($value) . '</option>';
										else echo '<option value="' . esc_attr($key) . '">' . esc_html($value) . '</option>';
									}
									?>
								</select>
							</td>
						<tr>
						<tr>
							<th scope="row"><?php echo __('Auto check user paid', 'ttqr') ?></th>
							<td>
								<input type="checkbox" name="settings[auto_check_status]" id="order_auto_check_status" <?php echo isset($settings['auto_check_status']) && (int)$settings['auto_check_status']? 'checked':'' ?>/>
							</td>
						</tr>
					</tbody>
				</table>

				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save Changes','ttqr')?>">
				</p>

			</form>
			<div id="ttqr-admin-footer" style="border: 1px dotted; padding: 5px;display: none">
				<?php
				/*printf(
					__('Wanna get support or give feedback? Please <a href="%1$s">rate ttqr</a> or post questions <a href="%2$s">in the forum</a>!', 'ttqr'),
					'https://wordpress.org/support/plugin/ttqr-tu-dong-xac-nhan-thanh-toan-chuyen-khoan-ngan-hang/reviews/',
					'https://wordpress.org/plugins/ttqr-tu-dong-xac-nhan-thanh-toan-chuyen-khoan-ngan-hang/'
				)*/
				?>
			</div>
		</div>
		<script type="text/javascript">
			
			function generateQrCode() {
				<?php 
				if(!TTQR_TEST && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off')) {
					?>
					$('#connectqr').html("<p class='ttqr-error-tip'><?php echo __("Can't generate QR code because website doesn't have ssl",'ttqr')?></p>");
					<?php
				}
				else if(!empty($settings['bank_transfer']['secure_token']) && !empty($settings['bank_transfer']['transaction_prefix'])) {//'url_auth'=>admin_url('admin-ajax.php').'?action=auth_app_ttqr'?>
				var code = "<?php echo base64_encode(json_encode(['pf'=>$settings['bank_transfer']['transaction_prefix'],'tk'=>$settings['bank_transfer']['secure_token'], 'url'=>admin_url('admin-ajax.php').'?action=paid_order_ttqr',]))?>";
				var options = {
					text: code,
					width: 256,
				    height: 256,
				    colorDark : "#000000",
					colorLight : "#ffffff",
					logo: "<?php echo plugins_url('../assets/logo.png',__FILE__)?>",
				};
				new QRCode(jQuery('#ttqrqrcode')[0], options);
				$('#ttqrapp').removeClass('ttqr-hidden');
				<?php }else {?>
					$('#connectqr').html("<p class='ttqr-error-tip'><?php echo __('Please save settings, then qrcode image will appear','ttqr')?></p>");

				<?php }?>
			}

			jQuery(document).ready(function(_$){
				if(typeof $=='undefined') $=_$;
				generateQrCode();
				
			});
			//if(location.href.indexOf('&pc-reset=')!=-1) 
			//	window.history.pushState(null, null, location.href.split('&pc-reset=')[0]);


		</script>
		<!-- #wrap ->
        <?php
	}
	
	public function get_order_statuses_after_paid()
	{
		$wooDefaultStatuses = array(
			"wc-pending",
			"wc-processing",
			"wc-on-hold",
			// "wc-completed",
			"wc-cancelled",
			"wc-refunded",
			"wc-failed",
			// "wc-paid",
			"wc-underpaid"
		);
		$statuses =  wc_get_order_statuses();
		$statuses['wc-default'] = __('Default', 'ttqr');
		for ($i = 0; $i < count($wooDefaultStatuses); $i++) {
			$statusName = $wooDefaultStatuses[$i];
			if (isset($statuses[$statusName])) {
				unset($statuses[$statusName]);
			}
		}
		return $statuses;
	}
	
	public function get_order_statuses_after_underpaid()
	{
		$wooDefaultStatuses = array(
			"wc-pending",
			// "wc-processing",
			"wc-on-hold",
			"wc-completed",
			"wc-cancelled",
			"wc-refunded",
			"wc-failed",
			"wc-paid",
			// "wc-underpaid"
		);
		$statuses =  wc_get_order_statuses();
		$statuses['wc-default'] =  __('Default', 'ttqr');
		for ($i = 0; $i < count($wooDefaultStatuses); $i++) {
			$statusName = $wooDefaultStatuses[$i];
			if (isset($statuses[$statusName])) {
				unset($statuses[$statusName]);
			}
		}
		return $statuses;
	}

	
}
