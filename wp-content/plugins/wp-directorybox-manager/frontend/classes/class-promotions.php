<?php
/**
 * File Type: Promotions
 */
if ( ! class_exists('Wp_dp_Promotions_Frontend') ) {

    class Wp_dp_Promotions_Frontend {

        /**
         * Start construct Functions
         */
        public function __construct() {
            global $wp_dp_plugin_options;
            $wp_dp_promotions_switch = isset($wp_dp_plugin_options['wp_dp_promotions_switch']) ? $wp_dp_plugin_options['wp_dp_promotions_switch'] : 'on';
            if ( $wp_dp_promotions_switch == 'on' ) {
                add_action('wp_dp_listings_quick_links', array( $this, 'wp_dp_listings_quick_links_callback' ), 10, 1);
                add_action('wp_ajax_wp_dp_promotions_pay', array( $this, 'wp_dp_promotions_pay_callback' ));
                add_action('wp_ajax_wp_dp_promotions_process', array( $this, 'wp_dp_promotions_process_callback' ));
                add_action('wp_dp_listings_caption_area', array( $this, 'wp_dp_listings_caption_area_callback' ), 10, 1);
            }
        }

        public function wp_dp_listings_quick_links_callback($listing_id) {
            
            $listing_status = get_post_meta($listing_id, 'wp_dp_listing_status', true);
            
            if($listing_status == 'active'){
             ?>
            <li class="promote-listing" data-param="<?php echo absint($listing_id); ?>" data-queryvar="dashboard=viewings_received">
                <a href="javascript:void(0);" data-toggle="modal" data-target="#promotions-popup-<?php echo absint($listing_id); ?>"><figure><img src="<?php echo esc_url(wp_dp::plugin_url() . 'assets/frontend/images/loud.png'); ?>" alt=""></figure><span><?php echo wp_dp_plugin_text_srt('wp_dp_promote_listing'); ?></span></a>
            </li>
            <?php  
            $this->popup_render($listing_id);
            }
            
        }

        public function popup_render($listing_id) {
            global $wp_dp_plugin_options, $wp_dp_html_fields;
            $wp_dp_promotions = isset($wp_dp_plugin_options['wp_dp_promotions']) ? $wp_dp_plugin_options['wp_dp_promotions'] : '';

            $wp_dp_vat_switch = isset($wp_dp_plugin_options['wp_dp_vat_switch']) ? $wp_dp_plugin_options['wp_dp_vat_switch'] : '';
            $wp_dp_pay_vat = isset($wp_dp_plugin_options['wp_dp_payment_vat']) ? $wp_dp_plugin_options['wp_dp_payment_vat'] : '';
            $wp_dp_pay_vat = isset($wp_dp_plugin_options['wp_dp_payment_vat']) ? $wp_dp_plugin_options['wp_dp_payment_vat'] : '';
            $promotions_popup_footer_text = isset($wp_dp_plugin_options['wp_dp_promotions_popup_footer_text']) ? $wp_dp_plugin_options['wp_dp_promotions_popup_footer_text'] : '';

            $promotions = get_post_meta($listing_id, 'wp_dp_promotions', true);
            $woocommerce_enabled = isset( $woocommerce_enabled  ) ? $woocommerce_enabled : ''; 
            $vat_enable = false;
            $wp_dp_pay_vat_data = 0;
            if ( $woocommerce_enabled != 'on' ) {
                if ( $wp_dp_vat_switch == 'on' && $wp_dp_pay_vat > 0 ) {
                    $vat_enable = true;
                    $wp_dp_pay_vat_data = $wp_dp_pay_vat;
                }
            }
            ?>
            <div id="promotions-popup-<?php echo absint($listing_id); ?>" class="modal fade promotion-popup-area" role="dialog" data-vat="<?php echo wp_dp_cs_allow_special_char($wp_dp_pay_vat_data); ?>">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><i class="icon-cross-out"></i></button>
                            <h4 class="modal-title"><?php echo wp_dp_plugin_text_srt('wp_dp_promotion_orders'); ?></h4>
                            <span><?php echo wp_dp_plugin_text_srt('wp_dp_promote_your_ad'); ?></span>
                        </div>
                        <div class="modal-body promotion-body-<?php echo wp_dp_cs_allow_special_char($listing_id); ?>">
                            <form id="promotions-form-<?php echo wp_dp_cs_allow_special_char($listing_id); ?>">
                                <?php
                                if ( ! empty($wp_dp_promotions) ) {
                                    echo '<ul>';
                                    foreach ( $wp_dp_promotions as $wp_dp_promotion_key => $wp_dp_promotion ) {
                                        $purchased_status = get_post_meta($listing_id, 'wp_dp_promotion_' . $wp_dp_promotion_key, true);
                                        $already_purchased = false;
                                        if ( $purchased_status == 'on' ) {
                                            $expiry_date = $promotions[$wp_dp_promotion_key]['expiry'];
                                            if ( $expiry_date >= date('Y-m-d') || $expiry_date == 'unlimitted' ) {
                                                $already_purchased = true;
                                            }
                                        }
                                        $disabled = ( $already_purchased == true ) ? ' disabled' : '';
                                        $purchaed_class = ( $already_purchased == true ) ? ' class="already-purchased"' : '';
                                        $bg_color = ( isset($wp_dp_promotion['background']) && $wp_dp_promotion['background'] != '' ) ? ' style="background:' . $wp_dp_promotion['background'] . '"' : '';
                                        ?>
                                        <li<?php echo wp_dp_cs_allow_special_char($purchaed_class); ?>>
                                            <div class="promotion-info">
                                                <?php  
                                                $wp_dp_promotion_val = isset($wp_dp_promotion['price']) ? $wp_dp_promotion['price'] : '';
                                                $wp_dp_opt_array = array(
                                                    'cust_name' => 'promotions[]',
                                                    'cust_type' => 'checkbox',
                                                    'id' => "promotion-".$wp_dp_promotion_key."-".$listing_id."",
                                                    'classes' => 'promotion-selection',
                                                    'std' => wp_dp_cs_allow_special_char($wp_dp_promotion_key),
                                                    'extra_atr' => 'data-price='.$wp_dp_promotion_val,
                                                    'prefix_on' => false
                                                );
                                                $wp_dp_html_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                                ?>
                                                <label for="promotion-<?php echo ($wp_dp_promotion_key) . '-' . $listing_id; ?>"<?php echo ($bg_color); ?>><?php echo ($wp_dp_promotion['title']); ?></label>
                                                <span><?php echo ( isset($wp_dp_promotion['description']) ) ? $wp_dp_promotion['description'] : ''; ?></span>
                                            </div>
                                            <div class="promotion-price-info">
                                                <?php
                                                if ( $already_purchased == true ) {
                                                    $now = date('Y-m-d');
                                                    $datediff = (strtotime($expiry_date) - strtotime(date("Y-m-d")));
                                                    $remaining_days = floor($datediff / 3600 / 24);
                                                    //$remaining_days = ( $remaining_days == 0 ) ? 1 : $remaining_days;
                                                    ?>  
                                                    <span class="promotion-days"><?php echo wp_dp_cs_allow_special_char($remaining_days); ?> <?php echo _n(wp_dp_plugin_text_srt('wp_dp_promotions_day'), wp_dp_plugin_text_srt('wp_dp_promotions_days'), $remaining_days, ''); ?> <?php echo wp_dp_plugin_text_srt('wp_dp_promotion_left'); ?></span>
                                                    <?php
                                                } else {
                                                    esc_html__('days','directorybox');
                                                    
                                                    ?>
                                                    <span class="promotion-days"><?php echo ( isset($wp_dp_promotion['duration']) ) ? $wp_dp_promotion['duration'] . ' '.wp_dp_plugin_text_srt('wp_dp_promotions_days').' - ' : ''; ?></span>
                                                    <span class="promotion-price"><?php echo ( isset($wp_dp_promotion['price']) ) ? wp_dp_get_currency($wp_dp_promotion['price'], true) : 'Free'; ?></span>
                                                <?php } ?>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    echo '</ul>';
                                }
                                ?>
                            </form>
                            <?php if ( $vat_enable == true ) { ?>
                                <div class="promotion-vat">
                                    <?php echo wp_dp_plugin_text_srt('wp_dp_promotion_vat'); ?>: <span><?php echo wp_dp_cs_allow_special_char($wp_dp_pay_vat); ?>%</span>
                                </div>
                            <?php } ?>
                            <div class="promotion-total">
                                <?php echo wp_dp_plugin_text_srt('wp_dp_promotion_total'); ?>: <div class="promotion-total-price"><span><?php echo wp_dp_get_currency_sign(); ?></span><strong>0</strong></div>
                            </div>
                            <div class="promotion-pay-area">
                                <p><?php echo htmlspecialchars_decode($promotions_popup_footer_text); ?></p>
                                <a href="javascript:;" class="promotions-pay" data-id="<?php echo wp_dp_cs_allow_special_char($listing_id); ?>"><?php echo wp_dp_plugin_text_srt('wp_dp_promotion_continue'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        /*
         * Callback function for promotion submission
         */

        public function wp_dp_promotions_pay_callback() {
            global $wp_dp_form_fields, $wp_dp_plugin_options;
            $listing_id = wp_dp_get_input('listing_id', '', 'STRING');
            $promotions = wp_dp_get_input('promotions', '', 'ARRAY');

            $wp_dp_promotions = isset($wp_dp_plugin_options['wp_dp_promotions']) ? $wp_dp_plugin_options['wp_dp_promotions'] : '';
            $total_price = 0;
            if ( ! empty($promotions) ) {
                foreach ( $promotions as $promotion_key ) {
                    $promotion_array[$promotion_key] = $wp_dp_promotions[$promotion_key];

                    $duration = $promotion_array[$promotion_key]['duration'];
                    if ( $duration != '' && $duration > 0 ) {
                        $expiry_date = date('Y-m-d', time() + 86400 * $duration);
                    } else {
                        $expiry_date = 'unlimitted';
                    }
                    $promotion_array[$promotion_key]['expiry'] = $expiry_date;
                    if ( $promotion_array[$promotion_key]['price'] != '' && $promotion_array[$promotion_key]['price'] > 0 ) {
                        $total_price = $total_price + $promotion_array[$promotion_key]['price'];
                    }
                }
            }

            if ( $total_price == 0 ) {
                $order_data = array(
                    'wp_dp_member' => wp_dp_company_id_form_user_id(get_current_user_id()),
                    'wp_dp_total_amount' => $total_price,
                    'wp_dp_listing_id' => $listing_id,
                    'wp_dp_promotions' => $promotion_array,
                    'wp_dp_pay_method' => '',
                );

                $order_id = $this->create_promotion_order($order_data);

                $this->listing_add_promotions($listing_id, $promotion_array);
                $this->success_message();
            } else {
                ?>
                <div class="register-payment-gw-holder">

                    <form id="promotions-payment-form-<?php echo wp_dp_cs_allow_special_char($listing_id); ?>">

                        <div class="dashboard-element-title">
                            <strong><?php echo wp_dp_plugin_text_srt('wp_dp_add_user_payment_info'); ?></strong>
                        </div>
                        <?php
                        ob_start();
                        $_REQUEST['trans_id'] = 0;
                        $_REQUEST['action'] = 'listing-package';
                        $_GET['trans_id'] = 0;
                        $_GET['action'] = 'listing-package';
                        $trans_fields = array(
                            'trans_id' => 0,
                            'action' => 'listing-package',
                            'back_button' => true,
                            'creating' => true,
                        );
                        do_action('wp_dp_payment_gateways', $trans_fields);
                        $output = ob_get_clean();
                        echo str_replace('col-lg-8 col-md-8', 'col-lg-12 col-md-12', $output);

                        if ( ! empty($promotions) ) {
                            foreach ( $promotions as $promotion ) {
                                $wp_dp_form_fields->wp_dp_form_hidden_render(
                                        array(
                                            'cust_name' => 'promotions[]',
                                            'id' => 'promotions',
                                            'std' => $promotion,
                                        )
                                );
                            }
                        }
                        ?>
                        <div class="promotion-payment-pay">
                            <a href="javascript:;" class="promotions-payment-process" data-id="<?php echo wp_dp_cs_allow_special_char($listing_id); ?>"><?php echo wp_dp_plugin_text_srt('wp_dp_promotion_pay_now'); ?></a>
                        </div>
                    </form>
                </div>
                <?php
            }
            wp_die();
        }

        /*
         * Callback function for promotion Payment processing
         */

        public function wp_dp_promotions_process_callback() {
            global $wp_dp_plugin_options;

            $wp_dp_vat_switch = isset($wp_dp_plugin_options['wp_dp_vat_switch']) ? $wp_dp_plugin_options['wp_dp_vat_switch'] : '';
            $wp_dp_pay_vat = isset($wp_dp_plugin_options['wp_dp_payment_vat']) ? $wp_dp_plugin_options['wp_dp_payment_vat'] : '';
            $woocommerce_enabled = isset($wp_dp_plugin_options['wp_dp_use_woocommerce_gateway']) ? $wp_dp_plugin_options['wp_dp_use_woocommerce_gateway'] : '';

            $listing_id = wp_dp_get_input('listing_id', '', 'STRING');
            $payment_method = wp_dp_get_input('wp_dp_listing_gateway', '', 'STRING');
            $promotions = wp_dp_get_input('promotions', '', 'ARRAY');

            $wp_dp_promotions = isset($wp_dp_plugin_options['wp_dp_promotions']) ? $wp_dp_plugin_options['wp_dp_promotions'] : '';
            $total_price = 0;
            $wp_dp_vat_amount = 0;
            if ( ! empty($promotions) ) {
                foreach ( $promotions as $promotion_key ) {
                    $promotion_array[$promotion_key] = $wp_dp_promotions[$promotion_key];

                    $duration = $promotion_array[$promotion_key]['duration'];
                    if ( $duration != '' && $duration > 0 ) {
                        $expiry_date = date('Y-m-d', time() + 86400 * $duration);
                    } else {
                        $expiry_date = 'unlimitted';
                    }
                    $promotion_array[$promotion_key]['expiry'] = $expiry_date;
                    if ( $promotion_array[$promotion_key]['price'] != '' && $promotion_array[$promotion_key]['price'] > 0 ) {
                        $total_price = $total_price + $promotion_array[$promotion_key]['price'];
                    }
                }
            }

            if ( $woocommerce_enabled != 'on' ) {
                if ( $wp_dp_vat_switch == 'on' && $wp_dp_pay_vat > 0 && $total_price > 0 ) {

                    $wp_dp_vat_amount = $total_price * ( $wp_dp_pay_vat / 100 );
                    $wp_dp_vat_amount = WP_DP_FUNCTIONS()->num_format($wp_dp_vat_amount);
                    $total_price += $wp_dp_vat_amount;
                }
            }



            $order_data = array(
                'wp_dp_member' => wp_dp_company_id_form_user_id(get_current_user_id()),
                'wp_dp_total_amount' => $total_price,
                'wp_dp_listing_id' => $listing_id,
                'wp_dp_promotions' => $promotion_array,
                'wp_dp_pay_method' => $payment_method,
                'wp_dp_vat_amount' => $wp_dp_vat_amount
            );

            $order_id = $this->create_promotion_order($order_data);



            if ( $total_price > 0 ) {
                $wp_dp_trans_array = array(
                    'transaction_id' => $order_id, // order id
                    'transaction_user' => wp_dp_company_id_form_user_id(get_current_user_id()),
                    'transaction_package' => 'Promotions',
                    'transaction_amount' => $total_price,
                    'listing_id' => $listing_id,
                    'promotions' => $promotion_array,
                    'transaction_order_type' => 'promotion-order',
                    'transaction_pay_method' => $payment_method,
                    'transaction_return_url' => isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? wp_dp_wpml_lang_page_permalink($wp_dp_plugin_options['wp_dp_member_dashboard'], 'page') . '?response=order-completed' : site_url(),
                );
                $transaction_detail = wp_dp_payment_process($wp_dp_trans_array);

                if ( $transaction_detail ) {
                    echo force_balance_tags($transaction_detail);
                }
				$this->listing_add_promotions($listing_id, $promotion_array);
            } else {
                $this->listing_add_promotions($listing_id, $promotion_array);
            }
            wp_die();
        }

        /*
         * Add promotions into listing
         */

        public function listing_add_promotions($listing_id, $promotions) {
            $promotions_saved = get_post_meta($listing_id, 'wp_dp_promotions', true);
            if ( ! empty($promotions_saved) ) {
                $promotions = array_merge($promotions_saved, $promotions);
            }
            update_post_meta($listing_id, 'wp_dp_promotions', $promotions);
            if ( ! empty($promotions) ) {
                foreach ( $promotions as $promotion_key => $promotion_array ) {
                    update_post_meta($listing_id, 'wp_dp_promotion_' . $promotion_key, 'on');
                    update_post_meta($listing_id, 'wp_dp_promotion_' . $promotion_key . '_expiry', $promotion_array['expiry']);
                }
            }
        }

        /*
         * Creating Promotion Order
         */

        public function create_promotion_order($order_data = array()) {
            $wp_dp_order_id = rand(10000000, 99999999);
            $order_post = array(
                'post_title' => '#' . $wp_dp_order_id,
                'post_status' => 'publish',
                'post_type' => 'promotion-orders',
                'post_date' => current_time('Y/m/d H:i:s', 1)
            );
            //insert the transaction
            $order_id = wp_insert_post($order_post);

            $order_status = ( isset($order_data['wp_dp_total_amount']) && $order_data['wp_dp_total_amount'] > 0 ) ? 'pending' : 'approved';

            if ( ! empty($order_data) ) {
                foreach ( $order_data as $meta_key => $meta_value ) {
                    update_post_meta($order_id, $meta_key, $meta_value);
                }
            }
            update_post_meta($order_id, 'wp_dp_order_status', $order_status);
            update_post_meta($order_id, 'wp_dp_currency', wp_dp_base_currency_sign());
            update_post_meta($order_id, 'wp_dp_currency_obj', wp_dp_get_base_currency());
            update_post_meta($order_id, 'wp_dp_currency_position', wp_dp_get_currency_position());

            return $order_id;
        }

        /*
         * Success Message after completion
         */

        public function success_message() {
            ?>
            <div class="wp-dp-promotions-success">
                <h3><?php echo wp_dp_plugin_text_srt('wp_dp_promotion_success_msg'); ?></h3>
            </div>
            <?php
        }

        /*
         * Showing active promotions on listings in dashboard
         */

        public function wp_dp_listings_caption_area_callback($listing_id) {
            $promotions = get_post_meta($listing_id, 'wp_dp_promotions', true);
            $already_purchased = false;
            $title = array();
            if ( ! empty($promotions) ) {
                foreach ( $promotions as $key => $promotion ) {
                    $expiry_date = isset($promotion['expiry']) ? $promotion['expiry'] : '';
                    if ( $expiry_date >= date('Y-m-d') || $expiry_date == 'unlimitted' ) {
                        $already_purchased = true;
                        $background_color   = isset($promotion['background']) ? $promotion['background'] : '#1e73be';
                        $style = ' style="border-color: ' . $background_color . ';"';
                        $title[] = '<span' . $style . '>' . $promotion['title'] . '</span>';
                    }
                }
            }
            if ( $already_purchased == true ) {
                ?>
                <div class="wp-dp-active-promotions">
                    <label><?php echo wp_dp_plugin_text_srt('wp_dp_promotions_active_promotions'); ?>:</label> <?php echo implode( '', $title ); ?>
                </div>
                <?php
            }
        }

    }

    global $wp_dp_promotions_frontend;
    $wp_dp_promotions_frontend = new Wp_dp_Promotions_Frontend();
}