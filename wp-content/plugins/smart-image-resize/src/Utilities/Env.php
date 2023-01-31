<?php

namespace WP_Smart_Image_Resize\Utilities;

use Imagick;

use function WP_Smart_Image_Resize\is_gd_image;

class Env {

    /**
     * Check whether Imagick is available.
     * 
     * @return bool
     */
    public static function imagick_loaded() {

        $loaded = wp_cache_get('imagick_loaded', 'wp_sir_cache');

        if( empty( $loaded ) ) {
            $loaded = ( extension_loaded('imagick') && class_exists(Imagick::class) ) ? 'yes' : 'no';
            wp_cache_set('imagick_loaded', $loaded , 'wp_sir_cache');
        }

        return $loaded === 'yes';
    }

    /**
     * Check whether GD is available
     * 
     * @return bool
     */
    public static function gd_loaded() {
        $loaded =  wp_cache_get('gd_loaded', 'wp_sir_cache');

        if( empty( $loaded ) ){
            $loaded = ( extension_loaded('gd') && function_exists('gd_info') ) ? 'yes' : 'no';
            wp_cache_set('gd_loaded', $loaded, 'wp_sir_cache');
        }

        return $loaded === 'yes';
    }

    /**
     * Check whether Imagick extension is available and supports WebP.
     * 
     * @return bool
     */
    public static function imagick_supports_webp() {
        $imagick_supports_webp =  wp_cache_get('imagick_supports_webp', 'wp_sir_cache');
        if( empty( $imagick_supports_webp ) ){
            $imagick_supports_webp = ( static::imagick_loaded() && Imagick::queryFormats('WEBP') ) ? 'yes' : 'no';
            wp_cache_set('imagick_supports_webp', $imagick_supports_webp, 'wp_sir_cache');
        }

        return $imagick_supports_webp === 'yes';
    }

    /**
     * Check whether GD extension is available and supports WebP.
     * 
     * @return bool
     */

    public static function gd_supports_webp() {
        return function_exists('imagewebp');
    }

    /**
     * Determine which available image processor to use depending on user settings.
     * 
     * @return string
     */
    public static function active_image_processor($filtered = true) {
        $default = Env::imagick_loaded() ? 'imagick' : 'gd';

        $settings = wp_sir_get_settings();

        if (
            $default === 'imagick' &&
            $settings['enable_webp'] &&
            !static::imagick_supports_webp() &&
            !$settings['enable_trim'] &&
            static::gd_loaded()
            && static::gd_supports_webp()
        ) {
            $default = 'gd';
        }
 
        if ( !$filtered ) {
            return $default;
        }

        $filtered = apply_filters('wp_sir_driver', $default);

        if ( in_array( strtolower( $filtered ), [ 'imagick', 'gd' ], true ) ) {
            return $filtered;
        }

        return $default;
    }

    /**
     * Get the installed Imagick version.
     * 
     * @return string
     */
    public static function getImagickVersion() {
        if (!class_exists('\Imagick')) {
            throw new \RuntimeException('Imagick not installed');
        }

        $version = \Imagick::getVersion();
        preg_match('/ImageMagick ([0-9]+\.[0-9]+\.[0-9]+)/', $version['versionString'], $version);
        return $version[1];
    }

    /**
     * Get the available processor that supports WebP images or false otherwise.
     * 
     * @return string|bool
     */
    public static function get_webp_image_processor() {

        if (static::imagick_loaded() && static::imagick_supports_webp()) {
            return 'imagick';
        }

        if (static::gd_loaded() && static::gd_supports_webp()) {
            return 'gd';
        }

        return false;
    }

    /**
     * Check whether the browser supports WebP images.
     * 
     * @return bool
     */
    public static function browser_supposts_webp() {
        return (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false)
            || isset($_COOKIE['_http_accept:image/webp']);
    }

  
    public static function get_webp_fallback_image_processor($image) {
        
        $fallback = wp_cache_get('webp_fallback_image_processor', 'wp_sir_cache');
        
        if( empty( $fallback )  ){
            if($image instanceof \Imagick && static::gd_supports_webp()) {
                $fallback =  'gd';
            }
    
           if(is_gd_image($image) && static::imagick_supports_webp()) {
                $fallback =  'imagick';
            }

            wp_cache_set('webp_fallback_image_processor', $fallback, 'wp_sir_cache');
        }

        return $fallback;
    }


}
