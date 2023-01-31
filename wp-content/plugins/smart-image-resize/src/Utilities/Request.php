<?php

namespace WP_Smart_Image_Resize\Utilities;

class Request
{
    public static function is_referer($needles, $referer = false)
    {
        if (! $referer) {
            $referer = wp_get_referer();
        }

        if (! $referer || ! is_string($referer)) {
            return false;
        }

        foreach ((array) $needles as $needle) {
            if (strpos($referer, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    public static function is_front_end()
    {

        // Front end ajax request.
        if (! self::is_referer('/wp-admin/') && wp_doing_ajax()) {
            return true;
        }

        if(is_feed()){
            return false;
        
        }

        return ! is_admin();
    }
}
