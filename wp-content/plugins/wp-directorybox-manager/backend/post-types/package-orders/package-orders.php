<?php

// Package Orders start
// Adding columns start

/**
 * Start Function  how to Create colume in transactions 
 */
if (!function_exists('package_orders_columns_add')) {
    add_filter('manage_package-orders_posts_columns', 'package_orders_columns_add');

    function package_orders_columns_add($columns) {
		$new_columns = array();
		unset($columns['date']);
		foreach($columns as $key => $value) {
			$new_columns[$key] = $value;
			if( $key == 'title' ){
				$new_columns[$key] = wp_dp_plugin_text_srt( 'wp_dp_package_id' );
				$new_columns['users'] = wp_dp_plugin_text_srt( 'wp_dp_package_member' );
				$new_columns['package'] = wp_dp_plugin_text_srt( 'wp_dp_package_name' );
				$new_columns['p_date'] = wp_dp_plugin_text_srt( 'wp_dp_package_date' );
				$new_columns['amount'] = wp_dp_plugin_text_srt( 'wp_dp_package_amount' );
				
			}
		}
		return $new_columns;
        
    }

}

/**
 * Start Function  how to Show data in columns
 */
if (!function_exists('package_orders_columns')) {
    add_action('manage_package-orders_posts_custom_column', 'package_orders_columns', 10, 2);

    function package_orders_columns($name) {
        global $post, $gateways, $wp_dp_plugin_options;
        $general_settings = new WP_DP_PAYMENTS();
        $currency_sign = wp_dp_get_currency_sign();
        $transaction_user = get_post_meta($post->ID, 'wp_dp_transaction_user', true);
        $transaction_amount = get_post_meta($post->ID, 'wp_dp_transaction_amount', true);
        $transaction_fee = get_post_meta($post->ID, 'transaction_fee', true);
        $transaction_status = get_post_meta($post->ID, 'wp_dp_transaction_status', true);

        // return payment gateway name
        switch ($name) {
            case 'users':
                echo ($transaction_user) != '' ? '<a href="'. esc_url(get_edit_post_link($transaction_user)) .'">'. get_the_title($transaction_user) .'</a>' : '';
                break;
			case 'p_date':
                //echo human_time_diff(get_post_time('U', true, $post->ID), current_time('timestamp')) . ' ' . wp_dp_plugin_text_srt('wp_dp_package_order_date_ago');
                $date_format = get_option('date_format');
                $time_in_seconds = get_post_time('U', true, $post->ID);
                $output = isset($time_in_seconds) && $time_in_seconds != '' ? wp_remsecond_date_format($date_format, $post->ID, $time_in_seconds) : '';
                echo $output;
                break;
            case 'package':
                $wp_dp_trans_type = get_post_meta(get_the_id(), "wp_dp_transaction_type", true);

                $wp_dp_trans_pkg = get_post_meta(get_the_id(), "wp_dp_transaction_package", true);
                $wp_dp_trans_pkg_title = get_the_title($wp_dp_trans_pkg);

                if ($wp_dp_trans_pkg_title != '') {
                    echo '<a href="'. esc_url(get_edit_post_link($wp_dp_trans_pkg)) .'">'. WP_DP_FUNCTIONS()->special_chars($wp_dp_trans_pkg_title) .'</a>';
                } else {
                    echo '-';
                }
                break;
            case 'amount':
                $wp_dp_trans_amount = get_post_meta(get_the_id(), "wp_dp_transaction_amount", true);
                $currency_sign = get_post_meta(get_the_id(), "wp_dp_currency", true);
                $currency_sign = ( $currency_sign != '' ) ? $currency_sign : '$';
                $currency_position = get_post_meta(get_the_id(), "wp_dp_currency_position", true);
                if ($wp_dp_trans_amount != '') {
                    echo wp_dp_get_order_currency( $wp_dp_trans_amount, $currency_sign, $currency_position );
                } else {
                    echo '-';
                }
                break;
        }
    }

}

if ( ! function_exists('wp_dp_package_orders_sortable') ) {
	add_filter( 'manage_edit-package-orders_sortable_columns', 'wp_dp_package_orders_sortable');
	function wp_dp_package_orders_sortable( $columns ){
		$columns['users'] = 'package_order_user';
		$columns['amount'] = 'package_order';
		$columns['p_date'] = 'p_date';
		return $columns;
	}
}
if ( ! function_exists('wp_dp_admin_package_orders_column_orderby') ) {
	add_filter( 'request', 'wp_dp_admin_package_orders_column_orderby');
	function wp_dp_admin_package_orders_column_orderby(  $vars ){
		if ( isset( $vars['orderby'] ) && 'package_order_user' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => 'wp_dp_transaction_user',
				'orderby' => 'meta_value',
			) );
		}
		if ( isset( $vars['orderby'] ) && 'package_order' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => 'wp_dp_transaction_amount',
				'orderby' => 'meta_value_num',
			) );
		}
		return $vars;
	}
}

if ( ! function_exists('wp_dp_admin_package_orders_filter') ) {
	add_action( 'restrict_manage_posts', 'wp_dp_admin_package_orders_filter', 11 );
	function wp_dp_admin_package_orders_filter(){
		global $wp_dp_form_fields, $post_type, $wpdb;
		//only add filter to post type you want
		if ($post_type == 'package-orders'){
			$querystr = "SELECT id FROM $wpdb->posts WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->posts.post_type = 'package-orders'";
			$package_orders = $wpdb->get_results($querystr);
			$package_names = array();
			if( !empty($package_orders)){
				$package_names[''] = wp_dp_plugin_text_srt('wp_dp_package_tile');
				foreach( $package_orders as $package_order ){
					if( isset($package_order->id)){
						$wp_dp_trans_pkg = get_post_meta($package_order->id, "wp_dp_transaction_package", true);
						$package_names[$wp_dp_trans_pkg] = get_the_title($wp_dp_trans_pkg); 
					}
				}
			}
			if( !empty($package_names)){
				$order_package = isset($_GET['order_package']) ? $_GET['order_package'] : '';
				$wp_dp_opt_array = array(
					'std' => $order_package,
					'id' => 'order_package',
					'cust_name' => 'order_package',
					'extra_atr' => '',
					'classes' => '',
					'options' => $package_names,
					'return' => false,
				);
				$wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);
			}
		}
	}
}

if ( ! function_exists('wp_dp_admin_package_orders_filter_query') ) {
	add_filter('parse_query', 'wp_dp_admin_package_orders_filter_query', 11, 1);
	function wp_dp_admin_package_orders_filter_query( $query ){
		global $pagenow;
		$custom_filter_arr = array();
		if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'package-orders' && isset($_GET['order_package']) && $_GET['order_package'] != '' ) {
			$custom_filter_arr[] = array(
				'key' => 'wp_dp_transaction_package',
				'value' => $_GET['order_package'],
				'compare' => '=',
			);
		}
                if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'package-orders' && isset($_GET['m']) && $_GET['m'] != '' && $_GET['m'] != 0) {
                    $date_query = [];
                    $date_query[] = array(
                        'year'  => substr($_GET['m'],0,4),
                        'month' => substr($_GET['m'],4,5),
                    );
                    $query->set('date_query', $date_query);
                }
		if( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'package-orders' && !empty($custom_filter_arr)){
			$query->set( 'meta_query', $custom_filter_arr );
		}
	}
}



/**
 * Start Function  how create post type of transactions
 */
if (!class_exists('post_type_package_orders')) {

    class post_type_package_orders {

        // The Constructor
        public function __construct() {
            add_action('init', array(&$this, 'transactions_init'));
            add_action('admin_init', array(&$this, 'transactions_admin_init'));
            add_action('admin_menu', array($this, 'wp_dp_remove_post_boxes'));
            add_action('do_meta_boxes', array($this, 'wp_dp_remove_post_boxes'));
            add_filter( 'views_edit-package-orders',array( $this,  'remove_total_views') );
             //remove quick edit
            add_filter('post_row_actions',array( $this, 'remove_quick_edit'),10,1);
        }
        
        function remove_quick_edit( $actions ){
            global $post;
            if($post->post_type == 'package-orders')
                unset($actions['inline hide-if-no-js']);
            return $actions;
        }

        
        public function transactions_init() {
            // Initialize Post Type
            $this->transactions_register();
        }
        
        function remove_total_views( $views ) {
            $remove_views = [ 'all','publish','future','sticky','draft','pending','trash' ];
            foreach( (array) $remove_views as $view )
            {
                if( isset( $views[$view] ) )
                    unset( $views[$view] );
            }
            return $views;
        }

        public function transactions_register() {
            $labels = array(
                'name' => wp_dp_plugin_text_srt( 'wp_dp_package_orders' ),
                'menu_name' => wp_dp_plugin_text_srt( 'wp_dp_package_orders' ),
                'add_new_item' => wp_dp_plugin_text_srt( 'wp_dp_package_add_new' ),
                'edit_item' => wp_dp_plugin_text_srt( 'wp_dp_package_edit' ),
                'new_item' => wp_dp_plugin_text_srt( 'wp_dp_new_package_order' ),
                'add_new' => wp_dp_plugin_text_srt( 'wp_dp_add_new_package_order' ),
                'view_item' => wp_dp_plugin_text_srt( 'wp_dp_view_package_order' ),
                'search_items' => wp_dp_plugin_text_srt( 'wp_dp_package_search' ),
                'not_found' => wp_dp_plugin_text_srt( 'wp_dp_package_nothing_found' ),
                'not_found_in_trash' => wp_dp_plugin_text_srt( 'wp_dp_package_nothing_found_trash' ),
                'parent_item_colon' => ''
            );
            $args = array(
                'labels' => $labels,
                'public' => false,
                'publicly_queryable' => false,
                'show_ui' => true,
                'query_var' => false,
                'menu_icon' => 'dashicons-admin-post',
                'show_in_menu' => 'edit.php?post_type=packages',
                'rewrite' => true,
                'capability_type' => 'post',
                'hierarchical' => false,
                'menu_position' => null,
                'supports' => array('')
            );
            register_post_type('package-orders', $args);
        } 
        /**
         * Start Function  how create add meta boxes of transactions
         */
        public function transactions_admin_init() {
            // Add metaboxes
            add_action('add_meta_boxes', array(&$this, 'wp_dp_meta_transactions_add'));
        }

        public function wp_dp_meta_transactions_add() {
            add_meta_box('wp_dp_meta_transactions', wp_dp_plugin_text_srt( 'wp_dp_package_order_options' ), array(&$this, 'wp_dp_meta_transactions'), 'package-orders', 'normal', 'high');
        }

        public function wp_dp_meta_transactions($post) {
            global $wp_dp_html_fields, $wp_dp_form_fields, $wp_dp_plugin_options;

            $wp_dp_users_list = array('' => wp_dp_plugin_text_srt( 'wp_dp_package_select_member' ));
            $args = array('posts_per_page' => '-1', 'post_type' => 'members', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC');
            $cust_query = get_posts($args);
            if (is_array($cust_query) && sizeof($cust_query) > 0) {
                foreach ($cust_query as $package_post) {
                    if (isset($package_post->ID)) {
                        $package_id = $package_post->ID;
                        $package_title = $package_post->post_title;
                        $wp_dp_users_list[$package_id] = $package_title;
                    }
                }
            }

            $wp_dp_packages_list = array('' => wp_dp_plugin_text_srt( 'wp_dp_select_package' ));
            $args = array('posts_per_page' => '-1', 'post_type' => 'packages', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC');
            $cust_query = get_posts($args);
            if (is_array($cust_query) && sizeof($cust_query) > 0) {
                foreach ($cust_query as $package_post) {
                    if (isset($package_post->ID)) {
                        $package_id = $package_post->ID;
                        $package_title = $package_post->post_title;
                        $wp_dp_packages_list[$package_id] = $package_title;
                    }
                }
            }

            $wp_dp_trans_type = get_post_meta(get_the_id(), "wp_dp_transaction_type", true);

            $transaction_meta = array();
            $transaction_meta['transaction_id'] = array(
                'name' => 'transaction_id',
                'type' => 'hidden_label',
                'title' => wp_dp_plugin_text_srt( 'wp_dp_package_order_id' ),
                'description' => '',
            );
            $transaction_meta['transaction_user'] = array(
                'name' => 'transaction_user',
                'type' => 'select',
                'classes' => 'chosen-select',
                'title' => wp_dp_plugin_text_srt( 'wp_dp_package_user' ),
                'options' => $wp_dp_users_list,
                'description' => '',
            );

            $transaction_meta['transaction_package'] = array(
                'name' => 'transaction_package',
                'type' => 'select',
                'classes' => 'chosen-select-no-single',
                'title' => wp_dp_plugin_text_srt( 'wp_dp_package' ),
                'options' => $wp_dp_packages_list,
                'description' => '',
            );
            $transaction_meta['transaction_amount'] = array(
                'name' => 'transaction_amount',
                'type' => 'text',
                'title' => wp_dp_plugin_text_srt( 'wp_dp_package_amount' ),
                'description' => '',
            );
            $transaction_meta['transaction_listings'] = array(
                'name' => 'transaction_listings',
                'type' => 'text',
                'title' => wp_dp_plugin_text_srt( 'wp_dp_package_no_of_listings' ),
                'description' => '',
            );

            $transaction_meta['transaction_listing_expiry'] = array(
                'name' => 'transaction_listing_expiry',
                'type' => 'text',
                'title' => wp_dp_plugin_text_srt( 'wp_dp_package_listing_expiry' ),
                'description' => '',
            ); 
            $transaction_meta['transaction_listing_pic_num'] = array(
                'name' => 'transaction_listing_pic_num',
                'type' => 'text',
                'title' => wp_dp_plugin_text_srt( 'wp_dp_package_no_of_pictures' ),
                'description' => '',
            );
            $transaction_meta['transaction_listing_doc_num'] = array(
                'name' => 'transaction_listing_doc_num',
                'type' => 'text',
                'title' => wp_dp_plugin_text_srt( 'wp_dp_package_no_of_documents' ),
                'description' => '',
            );
            $transaction_meta['transaction_listing_tags_num'] = array(
                'name' => 'transaction_listing_tags_num',
                'type' => 'text',
                'title' => wp_dp_plugin_text_srt( 'wp_dp_package_no_of_tags' ),
                'description' => '',
            );

            $transaction_meta['transaction_listing_phone_website'] = array(
                'name' => 'transaction_listing_phone_website',
                'type' => 'checkbox',
                'title' => wp_dp_plugin_text_srt( 'wp_dp_listing_phone_num_web_str' ),
                'description' => '',
            );

            $transaction_meta['transaction_listing_social'] = array(
                'name' => 'transaction_listing_social',
                'type' => 'checkbox',
                'title' => wp_dp_plugin_text_srt( 'wp_dp_package_social_impressions' ),
                'description' => '',
            );
            
            $transaction_meta['transaction_listing_video'] = array(
                'name' => 'transaction_listing_video',
                'type' => 'checkbox',
                'title' => wp_dp_plugin_text_srt( 'wp_dp_listing_video' ),
                'description' => '',
            );
            
            $transaction_meta['transaction_status'] = array(
                'name' => 'transaction_status',
                'type' => 'select',
                'classes' => 'chosen-select-no-single',
                'title' => wp_dp_plugin_text_srt( 'wp_dp_package_status' ),
                'options' => array('pending' => wp_dp_plugin_text_srt( 'wp_dp_package_pending' ), 'approved' => wp_dp_plugin_text_srt( 'wp_dp_package_approved' )),
                'description' => '',
            );

            $transaction_meta['transaction_listing_dynamic'] = array(
                'name' => 'transaction_listing_dynamic',
                'type' => 'trans_dynamic',
                'title' => wp_dp_plugin_text_srt( 'wp_dp_package_dynamic_fields' ),
                'description' => '',
            );

            $transaction_meta['transaction_ex_features'] = array(
                'type' => 'extra_features',
                'title' => wp_dp_plugin_text_srt( 'wp_dp_package_listings' ),
            );

            $html = '<div class="page-wrap">
                        <div class="option-sec" style="margin-bottom:0;">
                                <div class="opt-conts">
                                        <div class="wp-dp-review-wrap">';
            foreach ($transaction_meta as $key => $params) {
                $html .= wp_dp_create_package_orders_fields($key, $params);
            }

            $html .=                    '</div>
                                </div>
                        </div>';
            $wp_dp_opt_array = array(
                'std' => '1',
                'id' => 'transactions_form',
                'cust_name' => 'transactions_form',
                'cust_type' => 'hidden',
                'return' => true,
            );
            $html .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
            $html .= '
				<div class="clear"></div>
			</div>';
            echo force_balance_tags($html);
        }
        
         public function wp_dp_remove_post_boxes() {
            remove_meta_box('mymetabox_revslider_0', 'package-orders', 'normal');
        }

    }

    /**
     * End Function  how create add meta boxes of transactions
     */
    return new post_type_package_orders();
}


/**
 * Start Function  how to Row in columns
 */
if (!function_exists('remove_row_actions')) {
    add_filter('post_row_actions', 'remove_row_actions', 10, 1);

    function remove_row_actions($actions) {
        if (get_post_type() == 'package-orders') {
            unset($actions['view']);
            unset($actions['trash']);
            unset($actions['inline hide-if-no-js']);
        }
        return $actions;
    }

}
