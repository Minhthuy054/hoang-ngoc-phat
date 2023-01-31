<?php

/**
 * @Ads widget Class
 *
 *
 */
if ( ! class_exists( 'wp_dp_banner_ads' ) ) {

    class wp_dp_banner_ads extends WP_Widget {

        /**
         * @init Ads Module
         *
         *
         */
        public function __construct() {

            parent::__construct(
                    'wp_dp_banner_ads', // Base ID
                    wp_dp_plugin_text_srt('wp_dp_banner_add'), // Name
                    array( 'classname' => 'csbanner', 'description' => wp_dp_plugin_text_srt('wp_dp_banner_add_desc'), ) // Args
            );
        }

        /**
         * @Ads html form
         *
         *
         */
        function form( $instance ) {
            global $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_static_text;

            $cs_rand_id = rand( 23789, 934578930 );
            $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'banner_code' => '' ) );
            $title = $instance['title'];
            $banner_style = isset( $instance['banner_style'] ) ? esc_attr( $instance['banner_style'] ) : '';
            $banner_code = $instance['banner_code'];
            $banner_view = isset( $instance['banner_view'] ) ? esc_attr( $instance['banner_view'] ) : '';
            $showcount = isset( $instance['showcount'] ) ? esc_attr( $instance['showcount'] ) : '';


            $strings = new wp_dp_plugin_all_strings_1;
            $strings->wp_dp_plugin_strings();

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt( 'wp_dp_banner_title_field' ),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => esc_attr( $title ),
                    'id' => ($this->get_field_id( 'title' )),
                    'classes' => '',
                    'cust_id' => ($this->get_field_name( 'title' )),
                    'cust_name' => ($this->get_field_name( 'title' )),
                    'return' => true,
                    'required' => false
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt( 'wp_dp_banner_view' ),
                'hint_text' => wp_dp_plugin_text_srt( 'wp_dp_banner_view_hint' ),
                'echo' => true,
                'field_params' => array(
                    'std' => esc_attr( $banner_view ),
                    'cust_id' => ($this->get_field_id( 'banner_view' )),
                    'cust_name' => ($this->get_field_name( 'banner_view' )),
                    'extra_atr' => 'onchange="javascript:banner_widget_toggle(this.value ,  \'' . $cs_rand_id . '\')"',
                    'desc' => '',
                    'classes' => 'chosen-select',
                    'options' =>
                    array(
                        'single' => wp_dp_plugin_text_srt( 'wp_dp_banner_single_banner' ),
                        'random' => wp_dp_plugin_text_srt( 'wp_dp_banner_random_banner' ),
                    ),
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_select_field( $wp_dp_opt_array );

            $display_single = wp_dp_cs_allow_special_char( $banner_view ) == 'random' ? 'block' : 'none';

            echo '<div class="banner_style_field_' . esc_attr( $cs_rand_id ) . '" style="display:' . esc_html( $display_single ) . '">';

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt( 'wp_dp_banner_style' ),
                'hint_text' => wp_dp_plugin_text_srt( 'wp_dp_banner_style_hint' ),
                'echo' => true,
                'field_params' => array(
                    'std' => esc_attr( $banner_style ),
                    'cust_id' => ($this->get_field_id( 'banner_style' )),
                    'cust_name' => ($this->get_field_name( 'banner_style' )),
                    'desc' => '',
                    'classes' => 'chosen-select',
                    'options' =>
                    array(
                        'top_banner' => wp_dp_plugin_text_srt( 'wp_dp_banner_type_top' ),
                        'bottom_banner' => wp_dp_plugin_text_srt( 'wp_dp_banner_type_bottom' ),
                        'sidebar_banner' => wp_dp_plugin_text_srt( 'wp_dp_banner_type_sidebar' ),
                        'vertical_banner' => wp_dp_plugin_text_srt( 'wp_dp_banner_type_vertical' ),
                        'listing_detail_banner' => wp_dp_plugin_text_srt( 'wp_dp_banner_type_listing_detail' ),
                        'listing_banner' => wp_dp_plugin_text_srt( 'wp_dp_banner_type_listing' ),
                        'listing_banner_leftfilter' => wp_dp_plugin_text_srt( 'wp_dp_banner_type_listing_leftfilter' ),
						'member_banner' => wp_dp_plugin_text_srt('wp_dp_banner_type_member'),
                    ),
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_select_field( $wp_dp_opt_array );



            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt( 'wp_dp_banner_no_of_banner' ),
                'desc' => '',
                'hint_text' => wp_dp_plugin_text_srt( 'wp_dp_banner_no_of_banner_hint' ),
                'echo' => true,
                'field_params' => array(
                    'std' => esc_attr( $showcount ),
                    'id' => ($this->get_field_id( 'showcount' )),
                    'classes' => '',
                    'cust_id' => ($this->get_field_name( 'showcount' )),
                    'cust_name' => ($this->get_field_name( 'showcount' )),
                    'return' => true,
                    'required' => false
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );
            echo '</div>';
            $display_single = wp_dp_cs_allow_special_char( $banner_view ) == 'single' ? 'block' : 'none';
            echo '<div class="banner_code_field_' . esc_attr( $cs_rand_id ) . '" style="display:' . esc_html( $display_single ) . '">';
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt( 'wp_dp_banner_code' ),
                'desc' => '',
                'hint_text' => wp_dp_plugin_text_srt( 'wp_dp_banner_code_hint' ),
                'echo' => true,
                'field_params' => array(
                    'std' => esc_attr( $banner_code ),
                    'id' => ($this->get_field_id( 'banner_code' )),
                    'classes' => '',
                    'cust_id' => ($this->get_field_name( 'banner_code' )),
                    'cust_name' => ($this->get_field_name( 'banner_code' )),
                    'return' => true,
                    'required' => false
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );
            echo '</div>';
            ?>
            <script>
                function banner_widget_toggle(view, id) {
                    "use strict";
                    if (view == "random") {
                        jQuery(".banner_style_field_" + id).show();
                        jQuery(".banner_code_field_" + id).hide();
                    } else if (view == "single") {
                        jQuery(".banner_style_field_" + id).hide();
                        jQuery(".banner_code_field_" + id).show();
                    } else {
                        jQuery(".banner_code_field_" + id).show();
                    }
                }
            </script>

            <?php

        }

        /**
         * @Ads update form data
         *
         *
         */
        function update( $new_instance = array(), $old_instance = array() ) {
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['banner_style'] = esc_sql( $new_instance['banner_style'] );
            $instance['banner_code'] = $new_instance['banner_code'];
            $instance['banner_view'] = esc_sql( $new_instance['banner_view'] );
            $instance['showcount'] = esc_sql( $new_instance['showcount'] );
            return $instance;
        }

        /**
         * @Display Ads widget
         *
         */
        function widget( $args = array(), $instance = array() ) {
            extract( $args, EXTR_SKIP );
            global $wpdb, $post, $wp_dp_plugin_options;
            $title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
            $title = htmlspecialchars_decode( stripslashes( $title ) );
            
            $banner_style = empty( $instance['banner_style'] ) ? ' ' : apply_filters( 'widget_title', $instance['banner_style'] );
            $banner_code = empty( $instance['banner_code'] ) ? ' ' : $instance['banner_code'];
            $banner_view = empty( $instance['banner_view'] ) ? ' ' : apply_filters( 'widget_title', $instance['banner_view'] );
            $showcount = $instance['showcount'];
            // WIDGET display CODE Start
            echo balanceTags( $before_widget, false );
            
            if( isset( $instance['title'] ) && $instance['title'] != '' ){
                if ( strlen( $title ) <> 1 || strlen( $title ) <> 0 ) {
                    echo balanceTags( $before_title . $title . $after_title, false );
                }
            }
            $showcount = ( $showcount <> '' || ! is_integer( $showcount ) ) ? $showcount : 2;

            if ( $banner_view == 'single' ) {
                echo do_shortcode( $banner_code );
            } else {

                $cs_total_banners = ( is_integer( $showcount ) && $showcount > 10) ? 10 : $showcount;

                if ( isset( $wp_dp_plugin_options['wp_dp_banner_title'] ) ) {
                    $i = 0;
                    $d = 0;
                    $cs_banner_array = array();
                    foreach ( $wp_dp_plugin_options['wp_dp_banner_title'] as $banner ) :

                        if ( $wp_dp_plugin_options['wp_dp_banner_style'][$i] == $banner_style ) {
                            $cs_banner_array[] = $i;
                            $d ++;
                        }
                        if ( $cs_total_banners == $d ) {
                            break;
                        }
                        $i ++;
                    endforeach;
                    if ( sizeof( $cs_banner_array ) > 0 ) {
                        if ( sizeof( $cs_banner_array ) > 1 ) {
                            $cs_act_size = sizeof( $cs_banner_array ) - 1;
                            $cs_rand_banner = rand( 0, $cs_act_size );
                        } else {
                            $cs_rand_banner = 0;
                        }

                        $rand_banner = $cs_banner_array[$cs_rand_banner];

                        echo do_shortcode( '[wp_dp_ads id="' . $wp_dp_plugin_options['wp_dp_banner_field_code_no'][$rand_banner] . '"]' );
                    }
                }
            }

            echo balanceTags( $after_widget, false );
        }

    }

}
add_action( 'widgets_init', function(){ return register_widget("wp_dp_banner_ads"); } );



