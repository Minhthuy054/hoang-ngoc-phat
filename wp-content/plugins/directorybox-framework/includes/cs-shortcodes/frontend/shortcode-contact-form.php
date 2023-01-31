<?php

/*
 * Frontend file for Contact Us short code
 */
if ( ! function_exists('wp_dp_cs_var_contact_us_data') ) {

    function wp_dp_cs_var_contact_us_data($atts, $content = "") {
        global $post, $abc;
        $html = '';

        $page_element_size = isset($atts['contact_form_element_size']) ? $atts['contact_form_element_size'] : 100;
        if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
            $html .= '<div class="' . wp_dp_cs_var_page_builder_element_sizes($page_element_size, $atts) . ' ">';
        }
        $defaults = shortcode_atts(array(
            'wp_dp_cs_var_column_size' => '',
            'wp_dp_cs_var_contact_us_element_title' => '',
            'wp_dp_var_contact_us_align' => '',
            'wp_dp_cs_var_contact_us_element_subtitle' => '',
            'wp_dp_cs_var_contact_us_element_send' => '',
            'wp_dp_cs_var_contact_us_element_success' => '',
            'wp_dp_cs_var_contact_us_element_error' => '',
            'wp_dp_cs_var_contact_form_title_color' => '',
            'wp_dp_cs_var_contact_form_subtitle_color' => '',
            'wp_dp_var_contact_form_seperator_style' => '',
            'wp_dp_contact_label_switch' => '',
                ), $atts);


        extract(shortcode_atts($defaults, $atts));


        $strings = new wp_dp_cs_var_frame_all_strings;
        $strings->wp_dp_cs_var_frame_all_string_all();

        wp_enqueue_script('wp_dp_cs-growls');

        if ( isset($wp_dp_cs_var_column_size) && $wp_dp_cs_var_column_size != '' ) {
            if ( function_exists('wp_dp_cs_var_custom_column_class') ) {
                $column_class = wp_dp_cs_var_custom_column_class($wp_dp_cs_var_column_size);
            }
        }

        $wp_dp_cs_email_counter = rand(56, 5565);
        // Set All variables 
        $section_title = '';
        $column_class = isset($column_class) ? $column_class : '';
        $wp_dp_cs_contactus_section_title = isset($wp_dp_cs_var_contact_us_element_title) ? $wp_dp_cs_var_contact_us_element_title : '';
        $wp_dp_cs_contact_us_element_subtitle = isset($wp_dp_cs_var_contact_us_element_subtitle) ? $wp_dp_cs_var_contact_us_element_subtitle : '';
        $wp_dp_cs_contactus_send = isset($wp_dp_cs_var_contact_us_element_send) ? $wp_dp_cs_var_contact_us_element_send : '';
        $wp_dp_cs_success = isset($wp_dp_cs_var_contact_us_element_success) ? $wp_dp_cs_var_contact_us_element_success : '';
        $wp_dp_cs_error = isset($wp_dp_cs_var_contact_us_element_error) ? $wp_dp_cs_var_contact_us_element_error : '';
        $wp_dp_contact_label_switch = isset($wp_dp_contact_label_switch) && ! empty($wp_dp_contact_label_switch) ? $wp_dp_contact_label_switch : 'yes';


        // End All variables
        if ( isset($column_class) && $column_class <> '' ) {
            $html .= '<div class="' . esc_html($column_class) . '">';
        }

        $html .= wp_dp_title_sub_align($wp_dp_cs_contactus_section_title, $wp_dp_cs_contact_us_element_subtitle, $wp_dp_var_contact_us_align, $wp_dp_cs_var_contact_form_title_color, $wp_dp_var_contact_form_seperator_style, $wp_dp_cs_var_contact_form_subtitle_color);

        if ( trim($wp_dp_cs_success) && trim($wp_dp_cs_success) != '' ) {
            $success = $wp_dp_cs_success;
        } else {
            $success = wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_default_success_msg');
        }

        if ( trim($wp_dp_cs_error) && trim($wp_dp_cs_error) != '' ) {
            $error = $wp_dp_cs_error;
        } else {
            $error = wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_default_error_msg');
        }

        $wp_dp_cs_inline_script = '
		function wp_dp_cs_var_contact_frm_submit(form_id) {
			var wp_dp_cs_mail_id = \'' . esc_js($wp_dp_cs_email_counter) . '\';
			if (form_id == wp_dp_cs_mail_id) {
				var $ = jQuery;
				var thisObj = jQuery(\'.contact-btn-holder\');
				wp_dp_show_theme_loader(\'.contact-btn-holder\', \'\', \'button_loader\', thisObj);
				var datastring = $("#frm' . esc_js($wp_dp_cs_email_counter) . '").serialize() + "&wp_dp_cs_contact_email=' . esc_js($wp_dp_cs_contactus_send) . '&wp_dp_cs_contact_succ_msg=' . esc_js($success) . '&wp_dp_cs_contact_error_msg=' . esc_js($error) . '&action=wp_dp_cs_var_contact_submit";
                $.ajax({
					type: \'POST\',
					url: \'' . esc_js(esc_url(admin_url('admin-ajax.php'))) . '\',
					data: datastring,
					dataType: "json",
					success: function (response) {
						wp_dp_cs_show_response_theme(response, \'\', thisObj);
					}
				});
			}
		}';
        wp_dp_cs_inline_enqueue_script($wp_dp_cs_inline_script, 'wp-dp-custom-inline');

        $html .= '<div class="contact-form">';

        $html .= '<div class="form-holder row" id="ul_frm' . absint($wp_dp_cs_email_counter) . '">';
        $html .= '<form  name="frm' . absint($wp_dp_cs_email_counter) . '" id="frm' . absint($wp_dp_cs_email_counter) . '" action="javascript:wp_dp_cs_var_contact_frm_submit(' . absint($wp_dp_cs_email_counter) . ')" >';
        $html .= '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">';
        $html .= '<div class="field-holder">';
        if ( $wp_dp_contact_label_switch == 'yes' ) {
            $html .= '<strong>' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_first_name') . ' *</strong>';
        }
        $html .= '<div class="has-icon">';
        $html .= '<i class="icon-user4"></i>';
        $html .= '<input class="field-input" name="contact_name" type="text" placeholder="' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_first_name_placeholder') . '" required>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">';
        $html .= '<div class="field-holder">';
        if ( $wp_dp_contact_label_switch == 'yes' ) {
            $html .= '<strong>' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_email') . ' *</strong>';
        }
        $html .= '<div class="has-icon">';
        $html .= '<i class="icon-envelope3"></i>';
        $html .= '<input class="field-input" name="contact_email" type="text" placeholder="' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_email_address') . '" required>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">';
        $html .= '<div class="field-holder">';
        if ( $wp_dp_contact_label_switch == 'yes' ) {
            $html .= '<strong>' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_phone_number') . ' </strong>';
        }
        $html .= '<div class="has-icon">';
        $html .= '<i class="icon-phone4"></i>';
        $html .= '<input class="field-input" name="contact_number" type="text" placeholder="' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_phone_number_placeholder') . '">';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">';
        $html .= '<div class="field-holder">';
        if ( $wp_dp_contact_label_switch == 'yes' ) {
            $html .= '<strong>' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_last_name') . ' </strong>';
        }
        $html .= '<div class="has-icon">';
        $html .= '<i class="icon-align-left2"></i>';
        $html .= '<input class="field-input" name="contact_name_last" type="text" placeholder="' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_last_name_placeholder') . '" required>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
        $html .= '<div class="field-holder">';
        if ( $wp_dp_contact_label_switch == 'yes' ) {
            $html .= '<strong>' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_message_contact') . ' </strong>';
        }
        $html .= '<div class="has-icon has-textarea">';
        $html .= '<i class="icon-new-message"></i>';
        $html .= '<textarea name="contact_msg" placeholder="' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_message_contact_placeholder') . '"></textarea>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
        $html .= '<div class="field-holder contact-btn-holder">';
        $html .= '<input class="btn-holder bgcolor" type="submit" value="' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_button_text') . '">';
        $html .= '</div></div>';
        $html .= '</form>';
        $html .= '</div>';
        $html .= '</div>';

        if ( isset($column_class) && $column_class <> '' ) {
            $html .= '</div>';
        }
        if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
            $html .= '</div>';
        }
        return $html;
    }

}
if ( function_exists('wp_dp_cs_var_short_code') ) {
    wp_dp_cs_var_short_code('wp_dp_cs_contact_form', 'wp_dp_cs_var_contact_us_data');
}


// Contact form submit ajax
if ( ! function_exists('wp_dp_cs_var_contact_submit') ) {

    function wp_dp_cs_var_contact_submit() {

        define('WP_USE_THEMES', false);

        $strings = new wp_dp_cs_var_frame_all_strings;
        $strings->wp_dp_cs_var_frame_all_string_all();
        $check_box = '';
        $json = array();
        $wp_dp_cs_contact_error_msg = '';
        $subject_name = '';
        foreach ( $_REQUEST as $keys => $values ) {
            $$keys = $values;
        }

        $wp_dp_cs_danger_html = '<div class="alert alert-danger"><button class="close" type="button" data-dismiss="alert" aria-hidden="true">&times;</button><p><i class="icon-warning4"></i><span>';
        $wp_dp_cs_success_html = '<div class="alert alert-success"><button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button><p><i class="icon-warning4"></i><span>';
        $wp_dp_cs_msg_html = '</span></p></div>';

        $bloginfo = get_bloginfo();
        $wp_dp_cs_contactus_send = '';
        $subjecteEmail = "(" . $bloginfo . ") " . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_received');
        if ( '' == $wp_dp_cs_contact_email || ! preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $wp_dp_cs_contact_email) ) {
            $json['type'] = "error";
            $json['msg'] = esc_html($wp_dp_cs_contact_error_msg);
        } else {
            if ( ! preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $contact_email) ) {
                $json['type'] = 'error';
                $json['msg'] = esc_html(wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_valid_email'));
            } else if ( $contact_email == '' ) {
                $json['type'] = "error";
                $json['msg'] = esc_html(wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_email_should_not_be_empty'));
            } else {
                $message = '
				<table width="100%" border="1">
				  <tr>
					<td width="100"><strong>' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_full_name') . '</strong></td>
					<td>' . esc_html($contact_name) . '</td>
				  </tr>
				  <tr>
					<td><strong>' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_email') . '</strong></td>
					<td>' . esc_html($contact_email) . '</td>
				  </tr>';
                if ( $contact_number != '' ) {
                    $message .= '<tr>
					<td><strong>' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_phone_number') . '</strong></td>
					<td>' . esc_html($contact_number) . '</td>
				  </tr>';
                }
                if ( $contact_msg != '' ) {
                    $message .= '<tr>
					<td><strong>' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_text_here') . '</strong></td>
					<td>' . esc_html($contact_msg) . '</td>
				  </tr>';
                }
                if ( $check_box != '' ) {
                    $message .= '
				  <tr>
					<td><strong>' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_check_field') . '</strong></td>
					<td>' . esc_html($check_box) . '</td>
				  </tr>';
                }
                $message .= '
				  <tr>
					<td><strong>' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_contact_ip_address') . '</strong></td>
					<td>' . esc_html($_SERVER["REMOTE_ADDR"]) . '</td>
				  </tr>
				</table>';

                add_filter('wp_mail_content_type', function () {
                    return 'text/html';
                });

                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "From: " . $contact_name . " <" . $contact_email . ">\r\n";
                $headers .= "Reply-To: " . $contact_email . "\r\n";

                $respose = wp_mail($wp_dp_cs_contact_email, $subjecteEmail, $message, $headers);
                if ( $respose ) {
                    $json['type'] = "success";
                    $json['msg'] = esc_html($wp_dp_cs_contact_succ_msg);
                } else {
                    $json['type'] = "error";
                    $json['msg'] = esc_html($wp_dp_cs_contact_error_msg);
                };
            }
        }
        echo json_encode($json);
        die();
    }

}
//Submit Contact Us Form Hooks
add_action('wp_ajax_nopriv_wp_dp_cs_var_contact_submit', 'wp_dp_cs_var_contact_submit');
add_action('wp_ajax_wp_dp_cs_var_contact_submit', 'wp_dp_cs_var_contact_submit');
