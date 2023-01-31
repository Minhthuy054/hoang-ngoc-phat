<?php
/**
 * File Type: Features Element
 */
if ( ! class_exists('wp_dp_features_element') ) {

    class wp_dp_features_element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_dp_features_element_html', array( $this, 'wp_dp_features_element_html_callback' ), 11, 2);
            add_action('wp_dp_nearby_element_html', array( $this, 'wp_dp_nearby_element_html_callback' ), 11, 1);
            add_action('wp_dp_listing_video_html', array( $this, 'wp_dp_listing_video_html_callback' ), 11, 1);
            add_action('wp_dp_listing_vitual_tour_html', array( $this, 'wp_dp_listing_vitual_tour_html_callback' ), 11, 1);
            add_action('wp_dp_listing_apartment_html', array( $this, 'wp_dp_listing_apartment_html_callback' ), 11, 1);
        }

        public function wp_dp_listing_apartment_html_callback($post_id) {

            $wp_dp_apartments = get_post_meta($post_id, 'wp_dp_apartment', true);

            $listing_type_slug = get_post_meta($post_id, 'wp_dp_listing_type', true);
            $listing_type = get_page_by_path($listing_type_slug, OBJECT, 'listing-type');
            $listing_type_id = isset($listing_type->ID) ? $listing_type->ID : 0;
            
            $element_title = get_post_meta( $listing_type_id, 'wp_dp_listing_type_title_apartment_for_sale', true);

            $type_appartments = get_post_meta($listing_type_id, 'wp_dp_appartments_options_element', true);

            if ( $type_appartments != 'on' ) {
                return false;
            }

            if ( is_array($wp_dp_apartments) && $wp_dp_apartments != '' ) {
                ?>
                <div id="apartments" class="apartment-list">
                    <div class="element-title">
                        <h3><?php echo esc_html( $element_title ); ?></h3>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead style="background:#e7e7e7; color:#fff;">
                                <tr>
                                    <th><?php echo wp_dp_plugin_text_srt('wp_dp_features_plot'); ?></th>
                                    <th><?php echo wp_dp_plugin_text_srt('wp_dp_features_beds'); ?></th>
                                    <th><?php echo wp_dp_plugin_text_srt('wp_dp_features_price_from'); ?></th>
                                    <th><?php echo wp_dp_plugin_text_srt('wp_dp_features_floor'); ?></th>
                                    <th><?php echo wp_dp_plugin_text_srt('wp_dp_features_building_address'); ?></th>
                                    <th><?php echo wp_dp_plugin_text_srt('wp_dp_features_availability'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ( isset($wp_dp_apartments) && is_array($wp_dp_apartments) ) {
                                    foreach ( $wp_dp_apartments as $apartment_key => $apartment_value ) {
                                        $apartment_plot = isset($apartment_value['apartment_plot']) ? $apartment_value['apartment_plot'] : '';
                                        $apartment_beds = isset($apartment_value['apartment_beds']) ? $apartment_value['apartment_beds'] : '';
                                        $apartment_price_from = isset($apartment_value['apartment_price_from']) ? $apartment_value['apartment_price_from'] : '';
                                        $apartment_floor = isset($apartment_value['apartment_floor']) ? $apartment_value['apartment_floor'] : '';
                                        $apartment_address = isset($apartment_value['apartment_address']) ? $apartment_value['apartment_address'] : '';
                                        $apartment_availability = isset($apartment_value['apartment_availability']) ? $apartment_value['apartment_availability'] : '';
                                        $apartment_link = isset($apartment_value['apartment_link']) ? $apartment_value['apartment_link'] : '';
                                        if ( $apartment_availability == 'available' ) {
                                            $apartment_availability = wp_dp_plugin_text_srt('wp_dp_features_available');
                                        } elseif ( $apartment_availability == 'unavailable' ) {
                                            $apartment_availability = wp_dp_plugin_text_srt('wp_dp_features_not_available');
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo esc_html($apartment_plot); ?></td>
                                            <td><?php echo esc_html($apartment_beds); ?></td>
                                            <td><?php echo esc_html(wp_dp_get_currency_sign() . $apartment_price_from); ?></td>
                                            <td><?php echo esc_html($apartment_floor); ?></td>
                                            <td><?php echo esc_html($apartment_address); ?></td>
                                            <td><?php echo ucfirst($apartment_availability); ?></td>
                                            <td><a class="view-btn" href="<?php echo esc_url($apartment_link); ?>"><?php echo wp_dp_plugin_text_srt('wp_dp_features_view'); ?></a></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
            }
        }

        public function wp_dp_listing_video_html_callback($post_id) {

            $wp_dp_listing_video = get_post_meta($post_id, 'wp_dp_listing_video', true);
            $wp_dp_listing_video_package_switch = get_post_meta($post_id, 'wp_dp_transaction_listing_video', true);
            $wp_dp_listing_image = get_post_meta($post_id, 'wp_dp_listing_image', true);
            $img_url = wp_get_attachment_url($wp_dp_listing_image);
            $img_url = isset($img_url) ? $img_url : '';

            $listing_type_slug = get_post_meta($post_id, 'wp_dp_listing_type', true);
            $listing_type = get_page_by_path($listing_type_slug, OBJECT, 'listing-type');
            $listing_type_id = isset($listing_type->ID) ? $listing_type->ID : 0;

            $type_video = get_post_meta($listing_type_id, 'wp_dp_video_element', true);

            if ( $type_video != 'on' || $wp_dp_listing_video_package_switch != 'on' ) {
                return false;
            }
            $element_title = get_post_meta( $listing_type_id, 'wp_dp_listing_type_title_video', true);

            if ( isset($wp_dp_listing_video) && $wp_dp_listing_video != '' ) {
                ?>
                <div id="video" class="video-holder">
                    <div class="element-title">
                        <h3><?php echo esc_html( $element_title ); ?></h3>
                    </div>
                    <div class="video-fit-holder">
                        <?php
                        if ( function_exists('listing_gallery_first_image') ) {
                            $gallery_video_image_args = array(
                                'listing_id' => $post_id,
                                'size' => 'wp_dp_media_9',
                                'class' => '',
                                'return_type' => 'url',
                                'default_image_src' => esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image9x6.jpg')
                            );
                            $listing_gallery_first_image_url = listing_gallery_first_image($gallery_video_image_args);
                        }
                        $image_style = ( $listing_gallery_first_image_url != '' ) ? ' style="background-image:url(' . esc_url($listing_gallery_first_image_url) . ')"' : '';
                        ?>
                        <div class="img-holder"<?php echo wp_dp_allow_special_char($image_style); ?>>
                            <span class="play-btn"> 
                                <a id="play-video" data-id="<?php echo esc_html($post_id); ?>" class="video-btn" href="javascript:void(0);"><i class="icon-play_arrow"></i></a> 
                            </span>
                        </div>
                    </div>
                </div>		
                <?php
            }
        }

        public function wp_dp_listing_vitual_tour_html_callback($post_id = '') {

            $listing_type_slug = get_post_meta($post_id, 'wp_dp_listing_type', true);
            $listing_type = get_page_by_path($listing_type_slug, OBJECT, 'listing-type');
            $listing_type_id = isset($listing_type->ID) ? $listing_type->ID : 0;

            $type_virtual_tour = get_post_meta($listing_type_id, 'wp_dp_virtual_tour_element', true);

            if ( $type_virtual_tour != 'on' ) {
                return false;
            }

            $wp_dp_listing_virtual_tour = get_post_meta($post_id, 'wp_dp_listing_virtual_tour', true);
            if ( isset($wp_dp_listing_virtual_tour) && $wp_dp_listing_virtual_tour != '' ) {
                $wp_dp_cs_allowed_tags = array(
                    'iframe' => array( 'src' => array(), 'height' => array(), 'width' => array(), 'frameborder' => array(), 'allowfullscreen' => array() ),
                );
                $is_iframe_virtual_tour = wp_kses(htmlspecialchars_decode(force_balance_tags($wp_dp_listing_virtual_tour)), $wp_dp_cs_allowed_tags);
                $element_title = get_post_meta( $listing_type_id, 'wp_dp_listing_type_title_virtual_tour', true);
                if ( $is_iframe_virtual_tour ) {
                    ?>
                    <div id="vitual-tour" class="video-holder virtual-tour-holder">
                        <div class="element-title">
                            <h3><?php echo esc_html( $element_title ); ?></h3>
                        </div>
                        <div class="video-fit-holder virtual-tour">
                    <?php echo html_entity_decode($wp_dp_listing_virtual_tour); ?>
                        </div>
                    </div>		
                    <?php
                }
            }
        }

        /*
         * Output features html for frontend on listing detail page.
         */

        public function wp_dp_features_element_html_callback($post_id) {

            $listing_type_slug = get_post_meta($post_id, 'wp_dp_listing_type', true);
            $listing_type = get_page_by_path($listing_type_slug, OBJECT, 'listing-type');
            $listing_type_id = isset($listing_type->ID) ? $listing_type->ID : 0;

            $type_features_element = get_post_meta($listing_type_id, 'wp_dp_features_element', true);
            $element_title = get_post_meta( $listing_type_id, 'wp_dp_listing_type_title_amenities', true);
            

            if ( $type_features_element != 'on' ) {
                return false;
            }

            $features_list = get_post_meta($post_id, 'wp_dp_listing_feature_list', true);

            $type_features_not_selected = get_post_meta($listing_type_id, 'wp_dp_enable_not_selected', true);
            $type_features = get_post_meta($listing_type_id, 'feature_lables', true);

            if ( ! empty($features_list) || $type_features_not_selected == 'on' ) {
                ?>
                <div id="features" class="features-holder">
                    <div class="element-title">
                        <h3><?php echo esc_html( $element_title ); ?></h3>
                    </div>
                    <?php
                }

                $wp_dp_feature_icon = get_post_meta($listing_type_id, 'wp_dp_feature_icon', true);
                $wp_dp_feature_icon_group = get_post_meta($listing_type_id, 'wp_dp_feature_icon_group', true);
                $type_features_not_selected = get_post_meta($listing_type_id, 'wp_dp_enable_not_selected', true);
                if ( $type_features_not_selected != 'on' ) {
                    if ( isset($features_list) && ! empty($features_list) ) {
                        $html = '';
						$html .= '<div class="row">';
							$html .= '<ul class="features-list">';
							foreach ( $features_list as $feature_data ) {
								$icon = '';
								$feature_exploded = explode("_icon", $feature_data);
								$features_data_name = isset($feature_exploded[0]) ? $feature_exploded[0] : '';
								$feature_icon = isset($feature_exploded[1]) ? $feature_exploded[1] : '';
								$feature_icon_group = isset($feature_exploded[2]) ? $feature_exploded[2] : 'default';
								if ( $feature_icon != '' && $feature_icon != ' ' ) {
									wp_enqueue_style('cs_icons_data_css_' . $feature_icon_group);
									$feature_icon = ' <i class="' . $feature_icon . '"></i>';
								}
								$html .= '<li class="col-lg-4 col-md-4 col-sm-6 col-xs-12">' . $feature_icon . $features_data_name . '</li>';
							}
							$html .= '</ul>';
						$html .= '</div>';
                        echo force_balance_tags($html);
                    }
                } else {
                    $html = '';
                    if ( isset($type_features) && ! empty($type_features) ) {
						$html .= '<div class="row">';
							$html .= '<ul class="category-list">';
							foreach ( $type_features as $key => $label ) {
								$feature_icon = isset($wp_dp_feature_icon[$key]) ? $wp_dp_feature_icon[$key] : '';
								$feature_icon_group = isset($wp_dp_feature_icon_group[$key]) ? $wp_dp_feature_icon_group[$key] : 'default';
								if ( $feature_icon != '' && $feature_icon != ' ' ) {
									wp_enqueue_style('cs_icons_data_css_' . $feature_icon_group);
									$feature_icon = ' <i class="' . $feature_icon . '"></i>';
								}
								$icon = '';
								if ( isset($features_list) && ! empty($features_list) ) {
									foreach ( $features_list as $feature_data ) {
										$feature_exploded = explode("_icon", $feature_data);

										$features_data_name = isset($feature_exploded[0]) ? $feature_exploded[0] : '';

										if ( $features_data_name == $label ) {
											$icon = 'icon-check';
											break;
										} else {
											$icon = 'icon-cross';
										}
									}
								}
								$html .= '<li class="col-lg-4 col-md-4 col-sm-6 col-xs-12">' . $feature_icon . '<i class="' . $icon . '"></i></i>' . $label . '</li>';
							}
							$html .= '</ul>';
						$html .= '</div>';
                        echo force_balance_tags($html);
                    }
                }
                if ( ! empty($features_list) || $type_features_not_selected == 'on' ) {
                    ?>
                </div>
                <?php
            }
        }

        public function wp_dp_nearby_element_html_callback($post_id) {

            $near_by_array = get_post_meta($post_id, 'wp_dp_near_by', true);
            $wp_dp_post_loc_address_listing = get_post_meta($post_id, 'wp_dp_post_loc_address_listing', true);
            $html = '';

            if ( isset($near_by_array) && $near_by_array != '' && is_array($near_by_array) ) {
                $html .= '<div id="maps-nearby" class="location-holder">';
                $html .= '
				<div class="element-title">
					<h3>' . wp_dp_plugin_text_srt('wp_dp_features_what_near_by') . '</h3>
				</div>';
                $html .= '<ul class="location-list">';
                $count = 1;
                foreach ( $near_by_array as $key => $near_by ) {
                    $nearby_image_url = wp_get_attachment_url($near_by['near_by_image']);

                    $html .= '
					<script type="text/javascript">
						var source = 0;
						var destination = 0;
						source = "' . $wp_dp_post_loc_address_listing . '";
						destination = "' . $near_by["near_by_description"] . '";
						var service = new google.maps.DistanceMatrixService();
						service.getDistanceMatrix({
							origins: [source],
							destinations: [destination],
							travelMode: google.maps.TravelMode.DRIVING,
							unitSystem: google.maps.UnitSystem.METRIC,
							avoidHighways: false,
							avoidTolls: false
						}, function (response, status) {
							console.log(response);
							if (status == google.maps.DistanceMatrixStatus.OK && response.rows[0].elements[0].status != "ZERO_RESULTS") {
								var distance = response.rows[0].elements[0].distance.text;
								var duration = response.rows[0].elements[0].duration.text;
								total = distance.replace("km" ," ");
								total = total.replace("," ,"");
								totald = total*.621371;
							   jQuery("#add-miles' . $count . '").html((totald.toFixed(2)) + " ' . wp_dp_plugin_text_srt('wp_dp_features_miles_away') . '");
							} else {
								jQuery("#add-miles' . $count . '").html("' . wp_dp_plugin_text_srt('wp_dp_features_unable_find_distance') . '");
							}
						});
					</script>';

                    $html.='<li class="col-lg-6 col-md-6 col-sm-6 col-xs-12">';
                    if ( isset($nearby_image_url) && $nearby_image_url != '' ) {
                        $html.='<img src="' . esc_url($nearby_image_url) . '" alt="" />';
                    }
                    if ( isset($near_by['near_by_title']) && $near_by['near_by_title'] != '' ) {
                        $html .=$near_by['near_by_title'];
                    }
                    if ( isset($near_by['near_by_description']) && $near_by['near_by_description'] != '' ) {
                        $html .='<span id="add-miles' . $count . '"></span>';
                    }
                    $html .='</li>';
                    $count ++;
                }
                $html .='</ul>';
                $html .='</div>';
                echo force_balance_tags($html);
            }
        }

    }

    global $wp_dp_features_element;
    $wp_dp_features_element = new wp_dp_features_element();
}


if ( ! function_exists('wp_dp_detail_video_render') ) {

    function wp_dp_detail_video_render() {

        wp_enqueue_script('fitvids');
        $listing_id = wp_dp_get_input('listing_id');
        $listing_id = isset($listing_id) ? $listing_id : '';
        $wp_dp_listing_video = get_post_meta($listing_id, 'wp_dp_listing_video', true);
        
        $wp_dp_listing_video = isset($wp_dp_listing_video) ? $wp_dp_listing_video : '';
        $url_parts = parse_url($wp_dp_listing_video);
        $player_string_exists = strpos($wp_dp_listing_video, 'vimeo.com');
        if ( $player_string_exists === true ) {
            $video_path_exists = strpos($url_parts['path'], 'video');
            if ( $video_path_exists === true ) {
                $wp_dp_listing_video = 'https://player.vimeo.com/' . $url_parts['path'] . '';
            } else {
                $wp_dp_listing_video = 'https://player.vimeo.com/video/' . $url_parts['path'] . '';
            }
        }
        $video_data = '';
        $video_data = wp_oembed_get(($wp_dp_listing_video), array( 'height' => 52 ));
        $string_exists = '';
        $string_exists = strpos($video_data, 'vimeo');
        if ( $string_exists === false ) {
            $video_data = str_replace('feature=oembed', 'feature=oembed&autoplay=1', $video_data); // youtube autoplay
        } else {
            $doc = new DOMDocument();
            $doc->loadHTML($video_data);
            $src = $doc->getElementsByTagName('iframe')->item(0)->getAttribute('src');
            $video_data = str_replace($src, $src . '?autoplay=1', $video_data); // vimeo autoplay
        }
        echo json_encode($video_data);
        wp_die();
    }

    add_action('wp_ajax_wp_dp_detail_video_render', 'wp_dp_detail_video_render', 1);
    add_action('wp_ajax_nopriv_wp_dp_detail_video_render', 'wp_dp_detail_video_render', 1);
}