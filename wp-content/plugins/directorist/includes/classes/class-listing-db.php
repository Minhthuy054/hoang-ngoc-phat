<?php
/**
 * Listing DB class
 *
 * This class is for interacting with listing post type
 *
 * @package     ATBDP
 * @subpackage  inlcudes/classes/class-listing-db
 * @copyright   Copyright (c) 2018, AazzTech
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) { die( 'Sorry, it is not your place to have fun..' ); }

if (!class_exists('ATBDP_Listing_DB')):
class ATBDP_Listing_DB {

    public function __construct ()
    {
        add_action( 'before_delete_post', array( $this, 'atbdp_delete_attachment' ) );
    }

    /**
     * @param init $id  Current post id
     * @since 6.4.1
     *
     */
    public function atbdp_delete_attachment($id){

        if( 'at_biz_dir' === get_post_type( $id ) ){
            $listing_img = get_post_meta($id, '_listing_img', true);
            $listing_img = !empty($listing_img) ? $listing_img : array();
            $listing_prv_img = get_post_meta($id, '_listing_prv_img', true);

            if ( is_array( $listing_img ) ) {
                array_unshift($listing_img, $listing_prv_img);
            }

            if ( ! empty( $listing_img ) ) {
                foreach ( $listing_img as $image ) {
                    wp_delete_attachment( $image, true );
                }
            }
        }
    }

    /**
     *It returns all the listing
     * @return bool|WP_Query it returns an object of WP_Query with all listings on success and false on failure
     */
    public function all_listing()
    {
        $listings = new WP_Query(array(
            'post_type' => ATBDP_POST_TYPE,
            'post_per_page'=>-1
        ));
        if ($listings->found_posts) return $listings;
        return false;
    }

    public function get_listing_order_by_featured()
    {
        $args = array(
            'post_type' => ATBDP_POST_TYPE,
            'post_per_page'=>-1
        );
    }

    /**
     * It returns all the listing of the given user or the current user if no user id is passed.
     * @param int $user_id [optional] The id of the user. Default is current user id.
     * @return WP_Query   it returns an object of the WP_Query class with the items/listings on success and false on failure.
     */
    public function get_listing_by_user( $user_id = 0 )
    {
       $pagination = get_directorist_option('user_listings_pagination',1);
       $listingS_per_page = get_directorist_option('user_listings_per_page',9);

        //for pagination
        $paged = atbdp_get_paged_num();
        $args  = array(
            'author'         => ! empty( $user_id ) ? absint( $user_id ) : get_current_user_id(),
            'post_type'      => ATBDP_POST_TYPE,
            'posts_per_page' => (int) $listingS_per_page,
            'order'          => 'DESC',
            'orderby'        => 'date',
            'post_status'    => array( 'publish', 'pending', 'private', 'draft' ),
        );
        if( ! empty( $pagination) ) {
            $args['paged'] = $paged;
        }else{
            $args['no_found_rows'] = false;
        }
        $args = apply_filters('atbdp_user_dashboard_query_arguments', $args);
        return new WP_Query(apply_filters('atbdp_user_dashboard_query_arguments',$args));
    }

    /**
     * It deletes a specific listing along with its meta and reviews by listing id
     * @param int $id The ID of the listing that should be deleted
     * @return bool It returns true on success and false on failure
     */
    public function delete_listing_by_id($id)
    {

        $deleted = wp_delete_post(absint($id), true); // i
        if ( false !== $deleted ) {
            do_action( 'directorist_listing_deleted', $id );
            return true;
        }
        return false;

    }
    //@TODO; methods to add: delete all listing by user

    public function get_favourites( $user_id = 0 ) {
		$user_id = absint( $user_id );
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$favorites  = directorist_get_user_favorites( $user_id );
		$action     = ! empty( $_GET['atbdp_action'] ) ? directorist_clean( wp_unslash( $_GET['atbdp_action'] ) ) : '';
		$listing_id = ! empty( $_GET['atbdp_listing'] ) ? absint( directorist_clean( wp_unslash( $_GET['atbdp_listing'] ) ) ) : 0;

		if ( ! empty( $action ) && ! empty( $listing_id ) ) {
			if ( in_array( $listing_id, $favorites ) ) {
				directorist_delete_user_favorites( $user_id, $listing_id );
			} else {
				directorist_add_user_favorites( $user_id, $listing_id );
			}

			$favorites = directorist_get_user_favorites( $user_id );
		}

        if ( ! empty( $favorites ) ) {
            $args = array(
               // 'author'=> $user_id,
                'post_type'=> ATBDP_POST_TYPE,
                'posts_per_page' => -1, //@todo; Add pagination in future.
                'order'=> 'DESC',
                'post__in' => $favorites,
                'orderby' => 'date'
            );
        } else {
            $args = array();
        }

        return new WP_Query( $args );
    }

} // ends class ATBDP_Listing_DB

endif;