<?php
/*
 *
 * @File : locations
 * @retrun
 *
 */
if ( ! function_exists('wp_dp_cs_var_page_builder_locations') ) {

    function wp_dp_cs_var_page_builder_locations($die = 0) {
        global $post, $wp_dp_html_fields, $wp_dp_form_fields;
        $strings = new wp_dp_cs_theme_all_strings;
        $strings->wp_dp_cs_short_code_strings();
        $strings->wp_dp_cs_theme_option_strings();
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $counter = $_POST['counter'];
        $wp_dp_cs_counter = $_POST['counter'];
        if ( isset($_POST['action']) && ! isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes($shortcode_element_id);
            $PREFIX = 'wp_dp_cs_locations';
            $parseObject = new ShortcodeParse();
            $output = $parseObject->wp_dp_cs_shortcodes($output, $shortcode_str, true, $PREFIX);
        }
        $defaults = array(
            'wp_dp_cs_locations_element_title' => '',
            'wp_dp_cs_locations_element_subtitle' => '',
            'wp_dp_var_locations_align' => '',
            'wp_dp_var_locations_style' => '',
            'wp_dp_all_locations_names' => '',
            'wp_dp_all_locations_url' => '',
            'wp_dp_location_element_title_color' => '',
            'wp_dp_location_element_subtitle_color' => '',
            'wp_dp_location_seperator_style' => '',
        );

        // Apply filter on default attributes
        $defaults = apply_filters('wp_dp_shortcode_default_atts', $defaults, array( 'responsive_atts' => true ));

        if ( isset($output['0']['atts']) ) {
            $atts = $output['0']['atts'];
        } else {
            $atts = array();
        }
        $locations_element_size = '50';
        foreach ( $defaults as $key => $values ) {
            if ( isset($atts[$key]) ) {
                $$key = $atts[$key];
            } else {
                $$key = $values;
            }
        }
        $name = 'wp_dp_cs_var_page_builder_locations';
        $coloumn_class = 'column_' . $locations_element_size;
        $wp_dp_cs_locations_element_title = isset($wp_dp_cs_locations_element_title) ? $wp_dp_cs_locations_element_title : '';
        $wp_dp_cs_locations_element_subtitle = isset($wp_dp_cs_locations_element_subtitle) ? $wp_dp_cs_locations_element_subtitle : '';
        $wp_dp_var_locations_align = isset($wp_dp_var_locations_align) ? $wp_dp_var_locations_align : '';

        if ( isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode' ) {
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
        $wp_dp_cs_rand_id = rand(13441324, 93441324);
        ?>
        <div id="<?php echo esc_attr($name . $wp_dp_cs_counter); ?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class); ?> <?php echo esc_attr($shortcode_view); ?>" item="locations" data="<?php echo wp_dp_cs_element_size_data_array_index($locations_element_size) ?>">
            <?php wp_dp_cs_element_setting($name, $wp_dp_cs_counter, $locations_element_size); ?>
            <div class="cs-wrapp-class-<?php echo intval($wp_dp_cs_counter) ?> <?php echo esc_attr($shortcode_element); ?>" id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>" data-shortcode-template="[wp_dp_cs_locations {{attributes}}]"  style="display: none;">
                <div class="cs-heading-area">
                    <h5><?php echo esc_html(wp_dp_plugin_text_srt('wp_dp_element_location_shortcode_options')); ?></h5>
                    <a href="javascript:wp_dp_cs_frame_removeoverlay('<?php echo esc_js($name . $wp_dp_cs_counter); ?>','<?php echo esc_js($filter_element); ?>')" class="cs-btnclose"><i class="icon-cross"></i></a>
                </div>
                <div class="cs-pbwp-content">
                    <div class="cs-wrapp-clone cs-shortcode-wrapp">
                        <?php
                        if ( isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode' ) {
                            wp_dp_cs_shortcode_element_size();
                        }
                        $wp_dp_cs_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_element_title'),
                            'desc' => '',
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_element_title_hint'),
                            'echo' => true,
                            'field_params' => array(
                                'std' => esc_attr($wp_dp_cs_locations_element_title),
                                'cust_id' => '',
                                'cust_name' => 'wp_dp_cs_locations_element_title[]',
                                'return' => true,
                            ),
                        );
                        $wp_dp_html_fields->wp_dp_text_field($wp_dp_cs_opt_array);

                        $wp_dp_cs_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_element_sub_title'),
                            'desc' => '',
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_element_sub_title_hint'),
                            'echo' => true,
                            'field_params' => array(
                                'std' => esc_attr($wp_dp_cs_locations_element_subtitle),
                                'classes' => '',
                                'cust_name' => 'wp_dp_cs_locations_element_subtitle[]',
                                'return' => true,
                            ),
                        );
                        $wp_dp_html_fields->wp_dp_text_field($wp_dp_cs_opt_array);

                        $wp_dp_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_title_align'),
                            'desc' => '',
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_title_align_hint'),
                            'echo' => true,
                            'field_params' => array(
                                'std' => $wp_dp_var_locations_align,
                                'id' => '',
                                'cust_name' => 'wp_dp_var_locations_align[]',
                                'classes' => 'chosen-select-no-single',
                                'options' => array(
                                    'align-left' => wp_dp_plugin_text_srt('wp_dp_align_left'),
                                    'align-right' => wp_dp_plugin_text_srt('wp_dp_align_right'),
                                    'align-center' => wp_dp_plugin_text_srt('wp_dp_align_center'),
                                ),
                                'return' => true,
                            ),
                        );
                        $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                        $wp_dp_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_color'),
                            'desc' => '',
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_color_hint'),
                            'echo' => true,
                            'field_params' => array(
                                'std' => $wp_dp_location_element_title_color,
                                'cust_name' => 'wp_dp_location_element_title_color[]',
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
                                'std' => $wp_dp_location_element_subtitle_color,
                                'cust_name' => 'wp_dp_location_element_subtitle_color[]',
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
                                'std' => esc_attr($wp_dp_location_seperator_style),
                                'classes' => 'chosen-select-no-single',
                                'cust_name' => 'wp_dp_location_seperator_style[]',
                                'return' => true,
                                'options' => array(
                                    '' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_seperator_style_none'),
                                    'classic' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_seperator_style_classic'),
                                    'zigzag' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_seperator_style_zigzag'),
                                ),
                            ),
                        );
                        $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);




                        $wp_dp_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_element_location_shortcode_styles'),
                            'desc' => '',
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_element_location_shortcode_styles_hint'),
                            'echo' => true,
                            'field_params' => array(
                                'std' => $wp_dp_var_locations_style,
                                'id' => '',
                                'cust_id' => 'wp_dp_var_locations_style',
                                'cust_name' => 'wp_dp_var_locations_style[]',
                                'extra_atr' => 'onchange="javascript:location_hide_show(this.value)"',
                                'classes' => 'service_postion chosen-select-no-single select-medium',
                                'options' => array(
                                    'modern' => wp_dp_plugin_text_srt('wp_dp_element_location_shortcode_styles_modern'),
                                    'simple' => wp_dp_plugin_text_srt('wp_dp_element_location_shortcode_styles_simple'),
                                    'classic' => wp_dp_plugin_text_srt('wp_dp_location_element_style_classic'),
                                ),
                                'return' => true,
                            ),
                        );
                        $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                        $locations_names_array = array();
                        $get_all_locations_data = '';
                        $get_all_locations_data = get_terms(array(
                            'taxonomy' => 'wp_dp_locations',
                            'hide_empty' => false,
                        ));
                        if ( isset($get_all_locations_data) && ! empty($get_all_locations_data) && is_array($get_all_locations_data) ) {
                            foreach ( $get_all_locations_data as $key => $value ) {
                                $locations_names_array[$value->slug] = $value->name;
                            }
                        }
                        $wp_dp_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_element_location_shortcode_locations'),
                            'desc' => '',
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_element_location_shortcode_locations_hint'),
                            'echo' => true,
                            'multi' => true,
                            'classes' => 'chosen-select',
                            'field_params' => array(
                                'std' => $wp_dp_all_locations_names,
                                'id' => '',
                                'cust_id' => 'wp_dp_all_locations_names',
                                'cust_name' => 'wp_dp_all_locations_names[' . $wp_dp_cs_counter . '][]',
                                'classes' => 'chosen-select',
                                'options' => $locations_names_array,
                                'return' => true,
                            ),
                        );
                        $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                        $loc_sty = '';
                        if ( $wp_dp_var_locations_style == 'simple' ) {
                            $loc_sty = ' style="display:none"';
                        }
                        ?>
                        <script>

                            function location_hide_show(view) {
                                if (view == 'modern') {
                                    jQuery('.all-loc-hide').show();
                                } else {
                                    jQuery('.all-loc-hide').hide();
                                }
                            }
                        </script>
                        <?php
                        echo '<div class="all-loc-hide"' . $loc_sty . '>';
                        $wp_dp_cs_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_element_location_shortcode_all_locations_url'),
                            'desc' => '',
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_element_location_shortcode_all_locations_url_hint'),
                            'echo' => true,
                            'field_params' => array(
                                'std' => esc_attr($wp_dp_all_locations_url),
                                'classes' => '',
                                'cust_name' => 'wp_dp_all_locations_url[]',
                                'return' => true,
                            ),
                        );
                        $wp_dp_html_fields->wp_dp_text_field($wp_dp_cs_opt_array);
                        echo '</div>';
                        // add responsive fields				
                        do_action('wp_dp_shortcode_fields_render', $output, array( 'responsive_fields' => true ));
                        if ( isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode' ) {
                            ?>
                            <ul class="form-elements insert-bg">
                                <li class="to-field">
                                    <a class="insert-btn cs-main-btn" onclick="javascript:wp_dp_cs_shortcode_insert_editor('<?php echo str_replace('wp_dp_cs_var_page_builder_', '', $name); ?>', '<?php echo esc_js($name . $wp_dp_cs_counter) ?>', '<?php echo esc_js($filter_element); ?>')" ><?php echo wp_dp_plugin_text_srt('wp_dp_insert'); ?></a>
                                </li>
                            </ul>
                            <div id="results-shortocde"></div>
                        <?php } else { ?>

                            <?php
                            $wp_dp_cs_opt_array = array(
                                'std' => 'locations',
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
                            $listing_rand_id = isset( $listing_rand_id )? $listing_rand_id : '';
                            $wp_dp_cs_opt_array = array(
                                'name' => '',
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_save'),
                                    'cust_id' => 'locations_save',
                                    'cust_type' => 'button',
                                    'extra_atr' => 'onclick="javascript:_removerlay(jQuery(this))"',
                                    'classes' => 'cs-wp_dp_cs-admin-btn save_listing_locations_' . $listing_rand_id . '',
                                    'cust_name' => 'locations_save',
                                    'return' => true,
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_cs_opt_array);
                        }
                        ?>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                popup_over();
                chosen_selectionbox();
            </script>
        </div>

        <?php
        if ( $die <> 1 ) {
            die();
        }
    }

    add_action('wp_ajax_wp_dp_cs_var_page_builder_locations', 'wp_dp_cs_var_page_builder_locations');
}
if ( ! function_exists('wp_dp_cs_save_page_builder_data_locations_callback') ) {

    /**
     * Save data for locations shortcode.
     *
     * @param	array $args
     * @return	array
     */
    function wp_dp_cs_save_page_builder_data_locations_callback($args) {
        global $location_names;
        $data = $args['data'];
        $counters = $args['counters'];
        $widget_type = $args['widget_type'];
        $column = $args['column'];
        $shortcode_data = '';
        if ( $widget_type == "locations" || $widget_type == "cs_locations" ) {

            $wp_dp_cs_var_locations = '';
            $page_element_size = $data['locations_element_size'][$counters['wp_dp_cs_global_counter_locations']];
            $locations_element_size = $data['locations_element_size'][$counters['wp_dp_cs_global_counter_locations']];

            if ( isset($data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']]) && $data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']] == 'shortcode' ) {
                $shortcode_str = stripslashes(( $data['shortcode']['locations'][$counters['wp_dp_cs_shortcode_counter_locations']]));
                $element_settings = 'locations_element_size="' . $locations_element_size . '"';
                $reg = '/locations_element_size="(\d+)"/s';
                $shortcode_str = preg_replace($reg, $element_settings, $shortcode_str);
                $shortcode_data = $shortcode_str;
                $counters['wp_dp_cs_shortcode_counter_locations'] ++;
            } else {
                $wp_dp_cs_var_locations = '[wp_dp_cs_locations locations_element_size="' . htmlspecialchars($data['locations_element_size'][$counters['wp_dp_cs_global_counter_locations']]) . '" ';
                if ( isset($data['wp_dp_cs_locations_element_title'][$counters['wp_dp_cs_counter_locations']]) && $data['wp_dp_cs_locations_element_title'][$counters['wp_dp_cs_counter_locations']] != '' ) {
                    $wp_dp_cs_var_locations .= 'wp_dp_cs_locations_element_title="' . htmlspecialchars($data['wp_dp_cs_locations_element_title'][$counters['wp_dp_cs_counter_locations']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['wp_dp_cs_locations_element_subtitle'][$counters['wp_dp_cs_counter_locations']]) && $data['wp_dp_cs_locations_element_subtitle'][$counters['wp_dp_cs_counter_locations']] != '' ) {
                    $wp_dp_cs_var_locations .= 'wp_dp_cs_locations_element_subtitle="' . htmlspecialchars($data['wp_dp_cs_locations_element_subtitle'][$counters['wp_dp_cs_counter_locations']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['wp_dp_var_locations_align'][$counters['wp_dp_cs_counter_locations']]) && $data['wp_dp_var_locations_align'][$counters['wp_dp_cs_counter_locations']] != '' ) {
                    $wp_dp_cs_var_locations .= 'wp_dp_var_locations_align="' . htmlspecialchars($data['wp_dp_var_locations_align'][$counters['wp_dp_cs_counter_locations']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['wp_dp_location_seperator_style'][$counters['wp_dp_cs_counter_locations']]) && $data['wp_dp_location_seperator_style'][$counters['wp_dp_cs_counter_locations']] != '' ) {
                    $wp_dp_cs_var_locations .= 'wp_dp_location_seperator_style="' . htmlspecialchars($data['wp_dp_location_seperator_style'][$counters['wp_dp_cs_counter_locations']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['wp_dp_location_element_title_color'][$counters['wp_dp_cs_counter_locations']]) && $data['wp_dp_location_element_title_color'][$counters['wp_dp_cs_counter_locations']] != '' ) {
                    $wp_dp_cs_var_locations .= 'wp_dp_location_element_title_color="' . htmlspecialchars($data['wp_dp_location_element_title_color'][$counters['wp_dp_cs_counter_locations']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['wp_dp_location_element_subtitle_color'][$counters['wp_dp_cs_counter_locations']]) && $data['wp_dp_location_element_subtitle_color'][$counters['wp_dp_cs_counter_locations']] != '' ) {
                    $wp_dp_cs_var_locations .= 'wp_dp_location_element_subtitle_color="' . htmlspecialchars($data['wp_dp_location_element_subtitle_color'][$counters['wp_dp_cs_counter_locations']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['wp_dp_var_locations_style'][$counters['wp_dp_cs_counter_locations']]) && $data['wp_dp_var_locations_style'][$counters['wp_dp_cs_counter_locations']] != '' ) {
                    $wp_dp_cs_var_locations .= 'wp_dp_var_locations_style="' . htmlspecialchars($data['wp_dp_var_locations_style'][$counters['wp_dp_cs_counter_locations']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['wp_dp_all_locations_url'][$counters['wp_dp_cs_counter_locations']]) && $data['wp_dp_all_locations_url'][$counters['wp_dp_cs_counter_locations']] != '' ) {
                    $wp_dp_cs_var_locations .= 'wp_dp_all_locations_url="' . htmlspecialchars($data['wp_dp_all_locations_url'][$counters['wp_dp_cs_counter_locations']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['wp_dp_all_locations_names']) && $data['wp_dp_all_locations_names'] != '' ) {
                    $locations_array = array();
                    if( empty( $location_names ) ){
                        $location_names = $data['wp_dp_all_locations_names'];
                    }
                    if ( ! empty($data['wp_dp_all_locations_names']) ) {
                        foreach ( $location_names as $key => $location_names_array ) {
                            if( !isset( $locations_array[$counters['wp_dp_cs_counter_locations']] ) || empty( $locations_array[$counters['wp_dp_cs_counter_locations']] ) ){
                                $locations_array[$counters['wp_dp_cs_counter_locations']] = $location_names_array;
                                unset( $args['data']['wp_dp_all_locations_names'][$key] );
                                unset( $data['wp_dp_all_locations_names'][$key] );
                                unset( $location_names[$key]);
                            }
                        }
                    }
                    $listing_locations_names = $locations_array;
                    $location_lists = '';
                    $total = count($listing_locations_names);
                    $count = 0;
                    if ( is_array($listing_locations_names) ) {
                        foreach ( $listing_locations_names as $listing_cats ) {
                            $location_lists = implode( ',', $listing_cats );
                        }
                    }
                    $wp_dp_cs_var_locations .= 'wp_dp_all_locations_names="' . $location_lists . '" ';
                }

                // Apply filter on default attributes Saving
                $section_title = apply_filters('wp_dp_shortcode_default_atts_save', $wp_dp_cs_var_locations, $data, $counters['wp_dp_cs_counter_locations'], array( 'responsive_atts' => true ));
                $wp_dp_cs_var_locations = $section_title;
                $wp_dp_cs_var_locations .= ']';
                if ( isset($data['locations_text'][$counters['wp_dp_cs_counter_locations']]) && $data['locations_text'][$counters['wp_dp_cs_counter_locations']] != '' ) {
                    $wp_dp_cs_var_locations .= htmlspecialchars($data['locations_text'][$counters['wp_dp_cs_counter_locations']], ENT_QUOTES) . ' ';
                }
                $wp_dp_cs_var_locations .= '[/wp_dp_cs_locations]';
                $shortcode_data .= $wp_dp_cs_var_locations;

                $counters['wp_dp_cs_counter_locations'] ++;
            }
            $counters['wp_dp_cs_global_counter_locations'] ++;
        }
        return array(
            'data' => $data,
            'counters' => $counters,
            'widget_type' => $widget_type,
            'column' => $shortcode_data,
        );
    }

    add_filter('wp_dp_cs_save_page_builder_data_locations', 'wp_dp_cs_save_page_builder_data_locations_callback');
}
if ( ! function_exists('wp_dp_cs_load_shortcode_counters_locations_callback') ) {

    /**
     * Populate locations shortcode counter variables.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_load_shortcode_counters_locations_callback($counters) {
        $counters['wp_dp_cs_global_counter_locations'] = 0;
        $counters['wp_dp_cs_shortcode_counter_locations'] = 0;
        $counters['wp_dp_cs_counter_locations'] = 0;
        return $counters;
    }

    add_filter('wp_dp_cs_load_shortcode_counters', 'wp_dp_cs_load_shortcode_counters_locations_callback');
}
if ( ! function_exists('wp_dp_cs_shortcode_names_list_populate_locations_callback') ) {

    /**
     * Populate locations shortcode names list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_shortcode_names_list_populate_locations_callback($shortcode_array) {
        $shortcode_array['locations'] = array(
            'title' => wp_dp_plugin_text_srt('wp_dp_element_location_shortcode_name'),
            'name' => 'locations',
            'icon' => 'icon-my_location',
            'categories' => 'typography',
        );
        return $shortcode_array;
    }

//icon-support2
    add_filter('wp_dp_cs_shortcode_names_list_populate', 'wp_dp_cs_shortcode_names_list_populate_locations_callback');
}
if ( ! function_exists('wp_dp_cs_element_list_populate_locations_callback') ) {

    /**
     * Populate locations shortcode strings list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_element_list_populate_locations_callback($element_list) {
        $element_list['locations'] = wp_dp_plugin_text_srt('wp_dp_element_location_shortcode_name');
        return $element_list;
    }

    add_filter('wp_dp_cs_element_list_populate', 'wp_dp_cs_element_list_populate_locations_callback');
}