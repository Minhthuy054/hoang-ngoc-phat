<?php
/**
 * Shortcode Name : wp_dp_members
 *
 * @package	wp_dp_cs 
 */
if (!function_exists('wp_dp_cs_var_page_builder_wp_dp_members')) {

    function wp_dp_cs_var_page_builder_wp_dp_members($die = 0) {
        global $post, $wp_dp_html_fields, $wp_dp_cs_node, $wp_dp_form_fields;
        if (function_exists('wp_dp_cs_shortcode_names')) {
            $shortcode_element = '';
            $filter_element = 'filterdrag';
            $shortcode_view = '';
            $wp_dp_cs_output = array();
            $wp_dp_cs_PREFIX = 'wp_dp_members';
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
                'members_title' => '',
                'members_subtitle' => '',
                'members_title_align' => '',
                'member_view' => '',
                'member_sort_by' => 'no',
                'posts_per_page' => '',
                'pagination' => '',
                'member_location' => '',
                'member_excerpt_length' => '',
                'member_left_filter' => '',
                'member_featured_only' => '',
                'wp_dp_memebers_element_title_color' => '',
                'wp_dp_memebers_element_subtitle_color' => '',
                'wp_dp_member_seperator_style' => '',
                'wp_dp_member_sidebar_switch' => '',
                'wp_dp_member_sidebar' => '',
            );
            $defaults = apply_filters('wp_dp_members_shortcode_admin_default_attributes', $defaults);

            if (isset($wp_dp_cs_output['0']['atts'])) {
                $atts = $wp_dp_cs_output['0']['atts'];
            } else {
                $atts = array();
            }
            if (isset($wp_dp_cs_output['0']['content'])) {
                $wp_dp_members_column_text = $wp_dp_cs_output['0']['content'];
            } else {
                $wp_dp_members_column_text = '';
            }
            $wp_dp_members_element_size = '100';
            foreach ($defaults as $key => $values) {
                if (isset($atts[$key])) {
                    $$key = $atts[$key];
                } else {
                    $$key = $values;
                }
            }
            $name = 'wp_dp_cs_var_page_builder_wp_dp_members';
            $coloumn_class = 'column_' . $wp_dp_members_element_size;
            if (isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode') {
                $shortcode_element = 'shortcode_element_class';
                $shortcode_view = 'cs-pbwp-shortcode';
                $filter_element = 'ajax-drag';
                $coloumn_class = '';
            }
            $member_rand_id = rand(4444, 99999);
            wp_enqueue_script('wp_dp_cs-admin-upload');
            $member_views = array(
                'alphabatic' => wp_dp_plugin_text_srt('wp_dp_shortcode_members_alphabatic'),
                'grid' => wp_dp_plugin_text_srt('wp_dp_shortcode_members_grid'),
                'grid-slider' => wp_dp_plugin_text_srt('wp_dp_shortcode_members_slider'),
                'list' => wp_dp_plugin_text_srt('wp_dp_shortcode_members_list'),
            );
            ?>

            <div id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class); ?>
                 <?php echo esc_attr($shortcode_view); ?>" item="wp_dp_members" data="<?php echo wp_dp_cs_element_size_data_array_index($wp_dp_members_element_size) ?>" >
                     <?php wp_dp_cs_element_setting($name, $wp_dp_cs_counter, $wp_dp_members_element_size) ?>
                <div class="cs-wrapp-class-<?php echo intval($wp_dp_cs_counter) ?>
                     <?php echo esc_attr($shortcode_element); ?>" id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>" data-shortcode-template="[wp_dp_members {{attributes}}]{{content}}[/wp_dp_members]" style="display: none;">
                    <div class="cs-heading-area" data-counter="<?php echo esc_attr($wp_dp_cs_counter) ?>">
                        <h5><?php echo wp_dp_plugin_text_srt('wp_dp_shortcode_members_options'); ?></h5>
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
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_members_title'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($members_title),
                                    'id' => 'members_title',
                                    'cust_name' => 'members_title[]',
                                    'return' => true,
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_members_sub_title'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($members_subtitle),
                                    'id' => 'members_subtitle',
                                    'cust_name' => 'members_subtitle[]',
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
                                    'std' => esc_attr($members_title_align),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'members_title_align[]',
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
                                    'std' => $wp_dp_memebers_element_title_color,
                                    'cust_name' => 'wp_dp_memebers_element_title_color[]',
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
                                    'std' => $wp_dp_memebers_element_subtitle_color,
                                    'cust_name' => 'wp_dp_memebers_element_subtitle_color[]',
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
                                    'std' => esc_attr($wp_dp_member_seperator_style),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'wp_dp_member_seperator_style[]',
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
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_members_view'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($member_view),
                                    'id' => 'member_view' . $member_rand_id . '',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'member_view[]',
                                    'extra_atr' => 'onchange="member_map_position' . $member_rand_id . '()"',
                                    'return' => true,
                                    'options' => $member_views
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_members_top_filters'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($member_left_filter),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'member_left_filter[]',
                                    'return' => true,
                                    'options' => array(
                                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                    ),
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_members_featured_only'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($member_featured_only),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'member_featured_only[]',
                                    'return' => true,
                                    'options' => array(
                                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                    ),
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                           
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_members_sort_by'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($member_sort_by),
                                    'id' => 'member_sort_by[]',
                                    'cust_name' => 'member_sort_by[]',
                                    'classes' => 'chosen-select-no-single',
                                    'return' => true,
                                    'options' => array(
                                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                    ),
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);


                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_members_excerpt_length'),
                                'desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($member_excerpt_length),
                                    'id' => 'member_excerpt_length',
                                    'cust_name' => 'member_excerpt_length[]',
                                    'return' => true,
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                            ?>

                            <script>
                                jQuery(document).ready(function () {
                                    jQuery(".save_member_locations_<?php echo absint($member_rand_id); ?>").click(function () {
                                        var MY_SELECT = jQuery('#wp_dp_member_locations_<?php echo absint($member_rand_id); ?>').get(0);
                                        var selection = ChosenOrder.getSelectionOrder(MY_SELECT);
                                        var member_location_value = '';
                                        var comma = '';
                                        jQuery(selection).each(function (i) {
                                            member_location_value = member_location_value + comma + selection[i];
                                            comma = ',';
                                        });
                                        jQuery('#member_location_<?php echo absint($member_rand_id); ?>').val(member_location_value);
                                    });

                                });
                            </script>
                            <?php
                            $saved_member_location = $member_location;
                            $member_location_options = array(
                                'country' => wp_dp_plugin_text_srt('wp_dp_options_country'),
                                'state' => wp_dp_plugin_text_srt('wp_dp_options_state'),
                                'city' => wp_dp_plugin_text_srt('wp_dp_options_city'),
                                'town' => wp_dp_plugin_text_srt('wp_dp_options_town'),
                                'address' => wp_dp_plugin_text_srt('wp_dp_options_town_complete_address'),
                            );

                            if ($saved_member_location != '') {
                                $member_locations = explode(',', $saved_member_location);
                                foreach ($member_locations as $member_loc) {
                                    $get_member_locations[$member_loc] = $member_location_options[$member_loc];
                                }
                            }
                            if ($get_member_locations) {
                                $member_location_options = array_unique(array_merge($get_member_locations, $member_location_options));
                            } else {
                                $member_location_options = $member_location_options;
                            }

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_location_filter'),
                                'desc' => '',
                                'label_desc' => '',
                                'multi' => true,
                                'echo' => true,
                                'field_params' => array(
                                    'std' => $saved_member_location,
                                    'id' => 'member_locations_' . $member_rand_id . '',
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'member_locations[]',
                                    'return' => true,
                                    'options' => $member_location_options,
                                ),
                            );
                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                            $wp_dp_cs_opt_array = array(
                                'std' => $member_location,
                                'cust_id' => 'member_location_' . $member_rand_id . '',
                                'cust_name' => "member_location[]",
                                'required' => false
                            );
                            $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_cs_opt_array);





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

                             ?>
                            <script>

                                function member_sidebar_switch<?php echo absint($member_rand_id); ?>($wp_dp_member_sidebar_switch) {
                                    if ($wp_dp_member_sidebar_switch == 'no') {
                                        jQuery('.member_sidebar_fields<?php echo absint($member_rand_id); ?>').hide();
                                    } else {
                                        jQuery('.member_sidebar_fields<?php echo absint($member_rand_id); ?>').show();
                                    }
                                }
                            </script>
                            <?php
                            
                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_sidebar'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'field_params' => array(
                                    'std' => esc_attr($wp_dp_member_sidebar_switch),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'wp_dp_member_sidebar_switch[]',
                                    'return' => true,
                                    'extra_atr' => 'onchange="member_sidebar_switch' . $member_rand_id . '()"',
                                    'options' => array(
                                        'no' => wp_dp_plugin_text_srt('wp_dp_listing_no'),
                                        'yes' => wp_dp_plugin_text_srt('wp_dp_listing_yes'),
                                    ),
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                            
                            $member_sidebar_switch_hide_show = 'style="display:none;"';
                            if ($wp_dp_member_sidebar_switch == 'yes') {
                                $member_sidebar_switch_hide_show = 'style="display:block;"';
                            }
                            
                            $sidebar_list = array('' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_no_sidebar'));
                            foreach ($GLOBALS['wp_registered_sidebars'] as $sidebar) {
                                $sidebar_list[$sidebar['id']] = $sidebar['name'];
                            }

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_shortcode_listings_sidebar'),
                                'desc' => '',
                                'label_desc' => '',
                                'echo' => true,
                                'main_wraper' => true,
                                'main_wraper_class' => 'member_sidebar_fields' . $member_rand_id . '',
                                'main_wraper_extra' => $member_sidebar_switch_hide_show,
                                'field_params' => array(
                                    'std' => esc_attr($wp_dp_member_sidebar),
                                    'classes' => 'chosen-select-no-single',
                                    'cust_name' => 'wp_dp_member_sidebar[]',
                                    'return' => true,
                                    'options' => $sidebar_list
                                ),
                            );

                            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);


                            $wp_dp_cs_opt_array = array(
                                'std' => absint($member_rand_id),
                                'id' => '',
                                'cust_id' => 'member_counter',
                                'cust_name' => 'member_counter[]',
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
                                'std' => 'wp_dp_members',
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
                                    'cust_id' => 'wp_dp_members_save',
                                    'cust_type' => 'button',
                                    'extra_atr' => 'onclick="javascript:_removerlay(jQuery(this))"',
                                    'classes' => 'cs-wp_dp_cs-admin-btn save_member_locations_' . $member_rand_id . '',
                                    'cust_name' => 'wp_dp_members_save',
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

    add_action('wp_ajax_wp_dp_cs_var_page_builder_wp_dp_members', 'wp_dp_cs_var_page_builder_wp_dp_members');
}

if (!function_exists('wp_dp_cs_save_page_builder_data_wp_dp_members_callback')) {

    /**
     * Save data for wp_dp_members shortcode.
     *
     * @param	array $args
     * @return	array
     */
    function wp_dp_cs_save_page_builder_data_wp_dp_members_callback($args) {

        $data = $args['data'];
        $counters = $args['counters'];
        $widget_type = $args['widget_type'];
        $column = $args['column'];
        $shortcode_data = '';
        if ($widget_type == "wp_dp_members" || $widget_type == "cs_wp_dp_members") {
            $wp_dp_cs_bareber_wp_dp_members = '';

            $page_element_size = $data['wp_dp_members_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_members']];
            $current_element_size = $data['wp_dp_members_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_members']];
            if (isset($data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']]) && $data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']] == 'shortcode') {
                $shortcode_str = stripslashes(( $data['shortcode']['wp_dp_members'][$counters['wp_dp_cs_shortcode_counter_wp_dp_members']]));

                $element_settings = 'wp_dp_members_element_size="' . $current_element_size . '"';
                $reg = '/wp_dp_members_element_size="(\d+)"/s';
                $shortcode_str = preg_replace($reg, $element_settings, $shortcode_str);
                $shortcode_data = $shortcode_str;

                $counters['wp_dp_cs_shortcode_counter_wp_dp_members'] ++;
            } else {
                $element_settings = 'wp_dp_members_element_size="' . htmlspecialchars($data['wp_dp_members_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_members']]) . '"';
                $wp_dp_cs_bareber_wp_dp_members = '[wp_dp_members ' . $element_settings . ' ';
                if (isset($data['members_title'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['members_title'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'members_title="' . htmlspecialchars($data['members_title'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                if (isset($data['wp_dp_memebers_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['wp_dp_memebers_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'wp_dp_memebers_element_title_color="' . htmlspecialchars($data['wp_dp_memebers_element_title_color'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                if (isset($data['wp_dp_memebers_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['wp_dp_memebers_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'wp_dp_memebers_element_subtitle_color="' . htmlspecialchars($data['wp_dp_memebers_element_subtitle_color'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                if (isset($data['members_title_align'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['members_title_align'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'members_title_align="' . htmlspecialchars($data['members_title_align'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                if (isset($data['members_subtitle'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['members_subtitle'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'members_subtitle="' . htmlspecialchars($data['members_subtitle'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                if (isset($data['member_type'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['member_type'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'member_type="' . htmlspecialchars($data['member_type'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                if (isset($data['member_excerpt_length'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['member_excerpt_length'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'member_excerpt_length="' . htmlspecialchars($data['member_excerpt_length'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                if (isset($data['member_view'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['member_view'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'member_view="' . htmlspecialchars($data['member_view'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                if (isset($data['member_left_filter'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['member_left_filter'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'member_left_filter="' . htmlspecialchars($data['member_left_filter'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                if (isset($data['member_location'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['member_location'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'member_location="' . htmlspecialchars($data['member_location'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                if (isset($data['member_featured_only'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['member_featured_only'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'member_featured_only="' . htmlspecialchars($data['member_featured_only'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                if (isset($data['member_sort_by'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['member_sort_by'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'member_sort_by="' . htmlspecialchars($data['member_sort_by'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                if (isset($data['wp_dp_member_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['wp_dp_member_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'wp_dp_member_seperator_style="' . htmlspecialchars($data['wp_dp_member_seperator_style'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                if (isset($data['posts_per_page'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['posts_per_page'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'posts_per_page="' . htmlspecialchars($data['posts_per_page'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                if (isset($data['pagination'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['pagination'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'pagination="' . htmlspecialchars($data['pagination'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                 if (isset($data['wp_dp_member_sidebar_switch'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['wp_dp_member_sidebar_switch'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'wp_dp_member_sidebar_switch="' . htmlspecialchars($data['wp_dp_member_sidebar_switch'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                 if (isset($data['wp_dp_member_sidebar'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['wp_dp_member_sidebar'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'wp_dp_member_sidebar="' . htmlspecialchars($data['wp_dp_member_sidebar'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                if (isset($data['member_counter'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['member_counter'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= 'member_counter="' . htmlspecialchars($data['member_counter'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . '" ';
                }
                $wp_dp_cs_bareber_wp_dp_members .= ']';
                if (isset($data['wp_dp_members_column_text'][$counters['wp_dp_cs_counter_wp_dp_members']]) && $data['wp_dp_members_column_text'][$counters['wp_dp_cs_counter_wp_dp_members']] != '') {
                    $wp_dp_cs_bareber_wp_dp_members .= htmlspecialchars($data['wp_dp_members_column_text'][$counters['wp_dp_cs_counter_wp_dp_members']], ENT_QUOTES) . ' ';
                }
                $wp_dp_cs_bareber_wp_dp_members .= '[/wp_dp_members]';
                $shortcode_data .= $wp_dp_cs_bareber_wp_dp_members;
                $counters['wp_dp_cs_counter_wp_dp_members'] ++;
            }
            $counters['wp_dp_cs_global_counter_wp_dp_members'] ++;
        }
        return array(
            'data' => $data,
            'counters' => $counters,
            'widget_type' => $widget_type,
            'column' => $shortcode_data,
        );
    }

    add_filter('wp_dp_cs_save_page_builder_data_wp_dp_members', 'wp_dp_cs_save_page_builder_data_wp_dp_members_callback');
}

if (!function_exists('wp_dp_cs_load_shortcode_counters_wp_dp_members_callback')) {

    /**
     * Populate wp_dp_members shortcode counter variables.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_load_shortcode_counters_wp_dp_members_callback($counters) {
        $counters['wp_dp_cs_global_counter_wp_dp_members'] = 0;
        $counters['wp_dp_cs_shortcode_counter_wp_dp_members'] = 0;
        $counters['wp_dp_cs_counter_wp_dp_members'] = 0;
        return $counters;
    }

    add_filter('wp_dp_cs_load_shortcode_counters', 'wp_dp_cs_load_shortcode_counters_wp_dp_members_callback');
}



if (!function_exists('wp_dp_cs_element_list_populate_wp_dp_members_callback')) {

    /**
     * Populate wp_dp_members shortcode strings list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_element_list_populate_wp_dp_members_callback($element_list) {
        $element_list['wp_dp_members'] = wp_dp_plugin_text_srt('wp_dp_shortcode_members_heading');
        return $element_list;
    }

    add_filter('wp_dp_cs_element_list_populate', 'wp_dp_cs_element_list_populate_wp_dp_members_callback');
}

if (!function_exists('wp_dp_cs_shortcode_names_list_populate_wp_dp_members_callback')) {

    /**
     * Populate wp_dp_members shortcode names list.
     *
     * @param	array $counters
     * @return	array
     */
    function wp_dp_cs_shortcode_names_list_populate_wp_dp_members_callback($shortcode_array) {
        $shortcode_array['wp_dp_members'] = array(
            'title' => wp_dp_plugin_text_srt('wp_dp_shortcode_members_heading'),
            'name' => 'wp_dp_members',
            'icon' => 'icon-people2',
            'categories' => 'typography',
        );

        return $shortcode_array;
    }

    add_filter('wp_dp_cs_shortcode_names_list_populate', 'wp_dp_cs_shortcode_names_list_populate_wp_dp_members_callback');
}
