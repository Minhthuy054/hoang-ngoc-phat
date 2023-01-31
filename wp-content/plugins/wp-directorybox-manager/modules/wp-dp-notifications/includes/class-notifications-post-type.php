<?php
/**
 * Create Custom Post Type and it's meta boxes for Listing Alert Notifications
 *
 * @package	Directory Box
 */
// Direct access not allowed.
if ( ! defined('ABSPATH') ) {
    exit;
}

/**
 * WP_Listing_Hunt_Custom_Post_Type class.
 */
if ( ! class_exists('WP_Listing_Hunt_Custom_Post_Type') ) {

    class WP_Listing_Hunt_Custom_Post_Type {

        /**
         * Constructor
         */
        public function __construct() {
            //$this->create_listing_alert_post_type();
            add_action('init', array( $this, 'create_listing_alert_post_type' ), 10);

            // add column 
            add_filter('manage_listing-alert_posts_columns', array( $this, 'wp_dp_listing_alert_columns_add' ));
            add_action('manage_listing-alert_posts_custom_column', array( $this, 'wp_dp_listing_alert_columns' ), 10, 2);



            // Configure meta boxes to be created for Listing Notifications listing type.
            add_action('add_meta_boxes', array( $this, 'wp_dp_add_meta_boxes_to_listing_alerts' ));

            // Handle AJAX to create a listing alert.
            add_action('wp_ajax_wp_dp_create_listing_alert', array( $this, 'create_listing_alert_callback' ));
            add_action('wp_ajax_nopriv_wp_dp_create_listing_alert', array( $this, 'create_listing_alert_callback' ));

            // Handle AJAX to delete a listing alert.
            add_action('wp_ajax_wp_dp_remove_listing_alert', array( $this, 'remove_listing_alert_callback' ));
            add_action('wp_ajax_nopriv_wp_dp_remove_listing_alert', array( $this, 'remove_listing_alert_callback' ));

            // Handle AJAX to delete a listing alert.
            add_action('wp_ajax_wp_dp_unsubscribe_listing_alert', array( $this, 'unsubscribe_listing_alert' ));
            add_action('wp_ajax_nopriv_wp_dp_unsubscribe_listing_alert', array( $this, 'unsubscribe_listing_alert' ));

            add_action('admin_menu', array( $this, 'wp_dp_remove_post_boxes' ));
            add_action('do_meta_boxes', array( $this, 'wp_dp_remove_post_boxes' ));

            // Custom Sort Columns
            add_filter('manage_edit-listing-alert_sortable_columns', array( $this, 'wp_dp_email_alerts_sortable' ));
            add_filter('request', array( $this, 'wp_dp_email_alerts_column_orderby' ));
            // Custom Filter
            add_action('restrict_manage_posts', array( $this, 'wp_dp_admin_email_alerts_filter' ), 11);
            add_filter('parse_query', array( $this, 'wp_dp_admin_email_alerts_filter_query' ), 11, 1);

            // Remove "Add new" button from listing page and admin menu.
            add_action('admin_head', array( $this, 'disable_new_posts_capability_callback' ), 11);

            //remove quick edit
            add_filter('post_row_actions', array( $this, 'remove_quick_edit' ), 10, 1);

            add_action('views_edit-listing-alert', array( $this, 'wp_dp_remove_views' ));
        }

        public function wp_dp_remove_views($views) {
            unset($views['publish']);

            return $views;
        }

        function remove_quick_edit($actions) {
            global $post;
            if ( $post->post_type == 'listing-alert' )
                unset($actions['inline hide-if-no-js']);
            return $actions;
        }

        public function wp_dp_admin_email_alerts_filter() {
            global $wp_dp_form_fields, $post_type;

            //only add filter to post type you want
            if ( $post_type == 'listing-alert' ) {

                $alert_frequencies_options = array(
                    '' => wp_dp_plugin_text_srt('wp_dp_class_noti_post_type_email_frequemcies'),
                    'annually' => wp_dp_plugin_text_srt('wp_dp_notification_post_annually'),
                    'biannually' => wp_dp_plugin_text_srt('wp_dp_notification_post_biannually'),
                    'monthly' => wp_dp_plugin_text_srt('wp_dp_notification_post_monthly'),
                    'fortnightly' => wp_dp_plugin_text_srt('wp_dp_notification_post_fortnightly'),
                    'weekly' => wp_dp_plugin_text_srt('wp_dp_notification_post_weekly'),
                    'daily' => wp_dp_plugin_text_srt('wp_dp_notification_post_daily'),
                    'never' => wp_dp_plugin_text_srt('wp_dp_notification_post_never'),
                );
                $alert_frequency = isset($_GET['alert_frequency']) ? $_GET['alert_frequency'] : '';
                $wp_dp_opt_array = array(
                    'std' => $alert_frequency,
                    'id' => 'alert_frequency',
                    'cust_name' => 'alert_frequency',
                    'extra_atr' => '',
                    'classes' => '',
                    'options' => $alert_frequencies_options,
                    'return' => false,
                );
                $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);
            }
        }

        /**
         * Disable capibility to create new.
         */
        public function disable_new_posts_capability_callback() {
            global $post;

            // Hide link on listing page.
            if ( get_post_type() == 'listing-alert' ) {
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
                    /*.post-type-listing-alert .column-serach_keyword { width:500px !important; overflow:hidden }*/
                    .post-type-listing-alert .column-frequency { width:150px !important; overflow:hidden }
                    .post-type-listing-alert .column-name { width:200px !important; overflow:hidden }
                    .post-type-listing-alert .column-p_date { width:200px !important; overflow:hidden }
                    .post-type-listing-alert .column-alert_action { width:100px !important; overflow:hidden }
                    
                    
                </style>
                <?php
            }
            
            
            
            
        }

        public function wp_dp_admin_email_alerts_filter_query($query) {
            global $pagenow;
            $custom_filter_arr = array();
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'listing-alert' && isset($_GET['alert_frequency']) && $_GET['alert_frequency'] != '' ) {
                $custom_filter_arr[] = array(
                    'key' => 'wp_dp_frequency_' . $_GET['alert_frequency'],
                    'value' => 'on',
                    'compare' => '=',
                );
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'listing-alert' && isset($_GET['m']) && $_GET['m'] != '' && $_GET['m'] != 0 ) {
                $date_query = [ ];
                $date_query[] = array(
                    'year' => substr($_GET['m'], 0, 4),
                    'month' => substr($_GET['m'], 4, 5),
                );
                $query->set('date_query', $date_query);
            }
            if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'listing-alert' && ! empty($custom_filter_arr) ) {
                $query->set('meta_query', $custom_filter_arr);
            }
        }

        public function wp_dp_email_alerts_sortable($columns = array()) {
            if ( ! is_array($columns) ) {
                $columns = array();
            }
            $columns['email'] = 'alert_email';
            $columns['p_date'] = 'p_date';
            $columns['serach_keyword'] = 'alert_serach_keyword';
            return $columns;
        }

        public function wp_dp_email_alerts_column_orderby($vars) {
            if ( isset($vars['orderby']) && 'alert_email' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'wp_dp_email',
                    'orderby' => 'meta_value',
                ));
            }
            if ( isset($vars['orderby']) && 'alert_query' == $vars['orderby'] ) {
                $vars = array_merge($vars, array(
                    'meta_key' => 'wp_dp_query',
                    'orderby' => 'meta_value',
                ));
            }
            return $vars;
        }

        public function wp_dp_listing_alert_columns_add($columns) {

            unset($columns['date']);
            unset($columns['title']);
            $columns['name'] = wp_dp_plugin_text_srt('wp_dp_review_member_name_column');
           // $columns['email'] = wp_dp_plugin_text_srt('wp_dp_notification_post_lao_email');
            $columns['frequency'] = wp_dp_plugin_text_srt('wp_dp_class_noti_post_type_email_frequemcies');
            $columns['serach_keyword'] = wp_dp_plugin_text_srt('wp_dp_class_noti_post_type_keywords_search');
            $columns['p_date'] = wp_dp_plugin_text_srt('wp_dp_member_notift_created_date');
            $columns['alert_action'] = wp_dp_plugin_text_srt('wp_dp_actions');
            return $columns;
        }

        public function wp_dp_listing_alert_columns($name) {
            global $post,$wp_dp_form_fields;

            switch ( $name ) {
                default:
                    //echo "name is " . $name;
                    break;
                case 'alert_action':
                    
                    echo '<a class="alert-delete" href="' . get_delete_post_link($post->ID, '', true) . '">' . wp_dp_plugin_text_srt('wp_dp_listing_delete') . '</a>';
                    edit_post_link( wp_dp_plugin_text_srt('wp_dp_memberlist_edit'), '', '',$post->ID , 'alert-edit' );
                    
                    break;

                case 'name':
                    $publisher_id = get_post_meta($post->ID, 'wp_dp_member', true);
                    if ( isset($publisher_id) && $publisher_id != '' ) {
                        edit_post_link( get_the_title($publisher_id), '', '',$publisher_id , '' );
                    } else {
                        echo '-';
                    }
                    
                    $wp_dp_email = get_post_meta($post->ID, 'wp_dp_email', true);
                    if ( isset($wp_dp_email) && $wp_dp_email != '' ) {
                        $user = get_user_by('email', $wp_dp_email);
                        $user_id = '';
                        if ( ! empty($user) ) {
                            $user_id = $user->ID;
                        }
                        if ( $user_id !== '' ) {
                            echo '<br /><a href="' . esc_url(get_edit_user_link($user_id)) . '">' . esc_html($wp_dp_email) . '</a>';
                        } else {
                            echo esc_html($wp_dp_email);
                        }
                    }
                    
                    
                    
                    break;
                case 'email':
                    $wp_dp_email = get_post_meta($post->ID, 'wp_dp_email', true);
                    if ( isset($wp_dp_email) && $wp_dp_email != '' ) {
                        $user = get_user_by('email', $wp_dp_email);
                        $user_id = '';
                        if ( ! empty($user) ) {
                            $user_id = $user->ID;
                        }
                        if ( $user_id !== '' ) {
                            echo '<a href="' . esc_url(get_edit_user_link($user_id)) . '">' . esc_html($wp_dp_email) . '</a>';
                        } else {
                            echo esc_html($wp_dp_email);
                        }
                    } else {
                        echo '-';
                    }
                    break;
                case 'frequency':
                    $wp_dp_frequency_annually = get_post_meta($post->ID, 'wp_dp_frequency_annually', true);
                    $wp_dp_frequency_biannually = get_post_meta($post->ID, 'wp_dp_frequency_biannually', true);
                    $wp_dp_frequency_monthly = get_post_meta($post->ID, 'wp_dp_frequency_monthly', true);
                    $wp_dp_frequency_fortnightly = get_post_meta($post->ID, 'wp_dp_frequency_fortnightly', true);
                    $wp_dp_frequency_weekly = get_post_meta($post->ID, 'wp_dp_frequency_weekly', true);
                    $wp_dp_frequency_daily = get_post_meta($post->ID, 'wp_dp_frequency_daily', true);
                    $wp_dp_frequency_never = get_post_meta($post->ID, 'wp_dp_frequency_never', true);
                    $frequency_array = array();
                    if ( isset($wp_dp_frequency_annually) && $wp_dp_frequency_annually == 'on' ) {
                        $frequency_array[] = wp_dp_plugin_text_srt('wp_dp_notification_post_annually');
                    }
                    if ( isset($wp_dp_frequency_biannually) && $wp_dp_frequency_biannually == 'on' ) {
                        $frequency_array[] = wp_dp_plugin_text_srt('wp_dp_notification_post_biannually');
                    }
                    if ( isset($wp_dp_frequency_monthly) && $wp_dp_frequency_monthly == 'on' ) {
                        $frequency_array[] = wp_dp_plugin_text_srt('wp_dp_notification_post_monthly');
                    }
                    if ( isset($wp_dp_frequency_fortnightly) && $wp_dp_frequency_fortnightly == 'on' ) {
                        $frequency_array[] = wp_dp_plugin_text_srt('wp_dp_notification_post_fortnightly');
                    }
                    if ( isset($wp_dp_frequency_weekly) && $wp_dp_frequency_weekly == 'on' ) {
                        $frequency_array[] = wp_dp_plugin_text_srt('wp_dp_notification_post_weekly');
                    }
                    if ( isset($wp_dp_frequency_daily) && $wp_dp_frequency_daily == 'on' ) {
                        $frequency_array[] = wp_dp_plugin_text_srt('wp_dp_notification_post_daily');
                    }
                    if ( isset($wp_dp_frequency_never) && $wp_dp_frequency_never == 'on' ) {
                        $frequency_array[] = wp_dp_plugin_text_srt('wp_dp_notification_post_never');
                    }
                    echo esc_html(implode(", ", $frequency_array));
                    break;
                case 'serach_keyword':
                    $wp_dp_query = get_post_meta($post->ID, 'wp_dp_query', true);
                    $search_keywords = WP_Listing_Hunt_Alert_Helpers::query_to_array($wp_dp_query);
                    $all_words_search = array();
                    foreach ( $search_keywords as $key => $value ) {
                        if ( 'ajax_filter' == $key || 'advanced_search' == $key || 'listing_arg' == $key || 'action' == $key || 'alert-frequency' == $key || 'alerts-name' == $key || 'loc_polygon' == $key || 'alerts-email' == $key || 'loc_polygon_path' == $key ) {
                            continue;
                        }
                        $key = str_replace("wp_dp_wp_dp_", "", $key);
                        $key = str_replace("_", " ", $key);
                        $all_words_search[] = ucfirst($key) . ' : ' . $value . ' ';
                    }
                    $all_search_words = implode(', ', array_values($all_words_search));
                    if ( $all_search_words != '' ) {
                        echo esc_html($all_search_words);
                    } else {
                        echo wp_dp_plugin_text_srt('wp_dp_alerts_all_listings');
                    }
                    break;
                case 'p_date':
                    echo get_the_date('F j, Y', $post->ID);
                    break;
            }
        }

        /**
         * Register Custom Post Type for Listing Notifications
         */
        public function create_listing_alert_post_type() {
            // Check if post type already exists then don't register
            if ( post_type_exists("listing_hunt_notification") ) {
                return;
            }
            $labels = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_notification_post_name'),
                'singular_name' => wp_dp_plugin_text_srt('wp_dp_notification_post_singular_name'),
                'menu_name' => wp_dp_plugin_text_srt('wp_dp_notification_post_menu_name'),
                'name_admin_bar' => wp_dp_plugin_text_srt('wp_dp_notification_post_name_admin'),
                'add_new' => wp_dp_plugin_text_srt('wp_dp_notification_post_add_new'),
                'add_new_item' => wp_dp_plugin_text_srt('wp_dp_notification_post_add_new_item'),
                'new_item' => wp_dp_plugin_text_srt('wp_dp_notification_post_new_item'),
                'edit_item' => wp_dp_plugin_text_srt('wp_dp_notification_post_edit_item'),
                'view_item' => wp_dp_plugin_text_srt('wp_dp_notification_post_view_item'),
                'all_items' => wp_dp_plugin_text_srt('wp_dp_notification_post_all_item'),
                'search_items' => wp_dp_plugin_text_srt('wp_dp_notification_post_search_item'),
                'parent_item_colon' => wp_dp_plugin_text_srt('wp_dp_notification_post_parent_clone'),
                'not_found' => wp_dp_plugin_text_srt('wp_dp_notification_post_not_found'),
                'not_found_in_trash' => wp_dp_plugin_text_srt('wp_dp_notification_post_not_found_trash'),
            );

            $args = array(
                'labels' => $labels,
                'description' => wp_dp_plugin_text_srt('wp_dp_notification_post_description'),
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => 'edit.php?post_type=dp-templates',
                'query_var' => true,
                'capability_type' => 'post',
                'publicly_queryable' => false,
                'exclude_from_search' => true,
                'hierarchical' => false,
                'rewrite' => array( 'slug' => 'listing-alert' ),
                'supports' => false,
                'has_archive' => false,
                'capabilities' => array(
                    'create_posts' => 'do_not_allow', // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
                ),
                'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
            );

            // Register custom post type.
            register_post_type("listing-alert", $args);
        }

        /**
         * Add meta boxes for Custom post type Listing Alerts
         */
        public function wp_dp_add_meta_boxes_to_listing_alerts() {
            add_meta_box('wp_dp_meta_listings', wp_dp_plugin_text_srt('wp_dp_notification_post_listing_alerts_options'), array( $this, 'wp_dp_create_meta_boxes_to_listing_alerts' ), 'listing-alert', 'normal', 'high');
        }

        public function wp_dp_create_meta_boxes_to_listing_alerts() {
            global $post;
            ?>
            <div class="page-wrap page-opts left">
                <div class="option-sec" style="margin-bottom:0;">
                    <div class="opt-conts">
                        <div class="elementhidden">
                            <?php $this->wp_dp_listing_alert_options(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <?php
        }

        public function wp_dp_listing_alert_options() {
            global $post, $wp_dp_html_fields;
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_notification_post_lao_email'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => get_post_meta($post->ID, 'wp_dp_email', true),
                    'id' => 'email',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_notification_post_lao_name'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => get_post_meta($post->ID, 'wp_dp_name', true),
                    'id' => 'name',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_notification_post_query'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => get_post_meta($post->ID, 'wp_dp_query', true),
                    'id' => 'query',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_notification_post_complete_url'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => get_post_meta($post->ID, 'wp_dp_complete_url', true),
                    'id' => 'complete_url',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);


            $on_off_option = array( 'yes' => wp_dp_plugin_text_srt('wp_dp_notification_post_options_yes'), 'no' => wp_dp_plugin_text_srt('wp_dp_notification_post_options_no') );
            $wp_dp_opt_array = array(
                "name" => wp_dp_plugin_text_srt('wp_dp_notification_post_annually'),
                "desc" => "",
                "hint_text" => wp_dp_plugin_text_srt('wp_dp_notification_post_annually_hint'),
                'echo' => true,
                "options" => $on_off_option,
                'field_params' => array(
                    'std' => get_post_meta($post->ID, 'wp_dp_frequency_annually', true),
                    'id' => 'frequency_annually',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
            $wp_dp_opt_array = array(
                "name" => wp_dp_plugin_text_srt('wp_dp_notification_post_biannually'),
                "desc" => "",
                "hint_text" => wp_dp_plugin_text_srt('wp_dp_notification_post_biannually_hint'),
                'echo' => true,
                "options" => $on_off_option,
                'field_params' => array(
                    'std' => get_post_meta($post->ID, 'wp_dp_frequency_biannually', true),
                    'id' => 'frequency_biannually',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
            $wp_dp_opt_array = array(
                "name" => wp_dp_plugin_text_srt('wp_dp_notification_post_monthly'),
                "desc" => "",
                "hint_text" => wp_dp_plugin_text_srt('wp_dp_notification_post_monthly_hint'),
                'echo' => true,
                "options" => $on_off_option,
                'field_params' => array(
                    'std' => get_post_meta($post->ID, 'wp_dp_frequency_monthly', true),
                    'id' => 'frequency_monthly',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
            $wp_dp_opt_array = array(
                "name" => wp_dp_plugin_text_srt('wp_dp_notification_post_fortnightly'),
                "desc" => "",
                "hint_text" => wp_dp_plugin_text_srt('wp_dp_notification_post_fortnightly_hint'),
                'echo' => true,
                "options" => $on_off_option,
                'field_params' => array(
                    'std' => get_post_meta($post->ID, 'wp_dp_frequency_fortnightly', true),
                    'id' => 'frequency_fortnightly',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
            $wp_dp_opt_array = array(
                "name" => wp_dp_plugin_text_srt('wp_dp_notification_post_weekly'),
                "desc" => "",
                "hint_text" => wp_dp_plugin_text_srt('wp_dp_notification_post_weekly_hint'),
                'echo' => true,
                "options" => $on_off_option,
                'field_params' => array(
                    'std' => get_post_meta($post->ID, 'wp_dp_frequency_weekly', true),
                    'id' => 'frequency_weekly',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
            $wp_dp_opt_array = array(
                "name" => wp_dp_plugin_text_srt('wp_dp_notification_post_daily'),
                "desc" => "",
                "hint_text" => wp_dp_plugin_text_srt('wp_dp_notification_post_daily_hint'),
                'echo' => true,
                "options" => $on_off_option,
                'field_params' => array(
                    'std' => get_post_meta($post->ID, 'wp_dp_frequency_daily', true),
                    'id' => 'frequency_daily',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
            $wp_dp_opt_array = array(
                "name" => wp_dp_plugin_text_srt('wp_dp_notification_post_never'),
                "desc" => "",
                "hint_text" => wp_dp_plugin_text_srt('wp_dp_notification_post_never_hint'),
                'echo' => true,
                "options" => $on_off_option,
                'field_params' => array(
                    'std' => get_post_meta($post->ID, 'wp_dp_frequency_never', true),
                    'id' => 'frequency_never',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
        }

        public function create_listing_alert_callback() {
            check_ajax_referer('wp_dp_create_listing_alert', 'security');

            // Read data from user input.
            $email = sanitize_text_field($_POST['email']);
            $name = sanitize_text_field($_POST['name']);
            $location = sanitize_text_field($_POST['location']);
            $complete_url = $location;  // for permalink
            $query = end(explode('?', $location));
            $frequency = sanitize_text_field($_POST['frequency']);
            if ( empty($name) ) {
                $return = array(
                    'type' => 'error',
                    'msg' => wp_dp_plugin_text_srt('wp_dp_notification_post_title_not_empty'),
                );
                echo json_encode($return);
                wp_die();
            }
            if ( empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL) ) {
                $return = array(
                    'type' => 'error',
                    'msg' => wp_dp_plugin_text_srt('wp_dp_notification_post_valid_email'),
                );
                echo json_encode($return);
                wp_die();
            }
            $meta_query = array(
                array(
                    'key' => 'wp_dp_email',
                    'value' => $email,
                    'compare' => '=',
                ),
                array(
                    'key' => 'wp_dp_frequency_' . $frequency,
                    'value' => 'on',
                    'compare' => '=',
                ),
            );

            if ( $query != '' ) {
                $meta_query[] = array(
                    'key' => 'wp_dp_query',
                    'value' => $query,
                    'compare' => '=',
                );
            }
            $args = array(
                'post_type' => 'listing-alert',
                'meta_query' => $meta_query,
            );
            $obj_query = new WP_Query($args);
            $count = $obj_query->post_count;
            if ( $count > 0 ) {
                $return = array(
                    'type' => 'error',
                    'msg' => wp_dp_plugin_text_srt('wp_dp_notification_post_laready_exists'),
                );
            } else {
                // Insert Listing Alert as a post.
                $listing_alert_data = array(
                    'post_title' => $name,
                    'post_status' => 'publish',
                    'post_type' => 'listing-alert',
                    'comment_status' => 'closed',
                    'post_author' => get_current_user_id(),
                );
                $listing_alert_id = wp_insert_post($listing_alert_data);
                // Update email.
                update_post_meta($listing_alert_id, 'wp_dp_email', $email);
                // Update name.
                update_post_meta($listing_alert_id, 'wp_dp_name', $name);
                // Update member.
                $member_id = get_user_meta(get_current_user_id(), 'wp_dp_company', true);
                update_post_meta($listing_alert_id, 'wp_dp_member', $member_id);

                // Update frequencies.

                $frequencies = array(
                    'annually',
                    'biannually',
                    'monthly',
                    'fortnightly',
                    'weekly',
                    'daily',
                    'never',
                );
                $selected_frequencies = explode(',', $frequency);
                foreach ( $selected_frequencies as $key => $frequency ) {
                    if ( in_array($frequency, $frequencies) ) {
                        update_post_meta($listing_alert_id, 'wp_dp_frequency_' . $frequency, 'on');
                    }
                }
                // Update query.
                update_post_meta($listing_alert_id, 'wp_dp_query', $query);
                // complete url 
                update_post_meta($listing_alert_id, 'wp_dp_complete_url', $complete_url);
                // Last time email sent.
                update_post_meta($listing_alert_id, 'wp_dp_last_time_email_sent', 0);
                $return = array(
                    'type' => 'success',
                    'msg' => wp_dp_plugin_text_srt('wp_dp_notification_post_successfully_added'),
                );
            }
            echo json_encode($return);
            wp_die();
        }

        public function unsubscribe_listing_alert() {
            $listing_alert_id = sanitize_text_field($_REQUEST['jaid']);
            $post_data = get_post($listing_alert_id);
            if ( $post_data ) {
                wp_delete_post($listing_alert_id);
                echo '<div class="listing_alert_unsubscribe_msg" style="text-align: center;"><h3>' . wp_dp_plugin_text_srt('wp_dp_notification_post_successfully_unsubcribe') . '</h3></div>';
            } else {
                echo '<div class="listing_alert_unsubscribe_msg" style="text-align: center;"><h3>' . wp_dp_plugin_text_srt('wp_dp_notification_post_already_subcribe') . '</h3></div>';
            }
            die();
        }

        public function remove_listing_alert_callback() {
            $status = 0;
            $msg = '';
            if ( isset($_POST['post_id']) ) {
                wp_delete_post($_POST['post_id']);
                $status = 1;
                $msg = wp_dp_plugin_text_srt('wp_dp_notification_post_successfully_deleted');
            } else {
                $msg = wp_dp_plugin_text_srt('wp_dp_notification_post_provided_data_incomplete');
                $status = 0;
            }
            echo json_encode(array( "msg" => $msg, 'status' => $status ));
            wp_die();
        }

        //remove extra boxes
        public function wp_dp_remove_post_boxes() {
            remove_meta_box('mymetabox_revslider_0', 'listing-alert', 'normal');
        }

    }

    new WP_Listing_Hunt_Custom_Post_Type();
}