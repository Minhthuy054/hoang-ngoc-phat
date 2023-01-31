<?php
/**
 * Listing search box
 * default variable which is getting from ajax request or shotcode
 * $listing_short_counter, $listing_arg
 */
global $wp_dp_plugin_options, $wp_dp_form_fields_frontend, $wp_dp_post_listing_types, $wp_dp_shortcode_listings_frontend, $wp_dp_search_fields;

$listingsearch_title_switch = isset( $atts['listingsearch_title_field_switch'] ) ? $atts['listingsearch_title_field_switch'] : '';
$listingsearch_listing_type_switch = isset( $atts['listingsearch_listing_type_field_switch'] ) ? $atts['listingsearch_listing_type_field_switch'] : '';
$listingsearch_location_switch = isset( $atts['listingsearch_location_field_switch'] ) ? $atts['listingsearch_location_field_switch'] : '';
$listingsearch_categories_switch = isset( $atts['listingsearch_categories_field_switch'] ) ? $atts['listingsearch_categories_field_switch'] : '';
$listingsearch_price_switch = isset( $atts['listingsearch_price_field_switch'] ) ? $atts['listingsearch_price_field_switch'] : '';
$listingsearch_advance_filter_switch = isset( $atts['listingsearch_advance_filter_switch'] ) ? $atts['listingsearch_advance_filter_switch'] : '';
$listing_types_array = array();
wp_enqueue_script( 'bootstrap-datepicker' );
wp_enqueue_style( 'datetimepicker' );
wp_enqueue_style( 'datepicker' );
wp_enqueue_script( 'datetimepicker' );
$search_title = isset( $_REQUEST['search_title'] ) ? $_REQUEST['search_title'] : '';
$listing_typ = isset( $_REQUEST['listing_type'] ) ? $_REQUEST['listing_type'] : '';

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
			'std' => absint( $listing_short_counter ),
		)
);
?> 
<div style="display:none" id='listing_arg<?php echo absint( $listing_short_counter ); ?>'>
	<?php
	echo json_encode( $listing_arg );
	?>
</div>
 <?php do_action('wp_dp_search_header','text');?>

