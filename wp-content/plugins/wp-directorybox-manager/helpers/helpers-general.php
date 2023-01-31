<?php
/**
 * Core Helper Functions of Directory Box Manager
 *
 * @return
 * @package wp-dp-manager
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! function_exists( 'wp_dp_heartbeat_frequency' ) ) {

	function wp_dp_heartbeat_frequency( $settings ) {
		global $heartbeat_frequency;
		$settings['interval'] = 60;
		return $settings;
	}

	add_filter( 'heartbeat_settings', 'wp_dp_heartbeat_frequency' );
}
if ( ! function_exists( 'wp_dp_server_protocol' ) ) {

	/**
	 * Return whether request is on SSL or not. Return protocol.
	 *
	 * @return string
	 */
	function wp_dp_server_protocol() {
		if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) {
			return 'https://';
		}
		return 'http://';
	}

}
if ( ! function_exists( 'wp_dp_get_input' ) ) {

	/**
	 * Return an input variable from $_REQUEST if exists else default.
	 *
	 * @param	string $name name of the variable.
	 * @param string $default default value.
	 * @param string $filter
	 * @return string
	 */
	function wp_dp_get_input( $name, $default = null, $filter = 'cmd' ) {
		if ( isset( $_REQUEST[$name] ) ) {
			return wp_dp_input_clean( $_REQUEST[$name], $filter );
		}
		return $default;
	}

}
if ( ! function_exists( 'wp_dp_get_server' ) ) {

	/**
	 * Return an input variable from $_SERVER if exists else default.
	 *
	 * @param	string $name name of the variable.
	 * @param string $default default value.
	 * @return string
	 */
	function wp_dp_get_server( $name, $default = null ) {
		return isset( $_SERVER[$name] ) ? $_SERVER[$name] : $default;
	}

}

if ( ! function_exists( 'wp_dp_get_all_server' ) ) {

	/**
	 * Return an input variable from $_SERVER
	 *
	 * @return string
	 */
	function wp_dp_get_all_server() {
		return $_SERVER;
	}

}

if ( ! function_exists( 'wp_dp_get_cookie' ) ) {

	/**
	 * Return an input variable from $_COOKIE if exists else default.
	 *
	 * @param	string $name name of the variable.
	 * @param string $default default value.
	 * @return string
	 */
	function wp_dp_get_cookie( $name, $default = null ) {
		return isset( $_COOKIE[$name] ) ? $_COOKIE[$name] : $default;
	}

}

if ( ! function_exists( 'wp_dp_get_all_request' ) ) {

	/**
	 * Return an input variable from $_REQUEST
	 *
	 * @return string
	 */
	function wp_dp_get_all_request() {
		return $_REQUEST;
	}

}

if ( ! function_exists( 'wp_dp_get_all_cookie' ) ) {

	/**
	 * Return an input variable from $_COOKIE
	 *
	 * @return string
	 */
	function wp_dp_get_all_cookie() {
		return $_COOKIE;
	}

}

if ( ! function_exists( 'wp_dp_input_clean' ) ) {

	/**
	 * Clean given string by applying requested filter.
	 *
	 * @param   mixed   $source  Input string/array-of-string to be 'cleaned'
	 * @param   string  $type    Return type for the variable (INT, UINT, FLOAT, BOOLEAN, WORD, ALNUM, CMD, BASE64, STRING, ARRAY, PATH, NONE)
	 *
	 * @return  mixed  'Cleaned' version of input parameter
	 */
	function wp_dp_input_clean( $source, $type = 'string' ) {
		// Handle the type constraint
		switch ( strtoupper( $type ) ) {
			case 'INT':
			case 'INTEGER':
				// Only use the first integer value.
				preg_match( '/-?[0-9]+/', ( string ) $source, $matches );
				$result = @ ( int ) $matches[0];
				break;

			case 'UINT':
				// Only use the first integer value.
				preg_match( '/-?[0-9]+/', ( string ) $source, $matches );
				$result = @ abs( ( int ) $matches[0] );
				break;

			case 'FLOAT':
			case 'DOUBLE':
				// Only use the first floating point value.
				preg_match( '/-?[0-9]+(\.[0-9]+)?/', ( string ) $source, $matches );
				$result = @ ( float ) $matches[0];
				break;

			case 'BOOL':
			case 'BOOLEAN':
				$result = ( bool ) $source;
				break;

			case 'WORD':
				$result = ( string ) preg_replace_callback( '/[^A-Z_]/i', function($m) {
							return '';
						}, $source );
				break;

			case 'ALNUM':
				$result = ( string ) preg_replace_callback( '/[^A-Z0-9]/i', function($m) {
							return '';
						}, $source );
				break;

			case 'CMD':
				$result = ( string ) preg_replace_callback( '/[^A-Z0-9_\.-]/i', function($m) {
							return '';
						}, $source );
				$result = ltrim( $result, '.' );
				break;

			case 'BASE64':
				$result = ( string ) preg_replace_callback( '/[^A-Z0-9\/+=]/i', function($m) {
							return '';
						}, $source );
				break;

			case 'STRING':
				$result = ( string ) esc_html( wp_dp_decode_str( ( string ) $source ) );
				break;

			case 'HTML':
				$result = ( string ) $source;
				break;

			case 'ARRAY':
				$result = ( array ) $source;
				break;

			case 'RAW':
				$result = $source;
				break;

			case 'PATH':
				$pattern = '/^[A-Za-z0-9_-]+[A-Za-z0-9_\.-]*([\\\\\/][A-Za-z0-9_-]+[A-Za-z0-9_\.-]*)*$/';
				preg_match( $pattern, ( string ) $source, $matches );
				$result = @ ( string ) $matches[0];
				break;

			case 'USERNAME':
				$result = ( string ) preg_replace_callback( '/[\x00-\x1F\x7F<>"\'%&]/', function($m) {
							return '';
						}, $source );
				break;

			default:
				// Are we dealing with an array?
				if ( is_array( $source ) ) {
					foreach ( $source as $key => $value ) {
						// filter element for XSS and other 'bad' code etc.
						if ( is_string( $value ) ) {
							$source[$key] = esc_html( wp_dp_decode_str( $value ) );
						}
					}
					$result = $source;
				} else {
					// Or a string?
					if ( is_string( $source ) && ! empty( $source ) ) {
						// filter source for XSS and other 'bad' code etc.
						$result = esc_html( wp_dp_decode_str( $source ) );
					} else {
						// Not an array or string.. return the passed parameter.
						$result = $source;
					}
				}
				break;
		}

		return $result;
	}

}

if ( ! function_exists( 'wp_dp_decode_str' ) ) {

	/**
	 * Try to convert to plaintext
	 *
	 * @param   string  $source  The source string.
	 * @return  string  Plaintext string
	 */
	function wp_dp_decode_str( $source ) {
		static $ttr;

		if ( ! is_array( $ttr ) ) {
			// Entity decode.
			$trans_tbl = get_html_translation_table( HTML_ENTITIES );
			foreach ( $trans_tbl as $k => $v ) {
				$ttr[$v] = utf8_encode( $k );
			}
		}
		$source = strtr( $source, $ttr );
		// Convert decimal.
		$source = preg_replace_callback( '/&#x(\d+);/mi', function($m) {
			return utf8_encode( chr( '0x' . $m[1] ) );
		}, $source ); // Decimal notation.
		// Convert hex.
		$source = preg_replace_callback( '/&#x([a-f0-9]+);/mi', function($m) {
			return utf8_encode( chr( '0x' . $m[1] ) );
		}, $source ); // Hex notation.
		return $source;
	}

}

if ( ! function_exists( 'wp_dp_dbg' ) ) {

	/**
	 * Used for debugging, output given data to browser console.
	 *
	 * @param  mixed  $data		The data to be debugged.
	 * @param  string $label	The label to shown with debugged data.
	 */
	function wp_dp_dbg( $data, $label = '' ) {
		if ( '' === $label ) {
			$key = array_search( __FUNCTION__, array_column( debug_backtrace(), 'wp_dp_dbg' ) );
			$label = 'Debuged from \'' . basename( debug_backtrace()[$key]['file'] ) . '\'';
		}
		$data = var_export( $data, true );
		$data = explode( "\n", $data ); // Plz don't remove double quotes arround newline character.
		$output = '';
		foreach ( $data as $line ) {
			if ( trim( $line ) ) {
				$line = addslashes( $line );
				$output .= 'console.log( " ' . $line . '" );';
			}
		}
		echo '<script>console.log( "' . $label . ': "); ' . $output . ' </script>';
	}

}

if ( ! function_exists( 'wp_dp_shortcode_files' ) ) {

	/**
	 * Include Backend shortcodes pages function 
	 */
	function wp_dp_shortcode_files( $path ) {

		$shortcode_wp_dp = wp_dp::plugin_dir() . 'shortcodes/' . $path . '/';
		$aAdmin = array();
		$aFront = array();
		$aResult = array();
		$file_counter = 0;
		if ( is_dir( $shortcode_wp_dp ) ) {
			if ( $dh = opendir( $shortcode_wp_dp ) ) {
				while ( ($file = readdir( $dh )) !== false ) {
					$aAdmin[] = $file;
					$file_counter ++;
				}

				$aResult['admin'] = $aAdmin;
				closedir( $dh );
			}
		}
		if ( is_array( $aResult ) && count( $aResult ) > 0 ) {
			return $aResult;
		}
	}

}


/**
 * Start dashboard page link if user login
 */
if ( ! function_exists( 'wp_dp_user_dashboard_page_url' ) ) {

	function wp_dp_user_dashboard_page_url( $page = 'url' ) {
		global $wp_dp_plugin_options, $current_user;
		$wp_dp_page_id = '';
		$wp_dp_user_dashboard_page_url = '';
		if ( is_user_logged_in() ) {

			$user_roles = isset( $current_user->roles ) ? $current_user->roles : '';
			if ( ($user_roles != '' && in_array( "wp_dp_member", $user_roles ) ) || in_array( 'administrator', $user_roles ) ) {
				$wp_dp_page_id = isset( $wp_dp_plugin_options['wp_dp_member_dashboard'] ) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : $default_url;
				if ( $page == 'url' ) {
					if ( $wp_dp_page_id != '' ) {
						$wp_dp_user_dashboard_page_url = wp_dp_wpml_lang_page_permalink( $wp_dp_page_id, 'page' );
					}
				} else if ( $page == 'id' ) {
					$wp_dp_user_dashboard_page_url = wp_dp_wpml_lang_page_id( $wp_dp_page_id, 'page' );
				}
			}
		}
		return $wp_dp_user_dashboard_page_url;
	}

}



/*
 * @Shortcode Name: Start function for Map shortcode/element front end view
 * @retrun
 *
 */
if ( ! function_exists( 'wp_dp_map_content' ) ) {

	function wp_dp_map_content( $atts, $ready_state = true ) {

		global $wp_dp_plugin_options, $wp_dp_form_fields_frontend;
		$wp_dp_plugin_options = apply_filters( 'wp_dp_translate_options', $wp_dp_plugin_options );
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
			'wp_dp_map_directions' => 'off',
			'wp_dp_map_circle' => 'off',
			'wp_dp_nearby_places' => false,
			'wp_dp_branches_map' => false,
			'wp_dp_branches_markers' => array(),
			'listing_id' => '',
			'map_det_view' => '',
			'quick_view_controls' => false,
			'hide_map_btn' => false,
			'wp_dp_always_show_info' => false,
		);
		extract( shortcode_atts( $defaults, $atts ) );
		if (!wp_script_is( 'wp-dp-google-map-api', 'enqueued' )) {
                    //wp_enqueue_script('wp-dp-google-map-api');
                }
		wp_enqueue_script( 'wp_dp_map_style_js' );
		wp_enqueue_script( 'wp-dp-listing-map' );

		if ( $listing_id > 0 ) {
			$listing_type_slug = get_post_meta( $listing_id, 'wp_dp_listing_type', true );
			$listing_type_post = get_page_by_path( $listing_type_slug, OBJECT, 'listing-type' );
			$type_id = isset( $listing_type_post->ID ) ? $listing_type_post->ID : 0;
		}

		if ( $map_info_width == '' || $map_info_height == '' ) {
			$map_info_width = '300';
			$map_info_height = '150';
		}

		if ( isset( $map_height ) && $map_height == '' ) {
			$map_height = '500';
		}

		$map_dynmaic_no = rand( 1165480, 99999999 );

		if ( $map_rand_num != '' ) {
			$map_dynmaic_no = $map_rand_num;
		}

		$border = '';
		if ( isset( $map_border ) && $map_border == 'yes' && $map_border_color != '' ) {
			$border = 'border:1px solid ' . $map_border_color . '; ';
		}

		$map_type = isset( $map_type ) ? $map_type : '';
		$radius_circle = isset( $wp_dp_plugin_options['wp_dp_default_radius_circle'] ) ? $wp_dp_plugin_options['wp_dp_default_radius_circle'] : '10';
		$radius_circle = ($radius_circle * 1000);

		ob_start();

		if ( isset( $type_id ) && $type_id > 0 ) {

			$near_by_options = get_post_meta( $type_id, 'wp_dp_near_by_options_element', true );

			if ( isset( $near_by_options ) && $near_by_options != 'on' ) {
				$wp_dp_nearby_places = false;
			}
		}

		$map_col_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
		if ( isset( $wp_dp_nearby_places ) && $wp_dp_nearby_places == true && $map_det_view != 'detial-v4' ) {

			$wp_dp_map_markers_data = isset( $wp_dp_plugin_options['wp_dp_map_markers_data'] ) ? $wp_dp_plugin_options['wp_dp_map_markers_data'] : array();
			if ( isset( $wp_dp_map_markers_data['image'] ) && is_array( $wp_dp_map_markers_data['image'] ) && sizeof( $wp_dp_map_markers_data['image'] ) > 0 ) :
				wp_enqueue_style( 'swiper' );
				wp_enqueue_script( 'swiper' );
				?>
				<div class="map-checkboxes"> 
					<div class="swiper-container">
						<div class="swiper-wrapper">
							<?php
							foreach ( $wp_dp_map_markers_data['image'] as $key => $row ) :
								$image = isset( $wp_dp_map_markers_data['image'][$key] ) ? $wp_dp_map_markers_data['image'][$key] : '';
								$map_image = isset( $wp_dp_map_markers_data['map_image'][$key] ) ? $wp_dp_map_markers_data['map_image'][$key] : '';
								$title = isset( $wp_dp_map_markers_data['label'][$key] ) ? $wp_dp_map_markers_data['label'][$key] : '';
								$type = isset( $wp_dp_map_markers_data['type'][$key] ) ? $wp_dp_map_markers_data['type'][$key] : '';
								$icon_val = isset( $wp_dp_map_markers_data['icon'][$key] ) ? $wp_dp_map_markers_data['icon'][$key] : '';
								$map_icon_type = isset( $wp_dp_map_markers_data['icon_type'][$key] ) && $wp_dp_map_markers_data['icon_type'][$key] != '' ? $wp_dp_map_markers_data['icon_type'][$key] : 'image';
								$icon_group = isset( $wp_dp_map_markers_data['icon_group'][$key] ) ? $wp_dp_map_markers_data['icon_group'][$key] : '';
								wp_enqueue_style( 'cs_icons_data_css_' . $icon_group );
								$image_map = isset( $image ) ? wp_get_attachment_url( $image ) : '';
								?>
								<div class="swiper-slide">
									<div class="checkbox">
										<?php
										$wp_dp_opt_array = array(
											'std' => '',
											'simple' => true,
											'cust_id' => esc_html( $type ),
											'cust_name' => esc_html( $type ),
											'classes' => 'show-poi-checkbox wp_dp_show_nearby',
											'extra_atr' => ' data-label="' . esc_html( $title ) . '" data-image="' . wp_get_attachment_url( $map_image ) . '"',
										);
										$wp_dp_form_fields_frontend->wp_dp_form_checkbox_render( $wp_dp_opt_array );

										if ( $map_icon_type == 'icon' ) {
											?>
											<label class="show-nearby-point-label" for="<?php echo esc_html( $type ); ?>"><i class="<?php echo esc_html( $icon_val ); ?>"></i> <span><?php echo esc_html( $title ); ?></span> </label>
											<?php
										} else {
											?>
											<label class="show-nearby-point-label" for="<?php echo esc_html( $type ); ?>"><img src="<?php echo esc_url( $image_map ); ?>" alt="" /> <span><?php echo esc_html( $title ); ?></span> </label>
											<?php
										}
										?>
									</div>
								</div>
								<?php
							endforeach;
							?>
						</div>

					</div>
					<div class="swiper-checkbox-next"><i class="icon-arrows"></i></div>
					<div class="swiper-checkbox-prev"><i class="icon-arrows-1"></i></div>
				</div>
				<?php
			endif;

			if ( $map_det_view != 'detial-v5-gallery' ) {
				echo '<div class="row">';
				echo '<div class="map-radius-holder">';
			}

			if ( isset( $wp_dp_nearby_places ) && $wp_dp_nearby_places == true ) {
				$wp_dp_map_markers_data = isset( $wp_dp_plugin_options['wp_dp_map_markers_data'] ) ? $wp_dp_plugin_options['wp_dp_map_markers_data'] : array();
				if ( isset( $wp_dp_map_markers_data['image'] ) && is_array( $wp_dp_map_markers_data['image'] ) && sizeof( $wp_dp_map_markers_data['image'] ) > 0 ) :
					$map_col_class = 'col-lg-8 col-md-8 col-sm-6 col-xs-12';
					?>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div id="map-places-detail-<?php echo esc_html( $map_dynmaic_no ) ?>" class="map-places-detail-boxes"></div>
						<div id="map-direction-detail-<?php echo esc_html( $map_dynmaic_no ) ?>" style="display:none;"></div>
					</div>
					<?php
				endif;
			}
		} else {
			echo '<div class="row">';
		}

		if ( $map_det_view != 'detial-v5-gallery' ) {
			echo '<div class="' . $map_col_class . '">';
		}

		if ( $map_marker_icon == '' ) {
			$map_marker_icon = isset( $wp_dp_plugin_options['wp_dp_map_marker_icon'] ) ? $wp_dp_plugin_options['wp_dp_map_marker_icon'] : '';
			if ( $map_marker_icon != '' && is_numeric( $map_marker_icon ) ) {
				$map_marker_icon = wp_get_attachment_url( $map_marker_icon );
			}
		}
		$html = ob_get_clean();

		if ( $map_det_view != 'detial-v5-gallery' ) {
			$html .= '<div ' . $wp_dp_map_class . ' style="animation-duration:">';
			$html .= '<div class="clear"></div>';
			$html .= '<div class="cs-map-section" style="' . $border . ';">';
			$distance_symbol = isset( $wp_dp_plugin_options['wp_dp_distance_measure_by'] ) ? $wp_dp_plugin_options['wp_dp_distance_measure_by'] : 'km';
			if ( $map_det_view != 'detial-v4' ) {
				$html .= '
				<div class="map-places-radius-box">
					<div>
						<ul class="radius-val-dropdown">
							<li>
								<span class="radius-val-km dev-ch-radius-val" data-val="5">5 ' . $distance_symbol . '</span>
								<ul>
									<li><span class="radius-val-km" data-val="1">1 ' . $distance_symbol . '</span></li>
									<li><span class="radius-val-km" data-val="2">2 ' . $distance_symbol . '</span></li>
									<li><span class="radius-val-km" data-val="3">3 ' . $distance_symbol . '</span></li>
									<li><span class="radius-val-km" data-val="4">4 ' . $distance_symbol . '</span></li>
									<li><span class="radius-val-km" data-val="5">5 ' . $distance_symbol . '</span></li>
								</ul>
							</li>
						</ul>
						<input type="hidden" id="map-radius-input-' . $map_dynmaic_no . '" value="5">
					</div>
				</div>';
			}
		}

		$html .= '<div class="cs-map">
		<span id="clik-map-view-changed" style="position:absolute;">&nbsp;</span>';
		$html .= '<div class="cs-map-content">';
		if ( $hide_map_btn == true ) {
			$html .= '<div class="cs-map-hide"><a href="javascript:void(0);">' . wp_dp_plugin_text_srt( 'wp_dp_agent_review_hide_map' ) . '</a></div>';
		}
		$html .= '<div class="mapcode iframe mapsection gmapwrapp" id="map_canvas' . $map_dynmaic_no . '" style="height:' . $map_height . 'px;"> </div>';

		if ( $wp_dp_map_directions == 'off' ) {
			//$html .= '<div id="cs-directions-panel"></div>';
		}
		$html .= '</div>';
		$html .= '</div>';

		$html .= "<script type='text/javascript'>";
		if ( $ready_state == true ) {
			$html .= "jQuery(document).ready(function() {";
		}



		if ( $map_det_view == 'detial-v4' ) {
			$html .= "
						function googleMapButton(text, className) {
							'use strict';
							var controlDiv = document.createElement('div');
							controlDiv.className = className;
							controlDiv.index = 1;
							//controlDiv.style.margin = '0 10px 10px 0';
							// set CSS for the control border.
							var controlUi = document.createElement('div');
							controlUi.className = 'map-fullscreen-btn';
							controlDiv.appendChild(controlUi);
							// set CSS for the control interior.
							var controlText = document.createElement('a');
							controlText.innerHTML = '<i class=\"icon-fullscreen\"></i> ' + text;
							controlUi.appendChild(controlText);
							return controlDiv;
						}
						function FullScreenControl(map, enterFull, exitFull) {
							'use strict';
							if (enterFull === void 0) { enterFull = null; }
							if (exitFull === void 0) { exitFull = null; }
							if (enterFull == null) {
								enterFull = '" . wp_dp_plugin_text_srt( 'wp_dp_map_full_screen_text' ) . "';
							}
							if (exitFull == null) {
								exitFull = '" . wp_dp_plugin_text_srt( 'wp_dp_map_exit_full_screen_text' ) . "';
							}
							var controlDiv = googleMapButton(enterFull, 'wp-dp-map-full-screen');
							var fullScreen = false;
							var interval;
							var mapDiv = map.getDiv();
							//
							// header get styles
							if (document.getElementById('header')) {
							var headerDiv = document.getElementById('header');
							var headerDivStyle = headerDiv.style;
							if (headerDiv.runtimeStyle) {
								headerDivStyle = headerDiv.runtimeStyle;
							}
							var headerOriginalPos = headerDivStyle.position;
							var headerOriginalZIndex = headerDivStyle.zIndex;
		}
		if (document.getElementById('footer')) {
							// footer get styles
							var footerDiv = document.getElementById('footer');
							var footerDivStyle = footerDiv.style;
							if (footerDiv.runtimeStyle) {
								footerDivStyle = footerDiv.runtimeStyle;
							}
							var footerOriginalPos = footerDivStyle.position;
							var footerOriginalZIndex = footerDivStyle.zIndex;
		}
							// main id get styles
							var mainDiv = document.getElementById('main');
							var mainDivStyle = mainDiv.style;
							if (mainDiv.runtimeStyle) {
								mainDivStyle = mainDiv.runtimeStyle;
							}
							var mainOriginalPos = mainDivStyle.position;
							var mainOriginalZIndex = mainDivStyle.zIndex;
							
							// .detail-nav-wrap get styles
							var navwrapDiv = $('.detail-nav-wrap').get(0);
							var navwrapDivStyle = navwrapDiv.style;
							if (navwrapDiv.runtimeStyle) {
								navwrapDivStyle = navwrapDiv.runtimeStyle;
							}
							var navwrapOriginalPos = navwrapDivStyle.position;
							var navwrapOriginalZIndex = navwrapDivStyle.zIndex;
							
							// .dominant-places-wrapper get styles
							var nearplacesDiv = $('.dominant-places-wrapper').get(0);
							var nearplacesDivStyle = nearplacesDiv.style;
							if (nearplacesDiv.runtimeStyle) {
								nearplacesDivStyle = nearplacesDiv.runtimeStyle;
							}
							var nearplacesOriginalPos = nearplacesDivStyle.position;
							var nearplacesOriginalZIndex = nearplacesDivStyle.zIndex;
							
							// .map-actions get styles
							var mapactsDiv = $('.map-actions').get(0);
							var mapactsDivStyle = mapactsDiv.style;
							if (mapactsDiv.runtimeStyle) {
								mapactsDivStyle = mapactsDiv.runtimeStyle;
							}
							var mapactsOriginalPos = mapactsDivStyle.position;
							var mapactsOriginalZIndex = mapactsDivStyle.zIndex;
							//
							
							var divStyle = mapDiv.style;
							if (mapDiv.runtimeStyle) {
								divStyle = mapDiv.runtimeStyle;
							}
							var originalPos = divStyle.position;
							var originalWidth = divStyle.width;
							var originalHeight = divStyle.height;
							// ie8 hack
							if (originalWidth === '') {
								originalWidth = mapDiv.style.width;
							}
							if (originalHeight === '') {
								originalHeight = mapDiv.style.height;
							}
							var originalTop = divStyle.top;
							var originalLeft = divStyle.left;
							var originalZIndex = divStyle.zIndex;
							var bodyStyle = document.body.style;
							if (document.body.runtimeStyle) {
								bodyStyle = document.body.runtimeStyle;
							}
							var originalOverflow = bodyStyle.overflow;
							controlDiv.goFullScreen = function () {
								var center = map.getCenter();
								mapDiv.style.position = 'fixed';
								mapDiv.style.width = '100%';
								mapDiv.style.height = '100%';
								mapDiv.style.top = '0';
								mapDiv.style.left = '0';
								mapDiv.style.zIndex = '9999';
								//
								if (document.getElementById('header')) {
								headerDiv.style.position = 'fixed';
								headerDiv.style.zIndex = '-1';
		}if (document.getElementById('footer')) {
								footerDiv.style.position = 'fixed';
								footerDiv.style.zIndex = '-1';
		}
								mainDiv.style.position = 'fixed';
								mainDiv.style.zIndex = '-1';
								navwrapDiv.style.position = 'fixed';
								navwrapDiv.style.zIndex = '-1';
								nearplacesDiv.style.position = 'fixed';
								nearplacesDiv.style.zIndex = '99999';
								mapactsDiv.style.position = 'fixed';
								mapactsDiv.style.zIndex = '99999';
								//
								document.body.style.overflow = 'hidden';
								$(controlDiv).find('div a').html('<i class=\"icon-fullscreen_exit\"></i> ' + exitFull);
								fullScreen = true;
								google.maps.event.trigger(map, 'resize');
								map.setCenter(center);
								// this works around street view causing the map to disappear, which is caused by Google Maps setting the 
								// css position back to relative. There is no event triggered when Street View is shown hence the use of setInterval
								interval = setInterval(function () {
									if (mapDiv.style.position !== 'fixed') {
										mapDiv.style.position = 'fixed';
										google.maps.event.trigger(map, 'resize');
									}
								}, 100);
							};
							controlDiv.exitFullScreen = function () {
								var center = map.getCenter();
								if (originalPos === '') {
									mapDiv.style.position = 'relative';
								}
								else {
									mapDiv.style.position = originalPos;
								}
								mapDiv.style.width = originalWidth;
								mapDiv.style.height = originalHeight;
								mapDiv.style.top = originalTop;
								mapDiv.style.left = originalLeft;
								mapDiv.style.zIndex = originalZIndex;
								//
								if (document.getElementById('header')) {
								headerDiv.style.position = headerOriginalPos;
								headerDiv.style.zIndex = headerOriginalZIndex;
		}if (document.getElementById('footer')) {
								footerDiv.style.position = footerOriginalPos;
								footerDiv.style.zIndex = footerOriginalZIndex;
		}
								mainDiv.style.position = mainOriginalPos;
								mainDiv.style.zIndex = mainOriginalPos;
								navwrapDiv.style.position = navwrapOriginalPos;
								navwrapDiv.style.zIndex = navwrapOriginalPos;
								nearplacesDiv.style.position = nearplacesOriginalPos;
								nearplacesDiv.style.zIndex = nearplacesOriginalPos;
								mapactsDiv.style.position = mapactsOriginalPos;
								mapactsDiv.style.zIndex = mapactsOriginalPos;
								//
								document.body.style.overflow = originalOverflow;
								$(controlDiv).find('div a').html('<i class=\"icon-fullscreen\"></i> ' + enterFull);
								fullScreen = false;
								google.maps.event.trigger(map, 'resize');
								map.setCenter(center);
								clearInterval(interval);
							};
							// setup the click event listener
							google.maps.event.addDomListener(controlDiv, 'click', function () {
								if (!fullScreen) {
									controlDiv.goFullScreen();
								}
								else {
									controlDiv.exitFullScreen();
								}
							});
							return controlDiv;
						}";
		}

		$html .= "
		function mapZoomControlBtns(map, icon_plus, icon_minus) {
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

		$html .= "
					var center = new google.maps.LatLng(" . $map_lat . ", " . $map_lon . ");  
					var panorama;
                                        initialize();
					function initialize() {
						var myLatlng = new google.maps.LatLng(" . $map_lat . ", " . $map_lon . ");
						var mapOptions = {
							zoom: " . $map_zoom . ",
							scrollwheel: " . $map_scrollwheel . ",
							draggable: " . $map_draggable . ",
							streetViewControl: false,";






		$html .= "  center: center,
                    disableDefaultUI: true,
                    zoomControl: false,
                    mapTypeId: 'terrain',";
		if ( $map_det_view == 'view-5' ) {
			$html .= "zoomControl: false,";
		} else {
			$html .= "zoomControl: true,
                    zoomControlOptions: {
                            position: google.maps.ControlPosition.LEFT_TOP
                    },";
		}
		$html .= "mapTypeControl: false,};";
		$html .= "var directionsDisplay;
		var directionsService = new google.maps.DirectionsService();
		directionsDisplay = new google.maps.DirectionsRenderer();";

		if ( $map_det_view == 'view-5' || $map_det_view == 'detial-v5-gallery' ) {
			$html .= "var map = new google.maps.Map(document.getElementById('map_canvas" . $map_dynmaic_no . "'), mapOptions);";
		} else {
			$html .= "map = new google.maps.Map(document.getElementById('map_canvas" . $map_dynmaic_no . "'), mapOptions);";
		}
		if ( $quick_view_controls == false ) {

			if ( $map_det_view == 'detial-v4' ) {
				$html .= "
			map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(FullScreenControl(map, '" . wp_dp_plugin_text_srt( 'wp_dp_map_full_screen_text' ) . "', '" . wp_dp_plugin_text_srt( 'wp_dp_map_exit_full_screen_text' ) . "'));
			map.controls[google.maps.ControlPosition.LEFT_TOP].push(mapZoomControlBtns(map, 'icon-plus', 'icon-minus'));";
			} else {
				$html .= "map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(mapZoomControlBtns(map, 'icon-plus', 'icon-minus'));";
			}
		}

		if ( $wp_dp_map_circle == 'on' ) {

			$html .= "var circle = new google.maps.Circle({
						center: center,
						map: map,
						radius: " . $radius_circle . ",          // IN METERS.
						fillColor: '#FF6600',
						fillOpacity: 0.3,
						strokeColor: '#FF6600',
						strokeWeight: 1         // CIRCLE BORDER.     
					});";
		}

		$html .= "
		directionsDisplay.setMap(map);
        directionsDisplay.setPanel(document.getElementById('map-direction-detail-" . $map_dynmaic_no . "'));
		directionsDisplay.setOptions( { suppressMarkers: true } );";

		// Setting zoom level
		$html .= " 
		google.maps.event.addDomListener(document.getElementById('clik-map-view-changed'), 'click', function () {
			var getCurZoom = map.getZoom();
			if (getCurZoom < 3) {
				if (LatLngList.length > 0) {
					var latlngbounds = new google.maps.LatLngBounds();
					for (var io = 0; io < LatLngList.length; io++) {
						latlngbounds.extend(LatLngList[io]);
					}
					map.setCenter(latlngbounds.getCenter(), map.fitBounds(latlngbounds));
					map.setZoom(map.getZoom());
				} else {
					map.setZoom(" . $map_zoom . "); 
				}
				google.maps.event.trigger(map, 'resize');
			}
		});";
		//

		$wp_dp_map_style = isset( $wp_dp_plugin_options['wp_dp_def_map_style'] ) ? $wp_dp_plugin_options['wp_dp_def_map_style'] : '';
		$map_custom_style = isset( $wp_dp_plugin_options['wp_dp_map_custom_style'] ) ? $wp_dp_plugin_options['wp_dp_map_custom_style'] : '';

		if ( $map_custom_style != '' ) {
			$map_custom_style = str_replace( '&quot;', '"', $map_custom_style );
			$html .= "var style = " . $map_custom_style . ";
					if (style != '') {
						var styledMap = new google.maps.StyledMapType(style,
								{name: 'Styled Map'});
						map.mapTypes.set('map_style', styledMap);
						map.setMapTypeId('map_style');
					}";
		} else {
			$html .= "var style = '" . $wp_dp_map_style . "';
					if (style != '') { 
						var styles = wp_dp_map_select_style(style);
						if (styles != '') {
							var styledMap = new google.maps.StyledMapType(styles, {name: 'Styled Map'});
							map.mapTypes.set('map_style', styledMap);
							map.setMapTypeId('map_style');
						}
					}";
		}
		//if ( $wp_dp_map_circle != 'on' ) {

		if ( $wp_dp_branches_map ) {
			$pixelOffset = "new google.maps.Size(-108, -72)";
			$offset_start = '-108';
			$offset_end = '-72';
			if ( $quick_view_controls == true ) {
				$offset_start = '40';
				$offset_end = '72';
				$pixelOffset = "new google.maps.Size(40, 72)";
			}
			$wp_dp_map_cluster_icon = isset( $wp_dp_plugin_options['wp_dp_map_cluster_icon'] ) && $wp_dp_plugin_options['wp_dp_map_cluster_icon'] != '' ? wp_get_attachment_url( $wp_dp_plugin_options['wp_dp_map_cluster_icon'] ) : wp_dp::plugin_url() . '/assets/frontend/images/map-cluster.png';
			$html .= "
				var open_info_window;
				var all_branches_markers = [];
				// var infowindow = new google.maps.InfoWindow({
					// boxClass: 'liting_map_info',
					// content: '" . $map_info . "',
					// maxWidth: " . $map_info_width . ",
					// //pixelOffset: new google.maps.Size(-108, -72),
					// zIndex: null,
					// closeBoxMargin: '2px',
                    // closeBoxURL: 'close',
                    // infoBoxClearance: new google.maps.Size(1, 1),
                    // pane: 'floatPane',
					// enableEventPropagation: false,
					// //disableAutoPan: true,
					// isHidden: false,
				// });
				
				var infowindow = new InfoBox({
					boxClass: 'liting_map_info',							
					content: '" . $map_info . "',
					maxWidth: 0,
					alignBottom: true,
					pixelOffset: new google.maps.Size(" . $offset_start . ", " . $offset_end . "),
					zIndex: null,
					closeBoxMargin: '2px',
					closeBoxURL: 'close',
					infoBoxClearance: new google.maps.Size(1, 1),
					isHidden: false,
					pane: 'floatPane',
					enableEventPropagation: false,
				});
                    
				var marker = new google.maps.Marker({
					position: myLatlng,
					animation: google.maps.Animation.DROP,
					map: map,
					title: '',
					icon: '" . $map_marker_icon . "',
					shadow: ''
				});
				
				all_branches_markers.push( marker );
				
				if (infowindow.content != ''){
					google.maps.event.addListener(marker, 'click', function(event) {
						if (open_info_window) {
                            open_info_window.close();
						}
						map.panTo(marker.getPosition());
						infowindow.open(map, marker);
						open_info_window = infowindow;
					});
				}
                                

				
				google.maps.event.addListener(map, 'click', function (event) {
					if (infowindow) {
						infowindow.close();
					}
				});";

			if ( isset( $wp_dp_always_show_info ) && $wp_dp_always_show_info == true ) {
				$html .= "infowindow.open(map, marker);";
			}
			if ( isset( $wp_dp_branches_markers ) ) {

				$html .= "
					var markers = " . json_encode( $wp_dp_branches_markers ) . ";
					var LatLngList = [];
					
					$.each(markers, function( key, marker ) {
						myLatlng = new google.maps.LatLng( marker.lat, marker.lng );
						
						LatLngList.push(myLatlng);
						
						var infowindow1 = new InfoBox({
							boxClass: 'liting_map_info',							
							content: marker.content,
							maxWidth: 0,
							alignBottom: true,
							pixelOffset: new google.maps.Size(-108, -72),
							zIndex: null,
							closeBoxMargin: '2px',
							closeBoxURL: 'close',
							infoBoxClearance: new google.maps.Size(1, 1),
							isHidden: false,
							pane: 'floatPane',
							enableEventPropagation: false,
						});
						marker = new google.maps.Marker({
							position: myLatlng,
							map: map,
							animation: google.maps.Animation.DROP,
							title: '',
							icon: '" . $map_marker_icon . "',
							shadow: ''
						});
						
						all_branches_markers.push( marker );
						add_infowindow_event( marker, infowindow1 );
						
					});
					
					function add_infowindow_event( marker2, infowindow2 ) {
						if (infowindow2.content != ''){
							google.maps.event.addListener(marker2, 'click', function(event) {
								if (open_info_window) {
									open_info_window.close();
								}
								map.panTo(marker2.getPosition());
								infowindow2.open(map, marker2);
								open_info_window = infowindow2;
							});
						}
					}
					
					if (all_branches_markers) {
						var mcOptions;
						var clusterStyles = [
							{
								textColor: '#222222',
								opt_textColor: '#222222',
								url: '" . $wp_dp_map_cluster_icon . "',
								height: 65,
								width: 65,
								textSize: 12
							}
						];
						mcOptions = {
							gridSize: 15,
							ignoreHidden: true,
							maxZoom: 12,
							styles: clusterStyles
						};
						//markerClusterers = new MarkerClusterer(map, all_branches_markers, mcOptions);
					}
					
					window.all_branches_markers = all_branches_markers;
					
					if ( LatLngList.length > 0 ) {
						var latlngbounds = new google.maps.LatLngBounds();
						for (var i = 0; i < LatLngList.length; i++) {
							latlngbounds.extend(LatLngList[i]);
						}
						map.setCenter(latlngbounds.getCenter(), map.fitBounds(latlngbounds));

						map.setZoom(map.getZoom());
					}";
			}
		} else {
			$html .= "var infowindow = new google.maps.InfoWindow({
								content: '" . $map_info . "',
								maxWidth: " . $map_info_width . ",
								maxHeight: " . $map_info_height . ",
							});
							var marker = new google.maps.Marker({
								position: myLatlng,
								map: map,
								animation: google.maps.Animation.DROP,
								title: '',
								icon: '" . $map_marker_icon . "',
								shadow: ''
							});
							if (infowindow.content != ''){
							  infowindow.open(map, marker);
							   map.panBy(1,-60);
							   google.maps.event.addListener(marker, 'click', function(event) {
								infowindow.open(map, marker);
							   });
							};";
		}
		//  }
		$html .= "panorama = map.getStreetView();
							panorama.setPosition(myLatlng);
							panorama.setPov(({
							  heading: 265,
							  pitch: 0
							}));
						";

		if ( isset( $wp_dp_nearby_places ) && $wp_dp_nearby_places == true ) {
			$distance_symbol = isset( $wp_dp_plugin_options['wp_dp_distance_measure_by'] ) ? $wp_dp_plugin_options['wp_dp_distance_measure_by'] : 'km';
			ob_start();
			?>
			var markersArray = [];

			var map_slide_loader = $('.slide-loader');

			$(document).on('click', '.map-checkboxes .checkbox', function() {
			$(this).find('input[type="checkbox"]').trigger('click');
			$(this).find('input[type="checkbox"]').prop('checked', true);
			});
			$(document).on('click', '.map-checkboxes-v2 .swiper-slide', function() {
			$(this).find('input[type="checkbox"]').trigger('click');
			$(this).find('input[type="checkbox"]').prop('checked', true);
			});

			$(document).on('click', '.radius-val-dropdown ul > li', function() {
			var distance_symbol = '<?php echo wp_dp_cs_allow_special_char( $distance_symbol ); ?>';
			var this_val = $(this).find('.radius-val-km').attr('data-val');
			var this_val_org = $(this).find('.radius-val-km').attr('data-val');
			if( distance_symbol == 'miles' ){
			this_val = parseInt(this_val) * 1.6093;
			}
			$('.dev-ch-radius-val').attr('data-val', this_val);
			$('.dev-ch-radius-val').html(this_val_org + '<?php echo esc_html( $distance_symbol ) ?>');
			$('#map-radius-input-<?php echo esc_html( $map_dynmaic_no ) ?>').val(this_val);
			jQuery('#map-radius-input-<?php echo wp_dp_cs_allow_special_char( $map_dynmaic_no ); ?>').trigger('change');
			$('.radius-val-dropdown li > ul').hide();
			});

			jQuery(document).on('change', '#map-radius-input-<?php echo esc_html( $map_dynmaic_no ) ?>', function(){
			if (!map_slide_loader.hasClass('loading')) {
			map_slide_loader.addClass('loading');
			}
			if ($('input.wp_dp_show_nearby:checked').length !== 0) {
			var datType = $('input.wp_dp_show_nearby:checked').attr('id');
			var map_center = map.getCenter();
			var datImage = $('input.wp_dp_show_nearby:checked').attr('data-image');
			var datLabel = $('input.wp_dp_show_nearby:checked').attr('data-label');
			search_types(datType, map_center, datImage, datLabel);
			}
			});

			jQuery(document).ready(function(){
			$(document).on('hover', '.radius-val-dropdown li', function(){
			$(this).find('ul').show();
			});
			var stIntrval = setInterval(function(){
			$('input.wp_dp_show_nearby:first').trigger('click');
			$('input.wp_dp_show_nearby:first').prop('checked', true);
			$('.map-places-radius-box, .map-places-detail-boxes').show();
			clearInterval(stIntrval);
			}, 1000);
			});


			$('.wp_dp_show_nearby').click(function () {
			if (!map_slide_loader.hasClass('loading')) {
			map_slide_loader.addClass('loading');
			}
			$('.wp_dp_show_nearby').prop('checked', false);
			$('.wp_dp_show_nearby').removeAttr('checked');
			$(this).prop('checked', true);
			$(this).attr('checked', 'checked');

			var map_center = map.getCenter();
			if ( $(this).is(":checked") ) {

			directionsDisplay.setDirections({routes: []});
			clearOverlays();
			search_types( $(this).attr('id'), map_center, $(this).data('image'), $(this).data('label') );
			} else {
			clearOverlays();
			$('.wp_dp_show_nearby:checked').each(function(key, elem) {
			search_types( $(this).attr('id'), map_center, $(this).data('image'), $(this).data('label') );
			});
			}
			});

			clearOverlays();
			$('.wp_dp_show_nearby:checked').each(function(key, elem) {
			search_types( $(this).attr('id'), map.getCenter(), $(this).data('image'), $(this).data('label') );
			});

			function search_types(type, latLng, image, label) {
			LatLngList = [];
			latLng = new google.maps.LatLng(<?php echo ($map_lat) ?>, <?php echo ($map_lon) ?>);
			LatLngList.push(new google.maps.LatLng(<?php echo ($map_lat) ?>, <?php echo ($map_lon) ?>));
			if (!latLng) {
			var latLng = pyrmont;
			}
			var icon = image;

			var randNum = Math.random()*1000000;
			randNum = Math.ceil(randNum);

			var inpRadius<?php echo ($map_dynmaic_no); ?> = jQuery('#map-radius-input-<?php echo wp_dp_cs_allow_special_char( $map_dynmaic_no ); ?>').val();

			if ( typeof inpRadius<?php echo ($map_dynmaic_no); ?> === "undefined" || inpRadius<?php echo ($map_dynmaic_no); ?> < 1) {
			inpRadius<?php echo ($map_dynmaic_no); ?> = 1;
			}

			var newInpRadius<?php echo ($map_dynmaic_no); ?> = inpRadius<?php echo ($map_dynmaic_no); ?> * 1000;

			clearOverlays();

			var service<?php echo ($map_dynmaic_no); ?> = new google.maps.places.PlacesService(map);
			service<?php echo ($map_dynmaic_no); ?>.nearbySearch({
			location: latLng,
			//radius: newInpRadius<?php echo ($map_dynmaic_no); ?>,
			rankBy: google.maps.places.RankBy.DISTANCE,
			types: [type] //e.g. school,restaurant,bank,bar,city_hall,gym,night_club,park,zoo
			}, processResults<?php echo ($map_dynmaic_no); ?>);

			function processResults<?php echo ($map_dynmaic_no); ?> (results<?php echo ($map_dynmaic_no); ?>, status, pagination) {

			var preZoomLvl = 13;
			map.setZoom(preZoomLvl);
			if (status == google.maps.places.PlacesServiceStatus.OK) {

			<?php
			if ( $map_det_view != 'detial-v4' ) {
				?>

				var totalResHTML = '';

				var totalResCount = 0;
				for (var i = 0; i < results<?php echo ($map_dynmaic_no); ?>.length; i++) {

				var place = results<?php echo ($map_dynmaic_no); ?>[i];

				var detResult = place;

				var placeLocation = detResult.geometry.location;
				var placeLat = placeLocation.lat();
				var placeLng = placeLocation.lng();

				var placeDist = calcDistanceBtwPlaces(<?php echo ($map_lat) ?>, <?php echo ($map_lon) ?>, placeLat, placeLng);

				if (placeDist <= newInpRadius<?php echo ($map_dynmaic_no); ?>) {
				var timeHtml = '';

				function getTimeHtml(count) {
				var address = "<?php echo ($map_lat) ?>,<?php echo ($map_lon) ?>";
				var source = placeLat + "," + placeLng;
				var service = new google.maps.DistanceMatrixService();
				service.getDistanceMatrix(
				{
				origins: [address],
				destinations: [source],
				travelMode: 'DRIVING',
				unitSystem: google.maps.UnitSystem.IMPERIAL,
				}, function (responsef, status) {
				if (typeof responsef === 'object' && responsef != null && typeof responsef.rows === 'object') {
				var timeInerHtml = '<i class="icon-directions_walk"></i> '+responsef.rows[0].duration.text;
				$('#place-time-' + count).html(timeInerHtml);
				} else {
				$('#place-time-' + count).html('');
				}
				});
				}
				getTimeHtml(i);

				timeHtml = '<span id="place-time-" ' + String(i) + ' " class="place-time"></span>';

				totalResHTML += '\
				<div id="place-point-<?php echo esc_html( $map_dynmaic_no ) ?>' + String(i) + '" class="places-detail-box">\
					<span class="place-name"><a>'+detResult.name+'</a></span>\
					<span class="place-distance"><i class="icon-meter"></i> '+meterToKmConvert(placeDist)+'</span> ' + timeHtml + '\
				</div>';
				totalResCount ++;
				}
				}

				var doTotalResHTML = '';

				if (type != '') {
				doTotalResHTML += '\
				<div class="places-found-box">\
					<span class=\"places-count-name\">' +(label.replace("_", " "))+' </span><span class=\"places-count-num\">'+totalResCount+ ' <?php echo wp_dp_plugin_text_srt( 'wp_dp_map_places_found' ) ?></span>\
				</div>';
				doTotalResHTML += totalResHTML;
				}

				jQuery('#map-places-detail-<?php echo esc_html( $map_dynmaic_no ) ?>').html(doTotalResHTML);

				<?php
			}
			?>
			for (var i = 0; i < results<?php echo ($map_dynmaic_no); ?>.length; i++) {
			var place = results<?php echo ($map_dynmaic_no); ?>[i];

			var detResult = place;

			var placeLocation = detResult.geometry.location;
			var placeLat = placeLocation.lat();
			var placeLng = placeLocation.lng();

			var placeDist = calcDistanceBtwPlaces(<?php echo ($map_lat) ?>, <?php echo ($map_lon) ?>, placeLat, placeLng);

			if (placeDist <= newInpRadius<?php echo ($map_dynmaic_no); ?>) {
			results<?php echo ($map_dynmaic_no); ?>[i].html_attributions = '';

			var markerCountr = '<?php echo esc_html( $map_dynmaic_no ) ?>' + String(i);

			createMarker(results<?php echo ($map_dynmaic_no); ?>[i], icon, markerCountr);

			if ((i+1) == results<?php echo ($map_dynmaic_no); ?>.length) {
			map_slide_loader.removeClass('loading');
			}
			}
			}

			if (LatLngList.length > 1) {
			var latlngbounds = new google.maps.LatLngBounds();
			for (var i = 0; i < LatLngList.length; i++) {
			latlngbounds.extend(LatLngList[i]);
			}
			//map.setCenter(latlngbounds.getCenter(), map.fitBounds(latlngbounds));
			//map.panTo(latlngbounds.getCenter(), map.fitBounds(latlngbounds));
			//preZoomLvl = map.getZoom();
			//map.setZoom(preZoomLvl);
			map.setCenter(latLng);
			<?php
			if ( $map_det_view == 'view-5' || $map_det_view == 'view-1' ) {
				?>
				map.panBy(0, -40);
				<?php
			}
			?>
			map.setZoom(13);
			}
			} else {
			if (preZoomLvl > 9) {
			preZoomLvl = 9;
			}
			map.setCenter(latLng);
			map.setZoom(preZoomLvl);
			<?php
			if ( $map_det_view != 'detial-v4' ) {
				?>
				jQuery('#map-places-detail-<?php echo esc_html( $map_dynmaic_no ) ?>').html('\
				<div class="places-found-box">\
					<span class=\"places-count-name\">' +(label.replace("_", " "))+' </span><span class=\"places-count-num\">0 <?php echo wp_dp_plugin_text_srt( 'wp_dp_map_places_found' ) ?></span>\
				</div>');
				<?php
			}
			?>
			}
			}
			}
			function createMarker(place, icon, countr) {
			var placeLoc = place.geometry.location;
			var marker = new google.maps.Marker({
			map: map,
			animation: google.maps.Animation.DROP,
			position: place.geometry.location,
			icon: icon,
			visible: true,
			<?php
			if ( $map_det_view != 'detial-v4' ) {
				?>
				pointr: 'place-point-' + countr
				<?php
			}
			?>
			});

			markersArray.push(marker);
			google.maps.event.addListener(marker, 'click', function () {
			infowindow.setContent("<b>" + place.name + "</b><br>" + place.vicinity);
			infowindow.open(map, this);
			});

			var placeLat = placeLoc.lat();
			var placeLng = placeLoc.lng();
			LatLngList.push(new google.maps.LatLng(placeLat, placeLng));

			<?php
			if ( $map_det_view != 'detial-v4' ) {
				?>
				google.maps.event.addDomListener(document.getElementById('place-point-' + countr), "click", (function (marker) {
				return function () {
				$('.map-places-detail-boxes').find('.places-detail-box').removeClass('active');
				$(this).addClass('active');
				var start = new google.maps.LatLng(<?php echo floatval( $map_lat ) ?>, <?php echo floatval( $map_lon ) ?>);
				var end = new google.maps.LatLng(placeLat, placeLng);
				var request = {
				origin:start,
				destination:end,
				travelMode: 'DRIVING'
				};
				directionsService.route(request, function(response, status) {
				if (status == google.maps.DirectionsStatus.OK) {
				directionsDisplay.setDirections(response);
				}
				});

				new google.maps.event.trigger( marker, 'click' );
				}
				})(marker));
				<?php
			}
			?>

			}

			function calcDistanceBtwPlaces(fromLat, fromLng, toLat, toLng) {
			return google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(fromLat, fromLng), new google.maps.LatLng(toLat, toLng));
			}

			function meterToKmConvert(numbr) {
			numbr = parseFloat(numbr);
			var dist = numbr;
			var unit = 'm';
			if (numbr > 999) {
			dist = numbr/1000;
			unit = '<?php echo esc_html( $distance_symbol ) ?>';
			}
			var roundDist = parseFloat(Math.round(dist * 100) / 100).toFixed(2);

			return String(roundDist) + ' ' + unit;
			}

			// Deletes all markers in the array by removing references to them
			function clearOverlays() {
			if (markersArray) {
			for (i in markersArray) {
			infowindow.close();
			markersArray[i].setVisible(false);
			}
			}
			}
			<?php
			$html .= ob_get_clean();
		}

		$html .= "}
		function wp_dp_toggle_street_view(btn) {
		  var toggle = panorama.getVisible();
		  if (toggle == false) {
				if(btn == 'streetview'){
				  panorama.setVisible(true);
				}
		  } else {
				if(btn == 'mapview'){
				  panorama.setVisible(false);
				}
		  }
		}";

		$html .= "
		google.maps.event.addDomListener(window, 'load', initialize);";
		if ( $ready_state == true ) {
			$html .= "});";
		}

		$html .= "if (jQuery('.map-checkboxes .swiper-container').length > 0) {
			new Swiper('.map-checkboxes .swiper-container', {
				spaceBetween: 15,
				nextButton: '.map-checkboxes .swiper-checkbox-next',
				prevButton: '.map-checkboxes .swiper-checkbox-prev',
				slidesPerView: 5,
				speed: 500,
				onInit: function (swiper) {
					jQuery.fn.matchHeight._update();
				},
				breakpoints: {
					1024: {
						slidesPerView: 4,
						spaceBetween: 20
					},
					998: {
						slidesPerView: 4,
						spaceBetween: 20
					},
					767: {
						slidesPerView: 3,
						spaceBetween: 15
					},
					540: {
						slidesPerView: 2,
						spaceBetween: 15
					},
					380: {
						slidesPerView: 1,
						spaceBetween: 15
					}
				}
			});
		}
		</script>";

		if ( $map_det_view != 'detial-v5-gallery' ) {
			$html .= '</div>';
			$html .= '</div>';

			// col class end
			$html .= '</div>';
			if ( isset( $wp_dp_nearby_places ) && $wp_dp_nearby_places == true && $map_det_view != 'detial-v4' ) {
				// .map-radius-holder
				$html .= '</div>';
			}
			// row end
			$html .= '</div>';
		}
		echo wp_dp_cs_allow_special_char( $html );
	}

}

/**
 * Include any template file 
 * with wordpress standards
 */
if ( ! function_exists( 'wp_dp_get_template_part' ) ) {

	function wp_dp_get_template_part( $slug = '', $name = '', $ext_template = '' ) {
		$template = '';

		if ( $ext_template != '' ) {
			$ext_template = trailingslashit( $ext_template );
		}
		if ( $name ) {
			$template = locate_template( array( "{$slug}-{$name}.php", wp_dp::template_path() . "{$ext_template}{$slug}-{$name}.php" ) );
		}
		if ( ! $template && $name && file_exists( wp_dp::plugin_path() . "/templates/{$ext_template}{$slug}-{$name}.php" ) ) {

			$template = wp_dp::plugin_path() . "/templates/{$ext_template}{$slug}-{$name}.php";
		}
		if ( ! $template ) {

			$template = locate_template( array( "{$slug}.php", wp_dp::template_path() . "{$ext_template}{$slug}.php" ) );
		}
		if ( $template ) {
			load_template( $template, false );
		}
	}

}


if ( ! function_exists( 'wp_dp_tooltip_text' ) ) {

	/**
	 * Tool tip text for backend usage.
	 *
	 * @param type $popover_text
	 * @param type $return_html
	 * @return type
	 */
	function wp_dp_tooltip_text( $popover_text = '', $return_html = true ) {
		$popover_link = '';
		if ( isset( $popover_text ) && $popover_text != '' ) {
			$popover_link = '<a class="cs-help" data-toggle="popover" data-placement="right" data-trigger="hover" data-content="' . $popover_text . '"><i class="icon-help"></i></a>';
		}
		if ( $return_html == true ) {
			return $popover_link;
		} else {
			echo force_balance_tags( $popover_link );
		}
	}

}



if ( ! function_exists( 'wp_dp_get_currency' ) ) {

	/**
	 * Return an input variable from $_SERVER if exists else default.
	 *
	 * @param	string $name name of the variable.
	 * @param string $default default value.
	 * @return string
	 */
	function wp_dp_get_currency( $price = '', $currency_symbol = false, $before_currency = '', $after_currency = '', $currency_converter = true ) {
		global $wp_dp_plugin_options;
		$price_str = '';
		$default_currency = isset( $wp_dp_plugin_options['wp_dp_currency_sign'] ) ? $wp_dp_plugin_options['wp_dp_currency_sign'] : '$';
		$currency_pos = isset( $wp_dp_plugin_options['wp_dp_currency_position'] ) ? $wp_dp_plugin_options['wp_dp_currency_position'] : 'left';
		$plugin_currency_id = isset( $wp_dp_plugin_options['wp_dp_currency_id'] ) ? $wp_dp_plugin_options['wp_dp_currency_id'] : '';
		$current_currency_id = wp_dp_get_transient_obj( 'wp_dp_user_currency' );
		$current_currency_id = ( $current_currency_id == '' ) ? $plugin_currency_id : $current_currency_id;
		if ( $current_currency_id != '' ) {
			$all_currencies = isset( $wp_dp_plugin_options['wp_dp_currencies'] ) ? $wp_dp_plugin_options['wp_dp_currencies'] : array();
			$currency_obj = isset( $all_currencies[$current_currency_id] ) ? $all_currencies[$current_currency_id] : array();
			$conversion_rate = isset( $currency_obj['conversion_rate'] ) ? $currency_obj['conversion_rate'] : 1;
			$default_currency = isset( $currency_obj['currency_symbol'] ) ? $currency_obj['currency_symbol'] : '$';
			if ( $currency_converter === true ) {
				$price = $price * $conversion_rate;
			}
		}

		if ( $current_currency_id == '' ) {
			$base_currency = wp_dp_get_base_currency();
			$base_currency = wp_dp_base_currency_data( $base_currency );
			$default_currency = $base_currency['symbol'];
		}

		if ( class_exists( 'WooCommerce' ) ) {
			$woocommerce_enabled = isset( $wp_dp_plugin_options['wp_dp_use_woocommerce_gateway'] ) ? $wp_dp_plugin_options['wp_dp_use_woocommerce_gateway'] : '';
			if ( $woocommerce_enabled == 'on' ) {
				$default_currency = get_woocommerce_currency_symbol();
				$currency_pos = get_option( 'woocommerce_currency_pos' );
			}
		}

		$price = WP_DP_FUNCTIONS()->num_format( $price );
		if ( $currency_symbol == true && is_numeric( $price ) ) {
			$currency_sign = $before_currency . $default_currency . $after_currency;
			$price_str = $currency_sign . $price;
			switch ( $currency_pos ) {
				case 'left' :
					$price_str = $currency_sign . $price;
					break;
				case 'right' :
					$price_str = $price . $currency_sign;
					break;
				case 'left_space' :
					$price_str = $currency_sign . ' ' . $price;
					break;
				case 'right_space' :
					$price_str = $price . ' ' . $currency_sign;
					break;
			}
		} else {
			$price_str = $price;
		}
		return $price_str;
	}

}

if ( ! function_exists( 'wp_dp_get_order_currency' ) ) {

	function wp_dp_get_order_currency( $price = '', $currency_sign = '', $currency_position = '' ) {
		global $wp_dp_plugin_options;
		$default_currency = isset( $wp_dp_plugin_options['wp_dp_currency_sign'] ) ? $wp_dp_plugin_options['wp_dp_currency_sign'] : '$';
		$currency_pos = isset( $wp_dp_plugin_options['wp_dp_currency_position'] ) ? $wp_dp_plugin_options['wp_dp_currency_position'] : 'left';
		$currency_sign = ( $currency_sign != '' ) ? $currency_sign : $default_currency;
		$currency_position = ( $currency_position != '' ) ? $currency_position : $currency_pos;

		$price = WP_DP_FUNCTIONS()->num_format( $price );

		$price_str = $currency_sign . $price;
		switch ( $currency_position ) {
			case 'left' :
				$price_str = $currency_sign . $price;
				break;
			case 'right' :
				$price_str = $price . $currency_sign;
				break;
			case 'left_space' :
				$price_str = $currency_sign . ' ' . $price;
				break;
			case 'right_space' :
				$price_str = $price . ' ' . $currency_sign;
				break;
		}

		return $price_str;
	}

}

if ( ! function_exists( 'wp_dp_get_base_currency' ) ) {

	function wp_dp_get_base_currency() {
		global $wp_dp_plugin_options;
		$base_currency = isset( $wp_dp_plugin_options['wp_dp_currency_type'] ) ? $wp_dp_plugin_options['wp_dp_currency_type'] : 'USD';
		if ( class_exists( 'WooCommerce' ) ) {
			$woocommerce_enabled = isset( $wp_dp_plugin_options['wp_dp_use_woocommerce_gateway'] ) ? $wp_dp_plugin_options['wp_dp_use_woocommerce_gateway'] : '';
			if ( $woocommerce_enabled == 'on' ) {
				$base_currency = get_woocommerce_currency();
			}
		}
		return $base_currency;
	}

}

if ( ! function_exists( 'wp_dp_base_currency_sign' ) ) {

	function wp_dp_base_currency_sign() {
		global $wp_dp_plugin_options;
		$base_currency = wp_dp_get_base_currency();
		$base_currency = wp_dp_base_currency_data( $base_currency );
		$default_currency = $base_currency['symbol'];
		if ( class_exists( 'WooCommerce' ) ) {
			$woocommerce_enabled = isset( $wp_dp_plugin_options['wp_dp_use_woocommerce_gateway'] ) ? $wp_dp_plugin_options['wp_dp_use_woocommerce_gateway'] : '';
			if ( $woocommerce_enabled == 'on' ) {
				$default_currency = get_woocommerce_currency_symbol();
			}
		}
		return $default_currency;
	}

}

if ( ! function_exists( 'wp_dp_get_currency_position' ) ) {

	/**
	 *
	 * @return position for currency sign
	 */
	function wp_dp_get_currency_position() {
		global $wp_dp_plugin_options;
		$currency_position = isset( $wp_dp_plugin_options['wp_dp_currency_position'] ) ? $wp_dp_plugin_options['wp_dp_currency_position'] : 'left';

		if ( class_exists( 'WooCommerce' ) ) {
			$woocommerce_enabled = isset( $wp_dp_plugin_options['wp_dp_use_woocommerce_gateway'] ) ? $wp_dp_plugin_options['wp_dp_use_woocommerce_gateway'] : '';
			if ( $woocommerce_enabled == 'on' ) {
				$currency_position = get_option( 'woocommerce_currency_pos' );
			}
		}
		return $currency_position;
	}

}

if ( ! function_exists( 'wp_dp_base_currency_data' ) ) {

	function wp_dp_base_currency_data( $base_currency = 'USD' ) {
		global $wp_dp_plugin_options;
		$currencies = wp_dp_get_currencies();
		if ( isset( $currencies[$base_currency]['symbol'] ) ) {
			$base_currency = $currencies[$base_currency];
		}
		return $base_currency;
	}

}

if ( ! function_exists( 'wp_dp_get_currency_sign' ) ) {

	/**
	 *
	 * @return string for currency sign
	 */
	function wp_dp_get_currency_sign( $return_type = 'symbol' ) {
		global $wp_dp_plugin_options;
		$price_str = '';
		$default_currency = isset( $wp_dp_plugin_options['wp_dp_currency_sign'] ) ? $wp_dp_plugin_options['wp_dp_currency_sign'] : '$';
		$plugin_currency_id = isset( $wp_dp_plugin_options['wp_dp_currency_id'] ) ? $wp_dp_plugin_options['wp_dp_currency_id'] : '';
		$current_currency_id = wp_dp_get_transient_obj( 'wp_dp_user_currency' );

		$current_currency_id = ( $current_currency_id == '' ) ? $plugin_currency_id : $current_currency_id;

		if ( $current_currency_id != '' ) {
			$all_currencies = isset( $wp_dp_plugin_options['wp_dp_currencies'] ) ? $wp_dp_plugin_options['wp_dp_currencies'] : array();
			$currency_obj = isset( $all_currencies[$current_currency_id] ) ? $all_currencies[$current_currency_id] : array();
			if ( $return_type == 'code' ) {
				$default_currency = isset( $currency_obj['code'] ) ? $currency_obj['code'] : 'USD';
			} else {
				$default_currency = isset( $currency_obj['currency_symbol'] ) ? $currency_obj['currency_symbol'] : '$';
			}
		}

		if ( $current_currency_id == '' ) {
			$base_currency = wp_dp_get_base_currency();
			$base_currency = wp_dp_base_currency_data( $base_currency );
			$default_currency = $return_type == 'code' ? $base_currency['code'] : $base_currency['symbol'];
		}

		if ( class_exists( 'WooCommerce' ) ) {
			$woocommerce_enabled = isset( $wp_dp_plugin_options['wp_dp_use_woocommerce_gateway'] ) ? $wp_dp_plugin_options['wp_dp_use_woocommerce_gateway'] : '';
			if ( $woocommerce_enabled == 'on' ) {
				$default_currency = get_woocommerce_currency_symbol();
			}
		}

		return $default_currency;
	}

}


add_filter( 'icl_ls_languages', 'wpml_ls_filter' );

if ( ! function_exists( 'wpml_ls_filter' ) ) {

	function wpml_ls_filter( $languages ) {
		global $sitepress;
		if ( strpos( basename( $_SERVER['REQUEST_URI'] ), 'dashboard' ) !== false || strpos( basename( $_SERVER['REQUEST_URI'] ), 'tab' ) !== false ) {

			$cs_request_query = str_replace( '?', '', basename( $_SERVER['REQUEST_URI'] ) );

			$cs_request_query = explode( '&', $cs_request_query );

			$cs_request_quer = '';

			$query_count = 1;

			if ( is_array( $cs_request_query ) ) {
				foreach ( $cs_request_query as $quer ) {
					if ( strpos( $quer, 'page_id' ) !== false || strpos( $quer, 'lang' ) !== false ) {
						continue;
					}
					if ( $query_count == 1 ) {
						$cs_request_quer .= $quer;
					} else {
						$cs_request_quer .= '&' . $quer;
					}
					$query_count ++;
				}
			}

			if ( is_array( $languages ) && sizeof( $languages ) > 0 ) {
				foreach ( $languages as $lang_code => $language ) {
					if ( strpos( $languages[$lang_code]['url'], '?' ) !== false ) {
						$languages[$lang_code]['url'] = $languages[$lang_code]['url'] . '&' . $cs_request_quer;
					} else {
						$languages[$lang_code]['url'] = $languages[$lang_code]['url'] . '?' . $cs_request_quer;
					}
				}
			}
		}
		return $languages;
	}

}

/*
 * footer hook curriencies and languages
 */


if ( ! function_exists( 'wp_dp_before_app_in_footer_callback' ) ) {
	add_action( 'wp_dp_before_app_in_footer', 'wp_dp_before_app_in_footer_callback', 10 );

	function wp_dp_before_app_in_footer_callback( $currency = '' ) {
		global $wp_dp_plugin_options;
		$wp_dp_currency_switch = isset( $wp_dp_plugin_options['wp_dp_currency_switch'] ) ? $wp_dp_plugin_options['wp_dp_currency_switch'] : '';
		if ( isset( $wp_dp_currency_switch ) && $wp_dp_currency_switch != 'on' ) {
			return;
		}
		?>
		<div class="field-holder">
			<?php do_action( 'wp_dp_all_currencies_field' ); ?>
		</div>
		<?php
	}

}

/*
 * end footer currency and language 
 */

if ( ! function_exists( 'wp_dp_wpml_languages_callback' ) ) {

	function wp_dp_wpml_languages_callback() {
		if ( function_exists( 'icl_object_id' ) ) {
			global $wp_dp_cs_var_options;
			$wp_dp_wpml_switch = isset( $wp_dp_cs_var_options['wp_dp_cs_var_footer_lang_switch'] ) ? $wp_dp_cs_var_options['wp_dp_cs_var_footer_lang_switch'] : '';
			if ( function_exists( 'icl_object_id' ) && isset( $wp_dp_wpml_switch ) && $wp_dp_wpml_switch == 'on' ) {
				echo '<div class="field-holder wp-dp-wpml-languages">';
				do_action( 'wpml_add_language_selector' );
				echo '</div>';
			}
		}
	}

	add_action( 'wp_dp_before_contact_in_header', 'wp_dp_wpml_languages_callback', 9 );
}
if ( ! function_exists( 'wp_dp_before_contact_in_header_callback' ) ) {

	add_action( 'wp_dp_before_contact_in_header', 'wp_dp_before_contact_in_header_callback', 10 );

	function wp_dp_before_contact_in_header_callback( $currency = '' ) {
		global $wp_dp_plugin_options;
		$wp_dp_currency_switch = isset( $wp_dp_plugin_options['wp_dp_currency_switch'] ) ? $wp_dp_plugin_options['wp_dp_currency_switch'] : '';
		if ( isset( $wp_dp_currency_switch ) && $wp_dp_currency_switch != 'on' ) {
			return;
		}
		?>
		<div class="field-holder">
			<?php do_action( 'wp_dp_all_currencies_field' ); ?>
		</div>
		<?php
	}

}
if ( ! function_exists( 'wp_dp_all_currencies_calback' ) ) {

	add_action( 'wp_dp_all_currencies_field', 'wp_dp_all_currencies_calback' );

	function wp_dp_all_currencies_calback( $currency = '' ) {
		global $wp_dp_html_fields_frontend, $wp_dp_plugin_options;
		$all_currencies = isset( $wp_dp_plugin_options['wp_dp_currencies'] ) ? $wp_dp_plugin_options['wp_dp_currencies'] : array();
		$wp_dp_default_currency = isset( $wp_dp_plugin_options['wp_dp_currency_type'] ) ? $wp_dp_plugin_options['wp_dp_currency_type'] : 'USD';
		$wp_dp_currencuies = wp_dp_get_currencies();
		$base_currency = $wp_dp_currencuies[$wp_dp_default_currency];
		$currencies_array = array( '' => $base_currency['code'] . '(' . $base_currency['symbol'] . ')' );
		if ( ! empty( $all_currencies ) ) {
			foreach ( $all_currencies as $currency_key => $currencyObj ) {
				$currencies_array[$currency_key] = $currencyObj['currency_name'] . '(' . $currencyObj['currency_symbol'] . ')';
			}
		}
		$rand_currency = rand( 11111, 99999 );
		$current_currency_id = wp_dp_get_transient_obj( 'wp_dp_user_currency' );
		$wp_dp_opt_array = array(
			'name' => wp_dp_plugin_text_srt( 'wp_dp_helper_currency' ),
			'desc' => '',
			'echo' => true,
			'field_params' => array(
				'std' => $current_currency_id,
				'id' => 'currency-id' . $rand_currency,
				'classes' => 'dp-currency-field chosen-select-no-single',
				'options' => $currencies_array,
				'extra_atr' => ''
			),
		);
		$wp_dp_html_fields_frontend->wp_dp_form_select_render( $wp_dp_opt_array );

		$wp_dp_cs_inline_script = '
        jQuery(document).ready(function () {
            chosen_selectionbox();
            jQuery(document).on("change", "#wp_dp_currency-id' . $rand_currency . '", function() {
                "use strict";
                var field_value = jQuery(this).val();
                jQuery.ajax({
                    type: "POST",
                    url: wp_dp_globals.ajax_url,
                    data: "currency_id=" + field_value + "&action=wp_dp_change_user_currency",
                    dataType: "html",
                    success: function(response) {
                        location.reload(true);
                    }
                });
            });
        });';
		wp_dp_cs_inline_enqueue_script( $wp_dp_cs_inline_script, 'wp-dp-custom-inline' );
	}

}
if ( ! function_exists( 'wp_dp_all_currencies_array' ) ) {

	function wp_dp_all_currencies_array( $currency = '' ) {
		global $wp_dp_plugin_options;
		$currencies_array = array( '' => wp_dp_plugin_text_srt( 'wp_dp_helper_select_currency' ) );
		$all_currencies = isset( $wp_dp_plugin_options['wp_dp_currencies'] ) ? $wp_dp_plugin_options['wp_dp_currencies'] : array();

		if ( ! empty( $all_currencies ) ) {
			foreach ( $all_currencies as $currency_key => $currencyObj ) {
				$currencies_array[$currency_key] = $currencyObj['currency_name'];
			}
		}
		return $currencies_array;
	}

}
if ( ! function_exists( 'wp_dp_change_user_currency_callback' ) ) {

	function wp_dp_change_user_currency_callback() {
		global $wp_dp_plugin_options;
		$currency_id = wp_dp_get_input( 'currency_id' );
		if ( $currency_id != '' ) {
			wp_dp_set_transient_obj( 'wp_dp_user_currency', $currency_id );
		} else {
			wp_dp_remove_transient_obj( 'wp_dp_user_currency' );
		}
		wp_die();
	}

	add_action( 'wp_ajax_wp_dp_change_user_currency', 'wp_dp_change_user_currency_callback', 1 );
	add_action( 'wp_ajax_nopriv_wp_dp_change_user_currency', 'wp_dp_change_user_currency_callback', 1 );
}


if ( ! function_exists( 'wp_dp_current_page_url' ) ) {

	/**
	 * Return an input variable from $_SERVER if exists else default.
	 *
	 * @param	string $name name of the variable.
	 * @param string $default default value.
	 * @return string
	 */
	function wp_dp_current_page_url( $request_var = true ) {
		$pageURL = 'http';
		if ( isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on" ) {
			$pageURL .= "s";
		}
		$request_str = '';
		if ( $request_var == true ) {
			if ( isset( $_SERVER["REQUEST_URI"] ) && $_SERVER["REQUEST_URI"] != '' ) {
				$request_str = $_SERVER["REQUEST_URI"];
			}
		}
		$pageURL .= "://";
		if ( isset( $_SERVER["SERVER_PORT"] ) && $_SERVER["SERVER_PORT"] != "80" ) {
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $request_str;
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"] . $request_str;
		}
		echo esc_url( $pageURL );
		return $pageURL;
	}

}

if ( ! function_exists( 'wp_dp_company_id_form_user_id' ) ) {

	function wp_dp_company_id_form_user_id( $user_id = '' ) {
		$company_id = '';
		if ( $user_id == '' ) {
			$user_id = get_current_user_id();
		}
		if ( $user_id != '' ) {
			$company_id = get_user_meta( $user_id, 'wp_dp_company', true );
		}
		return $company_id;
	}

}

if ( ! function_exists( 'wp_dp_user_id_form_company_id' ) ) {

	function wp_dp_user_id_form_company_id( $company_id = '' ) {
		$user_id = '';

		if ( $company_id != '' ) {
			$args = array(
				'meta_query' =>
				array(
					array(
						'relation' => 'AND',
						array(
							'key' => 'wp_dp_company',
							'value' => $company_id,
							'compare' => '=',
							'type' => 'numeric'
						),
					)
				)
			);

			$users = get_users( $args );
			if ( ! empty( $users ) && is_array( $users ) )
				foreach ( $users as $user ) {
					foreach ( $user as $user_data ) {
						$user_id = isset( $user_data->ID ) ? $user_data->ID : '';
						break;
					}
				}
		}
		return $user_id;
	}

}

if ( ! function_exists( 'wp_dp_get_listing_type_item_count' ) ) {

	function wp_dp_get_listing_type_item_count( $left_filter_count_switch, $listing_type, $field_meta_key, $args_filters ) {
		if ( $left_filter_count_switch == 'yes' ) {
			$args_filters['meta_query'][] = array(
				'key' => $field_meta_key,
				'value' => $listing_type,
				'compare' => '=',
			);
			$listing_qry = new WP_Query( $args_filters );
			return $listing_qry->found_posts;
			wp_reset_postdata();
		}
	}

}


if ( ! function_exists( 'wp_dp_get_item_count' ) ) {

	function wp_dp_get_item_count( $left_filter_count_switch, $args, $count_arr, $listing_type, $listing_short_counter, $atts, $field_meta_key, $open_house = '' ) {
		if ( $left_filter_count_switch == 'yes' ) {
			global $wp_dp_shortcode_listings_frontend;


			// get all arguments from getting flters
			$left_filter_arr = array();
			$left_filter_arr = $wp_dp_shortcode_listings_frontend->get_filter_arg( $listing_type, $listing_short_counter, $field_meta_key );
			if ( $count_arr != '' ) {
				$left_filter_arr[] = $count_arr;
			}

			$search_features_filter = $wp_dp_shortcode_listings_frontend->listing_search_features_filter();
			if ( ! empty( $search_features_filter ) ) {
				$left_filter_arr[] = $search_features_filter;
			}

			$post_ids = '';
			if ( ! empty( $left_filter_arr ) ) {
				// apply all filters and get ids
				$post_ids = $wp_dp_shortcode_listings_frontend->get_listing_id_by_filter( $left_filter_arr );
			}

			if ( isset( $_REQUEST['location'] ) && $_REQUEST['location'] != '' && ! isset( $_REQUEST['loc_polygon_path'] ) ) {
				$radius = isset( $_REQUEST['radius'] ) ? $_REQUEST['radius'] : '';
				$post_ids = $wp_dp_shortcode_listings_frontend->listing_location_filter( $_REQUEST['location'], $post_ids );
				if ( empty( $post_ids ) ) {
					$post_ids = array( 0 );
				}
			}

			$all_post_ids = $post_ids;
			if ( ! empty( $all_post_ids ) ) {
				$args['post__in'] = $all_post_ids;
			}

			$restaurant_loop_obj = wp_dp_get_cached_obj( 'listing_result_cached_loop_count_obj', $args, 12, false, 'wp_query' );
			$restaurant_totnum = $restaurant_loop_obj->found_posts;
			return $restaurant_totnum;
		}
	}

}

if ( ! function_exists( 'wp_dp_get_cached_obj' ) ) {

	function wp_dp_get_cached_obj( $cache_variable, $args, $time = 12, $cache = true, $type = 'wp_query', $taxanomy_name = '' ) {
		$listing_loop_obj = '';
		if ( $cache == true ) {
			$time_string = $time * HOUR_IN_SECONDS;
			if ( $cache_variable != '' ) {
				if ( false === ( $listing_loop_obj = wp_cache_get( $cache_variable ) ) ) {
					if ( $type == 'wp_query' ) {
						$listing_loop_obj = new WP_Query( $args );
					} else if ( $type == 'get_term' ) {
						$listing_loop_obj = array();
						$terms = get_terms( $taxanomy_name, $args );
						if ( sizeof( $terms ) > 0 ) {
							foreach ( $terms as $term_data ) {
								$listing_loop_obj[] = $term_data->name;
							}
						}
					}
					wp_cache_set( $cache_variable, $listing_loop_obj, $time_string );
				}
			}
		} else {
			if ( $type == 'wp_query' ) {
				$listing_loop_obj = new WP_Query( $args );
			} else if ( $type == 'get_term' ) {
				$listing_loop_obj = array();
				$terms = get_terms( $taxanomy_name, $args );
				if ( sizeof( $terms ) > 0 ) {
					foreach ( $terms as $term_data ) {
						$listing_loop_obj[] = $term_data->name;
					}
				}
			}
		}


		return $listing_loop_obj;
	}

}
if ( ! function_exists( 'wp_dp_remove_transient_obj' ) ) {

	function wp_dp_remove_transient_obj( $transient_variable ) {
		$identifier = uniqid();
		if ( isset( $_COOKIE['identifier'] ) ) {
			$identifier = $_COOKIE['identifier'];
		}
		delete_transient( $identifier . $transient_variable );
	}

}

if ( ! function_exists( 'wp_dp_set_transient_obj' ) ) {

	function wp_dp_set_transient_obj( $transient_variable, $data_string, $time = 12 ) {
		if ( ! isset( $_COOKIE['identifier'] ) || $_COOKIE['identifier'] == '' ) {
			setcookie( 'identifier', uniqid(), time() + (86400 * 30), "/" ); // 86400 = 1 day
		}
		$result = '';
		$identifier = isset( $_COOKIE['identifier'] ) ? $_COOKIE['identifier'] : '';
		$time_string = $time * HOUR_IN_SECONDS;
		if ( $data_string != '' ) {
			$result = set_transient( $identifier . $transient_variable, $data_string, $time_string );
		}
		return $result;
	}

}

if ( ! function_exists( 'wp_dp_get_transient_obj' ) ) {

	function wp_dp_get_transient_obj( $transient_variable ) {
		$identifier = uniqid();
		if ( isset( $_COOKIE['identifier'] ) ) {
			$identifier = $_COOKIE['identifier'];
		}
		if ( false === ( $data_string = get_transient( $identifier . $transient_variable ) ) ) {
			return false;
		} else {
			return $data_string;
		}
	}

}

if ( ! function_exists( 'wp_dp_random_ads_callback' ) ) {

	function wp_dp_random_ads_callback( $banner_style ) {
		global $wpdb, $post, $wp_dp_plugin_options;
		$cs_total_banners = 1;
		if ( isset( $wp_dp_plugin_options['wp_dp_banner_title'] ) ) {
			$i = 0;
			$d = 0;
			$cs_banner_array = array();
			foreach ( $wp_dp_plugin_options['wp_dp_banner_title'] as $banner ) :

				if ( $wp_dp_plugin_options['wp_dp_banner_style'][$i] == $banner_style ) {

					$cs_banner_array[] = $i;
					$d ++;
				}
				$i ++;
			endforeach;
			if ( sizeof( $cs_banner_array ) > 0 ) {
				if ( sizeof( $cs_banner_array ) > 1 ) {
					$cs_act_size = sizeof( $cs_banner_array ) - 1;
					$cs_rand_banner = rand( 0, $cs_act_size );
				} else {
					$cs_rand_banner = 0;
				}

				$rand_banner = $cs_banner_array[$cs_rand_banner];

				echo do_shortcode( '[wp_dp_ads id="' . $wp_dp_plugin_options['wp_dp_banner_field_code_no'][$rand_banner] . '"]' );
			}
		}
	}

	add_action( 'wp_dp_random_ads', 'wp_dp_random_ads_callback', 1 );
}


if ( ! function_exists( 'wp_dp_contact_message_send' ) ) {

	function wp_dp_contact_message_send() {
		define( 'WP_USE_THEMES', false );
		global $wp_dp_plugin_options;
		$check_box = '';
		$json = array();
		$subject_name = '';

		foreach ( $_REQUEST as $keys => $values ) {
			$$keys = $values;
		}

		$wp_dp_cs_danger_html = '';
		$wp_dp_cs_success_html = '';
		$wp_dp_cs_msg_html = '';
		$bloginfo = get_bloginfo( 'name' );
		$wp_dp_cs_contactus_send = '';

		$subjecteEmail = "(" . $bloginfo . ") " . wp_dp_plugin_text_srt( 'wp_dp_helper_member_msg_received' );
		if ( '' == $wp_dp_member_email || ! preg_match( '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $wp_dp_member_email ) ) {
			$json['type'] = "error";
			$json['msg'] = $wp_dp_cs_danger_html . wp_dp_plugin_text_srt( 'wp_dp_helper_member_email_not_valid' ) . $wp_dp_cs_msg_html;
		} else {
			if ( $contact_full_name == '' ) {
				$json['type'] = 'error';
				$json['msg'] = $wp_dp_cs_danger_html . wp_dp_plugin_text_srt( 'wp_dp_helper_name_empty' ) . $wp_dp_cs_msg_html;
			} else if ( $contact_email_add == '' ) {
				$json['type'] = 'error';
				$json['msg'] = $wp_dp_cs_danger_html . wp_dp_plugin_text_srt( 'wp_dp_helper_email_empty' ) . $wp_dp_cs_msg_html;
			} else if ( ! preg_match( '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $contact_email_add ) ) {
				$json['type'] = "error";
				$json['msg'] = $wp_dp_cs_danger_html . wp_dp_plugin_text_srt( 'wp_dp_helper_email_not_valid' ) . $wp_dp_cs_msg_html;
			} else if ( $contact_message_field == '' ) {
				$json['type'] = "error";
				$json['msg'] = $wp_dp_cs_danger_html . wp_dp_plugin_text_srt( 'wp_dp_helper_msg_empty' ) . $wp_dp_cs_msg_html;
			} else {
				$message = '
			    <table width="100%" border="1">
				<tr>
				    <td width="100"><strong>' . wp_dp_plugin_text_srt( 'wp_dp_helper_name' ) . '</strong></td>
				    <td>' . esc_html( $contact_full_name ) . '</td>
				</tr>
				<tr>
				    <td><strong>' . wp_dp_plugin_text_srt( 'wp_dp_helper_email' ) . '</strong></td>
				    <td>' . esc_html( $contact_email_add ) . '</td>
				</tr>';

				if ( $contact_message_field != '' ) {
					$message .= '
					<tr>
						<td><strong>' . wp_dp_plugin_text_srt( 'wp_dp_helper_message' ) . '</strong></td>
						<td>' . esc_html( $contact_message_field ) . '</td>
					</tr>';
				}
				$wp_dp_captcha_switch = isset( $wp_dp_plugin_options['wp_dp_captcha_switch'] ) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';

				if ( $wp_dp_captcha_switch == 'on' ) {
					do_action( 'wp_dp_verify_captcha_form' );
				}

				$message .= ' <tr><td><strong>' . wp_dp_plugin_text_srt( 'wp_dp_helper_ip_address' ) . '</strong></td>
					<td>' . $_SERVER["REMOTE_ADDR"] . '</td>
				  </tr>
				</table>';

				add_filter( 'wp_mail_content_type', function () {
					return 'text/html';
				} );

				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "From: " . $contact_full_name . " <" . $contact_email_add . ">\r\n";
				$headers .= "Reply-To: " . $contact_email_add . "\r\n";

				$respose = wp_mail( $wp_dp_member_email, $subjecteEmail, $message, $headers );
				if ( $respose ) {
					$json['type'] = "success";
					$json['msg'] = $wp_dp_cs_success_html . wp_dp_plugin_text_srt( 'wp_dp_helper_sent_msg_successfully' ) . $wp_dp_cs_msg_html;
				} else {
					$json['type'] = "error";
					$json['msg'] = $wp_dp_cs_danger_html . wp_dp_plugin_text_srt( 'wp_dp_helper_msg_not_sent' ) . $wp_dp_cs_msg_html;
				};
			}
		}
		echo json_encode( $json );
		die();
	}

	//Submit member  Form Hooks
	add_action( 'wp_ajax_nopriv_wp_dp_contact_message_send', 'wp_dp_contact_message_send' );
	add_action( 'wp_ajax_wp_dp_contact_message_send', 'wp_dp_contact_message_send' );
}

if ( ! function_exists( 'wp_dp_listing_send_email_to_frnd' ) ) {

	function wp_dp_listing_send_email_to_frnd() {
		define( 'WP_USE_THEMES', false );
		global $wp_dp_plugin_options;
		$wp_dp_term_policy_switch = isset( $wp_dp_plugin_options['wp_dp_term_policy_switch'] ) ? $wp_dp_plugin_options['wp_dp_term_policy_switch'] : '';

		$check_box = '';
		$json = array();
		$subject_name = '';

		foreach ( $_REQUEST as $keys => $values ) {
			$$keys = $values;
		}

		$wp_dp_cs_danger_html = '<div class="error">';
		$wp_dp_cs_success_html = '<div class="success">';
		$wp_dp_cs_msg_html = '</div>';
		$bloginfo = get_bloginfo( 'name' );
		$wp_dp_cs_contactus_send = '';

		$subjecteEmail = "(" . $bloginfo . ") " . wp_dp_plugin_text_srt( 'wp_dp_email_to_frnd_mail_subject' ) . " '" . $contact_full_name . "'";
		if ( $contact_full_name == '' ) {
			$json['type'] = 'error';
			$json['msg'] = $wp_dp_cs_danger_html . wp_dp_plugin_text_srt( 'wp_dp_email_to_frnd_mail_name_field_error' ) . $wp_dp_cs_msg_html;
		} else if ( $contact_email_add == '' ) {
			$json['type'] = 'error';
			$json['msg'] = $wp_dp_cs_danger_html . wp_dp_plugin_text_srt( 'wp_dp_email_to_frnd_mail_email_field_empty' ) . $wp_dp_cs_msg_html;
		} else if ( ! preg_match( '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $contact_email_add ) ) {
			$json['type'] = "error";
			$json['msg'] = $wp_dp_cs_danger_html . wp_dp_plugin_text_srt( 'wp_dp_email_to_frnd_mail_email_field_error' ) . $wp_dp_cs_msg_html;
		} else if ( $contact_message_field == '' ) {
			$json['type'] = "error";
			$json['msg'] = $wp_dp_cs_danger_html . wp_dp_plugin_text_srt( 'wp_dp_email_to_frnd_mail_msg_field_error' ) . $wp_dp_cs_msg_html;
		} else if ( $wp_dp_term_policy_switch == 'on' && $member_detail_term_policy != 'on' ) {
			$json['type'] = "error";
			$json['msg'] = $wp_dp_cs_danger_html . wp_dp_plugin_text_srt( 'wp_dp_helper_read_terms_conditions' ) . $wp_dp_cs_msg_html;
		} else {
			$message = '
			<table width="100%" border="1">
			<tr>
				<td width="100"><strong>' . wp_dp_plugin_text_srt( 'wp_dp_email_to_msg_txt_name' ) . '</strong></td>
				<td>' . esc_html( $contact_full_name ) . '</td>
			</tr>
			<tr>
				<td><strong>' . wp_dp_plugin_text_srt( 'wp_dp_email_to_msg_txt_listing' ) . '</strong></td>
				<td><a href="' . get_permalink( $listing_id ) . '">' . get_the_title( $listing_id ) . '</a></td>
			</tr>';

			if ( $contact_message_field != '' ) {
				$message .= '<tr>
				<td><strong>' . wp_dp_plugin_text_srt( 'wp_dp_email_to_msg_txt_msg' ) . '</strong></td>
				<td>' . esc_html( $contact_message_field ) . '</td>
			  </tr>';
			}
			$wp_dp_captcha_switch = isset( $wp_dp_plugin_options['wp_dp_captcha_switch'] ) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';

			if ( $wp_dp_captcha_switch == 'on' ) {
				do_action( 'wp_dp_verify_captcha_form' );
			}

			$message .= ' <tr><td><strong>' . wp_dp_plugin_text_srt( 'wp_dp_helper_ip_address' ) . '</strong></td>
				<td>' . $_SERVER["REMOTE_ADDR"] . '</td>
			  </tr>
			</table>';

			add_filter( 'wp_mail_content_type', function () {
				return 'text/html';
			} );

			$headers = "MIME-Version: 1.0\r\n";
			$headers .= "From: " . $bloginfo . " <" . get_bloginfo( 'admin_email' ) . ">\r\n";
			$headers .= "Reply-To: " . get_bloginfo( 'admin_email' ) . "\r\n";

			$respose = wp_mail( $contact_email_add, $subjecteEmail, $message, $headers );
			if ( $respose ) {
				$json['type'] = "success";
				$json['msg'] = $wp_dp_cs_success_html . $wp_dp_cs_contact_succ_msg . $wp_dp_cs_msg_html;
			} else {
				$json['type'] = "error";
				$json['msg'] = $wp_dp_cs_danger_html . $wp_dp_cs_contact_error_msg . $wp_dp_cs_msg_html;
			};
		}
		echo json_encode( $json );
		die();
	}

	//Submit member  Form Hooks
	add_action( 'wp_ajax_nopriv_wp_dp_listing_send_email_to_frnd', 'wp_dp_listing_send_email_to_frnd' );
	add_action( 'wp_ajax_wp_dp_listing_send_email_to_frnd', 'wp_dp_listing_send_email_to_frnd' );
}

/*
 * Map Nearby icons with markers
 */

if ( ! function_exists( 'wp_dp_map_markers_nearby' ) ) {

	function wp_dp_map_markers_nearby( $map_dynmaic_no = '' ) {
		global $wp_dp_plugin_options, $wp_dp_form_fields_frontend;
		$wp_dp_map_markers_data = isset( $wp_dp_plugin_options['wp_dp_map_markers_data'] ) ? $wp_dp_plugin_options['wp_dp_map_markers_data'] : array();
		$distance_symbol = isset( $wp_dp_plugin_options['wp_dp_distance_measure_by'] ) ? $wp_dp_plugin_options['wp_dp_distance_measure_by'] : 'km';
		if ( isset( $wp_dp_map_markers_data['image'] ) ) :
			?>
			<div class="map-checkboxes-v2">
				<div class="swiper-container">
					<ul class="swiper-wrapper">
						<?php
						foreach ( $wp_dp_map_markers_data['image'] as $key => $row ) :
							$image = isset( $wp_dp_map_markers_data['image'][$key] ) ? $wp_dp_map_markers_data['image'][$key] : '';
							$map_image = isset( $wp_dp_map_markers_data['map_image'][$key] ) ? $wp_dp_map_markers_data['map_image'][$key] : '';
							$title = isset( $wp_dp_map_markers_data['label'][$key] ) ? $wp_dp_map_markers_data['label'][$key] : '';
							$type = isset( $wp_dp_map_markers_data['type'][$key] ) ? $wp_dp_map_markers_data['type'][$key] : '';
							$icon_val = isset( $wp_dp_map_markers_data['icon'][$key] ) ? $wp_dp_map_markers_data['icon'][$key] : '';
							$map_icon_type = isset( $wp_dp_map_markers_data['icon_type'][$key] ) && $wp_dp_map_markers_data['icon_type'][$key] != '' ? $wp_dp_map_markers_data['icon_type'][$key] : 'image';
							$icon_group = isset( $wp_dp_map_markers_data['icon_group'][$key] ) ? $wp_dp_map_markers_data['icon_group'][$key] : '';
							wp_enqueue_style( 'cs_icons_data_css_' . $icon_group );
							?>
							<li class="swiper-slide" data-placement="bottom" data-toggle="tooltip" title="<?php echo esc_html( $title ); ?>" >
								<?php
								$wp_dp_opt_array = array(
									'std' => '',
									'simple' => true,
									'cust_id' => esc_html( $type ),
									'cust_name' => esc_html( $type ),
									'classes' => 'hidden show-poi-checkbox wp_dp_show_nearby',
									'extra_atr' => ' data-label="' . esc_html( $title ) . '" data-image="' . wp_get_attachment_url( $map_image ) . '"',
								);
								$wp_dp_form_fields_frontend->wp_dp_form_checkbox_render( $wp_dp_opt_array );
								if ( $map_icon_type == 'icon' ) {
									?>
									<label class="show-nearby-point-label" for="<?php echo esc_html( $type ); ?>"><i class="<?php echo esc_html( $icon_val ); ?>"></i></label>
									<?php
								} else {
									?>
									<label class="show-nearby-point-label" for="<?php echo esc_html( $type ); ?>"><img src="<?php echo wp_get_attachment_url( $image ); ?>" alt=""></label>
									<?php
								}
								?>
							</li>
							<?php
						endforeach;
						?>
					</ul> 
				</div>
				<div class="swiper-checkbox-next"><i class="icon-arrows"></i></div>
				<div class="swiper-checkbox-prev"><i class="icon-arrows-1"></i></div>
			</div>
			<div class="map-places-radius-box">
				<div>
					<ul class="radius-val-dropdown">
						<li>
							<span class="radius-val-km dev-ch-radius-val" data-val="5">5 <?php echo esc_html( $distance_symbol ); ?></span>
							<ul>
								<li><span class="radius-val-km" data-val="1">1 <?php echo esc_html( $distance_symbol ); ?></span></li>
								<li><span class="radius-val-km" data-val="2">2 <?php echo esc_html( $distance_symbol ); ?></span></li>
								<li><span class="radius-val-km" data-val="3">3 <?php echo esc_html( $distance_symbol ); ?></span></li>
								<li><span class="radius-val-km" data-val="4">4 <?php echo esc_html( $distance_symbol ); ?></span></li>
								<li><span class="radius-val-km" data-val="5">5 <?php echo esc_html( $distance_symbol ); ?></span></li>
							</ul>
						</li>
					</ul>
					<input type="hidden" id="map-radius-input-<?php echo esc_html( $map_dynmaic_no ) ?>" value="5">
				</div>
			</div>
			<?php
		endif;
	}

}

if ( ! function_exists( 'get_user_info_array' ) ) {

	function get_user_info_array( $user_id = '' ) {
		$first_name = '';
		$last_name = '';
		$email = '';
		$phone_number = '';
		$address = '';
		if ( $user_id == '' ) {
			$user_data = wp_get_current_user();
			$user_id = $user_data->ID;
		}
		if ( is_user_logged_in() ) {
			$member_id = get_user_meta( $user_id, 'wp_dp_company', true );
			$display_name = get_the_title( $member_id );
			$user_names = explode( " ", $display_name );
			$first_name = isset( $user_names[0] ) ? $user_names[0] : '';
			$last_name = isset( $user_names[1] ) ? $user_names[1] : '';
			$phone_number = get_post_meta( $member_id, 'wp_dp_phone_number', true );
			$email = get_post_meta( $member_id, 'wp_dp_email_address', true );
			$address = get_post_meta( $member_id, 'wp_dp_post_loc_address_member', true );
		}

		$user_info = array(
			'first_name' => $first_name,
			'last_name' => $last_name,
			'phone_number' => $phone_number,
			'email' => $email,
			'display_name' => $display_name,
			'address' => $address,
		);

		return $user_info;
	}

}

/*
 * Listing counter
 */
if ( ! function_exists( 'wp_dp_listing_category_link' ) ) {

	function wp_dp_listing_category_link( $listing_type_id, $cat_slug, $type = 'complete' ) {
		global $wp_dp_plugin_options;
		$wp_dp_search_result_page = get_post_meta( $listing_type_id, 'wp_dp_search_result_page', true );
		if ( $wp_dp_search_result_page != '' ) {
			$wp_dp_search_result_page = wp_dp_wpml_lang_page_permalink( $wp_dp_search_result_page, 'page' );
		} else {
			$wp_dp_search_result_page = isset( $wp_dp_plugin_options['wp_dp_search_result_page'] ) ? $wp_dp_plugin_options['wp_dp_search_result_page'] : '';
			$wp_dp_search_result_page = wp_dp_wpml_lang_page_permalink( $wp_dp_search_result_page, 'page' );
		}

		$cate_link = '';
		if ( $wp_dp_search_result_page != '' ) {
			$listing_type_slug = wp_dp_post_slug( $listing_type_id );
			$cate_link = $wp_dp_search_result_page != '' ? add_query_arg( array( 'listing_type' => $listing_type_slug, 'listing_category' => $cat_slug, 'ajax_filter' => 'true', ), $wp_dp_search_result_page ) : 'javascript:void(0);';
		} else {
			$cate_link = 'javascript:void(0);';
		}
		if ( $type != 'complete' ) {
			return $cate_link;
		}
		return $cate_link;
	}

}

if ( ! function_exists( 'wp_dp_listing_type_link' ) ) {

	function wp_dp_listing_type_link( $listing_type_id, $type = 'complete' ) {
		global $wp_dp_plugin_options;
		$wp_dp_search_result_page = get_post_meta( $listing_type_id, 'wp_dp_search_result_page', true );
		if ( $wp_dp_search_result_page != '' ) {
			$wp_dp_search_result_page = wp_dp_wpml_lang_page_permalink( $wp_dp_search_result_page, 'page' );
		} else {
			$wp_dp_search_result_page = isset( $wp_dp_plugin_options['wp_dp_search_result_page'] ) ? $wp_dp_plugin_options['wp_dp_search_result_page'] : '';
			$wp_dp_search_result_page = wp_dp_wpml_lang_page_permalink( $wp_dp_search_result_page, 'page' );
		}

		$type_link = '';
		if ( $wp_dp_search_result_page != '' ) {
			$listing_type_slug = wp_dp_post_slug( $listing_type_id );
			$type_link = $wp_dp_search_result_page != '' ? add_query_arg( array( 'listing_type' => $listing_type_slug, 'ajax_filter' => 'true', ), $wp_dp_search_result_page ) : 'javascript:void(0);';
		} else {
			$type_link = 'javascript:void(0);';
		}
		if ( $type != 'complete' ) {
			return $type_link;
		}
		$type_link = '<a href="' . $type_link . '">' . get_the_title( $listing_type_id ) . '</a>';
		return $type_link;
	}

}

if ( ! function_exists( 'wp_dp_post_slug' ) ) {

	function wp_dp_post_slug( $post_id = '' ) {
		$post_slug = false;
		if ( $post_id != '' ) {
			$post = get_post( $post_id );
			if ( isset( $post->post_name ) && $post->post_name != '' ) {
				$post_slug = $post->post_name;
			}
		}
		return $post_slug;
	}

}

if ( ! function_exists( 'listing_gallery_first_image' ) ) {

	function listing_gallery_first_image( $args ) {

		$listing_id = '';
		$size = 'thumnail';
		$class = '';
		$return_type = '';
		$img_extra_atr = '';
		$default_image_src = esc_url( wp_dp::plugin_url() . 'assets/frontend/images/no-image9x6.jpg' );
		extract( $args );

		$gallery_image = $gallery_image_id = $gallery_image_url = '';
		if ( $listing_id != '' ) {
			$list_type_slug = get_post_meta( $listing_id, 'wp_dp_listing_type', true );
			if ( $post = get_page_by_path( $list_type_slug, OBJECT, 'listing-type' ) ) {
				$listing_type_id = $post->ID;
			} else {
				$listing_type_id = 0;
			}
			$listing_type_id = wp_dp_wpml_lang_page_id( $listing_type_id, 'listing-type' );
			$wp_dp_image_gallery_element = get_post_meta( $listing_type_id, 'wp_dp_image_gallery_element', true );

			if ( $wp_dp_image_gallery_element == 'on' ) {
				$listing_pic_num = get_post_meta( $listing_id, 'wp_dp_transaction_listing_pic_num', true );
				if ( $listing_pic_num != '' && $listing_pic_num > 0 && is_numeric( $listing_pic_num ) ) {
					$gallery_ids = get_post_meta( $listing_id, 'wp_dp_detail_page_gallery_ids', true );
					if ( is_array( $gallery_ids ) && sizeof( $gallery_ids ) > 0 ) {
						foreach ( $gallery_ids as $gallery_id ) {
							if ( $gallery_id != '' && is_numeric( $gallery_id ) && wp_get_attachment_image_src( $gallery_id ) ) {
								$gallery_image_src = wp_get_attachment_image_src( $gallery_id, $size, '', array( 'class' => $class ) );
								if ( ! empty( $gallery_image_src ) ) {
									$gallery_image = $gallery_image_src[0];
									$gallery_image_url = $gallery_image_src[0];
									$gallery_image_id = $gallery_id;
								}
								break;
							}
						}
					}
				}
			}
		}
		if ( $gallery_image == '' ) {
			$gallery_image = $default_image_src;
		}
		if ( $gallery_image != '' ) {
			$image_class = '';
			if ( $class != '' ) {
				$image_class = ' class="' . $class . '" ';
			}
			$alt_text = 'no-image';
			if ( $gallery_image_id != '' ) {
				$alt_text = get_post_meta( $gallery_image_id, '_wp_attachment_image_alt', true );
			}
			$gallery_image = '<img ' . $img_extra_atr . ' ' . $image_class . 'src="' . $gallery_image . '" alt="' . $alt_text . '"/>';
		}
		if ( $return_type == 'url' ) {
			return $gallery_image_url;
		} else {
			return $gallery_image;
		}
	}

}

if ( ! function_exists( 'wp_dp_plugin_title_sub_align' ) ) {
	/*
	 * Element structure
	 * Element title 
	 * Element sub title
	 * Element Title Alignment
	 */

	function wp_dp_plugin_title_sub_align( $title, $subtitle, $align, $title_style = '', $separator = '', $subtitle_color = '' ) {

		$element_title = isset( $title ) ? $title : '';
		$element_subtitle = isset( $subtitle ) ? $subtitle : '';
		$element_align = isset( $align ) ? $align : '';
		if ( ! empty( $title_style ) ) {
			$title_style = ' style="color:' . $title_style . ' ! important;"';
		}
		$subtitle_style = '';
		if ( ! empty( $subtitle_color ) ) {
			$subtitle_style = ' style="color:' . $subtitle_color . ' ! important;"';
		}
		$element_html = '';
		if ( ! empty( $element_title ) || ! empty( $element_subtitle ) ) {
			$element_html .= '<div class="element-title ' . $align . ' ">';
			if ( ! empty( $element_title ) ) {
				$element_html .= '<h2' . $title_style . '>' . $element_title . '</h2>';
			}
			if ( ! empty( $element_subtitle ) ) {
				$element_html .= '<p' . $subtitle_style . '>' . $element_subtitle . '</p>';
			}
			if ( ! empty( $separator ) ) {
				if ( $separator == 'classic' ) {
					$element_html .='<div class="classic-separator ' . $align . '"><span></span></div>';
				}
				if ( $separator == 'zigzag' ) {
					$element_html .='<div class="separator-zigzag ' . $align . '">
                                            <figure><img src="' . trailingslashit( wp_dp::plugin_url() ) . 'assets/images/zigzag-img1.png" alt=""/></figure>
                                        </div>';
				}
			}
			$element_html .= '</div>';
		}
		return $element_html;
	}

}

if ( ! function_exists( 'wp_dp_allow_large_joins' ) ) {

	function wp_dp_allow_large_joins() {
		global $wpdb;
		$wpdb->query( 'SET SQL_BIG_SELECTS=1' );
	}

	add_action( 'init', 'wp_dp_allow_large_joins' );
}
if ( ! function_exists( 'wp_dp_cs_inline_enqueue_script' ) ) {

	function wp_dp_cs_inline_enqueue_script( $script = '', $script_handler = 'wp-dp-custom-inline' ) {
		wp_register_script( $script_handler, plugins_url( '../assets/common/js/custom-inline.js', __FILE__ ), '', '', true );
		wp_enqueue_script( $script_handler );
		wp_add_inline_script( $script_handler, $script );
	}

}

if ( ! function_exists( 'wp_dp_listing_price' ) ) {

	function wp_dp_listing_price( $listing_id, $wp_dp_listing_price, $price_before = '', $price_after = '', $special_price_before = '<span class="special-price">', $special_price_after = '</span>', $special_price_position = 'left' ) {
		global $wp_dp_plugin_options;

		$listing_special_price = '';

		if ( $price_before == '' && $listing_special_price != '' ) {
			$price_before = '<span class="price old-price">';
		} elseif ( $price_before == '' ) {
			$price_before = '<span class="price">';
		}
		if ( $price_after == '' ) {
			$price_after = '</span>';
		}

		if ( $special_price_position == 'left' ) {

			$listing_info_price = $listing_special_price;
			$listing_info_price .= $price_before . wp_dp_get_currency( $wp_dp_listing_price, true ) . $price_after;
		} else {

			$listing_info_price = $price_before . wp_dp_get_currency( $wp_dp_listing_price, true ) . $price_after;
			$listing_info_price .= $listing_special_price;
		}
		return $listing_info_price;
	}

}

if ( ! function_exists( 'wp_dp_listing_special_price' ) ) {

	function wp_dp_listing_special_price( $listing_id = '', $special_price_before = '', $special_price_after = '', $currency_sign = false ) {
		$listing_type_id = wp_dp_listing_type_id_by_listing_id( $listing_id );
		$listing_type_special_price = get_post_meta( $listing_type_id, 'wp_dp_listing_type_special_price', true );
		$listing_type_special_price = isset( $listing_type_special_price ) ? $listing_type_special_price : 'off';
		$listing_special_price = '';
		if ( $listing_type_special_price == 'on' ) {
			$wp_dp_listing_special_price = get_post_meta( $listing_id, 'wp_dp_listing_special_price', true );
			if ( $wp_dp_listing_special_price != '' ) {
				$listing_special_price = $special_price_before . wp_dp_get_currency( $wp_dp_listing_special_price, $currency_sign ) . $special_price_after;
			}
		}
		return $listing_special_price;
	}

}

if ( ! function_exists( 'wp_dp_set_user_type_cookie_callback' ) ) {

	function wp_dp_set_user_type_cookie_callback() {
		$member_user_type = isset( $_POST['member_user_type'] ) ? $_POST['member_user_type'] : 'reseller';
		wp_dp_set_transient_obj( 'member_user_type', $member_user_type );
		echo wp_dp_cs_allow_special_char( $member_user_type );
		wp_die();
	}

}

add_action( 'wp_ajax_wp_dp_set_user_type_cookie', 'wp_dp_set_user_type_cookie_callback' );
add_action( 'wp_ajax_nopriv_wp_dp_set_user_type_cookie', 'wp_dp_set_user_type_cookie_callback' );

if ( ! function_exists( 'wp_dp_listing_type_id_by_listing_id' ) ) {

	function wp_dp_listing_type_id_by_listing_id( $listing_id = '' ) {
		$listing_type_id = 0;
		if ( $listing_id != '' ) {
			$wp_dp_listing_type_slug = get_post_meta( $listing_id, 'wp_dp_listing_type', true );
			if ( $post = get_page_by_path( $wp_dp_listing_type_slug, OBJECT, 'listing-type' ) ) {
				$listing_type_id = $post->ID;
			} else {
				$listing_type_id = 0;
			}
			$listing_type_id = wp_dp_wpml_lang_page_id( $listing_type_id, 'listing-type' );
		}
		return $listing_type_id;
	}

}

if ( ! function_exists( 'wp_dp_set_user_tab_cookie_callback' ) ) {

	function wp_dp_set_user_tab_cookie_callback() {
		$member_user_tab = isset( $_POST['member_user_tab'] ) ? $_POST['member_user_tab'] : 'login';
		wp_dp_set_transient_obj( 'member_user_tab', $member_user_tab );
		echo wp_dp_cs_allow_special_char( $member_user_tab );
		wp_die();
	}

}

add_action( 'wp_ajax_wp_dp_set_user_tab_cookie', 'wp_dp_set_user_tab_cookie_callback' );
add_action( 'wp_ajax_nopriv_wp_dp_set_user_tab_cookie', 'wp_dp_set_user_tab_cookie_callback' );

if ( ! function_exists( 'wp_dp_term_condition_form_field' ) ) {

	function wp_dp_term_condition_form_field( $field_id = 'term_policy', $field_name = 'term_policy' ) {
		global $wp_dp_plugin_options, $wp_dp_form_fields_frontend;

		$wp_dp_term_policy_switch = isset( $wp_dp_plugin_options['wp_dp_term_policy_switch'] ) ? $wp_dp_plugin_options['wp_dp_term_policy_switch'] : '';
		$wp_dp_term_policy_description = isset( $wp_dp_plugin_options['wp_dp_term_policy_description'] ) ? $wp_dp_plugin_options['wp_dp_term_policy_description'] : '';

		if ( $wp_dp_term_policy_switch == 'on' ) {
			?>
			<div class="check-box-dpind">
				<?php
				$wp_dp_opt_array = array(
					'std' => '',
					'simple' => true,
					'cust_id' => esc_html( $field_id ),
					'cust_name' => esc_html( $field_name ),
					'classes' => 'input-field',
				);
				$wp_dp_form_fields_frontend->wp_dp_form_checkbox_render( $wp_dp_opt_array );
				?>
				<label for="<?php echo esc_html( $field_id ); ?>"><?php echo htmlspecialchars_decode( $wp_dp_term_policy_description ); ?></label>
			</div> 
			<?php
		}
	}

}
if ( ! function_exists( 'wp_dp_verify_term_condition_form_field' ) ) {

	function wp_dp_verify_term_condition_form_field( $field_name = 'term_policy' ) {

		global $wp_dp_plugin_options;
		$wp_dp_term_policy_switch = isset( $wp_dp_plugin_options['wp_dp_term_policy_switch'] ) ? $wp_dp_plugin_options['wp_dp_term_policy_switch'] : '';
		if ( $wp_dp_term_policy_switch == 'on' ) {
			$term_policy_checkbox = wp_dp_get_input( $field_name, '', 'STRING' );
			if ( empty( $term_policy_checkbox ) || $term_policy_checkbox != 'on' ) {
				$response_array = array(
					'type' => 'error',
					'msg' => wp_dp_plugin_text_srt( 'wp_dp_helper_read_terms_conditions' )
				);
				echo json_encode( $response_array );
				exit();
			}
		}
	}

}


if ( ! function_exists( 'wp_dp_plublisher_listings_increment_callback' ) ) {

	function wp_dp_plublisher_listings_increment_callback( $member_id = '' ) {

		if ( $member_id == '' ) {
			$user_id = get_current_user_id();
			$member_id = get_user_meta( $user_id, 'wp_dp_company', true );
		}

		$wp_dp_num_of_listings = get_post_meta( $member_id, 'wp_dp_num_of_listings', true );
		$wp_dp_num_of_listings ++;
		update_post_meta( $member_id, 'wp_dp_num_of_listings', $wp_dp_num_of_listings );
	}

	add_action( 'wp_dp_plublisher_listings_increment', 'wp_dp_plublisher_listings_increment_callback', 10, 1 );
}

if ( ! function_exists( 'wp_dp_plublisher_listings_decrement_callback' ) ) {

	function wp_dp_plublisher_listings_decrement_callback( $member_id = '' ) {

		if ( $member_id == '' ) {
			$user_id = get_current_user_id();
			$member_id = get_user_meta( $user_id, 'wp_dp_company', true );
		}

		$wp_dp_num_of_listings = get_post_meta( $member_id, 'wp_dp_num_of_listings', true );
		if ( $wp_dp_num_of_listings > 0 ) {
			$wp_dp_num_of_listings --;
		}
		update_post_meta( $member_id, 'wp_dp_num_of_listings', $wp_dp_num_of_listings );
	}

	add_action( 'wp_dp_plublisher_listings_decrement', 'wp_dp_plublisher_listings_decrement_callback', 10, 1 );
}

if ( ! function_exists( 'wp_dp_create_listing_button_callback' ) ) {
	add_action( 'wp_dp_create_listing_button', 'wp_dp_create_listing_button_callback', 10, 1 );

	function wp_dp_create_listing_button_callback( $text_show = 'true' ) {
		global $wp_dp_plugin_options, $wp_dp_translate_options;
		$create_listing_label = wp_dp_plugin_text_srt( 'wp_dp_dp_create_list_text' );
		if ( $text_show == 'false' ) {
			$create_listing_label = '';
		}
		$create_listing_icon = '<i class="icon-plus"></i>';
		if ( $text_show == 'icon' ) {
			$create_listing_icon = '';
		}



		$wp_dp_create_listing_button = isset( $wp_dp_plugin_options['wp_dp_create_listing_button'] ) ? $wp_dp_plugin_options['wp_dp_create_listing_button'] : '';
		$wp_dp_create_listing_page = isset( $wp_dp_plugin_options['wp_dp_price_plan_page'] ) ? $wp_dp_plugin_options['wp_dp_price_plan_page'] : '';
		if ( $wp_dp_create_listing_button == 'on' && $wp_dp_create_listing_page ) {
			echo '<div class="header-add-listing input-button-loader"> ';
			$tooltip_html = '';
			if ( is_user_logged_in() ) {
				$tooltip_html = ' title="' . esc_html( $create_listing_label ) . '" data-toggle="popover" data-placement="left" data-trigger="hover" ';
				$create_listing_label = '';
			}

			if ( is_user_logged_in() && $text_show == 'icon' ) {
				$create_listing_label = wp_dp_plugin_text_srt( 'wp_dp_dp_create_list_text' );
				$tooltip_html = '';
			}

			if ( wp_is_mobile() ) {
				$create_listing_icon = '<i class="icon-plus"></i>';
				$create_listing_label = '';
			}


			echo '<a  href="' . wp_dp_wpml_lang_page_permalink( $wp_dp_create_listing_page, 'page' ) . '" class="listing-btn"' . $tooltip_html . '>' . $create_listing_icon . esc_html( $create_listing_label ) . '</a>';
			echo '</div>';
		}
		$wp_dp_cs_inline_script = '
        jQuery(document).ready(function () {
            jQuery(document).on("click", ".header-add-listing .listing-btn", function() {
                var thisObj = jQuery(".header-add-listing");
                thisObj.find("i").removeClass("icon-plus");
                thisObj.find("i").addClass("fancy-spinner");
                //wp_dp_show_loader(".header-add-listing", "", "button_loader", thisObj);
            });
        });';
		wp_dp_cs_inline_enqueue_script( $wp_dp_cs_inline_script, 'wp-dp-custom-inline' );
	}

}

if ( ! function_exists( 'wp_dp_listing_search_reset_field' ) ) {

	function wp_dp_listing_search_reset_field( $qrystr = '', $page_url = '', $exclude_field_key = '', $exclude_field_val = '' ) {
		//get all query string
		$select_exclude_field_val = (isset( $qrystr[$exclude_field_key] ) && $qrystr[$exclude_field_key] != '') ? $qrystr[$exclude_field_key] : '';
		if ( isset( $qrystr ) && $select_exclude_field_val != '' ) {
			$flag = 1;
			$qrystr = array_filter( $qrystr );
			$concat = '?';
			$url = '';
			foreach ( $qrystr as $qry_var => $qry_val ) {
				if ( $qry_val != '' && $exclude_field_key != $qry_var && $qry_var != 'view_type' && $qry_var != 'listing_arg' && $qry_var != 'listing_page' && $qry_var != 'action' ) {
					$url .= $concat . $qry_var . '=' . $qry_val;
					$concat = '&amp;';
				}
			}
			if ( $url != '' ) {
				echo '<a class="reset-field" data-toggle="tooltip" title="' . wp_dp_plugin_text_srt( 'wp_dp_listings_reset' ) . '" href="' . esc_url( $page_url . $url ) . '"><i class="icon icon-refresh3"></i></a>';
			} else {
				echo '<a class="reset-field" data-toggle="tooltip" title="' . wp_dp_plugin_text_srt( 'wp_dp_listings_reset' ) . '" href="' . esc_url( $page_url ) . '"><i class="icon icon-refresh3"></i></a>';
			}
		}
	}

}



if ( ! function_exists( 'wp_dp_gallery_photo_render' ) ) {

	function wp_dp_gallery_photo_render() {

		$listing_id = wp_dp_get_input( 'listing_id' );
		$listing_rand = wp_dp_get_input( 'listing_rand' );

		$number_of_gallery_items = get_post_meta( $listing_id, 'wp_dp_detail_page_gallery_ids', true );
		$gallery_pics_allowed = get_post_meta( $listing_id, 'wp_dp_transaction_listing_pic_num', true );
		$count_all = ( isset( $number_of_gallery_items ) && is_array( $number_of_gallery_items ) && sizeof( $number_of_gallery_items ) > 0 ) ? count( $number_of_gallery_items ) : 0;
		if ( $count_all > $gallery_pics_allowed ) {
			$count_all = $gallery_pics_allowed;
		}
		// galley prettyphotp start
		$all_img = '';
		$display_none = true;
		if ( isset( $number_of_gallery_items ) && ! empty( $number_of_gallery_items ) ) {
			foreach ( $number_of_gallery_items as $key => $value ) {
				$url_imge = wp_get_attachment_url( $value );
				$style = '';
				$tooltip = '';
				$trigger_class = '';
				if ( $display_none ) {
					$trigger_class = ' class="btnnn' . $listing_rand . '" ';
					$tooltip = '<div class="option-content"><span>' . wp_dp_plugin_text_srt( 'wp_dp_element_tooltip_icon_camera' ) . '</span></div>';
				}
				if ( ! $display_none ) {
					$style = ' style="display:none;"';
				}
				$display_none = false;
				$all_img .= '<a  ' . $trigger_class . $style . ' href="' . esc_url( $url_imge ) . '" data-rel="prettyPhoto[gal' . absint( $listing_rand ) . ']" ><i class="icon-camera6"></i><span class="capture-count">' . absint( $count_all ) . '</span>' . $tooltip . '</a>';
			}
		}

		echo json_encode( $all_img );
		// End galley prettyphotp start
		wp_die();
	}

	add_action( 'wp_ajax_wp_dp_gallery_photo_render', 'wp_dp_gallery_photo_render', 1 );
	add_action( 'wp_ajax_nopriv_wp_dp_gallery_photo_render', 'wp_dp_gallery_photo_render', 1 );
}

if ( ! function_exists( 'wp_dp_gallery_photo_render_detail' ) ) {

	function wp_dp_gallery_photo_render_detail() {

		$listing_id = wp_dp_get_input( 'listing_id' );
		$number_of_gallery_items = get_post_meta( $listing_id, 'wp_dp_detail_page_gallery_ids', true );
		// galley prettyphotp start
		$all_img = '';
		$display_none = true;
		if ( isset( $number_of_gallery_items ) && ! empty( $number_of_gallery_items ) ) {
			$counter = 1;
			foreach ( $number_of_gallery_items as $key => $value ) {
				$url_imge = wp_get_attachment_url( $value );

				$all_img .= '<a id="gal-img-' . $counter . '" style="display: none;" href="' . esc_url( $url_imge ) . '" data-rel="prettyPhoto[gal' . absint( $listing_id ) . ']" ></a>';
				$counter ++;
			}
		}

		echo json_encode( array( 'html' => $all_img ) );
		// End galley prettyphotp start
		wp_die();
	}

	add_action( 'wp_ajax_listing_detail_gallery_imgs_load', 'wp_dp_gallery_photo_render_detail' );
	add_action( 'wp_ajax_nopriv_listing_detail_gallery_imgs_load', 'wp_dp_gallery_photo_render_detail' );
}

if ( ! function_exists( 'wp_dp_find_in_multiarray' ) ) {

	function wp_dp_find_in_multiarray( $elem, $array, $field ) {

		$top = sizeof( $array );
		$k = 0;
		$new_array = array();
		for ( $i = 0; $i <= $top; $i ++ ) {
			if ( isset( $array[$i] ) ) {
				$new_array[$k] = $array[$i];
				$k ++;
			}
		}
		$array = $new_array;
		$top = sizeof( $array ) - 1;
		$bottom = 0;

		$finded_index = array();
		if ( is_array( $array ) ) {
			while ( $bottom <= $top ) {
				if ( isset( $array[$bottom][$field] ) && $array[$bottom][$field] == $elem )
					$finded_index[] = $bottom;
				else
				if ( isset( $array[$bottom][$field] ) && is_array( $array[$bottom][$field] ) )
					if ( wp_dp_find_in_multiarray( $elem, ($array[$bottom][$field] ) ) )
						$finded_index[] = $bottom;
				$bottom ++;
			}
		}
		return $finded_index;
	}

}
if ( ! function_exists( 'wp_dp_listing_hide_submit_callback' ) ) {
	add_action( 'wp_ajax_wp_dp_listing_hide_submit', 'wp_dp_listing_hide_submit_callback', 11 );

	/**
	 * Member Favourites
	 * @ added member favourites based on listing id
	 */
	function wp_dp_listing_hide_submit_callback() {

		$listing_id = wp_dp_get_input( 'listing_id' );
		$member_id = wp_dp_get_input( 'member_id' );
		$response_type = wp_dp_get_input( 'response_type' );
		$listing_short_counter = wp_dp_get_input( 'listing_short_counter' );
		$current_user = wp_get_current_user();
		$response = $member_hide_listing_list = array();

		if ( '' != $member_id ) {
			$user_company = get_user_meta( $member_id, 'wp_dp_company', true );
			$member_hide_listing_list = get_post_meta( $user_company, 'wp_dp_listing_hide_list', true );
			if ( ! empty( $member_hide_listing_list ) && wp_dp_find_in_multiarray( $listing_id, $member_hide_listing_list, 'listing_id' ) ) {
				$response['status'] = false;
			} else {
				$member_hide_listing_list = (empty( $member_hide_listing_list ) || ! is_array( $member_hide_listing_list )) ? array() : $member_hide_listing_list;
				$member_hide_listing_list[] = array(
					'listing_id' => $listing_id,
					'date' => strtotime( date( 'd-m-Y' ) ),
				);
				$response['status'] = true;
				$hide_list_html = '';
				if ( isset( $response_type ) && $response_type == 'short' ) {
					$hide_list_html .= '<a class="hide-btn wp-dp-open-signin-tab" href="javascript:void(0)" ><i class="icon-cancel2"></i> ' . wp_dp_plugin_text_srt( 'wp_dp_listing_hidden' ) . '</a>';
				} else {
					$hide_list_html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
					$hide_list_html .= '<div class="text-holder">
									<strong class="post-title"> 
										<span class="hidden-result-label">' . wp_dp_plugin_text_srt( 'wp_dp_listings_hidden_text' ) . '</span>
										<a href="' . esc_url( get_permalink( $listing_id ) ) . '">' . esc_html( get_the_title( $listing_id ) ) . '</a>                  
									</strong> 
									</div>';
					$hide_list_html .= '</div>';
				}
				$response['new_element'] = $hide_list_html;
			}
			if ( ! empty( $member_hide_listing_list ) ) {
				$member_hide_listing_list = array_values( $member_hide_listing_list );
			}
			update_post_meta( $user_company, 'wp_dp_listing_hide_list', $member_hide_listing_list );
			/*
			 * Adding Notification
			 */
			$member_name = '<a href="' . esc_url( get_the_permalink( $user_company ) ) . '">' . esc_html( get_the_title( $user_company ) ) . '</a>';
			$notification_array = array(
				'type' => 'hide_listing',
				'element_id' => $listing_id,
				'message' => force_balance_tags( $member_name . ' ' . wp_dp_plugin_text_srt( 'wp_dp_notification_hide_your_listing' ) . ' <a href="' . get_the_permalink( $listing_id ) . '">' . wp_dp_limit_text( get_the_title( $listing_id ), 3 ) . '</a>' ),
			);
			do_action( 'wp_dp_add_notification', $notification_array );
		} else {
			$response['status'] = false;
		}
		echo json_encode( $response );

		wp_die();
	}

}

if ( ! function_exists( 'wp_dp_removed_hidden_listings' ) ) {
	add_action( 'wp_ajax_wp_dp_removed_hidden_listings', 'wp_dp_removed_hidden_listings_callback', 11 );

	function wp_dp_removed_hidden_listings_callback() {

		$listing_id = wp_dp_get_input( 'listing_id' );
		$current_user = wp_get_current_user();
		$member_id = get_current_user_id();
		$user_data = get_user_info_array();
		$response = array();
		$response['status'] = false;
		if ( '' != $listing_id && '' != $member_id ) {
			$user_company = get_user_meta( $member_id, 'wp_dp_company', true );
			$member_favourites = get_post_meta( $user_company, 'wp_dp_listing_hide_list', true );
			foreach ( $member_favourites as $key => $sub_array ) {
				if ( $sub_array['listing_id'] == $listing_id ) {
					unset( $member_favourites[$key] );
					$response['status'] = true;
					$response['message'] = wp_dp_plugin_text_srt( 'wp_dp_favourite_delete_successfully' );
				}
			}
			if ( ! empty( $member_favourites ) ) {
				$member_favourites = array_values( $member_favourites );
			}
			update_post_meta( $user_company, 'wp_dp_listing_hide_list', $member_favourites );
			$response['listing_count'] = $listing_favourites;

			/*
			 * Adding Notification
			 */
			$member_name = '<a href="' . esc_url( get_the_permalink( $user_company ) ) . '">' . esc_html( get_the_title( $user_company ) ) . '</a>';
			$notification_array = array(
				'type' => 'hide_listing',
				'element_id' => $listing_id,
				'message' => force_balance_tags( $member_name . ' ' . wp_dp_plugin_text_srt( 'wp_dp_notification_removed_your_listing_from_hidden' ) . ' <a href="' . get_the_permalink( $listing_id ) . '">' . wp_dp_limit_text( get_the_title( $listing_id ), 3 ) . '</a>' ),
			);
			do_action( 'wp_dp_add_notification', $notification_array );
		}
		echo json_encode( $response );
		wp_die();
	}

}

if ( ! function_exists( 'wp_dp_hex2rgba' ) ) {

	function wp_dp_hex2rgba( $color, $opacity = false ) {

		$default = 'rgb(0,0,0)';
		//Return default if no color provided
		if ( empty( $color ) )
			return $default;

		//Sanitize $color if "#" is provided 
		if ( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		//Check if color has 6 or 3 characters and get values
		if ( strlen( $color ) == 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		//Convert hexadec to rgb
		$rgb = array_map( 'hexdec', $hex );

		//Check if opacity is set(rgba or rgb)
		if ( $opacity ) {
			if ( abs( $opacity ) > 1 )
				$opacity = 1.0;
			$output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ",", $rgb ) . ')';
		}

		//Return rgb(a) color string
		return $output;
	}

}
if ( ! function_exists( 'wp_dp_listing_type_field_listing_id' ) ) {

	function wp_dp_listing_type_field_listing_id( $listing_id = '', $field_name = '' ) {
		$wp_dp_listing_type = get_post_meta( $listing_id, 'wp_dp_listing_type', true );
		// checking review in on in listing type
		$wp_dp_listing_type = isset( $wp_dp_listing_type ) ? $wp_dp_listing_type : '';
		if ( $listing_type_post = get_page_by_path( $wp_dp_listing_type, OBJECT, 'listing-type' ) )
			$listing_type_id = $listing_type_post->ID;
		$listing_type_id = isset( $listing_type_id ) ? $listing_type_id : '';
		$listing_type_id = wp_dp_wpml_lang_page_id( $listing_type_id, 'listing-type' );
		$field_name_value = get_post_meta( $listing_type_id, $field_name, true );
		return $field_name_value;
	}

}
if ( ! function_exists( 'wp_dp_element_hide_show' ) ) {

	function wp_dp_element_hide_show( $listing_id = '', $field_name = '' ) {
		global $wp_dp_plugin_options;
		$hide_show = '';
		if ( $listing_id != '' && $field_name != '' ) {

			$wp_dp_listing_type_slug = get_post_meta( $listing_id, 'wp_dp_listing_type', true );
			if ( $post = get_page_by_path( $wp_dp_listing_type_slug, OBJECT, 'listing-type' ) ) {
				$listing_type_id = $post->ID;
			} else {
				$listing_type_id = 0;
			}
			$listing_type_id = wp_dp_wpml_lang_page_id( $listing_type_id, 'listing-type' );
			$listing_detail_view = get_post_meta( $listing_id, 'wp_dp_listing_detail_page', true );
			$listing_type_detail_view = 'detail_view5';
			$default_detail_page_view = 'detail_view5';


			if ( (($listing_detail_view == $listing_type_detail_view) || $listing_detail_view == '' ) ) {

				$selected_view = isset( $listing_type_detail_view ) && $listing_type_detail_view != '' ? $listing_type_detail_view : '';
				if ( $selected_view != '' ) {
					$hide_show = get_post_meta( $listing_type_id, 'wp_dp_' . $selected_view . '_' . $field_name, true );
				}
				if ( $hide_show == '' ) {
					$hide_show = isset( $wp_dp_plugin_options['wp_dp_' . $selected_view . '_' . $field_name] ) ? $wp_dp_plugin_options['wp_dp_' . $selected_view . '_' . $field_name] : '';
				}
			} elseif ( $listing_detail_view != '' ) {
				$hide_show = isset( $wp_dp_plugin_options['wp_dp_' . $listing_detail_view . '_' . $field_name] ) ? $wp_dp_plugin_options['wp_dp_' . $listing_detail_view . '_' . $field_name] : '';
				if ( $hide_show == '' ) {
					$hide_show = isset( $wp_dp_plugin_options['wp_dp_' . $default_detail_page_view . '_' . $field_name] ) ? $wp_dp_plugin_options['wp_dp_' . $default_detail_page_view . '_' . $field_name] : '';
				}
			} else {
				$hide_show = isset( $wp_dp_plugin_options['wp_dp_' . $default_detail_page_view . '_' . $field_name] ) ? $wp_dp_plugin_options['wp_dp_' . $default_detail_page_view . '_' . $field_name] : '';
			}
		}
		return $hide_show;
	}

}

if ( ! function_exists( 'wp_dp_wpml_lang_page_id' ) ) {

	function wp_dp_wpml_lang_page_id( $id = '', $post_type = '' ) {
		if ( function_exists( 'icl_object_id' ) && $id != '' && is_numeric( $id ) && $post_type != '' ) {
			return icl_object_id( $id, $post_type, true );
		} else {
			return $id;
		}
	}

}

if ( ! function_exists( 'wp_dp_wpml_lang_page_permalink' ) ) {

	function wp_dp_wpml_lang_page_permalink( $id = '', $post_type = '' ) {
		if ( $page_id = wp_dp_wpml_lang_page_id( $id, $post_type = '' ) ) {
			return esc_url( get_permalink( $page_id ) );
		} else {
			return false;
		}
	}

}

if ( ! function_exists( 'wp_dp_wpml_lang_code_field' ) ) {

	function wp_dp_wpml_lang_code_field( $field_type = 'hidden' ) {
		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			if ( $field_type == 'text' ) {
				return '<input type="text" name="lang" value="' . ICL_LANGUAGE_CODE . '" style="display:none;">';
			} else {
				return '<input type="hidden" name="lang" value="' . ICL_LANGUAGE_CODE . '">';
			}
		}
	}

}


if ( ! function_exists( 'wp_dp_listing_price_options' ) ) {

	function wp_dp_listing_price_options( $starting_point, $number_of_options = '50', $interval_difference = '', $first_value = '' ) {
		$output_array = array();
		$tmp = $starting_point;
		$i = 0;
		if ( $first_value != '' ) {
			$output_array[''] = $first_value;
		}
		// extra check if number of options greater than 50 then update it by 50
		if ( $number_of_options > 50 ) {
			$number_of_options = 50;
		}
		if ( ($tmp != '' && $tmp > 0) && ($number_of_options != '' && $number_of_options > 0) && ($interval_difference != '' ) ) {
			while ( $i < $number_of_options ) {
				$output_array[$tmp] = $tmp;
				$tmp = $tmp + $interval_difference;
				$i ++;
			}
		}
		return $output_array;
	}

}


if ( ! function_exists( 'wp_dp_listing_price_options_update_existing_callback' ) ) {

	add_action( 'wp_dp_plugin_db_structure_updater', 'wp_dp_listing_price_options_update_existing_callback', 10 );

	function wp_dp_listing_price_options_update_existing_callback() {
		$args_count = array(
			'posts_per_page' => "-1",
			'post_type' => 'listing-type',
			'post_status' => 'publish',
			'fields' => 'ids', // only load ids
		);
		$msg = '';
		$listing_type_loop_obj = wp_dp_get_cached_obj( 'listing_type_update_all_result_cached_loop_obj', $args_count, 12, false, 'wp_query' );
		if ( $listing_type_loop_obj->have_posts() ) {
			while ( $listing_type_loop_obj->have_posts() ) : $listing_type_loop_obj->the_post();
				global $post;
				$listing_type_id = $post;
				$wp_dp_listing_member = update_post_meta( $listing_type_id, 'wp_dp_price_max_options', '50' );
			endwhile;
		}
	}

}

/**
 * Start Function Allow Special Character
 */
if ( ! function_exists( 'wp_dp_cs_allow_special_char' ) ) {

	function wp_dp_cs_allow_special_char( $input = '' ) {
		$output = $input;
		return $output;
	}

}

if ( ! function_exists( 'wp_dp_reg_shortcodes_btn' ) ) {
	add_action( 'media_buttons', 'wp_dp_reg_shortcodes_btn', 11 );

	function wp_dp_reg_shortcodes_btn() {
		global $wp_dp_form_fields;
		$cs_rand = rand( 2342344, 95676556 );
		$shortcode_array = array();

		$shortcode_array = apply_filters( 'wp_dp_cs_shortcode_names_list_populate', $shortcode_array );

		$cs_shortcodes_list_option = array();
		$cs_shortcodes_list_option[] = "Shortcode";

		if ( ! class_exists( 'wp_dp_framework' ) ) {
			foreach ( $shortcode_array as $val ) {
				$cs_shortcodes_list_option[$val['name']] = $val['title'];
			}

			$cs_opt_array = array(
				'id' => '',
				'std' => esc_html__( "Browse", 'jobhunt' ),
				'cust_id' => '',
				'cust_name' => '',
				'classes' => 'sc_select chosen-select select-small',
				'return' => true,
				'options' => $cs_shortcodes_list_option,
				'extra_atr' => "onchange=\"wp_dp_shortocde_selection(this.value,'" . admin_url( 'admin-ajax.php' ) . "','composer-" . absint( $cs_rand ) . "')\"",
			);
			$cs_shortcodes_list = $wp_dp_form_fields->wp_dp_form_select_render( $cs_opt_array );

			$cs_shortcodes_list .= '<span id="cs-shrtcode-loader"></span>';

			echo force_balance_tags( $cs_shortcodes_list );
		}
	}

}

/*
 * On Update Plugin / Theme calling web service
 */

if ( class_exists( 'wp_dp_framework' ) ) {

	if ( ! function_exists( 'wp_dp_plugin_db_structure_updater_demo_callback' ) ) {

		function wp_dp_plugin_db_structure_updater_demo_callback() {
			$remote_api_url = REMOTE_API_URL;
			$envato_purchase_code_verification = get_option( 'item_purchase_code_verification' );
			$selected_demo = isset( $_POST['theme_demo'] ) ? $_POST['theme_demo'] : '';
			$envato_email = isset( $_POST['envato_email'] ) ? $_POST['envato_email'] : '';
			$envato_purchase_code_verification['selected_demo'] = $selected_demo;
			$envato_purchase_code_verification['envato_email_address'] = $envato_email;
			update_option( 'item_purchase_code_verification', $envato_purchase_code_verification );
			$theme_obj = wp_get_theme();
			$demo_data = array(
				'theme_puchase_code' => isset( $envato_purchase_code_verification['item_puchase_code'] )? $envato_purchase_code_verification['item_puchase_code'] : '',
				'theme_name' => $theme_obj->get( 'Name' ),
				'theme_id' => isset( $envato_purchase_code_verification['item_id'] )? $envato_purchase_code_verification['item_id'] : '',
				'user_email' => $envato_email,
				'theme_demo' => $selected_demo,
				'theme_version' => $theme_obj->get( 'Version' ),
				'site_url' => site_url(),
				'supported_until' => isset( $envato_purchase_code_verification['supported_until'] ) ? $envato_purchase_code_verification['supported_until'] : '',
				'action' => 'add_to_active_themes',
			);
			$url = $remote_api_url;
			$response = wp_remote_post( $url, array( 'body' => $demo_data ) );
			check_theme_is_active();
		}

		add_action( 'wp_dp_plugin_db_structure_updater', 'wp_dp_plugin_db_structure_updater_demo_callback', 10 );
	}
}

if ( ! function_exists( 'wp_dp_plugin_template_redirect' ) ) {
	add_action( 'template_redirect', 'wp_dp_plugin_template_redirect' );

	function wp_dp_plugin_template_redirect() {
		global $wp_query, $wp_dp_plugin_options;
		if ( class_exists( 'wp_dp' ) ) {

			if ( is_archive() || is_singular( array( 'listing-type' ) ) ) {
				if ( is_tax( 'listing-category' ) || is_tax( 'listing-tag' ) || is_singular( array( 'listing-type' ) ) ) {
					$wp_dp_search_result_page = isset( $wp_dp_plugin_options['wp_dp_search_result_page'] ) ? $wp_dp_plugin_options['wp_dp_search_result_page'] : '';
					$wp_dp_search_result_page = get_permalink( $wp_dp_search_result_page );
					if ( is_tax( 'listing-tag' ) ) {
						$cate_link = $wp_dp_search_result_page != '' ? add_query_arg( array( 'search_title' => esc_html( single_tag_title( '', false ) ), 'listing_type' => 'all', 'ajax_filter' => 'true' ), esc_url( $wp_dp_search_result_page ) ) : 'javascript:void(0);';
					}
					if ( is_tax( 'listing-category' ) ) {
						$queried_object = get_queried_object();
						$cate_slug = isset( $queried_object->slug ) ? $queried_object->slug : '';
						// get parent listing type
						global $wp_dp_listing_type_meta;
						$listing_type_slug = $wp_dp_listing_type_meta->listing_type_by_category_slug( $cate_slug );
						$cate_link = $wp_dp_search_result_page != '' ? add_query_arg( array( 'listing_category' => $cate_slug, 'listing_type' => $listing_type_slug, 'ajax_filter' => 'true' ), esc_url( $wp_dp_search_result_page ) ) : 'javascript:void(0);';
					}
					if ( is_singular( array( 'listing-type' ) ) ) {
						$type_post = get_post( get_the_ID() );
						$type_slug = isset( $type_post->post_name ) ? $type_post->post_name : '';
						$cate_link = $wp_dp_search_result_page != '' ? add_query_arg( array( 'listing_type' => $type_slug, 'ajax_filter' => 'true' ), esc_url( $wp_dp_search_result_page ) ) : 'javascript:void(0);';
					}
					$cate_link = str_replace( " ", "+", $cate_link );
					wp_redirect( $cate_link );
				}
			}
		}
	}

}

if ( ! function_exists( 'wp_dp_date_custom_format' ) ) {

	function wp_dp_date_custom_format( $post_date = '', $formate = 'M, d Y' ) {
		$current_date = strtotime( date( 'Y-m-d H:i:s' ) );
		$interval = $current_date - strtotime( $post_date );
		$minutes = round( $interval / 60 );
		$days = floor( $interval / (60 * 60 * 24) );
		if ( $minutes <= 2 ) {
			return wp_dp_plugin_text_srt( 'wp_dp_member_message_date_just_now' );
		} elseif ( $days < 1 ) {
			return human_time_diff( strtotime( $post_date ), current_time( 'timestamp' ) ) . ' ' . wp_dp_plugin_text_srt( 'wp_dp_reviews_ago_txt' );
		} else {
			return date_i18n( $formate, strtotime( $post_date ) );
		}
	}

}


if ( ! function_exists( 'wp_dp_check_promotion_status' ) ) {

	function wp_dp_check_promotion_status( $listing_id, $promotion_slug ) {
		$return_value = false;
		$promotion_value = get_post_meta( $listing_id, 'wp_dp_promotion_' . $promotion_slug, true );
		if ( $promotion_value == 'on' ) {
			$promotions = get_post_meta( $listing_id, 'wp_dp_promotions', true );
			$expiry_date = isset( $promotions[$promotion_slug]['expiry_date'] ) ? $promotions[$promotion_slug]['expiry_date'] : 'unlimitted';
			if ( isset( $promotions[$promotion_slug]['expiry_date'] ) && $promotions[$promotion_slug]['expiry_date'] == 'unlimitted' ) {
				$return_value = 'on';
			} else {
				$today_date = date( 'Y-m-d' );
				if ( $expiry_date >= $today_date ) {
					$return_value = 'on';
				}
			}
		}
		return $return_value;
	}

}

if ( ! function_exists( 'wp_dp_check_listing_urgent_status' ) ) {

	function wp_dp_check_listing_urgent_status( $listing_id, $promotion_slug ) {
		$return_value = get_post_meta( $listing_id, 'wp_dp_listing_' . $promotion_slug, true );
		return $return_value;
	}

}

if ( ! function_exists( 'wp_dp_search_header_callback' ) ) {

	add_action( 'wp_dp_search_header', 'wp_dp_search_header_callback', 10, 1 );

	function wp_dp_search_header_callback( $button_style = '' ) {

		global $wp_dp_plugin_options, $wp_dp_form_fields_frontend, $wp_dp_post_listing_types, $wp_dp_shortcode_listings_frontend, $wp_dp_split_map_search_fields;
		wp_enqueue_script( 'wp_dp_location_autocomplete_js' );
		wp_enqueue_script( 'wp_dp_listing_autocomplete_js' );
		$loc_title_counter = rand( 111, 99999 );

		$wp_dp_search_result_page = isset( $wp_dp_plugin_options['wp_dp_search_result_page'] ) ? $wp_dp_plugin_options['wp_dp_search_result_page'] : '';
		$wp_dp_search_result_page = ( $wp_dp_search_result_page != '' ) ? wp_dp_wpml_lang_page_permalink( $wp_dp_search_result_page, 'page' ) : '';
		?>
		<div class="header-search-form">
			<form method="GET" action="<?php echo esc_url( $wp_dp_search_result_page ); ?>" name="header_search_form">
				<div class="field-holder search-input with-search-country">
					<div class="search-header-holder">
						<?php
						wp_get_listing_autocomplete_field_home2();
						$wp_dp_select_display = 1;
						wp_dp_get_custom_locations_listing_filter( '<div id="wp-dp-header-location-select-holder" class="search-country" style="display:' . wp_dp_allow_special_char( $wp_dp_select_display ) . '"><div class="select-holder">', '</div></div>', false, $loc_title_counter, 'filter', '', '', 'homev2' );
						?>
					</div>
					<?php
					if ( $button_style == 'text' ) {
						$button_text = wp_dp_plugin_text_srt( 'wp_dp_currency_search' );
					} else {
						$button_text = '<i class="icon-search4"></i>';
					}
					?>
					<button type="submit"><?php echo wp_dp_allow_special_char( $button_text ); ?></button>
				</div>
			</form>
		</div>   
		<script>
		    var timer = 0;
		    function wp_dp_submit_header_form() {
		        clearTimeout(timer);
		        timer = setTimeout(function () {
		            document.header_search_form.submit();
		        }, 1000);
		    }
		</script>
		<?php
	}

}
if ( ! function_exists( 'wp_get_listing_autocomplete_field' ) ) {

	function wp_get_listing_autocomplete_field( $counter = '', $position = '' ) {
		global $wp_dp_form_fields_frontend, $wp_dp_post_listing_types, $wp_dp_plugin_options;
		if ( $counter == '' ) {
			$counter = rand( 123456789, 987654321 );
		}
		?>

		<div id="search-listing-field-<?php echo absint( $counter ); ?>" class="search-listing-field">
			<i class="icon-dp-magnifying-glass-browser"></i>
			<strong><?php echo wp_dp_plugin_text_srt( 'wp_dp_listing_search_flter_find' ); ?></strong>
			<?php
			$search_title = isset( $_REQUEST['search_title'] ) ? $_REQUEST['search_title'] : '';
			$wp_dp_form_fields_frontend->wp_dp_form_text_render(
					array(
						'cust_name' => 'search_title',
						'classes' => 'input-field listing_autocomplete_on',
						'std' => $search_title,
						'extra_atr' => ' autocomplete="off" placeholder=" ' . wp_dp_plugin_text_srt( 'wp_dp_map_search_what_looking' ) . '" data-id="' . $counter . '" data-position="' . $position . '"',
					)
			);
			?>
			<div id="listing-autocomplete-loader-<?php echo absint( $counter ); ?>" class="listing_autocomplete_on_loader"></div>
			<div id="listing-autocomplete-result-<?php echo absint( $counter ); ?>" class="listing_autocomplete_data_dev listing-autocomplete-result" style="display:none;">
				<ul>
					<?php
					// load all types  
					$listing_type_loop_obj = $wp_dp_post_listing_types->wp_dp_all_types_by_s( '' );
					if ( $listing_type_loop_obj->have_posts() ) {
						while ( $listing_type_loop_obj->have_posts() ) : $listing_type_loop_obj->the_post();
							global $post;
							$listing_type_id = $post;
							$listing_type_title = get_the_title( $listing_type_id );
							$listing_type_url = wp_dp_listing_type_link( $listing_type_id, 'link_only' );
							$listing_type_icon = get_post_meta( $listing_type_id, 'wp_dp_listing_type_icon', true );
							$listing_type_icon = isset( $listing_type_icon[0] ) ? $listing_type_icon[0] : $listing_type_icon;
							if ( $listing_type_icon != '' ) {
								$type_selected_icon_group = get_post_meta( $listing_type_id, 'wp_dp_listing_type_icon_group', true );
								$type_selected_icon_group = isset( $type_selected_icon_group[0] ) ? $type_selected_icon_group[0] : 'default';
								wp_enqueue_style( 'cs_icons_data_css_' . $type_selected_icon_group );
								$listing_type_icon = '<i class="' . $listing_type_icon . '"></i>';
							}
							echo '<li>' . $listing_type_icon . '<a class="ac-listing-record-dev" data-id="' . $counter . '" data-position="' . $position . '" data-listingtitle="' . esc_html( $listing_type_title ) . '" data-listingurl="' . esc_url( $listing_type_url ) . '">' . esc_html( $listing_type_title ) . '</a></li>';
						endwhile;
					}
					wp_reset_postdata();
					?>
				</ul>
			</div>
		</div> 
		<?php
	}

}


if ( ! function_exists( 'wp_get_listing_autocomplete_field_home2' ) ) {

	function wp_get_listing_autocomplete_field_home2( $counter = '', $position = '' ) {
		global $wp_dp_form_fields_frontend, $wp_dp_post_listing_types, $wp_dp_plugin_options;
		if ( $counter == '' ) {
			$counter = rand( 123456789, 987654321 );
		}
		?>

		<div id="search-listing-field-<?php echo absint( $counter ); ?>" class="search-listing-field">

			<strong><i class="icon-dp-magnifying-glass-browser"></i> <?php echo wp_dp_plugin_text_srt( 'wp_dp_listing_search_flter_find' ); ?></strong>
			<?php
			$search_title = isset( $_REQUEST['search_title'] ) ? $_REQUEST['search_title'] : '';
			echo '<label>';
			$wp_dp_form_fields_frontend->wp_dp_form_text_render(
					array(
						'cust_name' => 'search_title',
						'classes' => 'input-field listing_autocomplete_on',
						'std' => $search_title,
						'extra_atr' => ' autocomplete="off" placeholder=" ' . wp_dp_plugin_text_srt( 'wp_dp_map_search_what_looking' ) . '" data-id="' . $counter . '" data-position="' . $position . '"',
					)
			);
			echo '</label>';
			?>
			<div id="listing-autocomplete-loader-<?php echo absint( $counter ); ?>" class="listing_autocomplete_on_loader"></div>
			<div id="listing-autocomplete-result-<?php echo absint( $counter ); ?>" class="listing_autocomplete_data_dev listing-autocomplete-result" style="display:none;">
				<ul class="listings">
					<?php
					// load all types  
					$listing_type_loop_obj = $wp_dp_post_listing_types->wp_dp_all_types_by_s( '' );
					if ( $listing_type_loop_obj->have_posts() ) {
						while ( $listing_type_loop_obj->have_posts() ) : $listing_type_loop_obj->the_post();
							global $post;
							$listing_type_id = $post;
							$listing_type_title = get_the_title( $listing_type_id );
							$listing_type_url = wp_dp_listing_type_link( $listing_type_id, 'link_only' );
							$listing_type_icon = get_post_meta( $listing_type_id, 'wp_dp_listing_type_icon', true );
							$listing_type_icon = isset( $listing_type_icon[0] ) ? $listing_type_icon[0] : $listing_type_icon;
							if ( $listing_type_icon != '' ) {
								$type_selected_icon_group = get_post_meta( $listing_type_id, 'wp_dp_listing_type_icon_group', true );
								$type_selected_icon_group = isset( $type_selected_icon_group[0] ) ? $type_selected_icon_group[0] : 'default';
								wp_enqueue_style( 'cs_icons_data_css_' . $type_selected_icon_group );
								$listing_type_icon = '<i class="' . $listing_type_icon . '"></i>';
							}
							echo '<li>' . $listing_type_icon . '<a class="ac-listing-record-dev" data-id="' . $counter . '" data-position="' . $position . '" data-listingtitle="' . esc_html( $listing_type_title ) . '" data-listingurl="' . esc_url( $listing_type_url ) . '">' . esc_html( $listing_type_title ) . '</a></li>';
						endwhile;
					}
					wp_reset_postdata();
					?>
				</ul>
			</div>
		</div> 
		<?php
	}

}
if ( ! function_exists( 'wp_dp_listing_autocomplete_data_callback' ) ) {

	function wp_dp_listing_autocomplete_data_callback() {
		global $wp_dp_plugin_options, $wp_dp_post_listing_types, $wp_dp_shortcode_listings_frontend, $post;
		$submitted_text = wp_dp_get_input( 'autocomplete_field_txt', '', 'STRING' );

        $listing_type_id = $post;
		$counter = wp_dp_get_input( 'counter', '', 'STRING' );
		$position = wp_dp_get_input( 'position', '', 'STRING' );

		$html = '<ul>';
		// get from types
		$type_rec = 2;
		if ( $submitted_text == '' ) {
			$type_rec = '-1';
		}
		$listing_type_loop_obj = $wp_dp_post_listing_types->wp_dp_all_types_by_s( $submitted_text, $type_rec );
		$not_found = true;
		if ( $listing_type_loop_obj->have_posts() ) {
			$not_found = false;
			while ( $listing_type_loop_obj->have_posts() ) : $listing_type_loop_obj->the_post();

				$listing_type_title = get_the_title( $listing_type_id );
				$listing_type_url = wp_dp_listing_type_link( $listing_type_id, 'link_only' );
				$listing_type_icon = get_post_meta( $listing_type_id, 'wp_dp_listing_type_icon', true );
				$listing_type_icon = isset( $listing_type_icon[0] ) ? $listing_type_icon[0] : $listing_type_icon;
				if ( $listing_type_icon != '' ) {
					$type_selected_icon_group = get_post_meta( $listing_type_id, 'wp_dp_listing_type_icon_group', true );
					$type_selected_icon_group = isset( $type_selected_icon_group[0] ) ? $type_selected_icon_group[0] : 'default';
					wp_enqueue_style( 'cs_icons_data_css_' . $type_selected_icon_group );
					$listing_type_icon = '<i class="' . $listing_type_icon . '"></i>';
				}
				$html .= '<li>' . $listing_type_icon . '<a class="ac-listing-record-dev" data-id="' . $counter . '" data-position="' . $position . '" data-listingtitle="' . esc_html( $listing_type_title ) . '" data-listingurl="' . esc_url( $listing_type_url ) . '">' . esc_html( $listing_type_title ) . '</a></li>';
			endwhile;
		}
		if ( $submitted_text != '' ) {
			$terms = $wp_dp_post_listing_types->wp_dp_all_categories_by_s( $submitted_text, 3 );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				$not_found = false;
				foreach ( $terms as $term ) {
					$term_name = $term->name;
					$term_slug = $term->slug;
					$term_url = wp_dp_listing_category_link( $listing_type_id, $term_slug, 'link_only' );
					$term_icon = get_term_meta( $term->term_id, 'wp_dp_listing_taxonomy_icon', true );
					$term_icon_group = get_term_meta( $term->term_id, 'wp_dp_listing_taxonomy_icon_group', true );
					$term__icon = '';
					if ( $term_icon != '' ) {
						wp_enqueue_style( 'cs_icons_data_css_' . $term_icon_group );
						$term__icon = '<i class="' . $term_icon . '"></i> ';
					}
					$html .= '<li>' . $term__icon . '<a class="ac-listing-record-dev" data-id="' . $counter . '" data-position="' . $position . '" data-listingtitle="' . esc_html( $term_name ) . '" data-listingurl="' . esc_url( $term_url ) . '">' . $term_name . '</li>';
				}
			}
			$listing_loop_obj = $wp_dp_shortcode_listings_frontend->wp_dp_all_listings_by_s( $submitted_text, 5 );

			if ( $listing_loop_obj->have_posts() ) {
				$not_found = false;
				while ( $listing_loop_obj->have_posts() ) : $listing_loop_obj->the_post();
					global $post;
					$listing_id = $post;
					$listing_title = get_the_title( $listing_id );
					$listing_address = get_post_meta( $listing_id, 'wp_dp_post_loc_address_listing', true );

					if ( $listing_address != '' ) {
						$listing_address = "\n" . '<span class="address">' . $listing_address . '</span>' . "\n";
					}
					if ( function_exists( 'listing_gallery_first_image' ) ) {
						$size = 'wp_dp_media_6';
						$gallery_image_args = array(
							'listing_id' => $listing_id,
							'size' => $size,
							'class' => 'img-grid',
							'default_image_src' => esc_url( wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg' ),
							'img_extra_atr' => 'itemprop="image"',
						);
						$listing_gallery_first_image = '<div class="img-holder"><figure>' . listing_gallery_first_image( $gallery_image_args ) . '</figure></div>';
					}
					$html .= '<li> ' . $listing_gallery_first_image . ' <div class="text-holder"><a class="ac-listing-record-dev" data-id="' . $counter . '" data-position="' . $position . '" data-listingtitle="' . esc_html( $listing_title ) . '" data-listingurl="' . esc_url( get_permalink( $listing_id ) ) . '">' . esc_html( $listing_title ) . '</a>' . $listing_address . '</div></li>';
				endwhile;
			}
		}
		if ( $not_found != false ) {
			$html .= '<li> ' . wp_dp_plugin_text_srt( 'wp_dp_listing_search_flter_find_not_found' ) . '</li>';
		}
		$html .= '</ul>';
		echo wp_dp_cs_allow_special_char( $html );

		wp_die();
	}

	add_action( 'wp_ajax_wp_dp_listing_autocomplete_data', 'wp_dp_listing_autocomplete_data_callback', 1 );
	add_action( 'wp_ajax_nopriv_wp_dp_listing_autocomplete_data', 'wp_dp_listing_autocomplete_data_callback', 1 );
}

/*
 * Member detail listing 
 */

if ( ! function_exists( 'wp_dp_get_member_listings_callback' ) ) {

	function wp_dp_get_member_listings_callback() {
		global $wp_dp_plugin_options, $wp_dp_post_listing_types;
		$current_page = wp_dp_get_input( 'current_page' );
		$listing_member_id = wp_dp_get_input( 'listing_member_id' );
		$listing_per_page = wp_dp_get_input( 'listing_per_page' );
		$offset = $current_page * $listing_per_page + 1;


		$list_args = array(
			'posts_per_page' => $listing_per_page,
			'post_type' => 'listings',
			'offset' => $offset,
			'post_status' => 'publish',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'wp_dp_listing_member',
					'value' => $listing_member_id,
					'compare' => '=',
				),
			),
		);
		$custom_query = new WP_Query( $list_args );

		$listing_location_options = 'city,country';
		if ( $listing_location_options != '' ) {
			$listing_location_options = explode( ',', $listing_location_options );
		}
		$wp_dp_custom_title_length = isset( $wp_dp_plugin_options['wp_dp_custom_title_length'] ) ? $wp_dp_plugin_options['wp_dp_custom_title_length'] : 5;
		$wp_dp_custom_content_length = isset( $wp_dp_plugin_options['wp_dp_custom_content_length'] ) ? $wp_dp_plugin_options['wp_dp_custom_content_length'] : 20;


		$wp_dp_listings_title_limit = $wp_dp_custom_title_length;
		while ( $custom_query->have_posts() ) : $custom_query->the_post();
			global $post;
			$listing_id = $post->ID;
			$Wp_dp_Locations = new Wp_dp_Locations();
			$get_listing_location = $Wp_dp_Locations->get_element_listing_location( $listing_id, $listing_location_options );
			$listing_random_id = rand( 1111111, 9999999 );
			$wp_dp_listing_member = get_post_meta( $listing_id, 'wp_dp_listing_member', true );
			$wp_dp_cover_image_id = get_post_meta( $listing_id, 'wp_dp_cover_image', true );
			$wp_dp_cover_image = wp_get_attachment_url( $wp_dp_cover_image_id );
			$wp_dp_post_loc_address_listing = get_post_meta( $listing_id, 'wp_dp_post_loc_address_listing', true );
			$wp_dp_listing_price_options = get_post_meta( $listing_id, 'wp_dp_listing_price_options', true );
			$wp_dp_listing_type = get_post_meta( $listing_id, 'wp_dp_listing_type', true );
			$wp_dp_listing_type_cus_fields = $wp_dp_post_listing_types->wp_dp_types_custom_fields_array( $wp_dp_listing_type );
			$wp_dp_listing_is_urgent = wp_dp_check_promotion_status( $listing_id, 'urgent' );
			$wp_dp_listing_gallery_ids = get_post_meta( $listing_id, 'wp_dp_detail_page_gallery_ids', true );
			$gallery_pics_allowed = get_post_meta( $listing_id, 'wp_dp_transaction_listing_pic_num', true );
			$wp_dp_listing_is_top_cat = wp_dp_check_promotion_status( $listing_id, 'top-categories' );
			$wp_dp_listing_price = '';
			if ( $wp_dp_listing_price_options == 'price' ) {
				$wp_dp_listing_price = get_post_meta( $listing_id, 'wp_dp_listing_price', true );
			} else if ( $wp_dp_listing_price_options == 'on-call' ) {
				$wp_dp_listing_price = wp_dp_plugin_text_srt( 'wp_dp_listings_price_on_request' );
			}
			$count_all = ( isset( $wp_dp_listing_gallery_ids ) && is_array( $wp_dp_listing_gallery_ids ) && sizeof( $wp_dp_listing_gallery_ids ) > 0 ) ? count( $wp_dp_listing_gallery_ids ) : 0;
			if ( $count_all > $gallery_pics_allowed ) {
				$count_all = $gallery_pics_allowed;
			}
			$gallery_image_count = $count_all;
			// checking review in on in listing type
			$wp_dp_listing_type = isset( $wp_dp_listing_type ) ? $wp_dp_listing_type : '';
			if ( $listing_type_post = get_page_by_path( $wp_dp_listing_type, OBJECT, 'listing-type' ) )
				$listing_type_id = $listing_type_post->ID;
			$listing_type_id = isset( $listing_type_id ) ? $listing_type_id : '';
			$listing_type_id = wp_dp_wpml_lang_page_id( $listing_type_id, 'listing-type' );

			$wp_dp_listing_type_price_switch = get_post_meta( $listing_type_id, 'wp_dp_listing_type_price', true );

			/*
			 * Video and gallery from type 
			 */
			$wp_dp_video_element_switch = get_post_meta( $listing_type_id, 'wp_dp_video_element', true );
			$wp_dp_image_gallery_switch = get_post_meta( $listing_type_id, 'wp_dp_image_gallery_element', true );
			$wp_dp_video_element_switch = isset( $wp_dp_video_element_switch ) ? $wp_dp_video_element_switch : '';
			$wp_dp_image_gallery_switch = isset( $wp_dp_image_gallery_switch ) ? $wp_dp_image_gallery_switch : '';
			/*
			 * End Video and gallery 
			 */
			// get all categories
			$wp_dp_cate = '';
			$wp_dp_cate_str = '';
			$wp_dp_listing_category = get_post_meta( $listing_id, 'wp_dp_listing_category', true );
			$wp_dp_post_loc_address_listing = get_post_meta( $listing_id, 'wp_dp_post_loc_address_listing', true );
			if ( ! empty( $wp_dp_listing_category ) && is_array( $wp_dp_listing_category ) ) {
				$comma_flag = 0;
				foreach ( $wp_dp_listing_category as $cate_slug => $cat_val ) {
					$wp_dp_cate = get_term_by( 'slug', $cat_val, 'listing-category' );
					if ( ! empty( $wp_dp_cate ) ) {
						$cate_link = wp_dp_listing_category_link( $listing_type_id, $cat_val );
						if ( $comma_flag != 0 ) {
							$wp_dp_cate_str .= ', ';
						}
						$term_icon = get_term_meta( $wp_dp_cate->term_id, 'wp_dp_listing_taxonomy_icon', true );
						$term_icon_group = get_term_meta( $wp_dp_cate->term_id, 'wp_dp_listing_taxonomy_icon_group', true );

						$term__icon = '';
						if ( $term_icon != '' ) {
							wp_enqueue_style( 'cs_icons_data_css_' . $term_icon_group );
							$term__icon = '<i class="' . $term_icon . '"></i> ';
						}
						$wp_dp_cate_str .= $term__icon . '<a href="' . $cate_link . '">' . $wp_dp_cate->name . '</a>';
						$comma_flag ++;
					}
				}
			}
			$columns_class = 'col-lg-4 col-md-4 col-sm-6 col-xs-12';
			$main_class = 'listing-grid';
			$listings_excerpt_length = $wp_dp_custom_content_length;
			?>

			<div class="listing-row<?php echo esc_html( $columns_class ); ?>">
				<div class="<?php echo esc_html( $main_class ); ?> <?php echo esc_html( $pro_is_compare ); ?> list-top-category advance-grid" itemscope itemtype="<?php echo force_balance_tags( $http_request ); ?>schema.org/Product">
					<div class="listing-inner">
						<div class="img-holder image-loaded">
							<figure>
								<a href="<?php the_permalink(); ?>">
									<?php
									if ( function_exists( 'listing_gallery_first_image' ) ) {
										$size = 'wp_dp_media_10';
										$gallery_image_args = array(
											'listing_id' => $listing_id,
											'size' => $size,
											'class' => 'img-grid',
											'default_image_src' => esc_url( wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg' ),
											'img_extra_atr' => 'itemprop="image"',
										);
										$listing_gallery_first_image = listing_gallery_first_image( $gallery_image_args );
										echo wp_dp_cs_allow_special_char( $listing_gallery_first_image );
									}
									?>
								</a>
								<figcaption>
									<?php
									wp_dp_listing_sold_html( $listing_id );

									if ( $wp_dp_listing_is_urgent == 'on' ) {
										?>
										<span class="featured"><?php echo wp_dp_plugin_text_srt( 'wp_dp_listing_urgent' ); ?></span>
										<?php
									}
									?>

									<div class="caption-inner">
										<ul class="dp-listing-options">
											<?php
											$figcaption_div = true;
											$book_mark_args = array(
												'before_html' => '<li class="listing-like-opt"><div class="option-holder">',
												'after_html' => '</div></li>',
												'before_label' => '',
												'after_label' => '',
												'before_icon' => 'icon-heart-o',
												'after_icon' => 'icon-heart5',
												'show_tooltip' => 'no',
											);
											do_action( 'wp_dp_listing_favourite_button_frontend', $listing_id, $book_mark_args, $figcaption_div );
											?>

											<li class="listing-view-opt">
												<div class="quick-view">
													<a data-listings_excerpt_length="<?php echo absint( $listings_excerpt_length ) ?>" data-rand="<?php echo absint( $listing_random_id ) ?>" data-id="<?php echo absint( $listing_id ) ?>" class="wp-dp-quick-view-dev" data-toggle="modal" data-target="#quick-listing" href="javascript:void(0);">
														<i class="icon-full-screen"></i>
													</a>
												</div>
											</li>
										</ul> 
										<?php
										// check today status
										$today_status = false;
										$today_status = apply_filters( 'wp_dp_today_status_element_html', $listing_id );
                                                                                if( $today_status != 'opening_hours_off'){
										if ( $today_status == true ) {
											?>
											<span class="btn-open"><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_open_now' ); ?></span>
										<?php } else {
											?>
											<span class="btn-close"><?php echo wp_dp_plugin_text_srt( 'wp_dp_member_close_now' ); ?></span>
                                                                                <?php }}  ?>
									</div>

								</figcaption>
							</figure>

						</div> 
						<div class="text-holder">
							<?php
							$member_image_id = get_post_meta( $wp_dp_listing_member, 'wp_dp_profile_image', true );
							$member_image = wp_get_attachment_image_src( $member_image_id, 'thumbnail' );

							if ( $member_image == '' || FALSE == get_post_status( $wp_dp_listing_member ) ) {
								$member_image[0] = esc_url( wp_dp::plugin_url() . 'assets/frontend/images/member-no-image.jpg' );
							}
							if ( $member_image != '' && get_post_status( $wp_dp_listing_member ) ) {
								?>
								<div class="thumb-img">
									<figure>
										<a href="<?php echo get_the_permalink( $wp_dp_listing_member ); ?>">
											<img src="<?php echo esc_url( $member_image[0] ); ?>" alt="" >
										</a>
									</figure>
								</div>
								<?php
							}


							if ( $listing_enquiry_switch == 'yes' ) {
								$prop_enquir_args = array(
									'enquiry_label' => wp_dp_plugin_text_srt( 'wp_dp_enquiry_detail_enquiry' ),
								);

								do_action( 'wp_dp_enquiry_check_frontend_button', $listing_id, $prop_enquir_args );
							}

							$title__ = get_the_title( $listing_id );
							if ( isset( $title__ ) && ! empty( $title__ ) ) {
								?>
								<div class="post-title">
									<h4 itemprop="name">
										<?php if ( $wp_dp_listing_is_top_cat == 'on' ) {
											?>
											<a href="javascript:void(0)" class="wp-google-add" ><?php echo wp_dp_plugin_text_srt( 'wp_dp_listing_top_category' ); ?></a> 
										<?php } ?>
										<a href="<?php echo esc_url( get_permalink( $listing_id ) ); ?>"><?php echo esc_html( wp_dp_limit_text( $title__, $wp_dp_listings_title_limit ) ) ?><i class="icon-verified-circle"></i></a></h4>
								</div>
								<?php
							}



							if ( $wp_dp_listing_type_price_switch == 'on' && $wp_dp_listing_price != '' ) {
								?>
								<span class="listing-price" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags( $http_request ); ?>schema.org/Offer">
									<?php
									if ( $wp_dp_listing_price_options == 'on-call' ) {
										echo force_balance_tags( $wp_dp_listing_price );
									} else {
										$listing_info_price = wp_dp_listing_price( $listing_id, $wp_dp_listing_price, '<span class="price from-price" content="' . $wp_dp_listing_price . '" itemprop="price">', '<em>' . wp_dp_plugin_text_srt( 'wp_dp_search_fields_date_from' ) . '</em></span>' );
										$wp_dp_get_currency_sign = wp_dp_get_currency_sign( 'code' );
										echo '<span itemprop="priceCurrency" style="display:none;" content="' . $wp_dp_get_currency_sign . '"></span>';
										echo force_balance_tags( $listing_info_price );
									}
									?>
								</span>
								<?php
							}
							?>
							<?php if ( isset( $get_listing_location ) && ! empty( $get_listing_location ) ) { ?>
								<div class="grid-location"><span><?php echo esc_html( implode( ', ', $get_listing_location ) ); ?></span></div>
								<?php
							}
							$list_content = get_post_field( 'post_content', $listing_id );

							if ( isset( $list_content ) && ! empty( $list_content ) ) {
								?>
								<p><?php
									echo wp_dp_limit_text( $list_content, $listings_excerpt_length );
									?>
								</p>
							<?php } ?>
							<div class="grid-rating">
								<?php
								if ( $wp_dp_cate_str != '' ) {
									?>
									<ul class="post-category"><li><?php echo wp_dp_cs_allow_special_char( $wp_dp_cate_str ); ?></li></ul>  
									<?php
								}
								?>
								<div class="rating-star">
									<?php do_action( 'wp_rem_reviews_listing_ui', $listing_id, 'rat-num' ); ?> 
								</div>

							</div> 

						</div>

					</div>
				</div>
			</div>

			<?php
			wp_reset_postdata();
		endwhile;

		wp_die();
	}

	add_action( 'wp_ajax_wp_dp_get_member_listings', 'wp_dp_get_member_listings_callback', 1 );
	add_action( 'wp_ajax_nopriv_wp_dp_get_member_listings', 'wp_dp_get_member_listings_callback', 1 );
}





if ( ! function_exists( 'wp_dp_page_breadcrumb' ) ) {

	function wp_dp_page_breadcrumb( $page_iddd ) {


		global $post;
		/* === OPTIONS === */
		$wp_dp_cs_var_current_page = wp_dp_plugin_text_srt( 'wp_dp_cs_var_current_page' );
		$wp_dp_cs_var_home = wp_dp_plugin_text_srt( 'wp_dp_cs_var_home' );
		$text['home'] = esc_html( $wp_dp_cs_var_home ); // text for the 'Home' link
		$delimiter = ''; // delimiter between crumbs
		$before = '<li class="active">'; // tag before the current crumb
		$after = '</li>'; // tag after the current crumb
		/* === END OF OPTIONS === */
		$current_page = $wp_dp_cs_var_current_page;
		$homeLink = home_url() . '/';
		$linkBefore = '<li>';
		$linkAfter = '</li>';
		$linkAttr = '';
		$text_color = '';
		$wp_dp_cs_border = '';

		$linkhome = $linkBefore . '<a href="' . esc_url( $homeLink ) . '">' . $text['home'] . '</a>' . $linkAfter;
		$wp_dp_cs_border_style = $wp_dp_cs_border != '' ? ' style="border-top: 1px solid ' . $wp_dp_cs_border . ';"' : '';

		echo '<ul class="breadcrumbs">' . $linkhome;

		if ( is_single() ) {
			$post_type = get_post_type_object( get_post_type() );
			if ( ! empty( $post_type ) && is_single() ) {
				$before = '<li class="active">';
				echo wp_dp_cs_allow_special_char( $before ) . $post_type->labels->singular_name . wp_dp_cs_allow_special_char( $after );
			}
		}

		echo wp_dp_cs_allow_special_char( $delimiter . $before . get_the_title( $page_iddd ) . $after );

		echo '</ul>';
	}

}

function wp_dp_limit_text( $text = '', $length = '', $read_more = '' ) {
	global $wp_dp_plugin_options;
	if ( empty( $text ) ) {
		return;
	}
	
	$wp_dp_displaying_text_style = isset( $wp_dp_plugin_options['wp_dp_displaying_text_style'] ) ? $wp_dp_plugin_options['wp_dp_displaying_text_style'] : '';
	$length = isset( $length ) && $length != '' && $length > 0 ? $length : '10';
	if ( empty( $read_more ) ) {
		$read_more = '...';
	}
	$limited_string = '';
	if ( isset( $wp_dp_displaying_text_style ) && $wp_dp_displaying_text_style == 'words' ) {
		$limited_string = wp_trim_words( $text, $length, $read_more );
	} elseif ( isset( $wp_dp_displaying_text_style ) && $wp_dp_displaying_text_style == 'char' ) {
		$string_length = strlen( $text );
		if ( $string_length <= $length ) {
			$read_more = '';
		}
		$visible_string = substr( $text, 0, $length );
		$limited_string = balanceTags( $visible_string ) . $read_more;
	} else {
		$limited_string = $text;
	}
	return $limited_string;
}

/**
 * Set date time format
 * @date: date value
 * return: boolean/string
 */
function wp_remsecond_date_format( $date_format = 'F j, Y', $post_id = '', $time_in_seconds = '' ) {
	$converted_date = '';
	if ( (isset( $post_id ) && $post_id != '') && (isset( $time_in_seconds ) && $time_in_seconds != '') ) {
		$converted_date = date_i18n( $date_format, $time_in_seconds );
	}
	return $converted_date;
}
