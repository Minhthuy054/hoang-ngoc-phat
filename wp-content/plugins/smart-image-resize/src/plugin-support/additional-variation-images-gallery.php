<?php

namespace WP_Smart_Image_Resize\Plugin_Support;

/*
|--------------------------------------------------------------------------
| Add support for Additional Variation Images Gallery.
| @link https://wordpress.org/plugins/woo-variation-gallery/
|--------------------------------------------------------------------------
*/

function process_variation_images($variation_id){

    $images = get_post_meta($variation_id, 'woo_variation_gallery_images', true);

    foreach($images as $image_id){

        if( get_post_meta($image_id, '_processed_at', true)){
            continue;
        }

        $file_path = get_attached_file($image_id);

        if(! is_readable($file_path) ){
            return;
        }

        require_once ABSPATH . 'wp-admin/includes/image.php';
        
        $meta = wp_generate_attachment_metadata($image_id, $file_path);

        if(!empty($meta) && is_array($meta) && isset($meta['_processed_at'])){
            wp_update_attachment_metadata($image_id, $meta);
        }
    }
}

add_action('woocommerce_save_product_variation', __NAMESPACE__ . '\\process_variation_images', 9999);