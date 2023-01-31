<?php

/**
 * Start Function  how to Create Transations Fields
 */
if ( ! function_exists('wp_dp_create_transactions_fields') ) {

    function wp_dp_create_transactions_fields($key, $param) {
        global $post, $wp_dp_html_fields, $wp_dp_form_fields, $wp_dp_plugin_options;
        $wp_dp_gateway_options = get_option('wp_dp_plugin_options');
        $wp_dp_gateway_options = apply_filters('wp_dp_translate_options', $wp_dp_gateway_options);
        $wp_dp_currency_sign = wp_dp_get_currency_sign();
        $wp_dp_value = $param['title'];
        $html = '';
        switch ( $param['type'] ) {
            case 'text' :
                // prepare
                $wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $key, true);

                if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
                    $wp_dp_value = $wp_dp_value;
                } else {
                    $wp_dp_value = '';
                }

                $wp_dp_opt_array = array(
                    'name' => $param['title'],
                    'desc' => '',
                    'hint_text' => '',
                    'field_params' => array(
                        'std' => $wp_dp_value,
                        'id' => $key,
                        'classes' => 'wp-dp-form-text wp-dp-input',
                        'force_std' => true,
                        'return' => true,
                    ),
                );
                $output = '';
                $output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                $output .= '<span class="wp-dp-form-desc">' . $param['description'] . '</span>' . "\n";


                $html .= $output;
                break;
            case 'checkbox' :
                // prepare
                $wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $key, true);

                $wp_dp_opt_array = array(
                    'name' => $param['title'],
                    'desc' => '',
                    'hint_text' => '',
                    'field_params' => array(
                        'std' => $wp_dp_value,
                        'id' => $key,
                        'classes' => 'wp-dp-form-text wp-dp-input',
                        'force_std' => true,
                        'return' => true,
                    ),
                );
                $output = '';
                $output .= $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

                $html .= $output;
                break;
            case 'textarea' :
                // prepare
                $wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $key, true);
                if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
                    $wp_dp_value = $wp_dp_value;
                } else {
                    $wp_dp_value = '';
                }

                $wp_dp_opt_array = array(
                    'name' => $param['title'],
                    'desc' => '',
                    'hint_text' => '',
                    'field_params' => array(
                        'std' => '',
                        'id' => $key,
                        'return' => true,
                    ),
                );

                $output = $wp_dp_html_fields->wp_dp_textarea_field($wp_dp_opt_array);
                $html .= $output;
                break;
            case 'select' :
                $wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $key, true);
                if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
                    $wp_dp_value = $wp_dp_value;
                } else {
                    $wp_dp_value = '';
                }
                $wp_dp_classes = '';
                if ( isset($param['classes']) && $param['classes'] != "" ) {
                    $wp_dp_classes = $param['classes'];
                }
                $wp_dp_opt_array = array(
                    'name' => $param['title'],
                    'desc' => '',
                    'hint_text' => '',
                    'field_params' => array(
                        'std' => '',
                        'id' => $key,
                        'classes' => $wp_dp_classes,
                        'options' => $param['options'],
                        'return' => true,
                    ),
                );

                $output = $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                // append
                $html .= $output;
                break;
            case 'hidden_label' :
                // prepare
                $wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $key, true);
                if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
                    $wp_dp_value = $wp_dp_value;
                } else {
                    $wp_dp_value = '';
                }
                $hash_sign = '#';
                if ( $key == 'transaction_order_type' ) {
                    $trans_order_type = array(
                        'package-order' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_packages_order'),
                        'promotion-order' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_promotion_order'),
                    );
                    foreach ( $trans_order_type as $key => $value ) {
                        if ( $key == $wp_dp_value ) {
                            $wp_dp_value = $value;
                        }
                    }
                    $hash_sign = '';
                }
                if ( $key == 'transaction_amount' ) {
                    $hash_sign = '';
                }
                if ( $key == 'transaction_user' ) {

                    $wp_dp_users_list = array( '' => wp_dp_plugin_text_srt('wp_dp_transaction_post_type_slct_pblisher') );
                    $args = array( 'posts_per_page' => '-1', 'post_type' => 'members', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC' );
                    $cust_query = get_posts($args);
                    if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                        foreach ( $cust_query as $package_post ) {
                            if ( isset($package_post->ID) ) {
                                $package_id = $package_post->ID;
                                $package_title = $package_post->post_title;
                                $wp_dp_users_list[$package_id] = $package_title;
                            }
                        }
                    }

                    foreach ( $wp_dp_users_list as $key => $value ) {
                        if ( $key == $wp_dp_value ) {
                            $wp_dp_value = $value;
                        }
                    }
                    $hash_sign = '';
                }


                if ( $key == 'transaction_pay_method' ) {
                    global $gateways;
                    $object = new WP_DP_PAYMENTS();
                    $payment_geteways = array();
                    $payment_geteways[''] = wp_dp_plugin_text_srt('wp_dp_transaction_post_type_slct_pay_gateway');
                    $wp_dp_gateway_options = get_option('wp_dp_plugin_options');
                    $wp_dp_gateway_options = apply_filters('wp_dp_translate_options', $wp_dp_gateway_options);

                    foreach ( $gateways as $key => $value ) {
                        $status = $wp_dp_gateway_options[strtolower($key) . '_status'];
                        if ( isset($status) && $status == 'on' ) {
                            $payment_geteways[$key] = $value;
                        }
                    }

                    if ( isset($wp_dp_gateway_options['wp_dp_use_woocommerce_gateway']) && $wp_dp_gateway_options['wp_dp_use_woocommerce_gateway'] == 'on' ) {
                        if ( class_exists('WooCommerce') ) {
                            unset($payment_geteways);
                            $payment_geteways[''] = wp_dp_plugin_text_srt('wp_dp_transaction_slct_paymnt_gateway');
                            $gateways = WC()->payment_gateways->get_available_payment_gateways();
                            foreach ( $gateways as $key => $gateway_data ) {
                                $payment_geteways[$key] = $gateway_data->method_title;
                            }
                        }
                    }
                    foreach ( $payment_geteways as $key => $value ) {
                        if ( $key == $wp_dp_value ) {
                            $wp_dp_value = $value;
                        }
                    }
                    $hash_sign = '';
                }

                $wp_dp_opt_array = array(
                    'name' => $param['title'],
                    'hint_text' => '',
                );
                $output = $wp_dp_html_fields->wp_dp_opening_field($wp_dp_opt_array);
                $output .= '<span>' . $hash_sign . $wp_dp_value . '</span>';
                $output .= $wp_dp_form_fields->wp_dp_form_hidden_render(
                        array(
                            'name' => '',
                            'id' => $key,
                            'return' => true,
                            'classes' => '',
                            'std' => $wp_dp_value,
                            'description' => '',
                            'hint' => ''
                        )
                );

                $wp_dp_opt_array = array(
                    'desc' => '',
                );
                $output .= $wp_dp_html_fields->wp_dp_closing_field($wp_dp_opt_array);
                $html .= $output;
                break;

            case 'summary' :
                // prepare
                $trans_first_name = get_post_meta($post->ID, 'wp_dp_trans_first_name', true);
                $trans_last_name = get_post_meta($post->ID, 'wp_dp_trans_last_name', true);
                $trans_email = get_post_meta($post->ID, 'wp_dp_trans_email', true);
                $trans_phone_number = get_post_meta($post->ID, 'wp_dp_trans_phone_number', true);
                $trans_address = get_post_meta($post->ID, 'wp_dp_trans_address', true);

                $output = '';

                if ( $trans_first_name != '' || $trans_last_name != '' || $trans_email != '' || $trans_phone_number != '' || $trans_address != '' ) {

                    $wp_dp_opt_array = array(
                        'name' => $param['title'],
                        'hint_text' => '',
                    );
                    $output .= $wp_dp_html_fields->wp_dp_opening_field($wp_dp_opt_array);

                    $output .= '<ul class="trans-user-summary">';

                    if ( $trans_first_name != '' ) {
                        $output .= '<li>';
                        $output .= '<label>' . wp_dp_plugin_text_srt('wp_dp_trans_meta_first_name') . '</label><span>' . $trans_first_name . '</span>';
                        $output .= '</li>';
                    }
                    if ( $trans_last_name != '' ) {
                        $output .= '<li>';
                        $output .= '<label>' . wp_dp_plugin_text_srt('wp_dp_trans_meta_last_name') . '</label><span>' . $trans_last_name . '</span>';
                        $output .= '</li>';
                    }
                    if ( $trans_email != '' ) {
                        $output .= '<li>';
                        $output .= '<label>' . wp_dp_plugin_text_srt('wp_dp_trans_meta_email') . '</label><span>' . $trans_email . '</span>';
                        $output .= '</li>';
                    }
                    if ( $trans_phone_number != '' ) {
                        $output .= '<li>';
                        $output .= '<label>' . wp_dp_plugin_text_srt('wp_dp_trans_meta_phone_num') . '</label><span><a href="tel:' . $trans_phone_number . '">' . $trans_phone_number . '</a></span>';
                        $output .= '</li>';
                    }
                    if ( $trans_address != '' ) {
                        $output .= '<li>';
                        $output .= '<label>' . wp_dp_plugin_text_srt('wp_dp_trans_meta_address') . '</label><span>' . $trans_address . '</span>';
                        $output .= '</li>';
                    }

                    $output .= '<ul>';

                    $wp_dp_opt_array = array(
                        'desc' => '',
                    );
                    $output .= $wp_dp_html_fields->wp_dp_closing_field($wp_dp_opt_array);
                }

                $html .= $output;
                break;

            case 'promotion_summary' :
                // prepare
                $wp_dp_promotions = get_post_meta($post->ID, 'wp_dp_promotions', true);
                $output = '';

                $wp_dp_opt_array = array(
                    'name' => $param['title'],
                    'hint_text' => '',
                );
                $output .= $wp_dp_html_fields->wp_dp_opening_field($wp_dp_opt_array);

                $publish_date = get_the_date('d/M', get_the_ID());
                $output .= '<ul class="trans-user-summary">';

                $currency_sign = get_post_meta($post->ID, "wp_dp_currency", true);
                $currency_sign = ( $currency_sign != '' ) ? $currency_sign : '$';
                $currency_position = get_post_meta($post->ID, "wp_dp_currency_position", true);

                foreach ( $wp_dp_promotions as $promotion_array ) {
                    if ( isset($promotion_array['price']) && $promotion_array['price'] != '' ) {
                        $price = wp_dp_get_order_currency($promotion_array['price'], $currency_sign, $currency_position);
                    } else {
                        $price = 'Free';
                    }
                    $expiry_date = isset($promotion_array['expiry']) ? $promotion_array['expiry'] : 'unlimitted';
                    if ( $expiry_date == '' ) {
                        $expiry_date = 'unlimitted';
                    }
                    if ( $expiry_date != 'unlimitted' ) {
                        $expiry_date = date("d/M", strtotime($expiry_date));
                        $expiry_date = $publish_date . ' - ' . $expiry_date;
                    }
                    $output .= '<li>';
                    $output .= '<b>' . wp_dp_plugin_text_srt('wp_dp_promotion_title') . ': </b><span>' . $promotion_array['title'] . '</span>';
                    $output .= '</li>';
                    $output .= '<li>';
                    $output .= '<b>' . wp_dp_plugin_text_srt('wp_dp_promotion_amount') . ': </b><span>' . $price . '</span>';
                    $output .= '</li>';
                    $output .= '<li>';
                    $output .= '<b>' . wp_dp_plugin_text_srt('wp_dp_promotion_duration') . ': </b><span>' . $promotion_array['duration'] . ' Days (' . $expiry_date . ')</span>';
                    $output .= '</li>';
                    $output .= '<hr>';
                }
                $output .= '<ul>';

                $wp_dp_opt_array = array(
                    'desc' => '',
                );
                $output .= $wp_dp_html_fields->wp_dp_closing_field($wp_dp_opt_array);

                $html .= $output;
                break;

            default :
                break;
        }
        return $html;
    }

}
/**
 * End Function  how to Create Transations Fields
 */