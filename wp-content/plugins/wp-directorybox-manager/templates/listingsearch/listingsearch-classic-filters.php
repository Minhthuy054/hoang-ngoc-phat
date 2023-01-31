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
$search_title = isset($_REQUEST['search_title']) ? $_REQUEST['search_title'] : '';
$listing_type_slug = '';
$listing_search_fieled_class = '';
if ( ($listingsearch_title_switch != 'yes') || ($listingsearch_location_switch != 'yes') ) {
    $listing_search_fieled_class = ' one-field-hidden';
}
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
        if ( $listingsearch_location_switch == 'yes' ) {
            ?>
            <div class="field-holder search-input">
                <?php
                $wp_dp_select_display = 1;
                wp_dp_get_custom_locations_listing_filter('<div id="wp-dp-classic-location-select-holder" class="search-country" style="display:' . wp_dp_allow_special_char($wp_dp_select_display) . '"><div class="select-holder">', '</div></div>', false, $listing_short_counter);
                ?>
            </div>
            <?php
        }
        if ( $listingsearch_listing_type_switch == 'yes' ) {
            $number_option_flag = 1;
            ?>
        
        
            <div id="listing_type_select_fields_<?php echo wp_dp_allow_special_char($listing_short_counter); ?>" class="listing-type-cate-fields field-holder select-dropdown has-icon">
                <label>
                    <?php
                    $wp_dp_post_listing_types = new Wp_dp_Post_Listing_Types();
                    $listing_types_array = $wp_dp_post_listing_types->wp_dp_types_array_callback('NULL');

                    if ( is_array($listing_types_array) && ! empty($listing_types_array) ) {
                        foreach ( $listing_types_array as $key => $value ) {
                            $listing_type_slug = $key;
                            break;
                        }
                    }
                    foreach ( $listing_types_array as $key => $value ) {
                        $types_array[$key]  = $value;
                    }
                    $wp_dp_opt_array = array(
                        'std' => $listing_type_slug,
                        'cust_id' => 'search_form_listing_type' . $number_option_flag,
                        'cust_name' => 'listing_type',
                        'classes' => 'chosen-select',
                        'options' => $types_array,
                        'extra_atr' => ' onchange="wp_dp_listing_type_search_fields(this,\'' . $listing_short_counter . '\',\'' . $listingsearch_price_switch . '\'); wp_dp_listing_type_cate_fields(this,\'' . $listing_short_counter . '\',\'' . $listingsearch_categories_switch . '\'); "',
                    );
					if( count($types_array) <= 6 ){
						$wp_dp_opt_array['classes'] = 'chosen-select-no-single';
					}
                    $wp_dp_form_fields_frontend->wp_dp_form_select_render($wp_dp_opt_array);
                    ?>
                </label>
            </div>
            <?php
        }
        $listing_cats_array = $wp_dp_search_fields->wp_dp_listing_type_categories_options($listing_type_slug);
        if ( $listingsearch_categories_switch == 'yes' && ! empty($listing_cats_array) ) {
            ?>
            <div id="listing_type_cate_fields_<?php echo wp_dp_allow_special_char($listing_short_counter); ?>" class="listing-category-fields field-holder select-dropdown has-icon">
                <label>
                    <i class="icon-home"></i>
                    <?php
                    $wp_dp_opt_array = array(
                        'std' => '',
                        'id' => 'listing_category',
                        'classes' => 'chosen-select',
                        'cust_name' => 'listing_category',
                        'options' => $listing_cats_array,
                    );
					if( count($listing_cats_array) <= 6 ){
						$wp_dp_opt_array['classes'] = 'chosen-select-no-single';
					}
                    $wp_dp_form_fields_frontend->wp_dp_form_select_render($wp_dp_opt_array);
                    ?>
                </label>
            </div>
            <?php
        }
        if ( $listingsearch_advance_filter_switch == 'yes' ) {
            ?>
            <div class="field-holder advanced-btn">
                <a href="javascript:void(0);" onclick="wp_dp_advanced_search_field('<?php echo wp_dp_allow_special_char($listing_short_counter); ?>');"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_search_flter_advanced'); ?></a>
            </div>
        <?php } ?>
        <div class="field-holder search-btn">
            <div class="search-btn-loader-<?php echo wp_dp_allow_special_char($listing_short_counter); ?> input-button-loader">
                <button type="submit" class="bgcolor" value="Search"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_search_flter_saerch'); ?> <i class="icon-arrow-right3"></i></button>
            </div>
        </div>
    </div>
    <?php
    if ( $listing_type_slug != '' && $listingsearch_advance_filter_switch == 'yes' ) {
        if ( $listingsearch_categories_switch == 'yes' ) {
            ?>
            <div id="listing_type_fields_<?php echo wp_dp_allow_special_char($listing_short_counter); ?>" class="search-advanced-fields" style="display:none;">
                <?php
				$wp_dp_search_fields->wp_dp_listing_type_price_field_callback( $listingsearch_price_switch,  $listing_type_slug);
                do_action('wp_dp_listing_type_fields', $listing_type_slug);
                do_action('wp_dp_listing_type_features', $listing_type_slug, $listing_short_counter);
                $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                        array(
                            'simple' => true,
                            'cust_id' => 'advanced_search',
                            'cust_name' => 'advanced_search',
                            'std' => 'true',
                            'classes' => '',
                        )
                );
                ?>
            </div>
        <?php } ?>
    <?php } ?>
</form>