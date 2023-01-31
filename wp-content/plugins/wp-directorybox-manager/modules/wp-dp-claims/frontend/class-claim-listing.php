<?php
/*
 * claim listing class
 */
if ( ! class_exists('Wp_Dp_Claim_Listing') ) {

    Class Wp_Dp_Claim_Listing {
        /*
         * Constructor
         */

        public function __construct() {
            add_action('claim_listing_from', array( $this, 'claim_listing_from_callback' ), 10, 3);
            add_action('wp_ajax_claim_listing_from_save', array( $this, 'claim_listing_from_save_callback' ));
            add_action('wp_ajax_nopriv_claim_listing_from_save', array( $this, 'claim_listing_from_save_callback' ));
        }

        /*
         * claim listing form
         */

        public function claim_listing_from_save_callback() {
            global $wp_dp_plugin_options;

            $wp_dp_captcha_switch = isset($wp_dp_plugin_options['wp_dp_captcha_switch']) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';

            $wp_dp_claim_listing_user_name = isset($_POST['wp_dp_claim_listing_user_name']) ? $_POST['wp_dp_claim_listing_user_name'] : '';
            $wp_dp_claim_listing_user_email = isset($_POST['wp_dp_claim_listing_user_email']) ? $_POST['wp_dp_claim_listing_user_email'] : '';
            $wp_dp_claim_listing_reason = isset($_POST['wp_dp_claim_listing_reason']) ? $_POST['wp_dp_claim_listing_reason'] : '';
            $wp_dp_listing_id = isset($_POST['wp_dp_claim_listing_id']) ? $_POST['wp_dp_claim_listing_id'] : '';

            $error_string = '';
            if ( $wp_dp_claim_listing_user_name == '' ) {
                $error_string .= wp_dp_plugin_text_srt('wp_dp_claim_user_name_error');
            } else if ( $wp_dp_claim_listing_user_email == '' ) {
                $error_string .= wp_dp_plugin_text_srt('wp_dp_claim_email_error');
            } else if ( $wp_dp_claim_listing_reason == '' ) {
                $error_string .= wp_dp_plugin_text_srt('wp_dp_claim_reason_error');
            }
            if ( $error_string != '' ) {
                $response_array = array(
                    'type' => 'error',
                    'msg' => $error_string,
                );
                echo json_encode($response_array);
                wp_die();
            }

            if ( $wp_dp_captcha_switch == 'on' && ( ! is_user_logged_in()) ) {
                do_action('wp_dp_verify_captcha_form');
            }

            wp_dp_verify_term_condition_form_field('term_policy');

            if ( $wp_dp_claim_listing_user_name != '' && $wp_dp_claim_listing_user_email != '' && $wp_dp_claim_listing_reason != '' && $wp_dp_listing_id != '' ) {
                $listing_title = get_the_title($wp_dp_listing_id);

                $postarr = array( 'post_title' => $listing_title, 'post_status' => 'publish', 'post_type' => 'wp_dp_claims' );
                $inserted_listing_claim_id = wp_insert_post($postarr, false);
                if ( $inserted_listing_claim_id ) {
                    update_post_meta($inserted_listing_claim_id, 'wp_dp_claimer_on', $listing_title);
                    update_post_meta($inserted_listing_claim_id, 'wp_dp_claimer_name', $wp_dp_claim_listing_user_name);
                    update_post_meta($inserted_listing_claim_id, 'wp_dp_claimer_email', $wp_dp_claim_listing_user_email);
                    update_post_meta($inserted_listing_claim_id, 'wp_dp_claimer_reason', $wp_dp_claim_listing_reason);
                    update_post_meta($inserted_listing_claim_id, 'wp_dp_claim_type', 'claim');
                    update_post_meta($inserted_listing_claim_id, 'wp_dp_claim_action', 'pending');
                    update_post_meta($inserted_listing_claim_id, 'wp_dp_claim_listing_id', $wp_dp_listing_id);
                    

                    do_action('wp_dp_received_claim_email', $_POST);

                    $response_array = array(
                        'type' => 'success',
                        'msg' => '<p>' . wp_dp_plugin_text_srt('wp_dp_claim_list_success') . '</p>',
                    );
                    echo json_encode($response_array);
                }
            }
            die();
        }

        public function claim_listing_from_callback($listing_id = '', $before_html = '', $after_html = '') {
            global $post, $wp_dp_plugin_options, $wp_dp_html_fields_frontend, $wp_dp_form_fields_frontend, $Wp_dp_Captcha;
            wp_enqueue_script('wp-dp-validation-script');
            if ( $listing_id == '' ) {
                $listing_id = $post->ID;
            }

            $wp_dp_captcha_switch = isset($wp_dp_plugin_options['wp_dp_captcha_switch']) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';
            $wp_dp_term_policy_switch = isset($wp_dp_plugin_options['wp_dp_term_policy_switch']) ? $wp_dp_plugin_options['wp_dp_term_policy_switch'] : '';
            $wp_dp_sitekey = isset($wp_dp_plugin_options['wp_dp_sitekey']) ? $wp_dp_plugin_options['wp_dp_sitekey'] : '';
            $wp_dp_secretkey = isset($wp_dp_plugin_options['wp_dp_secretkey']) ? $wp_dp_plugin_options['wp_dp_secretkey'] : '';
            echo wp_dp_cs_allow_special_char($before_html);
            echo '<a class="claim-list" href="#" data-toggle="modal" data-target="#user-claim-listing"><i class="icon-edit3"></i>' . wp_dp_plugin_text_srt('wp_dp_claim') . '</a>';
            ?>
            <div class="modal fade modal-form" id="user-claim-listing" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="claimModalLabel"><?php echo wp_dp_plugin_text_srt('wp_dp_claim_listing'); ?></h4>
                        </div>
                        <div class="modal-body">
                            <form id="wp_dp_claim_listing">
                                <div class="row">
                                    <?php
                                    $user_name = '';
                                    $user_email = '';
                                    $user_login = false;
                                    $user_id = get_current_user_id();
                                    if ( is_user_logged_in() && $user_id ) {
                                        $company_id = get_user_meta($user_id, 'wp_dp_company', true);
                                        $user_name = esc_html(get_the_title($company_id));
                                        $user_email = get_post_meta($company_id, 'wp_dp_email_address', true);
                                        $user_login = true;
                                    }
                                    ?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-holder">
                                            <i class="icon-user2"></i>
                                            <?php
                                            $wp_dp_opt_array = array(
                                                'id' => 'claim_listing_user_name',
                                                'name' => 'claim_listing_user_name',
                                                'classes' => 'form-control wp-dp-dev-req-field',
                                                'std' => esc_html($user_name),
                                                'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_press(this,\'text\')"  placeholder="' . wp_dp_plugin_text_srt('wp_dp_claim_name') . ' *"',
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
                                                'id' => 'claim_listing_user_email',
                                                'name' => 'claim_listing_user_email',
                                                'classes' => 'form-control wp-dp-dev-req-field wp-dp-email-field',
                                                'std' => esc_html($user_email),
                                                'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_press(this,\'email\')"  placeholder="' . wp_dp_plugin_text_srt('wp_dp_claim_email') . ' *"',
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
                                                'description' => '',
                                                'id' => 'claim_listing_reason',
                                                'name' => 'claim_listing_reason',
                                                'classes' => 'wp-dp-dev-req-field',
                                                'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_press(this,\'text\')" placeholder="' . wp_dp_plugin_text_srt('wp_dp_claim_reason') . ' *"',
                                            );
                                            $wp_dp_form_fields_frontend->wp_dp_form_textarea_render($wp_dp_opt_array);
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                    $wp_dp_opt_array = array(
                                        'std' => admin_url('admin-ajax.php'),
                                        'description' => '',
                                        'id' => 'claim_ajax_url',
										'name' => 'url',
                                        'classes' => '',
                                        'cust_type' => 'hidden'
                                    );
                                    $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                                    $wp_dp_opt_array = array(
                                        'std' => $listing_id,
                                        'description' => '',
                                        'id' => 'claim_listing_id',
                                        'name' => 'listing_id',
                                        'classes' => '',
                                        'cust_type' => 'hidden'
                                    );
                                    $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                                    $wp_dp_opt_array = array(
                                        'std' => $user_login,
                                        'id' => 'claim_user_login',
                                        'name' => 'user_login',
                                        'classes' => '',
                                        'cust_type' => 'hidden'
                                    );
                                    $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                                    if ( $wp_dp_captcha_switch == 'on' ) {
                                        if ( $wp_dp_sitekey != '' && $wp_dp_secretkey != '' ) {
                                            wp_dp_google_recaptcha_scripts();
                                            ?>
                                            <script>
                                                var recaptcha_enquery;
                                                var wp_dp_multicap = function () {
                                                    //Render the recaptcha1 on the element with ID "recaptcha1"
                                                    recaptcha_enquery = grecaptcha.render('recaptcha_claim', {
                                                        'sitekey': '<?php echo ($wp_dp_sitekey); ?>', //Replace this with your Site key
                                                        'theme': 'light'
                                                    });

                                                };
                                            </script>
                    <?php
                }
                if ( class_exists('Wp_dp_Captcha') ) {
                    $output = '<div class="col-md-12 recaptcha-reload" id="recaptcha_claim_div">';
                    $output .= $Wp_dp_Captcha->wp_dp_generate_captcha_form_callback('recaptcha_claim', 'true');
                    $output .='</div>';
                    echo force_balance_tags($output);
                }
            }
            if ( $wp_dp_term_policy_switch == 'on' ) {
                ?>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="field-holder claim_term_policy">
                                        <?php wp_dp_term_condition_form_field('claim_term_policy_' . $listing_id, 'term_policy'); ?>
                                            </div>
                                        </div>
                                            <?php } ?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-holder claim-request-holder input-button-loader">
                                    <?php
                                    $wp_dp_opt_array = array(
                                        'std' => wp_dp_plugin_text_srt('wp_dp_claim_flag_send'),
                                        'id' => 'claim_listing_submit',
                                        'cust_name' => 'claim_listing_submit',
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
                            </form>

                        </div>
                    </div>

                </div>
            </div>
            <?php
            echo wp_dp_cs_allow_special_char($after_html);
        }

    }

    global $Wp_Dp_Claim_Listing;
    $Wp_Dp_Claim_Listing = new Wp_Dp_Claim_Listing();
}