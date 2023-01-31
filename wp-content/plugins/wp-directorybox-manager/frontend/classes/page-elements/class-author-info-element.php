<?php
/**
 * File Type: Listing Author info Page Element
 */
if ( ! class_exists('wp_dp_author_info_element') ) {

    class wp_dp_author_info_element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_dp_author_info_html', array( $this, 'wp_dp_author_info_html_callback' ), 11, 2);
			add_action('wp_dp_author_short_info_html', array( $this, 'wp_dp_author_short_info_html_callback' ), 11, 2);
            add_filter('wp_dp_member_members_count', array( $this, 'wp_dp_member_members_count_callback' ), 11, 1);
        }

		public function wp_dp_author_short_info_html_callback($listing_id = '', $det_view = '') {
            global $post, $wp_dp_plugin_options, $Wp_dp_Captcha, $wp_dp_form_fields;

            $bottom_member_info = wp_dp_element_hide_show($listing_id, 'bottom_member_info');
            if ( $bottom_member_info != 'on' && $det_view != 'view-5' ) {
                return;
            }   
            if ( $listing_id == '' ) {
                $listing_id = $post->ID;
            }
            if ( $listing_id != '' ) {

                $wp_dp_listing_member_id = get_post_meta($listing_id, 'wp_dp_listing_member', true);
                if ( isset($wp_dp_listing_member_id) && $wp_dp_listing_member_id <> '' && TRUE == get_post_status($wp_dp_listing_member_id) ) {
                    ?>
                    <div class="profile-info detail-view-5">
                        <?php
                        $member_image_id = get_post_meta($wp_dp_listing_member_id, 'wp_dp_profile_image', true);
                        $member_image = wp_get_attachment_url($member_image_id);
                        if ( $member_image == '' ) {
                            $member_image = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/member-no-image.jpg');
                        }
                        $wp_dp_member_title = '';
                        if ( isset($wp_dp_listing_member_id) && $wp_dp_listing_member_id <> '' ) {
                            $wp_dp_member_title = get_the_title($wp_dp_listing_member_id);
                        }
                        $wp_dp_post_loc_address_member = get_post_meta($wp_dp_listing_member_id, 'wp_dp_post_loc_address_member', true);
                        $wp_dp_member_phone_num = get_post_meta($wp_dp_listing_member_id, 'wp_dp_phone_number', true);
                        $wp_dp_member_email_address = get_post_meta($wp_dp_listing_member_id, 'wp_dp_email_address', true);
                        ?>
                        <?php if ( isset($member_image) && $member_image <> '' ) { ?>
                            <div class="img-holder">
                                <figure>
                                    <a href="<?php echo get_the_permalink($wp_dp_listing_member_id); ?>">
                                        <img src="<?php echo esc_url($member_image); ?>" alt="<?php esc_html($wp_dp_member_title); ?>" />
                                    </a>
                                </figure>
                            </div>
                        <?php } ?>
                        <div class="text-holder">
                            <?php if ( isset($wp_dp_member_title) && $wp_dp_member_title != '' ) { ?>
                                <a href="<?php echo get_the_permalink($wp_dp_listing_member_id); ?>">
                                    <h5><?php echo esc_html($wp_dp_member_title); ?></h5>
                                </a>
                            <?php } ?>  
							 <a href="<?php echo get_the_permalink($wp_dp_listing_member_id); ?>" class="profile-info-detail_link"><?php echo wp_dp_plugin_text_srt('wp_dp_author_info_view_profile'); ?></a>
                        </div>
                    </div>
                    <?php
                }
            }
        }

        public function wp_dp_author_info_html_callback($listing_id = '', $det_view = '') {
            global $post, $wp_dp_plugin_options, $Wp_dp_Captcha, $wp_dp_form_fields;

            $bottom_member_info = wp_dp_element_hide_show($listing_id, 'bottom_member_info');
            if ( $bottom_member_info != 'on' && $det_view != 'view-5' ) {
                return;
            }

            $wp_dp_captcha_switch = '';
            $wp_dp_captcha_switch = isset($wp_dp_plugin_options['wp_dp_captcha_switch']) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';
            $wp_dp_sitekey = isset($wp_dp_plugin_options['wp_dp_sitekey']) ? $wp_dp_plugin_options['wp_dp_sitekey'] : '';
            $wp_dp_secretkey = isset($wp_dp_plugin_options['wp_dp_secretkey']) ? $wp_dp_plugin_options['wp_dp_secretkey'] : '';
            if ( $listing_id == '' ) {
                $listing_id = $post->ID;
            }
            if ( $listing_id != '' ) {

                $wp_dp_listing_member_id = get_post_meta($listing_id, 'wp_dp_listing_member', true);
                if ( isset($wp_dp_listing_member_id) && $wp_dp_listing_member_id <> '' && TRUE == get_post_status($wp_dp_listing_member_id) ) {
                    ?>
                    <div <?php if ( $det_view != 'view-5' ) { ?>id="email-friend" <?php } ?>class="profile-info detail-<?php echo sanitize_html_class($det_view) ?>">
                        <?php
                        $member_image_id = get_post_meta($wp_dp_listing_member_id, 'wp_dp_profile_image', true);
                        $member_image = wp_get_attachment_url($member_image_id);
                        if ( $member_image == '' ) {
                            $member_image = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/member-no-image.jpg');
                        }
                        $wp_dp_member_title = '';
                        if ( isset($wp_dp_listing_member_id) && $wp_dp_listing_member_id <> '' ) {
                            $wp_dp_member_title = get_the_title($wp_dp_listing_member_id);
                        }
                        $wp_dp_post_loc_address_member = get_post_meta($wp_dp_listing_member_id, 'wp_dp_post_loc_address_member', true);
                        $wp_dp_member_phone_num = get_post_meta($wp_dp_listing_member_id, 'wp_dp_phone_number', true);
                        $wp_dp_member_email_address = get_post_meta($wp_dp_listing_member_id, 'wp_dp_email_address', true);
                        ?>
                        <?php if ( isset($member_image) && $member_image <> '' ) { ?>
                            <div class="img-holder">
                                <figure>
                                    <a href="<?php echo get_the_permalink($wp_dp_listing_member_id); ?>">
                                        <img src="<?php echo esc_url($member_image); ?>" alt="<?php esc_html($wp_dp_member_title); ?>" />
                                    </a>
                                </figure>
                            </div>
                        <?php } ?>
                        <div class="text-holder">
                            <?php if ( isset($wp_dp_member_title) && $wp_dp_member_title != '' ) { ?>
                                <a href="<?php echo get_the_permalink($wp_dp_listing_member_id); ?>">
                                    <h5><?php echo esc_html($wp_dp_member_title); ?></h5>
                                </a>
                            <?php } ?>
                            <ul>
                                <?php if ( isset($wp_dp_post_loc_address_member) && $wp_dp_post_loc_address_member != '' ) { ?>
                                    <li><?php if ( $det_view != 'view-5' ) { ?><i class="icon-location"></i><?php } echo esc_html($wp_dp_post_loc_address_member); ?></li>
                                <?php } ?>
                                <?php
                                if ( isset($wp_dp_member_phone_num) && $wp_dp_member_phone_num != '' ) {
                                    $icon_class = '';
                                    if ( $det_view != 'view-5' ) {
                                        $icon_class = 'icon-phone3';
                                    }
                                    $all_data = '';
                                    $all_data = $this->wp_dp_member_member_phone_num($wp_dp_member_phone_num, $icon_class);
                                    echo force_balance_tags($all_data);
                                }
                                ?>
                            </ul>
                            <?php
                            ob_start();
                            $prop_det_view = 'none';
                            if ( $det_view == 'view-5' ) {
                                $prop_det_view = 'listing-v5';
                            }
                            do_action('wp_dp_opening_hours_element_html', $wp_dp_listing_member_id, $prop_det_view);
                            $opening_hours_val = ob_get_clean();
                            if ( $opening_hours_val ) {
                                ?>
                                <div class="field-select-holder">
                                    <?php echo do_action('wp_dp_opening_hours_element_html', $wp_dp_listing_member_id, $prop_det_view); ?>
                                </div>
                            <?php } ?>
                            <?php
                            if ( $det_view != 'view-5' ) {
                                $target_modal = 'data-toggle="modal" data-target="#sign-in"';
                                $target_class = ' wp-dp-open-signin-tab';
                                if ( is_user_logged_in() ) {
                                    $target_class = '';
                                    $target_modal = ' data-toggle="modal" data-target="#enquiry-modal"';
                                }
                                ?>
                                <a href="javascript:void(0);" class="submit-btn <?php echo esc_attr($target_class); ?>" <?php echo ($target_modal); ?>><?php echo wp_dp_plugin_text_srt('wp_dp_author_info_request_details'); ?></a>
			    <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
            }
        }

        /*
         * get member count by member id
         */

        public function wp_dp_member_members_count_callback($member_id) {

            $team_args = array(
                'role' => 'wp_dp_member',
                'meta_query' => array(
                    array(
                        'key' => 'wp_dp_company',
                        'value' => $member_id,
                        'compare' => '='
                    ),
                    array(
                        'key' => 'wp_dp_user_status',
                        'value' => 'deleted',
                        'compare' => '!='
                    )
                    , array(
                        'key' => 'wp_dp_public_profile',
                        'value' => 'yes',
                        'compare' => '='
                    ),
                ),
            );
            $custom_query_listing = new WP_User_Query($team_args);
            $users_count = (int) $custom_query_listing->get_total();
            return $users_count;
        }

        public function wp_dp_member_member_phone_num($post_id = '', $icon_class = '', $before_html = '', $after_html = '', $phone_span_extra_attr = '' ,$em_add = '') {

            if ( empty($post_id) ) {
                return;
            }
            $before = '<li>';
            $after = '</li>';
            if ( ! empty($before_html) ) {
                $before = $before_html;
            }
            if ( ! empty($after_html) ) {
                $after = $after_html;
            }
            $rand_id = rand(123, 8675432);
            $icons_class = '';
            if ( ! empty($icon_class) ) {
                $icons_class = '<i class="' . $icon_class . '"></i>';
            }
            
            if($em_add == true){
                $icons_class = '<em>'.$icons_class.'</em>';
            }
            
            
            $wp_dp_member_phone_num = $post_id;
            if ( isset($wp_dp_member_phone_num) && $wp_dp_member_phone_num != '' ) {
                $new_phone_num = $wp_dp_member_phone_num;
                if ( strlen($wp_dp_member_phone_num) > 4 ) {
                    wp_enqueue_script('wp_dp_encryption_js');
                    ?>
                    <script>
                        jQuery(document).ready(function ($) {
                            var encodedString = decode64("<?php echo substr($wp_dp_member_phone_num, -4); ?>");
                            $("#abs-<?php echo intval($rand_id); ?>").attr("data-onum", encodedString);
                        });
                    </script>
                    <?php
                    $new_phone_num = '<span ' . $phone_span_extra_attr . ' id="abs-' . $rand_id . '" class="sh-hde-cnt-num sh-hde-cnt-num-' . $rand_id . '" data-onum=""><a class="cntct-num-hold" href="tel:' . str_replace(' ', '', substr($wp_dp_member_phone_num, 0, (strlen($wp_dp_member_phone_num) - 4))) . '">' . substr($wp_dp_member_phone_num, 0, (strlen($wp_dp_member_phone_num) - 4)) . '<span class="ch-cntct-num">xxxx</span></a> <a href="javascript:void(0)" class="ch-cnt-show-num ch-cnt-show-num-' . $rand_id . '">' . wp_dp_plugin_text_srt('wp_dp_prop_detail_contact_cnt_num_show') . '</a></span>';
                }
                $html = $before . $icons_class . ($new_phone_num) . $after;
                ?>
                <script>
                    jQuery(document).on("click", ".ch-cnt-show-num-<?php echo intval($rand_id); ?>", function () {
                        var main_dvi = jQuery(".sh-hde-cnt-num-<?php echo intval($rand_id); ?>");
                        var chnge_dvi = main_dvi.find(".ch-cntct-num");
                        var valuer = main_dvi.data("onum");
                        var decodedString = encodePlain(valuer);
                        if (chnge_dvi.html() == "xxxx") {
                            chnge_dvi.html(decodedString);
                            jQuery(this).html("<?php echo wp_dp_plugin_text_srt('wp_dp_prop_detail_contact_cnt_num_hide'); ?>");
                        } else {
                            chnge_dvi.html("xxxx");
                            jQuery(this).html(" <?php echo wp_dp_plugin_text_srt('wp_dp_prop_detail_contact_cnt_num_show'); ?>");
                        }
                    });
                </script>
                <?php
                return $html;
            }
        }

    }

    global $wp_dp_author_info;
    $wp_dp_author_info = new wp_dp_author_info_element();
}