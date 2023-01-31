<?php
/**
 * File Type: Listings Shortcode Frontend
 */
if ( ! class_exists('Wp_dp_Shortcode_Split_Map_Frontend') ) {

    class Wp_dp_Shortcode_Split_Map_Frontend {

        /**
         * Constant variables
         */
        var $PREFIX = 'wp_dp_split_map';
        var $FOOTER_CLASS = '';

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_shortcode($this->PREFIX, array( $this, 'wp_dp_split_map_shortcode_callback' ));
            add_action('wp_ajax_wp_dp_split_map_content', array( $this, 'wp_dp_split_map_content' ));
            add_action('wp_ajax_nopriv_wp_dp_split_map_content', array( $this, 'wp_dp_split_map_content' ));
            add_action('wp_ajax_wp_dp_listing_view_switch', array( $this, 'wp_dp_listing_view_switch' ), 11, 1);
            add_action('wp_ajax_nopriv_wp_dp_listing_view_switch', array( $this, 'wp_dp_listing_view_switch' ), 11, 1);
            add_action('wp_dp_listing_pagination', array( $this, 'wp_dp_listing_pagination_callback' ), 11, 1);
        }

        /*
         * Shortcode View on Frontend
         */

        public function wp_dp_split_map_shortcode_callback($atts, $content = "") {
            wp_enqueue_style('flexslider');
            wp_enqueue_script('flexslider');
            wp_enqueue_script('flexslider-mousewheel');
            wp_enqueue_script('wp-dp-prettyPhoto');
            wp_enqueue_style('wp-dp-prettyPhoto');
            wp_enqueue_script('wp-dp-bootstrap-slider');
            wp_enqueue_script('wp-dp-matchHeight-script');
            if (!wp_script_is( 'wp-dp-google-map-api', 'enqueued' )) {
                wp_enqueue_script('wp-dp-google-map-api');
            }
            
            $icons_groups = get_option('cs_icons_groups');
            if ( ! empty($icons_groups) ) {
                foreach ( $icons_groups as $icon_key => $icon_obj ) {
                    if ( isset($icon_obj['status']) && $icon_obj['status'] == 'on' ) {
                        wp_enqueue_style('cs_icons_data_css_' . $icon_key, $icon_obj['url'] . '/style.css');
                    }
                }
            }
            $listing_short_counter = isset($atts['listing_counter']) && $atts['listing_counter'] != '' ? ( $atts['listing_counter'] ) : rand(123, 9999); // for shortcode counter
            $wp_dp_map_position = isset($atts['wp_dp_map_position']) && $atts['wp_dp_map_position'] != '' ? ( $atts['wp_dp_map_position'] ) : 'right';
            $wp_dp_map_fixed = ( isset($atts['wp_dp_map_fixed']) && $atts['wp_dp_map_fixed'] == 'yes' ) ? ' split-map-fixed' : '';
            wp_enqueue_script('wp_dp_map_style_js');
            wp_enqueue_script('wp-dp-split-map');
            wp_enqueue_script('wp-dp-listing-functions');
            if ( false === ( $listing_view = wp_dp_get_transient_obj('wp_dp_listing_view' . $listing_short_counter) ) ) {
                $listing_view = isset($atts['listing_view']) ? $atts['listing_view'] : '';
            }

            wp_dp_set_transient_obj('wp_dp_listing_view' . $listing_short_counter, $listing_view);
            $listing_map_counter = rand(10000000, 99999999);
            $element_listing_footer = isset($atts['listing_footer']) ? $atts['listing_footer'] : '';
            $element_listing_map_position = isset($atts['listing_map_position']) ? $atts['listing_map_position'] : '';
            ob_start();
            $page_element_size = isset($atts['wp_dp_split_map_element_size']) ? $atts['wp_dp_split_map_element_size'] : 100;
            if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
                echo '<div class="' . wp_dp_cs_var_page_builder_element_sizes($page_element_size, $atts) . ' ">';
            }
            $map_change_class = '';
            if ( $listing_view == 'map' ) {
                if ( $element_listing_footer == 'yes' ) {
                    echo '<script>';
                    echo 'jQuery(document).ready(function () {'
                    . 'jQuery("footer#footer").hide();'
                    . '});';
                    echo '</script>';
                }
            }

            wp_reset_query();
            do_action('listing_checks_enquire_lists_submit');
            do_action('wp_dp_listing_compare_sidebar');
            do_action('wp_dp_listing_enquiries_sidebar');
            ?> 
            <!-- start quick view popup -->
            <div class="modal fade quick-view-listing" id="quick-listing" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="icon-close"></i></span></button>
                        </div>
                        <div class="modal-body">
                            <div class="quick-view-content"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end quick view popup -->
            <div class="wp-dp-listing-content wp-dp-split-map-wrap split-map-<?php
            echo esc_html($wp_dp_map_position);
            echo esc_html($wp_dp_map_fixed);
            ?>" id="wp-dp-listing-content-<?php echo esc_html($listing_short_counter); ?>">
                 <?php
                 echo '<div class="dev-map-class-changer' . $map_change_class . '">'; // container for content area when top map on
                 $page_url = get_permalink(get_the_ID());
                 $posts_per_page = isset($atts['posts_per_page']) ? $atts['posts_per_page'] : '-1';
                 $this->render_map($posts_per_page, $atts);
                 ?>
                <div id="Listing-content-<?php echo esc_html($listing_short_counter); ?>" class="col-lg-5 col-md-5 col-sm-12 col-xs-12 split-map-container">
                    <?php
					$base = $atts;
                    $replacements = array('listing_type' => "all");
                    //$atts = array_replace($base, $replacements);
                    $listing_arg = array(
                        'listing_short_counter' => $listing_short_counter,
                        'atts' => $atts,
                        'content' => $content,
                        'listing_map_counter' => $listing_map_counter,
                        'page_url' => $page_url,
                    );

                    $this->wp_dp_split_map_content($listing_arg);
                    ?>
                </div>
                <?php
                echo '</div>';
                ?>
            </div> 
            <?php
            if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
                echo '</div>';
            }
            $html = ob_get_clean();
            return $html;
        }

        public function wp_dp_split_map_content($listing_arg = array()) {
            wp_enqueue_script('wp-dp-matchHeight-script');
            global $wpdb, $wp_dp_form_fields_frontend, $wp_dp_search_fields;
            // getting arg array from ajax
            if ( isset($_REQUEST['listing_arg']) && $_REQUEST['listing_arg'] ) {
                $listing_arg = $_REQUEST['listing_arg'];
                $listing_arg = json_decode(str_replace('\"', '"', $listing_arg));
                $listing_arg = $this->toArray($listing_arg);
            }
            $listing_arg['atts']['search_title'] = ( isset( $_REQUEST['search_title'] ) )? $_REQUEST['search_title'] : $listing_arg['atts']['search_title'];
            $listing_arg['atts']['listing_type'] = ( isset( $_REQUEST['listing_type'] ) )? $_REQUEST['listing_type'] : $listing_arg['atts']['listing_type'];
            $listing_arg['atts']['radius'] = ( isset( $_REQUEST['radius'] ) )? $_REQUEST['radius'] : $listing_arg['atts']['radius'];
            
            if ( isset($listing_arg) && $listing_arg != '' && ! empty($listing_arg) ) {
                extract($listing_arg);
            }
            $default_date_time_formate = 'd-m-Y H:i:s';
            // getting if user set it with his choice
            if ( false === ( $listing_view = wp_dp_get_transient_obj('wp_dp_listing_view' . $listing_short_counter) ) ) {
                $listing_view = isset($atts['listing_view']) ? $atts['listing_view'] : '';
            }
            ?> 
            <script>
                var view = '<?php echo esc_html($listing_view); ?>';
                if (view == 'map') {
                    jQuery('.wrapper').css('padding-top', '0');
                    if (jQuery('.dev-map-class-changer').length > 0)
                        jQuery('.dev-map-class-changer').addClass('listing-map-holder');
                } else {
                    if (jQuery('.dev-map-class-changer').length > 0)
                        jQuery('.dev-map-class-changer').removeClass('listing-map-holder');
                }
            </script>
            <?php
            $element_listing_sort_by = isset($atts['listing_sort_by']) ? $atts['listing_sort_by'] : 'no';
            $element_listing_topmap = ''; //isset( $atts['listing_topmap'] ) ? $atts['listing_topmap'] : 'no';
            $element_listing_map_position = isset($atts['listing_map_position']) ? $atts['listing_map_position'] : 'full';
            $element_listing_layout_switcher = isset($atts['listing_layout_switcher']) ? $atts['listing_layout_switcher'] : 'no';
            $element_listing_layout_switcher_view = isset($atts['listing_layout_switcher_view']) ? $atts['listing_layout_switcher_view'] : 'grid';
            $element_listing_map_height = isset($atts['listing_map_height']) ? $atts['listing_map_height'] : 400;
            $element_listing_footer = isset($atts['listing_footer']) ? $atts['listing_footer'] : 'no';
            $element_listing_search_keyword = isset($atts['listing_search_keyword']) ? $atts['listing_search_keyword'] : 'no';
            $element_listing_top_category = isset($atts['listing_top_category']) ? $atts['listing_top_category'] : 'no';
            $element_listing_top_category_count = isset($atts['listing_top_category_count']) ? $atts['listing_top_category_count'] : '5';
            $listing_listing_urgent = isset($atts['listing_urgent']) ? $atts['listing_urgent'] : 'all';
            $listing_type = isset($atts['listing_type']) ? $atts['listing_type'] : 'all';
            $listing_price_filter = isset($atts['listing_price_filter']) ? $atts['listing_price_filter'] : 'no';




            $search_box_sidebar = isset($atts['search_box']) ? $atts['search_box'] : 'no';
            $wp_dp_map_position = isset($atts['wp_dp_map_position']) ? $atts['wp_dp_map_position'] : 'right';

            $posts_per_page = '-1';
            $pagination = 'no';
            $posts_per_page = isset($atts['posts_per_page']) ? $atts['posts_per_page'] : '-1';
            $pagination = isset($atts['pagination']) ? $atts['pagination'] : 'no';
            $show_more_listing_button_switch = isset($atts['show_more_listing_button_switch']) ? $atts['show_more_listing_button_switch'] : 'no';
            $show_more_listing_button_url = isset($atts['show_more_listing_button_url']) ? $atts['show_more_listing_button_url'] : '';

            $filter_arr = array();
            $element_filter_arr = array();
            $content_columns = 'col-lg-12 col-md-12 col-sm-12 col-xs-12'; // if filteration not true
            $paging_var = 'listing_page';

            // Element fields in filter
            if ( isset($_REQUEST['listing_type']) && $_REQUEST['listing_type'] != '' ) {
                $listing_type = $_REQUEST['listing_type'];
            }
            $listing_price = '';
            if ( isset($_REQUEST['listing_price']) && $_REQUEST['listing_price'] ) {
                $listing_price = $_REQUEST['listing_price'];
            }

            // posted date check
            $element_filter_arr[] = array(
                'key' => 'wp_dp_listing_posted',
                'value' => strtotime(date($default_date_time_formate)),
                'compare' => '<=',
            );

            $element_filter_arr[] = array(
                'key' => 'wp_dp_listing_expired',
                'value' => strtotime(date($default_date_time_formate)),
                'compare' => '>=',
            );

            $element_filter_arr[] = array(
                'key' => 'wp_dp_listing_status',
                'value' => 'active',
                'compare' => '=',
            );
            // check if member not inactive
            $element_filter_arr[] = array(
                'key' => 'listing_member_status',
                'value' => 'active',
                'compare' => '=',
            );

            if ( $listing_type != '' && $listing_type != 'all' ) {
                $element_filter_arr[] = array(
                    'key' => 'wp_dp_listing_type',
                    'value' => $listing_type,
                    'compare' => '=',
                );
            }
            if ( $listing_price != '' && $listing_price != 'all' ) {
                $element_filter_arr[] = array(
                    'key' => 'wp_dp_listing_price',
                    'value' => $listing_price,
                    'compare' => '=',
                );
            }
            // If featured listing.
            if ( $listing_listing_urgent == 'only-featured' ) {
                $element_filter_arr[] = array(
                    'key' => 'wp_dp_promotion_urgent',
                    'value' => 'on',
                    'compare' => '=',
                );

                $element_filter_arr[] = array( 'relation' => 'OR',
                    array(
                        'key' => 'wp_dp_promotion_urgent_expiry',
                        'value' => date('Y-m-d'),
                        'compare' => '>',
                    ),
                    array(
                        'key' => 'wp_dp_promotion_urgent_expiry',
                        'value' => 'unlimitted',
                        'compare' => '=',
                    )
                );
            }

            if ( function_exists('wp_dp_listing_visibility_query_args') ) {
                $element_filter_arr = wp_dp_listing_visibility_query_args($element_filter_arr);
            }

            if ( ! isset($_REQUEST[$paging_var]) ) {
                $_REQUEST[$paging_var] = '';
            }

            // Get all arguments from getting flters.
            $left_filter_arr = $this->get_filter_arg($listing_type, $listing_short_counter);

            $search_features_filter = $this->listing_search_features_filter();
            if ( ! empty($search_features_filter) ) {
                $left_filter_arr[] = $search_features_filter;
            }

            $post_ids = array();
            if ( ! empty($left_filter_arr) ) {
                // apply all filters and get ids
                $post_ids = $this->get_listing_id_by_filter($left_filter_arr);
            }
            if ( isset($_REQUEST['location']) && $_REQUEST['location'] != '' && ! isset($_REQUEST['loc_polygon_path']) ) {
                
                $radius = isset($_REQUEST['radius']) ? $_REQUEST['radius'] : '';
                $post_ids = $this->listing_location_filter($_REQUEST['location'], $post_ids);
                if ( empty($post_ids) ) {
                    $post_ids = array( 0 );
                }
            }

            $loc_polygon_path = '';
            if ( isset($_REQUEST['loc_polygon_path']) && $_REQUEST['loc_polygon_path'] != '' ) {
                $loc_polygon_path = $_REQUEST['loc_polygon_path'];
            }

            $post_ids = $this->listing_price_filter('', $post_ids);

            $all_post_ids = array();
            if ( ! empty($post_ids) ) {
                $all_post_ids = $post_ids;
            }

            $search_title = isset($_REQUEST['search_title']) ? $_REQUEST['search_title'] : '';

            $args_count = array(
                'posts_per_page' => "1",
                'post_type' => 'listings',
                'post_status' => 'publish',
                'fields' => 'ids', // only load ids
                'meta_query' => array(
                    $element_filter_arr,
                ),
            );

            $listing_sort_by = 'recent'; // default value
            $listing_sort_order = 'desc';   // default value

            if ( isset($_REQUEST['sort-by']) && $_REQUEST['sort-by'] != '' ) {
                $listing_sort_by = $_REQUEST['sort-by'];
            }
            /*
             * used for relevance sort by filter
             */

            if ( isset($_REQUEST['sort-by']) && $_REQUEST['sort-by'] == 'relevence' ) {
                $current_user_id = get_current_user_id();
                $current_member_id = get_user_meta($current_user_id, 'wp_dp_company', true);
                $lat = get_post_meta($current_member_id, 'wp_dp_post_loc_latitude_member', true);
                $lng = get_post_meta($current_member_id, 'wp_dp_post_loc_longitude', true);
                $wp_dp_loc_address_member = get_post_meta($current_member_id, 'wp_dp_post_loc_address_member', true);
                $radius = isset($wp_dp_plugin_options['wp_dp_default_radius_circle']) ? $wp_dp_plugin_options['wp_dp_default_radius_circle'] : '10';

                if ( $lat == '' || $lng == '' ) {
                    $Wp_dp_Locations = new Wp_dp_Locations();
                    $location_response = $Wp_dp_Locations->wp_dp_get_geolocation_latlng_callback($wp_dp_loc_address_member);
                    $lat = isset($location_response->lat) ? $location_response->lat : '';
                    $lng = isset($location_response->lng) ? $location_response->lng : '';
                }

                $radiusCheck = new RadiusCheck($lat, $lng, $radius);
                $minLat = $radiusCheck->MinLatitude();
                $maxLat = $radiusCheck->MaxLatitude();
                $minLong = $radiusCheck->MinLongitude();
                $maxLong = $radiusCheck->MaxLongitude();
                $wp_dp_compare_type = 'CHAR';
                if ( $radius > 0 ) {
                    $wp_dp_compare_type = 'DECIMAL(10,6)';
                }
                $element_filter_arr = array(
                    'relation' => 'OR',
                    array(
                        'key' => 'wp_dp_post_loc_latitude_listing',
                        'value' => array( $minLat, $maxLat ),
                        'compare' => 'BETWEEN',
                        'type' => $wp_dp_compare_type
                    ),
                    array(
                        'key' => 'wp_dp_post_loc_longitude_listing',
                        'value' => array( $minLong, $maxLong ),
                        'compare' => 'BETWEEN',
                        'type' => $wp_dp_compare_type
                    ),
                );
            }


            /*
             * End used for relevance sort by filter
             */



            $meta_key = 'wp_dp_listing_posted';
            $qryvar_listing_sort_type = 'DESC';
            $qryvar_sort_by_column = 'meta_value_num';

            if ( $listing_sort_by == 'recent' ) {
                $meta_key = 'wp_dp_listing_posted';
                $qryvar_listing_sort_type = 'DESC';
                $qryvar_sort_by_column = 'meta_value_num';
            } elseif ( $listing_sort_by == 'alphabetical' ) {
                $qryvar_listing_sort_type = 'ASC';
                $qryvar_sort_by_column = 'post_title';
            } elseif ( $listing_sort_by == 'most_viewed' ) {
                $qryvar_listing_sort_type = 'DESC';
                $qryvar_sort_by_column = 'meta_value_num';
                $meta_key = 'wp_dp_listing_views_count';
            } elseif ( $listing_sort_by == 'high_rated' ) {
                $qryvar_listing_sort_type = 'DESC';
                $qryvar_sort_by_column = 'meta_value_num';
                $meta_key = 'wp_dp_review_overall_rating';
            }

            $args = array(
                'posts_per_page' => $posts_per_page,
                'paged' => $_REQUEST[$paging_var],
                'post_type' => 'listings',
                'post_status' => 'publish',
                'meta_key' => $meta_key,
                'order' => $qryvar_listing_sort_type,
                'orderby' => $qryvar_sort_by_column,
                'fields' => 'ids', // only load ids
                'meta_query' => array(
                    $element_filter_arr,
                ),
            );

            if ( isset($_REQUEST['listing_category']) && $_REQUEST['listing_category'] != '' && ! isset($_REQUEST['advanced_search']) && ! isset($_REQUEST['ajax_filter']) ) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'listing-category',
                        'field' => 'slug',
                        'terms' => $_REQUEST['listing_category']
                    )
                );
            }

            if ( $element_listing_top_category == 'yes' ) {
                $element_top_cate_filter_arr[] = array(
                    'key' => 'wp_dp_listing_posted',
                    'value' => strtotime(date($default_date_time_formate)),
                    'compare' => '<=',
                );

                $element_top_cate_filter_arr[] = array(
                    'key' => 'wp_dp_listing_expired',
                    'value' => strtotime(date($default_date_time_formate)),
                    'compare' => '>=',
                );

                $element_top_cate_filter_arr[] = array(
                    'key' => 'wp_dp_listing_status',
                    'value' => 'active',
                    'compare' => '=',
                );

                $element_top_cate_filter_arr[] = array(
                    'key' => 'wp_dp_promotion_top-categories',
                    'value' => 'on',
                    'compare' => '=',
                );

                $element_top_cate_filter_arr[] = array( 'relation' => 'OR',
                    array(
                        'key' => 'wp_dp_promotion_top-categories_expiry',
                        'value' => date('Y-m-d'),
                        'compare' => '>',
                    ),
                    array(
                        'key' => 'wp_dp_promotion_top-categories_expiry',
                        'value' => 'unlimitted',
                        'compare' => '=',
                    )
                );

                if ( isset($_REQUEST['sort-by']) && $_REQUEST['sort-by'] == 'sold' ) {
                    $element_top_cate_filter_arr[] = array(
                        'key' => 'wp_dp_listing_sold',
                        'value' => 'yes',
                        'compare' => '=',
                    );
                }




                if ( $listing_type != '' && $listing_type != 'all' ) {
                    $element_top_cate_filter_arr[] = array(
                        'key' => 'wp_dp_listing_type',
                        'value' => $listing_type,
                        'compare' => '=',
                    );
                }

                if ( function_exists('wp_dp_listing_visibility_query_args') ) {
                    $element_top_cate_filter_arr = wp_dp_listing_visibility_query_args($element_top_cate_filter_arr);
                }

                $listing_type_category_name = 'wp_dp_listing_category';   // category_fieldname in db and request
                if ( isset($_REQUEST['listing_category']) && $_REQUEST['listing_category'] != '' ) {
                    $dropdown_query_str_var_name = explode(",", $_REQUEST['listing_category']);
                    $cate_filter_multi_arr ['relation'] = 'OR';
                    foreach ( $dropdown_query_str_var_name as $query_str_var_name_key ) {
                        $cate_filter_multi_arr[] = array(
                            'key' => $listing_type_category_name,
                            'value' => serialize($query_str_var_name_key),
                            'compare' => 'LIKE',
                        );
                    }
                    if ( isset($cate_filter_multi_arr) && ! empty($cate_filter_multi_arr) ) {
                        $element_top_cate_filter_arr[] = array(
                            $cate_filter_multi_arr
                        );
                    }
                }
				
                $top_categries_args = array(
                    'posts_per_page' => $element_listing_top_category_count,
                    'post_type' => 'listings',
                    'post_status' => 'publish',
                    'meta_key' => $meta_key,
                    'order' => $qryvar_listing_sort_type,
                    'orderby' => $qryvar_sort_by_column,
                    'fields' => 'ids', // only load ids
                    'meta_query' => array(
                        $element_top_cate_filter_arr,
                    ),
                );
            }


            if ( isset($search_title) && $search_title != '' ) {

                $query_1 = get_posts(array(
                    'posts_per_page' => '-1',
                    'fields' => 'ids',
                    'post_type' => 'listings',
                    's' => $search_title,
                    'post__in' => $all_post_ids,
                ));
                //$search_title = sanitize_title($search_title);
                $query_2_params = array(
                    'posts_per_page' => '-1',
                    'fields' => 'ids',
                    'post_type' => 'listings',
                    'meta_query' => array(
                        /*
                         * @ is being saved on each update post.
                         */
                        array(
                            'key' => 'search_keywords_field',
                            'value' => $search_title,
                            'compare' => 'LIKE'
                        ),
                    )
                );
                if ( ! empty($all_post_ids) ) {
                    $query_2_params['post__in'] = $all_post_ids;
                }

                $query_2 = get_posts($query_2_params);

                $all_post_ids = array_unique(array_merge($query_1, $query_2));

                $all_post_ids = empty($all_post_ids) ? array( 0 ) : $all_post_ids;
            }

            if ( ! empty($all_post_ids) ) {
                $args_count['post__in'] = $all_post_ids;
                $args['post__in'] = $all_post_ids;
            }

            ?>
            <script>
                jQuery(document).ready(function () {
                    var _this_list_view = '<?php echo esc_html($listing_view) ?>';
                    var _element_listing_footer = '<?php echo esc_html($element_listing_footer) ?>';
            <?php
            if ( isset($_POST['action']) ) {
                // temprary off
                ?>
                        if (_this_list_view == 'map') {
                            if (!jQuery('#wp-dp-listing-map-<?php echo esc_html($listing_map_counter) ?>').is(':visible')) {
                                jQuery('.dev-listing-map-holder').css({
                                    display: 'block',
                                });
                                jQuery('.dev-map-class-changer').removeClass('map-<?php echo esc_html($element_listing_map_position) ?>');
                                jQuery('.dev-map-class-changer').removeClass('listing-map-holder');
                                jQuery('.dev-map-class-changer').addClass('map-<?php echo esc_html($element_listing_map_position) ?>');
                                jQuery('.dev-map-class-changer').addClass('listing-map-holder');

                                if (_element_listing_footer == 'yes') {
                                    jQuery("footer#footer").hide();
                                }
                                // temprary comment
                                if (document.getElementById('wp-dp-top-map-holder').length > 0)
                                    document.getElementById('wp-dp-top-map-holder').style.display = "none";
                                // jQuery(window).load(); // temprarty off
                            }
                        } else {
                            if (jQuery('.dev-listing-map-holder').length > 0) {
                                jQuery('.dev-listing-map-holder').css({display: 'none', });
                            }
                            if (jQuery('.dev-map-class-changer').length > 0) {
                                jQuery('.dev-map-class-changer').removeClass('map-<?php echo esc_html($element_listing_map_position) ?>');
                            }
                            if (_element_listing_footer == 'yes') {
                                jQuery("footer#footer").show();
                            }
                            // temprary comment 
                            if (jQuery('#wp-dp-top-map-holder').length > 0) {
                                document.getElementById('wp-dp-top-map-holder').style.display = "block";
                            }
                            // jQuery(window).load(); // temprarty off
                        }

                <?php
            }
            ?>
                });
            </script>
            <?php
            // top categories
            if ( $element_listing_top_category == 'yes' ) {
                $listing_top_categries_loop_obj = wp_dp_get_cached_obj('listing_result_cached_top_categries_loop_obj', $top_categries_args, 12, false, 'wp_query');
            }


            // arrange excluded ids for result
            if ( isset($listing_top_categries_loop_obj->posts) && is_array($listing_top_categries_loop_obj->posts) && ! empty($listing_top_categries_loop_obj->posts) ) {

                if ( ! empty($all_post_ids) ) {
                    $all_post_ids = array_diff($all_post_ids, $listing_top_categries_loop_obj->posts);
                    $args['post__in'] = $all_post_ids;
                } else {
                    $args['post__not_in'] = $listing_top_categries_loop_obj->posts;
                }
                $args['posts_per_page'] = $args['posts_per_page'] - $listing_top_categries_loop_obj->post_count;
            }

            $listing_loop_obj = wp_dp_get_cached_obj('listing_result_cached_loop_obj1', $args, 12, false, 'wp_query');

            $listing_totnum = $listing_loop_obj->found_posts;
            if ( isset($listing_top_categries_loop_obj) && $listing_top_categries_loop_obj->found_posts != '' && $listing_loop_obj->have_posts() ) {
                $listing_totnum += $listing_top_categries_loop_obj->post_count;
            }
            ?>

            <form id="frm_listing_arg<?php echo absint($listing_short_counter); ?>">
                <div style="display:none" id='listing_arg<?php echo absint($listing_short_counter); ?>'><?php
                    echo json_encode($listing_arg);
                    ?>
                </div>
                <?php
                if ( $search_box_sidebar == 'yes' && isset($listing_view) && $listing_view != 'map' && $wp_dp_map_position != 'top' ) {  // if sidebar on from element
                    set_query_var('listing_type', $listing_type);
                    set_query_var('listing_short_counter', $listing_short_counter);
                    set_query_var('listing_arg', $listing_arg);
                    set_query_var('listing_price_filter', $listing_price_filter);
                    set_query_var('args_count', $args_count);
                    set_query_var('atts', $atts);
                    set_query_var('listing_totnum', $listing_totnum);
                    set_query_var('page_url', $page_url);
                    wp_dp_get_template_part('listings', 'splitmap-filters', 'listingsearch');
                    $content_columns = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
                }
                echo '<div class="row">';

                if ( $search_box_sidebar == 'yes' && isset($listing_view) && $listing_view != 'map' && $wp_dp_map_position == 'top' ) {  // if sidebar on from element
                    set_query_var('listing_type', $listing_type);
                    set_query_var('listing_short_counter', $listing_short_counter);
                    set_query_var('listing_arg', $listing_arg);
                    set_query_var('args_count', $args_count);
                    set_query_var('atts', $atts);
                    set_query_var('listing_totnum', $listing_totnum);
                    set_query_var('page_url', $page_url);
                    set_query_var('listing_loop_obj', $listing_loop_obj);
                    wp_dp_get_template_part('listing', 'leftfilters', 'listings');
                    $content_columns = 'col-lg-9 col-md-9 col-sm-12 col-xs-12';
                }
                ?> 
                <div class="<?php echo esc_html($content_columns); ?>">

                    <div class="directorybox-listing-content directorybox-dev-listing-content" id="wp-dp-data-listing-content-<?php echo esc_html($listing_short_counter); ?>" data-id="<?php echo esc_html($listing_short_counter); ?>">
                        <?php
                        $split_map_title = isset($atts['split_map_title']) ? $atts['split_map_title'] : '';
                        $split_map_subtitle = isset($atts['split_map_subtitle']) ? $atts['split_map_subtitle'] : '';
                        $split_map_title_alignment = isset($atts['split_map_title_alignment']) ? $atts['split_map_title_alignment'] : '';
                        $wp_dp_split_map_seperator_style = isset($atts['wp_dp_split_map_seperator_style']) ? $atts['wp_dp_split_map_seperator_style'] : '';
                        $wp_dp_split_map_element_title_color = isset($atts['wp_dp_split_map_element_title_color']) ? $atts['wp_dp_split_map_element_title_color'] : '';
                        $wp_dp_split_map_element_subtitle_color = isset($atts['wp_dp_split_map_element_subtitle_color']) ? $atts['wp_dp_split_map_element_subtitle_color'] : '';
                        $element_title_color = '';
                        if ( isset($wp_dp_split_map_element_title_color) && $wp_dp_split_map_element_title_color != '' ) {
                            $element_title_color = ' style="color:' . $wp_dp_split_map_element_title_color . ' ! important"';
                        }
                        $element_subtitle_color = '';
                        if ( isset($wp_dp_split_map_element_subtitle_color) && $wp_dp_split_map_element_subtitle_color != '' ) {
                            $element_subtitle_color = ' style="color:' . $wp_dp_split_map_element_subtitle_color . ' ! important"';
                        }

                        if ( $split_map_title != '' || $split_map_subtitle != '' || $show_more_listing_button_switch == 'yes' ) {
                            ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="element-title <?php echo ($split_map_title_alignment); ?>">
                                    <?php
                                    if ( $split_map_title != '' || $split_map_subtitle != '' ) {
                                        if ( $split_map_title != '' ) {
                                            ?>
                                            <h2<?php echo wp_dp_allow_special_char($element_title_color); ?>><?php echo esc_html($split_map_title); ?></h2>
                                            <?php
                                        }
                                        if ( $split_map_subtitle != '' ) {
                                            ?>
                                            <p<?php echo wp_dp_allow_special_char($element_subtitle_color); ?>><?php echo esc_html($split_map_subtitle); ?></p>
                                            <?php
                                        }
                                        if ( isset($wp_dp_split_map_seperator_style) && ! empty($wp_dp_split_map_seperator_style) ) {
                                            $wp_dp_split_listings_seperator_html = '';
                                            if ( $wp_dp_split_map_seperator_style == 'classic' ) {
                                                $wp_dp_split_listings_seperator_html .='<div class="classic-separator ' . $split_map_title_alignment . '"><span></span></div>';
                                            }
                                            if ( $wp_dp_split_map_seperator_style == 'zigzag' ) {
                                                $wp_dp_split_listings_seperator_html .='<div class="separator-zigzag ' . $split_map_title_alignment . '">
                                            <figure><img src="' . trailingslashit(wp_dp::plugin_url()) . 'assets/images/zigzag-img1.png" alt=""/></figure>
                                        </div>';
                                            }
                                            echo force_balance_tags($wp_dp_split_listings_seperator_html);
                                        }
                                    }
                                    if ( $show_more_listing_button_switch == 'yes' ) {
                                        ?> 
                                        <a href="<?php echo esc_url($show_more_listing_button_url) ?>" class="show-more-listing"><?php echo wp_dp_plugin_text_srt('wp_dp_listings_show_more') ?></a>
                                    <?php }
                                    ?>
                                </div>
                            </div> 
                            <?php
                        }
                        // only ajax request procced

                        if ( (isset($listing_view) && $listing_view != 'map' ) ) {
							$adv_filter_toggle = isset($_REQUEST['adv_filter_toggle']) ? $_REQUEST['adv_filter_toggle'] : 'false';
							$filter_class = '';
							if( $adv_filter_toggle == 'true' ){
								$filter_class = ' filter-fixed';
							}
                            // sorting fields
                            echo '<div class="slide-loader-holder">';
                            echo '<div class="split-map-fixed-filter'. $filter_class .'">';
                            $this->listing_search_sort_fields($atts, $listing_sort_by, $listing_short_counter, $listing_view, $listing_totnum, $args_count);
                            echo '</div>';
                            echo '</div>';
                        }
                        // search keywords  

                        set_query_var('listing_loop_obj', $listing_loop_obj);
                        set_query_var('listing_view', $listing_view);
                        set_query_var('listing_short_counter', $listing_short_counter);
                        set_query_var('atts', $atts);
                        set_query_var('element_listing_top_category', $element_listing_top_category);
                        if ( $element_listing_top_category == 'yes' ) {
                            set_query_var('listing_top_categries_loop_obj', $listing_top_categries_loop_obj);
                        }
                        if ( isset($listing_view) && $listing_view == 'grid' ) {
                            wp_dp_get_template_part('listing', 'grid', 'listings');
                        } elseif ( isset($listing_view) && $listing_view == 'list' ) { // for grid and view 2
                            wp_dp_get_template_part('listing', 'list', 'listings');
                        }
                        wp_reset_postdata();
                        // apply paging
                        $paging_args = array( 'total_posts' => $listing_totnum,
                            'posts_per_page' => $posts_per_page,
                            'paging_var' => $paging_var,
                            'show_pagination' => $pagination,
                            'listing_short_counter' => $listing_short_counter,
                        );
                        $this->wp_dp_listing_pagination_callback($paging_args);
                        ?>
                    </div>
                    <?php
                    ?>
                </div>
                <?php
                echo '</div>';
                if ( isset($element_listing_topmap) && $element_listing_topmap == 'yes' ) {
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }

                if ( $loc_polygon_path != '' ) {
                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                            array(
                                'simple' => true,
                                'cust_id' => "loc_polygon_path",
                                'cust_name' => 'loc_polygon_path',
                                'std' => $loc_polygon_path,
                            )
                    );
                }

                $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                        array(
                            'return' => false,
                            'cust_name' => '',
                            'classes' => 'listing-counter',
                            'std' => $listing_short_counter,
                        )
                );
                ?>
            </form>
            <script>
                if (jQuery('.chosen-select, .chosen-select-deselect, .chosen-select-no-single, .chosen-select-no-results, .chosen-select-width').length != '') {
                    var config = {
                        '.chosen-select': {width: "100%"},
                        '.chosen-select-deselect': {allow_single_deselect: true},
                        '.chosen-select-no-single': {disable_search_threshold: 10, width: "100%"},
                        '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
                        '.chosen-select-width': {width: "95%"}
                    };
                    for (var selector in config) {
                        jQuery(selector).chosen(config[selector]);
                    }
                }
                jQuery(document).ready(function () {
                    var Header_height = jQuery("header#header").height();
                    if (jQuery('.listing-map-holder.map-right').length != '') {
                        jQuery("header#header").addClass("fixed-header");
                        jQuery(".listing-map-holder.map-right .detail-map").addClass("fixed-item").css("padding-top", Header_height);
                        jQuery(".listing-map-holder.map-right .detail-map-listing").css("padding-top", Header_height);

                    } else {
                        jQuery(".listing-map-holder.map-right .detail-map").removeClass("fixed-item").css("padding-top", "0");
                        jQuery("header#header").removeClass("fixed-header");
                        jQuery(".listing-map-holder.map-right .detail-map-listing").css("padding-top", "0");
                    }

                    if (jQuery('.listing-map-holder.map-left').length != '') {
                        jQuery("header#header").addClass("fixed-header");
                        jQuery(".listing-map-holder.map-left .detail-map").addClass("fixed-item").css("padding-top", Header_height);
                        jQuery(".listing-map-holder.map-left .detail-map-listing").css("padding-top", Header_height);

                    } else {
                        jQuery(".listing-map-holder.map-left .detail-map").removeClass("fixed-item").css("padding-top", "0");
                        jQuery("header#header").removeClass("fixed-header");
                        jQuery(".listing-map-holder.map-left .detail-map-listing").css("padding-top", "0");
                    }
                });
            </script>

            <?php
// only for ajax request
            if ( isset($_REQUEST['action']) && $_REQUEST['action'] != 'editpost' ) {
                die();
            }
        }

        /*
         * Split Map View
         */

        public function render_map($posts_per_page, $atts) {

            $paging_var = 'listing_page';
            if ( ! isset($_REQUEST[$paging_var]) ) {
                $_REQUEST[$paging_var] = '';
            }
            $map_atts = array
                (
                'posts_per_page' => $posts_per_page,
                'paged' => $_REQUEST[$paging_var],
                'map_search_element_size' => 100,
                'map_search_title_alignment' => 'align-right',
                'map_search_box_switch' => 'no',
                'map_map_search_switch' => 'yes',
                'map_search_title_field_switch' => 'no',
                'map_search_listing_type_field_switch' => 'no',
                'map_search_location_field_switch' => 'no',
                'map_search_price_field_switch' => 'no',
                'map_search_categories_field_switch' => 'no',
                'map_map_search_height' => 500,
                'split_map' => true,
            );
            $listing_type = isset($atts['listing_type']) && $atts['listing_type'] != '' ? $atts['listing_type'] : '';
            $element_listing_top_category = isset($atts['listing_top_category']) ? $atts['listing_top_category'] : 'no';
            $element_listing_top_category_count = isset($atts['listing_top_category_count']) ? $atts['listing_top_category_count'] : '5';
            if ( $listing_type != '' && $listing_type != 'all' ) {
                $map_atts['listing_type'] = $listing_type;
            }
            if ( isset($element_listing_top_category) && $element_listing_top_category == 'yes' ) {
                $map_atts['listing_top_category'] = $element_listing_top_category;
                $map_atts['listing_top_category_count'] = $element_listing_top_category_count;
            }


            global $wp_dp_shortcode_map_search_front;
            echo ($wp_dp_shortcode_map_search_front->wp_dp_map_search_shortcode_callback($map_atts));
        }

        public function listing_polygon_filter($polygon_pathstr, $post_ids) {
            $polygon_path = array();
            $polygon_path = explode('||', $polygon_pathstr);
            if ( count($polygon_path) > 0 ) {
                array_walk($polygon_path, function(&$val) {
                    $val = explode(',', $val);
                });
            }
            $new_post_ids = array();
            foreach ( $post_ids as $key => $listing_id ) {
                $listing_latitude = get_post_meta($listing_id, 'wp_dp_post_loc_latitude_listing', true);
                $listing_longitude = get_post_meta($listing_id, 'wp_dp_post_loc_longitude_listing', true);
                if ( $this->pointInPolygon(array( $listing_latitude, $listing_longitude ), $polygon_path) ) {
                    $new_post_ids[] = $listing_id;
                }
            }
            return $new_post_ids;
        }

        public function pointInPolygon($point, $polygon) {
            $return = false;
            foreach ( $polygon as $k => $p ) {
                if ( ! $k )
                    $k_prev = count($polygon) - 1;
                else
                    $k_prev = $k - 1;

                if ( ($p[1] < $point[1] && $polygon[$k_prev][1] >= $point[1] || $polygon[$k_prev][1] < $point[1] && $p[1] >= $point[1]) && ($p[0] <= $point[0] || $polygon[$k_prev][0] <= $point[0]) ) {
                    if ( $p[0] + ($point[1] - $p[1]) / ($polygon[$k_prev][1] - $p[1]) * ($polygon[$k_prev][0] - $p[0]) < $point[0] ) {
                        $return = ! $return;
                    }
                }
            }
            return $return;
        }

        function listing_search_features_filter() {
            global $wp_dp_search_fields;

            $listing_type_slug = $listing_type_id = '';
            $features_filter = array();
            if ( isset($_REQUEST['listing_type']) && $_REQUEST['listing_type'] ) {
                $listing_type_slug = $_REQUEST['listing_type'];
            }

            if ( $listing_type_slug != '' ) {
                $listing_type_id = $wp_dp_search_fields->wp_dp_listing_type_id_by_slug($listing_type_slug);
            }

            if ( isset($_REQUEST['features']) && $_REQUEST['features'] != '' && $listing_type_id != '' ) {
                $features = $_REQUEST['features'];
                $features = explode(',', $features);

                $listing_type_features = get_post_meta($listing_type_id, 'feature_lables', true);
                $feature_icons = get_post_meta($listing_type_id, 'wp_dp_feature_icon', true);
                $search_features = array();
                if ( is_array($listing_type_features) && sizeof($listing_type_features) > 0 ) {
                    foreach ( $listing_type_features as $feat_key => $feature ) {
                        if ( isset($feature) && ! empty($feature) ) {
                            $feature_name = isset($feature) ? $feature : '';
                            $feature_icon = isset($feature_icons[$feat_key]) ? $feature_icons[$feat_key] : '';
                            if ( in_array($feature_name, $features) ) {
                                $search_features[] = $feature_name . '_icon' . $feature_icon;
                            }
                        }
                    }
                }

                if ( is_array($search_features) && ! empty($search_features) ) {
                    $features_filter['relation'] = 'OR';
                    foreach ( $search_features as $feature ) {
                        $features_filter['meta_query'][] = array(
                            'key' => 'wp_dp_listing_feature_list',
                            'value' => $feature,
                            'compare' => 'LIKE',
                            'type' => 'CHAR'
                        );
                    }
                }
            }
            return $features_filter;
        }

        public function get_filter_arg($listing_type, $listing_short_counter = '', $exclude_meta_key = '') {
            global $wp_dp_post_listing_types;
            $filter_arr = array();
            $listing_type_category_name = 'wp_dp_listing_category';   // category_fieldname in db and request
            if ( $exclude_meta_key != $listing_type_category_name ) {
                if ( isset($_REQUEST['listing_category']) && $_REQUEST['listing_category'] != '' ) {
                    $dropdown_query_str_var_name = explode(",", $_REQUEST['listing_category']);
                    $count_cats = count($dropdown_query_str_var_name);
                    $cate_filter_multi_arr ['relation'] = 'OR';
                    $i = 1;
                    foreach ( $dropdown_query_str_var_name as $query_str_var_name_key ) {
                        if ( $i >= $count_cats ) {
                            $cate_filter_multi_arr[] = array(
                                'key' => $listing_type_category_name,
                                'value' => serialize($query_str_var_name_key),
                                'compare' => 'LIKE',
                            );
                        }
                        $i ++;
                    }
                    if ( isset($cate_filter_multi_arr) && ! empty($cate_filter_multi_arr) ) {
                        $filter_arr[] = array(
                            $cate_filter_multi_arr
                        );
                    }
                }
            }
            if ( isset($listing_type) && $listing_type != '' && $listing_type != 'all' ) {
                $wp_dp_listing_type_cus_fields = $wp_dp_post_listing_types->wp_dp_types_custom_fields_array($listing_type);
                $wp_dp_fields_output = '';
                if ( is_array($wp_dp_listing_type_cus_fields) && sizeof($wp_dp_listing_type_cus_fields) > 0 ) {
                    $custom_field_flag = 1;
                    foreach ( $wp_dp_listing_type_cus_fields as $cus_fieldvar => $cus_field ) {
                        if ( isset($cus_field['enable_srch']) && $cus_field['enable_srch'] == 'yes' ) {
                            $query_str_var_name = $cus_field['meta_key'];
// only for date type field need to change field name
                            if ( $exclude_meta_key != $query_str_var_name ) {
                                if ( $cus_field['type'] == 'date' ) {

                                    if ( $cus_field['type'] == 'date' ) {

                                        $from_date = 'from' . $query_str_var_name;
                                        $to_date = 'to' . $query_str_var_name;
                                        if ( isset($_REQUEST[$from_date]) && $_REQUEST[$from_date] != '' ) {
                                            $filter_arr[] = array(
                                                'key' => $query_str_var_name,
                                                'value' => strtotime($_REQUEST[$from_date]),
                                                'compare' => '>=',
                                            );
                                        }
                                        if ( isset($_REQUEST[$to_date]) && $_REQUEST[$to_date] != '' ) {
                                            $filter_arr[] = array(
                                                'key' => $query_str_var_name,
                                                'value' => strtotime($_REQUEST[$to_date]),
                                                'compare' => '<=',
                                            );
                                        }
                                    }
                                } else if ( isset($_REQUEST[$query_str_var_name]) && $_REQUEST[$query_str_var_name] != '' ) {

                                    if ( $cus_field['type'] == 'dropdown' ) {
                                        if ( isset($cus_field['multi']) && $cus_field['multi'] == 'yes' ) {
                                            $filter_multi_arr ['relation'] = 'OR';
                                            $dropdown_query_str_var_name = explode(",", $_REQUEST[$query_str_var_name]);
                                            foreach ( $dropdown_query_str_var_name as $query_str_var_name_key ) {
                                                if ( $cus_field['post_multi'] == 'yes' ) {
                                                    $filter_multi_arr[] = array(
                                                        'key' => $query_str_var_name,
                                                        'value' => serialize($query_str_var_name_key),
                                                        'compare' => 'Like',
                                                    );
                                                } else {
                                                    $filter_multi_arr[] = array(
                                                        'key' => $query_str_var_name,
                                                        'value' => $query_str_var_name_key,
                                                        'compare' => '=',
                                                    );
                                                }
                                            }
                                            $filter_arr[] = array(
                                                $filter_multi_arr
                                            );
                                        } else {
                                            if ( $cus_field['post_multi'] == 'yes' ) {

                                                $filter_arr[] = array(
                                                    'key' => $query_str_var_name,
                                                    'value' => serialize($_REQUEST[$query_str_var_name]),
                                                    'compare' => 'Like',
                                                );
                                            } else {
                                                $filter_arr[] = array(
                                                    'key' => $query_str_var_name,
                                                    'value' => $_REQUEST[$query_str_var_name],
                                                    'compare' => '=',
                                                );
                                            }
                                        }
                                    } elseif ( $cus_field['type'] == 'text' || $cus_field['type'] == 'email' || $cus_field['type'] == 'url' ) {
                                        if ( $_REQUEST[$query_str_var_name] != 0 && $_REQUEST[$query_str_var_name] != '' ) {
                                            $filter_arr[] = array(
                                                'key' => $query_str_var_name,
                                                'value' => $_REQUEST[$query_str_var_name],
                                                'compare' => 'LIKE',
                                            );
                                        }
                                    } elseif ( $cus_field['type'] == 'number' ) {
                                        if ( $_REQUEST[$query_str_var_name] != 0 && $_REQUEST[$query_str_var_name] != '' ) {
                                            $filter_arr[] = array(
                                                'key' => $query_str_var_name,
                                                'value' => $_REQUEST[$query_str_var_name],
                                                'compare' => '>=',
                                            );
                                        }
                                    } elseif ( $cus_field['type'] == 'range' ) {
                                        $ranges_str_arr = explode(",", $_REQUEST[$query_str_var_name]);
                                        if ( ! isset($ranges_str_arr[1]) ) {
                                            $ranges_str_arr = explode(",", $ranges_str_arr[0]);
                                        }
                                        $range_first = $ranges_str_arr[0];
                                        $range_seond = $ranges_str_arr[1];
                                        $filter_arr[] = array(
                                            'key' => $query_str_var_name,
                                            'value' => $range_first,
                                            'compare' => '>=',
                                            'type' => 'numeric'
                                        );
                                        $filter_arr[] = array(
                                            'key' => $query_str_var_name,
                                            'value' => $range_seond,
                                            'compare' => '<=',
                                            'type' => 'numeric'
                                        );
                                    }
                                }
                            }
                        }
                        $custom_field_flag ++;
                    }
                }
            }
// }
            return $filter_arr;
        }

        public function get_listing_id_by_filter($left_filter_arr) {
            global $wpdb;
            $meta_post_ids_arr = '';
            $listing_id_condition = '';
            if ( isset($left_filter_arr) && ! empty($left_filter_arr) ) {
                $meta_post_ids_arr = wp_dp_get_query_whereclase_by_array($left_filter_arr);
// if no result found in filtration 
                if ( empty($meta_post_ids_arr) ) {
                    $meta_post_ids_arr = array( 0 );
                }

                if ( isset($_REQUEST['loc_polygon_path']) && $_REQUEST['loc_polygon_path'] != '' && $meta_post_ids_arr != '' ) {
                    $meta_post_ids_arr = $this->listing_polygon_filter($_REQUEST['loc_polygon_path'], $meta_post_ids_arr);
                    if ( empty($meta_post_ids_arr) ) {
                        $meta_post_ids_arr = '';
                    }
                }
                $ids = $meta_post_ids_arr != '' ? implode(",", $meta_post_ids_arr) : '0';
                $listing_id_condition = " ID in (" . $ids . ") AND ";
            }

            $post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE " . $listing_id_condition . " post_type='listings' AND post_status='publish'");

            if ( empty($post_ids) ) {
                $post_ids = array( 0 );
            }
            return $post_ids;
        }

        public function listing_search_features_meta_query($search_listing_ids = array()) {
            global $wp_dp_search_fields;
            $listing_type_slug = $listing_type_id = '';
            $search_features_ids = array();
            if ( isset($_REQUEST['listing_type']) && $_REQUEST['listing_type'] ) {
                $listing_type_slug = $_REQUEST['listing_type'];
            }

            if ( $listing_type_slug != '' ) {
                $listing_type_id = $wp_dp_search_fields->wp_dp_listing_type_id_by_slug($listing_type_slug);
            }

            if ( isset($_REQUEST['features']) && $_REQUEST['features'] != '' && $listing_type_id != '' ) {
                $features = $_REQUEST['features'];
                $features = explode(',', $features);

                $listing_type_features = get_post_meta($listing_type_id, 'feature_lables', true);
                $feature_icons = get_post_meta($listing_type_id, 'wp_dp_feature_icon', true);
                $search_features = array();
                if ( is_array($listing_type_features) && sizeof($listing_type_features) > 0 ) {
                    foreach ( $listing_type_features as $feat_key => $feature ) {
                        if ( isset($feature) && ! empty($feature) ) {
                            $feature_name = isset($feature) ? $feature : '';
                            $feature_icon = isset($feature_icons[$feat_key]) ? $feature_icons[$feat_key] : '';
                            if ( in_array($feature_name, $features) ) {

                                $search_features[] = $feature_name . '_icon' . $feature_icon;
                            }
                        }
                    }
                }

                if ( is_array($search_features) && ! empty($search_features) ) {
                    $args['post_type'] = 'listings';
                    $args['posts_per_page'] = -1;
                    $args['fields'] = 'ids'; // only load ids
                    $args['meta_query']['relation'] = 'OR';
                    foreach ( $search_features as $feature ) {
                        $args['meta_query'][] = array(
                            'key' => 'wp_dp_listing_feature_list',
                            'value' => $feature,
                            'compare' => 'LIKE',
                            'type' => 'CHAR'
                        );
                    }
                    $feature_query = new WP_Query($args);
                    if ( $feature_query->have_posts() ):
                        while ( $feature_query->have_posts() ): $feature_query->the_post();
                            $search_features_ids[] = get_the_ID();
                        endwhile;
                    endif;
                }
            }
            return $search_listing_ids;
        }

        public function listing_search_sort_fields($atts, $listing_sort_by, $listing_short_counter, $view = '', $listing_totnum = '', $args_count = array()) {
            global $wp_dp_form_fields_frontend;

            $counter = isset($atts['listing_counter']) && $atts['listing_counter'] != '' ? $atts['listing_counter'] : '';
            $listing_type = isset($atts['listing_type']) && $atts['listing_type'] != '' ? $atts['listing_type'] : '';
            $listing_type_slug = isset($_REQUEST['listing_type']) ? $_REQUEST['listing_type'] : $listing_type;
            $listing_type_text = $listing_type_slug;
            if ( isset($listing_type_slug) && ! empty($listing_type_slug) && $listing_type_slug != 'all' ) {
                if ( $post = get_page_by_path($listing_type_slug, OBJECT, 'listing-type') ) {
                    $id = $post->ID;
                    $listing_type_text = get_the_title($id);
                }
            }
            $transient_view = wp_dp_get_transient_obj('wp_dp_listing_view' . $counter);
            $view = isset($transient_view) && $transient_view != '' ? $transient_view : $view;
            $more_filters = false;
            if ( ( isset($atts['search_box']) && $atts['search_box'] != 'no' && isset($atts['wp_dp_map_position']) && $atts['wp_dp_map_position'] != 'top' ) ) {
                $more_filters = true;
            }
            if ( ( isset($atts['listing_sort_by']) && $atts['listing_sort_by'] != 'no') || ( isset($atts['listing_layout_switcher']) && $atts['listing_layout_switcher'] != 'no' ) || $more_filters == true ) {
                ?>
                <div class="listing-sorting-holder"><div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="all-results">
                                <div class="split-map-heading"><h5><span><?php echo absint($listing_totnum); ?></span> <?php
                                        echo wp_dp_plugin_text_srt('wp_dp_split_map_filter_heading_list_result_for');
                                        echo '<span class="result-clr">';
                                        printf(wp_dp_plugin_text_srt('wp_dp_split_map_filter_heading_list'), ($listing_type_text));
                                        echo '</span>';
                                        ?></h5></div>
                                    <?php
                                    if ( $view != 'map' ) {
                                        $this->listing_layout_switcher_fields($atts, $listing_short_counter, $view = '');
                                    }
                                    ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $adv_filter_toggle = isset($_REQUEST['adv_filter_toggle']) ? $_REQUEST['adv_filter_toggle'] : 'false';

                $args_more = array(
                    'listing_type' => $atts['listing_type'],
                    'listing_price_filter' => $atts['listing_price_filter'],
                    'search_box' => $atts['search_box'],
                    'args_count' => $args_count,
                    'wp_dp_map_position' => $atts['wp_dp_map_position'],
                    'listing_short_counter' => $listing_short_counter,
                    'listing_sort_by' => $atts['listing_sort_by'],
                    'adv_filter_toggle' => $adv_filter_toggle,
                );

                do_action('wp_dp_search_more_filter', $args_more);
            }
        }

        public function listing_layout_switcher_fields($atts, $listing_short_counter, $view = '', $frc_view = false) {

            $counter = isset($atts['listing_counter']) && $atts['listing_counter'] != '' ? $atts['listing_counter'] : '';
            $transient_view = wp_dp_get_transient_obj('wp_dp_listing_view' . $counter);

            if ( $frc_view == true ) {
                $view = $view;
            } else {
                if ( false === ( $view = wp_dp_get_transient_obj('wp_dp_listing_view' . $counter) ) ) {
                    $view = isset($atts['listing_view']) ? $atts['listing_view'] : '';
                }
            }
            if ( ( isset($atts['listing_layout_switcher']) && $atts['listing_layout_switcher'] != 'no' ) ) {

                if ( isset($atts['listing_layout_switcher_view']) && ! empty($atts['listing_layout_switcher_view']) ) {
                    $listing_layout_switcher_views = array(
                        'grid' => wp_dp_plugin_text_srt('wp_dp_element_view_grid'),
                        'map' => wp_dp_plugin_text_srt('wp_dp_list_meta_map'),
                        'list' => wp_dp_plugin_text_srt('wp_dp_element_view_list'),
                    );
                    ?> 
                    <ul class="listings-views-switcher-holder">
                        <li><?php echo wp_dp_plugin_text_srt('wp_dp_view_listings_by_switcher'); ?></li>
                        <?php
                        $element_listing_layout_switcher_view = explode(',', $atts['listing_layout_switcher_view']);

                        if ( ! empty($element_listing_layout_switcher_view) && is_array($element_listing_layout_switcher_view) ) {
                            $views_counter = 0;
                            foreach ( $element_listing_layout_switcher_view as $single_layout_view ) {
                                $case_for_list = $single_layout_view;
                                if ( $single_layout_view == 'list' ) {
                                    $case_for_list = 'listed';
                                }
                                if ( $single_layout_view == 'grid-medern' ) {
                                    $case_for_list = 'grid-medern';
                                }
                                switch ( $case_for_list ) {
                                    case 'grid':
                                        $icon = '<i class="icon-th-large"></i> ';
                                        $icon .= wp_dp_plugin_text_srt('wp_dp_element_view_grid');
                                        $view_class = 'grid-view';
                                        break;
                                    case 'listed':
                                        $icon = '<i class="icon-th-list"></i> ';
                                        $icon .= wp_dp_plugin_text_srt('wp_dp_element_view_list');
                                        $view_class = 'list-view';
                                        break;
                                    case 'grid-medern':
                                        $icon = '<i class="icon-th"></i> ';
                                        $icon .= wp_dp_plugin_text_srt('wp_dp_element_view_gid_modern');
                                        $view_class = 'grid-modern-view';
                                        break;
                                    case 'grid-classic':
                                        $icon = '<i class="icon-grid_on"></i> ';
                                        $icon .= wp_dp_plugin_text_srt('wp_dp_element_view_gid_classic');
                                        $view_class = 'grid-classic-view';
                                        break;
                                    case 'list-modern':
                                        $icon = '<i class="icon-list5"></i> ';
                                        $icon .= wp_dp_plugin_text_srt('wp_dp_element_view_list_modern');
                                        $view_class = 'list-modern-view';
                                        break;
                                    default:
                                        $icon = '<i class="icon-th-list"></i> ';
                                        $icon .= wp_dp_plugin_text_srt('wp_dp_element_view_list');
                                        $view_class = 'list-view';
                                }
                                if ( empty($view) && $views_counter === 0 ) {
                                    ?>
                                    <li><a href="javascript:void(0);" class="active"><i class="icon-th-list"></i><?php echo esc_html($listing_layout_switcher_views[$single_layout_view]); ?></a></li>
                                    <?php
                                } else {
                                    ?>
                                    <li class="<?php echo esc_html($view_class); ?>"><a href="javascript:void(0);" <?php if ( $view == $single_layout_view ) echo 'class="active"'; ?> <?php if ( $view != $single_layout_view ) { ?> onclick="wp_dp_listing_view_switch('<?php echo esc_html($single_layout_view) ?>', '<?php echo esc_html($listing_short_counter); ?>', '<?php echo esc_html($counter); ?>', 'split_map');"<?php } ?>><?php echo force_balance_tags($icon); ?></a></li>
                                    <?php
                                }
                                $views_counter ++;
                            }
                        }
                        ?>
                    </ul>

                    <?php
                }
            }
        }

        public function wp_dp_listing_view_switch() {
            $view = wp_dp_get_input('view', NULL, 'STRING');
            $listing_short_counter = wp_dp_get_input('listing_short_counter', NULL, 'STRING');
            wp_dp_set_transient_obj('wp_dp_listing_view' . $listing_short_counter, $view);
            echo 'success';
            wp_die();
        }

        public function listing_location_filter($location_slug, $all_post_ids) {
            $radius = isset($_REQUEST['radius']) ? $_REQUEST['radius'] : '';
            $search_type = isset($_REQUEST['search_type']) ? $_REQUEST['search_type'] : 'autocomplete';
            if (($search_type == 'autocomplete' && ($radius == 0 || $radius == '') ) || ($search_type == 'custom' && ($radius == 0 || $radius == '') )) {

                if ( isset($location_slug) && $location_slug != '' ) {
                    $location_condition_arr[] = array(
                        'relation' => 'OR',
                        array(
                            'key' => 'wp_dp_post_loc_country_listing',
                            'value' => $location_slug,
                            'compare' => 'LIKE',
                        ),
                        array(
                            'key' => 'wp_dp_post_loc_state_listing',
                            'value' => $location_slug,
                            'compare' => 'LIKE',
                        ),
                        array(
                            'key' => 'wp_dp_post_loc_city_listing',
                            'value' => $location_slug,
                            'compare' => 'LIKE',
                        ),
                        array(
                            'key' => 'wp_dp_post_loc_town_listing',
                            'value' => $location_slug,
                            'compare' => 'LIKE',
                        ),
                        array(
                            'key' => 'wp_dp_post_loc_address_listing',
                            'value' => $location_slug,
                            'compare' => 'LIKE',
                        ),
                    );
                    

                    $args_count = array(
                        'posts_per_page' => "-1",
                        'post_type' => 'listings',
                        'post_status' => 'publish',
                        'fields' => 'ids', // only load ids
                        'meta_query' => array(
                            $location_condition_arr,
                        ),
                    );
                    if ( ! empty($all_post_ids) ) {
                        $args_count['post__in'] = $all_post_ids;
                    }
                    $location_rslt = get_posts($args_count);
                }
            } else {
                $location_rslt = $this->listing_geolocation_filter($_REQUEST['location'], $all_post_ids, $radius);
            }
            return $location_rslt;
        }

        /*
         * Listing Price Search Filter
         */

        public function listing_price_filter($open_house = '', $all_post_ids = array()) {

            $results = $all_post_ids;

            $maximum_price = ( isset($_REQUEST['price_maximum']) && $_REQUEST['price_maximum'] != '' ) ? $_REQUEST['price_maximum'] : '';
            $minimum_price = ( isset($_REQUEST['price_minimum']) && $_REQUEST['price_minimum'] != '' ) ? $_REQUEST['price_minimum'] : '';
            $price_type = ( isset($_REQUEST['price_type']) && $_REQUEST['price_type'] != '' ) ? $_REQUEST['price_type'] : '';
            $filter_arr = array();
            if ( $minimum_price != '' && $minimum_price != 0 ) {
                $filter_arr[] = array(
                    'key' => 'wp_dp_listing_price',
                    'value' => $minimum_price,
                    'compare' => '>=',
                    'type' => 'NUMERIC',
                );
            }
            if ( $maximum_price != '' && $maximum_price != 0 ) {
                $filter_arr[] = array(
                    'key' => 'wp_dp_listing_price',
                    'value' => $maximum_price,
                    'compare' => '<=',
                    'type' => 'NUMERIC',
                );
            }

            if ( $price_type != '' ) {
                $filter_arr[] = array(
                    'key' => 'wp_dp_price_type',
                    'value' => $price_type,
                    'compare' => '=',
                );
            }

            if ( ! empty($filter_arr) ) {

                $filter_arr[] = array(
                    'key' => 'wp_dp_listing_price_options',
                    'value' => 'price',
                    'compare' => '=',
                );

                $args_count = array(
                    'posts_per_page' => "-1",
                    'post_type' => 'listings',
                    'post_status' => 'publish',
                    'fields' => 'ids', // only load ids
                    'meta_query' => array(
                        $filter_arr,
                    ),
                );
                if ( ! empty($all_post_ids) ) {
                    $args_count['post__in'] = $all_post_ids;
                }
                $results = get_posts($args_count);

                if ( empty($results) ) {
                    $results = array( 0 );
                }
            }
            return $results;
        }

        public function listing_geolocation_filter($location_slug, $all_post_ids, $radius) {
            global $wp_dp_plugin_options;
            $distance_symbol = isset($wp_dp_plugin_options['wp_dp_distance_measure_by']) ? $wp_dp_plugin_options['wp_dp_distance_measure_by'] : 'km';
            if ( $distance_symbol == 'km' ) {
                $radius = $radius / 1.60934; // 1.60934 == 1 Mile
            }
            if ( isset($location_slug) && $location_slug != '' ) {
                $Wp_dp_Locations = new Wp_dp_Locations();
                $location_response = $Wp_dp_Locations->wp_dp_get_geolocation_latlng_callback($location_slug);
                $lat = isset($location_response->lat) ? $location_response->lat : '';
                $lng = isset($location_response->lng) ? $location_response->lng : '';
                $radiusCheck = new RadiusCheck($lat, $lng, $radius);
                $minLat = $radiusCheck->MinLatitude();
                $maxLat = $radiusCheck->MaxLatitude();
                $minLong = $radiusCheck->MinLongitude();
                $maxLong = $radiusCheck->MaxLongitude();
                $wp_dp_compare_type = 'CHAR';
                if ( $radius > 0 ) {
                    $wp_dp_compare_type = 'DECIMAL(10,6)';
                }
                $location_condition_arr = array(
                    'relation' => 'OR',
                    array(
                        'key' => 'wp_dp_post_loc_latitude_listing',
                        'value' => array( $minLat, $maxLat ),
                        'compare' => 'BETWEEN',
                        'type' => $wp_dp_compare_type
                    ),
                    array(
                        'key' => 'wp_dp_post_loc_longitude_listing',
                        'value' => array( $minLong, $maxLong ),
                        'compare' => 'BETWEEN',
                        'type' => $wp_dp_compare_type
                    ),
                );
                $args_count = array(
                    'posts_per_page' => "-1",
                    'post_type' => 'listings',
                    'post_status' => 'publish',
                    'fields' => 'ids', // only load ids
                    'meta_query' => array(
                        $location_condition_arr,
                    ),
                );
                if ( ! empty($all_post_ids) ) {
                    $args_count['post__in'] = $all_post_ids;
                }
                $location_rslt = get_posts($args_count);
                return $location_rslt;
                $rslt = '';
            }
        }

        public function toArray($obj) {
            if ( is_object($obj) ) {
                $obj = (array) $obj;
            }
            if ( is_array($obj) ) {
                $new = array();
                foreach ( $obj as $key => $val ) {
                    $new[$key] = $this->toArray($val);
                }
            } else {
                $new = $obj;
            }

            return $new;
        }

        /*
         * listing pagination
         */

        public function wp_dp_listing_pagination_callback($args) {
            global $wp_dp_form_fields_frontend;
            $total_posts = '';
            $posts_per_page = '5';
            $paging_var = 'paged_id';
            $show_pagination = 'yes';
            $listing_short_counter = '';
            extract($args);

            $ajax_filter = ( isset($_REQUEST['ajax_filter']) || isset($_REQUEST['search_type']) ) ? 'true' : 'false';

            if ( $show_pagination <> 'yes' ) {
                return;
            } else if ( $total_posts <= $posts_per_page ) {
                return;
            } else {
                if ( ! isset($_REQUEST['page_id']) ) {
                    $_REQUEST['page_id'] = '';
                }
                $html = '';
                $dot_pre = '';
                $dot_more = '';
                $total_page = 0;
                if ( $total_posts <> 0 )
                    $total_page = ceil($total_posts / $posts_per_page);
                $paged_id = 1;
                if ( isset($_REQUEST[$paging_var]) && $_REQUEST[$paging_var] != '' ) {
                    $paged_id = $_REQUEST[$paging_var];
                }
                $loop_start = $paged_id;

                $loop_end = $paged_id + 1;

                if ( $paged_id < 3 ) {

                    $loop_start = 1;

                    if ( $total_page < 5 )
                        $loop_end = $total_page;
                    else
                        $loop_end = 3;
                }
                else if ( $paged_id >= $total_page - 1 ) {

                    if ( $total_page < 5 )
                        $loop_start = 1;
                    else
                        $loop_start = $total_page - 4;

                    $loop_end = $total_page;
                }
                $html .= $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                        array(
                            'simple' => true,
                            'cust_id' => $paging_var . '-' . $listing_short_counter,
                            'cust_name' => $paging_var,
                            'std' => '',
                            'extra_atr' => 'onchange="wp_dp_listing_content(\'' . $listing_short_counter . '\');"',
                        )
                );
                $html .= '<div class="page-nation"><ul class="pagination pagination-large">';
                if ( $paged_id > 1 ) {
                    $html .= '<li class="pagination-prev"><a onclick="wp_dp_listing_pagenation_ajax(\'' . $paging_var . '\', \'' . ($paged_id - 1) . '\', \'' . ($listing_short_counter) . '\', \'' . ($ajax_filter) . '\', \'split_map\');" href="javascript:void(0);">';
                    $html .= '<i class="icon-keyboard_arrow_left"><i></a></li>';
                } else {
                    
                }

                if ( $paged_id >= 3 and $total_page > 5 ) {


                    $html .= '<li><a onclick="wp_dp_listing_pagenation_ajax(\'' . $paging_var . '\', \'' . (1) . '\', \'' . ($listing_short_counter) . '\', \'' . ($ajax_filter) . '\', \'split_map\');" href="javascript:void(0);">';
                    $html .= '1</a></li>';
                }
                if ( $paged_id >= 3 and $total_page > 5 ) {
                    $html .= '<li class="no-border"><a>. . .</a><li>';
                }

                if ( $total_page > 1 ) {

                    for ( $i = $loop_start; $i <= $loop_end; $i ++ ) {

                        if ( $i <> $paged_id ) {

                            $html .= '<li><a onclick="wp_dp_listing_pagenation_ajax(\'' . $paging_var . '\', \'' . ($i) . '\', \'' . ($listing_short_counter) . '\', \'' . ($ajax_filter) . '\', \'split_map\');" href="javascript:void(0);">';
                            $html .= $i . '</a></li>';
                        } else {
                            $html .= '<li class="active"><span><a class="page-numbers active">' . $i . '</a></span></li>';
                        }
                    }
                }
                if ( $loop_end <> $total_page and $loop_end <> $total_page - 1 ) {
                    $html .= '<li class="no-border"><a>. . .</a></li>';
                }
                if ( $loop_end <> $total_page ) {
                    $html .= '<li><a onclick="wp_dp_listing_pagenation_ajax(\'' . $paging_var . '\', \'' . ($total_page) . '\', \'' . ($listing_short_counter) . '\', \'' . ($ajax_filter) . '\', \'split_map\');" href="javascript:void(0);">';
                    $html .= $total_page . '</a></li>';
                }
                if ( $total_posts > 0 and $paged_id < ($total_posts / $posts_per_page) ) {
                    $html .= '<li class="pagination-next"><a onclick="wp_dp_listing_pagenation_ajax(\'' . $paging_var . '\', \'' . ($paged_id + 1) . '\', \'' . ($listing_short_counter) . '\', \'' . ($ajax_filter) . '\', \'split_map\');" href="javascript:void(0);">';
                    $html .= '<i class="icon-keyboard_arrow_right"></i></a></li>';
                } else {
                    
                }
                $html .= "</ul></div>";
                echo force_balance_tags($html);
            }
        }

        public function wp_dp_listing_filter_categories($listing_type, $category_request_val) {
            $wp_dp_listing_type_category_array = array();
            $parent_cate_array = array();
            if ( $category_request_val != '' ) {
                $category_request_val_arr = explode(",", $category_request_val);
                $category_request_val = isset($category_request_val_arr[0]) && $category_request_val_arr[0] != '' ? $category_request_val_arr[0] : '';
                $single_term = get_term_by('slug', $category_request_val, 'listing-category');
                $single_term_id = isset($single_term->term_id) && $single_term->term_id != '' ? $single_term->term_id : '0';
                $parent_cate_array = $this->wp_dp_listing_parent_categories($single_term_id);
            }
            $wp_dp_listing_type_category_array = $this->wp_dp_listing_categories_list($listing_type, $parent_cate_array);
            return $wp_dp_listing_type_category_array;
        }

        public function wp_dp_listing_parent_categories($category_id) {
            $parent_cate_array = array();
            $category_obj = get_term_by('id', $category_id, 'listing-category');
            if ( isset($category_obj->parent) && $category_obj->parent != '0' ) {
                $parent_cate_array .= $this->wp_dp_listing_parent_categories($category_obj->parent);
            }
            $parent_cate_array .= isset($category_obj->slug) ? $category_obj->slug . ',' : '';
            return $parent_cate_array;
        }

        public function wp_dp_listing_categories_list($listing_type, $parent_cate_string) {
            $cate_list_found = 0;
            $wp_dp_listing_type_category_array = array();
            if ( $parent_cate_string != '' ) {
                $category_request_val_arr = explode(",", $parent_cate_string);
                $count_arr = sizeof($category_request_val_arr);
                while ( $count_arr >= 0 ) {
                    if ( isset($category_request_val_arr[$count_arr]) && $category_request_val_arr[$count_arr] != '' ) {
                        if ( $cate_list_found == 0 ) {
                            $single_term = get_term_by('slug', $category_request_val_arr[$count_arr], 'listing-category');
                            $single_term_id = isset($single_term->term_id) && $single_term->term_id != '' ? $single_term->term_id : '0';
                            $wp_dp_category_array = get_terms('listing-category', array(
                                'hide_empty' => false,
                                'parent' => $single_term_id,
                                    )
                            );
                            if ( is_array($wp_dp_category_array) && sizeof($wp_dp_category_array) > 0 ) {
                                foreach ( $wp_dp_category_array as $dir_tag ) {
                                    $wp_dp_listing_type_category_array['cate_list'][] = $dir_tag->slug;
                                }
                                $cate_list_found ++;
                            }
                        }if ( $cate_list_found > 0 ) {
                            $wp_dp_listing_type_category_array['parent_list'][] = $category_request_val_arr[$count_arr];
                        }
                    }
                    $count_arr --;
                }
            }

            if ( $cate_list_found == 0 && $listing_type != '' ) {
                $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type", 'post_status' => 'publish', 'fields' => 'ids' ));
                $listing_type_post_id = isset($listing_type_post[0]) ? $listing_type_post[0] : 0;
                $wp_dp_listing_type_category_array['cate_list'] = get_post_meta($listing_type_post_id, 'wp_dp_listing_type_cats', true);
            }
            return $wp_dp_listing_type_category_array;
        }

        public function wp_dp_listing_body_classes($classes) {
            $classes[] = 'listing-with-full-map';
            return $classes;
        }

        public function wp_dp_listing_map_coords_obj($listing_ids) {
            $map_cords = array();

            if ( is_array($listing_ids) && sizeof($listing_ids) > 0 ) {
                foreach ( $listing_ids as $listing_id ) {
                    global $wp_dp_member_profile;

                    $Wp_dp_Locations = new Wp_dp_Locations();
                    $listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                    $listing_type_obj = get_page_by_path($listing_type, OBJECT, 'listing-type');
                    $listing_type_id = isset($listing_type_obj->ID) ? $listing_type_obj->ID : '';
                    $listing_type_id = wp_dp_wpml_lang_page_id($listing_type_id, 'listing-type');
                    $listing_location = $Wp_dp_Locations->get_location_by_listing_id($listing_id);
                    $wp_dp_listing_username = get_post_meta($listing_id, 'wp_dp_listing_username', true);
                    $wp_dp_profile_image = $wp_dp_member_profile->member_get_profile_image($wp_dp_listing_username);
                    $listing_latitude = get_post_meta($listing_id, 'wp_dp_post_loc_latitude_listing', true);
                    $listing_longitude = get_post_meta($listing_id, 'wp_dp_post_loc_longitude_listing', true);
                    $listing_marker = get_post_meta($listing_type_id, 'wp_dp_listing_type_marker_image', true);

                    if ( $listing_marker != '' ) {
                        $listing_marker = wp_get_attachment_url($listing_marker);
                    } else {
                        $listing_marker = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/map-marker.png');
                    }

                    $wp_dp_listing_is_urgent = wp_dp_check_promotion_status($listing_id, 'urgent');

                    $wp_dp_listing_price_options = get_post_meta($listing_id, 'wp_dp_listing_price_options', true);
                    $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);

                    $wp_dp_listing_type_price_switch = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
                    $wp_dp_user_reviews = get_post_meta($listing_type_id, 'wp_dp_user_reviews', true);

// end checking review on in listing type

                    $wp_dp_listing_price = '';
                    if ( $wp_dp_listing_price_options == 'price' ) {
                        $wp_dp_listing_price = get_post_meta($listing_id, 'wp_dp_listing_price', true);
                    } else if ( $wp_dp_listing_price_options == 'on-call' ) {
                        $wp_dp_listing_price = wp_dp_plugin_text_srt('wp_dp_listings_price_on_request');
                    }

                    if ( has_post_thumbnail() ) {
                        $img_atr = array( 'class' => 'img-map-info' );
                        $listing_info_img = get_the_post_thumbnail($listing_id, 'wp_dp_cs_media_5', $img_atr);
                    } else {
                        $no_image_url = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg');
                        $listing_info_img = '<img class="img-map-info" src="' . $no_image_url . '" />';
                    }

                    $listing_info_price = '';
                    if ( $wp_dp_listing_type_price_switch == 'on' && $wp_dp_listing_price != '' ) {
                        $listing_info_price .= '
						<span class="listing-price">
							<span class="new-price text-color">';

                        if ( $wp_dp_listing_price_options == 'on-call' ) {
                            $listing_info_price .= $wp_dp_listing_price;
                        } else {
                            $listing_info_price .= wp_dp_listing_price($listing_id, $wp_dp_listing_price);
                        }
                        $listing_info_price .= '	
							</span>
						</span>';
                    }
                    $listing_info_address = '';
                    if ( $listing_location != '' ) {
                        $listing_info_address = '<span class="info-address">' . $listing_location . '</span>';
                    }

                    ob_start();
                    $favourite_label = '';
                    $favourite_label = '';
                    $figcaption_div = true;
                    $book_mark_args = array(
                        'before_label' => $favourite_label,
                        'after_label' => $favourite_label,
                        'before_icon' => '<i class="icon-heart-o"></i>',
                        'after_icon' => '<i class="icon-heart5"></i>',
                    );
                    do_action('wp_dp_favourites_frontend_button', $listing_id, $book_mark_args, $figcaption_div);
                    $list_favourite = ob_get_clean();

                    $listing_urgent = '';
                    if ( $wp_dp_listing_is_urgent == 'on' ) {
                        $listing_urgent .= '
						<div class="featured-listing">
							<span class="bgcolor">' . wp_dp_plugin_text_srt('wp_dp_listings_urgent') . '</span>
						</div>';
                    }

                    $listing_member = $wp_dp_listing_username != '' && get_the_title($wp_dp_listing_username) != '' ? '<span class="info-member">' . sprintf(wp_dp_plugin_text_srt('wp_dp_listings_members'), get_the_title($wp_dp_listing_username)) . '</span>' : '';

                    $ratings_data = array(
                        'overall_rating' => 0.0,
                        'count' => 0,
                    );
                    $ratings_data = apply_filters('reviews_ratings_data', $ratings_data, $listing_id);

                    if ( $listing_latitude != '' && $listing_longitude != '' ) {
                        $map_cords[] = array(
                            'lat' => $listing_latitude,
                            'long' => $listing_longitude,
                            'id' => $listing_id,
                            'title' => get_the_title($listing_id),
                            'link' => get_permalink($listing_id),
                            'img' => $listing_info_img,
                            'price' => $listing_info_price,
                            'address' => $listing_info_address,
                            'favourite' => $list_favourite,
                            'featured' => $listing_urgent,
                            'member' => $listing_member,
                            'marker' => $listing_marker,
                        );
                    }
                }
            }
            return $map_cords;
        }

        public function wp_dp_draw_search_element_callback($draw_on_map_url = '') {
            if ( $draw_on_map_url != '' ) {
                ?>
                <div class="email-me-top">
                    <a href="<?php echo esc_url($draw_on_map_url); ?>" class="email-alert-btn draw-your-search-btn"><?php echo wp_dp_plugin_text_srt('wp_dp_listings_draw_search'); ?></a>
                </div>
                <?php
            }
        }

    }

    global $wp_dp_shortcode_split_map_frontend;
    $wp_dp_shortcode_split_map_frontend = new Wp_dp_Shortcode_Split_Map_Frontend();
}