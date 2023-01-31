<?php
/**
 * File Type: Opening Hours
 */
if (!class_exists('wp_dp_opening_hours')) {

    class wp_dp_opening_hours {
        
        /**
         * Start construct Functions
         */
        public function __construct() {
            
            add_filter('wp_dp_opening_hours_admin_fields', array($this, 'wp_dp_opening_hours_admin_fields_callback'), 11, 2);
            add_action('save_post', array($this, 'wp_dp_insert_opening_hours'), 15);
        }
        
        public function wp_dp_opening_hours_admin_fields_callback( $post_id, $listing_type_slug ){
            global $wp_dp_html_fields, $post;
            $post_id                = ( isset( $post_id ) && $post_id != '' )? $post_id : $post->ID;
            $listing_type_post      = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish' ));
            $listing_type_id        = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
            $wp_dp_full_data    = get_post_meta( $listing_type_id, 'wp_dp_full_data', true );
            $lapse                  = 15;
            $wp_dp_opening_hours_gap        = get_post_meta( $listing_type_id, 'wp_dp_opening_hours_time_gap', true );
            if ( isset( $wp_dp_opening_hours_gap ) && $wp_dp_opening_hours_gap != '' ){
                $lapse              = $wp_dp_opening_hours_gap;
            }
            
            $html                   = '';
            if ( !isset( $wp_dp_full_data['wp_dp_opening_hours_element'] ) || $wp_dp_full_data['wp_dp_opening_hours_element'] != 'on' ){
                return $html = '';
            }
            
            $opening_hours_data     = get_post_meta( $post_id, 'wp_dp_opening_hours', true );
            $date       = date("Y/m/d 12:00");
            $time       = strtotime('12:00 am');
            $start_time = strtotime( $date. ' am' );
            $endtime   = strtotime( date("Y/m/d h:i a", strtotime('1440 minutes', $start_time)) );
            
            while( $start_time < $endtime ){
                $time   = date("h:i a", strtotime('+' . $lapse . ' minutes', $time));
                $hours[$time]   = $time;
                $time   = strtotime( $time );
                $start_time   = strtotime( date("Y/m/d h:i a", strtotime('+' . $lapse . ' minutes', $start_time)));
            }
            
            $html .= $wp_dp_html_fields->wp_dp_heading_render(
                array(
                    'name' => wp_dp_plugin_text_srt( 'wp_dp_listing_schedule_with_time' ),
                    'cust_name' => 'opening_hours',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => '',
                    'echo' => false,
                )
            );

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt( 'wp_dp_listing_monday' ),
                'id' => 'radius_fields',
                'desc' => '',
                'hint_text' => '',
                'echo' => false, 
                'fields_list' => array(
                    array(
                        'type' => 'select', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['monday']['opening_time'] ) )? $opening_hours_data['monday']['opening_time']:'',
                            'cust_name' => 'opening_hours[monday][opening_time]',
                            'id' => 'opening_hours[monday][opening_time]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_opening_time' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                            'options' => $hours,
                        ),
                    ),
                    array(
                        'type' => 'select', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['monday']['closing_time'] ) )? $opening_hours_data['monday']['closing_time']:'',
                            'cust_name' => 'opening_hours[monday][closing_time]',
                            'id' => 'opening_hours[monday][closing_time]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_closing_time' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                            'options' => $hours,
                        ),
                    ),

                    array(
                        'type' => 'checkbox', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['monday']['day_status'] ) )? $opening_hours_data['monday']['day_status']:'on',
                            'cust_name' => 'opening_hours[monday][day_status]',
                            'id' => 'opening_hours[monday][day_status]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_monday_on' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                        ),
                    ),
                ),
            );

            $html .= $wp_dp_html_fields->wp_dp_multi_fields($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt( 'wp_dp_listing_tuesday' ),
                'id' => 'radius_fields',
                'desc' => '',
                'hint_text' => '',
                'echo' => false, 
                'fields_list' => array(
                    array(
                        'type' => 'select', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['tuesday']['opening_time'] ) )? $opening_hours_data['tuesday']['opening_time']:'',
                            'cust_name' => 'opening_hours[tuesday][opening_time]',
                            'id' => 'opening_hours[tuesday][opening_time]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_opening_time' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                            'options' => $hours,
                        ),
                    ),
                    array(
                        'type' => 'select', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['tuesday']['closing_time'] ) )? $opening_hours_data['tuesday']['closing_time']:'',
                            'cust_name' => 'opening_hours[tuesday][closing_time]',
                            'id' => 'opening_hours[tuesday][closing_time]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_closing_time' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                            'options' => $hours,
                        ),
                    ),

                    array(
                        'type' => 'checkbox', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['tuesday']['day_status'] ) )? $opening_hours_data['tuesday']['day_status']:'on',
                            'cust_name' => 'opening_hours[tuesday][day_status]',
                            'id' => 'opening_hours[tuesday][day_status]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_tuesday_on' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                        ),
                    ),
                ),
            );

            $html .= $wp_dp_html_fields->wp_dp_multi_fields($wp_dp_opt_array);


            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt( 'wp_dp_listing_wednesday' ),
                'id' => 'radius_fields',
                'desc' => '',
                'hint_text' => '',
                'echo' => false, 
                'fields_list' => array(
                    array(
                        'type' => 'select', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['wednesday']['opening_time'] ) )? $opening_hours_data['wednesday']['opening_time']:'',
                            'cust_name' => 'opening_hours[wednesday][opening_time]',
                            'id' => 'opening_hours[wednesday][opening_time]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_opening_time' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                            'options' => $hours,
                        ),
                    ),
                    array(
                        'type' => 'select', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['wednesday']['closing_time'] ) )? $opening_hours_data['wednesday']['closing_time']:'',
                            'cust_name' => 'opening_hours[wednesday][closing_time]',
                            'id' => 'opening_hours[wednesday][closing_time]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_closing_time' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                            'options' => $hours,
                        ),
                    ),

                    array(
                        'type' => 'checkbox', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['wednesday']['day_status'] ) )? $opening_hours_data['wednesday']['day_status']:'on',
                            'cust_name' => 'opening_hours[wednesday][day_status]',
                            'id' => 'opening_hours[wednesday][day_status]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_wednesday_on' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                        ),
                    ),
                ),
            );

            $html .= $wp_dp_html_fields->wp_dp_multi_fields($wp_dp_opt_array);


            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt( 'wp_dp_listing_thursday' ),
                'id' => 'radius_fields',
                'desc' => '',
                'hint_text' => '',
                'echo' => false, 
                'fields_list' => array(
                    array(
                        'type' => 'select', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['thursday']['opening_time'] ) )? $opening_hours_data['thursday']['opening_time']:'',
                            'cust_name' => 'opening_hours[thursday][opening_time]',
                            'id' => 'opening_hours[thursday][opening_time]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_opening_time' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                            'options' => $hours,
                        ),
                    ),
                    array(
                        'type' => 'select', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['thursday']['closing_time'] ) )? $opening_hours_data['thursday']['closing_time']:'',
                            'cust_name' => 'opening_hours[thursday][closing_time]',
                            'id' => 'opening_hours[thursday][closing_time]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_closing_time' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                            'options' => $hours,
                        ),
                    ),

                    array(
                        'type' => 'checkbox', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['thursday']['day_status'] ) )? $opening_hours_data['thursday']['day_status']:'on',
                            'cust_name' => 'opening_hours[thursday][day_status]',
                            'id' => 'opening_hours[thursday][day_status]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_thursday_on' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                        ),
                    ),
                ),
            );

            $html .= $wp_dp_html_fields->wp_dp_multi_fields($wp_dp_opt_array);


            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt( 'wp_dp_listing_friday' ),
                'id' => 'radius_fields',
                'desc' => '',
                'hint_text' => '',
                'echo' => false, 
                'fields_list' => array(
                    array(
                        'type' => 'select', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['friday']['opening_time'] ) )? $opening_hours_data['friday']['opening_time']:'',
                            'cust_name' => 'opening_hours[friday][opening_time]',
                            'id' => 'opening_hours[friday][opening_time]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_opening_time' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                            'options' => $hours,
                        ),
                    ),
                    array(
                        'type' => 'select', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['friday']['closing_time'] ) )? $opening_hours_data['friday']['closing_time']:'',
                            'cust_name' => 'opening_hours[friday][closing_time]',
                            'id' => 'opening_hours[friday][closing_time]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_closing_time' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                            'options' => $hours,
                        ),
                    ),

                    array(
                        'type' => 'checkbox', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['friday']['day_status'] ) )? $opening_hours_data['friday']['day_status']:'on',
                            'cust_name' => 'opening_hours[friday][day_status]',
                            'id' => 'opening_hours[friday][day_status]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_friday_on' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                        ),
                    ),
                ),
            );

            $html .= $wp_dp_html_fields->wp_dp_multi_fields($wp_dp_opt_array);


            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt( 'wp_dp_listing_saturday' ),
                'id' => 'radius_fields',
                'desc' => '',
                'hint_text' => '',
                'echo' => false, 
                'fields_list' => array(
                    array(
                        'type' => 'select', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['saturday']['opening_time'] ) )? $opening_hours_data['saturday']['opening_time']:'',
                            'cust_name' => 'opening_hours[saturday][opening_time]',
                            'id' => 'opening_hours[saturday][opening_time]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_opening_time' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                            'options' => $hours,
                        ),
                    ),
                    array(
                        'type' => 'select', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['saturday']['closing_time'] ) )? $opening_hours_data['saturday']['closing_time']:'',
                            'cust_name' => 'opening_hours[saturday][closing_time]',
                            'id' => 'opening_hours[saturday][closing_time]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_closing_time' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                            'options' => $hours,
                        ),
                    ),

                    array(
                        'type' => 'checkbox', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['saturday']['day_status'] ) )? $opening_hours_data['saturday']['day_status']:'on',
                            'cust_name' => 'opening_hours[saturday][day_status]',
                            'id' => 'opening_hours[saturday][day_status]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_saturday_on' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                        ),
                    ),
                ),
            );

            $html .= $wp_dp_html_fields->wp_dp_multi_fields($wp_dp_opt_array);


            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt( 'wp_dp_listing_sunday' ),
                'id' => 'radius_fields',
                'desc' => '',
                'hint_text' => '',
                'echo' => false, 
                'fields_list' => array(
                    array(
                        'type' => 'select', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['sunday']['opening_time'] ) )? $opening_hours_data['sunday']['opening_time']:'',
                            'cust_name' => 'opening_hours[sunday][opening_time]',
                            'id' => 'opening_hours[sunday][opening_time]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_opening_time' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                            'options' => $hours,
                        ),
                    ),
                    array(
                        'type' => 'select', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['sunday']['closing_time'] ) )? $opening_hours_data['sunday']['closing_time']:'',
                            'cust_name' => 'opening_hours[sunday][closing_time]',
                            'id' => 'opening_hours[sunday][closing_time]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_closing_time' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                            'options' => $hours,
                        ),
                    ),

                    array(
                        'type' => 'checkbox', 'field_params' => array(
                            'std' => ( isset( $opening_hours_data['sunday']['day_status'] ) )? $opening_hours_data['sunday']['day_status']:'on',
                            'cust_name' => 'opening_hours[sunday][day_status]',
                            'id' => 'opening_hours[sunday][day_status]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_sunday_on' ) . '"',
                            'return' => true,
                            'classes' => 'input-small',
                        ),
                    ),
                ),
            );

            $html .= $wp_dp_html_fields->wp_dp_multi_fields($wp_dp_opt_array);
            
            return $html;
        }
        
        public function wp_dp_insert_opening_hours( $post_id ){
            if( isset( $_POST['opening_hours'] ) ){
                update_post_meta( $post_id, 'wp_dp_opening_hour', $_POST['opening_hours'] );
            }
        }
    }
    global $wp_dp_opening_hours;
    $wp_dp_opening_hours    = new wp_dp_opening_hours();
}