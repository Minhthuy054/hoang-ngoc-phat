<?php
/**
 * Listing search box
 * default variable which is getting from ajax request or shotcode
 * $listing_short_counter, $listing_arg
 */
global $wp_dp_plugin_options, $wp_dp_form_fields_frontend, $wp_dp_post_listing_types, $wp_dp_shortcode_listings_frontend, $wp_dp_split_map_search_fields;



wp_enqueue_script('bootstrap-datepicker');
wp_enqueue_style('datetimepicker');
wp_enqueue_style('datepicker');
wp_enqueue_script('datetimepicker');

$listing_type = isset($atts['listing_type']) ? $atts['listing_type'] : '';
$map_location_title_filter = isset($atts['map_location_title_filter']) ? $atts['map_location_title_filter'] : '';
$element_map_price_filter = isset($atts['listing_price_filter']) ? $atts['listing_price_filter'] : '';

$more_filters = false;
if ( ( isset($atts['search_box']) && $atts['search_box'] != 'no' && isset($atts['wp_dp_map_position']) && $atts['wp_dp_map_position'] != 'top' ) ) {
    $more_filters = true;
}
$listing_types_array = array();
$listing_type_slug = '';

if ( isset($map_location_title_filter) && $map_location_title_filter == 'yes' ) {
    ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="main-search split-map">
                <?php echo wp_dp_wpml_lang_code_field(); ?>
                <div class="search-default-fields">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <div class="field-holder search-input with-search-country">
                                <label>
                                    <i class="icon-search4"></i>
                                    <?php
                                    $search_title = isset($_REQUEST['search_title']) ? $_REQUEST['search_title'] : '';
                                    $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                                            array(
                                                'cust_name' => 'search_title',
                                                'classes' => 'input-field',
                                                'std' => $search_title,
                                                'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_listing_search_flter_wt_looking_for') . '" onkeyup="wp_dp_submit_top_search_form(\'' . $listing_short_counter . '\');"',
                                            )
                                    );
                                    ?> 
                                </label>
                                <script type="text/javascript">
                                    //jQuery(document).ready(function ($) {
                                    var timer = 0;
                                    function wp_dp_submit_top_search_form(listing_short_counter) {
                                        clearTimeout(timer);
                                        timer = setTimeout(function () {
                                            wp_dp_split_map_change_cords(listing_short_counter);
                                        }, 1000);
                                    }
                                    //});
                                </script>

                                <?php
                                $wp_dp_select_display = 1;
                                wp_dp_get_custom_locations_listing_filter('<div id="wp-dp-split-map-location-select-holder" class="search-country" style="display:' . wp_dp_allow_special_char($wp_dp_select_display) . '"><div class="select-holder">', '</div></div>', false, $listing_short_counter);
                                ?>
                                <script type="text/javascript">
                                    jQuery(document).ready(function ($) {
                                        $(".location-field-text<?php echo esc_html($listing_short_counter); ?>").on('change', function () {
                                            wp_dp_submit_top_search_form('<?php echo esc_html($listing_short_counter); ?>');
                                        });
                                    });
                                    var timer = 0;

                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
add_action('wp_dp_search_more_filter', 'wp_dp_search_more_filter_callback', 10, 1);

function wp_dp_search_more_filter_callback($atts = '') {


    global $wp_dp_plugin_options, $wp_dp_form_fields_frontend, $wp_dp_post_listing_types, $wp_dp_shortcode_listings_frontend, $wp_dp_split_map_search_fields;

    wp_enqueue_script('bootstrap-datepicker');
    wp_enqueue_style('datetimepicker');
    wp_enqueue_style('datepicker');
    wp_enqueue_script('datetimepicker');
    ?><div class="main-search split-map">
    <?php
    $listing_type = isset($atts['listing_type']) ? $atts['listing_type'] : '';
    $listing_short_counter = isset($atts['listing_short_counter']) ? $atts['listing_short_counter'] : '';
    $element_map_price_filter = isset($atts['listing_price_filter']) ? $atts['listing_price_filter'] : '';
    $args_count = isset($atts['args_count']) ? $atts['args_count'] : array();
    $more_filters = false;
    if ( ( isset($atts['search_box']) && $atts['search_box'] != 'no' && isset($atts['wp_dp_map_position']) && $atts['wp_dp_map_position'] != 'top' ) ) {
        $more_filters = true;
    }
    $listing_types_array = array();
    $listing_type_slug = '';
    ?>
        <div class="dropdown-with-btn">
            <div class="field-holder select-dropdown listing-type js-field-dropdown"> 
                <?php
                $listing_type = isset($_REQUEST['listing_type']) ? $_REQUEST['listing_type'] : $listing_type;
                $wp_dp_post_listing_types = new Wp_dp_Post_Listing_Types();
                $all_type_label = wp_dp_plugin_text_srt('wp_dp_listing_listing_type_all_categories');
                $listing_types_array = $wp_dp_post_listing_types->wp_dp_types_array_callback('NULL');
                if ( is_array($listing_types_array) && ! empty($listing_types_array) && $listing_type == '' ) {
                    foreach ( $listing_types_array as $key => $value ) {
                        $listing_type_slug = $key;
                        break;
                    }
                } else {
                    $listing_type_slug = $listing_type;
                }

                $output = '';
                $output .= '<div class="select-listing-type">';
                $output .= '<span class="selected-type">';
                if ( $listing_type == '' || $listing_type == 'all' ) {
                    $output .= wp_dp_plugin_text_srt('wp_dp_listing_listing_type_all_categories');
                } else {
                    $listing_type_id = $wp_dp_split_map_search_fields->wp_dp_listing_type_id_by_slug($listing_type);
                    $output .= esc_html(get_the_title($listing_type_id));
                }
                $output .= '</span>';
                $output .= '<ul class="listing-types">';
                foreach ( $listing_types_array as $key => $val ) {
                    $checked = '';
                    if ( $key == $listing_type ) {
                        $checked = 'checked';
                    }
                    $listing_type_id = $wp_dp_split_map_search_fields->wp_dp_listing_type_id_by_slug($key);
                    $listing_type_icon = get_post_meta($listing_type_id, 'wp_dp_listing_type_icon', true);
                    $listing_type_icon = isset($listing_type_icon[0]) ? $listing_type_icon[0] : $listing_type_icon;
                    $output .= '<li>';
                    $output .= '<div class="radio">';
                    $wp_dp_opt_array = array(
                        'std' => $key,
                        'cust_id' => 'search_form_listing_type' . $key,
                        'classes' => '',
                        'cust_name' => 'listing_type',
                        'extra_atr' => ' data-label="' . $val . '" ' . $checked . '',
                        'return' => true,
                    );
                    $output .= $wp_dp_form_fields_frontend->wp_dp_form_radio_render($wp_dp_opt_array);
                    $output .= '<label for="search_form_listing_type' . $key . '">';
                    $output .= $val;
                    $output .= '</label>';
                    $output .= '</div>';
                    if ( $listing_type_icon != '' ) {
                        $type_selected_icon_group = get_post_meta($listing_type_id, 'wp_dp_listing_type_icon_group', true);
                        $type_selected_icon_group = isset($type_selected_icon_group[0]) ? $type_selected_icon_group[0] : 'default';
                        wp_enqueue_style('cs_icons_data_css_' . $type_selected_icon_group);
                        $output .= '<span>';
                        $output .= '<i class="' . $listing_type_icon . '"></i>';
                        $output .= '</span>';
                    }
                    $output .= '</li>';
                }
                $output .= '</ul>';
                $output .= '</div>';

                echo wp_dp_cs_allow_special_char($output);
                ?>
                <script type="text/javascript">
                    var timer = 0;
                    
                    jQuery(document).on('click', '.listing-types .radio label', function() {
                        var for_id = jQuery(this).attr('for');
                        jQuery('#'+for_id).change();
                        jQuery('#'+for_id).prop( "checked", true );
                        
                    });
                    
                    jQuery('input:radio[name=listing_type]').change(function () {
                        var label = jQuery(this).data('label');
                        jQuery('.adv_filter_toggle').val('true');
                        jQuery('.select-listing-type span.selected-type').html(label);
                        jQuery('.js-field-dropdown').removeClass('js-field-open');
                        clearTimeout(timer);
                        timer = setTimeout(function () {
                            wp_dp_split_map_change_cords(<?php echo esc_js($listing_short_counter); ?>);
                        }, 500);
                    });
					
                    jQuery(document).on("click", ".js-field-dropdown", function () {
                            "use strict";
                            var js_field_dropdown = $('.js-field-dropdown');
                            var js_current_object = jQuery(this);
                            js_field_dropdown.removeClass('js-field-open');
                            if (!js_current_object.hasClass('js-field-open')) {
                                    js_current_object.addClass('js-field-open');
                                    $(document).one('click', function closeTooltip(e) {
                                            if (js_current_object.has(e.target).length === 0 && $('.js-field-dropdown').has(e.target).length === 0) {
                                                    js_current_object.removeClass('js-field-open');
                                                    js_field_dropdown.removeClass('js-field-open');
                                            } else if (js_current_object.hasClass('js-field-open')) {
                                                    $(document).one('click', closeTooltip);
                                            }
                                    });
                            } else {
                                    js_field_dropdown.removeClass('js-field-open');
                            }
                    });
                </script>
            </div>
            <?php
            $args = array(
                'name' => $listing_type_slug,
                'post_type' => 'listing-type',
                'post_status' => 'publish',
                'numberposts' => 1,
            );
            $my_posts = get_posts($args);
            if ( $my_posts ) {
                $listing_type_id = $my_posts[0]->ID;
            }
            $wp_dp_listing_type_price = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
            if ( ($wp_dp_listing_type_price == 'on' && $listing_type_id != '') || $listing_type_id == '' ) {
                $wp_dp_listing_type_price_search_style = get_post_meta($listing_type_id, 'wp_dp_listing_type_price_search_style', true);
                $wp_dp_price_minimum_options = get_post_meta($listing_type_id, 'wp_dp_price_minimum_options', true);
                $wp_dp_price_minimum_options = ( ! empty($wp_dp_price_minimum_options) ) ? $wp_dp_price_minimum_options : 1;
                $wp_dp_price_max_options = get_post_meta($listing_type_id, 'wp_dp_price_max_options', true);
                $wp_dp_price_max_options = ( ! empty($wp_dp_price_max_options) ) ? $wp_dp_price_max_options : 50; //50000;
                $wp_dp_price_interval = get_post_meta($listing_type_id, 'wp_dp_price_interval', true);
                $wp_dp_price_interval = ( ! empty($wp_dp_price_interval) ) ? $wp_dp_price_interval : 50;
                $wp_dp_price_interval = (int) $wp_dp_price_interval;
                $price_counter = $wp_dp_price_minimum_options;
                $price_min = array();
                $price_max = array();
                // gettting all values of price
                $price_min = wp_dp_listing_price_options($wp_dp_price_minimum_options, $wp_dp_price_max_options, $wp_dp_price_interval, wp_dp_plugin_text_srt('wp_dp_listing_search_flter_min_price'));
                $price_max = wp_dp_listing_price_options($wp_dp_price_minimum_options, $wp_dp_price_max_options, $wp_dp_price_interval, wp_dp_plugin_text_srt('wp_dp_listing_search_flter_max_price'));

                if ( $listing_type_id == '' ) {
                    $wp_dp_listing_type_price_search_style = 'slider';
                }
                if ( $element_map_price_filter == 'yes' ) {
                    ?>

                    <div class="field-holder price-filters-btn js-field-dropdown">
                        <a id ="price-visible" href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_split_map_filter_price_range'); ?><i class="icon-keyboard_arrow_down"></i></a>
                        <div id="price-filter-visibility" class="price-filter-visibility">  
                            <?php
                            // element price filter on/off
                            if ( $wp_dp_listing_type_price_search_style != 'dropdown' ) {
                                $rand_id = rand(12345, 54321);
                                $min_val = $wp_dp_price_minimum_options;
                                $max_val = end($price_max);

                                if ( $listing_type_id == '' ) {
                                    $min_val = 1;
                                    $max_val = 19999;
                                    $wp_dp_price_interval = 50;
                                }

                                $query_str_var_name = 'listing_price_range';
                                if ( (isset($min_val) && $min_val != '') && (isset($max_val) && $max_val != '' ) ) {

                                    $selected_min_val = '';
                                    $selected_max_val = '';
                                    $range_complete_str_first = $min_val;
                                    $range_complete_str_second = $max_val;
                                    $range_complete_str = '';
                                    if ( isset($_REQUEST['price_minimum']) && $_REQUEST['price_minimum'] != '' ) {
                                        $range_complete_str_first = $_REQUEST['price_minimum'];
                                        $selected_min_val = $_REQUEST['price_minimum'];
                                    }
                                    if ( isset($_REQUEST['price_maximum']) && $_REQUEST['price_maximum'] != '' ) {
                                        $range_complete_str_second = $_REQUEST['price_maximum'];
                                        $selected_max_val = $_REQUEST['price_maximum'];
                                    }
                                    $range_complete_str = $range_complete_str_first . ',' . $range_complete_str_second;

                                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                                            array(
                                                'simple' => true,
                                                'cust_id' => "range-hidden-price-minimum-" . $listing_short_counter,
                                                'cust_name' => 'price_minimum',
                                                'std' => esc_html($selected_min_val),
                                                'classes' => 'price-minimum',
                                            )
                                    );

                                    $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                                            array(
                                                'simple' => true,
                                                'cust_id' => "range-hidden-price-maximum-" . $listing_short_counter,
                                                'cust_name' => 'price_maximum',
                                                'std' => esc_html($selected_max_val),
                                                'classes' => 'price-maximum',
                                            )
                                    );
                                    ?>
                                    <div class="field-holder field-range split-map">
                                        <div class="price-per-person">
                                            <span class="rang-text"><span class="min-val"><?php echo esc_html($range_complete_str_first); ?></span> &nbsp; - &nbsp; <span class="max-val"><?php echo esc_html($range_complete_str_second); ?></span></span>
                                            <?php
                                            $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                                                    array(
                                                        'cust_name' => '',
                                                        'cust_id' => 'ex16b1' . esc_html($rand_id . $query_str_var_name),
                                                        'std' => '',
                                                    )
                                            );
                                            ?>  
                                        </div>
                                    </div>
                                    <?php
                                    $increment_step = isset($wp_dp_price_interval) ? $wp_dp_price_interval : 1;
                                    if ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
                                        echo '<script type="text/javascript">
									if (jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").length > 0) {
										jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").slider({
											step : ' . esc_html($increment_step) . ',
											min: ' . esc_html($min_val) . ',
											max: ' . esc_html($max_val) . ',
                                            tooltip :"hide",   
											value: [ ' . esc_html($range_complete_str) . '],
										});
                                        jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").on("slide", function(slideEvt) {
												var rang_slider_val = jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").val();
												var slider_val = rang_slider_val.split(",");
												var price_minimum = slider_val[0], price_maximum = slider_val[1];
												jQuery(".min-val").html(price_minimum);
												jQuery(".max-val").html(price_maximum);
										});
										jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").on("slideStop", function () {
											var rang_slider_val = jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").val();
											var slider_val = rang_slider_val.split(",");
											var price_minimum = slider_val[0], price_maximum = slider_val[1];
											jQuery("#range-hidden-price-minimum-' . $listing_short_counter . '").val(price_minimum);
											jQuery("#range-hidden-price-maximum-' . $listing_short_counter . '").val(price_maximum);
											wp_dp_split_map_change_cords("' . esc_html($listing_short_counter) . '");
										});
									}
								</script>';
                                    } else {
                                        echo '<script type="text/javascript">
									jQuery(window).load(function(){
										if (jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").length > 0) {
											jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").slider({
												step : ' . esc_html($increment_step) . ',
												min: ' . esc_html($min_val) . ',
												max: ' . esc_html($max_val) . ',
                                                tooltip :"hide",   
												value: [ ' . esc_html($range_complete_str) . '],
											});
											jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").on("slide", function(slideEvt) {
													var rang_slider_val = jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").val();
													var slider_val = rang_slider_val.split(",");
													var price_minimum = slider_val[0], price_maximum = slider_val[1];
													jQuery(".min-val").html(price_minimum);
													jQuery(".max-val").html(price_maximum);
											});
                                            jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").on("slideStop", function () {
												var rang_slider_val = jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").val();
												var slider_val = rang_slider_val.split(",");
												var price_minimum = slider_val[0], price_maximum = slider_val[1];
												jQuery("#range-hidden-price-minimum-' . $listing_short_counter . '").val(price_minimum);
												jQuery("#range-hidden-price-maximum-' . $listing_short_counter . '").val(price_maximum);
												wp_dp_split_map_change_cords("' . esc_html($listing_short_counter) . '");
											});
										}
									});
								</script>';
                                    }
                                }
                            } else {
                                ?> 
                                <div class="field-holder select-dropdown">
                                    <div class="wp-dp-min-max-price">
                                        <div class="select-categories"> 
                                            <ul>
                                                <li>
                                                    <?php
                                                    $price_min_checked = ( isset($_REQUEST['price_minimum']) && $_REQUEST['price_minimum'] ) ? $_REQUEST['price_minimum'] : '';
                                                    $wp_dp_form_fields_frontend->wp_dp_form_select_render(
                                                            array(
                                                                'simple' => true,
                                                                'cust_name' => 'price_minimum',
                                                                'std' => $price_min_checked,
                                                                'classes' => 'chosen-select-no-single',
                                                                'options' => $price_min,
                                                                'extra_atr' => 'onchange="wp_dp_split_map_change_cords(\'' . $listing_short_counter . '\');"',
                                                            )
                                                    );
                                                    ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="select-categories"> 
                                            <ul>
                                                <li >
                                                    <?php
                                                    $price_max_checked = ( isset($_REQUEST['price_maximum']) && $_REQUEST['price_maximum'] ) ? $_REQUEST['price_maximum'] : '';
                                                    $wp_dp_form_fields_frontend->wp_dp_form_select_render(
                                                            array(
                                                                'simple' => true,
                                                                'cust_name' => 'price_maximum',
                                                                'std' => $price_max_checked,
                                                                'classes' => 'chosen-select-no-single',
                                                                'options' => $price_max,
                                                                'extra_atr' => 'onchange="wp_dp_split_map_change_cords(\'' . $listing_short_counter . '\');"',
                                                            )
                                                    );
                                                    ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div> 
                    </div>
                    <?php
                }
            }
            ?>
            <div class="user-location-filters">
                <?php if ( isset($atts['listing_sort_by']) && $atts['listing_sort_by'] != 'no' ) { ?>

                    <div class="years-select-box">
                        <div class="input-field">
                            <?php
                            $sort_std = '';
                            if( isset($atts['listing_sort_by']) && !empty($atts['listing_sort_by'])){
                                $sort_std = $atts['listing_sort_by'];
                            }
                            if( isset($_REQUEST['sort-by']) && !empty($_REQUEST['sort-by'])){
                                $sort_std = $_REQUEST['sort-by'];
                            }
                            $wp_dp_opt_array = array(
                                'std' => $sort_std,
                                'id' => 'pagination',
                                'classes' => 'chosen-select-no-single',
                                'cust_name' => 'sort-by',
                                'extra_atr' => 'onchange="wp_dp_split_map_change_cords(\'' . $listing_short_counter . '\');"',
                                'options' => array(
                                    '' => wp_dp_plugin_text_srt('wp_dp_listings_sort_by'),
                                    'relevence' => wp_dp_plugin_text_srt('wp_dp_cs_var_filter_listing_relevence'),
                                    'recent' => wp_dp_plugin_text_srt('wp_dp_cs_var_filter_listing_newest'),
                                    'low_price' => wp_dp_plugin_text_srt('wp_dp_cs_var_filter_lowest_price'),
                                    'high_price' => wp_dp_plugin_text_srt('wp_dp_cs_var_filter_highest_price'),
                                    'high_rated' => wp_dp_plugin_text_srt('wp_dp_cs_var_filter_listing_highest_rated'),
                                    'most_viewed' => wp_dp_plugin_text_srt('wp_dp_cs_var_filter_listing_most_viewd')
                                ),
                            );

                            $wp_dp_form_fields_frontend->wp_dp_form_select_render($wp_dp_opt_array);
                            ?>

                        </div>
                    </div> 
                    <?php
                }
                ?>
            </div> 
            <?php
            $adv_filter_toggle = isset($_REQUEST['adv_filter_toggle']) ? $_REQUEST['adv_filter_toggle'] : 'false';
            $open_class = '';
            if ( $adv_filter_toggle == 'true' ) {
                $open_class = ' open';
            }
            if ( $listing_type_slug == '' || $listing_type == 'all' ) {
                ?>
                <div class="field-holder more-filters-btn<?php echo $open_class; ?>">
                    <a href="javascript:void(0);" title="Select Category" data-toggle="popover" data-placement="bottom" data-trigger="hover" data-content="Please select atleast one category to view its filter"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_search_more_filters'); ?><i class="icon-keyboard_arrow_down"></i></a>
                </div>
                <?php
            } else {
                if ( $more_filters == true ) {
                    ?>
                    <div class="field-holder more-filters-btn<?php echo $open_class; ?>">
                        <a id="adv_filter" href="javascript:void(0);" onclick="wp_dp_advanced_search_field('<?php echo wp_dp_allow_special_char($listing_short_counter); ?>');"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_search_more_filters'); ?><i class="icon-keyboard_arrow_down"></i></a>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <div class="split-map-search-btn"><?php do_action('pre_wp_dp_listings_listing', 'save_search'); ?></div>
        <!-- start category -->
        <?php
        $listing_type_cats = $wp_dp_split_map_search_fields->wp_dp_listing_type_categories_options($listing_type_slug);
        if ( ! empty($listing_type_cats) && ($listing_type != '' && $listing_type != 'all' ) ) {
            $listing_type_category_name = 'wp_dp_listing_category';
            $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                    array(
                        'simple' => true,
                        'cust_id' => "hidden_input-" . $listing_type_category_name,
                        'cust_name' => 'listing_category',
                        'std' => isset($listing_category) ? $listing_category : '',
                        'classes' => $listing_type_category_name,
                        'extra_atr' => 'onchange="wp_dp_split_map_change_cords(\'' . $listing_short_counter . '\');"',
                    )
            );

            $adv_filter_toggle = isset($_REQUEST['adv_filter_toggle']) ? $_REQUEST['adv_filter_toggle'] : 'false';
            $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                    array(
                        'simple' => true,
                        'classes' => "adv_filter_toggle",
                        'cust_name' => 'adv_filter_toggle',
                        'std' => $adv_filter_toggle,
                    )
            );
            ?>
            <script>
                jQuery(function () {

                    var $checkboxes = jQuery("input[type=checkbox].<?php echo esc_html($listing_type_category_name); ?>");
                    $checkboxes.on('change', function () {
                        var ids = $checkboxes.filter(':checked').map(function () {
                            return this.value;
                        }).get().join(',');
                        jQuery('#hidden_input-<?php echo esc_html($listing_type_category_name); ?>').val(ids);
                        jQuery('.adv_filter_toggle').val();
                        wp_dp_split_map_change_cords('<?php echo esc_html($listing_short_counter); ?>');
                    });
                });

                jQuery('#adv_filter').click(function (e) {
                    var adv_val = jQuery('.adv_filter_toggle').val();
                    if (adv_val == 'true') {
                        jQuery('input[name="adv_filter_toggle"]').val('false');
                    } else {
                        jQuery('input[name="adv_filter_toggle"]').val('true');
                    }
                });

            </script>




			<?php if( isset( $listing_type_cats ) && !empty( $listing_type_cats ) ) { ?>
            <div class="sub-categories-filters">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 features-list">

                        <div class="features-field-expand">
                            <div class="row">
                                <ul class="search-features-list">
                                    <?php
                                    $category_list_flag = 0;
                                    foreach ( $listing_type_cats as $listing_type_cat_slug => $listing_type_cat ) {
                                        if ( $category_list_flag == 0 ) {
                                            $category_list_flag ++;
                                            continue;
                                        }
										
                                        $term = get_term_by('slug', $listing_type_cat_slug, 'listing-category');
										
										if(isset( $term ) && !empty( $term )){
                                        // extra condidation
                                        $cate_count_arr = array(
                                            'key' => $listing_type_category_name,
                                            'value' => serialize($term->slug),
                                            'compare' => 'LIKE',
                                        );
                                        // main query array $args_count
                                        ?>
                                        <li class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <div class="checkbox">
                                                <?php
                                                $checked = '';
                                                if ( isset($_REQUEST['listing_category']) && $_REQUEST['listing_category'] != '' ) {
                                                    $selected_cats = explode(',', $_REQUEST['listing_category']);
                                                    if ( isset($selected_cats) && is_array($selected_cats) && in_array($term->slug, $selected_cats) ) {
                                                        $checked = ' checked="checked"';
                                                    }
                                                }
                                                $wp_dp_form_fields_frontend->wp_dp_form_checkbox_render(
                                                        array(
                                                            'simple' => true,
                                                            'cust_id' => $listing_type_category_name . '_' . $category_list_flag,
                                                            'cust_name' => '',
                                                            'std' => $term->slug,
                                                            'classes' => $listing_type_category_name,
                                                            'extra_atr' => $checked,
                                                        )
                                                );
                                                ?>
                                                <label for="<?php echo force_balance_tags($listing_type_category_name . '_' . $category_list_flag); ?>"><?php echo esc_html($term->name); ?></label>
                                                <?php if ( isset($left_filter_count_switch) && $left_filter_count_switch == 'yes' ) { ?><span>(<?php echo esc_html($cate_totnum); ?>)</span><?php } ?>
                                            </div>
                                        </li>
                                        <?php $category_list_flag ++; ?>
                                    <?php }
									}
                                    ?>
                                </ul>
                                <?php
                                $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                                        array(
                                            'simple' => true,
                                            'cust_id' => "adv_filter_toggle-" . $listing_short_counter,
                                            'cust_name' => 'adv_filter_toggle',
                                            'std' => $adv_filter_toggle,
                                        )
                                );
                                ?>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<?php } ?>
            <?php
        }
        ?>
        <!-- end category -->

        <?php
        if ( $listing_type_slug != '' ) {

            $ajax_filter = isset($_REQUEST['ajax_filter']) ? $_REQUEST['ajax_filter'] : false;
            $adv_filter_toggle = isset($_REQUEST['adv_filter_toggle']) ? $_REQUEST['adv_filter_toggle'] : $atts['adv_filter_toggle'];
            $displayClass = '';
            if ( $adv_filter_toggle == 'true' ) {
                $style = 'block;';
                $displayClass = ' filters-shown';
                
            } else {
                $style = 'none;';
            }
            ?>
            <div id="listing_type_fields_<?php echo wp_dp_allow_special_char($listing_short_counter); ?>" class="search-advanced-fields<?php echo esc_attr( $displayClass ); ?>">
                <div class="listing-type-fields-holder">
                    <div class="wp-dp-splitmap-advance-filter_<?php echo wp_dp_allow_special_char($listing_short_counter); ?>">
                    </div>
                    <div class="split-map-separator"></div>
                    <?php
                    do_action('wp_dp_listing_type_fields_split_map', $listing_type_slug, $listing_short_counter);
                    do_action('wp_dp_listing_type_split_map_features', $listing_type_slug, $listing_short_counter, $args_count);
                    ?>
                </div>
                <div class="split-search-btn-holder">
                    <button type="button" class="save-search-field" name="search_title" onclick="javascript:wp_dp_advanced_search_field('<?php echo wp_dp_allow_special_char($listing_short_counter); ?>');"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_filter_show_results'); ?></button>
                </div>
                <?php
                $adv_filter_toggle = isset($_REQUEST['adv_filter_toggle']) ? $_REQUEST['adv_filter_toggle'] : 'false';


                $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                        array(
                            'simple' => true,
                            'classes' => "adv_filter_toggle",
                            'cust_name' => 'adv_filter_toggle',
                            'std' => $adv_filter_toggle,
                        )
                );
                ?> 
            </div>

    <?php }
    ?>
    </div>
        <?php
    }
    