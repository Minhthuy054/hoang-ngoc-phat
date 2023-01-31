<?php

/**
 * @  Blog html form for page builder Frontend side
 *
 *
 */
if ( ! function_exists('wp_dp_cs_locations_shortcode') ) {

    function wp_dp_cs_locations_shortcode($atts) {
        global $wp_dp_plugin_options, $post, $wp_dp_cs_locations_element_title, $wpdb, $locations_pagination, $wp_dp_cs_locations_num_post, $wp_dp_cs_counter_node, $wp_dp_cs_column_atts, $wp_dp_cs_locations_description, $wp_dp_cs_locations_excerpt, $post_thumb_view, $wp_dp_cs_locations_section_title, $args, $wp_dp_cs_locations_orderby, $orderby;
        $html = '';
        ob_start();
        $defaults = array(
            'locations_element_size' => '',
            'wp_dp_cs_locations_element_title' => '',
            'wp_dp_cs_locations_element_subtitle' => '',
            'wp_dp_var_locations_align' => '',
            'wp_dp_var_locations_style' => '',
            'wp_dp_all_locations_names' => '',
            'wp_dp_all_locations_url' => '',
            'wp_dp_location_element_title_color' => '',
            'wp_dp_location_element_subtitle_color' => '',
            'wp_dp_location_seperator_style' => '',
        );
        extract(shortcode_atts($defaults, $atts));
        $page_element_size = isset($atts['locations_element_size']) ? $atts['locations_element_size'] : 100;
        if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
            $html .= '<div class="' . wp_dp_cs_var_page_builder_element_sizes($page_element_size, $atts) . ' ">';
        }



        $wp_dp_search_result_page = isset($wp_dp_plugin_options['wp_dp_search_result_page']) ? $wp_dp_plugin_options['wp_dp_search_result_page'] : '';
        $wp_dp_all_locations_names = isset($wp_dp_all_locations_names) ? $wp_dp_all_locations_names : '';

        $wp_dp_cs_locations_element_title = isset($wp_dp_cs_locations_element_title) ? $wp_dp_cs_locations_element_title : '';
        $wp_dp_cs_locations_element_subtitle = isset($wp_dp_cs_locations_element_subtitle) ? $wp_dp_cs_locations_element_subtitle : '';
        $wp_dp_var_locations_align = isset($wp_dp_var_locations_align) ? $wp_dp_var_locations_align : '';
        $wp_dp_var_locations_style = isset($wp_dp_var_locations_style) ? $wp_dp_var_locations_style : '';
        $wp_dp_all_locations_url = isset($wp_dp_all_locations_url) ? $wp_dp_all_locations_url : '';
        $all_locations = explode(",", $wp_dp_all_locations_names);
        $html .= wp_dp_plugin_title_sub_align($wp_dp_cs_locations_element_title, $wp_dp_cs_locations_element_subtitle, $wp_dp_var_locations_align, $wp_dp_location_element_title_color, $wp_dp_location_seperator_style, $wp_dp_location_element_subtitle_color);
        $page_url = isset($wp_dp_search_result_page) ? wp_dp_wpml_lang_page_permalink($wp_dp_search_result_page, 'page') : '';
        $view_class = '';
        if ( $wp_dp_var_locations_style == 'simple' ) {
            $view_class = ' v2';
        }

        if ( $wp_dp_var_locations_style == 'classic' ) {
            $view_class = ' classic';
        }

        
        
        if ( $wp_dp_var_locations_style == 'modern' ) {
            $html .= '<div class="top-locations">
	    <div class="row">';
        }else{
          $html .= '<div class="top-locations' . $view_class . '"><ul>';  
        }
        
        
        
        if ( isset($all_locations) && ! empty($all_locations) && is_array($all_locations) ) {
            foreach ( $all_locations as $value ) {
                $location = get_term_by('slug', $value, 'wp_dp_locations');
                if ( isset($location) && ! empty($location) ) {
                    $term_id = isset($location->term_id) ? $location->term_id : '';
					
                    $location_slug = isset($location->slug) ? $location->slug : '';
                    $location_name = isset($location->name) ? $location->name : '';
                    $wp_dp_location_img_field = get_term_meta($term_id, 'wp_dp_location_img_field', true);
                    $wp_dp_location_img_field = isset($wp_dp_location_img_field) ? $wp_dp_location_img_field : '';
                    $num_of_listings = '';
                    $list_args = array(
                        'posts_per_page' => "1",
                        'post_type' => 'listings',
                        'post_status' => 'publish',
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'key' => 'wp_dp_listing_expired',
                                'value' => strtotime(date("d-m-Y")),
                                'compare' => '>=',
                            ),
                            array(
                                'key' => 'wp_dp_listing_status',
                                'value' => 'delete',
                                'compare' => '!=',
                            ),
                            array(
                                'relation' => 'OR',
                                array(
                                    'key' => 'wp_dp_post_loc_country_listing',
                                    'value' => $location_slug,
                                    'compare' => '=',
                                ),
                                array(
                                    'key' => 'wp_dp_post_loc_state_listing',
                                    'value' => $location_slug,
                                    'compare' => '=',
                                ),
                                array(
                                    'key' => 'wp_dp_post_loc_city_listing',
                                    'value' => $location_slug,
                                    'compare' => '=',
                                ),
                            )
                        ),
                    );
                    $custom_query = new WP_Query($list_args);
                    $num_of_listings = $custom_query->found_posts;
                    $location_search_link = 'javascript:void(0)';
                    if ( ! empty($location_slug) && ! empty($page_url) ) {
                        $location_search_link = $page_url . '?location=' . $location_slug . '';
                    }
                    $location_url = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/location-no-img.jpg');
                    
                    if ( ! empty($wp_dp_location_img_field) ) {
                        $location_url = wp_get_attachment_url($wp_dp_location_img_field);
                    }
                    $no_img = '';
                    if ( empty($location_url) ) {
                        $no_img = ' no-img';
                    }
                    if ( $wp_dp_var_locations_style == 'simple' ) {
                        $html .='<li><a href="' . ($location_search_link) . '">' . esc_html($location_name) . '</a></li>';
                    } elseif ( $wp_dp_var_locations_style == 'classic' ) {

                        $html .=' <li> ';
                        $html .='<div class="text-holder">
                                    <a href="' . ($location_search_link) . '">' . esc_html($location_name) . '</a>
                                    <span class="listings-count">' . $num_of_listings . '</span>
                                    <span class="listings-listed">' . wp_dp_plugin_text_srt('wp_dp_location_element_listings_listed') . '</span>
                              </div>';
                        $html .=' </li> ';
                    } else {
                        $html .='<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="location-content">
                                    <div class="img-holder">
                                        <figure> <a href="' . ($location_search_link) . '"> <img src="' . esc_url($location_url) . '" alt=""> </a>
                                            <figcaption>
                                                <div class="title-holder">';
						    if( isset($location->parent) && $location->parent != 0 ){

							    $html .= '<div class="post-title">';
								    $html .= '<h4><a href="' . ($location_search_link) . '">' . esc_html($location_name) . '</a></h4>';
							    $html .= '</div>';
							    // Parent Location	
							    $parent_location = get_top_level_location( $location->term_id );
							    if( $parent_location != '' ){
								    $parent_location = get_term_by('slug', $parent_location, 'wp_dp_locations');
								    if( isset($parent_location) && is_object( $parent_location) ){
									    $html .= '<span>' . esc_html($parent_location->name) . '</span>';
								    }
							    }
						    }else{
							    $html .= '<div class="post-title">';
								    $html .= '<h4><a href="' . ($location_search_link) . '">' . esc_html($location_name) . '</a></h4>';
							    $html .= '</div>';
						    }
					    $html .= '</div>
                                                <div class="city-counter"> <a href="' . ($location_search_link) . '"><em class="city-numb">' . $num_of_listings . '</em><span class="city-text">'.wp_dp_plugin_text_srt('wp_dp_member_listings').'</span></a> </div>
                                            </figcaption>
                                        </figure>
                                    </div>
                                </div>
                            </div>';
                    }
                }
            }
        }
        
        if ( $wp_dp_var_locations_style == 'modern' ) {
            $html .= '</div></div>';
        }else{
           $html .='</ul>'; 
        }
        
        
        
        if ( ! empty($wp_dp_all_locations_url) && $wp_dp_var_locations_style == 'modern' ) {
            //$html .='<a href="' . esc_url($wp_dp_all_locations_url) . '" class="view-loc-btn">' . wp_dp_plugin_text_srt('wp_dp_locations_view_all_locations') . '</a>';
        }
        
        if ( $wp_dp_var_locations_style != 'modern' ) {
           $html .= '</div>';
        }
        
        
        
        
        if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
            $html .= '</div>';
        }



        return $html;
    }

    add_shortcode('wp_dp_cs_locations', 'wp_dp_cs_locations_shortcode');
	
	function get_top_level_location ($catid) {
	$cat_parent_id = 0;
		while ( $catid != null & $catid != 0 ) {
			$current_term = get_term( $catid );
			$catid = $current_term->parent;
			if ( $catid != null & $catid != 0 ) {
				$cat_parent_id = $catid;
			} else {
				$cat_parent_id = $current_term->slug;
			}
		}
		return $cat_parent_id;
	}

}
