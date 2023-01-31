<?php
if ( ! function_exists('wp_dp_is_package_order') ) {

    /**
     * checking package order
     * @return boolean
     */
    function wp_dp_is_package_order($id = '') {
        $package_order = get_post($id);
        if ( isset($package_order->post_type) && $package_order->post_type == 'package-orders' ) {
            return true;
        }
        return false;
    }

}

if ( ! function_exists('wp_dp_payment_summary_fields') ) {

    /**
     * Payment Summary fields
     * @return html
     */
    function wp_dp_payment_summary_fields() {

        global $current_user, $wp_dp_form_fields_frontend;

        $first_name = '';
        $last_name = '';
        $email = '';
        $phone = '';
        $address = '';
        if ( is_user_logged_in() ) {
            $company_id = wp_dp_company_id_form_user_id($current_user->ID);
            $first_name = $current_user->user_firstname;
            $last_name = $current_user->user_lastname;
            $email = $current_user->user_email;
            $phone = get_post_meta($company_id, 'wp_dp_phone_number', true);
            $address = get_post_meta($company_id, 'wp_dp_post_loc_address_member', true);
        }
        $html = '
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="payment-summary-fields">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="field-holder">
							<label>' . wp_dp_plugin_text_srt('wp_dp_payment_first_name') . '</label>' .
                $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                        array(
                            'cust_name' => 'trans_first_name',
                            'classes' => 'wp-dp-dev-req-field',
                            'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_payment_first_name') . '"',
                            'std' => esc_html($first_name),
                            'return' => true,
                        )
                )
                . '
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="field-holder">
							<label>' . wp_dp_plugin_text_srt('wp_dp_payment_last_name') . '</label>' .
                $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                        array(
                            'cust_name' => 'trans_last_name',
                            'classes' => 'wp-dp-dev-req-field',
                            'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_payment_last_name') . '"',
                            'std' => esc_html($last_name),
                            'return' => true,
                        )
                )
                . '
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="field-holder">
							<label>' . wp_dp_plugin_text_srt('wp_dp_payment_email') . '</label>' .
                $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                        array(
                            'cust_name' => 'trans_email',
                            'classes' => 'wp-dp-dev-req-field wp-dp-email-field',
                            'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_payment_email') . '"',
                            'std' => esc_html($email),
                            'return' => true,
                        )
                )
                . ' 
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="field-holder">
							<label>' . wp_dp_plugin_text_srt('wp_dp_payment_phone_number') . '</label>' .
                $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                        array(
                            'cust_name' => 'trans_phone_number',
                            'classes' => 'wp-dp-dev-req-field wp-dp-number-field',
                            'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_payment_phone_number') . '"',
                            'std' => $phone,
                            'return' => true,
                        )
                )
                . ' 
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="field-holder">
							<label>' . wp_dp_plugin_text_srt('wp_dp_payment_address') . '</label>
							<textarea class="wp-dp-dev-req-field" name="trans_address">' . $address . '</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>';

        return apply_filters('wp_dp_payment_summary_fields', $html);
    }

}

if ( ! function_exists('wp_dp_payment_gateways') ) {

    /**
     * Load Payment Gateways
     * @return markup
     */
    function wp_dp_payment_gateways($trans_fields = array()) {
        global $wp_dp_plugin_options, $gateways, $wp_dp_form_fields;
        wp_enqueue_script('wp-dp-validation-script');

        $html = '';
        $payments_settings = new WP_DP_PAYMENTS();

        if ( isset($trans_fields['creating']) && $trans_fields['creating'] === true ) {
            wp_enqueue_script('wp-dp-listing-user-add');
        } else {
            wp_enqueue_script('wp-dp-listing-add');
        }

        // Payment Process
        // when this form submit
        $buy_order_action = wp_dp_get_input('wp_dp_buy_order_flag', 0);
        $get_action = wp_dp_get_input('action', '');

        $get_trans_id = wp_dp_get_input('trans_id', 0);

        if ( $buy_order_action == '1' ) {

            if ( $get_action == 'listing-package' && wp_dp_is_package_order($get_trans_id) ) {

                $trans_user_id = get_post_meta($get_trans_id, 'wp_dp_transaction_user', true);
                $wp_dp_trans_pkg = get_post_meta($get_trans_id, 'wp_dp_transaction_package', true);
                $wp_dp_trans_amount = get_post_meta($get_trans_id, 'wp_dp_transaction_amount', true);

                $wp_dp_trans_pay_method = wp_dp_get_input('wp_dp_listing_gateway', '', 'STRING');

                $wp_dp_trans_array = array(
                    'transaction_id' => $get_trans_id, // order id
                    'transaction_user' => $trans_user_id,
                    'transaction_package' => $wp_dp_trans_pkg,
                    'transaction_amount' => $wp_dp_trans_amount,
                    'transaction_order_type' => 'package-order',
                    'transaction_pay_method' => $wp_dp_trans_pay_method,
                    'transaction_return_url' => isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? wp_dp_wpml_lang_page_permalink($wp_dp_plugin_options['wp_dp_member_dashboard'], 'page') . '?response=order-completed' : site_url(),
                );
                $transaction_detail = wp_dp_payment_process($wp_dp_trans_array);

                if ( $transaction_detail ) {
                    echo force_balance_tags($transaction_detail);
                }
            }

            // Order transaction
            if ( $get_action == 'reservation-order' ) {
                $wp_dp_order_service = '';
                $trans_order_user_company_id = get_post_meta($get_trans_id, 'wp_dp_order_user_company', true);
                $wp_dp_service_title = get_post_meta($get_trans_id, 'service_title', true);

                if ( is_array($wp_dp_service_title) && ! empty($wp_dp_service_title) ) {
                    $wp_dp_order_service = implode(' ', $wp_dp_service_title);
                }
                $wp_dp_trans_amount = get_post_meta($get_trans_id, 'services_total_price', true);
                $wp_dp_trans_pay_method = wp_dp_get_input('wp_dp_listing_gateway', '', 'STRING');

                $wp_dp_trans_array = array(
                    'transaction_id' => $get_trans_id, // order id
                    'transaction_user' => $trans_order_user_company_id,
                    'transaction_package' => $wp_dp_order_service,
                    'transaction_amount' => $wp_dp_trans_amount,
                    'transaction_order_type' => 'reservation-order',
                    'transaction_pay_method' => $wp_dp_trans_pay_method,
                );

                $transaction_detail = wp_dp_payment_process($wp_dp_trans_array);

                if ( $transaction_detail ) {
                    echo force_balance_tags($transaction_detail);
                }
            }
        } else {
            // Payment Gateways
            $payment_gw_list = '';
            $wp_dp_gateway_options = $wp_dp_plugin_options;
            $gw_counter = 1;
            $rand_id = rand(10000000, 99999999);
            if ( is_array($gateways) && sizeof($gateways) > 0 ) {
                $payment_gw_list .= '
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="cs-rich-editor">
				<div class="payment-gateways-main">';
                if ( isset($trans_fields['back_button']) && $trans_fields['back_button'] == true ) {
                    
                } else {
                    $payment_gw_list .= '
					<form class="wp-dp-dev-payment-form" data-id="' . $rand_id . '" method="post">';
                }
                $payment_gw_list .= '
				<div class="row">' . wp_dp_payment_summary_fields() . '';
                if ( ! isset($wp_dp_gateway_options['wp_dp_use_woocommerce_gateway']) || $wp_dp_gateway_options['wp_dp_use_woocommerce_gateway'] != 'on' ) {
                    $payment_gw_list .= '
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
						<div class="dashboard-element-title">
							<strong>
								' . wp_dp_plugin_text_srt('wp_dp_payment_methods') . '
								<span class="info-text">(' . wp_dp_plugin_text_srt('wp_dp_payment_options_below') . ')</span>
							</strong>
						</div>
					</div>';
                }
                $payment_gw_list .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="field-holder">
				<div class="payment-holder">
				<ul class="payment-list row">';

                if ( isset($wp_dp_gateway_options['wp_dp_use_woocommerce_gateway']) && $wp_dp_gateway_options['wp_dp_use_woocommerce_gateway'] == 'on' ) {


                    $payment_gw_list .= '
					<li class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hidden">
						<div class="payment-box">
							<input type="radio" id="WP_DP_WOOCOMMERCE_GATEWAY" checked="checked" name="wp_dp_listing_gateway" value="WP_DP_WOOCOMMERCE_GATEWAY">
							<label for="WP_DP_WOOCOMMERCE_GATEWAY"></label>
							<span>' . sprintf(wp_dp_plugin_text_srt('wp_dp_payment_pay_with'), "Woocommerce") . '</span>
						</div>
					</li>';
                    $gw_counter ++;
                } else {

                    foreach ( $gateways as $key => $value ) {
                        $status = $wp_dp_gateway_options[strtolower($key) . '_status'];
                        if ( isset($status) && $status == 'on' ) {
                            $rand_counter = rand(1000000, 9999999);
                            $logo = '';
                            if ( isset($wp_dp_gateway_options[strtolower($key) . '_logo']) ) {
                                $logo = $wp_dp_gateway_options[strtolower($key) . '_logo'];
                            }
                            if ( $logo == '' ) {
                                if ( $key == 'WP_DP_PAYPAL_GATEWAY' ) {
                                    $logo = wp_dp::plugin_url() . 'payments/images/paypal.png';
                                } else if ( $key == 'WP_DP_AUTHORIZEDOTNET_GATEWAY' ) {
                                    $logo = wp_dp::plugin_url() . 'payments/images/athorizedotnet_.png';
                                } else if ( $key == 'WP_DP_PRE_BANK_TRANSFER' ) {
                                    $logo = wp_dp::plugin_url() . 'payments/images/bank.png';
                                } else if ( $key == 'WP_DP_SKRILL_GATEWAY' ) {
                                    $logo = wp_dp::plugin_url() . 'payments/images/skrill.png';
                                }
                            }
                            if ( isset($logo) && $logo != '' ) {
                                if ( $logo > 0 ) {
                                    $logo = wp_get_attachment_url($logo);
                                }
                                $gateway_name = isset($gateways[$key]) ? $gateways[$key] : '';
                                $payment_gw_list .= '
								<li class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
									<div class="payment-box">
										<input type="radio" id="' . strtolower($key) . '_' . $rand_counter . '"' . ($gw_counter == 1 ? ' checked="checked"' : '') . ' name="wp_dp_listing_gateway" value="' . $key . '">
										<label for="' . strtolower($key) . '_' . $rand_counter . '"><img alt="" src="' . esc_url($logo) . '"></label>
										<span>' . $gateway_name . '</span>
									</div>
								</li>';
                            }
                            $gw_counter ++;
                        }
                    }
                }
                $payment_gw_list .= '
				</ul>
				<div class="payment-submit-btn input-button-loader">';
                if ( isset($trans_fields['back_button']) && $trans_fields['back_button'] == true ) {
                    
                } else {
                    $payment_gw_list .= '<input type="submit" value="' . wp_dp_plugin_text_srt('wp_dp_payment_submit_order') . '">';
                }
                $payment_gw_list .= '
				</div>
				<input type="hidden" name="wp_dp_buy_order_flag" value="1">
				<input type="hidden" name="trans_id" value="' . $get_trans_id . '">
				</div>
				</div>
				</div>
				</div>';
                if ( isset($trans_fields['back_button']) && $trans_fields['back_button'] == true ) {
                    
                } else {
                    $payment_gw_list .= '
					</form>';
                }
                $payment_gw_list .= '
				</div>
				</div>
				</div>';
            }

            if ( $payment_gw_list ) {

                if ( $get_action == 'listing-package' ) {
                    if ( is_user_logged_in() && true !== Wp_dp_Member_Permissions::check_permissions('packages') ) {
                        $html .= '
						<div class="row">
							<h3>' . wp_dp_plugin_text_srt('wp_dp_payment_package_permissions_error') . '</h3>
						</div>';
                    } else {
                        $html .= '
						<div class="row">
							' . $payment_gw_list . '
						</div>';
                    }
                }

                if ( $get_action == 'reservation-order' ) {
                    if ( is_user_logged_in() && true !== Wp_dp_Member_Permissions::check_permissions('orders') ) {
                        $html .= '
						<div class="row">
							<h3>' . wp_dp_plugin_text_srt('wp_dp_payment_order_permissions_error') . '</h3>
						</div>';
                    } else {
                        $html .= '
						<div class="row">
							' . $payment_gw_list . '
						</div>';
                    }
                }
            }

            echo force_balance_tags($html);
        }
    }

    add_action('wp_dp_payment_gateways', 'wp_dp_payment_gateways', 10, 1);
}

if ( ! function_exists('wp_dp_payment_process') ) {

    /**
     * Payment Process
     * @return id
     */
    function wp_dp_payment_process($wp_dp_transaction_fields = array()) {
        extract($wp_dp_transaction_fields);
        $transaction_detail = '';
        if ( isset($transaction_amount) && isset($transaction_pay_method) && $transaction_amount > 0 && $transaction_pay_method != '' ) {
            $wp_dp_transaction_fields['wp_dp_order_id'] = isset($transaction_id) ? $transaction_id : 0;

            // Add Transaction
            $wp_dp_trans_id = rand(10000000, 99999999);
            $transaction_post = array(
                'post_title' => '#' . $wp_dp_trans_id,
                'post_status' => 'publish',
                'post_type' => 'wp-dp-trans',
                'post_date' => current_time('Y/m/d H:i:s', 1)
            );
            //insert the transaction
            $trans_id = wp_insert_post($transaction_post);
            if ( $trans_id ) {

                update_post_meta($trans_id, 'wp_dp_currency', wp_dp_base_currency_sign());
                update_post_meta($trans_id, 'wp_dp_currency_obj', wp_dp_get_base_currency());
                update_post_meta($trans_id, 'wp_dp_currency_position', wp_dp_get_currency_position());
                $get_trans_first_name = wp_dp_get_input('trans_first_name', '');
                $get_trans_last_name = wp_dp_get_input('trans_last_name', '');
                $get_trans_email = wp_dp_get_input('trans_email', '');
                $get_trans_phone_number = wp_dp_get_input('trans_phone_number', '');
                $get_trans_address = wp_dp_get_input('trans_address', '');

                $trans_meta_arr = array(
                    'transaction_id' => $trans_id,
                    'transaction_order_id' => $transaction_id,
                    'transaction_amount' => $transaction_amount,
                    'transaction_user' => $transaction_user,
                    'transaction_order_type' => $transaction_order_type,
                    'transaction_pay_method' => $transaction_pay_method,
                    'trans_first_name' => $get_trans_first_name,
                    'trans_last_name' => $get_trans_last_name,
                    'trans_email' => $get_trans_email,
                    'trans_phone_number' => $get_trans_phone_number,
                    'trans_address' => $get_trans_address,
                );
                
                if( isset( $promotions ) ){
                    $trans_meta_arr['promotions']   = $promotions;
                }
                if( isset( $listing_id ) ){
                    $trans_meta_arr['listing_id']   = $listing_id;
                }
                
                
                // updating all fields of transaction
                foreach ( $trans_meta_arr as $trans_key => $trans_val ) {
                    update_post_meta($trans_id, "wp_dp_{$trans_key}", $trans_val);
                }

                $wp_dp_transaction_fields['transaction_id'] = $trans_id;

                // passing item id if any
                $trans_item_id = get_post_meta($transaction_id, 'order_item_id', true);
                $wp_dp_transaction_fields['trans_item_id'] = $trans_item_id;

                // Gateways Process
                if ( $transaction_pay_method == 'WP_DP_PAYPAL_GATEWAY' && ! empty($wp_dp_transaction_fields) ) {
                    $paypal_gateway = new WP_DP_PAYPAL_GATEWAY();
                    $paypal_gateway->wp_dp_proress_request($wp_dp_transaction_fields);
                } else if ( $transaction_pay_method == 'WP_DP_AUTHORIZEDOTNET_GATEWAY' && ! empty($wp_dp_transaction_fields) ) {
                    $authorizedotnet = new WP_DP_AUTHORIZEDOTNET_GATEWAY();
                    $authorizedotnet->wp_dp_proress_request($wp_dp_transaction_fields);
                } else if ( $transaction_pay_method == 'WP_DP_SKRILL_GATEWAY' && ! empty($wp_dp_transaction_fields) ) {
                    $skrill = new WP_DP_SKRILL_GATEWAY();
                    $skrill->wp_dp_proress_request($wp_dp_transaction_fields);
                } else if ( $transaction_pay_method == 'WP_DP_PRE_BANK_TRANSFER' && ! empty($wp_dp_transaction_fields) ) {
                    $banktransfer = new WP_DP_PRE_BANK_TRANSFER();
                    $transaction_detail = $banktransfer->wp_dp_proress_request($wp_dp_transaction_fields);
                } else if ( $transaction_pay_method == 'WP_DP_WOOCOMMERCE_GATEWAY' && ! empty($wp_dp_transaction_fields) ) {
                    /*
                     * If payment gateway is woocommerce
                     */
                    global $Payment_Processing;
                    update_post_meta($trans_id, 'wp_dp_order_with', 'woocommerce');
                    $payment_args = array(
                        'package_id' => $wp_dp_transaction_fields['transaction_id'],
                        'package_name' => ( get_the_title($wp_dp_transaction_fields['transaction_package']) )? get_the_title($wp_dp_transaction_fields['transaction_package']) : $wp_dp_transaction_fields['transaction_package'],
                        'price' => $wp_dp_transaction_fields['transaction_amount'],
                        'custom_var' => array(
                            'wp_dp_transaction_id' => $trans_id,
                            'listing_id' => $trans_item_id,
                            'wp_dp_listing_id' => $wp_dp_transaction_fields['transaction_id'],
                        ),
                        'redirect_url' => get_option('wooCommerce_current_page'),
                        'is_json' => 'true',
                    );
                    echo ($Payment_Processing->processing_payment($payment_args));
                }
            }
            //
        }
        return apply_filters('wp_dp_payment_process', $transaction_detail, $wp_dp_transaction_fields);
        // usage :: add_filter('wp_dp_payment_process', 'my_callback_function', 10, 2);
    }

}
