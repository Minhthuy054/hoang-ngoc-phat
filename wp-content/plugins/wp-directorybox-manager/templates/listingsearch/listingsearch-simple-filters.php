<?php
/**
 * Listing search box
 * default variable which is getting from ajax request or shotcode
 * $listing_short_counter, $listing_arg
 */
global $wp_dp_plugin_options, $wp_dp_form_fields_frontend, $wp_dp_post_listing_types, $wp_dp_shortcode_listings_frontend, $wp_dp_search_fields;
$listingsearch_title_switch = isset($atts['listingsearch_title_field_switch']) ? $atts['listingsearch_title_field_switch'] : '';
$listingsearch_listing_type_switch = isset($atts['listingsearch_listing_type_field_switch']) ? $atts['listingsearch_listing_type_field_switch'] : '';
$listingsearch_location_switch = isset($atts['listingsearch_location_field_switch']) ? $atts['listingsearch_location_field_switch'] : '';
$listingsearch_categories_switch = isset($atts['listingsearch_categories_field_switch']) ? $atts['listingsearch_categories_field_switch'] : '';
$listingsearch_price_switch = isset($atts['listingsearch_price_field_switch']) ? $atts['listingsearch_price_field_switch'] : '';
$listingsearch_advance_filter_switch = isset($atts['listingsearch_advance_filter_switch']) ? $atts['listingsearch_advance_filter_switch'] : '';
$listing_types_array = array();
wp_enqueue_script('bootstrap-datepicker');
wp_enqueue_style('datetimepicker');
wp_enqueue_style('datepicker');
wp_enqueue_script('datetimepicker');
$listing_type_slug = '';
$listing_search_fieled_class = '';
if ( ($listingsearch_title_switch != 'yes') || ($listingsearch_location_switch != 'yes') ) {
    $listing_search_fieled_class = ' one-field-hidden';
}
$search_title = isset($_REQUEST['search_title']) ? $_REQUEST['search_title'] : '';
$wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
        array(
            'simple' => true,
            'cust_id' => '',
            'cust_name' => '',
            'classes' => "listing-counter",
            'std' => absint($listing_short_counter),
        )
);
?> 
<div style="display:none" id='listing_arg<?php echo absint($listing_short_counter); ?>'>
    <?php
    echo json_encode($listing_arg);
    ?>
</div>
<form method="GET" id="top-search-form-<?php echo wp_dp_allow_special_char($listing_short_counter); ?>" class="search-form-element" action="<?php echo esc_html($wp_dp_search_result_page); ?>" onsubmit="wp_dp_top_search('<?php echo wp_dp_allow_special_char($listing_short_counter); ?>');" data-locationadminurl="<?php echo esc_url(admin_url("admin-ajax.php")); ?>">
    <?php echo wp_dp_wpml_lang_code_field(); ?>
	<div class="search-default-fields <?php echo esc_html($listing_search_fieled_class); ?>">
        <?php if ( $listingsearch_title_switch == 'yes' ) { ?>
            <div class="field-holder search-input">
                <label>
                    <i class="icon-search4"></i>
                    <?php
                    $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                            array(
                                'cust_name' => 'search_title',
                                'classes' => 'input-field',
                                'std' => $search_title,
                                'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_listing_search_flter_wt_looking_for') . '"',
                            )
                    );
                    ?> 
                </label>
            </div>
            <?php
        }
        if ( $listingsearch_listing_type_switch == 'yes' ) {
            ?>
            <div class="field-holder select-dropdown listing-type checkbox"> 
                <?php
                $wp_dp_post_listing_types = new Wp_dp_Post_Listing_Types();
                $listing_types_array = $wp_dp_post_listing_types->wp_dp_types_array_callback('NULL');
                if ( is_array($listing_types_array) && ! empty($listing_types_array) ) {
                    foreach ( $listing_types_array as $key => $value ) {
                        $listing_type_slug = $key;
                        break;
                    }
                }
                ?>
                <ul>
                    <?php
                    $number_option_flag = 1;
                    foreach ( $listing_types_array as $key => $value ) {
                        ?>
                        <li>
                            <?php
                            $checked = '';
                            if ( ( (isset($_REQUEST['listing_type']) && $_REQUEST['listing_type'] != '') && $_REQUEST['listing_type'] == $key ) || $listing_type_slug == $key ) {
                                $checked = 'checked="checked"';
                            }
                            $wp_dp_form_fields_frontend->wp_dp_form_radio_render(
                                    array(
                                        'simple' => true,
                                        'cust_id' => 'search_form_listing_type' . $number_option_flag,
                                        'cust_name' => 'listing_type',
                                        'std' => $key,
                                        'extra_atr' => $checked . ' onchange="wp_dp_listing_type_search_fields(this,\'' . $listing_short_counter . '\',\'' . $listingsearch_price_switch . '\'); wp_dp_listing_type_cate_fields(this,\'' . $listing_short_counter . '\',\'' . $listingsearch_categories_switch . '\'); "',
                                    )
                            );
                            ?>
                            <label for="<?php echo force_balance_tags('search_form_listing_type' . $number_option_flag) ?>"><?php echo force_balance_tags($value); ?></label>
                        </li>
                        <?php
                        $number_option_flag ++;
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
        ?>
        <div class="field-holder search-btn">
            <div class="search-btn-loader-<?php echo wp_dp_allow_special_char($listing_short_counter); ?> input-button-loader">
                <?php
                $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                        array(
                            'cust_name' => '',
                            'classes' => 'bgcolor',
                            'std' => wp_dp_plugin_text_srt('wp_dp_listing_search_flter_saerch'),
                            'cust_type' => "submit",
                        )
                );
                ?>  
            </div>
        </div>
    </div>    
</form>