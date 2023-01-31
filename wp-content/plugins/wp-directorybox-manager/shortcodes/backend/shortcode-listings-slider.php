<?php
/**
 * Shortcode Name : wp_dp_listings_slider
 *
 * @package	wp_dp_cs 
 */
if (!function_exists('wp_dp_cs_var_page_builder_wp_dp_listings_slider')) {

    function wp_dp_cs_var_page_builder_wp_dp_listings_slider($die = 0) {
        global $post, $wp_dp_html_fields, $wp_dp_cs_node, $wp_dp_form_fields;
        if (function_exists('wp_dp_cs_shortcode_names')) {
            $shortcode_element = '';
            $filter_element = 'filterdrag';
            $shortcode_view = '';
            $wp_dp_cs_output = array();
            $wp_dp_cs_PREFIX = 'wp_dp_listings_slider';
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
                'slider_listings_title' => '',
                'listings_subtitle' => '',
                'listings_slider_alignment' => '',
                'slider_listings_title_limit' => '20',
                'listing_type' => '',
                'listing_sort_by' => 'recent',
                'listing_featured' => 'all',
                'listing_location' => '',
                'listing_no_custom_fields' => '3',
                'posts_per_page' => '10',
                'wp_dp_listing_slider_element_title_color' => '',
                'wp_dp_listing_slider_element_subtitle_color' => '',
                'wp_dp_listings_slider_seperator_style' => '',
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
                $wp_dp_listings_slider_column_text = $wp_dp_cs_output['0']['content'];
            } else {
                $wp_dp_listings_slider_column_text = '';
            }
            $wp_dp_listings_slider_element_size = '100';
            foreach ($defaults as $key => $values) {
                if (isset($atts[$key])) {
                    $$key = $atts[$key];
                } else {
                    $$key = $values;
                }
            }
            $name = 'wp_dp_cs_var_page_builder_wp_dp_listings_slider';
            $coloumn_class = 'column_' . $wp_dp_listings_slider_element_size;
            if (isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode') {
                $shortcode_element = 'shortcode_element_class';
                $shortcode_view = 'cs-pbwp-shortcode';
                $filter_element = 'ajax-drag';
                $coloumn_class = '';
            }
            $listing_rand_id = rand(4444, 99999);
            wp_enqueue_script('wp_dp_cs-admin-upload');
            $listing_views = array(
                'grid' => wp_dp_plugin_text_srt('wp_dp_listings_element_slider_list_view_grid'),
                'list' => wp_dp_plugin_text_srt('wp_dp_listings_element_slider_list_view_list'),
                'fancy' => wp_dp_plugin_text_srt('wp_dp_listings_element_slider_list_view_fancy'),
                'map' => wp_dp_plugin_text_srt('wp_dp_listings_element_slider_list_view_map'),
            );
            ?>

            <div id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class); ?>
                 <?php echo esc_attr($shortcode_view); ?>" item="wp_dp_listings_slider" data="<?php echo wp_dp_cs_element_size_data_array_index($wp_dp_listings_slider_element_size) ?>" >
                     <?php wp_dp_cs_element_setting($name, $wp_dp_cs_counter, $wp_dp_listings_slider_element_size) ?>
                <div class="cs-wrapp-class-<?php echo intval($wp_dp_cs_counter) ?>
                     <?php echo esc_attr($shortcode_element); ?>" id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>" data-shortcode-template="[wp_dp_listings_slider {{attributes}}]{{content}}[/wp_dp_listings_slider]" style="display: none;">
                    <div class="cs-heading-area" data-counter="<?php echo esc_attr($wp_dp_cs_counter) ?>">
                        <h5><?php echo wp_dp_plugin_text_srt('wp_dp_listing_slider_options'); ?></h5>
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
                                    'std' => esc_attr($slider_listings_title),
                                    'id' => 'slider_listings_title',
                                    'cust_name' => 'slider_listings_title[]',
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
                                    'std' => esc_attr($listings_slider_alignment),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'listings_slider_alignment[]',
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
                                    'std' => $wp_dp_listing_slider_element_title_color,
                                    'cust_name' => 'wp_dp_listing_slider_element_title_color[]',
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
                                    'std' => $wp_dp_listing_slider_element_subtitle_color,
                                    'cust_name' => 'wp_dp_listing_slider_element_subtitle_color[]',
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
                                    'std' => esc_attr($wp_dp_listings_slider_seperator_style),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'wp_dp_listings_slider_seperator_style[]',
                                    'return' => true,
                                    'options' => array(
                                        '' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_seperator_style_none'),
                                        'classic' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_seperator_style_classic'),
                                        'zigzag' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_seperator_style_zigzag'),
                                    ),
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $wp_dp_post_listing_types = new Wp_dp_Post_Listing_Types();
                            $listing_types_array = $wp_dp_post_listing_types->wp_dp_types_array_callback(wp_dp_plugin_text_srt('wp_dp_shortcode_listings_all_types'));
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_listing_types'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listing_type),
                                    'id' => 'listing_type[]',
                                    'classes' => 'chosen-select',
                                    'cust_name' => 'listing_type[]',
                                    'return' => true,
                                    'options' => $listing_types_array
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_title_length'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($slider_listings_title_limit),
                                    'id' => 'slider_listings_title_limit',
                                    'cust_name' => 'slider_listings_title_limit[]',
                                    'return' => true,
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_members_sort_by'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listing_sort_by),
                                    'id' => 'listing_sort_by[]',
                                    'cust_name' => 'listing_sort_by[]',
                                    'classes' => 'chosen-select-no-single',
                                    'return' => true,
                                    'options' => array(
                                        'recent' => wp_dp_plugin_text_srt('wp_dp_member_members_recent'),
                                        'alphabetical' => wp_dp_plugin_text_srt('wp_dp_member_members_alphabetical'),
                                    )
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_listing_featured'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listing_featured),
                                    'id' => 'listing_featured[]',
                                    'cust_name' => 'listing_featured[]',
                                    'return' => true,
                                    'classes' => 'chosen-select-no-single',
                                    'options' => array(
                                        'all' => wp_dp_plugin_text_srt('wp_dp_options_all'),
                                        'only-featured' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_only_featured'),
                                    )
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
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
                                $listing_locations = explode(',', $saved_listing_location);
                                foreach ($listing_locations as $listing_loc) {
                                    $get_listing_locations[$listing_loc] = $listing_location_options[$listing_loc];
                                }
                            }
                            if ($get_listing_locations) {
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

                            $wp_dp_cs_opt_array = array(
                                'std' => absint($listing_rand_id),
                                'id' => '',
                                'cust_id' => 'listing_counter',
                                'cust_name' => 'listing_counter[]',
                                'required' => false
                            );
                            $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_cs_opt_array);

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
                                'std' => 'wp_dp_listings_slider',
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
                                    'cust_id' => 'wp_dp_listings_slider_save',
                                    'cust_type' => 'button',
                                    'extra_atr' => 'onclick="javascript:_removerlay(jQuery(this))"',
                                    'classes' => 'cs-wp_dp_cs-admin-btn save_listing_locations_' . $listing_rand_id . '',
                                    'cust_name' => 'wp_dp_listings_slider_save',
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

    add_action('wp_ajax_wp_dp_cs_var_page_builder_wp_dp_listings_slider', 'wp_dp_cs_var_page_builder_wp_dp_listings_slider');
}

if (!function_exists('wp_dp_cs_save_page_builder_data_wp_dp_listings_slider_callback')) {

    /**
     * Save data for wp_dp_listings_slider shortcode.
     *
     * @param	array $args
     * @return	array
     */
    function wp_dp_cs_save_page_builder_data_wp_dp_listings_slider_callback($args) {

        $data = $args['data'];
        $counters = $args['counters'];
        $widget_type = $args['widget_type'];
        $column = $args['column'];
        $shortcode_data = '';
        if ($widget_type == "wp_dp_listings_slider" || $widget_type == "cs_wp_dp_listings_slider") {
            $wp_dp_cs_bareber_wp_dp_listings_slider = '';

            $page_element_size = $data['wp_dp_listings_slider_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_listings_slider']];
            $current_element_size = $data['wp_dp_listings_slider_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_listings_slider']];

            if (isset($data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']]) && $data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']] == 'shortcode') {
                $shortcode_str = stripslashes(( $data['shortcode']['wp_dp_listings_slider'][$counters['wp_dp_cs_shortcode_counter_wp_dp_listings_slider']]));

                $element_settings = 'wp_dp_listings_slider_element_size="' . $current_element_size . '"';
                $reg = '/wp_dp_listings_slider_element_size="(\d+)"/s';
                $shortcode_str = preg_replace($reg, $element_settings, $shortcode_str);
                $shortcode_data = $shortcode_str;
                $counters['wp_dp_cs_shortcode_counter_wp_dp_listings_slider'] ++;
            } else {
                $element_settings = 'wp_dp_listings_slider_element_size="' . htmlspecialchars($data['wp_dp_listings_slider_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_listings_slider']]) . '"';
                $wp_dp_cs_bareber_wp_dp_listings_slider = '[wp_dp_listings_slider ' . $element_settings . ' ';
                if (isset($data['slider_listings_title'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']]) && $data['slider_listings_title'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_slider .= 'slider_listings_title="' . htmlspecialchars($data['slider_listings_title'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']], ENT_QUOTES) . '" ';
                }
                if (isset($data['wp_dp_listings_slider_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']]) && $data['wp_dp_listings_slider_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_slider .= 'wp_dp_listings_slider_seperator_style="' . htmlspecialchars($data['wp_dp_listings_slider_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']], ENT_QUOTES) . '" ';
                }
                if (isset($data['listings_slider_alignment'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']]) && $data['listings_slider_alignment'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_slider .= 'listings_slider_alignment="' . htmlspecialchars($data['listings_slider_alignment'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']], ENT_QUOTES) . '" ';
                }
                if (isset($data['listings_subtitle'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']]) && $data['listings_subtitle'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_slider .= 'listings_subtitle="' . htmlspecialchars($data['listings_subtitle'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']], ENT_QUOTES) . '" ';
                }
                if (isset($data['wp_dp_listing_slider_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']]) && $data['wp_dp_listing_slider_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_slider .= 'wp_dp_listing_slider_element_title_color="' . htmlspecialchars($data['wp_dp_listing_slider_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']], ENT_QUOTES) . '" ';
                }
                if (isset($data['wp_dp_listing_slider_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']]) && $data['wp_dp_listing_slider_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_slider .= 'wp_dp_listing_slider_element_subtitle_color="' . htmlspecialchars($data['wp_dp_listing_slider_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']], ENT_QUOTES) . '" ';
                }
                if (isset($data['listing_type'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']]) && $data['listing_type'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_slider .= 'listing_type="' . htmlspecialchars($data['listing_type'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']], ENT_QUOTES) . '" ';
                }
                if (isset($data['slider_listings_title_limit'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']]) && $data['slider_listings_title_limit'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_slider .= 'slider_listings_title_limit="' . htmlspecialchars($data['slider_listings_title_limit'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']], ENT_QUOTES) . '" ';
                }
                if (isset($data['listing_sort_by'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']]) && $data['listing_sort_by'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_slider .= 'listing_sort_by="' . htmlspecialchars($data['listing_sort_by'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']], ENT_QUOTES) . '" ';
                }
                // saving admin field using filter for add on
                $wp_dp_cs_bareber_wp_dp_listings_slider = apply_filters('wp_dp_save_listings_shortcode_admin_fields', $wp_dp_cs_bareber_wp_dp_listings_slider, $_POST, $counters['wp_dp_cs_counter_wp_dp_listings_slider']);
                if (isset($data['listing_featured'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']]) && $data['listing_featured'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_slider .= 'listing_featured="' . htmlspecialchars($data['listing_featured'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']], ENT_QUOTES) . '" ';
                }
                if (isset($data['listing_counter'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']]) && $data['listing_counter'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_slider .= 'listing_counter="' . htmlspecialchars($data['listing_counter'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']], ENT_QUOTES) . '" ';
                }
                if (isset($data['listing_location'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']]) && $data['listing_location'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_slider .= 'listing_location="' . htmlspecialchars($data['listing_location'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']], ENT_QUOTES) . '" ';
                } 
                if (isset($data['listing_enquiry_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']]) && $data['listing_enquiry_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_slider .= 'listing_enquiry_switch="' . htmlspecialchars($data['listing_enquiry_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']], ENT_QUOTES) . '" ';
                }
                if (isset($data['listing_notes_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']]) && $data['listing_notes_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_slider .= 'listing_notes_switch="' . htmlspecialchars($data['listing_notes_switch'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']], ENT_QUOTES) . '" ';
                }
                if (isset($data['listing_no_custom_fields'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']]) && $data['listing_no_custom_fields'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_slider .= 'listing_no_custom_fields="' . htmlspecialchars($data['listing_no_custom_fields'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']], ENT_QUOTES) . '" ';
                }
                if (isset($data['posts_per_page'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']]) && $data['posts_per_page'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_slider .= 'posts_per_page="' . htmlspecialchars($data['posts_per_page'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']], ENT_QUOTES) . '" ';
                }
                
                
                  // Apply filter on default attributes Saving
                $section_title = apply_filters('wp_dp_shortcode_default_atts_save', $wp_dp_cs_bareber_wp_dp_listings_slider, $data, $counters['wp_dp_cs_counter_wp_dp_listings_slider'], array( 'responsive_atts' => true ));
                 $wp_dp_cs_bareber_wp_dp_listings_slider = $section_title;
                
                $wp_dp_cs_bareber_wp_dp_listings_slider .= ']';
                if (isset($data['wp_dp_listings_slider_column_text'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']]) && $data['wp_dp_listings_slider_column_text'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']] != '') {
                    $wp_dp_cs_bareber_wp_dp_listings_slider .= htmlspecialchars($data['wp_dp_listings_slider_column_text'][$counters['wp_dp_cs_counter_wp_dp_listings_slider']], ENT_QUOTES) . ' ';
                }
                $wp_dp_cs_bareber_wp_dp_listings_slider .= '[/wp_dp_listings_slider]';
                $shortcode_data .= $wp_dp_cs_bareber_wp_dp_listings_slider;
                $counters['wp_dp_cs_counter_wp_dp_listings_slider'] ++;
            }
            $counters['wp_dp_cs_global_counter_wp_dp_listings_slider'] ++;
        }
        return array(
            'data' => $data,
            'counters' => $counters,
            'widget_type' => $widget_type,
            'column' => $shortcode_data,
        );
    }

    add_filter('wp_dp_cs_save_page_builder_data_wp_dp_listings_slider', 'wp_dp_cs_save_page_builder_data_wp_dp_listings_slider_callback');
}

if (!function_exists('wp_dp_cs_load_shortcode_counters_wp_dp_listings_slider_callback')) {

    /**
     * Populate wp_dp_listings_slider shortcode counter variables.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_load_shortcode_counters_wp_dp_listings_slider_callback($counters) {
        $counters['wp_dp_cs_global_counter_wp_dp_listings_slider'] = 0;
        $counters['wp_dp_cs_shortcode_counter_wp_dp_listings_slider'] = 0;
        $counters['wp_dp_cs_counter_wp_dp_listings_slider'] = 0;
        return $counters;
    }

    add_filter('wp_dp_cs_load_shortcode_counters', 'wp_dp_cs_load_shortcode_counters_wp_dp_listings_slider_callback');
}



if (!function_exists('wp_dp_cs_element_list_populate_wp_dp_listings_slider_callback')) {

    /**
     * Populate wp_dp_listings_slider shortcode strings list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_element_list_populate_wp_dp_listings_slider_callback($element_list) {
        $element_list['wp_dp_listings_slider'] = wp_dp_plugin_text_srt('wp_dp_listing_slider_heading');
        return $element_list;
    }

    add_filter('wp_dp_cs_element_list_populate', 'wp_dp_cs_element_list_populate_wp_dp_listings_slider_callback');
}

if (!function_exists('wp_dp_cs_shortcode_names_list_populate_wp_dp_listings_slider_callback')) {

    /**
     * Populate wp_dp_listings_slider shortcode names list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_shortcode_names_list_populate_wp_dp_listings_slider_callback($shortcode_array) {
        $shortcode_array['wp_dp_listings_slider'] = array(
            'title' => wp_dp_plugin_text_srt('wp_dp_listing_slider_heading'),
            'name' => 'wp_dp_listings_slider',
            'icon' => 'icon-sliders',
            'categories' => 'typography',
        );

        return $shortcode_array;
    }

    add_filter('wp_dp_cs_shortcode_names_list_populate', 'wp_dp_cs_shortcode_names_list_populate_wp_dp_listings_slider_callback');
}
