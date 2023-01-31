<?php

/**
 * Translate Plugin Options
 */
if ( ! class_exists('wp_dp_translate_options') ) {

    class wp_dp_translate_options {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_filter('wp_dp_translate_options_admin', array( $this, 'wp_dp_translate_options_admin_callback' ), 0, 1);
            add_filter('wp_dp_translate_options', array( $this, 'wp_dp_translate_options_callback' ), 0, 1);
        }

        public function wp_dp_translate_options_admin_callback($wp_dp_plugin_options = array()) {
            if ( function_exists('icl_register_string') ) {
                $lang_code = ICL_LANGUAGE_CODE;

                $review_flag_opts = isset($wp_dp_plugin_options['review_flag_opts']) ? $wp_dp_plugin_options['review_flag_opts'] : '';
                if ( isset($review_flag_opts) && ! empty($review_flag_opts) ) {
                    $review_flag_opts_trans = array();
                    foreach ( $review_flag_opts as $review_flag_opt ) {
                        do_action('wpml_register_single_string', 'WP DP Settings', 'Listing Reviews Flag - ' . $review_flag_opt, $review_flag_opt);
                    }
                }

                $term_policy_description = isset($wp_dp_plugin_options['wp_dp_term_policy_description']) ? $wp_dp_plugin_options['wp_dp_term_policy_description'] : '';
                do_action('wpml_register_single_string', 'WP DP Settings', 'Terms & Condition - Description', $term_policy_description);

                $member_title_options = isset($wp_dp_plugin_options['member_title']) ? $wp_dp_plugin_options['member_title'] : '';
                if ( isset($member_title_options) && ! empty($member_title_options) ) {
                    $member_title_options_trans = array();
                    foreach ( $member_title_options as $member_title_option ) {
                        do_action('wpml_register_single_string', 'WP DP Settings', 'Member Type - ' . $member_title_option, $member_title_option);
                    }
                }

                $wp_dp_yelp_places_cats = isset($wp_dp_plugin_options['wp_dp_yelp_places_cats']) ? $wp_dp_plugin_options['wp_dp_yelp_places_cats'] : '';
                if ( isset($member_title_options) && ! empty($member_title_options) ) {
                    $wp_dp_yelp_places_cats_trans = array();
                    if ( ! empty($wp_dp_yelp_places_cats) ) {
                        foreach ( $wp_dp_yelp_places_cats as $wp_dp_yelp_places_cat ) {
                            do_action('wpml_register_single_string', 'WP DP Settings', 'Yelp Places - ' . $wp_dp_yelp_places_cat, $wp_dp_yelp_places_cat);
                        }
                    }
                }

                $dashboard_announce_title = isset($wp_dp_plugin_options['wp_dp_dashboard_announce_title']) ? $wp_dp_plugin_options['wp_dp_dashboard_announce_title'] : '';
                do_action('wpml_register_single_string', 'WP DP Settings', 'Announcement Heading', $dashboard_announce_title);


                $dashboard_announce_desc = isset($wp_dp_plugin_options['wp_dp_dashboard_announce_description']) ? $wp_dp_plugin_options['wp_dp_dashboard_announce_description'] : '';
                do_action('wpml_register_single_string', 'WP DP Settings', 'Announcement Description', $dashboard_announce_desc);


                $wp_dp_mortgage_static_text_block = isset($wp_dp_plugin_options['wp_dp_mortgage_static_text_block']) ? $wp_dp_plugin_options['wp_dp_mortgage_static_text_block'] : '';
                do_action('wpml_register_single_string', 'WP DP Settings', 'Mortgage Calculator - Description', $wp_dp_mortgage_static_text_block);


                $wp_dp_listing_static_envior_text = isset($wp_dp_plugin_options['wp_dp_listing_static_envior_text']) ? $wp_dp_plugin_options['wp_dp_listing_static_envior_text'] : '';
                do_action('wpml_register_single_string', 'WP DP Settings', 'Instructions - Title', $wp_dp_listing_static_envior_text);


                $wp_dp_listing_static_text_block = isset($wp_dp_plugin_options['wp_dp_listing_static_text_block']) ? $wp_dp_plugin_options['wp_dp_listing_static_text_block'] : '';
                do_action('wpml_register_single_string', 'WP DP Settings', 'Instructions - Description', $wp_dp_listing_static_text_block);


                $wp_dp_map_markers_data = isset($wp_dp_plugin_options['wp_dp_map_markers_data']) ? $wp_dp_plugin_options['wp_dp_map_markers_data'] : array();
                if ( isset($wp_dp_map_markers_data['label']) ) {
                    foreach ( $wp_dp_map_markers_data['label'] as $key => $row ) {
                        $title = isset($wp_dp_map_markers_data['label'][$key]) ? $wp_dp_map_markers_data['label'][$key] : '';
                        do_action('wpml_register_single_string', 'WP DP Settings', 'Nearby Places - ' . $title, $title);
                    }
                }
            }
        }

        public function wp_dp_translate_options_callback($wp_dp_plugin_options = array()) {
            global $wp_dp_plugin_options;

            $wp_dp_plugin_options_translate = get_option('wp_dp_plugin_options'); 

            if ( function_exists('icl_register_string') ) {
                $lang_code = ICL_LANGUAGE_CODE;
                if ( isset($_GET['wpml_lang']) && $_GET['wpml_lang'] != '' ) {
                    $lang_code = $_GET['wpml_lang'];
                }

                $review_flag_opts = isset($wp_dp_plugin_options_translate['review_flag_opts']) ? $wp_dp_plugin_options_translate['review_flag_opts'] : '';
                if ( isset($review_flag_opts) && ! empty($review_flag_opts) ) {
                    $review_flag_opts_trans = array();
                    foreach ( $review_flag_opts as $review_flag_opt ) {
                        do_action('wpml_register_single_string', 'WP DP Settings', 'Listing Reviews Flag - ' . $review_flag_opt, $review_flag_opt);
                        $review_flag_opts_trans[] = apply_filters('wpml_translate_single_string', $review_flag_opt, 'WP DP Settings', 'Listing Reviews Flag - ' . $review_flag_opt, $lang_code);
                    }
                    $wp_dp_plugin_options['review_flag_opts'] = $review_flag_opts_trans;
                } 

                $term_policy_description = isset($wp_dp_plugin_options_translate['wp_dp_term_policy_description']) ? $wp_dp_plugin_options_translate['wp_dp_term_policy_description'] : '';
                do_action('wpml_register_single_string', 'WP DP Settings', 'Terms & Condition - Description', $term_policy_description);
                $wp_dp_plugin_options['wp_dp_term_policy_description'] = apply_filters('wpml_translate_single_string', $term_policy_description, 'WP DP Settings', 'Terms & Condition - Description', $lang_code);


                $member_title_options = isset($wp_dp_plugin_options_translate['member_title']) ? $wp_dp_plugin_options_translate['member_title'] : '';
                if ( isset($member_title_options) && ! empty($member_title_options) ) {
                    $member_title_options_trans = array();
                    foreach ( $member_title_options as $member_title_option ) {
                        do_action('wpml_register_single_string', 'WP DP Settings', 'Member Type - ' . $member_title_option, $member_title_option);
                        $member_title_options_trans[] = apply_filters('wpml_translate_single_string', $member_title_option, 'WP DP Settings', 'Member Type - ' . $member_title_option, $lang_code);
                    }
                    $wp_dp_plugin_options['member_title'] = $member_title_options_trans;
                }

                $wp_dp_yelp_places_cats = isset($wp_dp_plugin_options_translate['wp_dp_yelp_places_cats']) ? $wp_dp_plugin_options_translate['wp_dp_yelp_places_cats'] : '';
                if ( isset($wp_dp_yelp_places_cats) && ! empty($wp_dp_yelp_places_cats) ) {
                    $wp_dp_yelp_places_cats_trans = array();
                    foreach ( $wp_dp_yelp_places_cats as $wp_dp_yelp_places_cat ) {
                        do_action('wpml_register_single_string', 'WP DP Settings', 'Yelp Places - ' . $wp_dp_yelp_places_cat, $wp_dp_yelp_places_cat);
                        $wp_dp_yelp_places_cats_trans[] = apply_filters('wpml_translate_single_string', $wp_dp_yelp_places_cat, 'WP DP Settings', 'Yelp Places - ' . $wp_dp_yelp_places_cat, $lang_code);
                    }
                    $wp_dp_plugin_options['wp_dp_yelp_places_cats'] = $wp_dp_yelp_places_cats_trans;
                }

                $dashboard_announce_title = isset($wp_dp_plugin_options_translate['wp_dp_dashboard_announce_title']) ? $wp_dp_plugin_options_translate['wp_dp_dashboard_announce_title'] : '';
                do_action('wpml_register_single_string', 'WP DP Settings', 'Announcement Heading', $dashboard_announce_title);
                $wp_dp_plugin_options['wp_dp_dashboard_announce_title'] = apply_filters('wpml_translate_single_string', $dashboard_announce_title, 'WP DP Settings', 'Announcement Heading', $lang_code);

                $dashboard_announce_desc = isset($wp_dp_plugin_options_translate['wp_dp_dashboard_announce_description']) ? $wp_dp_plugin_options_translate['wp_dp_dashboard_announce_description'] : '';
                do_action('wpml_register_single_string', 'WP DP Settings', 'Announcement Description', $dashboard_announce_desc);
                $wp_dp_plugin_options['wp_dp_dashboard_announce_description'] = apply_filters('wpml_translate_single_string', $dashboard_announce_desc, 'WP DP Settings', 'Announcement Description', $lang_code);

                $wp_dp_mortgage_static_text_block = isset($wp_dp_plugin_options_translate['wp_dp_mortgage_static_text_block']) ? $wp_dp_plugin_options_translate['wp_dp_mortgage_static_text_block'] : '';
                do_action('wpml_register_single_string', 'WP DP Settings', 'Mortgage Calculator - Description', $wp_dp_mortgage_static_text_block);
                $wp_dp_plugin_options['wp_dp_mortgage_static_text_block'] = apply_filters('wpml_translate_single_string', $wp_dp_mortgage_static_text_block, 'WP DP Settings', 'Mortgage Calculator - Description', $lang_code);


                $wp_dp_listing_static_envior_text = isset($wp_dp_plugin_options_translate['wp_dp_listing_static_envior_text']) ? $wp_dp_plugin_options_translate['wp_dp_listing_static_envior_text'] : '';
                do_action('wpml_register_single_string', 'WP DP Settings', 'Instructions - Title', $wp_dp_listing_static_envior_text);
                $wp_dp_plugin_options['wp_dp_listing_static_envior_text'] = apply_filters('wpml_translate_single_string', $wp_dp_listing_static_envior_text, 'WP DP Settings', 'Instructions - Title', $lang_code);

                $wp_dp_listing_static_text_block = isset($wp_dp_plugin_options_translate['wp_dp_listing_static_text_block']) ? $wp_dp_plugin_options_translate['wp_dp_listing_static_text_block'] : '';
                do_action('wpml_register_single_string', 'WP DP Settings', 'Instructions - Description', $wp_dp_listing_static_text_block);
                $wp_dp_plugin_options['wp_dp_listing_static_text_block'] = apply_filters('wpml_translate_single_string', $wp_dp_listing_static_text_block, 'WP DP Settings', 'Instructions - Description', $lang_code);

                $wp_dp_map_markers_data = isset($wp_dp_plugin_options_translate['wp_dp_map_markers_data']) ? $wp_dp_plugin_options_translate['wp_dp_map_markers_data'] : array();
                if ( isset($wp_dp_map_markers_data['label']) && ! empty(isset($wp_dp_map_markers_data['label'])) ) {
                    foreach ( $wp_dp_map_markers_data['label'] as $key => $row ) {
                        $title = isset($wp_dp_map_markers_data['label'][$key]) ? $wp_dp_map_markers_data['label'][$key] : '';
                        do_action('wpml_register_single_string', 'WP DP Settings', 'Nearby Places - ' . $title, $title);
                        $wp_dp_map_markers_data['label'][$key] = apply_filters('wpml_translate_single_string', $title, 'WP DP Settings', 'Nearby Places - ' . $title, $lang_code);
                    }
                    $wp_dp_plugin_options['wp_dp_map_markers_data'] = $wp_dp_map_markers_data;
                }
            }
            return $wp_dp_plugin_options;
        }

    }

    global $wp_dp_translate_options;
    $wp_dp_translate_options = new wp_dp_translate_options();
}