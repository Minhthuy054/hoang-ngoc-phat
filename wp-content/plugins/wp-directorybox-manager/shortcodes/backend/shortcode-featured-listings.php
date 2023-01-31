<?php
/**
 * Shortcode Name : wp_dp_featured_listings
 *
 * @package	wp_dp_cs 
 */
if ( ! function_exists('wp_dp_cs_var_page_builder_wp_dp_featured_listings') ) {

    function wp_dp_cs_var_page_builder_wp_dp_featured_listings($die = 0) {
        global $post, $wp_dp_html_fields, $wp_dp_cs_node, $wp_dp_form_fields;
        if ( function_exists('wp_dp_cs_shortcode_names') ) {
            $shortcode_element = '';
            $filter_element = 'filterdrag';
            $shortcode_view = '';
            $wp_dp_cs_output = array();
            $wp_dp_cs_PREFIX = 'wp_dp_featured_listings';
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
                'featured_listings_title' => '',
                'listings_subtitle' => '',
                'featured_listings_title_limit' => '20',
                'featured_listings_title_alignment' => '',
                'listings_content_limit' => '100',
                'listing_type' => '',
                'filters_listing_type'=>array(),
                'listing_category' => array(),
                'listing_view' => '',
                'listing_urgent' => 'no',
                'listing_ads_switch' => 'no',
                'listing_ads_after_list_count' => '5',
                'listing_location' => '',
                'posts_per_page' => '6',
                'listing_top_category' => 'no',
                'listing_top_category_count' => '',
                'listing_no_custom_fields' => '3',
                'wp_dp_featured_listing_element_subtitle_color' => '',
                'wp_dp_featured_listing_element_title_color' => '',
                'wp_dp_featured_listings_seperator_style' => '',
                'listing_enquiry_switch' => 'no',
                'listing_hide_switch' => 'no',
                'listing_notes_switch' => 'no',
                'listing_slider_switch'=>'yes',
            );
            $defaults = apply_filters('wp_dp_listings_shortcode_admin_default_attributes', $defaults);

            // Apply filter on default attributes
            $defaults = apply_filters('wp_dp_shortcode_default_atts', $defaults, array( 'responsive_atts' => true ));

            if ( isset($wp_dp_cs_output['0']['atts']) ) {
                $atts = $wp_dp_cs_output['0']['atts'];
            } else {
                $atts = array();
            }
            if ( isset($wp_dp_cs_output['0']['content']) ) {
                $wp_dp_featured_listings_column_text = $wp_dp_cs_output['0']['content'];
            } else {
                $wp_dp_featured_listings_column_text = '';
            }
            $wp_dp_featured_listings_element_size = '100';
            foreach ( $defaults as $key => $values ) {
                if ( isset($atts[$key]) ) {
                    $$key = $atts[$key];
                } else {
                    $$key = $values;
                }
            }
            $name = 'wp_dp_cs_var_page_builder_wp_dp_featured_listings';
            $coloumn_class = 'column_' . $wp_dp_featured_listings_element_size;
            if ( isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode' ) {
                $shortcode_element = 'shortcode_element_class';
                $shortcode_view = 'cs-pbwp-shortcode';
                $filter_element = 'ajax-drag';
                $coloumn_class = '';
            }
            $listing_rand_id = rand(4444, 99999);
            wp_enqueue_script('wp_dp_cs-admin-upload');
            $listing_views = array(
                'single' => wp_dp_plugin_text_srt('wp_dp_element_view_single_listing'),
                'multiple' => wp_dp_plugin_text_srt('wp_dp_element_view_multiple_listings'),
            );
            ?>

            <div id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class); ?>
                 <?php echo esc_attr($shortcode_view); ?>" item="wp_dp_featured_listings" data="<?php echo wp_dp_cs_element_size_data_array_index($wp_dp_featured_listings_element_size) ?>" >
                     <?php wp_dp_cs_element_setting($name, $wp_dp_cs_counter, $wp_dp_featured_listings_element_size) ?>
                <div class="cs-wrapp-class-<?php echo intval($wp_dp_cs_counter) ?>
                     <?php echo esc_attr($shortcode_element); ?>" id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>" data-shortcode-template="[wp_dp_featured_listings {{attributes}}]{{content}}[/wp_dp_featured_listings]" style="display: none;">
                    <div class="cs-heading-area" data-counter="<?php echo esc_attr($wp_dp_cs_counter) ?>">
                        <h5><?php echo wp_dp_plugin_text_srt('wp_dp_shortcode_featured_listings_options'); ?></h5>
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
                                'label_desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_element_title_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($featured_listings_title),
                                    'id' => 'featured_listings_title',
                                    'cust_name' => 'featured_listings_title[]',
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
                                    'std' => esc_attr($featured_listings_title_alignment),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'featured_listings_title_alignment[]',
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
                                    'std' => $wp_dp_featured_listing_element_title_color,
                                    'cust_name' => 'wp_dp_featured_listing_element_title_color[]',
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
                                    'std' => $wp_dp_featured_listing_element_subtitle_color,
                                    'cust_name' => 'wp_dp_featured_listing_element_subtitle_color[]',
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
                                    'std' => esc_attr($wp_dp_featured_listings_seperator_style),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'wp_dp_featured_listings_seperator_style[]',
                                    'return' => true,
                                    'options' => array(
                                        '' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_seperator_style_none'),
                                        'classic' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_seperator_style_classic'),
                                        'zigzag' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_seperator_style_zigzag'),
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
                            //$wp_dp_html_fields->wp_dp_select_field( $wp_dp_opt_array );
                            ?>
                            <script>
                                jQuery(document).ready(function () {
                                    jQuery(".wp_dp_listings_with_categories_save_<?php echo absint($listing_rand_id); ?>").click(function () {
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
                                    
                                    function listing_view<?php echo absint($listing_rand_id); ?>($view) {
                                        if ($view == 'single') {
                                            jQuery('.listing_content_langth<?php echo absint($listing_rand_id); ?>').show();
                                            jQuery('.multiple-listings-fields-<?php echo absint($listing_rand_id); ?>').hide();
                                        } else {
                                            jQuery('.listing_content_langth<?php echo absint($listing_rand_id); ?>').hide();
                                            jQuery('.multiple-listings-fields-<?php echo absint($listing_rand_id); ?>').show();
                                        }
                                    }
                                    function listing_ads_count<?php echo absint($listing_rand_id); ?>($listing_ads_switcher) {
                                        if ($listing_ads_switcher == 'no') {
                                            jQuery('.listing_count_dynamic_fields<?php echo absint($listing_rand_id); ?>').hide();
                                        } else {
                                            jQuery('.listing_count_dynamic_fields<?php echo absint($listing_rand_id); ?>').show();
                                        }
                                    }
                                    
                                    

                                });
                            </script>
                            <?php
                            $saved_filters_listing_type = $filters_listing_type;
                            $filters_listing_type_options = $filters_listing_types_array;

                            if ($filters_listing_type != '') {
                                $filters_listing_types = explode(',', $filters_listing_type);
                                foreach ($filters_listing_types as $filters_listing_type) {
                                    $get_filters_listing_types[$filters_listing_type] = $filters_listing_type_options[$filters_listing_type];
                                }
                            }
                            if ($get_filters_listing_types) {
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
                            
                            $listing_element_rand_id = isset( $listing_element_rand_id )? $listing_element_rand_id : '';
                            ?>
                            <script>
                                jQuery(document).ready(function () {
                                    chosen_selectionbox();
                                    jQuery(".wp_dp_listings_with_categories_save_<?php echo wp_dp_cs_allow_special_char($listing_element_rand_id); ?>").click(function () {

                                        var MY_SELECT = jQuery('#listing_types_<?php echo absint($listing_rand_id); ?>').get(0);
                                        if (typeof MY_SELECT != 'undefined') {
                                            var selection = ChosenOrder.getSelectionOrder(MY_SELECT);
                                            var listing_category_value = '';
                                            var comma = '';
                                            jQuery(selection).each(function (i) {
                                                listing_category_value = listing_category_value + comma + selection[i];
                                                comma = ',';
                                            });
                                            jQuery('#listing_type_<?php echo absint($listing_rand_id); ?>').val(listing_category_value);
                                        }
                                    });
                                });
                            </script>
                            <?php
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
                                    'return' => true,
                                    'extra_atr' => 'onchange="listing_view' . $listing_rand_id . '(this.value)"',
                                    'options' => $listing_views
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                            
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_mmultiple_listing_slider_switdh'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_mmultiple_listing_slider_switdh_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listing_slider_switch),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'listing_slider_switch[]',
                                    'return' => true,
                                    'extra_atr' => '',
                                    'options' => array(
                                            'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                            'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        ),
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                            
                            
                            
                            

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_title_length'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($featured_listings_title_limit),
                                    'id' => 'featured_listings_title_limit',
                                    'cust_name' => 'featured_listings_title_limit[]',
                                    'return' => true,
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

                            $listing_content_display = '';
                            if ( $listing_view == 'multiple' ) {
                                $listing_content_display = 'style="display:none;"';
                            }
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_content_length'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'main_wraper' => true,
                                'main_wraper_class' => 'listing_content_langth' . $listing_rand_id . '',
                                'main_wraper_extra' => $listing_content_display,
                                'field_params' => array(
                                    'std' => esc_attr($listings_content_limit),
                                    'id' => 'listings_content_limit_' . $listing_rand_id,
                                    'cust_name' => 'listings_content_limit[]',
                                    'return' => true,
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            ?>                          
                            <script>
                                function listing_top_category_count<?php echo absint($listing_rand_id); ?>($listing_top_category) {
                                    if ($listing_top_category == 'no') {
                                        jQuery('.listing_top_category_count_dynamic_fields<?php echo absint($listing_rand_id); ?>').hide();
                                    } else {
                                        jQuery('.listing_top_category_count_dynamic_fields<?php echo absint($listing_rand_id); ?>').show();
                                    }
                                }
                                function listing_recent_count<?php echo absint($listing_rand_id); ?>($listing_recent_switch) {
                                    if ($listing_recent_switch == 'no') {
                                        jQuery('.listing_recent_count_dynamic_fields<?php echo absint($listing_rand_id); ?>').hide();
                                    } else {
                                        jQuery('.listing_recent_count_dynamic_fields<?php echo absint($listing_rand_id); ?>').show();
                                    }
                                }
                            </script>
            <?php
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_list_meta_top_category'),
                'desc' => '',
                'label_desc' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => esc_attr($listing_top_category),
                    'id' => 'listing_top_category[]',
                    'cust_name' => 'listing_top_category[]',
                    'return' => true,
                    'classes' => 'chosen-select-no-single',
                    'extra_atr' => 'onchange="listing_top_category_count' . $listing_rand_id . '(this.value)"',
                    'options' => array(
                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                    ),
                ),
            );
            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
            $listing_top_category_count_hide_string = '';
            if ( $listing_top_category == 'no' ) {
                $listing_top_category_count_hide_string = 'style="display:none;"';
            }
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_top_category_count'),
                'desc' => '',
                'label_desc' => '',
                'echo' => true,
                'main_wraper' => true,
                'main_wraper_class' => 'listing_top_category_count_dynamic_fields' . $listing_rand_id . '',
                'main_wraper_extra' => $listing_top_category_count_hide_string,
                'field_params' => array(
                    'std' => esc_attr($listing_top_category_count),
                    'id' => 'listing_top_category_count',
                    'cust_name' => 'listing_top_category_count[]',
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
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

            $multiple_fields_display = '';
            if ( $listing_view == 'single' || $listing_view == '' ) {
                $multiple_fields_display = 'style="display:none;"';
            }
            echo '<div class="multiple-listings-fields-' . $listing_rand_id . '" ' . $multiple_fields_display . '>';

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
                    'extra_atr' => 'data-placeholder="' . wp_dp_plugin_text_srt('wp_dp_select_location_option') . '"',
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

            echo '</div>';

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
            do_action('wp_dp_shortcode_fields_render', $wp_dp_cs_output, array( 'responsive_fields' => true ));
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
                                'std' => 'wp_dp_featured_listings',
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
                                    'cust_id' => 'wp_dp_featured_listings_save',
                                    'cust_type' => 'button',
                                    'extra_atr' => 'onclick="javascript:_removerlay(jQuery(this))"',
                                    'classes' => 'cs-wp_dp_cs-admin-btn wp_dp_listings_with_categories_save_' . $listing_rand_id . '  save_listing_locations_' . $listing_rand_id . '',
                                    'cust_name' => 'wp_dp_featured_listings_save',
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

                add_action('wp_ajax_wp_dp_cs_var_page_builder_wp_dp_featured_listings', 'wp_dp_cs_var_page_builder_wp_dp_featured_listings');
            }

            if ( ! function_exists('wp_dp_cs_save_page_builder_data_wp_dp_featured_listings_callback') ) {

                /**
                 * Save data for wp_dp_featured_listings shortcode.
                 *
                 * @param	array $args
                 * @return	array
                 */
                function wp_dp_cs_save_page_builder_data_wp_dp_featured_listings_callback($args) {

                    $data = $args['data'];
                    $counters = $args['counters'];
                    $widget_type = $args['widget_type'];
                    $column = $args['column'];
                    $shortcode_data = '';
                    if ( $widget_type == "wp_dp_featured_listings" || $widget_type == "cs_wp_dp_featured_listings" ) {
                        $wp_dp_featured_listings = '';

                        $page_element_size = $data['wp_dp_featured_listings_element_size'][$counters['wp_dp_featured_listings_global_counter']];
                        $current_element_size = $data['wp_dp_featured_listings_element_size'][$counters['wp_dp_featured_listings_global_counter']];

                        if ( isset($data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']]) && $data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']] == 'shortcode' ) {
                            $shortcode_str = stripslashes(( $data['shortcode']['wp_dp_featured_listings'][$counters['wp_dp_cs_shortcode_counter_wp_dp_featured_listings']]));

                            $element_settings = 'wp_dp_featured_listings_element_size="' . $current_element_size . '"';
                            $reg = '/wp_dp_featured_listings_element_size="(\d+)"/s';
                            $shortcode_str = preg_replace($reg, $element_settings, $shortcode_str);
                            $shortcode_data = $shortcode_str;
                            $counters['wp_dp_cs_shortcode_counter_wp_dp_featured_listings'] ++;
                        } else {
                            $element_settings = 'wp_dp_featured_listings_element_size="' . htmlspecialchars($data['wp_dp_featured_listings_element_size'][$counters['wp_dp_featured_listings_global_counter']]) . '"';
                            $wp_dp_featured_listings = '[wp_dp_featured_listings ' . $element_settings . ' ';
                            if ( isset($data['featured_listings_title'][$counters['wp_dp_featured_listings_counter']]) && $data['featured_listings_title'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'featured_listings_title="' . htmlspecialchars($data['featured_listings_title'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['featured_listings_title_alignment'][$counters['wp_dp_featured_listings_counter']]) && $data['featured_listings_title_alignment'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'featured_listings_title_alignment="' . htmlspecialchars($data['featured_listings_title_alignment'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['wp_dp_featured_listing_element_subtitle_color'][$counters['wp_dp_featured_listings_counter']]) && $data['wp_dp_featured_listing_element_subtitle_color'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'wp_dp_featured_listing_element_subtitle_color="' . htmlspecialchars($data['wp_dp_featured_listing_element_subtitle_color'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['wp_dp_featured_listing_element_title_color'][$counters['wp_dp_featured_listings_counter']]) && $data['wp_dp_featured_listing_element_title_color'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'wp_dp_featured_listing_element_title_color="' . htmlspecialchars($data['wp_dp_featured_listing_element_title_color'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['listing_category'][$counters['wp_dp_featured_listings_counter']]) && $data['listing_category'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'listing_category="' . htmlspecialchars($data['listing_category'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['listing_slider_switch'][$counters['wp_dp_featured_listings_counter']]) && $data['listing_slider_switch'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'listing_slider_switch="' . htmlspecialchars($data['listing_slider_switch'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['featured_listings_title_limit'][$counters['wp_dp_featured_listings_counter']]) && $data['featured_listings_title_limit'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'featured_listings_title_limit="' . htmlspecialchars($data['featured_listings_title_limit'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['wp_dp_featured_listings_seperator_style'][$counters['wp_dp_featured_listings_counter']]) && $data['wp_dp_featured_listings_seperator_style'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'wp_dp_featured_listings_seperator_style="' . htmlspecialchars($data['wp_dp_featured_listings_seperator_style'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['listings_subtitle'][$counters['wp_dp_featured_listings_counter']]) && $data['listings_subtitle'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'listings_subtitle="' . htmlspecialchars($data['listings_subtitle'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['listing_type'][$counters['wp_dp_featured_listings_counter']]) && $data['listing_type'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'listing_type="' . htmlspecialchars($data['listing_type'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['listing_view'][$counters['wp_dp_featured_listings_counter']]) && $data['listing_view'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'listing_view="' . htmlspecialchars($data['listing_view'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            // saving admin field using filter for add on
                            $wp_dp_featured_listings = apply_filters('wp_dp_save_listings_shortcode_admin_fields', $wp_dp_featured_listings, $_POST, $counters['wp_dp_featured_listings_counter']);
                            if ( isset($data['listing_urgent'][$counters['wp_dp_featured_listings_counter']]) && $data['listing_urgent'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'listing_urgent="' . htmlspecialchars($data['listing_urgent'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['listing_no_custom_fields'][$counters['wp_dp_featured_listings_counter']]) && $data['listing_no_custom_fields'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'listing_no_custom_fields="' . htmlspecialchars($data['listing_no_custom_fields'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['listing_ads_switch'][$counters['wp_dp_featured_listings_counter']]) && $data['listing_ads_switch'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'listing_ads_switch="' . htmlspecialchars($data['listing_ads_switch'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['filters_listing_type'][$counters['wp_dp_featured_listings_counter']]) && $data['filters_listing_type'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'filters_listing_type="' . htmlspecialchars($data['filters_listing_type'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            
                            if ( isset($data['listing_hide_switch'][$counters['wp_dp_featured_listings_counter']]) && $data['listing_hide_switch'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'listing_hide_switch="' . htmlspecialchars($data['listing_hide_switch'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['listing_notes_switch'][$counters['wp_dp_featured_listings_counter']]) && $data['listing_notes_switch'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'listing_notes_switch="' . htmlspecialchars($data['listing_notes_switch'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['listing_ads_after_list_count'][$counters['wp_dp_featured_listings_counter']]) && $data['listing_ads_after_list_count'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'listing_ads_after_list_count="' . htmlspecialchars($data['listing_ads_after_list_count'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['listing_top_category'][$counters['wp_dp_featured_listings_counter']]) && $data['listing_top_category'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'listing_top_category="' . htmlspecialchars($data['listing_top_category'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['listing_top_category_count'][$counters['wp_dp_featured_listings_counter']]) && $data['listing_top_category_count'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'listing_top_category_count="' . htmlspecialchars($data['listing_top_category_count'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['posts_per_page'][$counters['wp_dp_featured_listings_counter']]) && $data['posts_per_page'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'posts_per_page="' . htmlspecialchars($data['posts_per_page'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }
                            if ( isset($data['listing_location'][$counters['wp_dp_featured_listings_counter']]) && $data['listing_location'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= 'listing_location="' . htmlspecialchars($data['listing_location'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . '" ';
                            }

                            // Apply filter on default attributes Saving
                            $section_title = apply_filters('wp_dp_shortcode_default_atts_save', $wp_dp_featured_listings, $data, $counters['wp_dp_featured_listings_counter'], array( 'responsive_atts' => true ));

                            $wp_dp_featured_listings = $section_title;
                            $wp_dp_featured_listings .= ']';
                            if ( isset($data['wp_dp_featured_listings_column_text'][$counters['wp_dp_featured_listings_counter']]) && $data['wp_dp_featured_listings_column_text'][$counters['wp_dp_featured_listings_counter']] != '' ) {
                                $wp_dp_featured_listings .= htmlspecialchars($data['wp_dp_featured_listings_column_text'][$counters['wp_dp_featured_listings_counter']], ENT_QUOTES) . ' ';
                            }
                            $wp_dp_featured_listings .= '[/wp_dp_featured_listings]';
                            $shortcode_data .= $wp_dp_featured_listings;
                            $counters['wp_dp_featured_listings_counter'] ++;
                        }
                        $counters['wp_dp_featured_listings_global_counter'] ++;
                    }
                    return array(
                        'data' => $data,
                        'counters' => $counters,
                        'widget_type' => $widget_type,
                        'column' => $shortcode_data,
                    );
                }

                add_filter('wp_dp_cs_save_page_builder_data_wp_dp_featured_listings', 'wp_dp_cs_save_page_builder_data_wp_dp_featured_listings_callback');
            }

            if ( ! function_exists('wp_dp_cs_load_shortcode_counters_wp_dp_featured_listings_callback') ) {

                /**
                 * Populate wp_dp_featured_listings shortcode counter variables.
                 *
                 * @param	array $counters
                 * @return	array
                 */
                function wp_dp_cs_load_shortcode_counters_wp_dp_featured_listings_callback($counters) {
                    $counters['wp_dp_featured_listings_global_counter'] = 0;
                    $counters['wp_dp_cs_shortcode_counter_wp_dp_featured_listings'] = 0;
                    $counters['wp_dp_featured_listings_counter'] = 0;
                    return $counters;
                }

                add_filter('wp_dp_cs_load_shortcode_counters', 'wp_dp_cs_load_shortcode_counters_wp_dp_featured_listings_callback');
            }



            if ( ! function_exists('wp_dp_cs_element_list_populate_wp_dp_featured_listings_callback') ) {

                /**
                 * Populate wp_dp_featured_listings shortcode strings list.
                 *
                 * @param	array $counters
                 * @return	array
                 */
                function wp_dp_cs_element_list_populate_wp_dp_featured_listings_callback($element_list) {
                    $element_list['wp_dp_featured_listings'] = wp_dp_plugin_text_srt('wp_dp_shortcode_featured_listings_heading');
                    return $element_list;
                }

                add_filter('wp_dp_cs_element_list_populate', 'wp_dp_cs_element_list_populate_wp_dp_featured_listings_callback');
            }

            if ( ! function_exists('wp_dp_cs_shortcode_names_list_populate_wp_dp_featured_listings_callback') ) {

                /**
                 * Populate wp_dp_featured_listings shortcode names list.
                 *
                 * @param	array $counters
                 * @return	array
                 */
                function wp_dp_cs_shortcode_names_list_populate_wp_dp_featured_listings_callback($shortcode_array) {
                    $shortcode_array['wp_dp_featured_listings'] = array(
                        'title' => wp_dp_plugin_text_srt('wp_dp_shortcode_featured_listings_heading'),
                        'name' => 'wp_dp_featured_listings',
                        'icon' => 'icon-featured_video',
                        'categories' => 'typography',
                    );

                    return $shortcode_array;
                }

                add_filter('wp_dp_cs_shortcode_names_list_populate', 'wp_dp_cs_shortcode_names_list_populate_wp_dp_featured_listings_callback');
            }
            if ( ! function_exists('get_listing_cats_by_type_featured') ) {

                function get_listing_cats_by_type_featured($listing_type = '', $listing_category = '', $is_ajax = true, $listing_element_rand_id = '') {
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

    add_action('wp_ajax_get_listing_cats_by_type', 'get_listing_cats_by_type_featured');
}