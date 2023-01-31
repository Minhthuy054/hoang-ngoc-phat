<?php
/**
 * @Top Member widget Class
 *
 *
 */
if ( ! class_exists('wp_dp_top_members') ) {

    class wp_dp_top_members extends WP_Widget {
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
                    'wp_dp_top_members', // Base ID
                    wp_dp_plugin_text_srt('wp_dp_tp_members_widget_name'), // Name
                    array( 'classname' => 'widget_top_member', 'description' => wp_dp_plugin_text_srt('wp_dp_tp_members_widget_desc'), ) // Args
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
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_top_member_title_field'),
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
                'name' => wp_dp_plugin_text_srt('wp_dp_top_member_num_post'),
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
        }

        /**
         * @User list update data
         */
        function update($new_instance = array(), $old_instance = array()) {
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['showcount'] = esc_sql($new_instance['showcount']);
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
            // WIDGET display CODE Start
            echo balanceTags($before_widget, false);
            $cs_page_id = '';
            if ( isset($instance['title']) && $instance['title'] != '' ) {
                if ( strlen($title) <> 1 || strlen($title) <> 0 ) {
                    echo balanceTags($before_title . $title . $after_title, false);
                }
            }
            $showcount = $showcount <> '' ? $showcount : '10';
            $element_filter_arr[] = array(
                'key' => 'wp_dp_user_status',
                'value' => 'active',
                'compare' => '=',
            );
            $meta_key = 'wp_dp_num_of_listings';
            $qryvar_member_sort_type = 'DESC';
            $qryvar_sort_by_column = 'meta_value_num';
            $args = array(
                'posts_per_page' => $showcount,
                'post_type' => 'members',
                'post_status' => 'publish',
                'order' => $qryvar_member_sort_type,
                'orderby' => $qryvar_sort_by_column,
                'meta_key' => $meta_key,
                'fields' => 'ids', // only load ids 
                'meta_query' => array(
                    $element_filter_arr,
                ),
            );

            $member_loop_obj = wp_dp_get_cached_obj('top_member_result_cached_loop_obj', $args, 12, false, 'wp_query');
            if ( $member_loop_obj->have_posts() ) {
                ?>
                <div class="top-members-listing">
                    <?php
                    while ( $member_loop_obj->have_posts() ) : $member_loop_obj->the_post();
                        global $post;
                        $post_id = $post;
                        $member_id = get_post_meta($post_id, 'wp_dp_listing_member', true);
                        $member_image_id = get_post_meta($post_id, 'wp_dp_profile_image', true);
                        $member_image = wp_get_attachment_url($member_image_id);
                        $list_args = array(
                            'posts_per_page' => "-1",
                            'post_type' => 'listings',
                            'post_status' => 'publish',
                            'meta_query' => array(
                                'relation' => 'AND',
                                array(
                                    'key' => 'wp_dp_listing_member',
                                    'value' => $post_id,
                                    'compare' => '=',
                                ),
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
                            ),
                        );
                        $custom_query = new WP_Query($list_args);
                        $num_of_listings = $custom_query->post_count;
                        ?>
                        <div class="member-post"> 
                            <div class="img-holder">
                                <figure>
                                    <a title="<?php echo esc_html(get_the_title($member_id)); ?>" href="<?php the_permalink(); ?>">
                                        <?php
                                        if ( $member_image != '' ) {
                                            $no_image = '<img src="' . $member_image . '" alt=""/>';
                                            echo force_balance_tags($no_image);
                                        } else {
                                            $no_image_url = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg');
                                            $no_image = '<img class="img-grid" src="' . $no_image_url . '" alt=""/>';
                                            echo force_balance_tags($no_image);
                                        }
                                        ?>
                                    </a>
                                </figure>
                            </div>
                            <div class="text-holder">
                                <div class="post-title">
                                    <h4>
                                        <a title="<?php echo esc_html(get_the_title($member_id)); ?>" href="<?php the_permalink(); ?>"><?php echo esc_html(get_the_title($member_id)); ?></a> 
                                    </h4>
                                </div>
                                <?php
                                $listings_link_start = '';
                                $listings_link_end = '';
                                if ( $num_of_listings > 0 ) {
                                    $listings_link_start = '<a href="' . get_the_permalink($member_id) . '#listings">';
                                    $listings_link_end = '</a>';
                                }
                                ?>
                                <?php echo wp_dp_allow_special_char($listings_link_start); ?> 
                                <span class="listings-count"><?php echo absint($num_of_listings); ?>
                                    <?php echo wp_dp_plugin_text_srt('wp_dp_tp_members_widget_listings') ?>
                                </span>
                                <?php echo wp_dp_allow_special_char($listings_link_end); ?> 
                            </div>
                        </div> 
                        <?php
                    endwhile;
                    ?>
                </div>
                <?php
            } else {
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-member-match-error"><h6><i class="icon-warning"></i><strong> ' . wp_dp_plugin_text_srt('wp_dp_tp_members_widget_sorry') . '</strong>&nbsp; ' . wp_dp_plugin_text_srt('wp_dp_tp_members_widget_no_member_match') . ' </h6></div>';
            }
            echo balanceTags($after_widget, false);
        }

    }

}
add_action('widgets_init', function(){ return register_widget("wp_dp_top_members"); } );



