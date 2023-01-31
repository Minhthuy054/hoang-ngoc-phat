<?php

/**
 * File Type: Notifications Post Type
 */
if ( ! class_exists('Wp_dp_Post_Type_Notifications') ) {

    class Wp_dp_Post_Type_Notifications {

        /**
         * Start Contructer Function
         */
        public function __construct() {
            add_action('init', array( &$this, 'wp_dp_activity_notifications_register' ));
            add_action('post_row_actions', array( $this, 'post_row_actions_callback' ), 10, 2);
            add_action('views_edit-notifications', array( $this, 'wp_dp_remove_views' ));
            add_filter('bulk_actions-edit-notifications', array( $this, 'bulk_actions_callback' )); // Custom columns
            add_filter('manage_notifications_posts_columns', array( $this, 'custom_columns_callback' ), 10, 1);
            add_action('manage_notifications_posts_custom_column', array( $this, 'manage_posts_custom_column_callback' ), 10, 1);
            add_filter('manage_edit-notifications_sortable_columns', array( $this, 'wp_dp_sortable_colummns' ));
            add_filter('handle_bulk_actions-edit-notifications', array( $this, 'my_bulk_action_handler' ), 10, 3);

            // Remove "Add new" button from listing page and admin menu.
            add_action('admin_head', array( $this, 'disable_new_posts_capability_callback' ), 11);
			
            //add custom filters
            add_action('restrict_manage_posts', array($this, 'wp_dp_notifications_filters'));
            add_filter('parse_query', array($this, 'wp_dp_notification_filters_query'));
            
            //remove quick edit
            add_filter('post_row_actions',array( $this, 'remove_quick_edit'),10,1);
            
        }
        
        function remove_quick_edit( $actions ){
            global $post;
            if($post->post_type == 'notifications')
                unset($actions['inline hide-if-no-js']);
            return $actions;
        }
		
        public function wp_dp_sortable_colummns($columns){

                $columns['notification_received_date'] = 'notif_date';
                return $columns;
        }

        public function wp_dp_notifications_filters(){
                global $wp_dp_form_fields, $wp_dp_plugin_options, $post_type;

                if(isset($post_type) && $post_type == 'notifications'){
                        $notification_owner	=	'';
                        $listing_owner	=	'';
                        if(isset($_GET['notification_owner']) && !empty($_GET['notification_owner'])){
                                $notification_owner	=	$_GET['notification_owner'];
                        }
                        if(isset($_GET['listing_owner']) && !empty($_GET['listing_owner'])){
                                $listing_owner	=	$_GET['listing_owner'];
                        }

                        $wp_dp_opt_array = array(
            'id' => 'notification_owner',
            'cust_name' => 'notification_owner',
            'std' => $notification_owner,
            'classes' => 'filter-notification-owner',
            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_dashboard_search_by_notif_owner') . '"',
            'return' => false,
            'force_std' => true,
        );
                        $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);

                        $wp_dp_opt_array = array(
            'id' => 'listing_owner',
            'cust_name' => 'listing_owner',
            'std' => $listing_owner,
            'classes' => 'filter-listing-owner',
            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_dashboard_search_by_list_owner') . '"',
            'return' => false,
            'force_std' => true,
        );
                        $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                }
        }

        public function wp_dp_notification_filters_query($query){
                global $pagenow;
                $custom_filters_arr	=	array();
                if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'notifications' && isset($_GET['notification_owner']) && $_GET['notification_owner'] != '' ) {
                        $custom_filters_arr[]	=	array(
                                'key'	=>	'notif_owner_name',
                                'value'	=>	$_GET['notification_owner'],
                        );
                }
                if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'notifications' && isset($_GET['listing_owner']) && $_GET['listing_owner'] != '' ) {
                        $custom_filters_arr[]	=	array(
                                'key'	=>	'reciever_name',
                                'value'	=>	$_GET['listing_owner'],
                        );
                }
                if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'notifications' && isset($_GET['m']) && $_GET['m'] != '' && $_GET['m'] != 0) {
                    $date_query = [];
                    $date_query[] = array(
                        'year'  => substr($_GET['m'],0,4),
                        'month' => substr($_GET['m'],4,5),
                    );
                    $query->set('date_query', $date_query);
                }
                if(!empty($custom_filters_arr)){
                        $query->set('meta_query', $custom_filters_arr);
                }
        }

        public function wp_dp_remove_views($views) {
            unset($views['publish']);
            unset($views['mine']);
            
            return $views;
        }

        /**
         * Disable capibility to create new.
         */
        public function disable_new_posts_capability_callback() {
            global $post;

            // Hide link on listing page.
            if ( get_post_type() == 'notifications' ) {
                ?>
                <style type="text/css">
                    .wrap .page-title-action, 
                    #edit-slug-box, 
                    .submitbox .preview.button,
                    .submitbox .misc-pub-visibility,
                    .submitbox .edit-timestamp,
                    .metabox-prefs:first-child{
                        display:none;
                    }
                    .post-type-wp_dp_reviews .column-review_id { width:100px !important; overflow:hidden }
                    .post-type-wp_dp_reviews .column-helpful { width:100px !important; overflow:hidden }
                    .post-type-wp_dp_reviews .column-flag { width:100px !important; overflow:hidden }
                </style>
                <?php

            }
        }

        /**
         * Add new columns to notifications backend listing.
         *
         * @param	array	$columns
         * @return	array
         */
        public function custom_columns_callback($columns) {
            if ( ! is_array($columns) ) {
                $columns = array();
            }
            unset($columns['date']);
            unset($columns['title']);
            
            $new_columns = array();
            foreach ( $columns as $key => $value ) {
                $new_columns[$key] = $value;
            }
            
            $new_columns['notification_title'] = wp_dp_plugin_text_srt('wp_dp_dashboard_notif_title');
            $new_columns['notification_member'] = wp_dp_plugin_text_srt('wp_dp_activity_notification_member');
            $new_columns['notification_message'] = wp_dp_plugin_text_srt('wp_dp_activity_notification_message');
            $new_columns['notification_listing_name'] = wp_dp_plugin_text_srt('wp_dp_activity_notification_listing_name');
            $new_columns['notification_received_date'] = wp_dp_plugin_text_srt('wp_dp_activity_notification_received_date');
                
            return $new_columns;
        }

        /**
         * Output data for custom columns.
         *
         * @param	string	$column_name
         */
        public function manage_posts_custom_column_callback($column_name) {
            global $post;
            switch ( $column_name ) {
                case 'notification_title':
                    the_title();
                    break;
                case 'notification_message':
                    echo get_post_meta($post->ID, 'message', true);
                    break;

                case 'notification_member':
                    $member_id = get_post_meta($post->ID, 'reciever_id', true);
                    echo '<a href="' . get_edit_post_link($member_id) . '">' . get_the_title($member_id) . '</a>';
                    break;

                case 'notification_listing_name';
                    $listing_id = get_post_meta($post->ID, 'element_id', true);
                    echo '<a href="' . get_edit_post_link($listing_id) . '">' . get_the_title($listing_id) . '</a>';
                    break;
                case 'notification_received_date':
                    //echo human_time_diff(get_post_time('U', true, $post->ID), current_time('timestamp')) . ' ' . wp_dp_plugin_text_srt('wp_dp_activity_notification_recv_date_ago');
                    echo get_the_date('F j, Y', $post->ID);
                    break;
            }
        }

        /**
         * Remove Trash option from bulk dropdown
         */
        public function bulk_actions_callback($actions = array()) {
            if ( ! is_array($actions) ) {
                $actions = array();
            }
            unset($actions['trash']);
            unset($actions['edit']);
            $actions['delete'] = wp_dp_plugin_text_srt('wp_dp_listing_delete');
            return $actions;
        }
        
//         public function my_bulk_action_handler($redirect_to, $action_name, $post_ids) {
//            if ( 'delete' === $action_name ) {
//                foreach ( $post_ids as $post_id ) {
//                    $post = get_post($post_id);
//                    get_delete_post_link($post, '', true);
//                }
//                $redirect_to = add_query_arg('bulk_posts_processed', count($post_ids), $redirect_to);
//                return $redirect_to;
//            } else {
//                return $redirect_to;
//            }
//        }

        /**
         * Delete Notifications Permanently
         */
        public function post_row_actions_callback($actions = array(), $post = array()) {

            if ( ! is_array($actions) ) {
                $actions = array();
            }
            if ( $post->post_type == "notifications" ) {
                unset($actions['trash']);
                unset($actions['view']);
                unset($actions['edit']);
                $post_type_object = get_post_type_object($post->post_type);
                $actions['trash'] = "<a class='submitdelete' title='" . wp_dp_plugin_text_srt('wp_dp_notifications_delete_permanently') . "' href='" . get_delete_post_link($post->ID, '', true) . "'>" . wp_dp_plugin_text_srt('wp_dp_notifications_row_delete') . "</a>";
            }
            return $actions;
        }

        /**
         * Start Function How to Register post type
         */
        public function wp_dp_activity_notifications_register() {

            $labels = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_post_type_notification_name'),
                'menu_name' => wp_dp_plugin_text_srt('wp_dp_post_type_notification_name'),
                'singular_name' => wp_dp_plugin_text_srt('wp_dp_post_type_notification_singular_name'),
                'not_found' => wp_dp_plugin_text_srt('wp_dp_post_type_notification_not_found'),
                'not_found_in_trash' => wp_dp_plugin_text_srt('wp_dp_post_type_notification_not_found_in_trash'),
            );

            $args = array(
                'labels' => $labels,
                'description' => wp_dp_plugin_text_srt('wp_dp_activity_notifications'),
                'public' => false,
                'publicly_queryable' => false,
                'show_ui' => true, // Hidden post type it is not being displayed in admin.
                'show_in_menu' => 'edit.php?post_type=listings',
                'query_var' => false,
                'menu_icon' => wp_dp::plugin_url() . 'assets/backend/images/icon-notifications-active.png',
                'rewrite' => array( 'slug' => 'notifications' ),
                'capability_type' => 'post',
                'has_archive' => false,
                'capabilities' => array(
                    'create_posts' => 'create_posts',
                    'publish_posts' => 'publish_posts',
                    'edit_posts' => 'edit_posts',
                    'edit_others_posts' => 'edit_others_posts',
                    'delete_posts' => 'delete_posts',
                    'delete_others_posts' => 'delete_others_posts',
                    'read_private_posts' => 'read_private_posts',
                    'edit_post' => 'edit_posts',
                    'delete_post' => 'delete_posts',
                    'read_post' => 'read_posts',
                ),
                'map_meta_cap' => false,
                'hierarchical' => false,
                'menu_position' => 29,
                'exclude_from_search' => true,
            );

            register_post_type('notifications', $args);
        }

        // End of class	
    }

    // Initialize Object
    $wp_dp_activity_notifications_object = new Wp_dp_Post_Type_Notifications();
}