<?php

/**
 *  File Type: Skrill- Monery Booker Gateway
 *
 */
if (!class_exists('WP_DP_SKRILL_GATEWAY')) {

    class WP_DP_SKRILL_GATEWAY extends WP_DP_PAYMENTS {

        
        // Start skrill gateway construct
        
        public function __construct() {
            global $wp_dp_gateway_options;
            $wp_dp_lister_url = '';
            if (isset($wp_dp_gateway_options['wp_dp_skrill_ipn_url'])) {
                $wp_dp_lister_url = $wp_dp_gateway_options['wp_dp_skrill_ipn_url'];
            }



            $wp_dp_gateway_options = get_option('wp_dp_plugin_options');
            $wp_dp_gateway_options = apply_filters('wp_dp_translate_options', $wp_dp_gateway_options );
            $this->gateway_url = "https://www.moneybookers.com/app/payment.pl";
            $this->listner_url = $wp_dp_lister_url;
        }

        
        // Start function for skrill payment gateway setting 
        
        public function settings($wp_dp_gateways_id = '') {
            global $post;

            $wp_dp_rand_id = rand(10000000, 99999999);

            $on_off_option = array("show" => wp_dp_plugin_text_srt('wp_dp_skrill_options_on'), "hide" => wp_dp_plugin_text_srt('wp_dp_skrill_options_off'));


            $wp_dp_settings[] = array("name" => wp_dp_plugin_text_srt('wp_dp_skrill_money_booker_stng'),
                "id" => "tab-heading-options",
                "std" => wp_dp_plugin_text_srt('wp_dp_skrill_money_booker_stng'),
                "type" => "section",
                "id" => "$wp_dp_rand_id",
                "parrent_id" => "$wp_dp_gateways_id",
                "active" => false,
            );



            $wp_dp_settings[] = array("name" => wp_dp_plugin_text_srt('wp_dp_skrill_cistom_logo'),
                "desc" => "",
                "hint_text" => "",
                 "label_desc" => wp_dp_plugin_text_srt('wp_dp_skrill_cistom_logo_hint'),
                "id" => "skrill_gateway_logo",
                "std" => wp_dp::plugin_url() . 'payments/images/skrill.png',
                "display" => "none",
                "type" => "upload logo"
            );

            $wp_dp_settings[] = array("name" => wp_dp_plugin_text_srt('wp_dp_skrill_default_status'),
                "desc" => "",
                "hint_text" => '',
                "label_desc" => wp_dp_plugin_text_srt('wp_dp_skrill_default_status_hint'),
                "id" => "skrill_gateway_status",
                "std" => "on",
                "type" => "checkbox",
                "options" => $on_off_option
            );

            $wp_dp_settings[] = array("name" => wp_dp_plugin_text_srt('wp_dp_skrill_business_email'),
                "desc" => "",
                "hint_text" => '',
				"label_desc" => wp_dp_plugin_text_srt('wp_dp_skrill_business_email_hint'),
                "id" => "skrill_email",
                "std" => "",
                "type" => "text"
            );

            $ipn_url = wp_dp::plugin_url() . 'payments/listner.php';
            $wp_dp_settings[] = array("name" => wp_dp_plugin_text_srt('wp_dp_skrill_ipn_url'),
                "desc" => '',
                "hint_text" => '',
		"label_desc" => wp_dp_plugin_text_srt('wp_dp_skrill_ipn_url_hint'),
                "id" => "skrill_ipn_url",
                "std" => $ipn_url,
                "type" => "text",
				"extra_attr" => "readonly='readonly'",
            );



            return $wp_dp_settings;
        }
        
         // Start function for skrill payment gateway process request 

        public function wp_dp_proress_request($params = '') {
            global $post, $wp_dp_gateway_options, $wp_dp_form_fields;
            extract($params);

            $wp_dp_current_date = date('Y/m/d H:i:s');
            $output = '';
            $rand_id = $this->wp_dp_get_string(5);
            $business_email = $wp_dp_gateway_options['wp_dp_skrill_email'];

            $currency = isset($wp_dp_gateway_options['wp_dp_currency_type']) && $wp_dp_gateway_options['wp_dp_currency_type'] != '' ? $wp_dp_gateway_options['wp_dp_currency_type'] : 'USD';
            $user_ID = get_current_user_id();
			
            $wp_dp_package_title = get_the_title($transaction_package);
			
            $wp_dp_opt_hidden_array = array(
                'id' => '',
                'std' => sanitize_email($business_email),
                'cust_id' => "",
                'cust_name' => "pay_to_email",
                'return' => true,
            );
            $wp_dp_opt_amount_array = array(
                'id' => '',
                'std' => $transaction_amount,
                'cust_id' => "",
                'cust_name' => "amount",
                'return' => true,
            );
            $wp_dp_opt_language_array = array(
                'id' => '',
                'std' => 'EN',
                'cust_id' => "",
                'cust_name' => "language",
                'return' => true,
            );
            $wp_dp_opt_currency_array = array(
                'id' => '',
                'std' => $currency,
                'cust_id' => "",
                'cust_name' => "currency",
                'return' => true,
            );
            $wp_dp_opt_description_array = array(
                'id' => '',
                'std' => 'Package : ',
                'cust_id' => "",
                'cust_name' => "detail1_description",
                'return' => true,
            );
            $wp_dp_opt_detail1_array = array(
                'id' => '',
                'std' => $wp_dp_package_title,
                'cust_id' => "",
                'cust_name' => "detail1_text",
                'return' => true,
            );
            $wp_dp_opt_detail2_description_array = array(
                'id' => '',
                'std' => 'Ad Title : ',
                'cust_id' => "",
                'cust_name' => "detail2_description",
                'return' => true,
            );
            $wp_dp_opt_detail2_text_array = array(
                'id' => '',
                'std' => sanitize_text_field($wp_dp_package_title),
                'cust_id' => "",
                'cust_name' => "detail2_text",
                'return' => true,
            );
            $wp_dp_opt_detail3_description_array = array(
                'id' => '',
                'std' => "Ad ID : ",
                'cust_id' => "",
                'cust_name' => "detail3_description",
                'return' => true,
            );

            $wp_dp_opt_detail3_text_array = array(
                'id' => '',
                'std' => sanitize_text_field($transaction_id),
                'cust_id' => "",
                'cust_name' => "detail3_text",
                'return' => true,
            );
            $wp_dp_opt_cancel_url_array = array(
                'id' => '',
                'std' => esc_url(get_permalink()),
                'cust_id' => "",
                'cust_name' => "cancel_url",
                'return' => true,
            );

            $wp_dp_opt_status_url_array = array(
                'id' => '',
                'std' => sanitize_text_field($this->listner_url),
                'cust_id' => "",
                'cust_name' => "status_url",
                'return' => true,
            );

            $wp_dp_opt_transaction_id_array = array(
                'id' => '',
                'std' => sanitize_text_field($transaction_id) . '||' . sanitize_text_field($trans_item_id),
                'cust_id' => "",
                'cust_name' => "transaction_id",
                'return' => true,
            );

            $wp_dp_opt_customer_number_array = array(
                'id' => '',
                'std' => $transaction_id,
                'cust_id' => "",
                'cust_name' => "customer_number",
                'return' => true,
            );
			$return_url = isset( $transaction_return_url ) ? $transaction_return_url : esc_url( home_url( '/' ) );
            $wp_dp_opt_return_url_array = array(
                'id' => '',
                'std' => $return_url,
                'cust_id' => "",
                'cust_name' => "return_url",
                'return' => true,
            );

            $wp_dp_opt_merchant_fields_array = array(
                'id' => '',
                'std' => $transaction_id,
                'cust_id' => "",
                'cust_name' => "merchant_fields",
                'return' => true,
            );
            $output .= '<form name="SkrillForm" id="direcotry-skrill-form" action="' . $this->gateway_url . '" method="post">  
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_hidden_array) . '
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_amount_array) . '
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_language_array) . '
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_currency_array) . '
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_description_array) . '
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_detail1_array) . '
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_detail2_description_array) . '                    
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_detail2_text_array) . '  
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_detail3_description_array) . '  
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_detail3_text_array) . '  
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_cancel_url_array) . '  
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_status_url_array) . '  
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_transaction_id_array) . '  
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_customer_number_array) . '  
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_return_url_array) . '  
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_merchant_fields_array) . '  
                        ' . '</form>'
                        . '<h3>' . wp_dp_plugin_text_srt('wp_dp_skrill_redirecting_to_pg') . '</h3>';

            echo force_balance_tags($output);
            echo '<script>
				  	jQuery("#direcotry-skrill-form").submit();
				  </script>';
            die;
        }

    }

}