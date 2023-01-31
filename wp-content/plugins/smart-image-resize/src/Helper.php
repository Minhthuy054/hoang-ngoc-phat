<?php

namespace WP_Smart_Image_Resize;

class Helper
{
    /**
     * Determine if a given $extension is JPG format.
     *
     * @param string $extension
     *
     * @return bool
     */
    public static function is_jpg( $extension )
    {
        return $extension === 'jpg' || $extension === 'jpeg';
    }

    /**
     * Replace a given file extension with an another given extension.
     *
     * @param string $file File name/base path.
     * @param string|null $extension
     *
     * @return string
     */
    public static function replace_extension( $file, $extension = null )
    {
        $file              = strval( $file );
        $strippedExtension = substr( $file, 0, strrpos( $file, '.' ) );

        if ( empty( trim( strval( $extension ) ) ) ) {
            return $strippedExtension;
        }

        return $strippedExtension . '.' . $extension;
    }

    /**
     * Convert array to comman separated mysql placeholder.
     * Exemple: {'foo', 'bar'} -> %s,%s.
     */
    public static function array_sql_placeholder( $values, $type = '%s' )
    {
        return implode( ',', array_fill( 0, count( $values ), $type ) );
    }

    public static function recursive_parse_args( $args, $defaults )
    {
        $result = $defaults;

        foreach ( (array)$args as $key => $value ) {
            if ( is_array( $value ) && ! isset( $defaults[ $key ] ) ) {
                $result[ $key ] = self::recursive_parse_args( $value, $result[ $key ] );
            } else {
                $result[ $key ] = $args[ $key ];
            }
        }

        return $result;
    }

    public static function is_referer( $needles, $referer = false )
    {
        if ( ! $referer ) {
            $referer = wp_get_referer();
        }

        if ( ! $referer || gettype( $referer ) !== 'string' ) {
            return false;
        }
        foreach ( (array)$needles as $needle ) {
            if ( strpos( $referer, $needle ) !== false ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the given image metadata is valid.
     *
     * @param WP_Error|array|mixed
     *
     * @return bool
     */
    public static function is_image_metadata_valid( $data )
    {
        return ! is_wp_error( $data ) && is_array( $data ) && ! empty( $data ) && ! empty( $data[ 'sizes' ] );
    }


    public static function get_class_short_name( $class )
    {
        $path = explode( '\\', $class );

        return array_pop( $path );
    }


    public static function get_image_relative_path($file){
     $dirname = dirname( $file );

    if ( '.' === $dirname ) {
        return '';
    }

    if ( false !== strpos( $dirname, 'wp-content/uploads' ) ) {
        // Get the directory name relative to the upload directory (back compat for pre-2.7 uploads).
        $dirname = substr( $dirname, strpos( $dirname, 'wp-content/uploads' ) + 18 );
        $dirname = ltrim( $dirname, '/' );
    }
        
     return $dirname;
    }


    


}

