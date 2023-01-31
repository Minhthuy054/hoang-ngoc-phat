<?php

/**
 * Ads html form for page builder
 */
if (!function_exists('wp_dp_banner_ads_shortcode')) {

    function wp_dp_banner_ads_shortcode($atts, $content = "") {
		
        $rand_id = rand(12345, 54321);
        global $wp_dp_plugin_options;
        $defaults = array('id' => '0');
        extract(shortcode_atts($defaults, $atts));

        $html = '';

        if (isset($wp_dp_plugin_options['wp_dp_banner_field_code_no']) && is_array($wp_dp_plugin_options['wp_dp_banner_field_code_no'])) {
            $i = 0;
            foreach ($wp_dp_plugin_options['wp_dp_banner_field_code_no'] as $banner) :
                if ($wp_dp_plugin_options['wp_dp_banner_field_code_no'][$i] == $id) {
                    break;
                }
                $i++;
            endforeach;

            $wp_dp_banner_title = isset($wp_dp_plugin_options['wp_dp_banner_title'][$i]) ? $wp_dp_plugin_options['wp_dp_banner_title'][$i] : '';
            $wp_dp_banner_style = isset($wp_dp_plugin_options['wp_dp_banner_style'][$i]) ? $wp_dp_plugin_options['wp_dp_banner_style'][$i] : '';
            $wp_dp_banner_type = isset($wp_dp_plugin_options['wp_dp_banner_type'][$i]) ? $wp_dp_plugin_options['wp_dp_banner_type'][$i] : '';
            $wp_dp_banner_image = isset($wp_dp_plugin_options['wp_dp_banner_image_array'][$i]) ? $wp_dp_plugin_options['wp_dp_banner_image_array'][$i] : '';
            $wp_dp_banner_url = isset($wp_dp_plugin_options['wp_dp_banner_field_url'][$i]) ? $wp_dp_plugin_options['wp_dp_banner_field_url'][$i] : '';
            $wp_dp_banner_url_target = isset($wp_dp_plugin_options['wp_dp_banner_target'][$i]) ? $wp_dp_plugin_options['wp_dp_banner_target'][$i] : '';
            $wp_dp_banner_adsense_code = isset($wp_dp_plugin_options['wp_dp_banner_adsense_code'][$i]) ? $wp_dp_plugin_options['wp_dp_banner_adsense_code'][$i] : '';
            $wp_dp_banner_code_no = isset($wp_dp_plugin_options['wp_dp_banner_field_code_no'][$i]) ? $wp_dp_plugin_options['wp_dp_banner_field_code_no'][$i] : '';
            $image_url = '';
            $image_url = wp_get_attachment_url($wp_dp_banner_image);
            $html .= '<div class="wp_dp_banner_section ad-'. $rand_id .'">';
            if ($wp_dp_banner_type == 'image') {
                if (!isset($_COOKIE["banner_clicks_" . $wp_dp_banner_code_no])) {
                    $html .= '<a onclick="wp_dp_banner_click_count_plus(\'' . admin_url('admin-ajax.php') . '\', \'' . $wp_dp_banner_code_no . '\', \'' . $rand_id . '\')" id="banner_clicks' . $rand_id . '" href="' . esc_url($wp_dp_banner_url) . '" target="_blank"><img src="' . esc_url($image_url) . '" alt="' . $wp_dp_banner_title . '" /></a>';
                } else {
                    $html .= '<a href="' . esc_url($wp_dp_banner_url) . '" target="' . $wp_dp_banner_url_target . '"><img src="' . esc_url($image_url) . '" alt="' . $wp_dp_banner_title . '" /></a>';
                }
            } else {
                $html .= htmlspecialchars_decode(stripslashes($wp_dp_banner_adsense_code));
            }
            $html .= '</div>';
        }
        $html .= '<script type="text/javascript">
			jQuery(document).ready(function() {
				function appendMessage(argument) {
					var div = jQuery(\'<div>\').attr(\'class\', \'widget ad-disabling-message\').html(\''. wp_dp_plugin_text_srt( 'wp_dp_ad_block_disabling' ) .'\');
					jQuery(\'.ad-'. $rand_id .'\').parent().after(div);
				}
				setTimeout(function(){
					if(jQuery(".widget.widget-ad").css(\'display\') == "none") {
						appendMessage();
					}
				}, 500);
			});
			function wp_dp_banner_click_count_plus(ajax_url, id, rand_id) {
				"use strict";
				var dataString = "code_id=" + id + "&action=wp_dp_banner_click_count_plus";
				jQuery.ajax({
					type: "POST",
					url: ajax_url,
					data: dataString,
					success: function (response) {
						if (response != "error") {
							jQuery("#banner_clicks" + rand_id).removeAttr("onclick");
						}
					}
				});
				return false;
			}
		</script>'; 
        return $html;

        return do_shortcode($html);
    }

    add_shortcode('wp_dp_ads', 'wp_dp_banner_ads_shortcode');
}