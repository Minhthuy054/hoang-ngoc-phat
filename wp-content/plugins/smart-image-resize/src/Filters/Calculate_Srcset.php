<?php

namespace WP_Smart_Image_Resize\Filters;

use Exception;
use WP_Smart_Image_Resize\Utilities\Request;

class Calculate_Srcset extends Base_Filter {
	public function listen() {
		add_filter( 'wp_calculate_image_srcset', [ $this, 'remove_full_size' ], 99, 5 );
		add_filter( 'wp_get_attachment_metadata', [ $this, 'removeOrphanSizesMeta' ], PHP_INT_MAX );
		add_filter( 'wp_update_attachment_metadata', [ $this, 'remove_old_sizes' ], PHP_INT_MAX );
		add_filter( 'wp_calculate_image_srcset', [ $this, 'addCacheBustingToSrcset' ], PHP_INT_MAX, 3 );
		add_filter( 'wp_get_attachment_image_src', [ $this, 'addCacheBustingToSrc' ], PHP_INT_MAX, 4 );
	}

	function addCacheBustingToSrc( $img, $id, $size, $icon ) {
		if ( ! apply_filters( 'wp_sir_bust_cache', false ) ) {
			return $img;
		}

		if ( empty( $img ) || $icon ) {
			return $img;
		}

		$processed_at = get_post_meta( $id, '_processed_at', true );

		if ( $processed_at ) {
			$img[ 0 ] .= '?v=' . $processed_at;
		}

		return $img;
	}

	function addCacheBustingToSrcset( $sources, $size, $src ) {
		if ( ! apply_filters( 'wp_sir_bust_cache', false ) ) {
			return $sources;
		}

		if ( empty( $sources ) || ! is_array( $sources ) ) {
			return $sources;
		}
		wp_parse_str( parse_url( $src, PHP_URL_QUERY ), $params );

		$timestamp = false;

		if ( is_array( $params ) && isset( $params[ 'v' ] ) ) {
			$timestamp = $params[ 'v' ];
		}

		if ( ! $timestamp ) {
			return $sources;
		}

		foreach ( $sources as &$source ) {
			$source[ 'url' ] .= '?v=' . $timestamp;
		}

		return $sources;
	}

	function removeOrphanSizesMeta( $meta ) {

		try {
			if ( is_wp_error( $meta ) || empty( $meta ) || empty( $meta[ 'sizes' ] ) ) {
				return $meta;
			}

			if ( empty( $meta[ '_processed_at' ] ) ) {
				return $meta;
			}

			if ( empty( $meta[ '_processed_by' ] ) || version_compare( $meta[ '_processed_by' ], '1.7.6', '<' ) ) {
				return $meta;
			}

			$meta[ 'sizes' ] = array_filter( $meta[ 'sizes' ], function ( $size ) {
				return ! empty( $size[ '_processed_at' ] );
			} );

		} catch ( \Exception $e ) {
		}

		return $meta;
	}

	function remove_old_sizes( $meta ) {

		try {
			if ( ! is_array( $meta ) || empty( $meta ) || empty( $meta[ 'sizes' ] ) ) {
				return $meta;
			}

			if ( empty( $meta[ '_processed_at' ] ) ) {
				return $meta;
			}

			$settings = wp_sir_get_settings();
			$sizes    = $settings[ 'sizes' ];

			foreach ( $meta[ 'sizes' ] as $size => $data ) {
				if ( ! in_array( $size, $sizes ) || empty( $data[ '_processed_at' ] ) ) {
					unset( $meta[ 'sizes' ][ $size ] );
				}
			}
		} catch ( \Exception $e ) {
		}


		return $meta;
	}

	/**
	 * We need to remove original image from $sources array since it is not resized.
	 *
	 * @param $sources
	 * @param $sizes
	 * @param $image_src
	 * @param $image_meta
	 * @param $attachment_id
	 *
	 * @return void
	 */
	public function remove_full_size( $sources, $sizes, $image_src, $image_meta, $attachment_id ) {
		if ( ! apply_filters( 'wp_sir_remove_full_size_from_srcset', true ) ) {

			return $sources;
		}

		try {

			// We make sure it's a processable image so we don't touch to other images.
			if ( ! isset( $image_meta[ '_processed_at' ] ) ) {
				return $sources;
			}

			if ( ! Request::is_front_end() ) {
				return $sources;
			}

			if ( ! isset( $image_meta[ 'file' ] ) || ! isset( $sources[ $image_meta[ 'width' ] ] ) ) {
				return $sources;
			}

			$thumbnailUrl = parse_url( $sources[ $image_meta[ 'width' ] ][ 'url' ], PHP_URL_PATH );

			if ( ! $thumbnailUrl ) {
				return $sources;
			}

			$thumbnailFileName = basename( $thumbnailUrl );

			// There is a low risk that thumbnail is also the original image when their sizes are the same
			// WP change thumbnail filename with the original one in the meta.
			if ( $thumbnailFileName !== basename( $image_meta[ 'file' ] ) ) {
				return $sources;
			}

			unset( $sources[ $image_meta[ 'width' ] ] );
		} catch ( Exception $e ) {
		}

		return $sources;
	}
}
