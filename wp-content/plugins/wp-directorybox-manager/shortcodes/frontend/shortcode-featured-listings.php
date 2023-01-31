<?php
/**
 * File Type: Listings Shortcode Frontend
 */
if ( ! class_exists('Wp_dp_Shortcode_Featured_Listings_Frontend') ) {

    class Wp_dp_Shortcode_Featured_Listings_Frontend {

        /**
         * Constant variables
         */
        var $PREFIX = 'wp_dp_featured_listings';

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_shortcode($this->PREFIX, array( $this, 'wp_dp_featured_listings_shortcode_callback' ));
            add_action('wp_ajax_wp_dp_featured_listings_content', array( $this, 'wp_dp_featured_listings_content_callback' ));
            add_action('wp_ajax_nopriv_wp_dp_featured_listings_content', array( $this, 'wp_dp_featured_listings_content_callback' ));
            add_action('wp_dp_listing_pagination', array( $this, 'wp_dp_listing_pagination_callback' ), 11, 1);
        }

        /*
         * Shortcode View on Frontend
         */

        public function wp_dp_featured_listings_shortcode_callback($atts, $content = "") {
            wp_enqueue_script('wp-dp-listing-functions');
            do_action('wp_dp_notes_frontend_modal_popup');
            $icons_groups = get_option('cs_icons_groups');
            if ( ! empty($icons_groups) ) {
                foreach ( $icons_groups as $icon_key => $icon_obj ) {
                    if ( isset($icon_obj['status']) && $icon_obj['status'] == 'on' ) {
                        wp_enqueue_style('cs_icons_data_css_' . $icon_key, $icon_obj['url'] . '/style.css');
                    }
                }
            }
            $listing_short_counter = rand(10000000, 99999999);
            $page_element_size = isset($atts['wp_dp_listings_element_size']) ? $atts['wp_dp_listings_element_size'] : 100;

            if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
                echo '<div class="' . wp_dp_cs_var_page_builder_element_sizes($page_element_size, $atts) . ' ">';
            }
            wp_enqueue_script('wp-dp-split-map');
            wp_enqueue_script('flexslider');
            wp_enqueue_script('map-infobox');
            wp_enqueue_script('flexslider-mousewheel');
            wp_enqueue_script('wp-dp-bootstrap-slider');
            wp_enqueue_script('wp-dp-matchHeight-script');
            wp_enqueue_script('wp-dp-listing-functions');
            wp_enqueue_style('flexslider');
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
            <div class="wp-dp-listing-content" id="wp-dp-listing-content-<?php echo esc_html($listing_short_counter); ?>">
                <?php
                $listing_arg = array(
                    'listing_short_counter' => $listing_short_counter,
                    'atts' => $atts,
                    'content' => $content,
                    'page_url' => get_permalink(get_the_ID()),
                );
                $this->wp_dp_featured_listings_content($listing_arg);
                ?>
            </div>   
            <?php
            if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
                echo '</div>';
            }
        }

        public function wp_dp_featured_listings_content($listing_arg = '') {
            global $wpdb, $wp_dp_form_fields_frontend, $wp_dp_search_fields;

            // getting arg array from ajax
            if ( isset($_REQUEST['listing_arg']) && $_REQUEST['listing_arg'] ) {
                $listing_arg = $_REQUEST['listing_arg'];
                $listing_arg = json_decode(str_replace('\"', '"', $listing_arg));
                $listing_arg = $this->toArray($listing_arg);
            }
            if ( isset($listing_arg) && $listing_arg != '' && ! empty($listing_arg) ) {
                extract($listing_arg);
            }

            $posts_per_page = '6';

            $element_filter_arr = array();
            $content_columns = 'col-lg-12 col-md-12 col-sm-12 col-xs-12'; // if filteration not true
            $paging_var = 'paged_id';
            $default_date_time_formate = 'd-m-Y H:i:s';

            // element attributes
            $featured_listings_title = isset($atts['featured_listings_title']) ? $atts['featured_listings_title'] : '';
            $listings_subtitle = isset($atts['listings_subtitle']) ? $atts['listings_subtitle'] : '';
            $featured_listings_title_alignment = isset($atts['featured_listings_title_alignment']) ? $atts['featured_listings_title_alignment'] : '';
            $listing_view = isset($atts['listing_view']) ? $atts['listing_view'] : '';
            $listing_urgent = isset($atts['listing_urgent']) ? $atts['listing_urgent'] : 'all';
            $listing_type = isset($atts['filters_listing_type']) ? $atts['filters_listing_type'] : '';

            $listing_category = isset($atts['listing_category']) ? $atts['listing_category'] : '';


            if ( $listing_type == '' || $listing_type == 'all' ) {
                $listing_types_data = array();
                $wp_dp_listing_args = array( 'posts_per_page' => '-1', 'post_type' => 'listing-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => false );
                $cust_query = get_posts($wp_dp_listing_args);
                if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                    foreach ( $cust_query as $wp_dp_listing_type ) {
                        $listing_types_data[] = $wp_dp_listing_type->post_name;
                    }
                }
                $listing_type = implode(',', $listing_types_data);
            }
            $tab_counter = rand(12345, 54321);
            $posts_per_page = isset($atts['posts_per_page']) ? $atts['posts_per_page'] : '6';
            $element_listing_top_category = isset($atts['listing_top_category']) ? $atts['listing_top_category'] : 'no';
            $element_listing_top_category_count = isset($atts['listing_top_category_count']) ? $atts['listing_top_category_count'] : '5';
            $wp_dp_featured_listings_seperator_style = isset($atts['wp_dp_featured_listings_seperator_style']) ? $atts['wp_dp_featured_listings_seperator_style'] : '6';

            $listing_slider_switch = isset($atts['listing_slider_switch']) ? $atts['listing_slider_switch'] : 'yes';





            $wp_dp_featured_listing_element_title_color = isset($atts['wp_dp_featured_listing_element_title_color']) ? $atts['wp_dp_featured_listing_element_title_color'] : '';
            $wp_dp_featured_listing_element_subtitle_color = isset($atts['wp_dp_featured_listing_element_subtitle_color']) ? $atts['wp_dp_featured_listing_element_subtitle_color'] : '';
            $element_title_color = '';
            if ( isset($wp_dp_featured_listing_element_title_color) && $wp_dp_featured_listing_element_title_color != '' ) {
                $element_title_color = ' style="color:' . $wp_dp_featured_listing_element_title_color . ' ! important"';
            }
            $element_subtitle_color = '';
            if ( isset($wp_dp_featured_listing_element_subtitle_color) && $wp_dp_featured_listing_element_subtitle_color != '' ) {
                $element_subtitle_color = ' style="color:' . $wp_dp_featured_listing_element_subtitle_color . ' ! important"';
            }

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

            $element_filter_arr[] = array(
                'key' => 'wp_dp_promotion_home-featured',
                'value' => 'on',
                'compare' => '=',
            );

            $element_filter_arr[] = array( 'relation' => 'OR',
                array(
                    'key' => 'wp_dp_promotion_home-featured_expiry',
                    'value' => date('Y-m-d'),
                    'compare' => '>',
                ),
                array(
                    'key' => 'wp_dp_promotion_home-featured_expiry',
                    'value' => 'unlimitted',
                    'compare' => '=',
                )
            );

            if ( $listing_urgent == 'only-urgent' ) {
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

            $filter_align = '';
            if ( isset($listing_slider_switch) && $listing_slider_switch == 'no' ) {

                $filter_align = ' text-center';
            }


            $paged = isset($_REQUEST[$paging_var]) ? $_REQUEST[$paging_var] : 1;
            $args = array(
                'posts_per_page' => $posts_per_page,
                'post_type' => 'listings',
                'post_status' => 'publish',
                'fields' => 'ids', // only load ids
                'meta_query' => array(
                    $element_filter_arr,
                ),
            );

            if ( $element_listing_top_category == 'yes' ) {
                $element_top_cate_filter_arr[] = array(
                    'key' => 'wp_dp_listing_posted',
                    'value' => strtotime(current_time($default_date_time_formate, 1)),
                    'compare' => '<=',
                );

                $element_top_cate_filter_arr[] = array(
                    'key' => 'wp_dp_listing_expired',
                    'value' => strtotime(current_time($default_date_time_formate, 1)),
                    'compare' => '>=',
                );

                $element_top_cate_filter_arr[] = array(
                    'key' => 'wp_dp_listing_status',
                    'value' => 'active',
                    'compare' => '=',
                );

                // check if member not inactive
                $element_top_cate_filter_arr[] = array(
                    'key' => 'listing_member_status',
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

                $element_top_cate_filter_arr[] = array(
                    'key' => 'wp_dp_promotion_home-featured',
                    'value' => 'on',
                    'compare' => '=',
                );

                $element_top_cate_filter_arr[] = array( 'relation' => 'OR',
                    array(
                        'key' => 'wp_dp_promotion_home-featured_expiry',
                        'value' => date('Y-m-d'),
                        'compare' => '>',
                    ),
                    array(
                        'key' => 'wp_dp_promotion_home-featured_expiry',
                        'value' => 'unlimitted',
                        'compare' => '=',
                    )
                );

                if ( $listing_urgent == 'only-urgent' ) {
                    $element_top_cate_filter_arr[] = array(
                        'key' => 'wp_dp_promotion_urgent',
                        'value' => 'on',
                        'compare' => '=',
                    );

                    $element_top_cate_filter_arr[] = array( 'relation' => 'OR',
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
                    $element_top_cate_filter_arr = wp_dp_listing_visibility_query_args($element_top_cate_filter_arr);
                }

                $listing_type_category_name = 'wp_dp_listing_category';   // category_fieldname in db and request
                if ( isset($_REQUEST[$listing_type_category_name]) && $_REQUEST[$listing_type_category_name] != '' ) {
                    $dropdown_query_str_var_name = explode(",", $_REQUEST[$listing_type_category_name]);
                    $cate_filter_multi_arr['relation'] = 'OR';
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
                    'fields' => 'ids', // only load ids
                    'meta_query' => array(
                        $element_top_cate_filter_arr,
                    ),
                );
            }

            // top categories
            if ( $element_listing_top_category == 'yes' ) {

                $listing_top_categries_loop_obj = wp_dp_get_cached_obj('listing_result_cached_top_categries_loop_obj', $top_categries_args, 12, false, 'wp_query');
                $listing_top_categries_loop_obj->found_posts;
            }
            // arrange excluded ids for result
            if ( isset($listing_top_categries_loop_obj->posts) && is_array($listing_top_categries_loop_obj->posts) && ! empty($listing_top_categries_loop_obj->posts) ) {
                if ( ! empty($all_post_ids) ) {

                    $all_post_ids = empty($all_post_ids) ? array( 0 ) : $all_post_ids;
                    $args['post__in'] = $all_post_ids;
                } else {
                    $args['post__not_in'] = $listing_top_categries_loop_obj->posts;
                }
            }
            $listing_loop_obj = wp_dp_get_cached_obj('listing_result_cached_loop_obj', $args, 12, false, 'wp_query');
            $listing_totnum = $listing_loop_obj->found_posts;

            if ( $listing_view == 'single' ) {
                if ( $featured_listings_title != '' || $listings_subtitle != '' ) {
                    ?>
                    <div class="element-title <?php echo ($featured_listings_title_alignment); ?>">
                        <?php if ( $featured_listings_title ) { ?>
                            <h2<?php echo wp_dp_allow_special_char($element_title_color); ?>><?php echo esc_html($featured_listings_title); ?></h2>
                        <?php } ?>
                        <?php if ( $listings_subtitle ) { ?>
                            <p<?php echo wp_dp_allow_special_char($element_subtitle_color); ?>><?php echo esc_html($listings_subtitle); ?></p>
                            <?php
                        }
                        if ( isset($wp_dp_featured_listings_seperator_style) && ! empty($wp_dp_featured_listings_seperator_style) ) {
                            $wp_dp_featured_listings_seperator_html = '';
                            if ( $wp_dp_featured_listings_seperator_style == 'classic' ) {
                                $wp_dp_featured_listings_seperator_html .='<div class="classic-separator ' . $featured_listings_title_alignment . '"><span></span></div>';
                            }
                            if ( $wp_dp_featured_listings_seperator_style == 'zigzag' ) {
                                $wp_dp_featured_listings_seperator_html .='<div class="separator-zigzag ' . $featured_listings_title_alignment . '">
                                            <figure><img src="' . trailingslashit(wp_dp::plugin_url()) . 'assets/images/zigzag-img1.png" alt=""/></figure>
                                        </div>';
                            }
                            echo force_balance_tags($wp_dp_featured_listings_seperator_html);
                        }
                        $filters_class = 'modern-filters';
                        ?>
                    </div>
                    <ul id="filters" class="clearfix <?php echo esc_html($filters_class); ?>">
                        <?php
                        if ( isset($listing_category) && ! empty($listing_category) ) {
                            $listing_category = explode(',', $listing_category);
                            $active_tab = 'first_active';
                            $count = 1;
                            foreach ( $listing_category as $category_slug ) {
                                $term_obj = get_term_by('slug', $category_slug, 'listing-category');
                                if ( is_object($term_obj) ) {

                                    $current_tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : '';
                                    if ( isset($current_tab) && $current_tab == $category_slug ) {
                                        $active_tab = 'active';
                                    } else {
                                        if ( ! isset($_REQUEST['tab']) ) {
                                            if ( $active_tab == 'first_active' ) {
                                                $active_tab = 'active';
                                            }
                                        }
                                    }
                                    ?>
                                    <li class="<?php echo esc_attr($category_slug); ?> <?php echo esc_html($active_tab); ?>"><span class="filter"><a data-toggle="tab" href="#tab-<?php echo intval($tab_counter . $count); ?>"><?php echo esc_html($term_obj->name); ?></a></span></li>
                                    <?php
                                    $active_tab = '';
                                    $count ++;
                                }
                            }
                        }
                        ?>
                    </ul>
                    <?php
                }
                set_query_var('listing_loop_obj', $listing_loop_obj);
                set_query_var('listing_short_counter', $listing_short_counter);
                set_query_var('atts', $atts);
                wp_dp_get_template_part('listing', 'featured-single', 'listings');
            } else {
                wp_enqueue_style('swiper');
                wp_enqueue_script('swiper');


                if ( ! empty($featured_listings_title) || ! empty($listings_subtitle) ) {
                    ?>
                    <div class="element-title <?php echo ($featured_listings_title_alignment); ?>">
                        <?php if ( $featured_listings_title ) { ?>
                            <h2<?php echo wp_dp_allow_special_char($element_title_color); ?>><?php echo esc_html($featured_listings_title); ?></h2>
                        <?php } ?>
                        <?php if ( $listings_subtitle ) { ?>
                            <strong<?php echo wp_dp_allow_special_char($element_subtitle_color); ?>><?php echo esc_html($listings_subtitle); ?></strong>
                            <?php
                        }
                        if ( isset($wp_dp_featured_listings_seperator_style) && ! empty($wp_dp_featured_listings_seperator_style) ) {
                            $wp_dp_featured_listings_seperator_html = '';
                            if ( $wp_dp_featured_listings_seperator_style == 'classic' ) {
                                $wp_dp_featured_listings_seperator_html .='<div class="classic-separator ' . $featured_listings_title_alignment . '"><span></span></div>';
                            }
                            if ( $wp_dp_featured_listings_seperator_style == 'zigzag' ) {
                                $wp_dp_featured_listings_seperator_html .='<div class="separator-zigzag ' . $featured_listings_title_alignment . '">
                                            <figure><img src="' . trailingslashit(wp_dp::plugin_url()) . 'assets/images/zigzag-img1.png" alt=""/></figure>
                                        </div>';
                            }
                            echo force_balance_tags($wp_dp_featured_listings_seperator_html);
                        }
                        ?>
                    </div>
                    <?php
                }
                $filters_class = 'modern-filters';

                if ( isset($listing_slider_switch) && $listing_slider_switch == 'no' ) {

                    $filters_class = 'main-filters';
                }
                ?>


                <ul id="filters" class="clearfix <?php echo esc_html($filters_class); ?><?php echo wp_dp_cs_allow_special_char($filter_align); ?>">

                    <?php
                    if ( isset($listing_type) && ! empty($listing_type) ) {
                        $listing_type = explode(',', $listing_type);
                        $active_tab = 'active';
                        $count = 1;
                        foreach ( $listing_type as $type_slug ) {
                            $type_obj = get_page_by_path($type_slug, OBJECT, 'listing-type');
                            if ( is_object($type_obj) ) {
                                ?>
                                <li class="tab<?php echo intval($tab_counter . $count); ?> <?php echo esc_html($active_tab); ?>"><span><a data-toggle="tab" href="#tab<?php echo intval($tab_counter . $count); ?>">
                                            <?php echo esc_html($type_obj->post_title); ?>
                                        </a></span></li>
                                <?php
                                $active_tab = '';
                                $count ++;
                            }
                        }
                    }
                    ?>

                </ul>   
                <div class="listing-grid-slider directorybox-listing">

                    <div class="tab-content clearfix">
                        <?php
                        $count = 1;
                        foreach ( $listing_type as $type_slug ) {
                            if ( is_object($type_obj) ) {
                                $type_args = $args;
                                $type_args['meta_query'][] = array(
                                    'key' => 'wp_dp_listing_type',
                                    'value' => $type_slug,
                                    'compare' => '=',
                                );
                                $listing_short_counter = rand(12345, 54321);
                                $listing_loop_obj = wp_dp_get_cached_obj('listing_result_cached_loop_obj', $type_args, 12, false, 'wp_query');
                                $listing_found_count = $listing_loop_obj->post_count;
                                $slider_flag = false;
                                $swiper_class = '';
                                $cab_content_row = ' class="row"';
                                $div_swipper_wrapper = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
                                if ( $listing_found_count > 3 && $listing_slider_switch == 'yes' ) {
                                    $slider_flag = true;
                                    $swiper_class = 'swiper-container';
                                    $div_swipper_wrapper = 'swiper-wrapper';
                                    $cab_content_row = '';
                                }

                                $type_obj = get_page_by_path($type_slug, OBJECT, 'listing-type');
                                $current_tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : '';
                                $active_class = ( $count == 1 ) ? 'active' : '';
                                if ( isset($current_tab) && $current_tab == $type_slug ) {
                                    $active_class = 'active';
                                }
                                ?>
                                <div class="tab-pane fade in  <?php echo esc_attr($active_class); ?>" id="tab<?php echo intval($tab_counter . $count); ?>">
                                    <?php
                                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                                            array(
                                                'return' => false,
                                                'cust_name' => '',
                                                'classes' => 'listing-counter',
                                                'std' => $listing_short_counter,
                                            )
                                    );
                                    ?>
                                    <div style="display:none" id='listing_arg<?php echo absint($listing_short_counter); ?>'>
                                        <?php $listing_arg['listing_short_counter'] = $listing_short_counter; ?>
                                        <?php echo json_encode($listing_arg); ?>
                                    </div>
                                    <div id="listing-tab-content-<?php echo esc_attr($listing_short_counter); ?>"<?php echo wp_dp_cs_allow_special_char($cab_content_row); ?>>
                                        <?php if ( $slider_flag ) { ?>
                                            <div class="<?php echo wp_dp_cs_allow_special_char($swiper_class); ?>">
                                            <?php } ?>
                                            <div class="<?php echo wp_dp_cs_allow_special_char($div_swipper_wrapper); ?>">

                                                <?php if ( ! $slider_flag ) { ?>
                                                    <div class="row">
                                                        <?php
                                                    }

                                                    set_query_var('listing_loop_obj', $listing_loop_obj);
                                                    set_query_var('listing_short_counter', $listing_short_counter);
                                                    set_query_var('atts', $atts);
                                                    set_query_var('slider_flag', $slider_flag);
                                                    set_query_var('element_listing_top_category', $element_listing_top_category);
                                                    if ( $element_listing_top_category == 'yes' ) {
                                                        set_query_var('listing_top_categries_loop_obj', $listing_top_categries_loop_obj);
                                                    }
                                                    wp_dp_get_template_part('listing', 'featured-multiple', 'listings');
                                                    ?>
                                                </div>

                                                <?php if ( ! $slider_flag ) { ?>
                                                </div>
                                            <?php } ?>


                                            <?php if ( $slider_flag ) { ?>
                                            </div>
                                        <?php } ?>

                                        <?php if ( $slider_flag ) { ?>
                                            <div class="swiper-button-next"><i class="icon-chevron-thin-right"></i></div>
                                            <div class="swiper-button-prev"><i class="icon-chevron-thin-left"></i></div>
                                            <?php } ?>


                                    </div>
                                </div>
                                <?php wp_reset_postdata(); ?>
                                <?php
                                $count ++;
                            }
                        }
                        ?>
                    </div>
                    <?php if ( isset($slider_flag) ) { ?>
                        <script type="text/javascript">
                            jQuery(document).ready(function () {


                                if ("" != jQuery(".listing-grid-slider .swiper-container").length) {
                                    var mySlider = new Swiper(".listing-grid-slider .swiper-container", {
                                        slidesPerView: 3,
                                        paginationClickable: !1,
                                        nextButton: ".swiper-button-next",
                                        prevButton: ".swiper-button-prev",
                                        spaceBetween: 30,
                                        onInit: function (mySlider) {
                                            jQuery.fn.matchHeight._update();
                                            jQuery('.modern-filters li span a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                                                var activeTab = $(e.target).text();
                                                var previousTab = $(e.relatedTarget).text();
                                                mySlider.update();
                                            });
                                        },
                                        breakpoints: {
                                            991: {
                                                slidesPerView: 2,
                                                spaceBetween: 20
                                            },
                                            600: {
                                                slidesPerView: 1,
                                                spaceBetween: 15
                                            }
                                        }
                                    });
                                    var elementWidth = $(".wp-dp-listing-content").width();
                                    if (elementWidth < 992 && elementWidth > 600)
                                        mySlider.params.slidesPerView = 2;
                                    if (elementWidth < 600)
                                        mySlider.params.slidesPerView = 1;

                                }

                            });
                        </script>
                    <?php } ?>
                </div>
                <?php
            }
        }

    }

    global $wp_dp_shortcode_listings_filters_frontend;
    $wp_dp_shortcode_listings_filters_frontend = new Wp_dp_Shortcode_Featured_Listings_Frontend();
}
    