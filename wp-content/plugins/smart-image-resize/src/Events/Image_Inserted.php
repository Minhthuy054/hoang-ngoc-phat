<?php

namespace WP_Smart_Image_Resize\Events;

use Exception;

class Image_Inserted extends Base_Event
{
    public function listen()
    {
        add_action( 'add_attachment', [ $this, 'maybeRegenerateThumbnails' ] );
        add_action('woocommerce_product_import_inserted_product_object', [ $this, 'processOnImport' ] );
    }

    function processOnImport($product){

        if ( ! apply_filters( 'wp_sir_force_thumbnails_regeneration', false ) ) {
            return;
        }

       try{

        $imageIds = array_filter( array_merge( $product->get_gallery_image_ids(), [ $product->get_image_id() ]));

        foreach( $imageIds as $imageId ){    
            
            $metadata = wp_generate_attachment_metadata( $imageId, get_attached_file($imageId) );
           
            if( is_wp_error($metadata) || empty($metadata) ){
                continue;
            }
            
            wp_update_attachment_metadata( $imageId, $metadata );
            
          }
       }
       catch(Exception $e){}
    }
    /**
     * Maybe generate thumbnails for the given image.
     * 
     * @param int $imageId
     *
     * @return void
     */
    public function maybeRegenerateThumbnails( $imageId )
    {
        if ( ! apply_filters( 'wp_sir_force_thumbnails_regeneration', false ) ) {
            return;
        }

        try{

        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        
        $originalImagePath = get_attached_file( $imageId );

        if( ! file_exists( $originalImagePath ) ){  return; }
        
        $newMetadata = wp_generate_attachment_metadata( $imageId,  $originalImagePath );
        
        wp_update_attachment_metadata( $imageId, $newMetadata );

        }catch(Exception $e){}
    }
}
