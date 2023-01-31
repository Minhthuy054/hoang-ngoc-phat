<?php

use WP_Smart_Image_Resize\Helper;

if (!function_exists('wp_sir_is_woocommerce_activated')) {
    function wp_sir_is_woocommerce_activated() {
        return class_exists('woocommerce');
    }
}
function _wp_sir_get_default_sizes() {
    return array_filter(
        get_intermediate_image_sizes(),
        function ($sizeName) {
            if (wp_sir_is_woocommerce_activated()) {
                return in_array($sizeName, [
                    'woocommerce_single',
                    'woocommerce_thumbnail',
                    'woocommerce_gallery_thumbnail',
                    'thumbnail',
                    'medium',
                    'medium_large',
                    'large'
                ]);
            } else {
                return in_array($sizeName, ['thumbnail', 'medium', 'medium_large', 'large']);
            }
        }
    );
}

function _wp_sir_get_default_settings() {
    return [
        'enable'                => 1,
        'bg_color'              => '#ffffff',
        'jpg_quality'           => 5,
        'sizes'                 => _wp_sir_get_default_sizes(),
        'jpg_convert'           => 0,
        'enable_webp'           => 0,
        'enable_trim'           => 0,
        'trim_feather'          => 10,
        'trim_tolerance'        => 3,
        'processable_images'    => [
            'post_types' => ['product'],
            'taxonomies' => [],
        ],
        'size_options' => [],
        'enable_watermark'=> 0,
        'watermark_size'=>50,
        'watermark_image'=>0,
        'watermark_position'=>'center',
        'watermark_opacity'=> 50,
        'watermark_offset'=>[
            'y'=> 0,
            'x'=> 0
        ]
    ];
}
if (!function_exists('wp_sir_get_settings')) {
    /**
     * Get plugin settings.
     *
     * @param string $context
     *
     * @return array
     */

    function wp_sir_get_settings($context = 'read') {

        $defaults = _wp_sir_get_default_settings();
        _wp_sir_set_compat_settings();

        $settings = wp_parse_args(get_option('wp_sir_settings'), $defaults);

        if (!isset($settings['processable_images']['post_types'])) {
            $settings['processable_images']['post_types'] = ['product'];
        }

        if (!isset($settings['processable_images']['taxonomies'])) {
            $settings['processable_images']['taxonomies'] = [];
        }
        if ($context === 'read') {
            if (in_array('woocommerce_single', $settings['sizes'])) {
                $settings['sizes'][] = 'shop_single';
            }

            if (in_array('woocommerce_thumbnail', $settings['sizes'])) {
                $settings['sizes'][] = 'shop_catalog';
            }
            if (in_array('woocommerce_gallery_thumbnail', $settings['sizes'])) {
                $settings['sizes'][] = 'shop_thumbnail';
            }

            if (isset($settings['size_options']['woocommerce_single'])) {
                $settings['size_options']['shop_single'] = $settings['size_options']['woocommerce_single'];
            }
            if (isset($settings['size_options']['woocommerce_thumbnail'])) {
                $settings['size_options']['shop_catalog'] = $settings['size_options']['woocommerce_thumbnail'];
            }

            if (isset($settings['size_options']['woocommerce_gallery_thumbnail'])) {
                $settings['size_options']['shop_thumbnail'] = $settings['size_options']['woocommerce_gallery_thumbnail'];
            }
        } else {
            $settings['sizes'] = array_filter($settings['sizes'], function ($size) {
                return !in_array($size, ['shop_single', 'shop_catalog', 'shop_thumbnail']);
            });
        }

        return apply_filters('wp_sir_settings', $settings);
    }
}

if (!function_exists('_wp_sir_set_compat_settings')) {

    /**
     * Backward compatibility with old versions settings.
     */
    function _wp_sir_set_compat_settings() {
        $settings = get_option('wp_sir_settings') ?: [];
        if (!empty($settings)) {
            return;
        }

        $legacy_settings = get_option('ppsir_settings');
        if (empty($legacy_settings)) {
            return;
        }

        $settings['enable'] = isset($legacy_settings['enable'])
            && $legacy_settings['enable']
            ? 1 : 0;

        if (isset($legacy_settings['bg_color'])) {
            $settings['bg_color'] = $legacy_settings['bg_color'];
        }
        if (isset($legacy_settings['jpg_quality'])) {
            $settings['jpg_quality'] = 100 - absint($legacy_settings['jpg_quality']);
        }

        add_option('wp_sir_settings', $settings);

        delete_option('ppsir_settings');
    }
}

/*
 * Get working images sizes
 *
 * @return array
 */
if (!function_exists('wp_sir_get_additional_sizes')) :

    /**
     * Return registered sizes' data.
     * TODO: Use `wp_get_registered_image_subsizes` for WP >= 5.3
     * 
     * @return array
     */
    function wp_sir_get_additional_sizes($context = 'read') {
        $sizes = wp_get_additional_image_sizes();

        foreach (['thumbnail', 'medium', 'medium_large', 'large'] as $name) {
            $sizes[$name] = [
                'width'  => (int)get_option("{$name}_size_w"),
                'height' => (int)get_option("{$name}_size_h"),
            ];
        }

        foreach ($sizes as $name => $size_data) {

            if ((int)$size_data['width'] === 0 || (int)$size_data['width'] >= 9999) {
                $sizes[$name]['width'] = (int)$size_data['height'];
            } elseif ((int)$size_data['height'] === 0 || (int)$size_data['height'] >= 9999) {
                $sizes[$name]['height'] = (int)$size_data['width'];
            }
        }

        // New HD sizes can lead to memory exhaustion error when resizing large images.
        // It would be better to disable them by default since the majority of shared server
        // don't have a good memory limit and most of time uploaded images are smaller than those sizes.
        // Developers can use this filter `wp_sir_enable_hd_sizes` to enable them.
        if (!apply_filters('wp_sir_enable_hd_sizes', false) && $context === 'view') {
            unset($sizes['2048x2048']);
            unset($sizes['1536x1536']);
        }

        // No need to display WooCommerce legacy sizes.
        if ($context === 'view') {
            unset($sizes['shop_single']);
            unset($sizes['shop_catalog']);
            unset($sizes['shop_thumbnail']);
        }
        $order = [
            'woocommerce_thumbnail', 'woocommerce_single', 'woocommerce_gallery_thumbnail',
            'shop_catalog', 'shop_single', 'shop_thumbnail', 'thumbnail', 'medium', 'medium_large', 'large'
        ];

        $sorted_sizes = [];
        foreach ($order as $size_name) {
            if (isset($sizes[$size_name])) {
                $sorted_sizes[$size_name] = $sizes[$size_name];
                unset($sizes[$size_name]);
            }
        }

        $sizes = $sorted_sizes + $sizes;
        return apply_filters('wp_sir_registered_sizes', $sizes);
    }
endif;

if (!function_exists('wp_sir_get_size_dimensions')) :

    /**
     * Get a given size name width/height.
     *
     * @param string $name
     * @access private
     * @internal
     *
     * @return array|null
     */
    function wp_sir_get_size_dimensions($name, $size_options  = null) {
        $size_data = null;
        if (!$size_options) {
            $size_options = wp_sir_get_settings()['size_options'];
        }

        $registered_sizes = wp_sir_get_additional_sizes();

        if (
            !empty($registered_sizes[$name])
            && !empty($registered_sizes[$name]['width'])
            && !empty($registered_sizes[$name]['height'])
        ) {
            $size_data = $registered_sizes[$name];
        }

        if (!empty($size_options[$name]['width'])) {
            $size_data['width'] = (int)$size_options[$name]['width'];
        }

        if (!empty($size_options[$name]['height'])) {
            $size_data['height'] = (int)$size_options[$name]['height'];
        }

        return $size_data;
    }
endif;

/**
 * Return processable post types.
 *
 * @return array
 */
function wp_sir_get_processable_post_types() {
    $processable_post_types = (array)wp_sir_get_settings()['processable_images']['post_types'];

    $processable_post_types = (array)apply_filters('wp_sir_resize_post_type', 'product', $processable_post_types);

    if (in_array('product', $processable_post_types, true)) {
        $processable_post_types[] = 'product_variation';
    }

    return $processable_post_types;
}

if (!function_exists('wp_sir_is_processable')) {

    /**
     * Returns true if the given attachment is attached to the given post type.
     * OPTIMIZEME.
     *
     * @param int $attachment_id
     *
     * @return bool
     */

    function wp_sir_is_processable($attachment_id) {
        return apply_filters('wp_sir_is_attached_to', _wp_sir_is_processable($attachment_id), $attachment_id);
    }

    function _wp_sir_is_processable($attachment_id) {
        global $wpdb;

        // Starting 1.4.0 processed images are marked with `_processed_at` meta.
        // With that said, we don't need to do heavy check to determine
        // whether the given image is processable, using `_processed_at`
        // would be enough.
        if (get_post_meta($attachment_id, '_processed_at', true)) {
            return true;
        }

        // Maybe a WC product import request.
        if (doing_action('wp_ajax_woocommerce_do_ajax_product_import')) {
            return true;
        }

        $attachment = get_post($attachment_id);
        if (empty($attachment) || is_wp_error($attachment)) {
            return false;
        }

        // Get processable post type.
        $processable_post_types = wp_sir_get_processable_post_types();

        // Process images with `post_parent`.
        if (
            $attachment->post_parent
            && in_array(
                get_post_type($attachment->post_parent),
                $processable_post_types,
                true
            )
        ) {
            return true;
        }
        // No post type to process, check directly with taxonomies.
        if (empty($processable_post_types)) {
            return wp_sir_is_term_image($attachment_id);
        }

        // Find images attached using `_thumbnail_id` or `_product_image_gallery`
        $post_type_placeholder = Helper::array_sql_placeholder($processable_post_types);

        $sql = "SELECT pm.post_id from {$wpdb->postmeta} pm
                JOIN {$wpdb->posts} p
                ON p.ID = pm.post_id
                WHERE pm.meta_key IN ('_thumbnail_id','_product_image_gallery')
                AND FIND_IN_SET(%d, pm.meta_value)
                AND p.post_type IN ($post_type_placeholder)";

        $post_ids = $wpdb->get_col($wpdb->prepare(
            $sql,
            array_merge([$attachment_id], $processable_post_types)
        ));

        $post_ids = array_unique(array_filter($post_ids));

        // Check whether the given image is attached to processable post types.
        if (!empty($post_ids)) {
            return true;
        }

        // Finally, Check whether the given image is attached to processable taxonomies.
        return wp_sir_is_term_image($attachment_id);
    }

    /*
     * Returns processable taxonomies.
     * @access private
     * @return array
     */

    if (!function_exists('wp_sir_get_processable_taxonomies')) {
        function wp_sir_get_processable_taxonomies() {
            // Get selected taxonomies.
            $taxonomies = wp_sir_get_settings()['processable_images']['taxonomies'];

            // Allow developers to filter processable taxonomies.
            return (array)apply_filters('wp_sir_allowed_taxonomies', $taxonomies);
        }
    }

    if (!function_exists('wp_sir_is_term_image')) {
        /**
         * Check whether the given image is attached to the processable taxonomies.
         * This expect attachment id meta to be named `thumbnail_id`. Otherwise, you can use filter
         * `wp_sir_processable_tax_query_args` and provide two parameters (`$attachment_id` and `taxonomy`) to change meta name.
         *
         * @param int $attachment_id
         * @return bool
         *
         * @todo Other taxonomies will be added in the future including "pwb-brand" (Perfect WooCommerce Brands plugin).
         */
        function wp_sir_is_term_image($attachment_id) {
            if (!apply_filters('wp_sir_enable_category_image', true)) {
                return false;
            }

            $processable_taxonomies = wp_sir_get_processable_taxonomies();

            if (empty($processable_taxonomies)) {
                return false;
            }

            $args = [
                'hide_empty' => false,
                'meta_query' => [
                    [
                        'key'   => 'thumbnail_id',
                        'value' => $attachment_id,
                    ],
                ],
            ];

            /*
             * @todo optimize query.
             * @since 1.4
             */
            foreach ($processable_taxonomies as $tax) {
                $count = wp_count_terms(
                    $tax,
                    apply_filters('wp_sir_processable_tax_query_args', $args, $tax, $attachment_id)
                );

                if (is_wp_error($count) || empty($count)) {
                    continue;
                }
                // At least one taxonomy should processable.
                if ((int)$count > 0) {
                    return true;
                }
            }

            return false;
        }
    }

    /**
     * Determine if the current request is an upload attachment request.
     * @return bool
     * @todo Use screen `async-upload` instead.
     */
    function wp_sir_is_attachment_upload() {
        $upload_attachment = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

        return 'upload-attachment' === $upload_attachment || isset($_POST['post_id']);
    }

    /**
     * @todo test
     */
    function wp_sir_is_product_image_rest_upload() {
        $route = wp_sir_maybe_get_rest_route();

        if (empty($route)) {
            return false;
        }

        $processable_routes = [
            '/wc/v3/products',
            '/wc/v2/products',
        ];

        return in_array($route, $processable_routes);
    }

    /**
     * Check whether the image is being uploaded through the frontend.
     * @return bool
     * @todo Optimize frontend detection line:499
     *
     * @see _wp_sir_is_frontend_upload()
     */
    function wp_sir_is_frontend_upload() {
        return apply_filters('wp_sir_doing_frontend_upload', _wp_sir_is_frontend_upload());
    }

    function _wp_sir_is_frontend_upload() {
        // Upload request ?
        if (!wp_sir_is_attachment_upload()) {
            return false;
        }

        // Dokan frontend upload?
        if (wp_sir_is_dokan_frontend_upload()) {
            return true;
        }

        // Can we process any upload through the frontend?
        if (!apply_filters('wp_sir_process_frontend_upload', false)) {
            return false;
        }

        // Upload made through the frontend interface ?
        return !Helper::is_referer('/wp-admin/');
    }

    if (!function_exists('wp_doing_ajax')) {

        /**
         * @see https://developer.wordpress.org/reference/functions/wp_doing_ajax/
         */
        function wp_doing_ajax() {
            apply_filters('wp_doing_ajax', defined('DOING_AJAX') && DOING_AJAX);
        }
    }
    function wp_sir_is_dokan_frontend_upload() {

        // Not through the admin interface.
        if (Helper::is_referer('/wp-admin/')) {
            return false;
        }

        if (function_exists('dokan_is_seller_enabled')) {

            return dokan_is_seller_enabled(get_current_user_id());
        }

        return get_user_meta(get_current_user_id(), 'dokan_enable_selling', true) === 'yes';
    }


    /**
     * Determine whether image is uploaded through media libray.
     */
    function wp_sir_is_media_library_upload() {
        if (!apply_filters('wp_sir_process_media_library_upload', false)) {
            return false;
        }

        // Upload request?
        if (wp_sir_is_attachment_upload()) {

            // Through HTML uploader?
            require_once(ABSPATH . 'wp-admin/includes/screen.php');

            if (function_exists('get_current_screen')) {
                $screen = get_current_screen();

                if ($screen && is_object($screen) && $screen->id === 'media') {
                    return true;
                }
            }


            // Or through default uploader.
            return Helper::is_referer('/media-new.php');
        }

        // Through WP Rest API?
        $route = wp_sir_maybe_get_rest_route();

        return !empty($route) && $route === '/wp/v2/media';
    }

    /**
     * Get current rest route if present.
     *
     * @return false|string
     */
    function wp_sir_maybe_get_rest_route() {
        if (!isset($GLOBALS['wp']) || !is_object($GLOBALS['wp']) || !isset($GLOBALS['wp']->query_vars['rest_route'])) {
            return false;
        }

        $rest_route = $GLOBALS['wp']->query_vars['rest_route'];

        if (empty($rest_route)) {
            return false;
        }

        return untrailingslashit($rest_route);
    }

    /**
     * Check if an post image is being uploaded.
     *
     * @param int $attachment_id
     *
     * @return bool
     */
    function wp_sir_is_post_image_upload($attachment_id) {
        $processable_post_types = wp_sir_get_processable_post_types();

        // Uploaded trough media library?
        if (wp_sir_is_attachment_upload()) {
            $post_type = false;

            if (isset($_REQUEST['post_id']) && !empty($_REQUEST['post_id'])) {
                $post_type = get_post_type($_REQUEST['post_id']);
            }

            $is_editing_tax = \WP_Smart_Image_Resize\Utilities\Request::is_referer('taxonomy=');
            // Check referer post type.
            if (empty($post_type)) {
                foreach ($processable_post_types as $referer_post_type) {
                    if (\WP_Smart_Image_Resize\Utilities\Request::is_referer('post_type=' . $referer_post_type) && !$is_editing_tax) {
                        return true;
                    }
                }
            }

            return in_array($post_type, $processable_post_types, true) && !$is_editing_tax;
        }

        // Uploaded through WC Rest API?
        return in_array('product', $processable_post_types, true) && wp_sir_is_product_image_rest_upload();
    }

    function _wp_sir_is_uploading_term_image($taxonomies) {
        // Uploading through Media Library.
        if (!wp_sir_is_attachment_upload()) {
            return false;
        }

        // Get Edit category page url.
        $referer = wp_get_referer();

        if (!$referer) {
            return false;
        }

        $url_parts = wp_parse_args(wp_parse_url($referer), [
            'path'  => '',
            'query' => '',
        ]);

        if (!Helper::is_referer(['edit-tags.php', 'term.php'], $url_parts['path'])) {
            return false;
        }

        wp_parse_str($url_parts['query'], $params);

        $params = wp_parse_args($params, [
            'taxonomy' => '',
        ]);

        return in_array($params['taxonomy'], $taxonomies, true);
    }

    function wp_sir_is_term_image_upload() {
        if (!apply_filters('wp_sir_enable_category_image', true)) {
            return false;
        }

        $taxonomies = wp_sir_get_processable_taxonomies();

        if (empty($taxonomies)) {
            return false;
        }

        if (in_array('product_cat', $taxonomies) && wp_sir_is_product_category_image_rest_upload()) {
            return apply_filters('wp_sir_is_uploading_term_image', true, $taxonomies);
        }

        return apply_filters(
            'wp_sir_is_uploading_term_image',
            _wp_sir_is_uploading_term_image($taxonomies),
            $taxonomies
        );
    }

    if (!function_exists('wp_sir_is_imagick_installed')) {

        /**
         * Return true if ImageMagick is installed on the server.
         * @deprecated
         */
        function wp_sir_is_imagick_installed() {
            return extension_loaded('imagick') && class_exists('Imagick');
        }
    }

    if (!function_exists('wp_sir_is_webp_installed')) {
        /**
         * Return true if WebP is installed on the server.
         * @return bool
         * @deprecated
         */
        function wp_sir_is_webp_installed() {
            return function_exists('imagewebp');
        }
    }

    if (!function_exists('wp_sir_regen_thumb_active')) {
        function wp_sir_regen_thumb_active() {
            return in_array(
                'regenerate-thumbnails/regenerate-thumbnails.php',
                apply_filters('active_plugins', get_option('active_plugins'))
            );
        }
    }
}


if (!function_exists('wp_sir_is_product_category_image_rest_upload')) {

    function wp_sir_is_product_category_image_rest_upload() {
        if (!function_exists('wp_sir_maybe_get_rest_route')) {
            return false;
        }

        $route = wp_sir_maybe_get_rest_route();

        if (empty($route)) {
            return false;
        }

        $processable_routes = [
            '/wc/v3/products/categories',
            '/wc/v2/products/categories',
        ];

        return in_array($route, $processable_routes);
    }
}


/**
 * @access private
 * @internal
 */
function _wp_sir_get_sizes_to_generate() {
    $sizeNames = apply_filters('wp_sir_sizes', wp_sir_get_settings()['sizes']);

    $size_options = wp_sir_get_settings()['size_options'];

    $sizes = [];

    foreach ($sizeNames as $sizeName) {
        $size = wp_sir_get_size_dimensions($sizeName, $size_options);

        if (!empty($size)) {
            $sizes[$sizeName] = $size;
        }
    }

    if (!apply_filters('wp_sir_enable_hd_sizes', false)) {
        unset($sizes['2048x2048']);
        unset($sizes['1536x1536']);
    }

    return apply_filters('wp_sir_sizes', $sizes);
}


/**
 * 
 * Return the resize fit mode for the given size.
 * 
 * @access private 
 * @internal
 */
function _wp_sir_exclude_size($size_name) {
    return in_array($size_name, _wp_sir_get_excluded_sizes(), true);
}


function _wp_sir_get_excluded_sizes($filtered = true){

    $size_options = wp_sir_get_settings()['size_options'];

    $excluded_sizes = [];
    foreach($size_options as $size_name => $option){

        if(!empty($option['fit_mode']) && $option['fit_mode'] !== 'contain'){
            $excluded_sizes[] = $size_name;
        }
    }
    
    if ( $filtered ) {
        $excluded_sizes = apply_filters('wp_sir_exclude_sizes', $excluded_sizes);

        if (in_array('woocommerce_single', $excluded_sizes)) {
            $excluded_sizes[] = 'shop_single';
        }

        if (in_array('woocommerce_thumbnail', $excluded_sizes)) {
            $excluded_sizes[] = 'shop_catalog';
        }
        
        if (in_array('woocommerce_gallery_thumbnail', $excluded_sizes)) {
            $excluded_sizes[] = 'shop_thumbnail';
        }

        $excluded_sizes = array_unique($excluded_sizes);
        
        if (! $excluded_sizes) {
            return [];
        }

        if (! is_array($excluded_sizes)) {
            $excluded_sizes = (array) $excluded_sizes;
        }
    }
     return $excluded_sizes;

}