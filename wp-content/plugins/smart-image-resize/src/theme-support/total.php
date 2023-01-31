<?php
namespace WP_Smart_Image_Resize\Theme_Support;
/**
 * ----------------------------------------------------------------------------------------
 * Add theme support for Total.
 * ----------------------------------------------------------------------------------------
 */


function force_generated_image_tag($html){
  
    if( strpos( $html, 'woo-entry-image-main' ) !== false && has_post_thumbnail() ){
        $html = get_the_post_thumbnail(null, 'woocommerce_thumbnail');
    }

    return $html;
};

add_filter('wpex_post_thumbnail_html',__NAMESPACE__ . '\\force_generated_image_tag');
