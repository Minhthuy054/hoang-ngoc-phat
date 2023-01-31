<?php

namespace WP_Smart_Image_Resize\Events;

use WP_Smart_Image_Resize\Exceptions\Invalid_Image_Meta_Exception;
use WP_Smart_Image_Resize\Image_Meta;

class Image_Deleted extends Base_Event
{
    public function listen()
    {
        add_action( 'delete_attachment', [ $this, 'delete_webp_images' ] );
    }

    /**
     * Delete WebP images.
     *
     * @param int $attachmentId
     *
     * @return void
     */
    public function delete_webp_images( $attachmentId )
    {
        try{
            $imageMeta = new Image_Meta( $attachmentId );
            
            foreach ( $imageMeta->getSizeNames() as $sizeName ) {
                wp_delete_file( $imageMeta->getSizeFullPath( $sizeName, 'webp' ) );
            }

        }catch(Invalid_Image_Meta_Exception $e){

        }
      
    }
}
