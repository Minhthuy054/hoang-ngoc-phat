<?php
/**
 * Shortcode Name : listing_categories
 *
 * @package	wp_dp_cs 
 */
if (!function_exists('wp_dp_cs_var_page_builder_listing_categories')) {

    function wp_dp_cs_var_page_builder_listing_categories($die = 0) {
        global $post, $wp_dp_html_fields, $wp_dp_cs_node, $wp_dp_form_fields;
        if (function_exists('wp_dp_cs_shortcode_names')) {
            $shortcode_element = '';
            $filter_element = 'filterdrag';
            $shortcode_view = '';
            $wp_dp_cs_output = array();
            $wp_dp_cs_PREFIX = 'listing_categories';

            $wp_dp_cs_counter = isset($_POST['counter']) ? $_POST['counter'] : '';
            if (isset($_POST['action']) && !isset($_POST['shortcode_element_id'])) {
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
                'listing_categories_title' => '',
                'listing_categories_subtitle' => '',
                'listing_categories_title_align' => '', 
                'listing_categories' => '',
                'wp_dp_typess' => '',
                'listing_categories_more_less' => '',
            );
            if (isset($wp_dp_cs_output['0']['atts'])) {
                $atts = $wp_dp_cs_output['0']['atts'];
            } else {
                $atts = array();
            }
            if (isset($wp_dp_cs_output['0']['content'])) {
                $listing_categories_column_text = $wp_dp_cs_output['0']['content'];
            } else {
                $listing_categories_column_text = '';
            }
            $listing_categories_element_size = '100';
            foreach ($defaults as $key => $values) {
                if (isset($atts[$key])) {
                    $$key = $atts[$key];
                } else {
                    $$key = $values;
                }
            }
            $name = 'wp_dp_cs_var_page_builder_listing_categories';
            $coloumn_class = 'column_' . $listing_categories_element_size;
            if (isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode') {
                $shortcode_element = 'shortcode_element_class';
                $shortcode_view = 'cs-pbwp-shortcode';
                $filter_element = 'ajax-drag';
                $coloumn_class = '';
            }
            $rand_id = rand(4444, 99999);
            wp_enqueue_script('wp_dp_cs-admin-upload');
            ?>

            <div id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class); ?>
                 <?php echo esc_attr($shortcode_view); ?>" item="listing_categories" data="<?php echo wp_dp_cs_element_size_data_array_index($listing_categories_element_size) ?>" >
                     <?php wp_dp_cs_element_setting($name, $wp_dp_cs_counter, $listing_categories_element_size) ?>
                <div class="cs-wrapp-class-<?php echo intval($wp_dp_cs_counter) ?>
                     <?php echo esc_attr($shortcode_element); ?>" id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>" data-shortcode-template="[listing_categories {{attributes}}]{{content}}[/listing_categories]" style="display: none;">
                    <div class="cs-heading-area" data-counter="<?php echo esc_attr($wp_dp_cs_counter) ?>">
                        <h5><?php echo wp_dp_plugin_text_srt('wp_dp_listing_categories_options'); ?></h5>
                        <a href="javascript:wp_dp_cs_frame_removeoverlay('<?php echo esc_js($name . $wp_dp_cs_counter) ?>','<?php echo esc_js($filter_element); ?>')" class="cs-btnclose">
                            <i class="icon-cross"></i>
                        </a>
                    </div>
                    <div class="cs-pbwp-content">
                        <div class="cs-wrapp-clone cs-shortcode-wrapp">
                            <?php
                            if (isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode') {
                                wp_dp_cs_shortcode_element_size();
                            }


                            $wp_dp_types_array = array();
                            $args = array('post_type' => 'listing-type', 'posts_per_page' => '-1', 'post_status' => 'publish');
                            $query = new wp_query($args);
                            while ($query->have_posts()):
                                $query->the_post();
                                $wp_dp_types_array[get_the_id()] = get_the_title();
                            endwhile;

                            wp_reset_postdata();

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_element_title'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_element_title_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => $listing_categories_title,
                                    'id' => 'listing_categories_title',
                                    'cust_name' => 'listing_categories_title[]',
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
                                    'std' => $listing_categories_subtitle,
                                    'id' => 'listing_categories_subtitle',
                                    'cust_name' => 'listing_categories_subtitle[]',
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
                                    'std' => esc_attr($listing_categories_title_align),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'listing_categories_title_align[]',
                                    'return' => true,
                                    'options' => array(
                                        'align-left' => wp_dp_plugin_text_srt('wp_dp_align_left'),
                                        'align-right' => wp_dp_plugin_text_srt('wp_dp_align_right'),
                                        'align-center' => wp_dp_plugin_text_srt('wp_dp_align_center'),
                                    ),
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array); 
                            
                            ?>
                            <script>
                                jQuery(document).ready(function () {
                                    jQuery(".save_listing_categories_<?php echo absint($rand_id); ?>").click(function () {
                                        var MY_SELECT = jQuery('#wp_dp_listing_categories_array_<?php echo absint($rand_id); ?>').get(0);
                                        var selection = ChosenOrder.getSelectionOrder(MY_SELECT);
                                        var listing_categories_value = '';
                                        var comma = '';

                                        jQuery(selection).each(function (i) {
                                            listing_categories_value = listing_categories_value + comma + selection[i];
                                            comma = ',';
                                        });
                                        jQuery('#listing_categories_<?php echo absint($rand_id); ?>').val(listing_categories_value);
                                    });

                                });
                            </script>
                            <?php
                            $listing_categories_array = explode(',', $listing_categories);
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_listing_categories_categories'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_categories_categories_hint'),
                                'echo' => true,
                                'multi' => true,
                                'classes' => 'chosen-select',
                                'field_params' => array(
                                    'std' => $listing_categories_array,
                                    'id' => 'listing_categories_array_' . $rand_id,
                                    'cust_name' => 'listing_categories_array[]',
                                    'return' => true,
                                    'classes' => 'chosen-select',
                                    'options' => $wp_dp_types_array,
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $wp_dp_cs_opt_array = array(
                                'std' => $listing_categories,
                                'cust_id' => 'listing_categories_' . $rand_id . '',
                                'cust_name' => "listing_categories[]",
                                'required' => false
                            );
                            $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_cs_opt_array);
                            ?>
                        </div>
                        <?php if (isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode') { ?>
                            <ul class="form-elements insert-bg">
                                <li class="to-field">
                                    <a class="insert-btn cs-main-btn" onclick="javascript:wp_dp_cs_shortcode_insert_editor('<?php echo str_replace('wp_dp_cs_var_page_builder_', '', $name); ?>', '<?php echo esc_js($name . $wp_dp_cs_counter) ?>', '<?php echo esc_js($filter_element); ?>')" ><?php echo wp_dp_plugin_text_srt('wp_dp_insert'); ?></a>
                                </li>
                            </ul>
                            <div id="results-shortocde"></div>
                        <?php } else { 
                            $wp_dp_cs_opt_array = array(
                                'std' => 'listing_categories',
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
                                    'cust_id' => 'listing_categories_save',
                                    'cust_type' => 'button',
                                    'extra_atr' => 'onclick="javascript:_removerlay(jQuery(this))"',
                                    'classes' => 'cs-wp_dp_cs-admin-btn save_listing_categories_' . $rand_id,
                                    'cust_name' => 'listing_categories_save',
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
        if ($die <> 1) {
            die();
        }
    }

    add_action('wp_ajax_wp_dp_cs_var_page_builder_listing_categories', 'wp_dp_cs_var_page_builder_listing_categories');
}

if (!function_exists('wp_dp_cs_save_page_builder_data_listing_categories_callback')) {

    /**
     * Save data for listing_categories shortcode.
     *
     * @param	array $args
     * @return	array
     */
    function wp_dp_cs_save_page_builder_data_listing_categories_callback($args) {

        $data = $args['data'];
        $counters = $args['counters'];
        $widget_type = $args['widget_type'];
        $column = $args['column'];
        $shortcode_data = '';
        if ($widget_type == "listing_categories" || $widget_type == "cs_listing_categories") {

            $wp_dp_cs_bareber_listing_categories = '';

            $page_element_size = $data['listing_categories_element_size'][$counters['wp_dp_cs_global_counter_listing_categories']];
            $current_element_size = $data['listing_categories_element_size'][$counters['wp_dp_cs_global_counter_listing_categories']];

            if (isset($data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']]) && $data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']] == 'shortcode') {
                $shortcode_str = stripslashes(( $data['shortcode']['listing_categories'][$counters['wp_dp_cs_shortcode_counter_listing_categories']]));
                $element_settings = 'listing_categories_element_size="' . $current_element_size . '"';
                $reg = '/listing_categories_element_size="(\d+)"/s';
                $shortcode_str = preg_replace($reg, $element_settings, $shortcode_str);
                $shortcode_data = $shortcode_str;
                $wp_dp_cs_bareber_listing_categories ++;
                $counters['wp_dp_cs_shortcode_counter_listing_categories'] ++;
            } else {
                $element_settings = 'listing_categories_element_size="' . htmlspecialchars($data['listing_categories_element_size'][$counters['wp_dp_cs_global_counter_listing_categories']]) . '"';
                $wp_dp_cs_bareber_listing_categories = '[listing_categories ' . $element_settings . ' ';
                if (isset($data['listing_categories_title'][$counters['wp_dp_cs_counter_listing_categories']]) && $data['listing_categories_title'][$counters['wp_dp_cs_counter_listing_categories']] != '') {
                    $wp_dp_cs_bareber_listing_categories .= 'listing_categories_title="' . htmlspecialchars($data['listing_categories_title'][$counters['wp_dp_cs_counter_listing_categories']], ENT_QUOTES) . '" ';
                }
                if (isset($data['listing_categories_title_align'][$counters['wp_dp_cs_counter_listing_categories']]) && $data['listing_categories_title_align'][$counters['wp_dp_cs_counter_listing_categories']] != '') {
                    $wp_dp_cs_bareber_listing_categories .= 'listing_categories_title_align="' . htmlspecialchars($data['listing_categories_title_align'][$counters['wp_dp_cs_counter_listing_categories']], ENT_QUOTES) . '" ';
                } 
                if (isset($data['listing_categories_subtitle'][$counters['wp_dp_cs_counter_listing_categories']]) && $data['listing_categories_subtitle'][$counters['wp_dp_cs_counter_listing_categories']] != '') {
                    $wp_dp_cs_bareber_listing_categories .= 'listing_categories_subtitle="' . htmlspecialchars($data['listing_categories_subtitle'][$counters['wp_dp_cs_counter_listing_categories']], ENT_QUOTES) . '" ';
                }
                if (isset($data['listing_categories'][$counters['wp_dp_cs_counter_listing_categories']]) && $data['listing_categories'][$counters['wp_dp_cs_counter_listing_categories']] != '') {
                    $wp_dp_cs_bareber_listing_categories .= 'listing_categories="' . htmlspecialchars($data['listing_categories'][$counters['wp_dp_cs_counter_listing_categories']], ENT_QUOTES) . '" ';
                }
                $wp_dp_cs_bareber_listing_categories .= ']';
                if (isset($data['listing_categories_column_text'][$counters['wp_dp_cs_counter_listing_categories']]) && $data['listing_categories_column_text'][$counters['wp_dp_cs_counter_listing_categories']] != '') {
                    $wp_dp_cs_bareber_listing_categories .= htmlspecialchars($data['listing_categories_column_text'][$counters['wp_dp_cs_counter_listing_categories']], ENT_QUOTES) . ' ';
                }
                $wp_dp_cs_bareber_listing_categories .= '[/listing_categories]';

                $shortcode_data .= $wp_dp_cs_bareber_listing_categories;
                $counters['wp_dp_cs_counter_listing_categories'] ++;
            }
            $counters['wp_dp_cs_global_counter_listing_categories'] ++;
        }
        return array(
            'data' => $data,
            'counters' => $counters,
            'widget_type' => $widget_type,
            'column' => $shortcode_data,
        );
    }

    add_filter('wp_dp_cs_save_page_builder_data_listing_categories', 'wp_dp_cs_save_page_builder_data_listing_categories_callback');
}

if (!function_exists('wp_dp_cs_load_shortcode_counters_listing_categories_callback')) {

    /**
     * Populate listing_categories shortcode counter variables.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_load_shortcode_counters_listing_categories_callback($counters) {
        $counters['wp_dp_cs_global_counter_listing_categories'] = 0;
        $counters['wp_dp_cs_shortcode_counter_listing_categories'] = 0;
        $counters['wp_dp_cs_counter_listing_categories'] = 0;
        return $counters;
    }

    add_filter('wp_dp_cs_load_shortcode_counters', 'wp_dp_cs_load_shortcode_counters_listing_categories_callback');
}



if (!function_exists('wp_dp_cs_element_list_populate_listing_categories_callback')) {

    /**
     * Populate listing_categories shortcode strings list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_element_list_populate_listing_categories_callback($element_list) {
        $element_list['listing_categories'] = wp_dp_plugin_text_srt('wp_dp_listing_categories_heading');
        return $element_list;
    }

    add_filter('wp_dp_cs_element_list_populate', 'wp_dp_cs_element_list_populate_listing_categories_callback');
}

if (!function_exists('wp_dp_cs_shortcode_names_list_populate_listing_categories_callback')) {

    /**
     * Populate listing_categories shortcode names list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_shortcode_names_list_populate_listing_categories_callback($shortcode_array) {
        $shortcode_array['listing_categories'] = array(
            'title' => wp_dp_plugin_text_srt('wp_dp_listing_categories_heading'),
            'name' => 'listing_categories',
            'icon' => 'icon-home6',
            'categories' => 'typography',
        );

        return $shortcode_array;
    }

    add_filter('wp_dp_cs_shortcode_names_list_populate', 'wp_dp_cs_shortcode_names_list_populate_listing_categories_callback');
}
