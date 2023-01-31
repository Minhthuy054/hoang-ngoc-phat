<?php

/**
 * File Type: Searchs Shortcode Frontend
 */
if ( ! class_exists('Wp_dp_Shortcode_Listing_Types_Categories_front') ) {

    class Wp_dp_Shortcode_Listing_Types_Categories_front {

        /**
         * Constant variables
         */
        var $PREFIX = 'listing_types_categories';

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_shortcode($this->PREFIX, array( $this, 'wp_dp_listing_types_categories_shortcode_callback' ));
        }

        /*
         * Shortcode View on Frontend
         */

        function combine_pt_section($keys, $values) {
            $result = array();
            foreach ( $keys as $i => $k ) {
                $result[$k][] = $values[$i];
            }
            array_walk($result, function($v){$v = (count($v) == 1)? array_pop($v): $v;});
            return $result;
        }

        public function wp_dp_listing_types_categories_shortcode_callback($atts, $content = "") {
            global $current_user, $wp_dp_plugin_options;
            $default_date_time_formate = 'd-m-Y H:i:s';
            wp_enqueue_script('wp-dp-bootstrap-slider');
            wp_enqueue_script('wp-dp-matchHeight-script');
            $icons_groups = get_option('cs_icons_groups');
            if ( ! empty($icons_groups) ) {
                foreach ( $icons_groups as $icon_key => $icon_obj ) {
                    if ( isset($icon_obj['status']) && $icon_obj['status'] == 'on' ) {
                        wp_enqueue_style('cs_icons_data_css_' . $icon_key, $icon_obj['url'] . '/style.css');
                    }
                }
            }

            $listing_types_categories_title = isset($atts['listing_types_categories_title']) ? $atts['listing_types_categories_title'] : '';
            $listing_types_categories_title_align = isset($atts['listing_types_categories_title_align']) ? $atts['listing_types_categories_title_align'] : '';
            $pricing_tabl_subtitle = isset($atts['listing_types_categories_subtitle']) ? $atts['listing_types_categories_subtitle'] : '';
            $listing_types_categories_view = isset($atts['listing_types_categories_view']) ? $atts['listing_types_categories_view'] : 'fancy';
            $listing_types_categories = isset($atts['listing_types_categories']) ? $atts['listing_types_categories'] : '';

            ob_start();
            $page_element_size = isset($atts['listing_types_categories_element_size']) ? $atts['listing_types_categories_element_size'] : 100;
            if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
                echo '<div class="' . wp_dp_cs_var_page_builder_element_sizes($page_element_size) . ' ">';
            }
            $all_types_cats = explode(",", $listing_types_categories);
            $wp_dp_element_structure = '';
            $wp_dp_element_structure = wp_dp_plugin_title_sub_align($listing_types_categories_title, $pricing_tabl_subtitle, $listing_types_categories_title_align);
            echo force_balance_tags($wp_dp_element_structure);



            if ($all_types_cats) {

                if ( isset($listing_types_categories_view) && $listing_types_categories_view == 'classic' ) {

                    echo '<div class="categories-type">
                                               <ul>';
                    foreach ( $all_types_cats as $all_type_cat ) {
                        $term = get_term_by('slug', $all_type_cat, 'listing-category');
                        if ( $post = get_page_by_path($all_type_cat, OBJECT, 'listing-type') ) {
                            $type_id = $post->ID;
                        } else {
                            $type_id = 0;
                        }

                        if ( $all_type_cat != '' && isset($term) && is_object($term) ) {
                            $wp_dp_search_result_page = isset($wp_dp_plugin_options['wp_dp_search_result_page']) ? $wp_dp_plugin_options['wp_dp_search_result_page'] : '';
                            $wp_dp_search_result_page = get_permalink($wp_dp_search_result_page);

                            $type_slug = '';
                            $element_type_filter_arr = array(
                                'key' => 'wp_dp_listing_type_cats',
                                'value' => serialize($term->slug),
                                'compare' => 'LIKE',
                                array(
                                    'key' => 'wp_dp_listing_status',
                                    'value' => 'active',
                                    'compare' => '=',
                                ),
                                array(
                                    'key' => 'wp_dp_listing_expired',
                                    'value' => strtotime(date($default_date_time_formate)),
                                    'compare' => '>=',
                                ),
                            );
                            $args_count = array(
                                'posts_per_page' => "1",
                                'post_type' => 'listing-type',
                                'post_status' => 'publish',
                                'meta_query' => array(
                                    $element_type_filter_arr,
                                ),
                            );

                            $types = get_posts($args_count);
                            $type_slug = isset($types[0]->post_name) ? $types[0]->post_name : '';
                            wp_reset_postdata();

                            if ( $type_slug != '' ) {
                                $args['listing_type'] = $type_slug;
                            }
                            $args['listing_category'] = $term->slug;
                            $args['ajax_filter'] = 'true';

                            $wp_dp_search_result_page = isset($wp_dp_search_result_page) && $wp_dp_search_result_page != '' ? add_query_arg($args, $wp_dp_search_result_page) : '';

                            $term_icon = get_term_meta($term->term_id, 'wp_dp_listing_taxonomy_icon', true);
                            if ( $term_icon != '' ) {
                                $term_icon = '<i class="' . esc_html($term_icon) . '"></i>';
                            }
                            echo '<li> <a href="' . $wp_dp_search_result_page . '" class="category-content"> ' . $term_icon . ' <span class="category-name">' . esc_html($term->name) . '</span> </a> </li>';
                        } else if ( $type_id != 0 && (FALSE !== get_post_status($type_id) && 'publish' == get_post_status($type_id)) ) {
                            $type = $type_id;
                            $wp_dp_search_result_page = get_post_meta($type, 'wp_dp_search_result_page', true);
                            $type_post = get_post($type);
                            $type_post_slug = isset($type_post->post_name) ? $type_post->post_name : '';
                            $type_url = 0;
                            if ( $wp_dp_search_result_page != '' ) {
                                $type_url = 1;
                                $wp_dp_search_result_page = get_permalink($wp_dp_search_result_page);
                            } else {
                                $type_url = 0;
                                $wp_dp_search_result_page = isset($wp_dp_plugin_options['wp_dp_search_result_page']) ? $wp_dp_plugin_options['wp_dp_search_result_page'] : '';
                                $wp_dp_search_result_page = get_permalink($wp_dp_search_result_page);
                            }

                            $wp_dp_search_result_page = isset($wp_dp_search_result_page) && $wp_dp_search_result_page != '' ? add_query_arg(array( 'listing_type' => $type_post_slug, 'ajax_filter' => 'true' ), $wp_dp_search_result_page) : '';
                            $listing_type_tite = get_the_title($type);
                            $listing_type_icon = get_post_meta($type, 'wp_dp_listing_type_icon', true);
                            $listing_image = get_post_meta($type, 'wp_dp_listing_type_image', true);
                            $listing_image = $listing_image != '' ? wp_get_attachment_url($listing_image) : '';
                            $type_terms = get_post_meta((int) $type, 'wp_dp_listing_type_cats', true);
                            $wp_dp_listing_type_icon_image = get_post_meta($type, 'wp_dp_listing_type_icon_image', true);
                            if ( (isset($wp_dp_listing_type_icon_image) && $wp_dp_listing_type_icon_image == 'image') && $listing_image != '' ) {
                                echo '<li><figure><a href="' . $wp_dp_search_result_page . '" class="category-content"> <img src="' . $listing_image . '" alt="' . $listing_type_tite . '"> <span class="category-name">' . $listing_type_tite . '</span> </a> </figure></li>';
                            } elseif ( (isset($wp_dp_listing_type_icon_image) && $wp_dp_listing_type_icon_image == 'icon' ) ) {
                                if ( $listing_type_icon[0] != '' ) {
                                    echo '<li> <a href="' . $wp_dp_search_result_page . '" class="category-content"> <i class="' . $listing_type_icon[0] . '"></i> <span class="category-name">' . $listing_type_tite . '</span> </a> </li>';
                                }
                            }
                        }
                    }
                    echo '</ul></div>';
                } elseif ( isset($listing_types_categories_view) && $listing_types_categories_view == 'fancy' ) {
                    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                 <div class="row">';

                    foreach ( $all_types_cats as $all_type_cat ) {
                        $term = get_term_by('slug', $all_type_cat, 'listing-category');
                        if ( $post = get_page_by_path($all_type_cat, OBJECT, 'listing-type') ) {
                            $type_id = $post->ID;
                        } else {
                            $type_id = 0;
                        }

                        if ( $all_type_cat != '' && isset($term) && is_object($term) ) {
                            $wp_dp_search_result_page = isset($wp_dp_plugin_options['wp_dp_search_result_page']) ? $wp_dp_plugin_options['wp_dp_search_result_page'] : '';
                            $wp_dp_search_result_page = get_permalink($wp_dp_search_result_page);
                            $type_slug = '';

                            $element_type_filter_arr = array(
                                'key' => 'wp_dp_listing_type_cats',
                                'value' => serialize($term->slug),
                                'compare' => 'LIKE',
                                array(
                                    'key' => 'wp_dp_listing_status',
                                    'value' => 'active',
                                    'compare' => '=',
                                ),
                                array(
                                    'key' => 'wp_dp_listing_expired',
                                    'value' => strtotime(date($default_date_time_formate)),
                                    'compare' => '>=',
                                ),
                            );
                            $listing_type_args = array(
                                'posts_per_page' => "1",
                                'post_type' => 'listing-type',
                                'post_status' => 'publish',
                                'meta_query' => array(
                                    $element_type_filter_arr,
                                ),
                            );

                            $types = get_posts($listing_type_args);
                            $type_slug = isset($types[0]->post_name) ? $types[0]->post_name : '';
                            wp_reset_postdata();

                            if ( $type_slug != '' ) {
                                $args['listing_type'] = $type_slug;
                            }
                            $args['listing_category'] = $term->slug;
                            $args['ajax_filter'] = 'true';

                            $wp_dp_search_result_page = isset($wp_dp_search_result_page) && $wp_dp_search_result_page != '' ? add_query_arg($args, $wp_dp_search_result_page) : '';

                            $term_icon = get_term_meta($term->term_id, 'wp_dp_listing_taxonomy_icon', true);
                            echo '<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                                                                <div class="fancy-categories">';
                            if ( $term_icon != '' ) {
                                echo '<div class="img-holder">
                                                                        <figure>
                                                                            <a href="' . $wp_dp_search_result_page . '">
                                                                               <i class="' . $term_icon . '"></i>
                                                                            </a>
                                                                        </figure>
                                                                    </div>';
                            }

                            $num_of_listing = '';
                            $element_filter_arr = array();
                            if ( isset($type_post_slug) && ! empty($type_post_slug) ) {
                                $element_filter_arr[] = array(
                                    'key' => 'wp_dp_listing_category',
                                    'value' => serialize($term->slug),
                                    'compare' => 'LIKE',
                                    array(
                                        'key' => 'wp_dp_listing_status',
                                        'value' => 'active',
                                        'compare' => '=',
                                    ),
                                    array(
                                        'key' => 'wp_dp_listing_expired',
                                        'value' => strtotime(date($default_date_time_formate)),
                                        'compare' => '>=',
                                    ),
                                );
                                $args_count = array(
                                    'posts_per_page' => "1",
                                    'post_type' => 'listings',
                                    'post_status' => 'publish',
                                    'meta_query' => array(
                                        $element_filter_arr,
                                    ),
                                );
                                $the_query = new WP_Query($args_count);
                                $num_of_listing = $the_query->found_posts;
                                wp_reset_postdata();
                            }
                            echo '<div class="text-holder">
                                                                    <h4>
                                                                        <a href="' . $wp_dp_search_result_page . '">' . $term->name . '</a>
                                                                    </h4>
                                                                    <span>(' . $num_of_listing . ' ' . wp_dp_plugin_text_srt('wp_dp_member_listings') . ')</span>
                                                                </div>
                                                            </div>	
                                                        </div>';
                        } else if ( $type_id != 0 && (FALSE !== get_post_status($type_id) && 'publish' == get_post_status($type_id)) ) {
                            $type = $type_id;
                            $wp_dp_search_result_page = get_post_meta($type, 'wp_dp_search_result_page', true);
                            $type_post = get_post($type);
                            $type_post_slug = isset($type_post->post_name) ? $type_post->post_name : '';
                            $type_url = 0;
                            if ( $wp_dp_search_result_page != '' ) {
                                $type_url = 1;
                                $wp_dp_search_result_page = get_permalink($wp_dp_search_result_page);
                            } else {
                                $type_url = 0;
                                $wp_dp_search_result_page = isset($wp_dp_plugin_options['wp_dp_search_result_page']) ? $wp_dp_plugin_options['wp_dp_search_result_page'] : '';
                                $wp_dp_search_result_page = get_permalink($wp_dp_search_result_page);
                            }
                            $wp_dp_search_result_page = isset($wp_dp_search_result_page) && $wp_dp_search_result_page != '' ? add_query_arg(array( 'listing_type' => $type_post_slug, 'ajax_filter' => 'true' ), $wp_dp_search_result_page) : '';
                            $listing_type_tite = get_the_title($type);
                            $listing_type_icon = get_post_meta($type, 'wp_dp_listing_type_icon', true);
                            $listing_image = get_post_meta($type, 'wp_dp_listing_type_image', true);
                            $listing_image = $listing_image != '' ? wp_get_attachment_url($listing_image) : '';
                            $type_terms = get_post_meta((int) $type, 'wp_dp_listing_type_cats', true);
                            echo '<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                                                            <div class="fancy-categories">';
                            $wp_dp_listing_type_icon_image = get_post_meta($type, 'wp_dp_listing_type_icon_image', true);
                            if ( (isset($wp_dp_listing_type_icon_image) && $wp_dp_listing_type_icon_image == 'image') && $listing_image != '' ) {
                                echo '<div class="img-holder">
                                                                    <figure>
                                                                        <a href="' . $wp_dp_search_result_page . '">
                                                                           <img src="' . $listing_image . '" alt="' . $listing_type_tite . '">
                                                                        </a>
                                                                    </figure>
                                                                </div>';
                            } elseif ( (isset($wp_dp_listing_type_icon_image) && $wp_dp_listing_type_icon_image == 'icon' ) ) {
                                if ( $listing_type_icon[0] != '' ) {
                                    echo '<div class="img-holder">
                                                                    <figure>
                                                                        <a href="' . $wp_dp_search_result_page . '">
                                                                           <i class="' . $listing_type_icon[0] . '"></i>
                                                                        </a>
                                                                    </figure>
                                                                </div>';
                                }
                            }
                            $num_of_listing = '';
                            if ( isset($type_post_slug) && ! empty($type_post_slug) ) {
                                $element_filter_arr = array(
                                    'relation' => 'AND',
                                    array(
                                    'key' => 'wp_dp_listing_type',
                                    'value' => $type_post_slug,
                                    'compare' => '=',
                                    ),
                                    array(
                                        'key' => 'wp_dp_listing_status',
                                        'value' => 'active',
                                        'compare' => '=',
                                    ),
                                    array(
                                        'key' => 'wp_dp_listing_expired',
                                        'value' => strtotime(date($default_date_time_formate)),
                                        'compare' => '>=',
                                    ),
                                );
                                $args_count = array(
                                    'posts_per_page' => "1",
                                    'post_type' => 'listings',
                                    'post_status' => 'publish',
                                    'meta_query' => array(
                                        $element_filter_arr,
                                    ),
                                );
                                $the_query = new WP_Query($args_count);
                                $num_of_listing = $the_query->found_posts;
                                wp_reset_postdata();
                            }
                            echo '<div class="text-holder">
                                        <h4>
                                            <a href="' . $wp_dp_search_result_page . '">' . $listing_type_tite . '</a>
                                        </h4>
                                        <span>(' . $num_of_listing . ' ' . wp_dp_plugin_text_srt('wp_dp_member_listings') . ')</span>
                                    </div>
                                </div>	
                            </div>';
                        }
                    }
                    echo '</div>	
                    </div>';
                }elseif ( isset($listing_types_categories_view) && $listing_types_categories_view == 'simple' ) {
                    echo '<ul class="spatialism-sec">';
                    foreach ( $all_types_cats as $all_type_cat ) {
                        $term = get_term_by('slug', $all_type_cat, 'listing-category');
                        if ( $post = get_page_by_path($all_type_cat, OBJECT, 'listing-type') ) {
                            $type_id = $post->ID;
                        } else {
                            $type_id = 0;
                        }

                        if ( $all_type_cat != '' && isset($term) && is_object($term) ) {
                            $wp_dp_search_result_page = isset($wp_dp_plugin_options['wp_dp_search_result_page']) ? $wp_dp_plugin_options['wp_dp_search_result_page'] : '';
                            $wp_dp_search_result_page = get_permalink($wp_dp_search_result_page);
                            $type_slug = '';

                            $element_type_filter_arr = array(
                                'key' => 'wp_dp_listing_type_cats',
                                'value' => serialize($term->slug),
                                'compare' => 'LIKE',
                                array(
                                    'key' => 'wp_dp_listing_status',
                                    'value' => 'active',
                                    'compare' => '=',
                                ),
                                array(
                                    'key' => 'wp_dp_listing_expired',
                                    'value' => strtotime(date($default_date_time_formate)),
                                    'compare' => '>=',
                                ),
                            );
                            $listing_type_args = array(
                                'posts_per_page' => "1",
                                'post_type' => 'listing-type',
                                'post_status' => 'publish',
                                'meta_query' => array(
                                    $element_type_filter_arr,
                                ),
                            );

                            $types = get_posts($listing_type_args);
                            $type_slug = isset($types[0]->post_name) ? $types[0]->post_name : '';
                            wp_reset_postdata();

                            if ( $type_slug != '' ) {
                                $args['listing_type'] = $type_slug;
                            }
                            $args['listing_category'] = $term->slug;
                            $args['ajax_filter'] = 'true';

                            $wp_dp_search_result_page = isset($wp_dp_search_result_page) && $wp_dp_search_result_page != '' ? add_query_arg($args, $wp_dp_search_result_page) : '';

                            $term_icon = get_term_meta($term->term_id, 'wp_dp_listing_taxonomy_icon', true);
//                            echo '<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
//                                                                <div class="fancy-categories">';
//                            if ( $term_icon != '' ) {
//                                echo '<div class="img-holder">
//                                                                        <figure>
//                                                                            <a href="' . $wp_dp_search_result_page . '">
//                                                                               <i class="' . $term_icon . '"></i>
//                                                                            </a>
//                                                                        </figure>
//                                                                    </div>';
//                            }

                            $num_of_listing = '';
                            $element_filter_arr = array();
                            if ( isset($type_post_slug) && ! empty($type_post_slug) ) {
                                $element_filter_arr[] = array(
                                    'key' => 'wp_dp_listing_category',
                                    'value' => serialize($term->slug),
                                    'compare' => 'LIKE',
                                );
                                $args_count = array(
                                    'posts_per_page' => "1",
                                    'post_type' => 'listings',
                                    'post_status' => 'publish',
                                    'meta_query' => array(
                                        $element_filter_arr,
                                    ),
                                );
                                $the_query = new WP_Query($args_count);
                                $num_of_listing = $the_query->found_posts;
                                wp_reset_postdata();
                            }
//                            echo '<div class="text-holder">
//                                                                    <h4>
//                                                                        <a href="' . $wp_dp_search_result_page . '">' . $term->name . '</a>
//                                                                    </h4>
//                                                                    <span>(' . $num_of_listing . ' ' . wp_dp_plugin_text_srt('wp_dp_member_listings') . ')</span>
//                                                                </div>
//                                                            </div>	
//                                                        </div>';
                            
                            echo '<li class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <a href="' . $wp_dp_search_result_page . '"> ' . $term->name . '<span>(' . $num_of_listing . ' ' . wp_dp_plugin_text_srt('wp_dp_member_listings') . ')</span></a>
                                </li>';
                            
                            
                            
                        } else if ( $type_id != 0 && (FALSE !== get_post_status($type_id) && 'publish' == get_post_status($type_id)) ) {
                            $type = $type_id;
                            $wp_dp_search_result_page = get_post_meta($type, 'wp_dp_search_result_page', true);
                            $type_post = get_post($type);
                            $type_post_slug = isset($type_post->post_name) ? $type_post->post_name : '';
                            $type_url = 0;
                            if ( $wp_dp_search_result_page != '' ) {
                                $type_url = 1;
                                $wp_dp_search_result_page = get_permalink($wp_dp_search_result_page);
                            } else {
                                $type_url = 0;
                                $wp_dp_search_result_page = isset($wp_dp_plugin_options['wp_dp_search_result_page']) ? $wp_dp_plugin_options['wp_dp_search_result_page'] : '';
                                $wp_dp_search_result_page = get_permalink($wp_dp_search_result_page);
                            }
                            $wp_dp_search_result_page = isset($wp_dp_search_result_page) && $wp_dp_search_result_page != '' ? add_query_arg(array( 'listing_type' => $type_post_slug, 'ajax_filter' => 'true' ), $wp_dp_search_result_page) : '';
                            $listing_type_tite = get_the_title($type);
                            $listing_type_icon = get_post_meta($type, 'wp_dp_listing_type_icon', true);
                            $listing_image = get_post_meta($type, 'wp_dp_listing_type_image', true);
                            $listing_image = $listing_image != '' ? wp_get_attachment_url($listing_image) : '';
                            $type_terms = get_post_meta((int) $type, 'wp_dp_listing_type_cats', true);
//                            echo '<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
//                                                            <div class="fancy-categories">';
                            $wp_dp_listing_type_icon_image = get_post_meta($type, 'wp_dp_listing_type_icon_image', true);
                            if ( (isset($wp_dp_listing_type_icon_image) && $wp_dp_listing_type_icon_image == 'image') && $listing_image != '' ) {
//                                echo '<div class="img-holder">
//                                                                    <figure>
//                                                                        <a href="' . $wp_dp_search_result_page . '">
//                                                                           <img src="' . $listing_image . '" alt="' . $listing_type_tite . '">
//                                                                        </a>
//                                                                    </figure>
//                                                                </div>';
                            } elseif ( (isset($wp_dp_listing_type_icon_image) && $wp_dp_listing_type_icon_image == 'icon' ) ) {
//                                if ( $listing_type_icon[0] != '' ) {
//                                    echo '<div class="img-holder">
//                                                                    <figure>
//                                                                        <a href="' . $wp_dp_search_result_page . '">
//                                                                           <i class="' . $listing_type_icon[0] . '"></i>
//                                                                        </a>
//                                                                    </figure>
//                                                                </div>';
//                                }
                            }
                            $num_of_listing = '';
                            if ( isset($type_post_slug) && ! empty($type_post_slug) ) {
                                $element_filter_arr = array(
                                    'key' => 'wp_dp_listing_type',
                                    'value' => $type_post_slug,
                                    'compare' => '=',
                                );
                                $args_count = array(
                                    'posts_per_page' => "1",
                                    'post_type' => 'listings',
                                    'post_status' => 'publish',
                                    'meta_query' => array(
                                        $element_filter_arr,
                                    ),
                                );
                                $the_query = new WP_Query($args_count);
                                $num_of_listing = $the_query->found_posts;
                                wp_reset_postdata();
                            }
//                            echo '<div class="text-holder">
//                                        <h4>
//                                            <a href="' . $wp_dp_search_result_page . '">' . $listing_type_tite . '</a>
//                                        </h4>
//                                        <span>(' . $num_of_listing . ' ' . wp_dp_plugin_text_srt('wp_dp_member_listings') . ')</span>
//                                    </div>
//                                </div>	
//                            </div>';
                            
                            
                            echo '<li class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <a href="' . $wp_dp_search_result_page . '"> ' . $listing_type_tite . '<span>(' . $num_of_listing . ' ' . wp_dp_plugin_text_srt('wp_dp_member_listings') . ')</span></a>
                                </li>';
                            
                        }
                    }
                    echo '</ul>';
                }
            }

            if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
                echo '</div>';
            }
            $html = ob_get_clean();
            return $html;
        }

    }

    global $wp_dp_shortcode_listing_types_categories_front;
    $wp_dp_shortcode_listing_types_categories_front = new Wp_dp_Shortcode_Listing_Types_Categories_front();
}