<?php

/**
 * Claims / Flags Listing Module
 */
// Direct access not allowed.
if ( ! defined('ABSPATH') ) {
    exit;
}

if ( ! class_exists('Wp_Dp_Claims') ) {

    class Wp_Dp_Claims {

        /**
         * Start construct Functions
         */
        public function __construct() {
            // Define constants
            define('WP_DP_CLAIMS_PLUGIN_URL', WP_PLUGIN_URL . '/wp-directorybox-manager/modules/wp-dp-claims');
            define('WP_DP_CLAIMS_CORE_DIR', WP_PLUGIN_DIR . '/wp-directorybox-manager/modules/wp-dp-claims');

            add_filter('wp_dp_plugin_text_strings', array( $this, 'wp_dp_claims_strings_callback' ), 1);

            add_filter('manage_wp_dp_claims_posts_columns', array( $this, 'wp_dp_claims_cpt_columns' ));
            add_action('manage_wp_dp_claims_posts_custom_column', array( $this, 'custom_wp_dp_claims_column' ), 10, 2);
            add_filter('manage_edit-wp_dp_claims_sortable_columns', array( $this, 'wp_dp_sortable_wp_dp_claims_column' ));
            add_action('pre_get_posts', array( $this, 'wp_dp_type_orderby' ));
            add_action('request', array( $this, 'wp_dp_claims_column_sortable_orderby' ));

            add_filter('get_sample_permalink_html', array( $this, 'wp_dp_hide_permalinks' ));

            add_action('views_edit-wp_dp_claims', array( $this, 'wp_dp_remove_views' ));
            add_filter('bulk_actions-edit-wp_dp_claims', array( $this, 'wp_dp_remove_bulk_actions' ));
            
             add_filter('handle_bulk_actions-edit-wp_dp_claims', array( $this, 'my_bulk_action_handler' ), 10, 3);
            
            // Remove "Add new" button from listing page and admin menu.
            add_action('admin_head', array( $this, 'disable_new_posts_capability_callback' ), 11);

            add_action('wp_ajax_wp_dp_claim_update_action', array( $this, 'wp_dp_claim_update_action' ));
            add_action('wp_ajax_nopriv_wp_dp_claim_update_action', array( $this, 'wp_dp_claim_update_action' ));

            //remove edit link from post title
            add_action('admin_footer-edit.php', array( $this, 'wp_dp_remove_edit_link' ));

            
            if ( is_admin() ) {
                add_filter('post_row_actions', array( $this, 'remove_quick_edit' ), 10, 2);
                add_action('restrict_manage_posts', array( $this, 'wp_dp_admin_claims_flags_type_filters' ), 11);
                add_filter('parse_query', array( &$this, 'wp_dp_admin_claims_filter' ), 11, 1);
            }

            $this->includes();

            // Initialize Addon
            add_action('init', array( $this, 'init' ));
            
            add_filter('views_edit-wp_dp_claims', array( $this, 'show_claims_status_dashboard_list') );
            add_filter( 'views_edit-wp_dp_claims',array( $this,  'remove_total_views') );
        }
        
        public function show_claims_status_dashboard_list($views){
			
			
			
            $total_claims = wp_count_posts('wp_dp_claims');
            remove_filter('parse_query', array( &$this, 'wp_dp_admin_claims_filter' ), 11, 1);
            $args_approved = array(
                'post_type' => 'wp_dp_claims',
                'posts_per_page' => 1,
                'fields' => 'ids',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_claim_action',
                        'value' => 'resolved',
                        'compare' => 'LIKE',
                    ),
                ),
            );
			
			
			
            $query_approved = new WP_Query($args_approved);
            $count_claims_resolved = $query_approved->found_posts;
            // end claims resolved count

            $args_pending = array(
                'post_type' => 'wp_dp_claims',
                'posts_per_page' => 1,
                'fields' => 'ids',
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => 'wp_dp_claim_action',
                        'value' => 'pending',
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key' => 'wp_dp_claim_action',
                        'value' => '',
                        'compare' => 'NOT EXISTS',
                    ),
                ),
            );
            $query_pending = new WP_Query($args_pending);
            $count_claims_pending = $query_pending->found_posts;
            // end claims pending count



            wp_reset_postdata();

            echo '
            <ul class="total-wp-dp-listing row">
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_claims_total') . ' </strong><em>' . $total_claims->publish  . '</em><i class="icon-ticket"></i></div></li>
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_claims_resolved') . ' </strong><em>' . $count_claims_resolved . '</em><i class="icon-check_circle"></i></div></li>
                <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="wp-dp-text-holder"><strong>' . wp_dp_plugin_text_srt('wp_dp_claims_pending') . ' </strong><em>' . $count_claims_pending . '</em><i class="icon-back-in-time"></i></div></li>
            </ul>
            ';
			
			
			echo "<pre>";
			print_r($views['all']);
			echo "</pre>";
//            die('die here');
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
        public function my_bulk_action_handler($redirect_to, $action_name, $post_ids) {
            if ( 'delete' === $action_name ) {
                foreach ( $post_ids as $post_id ) {
                    $post = get_post($post_id);
                    get_delete_post_link($post, '', true);
                }
                $redirect_to = add_query_arg('bulk_posts_processed', count($post_ids), $redirect_to);
                return $redirect_to;
            } else {
                return $redirect_to;
            }
        }
        
        public function wp_dp_remove_views($views) {
            unset($views['mine']);
            unset($views['publish']);
            unset($views['trash']);
            return $views;
        }

        public function wp_dp_remove_bulk_actions($actions) {
            unset($actions['edit']);
            unset($actions['trash']);
            
            $actions['delete'] = wp_dp_plugin_text_srt('wp_dp_listing_delete');
            
            return $actions;
        }

        public function wp_dp_remove_edit_link() {
            if ( get_post_type() == 'wp_dp_claims' ) {
                ?>
                <script type="text/javascript">
                    jQuery('table.wp-list-table a.row-title').contents().unwrap();
                </script>
                <?php
            }
        }

        /**
         * Disable capibility to create new.
         */
        public function disable_new_posts_capability_callback() {
            global $post;

            // Hide link on listing page.
            if ( get_post_type() == 'wp_dp_claims' ) {
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

        public function wp_dp_claims_strings_callback($wp_dp_static_text = array()) {
            global $wp_dp_static_text;
            $wp_dp_static_text['wp_dp_claims_name'] = esc_html__('Claims/Flags Options ', 'wp-dp');
            $wp_dp_static_text['wp_dp_claims_sure_message'] = esc_html__('Are you sure to do this?', 'wp-dp');
            $wp_dp_static_text['wp_dp_claims_desc'] = esc_html__('Post type for claim and flag listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_id'] = esc_html__('Claim ID', 'wp-dp');
            $wp_dp_static_text['wp_dp_flag_id'] = esc_html__('Flag ID', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_reference_id'] = esc_html__('reference id of claim', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_on'] = esc_html__('Claim On', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_on_desc'] = esc_html__('Claim from which listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_user_name'] = esc_html__('User Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_user_name_desc'] = esc_html__('Claimer Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_user_email'] = esc_html__('User Email', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_user_email_desc'] = esc_html__('Claimer Email', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_reason'] = esc_html__('Reason', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_report_reason'] = esc_html__('Report Reason', 'wp-dp');
            
            $wp_dp_static_text['wp_dp_claim_reason_hint'] = esc_html__('Claim Reason', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_Type'] = esc_html__('Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_reprt_Type'] = esc_html__('Report Type', 'wp-dp');
            
            
            
            $wp_dp_static_text['wp_dp_select_claim_type'] = esc_html__('Select Report Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_Type_hint'] = esc_html__('Claim Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_action'] = esc_html__('Actions', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_action_hint'] = esc_html__('Claim Actions', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_listings'] = esc_html__('Claim Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_flag_listings'] = esc_html__('Flag Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_listing'] = esc_html__('Claim Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_flag_listing'] = esc_html__('Flag Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim'] = esc_html__('Claim', 'wp-dp');
            $wp_dp_static_text['wp_dp_flag'] = esc_html__('Flag', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_status_pending'] = esc_html__('Pending', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_status_resolved'] = esc_html__('Resolved', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_status_delete'] = esc_html__('Delete', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_user_name_error'] = esc_html__('Name is Required', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_email_error'] = esc_html__('Email is required', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_reason_error'] = esc_html__('Reason is required', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_list_success'] = esc_html__('Posted successfully', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_flag_send'] = esc_html__('Send', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_type'] = esc_html__('Listing Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_listing_title'] = esc_html__('Listing Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_listing_titlee'] = esc_html__('Listing Name', 'wp-dp');
            
            $wp_dp_static_text['wp_dp_claim_claimed_by'] = esc_html__('Claimed By', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_report_claimed_by'] = esc_html__('Report By', 'wp-dp');
            
            $wp_dp_static_text['wp_dp_claim_actions'] = esc_html__('Actions', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_are_you_sure'] = esc_html__('Are you sure to do this?', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_flags'] = esc_html__('Claims / Flags', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_flags_desc'] = esc_html__('Post type for claim and flag listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_name'] = esc_html__('Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_email'] = esc_html__('Email Address', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_action_success'] = esc_html__('status changed.', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_action_error'] = esc_html__('error occured.', 'wp-dp');

            return $wp_dp_static_text;
        }

        public function wp_dp_claims_cpt_columns($columns) {

            unset($columns['date']);
            unset($columns['title']);
            
            $columns['title_listing'] = wp_dp_plugin_text_srt('wp_dp_claim_listing_titlee');

            $new_columns = array(
                'type' => wp_dp_plugin_text_srt('wp_dp_claim_reprt_Type'),
                'reason' => wp_dp_plugin_text_srt('wp_dp_claim_report_reason'),
                'claimedby' => wp_dp_plugin_text_srt('wp_dp_claim_report_claimed_by'),
                'p_date' => wp_dp_plugin_text_srt('wp_dp_claims_date_Col'),
                'actions' => wp_dp_plugin_text_srt('wp_dp_claims_status_Col'),
            );
            return array_merge($columns, $new_columns);
        }

        function wp_dp_type_orderby($query) {
            if ( ! is_admin() )
                return;

            $orderby = $query->get('orderby');

            if ( 'type' == $orderby ) {
                $query->set('meta_key', 'wp_dp_claim_type');
                $query->set('orderby', 'meta_value');
            }
        }

        public function custom_wp_dp_claims_column($column) {
            $post_id = get_the_id();
            switch ( $column ) {
                case 'title_listing' :
                     $wp_dp_claim_listing_id = get_post_meta($post_id, 'wp_dp_claim_listing_id', true);
                     $wp_dp_claim_listing_id = isset($wp_dp_claim_listing_id) ? $wp_dp_claim_listing_id :$post_id;     
                     echo '<a href="' . get_edit_post_link($wp_dp_claim_listing_id) . '">  ' . get_the_title($post_id) . ' </a>';
                    break;
                case 'type' :
                    $post_type = get_post_meta($post_id, 'wp_dp_claim_type', true);
                    echo ($post_type);
                    break;
                case 'reason':
                    $wp_dp_claimer_reason = get_post_meta($post_id, 'wp_dp_claimer_reason', true);
                    echo ($wp_dp_claimer_reason);
                    break;

                case 'actions':
                    global $wp_dp_form_fields;

                    $wp_dp_claim_action = get_post_meta($post_id, 'wp_dp_claim_action', true);
                    $actions = array( 'pending' => wp_dp_plugin_text_srt('wp_dp_claim_status_pending'), 'resolved' => wp_dp_plugin_text_srt('wp_dp_claim_status_resolved'), );

                    $wp_dp_opt_array = array(
                        'std' => $wp_dp_claim_action,
                        'id' => 'claim_action_' . $post_id,
                        'classes' => '',
                        'options' => $actions,
                        'extra_atr' => 'onchange="wp_dp_claim_action_change(this.value, \'' . $post_id . '\')"'
                    );
                    $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);

                    echo '<div class="wp_dp_claim_action_' . $post_id . ' enquiry-status-loader"></div>';
                    break;

                case 'claimedby':
                    $wp_dp_claimer_name = get_post_meta($post_id, 'wp_dp_claimer_name', true);
                    echo ($wp_dp_claimer_name);
                    break;
                
                case 'p_date':
                    echo get_the_date('F j, Y', $post_id);
                    break;
            }
        }

        public function wp_dp_sortable_wp_dp_claims_column($columns) {
            $columns['type'] = 'type';
            $columns['claimedby'] = 'claimed_by';
            $columns['actions'] = 'action';
            $columns['p_date'] = 'p_date';

            return $columns;
        }

        public function wp_dp_claims_column_sortable_orderby($vars) {

            if ( isset($vars['orderby']) && 'claimed_by' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'wp_dp_claimer_name',
                    'orderby' => 'meta_value',
                ));
            }
            if ( isset($vars['orderby']) && 'action' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'wp_dp_claim_action',
                    'orderby' => 'meta_value',
                ));
            }

            return $vars;
        }

        /**
         * Initialize application, load text domain, enqueue scripts and bind hooks
         */
        public function init() {
            // Enqueue JS

            wp_enqueue_script('wp_dp-claims-script', esc_url(WP_DP_CLAIMS_PLUGIN_URL . '/assets/js/functions.js'), '', '', true);
            wp_localize_script('wp_dp-claims-script', 'wp_dp_claims', array(
                'admin_url' => esc_url(admin_url('admin-ajax.php')),
                'confirm_msg' => wp_dp_plugin_text_srt('wp_dp_claim_are_you_sure')
            ));
            $args = array(
                'label' => wp_dp_plugin_text_srt('wp_dp_claim_flags'),
                'description' => wp_dp_plugin_text_srt('wp_dp_claim_flags_desc'),
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => 'edit.php?post_type=listings',
                'menu_position' => true,
                'supports' => array( 'title' ),
                'exclude_from_search' => true,
            );
            register_post_type('wp_dp_claims', $args);
        }

        public function includes() {
            require_once 'frontend/class-claim-listing.php';
            require_once 'frontend/class-flag-listing.php';
            if ( is_admin() ) {
                require_once 'backend/meta-box/class-claims-meta.php';
            }
        }

        public function wp_dp_hide_permalinks($out) {
            global $post;
            if ( $post->post_type == 'wp_dp_claims' )
                $out = '';
            return $out;
        }

        public function remove_quick_edit($actions) {
            global $post;
            if ( $post->post_type == 'wp_dp_claims' ) {
                unset($actions['inline hide-if-no-js']);
                unset($actions['view']);
                unset($actions['edit']);
                unset($actions['trash']);
                $actions['deletee'] = '<span class="trash"><a class="submitdelete" href="' . get_delete_post_link($post->ID, '', true) . '">' . wp_dp_plugin_text_srt('wp_dp_listing_delete') . '</a></span>';
                
            }
            return $actions;
        }

        /*
         * add on strings
         */

        public function wp_dp_admin_claims_flags_type_filters() {
            global $wp_dp_form_fields, $post_type;

            //only add filter to post type you want
            if ( $post_type == 'wp_dp_claims' ) {

                $selected_type = isset($_GET['type']) ? $_GET['type'] : '';
                $type = array( '' => wp_dp_plugin_text_srt('wp_dp_select_claim_type'), 'flag' => wp_dp_plugin_text_srt('wp_dp_claim_flag_listings'), 'claim' => wp_dp_plugin_text_srt('wp_dp_claim_listings') );
                $wp_dp_opt_array = array(
                    'std' => $selected_type,
                    'id' => 'type',
                    'cust_id' => 'type',
                    'cust_name' => 'type',
                    'extra_atr' => '',
                    'classes' => '',
                    'options' => $type,
                    'return' => false,
                );
                $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);
                
                $selected_status = isset($_GET['claim_status']) ? $_GET['claim_status'] : '';
                $status_opts = array( 
                    '' => wp_dp_plugin_text_srt('wp_dp_claim_select_status'), 
                    'pending' => wp_dp_plugin_text_srt('wp_dp_claime_filter_pending'),
                    'resolved' => wp_dp_plugin_text_srt('wp_dp_claime_filter_resolved')
                );
                $wp_dp_opt_array = array(
                    'std' => $selected_status,
                    'id' => 'claim_status',
                    'cust_id' => 'claim_status',
                    'cust_name' => 'claim_status',
                    'extra_atr' => '',
                    'classes' => '',
                    'options' => $status_opts,
                    'return' => false,
                );
                $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);
            }
        }

        function wp_dp_admin_claims_filter($query) {
            global $pagenow;
            $custom_filter_arr = array();
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'wp_dp_claims' && isset($_GET['type']) && $_GET['type'] != '' ) {

                $custom_filter_arr[] = array(
                    'key' => 'wp_dp_claim_type',
                    'value' => $_GET['type'],
                    'compare' => '=',
                );
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'wp_dp_claims' && isset($_GET['status']) && $_GET['status'] != '' ) {

                $custom_filter_arr[] = array(
                    'key' => 'wp_dp_claim_action',
                    'value' => $_GET['claim_status'],
                    'compare' => '=',
                );
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'wp_dp_claims' && isset($_GET['m']) && $_GET['m'] != '' && $_GET['m'] != 0) {
                $date_query = [];
                $date_query[] = array(
                    'year'  => substr($_GET['m'],0,4),
                    'month' => substr($_GET['m'],4,5),
                );
                $query->set('date_query', $date_query);
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'wp_dp_claims' && ! empty($custom_filter_arr) ) {
                $query->set('meta_query', $custom_filter_arr);
            }
        }

        public function wp_dp_claim_update_action() {

            if ( isset($_REQUEST) ) {
                $claim_action = $_REQUEST['claim_action'];
                $post_id = $_REQUEST['post_id'];

                if ( ! empty($post_id) && ! empty($claim_action) ) {
                    update_post_meta($post_id, 'wp_dp_claim_action', $claim_action);
                    $msg = wp_dp_plugin_text_srt('wp_dp_claim_action_success');
                } else {
                    $msg = wp_dp_plugin_text_srt('wp_dp_claim_action_error');
                }
            }
            echo json_encode(array( 'msg' => $msg ));
            wp_die();
        }

    }

    global $wp_dp_claims;
    $wp_dp_claims = new Wp_Dp_Claims();
}