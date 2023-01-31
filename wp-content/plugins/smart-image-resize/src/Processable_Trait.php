<?php

namespace WP_Smart_Image_Resize;

use WP_REST_Server;

trait Processable_Trait
{

    private function is_valid_image($id, $path){
        return  is_file($path) &&
                is_readable($path) &&
                wp_attachment_is_image( $id );
    }

    public function isProcessable( $image_id, $imageMeta ){
        $is_processable = apply_filters('wp_sir_is_image_processable', $this->_isProcessable($image_id, $imageMeta), $image_id);

        if($is_processable && !$this->is_valid_image($image_id, $imageMeta->getOriginalFullPath())){
            $is_processable = false;
        }
      
        return $is_processable;
    }


    /**
     * Check whether the given image is processable.
     *
     * @param int $image_id
     *
     * @return bool
     */
    function _isProcessable( $image_id, $imageMeta)
    {
        
        $cache = wp_cache_get( 'processable_image_' . $image_id, 'wp_sir_cache' );

        if ( $cache === 'yes' || $cache === 'no' ) {
            return $cache === 'yes';
        }

        if(! $this->is_valid_image( $image_id, $imageMeta->getOriginalFullPath() ) ){
            return false;
        }
        // Process any request with `_processable_image` parameter.
        // This can be used by developers to integrate with the plugin.
        if ( isset( $_REQUEST[ '_processable_image' ] ) ) {
            return filter_var( $_REQUEST[ '_processable_image' ], FILTER_VALIDATE_BOOLEAN );
        }

        $rest_params = $this->get_rest_request_params();
        if ( isset( $rest_params[ '_processable_image' ] ) ) {
            return filter_var( $rest_params[ '_processable_image' ], FILTER_VALIDATE_BOOLEAN );
        }

        // Process if post type featured image/gallery image is being uploaded.
        // This includes WC REST API requests as well.
        if ( wp_sir_is_post_image_upload( $image_id ) ) {
            return true;
        }

        // Process if term image is being uploaded.
        if ( wp_sir_is_term_image_upload() ) {
            return true;
        }
        // Maybe process images uploaded through Media Library.
        if ( wp_sir_is_media_library_upload() ) {
            return true;
        }

        // Frontend upload (including Dokan).
        if ( wp_sir_is_frontend_upload() ) {
            return true;
        }

        return wp_sir_is_processable( $image_id );
    }


    function get_rest_request_params()
    {

        // Backward compatibility with versions prior to WP v4.4
        if ( ! class_exists( WP_REST_Server::class ) ) {
            return [];
        }

        $params = json_decode( WP_REST_Server::get_raw_data(), true );

        if ( is_null( $params ) && JSON_ERROR_NONE !== json_last_error() ) {
            return [];
        }

        return $params;
    }
}