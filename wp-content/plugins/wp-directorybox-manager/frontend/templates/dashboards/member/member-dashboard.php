<?php

/**
 * The template for displaying member dashboard
 *
 */
if(!function_exists('cs_member_popup_style')){
function cs_member_popup_style() {
    wp_enqueue_style('custom-member-style-inline', plugins_url('../../../../assets/frontend/css/custom_script.css', __FILE__));
    $cs_plugin_options = get_option('cs_plugin_options');


    $cs_custom_css = '#id_confrmdiv
    {
        display: none;
        background-color: #eee;
        border-radius: 5px;
        border: 1px solid #aaa;
        position: fixed;
        width: 300px;
        left: 50%;
        margin-left: -150px;
        padding: 6px 8px 8px;
        box-sizing: border-box;
        text-align: center;
    }
    #id_confrmdiv .button {
        background-color: #ccc;
        display: inline-block;
        border-radius: 3px;
        border: 1px solid #aaa;
        padding: 2px;
        text-align: center;
        width: 80px;
        cursor: pointer;
    }
    #id_confrmdiv .button:hover
    {
        background-color: #ddd;
    }
    #confirmBox .message
    {
        text-align: left;
        margin-bottom: 8px;
    }';
    wp_add_inline_style('custom-member-style-inline', $cs_custom_css);
}
}

add_action('wp_enqueue_scripts', 'cs_member_popup_style', 5);
get_header();
//editor
wp_enqueue_style('jquery-te');
wp_enqueue_script('jquery-te');

//iconpicker
wp_enqueue_style('fonticonpicker');
wp_enqueue_script('fonticonpicker');
wp_enqueue_script('wp-dp-reservation-functions');
wp_enqueue_script('wp-dp-validation-script');
wp_enqueue_script('wp-dp-members-script');
if (!wp_script_is( 'wp-dp-google-map-api', 'enqueued' )) {
    wp_enqueue_script('wp-dp-google-map-api');
}
wp_enqueue_script('jquery-latlon-picker');
wp_enqueue_script('jquery-branches-latlon-picker');
wp_enqueue_script('wp-dp-prettyPhoto');
wp_enqueue_style('wp-dp-prettyPhoto');
wp_enqueue_script('wp_dp_animation_effect');
wp_enqueue_script('wp-dp-print-pdf');

$post_id = get_the_ID();
$user_details = wp_get_current_user();
global $wp_dp_plugin_options;
$user_company_id = get_user_meta($user_details->ID, 'wp_dp_company', true);
$last_login = get_post_meta($user_company_id, 'last_login', true);
$fullName = isset($user_company_id) && $user_company_id != '' ? get_the_title($user_company_id) : '';
$user_display_name = get_the_author_meta('display_name', $user_details->ID);

$wp_dp_cs_var_page_container_switch = get_post_meta($post_id, 'wp_dp_cs_var_page_container_switch', true);
$wp_dp_cs_var_page_container_switch = isset($wp_dp_cs_var_page_container_switch) ? $wp_dp_cs_var_page_container_switch : 'off'; // by default container not required
$container_class = 'container-fluid';
if ( $wp_dp_cs_var_page_container_switch == 'on' ) {
    $container_class = 'container';
}


$profile_image_id = $wp_dp_member_profile->member_get_profile_image($user_details->ID);
$user_type = get_user_meta($user_details->ID, 'wp_dp_user_type', true);
$profile_description = $user_details->description;

if ( $profile_image_id == '' ) {
    $profile_image_id = wp_dp::plugin_url() . '/assets/frontend/images/member-no-image.jpg';
}
?>
<div id="main">
    <div class="page-section account-header">
        <div class="<?php echo esc_html($container_class); ?>">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 dashboard-sidebar-panel">

                    <div id="bg-day-night" class="dashboard-user-detail">
                        <div class="user-detail-holder">
                            <div class="img-holder">
                                <figure> <img src="<?php echo esc_url($profile_image_id); ?>" alt="" > </figure>
                            </div>
                            <div class="text-holder"> <strong class="user-name"><?php echo esc_html($fullName); ?></strong> <span class="user-label">
                                    <?php
                                    if ( $last_login && $last_login != '' ) {
                                        $output = human_time_diff($last_login, current_time('timestamp', 1)) . ' ' . wp_dp_plugin_text_srt('wp_dp_func_ago');
                                    } else {
                                        $output = wp_dp_plugin_text_srt('wp_dp_last_login_never');
                                    }
                                    echo esc_html($output);
                                    ?>
                                </span> 
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-nav-panel">
                        <button class="dashboard-nav-btn"><i class="icon-dashboard"></i></button>

                        <div class="user-account-nav user-account-sidebar">
                            <div class="user-account-holder">
<?php
$active_tab = ''; // default tab
$child_tab = '';
$wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
$wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';
?>
                                <ul class="dashboard-nav">
                                <?php
                                if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'suggested' ) {
                                    $active_tab = 'wp_dp_member_suggested';
                                }
                                wp_enqueue_script('wp-dp-favourites-script');
                                ?>
                                    <li class="user_dashboard_ajax active" id="wp_dp_member_suggested" data-queryvar="dashboard=suggested"><a href="javascript:void(0);"><i class="icon-dashboard-user-account"></i><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_Dashboard'); ?></a></li>

                                    <?php
                                    $current_user = wp_get_current_user();
                                    $wp_dp_user_type = get_user_meta($current_user->ID, 'wp_dp_user_type', true);
                                    $member_id = wp_dp_company_id_form_user_id($current_user->ID);

                                    /*
                                     * Pending listings
                                     */

                                    $args_pending_listing = array(
                                        'post_type' => 'listings',
                                        'post_status' => 'publish',
                                        'posts_per_page' => 1,
                                        'meta_query' => array(
                                            'relation' => 'AND',
                                            array(
                                                'key' => 'wp_dp_listing_member',
                                                'value' => $member_id,
                                                'compare' => '=',
                                            ),
                                            array(
                                                'key' => 'wp_dp_listing_status',
                                                'value' => 'awaiting-activation',
                                                'compare' => '=',
                                            ),
                                            array(
                                                'key' => 'wp_dp_listing_expired',
                                                'value' => strtotime(date('Y-m-d')),
                                                'compare' => '>=',
                                            )
                                        ),
                                    );
                                    $custom_query = new WP_Query($args_pending_listing);
                                    $dashboard_count_pending_listing = $custom_query->found_posts;
                                    wp_reset_postdata();
                                    /*
                                     * Expired Pending listings
                                     */

                                    $args_expired_listing = array(
                                        'post_type' => 'listings',
                                        'posts_per_page' => 1,
                                        'post_status' => 'publish',
                                        'meta_query' => array(
                                            'relation' => 'AND',
                                            array(
                                                'key' => 'wp_dp_listing_member',
                                                'value' => $member_id,
                                                'compare' => '=',
                                            ),
                                            array(
                                                'key' => 'wp_dp_listing_expired',
                                                'value' => strtotime(date('Y-m-d')),
                                                'compare' => '<',
                                            ),
                                            array(
                                                'key' => 'wp_dp_listing_status',
                                                'value' => 'delete',
                                                'compare' => '!=',
                                            )
                                        ),
                                    );

                                    $custom_query = new WP_Query($args_expired_listing);
                                    $dashboard_count_expired_listing = $custom_query->found_posts;
                                    wp_reset_postdata();

                                    /*
                                     * Published
                                     */
                                    $args_published_listing = array(
                                        'post_type' => 'listings',
                                        'posts_per_page' => 1,
                                        'post_status' => 'publish',
                                        'meta_query' => array(
                                            'relation' => 'AND',
                                            array(
                                                'key' => 'wp_dp_listing_member',
                                                'value' => $member_id,
                                                'compare' => '=',
                                            ),
                                            array(
                                                'key' => 'wp_dp_listing_status',
                                                'value' => 'active',
                                                'compare' => '=',
                                            ),
                                            array(
                                                'key' => 'wp_dp_listing_expired',
                                                'value' => strtotime(date('Y-m-d')),
                                                'compare' => '>=',
                                            ),
                                        ),
                                    );
                                    $custom_query = new WP_Query($args_published_listing);
                                    $dashboard_count_published_listing = $custom_query->found_posts;
                                    wp_reset_postdata();



                                    if ( true === Wp_dp_Member_Permissions::check_permissions('listings') ) {
                                        $listing_parent_class = '';

                                        if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'published_listings' ) {
                                            $active_tab = 'wp_dp_member_published_listings';
                                            $listing_parent_class = 'nav-open active';
                                        }
                                        if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'pending_listings' ) {
                                            $active_tab = 'wp_dp_member_pending_listings';
                                            $listing_parent_class = 'nav-open active';
                                        }
                                        if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'expired_listings' ) {
                                            $active_tab = 'wp_dp_member_expired_listings';
                                            $listing_parent_class = 'nav-open active';
                                        }
                                        ?>
                                        <li class="accordian <?php echo esc_html($listing_parent_class); ?>"><a href="javascript:void(0);"><i class="icon-listing-user-account"></i><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_my_prop'); ?></a>
                                            <ul>
                                                <li class="user_dashboard_ajax" id="wp_dp_member_published_listings" data-queryvar="dashboard=published_listings"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_published_listings'); ?> <b class="label count-submitted-enquiries">(<?php echo wp_dp_cs_allow_special_char($dashboard_count_published_listing); ?>)</b></a></li>
                                                <li class="user_dashboard_ajax" id="wp_dp_member_pending_listings" data-queryvar="dashboard=pending_listings"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_pending_listings'); ?> <b class="label count-submitted-enquiries">(<?php echo wp_dp_cs_allow_special_char($dashboard_count_pending_listing); ?>)</b></a></li>
                                                <li class="user_dashboard_ajax" id="wp_dp_member_expired_listings" data-queryvar="dashboard=expired_listings"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_expired_listings'); ?> <b class="label count-submitted-enquiries">(<?php echo wp_dp_cs_allow_special_char($dashboard_count_expired_listing); ?>)</b></a></li>
                                            </ul>
                                        </li>
    <?php
}
if ( true === Wp_dp_Member_Permissions::check_permissions('promotion') ) {

    if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'promoted_listings' ) {
        $active_tab = 'wp_dp_member_promoted_listings';
    }
    ?>
                                        <li class="user_dashboard_ajax <?php echo esc_html($listing_parent_class); ?>" id="wp_dp_member_promoted_listings" data-queryvar="dashboard=promoted_listings"><a href="javascript:void(0);"><i class="icon-megaphone-with-waves"></i><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_promoted_listing') ?></a></li>
                                        <?php
                                    }
                                    if ( true === Wp_dp_Member_Permissions::check_permissions('enquiries') ) {

                                        //$member_id = get_current_user_id();
                                        $args = array(
                                            'post_type' => 'listing_enquiries',
                                            'post_status' => 'publish',
                                            'posts_per_page' => '1',
                                            'fields' => 'ids',
                                            'meta_query' => array(
                                                'relation' => 'AND',
                                                array(
                                                    'key' => 'wp_dp_enquiry_member',
                                                    'value' => $member_id,
                                                    'compare' => '=',
                                                ),
                                                array(
                                                    'key' => 'buyer_read_status',
                                                    'value' => 0,
                                                    'compare' => '=',
                                                    'type' => 'numeric',
                                                )
                                            ),
                                        );

                                        $enquiry_query = new WP_Query($args);
                                        $total_enquiries = $enquiry_query->found_posts;

                                        $received_args = array(
                                            'post_type' => 'listing_enquiries',
                                            'post_status' => 'publish',
                                            'posts_per_page' => 1,
                                            'meta_query' => array(
                                                'relation' => 'AND',
                                                array(
                                                    'key' => 'wp_dp_listing_member',
                                                    'value' => $member_id,
                                                    'compare' => '=',
                                                ),
                                                array(
                                                    'key' => 'seller_read_status',
                                                    'value' => 0,
                                                    'compare' => '=',
                                                    'type' => 'numeric',
                                                )
                                            ),
                                        );

                                        $received_enquiry_query = new WP_Query($received_args);
                                        $total_received_inquiries = $received_enquiry_query->found_posts;

                                        wp_reset_postdata();

                                        $messages_parent_class = '';
                                        if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'enquiries' ) {
                                            $active_tab = 'wp_dp_member_enquiries';
                                            $messages_parent_class = 'nav-open active';
                                        }
                                        if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'received_enquiries' ) {
                                            $active_tab = 'wp_dp_member_received_enquiries';
                                            $messages_parent_class = 'active nav-open';
                                        }
                                        ?>
                                        <li class="accordian <?php echo esc_html($messages_parent_class); ?>"><a href="javascript:void(0);"><i class="icon-message-user-account"></i><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_enquires'); ?> <b class="label count-all-enquiries"><?php echo absint(($total_enquiries + $total_received_inquiries)); ?></b></a>
                                            <ul>
                                                <li class="user_dashboard_ajax" id="wp_dp_member_enquiries" data-queryvar="dashboard=enquiries"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_member_submitted_messages'); ?> <b class="label count-submitted-enquiries">(<?php echo absint($total_enquiries); ?>)</b></a></li>
                                                <li class="user_dashboard_ajax" id="wp_dp_member_received_enquiries" data-queryvar="dashboard=received_enquiries"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_member_received_messages'); ?> <b class="label count-received-enquiries">(<?php echo absint($total_received_inquiries); ?>)</b></a></li>
                                            </ul>
                                        </li>
                                        <?php
                                    }

                                    if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'enquiries_received' ) {
                                        $data_param = isset($_REQUEST['data_param']) && $_REQUEST['data_param'] != '' ? $_REQUEST['data_param'] : '';
                                        $data_sort = isset($_REQUEST['data_sort']) && $_REQUEST['data_sort'] != '' ? $_REQUEST['data_sort'] : '';
                                        $data_type = isset($_REQUEST['data_type']) && $_REQUEST['data_type'] != '' ? $_REQUEST['data_type'] : '';
                                        $active_tab = 'wp_dp_member_received_enquiries';
                                        if ( $data_param != '' ) {
                                            $active_tab = 'wp_dp_member_received_enquiries_' . $data_param;
                                        }
                                        if ( $data_sort != '' ) {
                                            $data_sort = 'data-sort="' . $data_param . '"';
                                        }
                                        if ( $data_type != '' ) {
                                            $data_type = 'data-type="' . $data_type . '"';
                                        }
                                        echo '<li class="user_dashboard_ajax" id="' . $active_tab . '" ' . $data_sort . ' ' . $data_type . ' data-param="' . $data_param . '" data-queryvar="dashboard=enquiries_received" style="display: none;"><a href="javascript:void(0);"><i class="icon-enquiries"></i>' . wp_dp_plugin_text_srt('wp_dp_member_enquiries_received_enquiries') . '</a></li>';
                                    }

                                    if ( true === Wp_dp_Member_Permissions::check_permissions('arrange_viewings') ) {
                                        $args = array(
                                            'post_type' => 'listing_viewings',
                                            'post_status' => 'publish',
                                            'posts_per_page' => '1',
                                            'fields' => 'ids',
                                            'meta_query' => array(
                                                'relation' => 'AND',
                                                array(
                                                    'key' => 'wp_dp_viewing_member',
                                                    'value' => $member_id,
                                                    'compare' => '=',
                                                ),
                                                array(
                                                    'key' => 'buyer_read_status',
                                                    'value' => 0,
                                                    'compare' => '=',
                                                    'type' => 'numeric',
                                                )
                                            ),
                                        );

                                        $order_query = new WP_Query($args);
                                        $total_inquiries = $order_query->found_posts;

                                        $received_viewing_args = array(
                                            'post_type' => 'listing_viewings',
                                            'post_status' => 'publish',
                                            'posts_per_page' => '1',
                                            'fields' => 'ids',
                                            'meta_query' => array(
                                                'relation' => 'AND',
                                                array(
                                                    'key' => 'wp_dp_listing_member',
                                                    'value' => $member_id,
                                                    'compare' => '=',
                                                ),
                                                array(
                                                    'key' => 'seller_read_status',
                                                    'value' => 0,
                                                    'compare' => '=',
                                                    'type' => 'numeric',
                                                )
                                            ),
                                        );

                                        $received_viewing_query = new WP_Query($received_viewing_args);
                                        $total_received_viewing = $received_viewing_query->found_posts;
                                        wp_reset_postdata();

                                        $viewing_parent_class = '';
                                        if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'viewings' ) {
                                            $active_tab = 'wp_dp_member_viewings';
                                            $viewing_parent_class = 'nav-open active';
                                        }
                                        
                                    }
                                  

                                    if ( true === Wp_dp_Member_Permissions::check_permissions('reviews') ) {
                                        $reviews_parent_class = '';
                                        if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'reviews' ) {
                                            $active_tab = 'wp_dp_publisher_reviews';
                                            $reviews_parent_class = 'nav-open active';
                                        }
                                        if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'my_reviews' ) {
                                            $active_tab = 'wp_dp_publisher_my_reviews';
                                            $reviews_parent_class = 'nav-open active';
                                        }
                                        ?>

                                        <li class="accordian <?php echo esc_html($reviews_parent_class); ?>"><a href="javascript:void(0);"><i class="icon-review-user-account"></i><?php echo wp_dp_plugin_text_srt('wp_dp_reviews_all_reviews_heading'); ?></a>
                                            <ul>
                                                <li class="user_dashboard_ajax" id="wp_dp_publisher_reviews" data-queryvar="dashboard=reviews"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_reviews_submitted'); ?></a></li>
                                                <li class="user_dashboard_ajax" id="wp_dp_publisher_my_reviews" data-queryvar="dashboard=my_reviews"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_reviews_received'); ?></a></li>
                                            </ul>
                                        </li>
    <?php
}

if ( true === Wp_dp_Member_Permissions::check_permissions('alerts') ) {
    if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'alerts' ) {
        $active_tab = 'wp_dp_member_listingalerts';
    }
    echo do_action('wp_dp_top_menu_member_dashboard', wp_dp_plugin_text_srt('wp_dp_member_dashboard_alerts_searches'), '<i class="icon-email-user-account"></i>');
}

if ( true === Wp_dp_Member_Permissions::check_permissions('favourites') ) {
    if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'favourites' ) {
        $active_tab = 'wp_dp_member_favourites';
    }
    echo do_action('wp_dp_top_menu_favourites_dashboard', wp_dp_plugin_text_srt('wp_dp_member_dashboard_fav_prop'), '<i class="icon-favourite-user-account"></i>');
}

if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'prop_notes' ) {
    $active_tab = 'wp_dp_member_prop_notes';
}
if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'hidden_listings' ) {
    $active_tab = 'wp_dp_member_hidden_listings';
}

                                    if ( true === Wp_dp_Member_Permissions::check_permissions('packages') ) {
                                        if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'packages' ) {
                                            $active_tab = 'wp_dp_member_packages';
                                        }
                                        ?>
                                        <li class="user_dashboard_ajax" id="wp_dp_member_packages" data-queryvar="dashboard=packages"><a href="javascript:void(0);"><i class="icon-package-user-account"></i><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_packages'); ?></a></li>

                                        <?php
                                    }

                                    if ( true === Wp_dp_Member_Permissions::check_permissions('transactions') ) {
                                        if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'transactions' ) {
                                            $active_tab = 'wp_dp_member_transactions';
                                        }
                                        ?>
                                        <li class="user_dashboard_ajax" id="wp_dp_member_transactions" data-queryvar="dashboard=transactions"><a href="javascript:void(0);"><i class="icon-invoice-user-account"></i><?php echo wp_dp_plugin_text_srt('wp_dp_transactions_invoices'); ?></a></li>

                                        <?php
                                    }

                                    if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'packages' ) {
                                        $active_tab = 'wp_dp_member_packages';
                                    }
                                    if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'change_pass' ) {

                                        $active_tab = 'wp_dp_member_change_password';
                                    }
                                    if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'location' ) {
                                        $child_tab = 'wp_dp_member_change_locations';
                                        $active_tab = 'wp_dp_member_accounts';
                                    }
                                    if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'opening-hours' ) {
                                        $child_tab = 'wp_dp_member_opening_hours';
                                        $active_tab = 'wp_dp_member_accounts';
                                    }
                                    if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'team_members' ) {
                                        $active_tab = 'wp_dp_member_company';
                                    }

                                    if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'account' ) {
                                        $active_tab = 'wp_dp_member_accounts';
                                        $profile_parent_class = 'active';
                                    }
                                    ?>
                                    <li class="user_dashboard_ajax" id="wp_dp_member_accounts" data-queryvar="dashboard=account"><a href="javascript:void(0);"><i class="icon-settings-user-account"></i><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_account_stng'); ?></a></li>
                                    <li><a href="<?php echo esc_url(wp_logout_url(wp_dp_server_protocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])) ?>"><i class="icon-logout-user-account"></i><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_signout'); ?></a></li>
                                    <?php
                                    if ( true === Wp_dp_Member_Permissions::check_permissions('add_listing') ) {
                                        wp_enqueue_script('wp-dp-listing-user-add');
                                        //editor
                                        wp_enqueue_style('jquery-te');
                                        wp_enqueue_script('jquery-te');
                                        wp_enqueue_script('jquery-ui');
                                        wp_enqueue_script('wp-dp-listing-categories');
                                        //iconpicker
                                        wp_enqueue_style('fonticonpicker');
                                        wp_enqueue_script('fonticonpicker');

                                        wp_enqueue_style('datetimepicker');
                                        wp_enqueue_script('datetimepicker');

                                        // enqueue required script
                                        wp_enqueue_script('wp-dp-tags-it');
                                        $package_id = '';
                                        if ( isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'add_listing' ) {
                                            $active_tab = 'wp_dp_member_create_listing';
                                            if ( isset($_REQUEST['package_id']) && $_REQUEST['package_id'] != '' && $_REQUEST['package_id'] != 0 ) {
                                                $package_id = 'data-package-id="' . $_REQUEST['package_id'] . '"';
                                            }
                                        }
                                        ?>
                                        <li style="display:none;" class="user_dashboard_ajax" id="wp_dp_member_create_listing" <?php echo $package_id; ?> data-listing-id="<?php echo (isset($_REQUEST['listing_id']) ? $_REQUEST['listing_id'] : '') ?>" data-queryvar="dashboard=add_listing"><a href="javascript:void(0);"><i class="icon-plus3"></i><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_add_listing'); ?></a></li>
                                    <?php }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <?php
                                    $user = wp_get_current_user();
                                    $current_user_id = isset($user->ID) ? $user->ID : '';
                                    $company_id = '';
                                    if ( $current_user_id != 0 && $current_user_id != '' ) {
                                        $company_id = get_user_meta($current_user_id, 'wp_dp_company', true);
                                    }
                                    $args_all = array(
                                        'post_type' => 'listings',
                                        'posts_per_page' => 1,
                                        'fields' => 'ids',
                                        'meta_query' => array(
                                            'relation' => 'AND',
                                            array(
                                                'key' => 'wp_dp_listing_member',
                                                'value' => $company_id,
                                                'compare' => '=',
                                            ),
                                            array(
                                                'key' => 'wp_dp_listing_status',
                                                'value' => 'delete',
                                                'compare' => '!=',
                                            )
                                        ),
                                    );
                                    $query_all = new WP_Query($args_all);
                                    $count_lisings_all = $query_all->found_posts;
                                    if ( $count_lisings_all < 10 ) {
                                        $count_lisings_all = '0' . $count_lisings_all;
                                    }


                                    $args_publish = array(
                                        'post_type' => 'listings',
                                        'posts_per_page' => 1,
                                        'fields' => 'ids',
                                        'meta_query' => array(
                                            'relation' => 'AND',
                                            array(
                                                'key' => 'wp_dp_listing_member',
                                                'value' => $company_id,
                                                'compare' => '=',
                                            ),
                                            array(
                                                'key' => 'wp_dp_listing_status',
                                                'value' => 'active',
                                                'compare' => '=',
                                            ),
                                            array(
                                                'key' => 'wp_dp_listing_expired',
                                                'value' => strtotime(date('Y-m-d')),
                                                'compare' => '>=',
                                            ),
                                        ),
                                    );
                                    $published_listing_qry = new WP_Query($args_publish);
                                    $count_published_listing = $published_listing_qry->found_posts;
                                    if ( $count_published_listing < 10 ) {
                                        $count_published_listing = '0' . $count_published_listing;
                                    }

                                    $args_pending = array(
                                        'post_type' => 'listings',
                                        'posts_per_page' => 1,
                                        'fields' => 'ids',
                                        'meta_query' => array(
                                            'relation' => 'AND',
                                            array(
                                                'key' => 'wp_dp_listing_member',
                                                'value' => $company_id,
                                                'compare' => '=',
                                            ),
                                            array(
                                                'key' => 'wp_dp_listing_status',
                                                'value' => 'awaiting-activation',
                                                'compare' => '=',
                                            ),
                                            array(
                                                'key' => 'wp_dp_listing_expired',
                                                'value' => strtotime(date('Y-m-d')),
                                                'compare' => '>=',
                                            ),
                                        ),
                                    );
                                    $pending_listing_qry = new WP_Query($args_pending);
                                    $count_pending_listing = $pending_listing_qry->found_posts;
                                    if ( $count_pending_listing < 10 ) {
                                        $count_pending_listing = '0' . $count_pending_listing;
                                    }

                                    $args_expired = array(
                                        'post_type' => 'listings',
                                        'posts_per_page' => 1,
                                        'fields' => 'ids',
                                        'meta_query' => array(
                                            'relation' => 'AND',
                                            array(
                                                'key' => 'wp_dp_listing_member',
                                                'value' => $company_id,
                                                'compare' => '=',
                                            ),
                                            array(
                                                'key' => 'wp_dp_listing_expired',
                                                'value' => strtotime(date('Y-m-d')),
                                                'compare' => '<',
                                            ),
                                            array(
                                                'key' => 'wp_dp_listing_status',
                                                'value' => 'delete',
                                                'compare' => '!=',
                                            )
                                        ),
                                    );
                                    $expired_listing_qry = new WP_Query($args_expired);
                                    $count_expired_listing = $expired_listing_qry->found_posts;
                                    if ( $count_expired_listing < 10 ) {
                                        $count_expired_listing = '0' . $count_expired_listing;
                                    }
                                    wp_reset_postdata();

                                    if ( $active_tab == 'wp_dp_member_suggested' || $active_tab == 'wp_dp_member_listings' || $active_tab == 'wp_dp_member_published_listings' || $active_tab == 'wp_dp_member_pending_listings' || $active_tab == 'wp_dp_member_expired_listings' || $active_tab == 'wp_dp_member_promoted_listings' ) {
                                        $user_statics_display = 'block';
                                    } else {
                                        $user_statics_display = 'none';
                                    }
// user profile not active message
                                    $member_profile_status = get_post_meta($user_company_id, 'wp_dp_user_status', true);
                                    if ( $member_profile_status != 'active' ) {
                                        ?>
                        <div class="user-message alert" >
                            <p><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_profile_not_active'); ?></p>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="user-listings-statics" style="display:<?php echo esc_html($user_statics_display); ?>;">
                        <ul class="listings-statics row">
                            <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="text-holder">
                                    <strong><?php echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_statics_all_listings'); ?></strong>
                                    <span><?php echo esc_html($count_lisings_all); ?></span>
                                    <i class="icon-list5"></i>
                                </div>
                            </li>
                            <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="text-holder">
                                    <strong><?php echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_listing_statics_published'); ?></strong>
                                    <span><?php echo esc_html($count_published_listing); ?></span>
                                    <i class="icon-tools2"></i>
                                </div>
                            </li>
                            <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="text-holder">
                                    <strong><?php echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_listing_statics_pending'); ?></strong>
                                    <span><?php echo esc_html($count_pending_listing); ?></span>
                                    <i class="icon-clock3"></i>
                                </div>
                            </li>
                            <li class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="text-holder">
                                    <strong><?php echo wp_dp_plugin_text_srt('wp_dp_dashboard_user_listing_statics_expired'); ?></strong>
                                    <span><?php echo esc_html($count_expired_listing); ?></span>
                                    <i class="icon-back-in-time"></i>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="user-account-holder loader-holder dashboard-loader">
                        <div class="user-holder animated" data-wow-delay="0.5s">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<?php
if ( ! isset($_REQUEST['dashboard']) || $_REQUEST['dashboard'] == '' ) {
    ?>
                                    <script type="text/javascript">
                                        jQuery(document).ready(function (e) {
                                            jQuery('#wp_dp_member_suggested>').trigger('click');
                                        });
                                    </script>
                                <?php } ?>
                            </div> 
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="page-section">
        <div class="<?php echo esc_html($container_class); ?>">
            <div class="row">
                <!-- warning popup -->
                <div id="id_confrmdiv">
                    <div class="cs-confirm-container">
                        <i class="icon-sad"></i>
                        <div class="message"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_want_to_profile'); ?></div>
                        <a href="javascript:void(0);" id="id_truebtn"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_delete_yes'); ?></a>
                        <a href="javascript:void(0);" id="id_falsebtn"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_delete_no'); ?></a>
                    </div>
                </div>
                <!-- end warning popup -->
            </div>
        </div>
    </div>
</div>
<?php
if ( $active_tab != '' ) {
    if ( defined('ICL_LANGUAGE_CODE') ) {
        $lang_code = ICL_LANGUAGE_CODE;
        if ( isset($_GET['wpml_lang']) && $_GET['wpml_lang'] != '' ) {
            $lang_code = $_GET['wpml_lang'];
        }
    }
    ?>
    <script type="text/javascript">
        var page_id_all = <?php echo isset($_REQUEST['page_id_all']) && $_REQUEST['page_id_all'] != '' ? $_REQUEST['page_id_all'] : '1' ?>;
        var lang_code = "<?php echo isset($lang_code) && $lang_code != '' ? $lang_code : '' ?>";
        jQuery(document).ready(function (e) {
            jQuery('#<?php echo esc_html($active_tab); ?>').trigger('click');
        });
        var count = 0;
        jQuery(document).ajaxComplete(function (event, request, settings) {
            if (count == 2) {
                jQuery('#<?php echo esc_html($child_tab); ?>').trigger('click');
            }
            count++;
        });

        function show_child() {
            jQuery('#<?php echo esc_html($child_tab); ?>').trigger('click');
        }
    </script>
    <?php
}

get_footer();
