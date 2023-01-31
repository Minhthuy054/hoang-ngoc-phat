<?php
/**
 * Shortcode Name : wp_dp_listingsearch
 *
 * @package	wp_dp_cs 
 */
if ( ! function_exists('wp_dp_cs_var_page_builder_wp_dp_listingsearch') ) {

    function wp_dp_cs_var_page_builder_wp_dp_listingsearch($die = 0) {
        global $post, $wp_dp_html_fields, $wp_dp_cs_node, $wp_dp_form_fields;
        if ( function_exists('wp_dp_cs_shortcode_names') ) {
            $shortcode_element = '';
            $filter_element = 'filterdrag';
            $shortcode_view = '';
            $wp_dp_cs_output = array();
            $wp_dp_cs_PREFIX = 'wp_dp_listingsearch';
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
                'listingsearch_title' => '',
                'listingsearch_subtitle' => '',
                'listingsearch_alignment' => '',
                'listingsearch_layout_bg' => '',
                'wp_dp_listing_search_element_title_color' => '',
                'wp_dp_listing_search_element_subtitle_color' => '',
                'listingsearch_layout_heading_color' => '',
                'listingsearch_title_field_switch' => '',
                'listingsearch_listing_type_field_switch' => '',
                'listingsearch_location_field_switch' => '',
                'listingsearch_price_field_switch' => '',
                'listingsearch_advance_filter_switch' => '',
                'listingsearch_categories_field_switch' => '',
                'advance_link' => '',
                'popup_link_text' => '',
                'listingsearch_view' => 'fancy',
                'wp_dp_search_label_color' => '',
                'search_background_color' => '',
                'listingsearch_price_type_switch' => '',
                'wp_dp_listing_search_seperator_style' => '',
            );
            $defaults = apply_filters('wp_dp_listingsearch_shortcode_admin_default_attributes', $defaults);

            // Apply filter on default attributes
            $defaults = apply_filters('wp_dp_shortcode_default_atts', $defaults, array( 'responsive_atts' => true ));

            if ( isset($wp_dp_cs_output['0']['atts']) ) {
                $atts = $wp_dp_cs_output['0']['atts'];
            } else {
                $atts = array();
            }
            if ( isset($wp_dp_cs_output['0']['content']) ) {
                $help_text_popup = $wp_dp_cs_output['0']['content'];
            } else {
                $help_text_popup = '';
            }
            $wp_dp_listingsearch_element_size = '100';
            foreach ( $defaults as $key => $values ) {
                if ( isset($atts[$key]) ) {
                    $$key = $atts[$key];
                } else {
                    $$key = $values;
                }
            }
            $name = 'wp_dp_cs_var_page_builder_wp_dp_listingsearch';
            $coloumn_class = 'column_' . $wp_dp_listingsearch_element_size;
            if ( isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode' ) {
                $shortcode_element = 'shortcode_element_class';
                $shortcode_view = 'cs-pbwp-shortcode';
                $filter_element = 'ajax-drag';
                $coloumn_class = '';
            }
            $listing_rand_id = rand(4444, 99999);
            wp_enqueue_script('wp_dp_cs-admin-upload');
            ?>

            <div id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class); ?>
                 <?php echo esc_attr($shortcode_view); ?>" item="wp_dp_listingsearch" data="<?php echo wp_dp_cs_element_size_data_array_index($wp_dp_listingsearch_element_size) ?>" >
                     <?php wp_dp_cs_element_setting($name, $wp_dp_cs_counter, $wp_dp_listingsearch_element_size) ?>
                <div class="cs-wrapp-class-<?php echo intval($wp_dp_cs_counter) ?>
                     <?php echo esc_attr($shortcode_element); ?>" id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>" data-shortcode-template="[wp_dp_listingsearch {{attributes}}]{{content}}[/wp_dp_listingsearch]" style="display: none;">
                    <div class="cs-heading-area" data-counter="<?php echo esc_attr($wp_dp_cs_counter) ?>">
                        <h5><?php echo wp_dp_plugin_text_srt('wp_dp_listing_search_options'); ?></h5>
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
                                    'std' => esc_attr($listingsearch_title),
                                    'id' => 'listingsearch_title',
                                    'cust_name' => 'listingsearch_title[]',
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
                                    'std' => esc_attr($listingsearch_subtitle),
                                    'id' => 'listingsearch_subtitle',
                                    'cust_name' => 'listingsearch_subtitle[]',
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
                                    'std' => esc_attr($listingsearch_alignment),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'listingsearch_alignment[]',
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
                                    'std' => $wp_dp_listing_search_element_title_color,
                                    'cust_name' => 'wp_dp_listing_search_element_title_color[]',
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
                                    'std' => $wp_dp_listing_search_element_subtitle_color,
                                    'cust_name' => 'wp_dp_listing_search_element_subtitle_color[]',
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
                                    'std' => esc_attr($wp_dp_listing_search_seperator_style),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'wp_dp_listing_search_seperator_style[]',
                                    'return' => true,
                                    'options' => array(
                                        '' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_seperator_style_none'),
                                        'classic' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_seperator_style_classic'),
                                        'zigzag' => wp_dp_plugin_text_srt('wp_dp_plugin_element_title_seperator_style_zigzag'),
                                    ),
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $listingsearch_views = array(
//                                'fancy' => wp_dp_plugin_text_srt('wp_dp_element_view_fancy'),
//                                'fancy_v2' => wp_dp_plugin_text_srt('wp_dp_element_view_fancy_v2'),
//                                'fancy_v3' => wp_dp_plugin_text_srt('wp_dp_element_view_fancy_v3'),
//                                'fancy_v4' => wp_dp_plugin_text_srt('wp_dp_element_view_fancy_v4'),
//                                'classic' => wp_dp_plugin_text_srt('wp_dp_shortcode_classic'),
//                                'list' => wp_dp_plugin_text_srt('wp_dp_element_view_list'),
//                                'modern' => wp_dp_plugin_text_srt('wp_dp_element_view_modernnn'),
                                'modern_v2' => wp_dp_plugin_text_srt('wp_dp_search_element_style_modern_v2'),
                                'modern_v3' => wp_dp_plugin_text_srt('wp_dp_search_element_style_modern_v3'),
                                'modern_v4' => wp_dp_plugin_text_srt('wp_dp_search_element_style_modern_v4'),
//                                'simple' => wp_dp_plugin_text_srt('wp_dp_element_view_simplee'),
//                                'advance' => wp_dp_plugin_text_srt('wp_dp_element_view_advance'),
                                'default' => wp_dp_plugin_text_srt('wp_dp_list_meta_default'),
                            );
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_element_view'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_element_view_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listingsearch_view),
                                    'id' => 'listingsearch_view' . $listing_rand_id . '',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'listingsearch_view[]',
                                    'return' => true,
                                    'options' => $listingsearch_views
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);


                            $fancy_v2_view_fields = 'none';
                            if ( isset($listingsearch_view) && ($listingsearch_view == 'default' || $listingsearch_view == 'fancy_v2' || $listingsearch_view == 'modern_v2' || $listingsearch_view == 'fancy_v3' || $listingsearch_view == 'fancy_v4' || $listingsearch_view == 'modern_v3' || $listingsearch_view == 'modern_v4') ) {
                                $fancy_v2_view_fields = 'block';
                            }
                            
                            $default_view_fields = 'block';
                            if ( isset($listingsearch_view) && $listingsearch_view == 'default') {
                                $default_view_fields = 'none';
                            }
                            
                            
                          //  echo '<div id="default_view' . $listing_rand_id . '" style="display:' . $default_view_fields . ';">';
                            
                            echo '<div id="search_background_color_field' . $listing_rand_id . '" style="display:' . $fancy_v2_view_fields . ';">';
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_search_element_background_colorrr'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_html($search_background_color),
                                    'cust_name' => 'search_background_color[]',
                                    'classes' => 'bg_color',
                                    'return' => true,
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            echo '</div>';
                            $modern_view_fields = 'none';
                            if ( isset($listingsearch_view) && $listingsearch_view == 'modern' ) {
                                $modern_view_fields = 'block';
                            }

                            $simple_view_fields = 'block';
                            if ( isset($listingsearch_view) && ($listingsearch_view == 'simple' || $listingsearch_view == 'advance' || $listingsearch_view == 'default') ) {
                                $simple_view_fields = 'none';
                            }
                            echo '<div id="search_label_color' . $listing_rand_id . '" style="display:' . $modern_view_fields . ';">';
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_listing_search_view_label_color'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_search_view_label_color_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_html($wp_dp_search_label_color),
                                    'id' => 'search_label_color' . $listing_rand_id . '',
                                    'cust_name' => 'wp_dp_search_label_color[]',
                                    'classes' => 'bg_color',
                                    'return' => true,
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            echo '</div>';
                            
                            $keyword_search_field = 'block';
                            if ( isset($listingsearch_view) && ($listingsearch_view == 'default')){
                                $keyword_search_field = 'none';
                            }
                            echo '<div id="search_keyword_field' . $listing_rand_id . '" style="display:' . $keyword_search_field . ';">';
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_map_search_keyword'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_map_search_keyword_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listingsearch_title_field_switch),
                                    'cust_id' => '',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'listingsearch_title_field_switch[]',
                                    'return' => true,
                                    'options' => array(
                                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                    )
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                            echo '</div>';
                            
                            $modern_v2_type_price = 'block';
                            if ( isset($listingsearch_view) && ($listingsearch_view == 'modern_v2' || $listingsearch_view == 'default')){
                                $modern_v2_type_price = 'none';
                            }
                            
                            
                            echo '<div id="search_type_field' . $listing_rand_id . '" style="display:' . $modern_v2_type_price . ';">';
                            
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_map_search_listing_type'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_map_search_listing_type_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listingsearch_listing_type_field_switch),
                                    'cust_id' => '',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'listingsearch_listing_type_field_switch[]',
                                    'return' => true,
                                    'options' => array(
                                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                    )
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                            
                            echo '</div>';
                            
                            echo '<div id="search_simple_dynamic' . $listing_rand_id . '" style="display:' . $simple_view_fields . ';">';
                            
                            $location_view_fields = 'block';
                            if ( isset($listingsearch_view) && $listingsearch_view == 'modern_v4' ) {
                                $location_view_fields = 'none';
                            }
                            
                            echo '<div id="listingsearch_location_field_switch_' . $listing_rand_id . '" style="display:' . $location_view_fields . ';">';
                            
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_map_search_location'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_map_search_location_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listingsearch_location_field_switch),
                                    'cust_id' => '',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'listingsearch_location_field_switch[]',
                                    'return' => true,
                                    'options' => array(
                                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                    )
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                            
                            echo '</div>';
                            
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_map_search_categories'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_map_search_categories_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listingsearch_categories_field_switch),
                                    'cust_id' => '',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'listingsearch_categories_field_switch[]',
                                    'return' => true,
                                    'options' => array(
                                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                    )
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array); 

                            $listing_price_field_display = 'block';
                            if ( isset($listingsearch_view) && ($listingsearch_view == 'fancy_v3' || $listingsearch_view == 'modern_v2')  ) {
                                $listing_price_field_display = 'none';
                            }
                            
                            echo '<div id="listing_price_field_' . $listing_rand_id . '" style="display:' . $listing_price_field_display . ';">';
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_listing_search_listing_price'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_search_listing_price_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listingsearch_price_field_switch),
                                    'cust_id' => '',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'listingsearch_price_field_switch[]',
                                    'return' => true,
                                    'options' => array(
                                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                    )
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                            echo '</div>';

                            $advanc_search_field_display = 'block';
                            if ( isset($listingsearch_view) && ($listingsearch_view == 'modern_v2' || $listingsearch_view == 'fancy_v3' || $listingsearch_view == 'modern_v3' || $listingsearch_view == 'modern_v4') ) {
                                $advanc_search_field_display = 'none';
                            }
                            echo '<div id="advanc_search_field_' . $listing_rand_id . '" style="display:' . $advanc_search_field_display . ';">';
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_listing_search_advance_filter'),
                                'desc' => '',
                                'label_desc' => wp_dp_plugin_text_srt('wp_dp_listing_search_advance_filter_hint'),
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($listingsearch_advance_filter_switch),
                                    'cust_id' => '',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'listingsearch_advance_filter_switch[]',
                                    'cust_id' => 'listingsearch_advance_filter_switch' . $listing_rand_id,
                                    'return' => true,
                                    'options' => array(
                                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                    )
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                            echo '</div>';

                            echo '</div>';
                            $fancy_view_fields = 'none';
                            if ( isset($listingsearch_view) && $listingsearch_view == 'fancy' ) {
                                $fancy_view_fields = 'block';
                            }
                            echo '<div id="fancy_view_fields_' . $listing_rand_id . '" style="display:' . $fancy_view_fields . ';">';
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_listing_search_poup_link_text'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($popup_link_text),
                                    'id' => 'popup_link_text',
                                    'cust_name' => 'popup_link_text[]',
                                    'return' => true,
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_listing_search_poup_help_text'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($help_text_popup),
                                    'id' => 'help_text_popup',
                                    'wp_dp_editor' => true,
                                    'cust_name' => 'help_text_popup[]',
                                    'return' => true,
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_textarea_field($wp_dp_opt_array);
                            echo '</div>';
                            ?>
                            <script type="text/javascript">
                                var listing_rand_id = "<?php echo esc_html($listing_rand_id); ?>";
                                jQuery(document).on('change', '#listingsearch_advance_filter_switch' + listing_rand_id + '', function () {
                                    if (this.value == 'yes') {
                                        jQuery('#advance_link_' + listing_rand_id + '').show();
                                    } else {
                                        jQuery('#advance_link_' + listing_rand_id + '').hide();
                                    }
                                });
                                jQuery(document).on('change', '#wp_dp_listingsearch_view' + listing_rand_id + '', function () {

                                    jQuery('#listing_price_field_' + listing_rand_id + '').show(); 
                                    jQuery('#advanc_search_field_' + listing_rand_id + '').show();

                                    if (this.value == 'fancy') {
                                        jQuery('#fancy_view_fields_' + listing_rand_id + '').show();
                                    } else {
                                        jQuery('#fancy_view_fields_' + listing_rand_id + '').hide();
                                    }
                                    if (this.value == 'modern') {
                                        jQuery('#search_label_color' + listing_rand_id + '').show();
                                    } else {
                                        jQuery('#search_label_color' + listing_rand_id + '').hide();
                                    }

                                    if (this.value == 'simple' || this.value == 'advance' || this.value == 'default') {
                                        jQuery('#search_simple_dynamic' + listing_rand_id + '').hide();
                                    } else {
                                        jQuery('#search_simple_dynamic' + listing_rand_id + '').show();
                                    }
                                    
                                    if (this.value == 'default') {
                                        jQuery('#search_keyword_field' + listing_rand_id + '').hide();
                                    } else {
                                        jQuery('#search_keyword_field' + listing_rand_id + '').show();
                                    }
                                    
                                    
                                    
                                    
                                    
                                    
                                    if (this.value == 'default' || this.value == 'fancy_v2' || this.value == 'modern_v2' || this.value == 'fancy_v3' || this.value == 'fancy_v4' || this.value == 'modern_v3' || this.value == 'modern_v4') {
                                        jQuery('#search_background_color_field' + listing_rand_id + '').show();
                                        jQuery('#search_background_color_field' + listing_rand_id + '').show();
                                        
                                    } else {
                                        jQuery('#search_background_color_field' + listing_rand_id + '').hide();
                                    }
                                    if (this.value == 'modern_v2' || this.value == 'modern_v3' || this.value == 'modern_v4') {
                                        jQuery('#advanc_search_field_' + listing_rand_id + '').hide();
                                    }
                                    if (this.value == 'fancy_v3' || this.value == 'modern_v2') {
                                        jQuery('#listing_price_field_' + listing_rand_id + '').hide(); 
                                        jQuery('#advanc_search_field_' + listing_rand_id + '').hide();
                                    }
                                    
                                    if (this.value == 'modern_v4') {
                                        jQuery('#listingsearch_location_field_switch_<?php echo esc_html($listing_rand_id); ?>').hide();
                                    } else {
                                        jQuery('#listingsearch_location_field_switch_<?php echo esc_html($listing_rand_id); ?>').show();
                                    }
                                    
                                    if (this.value == 'modern_v2' || this.value == 'default') {
                                        jQuery('#search_type_field<?php echo esc_html($listing_rand_id); ?>').hide();
                                    } else {
                                        jQuery('#search_type_field<?php echo esc_html($listing_rand_id); ?>').show();
                                    }
                                    
                                    

                                });
                                jQuery(document).ready(function () {
                                    var advance_val = jQuery('#listingsearch_advance_filter_switch' + listing_rand_id + '').val();
                                    if (advance_val == 'yes') {
                                        jQuery('#advance_link_' + listing_rand_id + '').show();
                                    } else {
                                        jQuery('#advance_link_' + listing_rand_id + '').hide();
                                    }
                                });
                            </script>
                            <?php
                            $wp_dp_cs_opt_array = array(
                                'std' => absint($listing_rand_id),
                                'id' => '',
                                'cust_id' => 'listingsearch_counter',
                                'cust_name' => 'listingsearch_counter[]',
                                'required' => false
                            );
                            $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_cs_opt_array);

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
                                'std' => 'wp_dp_listingsearch',
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
                                    'cust_id' => 'wp_dp_listingsearch_save',
                                    'cust_type' => 'button',
                                    'extra_atr' => 'onclick="javascript:_removerlay(jQuery(this))"',
                                    'classes' => 'cs-wp_dp_cs-admin-btn',
                                    'cust_name' => 'wp_dp_listingsearch_save',
                                    'return' => true,
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_cs_opt_array);
                        }
                        ?>
                    </div>
                </div>
                <script type="text/javascript">
                    chosen_selectionbox();
                    popup_over();
                </script>
            </div>

            <?php
        }
        if ( $die <> 1 ) {
            die();
        }
    }

    add_action('wp_ajax_wp_dp_cs_var_page_builder_wp_dp_listingsearch', 'wp_dp_cs_var_page_builder_wp_dp_listingsearch');
}

if ( ! function_exists('wp_dp_cs_save_page_builder_data_wp_dp_listingsearch_callback') ) {

    /**
     * Save data for wp_dp_listingsearch shortcode.
     *
     * @param	array $args
     * @return	array
     */
    function wp_dp_cs_save_page_builder_data_wp_dp_listingsearch_callback($args) {

        $data = $args['data'];
        $counters = $args['counters'];
        $widget_type = $args['widget_type'];
        $column = $args['column'];
        $shortcode_data = '';
        if ( $widget_type == "wp_dp_listingsearch" || $widget_type == "cs_wp_dp_listingsearch" ) {
            $wp_dp_cs_bareber_wp_dp_listingsearch = '';

            $page_element_size = $data['wp_dp_listingsearch_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_listingsearch']];
            $current_element_size = $data['wp_dp_listingsearch_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_listingsearch']];

            if ( isset($data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']]) && $data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']] == 'shortcode' ) {
                $shortcode_str = stripslashes(( $data['shortcode']['wp_dp_listingsearch'][$counters['wp_dp_cs_shortcode_counter_wp_dp_listingsearch']]));

                $element_settings = 'wp_dp_listingsearch_element_size="' . $current_element_size . '"';
                $reg = '/wp_dp_listingsearch_element_size="(\d+)"/s';
                $shortcode_str = preg_replace($reg, $element_settings, $shortcode_str);
                $shortcode_data = $shortcode_str;

                $counters['wp_dp_cs_shortcode_counter_wp_dp_listingsearch'] ++;
            } else {

                $element_settings = 'wp_dp_listingsearch_element_size="' . htmlspecialchars($data['wp_dp_listingsearch_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_listingsearch']]) . '"';
                $wp_dp_cs_bareber_wp_dp_listingsearch = '[wp_dp_listingsearch ' . $element_settings . ' ';
                if ( isset($data['listingsearch_title'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['listingsearch_title'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'listingsearch_title="' . htmlspecialchars($data['listingsearch_title'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['listingsearch_alignment'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['listingsearch_alignment'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'listingsearch_alignment="' . htmlspecialchars($data['listingsearch_alignment'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['search_background_color'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['search_background_color'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'search_background_color="' . htmlspecialchars($data['search_background_color'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['listingsearch_subtitle'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['listingsearch_subtitle'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'listingsearch_subtitle="' . htmlspecialchars($data['listingsearch_subtitle'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }

                if ( isset($data['wp_dp_listing_search_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['wp_dp_listing_search_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'wp_dp_listing_search_element_title_color="' . htmlspecialchars($data['wp_dp_listing_search_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['wp_dp_listing_search_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['wp_dp_listing_search_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'wp_dp_listing_search_element_subtitle_color="' . htmlspecialchars($data['wp_dp_listing_search_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['wp_dp_search_label_color'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['wp_dp_search_label_color'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'wp_dp_search_label_color="' . htmlspecialchars($data['wp_dp_search_label_color'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['listingsearch_layout_bg'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['listingsearch_layout_bg'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'listingsearch_layout_bg="' . htmlspecialchars($data['listingsearch_layout_bg'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['listingsearch_layout_heading_color'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['listingsearch_layout_heading_color'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'listingsearch_layout_heading_color="' . htmlspecialchars($data['listingsearch_layout_heading_color'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['listingsearch_title_field_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['listingsearch_title_field_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'listingsearch_title_field_switch="' . htmlspecialchars($data['listingsearch_title_field_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['listingsearch_listing_type_field_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['listingsearch_listing_type_field_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'listingsearch_listing_type_field_switch="' . htmlspecialchars($data['listingsearch_listing_type_field_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['listingsearch_location_field_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['listingsearch_location_field_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'listingsearch_location_field_switch="' . htmlspecialchars($data['listingsearch_location_field_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['listingsearch_price_field_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['listingsearch_price_field_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'listingsearch_price_field_switch="' . htmlspecialchars($data['listingsearch_price_field_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['listingsearch_categories_field_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['listingsearch_categories_field_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'listingsearch_categories_field_switch="' . htmlspecialchars($data['listingsearch_categories_field_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['wp_dp_listing_search_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['wp_dp_listing_search_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'wp_dp_listing_search_seperator_style="' . htmlspecialchars($data['wp_dp_listing_search_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                } 
                if ( isset($data['listingsearch_advance_filter_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['listingsearch_advance_filter_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'listingsearch_advance_filter_switch="' . htmlspecialchars($data['listingsearch_advance_filter_switch'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['advance_link'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['advance_link'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'advance_link="' . htmlspecialchars($data['advance_link'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['popup_link_text'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['popup_link_text'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'popup_link_text="' . htmlspecialchars($data['popup_link_text'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }
                if ( isset($data['listingsearch_view'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['listingsearch_view'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'listingsearch_view="' . htmlspecialchars($data['listingsearch_view'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }

                // saving admin field using filter for add on
                $wp_dp_cs_bareber_wp_dp_listingsearch = apply_filters('wp_dp_save_listingsearch_shortcode_admin_fields', $wp_dp_cs_bareber_wp_dp_listingsearch, $_POST, $counters['wp_dp_cs_counter_wp_dp_listingsearch']);
                if ( isset($data['listingsearch_counter'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['listingsearch_counter'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= 'listingsearch_counter="' . htmlspecialchars($data['listingsearch_counter'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . '" ';
                }

                // Apply filter on default attributes Saving
                $section_title = apply_filters('wp_dp_shortcode_default_atts_save', $wp_dp_cs_bareber_wp_dp_listingsearch, $data, $counters['wp_dp_cs_counter_wp_dp_listingsearch'], array( 'responsive_atts' => true ));
                $wp_dp_cs_bareber_wp_dp_listingsearch = $section_title;
                $wp_dp_cs_bareber_wp_dp_listingsearch .= ']';
                if ( isset($data['help_text_popup'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']]) && $data['help_text_popup'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']] != '' ) {
                    $wp_dp_cs_bareber_wp_dp_listingsearch .= htmlspecialchars($data['help_text_popup'][$counters['wp_dp_cs_counter_wp_dp_listingsearch']], ENT_QUOTES) . ' ';
                }
                $wp_dp_cs_bareber_wp_dp_listingsearch .= '[/wp_dp_listingsearch]';
                $shortcode_data .= $wp_dp_cs_bareber_wp_dp_listingsearch;
                $counters['wp_dp_cs_counter_wp_dp_listingsearch'] ++;
            }
            $counters['wp_dp_cs_global_counter_wp_dp_listingsearch'] ++;
        }
        return array(
            'data' => $data,
            'counters' => $counters,
            'widget_type' => $widget_type,
            'column' => $shortcode_data,
        );
    }

    add_filter('wp_dp_cs_save_page_builder_data_wp_dp_listingsearch', 'wp_dp_cs_save_page_builder_data_wp_dp_listingsearch_callback');
}

if ( ! function_exists('wp_dp_cs_load_shortcode_counters_wp_dp_listingsearch_callback') ) {

    /**
     * Populate wp_dp_listingsearch shortcode counter variables.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_load_shortcode_counters_wp_dp_listingsearch_callback($counters) {
        $counters['wp_dp_cs_global_counter_wp_dp_listingsearch'] = 0;
        $counters['wp_dp_cs_shortcode_counter_wp_dp_listingsearch'] = 0;
        $counters['wp_dp_cs_counter_wp_dp_listingsearch'] = 0;
        return $counters;
    }

    add_filter('wp_dp_cs_load_shortcode_counters', 'wp_dp_cs_load_shortcode_counters_wp_dp_listingsearch_callback');
}



if ( ! function_exists('wp_dp_cs_element_list_populate_wp_dp_listingsearch_callback') ) {

    /**
     * Populate wp_dp_listingsearch shortcode strings list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_element_list_populate_wp_dp_listingsearch_callback($element_list) {
        $element_list['wp_dp_listingsearch'] = wp_dp_plugin_text_srt('wp_dp_listing_search_heading');
        return $element_list;
    }

    add_filter('wp_dp_cs_element_list_populate', 'wp_dp_cs_element_list_populate_wp_dp_listingsearch_callback');
}

if ( ! function_exists('wp_dp_cs_shortcode_names_list_populate_wp_dp_listingsearch_callback') ) {

    /**
     * Populate wp_dp_listingsearch shortcode names list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_shortcode_names_list_populate_wp_dp_listingsearch_callback($shortcode_array) {
        $shortcode_array['wp_dp_listingsearch'] = array(
            'title' => wp_dp_plugin_text_srt('wp_dp_listing_search_heading'),
            'name' => 'wp_dp_listingsearch',
            'icon' => 'icon-search',
            'categories' => 'typography',
        );

        return $shortcode_array;
    }

    add_filter('wp_dp_cs_shortcode_names_list_populate', 'wp_dp_cs_shortcode_names_list_populate_wp_dp_listingsearch_callback');
}
