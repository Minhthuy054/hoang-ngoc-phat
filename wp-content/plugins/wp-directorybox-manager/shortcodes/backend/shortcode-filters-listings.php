<?php
/**
 * Shortcode Name : wp_dp_listings_with_filters
 *
 * @package	wp_dp_cs 
 */
if (!function_exists('wp_dp_cs_var_page_builder_wp_dp_listings_with_filters')) {

    function wp_dp_cs_var_page_builder_wp_dp_listings_with_filters($die = 0) {
        global $post, $wp_dp_html_fields, $wp_dp_cs_node, $wp_dp_form_fields;
        if (function_exists('wp_dp_cs_shortcode_names')) {
            $shortcode_element = '';
            $filter_element = 'filterdrag';
            $shortcode_view = '';
            $wp_dp_cs_output = array();
            $wp_dp_cs_PREFIX = 'wp_dp_listings_with_filters';
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
                'filters_listings_title' => '',
                'filters_listings_title_limit' => '',
                'listings_subtitle' => '',
                'listings_filters_alagnment' => '',
                'listing_view' => 'v1',
                'filters_listing_types' => array(),
                'filters_listing_type' => array(),
                'listing_sort_by' => 'no',
                'listing_urgent' => 'only-featured',
                'listing_ads_switch' => 'no',
                'listing_price_filter' => 'yes',
                'listing_ads_after_list_count' => '5',
                'listing_location' => '',
                'posts_per_page' => '6',
                'pagination' => '',
                'show_more_button' => 'no',
                'show_more_button_url' => '',
                'listing_no_custom_fields' => '3',
                'wp_dp_filter_listings_element_title_color' => '',
                'wp_dp_filter_listings_element_subtitle_color' => '',
                'wp_dp_filter_listings_seperator_style' => '',
                'listing_enquiry_switch' => 'no',
                'listing_notes_switch' => 'no',
            );
            $defaults = apply_filters('wp_dp_listings_shortcode_admin_default_attributes', $defaults);
            // Apply filter on default attributes
            $defaults = apply_filters('wp_dp_shortcode_default_atts', $defaults, array('responsive_atts' => true));
            if (isset($wp_dp_cs_output['0']['atts'])) {
                $atts = $wp_dp_cs_output['0']['atts'];
            } else {
                $atts = array();
            }
            if (isset($wp_dp_cs_output['0']['content'])) {
                $wp_dp_listings_with_filters_column_text = $wp_dp_cs_output['0']['content'];
            } else {
                $wp_dp_listings_with_filters_column_text = '';
            }
            $wp_dp_listings_with_filters_element_size = '100';
            foreach ($defaults as $key => $values) {
                if (isset($atts[$key])) {
                    $$key = $atts[$key];
                } else {
                    $$key = $values;
                }
            }
            $name = 'wp_dp_cs_var_page_builder_wp_dp_listings_with_filters';
            $coloumn_class = 'column_' . $wp_dp_listings_with_filters_element_size;
            if (isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode') {
                $shortcode_element = 'shortcode_element_class';
                $shortcode_view = 'cs-pbwp-shortcode';
                $filter_element = 'ajax-drag';
                $coloumn_class = '';
            }
            $listing_rand_id = rand(4444, 99999);
            wp_enqueue_script('wp_dp_cs-admin-upload');
            ?>

            <div id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class); ?>
                 <?php echo esc_attr($shortcode_view); ?>" item="wp_dp_listings_with_filters" data="<?php echo wp_dp_cs_element_size_data_array_index($wp_dp_listings_with_filters_element_size) ?>" >
                     <?php wp_dp_cs_element_setting($name, $wp_dp_cs_counter, $wp_dp_listings_with_filters_element_size) ?>
                <div class="cs-wrapp-class-<?php echo intval($wp_dp_cs_counter) ?>
                     <?php echo esc_attr($shortcode_element); ?>" id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>" data-shortcode-template="[wp_dp_listings_with_filters {{attributes}}]{{content}}[/wp_dp_listings_with_filters]" style="display: none;">
                    <div class="cs-heading-area" data-counter="<?php echo esc_attr($wp_dp_cs_counter) ?>">
                        <h5><?php echo wp_dp_plugin_text_srt('wp_dp_listings_with_filters_options'); ?></h5>
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

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_element_title'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_element_title_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($filters_listings_title),
                                    'id' => 'filters_listings_title',
                                    'cust_name' => 'filters_listings_title[]',
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
                                    'std' => esc_attr($listings_subtitle),
                                    'id' => 'listings_subtitle',
                                    'cust_name' => 'listings_subtitle[]',
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
                                    'std' => esc_attr($listings_filters_alagnment),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'listings_filters_alagnment[]',
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
                                    'std' => $wp_dp_filter_listings_element_title_color,
                                    'cust_name' => 'wp_dp_filter_listings_element_title_color[]',
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
                                    'std' => $wp_dp_filter_listings_element_subtitle_color,
                                    'cust_name' => 'wp_dp_filter_listings_element_subtitle_color[]',
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
                                    'std' => esc_attr($wp_dp_filter_listings_seperator_style),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'wp_dp_filter_listings_seperator_style[]',
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
                                'name' => wp_dp_plugin_text_srt('wp_dp_element_view'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_element_view_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listing_view),
                                    'id' => 'listing_view' . $listing_rand_id . '',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'listing_view[]',
                                    'extra_atr' => 'onchange="listing_view' . $listing_rand_id . '(this.value)"',
                                    'return' => true,
                                    'options' => array(
                                        'v1' => wp_dp_plugin_text_srt('wp_dp_view1'),
                                        'v2' => wp_dp_plugin_text_srt('wp_dp_view2'),
                                    ),
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $wp_dp_post_filters_listing_types = new Wp_dp_Post_Listing_Types();
                            $filters_listing_types_array = $wp_dp_post_filters_listing_types->wp_dp_types_array_callback('NULL');

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_listing_types'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'multi' => true,
                                'field_params' => array(
                                    'std' => esc_attr($filters_listing_type),
                                    'multi' => true,
                                    'id' => 'filters_listing_type[]',
                                    'classes' => 'chosen-select',
                                    'cust_name' => 'filters_listing_type[]',
                                    'return' => true,
                                    'options' => $filters_listing_types_array,
                                ),
                            );

                            ?>
                            <script>
                                jQuery(document).ready(function () {
                                    jQuery(".save_filters_listing_types_<?php echo absint($listing_rand_id); ?>").click(function () {
                                        var MY_SELECT = jQuery('#wp_dp_filters_listing_types_<?php echo absint($listing_rand_id); ?>').get(0);
                                        var selection = ChosenOrder.getSelectionOrder(MY_SELECT);
                                        var filters_listing_type_value = '';
                                        var comma = '';
                                        jQuery(selection).each(function (i) {
                                            filters_listing_type_value = filters_listing_type_value + comma + selection[i];
                                            comma = ',';
                                        });
                                        jQuery('#filters_listing_type_<?php echo absint($listing_rand_id); ?>').val(filters_listing_type_value);
                                    });

                                });
                            </script>
                            <?php
                            $saved_filters_listing_type = $filters_listing_type;
                            $filters_listing_type_options = $filters_listing_types_array;

                            if ($filters_listing_type != '') {
                                if(!is_array($filters_listing_type)){
                                    $filters_listing_types = explode(',', $filters_listing_type);
                                    foreach ($filters_listing_types as $filters_listing_type) {
                                        $get_filters_listing_types[$filters_listing_type] = $filters_listing_type_options[$filters_listing_type];
                                    }
                                }
                            }
                            if (isset($get_filters_listing_types) && $get_filters_listing_types) {
                                $filters_listing_type_options = array_unique(array_merge($get_filters_listing_types, $filters_listing_type_options));
                            } else {
                                $filters_listing_type_options = $filters_listing_type_options;
                            }
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_listing_types'),
                                'desc' => '',
                                'label_desc' => '',
                                'multi' => true,
                                'echo' => true,
                                'field_params' => array(
                                    'std' => $saved_filters_listing_type,
                                    'id' => 'filters_listing_types_' . $listing_rand_id . '',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'filters_listing_types[]',
                                    'return' => true,
                                    'options' => $filters_listing_type_options,
									'extra_atr' => 'data-placeholder="'. wp_dp_plugin_text_srt('wp_dp_select_proprty_type') .'"',
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $wp_dp_cs_opt_array = array(
                                'std' => $saved_filters_listing_type,
                                'cust_id' => 'filters_listing_type_' . $listing_rand_id . '',
                                'cust_name' => "filters_listing_type[]",
                                'required' => false
                            );
                            $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_cs_opt_array);


                            $dynamic_title_length_grid = 'style="display: block;"';
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_title_length'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'main_wraper' => true,
                                'main_wraper_class' => 'dynamic_title_length',
                                'main_wraper_extra' => $dynamic_title_length_grid,
                                'field_params' => array(
                                    'std' => esc_attr($filters_listings_title_limit),
                                    'id' => 'filters_listings_title_limit',
                                    'cust_name' => 'filters_listings_title_limit[]',
                                    'return' => true,
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_number_of_custom_fields'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'main_wraper' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listing_no_custom_fields),
                                    'id' => 'listing_no_custom_fields',
                                    'cust_name' => 'listing_no_custom_fields[]',
                                    'return' => true,
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_enquiry_option'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_enquiry_option_desc'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listing_enquiry_switch),
                                    'id' => 'listing_enquiry_switch[]',
                                    'cust_name' => 'listing_enquiry_switch[]',
                                    'return' => true,
                                    'classes' => 'chosen-select-no-single',
                                    'extra_atr' => '',
                                    'options' => array(
                                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                    )
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                            
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_notes_option'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_notes_option_desc'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listing_notes_switch),
                                    'id' => 'listing_notes_switch[]',
                                    'cust_name' => 'listing_notes_switch[]',
                                    'return' => true,
                                    'classes' => 'chosen-select-no-single',
                                    'extra_atr' => '',
                                    'options' => array(
                                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                    )
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            do_action('wp_dp_compare_listings_element_field', $atts);

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_listing_urgent'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listing_urgent),
                                    'id' => 'listing_urgent[]',
                                    'cust_name' => 'listing_urgent[]',
                                    'return' => true,
                                    'classes' => 'chosen-select-no-single',
                                    'options' => array(
                                        'all' => wp_dp_plugin_text_srt('wp_dp_options_all'),
                                        'only-urgent' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_only_urgent'),
                                    )
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_ads_switch'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listing_ads_switch),
                                    'id' => 'listing_ads_switch[]',
                                    'cust_name' => 'listing_ads_switch[]',
                                    'return' => true,
                                    'classes' => 'chosen-select-no-single',
                                    'extra_atr' => 'onchange="listing_ads_count' . $listing_rand_id . '(this.value)"',
                                    'options' => array(
                                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                    )
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);


                            $listing_count_hide_string = '';
                            if ($listing_ads_switch == 'no') {
                                $listing_count_hide_string = 'style="display:none;"';
                            }

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_count'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_count_hint'),
                                'echo' => true,
                                'main_wraper' => true,
                                'main_wraper_class' => 'listing_count_dynamic_fields' . $listing_rand_id . '',
                                'main_wraper_extra' => $listing_count_hide_string,
                                'field_params' => array(
                                    'std' => esc_attr($listing_ads_after_list_count),
                                    'id' => 'listing_ads_after_list_count',
                                    'cust_name' => 'listing_ads_after_list_count[]',
                                    'return' => true,
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            ?>
                            <script>
                                jQuery(document).ready(function () {
                                    jQuery(".save_listing_locations_<?php echo absint($listing_rand_id); ?>").click(function () {
                                        var MY_SELECT = jQuery('#wp_dp_listing_locations_<?php echo absint($listing_rand_id); ?>').get(0);
                                        var selection = ChosenOrder.getSelectionOrder(MY_SELECT);
                                        var listing_location_value = '';
                                        var comma = '';
                                        jQuery(selection).each(function (i) {
                                            listing_location_value = listing_location_value + comma + selection[i];
                                            comma = ',';
                                        });
                                        jQuery('#listing_location_<?php echo absint($listing_rand_id); ?>').val(listing_location_value);
                                    });

                                });
                            </script>
                            <?php
                            $saved_listing_location = $listing_location;
                            $listing_location_options = array(
                                'country' => wp_dp_plugin_text_srt('wp_dp_options_country'),
                                'state' => wp_dp_plugin_text_srt('wp_dp_options_state'),
                                'city' => wp_dp_plugin_text_srt('wp_dp_options_city'),
                                'town' => wp_dp_plugin_text_srt('wp_dp_options_town'),
                                'address' => wp_dp_plugin_text_srt('wp_dp_options_town_complete_address'),
                            );

                            if ($saved_listing_location != '') {
                                if (!is_array($saved_listing_location)) {
                                    $listing_locations = explode(',', $saved_listing_location);
                                    foreach ($listing_locations as $listing_loc) {
                                        $get_listing_locations[$listing_loc] = $listing_location_options[$listing_loc];
                                    }
                                }
                            }
                            if (isset($get_listing_locations) && $get_listing_locations) {
                                $listing_location_options = array_unique(array_merge($get_listing_locations, $listing_location_options));
                            } else {
                                $listing_location_options = $listing_location_options;
                            }

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_location_filter'),
                                'desc' => '',
                                'label_desc' => '',
                                'multi' => true,
                                'echo' => true,
                                'field_params' => array(
                                    'std' => $saved_listing_location,
                                    'id' => 'listing_locations_' . $listing_rand_id . '',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'listing_locations[]',
                                    'return' => true,
                                    'options' => $listing_location_options,
									'extra_atr' => 'data-placeholder="'. wp_dp_plugin_text_srt('wp_dp_select_location_option') .'"',
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $wp_dp_cs_opt_array = array(
                                'std' => $listing_location,
                                'cust_id' => 'listing_location_' . $listing_rand_id . '',
                                'cust_name' => "listing_location[]",
                                'required' => false
                            );
                            $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_cs_opt_array);

                            do_action('wp_wp_dp_listings_with_filters_shortcode_admin_fields', array('wp_dp_filters_listing_type' => isset($wp_dp_filters_listing_type) ? $wp_dp_filters_listing_type : '', 'listing_alert_button' => isset($listing_alert_button) ? $listing_alert_button : ''));


                            $show_more_switch_field_string = '';
                            if ($listing_view == 'v2') {
                                $show_more_switch_field_string = 'style="display:none;"';
                            }

                            $show_more_listing_button_switch_options = array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'));
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_show_more_switch'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'main_wraper' => true,
                                'main_wraper_class' => 'show_more_switch_field' . $listing_rand_id . '',
                                'main_wraper_extra' => $show_more_switch_field_string,
                                'field_params' => array(
                                    'std' => esc_attr($show_more_button),
                                    'id' => 'show_more_button',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'show_more_button[]',
                                    'return' => true,
                                    'extra_atr' => 'onchange="show_more_button_count' . $listing_rand_id . '(this.value)"',
                                    'options' => $show_more_listing_button_switch_options
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $show_more_button_hide_string = '';
                            if ($show_more_button == 'no' || $listing_view == 'v2') {
                                $show_more_button_hide_string = 'style="display:none;"';
                            }

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_show_more_url'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_show_more_url_hint'),
                                'echo' => true,
                                'main_wraper' => true,
                                'main_wraper_class' => 'show_more_button_dynamic_fields' . $listing_rand_id . '',
                                'main_wraper_extra' => $show_more_button_hide_string,
                                'field_params' => array(
                                    'std' => esc_attr($show_more_button_url),
                                    'id' => 'show_more_button_url',
                                    'cust_name' => 'show_more_button_url[]',
                                    'return' => true,
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            ?>
                            <script>
                                function listing_view<?php echo absint($listing_rand_id); ?>(view) {
                                    if (view === 'v2') {
                                        jQuery('.show_more_switch_field<?php echo absint($listing_rand_id); ?>').hide();
                                    } else {
                                        jQuery('.show_more_switch_field<?php echo absint($listing_rand_id); ?>').show();
                                    }
                                }
                                function show_more_button_count<?php echo absint($listing_rand_id); ?>(show_more_button_switcher) {
                                    if (show_more_button_switcher == 'no') {
                                        jQuery('.show_more_button_dynamic_fields<?php echo absint($listing_rand_id); ?>').hide();
                                    } else {
                                        jQuery('.show_more_button_dynamic_fields<?php echo absint($listing_rand_id); ?>').show();
                                    }
                                }
                                function listing_ads_count<?php echo absint($listing_rand_id); ?>($listing_ads_switcher) {
                                    if ($listing_ads_switcher == 'no') {
                                        jQuery('.listing_count_dynamic_fields<?php echo absint($listing_rand_id); ?>').hide();
                                    } else {
                                        jQuery('.listing_count_dynamic_fields<?php echo absint($listing_rand_id); ?>').show();
                                    }
                                }
                            </script>
                            <?php
                            $pagination_options = array('no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'));
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_members_pagination'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($pagination),
                                    'id' => 'pagination',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'pagination[]',
                                    'return' => true,
                                    'options' => $pagination_options
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_members_posts_per_page'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($posts_per_page),
                                    'id' => 'posts_per_page',
                                    'cust_name' => 'posts_per_page[]',
                                    'return' => true,
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            // add responsive fields				
do_action('wp_dp_shortcode_fields_render', $wp_dp_cs_output, array('responsive_fields' => true));

                            $wp_dp_cs_opt_array = array(
                                'std' => absint($listing_rand_id),
                                'id' => '',
                                'cust_id' => 'listing_counter',
                                'cust_name' => 'listing_counter[]',
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
                        <?php } else { ?>

                            <?php
                            $wp_dp_cs_opt_array = array(
                                'std' => 'wp_dp_listings_with_filters',
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
                                    'cust_id' => 'wp_dp_listings_with_filters_save',
                                    'cust_type' => 'button',
                                    'extra_atr' => 'onclick="javascript:_removerlay(jQuery(this))"',
                                    'classes' => 'cs-wp_dp_cs-admin-btn save_filters_listing_types_' . $listing_rand_id . ' save_listing_locations_' . $listing_rand_id . '',
                                    'cust_name' => 'wp_dp_listings_with_filters_save',
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

    add_action('wp_ajax_wp_dp_cs_var_page_builder_wp_dp_listings_with_filters', 'wp_dp_cs_var_page_builder_wp_dp_listings_with_filters');
}

if (!function_exists('wp_dp_cs_save_page_builder_data_wp_dp_listings_with_filters_callback')) {

    /**
     * Save data for wp_wp_dp_listings_with_filters shortcode.
     *
     * @param	array $args
     * @return	array
     */
    function wp_dp_cs_save_page_builder_data_wp_dp_listings_with_filters_callback($args) {

        $data = $args['data'];
        $counters = $args['counters'];
        $widget_type = $args['widget_type'];
        $column = $args['column'];
        $shortcode_data = '';
        if ($widget_type == "wp_dp_listings_with_filters" || $widget_type == "cs_wp_dp_listings_with_filters") {
            $wp_dp_cs_bareber_wp_dp_listings_with_filters = '';

            $page_element_size = $data['wp_dp_listings_with_filters_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_listings_with_filters']];
            $current_element_size = $data['wp_dp_listings_with_filters_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_listings_with_filters']];

            if (isset($data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']]) && $data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']] == 'shortcode') {
                $shortcode_str = stripslashes(( $data['shortcode']['wp_dp_listings_with_filters'][$counters['wp_dp_cs_shortcode_counter_wp_dp_listings_with_filters']]));

                $element_settings = 'wp_dp_listings_with_filters_element_size="' . $current_element_size . '"';
                $reg = '/wp_dp_listings_with_filters_element_size="(\d+)"/s';
                $shortcode_str = preg_replace($reg, $element_settings, $shortcode_str);
                $shortcode_data = $shortcode_str;
                $counters['wp_dp_cs_shortcode_counter_wp_dp_listings_with_filters'] ++;
            } else {
                $element_settings = 'wp_dp_listings_with_filters_element_size="' . htmlspecialchars($data['wp_dp_listings_with_filters_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_listings_with_filters']]) . '"';
                $wp_dp_cs_bareber_wp_dp_listings_with_filters = '[wp_dp_listings_with_filters ' . $element_settings . ' ';
                if (isset($data['filters_listings_title'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['filters_listings_title'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'filters_listings_title="' . htmlspecialchars($data['filters_listings_title'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                if (isset($data['wp_dp_filter_listings_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['wp_dp_filter_listings_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'wp_dp_filter_listings_element_title_color="' . htmlspecialchars($data['wp_dp_filter_listings_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                if (isset($data['wp_dp_filter_listings_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['wp_dp_filter_listings_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'wp_dp_filter_listings_seperator_style="' . htmlspecialchars($data['wp_dp_filter_listings_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                if (isset($data['wp_dp_filter_listings_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['wp_dp_filter_listings_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'wp_dp_filter_listings_element_subtitle_color="' . htmlspecialchars($data['wp_dp_filter_listings_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                if (isset($data['listings_filters_alagnment'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['listings_filters_alagnment'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'listings_filters_alagnment="' . htmlspecialchars($data['listings_filters_alagnment'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                if (isset($data['filters_listings_title_limit'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['filters_listings_title_limit'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'filters_listings_title_limit="' . htmlspecialchars($data['filters_listings_title_limit'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                if (isset($data['listings_subtitle'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['listings_subtitle'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'listings_subtitle="' . htmlspecialchars($data['listings_subtitle'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                if (isset($data['listing_view'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['listing_view'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'listing_view="' . htmlspecialchars($data['listing_view'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }

                if (isset($data['filters_listing_type'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['filters_listing_type'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'filters_listing_type="' . htmlspecialchars($data['filters_listing_type'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                if (isset($data['listing_no_custom_fields'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['listing_no_custom_fields'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'listing_no_custom_fields="' . htmlspecialchars($data['listing_no_custom_fields'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                // saving admin field using filter for add on
                $wp_dp_cs_bareber_wp_dp_listings_with_filters = apply_filters('wp_dp_save_listings_shortcode_admin_fields', $wp_dp_cs_bareber_wp_dp_listings_with_filters, $_POST, $counters['wp_dp_cs_counter_wp_dp_listings_with_filters']);
                if (isset($data['listing_urgent'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['listing_urgent'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'listing_urgent="' . htmlspecialchars($data['listing_urgent'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                if (isset($data['listing_ads_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['listing_ads_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'listing_ads_switch="' . htmlspecialchars($data['listing_ads_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                if (isset($data['listing_price_filter'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['listing_price_filter'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'listing_price_filter="' . htmlspecialchars($data['listing_price_filter'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                
                if (isset($data['listing_ads_after_list_count'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['listing_ads_after_list_count'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'listing_ads_after_list_count="' . htmlspecialchars($data['listing_ads_after_list_count'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                } 
                if (isset($data['listing_enquiry_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['listing_enquiry_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'listing_enquiry_switch="' . htmlspecialchars($data['listing_enquiry_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                
                if (isset($data['listing_notes_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['listing_notes_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'listing_notes_switch="' . htmlspecialchars($data['listing_notes_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                if (isset($data['posts_per_page'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['posts_per_page'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'posts_per_page="' . htmlspecialchars($data['posts_per_page'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                if (isset($data['pagination'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['pagination'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'pagination="' . htmlspecialchars($data['pagination'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                if (isset($data['show_more_button'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['show_more_button'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'show_more_button="' . htmlspecialchars($data['show_more_button'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                if (isset($data['show_more_button_url'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['show_more_button_url'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'show_more_button_url="' . htmlspecialchars($data['show_more_button_url'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                if (isset($data['listing_location'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['listing_location'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= 'listing_location="' . htmlspecialchars($data['listing_location'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . '" ';
                }
                
                  // Apply filter on default attributes Saving
                $section_title = apply_filters('wp_dp_shortcode_default_atts_save', $wp_dp_cs_bareber_wp_dp_listings_with_filters, $data, $counters['wp_dp_cs_counter_wp_dp_listings_with_filters'], array( 'responsive_atts' => true ));
                
                $wp_dp_cs_bareber_wp_dp_listings_with_filters = $section_title;
                
                $wp_dp_cs_bareber_wp_dp_listings_with_filters .= ']';
                if (isset($data['wp_dp_listings_with_filters_column_text'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']]) && $data['wp_dp_listings_with_filters_column_text'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_with_filters .= htmlspecialchars($data['wp_dp_listings_with_filters_column_text'][$counters['wp_dp_cs_counter_wp_dp_listings_with_filters']], ENT_QUOTES) . ' ';
                }
                $wp_dp_cs_bareber_wp_dp_listings_with_filters .= '[/wp_dp_listings_with_filters]';
                $shortcode_data .= $wp_dp_cs_bareber_wp_dp_listings_with_filters;
                $counters['wp_dp_cs_counter_wp_dp_listings_with_filters'] ++;
            }
            $counters['wp_dp_cs_global_counter_wp_dp_listings_with_filters'] ++;
        }
        return array(
            'data' => $data,
            'counters' => $counters,
            'widget_type' => $widget_type,
            'column' => $shortcode_data,
        );
    }

    add_filter('wp_dp_cs_save_page_builder_data_wp_dp_listings_with_filters', 'wp_dp_cs_save_page_builder_data_wp_dp_listings_with_filters_callback');
}

if (!function_exists('wp_dp_cs_load_shortcode_counters_wp_dp_listings_with_filters_callback')) {

    /**
     * Populate wp_dp_listings_with_filters shortcode counter variables.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_load_shortcode_counters_wp_dp_listings_with_filters_callback($counters) {
        $counters['wp_dp_cs_global_counter_wp_dp_listings_with_filters'] = 0;
        $counters['wp_dp_cs_shortcode_counter_wp_dp_listings_with_filters'] = 0;
        $counters['wp_dp_cs_counter_wp_dp_listings_with_filters'] = 0;
        return $counters;
    }

    add_filter('wp_dp_cs_load_shortcode_counters', 'wp_dp_cs_load_shortcode_counters_wp_dp_listings_with_filters_callback');
}



if (!function_exists('wp_dp_cs_element_list_populate_wp_dp_listings_with_filters_callback')) {

    /**
     * Populate wp_dp_listings_with_filters shortcode strings list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_element_list_populate_wp_dp_listings_with_filters_callback($element_list) {
        $element_list['wp_dp_listings_with_filters'] = wp_dp_plugin_text_srt('wp_dp_listings_with_filters_heading');
        return $element_list;
    }

    add_filter('wp_dp_cs_element_list_populate', 'wp_dp_cs_element_list_populate_wp_dp_listings_with_filters_callback');
}

if (!function_exists('wp_dp_cs_shortcode_names_list_populate_wp_dp_listings_with_filters_callback')) {

    /**
     * Populate wp_dp_listings_with_filters shortcode names list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_shortcode_names_list_populate_wp_dp_listings_with_filters_callback($shortcode_array) {
        $shortcode_array['wp_dp_listings_with_filters'] = array(
            'title' => wp_dp_plugin_text_srt('wp_dp_listings_with_filters_heading'),
            'name' => 'wp_dp_listings_with_filters',
            'icon' => 'icon-filter',
            'categories' => 'typography',
        );

        return $shortcode_array;
    }

    add_filter('wp_dp_cs_shortcode_names_list_populate', 'wp_dp_cs_shortcode_names_list_populate_wp_dp_listings_with_filters_callback');
}
