<?php
/**
 * Shortcode Name : wp_dp_listings_with_categories
 *
 * @package	wp_dp_cs 
 */
if ( ! function_exists('wp_dp_cs_var_page_builder_wp_dp_listings_with_categories') ) {

    function wp_dp_cs_var_page_builder_wp_dp_listings_with_categories($die = 0) {
        global $post, $wp_dp_html_fields, $wp_dp_cs_node, $wp_dp_form_fields;
        if ( function_exists('wp_dp_cs_shortcode_names') ) {
            $shortcode_element = '';
            $filter_element = 'filterdrag';
            $shortcode_view = '';
            $wp_dp_cs_output = array();
            $wp_dp_cs_PREFIX = 'wp_dp_listings_with_categories';
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
                'categories_listings_title' => '',
                'categories_listings_title_limit' => '',
                'listings_subtitle' => '',
                'listings_filters_alagnment' => '',
                'listing_types' => array(),
                'listing_type' => array(),
                'listing_category' => array(),
                'listing_view' => '',
                'listing_sort_by' => 'no',
                'listing_featured' => 'no',
                //'listing_ads_switch' => 'no',
                'listing_price_filter' => 'yes',
                'listing_ads_after_list_count' => '5',
                'listing_location' => '',
                'posts_per_page' => '6',
                //'pagination' => '',
                'show_more_button' => 'no',
                'show_more_button_url' => '',
                'listing_no_custom_fields' => '3',
                'wp_dp_listing_categories_element_title_color' => '',
                'wp_dp_listing_categories_element_subtitle_color' => '',
                'wp_dp_listings_with_categories_seperator_style' => '',
                //'listing_enquiry_switch' => 'no',
                //'listing_notes_switch' => 'no',
            );
            $defaults = apply_filters('wp_dp_listings_shortcode_admin_default_attributes', $defaults);
            
            // Apply filter on default attributes
            $defaults = apply_filters('wp_dp_shortcode_default_atts', $defaults, array('responsive_atts' => true));
            
            if ( isset($wp_dp_cs_output['0']['atts']) ) {
                $atts = $wp_dp_cs_output['0']['atts'];
            } else {
                $atts = array();
            }
            if ( isset($wp_dp_cs_output['0']['content']) ) {
                $wp_dp_listings_with_categories_column_text = $wp_dp_cs_output['0']['content'];
            } else {
                $wp_dp_listings_with_categories_column_text = '';
            }
            $wp_dp_listings_with_categories_element_size = '100';
            foreach ( $defaults as $key => $values ) {
                if ( isset($atts[$key]) ) {
                    $$key = $atts[$key];
                } else {
                    $$key = $values;
                }
            }
            $name = 'wp_dp_cs_var_page_builder_wp_dp_listings_with_categories';
            $coloumn_class = 'column_' . $wp_dp_listings_with_categories_element_size;
            if ( isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode' ) {
                $shortcode_element = 'shortcode_element_class';
                $shortcode_view = 'cs-pbwp-shortcode';
                $filter_element = 'ajax-drag';
                $coloumn_class = '';
            }
            $listing_rand_id = rand(4444, 99999);
            wp_enqueue_script('wp_dp_cs-admin-upload');
            $listing_views = array(
                'grid' => wp_dp_plugin_text_srt('wp_dp_element_view_grid'),
                'grid-modern' => wp_dp_plugin_text_srt('wp_dp_element_view_gid_modern'),
            );
            ?>

            <div id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class); ?>
                 <?php echo esc_attr($shortcode_view); ?>" item="wp_dp_listings_with_categories" data="<?php echo wp_dp_cs_element_size_data_array_index($wp_dp_listings_with_categories_element_size) ?>" >
                     <?php wp_dp_cs_element_setting($name, $wp_dp_cs_counter, $wp_dp_listings_with_categories_element_size) ?>
                <div class="cs-wrapp-class-<?php echo intval($wp_dp_cs_counter) ?>
                     <?php echo esc_attr($shortcode_element); ?>" id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>" data-shortcode-template="[wp_dp_listings_with_categories {{attributes}}]{{content}}[/wp_dp_listings_with_categories]" style="display: none;">
                    <div class="cs-heading-area" data-counter="<?php echo esc_attr($wp_dp_cs_counter) ?>">
                        <h5><?php echo wp_dp_plugin_text_srt('wp_dp_listings_with_categories_options'); ?></h5>
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
                                    'std' => esc_attr($categories_listings_title),
                                    'id' => 'categories_listings_title',
                                    'cust_name' => 'categories_listings_title[]',
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
                                    'std' => $wp_dp_listing_categories_element_title_color,
                                    'cust_name' => 'wp_dp_listing_categories_element_title_color[]',
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
                                    'std' => $wp_dp_listing_categories_element_subtitle_color,
                                    'cust_name' => 'wp_dp_listing_categories_element_subtitle_color[]',
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
                                    'std' => esc_attr($wp_dp_listings_with_categories_seperator_style),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'wp_dp_listings_with_categories_seperator_style[]',
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
                                'name' => wp_dp_plugin_text_srt('wp_dp_title_align'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_title_align_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listing_view),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'listing_view[]',
                                    'return' => true,
                                    'options' => $listing_views,
                                ),
                            );

                            $wp_dp_post_listing_types = new Wp_dp_Post_Listing_Types();
                            $listing_types_array = $wp_dp_post_listing_types->wp_dp_types_array_callback('NULL');
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listing_type),
                                    'id' => 'listing_types_' . $listing_rand_id,
                                    'classes' => 'chosen-select get-listing-cats-by-type',
                                    'cust_name' => 'listing_types_all[]',
                                    'extra_atr' => 'data-id="' . $listing_rand_id . '"',
                                    'return' => true,
                                    'options' => $listing_types_array
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                            ?>
                            <div class="type-categories-selection"><?php echo get_listing_cats_by_type_callback($listing_type, $listing_category, false, $listing_rand_id); ?></div>
                            <script>
                                jQuery(document).ready(function () {
                                   
                                    jQuery(".save_listing_types_<?php echo absint($listing_rand_id); ?>").click(function () {
                                        var MY_SELECT = jQuery('#wp_dp_listing_types_<?php echo absint($listing_rand_id); ?>').val();
                                        var listing_type_value = MY_SELECT;
                                        jQuery('#listing_type_<?php echo absint($listing_rand_id); ?>').val(listing_type_value);
                                    });

                                });
                            </script>
                            <?php
                            $saved_listing_type = $listing_type;

                            $wp_dp_cs_opt_array = array(
                                'std' => $listing_type,
                                'cust_id' => 'listing_type_' . $listing_rand_id . '',
                                'cust_name' => "listing_type[]",
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
                                    'std' => esc_attr($categories_listings_title_limit),
                                    'id' => 'categories_listings_title_limit',
                                    'cust_name' => 'categories_listings_title_limit[]',
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
                          //  $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                            
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
                           // $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            do_action('wp_dp_compare_listings_element_field', $atts);

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
                           // $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);



                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_price_filters'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listing_price_filter),
                                    'id' => 'listing_price_filter[]',
                                    'cust_name' => 'listing_price_filter[]',
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

                            $listing_count_hide_string = '';
                            if ( $listing_ads_switch == 'no' ) {
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

                            //$wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
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

                            if ( $saved_listing_location != '' ) {
                                $listing_locations = explode(',', $saved_listing_location);
                                foreach ( $listing_locations as $listing_loc ) {
                                    $get_listing_locations[$listing_loc] = $listing_location_options[$listing_loc];
                                }
                            }
                            if ( $get_listing_locations ) {
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

                            do_action('wp_wp_dp_listings_with_categories_shortcode_admin_fields', array( 'wp_dp_listing_type' => $wp_dp_listing_type, 'listing_alert_button' => $listing_alert_button ));

                            $show_more_listing_button_switch_options = array( 'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes') );
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_show_more_switch'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
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

                           // $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $show_more_button_hide_string = '';
                            if ( $show_more_button == 'no' ) {
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

                            //$wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            ?>
                            <script>
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
                            $pagination_options = array( 'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'), 'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes') );
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
                                'std' => 'wp_dp_listings_with_categories',
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
                                    'cust_id' => 'wp_dp_listings_with_categories_save',
                                    'cust_type' => 'button',
                                    'extra_atr' => 'onclick="javascript:_removerlay(jQuery(this))"',
                                    'classes' => 'wp_dp_listings_with_categories_save_' . $listing_rand_id .' cs-wp_dp_cs-admin-btn save_listing_types_' . $listing_rand_id . ' save_listing_locations_' . $listing_rand_id . '',
                                    'cust_name' => 'wp_dp_listings_with_categories_save',
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

    add_action('wp_ajax_wp_dp_cs_var_page_builder_wp_dp_listings_with_categories', 'wp_dp_cs_var_page_builder_wp_dp_listings_with_categories');
}

if ( ! function_exists('wp_dp_cs_save_page_builder_data_wp_dp_listings_with_categories_callback') ) {

    /**
     * Save data for wp_wp_dp_listings_with_categories shortcode.
     *
     * @param	array $args
     * @return	array
     */
    function wp_dp_cs_save_page_builder_data_wp_dp_listings_with_categories_callback($args) {

        $data = $args['data'];
        $counters = $args['counters'];
        $widget_type = $args['widget_type'];
        $column = $args['column'];
        $shortcode_data = '';
        if ( $widget_type == "wp_dp_listings_with_categories" || $widget_type == "cs_wp_dp_listings_with_categories" ) {
            $wp_dp_cs_bareber_wp_dp_listings_with_categories = '';

            $page_element_size = $data['wp_dp_listings_with_categories_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_listings_with_categories']];
            $current_element_size = $data['wp_dp_listings_with_categories_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_listings_with_categories']];

            if ( isset($data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']]) && $data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']] == 'shortcode' ) {
                $shortcode_str = stripslashes(( $data['shortcode']['wp_dp_listings_with_categories'][$counters['wp_dp_cs_shortcode_counter_wp_dp_listings_with_categories']]));

                $element_settings = 'wp_dp_listings_with_categories_element_size="' . $current_element_size . '"';
                $reg = '/wp_dp_listings_with_categories_element_size="(\d+)"/s';
                $shortcode_str = preg_replace($reg, $element_settings, $shortcode_str);
                $shortcode_data = $shortcode_str;
                $counters['wp_dp_cs_shortcode_counter_wp_dp_listings_with_categories'] ++;
            } else {
                $element_settings = 'wp_dp_listings_with_categories_element_size="' . htmlspecialchars($data['wp_dp_listings_with_categories_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_listings_with_categories']]) . '"';
                $wp_dp_cs_bareber_wp_dp_listings_with_categories = '[wp_dp_listings_with_categories ' . $element_settings . ' ';
                if ( isset($data['categories_listings_title'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['categories_listings_title'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'categories_listings_title="' . htmlspecialchars($data['categories_listings_title'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['wp_dp_listing_categories_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['wp_dp_listing_categories_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'wp_dp_listing_categories_element_title_color="' . htmlspecialchars($data['wp_dp_listing_categories_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['wp_dp_listing_categories_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['wp_dp_listing_categories_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'wp_dp_listing_categories_element_subtitle_color="' . htmlspecialchars($data['wp_dp_listing_categories_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['listings_filters_alagnment'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['listings_filters_alagnment'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'listings_filters_alagnment="' . htmlspecialchars($data['listings_filters_alagnment'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['wp_dp_listings_with_categories_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['wp_dp_listings_with_categories_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'wp_dp_listings_with_categories_seperator_style="' . htmlspecialchars($data['wp_dp_listings_with_categories_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['categories_listings_title_limit'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['categories_listings_title_limit'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'categories_listings_title_limit="' . htmlspecialchars($data['categories_listings_title_limit'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['listings_subtitle'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['listings_subtitle'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'listings_subtitle="' . htmlspecialchars($data['listings_subtitle'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['listing_view'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['listing_view'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'listing_view="' . htmlspecialchars($data['listing_view'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['listing_type'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['listing_type'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'listing_type="' . htmlspecialchars($data['listing_type'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['listing_category'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['listing_category'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'listing_category="' . htmlspecialchars($data['listing_category'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['listing_no_custom_fields'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['listing_no_custom_fields'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'listing_no_custom_fields="' . htmlspecialchars($data['listing_no_custom_fields'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
                }
                // saving admin field using filter for add on
                $wp_dp_cs_bareber_wp_dp_listings_with_categories = apply_filters('wp_dp_save_listings_shortcode_admin_fields', $wp_dp_cs_bareber_wp_dp_listings_with_categories, $_POST, $counters['wp_dp_cs_counter_wp_dp_listings_with_categories']);
                if ( isset($data['listing_featured'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['listing_featured'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'listing_featured="' . htmlspecialchars($data['listing_featured'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
                }
//                if ( isset($data['listing_ads_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['listing_ads_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
//                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'listing_ads_switch="' . htmlspecialchars($data['listing_ads_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
//                }
                if ( isset($data['listing_price_filter'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['listing_price_filter'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'listing_price_filter="' . htmlspecialchars($data['listing_price_filter'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
                }
                
//                if ( isset($data['listing_ads_after_list_count'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['listing_ads_after_list_count'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
//                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'listing_ads_after_list_count="' . htmlspecialchars($data['listing_ads_after_list_count'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
//                }
//                if ( isset($data['listing_enquiry_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['listing_enquiry_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
//                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'listing_enquiry_switch="' . htmlspecialchars($data['listing_enquiry_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
//                }
                
//                if ( isset($data['listing_notes_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['listing_notes_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
//                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'listing_notes_switch="' . htmlspecialchars($data['listing_notes_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
//                }
                if ( isset($data['posts_per_page'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['posts_per_page'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'posts_per_page="' . htmlspecialchars($data['posts_per_page'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['pagination'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['pagination'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'pagination="' . htmlspecialchars($data['pagination'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
                }
//                if ( isset($data['show_more_button'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['show_more_button'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
//                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'show_more_button="' . htmlspecialchars($data['show_more_button'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
//                }
//                if ( isset($data['show_more_button_url'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['show_more_button_url'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
//                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'show_more_button_url="' . htmlspecialchars($data['show_more_button_url'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
//                }
                if ( isset($data['listing_location'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['listing_location'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= 'listing_location="' . htmlspecialchars($data['listing_location'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . '" ';
                }
                
                
                 // Apply filter on default attributes Saving
                 $section_title = apply_filters('wp_dp_shortcode_default_atts_save', $wp_dp_cs_bareber_wp_dp_listings_with_categories, $data, $counters['wp_dp_cs_counter_wp_dp_listings_with_categories'], array( 'responsive_atts' => true ));
                
                $wp_dp_cs_bareber_wp_dp_listings_with_categories = $section_title;
                $wp_dp_cs_bareber_wp_dp_listings_with_categories .= ']';
                if ( isset($data['wp_dp_listings_with_categories_column_text'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']]) && $data['wp_dp_listings_with_categories_column_text'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listings_with_categories .= htmlspecialchars($data['wp_dp_listings_with_categories_column_text'][$counters['wp_dp_cs_counter_wp_dp_listings_with_categories']], ENT_QUOTES) . ' ';
                }
                $wp_dp_cs_bareber_wp_dp_listings_with_categories .= '[/wp_dp_listings_with_categories]';
                $shortcode_data .= $wp_dp_cs_bareber_wp_dp_listings_with_categories;
                $counters['wp_dp_cs_counter_wp_dp_listings_with_categories'] ++;
            }
            $counters['wp_dp_cs_global_counter_wp_dp_listings_with_categories'] ++;
        }
        return array(
            'data' => $data,
            'counters' => $counters,
            'widget_type' => $widget_type,
            'column' => $shortcode_data,
        );
    }

    add_filter('wp_dp_cs_save_page_builder_data_wp_dp_listings_with_categories', 'wp_dp_cs_save_page_builder_data_wp_dp_listings_with_categories_callback');
}

if ( ! function_exists('wp_dp_cs_load_shortcode_counters_wp_dp_listings_with_categories_callback') ) {

    /**
     * Populate wp_dp_listings_with_categories shortcode counter variables.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_load_shortcode_counters_wp_dp_listings_with_categories_callback($counters) {
        $counters['wp_dp_cs_global_counter_wp_dp_listings_with_categories'] = 0;
        $counters['wp_dp_cs_shortcode_counter_wp_dp_listings_with_categories'] = 0;
        $counters['wp_dp_cs_counter_wp_dp_listings_with_categories'] = 0;
        return $counters;
    }

    add_filter('wp_dp_cs_load_shortcode_counters', 'wp_dp_cs_load_shortcode_counters_wp_dp_listings_with_categories_callback');
}



if ( ! function_exists('wp_dp_cs_element_list_populate_wp_dp_listings_with_categories_callback') ) {

    /**
     * Populate wp_dp_listings_with_categories shortcode strings list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_element_list_populate_wp_dp_listings_with_categories_callback($element_list) {
        $element_list['wp_dp_listings_with_categories'] = wp_dp_plugin_text_srt('wp_dp_listings_with_categories_heading');
        return $element_list;
    }

    add_filter('wp_dp_cs_element_list_populate', 'wp_dp_cs_element_list_populate_wp_dp_listings_with_categories_callback');
}

if ( ! function_exists('wp_dp_cs_shortcode_names_list_populate_wp_dp_listings_with_categories_callback') ) {

    /**
     * Populate wp_dp_listings_with_categories shortcode names list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_shortcode_names_list_populate_wp_dp_listings_with_categories_callback($shortcode_array) {
        $shortcode_array['wp_dp_listings_with_categories'] = array(
            'title' => wp_dp_plugin_text_srt('wp_dp_listings_with_categories_heading'),
            'name' => 'wp_dp_listings_with_categories',
            'icon' => 'icon-list',
            'categories' => 'typography',
        );

        return $shortcode_array;
    }

    add_filter('wp_dp_cs_shortcode_names_list_populate', 'wp_dp_cs_shortcode_names_list_populate_wp_dp_listings_with_categories_callback');
}


if ( ! function_exists('get_listing_cats_by_type_callback') ) {

    function get_listing_cats_by_type_callback($listing_type = '', $listing_category = '', $is_ajax = true, $listing_element_rand_id = '') {
        global $wp_dp_html_fields, $wp_dp_form_fields;
        $listing_element_rand_id = isset($_POST['listing_element_rand_id']) ? $_POST['listing_element_rand_id'] : $listing_element_rand_id;
        $listing_type = isset($_POST['listing_type']) ? $_POST['listing_type'] : $listing_type;
        $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type", 'post_status' => 'publish' ));
        $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
        $categories = get_post_meta($listing_type_id, 'wp_dp_listing_type_cats', true);
        $categories_array = array();
        if ( ! empty($categories) ) {
            foreach ( $categories as $category ) {
                $term_obj = get_term_by('slug', $category, 'listing-category');
                $categories_array[$category] = $term_obj->name;
            }
        }
        $listing_rand_id = rand(1000, 9999);

        $saved_listing_category = $listing_category;

        $listing_categories_options = $categories_array;

        if ( $listing_category != '' ) {
            $listing_categories = explode(',', $listing_category);
            foreach ( $listing_categories as $listing_category ) {
                $get_listing_categories[$listing_category] = $listing_categories_options[$listing_category];
            }
        }
        if ( $get_listing_categories ) {
            $listing_categories_options = array_unique(array_merge($get_listing_categories, $listing_categories_options));
        } else {
            $listing_categories_options = $listing_categories_options;
        }
         ?>
        <script>
            jQuery(document).ready(function () {
                chosen_selectionbox();
                jQuery(".wp_dp_listings_with_categories_save_<?php echo wp_dp_cs_allow_special_char($listing_element_rand_id); ?>").click(function () {
                    var MY_SELECT = jQuery('#wp_dp_listing_categories_<?php echo absint($listing_rand_id); ?>').get(0);
                    if (typeof MY_SELECT != 'undefined') {
                        var selection = ChosenOrder.getSelectionOrder(MY_SELECT);
                        var listing_category_value = '';
                        var comma = '';
                        jQuery(selection).each(function (i) {
                            listing_category_value = listing_category_value + comma + selection[i];
                            comma = ',';
                        });
                        jQuery('#listing_category_<?php echo absint($listing_rand_id); ?>').val(listing_category_value);
                    }
                });
            });
        </script>
        <?php
        $wp_dp_opt_array = array(
            'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_categories'),
            'desc' => '',
            'label_desc' => '',
            'multi' => true,
            'echo' => true,
            'field_params' => array(
                'std' => $saved_listing_category,
                'id' => 'listing_categories_' . $listing_rand_id . '',
                'classes' => 'chosen-select-no-single',
                'cust_name' => 'listing_categories[]',
                'multi' => true,
                'return' => true,
                'options' => $listing_categories_options,
            ),
        );
        $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
       
        $wp_dp_cs_opt_array = array(
            'std' => $listing_category,
            'cust_id' => 'listing_category_' . $listing_rand_id . '',
            'cust_name' => "listing_category[]",
            'required' => false
        );
        $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_cs_opt_array);

        if ( $is_ajax == true ) {
            wp_die();
        }
    }

    add_action('wp_ajax_get_listing_cats_by_type', 'get_listing_cats_by_type_callback');
}