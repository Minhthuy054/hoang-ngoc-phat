<?php
/**
 * Member Listings
 *
 */
if (!class_exists('Wp_dp_Member_Profile')) {

    class Wp_dp_Member_Profile {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_ajax_wp_dp_member_accounts', array($this, 'wp_dp_member_accounts_callback'), 11, 1);
            add_action('wp_ajax_nopriv_wp_dp_member_accounts', array($this, 'wp_dp_member_accounts_callback'), 11, 1);
            add_action('wp_ajax_wp_dp_member_accounts_save', array($this, 'wp_dp_member_accounts_save'), 11, 1);
            add_action('wp_ajax_nopriv_wp_dp_member_accounts_save', array($this, 'wp_dp_member_accounts_save'), 11, 1);
            add_action('wp_ajax_member_change_address', array($this, 'member_change_address_call_back'), 11, 1);
            /*
             * Change Pasword
             */
            add_action('wp_ajax_wp_dp_member_change_password', array($this, 'member_change_password_callback'), 11, 1);
            add_action('wp_ajax_member_change_pass', array($this, 'member_change_pass_callback'));
            /*
             *  Change Location
             */
            add_action('wp_ajax_wp_dp_member_change_locations', array($this, 'wp_dp_member_change_location_callback'), 11, 1);

            // Opening Hours
            add_action('wp_ajax_wp_dp_member_opening_hours', array($this, 'wp_dp_member_opening_hours_callback'), 11, 1);
            add_action('wp_ajax_wp_dp_member_opening_hours_submission', array($this, 'wp_dp_member_opening_hours_submission_callback'), 11, 1);
            /*
             * Team Members 
             */
        }

        /*
         * Opening Hours Saving Form
         */

        public function wp_dp_member_opening_hours_submission_callback() {
            $member_id = get_current_user_id();
            $company_id = get_user_meta($member_id, 'wp_dp_company', true);
            $user_info = get_userdata($member_id);
            // Update opening_hours.
            if (isset($_POST['wp_dp_opening_hour'])) {
                update_post_meta($company_id, 'wp_dp_opening_hour', $_POST['wp_dp_opening_hour']);
            }
           
            // Update off days.
            if (isset($_POST['wp_dp_listing_off_days'])) {
                update_post_meta($company_id, 'wp_dp_calendar', $_POST['wp_dp_listing_off_days']);
            }else{
                update_post_meta($company_id, 'wp_dp_calendar', '');
            }
            $response_array = array(
                'type' => 'success',
                'msg' => wp_dp_plugin_text_srt('wp_dp_member_updated_success_mesage'),
            );
            echo json_encode($response_array);
            wp_die();
        }

        /*
         * change location
         */

        public function member_change_address_call_back() {

            $error_string = '';
            $member_id = get_current_user_id();
            $company_id = get_user_meta($member_id, 'wp_dp_company', true);
            $user_info = get_userdata($member_id);

            $wp_dp_post_loc_country_member = $_POST['wp_dp_post_loc_country_member'];
            $wp_dp_post_loc_state_member = $_POST['wp_dp_post_loc_state_member'];
            $wp_dp_post_loc_city_member = $_POST['wp_dp_post_loc_city_member'];
            $wp_dp_post_loc_town_member = $_POST['wp_dp_post_loc_town_member'];
            $wp_dp_post_loc_address = $_POST['wp_dp_post_loc_address_member'];
            $wp_dp_post_loc_latitude = $_POST['wp_dp_post_loc_latitude_member'];
            $wp_dp_post_loc_longitude = $_POST['wp_dp_post_loc_longitude_member'];
            $wp_dp_post_loc_radius = $_POST['wp_dp_loc_radius_member'];
            $wp_dp_post_loc_zoom = $_POST['wp_dp_post_loc_zoom_member'];
            $wp_dp_post_add_new_loc = $_POST['wp_dp_add_new_loc_member'];
            if ($company_id != '') {
                if ($wp_dp_post_loc_country_member != '') {
                    update_post_meta($company_id, 'wp_dp_post_loc_country_member', $wp_dp_post_loc_country_member);
                }
                if ($wp_dp_post_loc_state_member != '') {
                    update_post_meta($company_id, 'wp_dp_post_loc_state_member', $wp_dp_post_loc_state_member);
                }
                if ($wp_dp_post_loc_city_member != '') {
                    update_post_meta($company_id, 'wp_dp_post_loc_city_member', $wp_dp_post_loc_city_member);
                }
                if ($wp_dp_post_loc_town_member != '') {
                    update_post_meta($company_id, 'wp_dp_post_loc_town_member', $wp_dp_post_loc_town_member);
                }

                if ($wp_dp_post_loc_address != '') {
                    update_post_meta($company_id, 'wp_dp_post_loc_address_member', $wp_dp_post_loc_address);
                }
                if ($wp_dp_post_loc_latitude != '') {
                    update_post_meta($company_id, 'wp_dp_post_loc_latitude_member', $wp_dp_post_loc_latitude);
                }
                if ($wp_dp_post_loc_longitude != '') {
                    update_post_meta($company_id, 'wp_dp_post_loc_longitude_member', $wp_dp_post_loc_longitude);
                }
                if ($wp_dp_post_loc_zoom != '') {
                    update_post_meta($company_id, 'wp_dp_post_loc_zoom_member', $wp_dp_post_loc_zoom);
                }
                if ($wp_dp_post_loc_radius != '') {
                    update_post_meta($company_id, 'wp_dp_loc_radius_member', $wp_dp_post_loc_radius);
                }
                if ($wp_dp_post_add_new_loc != '') {
                    update_post_meta($company_id, 'wp_dp_add_new_loc_member', $wp_dp_post_add_new_loc);
                }
                update_post_meta($company_id, 'wp_dp_array_data', $_POST);
            } else {
                if ($wp_dp_post_loc_country_member != '') {
                    update_user_meta($member_id, 'wp_dp_post_loc_country_member', $wp_dp_post_loc_country_member);
                }
                if ($wp_dp_post_loc_state_member != '') {
                    update_user_meta($member_id, 'wp_dp_post_loc_state_member', $wp_dp_post_loc_state_member);
                }
                if ($wp_dp_post_loc_city_member != '') {
                    update_user_meta($member_id, 'wp_dp_post_loc_city_member', $wp_dp_post_loc_city_member);
                }
                if ($wp_dp_post_loc_town_member != '') {
                    update_user_meta($member_id, 'wp_dp_post_loc_town_member', $wp_dp_post_loc_town_member);
                }

                if ($wp_dp_post_loc_address != '') {
                    update_user_meta($member_id, 'wp_dp_post_loc_address_member', $wp_dp_post_loc_address);
                }
                if ($wp_dp_post_loc_latitude != '') {
                    update_user_meta($member_id, 'wp_dp_post_loc_latitude_member', $wp_dp_post_loc_latitude);
                }
                if ($wp_dp_post_loc_longitude != '') {
                    update_user_meta($member_id, 'wp_dp_post_loc_longitude_member', $wp_dp_post_loc_longitude);
                }
                if ($wp_dp_post_loc_zoom != '') {
                    update_user_meta($member_id, 'wp_dp_post_loc_zoom_member', $wp_dp_post_loc_zoom);
                }
                if ($wp_dp_post_loc_radius != '') {
                    update_user_meta($member_id, 'wp_dp_loc_radius_member', $wp_dp_post_loc_radius);
                }
                if ($wp_dp_post_add_new_loc != '') {
                    update_user_meta($member_id, 'wp_dp_add_new_loc_member', $wp_dp_post_add_new_loc);
                }
            }
            $response_array = array(
                'type' => 'success',
                'msg' => wp_dp_plugin_text_srt('wp_dp_member_updated_success_mesage'),
            );
            echo json_encode($response_array);
            wp_die();
        }

        public function member_change_pass_callback() {
            $error_string = '';
            $member_id = get_current_user_id();
            $user_info = get_userdata($member_id);
            $user_ID = $member_id;
            $member_current_password = $_POST['member_current_password'];
            $member_new_password = $_POST['member_new_password'];
            $member_confirm_new_password = $_POST['member_confirm_new_password'];
            $member_name = wp_dp_get_input('member_name', NULL, 'STRING');
            $member_phone_number = wp_dp_get_input('member_phone_number', NULL, 'STRING');
            $wp_dp_member_thumb_id = wp_dp_get_input('wp_dp_member_thumb_id', '', 'STRING');
            $member_thumb = isset($_FILES['wp_dp_member_thumb']) ? $_FILES['wp_dp_member_thumb'] : array();


            if (!empty($member_new_password)) {
                if (!wp_check_password($member_current_password, $user_info->user_pass, $member_id) && $member_current_password != '') {

                    $response_array = array(
                        'type' => 'error',
                        'msg' => wp_dp_plugin_text_srt('wp_dp_member_invalid_current_pass'),
                    );
                    echo json_encode($response_array);
                    wp_die();
                }
                if ($member_new_password != $member_confirm_new_password) {
                    $response_array = array(
                        'type' => 'error',
                        'msg' => wp_dp_plugin_text_srt('wp_dp_member_pass_and_confirmpass_not_mached'),
                    );
                    echo json_encode($response_array);
                    wp_die();
                }
                if (wp_check_password($member_current_password, $user_info->user_pass, $member_id)) {
                    if ($member_new_password == $member_confirm_new_password) {
                        $current_user = wp_get_current_user();
                        wp_set_password($member_confirm_new_password, $member_id);
                        wp_set_auth_cookie($member_id);
                        $template_data = array(
                            'user' => $current_user->user_login,
                            'email' => $current_user->user_email,
                            'password' => $member_confirm_new_password,
                        );

                        do_action('wp_dp_change_password_email', $template_data);
                    }
                }
            }

            if (!empty($member_thumb['name'])) {

                $types = array('image/jpeg', 'image/jpg', 'image/gif', 'image/png');

                if (in_array($member_thumb['type'], $types)) {
                    $this->update_member_thumb($member_thumb, $user_ID);
                } else {
                    $response_array = array(
                        'type' => 'error',
                        'msg' => wp_dp_plugin_text_srt('wp_dp_member_valid_file'),
                    );
                    echo json_encode($response_array);
                    wp_die();
                }
            } else {
                update_user_meta($user_ID, 'member_thumb', $wp_dp_member_thumb_id);
            }

            if (isset($member_name) && $member_name != '') {
                update_user_meta($user_ID, 'member_name', $member_name);
            }
            if (isset($member_phone_number) && $member_phone_number != '') {
                update_user_meta($user_ID, 'member_phone_number', $member_phone_number);
            }


            $response_array = array(
                'type' => 'success',
                'msg' => wp_dp_plugin_text_srt('wp_dp_member_updated_success_mesage'),
            );
            echo json_encode($response_array);
            wp_die();
        }

        public function update_member_thumb($thumb_file, $user_id) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            $current_user_id = get_current_user_id();
            $status = wp_handle_upload($thumb_file, array('test_form' => false));
            if (isset($status) && !isset($status['error'])) {
                $uploads = wp_upload_dir();
                $filename = isset($status['url']) ? $status['url'] : '';
                $filetype = wp_check_filetype(basename($filename), null);

                if ($filename != '') {
                    // Prepare an array of post data for the attachment.

                    $attachment = array(
                        'guid' => $status['url'],
                        'post_mime_type' => $filetype['type'],
                        'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );
                    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
                    require_once(ABSPATH . "wp-admin" . '/includes/media.php');
                    // Insert the attachment.
                    $attach_id = wp_insert_attachment($attachment, $status['file']);
                    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                    $attach_data = wp_generate_attachment_metadata($attach_id, $status['file']);
                    wp_update_attachment_metadata($attach_id, $attach_data);
                    $attach_id = $attach_id;

                    update_user_meta($user_id, 'member_thumb', $attach_id);
                }
            }
        }

        /*
         * Location Fields
         */

        public function wp_dp_member_change_location_callback() {
            global $wp_dp_member_branches;
            $member_id = get_current_user_id();
            $company_id = get_user_meta($member_id, 'wp_dp_company', true);
            ?>
            <div class = "row">
                <div class="response-holder-change-address"></div>
                <form id="change_address_form" method="POST">
                    <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class = "element-title has-border">
                            <h4><?php echo wp_dp_plugin_text_srt('wp_dp_member_loc_branch'); ?></h4> 
                        </div>
                    </div>
                    <?php
                    $member_id = get_current_user_id();
                    $company_id = get_user_meta($member_id, 'wp_dp_company', true);
                    $current_user = wp_get_current_user();
                    if ($company_id != '') {
                        $currrent_company = get_post($company_id);
                    } else {
                        WP_DP_FUNCTIONS()->wp_dp_frontend_location_fields('on', '', 'member', $current_user, true);
                    }
                    ?>
                    <div class="row">
                        <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button name="button" type="button" class="btn-submit" id="member_change_address"><?php echo wp_dp_plugin_text_srt('wp_dp_member_save'); ?></button>
                        </div>
                        <script type="text/javascript">
                            jQuery(document).ready(function ($) {
                                chosen_selectionbox();
                            });
                        </script>
                    </div>
                </form>
            </div>
            <?php
            wp_die();
        }

        /*
         * Opening Hours
         */

        public function wp_dp_member_opening_hours_callback() {
            ?>
            <div class = "row">
                <div class="response-holder-change-address"></div>
                <form id="member-opening-hours-form" method="POST">
                    <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class = "element-title has-border">
                            <h4><?php echo wp_dp_plugin_text_srt('wp_dp_member_opening_hours'); ?></h4>
                        </div>
                    </div>
                    <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php echo ($this->member_opening_hours()); ?>
                    </div>
                    <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php echo ($this->member_book_days_off()); ?>
                    </div>
                    <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <button name="button" type="button" class="btn-submit" id="member-opening-hours-btn"><?php echo wp_dp_plugin_text_srt('wp_dp_member_save'); ?></button>
                    </div>
                    <script type="text/javascript">
                        jQuery(document).ready(function ($) {
                            chosen_selectionbox();
                        });
                    </script>

                </form>
            </div>
            <?php
            wp_die();
        }

        /*
         * Change password Form
         */

        public function member_change_password_callback() {
            global $wpdb, $wp_dp_plugin_options, $wp_dp_form_fields_frontend, $wp_dp_html_fields_frontend, $wp_dp_html_fields, $wp_dp_form_fields;
            wp_enqueue_script('wp-dp-validation-script');
            $member_id = get_current_user_id();
            $member_name = get_user_meta($member_id, 'member_name', true);
            $member_phone_number = get_user_meta($member_id, 'member_phone_number', true);
            $wp_dp_member_thumb_id = get_user_meta($member_id, 'member_thumb', true);
            $company_id = get_user_meta($member_id, 'wp_dp_company', true);
            $user_type = get_user_meta($member_id, 'wp_dp_user_type', true);
			$change_pass_text = wp_dp_plugin_text_srt('wp_dp_member_change_pass');
            if ($user_type == 'team-member') {
                $change_pass_text = wp_dp_plugin_text_srt('wp_dp_member_my_profile');
            }
            $required_class = ( $user_type != 'team-member' ) ? 'wp-dp-dev-req-field' : '';
            ?>
            <div class = "row">
                <div class="response-holder-change-pass"></div>
                <form id="change_password_form" method="POST">
                    <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class = "element-title has-border">
                            <h4><?php echo esc_html($change_pass_text); ?></h4>
                        </div>
                    </div>
                    <?php if ($user_type == 'team-member') { ?>
                        <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class = "field-holder">
                                <label><?php echo wp_dp_plugin_text_srt('wp_dp_member_name'); ?></label>
                                <?php
                                $wp_dp_opt_array = array(
                                    'desc' => '',
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => $member_name,
                                        'id' => 'member_name',
                                        'classes' => '',
                                        'cust_name' => 'member_name',
                                        'force_std' => true
                                    ),
                                );

                                $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                ?>
                            </div>
                        </div>
                        <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class = "field-holder">
                                <label><?php echo wp_dp_plugin_text_srt('wp_dp_member_phone_number'); ?></label>
                                <?php
                                $wp_dp_opt_array = array(
                                    'desc' => '',
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => $member_phone_number,
                                        'id' => 'member_phone_number',
                                        'classes' => '',
                                        'cust_name' => 'member_phone_number',
                                        'force_std' => true
                                    ),
                                );

                                $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                ?>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="field-holder profile-image-field">
                                <label><?php echo wp_dp_plugin_text_srt('wp_dp_member_profile_image'); ?></label>
                                <?php
                                $wp_dp_opt_array = array(
                                    'name' => wp_dp_plugin_text_srt('wp_dp_member_profile_image'),
                                    'desc' => '',
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => '',
                                        'id' => 'member_thumb_' . $member_id,
                                        'extra_atr' => 'data-id="' . $member_id . '" class="wp-dp-member-thumb" style="display:none;"',
                                        'cust_name' => 'wp_dp_member_thumb',
                                        'cust_type' => 'file',
                                    ),
                                );
                                $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                                $wp_dp_opt_array = array(
                                    'name' => '',
                                    'desc' => '',
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => $wp_dp_member_thumb_id,
                                        'id' => 'member_thumb_id_' . $member_id,
                                        'cust_name' => 'wp_dp_member_thumb_id',
                                        'cust_type' => 'hidden',
                                    ),
                                );
                                $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                ?>
                                <div class="upload-file"><button for="file-1" class="member-thumbnail-upload" data-id="<?php echo esc_attr($member_id); ?>" type="button"><span><?php echo wp_dp_plugin_text_srt('wp_dp_member_upload'); ?></span></button></div>
                                <div class="member-thumbnail-<?php echo esc_attr($member_id); ?> member-profile-image">
                                    <?php
                                    if (isset($wp_dp_member_thumb_id) && $wp_dp_member_thumb_id != '') {
                                        echo wp_get_attachment_image($wp_dp_member_thumb_id, 'thumbnail');
                                        ?>
                                        <div class="remove-member-thumb" data-id="<?php echo esc_attr($member_id); ?>"><i class="icon-close"></i></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                    <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class = "field-holder">
                            <label> <?php echo wp_dp_plugin_text_srt('wp_dp_member_current_password'); ?></label>
                            <?php
                            $wp_dp_opt_array = array(
                                'desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => '',
                                    'id' => 'member_current_password',
                                    'cust_type' => 'password',
                                    'classes' => $required_class,
                                    'cust_name' => 'member_current_password',
                                    'force_std' => true
                                ),
                            );

                            $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                            ?>
                        </div>
                    </div>
                    <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class = "field-holder">
                            <label> <?php echo wp_dp_plugin_text_srt('wp_dp_member_new_password'); ?></label>
                            <?php
                            $wp_dp_opt_array = array(
                                'desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => '',
                                    'cust_type' => 'password',
                                    'classes' => $required_class,
                                    'id' => 'member_new_password',
                                    'cust_name' => 'member_new_password',
                                    'force_std' => true
                                ),
                            );

                            $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                            ?>
                        </div>
                    </div>
                    <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class = "field-holder">
                            <label> <?php echo wp_dp_plugin_text_srt('wp_dp_member_confirm_new_password'); ?> </label>
                            <?php
                            $wp_dp_opt_array = array(
                                'desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => '',
                                    'cust_type' => 'password',
                                    'id' => 'member_confirm_new_password',
                                    'classes' => $required_class,
                                    'cust_name' => 'member_confirm_new_password',
                                    'force_std' => true
                                ),
                            );

                            $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                            ?>
                        </div>
                    </div>
                    <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class = "field-holder">
                            <button name="button" type="button" class="btn-submit" id="member_change_password"><?php echo wp_dp_plugin_text_srt('wp_dp_member_save'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
            <?php
            wp_die();
        }

        /**
         * Member Listings
         * @ filter the listings based on member id
         */
        public function wp_dp_member_accounts_save() {
            $error_string = '';
            $member_id = get_current_user_id();
            $user_info = get_userdata($member_id);
            $company_id = get_user_meta($member_id, 'wp_dp_company', true);
            $display_name = $_POST['member_display_name'];
            $company_slug = $_POST['member_company_slug'];
            $member_email = $_POST['member_email'];
            $member_current_password = $_POST['member_current_password'];
            $member_new_password = $_POST['member_new_password'];
            $member_confirm_new_password = $_POST['member_confirm_new_password'];
            $saved_member_profile_image = $_POST['saved_member_profile_image'];

            $wp_dp_user_phone_number = $_POST['wp_dp_user_phone_number'];
            $wp_dp_biography = $_POST['wp_dp_biography'];
            $wp_dp_user_website = $_POST['wp_dp_user_website'];
            $wp_dp_user_facebook = $_POST['wp_dp_user_facebook'];
           // $wp_dp_user_google_plus = $_POST['wp_dp_user_google_plus'];
            $wp_dp_user_twitter = $_POST['wp_dp_user_twitter'];
            $wp_dp_user_linkedIn = $_POST['wp_dp_user_linkedIn'];
            $wp_dp_email_address = $member_email;
            $post = get_post($company_id);
            $company_slug_old = $post->post_name;
            $wp_dp_post_loc_country_member = $_POST['wp_dp_post_loc_country_member'];
            $wp_dp_post_loc_state_member = $_POST['wp_dp_post_loc_state_member'];
            $wp_dp_post_loc_city_member = $_POST['wp_dp_post_loc_city_member'];
            $wp_dp_post_loc_town_member = $_POST['wp_dp_post_loc_town_member'];
            $wp_dp_post_loc_address = $_POST['wp_dp_post_loc_address_member'];
            $wp_dp_post_loc_latitude = $_POST['wp_dp_post_loc_latitude_member'];
            $wp_dp_post_loc_longitude = $_POST['wp_dp_post_loc_longitude_member'];
            $wp_dp_post_loc_radius = $_POST['wp_dp_loc_radius_member'];
            $wp_dp_post_loc_zoom = $_POST['wp_dp_post_loc_zoom_member'];
            $wp_dp_post_add_new_loc = $_POST['wp_dp_add_new_loc_member'];


			
            if ($company_slug == '') {
                $response_array = array(
                    'type' => 'error',
                    'msg' => wp_dp_plugin_text_srt('wp_dp_member_company_name_empty_error'),
                );
                echo json_encode($response_array);
                wp_die();
            }
            if ($company_slug != $company_slug_old) {
                $comp_exists = get_page_by_path($company_slug, '', 'members');
		if (($comp_exists != '')) {
                    $response_array = array(
                        'type' => 'error',
                        'msg' => wp_dp_plugin_text_srt('wp_dp_member_company_name_exist_error'),
                    );
                    echo json_encode($response_array);
                    wp_die();
                }
            }

            if ($display_name == '') {
                $response_array = array(
                    'type' => 'error',
                    'msg' => wp_dp_plugin_text_srt('wp_dp_member_display_name_empty_error'),
                );
                echo json_encode($response_array);
                wp_die();
            }

            if ($wp_dp_biography == '') {
                $response_array = array(
                    'type' => 'error',
                    'msg' => wp_dp_plugin_text_srt('wp_dp_member_biography_empty_error'),
                );
                echo json_encode($response_array);
                wp_die();
            }

            if ($wp_dp_user_phone_number == '') {
                $response_array = array(
                    'type' => 'error',
                    'msg' => wp_dp_plugin_text_srt('wp_dp_member_phone_empty_error'),
                );
                echo json_encode($response_array);
                wp_die();
            }


            if ($member_email == '') {
                $response_array = array(
                    'type' => 'error',
                    'msg' => wp_dp_plugin_text_srt('wp_dp_member_email_empty_error'),
                );
                echo json_encode($response_array);
                wp_die();
            }

            if (!is_email($member_email)) {
                $response_array = array(
                    'type' => 'error',
                    'msg' => wp_dp_plugin_text_srt('wp_dp_member_email_valid_error'),
                );
                echo json_encode($response_array);
                wp_die();
            }
            $exists = email_exists($member_email); //  return userid
            if ( $exists != $member_id && $exists != '' ) {
                $response_array = array(
                    'type' => 'error',
                    'msg' => wp_dp_plugin_text_srt('wp_dp_member_email_exists'),
                );
                echo json_encode($response_array);
                wp_die();
            }

            if (wp_check_password($member_current_password, $user_info->user_pass, $member_id)) {
                if ($member_new_password == $member_confirm_new_password) {
                    wp_set_password($member_confirm_new_password, $member_id);
                }
            }
			
            $member_profile_image = isset($_FILES['wp_dp_member_profile_image']) ? $_FILES['wp_dp_member_profile_image'] : '';
            if (!empty($member_profile_image) && !is_numeric($member_profile_image)) {
                require_once ABSPATH . 'wp-admin/includes/image.php';
                require_once ABSPATH . 'wp-admin/includes/file.php';
                require_once ABSPATH . 'wp-admin/includes/media.php';

                $file = wp_handle_upload($member_profile_image, array('test_form' => false));
                if (empty($file['error'])) {
                    $filetype = wp_check_filetype(basename($file['url']), null);
                    $wp_upload_dir = wp_upload_dir();
                    $attachment = array(
                        'guid' => $file['url'],
                        'post_mime_type' => $filetype['type'],
                        'post_title' => preg_replace('/\.[^.]+$/', '', $member_profile_image['name']),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );

                    $profile_image_id = wp_insert_attachment($attachment, $file['file']);
                    if ($attach_data = wp_generate_attachment_metadata($profile_image_id, $file['file'])) {
                        wp_update_attachment_metadata($profile_image_id, $attach_data);
                    }
                    $member_profile_image_id = $profile_image_id;
                } else {
                    $member_profile_image_id = '';
                }
            } else if ($saved_member_profile_image != '') {
                $member_profile_image_id = $saved_member_profile_image;
            }

            if ($company_id != '') {
                if ($display_name != '') {
                    $my_post = array(
                        'ID' => $company_id,
                        'post_title' => $display_name,
                    );
                    wp_update_post($my_post);
                }

                if ($company_slug != '') {
                    $my_post = array(
                        'ID' => $company_id,
                        'post_name' => $company_slug,
			//'post_name' => sanitize_title($company_slug),
                    );
                    wp_update_post($my_post);
                } elseif ($display_name != '') {
                    $my_post = array(
                        'ID' => $company_id,
                        'post_name' => sanitize_title($display_name),
                    );
                    wp_update_post($my_post);
                }
                update_post_meta($company_id, 'wp_dp_profile_image', $member_profile_image_id);

                if ($wp_dp_user_phone_number != '') {
                    update_post_meta($company_id, 'wp_dp_phone_number', $wp_dp_user_phone_number);
                }
                if ($wp_dp_biography != '') {
                    update_post_meta($company_id, 'wp_dp_biography', $wp_dp_biography);
                }

                if ($wp_dp_user_website != '') {
                    update_post_meta($company_id, 'wp_dp_website', $wp_dp_user_website);
                }

                if ($wp_dp_user_facebook != '') {
                    update_post_meta($company_id, 'wp_dp_facebook', $wp_dp_user_facebook);
                }

                //if ($wp_dp_user_google_plus != '') {
                   // update_post_meta($company_id, 'wp_dp_google_plus', $wp_dp_user_google_plus);
               // }

                if ($wp_dp_user_twitter != '') {
                    update_post_meta($company_id, 'wp_dp_twitter', $wp_dp_user_twitter);
                }

                if ($wp_dp_user_linkedIn != '') {
                    update_post_meta($company_id, 'wp_dp_linkedIn', $wp_dp_user_linkedIn);
                }

                if ($wp_dp_email_address != '') {
                    update_post_meta($company_id, 'wp_dp_email_address', $wp_dp_email_address);
                }
                if ($wp_dp_post_loc_country_member != '') {
                    update_post_meta($company_id, 'wp_dp_post_loc_country_member', $wp_dp_post_loc_country_member);
                }
                if ($wp_dp_post_loc_state_member != '') {
                    update_post_meta($company_id, 'wp_dp_post_loc_state_member', $wp_dp_post_loc_state_member);
                }
                if ($wp_dp_post_loc_city_member != '') {
                    update_post_meta($company_id, 'wp_dp_post_loc_city_member', $wp_dp_post_loc_city_member);
                }
                if ($wp_dp_post_loc_town_member != '') {
                    update_post_meta($company_id, 'wp_dp_post_loc_town_member', $wp_dp_post_loc_town_member);
                }


                if ($wp_dp_post_loc_address != '') {
                    update_post_meta($company_id, 'wp_dp_post_loc_address_member', $wp_dp_post_loc_address);
                }
                if ($wp_dp_post_loc_latitude != '') {
                    update_post_meta($company_id, 'wp_dp_post_loc_latitude_member', $wp_dp_post_loc_latitude);
                }
                if ($wp_dp_post_loc_longitude != '') {
                    update_post_meta($company_id, 'wp_dp_post_loc_longitude_member', $wp_dp_post_loc_longitude);
                }
                if ($wp_dp_post_loc_zoom != '') {
                    update_post_meta($company_id, 'wp_dp_post_loc_zoom_member', $wp_dp_post_loc_zoom);
                }
                if ($wp_dp_post_loc_radius != '') {
                    update_post_meta($company_id, 'wp_dp_loc_radius_member', $wp_dp_post_loc_radius);
                }
                if ($wp_dp_post_add_new_loc != '') {
                    update_post_meta($company_id, 'wp_dp_add_new_loc_member', $wp_dp_post_add_new_loc);
                }
            }

            $response_array = array(
                'type' => 'success',
                'msg' => wp_dp_plugin_text_srt('wp_dp_member_updated_success_mesage'),
            );
            echo json_encode($response_array);
            wp_die();
        }

        public function wp_dp_member_accounts_callback($member_id = '') {
            global $wpdb, $wp_dp_plugin_options, $wp_dp_form_fields_frontend, $wp_dp_html_fields_frontend, $wp_dp_html_fields;
            wp_enqueue_script('wp-dp-validation-script');
            $member_user_type = '';
            $rand_id = rand(5, 99999);
            if (!isset($member_id) || $member_id == '') {
                $member_id = get_current_user_id();
                $company_id = get_user_meta($member_id, 'wp_dp_company', true);
                $member_complete_data = get_user_meta($member_id);
                $wp_dp_company_email = wp_get_current_user()->user_email;
                $display_name = wp_get_current_user()->display_name;
                if ($company_id != '') {
                    $display_name = get_the_title($company_id);
                }
                $wp_dp_company_email = get_post_meta($company_id, 'wp_dp_email_address', true);
                $wp_dp_biography = '';
                $wp_dp_profile_images_ids = isset($wp_dp_plugin_options['wp_dp_profile_images_ids']) ? $wp_dp_plugin_options['wp_dp_profile_images_ids'] : '';
                $wp_dp_user_phone_number = '';
                $wp_dp_user_website = '';
                $wp_dp_user_facebook = '';
                //$wp_dp_user_google_plus = '';
                $wp_dp_user_twitter = '';
                $wp_dp_user_linkedIn = '';
                $wp_dp_profile_image = $this->member_get_profile_image($member_id, '1');


                if ($company_id != '') {
                    $wp_dp_profile_images_ids = isset($wp_dp_plugin_options['wp_dp_profile_images_ids']) ? $wp_dp_plugin_options['wp_dp_profile_images_ids'] : '';
                    $wp_dp_user_phone_number = get_post_meta($company_id, 'wp_dp_phone_number', true);
                    $wp_dp_biography = get_post_meta($company_id, 'wp_dp_biography', true);
                    
                    $wp_dp_user_facebook = get_post_meta($company_id, 'wp_dp_facebook', true);
                    //$wp_dp_user_google_plus = get_post_meta($company_id, 'wp_dp_google_plus', true);
                    $wp_dp_user_twitter = get_post_meta($company_id, 'wp_dp_twitter', true);
                    $wp_dp_user_linkedIn = get_post_meta($company_id, 'wp_dp_linkedIn', true);


                    $post = get_post($company_id);
                    $company_slug = $post->post_name;
                }
            }

            $search_location = '1';
            ?>
            <ul class="breadcrumbs">
                <li class="user_dashboard_ajax" id="wp_dp_member_suggested" data-queryvar="dashboard=suggested"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_Dashboard'); ?></a></li>
                <li class="active"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_account_stng'); ?></li></ul>
            <div class="user-dashboard-background">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="user-profile user-profile-holder">
                                    <div class="response-holder"></div>
                                    <div class="ewrror-holder"></div>
                                    <div class ="element-title has-border">
                                        <h4><?php echo wp_dp_plugin_text_srt('wp_dp_user_meta_my_profile'); ?></h4>
                                    </div>
                                    <?PHP if (true === Wp_dp_Member_Permissions::check_permissions('company_profile')) { ?>
                                        <div class="row">
                                            <form id="member_profile" method="POST">
                                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <?php
                                                        if (true === Wp_dp_Member_Permissions::check_permissions('company_profile')) {
                                                            ?> 
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class = "field-holder">
                                                                    <label><?php echo wp_dp_plugin_text_srt('wp_dp_member_account_full_name'); ?>*</label>
                                                                    <?php
                                                                    $wp_dp_opt_array = array(
                                                                        'desc' => '',
                                                                        'echo' => true,
                                                                        'field_params' => array(
                                                                            'std' => $display_name,
                                                                            'id' => 'member_display_name',
                                                                            'cust_name' => 'member_display_name',
                                                                            'classes' => 'wp-dp-dev-req-field',
                                                                        ),
                                                                    );

                                                                    $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class = "field-holder">
                                                                    <label><?php echo wp_dp_plugin_text_srt('wp_dp_member_company_slug'); ?></label>
                                                                    <div class="profile-slug-field">
                                                                        <?php
                                                                        global $wp_dp_post_type_members;
                                                                        $profile_baseurl = $wp_dp_post_type_members->member_profile_baseurl;
                                                                        ?><span class="profile-fixed-slug-field"><?php echo esc_url($profile_baseurl); ?></span><?php
                                                                        $wp_dp_opt_array = array(
                                                                            'desc' => '',
                                                                            'echo' => true,
                                                                            'field_params' => array(
                                                                                'std' => urldecode($company_slug),
                                                                                'id' => 'member_company_slug',
                                                                                'cust_name' => 'member_company_slug',
                                                                                'extra_atr' => ' ',
                                                                            ),
                                                                        );
                                                                        $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                                        ?></div>
                                                                </div>
                                                            </div>
                                                            <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class = "field-holder">
                                                                    <label><?php echo wp_dp_plugin_text_srt('wp_dp_member_email_address'); ?> *</label>
                                                                    <?php
                                                                    $wp_dp_opt_array = array(
                                                                        'desc' => '',
                                                                        'echo' => true,
                                                                        'field_params' => array(
                                                                            'std' => $wp_dp_company_email,
                                                                            'id' => 'member_email',
                                                                            'classes' => 'wp-dp-dev-req-field wp-dp-email-field',
                                                                            'cust_name' => 'member_email',
                                                                        ),
                                                                    );

                                                                    $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class = "field-holder">
                                                                    <label><?php echo wp_dp_plugin_text_srt('wp_dp_member_biography'); ?></label>
                                                                    <?php
                                                                    $wp_dp_opt_array = array(
                                                                        'desc' => '',
                                                                        'echo' => true,
                                                                        'field_params' => array(
                                                                            'std' => $wp_dp_biography,
                                                                            'id' => 'biography',
                                                                            'extra_atr' => ' class="wp-dp-dev-req-field"',
                                                                            'cust_name' => 'wp_dp_biography',
                                                                        ),
                                                                    );

                                                                    $wp_dp_html_fields_frontend->wp_dp_form_textarea_render($wp_dp_opt_array);
                                                                    ?>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                                <div class = "field-holder">
                                                                    <label><?php echo wp_dp_plugin_text_srt('wp_dp_member_phone_number'); ?></label>
                                                                    <?php
                                                                    $wp_dp_opt_array = array(
                                                                        'desc' => '',
                                                                        'echo' => true,
                                                                        'field_params' => array(
                                                                            'std' => $wp_dp_user_phone_number,
                                                                            'classes' => 'wp-dp-dev-req-field wp-dp-number-field',
                                                                            'id' => 'wp_dp_user_phone_number',
                                                                            'cust_name' => 'wp_dp_user_phone_number',
                                                                        ),
                                                                    );

                                                                    $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                                    ?>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                                <div class="field-holder">
                                                                    <label><?php echo wp_dp_plugin_text_srt('wp_dp_user_meta_facebook'); ?></label>
                                                                    <?php
                                                                    $wp_dp_opt_array = array(
                                                                        'desc' => '',
                                                                        'echo' => true,
                                                                        'field_params' => array(
                                                                            'std' => $wp_dp_user_facebook,
                                                                            'id' => 'user_facebook',
                                                                            'cust_name' => 'wp_dp_user_facebook',
                                                                        ),
                                                                    );

                                                                    $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                                    ?>
                                                                </div>
                                                            </div>

                                                            <!--<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                                <div class = "field-holder">
                                                                    <label><?php /*echo wp_dp_plugin_text_srt('wp_dp_user_meta_google_plus'); */?></label>
                                                                    <?php
/*                                                                    $wp_dp_opt_array = array(
                                                                        'desc' => '',
                                                                        'echo' => true,
                                                                        'field_params' => array(
                                                                            'std' => $wp_dp_user_google_plus,
                                                                            'id' => 'user_google_plus',
                                                                            'cust_name' => 'wp_dp_user_google_plus',
                                                                        ),
                                                                    );

                                                                    $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                                    */?>
                                                                </div>
                                                            </div>-->

                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                                <div class="field-holder">
                                                                    <label><?php echo wp_dp_plugin_text_srt('wp_dp_user_meta_twitter'); ?></label>
                                                                    <?php
                                                                    $wp_dp_opt_array = array(
                                                                        'desc' => '',
                                                                        'echo' => true,
                                                                        'field_params' => array(
                                                                            'std' => $wp_dp_user_twitter,
                                                                            'id' => 'user_twitter',
                                                                            'cust_name' => 'wp_dp_user_twitter',
                                                                        ),
                                                                    );

                                                                    $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                                    ?>
                                                                </div>
                                                            </div>

                                                            <div class = "col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                                <div class = "field-holder">
                                                                    <label><?php echo wp_dp_plugin_text_srt('wp_dp_user_meta_linkedIn'); ?></label>
                                                                    <?php
                                                                    $wp_dp_opt_array = array(
                                                                        'desc' => '',
                                                                        'echo' => true,
                                                                        'field_params' => array(
                                                                            'std' => $wp_dp_user_linkedIn,
                                                                            'id' => 'user_linkedIn',
                                                                            'cust_name' => 'wp_dp_user_linkedIn',
                                                                        ),
                                                                    );
																	$wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                                    ?>
                                                                </div>
                                                            </div>


                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                                    <?php
                                                    $def_profile_image = isset($wp_dp_plugin_options['wp_dp_default_placeholder_image']) ? $wp_dp_plugin_options['wp_dp_default_placeholder_image'] : array();
                                                    $profile_img_id = isset($wp_dp_profile_image['img']) ? $wp_dp_profile_image['img'] : '';
                                                    if (is_numeric($profile_img_id)) {
                                                        $profile_img_id = wp_get_attachment_url($profile_img_id);
                                                    }
                                                    if (is_numeric($def_profile_image)) {
                                                        $def_profile_image = wp_get_attachment_url($def_profile_image);
                                                    }
                                                    ?>
                                                    <div class="user-profile-images">

                                                        <div class="current-img">
                                                            <div class="row mt">
                                                                <div id="cropContainerModal" data-def-img="<?php echo esc_url($def_profile_image) ?>" data-img-type="<?php echo isset($wp_dp_profile_image['type']) && $wp_dp_profile_image['type'] == '1' ? 'default' : 'selective' ?>">
                                                                    <figure>
                                                                        <a>
                                                                            <img src="<?php echo esc_url($profile_img_id); ?>">
                                                                        </a>
                                                                    </figure>
                                                                </div>
                                                                <?php
                                                                $hidden_wp_dp_profile_image = isset($wp_dp_profile_image['img']) ? $wp_dp_profile_image['img'] : array();
                                                                if (isset($wp_dp_profile_image['type']) && $wp_dp_profile_image['type'] == '1') {
                                                                    $hidden_wp_dp_profile_image = '';
                                                                }
                                                                $wp_dp_opt_array = array(
                                                                    'desc' => '',
                                                                    'echo' => true,
                                                                    'field_params' => array(
                                                                        'cust_type' => 'file',
                                                                        'std' => $hidden_wp_dp_profile_image,
                                                                        'id' => 'member_profile_image',
                                                                        'cust_name' => 'member_profile_image',
                                                                        'extra_atr' => 'style="visibility: hidden;"',
                                                                    ),
                                                                );
                                                                $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                                                                $wp_dp_opt_array = array(
                                                                    'desc' => '',
                                                                    'echo' => true,
                                                                    'field_params' => array(
                                                                        'cust_type' => 'hidden',
                                                                        'std' => $hidden_wp_dp_profile_image,
                                                                        'id' => 'saved_member_profile_image',
                                                                        'cust_name' => 'saved_member_profile_image',
                                                                    ),
                                                                );
                                                                $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                                ?>
                                                            </div>
                                                            <span><?php echo wp_dp_plugin_text_srt('wp_dp_member_upload_profile_picture'); ?></span>
                                                        </div>
                                                        <div class="upload-file">
                                                            <button for="file-1" class="upload-img" type="button"><span><?php echo wp_dp_plugin_text_srt('wp_dp_member_upload_profile_picture_button'); ?></span></button>
                                                        </div>
                                                        <ul class="uploaded-img">
                                                            <?php
                                                            foreach ($wp_dp_profile_images_ids as $image_id) {
                                                                if ($image_id != '') {
                                                                    ?>
                                                                    <li>
                                                                        <figure>
                                                                            <img data-attachment_id="<?php echo absint($image_id); ?>" src="<?php echo wp_get_attachment_url($image_id); ?>" >
                                                                        </figure> 
                                                                    </li>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class = "field-holder">
                                                        <?php
                                                        $wp_dp_opt_array = array(
                                                            'desc' => '',
                                                            'echo' => true,
                                                            'field_params' => array(
                                                                'cust_type' => 'hidden',
                                                                'std' => 'wp_dp_member_accounts_save',
                                                                'id' => 'action',
                                                                'cust_name' => 'action',
                                                            ),
                                                        );
                                                        $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                        ?>

                                                        <button name="button" type="button" class="btn-submit" id="profile_form"><?php echo wp_dp_plugin_text_srt('wp_dp_member_save'); ?></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <?php
                                    } // END company_profile CHECK

                                    $uploads = wp_upload_dir();
                                    ?>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="user-password-holder">
                                    <?php
                                    wp_enqueue_script('wp-dp-validation-script');
                                    $member_id = get_current_user_id();
                                    $member_name = get_user_meta($member_id, 'member_name', true);
                                    $member_phone_number = get_user_meta($member_id, 'member_phone_number', true);
                                    $wp_dp_member_thumb_id = get_user_meta($member_id, 'member_thumb', true);
                                    $company_id = get_user_meta($member_id, 'wp_dp_company', true);
                                    $user_type = get_user_meta($member_id, 'wp_dp_user_type', true);
									$change_pass_text = wp_dp_plugin_text_srt('wp_dp_member_change_pass');
                                    if ($user_type == 'team-member') {
                                        $change_pass_text = wp_dp_plugin_text_srt('wp_dp_member_my_profile');
                                    }
                                    $required_class = ( $user_type != 'team-member' ) ? 'wp-dp-dev-req-field' : '';
                                    ?>
                                    <div class="row">
                                        <div class="response-holder-change-pass"></div>
                                        <form id="change_password_form" method="POST">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class = "element-title has-border">
                                                    <h4><?php echo esc_html($change_pass_text); ?></h4>
                                                </div>
                                            </div>
                                            <?php if ($user_type == 'team-member') { ?>
                                                <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class = "field-holder">
                                                        <label><?php echo wp_dp_plugin_text_srt('wp_dp_member_name'); ?></label>
                                                        <?php
                                                        $wp_dp_opt_array = array(
                                                            'desc' => '',
                                                            'echo' => true,
                                                            'field_params' => array(
                                                                'std' => $member_name,
                                                                'id' => 'member_name',
                                                                'classes' => '',
                                                                'cust_name' => 'member_name',
                                                                'force_std' => true
                                                            ),
                                                        );

                                                        $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class = "field-holder">
                                                        <label><?php echo wp_dp_plugin_text_srt('wp_dp_member_phone_number'); ?></label>
                                                        <?php
                                                        $wp_dp_opt_array = array(
                                                            'desc' => '',
                                                            'echo' => true,
                                                            'field_params' => array(
                                                                'std' => $member_phone_number,
                                                                'id' => 'member_phone_number',
                                                                'classes' => '',
                                                                'cust_name' => 'member_phone_number',
                                                                'force_std' => true
                                                            ),
                                                        );

                                                        $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                        ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="field-holder profile-image-field">
                                                        <label><?php echo wp_dp_plugin_text_srt('wp_dp_member_profile_image'); ?></label>
                                                        <?php
                                                        $wp_dp_opt_array = array(
                                                            'name' => wp_dp_plugin_text_srt('wp_dp_member_profile_image'),
                                                            'desc' => '',
                                                            'echo' => true,
                                                            'field_params' => array(
                                                                'std' => '',
                                                                'id' => 'member_thumb_' . $member_id,
                                                                'extra_atr' => 'data-id="' . $member_id . '" class="wp-dp-member-thumb" style="display:none;"',
                                                                'cust_name' => 'wp_dp_member_thumb',
                                                                'cust_type' => 'file',
                                                            ),
                                                        );
                                                        $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                                                        $wp_dp_opt_array = array(
                                                            'name' => '',
                                                            'desc' => '',
                                                            'echo' => true,
                                                            'field_params' => array(
                                                                'std' => $wp_dp_member_thumb_id,
                                                                'id' => 'member_thumb_id_' . $member_id,
                                                                'cust_name' => 'wp_dp_member_thumb_id',
                                                                'cust_type' => 'hidden',
                                                            ),
                                                        );
                                                        $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                        ?>
                                                        <div class="upload-file"><button for="file-1" class="member-thumbnail-upload" data-id="<?php echo esc_attr($member_id); ?>" type="button"><span><?php echo wp_dp_plugin_text_srt('wp_dp_member_upload'); ?></span></button></div>
                                                        <div class="member-thumbnail-<?php echo esc_attr($member_id); ?> member-profile-image">
                                                            <?php
                                                            if (isset($wp_dp_member_thumb_id) && $wp_dp_member_thumb_id != '') {
                                                                echo wp_get_attachment_image($wp_dp_member_thumb_id, 'thumbnail');
                                                                ?>
                                                                <div class="remove-member-thumb" data-id="<?php echo esc_attr($member_id); ?>"><i class="icon-close"></i></div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php } ?>

                                            <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class = "field-holder">
                                                    <label> <?php echo wp_dp_plugin_text_srt('wp_dp_member_current_password'); ?></label>
                                                    <?php
                                                    $wp_dp_opt_array = array(
                                                        'desc' => '',
                                                        'echo' => true,
                                                        'field_params' => array(
                                                            'std' => '',
                                                            'id' => 'member_current_password',
                                                            'cust_type' => 'password',
                                                            'classes' => $required_class,
                                                            'cust_name' => 'member_current_password',
                                                            'force_std' => true
                                                        ),
                                                    );

                                                    $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                    ?>
                                                </div>
                                            </div>
                                            <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class = "field-holder">
                                                    <label> <?php echo wp_dp_plugin_text_srt('wp_dp_member_new_password'); ?></label>
                                                    <?php
                                                    $wp_dp_opt_array = array(
                                                        'desc' => '',
                                                        'echo' => true,
                                                        'field_params' => array(
                                                            'std' => '',
                                                            'cust_type' => 'password',
                                                            'classes' => $required_class,
                                                            'id' => 'member_new_password',
                                                            'cust_name' => 'member_new_password',
                                                            'force_std' => true
                                                        ),
                                                    );

                                                    $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                    ?>
                                                </div>
                                            </div>
                                            <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class = "field-holder">
                                                    <label> <?php echo wp_dp_plugin_text_srt('wp_dp_member_confirm_new_password'); ?> </label>
                                                    <?php
                                                    $wp_dp_opt_array = array(
                                                        'desc' => '',
                                                        'echo' => true,
                                                        'field_params' => array(
                                                            'std' => '',
                                                            'cust_type' => 'password',
                                                            'id' => 'member_confirm_new_password',
                                                            'classes' => $required_class,
                                                            'cust_name' => 'member_confirm_new_password',
                                                            'force_std' => true
                                                        ),
                                                    );

                                                    $wp_dp_html_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="field-holder">
                                                    <button name="button" type="button" class="btn-submit" id="member_change_password"><?php echo wp_dp_plugin_text_srt('wp_dp_member_save'); ?></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="user-location-holder">
                            <div class="row">
                                <?php
                                $member_id = get_current_user_id();
                                $company_id = get_user_meta($member_id, 'wp_dp_company', true);
                                $current_user = wp_get_current_user();
                                ?>
                                <div class="response-holder-change-address"></div>
                                <form id="change_address_form" method="POST">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="element-title has-border">
                                            <h4><?php echo wp_dp_plugin_text_srt('wp_dp_member_address_branch'); ?></h4>
                                        </div>
                                    </div>
                                    <?php
                                    if ($company_id != '') {
                                        $currrent_company = get_post($company_id);
                                        WP_DP_FUNCTIONS()->wp_dp_frontend_location_fields('on', $company_id, 'member', '', true);
                                    } else {
                                        WP_DP_FUNCTIONS()->wp_dp_frontend_location_fields('on', '', 'member', $current_user, true);
                                    }
                                    ?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button name="button" type="button" class="btn-submit" id="member_change_address"><?php echo wp_dp_plugin_text_srt('wp_dp_member_save'); ?></button>
                                    </div>
                                    <script type="text/javascript">
                                        jQuery(document).ready(function ($) {
                                            chosen_selectionbox();
                                        });
                                    </script>
                                </form>
                            </div>
                        </div>
                        <div class="user-opening-hours-holder">
                            <div class="row">
                                <div class="response-holder-change-address"></div>
                                <form id="member-opening-hours-form" method="post">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="element-title has-border">
                                            <h4><?php echo wp_dp_plugin_text_srt('wp_dp_member_opening_hours'); ?></h4>
                                            <?php $book_days_off_html = $this->member_book_days_off('seprate_html'); 
                                            echo force_balance_tags($book_days_off_html['cale_html']);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?php echo ($this->member_opening_hours());
                                        echo force_balance_tags($book_days_off_html['book_off_days_list_html']);
                                        ?>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button name="button" type="button" class="btn-submit" id="member-opening-hours-btn"><?php echo wp_dp_plugin_text_srt('wp_dp_member_save'); ?></button>
                                    </div>
                                    <script type="text/javascript">
                                        jQuery(document).ready(function ($) {
                                            chosen_selectionbox();
                                        });
                                    </script>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            wp_die();
        }

        public function member_get_profile_image($member_id, $ret_array = '') {
            global $wp_dp_plugin_options;
            $user_company = get_user_meta($member_id, 'wp_dp_company', true);
            $wp_dp_profile_image = '';
            $blank_img = '0';
            if ($user_company != '') {
                $wp_dp_profile_image = get_post_meta($user_company, 'wp_dp_profile_image', true);
                if ($wp_dp_profile_image != '') {
                    $wp_dp_profile_image = wp_get_attachment_url($wp_dp_profile_image);
                }
            }

            if ($wp_dp_profile_image == '') {
                $blank_img = '1';
                $wp_dp_profile_image = isset($wp_dp_plugin_options['wp_dp_default_placeholder_image']) ? $wp_dp_plugin_options['wp_dp_default_placeholder_image'] : '';
                $wp_dp_profile_image = wp_get_attachment_url($wp_dp_profile_image);
            }
            if ($ret_array == '1') {
                $img_array = array(
                    'img' => $wp_dp_profile_image,
                    'type' => $blank_img,
                );
                return $img_array;
            }
            return $wp_dp_profile_image;
        }

        /*
         * Openings Hours Start
         */

        function member_opening_hours() {
            global $member_add_counter, $wp_dp_html_fields;
            $member_add_counter = rand(10000000, 99999999);
            $user_id = get_current_user_id();
            $member_id = get_user_meta($user_id, 'wp_dp_company', true);
            $html = '';
            $time_list = $this->member_time_list();
            $week_days = $this->member_week_days();
            $time_from_html = '';
            $time_to_html = '';
            $post_id = $member_id;
            $get_opening_hours = get_post_meta($post_id, 'wp_dp_opening_hour', true);
            if ($get_opening_hours == '') {
                if (is_array($time_list) && sizeof($time_list) > 0) {
                    foreach ($time_list as $time_key => $time_val) {
                        $time_from_html .= '<option value="' . $time_key . '">' . date_i18n('g:i a', strtotime($time_val)) . '</option>' . "\n";
                        $time_to_html .= '<option value="' . $time_key . '">' . date_i18n('g:i a', strtotime($time_val)) . '</option>' . "\n";
                    }
                }
            }

            $days_html = '';
            if (is_array($week_days) && sizeof($week_days) > 0) {
                foreach ($week_days as $day_key => $week_day) {
                    $day_status = isset($get_opening_hours[$day_key]['day_status']) ? $get_opening_hours[$day_key]['day_status'] : '';
                    if (isset($get_opening_hours) && is_array($get_opening_hours) && sizeof($get_opening_hours) > 0) {
                        $opening_time = isset($get_opening_hours[$day_key]['opening_time']) ? $get_opening_hours[$day_key]['opening_time'] : '';
                        $closing_time = isset($get_opening_hours[$day_key]['closing_time']) ? $get_opening_hours[$day_key]['closing_time'] : '';
                        if (is_array($time_list) && sizeof($time_list) > 0) {
                            $time_from_html = '';
                            $time_to_html = '';
                            foreach ($time_list as $time_key => $time_val) {
                                $time_from_html .= '<option value="' . $time_key . '"' . ($opening_time == $time_key ? ' selected="selected"' : '') . '>' . date_i18n('g:i a', strtotime($time_val)) . '</option>' . "\n";
                                $time_to_html .= '<option value="' . $time_key . '"' . ($closing_time == $time_key ? ' selected="selected"' : '') . '>' . date_i18n('g:i a', strtotime($time_val)) . '</option>' . "\n";
                            }
                        }
                    }
                    $days_html .= '
                                               <li>
                                                   <div id="open-close-con-' . $day_key . '-' . $member_add_counter . '" class="open-close-time' . (isset($day_status) && $day_status == 'on' ? ' opening-time' : '') . '">
                                                       <div class="day-sec">
                                                           <span>' . $week_day . '</span>
                                                       </div>
                                                   <div class="time-sec">
                                                       <select class="chosen-select " name="wp_dp_opening_hour[' . $day_key . '][opening_time]">
                                                           ' . $time_from_html . '
                                                       </select>
                                                           <span class="option-label">' . wp_dp_plugin_text_srt('wp_dp_member_to') . '</span>
                                                       <select class="chosen-select " name="wp_dp_opening_hour[' . $day_key . '][closing_time]">
                                                           ' . $time_to_html . '
                                                       </select>
                                                           <a id="wp-dp-dev-close-time-' . $day_key . '-' . $member_add_counter . '" href="javascript:void(0);" data-id="' . $member_add_counter . '" data-day="' . $day_key . '" title="' . wp_dp_plugin_text_srt('wp_dp_member_close') . '"><i class="icon-close"></i></a>
                                                   </div>
                                                   <div class="close-time">
                                                       <a id="wp-dp-dev-open-time-' . $day_key . '-' . $member_add_counter . '" href="javascript:void(0);" data-id="' . $member_add_counter . '" data-day="' . $day_key . '">' . wp_dp_plugin_text_srt('wp_dp_member_closed') . ' <span>(' . wp_dp_plugin_text_srt('wp_dp_member_add_opening_hours') . ')</span></a>
                                                           <input id="wp-dp-dev-open-day-' . $day_key . '-' . $member_add_counter . '" type="hidden" name="wp_dp_opening_hour[' . $day_key . '][day_status]"' . (isset($day_status) && $day_status == 'on' ? ' value="on"' : '') . '>
                                                   </div>
                                                   </div>
                                               </li>';
                }
            }
            $html .= '
                                       <div class="wp-dp-dev-appended form-elements">
                                               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                       <div class="time-list">
                                                               <ul>
                                                                       ' . $days_html . '
                                                               </ul>
                                                       </div>
                                               </div>
                                       </div>
                                       <script>
						jQuery(document).ajaxComplete(function() {
							jQuery(".chosen-select").chosen();
						});
						</script>';

            return $html;
        }

        public function member_time_list() {
            $lapse = 15;
            $hours = array();
            $date = date("Y/m/d 12:00");
            $time = strtotime('12:00 am');
            $start_time = strtotime($date . ' am');
            $endtime = strtotime(date("Y/m/d h:i a", strtotime('1440 minutes', $start_time)));
            while ($start_time < $endtime) {
                $time = date("h:i a", strtotime('+' . $lapse . ' minutes', $time));
                $hours[$time] = $time;
                $time = strtotime($time);
                $start_time = strtotime(date("Y/m/d h:i a", strtotime('+' . $lapse . ' minutes', $start_time)));
            }
            return $hours;
        }

        public function member_week_days() {
            $week_days = array(
                'monday' => wp_dp_plugin_text_srt('wp_dp_member_monday'),
                'tuesday' => wp_dp_plugin_text_srt('wp_dp_member_tuesday'),
                'wednesday' => wp_dp_plugin_text_srt('wp_dp_member_wednesday'),
                'thursday' => wp_dp_plugin_text_srt('wp_dp_member_thursday'),
                'friday' => wp_dp_plugin_text_srt('wp_dp_member_friday'),
                'saturday' => wp_dp_plugin_text_srt('wp_dp_member_saturday'),
                'sunday' => wp_dp_plugin_text_srt('wp_dp_member_sunday')
            );
            return $week_days;
        }

        /**
         * Set Book Days off
         * @return markup
         */
        public function member_book_days_off($return_type = 'echo') {
            global $member_add_counter;
            $html = array(); 
            $off_days_list = '';
// In case of changing wp_dp type ajax
// it will load the pre filled data
            $get_member_form_select_type = wp_dp_get_input('select_type', '', 'STRING');
            if ($get_member_form_select_type != '') {
                $get_member_form_days_off = wp_dp_get_input('wp_dp_member_off_days', '', 'ARRAY');
                if (is_array($get_member_form_days_off) && sizeof($get_member_form_days_off)) {
                    foreach ($get_member_form_days_off as $get_off_day) {
                        $off_days_list .= $this->append_to_book_days_off($get_off_day);
                    }
                }
            }
// end ajax loading

            $user_id = get_current_user_id();
            $get_member_id = get_user_meta($user_id, 'wp_dp_company', true);
            if ($get_member_id != '' && $get_member_id != 0) {
                $get_member_off_days = get_post_meta($get_member_id, 'wp_dp_calendar', true);
                if (is_array($get_member_off_days) && sizeof($get_member_off_days)) {
                    foreach ($get_member_off_days as $get_off_day) {
                        $off_days_list .= $this->append_to_book_days_off($get_off_day);
                    }
                }
            }
            if ($off_days_list == '') {
                $off_days_list = '<li id="no-book-day-' . $member_add_counter . '" class="no-result-msg">' . wp_dp_plugin_text_srt('wp_dp_member_days_added') . '</li>';
            }

            wp_enqueue_script('responsive-calendar');
            $html['cale_html'] = isset( $html['cale_html'] )? $html['cale_html'] : '';
           $html['cale_html'] .= '<ul class="off-day-dropdown"><li class="wp-dp-dev-appended">
				
					
							<div id="dev-off-day-loader-' . $member_add_counter . '" class="listing-loader"></div>
							<a class="book-btn" href="javascript:void(0);">' . wp_dp_plugin_text_srt('wp_dp_member_off_days') . ' +</a>
							<div id="wp-dp-dev-cal-holder-' . $member_add_counter . '" class="calendar-holder">
								<div data-id="' . $member_add_counter . '" class="wp-dp-dev-insert-off-days responsive-calendar">
									<span class="availability">' . wp_dp_plugin_text_srt('wp_dp_member_availability') . '</span>
									<div class="controls">
										<a data-go="prev"><div class="btn btn-primary"><i class="icon-angle-left"></i></div></a>
										<h4><span data-head-month></span> <span data-head-year></span></h4>
										<a data-go="next"><div class="btn btn-primary"><i class="icon-angle-right"></i></div></a>
									</div>
									<div class="day-headers">
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_sun') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_mon') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_tue') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_wed') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_thu') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_fri') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_sat') . '</div>
									</div>
									<div class="days wp-dp-dev-calendar-days" data-group="days"></div>
								</div>
							</div>
						</div>
						<script>
						jQuery(document).ajaxComplete(function() {
							jQuery(".responsive-calendar").responsiveCalendar({
								time: "' . date('Y-m') . '",
								monthChangeAnimation: false,
								events: {
									"' . date('Y-m-d') . '": {
										number: 5,
										url: ""
									}
							}
							});
						});
						</script>
					</div>
                                        </li></ul>';
            $html['book_off_days_list_html'] = '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="book-list">
								<ul id="wp-dp-dev-add-off-day-app-' . $member_add_counter . '">
									' . $off_days_list . '
								</ul>
							</div>
                                        </div></div>';
            if($return_type == 'echo'){
                return $html['cale_html'] . '' .$html['book_off_days_list_html'] ;
            }else{
                return $html;
            }
        }

        public function append_to_book_days_off($get_off_day = '') {

            if ($get_off_day != '') {
                $book_off_date = $get_off_day;
            } else {
                $day = wp_dp_get_input('off_day_day', date('d'), 'STRING');
                $month = wp_dp_get_input('off_day_month', date('m'), 'STRING');
                $year = wp_dp_get_input('off_day_year', date('Y'), 'STRING');
                $book_off_date = $year . '-' . $month . '-' . $day;
            }
            $formated_off_date_day = date_i18n("l", strtotime($book_off_date));

            $formated_off_date = date_i18n(get_option('date_format'), strtotime($book_off_date));

            $rand_numb = rand(100000000, 999999999);

            $html = '<li id="day-dpove-' . $rand_numb . '">
				<div class="open-close-time opening-time">
					<div class="date-sec">
						<span>' . $formated_off_date_day . '</span>
						<input type="hidden" value="' . $book_off_date . '" name="wp_dp_listing_off_days[]">
					</div>
					<div class="time-sec"> '.$formated_off_date.'
						<a id="wp-dp-dev-day-off-dp-' . $rand_numb . '" data-id="' . $rand_numb . '" href="javascript:void(0);"><i class="icon-close"></i></a>
					</div>
				</div>
			</li>';

            if ($get_off_day != '') {
                return apply_filters('wp_dp_front_listing_add_single_off_day', $html, $get_off_day);
// usage :: add_filter('wp_dp_front_listing_add_single_off_day', 'my_callback_function', 10, 2);
            } else {
                echo json_encode(array('html' => $html));
                die;
            }
        }

    }

    global $wp_dp_member_profile;
    $wp_dp_member_profile = new Wp_dp_Member_Profile();
}
