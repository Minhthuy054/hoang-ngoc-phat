<?php

/**
 * Static string 2
 */
if (!class_exists('wp_dp_plugin_all_strings_2')) {

    class wp_dp_plugin_all_strings_2 {

        public function __construct() {

            add_filter('wp_dp_plugin_text_strings', array($this, 'wp_dp_plugin_text_strings_callback'), 1);
        }

        public function wp_dp_plugin_text_strings_callback($wp_dp_static_text) {
            global $wp_dp_static_text;
            /*
             * listing type meta
             */
            $wp_dp_static_text['wp_dp_reservation_inquires_form'] = esc_html__('Reservation / Inquiries From', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_sbmit'] = esc_html__('Submit', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_cancelaion_policy'] = esc_html__('* Cancellation Policy:', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_check_here'] = esc_html__('Check here', 'wp-dp');
            $wp_dp_static_text['wp_dp_show_all_feature_item'] = esc_html__('Show all Feature Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_show_only_checked_features'] = esc_html__('Show only checked Features', 'wp-dp');
            $wp_dp_static_text['wp_dp_show_all_feature_item_desc'] = esc_html__('If you turn on this option then all feature option will be show on listing detail page with checked / un-checked value, but if you turned off this option then only checked feature options will be show at listing detail page.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_delete_row'] = esc_html__('Delate Row', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_parent'] = esc_html__('Parent', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_listing_price'] = esc_html__('Price Option for Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_listing_price_desc'] = esc_html__('If set to "OFF" the listings in this type will not have the price options while adding / updating them.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_listing_price_options'] = esc_html__('Price options', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_listing_price_type'] = esc_html__('Price Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_listing_price_type_fixed'] = esc_html__('Fixed', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_listing_price_type_varient'] = esc_html__('Varient', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_minimum_options_filter'] = esc_html__('Minimum Price on search filters', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_minimum_options_filter_desc'] = esc_html__('This will be the minimum option on the search filters for price.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_maximum_options_filter'] = esc_html__('Number of Price option', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_maximum_options_filter_desc'] = esc_html__('This will be the maximum number of price options on the search filters, You can add only maximum 50 intervals.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_man_max_interval'] = esc_html__('Min - Max Interval', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_man_max_interval_desc'] = esc_html__('This will be the interval for the minimum and maximum options on search filters. e.g( if you have entered the interval as "50" the options will be like 1-50-100-150 .... ).', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_others'] = esc_html__('Others', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_allowed_extension'] = esc_html__('Allowed Types/Extensions', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_allowed_extension_desc'] = esc_html__('Select attachments files allowed types/extensions', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_walk_score'] = esc_html__('Walk Scores', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_show_more_less_desc'] = esc_html__('Show More/Less Description', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_description_length'] = esc_html__('Description Length', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_description_length_in_words'] = esc_html__('Description Length in words.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_detail_page'] = esc_html__('Views', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_detail_page_select'] = esc_html__('Select a listing detail page view from this dropdown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_detail_page_select1'] = esc_html__('View 1', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_detail_page_select2'] = esc_html__('View 2', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_detail_page_select3'] = esc_html__('View 3', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_detail_page_select4'] = esc_html__('View 4', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_df_sbheader_opt'] = esc_html__('Default Sub Header Option', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_df_sbheader_opt_desc'] = esc_html__('The selected value will be the default option for subheader while the view 3 is selected.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_sbheader_opt_map'] = esc_html__('Map', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_sbheader_opt_banner'] = esc_html__('Banner', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_sticky_navigation'] = esc_html__('Sticky Navigation', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_small_image'] = esc_html__('Small Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_small_image_desc'] = esc_html__('On some places we need to show small images for listing type, eg. types with categories element.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_big_iamge'] = esc_html__('Large Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_big_iamge_desc'] = esc_html__('On some places we need to show large images for listing type, eg. selecting type while submitting listing.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_only_num_allwd'] = esc_html__('Only numbers are allowed', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_frm_bldr_flds'] = esc_html__('Form Builder Fields', 'wp-dp');

            /*
             * Detail Page Modules titles dynamic
             */

            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_heading'] = esc_html__('Detail Page Section Titles', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_key_details'] = esc_html__('Key Details Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_key_details_hint'] = esc_html__('You can add title if you want to be the change it on detail page in "key detail section", otherwise by default text will be shown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_amenities'] = esc_html__('Amenities Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_amenities_hint'] = esc_html__('You can add title if you want to be the change it on detail page in "Amenities section", otherwise by default text will be shown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_listing_desc'] = esc_html__('Listing Description Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_listing_desc_hint'] = esc_html__('You can add title if you want to be the change it on detail page in "Listing Description section", otherwise by default text will be shown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_video'] = esc_html__('Video Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_video_hintt'] = esc_html__('You can add title if you want to be the change it on detail page in "Video section", otherwise by default text will be shown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_image_gallery'] = esc_html__('Image Gallery Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_image_gallery_hint'] = esc_html__('You can add title if you want to be the change it on detail page in "Image Gallery section", otherwise by default text will be shown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_virtual_tour'] = esc_html__('360&deg; Virtual Tour Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_virtual_tour_hint'] = esc_html__('You can add title if you want to be the change it on detail page in "360&deg; Virtual Tour section", otherwise by default text will be shown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_faq'] = esc_html__('FAQ Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_faq_hint'] = esc_html__('You can add title if you want to be the change it on detail page in "FAQ section", otherwise by default text will be shown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_near_by'] = esc_html__('Near By Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_floor_plan'] = esc_html__('Design Sketches Element Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_floor_plan_hint'] = esc_html__('You can add title if you want to be the change it on detail page in "Design Sketches section", otherwise by default text will be shown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_file_attachment'] = esc_html__('Files Attachments Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_file_attachment_hint'] = esc_html__('You can add title if you want to be the change it on detail page in "Files Attachments section", otherwise by default text will be shown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_yelp_places'] = esc_html__('Yelp Places Nearby Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_yelp_places_hint'] = esc_html__('You can add title if you want to be the change it on detail page in "Yelp Places Nearby section", otherwise by default text will be shown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_nearby_places'] = esc_html__('Near By Places Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_nearby_places_hint'] = esc_html__('You can add title if you want to be the change it on detail page in "Near By Places section", otherwise by default text will be shown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_default_text_key_details'] = esc_html__('Key Details', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_default_text_amenities'] = esc_html__('Amenities', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_default_text_listing_desc'] = esc_html__('Listing Description', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_default_text_video'] = esc_html__('Video', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_default_text_image_gallery'] = esc_html__('Image Gallery', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_default_text_virtual_tour'] = esc_html__('360&deg; Virtual Tour', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_default_text_faq'] = esc_html__('FAQ', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_default_text_near_by'] = esc_html__('Near By', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_default_text_apartment_4_sale'] = esc_html__('Apartment For Sale', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_default_text_floor_plan'] = esc_html__('Floor Plans', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_default_text_file_attachment'] = esc_html__('Files Attachments', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_default_text_yelp_places'] = esc_html__('Yelp Places Nearby', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_type_meta_section_title_default_text_nearby_places'] = esc_html__('Near By Places', 'wp-dp');

            /*
             * End Detail Page Modules titles dynamic
             */

            $wp_dp_static_text['wp_dp_listing_top_category'] = esc_html__('ad', 'wp-dp');


            /*
             * transaction
             */
            $wp_dp_static_text['wp_dp_transaction_column_transaction_id'] = esc_html__('Transaction Id', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_column_date'] = esc_html__('Date', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_column_order_owner'] = esc_html__('Order Owner', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_column_order_type'] = esc_html__('Order Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_column_gateway'] = esc_html__('Payment Gateway', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_column_amount'] = esc_html__('Amount', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_gateway_nill'] = esc_html__('Nill', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_gateway_deatil_order'] = esc_html__('Order Detail', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_post_type_name'] = esc_html__('Transactions', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_post_type_menu_name'] = esc_html__('Transactions', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_post_type_add_new_item'] = esc_html__('Add New Transaction', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_post_type_edit_item'] = esc_html__('Edit Transaction', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_post_type_new_item'] = esc_html__('New Transaction Item', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_post_type_add_new'] = esc_html__('Add New Transaction', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_post_type_view_item'] = esc_html__('View Transaction Item', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_post_type_search_item'] = esc_html__('Search', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_post_type_not_found'] = esc_html__('Nothing found', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_post_type_not_found_trash'] = esc_html__('Nothing found in Trash', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_post_type_trans_options'] = esc_html__('Transaction Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_post_type_slct_pblisher'] = esc_html__('Select Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_post_type_slct_pay_gateway'] = esc_html__('Select Payment Gateway', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_slct_paymnt_gateway'] = esc_html__('Select Payment Gateway', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_meta_trans_id'] = esc_html__('Transaction Id', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_meta_order_id'] = esc_html__('Order Id', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_meta_summary'] = esc_html__('Summary', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_meta_order_type'] = esc_html__('Order Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_meta_slct_order_type'] = esc_html__('Select Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_meta_packages_order'] = esc_html__('Packages Order', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_meta_reservation_order'] = esc_html__('Reservation Order', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_meta_user'] = esc_html__('User', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_meta_amount'] = esc_html__('Amount', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_meta_payment_gateway'] = esc_html__('Payment Gateway', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_meta_status'] = esc_html__('Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_meta_status_pending'] = esc_html__('Pending', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_meta_status_in_process'] = esc_html__('In Process', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_meta_status_approved'] = esc_html__('Approved', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_meta_status_cancelled'] = esc_html__('Cancelled', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_order_owner_filter'] = esc_html__('Search by Order Owner', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_total'] = esc_html__('Total', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_processed'] = esc_html__('In Process', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_approved'] = esc_html__('Approved', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_pending'] = esc_html__('Pending', 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_cancelled'] = esc_html__('Cancelled', 'wp-dp');
            /*
             * Plugin options
             */
            $wp_dp_static_text['wp_dp_plugin_options_save_all_settings'] = esc_html__('Save All Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_reset_all_options'] = esc_html__('Reset All Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_please_wait'] = esc_html__('Please Wait...', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_general_options'] = esc_html__('General Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_member_settings'] = esc_html__('Account Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_single_listing_settings'] = esc_html__('Single Listing Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_listing_submission'] = esc_html__('Listing Submission', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_gateways'] = esc_html__('Payment Setting', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_api_settings'] = esc_html__('Api Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_settings'] = esc_html__('Map Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_nearby_place'] = esc_html__('Map Nearby Places', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_location_settings'] = esc_html__('Locations Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_auto_post'] = esc_html__('Social Auto-Poster', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_icon'] = esc_html__('Social Icon', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_login'] = esc_html__('Social Login', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_login_announcement'] = __('Our free Social Login tool makes it easy for website and mobile users to easily register and log in on your site with their social network identities.[<a onclick="toggleDiv(this.hash);return false;" href="#tab-api-setting">Configure your social network account</a>]', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_ads_management'] = esc_html__('Ads Management', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_generl_options'] = esc_html__('General Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_user_hdr_lgn'] = esc_html__('User Header Login', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_user_hdr_lgn_hint'] = esc_html__('Dashboard and Front-End login/register option can be hide by turning off this switch.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_opening_hours'] = esc_html__('Opening Hours', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_opening_hours_hint'] = esc_html__('Opening Hours can be disabled / enabled from whole site by this switch ', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_member_dashboard'] = esc_html__('User Account Page', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_member_dashboard_hint'] = esc_html__('Select page for member dashboard here. This page is set in page template drop down. To create member dashboard page, go to Pages > Add new page, set the page template to "member" in the right menu.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_create_listing_btn_switch'] = esc_html__('Create Listing Button', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_create_listing_btn_switch_hint'] = esc_html__('Turn this switch "ON" to show create listing button in header otherwise switch it off ', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_create_listing_pge'] = esc_html__('Listing Submission Page ', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_create_listing_pge_hint'] = esc_html__('Select listing submission page, which already have the add listing shortcode on it.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_dashboard_pagination'] = esc_html__('User Account Pagination', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_dashboard_paginationsz'] = esc_html__('Pagination', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_pkgs_detail_page'] = esc_html__('Price Plan Checkout Page', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_pkgs_detail_page_hint'] = esc_html__('Please select a page for package details. This page is set in page template drop down. To create member dashboard page, go to Pages > Add new page, set the page template to "member" in the right menu.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_listing_settings'] = esc_html__('Page Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_free_listing_posting'] = esc_html__('Free Listings Posting', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_listing_expiray_duration'] = esc_html__('Listings Expiry Duration ( Days )', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_listing_expiray_duration_hint'] = esc_html__('', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_single_listing_gall_cntct'] = esc_html__('Single Listing Gallery & Contact', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_single_listing_gall_cntct_hint'] = esc_html__('Choose single listing gallery & contact display option.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_single_listng_bread_crum'] = esc_html__('Single Listing Breadcrumbs', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_single_listng_bread_crum_hint'] = esc_html__('Choose whether to show breadcrumbs on listing detail page or not.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_submissions'] = esc_html__('Submissions', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_search_result'] = esc_html__('Search Result Page', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_search_result_hint'] = esc_html__('Set the specific page where you want to show search results. The slected page must have listings page element on it. (Add listings page element while creating the listing search result page).', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_listing_publish_pend'] = esc_html__('Listing Default Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_select_compare_page'] = esc_html__('Compare Result Page', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_select_compare_page_hint'] = esc_html__('Select compare page. All listings added in compare will list here. This page must have compare shortcode in it.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_listing_publish_pend_hint'] = esc_html__('Turn this switcher OFF to allow direct publishing of submitted listings by member without review / moderation. If this switch is ON, listings will be published after admin review / moderation.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_announcments'] = esc_html__('Announcements', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_announcement_title_dshbrd'] = esc_html__('Announcement Heading', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_announcement_title_dshbrd_hint'] = esc_html__('Please add text for announcement title that shows at user dashboard ', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_announcement_desc_dashboard'] = esc_html__('Announcement', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_announcement_desc_dashboard_hint'] = esc_html__('Please add text for announcement description that shows at user dashboard .', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_announcement_title_listing'] = esc_html__('Announcement Title For Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_announcement_title_listing_hint'] = esc_html__('Please add text for announcement title that shows at listing page .', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_announcement_desc_listing'] = esc_html__('Announcement Description For Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_announcement_desc_listing_hint'] = esc_html__('Please add text for announcement description that shows at listing page .', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_announcement_bg_color'] = esc_html__('Announcement Background Color', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_environmental_text'] = esc_html__(' Instructions Heading', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_environmental_text_hint'] = esc_html__('Enter the title which will be used as instructions title on listing detail page.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_environmental_desc'] = esc_html__('Instructions', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_environmental_desc_hint'] = esc_html__('Enter the title which will be used as instructions description on listing detail page.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_yelp_places_cats'] = esc_html__('Yelp Places Categories', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_yelp_places'] = esc_html__('Yelp Places', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_default_mortgage'] = esc_html__('Default Mortgage', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_mortgage_std'] = esc_html__('Mortgage Calculator', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_min_lease_yr'] = esc_html__('Minimum Lease Year', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_max_lease_yr'] = esc_html__('Maximim Lease Year', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_dflt_lstng_cs_field'] = esc_html__('Default Listings Custom Fields', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_listing_cs_field'] = esc_html__('Listing Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_no_cus_fiels'] = esc_html__(' No of Listing Features for Listing Medium', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_terms_policy'] = esc_html__('Terms & Policy', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_terms_plicy_onoff'] = esc_html__('Term & Policy On/Off', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_terms_plicy_onoff_hint'] = esc_html__('Turn on if you need to add term & policy check in all forms.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_term_policy_descr'] = esc_html__('Term & Policy Description', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_term_policy_descr_hint'] = esc_html__('Please add text for term & policy with link', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_auto_apporal'] = esc_html__('User Auto Approval', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_auto_apporal_hint'] = esc_html__('If this switch set to ON new user will be auto approved. If switch is set to OFF admin will have to approve the new user.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_profile_image'] = esc_html__('User Default Avatars', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_profile_image_hint_label'] = esc_html__('Please click on add new avatar button if you want to add new avatar, which can be used in "Profile Settings" of front-end user dashboard.', 'wp-dp');

            $wp_dp_static_text['wp_dp_plugin_options_profile_image_desc'] = esc_html__('Add New Avatar', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_dflt_plac_hldr'] = esc_html__('Default Avatar Placeholder', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_price_option'] = esc_html__('Price Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_gateway_settings'] = esc_html__('Payment Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_vat_onoff'] = esc_html__('VAT On/Off', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_vat_onoff_hint'] = esc_html__('This switch will control VAT calculation and its payment along with package price. If this switch will be ON, user must have to pay VAT percentage separately. Turn OFF the switch to exclude VAT from payment.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_vat_in_percent'] = esc_html__('VAT in %', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_vat_in_percent_hint'] = esc_html__('Here you can add VAT percentage according to your country laws & regulations.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_woocommerece_pat_gateway'] = esc_html__('Woocommerce Payment Gateways', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_woocommerece_pat_gateway_hint'] = esc_html__('Make it on to use the woocommerce payment gateways instead of builtin ones', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_currency_position'] = esc_html__('Currency Position', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_currency_position_hint'] = esc_html__('You can control the position of the currency sign.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_currency_position_left'] = esc_html__('Left', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_currency_position_right'] = esc_html__('Right', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_currency_position_left_space'] = esc_html__('Left with space', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_currency_position_right_space'] = esc_html__('Right with space', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_payment_text'] = esc_html__('Payment Text', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_default_location'] = esc_html__('Default Location', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_default_location_hint'] = esc_html__('Default Location Set default location for your site. This location can be set from Listings > Locations in back end admin area. This will show location of admin only. It is not linked with Geo-location.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_marker_icon'] = esc_html__('Map Marker Icon', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_cluster_icon'] = esc_html__('Map Cluster Icon', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_slider_view_icon'] = esc_html__('Slider View Icon on listing detail page', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_view_icon'] = esc_html__('Map View Icon on listing detail page', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_street_view_icon'] = esc_html__('Map Street View Icon on listing detail page', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_zoom_level'] = esc_html__('Zoom Level', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_zoom_level_hint'] = esc_html__('Set zoom level 1 to 15 only.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style'] = esc_html__('Map Style', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_hint'] = esc_html__('Set Map Style.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_map_box'] = esc_html__('Map Box', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_blue_water'] = esc_html__('Blue Water', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_icy_blue'] = esc_html__('Icy Blue', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_bluish'] = esc_html__('Bluish', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_light_blue_water'] = esc_html__('Light Blue Water', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_clad_me'] = esc_html__('Clad Me', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_chilled'] = esc_html__('Chilled', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_two_tone'] = esc_html__('Two Tone', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_light_and_dark'] = esc_html__('Light and Dark', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_illusttracioa'] = esc_html__('Ilustracao', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_flat_pale'] = esc_html__('Flat Pale', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_title'] = esc_html__('Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_moret'] = esc_html__('Moret', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_semisel'] = esc_html__('Samisel', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_herbert_map'] = esc_html__('Herbert Map', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_light_dream'] = esc_html__('Light Dream', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_blue_essence'] = esc_html__('Blue Essence', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_style_rpn_map'] = esc_html__('RPN Map', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_street_address'] = esc_html__('Address', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_street_address_hint'] = esc_html__('Set default street address here.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_col_hdng_dflt_location_hint'] = esc_html__('Set default location for your site (Country, City & Address). This location can be set from Listings > Locations in back end admin area. This will show location of admin only and willl fetch results from the given location first. It is not linked with Geo-location', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_backend_loc_map_stng'] = esc_html__('Backend Locations & Maps Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_backend_locations'] = esc_html__('Locations', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_backend_loc_and_level'] = esc_html__('Location\'s Levels', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_backend_geo_loc'] = esc_html__('Geo Location', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_backend_geo_loc_share'] = esc_html__('Ask user to share his location.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_backend_auto_country_detection'] = esc_html__('Auto Country Detection', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_backend_auto_country_detection_hint'] = esc_html__('Do you want to detect country automatically using user\'s IP?', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_backend_max_adrs_lmt'] = esc_html__('Address Maximum Text Limit', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_backend_max_adrs_lmt_hint'] = esc_html__('Allowed address maximum text limit.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_backend_drawing_tools'] = esc_html__('Drawing Tools', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_backend_drawing_tools_hint'] = esc_html__('Do you want drawing tools on map?', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_drawing_tool_line_clr'] = esc_html__('Drawing Tools Line Color', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_drawing_tool_line_clr_hint'] = esc_html__('Color used while drawing line or polygon on map', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_drawing_tool_fill_clr'] = esc_html__('Drawing Tools Fill Color', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_drawing_tool_fill_clr_hint'] = esc_html__('Color used to fill polygon on map.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_drawing_tool_auto_cmplte'] = esc_html__('Auto Complete', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_drawing_tool_auto_cmplte_hint'] = esc_html__('Do you want google to give suggestions to auto complete?', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_circle_radius'] = esc_html__('Circle Radius', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_loc_frm_fields'] = esc_html__('Location Form Fields', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_loc_fields'] = esc_html__('Location\'s Fields', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_map_nearby_place_hint'] = esc_html__('Define labels and images to show nearby places on Google Maps.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_auto_post_stng'] = esc_html__('Social Auto Post Settings', 'wp-dp');

            $wp_dp_static_text['wp_dp_plugin_options_autopost_twitter'] = esc_html__('Twitter', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_autopost_announcement'] = __('Social Network Auto Poster lets you automatically post all new Submitted listings  to social networks such as Facebook, Twitter, LinkedIn. The whole process is completely automated. Just add new listing from front-end and it will be published to your configured social network account. You can reach the most audience and tell all your  readers and followers about Listing. [<a onclick="toggleDiv(this.hash);return false;" href="#tab-api-setting">Configure your social network account</a>]', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_autopost_facebook'] = esc_html__('Facebook', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_autopost_linkedin'] = esc_html__('LinkedIn', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_login_twitter'] = esc_html__('Show Twitter', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_login_twitter_hint'] = esc_html__('Manage user registration via Twitter here. If this switch is set ON, users will be able to sign up / sign in with Twitter. If it will be OFF, users will not be able to register / sign in through Twitter.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_login_facebook'] = esc_html__('Facebook Login On/Off', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_login_facebook_hint'] = esc_html__('Manage user registration via Facebook here. If this switch is set ON, users will be able to sign up / sign in with Facebook. If it will be OFF, users will not be able to register / sign in through Facebook.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_login_google'] = esc_html__('Google Login On/Off', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_login_google_hint'] = esc_html__('Manage user registration via Google here. If this switch is set ON, users will be able to sign up / sign in with Google. If it will be OFF, users will not be able to register / sign in through Google.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_api_setting'] = esc_html__('Api Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_api_setting_twitter'] = esc_html__('Twitter', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_twitter_cons_key'] = esc_html__('Consumer Key', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_twitter_cons_key_hint'] = esc_html__('Insert Twitter Consumer Key here. When you create your Twitter App, you will get this key.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_twitter_cons_secret'] = esc_html__('Consumer Secret', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_twitter_cons_secret_hint'] = esc_html__('Insert Twitter Consumer secret here. When you create your Twitter App, you will get this key', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_twitter_access_token'] = esc_html__('Access Token', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_twitter_access_token_hint'] = esc_html__('Insert Twitter Access Token for permissions. When you create your Twitter App, you will get this Token', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_twitter_access_token_secret'] = esc_html__('Access Token Secret', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_twitter_access_token_secret_hint'] = esc_html__('Insert Twitter Access Token Secret here. When you create your Twitter App, you will get this Token', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_api_settings_facebook'] = esc_html__('Facebook', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_facebook_app_id'] = esc_html__('Facebook Application ID', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_facebook_app_id_hint'] = esc_html__('Here you have to add your Facebook application ID. You will get this ID when you create Facebook App', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_facebook_secret'] = esc_html__('Facebook Secret', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_facebook_secret_hint'] = esc_html__('Put your Facebook Secret here. You can find it in your Facebook Application Dashboard', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_facebook_access_token'] = esc_html__('Facebook Access Token', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_facebook_access_token_hint'] = esc_html__('Click on the button bellow to get access token', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_api_settings_google'] = esc_html__('Google', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_google_client_id'] = esc_html__('Google Client ID', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_google_client_id_hint'] = esc_html__('Put your Google client ID here.  To get this ID, go to your Google account Dashboard', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_google_client_secret'] = esc_html__('Google Client Secret', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_google_client_secret_hint'] = esc_html__('Put your google client secret here.  To get client secret, go to your Google account', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_google_api_key'] = esc_html__('Google API key', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_google_api_key_hint'] = esc_html__('Put your Google API key here.  To get API, go to your Google account', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_google_redirect'] = esc_html__('Fixed redirect url for login', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_google_redirect_hint'] = esc_html__('Put your google redirect url here', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_api_setting_captcha'] = esc_html__('reCaptcha', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_api_setting_captcha_hint'] = esc_html__('Manage your captcha code for secured Signup here. If this switch will be ON, user can register after entering Captcha code. It helps to avoid robotic / spam sign-up', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_captcha_site_key'] = esc_html__('Site Key', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_captcha_site_key_hint'] = esc_html__('Put your site key for captcha. You can get this site key after registering your site on Google', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_captcha_secret_key'] = esc_html__('Secret Key', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_captcha_secret_key_hint'] = esc_html__('Put your site Secret key for captcha. You can get this Secret Key after registering your site on Google.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_api_setting_linkedin'] = esc_html__('LinkedIn', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_linkedin_app_id'] = esc_html__('LinkedIn Application Id', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_linkedin_app_id_hint'] = esc_html__('Add LinkedIn application ID. To get your Linked-in Application ID, go to your LinkedIn Dashboard', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_linkedin_secret'] = esc_html__('Linkedin Secret', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_linkedin_secret_hint'] = esc_html__('Put your LinkedIn Secret here. You can find it in your LinkedIn Application Dashboard', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_linkedin_access_token'] = esc_html__('Linkedin Access Token', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_linkedin_access_token_hint'] = esc_html__('Click on the button bellow to get access token', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_api_setting_yelp'] = esc_html__('Yelp', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_yelp_app_id'] = esc_html__('Yelp App ID', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_yelp_app_id_hint'] = esc_html__('Add Yelp application ID. To get your Yelp Application ID, go to your Yelp Account.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_yelp_app_secret'] = esc_html__('Yelp App Secret', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_yelp_app_secret_hint'] = esc_html__('Put your Yelp App Secret here. You can find it in your Yelp Application Dashboard', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_yelp_access_token'] = esc_html__('Yelp Access Token', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_yelp_access_token_hint'] = esc_html__('Click on the button bellow to get access token.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_api_setting_walk_score'] = esc_html__('Walk Score', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_walk_score_app_id'] = esc_html__('Walk Score API Key', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_walk_score_app_id_hint'] = esc_html__('Add Walk Score API key. To get your Walk Score API key, go to your Walk Score Account.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_ads_management_settings'] = esc_html__('Ads Management Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_sharing'] = esc_html__('Social Sharing', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_sharing_facebook'] = esc_html__('Facebook', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_sharing_twitter'] = esc_html__('Twitter', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_sharing_google_plus'] = esc_html__('Google Plus', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_sharing_pinterest'] = esc_html__('Pinterest', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_sharing_tumbler'] = esc_html__('Tumblr', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_sharing_dribble'] = esc_html__('Dribbble', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_sharing_instagram'] = esc_html__('Instagram', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_sharing_stumble_upon'] = esc_html__('StumbleUpon', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_sharing_youtube'] = esc_html__('youtube', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_social_sharing_share_more'] = esc_html__('share more', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_import_export'] = esc_html__('import & export', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_backup'] = esc_html__('Backup', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_user_import_export'] = esc_html__('Users Import / Export', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_import_user_data'] = esc_html__('Import Users Data', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_backup_locations'] = esc_html__('Backup Locations', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_backup_listing_type_cats'] = esc_html__('Backup Listing Type Categories', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_adv_settings'] = esc_html__('Advance Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_demo_user_login'] = esc_html__('Demo User Login', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_slct_agency'] = esc_html__('Please Select User', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_slct_member'] = esc_html__('Please Select Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_demo_member'] = esc_html__('Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_demo_member_hint'] = esc_html__('Please select a user for member login', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_demo_agency'] = esc_html__('Agency', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_demo_agency_hint'] = esc_html__('Please select a user for agency login', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_enquires_arrange_viewings'] = esc_html__('Enquiries/Arrange Viewings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_enquires_arrange_viewings_status'] = esc_html__('Enquiries/Arrange Viewings Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_enquires_arrange_viewings_status_desc'] = esc_html__('Add Enquiries/Arrange Viewings Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_fields_download'] = esc_html__('Download', 'wp-dp');

            /*
             * Plugin Settings
             */
            $wp_dp_static_text['wp_dp_plugin_seetings_dp_settings'] = esc_html__('Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_add_package'] = esc_html__('Add Package', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_title'] = esc_html__('Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_action'] = esc_html__('Actions', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_package_settings'] = esc_html__('Package Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_package_title'] = esc_html__('Package Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_package_price'] = esc_html__('Price', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_package_price_hint'] = esc_html__('Enter price here.', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_package_type'] = esc_html__('Package Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_package_type_single'] = esc_html__('Single Submission', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_package_type_subcription'] = esc_html__('Subscription', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_num_of_listings'] = esc_html__('No of Listings in Package', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_num_of_cvs'] = esc_html__('No of CV\'s', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_package_expiray'] = esc_html__('Package Expiry', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_package_day'] = esc_html__('Days', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_package_months'] = esc_html__('Months', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_package_years'] = esc_html__('Years', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_listing_expiray'] = esc_html__('Listings Expiry', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_listing_days'] = esc_html__('Days', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_listing_months'] = esc_html__('Months', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_listing_years'] = esc_html__('Years', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_package_feature'] = esc_html__('Package Featured', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_package_feature_no'] = esc_html__('No', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_package_feature_yes'] = esc_html__('Yes', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_package_desc'] = esc_html__('Description', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_add_package_to_list'] = esc_html__('Add Package to List', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_price_edit'] = esc_html__('Price edit', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_price_edit_hint'] = esc_html__('Enter price here', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_update_package'] = esc_html__('Update Package', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_alrady_exists_error'] = esc_html__('This feature "%s" is already exist. Please create with another Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_extrs_feature_setting'] = esc_html__('Extra Feature Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_extra_feature_title'] = esc_html__('Extra Feature Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_extra_feature_price'] = esc_html__('Price', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_extra_feature_type'] = esc_html__('Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_extra_feature_type_none'] = esc_html__('None', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_extra_feature_type_one_time'] = esc_html__('One Time', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_extra_feature_type_daily'] = esc_html__('Daily', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_extra_feature_guests'] = esc_html__('Guests', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_extra_feature_guests_none'] = esc_html__('None', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_extra_feature_guests_per_head'] = esc_html__('Per head', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_extra_feature_guests_group'] = esc_html__('Group', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_frontend_changeable'] = esc_html__('Frontend Changeable', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_extra_feature_description'] = esc_html__('Description', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_extra_feature_update'] = esc_html__('Update Extra Feature', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_feature_stng'] = esc_html__('Feature Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_feature_titl'] = esc_html__('Feature Title', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_feature_image'] = esc_html__('Image', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_upd_feature'] = esc_html__('Update Feature', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_extra_feature_please_wait'] = esc_html__('Please wait...', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_extra_feature_load_icon'] = esc_html__('Load Icon', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_extra_feature_try_again'] = esc_html__('Try Again', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_seetings_extra_feature_load_json'] = esc_html__('Load Json', 'wp-dp');

            /*
             * clasas ordere detail
             */
            $wp_dp_static_text['wp_dp_enquiry_detail_mark_enquiry_read'] = esc_html__('Mark Message Read', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_mark_as_read'] = esc_html__('Mark as Read', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_mark_as_unread'] = esc_html__('Mark as Unread', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_mark_enquiry_unread'] = esc_html__('Mark Message Unread', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_procceing'] = esc_html__('Processing', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_completed'] = esc_html__('Completed', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_closed'] = esc_html__('Closed', 'wp-dp');
            $wp_dp_static_text['wp_dp_order_detail_orders'] = esc_html__('Orders', 'wp-dp');
            $wp_dp_static_text['wp_dp_order_detail_order'] = esc_html__('Order', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_enquires'] = esc_html__('Messages', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_enquiry'] = esc_html__('Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_created'] = esc_html__('Created', 'wp-dp');
            $wp_dp_static_text['wp_dp_order_detail_req_delievery'] = esc_html__('Req. Delivery:', 'wp-dp');
            $wp_dp_static_text['wp_dp_order_detail_created'] = esc_html__('Created:', 'wp-dp');
            $wp_dp_static_text['wp_dp_order_detail_type'] = esc_html__('Type :', 'wp-dp');
            $wp_dp_static_text['wp_dp_order_detail_paymeny_status'] = esc_html__('Payment Status:', 'wp-dp');
            $wp_dp_static_text['wp_dp_order_detail_order_detail'] = esc_html__('Order Detail', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_inquiry_detail'] = esc_html__('Message Detail', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_custom_detail'] = esc_html__('Customer Detail', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_custom_name'] = esc_html__('Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_custom_phone_num'] = esc_html__('Phone Number', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_custom_email'] = esc_html__('Email', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_custom_address'] = esc_html__('Address', 'wp-dp');
            $wp_dp_static_text['wp_dp_order_detail_service_name'] = esc_html__('Service Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_order_detail_quantity'] = esc_html__('Quantity', 'wp-dp');
            $wp_dp_static_text['wp_dp_order_detail_net'] = esc_html__('Net', 'wp-dp');
            $wp_dp_static_text['wp_dp_order_detail_amount'] = esc_html__('Amount', 'wp-dp');
            $wp_dp_static_text['wp_dp_order_detail_total'] = esc_html__('Total', 'wp-dp');
            $wp_dp_static_text['wp_dp_order_detail_order_status'] = esc_html__('Order Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_enquiry_status'] = esc_html__('Message Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_order_detail_ur_order_completed'] = esc_html__('Your order is completed', 'wp-dp');
            $wp_dp_static_text['wp_dp_order_detail_closed_order'] = esc_html__('Close Order.', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_ur_enquiry_completed'] = esc_html__('Your enquiry is completed.', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_closed_enquiry'] = esc_html__('Close Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_order_detail_ur_order_is'] = esc_html__('Your order is ', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_ur_enquiry_is'] = esc_html__('Your enquiry is ', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_post_ur_msg'] = esc_html__('Post Your Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_message_here'] = esc_html__('Message here...', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_send_msg'] = esc_html__('Send Message', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_name'] = __('Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_phone'] = __('Phone Number', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_email'] = __('Email', 'wp-dp');
            $wp_dp_static_text['wp_dp_enquiry_detail_msg'] = __('Message', 'wp-dp');

            /*
             * listings php
             */
            $wp_dp_static_text['wp_dp_listing_php_tag_name'] = esc_html__('Listing Tags', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_tag_singular_name'] = esc_html__('Tag', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_tag_search_item'] = esc_html__('Tags', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_tag_all_item'] = esc_html__('All Tags', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_tag_prent_iem'] = esc_html__('Parent Tag', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_tag_edit_item'] = esc_html__('Edit Tag', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_tag_update_item'] = esc_html__('Update Tag', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_tag_add_new_item'] = esc_html__('Add New Tag', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_tag_new_item_name'] = esc_html__('New Tag Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_tag_menu_name'] = esc_html__('Listing Tags', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_error_svng_file'] = esc_html__('Error saving file!', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_bkup_generated'] = esc_html__('Backup Generated', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_file_del_successfully'] = esc_html__('File "%s" Deleted Successfully', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_error_deleting_file'] = esc_html__('Error Deleting file!', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_file_restore_success'] = esc_html__('File "%s" Restored Successfully', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_error_restoring_file'] = esc_html__('Error Restoring file!', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_total_ads'] = esc_html__('Total Ads', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_active_ads'] = esc_html__('Active Ads', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_expire_ads'] = esc_html__('Expire ads', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_free_ads'] = esc_html__('Free Ads', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_paid_ads'] = esc_html__('Paid Ads', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_file_import_successfully'] = esc_html__('File Imported Successfully', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_add'] = esc_html__('DB : Banner Ads', 'wp-dp');
            $wp_dp_static_text['wp_dp_banner_add_desc'] = esc_html__('WP Directorybox Manager Banner Ads', 'wp-dp');
            $wp_dp_static_text['wp_dp_locations_widget'] = esc_html__('DB : Locations', 'wp-dp');
            $wp_dp_static_text['wp_dp_locations_menu_name'] = esc_html__('Locations', 'wp-dp');
            $wp_dp_static_text['wp_dp_locations_widget_desc'] = esc_html__('WP Directorybox Manager Locations', 'wp-dp');
            $wp_dp_static_text['wp_dp_top_listings_widget'] = esc_html__('DB : Top Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_top_listings_widget_desc'] = esc_html__('To show Top Listings in widget.', 'wp-dp');
            $wp_dp_static_text['wp_dp_top_listings_widget_sorry'] = esc_html__('Sorry', 'wp-dp');
            $wp_dp_static_text['wp_dp_top_listings_widget_dosen_match'] = esc_html__('No Listing match your search criteria.', 'wp-dp');
            $wp_dp_static_text['wp_dp_social_login_error'] = esc_html__('ERROR', 'wp-dp');
            $wp_dp_static_text['wp_dp_social_login_reg_disable'] = esc_html__('Registration is disabled.', 'wp-dp');
            $wp_dp_static_text['wp_dp_social_login_smthng_went_wrong'] = esc_html__('Something went wrong: %s', 'wp-dp');
            $wp_dp_static_text['wp_dp_social_login_login_error'] = esc_html__('Login error', 'wp-dp');
            $wp_dp_static_text['wp_dp_social_login_google_connect'] = esc_html__('This Google profile is already linked with other account. Linking process failed!', 'wp-dp');
            $wp_dp_static_text['wp_dp_search_filter_min_price'] = esc_html__('Min Price', 'wp-dp');
            $wp_dp_static_text['wp_dp_search_filter_max_price'] = esc_html__('Max Price', 'wp-dp');
            $wp_dp_static_text['wp_dp_search_filter_any_price'] = esc_html__('Any Price', 'wp-dp');
            $wp_dp_static_text['wp_dp_search_filter_listing_type'] = esc_html__('Listing Type', 'wp-dp');

            /*
             * member dashboard
             */
            $wp_dp_static_text['wp_dp_member_dashboard_dashboards_name'] = esc_html__('Members Dashboard', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_listing_mont_statics'] = esc_html__('Listing Monthly Statistic', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_top_member'] = esc_html__('Top  %d Members', 'wp-dp');
            $wp_dp_static_text['wp_dp_tp_members_widget_name'] = esc_html__('DB : Top Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_tp_members_widget_desc'] = esc_html__('To show Top Member in widget', 'wp-dp');
            $wp_dp_static_text['wp_dp_popular_member_widget_name'] = esc_html__('DB : Popular Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_popular_member_widget_desc'] = esc_html__('To show Popular Member in widget', 'wp-dp');
            $wp_dp_static_text['wp_dp_popular_member_widget_choose_member'] = esc_html__('Memebers', 'wp-dp');
            $wp_dp_static_text['wp_dp_popular_member_widget_choose_member_desc'] = esc_html__('Choose popular member from this dropdown.', 'wp-dp');
            $wp_dp_static_text['wp_dp_tp_members_widget_listings'] = esc_html__('Listing(s)', 'wp-dp');
            $wp_dp_static_text['wp_dp_tp_members_widget_sorry'] = esc_html__('Sorry', 'wp-dp');
            $wp_dp_static_text['wp_dp_tp_members_widget_no_member_match'] = esc_html__('No Member match your search criteria', 'wp-dp');
            $wp_dp_static_text['wp_dp_form_fields_brows'] = esc_html__('Browse', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_type_address'] = esc_html__('Type Your Address', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_latitude'] = esc_html__('Latitude', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_longitude'] = esc_html__('Longitude', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_search_loc'] = esc_html__('Search Location', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_prvd_name'] = esc_html__('Please provide name', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_prvd_email'] = esc_html__('Please provide email address', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_prvd_vlid_email'] = esc_html__('Please provide valid email address', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_added_success'] = esc_html__('Branch added successfully', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_update_success'] = esc_html__('Branch updated successfully', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_name'] = esc_html__('Name', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_phone'] = esc_html__('Phone', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_email'] = esc_html__('Email', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_add'] = esc_html__('Add', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_update'] = esc_html__('Update', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_cancel'] = esc_html__('Cancel', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_removed_success'] = esc_html__('Branch Successfully Removed', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_branches'] = esc_html__('Branches', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_branches_add_branches'] = esc_html__('Add Branches', 'wp-dp');
            $wp_dp_static_text['wp_dp_tp_members_find_on_map'] = esc_html__('Find On Map', 'wp-dp');
            $wp_dp_static_text['wp_dp_tp_members_drag_drop_pin'] = esc_html__('For the precise location, you can drag and drop the pin.', 'wp-dp');
            $wp_dp_static_text['wp_dp_tp_members_ad_branch'] = esc_html__('Add Branch', 'wp-dp');
            $wp_dp_static_text['wp_dp_tp_members_upd_branch'] = esc_html__('Update Branch', 'wp-dp');
            $wp_dp_static_text['wp_dp_members_company_upload'] = esc_html__('Upload', 'wp-dp');
            $wp_dp_static_text['wp_dp_members_company_update'] = esc_html__('Update', 'wp-dp');
            $wp_dp_static_text['wp_dp_members_company_update_team_member'] = esc_html__('Update Team Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_wp'] = esc_html__('WP DB', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_alerts_email'] = esc_html__('Alerts / Emails', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_role_title'] = esc_html__('Directory Box Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_listing_type'] = esc_html__('Listing Type', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_serveice_addeed'] = esc_html__('Service added to list.', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_floor_plan_added'] = esc_html__('Floor plan added to list.', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_near_by_added'] = esc_html__('Nearby added to list.', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_attachment_added'] = esc_html__('Attachment added to list.', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_apartment_added'] = esc_html__('Apartment added to list.', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_off_day_added'] = esc_html__('Off day added to list.', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_buy_exists_pkg'] = esc_html__('Use existing package', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_buy_new_pkg'] = esc_html__('Buy new package', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_off_day_already_added'] = esc_html__('This date is already added in off days list.', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_upload_images'] = esc_html__('Please upload images only', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_action_error'] = esc_html__('Error! There is some Problem.', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_compulsory_fields'] = esc_html__('Please fill the compulsory fields first.', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_payment_text'] = esc_html__('Review', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_sbmit_order'] = esc_html__('Submit Order', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_update_text'] = esc_html__('Update', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_create_list_text'] = esc_html__('Add Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_listing_save_preview'] = esc_html__('Save & Proceed', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_listing_updated'] = esc_html__('Listing Updated', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_listing_created'] = esc_html__('Listing Created.', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_valid_price_error'] = esc_html__('Please enter valid price.', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_detail_text'] = esc_html__('Detail', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_close_text'] = esc_html__('Close', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_accept'] = esc_html__('Accept', 'wp-dp');
            $wp_dp_static_text['wp_dp_dp_terms_and_conditions'] = esc_html__('Terms and conditions', 'wp-dp');

            /*
             * listingsearch-list-filter
             */
            $wp_dp_static_text['wp_dp_listing_search_flter_find_not_found'] = esc_html__('No matches found', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_flter_find'] = esc_html__('Find', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_flter_location_near'] = esc_html__('Near', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_flter_wt_looking_for'] = esc_html__('Anywhere', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_flter_saerch'] = esc_html__('Search', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_flter_min_price'] = esc_html__('Min Price', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_flter_max_price'] = esc_html__('Max Price', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_flter_all'] = esc_html__('All', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_flter_per_weak'] = esc_html__('Per Week', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_flter_per_caleder'] = esc_html__('Per Calendar Month', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_flter_price_options'] = esc_html__('Price Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_flter_advanced'] = esc_html__('Advanced', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_more_filters'] = esc_html__('More Filters', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_flter_wt_keyword'] = esc_html__('What is Keyword search?', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_slider_sorry'] = esc_html__('Sorry', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_grid_listed_on'] = esc_html__('Listed on', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_hidden_heading'] = esc_html__('You have hidden %s listing on this page', 'wp-dp');
            $wp_dp_static_text['wp_dp_listings_hidden_heading'] = esc_html__('You have hidden %s listings on this page', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_grid_by'] = esc_html__('by', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_slider_doesn_match'] = esc_html__('There are no listings matching your search.', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_no_results'] = esc_html__('No Results', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_change_your_filter'] = esc_html__('Try Changing your search filters', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_or'] = esc_html__('or', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_search_reset_filter'] = esc_html__('Reset Filter', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_featrd'] = esc_html__('featured', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_hide'] = esc_html__('Hide Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_hidden'] = esc_html__('Hidden Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_leftflter_listing_type'] = esc_html__('Listing Types', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_leftflter_all_listings'] = esc_html__('All Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_leftflter_min'] = esc_html__('Min', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_leftflter_max'] = esc_html__('Max', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_leftflter_all'] = esc_html__('All', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_leftflter_per_weak'] = esc_html__('Per Week', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_leftflter_per_calender_month'] = esc_html__('Per Calendar Month', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_leftflter_price_options'] = esc_html__('Price Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_leftflter_price'] = esc_html__('Price', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_leftflter_fron_date'] = esc_html__('From Date', 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_leftflter_to_date'] = esc_html__('To Date', 'wp-dp');

            /*
             * skrill
             */
            $wp_dp_static_text['wp_dp_skrill_options_on'] = esc_html__('on', 'wp-dp');
            $wp_dp_static_text['wp_dp_skrill_options_off'] = esc_html__('off', 'wp-dp');
            $wp_dp_static_text['wp_dp_skrill_money_booker_stng'] = esc_html__('Skrill-MoneyBooker Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_skrill_cistom_logo'] = esc_html__('Custom Logo', 'wp-dp');
            $wp_dp_static_text['wp_dp_skrill_cistom_logo_hint'] = esc_html__('Browse custom logo image for "Skrill Account" which is uploaded in media gallery.', 'wp-dp');

            $wp_dp_static_text['wp_dp_skrill_default_status'] = esc_html__('Default Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_skrill_default_status_hint'] = esc_html__('If this switch will be OFF, no payment will be processed via Skrill-MoneyBooker', 'wp-dp');
            $wp_dp_static_text['wp_dp_skrill_business_email'] = esc_html__('Skrill-MoneryBooker Business Email', 'wp-dp');
            $wp_dp_static_text['wp_dp_skrill_business_email_hint'] = esc_html__('Add your business Email address here to proceed Skrill-MoneryBooker payments..', 'wp-dp');
            $wp_dp_static_text['wp_dp_skrill_ipn_url'] = esc_html__('Skrill-MoneryBooker Ipn Url', 'wp-dp');
            $wp_dp_static_text['wp_dp_skrill_ipn_url_hint'] = esc_html__('Here you can add your Skrill-MoneryBooker IPN URL', 'wp-dp');
            $wp_dp_static_text['wp_dp_skrill_redirecting_to_pg'] = esc_html__('Redirecting to payment gateway website...', 'wp-dp');
            $wp_dp_static_text['wp_dp_paypal_options_on'] = esc_html__('on', 'wp-dp');
            $wp_dp_static_text['wp_dp_paypal_options_off'] = esc_html__('off', 'wp-dp');
            $wp_dp_static_text['wp_dp_paypal_settings'] = esc_html__('Paypal Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_paypal_custom_logo'] = esc_html__('Custom Logo', 'wp-dp');
            $wp_dp_static_text['wp_dp_paypal_custom_logo_hint'] = esc_html__('Browse custom logo image for "Paypal Account" which is uploaded in media gallery.', 'wp-dp');

            $wp_dp_static_text['wp_dp_paypal_default_status'] = esc_html__('Default Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_paypal_default_status_hint'] = esc_html__('If this switch will be OFF, no payment will be processed via Paypal. ', 'wp-dp');
            $wp_dp_static_text['wp_dp_paypal_sandbox'] = esc_html__('Paypal Sandbox', 'wp-dp');
            $wp_dp_static_text['wp_dp_paypal_sandbox_hint'] = esc_html__('Control PayPal sandbox Account with this switch. If this switch is set to ON, payments will be  proceed with sandbox account.', 'wp-dp');
            $wp_dp_static_text['wp_dp_paypal_business_email'] = esc_html__('Paypal Business Email', 'wp-dp');
            $wp_dp_static_text['wp_dp_paypal_business_email_hint'] = esc_html__('Add your business Email address here to proceed PayPal payments.', 'wp-dp');
            $wp_dp_static_text['wp_dp_paypal_ipn_url'] = esc_html__('Paypal Ipn Url', 'wp-dp');
            $wp_dp_static_text['wp_dp_skrill_ipn_url_hint'] = esc_html__('Here you can add your PayPal IPN URL', 'wp-dp');
            $wp_dp_static_text['wp_dp_skrill_redirect_to_pg'] = esc_html__('Redirecting to payment gateway website...', 'wp-dp');
            $wp_dp_static_text['wp_dp_payment_bace_currency'] = esc_html__('Base Currency', 'wp-dp');
            $wp_dp_static_text['wp_dp_payment_base_currency_hint'] = esc_html__('All the transactions will be placed in this currency.', 'wp-dp');
            $wp_dp_static_text['wp_dp_notifications_delete_permanently'] = esc_html__('Delete this item permanently', 'wp-dp');
            $wp_dp_static_text['wp_dp_notifications_row_delete'] = esc_html__('Delete', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_change_pass'] = esc_html__('Change Password', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_my_profile'] = esc_html__('My Profile', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_Dashboard'] = esc_html__('Dashboard', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_my_prop'] = esc_html__('My Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_orders'] = esc_html__('Orders', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_enquires'] = esc_html__('Messages', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_alerts_searches'] = esc_html__('Email Alerts', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_fav_prop'] = esc_html__('Favourite Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_packages'] = esc_html__('Packages', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_account_stng'] = esc_html__('Profile Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_team_members'] = esc_html__('Team Members', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_signout'] = esc_html__('Sign out', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_profile_not_active'] = esc_html__('Your profile status is not active. All of your listing ads will not activate until your profile status will not active.', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_want_to_profile'] = esc_html__('You Want To Delete?', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_delete_yes'] = esc_html__('Yes, Delete', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_delete_no'] = esc_html__('No, Cancel', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_daily'] = esc_html__('Daily', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_weekly'] = esc_html__('Weekly', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_fortnightly'] = esc_html__('Fortnightly', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_monthly'] = esc_html__('Monthly', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_biannually'] = esc_html__('Biannually', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_annually'] = esc_html__('Annually', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_never'] = esc_html__('Never', 'wp-dp');
            $wp_dp_static_text['wp_dp_last_login_never'] = esc_html__('never', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_no_email_alerts'] = esc_html__('No Email Alerts', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_summary_email'] = esc_html__('Summary Emails', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_create_email_alerts'] = esc_html__('Create email alert', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_email_save_search'] = esc_html__('Email Alerts / Save this Search', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_filter_criteria'] = esc_html__('Alert Criteria', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_all_listings'] = esc_html__('All Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_enter_valid_email'] = esc_html__('Please enter a valid email', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_alert_name_title'] = esc_html__('Title *', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_email_address'] = esc_html__('Email Address *', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_alert_frequency'] = esc_html__('Alert Frequency', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_submit'] = esc_html__('Submit', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_email_template_desc'] = esc_html__('This template will be used when sending a listing alert, Template will have a list of listings as per user set filters.', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_biannualy2'] = esc_html__('Bi-Annually', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_save_search'] = esc_html__('Set Search Alert', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_unsubcribe'] = esc_html__('Unsubscribe', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_map_view_listings'] = esc_html__('View Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_map_or'] = esc_html__('OR', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_map_submit'] = esc_html__('Submit', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_view_full_listing'] = esc_html__('View Full Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_alerts_listing_found_at'] = esc_html__('New Listings Found at ', 'wp-dp');

            /*
             * favourites.php
             */
            $wp_dp_static_text['wp_dp_favourite_conform_msg'] = esc_html__('Are you sure to do this?', 'wp-dp');
            $wp_dp_static_text['wp_dp_favourite_favourite'] = esc_html__('Favourites', 'wp-dp');
            $wp_dp_static_text['wp_dp_favourite_favourite_manage'] = esc_html__('Favourites Manage', 'wp-dp');
            $wp_dp_static_text['wp_dp_favourite_fav_added'] = esc_html__('Favourite added', 'wp-dp');
            $wp_dp_static_text['wp_dp_favourite_fav_removed'] = esc_html__('Favourite removed', 'wp-dp');
            $wp_dp_static_text['wp_dp_favourite_favourite_ur_listing'] = esc_html__('favourite a listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_favourite_delete_successfully'] = esc_html__('Deleted Successfully', 'wp-dp');
            $wp_dp_static_text['wp_dp_favourite_remove_one_list'] = esc_html__(' removed your listing from favourites.', 'wp-dp');
            $wp_dp_static_text['wp_dp_favourite_dont_have_favourite'] = esc_html__('You don\'t have any favourite listings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_favourite_fav_propert'] = esc_html__('Favourite Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_favourite_dont_hav_fav_propert'] = esc_html__('You don\'t have any favourite listings.', 'wp-dp');

            /*
             * class bank transfer
             */
            $wp_dp_static_text['wp_dp_banktransfer_options_on'] = esc_html__('on', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_options_off'] = esc_html__('off', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_settings'] = esc_html__('Bank Transfer Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_custom_logo'] = esc_html__('Custom Logo', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_custom_logo_hint'] = esc_html__('Browse custom logo image for "Bank Transfer Account" which is uploaded in media gallery.', 'wp-dp');

            $wp_dp_static_text['wp_dp_banktransfer_dfault_status'] = esc_html__('Default Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_dfault_status_hint'] = esc_html__('If this switch will be OFF, no payment will be processed via Bank Transfer', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_bank_info'] = esc_html__('Bank Information', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_bank_info_hint'] = esc_html__('Add information of your bank (Bank Name).', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_account_num'] = esc_html__('Account Number', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_account_num_hint'] = esc_html__('Add your bank account Number where you want receive payment.', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_other_info'] = esc_html__('Other Information', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_other_info_hint'] = esc_html__('In this text box, you can add any help text whatever you want to show on front end for assistance of users regarding bank payment.', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_packages'] = esc_html__('Package', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_charges'] = esc_html__('Charges', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_vat'] = esc_html__('VAT :', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_total_charge'] = esc_html__('Total Charges: ', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_order_detail'] = esc_html__('Order detail', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_order_id'] = esc_html__('Order ID', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_bank_detail'] = esc_html__('Bank detail', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_bank_detail_hint'] = esc_html__('Please transfer amount To this account, After payment Received we will process your Order', 'wp-dp');
            $wp_dp_static_text['wp_dp_banktransfer_account_no'] = esc_html__('Account No', 'wp-dp');

            /*
             * class authorize net
             */
            $wp_dp_static_text['wp_dp_aythorize_option_on'] = esc_html__('on', 'wp-dp');
            $wp_dp_static_text['wp_dp_aythorize_option_off'] = esc_html__('off', 'wp-dp');
            $wp_dp_static_text['wp_dp_aythorize_settings'] = esc_html__('Authorize.net Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_aythorize_custom_logo'] = esc_html__('Custom Logo', 'wp-dp');
            $wp_dp_static_text['wp_dp_aythorize_custom_logo_hint'] = esc_html__('Browse custom logo image for "Authorize Net Account" which is uploaded in media gallery.', 'wp-dp');
            $wp_dp_static_text['wp_dp_aythorize_default_status'] = esc_html__('Default Status', 'wp-dp');
            $wp_dp_static_text['wp_dp_aythorize_default_status_hint'] = esc_html__('If this switch will be OFF, no payment will be processed via Authorize.net', 'wp-dp');
            $wp_dp_static_text['wp_dp_aythorize_sandbox'] = esc_html__('Authorize.net Sandbox', 'wp-dp');
            $wp_dp_static_text['wp_dp_aythorize_sandbox_hint'] = esc_html__('Control Authorize.net sandbox Account with this switch. If this switch is set to ON, payments will be  proceed with sandbox account.', 'wp-dp');
            $wp_dp_static_text['wp_dp_aythorize_login_id'] = esc_html__('Login Id', 'wp-dp');
            $wp_dp_static_text['wp_dp_aythorize_login_id_hint'] = esc_html__('Add your Authorize.net login ID here. You will get it while signing up on Authorize.net.', 'wp-dp');
            $wp_dp_static_text['wp_dp_aythorize_trans_key'] = esc_html__('Transaction Key', 'wp-dp');
            $wp_dp_static_text['wp_dp_aythorize_trans_key_hint'] = esc_html__('Add your Authorize.net Transaction Key here. You will get this key while signing up on Authorize.net', 'wp-dp');
            $wp_dp_static_text['wp_dp_aythorize_ipn_url'] = esc_html__('Authorize.net Ipn Url', 'wp-dp');
            $wp_dp_static_text['wp_dp_aythorize_ipn_url_hint'] = esc_html__('Here you can add your Authorize.net IPN URL', 'wp-dp');
            $wp_dp_static_text['wp_dp_aythorize_cancel_order'] = esc_html__('Cancel Order', 'wp-dp');
            $wp_dp_static_text['wp_dp_aythorize_redirect_payment'] = esc_html__('Redirecting to payment gateway website...', 'wp-dp');
            $wp_dp_static_text['wp_dp_listings_detail_view'] = esc_html__('Photos', 'wp-dp');
            $wp_dp_static_text['wp_dp_listings_detail_location'] = esc_html__('Map View', 'wp-dp');
            $wp_dp_static_text['wp_dp_listings_detail_street_view'] = esc_html__('Street View', 'wp-dp');
            $wp_dp_static_text['wp_dp_demo_user_not_allowed_to_modify'] = esc_html__('Demo users are not allowed to modify information.', 'wp-dp');

            // Settings Listing Detail Page Views
            $wp_dp_static_text['wp_dp_options_listing_detail_views'] = esc_html__('Listing detail page view', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_listing_detail_views_hint'] = esc_html__('If "Page Style" from the listing detail page settings is set to "Default view", and from the listing\'s type settings view  is also selected as "Default view" than the view selected in this field will be applied to that listing.', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_listing_detail_top_map'] = esc_html__('Top Map', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_listing_detail_top_slider'] = esc_html__('Top Slider', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_listing_detail_content_gallery'] = esc_html__('Content Gallery', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_listing_detail_content_bottom_member_info'] = esc_html__('Content Bottom Member Info', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_listing_detail_sidebar_map'] = esc_html__('Sidebar Map', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_listing_detail_sidebar_gallery'] = esc_html__('Sidebar Gallery', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_listing_detail_sidebar_member_info'] = esc_html__('Sidebar Member Info', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_listing_detail_sidebar_mortgage_calculator'] = esc_html__('Sidebar Mortgage Payment Calculator', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_listing_detail_sidebar_opening_hours'] = esc_html__('Sidebar Opening Hours', 'wp-dp');

            //hidden listings
            $wp_dp_static_text['wp_dp_hidden_dont_hav_hidden_propert'] = esc_html__('You don\'t have any hidden listings.', 'wp-dp');
            /*
             * Currencies
             */
            $wp_dp_static_text['wp_dp_plugin_options_currencies'] = esc_html__('Currencies', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_currency_settings'] = esc_html__('Currency Settings', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_price_options'] = esc_html__('Price Options', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_currency_symbol'] = esc_html__('Currency Symbol e.g. &#163;', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_conversion_rate'] = esc_html__('Conversion Rate e.g. 0.80', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_currency_name'] = esc_html__('Currency Name e.g. Pound', 'wp-dp');


            $wp_dp_static_text['wp_dp_options_feature_icon'] = esc_html__('Feature Icon', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_feature_label'] = esc_html__('Feature Label', 'wp-dp');
            $wp_dp_static_text['wp_dp_package_label'] = esc_html__('Package Label', 'wp-dp');
            $wp_dp_static_text['wp_dp_package_type'] = esc_html__('Package Type', 'wp-dp');

            $wp_dp_static_text['wp_dp_shortcode_listings_categories'] = esc_html__('Categories', 'wp-dp');
            $wp_dp_static_text['wp_dp_shortcode_filter_prev'] = esc_html__('Prev', 'wp-dp');
            $wp_dp_static_text['wp_dp_shortcode_filter_next'] = esc_html__('Next', 'wp-dp');

            $wp_dp_static_text['wp_dp_compare_label'] = esc_html__('Compare', 'wp-dp');
            $wp_dp_static_text['wp_dp_compared_label'] = esc_html__('Compared', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_cannot_add_to_list'] = esc_html__('Sorry! cannot add to compare list', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_already_compared'] = esc_html__('Already compared', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_add_to_compare'] = esc_html__('Compare', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_added_successfully_to'] = esc_html__('Added successfully to', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_added_to_compare'] = esc_html__('Added to compare', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_clear_list'] = esc_html__('Clear List', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_compare_list'] = esc_html__('compare list', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_added_successfully_to_compare_list'] = esc_html__('Added successfully to compare list.', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_already_have_listings'] = esc_html__('Compare list already have 3 listings for this listing type.', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_removed_from_compare_list'] = esc_html__('Removed from compare list.', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_was_removed_from_compare'] = esc_html__('Successfully removed', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_remove_to_compare'] = esc_html__('Remove Compare', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_shortcode_heading'] = esc_html__('DB: Listings Compare', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_compare_list_is_empty'] = esc_html__('Please add at least two item in list then try it. %s', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_basic_info'] = esc_html__('Basic Info', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_vs'] = esc_html__('VS', 'wp-dp');
            $wp_dp_static_text['wp_dp_compare_click_here'] = esc_html__('Click here', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_compare_listings'] = esc_html__('Compare Listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_compare_switch'] = esc_html__('Compare', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_compare_switch_label_desc'] = esc_html__('Turn this switch "ON" to show compare listings button otherwise switch it off.', 'wp-dp');
            $wp_dp_static_text['wp_dp_shortcode_compare_switch'] = esc_html__('Compare Switch', 'wp-dp');
            $wp_dp_static_text['wp_dp_shortcode_compare_error'] = esc_html__('Error', 'wp-dp');
            $wp_dp_static_text['wp_dp_shortcode_compare_label'] = esc_html__('Compare', 'wp-dp');
            $wp_dp_static_text['wp_dp_shortcode_compared_label'] = esc_html__('Compared', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_email_logs'] = esc_html__('Email Logs', 'wp-dp');
            $wp_dp_static_text['wp_dp_options_enable_disable_email_logs'] = esc_html__('Enable/Disable sent email logs', 'wp-dp');
            $wp_dp_static_text['wp_dp_register_login_demo_user'] = esc_html__('Demo User', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_demo_user'] = esc_html__('Demo User', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_demo_user_hint'] = esc_html__('Please select a user for demo login', 'wp-dp');
            $wp_dp_static_text['wp_dp_payment_woocommerce_enable'] = esc_html__('Woocommerce currency options are being used, please use the settings page for woocommerce to modify currency settings.', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_add_listing'] = esc_html__('Create Listing', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_select_member'] = esc_html__('Select Member', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_order_options'] = esc_html__('Order Details', 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_amount'] = esc_html__('Amount', 'wp-dp');
            $wp_dp_static_text['wp_dp_split_map_filter_price_range'] = esc_html__('Price Range', 'wp-dp');
            $wp_dp_static_text['wp_dp_split_map_filter_heading_list_result_for'] = esc_html__('Results For ', 'wp-dp');
            $wp_dp_static_text['wp_dp_list_view_listings_found'] = esc_html__('Listings Found', 'wp-dp');
            $wp_dp_static_text['wp_dp_list_view_listing_found'] = esc_html__('Listing Found', 'wp-dp');
            $wp_dp_static_text['wp_dp_split_map_filter_heading_list'] = esc_html__('%s listings', 'wp-dp');
            $wp_dp_static_text['wp_dp_member_dashboard_promoted_listing'] = esc_html__("Promotions", 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_promoted_listings_not_found'] = esc_html__("You don't have any Promoted listings", 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_search_by_notif_owner'] = esc_html__("Notification Owner", 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_search_by_list_owner'] = esc_html__("Listing Owner", 'wp-dp');
            $wp_dp_static_text['wp_dp_dashboard_notif_title'] = esc_html__("Notification", 'wp-dp');
            $wp_dp_static_text['wp_dp_iso_code_title'] = esc_html__("ISO Code", 'wp-dp');
            $wp_dp_static_text['wp_dp_viewing_member_filter'] = esc_html__("Search for a Viewing Member...", 'wp-dp');
            $wp_dp_static_text['wp_dp_promotion_member_filter'] = esc_html__("Search by Member...", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_date_col_title'] = esc_html__("Date", 'wp-dp');
            $wp_dp_static_text['wp_dp_promotions_date_col_title'] = esc_html__("Date", 'wp-dp');
            $wp_dp_static_text['wp_dp_package_order_date_ago'] = esc_html__("ago", 'wp-dp');
            $wp_dp_static_text['wp_dp_transaction_column_status'] = esc_html__("Status", 'wp-dp');
            $wp_dp_static_text['wp_dp_notif_status_pending'] = esc_html__("Pending", 'wp-dp');
            $wp_dp_static_text['wp_dp_notif_status_process'] = esc_html__("In Process", 'wp-dp');
            $wp_dp_static_text['wp_dp_notif_status_approved'] = esc_html__("Approved", 'wp-dp');
            $wp_dp_static_text['wp_dp_notif_status_cancelled'] = esc_html__("Cancelled", 'wp-dp');
            $wp_dp_static_text['wp_dp_notif_select_status'] = esc_html__("Select Status", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_row_action_delete'] = esc_html__("Delete", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_row_action_content'] = esc_html__("Content", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_detail_pop_title'] = esc_html__("Transaction Detail", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_pop_promotions'] = esc_html__("Promotions", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_pop_promotion_title'] = esc_html__("Title:", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_pop_promotion_price'] = esc_html__("Amount:", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_pop_promotion_price_free'] = esc_html__("Free", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_pop_promotion_duration'] = esc_html__("Duration", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_pop_summary_title'] = esc_html__("Summary", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_pop_order_type'] = esc_html__("Order Type", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_pop_order_owner'] = esc_html__("Order Owner", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_pop_price'] = esc_html__("Price", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_pop_total_amount'] = esc_html__("Total Amount", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_pop_amount2'] = esc_html__("Amount", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_pop_payment_gateway'] = esc_html__("Payment Gateway", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_pop_status'] = esc_html__("Status", 'wp-dp');
            $wp_dp_static_text['wp_dp_trans_pop_listing'] = esc_html__("Listing", 'wp-dp');

            $wp_dp_static_text['wp_dp_promotions_total'] = esc_html__("Total", 'wp-dp');
            $wp_dp_static_text['wp_dp_promotions_pending'] = esc_html__("Pending", 'wp-dp');
            $wp_dp_static_text['wp_dp_promotions_approved'] = esc_html__("Approved", 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_listing_categories_menu_name'] = esc_html__("Listing Categories", 'wp-dp');
            $wp_dp_static_text['wp_dp_listing_php_tag_menu_name'] = esc_html__("Listing Tags", 'wp-dp');

            $wp_dp_static_text['wp_dp_class_listing_alert_col_date'] = esc_html__('Date', 'wp-dp');
            
            
            $wp_dp_static_text['wp_dp_plugin_options_api_setting_ip_geolocation'] = esc_html__('IP Geolocation', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_ip_geolocation_api'] = esc_html__('IP Geolocation API Key', 'wp-dp');
            $wp_dp_static_text['wp_dp_plugin_options_ip_geolocation_api_hint'] = esc_html__('Add IP Geolocation API Key. To get your API key, go to https://ipstack.com/', 'wp-dp');

            return $wp_dp_static_text;
        }

    }

    new wp_dp_plugin_all_strings_2;
}
