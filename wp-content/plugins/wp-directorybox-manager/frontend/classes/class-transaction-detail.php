<?php
/**
 * File Type: Transaction Detail
 */
if ( ! class_exists('Wp_Dp_Transaction_Detail') ) {

    class Wp_Dp_Transaction_Detail {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_enqueue_scripts', array( $this, 'wp_dp_order_element_scripts' ));
            add_action('wp_dp_transaction_detail', array( $this, 'wp_dp_transaction_detail_callback' ), 11, 1);
        }

        public function wp_dp_order_element_scripts() {
            wp_enqueue_script('jquery-mCustomScrollbar');
            wp_enqueue_style('jquery-mCustomScrollbar');
            wp_enqueue_script('jquery-print');
        }

        public function wp_dp_transaction_detail_callback($transaction_id = '') {
            global $post, $wp_dp_cs_var_options;
            $wp_dp_custom_logo = isset($wp_dp_cs_var_options['wp_dp_cs_var_custom_logo']) ? $wp_dp_cs_var_options['wp_dp_cs_var_custom_logo'] : '';
            if ( $wp_dp_custom_logo != '' ) {
                $logo = $wp_dp_custom_logo;
            } else {
                $logo = esc_url(wp_dp::plugin_url()) . '/assets/frontend/images/logo-classic.png';
            }

            if ( $transaction_id == '' ) {
                $transaction_id = $post->ID;
            }

            $args = array(
                'post_type' => 'wp-dp-trans',
                'post_status' => 'publish',
                'p' => $transaction_id,
            );
            $order_query = new WP_Query($args);
            while ( $order_query->have_posts() ): $order_query->the_post();

                $transaction_id = get_the_ID();
                $transaction_order_id = get_post_meta($transaction_id, 'wp_dp_transaction_order_id', true);
                $transaction_package_id = get_post_meta($transaction_order_id, 'wp_dp_transaction_package', true);
                $order_type = get_post_meta($transaction_id, 'wp_dp_transaction_order_type', true);
                $transaction_status = get_post_meta($transaction_id, 'wp_dp_transaction_status', true);


                $transaction_statuses = array(
                    'pending' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_status_pending'),
                    'in-process' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_status_in_process'),
                    'approved' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_status_approved'),
                    'cancelled' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_status_cancelled')
                );
                $transaction_status = isset($transaction_statuses[$transaction_status]) ? $transaction_statuses[$transaction_status] : $transaction_status;
                $transaction_status = ( $transaction_status == '' ) ? $transaction_statuses['pending'] : $transaction_status;

                $order_types = array(
                    'package-order' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_packages_order'),
                    'promotion-order' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_promotion_order'),
                );
                $order_type = isset($order_types[$order_type]) ? $order_types[$order_type] : $order_type;

                $transaction_pay_method = get_post_meta($transaction_id, 'wp_dp_transaction_pay_method', true);
                $payment_geteways = $this->wp_dp_payment_gateways();
                $transaction_pay_method = isset($payment_geteways[$transaction_pay_method]) ? $payment_geteways[$transaction_pay_method] : '';
                ?>

                <div class="print-order-detail menu-order-detail order-detail" id="print-transaction-det-<?php echo wp_dp_cs_allow_special_char($transaction_id); ?>" style="display: none;">
                    <div class="logo"><img src="<?php echo esc_url($logo); ?>" alt="<?php esc_html(bloginfo('name')) ?>" /></div>
                    <h2> <?php esc_html_e('Order', 'wp-dp') ?> # <?php echo wp_dp_cs_allow_special_char($transaction_id); ?></h2>
                    <div class="order-detail-inner">
                        <div class="description-holder">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> <h2 class="heading"><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_customer_detail'); ?></h2></div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="list-detail-options has-checkbox">
                                         
                                        <ul class="order-detail-options">
                                            <li class="created-date">
                                                <strong><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_order_posted') ?></strong>
                                                <span><?php echo get_the_time(get_option('date_format'), $transaction_id); ?></span>
                                            </li>
                                            <?php if ( $order_type != '' ) { ?>
                                                <li class="order-type">
                                                    <strong><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_order_type') ?></strong>
                                                    <span><?php echo esc_html($order_type); ?></span>
                                                </li>
                                            <?php } ?>
                                            <?php if ( $transaction_pay_method ) { ?>
                                                <li class="order-type">
                                                    <strong><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_order_method') ?></strong>
                                                    <span><?php echo esc_html($transaction_pay_method); ?></span>
                                                </li>
                                            <?php } ?>
                                            <li class="order-type">
                                                <strong><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_payment_status') ?></strong>
                                                <span><?php echo esc_html($transaction_status); ?></span>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                                <?php
                                // transaction user info.
                                $this->transaction_user_info($transaction_id);
                                // Order menu list.
                                $this->order_package_detail($transaction_id);
                                // Order price.
                                $this->order_price($transaction_id);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade menu-order-detail order-detail" id="transaction-det-<?php echo intval($transaction_id); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_close') ?><span aria-hidden="true">&times;</span></button>
                                <h2> <?php echo wp_dp_plugin_text_srt('wp_dp_transaction_order') ?> # <?php echo wp_dp_cs_allow_special_char($transaction_id); ?></h2>
                                <button class="btn-print" onclick="CallPrint('print-transaction-det-<?php echo ($transaction_id); ?>');"><i class="icon-dowload"></i><span><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_print_invoice') ?></span></button>
                                <script type="text/javascript">
                                    function CallPrint(divName) {
										var title = "<?php echo wp_dp_plugin_text_srt('wp_dp_transaction_detail') ?>";
                                        var stylesheet_url = "<?php echo esc_url(wp_dp::plugin_url()) . 'assets/frontend/css/cm-print.css'; ?>";
                                        jQuery('#' + divName).show();
                                        jQuery("#" + divName).print({
                                            stylesheet: stylesheet_url,
                                            title: title,
                                        });
                                        jQuery('#' + divName).hide();
                                    }
                                </script>

                            </div>
                            <div class="modal-body">
                                <div class="order-detail-inner">
                                    <div class="description-holder">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> <h2 class="heading"><?php echo esc_html__('Customer Detail', 'wp-dp'); ?></h2></div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="list-detail-options has-checkbox">
                                                    <ul class="order-detail-options">
                                                        <li class="created-date">
                                                            <strong><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_order_posted') ?></strong>
                                                            <span><?php echo get_the_time(get_option('date_format'), $transaction_id); ?></span>
                                                        </li>
                                                        <?php if ( $order_type != '' ) { ?>
                                                            <li class="order-type">
                                                                <strong><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_order_type') ?></strong>
                                                                <span><?php echo esc_html($order_type); ?></span>
                                                            </li>
                                                        <?php } ?>
                                                        <?php if ( $transaction_pay_method ) { ?>
                                                            <li class="order-type">
                                                                <strong><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_order_method') ?></strong>
                                                                <span><?php echo esc_html($transaction_pay_method); ?></span>
                                                            </li>
                                                        <?php } ?>
                                                        <li class="order-type">
                                                            <strong><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_payment_status') ?></strong>
                                                            <span><?php echo esc_html($transaction_status); ?></span>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </div>
                                            <?php
                                            // transactio user info.
                                            $this->transaction_user_info($transaction_id);
                                            // Order menu list.
                                            $this->order_package_detail($transaction_id);
                                            // Order price.
                                            $this->order_price($transaction_id);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            endwhile;
            wp_reset_postdata();
            ?>
            <script>
                (function ($) {
                    $(document).ready(function () {
                        $(".order-detail .modal-dialog .modal-content").mCustomScrollbar({
                            setHeight: 724,
                            theme: "minimal-dark",
                            mouseWheelPixels: 100
                        });
                    });
                })(jQuery);
            </script>
            <?php
        }

        public function transaction_user_info($transaction_id = '') {
            global $post;

            if ( $transaction_id == '' ) {
                $transaction_id = $post->ID;
            }

            $transaction_order_id = get_post_meta($transaction_id, 'wp_dp_transaction_order_id', true);
            if ( $transaction_order_id != '' ) {
                $trans_first_name = get_post_meta($transaction_order_id, 'wp_dp_trans_first_name', true);
                $trans_last_name = get_post_meta($transaction_order_id, 'wp_dp_trans_last_name', true);
                $trans_email = get_post_meta($transaction_order_id, 'wp_dp_trans_email', true);
                $trans_phone_number = get_post_meta($transaction_order_id, 'wp_dp_trans_phone_number', true);
                $trans_address = get_post_meta($transaction_order_id, 'wp_dp_trans_address', true);
                $transaction_member_id = get_post_meta($transaction_order_id, 'wp_dp_transaction_user', true);
                $member_phone_num = get_post_meta($transaction_member_id, 'wp_dp_phone_number', true);
                $member_email_address = get_post_meta($transaction_member_id, 'wp_dp_email_address', true);
                $member_address = get_post_meta($transaction_member_id, 'wp_dp_post_loc_address_member', true);
                if ( $transaction_member_id != '' || $member_phone_num != '' || $member_email_address != '' || $member_address != '' ) {
                    ?>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="customer-detail-holder">
<!--                            <h3><?php //esc_html_e('Customer Detail', 'wp-dp'); ?></h3>-->
                            <ul class="customer-detail">
                                <?php if ( $transaction_member_id != '' || $transaction_member_id ) { ?>
                                    <li>
                                        <strong><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_name') ?></strong>
                                        <span><?php echo esc_html(get_the_title($transaction_member_id)); ?></span>
                                    </li>
                                <?php } ?>
                                <?php if ( '' != $member_phone_num ) { ?>
                                    <li>
                                        <strong><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_phone') ?></strong>
                                        <span><?php echo esc_html($member_phone_num); ?></span>
                                    </li>
                                <?php } ?>
                                <?php if ( '' != $member_email_address ) { ?>
                                    <li>
                                        <strong><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_email') ?></strong>
                                        <span><?php echo esc_html($member_email_address); ?></span>
                                    </li>
                                <?php } ?>
                                <?php if ( '' != $member_address ) { ?>
                                    <li>
                                        <strong><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_address') ?></strong>
                                        <span><?php echo esc_html($member_address); ?></span>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <?php
                }
            }
        }

        public function order_package_detail($transaction_id = '') {
            global $post;

            if ( $transaction_id == '' ) {
                $transaction_id = $post->ID;
            }
            $transaction_order_id = get_post_meta($transaction_id, 'wp_dp_transaction_order_id', true);
            $transaction_package_id = get_post_meta($transaction_order_id, 'wp_dp_transaction_package', true);
            $transaction_origional_price = get_post_meta($transaction_order_id, 'wp_dp_transaction_origional_price', true);
            ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h2 class="heading"><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_summary') ?></h2>
                <ul class="categories-order table-generic">
                    <li class="order-heading-titles">
                        <div><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_ref_no') ?></div>
                        <div><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_price') ?></div>
                    </li>
                    <li class="order-heading-titles">
                        <div><h4><?php echo esc_html(get_the_title($transaction_package_id)); ?></h4></div>
                        <div><span class="category-price"><?php echo wp_dp_get_currency($transaction_origional_price, true); ?></span></div>
                    </li>
                </ul>
            </div>
            <?php
        }

        public function order_price($transaction_id = '') {
            global $post;

            if ( $transaction_id == '' ) {
                $transaction_id = $post->ID;
            }
            $transaction_order_id = get_post_meta($transaction_id, 'wp_dp_transaction_order_id', true);
            $transaction_amount = get_post_meta($transaction_id, 'wp_dp_transaction_amount', true);
            $transaction_origional_price = get_post_meta($transaction_order_id, 'wp_dp_transaction_origional_price', true);
            $transaction_vat_price = get_post_meta($transaction_order_id, 'wp_dp_transaction_vat_price', true);
            $transaction_vat_tax = get_post_meta($transaction_order_id, 'wp_dp_transaction_vat_tax', true);

            if ( $transaction_origional_price != '' || $transaction_vat_price != '' || $transaction_amount != '' ) {
                ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <ul class="order-detail-options order-total">
                                <?php if ( $transaction_origional_price != '' ) { ?>
                                    <li class="created-date">
                                        <strong><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_subtotal') ?></strong>
                                        <span><?php echo wp_dp_get_currency($transaction_origional_price, true); ?></span>
                                    </li>
                                <?php } ?>

                                <?php if ( $transaction_vat_price != '' ) { ?>
                                    <li class="order-type">
                                        <strong><?php printf(esc_html__('VAT (%s&#37;)', 'wp-dp'), $transaction_vat_tax) ?></strong>
                                        <span><?php echo wp_dp_get_currency($transaction_vat_price, true); ?></span>
                                    </li>
                                <?php } ?>

                                <?php if ( $transaction_amount != '' ) { ?>
                                    <li class="order-type total-price">
                                        <strong><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_total') ?></strong>
                                        <span><?php echo wp_dp_get_currency($transaction_amount, true); ?></span>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php
            }
        }

        public function wp_dp_payment_gateways() {
            global $gateways;
            $object = new WP_DP_PAYMENTS();
            $payment_geteways = array();
            $wp_dp_gateway_options = get_option('wp_dp_plugin_options');
            $wp_dp_gateway_options = apply_filters('wp_dp_translate_options', $wp_dp_gateway_options);

            foreach ( $gateways as $key => $value ) {
                $status = isset( $wp_dp_gateway_options[strtolower($key) . '_status'] )? $wp_dp_gateway_options[strtolower($key) . '_status'] : '';
                if ( isset($status) && $status == 'on' ) {
                    $payment_geteways[$key] = $value;
                }
            }

            if ( isset($wp_dp_gateway_options['wp_dp_use_woocommerce_gateway']) && $wp_dp_gateway_options['wp_dp_use_woocommerce_gateway'] == 'on' ) {
                if ( class_exists('WooCommerce') ) {
                    unset($payment_geteways);
                    $gateways = WC()->payment_gateways->get_available_payment_gateways();
                    foreach ( $gateways as $key => $gateway_data ) {
                        $payment_geteways[$key] = $gateway_data->method_title;
                    }
                }
            }

            return $payment_geteways;
        }

    }

    global $wp_dp_transaction_detail;
    $wp_dp_transaction_detail = new Wp_Dp_Transaction_Detail();
}
