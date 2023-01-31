<?php
/**
 * File Type: Enquiry Detail
 */
if ( ! class_exists('Wp_dp_Enquiry_Detail') ) {

    class Wp_dp_Enquiry_Detail {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_ajax_wp_dp_enquiry_detail', array( $this, 'wp_dp_enquiry_detail_callback' ), 11, 1);
        }

        public function wp_dp_enquiry_detail_callback() {
            global $wp_dp_form_fields, $wp_dp_plugin_options;

            $enquiry_id = wp_dp_get_input('enquiry_id', 0);
            $type = wp_dp_get_input('type', 0);

            $args = array(
                'post_type' => 'listing_enquiries',
                'post_status' => 'publish',
                'p' => $enquiry_id,
            );
            $order_query = new WP_Query($args);
            while ( $order_query->have_posts() ): $order_query->the_post();

                $post_id = get_the_ID();
                $wp_dp_listing_id = get_post_meta($post_id, 'wp_dp_listing_id', true);


                $listing_member = get_post_meta($post_id, 'wp_dp_listing_member', true);
                $enquiry_member = get_post_meta($post_id, 'wp_dp_enquiry_member', true);
                $user_id = get_current_user_id();
                $current_login_member = wp_dp_company_id_form_user_id($user_id);

                $user_status = '';
                if ( $listing_member == $current_login_member ) {
                    update_post_meta($post_id, 'seller_read_status', '1');
                    $user_status = 'seller';
                } else if ( $enquiry_member == $current_login_member ) {
                    update_post_meta($post_id, 'buyer_read_status', '1');
                    $user_status = 'buyer';
                }
                ?>
                <div class="list-detail-options has-checkbox">
                    <?php
                    if ( $type == 'received' ) {
                        $enquiries_id = 'wp_dp_member_received_enquiries';
                        $data_param = 'data-param="' . $wp_dp_listing_id . '"';
                    } else {
                        $enquiries_id = 'wp_dp_member_enquiries';
                        $data_param = '';
                    }
                    ?>
                    <ul class="breadcrumbs">
                        <?php if ( $type != 'received' ) { ?>
                            <li><a id="wp_dp_member_enquiries" class="user_dashboard_ajax" href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_enquiry_detail_enquires'); ?></a></li>
                        <?php } ?>
                        <?php if ( $type != 'my' ) { ?>
                            <li><a id="<?php echo esc_html($enquiries_id); ?>" <?php echo wp_dp_cs_allow_special_char($data_param); ?> class="user_dashboard_ajax" href="javascript:void(0);"><?php echo get_the_title($wp_dp_listing_id); ?></a></li>
                        <?php } ?>
                        <li class="active"><?php echo wp_dp_plugin_text_srt('wp_dp_enquiry_detail_enquiry'); ?> #<?php echo absint($post_id); ?></li>
                    </ul>

                    <div class="order-read-unread">
                        <div class="input-field">
                            <?php
                            $read = wp_dp_plugin_text_srt('wp_dp_enquiry_detail_mark_enquiry_read');
                            $unread = wp_dp_plugin_text_srt('wp_dp_enquiry_detail_mark_enquiry_unread');

                            if ( $user_status == 'seller' ) {
                                $status_value = get_post_meta($post_id, 'seller_read_status', true);
                            } else {
                                $status_value = get_post_meta($post_id, 'buyer_read_status', true);
                            }
                            if ( $status_value == 1 ) {
                                $span_text = wp_dp_plugin_text_srt('wp_dp_enquiry_detail_mark_enquiry_unread');
                            } else {
                                $span_text = wp_dp_plugin_text_srt('wp_dp_enquiry_detail_mark_enquiry_read');
                            }
                            ?>
                            <div class="checkbox-list enquiry-read-checkbox" data-read="<?php echo esc_html($read); ?>" data-unread="<?php echo esc_html($unread); ?>">
                                <input type="hidden" id="enquiry_id" value="<?php echo absint($post_id); ?>">
                                <input type="hidden" id="user_status" value="<?php echo esc_html($user_status); ?>">
                                <input type="checkbox" id="enquiry_read_status" class="read_status" name="read_status" checked="checked" value="1">
                                <a id="unread-read-enquiry" href="javascript:void(0)"><span><?php echo esc_html($span_text); ?></span></a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                // Enquiry form field.
                $this->enquiry_form_fields($post_id);
                // Enquiry discussion list & form.
                $this->enquiry_discussion_ui($post_id);
            endwhile;
            wp_reset_postdata();
            wp_die();
        }

        public function enquiry_buyer_info($post_id = '') {
            global $post;

            if ( $post_id == '' ) {
                $post_id = $post->ID;
            }

            $wp_dp_enquiry_user = get_post_meta($post_id, 'wp_dp_enquiry_user', true);
            if ( $wp_dp_enquiry_user ) {
                ?>
                <div class="customer-detail-holder">
                    <strong class="heading"><?php echo wp_dp_plugin_text_srt('wp_dp_enquiry_detail_custom_detail'); ?></strong>
                    <?php if ( $wp_dp_enquiry_user != '' ) { ?>
                        <ul class="customer-detail">
                            <?php
                            $user_data = get_userdata($wp_dp_enquiry_user);
                            $company_id = wp_dp_company_id_form_user_id($user_data->ID);
                            $phone_number = get_post_meta($company_id, 'wp_dp_phone_number', true);
                            $email_address = get_post_meta($company_id, 'wp_dp_email_address', true);
                            $address = get_post_meta($company_id, 'wp_dp_post_loc_address_member', true);
                            ?>
                            <li>
                                <strong><?php echo wp_dp_plugin_text_srt('wp_dp_enquiry_detail_custom_name'); ?></strong>
                                <span><?php echo esc_html(get_the_title($company_id)); ?></span>
                            </li>
                            <?php if ( '' != $phone_number ) { ?>
                                <li>
                                    <strong><?php echo wp_dp_plugin_text_srt('wp_dp_enquiry_detail_custom_phone_num') ?></strong>
                                    <span><a href="tel:<?php echo esc_html($phone_number); ?>"><?php echo esc_html($phone_number); ?></a></span>
                                </li>
                            <?php } ?>
                            <?php if ( '' != $email_address ) { ?>
                                <li>
                                    <strong><?php echo wp_dp_plugin_text_srt('wp_dp_enquiry_detail_custom_email'); ?></strong>
                                    <span><?php echo esc_html($email_address); ?></span>
                                </li>
                            <?php } ?>
                            <?php if ( '' != $address ) { ?>
                                <li>
                                    <strong><?php echo wp_dp_plugin_text_srt('wp_dp_enquiry_detail_custom_address'); ?></strong>
                                    <span><?php echo esc_html($address); ?></span>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
                <?php
            }
        }

        public function enquiry_form_fields($post_id = '') {
            global $post;

            if ( $post_id == '' ) {
                $post_id = $post->ID;
            }
            $user_name = get_post_meta($post_id, 'wp_dp_user_name', true);
            $phone_number = get_post_meta($post_id, 'wp_dp_phone_number', true);
            $user_email = get_post_meta($post_id, 'wp_dp_user_email', true);
            $user_message = get_post_meta($post_id, 'wp_dp_user_message', true);

            if ( $user_name || $phone_number || $user_email || $user_message ) {
                ?>
                <div class="description-holder">
                    <div class="order-detail-holder">
                        <strong class="heading">
                            <?php echo wp_dp_plugin_text_srt('wp_dp_enquiry_detail_inquiry_detail'); ?>
                        </strong> 
                        <ul class="order-detail">
                            <li class="created-date">
                                <strong><?php echo wp_dp_plugin_text_srt('wp_dp_enquiry_detail_created'); ?></strong>
                                <span><?php echo get_the_time('d M Y', $post_id); ?></span>
                            </li>
                            <?php if ( $user_name ) { ?>
                                <li>
                                    <strong><?php echo wp_dp_plugin_text_srt('wp_dp_enquiry_detail_name'); ?></strong>
                                    <span><?php echo esc_html($user_name); ?></span>
                                </li>
                            <?php } ?>

                        </ul>
                    </div>
                </div>
                <?php if ( $user_message ) { ?>
                    <div class="order-detail-message"> 
                        <span><?php echo esc_html($user_message); ?></span>
                    </div>
                <?php } ?>
                <?php
            }
        }

        public function enquiry_status_ui($post_id = '') {
            global $post, $wp_dp_plugin_options, $wp_dp_form_fields;
            if ( $post_id == '' ) {
                $post_id = $post->ID;
            }

            $listing_member = get_post_meta($post_id, 'wp_dp_listing_member', true);
            $enquiry_member = get_post_meta($post_id, 'wp_dp_enquiry_member', true);
            $user_id = get_current_user_id();
            $current_login_member = wp_dp_company_id_form_user_id($user_id);

            $enquiry_status = get_post_meta($post_id, 'wp_dp_enquiry_status', true);
            $order_type = get_post_meta($post_id, 'wp_dp_order_type', true);
            ?>
            <div class="order-status-read-unread-holder">
                <?php if ( $listing_member == $current_login_member ) { ?>
                    <strong class="heading">
                        <?php echo wp_dp_plugin_text_srt('wp_dp_enquiry_detail_enquiry_status'); ?>
                    </strong>
                    <div class="input-field">
                        <?php
                        $orders_status = isset($wp_dp_plugin_options['orders_status']) ? $wp_dp_plugin_options['orders_status'] : '';
                        if ( is_array($orders_status) && sizeof($orders_status) > 0 ) {
                            foreach ( $orders_status as $key => $label ) {
                                $drop_down_options[$label] = $label;
                            }
                        } else {
                            $drop_down_options = array(
                                'Processing' => wp_dp_plugin_text_srt('wp_dp_enquiry_detail_procceing'),
                                'Completed' => wp_dp_plugin_text_srt('wp_dp_enquiry_detail_completed'),
                            );
                        }
                        if ( $enquiry_status == 'Closed' ) {
                            $drop_down_options['Closed'] = wp_dp_plugin_text_srt('wp_dp_enquiry_detail_closed');
                        }
                        $wp_dp_opt_array = array();
                        $wp_dp_opt_array['std'] = $enquiry_status;
                        $wp_dp_opt_array['cust_id'] = 'enquiry-status';
                        $wp_dp_opt_array['cust_name'] = 'enquiry-status';
                        $wp_dp_opt_array['options'] = $drop_down_options;
                        $wp_dp_opt_array['classes'] = 'chosen-select-no-single';
                        $wp_dp_opt_array['extra_atr'] = ' onchange="wp_dp_update_enquiry_status(this, \'' . $post_id . '\', \'' . admin_url('admin-ajax.php') . '\')"';
                        $wp_dp_opt_array['return'] = false;

                        $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);
                        ?>
                    </div>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            chosen_selectionbox();
                        });
                    </script>
                <?php } else if ( $enquiry_member == $current_login_member && $enquiry_status == 'Completed' ) { ?>
                    <div class="order-status-process enquiry-status">
                        <p><?php echo wp_dp_plugin_text_srt('wp_dp_enquiry_detail_ur_enquiry_completed') ?> <a href="javascript:void(0);" onclick="wp_dp_closed_enquiry('<?php echo esc_html($post_id); ?>');"><?php echo wp_dp_plugin_text_srt('wp_dp_enquiry_detail_closed_enquiry'); ?></a></p>
                    </div>
                <?php } else if ( $enquiry_member == $current_login_member ) { ?>
                    <div class="order-status-process enquiry-status">
                        <p><?php echo wp_dp_plugin_text_srt('wp_dp_enquiry_detail_ur_enquiry_is') . $enquiry_status; ?></p>
                    </div>
                <?php } ?>
            </div>
            <?php
        }

        public function enquiry_discussion_ui($post_id = '') {
            global $post;

            if ( $post_id == '' ) {
                $post_id = $post->ID;
            }

            $user_id = get_current_user_id();
            $member_id = wp_dp_company_id_form_user_id($user_id);
            $enquiry_status = get_post_meta($post_id, 'wp_dp_enquiry_status', true);
            ?>
            <div class="discussions-list-form-holder">
                <?php do_action('wp_dp_discussion_list', $post_id); ?>
                <?php if ( $enquiry_status != 'Closed' ) { ?>
                    <div class="contact-form">
                        <h3 class="comment-reply-title" id="reply-title"><div class="element-title">
                                <h3><?php echo wp_dp_plugin_text_srt('wp_dp_enquiry_detail_post_ur_msg'); ?></h3>
                            </div>
                        </h3>				
                        <form class="contact-form row" id="discussion-form" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="input-holder">
                                        <textarea class="wp-dp-required-field" placeholder="<?php echo wp_dp_plugin_text_srt('wp_dp_enquiry_detail_message_here'); ?>" name="message" id="comment_message"></textarea>
                                    </div>
                                </div>
                                <div id="response"></div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="input-btn">
                                        <button type="button" class="bgcolor discussion-submit" onclick="javascript:wp_dp_discussion_submit('<?php echo admin_url('admin-ajax.php'); ?>')"><?php echo wp_dp_plugin_text_srt('wp_dp_enquiry_detail_send_msg'); ?></button> 
                                    </div>
                                </div> 
                                <input type="hidden" id="comment_post_ID" value="<?php echo absint($post_id); ?>" name="comment_post_ID">
                                <input type="hidden" value="0" id="comment_parent" name="comment_parent">
                                <input type="hidden" value="<?php echo absint($member_id); ?>" id="comment_member" name="comment_member">
                            </div>
                        </form>
                    </div>
                <?php } ?>
            </div>
            <?php
        }

    }

    global $wp_dp_order_detail;
    $wp_dp_order_detail = new Wp_dp_Enquiry_Detail();
}