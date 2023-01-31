<?php
/**
 * Member Create Listing
 *
 */
if ( ! class_exists('Wp_dp_Member_Create_Listing') ) {

    class Wp_dp_Member_Create_Listing {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_ajax_wp_dp_member_create_listing', array( $this, 'wp_dp_member_create_listing_callback' ));
        }

        public function wp_dp_member_create_listing_callback() {
            echo do_shortcode('[wp_dp_register_user_and_add_listing]');
            wp_die();
        }

    }

    global $create_listing;
    $create_listing = new Wp_dp_Member_Create_Listing();
}
