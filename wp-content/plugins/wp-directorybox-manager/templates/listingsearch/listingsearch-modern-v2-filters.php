<?php
/**
 * Listing search box
 * default variable which is getting from ajax request or shotcode
 * $listing_short_counter, $listing_arg
 */
global $wp_dp_plugin_options, $wp_dp_form_fields_frontend, $wp_dp_post_listing_types, $wp_dp_shortcode_listings_frontend, $wp_dp_search_fields;

$listingsearch_title_switch = isset($atts['listingsearch_title_field_switch']) ? $atts['listingsearch_title_field_switch'] : '';
$wp_dp_search_label_color = isset($atts['wp_dp_search_label_color']) ? $atts['wp_dp_search_label_color'] : '';
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

$label_style_colr = '';
if ( isset($wp_dp_search_label_color) && $wp_dp_search_label_color != '' ) {
    $label_style_colr = 'style="color:' . $wp_dp_search_label_color . ' !important"';
}
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
                    <i class="icon-search5"></i>
                    <?php
                    $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                            array(
                                'cust_name' => 'search_title',
                                'classes' => 'input-field',
                                'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_listing_search_view_enter_kywrd') . '"',
                            )
                    );
                    ?> 
                    <span class="field-label"><?php echo wp_dp_allow_special_char(wp_dp_plugin_text_srt('wp_dp_search_keyword_search_field_label')); ?></span>
                </label>

            </div>
            <?php
        }
            
        $listing_type_slug = 'all'; // for load all categories
        $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
                array(
                    'simple' => true,
                    'cust_id' => '',
                    'cust_name' => 'listing_type',
                    'classes' => "",
                    'std' => 'all',
                )
        );
            
        $listing_cats_array = $wp_dp_search_fields->wp_dp_listing_type_categories_options($listing_type_slug);
        if ( $listingsearch_categories_switch == 'yes' && ! empty($listing_cats_array) ) {
            ?>
            <div id="listing_type_cate_fields_<?php echo wp_dp_allow_special_char($listing_short_counter); ?>" class="listing-category-fields field-holder select-dropdown">
                <label>
                    <?php
                    $wp_dp_opt_array = array(
                        'std' => '',
                        'id' => 'listing_category',
                        'classes' => 'chosen-select',
                        'cust_name' => 'listing_category',
                        'options' => $listing_cats_array,
                    );
                    if ( count($listing_cats_array) <= 6 ) {
                        $wp_dp_opt_array['classes'] = 'chosen-select-no-single';
                    }
                    $wp_dp_form_fields_frontend->wp_dp_form_select_render($wp_dp_opt_array);
                    ?>
                </label>
                <span class="field-label"><?php echo wp_dp_allow_special_char(wp_dp_plugin_text_srt('wp_dp_search_category_search_field_label')); ?></span>
            </div>
            <?php
        }
        if ( $listingsearch_location_switch == 'yes' ) {
            $wp_dp_select_display = 1;
            wp_dp_get_custom_locations_listing_filter('', '', false, $listing_short_counter, 'modern-v2');
        }
        ?>
        <div class="field-holder search-btn">
            <strong class="search-title"></strong>
            <div class="search-btn-loader-<?php echo wp_dp_allow_special_char($listing_short_counter); ?> input-button-loader">
                <button type="submit" class="bgcolor"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_search_flter_saerch'); ?></button>
                <span class="field-label"><?php echo wp_dp_allow_special_char(wp_dp_plugin_text_srt('wp_dp_search_adv_search_field_label')); ?></span>
            </div>
        </div>
    </div>
</form>