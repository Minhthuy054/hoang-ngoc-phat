<?php
/**
 * File Type: Enquire Arrange Buttons Page Element
 */
if ( ! class_exists('wp_dp_enquire_arrange_button_element') ) {

    class wp_dp_enquire_arrange_button_element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_dp_enquire_arrange_buttons_element_html', array( $this, 'wp_dp_enquire_arrange_buttons_element_html_callback' ), 11, 2);
            add_action('wp_dp_enquiry_agent_contact_form_html', array( $this, 'wp_dp_enquiry_agent_contact_form_html_callback' ), 11, 2);
            add_action('wp_ajax_nopriv_wp_dp_enquiry_agent_contact_form_submit', array( $this, 'wp_dp_enquiry_agent_contact_form_submit_callback' ), 11, 1);
            add_action('wp_ajax_wp_dp_enquiry_agent_contact_form_submit', array( $this, 'wp_dp_enquiry_agent_contact_form_submit_callback' ), 11, 1);

            add_action('wp_ajax_nopriv_wp_dp_send_enquire_arrange_submit', array( $this, 'wp_dp_send_enquire_arrange_submit_callback' ), 11, 1);
            add_action('wp_ajax_wp_dp_send_enquire_arrange_submit', array( $this, 'wp_dp_send_enquire_arrange_submit_callback' ), 11, 1);

            add_action('wp_ajax_nopriv_wp_dp_send_arrange_submit', array( $this, 'wp_dp_send_arrange_submit_callback' ), 11, 1);
            add_action('wp_ajax_wp_dp_send_arrange_submit', array( $this, 'wp_dp_send_arrange_submit_callback' ), 11, 1);
        }

        public function wp_dp_enquiry_agent_contact_form_submit_callback() {
            global $wp_dp_plugin_options;
            $user_name = wp_dp_get_input('user_name', NULL, 'STRING');
            $user_email = wp_dp_get_input('user_email', NULL, 'STRING');
            $user_message = wp_dp_get_input('user_message', NULL, 'STRING');
            $user_message_title = wp_dp_get_input('user_message_title', NULL, 'STRING');
            $listing_user = wp_dp_get_input('wp_dp_listing_user', 0);
            $listing_member = wp_dp_get_input('wp_dp_listing_member', 0);
            $listing_id = wp_dp_get_input('wp_dp_listing_id', 0);
            $listing_type_id = wp_dp_get_input('wp_dp_listing_type_id', 0);
            $enquiry_user = wp_dp_get_input('wp_dp_enquiry_user', 0);
            $enquiry_member = wp_dp_get_input('wp_dp_enquiry_member', 0);
            if ( $listing_member == $enquiry_member ) {
                $json['type'] = 'error';
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_enquiry_own_listing_error');
                echo json_encode($json);
                exit();
            }

            if ( empty($user_name) ) {
                $json['type'] = 'error';
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_viewing_name_empty');
                echo json_encode($json);
                exit();
            }

            if ( empty($user_message_title) ) {
                $json['type'] = 'error';
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_enquiry_msg_title_empty');
                echo json_encode($json);
                exit();
            }

            if ( empty($user_message) ) {
                $json['type'] = 'error';
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_enquiry_msg_empty');
                echo json_encode($json);
                exit();
            }

            $wp_dp_captcha_switch = isset($wp_dp_plugin_options['wp_dp_captcha_switch']) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';
            if ( $wp_dp_captcha_switch == 'on' ) {
                do_action('wp_dp_verify_captcha_form');
            }

            if ( ! is_user_logged_in() ) {

                $reg_array = array(
                    'username' => $user_email,
                    'display_name' => $user_email,
                    'company_name' => $user_email,
                    'email' => $user_email,
                    'id' => rand(100000, 9000000),
                    'wp_dp_user_role_type' => 'member',
                    'key' => '',
                );
                $member_data = wp_dp_registration_validation('', $reg_array);
                if ( isset($member_data[0]) && isset($member_data[1]) ) {
                    $enquiry_member = $member_data[0];
                    $enquiry_user = $member_data[1];
                }
                ajax_login(array( 'user_login' => $user_email ));
            }
            if ( is_user_logged_in() ) {

                /*
                 * Add inquery in DP logic
                 */

                $enquiry_post = array(
                    'post_title' => wp_strip_all_tags(get_the_title($listing_id)),
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_type' => 'listing_enquiries',
                    'post_date' => current_time('Y/m/d H:i:s', 1)
                );
                //insert Enquiry
                $enquiry_id = wp_insert_post($enquiry_post);
                // Update the post into the database
                $my_post = array(
                    'ID' => $enquiry_id,
                    'post_title' => $user_message_title,
                );
                wp_update_post($my_post);
                $viewing_id = isset($viewing_id) ? $viewing_id : 0;
                update_post_meta($enquiry_id, 'wp_dp_user_name', $user_name);
                update_post_meta($enquiry_id, 'wp_dp_user_email', $user_email);
                update_post_meta($enquiry_id, 'wp_dp_user_message', $user_message);
                update_post_meta($enquiry_id, 'wp_dp_user_message_title', $user_message_title);
                // Save Viewing Listing Fields
                update_post_meta($enquiry_id, 'wp_dp_listing_user', $listing_user);
                update_post_meta($enquiry_id, 'wp_dp_listing_member', $listing_member);
                update_post_meta($enquiry_id, 'wp_dp_listing_id', $listing_id);
                update_post_meta($enquiry_id, 'wp_dp_listing_type_id', $listing_type_id);
                update_post_meta($enquiry_id, 'wp_dp_enquiry_user', $enquiry_user);
                update_post_meta($enquiry_id, 'wp_dp_enquiry_member', $enquiry_member);
                update_post_meta($enquiry_id, 'wp_dp_enquiry_id', $viewing_id);
                update_post_meta($enquiry_id, 'wp_dp_enquiry_status', 'Processing');
                update_post_meta($enquiry_id, 'buyer_read_status', '0');
                update_post_meta($enquiry_id, 'seller_read_status', '0');
                do_action('wp_dp_received_enquiry_email', $_POST);

                $json['type'] = 'success';
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_helper_sent_msg_successfully');

                /* Adding Notification */
                // notification lock at listing author profile
                $member_name = '<a href="' . esc_url(get_the_permalink($enquiry_member)) . '">' . esc_html(get_the_title($enquiry_member)) . '</a>';
                $notification_array = array(
                    'type' => 'enquiry',
                    'element_id' => $listing_id,
                    'message' => force_balance_tags($member_name . ' ' . wp_dp_plugin_text_srt('wp_dp_notification_submitted_enquiry') . ' <a href="' . get_the_permalink($listing_id) . '">' . wp_dp_limit_text(get_the_title($listing_id), 3) . '</a>'),
                );
                do_action('wp_dp_add_notification', $notification_array);
                // notification lock at your profile
                $member_name = '<a href="' . esc_url(get_the_permalink($enquiry_member)) . '">' . esc_html(get_the_title($enquiry_member)) . '</a>';
                $notification_array = array(
                    'type' => 'enquiry',
                    'submitted_type' => 'true',
                    'reciever_id' => '10868',
                    'element_id' => $listing_id,
                    'message' => force_balance_tags(wp_dp_plugin_text_srt('wp_dp_notification_you_has_submitted_enquiry') . ' <a href="' . get_the_permalink($listing_id) . '">' . wp_dp_limit_text(get_the_title($listing_id), 3) . '</a>'),
                );
                do_action('wp_dp_add_notification', $notification_array);
                echo json_encode($json);
                exit();
            } else {
                $json['type'] = 'error';
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_enquire_arrange_login');
                echo json_encode($json);
                exit();
            }
        }

        public function wp_dp_send_enquire_arrange_submit_callback() {
            global $wp_dp_plugin_options;
            if ( is_user_logged_in() ) {

                $user_name = wp_dp_get_input('user_name', NULL, 'STRING');
                $user_phone = wp_dp_get_input('user_phone', NULL, 'STRING');
                $user_email = wp_dp_get_input('user_email', NULL, 'STRING');
                $user_message = wp_dp_get_input('user_message', NULL, 'STRING');
                $user_message_title = wp_dp_get_input('user_message_title', NULL, 'STRING');


                $listing_user = wp_dp_get_input('wp_dp_listing_user', 0);
                $listing_member = wp_dp_get_input('wp_dp_listing_member', 0);
                $listing_id = wp_dp_get_input('wp_dp_listing_id', 0);
                $listing_type_id = wp_dp_get_input('wp_dp_listing_type_id', 0);
                $enquiry_user = wp_dp_get_input('wp_dp_enquiry_user', 0);
                $enquiry_member = wp_dp_get_input('wp_dp_enquiry_member', 0);

                if ( $listing_member == $enquiry_member ) {
                    $json['type'] = 'error';
                    $json['msg'] = wp_dp_plugin_text_srt('wp_dp_enquiry_own_listing_error');
                    echo json_encode($json);
                    exit();
                }

                if ( empty($user_name) ) {
                    $json['type'] = 'error';
                    $json['msg'] = wp_dp_plugin_text_srt('wp_dp_viewing_name_empty');
                    echo json_encode($json);
                    exit();
                }

                if ( empty($user_message_title) ) {
                    $json['type'] = 'error';
                    $json['msg'] = wp_dp_plugin_text_srt('wp_dp_enquiry_msg_title_empty');
                    echo json_encode($json);
                    exit();
                }

                if ( empty($user_message) ) {
                    $json['type'] = 'error';
                    $json['msg'] = wp_dp_plugin_text_srt('wp_dp_enquiry_msg_empty');
                    echo json_encode($json);
                    exit();
                }

                wp_dp_verify_term_condition_form_field('term_policy');

                $wp_dp_captcha_switch = isset($wp_dp_plugin_options['wp_dp_captcha_switch']) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';
                if ( $wp_dp_captcha_switch == 'on' ) {
                    do_action('wp_dp_verify_captcha_form');
                }
                /*
                 * Add inquery in DP logic
                 */

                $enquiry_post = array(
                    'post_title' => wp_strip_all_tags(get_the_title($listing_id)),
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_type' => 'listing_enquiries',
                    'post_date' => current_time('Y/m/d H:i:s', 1)
                );
                //insert Enquiry
                $enquiry_id = wp_insert_post($enquiry_post);
                // Update the post into the database
                $my_post = array(
                    'ID' => $enquiry_id,
                    'post_title' => $user_message_title,
                );
                wp_update_post($my_post);

                update_post_meta($enquiry_id, 'wp_dp_user_name', $user_name);
                update_post_meta($enquiry_id, 'wp_dp_phone_number', $user_phone);
                update_post_meta($enquiry_id, 'wp_dp_user_email', $user_email);
                update_post_meta($enquiry_id, 'wp_dp_user_message', $user_message);
                update_post_meta($enquiry_id, 'wp_dp_user_message_title', $user_message_title);
                // Save Viewing Listing Fields
                update_post_meta($enquiry_id, 'wp_dp_listing_user', $listing_user);
                update_post_meta($enquiry_id, 'wp_dp_listing_member', $listing_member);
                update_post_meta($enquiry_id, 'wp_dp_listing_id', $listing_id);
                update_post_meta($enquiry_id, 'wp_dp_listing_type_id', $listing_type_id);
                update_post_meta($enquiry_id, 'wp_dp_enquiry_user', $enquiry_user);
                update_post_meta($enquiry_id, 'wp_dp_enquiry_member', $enquiry_member);

                update_post_meta($enquiry_id, 'wp_dp_enquiry_id', $viewing_id);
                update_post_meta($enquiry_id, 'wp_dp_enquiry_status', 'Processing');
                update_post_meta($enquiry_id, 'buyer_read_status', '0');
                update_post_meta($enquiry_id, 'seller_read_status', '0');

                do_action('wp_dp_received_enquiry_email', $_POST);

                $json['type'] = 'success';
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_enquiry_sent_successfully');

                /* Adding Notification */
                // notification lock at listing author profile
                $member_name = '<a href="' . esc_url(get_the_permalink($enquiry_member)) . '">' . esc_html(get_the_title($enquiry_member)) . '</a>';
                $notification_array = array(
                    'type' => 'enquiry',
                    'element_id' => $listing_id,
                    'message' => force_balance_tags($member_name . ' ' . wp_dp_plugin_text_srt('wp_dp_notification_submitted_enquiry') . ' <a href="' . get_the_permalink($listing_id) . '">' . wp_dp_limit_text(get_the_title($listing_id), 3) . '</a>'),
                );
                do_action('wp_dp_add_notification', $notification_array);
                // notification lock at your profile
                $member_name = '<a href="' . esc_url(get_the_permalink($enquiry_member)) . '">' . esc_html(get_the_title($enquiry_member)) . '</a>';
                $notification_array = array(
                    'type' => 'enquiry',
                    'submitted_type' => 'true',
                    'reciever_id' => '10868',
                    'element_id' => $listing_id,
                    'message' => force_balance_tags(wp_dp_plugin_text_srt('wp_dp_notification_you_has_submitted_enquiry') . ' <a href="' . get_the_permalink($listing_id) . '">' . wp_dp_limit_text(get_the_title($listing_id), 3) . '</a>'),
                );
                do_action('wp_dp_add_notification', $notification_array);

                echo json_encode($json);
                exit();
            } else {
                $json['type'] = 'error';
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_enquire_arrange_login');
                echo json_encode($json);
                exit();
            }
        }

        public function wp_dp_send_arrange_submit_callback() {
            global $wp_dp_plugin_options;
            $wp_dp_captcha_switch = isset($wp_dp_plugin_options['wp_dp_captcha_switch']) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';

            $arrange_user_name = wp_dp_get_input('arrange_user_name', NULL, 'STRING');
            $arrange_user_message = wp_dp_get_input('arrange_user_message', NULL, 'STRING');
            $arrange_view_date = wp_dp_get_input('arrange_view_date', '');
            $arrange_view_time = wp_dp_get_input('arrange_view_time', '');

            $listing_user = wp_dp_get_input('wp_dp_listing_user', 0);
            $listing_member = wp_dp_get_input('wp_dp_listing_member', 0);
            $listing_id = wp_dp_get_input('wp_dp_listing_id', 0);
            $listing_type_id = wp_dp_get_input('wp_dp_listing_type_id', 0);
            $viewing_user = wp_dp_get_input('wp_dp_viewing_user', 0);
            $viewing_member = wp_dp_get_input('wp_dp_viewing_member', 0);

            if ( $listing_member == $viewing_member ) {
                $json['type'] = 'error';
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_viewing_own_listing_error');
                echo json_encode($json);
                exit();
            }

            if ( empty($arrange_user_name) ) {
                $json['type'] = 'error';
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_viewing_name_empty');
                echo json_encode($json);
                exit();
            }

            if ( empty($arrange_user_message) ) {
                $json['type'] = 'error';
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_viewing_msg_empty');
                echo json_encode($json);
                exit();
            }
            wp_dp_verify_term_condition_form_field('term_policy');

            if ( $wp_dp_captcha_switch == 'on' ) {
                do_action('wp_dp_verify_captcha_form');
            }

            /*
             * Add inquery in DP logic
             */
            $order_inquiry_post = array(
                'post_title' => wp_strip_all_tags(get_the_title($listing_id)),
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'listing_viewings',
                'post_date' => current_time('Y/m/d H:i:s', 1)
            );
            //insert Arrange Viewing
            $viewing_id = wp_insert_post($order_inquiry_post);
            if ( $viewing_id ) {
                // Update the post into the database
                $my_post = array(
                    'ID' => $viewing_id,
                    'post_title' => 'viewing-' . $viewing_id,
                    'post_name' => 'viewing-' . $viewing_id,
                );
                wp_update_post($my_post);
                // Save Form Fields

                if ( $arrange_view_date != '' ) {
                    update_post_meta($viewing_id, 'wp_dp_arrange_view_date', strtotime($arrange_view_date));
                }
                if ( $arrange_view_time != '' ) {
                    update_post_meta($viewing_id, 'wp_dp_arrange_view_time', strtotime($arrange_view_time));
                }

                update_post_meta($viewing_id, 'wp_dp_user_name', $arrange_user_name);
                update_post_meta($viewing_id, 'wp_dp_user_message', $arrange_user_message);
                // Save Viewing Listing Fields
                update_post_meta($viewing_id, 'wp_dp_listing_user', $listing_user);
                update_post_meta($viewing_id, 'wp_dp_listing_member', $listing_member);
                update_post_meta($viewing_id, 'wp_dp_listing_id', $listing_id);
                update_post_meta($viewing_id, 'wp_dp_listing_type_id', $listing_type_id);
                update_post_meta($viewing_id, 'wp_dp_viewing_user', $viewing_user);
                update_post_meta($viewing_id, 'wp_dp_viewing_member', $viewing_member);

                update_post_meta($viewing_id, 'wp_dp_viewing_id', $viewing_id);
                update_post_meta($viewing_id, 'wp_dp_viewing_status', 'Processing');
                update_post_meta($viewing_id, 'buyer_read_status', '0');
                update_post_meta($viewing_id, 'seller_read_status', '0');

                /* Adding Notification */
                $member_name = '<a href="' . esc_url(get_the_permalink($viewing_member)) . '">' . esc_html(get_the_title($viewing_member)) . '</a>';
                $notification_array = array(
                    'type' => 'viewing',
                    'element_id' => $listing_id,
                    'message' => force_balance_tags($member_name . ' ' . wp_dp_plugin_text_srt('wp_dp_notification_submitted_viewing') . ' <a href="' . get_the_permalink($listing_id) . '">' . wp_dp_limit_text(get_the_title($listing_id), 5) . '</a>'),
                );
                do_action('wp_dp_add_notification', $notification_array);

                do_action('wp_dp_received_arrange_viewing_email', $_POST);  // email templete
                $json['type'] = 'success';
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_enquire_arrange_message_sent_successfully');
                echo json_encode($json);
                exit();
            }
        }

        public function wp_dp_enquire_arrange_buttons_element_html_callback($listing_id, $det_view = '') {
            $temperory = 'hide';
            if ( $temperory != 'hide' ) {
                ?>
                <div class="enquire-holder">

                    <?php
                    $target_modal = '';
                    $target_arrange_modal = '';
                    $target_class = ' wp-dp-open-signin-tab';
                    if ( is_user_logged_in() ) {
                        $target_class = '';
                        $target_modal = ' data-toggle="modal" data-target="#enquiry-modal"';
                        $target_arrange_modal = ' data-toggle="modal" data-target="#arrange-modal"';
                    }
                    ?>
                    <a class="enquire-btn<?php echo esc_attr($target_class); ?>" href="javascript:void(0);"<?php echo ($target_modal); ?>><i class="icon- icon-comment"></i><?php echo wp_dp_plugin_text_srt('wp_dp_enquire_arrange_enquiry_now'); ?></a>

                    <a class="<?php echo ($det_view != 'view-5' ? 'bgcolor ' : '') ?>enquire-btn<?php echo esc_attr($target_class); ?>" href="javascript:void(0);"<?php echo ($target_arrange_modal); ?>><i class="icon- icon-calendar-check-o"></i><?php echo wp_dp_plugin_text_srt('wp_dp_member_register_request_viewing'); ?></a>
                </div>
                <?php
            }
            $this->wp_dp_popupbox_enquire_now($listing_id, wp_dp_plugin_text_srt('wp_dp_enquire_arrange_enquiry_now'), 'enquiry');
            $this->wp_dp_popupbox_arrange_view($listing_id, wp_dp_plugin_text_srt('wp_dp_member_register_request_viewing'), 'arrange');

            $wp_dp_cs_inline_script = '
            jQuery(document).ready(function () {
                jQuery(document).on("click", ".listing-detail .enquire-holder .enquire-btn", function() {
                    "use strict";
                    jQuery("#enquiry-modal").find("form")[0].reset();
                    jQuery("#arrange-modal").find("form")[0].reset();
                    jQuery("#enquiry-modal .response-message").html("");
                    jQuery("#arrange-modal .response-message").html("");
                });
            });';
            wp_dp_cs_inline_enqueue_script($wp_dp_cs_inline_script, 'wp-dp-custom-inline');
        }

        public function wp_dp_popupbox_enquire_now($listing_id = '', $heading = '', $type = '') {
            global $wp_dp_plugin_options, $Wp_dp_Captcha, $wp_dp_form_fields_frontend;
            $wp_dp_captcha_switch = '';
            $wp_dp_captcha_switch = isset($wp_dp_plugin_options['wp_dp_captcha_switch']) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';
            $wp_dp_term_policy_switch = isset($wp_dp_plugin_options['wp_dp_term_policy_switch']) ? $wp_dp_plugin_options['wp_dp_term_policy_switch'] : '';
            $wp_dp_sitekey = isset($wp_dp_plugin_options['wp_dp_sitekey']) ? $wp_dp_plugin_options['wp_dp_sitekey'] : '';
            $wp_dp_secretkey = isset($wp_dp_plugin_options['wp_dp_secretkey']) ? $wp_dp_plugin_options['wp_dp_secretkey'] : '';
            $wp_dp_listing_counter = rand(12345, 54321);
            $wp_dp_listing_member_id = get_post_meta($listing_id, 'wp_dp_listing_member', true);
            $listing_member = wp_dp_user_id_form_company_id($wp_dp_listing_member_id);
            $wp_dp_member_email_address = get_post_meta($wp_dp_listing_member_id, 'wp_dp_email_address', true);
            $listing_type_slug = get_post_meta($listing_id, 'wp_dp_listing_type', true);
            $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => $listing_type_slug, 'post_status' => 'publish' ));
            $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
            $user_id = $company_id = 0;
            $user_id = get_current_user_id();
            $display_name = '';
            $phone_number = '';
            $email_address = '';
            wp_enqueue_script('wp-dp-validation-script');
            if ( $user_id != 0 ) {
                $company_id = get_user_meta($user_id, 'wp_dp_company', true);
                $display_name = esc_html(get_the_title($company_id));
                $phone_number = get_post_meta($company_id, 'wp_dp_phone_number', true);
                $email_address = get_post_meta($company_id, 'wp_dp_email_address', true);
            }
            ?>
            <!-- Modal -->
            <div class="modal modal-form fade" id="enquiry-modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="enquiry-myModalLabel"><?php echo wp_dp_plugin_text_srt('wp_dp_enquire_arrange_request_inquiry'); ?></h4>
                        </div>
                        <div class="modal-body">
                            <form id="frm_listing<?php echo absint($wp_dp_listing_counter); ?>" class="enquiry-request-form" name="form_name" onsubmit="return wp_dp_enquire_arrange_send_message('<?php echo absint($wp_dp_listing_counter); ?>');" method="get">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-holder">
                                            <i class="icon-user2"></i>
                                            <?php
                                            $wp_dp_opt_array = array(
                                                'std' => esc_html($display_name),
                                                'cust_name' => 'user_name',
                                                'return' => false,
                                                'classes' => 'input-field wp-dp-dev-req-field',
                                                'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_press(this,\'text\')"  placeholder=" ' . wp_dp_plugin_text_srt('wp_dp_member_contact_your_name') . '"',
                                            );
                                            if ( isset($display_name) && $display_name != '' ) {
                                                //$wp_dp_opt_array['extra_atr'] = 'readonly="readonly"';
                                            }
                                            $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-holder">
                                            <i class="icon-phone4"></i>
                                            <?php
                                            $wp_dp_opt_array = array(
                                                'std' => esc_html($phone_number),
                                                'cust_name' => 'user_phone',
                                                'return' => false,
                                                'classes' => 'input-field',
                                            );
                                            if ( isset($phone_number) && $phone_number != '' ) {
                                                //$wp_dp_opt_array['extra_atr'] = 'readonly="readonly"';
                                            }
                                            $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-holder">
                                            <i class="icon-mail"></i>
                                            <?php
                                            $wp_dp_opt_array = array(
                                                'std' => esc_html($email_address),
                                                'cust_name' => 'user_email',
                                                'return' => false,
                                                'classes' => 'input-field wp-dp-dev-req-field wp-dp-email-field',
                                                'extra_atr' => ' onchange="wp_dp_review_user_avail(\'email\');" onkeypress="wp_dp_contact_form_valid_press(this,\'email\')" placeholder=" ' . wp_dp_plugin_text_srt('wp_dp_member_contact_your_email') . '"',
                                            );
                                            if ( isset($email_address) && $email_address != '' ) {
                                                //$wp_dp_opt_array['extra_atr'] = 'readonly="readonly"';
                                            }
                                            $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-holder">
                                            <i class="icon-mail"></i>
                                            <?php
                                            $wp_dp_opt_array = array(
                                                'std' => '',
                                                'cust_name' => 'user_message_title',
                                                'return' => false,
                                                'classes' => 'input-field wp-dp-dev-req-field',
                                                'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_press(this,\'text\')" placeholder=" ' . wp_dp_plugin_text_srt('wp_dp_member_contact_your_message_title') . '"',
                                            );
                                            $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-holder">
                                            <i class="icon-message"></i>
                                            <?php
                                            $wp_dp_opt_array = array(
                                                'std' => '',
                                                'id' => 'user_message',
                                                'cust_name' => 'user_message',
                                                'classes' => 'textarea-field wp-dp-dev-req-field',
                                                'description' => '',
                                                'return' => false,
                                                'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_press(this,\'text\')" placeholder=" ' . wp_dp_plugin_text_srt('wp_dp_author_info_sender_message') . ' *"',
                                            );
                                            $wp_dp_form_fields_frontend->wp_dp_form_textarea_render($wp_dp_opt_array);
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                    if ( $wp_dp_captcha_switch == 'on' ) {
                                        if ( $wp_dp_sitekey <> '' and $wp_dp_secretkey <> '' ) {
                                            wp_dp_google_recaptcha_scripts();
                                            ?>
                                            <script>
                                                var recaptcha_enquery;
                                                var wp_dp_multicap = function () {
                                                    //Render the recaptcha1 on the element with ID "recaptcha1"
                                                    recaptcha_enquery = grecaptcha.render('recaptcha_enquery_popup', {
                                                        'sitekey': '<?php echo ($wp_dp_sitekey); ?>', //Replace this with your Site key
                                                        'theme': 'light'
                                                    });

                                                };
                                            </script>
                                            <?php
                                        }
                                        if ( class_exists('Wp_dp_Captcha') ) {
                                            $output = '<div class="col-md-12 recaptcha-reload" id="recaptcha_enquery_div">';
                                            $output .= $Wp_dp_Captcha->wp_dp_generate_captcha_form_callback('recaptcha_enquery_popup', 'true');
                                            $output .='</div>';
                                            echo force_balance_tags($output);
                                        }
                                    }

                                    if ( $wp_dp_term_policy_switch == 'on' ) {
                                        ?>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="field-holder">
                                                <?php wp_dp_term_condition_form_field('term_policy', 'term_policy'); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-holder enquiry-request-holder input-button-loader">
                                            <?php
                                            $wp_dp_opt_array = array(
                                                'std' => wp_dp_plugin_text_srt('wp_dp_contact_send_message'),
                                                'cust_name' => 'message_submit',
                                                'return' => false,
                                                'classes' => 'bgcolor',
                                                'cust_type' => 'submit',
                                                'force_std' => true,
                                            );
                                            $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                            ?>
                                        </div>
                                    </div>

                                </div>
                                <?php
                                $wp_dp_opt_array = array(
                                    'std' => intval($listing_member),
                                    'id' => 'listing_user',
                                    'return' => false,
                                    'force_std' => true,
                                );
                                $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                                $wp_dp_opt_array = array(
                                    'std' => intval($wp_dp_listing_member_id),
                                    'id' => 'listing_member',
                                    'return' => false,
                                    'force_std' => true,
                                );
                                $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                                $wp_dp_opt_array = array(
                                    'std' => intval($listing_id),
                                    'id' => 'listing_id',
                                    'return' => false,
                                    'force_std' => true,
                                );
                                $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                                $wp_dp_opt_array = array(
                                    'std' => intval($listing_type_id),
                                    'id' => 'listing_type_id',
                                    'return' => false,
                                    'force_std' => true,
                                );
                                $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                                $wp_dp_opt_array = array(
                                    'std' => intval($user_id),
                                    'id' => 'enquiry_user',
                                    'return' => false,
                                    'force_std' => true,
                                );
                                $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                                $wp_dp_opt_array = array(
                                    'std' => intval($company_id),
                                    'id' => 'enquiry_member',
                                    'return' => false,
                                    'force_std' => true,
                                );
                                $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $wp_dp_cs_inline_script = '
			function wp_dp_enquire_arrange_send_message(form_id, type) {
                                
                                "use strict";
                                var returnType = wp_dp_validation_process(jQuery(".enquiry-request-form"));
                                if (returnType == false) {
                                    return false;
                                }else{
				var thisObj = jQuery(".enquiry-request-holder");
				wp_dp_show_loader(".enquiry-request-holder", "", "button_loader", thisObj);
				var datastring = jQuery("#frm_listing" + form_id + "").serialize() + "&action=wp_dp_send_enquire_arrange_submit";
				jQuery.ajax({
					type: "POST",
					url: wp_dp_globals.ajax_url,
					data: datastring,
					dataType: "json",
					success: function(response) {
						wp_dp_show_response(response, "", thisObj);
						if (response.type == "success") {
							jQuery("#frm_listing" + form_id + "").trigger("reset");
						}
					}
				});
                               }
                               return false;
			}';
            wp_dp_cs_inline_enqueue_script($wp_dp_cs_inline_script, 'wp-dp-custom-inline');
        }

        public function wp_dp_enquiry_agent_contact_form_html_callback($listing_id = '', $type = '', $member_info = true) {
            global $wp_dp_plugin_options, $Wp_dp_Captcha, $wp_dp_form_fields_frontend;

            $sidebar_contact_info = wp_dp_element_hide_show($listing_id, 'sidebar_contact_info');
            if ( $sidebar_contact_info != 'on' ) {
                return;
            }

            $wp_dp_captcha_switch = '';
            $wp_dp_captcha_switch = isset($wp_dp_plugin_options['wp_dp_captcha_switch']) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';
            $wp_dp_term_policy_switch = isset($wp_dp_plugin_options['wp_dp_term_policy_switch']) ? $wp_dp_plugin_options['wp_dp_term_policy_switch'] : '';
            $wp_dp_sitekey = isset($wp_dp_plugin_options['wp_dp_sitekey']) ? $wp_dp_plugin_options['wp_dp_sitekey'] : '';
            $wp_dp_secretkey = isset($wp_dp_plugin_options['wp_dp_secretkey']) ? $wp_dp_plugin_options['wp_dp_secretkey'] : '';
            $wp_dp_listing_counter = rand(12345, 54321);

            if ( $type == 'member_detail' ) {
                $wp_dp_listing_member_id = $listing_id;
                $listing_member = wp_dp_user_id_form_company_id($wp_dp_listing_member_id);
                $wp_dp_member_email_address = get_post_meta($wp_dp_listing_member_id, 'wp_dp_email_address', true);
            } else {
                $wp_dp_listing_member_id = get_post_meta($listing_id, 'wp_dp_listing_member', true);
                $listing_member = wp_dp_user_id_form_company_id($wp_dp_listing_member_id);
                $wp_dp_member_email_address = get_post_meta($wp_dp_listing_member_id, 'wp_dp_email_address', true);
            }

            $listing_type_slug = get_post_meta($listing_id, 'wp_dp_listing_type', true);
            $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => $listing_type_slug, 'post_status' => 'publish' ));
            $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
            $user_id = $company_id = 0;
            $user_id = get_current_user_id();
            $display_name = '';
            $phone_number = '';
            $email_address = '';
            wp_enqueue_script('wp-dp-validation-script');
            if ( $user_id != 0 ) {
                $company_id = get_user_meta($user_id, 'wp_dp_company', true);
                $display_name = esc_html(get_the_title($company_id));
                $phone_number = get_post_meta($company_id, 'wp_dp_phone_number', true);
                $email_address = get_post_meta($company_id, 'wp_dp_email_address', true);
            }
            ?>
            <!-- Modal -->
            <div class="contact-member-form member-detail">
                <?php do_action('wp_dp_author_short_info_html', $listing_id, 'view-5'); ?>
                <form id="frm_listing<?php echo absint($wp_dp_listing_counter); ?>" class="contactform_name contactform_name<?php echo absint($wp_dp_listing_counter); ?>" name="form_name" onsubmit="return wp_dp_enquiry_agent_contact_form_send_message<?php echo absint($wp_dp_listing_counter); ?>('<?php echo absint($wp_dp_listing_counter); ?>');" method="get">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="field-holder">
                                <i class="icon-user2"></i>
                                <?php
                                $wp_dp_opt_array = array(
                                    'std' => esc_html($display_name),
                                    'cust_name' => 'user_name',
                                    'return' => false,
                                    'classes' => 'input-field wp-dp-dev-req-field',
                                    'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_presse(this,\'text\')"  placeholder=" ' . wp_dp_plugin_text_srt('wp_dp_member_contact_your_name') . '"',
                                );
                                $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                ?>
                            </div>
                        </div> 
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="field-holder">
                                <i class="icon-mail"></i>
                                <?php
                                $wp_dp_opt_array = array(
                                    'std' => esc_html($email_address),
                                    'cust_name' => 'user_email',
                                    'return' => false,
                                    'classes' => 'input-field wp-dp-dev-req-field wp-dp-email-field',
                                    'extra_atr' => ' onchange="wp_dp_review_user_avail(\'email\');" onkeypress="wp_dp_contact_form_valid_presse(this,\'email\')" placeholder=" ' . wp_dp_plugin_text_srt('wp_dp_member_contact_your_email') . '"',
                                );
                                $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                ?>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="field-holder">
                                <i class="icon-user2"></i>
                                <?php
                                $wp_dp_opt_array = array(
                                    'std' => esc_html($phone_number),
                                    'cust_name' => 'user_phone',
                                    'return' => false,
                                    'classes' => 'input-field wp-dp-dev-req-field',
                                    'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_presse(this,\'text\')"  placeholder=" ' . wp_dp_plugin_text_srt('listing_contact_phone') . '"',
                                );
                                $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                ?>
                            </div>
                        </div> 

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="field-holder">
                                <i class="icon-mail"></i>
                                <?php
                                $wp_dp_opt_array = array(
                                    'std' => '',
                                    'cust_name' => 'user_message_title',
                                    'return' => false,
                                    'classes' => 'input-field wp-dp-dev-req-field',
                                    'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_presse(this,\'text\')" placeholder=" ' . wp_dp_plugin_text_srt('wp_dp_member_contact_your_message_title') . '"',
                                );
                                $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                ?>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="field-holder">
                                <i class="icon-message"></i>
                                <?php
                                $wp_dp_opt_array = array(
                                    'std' => '',
                                    'id' => 'user_message',
                                    'cust_name' => 'user_message',
                                    'classes' => 'textarea-field wp-dp-dev-req-field',
                                    'description' => '',
                                    'return' => false,
                                    'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_presse(this,\'text\')" placeholder=" ' . wp_dp_plugin_text_srt('wp_dp_author_info_sender_message') . ' *"',
                                );
                                $wp_dp_form_fields_frontend->wp_dp_form_textarea_render($wp_dp_opt_array);
                                ?>
                            </div>
                        </div>
                        <?php
                        if ( $wp_dp_captcha_switch == 'on' ) {
                            if ( $wp_dp_sitekey <> '' and $wp_dp_secretkey <> '' ) {
                                wp_dp_google_recaptcha_scripts();
                                ?>
                                <script>
                                    var recaptcha_enquery;
                                    var wp_dp_multicap = function () {
                                        //Render the recaptcha1 on the element with ID "recaptcha1"
                                        recaptcha_enquery = grecaptcha.render('recaptcha_enquery', {
                                            'sitekey': '<?php echo ($wp_dp_sitekey); ?>', //Replace this with your Site key
                                            'theme': 'light'
                                        });

                                    };
                                </script>
                                <?php
                            }
                            if ( class_exists('Wp_dp_Captcha') ) {
                                $output = '<div class="col-md-12 recaptcha-reload" id="recaptcha_enquery_div">';
                                $output .= $Wp_dp_Captcha->wp_dp_generate_captcha_form_callback('recaptcha_enquery', 'true');
                                $output .='</div>';
                                echo force_balance_tags($output);
                            }
                        }
                        ?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="field-holder">
                                <div class="enquiry-request-holder input-button-loader">
                                    <?php
                                    // if (is_user_logged_in()) {
                                    $wp_dp_opt_array = array(
                                        'std' => wp_dp_plugin_text_srt('wp_dp_prop_detail_contact_cnt_agentt'),
                                        'cust_name' => 'message_submit',
                                        'return' => false,
                                        'classes' => '',
                                        'cust_type' => 'submit',
                                        'force_std' => true,
                                    );
                                    $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                    //    } 
//                                    else {
//                                        $wp_dp_opt_array = array(
//                                            'std' => wp_dp_plugin_text_srt('wp_dp_prop_detail_contact_cnt_agent'),
//                                            'cust_name' => 'message_submit',
//                                            'return' => false,
//                                            'classes' => 'bgcolor wp-dp-open-signin-tab',
//                                            'cust_type' => 'button',
//                                            'force_std' => true,
//                                        );
//                                        $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
//                                        
//                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php
                    $wp_dp_opt_array = array(
                        'std' => intval($listing_member),
                        'id' => 'listing_user',
                        'return' => false,
                        'force_std' => true,
                    );
                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                    $wp_dp_opt_array = array(
                        'std' => intval($wp_dp_listing_member_id),
                        'id' => 'listing_member',
                        'return' => false,
                        'force_std' => true,
                    );
                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                    $wp_dp_opt_array = array(
                        'std' => intval($listing_id),
                        'id' => 'listing_id',
                        'return' => false,
                        'force_std' => true,
                    );
                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                    $wp_dp_opt_array = array(
                        'std' => intval($listing_type_id),
                        'id' => 'listing_type_id',
                        'return' => false,
                        'force_std' => true,
                    );
                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                    $wp_dp_opt_array = array(
                        'std' => intval($user_id),
                        'id' => 'enquiry_user',
                        'return' => false,
                        'force_std' => true,
                    );
                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                    $wp_dp_opt_array = array(
                        'std' => intval($company_id),
                        'id' => 'enquiry_member',
                        'return' => false,
                        'force_std' => true,
                    );
                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);
                    ?>
                </form>
            </div> 
            <?php
            $wp_dp_cs_inline_script = '
			function wp_dp_enquiry_agent_contact_form_send_message' . absint($wp_dp_listing_counter) . '(form_id, type) {
                                
                                "use strict";
                                var returnType = wp_dp_validation_process(jQuery(".contactform_name' . absint($wp_dp_listing_counter) . '"));
                                if (returnType == false) {
                                    return false;
                                }else{
				var thisObj = jQuery(".enquiry-request-holder");
				wp_dp_show_loader(".enquiry-request-holder", "", "button_loader", thisObj);
				var datastring = jQuery("#frm_listing" + form_id + "").serialize() + "&action=wp_dp_enquiry_agent_contact_form_submit";
				jQuery.ajax({
					type: "POST",
					url: wp_dp_globals.ajax_url,
					data: datastring,
					dataType: "json",
					success: function(response) {
						wp_dp_show_response(response, "", thisObj);
						if (response.type == "success") {
							jQuery("#frm_listing" + form_id + "").trigger("reset");
						}
					}
				});
                               }
                               return false;
			}';
            wp_dp_cs_inline_enqueue_script($wp_dp_cs_inline_script, 'wp-dp-custom-inline');
        }

        public function wp_dp_popupbox_arrange_view($listing_id = '', $heading = '', $type = '') {
            global $wp_dp_plugin_options, $Wp_dp_Captcha, $Wp_dp_Captcha, $wp_dp_form_fields, $wp_dp_form_fields_frontend;
            wp_enqueue_script('wp-dp-validation-script');
            $wp_dp_captcha_switch = '';
            $wp_dp_captcha_switch = isset($wp_dp_plugin_options['wp_dp_captcha_switch']) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';
            $wp_dp_term_policy_switch = isset($wp_dp_plugin_options['wp_dp_term_policy_switch']) ? $wp_dp_plugin_options['wp_dp_term_policy_switch'] : '';
            $wp_dp_sitekey = isset($wp_dp_plugin_options['wp_dp_sitekey']) ? $wp_dp_plugin_options['wp_dp_sitekey'] : '';
            $wp_dp_secretkey = isset($wp_dp_plugin_options['wp_dp_secretkey']) ? $wp_dp_plugin_options['wp_dp_secretkey'] : '';

            $wp_dp_listing_counter = rand(12345, 54321);
            $wp_dp_listing_member_id = get_post_meta($listing_id, 'wp_dp_listing_member', true);
            $listing_member = wp_dp_user_id_form_company_id($wp_dp_listing_member_id);
            $listing_type_slug = get_post_meta($listing_id, 'wp_dp_listing_type', true);
            $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => $listing_type_slug, 'post_status' => 'publish' ));
            $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
            $user_id = $company_id = 0;
            $user_id = get_current_user_id();
            $display_name = '';
            $phone_number = '';
            $email_address = '';
            if ( $user_id != 0 ) {
                $company_id = get_user_meta($user_id, 'wp_dp_company', true);
                $display_name = esc_html(get_the_title($company_id));
                $phone_number = get_post_meta($company_id, 'wp_dp_phone_number', true);
                $email_address = get_post_meta($company_id, 'wp_dp_email_address', true);
            }


            $wp_dp_listing_member_id = get_post_meta($listing_id, 'wp_dp_listing_member', true);
            $wp_dp_member_email_address = get_post_meta($wp_dp_listing_member_id, 'wp_dp_email_address', true);
            $book_off_days = get_post_meta($wp_dp_listing_member_id, 'wp_dp_calendar', true);
            $book_off_days = ( ! empty($book_off_days) ) ? $book_off_days : array();
            if ( isset($book_off_days) && ! empty($book_off_days) ) {
                $book_off_days = implode(',', $book_off_days);
            }
            wp_enqueue_style('wp_dp_datepicker_css');
            wp_enqueue_script('jquery-ui');
            if ( empty($book_off_days) ) {
                $book_off_days = '';
            }
            ?>
            <div class="modal modal-form fade" id="arrange-modal" tabindex="-1" role="dialog" aria-labelledby="arrange-myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="arrange-myModalLabel"><?php echo wp_dp_plugin_text_srt('wp_dp_member_register_request_viewing'); ?></h4>
                            <p><?php echo wp_dp_plugin_text_srt('wp_dp_enquire_arrange_viewing_times_descriptione'); ?></p>
                        </div>
                        <div class="modal-body">
                            <div class="booking-info-sec">
                                <form id="frm_arrange<?php echo absint($wp_dp_listing_counter); ?>" class="viewing-request-form" name="form_arrange_view" onsubmit=" return wp_dp_arrange_view_send_message('<?php echo absint($wp_dp_listing_counter); ?>');" method="get">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="field-holder has-icon">
                                                <div class="date-sec">
                                                    <i class="icon-calendar5"></i>
                                                    <i class="icon-keyboard_arrow_down"> </i>
                                                    <?php
                                                    $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                                                            array(
                                                                'cust_name' => 'arrange_view_date',
                                                                'cust_id' => 'date-of-booking',
                                                                'classes' => 'form-control booking-date wp-dp-required-field',
                                                                'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_enquire_arrange_viewing_schedule') . '"',
                                                                'std' => '',
                                                            )
                                                    );
                                                    ?>  
                                                    <div id="datepicker_1468" class="reservaion-calendar hasDatepicker"></div>
                                                    <script type="text/javascript">
                                                        jQuery(document).ready(function () {
                                                            var off_days_array = '<?php echo wp_dp_allow_special_char($book_off_days); ?>';
                                                            off_days_array = off_days_array.split(',');
                                                            jQuery("#date-of-booking").datepicker({
                                                                showOtherMonths: true,
                                                                firstDay: 1,
                                                                minDate: 0,
                                                                dateFormat: "dd M, yy",
                                                                prevText: "",
                                                                nextText: "",
                                                                monthNames: [
                                                                    "<?php echo wp_dp_plugin_text_srt('wp_dp_enquire_arrange_calendar_month_jan'); ?>",
                                                                    "<?php echo wp_dp_plugin_text_srt('wp_dp_enquire_arrange_calendar_month_feb'); ?>",
                                                                    "<?php echo wp_dp_plugin_text_srt('wp_dp_enquire_arrange_calendar_month_mar'); ?>",
                                                                    "<?php echo wp_dp_plugin_text_srt('wp_dp_enquire_arrange_calendar_month_apr'); ?>",
                                                                    "<?php echo wp_dp_plugin_text_srt('wp_dp_enquire_arrange_calendar_month_may'); ?>",
                                                                    "<?php echo wp_dp_plugin_text_srt('wp_dp_enquire_arrange_calendar_month_jun'); ?>",
                                                                    "<?php echo wp_dp_plugin_text_srt('wp_dp_enquire_arrange_calendar_month_jul'); ?>",
                                                                    "<?php echo wp_dp_plugin_text_srt('wp_dp_enquire_arrange_calendar_month_aug'); ?>",
                                                                    "<?php echo wp_dp_plugin_text_srt('wp_dp_enquire_arrange_calendar_month_sep'); ?>",
                                                                    "<?php echo wp_dp_plugin_text_srt('wp_dp_enquire_arrange_calendar_month_oct'); ?>",
                                                                    "<?php echo wp_dp_plugin_text_srt('wp_dp_enquire_arrange_calendar_month_nov'); ?>",
                                                                    "<?php echo wp_dp_plugin_text_srt('wp_dp_enquire_arrange_calendar_month_dec'); ?>"
                                                                ],
                                                                beforeShowDay: function (date) {
                                                                    var string = jQuery.datepicker.formatDate('dd M, yy', date);
                                                                    return [off_days_array.indexOf(string) == -1];
                                                                }
                                                            });
                                                            jQuery(".chosen-select-no-single").chosen();
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="field-holder has-icon">
                                                <i class="icon-clock2"></i>
                                                <?php
                                                $time_lapse = 15;
                                                $time_list = $this->listing_time_list($time_lapse);
                                                if ( is_array($time_list) && sizeof($time_list) > 0 ) {
                                                    foreach ( $time_list as $time_key => $time_val ) {
                                                        $drop_down_options[$time_key] = esc_html($time_val);
                                                    }
                                                }
                                                if ( ! empty($drop_down_options) ) {
                                                    $wp_dp_opt_array = array();
                                                    $wp_dp_opt_array['std'] = '';
                                                    $wp_dp_opt_array['cust_id'] = 'arrange_view_time';
                                                    $wp_dp_opt_array['cust_name'] = 'arrange_view_time';
                                                    $wp_dp_opt_array['options'] = $drop_down_options;
                                                    $wp_dp_opt_array['classes'] = 'chosen-select-no-single my_select_box';
                                                    $wp_dp_opt_array['return'] = true;
                                                    echo wp_dp_allow_special_char($wp_dp_form_fields_frontend->wp_dp_form_select_render($wp_dp_opt_array));
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="field-holder">
                                                <i class="icon-user2"></i>
                                                <?php
                                                $wp_dp_opt_array = array(
                                                    'std' => esc_html($display_name),
                                                    'cust_name' => 'arrange_user_name',
                                                    'return' => false,
                                                    'classes' => 'input-field wp-dp-dev-req-field',
                                                    'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_press(this,\'text\')" placeholder=" ' . wp_dp_plugin_text_srt('wp_dp_member_contact_your_name') . '"',
                                                );
                                                if ( isset($display_name) && $display_name != '' ) {
                                                    //$wp_dp_opt_array['extra_atr'] = 'readonly="readonly"';
                                                }
                                                $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="field-holder">
                                                <i class="icon-message"></i>
                                                <?php
                                                $wp_dp_opt_array = array(
                                                    'std' => '',
                                                    'id' => 'arrange_user_message',
                                                    'cust_name' => 'arrange_user_message',
                                                    'classes' => 'textarea-field wp-dp-dev-req-field',
                                                    'description' => '',
                                                    'return' => false,
                                                    'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_press(this,\'text\')" placeholder=" ' . wp_dp_plugin_text_srt('wp_dp_enquire_arrange_viewing_message') . '"',
                                                );
                                                $wp_dp_form_fields_frontend->wp_dp_form_textarea_render($wp_dp_opt_array);
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                        if ( $wp_dp_captcha_switch == 'on' ) {
                                            if ( $wp_dp_sitekey <> '' and $wp_dp_secretkey <> '' ) {
                                                wp_dp_google_recaptcha_scripts();
                                                ?>
                                                <script>
                                                    var recaptcha_arrange_view;
                                                    var wp_dp_multicap = function () {
                                                        //Render the recaptcha1 on the element with ID "recaptcha1"
                                                        recaptcha_arrange_view = grecaptcha.render('recaptcha_arrange_view', {
                                                            'sitekey': '<?php echo ($wp_dp_sitekey); ?>', //Replace this with your Site key
                                                            'theme': 'light'
                                                        });

                                                    };
                                                </script>
                                                <?php
                                            }
                                            if ( class_exists('Wp_dp_Captcha') ) {
                                                $output = '<div class="col-md-12 recaptcha-reload" id="recaptcha_arrange_view_div">';
                                                $output .= $Wp_dp_Captcha->wp_dp_generate_captcha_form_callback('recaptcha_arrange_view', 'true');
                                                $output .='</div>';
                                                echo force_balance_tags($output);
                                            }
                                        }
                                        ?>
                                        <?php if ( $wp_dp_term_policy_switch == 'on' ) { ?>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="field-holder">
                                                    <div class="check-box-dpind">
                                                        <?php wp_dp_term_condition_form_field('arrange_viewing_term_policy', 'term_policy'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="field-holder viewing-request-holder input-button-loader">
                                                <?php
                                                $wp_dp_opt_array = array(
                                                    'std' => wp_dp_plugin_text_srt('wp_dp_contact_send_message'),
                                                    'cust_name' => 'submit_message_arrange',
                                                    'return' => false,
                                                    'classes' => 'bgcolor',
                                                    'cust_type' => 'submit',
                                                    'force_std' => true,
                                                );
                                                $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $wp_dp_opt_array = array(
                                        'std' => intval($listing_member),
                                        'id' => 'viewing_listing_user',
                                        'cust_name' => 'wp_dp_listing_user',
                                        'return' => false,
                                        'force_std' => true,
                                    );
                                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                                    $wp_dp_opt_array = array(
                                        'std' => intval($wp_dp_listing_member_id),
                                        'id' => 'viewing_listing_member',
                                        'cust_name' => 'wp_dp_listing_member',
                                        'return' => false,
                                        'force_std' => true,
                                    );
                                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                                    $wp_dp_opt_array = array(
                                        'std' => intval($listing_id),
                                        'id' => 'viewing_listing_id',
                                        'cust_name' => 'wp_dp_listing_id',
                                        'return' => false,
                                        'force_std' => true,
                                    );
                                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                                    $wp_dp_opt_array = array(
                                        'std' => intval($listing_type_id),
                                        'id' => 'viewing_listing_type_id',
                                        'cust_name' => 'wp_dp_listing_type_id',
                                        'return' => false,
                                        'force_std' => true,
                                    );
                                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                                    $wp_dp_opt_array = array(
                                        'std' => intval($user_id),
                                        'id' => 'viewing_viewing_user',
                                        'cust_name' => 'wp_dp_viewing_user',
                                        'return' => false,
                                        'force_std' => true,
                                    );
                                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                                    $wp_dp_opt_array = array(
                                        'std' => intval($company_id),
                                        'id' => 'viewing_viewing_member',
                                        'cust_name' => 'wp_dp_viewing_member',
                                        'return' => false,
                                        'force_std' => true,
                                    );
                                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);
                                    ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $wp_dp_cs_inline_script = '
			function wp_dp_arrange_view_send_message(form_id, type) {
				"use strict";
                                var returnType = wp_dp_validation_process(jQuery(".viewing-request-form"));
                                if (returnType == false) {
                                    return false;
                                }else{
				var thisObj = jQuery(".viewing-request-holder");
				wp_dp_show_loader(".viewing-request-holder", "", "button_loader", thisObj);
				var datastring = jQuery("#frm_arrange" + form_id + "").serialize() + "&action=wp_dp_send_arrange_submit";
				jQuery.ajax({
					type: "POST",
					url: wp_dp_globals.ajax_url,
					data: datastring,
					dataType: "json",
					success: function(response) {
						wp_dp_show_response(response, "", thisObj);
						if (response.type == "success") {
							jQuery("#frm_arrange" + form_id + "").trigger("reset");
						}
					}
				});
                             }
                             return false;
			}
            jQuery(document).ready(function () {
                jQuery(".booking-date").focus(function() {
					$(".booking-info-sec .reservaion-calendar.hasDatepicker").show();
					$(document).mouseup(function(e) {
						var container = $(".booking-info-sec .reservaion-calendar.hasDatepicker");
						if (!container.is(e.target) && container.has(e.target).length === 0 && !$(".booking-date").is(e.target)){
							container.hide();
						}
					});

					$(".booking-info-sec .reservaion-calendar.hasDatepicker .undefined").click(function() {
						"use strict";
						if ($(this).hasClass("ui-state-disabled") == false) {
							$(".booking-info-sec .reservaion-calendar.hasDatepicker").hide();
						}
					});
				});

            });';
            wp_dp_cs_inline_enqueue_script($wp_dp_cs_inline_script, 'wp-dp-custom-inline');
        }

        public function listing_time_list($lapse = 15) {
            $hours = array();
            $start = '12:00AM';
            $end = '11:59PM';
            $interval = '+' . $lapse . ' minutes';

            $start_str = strtotime($start);
            $end_str = strtotime($end);
            $now_str = $start_str;
            while ( $now_str <= $end_str ) {
                $hours[date('h:i a', $now_str)] = date('h:i A', $now_str);
                $now_str = strtotime($interval, $now_str);
            }
            return $hours;
        }

    }

    global $wp_dp_enquire_arrange_button;
    $wp_dp_enquire_arrange_button = new wp_dp_enquire_arrange_button_element();
}