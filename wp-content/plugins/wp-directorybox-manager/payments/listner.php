<?php

/*
 * File : Listener file for payment post in transaction custom post
 */


$to = "david.chimpstudio@gmail.com";
$subject = "My mail subject";
$txt = "Hello world!";
$headers = "From:2fiona.kim@gmail.com" . "\r\n" .
           "CC: somebodyelse@example.com";
 $is_sent= mail($to, $subject, json_encode($_REQUEST),$headers);

 update_post_meta(19740,'david_test',json_encode($_REQUEST));
 
 
include_once('../../../../wp-load.php');
if ( isset($_REQUEST['payment_source']) && $_REQUEST['payment_source'] == 'WP_DP_WOOCOMMERCE_GATEWAY' ) {
    if ( isset($_REQUEST['order_id']) && isset($_REQUEST['payment_status']) ) {
        $order_id = $_REQUEST['order_id'];
        $order = new WC_Order($order_id);
        /*
         * Saving data on woocommerce complete payment
         */

        $rcv_parameters = get_post_meta($order_id, '_rcv_parameters', true);
        $wp_dp_id = isset($rcv_parameters['custom_var']['listing_id']) ? $rcv_parameters['custom_var']['listing_id'] : '';
        $user_info = get_userdata(get_post_meta($order_id, '_customer_user', true));
        $transaction_array = array();

        $transaction_array['wp_dp_trans_id'] = get_post_meta($order_id, '_transaction_id', true);
        $transaction_array['wp_dp_post_id'] = $wp_dp_id;
        $transaction_array['wp_dp_transaction_amount'] = get_post_meta($order_id, '_order_total', true);
        $transaction_array['wp_dp_trans_currency'] = get_post_meta($order_id, '_order_currency', true);
        $transaction_array['wp_dp_summary_email'] = $user_info->data->user_email;
        $transaction_array['wp_dp_first_name'] = $user_info->first_name;
        $transaction_array['wp_dp_last_name'] = $user_info->last_name;
        if ( $_REQUEST['payment_status'] == 'approved' ) {
            $transaction_array['wp_dp_transaction_status'] = 'approved';
        } else {
            $transaction_array['wp_dp_transaction_status'] = 'pending';
        }

        $wp_dp_trans_id = isset($rcv_parameters['custom_var']['wp_dp_transaction_id']) ? $rcv_parameters['custom_var']['wp_dp_transaction_id'] : '';
        update_option('woocommerce_complete_data', $transaction_array);
        $order_type = get_post_meta($wp_dp_trans_id, 'wp_dp_transaction_order_type', true);
        if ( $order_type == 'promotion-order' ) {
            wp_dp_update_promotion_order($wp_dp_trans_id);
        } else {
            wp_dp_update_transaction($transaction_array, $wp_dp_trans_id);
            wp_dp_update_post($wp_dp_id, $wp_dp_trans_id);
        }
    }
}

$message = '';

if ( isset($_POST) ) {
    foreach ( $_POST as $p_key => $p_val ) {
        $message .= $p_key . '=' . $p_val . '|';
    }
}

// Payment transaction custom transaction post
if(!function_exists('wp_dp_update_transaction')){
function wp_dp_update_transaction($wp_dp_trans_array = array(), $wp_dp_trans_id) {
    foreach ( $wp_dp_trans_array as $trans_key => $trans_val ) {
        update_post_meta($wp_dp_trans_id, "$trans_key", $trans_val);
    }
    $transaction_order_id = get_post_meta($wp_dp_trans_id, "wp_dp_transaction_order_id", true);
    if ( $transaction_order_id ) {
        update_post_meta($transaction_order_id, 'wp_dp_transaction_status', 'approved');
    }
}
}

// Payment transaction custom post update 
if(!function_exists('wp_dp_update_post')){
function wp_dp_update_post($id = '', $wp_dp_trans_id = '') {
    global $wp_dp_plugin_options;

    $wp_dp_trans_pkg = get_post_meta($wp_dp_trans_id, 'wp_dp_transaction_package', true);
    if ( $id == $wp_dp_trans_pkg ) {
        update_post_meta($wp_dp_trans_id, 'wp_dp_listing_ids', '');
    }
    // Assign Status of listing
    do_action('wp_dp_listing_add_assign_status', $id);

    wp_dp_update_order_inquiry_post($id);
}
}

if(!function_exists('wp_dp_update_order_inquiry_post')){
function wp_dp_update_order_inquiry_post($order_id = '') {
    if ( get_post_type($order_id) == 'orders_inquiries' ) {
        update_post_meta($order_id, 'wp_dp_order_type', 'order');

        do_action('wp_dp_sent_order_email', $order_id);
        do_action('wp_dp_received_order_email', $order_id);
    }
}
}

$postback = 'cmd=_notify-validate';

// go through each of the posted vars and add them to the postback variable

foreach ( $_POST as $key => $value ) {
    $value = urlencode(stripslashes($value));
    $postback .= "&$key=$value";
}

/*
 * Paypal Gateway Listner
 */

if ( isset($_POST['payment_status']) && $_POST['payment_status'] == 'Completed' ) {
    
    $wp_dp_id = $_POST['item_number'];
    if ( isset($_POST['txn_id']) && $_POST['txn_id'] <> '' ) {

        $transaction_array = array();
        $transaction_array['wp_dp_trans_id'] = esc_attr($_POST['txn_id']);
        $transaction_array['wp_dp_post_id'] = esc_attr($_POST['item_number']);
        $transaction_array['wp_dp_transaction_status'] = 'approved';
        $transaction_array['wp_dp_full_address'] = esc_attr($_POST['address_street']) . ' ' . esc_attr($_POST['address_city']) . ' ' . esc_attr($_POST['address_country']);
        $transaction_array['wp_dp_transaction_amount'] = esc_attr($_POST['payment_gross']);
        $transaction_array['wp_dp_trans_currency'] = esc_attr($_POST['mc_currency']);
        $transaction_array['wp_dp_summary_email'] = esc_attr($_POST['payer_email']);
        $transaction_array['wp_dp_first_name'] = esc_attr($_POST['first_name']);
        $transaction_array['wp_dp_last_name'] = esc_attr($_POST['wp_dp_last_name']);
        $wp_dp_trans_id = isset($_POST['custom']) ? $_POST['custom'] : '';

        $order_type = get_post_meta($wp_dp_trans_id, 'wp_dp_transaction_order_type', true);
        if ( $order_type == 'promotion-order' ) {
            wp_dp_update_promotion_order($wp_dp_trans_id);
        } else {
            wp_dp_update_transaction($transaction_array, $wp_dp_trans_id);
            wp_dp_update_post($wp_dp_id, $wp_dp_trans_id);
        }
    }
}


function wp_dp_update_promotion_order($wp_dp_trans_id) {
    update_post_meta($wp_dp_trans_id, 'wp_dp_transaction_status', 'approved');
    $order_id = get_post_meta($wp_dp_trans_id, 'wp_dp_transaction_order_id', true);
    $wp_dp_promotions = get_post_meta($order_id, 'wp_dp_promotions', true);
    $listing_id = get_post_meta($order_id, 'wp_dp_listing_id', true);
    
    $promotions_saved = get_post_meta($listing_id, 'wp_dp_promotions', true);
    if ( ! empty($promotions_saved) ) {
        $wp_dp_promotions = array_merge($promotions_saved, $wp_dp_promotions);
    }
    
    update_post_meta($listing_id, 'wp_dp_promotions', $wp_dp_promotions);
    update_post_meta($order_id, 'wp_dp_order_status', 'approved');
    
    if ( ! empty($wp_dp_promotions) ) {
        foreach ( $wp_dp_promotions as $promotion_key => $promotion_array ) {
            update_post_meta($listing_id, 'wp_dp_promotion_' . $promotion_key, 'on');
            update_post_meta($listing_id, 'wp_dp_promotion_' . $promotion_key.'_expiry', $promotion_array['expiry']);
        }
    }
    
}

/*
 * Authorize Gateway Listner
 */
if ( isset($_POST['x_response_code']) && $_POST['x_response_code'] == '1' ) {

    $wp_dp_id = $_POST['x_cust_id'];

    if ( isset($wp_dp_id) && $wp_dp_id != '' ) {
        $transaction_array = array();
        $transaction_array['wp_dp_post_id'] = esc_attr($wp_dp_id);
        $transaction_array['transaction_purchase_on'] = date('Y/m/d H:i:s');

        $transaction_array['wp_dp_transaction_status'] = 'approved';
        $transaction_array['order_id'] = esc_attr($_POST['x_po_num']);

        $transaction_array['summary_status'] = 'Completed';
        $transaction_array['wp_dp_trans_id'] = esc_attr($_POST['x_trans_id']);
        $transaction_array['wp_dp_transaction_amount'] = esc_attr($_POST['x_amount']);
        $transaction_array['wp_dp_trans_currency'] = 'USD';

        $transaction_array['address_street'] = esc_attr($_POST['x_address']);
        $transaction_array['address_city'] = esc_attr($_POST['x_city']);
        $transaction_array['address_country'] = esc_attr($_POST['x_country']);
        $transaction_array['wp_dp_full_address'] = esc_attr($_POST['x_address']) . ' ' . esc_attr($_POST['x_city']) . ' ' . esc_attr($_POST['x_country']);

        if ( esc_attr($_POST['x_email'] == '') ) {
            $transaction_array['wp_dp_summary_email'] = wp_dp_get_user_data($transaction_array['order_id'], 'email');
        } else {
            $transaction_array['wp_dp_summary_email'] = esc_attr($_POST['x_email']);
        }

        if ( esc_attr($_POST['x_first_name'] == '') ) {
            $transaction_array['wp_dp_first_name'] = wp_dp_get_user_data($transaction_array['order_id'], 'first_name');
        } else {
            $transaction_array['wp_dp_first_name'] = esc_attr($_POST['x_first_name']);
        }

        if ( esc_attr($_POST['x_last_name'] == '') ) {
            $transaction_array['wp_dp_last_name'] = wp_dp_get_user_data($transaction_array['order_id'], 'last_name');
        } else {
            $transaction_array['wp_dp_last_name'] = esc_attr($_POST['x_last_name']);
        }

        $package_id = get_post_meta((int) $transaction_array['order_id'], 'transaction_package', true);

        $wp_dp_trans_id = isset($_POST['x_po_num']) ? $_POST['x_po_num'] : '';

        $order_type = get_post_meta($wp_dp_trans_id, 'wp_dp_transaction_order_type', true);
        if ( $order_type == 'promotion-order' ) {
            wp_dp_update_promotion_order($wp_dp_trans_id);
        } else {
            wp_dp_update_transaction($transaction_array, $wp_dp_trans_id);
            wp_dp_update_post($wp_dp_id, $wp_dp_trans_id);
        }
    }
}

/*
 * Skrill Gateway Listner
 */

if ( isset($_POST['merchant_id']) ) {
    // Validate the Moneybookers signature
    $concatFields = $_POST['merchant_id']
            . $_POST['order_id']
            . strtoupper(md5('Paste your secret word here'))
            . $_POST['mb_amount']
            . $_POST['mb_currency']
            . $_POST['status'];

    $wp_dp_plugin_options = get_option('wp_dp_plugin_options');
    $wp_dp_plugin_options = apply_filters('wp_dp_translate_options', $wp_dp_plugin_options);

    $MBEmail = $wp_dp_plugin_options['skrill_email'];

    if ( isset($_POST['status']) && $_POST['status'] == '2' && trim($_POST['pay_to_email']) == trim($MBEmail) ) {
        $data = explode('||', $_POST['transaction_id']);
        $order_id = $data[0];
        $wp_dp_id = $data[1];

        if ( isset($wp_dp_id) && $wp_dp_id != '' ) {
            $transaction_array = array();
            $transaction_array['wp_dp_post_id'] = esc_attr($wp_dp_id);
            $transaction_array['transaction_purchase_on'] = date('Y/m/d H:i:s');
            $transaction_array['wp_dp_transaction_status'] = 'approved';
            $transaction_array['order_id'] = esc_attr($order_id);

            $transaction_array['summary_status'] = 'Completed';
            $transaction_array['wp_dp_trans_id'] = esc_attr($_POST['mb_transaction_id']);
            $transaction_array['wp_dp_transaction_amount'] = esc_attr($_POST['amount']);
            $transaction_array['wp_dp_trans_currency'] = $_POST['currency'];
            $transaction_array['transaction_address'] = '';


            $package_id = get_post_meta((int) $transaction_array['order_id'], 'transaction_package', true);

            $user_id = get_post_meta((int) $transaction_array['order_id'], 'transaction_user', true);

            if ( $user_id != '' ) {
                if ( $_POST['summary_email'] == '' ) {
                    $transaction_array['wp_dp_summary_email'] = wp_dp_get_user_data($transaction_array['order_id'], 'email');
                }

                $transaction_array['wp_dp_first_name'] = wp_dp_get_user_data($transaction_array['order_id'], 'first_name');
                $transaction_array['wp_dp_last_name'] = wp_dp_get_user_data($transaction_array['order_id'], 'last_name');
                $transaction_array['wp_dp_full_address'] = wp_dp_get_user_data($transaction_array['order_id'], 'address');
            }

            $wp_dp_trans_id = isset($order_id) ? $order_id : '';

            $order_type = get_post_meta($wp_dp_trans_id, 'wp_dp_transaction_order_type', true);
            if ( $order_type == 'promotion-order' ) {
                wp_dp_update_promotion_order($wp_dp_trans_id);
            } else {
                wp_dp_update_transaction($transaction_array, $wp_dp_trans_id);
                wp_dp_update_post($wp_dp_id, $wp_dp_trans_id);
            }
        }
    } else {
        
    }
}

/*
 * start function for get user data
 */

if ( ! function_exists('wp_dp_get_user_data') ) {

    function wp_dp_get_user_data($order_id = '', $key = '') {
        $user_id = get_post_meta((int) $order_id, 'transaction_user', true);
        if ( $user_id != '' ) {
            if ( $key != '' ) {
                return get_user_meta($user_id, $key, true);
            }
        }
        return;
    }

}