<?php
/**
 * @Add Meta Box For Listings Post
 * @return
 *
 */
if ( ! class_exists('wp_dp_listing_meta') ) {

    class wp_dp_listing_meta {

        var $html_data = '';

        public function __construct() {
            add_action('add_meta_boxes', array( $this, 'wp_dp_meta_listings_add' ));
            add_action('wp_ajax_listing_type_dyn_fields', array( $this, 'listing_type_change_fields' ));
            add_action('admin_footer-edit-tags.php', array( $this, 'wp_dp_remove_catmeta' ));
            add_filter('manage_edit-wp_dp_locations_columns', array( $this, 'theme_columns' ));
            add_action('wp_ajax_wp_dp_listing_off_day_to_list_backend', array( $this, 'append_to_book_days_off_backend' ));

            add_action('wp_ajax_wp_dp_meta_listing_categories', array( $this, 'wp_dp_meta_listing_categories' ));
            add_action('wp_ajax_nopriv_wp_dp_meta_listing_categories', array( $this, 'wp_dp_meta_listing_categories' ));
            add_action('save_post', array( $this, 'wp_dp_listing_save_opening_hours' ), 20);
            add_action('save_post', array( $this, 'wp_dp_listing_save_off_days' ), 11);
            add_action('save_post', array( $this, 'wp_dp_listing_categories' ), 11);
            add_action('save_post', array( $this, 'wp_dp_listing_locations' ), 11);
            add_action('save_post', array( $this, 'wp_dp_save_listing_custom_fields_dates' ), 20);
            add_action('save_post', array( $this, 'wp_dp_save_listing_features' ), 20);
            add_action('save_post', array( $this, 'wp_dp_save_search_keywords_field' ), 20, 3);

            add_action('save_post', array( $this, 'wp_dp_change_listing_member' ), 1, 1);
            add_action('transition_post_status', array( $this, 'wp_dp_move_listing_trash' ), 20, 3);
        }

        public function wp_dp_change_listing_member($listing_id = '') {
            // Stop WP from clearing custom fields on autosave.
            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
                return;

            // Prevent quick edit from clearing custom fields
            if ( defined('DOING_AJAX') && DOING_AJAX )
                return;

            // If this is just a revision, don't send the email.
            if ( wp_is_post_revision($listing_id) )
                return;

            if ( get_post_status($listing_id) == 'publish' && get_post_type($listing_id) == 'listings' && isset($_POST) && ! empty($_POST) ) {

                $listing_old_member_id = get_post_meta($listing_id, 'wp_dp_listing_member', true);
                $wp_dp_listing_status = get_post_meta($listing_id, 'wp_dp_listing_status', true);
                $listing_new_member_id = isset($_POST['wp_dp_listing_member']) ? $_POST['wp_dp_listing_member'] : '';

                if ( $listing_old_member_id != $listing_new_member_id && $wp_dp_listing_status == 'active' ) {
                    if ( $listing_old_member_id != '' ) {
                        do_action('wp_dp_plublisher_listings_decrement', $listing_old_member_id);
                    }
                    if ( $listing_new_member_id != '' ) {
                        do_action('wp_dp_plublisher_listings_increment', $listing_new_member_id);
                    }
                }

                $wp_dp_listing_old_status = get_post_meta($listing_id, 'wp_dp_listing_status', true);
                $wp_dp_listing_new_status = isset($_POST['wp_dp_listing_status']) ? $_POST['wp_dp_listing_status'] : '';
                if ( $wp_dp_listing_old_status != $wp_dp_listing_new_status ) {
                    if ( $wp_dp_listing_new_status == 'active' && $listing_new_member_id != '' ) {
                        do_action('wp_dp_plublisher_listings_increment', $listing_new_member_id);
                    } else if ( $wp_dp_listing_new_status != 'active' && $listing_new_member_id != '' ) {
                        do_action('wp_dp_plublisher_listings_decrement', $listing_new_member_id);
                    }
                }
            }
        }

        public function wp_dp_move_listing_trash($new_status, $old_status, $listing) {
            if ( isset($listing->ID) && $listing->ID != '' && get_post_type($listing->ID) == 'listings' ) {
                $wp_dp_listing_status = get_post_meta($listing->ID, 'wp_dp_listing_status', true);
                $listing_member_id = get_post_meta($listing->ID, 'wp_dp_listing_member', true);
                if ( $listing_member_id != '' && $wp_dp_listing_status == 'active' ) {
                    if ( $old_status == 'publish' && $new_status != 'publish' ) {
                        do_action('wp_dp_plublisher_listings_decrement', $listing_member_id);
                    }
                    if ( $old_status != 'publish' && $new_status == 'publish' ) {
                        do_action('wp_dp_plublisher_listings_increment', $listing_member_id);
                    }
                }
            }
        }

        function wp_dp_meta_listings_add() {
            add_meta_box('wp_dp_meta_listings', wp_dp_plugin_text_srt('wp_dp_listing_options'), array( $this, 'wp_dp_meta_listings' ), 'listings', 'normal', 'high');
        }

        /**
         * Start Function How to Attach mata box with post
         */
        function wp_dp_meta_listings($post) {
            ?>
            <div class="page-wrap page-opts left" style="overflow:hidden; position:relative;">
                <div class="option-sec" style="margin-bottom:0;">
                    <div class="opt-conts">
                        <div class="elementhidden">
                            <nav class="admin-navigtion">
                                <ul id="cs-options-tab">
                                    <li><a href="javascript:void(0);" name="#tab-general-settings" href="javascript:;"><i class="icon-settings"></i><?php echo wp_dp_plugin_text_srt('wp_dp_general_info'); ?> </a></li>
                                    <li><a href="javascript:void(0);" name="#tab-package-settings" href="javascript:;"><i class="icon-list"></i> <?php echo wp_dp_plugin_text_srt('wp_dp_package_info'); ?></a></li>
                                    <li><a href="javascript:void(0);" name="#tab-detail-page-settings" href="javascript:;"><i class="icon-pencil2"></i> <?php echo wp_dp_plugin_text_srt('wp_dp_listing_type_meta_det_page_elements'); ?></a></li>
                                    <?php do_action('listing_options_sidebar_tab'); ?>
                                </ul>
                            </nav>
                            <div id="tabbed-content" data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                                <div id="tab-general-settings">
                                    <?php $this->wp_dp_listing_options(); ?>
                                </div>
                                <div id="tab-package-settings">
                                    <?php $this->wp_dp_package_info_options(); ?>
                                </div>
                                <div id="tab-detail-page-settings">
                                    <?php $this->wp_dp_single_page_options(); ?>
                                </div>
                                <?php do_action('listing_options_tab_container'); ?>
                            </div>
                            <?php $this->wp_dp_submit_meta_box('listings', $args = array()); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <?php
        }

        function wp_dp_listing_options() {
            global $post, $wp_dp_form_fields, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_options;
            $post_id = $post->ID;
            $wp_dp_listing_types = array();
            $wp_dp_args = array( 'posts_per_page' => '-1', 'post_type' => 'listings_capacity', 'orderby' => 'ID', 'post_status' => 'publish' );
            $cust_query = get_posts($wp_dp_args);
            $wp_dp_listing_capacity = get_post_meta($post->ID, 'wp_dp_listing_capacity', true);
            $wp_dp_listing_featured = get_post_meta($post->ID, 'wp_dp_listing_featured', true);
            $listing_type_slug = get_post_meta($post->ID, 'wp_dp_listing_type', true);
            $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish', 'suppress_filters' => false ));
            $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
            $wp_dp_type_full_data = get_post_meta($listing_type_id, 'wp_dp_full_data', true);

            $wp_dp_users_list = array( '' => wp_dp_plugin_text_srt('wp_dp_listing_select_member') );
            $args = array( 'posts_per_page' => '-1', 'post_type' => 'members', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC' );
            $cust_query = get_posts($args);
            if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                foreach ( $cust_query as $package_post ) {
                    if ( isset($package_post->ID) ) {
                        $package_id = $package_post->ID;
                        $package_title = $package_post->post_title;
                        $wp_dp_users_list[$package_id] = $package_title;
                    }
                }
            }

            $wp_dp_packages_list = array( '' => wp_dp_plugin_text_srt('wp_dp_select_package') );
            $args = array( 'posts_per_page' => '-1', 'post_type' => 'packages', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC' );
            $cust_query = get_posts($args);
            if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                foreach ( $cust_query as $package_post ) {
                    if ( isset($package_post->ID) ) {
                        $package_id = $package_post->ID;
                        $package_title = $package_post->post_title;
                        $wp_dp_packages_list[$package_id] = $package_title;
                    }
                }
            }


            $wp_dp_calendar = get_post_meta($post_id, 'wp_dp_calendar', true);
            $listing_types_data = array( '' => wp_dp_plugin_text_srt('wp_dp_listing_type') );
            $wp_dp_listing_args = array( 'posts_per_page' => '-1', 'post_type' => 'listing-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => false );
            $cust_query = get_posts($wp_dp_listing_args);
            if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                foreach ( $cust_query as $wp_dp_listing_type ) {
                    $listing_types_data[$wp_dp_listing_type->post_name] = get_the_title($wp_dp_listing_type->ID);
                }
            }

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type'),
                'desc' => sprintf('<a href="%s">' . wp_dp_plugin_text_srt('wp_dp_lisint_meta_add_new_listing_type') . '</a>', admin_url('post-new.php?post_type=listing-type    ', wp_dp_server_protocol())),
                'hint_text' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_list_detail_type_help_text'),
                'echo' => true,
                'field_params' => array(
                    'std' => '',
                    'id' => 'listing_type',
                    'extra_atr' => ' onchange="wp_dp_listing_type_change(this.value, \'' . $post_id . '\')"',
                    'classes' => 'chosen-select-no-single',
                    'return' => true,
                    'options' => $listing_types_data,
                ),
            );
            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

            echo '<div id="wp-dp-listing-type-field">';
            $this->listing_type_change_fields($listing_type_slug, $post_id);
            echo '</div>';

            $wp_dp_listing_cus_fields = get_option("wp_dp_listing_cus_fields");
            if ( is_array($wp_dp_listing_cus_fields) && sizeof($wp_dp_listing_cus_fields) > 0 ) {
                foreach ( $wp_dp_listing_cus_fields as $cus_field ) {
                    $wp_dp_type = isset($cus_field['type']) ? $cus_field['type'] : '';
                    switch ( $wp_dp_type ) {
                        case('text'):
                            if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] != '' ) {
                                $wp_dp_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => isset($cus_field['default_value']) ? $cus_field['default_value'] : '',
                                        'id' => $cus_field['meta_key'],
                                        'cus_field' => true,
                                        'return' => true,
                                    ),
                                );

                                $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            }
                            break;
                        case('textarea'):
                            if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] != '' ) {
                                $wp_dp_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => isset($cus_field['default_value']) ? $cus_field['default_value'] : '',
                                        'id' => $cus_field['meta_key'],
                                        'cus_field' => true,
                                        'return' => true,
                                    ),
                                );

                                $wp_dp_html_fields->wp_dp_textarea_field($wp_dp_opt_array);
                            }
                            break;
                        case('dropdown'):
                            if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] != '' ) {
                                $wp_dp_options = array();
                                if ( isset($cus_field['options']['value']) && is_array($cus_field['options']['value']) && sizeof($cus_field['options']['value']) > 0 ) {
                                    if ( isset($cus_field['first_value']) && $cus_field['first_value'] != '' ) {
                                        $wp_dp_options[''] = $cus_field['first_value'];
                                    }
                                    $wp_dp_opt_counter = 0;
                                    foreach ( $cus_field['options']['value'] as $wp_dp_option ) {

                                        $wp_dp_opt_label = $cus_field['options']['label'][$wp_dp_opt_counter];
                                        $wp_dp_options[$wp_dp_option] = $wp_dp_opt_label;
                                        $wp_dp_opt_counter ++;
                                    }
                                }

                                $wp_dp_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => isset($cus_field['default_value']) ? $cus_field['default_value'] : '',
                                        'id' => $cus_field['meta_key'],
                                        'options' => $wp_dp_options,
                                        'classes' => 'chosen-select-no-single',
                                        'cus_field' => true,
                                        'return' => true,
                                    ),
                                );

                                if ( isset($cus_field['multi']) && $cus_field['multi'] == 'yes' ) {
                                    $wp_dp_opt_array['multi'] = true;
                                }
                                $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                            }
                            break;
                        case('date'):
                            if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] != '' ) {
                                $wp_dp_format = isset($cus_field['date_format']) && $cus_field['date_format'] != '' ? $cus_field['date_format'] : 'd-m-Y';

                                $wp_dp_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => isset($cus_field['default_value']) ? $cus_field['default_value'] : '',
                                        'id' => $cus_field['meta_key'],
                                        'format' => $wp_dp_format,
                                        'cus_field' => true,
                                        'return' => true,
                                    ),
                                );

                                $wp_dp_html_fields->wp_dp_date_field($wp_dp_opt_array);
                            }
                            break;
                        case('email'):
                            if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] != '' ) {
                                $wp_dp_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => isset($cus_field['default_value']) ? $cus_field['default_value'] : '',
                                        'id' => $cus_field['meta_key'],
                                        'cus_field' => true,
                                        'return' => true,
                                    ),
                                );

                                $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            }
                            break;
                        case('url'):
                            if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] != '' ) {

                                $wp_dp_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => isset($cus_field['default_value']) ? $cus_field['default_value'] : '',
                                        'id' => $cus_field['meta_key'],
                                        'cus_field' => true,
                                        'return' => true,
                                    ),
                                );

                                $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            }
                            break;
                        case('range'):
                            if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] != '' ) {
                                $wp_dp_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => isset($cus_field['default_value']) ? $cus_field['default_value'] : '',
                                        'id' => $cus_field['meta_key'],
                                        'cus_field' => true,
                                        'classes' => 'wp-dp-range-field',
                                        'extra_atr' => 'data-min="' . $cus_field['min'] . '" data-max="' . $cus_field['max'] . '"',
                                        'return' => true,
                                    ),
                                );

                                $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            }
                            break;
                    }
                }
            }

            do_action('wp_dp_indeed_listing_admin_fields');

            $type_video = get_post_meta($listing_type_id, 'wp_dp_video_element', true);
            $type_virtual_tour = get_post_meta($listing_type_id, 'wp_dp_virtual_tour_element', true);

            echo '<div id="wp-dp-listing-type-video-con" style="display: ' . ($type_video == 'on' ? 'block' : 'none') . ';">';
            $wp_dp_html_fields->wp_dp_heading_render(
                    array(
                        'name' => wp_dp_plugin_text_srt('wp_dp_list_meta_listing_video'),
                        'id' => 'listing_video',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_list_meta_video_url'),
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_list_meta_video_url_desc'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => '',
                    'id' => 'listing_video',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
            echo '</div>';

            echo '<div id="wp-dp-listing-type-virtual-tour-con" style="display: ' . ($type_virtual_tour == 'on' ? 'block' : 'none') . ';">';
            $wp_dp_html_fields->wp_dp_heading_render(
                    array(
                        'name' => wp_dp_plugin_text_srt('wp_dp_add_listing_virtual_tour'),
                        'id' => 'listing_virtual_tour',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_add_listing_virtual_tour'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_meta_listing_virtual_tour_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => '',
                    'id' => 'listing_virtual_tour',
                    'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_add_listing_virtual_tour_desc') . '"',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_textarea_field($wp_dp_opt_array);
            echo '</div>';

            $wp_dp_form_fields->wp_dp_form_hidden_render(
                    array(
                        'name' => wp_dp_plugin_text_srt('wp_dp_listing_organization'),
                        'id' => 'org_name',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );

            $locations_data = array(
                'data' => array(
                    'country' => array(),
                    'state' => array(),
                    'city' => array(),
                    'town' => array(),
                ),
                'location_levels' => array(
                    'country' => -1,
                    'state' => -1,
                    'city' => -1,
                    'town' => -1,
                ),
            );
            $locations_data = apply_filters('get_locations_fields_data', $locations_data, 'locations_fields_selector');

            if ( ! empty($locations_data['data']) ) {
                $wp_dp_html_fields->wp_dp_heading_render(
                        array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_listing_locations_settings'),
                            'id' => 'mailing_information',
                            'classes' => '',
                            'std' => '',
                            'description' => '',
                            'hint' => ''
                        )
                );
            }

            WP_DP_FUNCTIONS()->wp_dp_location_fields('off', '', 'listing', '', true);
        }

        /**
         * Start Function How to add form options in html
         */
        function wp_dp_package_info_options() {
            global $post, $wp_dp_form_fields, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_options;
            $post_id = $post->ID;
            
            

            $wp_dp_listing_types = array();
            $wp_dp_args = array( 'posts_per_page' => '-1', 'post_type' => 'listings_capacity', 'orderby' => 'ID', 'post_status' => 'publish' );
            $cust_query = get_posts($wp_dp_args);
            $wp_dp_listing_capacity = get_post_meta($post->ID, 'wp_dp_listing_capacity', true);
            $wp_dp_listing_featured = get_post_meta($post->ID, 'wp_dp_listing_featured', true);
            $listing_type_slug = get_post_meta($post->ID, 'wp_dp_listing_type', true);
            $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish', 'suppress_filters' => false ));
            $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
            $wp_dp_type_full_data = get_post_meta($listing_type_id, 'wp_dp_full_data', true);

            $wp_dp_users_list = array( '' => wp_dp_plugin_text_srt('wp_dp_listing_select_member') );
            $args = array( 'posts_per_page' => '-1', 'post_type' => 'members', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC' );
            $cust_query = get_posts($args);
            if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                foreach ( $cust_query as $package_post ) {
                    if ( isset($package_post->ID) ) {
                        $package_id = $package_post->ID;
                        $package_title = $package_post->post_title;
                        $wp_dp_users_list[$package_id] = $package_title;
                    }
                }
            }

            $wp_dp_packages_list = array( '' => wp_dp_plugin_text_srt('wp_dp_select_package') );
            $args = array( 'posts_per_page' => '-1', 'post_type' => 'packages', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC' );
            $cust_query = get_posts($args);
            if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                foreach ( $cust_query as $package_post ) {
                    if ( isset($package_post->ID) ) {
                        $package_id = $package_post->ID;
                        $package_title = $package_post->post_title;
                        $wp_dp_packages_list[$package_id] = $package_title;
                    }
                }
            }

            $wp_dp_calendar = get_post_meta($post_id, 'wp_dp_calendar', true);

            $wp_dp_promotions = get_post_meta($post->ID, 'wp_dp_promotions', true);

            if ( ! empty($wp_dp_promotions) ) {

                $wp_dp_html_fields->wp_dp_heading_render(
                        array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_listings_promotions'),
                            'id' => 'wp_dp_fields_section',
                            'classes' => '',
                            'std' => '',
                            'description' => '',
                            'hint' => '',
                            'echo' => true
                        )
                );

                $output = '';

                $wp_dp_opt_array = array(
                    'name' => wp_dp_plugin_text_srt('wp_dp_listings_promotions'),
                    'hint_text' => '',
                );
                $output .= $wp_dp_html_fields->wp_dp_opening_field($wp_dp_opt_array);
                

                $publish_date = get_the_date('d/M', get_the_ID());
                $output .= '<ul class="trans-user-summary">';

                $currency_sign = get_post_meta($post->ID, "wp_dp_currency", true);
                $currency_sign = ( $currency_sign != '' ) ? $currency_sign : '$';
                $currency_position = get_post_meta($post->ID, "wp_dp_currency_position", true);

                foreach ( $wp_dp_promotions as $promotion_array ) {
                    $remaining_days = 'unlimitted';
                    if ( isset($promotion_array['price']) && $promotion_array['price'] != '' ) {
                        $price = wp_dp_get_order_currency($promotion_array['price'], $currency_sign, $currency_position);
                    } else {
                        $price = 'Free';
                    }
                    $expiry_date = isset($promotion_array['expiry']) ? $promotion_array['expiry'] : 'unlimitted';
                    if ( $expiry_date == '' ) {
                        $expiry_date = 'unlimitted';
                    }
                    if ( $expiry_date != 'unlimitted' ) {
                        $datediff = (strtotime($expiry_date) - strtotime(date("Y-m-d")));
                        $remaining_days = floor($datediff / 3600 / 24);
                    }
                    if ( $remaining_days > 0 ) {
                        $output .= '<li>';
                        $output .= '<b>' . wp_dp_plugin_text_srt('wp_dp_promotion_title') . ': </b><span>' . $promotion_array['title'] . '</span>';
                        $output .= '</li>';
                        $output .= '<li>';
                        $output .= '<b>' . wp_dp_plugin_text_srt('wp_dp_promotion_amount') . ': </b><span>' . $price . '</span>';
                        $output .= '</li>';
                        $output .= '<li>';
                        $output .= '<b>' . wp_dp_plugin_text_srt('wp_dp_promotion_duration') . ': </b><span>' . $remaining_days . ' ' . _n(wp_dp_plugin_text_srt('wp_dp_promotions_day'), wp_dp_plugin_text_srt('wp_dp_promotions_days'), $remaining_days, '') . ' ' . wp_dp_plugin_text_srt('wp_dp_promotion_left') . '</span>';
                        $output .= '</li>';
                        $output .= '<hr>';
                    }
                }
                $output .= '<ul>';

                $wp_dp_opt_array = array(
                    'desc' => '',
                );
                $output .= $wp_dp_html_fields->wp_dp_closing_field($wp_dp_opt_array);
                echo force_balance_tags($output);
            }

            $wp_dp_html_fields->wp_dp_heading_render(
                    array(
                        'name' => wp_dp_plugin_text_srt('wp_dp_listings_package_info'),
                        'id' => 'wp_dp_fields_section',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => '',
                        'echo' => true
                    )
            );

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('transaction_id'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => '',
                    'id' => 'trans_id',
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_posted_on'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'id' => 'listing_posted',
                    'classes' => '',
                    'strtotime' => true,
                    'std' => '', //date('d-m-Y H:i:s'),
                    'description' => '',
                    'hint' => '',
                    'format' => 'd-m-Y',
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_date_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_expired_on'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => '', //date('d-m-Y'),
                    'id' => 'listing_expired',
                    'format' => 'd-m-Y',
                    'strtotime' => true,
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_date_field($wp_dp_opt_array);

            apply_filters('listing_hunt_application_deadline_field', '');


            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_package'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => '',
                    'id' => 'listing_package',
                    'classes' => 'chosen-select-no-single',
                    'options' => $wp_dp_packages_list,
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_status'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => '',
                    'id' => 'listing_status',
                    'classes' => 'chosen-select-no-single',
                    'options' => array( 'awaiting-activation' => wp_dp_plugin_text_srt('wp_dp_listing_awaiting_activation'), 'active' => wp_dp_plugin_text_srt('wp_dp_listing_active'), 'inactive' => wp_dp_plugin_text_srt('wp_dp_listing_inactive'), 'delete' => wp_dp_plugin_text_srt('wp_dp_listing_delete') ),
                    'return' => true,
                ),
            );

            $wp_dp_listing_status = get_post_meta($post->ID, 'wp_dp_listing_status', true);
            $wp_dp_form_fields->wp_dp_form_hidden_render(
                    array(
                        'name' => wp_dp_plugin_text_srt('wp_dp_listing_listing_old_status'),
                        'id' => 'listing_old_status',
                        'classes' => '',
                        'std' => $wp_dp_listing_status,
                        'description' => '',
                        'hint' => ''
                    )
            );

            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_list_meta_no_of_pictures'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'id' => 'transaction_listing_pic_num',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => '',
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_list_meta_no_of_documents'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'id' => 'transaction_listing_doc_num',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => '',
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_list_meta_no_of_tags'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'id' => 'transaction_listing_tags_num',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => '',
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_video'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'id' => 'transaction_listing_video',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => '',
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_list_meta_respond'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'id' => 'transaction_listing_ror',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => '',
                    'return' => true,
                ),
            );

            $trans_dynamic_values = get_post_meta($post_id, 'wp_dp_transaction_dynamic', true);

            if ( is_array($trans_dynamic_values) && sizeof($trans_dynamic_values) > 0 ) {
                foreach ( $trans_dynamic_values as $trans_dynamic ) {
                    if ( isset($trans_dynamic['field_type']) && isset($trans_dynamic['field_label']) && isset($trans_dynamic['field_value']) ) {
                        $d_type = $trans_dynamic['field_type'];
                        $d_label = $trans_dynamic['field_label'];
                        $d_value = $trans_dynamic['field_value'];
                        if ( $d_type == 'single-choice' ) {
                            $d_value = $d_value == 'on' ? wp_dp_plugin_text_srt('wp_dp_listing_yes') : wp_dp_plugin_text_srt('wp_dp_listing_no');
                        }

                        echo '<div class="form-elements"><div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><label>' . $d_label . '</label></div><div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">' . $d_value . '</div></div>' . "\n";
                    }
                }
                // end foreach
            }
            // package assign data
        }

        function wp_dp_single_page_options() {
            global $post, $wp_dp_form_fields, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_options;
            $post_id = $post->ID;

            /*
             * Listing Elements Settings
             */

            $listing_type_slug = get_post_meta($post_id, 'wp_dp_listing_type', true);

            $listing_type = get_page_by_path($listing_type_slug, OBJECT, 'listing-type');
            $listing_type_id = isset($listing_type->ID) ? $listing_type->ID : 0;

            $type_floor_plans = get_post_meta($listing_type_id, 'wp_dp_floor_plans_options_element', true);
            $type_appartments = get_post_meta($listing_type_id, 'wp_dp_appartments_options_element', true);
            $type_yelp_places = get_post_meta($listing_type_id, 'wp_dp_yelp_places_element', true);
            $type_attachments = get_post_meta($listing_type_id, 'wp_dp_attachments_options_element', true);
            $type_video = get_post_meta($listing_type_id, 'wp_dp_video_element', true);
            $type_virtual_tour = get_post_meta($listing_type_id, 'wp_dp_virtual_tour_element', true);
            $type_features = get_post_meta($listing_type_id, 'wp_dp_features_element', true);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_add_listing_visibility'),
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_add_listing_visibility_desc'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => '',
                    'id' => 'listing_visibility',
                    'classes' => 'chosen-select-no-single',
                    'options' => array( 'public' => wp_dp_plugin_text_srt('wp_dp_add_listing_public'), 'invisible' => wp_dp_plugin_text_srt('wp_dp_add_listing_invisible') ),
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_plugin_listing_sold'),
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_listing_sold_desc'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => '',
                    'id' => 'listing_sold',
                    'classes' => 'chosen-select-no-single',
                    'return' => true,
                    'options' => array( 'no' => wp_dp_plugin_text_srt('wp_dp_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_yes') ),
                ),
            );
            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

            echo '<div id="wp-dp-listing-features-btn-holder" style="display: ' . ($type_features == 'on' ? 'block' : 'none') . ';">';
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_list_meta_features_element'),
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_list_meta_features_element_desc'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'id' => 'enable_features_element',
                    'classes' => '',
                    'std' => 'on',
                    'description' => '',
                    'hint' => '',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
            echo '</div>';

            echo '<div id="wp-dp-listing-video-holder" style="display: ' . ($type_video == 'on' ? 'block' : 'none') . ';">';
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_list_meta_video_element'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_list_meta_video_element_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'id' => 'enable_video_element',
                    'classes' => '',
                    'std' => 'on',
                    'description' => '',
                    'hint' => '',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
            echo '</div>';

            echo '<div id="wp-dp-listing-yelp-holder" style="display: ' . ($type_yelp_places == 'on' ? 'block' : 'none') . ';">';
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_list_meta_yelp_element'),
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_list_meta_yelp_element_desc'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'id' => 'enable_yelp_places_element',
                    'classes' => '',
                    'std' => 'on',
                    'description' => '',
                    'hint' => '',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
            echo '</div>';

            echo '<div id="wp-dp-listing-attachments-holder" style="display: ' . ($type_attachments == 'on' ? 'block' : 'none') . ';">';
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_list_meta_file_element'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_list_meta_file_element_desc'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'id' => 'enable_file_attachments_element',
                    'classes' => '',
                    'std' => 'on',
                    'description' => '',
                    'hint' => '',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
            echo '</div>';

            echo '<div id="wp-dp-listing-floor-plan-holder" style="display: ' . ($type_floor_plans == 'on' ? 'block' : 'none') . ';">';
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_list_meta_viewing'),
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_list_meta_viewing_desc'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'id' => 'enable_floot_plan_element',
                    'classes' => '',
                    'std' => 'on',
                    'description' => '',
                    'hint' => '',
                    'return' => true,
                ),
            );
            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
            echo '</div>';

            /*
             * Fields for listing Posted by
             */
            do_action('wp_dp_posted_by_admin_fields');
        }

        public function listing_type_change_fields($listing_type_slug = 0, $post_id = 0) {
            global $wp_dp_plugin_options;
            $wp_dp_opening_hours_switch = isset($wp_dp_plugin_options['wp_dp_opening_hours_switch']) ? $wp_dp_plugin_options['wp_dp_opening_hours_switch'] : 'on';
            if ( isset($_POST['listing_type_slug']) ) {
                $listing_type_slug = $_POST['listing_type_slug'];
            }
            if ( isset($_POST['post_id']) ) {
                $post_id = $_POST['post_id'];
            }

            $listing_type = get_page_by_path($listing_type_slug, OBJECT, 'listing-type');
            $listing_type_id = isset($listing_type->ID) ? $listing_type->ID : 0;

            $html = $this->listing_price($listing_type_slug, $post_id);
            $html .= $this->listing_categories($listing_type_slug, $post_id);
            $html .= $this->listing_tags($listing_type_slug, $post_id);
            $html .= $this->listing_type_dyn_fields($listing_type_slug);
            $html .= $this->feature_fields($listing_type_slug, $post_id);
            if( $wp_dp_opening_hours_switch == 'on'){
                $html .= $this->listing_opening_hours($listing_type_slug, $post_id);
                $html .= $this->listing_off_days($listing_type_slug, $post_id);
            }
            $html .= apply_filters('wp_dp_images_gallery_admin_fields', $post_id, $listing_type_slug);
            $html .= apply_filters('wp_dp_attachemnts_admin_fields', $post_id, $listing_type_slug);
            $html .= apply_filters('wp_dp_floor_plans_admin_fields', $post_id, $listing_type_slug);
            if ( isset($_POST['listing_type_slug']) ) {
                $type_floor_plans = get_post_meta($listing_type_id, 'wp_dp_floor_plans_options_element', true);
                $type_appartments = get_post_meta($listing_type_id, 'wp_dp_appartments_options_element', true);
                $type_yelp_places = get_post_meta($listing_type_id, 'wp_dp_yelp_places_element', true);
                $type_attachments = get_post_meta($listing_type_id, 'wp_dp_attachments_options_element', true);
                $type_video = get_post_meta($listing_type_id, 'wp_dp_video_element', true);
                $type_virtual_tour = get_post_meta($listing_type_id, 'wp_dp_virtual_tour_element', true);
                $type_features = get_post_meta($listing_type_id, 'wp_dp_features_element', true);
                $type_faqs = get_post_meta($listing_type_id, 'wp_dp_faqs_options_element', true);

                $detail_page_options = array(
                    'floor_plans' => $type_floor_plans,
                    'appartments' => $type_appartments,
                    'yelp_places' => $type_yelp_places,
                    'attachments' => $type_attachments,
                    'video' => $type_video,
                    'virtual_tour' => $type_virtual_tour,
                    'features' => $type_features,
                    'faqs' => $type_faqs,
                );

                echo json_encode(array( 'listing_fields' => $html, 'detail_options' => $detail_page_options ));
                die;
            } else {
                echo force_balance_tags($html);
            }
        }

        public function wp_dp_save_listing_features() {
            $listing_id = get_the_id();
            $wp_dp_listing_feature_list = isset($_POST['wp_dp_listing_feature_list']) ? $_POST['wp_dp_listing_feature_list'] : '';
            update_post_meta($listing_id, 'wp_dp_listing_feature_list', $wp_dp_listing_feature_list);
        }

        function listing_price($listing_type_slug = 0, $post_id = 0) {
            global $post, $wp_dp_html_fields, $wp_dp_plugin_options;
            $listing_type_post = get_posts(array( 'fields' => 'ids', 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish', 'suppress_filters' => false ));
            $listing_type_id = isset($listing_type_post[0]) && $listing_type_post[0] != '' ? $listing_type_post[0] : 0;
            $wp_dp_listing_type_price = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
            $wp_dp_listing_type_special_price = get_post_meta($listing_type_id, 'wp_dp_listing_type_special_price', true);

            $wp_dp_listing_type_price = isset($wp_dp_listing_type_price) && $wp_dp_listing_type_price != '' ? $wp_dp_listing_type_price : 'off';
            $wp_dp_listing_type_special_price = isset($wp_dp_listing_type_special_price) && $wp_dp_listing_type_special_price != '' ? $wp_dp_listing_type_special_price : 'off';
            $html = '';

            if ( $wp_dp_listing_type_price == 'on' ) {
                $wp_dp_opt_array = array(
                    'name' => wp_dp_plugin_text_srt('wp_dp_list_meta_price_option'),
                    'desc' => '',
                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_list_detail_price_listing_help_text'),
                    'hint_text' => '',
                    'echo' => false,
                    'field_params' => array(
                        'std' => '',
                        'extra_atr' => 'onchange="wp_dp_listing_price_change(this.value)"',
                        'id' => 'listing_price_options',
                        'classes' => 'chosen-select-no-single ',
                        'options' => array(
                            'none' => wp_dp_plugin_text_srt('wp_dp_list_meta_none'),
                            'on-call' => wp_dp_plugin_text_srt('wp_dp_list_meta_on_call'),
                            'price' => wp_dp_plugin_text_srt('wp_dp_list_meta_price'),
                        ),
                        'return' => true,
                    ),
                );

                $html .= "
				<script>
					function wp_dp_listing_price_change(price_selection) {
						if (price_selection == 'none' || price_selection == 'on-call') {
							jQuery('.dynamic_price_field').hide();
						} else {
							jQuery('.dynamic_price_field').show();
						}
					}
				</script>";

                $html .= $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                $wp_dp_listing_price_options = get_post_meta($post->ID, 'wp_dp_listing_price_options', true);
                $wp_dp_listing_price_options = isset($wp_dp_listing_price_options) ? $wp_dp_listing_price_options : '';
                $hide_div = '';
                if ( $wp_dp_listing_price_options == '' || $wp_dp_listing_price_options == 'none' || $wp_dp_listing_price_options == 'on-call' ) {
                    $hide_div = 'style="display:none;"';
                }
                $wp_dp_opt_array = array(
                    'name' => wp_dp_plugin_text_srt('wp_dp_list_meta_ad_price'),
                    'desc' => '',
                    'hint_text' => '',
                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_list_meta_ad_price_label_hint'),
                    'main_wraper' => true,
                    'main_wraper_class' => 'dynamic_price_field',
                    'main_wraper_extra' => $hide_div,
                    'echo' => false,
                    'field_params' => array(
                        'std' => '',
                        'classes' => 'wp-dp-number-field ',
                        'id' => 'listing_price',
                        'return' => true,
                    ),
                );
                $html .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                if ( $wp_dp_listing_type_special_price == 'on' ) {
                    $wp_dp_opt_array = array(
                        'name' => wp_dp_plugin_text_srt('wp_dp_list_meta_ad_special_price'),
                        'desc' => '',
                        'hint_text' => '',
                        'label_desc' => wp_dp_plugin_text_srt('wp_dp_list_meta_ad_special_price_hint'),
                        'main_wraper' => true,
                        'main_wraper_class' => 'dynamic_price_field',
                        'main_wraper_extra' => $hide_div,
                        'echo' => false,
                        'field_params' => array(
                            'std' => '',
                            'classes' => 'wp-dp-number-field ',
                            'id' => 'listing_special_price',
                            'return' => true,
                        ),
                    );
                    $html .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                }
            }

            return $html;
        }

        function listing_categories($listing_type_slug = '', $post_id = 0, $backend = true) {
            global $post, $wp_dp_html_fields, $wp_dp_plugin_static_text, $wp_dp_form_fields;
            $html = '';
            wp_enqueue_script('wp-dp-listing-categories');
            if ( $backend === false ) {
                if ( empty($listing_type_slug) ) {
                    $listing_type_id = 0;
                } else {
                    $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish', 'suppress_filters' => false ));
                    $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
                }
            } else {
                $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish', 'suppress_filters' => false ));
                $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
            }

            $wp_dp_listing_type_category_array = get_the_terms($listing_type_id, 'listing-category');
            $wp_dp_listing_type_categories = array();
            $wp_dp_listing_type_categories[''] = wp_dp_plugin_text_srt('wp_dp_listing_select_categories');
            if ( is_array($wp_dp_listing_type_category_array) && sizeof($wp_dp_listing_type_category_array) > 0 ) {
                foreach ( $wp_dp_listing_type_category_array as $in_category ) {
                    $wp_dp_listing_type_categories[$in_category->term_id] = $in_category->name;
                }
            }
            if ( ! isset($wp_dp_listing_type_categories) || ! is_array($wp_dp_listing_type_categories) || ! count($wp_dp_listing_type_categories) > 0 ) {
                $wp_dp_listing_type_categories = array();
            }

            if ( $backend === false ) {
                if ( empty($listing_type_slug) ) {
                    $listing_type_id = 0;
                } else {
                    $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish', 'suppress_filters' => false ));
                    $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
                }
            } else {
                $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish', 'suppress_filters' => false ));
                $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
            }

            $wp_dp_listing_type_category = get_post_meta($listing_type_id, 'wp_dp_listing_type_categories', true);

            if ( ! isset($wp_dp_listing_type_category) || ! is_array($wp_dp_listing_type_category) || ! count($wp_dp_listing_type_category) > 0 ) {
                $wp_dp_listing_type_category = array();
            }
            $wp_dp_multi_cat_option = 'off';

            $multiple = false;
            if ( $wp_dp_multi_cat_option == 'on' ) {
                $multiple = true;
            }

            $wp_dp_sub_child = '';

            $listing_type_cats = array( '' => wp_dp_plugin_text_srt('wp_dp_listing_listing_all_categories') );
            $wp_dp_listing_type_cats = get_post_meta($listing_type_id, 'wp_dp_listing_type_cats', true);
            if ( isset($wp_dp_listing_type_cats) && ! empty($wp_dp_listing_type_cats) ) {
                foreach ( $wp_dp_listing_type_cats as $wp_dp_listing_type_cat ) {
                    $term = get_term_by('slug', $wp_dp_listing_type_cat, 'listing-category');
                    $listing_type_cats[$term->slug] = $term->name;
                }
            }
            $wp_dp_listing_category_val = get_post_meta($post_id, 'wp_dp_listing_category', true);
            $wp_dp_listing_selected_value = isset($wp_dp_listing_category_val['parent']) && $wp_dp_listing_category_val['parent'] != '' ? $wp_dp_listing_category_val['parent'] : '';
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_meta_listing_category'),
                'desc' => sprintf('<a href="%s">' . wp_dp_plugin_text_srt('wp_dp_listing_listing_add_new_category') . '</a>', admin_url('edit-tags.php?taxonomy=listing-category&post_type=listings    ', wp_dp_server_protocol())),
                'hint_text' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_meta_listing_category_label_hint'),
                'echo' => false,
                'field_params' => array(
                    'std' => $wp_dp_listing_selected_value,
                    'cust_name' => 'wp_dp_listing_category[parent]',
                    'classes' => 'chosen-select wp-dp-dev-req-field',
                    'extra_atr' => ' onchange="wp_dp_load_category_models(this.value,\'' . $post_id . '\', \'wp_dp_listing_category_field\', \'0\')" ',
                    'options' => $listing_type_cats,
                    'return' => true,
                ),
            );

            $wp_dp_opt_array_frontend = array(
                'std' => $wp_dp_listing_selected_value,
                'cust_name' => 'wp_dp_listing_category[parent]',
                'classes' => 'chosen-select wp-dp-dev-req-field',
                'extra_atr' => ' onchange = "wp_dp_load_category_models(this.value,\'' . $post_id . '\', \'wp_dp_listing_category_field\', \'0\')" ',
                'options' => $listing_type_cats,
                'return' => true,
            );
            if ( $backend == true ) {
                $html .= $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
            } else {
                if ( isset($wp_dp_listing_type_cats) && ! empty($wp_dp_listing_type_cats) ) {
                    $html .= '
					<div class="field-holder">
					<label>' . wp_dp_plugin_text_srt('wp_dp_list_meta_step_2') . '</label>';
                    $html .= '<ul class="listing-cats-list">';
                    $categ_contr = 0;

                    foreach ( $wp_dp_listing_type_cats as $wp_dp_listing_type_cat ) {
                        $term = get_term_by('slug', $wp_dp_listing_type_cat, 'listing-category');
                        $term_id = isset($term->term_id) ? $term->term_id : '';
                        if ( $term_id != '' ) {

                            $typ_imag = get_term_meta($term_id, 'wp_dp_listing_taxonomy_icon', true);
                            $html .= '
							<li>
							<div class="type-categry-holder-main type-categry-holder-main-' . $term_id . '">
								<input id="listing-cat-' . $term_id . '" onclick="wp_dp_load_category_models(this.value,\'' . $term_id . '\', \'wp_dp_listing_category_field\', \'0\', \'\', \'parent_loader\')" class="wp-dp-dev-req-field" type="radio" name="wp_dp_listing_category[parent]" value="' . $term->slug . '"' . ($wp_dp_listing_selected_value != '' && $term->slug == $wp_dp_listing_selected_value ? ' checked="checked"' : '') . '>
								<label for="listing-cat-' . $term_id . '">' . ($typ_imag != '' ? '<div class="image-holder"><i class="' . $typ_imag . '"></i></div>' : '') . '<span>' . $term->name . '</span></label>
								<span class="loader-holder"><img src="' . wp_dp::plugin_url() . 'assets/frontend/images/ajax-loader.gif" alt=""></span>
							</div>
							</li>';
                            $categ_contr ++;
                        }
                    }
                    $html .= '</ul>';
                    $html .= '</div>';
                }
            }

            if ( is_admin() ) {
                $html .= '<div class="form-elements">';
            }
            $html .= '<div class="wp_dp_listing_category_field">';
            $html .= '</div>';
            if ( is_admin() ) {
                $html .= '</div>';
            }
            $html .= '<script>';
            $html .= 'jQuery(document).ready(function () {';
            $html .= 'wp_dp_load_category_models(\'' . $wp_dp_listing_selected_value . '\',\'' . $post_id . '\', \'wp_dp_listing_category_field\', \'1\', \'' . $wp_dp_sub_child . '\');';
            $html .= '});';
            $html .= '</script>';
            return $html;
        }

        public function wp_dp_meta_listing_categories($listing_arg = '') {
            global $wp_dp_html_fields, $wp_dp_form_fields;
            $html = '';
            $selected_val = wp_dp_get_input('selected_val', '', 'STRING');
            $load_saved_value = wp_dp_get_input('load_saved_value', '', 'STRING');

            $wp_dp_listing_category = wp_dp_get_input('wp_dp_listing_category', '', 'STRING');
            $post_id = wp_dp_get_input('post_id', '', 'STRING');
            $wp_dp_listing_category_val = get_post_meta($post_id, 'wp_dp_listing_category', true);
            if ( $selected_val != '' ) { // if selected value is empty
                $wp_dp_listing_selected_value = isset($wp_dp_listing_category_val[$selected_val]) && $wp_dp_listing_category_val[$selected_val] != '' ? $wp_dp_listing_category_val[$selected_val] : '';
                $single_term = get_term_by('slug', $selected_val, 'listing-category');
                $single_term_id = isset($single_term->term_id) && $single_term->term_id != '' ? $single_term->term_id : '';
                $single_term_name = isset($single_term->name) && $single_term->name != '' ? $single_term->name : '';
                if ( $single_term_id != '' || $single_term_id != 0 ) { //if geiven value not correct or not return id
                    $cate_arg = array(
                        'hide_empty' => false,
                        'parent' => $single_term_id,
                    );
                    $wp_dp_category_array = get_terms('listing-category', $cate_arg);
                    $listing_type_cats = array( 'test' => 'ALL ' . $single_term_name );
                    if ( is_array($wp_dp_category_array) && sizeof($wp_dp_category_array) > 0 ) {

                        if ( isset($_POST['data_child']) && $_POST['data_child'] != '' ) {
                            $wp_dp_listing_selected_value = $_POST['data_child'];
                        }
                        foreach ( $wp_dp_category_array as $dir_tag ) {
                            $listing_type_cats[$dir_tag->slug] = $dir_tag->name;
                        }

                        $html .= '
						<div class="field-holder">
                                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<label>' . wp_dp_plugin_text_srt('wp_dp_listing_categories') . $single_term_name . ' ?</label>
                                                            </div><div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">';
                        $wp_dp_opt_array = array(
                            'std' => $wp_dp_listing_selected_value,
                            'cust_name' => 'wp_dp_listing_category[' . $selected_val . ']',
                            'classes' => 'chosen-select',
                            'extra_atr' => ' onchange="wp_dp_load_category_models(this.value,\'' . $post_id . '\', \'wp_dp_listing_category_field' . $selected_val . '\', \'0\')"',
                            'options' => $listing_type_cats,
                            'return' => true,
                        );

                        $html .= $wp_dp_html_fields->wp_dp_form_select_render($wp_dp_opt_array);
                        $html .= '
						</div></div>';

                        $html .= '<div class="wp_dp_listing_category_field' . $selected_val . '">';
                        $html .= '</div>';


                        if ( (isset($load_saved_value) && $load_saved_value == '1' ) && $wp_dp_listing_category_val != '' ) {
                            $html .= '<script>';
                            $html .= 'wp_dp_load_category_models(\'' . $wp_dp_listing_selected_value . '\',\'' . $post_id . '\', \'wp_dp_listing_category_field' . $selected_val . '\', ' . $load_saved_value . ');';
                            $html .= '</script>';
                        }
                    }
                }
            }// selected value is empty check
            $output = array( 'html' => $html, );
            echo json_encode($output);
            wp_die();
        }

        function listing_tags($listing_type_slug = 0, $post_id = 0) {
            global $post, $wp_dp_html_fields, $wp_dp_plugin_static_text;
            $html = '';

            $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish', 'suppress_filters' => false ));
            $listing_type_slug = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;

            $wp_dp_listing_type_tags = get_post_meta($listing_type_slug, 'wp_dp_listing_type_tags', true);


            $wp_dp_tags_array = get_terms('listing-tag', array(
                'hide_empty' => false,
            ));
            $wp_dp_tags_list = array();
            if ( is_array($wp_dp_tags_array) && sizeof($wp_dp_tags_array) > 0 ) {
                foreach ( $wp_dp_tags_array as $dir_tag ) {
                    $wp_dp_tags_list[$dir_tag->slug] = $dir_tag->name;
                }
            }

			
			
			
            //$wp_dp_listing_type_tags = get_post_meta($post_id, 'wp_dp_listing_type_tags', true);
			
			$wp_dp_listing_tags = get_post_meta($post_id, 'wp_dp_listing_tags', true);
			
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_select_suggested_tags'),
                'desc' => '',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_meta_suggested_tags_desc'),
                'multi' => true,
                'desc' => sprintf('<a href="%s">' . wp_dp_plugin_text_srt('wp_dp_add_new_tag_link') . '</a>', admin_url('edit-tags.php?taxonomy=listing-tag&post_type=listings', wp_dp_server_protocol())),
                'field_params' => array(
                    'std' => $wp_dp_listing_tags,
                    'id' => 'tags',
                    'classes' => 'chosen-select-no-single chosen-select',
                    'options' => $wp_dp_tags_list,
                    'return' => true,
					'force_std' => true,
                ),
            );

            $html .= $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

            return $html;
        }

        function listing_opening_hours($listing_type_slug = 0, $post_id = 0) {
            global $listing_add_counter, $wp_dp_html_fields;

            $listing_add_counter = rand(10000000, 99999999);
            $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish' ));
            $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;

            $html = '';

            $time_list = $this->listing_time_list($listing_type_id);
            $week_days = $this->listing_week_days();

            $time_from_html = '';
            $time_to_html = '';

            $get_opening_hours = get_post_meta($post_id, 'wp_dp_opening_hours', true);
            if ( is_array($time_list) && sizeof($time_list) > 0 ) {
                foreach ( $time_list as $time_key => $time_val ) {
                    $time_from_html .= '<option value="' . $time_key . '"' . ('09:00 am' == $time_key ? ' selected="selected"' : '') . '>' . $time_val . '</option>' . "\n";
                    $time_to_html .= '<option value="' . $time_key . '"' . ('06:00 pm' == $time_key ? ' selected="selected"' : '') . '>' . $time_val . '</option>' . "\n";
                }
            }


            $days_html = '';
            if ( is_array($week_days) && sizeof($week_days) > 0 ) {
                $days_html .= '<li class="opening-hours-heading">
					<div class="open-close-time opening-time">
						<div class="day-sec">
							<span>' . wp_dp_plugin_text_srt('wp_dp_listing_days') . '</span>
						</div>
						<div class="time-sec">
							<span>' . wp_dp_plugin_text_srt('wp_dp_times') . '</span>
						</div>
					</div>
				</li>';
                foreach ( $week_days as $day_key => $week_day ) {

                    $day_status = isset($get_opening_hours[$day_key]['day_status']) ? $get_opening_hours[$day_key]['day_status'] : '';
                    if ( isset($get_opening_hours) && is_array($get_opening_hours) && sizeof($get_opening_hours) > 0 ) {

                        $opening_time = isset($get_opening_hours[$day_key]['opening_time']) ? $get_opening_hours[$day_key]['opening_time'] : '';
                        $closing_time = isset($get_opening_hours[$day_key]['closing_time']) ? $get_opening_hours[$day_key]['closing_time'] : '';

                        if ( is_array($time_list) && sizeof($time_list) > 0 ) {
                            $time_from_html = '';
                            $time_to_html = '';
                            foreach ( $time_list as $time_key => $time_val ) {
                                $time_from_html .= '<option value="' . $time_key . '"' . ($opening_time == $time_key ? ' selected="selected"' : '') . '>' . date_i18n('g:i a', strtotime($time_val)) . '</option>' . "\n";
                                $time_to_html .= '<option value="' . $time_key . '"' . ($closing_time == $time_key ? ' selected="selected"' : '') . '>' . date_i18n('g:i a', strtotime($time_val)) . '</option>' . "\n";
                            }
                        }
                    }elseif(  get_post_status ( $post_id ) === 'auto-draft' && $day_key != 'saturday' && $day_key != 'sunday' ){
						$day_status = 'on';
					}
					
                    $days_html .= '
					<li>
						<div id="open-close-con-' . $day_key . '-' . $listing_add_counter . '" class="open-close-time' . (isset($day_status) && $day_status == 'on' ? ' opening-time' : '') . '">
							<div class="day-sec">
								<span>' . $week_day . '</span>
							</div>
							<div class="time-sec">
								<select class="chosen-select " name="wp_dp_opening_hour[' . $day_key . '][opening_time]">
									' . $time_from_html . '
								</select>
								<span class="option-label">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_to') . '</span>
								<select class="chosen-select " name="wp_dp_opening_hour[' . $day_key . '][closing_time]">
									' . $time_to_html . '
								</select>
								<a id="wp-dp-dev-close-time-' . $day_key . '-' . $listing_add_counter . '" href="javascript:void(0);" data-id="' . $listing_add_counter . '" data-day="' . $day_key . '" title="' . wp_dp_plugin_text_srt('wp_dp_services_remove') . '">' . wp_dp_plugin_text_srt('wp_dp_services_remove') . '<i class="icon-close"></i></a>
							</div>
							<div class="close-time">
								<a id="wp-dp-dev-open-time-' . $day_key . '-' . $listing_add_counter . '" href="javascript:void(0);" data-id="' . $listing_add_counter . '" data-day="' . $day_key . '">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_closed') . ' <span>(' . wp_dp_plugin_text_srt('wp_dp_member_add_list_click_open_hours') . ')</span></a>
								<input id="wp-dp-dev-open-day-' . $day_key . '-' . $listing_add_counter . '" type="hidden" name="wp_dp_opening_hour[' . $day_key . '][day_status]"' . (isset($day_status) && $day_status == 'on' ? ' value="on"' : '') . '>
							</div>
						</div>
					</li>';
                }
            }
            $html .= $wp_dp_html_fields->wp_dp_heading_render(
                    array(
                        'name' => wp_dp_plugin_text_srt('wp_dp_listing_opening_hours'),
                        'id' => 'opening_hours',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => '',
                        'echo' => false
                    )
            );
            $html .= '
					<div class="form-elements">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<label>' . wp_dp_plugin_text_srt('wp_dp_listing_opening_hours') . '</label>
                                                        <p class="label-desc">'.wp_dp_plugin_text_srt('wp_dp_listing_opening_hours_hint_label').'</p>    
						</div>
						<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
							<div class="time-list">
								<ul>
									' . $days_html . '
								</ul>
							</div>
						</div>
					</div>';

            return $html;
        }

        function listing_off_days($listing_type_slug = 0, $post_id = 0) {
            global $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_static_text;
            echo '';
            $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish', 'suppress_filters' => false ));
            $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;

            $wp_dp_off_days = get_post_meta($listing_type_id, 'wp_dp_off_days', true);
            $html = $wp_dp_html_fields->wp_dp_heading_render(
                    array(
                        'name' => wp_dp_plugin_text_srt('wp_dp_listing_off_days'),
                        'id' => 'off_days',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => '',
                        'echo' => false
                    )
            );
            $date_js = '';
            if ( isset($wp_dp_calendar) && ! empty($wp_dp_calendar) ) {
                foreach ( $wp_dp_calendar as $calender_date ) {
                    $calender_date = strtotime($calender_date);
                    $dateVal = date("Y, m, d", strtotime('-1 month', $calender_date));
                    $date_js .= '{
							startDate: new Date(' . $dateVal . '),
							endDate: new Date(' . $dateVal . ')
						},';
                }
            }
            $html .= $this->listing_book_days_off();
            return $html;
        }

        function listing_type_dyn_fields($listing_type_slug = 0) {
            global $wp_dp_html_fields, $wp_dp_plugin_static_text;
            $wp_dp_fields_output = '';
            $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish', 'suppress_filters' => false ));
            $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
            $wp_dp_listing_type_cus_fields = get_post_meta($listing_type_id, "wp_dp_listing_type_cus_fields", true);
            $wp_dp_fields_output .= $wp_dp_html_fields->wp_dp_heading_render(
                    array(
                        'name' => wp_dp_plugin_text_srt('wp_dp_listing_custom_fields'),
                        'id' => 'wp_dp_fields_section',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => '',
                        'echo' => false
                    )
            );
            if ( is_array($wp_dp_listing_type_cus_fields) && sizeof($wp_dp_listing_type_cus_fields) > 0 ) {
                foreach ( $wp_dp_listing_type_cus_fields as $cus_field ) {
                    $wp_dp_type = isset($cus_field['type']) ? $cus_field['type'] : '';
                    $required_class = '';
                    if ( isset($cus_field['required']) && $cus_field['required'] == 'yes' ) {
                        $required_class = 'wp-dp-dev-req-field-admin';
                    }
                    switch ( $wp_dp_type ) {
                        case('section'):
                            break;
                        case('text'):

                            if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] != '' ) {
                                $wp_dp_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'echo' => false,
                                    'field_params' => array(
                                        'std' => isset($cus_field['default_value']) ? $cus_field['default_value'] : '',
                                        'id' => $cus_field['meta_key'],
                                        'cus_field' => true,
                                        'classes' => $required_class,
                                        'return' => true,
                                    ),
                                );

                                $wp_dp_fields_output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            }
                            break;

                        case('number'):

                            if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] != '' ) {
                                $wp_dp_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'echo' => false,
                                    'field_params' => array(
                                        'std' => isset($cus_field['default_value']) ? $cus_field['default_value'] : '',
                                        'id' => $cus_field['meta_key'],
                                        'cus_field' => true,
                                        'classes' => 'wp-dp-number-field ' . $required_class,
                                        'cust_type' => 'number',
                                        'return' => true,
                                    ),
                                );

                                $wp_dp_fields_output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            }
                            break;
                        case('textarea'):
                            if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] != '' ) {
                                $wp_dp_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'echo' => false,
                                    'field_params' => array(
                                        'std' => isset($cus_field['default_value']) ? $cus_field['default_value'] : '',
                                        'id' => $cus_field['meta_key'],
                                        'classes' => $required_class,
                                        'cus_field' => true,
                                        'return' => true,
                                    ),
                                );
                                $wp_dp_fields_output .= $wp_dp_html_fields->wp_dp_textarea_field($wp_dp_opt_array);
                            }
                            break;
                        case('dropdown'):
                            if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] != '' ) {
                                $wp_dp_dropdown_options = array();
                                if ( isset($cus_field['options']['value']) && is_array($cus_field['options']['value']) && sizeof($cus_field['options']['value']) > 0 ) {

                                    if ( isset($cus_field['first_value']) && $cus_field['first_value'] != '' ) {
                                        $wp_dp_dropdown_options[''] = $cus_field['first_value'];
                                    }
                                    $wp_dp_opt_counter = 0;
                                    foreach ( $cus_field['options']['value'] as $wp_dp_option ) {
                                        $wp_dp_opt_label = $cus_field['options']['label'][$wp_dp_opt_counter];
                                        $wp_dp_dropdown_options[$wp_dp_option] = $wp_dp_opt_label;
                                        $wp_dp_opt_counter ++;
                                    }
                                }

                                if ( isset($cus_field['chosen_srch']) && $cus_field['chosen_srch'] == 'yes' && count($wp_dp_dropdown_options) > 5 ) {
                                    $chosen_class = 'chosen-select';
                                } else {
                                    $chosen_class = 'chosen-select-no-single';
                                }

                                $wp_dp_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'echo' => false,
                                    'field_params' => array(
                                        'std' => isset($cus_field['default_value']) ? $cus_field['default_value'] : '',
                                        'id' => $cus_field['meta_key'],
                                        'options' => $wp_dp_dropdown_options,
                                        'classes' => $chosen_class . ' ' . $required_class,
                                        'cus_field' => true,
                                        'return' => true,
                                    ),
                                );
                                if ( isset($cus_field['post_multi']) && $cus_field['post_multi'] == 'yes' ) {
                                    $wp_dp_opt_array['multi'] = true;
                                }

                                $wp_dp_fields_output .= $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                            }
                            break;
                        case('date'):
                            if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] != '' ) {
                                $wp_dp_format = isset($cus_field['date_format']) && $cus_field['date_format'] != '' ? $cus_field['date_format'] : 'd-m-Y';
                                $wp_dp_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'echo' => false,
                                    'field_params' => array(
                                        'std' => isset($cus_field['default_value']) ? $cus_field['default_value'] : '',
                                        'id' => $cus_field['meta_key'],
                                        'format' => $wp_dp_format,
                                        'classes' => 'wp-dp-date-field ' . $required_class,
                                        'cus_field' => true,
                                        'strtotime' => true,
                                        'return' => true,
                                    ),
                                );
                                $wp_dp_fields_output .= $wp_dp_html_fields->wp_dp_date_field($wp_dp_opt_array);
                            }
                            break;
                        case('email'):
                            if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] != '' ) {
                                $wp_dp_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'echo' => false,
                                    'field_params' => array(
                                        'std' => isset($cus_field['default_value']) ? $cus_field['default_value'] : '',
                                        'id' => $cus_field['meta_key'],
                                        'classes' => 'wp-dp-email-field ' . $required_class,
                                        'cus_field' => true,
                                        'return' => true,
                                    ),
                                );
                                $wp_dp_fields_output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            }
                            break;
                        case('url'):
                            if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] != '' ) {

                                $wp_dp_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'echo' => false,
                                    'field_params' => array(
                                        'std' => isset($cus_field['default_value']) ? $cus_field['default_value'] : '',
                                        'id' => $cus_field['meta_key'],
                                        'classes' => 'wp-dp-url-field ' . $required_class,
                                        'cus_field' => true,
                                        'return' => true,
                                    ),
                                );
                                $wp_dp_fields_output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            }
                            break;
                        case('range'):
                            if ( isset($cus_field['meta_key']) && $cus_field['meta_key'] != '' ) {
                                $wp_dp_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'echo' => false,
                                    'field_params' => array(
                                        'std' => isset($cus_field['default_value']) ? $cus_field['default_value'] : '',
                                        'id' => $cus_field['meta_key'],
                                        'cus_field' => true,
                                        'classes' => 'wp-dp-range-field ' . $required_class,
                                        'extra_atr' => 'data-min="' . $cus_field['min'] . '" data-max="' . $cus_field['max'] . '"',
                                        'return' => true,
                                    ),
                                );
                                $wp_dp_fields_output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            }
                            break;
                    }
                }
                $wp_dp_fields_output .= '
                    <script>
                    jQuery(document).ready(function () {
                        chosen_selectionbox();
                    });
                    </script>';
            } else {
                $wp_dp_fields_output .= '<div class="custom-field-error">';
                $wp_dp_fields_output .= wp_dp_plugin_text_srt('wp_dp_listing_no_custom_field_found');
                $wp_dp_fields_output .= '</div>';
            }

            return $wp_dp_fields_output;
        }

        function feature_fields($listing_type_slug = 0, $post_id = 0) {
            global $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_static_text, $wp_dp_plugin_options;
            $wp_dp_opening_hours_switch = isset($wp_dp_plugin_options['wp_dp_opening_hours_switch']) ? $wp_dp_plugin_options['wp_dp_opening_hours_switch'] : 'on';

            $wp_dp_listing_features = get_post_meta($post_id, 'wp_dp_listing_feature_list', true);

            $listing_type = get_page_by_path($listing_type_slug, OBJECT, 'listing-type');
            $listing_type_id = isset($listing_type->ID) ? $listing_type->ID : 0;

            $type_features_element = get_post_meta($listing_type_id, 'wp_dp_features_element', true);

            $wp_dp_listing_features_array = array();
            if ( ! empty($wp_dp_listing_features) ) {
                foreach ( $wp_dp_listing_features as $feature ) {
                    if ( $feature != '' ) {
                        $explode_data = explode("_icon", $feature);
                        $feature_name = $explode_data[0];
                        $wp_dp_listing_features_array[] = $feature_name;
                    }
                }
            }

            $html = '<div id="wp-dp-listing-features-holder" style="display: ' . ($type_features_element == 'on' ? 'block' : 'none') . ';">';
            $html .= $wp_dp_html_fields->wp_dp_heading_render(
                    array(
                        'name' => wp_dp_plugin_text_srt('wp_dp_listing_features'),
                        'id' => 'features_information',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => '',
                        'echo' => false
                    )
            );
            

                $html .= $wp_dp_html_fields->wp_dp_opening_field(array( 
                    'name' => wp_dp_plugin_text_srt('wp_dp_listing_features') ,
                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_features_hint_label'),
                    ));

            $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish', 'suppress_filters' => false ));
            $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
            $wp_dp_get_features = get_post_meta($listing_type_id, 'feature_lables', true);
            $wp_dp_feature_icon = get_post_meta($listing_type_id, 'wp_dp_feature_icon', true);
            $wp_dp_feature_icon_group = get_post_meta($listing_type_id, 'wp_dp_feature_icon_group', true);


            if ( is_array($wp_dp_get_features) && sizeof($wp_dp_get_features) > 0 ) {

                $html .= '<ul class="checkbox-list">';
                foreach ( $wp_dp_get_features as $feat_key => $features ) {
                    $feat_rand = rand(1000000, 99999999);
                    if ( isset($features) && $features <> '' ) {
                        $wp_dp_feature_name = isset($features) ? $features : '';
                        $icon = isset($wp_dp_feature_icon[$feat_key]) ? $wp_dp_feature_icon[$feat_key] : '';
                        $icon_group = isset($wp_dp_feature_icon_group[$feat_key]) ? $wp_dp_feature_icon_group[$feat_key] : '';
                        $html .= '<li class="col-lg-6 col-md-6 col-sm-12 col-xs-12">';
                        $wp_dp_opt_array = array(
                            'std' => '' . $wp_dp_feature_name . "_icon" . $icon . '_icon' . $icon_group,
                            'id' => 'feat-' . $feat_rand . '',
                            'cust_name' => 'wp_dp_listing_feature_list[]',
                            'return' => true,
                            'cust_type' => 'checkbox',
                            'extra_atr' => ' ' . (is_array($wp_dp_listing_features) && in_array($wp_dp_feature_name, $wp_dp_listing_features_array) ? ' checked="checked"' : '') . '',
                            'prefix_on' => false,
                        );
                        $html .=$wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);

                        $html .= '<label for="feat-' . $feat_rand . '">  <i class="' . $icon . '"></i> ' . $wp_dp_feature_name . '</label>';
                        $html .= '</li>';
                    }
                }
                $html .='</ul>';
            }

            $html .= $wp_dp_html_fields->wp_dp_closing_field(array());
            $html .= '</div>';

            return $html;
        }


        function wp_dp_remove_catmeta() {
            global $current_screen;
            switch ( $current_screen->id ) {
                case 'edit-listing_type':
                    ?>
                    <script type="text/javascript">
                        jQuery(window).load(function ($) {
                            jQuery('#parent').parent().remove();
                        });
                    </script>
                    <?php
                    break;
                case 'edit-listing-tag':
                    break;
            }
        }

        /**
         * Start Function How to create coloumes of post and theme
         */
        //wp_dp_listing_name
        function theme_columns($theme_columns) {
            $new_columns = array(
                'cb' => '<input type="checkbox" />',
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_name'),
                'header_icon' => '',
                'slug' => wp_dp_plugin_text_srt('wp_dp_listing_slug'),
                'posts' => wp_dp_plugin_text_srt('wp_dp_listing_posts')
            );
            return $new_columns;
        }

        public function listing_time_list($type_id = '') {

            $lapse = 15;
            $hours = array();
            $date = date("Y-m-d 12:00");
            $time = strtotime('12:00 am');
            $start_time = strtotime($date . ' am');
            $endtime = strtotime(date("Y-m-d h:i a", strtotime('1440 minutes', $start_time)));

            while ( $start_time < $endtime ) {
                $time = date("h:i a", strtotime('+' . $lapse . ' minutes', $time));
                $hours[$time] = $time;
                $time = strtotime($time);
                $start_time = strtotime(date("Y-m-d h:i a", strtotime('+' . $lapse . ' minutes', $start_time)));
            }

            return $hours;
        }

        public function listing_week_days() {

            $week_days = array(
                'monday' => wp_dp_plugin_text_srt('wp_dp_listing_monday'),
                'tuesday' => wp_dp_plugin_text_srt('wp_dp_listing_tuesday'),
                'wednesday' => wp_dp_plugin_text_srt('wp_dp_listing_wednesday'),
                'thursday' => wp_dp_plugin_text_srt('wp_dp_listing_thursday'),
                'friday' => wp_dp_plugin_text_srt('wp_dp_listing_friday'),
                'saturday' => wp_dp_plugin_text_srt('wp_dp_listing_saturday'),
                'sunday' => wp_dp_plugin_text_srt('wp_dp_listing_sunday')
            );
            return $week_days;
        }

        public function listing_book_days_off() {
            global $post;
            $listing_add_counter = rand(10000000, 99999999);
            $html = '';
            $off_days_list = '';
            $get_listing_off_days = get_post_meta($post->ID, 'wp_dp_calendar', true);
            if ( is_array($get_listing_off_days) && sizeof($get_listing_off_days) ) {
                foreach ( $get_listing_off_days as $get_off_day ) {
                    $off_days_list .= $this->append_to_book_days_off_backend($get_off_day);
                }
			} elseif( isset($post->ID) && get_post_status ( $post->ID ) == 'auto-draft' ) {
				$current_year = date('Y');
				$get_listing_off_days = array( $current_year.'-12-25', ($current_year+1).'-01-01' );
				foreach ( $get_listing_off_days as $get_off_day ) {
                    $off_days_list .= $this->append_to_book_days_off_backend($get_off_day);
                }
            } else {
                $off_days_list = '<li id="no-book-day-' . $listing_add_counter . '" class="no-result-msg">' . wp_dp_plugin_text_srt('wp_dp_list_meta_no_of_days') . '</li>';
            }
            wp_enqueue_script('responsive-calendar');
            $html .= '
			<div class="form-elements">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<label>' . wp_dp_plugin_text_srt('wp_dp_list_meta_off_days') . '</label>
                                        <p class="label-desc">'.wp_dp_plugin_text_srt('wp_dp_list_meta_off_days_label_link').'</p>    
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="book-list">
						<ul id="wp-dp-dev-add-off-day-app-' . $listing_add_counter . '">
							' . $off_days_list . '
						</ul>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div id="wp-dp-dev-loader-' . absint($listing_add_counter) . '" class="wp-dp-loader"></div>
					<a class="book-btn" href="javascript:void(0);">' . wp_dp_plugin_text_srt('wp_dp_list_meta_off_days') . '</a>
					<div id="wp-dp-dev-cal-holder-' . $listing_add_counter . '" class="calendar-holder">
						<div data-id="' . $listing_add_counter . '" class="wp-dp-dev-insert-off-days-backend responsive-calendar" data-ajax-url="' . esc_url(admin_url('admin-ajax.php')) . '" data-plugin-url="' . esc_url(wp_dp::plugin_url()) . '">
							<span class="availability">' . wp_dp_plugin_text_srt('wp_dp_member_availability') . '</span>
							<div class="controls">
								<a data-go="prev"><div class="btn btn-primary"><i class="icon-angle-left"></i></div></a>
								<h4><span data-head-month></span> <span data-head-year></span></h4>
								<a data-go="next"><div class="btn btn-primary"><i class="icon-angle-right"></i></div></a>
							</div>
							<div class="day-headers">
								<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_mon') . '</div>
								<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_tue') . '</div>
								<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_wed') . '</div>
								<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_thu') . '</div>
								<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_fri') . '</div>
								<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_sat') . '</div>
								<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_sun') . '</div>
							</div>
							<div class="days wp-dp-dev-calendar-days" data-group="days"></div>
						</div>
					</div>
				</div>
				<script>
					jQuery(document).ready(function () {
						jQuery(".responsive-calendar").responsiveCalendar({
							monthChangeAnimation: false,
						});
					});
				</script>
			</div>';
            return force_balance_tags($html);
        }

        /**
         * Appending off days to list via Ajax
         * @return markup
         */
        public function append_to_book_days_off_backend($get_off_day = '') {

            if ( $get_off_day != '' ) {
                $book_off_date = $get_off_day;
            } else {
                $day = wp_dp_get_input('off_day_day', date('d'), 'STRING');
                $month = wp_dp_get_input('off_day_month', date('m'), 'STRING');
                $year = wp_dp_get_input('off_day_year', date('Y'), 'STRING');
                $book_off_date = $year . '-' . $month . '-' . $day;
            }
            $formated_off_date_day = date_i18n("l", strtotime($book_off_date));
            $formated_off_date = date_i18n(get_option('date_format'), strtotime($book_off_date));
            $rand_numb = rand(100000000, 999999999);
            $html = '';
            $html .= '
			<li id="day-dpove-' . $rand_numb . '">
				<div class="open-close-time opening-time">
					<div class="date-sec">
						<span>' . $formated_off_date_day . '</span>';

            $html .= '	<input type="hidden" value="' . $book_off_date . '" name="wp_dp_listing_off_days[]">';

            $html .= '	</div>
					<div class="time-sec">' . $formated_off_date . '
						<a id="wp-dp-dev-day-off-dp-' . $rand_numb . '" data-id="' . $rand_numb . '" href="javascript:void(0);">' . wp_dp_plugin_text_srt('wp_dp_services_remove') . '<i class="icon-close"></i></a>
					</div>
				</div>
			</li>';

            if ( $get_off_day != '' ) {
                return force_balance_tags($html);
            } else {
                echo json_encode(array( 'html' => $html ));
                die;
            }
        }

        public function wp_dp_listing_save_opening_hours($listing_id = '') {
            global $wp_dp_plugin_options;
            $wp_dp_opening_hours_switch = isset($wp_dp_plugin_options['wp_dp_opening_hours_switch']) ? $wp_dp_plugin_options['wp_dp_opening_hours_switch'] : 'on';
            if( $wp_dp_opening_hours_switch == 'on'){
                $wp_dp_opening_hours = wp_dp_get_input('wp_dp_opening_hour', '', 'ARRAY');
                update_post_meta($listing_id, 'wp_dp_opening_hours', $wp_dp_opening_hours);
            }
        }

        public function wp_dp_listing_save_off_days($listing_id = '') {
            global $wp_dp_plugin_options;
            $wp_dp_opening_hours_switch = isset($wp_dp_plugin_options['wp_dp_opening_hours_switch']) ? $wp_dp_plugin_options['wp_dp_opening_hours_switch'] : 'on';
            if( $wp_dp_opening_hours_switch == 'on'){
                $wp_dp_off_days = wp_dp_get_input('wp_dp_listing_off_days', '', 'ARRAY');
                update_post_meta($listing_id, 'wp_dp_calendar', $wp_dp_off_days);
            }
        }

        public function wp_dp_save_listing_custom_fields_dates($listing_id = '') {
            if ( $listing_id != '' ) {

                $listing_type_slug = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish', 'suppress_filters' => false ));
                $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
                $listing_type_fields = get_post_meta($listing_type_id, 'wp_dp_listing_type_cus_fields', true);
                if ( ! empty($listing_type_fields) ) {
                    foreach ( $listing_type_fields as $listing_type_field ) {
                        $field_type = isset($listing_type_field['type']) ? $listing_type_field['type'] : '';
                        $meta_key = isset($listing_type_field['meta_key']) ? $listing_type_field['meta_key'] : '';
                        if ( $field_type == 'date' ) {
                            if ( $meta_key != '' ) {
                                $cus_field_values = '';
                                $cus_field_values = isset($_POST['wp_dp_cus_field']) ? $_POST['wp_dp_cus_field'] : '';
                                if ( $cus_field_values ) {
                                    foreach ( $cus_field_values as $c_key => $c_val ) {
                                        if ( $c_key == $meta_key ) {
                                            update_post_meta($listing_id, $c_key, strtotime($c_val));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        public function wp_dp_listing_categories($listing_id = '') {
            $wp_dp_listing_cats = wp_dp_get_input('wp_dp_listing_category', '', 'ARRAY');
            $cat_ids = array();
            wp_set_post_terms($listing_id, '', 'listing-category');
            if ( $wp_dp_listing_cats ) {
                foreach ( $wp_dp_listing_cats as $wp_dp_listing_cat ) {
                    $term = get_term_by('slug', $wp_dp_listing_cat, 'listing-category');
                    $cat_ids[] = isset($term->term_id) ? $term->term_id : '';
                }
            }
            wp_set_post_terms($listing_id, $cat_ids, 'listing-category');
        }

        public function wp_dp_listing_locations($listing_id = '') {
            $wp_dp_listing_location_country = wp_dp_get_input('wp_dp_post_loc_country_listing', '', 'STRING');
            $wp_dp_listing_location_state = wp_dp_get_input('wp_dp_post_loc_state_listing', '', 'STRING');
            $wp_dp_listing_location_city = wp_dp_get_input('wp_dp_post_loc_city_listing', '', 'STRING');
            $wp_dp_listing_location_town = wp_dp_get_input('wp_dp_post_loc_town_listing', '', 'STRING');
            wp_set_post_terms($listing_id, '', 'wp_dp_locations');
            $location_ids = array();
            if ( $wp_dp_listing_location_country ) {
                $term = get_term_by('slug', $wp_dp_listing_location_country, 'wp_dp_locations');
                $location_ids[] = $term->term_id;
            }
            if ( $wp_dp_listing_location_state ) {
                $term = get_term_by('slug', $wp_dp_listing_location_state, 'wp_dp_locations');
                $location_ids[] = $term->term_id;
            }
            if ( $wp_dp_listing_location_city ) {
                $term = get_term_by('slug', $wp_dp_listing_location_city, 'wp_dp_locations');
                $location_ids[] = $term->term_id;
            }
            if ( $wp_dp_listing_location_town ) {
                $term = get_term_by('slug', $wp_dp_listing_location_town, 'wp_dp_locations');
                $location_ids[] = $term->term_id;
            }
            wp_set_post_terms($listing_id, $location_ids, 'wp_dp_locations');
        }

        /*
         * Save data into search meta key for the listing
         * @ this will be called from the frontend on search
         */

        public function wp_dp_save_search_keywords_field($listing_id = '', $post = array(), $update = '') {
            $post_type = get_post_type($listing_id);
            if ( $post_type == 'listings' ) {
                $post_title = $post->post_title;
                $content = $post->post_content;
                $listing_summary = get_post_meta($listing_id, 'wp_dp_listing_summary', true);
                $categories = get_post_meta($listing_id, 'wp_dp_listing_category', true);
                $listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                $tags = get_post_meta($listing_id, 'wp_dp_listing_tags', true);

                $field_string = '';

                $field_string .= $post_title . ' | ';
                $field_string .= $content . ' | ';
                $field_string .= $listing_summary . ' | ';

                if ( ! empty($categories) ) {
                    foreach ( $categories as $category_slug ) {
                        $term = get_term_by('slug', $category_slug, 'listing-category');
                        if ( isset($term->name) ) {
                            $field_string .= $term->name . ' | ';
                        }
                    }
                }

                if ( ! empty($tags) ) {
                    foreach ( $tags as $tag_slug ) {
                        $term = get_term_by('slug', $tag_slug, 'listing-tag');
                        if ( isset($term->name) ) {
                            $field_string .= $term->name . ' | ';
                        }
                    }
                }

                if ( $listing_type != '' ) {
                    $post_obj = get_page_by_path($listing_type, OBJECT, 'listing-type');
                    if ( isset($post_obj->post_title) ) {
                        $field_string .= $post_obj->post_title . ' | ';
                    }
                }
                update_post_meta($listing_id, 'search_keywords_field', $field_string);
            }
        }

        function wp_dp_submit_meta_box($post, $args = array()) {
            global $action, $wp_dp_form_fields, $post, $wp_dp_plugin_static_text;


            $post_type = $post->post_type;
            $post_type_object = get_post_type_object($post_type);
            $can_publish = current_user_can($post_type_object->cap->publish_posts);
            ?>
            <div class="submitbox wp-dp-submit" id="submitpost">
                <div id="minor-publishing">
                    <div style="display:none;">
                        <?php submit_button(wp_dp_plugin_text_srt('wp_dp_submit'), 'button', 'save'); ?>
                    </div>
                    <?php if ( $post_type_object->public && ! empty($post) ) : ?>
                        <!--						<div id="minor-publishing-actions">
                                                                                                                                                                                                                        <div id="preview-action">-->
                        <?php
                        if ( 'publish' == $post->post_status ) {
                            $preview_link = esc_url(get_permalink($post->ID));
                            $preview_button = wp_dp_plugin_text_srt('wp_dp_preview');
                        } else {
                            $preview_link = set_url_scheme(get_permalink($post->ID));

                            /**
                             * Filter the URI of a post preview in the post submit box.
                             *
                             * @since 2.0.5
                             * @since 4.0.0 $post parameter was added.
                             *
                             * @param string  $preview_link URI the user will be directed to for a post preview.
                             * @param WP_Post $post         Post object.
                             */
                            $preview_link = esc_url(apply_filters('preview_post_link', add_query_arg('preview', 'true', esc_url($preview_link)), $post));
                            $preview_button = wp_dp_plugin_text_srt('wp_dp_preview');
                        }
                        ?>
                        <!--							</div>
                                                                                                                                                                                                                        <div class="clear"></div>
                                                                                                                                                                                                                        </div>-->
                    <?php endif; // public post type                           ?>

                    <a href="classes/class-listings-page-elements.php"></a>
                </div>
                <div id="major-publishing-actions" style="border-top:0px">
                    <?php
                    /**
                     * Fires at the beginning of the publishing actions section of the Publish meta box.
                     *
                     * @since 2.7.0
                     */
                    do_action('post_submitbox_start');
                    ?>
                    <div id="delete-action">
                        <?php
                        if ( current_user_can("delete_post", $post->ID) ) {
                            if ( ! EMPTY_TRASH_DAYS ) {
                                $delete_text = wp_dp_plugin_text_srt('wp_dp_delete_permanently');
                            } else {
                                $delete_text = wp_dp_plugin_text_srt('wp_dp_move_to_trash');
                            }
                            if ( isset($_GET['action']) && $_GET['action'] == 'edit' ) {
                                ?>
                                <a class="submitdelete deletion" href="<?php echo get_delete_post_link($post->ID); ?>"><?php echo wp_dp_allow_special_char($delete_text) ?></a>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div id="publishing-action">
                        <span class="spinner"></span>
                        <?php
                        if ( ! in_array($post->post_status, array( 'publish', 'future', 'private' )) || 0 == $post->ID ) {
                            if ( $can_publish ) :
                                if ( ! empty($post->post_date_gmt) && time() < strtotime($post->post_date_gmt . ' +0000') ) :
                                    $wp_dp_opt_array = array(
                                        'std' => wp_dp_plugin_text_srt('wp_dp_schedule'),
                                        'id' => 'original_publish',
                                        'cust_name' => 'original_publish',
                                        'return' => false,
                                        'cust_type' => 'hidden',
                                        'prefix_on' => false,
                                    );
                                    $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);

                                    submit_button(esc_html('wp_dp_schedule'), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ));
                                else :
                                    $wp_dp_opt_array = array(
                                        'std' => wp_dp_plugin_text_srt('wp_dp_publish'),
                                        'id' => 'original_publish',
                                        'cust_name' => 'original_publish',
                                        'return' => false,
                                        'cust_type' => 'hidden',
                                        'prefix_on' => false,
                                    );
                                    $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                    submit_button(wp_dp_plugin_text_srt('wp_dp_publish'), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ));
                                endif;
                            else :
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_submit_for_review'),
                                    'id' => 'original_publish',
                                    'cust_name' => 'original_publish',
                                    'return' => false,
                                    'cust_type' => 'hidden',
                                    'prefix_on' => false,
                                );
                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                submit_button(wp_dp_plugin_text_srt('wp_dp_submit_for_review'), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ));
                                ?>
                            <?php
                            endif;
                        } else {

                            if ( isset($_GET['action']) && $_GET['action'] == 'edit' ) {
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_update'),
                                    'id' => 'original_publish',
                                    'cust_name' => 'original_publish',
                                    'return' => false,
                                    'cust_type' => 'hidden',
                                    'prefix_on' => false,
                                );
                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_update'),
                                    'id' => 'publish',
                                    'cust_name' => 'save',
                                    'return' => false,
                                    'classes' => 'button button-primary button-large',
                                    'cust_type' => 'submit',
                                    'extra_atr' => ' accesskey="p"',
                                    'prefix_on' => false,
                                );
                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                            } else {
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_publish'),
                                    'id' => 'original_publish',
                                    'cust_name' => 'original_publish',
                                    'return' => false,
                                    'cust_type' => 'hidden',
                                    'prefix_on' => false,
                                );
                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_publish'),
                                    'id' => 'publish',
                                    'cust_name' => 'publish',
                                    'return' => false,
                                    'classes' => 'button button-primary button-large',
                                    'cust_type' => 'submit',
                                    'extra_atr' => ' accesskey="p"',
                                    'prefix_on' => false,
                                );
                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                            }
                        }
                        ?>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>

            <?php
        }

    }

    global $wp_dp_listing_meta;
    $wp_dp_listing_meta = new wp_dp_listing_meta();
    return $wp_dp_listing_meta;
}
