<?php
/**
 * Shortcode Name : wp_dp_add_listing
 *
 * @package	wp_dp_cs 
 */
// This file is intentially disabled.
if ( false ) {



	if ( ! function_exists('wp_dp_cs_var_page_builder_wp_dp_add_listing') ) {

		function wp_dp_cs_var_page_builder_wp_dp_add_listing($die = 0) {
			global $post, $wp_dp_html_fields, $wp_dp_cs_node, $wp_dp_form_fields;
			if ( function_exists('wp_dp_cs_shortcode_names') ) {
				$shortcode_element = '';
				$filter_element = 'filterdrag';
				$shortcode_view = '';
				$wp_dp_cs_output = array();
				$wp_dp_cs_PREFIX = 'wp_dp_add_listing';

				$wp_dp_cs_counter = isset($_POST['counter']) ? $_POST['counter'] : '';
				if ( isset($_POST['action']) && ! isset($_POST['shortcode_element_id']) ) {
					$wp_dp_cs_POSTID = '';
					$shortcode_element_id = '';
				} else {
					$wp_dp_cs_POSTID = isset($_POST['POSTID']) ? $_POST['POSTID'] : '';
					$shortcode_element_id = isset($_POST['shortcode_element_id']) ? $_POST['shortcode_element_id'] : '';
					$shortcode_str = stripslashes($shortcode_element_id);
					$parseObject = new ShortcodeParse();
					$wp_dp_cs_output = $parseObject->wp_dp_cs_shortcodes($wp_dp_cs_output, $shortcode_str, true, $wp_dp_cs_PREFIX);
				}
				$defaults = array( 'listing_title' => '', 'title_align' => '', 'search_vew' => '', 'search_categories' => '', 'posts_per_page' => '', 'pagination' => '', 'button_text' => '' );
				if ( isset($wp_dp_cs_output['0']['atts']) ) {
					$atts = $wp_dp_cs_output['0']['atts'];
				} else {
					$atts = array();
				}
				if ( isset($wp_dp_cs_output['0']['content']) ) {
					$wp_dp_add_listing_column_text = $wp_dp_cs_output['0']['content'];
				} else {
					$wp_dp_add_listing_column_text = '';
				}
				$wp_dp_add_listing_element_size = '100';
				foreach ( $defaults as $key => $values ) {
					if ( isset($atts[$key]) ) {
						$$key = $atts[$key];
					} else {
						$$key = $values;
					}
				}
				$name = 'wp_dp_cs_var_page_builder_wp_dp_add_listing';
				$coloumn_class = 'column_' . $wp_dp_add_listing_element_size;
				if ( isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode' ) {
					$shortcode_element = 'shortcode_element_class';
					$shortcode_view = 'cs-pbwp-shortcode';
					$filter_element = 'ajax-drag';
					$coloumn_class = '';
				}
				wp_enqueue_script('wp_dp_cs-admin-upload');
				$listing_title = isset($atts['listing_title']) ? $atts['listing_title'] : '';
				$wp_dp_image_url = isset($atts['add_listing_url']) ? $atts['add_listing_url'] : '';
				$wp_dp_text_color = isset($atts['text_color']) ? $atts['text_color'] : '';
				$wp_dp_bg_color = isset($atts['bg_color']) ? $atts['bg_color'] : '';
				?>

				<div id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class); ?>
					 <?php echo esc_attr($shortcode_view); ?>" item="wp_dp_add_listing" data="<?php echo wp_dp_cs_element_size_data_array_index($wp_dp_add_listing_element_size) ?>" >
						 <?php wp_dp_cs_element_setting($name, $wp_dp_cs_counter, $wp_dp_add_listing_element_size) ?>
					<div class="cs-wrapp-class-<?php echo intval($wp_dp_cs_counter) ?>
						 <?php echo esc_attr($shortcode_element); ?>" id="<?php echo esc_attr($name . $wp_dp_cs_counter) ?>" data-shortcode-template="[wp_dp_add_listing {{attributes}}]{{content}}[/wp_dp_add_listing]" style="display: none;">
						<div class="cs-heading-area" data-counter="<?php echo esc_attr($wp_dp_cs_counter) ?>">
							<h5><?php echo wp_dp_plugin_text_srt('wp_dp_add_listing_options'); ?></h5>
							<a href="javascript:wp_dp_cs_frame_removeoverlay('<?php echo esc_js($name . $wp_dp_cs_counter) ?>','<?php echo esc_js($filter_element); ?>')" class="cs-btnclose">
								<i class="icon-cross"></i>
							</a>
						</div>
						<div class="cs-pbwp-content">
							<div class="cs-wrapp-clone cs-shortcode-wrapp">
								<?php
								if ( isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode' ) {
									wp_dp_cs_shortcode_element_size();
								}

								$wp_dp_opt_array = array(
									'name' => wp_dp_plugin_text_srt('wp_dp_element_title'),
									'desc' => '',
									'label_desc' => wp_dp_plugin_text_srt('wp_dp_element_title_hint'),
									'echo' => true,
									'field_params' => array(
										'std' => $listing_title,
										'id' => 'listing_title',
										'cust_name' => 'listing_title[]',
										'return' => true,
									),
								);

								$wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

								$wp_dp_opt_array = array(
									'name' => wp_dp_plugin_text_srt('wp_dp_title_align'),
									'desc' => '',
									'label_desc' => wp_dp_plugin_text_srt('wp_dp_title_align_hint'),
									'echo' => true,
									'field_params' => array(
										'std' => $title_align,
										'id' => '',
										'cust_id' => 'title_align',
										'cust_name' => 'title_align[]',
										'classes' => 'service_postion chosen-select-no-single select-medium',
										'options' => array(
											'align-left' => wp_dp_plugin_text_srt('wp_dp_align_left'),
											'align-right' => wp_dp_plugin_text_srt('wp_dp_align_right'),
											'align-center' => wp_dp_plugin_text_srt('wp_dp_align_center'),
										),
										'return' => true,
									),
								);
								$wp_dp_html_fields->wp_dp_var_select_field($wp_dp_opt_array);
								?>
							</div>
							<?php if ( isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode' ) { ?>
								<ul class="form-elements insert-bg">
									<li class="to-field">
										<a class="insert-btn cs-main-btn" onclick="javascript:wp_dp_cs_shortcode_insert_editor('<?php echo str_replace('wp_dp_cs_var_page_builder_', '', $name); ?>', '<?php echo esc_js($name . $wp_dp_cs_counter) ?>', '<?php echo esc_js($filter_element); ?>')" ><?php echo wp_dp_plugin_text_srt('wp_dp_insert'); ?></a>
									</li>
								</ul>
								<div id="results-shortocde"></div>
							<?php } else { ?>

								<?php
								$wp_dp_cs_opt_array = array(
									'std' => 'wp_dp_add_listing',
									'id' => '',
									'before' => '',
									'after' => '',
									'classes' => '',
									'extra_atr' => '',
									'cust_id' => 'wp_dp_cs_orderby' . $wp_dp_cs_counter,
									'cust_name' => 'wp_dp_cs_orderby[]',
									'required' => false
								);
								$wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_cs_opt_array);

								$wp_dp_cs_opt_array = array(
									'name' => '',
									'desc' => '',
									'label_desc' => '',
									'echo' => true,
									'field_params' => array(
										'std' => wp_dp_plugin_text_srt('wp_dp_save'),
										'cust_id' => 'wp_dp_add_listing_save',
										'cust_type' => 'button',
										'extra_atr' => 'onclick="javascript:_removerlay(jQuery(this))"',
										'classes' => 'cs-wp_dp_cs-admin-btn',
										'cust_name' => 'wp_dp_add_listing_save',
										'return' => true,
									),
								);

								$wp_dp_html_fields->wp_dp_text_field($wp_dp_cs_opt_array);
							}
							?>
						</div>
					</div>
					<script type="text/javascript">
				        popup_over();
						chosen_selectionbox();
					</script>
				</div>

				<?php
			}
			if ( $die <> 1 ) {
				die();
			}
		}

		add_action('wp_ajax_wp_dp_cs_var_page_builder_wp_dp_add_listing', 'wp_dp_cs_var_page_builder_wp_dp_add_listing');
	}

	if ( ! function_exists('wp_dp_cs_save_page_builder_data_wp_dp_add_listing_callback') ) {

		/**
		 * Save data for wp_dp_add_listing shortcode.
		 *
		 * @param	array $args
		 * @return	array
		 */
		function wp_dp_cs_save_page_builder_data_wp_dp_add_listing_callback($args) {

			$data = $args['data'];
			$counters = $args['counters'];
			$widget_type = $args['widget_type'];
			$column = $args['column'];
                        $shortcode_data = '';
			if ( $widget_type == "wp_dp_add_listing" || $widget_type == "cs_wp_dp_add_listing" ) {
				$wp_dp_cs_bareber_wp_dp_add_listing = '';

				$page_element_size = $data['wp_dp_add_listing_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_add_listing']];
				$current_element_size = $data['wp_dp_add_listing_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_add_listing']];

				if ( isset($data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']]) && $data['wp_dp_cs_widget_element_num'][$counters['wp_dp_cs_counter']] == 'shortcode' ) {
					$shortcode_str = stripslashes(( $data['shortcode']['wp_dp_add_listing'][$counters['wp_dp_cs_shortcode_counter_wp_dp_add_listing']]));

					$element_settings = 'wp_dp_add_listing_element_size="' . $current_element_size . '"';
					$reg = '/wp_dp_add_listing_element_size="(\d+)"/s';
					$shortcode_str = preg_replace($reg, $element_settings, $shortcode_str);
					$shortcode_data = $shortcode_str;

					$wp_dp_cs_bareber_wp_dp_add_listing ++;
				} else {
					$element_settings = 'wp_dp_add_listing_element_size="' . htmlspecialchars($data['wp_dp_add_listing_element_size'][$counters['wp_dp_cs_global_counter_wp_dp_add_listing']]) . '"';
					$wp_dp_cs_bareber_wp_dp_add_listing = '[wp_dp_add_listing ' . $element_settings . ' ';
					if ( isset($data['listing_title'][$counters['wp_dp_cs_counter_wp_dp_add_listing']]) && $data['listing_title'][$counters['wp_dp_cs_counter_wp_dp_add_listing']] != '' ) {
						$wp_dp_cs_bareber_wp_dp_add_listing .= 'listing_title="' . htmlspecialchars($data['listing_title'][$counters['wp_dp_cs_counter_wp_dp_add_listing']], ENT_QUOTES) . '" ';
					}
					
					if ( isset($data['title_align'][$counters['wp_dp_cs_counter_wp_dp_add_listing']]) && $data['title_align'][$counters['wp_dp_cs_counter_wp_dp_add_listing']] != '' ) {
						$wp_dp_cs_bareber_wp_dp_add_listing .= 'title_align="' . htmlspecialchars($data['title_align'][$counters['wp_dp_cs_counter_wp_dp_add_listing']], ENT_QUOTES) . '" ';
					}

					$wp_dp_cs_bareber_wp_dp_add_listing .= ']';
					if ( isset($data['wp_dp_add_listing_column_text'][$counters['wp_dp_cs_counter_wp_dp_add_listing']]) && $data['wp_dp_add_listing_column_text'][$counters['wp_dp_cs_counter_wp_dp_add_listing']] != '' ) {
						$wp_dp_cs_bareber_wp_dp_add_listing .= htmlspecialchars($data['wp_dp_add_listing_column_text'][$counters['wp_dp_cs_counter_wp_dp_add_listing']], ENT_QUOTES) . ' ';
					}
					$wp_dp_cs_bareber_wp_dp_add_listing .= '[/wp_dp_add_listing]';

					$shortcode_data .= $wp_dp_cs_bareber_wp_dp_add_listing;
					$counters['wp_dp_cs_counter_wp_dp_add_listing'] ++;
				}
				$counters['wp_dp_cs_global_counter_wp_dp_add_listing'] ++;
			}
			return array(
				'data' => $data,
				'counters' => $counters,
				'widget_type' => $widget_type,
				'column' => $shortcode_data,
			);
		}

		add_filter('wp_dp_cs_save_page_builder_data_wp_dp_add_listing', 'wp_dp_cs_save_page_builder_data_wp_dp_add_listing_callback');
	}

	if ( ! function_exists('wp_dp_cs_load_shortcode_counters_wp_dp_add_listing_callback') ) {

		/**
		 * Populate wp_dp_add_listing shortcode counter variables.
		 *
		 * @param	array $counters
		 * @return	array
		 */
		function wp_dp_cs_load_shortcode_counters_wp_dp_add_listing_callback($counters) {
			$counters['wp_dp_cs_global_counter_wp_dp_add_listing'] = 0;
			$counters['wp_dp_cs_shortcode_counter_wp_dp_add_listing'] = 0;
			$counters['wp_dp_cs_counter_wp_dp_add_listing'] = 0;
			return $counters;
		}

		add_filter('wp_dp_cs_load_shortcode_counters', 'wp_dp_cs_load_shortcode_counters_wp_dp_add_listing_callback');
	}



	if ( ! function_exists('wp_dp_cs_element_list_populate_wp_dp_add_listing_callback') ) {

		/**
		 * Populate wp_dp_add_listing shortcode strings list.
		 *
		 * @param	array $counters
		 * @return	array
		 */
		function wp_dp_cs_element_list_populate_wp_dp_add_listing_callback($element_list) {
			$element_list['wp_dp_add_listing'] = wp_dp_plugin_text_srt('wp_dp_add_listings');
			return $element_list;
		}

		add_filter('wp_dp_cs_element_list_populate', 'wp_dp_cs_element_list_populate_wp_dp_add_listing_callback');
	}

	if ( ! function_exists('wp_dp_cs_shortcode_names_list_populate_wp_dp_add_listing_callback') ) {

		/**
		 * Populate wp_dp_add_listing shortcode names list.
		 *
		 * @param	array $counters
		 * @return	array
		 */
		function wp_dp_cs_shortcode_names_list_populate_wp_dp_add_listing_callback($shortcode_array) {
			$shortcode_array['wp_dp_add_listing'] = array(
				'title' => wp_dp_plugin_text_srt('wp_dp_add_listings'),
				'name' => 'wp_dp_add_listing',
				'icon' => 'icon-gears',
				'categories' => 'typography',
			);

			return $shortcode_array;
		}

		add_filter('wp_dp_cs_shortcode_names_list_populate', 'wp_dp_cs_shortcode_names_list_populate_wp_dp_add_listing_callback');
	}

}