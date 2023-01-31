<?php
/**
 * Widget API: WP_nav_menu_Widget class
 *
 * @package WordPress
 * @subpackage Widgets
 * @since 4.4.0
 */

/**
 * Core class used to implement the Custom Menu widget.
 *
 * @since 3.0.0
 *
 * @see WP_Widget
 */
if ( ! class_exists('Wp_dp_listings_Widget') ) {
    class Wp_dp_listings_Widget extends WP_Widget {

    /**
     * Sets up a new Custom Menu widget instance.
     *
     * @since 3.0.0
     * @access public
     */
    public function __construct() {

        $widget_ops = array(
            'classname' => 'listings-widget',
            'description' => wp_dp_plugin_text_srt('wp_dp_listings_widget_desc'),
        );
        parent::__construct('wp_dp_listings_widget', wp_dp_plugin_text_srt('wp_dp_listings_widget'), $widget_ops);
    }

    /**
     * Outputs the settings form for the Custom Menu widget.
     *
     * @since 3.0.0
     * @access public
     *
     * @param array $instance Current settings.
     */
    public function form($instance = array()) {

        global $wp_dp_var_form_fields, $wp_dp_var_html_fields, $wp_dp_plugin_options, $wp_dp_html_fields, $wp_dp_form_fields;
        $title = isset($instance['title']) ? $instance['title'] : '';

        $listing_widget_sortby = isset($instance['listing_widget_sortby']) ? $instance['listing_widget_sortby'] : '';
        $num_of_listing = isset($instance['num_of_listing']) ? $instance['num_of_listing'] : '';
        $listing_widget_views = isset($instance['listing_widget_views']) ? $instance['listing_widget_views'] : '';
        $featured_listing_title_length = isset($instance['featured_listing_title_length']) ? $instance['featured_listing_title_length'] : '';

        
        $wp_dp_opt_array = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_widget_title'),
            'desc' => '',
            'hint_text' => wp_dp_plugin_text_srt('wp_dp_widget_title_desc'),
            'echo' => true,
            'field_params' => array(
                'std' => esc_attr($title),
                'cust_id' => '',
                'cust_name' => wp_dp_allow_special_char($this->get_field_name('title')),
                'return' => true,
            ),
        );
        $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
        
        $wp_dp_opt_array = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_widget_top_listings_title_length'),
            'desc' => '',
            'hint_text' =>'',
            'echo' => true,
            'field_params' => array(
                'std' => esc_attr($featured_listing_title_length),
                'cust_id' => '',
                'cust_name' => wp_dp_allow_special_char($this->get_field_name('featured_listing_title_length')),
                'return' => true,
            ),
        );
        $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
        
        
        $wp_dp_opt_array = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_listings_widget_style'),
            'hint_text' => '',
            'echo' => true,
            'field_params' => array(
                'cust_name' => wp_dp_allow_special_char($this->get_field_name('listing_widget_views')),
                'cust_id' => wp_dp_allow_special_char($this->get_field_id('listing_widget_views')),
                'return' => true,
                'std' => $listing_widget_views,
                'options' => array(
                    'grid' => wp_dp_plugin_text_srt('wp_dp_listings_widget_style_grid'),
                    'medium' => wp_dp_plugin_text_srt('wp_dp_listings_widget_style_medium'),
                ),
            ),
        );
        $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

        $wp_dp_opt_array = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_listings_widget_listing_num'),
            'desc' => '',
            'hint_text' => wp_dp_plugin_text_srt('wp_dp_listings_widget_listing_num_hint'),
            'echo' => true,
            'field_params' => array(
                'std' => esc_attr($num_of_listing),
                'cust_id' => '',
                'cust_name' => wp_dp_allow_special_char($this->get_field_name('num_of_listing')),
                'return' => true,
            ),
        );
        $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

        $wp_dp_opt_array = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_listings_widget_sort_by'),
            'hint_text' => '',
            'echo' => true,
            'field_params' => array(
                'cust_name' => wp_dp_allow_special_char($this->get_field_name('listing_widget_sortby')),
                'cust_id' => wp_dp_allow_special_char($this->get_field_id('listing_widget_sortby')),
                'return' => true,
                'std' => $listing_widget_sortby,
                'options' => array(
                    'most-viewed' => wp_dp_plugin_text_srt('wp_dp_listings_widget_sort_by_most_viewed'),
                    'featured' => wp_dp_plugin_text_srt('wp_dp_listings_widget_sort_by_featured'),
                    'recent' => wp_dp_plugin_text_srt('wp_dp_listings_widget_sort_by_recent'),
                ),
            ),
        );
        $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
    }

    /**
     * Handles updating settings for the current Custom Menu widget instance.
     *
     * @since 3.0.0
     * @access public
     *
     * @param array $new_instance New settings for this instance as input by the user via
     *                            WP_Widget::form().
     * @param array $old_instance Old settings for this instance.
     * @return array Updated settings to save.
     */
    public function update($new_instance = array(), $old_instance = array()) {
        $instance = array();
        $instance['title'] = $new_instance['title'];
        $instance['listing_widget_sortby'] = $new_instance['listing_widget_sortby'];
        $instance['num_of_listing'] = $new_instance['num_of_listing'];
        $instance['listing_widget_views'] = $new_instance['listing_widget_views'];
        $instance['featured_listing_title_length'] = $new_instance['featured_listing_title_length'];
        
        return $instance;
    }

    /**
     * Outputs the content for the current Custom Menu widget instance.
     *
     * @since 3.0.0
     * @access public
     *
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance Settings for the current Custom Menu widget instance.
     */
    public function widget($args = array(), $instance = array()) {
        // Get menu.

        $widget_title = isset($instance['title']) ? $instance['title'] : '';
        $listing_widget_sortby = isset($instance['listing_widget_sortby']) ? $instance['listing_widget_sortby'] : '';
        $num_of_listing = isset($instance['num_of_listing']) ? $instance['num_of_listing'] : 2;
        $listing_widget_views = isset($instance['listing_widget_views']) ? $instance['listing_widget_views'] : '';
        $featured_listing_title_length = isset($instance['featured_listing_title_length']) ? $instance['featured_listing_title_length'] : 10;
        
        
        $meta_key = 'wp_dp_listing_posted';
		$qryvar_listing_sort_type = 'DESC';
		$qryvar_sort_by_column = 'meta_value_num';
        
        if ( $listing_widget_sortby == 'most-viewed' ) {
            $qryvar_listing_sort_type = 'DESC';
            $qryvar_sort_by_column = 'meta_value_num';
            $meta_key = 'wp_dp_listing_views_count';
        }
        if ( $listing_widget_sortby == 'recent' ) {
            $meta_key = 'wp_dp_listing_posted';
            $qryvar_listing_sort_type = 'DESC';
            $qryvar_sort_by_column = 'meta_value_num';
        }

        $element_filter_arr = array();
        if ( $listing_widget_sortby == 'featured' ) {

            $default_date_time_formate = 'd-m-Y H:i:s';

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
            if ( function_exists('wp_dp_listing_visibility_query_args') ) {
                $element_filter_arr = wp_dp_listing_visibility_query_args($element_filter_arr);
            }
        }
        
        if ( $listing_widget_sortby == 'recent' ) {
            $element_filter_arr[] = array(
                'key' => 'wp_dp_listing_status',
                'value' => 'active',
                'compare' => '=',
            );
        }
        
        $args_widget = array(
            'post_type' => 'listings',
            'post_status' => 'publish',
            'meta_key' => $meta_key,
            'order' => $qryvar_listing_sort_type,
            'orderby' => $qryvar_sort_by_column,
            'fields' => 'ids', // only load ids
            'posts_per_page' => $num_of_listing,
            'meta_query' => array(
                $element_filter_arr,
            ),
        );
        
        $widget_listing = new WP_Query($args_widget);
        $listing_location_options = 'city,country';
        if ( $listing_location_options != '' ) {
            $listing_location_options = explode(',', $listing_location_options);
        }

        if ( $listing_widget_views == 'grid' ) {
            ?>
            <div class="widget home-featured-widget">
                <?php if ( ! empty($widget_title) ) { ?>
                    <div class="widget-title"><h5><?php echo esc_html($widget_title); ?></h5></div>
                <?php } ?>
                <?php
                if ( $widget_listing->have_posts() ) :
                    while ( $widget_listing->have_posts() ) : $widget_listing->the_post();
                        global $post;
                        $listing_id = $post;
                        $wp_dp_listing_member = get_post_meta($listing_id, 'wp_dp_listing_member', true);
                        $Wp_dp_Locations = new Wp_dp_Locations();
                        $get_listing_location = $Wp_dp_Locations->get_element_listing_location($listing_id, $listing_location_options);
                        ?>
                        <div class="listing-list">
                            <div class="img-holder">
                                <figure> 
                                    <a href="<?php the_permalink(); ?>">
                                        <?php
                                        if ( function_exists('listing_gallery_first_image') ) {
                                            $gallery_image_args = array(
                                                'listing_id' => $listing_id,
                                                'size' => 'wp_dp_media_9',
                                                'class' => 'img-grid',
                                                'default_image_src' => esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg'),
                                                'img_extra_atr' => 'itemprop="image"',
                                            );
                                            $listing_gallery_first_image = listing_gallery_first_image($gallery_image_args);
                                            echo wp_dp_cs_allow_special_char($listing_gallery_first_image);
                                        }
                                        ?>
                                    </a>
                                </figure>
                            </div>
                            <div class="text-holder">
                                <?php
                                $member_image_id = get_post_meta($wp_dp_listing_member, 'wp_dp_profile_image', true);
                                $member_image = wp_get_attachment_image_src($member_image_id, 'thumbnail');
                                if ( $member_image == '' || FALSE == get_post_status($wp_dp_listing_member) ) {
                                    $member_image[0] = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/member-no-image.jpg');
                                }
                                if ( $member_image != '' && get_post_status($wp_dp_listing_member) ) {
                                    ?>
                                    <div class="thumb-img">
                                        <figure>
                                            <a href="<?php echo get_the_permalink($wp_dp_listing_member); ?>">
                                                <img src="<?php echo esc_url($member_image[0]); ?>" alt="" >
                                            </a>
                                        </figure>
                                    </div>
                                <?php }
                                ?>

                                <div class="widget-post-title">
                                    <h6> <a href="<?php echo esc_url(get_permalink($listing_id)); ?>"><?php echo (wp_dp_limit_text(get_the_title($listing_id), $featured_listing_title_length)); ?></a></h6>
                                </div>
                                <?php if ( ! empty($get_listing_location) ) { ?>
                                    <ul class="listing-location">
                                        <li><span><?php echo esc_html(implode(', ', $get_listing_location)); ?></span></li>
                                    </ul>
                                <?php }
                                ?>
                            </div>
                        </div>
                        <?php
                    endwhile;
                endif;
                ?>
            </div>
        <?php } if ( $listing_widget_views == 'medium' ) { ?>
            <div class="widget most-viewed-widget">
                <?php if ( ! empty($widget_title) ) { ?>
                    <div class="widget-title"><h5><?php echo esc_html($widget_title); ?></h5></div>
                <?php } ?>
                <?php
                if ( $widget_listing->have_posts() ) :
                    while ( $widget_listing->have_posts() ) : $widget_listing->the_post();
                        global $post;
                        $listing_id = $post;
                        $wp_dp_listing_member = get_post_meta($listing_id, 'wp_dp_listing_member', true);
                        $Wp_dp_Locations = new Wp_dp_Locations();
                        $get_listing_location = $Wp_dp_Locations->get_element_listing_location($listing_id, $listing_location_options);

                        $ratings_data = array(
                            'overall_rating' => 0.0,
                            'count' => 0,
                        );
                        $ratings_data = apply_filters('reviews_ratings_data', $ratings_data, $listing_id);

                        $listing_percentage = isset($ratings_data['overall_rating']) ? $ratings_data['overall_rating'] : 0;
                        $list_rating = (5 / 100) * $listing_percentage;
                        
                        ?>
                        <div class="listing-list">
                            <div class="img-holder">
                                <figure> 
                                    <a href="<?php the_permalink(); ?>">
                                        <?php
                                        if ( function_exists('listing_gallery_first_image') ) {
                                            $gallery_image_args = array(
                                                'listing_id' => $listing_id,
                                                'size' => 'thumbnail',
                                                'class' => 'img-grid',
                                                'default_image_src' => esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg'),
                                                'img_extra_atr' => 'itemprop="image"',
                                            );
                                            $listing_gallery_first_image = listing_gallery_first_image($gallery_image_args);
                                            echo wp_dp_cs_allow_special_char($listing_gallery_first_image);
                                        }
                                        ?>
                                    </a>
                                </figure>
                            </div>
                            <div class="text-holder">
                                <div class="widget-post-title">
                                    <h6> <a href="<?php echo esc_url(get_permalink($listing_id)); ?>"><?php echo (wp_dp_limit_text(get_the_title($listing_id), $featured_listing_title_length)); ?></a></h6>
                                </div>
                                <?php if ( $list_rating > 0 ) { ?>
                                    <span class="widget-reviews-count"><?php echo sprintf("%.1f", $list_rating); ?></span>
                                <?php } ?>
                                <?php if ( ! empty($get_listing_location) ) { ?>
                                    <ul class="listing-location">
                                        <li><span><?php echo esc_html(implode(', ', $get_listing_location)); ?></span></li>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                    endwhile;
                endif;
                ?>
            </div>
            <?php
        }
    }

}
}
add_action('widgets_init', function(){ return register_widget("Wp_dp_listings_Widget"); } );
