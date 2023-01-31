<?php

/**
 * File Type: Listing Post Type
 */
if ( ! class_exists('post_type_listing') ) {

    class post_type_listing {

        /**
         * Start Contructer Function
         */
        public function __construct() {
            add_action('init', array( $this, 'wp_dp_listing_init' ), 12);
            add_filter('manage_listings_posts_columns', array( $this, 'wp_dp_listing_columns_add' ));
            add_action('manage_listings_posts_custom_column', array( $this, 'wp_dp_listing_columns' ), 10, 2);
            add_action('create_daily_listings_check', array( $this, 'create_daily_listings_check_callback' ), 10);

            add_action('admin_menu', array( $this, 'remove_cus_meta_boxes' ));
            add_action('do_meta_boxes', array( $this, 'remove_cus_meta_boxes' ));
            add_filter("get_user_option_screen_layout_listings", array( $this, 'listing_type_screen_layout' ));
            add_action('admin_head', array( $this, 'check_post_type_and_remove_media_buttons' ));

            // AJAX handlers for import/export listing type categories in plugin options.
            add_action('wp_ajax_generate_listing_type_categories_backup', array( $this, 'generate_listing_type_categories_backup_callback' ));
            add_action('wp_ajax_delete_listing_type_categories_backup_file', array( $this, 'delete_listing_type_categories_backup_file_callback' ));
            add_action('wp_ajax_restore_listing_type_categories_backup', array( $this, 'restore_listing_type_categories_backup_callback' ));
            add_action('wp_ajax_wp_dp_uploading_import_cat_file', array( $this, 'wp_dp_uploading_import_cat_file_callback' ));
            // Custom Sort Columns
            add_filter('manage_edit-listings_sortable_columns', array( $this, 'wp_dp_listings_sortable' ));
            add_filter('request', array( $this, 'wp_dp_listings_column_orderby' ));
            // Custom Filter
            add_action('restrict_manage_posts', array( $this, 'wp_dp_admin_listings_filter_restrict_manage_listings' ), 11);
            add_filter('parse_query', array( &$this, 'wp_dp_listing_filter' ), 11, 1);
            // Change Listing Featured
            add_action('wp_ajax_wp_dp_feature_listing', array( $this, 'wp_dp_feature_listing_callback' ), 11, 1);
            add_action('wp_ajax_wp_dp_top_category_listing', array( $this, 'wp_dp_top_category_listing_callback' ), 11, 1);
            add_action('wp_dp_plugin_db_structure_updater', array( $this, 'wp_dp_plugin_db_structure_updater_callback' ), 10);
            // Update Listing Categories by Selected Listing Type Filter
            add_action('wp_ajax_wp_dp_load_categories_by_listing_type', array( $this, 'wp_dp_load_categories_by_listing_type_callback' ));
            add_action('wp_ajax_wp_dp_load_categories_by_listing_type', array( $this, 'wp_dp_load_categories_by_listing_type_callback' ));
            add_filter('wp_insert_post_data', array( $this, 'wp_insert_post_data_callback' ), 10, 2);
            add_action('save_post', array( $this, 'wp_dp_listing_save_post_callback' ));
            add_filter('manage_edit-listing-tag_columns', array( $this, 'listing_category_columns' ));
             
            add_filter( 'views_edit-listings',array( $this,  'remove_total_views') );
            
            add_filter('views_edit-listings', array($this, 'wp_dp_add_analytics'));
            
        }
        
        function remove_total_views( $views ) {
            $remove_views = ['future','sticky','draft','pending'];
            foreach( (array) $remove_views as $view )
            {
                if( isset( $views[$view] ) )
                    unset( $views[$view] );
            }
            return $views;
        }
        
        public function listing_category_columns($columns) {

            unset($columns['description']);
			$columns['posts'] = wp_dp_plugin_text_srt('wp_dp_num_of_listing');
            return $columns;
        }
        

        /*
         * Before the data is saved in listings
         */

        public function wp_insert_post_data_callback($data, $postarr) {
            global $post;
            $listing_id = $postarr['ID'];
            if ( isset($postarr['post_type']) && $postarr['post_type'] == 'listings' ) {
                $old_slug = get_post_field('post_name', $listing_id);
                update_post_meta( $listing_id, 'old_listing_slug', $old_slug );
            }
            return $data;
        }

        /*
         * Saving hook for listings
         */

        public function wp_dp_listing_save_post_callback() {
            global $post;
            $listing_id = isset($post->ID) ? $post->ID : '';
            if ( isset($post->post_type) && $post->post_type == 'listings' ) {
                $old_slug = get_post_meta($listing_id, 'old_listing_slug', true);
                $new_slug = get_post_field('post_name', $listing_id);
                if ( $old_slug != $new_slug ) {
                    delete_post_meta($listing_id, 'wp_dp_ratings');
                    delete_post_meta($listing_id, 'wp_dp_reviews_ids');
                    delete_post_meta($listing_id, 'existing_ratings');
                }
            }
        }

        public function wp_dp_plugin_db_structure_updater_callback() {
            global $wp_dp_listing_meta;
            $search_keywords_updated = get_option('wp_dp_search_keywords_updated');

            if ( $search_keywords_updated != 'yes' ) {
                $listing_ids = get_posts(array(
                    'posts_per_page' => '-1',
                    'fields' => 'ids',
                    'post_type' => 'listings',
                ));
                if ( is_array($listing_ids) && ! empty($listing_ids) ) {
                    foreach ( $listing_ids as $listing_id ) {
                        $post_obj = get_post($listing_id);
                        $response = $wp_dp_listing_meta->wp_dp_save_search_keywords_field($listing_id, $post_obj, 'yes');
                    }
                }
                update_option('wp_dp_search_keywords_updated', 'yes');
            }
        }

        public function listing_type_screen_layout($selected) {
            return 1; // Use 1 column if user hasn't selected anything in Screen Options
        }

        function check_post_type_and_remove_media_buttons() {
            global $current_screen;
            if ( get_post_type() == 'listings' ) {
                remove_action('media_buttons', 'media_buttons');
                echo '<style type="text/css">';
                echo '.post-type-listings .column-listing_image { width:50px !important; overflow:hidden }';
                echo '.post-type-listings .column-featured { width:70px !important; overflow:hidden }';
                echo '.post-type-listings .column-top_category { width:80px !important; overflow:hidden }';
                echo '</style>';
            }
        }

        function remove_cus_meta_boxes() {
            remove_meta_box('submitdiv', 'listings', 'side');
            remove_meta_box('tagsdiv-listing-tag', 'listings', 'side');
            remove_meta_box('wp_dp_locationsdiv', 'listings', 'side');
            remove_meta_box('postimagediv', 'listings', 'side');
            remove_meta_box('mymetabox_revslider_0', 'listings', 'normal');
        }

        /**
         * Start Wp's Initilize action hook Function
         */
        public function wp_dp_listing_init() {
            // Initialize Post Type
            $this->wp_dp_listing_register();
            $this->create_listing_category();
            $this->create_listing_tags();
        }

        /**
         * End Wp's Initilize action hook Function
         */
        public function wp_dp_trim_content() {

            global $post;
            $read_more = '....';
            $the_content = get_the_content($post->ID);
            if ( strlen(get_the_content($post->ID)) > 200 ) {
                $the_content = substr(get_the_content($post->ID), 0, 200) . $read_more;
            }

            return $the_content;
        }

        /**
         * Start Function How to Register post type
         */
        public function wp_dp_listing_register() {

            $labels = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_listings'),
                'menu_name' => wp_dp_plugin_text_srt('wp_dp_listing_listings'),
                'add_new_item' => wp_dp_plugin_text_srt('wp_dp_listing_add_new_listing'),
                'edit_item' => wp_dp_plugin_text_srt('wp_dp_listing_edit_listing'),
                'new_item' => wp_dp_plugin_text_srt('wp_dp_listing_new_listing_item'),
                'add_new' => wp_dp_plugin_text_srt('wp_dp_listing_add_new_listing'),
                'view_item' => wp_dp_plugin_text_srt('wp_dp_listing_view_listing_item'),
                'search_items' => wp_dp_plugin_text_srt('wp_dp_listing_search'),
                'not_found' => wp_dp_plugin_text_srt('wp_dp_listing_nothing_found'),
                'not_found_in_trash' => wp_dp_plugin_text_srt('wp_dp_listing_nothing_found_in_trash'),
                'parent_item_colon' => ''
            );
            $args = array(
                'exclude_from_search' => true,
                'labels' => $labels,
                'public' => true,
                'menu_position' => 26,
                'menu_icon' => wp_dp::plugin_url() . 'assets/backend/images/listings.png',
                'has_archive' => false,
                'capability_type' => 'post',
                'supports' => array( 'title', 'editor', 'thumbnail' )
            );

            register_post_type('listings', $args);
        }

        /**
         * End Function How to Register post type
         */

        /**
         * @Register Listing Category
         * @return
         */
        function create_listing_category() {
            global $wp_dp_var_plugin_static_text;
            $labels = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_listing_categories'),
                'menu_name' => wp_dp_plugin_text_srt('wp_dp_listing_listing_categories_menu_name'),
                'singular_name' => wp_dp_plugin_text_srt('wp_dp_listing_listing_category'),
                'search_items' => wp_dp_plugin_text_srt('wp_dp_listing_listing_category'),
                'all_items' => wp_dp_plugin_text_srt('wp_dp_listing_listing_all_categories'),
                'parent_item' => wp_dp_plugin_text_srt('wp_dp_listing_listing_parent_category'),
                'parent_item_colon' => wp_dp_plugin_text_srt('wp_dp_listing_listing_parent_category_clone'),
                'edit_item' => wp_dp_plugin_text_srt('wp_dp_listing_listing_edit_category'),
                'update_item' => wp_dp_plugin_text_srt('wp_dp_listing_listing_update_category'),
                'add_new_item' => wp_dp_plugin_text_srt('wp_dp_listing_listing_add_new_category'),
                'new_item_name' => wp_dp_plugin_text_srt('wp_dp_listing_listing_category'),
                'menu_name' => wp_dp_plugin_text_srt('wp_dp_listing_listing_categories'),
            );
            $args = array(
                'hierarchical' => true,
                'labels' => $labels,
                'show_ui' => true,
                'show_admin_column' => false,
                'query_var' => false,
                'meta_box_cb' => false,
                'show_in_quick_edit' => false,
                'rewrite' => array( 'slug' => 'listing-category' ),
            );
            register_taxonomy('listing-category', array( 'listings' ), $args);
        }

        /**
         * @Register Listing Tags
         * @return
         */
        function create_listing_tags() {
            global $wp_dp_var_plugin_static_text;
            $labels = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_php_tag_name'),
                'menu_name' => wp_dp_plugin_text_srt('wp_dp_listing_php_tag_menu_name'),
                'singular_name' => wp_dp_plugin_text_srt('wp_dp_listing_php_tag_singular_name'),
                'search_items' => wp_dp_plugin_text_srt('wp_dp_listing_php_tag_search_item'),
                'all_items' => wp_dp_plugin_text_srt('wp_dp_listing_php_tag_all_item'),
                'parent_item' => wp_dp_plugin_text_srt('wp_dp_listing_php_tag_prent_iem'),
                'parent_item_colon' => null,
                'edit_item' => wp_dp_plugin_text_srt('wp_dp_listing_php_tag_edit_item'),
                'update_item' => wp_dp_plugin_text_srt('wp_dp_listing_php_tag_update_item'),
                'add_new_item' => wp_dp_plugin_text_srt('wp_dp_listing_php_tag_add_new_item'),
                'new_item_name' => wp_dp_plugin_text_srt('wp_dp_listing_php_tag_new_item_name'),
                'menu_name' => wp_dp_plugin_text_srt('wp_dp_listing_php_tag_menu_name'),
            );
            $args = array(
                'hierarchical' => false,
                'labels' => $labels,
                'show_ui' => true,
                'show_admin_column' => false,
                'query_var' => true,
                'meta_box_cb' => false,
                'show_in_quick_edit' => false,
                'rewrite' => array( 'slug' => 'listing-tag' ),
            );
            register_taxonomy('listing-tag', array( 'listings' ), $args);
        }

        /**
         * Start Function How to Add Title Columns
         */
        public function wp_dp_listing_columns_add($columns) {

            unset($columns['date']);
            unset($columns['tags']);
            $columns['company'] = wp_dp_plugin_text_srt('wp_dp_listing_company');
            $columns['listing_type'] = wp_dp_plugin_text_srt('wp_dp_listing_listing_type');
            $columns['listing_category'] = wp_dp_plugin_text_srt('wp_dp_listing_listing_category');
            $columns['urgent'] = '<span data-toggle="tooltip" data-placement="bottom" title="' . wp_dp_plugin_text_srt('wp_dp_listing_urgent') . '" class="dashicons dashicons-star-filled"></span>';
            $columns['top_category'] = '<span data-toggle="tooltip" data-placement="bottom" title="' . wp_dp_plugin_text_srt('wp_dp_list_meta_top_category') . '" class="dashicons dashicons-category"></span>';
            $columns['posted'] = wp_dp_plugin_text_srt('wp_dp_listing_listing_posted');
            $columns['expired'] = wp_dp_plugin_text_srt('wp_dp_listing_listing_expired');
            $columns['status'] = wp_dp_plugin_text_srt('wp_dp_listing_listing_status');
            $new_columns = array();
            foreach ( $columns as $key => $value ) {
                $new_columns[$key] = $value;
                if ( $key == 'cb' ) {
                    $new_columns['listing_image'] = '<i data-toggle="tooltip" data-placement="bottom" title="' . wp_dp_plugin_text_srt('wp_dp_listing_column_listing_image') . '" class="dashicons dashicons-format-image"></i>';
                }
            }
            return $new_columns;
        }

        /**
         * End Function How to Add Title Columns
         */

        /**
         * Start Function How to Add  Columns
         */
        public function wp_dp_listing_columns($name) {
            global $post, $gateway;

            switch ( $name ) {
                default:
                    //echo "name is " . $name;
                    break;
                case 'listing_image':
                    if ( function_exists('listing_gallery_first_image') ) {
                        $gallery_image_args = array(
                            'listing_id' => $post->ID,
                            'size' => 'thumbnail',
                            'class' => 'column-listing-image',
                            'default_image_src' => esc_url(wp_dp::plugin_url() . 'assets/backend/images/placeholder.png')
                        );
                        $listing_gallery_first_image = listing_gallery_first_image($gallery_image_args);
                        echo wp_dp_cs_allow_special_char($listing_gallery_first_image);
                    }
                    break;
                case 'company':
                    $wp_dp_listing_member = get_post_meta($post->ID, "wp_dp_listing_member", true); //
                    $member_title = '';
                    if ( $wp_dp_listing_member != '' ) {
                        $member_title = '<a href="' . esc_url(get_edit_post_link($wp_dp_listing_member)) . '">' . get_the_title($wp_dp_listing_member) . '</a>';
                    }
                    echo force_balance_tags($member_title);
                    break;
                case 'listing_type':
                    $listing_type = get_post_meta($post->ID, 'wp_dp_listing_type', true);
                    if ( $listing_type_post = get_page_by_path($listing_type, OBJECT, 'listing-type') ) {
                        $listing_type_id = $listing_type_post->ID;
                    } else {
                        $listing_type_id = 0;
                    }
                    $listing_type_id = wp_dp_wpml_lang_page_id($listing_type_id, 'listing-type');
                    echo '<a href="' . esc_url(get_edit_post_link($listing_type_id)) . '">' . get_the_title($listing_type_id) . '</a>';
                    break;
                case 'listing_category':
                    $wp_dp_listing_category = get_post_meta($post->ID, 'wp_dp_listing_category', true);
                    $wp_dp_listing_category = isset($wp_dp_listing_category['parent']) && $wp_dp_listing_category['parent'] != '' ? $wp_dp_listing_category['parent'] : '';
                    if ( $wp_dp_listing_category != '' ) {
                        $term_exist = term_exists($wp_dp_listing_category, 'listing-category');
                        if ( $term_exist !== 0 && $term_exist !== null ) {
                            $term = get_term_by('slug', $wp_dp_listing_category, 'listing-category');
                            echo '<a href="' . esc_url(admin_url('edit.php?post_type=listings&wp_dp_listing_category=' . $term->slug . '')) . '">' . $term->name . '</a>';
                        } else {
                            echo '-';
                        }
                    } else {
                        echo '-';
                    }
                    break;
                case 'urgent':
                    $urgent = wp_dp_check_listing_urgent_status($post->ID, 'is_featured');
                    echo '<a href="' . esc_url(get_edit_post_link($post->ID)) . '">';
                    if ( $urgent == 'on' ) {
                        echo '<i data-toggle="tooltip" data-placement="bottom" title="' . wp_dp_plugin_text_srt('wp_dp_listing_yes') . '" class="dashicons dashicons-star-filled"></i>';
                    } else {
                        echo '<i data-toggle="tooltip" data-placement="bottom" title="' . wp_dp_plugin_text_srt('wp_dp_listing_no') . '" class="dashicons dashicons-star-empty"></i>';
                    }
                    echo '</a>';
                    break;
                case 'top_category':
                    $top_cat = wp_dp_check_promotion_status($post->ID, 'top-categories');
                    echo '<a href="' . esc_url(get_edit_post_link($post->ID)) . '">';
                    if ( $top_cat == 'on' ) {
                        echo '<i data-toggle="tooltip" data-placement="bottom" title="' . wp_dp_plugin_text_srt('wp_dp_listing_yes') . '" class="dashicons dashicons-category" style="color: green; !important"></i>';
                    } else {
                        echo '<i data-toggle="tooltip" data-placement="bottom" title="' . wp_dp_plugin_text_srt('wp_dp_listing_no') . '" class="dashicons dashicons-category"></i>';
                    }
                    echo '</a>';
                    break;
                case 'posted':
                    $date_format = get_option('date_format');
                    $time_format = get_option('time_format');
                    $wp_dp_listing_posted = get_post_meta($post->ID, 'wp_dp_listing_posted', true);                    
                    $wp_dp_listing_posted_date = isset($wp_dp_listing_posted) && $wp_dp_listing_posted != '' ? date_i18n($date_format, ($wp_dp_listing_posted)) : '';
                    echo esc_html($wp_dp_listing_posted_date);
                    break;
                case 'expired':
                    $date_format = get_option('date_format');
                    $wp_dp_listing_expired = get_post_meta($post->ID, 'wp_dp_listing_expired', true);
                    $wp_dp_listing_expiry_date = isset($wp_dp_listing_expired) && $wp_dp_listing_expired != '' ? date_i18n($date_format, ($wp_dp_listing_expired)) : '';
                    echo esc_html($wp_dp_listing_expiry_date);
                    break;
                case 'views':
                    $wp_dp_views = get_post_meta($post->ID, "wp_dp_count_views", true);
                    echo absint($wp_dp_views);
                    echo ' / ';
                    $wp_dp_favourite = count_usermeta('cs-listings-wishlist', serialize(strval($post->ID)), 'LIKE');
                    echo absint($wp_dp_favourite);
                    echo ' / ';
                    $applications = count_usermeta('cs-listings-applied', serialize(strval($post->ID)), 'LIKE');
                    echo absint($applications);
                    break;
                case 'status':
                    $status = get_post_meta($post->ID, 'wp_dp_listing_status', true);
                    $status_color = '';
                    if ( $status == 'active' ) {
                        $status_color = ' style="color: #2ecc71; font-weight:700; !important"';
                    }
                    if ( $status == 'inactive' ) {
                        $status_color = ' style="color: #f67a82; font-weight:700; !important"';
                    }
                    if ( $status == 'delete' ) {
                        $status_color = ' style="color: #ff0000; font-weight:700; !important"';
                    }
                    if ( $status == 'awaiting-activation' ) {
                        $status_color = ' style="color: #f0ad4e; font-weight:700; !important"';
                    }
                    $listing_status_options = array(
                        'awaiting-activation' => wp_dp_plugin_text_srt('wp_dp_listing_awaiting_activation'),
                        'active' => wp_dp_plugin_text_srt('wp_dp_listing_active'),
                        'inactive' => wp_dp_plugin_text_srt('wp_dp_listing_inactive'),
                        'delete' => wp_dp_plugin_text_srt('wp_dp_listing_deleted')
                    );
                    $status = isset($listing_status_options[$status]) ? $listing_status_options[$status] : $status;
                    echo '<strong ' . $status_color . '>' . ucwords(str_replace('-', ' ', $status)) . '</strong>';
                    break;
            }
        }

        /**
         * End Function How to Add  Columns
         */
        public function wp_dp_listings_sortable($columns) {
            $columns['listing_type'] = 'listing_type';
            $columns['featured'] = 'featured';
            $columns['top_category'] = 'top_category';
            $columns['posted'] = 'posted';
            $columns['expired'] = 'expired';
            $columns['status'] = 'status';
            return $columns;
        }

        public function wp_dp_listings_column_orderby($vars) {
            if ( isset($vars['orderby']) && 'listing_type' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'wp_dp_listing_type',
                    'orderby' => 'meta_value',
                ));
            }
            if ( isset($vars['orderby']) && 'top_category' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'wp_dp_promotion_top-categories',
                    'orderby' => 'meta_value',
                ));
            }
            if ( isset($vars['orderby']) && 'posted' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'wp_dp_listing_posted',
                    'orderby' => 'meta_value_num',
                ));
            }
            if ( isset($vars['orderby']) && 'expired' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'wp_dp_listing_expired',
                    'orderby' => 'meta_value_num',
                ));
            }
            if ( isset($vars['orderby']) && 'status' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'wp_dp_listing_status',
                    'orderby' => 'meta_value',
                ));
            }
            return $vars;
        }

        public function wp_dp_admin_listings_filter_restrict_manage_listings() {
            global $wp_dp_form_fields, $post_type;

            //only add filter to post type you want
            if ( $post_type == 'listings' ) {

                $member_name = isset($_GET['member_name']) ? $_GET['member_name'] : '';
                $wp_dp_opt_array = array(
                    'id' => 'member_name',
                    'cust_name' => 'member_name',
                    'std' => $member_name,
                    'classes' => 'filter-member-name',
                    'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_listing_filter_search_for_member') . '"',
                    'return' => false,
                    'force_std' => true,
                );
                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                remove_filter('parse_query', array( &$this, 'wp_dp_listing_filter' ), 11, 1);
                $listing_types_options = array( '' => wp_dp_plugin_text_srt('wp_dp_listing_filter_all_listing_types') );
                $wp_dp_listing_args = array( 'posts_per_page' => '-1', 'post_type' => 'listing-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => false );
                $cust_query = get_posts($wp_dp_listing_args);
                if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                    foreach ( $cust_query as $wp_dp_listing_type ) {
                        $listing_types_options[$wp_dp_listing_type->post_name] = get_the_title($wp_dp_listing_type->ID);
                    }
                }

                $wp_dp_listing_types = isset($_GET['wp_dp_listing_types']) ? $_GET['wp_dp_listing_types'] : '';
                $wp_dp_listing_category = isset($_GET['wp_dp_listing_category']) ? $_GET['wp_dp_listing_category'] : '';
                $wp_dp_opt_array = array(
                    'std' => $wp_dp_listing_types,
                    'id' => 'listing_types',
                    'extra_atr' => '',
                    'classes' => '',
                    'options' => $listing_types_options,
                    'extra_atr' => 'onchange="wp_dp_listing_type_change_filter(this.value, \'' . $wp_dp_listing_category . '\')" style="width: 150px;"',
                    'return' => false,
                );
                $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);
                add_filter('parse_query', array( &$this, 'wp_dp_listing_filter' ), 11, 1);
                // Listing Categories
                $listing_type_cats_options = array( '' => wp_dp_plugin_text_srt('wp_dp_listing_filter_all_listing_type_categories') );

                $wp_dp_listing_types = isset($_GET['wp_dp_listing_types']) ? $_GET['wp_dp_listing_types'] : '';
                if ( isset($wp_dp_listing_types) && $wp_dp_listing_types != '' ) {

                    if ( $listing_type_post = get_page_by_path($wp_dp_listing_types, OBJECT, 'listing-type') ) {
                        $listing_type_id = $listing_type_post->ID;
                    } else {
                        $listing_type_id = 0;
                    }

                    $listing_type_cats = get_post_meta($listing_type_id, 'wp_dp_listing_type_cats', true);
                    if ( is_array($listing_type_cats) && sizeof($listing_type_cats) > 0 ) {
                        foreach ( $listing_type_cats as $listing_type_cat ) {
                            $term_exist = term_exists($listing_type_cat, 'listing-category');
                            if ( $term_exist !== 0 && $term_exist !== null ) {
                                $term = get_term_by('slug', $listing_type_cat, 'listing-category');
                                $listing_type_cats_options[$listing_type_cat] = $term->name;
                            }
                        }
                    }
                } else {
                    $wp_dp_tags_array = get_terms('listing-category', array(
                        'hide_empty' => false,
                        'parent' => 0,
                    ));
                    if ( is_array($wp_dp_tags_array) && sizeof($wp_dp_tags_array) > 0 ) {
                        foreach ( $wp_dp_tags_array as $dir_tag ) {
                            if ( ! in_array($dir_tag->slug, $wp_dp_tags_array) ) {
                                $listing_type_cats_options[$dir_tag->slug] = $dir_tag->name;
                            }
                        }
                    }
                }

                $wp_dp_listing_category = isset($_GET['wp_dp_listing_category']) ? $_GET['wp_dp_listing_category'] : '';
                echo '<div id="listing_categories_filter" style="float:left;">';
                $wp_dp_opt_array = array(
                    'std' => $wp_dp_listing_category,
                    'id' => 'listing_category',
                    'extra_atr' => '',
                    'classes' => '',
                    'options' => $listing_type_cats_options,
                    'extra_atr' => 'style="width: 150px;"',
                    'return' => false,
                );
                $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);
                echo '</div>';

                $wp_dp_listing_options = array(
                    '' => wp_dp_plugin_text_srt('wp_dp_listing_leftflter_all_listings'),
                    'urgent' => wp_dp_plugin_text_srt('wp_dp_listing_urgent'),
                    'top_category' => wp_dp_plugin_text_srt('wp_dp_listing_top_categories'),
                );
                $wp_dp_listing_type = isset($_GET['wp_dp_listing_type']) ? $_GET['wp_dp_listing_type'] : '';
                $wp_dp_opt_array = array(
                    'std' => $wp_dp_listing_type,
                    'id' => 'listing_type',
                    'extra_atr' => '',
                    'classes' => '',
                    'options' => $wp_dp_listing_options,
                    'return' => false,
                );
                $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);

                $listing_status_options = array(
                    '' => wp_dp_plugin_text_srt('wp_dp_select_listing_status'),
                    'awaiting-activation' => wp_dp_plugin_text_srt('wp_dp_listing_awaiting_activation'),
                    'active' => wp_dp_plugin_text_srt('wp_dp_listing_active'),
                    'inactive' => wp_dp_plugin_text_srt('wp_dp_listing_inactive'),
                    'delete' => wp_dp_plugin_text_srt('wp_dp_listing_delete'),
                    'expire' => wp_dp_plugin_text_srt('wp_dp_listing_expire'),
                );
                $wp_dp_listing_status = isset($_GET['wp_dp_listing_status']) ? $_GET['wp_dp_listing_status'] : '';
                $wp_dp_opt_array = array(
                    'std' => $wp_dp_listing_status,
                    'id' => 'listing_status',
                    'extra_atr' => '',
                    'classes' => '',
                    'options' => $listing_status_options,
                    'return' => false,
                );
                $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);
            }
        }

        function wp_dp_listing_filter($query) {
            global $pagenow;
            $custom_filter_arr = array();
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'listings' && isset($_GET['member_name']) && $_GET['member_name'] != '' ) {
                remove_filter('parse_query', array( &$this, 'wp_dp_listing_filter' ), 11, 1);
                $members_args = array(
                    'post_type' => 'members',
                    'posts_per_page' => -1,
                    's' => $_GET['member_name'],
                    'fields' => 'ids',
                );
                $members_ids = get_posts($members_args);
                wp_reset_postdata();
                add_filter('parse_query', array( &$this, 'wp_dp_listing_filter' ), 11, 1);
                if ( empty($members_ids) ) {
                    $members_ids = array( 0 );
                }
                $custom_filter_arr[] = array(
                    'key' => 'wp_dp_listing_member',
                    'value' => $members_ids,
                    'compare' => 'IN',
                );
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'listings' && isset($_GET['wp_dp_listing_types']) && $_GET['wp_dp_listing_types'] != '' ) {
                $wp_dp_listing_types = isset($_GET['wp_dp_listing_types']) ? $_GET['wp_dp_listing_types'] : '';
                $custom_filter_arr[] = array(
                    'key' => 'wp_dp_listing_type',
                    'value' => $wp_dp_listing_types,
                    'compare' => '=',
                );
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'listings' && isset($_GET['wp_dp_listing_category']) && $_GET['wp_dp_listing_category'] != '' ) {
                $wp_dp_listing_category = isset($_GET['wp_dp_listing_category']) ? $_GET['wp_dp_listing_category'] : '';
                $custom_filter_arr[] = array(
                    'key' => 'wp_dp_listing_category',
                    'value' => serialize($wp_dp_listing_category),
                    'compare' => 'LIKE',
                );
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'listings' && isset($_GET['wp_dp_listing_type']) && $_GET['wp_dp_listing_type'] != '' ) {
                $listing_type = isset($_GET['wp_dp_listing_type']) ? $_GET['wp_dp_listing_type'] : '';
                
                if ( $listing_type == 'urgent' ) {
                    $key_name = 'wp_dp_listing_is_featured';
                } else {
                    $key_name = 'wp_dp_promotion_top-categories';
                }
//                echo $key_name;
                $custom_filter_arr[] = array(
                    'key' => $key_name,
                    'value' => 'on',
                    'compare' => '=',
                );
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'listings' && isset($_GET['wp_dp_listing_status']) && $_GET['wp_dp_listing_status'] != '' ) {
                if ( $_GET['wp_dp_listing_status'] == 'expire' ) {
                    $custom_filter_arr[] = array(
                        'key' => 'wp_dp_listing_expired',
                        'value' => current_time('timestamp', 1),
                        'compare' => '<',
                    );
                } else {
                    $custom_filter_arr[] = array(
                        'key' => 'wp_dp_listing_status',
                        'value' => $_GET['wp_dp_listing_status'],
                        'compare' => '=',
                    );
                }
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'listings' && isset($_GET['m']) && $_GET['m'] != '' && $_GET['m'] != 0) {
                $date_query = [];
                $date_query[] = array(
                    'year'  => substr($_GET['m'],0,4),
                    'month' => substr($_GET['m'],4,5),
                );
                $query->set('date_query', $date_query);
            }
            
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'listings' && ! empty($custom_filter_arr) ) {
                $query->set('meta_query', $custom_filter_arr);
            }
        }

        /**
         * Invoked when daily cron runs for checking if any listing expired.
         */
        public function create_daily_listings_check_callback() {
            $args = array(
                'posts_per_page' => '-1',
                'post_type' => 'listings',
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                        'key' => 'wp_dp_listing_status',
                        'value' => 'active',
                    ),
                ),
            );
            $listings = new WP_Query($args);
            $listings = $listings->get_posts();
            foreach ( $listings as $key => $listing ) {
                $listing_post_expiry = get_post_meta($listing->ID, 'wp_dp_listing_expired', true);
                if ( ! empty($listing_post_expiry) ) {
                    $username = get_post_meta($listing->ID, 'wp_dp_listing_username', true);

                    if ( $listing_post_expiry <= time() ) {
                        update_post_meta($listing->ID, 'wp_dp_listing_status', 'inactive');

                        $listing_member_id = get_post_meta($listing->ID, 'wp_dp_listing_member', true);
                        if ( $listing_member_id != '' ) {
                            do_action('wp_dp_plublisher_listings_decrement', $listing_member_id);
                        }
                        do_action('wp_dp_listing_expired_email', get_user_by('ID', $username), $listing->ID);
                    }
                }
            }
        }

        /**
         * Generate listing type categories backup.
         */
        public function generate_listing_type_categories_backup_callback() {
            global $wp_filesystem;

            require_once ABSPATH . '/wp-admin/includes/file.php';

            $backup_url = wp_nonce_url('edit.php?page=wp_dp_settings');
            if ( false === ( $creds = request_filesystem_credentials($backup_url, '', false, false, array()) ) ) {
                return true;
            }
            if ( ! WP_Filesystem($creds) ) {
                request_filesystem_credentials($backup_url, '', true, false, array());
                return true;
            }

            $terms = get_terms('listing-category', array( 'hide_empty' => 0 ));

            $terms_arr = array();
            $terms_str = 'Name,Parent,Description' . PHP_EOL;
            foreach ( $terms as $key => $term ) {
                $term_arr = array();
                $term_arr[] = $term->name;
                $parent_term = get_term($term->parent, 'listing-category');
                if ( $parent_term != null ) {
                    $term_arr[] = $parent_term->name;
                } else {
                    $term_arr[] = "";
                }
                $term_arr[] = $term->description;
                $terms_str .= '"' . implode('","', $term_arr) . '"' . PHP_EOL;
            }
            $wp_dp_upload_dir = wp_dp::plugin_dir() . 'backend/settings/backups/listing-type-categories/';
            $wp_dp_filename = trailingslashit($wp_dp_upload_dir) . ( current_time('d-M-Y_H.i.s', 1) ) . '.csv';
            if ( ! $wp_filesystem->put_contents($wp_dp_filename, $terms_str, FS_CHMOD_FILE) ) {
                echo wp_dp_plugin_text_srt('wp_dp_listing_php_error_svng_file');
            } else {
                echo wp_dp_plugin_text_srt('wp_dp_listing_php_bkup_generated');
            }
            wp_die();
        }

        /**
         * Delete selected locations back file using AJAX.
         */
        public function delete_listing_type_categories_backup_file_callback() {
            global $wp_filesystem;

            require_once ABSPATH . '/wp-admin/includes/file.php';

            $backup_url = wp_nonce_url('edit.php?post_type=vehicles&page=wp_dp_settings');
            if ( false === ( $creds = request_filesystem_credentials($backup_url, '', false, false, array()) ) ) {
                return true;
            }
            if ( ! WP_Filesystem($creds) ) {
                request_filesystem_credentials($backup_url, '', true, false, array());
                return true;
            }
            $wp_dp_upload_dir = wp_dp::plugin_dir() . 'backend/settings/backups/listing-type-categories/';

            $file_name = isset($_POST['file_name']) ? $_POST['file_name'] : '';
            $wp_dp_filename = trailingslashit($wp_dp_upload_dir) . $file_name;
            if ( is_file($wp_dp_filename) ) {
                unlink($wp_dp_filename);
                printf(wp_dp_plugin_text_srt('wp_dp_listing_php_file_del_successfully'), $file_name);
            } else {
                echo wp_dp_plugin_text_srt('wp_dp_listing_php_error_deleting_file');
            }
            die();
        }

        /**
         * Uploading Category File
         */
        public function wp_dp_uploading_import_cat_file_callback() {
            global $wp_filesystem;

            require_once ABSPATH . '/wp-admin/includes/file.php';

            add_filter('upload_dir', array( $this, 'wp_dp_category_upload_wp_dp' ));
            $uploadedfile = $_FILES['wp_dp_btn_browse_category_file'];
            $upload_overrides = array( 'test_form' => false );
            $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

            if ( $movefile && ! isset($movefile['error']) ) {
                echo esc_html($movefile['url']);
            }
            remove_filter('upload_dir', array( $this, 'wp_dp_category_upload_wp_dp' ));
            wp_die();
        }

        public function wp_dp_category_upload_wp_dp($dir) {
            return array(
                'path' => $dir['basedir'] . '/category',
                'url' => $dir['baseurl'] . '/category',
                'subdir' => '/category',
                    ) + $dir;
        }

        /**
         * Restore location from backup file or URL.
         */
        public function restore_listing_type_categories_backup_callback() {
            global $wp_filesystem;

            require_once ABSPATH . '/wp-admin/includes/file.php';

            $backup_url = wp_nonce_url('edit.php?post_type=vehicles&page=wp_dp_settings');
            if ( false === ( $creds = request_filesystem_credentials($backup_url, '', false, false, array()) ) ) {
                return true;
            }
            if ( ! WP_Filesystem($creds) ) {
                request_filesystem_credentials($backup_url, '', true, false, array());
                return true;
            }
            $wp_dp_upload_dir = wp_dp::plugin_dir() . 'backend/settings/backups/listing-type-categories/';
            $file_name = isset($_POST['file_name']) ? $_POST['file_name'] : '';
            $file_path = isset($_POST['file_path']) ? $_POST['file_path'] : '';
            if ( $file_path == 'yes' ) {
                $wp_dp_file_body = '';

                $wp_remote_get_args = array(
                    'timeout' => 50,
                    'compress' => false,
                    'decompress' => true,
                );


                $wp_dp_file_response = wp_remote_get($file_name, $wp_remote_get_args);
                if ( is_array($wp_dp_file_response) ) {
                    $wp_dp_file_body = isset($wp_dp_file_response['body']) ? $wp_dp_file_response['body'] : '';
                    if ( $wp_dp_file_body != '' ) {
                        $this->import_listing_type_categories($wp_dp_file_body);
                        echo wp_dp_plugin_text_srt('wp_dp_listing_php_file_import_successfully');
                    }
                } else {
                    echo wp_dp_plugin_text_srt('wp_dp_listing_php_error_restoring_file');
                }
            } else {
                $wp_dp_filename = trailingslashit($wp_dp_upload_dir) . $file_name;
                if ( is_file($wp_dp_filename) ) {
                    $locations_file = $wp_filesystem->get_contents($wp_dp_filename);
                    $this->import_listing_type_categories($locations_file);
                    printf(wp_dp_plugin_text_srt('wp_dp_listing_php_file_restore_success'), $file_name);
                } else {
                    echo wp_dp_plugin_text_srt('wp_dp_listing_php_error_restoring_file');
                }
            }
            wp_die();
        }

        public function wp_dp_load_categories_by_listing_type_callback() {
            global $wp_dp_form_fields;

            $selected_listing = wp_dp_get_input('selected_listing', '', 'STRING');
            $selected_category = wp_dp_get_input('selected_category', '', 'STRING');

            if ( $listing_type_post = get_page_by_path($selected_listing, OBJECT, 'listing-type') ) {
                $listing_type_id = $listing_type_post->ID;
            } else {
                $listing_type_id = 0;
            }

            $listing_type_cats = get_post_meta($listing_type_id, 'wp_dp_listing_type_cats', true);
            $listing_type_cats_options = array( '' => wp_dp_plugin_text_srt('wp_dp_listing_filter_all_listing_type_categories') );
            if ( is_array($listing_type_cats) && sizeof($listing_type_cats) > 0 ) {
                foreach ( $listing_type_cats as $listing_type_cat ) {
                    $term_exist = term_exists($listing_type_cat, 'listing-category');
                    if ( $term_exist !== 0 && $term_exist !== null ) {
                        $term = get_term_by('slug', $listing_type_cat, 'listing-category');
                        $listing_type_cats_options[$listing_type_cat] = $term->name;
                    }
                }
            }

            $wp_dp_opt_array = array(
                'std' => $selected_category,
                'id' => 'listing_category',
                'extra_atr' => '',
                'classes' => '',
                'options' => $listing_type_cats_options,
                'extra_atr' => 'style="width: 150px;"',
                'return' => true,
            );
            $html = $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);

            echo json_encode(array( 'type' => 'success', 'html' => $html ));
            die;
        }

        public function import_listing_type_categories($csv_str) {
            $term_new_ids = array();
            $lines = preg_split('/\r*\n+|\r+/', $csv_str);
            $not_found = array();
            foreach ( $lines as $key => $line ) {
                if ( 0 == $key ) {
                    continue;
                }
                $parts = str_getcsv($line);
                if ( count($parts) < 3 ) {
                    continue;
                }
                $args = array(
                    'parent' => 0,
                    'slug' => sanitize_title($parts[0]),
                    'description' => $parts[2],
                );
                if ( ! empty($parts[1]) ) {
                    if ( isset($term_new_ids[$parts[0]]) ) {
                        $args['parent'] = $term_new_ids[$parts[0]];
                    } else {
                        $not_found[] = $line;
                    }
                }
                $return = wp_insert_term(
                        $parts[0], // The term.
                        'listing-category', // The taxonomy.
                        $args
                );
            }
        }
        
        public function wp_dp_add_analytics( $views ) {
            remove_filter('parse_query', array( &$this, 'wp_dp_listing_filter' ), 11, 1);
            $args_expire = array(
                'post_type' => 'listings',
                'posts_per_page' => 1,
                'fields' => 'ids',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_listing_expired',
                        'value' => current_time('timestamp', 1),
                        'compare' => '<',
                    ),
                ),
            );
            $query_expire = new WP_Query($args_expire);
            $count_lisings_expire = $query_expire->found_posts;
            // end expired listing count

            $total_add = wp_count_posts('listings');

            $args = array(
                'post_type' => 'listings',
                'posts_per_page' => "1",
                'fields' => 'ids',
            );
            $args['meta_query'] = array(
                array(
                    'key' => 'wp_dp_listing_status',
                    'value' => 'active',
                    'compare' => '=',
                ),
            );

            $total_query = new WP_Query($args);
            $total_active = $total_query->found_posts;

            /*
             * Getting Free Packages
             */

            $args = array(
                'post_type' => 'packages',
                'posts_per_page' => -1,
                'fields' => 'ids',
            );
            $args['meta_query'] = array(
                array(
                    'key' => 'wp_dp_package_type',
                    'value' => 'free',
                    'compare' => '=',
                ),
            );
            $free_listings_query = new WP_Query($args);
            $free_package_ids = $free_listings_query->posts;

            /*
             * Getting Paid Packages
             */

            $args = array(
                'post_type' => 'packages',
                'posts_per_page' => -1,
                'fields' => 'ids',
            );
            $args['meta_query'] = array(
                array(
                    'key' => 'wp_dp_package_type',
                    'value' => 'paid',
                    'compare' => '=',
                ),
            );
            $paid_listings_query = new WP_Query($args);
            $paid_package_ids = $paid_listings_query->posts;


            /*
             * Free Ads
             */
            $args = array(
                'post_type' => 'listings',
                'posts_per_page' => "1",
                'fields' => 'ids',
            );
            $args['meta_query'] = array(
                array(
                    'key' => 'wp_dp_listing_package',
                    'value' => $free_package_ids,
                    'compare' => 'IN',
                ),
            );
            $free_query = new WP_Query($args);
            $free_ads = $free_query->found_posts;


            /*
             * Paid Ads
             */
            $args = array(
                'post_type' => 'listings',
                'posts_per_page' => "1",
                'fields' => 'ids',
            );
            $args['meta_query'] = array(
                array(
                    'key' => 'wp_dp_listing_package',
                    'value' => $paid_package_ids,
                    'compare' => 'IN',
                ),
            );
            $paid_query = new WP_Query($args);
            $paid_ads = $paid_query->found_posts;

            wp_reset_postdata();
            echo '
            <ul class="total-wp-dp-listing row">
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_listing_php_total_ads') . ' </strong><em>' . $total_add->publish . '</em><i class="icon-coins"></i></div></li>
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_listing_php_active_ads') . ' </strong><em>' . $total_active . '</em><i class="icon-check_circle"></i></div></li>
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_listing_php_expire_ads') . ' </strong><em>' . $count_lisings_expire . '</em><i class="icon-back-in-time"></i></div></li>
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_listing_php_free_ads') . '</strong><em>' . $free_ads . '</em><i class="icon-money_off"></i></div></li>
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_listing_php_paid_ads') . '</strong><em>' . $paid_ads . '</em><i class="icon-attach_money"></i></div></li>
            </ul>
            ';
            return $views;
        }
        }

    // End of class
    // Initialize Object
    $listing_object = new post_type_listing();
}
if (!function_exists('wp_dp_listing_remove_help_tabs')) {
    add_action('admin_head', 'wp_dp_listing_remove_help_tabs');

    function wp_dp_listing_remove_help_tabs() {
        $screen = get_current_screen();
        if ( isset($screen->post_type) && $screen->post_type == 'listings' ) {
            add_filter('screen_options_show_screen', '__return_false');
            add_filter('bulk_actions-edit-listing-type', '__return_empty_array');
            echo '<style type="text/css">
                                    .post-type-listing-type .tablenav.top,
                                    .post-type-listing-type .tablenav.bottom,
                                    .post-type-listing-type #titlediv .inside,
                                    .post-type-listing-type #postdivrich{
                                            display: none;
                                    }
                            </style>';
        }
        
        if ( isset($screen->id) && $screen->id == 'edit-listing-tag' ) {
             echo '<style type="text/css">';
             echo '.term-description-wrap { display:none; }';
             echo '.post-type-listings .column-posts { width:150px !important; overflow:hidden }';
             echo '</style>';
        }
        
        
        
        
    }
}




if ( ! function_exists('wp_dp_get_walk_score') ) {

    function wp_dp_get_walk_score($lat, $lon, $address) {
        $key = sanitize_title($address . $lat . $lon);

        if ( false === ( $results = get_transient($key) ) ) {
            global $wp_dp_plugin_options;
            $walkscore_api_key = isset($wp_dp_plugin_options['wp_dp_walkscore_api_key']) ? $wp_dp_plugin_options['wp_dp_walkscore_api_key'] : '';
            $address = urlencode($address);

            $wp_remote_get_args = array(
                'timeout' => 50,
                'compress' => false,
                'decompress' => true,
            );
            $results = wp_remote_get("http://api.walkscore.com/score?format=json&transit=1&bike=1&address=$address&lat=$lat&lon=$lon&wsapikey=$walkscore_api_key", $wp_remote_get_args);
            set_transient($key, $results, 24 * 7 * HOUR_IN_SECONDS);
        }
        return $results;
    }

}