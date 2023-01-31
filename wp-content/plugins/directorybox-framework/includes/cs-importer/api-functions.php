<?php

/*
 * Reload all the information for user
 * @Based on Purchase code
 */
if (!function_exists('reload_user_data')) {
    function reload_user_data() {
        $envato_purchase_code_verification = get_option('item_purchase_code_verification');
        $purchase_code = isset($envato_purchase_code_verification['item_puchase_code']) ? $envato_purchase_code_verification['item_puchase_code'] : '';
        $theme_obj = wp_get_theme();

        $reload_data = array(
            'theme_puchase_code' => $purchase_code,
            'theme_name' => $theme_obj->get('Name'),
            'theme_id' => $envato_purchase_code_verification['item_id'],
            'user_email' => isset($envato_purchase_code_verification['envato_email_address']) ? $envato_purchase_code_verification['envato_email_address'] : '',
            'theme_version' => $theme_obj->get('Version'),
            'site_url' => site_url(),
        );
        echo json_encode($reload_data);
        wp_die();
    }

    add_action('wp_ajax_reaload_user_data', 'reload_user_data');
    add_action('wp_ajax_nopriv_reaload_user_data', 'reload_user_data');
}
if (!function_exists('cs_cron_schedules')) {
    function cs_cron_schedules($schedules) {
    if ( ! isset($schedules["10days"]) ) {
        $schedules["10days"] = array(
            //'interval' => 1 * 60,
            'interval' => 864000,
            'display' => __('Once every 10 Days') );
    }
    return $schedules;
}
}
add_filter('cron_schedules', 'cs_cron_schedules');
if (!function_exists('check_theme_is_active')) {
    function check_theme_is_active() {
    // Use wp_next_scheduled to check if the event is already scheduled.
    $timestamp = wp_next_scheduled('check_theme_is_active');

    // If $timestamp == false schedule daily alerts since it hasn't been done previously.
    if ( $timestamp == false ) {
        // Schedule the event for right now, then to repeat daily using the hook 'create_daily_listings_check'.
        wp_schedule_event(time(), '10days', 'check_theme_is_active_action');
    }
}
}
/*
 * Addint theme information into stats
 */
if ( ! function_exists('wp_dp_add_to_active_themes_callback') ) {

    function wp_dp_add_to_active_themes_callback() {
        $remote_api_url = REMOTE_API_URL;
        $envato_purchase_code_verification = get_option('item_purchase_code_verification');
        $selected_demo = isset($_POST['selected_demo']) ? $_POST['selected_demo'] : '';
        $envato_purchase_code_verification['selected_demo'] = $selected_demo;
        update_option('item_purchase_code_verification', $envato_purchase_code_verification);
        $theme_obj = wp_get_theme();
        $demo_data = array(
            'theme_puchase_code' => $envato_purchase_code_verification['item_puchase_code'],
            'theme_name' => $theme_obj->get('Name'),
            'theme_id' => $envato_purchase_code_verification['item_id'],
            'user_email' => isset($envato_purchase_code_verification['envato_email_address']) ? $envato_purchase_code_verification['envato_email_address'] : '',
            'theme_demo' => $selected_demo,
            'theme_version' => $theme_obj->get('Version'),
            'site_url' => site_url(),
            'supported_until' => isset($envato_purchase_code_verification['supported_until']) ? $envato_purchase_code_verification['supported_until'] : '',
            'action' => 'add_to_active_themes',
        );
        $url = $remote_api_url;
        $response = wp_remote_post($url, array( 'body' => $demo_data ));
        check_theme_is_active();
        wp_die();
    }

    add_action('wp_ajax_wp_dp_add_to_active_themes', 'wp_dp_add_to_active_themes_callback');
}
if (!function_exists('check_theme_is_active_callback')) {
    function check_theme_is_active_callback() {
        $remote_api_url = REMOTE_API_URL;
        $envato_purchase_code_verification = get_option('item_purchase_code_verification');
        $theme_obj = wp_get_theme();
        $demo_data = array(
            'theme_puchase_code' => isset( $envato_purchase_code_verification['item_puchase_code'] )? $envato_purchase_code_verification['item_puchase_code'] : '',
            'theme_name' => $theme_obj->get('Name'),
            'theme_version' => $theme_obj->get('Version'),
            'action' => 'check_active_theme',
        );
        $url = $remote_api_url;
        $response = wp_remote_post($url, array( 'body' => $demo_data ));
        wp_die();
    }

    add_action('check_theme_is_active_action', 'check_theme_is_active_callback');
}

/*
 * Releasing Purchase Code
 */

if ( ! function_exists('wp_dp_release_purchase_code_callback') ) {

    function wp_dp_release_purchase_code_callback() {
        $remote_api_url = REMOTE_API_URL;
        $envato_purchase_code_verification = get_option('item_purchase_code_verification');
        $purchase_code = isset($envato_purchase_code_verification['item_puchase_code']) ? $envato_purchase_code_verification['item_puchase_code'] : '';
        $update_data = array(
            'theme_puchase_code' => $purchase_code,
            'site_url' => site_url(),
            'action' => 'realse_purchase_code',
        );
        $url = $remote_api_url;
        $response = wp_remote_post($url, array( 'body' => $update_data ));
        delete_option( 'item_purchase_code_verification' );
        echo json_encode($response);
        wp_die();
    }

    add_action('wp_ajax_wp_dp_release_purchase_code', 'wp_dp_release_purchase_code_callback');
}