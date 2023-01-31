<?php
/**
 * File Type: Listing Posted By
 */
if (!class_exists('wp_dp_images_gallery')) {

    class wp_dp_images_gallery {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_filter('wp_dp_images_gallery_admin_fields', array($this, 'wp_dp_images_gallery_admin_fields_callback'), 11, 2);
            add_action('save_post', array($this, 'wp_dp_images_gallery_on_submission'), 14);
        }
        
        public function wp_dp_images_gallery_admin_fields_callback( $post_id, $listing_type_slug ){
            global $wp_dp_html_fields, $post;
            $post_id                = ( isset( $post_id ) && $post_id != '' )? $post_id : $post->ID;
            $listing_type_post      = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish' ));
            $listing_type_id        = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
            $wp_dp_full_data    = get_post_meta( $listing_type_id, 'wp_dp_full_data', true );
            $html                   = '';
            if ( !isset( $wp_dp_full_data['wp_dp_image_gallery_element'] ) || $wp_dp_full_data['wp_dp_image_gallery_element'] != 'on' ){
                return $html = '';
            }
            
            
            $html   .= $wp_dp_html_fields->wp_dp_heading_render(
                    array(
                        'name' => wp_dp_plugin_text_srt( 'wp_dp_listing_image_gallery' ),
                        'cust_name' => 'images_gallery',
                        'classes' => '',
                        'std' => '',
                        'echo' => false,
                        'description' => '',
                        'hint' => ''
                    )
            );
            
            $html   .= '<div id="post_detail_gallery">';
                $wp_dp_opt_array = array(
                    'name' => wp_dp_plugin_text_srt( 'wp_dp_listing_gallery_image' ),
                    'id' => 'detail_page_gallery',
                    'post_id' => $post_id,
                    'classes' => '',
                    'echo' => false,
                    'std' => '',
                );

                $html   .= $wp_dp_html_fields->wp_dp_gallery_render( $wp_dp_opt_array );
            $html   .= '</div>';
            return $html;
        }
        
        public function wp_dp_images_gallery_on_submission( $post_id ){
            if ( get_post_type( $post_id ) == 'listings' ){
                if( wp_dp_get_input( 'wp_dp_detail_page_gallery_ids', NULL, 'ARRAY' ) === NULL ){
                    delete_post_meta ( $post_id, 'wp_dp_detail_page_gallery_ids' );
                }
            }
        }
        
    }
    global $wp_dp_images_gallery;
    $wp_dp_images_gallery    = new wp_dp_images_gallery();
}