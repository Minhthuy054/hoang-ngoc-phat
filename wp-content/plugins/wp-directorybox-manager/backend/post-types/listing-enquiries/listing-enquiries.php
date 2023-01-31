<?php

/**
 * File Type: Listing Enquiries Post Type
 */
if ( ! class_exists('post_type_listing_enquiries') ) {

    class post_type_listing_enquiries {

        /**
         * Start Contructer Function
         */
        public function __construct() {
            add_action('init', array( &$this, 'wp_dp_listing_enquiries_register' ), 12);
            add_filter('manage_listing_enquiries_posts_columns', array( &$this, 'listing_enquiries_columns_add' ), 10, 1);
            add_action('manage_listing_enquiries_posts_custom_column', array( &$this, 'listing_enquiries_columns' ), 10, 2);
            add_filter('post_row_actions', array( &$this, 'listing_enquiries_remove_row_actions' ), 11, 2);
            add_action('admin_menu', array( $this, 'wp_dp_remove_post_boxes' ));
            add_action('do_meta_boxes', array( $this, 'wp_dp_remove_post_boxes' ));
            // Custom Sort Columns
            add_filter('manage_edit-listing_enquiries_sortable_columns', array( $this, 'wp_dp_enquiries_sortable' ));
            add_filter('request', array( $this, 'wp_dp_enquiries_column_orderby' ));
            // Custom Filter
            add_action('restrict_manage_posts', array( $this, 'wp_dp_admin_enquiries_filters' ), 11);
            add_filter('parse_query', array( &$this, 'wp_dp_enquiries_filter' ), 11, 1);
            // Status change action
            add_filter('wp_ajax_wp_dp_enquiry_status_change_bk', array( &$this, 'enquiry_status_change_bk' ));
            // Bulk action hook
            add_filter('bulk_actions-edit-listing_enquiries', array( &$this, 'custom_bulk_actions' ));
            // Remove Default search box
            add_filter('admin_head', array( &$this, 'hide_default_search_box' ));
            // Alter filter posts list
            add_filter('views_edit-listing_enquiries', array( &$this, 'alter_filter_posts_list' ));
            add_filter("pre_get_posts", array( $this, 'pre_get_posts_callback' ));
            
            //remove quick edit
            add_filter('post_row_actions',array( $this, 'remove_quick_edit'),10,1);
        }
        
        function remove_quick_edit( $actions ){
            global $post;
            if($post->post_type == 'listing_enquiries')
                unset($actions['inline hide-if-no-js']);
            return $actions;
        }

        public function pre_get_posts_callback($query) {
           
            $post_type = isset($query->query['post_type']) ? $query->query['post_type'] : '';
            if ( isset($post_type) && $post_type == 'listing_enquiries' ) {
                $member_id = isset( $_GET['member_id'] )? $_GET['member_id'] : '';
                $listing_member_id = isset( $_GET['listing_member_id'] )? $_GET['listing_member_id'] : '';
                if( $member_id != '' ){
                    $meta_query = array(
                        array(
                            'key' => 'wp_dp_enquiry_member',
                            'value' => $member_id,
                            'compare' => '=',
                        ),
                    );
                    $query->set('meta_query', $meta_query);
                }
                
                if( $listing_member_id != '' ){
                    $meta_query = array(
                        array(
                            'key' => 'wp_dp_listing_member',
                            'value' => $listing_member_id,
                            'compare' => '=',
                        ),
                    );
                    $query->set('meta_query', $meta_query);
                }
            }
            return $query;
        }
        

        public function listing_enquiries_remove_row_actions($actions, $post) {
            if ( isset($post->post_type) && $post->post_type == 'listing_enquiries' ) {
                unset($actions['view']);
                //unset($actions['edit']);
            }
            return $actions;
        }

        public function alter_filter_posts_list($views) {
            unset($views['publish']);
            return $views;
        }

        public function custom_bulk_actions($actions) {
            unset($actions['edit']);
            return $actions;
        }

        public function listing_enquiries_columns_add($columns) {
            unset($columns['title']);
            unset($columns['date']);
            unset($columns['validated_is_valid']);
            unset($columns['validated_check']);
            $columns['p_title'] = wp_dp_plugin_text_srt('wp_dp_enquiry_id');
            $columns['p_date'] = wp_dp_plugin_text_srt('wp_dp_enquiry_date');
            $columns['listing_member'] = wp_dp_plugin_text_srt('wp_dp_enquiry_listing_member');
            $columns['listing'] = wp_dp_plugin_text_srt('wp_dp_enquiry_listing_name');
            $columns['enquiry_member'] = wp_dp_plugin_text_srt('wp_dp_enquiry_enquiry_member');
            //$columns['status'] = wp_dp_plugin_text_srt('wp_dp_enquiry_status');
            return $columns;
        }

        public function listing_enquiries_columns($name) {
            global $post, $orders_inquiries, $wp_dp_plugin_options, $wp_dp_form_fields;

            $listing_member = get_post_meta($post->ID, 'wp_dp_listing_member', true);
            $enquiry_member = get_post_meta($post->ID, 'wp_dp_enquiry_member', true);
            $listing_id = get_post_meta($post->ID, 'wp_dp_listing_id', true);

            switch ( $name ) {
                case 'p_title':
                    echo '#' . $post->ID;
                    break;
                case 'p_date':
                    echo get_the_date();
                    break;
                case 'listing_member':
                    echo '<a href="' . admin_url('post.php?post=' . $listing_member . '&action=edit') . '">' . get_the_title($listing_member) . '</a>';
                    break;
                case 'listing':
                    echo '<a href="' . admin_url('post.php?post=' . $listing_id . '&action=edit') . '">' . get_the_title($listing_id) . '</a>';
                    break;
                case 'enquiry_member':
                    echo '<a href="' . admin_url('post.php?post=' . $enquiry_member . '&action=edit') . '">' . get_the_title($enquiry_member) . '</a>';
                    break;
            }
        }

        public function wp_dp_enquiries_sortable($columns) {
            $columns['p_title'] = 'post_title';
            $columns['p_date'] = 'date';
            return $columns;
        }

        public function wp_dp_enquiries_column_orderby($vars) {
            if ( isset($vars['orderby']) && 'p_title' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'orderby' => 'post_title',
                ));
            }
            if ( isset($vars['orderby']) && 'p_date' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'orderby' => 'date',
                ));
            }
            return $vars;
        }

        public function wp_dp_admin_enquiries_filters() {
            global $wp_dp_form_fields, $wp_dp_plugin_options, $post_type;

            //only add filter to post type you want
            if ( isset($post_type) && $post_type == 'listing_enquiries' ) {
                $member_name = isset($_GET['member_name']) ? $_GET['member_name'] : '';
                $wp_dp_opt_array = array(
                    'id' => 'member_name',
                    'cust_name' => 'member_name',
                    'std' => $member_name,
                    'classes' => 'filter-member-name',
                    'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_enquiry_post_filter_for_member') . '"',
                    'return' => false,
                    'force_std' => true,
                );
                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);

                $listing_name = isset($_GET['listing_name']) ? $_GET['listing_name'] : '';
                $wp_dp_opt_array = array(
                    'id' => 'listing_name',
                    'cust_name' => 'listing_name',
                    'std' => $listing_name,
                    'classes' => 'filter-listing-name',
                    'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_enquiry_post_filter_for_Listing') . '"',
                    'return' => false,
                    'force_std' => true,
                );
                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
            }
        }

        function wp_dp_enquiries_filter($query) {
            global $pagenow;
            $custom_filter_arr = array();
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'listing_enquiries' && isset($_GET['member_name']) && $_GET['member_name'] != '' ) {
                remove_filter('parse_query', array( &$this, 'wp_dp_enquiries_filter' ), 11, 1);
                $members_args = array(
                    'post_type' => 'members',
                    'posts_per_page' => -1,
                    's' => $_GET['member_name'],
                    'fields' => 'ids',
                );
                $members_ids = get_posts($members_args);
                wp_reset_postdata();
                add_filter('parse_query', array( &$this, 'wp_dp_enquiries_filter' ), 11, 1);
                if ( empty($members_ids) ) {
                    $members_ids = array( 0 );
                }
                $custom_filter_arr[] = array(
                    'key' => 'wp_dp_listing_member',
                    'value' => $members_ids,
                    'compare' => 'IN',
                );
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'listing_enquiries' && isset($_GET['listing_name']) && $_GET['listing_name'] != '' ) {
                remove_filter('parse_query', array( &$this, 'wp_dp_enquiries_filter' ), 11, 1);
                $listings_args = array(
                    'post_type' => 'listings',
                    'posts_per_page' => -1,
                    's' => $_GET['listing_name'],
                    'fields' => 'ids',
                );
                $listings_ids = get_posts($listings_args);
                wp_reset_postdata();
                add_filter('parse_query', array( &$this, 'wp_dp_enquiries_filter' ), 11, 1);
                if ( empty($listings_ids) ) {
                    $listings_ids = array( 0 );
                }
                $custom_filter_arr[] = array(
                    'key' => 'wp_dp_listing_id',
                    'value' => $listings_ids,
                    'compare' => 'IN',
                );
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'listing_enquiries' && isset($_GET['m']) && $_GET['m'] != '' && $_GET['m'] != 0) {
                $date_query = [];
                $date_query[] = array(
                    'year'  => substr($_GET['m'],0,4),
                    'month' => substr($_GET['m'],4,5),
                );
                $query->set('date_query', $date_query);
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'listing_enquiries' && ! empty($custom_filter_arr) ) {
                $query->set('meta_query', $custom_filter_arr);
            }
        }

        /**
         * Enquiry Status change callback
         */
        public function enquiry_status_change_bk() {
            $enquiry_id = isset($_POST['enquiry_id']) ? $_POST['enquiry_id'] : '';
            $status_val = isset($_POST['status_val']) ? $_POST['status_val'] : '';
            if ( $enquiry_id != '' && $status_val != '' ) {
                update_post_meta($enquiry_id, 'wp_dp_enquiry_status', $status_val);
                $msg = wp_dp_plugin_text_srt('wp_dp_enquiry_post_status_change');
            } else {
                $msg = wp_dp_plugin_text_srt('wp_dp_enquiry_post_there_is_error');
            }
            echo json_encode(array( 'msg' => $msg ));
            die;
        }

        function hide_default_search_box() {

            if ( get_post_type() === 'listing_enquiries' ) {
                echo '<style type="text/css">
				#posts-filter > p.search-box {
					display: none;
				}
				</style>';
            }
        }

        /**
         * Start Wp's Initilize action hook Function
         */
        public function wp_dp_listing_enquiries_init() {
            // Initialize Post Type
            $this->wp_dp_listing_enquiries_register();
        }

        /**
         * Start Function How to Register post type
         */
        public function wp_dp_listing_enquiries_register() {
            $labels = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_enquiries_name'),
                'singular_name' => wp_dp_plugin_text_srt('wp_dp_listing_enquiries_singular_name'),
                'menu_name' => wp_dp_plugin_text_srt('wp_dp_listing_enquiries'),
                'name_admin_bar' => wp_dp_plugin_text_srt('wp_dp_listing_enquiries_name_admin_bar'),
                'add_new' => wp_dp_plugin_text_srt('wp_dp_listing_enquiries_add_new'),
                'add_new_item' => wp_dp_plugin_text_srt('wp_dp_listing_enquiries_add_new_item'),
                'new_item' => wp_dp_plugin_text_srt('wp_dp_listing_enquiries_new_item'),
                'edit_item' => wp_dp_plugin_text_srt('wp_dp_listing_enquiries_edit_item'),
                'view_item' => wp_dp_plugin_text_srt('wp_dp_listing_enquiries_view_item'),
                'all_items' => wp_dp_plugin_text_srt('wp_dp_listing_enquiries_all_items'),
                'search_items' => wp_dp_plugin_text_srt('wp_dp_listing_enquiries_search_items'),
                'not_found' => wp_dp_plugin_text_srt('wp_dp_listing_enquiries_not_found'),
                'not_found_in_trash' => wp_dp_plugin_text_srt('wp_dp_listing_enquiries_not_found_in_trash'),
            );

            $args = array(
                'labels' => $labels,
                'description' => wp_dp_plugin_text_srt('wp_dp_listing_enquiries'),
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => 'edit.php?post_type=listings',
                'menu_position' => 29,
                'menu_icon' => wp_dp::plugin_url() . 'assets/backend/images/icon-enquiries.png',
                'query_var' => false,
                'rewrite' => array( 'slug' => 'listing_enquiries' ),
                'capability_type' => 'post',
                'has_archive' => false,
                'hierarchical' => false,
                'exclude_from_search' => true,
                'supports' => array( 'title' ),
                'capabilities' => array(
                    'create_posts' => false,
                ),
                'map_meta_cap' => true,
            );

            register_post_type('listing_enquiries', $args);
        }

        public function wp_dp_remove_post_boxes() {
            remove_meta_box('mymetabox_revslider_0', 'listing_enquiries', 'normal');
        }

        // End of class	
    }

    // Initialize Object
    $listing_enquiries_object = new post_type_listing_enquiries();
}

