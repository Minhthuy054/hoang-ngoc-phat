<?php

/**
 * Static string 4
 */
if ( ! class_exists( 'wp_dp_plugin_all_strings_4' ) ) {

	class wp_dp_plugin_all_strings_4 {

		public function __construct() {

			add_filter( 'wp_dp_plugin_text_strings', array( $this, 'wp_dp_plugin_text_strings_callback' ), 3 );
		}

		public function wp_dp_plugin_text_strings_callback( $wp_dp_static_text ) {
			global $wp_dp_static_text;

			// New strings 1
			$wp_dp_static_text['wp_dp_list_meta_listing_video'] = esc_html__( 'Listing Video', 'wp-dp' );
			$wp_dp_static_text['wp_dp_class_noti_post_type_email_frequemcies'] = esc_html__( 'Email Frequencies', 'wp-dp' );
			$wp_dp_static_text['wp_dp_class_noti_post_type_keywords_search'] = esc_html__( 'Search Keywords', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_video_url'] = esc_html__( 'Video URL', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_page_style'] = esc_html__( 'Page Style', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_default_view'] = esc_html__( 'Default View', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_view_1'] = esc_html__( 'View 1', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_view_2'] = esc_html__( 'View 2', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_view_3'] = esc_html__( 'View 3', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_view_4'] = esc_html__( 'View 4', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_default_header'] = esc_html__( 'Default Sub Header Option', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_default_header_hint'] = esc_html__( 'The selected value will be the default option for subheader while the view 3 is selected.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_default'] = esc_html__( 'Default', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_map'] = esc_html__( 'Map', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_banner'] = esc_html__( 'Banner', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_sticky'] = esc_html__( 'Sticky Navigation', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_featured'] = esc_html__( 'Featured', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_top_category'] = esc_html__( 'Top Category', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_listing_recent_switch'] = esc_html__( 'Recent Listings', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_listing_recent_numbers'] = esc_html__( 'Number of Recent Listings', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_listing_recent_hint'] = esc_html__( 'Recent Listings shows in listing page when user set any search criteria and find no result then recent listings will be show after result.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_no_of_pictures'] = esc_html__( 'Number of Pictures', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_no_of_documents'] = esc_html__( 'Number of Documents', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_no_of_tags'] = esc_html__( 'Number of Tags', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_reviews'] = esc_html__( 'Reviews', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_phone_number'] = esc_html__( 'Phone Number', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_website_link'] = esc_html__( 'Website Link', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_social_reach'] = esc_html__( 'Social Impressions Reach', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_respond'] = esc_html__( 'Respond to Reviews', 'wp-dp' );
                        $wp_dp_static_text['wp_dp_review_flag_not_allowed'] = esc_html__( 'You are not allowed to flag your own review.', 'wp-dp' );
                        $wp_dp_static_text['wp_dp_review_helpfull_not_allowed'] = esc_html__( 'You are not allowed to helpfull your own review.', 'wp-dp' );
                        $wp_dp_static_text['wp_dp_list_meta_features_element'] = esc_html__( 'Features', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_video_element'] = esc_html__( 'Video', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_yelp_element'] = esc_html__( 'Yelp Places', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_appartment_element'] = esc_html__( 'Appartment For Sale', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_file_element'] = esc_html__( 'File Attachments', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_floor_plan_element'] = esc_html__( 'Floor Plan', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_listing_summary'] = esc_html__( 'Listing Summary', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_per_week'] = esc_html__( 'Per Week', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_per_cm'] = esc_html__( 'Per Calendar Month', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_per_month'] = esc_html__( 'Per Month', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_price_option'] = esc_html__( 'Add Price Options', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_none'] = esc_html__( 'None', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_on_call'] = esc_html__( 'On Call', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_price'] = esc_html__( 'Price', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_ad_price'] = esc_html__( 'Add Price', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_ad_price_label_hint'] = esc_html__( ' Add  price here ', 'wp-dp' );


			$wp_dp_static_text['wp_dp_list_meta_price_type'] = esc_html__( 'Price Type', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_step_2'] = esc_html__( 'What type of listing category are you marketing?', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_date'] = esc_html__( 'Date', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_time_fom'] = esc_html__( 'Time From', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_time_to'] = esc_html__( 'Time To', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_no_of_days'] = esc_html__( 'No off days added.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_off_days'] = esc_html__( 'Off Days', 'wp-dp' );
			$wp_dp_static_text['wp_dp_list_meta_off_days_label_link'] = esc_html__( 'Set off days click on "Off Days" button and choose the off days date from calendar.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_front_holidays_heading'] = esc_html__( 'Holidays', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_access_token'] = esc_html__( 'Get Access Token', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_page_sharing'] = esc_html__( 'Select Page for Sharing', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_yelp_places'] = esc_html__( 'Yelp Places', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_select_a_page'] = esc_html__( 'Please select a page', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_unchecked_show'] = esc_html__( 'Single Page Unchecked show', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_select_image'] = esc_html__( 'Select Image', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_select_map_image'] = esc_html__( 'Select Map Image', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_address'] = esc_html__( 'Address', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_latitude'] = esc_html__( 'Latitude', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_longitude'] = esc_html__( 'Longitude', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_location_on_map'] = esc_html__( 'Search This Location on Map', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_add_banner'] = esc_html__( 'Add Banner', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_import_options'] = esc_html__( 'Import Options', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_file_url'] = esc_html__( 'File URL', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_file_url_hint'] = esc_html__( 'Input the Url from another location and hit Import Button to apply settings.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_import'] = esc_html__( 'Import', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_export_options'] = esc_html__( 'Export Options', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_generated_files'] = esc_html__( 'Generated Files', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_generated_files_hint'] = esc_html__( 'Here you can Generate/Download Backups. Also you can use these Backups to Restore settings.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_restore'] = esc_html__( 'Restore', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_delete'] = esc_html__( 'Delete', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_generated_backup'] = esc_html__( 'Generate Backup', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_imp_exp_locations'] = esc_html__( 'Import/Export Locations', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_imp_exp_hint'] = esc_html__( 'Input the Url from another location and hit Import Button to import locations.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_import_locations'] = esc_html__( 'Import Locations', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_choose_file_to_import_locations'] = esc_html__( 'Import File', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_browse_file'] = esc_html__( 'Browse file', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_imp_exp_categories'] = esc_html__( 'Import/Export Listing Type Categories', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_imp_exp_cat_hint'] = esc_html__( 'Input the URL from another location and hit Import Button to import listing type categories.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_import_categories'] = esc_html__( 'Import Listing Type Categories', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_backup_hint'] = esc_html__( 'Here you can Generate/Download Backups. Also you can use these Backups to Restore Listing type categories.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_download'] = esc_html__( 'Download', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_warning'] = esc_html__( 'Warning!!!', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_modfiying_warning'] = esc_html__( 'By modifying location levels your existing locations data may get useless as you change levels. So, it is recommended to backup and delete existing locations.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_locations_levels'] = esc_html__( 'Locations Levels', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_country'] = esc_html__( 'Country', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_state'] = esc_html__( 'State', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_city'] = esc_html__( 'City', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_town'] = esc_html__( 'Town', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_town_complete_address'] = esc_html__( 'Complete Address', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_edit_levels'] = esc_html__( 'Edit Levels', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_locations_selector'] = esc_html__( "Location's Fields Selector", 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_selector_hint'] = esc_html__( 'Select which location parts(Country, State, City, Town) you want to use on frontend. You can select only from location parts those you have selected on backend.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_location_parts'] = esc_html__( 'Frontend Location Parts', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_location_parts_hint'] = esc_html__( 'Select which location parts(Country, State, City, Town) you want to use on frontend. You can select only from location parts those you have selected on backend.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_country_hint'] = esc_html__( 'Select a Country which you want to use in locations or select "All".', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_state_hint'] = esc_html__( 'Select a State which you want to use in locations or select "All".', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_city_hint'] = esc_html__( 'Select a City which you want to use in locations or select "All".', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_town_hint'] = esc_html__( 'Select a Town which you want to use in locations or select "All".', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_choose'] = esc_html__( 'Choose...', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_all'] = esc_html__( 'All', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_import_users'] = esc_html__( 'Import Users', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_label'] = esc_html__( 'Label', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_add_more'] = esc_html__( 'Add More', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_member_title'] = esc_html__( 'Member Title', 'wp-dp' );
			$wp_dp_static_text['wp_dp_options_member_value'] = esc_html__( 'Member Value', 'wp-dp' );
			$wp_dp_static_text['wp_dp_opt_func_option_saved'] = esc_html__( 'Plugin Options Saved.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_opt_func_error_saving'] = esc_html__( 'Error saving file!', 'wp-dp' );
			$wp_dp_static_text['wp_dp_opt_func_backup_generated'] = esc_html__( 'Backup Generated.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_opt_func_file_delete'] = esc_html__( "File '%s' Deleted Successfully", 'wp-dp' );
			$wp_dp_static_text['wp_dp_opt_func_error_delete'] = esc_html__( 'Error Deleting file!', 'wp-dp' );
			$wp_dp_static_text['wp_dp_opt_func_restore_file'] = esc_html__( "File '%s' Restore Successfully", 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_disable'] = esc_html__( 'User Registration is disabled', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_alreaady_logged'] = esc_html__( 'Already Loggedin', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_login_here'] = esc_html__( 'Login Here', 'wp-dp' );

			$wp_dp_static_text['wp_dp_register_signin_here'] = esc_html__( 'Log in', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_text_register'] = esc_html__( 'register', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_need_more_help'] = esc_html__( 'Need more Help?', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_login_with_social_string'] = esc_html__( 'You Can Login using your facebook, twitter Profile or Google account', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_contact_us_string'] = esc_html__( 'contact us', 'wp-dp' );

			$wp_dp_static_text['wp_dp_register_logout_first'] = esc_html__( 'Please logout first then try to login again', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_login_demo'] = esc_html__( 'Click to login with Demo User', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_login_buyer_rent'] = esc_html__( 'Buy Or Rent', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_login_sell_let_out'] = esc_html__( 'Sell Or Let out', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_member'] = esc_html__( 'Member', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_agency'] = esc_html__( 'Agency', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_username'] = esc_html__( 'Username', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_password'] = esc_html__( 'Password', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_login'] = esc_html__( 'Log in', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_forgot_password'] = esc_html__( 'Forgot Password?', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_company_name'] = esc_html__( 'Company Name', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_display_name'] = esc_html__( 'Display Name', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_email'] = esc_html__( 'Email', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_signup'] = esc_html__( 'Sign Up', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_register'] = esc_html__( 'Sign up', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_sorry'] = esc_html__( 'Sorry! ', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_sorry_text'] = esc_html__( ' does not shared your email, please provide a valid email address.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_packages_type'] = esc_html__( 'Type', 'wp-dp' );
			$wp_dp_static_text['wp_dp_packages_purchase_button'] = esc_html__( 'Package Purchase Buton', 'wp-dp' );
			$wp_dp_static_text['wp_dp_packages_buy_now'] = esc_html__( 'Buy Now', 'wp-dp' );
			$wp_dp_static_text['wp_dp_packages_recommended'] = esc_html__( 'Recommended', 'wp-dp' );
			$wp_dp_static_text['wp_dp_packages_login_first'] = esc_html__( 'You have to login for purchase listing.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_packages_become_member'] = esc_html__( 'Become a member to subscribe a Package.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_price_subscribe'] = esc_html__( 'Package Subscribe Action', 'wp-dp' );
			$wp_dp_static_text['wp_dp_price_order_detail'] = esc_html__( 'Order Detail', 'wp-dp' );
			$wp_dp_static_text['wp_dp_price_add_listing'] = esc_html__( 'Add Listing', 'wp-dp' );
			$wp_dp_static_text['wp_dp_price_name'] = esc_html__( 'Name', 'wp-dp' );
			$wp_dp_static_text['wp_dp_price_price'] = esc_html__( 'Price', 'wp-dp' );
			$wp_dp_static_text['wp_dp_price_color'] = esc_html__( 'Color', 'wp-dp' );
			$wp_dp_static_text['wp_dp_price_description'] = esc_html__( 'Description', 'wp-dp' );
			$wp_dp_static_text['wp_dp_price_button_text'] = esc_html__( 'Button Text', 'wp-dp' );
			$wp_dp_static_text['wp_dp_price_duration'] = esc_html__( 'Duration', 'wp-dp' );
			$wp_dp_static_text['wp_dp_price_featured'] = esc_html__( 'Featured', 'wp-dp' );
			$wp_dp_static_text['wp_dp_price_package'] = esc_html__( 'Package', 'wp-dp' );
			$wp_dp_static_text['wp_dp_price_select_package'] = esc_html__( 'Select Package', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_username_error'] = esc_html__( 'Username should not be empty.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_display_name_error'] = esc_html__( 'Display name should not be empty.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_password_error'] = esc_html__( 'Password should not be empty.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_not_activated'] = esc_html__( 'Your account is not activated yet.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_removed_from_company'] = esc_html__( 'Your profile has been removed from company', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_check_password'] = esc_html__( 'Please check your password.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_wrong_user_pass'] = esc_html__( 'Wrong username or password.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_login_successfully'] = esc_html__( 'Login successfully...', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_enter_valid_username'] = esc_html__( 'Please enter a valid username. You can only enter alphanumeric value and only ( _ ) longer than or equals 5 chars', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_select_type'] = esc_html__( 'Please select member type.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_email_error'] = esc_html__( 'Email Field should not be empty.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_valid_email'] = esc_html__( 'Please enter a valid email.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_type_error'] = esc_html__( 'Profile Type should not be empty.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_display_name_error'] = esc_html__( 'Display Name should not be empty.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_company_name_error'] = esc_html__( 'Company Name should not be empty.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_email_already_exists'] = esc_html__( 'Sorry! Email already exists.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_user_already_exists'] = esc_html__( 'User already exists. Please try another one.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_check_email'] = esc_html__( 'Please check your email for login details.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_went_wrong'] = esc_html__( 'Something went wrong, Email could not be processed.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_an_issue'] = esc_html__( 'Currently there is an issue', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_success'] = esc_html__( 'Your account has been registered successfully, Please contact to site admin for password.', 'wp-dp' );

			$wp_dp_static_text['wp_dp_member_add_members'] = esc_html__( 'Add Members', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_add_team_member'] = esc_html__( 'Add Team Member', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_send'] = esc_html__( 'Send', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_cancel'] = esc_html__( 'Cancel', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_no_any_member'] = esc_html__( "You don't have any team member", 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_valid_email'] = esc_html__( 'Please provide valid email address', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_email_exists'] = esc_html__( 'Email address already exists', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_valid_file'] = esc_html__( 'Please provide valid file for an image', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_member_added'] = esc_html__( 'Team member successfully added!', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_branch_updated'] = esc_html__( 'Branch successfully updated!', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_super_admin_removed'] = esc_html__( 'Super Admin Successfully Removed', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_branch_removed'] = esc_html__( 'Branch Successfully Removed', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_upload'] = esc_html__( 'Upload', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_loc_branch'] = esc_html__( 'Locations', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_save'] = esc_html__( 'Save', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_change_pass'] = esc_html__( 'Change Password', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_my_profile'] = esc_html__( 'My Profile', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_closed'] = esc_html__( 'Closed', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_dashboard'] = esc_html__( 'Member Dashboard', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_package_detail'] = esc_html__( 'Package Detail', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_no_of_suggestions'] = esc_html__( 'Please provide number of suggestions.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_atleast_category'] = esc_html__( 'Please select at least one category.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_setting_saved'] = esc_html__( 'Your settings successfully saved.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_listings'] = esc_html__( 'My Listings', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_date_range'] = esc_html__( 'Select Date Range', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_submit_ad'] = esc_html__( 'Submit Ad', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_ads'] = esc_html__( 'Ads', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_posted'] = esc_html__( 'Posted', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_expires'] = esc_html__( 'Expires', 'wp-dp' );
			$wp_dp_static_text['wp_dp_slider_image'] = esc_html__( 'slider image', 'wp-dp' );
			$wp_dp_static_text['wp_dp_slider_view_all_photos'] = esc_html__( 'View all photos', 'wp-dp' );
			$wp_dp_static_text['wp_dp_services_element'] = esc_html__( 'Service & Rates', 'wp-dp' );
			$wp_dp_static_text['wp_dp_opening_hours_at_opens_at'] = esc_html__( ': Opens at', 'wp-dp' );
			$wp_dp_static_text['wp_dp_opening_hours_opens_at'] = esc_html__( 'Opens at', 'wp-dp' );
			$wp_dp_static_text['wp_dp_opening_hours_closed'] = esc_html__( 'Closed', 'wp-dp' );
			$wp_dp_static_text['wp_dp_opening_hours_opening_timings'] = esc_html__( 'Opening Timings', 'wp-dp' );

			// Start Locations Manager.
			$wp_dp_static_text['wp_dp_locations_taxonomy_label'] = esc_html__( 'Locations', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_not_found'] = esc_html__( 'No locations found.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_search'] = esc_html__( 'Search Locations', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_popular'] = esc_html__( 'Popular Locations', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_all'] = esc_html__( 'All Locations', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_parent'] = esc_html__( 'Parent Location', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_edit'] = esc_html__( 'Edit Location', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_update'] = esc_html__( 'Update Location', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_add_new'] = esc_html__( 'Add New Location', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_new_name'] = esc_html__( 'New Location Name', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_with_commas'] = esc_html__( 'Separate Locations with commas', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_add_remove'] = esc_html__( 'Add or Remove Locations', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_choose_from_most_used'] = esc_html__( 'Choose from the most used Locations', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_add'] = esc_html__( 'Add Location', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_select_area'] = esc_html__( 'Use me to select an area for a location.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_draw_polygon'] = esc_html__( 'Draw Polygon', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_search_placeholder'] = esc_html__( 'Search...', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_returned_place'] = esc_html__( 'Returned place contains no geometry', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_geocode_success'] = esc_html__( 'Geocode was not successful for the following reason', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_name'] = esc_html__( 'Name', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_type'] = esc_html__( 'Type', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_slug'] = esc_html__( 'Slug', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_posts'] = esc_html__( 'Posts', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_iso_code'] = esc_html__( 'ISO Code', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_coordinates'] = esc_html__( 'Location Coordinates', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_authentication_failed'] = esc_html__( 'Authentication Failed.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_invalid_data'] = esc_html__( 'Invalid Data.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_error_saving'] = esc_html__( 'Error saving file!', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_backup_generated'] = esc_html__( 'Backup Generated.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_file_deleted'] = esc_html__( "File '%s' Deleted Successfully", 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_error_deleting_file'] = esc_html__( 'Error Deleting file!', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_file_import'] = esc_html__( "File '%s' Restored Successfully", 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_error_restoring'] = esc_html__( 'Error Restoring file!', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_no_location_found'] = esc_html__( 'No location found', 'wp-dp' );
			$wp_dp_static_text['wp_dp_locations_taxonomy_imported_successfully'] = esc_html__( 'File Imported Successfully', 'wp-dp' );

			// End Locations Manager.
			// Start Google Captcha.
			$wp_dp_static_text['wp_dp_google_captcha_reload'] = esc_html__( 'Reload', 'wp-dp' );
			$wp_dp_static_text['wp_dp_google_captcha_provide_captcha_api_key'] = esc_html__( 'Please provide google captcha API keys', 'wp-dp' );
			$wp_dp_static_text['wp_dp_google_captcha_select_field'] = esc_html__( 'Please Select Captcha Field', 'wp-dp' );

			// End Google Captcha.
			// Start Image Cropper.
			$wp_dp_static_text['wp_dp_image_cropper_error_return_code'] = esc_html__( 'ERROR Return Code', 'wp-dp' );
			$wp_dp_static_text['wp_dp_image_cropper_type_not_supported'] = esc_html__( 'image type not supported', 'wp-dp' );
			$wp_dp_static_text['wp_dp_image_cropper_can_not_write_file'] = esc_html__( 'Can`t write cropped File', 'wp-dp' );

			// End Image Cropper.
			// Start Pagination.
			$wp_dp_static_text['wp_dp_pagination_prev'] = esc_html__( 'Prev', 'wp-dp' );
			$wp_dp_static_text['wp_dp_pagination_next'] = esc_html__( 'Next', 'wp-dp' );

			// End Pagination.
			// Start Search Fields.
			$wp_dp_static_text['wp_dp_search_fields_sub_categories'] = esc_html__( 'Sub Categories', 'wp-dp' );
			$wp_dp_static_text['wp_dp_search_fields_other_features'] = esc_html__( 'Other Features', 'wp-dp' );
			$wp_dp_static_text['wp_dp_search_fields_price_type_all'] = esc_html__( 'All', 'wp-dp' );
			$wp_dp_static_text['wp_dp_search_fields_price_type_per_week'] = esc_html__( 'Per Week', 'wp-dp' );
			$wp_dp_static_text['wp_dp_search_fields_price_type_per_month'] = esc_html__( 'Per Calendar Month', 'wp-dp' );
			$wp_dp_static_text['wp_dp_search_fields_date_from'] = esc_html__( 'From', 'wp-dp' );
			$wp_dp_static_text['wp_dp_search_fields_date_to'] = esc_html__( 'To', 'wp-dp' );

			// End Search Fields.
			// Start Social Sharing.
			$wp_dp_static_text['wp_dp_social_sharing_facebook'] = esc_html__( 'facebook', 'wp-dp' );
			$wp_dp_static_text['wp_dp_social_sharing_twitter'] = esc_html__( 'twitter', 'wp-dp' );
			$wp_dp_static_text['wp_dp_social_sharing_google_plus'] = esc_html__( 'google', 'wp-dp' );
			$wp_dp_static_text['wp_dp_social_sharing_tumbler'] = esc_html__( 'tumbler', 'wp-dp' );
			$wp_dp_static_text['wp_dp_social_sharing_dribble'] = esc_html__( 'dribble', 'wp-dp' );
			$wp_dp_static_text['wp_dp_social_sharing_stumbleupon'] = esc_html__( 'stumbleupon', 'wp-dp' );

			// End Social Sharing.
			// Start Frontend Attachments Elements.
			$wp_dp_static_text['wp_dp_attachments_files'] = esc_html__( 'Files attachments', 'wp-dp' );
			$wp_dp_static_text['wp_dp_attachments_downloads'] = esc_html__( 'Download', 'wp-dp' );

			// End Frontend Attachments Elements.
			// Start Frontend Author Info Elements.
			$wp_dp_static_text['wp_dp_author_info_request_details'] = esc_html__( 'Request Details', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_view_profile'] = esc_html__( 'View Profile', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_close'] = esc_html__( 'Close', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_send_msg'] = esc_html__( 'Send Message', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_sender_name'] = esc_html__( 'Name *', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_sender_email'] = esc_html__( 'Email *', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_sender_message'] = esc_html__( 'Message *', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_member_message_received'] = esc_html__( 'Member Message Received', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_member_email_invalid_empty'] = esc_html__( 'Member Email is invalid or empty', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_sender_name_empty'] = esc_html__( 'Name should not be empty', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_sender_email_empty'] = esc_html__( 'Email should not be empty', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_sender_email_invalid'] = esc_html__( 'Please enter a valid email address', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_sender_message_empty'] = esc_html__( 'Message should not be empty', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_sender_email_name'] = esc_html__( 'Name', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_sender_email_email'] = esc_html__( 'Email', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_sender_email_msg'] = esc_html__( 'Message', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_sender_email_ip_address'] = esc_html__( 'IP Address', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_sender_email_sent_success'] = esc_html__( 'Email sent successfully.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_author_info_sender_email_not_sent'] = esc_html__( 'Message Not sent.', 'wp-dp' );

			// End Frontend Author Info Elements.
			// Start Frontend Contact Elements.
			$wp_dp_static_text['wp_dp_contact_heading'] = esc_html__( 'Contact', 'wp-dp' );
			$wp_dp_static_text['wp_dp_contact_details'] = esc_html__( 'Contact Details', 'wp-dp' );
			$wp_dp_static_text['wp_dp_contact_send_enquiry'] = esc_html__( 'Send Message By Email', 'wp-dp' );
			$wp_dp_static_text['wp_dp_contact_close'] = esc_html__( 'Close', 'wp-dp' );
			$wp_dp_static_text['wp_dp_contact_enter_name'] = esc_html__( 'Enter Your Name *', 'wp-dp' );
			$wp_dp_static_text['wp_dp_contact_enter_email'] = esc_html__( 'Enter Your Email Address *', 'wp-dp' );
			$wp_dp_static_text['wp_dp_contact_enter_message'] = esc_html__( 'Message *', 'wp-dp' );
			$wp_dp_static_text['wp_dp_contact_send_message'] = esc_html__( 'Send message', 'wp-dp' );

			// End Frontend Contact Elements.
			// Start Frontend Discussion Elements.
			$wp_dp_static_text['wp_dp_discussion_login_post_comment'] = esc_html__( 'You must be login to post comment.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_order'] = esc_html__( 'order', 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_enquiry'] = esc_html__( 'enquiry', 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_enter_message'] = esc_html__( 'Please enter message.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_not_send_discussion_closed'] = esc_html__( "You can't send message because your enquiry has been closed.", 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_not_send_discussion_permission'] = esc_html__( "You can't send message due to member permission.", 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_sent_you_message'] = esc_html__( 'sent you a message on enquiry', 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_sent_successfully'] = esc_html__( 'Your message has been sent successfully.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_not_send_discussion_against'] = esc_html__( "You can't send message against this enquiry.", 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_duplicate_message'] = esc_html__( 'Duplicate message detected; it looks as though you&#8217;ve already said that!', 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_message'] = esc_html__( '%s Message', 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_messages'] = esc_html__( '%s Messages', 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_message_me'] = esc_html__( 'Me', 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_message_ago'] = esc_html__( 'ago', 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_message_unread'] = esc_html__( 'The enquiry has been marked as unread.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_message_read'] = esc_html__( 'The enquiry has been marked as read.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_message_status_changed'] = esc_html__( 'Message status has been changed.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_message_status_not_changed'] = esc_html__( '%s status not changed.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_discussion_message_status_closed'] = esc_html__( 'Your enquiry has been closed.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquiry_name_empty'] = esc_html__( 'Name should not be empty', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquiry_phone_empty'] = esc_html__( 'Phone number should not be empty', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquiry_email_empty'] = esc_html__( 'Email address should not be empty', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquiry_email_not_valid'] = esc_html__( 'Email address is not valid', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquiry_msg_title_empty'] = esc_html__( 'Ttitle should not be empty', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquiry_msg_empty'] = esc_html__( 'Message should not be empty', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquiry_own_listing_error'] = esc_html__( "You can't send enquiry on your own listing.", 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquiry_sent_successfully'] = esc_html__( 'Your enquiry has been sent successfully.', 'wp-dp' );

			// End Frontend Discussion Elements.
			// Start Frontend Enquires Arrange Buttons Elements.
			$wp_dp_static_text['wp_dp_enquire_arrange_login'] = esc_html__( 'You should be login for submit inquery,please login first then try again', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_full_name_empty'] = esc_html__( 'Full name should not be empty', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_phone_empty'] = esc_html__( 'Phone number should not be empty', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_phone_not_valid'] = esc_html__( 'Please enter a valid phone number.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_email_empty'] = esc_html__( 'Email address should not be empty', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_email_not_valid'] = esc_html__( 'Please enter a valid  email address.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_message_sent_successfully'] = esc_html__( 'Your message has been sent successfully.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_enquiry_now'] = esc_html__( 'Message now', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_arrange_viewing'] = esc_html__( 'Arrange viewing', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_request_inquiry'] = esc_html__( 'Request Message', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_send_copy'] = esc_html__( 'Send a copy to my email address', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_viewing_times_descriptione'] = esc_html__( 'Physical Arrange viewings is always been attractive to listing clients. Just fill out the form to arrange visualizations around our listings.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_viewing_schedule'] = esc_html__( 'Select Schedule', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_calendar_month_jan'] = esc_html__( 'Jan', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_calendar_month_feb'] = esc_html__( 'Feb', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_calendar_month_mar'] = esc_html__( 'Mar', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_calendar_month_apr'] = esc_html__( 'Apr', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_calendar_month_may'] = esc_html__( 'May', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_calendar_month_jun'] = esc_html__( 'Jun', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_calendar_month_jul'] = esc_html__( 'Jul', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_calendar_month_aug'] = esc_html__( 'Aug', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_calendar_month_sep'] = esc_html__( 'Sep', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_calendar_month_oct'] = esc_html__( 'Oct', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_calendar_month_nov'] = esc_html__( 'Nov', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_calendar_month_dec'] = esc_html__( 'Dec', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_viewing_full_name'] = esc_html__( 'Full Name *', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_viewing_phone_number'] = esc_html__( 'Phone Number *', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_viewing_email_address'] = esc_html__( 'Email Address *', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_viewing_message'] = esc_html__( 'Message', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_viewing_receive_information'] = esc_html__( 'I wish to receive information from WP Directorybox Manager or selected partners', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_viewing_accept_term'] = esc_html__( 'By submitting this form, you accept our', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_viewing_accept_term_of_use'] = esc_html__( 'Terms of Use', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_viewing_accept_term_and'] = esc_html__( 'and', 'wp-dp' );
			$wp_dp_static_text['wp_dp_enquire_arrange_viewing_privacy_policy'] = esc_html__( 'Privacy Policy.', 'wp-dp' );
// End Frontend Enquires Arrange Buttons Elements.
// Start Frontend Features Elements.
			$wp_dp_static_text['wp_dp_features_apartment_for_sale'] = esc_html__( 'Apartment for Sale', 'wp-dp' );
			$wp_dp_static_text['wp_dp_features_plot'] = esc_html__( 'Plot', 'wp-dp' );
			$wp_dp_static_text['wp_dp_features_beds'] = esc_html__( 'Beds', 'wp-dp' );
			$wp_dp_static_text['wp_dp_features_price_from'] = esc_html__( 'Price From', 'wp-dp' );
			$wp_dp_static_text['wp_dp_features_floor'] = esc_html__( 'Floor', 'wp-dp' );
			$wp_dp_static_text['wp_dp_features_building_address'] = esc_html__( 'Building / Address', 'wp-dp' );
			$wp_dp_static_text['wp_dp_features_availability'] = esc_html__( 'Availability', 'wp-dp' );
			$wp_dp_static_text['wp_dp_features_available'] = esc_html__( 'Available', 'wp-dp' );
			$wp_dp_static_text['wp_dp_features_not_available'] = esc_html__( 'Not Available', 'wp-dp' );
			$wp_dp_static_text['wp_dp_features_view'] = esc_html__( 'view', 'wp-dp' );
			$wp_dp_static_text['wp_dp_features_listing_video'] = esc_html__( 'Listing Video', 'wp-dp' );
			$wp_dp_static_text['wp_dp_features_listing_features'] = esc_html__( 'Listing Features', 'wp-dp' );
			$wp_dp_static_text['wp_dp_features_what_near_by'] = esc_html__( 'What is Near by', 'wp-dp' );
			$wp_dp_static_text['wp_dp_features_unable_find_distance'] = esc_html__( 'Unable to find the distance', 'wp-dp' );
			$wp_dp_static_text['wp_dp_features_miles_away'] = esc_html__( 'miles away', 'wp-dp' );
// End Frontend Features Elements.
// Start Frontend Gallery Elements.
			$wp_dp_static_text['wp_dp_gallery_all_photos'] = esc_html__( 'View all photos', 'wp-dp' );
// Start Frontend Nearby Listings Elements.
			$wp_dp_static_text['wp_dp_similar_listings_heading'] = esc_html__( 'Similar Listings', 'wp-dp' );
			$wp_dp_static_text['wp_dp_nearby_listings_price_on_request'] = esc_html__( 'Price On Request', 'wp-dp' );
// Start Frontend Opening Hours Elements.
			$wp_dp_static_text['wp_dp_opening_hours_open'] = esc_html__( 'Open', 'wp-dp' );
			$wp_dp_static_text['wp_dp_opening_hours_today_closed'] = esc_html__( 'Today : Closed', 'wp-dp' );
			$wp_dp_static_text['wp_dp_opening_hours_today'] = esc_html__( 'Today', 'wp-dp' );
// Start Frontend Payment Calculator Elements.
			$wp_dp_static_text['wp_dp_payment_calculator_heading'] = esc_html__( 'Mortgage Payment Calculator', 'wp-dp' );
			$wp_dp_static_text['wp_dp_payment_calculator_your_payment'] = esc_html__( 'Your payment', 'wp-dp' );
			$wp_dp_static_text['wp_dp_payment_calculator_your_price'] = esc_html__( 'Your price', 'wp-dp' );
			$wp_dp_static_text['wp_dp_payment_calculator_your_deposit'] = esc_html__( 'Your deposit', 'wp-dp' );
			$wp_dp_static_text['wp_dp_payment_calculator_your_interest'] = esc_html__( 'Your interest', 'wp-dp' );
			$wp_dp_static_text['wp_dp_payment_calculator_mo'] = esc_html__( 'MO', 'wp-dp' );
			$wp_dp_static_text['wp_dp_payment_calculator_listing_price'] = esc_html__( 'Listing price:', 'wp-dp' );
			$wp_dp_static_text['wp_dp_payment_calculator_deposit'] = esc_html__( 'Deposit:', 'wp-dp' );
			$wp_dp_static_text['wp_dp_payment_calculator_annual_interest'] = esc_html__( 'Annual Interest:', 'wp-dp' );
			$wp_dp_static_text['wp_dp_payment_calculator_year'] = esc_html__( 'Year:', 'wp-dp' );
			$wp_dp_static_text['wp_dp_payment_calculator_get_loan_btn'] = esc_html__( 'Get Loan Quote', 'wp-dp' );
			$wp_dp_static_text['wp_dp_payment_calculator_description'] = esc_html__( 'Description', 'wp-dp' );
			$wp_dp_static_text['wp_dp_payment_calculator_description_hint'] = esc_html__( 'Please add text shows at bottom of the mortgage calculator.', 'wp-dp' );

// Start Frontend Reservation Elements.
			$wp_dp_static_text['wp_dp_reservaion_reserve_btn_label'] = esc_html__( 'Reserve My Spot', 'wp-dp' );
			$wp_dp_static_text['wp_dp_reservaion_select_servoces'] = esc_html__( '- Select Services -', 'wp-dp' );
			$wp_dp_static_text['wp_dp_reservaion_choose_file'] = esc_html__( 'Choose file', 'wp-dp' );
			$wp_dp_static_text['wp_dp_reservaion_file_validation'] = esc_html__( 'Suitable files are .doc, docx, rft, pdf & .pdf', 'wp-dp' );
			$wp_dp_static_text['wp_dp_reservaion_available'] = esc_html__( 'Available', 'wp-dp' );
			$wp_dp_static_text['wp_dp_reservaion_unavailable'] = esc_html__( 'Unavailable', 'wp-dp' );
			$wp_dp_static_text['wp_dp_reservaion_booked'] = esc_html__( 'Booked', 'wp-dp' );
			$wp_dp_static_text['wp_dp_reservaion_am'] = esc_html__( 'AM', 'wp-dp' );
			$wp_dp_static_text['wp_dp_reservaion_pm'] = esc_html__( 'PM', 'wp-dp' );
			$wp_dp_static_text['wp_dp_reservaion_per_night'] = esc_html__( ' / Per Night', 'wp-dp' );
			$wp_dp_static_text['wp_dp_reservaion_service_validation'] = esc_html__( 'Please Selecct a service.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_reservaion_all_fields_validation'] = esc_html__( 'Please fill all required fields.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_reservaion_email_validation'] = esc_html__( 'Please enter a valid email address.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_reservaion_not_send_own_listing'] = esc_html__( " Sorry! You can't send %s on your own listing.", 'wp-dp' );
			$wp_dp_static_text['wp_dp_reservaion_submitted_reservation_on_ad'] = esc_html__( 'submitted a reservation form on your Ad', 'wp-dp' );
			$wp_dp_static_text['wp_dp_reservaion_sent_successfully'] = esc_html__( 'Your %s has been sent successfully.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_reservaion_smoething_went_wrong'] = esc_html__( 'Something went wrong, %s could not be processed.', 'wp-dp' );
// End Frontend Reservation Elements.
// Start Frontend Sub Navbar Elements.
			$wp_dp_static_text['wp_dp_subnav_item_1'] = esc_html__( 'Listing Detail', 'wp-dp' );
			$wp_dp_static_text['wp_dp_subnav_item_2'] = esc_html__( 'Features', 'wp-dp' );
			$wp_dp_static_text['wp_dp_subnav_item_3'] = esc_html__( 'Video', 'wp-dp' );
			$wp_dp_static_text['wp_dp_subnav_item_4'] = esc_html__( 'Yelp Places', 'wp-dp' );
			$wp_dp_static_text['wp_dp_subnav_item_5'] = esc_html__( 'Apartments', 'wp-dp' );
			$wp_dp_static_text['wp_dp_subnav_item_6'] = esc_html__( 'Attachments', 'wp-dp' );
			$wp_dp_static_text['wp_dp_subnav_item_7'] = esc_html__( 'Floor Plan', 'wp-dp' );
// End Frontend Sub Navbar Elements.
// Start Frontend Yelp Places Elements.
			$wp_dp_static_text['wp_dp_yelp_places_food'] = esc_html__( 'Food', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_nightlife'] = esc_html__( 'Nightlife', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_restaurants'] = esc_html__( 'Restaurants', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_shopping'] = esc_html__( 'Shopping', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_active_life'] = esc_html__( 'Active Life', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_arts_entertainment'] = esc_html__( 'Arts & Entertainment', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_automotive'] = esc_html__( 'Automotive', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_beauty_spas'] = esc_html__( 'Beauty & Spas', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_education'] = esc_html__( 'Education', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_event_planning_services'] = esc_html__( 'Event Planning & Services', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_health_medical'] = esc_html__( 'Health & Medical', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_home_services'] = esc_html__( 'Home Services', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_local_services'] = esc_html__( 'Local Services', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_financial_services'] = esc_html__( 'Financial Services', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_hotels_travel'] = esc_html__( 'Hotels & Travel', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_local_flavor'] = esc_html__( 'Local Flavor', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_mass_media'] = esc_html__( 'Mass Media', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_pets'] = esc_html__( 'Pets', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_professional_services'] = esc_html__( 'Professional Services', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_public_services_govt'] = esc_html__( 'Public Services & Government', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_real_estate'] = esc_html__( 'Directory Box', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_religious_organizations'] = esc_html__( 'Religious Organizations', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_nearby'] = esc_html__( 'Yelp Places Nearby', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_category'] = esc_html__( 'Category:', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_reviews'] = esc_html__( '%s Reviews', 'wp-dp' );
			$wp_dp_static_text['wp_dp_yelp_places_'] = esc_html__( 'Company', 'wp-dp' );
// End Frontend Yelp Places Elements.
// Start Frontend Members.
			$wp_dp_static_text['wp_dp_member_overview'] = esc_html__( 'Overview', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_office_branches'] = esc_html__( 'Office/Branches', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_staff'] = esc_html__( 'Staff', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_find_on_map'] = esc_html__( 'Find on Map', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_contact_email'] = esc_html__( 'Contact Email', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_listings'] = esc_html__( 'Listings', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_listed_on'] = esc_html__( 'Listed on %uth %s %u by ', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_contact_your_name'] = esc_html__( 'Your Name *', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_contact_your_email'] = esc_html__( 'Your Email Address *', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_contact_your_message_title'] = esc_html__( 'Title *', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_contact_your_message'] = esc_html__( 'Message *', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_featured_listing'] = esc_html__( 'Featured Listing', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_sorry'] = esc_html__( 'Sorry !', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_no_results'] = esc_html__( 'No member match your search criteria.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_find_real_members'] = esc_html__( 'Find Members', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_search_enter_name'] = esc_html__( 'Member name', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_listings2'] = esc_html__( 'Listing(s)', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_branchs'] = esc_html__( 'Branch(s)', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_members_found'] = esc_html__( 'Member(s) Found', 'wp-dp' );
			$wp_dp_static_text['wp_dp_oops_nothing_found'] = esc_html__( 'Oops, nothing found!', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_members_default_order'] = esc_html__( 'Default Order', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_members_recent'] = esc_html__( 'Recent', 'wp-dp' );
                        
                        $wp_dp_static_text['wp_dp_member_notift_created_date'] = esc_html__( 'Created Date', 'wp-dp' );
                        
                        
			$wp_dp_static_text['wp_dp_member_members_alphabetical'] = esc_html__( 'Alphabetical', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_members_no_of_listings'] = esc_html__( 'No of Listings', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_members_featured'] = esc_html__( 'Featured', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_members_trusted_agencies'] = esc_html__( 'Trusted Members', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_members_with_colan'] = esc_html__( 'Member: %s', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_members_'] = esc_html__( 'Default', 'wp-dp' );

// End Frontend Members.
// Start Frontend Shortcode Map Search.
			$wp_dp_static_text['wp_dp_map_search_categories_txt'] = esc_html__( 'Categories', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_draw_on_map'] = esc_html__( 'Draw on Map', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_delete_area'] = esc_html__( 'Clear Area', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_cancel_drawing'] = esc_html__( 'Cancel', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_geo_location'] = esc_html__( 'Geo Location', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_map_unlock'] = esc_html__( 'Map UnLock', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_map_lock'] = esc_html__( 'Map Lock', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_records_found'] = esc_html__( 'Records found', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_showing'] = esc_html__( 'Showing', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_results'] = esc_html__( 'results', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_what_looking'] = esc_html__( 'What are you looking for?', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_'] = esc_html__( 'Company', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_geoloc_timeout'] = esc_html__( 'Browser geolocation error. Timeout.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_geoloc_not_support'] = esc_html__( 'Geolocation is not supported by this browser.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_geoloc_unavailable'] = esc_html__( 'Browser geolocation error. Position unavailable.', 'wp-dp' );
// End Frontend Shortcode Map Search.
// Start Login Form.
			$wp_dp_static_text['wp_dp_element_title'] = esc_html__( 'Element Title', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_title_hint'] = esc_html__( 'Enter element title here', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_sub_title'] = esc_html__( 'Element Sub Title', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_sub_title_hint'] = esc_html__( 'Enter element sub title here', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view'] = esc_html__( 'View', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_hint'] = esc_html__( 'Please select element view from this dropdown', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_simple'] = esc_html__( 'Simple', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_classic'] = esc_html__( 'Classic', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_advance'] = esc_html__( 'Advance', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_fancy'] = esc_html__( 'Fancy', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_fancy_v2'] = esc_html__( 'Fancy v2', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_fancy_v3'] = esc_html__( 'Fancy v3', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_fancy_v4'] = esc_html__( 'Fancy v4', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_list'] = esc_html__( 'List', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_list_modern'] = esc_html__( 'List Modern', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_list_classic'] = esc_html__( 'List Classic', 'wp-dp' );

			$wp_dp_static_text['wp_dp_element_view_modernnn'] = esc_html__( 'Modern', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_gid_modern'] = esc_html__( 'Grid Modern', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_gid_classic'] = esc_html__( 'Grid Classic', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_gid_default'] = esc_html__( 'Grid Default', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_gid_masnory'] = esc_html__( 'Masnory', 'wp-dp' );

			$wp_dp_static_text['wp_dp_element_view_grid'] = esc_html__( 'Grid', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_single_listing'] = esc_html__( 'Single Listing Slider', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_multiple_listings'] = esc_html__( 'Multiple Listings', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_view_grid'] = esc_html__( 'Grid', 'wp-dp' );
			$wp_dp_static_text['wp_dp_element_see_more'] = esc_html__( 'See more', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register'] = esc_html__( 'Register', 'wp-dp' );
			$wp_dp_static_text['wp_dp_insert'] = esc_html__( 'Insert', 'wp-dp' );
			$wp_dp_static_text['wp_dp_save'] = esc_html__( 'Save', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_with_demo_user'] = esc_html__( 'Please login with demo user.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_facebook_credentials'] = esc_html__( 'Contact site admin to provide a valid Facebook connect credentials.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_twitter_credentials'] = esc_html__( 'Contact site admin to provide a valid Twitter credentials.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_google_credentials'] = esc_html__( 'Contact site admin to provide a valid Google credentials.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_login_facebook_or_google'] = esc_html__( 'You Can Login using your facebook Profile or Google account', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_set_api_key'] = esc_html__( 'Please set API key', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_connect_with_facebook'] = esc_html__( 'Log in with Facebook', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_connect_with_twitter'] = esc_html__( 'Log in with Twitter', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_connect_with_google'] = esc_html__( 'Log in with Google', 'wp-dp' );

			$wp_dp_static_text['wp_dp_login_form_dont_have_account'] = esc_html__( "Don't have an account ? ", 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_already_have_account'] = esc_html__( 'Already have an account ?', 'wp-dp' );

			$wp_dp_static_text['wp_dp_login_form_enter_email_address'] = esc_html__( 'Enter Email address.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_invalid_email_address'] = esc_html__( 'Invalid e-mail address.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_no_user_registered_with_email'] = esc_html__( 'There is no user registered with that email address.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_check_email_for_new_pass'] = esc_html__( 'Check your email address for you new password.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_wrong_updating_account'] = esc_html__( 'Oops something went wrong updating your account.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_forgot_pass'] = esc_html__( 'Forgot Password', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_login_your_account'] = esc_html__( 'Login To Your Account', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_send_email'] = esc_html__( 'Send Email', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_register_options'] = esc_html__( 'DB: Register Options', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_form_'] = esc_html__( 'Save', 'wp-dp' );
// End Login Form.
// Start Shortcode Login Form.
			$wp_dp_static_text['wp_dp_login_register_disabled'] = esc_html__( 'User Registration is disabled', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_register_sign_in'] = esc_html__( 'Login', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_register_already_have_account'] = esc_html__( 'Already have an account?', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_register_reset_your_password'] = esc_html__( 'Reset your password', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_register_need_more_help'] = esc_html__( 'Need more Help?', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_register_can_login'] = esc_html__( 'Enter the email address to reset your password we will send you link for confirmation at your email.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_register_dpember_me'] = esc_html__( 'Remember me', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_register_confirm_below_info_for_register'] = esc_html__( 'Please confirm the below information on form and submit for the registration.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_register_want_to'] = esc_html__( 'I want to', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_register_user_role_type'] = esc_html__( 'user role type', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_register_add_new_add'] = esc_html__( 'Add New Ad', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_register_dashboard'] = esc_html__( 'Dashboard', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_register_orders'] = esc_html__( 'Orders', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_register_enquiries'] = esc_html__( 'Messages', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_register_alerts_searches'] = esc_html__( 'Email Alerts', 'wp-dp' );
                        $wp_dp_static_text['wp_dp_login_register_alerts_searches_detail'] = esc_html__( 'Email Alerts Detail', 'wp-dp' );
                        
			$wp_dp_static_text['wp_dp_login_register_favourite_listings'] = esc_html__( 'Favourite Listings', 'wp-dp' );
			$wp_dp_static_text['wp_dp_login_register_sign_out'] = esc_html__( 'Sign out', 'wp-dp' );
// End Shortcode Login Form.
// Start Shortcodes.
			$wp_dp_static_text['wp_dp_shortcodes_typography'] = esc_html__( 'Typography', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcodes_common_elements'] = esc_html__( 'Common Elements', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcodes_media_element'] = esc_html__( 'Media Element', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcodes_content_blocks'] = esc_html__( 'Content Blocks', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcodes_loops'] = esc_html__( 'Loops', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcodes_add_element'] = esc_html__( 'Add Element', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcodes_filter_by'] = esc_html__( 'Filter by', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcodes_show_all'] = esc_html__( 'Show all', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcodes_insert_shortcode'] = esc_html__( 'WP Directorybox Manager: Insert shortcode', 'wp-dp' );
// End Shortcodes.
// Start Shortcodes Add Listing.
			$wp_dp_static_text['wp_dp_title_align'] = esc_html__( 'Title Align', 'wp-dp' );
			$wp_dp_static_text['wp_dp_title_align_hint'] = esc_html__( 'Select Title Alignment.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_align_left'] = esc_html__( 'Left', 'wp-dp' );
			$wp_dp_static_text['wp_dp_align_right'] = esc_html__( 'Right', 'wp-dp' );
			$wp_dp_static_text['wp_dp_align_center'] = esc_html__( 'Center', 'wp-dp' );
			$wp_dp_static_text['wp_dp_add_listings'] = esc_html__( 'Add Listings', 'wp-dp' );
			$wp_dp_static_text['wp_dp_add_listing_options'] = esc_html__( 'Add Listings Options', 'wp-dp' );
// End Shortcodes Add Listing.
// Start Shortcode Register.
			$wp_dp_static_text['wp_dp_shortcode_register_options'] = esc_html__( 'DB: Register Options', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_register_heading'] = esc_html__( 'DB: Register', 'wp-dp' );
// End Shortcodes Register.
// Start Shortcode Members.
			$wp_dp_static_text['wp_dp_shortcode_members_options'] = esc_html__( 'DB: Members Options', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_members_heading'] = esc_html__( 'DB: Members', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_members_title'] = esc_html__( 'Members Title', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_members_sub_title'] = esc_html__( 'Members Sub Title', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_members_agencies'] = esc_html__( 'Agencies', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_members_view'] = esc_html__( 'View', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_members_alphabatic'] = esc_html__( 'Alphabatic', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_members_grid'] = esc_html__( 'Grid', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_members_list'] = esc_html__( 'List', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_members_top_filters'] = esc_html__( 'Filters', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_members_featured_only'] = esc_html__( 'Featured Only', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_members_sort_by'] = esc_html__( 'Sort By', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_members_excerpt_length'] = esc_html__( 'Excerpt length', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_members_pagination'] = esc_html__( 'Pagination', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_members_posts_per_page'] = esc_html__( 'Posts Per Page', 'wp-dp' );

			// End Shortcode Members.
			// Start Shortcode Pricing Table.
			$wp_dp_static_text['wp_dp_pricing_table_options'] = esc_html__( 'DB: Pricing Plan Options', 'wp-dp' );
			$wp_dp_static_text['wp_dp_pricing_table_heading'] = esc_html__( 'DB: Pricing Plan', 'wp-dp' );
			$wp_dp_static_text['wp_dp_pricing_table_tables'] = esc_html__( 'Pricing Tables', 'wp-dp' );
			$wp_dp_static_text['wp_dp_pricing_table_tables_hint'] = esc_html__( 'Select pricing tables from this dropdown', 'wp-dp' );
			$wp_dp_static_text['wp_dp_pricing_table_'] = esc_html__( 'Save', 'wp-dp' );

			// End Shortcode Pricing Table.
			// Start Shortcode Map Search.
			$wp_dp_static_text['wp_dp_map_search_heading'] = esc_html__( 'DB: Map Search', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_options'] = esc_html__( 'DB: Map Search Options', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_listing_type'] = esc_html__( 'Listing Type', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_map_on'] = esc_html__( 'Please select map on/off', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_map_height'] = esc_html__( 'Map Height (px)', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_map_height_hint'] = esc_html__( 'Enter map height here in (px)', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_map_latitude'] = esc_html__( 'Map Latitude', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_map_latitude_hint'] = esc_html__( 'Enter map latitude here', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_map_longitude'] = esc_html__( 'Map Longitude', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_map_longitude_hint'] = esc_html__( 'Enter map longitude here', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_map_zoom'] = esc_html__( 'Map Zoom', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_map_zoom_hint'] = esc_html__( 'Enter map zoom here', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_box'] = esc_html__( 'Search Box', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_box_hint'] = esc_html__( 'Search box on/off', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_keyword'] = esc_html__( 'Search Keyword Field', 'wp-dp' );
			$wp_dp_static_text['wp_dp_pro_search_keyword_criteria'] = esc_html__( 'Search Criteria Sidebar', 'wp-dp' );
			$wp_dp_static_text['wp_dp_pro_search_keyword_criteria_hint'] = esc_html__( 'Choose yes/no option for view the search criteria results.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_keyword_hint'] = esc_html__( 'Show/hide search by keyword field', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_listing_type_hint'] = esc_html__( 'Select listing type field enable/disable', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_location'] = esc_html__( 'Listing Location', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_location_hint'] = esc_html__( 'Select listing location field enable/disable', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_categories'] = esc_html__( 'Listing Categories', 'wp-dp' );
			$wp_dp_static_text['wp_dp_map_search_categories_hint'] = esc_html__( 'Select listing categories field enable/disable', 'wp-dp' );

			// End Shortcode Map Search.
			// Start Shortcode Listing Search.
			$wp_dp_static_text['wp_dp_listing_search_heading'] = esc_html__( 'DB: Listing Search', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listing_search_options'] = esc_html__( 'DB: Listing Search Options', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listing_search_listing_price_type'] = esc_html__( 'Listing Price Type', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listing_search_listing_price_type_hint'] = esc_html__( 'Select listing price type field enable/disable', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listing_search_listing_price'] = esc_html__( 'Listing Price', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listing_search_listing_price_hint'] = esc_html__( 'Select listing price field enable/disable', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listing_search_advance_filter'] = esc_html__( 'Listing Advance Filters Switch', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listing_search_advance_filter_hint'] = esc_html__( 'Select listing advance filters enable/disable', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listing_search_poup_link_text'] = esc_html__( 'Popup Link Text', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listing_search_poup_help_text'] = esc_html__( 'Popup Help Text', 'wp-dp' );

			// End Shortcode Listing Search.
			// Start Shortcode Listings.
			$wp_dp_static_text['wp_dp_shortcode_listings_heading'] = esc_html__( 'DB: Listings', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_options'] = esc_html__( 'DB: Listings Options', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_featured_listings_options'] = esc_html__( 'DB: Featured Listings Options', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_all_types'] = esc_html__( 'All Types', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_title_length'] = esc_html__( 'Listing Title Length (in words)', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_content_length'] = esc_html__( 'Listing Content Length', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_layout_switcher'] = esc_html__( 'Layout Switcher', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_layout_switcher_views'] = esc_html__( 'Layout Switcher Views', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_map_position'] = esc_html__( 'Map Position', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_footer_disable'] = esc_html__( 'Footer Disable', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_footer_disable_desc'] = esc_html__( 'That will work on only map view', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_left_filters_sidebar'] = esc_html__( 'Filter Sidebar', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_top_filters_search'] = esc_html__( 'Top Search Filters', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_left_filters'] = esc_html__( 'Left Filters', 'wp-dp' );

			$wp_dp_static_text['wp_dp_shortcode_listings_left_filters_count'] = esc_html__( 'Left Filters Counts', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_notifications_box'] = esc_html__( 'Notifications Box', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_draw_on_map'] = esc_html__( 'Draw On Map (URL)', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_top_category_count'] = esc_html__( 'Top Category Count', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_only_featured'] = esc_html__( 'Only Featured', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_number_of_custom_fields'] = esc_html__( 'No. of Custom Fields', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_ads_switch'] = esc_html__( 'Ads Switch', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_price_filters'] = esc_html__( 'Price Filters', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_enquiry_option'] = esc_html__( 'Send Message Listing Option', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_enquiry_option_desc'] = esc_html__( 'You will turn off/on send enquiry option in listing element', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_hide_option'] = esc_html__( 'Hide Listing Option', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_hide_option_desc'] = esc_html__( 'You will turn off/on hide option in listing element', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_notes_option'] = esc_html__( 'Listing Notes Option', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_notes_option_desc'] = esc_html__( 'You will turn off/on notes option in listing element', 'wp-dp' );
			$wp_dp_static_text['wp_dp_prop_notes_already_added'] = esc_html__( 'You have already added notes for this listing.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_location_filter'] = esc_html__( 'Location', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_count'] = esc_html__( 'Listing Count', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_count_hint'] = esc_html__( 'Number of series for add ad after every number like: 0, 7, 4, 2, 5', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_right_sidebar_content'] = esc_html__( 'Right Sidebar Content', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_right_sidebar_content_hint'] = esc_html__( '', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_show_more_switch'] = esc_html__( 'Show More Listing Button Switch', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_show_more_url'] = esc_html__( 'Show More Listing Button URL', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_show_more_url_hint'] = esc_html__( 'exp: http://abc.com', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_no_sidebar'] = esc_html__( 'No sidebar', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_sidebar'] = esc_html__( 'Sidebar', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_'] = esc_html__( 'DB', 'wp-dp' );

			// End Shortcode Listings.
			// Start Shortcode Featured Listings.
			$wp_dp_static_text['wp_dp_shortcode_featured_listings_heading'] = esc_html__( 'DB: Featured Listings', 'wp-dp' );
			// End Shortcode Featured Listings.
			// Start Shortcode Listings Slider.
			$wp_dp_static_text['wp_dp_listing_slider_heading'] = esc_html__( 'DB: Listings Slider', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listing_slider_options'] = esc_html__( 'DB: Listings Slider Options', 'wp-dp' );

			// End Shortcode Listings Slider.
			// Start Shortcode Listings Categories.
			$wp_dp_static_text['wp_dp_listing_categories_heading'] = esc_html__( 'DB: Listing Categories', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listing_categories_options'] = esc_html__( 'DB: Listing Categories Options', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listing_categories_categories'] = esc_html__( 'Categories', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listing_categories_categories_hint'] = esc_html__( 'Select categories here', 'wp-dp' );

			// End Shortcode Listings Categories.
			
			
			// Start Shortcode Listings Types Categories.
			$wp_dp_static_text['wp_dp_listing_types_categories_heading'] = esc_html__( 'DB: Listing Types and Categories', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listing_types_categories_options'] = esc_html__( 'DB: Listing Types and Categories Options', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listing_types_categories_categories'] = esc_html__( 'Types/Categories', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_listings_types_categories_types'] = esc_html__( 'Types', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listing_types_categories_styles_hint'] = esc_html__('Select a style from this dropdown.', 'wp-dp');
			$wp_dp_static_text['wp_dp_listing_types_categories_categories_hint'] = esc_html__( 'Select listing types/categories here', 'wp-dp' );
			// End Shortcode Listings Categories.
			
			// Start Shortcode Listings with Filters.
			$wp_dp_static_text['wp_dp_listings_with_filters_heading'] = esc_html__( 'DB: Listings with Filters', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_with_categories_heading'] = esc_html__( 'DB: Listings with Categories', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_with_filters_options'] = esc_html__( 'DB: Listings with Filters Options', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_with_categories_options'] = esc_html__( 'DB: Listings with Categories Options', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_with_filters_'] = esc_html__( 'Categories', 'wp-dp' );

			// End Shortcode Listings with Filters.
			// Start Shortcode Register User and Listung.
			$wp_dp_static_text['wp_dp_register_user_and_listing_heading'] = esc_html__( 'DB: Register User and Add Listing', 'wp-dp' );
			$wp_dp_static_text['wp_dp_register_user_and_listing_options'] = esc_html__( 'DB: Register User and Add Listing Options', 'wp-dp' );

			// End Shortcode Register User and Listung.
			$wp_dp_static_text['wp_dp_google_service_error'] = esc_html__( 'A service error occurred:', 'wp-dp' );
			$wp_dp_static_text['wp_dp_google_client_error'] = esc_html__( 'A client error occurred:', 'wp-dp' );
			$wp_dp_static_text['wp_dp_google_error_code'] = esc_html__( 'Error Code', 'wp-dp' );
			$wp_dp_static_text['wp_dp_google_authencitcation_failed'] = esc_html__( 'Authentication failed due to Invalid Credentials', 'wp-dp' );

			// Start Shortcode About.
			$wp_dp_static_text['wp_dp_shortcode_about_heading'] = esc_html__( 'DB: About Us', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_about_options'] = esc_html__( 'DB: About Options', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_about_button_url'] = esc_html__( 'Button Url', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_about_button_text'] = esc_html__( 'Button Text', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_about_button_color'] = esc_html__( 'Color', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_about_button_color_hint'] = esc_html__( 'Set the button Text color', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_about_bg_color'] = esc_html__( 'BG Color', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_about_bg_color_hint'] = esc_html__( 'Set the BG color for your about us', 'wp-dp' );
			$wp_dp_static_text['wp_dp_shortcode_about_content'] = esc_html__( 'Content', 'wp-dp' );

			// End Shortcode About.
			// Start Custom Woocommerce Hooks.
			$wp_dp_static_text['wp_dp_wooc_hooks_order_received'] = esc_html__( 'Thank you. Your order has been received.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_wooc_hooks_vat'] = esc_html__( 'VAT', 'wp-dp' );

			// End Custom Woocommerce Hooks.
			// Start Notifications Modules Options.
			$wp_dp_static_text['wp_dp_yes'] = esc_html__( 'Yes', 'wp-dp' );
			$wp_dp_static_text['wp_dp_no'] = esc_html__( 'No', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_listing_alerts'] = esc_html__( 'Email Alerts Settings', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_alert_frequencies'] = esc_html__( 'Set Alert Frequencies', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_frequency'] = esc_html__( 'Frequency', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_announcements'] = esc_html__( 'For all email alert notification of user, whenever a new similar listing is posted, the alert is sent to relevant users. You can set frequency of sending alerts from option given below. Make sure email server / smtp is rightly configured for sending emails.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_annually'] = esc_html__( 'Annually', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_annually_hint'] = esc_html__( 'Do you want to allow user to set alert frequency to annually?', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_biannually'] = esc_html__( 'Biannually', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_biannually_hint'] = esc_html__( 'Do you want to allow user to set alert frequency to biannually?', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_monthly'] = esc_html__( 'Monthly', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_monthly_hint'] = esc_html__( 'Do you want to allow user to set alert frequency to monthly?', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_fortnightly'] = esc_html__( 'Fortnightly', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_fortnightly_hint'] = esc_html__( 'Do you want to allow user to set alert frequency to fortnight?', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_weekly'] = esc_html__( 'Weekly', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_weekly_hint'] = esc_html__( 'Do you want to allow user to set alert frequency to weekly?', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_daily'] = esc_html__( 'Daily', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_daily_hint'] = esc_html__( 'Do you want to allow user to set alert frequency to daily?', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_listing_alert_shortcode'] = esc_html__( 'Listing Alert Shortcode', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_listing_alert_shortcode_hint'] = esc_html__( 'Do you want to show "Get email notifications with any updates" button on this listings listing page to set listing alerts.', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_listing_alert_shortcode_enable'] = esc_html__( 'Enable', 'wp-dp' );
			$wp_dp_static_text['wp_dp_notifications_listing_alert_shortcode_disable'] = esc_html__( 'Disable', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listing_detail_five_favourited'] = esc_html__( 'Favourited', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_total_percentage'] = esc_html__( 'Member Reviews Percentage', 'wp-dp' );
			$wp_dp_static_text['wp_dp_member_total_review_count'] = esc_html__( 'Member Total Reviews', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_widget'] = esc_html__( 'DB : Featured Listing', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_widget_style'] = esc_html__( 'Listing Styles', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_widget_style_grid'] = esc_html__( 'Featured', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_widget_style_medium'] = esc_html__( 'Simple', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_widget_listing_num'] = esc_html__( 'Number of Listing', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_widget_listing_num_hint'] = esc_html__( 'Add number of listing to display', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_widget_sort_by'] = esc_html__( 'Filter by', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_widget_sort_by_most_viewed'] = esc_html__( 'Most Viewed', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_widget_sort_by_featured'] = esc_html__( 'Home Featuerd', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_widget_sort_by_recent'] = esc_html__( 'Recent', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_element_cs_fields'] = esc_html__( 'Custom Fields', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_element_slider_list_view_grid'] = esc_html__( 'Grid', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_element_slider_list_view_list'] = esc_html__( 'List', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_element_slider_list_view_fancy'] = esc_html__( 'Fancy', 'wp-dp' );
			$wp_dp_static_text['wp_dp_listings_element_slider_list_view_map'] = esc_html__( 'Map', 'wp-dp' );
			$wp_dp_static_text['wp_dp_review_backend_review_detail'] = esc_html__( 'Review Details', 'wp-dp' );
			$wp_dp_static_text['wp_dp_emails_backend_statics_all_mails'] = esc_html__( 'All Emails', 'wp-dp' );
			$wp_dp_static_text['wp_dp_emails_backend_statics_sucess_mails'] = esc_html__( 'Successful Emails', 'wp-dp' );
			$wp_dp_static_text['wp_dp_emails_backend_statics_fail_mails'] = esc_html__( 'Failed Emails', 'wp-dp' );
                        $wp_dp_static_text['wp_dp_login_popup_sign_in'] = esc_html__( 'Sign In', 'wp-dp' );
                        
			
			/*
             * Map Block
             */
            $wp_dp_static_text['wp_dp_plugin_options_distance_measure_by'] = esc_html__('Distance measure by', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_km'] = esc_html__('KM', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_miles'] = esc_html__('Miles', 'wp-dp');
            $wp_dp_static_text['wp_dp_func_km'] = esc_html__('Km', 'wp-dp');


            /*
             * Map Block
             */
            $wp_dp_static_text['wp_dp_sidebar_compare_listings_lable'] = esc_html__('Compare Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_sidebar_compare_button_lable'] = esc_html__('Compare', 'wp-dp');
            $wp_dp_static_text['wp_dp_sidebar_compare_reset_button_lable'] = esc_html__('Reset', 'wp-dp');


            /*
             * Compare Popup
             */
            $wp_dp_static_text['wp_dp_reset'] = esc_html__('Reset', 'wp-dp');
            $wp_dp_static_text['wp_dp_send_enquiry'] = esc_html__('Send Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_selected_enquiries'] = esc_html__('Selected Enquiries', 'wp-dp');
			
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
			
			// Start Arrange Viewing.
            $wp_dp_static_text['wp_dp_viewing_name_empty'] = esc_html__('Name should not be empty', 'wp-dp');
            $wp_dp_static_text['wp_dp_viewing_phone_empty'] = esc_html__('Phone number should not be empty', 'wp-dp');
            $wp_dp_static_text['wp_dp_viewing_email_empty'] = esc_html__('Email address should not be empty', 'wp-dp');
            $wp_dp_static_text['wp_dp_viewing_email_not_valid'] = esc_html__('Email address is not valid', 'wp-dp');
            $wp_dp_static_text['wp_dp_viewing_msg_empty'] = esc_html__('Message should not be empty', 'wp-dp');
            $wp_dp_static_text['wp_dp_viewing_own_listing_error'] = esc_html__("You can't sent message on your own listing.", 'wp-dp');
            $wp_dp_static_text['wp_dp_member_viewings_recent_viewings'] = esc_html__('Recent Arrange Viewings', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_viewings_my_viewings'] = esc_html__('My Arrange Viewings', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_viewings_received_viewings'] = esc_html__('Received Arrange Viewings', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_viewings_not_received_viewing'] = esc_html__('You don\'t have any received arrange viewing', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_viewings_not_viewing'] = esc_html__('You don\'t have any arrange viewing', 'wp-dp');
            $wp_dp_static_text['wwp_dp_member_viewings_title'] = esc_html__('Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_viewings_date'] = esc_html__('Date', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_viewings_buyer'] = esc_html__('Buyer', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_viewings_member'] = esc_html__('Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_viewings_status'] = esc_html__('Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_register_arrange_viewings'] = esc_html__('Arrange Viewings', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_arrange_viewing'] = esc_html__('Arrange Viewing', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_viewing_detail'] = esc_html__('Viewing Detail', 'wp-dp');
            $wp_dp_static_text['wp_dp_viewing_detail_status'] = esc_html__('Viewing Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_viewing_detail_viewing_completed'] = esc_html__('Your viewing is completed.', 'wp-dp');
            $wp_dp_static_text['wp_dp_viewing_detail_closed_viewing'] = esc_html__('Close Viewing', 'wp-dp');
            $wp_dp_static_text['wp_dp_viewing_detail_viewing_is'] = esc_html__('Your viewing is ', 'wp-dp');
            $wp_dp_static_text['wp_dp_viewing_detail_mark_read'] = esc_html__('Mark viewing Read', 'wp-dp');
            $wp_dp_static_text['wp_dp_viewing_detail_mark_unread'] = esc_html__('Mark viewing Unread', 'wp-dp');
            $wp_dp_static_text['wp_dp_viewing_unread'] = esc_html__('The viewing has been marked as unread.', 'wp-dp');
            $wp_dp_static_text['wp_dp_viewing_read'] = esc_html__('The viewing has been marked as read.', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewing_detail_date'] = esc_html__('Date', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewing_detail_name'] = esc_html__('Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewing_detail_phone'] = esc_html__('Phone Number', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewing_detail_email'] = esc_html__('Email', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewing_detail_msg'] = esc_html__('Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewing_detail_'] = esc_html__('Please', 'wp-dp');
            $wp_dp_static_text['wp_dp_viewing_status_changed'] = esc_html__('Viewing status has been changed.', 'wp-dp');
            $wp_dp_static_text['wp_dp_viewing_status_closed'] = esc_html__('Your viewing has been closed.', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewing_detail_procceing'] = esc_html__('Processing', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewing_detail_completed'] = esc_html__('Completed', 'wp-dp');
            $wp_dp_static_text['wp_dp_arrange_viewing_detail_closed'] = esc_html__('Closed', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_delete_selected_backup_file'] = esc_html__('This action will delete your selected Backup File. Do you still want to continue?', 'wp-dp');

            // End Arrange Viewing.


			return $wp_dp_static_text;
		}

	}

	new wp_dp_plugin_all_strings_4;
}
