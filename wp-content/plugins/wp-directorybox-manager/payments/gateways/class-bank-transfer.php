<?php

/**
 *  File Type: Pre Bank Transfer
 *
 */
if ( ! class_exists('WP_DP_PRE_BANK_TRANSFER') ) {

    class WP_DP_PRE_BANK_TRANSFER {

        public function __construct() {
            global $wp_dp_gateway_options;
            $wp_dp_gateway_options = get_option('wp_dp_plugin_options');
            $wp_dp_gateway_options = apply_filters('wp_dp_translate_options', $wp_dp_gateway_options );
        }

        // Start function for Bank Transfer setting 

        public function settings($wp_dp_gateways_id = '') {
            global $post;

            $wp_dp_rand_id = rand(10000000, 99999999);

            $on_off_option = array( "show" => wp_dp_plugin_text_srt('wp_dp_banktransfer_options_on'), "hide" => wp_dp_plugin_text_srt('wp_dp_banktransfer_options_off') );

            $wp_dp_settings[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_banktransfer_settings'),
                "id" => "tab-heading-options",
                "std" => wp_dp_plugin_text_srt('wp_dp_banktransfer_settings'),
                "type" => "section",
                "parrent_id" => "$wp_dp_gateways_id",
                "active" => false,
            );

            $wp_dp_settings[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_banktransfer_custom_logo'),
                "desc" => "",
                "hint_text" => "",
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_banktransfer_custom_logo_hint'),
                "id" => "pre_bank_transfer_logo",
                "std" => wp_dp::plugin_url() . 'payments/images/bank.png',
                "display" => "none",
                "type" => "upload logo"
            );

            $wp_dp_settings[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_banktransfer_dfault_status'),
                "desc" => "",
                "hint_text" => '',
                "label_desc" => wp_dp_plugin_text_srt('wp_dp_banktransfer_dfault_status_hint'),
                "id" => "pre_bank_transfer_status",
                "std" => "on",
                "type" => "checkbox",
                "options" => $on_off_option
            );
            $wp_dp_settings[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_banktransfer_bank_info'),
                "desc" => "",
                "hint_text" => '',
                "label_desc" => wp_dp_plugin_text_srt('wp_dp_banktransfer_bank_info_hint'),
                "id" => "bank_information",
                "std" => "",
                "type" => "text"
            );
            $wp_dp_settings[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_banktransfer_account_num'),
                "desc" => "",
                "hint_text" => '',
				"label_desc" => wp_dp_plugin_text_srt('wp_dp_banktransfer_account_num_hint'),
                "id" => "bank_account_id",
                "std" => "",
                "type" => "text"
            );
            $wp_dp_settings[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_banktransfer_other_info'),
                "desc" => "",
                "hint_text" => '',
				"label_desc" => wp_dp_plugin_text_srt('wp_dp_banktransfer_other_info_hint'),
                "id" => "other_information",
                "std" => "",
                "type" => "textarea"
            );

            return $wp_dp_settings;
        }

        // Start function for process request 

        public function wp_dp_proress_request($params = '') {
            global $post, $wp_dp_plugin_options, $wp_dp_gateway_options, $current_user;

            extract($params);

            $wp_dp_totl_amount = 0;
            $wp_dp_detail = '';
            //$wp_dp_currency_sign = isset($wp_dp_plugin_options['wp_dp_currency_sign']) ? $wp_dp_plugin_options['wp_dp_currency_sign'] : '$';
            $wp_dp_currency_sign = wp_dp_get_currency_sign();
            
            
            

            if ( isset($transaction_package) && $transaction_package <> '' ) {
                $transaction_package_title = $transaction_package <> '' ? get_the_title($transaction_package) : '';
                $transaction_package_price = $transaction_package <> '' ? get_post_meta($transaction_package, 'wp_dp_package_price', true) : '';
                $wp_dp_detail .= '<li> ' .  wp_dp_plugin_text_srt('wp_dp_banktransfer_packages'). $transaction_package_title . ' - ' . $wp_dp_currency_sign . $transaction_package_price . '</li>';
                $wp_dp_totl_amount += WP_DP_FUNCTIONS()->num_format($transaction_package_price);
                $wp_dp_totl_amount = WP_DP_FUNCTIONS()->num_format($wp_dp_totl_amount);
                $wp_dp_detail .= '<li> ' . wp_dp_plugin_text_srt('wp_dp_banktransfer_charges'). $wp_dp_currency_sign . $wp_dp_totl_amount . '</li>';
            }

            if ( isset($vat_amount) && $vat_amount > 0 && $wp_dp_totl_amount > 0 ) {

                $wp_dp_totl_amount += $vat_amount;
                $wp_dp_totl_amount = WP_DP_FUNCTIONS()->num_format($wp_dp_totl_amount);
                $wp_dp_detail .= '<li> ' .  wp_dp_plugin_text_srt('wp_dp_banktransfer_vat'). ' ' . $wp_dp_currency_sign . $vat_amount . '</li>';
                $wp_dp_detail .= '<li> ' .  wp_dp_plugin_text_srt('wp_dp_banktransfer_total_charge'). $wp_dp_currency_sign . $wp_dp_totl_amount . '</li>';
            }

            $wp_dp_bank_transfer = '<div class="wp-dp-bank-transfer">';
            $wp_dp_bank_transfer .= '<h2>' . wp_dp_plugin_text_srt('wp_dp_banktransfer_order_detail') . '</h2>';
            $wp_dp_bank_transfer .= '<ul class="list-group">';
            $wp_dp_bank_transfer .= '<li class="list-group-item">';
            $wp_dp_bank_transfer .= '<span class="badge">#' . (isset($trans_rand_id) ? $trans_rand_id : $transaction_id) . '</span>';
            $wp_dp_bank_transfer .= wp_dp_plugin_text_srt('wp_dp_banktransfer_order_id');
            $wp_dp_bank_transfer .= '</li>';
            $wp_dp_bank_transfer .= $wp_dp_detail;
            $wp_dp_bank_transfer .= '</ul>';
            $wp_dp_bank_transfer .= '<h2>' . wp_dp_plugin_text_srt('wp_dp_banktransfer_bank_detail') . '</h2>';
            $wp_dp_bank_transfer .= '<p>' . wp_dp_plugin_text_srt('wp_dp_banktransfer_bank_detail_hint') . '</p>';
            $wp_dp_bank_transfer .= '<ul class="list-group">';

            if ( isset($wp_dp_gateway_options['wp_dp_bank_information']) && $wp_dp_gateway_options['wp_dp_bank_information'] != '' ) {
                $wp_dp_bank_transfer .= '<li class="list-group-item">';
                $wp_dp_bank_transfer .= '<span class="badge">' . $wp_dp_gateway_options['wp_dp_bank_information'] . '</span>';
                $wp_dp_bank_transfer .= wp_dp_plugin_text_srt('wp_dp_banktransfer_bank_info');
                $wp_dp_bank_transfer .= '</li>';
            }

            if ( isset($wp_dp_gateway_options['wp_dp_bank_account_id']) && $wp_dp_gateway_options['wp_dp_bank_account_id'] != '' ) {
                $wp_dp_bank_transfer .= '<li class="list-group-item">';
                $wp_dp_bank_transfer .= '<span class="badge">' . $wp_dp_gateway_options['wp_dp_bank_account_id'] . '</span>';
                $wp_dp_bank_transfer .=  wp_dp_plugin_text_srt('wp_dp_banktransfer_account_no');
                $wp_dp_bank_transfer .= '</li>';
            }

            if ( isset($wp_dp_gateway_options['wp_dp_other_information']) && $wp_dp_gateway_options['wp_dp_other_information'] != '' ) {
                $wp_dp_bank_transfer .= '<li class="list-group-item">';
                $wp_dp_bank_transfer .= '<span>' . $wp_dp_gateway_options['wp_dp_other_information'] . '</span>';
                $wp_dp_bank_transfer .= '</li>';
            }

            $wp_dp_bank_transfer .= '</ul>';
            $wp_dp_bank_transfer .= '</div>';

            return force_balance_tags($wp_dp_bank_transfer);
        }

    }
 
}