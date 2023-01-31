<?php
/**
 * Shortcode Name : wp_dp_register
 *
 * @package	wp_dp_cs 
 */
if ( ! function_exists('wp_dp_cs_var_page_builder_wp_dp_register') ) {

    function wp_dp_cs_var_page_builder_wp_dp_register($die = 0) {
        global $post, $wp_dp_html_fields, $wp_dp_cs_node, $wp_dp_form_fields;
        if ( function_exists('wp_dp_cs_shortcode_names') ) {
            $shortcode_element = '';
            $filter_element = 'filterdrag';
            $shortcode_view = '';
            $wp_dp_cs_output = array();
            $wp_dp_cs_PREFIX = 'wp_dp_register';

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
                'wp_dp_cs_var_column' => '1',
                'wp_dp_cs_var_wp_dp_register_logo_url_array' => '',
                'wp_dp_cs_var_wp_dp_register_image_url_array' => '',
                'title' => '',
                'subtitle' => '',
                'title_alignmenet' => '',
                'wp_dp_cs_var_lunch_date' => '',
                'wp_dp_cs_var_wp_dp_register_estimated_time' => '',
                'wp_dp_register_element_title_color' => '',
                'wp_dp_register_element_subtitle_color' => '',
            );
            if ( isset($wp_dp_cs_output['0']['atts']) ) {
                $atts = $wp_dp_cs_output['0']['atts'];
            } else {
                $atts = array();
            }
            if ( isset($wp_dp_cs_output['0']['content']) ) {
                $wp_dp_register_column_text = $wp_dp_cs_output['0']['content'];
            } else {
                $wp_dp_register_column_text = '';
            }
            $wp_dp_register_element_size = '100';
            foreach ( $defaults as $key => $values ) {
                if ( isset($atts[$key]) ) {
                    $$key = $atts[$key];
                } else {
                    $$key = $values;
                }
            }
            $name = 'wp_dp_cs_var_page_builder_wp_dp_register';

            $coloumn_class = 'column_' . $wp_dp_register_element_size;
            if ( isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode' ) {
                $shortcode_element = 'shortcode_element_class';
                $shortcode_view = 'cs-pbwp-shortcode';
                $filter_element = 'ajax-drag';
                $coloumn_class = '';
            }
            wp_enqueue_script('wp_dp_cs-admin-upload');
            ?>

            <div id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class); ?>
                 <?php echo esc_attr($shortcode_view); ?>" item="wp_dp_register" data="<?php echo wp_dp_cs_element_size_data_array_index($wp_dp_register_element_size) ?>" >
                     <?php wp_dp_cs_element_setting($name, $wp_dp_cs_counter, $wp_dp_register_element_size) ?>
                <div class="cs-wrapp-class-<?php echo intval($wp_dp_cs_counter) ?>
                     <?php echo esc_attr($shortcode_element); ?>" id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>" data-shortcode-template="[wp_dp_register {{attributes}}]{{content}}[/wp_dp_register]" style="display: none;">
                    <div class="cs-heading-area" data-counter="<?php echo esc_attr($wp_dp_cs_counter) ?>">
                        <h5><?php echo wp_dp_plugin_text_srt('wp_dp_shortcode_register_options'); ?></h5>
                        <a href="javascript:wp_dp_cs_frame_removeoverlay('<?php echo esc_js($name . $wp_dp_cs_counter) ?>','<?php echo esc_js($filter_element); ?>')" class="cs-btnclose">
                            <i class="icon-cross"></i>
                        </a>
                    </div>
                    <div class="cs-pbwp-content">
                        <div class="cs-wrapp-clone cs-shortcode-wrapp">
                            <?php
                            if ( isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode' ) {
                                wp_dp_cs_shortcode_element_size();
                            }

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_element_title'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_element_title_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => $title,
                                    'id' => 'title',
                                    'cust_name' => 'title[]',
                                    'return' => true,
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_element_sub_title'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_element_sub_title_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => $subtitle,
                                    'id' => 'subtitle',
                                    'cust_name' => 'subtitle[]',
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
                                    'std' => esc_attr($title_alignmenet),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'title_alignmenet[]',
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
                                    'std' => $wp_dp_register_element_title_color,
                                    'cust_name' => 'wp_dp_register_element_title_color[]',
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
                                    'std' => $wp_dp_register_element_subtitle_color,
                                    'cust_name' => 'wp_dp_register_element_subtitle_color[]',
                                    'classes' => 'bg_color',
                                    'return' => true,
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
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
                                'std' => 'wp_dp_register',
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
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_save'),
                                    'cust_id' => 'wp_dp_register_save',
                                    'cust_type' => 'button',
                                    'extra_atr' => 'onclick="javascript:_removerlay(jQuery(this))"',
                                    'classes' => 'cs-wp_dp_cs-admin-btn',
                                    'cust_name' => 'wp_dp_register_save',
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
					chosen_selectionbox();
                </script>
            </div>

            <?php
        }
        if ( $die <> 1 ) {
            die();
        }
    }

    add_action('wp_ajax_wp_dp_cs_var_page_builder_wp_dp_register', 'wp_dp_cs_var_page_builder_wp_dp_register');
}

if ( ! function_exists('wp_dp_cs_save_page_builder_data_wp_dp_register_callback') ) {

    /**
     * Save data for wp_dp_register shortcode.
     *
     * @param	array $args
     * @return	array
     */
    function wp_dp_cs_save_page_builder_data_wp_dp_register_callback($args) {

        $data = $args['data'];
        $counters = $args['counters'];
        $widget_type = $args['widget_type'];
        $column = $args['column'];
        $shortcode_data = '';
        if ( $widget_type == "wp_dp_register" || $widget_type == "cs_wp_dp_register" ) {
            $wp_dp_cs_bareber_wp_dp_register = '';

            $page_element_size = $data['wp_dp_register_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_register']];
            $current_element_size = $data['wp_dp_register_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_register']];

            if ( isset($data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']]) && $data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']] == 'shortcode' ) {
                $shortcode_str = stripslashes(( $data['shortcode']['wp_dp_register'][$counters['wp_dp_cs_shortcode_counter_wp_dp_register']]));

                $element_settings = 'wp_dp_register_element_size="' . $current_element_size . '"';
                $reg = '/wp_dp_register_element_size="(\d+)"/s';
                $shortcode_str = preg_replace($reg, $element_settings, $shortcode_str);
                $shortcode_data = $shortcode_str;

                $wp_dp_cs_bareber_wp_dp_register ++;
            } else {
                $element_settings = 'wp_dp_register_element_size="' . htmlspecialchars($data['wp_dp_register_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_register']]) . '"';
                $wp_dp_cs_bareber_wp_dp_register = '[wp_dp_register ' . $element_settings . ' ';
                if ( isset($data['title'][$counters['wp_dp_cs_counter_wp_dp_register']]) && $data['title'][$counters['wp_dp_cs_counter_wp_dp_register']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_register .= 'title="' . htmlspecialchars($data['title'][$counters['wp_dp_cs_counter_wp_dp_register']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['subtitle'][$counters['wp_dp_cs_counter_wp_dp_register']]) && $data['subtitle'][$counters['wp_dp_cs_counter_wp_dp_register']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_register .= 'subtitle="' . htmlspecialchars($data['subtitle'][$counters['wp_dp_cs_counter_wp_dp_register']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['title_alignmenet'][$counters['wp_dp_cs_counter_wp_dp_register']]) && $data['title_alignmenet'][$counters['wp_dp_cs_counter_wp_dp_register']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_register .= 'title_alignmenet="' . htmlspecialchars($data['title_alignmenet'][$counters['wp_dp_cs_counter_wp_dp_register']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['wp_dp_register_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_register']]) && $data['wp_dp_register_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_register']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_register .= 'wp_dp_register_element_title_color="' . htmlspecialchars($data['wp_dp_register_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_register']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['wp_dp_register_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_register']]) && $data['wp_dp_register_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_register']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_register .= 'wp_dp_register_element_subtitle_color="' . htmlspecialchars($data['wp_dp_register_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_register']], ENT_QUOTES) . '" ';
                }
                $wp_dp_cs_bareber_wp_dp_register .= ']';
                if ( isset($data['wp_dp_register_column_text'][$counters['wp_dp_cs_counter_wp_dp_register']]) && $data['wp_dp_register_column_text'][$counters['wp_dp_cs_counter_wp_dp_register']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_register .= htmlspecialchars($data['wp_dp_register_column_text'][$counters['wp_dp_cs_counter_wp_dp_register']], ENT_QUOTES) . ' ';
                }
                
                $wp_dp_cs_bareber_wp_dp_register .= '[/wp_dp_register]';

                $shortcode_data .= $wp_dp_cs_bareber_wp_dp_register;
                $counters['wp_dp_cs_counter_wp_dp_register'] ++;
            }
            $counters['wp_dp_cs_global_counter_wp_dp_register'] ++;
        }
        return array(
            'data' => $data,
            'counters' => $counters,
            'widget_type' => $widget_type,
            'column' => $shortcode_data,
        );
    }

    add_filter('wp_dp_cs_save_page_builder_data_wp_dp_register', 'wp_dp_cs_save_page_builder_data_wp_dp_register_callback');
}

if ( ! function_exists('wp_dp_cs_load_shortcode_counters_wp_dp_register_callback') ) {

    /**
     * Populate wp_dp_register shortcode counter variables.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_load_shortcode_counters_wp_dp_register_callback($counters) {
        $counters['wp_dp_cs_global_counter_wp_dp_register'] = 0;
        $counters['wp_dp_cs_shortcode_counter_wp_dp_register'] = 0;
        $counters['wp_dp_cs_counter_wp_dp_register'] = 0;
        return $counters;
    }

    add_filter('wp_dp_cs_load_shortcode_counters', 'wp_dp_cs_load_shortcode_counters_wp_dp_register_callback');
}



if ( ! function_exists('wp_dp_cs_element_list_populate_wp_dp_register_callback') ) {

    /**
     * Populate wp_dp_register shortcode strings list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_element_list_populate_wp_dp_register_callback($element_list) {
        $element_list['wp_dp_register'] = wp_dp_plugin_text_srt('wp_dp_shortcode_register_heading');
        return $element_list;
    }

    add_filter('wp_dp_cs_element_list_populate', 'wp_dp_cs_element_list_populate_wp_dp_register_callback');
}

if ( ! function_exists('wp_dp_cs_shortcode_names_list_populate_wp_dp_register_callback') ) {

    /**
     * Populate wp_dp_register shortcode names list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_shortcode_names_list_populate_wp_dp_register_callback($shortcode_array) {
        $shortcode_array['wp_dp_register'] = array(
            'title' => wp_dp_plugin_text_srt('wp_dp_shortcode_register_heading'),
            'name' => 'wp_dp_register',
            'icon' => 'icon-registered',
            'categories' => 'typography',
        );

        return $shortcode_array;
    }

    add_filter('wp_dp_cs_shortcode_names_list_populate', 'wp_dp_cs_shortcode_names_list_populate_wp_dp_register_callback');
}
