<?php
/*
  Plugin Name: WP Directorybox Manager
  Plugin URI: http://themeforest.net/user/Chimpstudio/
  Description: WP Directorybox Manager
  Version: 1.9
  Author: ChimpStudio
  Text Domain: wp-dp
  Author URI: http://themeforest.net/user/Chimpstudio/
  License: GPL2
  Copyright 2020  chimpgroup  (email : info@chimpstudio.co.uk)
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, United Kingdom
 */
if (!class_exists('wp_dp')) {

    class wp_dp {

	public $plugin_url;
	public $plugin_dir;
	public static $wp_dp_version;
	public static $wp_dp_data_update_flag;

	/**
	 * Start Function of Construct
	 */
	public function __construct() {

	    self::$wp_dp_version = '1.9';
	    self::$wp_dp_data_update_flag = 'wp_dp_old_data_update_flag_' . str_replace(".", "_", self::$wp_dp_version);
	    add_action('init', array($this, 'load_plugin_textdomain'), 0);
	    add_action('wp_head', array($this, 'wp_dp_demo_user_auto_login'), 2);
	    remove_filter('pre_user_description', 'wp_filter_kses');
	    add_filter('pre_user_description', 'wp_filter_post_kses');
	    // Add optinos in Email Template Settings
	    add_filter('wp_dp_email_template_settings', array($this, 'email_template_settings_callback'), 0, 1);
	    add_filter('wp_dp_get_plugin_options', array($this, 'wp_dp_get_plugin_options_callback'), 0, 1);
	    add_action('admin_menu', array($this, 'admin_menu_position'));
	    add_action('wp_footer', array($this, 'wp_dp_loader'));
	    add_action('admin_footer', array($this, 'wp_dp_admin_footer_modal'));
	    $this->define_constants();
	    $this->includes();
	    add_action('admin_head', array($this, 'hide_update_notice_for_wp_dp_pages'), 11);
	    add_action('admin_notices', array($this, 'check_db_update_listing_visibility'), 5);
	    add_action('wp_ajax_wp_dp_listing_visibility_db_update', array($this, 'wp_dp_listing_visibility_db_update_callback'));
	    add_action('wp_ajax_nopriv_wp_dp_listing_visibility_db_update', array($this, 'wp_dp_listing_visibility_db_update_callback'));
	    add_action('wp_dp_importer_db_update', array($this, 'wp_dp_importer_db_update_callback'));
	    add_action('wp_logout', array($this, 'wp_logout_callback'), 1);

	    add_action('admin_menu', array($this, 'wp_dp_add_directory_menu'), 0);
	    add_action('admin_menu', array($this, 'wp_dp_add_directory_submenu'), 24);
            
            
	}

	//wp_dp_directory_menu_title
	public function wp_dp_add_directory_menu() {
	    add_menu_page(wp_dp_plugin_text_srt('wp_dp_directory_menu_title'), wp_dp_plugin_text_srt('wp_dp_directory_menu_title'), 'manage_options', 'wp_dp_directory', array($this, 'directory_menu_callback'), plugins_url('/assets/backend/images/directory-icon.png', __FILE__), 2);
	    add_submenu_page('wp_dp_directory', '', '', 'manage_options', 'wp_dp_directory', array($this, 'directory_menu_callback'));
	    remove_submenu_page('wp_dp_directory', 'wp_dp_directory');
	}

	public function directory_menu_callback() {
	    
	}

	public function wp_dp_add_directory_submenu() {
	    global $submenu;
	    add_submenu_page('wp_dp_directory', wp_dp_plugin_text_srt('wp_dp_directory_menu_customization'), wp_dp_plugin_text_srt('wp_dp_directory_menu_customization'), 'manage_options', 'wp_dp_customizaion_request', array($this, 'customization_request_callback'));
	    add_submenu_page('wp_dp_directory', wp_dp_plugin_text_srt('wp_dp_directory_menu_documentation'), wp_dp_plugin_text_srt('wp_dp_directory_menu_documentation'), 'manage_options', 'wp_dp_documentation', array($this, 'documentation_callback'));
	}

	public function customization_request_callback() {
	    global $submenu;
	    echo '<pre>';
	    print_r($submenu['wp_dp_directory'][4]);
	}

	public function recommended_plugins_callback() {
	    global $submenu;
	    echo '<pre>';
	    print_r($submenu['wp_dp_directory'][5]);
	}

	public function documentation_callback() {
	    global $submenu;
	    echo '<pre>';
	    print_r($submenu['wp_dp_directory'][6]);
	}

	/*
	 * Logout User and save the cookie for not auto login
	 */

	public function wp_logout_callback() {
	    global $wp_dp_plugin_options;
	    $user_id = get_current_user_id();
	    $wp_dp_demo_user_agency = isset($wp_dp_plugin_options['wp_dp_demo_user_agency']) ? $wp_dp_plugin_options['wp_dp_demo_user_agency'] : '';
        //if ($user_id == $wp_dp_demo_user_agency) {
		wp_dp_set_transient_obj('user_logged_out', 'yes');
	    //}
	}

	/*
	 * auto login for demo user
	 */

	public function wp_dp_demo_user_auto_login() {
	    if (!is_admin()) {
		global $wp_dp_plugin_options;
		$wp_dp_demo_user_login_switch = isset($wp_dp_plugin_options['wp_dp_demo_user_login_switch']) ? $wp_dp_plugin_options['wp_dp_demo_user_login_switch'] : '';
		$wp_dp_demo_user_agency = isset($wp_dp_plugin_options['wp_dp_demo_user_agency']) ? $wp_dp_plugin_options['wp_dp_demo_user_agency'] : '';
		$user = get_userdata($wp_dp_demo_user_agency);
		if ($wp_dp_demo_user_agency != '' && is_numeric($wp_dp_demo_user_agency) && $user != false) {
		    $user_logout_flag = wp_dp_get_transient_obj('user_logged_out');
		    if ($wp_dp_demo_user_login_switch == 'on' && $user_logout_flag != 'yes' && !is_user_logged_in()) {
			$wp_dp_demo_agency_detail = get_user_by('id', $wp_dp_demo_user_agency);
			$wp_dp_demo_agency_detail_user = isset($wp_dp_demo_agency_detail->user_login) ? $wp_dp_demo_agency_detail->user_login : '';
			ajax_login(array('user_login' => $wp_dp_demo_agency_detail_user));
		    }
		}
	    }
	}

	/*
	 * Update plugin versio
	 */

	private static function update_wp_dp_plugin_version() {
	    if ((get_option('wp_dp_plugin_version') == '') || ( get_option('wp_dp_plugin_version') !== wp_dp::get_plugin_version() )) {
		delete_option('wp_dp_plugin_version');
		add_option('wp_dp_plugin_version', wp_dp::get_plugin_version());
	    }
	}

	/*
	 * update all data for listing visibility
	 */

	public function wp_dp_listing_visibility_db_update_callback() {
	    $args_count = array(
		'posts_per_page' => "-1",
		'post_type' => 'listings',
		'post_status' => 'publish',
		'fields' => 'ids', // only load ids
		'meta_query' => array(
		    'relation' => 'OR',
		    array(
			'key' => 'wp_dp_listing_visibility',
			'compare' => 'NOT EXISTS'
		    ),
		    array(
			'key' => 'wp_dp_listing_visibility',
			'compare' => '=',
			'value' => ''
		    )
		)
	    );
	    $msg = '';
	    $listing_loop_obj = wp_dp_get_cached_obj('listing_visibility_all_result_cached_loop_obj', $args_count, 12, false, 'wp_query');
	    if ($listing_loop_obj->have_posts()) {
		while ($listing_loop_obj->have_posts()) : $listing_loop_obj->the_post();
		    global $post;
		    $listing_id = $post;
		    $wp_dp_listing_member = update_post_meta($listing_id, 'wp_dp_listing_visibility', 'public');
		endwhile;
	    }
	    do_action('wp_dp_plugin_db_structure_updater');
	    add_option(self::$wp_dp_data_update_flag, 'yes');
	    $json['type'] = 'success';
	    $json['msg'] = wp_dp_plugin_text_srt('wp_dp_listing_visibility_updated_msg');
	    echo json_encode($json);
	    die();
	}

	/*
	 * importer update all data for listing visibility
	 */

	public function wp_dp_importer_db_update_callback() {
	    $args_count = array(
		'posts_per_page' => "-1",
		'post_type' => 'listings',
		'post_status' => 'publish',
		'fields' => 'ids',
		'meta_query' => array(
		    'relation' => 'OR',
		    array(
			'key' => 'wp_dp_listing_visibility',
			'compare' => 'NOT EXISTS'
		    ),
		    array(
			'key' => 'wp_dp_listing_visibility',
			'compare' => '=',
			'value' => ''
		    )
		)
	    );
	    $listing_loop_obj = wp_dp_get_cached_obj('listing_visibility_all_result_cached_loop_obj', $args_count, 12, false, 'wp_query');
	    if ($listing_loop_obj->have_posts()) {
		while ($listing_loop_obj->have_posts()) : $listing_loop_obj->the_post();
		    global $post;
		    $listing_id = $post;
		    $wp_dp_listing_member = update_post_meta($listing_id, 'wp_dp_listing_visibility', 'public');
		endwhile;
	    }
	    add_option(self::$wp_dp_data_update_flag, 'yes');
	}

	/*
	 * Update db hook
	 */

	public static function check_db_update_listing_visibility() {
	    global $wp_dp_Class;
	    $purchase_code_data = get_option('item_purchase_code_verification');
	    $envato_email = isset($purchase_code_data['envato_email_address']) ? $purchase_code_data['envato_email_address'] : '';
	    $selected_demo = isset($purchase_code_data['selected_demo']) ? $purchase_code_data['selected_demo'] : '';
	    $demos_array = array();
	    $options = "<option value=''>Pleae select a demo you are using right now</option>";
	    if (function_exists('get_demo_data_structure')) {
		$demos = get_demo_data_structure();
	    }

	    if (!empty($demos)) {
		foreach ($demos as $demo_key => $demo_value) {
		    $demos_array[$demo_key] = $demo_key;
		    $demo_slug = isset($demo_value['slug']) ? $demo_value['slug'] : '';
		    $demo_name = isset($demo_value['name']) ? $demo_value['name'] : '';
		    $selected = ( $demo_slug == $selected_demo ) ? ' selected' : '';
		    $options .= "<option value='" . $demo_slug . "'" . $selected . ">" . $demo_name . "</option>";
		}
	    }
	    //$item_purchase_code    = isset( $purchase_code_data['item_puchase_code'] )? $purchase_code_data['item_puchase_code'] : '';
	    if (get_option(self::$wp_dp_data_update_flag) !== 'yes') {

		$class = 'notice notice-warning is-dismissible';
		$popup_fields = '';
		$popup_message = '<h1 style=\'color: #ff2e2e; margin-top: 0; float: none;\'>Warning!!!</h1> By upgrading it will take some time. So please wait after move next:<br>';

		if (class_exists('wp_dp_framework')) {

		    $popup_fields = "<div id=\'confirmText\' style=\'padding-left: 20px; padding-right: 20px;\'><div class='row'>\
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>\
                            <div class='field-holder'>\
                                    <input type='text' placeholder='Envato Provided Email *' id='envato_email' name='envato_email' value='" . $envato_email . "'>\
                            </div>\
                        </div>\
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>\
                            <div class='field-holder'>\
                                    <select name='theme_demo' class='chosen-select' id='theme_demo'>" . $options . "</select>\
                            </div>\
                        </div>\
                    </div></div>";
		}

		$popup = '
						<script type="text/javascript">
                                                        
							var html_popup1 = "<div id=\'confirmOverlay\' style=\'display:block\'><div id=\'confirmBox\' class=\'update-popup-box\'>";
							html_popup1 += "<div id=\'confirmText\' style=\'padding-left: 20px; padding-right: 20px;\'>' . $popup_message . '</div>";
							html_popup1 += "' . $popup_fields . '";
							html_popup1 += "<div id=\'confirmButtons\'><div class=\'button confirm-yes\'>Upgrade</div><div class=\'button confirm-no\'>Cancel</div><br class=\'clear\'></div><div id=\'listing-visibility-update-msg\'></div></div></div>";
							
							(function($){
								$(function() {
									$(".btnConfirmVisibleListingUpgrade").click(function() {
										$(this).parent().append(html_popup1);
										$(".confirm-yes").click(function() {

												//start ajax request
												var old_html =  $(".confirm-yes").html();
												var theme_demo = $("#theme_demo").val();

												var envato_email = $("#envato_email").val();

												var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,50}\b$/i;
												var result = pattern.test(envato_email);
												if( envato_email != "" && result == false){
													alert("Please provide valid email address.");
													return false;
												}
												$(".confirm-yes").html("<i class=\'icon-spinner\' style=\'margin:13px 0 0 -5px;\'></i>");
												$.ajax({
													type: "POST",
													dataType: "json",
													url: wp_dp_globals.ajax_url,
													data: "envato_email="+envato_email+"&theme_demo="+theme_demo+"&action=wp_dp_listing_visibility_db_update",
													success: function (response) {
													  $(".confirm-yes").html(old_html);
													  $("#listing-visibility-update-msg").html("<p style=\'color: #008000;padding-left: 20px; padding-right: 20px;\'>" + response.msg + "</p>");
													}
												});

												// end ajax request

										});
										$(".confirm-no").click(function() {
												$("#confirmOverlay").remove();
												window.location = window.location;
										});
										return false;
									});
								});
							})(jQuery);
						</script>';
		$message = '<h2>Directory Box Alert!</h2>';
		$message .= 'DB Structure Need to update for latest plugin compatibility. <br/><br/> <a href="#" class="btnConfirmVisibleListingUpgrade button button-primary button-hero load-customize hide-if-no-customize">Click here to run update</a> ' . $popup;
		printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
	    }
	}

	/**
	 * Start Function how to Create WC Contants
	 */
	private function define_constants() {

	    global $post, $wp_query, $wp_dp_plugin_options, $current_user, $wp_dp_jh_scodes, $plugin_user_images_wp_dp;
	    $wp_dp_plugin_options = get_option('wp_dp_plugin_options');
	    require_once 'backend/classes/class-translate-options.php';
	    $wp_dp_plugin_options = apply_filters('wp_dp_translate_options', $wp_dp_plugin_options);
	    $this->plugin_url = plugin_dir_url(__FILE__);
	    $this->plugin_dir = plugin_dir_path(__FILE__);
	    $plugin_user_images_wp_dp = 'wp-dp-users';
	}

	/**
	 * What type of request is this?
	 * string $type ajax, frontend or admin
	 * @return bool
	 */
	/*
	 * remove admin notices
	 */
	public function hide_update_notice_for_wp_dp_pages() {
	    $screen = get_current_screen();
	    $post_type_screen = isset($screen->post_type) ? $screen->post_type : '';
	    $argss = array(
		'public' => true,
		'_builtin' => false
	    );
	    $output = 'names'; // names or objects, note names is the default
	    $operator = 'and';
	    $all_custom_post_types = get_post_types($argss, $output, $operator);

	    if ($post_type_screen != '' && in_array($post_type_screen, $all_custom_post_types)) {
		global $wp_filter;
		remove_action('admin_notices', 'update_nag', 3);
		unset($wp_filter['user_admin_notices']);
		unset($wp_filter['admin_notices']);
	    }
	}

	public function is_request($type) {
	    switch ($type) {
		case 'admin' :
		    return is_admin();
		    break;
		case 'ajax' :
		    return defined('DOING_AJAX');
		case 'cron' :
		    return defined('DOING_CRON');
		case 'frontend' :
		    return (!is_admin() || defined('DOING_AJAX') ) && !defined('DOING_CRON');
	    }
	}

	/*
	 * WP Directorybox Manager Error Messages Popup in Footer for admin
	 *
	 */

	public function wp_dp_admin_footer_modal() {
	    echo '<div class="wp-dp-error-messages" style="display:none;"></div>';
	}

	/**
	 * WP Directorybox Manager Loader in Footer
	 */
	public function wp_dp_loader() {
	    echo '<div class="wp_dp_loader" style="display: none;">';
	    echo '<div class="loader-img"><i class="fancy-spinner"></i></div></div>';
	    echo '<div class="wp-dp-button-loader spinner">
                                    <div class="bounce1"></div>
                                      <div class="bounce2"></div>
                                      <div class="bounce3"></div>
                                  </div>';
	    if (is_user_logged_in()) {
		if (!wp_dp::is_demo_user_modification_allowed()) :
		    ?>
		    <script type="text/javascript">
		        var pageInitialized = false;
		        (function ($) {
		    	$(document).ready(function () {
		    	    bind_rest_auth_event();
		    	    $("body").on("DOMNodeInserted DOMNodeRemoved", bind_rest_auth_event);
		    	    if (pageInitialized)
		    		return;
		    	    jQuery.growl.error({
		    		message: '<?php echo wp_dp_plugin_text_srt('wp_dp_demo_user_not_allowed_to_modify'); ?>'
		    	    });
		    	    pageInitialized = true;
		    	});

		    	function bind_rest_auth_event() {
		    	    $("input[type='submit'], .btn-submit, .btn-send, .review-reply-btn, .review-listing .delete-this-user-review, .delete-shortlist, .remove_member, #team_update_form, .wp-dp-dev-listing-delete, .discussion-submit, .viewing-request-holder input, .delete-hidden-listing, .listing-visibility-update, .sold-listing-box label, #send_your_review").off("click");
		    	    $(document).off("click", "input[type='submit'], .btn-submit, .btn-send, .review-reply-btn, .review-listing .delete-this-user-review, .delete-shortlist, .remove_member, #team_update_form, .wp-dp-dev-listing-delete, .discussion-submit, .viewing-request-holder input, .delete-hidden-listing, .listing-visibility-update, .sold-listing-box label, #send_your_review");
		    	    $("body").off("click", "input[type='submit'], .btn-submit, .btn-send, .review-reply-btn, .review-listing .delete-this-user-review, .delete-shortlist, .remove_member, #team_update_form, .wp-dp-dev-listing-delete, .discussion-submit, .viewing-request-holder input, .delete-hidden-listing, .listing-visibility-update, .sold-listing-box label, #send_your_review");
		    	    $(".delete-this-user-review").click(function (e) {
		    		e.stopPropagation();
		    		e.preventDefault();
		    		e.stopImmediatePropagation();
		    		jQuery.growl.error({
		    		    message: '<?php echo wp_dp_plugin_text_srt('wp_dp_demo_user_not_allowed_to_modify'); ?>'
		    		});
		    		return false;
		    	    });
		    	    $("body").on("click", "input[type='submit'], .btn-submit, .btn-send, .review-listing .delete-this-user-review, .delete-shortlist, .remove_member, #team_update_form, .wp-dp-dev-listing-delete, .discussion-submit, .viewing-request-holder input, .delete-hidden-listing, .listing-visibility-update, .sold-listing-box label, .review-reply-btn, #send_your_review", function (e) {
		    		e.stopPropagation();
		    		e.preventDefault();
		    		e.stopImmediatePropagation();
		    		jQuery.growl.error({
		    		    message: '<?php echo wp_dp_plugin_text_srt('wp_dp_demo_user_not_allowed_to_modify'); ?>'
		    		});
		    		return false;
		    	    });
		    	}
		        })(jQuery);
		    </script>
		    <?php
		endif;

		if (!wp_dp::is_demo_user_modification_allowed_frontend()) :
		    ?>
		    <script type="text/javascript">
		        (function ($) {
		    	$(document).ready(function () {
		    	    bind_rest_auth_event_frontend();
		    	    $("body").on("DOMNodeInserted DOMNodeRemoved", bind_rest_auth_event_frontend);
		    	    $('.listing-hide-opt .hide-btn').attr('onclick', '');
		    	    //$('.listing-note-opt .listing-notes')
		    	});

		    	function bind_rest_auth_event_frontend() {
		    	    $(".viewing-request-holder input[type='submit'], .enquiry-request-holder input[type='submit'], .listing-hide-opt .hide-btn, .submit-prop-notes, #send_your_review").off("click");
		    	    $(document).off("click", ".viewing-request-holder input[type='submit'], .enquiry-request-holder input[type='submit'], .listing-hide-opt .hide-btn, .submit-prop-notes, #send_your_review");
		    	    $("body").off("click", ".viewing-request-holder input[type='submit'], .enquiry-request-holder input[type='submit'], .listing-hide-opt .hide-btn, .submit-prop-notes, #send_your_review");
		    	    $("body").on("click", ".viewing-request-holder input[type='submit'], .enquiry-request-holder input[type='submit'], .listing-hide-opt .hide-btn, .submit-prop-notes, #send_your_review", function (e) {
		    		e.stopPropagation();
		    		e.stopImmediatePropagation();
		    		jQuery.growl.error({
		    		    message: '<?php echo wp_dp_plugin_text_srt('wp_dp_demo_user_not_allowed_to_modify'); ?>'
		    		});
		    		return false;
		    	    });
		    	}
		        })(jQuery);
		    </script>
		    <?php
		endif;
	    }
	}

	public static function is_demo_user_modification_allowed_frontend() {
	    global $wp_dp_plugin_options, $post;
	    $current_page = isset($post->ID) ? $post->ID : '';
	    $create_listing_page = isset($wp_dp_plugin_options['wp_dp_create_listing_page']) ? $wp_dp_plugin_options['wp_dp_create_listing_page'] : '';
	    $wp_dp_demo_user_login_switch = isset($wp_dp_plugin_options['wp_dp_demo_user_login_switch']) ? $wp_dp_plugin_options['wp_dp_demo_user_login_switch'] : '';
	    if ($wp_dp_demo_user_login_switch == 'on') {
		$wp_dp_wp_dp_demo_user_member = isset($wp_dp_plugin_options['wp_dp_demo_user_member']) ? $wp_dp_plugin_options['wp_dp_demo_user_member'] : '';
		$wp_dp_wp_dp_demo_user_agency = isset($wp_dp_plugin_options['wp_dp_demo_user_agency']) ? $wp_dp_plugin_options['wp_dp_demo_user_agency'] : '';
		$current_user_id = get_current_user_id();
		if ($wp_dp_wp_dp_demo_user_member == $current_user_id || $wp_dp_wp_dp_demo_user_agency == $current_user_id) {
		    if (isset($wp_dp_plugin_options['wp_dp_demo_user_modification_allowed_switch']) && $wp_dp_plugin_options['wp_dp_demo_user_modification_allowed_switch'] == 'off') {
			return false;
		    }
		}
	    }
	    return true;
	}

	public static function is_demo_user_modification_allowed() {
	    global $wp_dp_plugin_options, $post;
	    $current_page = isset($post->ID) ? $post->ID : '';
	    $create_listing_page = isset($wp_dp_plugin_options['wp_dp_create_listing_page']) ? $wp_dp_plugin_options['wp_dp_create_listing_page'] : '';
	    if ('member-dashboard.php' === wp_dp_get_current_template() || '' === wp_dp_get_current_template() || $current_page == $create_listing_page) {
		$wp_dp_demo_user_login_switch = isset($wp_dp_plugin_options['wp_dp_demo_user_login_switch']) ? $wp_dp_plugin_options['wp_dp_demo_user_login_switch'] : '';
		if ($wp_dp_demo_user_login_switch == 'on') {
		    $wp_dp_wp_dp_demo_user_member = isset($wp_dp_plugin_options['wp_dp_demo_user_member']) ? $wp_dp_plugin_options['wp_dp_demo_user_member'] : '';
		    $wp_dp_wp_dp_demo_user_agency = isset($wp_dp_plugin_options['wp_dp_demo_user_agency']) ? $wp_dp_plugin_options['wp_dp_demo_user_agency'] : '';
		    $current_user_id = get_current_user_id();
		    if ($wp_dp_wp_dp_demo_user_member == $current_user_id || $wp_dp_wp_dp_demo_user_agency == $current_user_id) {
			if (isset($wp_dp_plugin_options['wp_dp_demo_user_modification_allowed_switch']) && $wp_dp_plugin_options['wp_dp_demo_user_modification_allowed_switch'] == 'off') {
			    return false;
			}
		    }
		}
	    }
	    return true;
	}

	/**
	 * Start Function how to add core files used in admin and theme
	 */
	public function includes() {




	    /*
	     * Strings Class
	     */
	    require_once 'assets/common/translate/class-strings-1.php';
	    require_once 'assets/common/translate/class-strings-2.php';
	    require_once 'assets/common/translate/class-strings-3.php';
	    require_once 'assets/common/translate/class-strings-4.php';
	    require_once 'assets/common/translate/class-strings-5.php';

	    /*
	     * Email Templates.
	     */

	    // Member 
	    require_once 'backend/classes/email-templates/member/class-register-template.php';
	    require_once 'backend/classes/email-templates/member/class-new-member-notification-site-owner-template.php';
	    require_once 'backend/classes/email-templates/member/class-change-password-template.php';
	    require_once 'backend/classes/email-templates/member/class-confirm-reset-password-template.php';
	    require_once 'backend/classes/email-templates/member/class-approved-member-profile-template.php';
	    require_once 'backend/classes/email-templates/member/class-not-approved-member-profile-template.php';


	    // Messages
	    require_once 'backend/classes/email-templates/messages/class-received-enquiry-template.php';
	    //require_once 'backend/classes/email-templates/messages/class-update-enquiry-status-template.php';
	    require_once 'backend/classes/email-templates/messages/class-received-enquiry-reply-template.php';

	    // Listings
	    require_once 'backend/classes/email-templates/listings/class-listing-add-template.php';
	    require_once 'backend/classes/email-templates/listings/class-listing-update-email-template.php';
	    require_once 'backend/classes/email-templates/listings/class-listing-approved-email-template.php';
	    require_once 'backend/classes/email-templates/listings/class-listing-not-approved-email-template.php';
	    require_once 'backend/classes/email-templates/listings/class-listing-pending-email-template.php';
	    require_once 'backend/classes/email-templates/listings/class-listing-expired-template.php';
	    require_once 'backend/classes/email-templates/listings/class-listing-claim-template.php';
	    require_once 'backend/classes/email-templates/listings/class-flag-listing-template.php';
	    require_once 'backend/classes/email-templates/listings/class-favourite-listing-template.php';


	    // Reviews Email Templates
	    require_once 'backend/classes/email-templates/reviews/class-review-received-template.php';
	    require_once 'backend/classes/email-templates/reviews/class-review-reply-template.php';
	    require_once 'backend/classes/email-templates/reviews/class-review-approved-template.php';

	    require_once 'frontend/classes/class-radius-check.php';
	    require_once 'frontend/classes/class-reviews-manager.php';
	    /*
	     * Include admin files
	     */

	    /*
	     * Form Fields Class
	     */
	    require_once 'backend/classes/form-fields/class-form-fields.php';
	    require_once 'backend/classes/form-fields/class-html-fields.php';
	    /*
	     * Form Fields Classes Frontend
	     */
	    require_once 'frontend/classes/form-fields/class-form-fields.php';
	    require_once 'frontend/classes/form-fields/class-html-fields.php';

	    /*
	     * Payment Gateways Files
	     */
	    require_once 'payments/class-payments.php';
	    require_once 'payments/custom-wooc-hooks.php';
	    require_once 'payments/config.php';

	    // importer hooks
	    require_once 'backend/include/importer-hooks.php';

	    /*
	     * Email Class
	     */
	    require_once 'backend/classes/class-email.php';

	    require_once 'backend/post-types/listings/listings.php';
	    require_once 'backend/post-types/comments/comments.php';

	    /*
	     * Helpers Classes
	     */
	    require_once 'helpers/helpers-notification.php';
	    require_once 'helpers/helpers-general.php';

	    /*
	     * Shortcode File
	     * Other files are being added into this file.
	     */
	    // for login
	    require_once 'elements/login/login-functions.php';
	    require_once 'elements/login/login-forms.php';
	    require_once 'elements/login/cs-social-login/cs-social-login.php';
	    require_once 'elements/login/cs-social-login/google/cs_google_connect.php';
	    // linkedin login
	    // recaptchas
	    require_once 'elements/login/recaptcha/autoload.php';

	    require_once 'shortcodes/backend/class-parent-shortcode.php';
	    require_once 'shortcodes/class-shortcodes.php';
	    require_once 'shortcodes/shortcode-include.php';

	    // listing add shortcde files
	    //require_once 'shortcodes/backend/wp-dp-add-listing.php';
	    //require_once 'shortcodes/frontend/wp-dp-add-listing.php';
	    /*
	     * shortcodes
	     */
	    // banners shortcode
	    require_once 'shortcodes/frontend/shortcode-banner-ads.php';

	    // map search
	    require_once 'shortcodes/backend/shortcode-map-search.php';
	    require_once 'shortcodes/frontend/shortcode-map-search.php';

	    /*
	     * Compare Listings
	     */
	    //require_once 'frontend/templates/compare/class-compare-listing.php';

	    /*
	     * social sharing Class
	     */
	    require_once 'frontend/classes/class-social-sharing.php';
	    /*
	     * social sharing Class
	     */

	    /*
	     * Search Fields Class
	     */
	    require_once 'frontend/classes/class-search-fields.php';
	    /*
	     * Split Map Search Fields Class
	     */
	    require_once 'frontend/classes/class-split-map-search-fields.php';

	    /*
	     * Order/Inquiry Detail Class
	     */
	    require_once 'frontend/classes/class-enquiry-detail.php';


	    /*
	     * Transaction Detail Class
	     */
	    require_once 'frontend/classes/class-transaction-detail.php';

	    /*
	     * pagination sharing Class
	     */
	    require_once 'frontend/classes/class-pagination.php';

	    /*
	     * Member Account Pages
	     */
	    require_once 'frontend/templates/dashboards/class-dashboards.php';
	    require_once 'frontend/templates/dashboards/member/member-add-listing.php';

	    require_once 'frontend/templates/payment-process-center.php';

	    /*
	     * Member Account Pages
	     */
	    require_once 'frontend/templates/dashboards/member/member-listings.php';
	    require_once 'frontend/templates/dashboards/member/member-profile.php';
	    require_once 'frontend/templates/dashboards/member/member-company.php';
	    require_once 'frontend/templates/dashboards/member/member-packages.php';
	    require_once 'frontend/templates/dashboards/member/member-transactions.php';
	    require_once 'frontend/templates/dashboards/member/member-listing-enquires.php';
	    require_once 'frontend/templates/dashboards/member/member-suggested.php';
	    require_once 'frontend/templates/dashboards/member/member-branches.php';
	    require_once 'frontend/templates/dashboards/member/member-create-listing.php';

	    /*
	     * listings Post type classes for fields
	     */
	    require_once 'backend/post-types/class-save-post-options.php';
	    require_once 'backend/post-types/listings/classes/class-listings-opening-hours.php';
	    require_once 'backend/post-types/listings/classes/class-listings-posted-by.php';
	    require_once 'backend/post-types/listings/classes/class-listings-images-gallery.php';
	    require_once 'backend/post-types/listings/classes/class-listings-floor-plan.php';
	    require_once 'backend/post-types/listings/classes/class-listings-attachments.php';
	    require_once 'backend/post-types/listings/classes/class-listings-page-elements.php';
	    require_once 'backend/post-types/listings/listings-meta.php';
	    require_once 'backend/post-types/listings/listing-taxonomy-mata.php';

	    /*
	     * listing-type Post type classes for fields
	     */
	    require_once 'backend/post-types/listing-type/listing-type.php';
	    require_once 'backend/post-types/listing-type/listing-type-fields.php';
	    require_once 'backend/post-types/listing-type/listing-type-meta.php';
	    require_once 'backend/post-types/listing-type/classes/class-listing-type-categories.php';
	    require_once 'backend/post-types/listings/classes/class-listings-faqs.php';



	    /*
	     * members Post type classes for fields
	     */

	    require_once 'backend/post-types/members/members.php';
	    require_once 'backend/post-types/members/members-meta.php';

	    /*
	     * Packages Post type classes for fields
	     * @Used as hooks
	     */
	    require_once 'backend/post-types/packages/packages.php';
	    require_once 'backend/post-types/packages/packages-meta.php';

	    require_once 'backend/post-types/transactions/transactions.php';
	    require_once 'backend/post-types/transactions/transactions-meta.php';


	    require_once 'backend/post-types/promotions/promotions.php';
	    require_once 'backend/post-types/promotions/promotions-meta.php';


	    // Branches Post Type
	    require_once 'backend/post-types/branches/branches.php';
	    require_once 'backend/post-types/branches/branches-meta.php';

	    /*
	     * Listing Enquires Post type classes for fields
	     * @Used as hooks
	     */
	    require_once 'backend/post-types/listing-enquiries/listing-enquiries.php';
	    require_once 'backend/post-types/listing-enquiries/listing-enquiries-meta.php';

	    /*
	     * Price Table Post type classes for fields
	     * @Files
	     */
	    require_once 'backend/post-types/price-tables/price-table.php';
	    require_once 'backend/post-types/price-tables/price-table-meta.php';

	    /*
	     * Form Fields Classes
	     */
	    require_once 'backend/classes/form-fields/class-form-fields.php';
	    require_once 'backend/classes/form-fields/class-html-fields.php';

	    require_once 'frontend/templates/functions.php';

	    /*
	     * User Meta
	     */
	    require_once 'backend/include/user-meta/meta.php';


	    /*
	     * Plugin Settings Classes
	     */

	    require_once 'backend/settings/plugin-settings.php';
	    require_once 'backend/settings/includes/plugin-options.php';
	    require_once 'backend/settings/includes/plugin-options-fields.php';
	    require_once 'backend/settings/includes/plugin-options-functions.php';
	    require_once 'backend/settings/includes/plugin-options-array.php';
	    require_once 'backend/settings/user-import/import.php';

	    /*
	     * Transactions Files
	     */
	    require_once 'backend/post-types/package-orders/package-orders.php';
	    require_once 'backend/post-types/package-orders/package-orders-meta.php';

	    /*
	     * Include frontend files
	     */

	    /*
	     * Listing Page Elements Classes
	     */
	    require_once 'frontend/classes/page-elements/class-sub-navbar.php';
	    require_once 'frontend/classes/page-elements/class-features-element.php';
	    require_once 'frontend/classes/page-elements/class-opening-hours-element.php';
	    require_once 'frontend/classes/page-elements/class-images-gallery-element.php';
	    require_once 'frontend/classes/page-elements/class-contact-element.php';
	    require_once 'frontend/classes/page-elements/class-discussion-element.php';
	    require_once 'frontend/classes/page-elements/class-custom-fields-element.php';
	    require_once 'frontend/classes/page-elements/class-enquire-arrange-buttons.php';
	    require_once 'frontend/classes/page-elements/class-payment-calculator-element.php';
	    require_once 'frontend/classes/page-elements/class-author-info-element.php';
	    require_once 'frontend/classes/page-elements/class-sidebar-gallery-element.php';
	    require_once 'frontend/classes/page-elements/class-sidebar-map-element.php';
	    require_once 'frontend/classes/page-elements/class-sidebar-tabs-map-element.php';
	    require_once 'frontend/classes/page-elements/class-yelp-results.php';
	    require_once 'frontend/classes/page-elements/class-walk-score.php';
	    require_once 'frontend/classes/page-elements/class-attachments-element.php';
	    require_once 'frontend/classes/page-elements/class-nearby-listings.php';
	    require_once 'frontend/classes/page-elements/class-sidebar-member-info-element.php';
	    require_once 'frontend/classes/page-elements/class-sidebar-contact-element.php';
	    /*
	     * Member Permissions
	     */
	    require_once 'frontend/classes/class-member-permissions.php';

	    /*
	     * Listing FAQ'S
	     */
	    require_once 'frontend/classes/class-listings-faqs.php';

	    /*
	     * Location Manager
	     */
	    require_once 'frontend/classes/class-locations-manager.php';

	    /*
	     * Reviews Manager
	     */
	    //require_once 'frontend/classes/class-reviews-manager.php';

	    /*
	     * widgets
	     */
	    require_once 'backend/widgets/wp-dp-locations.php';
	    require_once 'backend/widgets/wp-dp-banners.php';
	    require_once 'backend/widgets/wp-dp-top-listings.php';
	    require_once 'backend/widgets/wp-dp-top-member.php';
	    require_once 'backend/widgets/wp-dp-popular-member.php';
	    require_once 'backend/widgets/wp-dp-listing.php';

	    /*
	     * Member Account Pages
	     */


	    /*
	     * google cpathca
	     */
	    require_once 'frontend/classes/class-google-captcha.php';



	    /*
	     * Currencies Class
	     */

	    require_once 'backend/classes/class-currencies.php';


	    /*
	     * Promotions Class
	     */

	    require_once 'backend/classes/class-promotions.php';
	    require_once 'frontend/classes/class-promotions.php';

	    /*
	     * Reviews Images Class
	     */
	    require_once 'frontend/classes/class-reviews-images.php';
	    /*
	     * Including Modules files
	     */
	    $this->register_modules();

	    require_once 'include/after-files-loaded.php';

	    add_filter('template_include', array($this, 'wp_dp_single_template'));
	    add_action('admin_enqueue_scripts', array($this, 'wp_dp_defaultfiles_plugin_enqueue'), 2);
	    add_action('admin_enqueue_scripts', array($this, 'wp_dp_enqueue_admin_style_sheet'), 90);
	    add_action('wp_enqueue_scripts', array($this, 'wp_dp_defaultfiles_plugin_enqueue'), 2);
	    add_action('get_footer', array($this, 'wp_dp_enqueue_responsive_front_scripts'), 10);



	    add_action('admin_init', array($this, 'wp_dp_all_scodes'));
	    add_filter('body_class', array($this, 'wp_dp_boby_class_names'));
	}

	/**
	 * Start Function how to add Specific CSS Classes by filter
	 */
	function wp_dp_boby_class_names($classes) {
	    $classes[] = 'wp-dp';
	    return $classes;
	}

	/**
	 * Start Function how position admin menu
	 */
	public function admin_menu_position() {
	    global $menu, $submenu; //echo '<pre>';print_r( $menu );echo '</pre>'; die();
	    foreach ($menu as $key => $menu_item) {
		if (isset($menu_item[2]) && $menu_item[2] == 'edit.php?post_type=listings') {
		    $menu[$key][0] = wp_dp_plugin_text_srt('wp_dp_dp_wp');
		}
	    }
	}

	/**
	 * Start Function how to access admin panel
	 */
	public function prevent_admin_access() {
	    if (is_user_logged_in()) {

		if (strpos(strtolower($_SERVER['REQUEST_URI']), '/wp-admin') !== false && (current_user_can('wp_dp_member'))) {
		    wp_redirect(get_option('siteurl'));
		    add_filter('show_admin_bar', '__return_false');
		}
	    }
	}

	/**
	 * Start Function how to Add textdomain for translation
	 */
	public function load_plugin_textdomain() {
	    global $wp_dp_plugin_options;


	    if (function_exists('icl_object_id')) {

		global $sitepress, $wp_filesystem;

		require_once ABSPATH . '/wp-admin/includes/file.php';

		$backup_url = '';

		if (false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) )) {

		    return true;
		}

		if (!WP_Filesystem($creds)) {
		    request_filesystem_credentials($backup_url, '', true, false, array());
		    return true;
		}

		$wp_dp_languages_dir = plugin_dir_path(__FILE__) . 'languages/';

		$wp_dp_all_langs = $wp_filesystem->dirlist($wp_dp_languages_dir);

		$wp_dp_mo_files = array();
		if (is_array($wp_dp_all_langs) && sizeof($wp_dp_all_langs) > 0) {

		    foreach ($wp_dp_all_langs as $file_key => $file_val) {

			if (isset($file_val['name'])) {

			    $wp_dp_file_name = $file_val['name'];

			    $wp_dp_ext = pathinfo($wp_dp_file_name, PATHINFO_EXTENSION);

			    if ($wp_dp_ext == 'mo') {
				$wp_dp_mo_files[] = $wp_dp_file_name;
			    }
			}
		    }
		}

		$wp_dp_active_langs = $sitepress->get_current_language();
		foreach ($wp_dp_mo_files as $mo_file) {
		    if (strpos($mo_file, $wp_dp_active_langs) !== false) {
			$wp_dp_lang_mo_file = $mo_file;
		    }
		}
	    }

	    $locale = apply_filters('plugin_locale', get_locale(), 'wp-dp');
	    $dir = trailingslashit(WP_LANG_DIR);
	    if (isset($wp_dp_lang_mo_file) && $wp_dp_lang_mo_file != '') {
		load_textdomain('wp-dp', plugin_dir_path(__FILE__) . "languages/" . $wp_dp_lang_mo_file);
	    } else {
		load_textdomain('wp-dp', plugin_dir_path(__FILE__) . "languages/wp-dp-" . $locale . '.mo');
	    }
	    load_plugin_textdomain('wp-dp', false, plugin_basename(dirname(__FILE__)) . '/languages');
	}

	/**
	 * Fetch and return version of the current plugin
	 *
	 * @return	string	version of this plugin
	 */
	public static function get_plugin_version() {
	    $plugin_data = self::$wp_dp_version;
	    return $plugin_data;
	}

	/**
	 * Start Function how to Add User and custom Roles
	 */
	public function wp_dp_add_custom_role() {
	    add_role('guest', 'Guest', array(
		'read' => true, // True allows that capability
		'edit_posts' => true,
		'delete_posts' => false, // Use false to explicitly deny
	    ));
	}

	/**
	 * Start Function how to Add plugin urls
	 */
	public static function plugin_url() {
	    return plugin_dir_url(__FILE__);
	}

	/**
	 * Start Function how to Add image url for plugin wp_dp
	 */
	public static function plugin_img_url() {
	    return plugin_dir_url(__FILE__);
	}

	/**
	 * Start Function how to Create plugin WP Directorybox Manager
	 */
	public static function plugin_dir() {
	    return plugin_dir_path(__FILE__);
	}

	/**
	 * Start Function how to Activate the plugin
	 */
	public static function activate() {
	    global $plugin_user_images_wp_dp;
	    add_option('wp_dp__plugin_activation', 'installed');
	    add_option('wp_dp_', '1');
	    // create user role for wp_dp member
	    $result = add_role(
		    'wp_dp_member', esc_html('Directory Box Member'), array(
		'read' => false,
		'edit_posts' => false,
		'delete_posts' => false,
		    )
	    );
	    // create users images wp_dp
	    $upload = wp_upload_dir();
	    $upload_dir = $upload['basedir'];
	    $upload_dir = $upload_dir . '/' . $plugin_user_images_wp_dp;
	    if (!is_dir($upload_dir)) {
		mkdir($upload_dir, 0777);
	    }
	}

	/**
	 * Start Function how to DeActivate the plugin
	 */
	static function deactivate() {
	    delete_option('wp_dp__plugin_activation');
	    delete_option('wp_dp_', false);
	}

	/**
	 * Start Function how to Add Theme Templates
	 */
	public function wp_dp_single_template($single_template) {
	    global $post;
	    if (get_post_type() == 'listings') {
		if (is_single()) {
		    $single_template = plugin_dir_path(__FILE__) . 'frontend/templates/single_pages/single-listing.php';
		}
	    }
	    if (get_post_type() == 'members') {
		$single_template = plugin_dir_path(__FILE__) . 'frontend/templates/single_pages/single-members.php';
	    }
	    return $single_template;
	}

	/**
	 * Start Function how to Includes Default Scripts and Styles
	 */
	public function wp_dp_defaultfiles_plugin_enqueue() {
	    global $wp_dp_plugin_options;
	    // admin styles
	    if (is_admin()) {
		wp_enqueue_media();
	    }
	    wp_register_style('wp-dp-prettyPhoto', plugins_url('/assets/frontend/css/prettyPhoto.css', __FILE__));
	    wp_register_style('flexslider', plugins_url('/assets/frontend/css/flexslider.css', __FILE__));
	    // map height 100%
	    wp_register_style('leaflet', plugins_url('/assets/frontend/css/leaflet.css', __FILE__));
	    wp_register_script('leaflet', plugins_url('/assets/frontend/scripts/leaflet.js', __FILE__), array('jquery'));
	    wp_register_script('wp_dp_freetilee_js', plugins_url('/assets/frontend/scripts/jquery.freetile.js', __FILE__), array('jquery'), '', true);
	    wp_register_script('wp_dp_masonry_pkgd_min_js', plugins_url('/assets/frontend/scripts/masonry.pkgd.min.js', __FILE__), array('jquery'), '', true);
	    wp_register_script('wp_dp_init_js', plugins_url('/assets/frontend/scripts/init.js', __FILE__), array('jquery'), wp_dp::get_plugin_version(), true);
	    wp_register_script('wp_dp_encryption_js', plugins_url('/assets/frontend/scripts/wp-dp-encryption.js', __FILE__), '', wp_dp::get_plugin_version(), true);

	    wp_register_script('wp_dp_animation_effect', plugins_url('/assets/frontend/scripts/animation-effect.js', __FILE__), '', wp_dp::get_plugin_version(), true);



	    /*
	     *  only rendar when theme inactive Block
	     */
	    if (!class_exists('wp_dp_framework')) {
		if (is_admin()) {
		    // admin js files
		    $wp_dp_cs_scripts_path = plugins_url('/assets/backend/scripts/cs-page-builder-functions.js', __FILE__);
		    wp_enqueue_script('cs-frame-admin', $wp_dp_cs_scripts_path, array('jquery'));
		}
		wp_enqueue_style('wp-dp-default-element-style', plugins_url('/assets/frontend/css/default-element.css', __FILE__));


		wp_enqueue_script('wp-dp-resize-sensor', plugins_url('/assets/frontend/scripts/ResizeSensor.js', __FILE__), '', '');
		wp_enqueue_script('wp-dp-element-queries', plugins_url('/assets/frontend/scripts/ElementQueries.js', __FILE__), '', '');
	    }
	    /*
	     *  only rendar when theme inactive Block End
	     */
	    /* swipper */
	    wp_register_style('swiper', plugins_url('/assets/frontend/css/swiper.css', __FILE__));
	    wp_register_script('swiper', plugins_url('/assets/frontend/scripts/swiper.min.js', __FILE__), array('jquery'), '', true);

	    wp_register_script('fitvids', plugins_url('/assets/frontend/scripts/fitvids.js', __FILE__), array('jquery'), '', true);

	    // common file for listing category
	    wp_register_script('wp-dp-listing-categories', plugins_url('/assets/common/js/listing-categories.js', __FILE__), array('jquery'), wp_dp::get_plugin_version());
	    wp_register_script('chosen-ajaxify', plugins_url('/assets/backend/scripts/chosen-ajaxify.js', __FILE__));
	    wp_register_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.js');
	    $wp_dp_pt_array = array(
		'plugin_url' => wp_dp::plugin_url(),
	    );
	    wp_localize_script('chosen-ajaxify', 'wp_dp_chosen_vars', $wp_dp_pt_array);
	    if (!is_admin()) {
		wp_register_style('fonticonpicker', plugins_url('/assets/icomoon/css/jquery.fonticonpicker.min.css', __FILE__));
	    }
	    //wp_enqueue_style('iconmoon', plugins_url('/assets/icomoon/css/iconmoon.css', __FILE__));
	    wp_enqueue_style('wp_dp_fonticonpicker_bootstrap_css', plugins_url('/assets/icomoon/theme/bootstrap-theme/jquery.fonticonpicker.bootstrap.css', __FILE__));
	    wp_enqueue_script('bootstrap-min', plugins_url('/assets/frontend/scripts/bootstrap.min.js', __FILE__), array('jquery'), '', true);
	    wp_enqueue_style('chosen', plugins_url('/assets/backend/css/chosen.css', __FILE__));
	    wp_register_style('daterangepicker', plugins_url('/assets/frontend/css/daterangepicker.css', __FILE__));

	    if (!is_admin()) {
		wp_enqueue_style('bootstrap_css', plugins_url('/assets/frontend/css/bootstrap.css', __FILE__));
		wp_enqueue_style('wp_dp_bootstrap_slider_css', plugins_url('/assets/frontend/css/bootstrap-slider.css', __FILE__));
		wp_enqueue_style('dp-style-animate', plugins_url('/assets/frontend/css/style-animate.css', __FILE__));
		wp_enqueue_style('wp-dp-widget', plugins_url('/assets/frontend/css/widget.css', __FILE__));
		wp_enqueue_style('wp_dp_plugin_css', plugins_url('/assets/frontend/css/wp-dp-plugin.css', __FILE__), '', wp_dp::get_plugin_version());
		wp_enqueue_style('wp_dp_plugin_dashboard_css', plugins_url('/assets/frontend/css/wp-dp-dashboard.css', __FILE__), '', wp_dp::get_plugin_version());

		if (!class_exists('wp_dp_framework')) {
		    wp_enqueue_style('wp-dp-element-queries', plugins_url('/assets/frontend/css/elementqueries.css', __FILE__), '', wp_dp::get_plugin_version());
		}

		wp_register_style('wp_dp_datepicker_css', plugins_url('/assets/frontend/css/jquery-ui.css', __FILE__));
		$wp_dp_plugin_options = get_option('wp_dp_plugin_options');
		$wp_dp_plugin_options = apply_filters('wp_dp_translate_options', $wp_dp_plugin_options);
	    }

	    // All JS files
	    $google_api_key = '';
	    if (isset($wp_dp_plugin_options['wp_dp_google_api_key']) && $wp_dp_plugin_options['wp_dp_google_api_key'] != '') {
		$google_api_key = '?key=' . $wp_dp_plugin_options['wp_dp_google_api_key'] . '&libraries=geometry,places,drawing';
	    } else {
		$google_api_key = '?libraries=geometry,places,drawing';
	    }
	    wp_register_script('wp-dp-google-map-api', 'https://maps.googleapis.com/maps/api/js' . $google_api_key);
	    if (!is_admin()) {
		wp_enqueue_script('responsive-menu', plugins_url('/assets/frontend/scripts/responsive.menu.js', __FILE__), '', '', true);
	    }

	    wp_register_script('wp-dp-matchHeight-script', plugins_url('/assets/frontend/scripts/jquery.matchHeight-min.js', __FILE__), '', '', true);
	    wp_enqueue_script('wp-dp-matchHeight-script');


	    /*
	     * New Scripts
	     */
	    wp_register_script('wp-dp-validation-script', plugins_url('/assets/frontend/scripts/wp-dp-validation.js', __FILE__), '', wp_dp::get_plugin_version(), true);
	    wp_register_script('wp-dp-members-script', plugins_url('/assets/frontend/scripts/wp-dp-members.js', __FILE__), '', wp_dp::get_plugin_version(), true);
	    wp_register_script('wp-dp-login-script', plugins_url('/assets/frontend/scripts/wp-dp-login.js', __FILE__), '', wp_dp::get_plugin_version(), true);
	    wp_register_script('wp-dp-icons-loader', plugins_url('/assets/common/js/icons-loader.js', __FILE__), array('jquery'));
	    wp_register_script('wp-dp-listing-functions', plugins_url('/assets/frontend/scripts/listing-functions.js', __FILE__), array('jquery'), wp_dp::get_plugin_version());

	    wp_enqueue_script('wp-dp-promotions-functions', plugins_url('/assets/frontend/scripts/promotions.js', __FILE__), array('jquery'), wp_dp::get_plugin_version());

	    wp_register_script('wp-dp-print-pdf', 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js', array('jquery'), wp_dp::get_plugin_version());

	    wp_register_script('wp-dp-hide-listing-functions', plugins_url('/assets/frontend/scripts/hide-listing-functions.js', __FILE__), array('jquery'), wp_dp::get_plugin_version());
	    wp_register_script('jquery-mixitup', plugins_url('/assets/frontend/scripts/jquery.mixitup.min.js', __FILE__), array('jquery'));
	    wp_register_script('wp-dp-member-functions', plugins_url('/assets/frontend/scripts/member-functions.js', __FILE__), array('jquery'), wp_dp::get_plugin_version());
	    $wp_dp_listing_functions_string = array(
		'listing_type' => wp_dp_plugin_text_srt('wp_dp_dp_listing_type'),
		'price_type' => wp_dp_plugin_text_srt('wp_dp_advance_search_select_price_type_label'),
		'all' => wp_dp_plugin_text_srt('wp_dp_advance_search_select_price_types_all'),
		'plugin_url' => wp_dp::plugin_url(),
		'ajax_url' => admin_url('admin-ajax.php'),
		'promotion_error' => wp_dp_plugin_text_srt('wp_dp_promotion_error'),
	    );
	    wp_localize_script('wp-dp-listing-functions', 'wp_dp_listing_functions_string', $wp_dp_listing_functions_string);

	    wp_localize_script('wp-dp-promotions-functions', 'wp_dp_globals', $wp_dp_listing_functions_string);

	    $wp_dp_icons_array = array(
		'plugin_url' => wp_dp::plugin_url(),
	    );
	    wp_localize_script('wp-dp-icons-loader', 'icons_vars', $wp_dp_icons_array);

	    if (is_admin()) {
		wp_enqueue_script('wp-dp-icons-loader');
	    }
	    $wp_dp_listing_strings = array(
		'service_added' => wp_dp_plugin_text_srt('wp_dp_dp_serveice_addeed'),
		'ploor_plan_added' => wp_dp_plugin_text_srt('wp_dp_dp_floor_plan_added'),
		'nearby_added' => wp_dp_plugin_text_srt('wp_dp_dp_near_by_added'),
		'attachment_added' => wp_dp_plugin_text_srt('wp_dp_dp_attachment_added'),
		'apartment_added' => wp_dp_plugin_text_srt('wp_dp_dp_apartment_added'),
		'off_day_added' => wp_dp_plugin_text_srt('wp_dp_dp_off_day_added'),
		'buy_exist_packg' => wp_dp_plugin_text_srt('wp_dp_dp_buy_exists_pkg'),
		'buy_new_packg' => wp_dp_plugin_text_srt('wp_dp_dp_buy_new_pkg'),
		'off_day_already_added' => wp_dp_plugin_text_srt('wp_dp_dp_off_day_already_added'),
		'upload_images_only' => wp_dp_plugin_text_srt('wp_dp_dp_upload_images'),
		'action_error' => wp_dp_plugin_text_srt('wp_dp_dp_action_error'),
		'compulsory_fields' => wp_dp_plugin_text_srt('wp_dp_dp_compulsory_fields'),
		'payment_txt' => wp_dp_plugin_text_srt('wp_dp_dp_payment_text'),
		'submit_order_txt' => wp_dp_plugin_text_srt('wp_dp_dp_sbmit_order'),
		'update_txt' => wp_dp_plugin_text_srt('wp_dp_dp_update_text'),
		'create_list_txt' => wp_dp_plugin_text_srt('wp_dp_dp_create_list_text'),
		'listing_updated' => wp_dp_plugin_text_srt('wp_dp_dp_listing_updated'),
		'listing_created' => wp_dp_plugin_text_srt('wp_dp_dp_listing_created'),
		'valid_price_error' => wp_dp_plugin_text_srt('wp_dp_dp_valid_price_error'),
		'detail_txt' => wp_dp_plugin_text_srt('wp_dp_dp_detail_text'),
		'close_txt' => wp_dp_plugin_text_srt('wp_dp_dp_close_text'),
		'plugin_url' => wp_dp::plugin_url(),
		'ajax_url' => admin_url('admin-ajax.php'),
		'more_than_f' => wp_dp_plugin_text_srt('wp_dp_select_pkg_img_num_more_than'),
		'more_than_image_change' => wp_dp_plugin_text_srt('wp_dp_select_pkg_img_num_change_pkg'),
		'more_than_doc_change' => wp_dp_plugin_text_srt('wp_dp_select_pkg_doc_num_change_pkg'),
		'wp_dp_edit_details' => wp_dp_plugin_text_srt('wp_dp_edit_details'),
		'wp_dp_edit_details_update' => wp_dp_plugin_text_srt('wp_dp_edit_details_update'),
		'wp_dp_edit_details_edit' => wp_dp_plugin_text_srt('wp_dp_edit_details_edit'),
	    );
	    // temprary off

	    if (!is_admin()) {
		wp_enqueue_script('wp_dp_functions_frontend', plugins_url('/assets/frontend/scripts/functions.js', __FILE__), '', wp_dp::get_plugin_version());
		wp_localize_script('wp_dp_functions_frontend', 'wp_dp_listing_strings', $wp_dp_listing_strings);
	    }
	    wp_enqueue_script('wp_dp_common_functions', plugins_url('/assets/common/js/common.js', __FILE__), '', wp_dp::get_plugin_version());
	    wp_localize_script('wp_dp_common_functions', 'wp_dp_listing_strings', $wp_dp_listing_strings);
	    wp_register_script('wp-dp-split-map', plugins_url('/assets/frontend/scripts/split-map.js', __FILE__), '', wp_dp::get_plugin_version(), true);
	    wp_register_script('wp_dp_piechart_frontend', plugins_url('/assets/frontend/scripts/donut-pie-chart.min.js', __FILE__));
	    wp_register_script('wp-dp-listing-detail-scripts', plugins_url('/assets/frontend/scripts/listing-detail.js', __FILE__), '', wp_dp::get_plugin_version());
	    wp_register_script('html2canvas', plugins_url('/assets/frontend/scripts/html2canvas.min.js', __FILE__));
	    wp_register_script('listing-detail-print', plugins_url('/assets/frontend/scripts/listing-detail-print.js', __FILE__), '', wp_dp::get_plugin_version());

	    $wp_dp_globals = array(
		'some_txt_error' => wp_dp_plugin_text_srt('wp_dp_prop_notes_some_txt_error'),
		'own_listing_error' => wp_dp_plugin_text_srt('wp_dp_enquiry_own_listing_error'),
		'plugin_url' => wp_dp::plugin_url(),
		'is_frontend' => is_admin() ? 'false' : 'true',
		'security' => wp_create_nonce('wp-dp-security'),
		'listing_sold_confirm' => wp_dp_plugin_text_srt('wp_dp_listing_sold_confirm_notice'),
		'listing_sold_action_failed' => wp_dp_plugin_text_srt('wp_dp_listing_sold_action_failed_notice'),
		'promotion_error' => wp_dp_plugin_text_srt('wp_dp_promotion_error'),
	    );
	    if (defined('ICL_LANGUAGE_CODE')) {
		$wp_dp_globals['ajax_url'] = admin_url('admin-ajax.php?wpml_lang=' . ICL_LANGUAGE_CODE);
	    } else {
		$wp_dp_globals['ajax_url'] = admin_url('admin-ajax.php');
	    }

	    wp_localize_script('wp_dp_functions_frontend', 'wp_dp_globals', $wp_dp_globals);

	    wp_register_script('wp-dp-prettyPhoto', plugins_url('/assets/frontend/scripts/jquery.prettyPhoto.js', __FILE__), array('jquery'));

	    wp_register_script('flexslider', plugins_url('/assets/frontend/scripts/jquery.flexslider.js', __FILE__), '', '', true);
	    wp_register_script('flexslider-mousewheel', plugins_url('/assets/frontend/scripts/jquery.mousewheel.js', __FILE__), '', '', true);
	    wp_register_script('wp-dp-tags-it', plugins_url('/assets/frontend/scripts/tag-it.js', __FILE__));
	    if (!is_admin()) {
		wp_register_style('bootstrap-datepicker', plugins_url('/assets/frontend/css/bootstrap-datepicker.css', __FILE__));
		wp_register_style('jquery-mCustomScrollbar', plugins_url('/assets/frontend/css/jquery.mCustomScrollbar.css', __FILE__));
		wp_enqueue_script('wp-dp-growls', plugins_url('/assets/frontend/scripts/jquery.growl.js', __FILE__), '', '', true);
		wp_register_script('wp-dp-listing-add', plugins_url('/assets/frontend/scripts/listing-add-functions.js', __FILE__), '', wp_dp::get_plugin_version(), true);
		wp_register_script('wp-dp-listing-user-add', plugins_url('/assets/frontend/scripts/listing-add-user.js', __FILE__), '', wp_dp::get_plugin_version(), true);
		wp_register_script('wp-dp-reservation-functions', plugins_url('/assets/frontend/scripts/reservation-functions.js', __FILE__), '', wp_dp::get_plugin_version());
		/*
		 * Icons style and script
		 */
		wp_register_script('fonticonpicker', plugins_url('/assets/icomoon/js/jquery.fonticonpicker.min.js', __FILE__));

		wp_localize_script('wp-dp-listing-add', 'wp_dp_listing_strings', $wp_dp_listing_strings);
		wp_localize_script('wp-dp-listing-user-add', 'wp_dp_listing_strings', $wp_dp_listing_strings);
		// listing map js
		wp_register_script('map-infobox', plugins_url('/assets/frontend/scripts/map-infobox.js', __FILE__), '', wp_dp::get_plugin_version(), true);
		wp_register_script('map-clusterer', plugins_url('/assets/frontend/scripts/markerclusterer.js', __FILE__), '', '', true);
		wp_register_script('wp-dp-listing-map', plugins_url('/assets/frontend/scripts/listing-map.js', __FILE__), '', wp_dp::get_plugin_version(), true);
		wp_register_script('wp-dp-listing-top-map', plugins_url('/assets/frontend/scripts/listing-top-map.js', __FILE__), '', wp_dp::get_plugin_version(), true);
		do_action('wp_dp_enqueue_files_frontend');
		wp_enqueue_script('chosen', plugins_url('/assets/frontend/scripts/chosen.jquery.js', __FILE__));

		wp_register_script('jquery-mCustomScrollbar', plugins_url('/assets/frontend/scripts/jquery.mCustomScrollbar.concat.min.js', __FILE__), array('jquery'), '', true);
	    }
	    wp_register_script('wp-dp-jquery-scrollbar', plugins_url('/assets/frontend/scripts/jquery.scrollbar.js', __FILE__), '', '', true);
	    wp_enqueue_script('responsive-calendar', plugins_url('/assets/common/js/responsive-calendar.min.js', __FILE__), '', '', true);
	    wp_register_script('wp-dp-bootstrap-slider', plugins_url('/assets/frontend/scripts/bootstrap-slider.js', __FILE__), '', '', true);
	    wp_enqueue_script('wp-dp-bootstrap-slider');
	    // Dashboad date fields style & script.
	    wp_register_style('daterangepicker', plugins_url('/assets/frontend/css/daterangepicker.css', __FILE__));
	    wp_register_script('daterangepicker-moment', plugins_url('/assets/frontend/scripts/moment.js', __FILE__), '', '', true);
	    wp_register_script('daterangepicker', plugins_url('/assets/frontend/scripts/daterangepicker.js', __FILE__), '', '', true);
	    wp_register_script('wp-dp-filters-functions', plugins_url('/assets/frontend/scripts/filters-functions.js', __FILE__), '', wp_dp::get_plugin_version(), true);
	    wp_register_script('jquery-print', plugins_url('/assets/frontend/scripts/jQuery.print.js', __FILE__), '', '', true);

	    // listing compare
	    wp_register_script('wp-dp-listing-compare', plugins_url('/assets/frontend/scripts/listing-compare.js', __FILE__), '', wp_dp::get_plugin_version(), true);
	    $listing_compare_strings = array(
		'plugin_url' => wp_dp::plugin_url(),
		'ajax_url' => admin_url('admin-ajax.php'),
		'error' => wp_dp_plugin_text_srt('wp_dp_shortcode_compare_error'),
		'compare_label' => wp_dp_plugin_text_srt('wp_dp_shortcode_compare_label'),
		'compared_label' => wp_dp_plugin_text_srt('wp_dp_shortcode_compared_label'),
		'add_to_compare' => wp_dp_plugin_text_srt('wp_dp_compare_add_to_compare'),
		'removed_from_compare' => wp_dp_plugin_text_srt('wp_dp_compare_remove_to_compare'),
	    );
	    wp_localize_script('wp-dp-listing-compare', 'wp_dp_listing_compare', $listing_compare_strings);
	    /**
	     *
	     * @login popup script files
	     */
	    if (!function_exists('wp_dp_login_box_popup_scripts')) {

		function wp_dp_login_box_popup_scripts() {
		    echo '';
		}

	    }
	    /**
	     *
	     * @login popup script files
	     */
	    if (!function_exists('wp_dp_google_recaptcha_scripts')) {

		function wp_dp_google_recaptcha_scripts() {
		    wp_enqueue_script('wp_dp_google_recaptcha_scripts', wp_dp_server_protocol() . 'www.google.com/recaptcha/api.js?onload=wp_dp_multicap_all_functions&amp;render=explicit', '', '');
		}

	    }
	    //jquery text editor files
	    if (is_admin()) {
		wp_enqueue_style('jquery-te', plugins_url('/assets/common/css/jquery-te-1.4.0.css', __FILE__));
		wp_enqueue_script('jquery-te', plugins_url('/assets/common/js/jquery-te-1.4.0.min.js', __FILE__), '', '', true);
	    }
	    if (!is_admin()) {
		wp_register_style('jquery-te', plugins_url('/assets/common/css/jquery-te-1.4.0.css', __FILE__));
		wp_register_script('jquery-te', plugins_url('/assets/common/js/jquery-te-1.4.0.min.js', __FILE__));
	    }
	    //jquery text editor files end
	    if (is_admin()) {
		// admin css files
		global $price_tables_meta_object;
		wp_enqueue_style('wp_dp_datatable_css', plugins_url('/assets/backend/css/datatable.css', __FILE__));
		wp_enqueue_style('fonticonpicker', plugins_url('/assets/icomoon/css/jquery.fonticonpicker.min.css', __FILE__));
		//wp_enqueue_style('iconmoon', plugins_url('/assets/icomoon/css/iconmoon.css', __FILE__));
		wp_enqueue_style('wp_dp_fonticonpicker_bootstrap_css', plugins_url('/assets/icomoon/theme/bootstrap-theme/jquery.fonticonpicker.bootstrap.css', __FILE__));
		wp_enqueue_style('wp-dp-bootstrap', plugins_url('/assets/backend/css/bootstrap.css', __FILE__));
		wp_enqueue_style('wp_dp_bootstrap_calendar_css', plugins_url('/assets/backend/css/bootstrap-year-calendar.css', __FILE__));
		wp_enqueue_style('wp_dp_price_tables', plugins_url('/assets/backend/css/price-tables.css', __FILE__));
		wp_enqueue_script('jquery-latlon-picker', plugins_url('/assets/frontend/scripts/jquery_latlon_picker.js', __FILE__), '', '', false);
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-dp-bootstrap-slider');
		// admin js files
		wp_enqueue_script('wp_dp_datatable_js', plugins_url('/assets/backend/scripts/datatable.js', __FILE__), '', '', true);
		wp_enqueue_script('chosen-order-jquery', plugins_url('/assets/common/js/chosen.order.jquery.js', __FILE__));
		wp_enqueue_script('chosen-ajaxify', plugins_url('/assets/backend/scripts/chosen-ajaxify.js', __FILE__));
		$wp_dp_pt_array = array(
		    'plugin_url' => wp_dp::plugin_url(),
		);
		wp_localize_script('chosen-ajaxify', 'wp_dp_chosen_vars', $wp_dp_pt_array);
		wp_enqueue_script('wp_dp_bootstrap_calendar_js', plugins_url('/assets/backend/scripts/bootstrap-year-calendar.js', __FILE__));
		wp_enqueue_script('wp_dp_custom_wp_admin_script_js', plugins_url('/assets/backend/scripts/functions.js', __FILE__), array('wp-color-picker'), '', true);
		wp_localize_script(
			'wp_dp_custom_wp_admin_script_js', 'wp_dp_backend_globals', array(
		    'ajax_url' => admin_url('admin-ajax.php'),
		    'plugin_url' => wp_dp::plugin_url(),
		    'security' => wp_create_nonce('wp-dp-security'),
		    'banner_image_error' => wp_dp_plugin_text_srt('wp_dp_options_banner_image_error'),
		    'banner_code_error' => wp_dp_plugin_text_srt('wp_dp_options_banner_code_error'),
		    'delete_selected_file_cofirmation' => wp_dp_plugin_text_srt('wp_dp_options_delete_selected_backup_file'),
			)
		);
		wp_enqueue_script('wp_dp__shortcodes_js', plugins_url('/assets/backend/scripts/shortcode-functions.js', __FILE__), '', '', true);
		wp_localize_script(
			'wp_dp__shortcodes_js', 'wp_dp_globals', array(
		    'ajax_url' => admin_url('admin-ajax.php'),
		    'plugin_url' => wp_dp::plugin_url(),
			)
		);
		wp_enqueue_script('fonticonpicker', plugins_url('/assets/icomoon/js/jquery.fonticonpicker.min.js', __FILE__));
		wp_register_script('wp-dp-price-tables', plugins_url('/assets/backend/scripts/price-tables.js', __FILE__), '', '', true);
		$wp_dp_pt_array = array(
		    'plugin_url' => wp_dp::plugin_url(),
		    'ajax_url' => esc_url(admin_url('admin-ajax.php')),
		    'packages_dropdown' => $price_tables_meta_object->wp_dp_pkgs(),
		);
		wp_localize_script('wp-dp-price-tables', 'wp_dp_pt_vars', $wp_dp_pt_array);
		wp_enqueue_script('wp-dp-price-tables');
		wp_enqueue_style('datetimepicker', plugins_url('/assets/common/css/jquery.datetimepicker.css', __FILE__));
		wp_enqueue_script('datetimepicker', plugins_url('/assets/common/js/jquery.datetimepicker.js', __FILE__), '', '', true);
		wp_enqueue_script('chosen', plugins_url('/assets/backend/scripts/chosen.jquery.js', __FILE__));
	    }

	    wp_register_style('datepicker', plugins_url('/assets/frontend/css/datepicker.css', __FILE__));
	    wp_register_style('datetimepicker', plugins_url('/assets/common/css/jquery.datetimepicker.css', __FILE__));
	    wp_register_script('datetimepicker', plugins_url('/assets/common/js/jquery.datetimepicker.js', __FILE__), '', '', true);
	    wp_register_script('jquery-branches-latlon-picker', plugins_url('/assets/frontend/scripts/jquery-branches-latlon-picker.js', __FILE__), '', '', true);
	    wp_register_script('jquery-latlon-picker', plugins_url('/assets/frontend/scripts/jquery_latlon_picker.js', __FILE__), '', '', false);


	    wp_register_script('wp_dp_map_style_js', plugins_url('/assets/frontend/scripts/map-styles.js', __FILE__), '', '');
	    wp_enqueue_script('wp_dp_map_style_js');
	    wp_register_script('wp_dp_cs-admin-upload', trailingslashit(get_template_directory_uri()) . 'assets/backend/js/cs-media-upload.js', array('jquery', 'media-upload'));

	    /**
	     *
	     * @social login script
	     */
	    if (!function_exists('wp_dp_socialconnect_scripts')) {

		function wp_dp_socialconnect_scripts() {
		    wp_enqueue_script('wp_dp_socialconnect_js', plugins_url('/elements/login/cs-social-login/media/js/cs-connect.js', __FILE__), '', wp_dp::get_plugin_version(), true);
		}

	    }

	    // Register Location Autocomplete for late use.
	    wp_register_script('wp_dp_location_autocomplete_js', plugins_url('/assets/frontend/scripts/jquery.location-autocomplete.js', __FILE__), '', '', true);
	    // Register Listing Autocomplete.
	    wp_register_script('wp_dp_listing_autocomplete_js', plugins_url('/assets/frontend/scripts/listing-autocomplete.js', __FILE__), '', '', true);

	    /**
	     *
	     * @google auto complete script
	     */
	    if (!function_exists('wp_dp_google_autocomplete_scripts')) {

		function wp_dp_google_autocomplete_scripts() {
		    wp_enqueue_script('wp_dp_location_autocomplete_js', plugins_url('/assets/frontend/scripts/jquery.location-autocomplete.js', __FILE__), '', '');
		}

	    }
	}

	public function wp_dp_enqueue_admin_style_sheet() {
	    wp_enqueue_style('wp-dp-admin-style', plugins_url('/assets/backend/css/admin-style.css', __FILE__), '', wp_dp::get_plugin_version());
	}

	/**
	 *
	 * @Responsive Tabs Styles and Scripts
	 */
        
	public static function wp_dp_enqueue_responsive_front_scripts() {


	    $my_theme = wp_get_theme('wp-dp');
	    if (!$my_theme->exists()) {
		wp_enqueue_style('wp_dp_responsive_css', plugins_url('/assets/frontend/css/responsive.css', __FILE__), '', wp_dp::get_plugin_version());
	    }
	}

	/**
	 *
	 * @Data Table Style Scripts
	 */

	/**
	 * Start Function how to Add table Style Script
	 */
	public static function wp_dp_data_table_style_script() {
	    wp_enqueue_style('wp_dp_data_table_css', plugins_url('/assets/frontend/css/jquery.data_tables.css', __FILE__));
	}

	/**
	 * End Function how to Add Tablit Style Script
	 */
	public static function wp_dp_jquery_ui_scripts() {
	    
	}

	/**
	 * Start Function how to Add Location Picker Scripts
	 */
	public function wp_dp_location_gmap_script() {
	    wp_enqueue_script('jquery-latlon-picker', plugins_url('/assets/frontend/scripts/jquery_latlon_picker.js', __FILE__), '', '', true);
	}

	public function wp_dp_branches_location_gmap_script() {
	    wp_enqueue_script('jquery-branches-latlon-picker', plugins_url('/assets/frontend/scripts/jquery-branches-latlon-picker.js', __FILE__), '', '', true);
	}

	/**
	 * Start Function how to Add Google Place Scripts
	 */
	public function wp_dp_google_place_scripts() {
	    global $wp_dp_plugin_options;
	    $google_api_key = '';
	    if (isset($wp_dp_plugin_options['wp_dp_google_api_key']) && $wp_dp_plugin_options['wp_dp_google_api_key'] != '') {
		$google_api_key = '?key=' . $wp_dp_plugin_options['wp_dp_google_api_key'] . '&libraries=geometry,places,drawing';
	    } else {
		$google_api_key = '?libraries=geometry,places,drawing';
	    }
	    wp_enqueue_script('wp-dp-google-map-api', 'https://maps.googleapis.com/maps/api/js' . $google_api_key);
	}

	// start function for google map files
	public static function wp_dp_googlemapcluster_scripts() {
	    echo '';
	}

	/**
	 * Start Function how to Add Google Autocomplete Scripts
	 */
	public function wp_dp_autocomplete_scripts() {
	    wp_enqueue_script('jquery-ui-autocomplete');
	    wp_enqueue_script('jquery-ui-slider');
	}

	// Start function for global code
	public function wp_dp_all_scodes() {
	    global $wp_dp_jh_scodes;
	}

	// Start function for auto login user
	public function wp_dp_auto_login_user() {
	    
	}

	public static $email_template_type = 'general';
	public static $email_default_template = 'Hello! I am general email template by [COMPANY_NAME].';
	public static $email_template_variables = array(
	    array(
		'tag' => 'SITE_NAME',
		'display_text' => 'Site Name',
		'value_callback' => array('wp_dp', 'wp_dp_get_site_name'),
	    ),
	    array(
		'tag' => 'ADMIN_EMAIL',
		'display_text' => 'Admin Email',
		'value_callback' => array('wp_dp', 'wp_dp_get_admin_email'),
	    ),
	    array(
		'tag' => 'SITE_URL',
		'display_text' => 'SITE URL',
		'value_callback' => array('wp_dp', 'wp_dp_get_site_url'),
	    ),
	);

	public function email_template_settings_callback($email_template_options) {
	    $email_template_options['types'][] = self::$email_template_type;
	    $email_template_options['templates']['general'] = self::$email_default_template;
	    $email_template_options['variables']['General'] = self::$email_template_variables;

	    return $email_template_options;
	}

	/*
	 * Fetching Plugin Option for specific option ID
	 * @ @option_id is the option you want to get status for
	 */

	public function wp_dp_get_plugin_options_callback($option_id = '') {
	    if (isset($option_id) && $option_id != '') {
		$wp_dp_plugin_options = get_option('wp_dp_plugin_options');
		$wp_dp_plugin_options = apply_filters('wp_dp_translate_options', $wp_dp_plugin_options);
		if (isset($wp_dp_plugin_options[$option_id])) {
		    return $wp_dp_plugin_options[$option_id];
		}
	    }
	    return false;
	}

	public static function get_terms_and_conditions_field($label = '', $field_name = '', $show_accept = true) {
	    global $wp_dp_plugin_options;
	    $label = ( $label == '' ? wp_dp_plugin_text_srt('wp_dp_dp_terms_and_conditions') : $label );
	    $field_name = ( $field_name == '' ? 'terms_and_conditions' : $field_name );

	    $terms_condition_check = isset($wp_dp_plugin_options['wp_dp_cs_terms_condition_check']) ? $wp_dp_plugin_options['wp_dp_cs_terms_condition_check'] : '';
	    ob_start();
	    if ($terms_condition_check == 'on') {
		$terms_condition_page = isset($wp_dp_plugin_options['cs_terms_condition']) ? $wp_dp_plugin_options['cs_terms_condition'] : '';
		?>
		<div class="checkbox-area">
		    <input type="checkbox" id="<?php echo ($field_name); ?>" class="wp-dp-dev-req-field">

		    <label for="<?php echo ($field_name); ?>">
			<?php
			if ($show_accept) {
			    echo wp_dp_plugin_text_srt('wp_dp_dp_accept');
			}
			?>
			<a target="_blank" href="<?php echo esc_url(get_permalink($terms_condition_page)); ?>">
			    <?php echo esc_html($label); ?>
			</a>
		    </label>
		</div>
		<?php
	    }
	    return ob_get_clean();
	}

	public static function wp_dp_get_site_name() {
	    return get_bloginfo('name');
	}

	public static function wp_dp_get_admin_email() {
	    return get_bloginfo('admin_email');
	}

	public static function wp_dp_get_site_url() {
	    return get_bloginfo('url');
	}

	public static function wp_dp_replace_tags($template, $variables) {
	    // Add general variables to the list
	    $variables = array_merge(self::$email_template_variables, $variables);

	    foreach ($variables as $key => $variable) {
		$callback_exists = false;

		// Check if function/method exists.
		if (is_array($variable['value_callback'])) { // If it is a method of a class.
		    $callback_exists = method_exists($variable['value_callback'][0], $variable['value_callback'][1]);
		} else { // If it is a function.
		    $callback_exists = function_exists($variable['value_callback']);
		}

		// Substitute values in place of tags if callback exists.
		if (true == $callback_exists) {
		    // Make a call to callback to get value.
		    $value = call_user_func($variable['value_callback']);

		    // If we have some value to substitute then use that.
		    if (false != $value) {
			$template = str_replace('[' . $variable['tag'] . ']', $value, $template);
		    }
		}
	    }
	    return $template;
	}

	public static function get_template($email_template_index, $email_template_variables, $email_default_template) {
	    $email_template = '';
	    $template_data = array('subject' => '', 'from' => '', 'recipients' => '', 'email_notification' => '', 'email_type' => '', 'email_template' => '');
	    // Check if there is a template select else go with default template.
	    $selected_template_id = wp_dp_check_if_template_exists($email_template_index, 'dp-templates');
	    if (false != $selected_template_id) {

		// Check if a temlate selected else default template is used.
		if ($selected_template_id != 0) {
		    $templateObj = get_post($selected_template_id);
		    if ($templateObj != null) {
			$email_template = $templateObj->post_content;
			$template_id = $templateObj->ID;
			$template_data['subject'] = wp_dp::wp_dp_replace_tags(get_post_meta($template_id, 'jh_subject', true), $email_template_variables);
			$template_data['from'] = wp_dp::wp_dp_replace_tags(get_post_meta($template_id, 'jh_from', true), $email_template_variables);
			$template_data['recipients'] = wp_dp::wp_dp_replace_tags(get_post_meta($template_id, 'jh_recipients', true), $email_template_variables);
			$template_data['email_notification'] = get_post_meta($template_id, 'jh_email_notification', true);
			$template_data['email_type'] = get_post_meta($template_id, 'jh_email_type', true);
		    }
		} else {
		    // Get default template.
		    $email_template = $email_default_template;
		    $template_data['email_notification'] = 1;
		}
	    } else {
		$email_template = $email_default_template;
		$template_data['email_notification'] = 1;
	    }

	    $email_template = wp_dp::wp_dp_replace_tags($email_template, $email_template_variables);
	    $template_data['email_template'] = $email_template;
	    return $template_data;
	}

	public static function plugin_path() {
	    return untrailingslashit(plugin_dir_path(__FILE__));
	}

	public static function template_path() {
	    return apply_filters('wp_dp_plugin_template_path', 'wp-directorybox-manager/');
	}

	public function register_modules() {
	    /*
	     * Modules
	     */
	    require_once 'modules/wp-dp-favourites/wp-dp-favourites.php';
	    require_once 'modules/wp-dp-notifications/wp-dp-alerts.php';
	    require_once 'modules/wp-dp-activity-notifications/wp-dp-activity-notifications.php';
	    require_once 'modules/wp-dp-email-templates/wp-dp-email-templates.php';
	    require_once 'modules/wp-dp-claims/wp-dp-claims.php';
	}

    }

}
/*
 * Check if an email template exists
 */
if (!function_exists('wp_dp_check_if_template_exists')) {

    function wp_dp_check_if_template_exists($slug, $type) {
	global $wpdb;
	$post = $wpdb->get_row("SELECT ID FROM " . $wpdb->prefix . "posts WHERE post_name = '" . $slug . "' && post_type = '" . $type . "'", 'ARRAY_A');
	if (isset($post) && isset($post['ID'])) {
	    return $post['ID'];
	} else {
	    return false;
	}
    }

}


/**
 * Get Listing's Gallery Nth Image.
 *
 */
if (!function_exists('wp_dp_get_listing_gallery_nth_image_url')) {

    function wp_dp_get_listing_gallery_nth_image_url($post_id = 0, $size = 'thumbnail', $n = 0) {
	$image_url = '';

	if ($post_id > -1 && $n > -1) {
	    $listing_type_slug = get_post_meta($post_id, 'wp_dp_listing_type', true);
	    $listing_type_post = get_posts(array('posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish'));
	    $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
	    $listing_type_gal_switch = get_post_meta($listing_type_id, 'wp_dp_image_gallery_element', true);
	    if ($listing_type_gal_switch == 'on') {
		$gallery_ids_list = get_post_meta($post_id, 'wp_dp_detail_page_gallery_ids', true);
		if (( is_array($gallery_ids_list) && sizeof($gallery_ids_list) > 0)) {
		    if (isset($gallery_ids_list[$n])) {
			$attachment_id = $gallery_ids_list[$n];
		    } else {
			$attachment_id = $gallery_ids_list[0];
		    }
		    $image_attr = wp_get_attachment_image_src($attachment_id, $size);
		    if ($image_attr) {
			$image_url = $image_attr[0];
		    }
		}
	    }
	}
	return $image_url;
    }

}


/**
 *
 * @Create Object of class To Activate Plugin
 */
if (class_exists('wp_dp')) {
    global $wp_dp_Class;
    $wp_dp_Class = new wp_dp();
    register_activation_hook(__FILE__, array('wp_dp', 'activate'));
    register_deactivation_hook(__FILE__, array('wp_dp', 'deactivate'));
}

//Remove Sub Menu add new listings
if (!function_exists('modify_menu')) {

    function modify_menu() {
	global $submenu;
	if (isset($submenu['edit.php?post_type=listings'][10])) {
	    unset($submenu['edit.php?post_type=listings'][10]);
	}
	if (isset($submenu['edit.php?post_type=members'][10])) {
	    unset($submenu['edit.php?post_type=members'][10]);
	}
	if (isset($submenu['edit.php?post_type=packages'][10])) {
	    unset($submenu['edit.php?post_type=packages'][10]);
	}
	if (isset($submenu['wp_dp_directory'][4][2])) {
	    $submenu['wp_dp_directory'][4][2] = 'http://chimpgroup.com/crm/index.php/quotation';
	}
	if (isset($submenu['wp_dp_directory'][5][2])) {
	    $submenu['wp_dp_directory'][5][2] = 'http://chimpgroup.com/wp-demo/documentation/documentation/directorybox-documentation/';
	}
	if (isset($submenu['wp_dp_directory'][7][2])) {
	    $submenu['wp_dp_directory'][7][2] = '';
	}
    }

}

add_action('admin_menu', 'modify_menu', 25);
if (!function_exists('create_daily_listings_check')) {

    function create_daily_listings_check() {
	// Use wp_next_scheduled to check if the event is already scheduled.
	$timestamp = wp_next_scheduled('create_daily_listings_check');

	// If $timestamp == false schedule daily alerts since it hasn't been done previously.
	if ($timestamp == false) {
	    // Schedule the event for right now, then to repeat daily using the hook 'create_daily_listings_check'.
	    wp_schedule_event(time(), 'daily', 'create_daily_listings_check');
	}
    }

}
if (!function_exists('remove_daily_listings_check')) {

    function remove_daily_listings_check() {
	wp_clear_scheduled_hook('remove_daily_listings_check');
    }

}

// On plugin activation register daily cronj0b.
register_activation_hook(__FILE__, 'create_daily_listings_check');
register_activation_hook(__FILE__, array('Wp_dp_Listing_Alerts', 'create_daily_alert_schedule'));
register_deactivation_hook(__FILE__, array('Wp_dp_Listing_Alerts', 'remove_daily_alert_schedule'));

// On plugin deactivation

if (!function_exists('wp_dp_get_current_template')) {

    function wp_dp_get_current_template($echo = false) {
	if (!isset($GLOBALS['current_theme_template']))
	    return false;
	if ($echo)
	    echo wp_dp_cs_allow_special_char($GLOBALS['current_theme_template']);
	else
	    return $GLOBALS['current_theme_template'];
    }

}

add_filter('template_include', 'wp_dp_template_include', 1000);

if (!function_exists('wp_dp_template_include')) {

    function wp_dp_template_include($t) {
	$GLOBALS['current_theme_template'] = basename($t);
	return $t;
    }

}

add_action('activated_plugin', 'load_priority_for_directory_box_manager');
if (!function_exists('load_priority_for_directory_box_manager')) {

    function load_priority_for_directory_box_manager() {
	$path = 'wp-directorybox-manager/wp-directorybox-manager.php';
	$plugins = get_option('active_plugins');
	if ($key = array_search($path, $plugins)) {
	    array_splice($plugins, $key, 1);
	    array_unshift($plugins, $path);
	    update_option('active_plugins', $plugins);
	}
    }

}

if (!function_exists('directorybox_remove_filters')) {
    function directorybox_remove_filters($tag, $function_to_reove, $priority = 10) {
        remove_filter($tag, $function_to_reove, $priority);
    }
}

if (!function_exists('directorybox_widget_register')) {
    function directorybox_widget_register($widget) {
        return register_widget($widget);
    }
}

if (!function_exists('directorybox_iss_plugin_active')) {
    function directorybox_iss_plugin_active($slug) {
        if(isset($slug) && $slug != ''){
            return is_plugin_active($slug);
        }
        return false;
    }
}

if (!function_exists('directorybox_server')) {
    function directorybox_server($value) {
        if(isset($value) && $value != ''){
           return isset($_SERVER[$value]) ? $_SERVER[$value] : '';
        }else{
            return $_SERVER;
        }
    }
}

if (!function_exists('directorybox_tgmpa_iss_plugin_active')) {
    function directorybox_tgmpa_iss_plugin_active($obj, $slug) {
        if(isset($obj) && $slug != ''){
            return $obj->is_active_plugin($slug);
        }
        return false;
    }
}


if (!function_exists('wp_force_tags_balance')) {
    function wp_force_tags_balance($text) {
        return force_balance_tags($text);
    }
    return false;
}

// Pre
if(!function_exists('pre')){
    function pre( $data, $is_exit = true ){
        echo '<pre>';
            print_r( $data );
        echo '</pre>';
        if( $is_exit == true){
            exit;
        }
    }
}