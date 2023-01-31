<?php

/**
 * Static string Return
 */

if ( ! class_exists('wp_dp_plugin_all_strings_1') ) {

    class wp_dp_plugin_all_strings_1 {

        public function __construct() {
			global $wp_dp_static_text;
            /*
             * Triggering function for strings.
             */
            add_action('init', array( $this, 'wp_dp_plugin_strings' ), 0);
        }

        public function wp_dp_plugin_strings() {
            global $wp_dp_static_text;

            $wp_dp_static_text['wp_dp_create_listing_back'] = esc_html__('Back', 'wp-dp');
            $wp_dp_static_text['wp_dp_create_listing_back_to'] = esc_html__('Back to %s', 'wp-dp');
            /*
             * Listings Post Type Strings
             */
            $wp_dp_static_text['id_number'] = esc_html__('ID Number', 'wp-dp');
            $wp_dp_static_text['transaction_id'] = esc_html__('Transaction Id', 'wp-dp');
            $wp_dp_static_text['listing_contact_email'] = esc_html__('Email', 'wp-dp');
            $wp_dp_static_text['listing_contact_phone'] = esc_html__('Phone Number', 'wp-dp');
            $wp_dp_static_text['listing_contact_web'] = esc_html__('Web', 'wp-dp');
            $wp_dp_static_text['listing_contact_heading'] = esc_html__('Contact Information', 'wp-dp');
            $wp_dp_static_text['wp_dp_deals'] = esc_html__('Deals', 'wp-dp');

            $wp_dp_static_text['wp_dp_save_settings'] = esc_html__('Save All Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_reset_options'] = esc_html__('Reset All Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_please_wait'] = esc_html__('Please Wait...', 'wp-dp');
            $wp_dp_static_text['wp_dp_general_options'] = esc_html__('General Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_page_settings'] = esc_html__('Page Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_default_location'] = esc_html__('Default Location', 'wp-dp');
            $wp_dp_static_text['wp_dp_others'] = esc_html__('Others', 'wp-dp');
            $wp_dp_static_text['wp_dp_smtp_settings'] = esc_html__('SMTP Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_gateways'] = esc_html__('Gateways', 'wp-dp');
            $wp_dp_static_text['wp_dp_packages'] = esc_html__('Packages', 'wp-dp');
            $wp_dp_static_text['wp_dp_featured_listings'] = esc_html__('Featured Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_custom_fields'] = esc_html__('Custom Fields', 'wp-dp');
            $wp_dp_static_text['wp_dp_listings_fields'] = esc_html__('Listings Fields', 'wp-dp');
            $wp_dp_static_text['wp_dp_recruiters'] = esc_html__('Recruiters', 'wp-dp');
            $wp_dp_static_text['wp_dp_api_settings'] = esc_html__('Api Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_search_options'] = esc_html__('Search Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_social_icon'] = esc_html__('Social Icon', 'wp-dp');
            $wp_dp_static_text['wp_dp_user_settings'] = esc_html__('User Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_user_header_login'] = esc_html__('User Header Login', 'wp-dp');
            $wp_dp_static_text['wp_dp_user_header_login_hint'] = esc_html__('Dashboard and Front-End login/register option can be hide by turning off this switch.', 'wp-dp');
            $wp_dp_static_text['wp_dp_menu_location'] = esc_html__('Menu Location', 'wp-dp');
            $wp_dp_static_text['wp_dp_menu_location_hint'] = esc_html__('Show login section in Menu', 'wp-dp');
            $wp_dp_static_text['wp_dp_general_info'] = esc_html__('General Info', 'wp-dp');
            $wp_dp_static_text['wp_dp_package_info'] = esc_html__('Package Info', 'wp-dp');

// Listing Enquiries Strings
            $wp_dp_static_text['wp_dp_listing_enquiries'] = esc_html__('Messages', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_enquiries_name'] = esc_html__('Messages', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_enquiries_singular_name'] = esc_html__('Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_enquiries_menu_name'] = esc_html__('Messages', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_enquiries_name_admin_bar'] = esc_html__('Messages', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_enquiries_add_new'] = esc_html__('Add Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_enquiries_add_new_item'] = esc_html__('Add Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_enquiries_new_item'] = esc_html__('Add Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_enquiries_edit_item'] = esc_html__('Edit Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_enquiries_view_item'] = esc_html__('Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_enquiries_all_items'] = esc_html__('Messages', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_enquiries_search_items'] = esc_html__('Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_enquiries_not_found'] = esc_html__('Messages', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_enquiries_not_found_in_trash'] = esc_html__('Messages', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_enquiries_description'] = esc_html__('Edit Message', 'wp-dp');

// Arrange Viewings Strings
            $wp_dp_static_text['wp_dp_arrange_viewings'] = esc_html__('Viewings', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_name'] = esc_html__('Viewings', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_singular_name'] = esc_html__('Viewing', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_menu_name'] = esc_html__('Viewings', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_name_admin_bar'] = esc_html__('Viewings', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_add_new'] = esc_html__('Add Viewing', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_add_new_item'] = esc_html__('Add Viewing', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_new_item'] = esc_html__('Add Viewing', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_edit_item'] = esc_html__('Edit Viewing', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_view_item'] = esc_html__('Viewing', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_all_items'] = esc_html__('Viewings', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_search_items'] = esc_html__('Viewing', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_not_found'] = esc_html__('Viewings', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_not_found_in_trash'] = esc_html__('Viewings', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_description'] = esc_html__('Edit Viewing', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_column_id'] = esc_html__('Viewing ID', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_column_date'] = esc_html__('Date', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_column_listing_member'] = esc_html__('Listing Owner', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_column_listing'] = esc_html__('Listing Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_column_viewing_member'] = esc_html__('Viewing Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_column_status'] = esc_html__('Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_total'] = esc_html__('Total Viewings', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_completed'] = esc_html__('Completed Viewings', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_processing'] = esc_html__('Processing Viewings', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_closed'] = esc_html__('Closed Viewings', 'wp-dp');
// Listing Types
            $wp_dp_static_text['wp_dp_listing_types'] = esc_html__('Listing Types', 'wp-dp');
            $wp_dp_static_text['wp_dp_category_description'] = esc_html__('Category Description', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_listing_type'] = esc_html__('Add Listing Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_edit_listing_type'] = esc_html__('Edit Listing Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_submit'] = esc_html__('Submit', 'wp-dp');
            $wp_dp_static_text['wp_dp_preview'] = esc_html__('Preview', 'wp-dp');
            $wp_dp_static_text['wp_dp_delete_permanently'] = esc_html__('Delete permanently', 'wp-dp');
            $wp_dp_static_text['wp_dp_move_to_trash'] = esc_html__('Move to trash', 'wp-dp');
            $wp_dp_static_text['wp_dp_publish'] = esc_html__('Publish', 'wp-dp');
            $wp_dp_static_text['wp_dp_submit_for_review'] = esc_html__('Submit for review', 'wp-dp');
            $wp_dp_static_text['wp_dp_update'] = esc_html__('Update', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_category'] = esc_html__('Add Category', 'wp-dp');
            $wp_dp_static_text['wp_dp_name'] = esc_html__('Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_actions'] = esc_html__('Actions', 'wp-dp');
            $wp_dp_static_text['wp_dp_update_category'] = esc_html__('Update Category', 'wp-dp');
            $wp_dp_static_text['wp_dp_click_to_add_item'] = esc_html__('Click to Add Item', 'wp-dp');
            $wp_dp_static_text['wp_dp_text'] = esc_html__('TEXT', 'wp-dp');
            $wp_dp_static_text['wp_dp_services'] = esc_html__('Services', 'wp-dp');
            $wp_dp_static_text['wp_dp_availability'] = esc_html__('Availability', 'wp-dp');
            $wp_dp_static_text['wp_dp_availability_string'] = esc_html__('Availability: %s', 'wp-dp');
            $wp_dp_static_text['wp_dp_number'] = esc_html__('NUMBER', 'wp-dp');
            $wp_dp_static_text['wp_dp_textarea'] = esc_html__('TEXTAREA', 'wp-dp');
            $wp_dp_static_text['wp_dp_dropdown'] = esc_html__('DROPDOWN', 'wp-dp');
            $wp_dp_static_text['wp_dp_date'] = esc_html__('DATE', 'wp-dp');
            $wp_dp_static_text['wp_dp_email'] = esc_html__('EMAIL', 'wp-dp');
            $wp_dp_static_text['wp_dp_url'] = esc_html__('URL', 'wp-dp');
            $wp_dp_static_text['wp_dp_url_string'] = esc_html__('URL: %s', 'wp-dp');
            $wp_dp_static_text['wp_dp_range'] = esc_html__('RANGE', 'wp-dp');
            $wp_dp_static_text['wp_dp_quantity'] = esc_html__('Quantity', 'wp-dp');
            $wp_dp_static_text['wp_dp_net'] = esc_html__('Net', 'wp-dp');
            $wp_dp_static_text['wp_dp_section'] = esc_html__('SECTION', 'wp-dp');
            $wp_dp_static_text['wp_dp_time'] = esc_html__('Time', 'wp-dp');
            $wp_dp_static_text['wp_dp_form_title'] = esc_html__('Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_reservation_form_title'] = esc_html__('Form Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_paid_inquiry_form'] = esc_html__('Reservation Paid', 'wp-dp');
            $wp_dp_static_text['wp_dp_form_button_label'] = esc_html__('Form Button Label', 'wp-dp');
            $wp_dp_static_text['wp_dp_form_terms_label'] = esc_html__('Form Terms Label', 'wp-dp');
            $wp_dp_static_text['wp_dp_form_terms_link'] = esc_html__('Form Terms Link', 'wp-dp');
            $wp_dp_static_text['wp_dp_time_small'] = esc_html__('Time', 'wp-dp');
            $wp_dp_static_text['wp_dp_time_string'] = esc_html__('Time: %s', 'wp-dp');
            $wp_dp_static_text['wp_dp_file_upload'] = esc_html__('File Upload', 'wp-dp');
            $wp_dp_static_text['wp_dp_file'] = esc_html__('File', 'wp-dp');
            $wp_dp_static_text['wp_dp_file_hint'] = esc_html__('Upload Image / File here.', 'wp-dp');
            $wp_dp_static_text['wp_dp_file_upload_string'] = esc_html__('File Upload: %s', 'wp-dp');
            $wp_dp_static_text['wp_dp_please_insert_item'] = esc_html__('Please Insert Item', 'wp-dp');
            $wp_dp_static_text['wp_dp_section_small'] = esc_html__('Section', 'wp-dp');
            $wp_dp_static_text['wp_dp_section_string'] = esc_html__('Section: %s', 'wp-dp');
			$wp_dp_static_text['wp_dp_divider_small'] = esc_html__('Divider', 'wp-dp');
            $wp_dp_static_text['wp_dp_divider_string'] = esc_html__('Divider: %s', 'wp-dp');
			$wp_dp_static_text['wp_dp_enable_disable_divider'] = esc_html__('Enable/Disable divider from this dropdown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_text_string'] = esc_html__('Text: %s', 'wp-dp');
            $wp_dp_static_text['wp_dp_text_small'] = esc_html__('Text', 'wp-dp');
            $wp_dp_static_text['wp_dp_custom_field_title'] = esc_html__('Field Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_custom_field_title_desc'] = esc_html__('The provided text will be used as field label.', 'wp-dp');
            $wp_dp_static_text['wp_dp_services_small'] = esc_html__('Services', 'wp-dp');
            $wp_dp_static_text['wp_dp_services_string'] = esc_html__('Services: %s', 'wp-dp');
            $wp_dp_static_text['wp_dp_field_title'] = esc_html__('Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_field_time_lapse'] = esc_html__('Time Lapse', 'wp-dp');
            $wp_dp_static_text['wp_dp_field_time_lapse_hint'] = esc_html__('Add time lapse here in minutes ( 1 to 59 )', 'wp-dp');
            $wp_dp_static_text['wp_dp_list_type_icon_image'] = esc_html__('Listing Type\'s Icon / Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_title'] = esc_html__('Listing Type Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_list_type_icon_image_desc'] = esc_html__('Select the option between "image" and "icon" whichever you want to show along with the listing type\'s title on frontend', 'wp-dp');
            $wp_dp_static_text['wp_dp_list_menu_type_icon_image'] = esc_html__('Listing Type Menu Icon / Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_icon'] = esc_html__('Icon', 'wp-dp');
			$wp_dp_static_text['wp_dp_num_of_listing'] = esc_html__('Number of Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_icon_desc'] = esc_html__('Choose an icon related to the field, this will be shown along with the field on frontend.', 'wp-dp');
            $wp_dp_static_text['wp_dp_image'] = esc_html__('Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_icon'] = esc_html__('Select Icon for Listing type', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_image'] = esc_html__('Listing Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_menu_icon'] = esc_html__('Listing Menu Icon', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_menu_image'] = esc_html__('Listing Menu Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_map_marker_image'] = esc_html__('Map Marker Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_map_marker_image_desc'] = esc_html__('Listing Image to show on map as marker', 'wp-dp');
			$wp_dp_static_text['wp_dp_map_marker_hover_image'] = esc_html__('Map Marker Hover Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_map_marker_hover_image_desc'] = esc_html__('Marker hover Image to show on map as marker on listing hover', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_a_page'] = esc_html__('Please select a page', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_result_page'] = esc_html__('Result Page', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_result_page_hint'] = esc_html__('Select Result Page', 'wp-dp');
            $wp_dp_static_text['wp_dp_search_result_page'] = esc_html__('Search Result Page', 'wp-dp');
            $wp_dp_static_text['wp_dp_search_result_page_desc'] = esc_html__('Select a page with the listing element, so on searching in the search element you place on any page ( e.g. homepage ) will be directed to above selected page for showing the records.', 'wp-dp');
            $wp_dp_static_text['wp_dp_single_page_layout'] = esc_html__('Single Page Layout', 'wp-dp');
            $wp_dp_static_text['wp_dp_view1'] = esc_html__('View 1', 'wp-dp');
            $wp_dp_static_text['wp_dp_view2'] = esc_html__('View 2', 'wp-dp');
            $wp_dp_static_text['wp_dp_view3'] = esc_html__('View 3', 'wp-dp');
            $wp_dp_static_text['wp_dp_view4'] = esc_html__('View 4', 'wp-dp');
            $wp_dp_static_text['wp_dp_view5'] = esc_html__('View 5', 'wp-dp');
            $wp_dp_static_text['wp_dp_required'] = esc_html__('Required', 'wp-dp');
            $wp_dp_static_text['wp_dp_required_desc'] = esc_html__('Choose whether you want to make this field compulsory for user or not', 'wp-dp');
            $wp_dp_static_text['wp_dp_meta_key'] = esc_html__('Meta Key', 'wp-dp');
            $wp_dp_static_text['wp_dp_meta_key_hint'] = esc_html__('Please enter Meta Key without special characters and spaces', 'wp-dp');
            $wp_dp_static_text['wp_dp_meta_key_desc'] = esc_html__('This is for database usage, please enter Meta Key without special characters and spaces', 'wp-dp');
            $wp_dp_static_text['wp_dp_place_holder'] = esc_html__('Place Holder', 'wp-dp');
            $wp_dp_static_text['wp_dp_place_holder_desc'] = esc_html__('The provided text will be used as placeholder in the field.', 'wp-dp');
            $wp_dp_static_text['wp_dp_chosen_search'] = esc_html__('Chosen Search', 'wp-dp');
            $wp_dp_static_text['wp_dp_multi_select_desc'] = esc_html__('Allow user to select multiple options from this field.', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_multi_select_desc'] = esc_html__('This will only work if the "Enable in Search" is enabled. If this field set to "Yes" user will be able to select multiple options from the search filters', 'wp-dp');
            $wp_dp_static_text['wp_dp_chosen_search_desc'] = esc_html__('If Set to "Yes" the dropdown options of this field will be displayed in search filters and user can search the records based on them.', 'wp-dp');
            $wp_dp_static_text['wp_dp_enable_search'] = esc_html__('Enable In Search', 'wp-dp');
            $wp_dp_static_text['wp_dp_enable_search_hint'] = esc_html__('If Set to "Yes" user can filter the listings from search based on this field.', 'wp-dp');
            $wp_dp_static_text['wp_dp_enable_search_desc'] = esc_html__('If Set to "Yes" user can filter the listings from search based on this field.', 'wp-dp');
            $wp_dp_static_text['wp_dp_default_value'] = esc_html__('Default Value', 'wp-dp');
            $wp_dp_static_text['wp_dp_default_value_desc'] = esc_html__('The provided text will be used as value in the field by default.', 'wp-dp');
            $wp_dp_static_text['wp_dp_collapse_in_search'] = esc_html__('Collapse in Search', 'wp-dp');
            $wp_dp_static_text['wp_dp_field_size'] = esc_html__('Field Size', 'wp-dp');
            $wp_dp_static_text['wp_dp_services_dropdown'] = esc_html__('Services Dropdown', 'wp-dp');
            $wp_dp_static_text['wp_dp_services_dropdown_hint'] = esc_html__('Do you want to show services dropdown in form.', 'wp-dp');
            $wp_dp_static_text['wp_dp_small'] = esc_html__('Small', 'wp-dp');
            $wp_dp_static_text['wp_dp_medium'] = esc_html__('Medium', 'wp-dp');
            $wp_dp_static_text['wp_dp_large'] = esc_html__('Large', 'wp-dp');
            $wp_dp_static_text['wp_dp_number_small'] = esc_html__('Number', 'wp-dp');
            $wp_dp_static_text['wp_dp_number_string'] = esc_html__('Number: %s', 'wp-dp');
            $wp_dp_static_text['wp_dp_textarea_small'] = esc_html__('TextArea', 'wp-dp');
            $wp_dp_static_text['wp_dp_textarea_string'] = esc_html__('TextArea: %s', 'wp-dp');
            $wp_dp_static_text['wp_dp_title'] = esc_html__('Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_rows_desc'] = esc_html__('Please enter the number of rows for textarea field. Only number area allowed', 'wp-dp');
            $wp_dp_static_text['wp_dp_columns_desc'] = esc_html__('Please enter the number of columns( in width ) for textarea field. Only number area allowed', 'wp-dp');
            $wp_dp_static_text['wp_dp_help_text'] = esc_html__('Help Text', 'wp-dp');
            $wp_dp_static_text['wp_dp_rows'] = esc_html__('Rows', 'wp-dp');
            $wp_dp_static_text['wp_dp_columns'] = esc_html__('Columns', 'wp-dp');
            $wp_dp_static_text['wp_dp_search_style'] = esc_html__('Search Style', 'wp-dp');
            $wp_dp_static_text['wp_dp_search_style_desc'] = esc_html__('Select the field style to show on frontend.', 'wp-dp');
            $wp_dp_static_text['wp_dp_view_style'] = esc_html__('View Style', 'wp-dp');
            $wp_dp_static_text['wp_dp_simple'] = esc_html__('Simple', 'wp-dp');
            $wp_dp_static_text['wp_dp_with_background_image'] = esc_html__('With Background Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_multi_select'] = esc_html__('Multi Select', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_multi_select'] = esc_html__('Post Multi Select', 'wp-dp');
            $wp_dp_static_text['wp_dp_first_value'] = esc_html__('First Value', 'wp-dp');
            $wp_dp_static_text['wp_dp_first_value_desc'] = esc_html__('This can be considered as placeholder for this field, It will be selected by default with empty value.', 'wp-dp');
            $wp_dp_static_text['wp_dp_options'] = esc_html__('Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_desc'] = esc_html__('This is the select field and have multiple options to choose between so please add those options with values.', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_another'] = esc_html__('Add Another', 'wp-dp');
            $wp_dp_static_text['wp_dp_remove_this'] = esc_html__('Remove This', 'wp-dp');
            $wp_dp_static_text['wp_dp_date_small'] = esc_html__('Date', 'wp-dp');
            $wp_dp_static_text['wp_dp_date_string'] = esc_html__('Date: %s', 'wp-dp');
            $wp_dp_static_text['wp_dp_date_format'] = esc_html__('Date Format', 'wp-dp');
            $wp_dp_static_text['wp_dp_date_format_desc'] = esc_html__('Enter the dateformat you would like to show on while choosing from calendar and showing on frontend. e.g. ', 'wp-dp');
            $wp_dp_static_text['wp_dp_range_small'] = esc_html__('Range', 'wp-dp');
            $wp_dp_static_text['wp_dp_range_string'] = esc_html__('Range: %s', 'wp-dp');
            $wp_dp_static_text['wp_dp_quantity_small'] = esc_html__('Quantity', 'wp-dp');
            $wp_dp_static_text['wp_dp_quantity_string'] = esc_html__('Quantity: %s', 'wp-dp');
            $wp_dp_static_text['wp_dp_minimum_value'] = esc_html__('Minimum Value', 'wp-dp');
            $wp_dp_static_text['wp_dp_minimum_value_desc'] = esc_html__('This is the starting value of range field so add the minimum value user can select in this field, only numbers are allowed.', 'wp-dp');
            $wp_dp_static_text['wp_dp_maximum_value'] = esc_html__('Maximum Value', 'wp-dp');
            $wp_dp_static_text['wp_dp_maximum_value_desc'] = esc_html__('This is the ending value of range field so add the maximum value user can select in this field, only numbers are allowed.', 'wp-dp');
            $wp_dp_static_text['wp_dp_increment_step'] = esc_html__('Number of lapse in minimum and maximum value', 'wp-dp');
            $wp_dp_static_text['wp_dp_increment_step_desc'] = esc_html__('Enter a number interval which will be used as a step when building the range field e.g. the lapse between "100 - 200 - 300" is "100"', 'wp-dp');
            $wp_dp_static_text['wp_dp_slider'] = esc_html__('Slider', 'wp-dp');
            $wp_dp_static_text['wp_dp_dropdown_small'] = esc_html__('Dropdown', 'wp-dp');
            $wp_dp_static_text['wp_dp_dropdown_string'] = esc_html__('Dropdown: %s', 'wp-dp');
            $wp_dp_static_text['wp_dp_field_name_required'] = esc_html__('Field Name is Required', 'wp-dp');
            $wp_dp_static_text['wp_dp_whitespaces_not_allowed'] = esc_html__('Whitespaces not allowed', 'wp-dp');
            $wp_dp_static_text['wp_dp_special_characters_not_allowed'] = esc_html__('Special Characters are not allowed', 'wp-dp');
            $wp_dp_static_text['wp_dp_name_already_exists'] = esc_html__('Name already exists', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_options'] = esc_html__('Listing Type Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_feature'] = esc_html__('Add Feature', 'wp-dp');
            $wp_dp_static_text['wp_dp_update_feature'] = esc_html__('Update Feature', 'wp-dp');
            $wp_dp_static_text['wp_dp_image_gallery'] = esc_html__('Image Gallery', 'wp-dp');
            $wp_dp_static_text['wp_dp_opening_hours'] = esc_html__('Opening Hours', 'wp-dp');
            $wp_dp_static_text['wp_dp_off_days'] = esc_html__('Off Days', 'wp-dp');
            $wp_dp_static_text['wp_dp_inquiry_form_choice'] = esc_html__('Inquiry Form', 'wp-dp');
            $wp_dp_static_text['wp_dp_report_spams'] = esc_html__('Flag listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_similar_posts'] = esc_html__('Similar Posts', 'wp-dp');
            $wp_dp_static_text['wp_dp_featured_listing_image'] = esc_html__('Featured Listing Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_claim_listing'] = esc_html__('Claim Ads', 'wp-dp');
            $wp_dp_static_text['wp_dp_social_share'] = esc_html__('Social Share', 'wp-dp');
            $wp_dp_static_text['wp_dp_location_map'] = esc_html__('Map in content', 'wp-dp');
            $wp_dp_static_text['wp_dp_review_ratings'] = esc_html__('Review & Ratings', 'wp-dp');
            $wp_dp_static_text['wp_dp_services_options'] = esc_html__('Services Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_files_attchments_options'] = esc_html__('Files Attachments Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_floor_plans_options'] = esc_html__('Floor Plans Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_near_by_options'] = esc_html__('Near By Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_env_res_options'] = esc_html__('Environmental Responsibility Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_financing_calculator_choice'] = esc_html__('Financing Calculator', 'wp-dp');
            $wp_dp_static_text['wp_dp_uncheck_features'] = esc_html__('Uncheck Features', 'wp-dp');
            $wp_dp_static_text['wp_dp_review_options'] = esc_html__('Reviews Detail', 'wp-dp');

/////// Listing Type Suggested Tags
            $wp_dp_static_text['wp_dp_select_cats'] = esc_html__('Select Categories', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_cats_hint'] = esc_html__('Select the categories to assign this listing type.', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_new_cats_link'] = esc_html__('Add new Categories', 'wp-dp');

            $wp_dp_static_text['wp_dp_select_suggested_tags'] = esc_html__('Select Tags', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_suggested_tags_hint'] = esc_html__('Select listing type suggested tags from this dropdown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_meta_suggested_tags_desc'] = esc_html__('Select the tags correctly, these are helpfull while user searching the listing.', 'wp-dp');
            
            $wp_dp_static_text['wp_dp_list_meta_video_url_desc'] = esc_html__('Provide the url of the video, e.g. https://player.vimeo.com/video/113282703.', 'wp-dp');
            $wp_dp_static_text['wp_dp_meta_listing_virtual_tour_desc'] = esc_html__('Add the iframe code in this field for the 360&deg; virtual tour', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_listing_sold_desc'] = esc_html__('If the listing is sold already you can change the status to "Yes" and it will be shown along with the listing on frontend.', 'wp-dp');
            $wp_dp_static_text['wp_dp_list_meta_yelp_element_desc'] = esc_html__('Set this switch "ON" to show the yelp places section on listing detail page. Only enable this swtich if the listing type is about realestate. Make sure the Yelp Places switch is set to ON from the listing type settings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_list_meta_file_element_desc'] = esc_html__('Set this switch "ON" to show the attached files on listing detail page. Make sure the Files Attachment switch is set to ON from the listing type settings.', 'wp-dp');
            
            $wp_dp_static_text['wp_dp_list_meta_viewing'] = esc_html__('Viewing', 'wp-dp');
            $wp_dp_static_text['wp_dp_list_meta_viewing_desc'] = esc_html__('Set this switch "ON" to enable the viewing for this listing. Make sure the viewing is set to ON from the listing type settings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_list_meta_features_element_desc'] = esc_html__('Set this switch "ON" to show the features on listing detail page. Make sure the features element switch is set to ON from the listing type settings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_list_meta_video_element_desc'] = esc_html__('Set this switch "ON" to show the video section on listing detail page. Make sure the video element switch is set to ON from the listing type settings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_listing_visibility_desc'] = esc_html__('You can set this field to "Invisible" if dont want to show this listing on the frontend.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_nearby_announcement'] = esc_html__('nearby places.', 'wp-dp');

            
            
            $wp_dp_static_text['wp_dp_design_sketches'] = esc_html__('Design  Sketches', 'wp-dp');
            
            $wp_dp_static_text['wp_dp_add_tag'] = esc_html__('Add Tag', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_tag_name'] = esc_html__('Tag Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_tags'] = esc_html__('Suggested Tags', 'wp-dp');
            $wp_dp_static_text['wp_dp_update_tag'] = esc_html__('Update Tag', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_new_tag_link'] = esc_html__('Add new tags', 'wp-dp');

/////// Listing Type Features
            $wp_dp_static_text['wp_dp_listing_type_features_label'] = esc_html__('Enter Label', 'wp-dp');
/////// Listing Type Packages
            $wp_dp_static_text['wp_dp_select_packages'] = esc_html__('Select Packages', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_packages_hint'] = esc_html__('Select listing type packages from this dropdown.', 'wp-dp');

///////
            $wp_dp_static_text['wp_dp_listing_options'] = esc_html__('Listings Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_select'] = esc_html__('Select', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_posted_on'] = esc_html__('Listing Posted Date', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_expired_on'] = esc_html__('Listing Expired Date', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_yes'] = esc_html__('Yes', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_no'] = esc_html__('No', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_package'] = esc_html__('Listing Package', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_status'] = esc_html__('Listing Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_listing_status'] = esc_html__('Listing Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_awaiting_activation'] = esc_html__('Awaiting Activation', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_active'] = esc_html__('Active', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_inactive'] = esc_html__('Inactive', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_delete'] = esc_html__('Delete', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_deleted'] = esc_html__('Deleted', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_expire'] = esc_html__('Expire', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_old_status'] = esc_html__('Listing Old Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_style'] = esc_html__('Style', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_default'] = esc_html__('Default - Selected From Plugin Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_2_columns'] = esc_html__('2 Columns', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_3_columns'] = esc_html__('3 Columns', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_classic'] = esc_html__('Classic', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_fancy'] = esc_html__('Fancy', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_map_view'] = esc_html__('Map View', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type'] = esc_html__('Listing Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_calendar_demo'] = esc_html__('Calendar Demo', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_hint'] = esc_html__('Select Listing Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_fields'] = esc_html__('Custom Fields', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_organization'] = esc_html__('Organization', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_mailing_information'] = esc_html__('Mailing Information', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_locations_settings'] = esc_html__('Locations Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_env_res'] = esc_html__('ENVIRONMENTAL RESPONSIBILITY', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_select_categories'] = esc_html__('Select Categories', 'wp-dp');
            $wp_dp_static_text['wp_dp_meta_listing_category'] = esc_html__('Listing Category', 'wp-dp');
            $wp_dp_static_text['wp_dp_meta_listing_category_label_hint'] = esc_html__('Select listing category, which already have the add in listing categories', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_categories'] = esc_html__('How would you describe the ', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_no_custom_field_found'] = esc_html__('No Custom Field Found', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_off_days'] = esc_html__('Off Days', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_opening_hours'] = esc_html__('Opening Hours', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_opening_hours_hint_label'] = esc_html__('Set opening hours in your listing detail page sidebar "Click to add opening hours" link and set your hours there.', 'wp-dp');
            
            $wp_dp_static_text['wp_dp_listing_features'] = esc_html__('Features', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_features_hint_label'] = esc_html__('Choose you custom features, which already have the add in listing types features section', 'wp-dp');
            
            $wp_dp_static_text['wp_dp_listing_favourite'] = esc_html__('Favourite', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_to_favourite'] = esc_html__('Save to Favourite', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_remove_to_favourite'] = esc_html__('Removed from Favorites', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_v5_save_to_favourite'] = esc_html__('Favourite', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_v5_remove_to_favourite'] = esc_html__('Unfavourite', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_social_share_text'] = esc_html__('Share', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_featured'] = esc_html__('Featured', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_price_start_from'] = esc_html__('Start from', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_locations'] = esc_html__('Locations', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_add_location'] = esc_html__('Add Location', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_new_location'] = esc_html__('New Location', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_add_new_location'] = esc_html__('Add New Location', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_edit_location'] = esc_html__('Edit Location', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_no_locations_found.'] = esc_html__('No locations found.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_name'] = esc_html__('Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_slug'] = esc_html__('Slug', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_posts'] = esc_html__('Posts', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listings'] = esc_html__('Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_add_new_listing'] = esc_html__('Add New Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_edit_listing'] = esc_html__('Edit Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_new_listing_item'] = esc_html__('New Listing Item', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_view_listing_item'] = esc_html__('View Listing Item', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search'] = esc_html__('Search', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_filter_show_results'] = esc_html__('Show Results', 'wp-dp');
            
            $wp_dp_static_text['wp_dp_listing_nothing_found'] = esc_html__('Nothing found', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_nothing_found_in_trash'] = esc_html__('Nothing found in Trash', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_company'] = esc_html__('Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_price_table_company'] = esc_html__('Date', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_type'] = esc_html__('Listing Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_posted'] = esc_html__('Posted', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_filter_search_for_member'] = esc_html__('Search for a member...', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_filter_search_for_member'] = esc_html__('Search for a member...', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_image'] = esc_html__('Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_expired'] = esc_html__('Expired', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_status'] = esc_html__('Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_column_listing_image'] = esc_html__('Listing Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_categories'] = esc_html__('Listing Categories', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_add_listing_category_label'] = esc_html__('Listing Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_sub_category'] = esc_html__('Sub Category', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_all_categories'] = esc_html__('All Categories', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_parent_category'] = esc_html__('Parent Category', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_parent_category_clone'] = esc_html__('Parent Category Clone', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_edit_category'] = esc_html__('Edit Category', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_update_category'] = esc_html__('Update Category', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_add_new_category'] = esc_html__('Add New Category', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_no_locations_found'] = esc_html__('No locations found.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_column_name'] = esc_html__('Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_column_slug'] = esc_html__('Slug', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_column_posts'] = esc_html__('Posts', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_features'] = esc_html__('LISTING FEATURES', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_desc'] = esc_html__('LISTING DESCRIPTION', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_walk_scores'] = esc_html__('Walk Scores', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_walk_scores_more_detail'] = esc_html__('More Details Here', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_walk_scores_more_detail_simple'] = esc_html__('More Detail', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_transit_score'] = esc_html__('Transit Score', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_bike_score'] = esc_html__('Bike Score', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_score_error_occured'] = esc_html__('An error occurred while fetching walk scores.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_contact_member'] = esc_html__('Contact Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_contact_details'] = esc_html__('Contact Details', 'wp-dp');
// Listing Custom Fields
            $wp_dp_static_text['wp_dp_listing_custom_text'] = esc_html__('Text', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_required'] = esc_html__('Required', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_title'] = esc_html__('Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_meta_key'] = esc_html__('Meta Key', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_meta_key_hint'] = esc_html__('Please enter Meta Key without special character and space.', 'wp-dp');
            $wp_dp_static_text['dwp_dp_listing_custom_place_holder'] = esc_html__('Place Holder', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_enable_search'] = esc_html__('Enable Search', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_default_value'] = esc_html__('Default Value', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_collapse_search'] = esc_html__('Collapse in Search', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_icon'] = esc_html__('Icon', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_text_area'] = esc_html__('Text Area', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_help_text'] = esc_html__('Help Text', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_rows'] = esc_html__('Rows', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_columns'] = esc_html__('Columns', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_dropdown'] = esc_html__('Dropdown', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_enable_multi_select'] = esc_html__('Enable Multi Select', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_post_multi_select'] = esc_html__('Post Multi Select', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_first_value'] = esc_html__('First Value', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_options'] = esc_html__('Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_date'] = esc_html__('Date', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_date_format'] = esc_html__('Date Format', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_email'] = esc_html__('Email', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_url'] = esc_html__('Url', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_range'] = esc_html__('Range', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_minimum_value'] = esc_html__('Minimum Value', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_maximum_value'] = esc_html__('Maximum Value', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_increment_step'] = esc_html__('Increment Step', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_enable_inputs'] = esc_html__('Enable Inputs', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_search_style'] = esc_html__('Search Style', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_input'] = esc_html__('Input', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_slider'] = esc_html__('Slider', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_Input_Slider'] = esc_html__('Input + Slider', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_please_select_atleast_one_option'] = esc_html__('Please select atleast one option for', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_field'] = esc_html__('field', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_all_settings_saved'] = esc_html__('All Settings Saved', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_field_name_required'] = esc_html__('Field name is required.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_whitespaces_not_allowed'] = esc_html__('Whitespaces not allowed', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_special_characters_not_allowed'] = esc_html__('Special character not allowed but only (_,-).', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_name_already_exist'] = esc_html__('Name already exist.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_custom_name_available'] = esc_html__('Name Available.', 'wp-dp');
// Listing Images Gallery/opening hours.
            $wp_dp_static_text['wp_dp_listing_image_gallery'] = esc_html__('Images Gallery', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_gallery_image'] = esc_html__('Gallery Images', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_schedule_with_time'] = esc_html__('Schedule With Time', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_opening_time'] = esc_html__('Opening Time', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_closing_time'] = esc_html__('Closing Time', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_monday_on'] = esc_html__('Monday On?', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_monday'] = esc_html__('Monday', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_tuesday'] = esc_html__('Tuesday', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_tuesday_on'] = esc_html__('Tuesday On?', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_wednesday'] = esc_html__('Wednesday', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_wednesday_on'] = esc_html__('Wednesday On?', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_thursday'] = esc_html__('Thursday', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_thursday_on'] = esc_html__('Thursday On?', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_friday'] = esc_html__('Friday', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_friday_on'] = esc_html__('Friday On?', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_saturday'] = esc_html__('Saturday', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_saturday_on'] = esc_html__('Saturday On?', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_sunday'] = esc_html__('Sunday', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_sunday_on'] = esc_html__('Sunday On?', 'wp-dp');
//Listing Page element
            $wp_dp_static_text['wp_dp_listing_page_elements'] = esc_html__('Page Elements', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_page_inquire_form'] = esc_html__('Inquire Form ON / OFF', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_page_financing_calculator'] = esc_html__('Financing calculator ON / OFF', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_page_report_spams'] = esc_html__('Flag listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_page_similar_posts'] = esc_html__('Similar Posts', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_page_featured_listing_image'] = esc_html__('Featured Listing Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_page_claim_listing'] = esc_html__('Claim Ads', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_page_social_share'] = esc_html__('Social Share', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_page_review_ratings'] = esc_html__('Review & Ratings', 'wp-dp');
//Listing Posted by
            $wp_dp_static_text['wp_dp_listing_posted_by'] = esc_html__('Posted by', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_user_profile_data'] = esc_html__('User Profile Data', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_posted_logo'] = esc_html__('Logo', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_posted_full_name_business_name'] = esc_html__('Full Name / Business Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_posted_email'] = esc_html__('Email', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_posted_website'] = esc_html__('Website', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_posted_facebook'] = esc_html__('Facebook', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_posted_twitter'] = esc_html__('Twitter', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_posted_linkedIn'] = esc_html__('LinkedIn', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_posted_google_plus'] = esc_html__('Google Plus', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_posted_phone_no'] = esc_html__('Phone No', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_posted_select_a_user'] = esc_html__('Select a user', 'wp-dp');
//Listing Services
            $wp_dp_static_text['wp_dp_listing_services'] = esc_html__('Services', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_services_title'] = esc_html__('Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_services_description'] = esc_html__('Promotions', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_promotion_id'] = esc_html__('Promotion Id', 'wp-dp');
            
            $wp_dp_static_text['wp_dp_listing_services_icon'] = esc_html__('Icon', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_services_price'] = esc_html__('Price', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_services_capacity'] = esc_html__('Capacity', 'wp-dp');
//Listing save post options
            $wp_dp_static_text['wp_dp_listing_save_post_browse'] = esc_html__('Browse', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_load_icomoon'] = esc_html__('Load from IcoMoon selection.json', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_country'] = esc_html__('Country', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_select_country'] = esc_html__('Select Country', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_city'] = esc_html__('City', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_select_city'] = esc_html__('Select City', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_complete_address'] = esc_html__('Complete Address', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_complete_address_hint'] = esc_html__('Enter you complete address with city, state or country.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_find_on_map'] = esc_html__('Find on Map', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_address'] = esc_html__('Address', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_latitude'] = esc_html__('Latitude', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_longitude'] = esc_html__('Longitude', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_search_location_on_map'] = esc_html__('Search This Location on Map', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_search_location'] = esc_html__('Search Location', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_update_map'] = esc_html__('update map', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_please_wait'] = esc_html__('Please wait...', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_loaded_icons'] = esc_html__('Successfully loaded icons', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_error_try_again'] = esc_html__('Error: Try Again?', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_image'] = esc_html__('Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_choose_icon'] = esc_html__('Choose Icon', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_ISO_code'] = esc_html__('ISO Code', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_member'] = esc_html__('Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_listings'] = esc_html__('Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_save_post_register'] = esc_html__('Register', 'wp-dp');


// post type price tables
            $wp_dp_static_text['wp_dp_post_type_price_table_name'] = esc_html__('Price Tables', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_price_table_singular_name'] = esc_html__('Price Table', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_price_table_menu_name'] = esc_html__('Price Tables', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_price_table_name_admin_bar'] = esc_html__('Price Tables', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_price_table_add_new'] = esc_html__('Add Price Table', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_price_table_add_new_item'] = esc_html__('Add Price Table', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_price_table_new_item'] = esc_html__('Add Price Table', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_price_table_edit_item'] = esc_html__('Edit Price Table', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_price_table_view_item'] = esc_html__('Price Table', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_price_table_all_items'] = esc_html__('Price Tables', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_price_table_search_items'] = esc_html__('Price Table', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_price_table_not_found'] = esc_html__('Price Tables', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_price_table_not_found_in_trash'] = esc_html__('Price Tables', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_price_table_description'] = esc_html__('Edit Price Table', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_price_table_meta_number_of_services'] = esc_html__('Price Table', 'wp-dp');
// price tables meta
            $wp_dp_static_text['wp_dp_listing_price_tables_options'] = esc_html__('Price Table Options', 'wp-dp');

// post type packages
            $wp_dp_static_text['wp_dp_post_type_package_name'] = esc_html__('Packages', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_package_singular_name'] = esc_html__('Package', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_package_menu_name'] = esc_html__('Packages', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_package_name_admin_bar'] = esc_html__('Packages', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_package_add_new'] = esc_html__('Add Package', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_package_add_new_item'] = esc_html__('Add Package', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_package_new_item'] = esc_html__('Add Package', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_package_edit_item'] = esc_html__('Edit Package', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_package_view_item'] = esc_html__('Package', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_package_all_items'] = esc_html__('Packages', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_package_search_items'] = esc_html__('Package', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_package_not_found'] = esc_html__('Packages', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_package_not_found_in_trash'] = esc_html__('Packages', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_package_description'] = esc_html__('Edit Package', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_package_meta_number_of_services'] = esc_html__('Package', 'wp-dp');
// post type Branches
            $wp_dp_static_text['wp_dp_branches'] = esc_html__('Branches', 'wp-dp');
            $wp_dp_static_text['wp_dp_branch_options'] = esc_html__('Branch Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_branch_name'] = esc_html__('Branches', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_branch_singular_name'] = esc_html__('Branch', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_branch_menu_name'] = esc_html__('Branches', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_branch_name_admin_bar'] = esc_html__('Branches', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_branch_add_new'] = esc_html__('Add Branch', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_branch_add_new_item'] = esc_html__('Add Branch', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_branch_new_item'] = esc_html__('Add Branch', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_branch_edit_item'] = esc_html__('Edit Branch', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_branch_view_item'] = esc_html__('Branch', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_branch_all_items'] = esc_html__('Branches', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_branch_search_items'] = esc_html__('Branche', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_branch_not_found'] = esc_html__('Branches', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_branch_not_found_in_trash'] = esc_html__('Branches', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_branch_description'] = esc_html__('Edit Branch', 'wp-dp');
            $wp_dp_static_text['wp_dp_post_type_branch_meta_number_of_services'] = esc_html__('Branch', 'wp-dp');
// Arrange Viewing Post Type Meta
            $wp_dp_static_text['wp_dp_listing_arrange_viewings_options'] = esc_html__('Listing Arrange Viewing Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_order_id'] = esc_html__('Viewing ID', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_listing_member'] = esc_html__('Listing Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_Viewing_member'] = esc_html__('Viewing Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewings_'] = esc_html__('Service', 'wp-dp');
// Listing Enquiries Post Type Meta
            $wp_dp_static_text['wp_dp_listing_enquiries_options'] = esc_html__('Listing Message Options', 'wp-dp');
// Packages meta
            $wp_dp_static_text['wp_dp_listing_packages_options'] = esc_html__('Package Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_num_listing_allowed'] = esc_html__('Number of Listing Ads ', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_num_listing_allowed_hint'] = esc_html__('Add no of listing allowed in this package.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_duration'] = esc_html__('Package Duration ( Days )', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_duration_hint'] = esc_html__('Add duration of package.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_days'] = esc_html__('Days', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_listing_duration'] = esc_html__('Listing Ads Duration ( Days )', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_listing_duration_hint'] = esc_html__('Add duration of listing ads.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_month'] = esc_html__('Month', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_num_pictures'] = esc_html__('No of Pictures Allowed', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_num_pictures_hint'] = esc_html__('Add no of pictures allowed in this package.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_num_documents'] = esc_html__('Number of Attachments', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_num_documents_hint'] = esc_html__('Add no of attachment allowed in this package.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_num_tags'] = esc_html__('Search Tags', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_num_tags_hint'] = esc_html__('Add no of tags allowed in this package.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_reviews'] = esc_html__('Reviews Allowed', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_reviews_hint'] = esc_html__('Reviews On/Off', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_home_featured_listing'] = esc_html__('Home Featured Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_greater_val_error'] = esc_html__('Please enter a value less than number of listing Allowed', 'wp-dp');
            $wp_dp_static_text['wp_dp_number_of_top_cat_listings'] = esc_html__('Top Categories Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_number_of_top_cat_listings_hint'] = esc_html__('Add no of top categories listings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_package_tile'] = esc_html__('Package Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_package_type'] = esc_html__('Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_package_type_hint'] = esc_html__('Select package type from this dropdown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_package_type_free'] = esc_html__('Free', 'wp-dp');
            $wp_dp_static_text['wp_dp_package_type_paid'] = esc_html__('Paid', 'wp-dp');
			$wp_dp_static_text['wp_dp_package_already_activated'] = esc_html__('Activated', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_top_categories'] = esc_html__('Top of Categories', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_phone_num'] = esc_html__('Allow Phone Number', 'wp-dp');
			$wp_dp_static_text['wp_dp_listing_phone_num_web_str'] = esc_html__('Phone Number / Website', 'wp-dp');
			$wp_dp_static_text['wp_dp_listing_packages_phone_num_web'] = esc_html__('Allow Phone Number / Website', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_website_link'] = esc_html__('Website Link', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_cover_image'] = esc_html__('Cover Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_social_impressions'] = esc_html__('Social Auto-Poster', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_social_impressions_hint_text'] = esc_html__('Auto listing sharing for %s', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_social_impressions_hint_social_network'] = esc_html__('Social Networks', 'wp-dp');

            $wp_dp_static_text['wp_dp_listing_packages_respond_reviews'] = esc_html__('Can respond to reviews', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_24support'] = esc_html__('24 Support', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_analytics_tracking'] = esc_html__('Analytics and Tracking', 'wp-dp');
            $wp_dp_static_text['wp_dp_number_of_feature_listings'] = esc_html__('Featured Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_number_of_feature_listings_hint'] = esc_html__('Add no of featured listings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_packages_seo'] = esc_html__('Search Engine Optimization', 'wp-dp');
            $wp_dp_static_text['wp_dp_package_price'] = esc_html__('Price', 'wp-dp');
            $wp_dp_static_text['wp_dp_package_price_hint'] = esc_html__('Add package price in this field.', 'wp-dp');
            $wp_dp_static_text['wp_dp_package_icon'] = esc_html__('Icon', 'wp-dp');
            $wp_dp_static_text['wp_dp_package_best_offer'] = esc_html__('Best offer', 'wp-dp');

            // Import/Export users
            $wp_dp_static_text['wp_dp_listing_users_zip_file'] = esc_html__('Zip file', 'wp-dp');
            $wp_dp_static_text['wp_dp_import_may_want_to_see'] = esc_html__('You may want to see', 'wp-dp');
            $wp_dp_static_text['wp_dp_import_the_demo_file'] = esc_html__('the demo file', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_zip_notification'] = esc_html__('Notification', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_zip_send_new_users'] = esc_html__('Send to new users', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_password_nag'] = esc_html__('Password nag', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_password_nag_hint'] = esc_html__('Show password nag on new users signon', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_update'] = esc_html__('Users update', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_update_hint'] = esc_html__('Update user when a username or email exists', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_import_users'] = esc_html__('Import Users', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_export_all_users'] = esc_html__('Export All Users', 'wp-dp');

            // Import/Export users errors/Notices
            $wp_dp_static_text['wp_dp_listing_users_update'] = esc_html__('Import / Export Users', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_export'] = esc_html__('Export Users', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_data_import_error'] = esc_html__('There is an error in your users data import, please try later', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_import_notice'] = esc_html__('Notice: please make the wp_dp %s writable so that you can see the error log.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_error_file_upload'] = esc_html__('Error during file upload.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_cannot_extract_data'] = esc_html__('Cannot extract data from uploaded file or no file was uploaded.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_not_imported'] = esc_html__('No user was successfully imported%s.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_imported_some_success'] = esc_html__('Some users were successfully imported but some were not%s.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_import_successful'] = esc_html__('Users import was successful.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_invalid_file_type'] = esc_html__('You have selected invalid file type, Please try again.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_export_successful'] = esc_html__('Users has been done export successful.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_import_user_data'] = esc_html__('Import User Data', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_users_sufficient_permissions'] = esc_html__('You do not have sufficient permissions to access this page.', 'wp-dp');

            // user meta 
            $wp_dp_static_text['wp_dp_user_meta_extra_profile_information'] = esc_html__('Extra profile information', 'wp-dp');
            $wp_dp_static_text['wp_dp_user_meta_my_profile'] = esc_html__('Profile Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_user_meta_profile_settings'] = esc_html__('Profile Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_user_meta_logo'] = esc_html__('Logo', 'wp-dp');
            $wp_dp_static_text['wp_dp_user_meta_gallery'] = esc_html__('Gallery', 'wp-dp');
            $wp_dp_static_text['wp_dp_user_meta_full_name_or_business_nme'] = esc_html__('Full Name / Business Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_user_meta_email'] = esc_html__('Email', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_string'] = esc_html__('Email: %s', 'wp-dp');
            $wp_dp_static_text['wp_dp_user_meta_website'] = esc_html__('Website', 'wp-dp');
            $wp_dp_static_text['wp_dp_user_meta_social_networks'] = esc_html__('Social Networks', 'wp-dp');
            $wp_dp_static_text['wp_dp_user_meta_facebook'] = esc_html__('Facebook', 'wp-dp');
            $wp_dp_static_text['wp_dp_user_meta_twitter'] = esc_html__('Twitter', 'wp-dp');
            $wp_dp_static_text['wp_dp_user_meta_linkedIn'] = esc_html__('LinkedIn', 'wp-dp');
            $wp_dp_static_text['wp_dp_user_meta_google_plus'] = esc_html__('Google Plus', 'wp-dp');
            $wp_dp_static_text['wp_dp_user_meta_phone_no'] = esc_html__('Phone No', 'wp-dp');
            $wp_dp_static_text['wp_dp_user_meta_mailing_information'] = esc_html__('Mailing Information', 'wp-dp');

            // listing type meta
            $wp_dp_static_text['wp_dp_listing_type_meta_custom_fields'] = esc_html__('Custom Fields', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_form_builders'] = esc_html__('Form Builders', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_features'] = esc_html__('Features', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_suggested_tags'] = esc_html__('Suggested Tags', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_packages'] = esc_html__('Packages', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_categories'] = esc_html__('Categories', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_page_elements'] = esc_html__('Page Elements', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_required_elements'] = esc_html__('Listing Detail Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_det_page_elements'] = esc_html__('Detail Page Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_settings'] = esc_html__('Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_general_settings'] = esc_html__('General Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_enable_upload'] = esc_html__('Enable Upload', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_image_per_ad'] = esc_html__('Image per Ad', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_price'] = esc_html__('Price', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_price_switch'] = esc_html__('Price', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_price_field_label'] = esc_html__('Price Field Label', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_enable_price_search'] = esc_html__('Enable Price Search', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_min_range'] = esc_html__('Min Range', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_max_range'] = esc_html__('Max Range', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_increament'] = esc_html__('Increament', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_price_search_style'] = esc_html__('Price Search Style', 'wp-dp');

            $wp_dp_static_text['wp_dp_no_of_pictures_allowed'] = esc_html__('Number of Pictures Allowed', 'wp-dp');
            $wp_dp_static_text['wp_dp_no_of_tags_allowed'] = esc_html__('Number of Tags Allowed', 'wp-dp');
            $wp_dp_static_text['wp_dp_auto_reviews_approval'] = esc_html__('Auto Reviews Approval', 'wp-dp');
            $wp_dp_static_text['wp_dp_ads_images_videos_limit'] = esc_html__('Ads Images / Videos Limit', 'wp-dp');
            $wp_dp_static_text['wp_dp_opening_hour_time_lapse'] = esc_html__('Opening Hour Time Laps ( In Minutes )', 'wp-dp');
            $wp_dp_static_text['wp_dp_opening_hour_time_lapse_desc'] = esc_html__('Enter a time interval which will be used as a step when building opening hours. e.g. the lapse between "15 - 30 - 45" is "15" Minutes. Only numbers are allowed.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_feature_add_row'] = esc_html__('Add New Feature', 'wp-dp');
            $wp_dp_static_text['wp_dp_orders_inquiries_status'] = esc_html__('Enquiries/Arrange Viewings Statuses', 'wp-dp');
            $wp_dp_static_text['wp_dp_orders_inquiries_add_status'] = esc_html__('Add New Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_orders_inquiries_enter_status'] = esc_html__('Enter Status', 'wp-dp');

            // member profile tab
            $wp_dp_static_text['wp_dp_member_profile_settings'] = esc_html__('Profile Setting', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_first_name'] = esc_html__('First name', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_last_name'] = esc_html__('Last Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_display_name'] = esc_html__('Display Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_company_name'] = esc_html__('Member Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_email_address'] = esc_html__('Email Address', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_profile_individual'] = esc_html__('Individual', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_profile_company'] = esc_html__('Company', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_change_password'] = esc_html__('Password Change', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_current_password'] = esc_html__('Current Password', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_new_password'] = esc_html__('New Password', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_new_password_em'] = esc_html__('leave blank to leave unchanged', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_confirm_new_password'] = esc_html__('Confirm New Password', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_address'] = esc_html__('Address', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_country'] = esc_html__('Country', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_city_town'] = esc_html__('Town / City', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_upload_profile_picture'] = esc_html__('Upload a profile picture or choose one of the following', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_upload_featured_image'] = esc_html__('Upload a featured image', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_upload_profile_picture_button'] = esc_html__('Upload Picture', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_first_name_empty_error'] = esc_html__('first name should not be empty', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_last_name_empty_error'] = esc_html__('last name should not be empty', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_display_name_empty_error'] = esc_html__('display name should not be empty', 'wp-dp');

            $wp_dp_static_text['wp_dp_member_company_name_exist_error'] = esc_html__('Profile Url already taken', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_biography_empty_error'] = esc_html__('Biography should not be empty', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_phone_empty_error'] = esc_html__('Phone number should not be empty', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_email_empty_error'] = esc_html__('email should not be empty', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_company_name_empty_error'] = esc_html__('Company name should not be empty', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_email_valid_error'] = esc_html__('email address is not valid', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_email_exists_error'] = esc_html__('email already exists!', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_invalid_current_pass'] = esc_html__('Invalid current password', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_pass_and_confirmpass_not_mached'] = esc_html__('Password and confirm password did not matched', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_updated_success_mesage'] = esc_html__('Updated successfully', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_no_permissions_to_upload'] = esc_html__('No permissions to upload file', 'wp-dp');
            $wp_dp_static_text['wp_dp_cropping_file_error'] = esc_html__('something went wrong, most likely file is to large for upload. check upload_max_filesize, post_max_size and memory_limit in you php.ini', 'wp-dp');
            $wp_dp_static_text['wp_dp_uploading_avatar_error'] = esc_html__('Image size too large max image size is 1 MB', 'wp-dp');

            // Members Post Type
            $wp_dp_static_text['wp_dp_members'] = esc_html__('Members', 'wp-dp');
            $wp_dp_static_text['wp_dp_company'] = esc_html__('Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_search_members'] = esc_html__('Search Members', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_company'] = esc_html__('Add New Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_edit_company'] = esc_html__('Edit Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_no_member'] = esc_html__('No Member', 'wp-dp');

            // Member Permissions
            $wp_dp_static_text['profile_manage'] = esc_html__('Profile Manage', 'wp-dp');
            $wp_dp_static_text['listings_manage'] = esc_html__('Listings Manage', 'wp-dp');
            $wp_dp_static_text['orders_manage'] = esc_html__('Orders Manage', 'wp-dp');
            $wp_dp_static_text['enquiries_manage'] = esc_html__('Enquiries Manage', 'wp-dp');
            $wp_dp_static_text['arrange_viewings_manage'] = esc_html__('Viewings Manage', 'wp-dp');
            $wp_dp_static_text['hidden_listings_manage'] = esc_html__('Hidden Listings Manage', 'wp-dp');
            $wp_dp_static_text['listing_notes_manage'] = esc_html__('Listing Notes Manage', 'wp-dp');
            $wp_dp_static_text['arrange_viewings_manage'] = esc_html__('Viewings Manage', 'wp-dp');
            $wp_dp_static_text['reviews_manage'] = esc_html__('Reviews Manage', 'wp-dp');
            $wp_dp_static_text['packages_manage'] = esc_html__('Packages Manage', 'wp-dp');
            $wp_dp_static_text['favourites_manage'] = esc_html__('Favourite Manage', 'wp-dp');

            // Packages add fields
            $wp_dp_static_text['wp_dp_add_field'] = esc_html__('Add Package Field', 'wp-dp');
            $wp_dp_static_text['wp_dp_package_field'] = esc_html__('Package Field', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_field_label'] = esc_html__('Label', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_field_type'] = esc_html__('Field Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_field_type_hint'] = esc_html__('Select field type from this dropdown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_field_single_choice'] = esc_html__('Single Choice', 'wp-dp');
            $wp_dp_static_text['wp_dp_add_field_single_line'] = esc_html__('Single Line', 'wp-dp');
            $wp_dp_static_text['wp_dp_package_additional_fields'] = esc_html__('Package Additional Fields', 'wp-dp');
            $wp_dp_static_text['wp_dp_company_details'] = esc_html__('Member Data', 'wp-dp');
            $wp_dp_static_text['wp_dp_phone'] = esc_html__('Phone Number', 'wp-dp');
            $wp_dp_static_text['wp_dp_email_address'] = esc_html__('Email', 'wp-dp');
            $wp_dp_static_text['wp_dp_website'] = esc_html__('Website', 'wp-dp');


            $wp_dp_static_text['wp_dp_member_company_settings'] = esc_html__('Member Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_company_name'] = esc_html__('Display Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_company_slug'] = esc_html__('Profile Url', 'wp-dp');
            $wp_dp_static_text['wp_dp_add'] = esc_html__('Add', 'wp-dp');


            $wp_dp_static_text['wp_dp_member_company_website'] = esc_html__('Member Website', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_company_phone'] = esc_html__('Member Phone', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_company_description'] = esc_html__('Member Description', 'wp-dp');
            $wp_dp_static_text['company_profile_manage'] = esc_html__('Member Profile Manage', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_account_display_name'] = esc_html__('Account Display Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_company_name'] = esc_html__('Company Name', 'wp-dp');

            // widgets
            $wp_dp_static_text['wp_dp_var_locations'] = esc_html__('Cs:Locations', 'wp-dp');
            $wp_dp_static_text['wp_dp_var_locations_description'] = esc_html__('WP Directorybox Manager Locations widget');
            $wp_dp_static_text['wp_dp_widget_title'] = esc_html__('Title');
            $wp_dp_static_text['wp_dp_widget_title_desc'] = esc_html__('Enter Descreption');
            $wp_dp_static_text['choose_location_fields'] = esc_html__('Locations');
            $wp_dp_static_text['choose_location_fields_desc'] = esc_html__('Select Locations');


            // Banners
            $wp_dp_static_text['wp_dp_banner_single_banner'] = esc_html__(' Single Banner', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_random_banner'] = esc_html__('Random Banners', 'wp-dp');
            $wp_dp_static_text['wp_dp_select_category'] = esc_html__('Select Category', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_view'] = esc_html__('Banner View ', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_view_hint'] = esc_html__('Select Banner View ', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_search_pagination'] = esc_html__('Show Pagination', 'wp-dp');

            $wp_dp_static_text['wp_dp_banner_no_of_banner'] = esc_html__('Number of Banners', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_no_of_banner_hint'] = esc_html__('Please Number of Banners here', 'wp-dp');

            $wp_dp_static_text['wp_dp_banner_code'] = esc_html__('Banner Code', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_code_hint'] = esc_html__('Please Banner Code here', 'wp-dp');

            $wp_dp_static_text['wp_dp_banner_title_field'] = esc_html__('Banner Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_title_field_hint'] = esc_html__('Please enter Banner Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_style'] = esc_html__('Banner Style', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_style_hint'] = esc_html__('Please Select  Banner Style', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_type'] = esc_html__('Banner Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_type_hint'] = esc_html__('Please enter  Banner Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_type_top'] = esc_html__('Top Banner', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_type_bottom'] = esc_html__('Bottom Banner', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_type_listing_detail'] = esc_html__('Listing Detail Banner', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_type_listing'] = esc_html__('Listing Banner', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_type_listing_leftfilter'] = esc_html__('Listing Left Filter Banner', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_type_sidebar'] = esc_html__('Sidebar Banner', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_url_field'] = esc_html__('Banner Url', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_type_member'] = esc_html__('Member Banner', 'wp-dp');

            $wp_dp_static_text['wp_dp_banner_type_vertical'] = esc_html__('Vertical Banner', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_image'] = esc_html__('Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_code'] = esc_html__('Code', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_adsense_code'] = esc_html__('Adsense Code', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_adsense_code_hint'] = esc_html__('Please enter Adsense Code for Ad', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_image_hint'] = esc_html__('Please Select Banner Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_target'] = esc_html__('Target', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_target_hint'] = esc_html__('Please select Banner Link Target', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_target_self'] = esc_html__('Self', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_target_blank'] = esc_html__('Blank', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_already_added'] = esc_html__('Already Added Banners', 'wp-dp');

            $wp_dp_static_text['wp_dp_banner_table_title'] = esc_html__('Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_table_style'] = esc_html__('Style', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_table_image'] = esc_html__('Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_table_clicks'] = esc_html__('Clicks', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_table_shortcode'] = esc_html__('Shortcode', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_user_buyer'] = esc_html__('Buyer', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_user_reseller'] = esc_html__('Reseller', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_user_seller'] = esc_html__('Seller', 'wp-dp');
            $wp_dp_static_text['wp_dp_top_member_status_filter'] = esc_html__('Member Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_top_member_title_field'] = esc_html__('Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_top_member_num_post'] = esc_html__('No of Record', 'wp-dp');

            $wp_dp_static_text['wp_dp_top_listings_title_field'] = esc_html__('Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_top_listings_num_post'] = esc_html__('No of Record', 'wp-dp');

            // Class Save Posts
            $wp_dp_static_text['wp_dp_save_post_display_name_table'] = esc_html__('Display Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_listing_status_awaiting_activation'] = esc_html__('Awaiting Activation', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_listing_status_inactive'] = esc_html__('Inactive', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_listing_status_delete'] = esc_html__('Delete', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_listing_status_pending'] = esc_html__('Pending', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_listing_status_active'] = esc_html__('Active', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_country'] = esc_html__('Country', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_select_country'] = esc_html__('Select Country', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_select_country_hint'] = esc_html__('Select country from this dropdown list', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_state'] = esc_html__('State', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_select_state'] = esc_html__('Select State', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_select_state_hint'] = esc_html__('Select state from this dropdown list', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_city'] = esc_html__('City', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_select_city'] = esc_html__('Select City', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_select_city_hint'] = esc_html__('Select city from this dropdown list', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_town'] = esc_html__('Town', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_select_town'] = esc_html__('Select Town', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_select_town_hint'] = esc_html__('Select town from this dropdown list', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_find_on_map'] = esc_html__('Find on Map', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_address'] = esc_html__('Address', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_address_hint'] = esc_html__('Add address in the address field', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_latitude'] = esc_html__('Latitude', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_latitude_hint'] = esc_html__('Will be auto select when "Address" is given in address search field and click "Search This Location on Map" button.', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_longitude'] = esc_html__('Longitude', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_longitude_hint'] = esc_html__('Will be auto select when "Address" is given in address search field and click "Search This Location on Map" button.', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_search_on_map'] = esc_html__('Search This Location on Map', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_address_location'] = esc_html__('Address/Location', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_type_address'] = esc_html__('Type Your Address', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_hide_location'] = esc_html__('Hide Location', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_radius_exact_location'] = esc_html__('Radius/Exact Location', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_location_precise_drag_drop'] = esc_html__('For the precise location, you can drag and drop the pin.', 'wp-dp');
            $wp_dp_static_text['wp_dp_save_post_add_listing_location_precise_drag_drop'] = esc_html__('For precise location, drag and drop the pin.', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_image'] = esc_html__('Image / Icon', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_map_iamge'] = esc_html__('Map Marker Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_title'] = esc_html__('Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_icon'] = esc_html__('Icon', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_select_an_icon'] = esc_html__('Select an icon', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_select_an_imag'] = esc_html__('Select an image', 'wp-dp');
            $wp_dp_static_text['wp_dp_marker_opions_marker_type'] = esc_html__('Marker Type', 'wp-dp'); 
            $wp_dp_static_text['wp_dp_submit_button_save_changes'] = esc_html__('Save Changes', 'wp-dp');

            $wp_dp_static_text['wp_dp_plugin_option_map_custom_style'] = esc_html__('Map Custom Style', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_option_map_custom_style_desc'] = __('Add Map Custom Style code. You can find all styles from here %s', 'wp-dp');

            $wp_dp_static_text['wp_dp_plugin_options_demo_user_modification_allowed'] = esc_html__('Demo User Modification Allowed', 'wp-dp');

            $wp_dp_static_text['wp_dp_enquiry_post_filter_for_member'] = esc_html__('Search for a Owner...', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_post_filter_for_Listing'] = esc_html__('Search for a Listing...', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_post_status_change'] = esc_html__('Status Changed.', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_post_there_is_error'] = esc_html__('There is some error.', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_post_status_txt'] = esc_html__('Status', 'wp-dp');


            $wp_dp_static_text['wp_dp_options_status_color'] = esc_html__('Status Color', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_status_label'] = esc_html__('Status Label', 'wp-dp');


            $wp_dp_static_text['wp_dp_options_banner_image_error'] = esc_html__('Please provide image for banner', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_banner_code_error'] = esc_html__('Please provide adsense code', 'wp-dp');

            $wp_dp_static_text['wp_dp_shortcode_split_map_heading'] = esc_html__('DB: Split Map', 'wp-dp');
            $wp_dp_static_text['wp_dp_shortcode_classic'] = esc_html__('Classic', 'wp-dp');

            $wp_dp_static_text['wp_dp_shortcode_map_position'] = esc_html__('Map Position', 'wp-dp');
            $wp_dp_static_text['wp_dp_shortcode_map_left'] = esc_html__('Left', 'wp-dp');
            $wp_dp_static_text['wp_dp_shortcode_map_right'] = esc_html__('Right', 'wp-dp');
            $wp_dp_static_text['wp_dp_shortcode_map_top'] = esc_html__('Top', 'wp-dp');
            $wp_dp_static_text['wp_dp_shortcode_map_bottom'] = esc_html__('Bottom', 'wp-dp');
            $wp_dp_static_text['wp_dp_shortcode_split_map_options'] = esc_html__('DB: Split Map Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_shortcode_fixed_map'] = esc_html__('Fixed Map', 'wp-dp');

            $wp_dp_static_text['wp_dp_listing_type_in'] = esc_html__('in', 'wp-dp');
            $wp_dp_static_text['wp_dp_top_map_draw_btn'] = esc_html__('Draw', 'wp-dp');
            $wp_dp_static_text['wp_dp_top_map_clear_btn'] = esc_html__('Clear', 'wp-dp');
            $wp_dp_static_text['wp_dp_mortgage_calculator_month'] = esc_html__('MONTH', 'wp-dp');

            $wp_dp_static_text['wp_dp_advance_search_select_price_type_label'] = esc_html__('Price Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_advance_search_select_price_types_all'] = esc_html__('All', 'wp-dp');
            $wp_dp_static_text['wp_dp_advance_search_select_price_range'] = esc_html__('Price Range', 'wp-dp');
            $wp_dp_static_text['wp_dp_advance_search_min_price_range'] = esc_html__('Min. Price', 'wp-dp');
            $wp_dp_static_text['wp_dp_advance_search_max_price_range'] = esc_html__('Max. Price', 'wp-dp');

            $wp_dp_static_text['wp_dp_listing_notes'] = esc_html__('Listing Notes', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_notes_added'] = esc_html__('Listing Notes Added', 'wp-dp');
            $wp_dp_static_text['wp_dp_notes'] = esc_html__('Notes', 'wp-dp');
            $wp_dp_static_text['wp_dp_notes_added'] = esc_html__('Notes Added', 'wp-dp');
            $wp_dp_static_text['wp_dp_notification_removed'] = esc_html__('Activity notification has been removed successfully', 'wp-dp');
			$wp_dp_static_text['wp_dp_listing_quick_view'] = esc_html__('Preview', 'wp-dp');
			$wp_dp_static_text['wp_dp_listing_about'] = esc_html__('About:', 'wp-dp');
			$wp_dp_static_text['wp_dp_listing_read_mored'] = esc_html__('Read More', 'wp-dp');
			
			$wp_dp_static_text['wp_dp_email_logs_column_settings_resp_sent'] = esc_html__("Sent", 'wp-dp');
            $wp_dp_static_text['wp_dp_email_logs_column_settings_resp_fail'] = esc_html__("Failed", 'wp-dp');
			$wp_dp_static_text['wp_dp_user_listing_success'] = esc_html__("User account and listing successfully registered.", 'wp-dp');
			$wp_dp_static_text['wp_dp_cant_direct_access'] = esc_html__("You can't direct access add listing page.", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_customer_detail'] = esc_html__("Customer Detail", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_order_type'] = esc_html__("Order Type:", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_order_posted'] = esc_html__("Order Posted:", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_payment_status'] = esc_html__("Payment Status:", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_order_method'] = esc_html__("Payment Method:", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_order'] = esc_html__("Order", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_close'] = esc_html__("Close", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_print_invoice'] = esc_html__("Print Invoice", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_detail'] = esc_html__("Transaction Detail", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_name'] = esc_html__("Name:", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_phone'] = esc_html__("Phone Number:", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_email'] = esc_html__("Email:", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_address'] = esc_html__("Address", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_summary'] = esc_html__("Order Summary", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_ref_no'] = esc_html__("Ref No", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_price'] = esc_html__("Price", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_subtotal'] = esc_html__("Subtotal:", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_vat'] = esc_html__("VAT (%s&#37)", 'wp-dp');
			$wp_dp_static_text['wp_dp_transaction_total'] = esc_html__("Total:", 'wp-dp');
			$wp_dp_static_text['wp_dp_member_transaction_not_found'] = esc_html__("No transactions found.", 'wp-dp');
			$wp_dp_static_text['wp_dp_directory_menu_title'] = esc_html__("Directory", 'wp-dp');
			$wp_dp_static_text['wp_dp_directory_menu_customization'] = esc_html__("Customization", 'wp-dp');
			$wp_dp_static_text['wp_dp_directory_menu_plugins'] = esc_html__("Recommended Plugins", 'wp-dp');
			$wp_dp_static_text['wp_dp_directory_menu_documentation'] = esc_html__("Documentation", 'wp-dp');
                  
                        $wp_dp_static_text['wp_dp_inside_tab_content_hidden_val'] = esc_html__("1", 'wp-dp');
                        $wp_dp_cs_var_static_text['wp_dp_cs_var_current_page'] = esc_html__('Current Page', 'directorybox');
                        $wp_dp_cs_var_static_text['wp_dp_cs_var_home'] = esc_html__('Home', 'directorybox');
                        /*
			 * Reviews status
			 */
			$wp_dp_static_text['wp_dp_review_status_pending'] = esc_html__("Pending", 'wp-dp');
			$wp_dp_static_text['wp_dp_review_status_publish'] = esc_html__("Publish", 'wp-dp');
			$wp_dp_static_text['wp_dp_review_status_succeess'] = esc_html__("Status Changed", 'wp-dp');
			$wp_dp_static_text['wp_dp_review_status_error'] = esc_html__("Error Accured", 'wp-dp');
                        

			
            /*
             * Use this filter to add more strings from Add on.
             */
            $wp_dp_static_text = apply_filters('wp_dp_plugin_text_strings', $wp_dp_static_text);
			
			//var_dump($wp_dp_static_text);
			
            return $wp_dp_static_text;
        }

    }

	new wp_dp_plugin_all_strings_1;
}

if ( ! function_exists('wp_dp_plugin_text_srt') ) {

    function wp_dp_plugin_text_srt($str = '') {
        global $wp_dp_static_text;
		
        if ( isset($wp_dp_static_text[$str]) ) {
            return $wp_dp_static_text[$str];
        }
        return '';
    }

}