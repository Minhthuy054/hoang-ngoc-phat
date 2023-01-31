<?php
/**
 * File Type: Login Shortcode Frontend
 */
if ( ! class_exists('Wp_dp_Shortcode_Login_Frontend') ) {

    class Wp_dp_Shortcode_Login_Frontend {

        /**
         * Constant variables
         */
        var $PREFIX = 'wp_dp_login';
        var $LOGIN_OUTPUT = '';
        var $REGISTER_OUTPUT = '';

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_shortcode($this->PREFIX, array( $this, 'wp_dp_login_shortcode_callback' ));
            add_action($this->PREFIX, array( $this, 'wp_dp_login_callback' ), 11, 1);
        }

        public function wp_nav_menu_items_callback($items, $args) {
            global $post, $wp_dp_plugin_options, $wp_dp_theme_options;

            $wp_dp_html = '';
            echo wp_dp_allow_special_char($wp_dp_plugin_options['wp_dp_user_dashboard_switchs'] . 'ssdsddsd');
            //die();
            $wp_dp_user_dashboard_switchs = '';
            if ( isset($wp_dp_plugin_options['wp_dp_user_dashboard_switchs']) ) {
                $wp_dp_user_dashboard_switchs = $wp_dp_plugin_options['wp_dp_user_dashboard_switchs'];
                echo wp_dp_cs_allow_special_char($wp_dp_user_dashboard_switchs);
            }
            if ( $args->theme_location == 'primary' ) {

                ob_start();
                ?>
                <ul class="login-option">
                    <?php do_action('wp_dp_login'); ?>
                </ul>
                <?php
                $wp_dp_html .= ob_get_clean();
                if ( $wp_dp_user_dashboard_switchs == 'on' ) {
                    $items .= $wp_dp_html;
                }
            }
            return $items;
        }

        /*
         * Login hook calling shortcode
         */

        public function wp_dp_login_callback($view = '') {
            echo do_shortcode('[' . $this->PREFIX . ' header_view=' . $view . ']');
        }

        /*
         * Shortcode View on Frontend
         */

        public function wp_dp_login_shortcode_callback($atts, $content = "") {
            global $wpdb, $wp_dp_plugin_options, $wp_dp_form_fields_frontend, $wp_dp_form_fields_frontend, $wp_dp_html_fields;
            wp_enqueue_script('wp-dp-validation-script');
            wp_dp_socialconnect_scripts(); // social login script
            $defaults = array( 'column_size' => '1/1', 'title' => '', 'register_text' => '', 'register_role' => 'contributor', 'wp_dp_type' => '', 'wp_dp_login_txt' => '', 'login_btn_class' => '' );
            extract(shortcode_atts($defaults, $atts));

            $header_view = isset($atts['header_view']) ? $atts['header_view'] : '';

            $user_disable_text = wp_dp_plugin_text_srt('wp_dp_login_register_disabled');
            $wp_dp_sitekey = isset($wp_dp_plugin_options['wp_dp_sitekey']) ? $wp_dp_plugin_options['wp_dp_sitekey'] : '';
            $wp_dp_secretkey = isset($wp_dp_plugin_options['wp_dp_secretkey']) ? $wp_dp_plugin_options['wp_dp_secretkey'] : '';
            $wp_dp_captcha_switch = isset($wp_dp_plugin_options['wp_dp_captcha_switch']) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';

            $wp_dp_demo_user_login_switch = isset($wp_dp_plugin_options['wp_dp_demo_user_login_switch']) ? $wp_dp_plugin_options['wp_dp_demo_user_login_switch'] : '';
            if ( $wp_dp_demo_user_login_switch == 'on' ) {
                $wp_dp_demo_user_member = isset($wp_dp_plugin_options['wp_dp_demo_user_member']) ? $wp_dp_plugin_options['wp_dp_demo_user_member'] : '';
                $wp_dp_demo_user_agency = isset($wp_dp_plugin_options['wp_dp_demo_user_agency']) ? $wp_dp_plugin_options['wp_dp_demo_user_agency'] : '';
            }
            $rand_id = rand(13243, 99999);
            wp_dp_login_box_popup_scripts();
            if ( $wp_dp_sitekey <> '' and $wp_dp_secretkey <> '' and ! is_user_logged_in() ) {
                wp_dp_google_recaptcha_scripts();
                ?>
                <script>

                    var recaptcha2;

                    var recaptcha5;
                    var wp_dp_multicap = function () {
                        //Render the recaptcha1 on the element with ID "recaptcha1"
                        recaptcha2 = grecaptcha.render('recaptcha2', {
                            'sitekey': '<?php echo ($wp_dp_sitekey); ?>', //Replace this with your Site key
                            'theme': 'light'
                        });
                        //Render the recaptcha2 on the element with ID "recaptcha2"
                        recaptcha5 = grecaptcha.render('recaptcha5', {
                            'sitekey': '<?php echo ($wp_dp_sitekey); ?>', //Replace this with your Site key
                            'theme': 'light'
                        });
                    };

                </script>
                <?php
            }
            $output = '';
            if ( is_user_logged_in() ) {
                $output .= $this->wp_dp_profiletop_menu('', $header_view);

                if ( isset($_GET['reset_pass']) && $_GET['reset_pass'] == 'true' ) {
                    wp_redirect(home_url());
                    exit;
                }
            } else {
                wp_enqueue_script('wp-dp-login-script');
                $role = $register_role;
                $wp_dp_type = isset($wp_dp_type) ? $wp_dp_type : '';
                $wp_dp_login_class = 'login';
                $isRegistrationOn = get_option('users_can_register');

                $sign_in_label = wp_dp_plugin_text_srt('wp_dp_login_register_sign_in');
                $register_label = wp_dp_plugin_text_srt('wp_dp_register_register');
                if ( $header_view == 'fancy' ) {
                    $register_label = wp_dp_plugin_text_srt('wp_dp_header_join_nowww');
                }
                if ( $header_view != 'fancy' ) {
                    $output .= '<i class="icon-user-signup"></i>';
                    $output .= '<a id="btn-header-main-login" data-target="#sign-in" data-toggle="modal" class="popup-login-btn login-popup-btn wp-dp-open-signin-button user-tab-login" href="#user-login-tab-' . $rand_id . '">' . $sign_in_label . '</a>';
                    $output .= '<span>/</span>';
                }
                $output .= '<a class="color popup-joinus-btn login-popup-btn wp-dp-open-register-button user-tab-register" data-target="#sign-in" data-toggle="modal" href="#user-register-' . $rand_id . '">' . $register_label . '</a>';
                $login_btn_class_str = '';
                if ( $login_btn_class != '' ) {
                    $login_btn_class_str = 'class="' . $login_btn_class . '"';
                }
                /*
                 * Signin Popup Rendering
                 */
                $output_html = '';

                $output_html .= '<div class="modal fade" id="sign-in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                <div class="login-form login-form-element-' . $rand_id . '" data-id="' . $rand_id . '">
                                    
                                <div class="modal-content">';

                $output_html .= '<div class="tab-content">';


                // Signin Tab
                $output_html .= '<div id="user-login-tab-' . $rand_id . '" class="tab-pane fade">';

                $output_html .= '<div class="modal-header">
                                    <h1>' . wp_dp_plugin_text_srt('wp_dp_login_popup_sign_in') . '</h1>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true"><i class="icon-cross-out"></i></span> </button>
                               </div>';
                $output_html .= '<div class="modal-body">';
                $output_html .= '<div class="content-style-form cs-forgot-pbox-' . $rand_id . ' content-style-form-2" style="display:none;"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="login-detail">
                                            <h2>' . wp_dp_plugin_text_srt('wp_dp_login_register_reset_your_password') . '</h2>
                                            <p>' . wp_dp_plugin_text_srt('wp_dp_login_register_can_login') . '</p>
                                        </div>
                                    </div><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                ob_start();
                $output_html .= do_shortcode('[wp_dp_forgot_password wp_dp_type="popup"]');
                $output_html .= ob_get_clean();
                $output_html .= '<ul class="nav nav-tabs">
                    <li><span>' . wp_dp_plugin_text_srt('wp_dp_login_register_already_have_account') . '</span><a data-toggle="tab" data-id="' . $rand_id . '" class="cs-login-switch">' . wp_dp_plugin_text_srt('wp_dp_register_login_here') . '</a></li>
                </ul>';

                $output_html .= '</div></div></div>';
                $output_html .= '<p class="wp-dp-dev-login-top-msg" style="display: none;"></p>';

                $output_html .='<div class="cs-login-pbox-' . $rand_id . ' login-form-id-' . $rand_id . '">';
                $output_html .= '<div class="status status-message"></div>';
                $isRegistrationOn = get_option('users_can_register');
                if ( $isRegistrationOn ) {
                    
                }
                ob_start();
                $isRegistrationOn = get_option('users_can_register');
                // Social login switch options

                $twitter_login = isset($wp_dp_plugin_options['wp_dp_twitter_api_switch']) ? $wp_dp_plugin_options['wp_dp_twitter_api_switch'] : '';
                $facebook_login = isset($wp_dp_plugin_options['wp_dp_facebook_login_switch']) ? $wp_dp_plugin_options['wp_dp_facebook_login_switch'] : '';
                $google_login = isset($wp_dp_plugin_options['wp_dp_google_login_switch']) ? $wp_dp_plugin_options['wp_dp_google_login_switch'] : '';

                if ( $wp_dp_demo_user_login_switch == 'on' && $wp_dp_demo_user_member != '' && $wp_dp_demo_user_agency != '' ) {
                    $demo_user_password = esc_html('demo123');
                    $wp_dp_demo_member_detail = get_user_by('id', $wp_dp_demo_user_member);
                    $wp_dp_demo_agency_detail = get_user_by('id', $wp_dp_demo_user_agency);
                    require_once( ABSPATH . 'wp-includes/class-phpass.php');
                    $wp_hasher = new PasswordHash(8, TRUE);
                    if ( ! (isset($wp_dp_demo_member_detail->user_pass) && $wp_hasher->CheckPassword($demo_user_password, $wp_dp_demo_member_detail->user_pass)) ) {
                        wp_set_password($demo_user_password, $wp_dp_demo_user_member);
                    }
                    if ( ! (isset($wp_dp_demo_agency_detail->user_pass) && $wp_hasher->CheckPassword($demo_user_password, $wp_dp_demo_agency_detail->user_pass)) ) {
                        wp_set_password($demo_user_password, $wp_dp_demo_user_agency);
                    }
                    $wp_dp_demo_member_detail_user = isset($wp_dp_demo_member_detail->user_login) ? $wp_dp_demo_member_detail->user_login : '';
                    $wp_dp_demo_agency_detail_user = isset($wp_dp_demo_agency_detail->user_login) ? $wp_dp_demo_agency_detail->user_login : '';
                    $output_html .='<div class="cs-demo-login">';
                    $output_html .='<div class="cs-demo-login-lable">' . wp_dp_plugin_text_srt('wp_dp_register_login_demo');
                    $output_html .= '</div>';
                    $output_html .= '<ul class="login-switches">';
                    $output_html .= '<li>';
                    $output_html .= '<a class="demo-login-agency-' . $rand_id . '" href="javascript:void(0)" onclick="javascript:wp_dp_demo_user_login(\'' . $wp_dp_demo_agency_detail_user . '\',\'.demo-login-agency-' . $rand_id . '\')" '
                            . '><i class="icon-location_city"></i>' . wp_dp_plugin_text_srt('wp_dp_register_login_demo_user')
                            . '</a>';
                    $output_html .= '</li>';
                    $output_html .= '</ul>';


                    $output_html .='</div>';
                    $output_html .='<script>
                        function wp_dp_demo_user_login(user, buttonloader) {
                            jQuery("#user_login' . $rand_id . '" ).val(user);
                            jQuery("#user_pass' . $rand_id . '" ).val("' . $demo_user_password . '");
                            wp_dp_user_authentication(\'' . admin_url("admin-ajax.php") . '\',\'' . $rand_id . '\',buttonloader);
                        }
                    </script>';
                }


                $output_html .= '<div class="flex-user-form">';
                $output_html .= do_action('login_form', array( 'form_rand_id' => $rand_id ));
                $output_html .= ob_get_clean();

                if ( $isRegistrationOn && ($twitter_login == 'on' || $facebook_login == 'on' || $google_login == 'on') ) {
                    
                }

                if ( is_user_logged_in() ) {
                    $output_html .='<script>'
                            . 'jQuery("body").on("keypress", "input#user_login' . absint($rand_id) . ', input#user_pass' . absint($rand_id) . '", function (e) {
                                        if (e.which == "13") {
                                            show_alert_msg("' . wp_dp_plugin_text_srt('wp_dp_register_logout_first') . '");
                                            return false;
                                        }
                                });'
                            . '</script>';
                } else {
                    $output_html .='<script>'
                            . 'jQuery("body").on("keypress", "input#user_login' . absint($rand_id) . ', input#user_pass' . absint($rand_id) . '", function (e) {
                                    if (e.which == "13") {
                                        wp_dp_user_authentication("' . esc_url(admin_url("admin-ajax.php")) . '", "' . absint($rand_id) . '", \'.ajax-login-button\');
                                        return false;
                                    }
                                });'
                            . '</script>';
                }

                $output_html .='<form method="post" class="wp-user-form webkit" id="ControlForm_' . $rand_id . '">';
                if ( defined('ICL_LANGUAGE_CODE') ) {
                    $output_html .=wp_dp_wpml_lang_code_field();
                }




                $output_html .='<div class="input-filed">';
                $output_html .='<i class="icon-directory-user"></i>';
                $wp_dp_opt_array = array(
                    'id' => '',
                    'std' => '',
                    'cust_id' => 'user_login' . $rand_id,
                    'cust_name' => 'user_login',
                    'classes' => 'form-control wp-dp-dev-req-field',
                    'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_press(this,\'text\')" tabindex="11" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_username') . '"',
                    'return' => true,
                );
                $output_html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                $output_html .='</div>';

                $output_html .='<div class="input-filed">';
                $output_html .='<i class="icon-directory-lock"></i>';
                $wp_dp_opt_array = array(
                    'id' => '',
                    'std' => '',
                    'cust_id' => 'user_pass' . $rand_id,
                    'cust_name' => 'user_pass',
                    'cust_type' => 'password',
                    'classes' => 'form-control wp-dp-dev-req-field',
                    'extra_atr' => ' placeholder=" ' . wp_dp_plugin_text_srt('wp_dp_register_password') . '" onkeypress="wp_dp_contact_form_valid_press(this,\'text\')" tabindex="12" size="20"',
                    'return' => true,
                );
                $output_html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                $output_html .='</div>';
                $output_html .='<div class="input-holder">
                                                        <div class="check-box-dpind">
                                                            <input class="input-field" type="checkbox" id="remember">
                                                            <label for="remember">' . wp_dp_plugin_text_srt('wp_dp_login_register_dpember_me') . '</label>
                                                        </div>';
                $output_html .='<div class="forget-password"><i class="icon-help"></i><a data-id="' . $rand_id . '" class="cs-forgot-switch">' . wp_dp_plugin_text_srt('wp_dp_register_forgot_password') . '</a></div>';
                $output_html .='</div>';
                if ( is_user_logged_in() ) {
                    $output_html .='<div class="input-filed">';
                    $wp_dp_opt_array = array(
                        'std' => wp_dp_plugin_text_srt('wp_dp_register_login'),
                        'cust_name' => 'user-submit',
                        'cust_type' => 'button',
                        'classes' => 'bgcolor',
                        'extra_atr' => ' onclick="javascript:show_alert_msg(\'' . wp_dp_plugin_text_srt('wp_dp_register_logout_first') . '\')"',
                        'return' => true,
                    );
                    $output_html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                    $output_html .= '</div>';
                } else {
                    $output_html .='<div class="input-filed">';
                    $output_html .='<div class="ajax-login-button input-button-loader">';
                    $wp_dp_opt_array = array(
                        'std' => wp_dp_plugin_text_srt('wp_dp_register_login'),
                        'cust_name' => 'user-submit',
                        'cust_type' => 'button',
                        'classes' => 'bgcolor',
                        'extra_atr' => ' onclick="javascript:wp_dp_user_authentication(\'' . admin_url("admin-ajax.php") . '\', \'' . $rand_id . '\', \'.ajax-login-button\')"',
                        'return' => true,
                    );
                    $output_html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                    $wp_dp_opt_array = array(
                        'id' => '',
                        'std' => get_permalink(),
                        'cust_id' => 'redirect_to',
                        'cust_name' => 'redirect_to',
                        'cust_type' => 'hidden',
                        'return' => true,
                    );
                    $output_html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                    $wp_dp_opt_array = array(
                        'id' => '',
                        'std' => '1',
                        'cust_id' => 'user-cookie',
                        'cust_name' => 'user-cookie',
                        'cust_type' => 'hidden',
                        'return' => true,
                    );
                    $output_html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                    $wp_dp_opt_array = array(
                        'id' => '',
                        'std' => 'ajax_login',
                        'cust_name' => 'action',
                        'cust_type' => 'hidden',
                        'return' => true,
                    );
                    $output_html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                    $wp_dp_opt_array = array(
                        'id' => '',
                        'std' => 'login',
                        'cust_id' => 'login',
                        'cust_name' => 'login',
                        'cust_type' => 'hidden',
                        'return' => true,
                    );
                    $output_html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                    $wp_dp_opt_array = array(
                        'id' => '',
                        'std' => get_the_permalink(),
                        'cust_id' => 'login-redirect-page',
                        'cust_name' => 'login-redirect-page',
                        'cust_type' => 'hidden',
                        'return' => true,
                    );
                    $output_html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                    $output_html .= '
				</div>
			</div>';
                }
                $output_html .='</form>';
                $output_html .= '</div>';
                $output_html .= '<ul class="nav nav-tabs">
                    <li><span>' . wp_dp_plugin_text_srt('wp_dp_login_form_dont_have_account') . '</span><a data-toggle="tab" href="#user-register-' . $rand_id . '" class="user-tab-register">' . wp_dp_plugin_text_srt('wp_dp_register_register') . '</a></li>
                </ul>';
                $output_html .= '</div>'; // end flex-user-form
                $output_html .= '</div>';


                $output_html .= '</div>';
                //End Signin Tab
                // Signup Tab
                $output_html .= '<div id="user-register-' . $rand_id . '" class="tab-pane fade">';

                $output_html .= $this->wp_dp_registration_tab($rand_id);

                $output_html .='
                </div>';
                //End Signup Tab
                //Forgot Password Tab
                $output_html .='<div id="user-password' . $rand_id . '" class="tab-pane fade">';


                $output_html .='
                </div>';
                //End Password Tab


                $output_html .= '</div>';

                $output_html .='
                </div>';

                $output_html .='
                </div>';

                $output_html .='
            </div></div>';
                $data = get_transient('social_data');
                delete_transient('social_data');
                if ( $data != false ) {
                    ob_start();
                    $status_message = wp_dp_plugin_text_srt('wp_dp_register_sorry') . ' ' . ucfirst($data['social_login_provider']) . ' ' . wp_dp_plugin_text_srt('wp_dp_register_sorry_text');
                    if ( isset($data['user_email']) && ! empty($data['user_email']) ) {
                        $status_message = wp_dp_plugin_text_srt('wp_dp_login_register_confirm_below_info_for_register');
                    }
                    ?>
                    <script type="text/javascript">
                        (function ($) {
                            $(function () {
                                var rand_id = window.rand_id_registration;
                                $("input[name='user_login" + rand_id + "']").val('<?php echo esc_html($data['user_login']); ?>');
                                $("input[name='wp_dp_display_name" + rand_id + "']").val('<?php echo esc_html($data['first_name']) . ' ' . esc_html($data['last_name']); ?>');
                                $("input[name='wp_dp_user_email" + rand_id + "']").val('<?php echo esc_html($data['user_email']); ?>');
                                $(".status-message").addClass('text-danger').html('<?php echo esc_html($status_message); ?>');
                                $("#signin-role").after('<input type="hidden" name="social_meta_key" value="<?php echo esc_html($data['social_meta_key']); ?>">');
                                $("#signin-role").after('<input type="hidden" name="social_meta_value" value="<?php echo esc_html($data['social_meta_value']); ?>">');
                                $(".wp-dp-open-register-button").click();
                            });
                        })(jQuery);
                    </script>

                    <?php
                    $output_html .= ob_get_clean();
                }
                if ( isset($_GET['reset_pass']) && $_GET['reset_pass'] == 'true' && isset($_GET['popup']) && $_GET['popup'] == 'false' ) {
                    if ( is_user_logged_in() ) {
                        wp_redirect(home_url());
                        exit;
                    }
                    ob_start();
                    ?>
                    <script type="text/javascript">
                        (function ($) {
                            $(function () {
                                $(".cs-forgot-switch").click();
                            });
                        })(jQuery);
                    </script>

                    <?php
                    $output_html .= ob_get_clean();
                } else if ( isset($_GET['reset_pass']) && $_GET['reset_pass'] == 'true' ) {
                    if ( is_user_logged_in() ) {
                        wp_redirect(home_url());
                        exit;
                    }
                    ob_start();
                    ?>
                    <script type="text/javascript">
                        (function ($) {
                            $(function () {
                                if ($(".popup-login-btn").length > 0) {
                                    $(".popup-login-btn").click();
                                } else if ($(".wp-dp-open-register-button").length > 0) {
                                    $(".wp-dp-open-register-button").click();
                                    $(".cs-login-switch").click();
                                    $(".user-tab-login").click();
                                }
                                $(".cs-forgot-switch").click();
                            });
                        })(jQuery);
                    </script>

                    <?php
                    $output_html .= ob_get_clean();
                }

                $this->LOGIN_OUTPUT = $output_html;

                if ( ! class_exists('wp_dp_framework') && ! is_user_logged_in() ) {
                    $output .= $output_html;
                } else {
                    $this->wp_dp_popup_into_footer();
                }
            }
            return $output;
        }

        public function wp_dp_registration_tab($rand_id = '') {
            global $wp_dp_form_fields_frontend, $wp_dp_html_fields, $wp_dp_plugin_options;
            $wp_dp_captcha_switch = '';
            $wp_dp_captcha_switch = isset($wp_dp_plugin_options['wp_dp_captcha_switch']) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';
            $wp_dp_sitekey = isset($wp_dp_plugin_options['wp_dp_sitekey']) ? $wp_dp_plugin_options['wp_dp_sitekey'] : '';
            $wp_dp_secretkey = isset($wp_dp_plugin_options['wp_dp_secretkey']) ? $wp_dp_plugin_options['wp_dp_secretkey'] : '';
            $output_html = '';
            $role = '';
            $register_text = '';
            $user_disable_text = wp_dp_plugin_text_srt('wp_dp_login_register_disabled');
            $content = '';
            $output_html .= '<div class="modal-header">
				<h1>' . wp_dp_plugin_text_srt('wp_dp_register_signup') . '</h1>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true"><i class="icon-cross-out"></i></span> </button>
			</div>';
            $output_html .='<div class="modal-body">';
            $isRegistrationOn = get_option('users_can_register');
            $popup_register_rand_divids = rand(0, 999999);
            if ( $isRegistrationOn ) {

                $rand_ids = rand(0, 999999);

                $rand_ids = $rand_id;

                // popup registration forms
                // popup member registration form
                $output_html .='<div id="member' . $popup_register_rand_divids . '" role="tabpanel" class="tab-pane active">';
                $output_html .= '<div id="result_' . $rand_ids . '" class="status-message"></div>';
                $output_html .='<script>'
                        . 'window.rand_id_registration = \'' . $rand_ids . '\';
                        jQuery("body").on("keypress", "input#wp_dp_company_name' . absint($rand_ids) . ', input#user_login_' . absint($rand_ids) . ', input#wp_dp_display_name' . absint($rand_ids) . ', input#wp_dp_user_email' . absint($rand_ids) . '", function (e) {
                                                                            if (e.which == "13") {
                                                                                    wp_dp_registration_validation("' . esc_url(admin_url("admin-ajax.php")) . '", "' . absint($rand_ids) . '", \'.ajax-signup-button\');
                                                                                    return false;
                                                                            }
                                                                            });'
                        . '</script>';
                ob_start();
                $output_html .= '<div class="flex-user-form">';
                if ( class_exists('wp_dp') ) {
                    //$output_html .= do_action('login_form', array( 'form_rand_id' => $rand_id ));
                }
                $output_html .= ob_get_clean();
                $key = wp_dp_get_input('key', NULL, 'STRING');
                if ( $key != NULL ) {
                    $key_data = get_option($key);
                    $output_html .= '<script>jQuery(document).ready(function($){$("#join-us").modal("show")}); </script>';
                }


                $demo_user_login = isset($wp_dp_plugin_options['wp_dp_demo_user_login_switch']) ? $wp_dp_plugin_options['wp_dp_demo_user_login_switch'] : 'off';
                if ( $demo_user_login == 'on' ) {
                    $wp_dp_demo_user_agency = isset($wp_dp_plugin_options['wp_dp_demo_user_agency']) ? $wp_dp_plugin_options['wp_dp_demo_user_agency'] : '';
                    if ( $wp_dp_demo_user_agency == '' || $wp_dp_demo_user_agency == 0 ) {
                        $demo_user_login = 'off';
                    }
                }

                if ( $demo_user_login == 'on' ) {
                    $output_html .= '<div class="registration-disabled-msg"><p>' . wp_dp_plugin_text_srt('wp_dp_registration_disabled_login_with_demo_user') . '</p></div>';
                } else {

                    $output_html .='<form method="post" class="wp-user-form demo_test" id="wp_signup_form_' . $rand_ids . '" enctype="multipart/form-data">';

                    $output_html .='<div class="input-filed">';
                    $output_html .='<i class="icon-dp-id-card"></i>';
                    $wp_dp_opt_array = array(
                        'id' => '',
                        'std' => '',
                        'cust_id' => 'user_login_' . $rand_ids,
                        'cust_name' => 'user_login' . $rand_ids,
                        'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_press(this,\'text\')" placeholder="' . wp_dp_plugin_text_srt('wp_dp_register_username') . '"',
                        'classes' => 'wp-dp-dev-req-field',
                        'return' => true,
                    );
                    $output_html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                    $output_html .= '
                                </div>';

                    if ( $key == NULL ) {
                        $output_html .='<div class="input-filed display-name-field">';
                        $output_html .='<i class="icon-directory-user"></i>';
                        $output_html .=$wp_dp_form_fields_frontend->wp_dp_form_text_render(
                                array( 'name' => wp_dp_plugin_text_srt('wp_dp_register_display_name'),
                                    'id' => 'display_name' . $rand_ids,
                                    'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_register_display_name') . '"',
                                    'std' => '',
                                    'classes' => '',
                                    'return' => true,
                                )
                        );
                        $output_html .= '</div>';
                    }
                    $output_html .= '<script>jQuery(window).load(function($){'
                            . 'if (jQuery(".chosen-select, .chosen-select-deselect, .chosen-select-no-single, .chosen-select-no-results, .chosen-select-width").length != "") {
                                var config = {
                                    ".chosen-select": {
                                        width: "100%"
                                    },
                                    ".chosen-select-deselect": {
                                        allow_single_deselect: true
                                    },
                                    ".chosen-select-no-single": {
                                        disable_search_threshold: 10,
                                        width: "100%"
                                    },
                                    ".chosen-select-no-results": {
                                        no_results_text: "Oops, nothing found!"
                                    },
                                    ".chosen-select-width": {
                                        width: "95%"
                                    }
                                };
                                for (var selector in config) {
                                    jQuery(selector).chosen(config[selector]);
                                }
                            }'
                            . '}); </script>';

                    $output_html .='<div class="input-filed">';
                    $output_html .='<i class="icon-directory-envelope"></i>';
                    $readonly = ( isset($key_data['email']) ) ? 'readonly' : '';
                    $output_html .=$wp_dp_form_fields_frontend->wp_dp_form_text_render(
                            array( 'name' => wp_dp_plugin_text_srt('wp_dp_register_email'),
                                'id' => 'user_email' . $rand_ids,
                                'extra_atr' => '  onkeypress="wp_dp_contact_form_valid_press(this,\'email\')" placeholder="' . wp_dp_plugin_text_srt('wp_dp_register_email') . '"' . $readonly . '',
                                'std' => ( isset($key_data['email']) ) ? $key_data['email'] : '',
                                'return' => true,
                                'classes' => 'wp-dp-dev-req-field wp-dp-email-field',
                            )
                    );
                    $output_html .= '</div>';

                    $output_html .=$wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                            array( 'name' => wp_dp_plugin_text_srt('wp_dp_login_register_user_role_type'),
                                'id' => 'user_role_type' . $rand_ids,
                                'classes' => 'input-holder',
                                'std' => 'member',
                                'description' => '',
                                'return' => true,
                                'hint' => '',
                                'icon' => 'icon-user9'
                            )
                    );

                    $output_html .='<div class="side-by-side select-icon clearfix">';
                    $output_html .='<div class="select-holder">';

                    $output_html .='</div>';
                    $output_html .='</div>';


                    if ( $wp_dp_captcha_switch == 'on' && ( ! is_user_logged_in()) ) {
                        if ( $wp_dp_sitekey <> '' and $wp_dp_secretkey <> '' and ! is_user_logged_in() ) {
                            wp_dp_google_recaptcha_scripts();
                            ?>
                            <script>
                                var recaptcha10;
                                var wp_dp_multicap = function () {
                                    //Render the recaptcha1 on the element with ID "recaptcha1"
                                    recaptcha10 = grecaptcha.render('recaptcha10', {
                                        'sitekey': '<?php echo ($wp_dp_sitekey); ?>', //Replace this with your Site key
                                        'theme': 'light'
                                    });

                                };
                            </script>
                            <?php
                        }
                        if ( class_exists('Wp_dp_Captcha') ) {
                            global $Wp_dp_Captcha;
                            $output_html .='<div class="recaptcha-reload" id="recaptcha10_div">';
                            $output_html .= $Wp_dp_Captcha->wp_dp_generate_captcha_form_callback('recaptcha10', 'true');
                            $output_html .='</div>';
                        }
                    }
                    $output_html .= '<div class="checks-holder">';
                    ob_start();
                    $output_html .= do_action('register_form');
                    $output_html .= ob_get_clean();
                    $wp_dp_rand_id = rand(122, 1545464897);

                    $output_html .= '<div class="input-filed">';
                    $output_html .= '<div class="ajax-signup-button-' . $rand_id . ' input-button-loader">';
                    $wp_dp_opt_array = array(
                        'std' => wp_dp_plugin_text_srt('wp_dp_register_signup'),
                        'cust_id' => 'submitbtn' . $wp_dp_rand_id,
                        'cust_name' => 'user-submit',
                        'cust_type' => 'button',
                        'classes' => 'user-submit bgcolor acc-submit',
                        'extra_atr' => ' tabindex="103" onclick="javascript:wp_dp_registration_validation(\'' . admin_url("admin-ajax.php") . '\', \'' . $rand_ids . '\', \'.ajax-signup-button-' . $rand_id . '\')"',
                        'return' => true,
                    );
                    $output_html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                    $wp_dp_opt_array = array(
                        'id' => '',
                        'std' => $role,
                        'cust_id' => 'signin-role',
                        'cust_name' => 'role',
                        'cust_type' => 'hidden',
                        'return' => true,
                    );
                    $output_html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                    $wp_dp_opt_array = array(
                        'id' => '',
                        'std' => 'wp_dp_registration_validation',
                        'cust_name' => 'action',
                        'cust_type' => 'hidden',
                        'return' => true,
                    );
                    $output_html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                    if ( $key != NULL ) {
                        $wp_dp_opt_array = array(
                            'id' => '',
                            'std' => $key,
                            'cust_name' => 'key',
                            'cust_type' => 'hidden',
                            'return' => true,
                        );
                        $output_html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                    }
                    $output_html .= '</div>';
                    $output_html .= '</div>';
                    $output_html .= '</div>';

                    $output_html .= '</form>
                                        <div class="register_content">' . do_shortcode($content . $register_text) . '</div>';

                    $output_html .='</div>';
                }
                $output_html .='</div>'; //end flex-user-form
                $output_html .= '<ul class="nav nav-tabs">
                    <li><span>' . wp_dp_plugin_text_srt('wp_dp_login_form_already_have_account') . '</span><a data-toggle="tab" href="#user-login-tab-' . $rand_id . '"  class ="user-tab-login">' . wp_dp_plugin_text_srt('wp_dp_register_signin_here') . '</a></li>
                </ul>';
            } else {
                $output_html .='<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 register-page">
                                            <div class="cs-user-register">
                                                <div class="element-title">
                                                       <h2>' . wp_dp_plugin_text_srt('wp_dp_register_register') . '</h2>
                                               </div>
                                               <p>' . $user_disable_text . '</p>
                                            </div>
                                        </div>
                                </div>';
                $output_html .='</div>';
            }
            $output_html .= '</div>';
            return $output_html;
        }

        public function wp_dp_registration_popup() {
            global $wp_dp_form_fields_frontend, $wp_dp_html_fields;
            $rand_id = rand(0, 999999);

            $output = '';
            $user_disable_text = wp_dp_plugin_text_srt('wp_dp_login_register_disabled');
            $output .= '<div class="modal fade" id="join-us' . absint($rand_id) . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                              <div class="modal-dialog" role="document">
                              <div class="login-form">
                                <div class="modal-content">
                                
                                  <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                   
                                    </div>';
            $output .= '<div class="modal-body">';
            $isRegistrationOn = get_option('users_can_register');
            $popup_register_rand_divids = rand(0, 999999);
            if ( $isRegistrationOn ) {

                $rand_ids = rand(0, 999999);

                // popup registration forms
                $output .='<div class="tab-content">';

                // popup member registration form
                $output .='<div id="member' . $popup_register_rand_divids . '" role="tabpanel" class="tab-pane active">';
                $output .= '<div id="result_' . $rand_ids . '" class="status-message"></div>';
                $output .='<script>'
                        . 'jQuery("body").on("keypress", "input#wp_dp_company_name' . absint($rand_ids) . ', input#user_login_' . absint($rand_ids) . ', input#wp_dp_display_name' . absint($rand_ids) . ', input#wp_dp_user_email' . absint($rand_ids) . '", function (e) {
				if (e.which == "13") {
						wp_dp_registration_validation("' . esc_url(admin_url("admin-ajax.php")) . '", "' . absint($rand_ids) . '", \'.ajax-signup-button\', \'.ajax-signup-button\');
						return false;
				}
				});'
                        . '</script>';
                ob_start();
                if ( class_exists('wp_dp') ) {
                    $output .= do_action('login_form');
                }
                $output .= ob_get_clean();
                $output .='<form method="post" class="wp-user-form demo_test" id="wp_signup_form_' . $rand_ids . '" enctype="multipart/form-data">';
                $output .='<div class="input-filed">';
                $key = wp_dp_get_input('key', NULL, 'STRING');
                if ( $key != NULL ) {

                    $key_data = get_option($key);
                }
                $wp_dp_opt_array = array(
                    'id' => '',
                    'std' => '',
                    'cust_id' => 'user_login_' . $rand_ids,
                    'cust_name' => 'user_login' . $rand_ids,
                    'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_username') . '"',
                    'classes' => 'form-control',
                    'return' => true,
                );
                $output .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                $output .= '
                        </div>';

                $output .='<div class="input-filed">';
                $output .=$wp_dp_form_fields_frontend->wp_dp_form_text_render(
                        array( 'name' => wp_dp_plugin_text_srt('wp_dp_register_display_name'),
                            'id' => 'display_name' . $rand_ids,
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_register_display_name') . '"',
                            'std' => '',
                            'return' => true,
                        )
                );
                $output .= '</div>';

                $output .='<div class="input-filed">';
                $readonly = ( isset($key_data['email']) ) ? 'readonly' : '';
                $output .=$wp_dp_form_fields_frontend->wp_dp_form_text_render(
                        array( 'name' => wp_dp_plugin_text_srt('wp_dp_register_email'),
                            'id' => 'user_email' . $rand_ids,
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_register_email') . '"' . $readonly . '',
                            'std' => ( isset($key_data['email']) ) ? $key_data['email'] : '',
                            'return' => true,
                        )
                );
                $output .= '</div>';

                $output .='<div class="input-filed">';
                $output .=$wp_dp_form_fields_frontend->wp_dp_form_text_render(
                        array( 'name' => wp_dp_plugin_text_srt('wp_dp_register_password'),
                            'id' => 'user_password' . $rand_ids,
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_register_password') . '"',
                            'std' => '',
                            'cust_type' => 'password',
                            'return' => true,
                        )
                );
                $output .= '</div>';


                $output .=$wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                        array( 'name' => wp_dp_plugin_text_srt('wp_dp_login_register_user_role_type'),
                            'id' => 'user_role_type' . $rand_ids,
                            'classes' => 'input-holder',
                            'std' => 'member',
                            'description' => '',
                            'return' => true,
                            'hint' => '',
                            'icon' => 'icon-user9'
                        )
                );

                $output .='<div class="side-by-side select-icon clearfix">';
                $output .='<div class="select-holder">';

                $output .='</div>';
                $output .='</div>';
                $output .='<div class="input-filed phone">';
                $output .=$wp_dp_form_fields_frontend->wp_dp_form_text_render(
                        array( 'name' => wp_dp_plugin_text_srt('listing_contact_phone'),
                            'id' => 'phone_no' . $rand_ids,
                            'std' => '',
                            'extra_atr' => ' placeholder=" ' . wp_dp_plugin_text_srt('listing_contact_phone') . '"',
                            'return' => true,
                        )
                );
                $output .='</div>';
                if ( $wp_dp_captcha_switch == 'on' && ( ! is_user_logged_in()) ) {
                    if ( class_exists('Wp_dp_Captcha') ) {
                        global $Wp_dp_Captcha;
                        $output .='<div class="col-md-12 recaptcha-reload" id="recaptcha5_div">';
                        $output .= $Wp_dp_Captcha->wp_dp_generate_captcha_form_callback('recaptcha5', 'true');
                        $output .='</div>';
                    }
                }
                $output .= '<div class="checks-holder">';
                ob_start();
                $output .= do_action('register_form');
                $output .= ob_get_clean();
                $wp_dp_rand_id = rand(122, 1545464897);
                $output .= '<div class="input-filed">';
                $output .= '<div class="ajax-signup-button input-button-loader">';
                $wp_dp_opt_array = array(
                    'std' => wp_dp_plugin_text_srt('wp_dp_register_signup'),
                    'cust_id' => 'submitbtn' . $wp_dp_rand_id,
                    'cust_name' => 'user-submit',
                    'cust_type' => 'button',
                    'classes' => 'user-submit bgcolor acc-submit',
                    'extra_atr' => ' tabindex="103" onclick="javascript:wp_dp_registration_validation(\'' . admin_url("admin-ajax.php") . '\',\'' . $rand_ids . '\',\'.ajax-signup-button\')"',
                    'return' => true,
                );
                $output .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                $wp_dp_opt_array = array(
                    'id' => '',
                    'std' => $role,
                    'cust_id' => 'signin-role',
                    'cust_name' => 'role',
                    'cust_type' => 'hidden',
                    'return' => true,
                );
                $output .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                $wp_dp_opt_array = array(
                    'id' => '',
                    'std' => 'wp_dp_registration_validation',
                    'cust_name' => 'action',
                    'cust_type' => 'hidden',
                    'return' => true,
                );
                $output .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                if ( $key != NULL ) {
                    $wp_dp_opt_array = array(
                        'id' => '',
                        'std' => $key,
                        'cust_name' => 'key',
                        'cust_type' => 'hidden',
                        'return' => true,
                    );
                    $output .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                }

                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';

                $output .= '</form>
                                <div class="register_content">' . do_shortcode($content . $register_text) . '</div>';
                $output .='</div>';

                $output .='</div>';
            } else {
                $output .='<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 register-page">
                                <div class="cs-user-register">
                                    <div class="element-title">
                                           <h2>' . wp_dp_plugin_text_srt('wp_dp_register_register') . '</h2>
                                   </div>
                                   <p>' . $user_disable_text . '</p>
                                </div>
                            </div>
                        </div>';
                $output .='</div>';
            }
            $output .= '</div>';
            $output .= '</div>';

            $output .= '
                      </div></div>
                                </div>
                          ';

            echo wp_dp_cs_allow_special_char($output);
        }

        /*
         * Calling Footer Hook
         */

        public function wp_dp_popup_into_footer() {
            add_action('wp_footer', array( $this, 'wp_dp_footer_callback' ));
        }

        /*
         * Outputting Signin and Registration Popups into footer
         */

        public function wp_dp_footer_callback() {
            echo ($this->LOGIN_OUTPUT);
            echo ($this->REGISTER_OUTPUT);
        }

        public function wp_dp_dashboar_top_menu_url($url_param = '') {
            $pageid = get_the_ID();
            $final_url = '';
            $dashboard_page_link = wp_dp_user_dashboard_page_url('id');
            $dashboard_url_off = 0;
            if ( $dashboard_page_link == $pageid ) {
                $dashboard_url_off = 1;
            }
            if ( $url_param != '' ) {
                $url_param = '?' . $url_param;
            }
            if ( $dashboard_url_off == 1 ) {
                $final_url = 'javascript:void(0);';
            } else {
                $dashboard_page_link = wp_dp_user_dashboard_page_url('url');
                $final_url = ( $dashboard_page_link . $url_param );
            }

            return $final_url;
        }

        /**
         * Start Function how to add user profile menu in top position
         */
        public function wp_dp_profiletop_menu($uid = '', $header_view = '') {
            global $post, $cs_plugin_options, $current_user, $wp_roles, $userdata, $wp_dp_member_profile, $wp_dp_plugin_options;
            if ( is_user_logged_in() ) {

                $menu_cls = '';
                $uid = (isset($uid) and $uid <> '') ? $uid : $current_user->ID;
                $user_display_name = get_the_author_meta('display_name', $uid);
                $cs_page_id = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
                $user_company = get_user_meta($uid, 'wp_dp_company', true);
                $fullName = isset($user_company) && $user_company != '' ? get_the_title($user_company) : '';

                if ( strlen($fullName) > 10 ) {
                    $fullName = substr($fullName, 0, 10) . "...";
                }
                $wp_dp_profile_image = $wp_dp_member_profile->member_get_profile_image($uid);
                $wp_dp_user_type = get_user_meta($current_user->ID, 'wp_dp_user_type', true);
                $user_roles = isset($current_user->roles) ? $current_user->roles : '';
                $dashboard_page_link = wp_dp_user_dashboard_page_url();
                $wp_dp_listing_add_url = $dashboard_page_link != '' ? add_query_arg(array( 'tab' => 'add-listing' ), $dashboard_page_link) : '#';

                if ( $wp_dp_profile_image == '' ) {
                    $wp_dp_profile_image = wp_dp::plugin_url() . '/assets/frontend/images/member-no-image.jpg';
                }
                ?>
                <div class="user-dashboard-menu">
                    <ul>
                        <li class="user-dashboard-menu-children">
                            <a href="">
                                <?php
                                if ( $wp_dp_profile_image != '' ) {
                                    if ( is_numeric($wp_dp_profile_image) ) {
                                        $wp_dp_profile_image = wp_get_attachment_url($wp_dp_profile_image);
                                    }
                                    echo '<div class="img-holder"><figure class="profile-image"><img src="' . esc_url($wp_dp_profile_image) . '" alt="' . wp_dp_plugin_text_srt('wp_dp_member_profile_image') . '"></figure></div>';
                                }

                                if ( $header_view != 'advance_v2' ) {
                                    //echo esc_html($fullName); 
                                    ?>
                                    <i class="icon-caret-down"></i>
                                <?php } ?>
                            </a>
                            <?php if ( ($user_roles != '' && in_array("wp_dp_member", $user_roles) ) || in_array('administrator', $user_roles) ) {
                                ?>
                                <ul>
                                    <?php if ( true === Wp_dp_Member_Permissions::check_permissions('listings') ) { ?>
                                                                                                                                                                                                                                <!--<li class="user-add-listing"><a href="<?php echo esc_url_raw($wp_dp_listing_add_url) ?>" ><i class="icon-add-listings"></i> <?php echo wp_dp_plugin_text_srt('wp_dp_login_register_add_new_add'); ?> </a></li>-->
                                        <?php
                                    }
                                    $dashboard_url = '';
                                    $dashboard_url = $this->wp_dp_dashboar_top_menu_url();
                                    if ( isset($dashboard_url) && $dashboard_url != '' ) {
                                        $dashboard_url = $dashboard_url;
                                    } else {
                                        $dashboard_url = 'javascript:void(0)';
                                    }
                                    ?>
                                    <li class="user_dashboard_ajax active" id="wp_dp_member_suggested" data-queryvar="dashboard=suggested"><a href="<?php echo wp_dp_allow_special_char($dashboard_url); ?>"><i class="icon-dashboard-user-account"></i><?php echo wp_dp_plugin_text_srt('wp_dp_login_register_dashboard') ?></a></li>
                                    <?php
                                    if ( true === Wp_dp_Member_Permissions::check_permissions('listings') ) {
                                        $listings_url = '';
                                        $listings_url = $this->wp_dp_dashboar_top_menu_url('dashboard=published_listings');
                                        if ( isset($listings_url) && $listings_url != '' ) {
                                            $listings_url = $listings_url;
                                        } else {
                                            $listings_url = 'javascript:void(0)';
                                        }
                                        ?>
                                        <li class="user_dashboard_ajax" id="wp_dp_member_listings" data-queryvar="dashboard=published_listings"><a href="<?php echo wp_dp_allow_special_char($listings_url); ?>"><i class="icon-listing-user-account"></i><?php echo wp_dp_plugin_text_srt('wp_dp_listings_listings') ?></a></li>
                                        <?php
                                    }
                                    if ( true === Wp_dp_Member_Permissions::check_permissions('promotion') ) {
                                        $package_url = $this->wp_dp_dashboar_top_menu_url('dashboard=promoted_listings');
                                        if ( isset($package_url) && $package_url != '' ) {
                                            $package_url = $package_url;
                                        } else {
                                            $package_url = 'javascript:void(0)';
                                        }
                                        ?>
                                        <li class="user_dashboard_ajax" id="wp_dp_member_promoted_listings" data-queryvar="dashboard=promoted_listings"><a href="<?php echo wp_dp_allow_special_char($package_url); ?>"><i class="icon-megaphone-with-waves"></i><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_promoted_listing') ?></a></li>
                                        <?php
                                    }
                                    if ( true === Wp_dp_Member_Permissions::check_permissions('enquiries') ) {
                                        $inquiries_url = '';
                                        $enquiries_url = $this->wp_dp_dashboar_top_menu_url('dashboard=enquiries');
                                        if ( isset($enquiries_url) && $enquiries_url != '' ) {
                                            $enquiries_url = $enquiries_url;
                                        } else {
                                            $enquiries_url = 'javascript:void(0)';
                                        }
                                        ?>
                                        <li class="user_dashboard_ajax" id="wp_dp_member_enquiries" data-queryvar="dashboard=enquiries"><a href="<?php echo wp_dp_allow_special_char($enquiries_url); ?>"><i class="icon-message-user-account"></i><?php echo wp_dp_plugin_text_srt('wp_dp_login_register_enquiries') ?></a></li>
                                        <?php
                                    }

                                    if ( true === Wp_dp_Member_Permissions::check_permissions('reviews') ) {
                                        $inquiries_url = '';
                                        $arrange_viewings_url = $this->wp_dp_dashboar_top_menu_url('dashboard=reviews');
                                        if ( isset($arrange_viewings_url) && $arrange_viewings_url != '' ) {
                                            $arrange_viewings_url = $arrange_viewings_url;
                                        } else {
                                            $arrange_viewings_url = 'javascript:void(0)';
                                        }
                                        ?>
                                        <li class="user_dashboard_ajax" id="wp_dp_publisher_reviews" data-queryvar="dashboard=reviews"><a href="<?php echo wp_dp_allow_special_char($arrange_viewings_url); ?>"><i class="icon-review-user-account"></i><?php echo wp_dp_plugin_text_srt('wp_dp_reviews_all_reviews_heading') ?></a></li>
                                        <?php
                                    }

                                    $search_alerts_url = '';
                                    $favourite_url = '';
                                    // search & alerts link for login shortcode.
                                    if ( true === Wp_dp_Member_Permissions::check_permissions('alerts') ) {
                                        $search_alerts_url = $this->wp_dp_dashboar_top_menu_url('dashboard=alerts');
                                        echo do_action('wp_dp_top_menu_member_dashboard', wp_dp_plugin_text_srt('wp_dp_login_register_alerts_searches'), '<i class="icon-email-user-account"></i>', $search_alerts_url);
                                    }

                                    // Favourites link for login shortcode.
                                    if ( true === Wp_dp_Member_Permissions::check_permissions('favourites') ) {
                                        $favourite_url = $this->wp_dp_dashboar_top_menu_url('dashboard=favourites');
                                        echo do_action('wp_dp_top_menu_favourites_dashboard', wp_dp_plugin_text_srt('wp_dp_login_register_favourite_listings'), '<i class="icon-favourite-user-account"></i>', $favourite_url);
                                    }
                                    if ( true === Wp_dp_Member_Permissions::check_permissions('packages') ) {
                                        $package_url = $this->wp_dp_dashboar_top_menu_url('dashboard=packages');
                                        if ( isset($package_url) && $package_url != '' ) {
                                            $package_url = $package_url;
                                        } else {
                                            $package_url = 'javascript:void(0)';
                                        }
                                        ?>
                                        <li class="user_dashboard_ajax" id="wp_dp_member_packages" data-queryvar="dashboard=packages"><a href="<?php echo wp_dp_allow_special_char($package_url); ?>"><i class="icon-package-user-account"></i><?php echo wp_dp_plugin_text_srt('wp_dp_packages') ?></a></li>
                                        <?php
                                    }

                                    if ( true === Wp_dp_Member_Permissions::check_permissions('transactions') ) {
                                        $transactions_url = $this->wp_dp_dashboar_top_menu_url('dashboard=transactions');
                                        if ( isset($transactions_url) && $transactions_url != '' ) {
                                            $transactions_url = $transactions_url;
                                        } else {
                                            $transactions_url = 'javascript:void(0)';
                                        }
                                        ?>
                                        <li class="user_dashboard_ajax" id="wp_dp_member_transactions" data-queryvar="dashboard=transactions"><a href="<?php echo wp_dp_allow_special_char($transactions_url); ?>"><i class="icon-invoice-user-account"></i><?php echo wp_dp_plugin_text_srt('wp_dp_transactions_invoices') ?></a></li>
                                        <?php
                                    }

                                    $notes_url = $this->wp_dp_dashboar_top_menu_url('dashboard=prop_notes');
                                    if ( isset($notes_url) && $notes_url != '' ) {
                                        $notes_url = $notes_url;
                                    } else {
                                        $notes_url = 'javascript:void(0)';
                                    }
                                    $hidden_listing_url = $this->wp_dp_dashboar_top_menu_url('dashboard=hidden_listings');
                                    if ( isset($hidden_listing_url) && $hidden_listing_url != '' ) {
                                        $hidden_listing_url = $hidden_listing_url;
                                    } else {
                                        $hidden_listing_url = 'javascript:void(0)';
                                    }
                                    if ( true === Wp_dp_Member_Permissions::check_permissions('company_profile') ) {
                                        $company_profile_url = '';
                                        $company_profile_url = $this->wp_dp_dashboar_top_menu_url('dashboard=account');
                                        if ( isset($company_profile_url) && $company_profile_url != '' ) {
                                            $company_profile_url = $company_profile_url;
                                        } else {
                                            $company_profile_url = 'javascript:void(0)';
                                        }
                                        ?>
                                        <li class="user_dashboard_ajax" id="wp_dp_member_accounts" data-queryvar="dashboard=account"><a href="<?php echo wp_dp_allow_special_char($company_profile_url); ?>"><i class="icon-settings-user-account"></i><?php echo wp_dp_plugin_text_srt('wp_dp_user_meta_my_profile') ?></a></li>
                                    <?php } ?>

                                    <?php
                                    if ( $wp_dp_user_type == 'supper-admin' ) {
                                        $team_members_url = '';
                                        $team_members_url = $this->wp_dp_dashboar_top_menu_url('dashboard=team_members');
                                        if ( isset($team_members_url) && $team_members_url != '' ) {
                                            $team_members_url = $team_members_url;
                                        } else {
                                            $team_members_url = 'javascript:void(0)';
                                        }
                                        // // please don't remove this code its temperory commented
                                        ?>
                                                                                                                                                                                                                                <!--                                        <li class="user_dashboard_ajax" id="wp_dp_member_company" data-queryvar="dashboard=team_members"><a href="<?php //echo wp_dp_allow_special_char($team_members_url);             ?>"><i class="icon-group"></i><?php //echo wp_dp_plugin_text_srt('wp_dp_member_team_members')             ?></a></li>-->
                                    <?php } ?>

                                    <li>
                                        <?php
                                        if ( is_user_logged_in() ) {
                                            ?>
                                            <a class="logout-btn" href="<?php echo esc_url(wp_logout_url(wp_dp_server_protocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])) ?>"><i class="icon-logout-user-account"></i><?php echo wp_dp_plugin_text_srt('wp_dp_login_register_sign_out') ?></a>
                                            <?php
                                        }
                                        ?>
                                    </li>
                                </ul><?php
                            } else {
                                ?>
                                <ul>
                                    <li>
                                        <h6><?php echo esc_html($user_display_name) ?></h6>
                                        <?php
                                        if ( is_user_logged_in() ) {
                                            ?>
                                            <a class="logout-btn" href="<?php echo esc_url(wp_logout_url(wp_dp_server_protocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])) ?>"><i class="icon-logout"></i><?php echo wp_dp_plugin_text_srt('wp_dp_login_register_sign_out') ?></a>
                                            <?php
                                        }
                                        ?>
                                    </li>

                                </ul>
                            <?php }
                            ?> 
                        </li>
                        <?php
                        $args = array(
                            'posts_per_page' => 5,
                            'post_type' => 'notifications',
                            'orderby' => 'ID',
                            'order' => 'DESC',
                            'post_status' => 'publish',
                            'meta_query' => array(
                                'relation' => 'AND',
                                array(
                                    'key' => 'status',
                                    'value' => 'new',
                                    'compare' => '=',
                                ),
                                array(
                                    'key' => 'reciever_id',
                                    'value' => $user_company,
                                    'compare' => '=',
                                ),
                            ),
                        );

                        $activity_query = new WP_Query($args);
                        ?>
                        <li class="user-dashboard-menu-children activities-list-holder">
                            <?php if ( $activity_query->have_posts() ) { ?>
                                <a>
                                    <i class="icon-db-notification"></i>
                                    <em class="bgcolor" id="activities-counts"><?php echo wp_dp_cs_allow_special_char($activity_query->found_posts); ?></em>
                                </a>
                                <ul class="activities-list">
                                    <li class="" data-id="<?php echo get_the_ID(); ?>"><span class="activities-count"><?php echo wp_dp_plugin_text_srt('wp_dp_member_recent_activities'); ?> (<span id="heading-counts"><?php echo wp_dp_cs_allow_special_char($activity_query->found_posts); ?></span>)</span></li>
                                    <?php while ( $activity_query->have_posts() ): $activity_query->the_post(); ?>
                                        <?php
                                        $user_id = get_post_meta(get_the_ID(), 'user_id', true);
                                        $message = get_post_meta(get_the_ID(), 'notification_content', true);
                                        $icon = get_post_meta(get_the_ID(), 'notification_icon', true);

                                        $icon_heart = strpos($icon, "icon-heart5");
                                        $icon_question_answer = strpos($icon, "icon-question_answer");
                                        $icon_layer = strpos($icon, "icon-layers3");
                                        $icon_review = strpos($icon, "icon-star");

                                        $icon_colorclass = '';
                                        if ( $icon_heart ) {
                                            $icon_colorclass = ' favourite-bg';
                                        }
                                        if ( $icon_review ) {
                                            $icon_colorclass = ' review-bg';
                                        }
                                        if ( $icon_question_answer ) {
                                            $icon_colorclass = ' message-bg';
                                        }
                                        if ( $icon_layer ) {
                                            $icon_colorclass = ' viewing-bg';
                                        }
                                        $user_info = get_userdata($user_id);
//                                                                               
                                        echo '<li id="activity-' . get_the_ID() . '" data-id="' . get_the_ID() . '">
														<span class="icon-holder' . $icon_colorclass . '">' . $icon . '</span>
														<div class="activity-content">' . $message . '
															<em>' . human_time_diff(get_the_time('U'), current_time('timestamp', 1)) . ' ' . wp_dp_plugin_text_srt('wp_dp_func_ago') . '</em>
															<a href="javascript:void(0);" class="close top_hide_notification"><span>' . wp_dp_plugin_text_srt('wp_dp_attachment_remove') . '</span><i class="icon-close"></i></a>
														</div>
												 </li>';
                                        ?>
                                    <?php endwhile; ?>
                                    <?php
                                    $dashboard_url = '';
                                    $dashboard_url = $this->wp_dp_dashboar_top_menu_url();
                                    if ( isset($dashboard_url) && $dashboard_url != '' ) {
                                        $dashboard_url = $dashboard_url;
                                    } else {
                                        $dashboard_url = 'javascript:void(0)';
                                    }
                                    ?>
                                    <li class="view-all"><a href="<?php echo wp_dp_allow_special_char($dashboard_url); ?>"><?php echo wp_dp_plugin_text_srt('wp_dp_view_all') ?></a></li>
                                </ul>
                            <?php } else { ?>
                                <a>
                                    <i title="<?php echo wp_dp_plugin_text_srt('wp_dp_no_activity'); ?>" data-toggle="popover" data-placement="left" data-trigger="hover" class="icon-db-notification"></i>
                                    <script>
                                        jQuery(document).ready(function () {
                                            jQuery('[data-toggle="tooltip"]').tooltip();
                                        });
                                    </script>
                                </a>
                            <?php } ?>
                        </li>

                        <?php wp_reset_postdata(); ?>
                    </ul> 
                </div>

                <?php
            }
        }

    }

    global $wp_dp_shortcode_login_frontend;
    $wp_dp_shortcode_login_frontend = new Wp_dp_Shortcode_Login_Frontend();
}
