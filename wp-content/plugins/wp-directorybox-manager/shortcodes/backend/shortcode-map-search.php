<?php
/**
 * Shortcode Name : map_search
 *
 * @package	wp_dp_cs 
 */
if ( ! function_exists('wp_dp_page_builder_map_search') ) {

    function wp_dp_page_builder_map_search($die = 0) {
        global $post, $wp_dp_html_fields, $wp_dp_cs_node, $wp_dp_form_fields;
        if ( function_exists('wp_dp_cs_shortcode_names') ) {
            $shortcode_element = '';
            $filter_element = 'filterdrag';
            $shortcode_view = '';
            $wp_dp_cs_output = array();
            $wp_dp_cs_PREFIX = 'map_search';

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
                'map_search_title' => '',
                'map_search_subtitle' => '',
                'map_search_title_alignment' => '',
                'map_map_search_switch' => '',
                'listing_type' => '',
                'map_search_box_switch' => '',
                'map_search_result_page' => '',
                'map_search_lat' => '',
                'map_search_long' => '',
                'map_search_zoom' => '',
                'map_map_search_height' => '400',
                'map_search_title_field_switch' => '',
                'map_search_listing_type_field_switch' => '',
                'map_search_location_field_switch' => '',
                'map_search_categories_field_switch' => '',
                'map_search_advance_filter_switch' => '',
            );
            if ( isset($wp_dp_cs_output['0']['atts']) ) {
                $atts = $wp_dp_cs_output['0']['atts'];
            } else {
                $atts = array();
            }
            if ( isset($wp_dp_cs_output['0']['content']) ) {
                $map_search_column_text = $wp_dp_cs_output['0']['content'];
            } else {
                $map_search_column_text = '';
            }
            $map_search_element_size = '100';
            foreach ( $defaults as $key => $values ) {
                if ( isset($atts[$key]) ) {
                    $$key = $atts[$key];
                } else {
                    $$key = $values;
                }
            }
            $name = 'wp_dp_cs_var_page_builder_map_search';
            $coloumn_class = 'column_' . $map_search_element_size;
            if ( isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode' ) {
                $shortcode_element = 'shortcode_element_class';
                $shortcode_view = 'cs-pbwp-shortcode';
                $filter_element = 'ajax-drag';
                $coloumn_class = '';
            }
            ?>

            <div id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class); ?>
                 <?php echo esc_attr($shortcode_view); ?>" item="map_search" data="<?php echo wp_dp_cs_element_size_data_array_index($map_search_element_size) ?>" >
                     <?php wp_dp_cs_element_setting($name, $wp_dp_cs_counter, $map_search_element_size) ?>
                <div class="cs-wrapp-class-<?php echo intval($wp_dp_cs_counter) ?>
                     <?php echo esc_attr($shortcode_element); ?>" id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>" data-shortcode-template="[map_search {{attributes}}]{{content}}[/map_search]" style="display: none;">
                    <div class="cs-heading-area" data-counter="<?php echo esc_attr($wp_dp_cs_counter) ?>">
                        <h5><?php echo wp_dp_plugin_text_srt('wp_dp_map_search_options'); ?></h5>
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
                                    'std' => $map_search_title,
                                    'id' => 'map_search_title',
                                    'cust_name' => 'map_search_title[]',
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
                                    'std' => $map_search_subtitle,
                                    'id' => 'map_search_subtitle',
                                    'cust_name' => 'map_search_subtitle[]',
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
                                    'std' => esc_attr($map_search_title_alignment),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'map_search_title_alignment[]',
                                    'return' => true,
                                    'options' => array(
                                        'align-left' => wp_dp_plugin_text_srt('wp_dp_align_left'),
                                        'align-right' => wp_dp_plugin_text_srt('wp_dp_align_right'),
                                        'align-center' => wp_dp_plugin_text_srt('wp_dp_align_center'),
                                    ),
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);


                            $wp_dp_post_listing_types = new wp_dp_Post_Listing_Types();
                            $listing_types_array = $wp_dp_post_listing_types->wp_dp_types_array_callback(wp_dp_plugin_text_srt('wp_dp_shortcode_listings_all_types'));
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_map_search_listing_type'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listing_type),
                                    'id' => 'listing_type',
                                    'classes' => 'chosen-select',
                                    'cust_name' => 'listing_type[]',
                                    'return' => true,
                                    'options' => $listing_types_array
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $field_args = array(
                                'depth' => 0,
                                'child_of' => 0,
                                'class' => 'chosen-select',
                                'sort_order' => 'ASC',
                                'sort_column' => 'post_title',
                                'show_option_none' => wp_dp_plugin_text_srt('wp_dp_select_a_page'),
                                'hierarchical' => '1',
                                'exclude' => '',
                                'include' => '',
                                'meta_key' => '',
                                'meta_value' => '',
                                'authors' => '',
                                'exclude_tree' => '',
                                'selected' => $map_search_result_page,
                                'echo' => 0,
                                'name' => 'map_search_result_page[]',
                                'post_type' => 'page'
                            );
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_select_result_page'),
                                'id' => 'map_search_result_page',
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_select_result_page_hint'),
                                'std' => $map_search_result_page,
                                'args' => $field_args,
                            );
                            echo ($wp_dp_html_fields->wp_dp_select_page_field($wp_dp_opt_array));

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_list_meta_map'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_map_search_map_on'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => $map_map_search_switch,
                                    'id' => 'map_map_search_switch',
                                    'cust_name' => 'map_map_search_switch[]',
                                    'return' => true,
                                    'options' => array( 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'), 'no' => wp_dp_plugin_text_srt('wp_dp_listing_no') ),
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_map_search_map_height'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_map_search_map_height_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => $map_map_search_height,
                                    'id' => 'map_map_search_height',
                                    'cust_name' => 'map_map_search_height[]',
                                    'return' => true,
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_map_search_box'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_map_search_box_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => $map_search_box_switch,
                                    'id' => 'map_search_box_switch',
                                    'cust_name' => 'map_search_box_switch[]',
                                    'return' => true,
                                    'options' => array( 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'), 'no' => wp_dp_plugin_text_srt('wp_dp_listing_no') ),
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_map_search_keyword'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_map_search_keyword_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($map_search_title_field_switch),
                                    'cust_id' => '',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'map_search_title_field_switch[]',
                                    'return' => true,
                                    'options' => array(
                                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                    )
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_map_search_listing_type'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_map_search_listing_type_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($map_search_listing_type_field_switch),
                                    'cust_id' => '',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'map_search_listing_type_field_switch[]',
                                    'return' => true,
                                    'options' => array(
                                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                    )
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_map_search_location'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_map_search_location_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($map_search_location_field_switch),
                                    'cust_id' => '',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'map_search_location_field_switch[]',
                                    'return' => true,
                                    'options' => array(
                                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                    )
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_map_search_categories'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_map_search_categories_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($map_search_categories_field_switch),
                                    'cust_id' => '',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'map_search_categories_field_switch[]',
                                    'return' => true,
                                    'options' => array(
                                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                    )
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                            ?>
                        </div>
                        <?php if ( isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode' ) { ?>
                            <ul class="form-elements insert-bg">
                                <li class="to-field">
                                    <a class="insert-btn cs-main-btn" onclick="javascript:wp_dp_cs_shortcode_insert_editor('<?php echo str_replace('wp_dp_page_builder_', '', $name); ?>', '<?php echo esc_js($name . $wp_dp_cs_counter) ?>', '<?php echo esc_js($filter_element); ?>')" ><?php echo wp_dp_plugin_text_srt('wp_dp_insert'); ?></a>
                                </li>
                            </ul>
                            <div id="results-shortocde"></div>
                        <?php } else { ?>

                            <?php
                            $wp_dp_cs_opt_array = array(
                                'std' => 'map_search',
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
                                    'cust_id' => 'map_search_save',
                                    'cust_type' => 'button',
                                    'extra_atr' => 'onclick="javascript:_removerlay(jQuery(this))"',
                                    'classes' => 'cs-wp_dp_cs-admin-btn',
                                    'cust_name' => 'map_search_save',
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

    add_action('wp_ajax_wp_dp_cs_var_page_builder_map_search', 'wp_dp_page_builder_map_search');
}

if ( ! function_exists('wp_dp_cs_save_page_builder_data_map_search_callback') ) {

    /**
     * Save data for map_search shortcode.
     *
     * @param	array $args
     * @return	array
     */
    function wp_dp_cs_save_page_builder_data_map_search_callback($args) {

        $data = $args['data'];
        $counters = $args['counters'];
        $widget_type = $args['widget_type'];
        $column = $args['column'];
        $shortcode_data = '';
        if ( $widget_type == "map_search" || $widget_type == "cs_map_search" ) {
            $wp_dp_cs_bareber_map_search = '';

            $page_element_size = $data['map_search_element_size'][$counters['wp_dp_cs_global_counter_map_search']];
            $current_element_size = $data['map_search_element_size'][$counters['wp_dp_cs_global_counter_map_search']];

            if ( isset($data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']]) && $data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']] == 'shortcode' ) {
                $shortcode_str = stripslashes(( $data['shortcode']['map_search'][$counters['wp_dp_cs_shortcode_counter_map_search']]));

                $element_settings = 'map_search_element_size="' . $current_element_size . '"';
                $reg = '/map_search_element_size="(\d+)"/s';
                $shortcode_str = preg_replace($reg, $element_settings, $shortcode_str);
                $shortcode_data = $shortcode_str;

                $counters['wp_dp_cs_shortcode_counter_map_search'] ++;
            } else {
                $element_settings = 'map_search_element_size="' . htmlspecialchars($data['map_search_element_size'][$counters['wp_dp_cs_global_counter_map_search']]) . '"';
                $wp_dp_cs_bareber_map_search = '[map_search ' . $element_settings . ' ';
                if ( isset($data['map_search_title'][$counters['wp_dp_cs_counter_map_search']]) && $data['map_search_title'][$counters['wp_dp_cs_counter_map_search']] != '' ) {
                    $wp_dp_cs_bareber_map_search .= 'map_search_title="' . htmlspecialchars($data['map_search_title'][$counters['wp_dp_cs_counter_map_search']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['map_search_title_alignment'][$counters['wp_dp_cs_counter_map_search']]) && $data['map_search_title_alignment'][$counters['wp_dp_cs_counter_map_search']] != '' ) {
                    $wp_dp_cs_bareber_map_search .= 'map_search_title_alignment="' . htmlspecialchars($data['map_search_title_alignment'][$counters['wp_dp_cs_counter_map_search']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['map_search_subtitle'][$counters['wp_dp_cs_counter_map_search']]) && $data['map_search_subtitle'][$counters['wp_dp_cs_counter_map_search']] != '' ) {
                    $wp_dp_cs_bareber_map_search .= 'map_search_subtitle="' . htmlspecialchars($data['map_search_subtitle'][$counters['wp_dp_cs_counter_map_search']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['map_search_box_switch'][$counters['wp_dp_cs_counter_map_search']]) && $data['map_search_box_switch'][$counters['wp_dp_cs_counter_map_search']] != '' ) {
                    $wp_dp_cs_bareber_map_search .= 'map_search_box_switch="' . htmlspecialchars($data['map_search_box_switch'][$counters['wp_dp_cs_counter_map_search']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['map_map_search_switch'][$counters['wp_dp_cs_counter_map_search']]) && $data['map_map_search_switch'][$counters['wp_dp_cs_counter_map_search']] != '' ) {
                    $wp_dp_cs_bareber_map_search .= 'map_map_search_switch="' . htmlspecialchars($data['map_map_search_switch'][$counters['wp_dp_cs_counter_map_search']], ENT_QUOTES) . '" ';
                }

                if ( isset($data['map_search_title_field_switch'][$counters['wp_dp_cs_counter_map_search']]) && $data['map_search_title_field_switch'][$counters['wp_dp_cs_counter_map_search']] != '' ) {
                    $wp_dp_cs_bareber_map_search .= 'map_search_title_field_switch="' . htmlspecialchars($data['map_search_title_field_switch'][$counters['wp_dp_cs_counter_map_search']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['listing_type'][$counters['wp_dp_cs_counter_map_search']]) && $data['listing_type'][$counters['wp_dp_cs_counter_map_search']] != '' ) {
                    $wp_dp_cs_bareber_map_search .= 'listing_type="' . htmlspecialchars($data['listing_type'][$counters['wp_dp_cs_counter_map_search']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['map_search_listing_type_field_switch'][$counters['wp_dp_cs_counter_map_search']]) && $data['map_search_listing_type_field_switch'][$counters['wp_dp_cs_counter_map_search']] != '' ) {
                    $wp_dp_cs_bareber_map_search .= 'map_search_listing_type_field_switch="' . htmlspecialchars($data['map_search_listing_type_field_switch'][$counters['wp_dp_cs_counter_map_search']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['map_search_location_field_switch'][$counters['wp_dp_cs_counter_map_search']]) && $data['map_search_location_field_switch'][$counters['wp_dp_cs_counter_map_search']] != '' ) {
                    $wp_dp_cs_bareber_map_search .= 'map_search_location_field_switch="' . htmlspecialchars($data['map_search_location_field_switch'][$counters['wp_dp_cs_counter_map_search']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['map_search_categories_field_switch'][$counters['wp_dp_cs_counter_map_search']]) && $data['map_search_categories_field_switch'][$counters['wp_dp_cs_counter_map_search']] != '' ) {
                    $wp_dp_cs_bareber_map_search .= 'map_search_categories_field_switch="' . htmlspecialchars($data['map_search_categories_field_switch'][$counters['wp_dp_cs_counter_map_search']], ENT_QUOTES) . '" ';
                }

                if ( isset($data['map_search_result_page'][$counters['wp_dp_cs_counter_map_search']]) && $data['map_search_result_page'][$counters['wp_dp_cs_counter_map_search']] != '' ) {
                    $wp_dp_cs_bareber_map_search .= 'map_search_result_page="' . htmlspecialchars($data['map_search_result_page'][$counters['wp_dp_cs_counter_map_search']], ENT_QUOTES) . '" ';
                }

                if ( isset($data['map_search_lat'][$counters['wp_dp_cs_counter_map_search']]) && $data['map_search_lat'][$counters['wp_dp_cs_counter_map_search']] != '' ) {
                    $wp_dp_cs_bareber_map_search .= 'map_search_lat="' . htmlspecialchars($data['map_search_lat'][$counters['wp_dp_cs_counter_map_search']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['map_search_long'][$counters['wp_dp_cs_counter_map_search']]) && $data['map_search_long'][$counters['wp_dp_cs_counter_map_search']] != '' ) {
                    $wp_dp_cs_bareber_map_search .= 'map_search_long="' . htmlspecialchars($data['map_search_long'][$counters['wp_dp_cs_counter_map_search']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['map_search_zoom'][$counters['wp_dp_cs_counter_map_search']]) && $data['map_search_zoom'][$counters['wp_dp_cs_counter_map_search']] != '' ) {
                    $wp_dp_cs_bareber_map_search .= 'map_search_zoom="' . htmlspecialchars($data['map_search_zoom'][$counters['wp_dp_cs_counter_map_search']], ENT_QUOTES) . '" ';
                }

                if ( isset($data['map_map_search_height'][$counters['wp_dp_cs_counter_map_search']]) && $data['map_map_search_height'][$counters['wp_dp_cs_counter_map_search']] != '' ) {
                    $wp_dp_cs_bareber_map_search .= 'map_map_search_height="' . htmlspecialchars($data['map_map_search_height'][$counters['wp_dp_cs_counter_map_search']], ENT_QUOTES) . '" ';
                }

                $wp_dp_cs_bareber_map_search .= ']';
                if ( isset($data['map_search_column_text'][$counters['wp_dp_cs_counter_map_search']]) && $data['map_search_column_text'][$counters['wp_dp_cs_counter_map_search']] != '' ) {
                    $wp_dp_cs_bareber_map_search .= htmlspecialchars($data['map_search_column_text'][$counters['wp_dp_cs_counter_map_search']], ENT_QUOTES) . ' ';
                }
                $wp_dp_cs_bareber_map_search .= '[/map_search]';

                $shortcode_data .= $wp_dp_cs_bareber_map_search;
                $counters['wp_dp_cs_counter_map_search'] ++;
            }
            $counters['wp_dp_cs_global_counter_map_search'] ++;
        }
        return array(
            'data' => $data,
            'counters' => $counters,
            'widget_type' => $widget_type,
            'column' => $shortcode_data,
        );
    }

    add_filter('wp_dp_cs_save_page_builder_data_map_search', 'wp_dp_cs_save_page_builder_data_map_search_callback');
}

if ( ! function_exists('wp_dp_cs_load_shortcode_counters_map_search_callback') ) {

    /**
     * Populate map_search shortcode counter variables.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_load_shortcode_counters_map_search_callback($counters) {
        $counters['wp_dp_cs_global_counter_map_search'] = 0;
        $counters['wp_dp_cs_shortcode_counter_map_search'] = 0;
        $counters['wp_dp_cs_counter_map_search'] = 0;
        return $counters;
    }

    add_filter('wp_dp_cs_load_shortcode_counters', 'wp_dp_cs_load_shortcode_counters_map_search_callback');
}

if ( ! function_exists('wp_dp_cs_element_list_populate_map_search_callback') ) {

    /**
     * Populate map_search shortcode strings list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_element_list_populate_map_search_callback($element_list) {
        $element_list['map_search'] = wp_dp_plugin_text_srt('wp_dp_map_search_heading');
        return $element_list;
    }

    add_filter('wp_dp_cs_element_list_populate', 'wp_dp_cs_element_list_populate_map_search_callback');
}

if ( ! function_exists('wp_dp_cs_shortcode_names_list_populate_map_search_callback') ) {

    /**
     * Populate map_search shortcode names list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_shortcode_names_list_populate_map_search_callback($shortcode_array) {
        $shortcode_array['map_search'] = array(
            'title' => wp_dp_plugin_text_srt('wp_dp_map_search_heading'),
            'name' => 'map_search',
            'icon' => 'icon-street-view',
            'categories' => 'typography',
        );

        return $shortcode_array;
    }

    add_filter('wp_dp_cs_shortcode_names_list_populate', 'wp_dp_cs_shortcode_names_list_populate_map_search_callback');
}
