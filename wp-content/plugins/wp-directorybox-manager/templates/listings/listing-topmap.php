<?php
/**
 * Listing Map View
 *
 */
global $wp_dp_plugin_options, $wp_dp_shortcode_listings_frontend;

?>
<!--Element Section Start-->
<!--Wp-dp Element Start-->
<?php
$rand_numb = isset($listing_map_counter) ? $listing_map_counter : '';

$flag = 1;
$map_position = isset($atts['listing_map_position']) ? $atts['listing_map_position'] : '';
if (false === ( $listing_view = wp_dp_get_transient_obj('wp_dp_listing_view' . $listing_short_counter) )) {
    $listing_view = isset($atts['listing_view']) ? $atts['listing_view'] : '';
}
$map_elem_height = isset($atts['listing_map_height']) && $atts['listing_map_height'] > 0 ? absint($atts['listing_map_height']) : '400';
if ($map_position == 'full') {
    $map_height = $map_elem_height . 'px';
} else {
    $map_height = '100%';
}

$map_display = ' style="display: none;"';
if ($listing_view == 'map') {
    $map_display = ' style="display: block;"';
}
?>
<div class="dev-listing-map-holder"<?php echo esc_html($map_display) ?>>
    <div class="detail-map col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="wp-dp-listing-map">
            <?php
            $wp_dp_shortcode_listings_frontend->listing_layout_switcher_fields($atts, $listing_short_counter, 'map', true);
            $del_btn_class = ' is-disabled';
            $draw_btn_class = '';

            if (isset($_REQUEST['location']) && $_REQUEST['location'] != '') {
                $get_loc_term = get_term_by('slug', $_REQUEST['location'], 'wp_dp_locations');
                if (isset($get_loc_term->term_id)) {
                    $location_coordinates = get_term_meta($get_loc_term->term_id, 'location_coordinates', true);
                }
            }
            if (isset($_REQUEST['loc_polygon']) && $_REQUEST['loc_polygon'] != '') {
                $loc_poly_cords = wp_dp_decode_url_string($_REQUEST['loc_polygon']);
                $loc_poly_cords = stripslashes($loc_poly_cords);
            }

            $wp_dp_map_zoom = isset($wp_dp_plugin_options['wp_dp_map_zoom_level']) && $wp_dp_plugin_options['wp_dp_map_zoom_level'] != '' ? $wp_dp_plugin_options['wp_dp_map_zoom_level'] : '9';
            $wp_dp_map_style = isset($wp_dp_plugin_options['wp_dp_def_map_style']) && $wp_dp_plugin_options['wp_dp_def_map_style'] != '' ? $wp_dp_plugin_options['wp_dp_def_map_style'] : '';
            $map_custom_style = isset($wp_dp_plugin_options['wp_dp_map_custom_style']) && $wp_dp_plugin_options['wp_dp_map_custom_style'] != '' ? $wp_dp_plugin_options['wp_dp_map_custom_style'] : '';
            $wp_dp_map_lat = isset($wp_dp_plugin_options['wp_dp_post_loc_latitude']) && $wp_dp_plugin_options['wp_dp_post_loc_latitude'] != '' ? $wp_dp_plugin_options['wp_dp_post_loc_latitude'] : '51.5';
            $wp_dp_map_long = isset($wp_dp_plugin_options['wp_dp_post_loc_longitude']) && $wp_dp_plugin_options['wp_dp_post_loc_longitude'] != '' ? $wp_dp_plugin_options['wp_dp_post_loc_longitude'] : '-0.2';
            $wp_dp_map_marker_icon = isset($wp_dp_plugin_options['wp_dp_map_marker_icon']) && $wp_dp_plugin_options['wp_dp_map_marker_icon'] != '' ? $wp_dp_plugin_options['wp_dp_map_marker_icon'] : wp_dp::plugin_url() . '/assets/frontend/images/map-marker.png';
            $wp_dp_map_cluster_icon = isset($wp_dp_plugin_options['wp_dp_map_cluster_icon']) && $wp_dp_plugin_options['wp_dp_map_cluster_icon'] != '' ? $wp_dp_plugin_options['wp_dp_map_cluster_icon'] : wp_dp::plugin_url() . '/assets/frontend/images/map-cluster.png';

			$map_custom_style = str_replace('&quot;', '"', $map_custom_style);
			
            if (isset($location_coordinates) && !empty($location_coordinates)) {
                $location_coordinates_arr = json_decode($location_coordinates, true);

                if (isset($location_coordinates_arr[0]['lat']) && isset($location_coordinates_arr[0]['lng'])) {
                    $wp_dp_map_lat = $location_coordinates_arr[0]['lat'];
                    $wp_dp_map_long = $location_coordinates_arr[0]['lng'];
                }
            }

            if (isset($loc_poly_cords) && !empty($loc_poly_cords)) {
                $loc_poly_cords_arr = json_decode($loc_poly_cords, true);

                $loc_poly_cords_bounds = isset($loc_poly_cords_arr['cords']) ? $loc_poly_cords_arr['cords'] : '';

                $loc_poly_cords_bounds_arr = json_decode($loc_poly_cords_bounds, true);
                if (isset($loc_poly_cords_bounds_arr[0]['lat']) && isset($loc_poly_cords_bounds_arr[0]['lng'])) {
                    $wp_dp_map_lat = $loc_poly_cords_bounds_arr[0]['lat'];
                    $wp_dp_map_long = $loc_poly_cords_bounds_arr[0]['lng'];
                }
            }

            $map_zoom = $wp_dp_map_zoom;
            $map_latitude = $wp_dp_map_lat;
            $map_longitude = $wp_dp_map_long;

            //

            $map_params = array(
                'map_id' => $rand_numb,
                'map_zoom' => $map_zoom,
                'latitude' => $map_latitude,
                'longitude' => $map_longitude,
                'map_style' => $wp_dp_map_style,
                'map_custom_style' => $map_custom_style,
                'marker_icon' => $wp_dp_map_marker_icon,
                'cluster_icon' => $wp_dp_map_cluster_icon,
            );

            $map_init_params = array(
                'map_id' => $rand_numb,
                'map_zoom' => $map_zoom,
                'latitude' => $map_latitude,
                'longitude' => $map_longitude,
                'map_style' => $wp_dp_map_style,
                'map_custom_style' => $map_custom_style,
                'marker_icon' => $wp_dp_map_marker_icon,
            );

            if (isset($location_coordinates) && !empty($location_coordinates)) {
                $map_params['location_cords'] = $location_coordinates;
                $del_btn_class = '';
                $draw_btn_class = ' is-disabled';
            }
            if (isset($loc_poly_cords_bounds) && !empty($loc_poly_cords_bounds)) {
                $map_params['location_cords'] = $loc_poly_cords_bounds;
                $del_btn_class = '';
                $draw_btn_class = ' is-disabled';
            }

            $map_json = json_encode($map_params);

            $map_init_json = json_encode($map_init_params);
            ?>
            <ul class="map-actions">
                <li><a data-toggle="tooltip" title="<?php echo wp_dp_plugin_text_srt( 'wp_dp_map_draw_on_map' ) ?>" id="draw-map-<?php echo absint($rand_numb) ?>" class="act-btn<?php echo esc_html($draw_btn_class) ?>"><img src="<?php echo wp_dp::plugin_url() ?>assets/frontend/images/draw_on.svg" alt=""></a></li>
                <li><a data-toggle="tooltip" title="<?php echo wp_dp_plugin_text_srt( 'wp_dp_map_cancel_draw' ) ?>" id="cancel-draw-map-<?php echo absint($rand_numb) ?>" class="act-btn is-disabled"><img src="<?php echo wp_dp::plugin_url() ?>assets/frontend/images/hand-move.svg" alt=""></a></li>
                <li><a data-toggle="tooltip" title="<?php echo wp_dp_plugin_text_srt( 'wp_dp_map_delete_area' ) ?>" id="delete-button-<?php echo absint($rand_numb) ?>" class="act-btn<?php echo esc_html($del_btn_class) ?>"><img src="<?php echo wp_dp::plugin_url() ?>assets/frontend/images/delete_draw.svg" alt=""></a></li>
                <li><a data-toggle="tooltip" title="<?php echo wp_dp_plugin_text_srt( 'wp_dp_map_lock' ) ?>" id="map-lock-<?php echo absint($rand_numb) ?>" class="map-unloked"><img src="<?php echo wp_dp::plugin_url() ?>assets/frontend/images/lock.svg" alt=""></a></li>
            </ul>
            <div id="listing-records-<?php echo absint($rand_numb) ?>" class="listing-records-sec" style="display: none;">
                <p><span id="total-records-<?php echo absint($rand_numb) ?>">0</span>&nbsp;<?php echo wp_dp_plugin_text_srt( 'wp_dp_map_records' ) ?>,&nbsp;<?php echo wp_dp_plugin_text_srt( 'wp_dp_map_showing' ) ?>&nbsp;<span id="showing-records-<?php echo absint($rand_numb) ?>">0</span>&nbsp;<?php echo wp_dp_plugin_text_srt( 'wp_dp_map_results' ) ?></p>
            </div>
            <div id="map-loader-<?php echo absint($rand_numb) ?>" class="map-loader"><div class="loader-holder"><img src="<?php echo wp_dp::plugin_url() ?>assets/frontend/images/ajax-loader.gif" alt=""></div></div>
            <div id="wp-dp-listing-map-<?php echo absint($rand_numb) ?>" style="height: <?php echo esc_html($map_height) ?>;"></div>
            <script type="text/javascript">
                var dataobj = jQuery.parseJSON('<?php echo addslashes($map_json) ?>');
                var dataobj_init = jQuery.parseJSON('<?php echo addslashes($map_init_json) ?>');
                jQuery(window).load(function () {
                    wp_dp_listing_map_init(dataobj_init);
                    jQuery('#map-loader-<?php echo absint($rand_numb) ?>').html('');
                });
                function wp_dp_listing_map_<?php echo absint($rand_numb) ?>(listings_obj) {
                    //console.log(listings_obj);
                    wp_dp_listing_map(dataobj, listings_obj);
                }
            </script>
            <!--            </form>-->
        </div>
    </div>
</div>

<!--Wp-dp Element End-->