<?php
/**
 * @Top Listings widget Class
 *
 *
 */
if ( ! class_exists('wp_dp_top_listings') ) {

    class wp_dp_top_listings extends WP_Widget {
        /**
         * Outputs the content of the widget
         * @param array $args
         * @param array $instance
         */

        /**
         * @init User list Module
         */
        public function __construct() {

            parent::__construct(
                    'wp_dp_top_listings', // Base ID
                    wp_dp_plugin_text_srt('wp_dp_top_listings_widget'), // Name
                    array( 'classname' => 'widget_top_listings', 'description' => wp_dp_plugin_text_srt('wp_dp_top_listings_widget_desc'), ) // Args
            );
        }

        /**
         * @User list html form
         */
        function form($instance = array()) {
            global $wp_dp_html_fields;
            $instance = wp_parse_args((array) $instance, array( 'title' => '' ));
            $title = $instance['title'];
            $showcount = isset($instance['showcount']) ? esc_attr($instance['showcount']) : '';
            $listing_widget_style = isset($instance['listing_widget_style']) ? esc_attr($instance['listing_widget_style']) : '';
            $listing_title_length = isset($instance['listing_title_length']) ? esc_attr($instance['listing_title_length']) : '';
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_top_listings_title_field'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => esc_attr($title),
                    'id' => ($this->get_field_id('title')),
                    'classes' => '',
                    'cust_id' => ($this->get_field_name('title')),
                    'cust_name' => ($this->get_field_name('title')),
                    'return' => true,
                    'required' => false
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_widget_top_listings_styles'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'cust_name' => wp_dp_allow_special_char($this->get_field_name('listing_widget_style')),
                    'cust_id' => wp_dp_allow_special_char($this->get_field_id('listing_widget_style')),
                    'return' => true,
                    'classes' => 'chosen-select',
                    'std' => $listing_widget_style,
                    'options' => array(
                        '' => wp_dp_plugin_text_srt('wp_dp_widget_top_listings_styles_classic'),
                        'simple' => wp_dp_plugin_text_srt('wp_dp_widget_top_listings_styles_simple'),
                        'modern' => wp_dp_plugin_text_srt('wp_dp_widget_top_listings_styles_modern'),
                    ),
                ),
            );
            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_top_listings_num_post'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => esc_attr($showcount),
                    'id' => wp_dp_cs_allow_special_char($this->get_field_id('showcount')),
                    'classes' => '',
                    'cust_id' => wp_dp_cs_allow_special_char($this->get_field_name('showcount')),
                    'cust_name' => wp_dp_cs_allow_special_char($this->get_field_name('showcount')),
                    'return' => true,
                    'required' => false
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_widget_top_listings_title_length'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => esc_attr($listing_title_length),
                    'id' => ($this->get_field_id('listing_title_length')),
                    'classes' => '',
                    'cust_id' => ($this->get_field_name('listing_title_length')),
                    'cust_name' => ($this->get_field_name('listing_title_length')),
                    'return' => true,
                    'required' => false
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
        }

        /**
         * @User list update data
         */
        function update($new_instance = array(), $old_instance = array()) {
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['showcount'] = esc_sql($new_instance['showcount']);
            $instance['listing_widget_style'] = $new_instance['listing_widget_style'];
            $instance['listing_title_length'] = $new_instance['listing_title_length'];

            return $instance;
        }

        /**
         * @Display User list widget */
        function widget($args = array(), $instance = array()) {
            extract($args, EXTR_SKIP);
            global $wpdb, $post, $cs_theme_options;
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
            $title = htmlspecialchars_decode(stripslashes($title));
            $showcount = $instance['showcount'];
            $listing_widget_style = isset($instance['listing_widget_style']) ? $instance['listing_widget_style'] : '';
            $listing_title_length = isset($instance['listing_title_length']) ? $instance['listing_title_length'] : '4';

            $view_class = '';
            if ( isset($listing_widget_style) && $listing_widget_style == 'simple' ) {
                $view_class = ' simple';
            }

            // WIDGET display CODE Start
            echo balanceTags($before_widget, false);
            $cs_page_id = '';

            if ( isset($instance['title']) && $instance['title'] != '' ) {
                if ( strlen($title) <> 1 || strlen($title) <> 0 ) {
                    echo balanceTags($before_title . $title . $after_title, false);
                }
            }
            $showcount = $showcount <> '' ? $showcount : '10';
            $default_date_time_formate = 'd-m-Y H:i:s';
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
            
            $element_filter_arr[] = array(
                'key' => 'wp_dp_promotion_top-categories',
                'value' => 'on',
                'compare' => '=',
            );

            $element_filter_arr[] = array( 'relation' => 'OR',
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
            
            
            $paging_var = isset($paging_var) ? $paging_var : '';
            $args = array(
                'posts_per_page' => $showcount,
                'paged' => isset($_REQUEST[$paging_var]) ? $_REQUEST[$paging_var] : 1,
                'post_type' => 'listings',
                'post_status' => 'publish',
                'fields' => 'ids', // only load ids 
                'meta_query' => array(
                    $element_filter_arr,
                ),
            );

            $top_listings_loop_obj = wp_dp_get_cached_obj('top_listings_result_cached_loop_obj', $args, 12, false, 'wp_query');
            if ( $top_listings_loop_obj->have_posts() ) {

                $listing_location_options = 'city,country';
                if ( $listing_location_options != '' ) {
                    $listing_location_options = explode(',', $listing_location_options);
                }
                ?>
				<div class="widget-inner">
					<div class="top-listings-listing<?php echo wp_dp_cs_allow_special_char($view_class); ?>">
                    <?php
                    while ( $top_listings_loop_obj->have_posts() ) : $top_listings_loop_obj->the_post();
                        global $post;
                        $listing_id = $post;
                        $Wp_dp_Locations = new Wp_dp_Locations();
                        $get_listing_location = $Wp_dp_Locations->get_element_listing_location($listing_id, $listing_location_options);
                        $wp_dp_listing_price_options = get_post_meta($listing_id, 'wp_dp_listing_price_options', true);
                        $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                        // checking review in on in listing type
                        $wp_dp_listing_type = isset($wp_dp_listing_type) ? $wp_dp_listing_type : '';
                        if ( $listing_type_post = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type') )
                            $listing_type_id = $listing_type_post->ID;
                        $listing_type_id = isset($listing_type_id) ? $listing_type_id : '';
						$listing_type_id = wp_dp_wpml_lang_page_id( $listing_type_id, 'listing-type' );
                        $wp_dp_user_reviews = get_post_meta($listing_type_id, 'wp_dp_user_reviews', true);
                        $wp_dp_listing_type_price_switch = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
                        // end checking review on in listing type
                        $wp_dp_listing_price = '';
                        if ( $wp_dp_listing_price_options == 'price' ) {
                            $wp_dp_listing_price = get_post_meta($listing_id, 'wp_dp_listing_price', true);
                        } else if ( $wp_dp_listing_price_options == 'on-call' ) {
                            $wp_dp_listing_price = wp_dp_plugin_text_srt('wp_dp_listings_price_on_request');
                        }
                        ?>
                        <div class="listings-post"> 
                            <?php if ( isset($listing_widget_style) && $listing_widget_style != 'simple' ) { ?>
                                <div class="img-holder">
                                    <figure>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php
                                            $size = '';
                                            if ( $listing_widget_style == 'modern' ) {
                                                $size = 'thumbnail';
                                            } else {
                                                $size = 'wp_dp_cs_media_4';
                                            }
                                            if ( function_exists('listing_gallery_first_image') ) {
                                                $gallery_image_args = array(
                                                    'listing_id' => $listing_id,
                                                    'size' => $size,
                                                    'class' => 'img-list',
                                                    'default_image_src' => esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg')
                                                );
                                                $listing_gallery_first_image = listing_gallery_first_image($gallery_image_args);
                                                echo wp_dp_cs_allow_special_char($listing_gallery_first_image);
                                            }
                                            ?>
                                        </a>
                                        <figcaption>
                                            <?php wp_dp_listing_sold_html($listing_id);?>
                                        </figcaption>
                                    </figure>
                                </div>
                            <?php } ?>
                            <div class="text-holder">
                                <?php if ( $wp_dp_listing_type_price_switch == 'on' && $wp_dp_listing_price != '' && ($listing_widget_style == 'simple' || $listing_widget_style == 'modern') ) { ?>
                                    <span class="listing-price">
                                        <span class="new-price text-color">
                                            <?php
                                            if ( $wp_dp_listing_price_options == 'on-call' ) {
                                                echo force_balance_tags($wp_dp_listing_price);
                                            } else {
                                                $listing_info_price = wp_dp_listing_price($listing_id, $wp_dp_listing_price );
                                                echo force_balance_tags($listing_info_price);
                                            }
                                            ?>
                                        </span>
                                    </span>
                                <?php } ?>
                                <?php if ( $listing_widget_style == 'simple' ) { ?>
                                    <a href="<?php echo esc_url(get_permalink($listing_id)); ?>"><?php echo wp_dp_limit_text(get_the_title($listing_id), $listing_title_length); ?></a>
                                <?php } else { ?>
                                    <div class="post-title">
                                        <h4><a href="<?php echo esc_url(get_permalink($listing_id)); ?>"><?php echo wp_dp_limit_text(get_the_title($listing_id), $listing_title_length); ?></a></h4> 
                                    </div>
                                <?php } ?>
                                <?php if ( $wp_dp_listing_type_price_switch == 'on' && $wp_dp_listing_price != '' && $listing_widget_style != 'simple' && $listing_widget_style != 'modern' ) { ?>
                                    <span class="listing-price">
                                        <span class="new-price text-color">
                                            <?php
                                            if ( $wp_dp_listing_price_options == 'on-call' ) {
                                                echo force_balance_tags($wp_dp_listing_price);
                                            } else {
                                                $listing_info_price = wp_dp_listing_price($listing_id, $wp_dp_listing_price);
                                                echo force_balance_tags($listing_info_price);
                                            }
                                            ?>
                                        </span>
                                    </span>
                                <?php }
                                ?>
                                <?php if ( ! empty($get_listing_location) && ( $listing_widget_style == 'simple' || $listing_widget_style == 'modern') ) { ?>
                                    <ul class="location-list">
                                        <li><i class="icon-map-marker"></i><span><?php echo esc_html(implode(' / ', $get_listing_location)); ?></span></li>
                                    </ul>
                                <?php } ?>


                            </div>
                        </div> 
                        <?php
                    endwhile;
                    ?>
                </div>
				</div>
                <?php
            } else {
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-member-match-error"><h6><i class="icon-warning"></i><strong> ' . wp_dp_plugin_text_srt('wp_dp_top_listings_widget_sorry') . '</strong>&nbsp; ' . wp_dp_plugin_text_srt('wp_dp_top_listings_widget_dosen_match') . ' </h6></div>';
            }
            echo balanceTags($after_widget, false);
        }

    }

}
add_action('widgets_init', function(){ return register_widget("wp_dp_top_listings"); });



