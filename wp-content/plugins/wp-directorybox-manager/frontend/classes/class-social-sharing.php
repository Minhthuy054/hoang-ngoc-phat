<?php

/**
 * File Type: Header Element
 */
if (!class_exists('Wp_dp_Social_Sharing')) {

    class Wp_dp_Social_Sharing {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_dp_social_sharing', array($this, 'wp_dp_social_sharing_function'));
        }

        /* ----------------------------------------------------------------
          // @Social Sharing Function
          /---------------------------------------------------------------- */

        public function wp_dp_social_sharing_function() {


            global $post, $wp_dp_plugin_options, $wp_dp_theme_options;
            $html = '';
            $wp_dp_social_share = isset($wp_dp_plugin_options['wp_dp_social_share']) ? $wp_dp_plugin_options['wp_dp_social_share'] : '';
            $wp_dp_var_twitter = isset($wp_dp_plugin_options['wp_dp_twitter_share']) ? $wp_dp_plugin_options['wp_dp_twitter_share'] : '';
            $wp_dp_var_facebook = isset($wp_dp_plugin_options['wp_dp_facebook_share']) ? $wp_dp_plugin_options['wp_dp_facebook_share'] : '';
            $wp_dp_var_google_plus = isset($wp_dp_plugin_options['wp_dp_google_plus_share']) ? $wp_dp_plugin_options['wp_dp_google_plus_share'] : '';
            $wp_dp_var_tumblr = isset($wp_dp_plugin_options['wp_dp_tumblr_share']) ? $wp_dp_plugin_options['wp_dp_tumblr_share'] : '';
            $wp_dp_var_dribbble = isset($wp_dp_plugin_options['wp_dp_dribbble_share']) ? $wp_dp_plugin_options['wp_dp_dribbble_share'] : '';
            $wp_dp_var_share = isset($wp_dp_plugin_options['wp_dp_var_stumbleupon_share']) ? $wp_dp_plugin_options['wp_dp_var_stumbleupon_share'] : '';
            $wp_dp_var_stumbleupon = isset($wp_dp_plugin_options['wp_dp_stumbleupon_share']) ? $wp_dp_plugin_options['wp_dp_stumbleupon_share'] : '';
            $wp_dp_var_sharemore = isset($wp_dp_plugin_options['wp_dp_share_share']) ? $wp_dp_plugin_options['wp_dp_share_share'] : '';
            $wp_dp_pintrest_share = isset($wp_dp_plugin_options['wp_dp_pintrest_share']) ? $wp_dp_plugin_options['wp_dp_pintrest_share'] : '';
            $wp_dp_instagram_share = isset($wp_dp_plugin_options['wp_dp_instagram_share']) ? $wp_dp_plugin_options['wp_dp_instagram_share'] : '';
            if (isset($wp_dp_social_share) && 'on' === $wp_dp_social_share) {
                wp_dp_addthis_script_init_method();
                $html = '';

                $single = false;
                if (is_single()) {
                    $single = true;
                }

                $path = trailingslashit(get_template_directory_uri()) . "include/assets/images/";
                if ($wp_dp_var_twitter == 'on' or $wp_dp_var_sharemore == 'on' or $wp_dp_var_facebook == 'on' or $wp_dp_var_google_plus == 'on' or $wp_dp_var_tumblr == 'on' or $wp_dp_var_dribbble == 'on' or $wp_dp_var_share == 'on' or $wp_dp_var_stumbleupon == 'on') {
                    $html .='<ul class="dp-social-sharing-links">';
                    if (isset($wp_dp_var_facebook) && $wp_dp_var_facebook == 'on') {
                        if ($single == true) {
                            $html .='<li><a class="addthis_button_facebook" data-original-title="'. wp_dp_plugin_text_srt('wp_dp_social_sharing_facebook') .'"><i class="icon-facebook3"></i></a></li>';
                        } else {
                            $html .='<li><a class="addthis_button_facebook" data-original-title="'. wp_dp_plugin_text_srt('wp_dp_social_sharing_facebook') .'"><i class="icon-facebook3"></i></a></li>';
                        }
                    }
                    if (isset($wp_dp_var_twitter) && $wp_dp_var_twitter == 'on') {

                        if ($single == true) {
                            $html .='<li><a class="addthis_button_twitter"  data-original-title="'. wp_dp_plugin_text_srt('wp_dp_social_sharing_twitter') .'"><i class="icon-twitter3"></i></a></li>';
                        } else {
                            $html .='<li><a class="addthis_button_twitter"  data-original-title="'. wp_dp_plugin_text_srt('wp_dp_social_sharing_twitter') .'"><i class="icon-twitter3"></i></a></li>';
                        }
                    }
                    /*if (isset($wp_dp_var_google_plus) && $wp_dp_var_google_plus == 'on') {

                        if ($single == true) {
                            $html .='<li><a class="addthis_button_google" data-original-title="'. wp_dp_plugin_text_srt('wp_dp_social_sharing_google_plus') .'"><i class="icon-google"></i></a></li>';
                        } else {
                            $html .='<li><a class="addthis_button_google" data-original-title="'. wp_dp_plugin_text_srt('wp_dp_social_sharing_google_plus') .'"><i class="icon-google"></i></a></li>';
                        }
                    }*/
                    if (isset($wp_dp_var_tumblr) && $wp_dp_var_tumblr == 'on') {

                        if ($single == true) {
                            $html .='<li><a class="addthis_button_tumblr" data-original-title="'. wp_dp_plugin_text_srt('wp_dp_social_sharing_tumbler') .'"><i class="icon-tumblr3"></i></a></li>';
                        } else {
                            $html .='<li><a class="addthis_button_tumblr" data-original-title="'. wp_dp_plugin_text_srt('wp_dp_social_sharing_tumbler') .'"><i class="icon-tumblr3""></i></a></li>';
                        }
                    }

                    if (isset($wp_dp_var_dribbble) && $wp_dp_var_dribbble == 'on') {
                        if ($single == true) {
                            $html .='<li><a class="addthis_button_dribbble" data-original-title="'. wp_dp_plugin_text_srt('wp_dp_social_sharing_dribble') .'"><i class="icon-dribbble3"></i></a></li>';
                        } else {
                            $html .='<li><a class="addthis_button_dribbble" data-original-title="'. wp_dp_plugin_text_srt('wp_dp_social_sharing_dribble') .'"><i class="icon-dribbble3"></i></a></li>';
                        }
                    }
                    if (isset($wp_dp_var_stumbleupon) && $wp_dp_var_stumbleupon == 'on') {
                        if ($single == true) {
                            $html .='<li><a class="addthis_button_stumbleupon" data-original-title="'. wp_dp_plugin_text_srt('wp_dp_social_sharing_stumbleupon') .'"><i class="icon-stumbleupon"></i></a></li>';
                        } else {
                            $html .='<li><a class="addthis_button_stumbleupon" data-original-title="'. wp_dp_plugin_text_srt('wp_dp_social_sharing_stumbleupon') .'"><i class="icon-stumbleupon"></i></a></li>';
                        }
                    }
                    if (isset($wp_dp_var_sharemore) && $wp_dp_var_sharemore == 'on') {
                        $html .='<li><a class="cs-more addthis_button_compact"><i class="icon-share"></a></li>';
                    }
					$html .='</ul>';
                }
                echo balanceTags($html, true);
            }
        }

    }

    global $Wp_dp_Social_Sharing;
    $Wp_dp_Social_Sharing = new Wp_dp_Social_Sharing();
}