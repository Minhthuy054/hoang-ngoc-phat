<?php
/**
 * File Type: Search Fields
 */
if ( ! class_exists('Wp_dp_Split_Map_Search_Fields') ) {

    class Wp_dp_Split_Map_Search_Fields {

        public $listing_short_counter;

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_dp_listing_type_fields_split_map', array( $this, 'wp_dp_listing_type_fields_split_map_callback' ), 10, 2);
            add_action('wp_dp_listing_type_split_map_features', array( $this, 'wp_dp_listing_type_split_map_features' ), 10, 3);
            add_action('wp_ajax_wp_dp_listing_type_split_map_search_fields', array( $this, 'wp_dp_listing_type_split_map_search_fields_callback' ));
            add_action('wp_ajax_nopriv_wp_dp_listing_type_split_map_search_fields', array( $this, 'wp_dp_listing_type_split_map_search_fields_callback' ));
            add_action('wp_ajax_wp_dp_listing_type_split_map_cate_fields', array( $this, 'wp_dp_listing_type_split_map_cate_fields_callback' ));
            add_action('wp_ajax_nopriv_wp_dp_listing_type_split_map_cate_fields', array( $this, 'wp_dp_listing_type_split_map_cate_fields_callback' ));
        }

        public function wp_dp_listing_type_split_map_features($list_type_slug = '', $listing_short_counter = '', $args_count = array()) {
            global $wp_dp_form_fields_frontend;
            $listing_type_id = $this->wp_dp_listing_type_id_by_slug($list_type_slug);
            $listing_type_features = get_post_meta($listing_type_id, 'feature_lables', true);
            $feature_icons = get_post_meta($listing_type_id, 'wp_dp_feature_icon', true);

            if ( is_array($listing_type_features) && sizeof($listing_type_features) > 0 ) {
                ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 features-list">
                    <strong class="advance-trigger"><?php echo wp_dp_plugin_text_srt('wp_dp_search_fields_other_features'); ?></strong>
					<div class="clearfix"></div>
					<div class="features-field-expand">
                        <ul id="search-features-list" class="search-features-list">
                            <?php
                            $features = isset($_REQUEST['features']) && $_REQUEST['features'] != '' ? $_REQUEST['features'] : '';
                            $checked_features = explode(',', $features);
                            $feature_counter = 1;
                            $html = '';
                            foreach ( $listing_type_features as $feat_key => $feature ) {
                                if ( isset($feature) && ! empty($feature) ) {


                                    $feature_name = isset($feature) ? $feature : '';
                                    $feature_icon = isset($feature_icons[$feat_key]) ? $feature_icons[$feat_key] : '';
                                    $count_feature_listings = $this->listing_search_features_listings($list_type_slug, $feature_name, $args_count);

                                    $html .= '<li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                        <div class="checkbox">';
                                    $wp_dp_opt_array = array(
                                        'std' => esc_attr($feature_name),
                                        'cust_id' => 'check-' . $feature_counter . '',
                                        'cust_name' => '',
                                        'return' => true,
                                        'classes' => 'search-feature-' . $listing_short_counter . '',
                                        'cust_type' => 'checkbox',
                                        'prefix_on' => false,
                                    );
                                    if ( isset($checked_features) && ! empty($checked_features) && in_array($feature_name, $checked_features) ) {
                                        $wp_dp_opt_array['extra_atr'] = 'checked="checked"';
                                    }
                                    $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                    $html .= '    <label for="check-' . $feature_counter . '">' . $feature_name . '</label>
                                                        </div>
                                                </li>'."\n";
                                    $feature_counter ++;
                                }
                            }
                            $html .= '<li class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="display:none;">';
                            $html .= $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                                    array(
                                        'return' => true,
                                        'cust_name' => 'features',
                                        'cust_id' => 'search-listing-features-' . $listing_short_counter . '',
                                        'std' => $features,
                                    )
                            );
                            $html .= '</li>'."\n";
                            echo wp_dp_allow_special_char($html);
                            ?>
                            <script type="text/javascript">
                                jQuery(document).ready(function () {
                                    'use strict'
                                    var $checkboxes = jQuery("input[type=checkbox].search-feature-<?php echo esc_html($listing_short_counter); ?>");
                                    var features = $checkboxes.filter(':checked').map(function () {
                                        return this.value;
                                    }).get().join(',');
                                    jQuery('#search-listing-features-<?php echo esc_html($listing_short_counter); ?>').val(features);
                                });
                                var $checkboxes = jQuery("input[type=checkbox].search-feature-<?php echo esc_html($listing_short_counter); ?>");
                                $checkboxes.on('change', function () {
                                    var features = $checkboxes.filter(':checked').map(function () {
                                        return this.value;
                                    }).get().join(',');
                                    jQuery('#search-listing-features-<?php echo esc_html($listing_short_counter); ?>').val(features);
                                    wp_dp_split_map_change_cords('<?php echo wp_dp_allow_special_char($this->listing_short_counter); ?>', 'false');
                                });
                            </script>
                        </ul>
                    </div>
                </div>
                <?php
            }
        }

        function listing_search_features_listings($listing_type_slug = '', $feature_name = '', $args = array()) {

            if ( $listing_type_slug != '' && $feature_name != '' ) {
                $args['meta_query'][] = array(
                    'key' => 'wp_dp_listing_feature_list',
                    'value' => $feature_name,
                    'compare' => 'LIKE',
                    'type' => 'CHAR'
                );

                $feature_query = new WP_Query($args);
                return $feature_query->found_posts;
                wp_reset_postdata();
            }
        }

        public function wp_dp_listing_type_split_map_search_fields_callback() {
            global $wp_dp_form_fields_frontend;

            $listing_short_counter = wp_dp_get_input('listing_short_counter', 0);
            $listing_type_slug = wp_dp_get_input('listing_type_slug', NULL, 'STRING');
            $json = array();
            $json['type'] = "error";
            if ( $listing_type_slug != '' ) {
                ob_start();
                $args = array(
                    'name' => $listing_type_slug,
                    'post_type' => 'listing-type',
                    'post_status' => 'publish',
                    'numberposts' => 1,
                );
                $my_posts = get_posts($args);
                if ( $my_posts ) {
                    $listing_type_id = $my_posts[0]->ID;
                }
                $wp_dp_listing_type_price = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
                if ( $wp_dp_listing_type_price == 'on' && $listing_type_id != '' ) {
                    $wp_dp_price_minimum_options = get_post_meta($listing_type_id, 'wp_dp_price_minimum_options', true);
                    $wp_dp_price_minimum_options = ( ! empty($wp_dp_price_minimum_options) ) ? $wp_dp_price_minimum_options : 1;
                    $wp_dp_price_max_options = get_post_meta($listing_type_id, 'wp_dp_price_max_options', true);
                    $wp_dp_price_max_options = ( ! empty($wp_dp_price_max_options) ) ? $wp_dp_price_max_options : 50; //50000;
                    $wp_dp_price_interval = get_post_meta($listing_type_id, 'wp_dp_price_interval', true);
                    $wp_dp_price_interval = ( ! empty($wp_dp_price_interval) ) ? $wp_dp_price_interval : 50;
                    $wp_dp_price_interval = (int) $wp_dp_price_interval;
                    $price_counter = $wp_dp_price_minimum_options;
                    $price_min = array();
                    $price_max = array();
                    // gettting all values of price
                    $price_min = wp_dp_listing_price_options($wp_dp_price_minimum_options, $wp_dp_price_max_options, $wp_dp_price_interval, wp_dp_plugin_text_srt('wp_dp_search_filter_min_price'));
                    $price_max = wp_dp_listing_price_options($wp_dp_price_minimum_options, $wp_dp_price_max_options, $wp_dp_price_interval, wp_dp_plugin_text_srt('wp_dp_search_filter_max_price'));
                    ?>
                    <div class="field-holder select-dropdown">
                        <div class="wp-dp-min-max-price">
                            <div class="select-categories"> 
                                <ul>
                                    <li>
                                        <?php
                                        $price_min_checked = ( isset($_REQUEST['price_minimum']) && $_REQUEST['price_minimum'] ) ? $_REQUEST['price_minimum'] : '';
                                        $wp_dp_form_fields_frontend->wp_dp_form_select_render(
                                                array(
                                                    'simple' => true,
                                                    'cust_name' => 'price_minimum',
                                                    'std' => $price_min_checked,
                                                    'classes' => 'chosen-select-no-single',
                                                    'options' => $price_min,
                                                    'extra_atr' => 'onchange="wp_dp_split_map_change_cords(\'' . $listing_short_counter . '\', \'false\');"',
                                                )
                                        );
                                        ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="select-categories"> 

                                <ul>
                                    <li>
                                        <?php
                                        $price_max_checked = ( isset($_REQUEST['price_maximum']) && $_REQUEST['price_maximum'] ) ? $_REQUEST['price_maximum'] : '';
                                        $wp_dp_form_fields_frontend->wp_dp_form_select_render(
                                                array(
                                                    'simple' => true,
                                                    'cust_name' => 'price_maximum',
                                                    'std' => $price_max_checked,
                                                    'classes' => 'chosen-select-no-single',
                                                    'options' => $price_max,
                                                    'extra_atr' => 'onchange="wp_dp_split_map_change_cords(\'' . $listing_short_counter . '\', \'false\');"',
                                                )
                                        );
                                        ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php
                $this->wp_dp_listing_type_fields_split_map_callback($listing_type_slug, $listing_short_counter);
                $this->wp_dp_listing_type_split_map_features($listing_type_slug, $listing_short_counter);
                ?>
                <script type="text/javascript">
                    chosen_selectionbox();
                </script>
                <?php
                $content = ob_get_clean();
                $json['type'] = "success";
                $json['html'] = $content;
            }
            echo json_encode($json);
            wp_die();
        }

        public function wp_dp_listing_type_split_map_cate_fields() {
            global $wp_dp_form_fields_frontend;
            $listing_short_counter = wp_dp_get_input('listing_short_counter', 0);
            $listing_type_slug = wp_dp_get_input('listing_type_slug', NULL, 'STRING');

            
            $wp_dp_search_label_color = wp_dp_get_input('color', NULL, 'STRING');
            $wp_dp_search_label_color = isset($wp_dp_search_label_color) ? $wp_dp_search_label_color : '';


            if ( isset($wp_dp_search_label_color) && $wp_dp_search_label_color != '' && $wp_dp_search_label_color != 'none' ) {
                $label_style_colr = 'style="color:' . $wp_dp_search_label_color . ' !important"';
            }

            $json = array();
            $json['type'] = "error";
            if ( $listing_type_slug != '' ) {
                ob_start();

                $listing_cats_array = $this->wp_dp_listing_type_categories_options($listing_type_slug);

                if ( ! empty($listing_cats_array) ) {
                    $wp_dp_opt_array = array(
                        'std' => (isset($_REQUEST['listing_category']) && $_REQUEST['listing_category'] != '') ? $_REQUEST['listing_category'] : '',
                        'id' => 'wp_dp_listing_category',
                        'classes' => 'chosen-select',
                        'cust_name' => 'listing_category',
                        'options' => $listing_cats_array,
                    );
                    if ( count($listing_cats_array) <= 6 ) {
                        $wp_dp_opt_array['classes'] = 'chosen-select-no-single';
                    }
                    $wp_dp_form_fields_frontend->wp_dp_form_select_render($wp_dp_opt_array);
                    ?>
                <?php } ?>

                <?php
                ?>
                <script type="text/javascript">
                    chosen_selectionbox();
                </script>
                <?php
                $content = ob_get_clean();
                $json['type'] = "success";
                $json['html'] = $content;
            }
            echo json_encode($json);
            wp_die();
        }

        public function wp_dp_listing_type_fields_split_map_callback($list_type_slug = '', $listing_short_counter = '') {
            global $wp_dp_form_fields;
            $advanced_filter = false;
            $this->listing_short_counter = $listing_short_counter;
            if ( $list_type_slug != '' ) {
                $listing_type_id = $this->wp_dp_listing_type_id_by_slug($list_type_slug);
                if ( $listing_type_id != 0 ) {
                    $listing_type_fields = get_post_meta($listing_type_id, 'wp_dp_listing_type_cus_fields', true);
                    if ( isset($listing_type_fields) && is_array($listing_type_fields) && ! empty($listing_type_fields) ) {
                        foreach ( $listing_type_fields as $listing_type_field ) {
                            $field_type = isset($listing_type_field['type']) ? $listing_type_field['type'] : '';
                            $field_enable_srch = isset($listing_type_field['enable_srch']) ? $listing_type_field['enable_srch'] : '';
                            if ( $field_enable_srch == 'yes' || $field_type == 'section' || $field_type == 'divider' ) {
                                $field_fontawsome_icon = isset($listing_type_field['fontawsome_icon']) ? $listing_type_field['fontawsome_icon'] : '';
                                $cus_field_icon_group_arr = isset($listing_type_field['fontawsome_icon_group']) ? $listing_type_field['fontawsome_icon_group'] : 'default';
                                if ( isset($field_fontawsome_icon) && $field_fontawsome_icon <> '' ) {
                                    wp_enqueue_style('cs_icons_data_css_' . $cus_field_icon_group_arr);
                                }
                                if ( $field_type == 'date' ) {
                                    $this->wp_dp_date_field($listing_type_field);
                                } else if ( $field_type == 'range' ) {
                                    $this->wp_dp_range_field($listing_type_field);
                                } else if ( $field_type == 'section' ) {
                                    echo force_balance_tags($this->wp_dp_section_field($listing_type_field));
                                } else if ( $field_type == 'divider' ) {
                                    echo force_balance_tags($this->wp_dp_divider_field($listing_type_field));
                                } else {
                                    echo force_balance_tags($this->wp_dp_common_field($listing_type_field));
                                }
                                $advanced_filter = true;
                            }
                        }
                    }
                }
            }
        }

        public function wp_dp_section_field($custom_field = array()) {
            $field_type = isset($custom_field['type']) ? $custom_field['type'] : '';
            $field_label = isset($custom_field['label']) ? $custom_field['label'] : '';
            $field_fontawsome_icon = isset($custom_field['fontawsome_icon']) ? $custom_field['fontawsome_icon'] : '';

            $output = '';
            if ( $field_label != '' ) {
                $output .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 features-list">';
                $output .= '<div class="filter-section-title">';
                $output .= '<strong class="section-title">' . $field_label . '</strong>';
                $output .= '</div>';
                $output .= '</div>';
            }
            $output .= force_balance_tags($this->wp_dp_divider_field($custom_field));
            return $output;
        }

        public function wp_dp_divider_field($custom_field = array()) {
            $enable_divider = isset($custom_field['divider']) ? $custom_field['divider'] : '';

            $output = '';
            if ( $enable_divider == 'yes' ) {
                $output .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 features-list">';
                $output .= '<div class="filter-divider">';
                $output .= '</div>';
                $output .= '</div>';
            }
            return $output;
        }

        public function wp_dp_listing_type_id_by_slug($list_type_slug = '') {
            if ( $post = get_page_by_path($list_type_slug, OBJECT, 'listing-type') ) {
                $listing_type_id = $post->ID;
            } else {
                $listing_type_id = 0;
            }
            return wp_dp_wpml_lang_page_id($listing_type_id, 'listing-type');
        }

        public function wp_dp_common_field($custom_field = '') {
            global $wp_dp_form_fields;
            $field_counter = rand(12345, 54321);
            $field_type = isset($custom_field['type']) ? $custom_field['type'] : '';
            $field_label = isset($custom_field['label']) ? $custom_field['label'] : '';
            $field_meta_key = isset($custom_field['meta_key']) ? $custom_field['meta_key'] : '';
            $field_placeholder = isset($custom_field['placeholder']) ? $custom_field['placeholder'] : '';
            $field_default_value = isset($custom_field['default_value']) ? $custom_field['default_value'] : '';
            $field_size = isset($custom_field['field_size']) ? $custom_field['field_size'] : '';
            $field_fontawsome_icon = isset($custom_field['fontawsome_icon']) ? $custom_field['fontawsome_icon'] : '';
            $field_required = isset($custom_field['required']) ? $custom_field['required'] : '';

            $output = '';

            if ( $field_meta_key != '' ) {

                // Field Options
                $wp_dp_opt_array = array();
                $wp_dp_opt_array['std'] = esc_attr($field_default_value);
                $wp_dp_opt_array['label'] = $field_label;
                $wp_dp_opt_array['cust_id'] = $field_meta_key;
                $wp_dp_opt_array['cust_name'] = $field_meta_key;
                $wp_dp_opt_array['extra_atr'] = $this->wp_dp_field_placeholder($field_placeholder);
                $wp_dp_opt_array['classes'] = 'input-field';
                $wp_dp_opt_array['return'] = true;
                // End Field Options

                $field_size = $this->wp_dp_field_size($field_size);
                $field_icon = $this->wp_dp_field_icon($field_fontawsome_icon);
                $has_icon = '';
                if ( $field_icon != '' ) {
                    $has_icon = 'has-icon';
                }

                // Making Field with defined options
                if ( $field_type == 'text' || $field_type == 'url' || $field_type == 'email' ) {

                    $wp_dp_opt_array['std'] = isset($_REQUEST[$field_meta_key]) && $_REQUEST[$field_meta_key] != '' ? $_REQUEST[$field_meta_key] : '';
                    $wp_dp_opt_array['extra_atr'] = 'onchange="wp_dp_split_map_change_cords(\'' . $this->listing_short_counter . '\', \'false\');"';

                    $output .= '<div class="field-holder search-input ' . esc_html($has_icon) . '">';
                    $output .= '<label>';
                    $output .= $field_label;
                    $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                    $output .= '</label>';
                    $output .= '</div>' . "\n";
                } elseif ( $field_type == 'number' ) {
                    if ( isset($_REQUEST[$field_meta_key]) ) {
                        $field_default_value = $_REQUEST[$field_meta_key];
                    }

                    $wp_dp_form_fields->wp_dp_form_hidden_render(
                            array(
                                'simple' => true,
                                'cust_id' => "number-hidden1-" . $field_meta_key,
                                'cust_name' => wp_dp_allow_special_char($field_meta_key),
                                'std' => isset($field_default_value) && $field_default_value != '' ? $field_default_value : 0,
                            )
                    );
                    ?>
                    <div class="field-holder-number">
                        <div class="field-holder search-input select-categories <?php echo esc_html($has_icon); ?>">
                            <label><?php echo esc_html($field_label); ?></label>
                            <ul class="minimum-loading-list">
                                <li>
                                    <div class="spinner-btn input-group spinner">
                  
                                        <span class="list-text"><?php echo esc_html($field_placeholder); ?></span>
                                        <div class="input-group-btn-vertical">
                                            <button class="btn-decrement1<?php echo esc_html($field_meta_key); ?> caret-btn btn-default " type="button"><i class="icon-minus-circle"></i></button>
                                            <?php
                                            $wp_dp_form_fields->wp_dp_form_text_render(
                                                    array(
                                                        'cust_id' => 'wp_dp_' . $field_meta_key,
                                                        'cust_name' => '',
                                                        'classes' => "num-input1" . esc_html($field_meta_key) . " form-control",
                                                        'std' => isset($field_default_value) && $field_default_value != '' ? $field_default_value : 0,
                                                        'force_std' => true,
                                                    )
                                            );
                                            ?>
                                            <button class="btn-increment1<?php echo esc_html($field_meta_key); ?> caret-btn btn-default" type="button"><i class="icon-plus-circle"></i></button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <script type="text/javascript">
                                jQuery(document).ready(function ($) {
                                    $(".num-input1<?php echo esc_html($field_meta_key); ?>").keypress(function (e) {
                                        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                                            return false;
                                        }
                                    });
                                    $('.spinner .btn-increment1<?php echo esc_html($field_meta_key); ?>').on('click', function () {

                                        var field_value = $('.spinner .num-input1<?php echo esc_html($field_meta_key); ?>').val();

                                        field_value = field_value || 0;

                                        $('.spinner .num-input1<?php echo esc_html($field_meta_key); ?>').val(parseInt(field_value, 10) + 1);
                                        var selected_num = parseInt(field_value, 10) + 1;
                                        $('#number-hidden1-<?php echo esc_html($field_meta_key); ?>').val(selected_num);
                                        submit_left_sidebar_form();
                                    });
                                    $('.spinner .btn-decrement1<?php echo esc_html($field_meta_key); ?>').on('click', function () {
                                        var field_value = $('.spinner .num-input1<?php echo esc_html($field_meta_key); ?>').val();
                                        field_value = field_value || 0;
                                        var val = parseInt(field_value, 10);
                                        if (val < 1) {
                                            //return;
                                        }
                                        var minus_val = val - 1;
                                        if (minus_val < 0) {
                                            minus_val = 0;
                                        }
                                        $('.spinner .num-input1<?php echo esc_html($field_meta_key); ?>').val(minus_val);
                                        var selected_num = minus_val;
                                        $('#number-hidden1-<?php echo esc_html($field_meta_key); ?>').val(selected_num);
                                        submit_left_sidebar_form();
                                    });
                                    $(".num-input1<?php echo esc_html($field_meta_key); ?>").on('change keydown', function () {
                                        var field_value = $('.spinner .num-input1<?php echo esc_html($field_meta_key); ?>').val();
                                        field_value = field_value || 0;
                                        var selected_num = field_value;
                                        $('#number-hidden1-<?php echo esc_html($field_meta_key); ?>').val(selected_num);
                                        submit_left_sidebar_form();
                                    });

                                    var timer = 0;
                                    function submit_left_sidebar_form() {
                                        clearTimeout(timer);
                                        timer = setTimeout(function () {
                                            wp_dp_split_map_change_cords('<?php echo wp_dp_allow_special_char($this->listing_short_counter); ?>', 'false');
                                        }, 1000);
                                    }

                                });
                            </script>
                            <?php ?>
                        </div>
                    </div>
                    <?php
                } elseif ( $field_type == 'dropdown' ) {
                    $wp_dp_opt_array['std'] = isset($_REQUEST[$field_meta_key]) && $_REQUEST[$field_meta_key] != '' ? $_REQUEST[$field_meta_key] : '';
                    $output .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 features-list">';
                    $output .= '<strong class="advance-trigger">' . $field_label . '</strong>'."\n";
                    $output .= '<div class="features-field-expand">' . $this->wp_dp_dropdown_field($custom_field, $wp_dp_opt_array, $field_counter) . '</div>';
                    $output .= '</div>' . "\n";
                }
            }
            return $output;
        }

        public function wp_dp_dropdown_field($cus_field = '', $wp_dp_opt_array = '', $field_counter = '') {
            global $wp_dp_form_fields, $wp_dp_form_fields_frontend;
            $html = '';
            $query_str_var_name = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
            $number_option_flag = 1;
            $cut_field_flag = 0;
            $request_val = isset($_REQUEST[$query_str_var_name]) ? $_REQUEST[$query_str_var_name] : '';

            $request_val_arr = explode(",", $request_val);
            if ( $cus_field['multi'] == 'yes' ) { // if multi select then use hidden for submittion
                $html .= $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                        array(
                            'return' => true,
                            'simple' => true,
                            'cust_id' => "hidden_input-" . $query_str_var_name,
                            'cust_name' => $query_str_var_name,
                            'std' => $request_val,
                            'classes' => $query_str_var_name,
                        )
                );
                $html .='<script type="text/javascript">
					jQuery(function () {
						"use strict"
						var $checkboxes = jQuery("input[type=checkbox].' . esc_html($query_str_var_name) . '");
						$checkboxes.on("change", function () {
							var ids = $checkboxes.filter(":checked").map(function () {
								return this.value;
							}).get().join(",");
							jQuery("#hidden_input-' . esc_html($query_str_var_name) . '").val(ids);
							wp_dp_split_map_change_cords("' . esc_html($this->listing_short_counter) . '", \'false\');
						});

					});
				</script>';
            }
            $html .='<ul class="cs-checkbox-list">';
            foreach ( $cus_field['options']['value'] as $cus_field_options_value ) {

                if ( $cus_field['options']['value'][$cut_field_flag] == '' || $cus_field['options']['label'][$cut_field_flag] == '' ) {
                    $cut_field_flag ++;
                    continue;
                }

                // get count of each item
                // extra condidation
                if ( $cus_field['post_multi'] == 'yes' ) {

                    $dropdown_count_arr = array(
                        'key' => $query_str_var_name,
                        'value' => serialize($cus_field_options_value),
                        'compare' => 'Like',
                    );
                } else {
                    $dropdown_count_arr = array(
                        'key' => $query_str_var_name,
                        'value' => $cus_field_options_value,
                        'compare' => '=',
                    );
                }
                // main query array $args_count 
                if ( $cus_field_options_value != '' ) {
                    if ( $cus_field['multi'] == 'yes' ) {
                        $html .='<li class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><div class="checkbox">';
                        $checked = '';
                        if ( ! empty($request_val_arr) && in_array($cus_field_options_value, $request_val_arr) ) {
                            $checked = 'checked="checked"';
                        }
                        $html .= $wp_dp_form_fields_frontend->wp_dp_form_checkbox_render(
                                array(
                                    'return' => true,
                                    'simple' => true,
                                    'cust_id' => $query_str_var_name . '_' . $number_option_flag,
                                    'cust_name' => '',
                                    'std' => $cus_field_options_value,
                                    'classes' => $query_str_var_name,
                                    'extra_atr' => $checked . '',
                                )
                        );
                        $html .='<label for="' . force_balance_tags($query_str_var_name . '_' . $number_option_flag) . '">' . force_balance_tags($cus_field['options']['label'][$cut_field_flag]) . '</label> 
								</div>
							</li>' . "\n";
                    } else {
                        $html .='<li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<div class="checkbox">';
                        $checked = '';
                        if ( ! empty($request_val) && $cus_field_options_value == $request_val ) {
                            $checked = 'checked="checked"';
                        }
                        $html .=$wp_dp_form_fields_frontend->wp_dp_form_radio_render(
                                array(
                                    'return' => true,
                                    'simple' => true,
                                    'cust_id' => $query_str_var_name . '_' . $number_option_flag,
                                    'cust_name' => $query_str_var_name,
                                    'std' => $cus_field_options_value,
                                    'extra_atr' => $checked . ' onchange="wp_dp_split_map_change_cords(\'' . esc_html($this->listing_short_counter) . '\', \'false\');"',
                                )
                        );
                        $html .='<label for="' . force_balance_tags($query_str_var_name . '_' . $number_option_flag) . '">' . force_balance_tags($cus_field['options']['label'][$cut_field_flag]) . '</label> 
								</div>
							</li>' . "\n";
                    }
                }
                $number_option_flag ++;
                $cut_field_flag ++;
            }
            $html .='</ul>';
            return $html;
        }

        public function wp_dp_date_field($custom_field = '') {
            global $wp_dp_form_fields;
            $field_counter = rand(12345, 54321);

            $query_str_var_name = isset($custom_field['meta_key']) ? $custom_field['meta_key'] : '';
            $field_label = isset($custom_field['label']) ? $custom_field['label'] : '';
            $field_fontawsome_icon = isset($custom_field['fontawsome_icon']) ? $custom_field['fontawsome_icon'] : '';
            $field_icon = $this->wp_dp_field_icon($field_fontawsome_icon);
            wp_enqueue_script('bootstrap-datepicker');
            wp_enqueue_style('datetimepicker');
            wp_enqueue_style('datepicker');
            wp_enqueue_script('datetimepicker');
            ?>

            <div class="cs-datepicker field-datepicker field-holder search-input">

                <label id="Deadline" class="cs-calendar-from-<?php echo wp_dp_cs_allow_special_char($field_counter); ?>">
                    <?php echo wp_dp_allow_special_char($field_label); ?>
                    <?php
                    $wp_dp_form_fields->wp_dp_form_text_render(
                            array(
                                'id' => $query_str_var_name,
                                'cust_name' => 'from' . $query_str_var_name,
                                'classes' => '',
                                'std' => isset($_REQUEST['from' . $query_str_var_name]) ? $_REQUEST['from' . $query_str_var_name] : '',
                                'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_listing_leftflter_fron_date') . '");" onchange="wp_dp_split_map_change_cords(\'' . $this->listing_short_counter . '\', \'false\');"',
                            )
                    );
                    ?>

                </label>
            </div>
            <div class="cs-datepicker field-datepicker field-holder search-input">
                <label id="Deadline" class="cs-calendar-to-<?php echo wp_dp_cs_allow_special_char($field_counter); ?>">
                    <?php //echo wp_dp_allow_special_char($field_label); ?>&nbsp;
                    <?php
                    $wp_dp_form_fields->wp_dp_form_text_render(
                            array(
                                'id' => $query_str_var_name,
                                'cust_name' => 'to' . $query_str_var_name,
                                'classes' => '',
                                'std' => isset($_REQUEST['to' . $query_str_var_name]) ? $_REQUEST['to' . $query_str_var_name] : '',
                                'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_listing_leftflter_to_date') . '");" onchange="wp_dp_split_map_change_cords(\'' . $this->listing_short_counter . '\', \'false\');"',
                            )
                    );
                    ?>

                </label>
            </div>
            <?php
            if ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
                echo '<script type="text/javascript">
						if (jQuery(".cs-calendar-from-' . $field_counter . ' input").length != "") {
							jQuery(".cs-calendar-from-' . $field_counter . ' input").datetimepicker({
								timepicker:false,
								format:	"Y/m/d",
								scrollInput: false
							});
						}
						if (jQuery(".cs-calendar-to-' . $field_counter . ' input").length != "") {
							jQuery(".cs-calendar-to-' . $field_counter . ' input").datetimepicker({
								timepicker:false,
								format:	"Y/m/d",
								scrollInput: false
							});
						}
					</script>';
            } else {
                echo '<script type="text/javascript">
						jQuery(window).load(function(){
								if (jQuery(".cs-calendar-from-' . $field_counter . ' input").length != "") {
								jQuery(".cs-calendar-from-' . $field_counter . ' input").datetimepicker({
									timepicker:false,
									format:	"Y/m/d",
									scrollInput: false
								});
							}
							if (jQuery(".cs-calendar-to-' . $field_counter . ' input").length != "") {
								jQuery(".cs-calendar-to-' . $field_counter . ' input").datetimepicker({
									timepicker:false,
									format:	"Y/m/d",
									scrollInput: false
								});
							}
						});
					</script>';
            }
            ?>

            <?php
        }

        public function wp_dp_range_field($custom_field = '') {
            global $wp_dp_form_fields, $wp_dp_form_fields_frontend;
            $range_min = $custom_field['min'];
            $field_label = isset($custom_field['label']) ? $custom_field['label'] : '';
            $range_max = $custom_field['max'];
            $range_increment = $custom_field['increment'];
            $query_str_var_name = $custom_field['meta_key'];
            $filed_type = $custom_field['srch_style']; //input, slider, input_slider
            if ( strpos($filed_type, '-') !== FALSE ) {
                $filed_type_arr = explode("_", $filed_type);
            } else {
                $filed_type_arr[0] = $filed_type;
            }
            $range_flag = 0;
            $rand_id = rand(12345, 54321);
            ?>
            <div class="features-list field-holder <?php
            if ( $filed_type_arr[$range_flag] == 'slider' ) {
                echo 'field-range';
            } else {
                echo 'select-dropdown ';
            }
            ?>">
                     <?php
                     while ( count($filed_type_arr) > $range_flag ) {
                         if ( $filed_type_arr[$range_flag] == 'slider' ) { // if slider style
                             if ( (isset($custom_field['min']) && $custom_field['min'] != '') && (isset($custom_field['max']) && $custom_field['max'] != '' ) ) {
                                 $range_complete_str_first = "";
                                 $range_complete_str_second = "";
                                 $range_complete_str = '';
                                 if ( isset($_REQUEST[$query_str_var_name]) ) {
                                     $range_complete_str = $_REQUEST[$query_str_var_name];
                                     $range_complete_str_arr = explode(",", $range_complete_str);
                                     $range_complete_str_first = isset($range_complete_str_arr[0]) ? $range_complete_str_arr[0] : '';
                                     $range_complete_str_second = isset($range_complete_str_arr[1]) ? $range_complete_str_arr[1] : '';
                                 } else {
                                     $range_complete_str = $custom_field['min'] . ',' . $custom_field['max'];
                                     $range_complete_str_first = $custom_field['min'];
                                     $range_complete_str_second = $custom_field['max'];
                                 }

                                 $wp_dp_form_fields->wp_dp_form_hidden_render(
                                         array(
                                             'simple' => true,
                                             'cust_id' => "range-hidden-" . $query_str_var_name,
                                             'cust_name' => $query_str_var_name,
                                             'std' => esc_html($range_complete_str),
                                             'classes' => $query_str_var_name,
                                         )
                                 );
                                 ?>
                            <div class="price-per-person">
                                <span class="rang-text"><?php echo wp_dp_allow_special_char($field_label); ?>&nbsp;<?php echo esc_html($range_complete_str_first); ?> &nbsp; - &nbsp; <?php echo esc_html($range_complete_str_second); ?></span>
                                <?php
                                $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                                        array(
                                            'cust_name' => '',
                                            'cust_id' => 'ex16b1' . esc_html($rand_id . $query_str_var_name),
                                            'std' => '',
                                        )
                                );
                                ?>  
                            </div>
                            <?php
                            $increment_step = isset($custom_field['increment']) ? $custom_field['increment'] : 1;
                            if ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
                                echo '<script type="text/javascript">
									if (jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").length > 0) {
										jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").slider({
											step : ' . esc_html($increment_step) . ',
											min: ' . esc_html($custom_field['min']) . ',
											max: ' . esc_html($custom_field['max']) . ',
											value: [ ' . esc_html($range_complete_str) . '],
										});
										jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").on("slideStop", function () {
											var rang_slider_val = jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").val();
											jQuery("#range-hidden-' . $query_str_var_name . '").val(rang_slider_val);
											wp_dp_split_map_change_cords("' . esc_html($this->listing_short_counter) . '", \'false\');
										});
									}
								</script>';
                            } else {
                                echo '<script type="text/javascript">
									jQuery(window).load(function(){
										if (jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").length > 0) {
											jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").slider({
												step : ' . esc_html($increment_step) . ',
												min: ' . esc_html($custom_field['min']) . ',
												max: ' . esc_html($custom_field['max']) . ',
												value: [ ' . esc_html($range_complete_str) . '],
											});
											jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").on("slideStop", function () {
												var rang_slider_val = jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").val();
												jQuery("#range-hidden-' . $query_str_var_name . '").val(rang_slider_val); 
												wp_dp_split_map_change_cords("' . esc_html($this->listing_short_counter) . '", \'false\');
											});
										}
									});
								</script>';
                            }
                        }
                    } else {
                        ?>
                        <strong class="advance-trigger"><?php echo esc_html($field_label); ?><em class="currency-sign"><?php echo wp_dp_get_currency_sign(); ?></em></strong> 

                        <div class="features-field-expand">
                            <?php
                            $options = array();
                            $options[''] = wp_dp_allow_special_char($field_label);
                            $range_min = $custom_field['min'];
                            $range_max = $custom_field['max'];

                            $counter = 0;
                            while ( $counter < $range_max ) {
                                $options[$counter . ',' . ($counter + $range_increment)] = ($counter . ' - ' . ($counter + $range_increment));
                                $counter += $range_increment;
                            }

                            ksort($options);
                            $options = array_filter($options);
                            $wp_dp_opt_array = array(
                                'std' => '',
                                'id' => $query_str_var_name,
                                'classes' => 'chosen-select',
                                'cust_name' => $query_str_var_name,
                                'options' => $options,
                            );
                            $wp_dp_form_fields_frontend->wp_dp_form_select_render($wp_dp_opt_array);
                            ?> 
                        </div>
                        <?php
                    }
                    $range_flag ++;
                }
                ?>
            </div>
            <?php
        }

        public function wp_dp_field_size($field_size) {
            switch ( $field_size ) {
                case "small":
                    $col_size = '4';
                    break;
                case "medium":
                    $col_size = '6';
                    break;
                case "large":
                    $col_size = '12';
                    break;
                default :
                    $col_size = '12';
                    break;
            }
            return $col_size;
        }

        public function wp_dp_field_label($field_label) {
            $output = '';
            if ( $field_label != '' ) {
                $output .= '<label>' . $field_label . '</label>';
            }
            return $output;
        }

        public function wp_dp_field_icon($field_fontawsome_icon) {
            $output = '';
            if ( $field_fontawsome_icon != '' ) {
                $output .= '<i class="' . $field_fontawsome_icon . '"></i>';
            }
            return $output;
        }

        public function wp_dp_field_placeholder($field_placeholder) {
            $placeholder = '';
            if ( $field_placeholder != '' ) {
                $placeholder .= 'placeholder="' . $field_placeholder . '"';
            }
            return $placeholder;
        }

        public function wp_dp_listing_type_categories_options($listing_type_slug = '') {
            $listing_cats_options = array();
            if ( $listing_type_slug != '' ) {
                $listing_type_id = $this->wp_dp_listing_type_id_by_slug($listing_type_slug);
                $listing_type_cats = get_post_meta($listing_type_id, 'wp_dp_listing_type_cats', true);
                if ( isset($listing_type_cats) && is_array($listing_type_cats) && ! empty($listing_type_cats) ) {
                    $listing_cats_options[''] = wp_dp_plugin_text_srt('wp_dp_search_element_listig_categories');
                    foreach ( $listing_type_cats as $listing_type_cat_slug ) {
                        if ( $listing_type_cat_slug != '' ) {
                            $term = get_term_by('slug', $listing_type_cat_slug, 'listing-category');
                            if ( isset($term->name) && ! empty($term->name) ) {
                                $listing_cats_options[$listing_type_cat_slug] = $term->name;
                            }
                        }
                    }
                }
            }
            
            if ( isset($listing_type_slug) && $listing_type_slug == 'all' ) {
                $all_terms = get_terms(array(
                    'taxonomy' => 'listing-category',
                    'hide_empty' => false,
                        ));
                if ( $all_terms && ! is_wp_error($all_terms) ) :
                    $listing_cats_options[''] = wp_dp_plugin_text_srt('wp_dp_search_element_listig_categories');
                    foreach ( $all_terms as $term_single ) {
                        $listing_cats_options[$term_single->slug] = $term_single->name;
                    } endif;
            }
            return $listing_cats_options;
        }

    }

    global $wp_dp_split_map_search_fields;
    $wp_dp_split_map_search_fields = new Wp_dp_Split_Map_Search_Fields();
}