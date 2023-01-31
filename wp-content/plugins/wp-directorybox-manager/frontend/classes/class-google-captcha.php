<?php

/**
 * File Type: Google Captcha
 */
if (!class_exists('Wp_dp_Captcha')) {

    Class Wp_dp_Captcha {

        public function __construct() {
            add_action('wp_dp_generate_captcha_form', array($this, 'wp_dp_generate_captcha_form_callback'), 10, 2);
            add_action('wp_ajax_wp_dp_reload_captcha_form', array($this, 'wp_dp_reload_captcha_form_callback'), 10, 1);
            add_action('wp_ajax_nopriv_wp_dp_reload_captcha_form', array($this, 'wp_dp_reload_captcha_form_callback'), 10, 1);
            add_action('wp_dp_verify_captcha_form', array($this, 'wp_dp_verify_captcha_form_callback'), 10, 1);
        }

        public function wp_dp_generate_captcha_form_callback($captcha_id = '',$return_output='false') {
            global $wp_dp_plugin_options;

            $wp_dp_captcha_switch = isset($wp_dp_plugin_options['wp_dp_captcha_switch']) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';
            $wp_dp_sitekey = isset($wp_dp_plugin_options['wp_dp_sitekey']) ? $wp_dp_plugin_options['wp_dp_sitekey'] : '';
            $wp_dp_secretkey = isset($wp_dp_plugin_options['wp_dp_secretkey']) ? $wp_dp_plugin_options['wp_dp_secretkey'] : '';
            $output = '';
            if ($wp_dp_captcha_switch == 'on') {
                if ($wp_dp_sitekey <> '' && $wp_dp_secretkey <> '') {
                         
                    $output .= '<div class="g-recaptcha" data-theme="light" id="' . $captcha_id . '" data-sitekey="' . $wp_dp_sitekey . '" style="">'
                            . '</div> <a class="recaptcha-reload-a" href="javascript:void(0);" onclick="captcha_reload(\'' . admin_url('admin-ajax.php') . '\', \'' . $captcha_id . '\');">'
                            . '<i class="icon-refresh2"></i> ' . wp_dp_plugin_text_srt('wp_dp_google_captcha_reload') . '</a>';
                } else {
                    $output .= '<p>' . wp_dp_plugin_text_srt('wp_dp_google_captcha_provide_captcha_api_key') . '</p>';
                }
            }
            if($return_output=='true'){
            return $output;
            }
            else{
                  echo force_balance_tags($output);
            }
        }

        public function wp_dp_reload_captcha_form_callback() {
            global $wp_dp_plugin_options;
            $captcha_id = $_REQUEST['captcha_id'].'_div';
	    
            $wp_dp_sitekey = isset($wp_dp_plugin_options['wp_dp_sitekey']) ? $wp_dp_plugin_options['wp_dp_sitekey'] : '';
           
	    $return_str = "<script>
        var " . $captcha_id . ";
            " . $captcha_id . " = grecaptcha.render('" . $captcha_id . "', {
                'sitekey': '" . $wp_dp_sitekey . "', //Replace this with your Site key
                'theme': 'light'
            });"
                    . "</script>";

            echo force_balance_tags($return_str);
            wp_die();
        }

        public function wp_dp_verify_captcha_form_callback($page) {
            global $wp_dp_plugin_options;
            $wp_dp_secretkey = isset($wp_dp_plugin_options['wp_dp_secretkey']) ? $wp_dp_plugin_options['wp_dp_secretkey'] : '';
            $wp_dp_captcha = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
            $wp_dp_captcha_switch = isset($wp_dp_plugin_options['wp_dp_captcha_switch']) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';

            if ($wp_dp_captcha_switch == 'on') {
                if ($page == true) {
                    if (empty($wp_dp_captcha)) {
                        return true;
                    }
                } else {

                    if (empty($wp_dp_captcha)) {
                        $response_array = array(
                            'type' => 'error',
                            'msg' => '<p>'. wp_dp_plugin_text_srt('wp_dp_google_captcha_select_field') .'</p>'
                        );
                        echo json_encode($response_array);
                        exit();
                    }
                }
            }
        }

    }

    global $Wp_dp_Captcha;
    $Wp_dp_Captcha = new Wp_dp_Captcha();
}