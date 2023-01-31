<?php

/**
 * Static string 5
 */
if ( ! class_exists('wp_dp_plugin_all_strings_5') ) {

    class wp_dp_plugin_all_strings_5 {

        public function __construct() {

            add_filter('wp_dp_plugin_text_strings', array( $this, 'wp_dp_plugin_text_strings_callback' ), 4);
        }

        public function wp_dp_plugin_text_strings_callback($wp_dp_static_text) {
            global $wp_dp_static_text;

            /*
             * Common
             */
            $wp_dp_static_text['wp_dp_select_proprty_type'] = esc_html__('Select Listing Types', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_description'] = esc_html__('Description', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_features_element'] = esc_html__('Features Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_design_skeches_element'] = esc_html__('Design / Sketches Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_view_all'] = esc_html__('View All', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_viewin_eglement'] = esc_html__('Viewing Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_image_gallery_element'] = esc_html__('Image Gallery Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_social_share_element'] = esc_html__('Social Share Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_print_switch_element'] = esc_html__('Print Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_switch_element'] = esc_html__('Claim Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_flag_switch_element'] = esc_html__('Flag Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_location_map_element'] = esc_html__('Map Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_renew_listing'] = esc_html__('Renew', 'wp-dp');
            $wp_dp_static_text['wp_dp_opening_hours_element'] = esc_html__('Opening Hours Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_files_attchments_options_elements'] = esc_html__('Files Attachments Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_list_meta_listing_video_element'] = esc_html__('Video Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_listing_virtual_tour_element'] = esc_html__('360&deg; Virtual Tour Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_near_by_options_element'] = esc_html__('Near By Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_yelp_places_element'] = esc_html__('Yelp Places Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_walk_score_element'] = esc_html__('Walk Scores Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_floor_plans_options_element'] = esc_html__('Floor Plans Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_list_meta_for_sale_element'] = esc_html__('For Sale Element', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_detail_page_style'] = esc_html__('Pages Style', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_suggested_tags_desc'] = esc_html__('Only the tags selected in this field will be available while adding / editing the listing. Tags help user while searching the listings.', 'wp-dp');

            $wp_dp_static_text['wp_dp_listing_type_features_element_desc'] = esc_html__('If swtich is set to "OFF" the features wont be showing on the listing\'s detail page for this type, event if it is enabled from the listing\'s settings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_tags_element_desc'] = esc_html__('If swtich is set to "OFF" the tags wont be showing on the listing\'s detail page for this type, event if it is enabled from the listing\'s settings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_viewing_desc'] = esc_html__('If swtich is set to "OFF" the listings for this type will not have the functionality for adding viewing while creating / updating.', 'wp-dp');
            $wp_dp_static_text['wp_dp_image_gallery_desc'] = esc_html__('If swtich is set to "OFF" the listings for this type will not have the functionality for images gallery.', 'wp-dp');
            $wp_dp_static_text['wp_dp_social_share_desc'] = esc_html__('If swtich is set to "OFF" the listings for this type will not have the functionality for social sharing.', 'wp-dp');
            $wp_dp_static_text['wp_dp_print_switch_desc'] = esc_html__('If swtich is set to "OFF" the print option will not be enabled on the listings for this type.', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_switch_desc'] = esc_html__('If swtich is set to "OFF" the claiming the listing functionality will not be enabled on this type\'s listings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_flag_switch_desc'] = esc_html__('If swtich is set to "OFF" flaging the listing functionality will not be enabled for the listings of this type.', 'wp-dp');
            $wp_dp_static_text['wp_dp_location_map_desc'] = esc_html__('If swtich is set to "OFF" the map within the content area for the listings will not be showing.', 'wp-dp');
            $wp_dp_static_text['wp_dp_opening_hours_element_desc'] = esc_html__('If swtich is set to "OFF" the opening hours functionality will not be enabled for the listings of this type.', 'wp-dp');
            $wp_dp_static_text['wp_dp_files_attchments_options_desc'] = esc_html__('If swtich is set to "OFF" the attaching the files functionality will not be enabled for listing\'s detail page of this type, event if it is enabled from the listing\'s settings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_list_meta_listing_video_desc'] = esc_html__('If swtich is set to "OFF" the video section on the listings detail page will not be enabled for this type, event if it is enabled from the listing\'s settings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_listing_virtual_tour_meta_desc'] = esc_html__('If swtich is set to "OFF" the 360&deg; Virtual Tour section on the listings detail page will not be enabled for this type.', 'wp-dp');
            $wp_dp_static_text['wp_dp_near_by_options_desc'] = esc_html__('If swtich is set to "OFF" the near by section with the listings will not be showing on the listing\'s detail page for this type', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_yelp_places_desc'] = esc_html__('If swtich is set to "OFF" the yelp place wont be showing on the listing\'s detail page for this type, event if it is enabled from the listing\'s settings. Only enable this swtich if the listing type is about realestate.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_walk_score_desc'] = esc_html__('If swtich is set to "OFF" the walk score section wont be showing on the listing\'s detail page for this type. Only enable this swtich if the listing type is about realestate.', 'wp-dp');
            $wp_dp_static_text['wp_dp_floor_design_skeches_desc'] = esc_html__('If swtich is set to "OFF" the design / sketches section wont be showing on the listing\'s detail page for this type.', 'wp-dp');
            $wp_dp_static_text['wp_dp_list_meta_appartment_element_desc'] = esc_html__('If swtich is set to "OFF" the appartment for sale section wont be showing on the listing\'s detail page for this type, event if it is enabled from the listing\'s settings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_show_more_less_desc_desc'] = esc_html__('If swtich is set to "OFF" a small chunk of description for the listing will be displayed with "show more", on the show more click user can see the full description.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_description_length_desc'] = esc_html__('Enter the number of words you want to show before the show more appears, this will only work if the above "Show More/Less Description" is enabled.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_detail_page_desc'] = esc_html__('If "Page Style" from the listing detail page settings is set to "Default view", the view selected in this field will be applied to that listing.', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_new_listing_quick_tip_text'] = esc_html__('You must enter your business information here. Information added here will not appear in search result until approved by admin. After approval, you will receive email notification with complete instructions explaining how your advertisement will be displayed there.', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_new_listing_quick_tip'] = esc_html__('Quick Tip', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_new_listing_holiday'] = esc_html__('Add holidays to listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_new_listing_holiday_text'] = esc_html__('Define the work schedule, work hours per day and holidays. This will associated with listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_location_option'] = esc_html__('Select Location Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_listing_virtual_tour'] = esc_html__('360&deg; Virtual Tour', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_listing_virtual_tour_desc'] = esc_html__('Embed Iframe code', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_listing_visibility'] = esc_html__('Visibility', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_listing_public'] = esc_html__('Public', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_listing_invisible'] = esc_html__('Private', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_invisible_update_success'] = esc_html__('You have changed listing visibility successfully.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_invisible_update_error'] = esc_html__('Sorry! Listing visibility has not been changed.', 'wp-dp');
            $wp_dp_static_text['wp_dp_print'] = esc_html__('Print', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_yelp_places'] = esc_html__('Yelp Places', 'wp-dp');
            $wp_dp_static_text['wp_dp_location_element_listings_listed'] = esc_html__('Listings Listed', 'wp-dp');
            $wp_dp_static_text['wp_dp_search_element_title_colorrr'] = esc_html__('Element Title Color', 'wp-dp');
            $wp_dp_static_text['wp_dp_location_element_style_classic'] = esc_html__('Classic', 'wp-dp');
            $wp_dp_static_text['wp_dp_element_listing_excerpt_length'] = esc_html__('Length of Excerpt', 'wp-dp');
            $wp_dp_static_text['wp_dp_element_listing_excerpt_length_hint'] = esc_html__('Add number of excerpt words here for display on listings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_widget_top_listings_styles'] = esc_html__('Listings Styles', 'wp-dp');
            $wp_dp_static_text['wp_dp_widget_top_listings_styles_classic'] = esc_html__('Classic', 'wp-dp');
            $wp_dp_static_text['wp_dp_widget_top_listings_styles_simple'] = esc_html__('Simple', 'wp-dp');
            $wp_dp_static_text['wp_dp_widget_top_listings_styles_modern'] = esc_html__('Modern', 'wp-dp');
            $wp_dp_static_text['wp_dp_single_prop_gallery_count_photos'] = esc_html__('Photos', 'wp-dp');
            $wp_dp_static_text['wp_dp_single_prop_print_this_page'] = esc_html__('Print this Page', 'wp-dp');
            $wp_dp_static_text['wp_dp_single_prop_email_a_frnd'] = esc_html__('Email a friend', 'wp-dp');
            $wp_dp_static_text['wp_dp_widget_top_listings_title_length'] = esc_html__('Listing Title Length', 'wp-dp');
            $wp_dp_static_text['wp_dp_locations_view_all_locations'] = esc_html__('view all locations', 'wp-dp');
            $wp_dp_static_text['wp_dp_video_url_sites_example'] = esc_html__('Optional', 'wp-dp');

            /* Start Dashboard Notification */
            $wp_dp_static_text['wp_dp_notification_hide_your_listing'] = esc_html__('hide your listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_notification_removed_your_listing_from_hidden'] = esc_html__('removed your listing from hidden', 'wp-dp');
            $wp_dp_static_text['wp_dp_notification_added_notes_on_your_listing'] = esc_html__('added notes on your listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_notification_removed_notes_on_your_listing'] = esc_html__('removed notes on your listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_notification_submitted_enquiry'] = esc_html__('has submitted an enquiry on your listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_notification_you_has_submitted_enquiry'] = esc_html__('You have submitted an enquiry on ', 'wp-dp');
            $wp_dp_static_text['wp_dp_notification_submitted_viewing'] = esc_html__('request a viewing on your listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_notification_update_viewing_status'] = esc_html__('your viewing request', 'wp-dp');
            /* End Dashboard Notification */

            /*
             * Options Single Listing Options
             */
            $wp_dp_static_text['wp_dp_single_options_view_1_heading'] = esc_html__('View 1 Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_single_options_view_2_heading'] = esc_html__('View 2 Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_single_options_view_3_heading'] = esc_html__('View 3 Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_single_options_view_4_heading'] = esc_html__('View 4 Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_single_options_view_5_heading'] = esc_html__('View 5 Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_single_options_top_gallery_with_map'] = esc_html__('Top Gallery', 'wp-dp');
            $wp_dp_static_text['wp_dp_single_options_sidebar_contact_info'] = esc_html__('Sidebar Member with Contact Box', 'wp-dp');

            /*
             * Claims/Flags Module
             */
            
            $wp_dp_static_text['wp_dp_claims_total'] = esc_html__('Total', 'wp-dp');
            $wp_dp_static_text['wp_dp_claims_pending'] = esc_html__('Pending', 'wp-dp');
            $wp_dp_static_text['wp_dp_claims_resolved'] = esc_html__('Resolved', 'wp-dp');
            
            $wp_dp_static_text['wp_dp_received_listing_claim'] = esc_html__('Received Listing Claim', 'wp-dp');
            $wp_dp_static_text['wp_dp_print_switch'] = esc_html__('Print Option', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_switch'] = esc_html__('Claim Option', 'wp-dp');
            $wp_dp_static_text['wp_dp_flag_switch'] = esc_html__('Flag Option', 'wp-dp');

            $wp_dp_static_text['wp_dp_received_listing_claim_email'] = esc_html__('This template is used to send email when administrator receive listing claim.', 'wp-dp');
            $wp_dp_static_text['wp_dp_received_listing_claim_email_subject'] = esc_html__('A new claim listing has been received on your website', 'wp-dp');
            $wp_dp_static_text['wp_dp_received_listing_flag'] = esc_html__('Received Flag Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_received_listing_flag_email'] = esc_html__('This template is used to send email when administrator receive flag listing.', 'wp-dp');
            $wp_dp_static_text['wp_dp_received_listing_flag_email_subject'] = esc_html__('A new flag listing has been received on your website', 'wp-dp');

            $wp_dp_static_text['wp_dp_received_listing_favourite'] = esc_html__('User Favourite Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_received_listing_favourite_description'] = esc_html__('This template is used to send email when user favourite listing.', 'wp-dp');
            $wp_dp_static_text['wp_dp_received_listing_favourite_email_subject'] = esc_html__('A new user has favourite your listing', 'wp-dp');

            /*
             * Forgot Password
             */
            $wp_dp_static_text['wp_dp_forgot_pass_enter_username_email'] = esc_html__('Enter Username/Email Address', 'wp-dp');
            $wp_dp_static_text['wp_dp_forgot_pass_enter_new_pass'] = esc_html__('Enter new password', 'wp-dp');
            $wp_dp_static_text['wp_dp_forgot_pass_confirm_new_pass'] = esc_html__('Confirm new password', 'wp-dp');
            $wp_dp_static_text['wp_dp_forgot_pass_confirm_not_match'] = esc_html__('The passwords do not match.', 'wp-dp');
            $wp_dp_static_text['wp_dp_forgot_pass_username_email_empty'] = esc_html__('Please enter a username or email address.', 'wp-dp');
            $wp_dp_static_text['wp_dp_forgot_pass_username_error'] = esc_html__('There is no user registered with that username.', 'wp-dp');
            $wp_dp_static_text['wp_dp_forgot_pass_email_error'] = esc_html__('There is no user registered with that email address.', 'wp-dp');
            $wp_dp_static_text['wp_dp_forgot_pass_not_allow'] = esc_html__('Sorry! password reset is not allowed.', 'wp-dp');
            $wp_dp_static_text['wp_dp_forgot_pass_wp_error'] = esc_html__('Sorry! there is a wp error.', 'wp-dp');
            $wp_dp_static_text['wp_dp_forgot_pass_link_sent'] = esc_html__('Link for password reset has been emailed to you. Please check your email.', 'wp-dp');
            $wp_dp_static_text['wp_dp_forgot_pass_title'] = esc_html__('%s Password Reset', 'wp-dp');
            $wp_dp_static_text['wp_dp_forgot_pass_link_invalid'] = esc_html__('Your password reset link appears to be invalid. Please request a new link below.', 'wp-dp');
            $wp_dp_static_text['wp_dp_forgot_pass_link_expired'] = esc_html__('Your password reset link has expired. Please request a new link below.', 'wp-dp');

            /*
             * Backend Review
             */
            $wp_dp_static_text['wp_dp_review_id_column'] = esc_html__('Review ID', 'wp-dp');
            $wp_dp_static_text['wp_dp_review_member_name_column'] = esc_html__('Member Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_review_listing_name_column'] = esc_html__('Listing Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_review_helpful_column'] = esc_html__('Helpful', 'wp-dp');
            $wp_dp_static_text['wp_dp_review_flag_column'] = esc_html__('Flag', 'wp-dp');
            $wp_dp_static_text['wp_dp_review_start_date_field_label'] = esc_html__('Start Date', 'wp-dp');
            $wp_dp_static_text['wp_dp_review_end_date_field_label'] = esc_html__('End Date', 'wp-dp');

            // Email to friend strings
            $wp_dp_static_text['wp_dp_email_to_frnd_mail_subject'] = esc_html__('Email from friend', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_to_frnd_mail_name_field_error'] = esc_html__('Name field should not be empty.', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_to_frnd_mail_email_field_empty'] = esc_html__('Email field should not be empty.', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_to_frnd_mail_email_field_error'] = esc_html__('Email field is not valid.', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_to_frnd_mail_msg_field_error'] = esc_html__('Message field should not be empty.', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_to_msg_txt_name'] = esc_html__('Your Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_to_msg_txt_listing'] = esc_html__('Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_to_msg_txt_msg'] = esc_html__('Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_to_msg_success_msg'] = esc_html__('Message sent successfully.', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_to_msg_error_msg'] = esc_html__('Message not sent.', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_to_form_email_to_fr'] = esc_html__('Email to Friend', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_to_form_your_name'] = esc_html__('Your Name *', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_to_form_friends_email'] = esc_html__('Friend\'s email address *', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_to_form_your_message'] = esc_html__('Your Message *', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_to_form_send_message'] = esc_html__('Send Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_element_title_color'] = esc_html__('Element Title Color', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_element_title_color_hint'] = esc_html__('Set the element title color here', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_element_subtitle_color'] = esc_html__('Element Subtitle Color', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_element_subtitle_color_hint'] = esc_html__('Set the element subtitle color here', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_element_search_modern_v4_search'] = esc_html__('Search Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_element_listings_grid_size'] = esc_html__('Grid Size', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_element_listings_grid_size_hint'] = esc_html__('Set the column size of the view.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_element_listings_grid_size_4_column'] = esc_html__('Four Column', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_element_listings_grid_size_3_column'] = esc_html__('Three Column', 'wp-dp');

            $wp_dp_static_text['wp_dp_plugin_element_title_seperator'] = esc_html__('Seperator', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_element_title_seperator_hint'] = esc_html__('Set the element title seperator here', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_element_title_seperator_style_none'] = esc_html__('None', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_element_title_seperator_style_classic'] = esc_html__('Classic Seperator', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_element_title_seperator_style_zigzag'] = esc_html__('Zigzag Seperator', 'wp-dp');

            $wp_dp_static_text['wp_dp_map_full_screen_text'] = esc_html__('Full Screen', 'wp-dp');
            $wp_dp_static_text['wp_dp_map_exit_full_screen_text'] = esc_html__('Exit Full Screen', 'wp-dp');

            // sold listing strings
            $wp_dp_static_text['wp_dp_plugin_listing_sold'] = esc_html__('Listing Sold', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_sold_confirm_notice'] = esc_html__('Do you really want to mark this listing as sold. You cannot undo this action. Proceed anyway?', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_sold_action_failed_notice'] = esc_html__('Action Failed.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_sold_mark_as_sold'] = esc_html__('Mark as Sold', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_sold_marked_as_sold'] = esc_html__('Listing marked as Sold.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_sold_single_txt'] = esc_html__('Sold', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_already_sold_txt'] = esc_html__('Already Sold', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_sold_out_txt'] = esc_html__('Sold Out', 'wp-dp');

            $wp_dp_static_text['wp_dp_listing_sold_sold_listings'] = esc_html__('Sold out', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_visibility_updated_msg'] = esc_html__('your DB structure updated successfully.', 'wp-dp');

            // Start Activity Notifications Modules Options.
            $wp_dp_static_text['wp_dp_post_type_notification_name'] = esc_html__('Notifications', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_notification_singular_name'] = esc_html__('Notification', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_notification_not_found'] = esc_html__('Notification not found', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_notification_not_found_in_trash'] = esc_html__('Notification not found', 'wp-dp');
            $wp_dp_static_text['wp_dp_activity_notifications'] = esc_html__('Notifications', 'wp-dp');
            $wp_dp_static_text['wp_dp_activity_notifications_settings'] = esc_html__('Activity Notifications Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_activity_notifications_heading'] = esc_html__('Activity Notifications', 'wp-dp');
            $wp_dp_static_text['wp_dp_activity_notifications_notifications'] = esc_html__('Notifications', 'wp-dp');
            $wp_dp_static_text['wp_dp_activity_notifications_notification'] = esc_html__('Notification', 'wp-dp');
            $wp_dp_static_text['wp_dp_activity_notifications_add_notification'] = esc_html__('Add Notification', 'wp-dp');
            $wp_dp_static_text['wp_dp_activity_notifications_edit_notification'] = esc_html__('Edit Notification', 'wp-dp');
            $wp_dp_static_text['wp_dp_activity_notifications_turn_on'] = esc_html__('Trun on this switch to show notifications for each user on member dashboard.', 'wp-dp');
            $wp_dp_static_text['wp_dp_activity_notification_message'] = esc_html__('Notification Message', 'wp-dp');
			$wp_dp_static_text['wp_dp_activity_notification_member'] = esc_html__('Listing Owner', 'wp-dp');
			$wp_dp_static_text['wp_dp_activity_notification_listing_name'] = esc_html__('Listing Name', 'wp-dp');
			$wp_dp_static_text['wp_dp_activity_notification_received_date'] = esc_html__('Received Date', 'wp-dp');
			$wp_dp_static_text['wp_dp_activity_notification_recv_date_ago'] = esc_html__('ago', 'wp-dp');

            // Start Helper Generals.
            $wp_dp_static_text['wp_dp_helper_currency'] = esc_html__('Currency', 'wp-dp');
            $wp_dp_static_text['wp_dp_helper_select_currency'] = esc_html__('Select Currency', 'wp-dp');
            $wp_dp_static_text['wp_dp_helper_member_msg_received'] = esc_html__('Member Contact Message Received', 'wp-dp');
            $wp_dp_static_text['wp_dp_helper_member_email_not_valid'] = esc_html__('Member email is invalid or empty', 'wp-dp');
            $wp_dp_static_text['wp_dp_helper_name_empty'] = esc_html__('Name should not be empty', 'wp-dp');
            $wp_dp_static_text['wp_dp_helper_email_empty'] = esc_html__('Email should not be empty', 'wp-dp');
            $wp_dp_static_text['wp_dp_helper_email_not_valid'] = esc_html__('Not a valid email address', 'wp-dp');
            $wp_dp_static_text['wp_dp_helper_msg_empty'] = esc_html__('Message should not be empty', 'wp-dp');
            $wp_dp_static_text['wp_dp_helper_name'] = esc_html__('Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_helper_email'] = esc_html__('Email', 'wp-dp');
            $wp_dp_static_text['wp_dp_helper_message'] = esc_html__('Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_helper_ip_address'] = esc_html__('IP Address', 'wp-dp');
            $wp_dp_static_text['wp_dp_helper_sent_msg_successfully'] = esc_html__('Sent message successfully', 'wp-dp');
            $wp_dp_static_text['wp_dp_helper_msg_not_sent'] = esc_html__('Message not sent', 'wp-dp');
            $wp_dp_static_text['wp_dp_helper_read_terms_conditions'] = esc_html__('Please indicate that you have read and agree to the Terms and Conditions and Privacy Policy', 'wp-dp');

            // Start Price Table Meta.
            $wp_dp_static_text['wp_dp_price_table_add_package'] = esc_html__('Add Package', 'wp-dp');
            $wp_dp_static_text['wp_dp_price_table_add_row'] = esc_html__('Add Row', 'wp-dp');
            $wp_dp_static_text['wp_dp_price_table_add_section'] = esc_html__('Add Section', 'wp-dp');
            $wp_dp_static_text['wp_dp_price_table_reset_all'] = esc_html__('Reset All', 'wp-dp');
            $wp_dp_static_text['wp_dp_price_table_buy_now'] = esc_html__('Buy Now', 'wp-dp');

            // End Price Table Meta.
            // Start Social Login.
            $wp_dp_static_text['wp_dp_social_login_check_fb_account'] = esc_html__('Please check facebook account developers settings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_social_login_profile_already_linked'] = esc_html__('This profile is already linked with other account. Linking process failed!', 'wp-dp');
            $wp_dp_static_text['wp_dp_social_login_contact_site_admin'] = esc_html__('Contact site admin to provide a valid Twitter connect credentials.', 'wp-dp');

            // End Social Login.
            
            /*
             * search Modern
             */
            $wp_dp_static_text['wp_dp_listing_search_view_enter_kywrd'] = esc_html__('Enter Your Keyword', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_view_enter_kywrd_label'] = esc_html__('Keyword', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_view_enter_location_label'] = esc_html__('Location', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_view_enter_type_label'] = esc_html__('Select Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_view_enter_listing_type_label'] = esc_html__('Listing Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_view_label_color'] = esc_html__('Label Color', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_view_label_color_hint'] = esc_html__('Select a color for search fields labels(modern view only).', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_view_element_seperator'] = esc_html__('Element Seperator', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_view_element_seperator_hint'] = esc_html__('Select yes/no for element title/subtitle seperator.', 'wp-dp');
            $wp_dp_static_text['wp_dp_element_view_simplee'] = esc_html__('Simple', 'wp-dp');
            $wp_dp_static_text['wp_dp_element_view_advancee'] = esc_html__('Advance', 'wp-dp');
            $wp_dp_static_text['wp_dp_texonomy_location_location_img'] = esc_html__('Location Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_element_location_shortcode_name'] = esc_html__('DB: Locations', 'wp-dp');
            $wp_dp_static_text['wp_dp_element_location_shortcode_options'] = esc_html__('Locations Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_element_location_shortcode_locations'] = esc_html__('Locations', 'wp-dp');
            $wp_dp_static_text['wp_dp_element_location_shortcode_locations_hint'] = esc_html__('Select locations from this dropdown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_element_location_shortcode_all_locations_url'] = esc_html__('All Location URL', 'wp-dp');
            $wp_dp_static_text['wp_dp_element_location_shortcode_all_locations_url_hint'] = esc_html__('Enter a page url to show all locations', 'wp-dp');
            $wp_dp_static_text['wp_dp_element_location_shortcode_styles'] = esc_html__('Styles', 'wp-dp');
            $wp_dp_static_text['wp_dp_element_cat_shortcode_styles_hint'] = esc_html__('Select a category style from this dropdown.', 'wp-dp');
            
            $wp_dp_static_text['wp_dp_element_location_shortcode_styles_hint'] = esc_html__('Select a location style from this dropdown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_element_location_shortcode_styles_simple'] = esc_html__('Simple', 'wp-dp');
            $wp_dp_static_text['wp_dp_element_location_shortcode_styles_modern'] = esc_html__('Modern', 'wp-dp');
            $wp_dp_static_text['wp_dp_element_search_advance_view_placeholder_enter_word'] = esc_html__('Enter Keyword ');
            $wp_dp_static_text['wp_dp_element_search_advance_view_placeholder_ie'] = esc_html__(' i.e   Modern Apartment', 'wp-dp');
            $wp_dp_static_text['wp_dp_element_tooltip_icon_camera'] = esc_html__('Photos', 'wp-dp');
            $wp_dp_static_text['wp_dp_shortcode_members_slider'] = esc_html__('Grid Slider', 'wp-dp');
            $wp_dp_static_text['wp_dp_map_places_found'] = esc_html__('results found', 'wp-dp');
            $wp_dp_static_text['wp_dp_map_places_radius'] = esc_html__('Radius', 'wp-dp');
            $wp_dp_static_text['wp_dp_map_places_put_radius_value'] = esc_html__('Select Radius value (km)', 'wp-dp');
            $wp_dp_static_text['wp_dp_hidden_listings'] = esc_html__('Hidden Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_notes_notes'] = esc_html__('Listing Notes', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_notes_add_notes'] = esc_html__('Add Notes', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_notes_notes_added'] = esc_html__('Notes added', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_notes_prop_notes_deleted'] = esc_html__('Listing notes deleted.', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_notes_listings_notes'] = esc_html__('Listings Notes', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_notes_no_result_notes'] = esc_html__('No result found.', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_notes_no_500_words_allow'] = esc_html__('Text more then 500 characters not allowed.', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_notes_saved_msg'] = esc_html__('Listing notes saved.', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_notes_removed_msg'] = esc_html__('Listing notes removed successfully.', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_notes_not_removed_msg'] = esc_html__('Listing notes not removed.', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_notes_add_notes_for'] = esc_html__('Add Notes for', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_notes_type_here'] = esc_html__('Type here...', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_notes_submit'] = esc_html__('Submit', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_notes_max_chars_allowed'] = esc_html__('Max characters allowed 500.', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_notes_some_txt_error'] = esc_html__('Please type some text first.', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_notes_show_more'] = esc_html__('Show more', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_notes_show_less'] = esc_html__('Show less', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_detail_contact_success_mgs'] = esc_html__('Email sent successfully', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_detail_contact_error_mgs'] = esc_html__('There is some error in sending email.', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_detail_contact_cnt_agent'] = esc_html__('Contact Agent', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_detail_contact_cnt_agentt'] = esc_html__('Contact', 'wp-dp');

            $wp_dp_static_text['wp_dp_prop_detail_contact_cnt_num_hide'] = esc_html__('Hide', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_detail_contact_cnt_num_show'] = esc_html__('Show', 'wp-dp');
            $wp_dp_static_text['wp_dp_prop_detail_near_by_places'] = esc_html__('Near by Places', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_pkg_img_num_more_than'] = esc_html__('You cannot upload more than', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_pkg_img_num_change_pkg'] = esc_html__('images. Please change your package to upload more.', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_pkg_doc_num_change_pkg'] = esc_html__('documents. Please change your package to upload more.', 'wp-dp');
            $wp_dp_static_text['wp_dp_search_element_background_colorrr'] = esc_html__('Search Background Color', 'wp-dp');
            $wp_dp_static_text['wp_dp_search_element_style_modern_v2'] = esc_html__('Modern V2', 'wp-dp');
            $wp_dp_static_text['wp_dp_search_element_style_modern_v3'] = esc_html__('Modern V3', 'wp-dp');
            $wp_dp_static_text['wp_dp_search_element_style_modern_v4'] = esc_html__('Modern V4', 'wp-dp');

            /*
             *  start neaby places marker type
             */

            $wp_dp_static_text['wp_dp_marker_opions_accounting'] = esc_html__('Accounting', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_airport'] = esc_html__('Airport', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_amusement_park'] = esc_html__('Amusement Park', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_aquarium'] = esc_html__('Aquarium', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_art_gallery'] = esc_html__('Art Gallery', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_atm'] = esc_html__('Atm', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_bakery'] = esc_html__('Bakery', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_bank'] = esc_html__('Bank', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_bar'] = esc_html__('Bar', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_beauty_salon'] = esc_html__('Beauty Salon', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_bicycle_store'] = esc_html__('Bicycle Store', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_book_store'] = esc_html__('Book Store', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_bowling_alley'] = esc_html__('Bowling Alley', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_bus_station'] = esc_html__('Bus Station', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_cafe'] = esc_html__('Cafe', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_campground'] = esc_html__('Campground', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_car_dealer'] = esc_html__('Car Dealer', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_car_rental'] = esc_html__('Car Rental', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_car_repair'] = esc_html__('Car Repair', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_car_wash'] = esc_html__('Car Wash', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_casino'] = esc_html__('Casino', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_cemetery'] = esc_html__('Cemetery', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_church'] = esc_html__('Church', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_city_hall'] = esc_html__('City Hall', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_clothing_store'] = esc_html__('Clothing Store', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_convenience_store'] = esc_html__('Convenience Store', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_courthouse'] = esc_html__('Courthouse', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_dentist'] = esc_html__('Dentist', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_department_store'] = esc_html__('Department Store', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_doctor'] = esc_html__('Doctor', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_electrician'] = esc_html__('Electrician', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_electronics_store'] = esc_html__('Electronics Store', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_embassy'] = esc_html__('Embassy', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_establishment_deprecated'] = esc_html__('Establishment (deprecated)', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_finance_deprecated'] = esc_html__('Finance (deprecated)', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_fire_station'] = esc_html__('Fire Station', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_florist'] = esc_html__('Florist', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_food_deprecated'] = esc_html__('Food (deprecated)', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_funeral_home'] = esc_html__('Funeral Home', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_furniture_store'] = esc_html__('Furniture Store', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_gas_station'] = esc_html__('Gas Station', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_general_contractor_deprecated'] = esc_html__('General Contractor (deprecated)', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_grocery_or_supermarket_deprecated'] = esc_html__('Grocery or Supermarket (deprecated)', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_gym'] = esc_html__('Gym', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_hair_care'] = esc_html__('Hair Care', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_hardware_store'] = esc_html__('Hardware Store', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_health_deprecated'] = esc_html__('Health (deprecated)', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_hindu_temple'] = esc_html__('Hindu Temple', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_home_goods_store'] = esc_html__('Home Goods Store', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_hospital'] = esc_html__('Hospital', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_insurance_agency'] = esc_html__('Insurance Agency', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_jewelry_store'] = esc_html__('Jewelry Store', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_laundry'] = esc_html__('Laundry', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_lawyer'] = esc_html__('Lawyer', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_library'] = esc_html__('Library', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_liquor_store'] = esc_html__('Liquor Store', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_local_government_office'] = esc_html__('Local Government Office', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_locksmith'] = esc_html__('Locksmith', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_lodging'] = esc_html__('Lodging', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_meal_delivery'] = esc_html__('Meal Delivery', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_meal_takeaway'] = esc_html__('Meal Takeaway', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_mosque'] = esc_html__('Mosque', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_movie_rental'] = esc_html__('Movie Rental', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_movie_theater'] = esc_html__('Movie Theater', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_moving_company'] = esc_html__('Moving Company', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_museum'] = esc_html__('Museum', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_night_club'] = esc_html__('Night Club', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_painter'] = esc_html__('Painter', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_park'] = esc_html__('Park', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_parking'] = esc_html__('Parking', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_pet_store'] = esc_html__('Pet Store', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_pharmacy'] = esc_html__('Pharmacy', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_physiotherapist'] = esc_html__('Physiotherapist', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_place_of_worship_deprecated'] = esc_html__('Place of Worship (deprecated)', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_plumber'] = esc_html__('Plumber', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_police'] = esc_html__('Police', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_post_office'] = esc_html__('Post Office', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_real_estate_agency'] = esc_html__('Directory Box Agency', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_restaurant'] = esc_html__('Restaurant', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_roofing_contractor'] = esc_html__('Roofing Contractor', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_rv_park'] = esc_html__('Rv Park', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_school'] = esc_html__('School', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_shoe_store'] = esc_html__('Shoe Store', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_shopping_mall'] = esc_html__('Shopping Mall', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_spa'] = esc_html__('Spa', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_stadium'] = esc_html__('Stadium', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_storage'] = esc_html__('Storage', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_store'] = esc_html__('Store', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_subway_station'] = esc_html__('Subway Station', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_synagogue'] = esc_html__('Synagogue', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_taxi_stand'] = esc_html__('Taxi Stand', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_train_station'] = esc_html__('Train Station', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_transit_station'] = esc_html__('Transit Station', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_travel_agency'] = esc_html__('Travel Agency', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_university'] = esc_html__('University', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_veterinary_care'] = esc_html__('Veterinary Care', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_zoo'] = esc_html__('Zoo', 'wp-dp');

            /*
             * FAQ
             */

            $wp_dp_static_text['wp_dp_faq_add_to_list'] = esc_html__('Add FAQ to List', 'wp-dp');
            $wp_dp_static_text['wp_dp_faq_create_to_list'] = esc_html__('Create FAQ', 'wp-dp');
            $wp_dp_static_text['wp_dp_faq_update_faq'] = esc_html__('Update FAQ', 'wp-dp');
            $wp_dp_static_text['wp_dp_faq_update_to_list'] = esc_html__('Update FAQ to List', 'wp-dp');
            $wp_dp_static_text['wp_dp_faq_added_to_list'] = esc_html__('FAQ added to list successfully.', 'wp-dp');
            $wp_dp_static_text['wp_dp_faq_updated_to_list'] = esc_html__('FAQ updated to list successfully.', 'wp-dp');
            $wp_dp_static_text['wp_dp_faq_title_empty'] = esc_html__('FAQ title should not be empty.', 'wp-dp');
            $wp_dp_static_text['wp_dp_faq_desc_empty'] = esc_html__('FAQ description should not be empty.', 'wp-dp');
            $wp_dp_static_text['wp_dp_detail_page_settings'] = esc_html__('Detail Page Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_ziparchive_missing'] = esc_html__('The PHP extention "ZipArchive" is missing on your server.', 'wp-dp');
            $wp_dp_static_text['wp_dp_activity_notifications_load_more'] = esc_html__('Load More...', 'wp-dp');
            $wp_dp_static_text['wp_dp_edit_details'] = esc_html__('Edit Details', 'wp-dp');
            $wp_dp_static_text['wp_dp_edit_details_update'] = esc_html__('Update', 'wp-dp');
            $wp_dp_static_text['wp_dp_edit_details_edit'] = esc_html__('Edit', 'wp-dp');
            $wp_dp_static_text['wp_dp_price_plan_most_popular'] = esc_html__('Popular', 'wp-dp');
            $wp_dp_static_text['wp_dp_cs_var_size'] = __('Size', 'wp-dp');
            $wp_dp_static_text['wp_dp_cs_var_column_hint'] = __('Select column width. This width will be calculated depend page width.', 'wp-dp');
            $wp_dp_static_text['wp_dp_cs_var_one_half'] = __('One half', 'wp-dp');
            $wp_dp_static_text['wp_dp_cs_var_one_third'] = __('One third', 'wp-dp');
            $wp_dp_static_text['wp_dp_cs_var_two_third'] = __('Two third', 'wp-dp');
            $wp_dp_static_text['wp_dp_cs_var_one_fourth'] = __('One fourth', 'wp-dp');
            $wp_dp_static_text['wp_dp_cs_var_three_fourth'] = __('Three fourth', 'wp-dp');

            $wp_dp_static_text['wp_dp_cs_var_filter_lowest_price'] = __('Lowest Price', 'wp-dp');
            $wp_dp_static_text['wp_dp_cs_var_filter_highest_price'] = __('Highest Price', 'wp-dp');
            $wp_dp_static_text['wp_dp_cs_var_filter_listing_newest'] = __('Newest', 'wp-dp');
            $wp_dp_static_text['wp_dp_cs_var_filter_listing_relevence'] = __('Relevence', 'wp-dp');
            $wp_dp_static_text['wp_dp_cs_var_filter_listing_highest_rated'] = __('Highest Rated', 'wp-dp');
            $wp_dp_static_text['wp_dp_cs_var_filter_listing_most_viewd'] = __('Most Viewed', 'wp-dp');





            $wp_dp_static_text['wp_dp_add_listing_suggested_tags'] = esc_html__('Suggested Tags', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_listing_key_tags'] = esc_html__('Keywords/Tags', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_listing_key_tags_maximum'] = esc_html__('%s maximum', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_listing_key_tags_placeholder'] = esc_html__('Enter tags or keywords', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_listing_key_tags_limit'] = esc_html__('You can add maximum %s tags.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_tags_element'] = esc_html__('Tags Element', 'wp-dp');


            $wp_dp_static_text['wp_dp_valid_email_error'] = __('Please provide valid email address.', 'wp-dp');
            $wp_dp_static_text['wp_dp_theme_demo_error'] = __('Please select demo before continue.', 'wp-dp');

            $wp_dp_static_text['wp_dp_plugin_options_user_reg_package'] = __('User Registration Package', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_user_reg_package_asign'] = __('Assign Package on Registration', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_user_reg_package_asign_hint'] = __('Assign one default package for every user at registration.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_user_reg_package_txt'] = __('Package', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_user_reg_package_select'] = __('Select Package', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_user_reg_package_txt_hint'] = __('Choose one from packages which will assign every user at registration. Only free packages will list here. Add or manage packages <a href="' . admin_url('edit.php?post_type=packages') . '" target="_blank">here</a>', 'wp-dp');


            $wp_dp_static_text['wp_dp_plugin_options_success_msg_stings'] = __('Listing Success Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_success_msg'] = __('Success Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_success_editor'] = __('Success Editor', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_success_msg_img'] = __('Success Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_success_msg_hint'] = __('This message will show when user listing will auto approved.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_success_msg_default'] = __('You have successfully created your listing, to add more details, go to your email inbox for login details.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_success_rev_msg'] = __('Review Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_success_msg_review'] = __('Your listing is under review for approval.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_success_rev_msg_hint'] = __('This message will show when user listing will not auto approved.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_success_msg_phn'] = __('Success Phone', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_success_msg_fax'] = __('Success Fax', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_success_msg_email'] = __('Success Email', 'wp-dp');
            
            
            $wp_dp_static_text['wp_dp_plugin_options_review_settings'] = __('Reviews', 'wp-dp');


            $wp_dp_static_text['wp_dp_tabs_map_map_view'] = __('Map View', 'wp-dp');
            $wp_dp_static_text['wp_dp_tabs_map_street'] = __('Street View', 'wp-dp');
            $wp_dp_static_text['wp_dp_tabs_map_directions'] = __('Directions', 'wp-dp');
            $wp_dp_static_text['wp_dp_tabs_map_map_enter_location'] = __('Enter Location', 'wp-dp');
            $wp_dp_static_text['wp_dp_tabs_map_map_driving'] = __('Driving', 'wp-dp');
            $wp_dp_static_text['wp_dp_tabs_map_map_walking'] = __('Walking', 'wp-dp');
            $wp_dp_static_text['wp_dp_tabs_map_map_bicycling'] = __('Bicycling', 'wp-dp');
            $wp_dp_static_text['wp_dp_tabs_map_map_transit'] = __('Transit', 'wp-dp');
            $wp_dp_static_text['wp_dp_tabs_map_map_get_directions'] = __('Get Directions', 'wp-dp');
            $wp_dp_static_text['wp_dp_search_element_listig_categories'] = __('Listing Categories', 'wp-dp');

            $wp_dp_static_text['wp_dp_ad_block_disabling'] = __('Ad block is installed and active. Please support us by disabling it.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_filter_all_listing_types'] = __('All Listing Types', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_filter_all_listing_type_categories'] = __('All Categories', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_category'] = __('Listing Category', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_categories'] = __('Listing Categories', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_type_alll'] = __('All', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_type_all_categories'] = __('All Categories', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_select_listings'] = __('Select Listings', 'wp-dp');



            $wp_dp_static_text['wp_dp_hide_compare_note_listings'] = __('Listing  Hide/Notes Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_listings_hint'] = __('Turn this switch on/off to show/hide listings compare functionality in whole site', 'wp-dp');
            $wp_dp_static_text['wp_dp_hide_listings_hint'] = __('Turn this switch on/off to show/hide listings hide functionality in whole site', 'wp-dp');
            $wp_dp_static_text['wp_dp_hide_listings_func'] = __('Hide Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_notes_listings_hint'] = __('Turn this switch on/off to show/hide listings notes functionality in whole site', 'wp-dp');
            $wp_dp_static_text['wp_dp_notes_listings_func'] = __('Listings Notes', 'wp-dp');
            $wp_dp_static_text['wp_dp_popup_or'] = __('or', 'wp-dp');

            $wp_dp_static_text['wp_dp_dashboard_edit_profile'] = __("Edit Profile", 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_user_statics_all_listings'] = __('All Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_user_listing_statics_published'] = __('Published', 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_user_listing_statics_pending'] = __('Pending', 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_user_listing_statics_expired'] = __('Expired', 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_user_published_listings'] = __('Published Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_user_pending_listings'] = __('Pending Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_user_expired_listings'] = __('Expired Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_all_listings_not_found'] = __("You don't have any listings.", 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_published_listings_not_found'] = __("You don't have any published listings.", 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_pending_listings_not_found'] = __("You don't have any pending listings.", 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_expired_listings_not_found'] = __("You don't have any expired listings.", 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_user_reviews_submitted'] = __('Reviews Submited', 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_user_reviews_received'] = __('Reviews Received', 'wp-dp');

            $wp_dp_static_text['wp_dp_dashboard_user_listing_favorites'] = __('Favorites', 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_user_listing_favorite'] = __('Favorite', 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_user_listing_views'] = __('Views', 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_user_listing_orders'] = __('Orders', 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_user_listing_days_left'] = __('days left', 'wp-dp');
            $wp_dp_static_text['wp_dp_transactions_invoices'] = __('Invoices', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_ref_no'] = __('Ref no', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_date_issued'] = __('Date Issued', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_payment_method'] = __('Payment Method', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_submitted_messages'] = __('Submitted Messages', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_received_messages'] = __('Received Messages', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_requested_viewings'] = __('Order/Bookings', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_submitted_orders'] = __('Submitted Orders', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_received_orders'] = __('Received Orders', 'wp-dp');

            $wp_dp_static_text['wp_dp_member_select_messages'] = __('all', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_read_messages'] = __('Read', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_unread_messages'] = __('Unread', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_message_date_just_now'] = __('Just now', 'wp-dp');
            $wp_dp_static_text['wp_dp_no_images_to_gallery'] = __('No Images to gallery', 'wp-dp');
            $wp_dp_static_text['wp_dp_upload_gallery_images_placeholder'] = __('%s maximum', 'wp-dp');
            $wp_dp_static_text['wp_dp_have_an_account_title'] = __('Have an account?', 'wp-dp');
            $wp_dp_static_text['wp_dp_sign_in'] = __('Sign in', 'wp-dp');
            $wp_dp_static_text['wp_dp_please_sign_in'] = __('[ Please ', 'wp-dp');
            $wp_dp_static_text['wp_dp_dont_have_an_account'] = __("In and if you are a New User, continue below and register along with this submission. Your account details will be confirmed via email. ]", 'wp-dp');
            $wp_dp_static_text['wp_dp_dont_have_an_account_confirmed'] = __('If you already have an account , please log in before you begin. continue below and register along with this submission. Your account details will be confirmed via email.', 'wp-dp');
            $wp_dp_static_text['wp_dp_times'] = __('Times', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_listing_login_dec_note'] = __('Note', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_listing_login_dec'] = __('Please <a class="sign-in wp-dp-open-signin-tab" href="javascript:void(0);">log-in</a> before you begin Or register along with this submission & details will be confirmed via email.', 'wp-dp');

            $wp_dp_static_text['wp_dp_no_files_to_file_documents'] = __('No files to file documents', 'wp-dp');
            $wp_dp_static_text['wp_dp_no_files_to_file_documents_placeholder'] = __('You can upload <span>%s</span> files in your library', 'wp-dp');
            $wp_dp_static_text['wp_dp_no_image_to_floor_plan'] = __('No images to floor plan', 'wp-dp');
            $wp_dp_static_text['wp_dp_continue_label'] = esc_html__('Continue', 'wp-dp');

            /*
             * Reviews Images Option
             */

            $wp_dp_static_text['wp_dp_reviews_browse'] = __('Browse', 'wp-dp');
            $wp_dp_static_text['wp_dp_recieved_reviews'] = __('Reviews Received', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviewed'] = __('Reviewed', 'wp-dp');
            $wp_dp_static_text['wp_dp_review_submitted'] = __('Review Submitted', 'wp-dp');
            $wp_dp_static_text['wp_dp_replied'] = __('Replied', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_submitted'] = __('Reviews Submited', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_alertt_alert_frequency'] = __('Alert Frequency : ', 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_packahe_expiray_holder'] = __('Package Expiray ', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews'] = __('reviews', 'wp-dp');
            $wp_dp_static_text['wp_dp_review'] = __('review', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_images'] = __('select images', 'wp-dp');
            $wp_dp_static_text['wp_dp_review_images'] = __('Review Images', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_already_added_flagg'] = esc_html__('Flag Already Added', 'wp-dp');
            $wp_dp_static_text['wp_dp_reset_all'] = esc_html__('Reset All', 'wp-dp');
            $wp_dp_static_text['wp_dp_promote_listing'] = esc_html__('promote listing', 'wp-dp');


            /*
             * Promotions Strings
             */
            $wp_dp_static_text['wp_dp_promotions_settings'] = esc_html__('Promotions Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_promotion_title'] = esc_html__('Promotion Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_duration'] = esc_html__('Promotion Duration', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_price'] = esc_html__('Promotion Price', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_description'] = esc_html__('Promotion Description', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_meta_promotion_order'] = esc_html__('Promotion Order', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_meta_listing_id'] = esc_html__('Listing Id', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_promotions'] = esc_html__('Promotions', 'wp-dp');

            $wp_dp_static_text['wp_dp_promotion_orders'] = esc_html__('Promotions', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_orders_id'] = esc_html__('Promotion ID ', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_add_new'] = esc_html__('Add New Promotion', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_add_new'] = esc_html__('Edit Promotion', 'wp-dp');
            $wp_dp_static_text['wp_dp_new_promotion_order'] = esc_html__('New Promotion Item', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_new_promotion_order'] = esc_html__('Add New Promotion', 'wp-dp');
            $wp_dp_static_text['wp_dp_view_promotion_order'] = esc_html__('View Promotion Item', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_search'] = esc_html__('Search', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_nothing_found'] = esc_html__('Nothing found', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_nothing_found_trash'] = esc_html__('Nothing found in Trash', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_date'] = esc_html__('Date', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_id'] = esc_html__('Listing Id', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_amount'] = esc_html__('Amount', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_title'] = esc_html__('Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_duration'] = esc_html__('Duration', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_total_amount'] = esc_html__('Total Amount', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_amount'] = esc_html__('Amount', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_member'] = esc_html__('Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_listing'] = esc_html__('Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_order_id'] = esc_html__('Order Id', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_status'] = esc_html__('Status', 'wp-dp');


            $wp_dp_static_text['wp_dp_promote_your_ad'] = esc_html__('Select an option to promote your ad', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_pay_now'] = esc_html__('Pay Now', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_success_msg'] = esc_html__('You are successfully subscribed for this promotion.', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_error'] = esc_html__('Please select at least one promotion to continue.', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_promotion_background'] = esc_html__('Promotion Background', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_top_categories'] = esc_html__('Top Categories', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_top_categories_desc'] = esc_html__('Show your ad in top categories', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_home_featured'] = esc_html__('Home Featured', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_home_featured_desc'] = esc_html__('Set your ad as featured', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_urgent'] = esc_html__('Urgent', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_urgent_desc'] = esc_html__('Let people know that you want to sell, rent or hire quickly.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_urgent'] = esc_html__('Urgent', 'wp-dp');
            $wp_dp_static_text['wp_dp_shortcode_listings_only_urgent'] = esc_html__('Only Urgent', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotions_switch'] = esc_html__('Promotions ( Enable / Disable )', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotions_popup_footer'] = esc_html__('Promotions Popup Footer Text', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_continue'] = esc_html__('Continue', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_remaining'] = esc_html__('remaining', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_left'] = esc_html__('left', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_vat'] = esc_html__('VAT', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_total'] = esc_html__('Total', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotions_day'] = esc_html__('day', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotions_days'] = esc_html__('days', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotions_active_promotions'] = esc_html__('Active Promotions', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_promotion_background_color'] = esc_html__('background color', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_promotion_no_of_days'] = esc_html__('number of days', 'wp-dp');
            $wp_dp_static_text['wp_dp_listings_promotions'] = esc_html__('Promotions', 'wp-dp');
            $wp_dp_static_text['wp_dp_listings_package_info'] = esc_html__('Package Info', 'wp-dp');
            $wp_dp_static_text['wp_dp_view_listings_by_switcher'] = esc_html__('View listings by:', 'wp-dp');

            $wp_dp_static_text['wp_dp_agent_review_verified'] = esc_html__('Verified', 'wp-dp');
            $wp_dp_static_text['wp_dp_agent_review_reviews'] = esc_html__('Reviews', 'wp-dp');
            $wp_dp_static_text['wp_dp_agent_review_show_map'] = esc_html__('Show Map', 'wp-dp');
            $wp_dp_static_text['wp_dp_agent_review_hide_map'] = esc_html__('Hide Map', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_element_price_filter'] = esc_html__('Price Filter', 'wp-dp');
            $wp_dp_static_text['wp_dp_split_map_title_location_filter'] = esc_html__('Location Title filter', 'wp-dp');
            $wp_dp_static_text['wp_dp_load_more_listings_members'] = esc_html__('Load More Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_element_filters_toggle'] = esc_html__('Filters Toggle', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_search_name_placeholder'] = esc_html__('Set search alert title', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_search_alert_name_placeholder'] = esc_html__('Set email alert title', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_video'] = esc_html__('Listing Video', 'wp-dp');
            $wp_dp_static_text['wp_dp_price_plan_page_selected'] = esc_html__('Price Plan Page', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_add_list_no_of_videos'] = esc_html__('Video', 'wp-dp');
            $wp_dp_static_text['wp_dp_map_info_contact_info'] = esc_html__('Contact Information', 'wp-dp');
            $wp_dp_static_text['wp_dp_map_search_by_goolge'] = esc_html__('Search By Google', 'wp-dp');
            $wp_dp_static_text['wp_dp_map_manual_search'] = esc_html__('Manual Search', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_listing_holidays'] = esc_html__('Holidays', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_detail_key_details'] = esc_html__('Key details', 'wp-dp');
            $wp_dp_static_text['wp_dp_current_location'] = esc_html__('Current Location', 'wp-dp');
            $wp_dp_static_text['wp_dp_no_activity'] = esc_html__('No Activity', 'wp-dp');
            $wp_dp_static_text['wp_dp_no_allow_to_fav_own_listing'] = esc_html__('you are not allowed to favourite your own listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_detail_page_options_new'] = esc_html__('Detail Page Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_similar_listings_switch'] = esc_html__('Similar Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_similar_listings_switch_hint'] = esc_html__('If switch is set to be "OFF" the "Similar Listings" will not be showing on the bottom of listings detail page for this type.', 'wp-dp');
            $wp_dp_static_text['wp_dp_faq_listings_switch_hint'] = esc_html__("If switch is set to be \"OFF\" the \"FAQ's\" will not be showing in back-end of listings detail page general setting section  ,front-end the listings detail page for this type.", 'wp-dp');
            $wp_dp_static_text['wp_dp_lisint_meta_add_new_listing_type'] = esc_html__("Add new Listing Type", 'wp-dp');
            $wp_dp_static_text['wp_dp_list_detail_type_help_text'] = esc_html__("Select listing type, which already have the added in listing types", 'wp-dp');
            $wp_dp_static_text['wp_dp_list_detail_price_listing_help_text'] = esc_html__("Select price option from the dropdown list.", 'wp-dp');
            $wp_dp_static_text['wp_dp_list_listing_type_help_text'] = esc_html__("If swtich is set to \"OFF\" %s won't be showing on the listings detail page for this type.", 'wp-dp');
            $wp_dp_static_text['wp_dp_shortcode_listings_left_filters_location'] = esc_html__("Left filter Location", 'wp-dp');
            $wp_dp_static_text['wp_dp_listings_detail_sidebar'] = esc_html__("Detail Page Widget Sidebar Option", 'wp-dp');
            $wp_dp_static_text['wp_dp_listings_detail_sidebar_switch'] = esc_html__("Sidebar", 'wp-dp');
            $wp_dp_static_text['wp_dp_listings_detail_sidebar_switch_hint'] = esc_html__("Turn this switch on/off to Enable/Disable sidebar on listing detail page.", 'wp-dp');
            $wp_dp_static_text['wp_dp_listings_detail_sidebar_select'] = esc_html__("Select Sidebar", 'wp-dp');
            $wp_dp_static_text['wp_dp_listings_detail_sidebar_hint'] = esc_html__("Select sidebar for listing detail page from dropdown list, which is already added in Appearance > Widgets section.", 'wp-dp');
            $wp_dp_static_text['wp_dp_shortcode_listings_left_filters_location'] = esc_html__("Left filter Location", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_settings_price_plan_page_hint'] = esc_html__("Select price plan page for add new listing submission page process, For adding this page you can go to Pages > Add new page title and select \"DB:Pricing Plan\" element in page builder section after that select that page in this dropdown list", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_dflt_plac_hldr_hint'] = esc_html__("Browse avatar placeholder image which is uploaded in media gallery.", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_dashboard_pagination_hint'] = esc_html__("Enter default pagination for user dashboard inner pages.", 'wp-dp');
            $wp_dp_static_text['wp_dp_activity_notifications_heading_hint'] = esc_html__("If swtich is set to \"OFF\" user activity notification wont be showing  in front-end user dashboard.", 'wp-dp');
            $wp_dp_static_text['wp_dp_options_yelp_places_hint'] = esc_html__("Choose one or more yelp places for listing detail page, if in \"Listing Types\" yelp places switch is to be \"ON\"", 'wp-dp');


            $wp_dp_static_text['wp_dp_plugin_options_min_lease_yr_hint'] = esc_html__("Enter minimum lease year for mortgage calculator, which is to be shown in listing detail page, if in \"Listing Types\" mortgage calculator switch is to be \"ON\"", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_max_lease_yr_hint'] = esc_html__("Enter maximum lease year for mortgage calculator, which is to be shown in listing detail page, if in \"Listing Types\" mortgage calculator switch is to be \"ON\"", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_google_redirect_hint'] = esc_html__("Put your fixed redirect url page of login for your site.", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_hint'] = esc_html__("Select default map style from dropdown list for your site here.", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_street_lat_desc_hint'] = esc_html__("Set level of location for your site.", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_circle_radius_hint'] = esc_html__("Enter the map circle radius value here.", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_autopost_twitter_hint'] = esc_html__("If swtich is set to \"OFF\" twitter social network wont be showing in all add new post.", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_autopost_facebook_hint'] = esc_html__("If swtich is set to \"OFF\" facebook social network wont be showing in all add new post.", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_autopost_linkedin_hint'] = esc_html__("If swtich is set to \"OFF\" linkedIn social network wont be showing in all add new post.", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_text_content_settings'] = esc_html__("Listing Element Pages Content", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_content_settings_text_in'] = esc_html__("Text Format By", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_content_settings_text_in_hint'] = esc_html__("Select the listing title, paragraph excerpt length format options from dropdown list", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_content_settings_text_in_words'] = esc_html__("Words", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_content_settings_text_in_character'] = esc_html__("Characters", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_content_title_length'] = esc_html__("Listing Element Title", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_content_title_length_hint'] = esc_html__("Set the listing element title excerpt length , it will be applying in all listing elements.", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_content_content_length'] = esc_html__("Listing Element Description", 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_content_content_length_hint'] = esc_html__("Set the listing element description excerpt length , it will be applying in all listing elements", 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_column_settings_image_icon'] = esc_html__("Listing Type Image/Icon", 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_column_settings_num_listing'] = esc_html__("Number of Listing", 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_column_settings_posted_date'] = esc_html__("Posted Date", 'wp-dp');
           
			// reviews strings
            // starts here
            $wp_dp_static_text['wp_dp_reviews_name'] = __('Reviews', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_singular_name'] = __('Review', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_add_review'] = __('Add Review', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_add_new_review'] = __('Add New Review', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_new_review'] = __('New Review', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_edit_review'] = __('Edit Review', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_view_review'] = __('View Review', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_all_reviews'] = __('All Reviews', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_search_reviews'] = __('Search Reviews', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_not_found_reviews'] = __('No reviews found.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_not_found_in_trash_reviews'] = __('No reviews found in Trash.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_description'] = __('No reviews found in Trash.', 'wp-dp');

            $wp_dp_static_text['review_title'] = __('Review Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_overall_rating'] = __('Overall Rating', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_username'] = __('User Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_detail'] = __('Reviews Detail', 'wp-dp');

            $wp_dp_static_text['wp_dp_reviews_settings_tab_text'] = __('Reviews/Comments', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_enable_user_reviews'] = __('Review & Ratings', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_enable_user_reviews_hint'] = __('Turn on/off user reviews system.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_enable_user_reviews_desc'] = __('Turn this switch "OFF" to disable the review and ratings on the listing.', 'wp-dp');

            $wp_dp_static_text['wp_dp_reviews_auto_approve_reviews'] = __('Auto Approve Reviews/Comments', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_auto_approve_reviews_hint'] = __('Do you want to Reviews get approved automatically?', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_auto_approve_reviews_desc'] = __('If the switch is set to "ON" the reviews and comments will get approved automatically', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_enable_review_comment_desc'] = __('Set this switch to "ON" if you dont want to use the reviews but the comments, This switch will convert all the reviews into the comments.', 'wp-dp');
            
            
            
            $wp_dp_static_text['wp_dp_reviews_enable_multiple_reviews_desc'] = __('If switch is set to "ON" the load more button will be added in the bottom of reviews listing to see older reviews.', 'wp-dp');
            
            $wp_dp_static_text['wp_dp_reviews_min_length_desc'] = __('While adding the review user will have to enter the minimum characters mentioned in this field before they can submit.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_max_length_desc'] = __('While adding the review user can not enter the characters more than mentioned in this field.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_number_of_reviews_desc'] = __('Enter the number of reviews you want to list on listing detail page.', 'wp-dp');
            
            $wp_dp_static_text['wp_dp_plugin_options_map_marker_icon_desc'] = __('You can change the marker on the map which are representing the listings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_cluster_icon_desc'] = __('If same location have more than 1 listings or the map is zoom out and listings are overlapping this is the icon which will be displayed with number of listings available for that location.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_slider_view_icon_desc'] = __('This icon is displaying on the listing detail page for the header as slider view.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_view_icon_desc'] = __('This icon is displaying on the listing detail page for the header as map view.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_street_view_icon_desc'] = __('This icon is displaying on the listing detail page for the header as street view.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_distance_measure_by_desc'] = __('Choose the unit of the distance you want to use on maps.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_street_address_desc'] = __('Set the default address which is selected for users and listings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_street_long_desc'] = __('Longitude will be auto select when "Address" is given in address field and click on "Search This Location on Map" button.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_street_lat_desc'] = __('Latitude will be auto select when "Address" is given in address field and click on "Search This Location on Map" button.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_without_login_user_reviews'] = __('Reviews/Comments Without Login', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_without_login_user_reviews_hint'] = __('Allow user to add review without login.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_enable_review_comment'] = __('Use reviews as Comments', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_enable_multiple_reviews'] = __('Enable Multiple Reviews', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_min_length'] = __('Review Min Length', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_max_length'] = __('Review Max Length', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_number_of_reviews'] = __('Number of reviews to list', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_load_more_reviews'] = __('Load More Reviews/Comments Option', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_captcha_for_reviews'] = __('Captcha', 'wp-dp');

            $wp_dp_static_text['wp_dp_reviews_settings_labels'] = __('Score Values', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_settings_labels_hint'] = __('Click on "Add Score Value" button to add the score value and enter the value title in review & rating form.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_settings_labels_label'] = __('Score Value', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_settings_labels_rating'] = __('Rating Value [1-100]', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_settings_labels_cntrl_delete_row'] = __('Delate Row', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_settings_labels_cntrl_add_row'] = __('+Add Score Value', 'wp-dp');

            $wp_dp_static_text['wp_dp_feature_setting_labels_add'] = __('Add Feature', 'wp-dp');
            $wp_dp_static_text['wp_dp_feature_setting_labels_add_label'] = __('Label', 'wp-dp');

            $wp_dp_static_text['wp_dp_reviews_total_reviews_label'] = __('Ratings & Reviews', 'wp-dp');

            $wp_dp_static_text['wp_dp_reviews_total_comments_label'] = __('%d  Comments', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_post_new_reviews_button'] = __('Write new review', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_post_new_reviews_comments'] = __('Post new comment', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_rating_summary_heading'] = __('Rating summary', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_overall_rating_heading'] = __('Overall rating', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_all_reviews_heading'] = __('Reviews', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_all_comments_heading'] = __('Comments', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_delete'] = __('Delete', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_delete_this_item'] = __('Delete this item permanently', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_post_name'] = __('Post Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_user_txt'] = __('User', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_overall_rating'] = __('Overall Rating', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_status'] = __('Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_date'] = __('Date', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_rating_sumary'] = __('Ratings Summary', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_post_dosnt_exist'] = __('POST DOES NOT EXIST.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_show_more'] = __('Show more...', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_show_less'] = __('Show less', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_ago_txt'] = __('Ago', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_invalid_user'] = __('Invalid user.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_configure_captcha_for_reviews'] = __('This will turn on/off captcha for reviews form. You can configure Captcha API key in plugin API settings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_score_values'] = __('Score Values', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_name_field'] = __('Username *', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_email_field'] = __('Email *', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_tell_your_experience'] = __('Tell about your experience or leave a tip for others', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_captcha_reload'] = __('Reload', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_min_chars'] = __('Min characters:', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_max_chars'] = __('Max characters:', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_no_perm_to_del_review'] = __('You dont have permissions to delete this review', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_slass_coments'] = __('Reviews/Comments', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_total_reviews'] = __('Total Reviews ', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_active_reviews'] = __('Active Reviews ', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_pend_reviews'] = __('Pending Reviews ', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_oall_reviews'] = __('Overall Rating ', 'wp-dp');

            $wp_dp_static_text['wp_dp_reviews_rate_and_write_a_review_label'] = __('Rate us and write a review', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_rate_and_write_a_comment_label'] = __('Write a Comment', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_num_of_reviews_on_your_listing'] = __('%s posted a review on your Listing <a href="%s">%s</a>.', 'wp-dp');
			$wp_dp_static_text['wp_dp_reply_reviews_notification_on_your_given_review'] = __('%s has replied against your given review <a href="%s">%s</a>.', 'wp-dp');

            $wp_dp_static_text['wp_dp_reviews_pakage_order_reviews'] = __('Reviews', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_pakage_order_response_reviews'] = __('Respond to Reviews', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_characters_dpaining'] = __('Characters remaining', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_characters_dpaining'] = __('Characters remaining', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_marked_helpful'] = __('Helpful', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_marked_flag'] = __('Flag', 'wp-dp');

            $wp_dp_static_text['wp_dp_reviews_add_new_reviews_close_button'] = __('Close', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_overall_rating_label'] = __('Overall rating', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_your_overall_rating_label'] = __('Your overall rating', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_send_your_review_btn'] = __('Submit', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_send_your_comment_btn'] = __('Submit your Comment', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_textarea_word_length'] = __(' Words typed: ', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_read_more_text'] = __('Read More...', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_read_more_reviews_text'] = __('Load More Reviews', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_read_more_comments_text'] = __('Load More Comments', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_no_reviews_text'] = __('At the moment i don\'t have any reviews.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_no_reviews_review_text'] = __('We would love your review, here\'s where your review will show up!', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_sort_by_highest_rating_option'] = __('Highest Rating', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_sort_by_lowest_rating_option'] = __('Lowest Rating', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_sort_by_newest_reviews_option'] = __('Newest Reviews', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_request_processing_text'] = __('Processing...', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_incomplete_data_msg'] = __('Incomplete data.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_success_msg'] = __('Your review successfully added.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_moderation_msg'] = __('Your review is under moderation.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_no_more_reviews_text'] = __('Sorry, no more reviews.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_already_added_review_msg'] = __('Sorry, You have already added review for this post.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_already_added_review0_msg'] = __('You have already added review.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_already_added_review1_msg'] = __('"You have already added review."', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_already_added_comment1_msg'] = __('"You have already added comment."', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_post_owner_review_msg'] = __('Owner of the post is not allowed to add review.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_withtout_login_msg'] = __('Please login in order to post review.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_recaptcha_error_msg'] = __('Please select captcha field.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_post_notallowed_review_msg'] = __('You are not allowed to post any review.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_post_notallowed_comment_msg'] = __('You are not allowed to post any comment.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_based_on_all_ratings'] = __('based on all ratings', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_would_recomended'] = __('would recommend it to a friend', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_cannot_write_review_on_own_listing'] = __('You cannot write review on your own listing.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_cannot_write_comment_on_own_listing'] = __('You cannot write comment on your own listing.', 'wp-dp');

            $wp_dp_static_text['wp_dp_reviews_dashboard_heading'] = __('My Reviews', 'wp-dp');
            $wp_dp_static_text['wp_dp_given_reviews_dashboard_heading'] = __('Given Reviews', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_dashboard_stats'] = __('%d', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_dashboard_sort_by_label'] = __('Sort by', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_dashboard_sort_by_highest_rating_option'] = __('Highest Rating', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_dashboard_sort_by_lowest_rating_option'] = __('Lowest Rating', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_dashboard_sort_by_newest_reviews_option'] = __('Newest Reviews', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_dashboard_no_reviews_text'] = __('You haven\'t written any reviews.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_dashboard_no_reviews_recieved_text'] = __('You haven\'t received any reviews.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_dashboard_delete_success_msg'] = __('Your review successfully got deleted.', 'wp-dp');

            $wp_dp_static_text['wp_dp_reviews_reply_for'] = __('Reply for', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_review_reply_for'] = __('Select review reply for', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_reply'] = __('Post a reply', 'wp-dp');

            $wp_dp_static_text['wp_dp_select_review_reply_for'] = __('Select review reply for', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_reply'] = __('Post a reply', 'wp-dp');

            $wp_dp_static_text['wp_dp_reviews_review_desc_length_must_be'] = __('Description length must be ', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_review_desc_length_must_be_to'] = __(' to ', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_review_desc_length_must_be_to_long'] = __(' long ', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_error_provide_title'] = __('Review title length must be 3 characters or more', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_error_provide_email_address'] = __('Please provide valid email address.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_error_provide_full_name'] = __('Provided full name must be 3 characters or more', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_listing_review_flag_options'] = __('Listing Reviews Flag Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_flags_text'] = __('Flags', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_single_flag_text'] = __('Flag', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_flags_reason_text'] = __('Reason', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_flags_all_flags'] = __('All Flags', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_flags_edit_flag'] = __('Edit Flag', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_flags_update_flag'] = __('Update Flag', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_flags_add_new_flag'] = __('Add New Flag', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_flags_new_flag_name'] = __('New Flag Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_flags_username_text'] = __('Username', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_flags_email_text'] = __('Email', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_flags_no_flag_found'] = __('There is no flag for this review.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_flags_report_submit_successfully'] = __('Your report submit successfully.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_flags_there_is_problem'] = __('There is some problem.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_marked_as_helpful'] = __('Review marked as helpful.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_marked_not_as_helpful'] = __('Review marked as not helpful.', 'wp-dp');
            $wp_dp_static_text['wp_dp_reviews_leave_reply'] = __('Leave Reply', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_review_post_new_reviews'] = __('Post new review', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_review_report_this_review'] = __('Report this review', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_review_choose_report_reason'] = __('Please choose one of the following reasons.', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_review_report_submit'] = __('Submit', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_review_flag_no_reason'] = __('No reason', 'wp-dp');

            //Adding listing type title
            $wp_dp_static_text['wp_dp_listing_type_title'] = __('Listing Type Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_claims_status_Col'] = __('Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_select_status'] = __('Select Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_claime_filter_pending'] = __('Pending', 'wp-dp');
            $wp_dp_static_text['wp_dp_claime_filter_resolved'] = __('Resolved', 'wp-dp');
            $wp_dp_static_text['wp_dp_claims_date_Col'] = __('Date', 'wp-dp');
            
            $wp_dp_static_text['wp_dp_mmultiple_listing_slider_switdh'] = __('Multiple Listing Slider', 'wp-dp');
            $wp_dp_static_text['wp_dp_mmultiple_listing_slider_switdh_hint'] = __('Select yes/no to enable/disable slider in case of multiple listing slider view.', 'wp-dp');
            
            
            
            
            //Adding review labels
            $wp_dp_static_text['wp_dp_review_coming_soon'] = __('Review coming soon', 'wp-dp');
            $wp_dp_static_text['wp_dp_mailer_response_sent'] = __('Sent', 'wp-dp');
            $wp_dp_static_text['wp_dp_mailer_response_failed'] = __('Failed', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_status_processed'] = __('Processed', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_status_new'] = __('New', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_listing_alert'] = esc_html__('Listing Alert', 'wp-dp');
			//Adding iconMoon	load text
			$wp_dp_static_text['wp_dp_load_icomoon_selection_json'] = __('Load from IcoMoon selection.json', 'wp-dp');
			
			$wp_dp_static_text['wp_dp_days'] = __('Days', 'wp-dp');
			$wp_dp_static_text['wp_dp_registration_disabled_login_with_demo_user'] = __('Registration is disabled. Please login with demo user.', 'wp-dp');
			
            $wp_dp_static_text['wp_dp_header_join_nowww'] = __('Join Now', 'wp-dp');
            
            
            
            return $wp_dp_static_text;
        }

    }

    new wp_dp_plugin_all_strings_5;
}
