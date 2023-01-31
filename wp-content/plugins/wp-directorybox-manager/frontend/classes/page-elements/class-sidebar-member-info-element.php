<?php
/**
 * File Type: Listing Sidebar Member info Page Element
 */
if ( ! class_exists('wp_dp_sidebar_member_info_element') ) {

    class wp_dp_sidebar_member_info_element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_dp_sidebar_member_info_html', array( $this, 'wp_dp_sidebar_member_info_html_callback' ), 11, 2);
        }

        public function wp_dp_sidebar_member_info_html_callback($listing_id = '', $view = '') {
            global $wp_dp_plugin_options, $wp_dp_author_info;

            $sidebar_member_info = wp_dp_element_hide_show($listing_id, 'sidebar_member_info');
            if ( $sidebar_member_info != 'on' ) {
                return;
            }
            $wp_dp_listing_member_id = get_post_meta($listing_id, 'wp_dp_listing_member', true);
            $wp_dp_listing_member_id = isset($wp_dp_listing_member_id) ? $wp_dp_listing_member_id : '';
            $wp_dp_post_loc_address_member = get_post_meta($wp_dp_listing_member_id, 'wp_dp_post_loc_address_member', true);
            $wp_dp_member_title = '';
            if ( isset($wp_dp_listing_member_id) && $wp_dp_listing_member_id <> '' ) {
                $wp_dp_member_title = get_the_title($wp_dp_listing_member_id);
            }
            $wp_dp_member_link = 'javascript:void(0)';
            if ( isset($wp_dp_listing_member_id) && $wp_dp_listing_member_id <> '' ) {
                $wp_dp_member_link = get_the_permalink($wp_dp_listing_member_id);
            }
            $member_image_id = get_post_meta($wp_dp_listing_member_id, 'wp_dp_profile_image', true);
            $member_image = wp_get_attachment_url($member_image_id);
            $wp_dp_member_phone_num = get_post_meta($wp_dp_listing_member_id, 'wp_dp_phone_number', true);
            $wp_dp_member_email_address = get_post_meta($wp_dp_listing_member_id, 'wp_dp_email_address', true);
            $wp_dp_member_email_address = isset($wp_dp_member_email_address) ? $wp_dp_member_email_address : '';
            $http_request = wp_dp_server_protocol();
            ?>
            <div itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Person" class="profile-info boxed">
                <?php if ( isset($member_image) && $member_image != '' ) { ?>
                    <div class="img-holder">
                        <figure>
                            <a href="<?php echo esc_url($wp_dp_member_link); ?>">
                                <img itemprop="image" src="<?php echo esc_url($member_image); ?>" alt="" />
                            </a>
                        </figure>
                    </div>
                <?php } ?>
                <div class="text-holder">
                    <?php if ( isset($wp_dp_member_title) && $wp_dp_member_title != '' ) { ?>
                        <a href="<?php echo esc_url($wp_dp_member_link); ?>">
                            <h5 itemprop="name"><?php echo esc_html($wp_dp_member_title); ?></h5>
                        </a>
                    <?php } ?>
                    <?php
                    if ( isset($wp_dp_member_phone_num) && $wp_dp_member_phone_num != '' ) {
                        $all_data = '';
                        $all_data = $wp_dp_author_info->wp_dp_member_member_phone_num($wp_dp_member_phone_num, '', '<strong itemprop="telephone">', '</strong>');
                        echo force_balance_tags($all_data);
                        ?>
                    <?php } ?>
                    <?php if ( isset($wp_dp_post_loc_address_member) && $wp_dp_post_loc_address_member != '' ) { ?>	
                        <ul>
                            <li itemprop="address"><?php echo esc_html($wp_dp_post_loc_address_member); ?></li>
                        </ul>
                    <?php } ?>

                    <div class="field-select-holder">
                        <?php echo do_action('wp_dp_opening_hours_element_html', $wp_dp_listing_member_id, 'listing-v2'); ?>  
                    </div>
                    <?php
                    $target_modal = 'data-toggle="modal" data-target="#sign-in"';
                    $target_class = ' wp-dp-open-signin-tab';
                    if ( is_user_logged_in() ) {
                        $target_class = '';
                        $target_modal = ' data-toggle="modal" data-target="#enquiry-modal"';
                    }
                    ?>    
                    <a href="javascript:void(0);" class="submit-btn <?php echo esc_attr($target_class); ?>" <?php echo wp_dp_cs_allow_special_char($target_modal); ?>><?php echo wp_dp_plugin_text_srt('wp_dp_prop_detail_contact_cnt_agent'); ?></a>
                </div>
            </div>
            <?php
        }

    }

    global $wp_dp_sidebar_member_info;
    $wp_dp_sidebar_member_info = new wp_dp_sidebar_member_info_element();
}