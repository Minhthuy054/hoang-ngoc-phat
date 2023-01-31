<?php

namespace WP_Smart_Image_Resize;

/**
 * Check whether the given color hex is transparent or white.
 * @param string $color_hex
 * @return bool
 */
function is_clear_color($color_hex) {
    $color = maybe_hash_hex_color(strtolower($color_hex)) ?: false;
    return empty($color) || $color === '#fff' ||  $color === '#ffffff';
}

/**
 * @access private
 */
function is_transparent_color($color) {
    return empty($color) || strtolower($color) === 'transparent';
}

/**
 * @access private
 */
function position_to_coords($position, $container_size, $child_size) {
    $xy = [0, 0];

    switch ($position) {

        case 'top':
            $xy[0] = ($container_size['width'] - $child_size['width']) / 2;
            break;
        case 'bottom':
            $xy[0] = ($container_size['width'] - $child_size['width']) / 2;
            $xy[1] = ($container_size['height'] - $child_size['height']);
            break;
        case 'left':
            $xy[1] = ($container_size['height'] - $child_size['height']) / 2;
            break;
        case 'right':
            $xy[0] = ($container_size['width'] - $child_size['width']);
            $xy[1] = ($container_size['height'] - $child_size['height']) / 2;
            break;
        case 'center':
        default:
            $xy[0] = ($container_size['width'] - $child_size['width']) / 2;
            $xy[1] = ($container_size['height'] - $child_size['height']) / 2;
            break;
    }

    $xy[0]  = -max($xy[0], 0);
    $xy[1]  = -max($xy[1], 0);
    return $xy;
}


function is_woocommerce_size($size_name) {


    return wp_sir_is_woocommerce_activated() &&  in_array(
        $size_name,
        [
            'woocommerce_single',
            'woocommerce_thumbnail',
            'woocommerce_gallery_thumbnail',
            'shop_single',
            'shop_catalog',
            'shop_thumbnail'
        ],
        true
    );
}

function is_imagick_image($image){
    return is_object($image) && $image instanceof \Imagick;
}
function is_gd_image($image) {

    if (function_exists('\is_gd_image')) {
        return \is_gd_image($image);
    }

    if ( (is_resource($image) && get_resource_type($image) === 'gd')
        || (is_object($image) && $image instanceof \GdImage)
    ) {
        return true;
    }

    return false;
}
