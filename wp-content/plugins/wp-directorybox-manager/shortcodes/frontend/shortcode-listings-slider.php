<?php
/**
 * File Type: Listings Slider Shortcode Frontend
 */
if ( ! class_exists('Wp_dp_Shortcode_Listings_Slider_Frontend') ) {

    class Wp_dp_Shortcode_Listings_Slider_Frontend {

        /**
         * Constant variables
         */
        var $PREFIX = 'wp_dp_listings_slider';

        public function __construct() {
            add_shortcode($this->PREFIX, array( $this, 'wp_dp_listings_slider_shortcode_callback' ));
        }

        public function wp_dp_listings_slider_shortcode_callback($atts, $content = "") {
            do_action('wp_dp_notes_frontend_modal_popup');
            $listing_short_counter = isset($atts['listing_counter']) && $atts['listing_counter'] != '' ? ( $atts['listing_counter'] ) : rand(123, 9999);
            $icons_groups = get_option('cs_icons_groups');
            if ( ! empty($icons_groups) ) {
                foreach ( $icons_groups as $icon_key => $icon_obj ) {
                    if ( isset($icon_obj['status']) && $icon_obj['status'] == 'on' ) {
                        wp_enqueue_style('cs_icons_data_css_' . $icon_key, $icon_obj['url'] . '/style.css');
                    }
                }
            }
            ob_start();
            $page_element_size = isset($atts['wp_dp_listings_slider_element_size']) ? $atts['wp_dp_listings_slider_element_size'] : 100;
            if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
                echo '<div class="' . wp_dp_cs_var_page_builder_element_sizes($page_element_size,$atts) . ' ">';
            }
            wp_enqueue_script('wp-dp-listing-functions');
            wp_enqueue_script('wp-dp-matchHeight-script');
            ?>
            <div class="row">
                <?php
                do_action('listing_checks_enquire_lists_submit');
                do_action('wp_dp_listing_compare_sidebar');
                do_action('wp_dp_listing_enquiries_sidebar');
                ?>
                <div class="wp-dp-listing-content" id="wp-dp-listing-content-<?php echo esc_html($listing_short_counter); ?>">
                    <div id="Listing-content-<?php echo esc_html($listing_short_counter); ?>">
                        <?php
                        $listing_arg = array(
                            'listing_short_counter' => $listing_short_counter,
                            'atts' => $atts,
                            'content' => $content,
                        );
                        $this->wp_dp_listings_content($listing_arg);
                        ?>
                    </div>
                </div>   
            </div>
            <?php
            if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
                echo '</div>';
            }
            $html = ob_get_clean();
            return $html;
        }

        public function wp_dp_listings_content($listing_arg = '') {
            global $wpdb, $wp_dp_form_fields_frontend;

            if ( isset($_REQUEST['listing_arg']) && $_REQUEST['listing_arg'] ) {
                $listing_arg = $_REQUEST['listing_arg'];
                $listing_arg = json_decode(str_replace('\"', '"', $listing_arg));
                $listing_arg = $this->toArray($listing_arg);
            }
            if ( isset($listing_arg) && $listing_arg != '' && ! empty($listing_arg) ) {
                extract($listing_arg);
            }

            $default_date_time_formate = 'd-m-Y H:i:s';
            $listing_view = 'slider';
            $listing_sort_by = 'recent'; // default value
            $listing_sort_order = 'desc';   // default value

            $listing_type = isset($atts['listing_type']) ? $atts['listing_type'] : '';
			$listing_listing_urgent = isset($atts['listing_urgent']) ? $atts['listing_urgent'] : 'all';
            $listing_sort_by = isset($atts['listing_sort_by']) ? $atts['listing_sort_by'] : 'recent';
            $posts_per_page = isset($atts['posts_per_page']) ? $atts['posts_per_page'] : '10';
            $content_columns = 'page-content col-lg-12 col-md-12 col-sm-12 col-xs-12'; // if filteration not true

            $element_filter_arr = array();
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
            // if listing type
            if ( $listing_type != 'all' && $listing_type != '' ) {
                $element_filter_arr[] = array(
                    'key' => 'wp_dp_listing_type',
                    'value' => $listing_type,
                    'compare' => '=',
                );
            }
            // if featured listing
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
			
            // if listing sort by
            $meta_key = 'wp_dp_listing_posted';
            $qryvar_listing_sort_type = 'DESC';
            $qryvar_sort_by_column = 'meta_value_num';
            if ( $listing_sort_by == 'recent' ) {
				$meta_key = 'wp_dp_listing_posted';
				$qryvar_sort_by_column = 'meta_value_num';
                $qryvar_listing_sort_type = 'DESC';
            } elseif ( $listing_sort_by == 'alphabetical' ) {
                $qryvar_listing_sort_type = 'ASC';
                $qryvar_sort_by_column = 'title';
            }

            $args = array(
                'posts_per_page' => $posts_per_page,
                'post_type' => 'listings',
                'post_status' => 'publish',
				'meta_key' => $meta_key,
                'orderby' => $qryvar_sort_by_column,
                'order' => $qryvar_listing_sort_type,
                'fields' => 'ids', // only load ids
                'meta_query' => array(
                    $element_filter_arr,
                ),
            );

            $listing_loop_obj = new WP_Query($args);
            ?>
            <div class="<?php echo esc_html($content_columns); ?>">
                <div class="directorybox-listing">
                    <?php
                    set_query_var('listing_loop_obj', $listing_loop_obj);
                    set_query_var('listing_view', $listing_view);
                    set_query_var('listing_short_counter', $listing_short_counter);
                    set_query_var('atts', $atts);
                    wp_dp_get_template_part('listing', 'slider', 'listings');
                    ?>
                </div>
            </div>
            <?php
            wp_reset_postdata();
        }

    }

    global $wp_dp_shortcode_listings_slider_frontend;
    $wp_dp_shortcode_listings_slider_frontend = new Wp_dp_Shortcode_Listings_Slider_Frontend();
}