<?php
/*
 * Sign In With Social Media
 */
if ( ! function_exists('wp_dp_pb_register') ) {

    function wp_dp_pb_register($die = 0) {

        global $wp_dp_form_fields, $wp_dp_html_fields;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $PREFIX = 'wp_dp_register';
        $counter = $_POST['counter'];

        $wp_dp_counter = $_POST['counter'];
        if ( isset($_POST['action']) && ! isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $parseObject = new ShortcodeParse();
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes($shortcode_element_id);
            $output = $parseObject->wp_dp_shortcodes($output, $shortcode_str, true, $PREFIX);
        }
        $defaults = array( 'member_register_element_title' => '' );
        if ( isset($output['0']['atts']) ) {
            $atts = $output['0']['atts'];
        } else {
            $atts = array();
        }
        if ( isset($output['0']['content']) ) {
            $atts_content = $output['0']['content'];
        } else {
            $atts_content = array();
        }
        $button_element_size = '100';
        foreach ( $defaults as $key => $values ) {
            if ( isset($atts[$key]) ) {
                $$key = $atts[$key];
            } else {
                $$key = $values;
            }
        }
        $name = 'wp_dp_pb_register';

        $coloumn_class = 'column_' . $button_element_size;

        if ( isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode' ) {
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }

        $rand_id = rand(45, 897009);
        ?>

        <div id="<?php echo esc_attr($name . $wp_dp_counter); ?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class); ?> <?php echo esc_attr($shortcode_view); ?>" item="register" data="<?php echo wp_dp_element_size_data_array_index($button_element_size) ?>" >
            <?php wp_dp_element_setting($name, $wp_dp_counter, $button_element_size, '', 'heart'); ?>
            <div class="cs-wrapp-class-<?php echo esc_attr($wp_dp_counter) ?> <?php echo esc_attr($shortcode_element); ?>" id="<?php echo esc_attr($name . $wp_dp_counter) ?>" data-shortcode-template="[wp_dp_register {{attributes}}]" style="display: none;">
                <div class="cs-heading-area">

                    <h5><?php echo wp_dp_plugin_text_srt('wp_dp_login_form_register_options'); ?></h5>
                    <a href="javascript:removeoverlay('<?php echo esc_attr($name . $wp_dp_counter) ?>','<?php echo esc_attr($filter_element); ?>')" class="cs-btnclose"><i class="icon-times"></i></a> 
                </div>
                <div class="cs-pbwp-content">
                    <div class="cs-wrapp-clone cs-shortcode-wrapp cs-pbwp-content">

                    </div>
                    <div class="cs-wrapp-clone cs-shortcode-wrapp">
                        <?php
                        $wp_dp_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_element_title'),
                            'desc' => '',
                            'echo' => true,
                            'field_params' => array(
                                'std' => $member_register_element_title,
                                'id' => 'member_register_element_title',
                                'cust_name' => 'member_register_element_title[]',
                                'return' => true,
                            ),
                        );

                        $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                        ?>
                    </div>
                    <?php if ( isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode' ) {
                        ?>
                        <ul class="form-elements insert-bg">
                            <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo esc_js(str_replace('wp_dp_pb_', '', $name)); ?>', '<?php echo esc_js($name . $wp_dp_counter) ?>', '<?php echo esc_js($filter_element); ?>')" ><?php echo wp_dp_plugin_text_srt('wp_dp_insert'); ?></a> </li>
                        </ul>
                        <div id="results-shortocde"></div>
                        <?php
                    } else {

                        $wp_dp_opt_array = array(
                            'std' => wp_dp_plugin_text_srt('wp_dp_register'),
                            'id' => '',
                            'before' => '',
                            'after' => '',
                            'classes' => '',
                            'extra_atr' => '',
                            'cust_id' => '',
                            'cust_name' => 'wp_dp_orderby[]',
                            'return' => false,
                            'required' => false
                        );
                        $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_array);


                        $wp_dp_opt_array = array(
                            'name' => '',
                            'desc' => '',
                            'hint_text' => '',
                            'echo' => true,
                            'field_params' => array(
                                'std' => wp_dp_plugin_text_srt('wp_dp_save'),
                                'cust_id' => '',
                                'cust_type' => 'button',
                                'classes' => 'cs-admin-btn',
                                'cust_name' => '',
                                'extra_atr' => 'onclick="javascript:_removerlay(jQuery(this))"',
                                'return' => true,
                            ),
                        );

                        $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
        if ( $die <> 1 ) {
            die();
        }
    }

    add_action('wp_ajax_wp_dp_pb_register', 'wp_dp_pb_register');
}

/*
 * Start Function  how to login from social site(facebook, linkedin,twitter,etc)
 */
if ( ! function_exists('wp_dp_social_login_form') ) {

    add_action('login_form', 'wp_dp_social_login_form', 10);
    add_action('social_form', 'wp_dp_social_login_form', 10);
    add_action('after_signup_form', 'wp_dp_social_login_form', 10);
    add_action('social_login_form', 'wp_dp_social_login_form', 10);

    function wp_dp_social_login_form($args = NULL) {

        global $wp_dp_plugin_options, $wp_dp_form_fields_frontend;
        $display_label = false;
        // check for admin login form
        $admin_page = '0';
        if ( in_array($GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' )) ) {
            $admin_page = '1';
        }
        if ( get_option('users_can_register') && $admin_page == 0 ) {
            if ( $args == NULL )
                $display_label = true;
            elseif ( is_array($args) )
                extract($args);
            if ( ! isset($images_url) )
                $images_url = wp_dp::plugin_url() . 'wp-dp-login/cs-social-login/media/img/';
            $facebook_app_id = '';
            $facebook_secret = '';
            if ( isset($wp_dp_plugin_options['wp_dp_dashboard']) ) {
                $wp_dp_dashboard_link = get_permalink($wp_dp_plugin_options['wp_dp_dashboard']);
            }
            $twitter_enabled = isset($wp_dp_plugin_options['wp_dp_twitter_api_switch']) ? $wp_dp_plugin_options['wp_dp_twitter_api_switch'] : '';
            $facebook_enabled = isset($wp_dp_plugin_options['wp_dp_facebook_login_switch']) ? $wp_dp_plugin_options['wp_dp_facebook_login_switch'] : '';
            $google_enabled = isset($wp_dp_plugin_options['wp_dp_google_login_switch']) ? $wp_dp_plugin_options['wp_dp_google_login_switch'] : '';
			$demo_user_login = isset($wp_dp_plugin_options['wp_dp_demo_user_login_switch']) ? $wp_dp_plugin_options['wp_dp_demo_user_login_switch'] : 'off';
			if( $demo_user_login == 'on' ){
				$wp_dp_demo_user_agency = isset($wp_dp_plugin_options['wp_dp_demo_user_agency']) ? $wp_dp_plugin_options['wp_dp_demo_user_agency'] : '';
				if( $wp_dp_demo_user_agency == '' || $wp_dp_demo_user_agency == 0 ){
					$demo_user_login = 'off';
				}
			}
			
            $form_rand_id = isset($form_rand_id) ? $form_rand_id : '';

            if ( isset($wp_dp_plugin_options['wp_dp_facebook_app_id']) )
                $facebook_app_id = $wp_dp_plugin_options['wp_dp_facebook_app_id'];
            if ( isset($wp_dp_plugin_options['wp_dp_facebook_secret']) )
                $facebook_secret = $wp_dp_plugin_options['wp_dp_facebook_secret'];
            if ( isset($wp_dp_plugin_options['wp_dp_consumer_key']) )
                $twitter_app_id = $wp_dp_plugin_options['wp_dp_consumer_key'];
            if ( isset($wp_dp_plugin_options['wp_dp_google_client_id']) )
                $google_app_id = $wp_dp_plugin_options['wp_dp_google_client_id'];
            if ( $twitter_enabled == 'on' || $facebook_enabled == 'on' || $google_enabled == 'on' ) :
                $rand_id = rand(0, 98989899);
                $isRegistrationOn = get_option('users_can_register');
                if ( $isRegistrationOn ) {
                    ?>
                    <div class="footer-element comment-form-social-connect social_login_ui <?php if ( strpos($_SERVER['REQUEST_URI'], 'wp-signup.php') ) echo 'mu_signup'; ?>">
                        <div class="social_login_facebook_auth">
                            <?php
							$wp_dp_opt_array = array(
                                'id' => '',
                                'std' => $demo_user_login,
                                'cust_id' => "",
                                'cust_name' => "is_demo_user_login",
                                'extra_atr' => ' data-error-msg="' . wp_dp_plugin_text_srt('wp_dp_login_with_demo_user') . '"',
                                'classes' => '',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);
							
							
                            $wp_dp_opt_array = array(
                                'id' => '',
                                'std' => esc_attr($facebook_app_id),
                                'cust_id' => "",
                                'cust_name' => "client_id",
                                'classes' => '',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'id' => '',
                                'std' => home_url('index.php?social-login=facebook-callback'),
                                'cust_id' => "",
                                'cust_name' => "redirect_uri",
                                'classes' => '',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);


                            $fb_session_state = md5(uniqid(rand(), TRUE));
                            if ( ! get_transient('wp_dp_login_fb_session_state') ) {
                                set_transient('wp_dp_login_fb_session_state', $fb_session_state, 60 * 60 * 24 * 30);
                            } else {
                                $fb_session_state = get_transient('wp_dp_login_fb_session_state');
                            }
                            $wp_dp_opt_array = array(
                                'id' => '',
                                'std' => esc_attr($fb_session_state),
                                'cust_id' => "",
                                'cust_name' => "state",
                                'classes' => '',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                            $facebook_flag = wp_dp_facebook_auth_callback();
                            $wp_dp_opt_array = array(
                                'id' => '',
                                'std' => $facebook_flag,
                                'cust_id' => "",
                                'cust_name' => "is_fb_valid",
                                'extra_atr' => ' data-api-error-msg="' . wp_dp_plugin_text_srt('wp_dp_login_form_facebook_credentials') . '"',
                                'classes' => '',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);
                            ?>
                        </div>
                        <div class="social_login_twitter_auth">
                            <?php
                            $wp_dp_opt_array = array(
                                'id' => '',
                                'std' => esc_attr($twitter_app_id),
                                'cust_id' => "",
                                'cust_name' => "client_id",
                                'classes' => '',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);
                            $wp_dp_opt_array = array(
                                'id' => '',
                                'std' => home_url('index.php?social-login=twitter'),
                                'cust_id' => "",
                                'cust_name' => "redirect_uri",
                                'classes' => '',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                            $twitter_flag = wp_dp_twitter_auth_callback();
                            $wp_dp_opt_array = array(
                                'id' => '',
                                'std' => $twitter_flag,
                                'cust_id' => "",
                                'cust_name' => "is_twitter_valid",
                                'extra_atr' => ' data-api-error-msg="' . wp_dp_plugin_text_srt('wp_dp_login_form_twitter_credentials') . '"',
                                'classes' => '',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);
                            ?>
                        </div>
                        <div class="social_login_google_auth">
                            <?php
                            $wp_dp_opt_array = array(
                                'id' => '',
                                'std' => esc_attr($google_app_id),
                                'cust_id' => "",
                                'cust_name' => "client_id",
                                'classes' => '',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);
                            $wp_dp_opt_array = array(
                                'id' => '',
                                'std' => wp_dp_google_login_url() . (isset($_GET['redirect_to']) ? '&redirect=' . $_GET['redirect_to'] : ''),
                                'cust_id' => "",
                                'cust_name' => "redirect_uri",
                                'classes' => '',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                            $google_flag = wp_dp_google_auth_callback();
                            $wp_dp_opt_array = array(
                                'id' => '',
                                'std' => $google_flag,
                                'cust_id' => "",
                                'cust_name' => "is_google_valid",
                                'extra_atr' => ' data-api-error-msg="' . wp_dp_plugin_text_srt('wp_dp_login_form_google_credentials') . '"',
                                'classes' => '',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);
                            ?>
                        </div>

                        <div class="social-media">
<!--                            <p><?php //echo wp_dp_plugin_text_srt('wp_dp_login_form_login_facebook_or_google'); ?></p>-->
                            <ul>	 
                                <?php
                                if ( is_user_logged_in() ) {

                                    // remove id from all links
                                    if ( $facebook_enabled == 'on' ) :
                                        echo apply_filters('social_login_login_facebook', '<li><a onclick="javascript:show_alert_msg(\'' . wp_dp_plugin_text_srt('wp_dp_register_logout_first') . '\')" href="javascript:void(0);" title="' . wp_dp_plugin_text_srt('wp_dp_social_sharing_facebook') . '" data-original-title="' . wp_dp_plugin_text_srt('wp_dp_social_sharing_facebook') . '" class=" facebook"><span class="social-mess-top fb-social-login" style="display:none">' . wp_dp_plugin_text_srt('wp_dp_login_form_set_api_key') . '</span><i class="icon-facebook3"></i>' . wp_dp_plugin_text_srt('wp_dp_login_form_connect_with_facebook') . '</a></li>');
                                    endif;
                                    if ( $twitter_enabled == 'on' ) :
                                        echo apply_filters('social_login_login_twitter', '<li><a onclick="javascript:show_alert_msg(\'' . wp_dp_plugin_text_srt('wp_dp_register_logout_first') . '\')" href="javascript:void(0);" title="' . wp_dp_plugin_text_srt('wp_dp_social_sharing_twitter') . '" data-original-title="' . wp_dp_plugin_text_srt('wp_dp_social_sharing_twitter') . '" class="twitter"><span class="social-mess-top tw-social-login" style="display:none">' . wp_dp_plugin_text_srt('wp_dp_login_form_set_api_key') . '</span><i class="icon-twitter3"></i>' . wp_dp_plugin_text_srt('wp_dp_login_form_connect_with_twitter') . '</a></li>');
                                    endif;
                                    if ( $google_enabled == 'on' ) :
                                        echo apply_filters('social_login_login_google', '<li><a onclick="javascript:show_alert_msg(\'' . wp_dp_plugin_text_srt('wp_dp_register_logout_first') . '\')" href="javascript:void(0);" rel="nofollow" title="' . wp_dp_plugin_text_srt('wp_dp_listing_posted_google_plus') . '" data-original-title="' . wp_dp_plugin_text_srt('wp_dp_listing_posted_google_plus') . '" class="gplus"><span class="social-mess-top gplus-social-login" style="display:none">' . wp_dp_plugin_text_srt('wp_dp_login_form_set_api_key') . '</span><i class="icon-google2"></i>' . wp_dp_plugin_text_srt('wp_dp_login_form_connect_with_google') . '</a></li>');
                                    endif;
                                } else {

                                    // remove id from all links
                                    if ( $facebook_enabled == 'on' ) :
                                        echo apply_filters('social_login_login_facebook', '<li><a href="javascript:void(0);" title="' . wp_dp_plugin_text_srt('wp_dp_social_sharing_facebook') . '" data-original-title="' . wp_dp_plugin_text_srt('wp_dp_social_sharing_facebook') . '" class="social_login_login_facebook  facebook" data-id="' . $form_rand_id . '"><span class="social-mess-top fb-social-login" style="display:none">' . wp_dp_plugin_text_srt('wp_dp_login_form_set_api_key') . '</span><i class="icon-facebook3"></i>' . wp_dp_plugin_text_srt('wp_dp_login_form_connect_with_facebook') . '</a></li>');
                                    endif;
                                    if ( $twitter_enabled == 'on' ) :
                                        echo apply_filters('social_login_login_twitter', '<li><a href="javascript:void(0);" title="' . wp_dp_plugin_text_srt('wp_dp_social_sharing_twitter') . '" data-original-title="' . wp_dp_plugin_text_srt('wp_dp_social_sharing_twitter') . '" class="social_login_login_twitter twitter" data-id="' . $form_rand_id . '"><span class="social-mess-top tw-social-login" style="display:none">' . wp_dp_plugin_text_srt('wp_dp_login_form_set_api_key') . '</span><i class="icon-twitter3"></i>' . wp_dp_plugin_text_srt('wp_dp_login_form_connect_with_twitter') . '</a></li>');
                                    endif;
                                    if ( $google_enabled == 'on' ) :
                                        echo apply_filters('social_login_login_google', '<li><a  href="javascript:void(0);" rel="nofollow" title="' . wp_dp_plugin_text_srt('wp_dp_listing_posted_google_plus') . '" data-original-title="' . wp_dp_plugin_text_srt('wp_dp_listing_posted_google_plus') . '" class="social_login_login_google gplus" data-id="' . $form_rand_id . '"><span class="social-mess-top gplus-social-login" style="display:none">' . wp_dp_plugin_text_srt('wp_dp_login_form_set_api_key') . '</span><i class="icon-google2"></i>' . wp_dp_plugin_text_srt('wp_dp_login_form_connect_with_google') . '</a></li>');
                                    endif;
                                }
                                $social_login_provider = isset($_COOKIE['social_login_current_provider']) ? $_COOKIE['social_login_current_provider'] : '';
                                do_action('social_login_auth');
                                ?> 
                            </ul> 
                        </div>
                        <div class="login-form-separator"><span><?php echo wp_dp_plugin_text_srt('wp_dp_popup_or'); ?></span></div>
                    </div>
                <?php } ?>

                <?php
            endif;
        }
    }

}

/*
 * Start Function  how to user  recover his  password
 */
if ( ! function_exists('wp_dp_get_new_pass') ) {

    function wp_dp_get_new_pass() {
        global $wpdb, $wp_hasher;
        if ( isset($_POST['action']) && 'wp_dp_get_new_pass' == $_POST['action'] ) {
            $user_login = isset($_POST['user_input']) ? $_POST['user_input'] : '';
            $type = isset($_POST['type']) ? $_POST['type'] : '';
            $current_page_id = isset($_POST['current_page_id']) ? $_POST['current_page_id'] : '';
            $home_url = isset($_POST['home_url']) ? $_POST['home_url'] : home_url();

            $user_login = sanitize_text_field($user_login);
            if ( empty($user_login) ) {
                $json['type'] = "error";
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_forgot_pass_username_email_empty');
                echo json_encode($json);
                wp_die();
            } else if ( strpos($user_login, '@') ) {
                $user_data = get_user_by('email', trim($user_login));
                if ( empty($user_data) ) {
                    $json['type'] = "error";
                    $json['msg'] = wp_dp_plugin_text_srt('wp_dp_forgot_pass_email_error');
                    echo json_encode($json);
                    wp_die();
                }
            } else {
                $login = trim($user_login);
                $user_data = get_user_by('login', $login);
                if ( empty($user_data) ) {
                    $json['type'] = "error";
                    $json['msg'] = wp_dp_plugin_text_srt('wp_dp_forgot_pass_username_error');
                    echo json_encode($json);
                    wp_die();
                }
            }

            do_action('lostpassword_post');

            // redefining user_login ensures we return the right case in the email
            $user_login = $user_data->user_login;
            $user_email = $user_data->user_email;
            do_action('retreive_password', $user_login);  // Misspelled and deprecated
            do_action('retrieve_password', $user_login);
            $allow = apply_filters('allow_password_reset', true, $user_data->ID);
            if ( ! $allow ) {
                $json['type'] = "error";
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_forgot_pass_not_allow');
                echo json_encode($json);
                wp_die();
            } else if ( is_wp_error($allow) ) {
                $json['type'] = "error";
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_forgot_pass_wp_error');
                echo json_encode($json);
                wp_die();
            }
            $key = wp_generate_password(20, false);
            do_action('retrieve_password_key', $user_login, $key);

            if ( empty($wp_hasher) ) {
                require_once ABSPATH . 'wp-includes/class-phpass.php';
                $wp_hasher = new PasswordHash(8, true);
            }
            $hashed = $wp_hasher->HashPassword($key);
            $wpdb->update($wpdb->users, array( 'user_activation_key' => time() . ":" . $hashed ), array( 'user_login' => $user_login ));
            if ( isset($type) && $type == 'page' && isset($current_page_id) && is_numeric($current_page_id) ) {
                $reset_link = add_query_arg(array( 'reset_pass' => 'true', 'key' => $key, 'login' => rawurlencode($user_login), 'popup' => 'false' ), esc_url(wp_dp_wpml_lang_page_permalink($current_page_id, 'page')));
            } else {
                $reset_link = $home_url."?reset_pass=true&key=$key&login=" . rawurlencode($user_login) . '&popup=true';
            }
            if ( is_multisite() ) {
                $blogname = $GLOBALS['current_site']->site_name;
            } else {
                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
            }
            $title = sprintf(wp_dp_plugin_text_srt('wp_dp_forgot_pass_title'), $blogname);

            $args['user_login'] = $user_login;
            $args['user_email'] = $user_email;
            $args['title'] = $title;
            $args['reset_link'] = '<a href="' . $reset_link . '">' . $reset_link . '</a>';
            $args['home_url'] = $home_url;

            do_action('wp_dp_confirm_reset_password', $args);

            $json['type'] = "success";
            $json['msg'] = wp_dp_plugin_text_srt('wp_dp_forgot_pass_link_sent');
            echo json_encode($json);
            wp_die();
        }
    }

    add_action('wp_ajax_wp_dp_get_new_pass', 'wp_dp_get_new_pass');
    add_action('wp_ajax_nopriv_wp_dp_get_new_pass', 'wp_dp_get_new_pass');
}

/*
 * Start Function  how to user  recover his  password
 */
if ( ! function_exists('wp_dp_reset_pass') ) {

    function wp_dp_reset_pass() {
        global $wpdb, $wp_dp_plugin_options;

        $json = array();
        // check if we're in reset form
        if ( isset($_POST['action']) && 'wp_dp_reset_pass' == $_POST['action'] ) {
            $random_password = esc_sql(trim($_POST['user_input']));
            $confirm_new_pass = esc_sql(trim($_POST['confirm_new_pass']));

            if ( $random_password != $confirm_new_pass ) {
                $json['type'] = "error";
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_forgot_pass_confirm_not_match');
                echo json_encode($json);
                wp_die();
            }

            $user_login = esc_sql(trim($_POST['user_login']));

            if ( empty($user_login) ) {
                $json['type'] = "error";
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_forgot_pass_username_email_empty');
                echo json_encode($json);
                wp_die();
            } else if ( strpos($user_login, '@') ) {
                $user_data = get_user_by('email', trim($user_login));
                if ( empty($user_data) ) {
                    $json['type'] = "error";
                    $json['msg'] = wp_dp_plugin_text_srt('wp_dp_forgot_pass_email_error');
                    echo json_encode($json);
                    wp_die();
                }
            } else {
                $login = trim($user_login);
                $user_data = get_user_by('login', $login);
                if ( empty($user_data) ) {
                    $json['type'] = "error";
                    $json['msg'] = wp_dp_plugin_text_srt('wp_dp_forgot_pass_username_error');
                    echo json_encode($json);
                    wp_die();
                }
            }

            $username = $user_data->user_login;
            $email = $user_data->user_email;
            $update_user = wp_set_password($random_password, $user_data->ID);

            $template_data = array(
                'user' => $username,
                'email' => $email,
                'password' => $random_password,
            );
            do_action('wp_dp_change_password_email', $template_data);
            if ( class_exists('Wp_dp_change_password_email_template') && isset(Wp_dp_change_password_email_template::$is_email_sent1) ) {
                $json['type'] = "success";
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_login_form_check_email_for_new_pass');
                echo json_encode($json);
                wp_die();
            } else {
                $json['type'] = "error";
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_login_form_wrong_updating_account');
                echo json_encode($json);
                wp_die();
            }
        }
    }

    add_action('wp_ajax_wp_dp_reset_pass', 'wp_dp_reset_pass');
    add_action('wp_ajax_nopriv_wp_dp_reset_pass', 'wp_dp_reset_pass');
}

/*
 * Start Function  how to user  recover his  password
 */
if ( ! function_exists('wp_dp_recover_pass') ) {

    function wp_dp_recover_pass() {
        global $wpdb, $wp_dp_plugin_options;

        $wp_dp_danger_html = '<div class="alert alert-danger"><p><i class="icon-warning4"></i>';

        $wp_dp_success_html = '<div class="alert alert-success"><p><i class="icon-checkmark6"></i>';

        $wp_dp_msg_html = '</p></div>';

        $wp_dp_msg = '';
        $json = array();
        // check if we're in reset form
        if ( isset($_POST['action']) && 'wp_dp_recover_pass' == $_POST['action'] ) {
            $email = esc_sql(trim($_POST['user_input']));
            if ( empty($email) ) {
                $json['type'] = "error";
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_login_form_enter_email_address');
                echo json_encode($json);
                wp_die();
            } else if ( ! is_email($email) ) {
                $json['type'] = "error";
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_login_form_invalid_email_address');
                echo json_encode($json);
                wp_die();
            } else if ( ! email_exists($email) ) {
                $json['type'] = "error";
                $json['msg'] = wp_dp_plugin_text_srt('wp_dp_login_form_no_user_registered_with_email');
                echo json_encode($json);
                wp_die();
            } else {
                $random_password = wp_generate_password(12, false);
                $user = get_user_by('email', $email);
                $username = $user->user_login;
                $update_user = wp_set_password($random_password, $user->ID);

                $template_data = array(
                    'user' => $username,
                    'email' => $email,
                    'password' => $random_password,
                );
                //if ( true ) {
                do_action('wp_dp_change_password_email', $template_data);
                if ( class_exists('Wp_dp_change_password_email_template') && isset(Wp_dp_change_password_email_template::$is_email_sent1) ) {
                    $json['type'] = "success";
                    $json['msg'] = wp_dp_plugin_text_srt('wp_dp_login_form_check_email_for_new_pass');
                    echo json_encode($json);
                    wp_die();
                } else {
                    $json['type'] = "error";
                    $json['msg'] = wp_dp_plugin_text_srt('wp_dp_login_form_wrong_updating_account');
                    echo json_encode($json);
                    wp_die();
                }
                //}
            }
            //end else
        }
        // end if
        echo ($wp_dp_msg);

        die;
    }

    add_action('wp_ajax_wp_dp_recover_pass', 'wp_dp_recover_pass');
    add_action('wp_ajax_nopriv_wp_dp_recover_pass', 'wp_dp_recover_pass');
}
/*
 * Start Function how to user recover his lost password
 */

if ( ! function_exists('wp_dp_lost_pass') ) {

    function wp_dp_lost_pass($atts, $content = "") {
        global $wp_dp_form_fields_frontend;
        $wp_dp_defaults = array(
            'wp_dp_type' => '',
        );
        extract(shortcode_atts($wp_dp_defaults, $atts));
        wp_enqueue_script('wp-dp-validation-script');
        ob_start();
        $wp_dp_rand = rand(12345678, 98765432);

        $reset_pass = false;
        $error_msg = '';
        if ( isset($_GET['reset_pass']) && $_GET['reset_pass'] == 'true' ) {
            if ( isset($_GET['key']) && $_GET['key'] != '' && isset($_GET['login']) && $_GET['login'] != '' ) {
                $user = check_password_reset_key($_GET['key'], $_GET['login']);
                $reset_pass = true;
                if ( $user && $user->get_error_code() === 'expired_key' ) {
                    $error_msg = wp_dp_plugin_text_srt('wp_dp_forgot_pass_link_expired');
                    $reset_pass = false;
                } else if ( $user && $user->get_error_code() === 'invalid_key' ) {
                    $error_msg = wp_dp_plugin_text_srt('wp_dp_forgot_pass_link_invalid');
                    $reset_pass = false;
                }
            }
        }
        if ( $reset_pass == true ) {
            $display_reset_pass_form = 'block';
            $display_lost_pass_form = 'none';
        } else {
            $display_reset_pass_form = 'none';
            $display_lost_pass_form = 'block';
        }
        if ( $wp_dp_type == 'popup' ) {
            ?>
            <span class="wp-dp-dev-login-forget-txt" style="display: none;"><?php echo wp_dp_plugin_text_srt('wp_dp_login_form_forgot_pass'); ?></span>
            <span class="wp-dp-dev-login-box-t-txt" style="display: none;"><?php echo wp_dp_plugin_text_srt('wp_dp_login_form_login_your_account'); ?></span>
            <span class="wp-dp-reset-link-error" style="color:red;"><?php echo esc_html($error_msg); ?></span>
            <div id="cs-result-<?php echo absint($wp_dp_rand) ?>"></div>
            <div class="login-form-id-<?php echo absint($wp_dp_rand) ?>">
                <form class="user_form" id="wp_pass_reset_<?php echo absint($wp_dp_rand) ?>" method="post" style="display:<?php echo esc_html($display_reset_pass_form); ?>;">		
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <div class="input-filed"> 
                            <i class="icon-lock4"></i>
                            <?php
                            $random_password = wp_generate_password(12, false);
                            $wp_dp_opt_array = array(
                                'id' => '',
                                'std' => '',
                                'cust_id' => "",
                                'cust_name' => "user_input",
                                'classes' => 'wp-dp-dev-req-field form-control',
                                'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_forgot_pass_enter_new_pass') . '"',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                            ?>
                        </div>
                        <div class="input-filed"> 
                            <i class="icon-lock4"></i>
                            <?php
                            $wp_dp_opt_array = array(
                                'id' => '',
                                'std' => '',
                                'cust_id' => "",
                                'cust_name' => "confirm_new_pass",
                                'classes' => 'wp-dp-dev-req-field form-control',
                                'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_forgot_pass_confirm_new_pass') . '"',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'id' => '',
                                'std' => (isset($_GET['login']) && $_GET['login'] != '') ? $_GET['login'] : '',
                                'cust_id' => "",
                                'cust_name' => "user_login",
                                'classes' => 'form-control',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);
                            ?>
                        </div>
                        <div class="input-filed">
                            <div class="ajax-forgot-button input-button-loader">
                                <label>
                                    <?php
                                    $wp_dp_opt_array = array(
                                        'id' => '',
                                        'std' => wp_dp_plugin_text_srt('wp_dp_login_form_send_email'),
                                        'cust_id' => "",
                                        'cust_name' => "submit",
                                        'classes' => 'reset_password bgcolor',
                                        'cust_type' => 'submit',
                                    );
                                    $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                    ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
                <form class="user_form" id="wp_pass_lost_<?php echo absint($wp_dp_rand) ?>" method="post" style="display:<?php echo esc_html($display_lost_pass_form); ?>;">		
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <div class="input-filed"> 
                            <i class="icon-directory-envelope"></i>
                            <?php
                            $wp_dp_opt_array = array(
                                'id' => '',
                                'std' => '',
                                'cust_id' => "",
                                'cust_name' => "user_input",
                                'classes' => 'wp-dp-dev-req-field form-control',
                                'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_press(this,\'email\')" placeholder="' . wp_dp_plugin_text_srt('wp_dp_forgot_pass_enter_username_email') . '"',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'id' => '',
                                'std' => get_the_ID(),
                                'cust_id' => "",
                                'cust_name' => "current_page_id",
                                'classes' => 'form-control',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'id' => '',
                                'std' => home_url(),
                                'cust_id' => "",
                                'cust_name' => "home_url",
                                'classes' => '',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);
                            ?>
                        </div>
                        <div class="input-filed">
                            <div class="ajax-forgot-button input-button-loader">
                                <label>
                                    <?php
                                    $wp_dp_opt_array = array(
                                        'id' => '',
                                        'std' => wp_dp_plugin_text_srt('wp_dp_login_form_send_email'),
                                        'cust_id' => "",
                                        'cust_name' => "submit",
                                        'classes' => 'reset_password bgcolor',
                                        'cust_type' => 'submit',
                                    );
                                    $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                    ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <?php
        } else {
            ?>
            <div class="scetion-title">
                <h4><?php echo wp_dp_plugin_text_srt('wp_dp_register_forgot_password'); ?></h4>
            </div>
            <div class="status status-message" id="cs-result-<?php echo absint($wp_dp_rand) ?>"></div>
            <form class="user_form" id="shortcode_wp_pass_lost_<?php echo absint($wp_dp_rand) ?>" method="post">		
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <div class="input-holder">
                            <?php
                            $wp_dp_opt_array = array(
                                'id' => '',
                                'std' => '',
                                'cust_id' => "",
                                'cust_name' => "user_input",
                                'classes' => 'form-control',
                                'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_login_form_enter_email_address') . '"',
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                            ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <div class="shortcode-ajax-forgot-button input-button-loader">
            <?php
            $wp_dp_opt_array = array(
                'id' => '',
                'std' => wp_dp_plugin_text_srt('wp_dp_login_form_send_email'),
                'cust_id' => "",
                'cust_name' => "submit",
                'classes' => 'reset_password user-submit backcolr bgcolor acc-submit',
                'cust_type' => 'submit',
            );
            $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
            ?>
                                </div>
                            </div>
                            <div class="col-mlg-7 col-md-7 col-sm-12 col-xs-12 login-section">
                                <div class="login-here-seaction">
                                    <a class="login-link-page" href="javascript:void(0)"><?php echo wp_dp_plugin_text_srt('wp_dp_register_login_here') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php
        }
        ?>
        <script type="text/javascript">
            var $ = jQuery;
            $("#wp_pass_reset_<?php echo absint($wp_dp_rand) ?>").submit(function () {

                var returnType = wp_dp_validation_process(jQuery("#wp_pass_reset_<?php echo absint($wp_dp_rand) ?>"));
                if (returnType == false) {
                    return false;
                }
                var data_id = jQuery(this).closest('.login-form').data('id');
                var forget_pass = '.login-form-element-' + data_id + ' div.active .modal-body .content-style-form';
                var thisObj = jQuery('.ajax-forgot-button');
                wp_dp_show_loader('.ajax-forgot-button', '', 'button_loader', thisObj);

                var input_data = $('#wp_pass_reset_<?php echo absint($wp_dp_rand) ?>').serialize() + '&action=wp_dp_reset_pass';
                $.ajax({
                    type: "POST",
                    url: "<?php echo esc_url(admin_url('admin-ajax.php')) ?>",
                    data: input_data,
                    dataType: 'json',
                    success: function (msg) {
                        // call response function div.
                        if (msg.type === 'success') {
                            jQuery('#wp_pass_reset_<?php echo absint($wp_dp_rand) ?> input[name=user_input]').val('');
                            jQuery('#wp_pass_reset_<?php echo absint($wp_dp_rand) ?> input[name=confirm_new_pass]').val('');
                        }
                        wp_dp_show_response(msg, '', thisObj);
                    }
                });
                return false;
            });
            $("#wp_pass_lost_<?php echo absint($wp_dp_rand) ?>").submit(function () {

                var returnType = wp_dp_validation_process(jQuery("#wp_pass_lost_<?php echo absint($wp_dp_rand) ?>"));
                if (returnType == false) {
                    return false;
                }
                var data_id = jQuery(this).closest('.login-form').data('id');
                var forget_pass = '.login-form-element-' + data_id + ' div.active .modal-body .content-style-form';
                var thisObj = jQuery('.ajax-forgot-button');
                wp_dp_show_loader('.ajax-forgot-button', '', 'button_loader', thisObj);

                var popup_class = jQuery(this).parent().parent().closest('#sign-in').attr('class');

                if (typeof popup_class !== 'undefined' && popup_class === 'modal fade active in') {
                    var input_data = $('#wp_pass_lost_<?php echo absint($wp_dp_rand) ?>').serialize() + '&action=wp_dp_get_new_pass&type=popup';
                } else if (typeof popup_class === 'undefined') {
                    var input_data = $('#wp_pass_lost_<?php echo absint($wp_dp_rand) ?>').serialize() + '&action=wp_dp_get_new_pass&type=page';
                } else {
                    var input_data = $('#wp_pass_lost_<?php echo absint($wp_dp_rand) ?>').serialize() + '&action=wp_dp_get_new_pass&type=popup';
                }
                $.ajax({
                    type: "POST",
                    url: "<?php echo esc_url(admin_url('admin-ajax.php')) ?>",
                    data: input_data,
                    dataType: 'json',
                    success: function (msg) {
                        // call response function div.
                        if (msg.type === 'success') {
                            if (jQuery('.wp-dp-reset-link-error').length > 0) {
                                jQuery('.wp-dp-reset-link-error').hide();
                            }
                            jQuery('#wp_pass_lost_<?php echo absint($wp_dp_rand) ?> input[name=user_input]').val('');
                        }
                        wp_dp_show_response(msg, '', thisObj);
                    }
                });
                return false;
            });
            $("#shortcode_wp_pass_lost_<?php echo absint($wp_dp_rand) ?>").submit(function () {

                var thisObj = jQuery('.shortcode-ajax-forgot-button');
                wp_dp_show_loader('.shortcode-ajax-forgot-button', '', 'button_loader', thisObj);

                var input_data = $('#wp_pass_lost_<?php echo absint($wp_dp_rand) ?>').serialize() + '&action=wp_dp_get_new_pass&type=page';
                $.ajax({
                    type: "POST",
                    url: "<?php echo esc_url(admin_url('admin-ajax.php')) ?>",
                    data: input_data,
                    dataType: 'json',
                    success: function (msg) {
                        // call response function div.
                        wp_dp_show_response(msg, '', thisObj);
                    }
                });
                return false;
            });

            $(document).on('click', '.user-tab-login', function () {
                $('.cs-login-switch').click();
            });
            $(document).on('click', '.cs-forgot-switch', function () {
                var _this_title = $('.wp-dp-dev-login-forget-txt').html();
                var _this_append = $('.wp-dp-dev-login-main-title');
                var data_id = $(this).data('id');
                _this_append.html(_this_title);
                $('.cs-login-pbox-' + data_id).hide();
                $('.cs-forgot-pbox-' + data_id).show();
            });
            $(document).on('click', '.cs-login-switch', function () {
                var _this_title = $('.wp-dp-dev-login-box-t-txt').html();
                var _this_append = $('.wp-dp-dev-login-main-title');
                var data_id = $(this).data('id');
                _this_append.html(_this_title);

                $('.cs-forgot-pbox-' + data_id).hide();
                $('.cs-login-pbox-' + data_id).show();
            });
            $(document).on('click', '.user-registeration a', function () {
                $('#sign-in').modal('hide');
            });
            $(document).on('click', '.user-logging-in a', function () {
                $('#join-us').modal('hide');
            });
            $(document).on('click', '.wp-dp-subscribe-pkg', function () {
                var msg_show = $(this).data('msg');
                $("#sign-in .modal-body .wp-dp-dev-login-top-msg").html(msg_show);
                $("#sign-in .modal-body .wp-dp-dev-login-top-msg").show();
            });
            $(document).on('click', '.cs-popup-login-btn', function () {
                $("#sign-in .modal-body .wp-dp-dev-login-top-msg").html('');
                $("#sign-in .modal-body .wp-dp-dev-login-top-msg").hide();
            });
        </script>
        <?php
        $wp_dp_html = ob_get_clean();

        return do_shortcode($wp_dp_html);
    }

    add_shortcode('wp_dp_forgot_password', 'wp_dp_lost_pass');
}
/*
 * Start Function google authentication
 */
if ( ! function_exists('wp_dp_google_auth_callback') ) {

    function wp_dp_google_auth_callback() {
        global $wpdb, $wp_dp_plugin_options;
        $client_id = isset($wp_dp_plugin_options['wp_dp_google_client_id']) ? $wp_dp_plugin_options['wp_dp_google_client_id'] : '';

        if ( false === ( $transient = get_transient('is_google_valid') ) || $transient['app_id'] != $client_id ) {
            $redirect_url = isset($wp_dp_plugin_options['wp_dp_google_login_redirect_url']) ? $wp_dp_plugin_options['wp_dp_google_login_redirect_url'] : '';

            $wp_remote_get_args = array(
                'timeout' => 50,
                'compress' => false,
                'decompress' => true,
            );

            $response = wp_remote_get('https://accounts.google.com/o/oauth2/auth?response_type=code&redirect_uri=' . $redirect_url . '&client_id=' . $client_id . '&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email&access_type=offline&approval_prompt=auto', $wp_remote_get_args);
            $response = ( is_array( $response ) )? $response : (array) $response;
            if ( ! isset($response['response']['code']) || isset($response['response']['code']) && $response['response']['code'] != 200 ) {
                $is_google_valid = false;
            } else {
                $is_google_valid = true;
            }
            $transient = array( 'is_google_valid' => $is_google_valid, 'app_id' => $client_id );
            set_transient('is_google_valid', $transient, 24 * HOUR_IN_SECONDS);
        }

        return $transient['is_google_valid'];
    }

}
/*
 * Start Function facebook authentication
 */
if ( ! function_exists('wp_dp_facebook_auth_callback') ) {

    function wp_dp_facebook_auth_callback() {
        global $wpdb, $wp_dp_plugin_options;
        $facebook_app_id = isset($wp_dp_plugin_options['wp_dp_facebook_app_id']) ? $wp_dp_plugin_options['wp_dp_facebook_app_id'] : '';

        if ( false === ( $transient = get_transient('is_fb_valid') ) || $transient['app_id'] != $facebook_app_id ) {
            // It wasn't there, so regenerate the data and save the transient.

            $response = wp_remote_get('https://graph.facebook.com/oauth/authorize?client_id=' . esc_attr($facebook_app_id) . '&redirect_uri=' . home_url('index.php?social-login=facebook-callback') . '&scope=email', array( 'redirection' => 0 ));
            $is_fb_valid = false;
            if ( is_array($response) ) {
                if ( ! $response['body'] ) {
                    $is_fb_valid = true;
                }
            }
            $transient = array( 'is_fb_valid' => $is_fb_valid, 'app_id' => $facebook_app_id );
            set_transient('is_fb_valid', $transient, 24 * HOUR_IN_SECONDS);
        }

        return $transient['is_fb_valid'];
    }

}
/*
 * Start Function twitter authentication
 */
if ( ! function_exists('wp_dp_twitter_auth_callback') ) {

    function wp_dp_twitter_auth_callback() {
        global $wpdb, $wp_dp_plugin_options;
        $consumer_key = isset($wp_dp_plugin_options['wp_dp_consumer_key']) ? $wp_dp_plugin_options['wp_dp_consumer_key'] : '';
        $consumer_secret = isset($wp_dp_plugin_options['wp_dp_consumer_secret']) ? $wp_dp_plugin_options['wp_dp_consumer_secret'] : '';
        if ( false === ( $transient = get_transient('is_twitter_valid') ) || $transient['app_id'] != $consumer_key ) {

            if ( ! class_exists('TwitterOAuth') ) {
                require_once wp_dp::plugin_dir() . 'include/cs-twitter/twitteroauth.php';
            }
            $twitter_oath_callback = home_url('index.php?social-login=twitter-callback');

            $connection = new TwitterOAuth($consumer_key, $consumer_secret, '', '');
            $request_token = $connection->getRequestToken($twitter_oath_callback);
            if ( $connection->http_code != 200 ) {
                $is_twitter_valid = false;
            } else {
                $is_twitter_valid = true;
            }


            $transient = array( 'is_twitter_valid' => $is_twitter_valid, 'app_id' => $consumer_key );
            set_transient('is_twitter_valid', $transient, 24 * HOUR_IN_SECONDS);
        }

        return $transient['is_twitter_valid'];
    }

}
