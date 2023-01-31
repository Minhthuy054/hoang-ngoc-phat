<?php
/**
 * File Type: Listings Shortcode Frontend
 */
if ( ! class_exists('Wp_dp_Shortcode_Listings_with_Categories_Frontend') ) {

    class Wp_dp_Shortcode_Listings_with_Categories_Frontend {

        /**
         * Constant variables
         */
        var $PREFIX = 'wp_dp_listings_with_categories';

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_shortcode($this->PREFIX, array( $this, 'wp_dp_listings_shortcode_callback' ));
            add_action('wp_ajax_wp_dp_listing_by_categories_filters_content', array( $this, 'wp_dp_listings_filters_content_callback' ));
            add_action('wp_ajax_nopriv_wp_dp_listing_by_categories_filters_content', array( $this, 'wp_dp_listings_filters_content_callback' ));
            add_action('wp_dp_listing_pagination', array( $this, 'wp_dp_listing_pagination_callback' ), 11, 1);
        }

        /*
         * Shortcode View on Frontend
         */

        public function wp_dp_listings_shortcode_callback($atts, $content = "") {
            wp_enqueue_script('wp-dp-listing-functions');
            wp_enqueue_script('jquery-mixitup');
            wp_enqueue_script('wp-dp-matchHeight-script');
            wp_enqueue_script('wp-dp-bootstrap-slider');
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
            do_action('listing_checks_enquire_lists_submit');
            do_action('wp_dp_listing_compare_sidebar');
            do_action('wp_dp_listing_enquiries_sidebar');
            ?>
            <div class="wp-dp-listing-content" id="wp-dp-listing-content-<?php echo esc_html($listing_short_counter); ?>">
                <?php
                $listing_arg = array(
                    'listing_short_counter' => $listing_short_counter,
                    'atts' => $atts,
                    'content' => $content,
                    'page_url' => get_permalink(get_the_ID()),
                );
                $this->wp_dp_listings_filters_content($listing_arg);
                ?>
            </div>   
            <?php
            if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
                echo '</div>';
            }

            $wp_dp_cs_inline_script = 'jQuery(document).ready(function($) {
                var wrapHeight;
                $(window).load(function() {
                    wrapHeight=$(".directorybox-listing .tab-content > .tab-pane.active").outerHeight();
                    $(".directorybox-listing .tab-content").height(wrapHeight);
                    $(".directorybox-listing").addClass("tabs-loaded");
                });
                $(window).resize(function(){
                    wrapHeight=$(".directorybox-listing .tab-content > .tab-pane.active").outerHeight();
                    $(".directorybox-listing.tabs-loaded .tab-content").height(wrapHeight);
                });
                $(\'.directorybox-listing a[data-toggle="tab"]\').on("shown.bs.tab", function (e) {
                   e.target
                   e.relatedTarget
                   var target=$(e.target).attr("href");
                   var prevTarget=$(e.relatedTarget).attr("href");
                   var wrapHeight=$(target).outerHeight();
                   $(".directorybox-listing .tab-content").height(wrapHeight);
                   $(prevTarget).addClass("active-moment").find(".animated").removeClass("slideInUp").addClass("fadeOutDown");
                   $(target).find(".animated").addClass("slideInUp").removeClass("fadeOutDown");
                   setTimeout(function(){
                      $(prevTarget).removeClass("active-moment").find(".animated").removeClass("fadeOutDown");
                    }, 800);
                    if($(".tab-pane").length>0){
                        $(target).find(".listing-grid.v1").matchHeight._update();
                        $(target).find(".listing-grid.modern.v2 .text-holder").matchHeight._update();
                        $(target).find(".listing-grid.modern.v1 .text-holder").matchHeight._update();
                    }
                });
            });';
            wp_dp_cs_inline_enqueue_script($wp_dp_cs_inline_script, 'wp-dp-custom-inline');
        }

        public function wp_dp_listings_filters_content($listing_arg = '') {
            global $wpdb, $wp_dp_form_fields_frontend, $wp_dp_search_fields;
            wp_enqueue_script('wp-dp-matchHeight-script');
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
            $pagination = 'no';
            $element_filter_arr = array();
            $content_columns = 'col-lg-12 col-md-12 col-sm-12 col-xs-12'; // if filteration not true
            $paging_var = 'paged_id';
            $default_date_time_formate = 'd-m-Y H:i:s';
            // element attributes
            $listing_view = isset($atts['listing_view']) ? $atts['listing_view'] : '';
            $listing_listing_featured = isset($atts['listing_featured']) ? $atts['listing_featured'] : 'all';
            $listing_type = isset($atts['listing_type']) ? $atts['listing_type'] : '';
            $listing_category = isset($atts['listing_category']) ? $atts['listing_category'] : '';
            $posts_per_page = isset($atts['posts_per_page']) ? $atts['posts_per_page'] : '6';
            $pagination = isset($atts['pagination']) ? $atts['pagination'] : 'no';

            if ( $listing_category == '' || $listing_category == 'all' || $listing_category == 'Array' ) {
                $listing_type_id = 0;
                if ( $post = get_page_by_path($listing_type, OBJECT, 'listing-type') ) {
                    $listing_type_id = $post->ID;
                }
                $listing_type_id = wp_dp_wpml_lang_page_id($listing_type_id, 'listing-type');
                $listing_type_cats = get_post_meta($listing_type_id, 'wp_dp_listing_type_cats', true);
                if ( is_array($listing_type_cats) && ! empty($listing_type_cats) ) {
                    $listing_category = implode(',', $listing_type_cats);
                }
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
                'key' => 'wp_dp_listing_type',
                'value' => $listing_type,
                'compare' => '=',
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
//
//
//            $element_filter_arr[] = array(
//                'key' => 'wp_dp_promotion_urgent',
//                'value' => 'on',
//                'compare' => '=',
//            );
//
//            $element_filter_arr[] = array( 'relation' => 'OR',
//                array(
//                    'key' => 'wp_dp_promotion_urgent_expiry',
//                    'value' => date('Y-m-d'),
//                    'compare' => '>',
//                ),
//                array(
//                    'key' => 'wp_dp_promotion_urgent_expiry',
//                    'value' => 'unlimitted',
//                    'compare' => '=',
//                )
//            );



            if ( function_exists('wp_dp_listing_visibility_query_args') ) {
                $element_filter_arr = wp_dp_listing_visibility_query_args($element_filter_arr);
            }

            $paged = isset($_REQUEST[$paging_var]) ? $_REQUEST[$paging_var] : 1;
            $args = array(
                'posts_per_page' => $posts_per_page,
                'paged' => $paged,
                'post_type' => 'listings',
                'post_status' => 'publish',
                'fields' => 'ids', // only load ids
                'orderby' => 'ID',
                'order' => 'DESC',
                'meta_query' => array(
                    $element_filter_arr,
                ),
            );

            //wp_dp_listing_category


            $show_more_listing = '';
            $tab_counter = rand(12345, 54321);
            ?>
            <div class="directorybox-listing <?php echo esc_html($show_more_listing); ?>">
                <?php
                $categories_listings_title = isset($atts['categories_listings_title']) ? $atts['categories_listings_title'] : '';
                $listings_subtitle = isset($atts['listings_subtitle']) ? $atts['listings_subtitle'] : '';
                $listings_filters_alagnment = isset($atts['listings_filters_alagnment']) ? $atts['listings_filters_alagnment'] : '';

                $wp_dp_listings_categories_seperator = isset($atts['wp_dp_listings_with_categories_seperator_style']) ? $atts['wp_dp_listings_with_categories_seperator_style'] : '';
                $show_more_button = isset($atts['show_more_button']) ? $atts['show_more_button'] : '';
                $show_more_button_url = isset($atts['show_more_button_url']) ? $atts['show_more_button_url'] : '';

                $wp_dp_listing_categories_element_title_color = isset($atts['wp_dp_listing_categories_element_title_color']) ? $atts['wp_dp_listing_categories_element_title_color'] : '';
                $wp_dp_listing_categories_element_subtitle_color = isset($atts['wp_dp_listing_categories_element_subtitle_color']) ? $atts['wp_dp_listing_categories_element_subtitle_color'] : '';
                $element_title_color = '';
                if ( isset($wp_dp_listing_categories_element_title_color) && $wp_dp_listing_categories_element_title_color != '' ) {
                    $element_title_color = ' style="color:' . $wp_dp_listing_categories_element_title_color . ' ! important"';
                }
                $element_subtitle_color = '';
                if ( isset($wp_dp_listing_categories_element_subtitle_color) && $wp_dp_listing_categories_element_subtitle_color != '' ) {
                    $element_subtitle_color = ' style="color:' . $wp_dp_listing_categories_element_subtitle_color . ' ! important"';
                }
                ?>
                <div class="element-title <?php echo ($listings_filters_alagnment); ?>">
                    <?php if ( $categories_listings_title != '' ) { ?>
                        <h2<?php echo wp_dp_allow_special_char($element_title_color); ?>><?php echo esc_html($categories_listings_title); ?></h2>
                    <?php } ?>
                    <?php if ( $listings_subtitle != '' ) { ?>
                        <p<?php echo wp_dp_allow_special_char($element_subtitle_color); ?>><?php echo esc_html($listings_subtitle); ?></p>
                        <?php
                    }
                    if ( isset($wp_dp_listings_categories_seperator) && ! empty($wp_dp_listings_categories_seperator) ) {
                        $wp_dp_cat_listings_seperator_html = '';
                        if ( $wp_dp_listings_categories_seperator == 'classic' ) {
                            $wp_dp_cat_listings_seperator_html .='<div class="classic-separator ' . $listings_filters_alagnment . '"><span></span></div>';
                        }
                        if ( $wp_dp_listings_categories_seperator == 'zigzag' ) {
                            $wp_dp_cat_listings_seperator_html .='<div class="separator-zigzag ' . $listings_filters_alagnment . '">
                                            <figure><img src="' . trailingslashit(wp_dp::plugin_url()) . 'assets/images/zigzag-img1.png" alt=""/></figure>
                                        </div>';
                        }
                        echo force_balance_tags($wp_dp_cat_listings_seperator_html);
                    }
                    ?>
                    <?php
                    $filters_class = 'modern-filters';
                    ?>
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
                    <?php //if ( $show_more_button == 'yes' && $show_more_button_url != '' && $listing_view != 'grid-modern' ) { ?>
<!--                        <a href="<?php// echo esc_url($show_more_button_url); ?>" class="show-more-listing "><?php// echo wp_dp_plugin_text_srt('wp_dp_listfilter_showmore'); ?></a>-->
                    <?php //} ?>
                </div>
                <?php $listing_short_counter = rand(12345, 54321); ?>
                <div class="row">
                    <div class="<?php echo esc_html($content_columns); ?>">
                        <div class="tab-content clearfix">
                            <?php
                            if ( isset($listing_category) && ! empty($listing_category) ) {
                                $count = 1;
                                foreach ( $listing_category as $category_slug ) {
                                    $listing_short_counter = rand(12345, 54321);
                                    if ( is_object($term_obj) ) {
                                        $category_args = $args;
                                        $category_args['meta_query'][0][] = array(
                                            'key' => 'wp_dp_listing_category',
                                            'value' => $category_slug,
                                            'compare' => 'LIKE',
                                        );

                                        $listing_loop_obj = wp_dp_get_cached_obj('listing_result_cached_loop_obj', $category_args, 12, false, 'wp_query');
                                        $listing_found_count = $listing_loop_obj->found_posts;

                                        $term_obj = get_term_by('slug', $category_slug, 'listing-category');
                                        $current_tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : '';
                                        if ( ! isset($_REQUEST['tab']) ) {
                                            $active_class = ( $count == 1 ) ? 'active' : '';
                                        }
                                        if ( isset($current_tab) && $current_tab == $category_slug ) {
                                            $active_class = 'active';
                                        }
                                        ?>
                                        <div class="tab-pane in <?php echo esc_attr($active_class); ?>" id="tab-<?php echo intval($tab_counter . $count); ?>">
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
                                            <div id="listing-tab-content-<?php echo esc_attr($listing_short_counter); ?>">
                                                <?php
                                                
//                                                    echo '<pre>';
//                                                    print_r($listing_loop_obj);
//                                                    echo '</pre>';
                                                
                                                
                                                set_query_var('listing_loop_obj', $listing_loop_obj);
                                                set_query_var('listing_short_counter', $listing_short_counter);
                                                set_query_var('atts', $atts);
                                                wp_dp_get_template_part('listing', 'filters-grid-modern', 'listings');
                                                // apply paging
                                                $paging_args = array(
                                                    'listing_view' => $listing_view,
                                                    'tab' => $category_slug,
                                                    'total_posts' => $listing_found_count,
                                                    'posts_per_page' => $posts_per_page,
                                                    'paging_var' => $paging_var,
                                                    'show_pagination' => $pagination,
                                                    'listing_short_counter' => $listing_short_counter,
                                                );
                                                $this->wp_dp_listing_pagination_callback($paging_args);
                                                ?>
                                            </div>
                                        </div>
                                        <?php wp_reset_postdata(); ?>
                                        <?php
                                        $count ++;
                                        $active_class = '';
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        public function wp_dp_listings_filters_content_callback($listing_arg = '') {
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
            $pagination = 'no';
            $element_filter_arr = array();
            $content_columns = 'col-lg-12 col-md-12 col-sm-12 col-xs-12'; // if filteration not true
            $paging_var = 'paged_id';
            $default_date_time_formate = 'd-m-Y H:i:s';
            // element attributes
            $listing_listing_featured = isset($atts['listing_featured']) ? $atts['listing_featured'] : 'all';
            $listing_type = isset($atts['listing_type']) ? $atts['listing_type'] : '';
            $posts_per_page = isset($atts['posts_per_page']) ? $atts['posts_per_page'] : '6';
            $pagination = isset($atts['pagination']) ? $atts['pagination'] : 'no';

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


            $element_filter_arr[] = array(
                'key' => 'wp_dp_listing_type',
                'value' => $listing_type,
                'compare' => '=',
            );
            if ( isset($_REQUEST['tab']) && $_REQUEST['tab'] != '' ) {
                $element_filter_arr[] = array(
                    'key' => 'wp_dp_listing_category',
                    'value' => $_REQUEST['tab'],
                    'compare' => 'LIKE',
                );
            }

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


            $paged = isset($_REQUEST[$paging_var]) ? $_REQUEST[$paging_var] : 1;
            $args = array(
                'posts_per_page' => $posts_per_page,
                'paged' => $paged,
                'post_type' => 'listings',
                'post_status' => 'publish',
                'fields' => 'ids', // only load ids
                'orderby' => 'ID',
                'order' => 'DESC',
                'meta_query' => array(
                    $element_filter_arr,
                ),
            );

            $tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : 'flats';
            $listing_loop_obj = wp_dp_get_cached_obj('listing_result_cached_loop_obj', $args, 12, false, 'wp_query');
            $listing_found_count = $listing_loop_obj->found_posts;
            set_query_var('listing_loop_obj', $listing_loop_obj);
            set_query_var('listing_short_counter', $listing_short_counter);
            set_query_var('atts', $atts);
            wp_dp_get_template_part('listing', 'filters-grid', 'listings');
            // apply paging
            $paging_args = array(
                'tab' => $tab,
                'total_posts' => $listing_found_count,
                'posts_per_page' => $posts_per_page,
                'paging_var' => $paging_var,
                'show_pagination' => $pagination,
                'listing_short_counter' => $listing_short_counter,
            );
            $this->wp_dp_listing_pagination_callback($paging_args);
            wp_reset_postdata();
            wp_die();
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

        public function wp_dp_listing_pagination_callback($args) {
            global $wp_dp_form_fields_frontend;
            $total_posts = '';
            $posts_per_page = '5';
            $paging_var = 'paged_id';
            $show_pagination = 'yes';
            $tab = 'for-sale';
            $listing_short_counter = '';

            extract($args);
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
                $loop_start = $paged_id - 2;

                $loop_end = $paged_id + 2;

                if ( $paged_id < 3 ) {

                    $loop_start = 1;

                    if ( $total_page < 5 )
                        $loop_end = $total_page;
                    else
                        $loop_end = 5;
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
                            'extra_atr' => 'onchange="wp_dp_listing_filters_content(\'' . $listing_short_counter . '\');"',
                        )
                );
                $html .= '<div class="row"><div class="portfolio grid-fading animated col-lg-12 col-md-12 col-sm-12 col-xs-12 page-nation"><ul class="pagination pagination-large">';
                if ( $paged_id > 1 ) {
                    $html .= '<li><a onclick="wp_dp_listing_by_category_filters_pagenation_ajax(\'' . $paging_var . '\', \'' . ($paged_id - 1) . '\', \'' . ($listing_short_counter) . '\' , \'' . ($tab) . '\');" href="javascript:void(0);">';
                    $html .= wp_dp_plugin_text_srt('wp_dp_shortcode_filter_prev') . '</a></li>';
                }
                if ( $paged_id > 3 and $total_page > 5 ) {


                    $html .= '<li><a onclick="wp_dp_listing_by_category_filters_pagenation_ajax(\'' . $paging_var . '\', \'' . (1) . '\', \'' . ($listing_short_counter) . '\', \'' . ($tab) . '\');" href="javascript:void(0);">';
                    $html .= '1</a></li>';
                }
                if ( $paged_id > 4 and $total_page > 6 ) {
                    $html .= '<li class="no-border"><a>. . .</a><li>';
                }

                if ( $total_page > 1 ) {

                    for ( $i = $loop_start; $i <= $loop_end; $i ++ ) {

                        if ( $i <> $paged_id ) {

                            $html .= '<li><a onclick="wp_dp_listing_by_category_filters_pagenation_ajax(\'' . $paging_var . '\', \'' . ($i) . '\', \'' . ($listing_short_counter) . '\', \'' . ($tab) . '\');" href="javascript:void(0);">';
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
                    $html .= '<li><a onclick="wp_dp_listing_by_category_filters_pagenation_ajax(\'' . $paging_var . '\', \'' . ($total_page) . '\', \'' . ($listing_short_counter) . '\', \'' . ($tab) . '\');" href="javascript:void(0);">';
                    $html .= $total_page . '</a></li>';
                }
                if ( $total_posts > 0 and $paged_id < ($total_posts / $posts_per_page) ) {
                    $html .= '<li><a onclick="wp_dp_listing_by_category_filters_pagenation_ajax(\'' . $paging_var . '\', \'' . ($paged_id + 1) . '\', \'' . ($listing_short_counter) . '\', \'' . ($tab) . '\');" href="javascript:void(0);">';
                    $html .= wp_dp_plugin_text_srt('wp_dp_shortcode_filter_next') . '</a></li>';
                }
                $html .= "</ul></div></div>";
                echo force_balance_tags($html);
            }
        }

    }

    global $wp_dp_shortcode_listings_filters_frontend;
    $wp_dp_shortcode_listings_filters_frontend = new Wp_dp_Shortcode_Listings_with_Categories_Frontend();
}
    