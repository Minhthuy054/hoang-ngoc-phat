<?php
/**
 * File Type: Services Page Element
 */
if ( ! class_exists('wp_dp_contact_element') ) {

    class wp_dp_contact_element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_dp_contact_element_html', array( $this, 'wp_dp_contact_element_html_callback' ), 11, 1);
            add_action('wp_dp_contact_form_element_html', array( $this, 'wp_dp_contact_form_element_html_callback' ), 11, 1);
        }

        /*
         * Output features html for frontend on listing detail page.
         */

        public function wp_dp_contact_element_html_callback($post_id) {
            global $wp_dp_plugin_options;


            // listing type fields
            $wp_dp_listing_type = get_post_meta($post_id, 'wp_dp_listing_type', true);
            $list_type = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type');
            $listing_type_id = isset($list_type) ? $list_type->ID : '';
			$listing_type_id = wp_dp_wpml_lang_page_id( $listing_type_id, 'listing-type' );
            $listing_type_marker_image_id = get_post_meta($listing_type_id, 'wp_dp_listing_type_marker_image', true);
            $listing_type_marker_image = $listing_type_marker_image_id;

            $wp_dp_listing_type_loc_map_switch = get_post_meta($listing_type_id, 'wp_dp_location_element', true);
            $wp_dp_listing_type_open_hours_switch = get_post_meta($listing_type_id, 'wp_dp_opening_hours_element', true);

            // listing fields
            $wp_dp_post_loc_address_listing = get_post_meta($post_id, 'wp_dp_post_loc_address_listing', true);
            $wp_dp_post_loc_latitude = get_post_meta($post_id, 'wp_dp_post_loc_latitude_listing', true);
            $wp_dp_post_loc_longitude = get_post_meta($post_id, 'wp_dp_post_loc_longitude_listing', true);
            $wp_dp_loc_radius_listing = get_post_meta($post_id, 'wp_dp_loc_radius_listing', true);

            // user profile fields

            $user_profile_data = get_post_meta($post_id, 'wp_dp_user_profile_data', true);
            // package fields
            $wp_dp_user_phone_number = get_post_meta($post_id, 'wp_dp_listing_contact_phone', true);
            $wp_dp_user_website = get_post_meta($post_id, 'wp_dp_listing_contact_web', true);
            $wp_dp_user_email = get_post_meta($post_id, 'wp_dp_listing_contact_email', true);
            $phone_number_website_limit = wp_dp_cred_limit_check($post_id, 'wp_dp_transaction_listing_phone_wesite');
            $map_zoom_level_default = isset($wp_dp_plugin_options['wp_dp_map_zoom_level']) ? $wp_dp_plugin_options['wp_dp_map_zoom_level'] : '10';
            $map_zoom_level_post = get_post_meta($post_id, 'wp_dp_post_loc_zoom_listing', true);
            if ( $map_zoom_level_post == '' || ! isset($map_zoom_level_post) ) {
                $map_zoom_level_post = $map_zoom_level_default;
            }
            if ( $wp_dp_loc_radius_listing == 'on' ) {
                $listing_type_marker_image = ' ';
            }
            if ( isset($wp_dp_plugin_options['wp_dp_plugin_gallery_contact']) && $wp_dp_plugin_options['wp_dp_plugin_gallery_contact'] == 'content' ) {

                $wp_dp_post_loc_address_listing = wp_dp_limit_text($wp_dp_post_loc_address_listing, 12);
                $wp_dp_listing_type_loc_map_switch = 'on';
                if ( $wp_dp_listing_type_loc_map_switch == 'on' && $wp_dp_post_loc_longitude != '' && $wp_dp_post_loc_latitude != '' ) {
                    ?>
                    <div class="map-sec-holder">
                        <?php
                        $map_atts = array(
                            'map_height' => '180',
                            'map_lat' => $wp_dp_post_loc_latitude,
                            'map_lon' => $wp_dp_post_loc_longitude,
                            'map_zoom' => $map_zoom_level_post,
                            'map_type' => '',
                            'map_info' => '',
                            'map_info_width' => '200',
                            'map_info_height' => '200',
                            'map_marker_icon' => $listing_type_marker_image,
                            'map_show_marker' => 'true',
                            'map_controls' => 'true',
                            'map_draggable' => 'true',
                            'map_scrollwheel' => 'false',
                            'map_border' => '',
                            'map_border_color' => '',
                            'wp_dp_map_style' => '',
                            'wp_dp_map_class' => '',
                            'wp_dp_map_directions' => 'off',
                            'wp_dp_map_circle' => $wp_dp_loc_radius_listing,
                        );
                        wp_dp_map_content($map_atts);
                        ?>

                    </div>
                    <?php
                }

                if ( $wp_dp_listing_type_loc_map_switch == 'on' || $wp_dp_listing_type_open_hours_switch == 'on' ) {
                    ?>
                    <div class="contact-info-detail">
                        <div class="row">
                            <?php
                            $contact_flag = false;
                            if ( ( $phone_number_website_limit == 'on' && $wp_dp_user_phone_number != '' ) || ( $phone_number_website_limit == 'on' && $wp_dp_user_website != '' ) || $wp_dp_user_email != '' || $wp_dp_post_loc_address_listing != '' ) {
                                $contact_flag = true;
                            }
                            if ( $wp_dp_listing_type_loc_map_switch == 'on' ) {
                                ?>
                                <?php if ( $contact_flag ) { ?>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="contact-info">

                                            <h4><?php echo wp_dp_plugin_text_srt('wp_dp_contact_details'); ?></h4>
                                            <?php echo apply_filters('the_content', $wp_dp_post_loc_address_listing); ?>
                                            <ul>
                                                <?php if ( $phone_number_website_limit == 'on' && $wp_dp_user_phone_number != '' ) { ?>
                                                    <li class="cell"><i class=" icon-phone2"></i><a href="tel:<?php echo esc_html($wp_dp_user_phone_number); ?>"><?php echo esc_html($wp_dp_user_phone_number); ?></a></li>
                                                <?php } ?>
                                                <?php if ( $phone_number_website_limit == 'on' && $wp_dp_user_website != '' ) { ?>
                                                    <li class="pizzaeast-"><i class="icon-globe3"></i><a href="<?php echo esc_url($wp_dp_user_website); ?>"><?php echo esc_html($wp_dp_user_website); ?></a></li>
                                                <?php } ?>
                                                <?php if ( $wp_dp_user_email != '' ) { ?>
                                                    <li class="email"><i class="icon-mail"></i><a href="mailto:<?php echo sanitize_email($wp_dp_user_email); ?>" class="text-color"><?php echo wp_dp_plugin_text_srt('wp_dp_contact_send_enquiry'); ?></a></li>
                                                <?php } ?>
                                            </ul>

                                        </div>
                                    </div>
                                <?php } ?>
                                <?php
                            }
                            if ( $wp_dp_listing_type_open_hours_switch == 'on' ) {
                                ?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <?php do_action('wp_dp_opening_hours_element_html', $post_id, 'none'); ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
            } elseif ( isset($wp_dp_plugin_options['wp_dp_plugin_gallery_contact']) && $wp_dp_plugin_options['wp_dp_plugin_gallery_contact'] == 'sidebar' ) {
                // listing fields
                $wp_dp_post_loc_address_listing = get_post_meta($post_id, 'wp_dp_post_loc_address_listing', true);
                $wp_dp_post_loc_latitude = get_post_meta($post_id, 'wp_dp_post_loc_latitude_listing', true);
                $wp_dp_post_loc_longitude = get_post_meta($post_id, 'wp_dp_post_loc_longitude_listing', true);

                if ( $wp_dp_listing_type_loc_map_switch == 'on' ) {
                    ?>
                    <div class="widget widget-contact">
                        <div class="widget-map">
                            <?php
                            if ( $wp_dp_post_loc_longitude != '' && $wp_dp_post_loc_latitude != '' ) {
                                $map_atts = array(
                                    'map_height' => '180',
                                    'map_lat' => $wp_dp_post_loc_latitude,
                                    'map_lon' => $wp_dp_post_loc_longitude,
                                    'map_zoom' => $map_zoom_level_post,
                                    'map_type' => '',
                                    'map_info' => '',
                                    'map_info_width' => '200',
                                    'map_info_height' => '200',
                                    'map_marker_icon' => $listing_type_marker_image,
                                    'map_show_marker' => 'true',
                                    'map_controls' => 'true',
                                    'map_draggable' => 'true',
                                    'map_scrollwheel' => 'false',
                                    'map_border' => '',
                                    'map_border_color' => '',
                                    'wp_dp_map_style' => '',
                                    'wp_dp_map_class' => '',
                                    'wp_dp_map_directions' => 'off',
                                    'wp_dp_map_circle' => $wp_dp_loc_radius_listing,
                                );
                                wp_dp_map_content($map_atts);
                            }
                            ?>
                        </div>
                        <div class="text-holder">
                            <h4><?php echo wp_dp_plugin_text_srt('wp_dp_contact_details'); ?></h4>
                            <?php echo apply_filters('the_content', $wp_dp_post_loc_address_listing); ?>
                        </div>
                    </div>
                    <?php
                }
            }
        }

        /*
         * contact form for member listing
         */

        public function wp_dp_contact_form_element_html_callback($member) {
            global $wp_dp_form_fields, $wp_dp_plugin_options, $Wp_dp_Captcha;
            wp_enqueue_script('wp-dp-validation-script');
            $wp_dp_captcha_switch = '';
            $wp_dp_captcha_switch = isset($wp_dp_plugin_options['wp_dp_captcha_switch']) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';
            $wp_dp_sitekey = isset($wp_dp_plugin_options['wp_dp_sitekey']) ? $wp_dp_plugin_options['wp_dp_sitekey'] : '';
            $wp_dp_secretkey = isset($wp_dp_plugin_options['wp_dp_secretkey']) ? $wp_dp_plugin_options['wp_dp_secretkey'] : '';
            $wp_dp_term_policy_switch = isset($wp_dp_plugin_options['wp_dp_term_policy_switch']) ? $wp_dp_plugin_options['wp_dp_term_policy_switch'] : '';
            $wp_dp_cs_email_counter = rand(3433, 7865);
            $wp_dp_email_address = get_post_meta($member, 'wp_dp_email_address', true);
            $wp_dp_email_address = isset($wp_dp_email_address) ? $wp_dp_email_address : '';
            
            
            /*
             * sign in 
             */
            $user_id = $company_id = 0;
            $user_id = get_current_user_id();
            $display_name = '';
            $phone_number = '';
            $email_address = '';
            if ( $user_id != 0 ) {
                $company_id = get_user_meta($user_id, 'wp_dp_company', true);
                $user_data = get_userdata($user_id);
                $display_name = esc_html(get_the_title($company_id));
                $email_address = get_post_meta($company_id, 'wp_dp_email_address', true);
            }
            
            /*
             * sign in end
             */
            
            
            $target_modal = '';
            $target_class = ' wp-dp-open-signin-tab';
            if ( is_user_logged_in() ) {
                $target_class = '';
                $target_modal = ' data-toggle="modal" data-target="#member-contant-modal' . absint($member) . '"';
            }
            ?>
            <div class="member-contact">
                <a class="contact-btn <?php echo esc_attr($target_class); ?>" href="javascript:void(0);" <?php echo ($target_modal); ?>><i class="icon-contact_mail"></i> <?php echo wp_dp_plugin_text_srt('wp_dp_contact_heading'); ?></a>
            </div> 
            <!-- Modal -->
            <div class="modal modal-form fade" id="member-contant-modal<?php echo absint($member); ?>" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo wp_dp_plugin_text_srt('wp_dp_contact_close'); ?>"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="contact-myModalLabel"><?php echo wp_dp_plugin_text_srt('wp_dp_contact_heading'); ?> <?php echo esc_html(get_the_title($member)); ?></h4>
                        </div>

                        <div class="modal-body" id="profile-valid-<?php echo intval($member); ?>">
						<?php do_action('wp_dp_enquiry_agent_contact_form_html', $member, 'member_detail', false);?>
                           
                        </div>
                    </div>
                </div>
            </div> 
            <?php
        }

    }

    global $wp_dp_contact_element;
    $wp_dp_contact_element = new wp_dp_contact_element();
}