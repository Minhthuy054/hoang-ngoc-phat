<?php
/**
 * File Type: Search Fields
 */
if ( ! class_exists( 'Wp_dp_Search_Fields' ) ) {

	class Wp_dp_Search_Fields {

		/**
		 * Start construct Functions
		 */
		public function __construct() {
			add_action( 'wp_dp_listing_type_fields', array( $this, 'wp_dp_listing_type_fields_callback' ) );
			add_action( 'wp_dp_listing_type_features', array( $this, 'wp_dp_listing_type_features' ), 10, 2 );
			add_action( 'wp_ajax_wp_dp_listing_type_search_fields', array( $this, 'wp_dp_listing_type_search_fields_callback' ) );
			add_action( 'wp_ajax_nopriv_wp_dp_listing_type_search_fields', array( $this, 'wp_dp_listing_type_search_fields_callback' ) );
			add_action( 'wp_ajax_wp_dp_listing_type_cate_fields', array( $this, 'wp_dp_listing_type_cate_fields_callback' ) );
			add_action( 'wp_ajax_nopriv_wp_dp_listing_type_cate_fields', array( $this, 'wp_dp_listing_type_cate_fields_callback' ) );
		}

		public function wp_dp_listing_type_features( $list_type_slug = '', $listing_short_counter = '') {
			global $wp_dp_form_fields_frontend;
			$listing_type_id = $this->wp_dp_listing_type_id_by_slug( $list_type_slug );
			$listing_type_features = get_post_meta( $listing_type_id, 'feature_lables', true );
			$feature_icons = get_post_meta( $listing_type_id, 'wp_dp_feature_icon', true );

			if ( is_array( $listing_type_features ) && sizeof( $listing_type_features ) > 0 ) {
				?>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 features-list">
					<strong class="advance-trigger"><?php echo wp_dp_plugin_text_srt( 'wp_dp_search_fields_other_features' ); ?></strong>
					<div class="clearfix"></div>
					<div class="features-field-expand">
						<ul id="search-features-list" class="search-features-list">
							<?php
							$feature_counter = 1;
							$html = '';
							foreach ( $listing_type_features as $feat_key => $feature ) {
								if ( isset( $feature ) && ! empty( $feature ) ) {
									$feature_name = isset( $feature ) ? $feature : '';
									$feature_icon = isset( $feature_icons[$feat_key] ) ? $feature_icons[$feat_key] : '';
									$count_feature_listings = $this->listing_search_features_listings( $list_type_slug, $feature_name );
									$html .= '<li class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                        <div class="checkbox">';
									$html .=$wp_dp_form_fields_frontend->wp_dp_form_text_render(
											array(
												'std' => esc_attr( $feature_name ),
												'cust_id' => 'check-' . $feature_counter . '',
												'cust_name' => '',
												'return' => true,
												'classes' => 'search-feature-' . $listing_short_counter . '',
												'cust_type' => 'checkbox',
												'prefix_on' => false,
											)
									);
									$html .= '    <label for="check-' . $feature_counter . '">' . $feature_name . ' (' . $count_feature_listings . ')</label>
                                                        </div>
                                                </li>';
									$feature_counter ++;
								}
							}
							$html .= '<li class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="display:none;">';
							$html .= $wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
									array(
										'return' => true,
										'cust_name' => 'features',
										'cust_id' => 'search-listing-features-' . $listing_short_counter . '',
										'std' => '',
									)
							);
							$html .= '</li>';
							echo wp_dp_allow_special_char( $html );
							?>
							<script type="text/javascript">
								jQuery(document).ready(function () {
									'use strict'
									var $checkboxes = jQuery("input[type=checkbox].search-feature-<?php echo esc_html( $listing_short_counter ); ?>");
									var features = $checkboxes.filter(':checked').map(function () {
										return this.value;
									}).get().join(',');
									jQuery('#search-listing-features-<?php echo esc_html( $listing_short_counter ); ?>').val(features);
								});
								var $checkboxes = jQuery("input[type=checkbox].search-feature-<?php echo esc_html( $listing_short_counter ); ?>");
								$checkboxes.on('change', function () {
									var features = $checkboxes.filter(':checked').map(function () {
										return this.value;
									}).get().join(',');
									jQuery('#search-listing-features-<?php echo esc_html( $listing_short_counter ); ?>').val(features);
								});
							</script>
						</ul>
					</div>
				</div>
				<?php
			}
		}

		function listing_search_features_listings( $listing_type_slug = '', $feature_name = '' ) {

			if ( $listing_type_slug != '' && $feature_name != '' ) {
				$args['post_type'] = 'listings';
				$args['posts_per_page'] = 1;
				$args['fields'] = 'ids'; // only load ids
				$args['meta_query']['relation'] = 'AND';
				$args['meta_query'][] = array(
					'key' => 'wp_dp_listing_type',
					'value' => $listing_type_slug,
					'compare' => '=',
				);
				$args['meta_query'][] = array(
					'key' => 'wp_dp_listing_feature_list',
					'value' => $feature_name,
					'compare' => 'LIKE',
					'type' => 'CHAR'
				);

				$feature_query = new WP_Query( $args );
				return $feature_query->found_posts;
				wp_reset_postdata();
			}
		}

		public function wp_dp_listing_type_price_field_callback( $price_switch = '', $listing_type_slug = '' ) {
			global $wp_dp_form_fields_frontend;
                        
                        
			if ( isset( $price_switch ) && $price_switch == 'yes' ) {
                            $listing_type_obj   =   get_page_by_path( $listing_type_slug, OBJECT, 'listing-type' );
                            if(isset($listing_type_obj) && is_object($listing_type_obj)){
                                $listing_type_id    =    $listing_type_obj->ID; 
                            }else{
                                $listing_type_id    =   0;
                            }
                            
				$wp_dp_listing_type_price = get_post_meta( $listing_type_id, 'wp_dp_listing_type_price', true );
				if ( ($wp_dp_listing_type_price == 'on' && $listing_type_id != '') || $listing_type_id == '' ) {
					$wp_dp_listing_type_price_search_style = get_post_meta( $listing_type_id, 'wp_dp_listing_type_price_search_style', true );
					$wp_dp_price_minimum_options = get_post_meta( $listing_type_id, 'wp_dp_price_minimum_options', true );
					$wp_dp_price_minimum_options = ( ! empty( $wp_dp_price_minimum_options ) ) ? $wp_dp_price_minimum_options : 1;
					$wp_dp_price_max_options = get_post_meta( $listing_type_id, 'wp_dp_price_max_options', true );
					$wp_dp_price_max_options = ( ! empty( $wp_dp_price_max_options ) ) ? $wp_dp_price_max_options : 50; //50000;
					$wp_dp_price_interval = get_post_meta( $listing_type_id, 'wp_dp_price_interval', true );
					$wp_dp_price_interval = ( ! empty( $wp_dp_price_interval ) ) ? $wp_dp_price_interval : 50;
					$wp_dp_price_interval = ( int ) $wp_dp_price_interval;
					$price_counter = $wp_dp_price_minimum_options;
					$price_min = array();
					$price_max = array();
					// gettting all values of price
					$price_min = wp_dp_listing_price_options( $wp_dp_price_minimum_options, $wp_dp_price_max_options, $wp_dp_price_interval, wp_dp_plugin_text_srt( 'wp_dp_listing_search_flter_min_price' ) );
					$price_max = wp_dp_listing_price_options( $wp_dp_price_minimum_options, $wp_dp_price_max_options, $wp_dp_price_interval, wp_dp_plugin_text_srt( 'wp_dp_listing_search_flter_max_price' ) );

					if ( $listing_type_id == '' ) {
						$wp_dp_listing_type_price_search_style = 'slider';
					}

					if ( $wp_dp_listing_type_price_search_style != 'dropdown' ) {
						$rand_id = rand( 12345, 54321 );
						$min_val = $wp_dp_price_minimum_options;
						$max_val = end( $price_max );

						if ( $listing_type_id == '' ) {
							$min_val = 1;
							$max_val = 19999;
							$wp_dp_price_interval = 50;
						}

						$query_str_var_name = 'listing_price_range';
						if ( (isset( $min_val ) && $min_val != '') && (isset( $max_val ) && $max_val != '' ) ) {
                                                    $listing_short_counter = wp_dp_get_input( 'listing_short_counter', 0 );
							$selected_min_val = '';
							$selected_max_val = '';
							$range_complete_str_first = $min_val;
							$range_complete_str_second = $max_val;
							$range_complete_str = '';
							if ( isset( $_REQUEST['price_minimum'] ) && $_REQUEST['price_minimum'] != '' ) {
								$range_complete_str_first = $_REQUEST['price_minimum'];
								$selected_min_val = $_REQUEST['price_minimum'];
							}
							if ( isset( $_REQUEST['price_maximum'] ) && $_REQUEST['price_maximum'] != '' ) {
								$range_complete_str_second = $_REQUEST['price_maximum'];
								$selected_max_val = $_REQUEST['price_maximum'];
							}
							$range_complete_str = $range_complete_str_first . ',' . $range_complete_str_second;

							$wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
									array(
										'simple' => true,
										'cust_id' => "range-hidden-price-minimum-" . $listing_short_counter,
										'cust_name' => 'price_minimum',
										'std' => esc_html( $selected_min_val ),
										'classes' => 'price-minimum',
									)
							);

							$wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
									array(
										'simple' => true,
										'cust_id' => "range-hidden-price-maximum-" . $listing_short_counter,
										'cust_name' => 'price_maximum',
										'std' => esc_html( $selected_max_val ),
										'classes' => 'price-maximum',
									)
							);
							?>
							<div class="field-holder field-range split-map">
								<div class="price-per-person">
									<span class="rang-text"><?php echo wp_dp_plugin_text_srt( 'wp_dp_advance_search_select_price_range' ); ?>&nbsp;<?php echo esc_html( $range_complete_str_first ); ?> &nbsp; - &nbsp; <?php echo esc_html( $range_complete_str_second ); ?></span>
									<?php
									$wp_dp_form_fields_frontend->wp_dp_form_text_render(
											array(
												'cust_name' => '',
												'cust_id' => 'ex16b1' . esc_html( $rand_id . $query_str_var_name ),
												'std' => '',
											)
									);
									?>  
								</div>
							</div>
							<?php
							$increment_step = isset( $wp_dp_price_interval ) ? $wp_dp_price_interval : 1;
							if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
								echo '<script type="text/javascript">
									if (jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").length > 0) {
										jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").slider({
											step : ' . esc_html( $increment_step ) . ',
											min: ' . esc_html( $min_val ) . ',
											max: ' . esc_html( $max_val ) . ',
											value: [ ' . esc_html( $range_complete_str ) . '],
										});
										jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").on("slideStop", function () {
											var rang_slider_val = jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").val();
											var slider_val = rang_slider_val.split(",");
											var price_minimum = slider_val[0], price_maximum = slider_val[1];
											jQuery("#range-hidden-price-minimum-' . $listing_short_counter . '").val(price_minimum);
											jQuery("#range-hidden-price-maximum-' . $listing_short_counter . '").val(price_maximum);
											wp_dp_split_map_change_cords("' . esc_html( $listing_short_counter ) . '");
										});
									}
								</script>';
							} else {
								echo '<script type="text/javascript">
									jQuery(window).load(function(){
										if (jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").length > 0) {
											jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").slider({
												step : ' . esc_html( $increment_step ) . ',
												min: ' . esc_html( $min_val ) . ',
												max: ' . esc_html( $max_val ) . ',
												value: [ ' . esc_html( $range_complete_str ) . '],
											});
											jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").on("slideStop", function () {
												var rang_slider_val = jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").val();
												var slider_val = rang_slider_val.split(",");
												var price_minimum = slider_val[0], price_maximum = slider_val[1];
												jQuery("#range-hidden-price-minimum-' . $listing_short_counter . '").val(price_minimum);
												jQuery("#range-hidden-price-maximum-' . $listing_short_counter . '").val(price_maximum);
												wp_dp_split_map_change_cords("' . esc_html( $listing_short_counter ) . '");
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
											$price_min_checked = ( isset( $_REQUEST['price_minimum'] ) && $_REQUEST['price_minimum'] ) ? $_REQUEST['price_minimum'] : '';
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
										<li>
											<?php
											$price_max_checked = ( isset( $_REQUEST['price_maximum'] ) && $_REQUEST['price_maximum'] ) ? $_REQUEST['price_maximum'] : '';
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
				}
			}
		}

		public function wp_dp_listing_type_search_fields_callback() {
			global $wp_dp_form_fields_frontend;

			$listing_short_counter = wp_dp_get_input( 'listing_short_counter', 0 );
			$listing_type_slug = wp_dp_get_input( 'listing_type_slug', NULL, 'STRING' );
			$price_switch = wp_dp_get_input( 'price_switch', NULL, 'STRING' );
			$json = array();
			$json['type'] = "error";
			if ( $listing_type_slug != '' ) {
				ob_start();
				$this->wp_dp_listing_type_price_field_callback( $price_switch,  $listing_type_slug);
				$this->wp_dp_listing_type_fields_callback( $listing_type_slug );
				$this->wp_dp_listing_type_features( $listing_type_slug, $listing_short_counter );
				?>
				<script type="text/javascript">
					chosen_selectionbox();
				</script>
				<?php
				$content = ob_get_clean();
				$json['type'] = "success";
				$json['html'] = $content;
			}
			echo json_encode( $json );
			wp_die();
		}

		public function wp_dp_listing_type_cate_fields_callback() {
			global $wp_dp_form_fields_frontend;
			$listing_short_counter = wp_dp_get_input( 'listing_short_counter', 0 );
			$listing_type_slug = wp_dp_get_input( 'listing_type_slug', NULL, 'STRING' );
			$cats_switch = wp_dp_get_input( 'cats_switch', NULL, 'STRING' );
			$search_view = wp_dp_get_input( 'view', NULL, 'STRING' );
			$search_view = isset( $search_view ) ? $search_view : '';

			$wp_dp_search_label_color = wp_dp_get_input( 'color', NULL, 'STRING' );
			$wp_dp_search_label_color = isset( $wp_dp_search_label_color ) ? $wp_dp_search_label_color : '';


			if ( isset( $wp_dp_search_label_color ) && $wp_dp_search_label_color != '' && $wp_dp_search_label_color != 'none' ) {
				$label_style_colr = 'style="color:' . $wp_dp_search_label_color . ' !important"';
			}

			$json = array();
			$json['type'] = "error";
			if ( $listing_type_slug != '' ) {
				ob_start();

				$listing_cats_array = $this->wp_dp_listing_type_categories_options( $listing_type_slug );

				if ( isset( $cats_switch ) && $cats_switch == 'yes' && ! empty( $listing_cats_array ) ) {

					if ( ! empty( $search_view ) && $search_view == 'modern' ) {
						?>
						<strong class="search-title" <?php echo wp_dp_allow_special_char( $label_style_colr ); ?>><?php echo wp_dp_plugin_text_srt( 'wp_dp_listing_search_view_enter_listing_type_label' ); ?></strong>
					<?php } ?>    
					<label>
						<?php if ( ! empty( $search_view ) && $search_view != 'modern-v2' && $search_view != 'fancy-v3' ) { ?>
							<i class="icon-home"></i>
						<?php } ?>
						<?php
						$wp_dp_opt_array = array(
							'std' => (isset( $_REQUEST['listing_category'] ) && $_REQUEST['listing_category'] != '') ? $_REQUEST['listing_category'] : '',
							'id' => 'wp_dp_listing_category',
							'classes' => 'chosen-select',
							'cust_name' => 'listing_category',
							'options' => $listing_cats_array,
						);
						if ( count( $listing_cats_array ) <= 6 ) {
							$wp_dp_opt_array['classes'] = 'chosen-select-no-single';
						}
						$wp_dp_form_fields_frontend->wp_dp_form_select_render( $wp_dp_opt_array );
						?>
					</label>
				<?php } ?>

				<?php
				?>
				<script type="text/javascript">
					chosen_selectionbox();
				</script>
				<?php
				$content = ob_get_clean();
				$json['type'] = "success";
				$json['html'] = $content;
			}
			echo json_encode( $json );
			wp_die();
		}

		public function wp_dp_listing_type_fields_callback( $list_type_slug = '' ) {
			global $wp_dp_form_fields;
			$advanced_filter = false;

			if ( $list_type_slug != '' ) {
				$listing_type_id = $this->wp_dp_listing_type_id_by_slug( $list_type_slug );
				if ( $listing_type_id != 0 ) {
					$listing_type_fields = get_post_meta( $listing_type_id, 'wp_dp_listing_type_cus_fields', true );
					if ( isset( $listing_type_fields ) && is_array( $listing_type_fields ) && ! empty( $listing_type_fields ) ) {
						foreach ( $listing_type_fields as $listing_type_field ) {
							$field_type = isset( $listing_type_field['type'] ) ? $listing_type_field['type'] : '';
							$field_enable_srch = isset( $listing_type_field['enable_srch'] ) ? $listing_type_field['enable_srch'] : '';
							if ( $field_enable_srch == 'yes' ) {
								if ( $field_type == 'date' ) {
									$this->wp_dp_date_field( $listing_type_field );
								} else if ( $field_type == 'range' ) {
									$this->wp_dp_range_field( $listing_type_field );
								} else {
									echo force_balance_tags( $this->wp_dp_common_field( $listing_type_field ) );
								}
								$advanced_filter = true;
							}
						}
					}
				}
			}
		}

		public function wp_dp_listing_type_id_by_slug( $list_type_slug = '' ) {
			if ( $post = get_page_by_path( $list_type_slug, OBJECT, 'listing-type' ) ) {
				$listing_type_id = $post->ID;
			} else {
				$listing_type_id = 0;
			}
			return wp_dp_wpml_lang_page_id( $listing_type_id, 'listing-type' );
		}

		public function wp_dp_common_field( $custom_field = '' ) {
			global $wp_dp_form_fields;
			$field_counter = rand( 12345, 54321 );
			$field_type = isset( $custom_field['type'] ) ? $custom_field['type'] : '';
			$field_label = isset( $custom_field['label'] ) ? $custom_field['label'] : '';
			$field_meta_key = isset( $custom_field['meta_key'] ) ? $custom_field['meta_key'] : '';
			$field_placeholder = isset( $custom_field['placeholder'] ) ? $custom_field['placeholder'] : '';
			$field_default_value = isset( $custom_field['default_value'] ) ? $custom_field['default_value'] : '';
			$field_size = isset( $custom_field['field_size'] ) ? $custom_field['field_size'] : '';
			$field_fontawsome_icon = isset( $custom_field['fontawsome_icon'] ) ? $custom_field['fontawsome_icon'] : '';
			$field_required = isset( $custom_field['required'] ) ? $custom_field['required'] : '';

			$output = '';

			if ( $field_meta_key != '' ) {

				// Field Options
				$wp_dp_opt_array = array();
				$wp_dp_opt_array['std'] = esc_attr( $field_default_value );
				$wp_dp_opt_array['label'] = $field_label;
				$wp_dp_opt_array['cust_id'] = $field_meta_key;
				$wp_dp_opt_array['cust_name'] = $field_meta_key;
				$wp_dp_opt_array['extra_atr'] = $this->wp_dp_field_placeholder( $field_placeholder );
				$wp_dp_opt_array['classes'] = 'input-field';
				$wp_dp_opt_array['return'] = true;
				// End Field Options

				$field_size = $this->wp_dp_field_size( $field_size );
				$field_icon = $this->wp_dp_field_icon( $field_fontawsome_icon );
				$has_icon = '';
				if ( $field_icon != '' ) {
					$has_icon = 'has-icon';
				}

				// Making Field with defined options
				if ( $field_type == 'text' || $field_type == 'url' || $field_type == 'email' ) {
					$output .= '<div class="field-holder search-input ' . esc_html( $has_icon ) . '">';
					$output .= '<label>';
					$output .= $field_icon;
					$output .= $wp_dp_form_fields->wp_dp_form_text_render( $wp_dp_opt_array );
					$output .= '</label>';
					$output .= '</div>' . "\n";
				} elseif ( $field_type == 'number' ) {
					if ( isset( $_GET[$field_meta_key] ) ) {
						$field_default_value = $_GET[$field_meta_key];
					}

					$wp_dp_form_fields->wp_dp_form_hidden_render(
							array(
								'simple' => true,
								'cust_id' => "number-hidden1-" . $field_meta_key,
								'cust_name' => wp_dp_allow_special_char( $field_meta_key ),
								'std' => isset( $field_default_value ) && $field_default_value != '' ? $field_default_value : 0,
							)
					);
					?>
					<div class="field-holder search-input select-categories <?php echo esc_html( $has_icon ); ?>">
						<ul class="minimum-loading-list">
							<li>
								<div class="spinner-btn input-group spinner">
									<span><?php echo wp_dp_cs_allow_special_char($field_icon); ?></span>
									<?php
									$wp_dp_form_fields->wp_dp_form_text_render(
											array(
												'id' => 'wp_dp_' . $field_meta_key,
												'cust_name' => '',
												'classes' => "num-input1" . esc_html( $field_meta_key ) . " form-control",
												'std' => isset( $field_default_value ) && $field_default_value != '' ? $field_default_value : 0,
												'force_std' => true,
											)
									);
									?>
									<span class="list-text"><?php echo esc_html( $field_label ); ?></span>
									<div class="input-group-btn-vertical">
										<button class="btn-decrement1<?php echo esc_html( $field_meta_key ); ?> caret-btn btn-default " type="button"><i class="icon-minus-circle"></i></button>
										<button class="btn-increment1<?php echo esc_html( $field_meta_key ); ?> caret-btn btn-default" type="button"><i class="icon-plus-circle"></i></button>
									</div>
								</div>
							</li>
						</ul>
						<script type="text/javascript">
							jQuery(document).ready(function ($) {
								$(".num-input1<?php echo esc_html( $field_meta_key ); ?>").keypress(function (e) {
									if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
										return false;
									}
								});
								$('.spinner .btn-increment1<?php echo esc_html( $field_meta_key ); ?>').on('click', function () {

									var field_value = $('.spinner .num-input1<?php echo esc_html( $field_meta_key ); ?>').val();

									field_value = field_value || 0;

									$('.spinner .num-input1<?php echo esc_html( $field_meta_key ); ?>').val(parseInt(field_value, 10) + 1);
									var selected_num = parseInt(field_value, 10) + 1;
									$('#number-hidden1-<?php echo esc_html( $field_meta_key ); ?>').val(selected_num);
								});
								$('.spinner .btn-decrement1<?php echo esc_html( $field_meta_key ); ?>').on('click', function () {
									var field_value = $('.spinner .num-input1<?php echo esc_html( $field_meta_key ); ?>').val();
									field_value = field_value || 0;
									var val = parseInt(field_value, 10);
									if (val < 1) {
										//return;
									}
									var minus_val = val - 1;
									if (minus_val < 0) {
										minus_val = 0;
									}
									$('.spinner .num-input1<?php echo esc_html( $field_meta_key ); ?>').val(minus_val);
									var selected_num = minus_val;
									$('#number-hidden1-<?php echo esc_html( $field_meta_key ); ?>').val(selected_num);
								});
								$(".num-input1<?php echo esc_html( $field_meta_key ); ?>").on('change keydown', function () {
									var field_value = $('.spinner .num-input1<?php echo esc_html( $field_meta_key ); ?>').val();
									field_value = field_value || 0;
									var selected_num = field_value;
									$('#number-hidden1-<?php echo esc_html( $field_meta_key ); ?>').val(selected_num);
								});
							});
						</script>
						<?php ?>
					</div>
					<?php
				} elseif ( $field_type == 'dropdown' ) {
					$output .= '<div class="field-holder select-dropdown ' . esc_html( $has_icon ) . '">';
					$output .= '<label>';
					$output .= $field_icon;
					$output .= $this->wp_dp_dropdown_field( $custom_field, $wp_dp_opt_array, $field_counter );
					$output .= '</label>';
					$output .= '</div>' . "\n";
				}
			}
			return $output;
		}

		public function wp_dp_dropdown_field( $custom_field = '', $wp_dp_opt_array = '', $field_counter = '' ) {
			global $wp_dp_form_fields;

			$field_meta_key = isset( $custom_field['meta_key'] ) ? $custom_field['meta_key'] : '';
			$output = '';
			if ( ! empty( $wp_dp_opt_array ) ) {
				$drop_down_options = array();
				if ( isset( $custom_field['options'] ) && ! empty( $custom_field['options'] ) ) {
					$first_value = isset( $custom_field['label'] ) ? $custom_field['label'] : '';
					if ( $first_value != '' ) {
						$drop_down_options[''] = esc_html( $first_value );
					}
					foreach ( $custom_field['options']['label'] as $key => $value ) {
						$drop_down_options[esc_html( $custom_field['options']['value'][$key] )] = esc_html( $value );
					}
				}
				$wp_dp_opt_array['options'] = $drop_down_options;

				if ( isset( $custom_field['chosen_srch'] ) && $custom_field['chosen_srch'] == 'yes' && count( $drop_down_options ) > 5 ) {
					$wp_dp_opt_array['classes'] = 'chosen-select';
				} else {
					$wp_dp_opt_array['classes'] = 'chosen-select-no-single';
				}
				if ( isset( $custom_field['multi'] ) && $custom_field['multi'] == 'yes' ) {
					?>
					<script type="text/javascript">
						jQuery(document).ready(function () {
							jQuery('#<?php echo wp_dp_cs_allow_special_char($field_meta_key); ?>_<?php echo wp_dp_cs_allow_special_char($field_counter); ?>').on('change', function () {
								var selected_val = jQuery(this).val();
								console.log(selected_val);
								jQuery('#wp_dp_<?php echo wp_dp_cs_allow_special_char($field_meta_key); ?>_<?php echo wp_dp_cs_allow_special_char($field_counter); ?>').val(selected_val);
							});
						});
					</script>
					<?php
					$wp_dp_hidden_opt_array = array(
						'id' => $field_meta_key . '_' . $field_counter,
						'cust_name' => $field_meta_key,
						'std' => '',
						'return' => true,
						'force_std' => true,
					);
					$output .= $wp_dp_form_fields->wp_dp_form_hidden_render( $wp_dp_hidden_opt_array );
					$wp_dp_opt_array['cust_id'] = $field_meta_key . '_' . $field_counter;
					$wp_dp_opt_array['cust_name'] = '';
					$output .= $wp_dp_form_fields->wp_dp_form_multiselect_render( $wp_dp_opt_array );
				} else {
					$output .= $wp_dp_form_fields->wp_dp_form_select_render( $wp_dp_opt_array );
				}
			}
			return $output;
		}

		public function wp_dp_date_field( $custom_field = '' ) {
			global $wp_dp_form_fields;
			$field_counter = rand( 12345, 54321 );

			$query_str_var_name = isset( $custom_field['meta_key'] ) ? $custom_field['meta_key'] : '';
			$field_label = isset( $custom_field['label'] ) ? $custom_field['label'] : '';
			$field_fontawsome_icon = isset( $custom_field['fontawsome_icon'] ) ? $custom_field['fontawsome_icon'] : '';
			$field_icon = $this->wp_dp_field_icon( $field_fontawsome_icon );
			wp_enqueue_script( 'bootstrap-datepicker' );
			wp_enqueue_style( 'datetimepicker' );
			wp_enqueue_style( 'datepicker' );
			wp_enqueue_script( 'datetimepicker' );
			?>

			<div class="cs-datepicker field-datepicker field-holder search-input">

				<label id="Deadline" class="cs-calendar-from-<?php echo wp_dp_cs_allow_special_char($field_counter); ?>">
					<?php echo wp_dp_allow_special_char( $field_icon ); ?>
					<?php
					$wp_dp_form_fields->wp_dp_form_text_render(
							array(
								'id' => $query_str_var_name,
								'cust_name' => 'from' . $query_str_var_name,
								'classes' => '',
								'std' => isset( $_REQUEST['from' . $query_str_var_name] ) ? $_REQUEST['from' . $query_str_var_name] : '',
								'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_search_fields_date_from' ) . ' ' . $field_label . '");"',
							)
					);
					?>

				</label>
			</div>
			<div class="cs-datepicker field-datepicker field-holder search-input">
				<label id="Deadline" class="cs-calendar-to-<?php echo wp_dp_cs_allow_special_char($field_counter); ?>">
					<?php echo wp_dp_allow_special_char( $field_icon ); ?>
					<?php
					$wp_dp_form_fields->wp_dp_form_text_render(
							array(
								'id' => $query_str_var_name,
								'cust_name' => 'to' . $query_str_var_name,
								'classes' => '',
								'std' => isset( $_REQUEST['to' . $query_str_var_name] ) ? $_REQUEST['to' . $query_str_var_name] : '',
								'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_search_fields_date_to' ) . ' ' . $field_label . '");"',
							)
					);
					?>

				</label>
			</div>
			<?php
			if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
				echo '<script type="text/javascript">
						if (jQuery(".cs-calendar-from-' . $field_counter . ' input").length != "") {
							jQuery(".cs-calendar-from-' . $field_counter . ' input").datetimepicker({
								timepicker:false,
								format:	"Y/m/d",
								scrollInput: false
							});
						}
						if (jQuery(".cs-calendar-to-' . $field_counter . ' input").length != "") {
							jQuery(".cs-calendar-to-' . $field_counter . ' input").datetimepicker({
								timepicker:false,
								format:	"Y/m/d",
								scrollInput: false
							});
						}
					</script>';
			} else {
				echo '<script type="text/javascript">
						jQuery(window).load(function(){
								if (jQuery(".cs-calendar-from-' . $field_counter . ' input").length != "") {
								jQuery(".cs-calendar-from-' . $field_counter . ' input").datetimepicker({
									timepicker:false,
									format:	"Y/m/d",
									scrollInput: false
								});
							}
							if (jQuery(".cs-calendar-to-' . $field_counter . ' input").length != "") {
								jQuery(".cs-calendar-to-' . $field_counter . ' input").datetimepicker({
									timepicker:false,
									format:	"Y/m/d",
									scrollInput: false
								});
							}
						});
					</script>';
			}
			?>

			<?php
		}

		public function wp_dp_range_field( $custom_field = '' ) {
			global $wp_dp_form_fields, $wp_dp_form_fields_frontend;
			$range_min = $custom_field['min'];
			$field_label = isset( $custom_field['label'] ) ? $custom_field['label'] : '';
			$range_max = $custom_field['max'];
			$range_increment = $custom_field['increment'];
			$query_str_var_name = $custom_field['meta_key'];
			$filed_type = $custom_field['srch_style']; //input, slider, input_slider
			if ( strpos( $filed_type, '-' ) !== FALSE ) {
				$filed_type_arr = explode( "_", $filed_type );
			} else {
				$filed_type_arr[0] = $filed_type;
			}
			$range_flag = 0;
			$rand_id = rand( 12345, 54321 );
			?>
			<div class="field-holder <?php
			if ( $filed_type_arr[$range_flag] == 'slider' ) {
				echo 'field-range';
			} else {
				echo 'select-dropdown has-icon';
			}
			?>">
					 <?php
					 while ( count( $filed_type_arr ) > $range_flag ) {
						 if ( $filed_type_arr[$range_flag] == 'slider' ) { // if slider style
							 if ( (isset( $custom_field['min'] ) && $custom_field['min'] != '') && (isset( $custom_field['max'] ) && $custom_field['max'] != '' ) ) {
								 $range_complete_str_first = "";
								 $range_complete_str_second = "";
								 $range_complete_str = '';
								 if ( isset( $_REQUEST[$query_str_var_name] ) ) {
									 $range_complete_str = $_REQUEST[$query_str_var_name];
									 $range_complete_str_arr = explode( ",", $range_complete_str );
									 $range_complete_str_first = isset( $range_complete_str_arr[0] ) ? $range_complete_str_arr[0] : '';
									 $range_complete_str_second = isset( $range_complete_str_arr[1] ) ? $range_complete_str_arr[1] : '';
								 } else {
									 $range_complete_str = $custom_field['min'] . ',' . $custom_field['max'];
									 $range_complete_str_first = $custom_field['min'];
									 $range_complete_str_second = $custom_field['max'];
								 }

								 $wp_dp_form_fields->wp_dp_form_hidden_render(
										 array(
											 'simple' => true,
											 'cust_id' => "range-hidden-" . $query_str_var_name,
											 'cust_name' => $query_str_var_name,
											 'std' => esc_html( $range_complete_str ),
											 'classes' => $query_str_var_name,
										 )
								 );
								 ?>
							<div class="price-per-person">
								<span class="rang-text"><?php echo wp_dp_allow_special_char( $field_label ); ?>&nbsp;<?php echo esc_html( $range_complete_str_first ); ?> &nbsp; - &nbsp; <?php echo esc_html( $range_complete_str_second ); ?></span>
								<?php
								$wp_dp_form_fields_frontend->wp_dp_form_text_render(
										array(
											'cust_name' => '',
											'cust_id' => 'ex16b1' . esc_html( $rand_id . $query_str_var_name ),
											'std' => '',
										)
								);
								?>  
							</div>
							<?php
							$increment_step = isset( $custom_field['increment'] ) ? $custom_field['increment'] : 1;
							if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
								echo '<script type="text/javascript">
									if (jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").length > 0) {
										jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").slider({
											step : ' . esc_html( $increment_step ) . ',
											min: ' . esc_html( $custom_field['min'] ) . ',
											max: ' . esc_html( $custom_field['max'] ) . ',
											value: [ ' . esc_html( $range_complete_str ) . '],
										});
										jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").on("slideStop", function () {
											var rang_slider_val = jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").val();
											jQuery("#range-hidden-' . $query_str_var_name . '").val(rang_slider_val);    
										});
									}
								</script>';
							} else {
								echo '<script type="text/javascript">
									jQuery(window).load(function(){
										if (jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").length > 0) {
											jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").slider({
												step : ' . esc_html( $increment_step ) . ',
												min: ' . esc_html( $custom_field['min'] ) . ',
												max: ' . esc_html( $custom_field['max'] ) . ',
												value: [ ' . esc_html( $range_complete_str ) . '],
											});
											jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").on("slideStop", function () {
												var rang_slider_val = jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").val();
												jQuery("#range-hidden-' . $query_str_var_name . '").val(rang_slider_val);    
											});
										}
									});
								</script>';
							}
						}
					} else {
						?>
						<label>
							<em class="currency-sign"><?php echo wp_dp_get_currency_sign(); ?></em>
							<?php
							$options = array();
							$options[''] = wp_dp_allow_special_char( $field_label );
							$range_min = $custom_field['min'];
							$range_max = $custom_field['max'];

							$counter = 0;
							while ( $counter < $range_max ) {
								$options[$counter . ',' . ($counter + $range_increment)] = ($counter . ' - ' . ($counter + $range_increment));
								$counter += $range_increment;
							}

							ksort( $options );
							$options = array_filter( $options );
							$wp_dp_opt_array = array(
								'std' => '',
								'id' => $query_str_var_name,
								'classes' => 'chosen-select',
								'cust_name' => $query_str_var_name,
								'options' => $options,
							);
							$wp_dp_form_fields_frontend->wp_dp_form_select_render( $wp_dp_opt_array );
							?>
						</label>
						<?php
					}
					$range_flag ++;
				}
				?>
			</div>
			<?php
		}

		public function wp_dp_field_size( $field_size ) {
			switch ( $field_size ) {
				case "small":
					$col_size = '4';
					break;
				case "medium":
					$col_size = '6';
					break;
				case "large":
					$col_size = '12';
					break;
				default :
					$col_size = '12';
					break;
			}
			return $col_size;
		}

		public function wp_dp_field_label( $field_label ) {
			$output = '';
			if ( $field_label != '' ) {
				$output .= '<label>' . $field_label . '</label>';
			}
			return $output;
		}

		public function wp_dp_field_icon( $field_fontawsome_icon ) {
			$output = '';
			if ( $field_fontawsome_icon != '' ) {
				$output .= '<i class="' . $field_fontawsome_icon . '"></i>';
			}
			return $output;
		}

		public function wp_dp_field_placeholder( $field_placeholder ) {
			$placeholder = '';
			if ( $field_placeholder != '' ) {
				$placeholder .= 'placeholder="' . $field_placeholder . '"';
			}
			return $placeholder;
		}

		public function wp_dp_listing_type_categories_options( $listing_type_slug = '' ) {
			$listing_cats_options = array();
			if ( $listing_type_slug != '' ) {
				$listing_type_id = $this->wp_dp_listing_type_id_by_slug( $listing_type_slug );
				$listing_type_cats = get_post_meta( $listing_type_id, 'wp_dp_listing_type_cats', true );
				if ( isset( $listing_type_cats ) && is_array( $listing_type_cats ) && ! empty( $listing_type_cats ) ) {
					$listing_cats_options[''] = wp_dp_plugin_text_srt( 'wp_dp_search_element_listig_categories' );
					foreach ( $listing_type_cats as $listing_type_cat_slug ) {
						if ( $listing_type_cat_slug != '' ) {
							$term = get_term_by( 'slug', $listing_type_cat_slug, 'listing-category' );
							if ( isset( $term->name ) && ! empty( $term->name ) ) {
								$listing_cats_options[$listing_type_cat_slug] = $term->name;
							}
						}
					}
				}
			}

			if ( isset( $listing_type_slug ) && $listing_type_slug == 'all' ) {
				$all_terms = get_terms( array(
					'taxonomy' => 'listing-category',
					'hide_empty' => false,
						) );
				if ( $all_terms && ! is_wp_error( $all_terms ) ) :
					$listing_cats_options[''] = wp_dp_plugin_text_srt( 'wp_dp_search_element_listig_categories' );
					foreach ( $all_terms as $term_single ) {
						$listing_cats_options[$term_single->slug] = $term_single->name;
					} endif;
			}
			return $listing_cats_options;
		}

	}

	global $wp_dp_search_fields;
	$wp_dp_search_fields = new Wp_dp_Search_Fields();
}