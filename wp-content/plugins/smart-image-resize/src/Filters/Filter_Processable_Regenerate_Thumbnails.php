<?php

namespace WP_Smart_Image_Resize\Filters;

use Exception;
use WP_Smart_Image_Resize\Helper;

class Filter_Processable_Regenerate_Thumbnails extends Base_Filter
{

    public function listen()
    {
        add_filter( 'rest_attachment_query', [ $this, 'filter' ], 99, 2 );
        add_filter( 'rest_attachment_query', [ $this, 'add_start_id' ], 100, 2 );
    }

    public function add_start_id($args, $request){

        if ( !wp_sir_get_settings()[ 'enable' ] ) {
            return $args;
        }

        if ( !$request->get_param( 'is_regeneratable' ) ) {
            return $args;
        }
        
        if( empty( $args['post__in'] ) ){
            return $args;
        }
        
        $referer = wp_get_referer();
        if( !$referer ){
          return $args;
        }

        $referer = parse_url($referer,PHP_URL_QUERY);
        parse_str($referer, $params);

        if( empty( $params['start_id'] ) ){
            return $args;
        }

        $start_id = (int)$params['start_id'];

        $args['post__in'] = array_filter($args['post__in'], function($id) use($start_id){
               return $id >= $start_id;
        });

        return $args;
    }
    /**
     * TODO: handle '_processed_at' flag when a post type or tax is no longer processable.
     */
    public function filter_processable_images_hook()
    {
        global $wpdb;

        $post_types             = wp_sir_get_processable_post_types();
        $taxonomies             = wp_sir_get_processable_taxonomies();
        $post_types_placeholder = Helper::array_sql_placeholder( $post_types );
        $taxonomies_placeholder = Helper::array_sql_placeholder( $taxonomies );

        $sqlParts = [];

// Post-attached images to process.
        if ( !empty( $post_types ) ) {
            $sqlParts[] = "( SELECT pm.meta_value FROM $wpdb->posts p INNER JOIN $wpdb->postmeta pm ON pm.post_id = p.ID WHERE pm.meta_key IN ( '_thumbnail_id','_product_image_gallery' ) AND p.post_type IN ( $post_types_placeholder ) )";
        }

// Taxonomy-attached images to process.
        if ( !empty( $taxonomies ) ) {
            $sqlParts[] = "( SELECT tm.meta_value FROM $wpdb->termmeta tm INNER JOIN $wpdb->term_taxonomy tt ON tt.term_id = tm.term_id WHERE tm.meta_key = 'thumbnail_id' AND tt.taxonomy IN ( $taxonomies_placeholder ) )";
        }

// Already processed images.
        $sqlParts[] = "(SELECT p.ID FROM $wpdb->posts p INNER JOIN $wpdb->postmeta pm ON p.ID = pm.post_id WHERE pm.meta_key = '_processed_at' AND pm.meta_value != '' )";

        // Process images added via the "Additional variation images gallery" plugin.
        // @see /plugin-support/additional-variation-images-gallery.php.
        // $sqlParts[] = "(SELECT p.ID FROM $wpdb->posts p INNER JOIN $wpdb->postmeta pm ON p.ID = pm.post_id WHERE pm.meta_key = 'woo_variation_gallery_images' )";
        $sql = implode( ' UNION ', $sqlParts );

        if ( !empty( $taxonomies ) || !empty( $post_types ) ) {
            $sql = $wpdb->prepare( $sql, array_merge( $post_types, $taxonomies ) );
        }

        $image_ids = $wpdb->get_col( $sql );
        $image_ids = explode( ',', implode( ',', $image_ids ) );

        // Do some clean up.
        return array_map( 'intval', array_unique( array_filter( $image_ids ) ) );

    }

      /**
     * Filter out processable images
     *
     * @param array $args
     * @param \WP_REST_Request $request
     * @return array|null
     */
    public function filter($args, $request ){
        if ( !wp_sir_get_settings()[ 'enable' ] ) {
            return $args;
        }

        if ( !$request->get_param( 'is_regeneratable' ) ) {
            return $args;
        }

        // Let developers disable filtering.
        if ( !apply_filters( 'wp_sir_filter_processable_images_rt', true ) ) {
            return $args;
        }

        $image_ids = $this->filter_processable_images();

        if ( empty( $image_ids ) ) {
            $image_ids = [ -1 ];
        }

        $args[ 'post__in' ] = $image_ids;

        return $args;

    }

  
    public function filter_processable_images()
    {
        try {
       
            if ( !has_filter( 'wp_sir_is_attached_to' ) ) {
                $image_ids = $this->filter_processable_images_hook();
            } else {
                global $wpdb;

                $sql = "SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%' AND post_status != 'trash'";

                $image_ids = $wpdb->get_col( $sql );
                $image_ids = array_filter( $image_ids, 'wp_sir_is_processable' );
                
            }

           
        } catch ( Exception $e ) {
        }
        return $image_ids;
    }

}