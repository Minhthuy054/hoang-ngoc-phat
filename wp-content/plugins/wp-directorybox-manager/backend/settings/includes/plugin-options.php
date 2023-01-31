<?php

/**
 * Start Function  how to Create Theme Options in Backend 
 */
if ( ! function_exists('wp_dp_settings_options_page') ) {

    function wp_dp_settings_options_page() {

        global $wp_dp_setting_options, $wp_dp_form_fields, $gateways;
        $wp_dp_plugin_options = get_option('wp_dp_plugin_options');
        apply_filters('wp_dp_translate_options_admin', $wp_dp_plugin_options);
        $obj = new wp_dp_options_fields();
        $return = $obj->wp_dp_fields($wp_dp_setting_options);
        $wp_dp_opt_btn_array = array(
            'id' => '',
            'std' => wp_dp_plugin_text_srt('wp_dp_plugin_options_save_all_settings'),
            'cust_id' => "submit_btn",
            'cust_name' => "submit_btn",
            'cust_type' => 'button',
            'classes' => 'bottom_btn_save',
            'extra_atr' => 'onclick="javascript:plugin_option_save(\'' . esc_js(admin_url('admin-ajax.php')) . '\');" ',
            'return' => true,
        );

        $wp_dp_opt_hidden1_array = array(
            'id' => '',
            'std' => 'plugin_option_save',
            'cust_id' => "",
            'cust_name' => "action",
            'return' => true,
        );
        $wp_dp_opt_hidden2_array = array(
            'id' => '',
            'std' => wp_dp::plugin_url(),
            'cust_id' => "wp_dp_plugin_url",
            'cust_name' => "wp_dp_plugin_url",
            'return' => true,
        );

        $wp_dp_opt_btn_cancel_array = array(
            'id' => '',
            'std' => wp_dp_plugin_text_srt('wp_dp_plugin_options_reset_all_options'),
            'cust_id' => "submit_btn",
            'cust_name' => "reset",
            'cust_type' => 'button',
            'classes' => 'bottom_btn_reset',
            'extra_atr' => 'onclick="javascript:wp_dp_rest_plugin_options(\'' . esc_js(admin_url('admin-ajax.php')) . '\');"',
            'return' => true,
        );

        $html = '
        <div class="theme-wrap fullwidth">
            <div class="inner">
                <div class="outerwrapp-layer">
                    <div class="loading_div" id="wp_dp_loading_msg_div"> <i class="icon-circle-o-notch icon-spin"></i> <br>
                        ' . wp_dp_plugin_text_srt('wp_dp_plugin_options_please_wait') . '
                    </div>
                    <div class="form-msg"> <i class="icon-check-circle-o"></i>
                        <div class="innermsg"></div>
                    </div>
                </div>
                <div class="row">
                    <form id="plugin-options" method="post" enctype="multipart/form-data">
			<div class="col1">
                            <nav class="admin-navigtion">
                                <div class="logo"> <a href="javascript;;" class="logo1"><img src="' . esc_url(wp_dp::plugin_url()) . 'assets/backend/images/logo.png" /></a> <a href="#" class="nav-button"><i class="icon-align-justify"></i></a> </div>
                                <ul>
                                    ' . force_balance_tags($return[1], true) . '
                                </ul>
                            </nav>
                        </div>
                        <div class="col2">
                        ' . force_balance_tags($return[0], true) . '
                        </div>

                        <div class="clear"></div>
                        <div class="footer">
                        ' . $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_btn_array) . '
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_hidden1_array) . '
                        ' . $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_hidden2_array) . '
                        ' . $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_btn_cancel_array) . '
                                
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="clear"></div>';
        $html .= '<script type="text/javascript">
			// Sub Menus Show/hide
			jQuery(document).ready(function($) {
                jQuery(".sub-menu").parent("li").addClass("parentIcon");
                $("a.nav-button").click(function() {
                    $(".admin-navigtion").toggleClass("navigation-small");
                });                
                $("a.nav-button").click(function() {
                    $(".inner").toggleClass("shortnav");
                });                
                $(".admin-navigtion > ul > li > a").click(function() {
                    var a = $(this).next(\'ul\')
                    $(".admin-navigtion > ul > li > a").not($(this)).removeClass("changeicon")
                    $(".admin-navigtion > ul > li ul").not(a) .slideUp();
                    $(this).next(\'.sub-menu\').slideToggle();
                    $(this).toggleClass(\'changeicon\');
                });
                $(\'[data-toggle="popover"]\').popover(\'destroy\');
            });            
            function show_hide(id){
				var link = id.replace("#", "");
                jQuery(\'.horizontal_tab\').fadeOut(0);
                jQuery("#"+link).fadeIn(400);
            }            
            function toggleDiv(id) { 
                jQuery(\'.col2\').children().hide();
                jQuery(id).show();
                location.hash = id+"-show";
                var link = id.replace("#", "");
                jQuery(\'.categoryitems li\').removeClass(\'active\');
                jQuery(".menuheader.expandable") .removeClass(\'openheader\');
                jQuery(".categoryitems").hide();
		jQuery("."+link).addClass(\'active\');
		jQuery("."+link) .parent("ul").show().prev().addClass("openheader");
                google.maps.event.trigger(document.getElementById("cs-map-location-id"), "resize");
            }
            jQuery(document).ready(function() {
                jQuery(".categoryitems").hide();
                jQuery(".categoryitems:first").show();
                jQuery(".menuheader:first").addClass("openheader");
                jQuery(".menuheader").on(\'click\', function(event) {
                    if (jQuery(this).hasClass(\'openheader\')){
                        jQuery(".menuheader").removeClass("openheader");
                        jQuery(this).next().slideUp(200);
                        return false;
                    }
                    jQuery(".menuheader").removeClass("openheader");
                    jQuery(this).addClass("openheader");
                    jQuery(".categoryitems").slideUp(200);
                    jQuery(this).next().slideDown(200); 
                    return false;
                });                
                var hash = window.location.hash.substring(1);
                var id = hash.split("-show")[0];
                if (id){
                    jQuery(\'.col2\').children().hide();
                    jQuery("#"+id).show();
                    jQuery(\'.categoryitems li\').removeClass(\'active\');
                    jQuery(".menuheader.expandable") .removeClass(\'openheader\');
                    jQuery(".categoryitems").hide();
                    jQuery("."+id).addClass(\'active\');
                    jQuery("."+id) .parent("ul").slideDown(300).prev().addClass("openheader");
                } 
            });
            
        </script>';
        echo force_balance_tags($html, true);
    }

    /**
     * end Function  how to Create Theme Options in Backend 
     */
}
/**
 * Start Function  how to Create Theme Options setting in Backend 
 */
if ( ! function_exists('wp_dp_settings_option') ) {

    function wp_dp_settings_option() {
        global $wp_dp_setting_options, $gateways;
        $wp_dp_theme_menus = get_registered_nav_menus();
        $wp_dp_plugin_options = get_option('wp_dp_plugin_options');
        $wp_dp_plugin_options = apply_filters('wp_dp_translate_options', $wp_dp_plugin_options);
        $on_off_option = array( "show" => "on", "hide" => "off" );

        $wp_dp_min_days = array();
        for ( $days = 1; $days < 11; $days ++ ) {
            $wp_dp_min_days[$days] = "$days day";
        }


        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_general_options'),
            "fontawesome" => 'icon-build',
            "id" => "tab-general-page-settings",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );


        // member settings
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_member_settings'),
            "fontawesome" => 'icon-user',
            "id" => "tab-member-settings",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_single_listing_settings'),
            "fontawesome" => 'icon-list5',
            "id" => "tab-single-listing",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_currencies'),
            "fontawesome" => 'icon-money',
            "id" => "tab-currencies-settings",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_gateways'),
            "fontawesome" => 'icon-wallet2',
            "id" => "tab-gateways-settings",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_settings'),
            "fontawesome" => 'icon-ioxhost',
            "id" => "tab-api-setting",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_settings'),
            "fontawesome" => 'icon-map-marker',
            "id" => "tab-general-default-location",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_nearby_place'),
            "fontawesome" => 'icon-map-marker',
            "id" => "tab-map-nearby-places",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_location_settings'),
            "fontawesome" => 'icon-location-arrow',
            "id" => "tab-backend-settings",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );


        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_auto_post'),
            "fontawesome" => 'icon-comments-o',
            "id" => "tab-autopost-setting",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_login'),
            "fontawesome" => 'icon-user',
            "id" => "tab-social-login-setting",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_ads_management'),
            "fontawesome" => 'icon-list2',
            "id" => "tab-ads-management-setting",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );
        // Directory Box Plugin Option Smtp Tab.
        $wp_dp_setting_options = apply_filters('wp_dp_plugin_option_smtp_tab', $wp_dp_setting_options);

        $wp_dp_setting_options = apply_filters('wp_dp_plugin_option_addon_tab', $wp_dp_setting_options);


        // General Settings
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_generl_options'),
            "id" => "tab-general-page-settings",
            "extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_plugin_options_generl_options') . '"',
            "type" => "sub-heading",
            "help_text" => "",
        );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_generl_options'),
            "id" => "tab-user-settings",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_generl_options'),
            "type" => "section",
            "options" => ""
        );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_user_hdr_lgn'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_user_hdr_lgn_hint'),
            "id" => "user_dashboard_switchs",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );
        
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_opening_hours'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_opening_hours_hint'),
            "id" => "opening_hours_switch",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );



        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_listing_settings'),
            "id" => "tab-listing-options",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_listing_settings'),
            "type" => "section",
            "options" => ""
        );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_create_listing_btn_switch'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_create_listing_btn_switch_hint'),
            "id" => "create_listing_button",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_create_listing_pge'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_create_listing_pge_hint'),
            "id" => "wp_dp_create_listing_page",
            "std" => "",
            "type" => "select_dashboard",
            'custom_page_select' => true,
            "options" => '',
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_member_dashboard'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_member_dashboard_hint'),
            "id" => "wp_dp_member_dashboard",
            "std" => "",
            "type" => "select_dashboard",
            'custom_page_select' => true,
            "options" => '',
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_price_plan_page_selected'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_settings_price_plan_page_hint'),
            "id" => "wp_dp_price_plan_page",
            "std" => "",
            "type" => "select_dashboard",
            'custom_page_select' => true,
            "options" => '',
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_pkgs_detail_page'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_pkgs_detail_page_hint'),
            "id" => "wp_dp_package_page",
            "std" => "",
            "type" => "select_dashboard",
            'custom_page_select' => true,
            "options" => '',
        );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_search_result'),
            "desc" => '',
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_search_result_hint'),
            "id" => "wp_dp_search_result_page",
            "std" => '',
            "type" => "select_dashboard",
            'custom_page_select' => true,
            "options" => ''
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_options_email_logs'),
            "id" => "tab-email-logs-options",
            "std" => wp_dp_plugin_text_srt('wp_dp_options_email_logs'),
            "type" => "section",
            "options" => ""
        );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_options_email_logs'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_options_enable_disable_email_logs'),
            "id" => "email_logs",
            "std" => "off",
            "type" => "checkbox",
            "options" => $on_off_option
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_submissions'),
            "id" => "tab-settings-submissions",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_submissions'),
            "type" => "section",
            "options" => ""
        );
        
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_listing_publish_pend'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_listing_publish_pend_hint'),
            "id" => "listings_review_option",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );
        
//        $wp_dp_setting_options[] = array(
//            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_success_msg'),
//            "desc" => "",
//            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_success_msg_hint'),
//            "id" => "listing_success_message",
//            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_success_msg_default'),
//            "type" => "text",
//        );
//        

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_success_rev_msg'),
            "desc" => "",
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_success_rev_msg_hint'),
            "id" => "listing_approval_message",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_success_msg_review'),
            "type" => "text",
        );

        
         $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_text_content_settings'),
            "id" => "tab-term-policy-options",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_text_content_settings'),
            "type" => "section",
            "options" => ""
        );
        
         $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_content_settings_text_in'),
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_content_settings_text_in_hint'),
            "id" => "displaying_text_style",
            "std" => "",
            "type" => "select",
            'classes' => 'chosen-select-no-single',
            "options" => array(
                'words'=>wp_dp_plugin_text_srt('wp_dp_plugin_options_content_settings_text_in_words'),
                'char'=>wp_dp_plugin_text_srt('wp_dp_plugin_options_content_settings_text_in_character'),
                
            ),
        );
         
         $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_content_title_length'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_content_title_length_hint'),
            "id" => "custom_title_length",
            "std" => '',
            "type" => "text",
        );
         
         $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_content_content_length'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_content_content_length_hint'),
            "id" => "custom_content_length",
            "std" => '',
            "type" => "text",
        );
         
        
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_terms_policy'),
            "id" => "tab-term-policy-options",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_terms_policy'),
            "type" => "section",
            "options" => ""
        );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_terms_plicy_onoff'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_terms_plicy_onoff_hint'),
            "id" => "term_policy_switch",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_term_policy_descr'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_term_policy_descr_hint'),
            "id" => "term_policy_description",
            "std" => "",
            "type" => "textarea",
        );

        $wp_dp_setting_options[] = array(
            "type" => "col-right-text",
            "extra" => "div",
        );

        // End General Options Announcements
        // general default location 
        // Smtp Email plugin fields filter.
        /**
         * Apply the filters by calling the 'wp_dp_smtp_plugin_options' function we
         * "hooked" to 'wp_dp_smtp_plugin_options' using the add_filter() function above.
         */
        $wp_dp_setting_options = apply_filters('wp_dp_smtp_plugin_options', $wp_dp_setting_options);
        // End Smtp Email plugin fields filter.

        $wp_dp_setting_options = apply_filters('wp_dp_notification_plugin_settings', $wp_dp_setting_options);



        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_member_settings'),
            "id" => "tab-member-settings",
            "extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_plugin_options_member_settings') . '"',
            "type" => "sub-heading"
        );

        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_member_settings'),
            'id' => 'member-settings',
            'std' => wp_dp_plugin_text_srt('wp_dp_plugin_options_member_settings'),
            'type' => 'section',
            'options' => ''
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_auto_apporal'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_auto_apporal_hint'),
            "id" => "member_review_option",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_profile_image'),
            "desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_profile_image_desc'),
            "hint_text" => '',
            "label_desc"=>wp_dp_plugin_text_srt('wp_dp_plugin_options_profile_image_hint_label'),
            "echo" => false,
            "id" => "profile_images",
            "type" => "gallery_upload",
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_dflt_plac_hldr'),
            "desc" => "",
            "label_desc"=> wp_dp_plugin_text_srt('wp_dp_plugin_options_dflt_plac_hldr_hint'),
            "hint_text" => '',
            "id" => "default_placeholder_image",
            "std" => "",
            "type" => "upload logo"
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_announcments'),
            "id" => "tab-announcements-options",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_announcments'),
            "type" => "section",
            "options" => ""
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_announcement_title_dshbrd'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_announcement_title_dshbrd_hint'),
            "id" => "dashboard_announce_title",
            "std" => '',
            "type" => "text",
        );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_announcement_desc_dashboard'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_announcement_desc_dashboard_hint'),
            "id" => "dashboard_announce_description",
            "std" => "",
            "type" => "textarea",
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_announcement_bg_color'),
            "desc" => "",
            "hint_text" => '',
            "id" => "announce_bg_color",
            "std" => "",
            "type" => "color"
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_user_reg_package'),
            "id" => '',
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_user_reg_package'),
            "type" => "section",
            "options" => ""
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_user_reg_package_asign'),
            "desc" => '',
            'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_options_user_reg_package_asign_hint'),
            "hint_text" => '',
            "id" => "member_register_package",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option,
        );

        $free_packages = array( '' => wp_dp_plugin_text_srt('wp_dp_plugin_options_user_reg_package_select') );
        $p_args = array(
            'post_type' => 'packages',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'wp_dp_package_type',
                    'value' => 'free',
                    'compare' => '=',
                ),
            ),
        );
        $pkgs_query = new WP_Query($p_args);
        $pkgs_query_posts = $pkgs_query->posts;
        if ( is_array($pkgs_query_posts) && sizeof($pkgs_query_posts) > 0 ) {
            foreach ( $pkgs_query_posts as $pkgs_query_post ) {
                $free_packages[$pkgs_query_post] = get_the_title($pkgs_query_post);
            }
        }

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_user_reg_package_txt'),
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_user_reg_package_txt_hint'),
            "id" => "member_assign_package",
            "std" => "",
            "type" => "select",
            'classes' => 'chosen-select-no-single',
            "options" => $free_packages
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_dashboard_paginationsz'),
            "id" => "",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_dashboard_paginationsz'),
            "type" => "section",
            "options" => ""
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_dashboard_pagination'),
            "desc" => "",
            'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_options_dashboard_pagination_hint'),
            "hint_text" => '',
            "id" => "member_dashboard_pagination",
            "std" => "20",
            "type" => "text",
        );

        $wp_dp_setting_options = apply_filters('wp_dp_activity_notification_plugin_settings', $wp_dp_setting_options);

        $wp_dp_setting_options[] = array( "col_heading" => "",
            "type" => "col-right-text",
            "help_text" => ""
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_single_listing_settings'),
            "id" => "tab-single-listing",
            "extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_plugin_options_single_listing_settings') . '"',
            "type" => "sub-heading"
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_yelp_places_cats'),
            "id" => "tab-yelp-places-options",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_yelp_places_cats'),
            "type" => "section",
            "options" => ""
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_yelp_places'),
            "desc" => "",
            "id" => "yelp_places_cats",
            'label_desc' => 'asasasa',
            'hint_text' => '',
            "std" => '',
            "type" => "yelp_cats_list",
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_default_mortgage'),
            "id" => "tab-mortgage-options",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_mortgage_std'),
            "type" => "section",
            "options" => ""
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_min_lease_yr'),
            "desc" => "",
            'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_options_min_lease_yr_hint'),
            "id" => "mortgage_min_year",
            'hint_text' => '',
            "std" => '',
            "type" => "text",
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_max_lease_yr'),
            "desc" => "",
            'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_options_max_lease_yr_hint'),
            'hint_text' => '',
            "id" => "mortgage_max_year",
            "std" => '',
            "type" => "text",
        );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_payment_calculator_description'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_payment_calculator_description_hint'),
            "id" => "mortgage_static_text_block",
            "std" => "",
            "type" => "textarea",
            'wp_dp_editor' => true,
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_listings_detail_sidebar'),
            "id" => "",
            "std" => wp_dp_plugin_text_srt('wp_dp_listings_detail_sidebar'),
            "type" => "section",
            "options" => ""
        );
        /*
         * get all sidebars
         */
            
        $wp_dp_cs_sidebars_array = array(''=>wp_dp_plugin_text_srt('wp_dp_listings_detail_sidebar_select'));
        foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
           $wp_dp_cs_sidebars_array[$sidebar['id']] = ucwords($sidebar['name']);
        }
         $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_listings_detail_sidebar_switch'),
            "desc" => '',
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_listings_detail_sidebar_switch_hint'),
            "id" => "listing_sidebar_switch",
            "std" => "off",
            "type" => "checkbox",
            "options" => $on_off_option,
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_listings_detail_sidebar_select'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_listings_detail_sidebar_hint'),
            "id" => "listing_detail_select_sidebar",
            "std" => "",
            "type" => "select",
            'classes' => 'chosen-select-no-single',
            "options" => $wp_dp_cs_sidebars_array,
        );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_environmental_desc'),
            "id" => "",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_environmental_desc'),
            "type" => "section",
            "options" => ""
        );


        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_environmental_text'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_environmental_text_hint'),
            "id" => "listing_static_envior_text",
            "std" => "",
            "type" => "text",
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_environmental_desc'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_environmental_desc_hint'),
            "id" => "listing_static_text_block",
            "std" => "",
            "type" => "textarea",
            'wp_dp_editor' => true,
        );

        $single_listing_options = array(
            'top_map' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_top_map'),
            'top_slider' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_top_slider'),
            'top_gallery_map' => wp_dp_plugin_text_srt('wp_dp_single_options_top_gallery_with_map'),
            'sticky_navigation' => wp_dp_plugin_text_srt('wp_dp_list_meta_sticky'),
            'content_gallery' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_content_gallery'),
            'social_networks' => wp_dp_plugin_text_srt('wp_dp_user_meta_social_networks'),
            'bottom_member_info' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_content_bottom_member_info'),
            'sidebar_map' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_sidebar_map'),
            'sidebar_gallery' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_sidebar_gallery'),
            'sidebar_member_info' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_sidebar_member_info'),
            'sidebar_contact_info' => wp_dp_plugin_text_srt('wp_dp_single_options_sidebar_contact_info'),
            'sidebar_mortgage_calculator' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_sidebar_mortgage_calculator'),
            'sidebar_opening_hours' => wp_dp_plugin_text_srt('wp_dp_options_listing_detail_sidebar_opening_hours'),
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_detail_page_options_new'),
            "id" => "detail_view5",
            "std" => wp_dp_plugin_text_srt('wp_dp_detail_page_options_new'),
            "type" => "section",
            "options" => ""
        );
        if ( isset($single_listing_options) ) {
            $exclude_view_1_options = array( 'top_map', 'top_slider', 'social_networks' );
            foreach ( $single_listing_options as $key => $val ) {
                if ( ! in_array($key, $exclude_view_1_options) ) {
                    $wp_dp_setting_options[] = array(
                        "name" => $val,
                        "desc" => "",
                        "hint_text" => '',
                        'label_desc' => sprintf(wp_dp_plugin_text_srt('wp_dp_list_listing_type_help_text'), $val),
                        "id" => "detail_view5_" . $key,
                        "std" => "on",
                        "type" => "checkbox",
                        "options" => $on_off_option
                    );
                }
            }
        }

        $wp_dp_setting_options[] = array( "col_heading" => "",
            "type" => "col-right-text",
            "help_text" => ""
        );


        /*
         * Currencies Options
         */

        $wp_dp_setting_options = apply_filters('wp_dp_currency_settings', $wp_dp_setting_options);

        $wp_dp_setting_options = apply_filters('wp_dp_plugin_option_addon_content', $wp_dp_setting_options);


        // end member settings
        // Payments Gateways
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_gateway_settings'),
            "id" => "tab-gateways-settings",
            "extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_plugin_options_gateway_settings') . '"',
            "type" => "sub-heading"
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_gateway_settings'),
            "id" => "tab-gateways-settings",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_gateway_settings'),
            "type" => "section",
            "options" => ""
        );
        $wp_dp_gateways_id = WP_DP_FUNCTIONS()->rand_id();

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_vat_onoff'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_vat_onoff_hint'),
            "id" => "vat_switch",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_vat_in_percent'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_vat_in_percent_hint'),
            "id" => "payment_vat",
            "std" => "",
            "type" => "text",
        );



        if ( class_exists('WooCommerce') ) {

            $use_wooC_gateways = isset($wp_dp_plugin_options['wp_dp_use_woocommerce_gateway']) ? $wp_dp_plugin_options['wp_dp_use_woocommerce_gateway'] : '';
            $is_gateways_display = ( $use_wooC_gateways == 'on' ) ? 'style="display:none;"' : 'style="display:block;"';
            $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_woocommerece_pat_gateway'),
                "desc" => "",
                "hint_text" => '',
                "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_woocommerece_pat_gateway_hint'),
                "id" => "use_woocommerce_gateway",
                "std" => "off",
                "type" => "checkbox",
                "onchange" => "use_wooC_gateways(this)",
                "options" => $on_off_option
            );

            $wp_dp_setting_options[] = array(
                "type" => "division",
                "enable_id" => "wp_dp_use_woocommerce_gateway_style",
                "enable_val" => "",
                "auto_enable" => false,
                "extra_atts" => 'id="wp-dp-no-wooC-gateway-div" ' . $is_gateways_display,
            );
        }

        $gtws_settings = new WP_DP_PAYMENTS();
        
        if ( is_array($gateways) && sizeof($gateways) > 0 ) {
            foreach ( $gateways as $key => $value ) {
                if ( class_exists($key) ) {
                    $settings = new $key();
                    $gtw_settings = $settings->settings($wp_dp_gateways_id);
                    foreach ( $gtw_settings as $key => $params ) {
                        $wp_dp_setting_options[] = $params;
                    }
                }
            }
        }

        if ( class_exists('WooCommerce') ) {
            $wp_dp_setting_options[] = array(
                "type" => "division_close",
            );
        }

        $wp_dp_setting_options[] = array( "col_heading" => wp_dp_plugin_text_srt('wp_dp_plugin_options_payment_text'),
            "type" => "col-right-text",
            "hint_text" => "",
            "help_text" => ""
        );
        /*
         * defaul locations
         */
        // Default location

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_default_location'),
            "id" => "tab-general-default-location",
            "type" => "sub-heading",
            "extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_plugin_options_default_location') . '"',
            "help_text" => wp_dp_plugin_text_srt('wp_dp_plugin_options_default_location_hint'),
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_default_location'),
            "id" => "tab-settings-default-location",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_default_location'),
            "type" => "section",
            "options" => "",
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_marker_icon'),
            "desc" => "",
            "hint_text" => "",
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_marker_icon_desc'),
            "id" => "map_marker_icon",
            "std" => wp_dp::plugin_url() . 'assets/images/map-marker.png',
            "display" => "block",
            "type" => "upload logo"
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_cluster_icon'),
            "desc" => "",
            "hint_text" => "",
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_cluster_icon_desc'),
            "id" => "map_cluster_icon",
            "std" => wp_dp::plugin_url() . 'assets/frontend/images/map-cluster.png',
            "display" => "block",
            "type" => "upload logo"
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_zoom_level'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_zoom_level_hint'),
            "id" => "map_zoom_level",
            "std" => "9",
            "classes" => "wp-dp-dev-req-field-admin wp-dp-number-field wp-dp-range-field",
            "extra_attr" => 'data-min="1" data-max="15"',
            "type" => "text"
        );


        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_distance_measure_by'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_distance_measure_by_desc'),
            "id" => "distance_measure_by",
            "std" => "",
            "type" => "select",
            'classes' => 'chosen-select-no-single',
            "options" => array(
                'km' => wp_dp_plugin_text_srt('wp_dp_plugin_options_km'),
                'miles' => wp_dp_plugin_text_srt('wp_dp_plugin_options_miles'),
            )
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_hint'),
            "id" => "def_map_style",
            "std" => "",
            "type" => "select",
            'classes' => 'chosen-select-no-single',
            "options" => array(
                'map-box' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_map_box'),
                'blue-water' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_blue_water'),
                'icy-blue' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_icy_blue'),
                'bluish' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_bluish'),
                'light-blue-water' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_light_blue_water'),
                'clad-me' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_clad_me'),
                'chilled' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_chilled'),
                'two-tone' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_two_tone'),
                'light-and-dark' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_light_and_dark'),
                'ilustracao' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_illusttracioa'),
                'flat-pale' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_flat_pale'),
                'title' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_title'),
                'moret' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_moret'),
                'samisel' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_semisel'),
                'herbert-map' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_herbert_map'),
                'light-dream' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_light_dream'),
                'blue-essence' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_blue_essence'),
                'rpn-map' => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_style_rpn_map'),
            )
        );
        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_option_map_custom_style'),
            'desc' => '',
            'hint_text' => '',
            'label_desc' => sprintf(wp_dp_plugin_text_srt('wp_dp_plugin_option_map_custom_style_desc'), '<a href="https://snazzymaps.com" target="_blank">https://snazzymaps.com</a>'),
            'id' => 'map_custom_style',
            'std' => '',
            'type' => 'textarea'
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_street_address'),
            "desc" => "",
            "hint_text" => "",
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_street_address_descscr'),
            "id" => "default_locations",
            "std" => "",
            "type" => "default_location_fields",
            "contry_hint" => '',
            "city_hint" => '',
        );
        $wp_dp_setting_options[] = array( "col_heading" => wp_dp_plugin_text_srt('wp_dp_plugin_options_default_location'),
            "type" => "col-right-text",
            "extra" => "div",
            "help_text" => wp_dp_plugin_text_srt('wp_dp_plugin_options_col_hdng_dflt_location_hint'),
        );
        //End default location 

        /*
         * Backend Locations and Map settings.
         */
        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_backend_loc_map_stng'),
            'id' => 'tab-backend-settings',
            "extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_plugin_options_backend_loc_map_stng') . '"',
            'type' => 'sub-heading'
        );

        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_backend_locations'),
            'id' => 'locations',
            'std' => wp_dp_plugin_text_srt('wp_dp_plugin_options_backend_locations'),
            'type' => 'section',
            'options' => ''
        );

        $wp_dp_setting_options[] = array( 'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_backend_loc_and_level'),
            'desc' => '',
            'hint_text' => '',
             "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_street_lat_desc_hint'), 
            'id' => 'locations_level_selector',
            'std' => '',
            'type' => 'locations_level_selector'
        );

        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_backend_geo_loc'),
            'desc' => '',
            'hint_text' => '',
            'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_options_backend_geo_loc_share'),
            'id' => 'map_geo_location',
            'main_id' => 'wp_dp_map_geo_location',
            'std' => '',
            'type' => 'checkbox'
        );

        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_backend_auto_country_detection'),
            'desc' => '',
            'hint_text' => '',
            'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_options_backend_auto_country_detection_hint'),
            'id' => 'map_auto_country_detection',
            'main_id' => 'wp_dp_map_auto_country_detection',
            'std' => '',
            'type' => 'checkbox'
        );

        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_backend_max_adrs_lmt'),
            'desc' => '',
            'hint_text' => '',
            'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_options_backend_max_adrs_lmt_hint'),
            'id' => 'map_address_maximum_text_limit',
            'main_id' => 'wp_dp_map_address_maximum_text_limit',
            'std' => '',
            'classes' => 'wp-dp-number-field',
            'type' => 'text'
        );

        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_backend_drawing_tools'),
            'desc' => '',
            'hint_text' => '',
            'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_options_backend_drawing_tools_hint'),
            'id' => 'drawing_tools',
            'main_id' => 'wp_dp_map_drawing_tools',
            'std' => '',
            'type' => 'checkbox'
        );

        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_drawing_tool_line_clr'),
            'desc' => '',
            'hint_text' => '',
            'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_options_drawing_tool_line_clr_hint'),
            'id' => 'drawing_tools_line_color',
            'main_id' => 'wp_dp_map_drawing_tools_line_color',
            'std' => '#000000',
            'type' => 'color',
        );

        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_drawing_tool_fill_clr'),
            'desc' => '',
            'hint_text' => '',
            'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_options_drawing_tool_fill_clr_hint'),
            'id' => 'drawing_tools_fill_color',
            'main_id' => 'wp_dp_map_drawing_tools_fill_color',
            'std' => '#000000',
            'type' => 'color',
        );

        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_drawing_tool_auto_cmplte'),
            'desc' => '',
            'hint_text' => '',
            'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_options_drawing_tool_auto_cmplte_hint'),
            'id' => 'location_autocomplete',
            'main_id' => 'wp_dp_map_location_autocomplete',
            'std' => '',
            'type' => 'checkbox'
        );

        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_circle_radius'),
            'desc' => '',
            'hint_text' => '',
             'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_options_circle_radius_hint'),
            'id' => 'default_radius_circle',
            'main_id' => 'wp_dp_default_radius_circle',
            'std' => '10',
            'type' => 'text'
        );

        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_loc_frm_fields'),
            'id' => 'locations',
            'std' => wp_dp_plugin_text_srt('wp_dp_plugin_options_loc_frm_fields'),
            'type' => 'section',
            'options' => ''
        );

        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_loc_fields'),
            'desc' => '',
            'hint_text' => '',
            'id' => 'locations_fields_selector',
            'cust_name' => 'locations_fields_selector[]',
            'std' => '',
            'type' => 'locations_fields_selector',
        );


        $wp_dp_setting_options[] = array( 'col_heading' => '',
            'type' => 'col-right-text',
            'help_text' => ''
        );




        /*
         * Map Nearby Settings
         */

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_nearby_place'),
            "id" => "tab-map-nearby-places",
            "type" => "sub-heading",
            "extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_plugin_options_map_nearby_place') . '"',
            "help_text" => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_nearby_place_hint'),
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_nearby_place'),
            "id" => "tab-map-nearby-places",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_nearby_place'),
            "type" => "section",
            "options" => "",
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_map_nearby_place'),
            "type" => "map_nearby_places",
        );
        $wp_dp_setting_options[] = array( 'col_heading' => '',
            'type' => 'col-right-text',
            'help_text' => ''
        );
        /*
         * Social auto post start
         */

        $twitter_format = array(
            'twitter_listing_title' => '[Listing Title]',
            'twitter_permalink' => '[Permalink]',
            'twitter_listing_content' => '[Listing Content]',
        );
        $facebook_format = array(
            'facebook_listing_title' => '[Listing Title]',
            'facebook_permalink' => '[Permalink]',
            'facebook_listing_content' => '[Listing Content]',
        );
        $linkedin_format = array(
            'linkedin_listing_title' => '[Listing Title]',
            'linkedin_permalink' => '[Permalink]',
            'linkedin_listing_content' => '[Listing Content]',
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_auto_post_stng'),
            "id" => "tab-autopost-setting",
            "extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_plugin_options_social_auto_post_stng') . '"',
            "type" => "sub-heading"
        );

        $wp_dp_setting_options[] = array(
            'name' => '',
            'id' => '',
            'std' => wp_dp_plugin_text_srt('wp_dp_plugin_options_autopost_announcement'),
            'type' => 'announcement',
            'options' => ''
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_autopost_twitter'),
            "desc" => "",
            "id" => "twitter_autopost_switch",
            'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_options_autopost_twitter_hint'),
            "std" => "off",
            "type" => "checkbox",
            "onchange" => "wp_dp_autopost_twitter_hide_show(this.name)",
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_autopost_facebook'),
            "desc" => "",
            "id" => "facebook_autopost_switch",
            'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_options_autopost_facebook_hint'),
            "std" => "off",
            "type" => "checkbox",
            "onchange" => "wp_dp_autopost_facebook_hide_show(this.name)",
            "options" => $on_off_option
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_autopost_linkedin'),
            "desc" => "",
            'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_options_autopost_linkedin_hint'),
            "id" => "linkedin_autopost_switch",
            "std" => "off",
            "onchange" => "wp_dp_autopost_linkedin_hide_show(this.name)",
            "type" => "checkbox",
            "options" => $on_off_option
        );

        $wp_dp_setting_options[] = array( "col_heading" => "",
            "type" => "col-right-text",
            "help_text" => ""
        );

        /*
         * End auto post settings
         */
        // social login 
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_login'),
            "id" => "tab-social-login-setting",
            "extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_plugin_options_social_login') . '"',
            "type" => "sub-heading"
        );

        $wp_dp_setting_options[] = array(
            'name' => '',
            'id' => '',
            'std' => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_login_announcement'),
            'type' => 'announcement',
            'options' => ''
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_login_twitter'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_login_twitter_hint'),
            "id" => "twitter_api_switch",
            "std" => "on",
            "type" => "checkbox"
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_login_facebook'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_login_facebook_hint'),
            "id" => "facebook_login_switch",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_login_google'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_login_google_hint'),
            "id" => "google_login_switch",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );
        $wp_dp_setting_options[] = array( "col_heading" => "",
            "type" => "col-right-text",
            "help_text" => ""
        );
        // end social login
        // API settings.
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_setting'),
            "id" => "tab-api-setting",
            "extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_plugin_options_api_setting') . '"',
            "type" => "sub-heading"
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_setting_twitter'),
            "id" => "Twitter",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_setting_twitter'),
            "type" => "section",
            "options" => ""
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_twitter_cons_key'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_twitter_cons_key_hint'),
            "id" => "consumer_key",
            "std" => "",
            "type" => "text"
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_twitter_cons_secret'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_twitter_cons_secret_hint'),
            "id" => "consumer_secret",
            "std" => "",
            "type" => "text"
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_twitter_access_token'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_twitter_access_token_hint'),
            "id" => "access_token",
            "std" => "",
            "type" => "text"
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_twitter_access_token_secret'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_twitter_access_token_secret_hint'),
            "id" => "access_token_secret",
            "std" => "",
            "type" => "text"
        );
        //end Twitter Api		
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_settings_facebook'),
            "id" => "Facebook",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_settings_facebook'),
            "type" => "section",
            "options" => ""
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_facebook_app_id'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_facebook_app_id_hint'),
            "id" => "facebook_app_id",
            "std" => "",
            "classes" => "wp-dp-number-field",
            "type" => "text"
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_facebook_secret'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_facebook_secret_hint'),
            "id" => "facebook_secret",
            "std" => "",
            "type" => "text"
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_facebook_access_token'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_facebook_access_token_hint'),
            "id" => "facebook_access_token",
            "std" => "",
            "type" => "fb_access_token"
        );
        //end facebook api
        //start google api
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_settings_google'),
            "id" => "Google",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_settings_google') . '+',
            "type" => "section",
            "options" => ""
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_google_client_id'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_google_client_id_hint'),
            "id" => "google_client_id",
            "std" => "",
            "type" => "text"
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_google_client_secret'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_google_client_secret_hint'),
            "id" => "google_client_secret",
            "std" => "",
            "type" => "text"
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_google_api_key'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_google_api_key_hint'),
            "id" => "google_api_key",
            "std" => "",
            "type" => "text"
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_google_redirect'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_google_redirect_hint'),
            "id" => "google_login_redirect_url",
            "std" => "",
            "type" => "text"
        );
        //end google api
        // captcha settings
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_setting_captcha'),
            "id" => "Captcha",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_setting_captcha'),
            "type" => "section",
            "options" => ""
        );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_setting_captcha'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_setting_captcha_hint'),
            "id" => "captcha_switch",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_captcha_site_key'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_captcha_site_key_hint'),
            "id" => "sitekey",
            "std" => "",
            "type" => "text",
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_captcha_secret_key'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_captcha_secret_key_hint'),
            "id" => "secretkey",
            "std" => "",
            "type" => "text",
        );
        // end captcha settings

        /*
         * Start Linkedin API Settings
         */

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_setting_linkedin'),
            "id" => "LinkedIn",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_setting_linkedin'),
            "type" => "section",
            "options" => ""
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_linkedin_app_id'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_linkedin_app_id_hint'),
            "id" => "linkedin_app_id",
            "std" => "",
            "type" => "text"
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_linkedin_secret'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_linkedin_secret_hint'),
            "id" => "linkedin_secret",
            "std" => "",
            "type" => "text"
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_linkedin_access_token'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_linkedin_access_token_hint'),
            "id" => "linkedin_access_token",
            "std" => "",
            "type" => "linkedin_access_token"
        );

        /*
         * End Linkedin API setting
         */

        /*
         * Start Yelp API Settings
         */

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_setting_yelp'),
            "id" => "yelp-api",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_setting_yelp'),
            "type" => "section",
            "options" => ""
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_yelp_app_id'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_yelp_app_id_hint'),
            "id" => "yelp_app_id",
            "std" => "",
            "type" => "text"
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_yelp_app_secret'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_yelp_app_secret_hint'),
            "id" => "yelp_secret",
            "std" => "",
            "type" => "text"
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_yelp_access_token'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_yelp_access_token_hint'),
            "id" => "yelp_access_token",
            "std" => "",
            "type" => "yelp_access_token"
        );

        /*
         * Start Walk Score API Settings
         */
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_setting_walk_score'),
            "id" => "walk-score-api",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_setting_walk_score'),
            "type" => "section",
            "options" => ""
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_walk_score_app_id'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_walk_score_app_id_hint'),
            "id" => "walkscore_api_key",
            "std" => "",
            "type" => "text"
        );
        
        
        /*
         * Start IP Geolocation API
         */
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_setting_ip_geolocation'),
            "id" => "ip-geolocation-api",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_setting_ip_geolocation'),
            "type" => "section",
            "options" => ""
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_ip_geolocation_api'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_ip_geolocation_api_hint'),
            "id" => "ip_geolocation_api_key",
            "std" => "",
            "type" => "text"
        );

        $wp_dp_setting_options[] = array( "col_heading" => wp_dp_plugin_text_srt('wp_dp_plugin_options_api_settings'),
            "type" => "col-right-text",
            "help_text" => ""
        );

        // Ads Management settings.
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_ads_management'),
            "id" => "tab-ads-management-setting",
            "extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_plugin_options_ads_management') . '"',
            "type" => "sub-heading"
        );

        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_ads_management'),
            'id' => 'ads-management-settings',
            'std' => wp_dp_plugin_text_srt('wp_dp_plugin_options_ads_management'),
            'type' => 'section',
            'options' => ''
        );

        ///Ads Unit 
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_ads_management_settings'),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_banner_fields",
            "std" => "",
            "type" => "banner_fields",
            "options" => array()
        );

        $wp_dp_setting_options[] = array( "col_heading" => wp_dp_plugin_text_srt('wp_dp_plugin_options_ads_management'),
            "type" => "col-right-text",
            "help_text" => ""
        );

        /* social Network setting */
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_sharing'),
            "id" => "tab-social-icons",
            "extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_plugin_options_social_sharing') . '"',
            "type" => "sub-heading"
        );
        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_sharing'),
            'id' => 'social-sharing-settings',
            'std' => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_sharing'),
            'type' => 'section',
            'options' => ''
        );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_sharing'),
            "desc" => "",
            "hint_text" => "",
            "id" => "social_share",
            "std" => "on",
            "type" => "checkbox" );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_sharing_facebook'),
            "desc" => "",
            "hint_text" => "",
            "id" => "facebook_share",
            "std" => "on",
            "type" => "checkbox" );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_sharing_twitter'),
            "desc" => "",
            "hint_text" => "",
            "id" => "twitter_share",
            "std" => "on",
            "type" => "checkbox" );

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_sharing_pinterest'),
            "desc" => "",
            "hint_text" => "",
            "id" => "pintrest_share",
            "std" => "on",
            "type" => "checkbox"
        );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_sharing_tumbler'),
            "desc" => "",
            "hint_text" => "",
            "id" => "tumblr_share",
            "std" => "on",
            "type" => "checkbox" );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_sharing_dribble'),
            "desc" => "",
            "hint_text" => "",
            "id" => "dribbble_share",
            "std" => "off",
            "type" => "checkbox" );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_sharing_instagram'),
            "desc" => "",
            "hint_text" => "",
            "id" => "instagram_share",
            "std" => "on",
            "type" => "checkbox" );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_sharing_stumble_upon'),
            "desc" => "",
            "hint_text" => "",
            "id" => "stumbleupon_share",
            "std" => "on",
            "type" => "checkbox" );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_sharing_youtube'),
            "desc" => "",
            "hint_text" => "",
            "id" => "youtube_share",
            "std" => "on",
            "type" => "checkbox" );
        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_sharing_share_more'),
            "desc" => "",
            "hint_text" => "",
            "id" => "share_share",
            "std" => "off",
            "type" => "checkbox" );
        /* social network end */

        $wp_dp_setting_options[] = array( "col_heading" => wp_dp_plugin_text_srt('wp_dp_plugin_options_social_icon'),
            "type" => "col-right-text",
            "help_text" => ""
        );
        // wp_dp Add-ons.
        /**
         * Apply the filters by calling the 'wp_dp__plugin_addons_options' function we
         * "hooked" to 'wp_dp__plugin_addons_options' using the add_filter() function above.
         */
        $wp_dp_setting_options = apply_filters('wp_dp__plugin_addons_options', $wp_dp_setting_options);
        // End wp_dp Add-ons.

        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_import_export'),
            "fontawesome" => 'icon-refresh3',
            "id" => "tab-import-export-options",
            "std" => "",
            "type" => "main-heading",
            "options" => ""
        );


        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_import_export'),
            "id" => "tab-import-export-options",
            "extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_plugin_options_import_export') . '"',
            "type" => "sub-heading"
        );


        $wp_dp_setting_options[] = array( "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_backup'),
            "desc" => "",
            "hint_text" => '',
            "id" => "backup_options",
            "std" => "",
            "type" => "generate_backup"
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_user_import_export'),
            "id" => "user-import-export",
            "std" => wp_dp_plugin_text_srt('wp_dp_plugin_options_user_import_export'),
            "type" => "section",
            "options" => ""
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_import_user_data'),
            "desc" => "",
            "hint_text" => '',
            "id" => "backup_options",
            "std" => "",
            "type" => "user_import_export",
        );

        $wp_dp_setting_options[] = array( 'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_backup_locations'),
            'desc' => '',
            'hint_text' => '',
            'id' => 'backup_locations',
            'std' => '',
            'type' => 'backup_locations'
        );

        $wp_dp_setting_options[] = array( 'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_backup_listing_type_cats'),
            'desc' => '',
            'hint_text' => '',
            'id' => 'backup_listing_type_categories',
            'std' => '',
            'type' => 'backup_listing_type_categories'
        );

        $wp_dp_setting_options[] = array( "col_heading" => wp_dp_plugin_text_srt('wp_dp_plugin_options_import_export'),
            "type" => "col-right-text",
            "help_text" => ""
        );

        // advance settings
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_adv_settings'),
            "fontawesome" => 'icon-cog',
            "id" => "tab-advance-settings",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_adv_settings'),
            "id" => "tab-advance-settings",
            "extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_plugin_options_adv_settings') . '"',
            "type" => "sub-heading"
        );

        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_success_msg_stings'),
            'id' => 'listing-success-settings',
            'std' => wp_dp_plugin_text_srt('wp_dp_plugin_options_success_msg_stings'),
            'type' => 'section',
            'options' => ''
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_success_msg_img'),
            "desc" => "",
            "hint_text" => '',
            "id" => "listing_success_image",
            "std" => "",
            "type" => "upload"
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_success_editor'),
            "desc" => "",
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_success_msg_hint'),
            "id" => "listing_success_message",
            "std" => '',
            "type" => "textarea",
            'wp_dp_editor' => true,
            'hint_text' => '',
        );
        
        
        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_demo_user_login'),
            'id' => 'advance-settings',
            'std' => wp_dp_plugin_text_srt('wp_dp_plugin_options_demo_user_login'),
            'type' => 'section',
            'options' => ''
        );


        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_demo_user_login'),
            "desc" => "",
            "hint_text" => '',
            "id" => "demo_user_login_switch",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );

        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_demo_user_modification_allowed'),
            "desc" => "",
            "hint_text" => '',
            "id" => "demo_user_modification_allowed_switch",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );

        $wp_dp_agency_list = array( '' => wp_dp_plugin_text_srt('wp_dp_plugin_options_slct_agency') );
        $wp_dp_member_list = array( '' => wp_dp_plugin_text_srt('wp_dp_plugin_options_slct_member') );

        $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_options_demo_user'),
            "desc" => "",
            "hint_text" => '',
            "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_demo_user_hint'),
            'id' => 'demo_user_agency',
            "std" => "",
            "classes" => "chosen-select",
            "type" => "custom_select",
            'main_wraper' => true,
            'main_wraper_class' => 'demo_user_agency_holder',
            'main_wraper_extra' => ' onclick="wp_dp_load_dropdown_values(\'demo_user_agency_holder\', \'demo_user_agency\', \'wp_dp_load_all_agencies_options\');"',
            'markup' => '<span class="select-loader"></span>',
            "options" => $wp_dp_agency_list,
        );


        $wp_dp_setting_options[] = array(
            "col_heading" => "",
            "type" => "col-right-text",
            "help_text" => ""
        );
      

        $wp_dp_setting_options[] = array(
            "col_heading" => '',
            "type" => "col-right-text",
            "help_text" => ""
        );
        
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_review_settings'),
            "fontawesome" => 'icon-star',
            "id" => "tab-review-settings",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );
        
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_plugin_options_review_settings'),
            "id" => "tab-review-settings",
            "extra" => 'class="wp_dp_tab_block" data-title="' . wp_dp_plugin_text_srt('wp_dp_plugin_options_review_settings') . '"',
            "type" => "sub-heading"
        );
        
                $wp_dp_setting_options[] = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_reviews_listing_review_flag_options'),
            'id' => 'review-flag-options',
            'std' => wp_dp_plugin_text_srt('wp_dp_reviews_listing_review_flag_options'),
            'type' => 'section',
            'options' => ''
        );
        $wp_dp_setting_options[] = array(
            "name" => wp_dp_plugin_text_srt('wp_dp_reviews_listing_review_flag_options'),
            "desc" => "",
            "hint_text" => '',
            "echo" => true,
            "id" => "review_flag_opts",
            "type" => "review_flags",
        );

        $wp_dp_setting_options[] = array(
            "type" => "col-right-text",
            "extra" => "div",
        );
        

        update_option('wp_dp_plugin_data', $wp_dp_setting_options);
    }

}
$output = '';
$output .= '</div>';
