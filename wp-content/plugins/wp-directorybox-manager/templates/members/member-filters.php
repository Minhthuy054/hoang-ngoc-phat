<?php
/**
 * Listing search box
 * default variable which is getting from ajax request or shotcode
 * $member_short_counter, $listing_arg
 */
global $wp_dp_plugin_options, $wp_dp_form_fields_frontend, $wp_dp_post_listing_types, $wp_dp_shortcode_listings_frontend, $wp_dp_search_fields;



wp_enqueue_script('bootstrap-datepicker');
wp_enqueue_style('datetimepicker');
wp_enqueue_style('datepicker');
wp_enqueue_script('datetimepicker');
$listing_type_slug = '';
$search_title = isset($_REQUEST['search_title']) ? $_REQUEST['search_title'] : '';

$wp_dp_opt_array = array(
    'id' => 'listing_counter',
    'classes' => 'listing-counter',
    'std' => absint($member_short_counter),
);
$wp_dp_form_fields_frontend->wp_dp_form_hidden_render($wp_dp_opt_array);
$sidebar_filter_columns = 'col-lg-3 col-md-3 col-sm-12 col-xs-12';
?>
<aside class="page-sidebar <?php echo esc_html($sidebar_filter_columns); ?>">
    <?php if ( isset($member_left_filter) && $member_left_filter == 'yes' ) { ?>
        <div class="main-search member-search widget">
            <div class="widget-title">
                <h5><?php echo wp_dp_plugin_text_srt('wp_dp_member_find_real_members'); ?></h5> 
            </div>
            <form method="GET" id="top-search-form-<?php echo wp_dp_allow_special_char($member_short_counter); ?>"  onsubmit="wp_dp_top_search('<?php echo wp_dp_allow_special_char($member_short_counter); ?>');">
                <?php echo wp_dp_wpml_lang_code_field(); ?>
                <div role="tabpanel" class="tab-pane" id="home">
                    <div class="search-default-fields">
                        <div class="field-holder search-input title-field">
                            <label><?php echo wp_dp_plugin_text_srt('wp_dp_review_member_name_column'); ?></label>
                                <?php
                                $wp_dp_opt_array = array(
                                    'cust_name' => 'search_title',
                                    'return' => false,
                                    'std' => esc_html($search_title),
                                    'classes' => 'input-field',
                                    'extra_atr' => ' placeholder=" ' . wp_dp_plugin_text_srt('wp_dp_member_search_enter_name') . '"',
                                );
                                $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                ?>
                        </div>
                        <div class="field-holder search-input">
                            <label><?php echo wp_dp_plugin_text_srt('wp_dp_listing_location'); ?></label>
                            <?php
                            $wp_dp_select_display = 1;
                            wp_dp_get_custom_locations_listing_filter('<div id="wp-dp-top-select-holder" class="search-country" style="display:' . wp_dp_allow_special_char($wp_dp_select_display) . '"><div class="select-holder">', '</div></div>', false, $member_short_counter);
                            ?>
                        </div>
                        <div class="field-holder search-btn">
                            <div class="search-btn-loader-<?php echo wp_dp_allow_special_char($member_short_counter); ?> input-button-loader">
                                <?php
                                $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                                        array(
                                            'cust_name' => '',
                                            'classes' => 'bgcolor',
                                            'std' => wp_dp_plugin_text_srt('wp_dp_listing_search') . '',
                                            'cust_type' => "submit",
                                        )
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <?php } ?>

    <?php
    if ( isset($wp_dp_member_sidebar_switch) && $wp_dp_member_sidebar_switch == 'yes' ) {
        if ( is_active_sidebar($wp_dp_member_sidebar) ) {
            if ( ! function_exists('dynamic_sidebar') || ! dynamic_sidebar($wp_dp_member_sidebar) ) :
                echo '';
            endif;
        }
    }
    ?>
</aside>