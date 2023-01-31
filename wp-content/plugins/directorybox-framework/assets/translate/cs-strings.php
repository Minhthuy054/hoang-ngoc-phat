<?php

/**
 * @Text which is being used in Framework
 *
 */
if ( ! function_exists('wp_dp_cs_var_frame_text_srt') ) {

    function wp_dp_cs_var_frame_text_srt($str = '') {
        global $wp_dp_cs_var_frame_static_text;
        if ( isset($wp_dp_cs_var_frame_static_text[$str]) ) {
            return $wp_dp_cs_var_frame_static_text[$str];
        }
    }

}
if ( ! class_exists('wp_dp_cs_var_frame_all_strings') ) {

    class wp_dp_cs_var_frame_all_strings {

        public function __construct() {
            /*
             * Triggering function for strings.
             */
            add_action('init', array( $this, 'wp_dp_cs_var_frame_all_string_all' ), 0);
        }

        function wp_dp_cs_var_login_strings() {
            global $wp_dp_cs_var_frame_static_text;
            /*
             * Sign Up
             * Sign In
             * Forget Password
             * */
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_join_us'] = __(' Register', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_confirm_password'] = __('CONFIRM PASSWORD ', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_user_registration'] = __('User Registration is disabled', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_you_have_already_logged_in'] = __(' You have already logged in, Please logout to try again.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_please_logout_first'] = __('Please logout first then try to login again', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_user_login'] = __('User Login', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_username'] = __('USERNAME', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_username_small'] = __('username', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_password'] = __('PASSWORD', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_log_in'] = __('Login', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_forgot_password'] = __('Forgot Password', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_new_to_us'] = __('New to Us?', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_signup_signin'] = __('Signup / Signin with', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_desired_username'] = __('Type desired username', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_phone'] = __('Phone Number', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_phone_hint'] = __('Enter Phone Number', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_register_here'] = __('Register Here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_create_account'] = __('Create Account', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_not_member_yet'] = __('Not a Member yet?', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_Sign_up_now'] = __('Sign Up Now', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_or'] = __('Or', 'wp-dp-frame');


            //$wp_dp_cs_var_frame_static_text['wp_dp_cs_var_sign_in'] = __('Log in', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_sign_in'] = __('SIGN IN', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_password_should_not_be_empty'] = __('Password should not be empty', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_password_should_not_match'] = __('Password Not Match', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_already_have_account'] = __(' Already have an account', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_login_now'] = __(' Login Now', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_user_sign_in'] = __('User Sign in', 'wp-dp-frame');

            /*
             *  Login File
             * */
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_register_options'] = __('User Registration Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_set_api_key'] = __('Please set API key', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_signin_with_your_Social_networks'] = __('Signin with your Social Networks', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_google'] = __('google', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_linkedin'] = __('Linkedin', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_linkedin_title'] = __('twitter', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_send_email'] = __('Send Email', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_login_here'] = __('Login Here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_enter_email_address'] = __('Enter E-Mail address...', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_signup_now'] = __('Sign up Now', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_password_recovery'] = __('Password Recovery', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_oops_something_went_wrong_updating_your_account'] = __('Oops something went wrong updating your account', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_check_your_email_address_for_new_password'] = __('Check your email for your new password.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_ur_request_has_been_completed_succssfully'] = __('Your request has been completed succssfully, Now you can use following information for login.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_your_new_password'] = __('Your new password', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_your_username_is'] = __('Your username is:', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_your_new_password_is'] = __('Your new password is:', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_from'] = __('From:', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_there_is_no_user_registered'] = __('There is no user registered with that email address.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_invalid_email_address'] = __('Invalid e-mail address.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_username_should_not_be_empty'] = __('User name should not be empty.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_password_should_not_be_empty.'] = __('Password should not be empty.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_email_should_not_be_empty'] = __('Email should not be empty.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_wrong_username_or_password'] = __('Wrong username or password.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_login_successfully'] = __('Login Successfully...', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_valid_username'] = __('Please enter a valid username. You can only enter alphanumeric value and only ( _ ) longer than or equals 5 chars', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_valid_email'] = __('Please enter a valid email.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_user_already_exists'] = __('User already exists. Please try another one.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_user_registration_detail'] = __('User registration Detail', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_check_email'] = __('Please check your email for login details', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_successfully_registered_with_login'] = __('You have been successfully registered <a href="javascript:void(0);" data-toggle="modal" data-target="#cs-login" data-dismiss="modal" class="cs-color" aria-hidden="true">Login</a>.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_currently_issue'] = __('Currently there are and issue', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_successfully_registered'] = __('Your account has been registered successfully, Please contact to site admin for password.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_captcha_api_key'] = __('Please provide google captcha API keys', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_select_captcha_field'] = __('Please select captcha field.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_reload'] = __('Reload', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_already_linked'] = __('This profile is already linked with other account. Linking process failed!', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_error'] = __('ERROR', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_something_went_wrong'] = __('Something went wrong: %s', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_problem_connecting_to_twitter'] = __(' There is problem while connecting to twitter', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_login_error'] = __('Login error', 'wp-dp-frame');

            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_facebook'] = __('facebook', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_twitter'] = __('twitter', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_google_plus_icon'] = __('google-plus', 'wp-dp-frame');





            return $wp_dp_cs_var_frame_static_text;
        }

        public function wp_dp_cs_var_frame_all_string_all() {

            global $wp_dp_cs_var_frame_static_text;

            /* framework */


            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_add_page_section'] = __('Add Page Sections', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_blog_search'] = __('Blog Search', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_Oops_404'] = __('Oops! That page can’t be found. ', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_nothing_found_404'] = __('It looks like nothing was found at this location. Maybe try a search?. ', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_api_set_msg'] = __('There is an issue in API, Please contact to administrator and try again', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_subscribe_success'] = __('subscribe successfully', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_noresult_found'] = __('No result found.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_comments'] = __('Comments', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_by'] = __('By', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_article_ads'] = __('Article Bottom Banner', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_next'] = __('Next', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_prev'] = __('PREVIOUS', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_tag'] = __('Tags', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_ago'] = __('Ago', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_related_posts'] = __('Related Posts', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_image_edit'] = __('Edit "%s"', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_primary_menu'] = __('Primary Menu', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_social_links_menu'] = __('Social Links Menu', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_widget_display_text'] = __('This widget will be displayed on right/left side of the page.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_widget_display_right_text'] = __('This widget will be displayed on right side of the page.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_footer'] = __('Footer ', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_widgets'] = __('Widgets ', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_search_result'] = __('Search Results : %s', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_author'] = __('Author', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_archives'] = __('Archives', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_packages'] = __('Inventory Packages', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_tweets'] = __('Tweets', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_no_tweets_found'] = __('NO tweets found.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_tweets_time_on'] = __('On', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_daily_archives'] = __('Daily Archives: %s', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_monthly_archives'] = __('Monthly Archives: %s', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_yearly_archives'] = __('Yearly Archives: %s', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_tags'] = __('Tags', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_error_404'] = __('Error 404', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_home'] = __('Home', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_current_page'] = __('Current Page', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_theme_options'] = __('CS Theme Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_previous_page'] = __('Previous page', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_next_page'] = __('Next page', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_previous_image'] = __('Previous Image', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_next_image'] = __('Next Image', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_pages'] = __('Pages:', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page'] = __('Page', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_comments_closed'] = __('Comments are closed.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_reply'] = __('Reply', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_full_width'] = __('Full width', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_sidebar_right'] = __('Sidebar Right', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_sidebar_left'] = __('Sidebar Left', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_sidebar_small_left'] = __('Small Left Sidebar', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_sidebar_small_right'] = __('Small Right Sidebar', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_sidebar_small_left_large_right'] = __('Small Left and Large Right Sidebars', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_sidebar_large_left_small_right'] = __('Large Left and Small Right Sidebars', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_sidebar_both_left'] = __('Both Left Sidebars', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_sidebar_both_right'] = __('Both Right Sidebars', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_delete_image'] = __('Delete image', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_item'] = __('Edit Item', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_description'] = __('Description', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_update'] = __('Update', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_delete'] = __('Delete', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_select_attribute'] = __('Select Attribute', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_ads'] = __('CS : Ads', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_select_image_ads'] = __('Select Image from Ads.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_flickr_gallery'] = __('CS : Flickr Gallery', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_flickr_description'] = __('Type a user name to show photos in widget', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_flickr_username'] = __('Flickr username', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_flickr_username_hint'] = __('Enter your Flicker Username here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_flickr_photos'] = __('Number of Photos', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_error'] = __('Error:', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_flickr_api_key'] = __('Please Enter Flickr API key from Theme Options.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_mailchimp'] = __('CS: Mail Chimp', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_mailchimp_desciption'] = __('Mail Chimp Newsletter Widget', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_description_hint'] = __('Enter discription here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_social_icon'] = __('Social Icon On/Off.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_recent_post'] = __('CS : Recent Posts', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_recent_post_des'] = __('Recent Posts from category.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_choose_category'] = __('Choose Category.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_num_post'] = __('Number of Posts To Display.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_availability'] = __('Availability', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_in_stock'] = __('in stock', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_out_stock'] = __('out of stock', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_wait'] = __('Please wait...', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_load_icon'] = __('Successfully loaded icons', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_try_again'] = __('Error: Try Again?', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_load_json'] = __('Load from IcoMoon selection.json', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_are_sure'] = __('Are you sure! You want to delete this', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_hint'] = __('Please enter text for icon', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_path'] = __('Icon Path', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon'] = __('Icon', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_comment_awaiting'] = __('Your comment is awaiting moderation.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit'] = __('Edit', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_you_may'] = __('You may use these HTML tags and attributes: %s', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_message'] = __('Message', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_view_posts'] = __('View all posts by %s', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_nothing'] = __('Nothing Found', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_ready_publish'] = __('Ready to publish your first post? Get started here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_nothing_match'] = __('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_perhaps'] = __('It seems we can’t find what you’re looking for. Perhaps searching can help.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_you_must'] = __('You must be to post a comment.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_log_out'] = __('Log out', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_log_in'] = __('Logged in as', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_require_fields'] = __('Required fields are marked %s', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_name'] = __('Name *', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_full_name'] = __('full name', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_email'] = __('Email', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_enter_email'] = __('Type your email address', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_website'] = __('Website', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_text_here'] = __('Text here...', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_leave_comment'] = __('Leave us a comment', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_cancel_reply'] = __('Cancel reply', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_comment'] = __('Post comments', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_interested'] = __('I am interested in a price quote on this vehicle. Please contact me at your earliest convenience with your best price for this vehicle.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_dealer'] = __('Dealers Listing', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_option'] = __('Page Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_general_setting'] = __('General Settings', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_subheader'] = __('Subheader', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_choose_subheader'] = __('Choose Sub-Header', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_default_subheader'] = __('Default Subheader', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_custom_subheader'] = __('Custom Subheader', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_rev_slider'] = __('Revolution Slider', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_map'] = __('Map', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_no_subheader'] = __('No Subheader', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_style'] = __('Style', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_classic'] = __('Classic', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_with_image'] = __('With Background Image', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_padding_top'] = __('Padding Top', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_padding_top_mobile'] = __('Padding Top (Mobile)', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_padding_top_hint'] = __('Set padding top here.(In px)', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_padding_bot'] = __('Padding Bottom', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_padding_bot_mobile'] = __('Padding Bottom (Mobile)', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_padding_bot_hint'] = __('Set padding bottom. (In px)', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_margin_top'] = __('Margin Top', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_margin_top_mobile'] = __('Margin Top (Mobile)', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_margin_top_hint'] = __('Set Margin top here.(In px) ', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_margin_bot'] = __('Margin Bottom', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_margin_bot_mobile'] = __('Margin Bottom (Mobile)', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_margin_bot_hint'] = __('Set Margin bottom. (In px)', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_select_layout'] = __('Select Layout', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_title'] = __('Page Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_text_color'] = __('Text Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_text_color_hint'] = __('Provide a hex color code here (with #) for title.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_breadcrumbs'] = __('Breadcrumbs', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_sub_heading'] = __('Sub Heading', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_sub_heading_hint'] = __('Enter subheading text here.it will display after title.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_bg_image'] = __('Background Image', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_bg_image_hint'] = __('Choose subheader background image from media gallery or leave it empty for display default image set by theme options.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_parallax'] = __('Parallax', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_parallax_hint'] = __('Parallax is an effect where the background content or image in this case, is moved at a different speed than the foreground content while scrolling can be enable with this switch.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_bg_color'] = __('Background Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_bg_color_hint'] = __('Provide a hex color code here (with #) if you want to override the default, leave it empty for using background image.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_breadcrumb_bg_color'] = __('Breadcrumb Background Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_breadcrumb_bg_color_hint'] = __('Provide a hex color code here (with #) for breadcrumb background.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_slider'] = __('Select Slider', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_map_sc'] = __('Custom Map Short Code', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_header_border'] = __('Header Border Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_header_hint'] = __('Provide a hex color code here (with #) for header border color.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_header_style'] = __('Choose Header Style', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_modern_header'] = __('Modern Header Style', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_default_header'] = __('Default header style', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_header_view'] = __('Header Style', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_header_view_default'] = __('Default', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_header_view_moderm'] = __('Modern', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_header_view_classic'] = __('Classic', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_header_view_fancy'] = __('Fancy', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_header_view_default_v2'] = __('Default', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_header_view_transparent'] = __('Transparent', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_header_view_advance_v2'] = __('Advance V2', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_header_view_modern_v2'] = __('Modern V2', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_header_menu_styles'] = __('Menu Styles', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_header_menu_styles_hint'] = __('Select a menu for current page.', 'wp-dp-frame');
            
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_side_bar'] = __('Select Sidebar', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_choose_sidebar'] = __('Choose Sidebar', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_margin'] = __('Page Margin', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_container'] = __('Page Container', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_hide_header_footer'] = __('Hide Header Footer', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_hide_header'] = __('Hide Header', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_header_wide_box'] = __('Header Full Width', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_header_wide_box_hint'] = __('Turn this switch on/off for header wide/box', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_hide_footer'] = __('Hide Footer', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['header_fixed_switch'] = esc_html__('Fixed Header', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['header_fixed_switch_hint'] = esc_html__('By turning this option as "ON" your website header will fix to top as you scroll down.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_bg_color'] = __('Page Background Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_bg_color_hint'] = __('Please Select Page Background Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_sidebar_hint'] = __('Choose sidebar layout for this post.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_left_sidebar'] = __('Select Left Sidebar', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_right_sidebar'] = __('Select Right Sidebar', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_second_right_sidebar'] = __('Select Second Right Sidebar', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_second_left_sidebar'] = __('Select Second Left Sidebar', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_options'] = __('Post Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_social_sharing'] = __('Social Sharing', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_about_author'] = __('About Author', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_product_options'] = __('Product Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_add_element'] = __('Add Element', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_search'] = __('Search', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_show_all'] = __('Show all', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_filter_by'] = __('Filter by', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_insert_sc'] = __('CS: Insert shortcode', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_quote'] = __('Blockquote', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_dropcap'] = __('Dropcap', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_options'] = __('%s Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_title'] = __('Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_title_color'] = __('Title Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_title_hint'] = __('This Title will view on top of this section.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_subtitle'] = __('Sub Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_subtitle_hint'] = __('This Sub Title will view below the Title of this section.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_subtitle_color'] = __('Sub Title Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_bg_view'] = __('Background View', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_choose_bg'] = __('Choose Background View.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_none'] = __('None', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_title_sub_title_align'] = __('Alignment', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_sub_header_align'] = __('Text Align', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_title_sub_title_align_hint'] = __('Set title/sub title alignment from this dropdown.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_align_left'] = __('Left', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_align_center'] = __('Center', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_align_right'] = __('Right', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_align_bottom'] = __('Bottom', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_custom_slider'] = __('Custom Slider', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_video'] = __('Video', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_youtube_vimeo_video_url'] = __('Youtube / Vimeo Video URL', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_bg_position'] = __('Background Image Position', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_choose_bg_position'] = __('Choose Background Image Position', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_no_center_top'] = __('no-repeat center top', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_center_top'] = __('repeat center top', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_no_center'] = __('no-repeat center', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_no_center_cover'] = __('no-repeat center / cover', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_center_cover'] = __('repeat center / cover', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_repeat_center'] = __('repeat center', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_no_left_top'] = __('no-repeat left top', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_repeat_left_top'] = __('repeat left top', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_no_fixed'] = __('no-repeat fixed center', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_no_fixed_cover'] = __('no-repeat fixed center / cover', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_custom_slider_hint'] = __('Enter Custom Slider', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_video_url'] = __('Video Url', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_browse'] = __('Browse', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_mute'] = __('Enable Mute', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_choose_mute'] = __('Choose Mute selection', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_yes'] = __('Yes', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_no'] = __('No', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_video_auto'] = __('Video Auto Play', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_choose_video_auto'] = __('Choose Video Auto Play selection', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_enable_parallax'] = __('Enable Parallax', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_section_nopadding'] = __('No Padding', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_section_nomargin'] = __('No Margin', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_select_view'] = __('Select View', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_box'] = __('Box', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_wide'] = __('Wide', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_choose_bg_coor'] = __('Choose background color.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_border_top'] = __('Border Top', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_border_top_hint'] = __('Set the Border top (In px)', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_border_bot'] = __('Border Bottom', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_border_bot_hint'] = __('Set the Border Bottom (In px)', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_border_color'] = __('Border Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_choose_border_color'] = __('Choose Border color.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_cus_id'] = __('Custom Id', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_cus_id_hint'] = __('Enter Custom Id.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_select_layout'] = __('Select Layout', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_page'] = __('Edit Page Section', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_ads_only'] = __('Ads', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_inventories'] = __('Inventory Listing', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_inventories_search'] = __('Inventory Search', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_compare_inventories'] = __('Inventory Compare', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_gallery'] = __('Gallery', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icons_box'] = __('Icons Box', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_plan'] = __('Pricing Tables', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_wc_feature'] = __('WC Feature Product', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_column'] = __('Columns', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_form'] = __('Contact Form', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_schedule_form'] = __('Schedule Form', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_best_time'] = __('Best time', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_testimonial'] = __('Testimonial', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_accordion'] = __('Accordion', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_multi_services'] = __('Multi Services', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_partner'] = __('Partner', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_blog'] = __('Blog - Views', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_heading'] = __('Headings', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_counter'] = __('Counter', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_image_frame'] = __('Image Frame', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_flex_editor'] = __('flex editor', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_editor'] = __('Editor', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_call_action'] = __('Call To Action', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance'] = __('maintenance', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_list'] = __('List', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_info'] = __('Contact Info', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_divider'] = __('Divider', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_promobox'] = __('Promobox', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_auto_heading'] = __('Rem_cs Heading', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_button'] = __('Buttons', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_sitemap'] = __('Site Map', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_listing_price'] = __('Listing Price', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_spacer'] = __('Spacer', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_typography'] = __('Typography', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_common_elements'] = __('Common Elements', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_media_element'] = __('Media Element', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_content_blocks'] = __('Content Blocks', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_loops'] = __('Loops', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_wpam'] = __('WPAM', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_size'] = __('Size', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_column_hint'] = __('Select column width. This width will be calculated depend page width.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_one_half'] = __('One half', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_one_third'] = __('One third', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_two_third'] = __('Two third', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_one_fourth'] = __('One fourth', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_three_fourth'] = __('Three fourth', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_plz_select'] = __('Please select..', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_text'] = __('Content', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_testimonial_text'] = __('Text', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_text_hint'] = __('Enter testimonial content here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_author_hint'] = __('Enter testimonial author name here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_position'] = __('Position', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_position_hint'] = __('Enter position of author here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_image'] = __('Image', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_image_hint'] = __('Set author image from media gallery.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_active'] = __('Active', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_active_hint'] = __('You can set the accordian section that is open by default on frontend by select dropdown', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_faq_active_hint'] = __('You can set the faq section that is open by default on frontend by select dropdown', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_remove'] = __('Remove', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_list_Item'] = __('List Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_list_Item_hint'] = __('Enter list title here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_tooltip'] = __('Choose icon for list.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_list_sc_icon_color'] = __('Icon Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_list_sc_icon_color_hint'] = __('Select icon color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_list_sc_icon_bg_color'] = __('Icon Background Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_list_sc_icon_bg_color_hint'] = __('Select icon background color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_listing_title_hint'] = __('Enter listing_price text here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price'] = __('Price', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_hint'] = __('Enter listing_price author name here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_color'] = __('Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_color_hint'] = __('Set text color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_counter_hint'] = __('Enter counter text here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_counter_author_hint'] = __('Enter counter author name here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_counter_text_hint'] = __('Enter position of author here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_divider_hint'] = __('Divider setting on/off', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_image_url'] = __('Image Url', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_image_url_hint'] = __('Enter image url', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_multiple_service'] = __('Multiple Service', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_content_title'] = __('Content Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_content_title_hint'] = __('Add service title here..', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_link_url'] = __('Link Url', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_link_hint'] = __('e.g. http://yourdomain.com/.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_content_title_color'] = __('Content title Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_content_title_color_hint'] = __('Set title color from here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_bg_color'] = __('Icon Background Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_bg_color_hint'] = __('Set the Service Background', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_color'] = __('Icon Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_color_hint'] = __('Set the position of service image here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_service_text_hint'] = __('Enter little description about service.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_content_color'] = __('Content Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_content_color_hint'] = __('Provide a hex colour code here (with #) for text color. if you want to override the default.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_builder'] = __('CS Page Builder', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_enter_valid'] = __('Enter Your Email Address...', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_subscribe_success'] = __('subscribe successfully', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_inventory_type'] = __('Inventory Makes', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_style'] = __('Style', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_view'] = __('View', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_view_hint'] = __('Select post view from this dropdown. Default view is selected from theme option.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_ad_unit'] = __('Ad Unit', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_select_ad'] = __('Select Ad', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_cover_image'] = __('Cover Image', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_format'] = __('Format', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_view_thumbnail'] = __(' Thumbnail ', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_view_slider'] = __(' Slider ', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_format_thumbnail'] = __(' Thumbnail ', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_format_slider'] = __(' Slider ', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_format_sound'] = __('Sound', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_format_video'] = __('Video', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_inside_thumbnail'] = __('Inside Post Thumbnail', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_soundcloud_url'] = __('SoundCloud URL', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_view_select_format'] = __('Select Format', 'wp-dp-frame');
            
            //Social media widget
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_social_media'] = esc_html__('CS : Social media', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_social_media_desc'] = esc_html__('Enter Social media here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_social_info_facebook_url'] = esc_html__('Facebook Page Url', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_social_info_facebook_url_desc'] = esc_html__('Enter facebook page url here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_social_info_twitter_url'] = esc_html__('Twitter user', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_social_info_twitter_url_desc'] = esc_html__('Enter twitter user here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_social_info_google_url'] = esc_html__('Google username/id', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_social_info_google_url_desc'] = esc_html__('Enter google user or ID  here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_social_media_twitter_followers'] = esc_html__('Twitter followers', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_social_media_google_followers'] = esc_html__('G+ Followers', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_social_media_facebook_followers'] = esc_html__('Facebook Fans', 'wp-dp-frame');

            /*
              multi counter
             */

            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_multiple_counter_title'] = __('Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_multiple_counter_title_hint'] = __('Enter Title Here', 'wp-dp-frame');

            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_multiple_counter'] = __('Counter', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_multiple_counter_icon'] = __('Icon', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_multiple_counter_icon_tooltip'] = __('Please Select Icon ', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_multiple_counter_count'] = __('Count', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_multiple_counter_count_tooltip'] = __('Enter Counter Range', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_multiple_counter_content'] = __('Content', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_multiple_counter_content_tooltip'] = __('Enter Content Here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_multiple_counter_content_color'] = __('Content Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_multiple_counter_content_color_tooltip'] = __('Select Content Color ', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_thumbnail_view_demo'] = __('Thumbnail View demo', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_thumbnail_view_demo_hint'] = __('Choose thumbnial view type for this post. None for no image. Single image for display featured image on listings and slider for display slides on thumbnail view.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_single_image'] = __('Single Image', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_slider'] = __('Slider', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_audio'] = __('Audio', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_thumbnail_audio_url'] = __('Thumbnail Audio URL', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_thumbnail_audio_url_hint'] = __('Enter Audio URL for this Post', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_thumbnail_video_url'] = __('Thumbnail Video URL', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_thumbnail_video_url_hint'] = __('Enter Specific Video Url (Youtube, Vimeo and Dailymotion)', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_add_gallery_images'] = __('Add Gallery Images', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_detail_views'] = __('Detail Views', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_simple'] = __('Simple', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_fancy'] = __('Fancy', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_inside_post_view'] = __('Inside Post Thumbnail View', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_inside_post_view_hint'] = __('Choose inside thumbnial view type for this post. None for no image. Single image for display featured image on detail. Slider for display slides on detail. Audio for make this audio post and video for display video inside post.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_audio_url'] = __('Audio Url', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_audio_url_hint'] = __('Enter Mp3 audio url for this post .', 'wp-dp-frame');

            /*             * accordion Code */
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_accordian'] = __('Accordion', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_faq'] = __('Faq', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_accordion_title'] = __('Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_accordion_title_hint'] = __('Enter accordion title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_faq_title'] = __('Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_faq_title_hint'] = __('Enter faq title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_accordion_text'] = __('Content', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_accordion_text_hint'] = __('Enter accordian content here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_faq_text'] = __('Content', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_faq_text_hint'] = __('Enter faq content here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_accordion_icon'] = __('Icon', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_accordion_icon_hint'] = __('Select Icon for accordion', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_faq_icon'] = __('Icon', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_faq_icon_hint'] = __('Select Icon for faq', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_accordion_title_hint'] = __('Enter accordion title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_accordion_view'] = __('View', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_accordion_view_hint'] = __('Select View for Accordion', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_faq_view'] = __('View', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_faq_view_hint'] = __('Select View for Accordion', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_accordion_view_simple'] = __('Simple', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_accordion_view_modern'] = __('Modern', 'wp-dp-frame');

            /*             * Site map Short Code */
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_sitemap'] = __('Edit SiteMap Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_map_search_page'] = __('DB: Map Search Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_section_title'] = __('Section Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_settings'] = __('Post Settings', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_post_gallery'] = __('Post Gallery', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_maintain_page'] = __('Edit Maintain Page Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_insert'] = __('Insert', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_logo'] = __('Logo', 'wp-dp-frame');

            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_no_margin_hint'] = __('Select Yes to remove margin for this section', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_please_select_maintinance'] = __('Please Select a Maintinance Page', 'wp-dp-frame');
            /*             * Client Short Code */
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_clients'] = __('Clients', 'wp-dp-frame');
            /*
              team
             */

            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team'] = __('Team', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_add_item'] = __('Add Team', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_name'] = __('Name', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_name_hint'] = __('Enter team member name here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_designation'] = __('Designation', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_designation_hint'] = __('Enter team member designation here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_image'] = __('Team Profile Image', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_image_hint'] = __('Select team member image from media gallery.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_phone'] = __('Phone No', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_phone_hint'] = __('Enter phone number here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_fb'] = __('Facebook', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_fb_hint'] = __('Enter facebook account link here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_twitter'] = __('Twitter', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_twitter_hint'] = __('Enter twitter account link here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_google'] = __('Google Plus', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_google_hint'] = __('Enter google accoount link here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_linkedin'] = __('Linkedin', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_linkedin_hint'] = __('Enter linkedin account link here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_youtube'] = __('Youtube', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_youtube_hint'] = __('Enter youtube link here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_title'] = __('Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_title_hint'] = __('Enter Team Title Here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_sub_title'] = __('Sub Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc_sub_title_hint'] = __('Enter Team Sub Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_team_sc'] = __('Team', 'wp-dp-frame');
            /*             * Maintenance Short Code */

            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_maintenance_page'] = __('Maintenance Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_wp_dp_listings_page'] = __('Listings Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_wp_dp_listings_element'] = __('DB: Listings Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_wp_dp_listings_element_wd_filter'] = __('DB: Listings with Filters Options', 'wp-dp-frame');


            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_wp_dp_listings_page_slider'] = __('DB: Listings Slider Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_wp_dp_listingsearch_page'] = __('DB: Listing Search Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_wp_dp_map_listings_page'] = __('DB: Map Listings Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_wp_dp_members_page'] = __('DB: Members Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_wp_dp_register_page'] = __('DB: Register Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_wp_dp_register_user_and_add_listing_page'] = __('DB: Register User and Add Listing Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_listing_categories_page'] = __('DB: Listing Categories Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_pricing_table_page'] = __('DB: Pricing Plan Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_tabs_fancy_edit_options'] = __('Facny Tabs Options', 'wp-dp-frame');




            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance'] = __('Maintenance', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_sc_title'] = __('Element Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_sc_title_hint'] = __('Enter Maintenance Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_sc_sub_title'] = __('Element Sub Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_sc_sub_title_hint'] = __('Enter Maintenance Subtitle', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_sc_text'] = __('Content', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_sc_text_hint'] = __('Enter Maintenance Text', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_sc_views'] = __('Views ', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_sc_views_hint'] = __('Select a view for underconstruction page.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_sc_view1'] = __('View 1 ', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_sc_view2'] = __('View 2 ', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_sc_image_hint'] = __('Select Image for Maintaince background.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_sc_logo_hint'] = __('Select Image for Maintaince Logo.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_sc_launch_date'] = __('Launch Date', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_sc_launch_date_hint'] = __('Enter launch Date', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_sc_save'] = __('Save', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_save_settings'] = __('Save Settings', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_select_page'] = __('Select A page', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_newsletter'] = __('Newsletter ', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_newsletter_sign_up'] = __('Sign Up! ', 'wp-dp-frame');
            /*
              tabs */

            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_tabs'] = __('Tabs', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_tab'] = __('Tab', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_tabs_desc'] = __('You can manage your tabs using this shortcode', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_tab_active'] = __('Active', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_tab_active_hint'] = __('You can set the tab section that is open by default on frontend by select dropdown', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_tab_item_text'] = __('Tab Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_tab_item_text_hint'] = __('Enter tab title here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_tab_desc'] = __('Content', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_tab_desc_hint'] = __('Enter tab content here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_tab_icon'] = __('Tab Icon', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_tab_icon_hint'] = __('Select the Icons you would like to show with your tab .', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_saving_changes'] = __('Saving changes...', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_no_title'] = __('No Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_no_padding_hint'] = __('Select Yes to remove padding for this section', 'wp-dp-frame');




            /* Maintenance Mode */

            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_save_btn'] = __('Save Settings', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_field_name'] = __('Maintenance Mode', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_field_mode'] = __('Maintenance Mode', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_field_mode_hint'] = __('Turn Maintenance Mode On/Off here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_field_logo'] = __('Maintenance Mode Logo', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_field_logo_hint'] = __('Turn Logo On/Off here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_field_social'] = __('Social Contact', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_field_social_hint'] = __('Turn Social Contact On/Off here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_field_newsletter'] = __('Newsletter', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_field_newsletter_hint'] = __('Turn newsletter On/Off here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_field_header'] = __('Header Switch', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_field_header_hint'] = __('Turn Header On/Off here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_field_footer'] = __('Footer Switch', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_field_footer_hint'] = __('Turn Footer On/Off here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_field_select_page'] = __('Please Select a Page', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_field_mode_page'] = __('Maintenance Mode Page', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_field_mode_page_hint'] = __('Choose a page that you want to set for maintenance mode', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_save_message'] = __('All Settings Saved', 'wp-dp-frame');
            /*
              icos box
             */
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_boxs_title'] = __('Icon Box', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_boxs_views'] = __('Views', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_boxs_views_hint'] = __('Select the Icon Box style', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_box_view_option_1'] = __('Simple', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_box_view_option_2'] = __('Top Center', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_boxes_content_title'] = __('Icon Box Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_boxes_content_title_hint'] = __('Add Icon Box title here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_boxes_link_url'] = __('Title Link', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_boxes_link_url_hint'] = __('e.g. http://yourdomain.com/.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_boxes_content_title_color'] = __('Content title Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_boxes_content_title_color_hint'] = __('Set title color from here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_boxes_Icon'] = __('Icon Box Icon', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_boxes_Icon_hint'] = __('Select the icons you would like to show with your accordion title.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_box_icon_font_size'] = __('Icon Font Size', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_box_icon_font_size_hint'] = __('Set the Icon Font Size', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_box_icon_font_size_option_1'] = __('Extra Small', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_box_icon_font_size_option_2'] = __('Small', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_box_icon_font_size_option_3'] = __('Medium', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_box_icon_font_size_option_4'] = __('Medium Large', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_box_icon_font_size_option_5'] = __('Large', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_box_icon_font_size_option_6'] = __('Extra Large', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_box_icon_font_size_option_7'] = __('Free Size', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_boxes_Icon_bg'] = __('Icon Background Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_boxes_Icon_bg_hint'] = __('Set the Icon Box Background.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_boxes_Icon_color'] = __('Icon Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_boxes_Icon_color_hint'] = __('Set Icon Box icon color from here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_boxes_text'] = __('Icon Box Content', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_boxes_text_hint'] = __('Enter icon box content here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_box_icon_type'] = __('Icon Type', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_box_icon_type_hint'] = __('Select icon type image or font icon.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_box_icon_type_1'] = __('Icon', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_box_icon_type_2'] = __('Image', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_box_image'] = __('Image', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_icon_box_image_hint'] = __('Attach image from media gallery.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_register_heading'] = __('User Registration', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_register'] = __('User Registration', 'wp-dp-frame');




            /* Price Table */
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_title'] = __('Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_title_hint'] = __('Enter Price table Title Here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_title_color'] = __('Title Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_title_color_hint'] = __('Set price-table title color from here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_price_color'] = __('Price Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_price_color_hint'] = __('Set Price color from here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_price'] = __('Price', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_price_hint'] = __('Add price without symbol', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_currency'] = __('Currency Symbols', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_currency_hint'] = __('Add your currency symbol here like $', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_time'] = __('Time Duration', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_time_hint'] = __('Add time duration for package or time range like this package for a month or year So wirte here for Mothly and year for Yearly Package', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_button_link'] = __('Button Link', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_button_link_hint'] = __('Add price table button Link here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_button_text'] = __('Button Text', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_button_text_hint'] = __('Add button text here Example : Buy Now, Purchase Now, View Packages etc', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_button_color'] = __('Button text Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_button_color_hint'] = __('Set button color with color picker', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_button_bg_color'] = __('Button Background Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_button_bg_color_hint'] = __('Set button background color with color picker', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_featured'] = __('Featured on/off', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_featured_hint'] = __('Price table featured option enable/disable with this dropdown', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_description'] = __('Content', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_description_hint'] = __('Features can be add easily in input with this shortcode 
					    					[feature_item]Text here[/feature_item][feature_item]Text here[/feature_item]', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_column_color'] = __('Column Background Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_price_table_column_color_hint'] = __('Set Column Background color', 'wp-dp-frame');

            /* Progressbar Shortcode */
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_progressbars'] = __('Progress Bars', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_progressbar'] = __('Progress Bar', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_progressbar_title'] = __('Progress Bar Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_progressbar_title_hint'] = __('Enter progress bar title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_progressbar_skill'] = __('Skill (in percentage)', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_progressbar_skill_hint'] = __('Enter skill (in percentage) here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_progressbar_color'] = __('Progress Bar Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_progressbar_color_hint'] = __('Set progress bar color here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_progressbar_add_button'] = __('Add Progress Bar', 'wp-dp-frame');

            /* Table Shortcode */
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_table'] = __('Table', 'wp-dp-frame');

            /* Page Editor Tabs */
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_classic_editor'] = __('Classic Editor', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_page_builder'] = __('Page Builder', 'wp-dp-frame');
            /* About Info */
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_about_info'] = __('About Info', 'wp-dp-frame');

            /* Maintenance Page */
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_days'] = __('days', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_hours'] = __('hours', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_minutes'] = __('minutes', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_maintenance_seconds'] = __('seconds', 'wp-dp-frame');



            /*
             * Contact Form Stings 
             */

            // frontend
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_default_success_msg'] = esc_html__('Email has been sent Successfully', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_default_error_msg'] = esc_html__('An error Occured, please try again later', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_first_name'] = esc_html__('Full Name', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_first_name_placeholder'] = esc_html__('Your Name', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_last_name'] = esc_html__('Subject Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_last_name_placeholder'] = esc_html__('Subject', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_email'] = esc_html__('Email Address', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_email_address'] = esc_html__('E-mail address', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_phone_number'] = esc_html__('Contact Number', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_phone_number_placeholder'] = esc_html__('Phone Number', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_button_text'] = esc_html__('Send A message', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_message_contact'] = esc_html__('Type Message', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_message_contact_placeholder'] = esc_html__('Your Feedback', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_check_field'] = esc_html__('Subscribe and Get latest updates and offers by Email', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_received'] = esc_html__('Contact Form Received', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_valid_email'] = esc_html__('Please enter a valid email.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_email_should_not_be_empty'] = esc_html__('Email should not be empty.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_full_name'] = esc_html__('Name', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_ip_address'] = esc_html__('IP Address:', 'wp-dp-frame');
            
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_label_display'] = esc_html__('Fields Label', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_contact_label_display_hint'] = esc_html__('Select on/off to display/hide the labels of all fields in the contact form', 'wp-dp-frame');
            
            
            

            // backend
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_edit_form'] = esc_html__('Contact Form Options', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_element_title'] = esc_html__('Element Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_element_title_hint'] = esc_html__('Enter element title here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_element_subtitle'] = esc_html__('Element Sub Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_element_subtitle_hint'] = esc_html__('Enter element sub title here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_var_title_alignment'] = esc_html__('Title Alignment', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_var_title_alignment_hint'] = esc_html__('Select title alignment here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_var_title_color'] = esc_html__('Element Title Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_var_title_color_hint'] = esc_html__('Set the element title color here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_var_subtitle_color'] = esc_html__('Element Subtitle Color', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_var_subtitle_color_hint'] = esc_html__('Set element subtitle color here', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_var_align_left'] = esc_html__('Left', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_var_align_right'] = esc_html__('Right', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_var_align_center'] = esc_html__('Center', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_send_to'] = esc_html__('Receiver Email', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_send_to_hint'] = esc_html__('Receiver, or receivers of the mail.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_success_message'] = esc_html__('Success Message', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_success_message_hint'] = esc_html__('Enter Mail Successfully Send Message.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_error_message'] = esc_html__('Error Message', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_error_message_hint'] = esc_html__('Enter Error Message In any case Mail Not Submited.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_save'] = esc_html__('Save', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_newsletter_email_id'] = esc_html__('Enter Your Email ID', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_newsletter_email_id_name'] = esc_html__('Enter Name', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_newsletter_subscribe'] = esc_html__('Subscribe', 'wp-dp-frame');

            // Google Fonts
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_fonts_menu_label'] = esc_html__('CS Fonts Manager', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_google_fonts_label'] = esc_html__('Google Fonts', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_custom_fonts_label'] = esc_html__('Custom Fonts', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_refresh_fonts_list'] = esc_html__('Refresh Google Fonts List', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_new_fonts_added'] = esc_html__('%s new fonts added.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_fonts_added_error'] = esc_html__('Fonts could not be downloaded as there might be some issue with file_get_contents or wp_remote_get due to your server configuration.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_select_font_attributes'] = esc_html__('Select Attribute', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_search_font'] = esc_html__('Serach font...', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_google_fonts_is_added'] = esc_html__('Font is added in font list.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_google_fonts_is_deleted'] = esc_html__('Font is deleted in font list.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_google_fonts_is_updated'] = esc_html__('Font is updated in font list.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_are_you_sure_remove_font'] = esc_html__('Are you sure you want to remove this font?', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_google_fonts_page_is_loading'] = esc_html__('Please wait, Page is reloading.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_google_fonts_added_in_list'] = esc_html__('Added in List', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_google_fonts_add_to_list'] = esc_html__('Add to List', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_your_selected_google_fonts'] = esc_html__('Your Selected Google Fonts', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_not_font_search'] = esc_html__('Sorry! there are no font families that match. Try with other search keyword', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_seems_dont_have_font'] = esc_html__('It seems you don\'t have any Google Fonts yet. But you can download them now with', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_google_font_just_click'] = esc_html__('just a click', 'wp-dp-frame');
	    $wp_dp_cs_var_frame_static_text['wp_dp_fonts_export_btn_label'] = esc_html__('Export Fonts', 'wp-dp-frame');
            /*
             * Custom Font
             */
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_browse'] = esc_html__('Browse', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_theme_option_custom_font_name'] = esc_html__('Font Name', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_theme_option_custom_font_woff'] = esc_html__('Custom Font .woff', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_theme_option_custom_font_woff_hint'] = esc_html__('Upload Your Custom Font file in .woff format', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_theme_option_custom_font_ttf'] = esc_html__('Custom Font .ttf', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_theme_option_custom_font_ttf_hint'] = esc_html__('Upload Your Custom Font file in .ttf format', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_theme_option_custom_font_svg'] = esc_html__('Custom Font .svg', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_theme_option_custom_font_svg_hint'] = esc_html__('Upload Your Custom Font file in .svg format', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_theme_option_custom_font_eot'] = esc_html__('Custom Font .eot', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_theme_option_custom_font_eot_hint'] = esc_html__('Upload Your Custom Font file in .eot format', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_theme_option_custom_fonts'] = esc_html__('Custom Fonts', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_add_custom_fonts_list'] = esc_html__('+ Add to Custom Fonts List', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_custom_fonts_fields_empty'] = esc_html__('Please fill all mandatory fields.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_selected_custom_fonts_is_deleted'] = esc_html__('Selected custom font is deleted in custom font list.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_custom_fonts_is_deleted'] = esc_html__('Custom font is deleted in custom font list.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_add_new_custom_font'] = esc_html__('Add new Custom Font', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_add_to_selected_custom_fonts'] = esc_html__('Add to List', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_add_in_selected_custom_fonts'] = esc_html__('Added in List', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_your_selected_custom_fonts'] = esc_html__('Your Selected Custom Fonts', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_google_font_attr_select_all'] = esc_html__('Select All', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_google_font_attr_unselect_all'] = esc_html__('Un-Select All', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_element_title_seperator'] = esc_html__('Seperator', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_element_title_seperator_hint'] = esc_html__('Set the element title seperator here.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_element_title_seperator_none'] = esc_html__('None', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_element_title_seperator_classic'] = esc_html__('Classic Seperator', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_element_title_seperator_zigzag'] = esc_html__('Zigzag Seperator', 'wp-dp-frame');
            
            // Shortcode responsive fields strings
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_settings_heading'] = esc_html__('Responsive Settings', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_device_txt'] = esc_html__('Device', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_column_width'] = esc_html__('Column Width', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_offset_txt'] = esc_html__('Offset', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_hide_txt'] = esc_html__('Hide', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_large_txt'] = esc_html__('Large', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_medium_txt'] = esc_html__('Medium', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_small_medium_txt'] = esc_html__('Small Medium', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_small_txt'] = esc_html__('Small', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_default_txt'] = esc_html__('Default', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_no_offset_txt'] = esc_html__('No offset', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_column_1_width'] = esc_html__('1 column - 1/12', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_column_2_width'] = esc_html__('2 columns - 2/12', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_column_3_width'] = esc_html__('3 columns - 3/12', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_column_4_width'] = esc_html__('4 columns - 4/12', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_column_5_width'] = esc_html__('5 columns - 5/12', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_column_6_width'] = esc_html__('6 columns - 6/12', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_column_7_width'] = esc_html__('7 columns - 7/12', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_column_8_width'] = esc_html__('8 columns - 8/12', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_column_9_width'] = esc_html__('9 columns - 9/12', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_column_10_width'] = esc_html__('10 columns - 10/12', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_column_11_width'] = esc_html__('11 columns - 11/12', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_responsive_column_12_width'] = esc_html__('12 columns - 12/12', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_plugin_error'] = esc_html__('Please install the "Directory Box" before activating "Directory Box Framework" plugin.', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_container_width'] = esc_html__('Container Width (px)', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_container_placeholder'] = esc_html__('Default Width', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_alternate_page_title'] = esc_html__('Alternate Page Title', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_hide_page_back_to_top'] = esc_html__('Hide Back to top', 'wp-dp-frame');
            
            
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_header_search'] = esc_html__('Header Search', 'wp-dp-frame');
            $wp_dp_cs_var_frame_static_text['wp_dp_cs_var_header_search_hint'] = esc_html__('Turn this switch this on/off to hide show search in the header.', 'wp-dp-frame');
            
            
            

            return $wp_dp_cs_var_frame_static_text;
        }

    }

}
new wp_dp_cs_var_frame_all_strings;
