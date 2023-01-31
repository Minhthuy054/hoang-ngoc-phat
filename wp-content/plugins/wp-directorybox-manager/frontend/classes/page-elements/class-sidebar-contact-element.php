<?php

/**

 * File Type: Listing Sidebar Member info Page Element

 */

if ( ! class_exists('wp_dp_sidebar_contact_element') ) {



    class wp_dp_sidebar_contact_element {



        /**

         * Start construct Functions

         */

        public function __construct() {

            add_action('wp_dp_sidebar_contact_html', array( $this, 'wp_dp_sidebar_contact_html_callback' ), 11, 2);

        }



        public function wp_dp_sidebar_contact_html_callback($listing_id = '', $view = '') {

            global $wp_dp_plugin_options, $wp_dp_form_fields_frontend, $Wp_dp_Captcha;

            wp_enqueue_script('wp-dp-validation-script');

            $sidebar_contact_info = wp_dp_element_hide_show($listing_id, 'sidebar_contact_info');

            if ( $sidebar_contact_info != 'on' ) {

                return;

            }

            

            /*

             * login case data fetch

             */

            

            $user_id = $company_id = 0;

            $user_id = get_current_user_id();

            $display_name = '';

            $email_address = '';

            if ( $user_id != 0 ) {

                $company_id = get_user_meta($user_id, 'wp_dp_company', true);

                $user_data = get_userdata($user_id);

                $display_name = esc_html(get_the_title($company_id));

                $email_address = get_post_meta($company_id, 'wp_dp_email_address', true);

            }

            /*

             * login case data fetch end

             */

            

            ?>

            <div class="contact-member-form member-detail">

                <?php

                $wp_dp_cs_email_counter = rand(100000, 900000);

                $listing_member = get_post_meta($listing_id, 'wp_dp_listing_member', true);

                $wp_dp_captcha_switch = isset($wp_dp_plugin_options['wp_dp_captcha_switch']) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';

                $wp_dp_sitekey = isset($wp_dp_plugin_options['wp_dp_sitekey']) ? $wp_dp_plugin_options['wp_dp_sitekey'] : '';

                $wp_dp_secretkey = isset($wp_dp_plugin_options['wp_dp_secretkey']) ? $wp_dp_plugin_options['wp_dp_secretkey'] : '';

                ?>

                <?php do_action('wp_dp_author_info_html', $listing_id, 'view-5'); ?>

                <form id="contactfrm<?php echo absint($wp_dp_cs_email_counter); ?>" class="contactform_name" name="contactform_name" onsubmit="return wp_dp_contact_send_message(<?php echo absint($wp_dp_cs_email_counter); ?>)">

                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">



                            <div class="field-holder">

                                <i class="icon- icon-user4"></i>

                                <?php

                                

                                $wp_dp_opt_array = array(

                                    'std' =>$display_name,

                                    'cust_name' => 'contact_full_name',

                                    'return' => false,

                                    'classes' => 'input-field wp-dp-dev-req-field',

                                    'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_press(this,\'text\')" placeholder=" ' . wp_dp_plugin_text_srt('wp_dp_member_contact_your_name') . '"',

                                );

                                $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                                ?>

                            </div>

                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="field-holder">

                                <i class="icon- icon-envelope3"></i>

                                <?php

                                $wp_dp_opt_array = array(

                                     'std' =>$email_address,

                                    'cust_name' => 'contact_email_add',

                                    'return' => false,

                                    'classes' => 'input-field wp-dp-dev-req-field wp-dp-email-field',

                                    'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_press(this,\'email\')"  placeholder=" ' . wp_dp_plugin_text_srt('wp_dp_member_contact_your_email') . '"',

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

                                    'id' => 'contact_message_field',

                                    'name' => '',

                                    'cust_name' => 'contact_message_field',

                                    'classes' => 'wp-dp-dev-req-field',

                                    'return' => false,

                                    'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_press(this,\'text\')" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_contact_your_message') . '"',

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

                                    var recaptcha_member;

                                    var wp_dp_multicap = function () {

                                        //Render the recaptcha1 on the element with ID "recaptcha1"

                                        recaptcha_member = grecaptcha.render('recaptcha_member_sidebar', {

                                            'sitekey': '<?php echo ($wp_dp_sitekey); ?>', //Replace this with your Site key

                                            'theme': 'light'

                                        });



                                    };

                                </script>

                                <?php

                            }

                            if ( class_exists('Wp_dp_Captcha') ) {

                                $output = '<div class="col-md-12 recaptcha-reload" id="member_sidebar_div">';

                                $output .= $Wp_dp_Captcha->wp_dp_generate_captcha_form_callback('member_sidebar', 'true');

                                $output .= '</div>';

                                echo force_balance_tags($output);

                            }

                        }

                        ?>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="field-holder">

                                <div class="contact-message-submit input-button-loader">

                                    <?php

                                    if ( is_user_logged_in() ) {

                                        $wp_dp_form_fields_frontend->wp_dp_form_text_render(

                                                array(

                                                    'cust_id' => 'message_submit',

                                                    'cust_name' => 'contact_message_submit',

                                                    'classes' => 'bgcolor',

                                                    'std' => wp_dp_plugin_text_srt('wp_dp_prop_detail_contact_cnt_agent') . '',

                                                    'cust_type' => "submit",

                                                )

                                        );

                                    } else {

                                        

                                        $wp_dp_form_fields_frontend->wp_dp_form_text_render(

                                                array(

                                                    'cust_id' => 'contact_message_submit',

                                                    'cust_name' => 'contact_message_submit',

                                                    'classes' => 'bgcolor wp-dp-open-signin-tab',

                                                    'std' => wp_dp_plugin_text_srt('wp_dp_prop_detail_contact_cnt_agent') . '',

                                                    'cust_type' => "button",

                                                )

                                        );

                                        ?>

                                       

                                        <?php

                                    }

                                    ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

            <?php

            $wp_dp_email_address = get_post_meta($listing_member, 'wp_dp_email_address', true);

            $error = wp_dp_plugin_text_srt('wp_dp_prop_detail_contact_error_mgs');

            $success = wp_dp_plugin_text_srt('wp_dp_prop_detail_contact_success_mgs');

            $wp_dp_cs_inline_script = '   

			function wp_dp_contact_send_message(form_id) {

                            

                                var returnType = wp_dp_validation_process(jQuery("#contactfrm' . ($wp_dp_cs_email_counter) . '"));

                                if (returnType == false) {

                                    return false;

                                }else{

				var wp_dp_cs_mail_id = \'' . esc_js($wp_dp_cs_email_counter) . '\';

				var thisObj = jQuery(".contact-message-submit");

				wp_dp_show_loader(".contact-message-submit", "", "button_loader", thisObj);

				if (form_id == wp_dp_cs_mail_id) {

					var $ = jQuery;

					var datastring = $("#contactfrm' . esc_js($wp_dp_cs_email_counter) . '").serialize() + "&wp_dp_member_email=' . esc_html($wp_dp_email_address) . '&wp_dp_cs_contact_succ_msg=' . esc_js($success) . '&wp_dp_cs_contact_error_msg=' . esc_js($error) . '&action=wp_dp_contact_message_send";

					$.ajax({

						type: \'POST\',

						url: \'' . esc_js(esc_url(admin_url('admin-ajax.php'))) . '\',

						data: datastring,

						dataType: "json",

						success: function (response) {

							wp_dp_show_response( response, "", thisObj);

						}

					});

				}

                                 return false;

                             }

			}';

            wp_dp_cs_inline_enqueue_script($wp_dp_cs_inline_script, 'wp-dp-custom-inline');

        }



    }



    global $wp_dp_sidebar_contact;

    $wp_dp_sidebar_contact = new wp_dp_sidebar_contact_element();

}