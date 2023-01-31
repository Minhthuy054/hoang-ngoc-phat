<?php

namespace WP_Smart_Image_Resize\Filters;

class Custom_Sizes extends Base_Filter {
    public function listen() {
        $this->load_custom_wc_sizes();
    }

    function load_custom_wc_sizes() {

        if (!wp_sir_is_woocommerce_activated()) {
            return;
        }

        $settings = wp_sir_get_settings();

        if (! $settings['enable']) {
            return;
        }
        $wc_sizes = ['single', 'thumbnail', 'gallery_thumbnail'];
        foreach ($wc_sizes as $size_name) {
            if (in_array('woocommerce_' . $size_name, $settings['sizes'], true) && !empty($settings['size_options']['woocommerce_' . $size_name])) {
                $size_data = ['width' => 0, 'height' => 0];
                $size_data = wp_parse_args($settings['size_options']['woocommerce_' . $size_name], $size_data);
                if ($size_data['width'] > 0 && $size_data['height'] > 0) {
                    add_filter('woocommerce_get_image_size_' . $size_name, function () use ($size_data) {
                        return ['width' => $size_data['width'], 'height' => $size_data['height'], 'crop' => 0];
                    });
                }
            }
        }
    }
}
