<?php
namespace WP_Smart_Image_Resize\Theme_Support;

/**
 * ----------------------------------------------------------------------------------------
 * Add theme support for REHub.
 * ----------------------------------------------------------------------------------------
 */


function force_generated_image_url($url){
  
    if( strpos($url, 'wooproductph') !== false && has_post_thumbnail()){
        $url = get_the_post_thumbnail_url(null, 'woocommerce_thumbnail');
    }

    return $url;
};

add_filter('rh_static_resized_url',__NAMESPACE__ . '\\force_generated_image_url');
