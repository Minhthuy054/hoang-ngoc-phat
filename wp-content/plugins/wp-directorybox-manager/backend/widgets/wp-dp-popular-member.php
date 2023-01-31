<?php
/**
 * @Popular Member widget Class
 *
 *
 */
if ( ! class_exists('wp_dp_popular_members') ) {

    class wp_dp_popular_members extends WP_Widget {
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
                    'wp_dp_popular_member', // Base ID
                    wp_dp_plugin_text_srt('wp_dp_popular_member_widget_name'), // Name
                    array( 'classname' => 'author-post-widget', 'description' => wp_dp_plugin_text_srt('wp_dp_popular_member_widget_desc'), ) // Args
            );
        }

        /**
         * @User list html form
         */
        function form($instance = array()) {
            global $wp_dp_html_fields;

            $instance = wp_parse_args((array) $instance, array( 'popular_memeber' => '' ));
            $popular_memeber = isset($instance['popular_memeber']) ? esc_attr($instance['popular_memeber']) : '';
            $element_filter_arr = array();
            $element_filter_arr[] = array(
                'key' => 'wp_dp_user_status',
                'value' => 'active',
                'compare' => '=',
            );

            $args = array(
                'posts_per_page' => -1,
                'post_type' => 'members',
                'post_status' => 'publish',
                'order' => 'ASC',
                'orderby' => 'post_title',
                'fields' => 'ids', // only load ids 
                'meta_query' => array(
                    $element_filter_arr,
                ),
            );

            $members_list = array();
            $member_query = new WP_Query($args);
            if ( $member_query->have_posts() ):
                while ( $member_query->have_posts() ): $member_query->the_post();
                    $post = get_post(get_the_ID());
                    $members_list[$post->post_name] = get_the_title(get_the_ID());
                endwhile;
            endif;
            wp_reset_postdata();


            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_popular_member_widget_choose_member'),
                'hint_text' => wp_dp_plugin_text_srt('wp_dp_popular_member_widget_choose_member_desc'),
                'echo' => true,
                'field_params' => array(
                    'cust_name' => wp_dp_allow_special_char($this->get_field_name('popular_memeber')),
                    'cust_id' => wp_dp_allow_special_char($this->get_field_id('popular_memeber')),
                    'return' => true,
                    'classes' => 'members',
                    'std' => $popular_memeber,
                    'options' => $members_list,
                ),
            );
            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    chosen_selectionbox();
                    popup_over();
                });
            </script>
            <?php
        }

        /**
         * @User list update data
         */
        function update($new_instance = array(), $old_instance = array()) {
            $instance = $old_instance;
            $instance['popular_memeber'] = esc_sql($new_instance['popular_memeber']);
            return $instance;
        }

        /**
         * @Display User list widget */
        function widget($args = array(), $instance = array()) {
            extract($args, EXTR_SKIP);
            $popular_memeber = $instance['popular_memeber'];
            // WIDGET display CODE Start

            if ( $post = get_page_by_path($popular_memeber, OBJECT, 'members') ) {
                $member_id = $post->ID;
            } else {
                $member_id = 0;
            }

            if ( $member_id != 0 && $member_id != '' && 'publish' == get_post_status($member_id) ) {
                $member_profile_image = get_post_meta($member_id, 'wp_dp_profile_image', true);
                $member_image = wp_get_attachment_image_src($member_profile_image, 'thumbnail');

                $member_address = get_post_meta($member_id, 'wp_dp_post_loc_address_member', true);
                $member_country = get_post_meta($member_id, 'wp_dp_post_loc_country_member', true);
                $member_state = get_post_meta($member_id, 'wp_dp_post_loc_state_member', true);
                $member_city = get_post_meta($member_id, 'wp_dp_post_loc_city_member', true);

                $member_location = array();
                if ( $member_city != '' ) {
                    $term = get_term_by('slug', $member_city, 'wp_dp_locations');
                    if ( $term ) {
                        $member_location[] = $term->name;
                    }
                }

                if ( $member_country != '' ) {
                    $term = get_term_by('slug', $member_country, 'wp_dp_locations');
                    if ( $term ) {
                        $member_location[] = $term->name;
                    }
                }

                $member_biography = get_post_meta($member_id, 'wp_dp_biography', true);
                $member_facebook = get_post_meta($member_id, 'wp_dp_facebook', true);
                $member_twitter = get_post_meta($member_id, 'wp_dp_twitter', true);
                $member_linkedIn = get_post_meta($member_id, 'wp_dp_linkedIn', true);

                echo balanceTags($before_widget, false);
                if ( isset($member_image) && ! empty($member_image) ) {
                    ?>
                    <div class="img-holder">
                        <figure>
                            <img src="<?php echo ($member_image[0]); ?>" alt="<?php echo esc_html(get_the_title($member_id)); ?>">
                        </figure>
                    </div>
                <?php } ?>
                <div class="text-holder">
                    <div class="author-title">
                        <strong><a href="<?php echo esc_url(get_permalink($member_id)); ?>"><?php echo esc_html(get_the_title($member_id)); ?></a></strong>
                    </div>
                    <?php if ( ! empty($member_location) && is_array($member_location) ) { ?>
                        <div class="author-address">
                            <i class="icon-location-pin2"></i>
                            <strong><?php echo implode(', ', $member_location); ?></strong>
                        </div>
                    <?php } ?>
                    <?php if ( $member_biography != '' ) { ?>
                        <p><?php echo wp_dp_limit_text($member_biography, 17, '...'); ?></p>
                    <?php } ?>
                    
                    <?php if ( $member_twitter != '' || $member_facebook != '' || $member_linkedIn != '' ) { ?>
                        <ul class="author-social-media">
                            <?php if ( $member_twitter != '' ) { ?>
                                <li><a href="<?php echo esc_url($member_twitter); ?>" data-original-title="twitter"><i class="icon-twitter2"></i></a></li>
                            <?php } ?>
                            <?php if ( $member_facebook != '' ) { ?>
                                <li><a href="<?php echo esc_url($member_facebook); ?>" data-original-title="facebook"><i class="icon-facebook5"></i></a></li>
                            <?php } ?>
                            <?php if ( $member_linkedIn != '' ) { ?>
                                <li><a href="<?php echo esc_url($member_linkedIn); ?>" data-original-title="linkedin"><i class="icon-linkedin4"></i></a></li>
                                    <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
                <?php
                echo balanceTags($after_widget, false);
            }
        }

    }

}
add_action('widgets_init',  function(){ return register_widget("wp_dp_popular_members"); });



