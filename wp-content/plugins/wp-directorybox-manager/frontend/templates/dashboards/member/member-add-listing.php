<?php
/**
 * Member add/edit Listing
 *
 */
if ( ! class_exists('wp_dp_member_listing_actions') ) {

    class wp_dp_member_listing_actions {

        /**
         * Start construct Functions
         */
        public function __construct() {
            $this->listing_action_hooks();
        }

        /**
         * Listing Hooks
         * @return
         */
        public function listing_action_hooks() {
            add_action('wp_dp_listing_add', array( $this, 'add_edit_listing' ), 10, 1);
            add_action('wp_dp_listing_basic_info', array( $this, 'title_description' ), 10, 1);
            add_action('wp_dp_contact_info', array( $this, 'listing_contact_information' ), 10, 3);
            add_action('wp_dp_listing_user_signup', array( $this, 'user_register_fields' ), 10, 1);
            add_action('wp_dp_listing_type_selection', array( $this, 'select_listing_type' ), 10, 1);
            add_action('wp_dp_listing_add_info', array( $this, 'listing_info' ), 10, 1);
            add_filter('wp_dp_listing_add_loader', array( $this, 'ajax_loader' ), 10, 1);
            add_action('wp_dp_listing_add_tag_before', array( $this, 'listing_add_tag_before' ), 10);
            add_action('wp_dp_listing_add_tag_after', array( $this, 'listing_add_tag_after' ), 10);

            add_action('wp_dp_listing_add_meta_data', array( $this, 'listing_meta_data' ), 10);
            add_action('wp_dp_listing_add_packages', array( $this, 'listing_packages' ), 10);
            add_action('wp_dp_listing_add_submit_button', array( $this, 'listing_submit_button' ), 10);
            add_action('wp_dp_listing_add_meta_save', array( $this, 'listing_meta_save' ), 10);
            add_action('wp_dp_listing_add_save_assignments', array( $this, 'listing_save_assignments' ), 10, 2);
            add_action('wp_dp_listing_add_assign_status', array( $this, 'listing_update_status' ), 10, 1);
            add_action('wp_dp_listing_assign_trans_meta', array( $this, 'listing_assign_meta' ), 10, 2);
            add_action('wp_dp_listing_social_post', array( $this, 'social_post_after_activation' ), 10, 1);
            add_action('wp_ajax_wp_dp_listing_load_cf', array( $this, 'custom_fields_features' ));
            add_action('wp_ajax_nopriv_wp_dp_listing_load_cf', array( $this, 'custom_fields_features' ));

            add_action('wp_ajax_wp_dp_listing_floor_plan_to_list', array( $this, 'append_to_floor_plans_list' ));
            add_action('wp_ajax_nopriv_wp_dp_listing_floor_plan_to_list', array( $this, 'append_to_floor_plans_list' ));
            add_action('wp_ajax_wp_dp_listing_apartment_to_list', array( $this, 'append_to_apartment_list' ));
            add_action('wp_ajax_nopriv_wp_dp_listing_apartment_to_list', array( $this, 'append_to_apartment_list' ));

            add_action('wp_ajax_wp_dp_listing_opening_house_to_list', array( $this, 'append_to_opening_house_list' ));
            add_action('wp_ajax_nopriv_wp_dp_listing_opening_house_to_list', array( $this, 'append_to_opening_house_list' ));

            add_action('wp_ajax_wp_dp_listing_attachments_to_list', array( $this, 'append_to_attachments_list' ));
            add_action('wp_ajax_nopriv_wp_dp_listing_attachments_to_list', array( $this, 'append_to_attachments_list' ));
            add_action('wp_ajax_get_atachment_id_by_file', array( $this, 'get_atachment_id_by_file' ));
            add_action('wp_ajax_nopriv_get_atachment_id_by_file', array( $this, 'get_atachment_id_by_file' ));


            add_action('wp_ajax_wp_dp_listing_off_day_to_list', array( $this, 'append_to_book_days_off' ));
            add_action('wp_ajax_nopriv_wp_dp_listing_off_day_to_list', array( $this, 'append_to_book_days_off' ));
            add_action('wp_ajax_wp_dp_new_package_info', array( $this, 'new_package_info' ));
            add_action('wp_ajax_nopriv_wp_dp_new_package_info', array( $this, 'new_package_info' ));
            add_action('wp_ajax_wp_dp_subs_package_info', array( $this, 'subs_package_info' ));
            add_action('wp_ajax_nopriv_wp_dp_subs_package_info', array( $this, 'subs_package_info' ));
            add_action('wp_ajax_wp_dp_listing_user_authentication', array( $this, 'user_authentication' ));
            add_action('wp_ajax_nopriv_wp_dp_listing_user_authentication', array( $this, 'user_authentication' ));
            add_action('before_wp_dp_listing_add', array( $this, 'before_listing' ), 10, 1);
            add_action('after_wp_dp_listing_add', array( $this, 'after_listing' ), 10, 1);
        }

        /**
         * add/edit Listing
         * @return markup
         */
        public function listing_contact_information($type_id = '', $wp_dp_id = '') {
            global $wp_dp_form_fields, $listing_add_counter, $wp_dp_plugin_options;
            if ( $type_id == '' ) {
                $wp_dp_id = wp_dp_get_input('listing_id', 0);
            }

            $listing_email = get_post_meta($wp_dp_id, 'wp_dp_listing_contact_email', true);
            $listing_phone = get_post_meta($wp_dp_id, 'wp_dp_listing_contact_phone', true);
            $listing_web = get_post_meta($wp_dp_id, 'wp_dp_listing_contact_web', true);
            $html = '<li class="wp-dp-dev-appended"> <div class="field-holder">';
            $html .= '
			<div class="dashboard-element-title">
				<strong>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_contact_info') . '</strong>
			</div>
			<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_email') . '</label>';
            $html .= $wp_dp_form_fields->wp_dp_form_text_render(
                    array(
                        'id' => 'listing_contact_email_' . $listing_add_counter,
                        'cust_name' => 'wp_dp_listing_contact_email',
                        'std' => $listing_email,
                        'desc' => '',
                        'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_email') . '"',
                        'classes' => 'wp-dp-email-field',
                        'return' => true,
                        'force_std' => true,
                        'hint_text' => '',
                    )
            );
            $html .= '
			</div>
			<div class="field-holder">
			<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_phone') . '</label>';
            $html .= $wp_dp_form_fields->wp_dp_form_text_render(
                    array(
                        'id' => 'listing_contact_phone_' . $listing_add_counter,
                        'cust_name' => 'wp_dp_listing_contact_phone',
                        'std' => $listing_phone,
                        'desc' => '',
                        'classes' => 'wp-dp-number-field',
                        'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_phone') . '"',
                        'return' => true,
                        'force_std' => true,
                        'hint_text' => '',
                    )
            );
            $html .= '
			</div>
			<div class="field-holder">
			<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_web') . '</label>';
            $html .= $wp_dp_form_fields->wp_dp_form_text_render(
                    array(
                        'id' => 'listing_contact_web_' . $listing_add_counter,
                        'cust_name' => 'wp_dp_listing_contact_web',
                        'std' => $listing_web,
                        'desc' => '',
                        'classes' => 'wp-dp-url-field',
                        'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_web') . '"',
                        'return' => true,
                        'force_std' => true,
                        'hint_text' => '',
                    )
            );

            $html .= '
			</div></li>';
            return apply_filters('wp_dp_front_listing_add_contact_information', $html, $type_id, $wp_dp_id);
        }

        public function add_edit_listing($params = array()) {
            wp_enqueue_script('wp-dp-validation-script');
            global $listing_add_counter, $wp_dp_plugin_options;
            extract($params);
            ob_start();
            $listing_add_counter = rand(10000000, 99999999);

            wp_enqueue_script('wp-dp-listing-add');
            //editor
            wp_enqueue_style('jquery-te');
            wp_enqueue_script('jquery-te');

            //iconpicker
            wp_enqueue_style('fonticonpicker');
            wp_enqueue_script('fonticonpicker');
            ?>
            <div id="wp-dp-dev-posting-main-<?php echo absint($listing_add_counter); ?>" class="user-holder" data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" data-plugin-url="<?php echo esc_url(wp_dp::plugin_url()); ?>">
                <?php
                do_action('wp_dp_listing_add_meta_save');
                ?>
                <form id="wp-dp-dev-listing-form-<?php echo absint($listing_add_counter); ?>" name="wp-dp-dev-listing-form" class="wp-dp-dev-listing-form" data-id="<?php echo absint($listing_add_counter); ?>" method="post" enctype="multipart/form-data">
                    <?php
                    do_action('wp_dp_listing_add_tag_before');
                    do_action('wp_dp_listing_basic_info', '');
                    do_action('wp_dp_listing_user_signup', '');
                    do_action('wp_dp_listing_type_selection', '');
                    do_action('wp_dp_listing_add_meta_data');
                    do_action('wp_dp_listing_add_info', '');
                    do_action('wp_dp_listing_add_packages');
                    do_action('wp_dp_listing_add_submit_button');
                    do_action('after_wp_dp_listing_add', '');
                    do_action('wp_dp_listing_add_tag_after');
                    ?>
                </form>
            </div>
            <?php
            $html = ob_get_clean();
            if ( isset($return_html) && $return_html == true ) {
                return $html;
            } else {
                echo force_balance_tags($html);
            }
        }

        /**
         * Basic Info
         * @return markup
         */
        public function title_description($html = '') {
            global $wp_dp_form_fields, $listing_add_counter, $wp_dp_plugin_options;
            $wp_dp_listing_title = '';
            $wp_dp_listing_desc = '';
            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $wp_dp_listing_title = get_the_title($get_listing_id);
                $wp_dp_listing_desc = $this->listing_post_content($get_listing_id);
            }
            $html .= '
			<li>
			<div class="row">';
            $wp_dp_listing_announce_title = isset($wp_dp_plugin_options['wp_dp_listing_announce_title']) ? $wp_dp_plugin_options['wp_dp_listing_announce_title'] : '';
            $wp_dp_listing_announce_description = isset($wp_dp_plugin_options['wp_dp_listing_announce_description']) ? $wp_dp_plugin_options['wp_dp_listing_announce_description'] : '';
            ob_start();
            if ( (isset($wp_dp_listing_announce_title) && $wp_dp_listing_announce_title <> '') || (isset($wp_dp_listing_announce_description) && $wp_dp_listing_announce_description <> '') ) {
                do_action('before_wp_dp_listing_add', '');
            }
            $html .= ob_get_clean();
            $html .= '
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="field-holder">
					<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_title') . '</label>';
            $html .= $wp_dp_form_fields->wp_dp_form_text_render(
                    array(
                        'id' => 'listing_title_' . $listing_add_counter,
                        'cust_name' => 'wp_dp_listing_title',
                        'std' => $wp_dp_listing_title,
                        'desc' => '',
                        'classes' => 'wp-dp-dev-req-field',
                        'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_title') . '"',
                        'return' => true,
                        'force_std' => true,
                        'hint_text' => '',
                    )
            );
            $html .= '
				</div>
				<div class="field-holder">
					<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_description') . '</label>';
            $html .= $wp_dp_form_fields->wp_dp_form_textarea_render(
                    array(
                        'name' => '',
                        'id' => 'listing_desc_' . $listing_add_counter,
                        'cust_name' => 'wp_dp_listing_desc',
                        'classes' => 'wp-dp-dev-req-field ad-wp-dp-editor',
                        'std' => $wp_dp_listing_desc,
                        'description' => '',
                        'return' => true,
                        'wp_dp_editor' => true,
                        'force_std' => true,
                        'hint' => ''
                    )
            );
            $html .= '
					</div>
				</div>
			</div>
			</li>';
            echo force_balance_tags($html);
        }

        /**
         * User Register Fields
         * @return markup
         */
        public function user_register_fields($html = '') {
            global $listing_add_counter;

            $is_updating = false;
            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
            }

            if ( ! $is_updating && ! is_user_logged_in() ) {
                $html .= '
				<li id="wp-dp-dev-user-signup-' . $listing_add_counter . '">
				<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="dashboard-element-title">
						<strong>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_signup') . '</strong>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="field-holder">
						<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_username_asterisk') . '</label>
						<input type="text" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_username') . '" data-id="' . $listing_add_counter . '" data-type="username" name="wp_dp_listing_username" class="wp-dp-dev-username wp-dp-dev-req-field">
						<span class="field-info wp-dp-dev-username-check"></span>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="field-holder">
						<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_email_asterisk') . '</label>
						<input type="text" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_email_adress') . '" data-id="' . $listing_add_counter . '" data-type="useremail" name="wp_dp_listing_user_email" class="wp-dp-dev-user-email wp-dp-dev-req-field">
						<span class="field-info wp-dp-dev-useremail-check"></span>
					</div>
				</div>
				</div>
				</li>';
            }
            echo force_balance_tags($html);
        }

        /**
         * Select Listing Type
         * @return markup
         */
        public function select_listing_type($html = '') {
            global $wp_dp_form_fields, $listing_add_counter;
            $selected_type = '';
            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $selected_type = get_post_meta($get_listing_id, 'wp_dp_listing_type', true);
            } else {
                $types_args = array( 'posts_per_page' => '-1', 'post_type' => 'listing-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC' );
                $cust_query = get_posts($types_args);
                $selected_type = isset($cust_query[0]->post_name) ? $cust_query[0]->post_name : '';
            }

            $types_options = '';
            $types_args = array( 'posts_per_page' => '-1', 'post_type' => 'listing-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC' );
            $cust_query = get_posts($types_args);
            $types_options .= '<option value="">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_select_type') . '</option>';
            if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                $type_counter = 1;
                foreach ( $cust_query as $type_post ) {
                    $option_selected = '';
                    if ( $selected_type != '' && $selected_type == $type_post->post_name ) {
                        $option_selected = ' selected="selected"';
                    } else if ( $type_counter == 1 ) {
                        
                    }
                    $types_data[$type_post->post_name] = get_the_title($type_post->ID);
                    $types_options .= '<option' . $option_selected . ' value="' . $type_post->post_name . '">' . get_the_title($type_post->ID) . '</option>' . "\n";
                    $type_counter ++;
                }
            }
            $html .= '
			<li id="wp-dp-type-sec-' . $listing_add_counter . '" class="wp-dp-type-holder">
			<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="dashboard-element-title">
					<strong>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_manager_type') . '</strong>
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="field-holder">';
            $html .= apply_filters('wp_dp_listing_add_loader', false);
            if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                $html .= '<ul class="listing-radios">';
                foreach ( $cust_query as $post_typ ) {
                    $typ_imag = get_post_meta($post_typ->ID, 'wp_dp_listing_type_image', true);
                    $typ_imag = wp_get_attachment_url($typ_imag);
                    $html .= '	
					<li>
						<input id="listing-type-' . $post_typ->ID . '" data-id="' . $listing_add_counter . '" class="wp-dp-dev-select-type" type="radio" name="wp_dp_listing_type" value="' . $post_typ->post_name . '"' . ($post_typ->post_name == $selected_type ? ' checked="checked"' : '') . '>
						<label for="listing-type-' . $post_typ->ID . '"><span>' . $post_typ->post_title . '</span><img src="' . $typ_imag . '" alt=""></label>
					</li>';
                }
                $html .= '</ul>';
            }

            $html .= '
				</div>
			</div>
			
			</div>
			</li>';
            echo force_balance_tags($html);
        }

        /**
         * Info Icon Check
         * @return markup
         */
        public function listing_info_icon_check($info_el = '') {
            $info_icon = $info_el == 'on' ? '<i class="icon-check2"></i>' : '<i class="icon-minus"></i>';
            return $info_icon;
        }

        /**
         * Info Field Create
         * @return markup
         */
        public function listing_info_field_show($info_meta = array(), $index = '') {
            if ( isset($info_meta[$index]) && isset($info_meta[$index]['key']) && isset($info_meta[$index]['label']) && isset($info_meta[$index]['value']) ) {
                $key = isset($info_meta[$index]['key']) ? $info_meta[$index]['key'] : '';
                $label = isset($info_meta[$index]['label']) ? $info_meta[$index]['label'] : '';
                $value = isset($info_meta[$index]['value']) ? $info_meta[$index]['value'] : '';
                if ( $value != '' && $value != 'on' ) {
                    $html = '<li><label>' . $label . '</label><span>' . $value . '</span></li>';
                } else if ( $value != '' && $value == 'on' ) {
                    $html = '<li><label>' . $label . '</label><span><i class="icon-check2"></i></span></li>';
                } else {
                    $html = '<li><label>' . $label . '</label><span><i class="icon-minus"></i></span></li>';
                }

                return $html;
            }
        }

        /**
         * Select Listing Type
         * @return markup
         */
        public function listing_info($html = '') {
            global $listing_add_counter;
            $selected_type = '';
            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $listing_status = get_post_meta($get_listing_id, 'wp_dp_listing_status', true);
                $listing_post_on = get_post_meta($get_listing_id, 'wp_dp_listing_posted', true);
                $listing_post_expiry = get_post_meta($get_listing_id, 'wp_dp_listing_expired', true);

                $listing_post_expiry_date = date('d-m-Y', $listing_post_expiry);
                $listing_post_on_date = date('d-m-Y', $listing_post_on);

                $html .= '
				<li id="listing-info-sec-' . $listing_add_counter . '">
				<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="dashboard-element-title">
						<strong>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_listing_info') . '</strong>
					</div>
					<div class="buy-new-pakg-actions">
						<label>
							<a data-id="' . $listing_add_counter . '" href="javascript:void(0);" class="dev-wp-dp-listing-update-package">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_update_pkg') . '</a>
						</label>
					</div>
				</div>';
                if ( $listing_status == '' ) {
                    $listing_status = 'pending';
                }
                // pending post
                if ( $listing_status == 'pending' ) {
                    $html .= '
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="listing-info-sec">
							<div class="field-holder">
								<p>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_listing_pending_mode') . '</p>
							</div>
						</div>
					</div>';
                }
                // expired post
                else if ( strtotime($listing_post_expiry_date) >= strtotime($listing_post_on_date) && strtotime($listing_post_expiry_date) <= strtotime(current_time('d-m-Y', 1)) ) {
                    $html .= '
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="listing-info-sec">
						<div class="field-holder">
							<p>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_listing_expired') . '</p>
						</div>
						</div>
					</div>';
                }
                // awaiting approval OR active Listing
                else if ( strtotime($listing_post_expiry_date) > strtotime(current_time('d-m-Y', 1)) && $listing_status != 'pending' ) {

                    $listing_status_str = WP_DP_FUNCTIONS()->get_listing_status($listing_status);

                    $listing_is_featured = get_post_meta($get_listing_id, 'wp_dp_listing_is_featured', true);
                    $listing_is_featured = $this->listing_info_icon_check($listing_is_featured);
                    $listing_is_top_cat = get_post_meta($get_listing_id, 'wp_dp_listing_is_top_cat', true);
                    $listing_is_top_cat = $this->listing_info_icon_check($listing_is_top_cat);
                    $trans_all_meta = get_post_meta($get_listing_id, 'wp_dp_trans_all_meta', true);

                    $trans_dynamic_meta = get_post_meta($get_listing_id, 'wp_dp_transaction_dynamic', true);

                    $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                    $html .= '<div class="package-info-sec listing-info-sec">';
                    $html .= '<div class="row">';
                    $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                    $html .= '<ul class="listing-pkg-points">';
                    $active_class = '';
                    if ( $listing_status_str == '' ) {
                        $active_class = ' class="active"';
                    }
                    $html .= '<li><label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_expiry') . '</label><span class="info-expiry-date">' . date_i18n(get_option('date_format'), $listing_post_expiry) . '</span></li>';
                    $html .= '<li><label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_status') . '</label><span ' . $active_class . '>' . $listing_status_str . '</span></li>';
                    $html .= '<li><label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_featured') . '</label><span>' . $listing_is_featured . '</span></li>';
                    $html .= '<li><label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_top_category') . '</label><span>' . $listing_is_top_cat . '</span></li>';
                    $html .= $this->listing_info_field_show($trans_all_meta, 0);
                    $html .= $this->listing_info_field_show($trans_all_meta, 1);
                    $html .= $this->listing_info_field_show($trans_all_meta, 2);
                    $html .= $this->listing_info_field_show($trans_all_meta, 3);
                    $html .= $this->listing_info_field_show($trans_all_meta, 4);

                    
                    $html .= $this->listing_info_field_show($trans_all_meta, 5);
                    $html .= $this->listing_info_field_show($trans_all_meta, 6);
                    $html .= $this->listing_info_field_show($trans_all_meta, 7);
                    $html .= $this->listing_info_field_show($trans_all_meta, 8);
                    $html .= $this->listing_info_field_show($trans_all_meta, 9);

                    if ( is_array($trans_dynamic_meta) && sizeof($trans_dynamic_meta) > 0 ) {
                        foreach ( $trans_dynamic_meta as $trans_dynamic ) {
                            if ( isset($trans_dynamic['field_type']) && isset($trans_dynamic['field_label']) && isset($trans_dynamic['field_value']) ) {
                                $d_type = $trans_dynamic['field_type'];
                                $d_label = $trans_dynamic['field_label'];
                                $d_value = $trans_dynamic['field_value'];

                                if ( $d_value == 'on' && $d_type == 'single-choice' ) {
                                    $html .= '<li><label>' . $d_label . '</label><span><i class="icon-check2"></i></span></li>';
                                } else if ( $d_value != '' && $d_type != 'single-choice' ) {
                                    $html .= '<li><label>' . $d_label . '</label><span>' . $d_value . '</span></li>';
                                } else {
                                    $html .= '<li><label>' . $d_label . '</label><span><i class="icon-minus"></i></span></li>';
                                }
                            }
                        }
                        // end foreach
                    }
                    // end of Dynamic fields
                    // other Features

                    $html .= '
					</ul>
					</div>
					</div>
					</div>
					</div>';
                }
                $html .= '
				</div>
				</li>';
            }
            echo force_balance_tags($html);
        }

        /**
         * Loading custom fields and features
         * while selecting type
         * @return markup
         */
        public function custom_fields_features() {
            global $listing_add_counter;
            $cus_fields_html = '';
            $main_append_html = '';
            $listing_add_counter = wp_dp_get_input('listing_add_counter', '');
            $select_type = wp_dp_get_input('select_type', '');
            if ( $select_type != '' ) {
                $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$select_type", 'post_status' => 'publish' ));
                $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;

                $cus_fields_html = $this->custom_fields($listing_type_id);
                $main_append_html = $this->listing_categories($listing_type_id);
                $main_append_html .= $this->listing_price($listing_type_id);
                $main_append_html .= $this->listing_location($listing_type_id);
                //$main_append_html .= $this->listing_gallery($listing_type_id);
                $main_append_html .= $this->listing_tags($listing_type_id);
                $main_append_html .= $this->listing_features_list($listing_type_id);
                //$main_append_html .= $this->listing_opening_house($listing_type_id);
                //$main_append_html .= $this->listing_attachments($listing_type_id);
                //$main_append_html .= $this->listing_floor_plans($listing_type_id);
                $main_append_html .= $this->listing_apartment($listing_type_id);
            }
            die;
        }

        /**
         * Ajax Loader
         * @return markup
         */
        public function ajax_loader($echo = true) {
            global $listing_add_counter;
            $html = '
			<div id="wp-dp-dev-loader-' . absint($listing_add_counter) . '" class="wp-dp-loader"></div>
			<div id="wp-dp-dev-act-msg-' . absint($listing_add_counter) . '" class="wp-dp-loader"></div>';
            if ( $echo ) {
                echo force_balance_tags($html);
            } else {
                return force_balance_tags($html);
            }
        }

        /**
         * Features List
         * @return markup
         */
        public function listing_features_list($type_id = '', $wp_dp_id = '') {
            global $listing_add_counter;

            $html = '';
            $wp_dp_listing_features = get_post_meta($wp_dp_id, 'wp_dp_listing_feature_list', true);
            $wp_dp_get_features = get_post_meta($type_id, 'feature_lables', true);
            $wp_dp_feature_icons = get_post_meta($type_id, 'wp_dp_feature_icon', true);

            if ( is_array($wp_dp_get_features) && sizeof($wp_dp_get_features) > 0 ) {
                $html .= '
				<li class="wp-dp-dev-appended">
				<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="dashboard-element-title">
						<strong>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_feature_list') . '</strong>
						<a id="choose-all-apply-' . $listing_add_counter . '" data-id="' . $listing_add_counter . '" class="choose-all-apply" href="javascript:void(0);">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_select_unselect') . '</a>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="row">
				<div class="field-holder">
				<ul id="features-check-list-' . $listing_add_counter . '" class="checkbox-list">';
                $feature_counter = 1;
                foreach ( $wp_dp_get_features as $feat_key => $features ) {
                    if ( isset($features) && ! empty($features) ) {

                        $wp_dp_feature_name = isset($features) ? $features : '';
                        $wp_dp_feature_icon = isset($wp_dp_feature_icons[$feat_key]) ? $wp_dp_feature_icons[$feat_key] : '';

                        $html .= '
						<li class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<input type="checkbox" id="feature-list-check-' . $wp_dp_id . $feature_counter . '" value="' . $wp_dp_feature_name . "_icon" . $wp_dp_feature_icon . '" name="wp_dp_listing_feature[]"' . (is_array($wp_dp_listing_features) && in_array($wp_dp_feature_name . "_icon" . $wp_dp_feature_icon, $wp_dp_listing_features) ? ' checked="checked"' : '') . '>
							<label for="feature-list-check-' . $wp_dp_id . $feature_counter . '">';
                        if ( $wp_dp_feature_icon != '' ) {
                            $html .= '<i class="' . $wp_dp_feature_icon . '"></i>';
                        }
                        $html .= $wp_dp_feature_name . '</label>
						</li>';
                        $feature_counter ++;
                    }
                }
                $html .= '</ul>
				</div>
				</div>
				</div>
				</div>
				</li>';
            }
            return apply_filters('wp_dp_front_listing_add_features_list', $html, $type_id, $wp_dp_id);
            //add_filter('wp_dp_front_listing_add_features_list', 'my_callback_function', 10, 3);
        }

        /**
         * Location Map
         * @return markup
         */
        public function listing_location($type_id = '', $wp_dp_id = '') {
            global $listing_add_counter;
            $html = '';
            $wp_dp_listing_location = get_post_meta($type_id, 'wp_dp_location_element', true);
            //if ( $wp_dp_listing_location == 'on' ) {
            $html .= '
				<li class="wp-dp-dev-appended location-holder">
				<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="dashboard-element-title">
					<strong>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_step4_address') . '</strong>
				</div>
					<div class="row">';
            ob_start();
            WP_DP_FUNCTIONS()->wp_dp_frontend_location_fields('on', $wp_dp_id, 'listing', '', true, true);
            $html .= ob_get_clean();
            $html .= '</div>
				</div>
				</div>
				</li>';
            //}
            return apply_filters('wp_dp_front_listing_add_location', $html, $type_id, $wp_dp_id);
            // usage :: add_filter('wp_dp_front_listing_add_location', 'my_callback_function', 10, 3);
        }

        /*
         * Listing Video
         */

        public function listing_listing_video_image($type_id = '', $wp_dp_id = '') {
            global $listing_add_counter, $wp_dp_html_fields_frontend, $wp_dp_form_fields;
            $html = '';

            $wp_dp_listing_video = get_post_meta($wp_dp_id, 'wp_dp_listing_video', true);
            $wp_dp_listing_video = isset($wp_dp_listing_video) ? $wp_dp_listing_video : '';

            $html .= '<li class="wp-dp-dev-appended">
			<div class="row featured-image-holder">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="dashboard-element-title">
						<strong>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_listing_video') . '</strong>
					</div>
				</div>
				
			</div>';
            $html .= '<div class="field-holder">
                        <label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_video_url') . '</label>';
            $html .= $wp_dp_form_fields->wp_dp_form_text_render(
                    array(
                        'id' => '',
                        'cust_name' => 'wp_dp_listing_video',
                        'std' => $wp_dp_listing_video,
                        'desc' => '',
                        'classes' => 'wp-dp-url-field',
                        'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_listing_video') . '"',
                        'return' => true,
                        'force_std' => true,
                        'hint_text' => '',
                    )
            );
            $html .= '</div></li>';

            return apply_filters('wp_dp_front_listing_add_video_image', $html, $type_id, $wp_dp_id);
            // usage :: add_filter('wp_dp_front_listing_add_featured_image', 'my_callback_function', 10, 3);
        }

        /*
         * Listing Video End 
         */

        /**
         * Featured Image
         * @return markup
         */
        public function listing_featured_image($type_id = '', $wp_dp_id = '') {
            global $listing_add_counter, $wp_dp_html_fields_frontend;
            $html = '';

            $listing_featured_image = get_post_thumbnail_id($wp_dp_id);
            $attacment_placeholder = '';
            $listing_added_featured_image = '';

            $placeholder_style = '';

            if ( $listing_featured_image != '' ) {
                $img_url = wp_get_attachment_url($listing_featured_image);
                $listing_added_featured_image .= '
				<li class="gal-img">
					<div class="drag-list">
						<div class="item-thumb"><img class="thumbnail" src="' . $img_url . '" alt=""/></div>
						<div class="item-assts">
							<ul class="list-inline pull-right">
								<li class="drag-btn"><a href="javascript:void(0);"><i class="icon-bars"></i></a></li>
								<li class="close-btn"><a href="javascript:void(0);"><i class="icon-cross-out"></i></a></li>
							</ul>
							<input type="hidden" name="wp_dp_listing_featured_image_id" value="' . $listing_featured_image . '">
						</div>
					</div>
				</li>';
            }
            if ( $listing_featured_image != '' ) {
                $placeholder_style = ' style="display: none;"';
            }
            $attacment_placeholder = '
			<div' . $placeholder_style . ' id="featured-placeholder-' . $listing_add_counter . '" class="update-attachment">
				<div class="img-holder">
					<figure>
						<img src="' . wp_dp::plugin_url() . 'assets/frontend/images/upload-attach-img.jpg" alt="" />
					</figure>
				</div>
				<div class="text">
					<h3>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_attachment') . '</h3>
					<p>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_image_formats') . '</p>
				</div>
			</div>';

            $html .= '
			<li class="wp-dp-dev-appended">
			<div class="row featured-image-holder">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="dashboard-element-title">
						<strong>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_feature_image') . '</strong>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="upload-gallery">
                        <div class="field-holder">
                            <label style="display:none;">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_feature_image') . '</label>
							<input id="featured-image-uploader-' . $listing_add_counter . '" class="wp-dp-dev-gallery-uploader" style="display:none;" type="file" name="wp_dp_listing_featured_image[]" onchange="wp_dp_handle_file_single_select(event, \'' . $listing_add_counter . '\')">
							<a href="javascript:void(0);" class="upload-btn wp-dp-dev-featured-upload-btn" data-id="' . $listing_add_counter . '"><i class="icon-upload6"></i>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_upload_feature_image') . '</a>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="field-holder">
						' . $attacment_placeholder . '
						<ul id="wp-dp-dev-featured-img-' . $listing_add_counter . '" class="wp-dp-gallery-holder">' . $listing_added_featured_image . '</ul>
					</div>
				</div>
			</div>
			</li>';
            return apply_filters('wp_dp_front_listing_add_featured_image', $html, $type_id, $wp_dp_id);
            // usage :: add_filter('wp_dp_front_listing_add_featured_image', 'my_callback_function', 10, 3);
        }

        /**
         * Listing Tags
         * @return markup
         */
        public function listing_tags($type_id = '', $wp_dp_id = '') {
            global $listing_add_counter;

            $html = '';
            // enqueue required script
            wp_enqueue_script('jquery-ui');
            wp_enqueue_script('wp-dp-tags-it');
            $select_listing_type = wp_dp_get_input('select_type', '');
            if ( $select_listing_type != '' ) {
                $post = get_page_by_path($select_listing_type, OBJECT, 'listing-type');
                $type_id = $post->ID;
            } else {
                $type_id = $type_id;
            }
            $type_id = wp_dp_wpml_lang_page_id($type_id, 'listing-type');
            $wp_dp_listing_type_tags = get_post_meta($type_id, 'wp_dp_listing_type_tags', true);

            $wp_dp_tags_list = '';

            // In case of changing wp_dp type ajax
            // it will load the pre filled data
            $get_listing_form_select_type = wp_dp_get_input('select_type', '', 'STRING');
            if ( $get_listing_form_select_type != '' ) {
                $get_listing_form_tags = wp_dp_get_input('wp_dp_tags', '', 'ARRAY');
                if ( is_array($get_listing_form_tags) && sizeof($get_listing_form_tags) > 0 ) {
                    $wp_dp_tags_list = '';
                    foreach ( $get_listing_form_tags as $dir_tag ) {
                        $wp_dp_tags_list .= '<li>' . $dir_tag . '</li>';
                    }
                }
            }
            //

            $wp_dp_listing_tags = get_post_meta($wp_dp_id, 'wp_dp_listing_tags', true);
            if ( is_array($wp_dp_listing_tags) && ! empty($wp_dp_listing_tags) ) {
                $wp_dp_tags_list = '';
                foreach ( $wp_dp_listing_tags as $wp_dp_listing_tag ) {
                    $wp_dp_tags_list .= '<li>' . $wp_dp_listing_tag . '</li>';
                }
            }

            $html .= '<li class="wp-dp-dev-appended">';
            $html .= '<div class="row">';
            $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            $html .= '<div class="dashboard-element-title">';
            $html .= '<strong>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_tags_cloud') . '</strong>';
            $html .= '</div>';
            $html .= '<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery(\'#wp-dp-tags\').tagit({
						allowSpaces: true,
						fieldName : \'wp_dp_tags[]\'
					});
				});
			</script>';
            $html .= '<ul id="wp-dp-tags">';
            $html .= $wp_dp_tags_list;
            $html .= '</ul>';
            if ( is_array($wp_dp_listing_type_tags) && ! empty($wp_dp_listing_type_tags) ) {
                $html .= '<div class="dashboard-element-title">';
                $html .= '<h5>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_suggested_tags') . '</h5>';
                $html .= '</div>';
                $html .= '<ul class="tag-cloud-container" id="tag-cloud">';
                foreach ( $wp_dp_listing_type_tags as $wp_dp_listing_type_tag ) {
                    $term = get_term_by('slug', $wp_dp_listing_type_tag, 'listing-tag');
                    if ( is_object($term) ) {
                        $html .= '<li class="tag-cloud" onclick="jQuery(\'#wp-dp-tags\').tagit(\'createTag\', \'' . $term->name . '\');return false;">' . $term->name . '</li>';
                    }
                }
                $html .= '</ul>';
            }
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</li>';



            return apply_filters('wp_dp_front_listing_add_tags', $html, $type_id, $wp_dp_id);
            // usage :: add_filter('wp_dp_front_listing_add_tags', 'my_callback_function', 10, 3);
        }

        /**
         * Listing Categories
         * @return markup
         */
        public function listing_categories($type_id = '', $wp_dp_id = '') {
            global $listing_add_counter, $wp_dp_form_fields, $wp_dp_listing_meta;

            $html = '';
            $listing_type_post = get_post($type_id);
            $listing_type_slug = isset($listing_type_post->post_name) ? $listing_type_post->post_name : 0;

            $html .= '<li class="wp-dp-dev-appended wp-dp-dev-appended-cats">';
            $html .= '<div class="create-listings-cats">';
            $html .= $wp_dp_listing_meta->listing_categories($listing_type_slug, $wp_dp_id, $backend = false);
            $html .= '</div>';
            $html .= '</li>';


            return apply_filters('wp_dp_front_listing_add_categories', $html, $type_id, $wp_dp_id);
            // usage :: add_filter('wp_dp_front_listing_add_categories', 'my_callback_function', 10, 3);
        }

        /*
         * apartment Add and append code
         */

        public function listing_apartment($type_id = '', $wp_dp_id = '') {

            global $listing_add_counter, $wp_dp_plugin_options;

            $html = '';
            $wp_dp_listing_apartment = get_post_meta($type_id, 'wp_dp_apartment_options_element', true);
            $wp_dp_listing_apartment = 'on';
            if ( $wp_dp_listing_apartment == 'on' ) {

                $rand_id = rand(100000000, 999999999);
                $apartment_list = '';
                // In case of changing wp_dp type ajax
                // it will load the pre filled data
                $get_listing_form_select_type = wp_dp_get_input('select_type', '', 'STRING');
                if ( $get_listing_form_select_type != '' ) {
                    $wp_dp_listing_apartment_plot = wp_dp_get_input('wp_dp_listing_apartment_plot', '', 'ARRAY');
                    $wp_dp_listing_apartment_beds = wp_dp_get_input('wp_dp_listing_apartment_beds', '', 'ARRAY');
                    $wp_dp_listing_apartment_price_from = wp_dp_get_input('wp_dp_listing_apartment_price_from', '', 'ARRAY');
                    $wp_dp_listing_apartment_floor = wp_dp_get_input('wp_dp_listing_apartment_floor', '', 'ARRAY');
                    $wp_dp_listing_apartment_address = wp_dp_get_input('wp_dp_listing_apartment_address', '', 'ARRAY');
                    $wp_dp_listing_apartment_availability = wp_dp_get_input('wp_dp_listing_apartment_availability', '', 'ARRAY');
                    $wp_dp_listing_apartment_link = wp_dp_get_input('wp_dp_listing_apartment_link', '', 'ARRAY');
                    $form_apartment_array = array();
                    if ( is_array($wp_dp_listing_apartment_plot) && sizeof($wp_dp_listing_apartment_plot) > 0 ) {
                        foreach ( $wp_dp_listing_apartment_plot as $key => $apartment ) {
                            if ( count($apartment) > 0 ) {
                                $form_apartment_array[] = array(
                                    'apartment_plot' => $apartment,
                                    'apartment_beds' => isset($wp_dp_listing_apartment_beds[$key]) ? $wp_dp_listing_apartment_beds[$key] : '',
                                    'apartment_price_from' => isset($wp_dp_listing_apartment_price_from[$key]) ? $wp_dp_listing_apartment_price_from[$key] : '',
                                    'apartment_floor' => isset($wp_dp_listing_apartment_floor[$key]) ? $wp_dp_listing_apartment_floor[$key] : '',
                                    'apartment_address' => isset($wp_dp_listing_apartment_address[$key]) ? $wp_dp_listing_apartment_address[$key] : '',
                                    'apartment_availability' => isset($wp_dp_listing_apartment_availability[$key]) ? $wp_dp_listing_apartment_availability[$key] : '',
                                    'apartment_link' => isset($wp_dp_listing_apartment_link[$key]) ? $wp_dp_listing_apartment_link[$key] : '',
                                );
                            }
                        }
                    }
                    if ( sizeof($form_apartment_array) > 0 ) {
                        foreach ( $form_apartment_array as $get_apartment ) {
                            $apartment_list .= $this->append_to_apartment_list($get_apartment);
                        }
                    }
                }
                // end ajax load

                $get_listing_id = wp_dp_get_input('listing_id', 0);
                if ( $get_listing_id != '' && $get_listing_id != 0 ) {
                    $get_listing_apartment = get_post_meta($get_listing_id, 'wp_dp_apartment', true);
                    if ( is_array($get_listing_apartment) && sizeof($get_listing_apartment) ) {
                        foreach ( $get_listing_apartment as $get_apartment ) {
                            $apartment_list .= $this->append_to_apartment_list($get_apartment);
                        }
                    }
                }
                if ( $apartment_list == '' ) {
                    $apartment_list = '<li id="no-apartment-' . $listing_add_counter . '" class="no-result-msg">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_no_apartment_add') . '</li>';
                }

                $html .= '
				<li class="wp-dp-dev-appended">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="dashboard-element-title">
							<strong>' . wp_dp_plugin_text_srt('wp_dp_member_add_apartment') . '</strong>
							<a id="wp-dp-dev-insert-apartment-' . $listing_add_counter . '" data-id="' . $listing_add_counter . '" class="add-service add-apartment" href="javascript:void(0);">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_add_apartment') . '</a>
							<div id="dev-apartment-loader-' . $listing_add_counter . '" class="listing-loader"></div>
						</div>
					</div>
					<div id="wp-dp-dev-insert-apartment-con-' . $listing_add_counter . '" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: none;">
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<div class="field-holder">
									<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_plots') . ' *</label>
									<input class="apartment-plot" type="text" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_plots') . '">
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<div class="field-holder">
									<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_beds') . '</label>
									<input class="apartment-beds" type="text" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_beds') . '">
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<div class="field-holder">
									<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_price_from') . '</label>
									<input class="apartment-price-from" type="text" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_price_from') . '">
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<div class="field-holder">
									<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_floor') . '</label>
									<input class="apartment-floor" type="text" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_floor') . '">
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<div class="field-holder">
									<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_address') . '</label>
									<input class="apartment-address" type="text" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_address') . '">
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<div class="field-holder">
									<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_availbility') . '</label>
									 <select class="apartment-availability chosen-select-no-single">
									    <option value="available">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_availble') . '</option>
									    <option value="unavailable">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_not_availble') . '</option>
									 </select>   
								</div>
							</div>
							
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="field-holder">
									<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_link') . '</label>
									<input class="apartment-link" type="text" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_link') . '">    
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="field-holder">
									<label>&nbsp;</label>
									<a id="wp-dp-dev-add-apartment-' . $listing_add_counter . '" data-id="' . $listing_add_counter . '" href="javascript:void(0);" class="add-apartment add-service add-apartment-list add-service-list">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_service_add_to_list') . '</a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="field-holder">
							<div class="apartment-list service-list">
								<ul id="wp-dp-dev-add-apartment-app-' . $listing_add_counter . '">
									' . $apartment_list . '
								</ul>
							</div>
						</div>
					</div>
				</div>
				</li>';
            }

            return apply_filters('wp_dp_front_listing_add_apartment', $html, $type_id, $wp_dp_id);
            // usage :: add_filter('wp_dp_front_listing_add_apartment', 'my_callback_function', 10, 3);
        }

        public function append_to_apartment_list($get_apartment = '') {
            global $wp_dp_plugin_options;


            if ( is_array($get_apartment) && sizeof($get_apartment) > 0 ) {
                $plot_field = isset($get_apartment['apartment_plot']) ? $get_apartment['apartment_plot'] : '';
                $beds_field = isset($get_apartment['apartment_beds']) ? $get_apartment['apartment_beds'] : '';
                $price_from_field = isset($get_apartment['apartment_price_from']) ? $get_apartment['apartment_price_from'] : '';
                $floor_field = isset($get_apartment['apartment_floor']) ? $get_apartment['apartment_floor'] : '';
                $address_field = isset($get_apartment['apartment_address']) ? $get_apartment['apartment_address'] : '';
                $availability_field = isset($get_apartment['apartment_availability']) ? $get_apartment['apartment_availability'] : '';
                $link_field = isset($get_apartment['apartment_link']) ? $get_apartment['apartment_link'] : '';
            } else {
                $plot_field = wp_dp_get_input('plot_field', '', 'STRING');
                $beds_field = wp_dp_get_input('beds_field', '', 'STRING');
                $price_from_field = wp_dp_get_input('price_from_field', '', 'STRING');
                $floor_field = wp_dp_get_input('floor_field', '', 'STRING');
                $address_field = wp_dp_get_input('address_field', '', 'STRING');
                $availability_field = wp_dp_get_input('availability_field', '', 'STRING');
                $link_field = wp_dp_get_input('link_field', '', 'STRING');
            }

            $plot_field = isset($plot_field) ? $plot_field : '';
            $beds_field = isset($beds_field) ? $beds_field : '';
            $price_from_field = isset($price_from_field) ? $price_from_field : '';
            $floor_field = isset($floor_field) ? $floor_field : '';
            $address_field = isset($address_field) ? $address_field : '';
            $availability_field = isset($availability_field) ? $availability_field : '';
            $link_field = isset($link_field) ? $link_field : '';
            $available_select = '';
            $unavailable_select = '';
            $availability_fieldd = '';
            if ( $availability_field == 'available' ) {
                $available_select = ' selected ';
            } elseif ( $availability_field == 'unavailable' ) {
                $unavailable_select = ' selected ';
                $availability_fieldd = 'Not Available';
            }
            if ( isset($price_from_field) && $price_from_field != '' ) {
                $price_from_fieldd = wp_dp_get_currency_sign() . $price_from_field;
            }
            $rand_numb = rand(100000000, 999999999);
            $html = '
			<li class="alert">
				<div class="drag-list">
					<span class="drag-option"><i class="icon-bars"></i></span>
					<div id="apartment-plot-' . $rand_numb . '" class="list-plot">
						<h6>' . $plot_field . '</h6>
					</div>
					<div id="apartment-beds-' . $rand_numb . '" class="list-beds">
						<h6>' . $beds_field . '</h6>
					</div>
					<div id="apartment-price-' . $rand_numb . '" class="list-price">
						<h6>' . $price_from_fieldd . '</h6>
					</div>
					<div class="list-option">
						<a id="wp-dp-dev-apartment-edit-' . $rand_numb . '" data-id="' . $rand_numb . '" href="javascript:void(0);" class="edit">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_edit') . '</a>
						<a href="#" class="close" data-dismiss="alert"><i class="icon-cross-out"></i></a>
					</div>
				</div>
				
				<div id="wp-dp-dev-apartment-edit-con-' . $rand_numb . '" class="info-holder" style="display: none;">
					<a href="javascript:void(0);" class="remove-this-apartment"><i class="icon-close"></i></a>
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="field-holder">
								<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_plots') . ' *</label>
								<input class="apartment-plot" type="text" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_plots') . '" value="' . $plot_field . '" name="wp_dp_listing_apartment_plot[]">
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="field-holder">
								<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_beds') . '</label>
								<input class="apartment-beds" type="text" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_beds') . '" value="' . $beds_field . '" name="wp_dp_listing_apartment_beds[]">
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="field-holder">
								<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_price_from') . '</label>
								<input class="apartment-price-from" type="text" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_price_from') . '" value="' . $price_from_field . '" name="wp_dp_listing_apartment_price_from[]">
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="field-holder">
								<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_floor') . '</label>
								<input class="apartment-floor" type="text" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_floor') . '" value="' . $floor_field . '" name="wp_dp_listing_apartment_floor[]">
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="field-holder">
								<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_address') . '</label>
								<input class="apartment-address" type="text" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_address') . '" value="' . $address_field . '" name="wp_dp_listing_apartment_address[]">
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="field-holder">
								<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_availbility') . '</label>
								 <select class="apartment-availability chosen-select-no-single" name="wp_dp_listing_apartment_availability[]">
									<option ' . $available_select . ' value="available">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_availble') . '</option>
									<option ' . $unavailable_select . ' value="unavailable">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_not_availble') . '</option>
								 </select>   
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="field-holder">
								<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_link') . '</label>
								<input class="apartment-link" type="text" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_apartment_link') . '" value="' . $link_field . '" name="wp_dp_listing_apartment_link[]">    
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="field-holder">
								<label>&nbsp;</label>
								<a id="wp-dp-dev-apartment-save-' . $rand_numb . '" data-id="' . $rand_numb . '" href="javascript:void(0);" class="add-apartment">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_save') . '</a>
							</div>
						</div>
					</div>
				</div>
			</li>';

            if ( is_array($get_apartment) && sizeof($get_apartment) > 0 ) {
                return apply_filters('wp_dp_front_listing_add_single_apartment', $html, $get_apartment);
                // usage :: add_filter('wp_dp_front_listing_add_single_floor_plan', 'my_callback_function', 10, 2);
            } else {
                echo json_encode(array( 'html' => $html ));
                die;
            }
        }

        /*
         * End apartment Add and append code
         */

        /**
         * Appending floor plans to list via Ajax
         * @return markup
         */
        public function append_to_floor_plans_list($get_floor_plan = array()) {
            global $wp_dp_plugin_options;

            if ( is_array($get_floor_plan) && sizeof($get_floor_plan) > 0 ) {
                $title_field = isset($get_floor_plan['floor_plan_title']) ? $get_floor_plan['floor_plan_title'] : '';
                $desc_field = isset($get_floor_plan['floor_plan_description']) ? $get_floor_plan['floor_plan_description'] : '';
                $image_id = isset($get_floor_plan['floor_plan_image']) ? $get_floor_plan['floor_plan_image'] : '';
                $thumb_url = wp_get_attachment_url($image_id);
            } else {
                $title_field = wp_dp_get_input('title_field', '', 'STRING');
                $image_id = 0;
                $thumb_url = '';
                $desc_field = wp_dp_get_input('desc_field', '', 'STRING');
                if ( isset($_FILES[0]) ) {
                    if ( ! function_exists('wp_handle_upload') ) {
                        require_once( ABSPATH . 'wp-admin/includes/file.php' );
                    }

                    $uploadedfile = $_FILES[0];

                    $upload_overrides = array( 'test_form' => false );

                    $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

                    if ( $movefile && ! isset($movefile['error']) ) {

                        // $filename should be the path to a file in the upload wp_dp.
                        $filename = $movefile['file'];
                        $thumb_url = $movefile['url'];

                        // The ID of the post this attachment is for.
                        $parent_post_id = 0;

                        // Check the type of file. We'll use this as the 'post_mime_type'.
                        $filetype = wp_check_filetype(basename($filename), null);

                        // Get the path to the upload wp_dp.
                        $wp_upload_dir = wp_upload_dir();

                        // Prepare an array of post data for the attachment.
                        $attachment = array(
                            'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
                            'post_mime_type' => $filetype['type'],
                            'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                            'post_content' => '',
                            'post_status' => 'inherit'
                        );

                        // Insert the attachment.
                        $attach_id = wp_insert_attachment($attachment, $filename, $parent_post_id);

                        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                        require_once( ABSPATH . 'wp-admin/includes/image.php' );

                        // Generate the metadata for the attachment, and update the database record.
                        $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
                        wp_update_attachment_metadata($attach_id, $attach_data);
                        //var_dump($attach_id);
                        //echo "File is valid, and was successfully uploaded.\n";
                        //var_dump( $movefile );
                        $image_id = $attach_id;
                    } else {
                        /**
                         * Error generated by _wp_handle_upload()
                         * @see _wp_handle_upload() in wp-admin/includes/file.php
                         */
                        echo '';
                    }
                }
            }

            $rand_numb = rand(100000000, 999999999);

            $_image_html = '&nbsp';
            if ( $thumb_url != '' ) {
                $_image_html = '<img src="' . $thumb_url . '">';
            }

            $editor_class = 'wp_dp_editor' . mt_rand();

            $html = '
			<li class="alert">
				<div class="drag-list">
					<span class="drag-option"><i class="icon-bars"></i></span>
					<div id="floor-plan-image-' . $rand_numb . '" class="icon-holder">
						' . $_image_html . '
					</div>
					<div id="floor-plan-title-' . $rand_numb . '" class="list-title">
						<h6>' . $title_field . '</h6>
					</div>
					<div class="list-price">
						<span>&nbsp;</span>
					</div>
					<div class="list-option">
						<a id="wp-dp-dev-floor-plan-edit-' . $rand_numb . '" data-id="' . $rand_numb . '" href="javascript:void(0);" class="edit">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_edit') . '</a>
						<a href="#" class="close" data-dismiss="alert"><i class="icon-cross-out"></i></a>
					</div>
				</div>
				
				<div id="wp-dp-dev-floor-plan-edit-con-' . $rand_numb . '" class="info-holder" style="display: none;">
					<a href="javascript:void(0);" class="remove-this-floor-plan"><i class="icon-cross-out"></i></a>
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="field-holder">
								<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_floor_title') . '</label>
								<input class="floor-plan-title" type="text" name="wp_dp_listing_floor_plan_title[]" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_floor_title') . '" value="' . $title_field . '">
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="field-holder">
								<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_floor_image') . '</label>
								
								<div id="browse-btn-sec-' . $rand_numb . '" class="browse-btn-sec" style="display: bolck !important;">
									<div class="image-holder"><img src="' . $thumb_url . '" alt=""></div>
									<input type="file" id="browse-floor-icon-img-' . $rand_numb . '" class="floor-plan-image" accept="image/*" style="display: none;">
									<input type="hidden" name="wp_dp_listing_floor_plan_image[]" value="' . $image_id . '">
									<a data-id="' . $rand_numb . '" href="javascript:void(0)" class="browse-menu-icon-img browse-floor-icon-img btn bgcolor" data-id="' . $rand_numb . '">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_floor_browse') . '</a>
								</div>

							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="field-holder">
								<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_floor_desc') . '</label>
								<textarea class="floor-plan-desc" name="wp_dp_listing_floor_plan_desc[]" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_floor_desc') . '">' . $desc_field . '</textarea>
							</div>
							<script> jQuery(".wp_dp_editor").jqte({
								"sub": false,
								"sup": false,
								"indent": false,
								"outdent": false,
								"unlink": false,
								"format": false,
								"color": false,
								"left": false,
								"right": false,
								"center": false,
								"strike": false,
								"rule": false,
								"fsize": false,
							});</script>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="field-holder">
								<label>&nbsp;</label>
								<a id="wp-dp-dev-floor-plan-save-' . $rand_numb . '" data-id="' . $rand_numb . '" href="javascript:void(0);" class="add-floor-plan">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_save') . '</a>
							</div>
						</div>
					</div>
				</div>
			</li>';
            if ( is_array($get_floor_plan) && sizeof($get_floor_plan) > 0 ) {
                return apply_filters('wp_dp_front_listing_add_single_floor_plan', $html, $get_floor_plan);
                // usage :: add_filter('wp_dp_front_listing_add_single_floor_plan', 'my_callback_function', 10, 2);
            } else {
                echo json_encode(array( 'html' => $html ));
                die;
            }
        }

        public function append_to_attachments_list($get_attachments = '') {
            global $wp_dp_plugin_options;

            if ( is_array($get_attachments) && sizeof($get_attachments) > 0 ) {
                $title_field = isset($get_attachments['attachment_title']) ? $get_attachments['attachment_title'] : '';
                $image_id = isset($get_attachments['attachment_file']) ? $get_attachments['attachment_file'] : '';
                $allowed_extentions = isset($get_attachments['allowed_extentions']) ? $get_attachments['allowed_extentions'] : '';
                $thumb_url = wp_get_attachment_url($image_id);
            } else {
                $title_field = wp_dp_get_input('title_field', '', 'STRING');
                $allowed_extentions = wp_dp_get_input('allowed_extentions', '', 'STRING');
                $image_id = 0;
                $thumb_url = '';
                $image_id = $this->get_atachment_id_by_file($_FILES);
                if ( $image_id != '' ) {
                    $thumb_url = wp_get_attachment_url($image_id);
                }
            }

            $rand_numb = rand(100000000, 999999999);
            $_image_html = '&nbsp';
            if ( $thumb_url != '' ) {
                $filet_type = wp_check_filetype($thumb_url);
                $filet_type = isset($filet_type['ext']) ? $filet_type['ext'] : '';
                $thumb_url = wp_dp::plugin_url() . '/assets/common/attachment-images/attach-' . $filet_type . '.png';
                $_image_html = '<img src="' . $thumb_url . '">';
            }

            $html = '
			<li class="alert">
				<div class="drag-list">
					<span class="drag-option"><i class="icon-bars"></i></span>
					<div id="attachment-file-' . $rand_numb . '" class="icon-holder">
						' . $_image_html . '
					</div>
					<div id="attachment-title-' . $rand_numb . '" class="list-title">
						<h6>' . $title_field . '</h6>
					</div>
					<div class="list-price">
						<span>&nbsp;</span>
					</div>
					<div class="list-option">
						<a id="wp-dp-dev-attachments-edit-' . $rand_numb . '" data-id="' . $rand_numb . '" href="javascript:void(0);" class="edit">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_edit') . '</a>
						<a href="#" class="close" data-dismiss="alert"><i class="icon-cross-out"></i></a>
					</div>
				</div>
				<div id="wp-dp-dev-attachments-edit-con-' . $rand_numb . '" class="info-holder" style="display: none;">
					<a href="javascript:void(0);" class="remove-this-attachment"><i class="icon-cross-out"></i></a>
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="field-holder">
								<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_attach_title') . '*</label>
								<input class="attachment-title" type="text" name="wp_dp_listing_attachment_title[]" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_add_list_attach_title') . '" value="' . $title_field . '">
							</div>
						</div>
						<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
							<div class="field-holder">
								<label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_attach_file') . '*</label>
								<div id="browse-btn-sec-' . $rand_numb . '" class="browse-btn-sec" style="display: bolck !important;">
									<div class="image-holder attachment-file-viewer-holder"><img class="attached-file-' . $rand_numb . '" src="' . $thumb_url . '"></div>
									<input type="file" id="browse-attach-icon-img-' . $rand_numb . '" class="attachment-file" style="display: none;" data-allowed-extentions="' . esc_html($allowed_extentions) . '">
									<input type="hidden" class="listing-attachment-id" name="wp_dp_listing_attachment_file[]" value="' . $image_id . '">
									<a data-id="' . $rand_numb . '" href="javascript:void(0)" class="browse-menu-icon-img browse-attach-icon-img btn bgcolor" data-id="' . $rand_numb . '">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_attach_browse') . '</a>
								</div>

								<span class="allowed-extensions-' . $rand_numb . '">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_attach_allow_formats') . str_replace(',', ', ', $allowed_extentions) . '</span>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="field-holder">
								<label>&nbsp;</label>
								<a id="wp-dp-dev-attachments-save-' . $rand_numb . '" data-id="' . $rand_numb . '" href="javascript:void(0);" class="add-attachments">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_save') . '</a>
							</div>
						</div>
					</div>
				</div>
			</li>';

            if ( is_array($get_attachments) && sizeof($get_attachments) > 0 ) {
                return apply_filters('wp_dp_front_listing_add_single_attachments', $html, $get_attachments);
                // usage :: add_filter('wp_dp_front_listing_add_single_attachments', 'my_callback_function', 10, 2);
            } else {
                echo json_encode(array( 'html' => $html ));
                die;
            }
        }

        public function get_atachment_id_by_file($files = array(), $ajax_filter = false) {
            $ajax_filter = wp_dp_get_input('ajax_filter', '', 'STRING');
            if ( $ajax_filter == true ) {
                $files = $_FILES;
            }
            $image_id = '';
            if ( isset($files[0]) ) {
                if ( ! function_exists('wp_handle_upload') ) {
                    require_once( ABSPATH . 'wp-admin/includes/file.php' );
                }

                $uploadedfile = $_FILES[0];
                $upload_overrides = array( 'test_form' => false );
                $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

                if ( $movefile && ! isset($movefile['error']) ) {
                    // $filename should be the path to a file in the upload wp_dp.
                    $filename = $movefile['file'];
                    $thumb_url = $movefile['url'];

                    // The ID of the post this attachment is for.
                    $parent_post_id = 0;

                    // Check the type of file. We'll use this as the 'post_mime_type'.
                    $filetype = wp_check_filetype(basename($filename), null);

                    // Get the path to the upload wp_dp.
                    $wp_upload_dir = wp_upload_dir();

                    // Prepare an array of post data for the attachment.
                    $attachment = array(
                        'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
                        'post_mime_type' => $filetype['type'],
                        'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );
                    // Insert the attachment.
                    $attach_id = wp_insert_attachment($attachment, $filename, $parent_post_id);
                    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                    require_once( ABSPATH . 'wp-admin/includes/image.php' );
                    // Generate the metadata for the attachment, and update the database record.
                    $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
                    wp_update_attachment_metadata($attach_id, $attach_data);
                    $image_id = $attach_id;
                }
            }
            if ( $ajax_filter == true ) {
                echo json_encode(array( 'html' => $image_id ));
                die;
            } else {
                return $image_id;
            }
        }

        /**
         * Set Book Days off
         * @return markup
         */
        public function listing_book_days_off($type_id = '', $wp_dp_id = '') {
            global $listing_add_counter;
            $html = '';
            $off_days_list = '';

            $wp_dp_off_days = get_post_meta($type_id, 'wp_dp_off_days', true);
            if ( $wp_dp_off_days == 'on' ) {
                // In case of changing wp_dp type ajax
                // it will load the pre filled data
                $get_listing_form_select_type = wp_dp_get_input('select_type', '', 'STRING');
                if ( $get_listing_form_select_type != '' ) {
                    $get_listing_form_days_off = wp_dp_get_input('wp_dp_listing_off_days', '', 'ARRAY');
                    if ( is_array($get_listing_form_days_off) && sizeof($get_listing_form_days_off) ) {
                        foreach ( $get_listing_form_days_off as $get_off_day ) {
                            $off_days_list .= $this->append_to_book_days_off($get_off_day);
                        }
                    }
                }
                // end ajax loading

                $get_listing_id = wp_dp_get_input('listing_id', 0);
                if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                    $get_listing_off_days = get_post_meta($get_listing_id, 'wp_dp_calendar', true);
                    if ( is_array($get_listing_off_days) && sizeof($get_listing_off_days) ) {
                        foreach ( $get_listing_off_days as $get_off_day ) {
                            $off_days_list .= $this->append_to_book_days_off($get_off_day);
                        }
                    }
                }
                if ( $off_days_list == '' ) {
                    $off_days_list = '<li id="no-book-day-' . $listing_add_counter . '" class="no-result-msg">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_no_off_days') . '</li>';
                }

                wp_enqueue_script('responsive-calendar');


                $html .= '<li class="wp-dp-dev-appended">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="dashboard-element-title">
							<strong>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_off_days') . '</strong>
							<div id="dev-off-day-loader-' . $listing_add_counter . '" class="listing-loader"></div>
							<a class="book-btn" href="javascript:void(0);">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_off_days') . '</a>
							<div id="wp-dp-dev-cal-holder-' . $listing_add_counter . '" class="calendar-holder">
								<div data-id="' . $listing_add_counter . '" class="wp-dp-dev-insert-off-days responsive-calendar">
									<span class="availability">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_availability') . '</span>
									<div class="controls">
										<a data-go="prev"><div class="btn btn-primary"><i class="icon-angle-left"></i></div></a>
										<strong><span data-head-month></span> <span data-head-year></span></strong>
										<a data-go="next"><div class="btn btn-primary"><i class="icon-angle-right"></i></div></a>
									</div>
									<div class="day-headers">
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_sun') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_mon') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_tue') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_wed') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_thu') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_fri') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_sat') . '</div>
									</div>
									<div class="days wp-dp-dev-calendar-days" data-group="days"></div>
								</div>
							</div>
						</div>
						<script>
						jQuery(window).load(function () {
							jQuery(".responsive-calendar").responsiveCalendar({
								time: "' . date('Y-m') . '",
								monthChangeAnimation: false,
								"' . date('Y-m-d') . '": {
									number: 5,
									url: ""
								}
							});
						});
						</script>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="field-holder">
							<div class="book-list">
								<ul id="wp-dp-dev-add-off-day-app-' . $listing_add_counter . '">
									' . $off_days_list . '
								</ul>
							</div>
						</div>
					</div>
				</div>
				</li>';
            }
            return apply_filters('wp_dp_front_listing_add_book_off_days', $html, $type_id, $wp_dp_id);
            // usage :: add_filter('wp_dp_front_listing_add_book_off_days', 'my_callback_function', 10, 3);
        }

        /**
         * Appending off days to list via Ajax
         * @return markup
         */
        public function append_to_book_days_off($get_off_day = '') {

            if ( $get_off_day != '' ) {
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
					<div class="time-sec"><em>' . $formated_off_date . '<em>
						<a id="wp-dp-dev-day-off-dp-' . $rand_numb . '" data-id="' . $rand_numb . '" href="javascript:void(0);">' . wp_dp_plugin_text_srt('wp_dp_services_remove') . '</a>
					</div>
				</div>
			</li>';

            if ( $get_off_day != '' ) {
                return apply_filters('wp_dp_front_listing_add_single_off_day', $html, $get_off_day);
                // usage :: add_filter('wp_dp_front_listing_add_single_off_day', 'my_callback_function', 10, 2);
            } else {
                echo json_encode(array( 'html' => $html ));
                die;
            }
        }

        /**
         * Opening Hours
         * @return markup
         */
        public function listing_opening_hours($type_id = '', $wp_dp_id = '') {
            global $listing_add_counter;
            $html = '';
            $wp_dp_listing_opening_hours = get_post_meta($type_id, 'wp_dp_opening_hours_element', true);
            if ( $wp_dp_listing_opening_hours == 'on' ) {
                $time_list = $this->listing_time_list($type_id);
                $week_days = $this->listing_week_days();

                $time_from_html = '';
                $time_to_html = '';

                // In case of changing wp_dp type ajax
                // it will load the pre filled data
                $get_listing_form_select_type = wp_dp_get_input('select_type', '', 'STRING');
                if ( $get_listing_form_select_type != '' ) {
                    $get_opening_hours = wp_dp_get_input('wp_dp_opening_hour', '', 'ARRAY');
                }
                // end ajax loading

                $get_listing_id = wp_dp_get_input('listing_id', 0);
                if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                    $get_opening_hours = get_post_meta($get_listing_id, 'wp_dp_opening_hours', true);
                } else {
                    if ( is_array($time_list) && sizeof($time_list) > 0 ) {
                        foreach ( $time_list as $time_key => $time_val ) {
                            $time_from_html .= '<option value="' . $time_key . '">' . $time_val . '</option>' . "\n";
                            $time_to_html .= '<option value="' . $time_key . '">' . $time_val . '</option>' . "\n";
                        }
                    }
                }

                $days_html = '';
                if ( is_array($week_days) && sizeof($week_days) > 0 ) {
                    foreach ( $week_days as $day_key => $week_day ) {

                        $day_status = isset($get_opening_hours[$day_key]['day_status']) ? $get_opening_hours[$day_key]['day_status'] : '';
                        if ( isset($get_opening_hours) && is_array($get_opening_hours) && sizeof($get_opening_hours) > 0 ) {

                            $opening_time = isset($get_opening_hours[$day_key]['opening_time']) ? $get_opening_hours[$day_key]['opening_time'] : '';
                            $closing_time = isset($get_opening_hours[$day_key]['closing_time']) ? $get_opening_hours[$day_key]['closing_time'] : '';

                            if ( is_array($time_list) && sizeof($time_list) > 0 ) {
                                $time_from_html = '';
                                $time_to_html = '';
                                foreach ( $time_list as $time_key => $time_val ) {
                                    $time_from_html .= '<option value="' . $time_key . '"' . ($opening_time == $time_key ? ' selected="selected"' : '') . '>' . date_i18n('g:i a', strtotime($time_val)) . '</option>' . "\n";
                                    $time_to_html .= '<option value="' . $time_key . '"' . ($closing_time == $time_key ? ' selected="selected"' : '') . '>' . date_i18n('g:i a', strtotime($time_val)) . '</option>' . "\n";
                                }
                            }
                        }
                        $days_html .= '
						<li>
							<div id="open-close-con-' . $day_key . '-' . $listing_add_counter . '" class="open-close-time' . (isset($day_status) && $day_status == 'on' ? ' opening-time' : '') . '">
								<div class="day-sec">
									<span>' . $week_day . '</span>
								</div>
								<div class="time-sec">
									<select class="chosen-select " name="wp_dp_opening_hour[' . $day_key . '][opening_time]">
										' . $time_from_html . '
									</select>
									<span class="option-label">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_to') . '</span>
									<select class="chosen-select " name="wp_dp_opening_hour[' . $day_key . '][closing_time]">
										' . $time_to_html . '
									</select>
									<a id="wp-dp-dev-close-time-' . $day_key . '-' . $listing_add_counter . '" href="javascript:void(0);" data-id="' . $listing_add_counter . '" data-day="' . $day_key . '" title="' . c . '"><i class="icon-cross-out"></i></a>
								</div>
								<div class="close-time">
									<a id="wp-dp-dev-open-time-' . $day_key . '-' . $listing_add_counter . '" href="javascript:void(0);" data-id="' . $listing_add_counter . '" data-day="' . $day_key . '">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_closed') . ' <span>(' . wp_dp_plugin_text_srt('wp_dp_member_add_list_click_open_hours') . ')</span></a>
									<input id="wp-dp-dev-open-day-' . $day_key . '-' . $listing_add_counter . '" type="hidden" name="wp_dp_opening_hour[' . $day_key . '][day_status]"' . (isset($day_status) && $day_status == 'on' ? ' value="on"' : '') . '>
								</div>
							</div>
						</li>';
                    }
                }
                $html .= '
				<li class="wp-dp-dev-appended">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="dashboard-element-title">
							<strong>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_open_hours') . '</strong>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="field-holder">
						<div class="time-list">
							<ul>
								' . $days_html . '
							</ul>
						</div>
					</div>
				</div>
				</li>';
            }
            return apply_filters('wp_dp_front_listing_add_open_hours', $html, $type_id, $wp_dp_id);
            // usage :: add_filter('wp_dp_front_listing_add_open_hours', 'my_callback_function', 10, 3);
        }

        /**
         * Load wp_dp Meta Data
         * @return markup
         */
        public function listing_meta_data() {
            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $listing_type = get_post_meta($get_listing_id, 'wp_dp_listing_type', true);
            } else {
                $types_args = array( 'posts_per_page' => '-1', 'post_type' => 'listing-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC' );
                $cust_query = get_posts($types_args);
                $listing_type = isset($cust_query[0]->post_name) ? $cust_query[0]->post_name : '';
            }

            if ( $listing_type != '' ) {

                $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type", 'post_status' => 'publish' ));
                $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
                $html = $this->listing_categories($listing_type_id, $get_listing_id);
                $html .= $this->listing_price($listing_type_id, $get_listing_id);
                $html .= $this->listing_location($listing_type_id, $get_listing_id);
                $html .= $this->listing_contact_information($listing_type_id, $get_listing_id, 'no');
                //$html .= $this->listing_gallery($listing_type_id, $get_listing_id);
                $html .= $this->listing_tags($listing_type_id, $get_listing_id);
                $html .= $this->listing_features_list($listing_type_id, $get_listing_id);
                //$html .= $this->listing_opening_house($listing_type_id, $get_listing_id);
                //$html .= $this->listing_attachments($listing_type_id, $get_listing_id);
                //$html .= $this->listing_floor_plans($listing_type_id, $get_listing_id);
                $html .= $this->listing_listing_video_image($listing_type_id, $get_listing_id);
                $html .= $this->listing_apartment($listing_type_id, $get_listing_id);
                echo force_balance_tags($html);
            }
        }

        /**
         * Load Subscribed Packages
         * @return markup
         */
        public function listing_user_subscribed_packages() {
            global $listing_add_counter, $wp_dp_plugin_options;
            $html = '';
            $pkg_options = '';
            $wp_dp_currency_sign = wp_dp_get_currency_sign();

            $atcive_pkgs = $this->user_all_active_pkgs();
            if ( is_array($atcive_pkgs) && sizeof($atcive_pkgs) > 0 ) {
                $pkgs_counter = 1;
                $html .= '<div class="all-pckgs-sec">';
                foreach ( $atcive_pkgs as $atcive_pkg ) {

                    $package_id = get_post_meta($atcive_pkg, 'wp_dp_transaction_package', true);
                    $package_price = get_post_meta($atcive_pkg, 'wp_dp_transaction_amount', true);
                    $package_title = $package_id != '' ? get_the_title($package_id) : '';
                    $pkg_options .= '<div class="wp-dp-pkg-holder">';
                    $pkg_options .= '<div class="wp-dp-pkg-header">';
                    $pkg_options .= '
					<div class="pkg-title-price pull-left">
						<label class="pkg-title">' . $package_title . '</label>
						<span class="pkg-price">' . sprintf(wp_dp_plugin_text_srt('wp_dp_member_add_list_price_var'), wp_dp_get_currency($package_price, true)) . '</span>
					</div>
					<div class="pkg-detail-btn pull-right">
						<input type="radio" id="package-' . $package_id . 'pt_' . $atcive_pkg . '" name="wp_dp_listing_active_package" value="' . $package_id . 'pt_' . $atcive_pkg . '">
						<a href="javascript:void(0);" class="wp-dp-dev-detail-pkg" data-id="' . $package_id . 'pt_' . $atcive_pkg . '">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_detail') . '</a>
					</div>';
                    $pkg_options .= '</div>';
                    $pkg_options .= $this->subs_package_info($package_id, $atcive_pkg);
                    $pkg_options .= '</div>';
                    $pkgs_counter ++;
                }

                $html .= $pkg_options;
                $html .= '</div>';
            }

            return apply_filters('wp_dp_listing_add_subscribed_packages', $html);
            // usage :: add_filter('wp_dp_listing_add_subscribed_packages', 'my_callback_function', 10, 1);
        }

        /**
         * Load Packages and Payment
         * @return markup
         */
        public function listing_packages() {
            global $wp_dp_plugin_options, $listing_add_counter;

            $html = '';

            $listing_up_visi = 'block';
            $listing_hide_btn = 'none';

            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $listing_up_visi = 'none';
                $listing_hide_btn = 'inline-block';
            }

            $show_li = false;
            $show_pgt = false;

            $wp_dp_free_listings_switch = isset($wp_dp_plugin_options['wp_dp_free_listings_switch']) ? $wp_dp_plugin_options['wp_dp_free_listings_switch'] : '';
            $wp_dp_currency_sign = isset($wp_dp_plugin_options['wp_dp_currency_sign']) ? $wp_dp_plugin_options['wp_dp_currency_sign'] : '$';

            if ( $wp_dp_free_listings_switch != 'on' ) {

                // subscribed packages list
                $subscribed_active_pkgs = $this->listing_user_subscribed_packages();

                if ( isset($_GET['package_id']) && $_GET['package_id'] != '' ) {
                    $subscribed_active_pkgs = '';
                    $buying_pkg_id = $_GET['package_id'];
                }
                $new_pkg_btn_visibility = 'none';
                $new_pkgs_visibility = 'block';
                if ( $subscribed_active_pkgs ) {
                    $new_pkg_btn_visibility = 'block';
                    $new_pkgs_visibility = 'none';
                }

                // Packages
                $packages_list = '';
                $args = array( 'posts_per_page' => '-1', 'post_type' => 'packages', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC' );
                $cust_query = get_posts($args);
                if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                    $opts_counter = 1;
                    $packages_list_opts = '<div class="all-pckgs-sec">';
                    foreach ( $cust_query as $package_post ) {
                        if ( isset($package_post->ID) ) {
                            $show_li = true;
                            $packg_title = $package_post->ID != '' ? get_the_title($package_post->ID) : '';
                            $package_type = get_post_meta($package_post->ID, 'wp_dp_package_type', true);
                            $package_price = get_post_meta($package_post->ID, 'wp_dp_package_price', true);
                            $pckg_color = '';
                            if ( isset($buying_pkg_id) && $buying_pkg_id == $package_post->ID ) {
                                $pckg_color = ' style="background-color: #b7b7b7;"';
                            }
                            $packages_list_opts .= '<div class="wp-dp-pkg-holder">';
                            $packages_list_opts .= '<div class="wp-dp-pkg-header"' . $pckg_color . '>';
                            $packages_list_opts .= '
							<div class="pkg-title-price pull-left">
								<label class="pkg-title">' . $packg_title . '</label>
								<span class="pkg-price">' . sprintf(wp_dp_plugin_text_srt('wp_dp_member_add_list_price_var'), $wp_dp_currency_sign . WP_DP_FUNCTIONS()->num_format($package_price)) . '</span>
							</div>
							<div class="pkg-detail-btn pull-right">
								<input  type="radio" id="package-' . $package_post->ID . '" name="wp_dp_listing_package"' . (isset($buying_pkg_id) && $buying_pkg_id == $package_post->ID ? ' checked="checked"' : '') . ' value="' . $package_post->ID . '">
								<a href="javascript:void(0);" class="wp-dp-dev-detail-pkg" data-id="' . $package_post->ID . '">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_detail') . '</a>
							</div>';
                            $packages_list_opts .= '</div>';
                            $packages_list_opts .= $this->new_package_info($package_post->ID);
                            $packages_list_opts .= '</div>';
                            $opts_counter ++;
                        }
                    }
                    $packages_list_opts .= '</div>';

                    $packages_list .= '<div class="packages-main-holder">';

                    if ( $subscribed_active_pkgs ) {
                        $packages_list .= '
						<div id="purchased-package-head-' . $listing_add_counter . '" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
							<div class="dashboard-element-title">
								<strong>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_purchased_pkgs') . '</strong>
							</div>
						</div>';
                    }

                    $packages_list .= '
					<div id="buy-package-head-' . $listing_add_counter . '" style="display:' . $new_pkgs_visibility . ';" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
						<div class="dashboard-element-title">
							<strong>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_buy_pkg') . '</strong>
						</div>
					</div>';
                    if ( ! is_user_logged_in() ) {
                        $packages_list .= '<input type="checkbox" checked="checked" style="display:none;" name="wp_dp_listing_new_package_used">';
                    }
                    if ( true === Wp_dp_Member_Permissions::check_permissions('packages') ) {
                        if ( $subscribed_active_pkgs ) {
                            $packages_list .= '
							<div class="buy-new-pakg-actions">
								<input type="checkbox" style="display:none;" id="wp-dp-dev-new-pkg-checkbox-' . $listing_add_counter . '" name="wp_dp_listing_new_package_used">
								<label for="new-pkg-btn-' . $listing_add_counter . '">
									<a id="wp-dp-dev-new-pkg-btn-' . $listing_add_counter . '" class="dir-switch-packges-btn" data-id="' . $listing_add_counter . '" href="javascript:void(0);">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_buy_new_pkg') . '</a>
								</label>
								<a data-id="' . $listing_add_counter . '" style="display:' . $listing_hide_btn . ';" href="javascript:void(0);" class="wp-dp-dev-cancel-pkg"><i class="icon-cross-out"></i></a>
							</div>';
                        } else {
                            $packages_list .= '<input type="checkbox" checked="checked" style="display:none;" name="wp_dp_listing_new_package_used">';
                            $packages_list .= '
							<div class="buy-new-pakg-actions" style="display:' . $listing_hide_btn . ';">
								<a data-id="' . $listing_add_counter . '" href="javascript:void(0);" class="wp-dp-dev-cancel-pkg"><i class="icon-cross-out"></i></a>
							</div>';
                        }
                    }

                    $packages_list .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                    if ( $subscribed_active_pkgs ) {
                        $packages_list .= '<div id="purchased-packages-' . $listing_add_counter . '" class="dir-purchased-packages">' . $subscribed_active_pkgs . '</div>';
                    }
                    $packages_list .= '<div id="new-packages-' . $listing_add_counter . '" style="display:' . $new_pkgs_visibility . ';" class="dir-new-packages">' . $packages_list_opts . '</div>';
                    $packages_list .= '</div>';
                    $packages_list .= '</div>';
                }
            }

            if ( $show_li ) {
                $html .= '
				<li id="listing-packages-sec-' . $listing_add_counter . '" style="display: ' . $listing_up_visi . ';">
					<div class="row">
						' . $packages_list . '
					</div>
				</li>';
            }
            echo force_balance_tags($html);
        }

        /**
         * Select WP Directorybox Manager Featured
         * and Top Category
         * @return markup
         */
        public function listing_featured_top_cat($pckg_id = '', $trans_id = '') {
            global $listing_add_counter;

            $html = '';
            $listing_featured = '';
            $listing_top_cat = '';

            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $listing_featured = get_post_meta($get_listing_id, 'wp_dp_listing_is_featured', true);
                $listing_top_cat = get_post_meta($get_listing_id, 'wp_dp_listing_is_top_cat', true);
            }

            $featured_num = 0;
            $top_cat_num = 0;
            if ( $pckg_id != '' && $trans_id == '' ) {
                $packg_data = get_post_meta($pckg_id, 'wp_dp_package_data', true);
                $featured_num = isset($packg_data['number_of_featured_listings']['value']) ? $packg_data['number_of_featured_listings']['value'] : '';
                $top_cat_num = isset($packg_data['number_of_top_cat_listings']['value']) ? $packg_data['number_of_top_cat_listings']['value'] : '';
            } else if ( $pckg_id != '' && $trans_id != '' ) {
                if ( $user_package = $this->get_user_package_trans($pckg_id, $trans_id) ) {

                    $featured_num = get_post_meta($trans_id, 'wp_dp_transaction_listing_feature_list', true);

                    $top_cat_num = get_post_meta($trans_id, 'wp_dp_transaction_listing_top_cat_list', true);
                }
            }

            if ( $featured_num != 'on' && $top_cat_num != 'on' ) {
                return apply_filters('wp_dp_listing_add_featured_top_cat', $html, $pckg_id, $trans_id);
                // usage :: add_filter('wp_dp_listing_add_featured_top_cat', 'my_callback_function', 10, 3);
            }

            $html .= '
			<div class="dev-listing-featured-top-cat col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="field-holder">';

            if ( $featured_num == 'on' ) {
                $html .= '
					<input id="wp_dp_listing_featured_' . $listing_add_counter . '" type="checkbox" name="wp_dp_listing_featured"' . ($listing_featured == 'on' ? ' checked="checked"' : '') . '>
					<label for="wp_dp_listing_featured_' . $listing_add_counter . '">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_featured') . '</label>';
            }
            if ( $top_cat_num == 'on' ) {
                $html .= '
				<input id="wp_dp_listing_top_cat_' . $listing_add_counter . '" type="checkbox" name="wp_dp_listing_top_cat"' . ($listing_top_cat == 'on' ? ' checked="checked"' : '') . '>
				<label for="wp_dp_listing_top_cat_' . $listing_add_counter . '">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_top_category') . '</label>';
            }

            $html .= '
				</div>
			</div>';

            return apply_filters('wp_dp_listing_add_featured_top_cat', $html, $pckg_id, $trans_id);
            // usage :: add_filter('wp_dp_listing_add_featured_top_cat', 'my_callback_function', 10, 3);
        }

        /**
         * Terms and Conditions
         * and Submit Button
         * @return markup
         */
        public function listing_submit_button() {
            global $listing_add_counter;
            $check_box = '';
            $get_listing_id = wp_dp_get_input('listing_id', 0);
            $btn_text = wp_dp_plugin_text_srt('wp_dp_member_add_list_proceed');
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $btn_text = wp_dp_plugin_text_srt('wp_dp_member_add_list_update_ad');
            } else {
                $check_box = '
				<div class="checkbox-area">
					<input type="checkbox" id="terms-' . $listing_add_counter . '" class="wp-dp-dev-req-field">
					<label for="terms-' . $listing_add_counter . '">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_accept_terms') . '</label>
				</div>';
            }
            $html = '
			<li>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="field-holder">
							<div class="payment-holder">
								' . $check_box . '
								<div class="add-listing-loader input-button-loader">
									<input type="submit" value="' . $btn_text . '">
								</div>
							</div>
						</div> 
					</div>
				</div>
			</li>';
            echo force_balance_tags($html);
        }

        /**
         * Time List
         * @return array
         */
        public function listing_time_list($type_id = '') {

            $lapse = 15;

            $wp_dp_opening_hours_gap = get_post_meta($type_id, 'wp_dp_opening_hours_time_gap', true);
            if ( isset($wp_dp_opening_hours_gap) && $wp_dp_opening_hours_gap != '' ) {
                $lapse = $wp_dp_opening_hours_gap;
            }

            $date = date("Y/m/d 12:00");
            $time = strtotime('12:00 am');
            $start_time = strtotime($date . ' am');
            $endtime = strtotime(date("Y/m/d h:i a", strtotime('1440 minutes', $start_time)));

            while ( $start_time < $endtime ) {
                $time = date("h:i a", strtotime('+' . $lapse . ' minutes', $time));
                $hours[$time] = $time;
                $time = strtotime($time);
                $start_time = strtotime(date("Y/m/d h:i a", strtotime('+' . $lapse . ' minutes', $start_time)));
            }

            return apply_filters('wp_dp_front_listing_add_time_list', $hours, $type_id);
            // usage :: add_filter('wp_dp_front_listing_add_time_list', 'my_callback_function', 10, 2);
        }

        /**
         * Week Days
         * @return array
         */
        public function listing_week_days() {

            $week_days = array(
                'monday' => wp_dp_plugin_text_srt('wp_dp_member_add_list_monday'),
                'tuesday' => wp_dp_plugin_text_srt('wp_dp_member_add_list_tuesday'),
                'wednesday' => wp_dp_plugin_text_srt('wp_dp_member_add_list_wednesday'),
                'thursday' => wp_dp_plugin_text_srt('wp_dp_member_add_list_thursday'),
                'friday' => wp_dp_plugin_text_srt('wp_dp_member_add_list_friday'),
                'saturday' => wp_dp_plugin_text_srt('wp_dp_member_add_list_saturday'),
                'sunday' => wp_dp_plugin_text_srt('wp_dp_member_add_list_sunday'),
            );

            return apply_filters('wp_dp_front_listing_add_week_days', $week_days);
            // usage :: add_filter('wp_dp_front_listing_add_week_days', 'my_callback_function', 10, 1);
        }

        /**
         * Creating wp_dp listing
         * @return listing id
         */
        public function listing_insert($member_id = '') {
            global $wp_dp_plugin_options, $listing_add_counter;

            $wp_dp_free_listings_switch = isset($wp_dp_plugin_options['wp_dp_free_listings_switch']) ? $wp_dp_plugin_options['wp_dp_free_listings_switch'] : '';

            $member_profile_status = get_post_meta($member_id, 'wp_dp_user_status', true);

            if ( $member_profile_status != 'active' ) {
                $sumbit_msg = wp_dp_plugin_text_srt('wp_dp_member_add_list_profile_not_active');
                $this->listing_submit_msg($sumbit_msg, 'error');
                return false;
            }

            $listing_id = 0;
            $listing_title = isset($_POST['wp_dp_listing_title']) ? $_POST['wp_dp_listing_title'] : '';
            $listing_desc = isset($_POST['wp_dp_listing_desc']) ? $_POST['wp_dp_listing_desc'] : '';
            if ( $listing_title != '' && $listing_desc != '' && $member_id != '' ) {

                $form_rand_numb = isset($_POST['form_rand_id']) ? $_POST['form_rand_id'] : '';
                $form_rand_transient = get_transient('listing_submission_check');

                if ( $form_rand_transient != $form_rand_numb ) {
                    $listing_post = array(
                        'post_title' => wp_strip_all_tags($listing_title),
                        'post_content' => $listing_desc,
                        'post_status' => 'publish',
                        'post_type' => 'listings',
                        'post_date' => current_time('Y/m/d H:i:s', 1)
                    );

                    //insert post
                    $listing_id = wp_insert_post($listing_post);

                    set_transient('listing_submission_check', $form_rand_numb, 60 * 60 * 24 * 30);

                    $user_data = wp_get_current_user();
                    do_action('wp_dp_listing_add_email', $user_data, $listing_id);
                }
            }

            return apply_filters('wp_dp_front_listing_add_create', $listing_id);
            // usage :: add_filter('wp_dp_front_listing_add_create', 'my_callback_function', 10, 1);
        }

        /**
         * Save wp_dp listing
         * @return
         */
        public function listing_meta_save() {
            global $current_user, $listing_add_counter;
            $get_listing_id = wp_dp_get_input('listing_id', 0);
            $is_updating = false;
            $listing_id = '';
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                //listing is for update
                $listing_id = $get_listing_id;
                $is_updating = true;
                $member_id = get_post_meta($listing_id, 'wp_dp_listing_member', true);

                $member_profile_status = get_post_meta($member_id, 'wp_dp_user_status', true);

                if ( $member_profile_status != 'active' ) {
                    $sumbit_msg = wp_dp_plugin_text_srt('wp_dp_member_add_list_profile_not_active');
                    $this->listing_submit_msg($sumbit_msg, 'error');
                    return false;
                }
            } else {
                // Inserting Listing
                if ( $this->is_form_submit() ) {
                    if ( is_user_logged_in() ) {
                        $company_id = wp_dp_company_id_form_user_id($current_user->ID);
                        $member_id = $company_id;
                        $publish_user_id = $current_user->ID;
                        $listing_id = $this->listing_insert($member_id);
                    } else {
                        $member_id = '';
                        $listing_id = '';
                        $get_username = wp_dp_get_input('wp_dp_listing_username', '', 'STRING');
                        $get_useremail = wp_dp_get_input('wp_dp_listing_user_email', '', 'STRING');
                        $reg_array = array(
                            'username' => $get_username,
                            'display_name' => $get_username,
                            'email' => $get_useremail,
                            'id' => $listing_add_counter,
                            'wp_dp_user_role_type' => 'member',
                            'key' => '',
                        );
                        if ( $this->is_form_submit() ) {
                            $member_data = wp_dp_registration_validation('', $reg_array);
                            $member_id = isset($member_data[0]) ? $member_data[0] : '';
                            $publish_user_id = isset($member_data[1]) ? $member_data[1] : '';
                            $listing_id = $this->listing_insert($member_id);
                        }
                    }
                }
            }

            if ( $listing_id != '' && $listing_id != 0 && $this->is_form_submit() ) {

                if ( $is_updating ) {
                    // updating post title and content
                    $wp_dp_listing_title = wp_dp_get_input('wp_dp_listing_title', '', 'STRING');
                    $wp_dp_listing_content = isset($_POST['wp_dp_listing_desc']) ? $_POST['wp_dp_listing_desc'] : '';
                    $listing_post = array(
                        'ID' => $listing_id,
                        'post_title' => $wp_dp_listing_title,
                        'post_content' => $wp_dp_listing_content,
                    );
                    wp_update_post($listing_post);
                }

                if ( ! $is_updating ) {
                    // saving Listing posted date
                    update_post_meta($listing_id, 'wp_dp_listing_posted', strtotime(current_time('d-m-Y', 1)));

                    // saving Listing Member
                    update_post_meta($listing_id, 'wp_dp_listing_member', $member_id);
                    if ( isset($publish_user_id) ) {
                        update_post_meta($listing_id, 'wp_dp_listing_username', $publish_user_id);
                    }
                }

                // Saving Listing Featured Image
                $listing_featured_image_id = '';
                $wp_dp_listing_featured_image_id = isset($_POST['wp_dp_listing_featured_image_id']) ? $_POST['wp_dp_listing_featured_image_id'] : '';
                $listing_featured_image = isset($_FILES['wp_dp_listing_featured_image']) ? $_FILES['wp_dp_listing_featured_image'] : '';
                if ( $wp_dp_listing_featured_image_id != '' ) {
                    $listing_featured_image_id = $wp_dp_listing_featured_image_id;
                } else if ( $listing_featured_image != '' && ! is_numeric($listing_featured_image) && ! empty($listing_featured_image) ) {
                    $gallery_media_upload = $this->listing_gallery_upload('wp_dp_listing_featured_image', $listing_featured_image);
                    $listing_featured_image_id = isset($gallery_media_upload[0]) ? $gallery_media_upload[0] : '';
                }

                // member status
                update_post_meta($listing_id, 'listing_member_status', 'active');

                // Saving video cover image
                $listing_featured_image_id = '';
                $wp_dp_listing_featured_image_id = isset($_POST['wp_dp_listing_image_id']) ? $_POST['wp_dp_listing_image_id'] : '';
                $listing_featured_image = isset($_FILES['wp_dp_listing_image']) ? $_FILES['wp_dp_listing_image'] : '';
                if ( $wp_dp_listing_featured_image_id != '' ) {
                    $listing_featured_image_id = $wp_dp_listing_featured_image_id;
                } else if ( $listing_featured_image != '' && ! is_numeric($listing_featured_image) && ! empty($listing_featured_image) ) {
                    $gallery_media_upload = $this->listing_gallery_upload('wp_dp_listing_image', $listing_featured_image);
                    $listing_featured_image_id = isset($gallery_media_upload[0]) ? $gallery_media_upload[0] : '';
                }
                if ( $listing_featured_image_id != '' && is_numeric($listing_featured_image_id) ) {
                    update_post_meta($listing_id, 'wp_dp_listing_image', $listing_featured_image_id);
                } else {
                    delete_post_thumbnail($listing_id);
                    update_post_meta($listing_id, 'wp_dp_listing_image', '');
                }

                // Saving Listing Gallery
                $listing_gal_array = array();
                if ( isset($_FILES['wp_dp_listing_gallery_images']) && ! empty($_FILES['wp_dp_listing_gallery_images']) ) {
                    $gallery_media_upload = $this->listing_gallery_upload('wp_dp_listing_gallery_images');
                    if ( is_array($gallery_media_upload) ) {
                        $listing_gal_array = array_merge($listing_gal_array, $gallery_media_upload);
                    }
                }
                $wp_dp_listing_gallery_items = wp_dp_get_input('wp_dp_listing_gallery_item', '', 'ARRAY');
                if ( is_array($wp_dp_listing_gallery_items) && sizeof($wp_dp_listing_gallery_items) > 0 ) {
                    $listing_gal_array = array_merge($listing_gal_array, $wp_dp_listing_gallery_items);
                }

                update_post_meta($listing_id, 'wp_dp_detail_page_gallery_ids', $listing_gal_array);

                // updating company id
                $company_id = get_user_meta($member_id, 'wp_dp_company', true);
                update_post_meta($listing_id, 'wp_dp_listing_company', $company_id);

                // saving Listing Type
                $wp_dp_listing_type = wp_dp_get_input('wp_dp_listing_type', '');
                update_post_meta($listing_id, 'wp_dp_listing_type', $wp_dp_listing_type);

                // saving Custom Fields
                // all dynamic fields
                $wp_dp_cus_fields = wp_dp_get_input('wp_dp_cus_field', '', 'ARRAY');
                if ( is_array($wp_dp_cus_fields) && sizeof($wp_dp_cus_fields) > 0 ) {
                    foreach ( $wp_dp_cus_fields as $c_key => $c_val ) {
                        update_post_meta($listing_id, $c_key, $c_val);
                    }
                }

                // price save

                $listing_type_post = get_posts(array( 'fields' => 'ids', 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$wp_dp_listing_type", 'post_status' => 'publish' ));
                $listing_type_id = isset($listing_type_post[0]) && $listing_type_post[0] != '' ? $listing_type_post[0] : 0;
                $wp_dp_listing_type_price = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
                $wp_dp_listing_type_price = isset($wp_dp_listing_type_price) && $wp_dp_listing_type_price != '' ? $wp_dp_listing_type_price : 'off';
                $html = '';
                if ( $wp_dp_listing_type_price == 'on' ) {
                    $wp_dp_listing_price_options = wp_dp_get_input('wp_dp_listing_price_options', 'STRING');
                    $wp_dp_listing_price = wp_dp_get_input('wp_dp_listing_price', 'STRING');

                    update_post_meta($listing_id, 'wp_dp_listing_price_options', $wp_dp_listing_price_options);
                    update_post_meta($listing_id, 'wp_dp_listing_price', $wp_dp_listing_price);
                }
                // end price save

                $listing_cats_formate = 'single';

                $wp_dp_listing_category_array = wp_dp_get_input('wp_dp_listing_category', '', 'ARRAY');
                if ( ! empty($wp_dp_listing_category_array) && is_array($wp_dp_listing_category_array) ) {
                    foreach ( $wp_dp_listing_category_array as $cate_slug => $cat_val ) {

                        if ( $cat_val ) {
                            $term = get_term_by('slug', $cat_val, 'listing-category');

                            if ( isset($term->term_id) ) {
                                $cat_ids = array();
                                $cat_ids[] = $term->term_id;
                                $cat_slugs = $term->slug;
                                wp_set_post_terms($listing_id, $cat_ids, 'listing-category', FALSE);
                            }
                        }
                    }

                    update_post_meta($listing_id, 'wp_dp_listing_category', $wp_dp_listing_category_array);
                }

                // adding listing tags
                $new_pkg_check = wp_dp_get_input('wp_dp_listing_new_package_used', '');
                if ( $new_pkg_check == 'on' ) {
                    $get_package_id = wp_dp_get_input('wp_dp_listing_package', '');
                } else {
                    $active_package_key = wp_dp_get_input('wp_dp_listing_active_package', '');
                    $active_package_key = explode('pt_', $active_package_key);
                    $get_package_id = isset($active_package_key[0]) ? $active_package_key[0] : '';
                }

                if ( $get_package_id == '' ) {
                    $get_package_id = get_post_meta($listing_id, 'wp_dp_listing_package', true);
                }

                $trans_id = $this->listing_trans_id($listing_id);

                if ( $trans_id > 0 && $this->wp_dp_is_pkg_subscribed($get_package_id, $trans_id) ) {
                    $tags_limit = get_post_meta($trans_id, 'wp_dp_transaction_listing_tags_num', true);
                } else {
                    $wp_dp_pckg_data = get_post_meta($get_package_id, 'wp_dp_package_data', true);
                    $tags_limit = isset($wp_dp_pckg_data['number_of_tags']['value']) ? $wp_dp_pckg_data['number_of_tags']['value'] : '';
                }

                $wp_dp_listing_tags = wp_dp_get_input('wp_dp_tags', '', 'ARRAY');
                if ( ! empty($wp_dp_listing_tags) && is_array($wp_dp_listing_tags) ) {
                    if ( $tags_limit && $tags_limit > 0 ) {
                        $wp_dp_listing_tags = array_slice($wp_dp_listing_tags, 0, $tags_limit, true);
                    }
                    wp_set_post_terms($listing_id, $wp_dp_listing_tags, 'listing-tag', FALSE);
                    update_post_meta($listing_id, 'wp_dp_listing_tags', $wp_dp_listing_tags);
                }

                // saving listing features
                $wp_dp_listing_features = wp_dp_get_input('wp_dp_listing_feature', '', 'ARRAY');
                update_post_meta($listing_id, 'wp_dp_listing_feature_list', $wp_dp_listing_features);

                // saving location fields
                $wp_dp_listing_country = wp_dp_get_input('wp_dp_post_loc_country_listing', '', 'STRING');
                $wp_dp_listing_state = wp_dp_get_input('wp_dp_post_loc_state_listing', '', 'STRING');
                $wp_dp_listing_city = wp_dp_get_input('wp_dp_post_loc_city_listing', '', 'STRING');
                $wp_dp_listing_town = wp_dp_get_input('wp_dp_post_loc_town_listing', '', 'STRING');
                $wp_dp_listing_loc_addr = wp_dp_get_input('wp_dp_post_loc_address_listing', '', 'STRING');
                $wp_dp_listing_loc_lat = wp_dp_get_input('wp_dp_post_loc_latitude_listing', '', 'STRING');
                $wp_dp_listing_loc_long = wp_dp_get_input('wp_dp_post_loc_longitude_listing', '', 'STRING');
                $wp_dp_listing_loc_zoom = wp_dp_get_input('wp_dp_post_loc_zoom_listing', '', 'STRING');
                $wp_dp_listing_loc_radius = wp_dp_get_input('wp_dp_loc_radius_listing', '', 'STRING');
                $wp_dp_add_new_loc = wp_dp_get_input('wp_dp_add_new_loc_listing', '', 'STRING');
                $wp_dp_loc_bounds_rest = wp_dp_get_input('wp_dp_loc_bounds_rest_listing', '', 'STRING');

                update_post_meta($listing_id, 'wp_dp_post_loc_country_listing', $wp_dp_listing_country);
                update_post_meta($listing_id, 'wp_dp_post_loc_state_listing', $wp_dp_listing_state);
                update_post_meta($listing_id, 'wp_dp_post_loc_city_listing', $wp_dp_listing_city);
                update_post_meta($listing_id, 'wp_dp_post_loc_town_listing', $wp_dp_listing_town);
                update_post_meta($listing_id, 'wp_dp_post_loc_address_listing', $wp_dp_listing_loc_addr);
                update_post_meta($listing_id, 'wp_dp_post_loc_latitude_listing', $wp_dp_listing_loc_lat);
                update_post_meta($listing_id, 'wp_dp_post_loc_longitude_listing', $wp_dp_listing_loc_long);
                update_post_meta($listing_id, 'wp_dp_post_loc_zoom_listing', $wp_dp_listing_loc_zoom);
                update_post_meta($listing_id, 'wp_dp_loc_radius_listing', $wp_dp_listing_loc_radius);
                update_post_meta($listing_id, 'wp_dp_add_new_loc_listing', $wp_dp_add_new_loc);
                update_post_meta($listing_id, 'wp_dp_loc_bounds_rest_listing', $wp_dp_loc_bounds_rest);





                $wp_dp_listing_floor_plan_title = wp_dp_get_input('wp_dp_listing_floor_plan_title', '', 'ARRAY');
                $wp_dp_listing_floor_plan_image = wp_dp_get_input('wp_dp_listing_floor_plan_image', '', 'ARRAY');
                $wp_dp_listing_floor_plan_desc = wp_dp_get_input('wp_dp_listing_floor_plan_desc', '', 'ARRAY');


                if ( is_array($wp_dp_listing_floor_plan_title) && sizeof($wp_dp_listing_floor_plan_title) > 0 ) {
                    $floor_plans_array = array();
                    foreach ( $wp_dp_listing_floor_plan_title as $key => $floor_plan ) {

                        if ( count($floor_plan) > 0 ) {
                            $floor_plans_array[] = array(
                                'floor_plan_title' => $floor_plan,
                                'floor_plan_description' => isset($wp_dp_listing_floor_plan_desc[$key]) ? $wp_dp_listing_floor_plan_desc[$key] : '',
                                'floor_plan_image' => isset($wp_dp_listing_floor_plan_image[$key]) ? $wp_dp_listing_floor_plan_image[$key] : '',
                            );
                        }
                    }
                    update_post_meta($listing_id, 'wp_dp_floor_plans', $floor_plans_array);
                }

               

                // saving apartment by
                $wp_dp_listing_apartment_plot = wp_dp_get_input('wp_dp_listing_apartment_plot', '', 'ARRAY');
                $wp_dp_listing_apartment_beds = wp_dp_get_input('wp_dp_listing_apartment_beds', '', 'ARRAY');
                $wp_dp_listing_apartment_price_from = wp_dp_get_input('wp_dp_listing_apartment_price_from', '', 'ARRAY');
                $wp_dp_listing_apartment_floor = wp_dp_get_input('wp_dp_listing_apartment_floor', '', 'ARRAY');
                $wp_dp_listing_apartment_address = wp_dp_get_input('wp_dp_listing_apartment_address', '', 'ARRAY');
                $wp_dp_listing_apartment_availability = wp_dp_get_input('wp_dp_listing_apartment_availability', '', 'ARRAY');
                $wp_dp_listing_apartment_link = wp_dp_get_input('wp_dp_listing_apartment_link', '', 'ARRAY');

                if ( is_array($wp_dp_listing_apartment_plot) && sizeof($wp_dp_listing_apartment_plot) > 0 ) {
                    $apartment_array = array();
                    foreach ( $wp_dp_listing_apartment_plot as $key => $apartment ) {
                        if ( count($apartment) > 0 ) {
                            $apartment_array[] = array(
                                'apartment_plot' => $apartment,
                                'apartment_beds' => isset($wp_dp_listing_apartment_beds[$key]) ? $wp_dp_listing_apartment_beds[$key] : '',
                                'apartment_price_from' => isset($wp_dp_listing_apartment_price_from[$key]) ? $wp_dp_listing_apartment_price_from[$key] : '',
                                'apartment_floor' => isset($wp_dp_listing_apartment_floor[$key]) ? $wp_dp_listing_apartment_floor[$key] : '',
                                'apartment_address' => isset($wp_dp_listing_apartment_address[$key]) ? $wp_dp_listing_apartment_address[$key] : '',
                                'apartment_availability' => isset($wp_dp_listing_apartment_availability[$key]) ? $wp_dp_listing_apartment_availability[$key] : '',
                                'apartment_link' => isset($wp_dp_listing_apartment_link[$key]) ? $wp_dp_listing_apartment_link[$key] : '',
                            );
                        }
                    }
                    update_post_meta($listing_id, 'wp_dp_apartment', $apartment_array);
                }

                // saving attachments.
                $wp_dp_listing_attachment_title = wp_dp_get_input('wp_dp_listing_attachment_title', '', 'ARRAY');
                $wp_dp_listing_attachment_file = wp_dp_get_input('wp_dp_listing_attachment_file', '', 'ARRAY');
                if ( is_array($wp_dp_listing_attachment_file) && sizeof($wp_dp_listing_attachment_file) > 0 ) {
                    $attachments_array = array();
                    foreach ( $wp_dp_listing_attachment_file as $key => $attachment ) {
                        if ( count($attachment) > 0 ) {
                            $attachments_array[] = array(
                                'attachment_title' => isset($wp_dp_listing_attachment_title[$key]) ? $wp_dp_listing_attachment_title[$key] : '',
                                'attachment_file' => $attachment,
                            );
                        }
                    }
                    update_post_meta($listing_id, 'wp_dp_attachments', $attachments_array);
                }
                // end saving attachments.
                // saving opening hours
                $wp_dp_opening_hours = wp_dp_get_input('wp_dp_opening_hour', '', 'ARRAY');
                update_post_meta($listing_id, 'wp_dp_opening_hours', $wp_dp_opening_hours);

                // saving book off days
                $wp_dp_off_days = wp_dp_get_input('wp_dp_listing_off_days', '', 'ARRAY');
                update_post_meta($listing_id, 'wp_dp_calendar', $wp_dp_off_days);

                // Check Free or Paid listing
                // Assign Package in case of paid
                // Assign Status of listing

                do_action('wp_dp_listing_add_save_assignments', $listing_id, $member_id);
            }
        }

        /**
         * Assigning Status for Listing
         * @return
         */
        public function listing_update_status($listing_id = '') {
            global $wp_dp_plugin_options;
            $wp_dp_listings_review_option = isset($wp_dp_plugin_options['wp_dp_listings_review_option']) ? $wp_dp_plugin_options['wp_dp_listings_review_option'] : '';

            $get_listing_id = wp_dp_get_input('listing_id', 0);
            $is_updating = false;
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
            }

            $user_data = wp_get_current_user();

            if ( $wp_dp_listings_review_option == 'on' ) {
                update_post_meta($listing_id, 'wp_dp_listing_status', 'awaiting-activation');
                // Listing not approved
                do_action('wp_dp_listing_not_approved_email', $user_data, $listing_id);
            } else {
                update_post_meta($listing_id, 'wp_dp_listing_status', 'active');
                // Listing approved
                do_action('wp_dp_listing_approved_email', $user_data, $listing_id);

                // social sharing
                $get_social_reach = get_post_meta($listing_id, 'wp_dp_transaction_listing_social', true);
                if ( $get_social_reach == 'on' ) {
                    do_action('wp_dp_listing_social_post', $listing_id);
                }
            }

            $wp_dp_free_listings_switch = isset($wp_dp_plugin_options['wp_dp_free_listings_switch']) ? $wp_dp_plugin_options['wp_dp_free_listings_switch'] : '';

            if ( $wp_dp_free_listings_switch != 'on' ) {

                $wp_dp_package_id = get_post_meta($listing_id, 'wp_dp_listing_package', true);
                if ( $wp_dp_package_id ) {
                    $wp_dp_package_data = get_post_meta($wp_dp_package_id, 'wp_dp_package_data', true);

                    $listing_duration = isset($wp_dp_package_data['listing_duration']['value']) ? $wp_dp_package_data['listing_duration']['value'] : 0;

                    // calculating listing expiry date
                    $wp_dp_trans_listing_expiry = $this->date_conv($listing_duration, 'days');
                    update_post_meta($listing_id, 'wp_dp_listing_expired', strtotime($wp_dp_trans_listing_expiry));
                }
            }
        }

        /**
         * checking member own post
         * @return boolean
         */
        public function is_member_listing($listing_id = '') {
            global $current_user;
            $company_id = wp_dp_company_id_form_user_id($current_user->ID);
            $wp_dp_member_id = get_post_meta($listing_id, 'wp_dp_listing_member', true);
            if ( is_user_logged_in() && $company_id == $wp_dp_member_id ) {
                return true;
            }
            return false;
        }

        /**
         * checking package
         * @return boolean
         */
        public function is_package($id = '') {
            $package = get_post($id);
            if ( isset($package->post_type) && $package->post_type == 'packages' ) {
                return true;
            }
            return false;
        }

        /**
         * Checking is form submit
         * @return boolean
         */
        public function is_form_submit() {

            if ( isset($_POST['wp_dp_listing_title']) ) {
                return true;
            }
            return false;
        }

        /**
         * Get Listing Content
         * @return markup
         */
        public function listing_post_content($id = '') {

            $content = get_post($id);
            $content = $content->post_content;
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
            return apply_filters('wp_dp_front_listing_post_content', $content, $id);
            // usage :: add_filter('wp_dp_front_listing_post_content', 'my_callback_function', 10, 2);
        }

        /**
         * Get Listing Transaction id
         * @return id
         */
        public function listing_trans_id($listing_id = '') {

            $get_subscripton_data = get_post_meta($listing_id, "package_subscripton_data", true);
            if ( is_array($get_subscripton_data) ) {
                $last_subs = end($get_subscripton_data);
                $trans_id = isset($last_subs['transaction_id']) ? $last_subs['transaction_id'] : false;
                return $trans_id;
            }
        }

        /**
         * Check Free or Paid listing
         * Assign Package in case of paid
         * Assign Status of listing
         * @return
         */
        public function listing_save_assignments($listing_id = '', $member_id = '') {
            global $wp_dp_plugin_options;
            $get_listing_id = wp_dp_get_input('listing_id', 0);
            $is_updating = false;
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
            }
            $wp_dp_free_listings_switch = isset($wp_dp_plugin_options['wp_dp_free_listings_switch']) ? $wp_dp_plugin_options['wp_dp_free_listings_switch'] : '';
            $wp_dp_listing_default_expiry = isset($wp_dp_plugin_options['wp_dp_listing_default_expiry']) ? $wp_dp_plugin_options['wp_dp_listing_default_expiry'] : '';

            if ( $wp_dp_free_listings_switch == 'on' ) {
                // Free Posting without any Package
                if ( ! $is_updating ) {

                    // Assign expire date
                    $wp_dp_ins_exp = strtotime(current_time('Y/m/d H:i:s', 1));
                    if ( $wp_dp_listing_default_expiry != '' && is_numeric($wp_dp_listing_default_expiry) && $wp_dp_listing_default_expiry > 0 ) {
                        $wp_dp_ins_exp = $this->date_conv($wp_dp_listing_default_expiry, 'days');
                    }
                    update_post_meta($listing_id, 'wp_dp_listing_expired', strtotime($wp_dp_ins_exp));

                    // Assign without package true
                    update_post_meta($listing_id, 'wp_dp_listing_without_package', '1');

                    // Assign Status of listing
                    do_action('wp_dp_listing_add_assign_status', $listing_id);
                }
            } else {

                $new_pkg_check = wp_dp_get_input('wp_dp_listing_new_package_used', '');

                if ( $new_pkg_check == 'on' ) {

                    $package_id = wp_dp_get_input('wp_dp_listing_package', 0);
                    if ( $this->is_package($package_id) ) {
                        if ( $is_updating ) {
                            // package subscribe
                            // add transaction
                            $transaction_detail = $this->wp_dp_listing_add_transaction('update-listing', $listing_id, $package_id, $member_id);
                            echo force_balance_tags($transaction_detail);
                        } else {
                            // package subscribe
                            // add transaction
                            $transaction_detail = $this->wp_dp_listing_add_transaction('add-listing', $listing_id, $package_id, $member_id);
                            echo force_balance_tags($transaction_detail);
                        }
                    }
                    // end of using new package
                } else {

                    $active_package_key = wp_dp_get_input('wp_dp_listing_active_package', 0);
                    $active_package_key = explode('pt_', $active_package_key);
                    $active_pckg_id = isset($active_package_key[0]) ? $active_package_key[0] : '';
                    $active_pckg_trans_id = isset($active_package_key[1]) ? $active_package_key[1] : '';
                    if ( $this->is_package($active_pckg_id) ) {
                        $t_package_feature_list = get_post_meta($active_pckg_trans_id, 'wp_dp_transaction_listing_feature_list', true);
                        $t_package_top_cat_list = get_post_meta($active_pckg_trans_id, 'wp_dp_transaction_listing_top_cat_list', true);

                        if ( $is_updating ) {
                            $wp_dp_package_id = get_post_meta($listing_id, 'wp_dp_listing_package', true);
                            $wp_dp_trans_id = $this->listing_trans_id($listing_id);
                            // update-listing
                            $is_pkg_subs = $this->wp_dp_is_pkg_subscribed($active_pckg_id, $active_pckg_trans_id);
                            if ( $wp_dp_package_id != $active_pckg_id || $active_pckg_trans_id != $wp_dp_trans_id ) {
                                // if package subscribe
                                if ( $is_pkg_subs ) {

                                    // update featured, top category
                                    // this change will be temporary
                                    update_post_meta($listing_id, "wp_dp_listing_is_featured", '');
                                    update_post_meta($listing_id, "wp_dp_listing_is_top_cat", '');

                                    // Get Transaction Listings array
                                    // Merge new Listing in Array
                                    $get_trans_listings = get_post_meta($active_pckg_trans_id, "wp_dp_listing_ids", true);
                                    $updated_trans_listings = $this->merge_in_array($get_trans_listings, $listing_id);
                                    update_post_meta($active_pckg_trans_id, "wp_dp_listing_ids", $updated_trans_listings);

                                    $active_pckg_trans_title = $active_pckg_trans_id != '' ? str_replace('#', '', get_the_title($active_pckg_trans_id)) : '';

                                    // updating package id in listing
                                    update_post_meta($listing_id, "wp_dp_listing_package", $active_pckg_id);

                                    // updating transaction title id in listing
                                    update_post_meta($listing_id, "wp_dp_trans_id", $active_pckg_trans_title);

                                    // update listing subscription renew
                                    $get_subscripton_data = get_post_meta($listing_id, "package_subscripton_data", true);
                                    if ( empty($get_subscripton_data) ) {
                                        $package_subscripton_data = array(
                                            array(
                                                'type' => 'update_package',
                                                'transaction_id' => $active_pckg_trans_id,
                                                'title_id' => $active_pckg_trans_title,
                                                'package_id' => $active_pckg_id,
                                                'subscribe_date' => strtotime(current_time('Y/m/d H:i:s', 1)),
                                            )
                                        );
                                    } else {
                                        $package_subscripton_data = array(
                                            'type' => 'update_package',
                                            'transaction_id' => $active_pckg_trans_id,
                                            'title_id' => $active_pckg_trans_title,
                                            'package_id' => $active_pckg_id,
                                            'renew_date' => strtotime(current_time('Y/m/d H:i:s', 1)),
                                        );
                                    }
                                    $merged_subscripton_data = $this->merge_in_array($get_subscripton_data, $package_subscripton_data, false);
                                    update_post_meta($listing_id, "package_subscripton_data", $merged_subscripton_data);

                                    // updating listing meta
                                    // as per transaction meta
                                    do_action('wp_dp_listing_assign_trans_meta', $listing_id, $active_pckg_trans_id);

                                    // Assign Status of listing
                                    do_action('wp_dp_listing_add_assign_status', $listing_id);
                                }
                            }
                            if ( $is_pkg_subs ) {
                                // update listing featured
                                if ( (int) $t_package_feature_list > 0 ) {
                                    // featured from form
                                    $get_listing_featured = wp_dp_get_input('wp_dp_listing_featured', '');
                                    // featured from meta
                                    $db_listing_featured = get_post_meta($listing_id, "wp_dp_listing_is_featured", true);
                                    $get_trans_feature_list = get_post_meta($active_pckg_trans_id, "wp_dp_featured_ids", true);
                                    if ( empty($get_trans_feature_list) ) {
                                        $get_trans_feature_size = 0;
                                    } else {
                                        $get_trans_feature_size = absint(sizeof($get_trans_feature_list));
                                    }
                                    $remaining_featured_list = (int) $t_package_feature_list - $get_trans_feature_size;
                                    if ( $remaining_featured_list > 0 ) {
                                        if ( $get_listing_featured == 'on' && $db_listing_featured != 'on' ) {
                                            update_post_meta($listing_id, "wp_dp_listing_is_featured", 'on');
                                            $feat_merged_res = $this->merge_in_array($get_trans_feature_list, $listing_id);
                                            update_post_meta($active_pckg_trans_id, "wp_dp_featured_ids", $feat_merged_res);
                                        } else if ( $get_listing_featured != 'on' && $db_listing_featured == 'on' ) {
                                            update_post_meta($listing_id, "wp_dp_listing_is_featured", '');
                                        }
                                    }
                                } else {
                                    update_post_meta($listing_id, "wp_dp_listing_is_featured", '');
                                }

                                // update listing top category
                                if ( (int) $t_package_top_cat_list > 0 ) {
                                    // Top Cat from form
                                    $get_listing_top_cat = wp_dp_get_input('wp_dp_listing_top_cat', '');
                                    // Top Cat from meta
                                    $db_listing_top_cat = get_post_meta($listing_id, 'wp_dp_listing_is_top_cat', true);
                                    $get_trans_top_cat_list = get_post_meta($active_pckg_trans_id, "wp_dp_top_cat_ids", true);
                                    if ( empty($get_trans_top_cat_list) ) {
                                        $get_trans_top_cat_size = 0;
                                    } else {
                                        $get_trans_top_cat_size = absint(sizeof($get_trans_top_cat_list));
                                    }
                                    $remaining_top_cat_list = (int) $t_package_top_cat_list - $get_trans_top_cat_size;
                                    if ( $remaining_top_cat_list > 0 ) {
                                        if ( $get_listing_top_cat == 'on' && $db_listing_top_cat != 'on' ) {
                                            update_post_meta($listing_id, "wp_dp_listing_is_top_cat", 'on');
                                            $top_cat_merged_res = $this->merge_in_array($get_trans_top_cat_list, $listing_id);
                                            update_post_meta($active_pckg_trans_id, "wp_dp_top_cat_ids", $top_cat_merged_res);
                                        } else if ( $get_listing_top_cat != 'on' && $db_listing_top_cat == 'on' ) {
                                            update_post_meta($listing_id, "wp_dp_listing_is_top_cat", '');
                                        }
                                    }
                                } else {
                                    update_post_meta($listing_id, "wp_dp_listing_is_top_cat", '');
                                }
                            }
                        } else {
                            // if package subscribe
                            if ( $this->wp_dp_is_pkg_subscribed($active_pckg_id, $active_pckg_trans_id) ) {

                                // Get Transaction Listings array
                                // Merge new Listing in Array
                                $get_trans_listings = get_post_meta($active_pckg_trans_id, "wp_dp_listing_ids", true);
                                $updated_trans_listings = $this->merge_in_array($get_trans_listings, $listing_id);
                                update_post_meta($active_pckg_trans_id, "wp_dp_listing_ids", $updated_trans_listings);

                                $active_pckg_trans_title = $active_pckg_trans_id != '' ? str_replace('#', '', get_the_title($active_pckg_trans_id)) : '';
                                // updating package id in listing
                                update_post_meta($listing_id, "wp_dp_listing_package", $active_pckg_id);

                                // updating transaction title id in listing
                                update_post_meta($listing_id, "wp_dp_trans_id", $active_pckg_trans_title);

                                // update listing subscription renew
                                $get_subscripton_data = get_post_meta($listing_id, "package_subscripton_data", true);

                                if ( empty($get_subscripton_data) ) {
                                    $package_subscripton_data = array(
                                        array(
                                            'type' => 'update_package',
                                            'transaction_id' => $active_pckg_trans_id,
                                            'title_id' => $active_pckg_trans_title,
                                            'package_id' => $active_pckg_id,
                                            'subscribe_date' => strtotime(current_time('Y/m/d H:i:s', 1)),
                                        )
                                    );
                                } else {
                                    $package_subscripton_data = array(
                                        'type' => 'update_package',
                                        'transaction_id' => $active_pckg_trans_id,
                                        'title_id' => $active_pckg_trans_title,
                                        'package_id' => $active_pckg_id,
                                        'renew_date' => strtotime(current_time('Y/m/d H:i:s', 1)),
                                    );
                                }
                                $merged_subscripton_data = $this->merge_in_array($get_subscripton_data, $package_subscripton_data, false);
                                update_post_meta($listing_id, "package_subscripton_data", $merged_subscripton_data);

                                // update listing featured
                                if ( (int) $t_package_feature_list > 0 ) {
                                    // featured from form
                                    $get_listing_featured = wp_dp_get_input('wp_dp_listing_featured', '');
                                    // featured from meta
                                    $db_listing_featured = get_post_meta($listing_id, "wp_dp_listing_is_featured", true);

                                    $get_trans_feature_list = get_post_meta($active_pckg_trans_id, "wp_dp_featured_ids", true);
                                    if ( empty($get_trans_feature_list) ) {
                                        $get_trans_feature_size = 0;
                                    } else {
                                        $get_trans_feature_size = absint(sizeof($get_trans_feature_list));
                                    }
                                    $remaining_featured_list = (int) $t_package_feature_list - $get_trans_feature_size;
                                    if ( $remaining_featured_list > 0 ) {
                                        if ( $get_listing_featured == 'on' && $db_listing_featured != 'on' ) {
                                            update_post_meta($listing_id, "wp_dp_listing_is_featured", 'on');
                                            $feat_merged_res = $this->merge_in_array($get_trans_feature_list, $listing_id);
                                            update_post_meta($active_pckg_trans_id, "wp_dp_featured_ids", $feat_merged_res);
                                        } else if ( $get_listing_featured != 'on' && $db_listing_featured == 'on' ) {
                                            update_post_meta($listing_id, "wp_dp_listing_is_featured", '');
                                        }
                                    }
                                } else {
                                    update_post_meta($listing_id, "wp_dp_listing_is_featured", '');
                                }

                                // update listing top category
                                if ( (int) $t_package_top_cat_list > 0 ) {
                                    // Top Cat from form
                                    $get_listing_top_cat = wp_dp_get_input('wp_dp_listing_top_cat', '');
                                    // Top Cat from meta
                                    $db_listing_top_cat = wp_dp_check_promotion_status($listing_id, 'top-categories');

                                    $get_trans_top_cat_list = get_post_meta($active_pckg_trans_id, "wp_dp_top_cat_ids", true);
                                    if ( empty($get_trans_top_cat_list) ) {
                                        $get_trans_top_cat_size = 0;
                                    } else {
                                        $get_trans_top_cat_size = absint(sizeof($get_trans_top_cat_list));
                                    }
                                    $remaining_top_cat_list = (int) $t_package_top_cat_list - $get_trans_top_cat_size;
                                    if ( $remaining_top_cat_list > 0 ) {
                                        if ( $get_listing_top_cat == 'on' && $db_listing_top_cat != 'on' ) {
                                            update_post_meta($listing_id, "wp_dp_listing_is_top_cat", 'on');
                                            $top_cat_merged_res = $this->merge_in_array($get_trans_top_cat_list, $listing_id);
                                            update_post_meta($active_pckg_trans_id, "wp_dp_top_cat_ids", $top_cat_merged_res);
                                        } else if ( $get_listing_top_cat != 'on' && $db_listing_top_cat == 'on' ) {
                                            update_post_meta($listing_id, "wp_dp_listing_is_top_cat", '');
                                        }
                                    }
                                } else {
                                    update_post_meta($listing_id, "wp_dp_listing_is_top_cat", '');
                                }

                                // updating listing meta
                                // as per transaction meta
                                do_action('wp_dp_listing_assign_trans_meta', $listing_id, $active_pckg_trans_id);

                                // Assign Status of listing
                                do_action('wp_dp_listing_add_assign_status', $listing_id);
                            }
                        }
                    }
                    // end of using existing package
                }
                // end assigning packages
                // and payment processs
            }

            // submit msg
            if ( $is_updating ) {
                $sumbit_msg = wp_dp_plugin_text_srt('wp_dp_member_add_list_ad_updated');
                $user_data = wp_get_current_user();
                // Listing not approved
                do_action('wp_dp_listing_updated_on_admin', $user_data, $listing_id);
            } else {
                $sumbit_msg = wp_dp_plugin_text_srt('wp_dp_member_add_list_ad_added');
            }
            $this->listing_submit_msg($sumbit_msg);
        }

        /**
         * Adding Transaction
         * @return id
         */
        public function wp_dp_listing_add_transaction($type = '', $listing_id = 0, $package_id = 0, $member_id = '') {
            global $wp_dp_plugin_options;
            $wp_dp_vat_switch = isset($wp_dp_plugin_options['wp_dp_vat_switch']) ? $wp_dp_plugin_options['wp_dp_vat_switch'] : '';
            $wp_dp_pay_vat = isset($wp_dp_plugin_options['wp_dp_payment_vat']) ? $wp_dp_plugin_options['wp_dp_payment_vat'] : '';
            $woocommerce_enabled = isset($wp_dp_plugin_options['wp_dp_use_woocommerce_gateway']) ? $wp_dp_plugin_options['wp_dp_use_woocommerce_gateway'] : '';
            $wp_dp_trans_id = rand(10000000, 99999999);
            $transaction_detail = '';
            $transaction_post = array(
                'post_title' => '#' . $wp_dp_trans_id,
                'post_status' => 'publish',
                'post_type' => 'package-orders',
                'post_date' => current_time('Y/m/d H:i:s', 1)
            );
            //insert the transaction
            if ( $member_id != '' ) {
                $trans_id = wp_insert_post($transaction_post);
                update_post_meta($trans_id, 'wp_dp_currency', wp_dp_base_currency_sign());
                update_post_meta($trans_id, 'wp_dp_currency_position', wp_dp_get_currency_position());
                update_post_meta($trans_id, 'wp_dp_currency_obj', wp_dp_get_base_currency());
            }

            if ( isset($trans_id) && $type != '' && $trans_id > 0 ) {

                $pay_process = true;

                $wp_dp_trans_pkg = '';
                $package_listing_allowed = 0;
                $package_listing_duration = 0;

                $wp_dp_trans_amount = 0;
                $wp_dp_vat_amount = 0;

                if ( $package_id != '' && $package_id != 0 ) {
                    $wp_dp_trans_pkg = $package_id;

                    $wp_dp_package_data = get_post_meta($package_id, 'wp_dp_package_data', true);
                    $package_listing_duration = isset($wp_dp_package_data['listing_duration']['value']) ? $wp_dp_package_data['listing_duration']['value'] : 0;
                    $package_listing_allowed = isset($wp_dp_package_data['number_of_listing_allowed']['value']) ? $wp_dp_package_data['number_of_listing_allowed']['value'] : 0;

                    $package_amount = get_post_meta($package_id, 'wp_dp_package_price', true);

                    // calculating_amount
                    $wp_dp_trans_amount += WP_DP_FUNCTIONS()->num_format($package_amount);

                    if ( $woocommerce_enabled != 'on' ) {
                        if ( $wp_dp_vat_switch == 'on' && $wp_dp_pay_vat > 0 && $wp_dp_trans_amount > 0 ) {

                            $wp_dp_vat_amount = $wp_dp_trans_amount * ( $wp_dp_pay_vat / 100 );
                            $wp_dp_vat_amount = WP_DP_FUNCTIONS()->num_format($wp_dp_vat_amount);
                            $wp_dp_trans_amount += $wp_dp_vat_amount;
                        }
                    }

                    // transaction offer fields 
                    $t_package_pic_num = isset($wp_dp_package_data['number_of_pictures']['value']) ? $wp_dp_package_data['number_of_pictures']['value'] : 0;
                    $t_package_doc_num = isset($wp_dp_package_data['number_of_documents']['value']) ? $wp_dp_package_data['number_of_documents']['value'] : 0;
                    $t_package_tags_num = isset($wp_dp_package_data['number_of_tags']['value']) ? $wp_dp_package_data['number_of_tags']['value'] : 0;
                    $t_package_feature_list = isset($wp_dp_package_data['number_of_featured_listings']['value']) ? $wp_dp_package_data['number_of_featured_listings']['value'] : '';
                    $t_package_top_cat_list = isset($wp_dp_package_data['number_of_top_cat_listings']['value']) ? $wp_dp_package_data['number_of_top_cat_listings']['value'] : '';
                    $t_package_phone = isset($wp_dp_package_data['phone_number_website']['value']) ? $wp_dp_package_data['phone_number_website']['value'] : '';
                    $t_package_social = isset($wp_dp_package_data['social_impressions_reach']['value']) ? $wp_dp_package_data['social_impressions_reach']['value'] : '';
                    $t_package_reviews = isset($wp_dp_package_data['reviews']['value']) ? $wp_dp_package_data['reviews']['value'] : '';
                    $t_package_ror = isset($wp_dp_package_data['respond_to_reviews']['value']) ? $wp_dp_package_data['respond_to_reviews']['value'] : '';
                    $t_package_dynamic_values = get_post_meta($package_id, 'wp_dp_package_fields', true);
                }

                $wp_dp_trans_array = array(
                    'transaction_id' => $trans_id,
                    'transaction_user' => $member_id,
                    'transaction_package' => $wp_dp_trans_pkg,
                    'transaction_amount' => $wp_dp_trans_amount,
                    'transaction_origional_price' => $wp_dp_trans_origional_price,
                    'transaction_vat_price' => $wp_dp_vat_amount,
                    'transaction_vat_tax' => $wp_dp_pay_vat,
                    'transaction_listings' => $package_listing_allowed,
                    'transaction_listing_expiry' => $package_listing_duration,
                    'transaction_listing_pic_num' => isset($t_package_pic_num) ? $t_package_pic_num : '',
                    'transaction_listing_doc_num' => isset($t_package_doc_num) ? $t_package_doc_num : '',
                    'transaction_listing_tags_num' => isset($t_package_tags_num) ? $t_package_tags_num : '',
                    'transaction_listing_feature_list' => isset($t_package_feature_list) ? $t_package_feature_list : '',
                    'transaction_listing_top_cat_list' => isset($t_package_top_cat_list) ? $t_package_top_cat_list : '',
                    'transaction_listing_phone_website' => isset($t_package_phone) ? $t_package_phone : '',
                    'transaction_listing_social' => isset($t_package_social) ? $t_package_social : '',
                    'transaction_listing_reviews' => isset($t_package_reviews) ? $t_package_reviews : '',
                    'transaction_listing_ror' => isset($t_package_ror) ? $t_package_ror : '',
                    'transaction_dynamic' => isset($t_package_dynamic_values) ? $t_package_dynamic_values : '',
                    'transaction_ptype' => $type,
                );

                if ( $package_id != '' && $package_id != 0 ) {
                    if ( $wp_dp_trans_amount <= 0 ) {
                        $wp_dp_trans_array['transaction_pay_method'] = '-';
                        $wp_dp_trans_array['transaction_status'] = 'approved';
                        $pay_process = false;
                    }
                    $package_type = get_post_meta($package_id, 'wp_dp_package_type', true);
                    if ( $package_type == 'free' ) {
                        $wp_dp_trans_array['transaction_pay_method'] = '-';
                        $wp_dp_trans_array['transaction_status'] = 'approved';
                        $pay_process = false;
                    }
                }

                if ( ($type == 'add-listing' || $type == 'update-listing') && $listing_id != '' && $listing_id != 0 ) {

                    // update listing expiry, featured, top category
                    // this change will be temporary
                    update_post_meta($listing_id, "wp_dp_listing_expired", strtotime(current_time('Y/m/d H:i:s', 1)));
                    update_post_meta($listing_id, "wp_dp_listing_is_featured", '');
                    update_post_meta($listing_id, "wp_dp_listing_is_top_cat", '');

                    // updating listing ids in transaction
                    $wp_dp_trans_array['listing_ids'] = array( $listing_id );
                    // updating transaction id in listing
                    update_post_meta($listing_id, "wp_dp_trans_id", $wp_dp_trans_id);

                    // updating package id in listing
                    update_post_meta($listing_id, "wp_dp_listing_package", $package_id);

                    // update listing subscription
                    if ( $type == 'add-listing' ) {
                        $package_subscripton_data = array(
                            array(
                                'type' => ($type == 'add-listing' ? 'add_package' : 'update_package'),
                                'transaction_id' => $trans_id,
                                'title_id' => $wp_dp_trans_id,
                                'package_id' => $package_id,
                                'subscribe_date' => strtotime(current_time('Y/m/d H:i:s', 1)),
                            )
                        );
                    } else {
                        $package_subscripton_data = array(
                            'type' => ($type == 'add-listing' ? 'add_package' : 'update_package'),
                            'transaction_id' => $trans_id,
                            'title_id' => $wp_dp_trans_id,
                            'package_id' => $package_id,
                            'subscribe_date' => strtotime(current_time('Y/m/d H:i:s', 1)),
                        );
                    }
                    $get_subscripton_data = get_post_meta($listing_id, "package_subscripton_data", true);
                    $merged_subscripton_data = $this->merge_in_array($get_subscripton_data, $package_subscripton_data, false);
                    update_post_meta($listing_id, "package_subscripton_data", $merged_subscripton_data);

                    // update listing featured
                    if ( isset($wp_dp_package_data) && ! empty($wp_dp_package_data) ) {
                        // Top Cat from form
                        $get_listing_featured = wp_dp_get_input('wp_dp_listing_featured', '');
                        if ( (int) $t_package_feature_list > 0 && $get_listing_featured == 'on' ) {
                            update_post_meta($listing_id, "wp_dp_listing_is_featured", 'on');
                            $wp_dp_trans_array['featured_ids'] = array( $listing_id );
                        }
                    }

                    // update listing top category
                    if ( isset($wp_dp_package_data) && ! empty($wp_dp_package_data) ) {
                        // Top Cat from form
                        $get_listing_top_cat = wp_dp_get_input('wp_dp_listing_top_cat', '');
                        if ( (int) $t_package_top_cat_list > 0 && $get_listing_top_cat == 'on' ) {
                            update_post_meta($listing_id, "wp_dp_listing_is_top_cat", 'on');
                            $wp_dp_trans_array['top_cat_ids'] = array( $listing_id );
                        }
                    }
                }

                // update package dynamic fields in transaction
                $wp_dp_package_dynamic = get_post_meta($package_id, 'wp_dp_package_fields', true);
                $wp_dp_trans_array['transaction_dynamic'] = $wp_dp_package_dynamic;

                // updating all fields of transaction
                foreach ( $wp_dp_trans_array as $trans_key => $trans_val ) {
                    update_post_meta($trans_id, "wp_dp_{$trans_key}", $trans_val);
                }

                // Inserting VAT amount in array
                if ( isset($wp_dp_vat_amount) && $wp_dp_vat_amount > 0 ) {
                    $wp_dp_trans_array['vat_amount'] = $wp_dp_vat_amount;
                }

                // Inserting random id in array
                $wp_dp_trans_array['trans_rand_id'] = $wp_dp_trans_id;

                // Inserting item id in array
                if ( $listing_id != '' && $listing_id != 0 ) {
                    $wp_dp_trans_array['trans_item_id'] = $listing_id;
                    update_post_meta($trans_id, "order_item_id", $listing_id);
                } else {
                    $wp_dp_trans_array['trans_item_id'] = $wp_dp_trans_id;
                }

                if ( ($type == 'add-listing' || $type == 'update-listing') && $listing_id != '' && $listing_id != 0 ) {
                    // updating listing meta
                    // as per transaction meta
                    do_action('wp_dp_listing_assign_trans_meta', $listing_id, $trans_id);
                }

                // Payment Process
                if ( $pay_process ) {
                    // update listing status temporarily
                    // as pending
                    update_post_meta($listing_id, 'wp_dp_listing_status', 'pending');

                    $user_data = wp_get_current_user();
                    // Listing pending email
                    do_action('wp_dp_listing_pending_email', $user_data, $listing_id);

                    // Redirecting parameters
                    $wp_dp_payment_params = array(
                        'action' => 'listing-package',
                        'trans_id' => $trans_id,
                    );
                    $wp_dp_payment_page = isset($wp_dp_plugin_options['wp_dp_package_page']) ? $wp_dp_plugin_options['wp_dp_package_page'] : '';
                    $wp_dp_payment_page_link = $wp_dp_payment_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_payment_page, 'page') : '';

                    // Redirecting to Payment process on next page
                    if ( $wp_dp_payment_page_link != '' && $wp_dp_trans_amount > 0 ) {
                        $redirect_form_id = rand(1000000, 9999999);
                        $redirect_html = '
						<form id="form-' . $redirect_form_id . '" method="get" action="' . $wp_dp_payment_page_link . '">
						<input type="hidden" name="action" value="listing-package">
						<input type="hidden" name="trans_id" value="' . $trans_id . '">';
                        if ( isset($_GET['lang']) ) {
                            $redirect_html .= '<input type="hidden" name="lang" value="' . $_GET['lang'] . '">';
                        }
                        $redirect_html .= '
						</form>
						<script>document.getElementById("form-' . $redirect_form_id . '").submit();</script>';
                        echo force_balance_tags($redirect_html);
                        wp_die();
                    }
                } else {
                    $msg_arr = array( 'msg' => wp_dp_plugin_text_srt('wp_dp_member_add_list_pkg_subscribed'), 'type' => 'success' );
                    $msg_arr = json_encode($msg_arr);
                    echo '
					<script>
					jQuery(document).ready(function () {
						wp_dp_show_response(' . $msg_arr . ');
					});
					</script>';

                    // Assign Status of listing
                    // This will be case of Free Package
                    do_action('wp_dp_listing_add_assign_status', $listing_id);
                }
            }
            return apply_filters('wp_dp_listing_add_transaction', $transaction_detail, $type, $listing_id, $package_id, $member_id);
            // usage :: add_filter('wp_dp_listing_add_transaction', 'my_callback_function', 10, 5);
        }

        /**
         * Check user package subscription
         * @return id
         */
        public function wp_dp_is_pkg_subscribed($wp_dp_package_id = 0, $trans_id = 0) {
            global $post, $current_user;

            $company_id = wp_dp_company_id_form_user_id($current_user->ID);

            if ( $trans_id == '' ) {
                $trans_id = 0;
            }
            $transaction_id = false;
            $wp_dp_current_date = strtotime(date('d-m-Y'));
            $args = array(
                'posts_per_page' => "-1",
                'post_type' => 'package-orders',
                'post_status' => 'publish',
                'post__in' => array( $trans_id ),
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_transaction_package',
                        'value' => $wp_dp_package_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_transaction_user',
                        'value' => $company_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_transaction_status',
                        'value' => 'approved',
                        'compare' => '=',
                    ),
                ),
            );

            $custom_query = new WP_Query($args);
            $wp_dp_trans_count = $custom_query->post_count;

            if ( $wp_dp_trans_count > 0 ) {
                while ( $custom_query->have_posts() ) : $custom_query->the_post();
                    $wp_dp_pkg_list_num = get_post_meta($post->ID, 'wp_dp_transaction_listings', true);
                    $wp_dp_listing_ids = get_post_meta($post->ID, 'wp_dp_listing_ids', true);

                    if ( empty($wp_dp_listing_ids) ) {
                        $wp_dp_listing_ids_size = 0;
                    } else {
                        $wp_dp_listing_ids_size = absint(sizeof($wp_dp_listing_ids));
                    }
                    $wp_dp_ids_num = $wp_dp_listing_ids_size;
                    if ( (int) $wp_dp_ids_num < (int) $wp_dp_pkg_list_num ) {
                        $wp_dp_trnasaction_id = $post->ID;
                    }
                endwhile;
                wp_reset_postdata();
            }

            if ( isset($wp_dp_trnasaction_id) && $wp_dp_trnasaction_id > 0 ) {
                $transaction_id = $wp_dp_trnasaction_id;
            }
            return apply_filters('wp_dp_listing_is_package_subscribe', $transaction_id, $wp_dp_package_id, $trans_id);
            // usage :: add_filter('wp_dp_listing_is_package_subscribe', 'my_callback_function', 10, 3);
        }

        /**
         * Get all active packages of current user
         * @return array
         */
        public function user_all_active_pkgs() {
            global $post, $current_user;

            $company_id = wp_dp_company_id_form_user_id($current_user->ID);

            $trans_ids = array();
            $wp_dp_current_date = strtotime(date('d-m-Y'));
            $args = array(
                'posts_per_page' => "-1",
                'post_type' => 'package-orders',
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_transaction_user',
                        'value' => $company_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_transaction_status',
                        'value' => 'approved',
                        'compare' => '=',
                    ),
                ),
            );

            $custom_query = new WP_Query($args);
            $wp_dp_trans_count = $custom_query->post_count;

            if ( $wp_dp_trans_count > 0 ) {
                while ( $custom_query->have_posts() ) : $custom_query->the_post();
                    $wp_dp_pkg_list_num = get_post_meta($post->ID, 'wp_dp_transaction_listings', true);
                    $wp_dp_listing_ids = get_post_meta($post->ID, 'wp_dp_listing_ids', true);

                    if ( empty($wp_dp_listing_ids) ) {
                        $wp_dp_listing_ids_size = 0;
                    } else {
                        $wp_dp_listing_ids_size = absint(sizeof($wp_dp_listing_ids));
                    }

                    $wp_dp_ids_num = $wp_dp_listing_ids_size;
                    if ( (int) $wp_dp_ids_num < (int) $wp_dp_pkg_list_num ) {
                        $trans_ids[] = $post->ID;
                    }
                endwhile;
                wp_reset_postdata();
            }

            return apply_filters('wp_dp_listing_user_active_packages', $trans_ids);
            // usage :: add_filter('wp_dp_listing_user_active_packages', 'my_callback_function', 10, 1);
        }

        /**
         * Get User Package Trans
         * @return id
         */
        public function get_user_package_trans($wp_dp_package_id = 0, $trans_id = 0) {
            global $post, $current_user;

            $company_id = wp_dp_company_id_form_user_id($current_user->ID);

            if ( $trans_id == '' ) {
                $trans_id = 0;
            }
            $transaction_id = false;
            $args = array(
                'posts_per_page' => "1",
                'post_type' => 'package-orders',
                'post_status' => 'publish',
                'post__in' => array( $trans_id ),
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_transaction_package',
                        'value' => $wp_dp_package_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_transaction_user',
                        'value' => $company_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_transaction_status',
                        'value' => 'approved',
                        'compare' => '=',
                    ),
                ),
            );

            $custom_query = new WP_Query($args);
            $wp_dp_trans_count = $custom_query->post_count;

            if ( $wp_dp_trans_count > 0 ) {
                while ( $custom_query->have_posts() ) : $custom_query->the_post();
                    $wp_dp_trnasaction_id = $post->ID;
                endwhile;
                wp_reset_postdata();
            }

            if ( isset($wp_dp_trnasaction_id) && $wp_dp_trnasaction_id > 0 ) {
                $transaction_id = $wp_dp_trnasaction_id;
            }
            return apply_filters('wp_dp_listing_user_package_trans', $transaction_id, $wp_dp_package_id, $trans_id);
            // usage :: add_filter('wp_dp_listing_user_package_trans', 'my_callback_function', 10, 3);
        }

        /**
         * Purchased Package Info Field Create
         * @return markup
         */
        public function purchase_package_info_field_show($value = '', $label = '', $value_plus = '') {

            if ( $value != '' && $value != 'on' ) {
                $html = '<li><label>' . $label . '</label><span>' . $value . ' ' . $value_plus . '</span></li>';
            } else if ( $value != '' && $value == 'on' ) {
                $html = '<li><label>' . $label . '</label><span><i class="icon-check2"></i></span></li>';
            } else {
                $html = '<li><label>' . $label . '</label><span><i class="icon-minus"></i></span></li>';
            }

            return $html;
        }

        /**
         * Get Subscribe Package info
         * @return html
         */
        public function subs_package_info($package_id = 0, $trans_id = 0) {
            global $listing_add_counter;
            $html = '';
            $inner_html = '';

            if ( $user_package = $this->get_user_package_trans($package_id, $trans_id) ) {
                $title_id = $user_package != '' ? get_the_title($user_package) : '';
                $trans_packg_id = get_post_meta($trans_id, 'wp_dp_transaction_package', true);
                $packg_title = $trans_packg_id != '' ? get_the_title($trans_packg_id) : '';

                $trans_packg_list_num = get_post_meta($trans_id, 'wp_dp_transaction_listings', true);
                $trans_packg_list_expire = get_post_meta($trans_id, 'wp_dp_transaction_listing_expiry', true);
                $wp_dp_listing_ids = get_post_meta($trans_id, 'wp_dp_listing_ids', true);

                if ( empty($wp_dp_listing_ids) ) {
                    $wp_dp_listing_used = 0;
                } else {
                    $wp_dp_listing_used = absint(sizeof($wp_dp_listing_ids));
                }

                $wp_dp_listing_dpain = '0';
                if ( (int) $trans_packg_list_num > (int) $wp_dp_listing_used ) {
                    $wp_dp_listing_dpain = (int) $trans_packg_list_num - (int) $wp_dp_listing_used;
                }

                $trans_featured = get_post_meta($trans_id, 'wp_dp_transaction_listing_feature_list', true);

                $trans_top_cat = get_post_meta($trans_id, 'wp_dp_transaction_listing_top_cat_list', true);
                $trans_pics_num = get_post_meta($trans_id, 'wp_dp_transaction_listing_pic_num', true);
                $trans_docs_num = get_post_meta($trans_id, 'wp_dp_transaction_listing_doc_num', true);
                $trans_tags_num = get_post_meta($trans_id, 'wp_dp_transaction_listing_tags_num', true);

                $trans_phone_website = get_post_meta($trans_id, 'wp_dp_transaction_listing_phone_website', true);
                $trans_social = get_post_meta($trans_id, 'wp_dp_transaction_listing_social', true);
                $trans_reviews = get_post_meta($trans_id, 'wp_dp_transaction_listing_reviews', true);
                $trans_ror = get_post_meta($trans_id, 'wp_dp_transaction_listing_ror', true);
                $trans_dynamic_f = get_post_meta($trans_id, 'wp_dp_transaction_dynamic', true);

                $pkg_expire_date = date_i18n(get_option('date_format'), $trans_packg_expiry);

                $html .= '<div id="package-detail-' . $package_id . 'pt_' . $trans_id . '" style="display:none;" class="package-info-sec listing-info-sec">';
                $html .= '<div class="row">';
                $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                $html .= '<ul class="listing-pkg-points">';

                $html .= $this->purchase_package_info_field_show($pkg_expire_date, wp_dp_plugin_text_srt('wp_dp_member_add_list_expiry_date'));
                $html .= '<li><label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_listings') . '</label><span>' . absint($wp_dp_listing_used) . '/' . absint($trans_packg_list_num) . '</span></li>';
                $html .= $this->purchase_package_info_field_show($trans_packg_list_expire, wp_dp_plugin_text_srt('wp_dp_member_add_list_listing_duration'), wp_dp_plugin_text_srt('wp_dp_member_add_list_listing_days'));
                if ( $trans_featured == 'on' ) {
                    $html .= '<li><label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_featured_listing') . '</label><span><i class="icon-check2"></i></span></li>';
                } else {
                    $html .= '<li><label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_featured_listing') . '</label><span><i class="icon-minus"></i></span></li>';
                }
                if ( $trans_top_cat == 'on' ) {
                    $html .= '<li><label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_top_cat_listing') . '</label><span><i class="icon-check2"></i></span></li>';
                } else {
                    $html .= '<li><label>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_top_cat_listing') . '</label><span><i class="icon-minus"></i></span></li>';
                }
                $html .= $this->purchase_package_info_field_show($trans_pics_num, wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_pictures'));
                $html .= $this->purchase_package_info_field_show($trans_docs_num, wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_docs'));
                $html .= $this->purchase_package_info_field_show($trans_tags_num, wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_tags'));
                $html .= $this->purchase_package_info_field_show($trans_phone_website, wp_dp_plugin_text_srt('wp_dp_listing_phone_num_web_str'));
                $html .= $this->purchase_package_info_field_show($trans_social, wp_dp_plugin_text_srt('wp_dp_member_add_list_social_reach'));

                $dyn_fields_html = '';
                if ( is_array($trans_dynamic_f) && sizeof($trans_dynamic_f) > 0 ) {
                    foreach ( $trans_dynamic_f as $trans_dynamic ) {
                        if ( isset($trans_dynamic['field_type']) && isset($trans_dynamic['field_label']) && isset($trans_dynamic['field_value']) ) {
                            $d_type = $trans_dynamic['field_type'];
                            $d_label = $trans_dynamic['field_label'];
                            $d_value = $trans_dynamic['field_value'];

                            if ( $d_value == 'on' && $d_type == 'single-choice' ) {
                                $html .= '<li><label>' . $d_label . '</label><span><i class="icon-check2"></i></span></li>';
                            } else if ( $d_value != '' && $d_type != 'single-choice' ) {
                                $html .= '<li><label>' . $d_label . '</label><span>' . $d_value . '</span></li>';
                            } else {
                                $html .= '<li><label>' . $d_label . '</label><span><i class="icon-minus"></i></span></li>';
                            }
                        }
                    }
                    // end foreach
                }
                // emd of Dynamic fields
                // other Features



                $html .= '
				</ul>
				</div>';

                if ( $trans_featured == 'on' || $trans_top_cat == 'on' ) {
                    $html .= '
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';

                    if ( $trans_featured == 'on' ) {
                        $html .= '
						<div class="package-featured pakg-switch">
							<span>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_featured') . ' :</span>
							<input id="package-featured-' . $package_id . 'pt_' . $trans_id . '" type="checkbox" class="cmn-toggle cmn-toggle-round" name="wp_dp_listing_featured">
							<label for="package-featured-' . $package_id . 'pt_' . $trans_id . '"></label>
						</div>';
                    }

                    if ( $trans_top_cat == 'on' ) {
                        $html .= '
						<div class="package-top-cat pakg-switch">
							<span>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_top_category') . ' :</span>
							<input id="package-top-cat-' . $package_id . 'pt_' . $trans_id . '" type="checkbox" class="cmn-toggle cmn-toggle-round" name="wp_dp_listing_top_cat">
							<label for="package-top-cat-' . $package_id . 'pt_' . $trans_id . '"></label>
						</div>';
                    }

                    $html .= '
					</div>';
                }

                $html .= '
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="pgk-action-btns">
						<a href="javascript:void(0);" data-id="' . $package_id . 'pt_' . $trans_id . '" class="pkg-choose-btn">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_choose_pkg') . '</a>
						<a href="javascript:void(0);" data-id="' . $package_id . 'pt_' . $trans_id . '" class="pkg-cancel-btn">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_cancel') . '</a>
					</div>
				</div>
				</div>
				</div>';
            }

            return apply_filters('wp_dp_listing_user_subs_package_info', $html, $package_id, $trans_id);
            // usage :: add_filter('wp_dp_listing_user_subs_package_info', 'my_callback_function', 10, 3);
        }

        /**
         * Package Info Field Create
         * @return markup
         */
        public function package_info_field_show($info_meta = array(), $index = '', $label = '', $value_plus = '') {
            if ( isset($info_meta[$index]['value']) ) {
                $value = $info_meta[$index]['value'];

                if ( $value != '' && $value != 'on' ) {
                    $html = '<li><label>' . $label . '</label><span>' . $value . ' ' . $value_plus . '</span></li>';
                } else if ( $value != '' && $value == 'on' ) {
                    $html = '<li><label>' . $label . '</label><span><i class="icon-check2"></i></span></li>';
                } else {
                    $html = '<li><label>' . $label . '</label><span><i class="icon-minus"></i></span></li>';
                }

                return $html;
            }
        }

        /**
         * Get New Package info
         * @return html
         */
        public function new_package_info($package_id = 0) {
            global $listing_add_counter;
            $html = '';

            $packg_title = $package_id != '' ? get_the_title($package_id) : '';
            $trans_all_meta = get_post_meta($package_id, 'wp_dp_package_data', true);

            $html .= '<div id="package-detail-' . $package_id . '" style="display:none;" class="package-info-sec listing-info-sec">';
            $html .= '<div class="row">';
            $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            $html .= '<ul class="listing-pkg-points">';
            $html .= $this->package_info_field_show($trans_all_meta, 'number_of_listing_allowed', wp_dp_plugin_text_srt('wp_dp_listing_total_listings'));
            $html .= $this->package_info_field_show($trans_all_meta, 'listing_duration', wp_dp_plugin_text_srt('wp_dp_listing_listing_duration'), wp_dp_plugin_text_srt('wp_dp_listing_days'));
            $html .= $this->package_info_field_show($trans_all_meta, 'number_of_featured_listings', wp_dp_plugin_text_srt('wp_dp_listing_featured_listings'));
            $html .= $this->package_info_field_show($trans_all_meta, 'number_of_top_cat_listings', wp_dp_plugin_text_srt('wp_dp_listing_top_cat_listings'));
            $html .= $this->package_info_field_show($trans_all_meta, 'number_of_pictures', wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_pictures'));
            $html .= $this->package_info_field_show($trans_all_meta, 'number_of_documents', wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_docs'));
            $html .= $this->package_info_field_show($trans_all_meta, 'number_of_tags', wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_tags'));
            $html .= $this->package_info_field_show($trans_all_meta, 'phone_number', wp_dp_plugin_text_srt('wp_dp_member_add_list_phone_number'));
            $html .= $this->package_info_field_show($trans_all_meta, 'website_link', wp_dp_plugin_text_srt('wp_dp_member_add_list_web_link'));
            $html .= $this->package_info_field_show($trans_all_meta, 'cover_image', wp_dp_plugin_text_srt('wp_dp_member_cover_image'));
            $html .= $this->package_info_field_show($trans_all_meta, 'social_impressions_reach', wp_dp_plugin_text_srt('wp_dp_member_add_list_social_reach'));
            $trans_dynamic_f = get_post_meta($package_id, 'wp_dp_package_fields', true);
            if ( is_array($trans_dynamic_f) && sizeof($trans_dynamic_f) > 0 ) {
                foreach ( $trans_dynamic_f as $trans_dynamic ) {
                    if ( isset($trans_dynamic['field_type']) && isset($trans_dynamic['field_label']) && isset($trans_dynamic['field_value']) ) {
                        $d_type = $trans_dynamic['field_type'];
                        $d_label = $trans_dynamic['field_label'];
                        $d_value = $trans_dynamic['field_value'];

                        if ( $d_value == 'on' && $d_type == 'single-choice' ) {
                            $html .= '<li><label>' . $d_label . '</label><span><i class="icon-check2"></i></span></li>';
                        } else if ( $d_value != '' && $d_type != 'single-choice' ) {
                            $html .= '<li><label>' . $d_label . '</label><span>' . $d_value . '</span></li>';
                        } else {
                            $html .= '<li><label>' . $d_label . '</label><span><i class="icon-minus"></i></span></li>';
                        }
                    }
                }
                // end foreach
            }
            // end of Dynamic fields
            // other Features
            $html .= '
			</ul>
			</div>';

            if ( isset($trans_all_meta['number_of_featured_listings']['value']) && (absint($trans_all_meta['number_of_featured_listings']['value']) == 'on' || absint($trans_all_meta['number_of_top_cat_listings']['value']) == 'on' ) ) {
                $html .= '
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                if ( absint($trans_all_meta['number_of_featured_listings']['value']) == 'on' ) {
                    $html .= '
					<div class="package-featured pakg-switch">
						<span>' . wp_dp_plugin_text_srt('wp_dp_listings_featured') . ' :</span>
						<input id="package-featured-' . $package_id . '" type="checkbox" class="cmn-toggle cmn-toggle-round" name="wp_dp_listing_featured">
						<label for="package-featured-' . $package_id . '"></label>
					</div>';
                }
                if ( absint($trans_all_meta['number_of_top_cat_listings']['value']) == 'on' ) {
                    $html .= '
					<div class="package-top-cat pakg-switch">
						<span>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_top_category') . ' :</span>
						<input id="package-top-cat-' . $package_id . '" type="checkbox" class="cmn-toggle cmn-toggle-round" name="wp_dp_listing_top_cat">
						<label for="package-top-cat-' . $package_id . '"></label>
					</div>';
                }
                $html .= '
				</div>';
            }

            $html .= '
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="pgk-action-btns">
					<a href="javascript:void(0);" data-id="' . $package_id . '" class="pkg-choose-btn">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_choose_pkg') . '</a>
					<a href="javascript:void(0);" data-id="' . $package_id . '" class="pkg-cancel-btn">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_save') . '</a>
				</div>
			</div>
			</div>
			</div>';

            return apply_filters('wp_dp_listing_user_new_package_info', $html, $package_id);
            // usage :: add_filter('wp_dp_listing_user_new_package_info', 'my_callback_function', 10, 2);
        }

        /**
         * Updating transaction meta into listing meta
         * @return
         */
        public function listing_assign_meta($listing_id = '', $trans_id = '') {
            $assign_array = array();
            $trans_get_value = get_post_meta($trans_id, 'wp_dp_transaction_listing_pic_num', true);
            $assign_array[] = array(
                'key' => 'wp_dp_transaction_listing_pic_num',
                'label' => wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_pictures'),
                'value' => $trans_get_value,
            );
            $trans_get_value = get_post_meta($trans_id, 'wp_dp_transaction_listing_doc_num', true);
            $assign_array[] = array(
                'key' => 'wp_dp_transaction_listing_doc_num',
                'label' => wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_docs'),
                'value' => $trans_get_value,
            );
            $trans_get_value = get_post_meta($trans_id, 'wp_dp_transaction_listing_tags_num', true);
            $assign_array[] = array(
                'key' => 'wp_dp_transaction_listing_tags_num',
                'label' => wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_tags'),
                'value' => $trans_get_value,
            );
            $trans_get_value = get_post_meta($trans_id, 'wp_dp_transaction_listing_reviews', true);
            $assign_array[] = array(
                'key' => 'wp_dp_transaction_listing_reviews',
                'label' => wp_dp_plugin_text_srt('wp_dp_listing_reviews'),
                'value' => $trans_get_value,
            );
            $trans_get_value = get_post_meta($trans_id, 'wp_dp_transaction_listing_phone_website', true);
            $assign_array[] = array(
                'key' => 'wp_dp_transaction_listing_phone_website',
                'label' => wp_dp_plugin_text_srt('wp_dp_listing_phone_num_web_str'),
                'value' => $trans_get_value,
            );

            $trans_get_value = get_post_meta($trans_id, 'wp_dp_transaction_listing_social', true);
            $assign_array[] = array(
                'key' => 'wp_dp_transaction_listing_social',
                'label' => wp_dp_plugin_text_srt('wp_dp_member_add_list_social_reach'),
                'value' => $trans_get_value,
            );

            $trans_get_value = get_post_meta($trans_id, 'wp_dp_transaction_dynamic', true);
            $assign_array[] = array(
                'key' => 'wp_dp_transaction_dynamic',
                'label' => wp_dp_plugin_text_srt('wp_dp_listing_other_features'),
                'value' => $trans_get_value,
            );

            if ( $listing_id != '' && $trans_id != '' ) {
                foreach ( $assign_array as $assign ) {
                    update_post_meta($listing_id, $assign['key'], $assign['value']);
                }
                update_post_meta($listing_id, 'wp_dp_trans_all_meta', $assign_array);
            }

            return $assign_array;
        }

        /**
         * User authentication
         * @return Ajax
         */
        public function user_authentication() {

            $field_type = isset($_POST['field_type']) ? $_POST['field_type'] : '';
            $field_val = isset($_POST['field_val']) ? $_POST['field_val'] : '';

            if ( $field_type == 'username' ) {
                if ( username_exists($field_val) ) {
                    $msg = wp_dp_plugin_text_srt('wp_dp_member_username_exists');
                    $action = 'false';
                } else if ( ! validate_username($field_val) || strlen($field_val) < 4 || strlen($field_val) > 20 ) {
                    $msg = wp_dp_plugin_text_srt('wp_dp_member_username_valid_error');
                    $action = 'false';
                } elseif ( ! preg_match('/^[a-zA-Z0-9_]{5,}$/', $field_val) ) {
                    $msg = wp_dp_plugin_text_srt('wp_dp_member_username_alpha_error');
                    $action = 'false';
                } else {
                    $msg = wp_dp_plugin_text_srt('wp_dp_member_username_available');
                    $action = 'true';
                }
            } else if ( $field_type == 'useremail' ) {
                if ( email_exists($field_val) ) {
                    $msg = wp_dp_plugin_text_srt('wp_dp_member_email_exists');
                    $action = 'false';
                } else if ( ! filter_var($field_val, FILTER_VALIDATE_EMAIL) ) {
                    $msg = wp_dp_plugin_text_srt('wp_dp_member_email_valid_error');
                    $action = 'false';
                } else {
                    $msg = wp_dp_plugin_text_srt('wp_dp_member_email_available');
                    $action = 'true';
                }
            } else {
                $msg = wp_dp_plugin_text_srt('wp_dp_member_unknown_field');
                $action = 'false';
            }

            echo json_encode(array( 'msg' => $msg, 'action' => $action ));
            wp_die();
        }

        public function listing_gallery_upload($Fieldname = 'media_upload', $listing_id = '') {
            $img_resized_name = '';
            $listing_gallery = array();
            $count = 0;

            if ( isset($_FILES[$Fieldname]) && $_FILES[$Fieldname] != '' ) {

                $multi_files = isset($_FILES[$Fieldname]) ? $_FILES[$Fieldname] : array();

                if ( isset($multi_files['name']) && is_array($multi_files['name']) ) {
                    $img_name_array = array();
                    foreach ( $multi_files['name'] as $multi_key => $multi_value ) {
                        if ( $multi_files['name'][$multi_key] ) {
                            $loop_file = array(
                                'name' => $multi_files['name'][$multi_key],
                                'type' => $multi_files['type'][$multi_key],
                                'tmp_name' => $multi_files['tmp_name'][$multi_key],
                                'error' => $multi_files['error'][$multi_key],
                                'size' => $multi_files['size'][$multi_key]
                            );

                            $json = array();
                            require_once ABSPATH . 'wp-admin/includes/image.php';
                            require_once ABSPATH . 'wp-admin/includes/file.php';
                            require_once ABSPATH . 'wp-admin/includes/media.php';
                            $allowed_image_types = array(
                                'jpg|jpeg|jpe' => 'image/jpeg',
                                'png' => 'image/png',
                                'gif' => 'image/gif',
                            );

                            $status = wp_handle_upload($loop_file, array( 'test_form' => false, 'mimes' => $allowed_image_types ));

                            if ( empty($status['error']) ) {

                                $image = wp_get_image_editor($status['file']);
                                $img_resized_name = $status['file'];

                                if ( is_wp_error($image) ) {

                                    echo '<span class="error-msg">' . $image->get_error_message() . '</span>';
                                } else {
                                    $wp_upload_dir = wp_upload_dir();
                                    $img_name_array[] = isset($status['url']) ? $status['url'] : '';
                                    $filename = $img_name_array[$count];
                                    $filetype = wp_check_filetype(basename($filename), null);

                                    if ( $filename != '' ) {
                                        // Prepare an array of post data for the attachment.

                                        $attachment = array(
                                            'guid' => ($filename),
                                            'post_mime_type' => $filetype['type'],
                                            'post_title' => preg_replace('/\.[^.]+$/', '', ($loop_file['name'])),
                                            'post_content' => '',
                                            'post_status' => 'inherit'
                                        );
                                        require_once( ABSPATH . 'wp-admin/includes/image.php' );
                                        // Insert the attachment.
                                        $attach_id = wp_insert_attachment($attachment, $status['file']);
                                        if ( $listing_id != '' ) {
                                            wp_update_post(
                                                    array(
                                                        'ID' => $attach_id,
                                                        'post_parent' => $listing_id
                                                    )
                                            );
                                        }
                                        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                                        $attach_data = wp_generate_attachment_metadata($attach_id, $status['file']);
                                        wp_update_attachment_metadata($attach_id, $attach_data);
                                        $listing_gallery[] = $attach_id;
                                        $count ++;
                                    }
                                }
                            }
                        }
                    }

                    $img_resized_name = $listing_gallery;
                } else {
                    $img_resized_name = '';
                }
            }

            return $img_resized_name;
        }

        /**
         * Date plus period
         * @return date
         */
        public function date_conv($duration, $format = 'days') {
            if ( $format == "months" ) {
                $adexp = date('Y/m/d H:i:s', strtotime("+" . absint($duration) . " months"));
            } else if ( $format == "years" ) {
                $adexp = date('Y/m/d H:i:s', strtotime("+" . absint($duration) . " years"));
            } else {
                $adexp = date('Y/m/d H:i:s', strtotime("+" . absint($duration) . " days"));
            }
            return $adexp;
        }

        /**
         * Array merge
         * @return Array
         */
        public function merge_in_array($array, $value = '', $with_array = true) {
            $ret_array = '';
            if ( is_array($array) && sizeof($array) > 0 && $value != '' ) {
                $array[] = $value;
                $ret_array = $array;
            } else if ( ! is_array($array) && $value != '' ) {
                $ret_array = $with_array ? array( $value ) : $value;
            }
            return $ret_array;
        }

        /**
         * Listing Tag Open
         * @return markup
         */
        public function listing_add_tag_before() {
            global $listing_add_counter;
            echo '<ul id="wp-dp-dev-main-con-' . $listing_add_counter . '">';
        }

        /**
         * Listing Tag Close
         * @return markup
         */
        public function listing_add_tag_after() {

            echo '</ul>';
        }

        /**
         * Steps before
         * @return markup
         */
        public function before_listing($html = '') {
            global $wp_dp_plugin_options, $Payment_Processing;
            $wp_dp_listing_announce_title = isset($wp_dp_plugin_options['wp_dp_listing_announce_title']) ? $wp_dp_plugin_options['wp_dp_listing_announce_title'] : '';
            $wp_dp_listing_announce_description = isset($wp_dp_plugin_options['wp_dp_listing_announce_description']) ? $wp_dp_plugin_options['wp_dp_listing_announce_description'] : '';
            $wp_dp_announce_bg_color = isset($wp_dp_plugin_options['wp_dp_announce_bg_color']) ? $wp_dp_plugin_options['wp_dp_announce_bg_color'] : '#2b8dc4';
            $listing_color = 'style="background-color:' . $wp_dp_announce_bg_color . '"';
            update_option('wooCommerce_current_page', wp_dp_server_protocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

            $wp_dp_order_data = $Payment_Processing->custom_order_status_display();
            if ( isset($wp_dp_order_data) && ! empty($wp_dp_order_data) ) {
                ?>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="field-holder">
                        <div class="user-message alert" <?php echo esc_html($listing_color); ?>>
                            <a href="#" data-dismiss="alert" class="close"><i class="icon-cross-out"></i></a>
                                <?php
                                global $woocommerce;
                                if ( class_exists('WooCommerce') ) {
                                    WC()->payment_gateways();
                                    echo force_balance_tagss('<h2>' . wp_dp_allow_special_char($wp_dp_order_data['status_message']) . '</h2>');
                                    do_action('woocommerce_thankyou_' . $wp_dp_order_data['payment_method'], $wp_dp_order_data['order_id']);
                                    $Payment_Processing->remove_raw_data($wp_dp_order_data['order_id']);
                                }
                                ?>
                        </div>
                    </div>
                </div>
                <?php
                $active = '';
            }

            $html .= '
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="field-holder">
					<div class="user-message alert" ' . $listing_color . '>
						<a href="#" data-dismiss="alert" class="close"><i class="icon-cross-out"></i></a>
						<h2>' . $wp_dp_listing_announce_title . '</h2>
						<p>' . htmlspecialchars_decode($wp_dp_listing_announce_description) . '</p>
					</div>				
				</div>
			</div>';
            echo force_balance_tags($html);
        }

        /**
         * Listing Submit Msg
         * @return markup
         */
        public function listing_submit_msg($msg = '', $type = 'success') {

            $html = '';
            if ( $msg != '' ) {
                $msg_arr = array( 'msg' => $msg, 'type' => $type );
                $msg_arr = json_encode($msg_arr);
                $html = '
				<script>
				jQuery(document).ready(function () {
					wp_dp_show_response(' . $msg_arr . ');
				});
				</script>';
            }
            echo force_balance_tags($html);
        }

        /**
         * Steps after
         * @return markup
         */
        public function after_listing($html = '') {
            global $listing_add_counter;
            $html .= '<li style="display: none;"><input type="hidden" name="form_rand_id" value="' . $listing_add_counter . '"></li>';
            echo force_balance_tags($html);
        }

        /**
         * Social Post
         * @return
         */
        public function social_post_after_activation($listing_id) {

            global $wp_dp_plugin_options;

            if ( $listing_id == '' ) {
                return;
            }

            $listing_post = get_post($listing_id);

            if ( is_object($listing_post) ) {
                $name = $listing_post->post_title;
                $name = apply_filters('the_title', $name);
                $name = html_entity_decode($name, ENT_QUOTES, get_bloginfo('charset'));
                $name = strip_tags($name);
                $name = strip_shortcodes($name);

                $content = $listing_post->post_content;
                $content = apply_filters('the_content', $content);
                $content = wp_kses($content, array());

                $description = $content;

                $excerpt = '';
                $caption = '';
                $user_nicename = '';

                $post_thumbnail_id = get_post_thumbnail_id($listing_id);
                $attachmenturl = '';
                if ( $post_thumbnail_id ) {
                    $attachmenturl = wp_get_attachment_url($post_thumbnail_id);
                }
                $link = get_permalink($listing_post->ID);
            } else {
                return;
            }

            // Twitter Posting Start
            $wp_dp_twitter_posting_switch = isset($wp_dp_plugin_options['wp_dp_twitter_autopost_switch']) ? $wp_dp_plugin_options['wp_dp_twitter_autopost_switch'] : '';

            if ( $wp_dp_twitter_posting_switch == 'on' ) {

                if ( ! class_exists('SMAPTwitterOAuth') ) {
                    require_once( dirname(__FILE__) . '/social-api/twitteroauth.php' );
                }

                $tappid = isset($wp_dp_plugin_options['wp_dp_consumer_key']) ? $wp_dp_plugin_options['wp_dp_consumer_key'] : '';
                $tappsecret = isset($wp_dp_plugin_options['wp_dp_consumer_secret']) ? $wp_dp_plugin_options['wp_dp_consumer_secret'] : '';
                $taccess_token = isset($wp_dp_plugin_options['wp_dp_access_token']) ? $wp_dp_plugin_options['wp_dp_access_token'] : '';
                $taccess_token_secret = isset($wp_dp_plugin_options['wp_dp_access_token_secret']) ? $wp_dp_plugin_options['wp_dp_access_token_secret'] : '';

                $post_twitter_image_permission = 1;

                $messagetopost = '{POST_TITLE} - {PERMALINK}{POST_CONTENT}';

                $img_status = "";
                if ( $post_twitter_image_permission == 1 ) {

                    $wp_remote_get_args = array(
                        'timeout' => 50,
                        'compress' => false,
                        'decompress' => true,
                    );

                    $img = array();
                    if ( $attachmenturl != "" )
                        $img = wp_remote_get($attachmenturl, $wp_remote_get_args);

                    if ( is_array($img) ) {
                        if ( isset($img['body']) && trim($img['body']) != '' ) {
                            $image_found = 1;
                            if ( ($img['headers']['content-length']) && trim($img['headers']['content-length']) != '' ) {
                                $img_size = $img['headers']['content-length'] / (1024 * 1024);
                                if ( $img_size > 3 ) {
                                    $image_found = 0;
                                    $img_status = "Image skipped(greater than 3MB)";
                                }
                            }

                            $img = $img['body'];
                        } else
                            $image_found = 0;
                    }
                }
                ///Twitter upload image end/////

                $messagetopost = str_replace("&nbsp;", "", $messagetopost);

                preg_match_all("/{(.+?)}/i", $messagetopost, $matches);
                $matches1 = $matches[1];
                $substring = "";
                $islink = 0;
                $issubstr = 0;
                $len = 118;
                if ( $image_found == 1 )
                    $len = $len - 24;

                foreach ( $matches1 as $key => $val ) {
                    $val = "{" . $val . "}";
                    if ( $val == "{POST_TITLE}" ) {
                        $replace = $name;
                    }
                    if ( $val == "{POST_CONTENT}" ) {
                        $replace = $description;
                    }
                    if ( $val == "{PERMALINK}" ) {
                        $replace = "{PERMALINK}";
                        $islink = 1;
                    }
                    if ( $val == "{POST_EXCERPT}" ) {
                        $replace = $excerpt;
                    }
                    if ( $val == "{BLOG_TITLE}" )
                        $replace = $caption;

                    if ( $val == "{USER_NICENAME}" )
                        $replace = $user_nicename;



                    $append = mb_substr($messagetopost, 0, mb_strpos($messagetopost, $val));

                    if ( mb_strlen($append) < ($len - mb_strlen($substring)) ) {
                        $substring.=$append;
                    } else if ( $issubstr == 0 ) {
                        $avl = $len - mb_strlen($substring) - 4;
                        if ( $avl > 0 )
                            $substring .= mb_substr($append, 0, $avl) . "...";

                        $issubstr = 1;
                    }



                    if ( $replace == "{PERMALINK}" ) {
                        $chkstr = mb_substr($substring, 0, -1);
                        if ( $chkstr != " " ) {
                            $substring.=" " . $replace;
                            $len = $len + 12;
                        } else {
                            $substring.=$replace;
                            $len = $len + 11;
                        }
                    } else {

                        if ( mb_strlen($replace) < ($len - mb_strlen($substring)) ) {
                            $substring.=$replace;
                        } else if ( $issubstr == 0 ) {

                            $avl = $len - mb_strlen($substring) - 4;
                            if ( $avl > 0 )
                                $substring .= mb_substr($replace, 0, $avl) . "...";

                            $issubstr = 1;
                        }
                    }
                    $messagetopost = mb_substr($messagetopost, mb_strpos($messagetopost, $val) + strlen($val));
                }

                if ( $islink == 1 )
                    $substring = str_replace('{PERMALINK}', $link, $substring);

                $twobj = new SMAPTwitterOAuth(array( 'consumer_key' => $tappid, 'consumer_secret' => $tappsecret, 'user_token' => $taccess_token, 'user_secret' => $taccess_token_secret, 'curl_ssl_verifypeer' => false ));

                if ( $image_found == 1 && $post_twitter_image_permission == 1 ) {
                    $resultfrtw = $twobj->request('POST', 'https://api.twitter.com/1.1/statuses/update_with_media.json', array( 'media[]' => $img, 'status' => $substring ), true, true);

                    if ( $resultfrtw != 200 ) {
                        if ( $twobj->response['response'] != "" )
                            $tw_publish_status["statuses/update_with_media"] = print_r($twobj->response['response'], true);
                        else
                            $tw_publish_status["statuses/update_with_media"] = $resultfrtw;
                    }
                }
                else {
                    $resultfrtw = $twobj->request('POST', $twobj->url('1.1/statuses/update'), array( 'status' => $substring ));

                    if ( $resultfrtw != 200 ) {
                        if ( $twobj->response['response'] != "" )
                            $tw_publish_status["statuses/update"] = print_r($twobj->response['response'], true);
                        else
                            $tw_publish_status["statuses/update"] = $resultfrtw;
                    }
                    else if ( $img_status != "" )
                        $tw_publish_status["statuses/update_with_media"] = $img_status;
                }
            }

            // Linkedin
            $lk_client_id = isset($wp_dp_plugin_options['wp_dp_linkedin_app_id']) ? $wp_dp_plugin_options['wp_dp_linkedin_app_id'] : '';
            $lk_secret_id = isset($wp_dp_plugin_options['wp_dp_linkedin_secret']) ? $wp_dp_plugin_options['wp_dp_linkedin_secret'] : '';
            $lk_posting_switch = isset($wp_dp_plugin_options['wp_dp_linkedin_autopost_switch']) ? $wp_dp_plugin_options['wp_dp_linkedin_autopost_switch'] : '';

            $lnpost_permission = 1;

            if ( $lk_posting_switch == 'on' && $lk_client_id != "" && $lk_secret_id != "" && $lnpost_permission == 1 ) {
                if ( ! class_exists('SMAPLinkedInOAuth2') ) {
                    require_once( dirname(__FILE__) . '/social-api/linkedin.php' );
                }

                $authorized_access_token = isset($wp_dp_plugin_options['wp_dp_linkedin_access_token']) ? $wp_dp_plugin_options['wp_dp_linkedin_access_token'] : '';

                $lmessagetopost = '{POST_TITLE} - {PERMALINK}{POST_CONTENT}';

                $contentln = array();

                $description_li = wp_dp_listing_string_limit($description, 362);
                $caption_li = wp_dp_listing_string_limit($caption, 200);
                $name_li = wp_dp_listing_string_limit($name, 200);

                $message1 = str_replace('{POST_TITLE}', $name, $lmessagetopost);
                $message2 = str_replace('{BLOG_TITLE}', $caption, $message1);
                $message3 = str_replace('{PERMALINK}', $link, $message2);
                $message4 = str_replace('{POST_EXCERPT}', $excerpt, $message3);
                $message5 = str_replace('{POST_CONTENT}', $description, $message4);
                $message5 = str_replace('{USER_NICENAME}', $user_nicename, $message5);

                $message5 = str_replace("&nbsp;", "", $message5);

                $contentln['comment'] = $message5;
                $contentln['content']['title'] = $name_li;
                $contentln['content']['submitted-url'] = $link;
                if ( $attachmenturl != "" ) {
                    $contentln['content']['submitted-image-url'] = $attachmenturl;
                }
                $contentln['content']['description'] = $description_li;

                $contentln['visibility']['code'] = 'anyone';

                $ln_publish_status = array();

                $ObjLinkedin = new SMAPLinkedInOAuth2($authorized_access_token);
                $contentln = wp_dp_linkedin_attachment_metas($contentln, $link);

                $arrResponse = $ObjLinkedin->shareStatus($contentln);
            }
            //
            // Facebook
            $fb_posting_switch = isset($wp_dp_plugin_options['wp_dp_facebook_autopost_switch']) ? $wp_dp_plugin_options['wp_dp_facebook_autopost_switch'] : '';

            $fb_app_id = isset($wp_dp_plugin_options['wp_dp_facebook_app_id']) ? $wp_dp_plugin_options['wp_dp_facebook_app_id'] : '';
            $fb_secret = isset($wp_dp_plugin_options['wp_dp_facebook_secret']) ? $wp_dp_plugin_options['wp_dp_facebook_secret'] : '';
            $fb_access_token = isset($wp_dp_plugin_options['wp_dp_facebook_access_token']) ? $wp_dp_plugin_options['wp_dp_facebook_access_token'] : '';

            if ( $fb_posting_switch == 'on' && $fb_app_id != "" && $fb_secret != "" && $fb_access_token != "" ) {
                $descriptionfb_li = wp_dp_listing_string_limit($description, 10000);

                if ( ! class_exists('SMAPFacebook') ) {
                    require_once( dirname(__FILE__) . '/social-api/facebook.php' );
                }
                $disp_type = 'feed';

                $lmessagetopost = '{POST_TITLE} - {PERMALINK}{POST_CONTENT}';

                $wp_dp_listing_pages_ids = get_option('wp_dp_fb_pages_ids');
                if ( $wp_dp_listing_pages_ids == "" ) {
                    $wp_dp_listing_pages_ids = -1;
                }

                $wp_dp_listing_pages_ids1 = explode(",", $wp_dp_listing_pages_ids);

                foreach ( $wp_dp_listing_pages_ids1 as $key => $value ) {
                    if ( $value != -1 ) {
                        $value1 = explode("-", $value);
                        $acces_token = $value1[1];
                        $page_id = $value1[0];

                        $fb = new SMAPFacebook(array(
                            'appId' => $fb_app_id,
                            'secret' => $fb_secret,
                            'cookie' => true
                        ));
                        $message1 = str_replace('{POST_TITLE}', $name, $lmessagetopost);
                        $message2 = str_replace('{BLOG_TITLE}', $caption, $message1);
                        $message3 = str_replace('{PERMALINK}', $link, $message2);
                        $message4 = str_replace('{POST_EXCERPT}', $excerpt, $message3);
                        $message5 = str_replace('{POST_CONTENT}', $description, $message4);
                        $message5 = str_replace('{USER_NICENAME}', $user_nicename, $message5);

                        $message5 = str_replace("&nbsp;", "", $message5);

                        $attachment = array(
                            'message' => $message5,
                            'access_token' => $acces_token,
                            'link' => $link,
                            'name' => $name,
                            'caption' => $caption,
                            'description' => $descriptionfb_li,
                            'actions' => array(
                                array(
                                    'name' => $name,
                                    'link' => $link
                                )
                            ),
                            'picture' => $attachmenturl
                        );

                        $attachment = wp_dp_fbapp_attachment_metas($attachment, $link);

                        $result = $fb->api('/' . $page_id . '/' . $disp_type . '/', 'post', $attachment);
                    }
                }
            }
            //
        }

    }

    $wp_dp_member_listing_actions = new wp_dp_member_listing_actions();
}
