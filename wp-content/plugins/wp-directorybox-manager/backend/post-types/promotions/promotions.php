<?php
// Package Orders start
// Adding columns start

/**
 * Start Function  how to Create colume in transactions 
 */
if ( ! function_exists('promotion_orders_columns_add') ) {
    add_filter('manage_promotion-orders_posts_columns', 'promotion_orders_columns_add');

    function promotion_orders_columns_add($columns) {
        $new_columns = array();

        unset($columns['title']);
        unset($columns['date']);
        
        
        $new_columns['listing'] = wp_dp_plugin_text_srt('wp_dp_listing_services_description');
        $new_columns['member'] = wp_dp_plugin_text_srt('wp_dp_promotion_member');
        $new_columns['amount'] = wp_dp_plugin_text_srt('wp_dp_promotion_total_amount');
		$new_columns['p_date'] = wp_dp_plugin_text_srt('wp_dp_promotions_date_col_title');
        $new_columns['status'] = wp_dp_plugin_text_srt('wp_dp_promotion_status');
       
        return $new_columns;
    }

}

/**
 * Start Function  how to Show data in columns
 */
if ( ! function_exists('promotion_orders_columns') ) {
    add_action('manage_promotion-orders_posts_custom_column', 'promotion_orders_columns', 10, 2);

    function promotion_orders_columns($name) {
        global $post, $gateways, $wp_dp_plugin_options, $wp_dp_form_fields;
       
        $general_settings = new WP_DP_PAYMENTS();
        $currency_sign = wp_dp_get_currency_sign();
        $transaction_user = get_post_meta($post->ID, 'wp_dp_member', true);
        $wp_dp_listing_id = get_post_meta($post->ID, 'wp_dp_listing_id', true);
        $transaction_amount = get_post_meta($post->ID, 'wp_dp_total_amount', true);
        $transaction_status = get_post_meta($post->ID, 'wp_dp_transaction_status', true);
        $wp_dp_promotions = get_post_meta($post->ID, 'wp_dp_promotions', true);
        $wp_dp_order_status = get_post_meta($post->ID, 'wp_dp_order_status', true);

        $wp_dp_promotion_id = get_the_title(); 

        $promotins_text = '';
        if ( ! empty($wp_dp_promotions) ) {
            $promotins_text .= '<div><b>' . wp_dp_plugin_text_srt('wp_dp_promotion_orders_id')  . $wp_dp_promotion_id .  '</b></div>';
            $total_promotions_text_count = count($wp_dp_promotions);
            $count = 1;
            foreach ( $wp_dp_promotions as $promotion ) { 
                $promotins_text .= $total_promotions_text_count == $count ? '<span>' . $promotion['title'] . ' </span>' : '<span>' . $promotion['title'] . ', </span>';
                $count++;
            }
        }
        // return payment gateway name
        switch ( $name ) {
            case 'promotion_title':
				//the_title();
                break;
			case 'p_date':
				echo str_replace('Published', ' ', get_the_date()); 
				break;
            case 'listing':
                echo ($transaction_user) != '' ? '<a href="' . esc_url(get_edit_post_link($wp_dp_listing_id)) . '">' . get_the_title($wp_dp_listing_id) . '</a>' : '';
                echo ($promotins_text);
                break;
            case 'member':
                echo ($transaction_user) != '' ? '<a href="' . esc_url(get_edit_post_link($transaction_user)) . '">' . get_the_title($transaction_user) . '</a>' : '';
                break;
            case 'amount':
                $wp_dp_trans_amount = get_post_meta(get_the_id(), "wp_dp_total_amount", true);
                $currency_sign = get_post_meta(get_the_id(), "wp_dp_currency", true);
                $currency_sign = ( $currency_sign != '' ) ? $currency_sign : '$';
                $currency_position = get_post_meta(get_the_id(), "wp_dp_currency_position", true);
                if ( $wp_dp_trans_amount != '' ) {
                    echo wp_dp_get_order_currency($wp_dp_trans_amount, $currency_sign, $currency_position);
                } else {
                    echo '-';
                }
                break;
            case 'status' :
                $wp_dp_opt_array = array(
                    'std' => $wp_dp_order_status,
                    'id' => 'order_status',
                    'extra_atr' => ' onchange="wp_dp_promotion_ststus_change(this.value, ' . $post->ID . ')" ',
                    'classes' => '',
                    'return' => false,
                    'options' => array( 'pending' => wp_dp_plugin_text_srt('wp_dp_package_pending'), 'approved' => wp_dp_plugin_text_srt('wp_dp_package_approved') ),
                );
                $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);
                echo '<div class="wp_dp_promotion_action_' . $post->ID . ' promotion-status-loader"></div>';
            break;
        }
    }

}
if ( ! function_exists('wp_dp_promotion_ststus_change_callback') ) {


    function wp_dp_promotion_ststus_change_callback($columns) {

        if ( isset($_POST) ) {
            $promotion_id = $_POST['promotion_id'];
            $promotion_value = $_POST['promotion_value'];
            if ( ! empty($promotion_id) && ! empty($promotion_value) ) {
                update_post_meta($promotion_id, 'wp_dp_order_status', $promotion_value);
                $msg = wp_dp_plugin_text_srt('wp_dp_claim_action_success');
            } else {
                $msg = wp_dp_plugin_text_srt('wp_dp_claim_action_error');
            }
        }
        echo json_encode(array( 'msg' => $msg ));
        wp_die();
    }

    add_action('wp_ajax_wp_dp_promotion_ststus_change', 'wp_dp_promotion_ststus_change_callback');
}
if ( ! function_exists('wp_dp_promotion_orders_sortable') ) {
    add_filter('manage_edit-promotion-orders_sortable_columns', 'wp_dp_promotion_orders_sortable');

    function wp_dp_promotion_orders_sortable($columns) {
        $columns['member'] = 'promotion_order_user';
        $columns['amount'] = 'promotion_order';
		$columns['p_date'] = 'p_date';
        return $columns;
    }

}
if ( ! function_exists('wp_dp_admin_promotion_orders_column_orderby') ) {
    add_filter('request', 'wp_dp_admin_promotion_orders_column_orderby');

    function wp_dp_admin_promotion_orders_column_orderby($vars) {
        if ( isset($vars['orderby']) && 'promotion_order_user' == $vars['orderby'] ) {
            $vars = array_merge($vars, array(
                'meta_key' => 'wp_dp_member',
                'orderby' => 'meta_value',
            ));
        }
        if ( isset($vars['orderby']) && 'promotion_order' == $vars['orderby'] ) {
            $vars = array_merge($vars, array(
                'meta_key' => 'total_amount',
                'orderby' => 'meta_value_num',
            ));
        }
        return $vars;
    }

}

/**
 * Start Function  how to Row in columns
 */
if ( ! function_exists('remove_row_actions') ) {
    add_filter('post_row_actions', 'remove_row_actions', 10, 1);

    function remove_row_actions($actions) {
        if ( get_post_type() == 'promotion-orders' ) {
            unset($actions['view']);
            unset($actions['trash']);
            unset($actions['editinline']);
        }
        return $actions;
    }

}

/**
 * Start Function  how create post type of transactions
 */
if ( ! class_exists('post_type_promotion_orders') ) {

    class post_type_promotion_orders {

        // The Constructor
        public function __construct() {
            add_action('init', array( &$this, 'transactions_init' ));
            add_action('admin_init', array( &$this, 'transactions_admin_init' ));
            add_action('admin_menu', array( $this, 'wp_dp_remove_post_boxes' ));
            add_action('do_meta_boxes', array( $this, 'wp_dp_remove_post_boxes' ));
            add_action('admin_head', array( $this, 'disable_new_posts_capability_callback' ), 11);
            add_filter('post_row_actions', array( $this, 'remove_row_actions' ), 10, 2);
			add_filter('bulk_actions-edit-promotion-orders', array($this, 'wp_dp_remove_edit'));
            add_action('wp_ajax_wp_dp_promotions_detail_content', array( $this, 'wp_dp_promotions_detail_content_callback' ));
            add_action('views_edit-promotion-orders', array( $this, 'wp_dp_remove_views' ));
			
            add_action('restrict_manage_posts', array($this, 'wp_dp_promotions_filters'));
            add_filter('parse_query', array($this, 'wp_dp_promotions_filters_query'), 11, 1);
            
            add_filter('views_edit-promotion-orders', array( $this, 'show_promotion_orders_status_dashboard_list') );
            add_filter( 'views_edit-promotion-orders',array( $this,  'remove_total_views') );
        }
        
        public function show_promotion_orders_status_dashboard_list($views){
            $total_promotion = wp_count_posts('promotion-orders');
            remove_filter('parse_query', array(&$this, 'wp_dp_promotions_filters_query'), 11, 1);
            $args_approved = array(
                'post_type' => 'promotion-orders',
                'posts_per_page' => 1,
                'fields' => 'ids',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_order_status',
                        'value' => 'approved',
                        'compare' => 'LIKE',
                    ),
                ),
            );
            $query_approved = new WP_Query($args_approved);
            $count_promotion_approved = $query_approved->found_posts;
            // end promotion approved count

            $args_pending = array(
                'post_type' => 'promotion-orders',
                'posts_per_page' => 1,
                'fields' => 'ids',
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => 'wp_dp_order_status',
                        'value' => 'pending',
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key' => 'wp_dp_order_status',
                        'value' => '',
                        'compare' => 'NOT EXISTS',
                    ),
                ),
            );
            $query_pending = new WP_Query($args_pending);
            $count_promotion_pending = $query_pending->found_posts;
            // end promotion pending count



            wp_reset_postdata();

            echo '
            <ul class="total-wp-dp-listing row">
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_promotions_total') . ' </strong><em>' . $total_promotion->publish  . '</em><i class="icon-line-chart"></i></div></li>
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_promotions_approved') . ' </strong><em>' . $count_promotion_approved . '</em><i class="icon-check_circle"></i></div></li>
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_promotions_pending') . ' </strong><em>' . $count_promotion_pending . '</em><i class="icon-back-in-time"></i></div></li>
            </ul>
            ';
            return $views;
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
		
		public function wp_dp_remove_edit($actions){
			unset($actions['edit']);
                        unset($actions['all']);
                        unset($actions['trash']);
			
			return $actions;
		}
		
		public function wp_dp_promotions_filters(){
			global $post_type, $wp_dp_form_fields;
			if($post_type == 'promotion-orders'){
				$member_name = isset($_GET['member_name']) ? $_GET['member_name'] : '';
				$wp_dp_opt_array = array(
					'id' => 'member_name',
					'cust_name' => 'member_name',
					'std' => $member_name,
					'classes' => 'filter-member-name',
					'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_promotion_member_filter') . '"',
					'return' => false,
					'force_std' => true,
				);
				$wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
			}
		}
		public function wp_dp_promotions_filters_query($query){
			global $pagenow;
			$custom_filters_arr	=	array();
			
			if(is_admin() && $pagenow == 'edit.php' && isset($_GET['member_name']) && $_GET['member_name'] != '' && isset($_GET['post_type']) && $_GET['post_type'] == 'promotion-orders'){
				remove_filter('parse_query', array(&$this, 'wp_dp_promotions_filters_query'), 11, 1);
				$member_args	=	array(
					'post_type'	=>	'members',
					'posts_per_page'	=>	-1,
					's'	=>	$_GET['member_name'],
					'fields'	=>	'ids'
				);
				$member_ids	=	get_posts($member_args);
				wp_reset_postdata();
				
				add_filter('parse_query', array(&$this, 'wp_dp_promotions_filters_query'), 11, 1);
				if(empty($member_ids)){
					$member_ids	=	array(0);
				}
				$custom_filters_arr[]	=	array(
					'key'	=>	'wp_dp_member',
					'value'	=>	$member_ids,
					'compare'	=> 'IN'
				);
			}
                        if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'promotion-orders' && isset($_GET['m']) && $_GET['m'] != '' && $_GET['m'] != 0) {
                            $date_query = [];
                            $date_query[] = array(
                                'year'  => substr($_GET['m'],0,4),
                                'month' => substr($_GET['m'],4,5),
                            );
                            $query->set('date_query', $date_query);
                        }
			if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'promotion-orders' && ! empty($custom_filters_arr) ) {
				$query->set('meta_query', $custom_filters_arr);
			}
			
		}
        public function wp_dp_remove_views($views) {
            unset($views['publish']);
            return $views;
        }
        public function remove_row_actions($actions, $post) {
            if ( get_post_type() === 'promotion-orders' ) {
                $actions = array(
                    'content' => '<a onClick="javascript:wp_dp_promotions_detail_content(' . $post->ID . ');" href="#TB_inline?width=600&height=800&inlineId=promotion-content-popup-' . $post->ID . '" class="thickbox">' . wp_dp_plugin_text_srt('wp_dp_email_content_link_text') . '</a> ',
                    'delete' => '<a href="' . get_delete_post_link($post->ID, '', true) . '">' . wp_dp_plugin_text_srt('wp_dp_listing_delete') . '</a>',
                );
            }
            ?>
            <div style="display:none;" id="promotion-content-popup-<?php echo $post->ID ?>">
                <div class="promotions-content-wrapper-<?php echo $post->ID ?> popup-loader user-fancy-modal-box"></div>
                <?php
                ?>
            </div>
            <?php
            return $actions;
        }

        public function wp_dp_promotions_detail_content_callback() {
            // Initialize Post Type
            $promotion_id = $_POST['promotion_id'];
            $member_name = get_post_meta($promotion_id, 'wp_dp_member', true);
            $wp_dp_total_amount = get_post_meta($promotion_id, 'wp_dp_total_amount', true);
            $wp_dp_vat_amount = get_post_meta($promotion_id, 'wp_dp_vat_amount', true);
            $wp_dp_listing_id = get_post_meta($promotion_id, 'wp_dp_listing_id', true);
            $wp_dp_order_status = get_post_meta($promotion_id, 'wp_dp_order_status', true);
            $currency_sign = get_post_meta($promotion_id, "wp_dp_currency", true);
            $currency_sign = ( $currency_sign != '' ) ? $currency_sign : '$';
            $output = '';
            $output .='<div class="modelbox-wrapper">'; // wrapper
            $output .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="popup-title">
                                <h2>' . wp_dp_plugin_text_srt('wp_dp_price_order_detail') . '</h2>
                            </div>
                        </div>';
            $output .='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            $output .='<div class="popup-value-holder">
                    <h3><strong>' . wp_dp_plugin_text_srt('wp_dp_promotion_member') . ': </strong><span> ' . get_the_title($member_name) . ' </span></h3>
                </div>';
            $list_url_title = '';
            if ( ! empty($member_name) ) {
                $list_url_title = '<a href="' . esc_url(get_edit_post_link($wp_dp_listing_id)) . '">' . get_the_title($wp_dp_listing_id) . '</a>';
            }
            $output .='<div class="popup-value-holder">
                            <h3><strong>' . wp_dp_plugin_text_srt('wp_dp_promotion_listing') . ': </strong><span> ' . $list_url_title . '</span></h3>
                        </div>';
            $wp_dp_promotions = get_post_meta($promotion_id, 'wp_dp_promotions', true);
            $publish_date = get_the_date('d/M/Y',$promotion_id);
            // promotion
            $output .= '<div class="popup-list-holder">
            <div class="list-title">
                <h3>' . wp_dp_plugin_text_srt('wp_dp_transaction_promotions') . '</h3>
            </div>
            <ul>';
            $currency_position = get_post_meta($promotion_id, "wp_dp_currency_position", true);
            if ( is_array($wp_dp_promotions) && ! empty($wp_dp_promotions) ) {
                foreach ( $wp_dp_promotions as $promotion_array ) {
                    if ( isset($promotion_array['price']) && $promotion_array['price'] != '' ) {
                        $price = wp_dp_get_order_currency($promotion_array['price'], $currency_sign, $currency_position);
                    } else {
                        $price = wp_dp_plugin_text_srt('wp_dp_package_type_free');
                    }
                    $expiry_date = isset($promotion_array['expiry']) ? $promotion_array['expiry'] : 'unlimitted';
                    if ( $expiry_date == '' ) {
                        $expiry_date = 'unlimitted';
                    }
                    if ( $expiry_date != 'unlimitted' ) {
                        $expiry_date = date("d/M/Y", strtotime($expiry_date));
                        $expiry_date = $publish_date . ' - ' . $expiry_date;
                    }
                    $output .='<li><h4><strong>' . wp_dp_plugin_text_srt('wp_dp_promotion_title') . ':</strong><span> ' . $promotion_array['title'] . ' </span></h4></li>';
                    $output .='<li><h4><strong>' . wp_dp_plugin_text_srt('wp_dp_promotion_amount') . ':</strong><span> ' . $price . ' </span></h4></li>';
                    $output .='<li><h4><strong>' . wp_dp_plugin_text_srt('wp_dp_promotion_duration') . ':</strong><span> ' . $promotion_array['duration'] . ' Days (' . $expiry_date . ') </span></h4></li>';
                }
            }
            $output .= '</ul></div>';
            // status
            $output .='<div class="popup-value-holder">
                    <h3><strong>' . wp_dp_plugin_text_srt('wp_dp_promotion_vat') . ': </strong><span> ' . $currency_sign . $wp_dp_vat_amount . ' </span></h3>
                </div>';
            $output .='<div class="popup-value-holder">
                    <h3><strong>' . wp_dp_plugin_text_srt('wp_dp_promotion_total_amount') . ': </strong><span> ' . $currency_sign . $wp_dp_total_amount . ' </span></h3>
                </div>';
            $output .= '<div class="popup-value-holder">
                            <h3><strong>' . wp_dp_plugin_text_srt('wp_dp_promotion_status') . ': </strong><span> ' . ($wp_dp_order_status) . '</span></h3>
                        </div>';
            $output .=' </div>'; // end cols
            $output .=' </div>'; // wrapper close
            echo force_balance_tags($output);
            wp_die();
        }

        public function transactions_init() {
            // Initialize Post Type
            $this->transactions_register();
        }

        public function transactions_register() {
            $labels = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_promotion_orders'),
                'menu_name' => wp_dp_plugin_text_srt('wp_dp_promotion_orders'),
                'add_new_item' => wp_dp_plugin_text_srt('wp_dp_promotion_add_new'),
                'edit_item' => wp_dp_plugin_text_srt('wp_dp_promotion_edit'),
                'new_item' => wp_dp_plugin_text_srt('wp_dp_new_promotion_order'),
                'add_new' => wp_dp_plugin_text_srt('wp_dp_add_new_promotion_order'),
                'view_item' => wp_dp_plugin_text_srt('wp_dp_view_promotion_order'),
                'search_items' => wp_dp_plugin_text_srt('wp_dp_promotion_search'),
                'not_found' => wp_dp_plugin_text_srt('wp_dp_promotion_nothing_found'),
                'not_found_in_trash' => wp_dp_plugin_text_srt('wp_dp_promotion_nothing_found_trash'),
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
                'supports' => array( '' ),
                'capabilities' => array(
                'create_posts' => false, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
                ),
                'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
            );
            register_post_type('promotion-orders', $args);
        }

        public function disable_new_posts_capability_callback() {
            global $post;

            // Hide link on listing page.
            if ( get_post_type() == 'promotion-orders' ) {
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
                    .post-type-promotion-orders .column-listing  { width:350px !important; overflow:hidden }
                    .post-type-promotion-orders .column-member   { width:100px !important; overflow:hidden }
                    .post-type-promotion-orders .column-amount   { width:100px !important; overflow:hidden }
                    .post-type-promotion-orders .column-p_date   { width:100px !important; overflow:hidden }
                    .post-type-promotion-orders .column-status   { width:100px !important; overflow:hidden }

                </style>
                <?php
            }
        }

        /**
         * Start Function  how create add meta boxes of transactions
         */
        public function transactions_admin_init() {
            // Add metaboxes
            add_action('add_meta_boxes', array( &$this, 'wp_dp_meta_transactions_add' ));
        }

        public function wp_dp_meta_transactions_add() {
            add_meta_box('wp_dp_meta_transactions', wp_dp_plugin_text_srt('wp_dp_promotion_order_options'), array( &$this, 'wp_dp_meta_transactions' ), 'promotion-orders', 'normal', 'high');
        }

        public function wp_dp_meta_transactions($post) {
            global $wp_dp_html_fields, $wp_dp_form_fields, $wp_dp_plugin_options;
            $wp_dp_users_list = array( '' => wp_dp_plugin_text_srt('wp_dp_promotion_select_member') );
            $args = array( 'posts_per_page' => '-1', 'post_type' => 'members', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC' );
            $cust_query = get_posts($args);
            if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                foreach ( $cust_query as $promotion_post ) {
                    if ( isset($promotion_post->ID) ) {
                        $promotion_id = $promotion_post->ID;
                        $promotion_title = $promotion_post->post_title;
                        $wp_dp_users_list[$promotion_id] = $promotion_title;
                    }
                }
            }

            $wp_dp_promotions_list = array( '' => wp_dp_plugin_text_srt('wp_dp_select_promotion') );
            $args = array( 'posts_per_page' => '-1', 'post_type' => 'promotions', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC' );
            $cust_query = get_posts($args);
            if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                foreach ( $cust_query as $promotion_post ) {
                    if ( isset($promotion_post->ID) ) {
                        $promotion_id = $promotion_post->ID;
                        $promotion_title = $promotion_post->post_title;
                        $wp_dp_promotions_list[$promotion_id] = $promotion_title;
                    }
                }
            }

            $wp_dp_trans_type = get_post_meta(get_the_id(), "wp_dp_transaction_type", true);

            $transaction_meta = array();

            $transaction_meta['member'] = array(
                'name' => 'member',
                'type' => 'select',
                'classes' => 'chosen-select',
                'title' => wp_dp_plugin_text_srt('wp_dp_promotion_member'),
                'options' => $wp_dp_users_list,
                'extra_atr' => 'disabled',
                'description' => '',
            );

            $transaction_meta['listing_id'] = array(
                'name' => 'listing_id',
                'type' => 'info',
                'title' => wp_dp_plugin_text_srt('wp_dp_promotion_listing'),
                'description' => '',
            );

            $transaction_meta['total_amount'] = array(
                'name' => 'total_amount',
                'type' => 'text',
                'title' => wp_dp_plugin_text_srt('wp_dp_promotion_total_amount'),
                'extra_atr' => 'disabled',
                'description' => '',
            );

            $transaction_meta['transaction_promotions'] = array(
                'name' => 'transaction_promotions',
                'type' => 'summary',
                'title' => wp_dp_plugin_text_srt('wp_dp_transaction_promotions'),
                'description' => '',
            );

            $transaction_meta['order_status'] = array(
                'name' => 'order_status',
                'type' => 'select',
                'classes' => 'chosen-select-no-single',
                'title' => wp_dp_plugin_text_srt('wp_dp_promotion_status'),
                'options' => array( 'pending' => wp_dp_plugin_text_srt('wp_dp_package_pending'), 'approved' => wp_dp_plugin_text_srt('wp_dp_package_approved') ),
                'description' => '',
            );

            $html = '<div class="page-wrap">
						<div class="option-sec" style="margin-bottom:0;">
							<div class="opt-conts">
								<div class="wp-dp-review-wrap">
									<script type="text/javascript">
										//jQuery(function(){
											//jQuery("#wp_dp_transaction_expiry_date").datetimepicker({
												//format:"d-m-Y",
												//timepicker:false
											//});
										//});
									</script>';
            foreach ( $transaction_meta as $key => $params ) {
                $html .= wp_dp_create_promotion_orders_fields($key, $params);
            }

            $html .= '</div>
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
            remove_meta_box('mymetabox_revslider_0', 'promotion-orders', 'normal');
        }

    }

    /**
     * End Function  how create add meta boxes of transactions
     */
    return new post_type_promotion_orders();
}