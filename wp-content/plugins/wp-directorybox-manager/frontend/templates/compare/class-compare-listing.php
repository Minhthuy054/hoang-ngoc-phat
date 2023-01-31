<?php
/**
 * Compare listings class
 * 
 * @return Compare functionalities
 */
if ( ! class_exists('wp_dp_compare_listings') ) {

    class wp_dp_compare_listings {

        public function __construct() {
            add_action('wp_ajax_wp_dp_compare_add', array( $this, 'wp_dp_compare_add' ));
            add_action('wp_ajax_nopriv_wp_dp_compare_add', array( $this, 'wp_dp_compare_add' ));

            add_action('wp_ajax_wp_dp_dpoving_compare', array( $this, 'wp_dp_dpoving_compare' ));
            add_action('wp_ajax_nopriv_wp_dp_dpoving_compare', array( $this, 'wp_dp_dpoving_compare' ));

            add_action('wp_ajax_wp_dp_removed_compare', array( $this, 'wp_dp_removed_compare' ));
            add_action('wp_ajax_nopriv_wp_dp_removed_compare', array( $this, 'wp_dp_removed_compare' ));

            add_action('wp_ajax_wp_dp_clear_compare', array( $this, 'wp_dp_clear_compare_callback' ));
            add_action('wp_ajax_nopriv_wp_dp_clear_compare', array( $this, 'wp_dp_clear_compare_callback' ));

            add_action('wp_ajax_wp_dp_clear_compare_list', array( $this, 'wp_dp_clear_compare_list_callback' ));
            add_action('wp_ajax_nopriv_wp_dp_clear_compare_list', array( $this, 'wp_dp_clear_compare_list_callback' ));

            add_action('wp_dp_compare_btn', array( $this, 'wp_dp_listing_compare_button' ), 10, 3);
            add_action('wp_dp_listing_compare', array( $this, 'wp_dp_listing_compare_frontend_ui' ), 10, 5);

            add_filter('wp_dp_is_compare', array( $this, 'wp_dp_listing_is_compare' ), 10, 2);
            add_action('wp_dp_detail_compare_btn', array( $this, 'wp_dp_listing_detail_compare_button' ), 10, 3);
            add_action('wp_dp_listing_compare_sidebar', array( $this, 'wp_dp_listing_compare_sidebar_ui' ));
            add_shortcode('wp_dp_compare_listing', array( $this, 'wp_dp_compare_listings_listing' ));

            add_action('wp_ajax_wp_dp_cs_var_page_builder_wp_dp_compare_listing', array( $this, 'wp_dp_cs_var_page_builder_wp_dp_compare_listing' ));
            add_filter('wp_dp_cs_save_page_builder_data_wp_dp_compare_listing', array( $this, 'wp_dp_cs_save_page_builder_data_wp_dp_compare_listing_callback' ));
            add_filter('wp_dp_cs_load_shortcode_counters', array( $this, 'wp_dp_cs_load_shortcode_counters_wp_dp_compare_listing_callback' ));
            add_filter('wp_dp_cs_element_list_populate', array( $this, 'wp_dp_cs_element_list_populate_wp_dp_compare_listing_callback' ));
            add_filter('wp_dp_cs_shortcode_names_list_populate', array( $this, 'wp_dp_cs_shortcode_names_list_populate_wp_dp_compare_listing_callback' ));

            add_filter('wp_dp_listings_shortcode_admin_default_attributes', array( $this, 'wp_dp_listings_shortcode_admin_default_attributes_callback' ), 11, 1);
            add_action('wp_dp_compare_listings_element_field', array( $this, 'wp_dp_compare_listings_element_field_callback' ), 11, 1);
            add_filter('wp_dp_save_listings_shortcode_admin_fields', array( $this, 'wp_dp_save_listings_shortcode_admin_fields_callback' ), 11, 3);
        }

        public function wp_dp_clear_compare_callback() {
            $listing_id = isset($_POST['listing_id']) ? $_POST['listing_id'] : '';
            $listing_type_id = isset($_POST['type_id']) ? $_POST['type_id'] : '';
            if ( $listing_type_id != '' ) {
                if ( isset($_COOKIE['wp_dp_compare_list']) && $_COOKIE['wp_dp_compare_list'] != '' ) {
                    $cookie_compare_list = stripslashes($_COOKIE['wp_dp_compare_list']);
                    $cookie_compare_list = json_decode($cookie_compare_list, true);
                }
                $cookie_compare_list["type_{$listing_type_id}"] = '';
                $cookie_compare_list_clear = json_encode($cookie_compare_list);
                setcookie('wp_dp_compare_list', $cookie_compare_list_clear, time() + 86400, '/');
                echo json_encode(array( 'type' => 'success' ));
            } else {
                echo json_encode(array( 'type' => 'error' ));
            }
            die;
        }

        public function wp_dp_clear_compare_list_callback() {
            setcookie('wp_dp_compare_list', '', time() + 86400, '/');
            echo json_encode(array( 'type' => 'success' ));
            die;
        }

        public function wp_dp_listings_shortcode_admin_default_attributes_callback($attributes = array()) {
            $attributes['compare_listing_switch'] = '';
            return $attributes;
        }

        public function wp_dp_compare_listings_element_field_callback($atts = array()) {

            global $wp_dp_plugin_options, $wp_dp_html_fields;
            $wp_dp_all_compare_buttons = isset($wp_dp_plugin_options['wp_dp_all_compare_buttons']) ? $wp_dp_plugin_options['wp_dp_all_compare_buttons'] : '';
            if ( $wp_dp_all_compare_buttons ) {
                $wp_dp_opt_array = array(
                    'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_compare_switch'),
                    'desc' => '',
                    'hint_text' => '',
                    'echo' => true,
                    'field_params' => array(
                        'std' => esc_attr(isset($atts['compare_listing_switch']) ? $atts['compare_listing_switch'] : ''),
                        'id' => 'compare_listings',
                        'classes' => 'chosen-select-no-single',
                        'cust_name' => 'compare_listing_switch[]',
                        'return' => true,
                        'options' => array(
                            'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                            'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                        )
                    ),
                );
                $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
            }
        }

        public function wp_dp_save_listings_shortcode_admin_fields_callback($attributes = '', $data = array(), $counter = '') {
            if ( isset($data['compare_listing_switch'][$counter]) && $data['compare_listing_switch'][$counter] != '' ) {
                $attributes .= 'compare_listing_switch="' . htmlspecialchars($data['compare_listing_switch'][$counter], ENT_QUOTES) . '" ';
            }
            return $attributes;
        }

        public function wp_dp_compare_add() {
            global $wp_dp_plugin_options;
            $compare_listing_url = isset($wp_dp_plugin_options['wp_dp_compare_list_page']) ? $wp_dp_plugin_options['wp_dp_compare_list_page'] : '';
            if ( $compare_listing_url != '' ) {
                $compare_listing_url = esc_url(get_permalink($compare_listing_url));
            }
            $cookie_compare_list_add = $cookie_compare_list = array();
            $cookie_compare_list = array();
            if ( isset($_COOKIE['wp_dp_compare_list']) && $_COOKIE['wp_dp_compare_list'] != '' ) {
                $cookie_compare_list = stripslashes($_COOKIE['wp_dp_compare_list']);
                $cookie_compare_list = json_decode($cookie_compare_list, true);
            }

            $wp_dp_prop_id = isset($_POST['wp_dp_listing_id']) ? $_POST['wp_dp_listing_id'] : '';
            $wp_dp_check_action = isset($_POST['_action']) ? $_POST['_action'] : '';
            $add_to_compare = '';
            $add_to_compare_already = '';
            $listing_type_slug = get_post_meta($wp_dp_prop_id, 'wp_dp_listing_type', true);
            if ( $listing_type_slug != '' ) {
                $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish' ));
                $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
            } else {
                $listing_type_id = '';
            }
            $added_compare = '0';
            $before_btn = '<div class="compare-list-btn-holder">';
            $after_btn = '</div>';

            $before_btn = '';
            $after_btn = '';

            $mark_msg = wp_dp_plugin_text_srt('wp_dp_compare_cannot_add_to_list');
            if ( $listing_type_id != '' ) {
                if ( $wp_dp_check_action == 'check' ) {
                    $already_in_list = false;
                    if ( isset($cookie_compare_list["type_{$listing_type_id}"]['list_ids']) ) {
                        $wp_dp_type_comp_list = $cookie_compare_list["type_{$listing_type_id}"]['list_ids'];
                        if ( is_array($wp_dp_type_comp_list) && in_array($wp_dp_prop_id, $wp_dp_type_comp_list) ) {
                            if ( ($key = array_search($wp_dp_prop_id, $wp_dp_type_comp_list)) !== false ) {
                                $already_in_list = true;
                            }
                        }
                    }
                    if ( $already_in_list == true ) {
                        $mark_msg = wp_dp_plugin_text_srt('wp_dp_compare_already_compared');
                        if ( $compare_listing_url != '' ) {
                            $mark_msg .= $before_btn;
                            $mark_msg .= $after_btn;
                            $added_compare = '1';
                        }
                        $add_to_compare_already = wp_dp_plugin_text_srt('wp_dp_compare_add_to_compare');
                    } else {
                        $added_compare = '1';
                        if ( $compare_listing_url != '' ) {
                            $added_succesfully_msg = '';
                            $added_succesfully_msg .= wp_dp_plugin_text_srt('wp_dp_compare_added_to_compare');
                            $added_succesfully_msg .= $before_btn;
                            $added_succesfully_msg .= $after_btn;
                        } else {
                            $added_succesfully_msg = '';
                            $added_succesfully_msg .= wp_dp_plugin_text_srt('wp_dp_compare_added_to_compare');
                        }

                        if ( isset($cookie_compare_list) && ! isset($cookie_compare_list["type_{$listing_type_id}"]) ) {
                            $cookie_compare_list["type_{$listing_type_id}"] = array(
                                'type_id' => $listing_type_id,
                                'list_ids' => array( $wp_dp_prop_id ),
                            );
                            $cookie_compare_list_add = json_encode($cookie_compare_list);
                            setcookie('wp_dp_compare_list', $cookie_compare_list_add, time() + 86400, '/');
                            $mark_msg = $added_succesfully_msg;
                        } else if ( isset($cookie_compare_list) && isset($cookie_compare_list["type_{$listing_type_id}"]) ) {
                            $type_session_arr = $cookie_compare_list["type_{$listing_type_id}"];
                            if ( isset($type_session_arr['list_ids']) && is_array($type_session_arr['list_ids']) && sizeof($type_session_arr['list_ids']) < 3 ) {
                                array_push($type_session_arr['list_ids'], $wp_dp_prop_id);
                                $cookie_compare_list["type_{$listing_type_id}"] = array(
                                    'type_id' => $listing_type_id,
                                    'list_ids' => $type_session_arr['list_ids'],
                                );
                                $cookie_compare_list_add = json_encode($cookie_compare_list);
                                setcookie('wp_dp_compare_list', $cookie_compare_list_add, time() + 86400, '/');
                                $mark_msg = $added_succesfully_msg;
                            } else if ( isset($type_session_arr['list_ids']) && is_array($type_session_arr['list_ids']) && sizeof($type_session_arr['list_ids']) >= 3 ) {
                                $mark_msg = wp_dp_plugin_text_srt('wp_dp_compare_already_have_listings');
                                $mark_msg .= $before_btn;
                                $mark_msg .= $after_btn;
                                $add_to_compare_already = wp_dp_plugin_text_srt('wp_dp_compare_add_to_compare');
                                $mark_msg .= '<script>jQuery("#check-list' . $wp_dp_prop_id . '").attr("checked", false);</script>';
                                $added_compare = '3';
                            } else if ( ! isset($type_session_arr['list_ids']) ) {
                                $cookie_compare_list["type_{$listing_type_id}"] = array(
                                    'type_id' => $listing_type_id,
                                    'list_ids' => array( $wp_dp_prop_id ),
                                );
                                $cookie_compare_list_add = json_encode($cookie_compare_list);
                                setcookie('wp_dp_compare_list', $cookie_compare_list_add, time() + 86400, '/');
                                $mark_msg = $added_succesfully_msg;
                                $added_compare = '0';
                            }
                        } else {
                            $cookie_compare_list = array(
                                "type_{$listing_type_id}" => array(
                                    'type_id' => $listing_type_id,
                                    'list_ids' => array( $wp_dp_prop_id ),
                                ),
                            );
                            $cookie_compare_list_add = json_encode($cookie_compare_list);
                            setcookie('wp_dp_compare_list', $cookie_compare_list_add, time() + 86400, '/');
                            $mark_msg = $added_succesfully_msg;
                            $added_compare = '1';
                        }
                    }
                } else {
                    if ( isset($cookie_compare_list["type_{$listing_type_id}"]['list_ids']) ) {
                        $wp_dp_type_comp_list = $cookie_compare_list["type_{$listing_type_id}"]['list_ids'];
                        if ( is_array($wp_dp_type_comp_list) && in_array($wp_dp_prop_id, $wp_dp_type_comp_list) ) {
                            if ( ($key = array_search($wp_dp_prop_id, $wp_dp_type_comp_list)) !== false ) {
                                unset($wp_dp_type_comp_list[$key]);
                                $cookie_compare_list_add["type_{$listing_type_id}"] = array(
                                    'type_id' => $listing_type_id,
                                    'list_ids' => $wp_dp_type_comp_list,
                                );

                                $cookie_compare_list_add = json_encode($cookie_compare_list_add);
                                setcookie('wp_dp_compare_list', $cookie_compare_list_add, time() + 86400, '/');
                            }
                        }
                    }

                    $mark_msg = '';
                    $mark_msg .= wp_dp_plugin_text_srt('wp_dp_compare_was_removed_from_compare');
                    if ( $compare_listing_url != '' ) {
                        $mark_msg .= $before_btn;
                        $mark_msg .= $after_btn;
                    }

                    $add_to_compare = wp_dp_plugin_text_srt('wp_dp_compare_add_to_compare');
                    $added_compare = '0';
                }
            } else {
                $mark_msg = wp_dp_plugin_text_srt('wp_dp_compare_cannot_add_to_list');
                if ( $compare_listing_url != '' ) {
                    $mark_msg .= $before_btn;
                    $mark_msg .= $after_btn;
                }
                $added_compare = '0';
            }
            $compare_msg = '';
            $compare_status = '';
            if ( $add_to_compare != '' ) {
                $compare_msg = $add_to_compare;
                $compare_status = 'removed';
            } elseif ( $add_to_compare_already != '' ) {
                $compare_msg = $add_to_compare_already;
                $compare_status = 'already_added';
            } else {
                $compare_msg = wp_dp_plugin_text_srt('wp_dp_compare_remove_to_compare');
                $compare_status = 'added';
            }

            ob_start();
            $this->wp_dp_listing_compare_sidebar_list_render($wp_dp_prop_id, $listing_type_id);
            $output = ob_get_contents();
            ob_end_clean();

            echo json_encode(array( 'mark' => $mark_msg, 'compare' => $compare_msg, 'type' => $added_compare, 'html' => $output, 'status' => $compare_status ));
            die;
        }

        public function wp_dp_dpoving_compare() {

            $wp_dp_prop_id = isset($_POST['listing_id']) ? $_POST['listing_id'] : '';
            $wp_dp_type_id = isset($_POST['type_id']) ? $_POST['type_id'] : '';
            $wp_dp_prop_ids = isset($_POST['prop_ids']) ? $_POST['prop_ids'] : '';
            $wp_dp_page_id = isset($_POST['page_id']) ? $_POST['page_id'] : '';

            $cookie_compare_list_add = array();
            $cookie_compare_list = '';
            if ( isset($_COOKIE['wp_dp_compare_list']) && $_COOKIE['wp_dp_compare_list'] != '' ) {
                $cookie_compare_list = stripslashes($_COOKIE['wp_dp_compare_list']);
                $cookie_compare_list = json_decode($cookie_compare_list, true);
            }

            if ( isset($cookie_compare_list["type_{$wp_dp_type_id}"]['list_ids']) ) {
                $wp_dp_type_comp_list = $cookie_compare_list["type_{$wp_dp_type_id}"]['list_ids'];
                if ( is_array($wp_dp_type_comp_list) && in_array($wp_dp_prop_id, $wp_dp_type_comp_list) ) {
                    if ( ($key = array_search($wp_dp_prop_id, $wp_dp_type_comp_list)) !== false ) {
                        unset($wp_dp_type_comp_list[$key]);
                        $cookie_compare_list_add["type_{$wp_dp_type_id}"] = array(
                            'type_id' => $wp_dp_type_id,
                            'list_ids' => $wp_dp_type_comp_list,
                        );
                        $cookie_compare_list_add = json_encode($cookie_compare_list_add);
                        setcookie('wp_dp_compare_list', $cookie_compare_list_add, time() + 86400, '/');
                    }
                }
            }

            $wp_dp_prop_ids = explode(',', $wp_dp_prop_ids);
            if ( in_array($wp_dp_prop_id, $wp_dp_prop_ids) ) {
                if ( ($key = array_search($wp_dp_prop_id, $wp_dp_prop_ids)) !== false ) {
                    unset($wp_dp_prop_ids[$key]);
                }
            }
            $wp_dp_prop_ids = implode(',', $wp_dp_prop_ids);

            $final_url = add_query_arg(array( 'type' => $wp_dp_type_id, 'listings_ids' => $wp_dp_prop_ids ), get_permalink($wp_dp_page_id));

            echo json_encode(array( 'url' => $final_url ));
            die;
        }

        public function wp_dp_removed_compare() {

            $wp_dp_prop_id = isset($_POST['listing_id']) ? $_POST['listing_id'] : '';
            $wp_dp_type_id = isset($_POST['type_id']) ? $_POST['type_id'] : '';

            $cookie_compare_list_add = array();
            $cookie_compare_list = '';
            if ( isset($_COOKIE['wp_dp_compare_list']) && $_COOKIE['wp_dp_compare_list'] != '' ) {
                $cookie_compare_list = stripslashes($_COOKIE['wp_dp_compare_list']);
                $cookie_compare_list = json_decode($cookie_compare_list, true);
            }

            if ( isset($cookie_compare_list["type_{$wp_dp_type_id}"]['list_ids']) ) {
                $wp_dp_type_comp_list = $cookie_compare_list["type_{$wp_dp_type_id}"]['list_ids'];
                if ( is_array($wp_dp_type_comp_list) && in_array($wp_dp_prop_id, $wp_dp_type_comp_list) ) {
                    if ( ($key = array_search($wp_dp_prop_id, $wp_dp_type_comp_list)) !== false ) {
                        unset($wp_dp_type_comp_list[$key]);
                        $cookie_compare_list_add["type_{$wp_dp_type_id}"] = array(
                            'type_id' => $wp_dp_type_id,
                            'list_ids' => $wp_dp_type_comp_list,
                        );
                        $cookie_compare_list_add = json_encode($cookie_compare_list_add);
                        setcookie('wp_dp_compare_list', $cookie_compare_list_add, time() + 86400, '/');
                    }
                }
            }

            echo json_encode(array( 'type' => 'success', 'mark' => wp_dp_plugin_text_srt('wp_dp_compare_was_removed_from_compare') ));
            die;
        }

        public function wp_dp_listing_compare_sidebar_ui() {
            global $wp_dp_plugin_options;
            $wp_dp_plugin_compare_listing_switch = isset($wp_dp_plugin_options['wp_dp_compare_functionality_switch']) ? $wp_dp_plugin_options['wp_dp_compare_functionality_switch'] : '';
            if ( $wp_dp_plugin_compare_listing_switch != 'on' ) {
                return;
            }
            if ( did_action('wp_dp_listing_compare_sidebar') == 1 ) {
				wp_enqueue_script('wp-dp-listing-compare');
                $compare_listing_url = isset($wp_dp_plugin_options['wp_dp_compare_list_page']) ? $wp_dp_plugin_options['wp_dp_compare_list_page'] : '';
                $cookie_compare_list = '';
                if ( isset($_COOKIE['wp_dp_compare_list']) && $_COOKIE['wp_dp_compare_list'] != '' ) {

                    $cookie_compare_list = stripslashes($_COOKIE['wp_dp_compare_list']);
                    $cookie_compare_list = json_decode($cookie_compare_list, true);
                }

                $compare_listings_list = array();
                if ( isset($cookie_compare_list) && ! empty($cookie_compare_list) && is_array($cookie_compare_list) ) {
                    foreach ( $cookie_compare_list as $key => $val ) {
                        if ( $val['type_id'] != '' && ! empty($val['list_ids']) ) {
                            $compare_listings_list[$val['type_id']] = $val['list_ids'];
                        }
                    }
                }
                ?>
                <div id="compare-sidebar-panel" class="fixed-sidebar-panel right chosen-compare-list">
                    <div class="sidebar-panel-content">
                        <div class="sidebar-panel-header">
                            <strong class="sidebar-panel-title"> 
                                <?php echo wp_dp_plugin_text_srt('wp_dp_sidebar_compare_listings_lable'); ?>
                                <span class="sidebar-panel-btn-close pull-right">
                                    <i class="icon-cross"></i>
                                </span>
                            </strong>
                        </div>
                        <div class="sidebar-panel-body">
                            <div class="sidebar-listings-list">
                                <ul>
                                    <?php
                                    $compare_open_close = 'none';
                                    if ( isset($compare_listings_list) && ! empty($compare_listings_list) ) {
                                        foreach ( $compare_listings_list as $type_id => $listing_ids ) {
                                            if ( isset($compare_listings_list) && ! empty($compare_listings_list) ) {
                                                foreach ( $listing_ids as $listing_id ) {
                                                    $this->wp_dp_listing_compare_sidebar_list_render($listing_id, $type_id);
                                                    $compare_open_close = 'block';
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </ul>
                                <div class="sidebar-btn-holder">
                                    <a href="javascript:void(0);" class="bgcolor sidebar-listing-btn clear-compare-list"><?php echo wp_dp_plugin_text_srt('wp_dp_sidebar_compare_reset_button_lable'); ?></a>
                                    <?php if ( $compare_listing_url != '' ) { ?>
                                        <a href="<?php echo wp_dp_wpml_lang_page_permalink($compare_listing_url, 'page'); ?>" class="sidebar-listing-btn text-color border-color"><?php echo wp_dp_plugin_text_srt('wp_dp_sidebar_compare_button_lable'); ?></a>
                                    <?php } ?>
                                </div>
                                <div class="compare-response" style="display:none;"></div>
                            </div>
                            <button class="bgcolor sidebar-panel-btn" style="display:<?php echo wp_dp_cs_allow_special_char($compare_open_close); ?>"><i class="icon-keyboard_arrow_left"></i></button>
                        </div>
                    </div>
                </div>
                <?php
            }
        }

        public function wp_dp_listing_compare_sidebar_list_render($listing_id = '', $type_id = '') {
            if ( $listing_id != '' ) {
                $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                $wp_dp_listing_type = isset($wp_dp_listing_type) ? $wp_dp_listing_type : '';
                if ( $listing_type_post = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type') )
                    $listing_type_id = $listing_type_post->ID;
                $listing_type_id = isset($listing_type_id) ? $listing_type_id : '';
                $listing_type_id = wp_dp_wpml_lang_page_id($listing_type_id, 'listing-type');
                $wp_dp_listing_type_price_switch = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
                $wp_dp_listing_price_options = get_post_meta($listing_id, 'wp_dp_listing_price_options', true);
                $wp_dp_listing_price = '';
                if ( $wp_dp_listing_price_options == 'price' ) {
                    $wp_dp_listing_price = get_post_meta($listing_id, 'wp_dp_listing_price', true);
                } else if ( $wp_dp_listing_price_options == 'on-call' ) {
                    $wp_dp_listing_price = wp_dp_plugin_text_srt('wp_dp_listings_price_on_request');
                }
                ?>
                <li class="compare-listing-<?php echo intval($listing_id); ?>" data-id="<?php echo intval($listing_id); ?>" data-type-id="<?php echo intval($type_id); ?>">
                    <div class="listing-item">
                        <div class="img-holder">
                            <figure>
                                <?php
                                if ( function_exists('listing_gallery_first_image') ) {
                                    $gallery_image_args = array(
                                        'listing_id' => $listing_id,
                                        'size' => 'thumbnail',
                                        'class' => 'compare-img-grid',
                                        'default_image_src' => esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image9x6.jpg')
                                    );
                                    $listing_gallery_first_image = listing_gallery_first_image($gallery_image_args);
                                    echo wp_dp_cs_allow_special_char($listing_gallery_first_image);
                                }
                                ?>
                            </figure>
                        </div>
                        <div class="text-holder">
                            <strong class="listing-title"><a href="<?php echo esc_url(get_permalink($listing_id)); ?>"><?php echo esc_html(get_the_title($listing_id)); ?></a></strong>
                            <?php if ( $wp_dp_listing_type_price_switch == 'on' && $wp_dp_listing_price != '' ) { ?>
                                <span class="text-color listing-price">
                                    <?php
                                    if ( $wp_dp_listing_price_options == 'on-call' ) {
                                        echo force_balance_tags($wp_dp_listing_price);
                                    } else {
                                        $listing_info_price = wp_dp_listing_price($listing_id, $wp_dp_listing_price);
                                        echo force_balance_tags($listing_info_price);
                                    }
                                    ?>                           
                                </span>
                            <?php } ?>
                            <span class="listing-item-dpove"><i class="icon-trash3"></i></span>
                        </div>
                    </div>
                </li>
                <?php
            }
        }

        public function wp_dp_listing_is_compare($prop_id = '', $show_compare = 'no') {
            global $listing_random_id, $wp_dp_plugin_options;
            $prop_is_compare = '';
            $wp_dp_all_compare_buttons = isset($wp_dp_plugin_options['wp_dp_all_compare_buttons']) ? $wp_dp_plugin_options['wp_dp_all_compare_buttons'] : '';
            if ( $wp_dp_all_compare_buttons == 'on' && $show_compare == 'yes' ) {
                wp_enqueue_script('wp-dp-listing-compare');
                $cookie_compare_list = '';
                if ( isset($_COOKIE['wp_dp_compare_list']) && $_COOKIE['wp_dp_compare_list'] != '' ) {
                    $cookie_compare_list = stripslashes($_COOKIE['wp_dp_compare_list']);
                    $cookie_compare_list = json_decode($cookie_compare_list, true);
                }

                $listing_type_slug = get_post_meta($prop_id, 'wp_dp_listing_type', true);
                if ( $listing_type_slug != '' ) {
                    $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish' ));
                    $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
                } else {
                    $listing_type_id = '';
                }
                if ( isset($cookie_compare_list["type_{$listing_type_id}"]['list_ids']) ) {
                    $wp_dp_type_comp_list = $cookie_compare_list["type_{$listing_type_id}"]['list_ids'];
                    if ( is_array($wp_dp_type_comp_list) && in_array($prop_id, $wp_dp_type_comp_list) ) {
                        $prop_is_compare = ' active';
                    }
                }
                return $prop_is_compare;
            }
        }

        public function wp_dp_listing_compare_frontend_ui($prop_id = '', $show_compare = 'no', $tooltip = 'no', $before_html = '', $after_html = '') {
            global $listing_random_id, $wp_dp_plugin_options;
            $wp_dp_all_compare_buttons = isset($wp_dp_plugin_options['wp_dp_all_compare_buttons']) ? $wp_dp_plugin_options['wp_dp_all_compare_buttons'] : '';
            if ( $wp_dp_all_compare_buttons == 'on' && $show_compare == 'yes' ) {
                $rand = rand(98765, 56789);
                wp_enqueue_script('wp-dp-listing-compare');
                $cookie_compare_list = '';
                if ( isset($_COOKIE['wp_dp_compare_list']) && $_COOKIE['wp_dp_compare_list'] != '' ) {
                    $cookie_compare_list = stripslashes($_COOKIE['wp_dp_compare_list']);
                    $cookie_compare_list = json_decode($cookie_compare_list, true);
                }

                $listing_type_slug = get_post_meta($prop_id, 'wp_dp_listing_type', true);
                if ( $listing_type_slug != '' ) {
                    $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish' ));
                    $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
                } else {
                    $listing_type_id = '';
                }
                $prop_compare_check = '';
                $pro_compare_class = '';
                $compare_label = wp_dp_plugin_text_srt('wp_dp_compare_label');
                if ( isset($cookie_compare_list["type_{$listing_type_id}"]['list_ids']) ) {
                    $wp_dp_type_comp_list = $cookie_compare_list["type_{$listing_type_id}"]['list_ids'];
                    if ( is_array($wp_dp_type_comp_list) && in_array($prop_id, $wp_dp_type_comp_list) ) {
                        $prop_compare_check = ' checked="checked"';
                        $pro_compare_class = ' compared';
                        $compare_label = wp_dp_plugin_text_srt('wp_dp_compared_label');
                    }
                }
                $html = '';
                $html .= $before_html;
                $html .= '<div class="compare-listings-' . absint($prop_id) . ' compare-listing ' . esc_html($pro_compare_class) . '">';
                $html .= '<input type="checkbox" ' . $prop_compare_check . ' class="wp_dp_listing_compare_check" data-id="' . absint($prop_id) . '" data-random="' . absint($prop_id . $rand) . '" name="list" value="check-listn" id="check-list' . absint($prop_id . $rand) . '">';
                $html .= '<label for="check-list' . absint($prop_id . $rand) . '"><i class="icon-compare_arrows"></i>';
                $html .= '<span class="option-content"><span>' . $compare_label . '</span></span></label>';
                $html .= '</div>';
                $html .= $after_html;

                echo force_balance_tags($html);
            }
        }

        public function wp_dp_listing_compare_button($prop_id = '', $show_compare = 'no', $tooltip = 'no') {
            global $listing_random_id, $wp_dp_plugin_options;
            $wp_dp_all_compare_buttons = isset($wp_dp_plugin_options['wp_dp_all_compare_buttons']) ? $wp_dp_plugin_options['wp_dp_all_compare_buttons'] : '';
            if ( $wp_dp_all_compare_buttons == 'on' && $show_compare == 'yes' ) {
                wp_enqueue_script('wp-dp-listing-compare');
                $cookie_compare_list = '';
                if ( isset($_COOKIE['wp_dp_compare_list']) && $_COOKIE['wp_dp_compare_list'] != '' ) {
                    $cookie_compare_list = stripslashes($_COOKIE['wp_dp_compare_list']);
                    $cookie_compare_list = json_decode($cookie_compare_list, true);
                }

                $listing_type_slug = get_post_meta($prop_id, 'wp_dp_listing_type', true);
                if ( $listing_type_slug != '' ) {
                    $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish' ));
                    $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
                } else {
                    $listing_type_id = '';
                }
                $prop_compare_check = '';
                $pro_compare_class = '';
                if ( isset($cookie_compare_list["type_{$listing_type_id}"]['list_ids']) ) {
                    $wp_dp_type_comp_list = $cookie_compare_list["type_{$listing_type_id}"]['list_ids'];
                    if ( is_array($wp_dp_type_comp_list) && in_array($prop_id, $wp_dp_type_comp_list) ) {
                        $prop_compare_check = ' checked="checked"';
                        $pro_compare_class = ' compared';
                    }
                }
                $html = '';
                $html .= '<div class="compare-listing ' . esc_html($pro_compare_class) . '">';
                if ( $tooltip == 'yes' ) {
                    $html .= '<span class="compare-label">' . wp_dp_plugin_text_srt('wp_dp_compare_label') . '</span>';
                }
                $html .= '<input type="checkbox" ' . $prop_compare_check . ' class="wp_dp_compare_check_add" data-id="' . absint($prop_id) . '" data-random = "' . absint($prop_id) . '" name="list" value="check-listn" id="check-list' . absint($prop_id) . '">';
                $html .= '<label for="check-list' . absint($prop_id) . '"><i class="icon-compare_arrows"></i><span class="wp-dp-compare-loader-' . absint($prop_id) . '"></span><em>' . wp_dp_plugin_text_srt('wp_dp_compare_label') . '</em></label>';
                $html .= '</div>';

                echo force_balance_tags($html);
            }
        }

        public function wp_dp_listing_detail_compare_button($prop_id = '', $before_html = '', $after_html = '') {
            global $wp_dp_plugin_options;
            $wp_dp_all_compare_buttons = isset($wp_dp_plugin_options['wp_dp_all_compare_buttons']) ? $wp_dp_plugin_options['wp_dp_all_compare_buttons'] : '';
            if ( $wp_dp_all_compare_buttons == 'on' ) {
                wp_enqueue_script('wp-dp-listing-compare');
                $cookie_compare_list = '';
                if ( isset($_COOKIE['wp_dp_compare_list']) && $_COOKIE['wp_dp_compare_list'] != '' ) {
                    $cookie_compare_list = stripslashes($_COOKIE['wp_dp_compare_list']);
                    $cookie_compare_list = json_decode($cookie_compare_list, true);
                }

                $listing_type_slug = get_post_meta($prop_id, 'wp_dp_listing_type', true);
                if ( $listing_type_slug != '' ) {
                    $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => $listing_type_slug, 'post_status' => 'publish' ));
                    $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
                } else {
                    $listing_type_id = '';
                }
                $prop_compare_check = 'check';
                $compare_title = wp_dp_plugin_text_srt('wp_dp_compare_add_to_compare');
                $remove_compare_title = wp_dp_plugin_text_srt('wp_dp_compare_remove_to_compare');
                $html = $before_html;
                $html .= '<div class="detail-compare-btn"><a class="btn-compare wp-dp-btn-compare" data-type="detail-page" data-id="' . $prop_id . '" data-check="' . $prop_compare_check . '" data-ajaxurl="' . admin_url('admin-ajax.php') . '"><i class="icon-compare-filled2"></i><span>' . $compare_title . '</span></a></div>';
                $html .= $after_html;
                if ( isset($cookie_compare_list["type_{$listing_type_id}"]['list_ids']) ) {
                    $wp_dp_type_comp_list = $cookie_compare_list["type_{$listing_type_id}"]['list_ids'];
                    if ( is_array($wp_dp_type_comp_list) && in_array($prop_id, $wp_dp_type_comp_list) ) {
                        $prop_compare_uncheck = 'uncheck';
                        $html = $before_html;
                        $html .= '<div class="detail-compare-btn"><a class="btn-compare wp-dp-btn-compare" data-type="detail-page" data-id="' . $prop_id . '" data-check="' . $prop_compare_uncheck . '" data-check="' . $prop_compare_check . '" data-ajaxurl="' . admin_url('admin-ajax.php') . '"><i class="icon-compare-filled2"></i><span>' . $remove_compare_title . '</span></a></div>';
                        $html .= $after_html;
                    }
                }
                echo force_balance_tags($html);
            }
        }

        public function wp_dp_compare_listings_listing($atts, $content = "") {
            global $wp_dp_plugin_options, $wp_dp_form_fields_frontend;
            $wp_dp_search_result_page = isset($wp_dp_plugin_options['wp_dp_search_result_page']) ? $wp_dp_plugin_options['wp_dp_search_result_page'] : '';

            wp_enqueue_script('wp-dp-listing-compare');

            $wp_dp_blog_num_post = 4; // only allow to compare number of items
            $default_date_time_formate = 'd-m-Y H:i:s';
            $wp_dp_compare_listing_posted_date_formate = 'd-m-Y H:i:s';
            $wp_dp_compare_listing_expired_date_formate = 'd-m-Y H:i:s';
            $listing_type = '';
            ob_start();

            $cookie_compare_list = '';
            if ( isset($_COOKIE['wp_dp_compare_list']) && $_COOKIE['wp_dp_compare_list'] != '' ) {
                $cookie_compare_list = stripslashes($_COOKIE['wp_dp_compare_list']);
                $cookie_compare_list = json_decode($cookie_compare_list, true);
            }

            $get_query_type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
            $wp_dp_compare_session_list = array();
            if ( isset($cookie_compare_list) && is_array($cookie_compare_list) && sizeof($cookie_compare_list) > 0 ) {
                foreach ( $cookie_compare_list as $wp_dp_compare_list ) {
                    $listing_type = isset($wp_dp_compare_list['type_id']) ? $wp_dp_compare_list['type_id'] : '';
                    $meta_post_ids_arr = isset($wp_dp_compare_list['list_ids']) ? $wp_dp_compare_list['list_ids'] : '';
                    if ( is_array($meta_post_ids_arr) && sizeof($meta_post_ids_arr) > 1 ) {
                        $wp_dp_compare_session_list[$listing_type] = $wp_dp_compare_list;
                    }
                }
            }
            $meta_post_ids_arr = array();
            if ( (isset($_REQUEST['listings_ids']) && $_REQUEST['listings_ids'] != '') && (isset($_REQUEST['type']) && $_REQUEST['type'] != '') ) {
                $listing_type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
                $listings_ids = isset($_REQUEST['listings_ids']) ? $_REQUEST['listings_ids'] : '';
                $meta_post_ids_arr = explode(',', $listings_ids);
            } else if ( isset($cookie_compare_list) && is_array($cookie_compare_list) && sizeof($cookie_compare_list) > 0 ) {
                foreach ( $cookie_compare_list as $wp_dp_compare_list ) {
                    if ( isset($wp_dp_compare_list['list_ids']) && is_array($wp_dp_compare_list['list_ids']) && sizeof($wp_dp_compare_list['list_ids']) > 1 ) {
                        $listing_type = isset($wp_dp_compare_list['type_id']) ? $wp_dp_compare_list['type_id'] : '';
                        $meta_post_ids_arr = isset($wp_dp_compare_list['list_ids']) ? $wp_dp_compare_list['list_ids'] : '';
                        if ( $get_query_type != '' && $get_query_type == $listing_type ) {
                            $listing_type = isset($wp_dp_compare_list['type_id']) ? $wp_dp_compare_list['type_id'] : '';
                            $meta_post_ids_arr = isset($wp_dp_compare_list['list_ids']) ? $wp_dp_compare_list['list_ids'] : '';
                            break;
                        }
                    }
                }
            }

            if ( $get_query_type == '' ) {
                $get_query_type = $listing_type;
            }
            ?>

            <!-- alert for complete theme -->
            <div class="wp_dp_alerts"></div>
            <!-- main-wp-dp-loader for complete theme -->
            <div class="main-wp-dp-loader"></div>
            <?php
            $defaults = array(
                'listing_compare_title' => '',
                'compare_subtitle' => '',
                'compare_title_align' => '',
                'wp_dp_compare_element_title_color' => '',
                'wp_dp_compare_element_subtitle_color' => '',
                'wp_dp_compare_seperator_style' => '',
            );
            extract(shortcode_atts($defaults, $atts));
            ?>
            <div class="row">
                <div class="section-fullwidth col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php
                    $wp_dp_element_structure = '';
                    $wp_dp_element_structure .= wp_dp_plugin_title_sub_align($listing_compare_title, $compare_subtitle, $compare_title_align, $wp_dp_compare_element_title_color, $wp_dp_compare_seperator_style, $wp_dp_compare_element_subtitle_color);
                    echo force_balance_tags($wp_dp_element_structure);
                    ?>
                    <div class="wp-dp-compare" data-id="<?php echo get_the_id() ?>" data-ids="<?php echo isset($_REQUEST['listings_ids']) ? $_REQUEST['listings_ids'] : '' ?>">
                        <?php if ( sizeof($wp_dp_compare_session_list) > 1 ) { ?>
                            <div class="compare-listings-types">
                                <div class="field-holder"> 
                                    <form method="get" name="compare-types" action="">
                                        <ul>
                                            <?php
                                            $number_option_flag = 1;
                                            foreach ( $wp_dp_compare_session_list as $c_m_key => $c_m_val ) {
                                                ?>
                                                <li>
                                                    <?php
                                                    $checked = '';
                                                    if ( $c_m_key == $get_query_type ) {
                                                        $checked = 'checked="checked"';
                                                    }
                                                    $wp_dp_form_fields_frontend->wp_dp_form_radio_render(
                                                            array(
                                                                'simple' => true,
                                                                'cust_id' => 'listing_type' . $number_option_flag,
                                                                'cust_name' => 'type',
                                                                'std' => $c_m_key,
                                                                'extra_atr' => $checked . 'onchange="this.form.submit();"',
                                                            )
                                                    );
                                                    ?>
                                                    <label for="<?php echo force_balance_tags('listing_type' . $number_option_flag) ?>"><?php echo get_the_title($c_m_key); ?></label>
                                                </li>
                                                <?php
                                                $number_option_flag ++;
                                            }
                                            ?>
                                        </ul>
                                    </form>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ( is_array($meta_post_ids_arr) && sizeof($meta_post_ids_arr) > 1 ) {
                            ?>
                            <script>
                                var current_url = location.protocol + "//" + location.host + location.pathname;
                                var query_sep = '?';
                                if (current_url.indexOf("?") != -1) {
                                    query_sep = '&';
                                }
                                window.history.pushState(null, null, current_url + query_sep + "type=<?php echo absint($listing_type) ?>&listings_ids=<?php echo implode(',', $meta_post_ids_arr) ?>");
                            </script>
                            <ul class="wp-dp-compare-list">
                                <li>
                                    <div class="wp-dp-compare-box"></div>
                                    <?php
                                    $meta_post_ids_size = sizeof($meta_post_ids_arr);
                                    $meta_post_ids_counter = 1;
                                    foreach ( $meta_post_ids_arr as $listing_id ) {
                                        ?>
                                        <div class="wp-dp-compare-box dev-dp-<?php echo absint($listing_id) ?>">
                                            <div class="wp-dp-media">
                                                <figure>
                                                    <?php
                                                    if ( function_exists('listing_gallery_first_image') ) {
                                                        $size = 'wp_dp_cs_media_5';
                                                        $gallery_image_args = array(
                                                            'listing_id' => $listing_id,
                                                            'size' => $size,
                                                            'class' => 'img-grid',
                                                            'default_image_src' => esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image9x6.jpg')
                                                        );
                                                        $listing_gallery_first_image = listing_gallery_first_image($gallery_image_args);
                                                        echo wp_dp_cs_allow_special_char($listing_gallery_first_image);
                                                    }
                                                    ?>

                                                    <figcaption>
                                                        <a class="wp-dp-bgcolor wp-dp-dpove-compare-item" data-id="<?php echo absint($listing_id) ?>" data-type-id="<?php echo absint($listing_type) ?>"><i class="icon-cross2"></i></a>
                                                    </figcaption>
                                                </figure>
                                            </div>
                                            <?php if ( $meta_post_ids_counter != $meta_post_ids_size ) { ?>
                                                <span class="wp-dp-vs"><?php echo wp_dp_plugin_text_srt('wp_dp_compare_vs'); ?></span>
                                            <?php } ?>
                                        </div>
                                        <?php
                                        $meta_post_ids_counter ++;
                                    }
                                    ?>
                                </li>  
                                <li>
                                    <div class="wp-dp-compare-box">
                                        <p class="label"><?php echo wp_dp_plugin_text_srt('wp_dp_compare_basic_info'); ?></p>
                                    </div>
                                    <?php
                                    foreach ( $meta_post_ids_arr as $listing_id ) {
                                        $wp_dp_listing_price = '';
                                        $wp_dp_listing_price_options = get_post_meta($listing_id, 'wp_dp_listing_price_options', true);
                                        if ( $wp_dp_listing_price_options == 'price' ) {
                                            $wp_dp_listing_price = get_post_meta($listing_id, 'wp_dp_listing_price', true);
                                        } else if ( $wp_dp_listing_price_options == 'on-call' ) {
                                            $wp_dp_listing_price = wp_dp_plugin_text_srt('wp_dp_listings_price_on_request');
                                        }
                                        ?>
                                        <div class="wp-dp-compare-box dev-dp-<?php echo absint($listing_id) ?>">
                                            <div class="wp-dp-post-title">
                                                <h6>
                                                    <a href="<?php echo esc_url(get_permalink($listing_id)) ?>"><?php echo get_the_title($listing_id) ?></a>
                                                </h6>
                                            </div>
                                            <div class="wp-dp-price">
                                                <strong class="wp-dp-color">
                                                    <?php
                                                    if ( $wp_dp_listing_price_options == 'on-call' ) {
                                                        echo force_balance_tags($wp_dp_listing_price);
                                                    } else {
                                                        $listing_info_price = wp_dp_listing_price($listing_id, $wp_dp_listing_price);
                                                        echo force_balance_tags($listing_info_price);
                                                    }
                                                    ?>
                                                </strong>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </li>
                                <li>
                                    <div class="wp-dp-compare-box">
                                        <small class="label"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_listing_category'); ?></small>
                                    </div>
                                    <?php foreach ( $meta_post_ids_arr as $listing_id ) { ?>
                                        <div class="wp-dp-compare-box dev-dp-<?php echo absint($listing_id) ?>">
                                            <span>
                                                <?php
                                                $listing_category = get_post_meta($listing_id, 'wp_dp_listing_category', true);
                                                $listing_parent_category = isset($listing_category['parent']) ? $listing_category['parent'] : '';
                                                if ( $listing_parent_category !== '' ) {
                                                    $term = get_term_by('slug', $listing_parent_category, 'listing-category');
                                                    echo wp_dp_allow_special_char(isset($term->name) ? $term->name : '<i class="icon icon-cross"></i>');
                                                } else {
                                                    echo '<i class="icon icon-cross"></i>';
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    <?php } ?>
                                </li>
                                <li>
                                    <div class="wp-dp-compare-box">
                                        <small class="label"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_listing_sub_category'); ?></small>
                                    </div>
                                    <?php foreach ( $meta_post_ids_arr as $listing_id ) { ?>
                                        <div class="wp-dp-compare-box dev-dp-<?php echo absint($listing_id) ?>">
                                            <span>
                                                <?php
                                                $listing_category = get_post_meta($listing_id, 'wp_dp_listing_category', true);
                                                $listing_parent_category = isset($listing_category['parent']) ? $listing_category['parent'] : '';
                                                $listing_sub_categories = isset($listing_category[$listing_parent_category]) ? $listing_category[$listing_parent_category] : '';
                                                if ( $listing_sub_categories !== '' ) {
                                                    $term = get_term_by('slug', $listing_sub_categories, 'listing-category');
                                                    echo wp_dp_allow_special_char(isset($term->name) ? $term->name : '<i class="icon icon-cross"></i>');
                                                } else {
                                                    echo '<i class="icon icon-cross"></i>';
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    <?php } ?>
                                </li>
                                <li>
                                    <div class="wp-dp-compare-box">
                                        <small class="label"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_featured'); ?></small>
                                    </div>
                                    <?php foreach ( $meta_post_ids_arr as $listing_id ) { ?>
                                        <div class="wp-dp-compare-box dev-dp-<?php echo absint($listing_id) ?>">
                                            <span>
                                                <?php
                                                $is_urgent = wp_dp_check_promotion_status($listing_id, 'urgent');
                                                if ( $is_urgent == 'on' ) {
                                                    echo '<i class="icon icon-check"></i>';
                                                } else {
                                                    echo '<i class="icon icon-cross"></i>';
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    <?php } ?>
                                </li>
                                <li>
                                    <div class="wp-dp-compare-box">
                                        <small class="label"><?php echo wp_dp_plugin_text_srt('wp_dp_list_meta_top_category'); ?></small>
                                    </div>
                                    <?php foreach ( $meta_post_ids_arr as $listing_id ) { ?>
                                        <div class="wp-dp-compare-box dev-dp-<?php echo absint($listing_id) ?>">
                                            <span>
                                                <?php
                                                $is_top_cat = wp_dp_check_promotion_status( $listing_id, 'top-categories');
                                                if ( $is_top_cat == 'on' ) {
                                                    echo '<i class="icon icon-check"></i>';
                                                } else {
                                                    echo '<i class="icon icon-cross"></i>';
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    <?php } ?>
                                </li>
                                <?php
                                // load listing type record by slug
                                $listing_type_fields_arr = array();
                                $wp_dp_listing_type_cus_fields = get_post_meta($listing_type, "wp_dp_listing_type_cus_fields", true);
                                if ( is_array($wp_dp_listing_type_cus_fields) && sizeof($wp_dp_listing_type_cus_fields) > 0 ) {
                                    foreach ( $wp_dp_listing_type_cus_fields as $cus_field ) {
                                        $wp_dp_unique_id = rand(1111111, 9999999);
                                        $wp_dp_type = isset($cus_field['type']) ? $cus_field['type'] : '';
                                        if ( $wp_dp_type != 'section' ) {
                                            $single_listing_arr = array();
                                            $wp_dp_cus_title = isset($cus_field['label']) ? $cus_field['label'] : '';
                                            $wp_dp_meta_key_field = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
                                            foreach ( $meta_post_ids_arr as $listing_id ) {
                                                $wp_dp_label_value = get_post_meta($listing_id, "$wp_dp_meta_key_field", true);
                                                $single_listing_arr[] = array(
                                                    'type' => $wp_dp_type,
                                                    'id' => $listing_id,
                                                    'key' => $wp_dp_meta_key_field,
                                                    'label' => $wp_dp_cus_title,
                                                    'value' => $wp_dp_label_value,
                                                );
                                            }
                                            $listing_type_fields_arr[$wp_dp_unique_id] = $single_listing_arr;
                                        }
                                    }
                                }
                                if ( is_array($listing_type_fields_arr) && sizeof($listing_type_fields_arr) > 0 ) {
                                    foreach ( $listing_type_fields_arr as $li_row ) {
                                        ?>
                                        <li>
                                            <?php
                                            if ( is_array($li_row) && sizeof($li_row) > 0 ) {


                                                $li_row_counter = 0;
                                                foreach ( $li_row as $li_ro ) {
                                                    $type = isset($li_ro['type']) ? $li_ro['type'] : '';
                                                    if ( $li_row_counter == 0 ) {
                                                        ?>
                                                        <div class="wp-dp-compare-box">
                                                            <small class="label"><?php echo isset($li_ro['label']) ? esc_html($li_ro['label']) : '' ?></small>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="wp-dp-compare-box dev-dp-<?php echo absint(isset($li_ro['id']) ? $li_ro['id'] : '') ?>">
                                                        <span>&nbsp;
                                                            <?php
                                                            $row_val = isset($li_ro['value']) ? $li_ro['value'] : '';
                                                            if ( is_array($row_val) && ! empty($row_val) ) {
                                                                $row_val = implode(', ', $row_val);
                                                                if ( $row_val != '' ) {
                                                                    echo esc_html(ucwords(str_replace("-", " ", $row_val)));
                                                                } else {
                                                                    echo '<i class="icon icon-cross"></i>';
                                                                }
                                                            } else {
                                                                if ( $row_val != '' ) {
                                                                    if ( $type == 'date' ) {
                                                                        echo date_i18n(get_option('date_format'), $row_val);
                                                                    } else {
                                                                        echo esc_html(ucwords(str_replace("-", " ", $row_val)));
                                                                    }
                                                                } else {
                                                                    echo '<i class="icon icon-cross"></i>';
                                                                }
                                                            }
                                                            ?>
                                                        </span>
                                                    </div>
                                                    <?php
                                                    $li_row_counter ++;
                                                }
                                            }
                                            ?>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                                <li>
                                    <div class="wp-dp-compare-box">
                                        <small class="label"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_features'); ?></small>
                                    </div>
                                    <?php foreach ( $meta_post_ids_arr as $listing_id ) { ?>
                                        <div class="wp-dp-compare-box dev-dp-<?php echo absint($listing_id) ?>">
                                            <?php $features_list = get_post_meta($listing_id, 'wp_dp_listing_feature_list', true); //print_r($features_list); ?>
                                            <span>
                                                <?php
                                                if ( isset($features_list) && ! empty($features_list) ) {
                                                    $comma = '';
                                                    foreach ( $features_list as $feature_data ) {
                                                        $feature_exploded = explode("_icon", $feature_data);
                                                        $features_data_name = isset($feature_exploded[0]) ? $feature_exploded[0] : '';
                                                        echo esc_html($comma . ucwords(str_replace("-", " ", $features_data_name)));
                                                        $comma = ', ';
                                                    }
                                                }
                                                ?>

                                            </span>
                                        </div>
                                    <?php } ?>
                                </li>
                            </ul>
                            <?php
                        } else {
                            ?>
                            <ul class="wp-dp-compare-list">
                                <li>
                                    <div class="compare-text-div">
                                        <?php
                                        $listing_url = '';
                                        if ( $wp_dp_search_result_page != '' && is_numeric($wp_dp_search_result_page) ) {
                                            $listing_url = '<a href="' . wp_dp_wpml_lang_page_permalink($wp_dp_search_result_page, 'page') . '">' . ' ' . wp_dp_plugin_text_srt('wp_dp_compare_click_here') . '</a>';
                                        }
                                        echo sprintf(wp_dp_plugin_text_srt('wp_dp_compare_compare_list_is_empty'), $listing_url);
                                        ?>
                                    </div>
                                </li>
                            </ul>
                            <?php
                        }
                        ?>               
                    </div>
                </div>
            </div>

            <?php
            $eventpost_data = ob_get_clean();
            return do_shortcode($eventpost_data);
        }

        public function wp_dp_cs_var_page_builder_wp_dp_compare_listing($die = 0) {
            global $post, $wp_dp_html_fields, $wp_dp_cs_node, $wp_dp_form_fields;
            if ( function_exists('wp_dp_cs_shortcode_names') ) {
                $shortcode_element = '';
                $filter_element = 'filterdrag';
                $shortcode_view = '';
                $wp_dp_cs_output = array();
                $wp_dp_cs_PREFIX = 'wp_dp_compare_listing';

                $wp_dp_cs_counter = isset($_POST['counter']) ? $_POST['counter'] : '';
                if ( isset($_POST['action']) && ! isset($_POST['shortcode_element_id']) ) {
                    $wp_dp_cs_POSTID = '';
                    $shortcode_element_id = '';
                } else {
                    $wp_dp_cs_POSTID = isset($_POST['POSTID']) ? $_POST['POSTID'] : '';
                    $shortcode_element_id = isset($_POST['shortcode_element_id']) ? $_POST['shortcode_element_id'] : '';
                    $shortcode_str = stripslashes($shortcode_element_id);
                    $parseObject = new ShortcodeParse();
                    $wp_dp_cs_output = $parseObject->wp_dp_cs_shortcodes($wp_dp_cs_output, $shortcode_str, true, $wp_dp_cs_PREFIX);
                }
                $defaults = array(
                    'listing_compare_title' => '',
                    'compare_subtitle' => '',
                    'compare_title_align' => '',
                    'wp_dp_compare_element_title_color' => '',
                    'wp_dp_compare_element_subtitle_color' => '',
                    'wp_dp_compare_seperator_style' => '',
                );
                if ( isset($wp_dp_cs_output['0']['atts']) ) {
                    $atts = $wp_dp_cs_output['0']['atts'];
                } else {
                    $atts = array();
                }
                if ( isset($wp_dp_cs_output['0']['content']) ) {
                    $wp_dp_compare_listing_column_text = $wp_dp_cs_output['0']['content'];
                } else {
                    $wp_dp_compare_listing_column_text = '';
                }
                $wp_dp_compare_listing_element_size = '100';
                foreach ( $defaults as $key => $values ) {
                    if ( isset($atts[$key]) ) {
                        $$key = $atts[$key];
                    } else {
                        $$key = $values;
                    }
                }
                $name = 'wp_dp_cs_var_page_builder_wp_dp_compare_listing';
                $coloumn_class = 'column_' . $wp_dp_compare_listing_element_size;
                if ( isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode' ) {
                    $shortcode_element = 'shortcode_element_class';
                    $shortcode_view = 'cs-pbwp-shortcode';
                    $filter_element = 'ajax-drag';
                    $coloumn_class = '';
                }
                ?>
                <div id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class); ?>
                     <?php echo esc_attr($shortcode_view); ?>" item="wp_dp_compare_listing" data="<?php echo wp_dp_cs_element_size_data_array_index($wp_dp_compare_listing_element_size) ?>" >
                         <?php wp_dp_cs_element_setting($name, $wp_dp_cs_counter, $wp_dp_compare_listing_element_size) ?>
                    <div class="cs-wrapp-class-<?php echo intval($wp_dp_cs_counter) ?>
                         <?php echo esc_attr($shortcode_element); ?>" id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>" data-shortcode-template="[wp_dp_compare_listing {{attributes}}]{{content}}[/wp_dp_compare_listing]" style="display: none;">
                        <div class="cs-heading-area" data-counter="<?php echo esc_attr($wp_dp_cs_counter) ?>">
                            <h5><?php echo wp_dp_plugin_text_srt('wp_dp_compare_shortcode_heading'); ?></h5>
                            <a href="javascript:wp_dp_cs_frame_removeoverlay('<?php echo esc_js($name . $wp_dp_cs_counter) ?>','<?php echo esc_js($filter_element); ?>')" class="cs-btnclose">
                                <i class="icon-cross"></i>
                            </a>
                        </div>
                        <div class="cs-pbwp-content">
                            <div class="cs-wrapp-clone cs-shortcode-wrapp">
                                <?php
                                $wp_dp_opt_array = array(
                                    'name' => wp_dp_plugin_text_srt('wp_dp_element_title'),
                                    'desc' => '',
                                    'hint_text' => wp_dp_plugin_text_srt('wp_dp_element_title_hint'),
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => $listing_compare_title,
                                        'id' => 'listing_compare_title',
                                        'cust_name' => 'listing_compare_title[]',
                                        'return' => true,
                                    ),
                                );

                                $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);


                                /*
                                 * 
                                 */

                                $wp_dp_opt_array = array(
                                    'name' => wp_dp_plugin_text_srt('wp_dp_element_sub_title'),
                                    'desc' => '',
                                    'label_desc' => '',
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => esc_attr($compare_subtitle),
                                        'cust_name' => 'compare_subtitle[]',
                                        'return' => true,
                                    ),
                                );
                                $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

                                $wp_dp_opt_array = array(
                                    'name' => wp_dp_plugin_text_srt('wp_dp_title_align'),
                                    'desc' => '',
                                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_title_align_hint'),
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => esc_attr($compare_title_align),
                                        'classes' => 'chosen-select-no-single',
                                        'cust_name' => 'compare_title_align[]',
                                        'return' => true,
                                        'options' => array(
                                            'align-left' => wp_dp_plugin_text_srt('wp_dp_align_left'),
                                            'align-right' => wp_dp_plugin_text_srt('wp_dp_align_right'),
                                            'align-center' => wp_dp_plugin_text_srt('wp_dp_align_center'),
                                        ),
                                    ),
                                );

                                $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);


                                $wp_dp_opt_array = array(
                                    'name' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_color'),
                                    'desc' => '',
                                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_color_hint'),
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => $wp_dp_compare_element_title_color,
                                        'cust_name' => 'wp_dp_compare_element_title_color[]',
                                        'classes' => 'bg_color',
                                        'return' => true,
                                    ),
                                );
                                $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

                                $wp_dp_opt_array = array(
                                    'name' => wp_dp_plugin_text_srt('wp_dp_plugin_element_subtitle_color'),
                                    'desc' => '',
                                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_element_subtitle_color_hint'),
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => $wp_dp_compare_element_subtitle_color,
                                        'cust_name' => 'wp_dp_compare_element_subtitle_color[]',
                                        'classes' => 'bg_color',
                                        'return' => true,
                                    ),
                                );
                                $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

                                $wp_dp_opt_array = array(
                                    'name' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_seperator'),
                                    'desc' => '',
                                    'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_seperator_hint'),
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => esc_attr($wp_dp_compare_seperator_style),
                                        'classes' => 'chosen-select-no-single',
                                        'cust_name' => 'wp_dp_compare_seperator_style[]',
                                        'return' => true,
                                        'options' => array(
                                            '' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_seperator_style_none'),
                                            'classic' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_seperator_style_classic'),
                                            'zigzag' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_seperator_style_zigzag'),
                                        ),
                                    ),
                                );

                                $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                                /*
                                 * 
                                 */
                                ?>
                            </div>
                            <?php if ( isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode' ) { ?>
                                <ul class="form-elements insert-bg">
                                    <li class="to-field">
                                        <a class="insert-btn cs-main-btn" onclick="javascript:wp_dp_cs_shortcode_insert_editor('<?php echo str_replace('wp_dp_cs_var_page_builder_', '', $name); ?>', '<?php echo esc_js($name . $wp_dp_cs_counter) ?>', '<?php echo esc_js($filter_element); ?>')" ><?php echo wp_dp_plugin_text_srt('wp_dp_insert'); ?></a>
                                    </li>
                                </ul>
                                <div id="results-shortocde"></div>
                            <?php } else { ?>

                                <?php
                                $wp_dp_cs_opt_array = array(
                                    'std' => 'wp_dp_compare_listing',
                                    'id' => '',
                                    'before' => '',
                                    'after' => '',
                                    'classes' => '',
                                    'extra_atr' => '',
                                    'cust_id' => 'wp_dp_cs_orderby' . $wp_dp_cs_counter,
                                    'cust_name' => 'wp_dp_cs_orderby[]',
                                    'required' => false
                                );
                                $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_cs_opt_array);

                                $wp_dp_cs_opt_array = array(
                                    'name' => '',
                                    'desc' => '',
                                    'hint_text' => '',
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => wp_dp_plugin_text_srt('wp_dp_save'),
                                        'cust_id' => 'wp_dp_compare_listing_save',
                                        'cust_type' => 'button',
                                        'extra_atr' => 'onclick="javascript:_removerlay(jQuery(this))"',
                                        'classes' => 'cs-wp_dp_cs-admin-btn',
                                        'cust_name' => 'wp_dp_compare_listing_save',
                                        'return' => true,
                                    ),
                                );

                                $wp_dp_html_fields->wp_dp_text_field($wp_dp_cs_opt_array);
                            }
                            ?>
                        </div>
                    </div>
                    <script type="text/javascript">
                        popup_over();
                    </script>
                </div>

                <?php
            }
            if ( $die <> 1 ) {
                die();
            }
        }

        public function wp_dp_cs_save_page_builder_data_wp_dp_compare_listing_callback($args) {

            $data = $args['data'];
            $counters = $args['counters'];
            $widget_type = $args['widget_type'];
            $column = $args['column'];
            $shortcode_data = '';
            if ( $widget_type == "wp_dp_compare_listing" || $widget_type == "cs_wp_dp_compare_listing" ) {
                $wp_dp_cs_bareber_wp_dp_compare_listing = '';

                $page_element_size = $data['wp_dp_compare_listing_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_compare_listing']];
                $current_element_size = $data['wp_dp_compare_listing_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_compare_listing']];

                if ( isset($data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']]) && $data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']] == 'shortcode' ) {
                    $shortcode_str = stripslashes(( $data['shortcode']['wp_dp_compare_listing'][$counters['wp_dp_cs_shortcode_counter_wp_dp_compare_listing']]));

                    $element_settings = 'wp_dp_compare_listing_element_size="' . $current_element_size . '"';
                    $reg = '/wp_dp_compare_listing_element_size="(\d+)"/s';
                    $shortcode_str = preg_replace($reg, $element_settings, $shortcode_str);
                    $shortcode_data = $shortcode_str;

                    $wp_dp_cs_bareber_wp_dp_compare_listing ++;
                } else {
                    $element_settings = 'wp_dp_compare_listing_element_size="' . htmlspecialchars($data['wp_dp_compare_listing_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_compare_listing']]) . '"';
                    $wp_dp_cs_bareber_wp_dp_compare_listing = '[wp_dp_compare_listing ' . $element_settings . ' ';
                    if ( isset($data['listing_compare_title'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']]) && $data['listing_compare_title'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']] != '' ) {
                        $wp_dp_cs_bareber_wp_dp_compare_listing .= 'listing_compare_title="' . htmlspecialchars($data['listing_compare_title'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']], ENT_QUOTES) . '" ';
                    }
                    if ( isset($data['compare_subtitle'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']]) && $data['compare_subtitle'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']] != '' ) {
                        $wp_dp_cs_bareber_wp_dp_compare_listing .= 'compare_subtitle="' . htmlspecialchars($data['compare_subtitle'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']], ENT_QUOTES) . '" ';
                    }
                    if ( isset($data['compare_title_align'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']]) && $data['compare_title_align'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']] != '' ) {
                        $wp_dp_cs_bareber_wp_dp_compare_listing .= 'compare_title_align="' . htmlspecialchars($data['compare_title_align'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']], ENT_QUOTES) . '" ';
                    }
                    if ( isset($data['wp_dp_compare_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']]) && $data['wp_dp_compare_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']] != '' ) {
                        $wp_dp_cs_bareber_wp_dp_compare_listing .= 'wp_dp_compare_element_title_color="' . htmlspecialchars($data['wp_dp_compare_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']], ENT_QUOTES) . '" ';
                    }
                    if ( isset($data['wp_dp_compare_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']]) && $data['wp_dp_compare_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']] != '' ) {
                        $wp_dp_cs_bareber_wp_dp_compare_listing .= 'wp_dp_compare_element_subtitle_color="' . htmlspecialchars($data['wp_dp_compare_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']], ENT_QUOTES) . '" ';
                    }
                    if ( isset($data['wp_dp_compare_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']]) && $data['wp_dp_compare_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']] != '' ) {
                        $wp_dp_cs_bareber_wp_dp_compare_listing .= 'wp_dp_compare_seperator_style="' . htmlspecialchars($data['wp_dp_compare_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']], ENT_QUOTES) . '" ';
                    }
                    $wp_dp_cs_bareber_wp_dp_compare_listing .= ']';
                    if ( isset($data['wp_dp_compare_listing_column_text'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']]) && $data['wp_dp_compare_listing_column_text'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']] != '' ) {
                        $wp_dp_cs_bareber_wp_dp_compare_listing .= htmlspecialchars($data['wp_dp_compare_listing_column_text'][$counters['wp_dp_cs_counter_wp_dp_compare_listing']], ENT_QUOTES) . ' ';
                    }
                    $wp_dp_cs_bareber_wp_dp_compare_listing .= '[/wp_dp_compare_listing]';

                    $shortcode_data .= $wp_dp_cs_bareber_wp_dp_compare_listing;
                    $counters['wp_dp_cs_counter_wp_dp_compare_listing'] ++;
                }
                $counters['wp_dp_cs_global_counter_wp_dp_compare_listing'] ++;
            }
            return array(
                'data' => $data,
                'counters' => $counters,
                'widget_type' => $widget_type,
                'column' => $shortcode_data,
            );
        }

        public function wp_dp_cs_load_shortcode_counters_wp_dp_compare_listing_callback($counters) {
            $counters['wp_dp_cs_global_counter_wp_dp_compare_listing'] = 0;
            $counters['wp_dp_cs_shortcode_counter_wp_dp_compare_listing'] = 0;
            $counters['wp_dp_cs_counter_wp_dp_compare_listing'] = 0;
            return $counters;
        }

        function wp_dp_cs_element_list_populate_wp_dp_compare_listing_callback($element_list) {
            $element_list['wp_dp_compare_listing'] = wp_dp_plugin_text_srt('wp_dp_compare_shortcode_heading');
            return $element_list;
        }

        public function wp_dp_cs_shortcode_names_list_populate_wp_dp_compare_listing_callback($shortcode_array) {
            $shortcode_array['wp_dp_compare_listing'] = array(
                'title' => wp_dp_plugin_text_srt('wp_dp_compare_shortcode_heading'),
                'name' => 'wp_dp_compare_listing',
                'icon' => 'icon-compare',
                'categories' => 'typography',
            );

            return $shortcode_array;
        }

    }

    new wp_dp_compare_listings();
}
