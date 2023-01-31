<?php

/**
 * Start Function  how to Create Transations Fields
 */
if ( ! function_exists('wp_dp_create_package_orders_fields') ) {

	function wp_dp_create_package_orders_fields($key, $param) {
		global $post, $wp_dp_html_fields, $wp_dp_form_fields, $wp_dp_plugin_options;
		$wp_dp_gateway_options = get_option('wp_dp_plugin_options');
                $wp_dp_gateway_options = apply_filters('wp_dp_translate_options', $wp_dp_gateway_options );
		$wp_dp_currency_sign = wp_dp_get_currency_sign();
		$wp_dp_value = $param['title'];
		$html = '';
		switch ( $param['type'] ) {
			case 'text' :
				// prepare
				$wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $key, true);

				if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
                                        $wp_dp_value = $wp_dp_value;
				} else {
					$wp_dp_value = '';
				}

				$wp_dp_opt_array = array(
					'name' => $param['title'],
					'desc' => '',
					'hint_text' => '',
					'field_params' => array(
						'std' => $wp_dp_value,
						'id' => $key,
						'classes' => 'wp-dp-form-text wp-dp-input',
						'force_std' => true,
						'return' => true,
					),
				);
				$output = '';
				$output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
				$output .= '<span class="wp-dp-form-desc">' . $param['description'] . '</span>' . "\n";


				$html .= $output;
				break;
			case 'checkbox' :
				// prepare
				$wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $key, true);

				$wp_dp_opt_array = array(
					'name' => $param['title'],
					'desc' => '',
					'hint_text' => '',
					'field_params' => array(
						'std' => $wp_dp_value,
						'id' => $key,
						'classes' => 'wp-dp-form-text wp-dp-input',
						'force_std' => true,
						'return' => true,
					),
				);
				$output = '';
				$output .= $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

				$html .= $output;
				break;
			case 'textarea' :
				// prepare
				$wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $key, true);
				if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
					$wp_dp_value = $wp_dp_value;
				} else {
					$wp_dp_value = '';
				}

				$wp_dp_opt_array = array(
					'name' => $param['title'],
					'desc' => '',
					'hint_text' => '',
					'field_params' => array(
						'std' => '',
						'id' => $key,
						'return' => true,
					),
				);

				$output = $wp_dp_html_fields->wp_dp_textarea_field($wp_dp_opt_array);
				$html .= $output;
				break;
			case 'select' :
				$wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $key, true);
				if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
					$wp_dp_value = $wp_dp_value;
				} else {
					$wp_dp_value = '';
				}
				$wp_dp_classes = '';
				if ( isset($param['classes']) && $param['classes'] != "" ) {
					$wp_dp_classes = $param['classes'];
				}
				$wp_dp_opt_array = array(
					'name' => $param['title'],
					'desc' => '',
					'hint_text' => '',
					'field_params' => array(
						'std' => '',
						'id' => $key,
						'classes' => $wp_dp_classes,
						'options' => $param['options'],
						'return' => true,
					),
				);

				$output = $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
				// append
				$html .= $output;
				break;
			case 'hidden_label' :
				// prepare
				$wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $key, true);

				if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
					$wp_dp_value = $wp_dp_value;
				} else {
					$wp_dp_value = '';
				}

				$wp_dp_opt_array = array(
					'name' => $param['title'],
					'hint_text' => '',
				);
				$output = $wp_dp_html_fields->wp_dp_opening_field($wp_dp_opt_array);

				$output .= '<span>#' . $wp_dp_value . '</span>';

				$output .= $wp_dp_form_fields->wp_dp_form_hidden_render(
						array(
							'name' => '',
							'id' => $key,
							'return' => true,
							'classes' => '',
							'std' => $wp_dp_value,
							'description' => '',
							'hint' => ''
						)
				);

				$wp_dp_opt_array = array(
					'desc' => '',
				);
				$output .= $wp_dp_html_fields->wp_dp_closing_field($wp_dp_opt_array);
				$html .= $output;
				break;
			case 'trans_dynamic' :
				$wp_dp_trans_dynamic = get_post_meta($post->ID, "wp_dp_transaction_dynamic", true);
				
				if(is_array($wp_dp_trans_dynamic) && sizeof($wp_dp_trans_dynamic) > 0) {
					$wp_dp_opt_array = array(
						'name' => $param['title'],
						'hint_text' => '',
					);
					$output = $wp_dp_html_fields->wp_dp_opening_field($wp_dp_opt_array);
					
					foreach($wp_dp_trans_dynamic as $trans_dynamic){
						if(isset($trans_dynamic['field_type']) && isset($trans_dynamic['field_label']) && isset($trans_dynamic['field_value'])) {
							$d_type = $trans_dynamic['field_type'];
							$d_label = $trans_dynamic['field_label'];
							$d_value = $trans_dynamic['field_value'];
							if ($d_type == 'single-choice') {
								$d_value = $d_value == 'on' ? wp_dp_plugin_text_srt( 'wp_dp_listing_yes' ) : wp_dp_plugin_text_srt( 'wp_dp_listing_no' );
							}
							
							$output .= '<div class="col-md-3"><strong>'.$d_label.'</strong></div><div class="col-md-8">'.$d_value.'</div><br><hr>' . "\n";
						}
					}
	
					$wp_dp_opt_array = array(
						'desc' => '',
					);
					$output .= $wp_dp_html_fields->wp_dp_closing_field($wp_dp_opt_array);
					
					$html .= $output;
				}
				
				break;
			case 'extra_features' :
				// prepare
				$wp_dp_listing_ids = get_post_meta($post->ID, "wp_dp_listing_ids", true);
				$wp_dp_featured_ids = get_post_meta($post->ID, "wp_dp_featured_ids", true);
				$wp_dp_top_cat_ids = get_post_meta($post->ID, "wp_dp_top_cat_ids", true);
				
				$output = '';
				
				$output .= '<div class="form-elements">';
				
				$wp_dp_post_data = '<div class="col-md-12">';
				$wp_dp_post_data .= '<h1>' . wp_dp_plugin_text_srt( 'wp_dp_package_lists_used' ) . '</h1>';
				if ( is_array($wp_dp_listing_ids) && sizeof($wp_dp_listing_ids) ) {
					$wp_dp_total_lists = get_post_meta($post->ID, "wp_dp_transaction_listings", true);
					$wp_dp_dpain_lists = (int) $wp_dp_total_lists - absint(sizeof($wp_dp_listing_ids));
					$wp_dp_dpain_lists = absint($wp_dp_dpain_lists);
					$wp_dp_post_data .= '<h2>' . sprintf( wp_dp_plugin_text_srt( 'wp_dp_package_total' ), $wp_dp_total_lists) . '</h2>';
					$wp_dp_post_data .= '<h2>' . sprintf( wp_dp_plugin_text_srt( 'wp_dp_package_used' ), absint(sizeof($wp_dp_listing_ids))) . '</h2>';
					$wp_dp_post_data .= '<h2>' . sprintf( wp_dp_plugin_text_srt( 'wp_dp_package_dpaining' ), $wp_dp_dpain_lists) . '</h2>';
					$wp_dp_post_data .= '<hr>';
					$listing_counter = 1;
					foreach ( $wp_dp_listing_ids as $id ) {
						$wp_dp_permalink = get_the_title($id) ? ' target="_blank" href="' . get_edit_post_link($id) . '"' : '';
						$wp_dp_title = get_the_title($id) ? get_the_title($id) : wp_dp_plugin_text_srt( 'wp_dp_package_removed' );
						$wp_dp_post = '<ul>';
						$wp_dp_post .= '<li><strong>' . $listing_counter . '. </strong>' . wp_dp_plugin_text_srt( 'wp_dp_package_listing_id' ) . ' : #' . $id . '</li>';
						$wp_dp_post .= '<li>' . wp_dp_plugin_text_srt( 'wp_dp_package_listing_title' ) . ' : <a' . $wp_dp_permalink . '">' . $wp_dp_title . '</a></li>';
						$wp_dp_post .= '</ul>';
						$wp_dp_post_data .= '<span>' . $wp_dp_post . '</span>';
						$listing_counter++;
					}
				} else {
					$wp_dp_post_data .= wp_dp_plugin_text_srt( 'wp_dp_package_used_yet' );
				}
				$wp_dp_post_data .= '</div>';
				
				$output .= $wp_dp_post_data;
				
				$output .= '</div>';

				$html .= $output;
				break;

			default :
				break;
		}
		return $html;
	}

}
/**
 * End Function  how to Create Transations Fields
 */