<?php

namespace WP_Smart_Image_Resize;

use Exception;
use WP_Smart_Image_Resize\Exceptions\Invalid_Image_Meta_Exception;
use WP_Smart_Image_Resize\Utilities\File;

class Image_Meta
{
    /**
     * @var array The image metadata.
     */
    public $data = [];

    /**
     * @var int The image post ID.
     */
    protected $id;


    /**
     * Save a bakup of the initial instance.
     */
    public $backup = null;

    public function __construct( $id, $data = [] )
    {
        $this->id = $id;

        $this->setData( $data );
    }

    /**
     * Set image's metadata.
     *
     * @param array $data
     *
     * @throws
     */
    public function setData( $data )
    {

        if ( empty( $data ) || ! is_array( $data ) ) {
            $data = wp_get_attachment_metadata( $this->id );
        }

        if ( ! $this->isValid( $data ) ) {
            throw new Invalid_Image_Meta_Exception();
        }

        $defaults = [
            '_trimmed_width'  => '',
            '_trimmed_height' => '',
            '_mime-type'      => '',
            'sizes'           => []
        ];

        $this->data = wp_parse_args( $data, $defaults );

        $this->data[ 'sizes' ][ 'full' ] = [
            'file'      => basename( $this->data[ 'file' ] ),
            'width'     => $this->data[ 'width' ],
            'height'    => $this->data[ 'height' ],
            'mime-type' => $this->data[ '_mime-type' ],
        ];

        $this->checkTrimmedDimensions();

    }

    /**
     * Set trim dimensions
     */
    private function checkTrimmedDimensions()
    {
        if ( ! empty( $this->data[ '_trimmed_width' ] )
             && ! empty( $this->data[ '_trimmed_height' ] ) ) {
            return;
        }

        if ( ! wp_sir_get_settings()[ 'enable_trim' ] ) {
            return;
        }


        try {

            // At this time, only original webp image get trimmed.
            $webpPath = $this->getOriginalFullPath( 'webp' );

            if ( ! is_readable( $webpPath ) ) {
                return;
            }

            wp_raise_memory_limit( 'image' );
            $image = ( new Image_Manager())->make( $webpPath );

            $this->data[ '_trimmed_width' ]  = $image->getWidth();
            $this->data[ '_trimmed_height' ] = $image->getHeight();

            $image->destroy();

        } catch ( Exception $e ) {
        }

    }

    public function setMetaItem( $key, $value )
    {
        $this->data[ $key ] = $value;
    }

    public function getMetaItem( $key )
    {
        return isset( $this->data[ $key ] ) ? $this->data[ $key ] : false;
    }

    public function toArray( $raw = false )
    {
        if ( ! $raw ) {
            unset( $this->data[ 'sizes' ][ 'full' ] );
        }

        return $this->data;
    }

    /**
     * Check whether the metadata is not corrupted.
     *
     * @param array $data
     *
     * @return bool
     */
    public function isValid( $data )
    {
        return ! is_wp_error( $data ) &&
               wp_attachment_is_image( $this->id ) &&
               is_array( $data ) && ! empty( $data ) &&
               isset( $data[ 'file' ] ) && ! empty( $data[ 'file' ] );
    }

    /**
     * Compute and return original image full path
     * and change extension if present.
     *
     * @param string|bool $extension
     *
     * @return string
     */
    public function getOriginalFullPath( $extension = false )
    {
        $uploadsDir = wp_get_upload_dir()[ 'basedir' ];

        if ( ! $extension ) {
            $path = path_join( $uploadsDir, $this->data[ 'file' ] );
            if ( is_readable( $path ) ) {
                return $path;
            }

            return get_attached_file( $this->id );
        }

        return path_join( $uploadsDir,
            trailingslashit( $this->getRelativeDirectory() ) . File::mb_pathinfo( $this->data[ 'file' ],
                PATHINFO_FILENAME ) . '.' . $extension );

    }

    /**
     * Get image relative path to uploads directory.
     * @return string
     */
    public function getRelativeDirectory()
    {
        return dirname( $this->data[ 'file' ] );
    }

    /**
     * Compute and return the given intermediate size
     * full path and change extension if present.
     *
     * @param string $sizeName
     * @param string|bool $extension
     *
     * @return string|false
     */
    public function getSizeFullPath( $sizeName, $extension = false )
    {
        $sizeData = $this->getSizeData( $sizeName );

        if ( empty( $sizeData ) ) {
            return false;
        }

        $uploadsDir = wp_get_upload_dir()[ 'basedir' ];

        if ( ! $extension ) {
            return path_join( $uploadsDir,
                trailingslashit( $this->getRelativeDirectory() ) . $sizeData[ 'file' ] );
        }

        return path_join( $uploadsDir,
            trailingslashit( $this->getRelativeDirectory() ) . File::mb_pathinfo( $sizeData[ 'file' ],
                PATHINFO_FILENAME ) . '.' . $extension );
    }

    public function hasSize( $sizeName )
    {
        return isset( $this->data[ 'sizes' ][ $sizeName ] )
               && ! empty( $this->data[ 'sizes' ][ $sizeName ] );
    }

    public function setBackup()
    {
        $this->backup = clone $this;
    }

    public function getBackup()
    {
        return $this->backup;
    }

    public function getSizeData( $sizeName = false, $trimmed = false )
    {
        if ( ! $this->hasSize( $sizeName ) ) {
            return false;
        }

        $sizeData = $this->data[ 'sizes' ][ $sizeName ];

        if ( empty( $sizeData ) ) {
            return false;
        }

        if ( $sizeName === 'full' && $trimmed ) {
            if ( ! empty( $this->data[ '_trimmed_width' ] ) ) {
                $sizeData[ 'width' ] = $this->data[ '_trimmed_width' ];
            }
            if ( ! empty( $this->data[ '_trimmed_height' ] ) ) {
                $sizeData[ 'height' ] = $this->data[ '_trimmed_height' ];
            }
        }

        return $sizeData;
    }

    public function setSizeData( $sizeName, $data )
    {
        $this->data[ 'sizes' ][ $sizeName ] = $data;
    }

    public function getOriginalUrl( $extension = false )
    {
        $uploadsUrl = wp_get_upload_dir()[ 'baseurl' ];

        if ( ! $extension ) {
            return $uploadsUrl . '/' . $this->data[ 'file' ];
        }

        return $uploadsUrl . '/' . trailingslashit( $this->getRelativeDirectory() ) . File::mb_pathinfo( $this->data[ 'file' ],PATHINFO_FILENAME) . '.' . $extension;
    }

    public function getSizeUrl( $sizeName, $extension = false )
    {
        $sizeData = $this->getSizeData( $sizeName );

        if ( ! $sizeData ) {
            return false;
        }

        $baseUrl = trailingslashit( wp_get_upload_dir()[ 'baseurl' ] )
                   . trailingslashit( $this->getRelativeDirectory() );

        if ( ! $extension ) {
            return $baseUrl . $sizeData[ 'file' ];
        }

        return $baseUrl . File::mb_pathinfo( $sizeData[ 'file' ],
                PATHINFO_FILENAME ) . '.' . $extension;
    }

    public function getSizes( $namesOnly = false )
    {
        return $namesOnly
            ? array_keys( $this->data[ 'sizes' ] )
            : $this->data[ 'sizes' ];
    }

    public function getSizeNames()
    {
        return $this->getSizes( true );
    }

    public function clearSizes()
    {
        $fullSize                        = $this->data[ 'sizes' ][ 'full' ];
        $this->data[ 'sizes' ]           = [];
        $this->data[ 'sizes' ][ 'full' ] = $fullSize;
    }


    public function setMimeType( $mime )
    {
        $this->data[ '_mime-type' ]                     = $mime;
        $this->data[ 'sizes' ][ 'full' ][ 'mime-type' ] = $mime;
    }

    public function setSizeExtension( $sizeName, $extension )
    {
        $path = $this->getSizeFullPath( $sizeName, $extension );

        if ( ! is_readable( $path ) ) {
            return;
        }
        $sizeData = $this->getSizeData( $sizeName );

        $mimeType = $sizeData[ 'mime-type' ];

        if ( $extension === 'webp' ) {
            $mimeType = 'image/webp';
        }

        if ( $mimeType ) {
            $this->data[ 'sizes' ][ $sizeName ][ 'mime-type' ] = $mimeType;
        }

        $this->data[ 'sizes' ][ $sizeName ][ 'file' ] = basename( $path );

        if ( $sizeName === 'full' ) {
            $this->data[ 'file' ] = trailingslashit( $this->getRelativeDirectory() ) . basename( $this->getOriginalFullPath( $extension ) );
        }
    }


    /**
     * Find size by the given size file.
     *
     * @param array $compareSizeData
     *
     * @return string
     */
    public function findSizeByFile( $compareSizeData )
    {

        if ( ! $compareSizeData ) {
            return 'full';
        }
        $sizes = array_filter( $this->getSizes(), function ( $sizeData ) use ( $compareSizeData ) {
            return $compareSizeData[ 'file' ] === $sizeData[ 'file' ];
        } );

        $sizes = array_keys( $sizes );

        return array_shift( $sizes );

    }

    public function markSizesRegenerated($processed_at)
    {
        $this->setMetaItem( '_processed_at', $processed_at );
        $this->setMetaItem( '_processed_by', WP_SIR_VERSION );
        update_post_meta( $this->id, '_processed_at', $processed_at );
        update_post_meta( $this->id, '_processed_by', WP_SIR_VERSION );
    }

}
