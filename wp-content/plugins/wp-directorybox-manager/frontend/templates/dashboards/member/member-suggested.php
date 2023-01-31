<?php
/**
 * Member Suggested Data
 *
 */
if ( ! class_exists('Wp_dp_Member_Suggested') ) {

    class Wp_dp_Member_Suggested {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_ajax_wp_dp_member_suggested', array( $this, 'wp_dp_member_suggested_callback' ), 11, 1);
            add_action('wp_ajax_wp_dp_save_suggested_data', array( $this, 'wp_dp_save_suggested_data_callback' ), 11, 1);
            add_action('wp_ajax_wp_dp_add_team_member', array( $this, 'wp_dp_add_team_member_callback' ), 11, 1);
            add_action('wp_ajax_wp_dp_update_team_member', array( $this, 'wp_dp_update_team_member_callback' ), 11);
            add_action('wp_ajax_wp_dp_remove_team_member', array( $this, 'wp_dp_remove_team_member_callback' ), 11);
            add_action('wp_ajax_transient_call_back', array( $this, 'transient_call_back' ), 11);
            add_action('wp_ajax_nopriv_transient_call_back', array( $this, 'transient_call_back' ), 11);
            add_action('clear_auth_cookie', array( $this, 'clear_transient_on_logout' ), 11);
        }

        /**
         * Member Suggested Form
         */
        public function wp_dp_member_suggested_callback($member_id = '') {

            global $wp_dp_html_fields_frontend, $post, $wp_dp_form_fields_frontend, $wp_dp_plugin_options, $wp_dp_post_listing_types, $wp_dp_shortcode_listings_frontend;

            $wp_dp_plugin_options = apply_filters('wp_dp_translate_options', $wp_dp_plugin_options);

            $pagi_per_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard_pagination']) ? $wp_dp_plugin_options['wp_dp_member_dashboard_pagination'] : '';

            $user = wp_get_current_user();
            $suggested_default_listings_categories = '';

            $this->wp_dp_default_suggestions_settings_dashboard_callback();

            $wp_dp_dashboard_announce_title = isset($wp_dp_plugin_options['wp_dp_dashboard_announce_title']) ? $wp_dp_plugin_options['wp_dp_dashboard_announce_title'] : '';
            $wp_dp_dashboard_announce_description = isset($wp_dp_plugin_options['wp_dp_dashboard_announce_description']) ? $wp_dp_plugin_options['wp_dp_dashboard_announce_description'] : '';
            $wp_dp_announce_bg_color = isset($wp_dp_plugin_options['wp_dp_announce_bg_color']) ? $wp_dp_plugin_options['wp_dp_announce_bg_color'] : '#2b8dc4';
            ?> 
            <script>
                function transient_call_back(id) {
                    "use strict";
                    var dataString = 'user_id=' + id + '&action=transient_call_back';
                    jQuery.ajax({
                        type: "POST",
                        url: wp_dp_globals.ajax_url,
                        data: dataString,
                        success: function (response) {
                            if (response != 'error') {
                                jQuery("#close-me").remove();
                            }
                        }
                    });
                    return false;
                }

            </script>
            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="user-dashboard-background">
                                <?php do_action('wp_dp_new_notifications'); ?>
                            </div>    
                        </div>    
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="user-dashboard-background">
                                <div class="user-suggest-list">
                                    <div class="element-title">
                                        <h4><?php echo wp_dp_plugin_text_srt('wp_dp_member_suggested_ads'); ?></h4>
                                        <span><?php echo wp_dp_plugin_text_srt('wp_dp_member_define') . ' ' . force_balance_tags('<em data-target="#suggestions-box" data-toggle="modal">' . wp_dp_plugin_text_srt('wp_dp_member_search_criteria') . '</em>' . wp_dp_plugin_text_srt('wp_dp_member_specific')); ?></span>
                                    </div>
                                    <?php
                                    $suggested_listings_categories = array();
                                    $total_posts = '';
                                    $suggested_listings_max_listings = 20;
                                    if ( $user->ID > 0 ) {
                                        $suggested_listings_categories = get_user_meta($user->ID, 'suggested_listings_categories', true);
                                        $suggested_listings_max_listings = get_user_meta($user->ID, 'suggested_listings_max_listings', true);
                                    }

                                    $posts_per_page = $pagi_per_page > 0 ? $pagi_per_page : 10;
                                    $posts_paged = isset($_REQUEST['page_id_all']) ? $_REQUEST['page_id_all'] : '';
                                    $total_posts = $suggested_listings_max_listings;

                                    $all_category_in_array = in_array('all_categories', $suggested_listings_categories);
                                    $cate_filter_multi_arr = array();
                                    if ( isset($suggested_listings_categories) && empty($all_category_in_array) ) {
                                        if ( count($suggested_listings_categories) > 0 ) {
                                            $cate_filter_multi_arr['relation'] = 'OR';
                                            foreach ( $suggested_listings_categories as $suggested_listings_categories_single ) {
                                                $cate_filter_multi_arr[] = array(
                                                    'key' => 'wp_dp_listing_category',
                                                    'value' => serialize($suggested_listings_categories_single),
                                                    'compare' => 'LIKE',
                                                );
                                            }
                                        }
                                    }


                                    $args = array(
                                        'posts_per_page' => $posts_per_page,
                                        'paged' => $posts_paged,
                                        'post_type' => 'listings',
                                        'post_status' => 'publish',
                                        'meta_query' => array(
                                            'relation' => 'AND',
                                            array(
                                                'key' => 'wp_dp_listing_posted',
                                                'value' => strtotime(date("d-m-Y")),
                                                'compare' => '<=',
                                            ),
                                            array(
                                                'key' => 'wp_dp_listing_expired',
                                                'value' => strtotime(date("d-m-Y")),
                                                'compare' => '>=',
                                            ),
                                            array(
                                                'key' => 'listing_member_status',
                                                'value' => 'active',
                                                'compare' => '=',
                                            ),
                                            array(
                                                'key' => 'wp_dp_listing_status',
                                                'value' => 'delete',
                                                'compare' => '!=',
                                            ),
                                            $cate_filter_multi_arr,
                                        ),
                                    );
                                    $custom_query = new WP_Query($args);
                                    $all_listings = $custom_query->posts;
                                    ?>
                                    <ul class="user-suggest-list-holder">
                                        <?php
                                        $new_page_number = ($posts_paged - 1) * $posts_per_page;

                                        $flag = 1;

                                        if ( isset($all_listings) && ! empty($all_listings) ) {
                                            foreach ( $all_listings as $listing_data ) {
                                                // break after showing desire listing
                                                $new_flag = $flag + $new_page_number;
                                                if ( ($new_flag) > $total_posts ) {
                                                    break;
                                                }
                                                $post = $listing_data;
                                                setup_postdata($post);
                                                $wp_dp_listing_type = get_post_meta(get_the_ID(), 'wp_dp_listing_type', true);
                                                if ( $listing_type_post = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type') )
                                                    $listing_type_id = $listing_type_post->ID;
                                                $listing_type_id = wp_dp_wpml_lang_page_id($listing_type_id, 'listing-type');
                                                $wp_dp_cate_str = '';
                                                $wp_dp_listing_category = get_post_meta(get_the_ID(), 'wp_dp_listing_category', true);
                                                $wp_dp_post_loc_address_listing = get_post_meta(get_the_ID(), 'wp_dp_post_loc_address_listing', true);

                                                if ( ! empty($wp_dp_listing_category) && is_array($wp_dp_listing_category) ) {
                                                    $comma_flag = 0;
                                                    foreach ( $wp_dp_listing_category as $cate_slug => $cat_val ) {
                                                        $wp_dp_cate = get_term_by('slug', $cat_val, 'listing-category');

                                                        if ( ! empty($wp_dp_cate) ) {
                                                            $cate_link = wp_dp_listing_category_link($listing_type_id, $cat_val);
                                                            if ( $comma_flag != 0 ) {
                                                                $wp_dp_cate_str .= ', ';
                                                            }
                                                            $wp_dp_cate_str = '<a href="' . $cate_link . '">' . $wp_dp_cate->name . '</a>';
                                                            $comma_flag ++;
                                                        }
                                                    }
                                                }
                                                $listing_post_on = get_post_meta(get_the_ID(), 'wp_dp_listing_posted', true);
                                                $listing_post_expiry = get_post_meta(get_the_ID(), 'wp_dp_listing_expired', true);
                                                $listing_status = get_post_meta(get_the_ID(), 'wp_dp_listing_status', true);
                                                ?>
                                                <li>
                                                    <div class="suggest-list-holder">
                                                        <div class="img-holder">
                                                            <a href="<?php echo esc_url(get_the_permalink()); ?>">
                                                                <figure>
                                                                    <?php
                                                                    if ( function_exists('listing_gallery_first_image') ) {
                                                                        $gallery_image_args = array(
                                                                            'listing_id' => get_the_ID(),
                                                                            'size' => 'thumbnail',
                                                                            'class' => '',
                                                                            'default_image_src' => esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg')
                                                                        );
                                                                        $listing_gallery_first_image = listing_gallery_first_image($gallery_image_args);
                                                                        echo wp_dp_cs_allow_special_char($listing_gallery_first_image);
                                                                    }
                                                                    ?>
                                                                </figure>
                                                            </a>
                                                        </div>
                                                        <div class="text-holder">
                                                            <h6><a href="<?php echo esc_url(get_the_permalink()); ?>"><?php echo esc_html__(get_the_title()); ?></a></h6>
                                                            <?php if ( $wp_dp_cate_str != '' ) { ?>
                                                                <span class="rent-label"><?php echo wp_dp_allow_special_char($wp_dp_cate_str); ?></span>
                                                                <?php
                                                            }
                                                            $post_id = get_the_ID();
                                                            $favourite_label = 'Favourite';
                                                            $favourite_label = 'Favourite';
                                                            $book_mark_args = array(
                                                                'before_icon' => '<i class="icon-heart-o"></i>',
                                                                'after_icon' => '<i class="icon-heart5"></i>',
                                                            );
                                                            do_action('wp_dp_favourites_frontend_button', $post_id, $book_mark_args);
                                                            ?>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php
                                                $flag ++;
                                            }
                                        }
                                        wp_reset_postdata();
                                        ?>
                                    </ul>
                                </div>
                                <?php
                                $total_pages = 1;
                                if ( $total_posts > 0 && $posts_per_page > 0 && $total_posts > $posts_per_page ) {
                                    $total_pages = ceil($total_posts / $posts_per_page);
                                    $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
                                    $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';
                                    $this_url = $wp_dp_dashboard_link != '' ? add_query_arg(array( 'dashboard' => 'suggested' ), $wp_dp_dashboard_link) : '';
                                    wp_dp_dashboard_pagination($total_pages, $posts_paged, $this_url, 'suggested');
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if ( (isset($wp_dp_dashboard_announce_title) && $wp_dp_dashboard_announce_title <> '') || (isset($wp_dp_dashboard_announce_description) && $wp_dp_dashboard_announce_description <> '') ) {
                    ?>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div id="close-me" class="user-message" style="background-color:<?php echo esc_html__($wp_dp_announce_bg_color); ?>;" > 
                            <?php if ( ! empty($wp_dp_dashboard_announce_title) ) { ?>
                                <h2><?php echo esc_html__($wp_dp_dashboard_announce_title); ?></h2>
                            <?php }if ( ! empty($wp_dp_dashboard_announce_description) ) { ?>
                                <p><?php echo htmlspecialchars_decode($wp_dp_dashboard_announce_description); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="modal fade" id="suggestions-box" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
                    <div class="modal-dialog" role="document">
                        <div class="login-form">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title"><?php echo esc_html__('Suggested Listings Settings', 'wp-dp'); ?></h3>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"><?php echo esc_html__('&times;', 'wp-dp'); ?></span>
                                    </button>

                                </div>
                                <div class="modal-body">
                                    <div class="status status-message"></div>
                                    <form method="post" class="wp-user-form webkit" id="ControlForm_suggestions">
                                        <div class="input-filed">
                                            <?php
                                            $select_options = array( 'all_categories' => wp_dp_plugin_text_srt('wp_dp_member_all_categories') );
                                            $wp_dp_post_listing_types = new Wp_dp_Post_Listing_Types();
                                            $listing_types_array = $wp_dp_post_listing_types->wp_dp_types_array_callback();
                                            foreach ( $listing_types_array as $key => $value ) {
                                                if ( $key != 'all' ) {
                                                    $wp_dp_listing_type_category_array = $wp_dp_shortcode_listings_frontend->wp_dp_listing_filter_categories($key, '');

                                                    if ( isset($wp_dp_listing_type_category_array['cate_list']) && is_array($wp_dp_listing_type_category_array['cate_list']) ) {
                                                        foreach ( $wp_dp_listing_type_category_array['cate_list'] as $category ) {
                                                            if ( $category != '' ) {
                                                                $term = get_term_by('slug', $category, 'listing-category');
                                                                $listing_type_category_slug = $term->slug;
                                                                $listing_type_category_lable = $term->name;
                                                                $select_options[$listing_type_category_slug] = $listing_type_category_lable . ' - ' . $value;
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            $wp_dp_opt_array = array(
                                                'id' => 'suggested_listings_categories',
                                                'cust_id' => 'suggested_listings_categories',
                                                'cust_name' => 'suggested_listings_categories[]',
                                                'std' => $suggested_listings_categories,
                                                'desc' => '',
                                                'extra_atr' => 'data-placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_select_categories') . '"',
                                                'options' => $select_options,
                                                'hint_text' => '',
                                                'required' => 'yes',
                                                'return' => false,
                                                'description' => '',
                                                'name' => wp_dp_plugin_text_srt('wp_dp_member_categories_for_sugg'),
                                            );

                                            $wp_dp_form_fields_frontend->wp_dp_form_multiselect_render($wp_dp_opt_array);
                                            ?>
                                        </div>
                                        <div class="input-filed">
                                            <label><?php echo wp_dp_plugin_text_srt('wp_dp_member_no_of_suggestions'); ?></label>
                                            <?php
                                            $wp_dp_opt_array = array(
                                                'id' => '',
                                                'std' => $suggested_listings_max_listings,
                                                'cust_id' => 'suggested_listings_max_listings',
                                                'cust_name' => 'suggested_listings_max_listings',
                                                'classes' => 'form-control',
                                                'extra_atr' => ' tabindex="11" placeholder="' . wp_dp_plugin_text_srt('wp_dp_member_example') . '"',
                                                'return' => false,
                                            );
                                            $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                            ?>
                                        </div>
                                        <div class="input-filed">
                                            <div class="search-criteria-loader input-button-loader">
                                                <input type="button" class="btn-suggestions-settings bgcolor" name="submit-suggestions-settings" value="<?php echo wp_dp_plugin_text_srt('wp_dp_member_save_settings'); ?>">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>          

                            <script type="text/javascript">
                                (function ($) {
                                    $(function () {
                                        $(".btn-suggestions-settings").click(function () {
                                            var thisObj = jQuery(".search-criteria-loader");
                                            wp_dp_show_loader('.search-criteria-loader', '', 'button_loader', thisObj);
                                            var input_data = $('#ControlForm_suggestions').serialize() + '&action=wp_dp_save_suggestions_settings_dashboard';
                                            $.ajax({
                                                type: "POST",
                                                url: "<?php echo esc_js(admin_url('admin-ajax.php')); ?>",
                                                data: input_data,
                                                dataType: "json",
                                                success: function (data) {
                                                    wp_dp_show_response(data, '#ControlForm_suggestions', '');
                                                    if (data.type == 'success') {
                                                        setTimeout(function () {
                                                            jQuery("#suggestions-box").modal('toggle');
                                                            jQuery('#wp_dp_member_suggested').trigger('click');
                                                        }, 900);
                                                    }
                                                },
                                            });
                                            return false;
                                        });
                                        $('#wp_dp_suggested_listings_categories').chosen();
                                    });
                                })(jQuery);
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            wp_die();
        }

        public function transient_call_back() {
            set_transient("cookie_close" . $_POST['user_id'], 'true', (3600 * 60) * 24);
            wp_die();
        }

        public function clear_transient_on_logout() {
            $user_data = wp_get_current_user();
            delete_transient('cookie_close' . $user_data->ID);
        }

        /**
         * Member Suggested Saving Data
         */
        public function wp_dp_save_suggested_data_callback() {

            $suggested_id = wp_dp_get_input('member_suggested_id', NULL, 'INT');
            $suggested_name = wp_dp_get_input('member_suggested_name', NULL, 'STRING');
            
            $suggested_phone = wp_dp_get_input('member_suggested_phone', NULL, 'STRING');
            $suggested_content = wp_dp_get_input('wp_dp_member_suggested_description', NULL, 'STRING');
            $post_data = array(
                'ID' => $suggested_id,
                'post_title' => $suggested_name,
                'post_content' => $suggested_content,
            );

            wp_update_post($post_data);

            
            update_post_meta($suggested_id, 'wp_dp_phone_number', $suggested_phone);

            $response_array = array(
                'type' => 'success',
                'msg' => 'Successfully Updated!'
            );
            echo json_encode($response_array);
            wp_die();
        }

        /*
         * Adding Team Member
         */

        public function wp_dp_add_team_member_callback() {
            $first_name = wp_dp_get_input('wp_dp_first_name', NULL, 'STRING');
            $last_name = wp_dp_get_input('wp_dp_last_name', NULL, 'STRING');
            $permissions = wp_dp_get_input('permissions', NULL, 'ARRAY');
            $email = wp_dp_get_input('wp_dp_email_address', NULL, 'STRING');
            if ( $email == NULL ) {
                $response_array = array(
                    'type' => 'error',
                    'msg' => wp_dp_plugin_text_srt('wp_dp_member_sugg_provide_email'),
                );
                echo json_encode($response_array);
                wp_die();
            }
            if ( email_exists($email) ) {
                $response_array = array(
                    'type' => 'error',
                    'msg' => wp_dp_plugin_text_srt('wp_dp_member_sugg_email_exists'),
                );
                echo json_encode($response_array);
                wp_die();
            }

            $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);

            $user_ID = wp_create_user($email, $random_password, $email);

            if ( ! is_wp_error($user_ID) ) {

                wp_update_user(array(
                    'ID' => $user_ID,
                    'role' => 'wp_dp_member'
                ));

                update_user_meta($user_ID, 'show_admin_bar_front', false);

                if ( $permissions != NULL ) {
                    update_user_meta($user_ID, 'wp_dp_permissions', $permissions);
                }



                if ( $first_name != NULL ) {
                    update_user_meta($user_ID, 'first_name', $first_name);
                }

                if ( $last_name != NULL ) {
                    update_user_meta($user_ID, 'last_name', $last_name);
                }


                update_user_meta($user_ID, 'wp_dp_user_type', 'team-member');
                update_user_meta($user_ID, 'wp_dp_user_status', 'active');

                $suggested_ID = get_user_meta(get_current_user_id(), 'wp_dp_suggested', true);
                update_user_meta($user_ID, 'wp_dp_suggested', $suggested_ID);
                update_user_meta($user_ID, 'wp_dp_is_admin', 0);

                $message = 'Hi, ' . $first_name . ' ' . $last_name . ' ';
                $message .= 'Your account was created on wp_dp, you can login with following details  ';
                $message .= 'Username: ' . $email . ' | ';
                $message .= 'Password: ' . $random_password . '';

                /*
                 * Sending Email with login details.
                 */
                $email_array = array(
                    'to' => $email,
                    'subject' => 'Login Details',
                    'message' => $message,
                );

                do_action('wp_dp_send_mail', $email_array);

                $response_array = array(
                    'type' => 'success',
                    'msg' => wp_dp_plugin_text_srt('wp_dp_member_team_member_added'),
                );
                echo json_encode($response_array);

                wp_die();
            }
        }

        /*
         * Updating Team Member
         */

        public function wp_dp_update_team_member_callback() {
            $user_ID = wp_dp_get_input('wp_dp_user_id', NULL, 'INT');

            $permissions = wp_dp_get_input('permissions', '', 'ARRAY');
            update_user_meta($user_ID, 'wp_dp_permissions', $permissions);

            $response_array = array(
                'type' => 'success',
                'msg' => wp_dp_plugin_text_srt('wp_dp_member_team_member_updated'),
            );
            echo json_encode($response_array);
            wp_die();
        }

        /*
         * Removing Team Member
         * @ User ID
         */

        public function wp_dp_remove_team_member_callback() {
            $user_ID = wp_dp_get_input('wp_dp_user_id', NULL, 'INT');
            update_user_meta($user_ID, 'wp_dp_user_status', 'deleted');
            $response_array = array(
                'type' => 'success',
                'msg' => wp_dp_plugin_text_srt('wp_dp_member_team_member_removed'),
            );
            echo json_encode($response_array);
            wp_die();
        }

        /**
         * Suggestions default settings for user's dashaboard.
         */
        public function wp_dp_default_suggestions_settings_dashboard_callback() {
            $suggested_default_listings_categories = array();
            $suggested_default_listings_categories[] = 'all_categories';
            $suggested_listings_max_listings = 20;
            if ( ! empty($suggested_default_listings_categories) && $suggested_listings_max_listings != '' ) {
                $user = wp_get_current_user();
                if ( $user->ID > 0 ) {
                    $user_selected_cats = get_user_meta($user->ID, 'suggested_listings_categories', true);
                    if ( empty($user_selected_cats) || $user_selected_cats == '' ) {
                        update_user_meta($user->ID, 'suggested_listings_categories', $suggested_default_listings_categories);
                        update_user_meta($user->ID, 'suggested_listings_max_listings', $suggested_listings_max_listings);
                    }
                }
            }
        }

    }

    global $wp_dp_member_suggested;
    $wp_dp_member_suggested = new Wp_dp_Member_Suggested();
}
