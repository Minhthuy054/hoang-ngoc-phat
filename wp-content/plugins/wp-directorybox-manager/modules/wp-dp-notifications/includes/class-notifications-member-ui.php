<?php
/**
 * Create Employer Dashboard UI
 *
 * @package	Directory Box
 */
// Direct access not allowed.
if ( ! defined('ABSPATH') ) {
    exit;
}

/**
 * WP_Listing_Hunt_Alerts_Shortcode class.
 */
if(!class_exists('WP_Listing_Hunt_Employer_UI')){
class WP_Listing_Hunt_Employer_UI {

    /**
     * Construct.
     */
    public function __construct() {
        // Initialize Addon
        $this->init();
    }

    public function init() {
        // Add hook for dashboard member top menu links.
        add_action('wp_dp_top_menu_member_dashboard', array( $this, 'top_menu_member_dashboard_callback' ), 10, 3);

        // Add Employer left menu and tabs.
        add_action('wp_dp_member_dashboard_menu_left', array( $this, 'add_member_dashboard_menu_left' ), 10, 2);

        add_action('wp_dp_member_admin_tab_menu', array( $this, 'member_admin_tab_menu_callback' ), 10);
        add_action('wp_dp_member_admin_tab_content', array( $this, 'member_admin_tab_content_callback' ), 10);

        add_action('wp_dp_member_dashboard_tabs', array( $this, 'add_member_dashboard_tab' ), 5, 2);
        // Handle AJAX to list all member listing alerts in frontend dashboard.
        add_action('wp_ajax_wp_dp_member_listingalerts', array( $this, 'list_member_listingalerts_callback' ));
        add_action('wp_ajax_nopriv_wp_dp_member_listingalerts', array( $this, 'list_member_listingalerts_callback' ));
        add_filter('wp_dp_member_permissions', array( $this, 'wp_dp_member_permissions_callback' ), 10, 1);
    }

    public function wp_dp_member_permissions_callback($permissions = array()) {
        if ( ! is_array($permissions) ) {
            $permissions = array();
        }
        $permissions['alerts'] = wp_dp_plugin_text_srt('wp_dp_notifactn_member_alert_search_manage');
        return $permissions;
    }

    public function top_menu_member_dashboard_callback($wp_dp_page_id, $icon = '', $search_alerts_url = '') {
        $permissions = apply_filters('member_permissions', '');
        $permission = apply_filters('check_permissions', 'alerts', '');
        $permission_added = false;

        if ( array_key_exists('alerts', $permissions) ) {
            $permission_added = true;
        }

        if ( isset($search_alerts_url) && $search_alerts_url <> '' ) {
            if ( $permission == true || $permission_added == false ) {
                echo ' <li class="user_dashboard_ajax" id="wp_dp_member_listingalerts" data-queryvar="dashboard=alerts"><a href="' . $search_alerts_url . '">' . $icon . $wp_dp_page_id . '</a></li>';
            }
        } else if ( $permission == true || $permission_added == false ) {
            echo ' <li class="user_dashboard_ajax" id="wp_dp_member_listingalerts" data-queryvar="dashboard=alerts"><a href="javascript:void(0);">' . $icon . $wp_dp_page_id . '</a></li>';
        }
    }

    public function add_member_dashboard_menu_left($profile_tab, $uid) {
        $is_active = '';
        if ( isset($profile_tab) && $profile_tab == 'listing-alerts' ) {
            $is_active = ' active ';
        }
        echo '
			<li id="member_left_listing_alerts_link" class="' . $is_active . '">
				<a id="member_postlistings_click_link_id" href="javascript:void(0);" onclick="wp_dp_dashboard_tab_load(\'listing-alerts\', \'member\', \'' . esc_js(admin_url('admin-ajax.php')) . '\', \'' . absint($uid) . '\');" >
					<i class="icon-bell-o"></i>' . wp_dp_plugin_text_srt('wp_dp_notifactn_member_listing_alerts') . '
				</a>
			</li>
		';
    }

    public function member_admin_tab_menu_callback() {
        echo '<li><a href="javascript:void(0);" name="#tab-Searches22" href="javascript:;"><i class="icon-add_alert "></i>' . wp_dp_plugin_text_srt('wp_dp_notifactn_member_searches_alerts') . '</a></li>';
    }

    public function member_admin_tab_content_callback() {
        ?>
        <div id="tab-Searches22">
            <?php $this->wp_dp_listingalerts(); ?>
        </div>
        <?php
    }

    /**
     * Start Function Search Alerts
     */
    public function wp_dp_listingalerts() {
        global $post, $search_keywords, $post_id;
        $wp_dp_blog_num_post = 10;

        $uid = empty($_POST['wp_dp_uid']) ? '' : sanitize_text_field($_POST['wp_dp_uid']);
        $uid = '111';
        if ( $uid <> '' ) {
            $user_id = wp_dp_get_user_id();
            if ( ! empty($user_id) ) {
                // Get count of total posts
                $args = array(
                    'post_type' => 'listing-alert',
                    'posts_per_page' => -1,
                    'orderby' => 'post_date',
                    'order' => 'DESC',
                    'meta_query' =>
                    array(
                        array(
                            'key' => 'wp_dp_member',
                            'value' => $post_id,
                            'compare' => '=',
                        ),
                    ),
                );
                $listing_alerts = new WP_Query($args);
                $alerts_count = $listing_alerts->post_count;

                $page_num = empty($_POST['page_id_all']) ? 1 : sanitize_text_field($_POST['page_id_all']);
                // Get alerts with respect to pagination.
                $args = array(
                    'post_type' => 'listing-alert',
                    'posts_per_page' => $wp_dp_blog_num_post,
                    'paged' => $page_num,
                    'orderby' => 'post_date',
                    'order' => 'DESC',
                    'meta_query' =>
                    array(
                        array(
                            'key' => 'wp_dp_member',
                            'value' => $post_id,
                            'compare' => '=',
                        ),
                    ),
                );
                $listing_alerts = new WP_Query($args);
            }
            ?>
            <div class="cs-loader"></div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <section class="cs-favorite-listings">
                        <div class="element-title">
                            <h4><?php echo wp_dp_plugin_text_srt('wp_dp_notifactn_member_ad_alerts'); ?></h4>
                        </div>
                        <?php
                        $wp_dp_plugin_options = get_option('wp_dp_plugin_options');
                        $wp_dp_plugin_options = apply_filters('wp_dp_translate_options', $wp_dp_plugin_options);
                        $search_list_page = '';
                        if ( ! empty($wp_dp_plugin_options) && $wp_dp_plugin_options['wp_dp_search_result_page'] ) {
                            $search_list_page = $wp_dp_plugin_options['wp_dp_search_result_page'];
                        }
                        if ( ! empty($listing_alerts) && $listing_alerts->have_posts() ) {
                            ?>
                            <ul class="feature-listings">
                                <?php
                                while ( $listing_alerts->have_posts() ) :
                                    $listing_alerts->the_post();

                                    $wp_dp_listing_expired = get_post_meta($post->ID, 'wp_dp_listing_expired', true) . '<br>';
                                    $wp_dp_org_name = get_post_meta($post->ID, 'wp_dp_org_name', true);
                                    // Get listing's Meta Data.
                                    $wp_dp_name = get_post_meta($post->ID, 'wp_dp_name', true);
                                    $wp_dp_query = get_post_meta($post->ID, 'wp_dp_query', true);
                                    $wp_dp_complete_url = get_post_meta($post->ID, 'wp_dp_complete_url', true);
                                    // Get selected frequencies. 
                                    $frequencies = array(
                                        'annually',
                                        'biannually',
                                        'monthly',
                                        'fortnightly',
                                        'weekly',
                                        'daily',
                                        'never',
                                    );
                                    $selected_frequencies = array();
                                    foreach ( $frequencies as $key => $frequency ) {
                                        $frequency_val = get_post_meta($post->ID, 'wp_dp_frequency_' . $frequency, true);
                                        if ( ! empty($frequency_val) && $frequency_val == 'on' ) {
                                            $selected_frequencies[] = $frequency;
                                        }
                                    }

                                    $search_keywords = WP_Listing_Hunt_Alert_Helpers::query_to_array($wp_dp_query);
                                    ?>
                                    <script>


                                        (function ($) {
                                            $(function () {
                                                $(".delete-listing-alert a").click(function () {
                                                    var post_id = $(this).data("post-id");
                                                    $('#id_confrmdiv').show();
                                                    var dataString = 'post_id=' + post_id + '&action=wp_dp_remove_listing_alert';
                                                    jQuery('.holder-' + post_id).find('#remove_resume_link' + post_id).html('<i class="fancy-spinner"></i>');
                                                    jQuery.ajax({
                                                        type: "POST",
                                                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                                        data: dataString,
                                                        dataType: "JSON",
                                                        success: function (response) {
                                                            if (response.status == 0) {
                                                                show_alert_msg(response.msg);
                                                            } else {
                                                                jQuery('.holder-' + post_id).remove();
                                                                var msg_obj = {msg: 'Deleted Successfully.', type: 'success'};
                                                                wp_dp_show_response(msg_obj);
                                                            }
                                                        }
                                                    });
                                                    $('#id_confrmdiv').hide();
                                                    return false;
                                                    $('#id_falsebtn').click(function () {
                                                        $('#id_confrmdiv').hide();
                                                        return false;
                                                    });
                                                    return false;
                                                });
                                            });
                                        })(jQuery);
                                    </script>
                                    <li class="holder-<?php echo intval($post->ID); ?>">
                                        <div class="company-detail-inner">
                                            <h6><a href="<?php echo wp_dp_wpml_lang_page_permalink($search_list_page, 'page') . '?' ?>"><?php echo esc_html($wp_dp_name); ?></a></h6><br>
                                            <b><?php echo wp_dp_plugin_text_srt('wp_dp_notifactn_member_search_keywords'); ?> </b><br>
                                            <?php
                                            $all_words_search = array();
                                            foreach ( $search_keywords as $key => $value ) {
                                                $all_words_search[] = '<em>' . $key . ' : ' . $value . '</em> ';
                                            }
                                            $all_search_words = implode('', array_values($all_words_search));
                                            if ( ! empty($all_search_words) ) {
                                                echo ($all_search_words);
                                            } else {
                                                echo wp_dp_plugin_text_srt('wp_dp_alerts_all_listings');
                                            }
                                            ?>
                                        </div>

                                        <div class="company-date-option">
                                            <?php echo implode(', ', array_map('ucfirst', $selected_frequencies)); ?>
                                            <div class="control delete-listing-alert">
                                                <a data-toggle="tooltip" data-placement="top" title="<?php echo wp_dp_plugin_text_srt('wp_dp_notifactn_member_remove'); ?>" id="remove_resume_link<?php echo absint($post->ID); ?>" href="#"  class="delete-listing delete" data-post-id="<?php echo absint($post->ID); ?>">
                                                    <i class="icon-close2"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                    <?php
                                endwhile;
                                wp_reset_postdata();
                                ?>
                            </ul>
                            <?php
                            //==Pagination Start
                            if ( $alerts_count > $wp_dp_blog_num_post && $wp_dp_blog_num_post > 0 ) {
                                echo '<nav>';
                                echo wp_dp_ajax_pagination($alerts_count, $wp_dp_blog_num_post, 'listing-alerts', 'member', $uid, '');
                                echo '</nav>';
                            }//==Pagination End 
                            ?>
                            <?php
                        } else {
                            echo '<div class="cs-no-record">' . wp_dp_info_messages_listing(wp_dp_plugin_text_srt('wp_dp_notifactn_member_dont_have_search_update')) . '</div>';
                        }
                        ?>
                    </section>
                </div>
            </div>
            <?php
        } else {
            echo '<div class="no-result"><h1>' . wp_dp_plugin_text_srt('wp_dp_notifactn_member_create_user_profile') . '</h1></div>';
        }
        ?>
        <script>
            jQuery(document).ready(function () {
                jQuery('[data-toggle="tooltip"]').tooltip();
            });
        </script>
        <?php
    }

    public function add_member_dashboard_tab($profile_tab, $uid) {
        $is_active = '';
        $script = '';
        if ( isset($profile_tab) && $profile_tab == 'listing-alerts' ) {
            $is_active = 'active';
            $script = '
				<script type="text/javascript">
					jQuery(window).load(function () {
						(function (admin_url, wp_dp_uid) {
							var dataString = \'wp_dp_uid=\' + wp_dp_uid + \'&action=wp_dp_member_listingalerts\';
							wp_dp_data_loader_load(\'#listing-alerts\');
							jQuery.ajax({
								type: "POST",
								url: admin_url,
								data: dataString,
								success: function (response) {
									jQuery(\'#listing-alerts\').html(response);
									jQuery("#listing-alerts .cs-loader").fadeTo(2000, 500).slideUp(500);
								}
							});
						})("' . esc_js(admin_url('admin-ajax.php')) . '", "' . absint($uid) . '");
					});
				</script>
			';
        }
        echo '
			<div class="tab-pane ' . $is_active . ' fade1 tabs-container" id="listing-alerts">
				<div class="cs-loader"></div>
				' . $script . '
			</div>
		';
    }

    public function list_member_listingalerts_callback() {
        global $post;
        $wp_dp_blog_num_post = 10;

        $uid = empty($_POST['wp_dp_uid']) ? '' : sanitize_text_field($_POST['wp_dp_uid']);
        $uid = '111';
        if ( $uid <> '' ) {
            $user_id = wp_dp_get_user_id();
            // Update member.
            $member_id = get_user_meta($user_id, 'wp_dp_company', true);
            if ( ! empty($user_id) ) {
                // Get count of total posts
                $args = array(
                    'post_type' => 'listing-alert',
                    'posts_per_page' => -1,
                    'orderby' => 'post_date',
                    'order' => 'DESC',
                    'meta_query' =>
                    array(
                        array(
                            'key' => 'wp_dp_member',
                            'value' => $member_id,
                            'compare' => '=',
                        ),
                    ),
                );
                $listing_alerts = new WP_Query($args);
                $alerts_count = $listing_alerts->post_count;

                $page_num = empty($_POST['page_id_all']) ? 1 : sanitize_text_field($_POST['page_id_all']);
                // Get alerts with respect to pagination.
                $args = array(
                    'post_type' => 'listing-alert',
                    'posts_per_page' => $wp_dp_blog_num_post,
                    'paged' => $page_num,
                    'orderby' => 'post_date',
                    'order' => 'DESC',
                    'meta_query' =>
                    array(
                        array(
                            'key' => 'wp_dp_member',
                            'value' => $member_id,
                            'compare' => '=',
                        ),
                    ),
                );
                $listing_alerts = new WP_Query($args);
            }
            $has_border = ' has-border';
            if ( ! empty($listing_alerts) && $listing_alerts->have_posts() ) {
                $has_border = '';
            }
            ?>
            <ul class="breadcrumbs">
                <li class="user_dashboard_ajax" id="wp_dp_member_suggested" data-queryvar="dashboard=suggested"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_Dashboard'); ?></a></li>
                <li class="active"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_alerts_searches'); ?></li>
            </ul>
            <div class="user-dashboard-background">
                <div class="cs-loader"></div>
                <section class="cs-favorite-listings">
                    <div class="element-title<?php echo wp_dp_allow_special_char($has_border); ?>">
                        <h4><?php echo wp_dp_plugin_text_srt('wp_dp_notifactn_member_searches_alerts'); ?></h4>
                    </div>
                    <?php
                    $wp_dp_plugin_options = get_option('wp_dp_plugin_options');
                    $wp_dp_plugin_options = apply_filters('wp_dp_translate_options', $wp_dp_plugin_options);
                    $search_list_page = '';
                    if ( ! empty($wp_dp_plugin_options) && $wp_dp_plugin_options['wp_dp_search_result_page'] ) {
                        $search_list_page = $wp_dp_plugin_options['wp_dp_search_result_page'];
                    }
                    if ( ! empty($listing_alerts) && $listing_alerts->have_posts() ) {
                        ?>
                        <ul class="feature-listings">
                            <?php
                            while ( $listing_alerts->have_posts() ) :
                                $listing_alerts->the_post();

                                $wp_dp_listing_expired = get_post_meta($post->ID, 'wp_dp_listing_expired', true) . '<br>';
                                $wp_dp_org_name = get_post_meta($post->ID, 'wp_dp_org_name', true);
                                // Get listing's Meta Data.
                                $wp_dp_name = get_post_meta($post->ID, 'wp_dp_name', true);
                                $wp_dp_query = get_post_meta($post->ID, 'wp_dp_query', true);
                                $wp_dp_complete_url = get_post_meta($post->ID, 'wp_dp_complete_url', true);

                                // Get selected frequencies.
                                $frequencies = array(
                                    'annually',
                                    'biannually',
                                    'monthly',
                                    'fortnightly',
                                    'weekly',
                                    'daily',
                                    'never',
                                );
                                $selected_frequencies = array();
                                foreach ( $frequencies as $key => $frequency ) {
                                    $frequency_val = get_post_meta($post->ID, 'wp_dp_frequency_' . $frequency, true);
                                    if ( ! empty($frequency_val) && $frequency_val == 'on' ) {
                                        $selected_frequencies[] = $frequency;
                                    }
                                }

                                $search_keywords = WP_Listing_Hunt_Alert_Helpers::query_to_array($wp_dp_query);
                                ?>
                                <li class="holder-<?php echo intval($post->ID); ?>">
                                    <div class="company-detail-inner">
                                        <h6><a href="<?php echo esc_url($wp_dp_complete_url); //echo esc_url( get_permalink( $search_list_page ) ) . '?' . http_build_query( $search_keywords );                ?>"><?php echo wp_dp_cs_allow_special_char($wp_dp_name); ?></a></h6><br>
                                        <div class="search-keyword-alerts"><b><?php echo wp_dp_plugin_text_srt('wp_dp_notifactn_member_search_keywords'); ?> </b>
                                            <?php
                                            $all_words_search = array();

                                            foreach ($search_keywords as $key => $value) {
                                            if ('adv_filter_toggle' == $key || 'ajax_filter' == $key || 'advanced_search' == $key || 'listing_arg' == $key || 'action' == $key || 'alert-frequency' == $key || 'alerts-name' == $key || 'loc_polygon' == $key || 'alerts-email' == $key || 'loc_polygon_path' == $key) {
                                            continue;
                                            }
                                            $key = str_replace("wp_dp_wp_dp_", "", $key);
                                            $key = str_replace("_", " ", $key);
                                            $all_words_search[] = '<em>' . ucfirst($key) . ' : ' . ($value) . '</em> ';
                                            }
                                            $all_search_words = implode('', array_values($all_words_search));
                                            if ( ! empty($all_search_words)) {
                                            echo ($all_search_words);
                                            } else {
                                            echo wp_dp_plugin_text_srt('wp_dp_alerts_all_listings');
                                            }
                                            ?>

                                            </div>
                                        <div class="search-keyword-alerts"><b><?php echo wp_dp_plugin_text_srt('wp_dp_email_alertt_alert_frequency'); ?> </b>
                                        <?php 
                                            echo '<em>'.implode(', ', array_map('ucfirst', $selected_frequencies)).'</em> ';
                                        ?>
                                        </div>
                                        
                                              
                                        
                                            </div>

                                            <div class = "company-date-option">

                                            <div class = "control delete-listing-alert">
                                            <a data-toggle = "tooltip" data-placement = "top" title = "<?php echo wp_dp_plugin_text_srt('wp_dp_notifactn_member_remove'); ?>" id = "remove_resume_link<?php echo absint($post->ID); ?>" href = "#" class = "delete short-icon" data-post-id = "<?php echo absint($post->ID); ?>">
                                            <span><?php echo wp_dp_plugin_text_srt('wp_dp_notifactn_member_remove');
                                            ?> </span><i class="icon-close"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <?php
                            endwhile;
                            ?>
                        </ul>
                        <?php
                        //==Pagination Start
                        if ( $alerts_count > $wp_dp_blog_num_post && $wp_dp_blog_num_post > 0 ) {
                            echo '<nav>';
                            echo wp_dp_ajax_pagination($alerts_count, $wp_dp_blog_num_post, 'listing-alerts', 'member', $uid, '');
                            echo '</nav>';
                        }//==Pagination End 
                        ?>
                        <?php
                    } else {
                        echo '<div class="cs-no-record">' . wp_dp_info_messages_listing(wp_dp_plugin_text_srt('wp_dp_notifactn_member_dont_have_any_ad_alert')) . '</div>';
                    }
                    ?>
                </section>
            </div>
            <?php
        } else {
            echo '<div class="no-result"><h1>' . wp_dp_plugin_text_srt('wp_dp_notifactn_member_create_user_profile') . '</h1></div>';
        }
        ?>
        <script>
            jQuery(document).ready(function () {
                jQuery('[data-toggle="tooltip"]').tooltip();
            });
        <?php echo WP_Listing_Hunt_Alert_Helpers::get_script_str(); ?>
        </script>
        <?php
        wp_die();
    }

}

new WP_Listing_Hunt_Employer_UI();

}