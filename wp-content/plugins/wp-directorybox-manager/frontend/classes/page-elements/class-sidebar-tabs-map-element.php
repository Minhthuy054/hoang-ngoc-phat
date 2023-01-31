<?php
/**
 * File Type: Listing Sidebar Tabs Map Page Element
 */
if ( ! class_exists('wp_dp_sidebar_tabs_map_element') ) {

    class wp_dp_sidebar_tabs_map_element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_dp_sidebar_tabs_map_html', array( $this, 'wp_dp_sidebar_tabs_map_html_callback' ), 11, 2);
        }

        public function wp_dp_sidebar_tabs_map_html_callback($listing_id = '', $view = '') {
            global $post, $wp_dp_plugin_options;

            $sidebar_map = wp_dp_element_hide_show($listing_id, 'sidebar_map');
            if ( $sidebar_map != 'on' ) {
                return;
            }

            if ( $listing_id == '' ) {
                $listing_id = $post->ID;
            }
            if ( $listing_id != '' ) {
                $default_zoom_level = ( isset($wp_dp_plugin_options['wp_dp_map_zoom_level']) && $wp_dp_plugin_options['wp_dp_map_zoom_level'] != '' ) ? $wp_dp_plugin_options['wp_dp_map_zoom_level'] : 10;
                $wp_dp_post_loc_latitude = get_post_meta($listing_id, 'wp_dp_post_loc_latitude_listing', true);
                $wp_dp_post_loc_longitude = get_post_meta($listing_id, 'wp_dp_post_loc_longitude_listing', true);
                $wp_dp_post_loc_address_listing = get_post_meta($listing_id, 'wp_dp_post_loc_address_listing', true);
                $wp_dp_listing_zoom = get_post_meta($listing_id, 'wp_dp_post_loc_zoom_listing', true);
                if ( $wp_dp_listing_zoom == '' || $wp_dp_listing_zoom == 0 ) {
                    $wp_dp_listing_zoom = $default_zoom_level;
                }

                $listing_type_id = '';
                $listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                if ( $listing_type != '' ) {
                    $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type", 'post_status' => 'publish' ));
                    $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
                }
                $wp_dp_near_by_options = get_post_meta($listing_type_id, 'wp_dp_near_by_options_element', true);
                $map_marker_icon = get_post_meta($listing_type_id, 'wp_dp_listing_type_marker_image', true);

                $map_marker_icon = wp_get_attachment_url($map_marker_icon);
                ?>
                <div class="widget widget-map-sec">
                    <?php
                    $map_dynmaic_no = rand(1000000, 99999999);
                    $map_atts = array(
                        'map_rand_num' => $map_dynmaic_no,
                        'map_height' => '380',
                        'map_lat' => $wp_dp_post_loc_latitude,
                        'map_lon' => $wp_dp_post_loc_longitude,
                        'map_zoom' => $wp_dp_listing_zoom,
                        'map_type' => '',
                        'map_info' => $wp_dp_post_loc_address_listing, //$wp_dp_post_comp_address,
                        'map_info_width' => '200',
                        'map_info_height' => '350',
                        'map_marker_icon' => $map_marker_icon,
                        'map_show_marker' => 'false',
                        'map_controls' => 'true',
                        'map_draggable' => 'true',
                        'map_scrollwheel' => 'false',
                        'map_border' => '',
                        'map_border_color' => '',
                        'wp_dp_map_style' => '',
                        'wp_dp_map_class' => '',
                        'map_det_view' => '',
                    );
                    if ( function_exists('wp_dp_map_content') ) {
                        $this->wp_dp_sidebar_map_content($map_atts);
                    }
                    ?>
                </div>

                <?php
            }
        }

        public function wp_dp_sidebar_map_content($atts) {

            global $wp_dp_plugin_options, $wp_dp_form_fields_frontend;
            $distance_symbol = isset($wp_dp_plugin_options['wp_dp_distance_measure_by']) ? $wp_dp_plugin_options['wp_dp_distance_measure_by'] : 'km';
            $defaults = array(
                'map_rand_num' => '',
                'map_height' => '',
                'map_lat' => '51.507351',
                'map_lon' => '-0.127758',
                'map_zoom' => '10',
                'map_type' => '',
                'map_info' => '',
                'map_info_width' => '200',
                'map_info_height' => '200',
                'map_marker_icon' => '',
                'map_show_marker' => 'true',
                'map_controls' => 'true',
                'map_draggable' => 'true',
                'map_scrollwheel' => 'false',
                'map_border' => '',
                'map_border_color' => '',
                'wp_dp_map_style' => '',
                'wp_dp_map_class' => '',
                'map_det_view' => '',
            );
            extract(shortcode_atts($defaults, $atts));
            if (!wp_script_is( 'wp-dp-google-map-api', 'enqueued' )) {
                wp_enqueue_script('wp-dp-google-map-api');
            }
            if ( $map_info_width == '' || $map_info_height == '' ) {
                $map_info_width = '300';
                $map_info_height = '150';
            }
            if ( isset($map_height) && $map_height == '' ) {
                $map_height = '500';
            }

            $map_dynmaic_no = rand(1165480, 99999999);

            if ( $map_rand_num != '' ) {
                $map_dynmaic_no = $map_rand_num;
            }

            $border = '';
            if ( isset($map_border) && $map_border == 'yes' && $map_border_color != '' ) {
                $border = 'border:1px solid ' . $map_border_color . '; ';
            }

            $map_type = isset($map_type) ? $map_type : '';
            $radius_circle = isset($wp_dp_plugin_options['wp_dp_default_radius_circle']) ? $wp_dp_plugin_options['wp_dp_default_radius_circle'] : '10';
            $radius_circle = ($radius_circle * 1000);

            ob_start();

            $map_col_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
            echo '<div class="row">';
            echo '<div class="' . $map_col_class . '">';


            if ( $map_marker_icon == '' ) {
                $map_marker_icon = isset($wp_dp_plugin_options['wp_dp_map_marker_icon']) ? $wp_dp_plugin_options['wp_dp_map_marker_icon'] : '';
                $map_marker_icon = wp_get_attachment_url($map_marker_icon);
            }

            $html = ob_get_clean();


            $html .= '<div ' . $wp_dp_map_class . ' style="animation-duration:">';

            $html .= '<div class="clear"></div>';
            $html .= '<div class="cs-map-section" style="' . $border . ';">';

            $html .= '
			<div class="sidebar-map-tabs-area">
				<div>
					<ul class="map-tabs">
						<li class="map-view" onclick="javascript:wp_dp_toggle_map_view(\'mapview\');">' . wp_dp_plugin_text_srt('wp_dp_tabs_map_map_view') . '</li>
						<li class="street-view" onclick="javascript:wp_dp_toggle_map_view(\'streetview\');">' . wp_dp_plugin_text_srt('wp_dp_tabs_map_street') . '</li>
						<li class="directions-view" onclick="javascript:wp_dp_toggle_map_view(\'directionview\');">' . wp_dp_plugin_text_srt('wp_dp_tabs_map_directions') . '</li>
					</ul>
				</div>
			</div>';

            $html .= '<div class="cs-map">
			<span id="sidebar-click-map-view-changed" style="position:absolute;">&nbsp;</span>';
            $html .= '<div class="cs-map-content">';

            $html .= '<div class="mapcode iframe mapsection gmapwrapp sidebar-tabs-map" data-lat="' . $map_lat . '"  data-lng="' . $map_lon . '" data-id="' . $map_dynmaic_no . '" id="map_canvas' . $map_dynmaic_no . '" style="height:' . $map_height . 'px;"> </div>';
			$html .= '
			<div class="wp-dp-dir-srch-box" id="wp-dp-dir-srch-box" style="display: none;">
				<input type="text" placeholder="' . wp_dp_plugin_text_srt('wp_dp_tabs_map_map_enter_location') . '" id="wp_dp_end_direction" />
				<i class="icon-arrow_drop_down"></i>
				<ul id="wp_dp_direction_mode" class="wp_dp_direction_mode">
					<li class="active" data-value="DRIVING">' . wp_dp_plugin_text_srt('wp_dp_tabs_map_map_driving') . '</li>
					<li data-value="WALKING">' . wp_dp_plugin_text_srt('wp_dp_tabs_map_map_walking') . '</li>
					<li data-value="BICYCLING">' . wp_dp_plugin_text_srt('wp_dp_tabs_map_map_bicycling') . '</li>
					<li data-value="TRANSIT">' . wp_dp_plugin_text_srt('wp_dp_tabs_map_map_transit') . '</li>
				</ul>
				<input type="hidden" id="wp_dp_chng_dir_mode" value="DRIVING" />
				<label class="search-button">
					<input type="button" id="wp_dp_search_direction" class="wp_dp_search_direction" value="' . wp_dp_plugin_text_srt('wp_dp_tabs_map_map_get_directions') . '" />
				</label>
				<div id="wp-dp-directions-panel"></div>
			</div>';

            $html .= '</div>';
            $html .= '</div>';

            
            $html .= "<script type='text/javascript'>";

            $html .= "
			var tabsPanorama = '';
			function mapTabsZoomControlBtns(map, icon_plus, icon_minus) {
				'use strict';
				var controlDiv = document.createElement('div');
				controlDiv.className = 'wp-dp-map-zoom-controls';
				controlDiv.index = 1;
				//controlDiv.style.margin = '6px';
				var controlPlus = document.createElement('a');
				controlPlus.className = 'control-zoom-in';
				controlPlus.innerHTML = '<i class=\"'+icon_plus+'\"></i>';
				controlDiv.appendChild(controlPlus);
				var controlMinus = document.createElement('a');
				controlMinus.className = 'control-zoom-out';
				controlMinus.innerHTML = '<i class=\"'+icon_minus+'\"></i>';
				controlDiv.appendChild(controlMinus);

				google.maps.event.addDomListener(controlPlus, 'click', function () {
					var curZoom = map.getZoom();
					if (curZoom < 20) {
						var newZoom = curZoom+1;
						map.setZoom(newZoom);
						var mapZoomLvl = map.getZoom();
					}
				});
				google.maps.event.addDomListener(controlMinus, 'click', function () {
					var curZoom = map.getZoom();
					if (curZoom > 0) {
						var newZoom = curZoom-1;
						map.setZoom(newZoom);
						var mapZoomLvl = map.getZoom();
					}
				});
				return controlDiv;
			}";

            $html .= "jQuery(document).ready(function() {
			var center = new google.maps.LatLng(" . $map_lat . ", " . $map_lon . ");
			
			function sidebar_initialize() {
				var myLatlng = new google.maps.LatLng(" . $map_lat . ", " . $map_lon . ");

				var mapOptions = {
					zoom: " . $map_zoom . ",
					scrollwheel: " . $map_scrollwheel . ",
					draggable: " . $map_draggable . ",
					streetViewControl: false,
					center: center,
					disableDefaultUI: true,
					zoomControl: false,
					mapTypeId: 'terrain',
					mapTypeControl: false,
				};";
			
            $html .= "
			var directionsDisplay;
			var directionsService = new google.maps.DirectionsService();
			directionsDisplay = new google.maps.DirectionsRenderer();";
			
			$html .= "

			function wp_dp_calc_route() {
				var myLatlng = new google.maps.LatLng(" . $map_lat . ", " . $map_lon . ");
				var start = myLatlng;
				var end = document.getElementById('wp_dp_end_direction').value;
				var mode = document.getElementById('wp_dp_chng_dir_mode').value;
				var request = {
					origin:start,
					destination:end,
					travelMode: google.maps.TravelMode[mode]
				};
				directionsService.route(request, function(response, status) { 
					if (status == google.maps.DirectionsStatus.OK) {
						directionsDisplay.setDirections(response);
					} else {
						alert('"."In your given region direction type is not available,"."');
					}
				});
			}

			google.maps.event.addDomListener(document.getElementById('wp_dp_search_direction'), 'click', function() {
				wp_dp_calc_route();
			});";

            $html .= "var tabsMap = new google.maps.Map(document.getElementById('map_canvas" . $map_dynmaic_no . "'), mapOptions);";
			
			$html .= "
			directionsDisplay.setMap(tabsMap);
			directionsDisplay.setPanel(document.getElementById('wp-dp-directions-panel'));";
            
            $html .= "tabsMap.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(mapTabsZoomControlBtns(tabsMap, 'icon-plus', 'icon-minus'));";

            $wp_dp_map_style = isset($wp_dp_plugin_options['wp_dp_def_map_style']) ? $wp_dp_plugin_options['wp_dp_def_map_style'] : '';
            $map_custom_style = isset($wp_dp_plugin_options['wp_dp_map_custom_style']) ? $wp_dp_plugin_options['wp_dp_map_custom_style'] : '';

            if ( $map_custom_style != '' ) {
                $map_custom_style = str_replace('&quot;', '"', $map_custom_style);
                $html .= "var style = " . $map_custom_style . ";
						if (style != '') {
							var styledMap = new google.maps.StyledMapType(style,
									{name: 'Styled Map'});
							tabsMap.mapTypes.set('map_style', styledMap);
							tabsMap.setMapTypeId('map_style');
						}";
            } else {
                $html .= "var style = '" . $wp_dp_map_style . "';
						if (style != '') {
							var styles = wp_dp_map_select_style(style);
							if (styles != '') {
								var styledMap = new google.maps.StyledMapType(styles, {name: 'Styled Map'});
								tabsMap.mapTypes.set('map_style', styledMap);
								tabsMap.setMapTypeId('map_style');
							}
						}";
            }

            $html .= "
			var infowindow = new google.maps.InfoWindow({ 
				content: '" . $map_info . "',
				maxWidth: " . $map_info_width . ",
				maxHeight: " . $map_info_height . ",
			});
			var marker = new google.maps.Marker({
				position: myLatlng,
				map: tabsMap,
				animation: google.maps.Animation.DROP,
				title: '',
				icon: '" . $map_marker_icon . "',
				shadow: ''
			});
			if (infowindow.content != ''){
			  infowindow.open(tabsMap, marker);
			   tabsMap.panBy(1,-60);
			   google.maps.event.addListener(marker, 'click', function(event) {
				infowindow.open(tabsMap, marker);
			   });
			};";

            $html .= "
            tabsPanorama = tabsMap.getStreetView();
            tabsPanorama.setPosition(center);
            tabsPanorama.setPov(({
              heading: 265,
              pitch: 0
            }));";
			
            $html .= "} ";

            // Setting zoom level
            //

            $html .= "
			google.maps.event.addDomListener(window, 'load', sidebar_initialize);             
            });
			function wp_dp_toggle_map_view(btn) {
				var toggle = tabsPanorama.getVisible();
				if (toggle == false) {
					if(btn == 'streetview'){
						tabsPanorama.setVisible(true);
						document.getElementById('wp-dp-dir-srch-box').style.display = 'none';
					} else if(btn == 'directionview') {
						document.getElementById('wp-dp-dir-srch-box').style.display = 'block';
					} else {
						document.getElementById('wp-dp-dir-srch-box').style.display = 'none';
					}
				} else {
					if(btn == 'mapview'){
						tabsPanorama.setVisible(false);
						document.getElementById('wp-dp-dir-srch-box').style.display = 'none';
					} else if(btn == 'directionview') {
						tabsPanorama.setVisible(false);
						document.getElementById('wp-dp-dir-srch-box').style.display = 'block';
					} else {
						document.getElementById('wp-dp-dir-srch-box').style.display = 'none';
					}
				}
			}
			jQuery('#wp-dp-dir-srch-box').hide();
			jQuery('ul.map-tabs > li').click(function () {
				jQuery('ul.map-tabs > li').removeClass('active');
				jQuery(this).addClass('active');
				jQuery('#wp-dp-dir-srch-box').hide();
				if (jQuery(this).index() == 2) {
					jQuery('#wp-dp-dir-srch-box').show();
				}
			});
			// Single map direction modes
			jQuery('#wp_dp_direction_mode').hide();
			jQuery('#wp-dp-dir-srch-box > i').click(function () {
				jQuery('#wp_dp_direction_mode').slideToggle();
			});
			jQuery('ul#wp_dp_direction_mode > li').click(function () {
				jQuery('ul#wp_dp_direction_mode > li').removeClass('active');
				jQuery(this).addClass('active');
				jQuery('#wp_dp_chng_dir_mode').val(jQuery(this).data('value'));
				jQuery('#wp_dp_direction_mode').slideUp('slow');
			});";
            $html .= "</script>";
            $html .= '</div>';
            $html .= '</div>';
            // col class end
            $html .= '</div>';
            // row end
            $html .= '</div>';

            echo force_balance_tags($html);
        }

    }

    global $wp_dp_sidebar_tabs_map;
    $wp_dp_sidebar_tabs_map = new wp_dp_sidebar_tabs_map_element();
}