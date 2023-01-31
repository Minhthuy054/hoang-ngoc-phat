<?php
/**
 * Directory Box Notifications Module
 */
// Direct access not allowed.
if ( ! defined('ABSPATH') ) {
    exit;
}

/**
 * Wp_dp_Listing_Alerts class.
 */
if ( ! class_exists('Wp_dp_Listing_Alerts') ) {

    class Wp_dp_Listing_Alerts {

        public static $listing_details = array();
        public $email_template_type;
        public $email_default_template;
        public $email_template_variables;
        public $admin_notices;
        public $email_template_index;
        public $template_group;

        /**
         * Defined constants, include classes, enqueue scripts, bind hooks to parent plugin
         */
        public function __construct() {
            // Define constants
            define('WP_DP_NOTIFICATIONS_FILE', __FILE__);
            define('WP_DP_NOTIFICATIONS_CORE_DIR', WP_PLUGIN_DIR . '/wp-directorybox-manager/modules/wp-dp-notifications');
            define('WP_DP_NOTIFICATIONS_INCLUDES_DIR', WP_DP_NOTIFICATIONS_CORE_DIR . '/includes');
            define('WP_DP_NOTIFICATIONS_TEMPLATES_DIR', WP_DP_NOTIFICATIONS_CORE_DIR . '/templates');
            define('WP_DP_NOTIFICATIONS_PLUGIN_URL', WP_PLUGIN_URL . '/wp-directorybox-manager/modules/wp-dp-notifications');

            $this->email_template_type = 'Listing Alert';

            $this->email_default_template = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/></head><body style="margin: 0; padding: 0;"><div style="background-color: #eeeeef; padding: 50px 0;"><table style="max-width: 640px;" border="0" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td style="padding: 40px 30px 30px 30px;" align="center" bgcolor="#33333e"><h1 style="color: #fff; line-height:28px;">Listing Alert</h1></td></tr><tr><td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td>Hello! Following are the listings for which you have subscribed at [SITE_NAME]</td></tr><tr><td style="padding: 10px 0 0 0;">Listing Alert Title: [LSITING_ALERT_TITLE]</td></tr><tr><td style="padding: 10px 0 0 0;">[LSITING_ALERT_LSITINGS_LIST]</td></tr><tr><td style="padding: 10px 0 0 0;">All Listings Link: [LSITING_ALERT_FULL_LISTING_URL]</td></tr><tr><td style="padding: 10px 0 0 0;">Listings Count: [LSITING_ALERT_TOTAL_LSITINGS_COUNT]</td></tr><tr><td style="padding: 10px 0 0 0;">Alert Frequency: [LSITING_ALERT_FREQUENCY]</td></tr><tr><td style="padding: 10px 0 0 0;">To unsubscribe listing alert: [LSITING_ALERT_UNSUBSCRIBE_LINK]</td></tr></table></td></tr><tr><td style="background-color: #ffffff; padding: 30px 30px 30px 30px;"><table border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="font-family: Arial, sans-serif; font-size: 14px;">&reg; [SITE_NAME], 2016</td></tr></tbody></table></td></tr></tbody></table></div></body></html>';

            $this->email_template_variables = array(
                array(
                    'tag' => 'LSITING_ALERT_TITLE',
                    'display_text' => 'Listing Alert Title',
                    'value_callback' => array( $this, 'get_listing_alert_title' ),
                ),
                array(
                    'tag' => 'LSITING_ALERT_LSITINGS_LIST',
                    'display_text' => wp_dp_plugin_text_srt('wp_dp_alerts_filtered_listings'),
                    'value_callback' => array( $this, 'get_filtered_listings_list' ),
                ),
                array(
                    'tag' => 'LSITING_ALERT_TOTAL_LSITINGS_COUNT',
                    'display_text' => wp_dp_plugin_text_srt('wp_dp_alerts_total_listings'),
                    'value_callback' => array( $this, 'get_total_listings_count' ),
                ),
                array(
                    'tag' => 'LSITING_ALERT_UNSUBSCRIBE_LINK',
                    'display_text' => wp_dp_plugin_text_srt('wp_dp_alerts_unsubscribe_link'),
                    'value_callback' => array( $this, 'get_unsubscribe_link' ),
                ),
                array(
                    'tag' => 'LSITING_ALERT_FREQUENCY',
                    'display_text' => wp_dp_plugin_text_srt('wp_dp_alerts_listing_alert_frequency'),
                    'value_callback' => array( $this, 'get_frequency' ),
                ),
                array(
                    'tag' => 'LSITING_ALERT_FULL_LISTING_URL',
                    'display_text' => wp_dp_plugin_text_srt('wp_dp_alerts_full_listing_url'),
                    'value_callback' => array( $this, 'get_full_listing_url' ),
                ),
            );

            $this->email_template_index = 'listing-alert-template';
            $this->template_group = 'listing';

            // Initialize Addon
            add_action('init', array( $this, 'init' ), 0);
        }

        /**
         * Initialize application, load text domain, enqueue scripts and bind hooks
         */
        public function init() {
            // Add Plugin textdomain

            add_action('wp_enqueue_scripts', array( $this, 'enqueue_scripts' ));
            // Include Custom Post Type class - Create Notification type and meta boxes.
            require_once WP_DP_NOTIFICATIONS_INCLUDES_DIR . '/class-notifications-post-type.php';
            require_once WP_DP_NOTIFICATIONS_INCLUDES_DIR . '/class-notifications-plugin-options.php';
            require_once WP_DP_NOTIFICATIONS_INCLUDES_DIR . '/class-notifications-helpers.php';
            require_once WP_DP_NOTIFICATIONS_INCLUDES_DIR . '/class-notifications-member-ui.php';
            // Add hook for frontend UI.
            add_action('pre_wp_dp_listings_listing', array( $this, 'frontend_ui_callback' ), 10, 1);
            add_action('wp_dp_save_search_element', array( $this, 'wp_dp_save_search_element_callback' ), 10, 0);
            // Hook our function , create_daily_alert_schedule_callback(), into the action create_daily_alert_schedule.
            add_action('create_daily_alert_schedule_action', array( $this, 'create_daily_alert_schedule_callback' ));
            // temprary testing
            // 
            if ( isset($_GET['wp_dp_cron']) && $_GET['wp_dp_cron'] == 'yes' ) {
                do_action('create_daily_alert_schedule_action');
            }
            // Add optinos in Email Template Settings
            add_filter('wp_dp_email_template_settings', array( $this, 'email_template_settings_callback' ), 0, 1);
            add_action('init', array( $this, 'add_email_template_callback' ), 5);

            add_filter('post_row_actions', array( $this, 'remove_row_actions' ), 10, 2);
            add_action('wp_ajax_wp_dp_email_alerts_detail_content', array( $this, 'wp_dp_email_alerts_detail_content_callback' ));
        }

        public function remove_row_actions($actions) {
            global $post;
            //unset($actions['edit']);
            //unset($actions['trash']);
            return $actions;
        }

       

        /**
         * Enqueue Frontend Styles and Scripts
         */
        public function enqueue_scripts() {
            // Enqueue CSS
            wp_enqueue_style('wp-dp-notifications-css', WP_DP_NOTIFICATIONS_PLUGIN_URL . '/assets/css/wp-dp-notifications-frontend.css');
            // Register JS, should be included in header as this uses some variables.
            wp_register_script('wp-dp-alerts-js', WP_DP_NOTIFICATIONS_PLUGIN_URL . '/assets/js/wp-dp-notifications.js', '', '', true);

            wp_localize_script('wp-dp-alerts-js', 'wp_dp_notifications', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'security' => wp_create_nonce("wp_dp_create_listing_alert"),
                    )
            );
        }

        public function listing_alert_notices_callback() {
            foreach ( $this->admin_notices as $value ) {
                echo ($value);
            }
        }

        public function after_listings_listing_callback($listings_query, $sort_by) {
            echo '<div class="listings_query hidden">' . json_encode($listings_query) . '</div>';
        }

        public function frontend_ui_callback($button_text = '') {
            global $wp_dp_form_fields_frontend;
            wp_enqueue_script('wp-dp-alerts-js');
            wp_enqueue_script('wp-dp-validation-script');
            $wp_dp_plugin_options = get_option('wp_dp_plugin_options');
            $wp_dp_plugin_options = apply_filters('wp_dp_translate_options', $wp_dp_plugin_options);
            $wp_dp_terms_conditions = isset($wp_dp_plugin_options['wp_dp_terms_conditions']) ? $wp_dp_plugin_options['wp_dp_terms_conditions'] : '';
            $frequencies = array(
                'wp_dp_frequency_daily' => wp_dp_plugin_text_srt('wp_dp_alerts_daily'),
                'wp_dp_frequency_weekly' => wp_dp_plugin_text_srt('wp_dp_alerts_weekly'),
                'wp_dp_frequency_fortnightly' => wp_dp_plugin_text_srt('wp_dp_alerts_fortnightly'),
                'wp_dp_frequency_monthly' => wp_dp_plugin_text_srt('wp_dp_alerts_monthly'),
                'wp_dp_frequency_biannually' => wp_dp_plugin_text_srt('wp_dp_alerts_biannually'),
                'wp_dp_frequency_annually' => wp_dp_plugin_text_srt('wp_dp_alerts_annually'),
                'wp_dp_frequency_never' => wp_dp_plugin_text_srt('wp_dp_alerts_never'),
            );
            $options_str = '';
            $options_search_str = '';
            $is_one_checked = false;
            $checked = '';
            $frequency_counter = 2;
            $frequency_exists_flag = false;

            $options_str .= '<ul>';
            $email_field_show = false;
            if ( ! is_user_logged_in() ) {
                $email_field_show = true;
                $options_str .= '<li><input id="frequency-1" name="alert-frequency" class="radio-frequency css-radio" type="radio" value="never" checked="checked"> <label for="frequency-1" class="css-radio-lbl">' . wp_dp_plugin_text_srt('wp_dp_alerts_no_email_alerts') . '</label></li>';
            }

            foreach ( $frequencies as $frequency => $label ) {
                // If it is 'on' then show it's option
                if ( isset($wp_dp_plugin_options[$frequency]) && 'on' == $wp_dp_plugin_options[$frequency] ) {
                    $frequency_exists_flag = true;
                    $options_str .= '<li><input id="frequency-' . $frequency_counter . '" name="alert-frequency" class="radio-frequency css-radio" type="radio" value="' . strtolower($label) . '" ' . $checked . '> <label for="frequency-' . $frequency_counter . '" class="css-radio-lbl">' . $label . ' ' . wp_dp_plugin_text_srt('wp_dp_alerts_summary_email') . '</label></li>';
                    $frequency_counter ++;
                }
            }
            $options_str .= '</ul>';

            // Get logged in user email and hide email address field.
            $user = wp_get_current_user();
            $disabled = '';
            $email = '';
            if ( $user->ID > 0 ) {
                $email = $user->user_email;
                $disabled = ' disabled="disabled"';
            }

            if ( $button_text == 'save_search' ) {
                $button_data = wp_dp_plugin_text_srt('wp_dp_alerts_save_search') . '<i class="icon-plus"></i>';
                $model_title = wp_dp_plugin_text_srt('wp_dp_alerts_save_search');
                $name_placeholder = wp_dp_plugin_text_srt('wp_dp_save_search_name_placeholder');
            } else {
                $button_data = wp_dp_plugin_text_srt('wp_dp_alerts_set_search_alert') . '<i class="icon-plus"></i>';
                $model_title = wp_dp_plugin_text_srt('wp_dp_alerts_create_email_alerts');
                $name_placeholder = wp_dp_plugin_text_srt('wp_dp_save_search_alert_name_placeholder');
            }
            ?>
            <div class="email-me-top">
                <!-- Trigger the modal with a button -->
                <button type="button" class="email-alert-btn" data-toggle="modal" data-target="#listing-alert-model"><?php echo force_balance_tags($button_data); ?></button>
                <!-- Modal -->
                <div id="listing-alert-model" class="modal fade modal-form" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><i class="icon-cross-out"></i></button>
                                <h4 class="modal-title"><?php echo esc_html($model_title); ?></h4>
                            </div>
                            <div class="modal-body">
                                <div class="listing-alert-box listing-alert listing-alert-container-top" >
                                    <div class="search-query-filters">
                                        <h6><?php echo wp_dp_plugin_text_srt('wp_dp_alerts_filter_criteria'); ?></h6>
                                        <ul id="wp-dp-tags" class="tagit ui-widget ui-widget-content ui-corner-all">
            <?php
            //get all query string
            $qrystr = $_REQUEST;
            $query_flag = false;
            if ( isset($qrystr) ) {
                $flag = 1;
                $qrystr = array_filter($qrystr);
                foreach ( $qrystr as $qry_var => $qry_val ) {
                    if ( 'ajax_filter' == $qry_var || 'advanced_search' == $qry_var || 'listing_arg' == $qry_var || 'action' == $qry_var || 'alert-frequency' == $qry_var || 'alerts-name' == $qry_var || 'loc_polygon' == $qry_var || 'alerts-email' == $qry_var || 'loc_polygon_path' == $qry_var || 'adv_filter_toggle' == $qry_var || 'map' == $qry_var )
                        continue;

                    if ( $qry_val != '' ) {
                        $query_flag = true;
                        $flag ++;
                        $final_val = ucwords(str_replace("-", " ", str_replace("+", " ", $qry_val)));
                        if ( $final_val != 'Split_map' ) {
                            echo '<li class="tagit-choice ui-widget-content ui-state-default ui-corner-all tagit-choice-editable"><span class="tagit-label">';
                            if ( is_numeric($final_val) ) {
                                echo ucfirst($qry_var) . ': ' . esc_html($final_val);
                            } else {
                                echo esc_html($final_val);
                            }
                            echo '</span></li>';
                        }
                    }
                }
            }
            if ( $query_flag == false ) {
                echo '<li class="tagit-choice ui-widget-content ui-state-default ui-corner-all tagit-choice-editable"><span class="tagit-label">';
                echo wp_dp_plugin_text_srt('wp_dp_alerts_all_listings');
                echo '</span></li>';
            }
            ?>
                                        </ul>
                                    </div>

                                    <div class="validation error hide">
                                        <label class=""><?php echo wp_dp_plugin_text_srt('wp_dp_alerts_enter_valid_email'); ?></label>
                                    </div>
                                    <div class="newsletter">
            <?php
            echo '<div class="field-holder">';
            $wp_dp_field_opt = array(
                'id' => 'alerts-name',
                'cust_name' => 'alerts-name',
                'std' => '',
                'desc' => '',
                'classes' => 'form-control name-input-top wp-dp-dev-req-field',
                'extra_atr' => ' onkeypress="wp_dp_contact_form_valid_press(this,\'text\')" placeholder="' . esc_html($name_placeholder) . '"',
                'hint_text' => '',
            );
            $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_field_opt);
            echo '</div>';


            $sty_le = ' style="display: none;"';
            if ( $email_field_show ) {
                $sty_le = '';
            }
            echo '<div class="field-holder"' . $sty_le . '>';
            $wp_dp_field_opt = array(
                'id' => 'alerts-email',
                'cust_name' => 'alerts-email',
                'std' => $email,
                'desc' => '',
                'classes' => 'form-control email-input-top alerts-email wp-dp-dev-req-field wp-dp-email-field',
                'extra_atr' => $disabled . ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_alerts_email_address') . '" onkeypress="wp_dp_contact_form_valid_press(this,\'email\')" ',
                'hint_text' => '',
            );
            $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_field_opt);
            echo '</div>';


            if ( strlen($options_str) > 0 && $frequency_exists_flag == true ) :
                ?>
                                            <div class="field-holder">
                                                <div class="alert-frequency">
                                                    <h6><?php echo wp_dp_plugin_text_srt('wp_dp_alerts_alert_frequency'); ?>:</h6>
                <?php echo wp_dp_cs_allow_special_char($options_str); ?>
                                                </div>
                                            </div>
                                                <?php endif; ?>
                                        <div class="field-holder">
                                            <div class="listingalert-submit-button input-button-loader">
                                        <?php
                                        $wp_dp_opt_array = array(
                                            'std' => wp_dp_plugin_text_srt('wp_dp_alerts_submit'),
                                            'cust_name' => 'AlertsEmail',
                                            'return' => false,
                                            'classes' => 'listingalert-submit',
                                            'cust_type' => 'button',
                                            'force_std' => true,
                                        );
                                        $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                        ?>
                                            </div>
                                        </div>
                                    </div>

            <?php if ( $wp_dp_terms_conditions <> '' ) : ?>
                                        <div class="terms-message"><?php echo html_entity_decode($wp_dp_terms_conditions); ?></div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- end class email-me-top -->
            <?php
        }

        public static function create_daily_alert_schedule() {
            // Use wp_next_scheduled to check if the event is already scheduled.
            $timestamp = wp_next_scheduled('create_daily_alert_schedule');

            // If $timestamp == false schedule daily alerts since it hasn't been done previously.
            if ( $timestamp == false ) {
                // Schedule the event for right now, then to repeat daily using the hook 'create_daily_alert_schedule'.
                wp_schedule_event(time(), 'hourly', 'create_daily_alert_schedule_action');
            }
        }

        public function add_email_template_callback() {
            $email_templates = array();
            $email_templates[$this->template_group] = array();
            $email_templates[$this->template_group][$this->email_template_index] = array(
                'title' => $this->email_template_type,
                'template' => $this->email_default_template,
                'email_template_type' => $this->email_template_type,
                'is_recipients_enabled' => false,
                'description' => wp_dp_plugin_text_srt('wp_dp_alerts_email_template_desc'),
                'jh_email_type' => 'html',
            );
            do_action('wp_dp_load_email_templates', $email_templates);
        }

        public function get_template() {
            return wp_dp::get_template($this->email_template_index, $this->email_template_variables, $this->email_default_template);
        }

        public function create_daily_alert_schedule_callback() {
            // Get alerts
            $args = array(
                'post_type' => 'listing-alert',
            );


            $listing_details = array();
            $listing_alerts = new WP_Query($args);

            while ( $listing_alerts->have_posts() ) {

                $listing_alerts->the_post();
                $listing_id = get_the_ID();
                $frequency_annually = get_post_meta($listing_id, 'wp_dp_frequency_annually', true);
                $frequency_biannually = get_post_meta($listing_id, 'wp_dp_frequency_biannually', true);
                $frequency_monthly = get_post_meta($listing_id, 'wp_dp_frequency_monthly', true);
                $frequency_fortnightly = get_post_meta($listing_id, 'wp_dp_frequency_fortnightly', true);
                $frequency_weekly = get_post_meta($listing_id, 'wp_dp_frequency_weekly', true);
                $frequency_daily = get_post_meta($listing_id, 'wp_dp_frequency_daily', true);
                $frequency_never = get_post_meta($listing_id, 'wp_dp_frequency_never', true);
                $last_time_email_sent = get_post_meta($listing_id, 'wp_dp_last_time_email_sent', true);

                $set_frequency = '';
                if ( ! empty($frequency_annually) ) {
                    $selected_frequency = '+365 days';
                    $set_frequency = wp_dp_plugin_text_srt('wp_dp_alerts_annually');
                } else if ( ! empty($frequency_biannually) ) {
                    $selected_frequency = '+182 days';
                    $set_frequency = wp_dp_plugin_text_srt('wp_dp_alerts_biannualy2');
                } else if ( ! empty($frequency_monthly) ) {
                    $selected_frequency = '+30 days';
                    $set_frequency = wp_dp_plugin_text_srt('wp_dp_alerts_monthly');
                } else if ( ! empty($frequency_fortnightly) ) {
                    $selected_frequency = '+15 days';
                    $set_frequency = wp_dp_plugin_text_srt('wp_dp_alerts_fortnightly');
                } else if ( ! empty($frequency_weekly) ) {
                    $selected_frequency = '+7 days';
                    $set_frequency = wp_dp_plugin_text_srt('wp_dp_alerts_weekly');
                } else if ( ! empty($frequency_daily) ) {
                    $selected_frequency = '+1 days';
                    $set_frequency = wp_dp_plugin_text_srt('wp_dp_alerts_daily');
                } else if ( ! empty($frequency_never) ) {
                    $selected_frequency = false;
                    $set_frequency = wp_dp_plugin_text_srt('wp_dp_alerts_never');
                } else {
                    $selected_frequency = false;
                    $set_frequency = '';
                }
                if ( $selected_frequency != false ) {
                    $email_template_variables = array();
                    $email_template = '';

                    $querystring = get_post_meta($listing_id, 'wp_dp_query', true);

                    parse_str($querystring, $listings_query_array);
                    $listing_obj = $this->get_listings_by_query($listings_query_array, $selected_frequency);
                    $listing_found_count = $listing_obj->found_posts;
                    if ( time() > strtotime($selected_frequency, intval($last_time_email_sent)) ) {

                        // Set this for email data.
                        self::$listing_details = array(
                            'id' => $listing_id,
                            'title' => get_the_title(),
                            'listings_query' => $querystring,
                            'email' => get_post_meta($listing_id, 'wp_dp_email', true),
                            'url_query' => get_post_meta($listing_id, 'wp_dp_query', true),
                            'frequency' => $selected_frequency,
                            'set_frequency' => $set_frequency,
                        );

                        $template = $this->get_template();
                        // Checking email notification is enabled/disabled.
                        if ( isset($template['email_notification']) && $template['email_notification'] == 1 && $listing_found_count > 0 ) {

                            $subject = (isset($template['subject']) && $template['subject'] != '' ) ? $template['subject'] : wp_dp_plugin_text_srt('wp_dp_alerts_listing_found_at') . get_bloginfo('name');
                            $recipients = (isset($template['recipients']) && $template['recipients'] != '') ? $template['recipients'] : self::$listing_details['email']; //$this->get_listing_added_email();
                            $email_type = (isset($template['email_type']) && $template['email_type'] != '') ? $template['email_type'] : 'html';

                            $args = array(
                                'to' => self::$listing_details['email'],
                                'subject' => $subject,
                                'message' => $template['email_template'],
                                'email_type' => $email_type,
                                'class_obj' => $this,
                            );
                            // Update last time email sent for this listing alert.
                            update_post_meta($listing_id, 'wp_dp_last_time_email_sent', time());
                            //  Send email.
                            do_action('wp_dp_send_mail', $args);
                        }
                    }
                }
            }
        }

        public static function remove_daily_alert_schedule() {
            wp_clear_scheduled_hook('create_daily_alert_schedule');
        }

        public function email_template_settings_callback($email_template_options = array()) {

            if ( ! is_array($email_template_options) ) {
                $email_template_options = array();
            }
            $email_template_options["types"][] = $this->email_template_type;
            $email_template_options["templates"]["listing alert"] = $this->email_default_template;
            $email_template_options["variables"]["listing alert"] = $this->email_template_variables;
            return $email_template_options;
        }

        public static function get_listing_alert_title() {
            if ( isset(self::$listing_details['title']) ) {
                return ucfirst(self::$listing_details['title']);
            }
            return false;
        }

        public static function get_filtered_listings_list() {
            if ( isset(self::$listing_details['listings_query']) ) {


                parse_str(self::$listing_details['listings_query'], $listings_query_array);
                $listing_obj = self::get_listings_by_query($listings_query_array, self::$listing_details['frequency']);

                ob_start();
                ?>
                <table cellpadding="0px" cellspacing="0px">
                <?php while ( $listing_obj->have_posts() ) : $listing_obj->the_post(); ?>
                        <tr><td style="padding: 5px 0 0 0;"><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></td></tr>
                    <?php endwhile; ?>
                </table>
                    <?php
                    $html1 = ob_get_clean();
                    return $html1;
                }
                return false;
            }

            public static function get_total_listings_count() {

                if ( isset(self::$listing_details['listings_query']) ) {
                    parse_str(self::$listing_details['listings_query'], $listings_query_array);
                    $listing_obj = self::get_listings_by_query($listings_query_array, self::$listing_details['frequency']);
                    $listing_found_count = $listing_obj->found_posts;
                    return $listing_found_count;
                }
                return false;
            }

            public static function get_unsubscribe_link() {
                if ( isset(self::$listing_details['id']) ) {
                    return '<a href="' . admin_url('admin-ajax.php') . '?action=wp_dp_unsubscribe_listing_alert&jaid=' . self::$listing_details['id'] . '">' . wp_dp_plugin_text_srt('wp_dp_alerts_unsubcribe') . '</a>';
                }
                return false;
            }

            public static function get_frequency() {
                if ( isset(self::$listing_details['set_frequency']) ) {
                    return self::$listing_details['set_frequency'];
                }
                return false;
            }

            public static function get_full_listing_url() {
                if ( isset(self::$listing_details['id']) ) {
                    $wp_dp_plugin_options = get_option('wp_dp_plugin_options');
                    $wp_dp_plugin_options = apply_filters('wp_dp_translate_options', $wp_dp_plugin_options);
                    $default_listing_page = '';
                    $page = 0;
                    if ( isset($wp_dp_plugin_options['wp_dp_search_result_page']) ) {
                        $page = get_post($wp_dp_plugin_options['wp_dp_search_result_page']);
                    }
                    //wp_dp_search_result_page
                    return '<a href="' . wp_dp_wpml_lang_page_permalink($page, 'page') . '?' . self::$listing_details['url_query'] . '">' . wp_dp_plugin_text_srt('wp_dp_alerts_view_full_listing') . '</a>';
                }
                return false;
            }

            public static function get_listing_alerts_count($listings_query = array(), $frequency = '') {
                if ( ! is_array($listings_query) ) {
                    $listings_query = array();
                }
                $frequency = str_replace('+', '-', $frequency);
                $listings_query['meta_query'][] = array(
                    'key' => 'wp_dp_listing_posted',
                    'value' => strtotime(date('Y/m/d', strtotime($frequency))),
                    'compare' => '>=',
                );
                $listings_query['posts_per_page'] = -1;
                $loop_count = new WP_Query($listings_query);
                //wp_dp_dbg( $loop_count );

                return $loop_count->found_posts;
            }

            static function get_filter_arg($listing_type, $request_data) {
                global $wp_dp_post_listing_types;
                $filter_arr = $cate_filter_multi_arr = $filter_multi_arr = array();
                if ( isset($request_data['ajax_filter']) ) {
                    if ( isset($listing_type) && $listing_type != '' ) {

                        $listing_type_category_name = 'wp_dp_listing_category';   // category_fieldname in db and request
                        if ( isset($request_data[$listing_type_category_name]) && $request_data[$listing_type_category_name] != '' ) {
                            $dropdown_query_str_var_name = explode(",", $request_data[$listing_type_category_name]);
                            $cate_filter_multi_arr ['relation'] = 'OR';
                            foreach ( $dropdown_query_str_var_name as $query_str_var_name_key ) {
                                $cate_filter_multi_arr[] = array(
                                    'key' => $listing_type_category_name,
                                    'value' => serialize($query_str_var_name_key),
                                    'compare' => 'LIKE',
                                );
                            }
                            if ( isset($cate_filter_multi_arr) && ! empty($cate_filter_multi_arr) ) {
                                $filter_arr[] = array(
                                    $cate_filter_multi_arr
                                );
                            }
                        }
                        $wp_dp_listing_type_cus_fields = $wp_dp_post_listing_types->wp_dp_types_custom_fields_array($listing_type);
                        $wp_dp_fields_output = '';
                        if ( is_array($wp_dp_listing_type_cus_fields) && sizeof($wp_dp_listing_type_cus_fields) > 0 ) {
                            $custom_field_flag = 1;
                            foreach ( $wp_dp_listing_type_cus_fields as $cus_fieldvar => $cus_field ) {
                                if ( isset($cus_field['enable_srch']) && $cus_field['enable_srch'] == 'yes' ) {
                                    $query_str_var_name = $cus_field['meta_key'];
                                    // only for date type field need to change field name
                                    if ( $cus_field['type'] == 'date' ) {

                                        if ( $cus_field['type'] == 'date' ) {

                                            $from_date = 'from' . $query_str_var_name;
                                            $to_date = 'to' . $query_str_var_name;
                                            if ( isset($request_data[$from_date]) && $request_data[$from_date] != '' ) {
                                                $filter_arr[] = array(
                                                    'key' => $query_str_var_name,
                                                    'value' => strtotime($request_data[$from_date]),
                                                    'compare' => '>=',
                                                );
                                            }
                                            if ( isset($request_data[$to_date]) && $request_data[$to_date] != '' ) {
                                                $filter_arr[] = array(
                                                    'key' => $query_str_var_name,
                                                    'value' => strtotime($request_data[$to_date]),
                                                    'compare' => '<=',
                                                );
                                            }
                                        }
                                    } else if ( isset($request_data[$query_str_var_name]) && $request_data[$query_str_var_name] != '' ) {

                                        if ( $cus_field['type'] == 'dropdown' ) {
                                            if ( isset($cus_field['multi']) && $cus_field['multi'] == 'yes' ) {
                                                $filter_multi_arr ['relation'] = 'OR';
                                                $dropdown_query_str_var_name = explode(",", $request_data[$query_str_var_name]);
                                                foreach ( $dropdown_query_str_var_name as $query_str_var_name_key ) {
                                                    if ( $cus_field['post_multi'] == 'yes' ) {
                                                        $filter_multi_arr[] = array(
                                                            'key' => $query_str_var_name,
                                                            'value' => serialize($query_str_var_name_key),
                                                            'compare' => 'Like',
                                                        );
                                                    } else {
                                                        $filter_multi_arr[] = array(
                                                            'key' => $query_str_var_name,
                                                            'value' => $query_str_var_name_key,
                                                            'compare' => '=',
                                                        );
                                                    }
                                                }
                                                $filter_arr[] = array(
                                                    $filter_multi_arr
                                                );
                                            } else {
                                                if ( $cus_field['post_multi'] == 'yes' ) {

                                                    $filter_arr[] = array(
                                                        'key' => $query_str_var_name,
                                                        'value' => serialize($request_data[$query_str_var_name]),
                                                        'compare' => 'Like',
                                                    );
                                                } else {
                                                    $filter_arr[] = array(
                                                        'key' => $query_str_var_name,
                                                        'value' => $request_data[$query_str_var_name],
                                                        'compare' => '=',
                                                    );
                                                }
                                            }
                                        } elseif ( $cus_field['type'] == 'text' || $cus_field['type'] == 'email' || $cus_field['type'] == 'url' || $cus_field['type'] == 'number' ) {
                                            $filter_arr[] = array(
                                                'key' => $query_str_var_name,
                                                'value' => $request_data[$query_str_var_name],
                                                'compare' => 'LIKE',
                                            );
                                        } elseif ( $cus_field['type'] == 'range' ) {
                                            $ranges_str_arr = explode(",", $request_data[$query_str_var_name]);
                                            if ( ! isset($ranges_str_arr[1]) ) {
                                                $ranges_str_arr = explode(",", $ranges_str_arr[0]);
                                            }
                                            $range_first = $ranges_str_arr[0];
                                            $range_seond = $ranges_str_arr[1];
                                            $filter_arr[] = array(
                                                'key' => $query_str_var_name,
                                                'value' => $range_first,
                                                'compare' => '>=',
                                                'type' => 'numeric'
                                            );
                                            $filter_arr[] = array(
                                                'key' => $query_str_var_name,
                                                'value' => $range_seond,
                                                'compare' => '<=',
                                                'type' => 'numeric'
                                            );
                                        }
                                    }
                                }
                                $custom_field_flag ++;
                            }
                        }
                    }
                }
                return $filter_arr;
            }

            static function get_listings_by_query($listings_query_array, $frequency) {
                global $wpdb;
                /*
                 * Query building
                 */

                $args_count = array();
                $listings_query = array();
                $default_date_time_formate = 'd-m-Y H:i:s';
                $frequency = str_replace('+', '-', $frequency);
                $listings_query['meta_query'][] = array(
                    'key' => 'wp_dp_listing_posted',
                    'value' => strtotime(date('Y/m/d', strtotime($frequency))),
                    'compare' => '>=',
                );

                $element_filter_arr[] = array(
                    'key' => 'wp_dp_listing_expired',
                    'value' => strtotime(date($default_date_time_formate)),
                    'compare' => '>=',
                );

                $element_filter_arr[] = array(
                    'key' => 'listing_member_status',
                    'value' => 'active',
                    'compare' => '=',
                );

                $element_filter_arr[] = array(
                    'key' => 'wp_dp_listing_status',
                    'value' => 'active',
                    'compare' => '=',
                );

                if ( isset($listings_query_array['listing_type']) ) {
                    $element_filter_arr[] = array(
                        'key' => 'wp_dp_listing_type',
                        'value' => $listings_query_array['listing_type'],
                        'compare' => '=',
                    );
                }
                $left_filter_arr = array();
                if ( isset($listings_query_array['listing_type']) ) {
                    $left_filter_arr = self::get_filter_arg($listings_query_array['listing_type'], $listings_query_array);
                }
                $meta_post_ids_arr = wp_dp_get_query_whereclase_by_array($left_filter_arr);
                $listing_id_condition = '';
                if ( isset($left_filter_arr) && ! empty($left_filter_arr) ) {
                    $meta_post_ids_arr = wp_dp_get_query_whereclase_by_array($left_filter_arr);
                    // if no result found in filtration 
                    if ( empty($meta_post_ids_arr) ) {
                        $meta_post_ids_arr = array( 0 );
                    }
                    $ids = $meta_post_ids_arr != '' ? implode(",", $meta_post_ids_arr) : '0';
                    $listing_id_condition = " ID in (" . $ids . ") AND ";
                }
                $post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE " . $listing_id_condition . " post_type='listings' AND post_status='publish'");
                $listing_sort_by = 'recent';    // default value
                $listing_sort_order = 'desc';   // default value

                if ( isset($listings_query_array['sort-by']) && $listings_query_array['sort-by'] != '' ) {
                    $listing_sort_by = $listings_query_array['sort-by'];
                }
                $qryvar_listing_sort_type = 'ASC';
                $qryvar_sort_by_column = 'post_title';
                if ( $listing_sort_by == 'recent' ) {
                    $qryvar_listing_sort_type = 'DESC';
                    $qryvar_sort_by_column = 'post_date';
                } elseif ( $listing_sort_by == 'alphabetical' ) {
                    $qryvar_listing_sort_type = 'ASC';
                    $qryvar_sort_by_column = 'post_title';
                }
                $args = array(
                    'posts_per_page' => 10,
                    'post_type' => 'listings',
                    'post_status' => 'publish',
                    'order' => $qryvar_listing_sort_type,
                    'orderby' => $qryvar_sort_by_column,
                    'fields' => 'ids', // only load ids
                    'meta_query' => array(
                        $element_filter_arr,
                    ),
                );

                $all_post_ids = $post_ids;

                if ( ! empty($all_post_ids) ) {

                    $args_count['post__in'] = $all_post_ids;
                    $args['post__in'] = $all_post_ids;
                }
                $listing_loop_obj = wp_dp_get_cached_obj('listing_result_cached_loop_obj', $args, 12, false, 'wp_query');
                return $listing_loop_obj;
            }

            public function wp_dp_save_search_element_callback() {
                global $wp_dp_form_fields_frontend;
                //$user->ID
                $target_class = ' wp-dp-open-register-tab';
                if ( is_user_logged_in() ) {
                    $target_class = '';
                }
                ?>
            <div class="email-me-top">
                <button type="button" class="email-alert-btn<?php echo esc_attr($target_class); ?>" data-toggle="modal" data-target="#listing-alert-model"><?php echo wp_dp_plugin_text_srt('wp_dp_alerts_save_search'); ?><i class="icon-plus"></i></button>
            </div>
            <?php
        }

    }

    new Wp_dp_Listing_Alerts();
}