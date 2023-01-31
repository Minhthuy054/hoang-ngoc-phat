<?php

/*
 * Directory Box Add Listing
 * Shortcode
 * @retrun markup
 */

if (!function_exists('wp_dp_add_listing_shortcode')) {

    function wp_dp_add_listing_shortcode($atts, $content = "") {
        $defaults = array('listing_title' => '', 'title_align' => '',);

        extract(shortcode_atts($defaults, $atts));
        $html = '';

        ob_start();
        $page_element_size = isset($atts['wp_dp_add_listing_element_size']) ? $atts['wp_dp_add_listing_element_size'] : 100;
        if (function_exists('wp_dp_cs_var_page_builder_element_sizes')) {
            echo '<div class="' . wp_dp_cs_var_page_builder_element_sizes($page_element_size) . ' ' . $title_align . '">';
        }
        $listing_add_settings = array(
            'return_html' => false,
        );
        if (is_user_logged_in() && current_user_can('wp_dp_member')) {
            do_action('wp_dp_listing_add', $listing_add_settings);
        } else if (!is_user_logged_in()) {
            do_action('wp_dp_listing_add', $listing_add_settings);
        } else {
            echo wp_dp_plugin_text_srt('wp_dp_add_listing_not_authorized');
        }
        if (function_exists('wp_dp_cs_var_page_builder_element_sizes')) {
            echo '</div>';
        }
        $html .= ob_get_clean();

        return $html;
    }

    add_shortcode('wp_dp_add_listing', 'wp_dp_add_listing_shortcode');
}