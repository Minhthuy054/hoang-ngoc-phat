<?php

/**
 * File Type: Price Tables Post Type
 */
if (!class_exists('post_type_price_tables')) {

    class post_type_price_tables {

        /**
         * Start Contructer Function
         */
        public function __construct() {
            add_action('init', array(&$this, 'wp_dp_price_tables_register'), 12);
            add_filter('manage_wp-dp-pt_posts_columns', array($this, 'price_tables_cpt_columns'));
            add_action( 'manage_wp-dp-pt_posts_custom_column' , array($this,'price_tables_cpt_custom_columns') , 10 , 2 );
           // add_action('manage_price_tables_posts_custom_column', array($this, 'custom_price_tables_column'), 10, 2);
            add_filter('manage_edit-wp-dp-pt_sortable_columns', array( $this, 'wp_dp_pt_sortable' ));
            add_shortcode('wp_dp_price_table', array($this, 'wp_dp_price_table_shortcode_function'));
            add_action('admin_menu', array($this, 'wp_dp_remove_post_boxes'));
            add_action('do_meta_boxes', array($this, 'wp_dp_remove_post_boxes'));
            
            add_filter('parse_query', array( &$this, 'wp_dp_price_tables_filter' ), 11, 1);
        }

        function wp_dp_price_tables_filter($query) {
            global $pagenow;
            $custom_filter_arr = array();
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'wp-dp-pt' && isset($_GET['m']) && $_GET['m'] != '' && $_GET['m'] != 0) {
                $date_query = [];
                $date_query[] = array(
                    'year'  => substr($_GET['m'],0,4),
                    'month' => substr($_GET['m'],4,5),
                );
                $query->set('date_query', $date_query);
            }
            
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'wp-dp-pt' && ! empty($custom_filter_arr) ) {
                $query->set('meta_query', $custom_filter_arr);
            }
        }
        
        /**
         * Start Wp's Initilize action hook Function
         */
        public function wp_dp_price_tables_init() {
            // Initialize Post Type
            $this->wp_dp_price_tables_register();
        }
        
      
        /**
         * Start Function How to Register post type
         */
        public function wp_dp_price_tables_register() {
            $labels = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_post_type_price_table_name'),
                'singular_name' => wp_dp_plugin_text_srt('wp_dp_post_type_price_table_singular_name'),
                'menu_name' => wp_dp_plugin_text_srt('wp_dp_post_type_price_table_menu_name'),
                'name_admin_bar' => wp_dp_plugin_text_srt('wp_dp_post_type_price_table_name_admin_bar'),
                'add_new' => wp_dp_plugin_text_srt('wp_dp_post_type_price_table_add_new'),
                'add_new_item' => wp_dp_plugin_text_srt('wp_dp_post_type_price_table_add_new_item'),
                'new_item' => wp_dp_plugin_text_srt('wp_dp_post_type_price_table_new_item'),
                'edit_item' => wp_dp_plugin_text_srt('wp_dp_post_type_price_table_edit_item'),
                'view_item' => wp_dp_plugin_text_srt('wp_dp_post_type_price_table_view_item'),
                'all_items' => wp_dp_plugin_text_srt('wp_dp_post_type_price_table_all_items'),
                'search_items' => wp_dp_plugin_text_srt('wp_dp_post_type_price_table_search_items'),
                'not_found' => wp_dp_plugin_text_srt('wp_dp_post_type_price_table_not_found'),
                'not_found_in_trash' => wp_dp_plugin_text_srt('wp_dp_post_type_price_table_not_found_in_trash'),
            );

            $args = array(
                'labels' => $labels,
                'description' => wp_dp_plugin_text_srt('wp_dp_price_tables'),
                'public' => false,
                'publicly_queryable' => false,
                'show_ui' => true,
                'show_in_menu' => 'edit.php?post_type=packages',
                'query_var' => false,
                'rewrite' => array('slug' => 'wp-dp-pt'),
                'capability_type' => 'post',
                'has_archive' => false,
                'hierarchical' => false,
                'exclude_from_search' => true,
                'supports' => array('title')
            );

            register_post_type('wp-dp-pt', $args);
        }

        /*
         * add custom column to to row
         */

        public function price_tables_cpt_columns($columns) {
            unset($columns['date']);
            $new_columns = array();
            $new_columns['pt_publish_date'] = wp_dp_plugin_text_srt('wp_dp_price_table_company');;
            return array_merge($columns, $new_columns);
        }

        /**
        * Add Columns to custom post type table
        */
        function price_tables_cpt_custom_columns($column_name , $post_id){
           $date_format = get_option('date_format');
           switch ( $column_name ) {
                default:
                    //echo "name is " . $name;
                    break;
                case 'pt_publish_date':
                    $pfx_date = get_the_date( $date_format, $post_id );
                    echo $pfx_date;
                    break;
           }
        }
        
          /**
         * 
         */
        function wp_dp_pt_sortable($columns){
            $columns['pt_publish_date'] = 'pt_publish_date';
            return $columns;
        }


        public function wp_dp_remove_post_boxes() {
            remove_meta_box('mymetabox_revslider_0', 'wp-dp-pt', 'normal');
        }
        // End of class	
    }

    // Initialize Object
    $price_tables_object = new post_type_price_tables();
}
