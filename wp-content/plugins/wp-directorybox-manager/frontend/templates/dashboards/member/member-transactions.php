<?php
/**
 * Member Transactions
 *
 */
if ( ! class_exists('Wp_dp_Member_Transactions') ) {

    class Wp_dp_Member_Transactions {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_ajax_wp_dp_member_transactions', array( $this, 'wp_dp_member_transactions_callback' ));
        }

        public function wp_dp_member_transactions_callback() {
            global $wp_dp_plugin_options, $current_user;

            $pagi_per_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard_pagination']) ? $wp_dp_plugin_options['wp_dp_member_dashboard_pagination'] : '';
            $posts_per_page = $pagi_per_page > 0 ? $pagi_per_page : 1;
            $posts_paged = isset($_REQUEST['page_id_all']) ? $_REQUEST['page_id_all'] : '';

            $member_id = wp_dp_company_id_form_user_id($current_user->ID);
            $args = array(
                'posts_per_page' => $posts_per_page,
                'paged' => $posts_paged,
                'post_type' => 'wp-dp-trans',
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_transaction_user',
                        'value' => $member_id,
                        'compare' => '=',
                    )
                )
            );

            $transaction_query = new WP_Query($args);
            $total_posts = $transaction_query->found_posts;
            ?>
            <ul class="breadcrumbs">
                <li class="user_dashboard_ajax" id="wp_dp_member_suggested" data-queryvar="dashboard=suggested"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_Dashboard'); ?></a></li>
                <li class="active"><?php echo wp_dp_plugin_text_srt('wp_dp_transactions_invoices'); ?></li>
            </ul>
            <div class="user-dashboard-background">
                <div class="element-title has-border right-filters-row transactions-title">
                    <h4><?php echo wp_dp_plugin_text_srt('wp_dp_transactions_invoices'); ?></h4>
                </div>
                <?php if ( $transaction_query->have_posts() ) { ?>
                    <div class="responsive-table transactions-list">
                        <ul class="table-generic">
                            <li class="transaction-heading-titles">
                                <div class="trans-ref"><span><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_ref_no'); ?></span></div>	
                                <div class="payment-method"><span><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_payment_method'); ?></span></div>
                                <div class="date-issued"><span><?php echo wp_dp_plugin_text_srt('wp_dp_transaction_date_issued'); ?></span></div>
                                <div class="trans-payment"><span><?php echo wp_dp_plugin_text_srt('wp_dp_package_amount'); ?></span></div>
                                <div class="trans-status"><span><?php echo wp_dp_plugin_text_srt('wp_dp_listing_status'); ?></span></div>
                                <div class="trans-actions"></div>
                            </li>
                            <?php
                            while ( $transaction_query->have_posts() ) : $transaction_query->the_post();
                                $transaction_id = get_the_ID();
                                $transaction_pay_method = get_post_meta($transaction_id, 'wp_dp_transaction_pay_method', true);
                                $transaction_amount = get_post_meta($transaction_id, 'wp_dp_transaction_amount', true);
                                $transaction_status = get_post_meta($transaction_id, 'wp_dp_transaction_status', true);

                                // Package Detail
                                $transaction_order_id = get_post_meta($transaction_id, 'wp_dp_transaction_order_id', true);
                                $transaction_package_name = get_post_meta($transaction_order_id, 'wp_dp_transaction_package', true);
                                $payment_geteways = $this->wp_dp_payment_gateways();
                                $transaction_statuses = array(
                                    'pending' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_status_pending'),
                                    'in-process' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_status_in_process'),
                                    'approved' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_status_approved'),
                                    'cancelled' => wp_dp_plugin_text_srt('wp_dp_transaction_meta_status_cancelled')
                                );
                                ?>
                                <li class="transaction-content-titles">
                                    <div class="trans-ref"><span><?php the_title(); ?></span></div>
                                    <div class="payment-method">
                                        
                                        <span>
                                            <?php
                                            if ( $transaction_pay_method == 'WP_DP_PAYPAL_GATEWAY' ) {
                                                $wp_dp_payment_logo_id = isset($wp_dp_plugin_options['wp_dp_paypal_gateway_logo']) ? $wp_dp_plugin_options['wp_dp_paypal_gateway_logo'] : '';
                                            } elseif ( $transaction_pay_method == 'WP_DP_AUTHORIZEDOTNET_GATEWAY' ) {
                                                $wp_dp_payment_logo_id = isset($wp_dp_plugin_options['wp_dp_authorizedotnet_gateway_logo']) ? $wp_dp_plugin_options['wp_dp_authorizedotnet_gateway_logo'] : '';
                                            } elseif ( $transaction_pay_method == 'WP_DP_PRE_BANK_TRANSFER' ) {
                                                $wp_dp_payment_logo_id = isset($wp_dp_plugin_options['wp_dp_pre_bank_transfer_logo']) ? $wp_dp_plugin_options['wp_dp_pre_bank_transfer_logo'] : '';
                                            } elseif ( $transaction_pay_method == 'WP_DP_SKRILL_GATEWAY' ) {
                                                $wp_dp_payment_logo_id = isset($wp_dp_plugin_options['wp_dp_skrill_gateway_logo']) ? $wp_dp_plugin_options['wp_dp_skrill_gateway_logo'] : '';
                                            }
                                            $image_url = array();
                                            if( isset( $wp_dp_payment_logo_id )){
                                                $image_url = wp_get_attachment_image_src($wp_dp_payment_logo_id);
                                            }
                                            if ( isset($image_url[0]) && ! empty($image_url[0]) ) {
                                                echo '<img src="' . esc_url($image_url[0]) . '" alt=""" />';
                                            } else {
                                                echo esc_html(isset($payment_geteways[$transaction_pay_method]) ? $payment_geteways[$transaction_pay_method] : '' );
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <div class="date-issued"><span><?php echo get_the_date(get_option('date_format')); ?></span></div>
                                    <div class="trans-payment"><span class="amount"><?php echo wp_dp_get_currency($transaction_amount, true); ?></span></div>
                                    <div class="trans-status"><span><?php echo esc_html(isset($transaction_statuses[$transaction_status]) ? $transaction_statuses[$transaction_status] : wp_dp_plugin_text_srt('wp_dp_transaction_meta_status_pending') ); ?></span></div>
                                    <div class="trans-actions">
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#transaction-det-<?php echo wp_dp_cs_allow_special_char($transaction_id); ?>"><i class="icon-plus"></i><span><?php echo esc_html__('Invoice Detail', 'wp-dp'); ?></span></a>
                                    </div>
                                    <?php do_action('wp_dp_transaction_detail', $transaction_id); ?>
                                </li>
                                
                                <?php
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </ul>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="no-listing-found">
                        <i class="icon-caution"></i>&nbsp;&nbsp;<?php esc_html_e('No transactions found.', 'foodbakery'); ?>
                    </div>
                <?php }
                ?> </div>
            <?php
            wp_reset_postdata();
            $total_pages = 1;
            if ( $total_posts > 0 && $posts_per_page > 0 && $total_posts > $posts_per_page ) {
                $total_pages = ceil($total_posts / $posts_per_page);
                $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
                $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';
                $this_url = $wp_dp_dashboard_link != '' ? add_query_arg(array( 'dashboard' => 'transactions' ), $wp_dp_dashboard_link) : '';
                wp_dp_dashboard_pagination($total_pages, $posts_paged, $this_url, 'transactions');
            }
            wp_die();
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

    global $member_transactions;
    $member_transactions = new Wp_dp_Member_Transactions();
}
