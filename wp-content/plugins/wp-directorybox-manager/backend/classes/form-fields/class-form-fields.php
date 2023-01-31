<?php

/**
 * File Type: Form Fields
 */
if ( ! class_exists('wp_dp_form_fields') ) {

	class wp_dp_form_fields {

		private $counter = 0;

		public function __construct() {

			// Do something...
		}

		/**
		 * @ render label
		 */
		public function wp_dp_form_text_render($params = array()) {

			global $post, $pagenow, $user;

			if ( isset($params) && is_array($params) ) {
				extract($params);
			}
			$wp_dp_output = '';
			$prefix_enable = 'true'; // default value of prefix add in name and id
			if ( ! isset($id) ) {
				$id = '';
			}
			if ( ! isset($std) ) {
				$std = '';
			}

			if ( isset($prefix_on) ) {
				$prefix_enable = $prefix_on;
			}

			$prefix = 'wp_dp_'; // default prefix
			if ( isset($field_prefix) && $field_prefix != '' ) {
				$prefix = $field_prefix;
			}
			if ( $prefix_enable != true ) {
				$prefix = '';
			}
			if ( $pagenow == 'post.php' ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_post_meta($post->ID, $id, true);
				} else {
					$wp_dp_value = get_post_meta($post->ID, $prefix . $id, true);
				}
			} elseif ( isset($usermeta) && $usermeta == true ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_the_author_meta($id, $user->ID);
				} else {
					if ( isset($id) && $id != '' ) {
						$wp_dp_value = get_the_author_meta('wp_dp_' . $id, $user->ID);
					}
				}
			} else {
				$wp_dp_value = isset($std) ? $std : '';
			}
			if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
				$value = $wp_dp_value;
			} else {
				$value = $std;
			}

			if ( isset($force_std) && $force_std == true ) {
				$value = $std;
			}
			
			$wp_dp_rand_id = time();

			if ( isset($rand_id) && $rand_id != '' ) {
				$wp_dp_rand_id = $rand_id;
			}

			$html_id = ' id="' . $prefix . sanitize_html_class($id) . '"';

			if ( isset($cus_field) && $cus_field == true ) {
				$html_name = ' name="' . $prefix . 'cus_field[' . sanitize_html_class($id) . ']"';
			} else {
				$html_name = ' name="' . $prefix . sanitize_html_class($id) . '"';
			}

			if ( isset($array) && $array == true ) {
				$html_id = ' id="' . $prefix . sanitize_html_class($id) . $wp_dp_rand_id . '"';
				$html_name = ' name="' . $prefix . sanitize_html_class($id) . '_array[]"';
			}

			if ( isset($cust_id) && $cust_id != '' ) {
				$html_id = ' id="' . $cust_id . '"';
			}

			if ( isset($cust_name) && $cust_name != '' ) {
				$html_name = ' name="' . $cust_name . '"';
			}
			
			if ( isset($cust_name) && $cust_name == '' ) {
                $html_name = '';
            }

			// Disabled Field
			$wp_dp_visibilty = '';
			if ( isset($active) && $active == 'in-active' ) {
				$wp_dp_visibilty = 'readonly="readonly"';
			}

			$wp_dp_required = '';
			if ( isset($required) && $required == 'yes' ) {
				$wp_dp_required = ' required';
			}

			$wp_dp_classes = '';
			if ( isset($classes) && $classes != '' ) {
				$wp_dp_classes = ' class="' . $classes . '"';
			}
			$extra_atributes = '';
			if ( isset($extra_atr) && $extra_atr != '' ) {
				$extra_atributes = $extra_atr;
			}

			$wp_dp_input_type = 'text';
			if ( isset($cust_type) && $cust_type != '' ) {
				$wp_dp_input_type = $cust_type;
			}

			$wp_dp_before = '';
			if ( isset($before) && $before != '' ) {
				$wp_dp_before = '<div class="' . $before . '">';
			}

			$wp_dp_after = '';
			if ( isset($after) && $after != '' ) {
				$wp_dp_after = $after;
			}

			if ( $html_id == ' id=""' || $html_id == ' id="wp_dp_"' ) {
				$html_id = '';
			}

			if ( isset($rang) && $rang == true && isset($min) && isset($max) ) {
				$wp_dp_output .= '<div class="cs-drag-slider" data-slider-min="' . $min . '" data-slider-max="' . $max . '" data-slider-step="1" data-slider-value="' . $value . '">';
			}
			$wp_dp_output .= $wp_dp_before;
			if ( $value != '' ) {
				$wp_dp_output .= '<input type="' . $wp_dp_input_type . '" ' . $wp_dp_visibilty . $wp_dp_required . ' ' . $extra_atributes . ' ' . $wp_dp_classes . ' ' . $html_id . $html_name . ' value="' . $value . '" />';
			} else {
				$wp_dp_output .= '<input type="' . $wp_dp_input_type . '" ' . $wp_dp_visibilty . $wp_dp_required . ' ' . $extra_atributes . ' ' . $wp_dp_classes . ' ' . $html_id . $html_name . ' />';
			}

			$wp_dp_output .= $wp_dp_after;

			if ( isset($return) && $return == true ) {
				return force_balance_tags($wp_dp_output);
			} else {
				echo force_balance_tags($wp_dp_output);
			}
		}

		/**
		 * @ render Radio field
		 */
		public function wp_dp_form_radio_render($params = '') {
			global $post, $user, $pagenow;
			extract($params);

			$wp_dp_output = '';

			if ( ! isset($id) ) {
				$id = '';
			}

			$prefix_enable = 'true'; // default value of prefix add in name and id

			if ( isset($prefix_on) ) {
				$prefix_enable = $prefix_on;
			}

			$prefix = 'wp_dp_'; // default prefix
			if ( isset($field_prefix) && $field_prefix != '' ) {
				$prefix = $field_prefix;
			}
			if ( $prefix_enable != true ) {
				$prefix = '';
			}

			if ( $pagenow == 'post.php' ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_post_meta($post->ID, $id, true);
				} else {
					$wp_dp_value = get_post_meta($post->ID, $prefix . $id, true);
				}
			} elseif ( isset($usermeta) && $usermeta == true ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_the_author_meta($id, $user->ID);
				} else {
					if ( isset($id) && $id != '' ) {
						$wp_dp_value = get_the_author_meta('wp_dp_' . $id, $user->ID);
					}
				}
			} else {
				$wp_dp_value = isset($std) ? $std : '';
			}

			if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
				$value = $wp_dp_value;
			} else {
				$value = $std;
			}

			if ( isset($cus_field) && $cus_field == true ) {
				$html_name = ' name="' . $prefix . 'cus_field[' . sanitize_html_class($id) . ']"';
			} else {
				$html_name = ' name="' . $prefix . sanitize_html_class($id) . '"';
			}

			if ( isset($array) && $array == true ) {
				$html_id = ' id="' . $prefix . sanitize_html_class($id) . $wp_dp_rand_id . '"';
				$html_name = ' name="' . $prefix . sanitize_html_class($id) . '_array[]"';
			}

			if ( isset($cust_id) && $cust_id != '' ) {
				$html_id = ' id="' . $cust_id . '"';
			}

			if ( isset($cust_name) ) {
				$html_name = ' name="' . $cust_name . '"';
			}

			$html_id = isset($html_id) ? $html_id : '';

			// Disbaled Field
			$wp_dp_visibilty = '';
			if ( isset($active) && $active == 'in-active' ) {
				$wp_dp_visibilty = 'readonly="readonly"';
			}
			$wp_dp_required = '';
			if ( isset($required) && $required == 'yes' ) {
				$wp_dp_required = ' required';
			}
			$wp_dp_classes = '';
			if ( isset($classes) && $classes != '' ) {
				$wp_dp_classes = ' class="' . $classes . '"';
			}

			$extra_atributes = '';
			if ( isset($extra_atr) && $extra_atr != '' ) {
				$extra_atributes = $extra_atr;
			}

			if ( $html_id == ' id=""' || $html_id == ' id="wp_dp_"' ) {
				$html_id = '';
			}

			$wp_dp_output .= '<input type="radio" ' . $wp_dp_visibilty . $wp_dp_required . ' ' . $wp_dp_classes . ' ' . $extra_atributes . ' ' . $html_id . $html_name . ' value="' . ($value) . '" />';

			if ( isset($return) && $return == true ) {
				return force_balance_tags($wp_dp_output);
			} else {
				echo force_balance_tags($wp_dp_output);
			}
		}

		/**
		 * @ render Radio field
		 */
		public function wp_dp_form_hidden_render($params = '') {
			global $post, $pagenow;
			extract($params);

			$wp_dp_rand_id = time();

			if ( ! isset($id) ) {
				$id = '';
			}
			$html_id = '';
			$html_id = ' id="wp_dp_' . sanitize_html_class($id) . '"';
			$html_name = ' name="wp_dp_' . sanitize_html_class($id) . '"';

			if ( isset($array) && $array == true ) {
				$html_name = ' name="wp_dp_' . sanitize_html_class($id) . '_array[]"';
			}

			if ( isset($cust_id) && $cust_id != '' ) {
				$html_id = ' id="' . $cust_id . '"';
			}

			if ( isset($cust_name) ) {
				$html_name = ' name="' . $cust_name . '"';
			}

			$wp_dp_classes = '';
			if ( isset($classes) && $classes != '' ) {
				$wp_dp_classes = ' class="' . $classes . '"';
			}

			$extra_atributes = '';
			if ( isset($extra_atr) && $extra_atr != '' ) {
				$extra_atributes = $extra_atr;
			}

			if ( $html_id == ' id=""' || $html_id == ' id="wp_dp_"' ) {
				$html_id = '';
			}

			$wp_dp_output = '<input type="hidden" ' . $html_id . ' ' . $wp_dp_classes . ' ' . $extra_atributes . ' ' . $html_name . ' value="' . sanitize_text_field($std) . '" />';
			if ( isset($return) && $return == true ) {
				return force_balance_tags($wp_dp_output);
			} else {
				echo force_balance_tags($wp_dp_output);
			}
		}

		/**
		 * @ render Date field
		 */
		public function wp_dp_form_date_render($params = '') {
			global $post, $pagenow;
			extract($params);

			$wp_dp_output = '';

			$wp_dp_format = 'd-m-Y';
			$prefix_enable = 'true'; // default value of prefix add in name and id

			if ( isset($prefix_on) ) {
				$prefix_enable = $prefix_on;
			}

			$prefix = 'wp_dp_'; // default prefix
			if ( isset($field_prefix) && $field_prefix != '' ) {
				$prefix = $field_prefix;
			}
			if ( $prefix_enable != true ) {
				$prefix = '';
			}
			$wp_dp_classes = '';
			if ( isset($classes) && $classes != '' ) {
				$wp_dp_classes = ' class="' . $classes . '"';
			}
			$extra_atributes = '';
			if ( isset($extra_atr) && $extra_atr != '' ) {
				$extra_atributes = $extra_atr;
			}

			if ( isset($format) && $format != '' ) {
				$wp_dp_format = $format;
			}
			$wp_dp_value = '';
			if ( $pagenow == 'post.php' ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_post_meta($post->ID, $id, true);
				} else {
					$wp_dp_value = get_post_meta($post->ID, $prefix . $id, true);
				}
				if ( isset($strtotime) && $strtotime == true ) {
					
				}
			} elseif ( isset($usermeta) && $usermeta == true ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_the_author_meta($id, $user->ID);
				} else {
					if ( isset($id) && $id != '' ) {
						$wp_dp_value = get_the_author_meta('wp_dp_' . $id, $user->ID);
					}
				}

				if ( isset($strtotime) && $strtotime == true ) {
					
				}
			} else {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_post_meta($post->ID, $id, true);
				} else {
					if ( isset($strtotime) && $strtotime == true ) {
						$wp_dp_value = isset($post->ID) ? get_post_meta((int) $post->ID, 'wp_dp_' . $id, true) : '';
					} else {
						$wp_dp_value = isset($post->ID) ? get_post_meta($post->ID, 'wp_dp_' . $id, true) : '';
					}
				}
			}

			if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
				if ( isset($strtotime) && $strtotime == true ) {
					$wp_dp_value = date($wp_dp_format, (int) $wp_dp_value);
				}
				$value = $wp_dp_value;
			} elseif ( isset($std) && $std != '' ) {
				$value = $std;
			} else {
				$value = date($wp_dp_format);
			}

			if ( isset($force_std) && $force_std == true ) {
				$value = $std;
			}


			$wp_dp_required = '';
			if ( isset($required) && $required == 'yes' ) {
				$wp_dp_required = ' required';
			}
			// disable attribute
			$wp_dp_disabled = '';
			if ( isset($disabled) && $disabled == 'yes' ) {
				$wp_dp_disabled = ' disabled="disabled"';
			}

			$wp_dp_visibilty = '';
			if ( isset($active) && $active == 'in-active' ) {
				$wp_dp_visibilty = 'readonly="readonly"';
			}

			if ( isset($force_std) && $force_std == true ) {
				$value = $std;
			}

			$wp_dp_rand_id = time();
			if ( isset($rand_id) && $rand_id != '' ) {
				$wp_dp_rand_id = $rand_id;
			}

			$html_id = ' id="' . $prefix . sanitize_html_class($id) . '"';
			if ( isset($cus_field) && $cus_field == true ) {
				$html_name = ' name="' . $prefix . 'cus_field[' . sanitize_html_class($id) . ']"';
			} else {
				$html_name = ' name="' . $prefix . sanitize_html_class($id) . '"';
			}

			$wp_dp_piker_id = $id;
			if ( isset($array) && $array == true ) {
				$html_id = ' id="' . $prefix . sanitize_html_class($id) . $wp_dp_rand_id . '"';
				$html_name = ' name="' . $prefix . sanitize_html_class($id) . '_array[]"';
				$wp_dp_piker_id = $id . $wp_dp_rand_id;
			}

			if ( $html_id == ' id=""' || $html_id == ' id="wp_dp_"' ) {
				$html_id = '';
			}

			$wp_dp_output .= '<script>
                                jQuery(function(){
                                    jQuery("#' . $prefix . $wp_dp_piker_id . '").datetimepicker({
                                        format:"' . $wp_dp_format . '",
                                        timepicker:false
                                    });
                                });
                          </script>';
			$wp_dp_output .= '<div class="input-date">';
			$wp_dp_output .= '<input type="text"' . $wp_dp_visibilty . $wp_dp_required . ' ' . $wp_dp_disabled . ' ' . $extra_atributes . ' ' . $wp_dp_classes . ' ' . $html_id . $html_name . '  value="' . sanitize_text_field($value) . '" />';
			$wp_dp_output .= '</div>';

			if ( isset($return) && $return == true ) {
				return force_balance_tags($wp_dp_output);
			} else {
				echo force_balance_tags($wp_dp_output);
			}
		}

		/**
		 * @ render Textarea field
		 */
		public function wp_dp_form_textarea_render($params = '') {
			global $post, $pagenow;
			if ( isset($params['wp_dp_editor']) ) {
				if ( $params['wp_dp_editor'] == true ) {
					$editor_class = 'wp_dp_editor' . mt_rand();
					if ( isset($params['before']) ) {
						$params['before'] .= ' ' . $editor_class;
					} else {
						$params['before'] = ' ' . $editor_class;
					}
				}
			}
			extract($params);
			$wp_dp_output = '';
			if ( ! isset($id) ) {
				$id = '';
			}
			if ( $pagenow == 'post.php' ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_post_meta($post->ID, $id, true);
				} else {
					$wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $id, true);
				}
			} elseif ( isset($usermeta) && $usermeta == true ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_the_author_meta($id, $user->ID);
				} else {
					if ( isset($id) && $id != '' ) {
						$wp_dp_value = get_the_author_meta('wp_dp_' . $id, $user->ID);
					}
				}
			} else {
				$wp_dp_value = $std;
			}
			//echo "==(".$wp_dp_value.")";

			if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
				$value = $wp_dp_value;
			} else {
				$value = $std;
			}

			$wp_dp_rand_id = time();

			if ( isset($force_std) && $force_std == true ) {
				$value = $std;
			}

			$html_id = ' id="wp_dp_' . sanitize_html_class($id) . '"';
			if ( isset($cus_field) && $cus_field == true ) {
				$html_name = ' name="wp_dp_cus_field[' . sanitize_html_class($id) . ']"';
			} else {
				$html_name = ' name="wp_dp_' . sanitize_html_class($id) . '"';
			}

			if ( isset($array) && $array == true ) {
				$html_id = ' id="wp_dp_' . sanitize_html_class($id) . $wp_dp_rand_id . '"';
				$html_name = ' name="wp_dp_' . sanitize_html_class($id) . '_array[]"';
			}

			if ( isset($cust_id) && $cust_id != '' ) {
				$html_id = ' id="' . $cust_id . '"';
			}
			$wp_dp_classes = '';
			if ( isset($classes) && $classes != '' ) {
				$wp_dp_classes = ' class="' . $classes . '"';
			}

			if ( isset($cust_name) ) {
				$html_name = ' name="' . $cust_name . '"';
			}

			$wp_dp_required = '';
			if ( isset($required) && $required == 'yes' ) {
				$wp_dp_required = ' required';
			}
			$wp_dp_before = '';
			if ( isset($before) && $before != '' ) {
				$wp_dp_before = '<div class="' . $before . '">';
			}

			$extra_atributes = '';
			if ( isset($extra_atr) && $extra_atr != '' ) {
				$extra_atributes = $extra_atr;
			}

			$wp_dp_after = '';
			if ( isset($after) && $after != '' ) {
				$wp_dp_after = '</div>';
			}

			if ( $html_id == ' id=""' || $html_id == ' id="wp_dp_"' ) {
				$html_id = '';
			}

			$wp_dp_output .= $wp_dp_before;
			$wp_dp_output .= ' <textarea' . $wp_dp_required . ' ' . $extra_atributes . ' ' . $html_id . $html_name . $wp_dp_classes . '>' . $value . '</textarea>';
			$wp_dp_output .= $wp_dp_after;
			if ( isset($params['wp_dp_editor']) ) {
				if ( $params['wp_dp_editor'] == true ) {
					$jquery = '<script>
						jQuery( document ).ready(function() {
							jQuery(".' . $editor_class . ' textarea").jqte(' . (isset($wp_dp_editor_placeholder) && $wp_dp_editor_placeholder != '' ? '{placeholder: "' . $wp_dp_editor_placeholder . '"}' : '') . ');
						});
					</script>';
				}
			}
			$wp_dp_jquery = '';
			if ( isset($jquery) && $jquery != '' ) {
				$wp_dp_jquery = $jquery;
			}
			$wp_dp_output .= $wp_dp_jquery;

			if ( isset($return) && $return == true ) {
				return force_balance_tags($wp_dp_output);
			} else {
				echo force_balance_tags($wp_dp_output);
			}
		}

		/**
		 * @ render select field
		 */
		public function wp_dp_form_select_render($params = '') {
			global $post, $pagenow;
			extract($params);
			$prefix_enable = 'true'; // default value of prefix add in name and id
			if ( ! isset($id) ) {
				$id = '';
			}
			$wp_dp_output = '';

			if ( isset($prefix_on) ) {
				$prefix_enable = $prefix_on;
			}

			$prefix = 'wp_dp_'; // default prefix
			if ( isset($field_prefix) && $field_prefix != '' ) {
				$prefix = $field_prefix;
			}
			if ( $prefix_enable != true ) {
				$prefix = '';
			}

			$wp_dp_onchange = '';

			if ( $pagenow == 'post.php' ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_post_meta($post->ID, $id, true);
				} else {
					$wp_dp_value = get_post_meta($post->ID, $prefix . $id, true);
				}
			} elseif ( isset($usermeta) && $usermeta == true ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_the_author_meta($id, $user->ID);
				} else {
					if ( isset($id) && $id != '' ) {
						$wp_dp_value = get_the_author_meta('wp_dp_' . $id, $user->ID);
					}
				}
			} else {
				$wp_dp_value = $std;
			}
			if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
				$value = $wp_dp_value;
			} else {
				$value = $std;
			}
			if ( isset($force_std) && $force_std == true ) {
				$value = $std;
			}
			$wp_dp_rand_id = time();
			if ( isset($rand_id) && $rand_id != '' ) {
				$wp_dp_rand_id = $rand_id;
			}

			$html_wraper = ' id="wrapper_' . sanitize_html_class($id) . '"';
			$html_id = ' id="' . $prefix . sanitize_html_class($id) . '"';
			if ( isset($cus_field) && $cus_field == true ) {
				$html_name = ' name="' . $prefix . 'cus_field[' . sanitize_html_class($id) . ']"';
			} else {
				$html_name = ' name="' . $prefix . sanitize_html_class($id) . '"';
			}

			if ( isset($array) && $array == true ) {
				$html_id = ' id="' . $prefix . sanitize_html_class($id) . $wp_dp_rand_id . '"';
				$html_name = ' name="' . $prefix . sanitize_html_class($id) . '_array[]"';
				$html_wraper = ' id="wrapper_' . sanitize_html_class($id) . $wp_dp_rand_id . '"';
			}

			if ( isset($cust_id) && $cust_id != '' ) {
				$html_id = ' id="' . $cust_id . '"';
			}

			if ( isset($cust_name) ) {
				$html_name = ' name="' . $cust_name . '"';
			}

			$wp_dp_display = '';
			if ( isset($status) && $status == 'hide' ) {
				$wp_dp_display = 'style=display:none';
			}

			if ( isset($onclick) && $onclick != '' ) {
				$wp_dp_onchange = 'onchange="' . $onclick . '"';
			}

			$wp_dp_visibilty = '';
			if ( isset($active) && $active == 'in-active' ) {
				$wp_dp_visibilty = 'readonly="readonly"';
			}
			$wp_dp_required = '';
			if ( isset($required) && $required == 'yes' ) {
				$wp_dp_required = ' required';
			}
			$wp_dp_classes = '';
			if ( isset($classes) && $classes != '' ) {
				$wp_dp_classes = ' class="' . $classes . '"';
			}
			$extra_atributes = '';
			if ( isset($extra_atr) && $extra_atr != '' ) {
				$extra_atributes = $extra_atr;
			}

			if ( isset($markup) && $markup != '' ) {
				$wp_dp_output .= $markup;
			}

			if ( isset($div_classes) && $div_classes <> "" ) {
				$wp_dp_output .= '<div class="' . esc_attr($div_classes) . '">';
			}

			if ( $html_id == ' id=""' || $html_id == ' id="wp_dp_"' ) {
				$html_id = '';
			}

			$wp_dp_output .= '<select ' . $wp_dp_visibilty . ' ' . $wp_dp_required . ' ' . $extra_atributes . ' ' . $wp_dp_classes . ' ' . $html_id . $html_name . ' ' . $wp_dp_onchange . ' >';
			if ( isset($options_markup) && $options_markup == true ) {
				$wp_dp_output .= $options;
			} else {
				if ( is_array($options) ) {
					foreach ( $options as $key => $option ) {
						if ( ! is_array($option)) {
                                                       $wp_dp_output .= '<option ' . selected($key, $value, false) . ' value="' . $key . '">' . $option . '</option>';
						}
					}
				}
			}
			$wp_dp_output .= '</select>';

			if ( isset($div_classes) && $div_classes <> "" ) {
				$wp_dp_output .= '</div>';
			}

			if ( isset($return) && $return == true ) {
				return force_balance_tags($wp_dp_output);
			} else {
				echo force_balance_tags($wp_dp_output);
			}
		}

		/**
		 * @ render Multi Select field
		 */
		public function wp_dp_form_multiselect_render($params = '') {
			global $post, $pagenow;
			extract($params);

			$wp_dp_output = '';

			$prefix_enable = 'true'; // default value of prefix add in name and id
			if ( isset($prefix_on) ) {
				$prefix_enable = $prefix_on;
			}

			$prefix = 'wp_dp_'; // default prefix
			if ( isset($field_prefix) && $field_prefix != '' ) {
				$prefix = $field_prefix;
			}
			if ( $prefix_enable != true ) {
				$prefix = '';
			}
			$wp_dp_onchange = '';

			if ( $pagenow == 'post.php' ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_post_meta($post->ID, $id, true);
				} else {
					$wp_dp_value = get_post_meta($post->ID, $prefix . $id, true);
				}
			} elseif ( isset($usermeta) && $usermeta == true ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_the_author_meta($id, $user->ID);
				} else {
					if ( isset($id) && $id != '' ) {
						$wp_dp_value = get_the_author_meta('wp_dp_' . $id, $user->ID);
					}
				}
			} else {
				$wp_dp_value = $std;
			}
			if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
				$value = $wp_dp_value;
			} else {
				$value = $std;
			}
			$wp_dp_rand_id = time();
			if ( isset($rand_id) && $rand_id != '' ) {
				$wp_dp_rand_id = $rand_id;
			}
			$html_wraper = '';
			if ( isset($id) && $id != '' ) {
				$html_wraper = ' id="wrapper_' . sanitize_html_class($id) . '"';
			}
			$html_id = '';
			if ( isset($id) && $id != '' ) {
				$html_id = ' id="' . $prefix . sanitize_html_class($id) . '"';
			}
			$html_name = '';
			if ( isset($cus_field) && $cus_field == true ) {
				$html_name = ' name="' . $prefix . 'cus_field[' . sanitize_html_class($id) . '][]"';
			} else {
				if ( isset($id) && $id != '' ) {
					$html_name = ' name="' . $prefix . sanitize_html_class($id) . '[]"';
				}
			}

			if ( isset($cust_id) && $cust_id != '' ) {
				$html_id = ' id="' . $cust_id . '"';
			}

			if ( isset($cust_name) ) {
				$html_name = ' name="' . $cust_name . '"';
			}
			
			if (isset($cust_name) && $cust_name == '' ) {
				$html_name = '';
			}

			$wp_dp_display = '';
			if ( isset($status) && $status == 'hide' ) {
				$wp_dp_display = 'style=display:none';
			}

			if ( isset($onclick) && $onclick != '' ) {
				$wp_dp_onchange = 'onchange="javascript:' . $onclick . '(this.value, \'' . esc_js(admin_url('admin-ajax.php')) . '\')"';
			}

			if ( ! is_array($value) && $value != '' ) {
				$value = explode(',', $value);
			}

			if ( ! is_array($value) ) {
				$value = array();
			}

			// Disbaled Field
			$wp_dp_visibilty = '';
			if ( isset($active) && $active == 'in-active' ) {
				$wp_dp_visibilty = 'readonly="readonly"';
			}
			$wp_dp_required = '';
			if ( isset($required) && $required == 'yes' ) {
				$wp_dp_required = ' required';
			}
			$wp_dp_classes = '';
			if ( isset($classes) && $classes != '' ) {
				$wp_dp_classes = ' class="multiple ' . $classes . '"';
			} else {
				$wp_dp_classes = ' class="multiple"';
			}
			$extra_atributes = '';
			if ( isset($extra_atr) && $extra_atr != '' ) {
				$extra_atributes = $extra_atr;
			}
                        
                        if ( isset($markup) && $markup != '' ) {
				$wp_dp_output .= $markup;
			}

			if ( $html_id == ' id=""' || $html_id == ' id="wp_dp_"' ) {
				$html_id = '';
			}

			$wp_dp_output .= '<select' . $wp_dp_visibilty . $wp_dp_required . ' ' . $extra_atributes . ' ' . $wp_dp_classes . ' ' . ' multiple ' . $html_id . $html_name . ' ' . $wp_dp_onchange . ' style="height:110px !important;">';

			if ( isset($options_markup) && $options_markup == true ) {
				$wp_dp_output .= $options;
			} else {
				foreach ( $options as $key => $option ) {
					$selected = '';
					if ( in_array($key, $value) ) {
						$selected = 'selected="selected"';
					}

					$wp_dp_output .= '<option ' . $selected . 'value="' . $key . '">' . $option . '</option>';
				}
			}
			$wp_dp_output .= '</select>';

			if ( isset($return) && $return == true ) {
				return force_balance_tags($wp_dp_output);
			} else {
				echo force_balance_tags($wp_dp_output);
			}
		}

		/**
		 * @ render Checkbox field         
		 */
		public function wp_dp_form_checkbox_render($params = '') {
			global $post, $pagenow;
			extract($params);
			$prefix_enable = 'true'; // default value of prefix add in name and id

			$wp_dp_output = '';

			if ( isset($prefix_on) ) {
				$prefix_enable = $prefix_on;
			}

			if ( ! isset($id) ) {
				$id = '';
			}
			$prefix = 'wp_dp_'; // default prefix
			if ( isset($field_prefix) && $field_prefix != '' ) {
				$prefix = $field_prefix;
			}
			if ( $prefix_enable != true ) {
				$prefix = '';
			}

			if ( $pagenow == 'post.php' && $id != '' ) {
				$wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $id, true);
				$value = $wp_dp_value;
			} elseif ( isset($usermeta) && $usermeta == true ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_the_author_meta($id, $user->ID);
					$value = $wp_dp_value;
				} else {
					if ( isset($id) && $id != '' ) {
						$wp_dp_value = get_the_author_meta('wp_dp_' . $id, $user->ID);
						$value = $wp_dp_value;
					}
				}
			} else {
				$wp_dp_value = $std;
				$value = $wp_dp_value;
			}

			if ( $value == '' ) {
				$value = $std;
			}
			if ( isset($force_std) && $force_std == true ) {
				$value = $std;
			}

			$wp_dp_rand_id = time();

			$html_id = ' id="' . $prefix . sanitize_html_class($id) . '"';
			$btn_name = ' name="' . $prefix . sanitize_html_class($id) . '"';

			$html_name = ' name="' . $prefix . sanitize_html_class($id) . '"';

			if ( isset($array) && $array == true ) {
				$html_id = ' id="' . $prefix . sanitize_html_class($id) . $wp_dp_rand_id . '"';
				$btn_name = ' name="' . $prefix . sanitize_html_class($id) . $wp_dp_rand_id . '"';
				$html_name = ' name="' . $prefix . sanitize_html_class($id) . '_array[]"';
			}

			if ( isset($cust_id) && $cust_id != '' ) {
				$html_id = ' id="' . $cust_id . '"';
			}

			if ( isset($cust_name) ) {
				$html_name = ' name="' . $cust_name . '"';
			}

			$checked = isset($value) && $value == 'on' ? ' checked="checked"' : '';
			// Disbaled Field
			$wp_dp_visibilty = '';
			if ( isset($active) && $active == 'in-active' ) {
				$wp_dp_visibilty = 'readonly="readonly"';
			}
			$wp_dp_required = '';
			if ( isset($required) && $required == 'yes' ) {
				$wp_dp_required = ' required';
			}
			$wp_dp_classes = '';
			if ( isset($classes) && $classes != '' ) {
				$wp_dp_classes = ' class="' . $classes . '"';
			}
			$extra_atributes = '';
			if ( isset($extra_atr) && $extra_atr != '' ) {
				$extra_atributes = $extra_atr;
			}

			if ( $html_id == ' id=""' || $html_id == ' id="wp_dp_"' ) {
				$html_id = '';
			}
			$html_data_id = str_replace('id=', 'data-id=', $html_id);
			if ( isset($simple) && $simple == true ) {
				if ( $value == '' ) {
					$wp_dp_output .= '<input type="checkbox" ' . $html_id . $html_name . ' ' . $wp_dp_classes . ' ' . $checked . ' ' . $extra_atributes . ' />';
				} else {
					$wp_dp_output .= '<input type="checkbox" ' . $html_id . $html_name . ' ' . $wp_dp_classes . ' ' . $checked . ' value="' . $value . '"' . $extra_atributes . ' />';
				}
			} else {
				if ( $value == '' ) {
					$value = 'off';
				}
				$wp_dp_output .= '<label class="pbwp-checkbox cs-chekbox">';
				$wp_dp_output .= '<input type="hidden"' . $html_id . $html_name . ' value="' . $value . '" />';
				$wp_dp_output .= '<input type="checkbox" ' . $html_data_id . ' ' . $wp_dp_classes . ' ' . $checked . ' ' . $extra_atributes . ' />';
				$wp_dp_output .= '<span class="pbwp-box"></span>';
				$wp_dp_output .= '</label>';
			}

			if ( isset($return) && $return == true ) {
				return force_balance_tags($wp_dp_output);
			} else {
				echo force_balance_tags($wp_dp_output);
			}
		}

		/**
		 * @ render Checkbox With Input Field
		 */
		public function wp_dp_form_checkbox_with_field_render($params = '') {
			global $post, $pagenow;
			extract($params);
			extract($field);
			$prefix_enable = 'true'; // default value of prefix add in name and id

			if ( isset($prefix_on) ) {
				$prefix_enable = $prefix_on;
			}

			$prefix = 'wp_dp_'; // default prefix
			if ( isset($field_prefix) && $field_prefix != '' ) {
				$prefix = $field_prefix;
			}
			if ( $prefix_enable != true ) {
				$prefix = '';
			}

			$wp_dp_value = get_post_meta($post->ID, $prefix . $id, true);
			if ( isset($usermeta) && $usermeta == true ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_the_author_meta($id, $user->ID);
				} else {
					if ( isset($id) && $id != '' ) {
						$wp_dp_value = get_the_author_meta('wp_dp_' . $id, $user->ID);
					}
				}
			}
			if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
				$value = $wp_dp_value;
			} else {
				$value = $std;
			}

			$wp_dp_input_value = get_post_meta($post->ID, $prefix . $field_id, true);
			if ( isset($wp_dp_input_value) && $wp_dp_input_value != '' ) {
				$input_value = $wp_dp_input_value;
			} else {
				$input_value = $field_std;
			}

			$wp_dp_visibilty = ''; // Disbaled Field
			if ( isset($active) && $active == 'in-active' ) {
				$wp_dp_visibilty = 'readonly="readonly"';
			}
			$wp_dp_required = '';
			if ( isset($required) && $required == 'yes' ) {
				$wp_dp_required = ' required';
			}
			$wp_dp_classes = '';
			if ( isset($classes) && $classes != '' ) {
				$wp_dp_classes = ' class="' . $classes . '"';
			}
			$extra_atributes = '';
			if ( isset($extra_atr) && $extra_atr != '' ) {
				$extra_atributes = $extra_atr;
			}

			$wp_dp_output .= '<label class="pbwp-checkbox">';
			$wp_dp_output .= $this->wp_dp_form_hidden_render(array( 'id' => $id, 'std' => '', 'type' => '', 'return' => 'return' ));
			$wp_dp_output .= '<input type="checkbox" ' . $wp_dp_visibilty . $wp_dp_required . ' ' . $extra_atributes . ' ' . $wp_dp_classes . ' ' . ' name="' . $prefix . sanitize_html_class($id) . '" id="' . $prefix . sanitize_html_class($id) . '" value="' . sanitize_text_field('on') . '" ' . checked('on', $value, false) . ' />';
			$wp_dp_output .= '<span class="pbwp-box"></span>';
			$wp_dp_output .= '</label>';
			$wp_dp_output .= '<input type="text" name="' . $prefix . sanitize_html_class($field_id) . '"  value="' . sanitize_text_field($input_value) . '">';
			$wp_dp_output .= $this->wp_dp_form_description($description);

			if ( isset($return) && $return == true ) {
				return force_balance_tags($wp_dp_output);
			} else {
				echo force_balance_tags($wp_dp_output);
			}
		}

		/**
		 * @ render File Upload field
		 */
		public function wp_dp_media_url($params = '') {
			global $post, $pagenow;
			extract($params);

			$wp_dp_output = '';

			$wp_dp_value = isset($post->ID) ? get_post_meta($post->ID, 'wp_dp_' . $id, true) : '';
			if ( isset($usermeta) && $usermeta == true ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_the_author_meta($id, $user->ID);
				} else {
					if ( isset($dp) && $dp == true ) {
						$wp_dp_value = get_the_author_meta($id, $user->ID);
					} else {
						if ( isset($id) && $id != '' ) {
							$wp_dp_value = get_the_author_meta('wp_dp_' . $id, $user->ID);
						}
					}
				}
			}
			if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
				$value = $wp_dp_value;
			} else {
				$value = $std;
			}

			$wp_dp_rand_id = time();

			if ( isset($force_std) && $force_std == true ) {
				$value = $std;
			}

			$html_id = ' id="wp_dp_' . sanitize_html_class($id) . '"';
			$html_id_btn = ' id="wp_dp_' . sanitize_html_class($id) . '_btn"';
			$html_name = ' name="wp_dp_' . sanitize_html_class($id) . '"';

			if ( isset($array) && $array == true ) {
				$html_id = ' id="wp_dp_' . sanitize_html_class($id) . $wp_dp_rand_id . '"';
				$html_id_btn = ' id="wp_dp_' . sanitize_html_class($id) . $wp_dp_rand_id . '_btn"';
				$html_name = ' name="wp_dp_' . sanitize_html_class($id) . '_array[]"';
			}

			$wp_dp_output .= '<input type="text" class="cs-form-text cs-input" ' . $html_id . $html_name . ' value="' . sanitize_text_field($value) . '" />';
			$wp_dp_output .= '<label class="cs-browse">';
			$wp_dp_output .= '<input type="button" ' . $html_id_btn . $html_name . ' class="uploadfile left" value="' . wp_dp_plugin_text_srt( 'wp_dp_form_fields_browse' ) . '"/>';
			$wp_dp_output .= '</label>';

			if ( isset($return) && $return == true ) {
				return $wp_dp_output;
			} else {
				echo force_balance_tags($wp_dp_output);
			}
		}

		/**
		 * @ render File Upload field
		 */
		public function wp_dp_form_fileupload_render($params = '') {
			global $post, $pagenow, $image_val, $wp_dp_html_fields;
			extract($params);



			$std = isset($std) ? $std : '';
			$wp_dp_output = '';
			if ( $pagenow == 'post.php' ) {

				if ( isset($dp) && $dp == true ) {
					$wp_dp_value = get_post_meta($post->ID, $id, true);
				} else {
					$wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $id, true);
				}
			} elseif ( isset($usermeta) && $usermeta == true ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_the_author_meta($id, $user->ID);
				} else {
					if ( isset($dp) && $dp == true ) {
						$wp_dp_value = get_the_author_meta($id, $user->ID);
					} else {
						if ( isset($id) && $id != '' ) {
							$wp_dp_value = get_the_author_meta('wp_dp_' . $id, $user->ID);
						}
					}
				}
			} else {
				$wp_dp_value = $std;
			}

			if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
				$value = $wp_dp_value;
				if ( isset($dp) && $dp == true ) {
					$value = wp_dp_get_img_url($wp_dp_value, 'wp_dp_media_5');
				} else {
					$value = $wp_dp_value;
				}
			} else {
				$std = ( isset($std) ) ? $std : '';
				$value = $std;
			}

			if ( isset($force_std) && $force_std == true ) {
				$value = $std;
			}

			$btn_name = ' name="wp_dp_' . sanitize_html_class($id) . '_rand"';
			$html_id = ' id="wp_dp_' . sanitize_html_class($id) . '_rand"';
			$html_name = ' name="wp_dp_' . sanitize_html_class($id) . '"';

			if ( isset($array) && $array == true ) {
				$btn_name = ' name="wp_dp_' . sanitize_html_class($id) . $wp_dp_random_id . '"';
				$html_id = ' id="wp_dp_' . sanitize_html_class($id) . $wp_dp_random_id . '"';
				$html_name = ' name="wp_dp_' . sanitize_html_class($id) . '_array[]"';
			} else if ( isset($dp) && $dp == true ) {
				$html_name = ' name="' . sanitize_html_class($id) . '"';
			}

			if ( isset($cust_name) ) {
				$html_name = ' name="' . $cust_name . '"';
			}

			if ( isset($value) && $value != '' ) {
				$display_btn = ' style="display:none !important;"';
			} else {
				$display_btn = ' style="display:block !important;"';
			}

			$wp_dp_output .= '<input' . $html_id . $html_name . 'type="hidden" class="" value="' . $value . '"/>';

			$wp_dp_output .= '<label ' . $display_btn . ' class="browse-icon"><input' . $btn_name . 'type="button" class="cs-uploadMedia left" value=' . wp_dp_plugin_text_srt( 'wp_dp_form_fields_browse' ) . ' /></label>';

			if ( isset($return) && $return == true ) {
				return force_balance_tags($wp_dp_output);
			} else {
				echo force_balance_tags($wp_dp_output);
			}
		}

		/**
		 * @ render Custom File Upload field
		 */
		public function wp_dp_form_custom_fileupload_render($params = '') {
			global $post, $pagenow, $image_val;
			extract($params);

			$wp_dp_output = '';
			if ( $pagenow == 'post.php' ) {

				if ( isset($dp) && $dp == true ) {
					$wp_dp_value = get_post_meta($post->ID, $id, true);
				} else {
					$wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $id, true);
				}
			} elseif ( isset($usermeta) && $usermeta == true ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_the_author_meta($id, $user->ID);
				} else {
					if ( isset($dp) && $dp == true ) {
						$wp_dp_value = get_the_author_meta($id, $user->ID);
					} else {
						if ( isset($id) && $id != '' ) {
							$wp_dp_value = get_the_author_meta('wp_dp_' . $id, $user->ID);
						}
					}
				}
			} else {
				$wp_dp_value = $std;
			}
			$imagename_only = '';
			if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
				$value = $wp_dp_value;
				$imagename_only = $wp_dp_value;
				if ( isset($dp) && $dp == true ) {
					$value = wp_dp_get_img_url($wp_dp_value, 'wp_dp_media_5');
				} else {
					$value = $wp_dp_value;
				}
			} else {
				$value = $std;
			}

			if ( isset($force_std) && $force_std == true ) {
				$value = $std;
			}

			$btn_name = ' name="wp_dp_' . sanitize_html_class($id) . '_media"';
			$html_id = ' id="wp_dp_' . sanitize_html_class($id) . '"';
			$html_name = ' name="wp_dp_' . sanitize_html_class($id) . '"';

			if ( isset($array) && $array == true ) {
				$btn_name = ' name="wp_dp_' . sanitize_html_class($id) . '_media' . $wp_dp_random_id . '"';
				$html_id = ' id="wp_dp_' . sanitize_html_class($id) . $wp_dp_random_id . '"';
				$html_name = ' name="wp_dp_' . sanitize_html_class($id) . '_array[]"';
			} else if ( isset($dp) && $dp == true ) {
				$html_name = ' name="' . sanitize_html_class($id) . '"';
			}

			if ( isset($cust_name) && $cust_name == true ) {
				$html_name = ' name="' . $cust_name . '"';
			}

			if ( isset($value) && $value != '' ) {
				$display_btn = ' style=display:none';
			} else {
				$display_btn = ' style=display:block';
			}

			$wp_dp_classes = '';
			if ( isset($classes) && $classes != '' ) {
				$wp_dp_classes = ' class="' . $classes . '"';
			}

			$wp_dp_output .= '<input' . $html_id . $html_name . 'type="hidden" class="" value="' . $imagename_only . '"/>';

			$wp_dp_output .= '<label' . $display_btn . ' class="browse-icon"><input' . $btn_name . 'type="file" class="' . $wp_dp_classes . '" value=' . wp_dp_plugin_text_srt( 'wp_dp_form_fields_browse' ) . ' /></label>';

			if ( isset($return) && $return == true ) {
				return force_balance_tags($wp_dp_output);
			} else {
				echo force_balance_tags($wp_dp_output);
			}
		}

		/**
		 * @ render cvupload Upload field
		 */
		public function wp_dp_form_cvupload_render($params = '') {
			global $post, $pagenow;
			extract($params);
			$wp_dp_output = '';
			if ( $pagenow == 'post.php' ) {
				$wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $id, true);
			} elseif ( isset($usermeta) && $usermeta == true ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_the_author_meta($id, $user->ID);
				} else {
					if ( isset($dp) && $dp == true ) {
						$wp_dp_value = get_the_author_meta($id, $user->ID);
					} else {
						if ( isset($id) && $id != '' ) {
							$wp_dp_value = get_the_author_meta('wp_dp_' . $id, $user->ID);
						}
					}
				}
			} else {
				$wp_dp_value = $std;
			}
			if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
				$value = $wp_dp_value;
			} else {
				$value = $std;
			}

			if ( isset($value) && $value != '' ) {
				$display = 'style=display:block';
			} else {
				$display = 'style=display:none';
			}

			$wp_dp_random_id = WP_DP_FUNCTIONS()->rand_id();

			$btn_name = ' name="' . sanitize_html_class($id) . '"';
			$html_id = ' id="' . sanitize_html_class($id) . '"';
			$html_name = ' name="wp_dp_' . sanitize_html_class($id) . '"';

			if ( isset($array) && $array == true ) {
				$btn_name = ' name="' . sanitize_html_class($id) . $wp_dp_random_id . '"';
				$html_id = ' id="' . sanitize_html_class($id) . $wp_dp_random_id . '"';
				$html_name = ' name="wp_dp_' . sanitize_html_class($id) . '_array[]"';
			}

			$wp_dp_output .= '<input' . $html_id . $html_name . 'type="hidden" class="" value="' . $value . '"/>';
			$wp_dp_output .= '<label class="browse-icon"><input' . $btn_name . 'type="button" class="cs-uploadMedia left" value="' . wp_dp_plugin_text_srt( 'wp_dp_form_fields_browse' ) . '" /></label>';

			$wp_dp_output .= '<div class="page-wrap" ' . $display . ' id="wp_dp_' . sanitize_html_class($id) . '_box">';
			$wp_dp_output .= '<div class="gal-active">';
			$wp_dp_output .= '<div class="dragareamain" style="padding-bottom:0px;">';
			$wp_dp_output .= '<ul id="gal-sortable">';
			$wp_dp_output .= '<li class="ui-state-default" id="">';
			$wp_dp_output .= '<div class="thumb-secs" id="wp_dp_' . sanitize_html_class($id) . '_img"> ' . basename($value);
			$wp_dp_output .= '<div class="gal-edit-opts"><a href="javascript:del_cv_media(\'wp_dp_' . sanitize_html_class($id) . '\', \'' . sanitize_html_class($id) . '\')" class="delete"></a> </div>';
			$wp_dp_output .= '</div>';
			$wp_dp_output .= '</li>';
			$wp_dp_output .= '</ul>';
			$wp_dp_output .= '</div>';
			$wp_dp_output .= '</div>';
			$wp_dp_output .= '</div>';


			if ( isset($return) && $return == true ) {
				return force_balance_tags($wp_dp_output);
			} else {
				echo force_balance_tags($wp_dp_output);
			}
		}

		/**
		 * @ render Random String
		 */
		public function wp_dp_generate_random_string($length = 3) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randomString = '';
			for ( $i = 0; $i < $length; $i ++ ) {
				$randomString .= $characters[rand(0, strlen($characters) - 1)];
			}
			return $randomString;
		}

		public function wp_dp_img_upload_button($params = '') {
			global $post, $pagenow, $image_val, $wp_dp_plugin_static_text;
			extract($params);

			$wp_dp_output = '';
			if ( $pagenow == 'post.php' ) {

				if ( isset($dp) && $dp == true ) {
					$wp_dp_value = get_post_meta($post->ID, $id, true);
				} else {
					$wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $id, true);
				}
			} elseif ( isset($usermeta) && $usermeta == true ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_the_author_meta($id, $user->ID);
				} else {
					if ( isset($dp) && $dp == true ) {
						$wp_dp_value = get_the_author_meta($id, $user->ID);
					} else {
						if ( isset($id) && $id != '' ) {
							$wp_dp_value = get_the_author_meta('wp_dp_' . $id, $user->ID);
						}
					}
				}
			} else {
				$wp_dp_value = $std;
			}

			if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
				$value = $wp_dp_value;
				if ( isset($dp) && $dp == true ) {
					$value = wp_dp_get_img_url($wp_dp_value, 'wp_dp_media_6');
				} else {
					$value = $wp_dp_value;
				}
			} else {
				$value = $std;
			}

			if ( isset($force_std) && $force_std == true ) {
				$value = $std;
			}

			if ( isset($value) && $value != '' ) {
				$display = 'style=display:block';
			} else {
				$display = 'style=display:none';
			}

			$wp_dp_random_id = '';
			$html_id = ' id="wp_dp_' . sanitize_html_class($id) . '"';
			if ( isset($array) && $array == true ) {
				$wp_dp_random_id = rand(12345678, 98765432);
				$html_id = ' id="wp_dp_' . sanitize_html_class($id) . $wp_dp_random_id . '"';
			}

			$btn_name = ' name="wp_dp_' . sanitize_html_class($id) . '"';
			$html_id = ' id="wp_dp_' . sanitize_html_class($id) . '"';
			$html_name = ' name="wp_dp_' . sanitize_html_class($id) . '"';

			if ( isset($array) && $array == true ) {
				$btn_name = ' name="wp_dp_' . sanitize_html_class($id) . $wp_dp_random_id . '"';
				$html_id = ' id="wp_dp_' . sanitize_html_class($id) . $wp_dp_random_id . '"';
				$html_name = ' name="wp_dp_' . sanitize_html_class($id) . '_array[]"';
			} else if ( isset($dp) && $dp == true ) {
				$html_name = ' name="' . sanitize_html_class($id) . '"';
			}
			if ( isset($cust_id) && $cust_id != '' ) {
				$html_id = ' name="' . $cust_name . '"';
			}

			if ( isset($cust_name) && $cust_name != '' ) {
				$html_name = ' name="' . $cust_name . '"';
			}

			if ( isset($value) && $value != '' ) {
				$display_btn = ' style=display:none';
			} else {
				$display_btn = ' style=display:block';
			}

			$wp_dp_output .= '<input' . $html_id . $html_name . 'type="hidden" class="" value="' . $value . '"/>';
			$wp_dp_output .= '<label' . $display_btn . ' class="browse-icon"><input' . $btn_name . 'type="button" class="cs-uploadMedia left" value=' . wp_dp_plugin_text_srt( 'wp_dp_form_fields_browse' ) . ' /></label>';
			$wp_dp_output .= '<div class="page-wrap" ' . $display . ' id="wp_dp_' . sanitize_html_class($id) . $wp_dp_random_id . '_box">';
			$wp_dp_output .= '<div class="gal-active">';
			$wp_dp_output .= '<div class="dragareamain" style="padding-bottom:0px;">';
			$wp_dp_output .= '<ul id="gal-sortable">';
			$wp_dp_output .= '<li class="ui-state-default" id="">';
			$wp_dp_output .= '<div class="thumb-secs"> <img src="' . esc_url($value) . '" id="wp_dp_' . sanitize_html_class($id) . $wp_dp_random_id . '_img" width="100" alt="" />';
			$wp_dp_output .= '<div class="gal-edit-opts"><a href="javascript:del_media(\'wp_dp_' . sanitize_html_class($id) . $wp_dp_random_id . '\')" class="delete delImgMedia"></a> </div>';
			$wp_dp_output .= '</div>';
			$wp_dp_output .= '</li>';
			$wp_dp_output .= '</ul>';
			$wp_dp_output .= '</div>';
			$wp_dp_output .= '</div>';
			$wp_dp_output .= '</div>';

			if ( isset($return) && $return == true ) {
				return force_balance_tags($wp_dp_output);
			} else {
				echo force_balance_tags($wp_dp_output);
			}
		}

		public function wp_dp_form_attachemnt_fileupload_render($params = '') {
			global $post, $pagenow, $image_val, $wp_dp_html_fields;
			extract($params);



			$std = isset($std) ? $std : '';
			$wp_dp_output = '';
			if ( $pagenow == 'post.php' ) {

				if ( isset($dp) && $dp == true ) {
					$wp_dp_value = get_post_meta($post->ID, $id, true);
				} else {
					$wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $id, true);
				}
			} elseif ( isset($usermeta) && $usermeta == true ) {
				if ( isset($cus_field) && $cus_field == true ) {
					$wp_dp_value = get_the_author_meta($id, $user->ID);
				} else {
					if ( isset($dp) && $dp == true ) {
						$wp_dp_value = get_the_author_meta($id, $user->ID);
					} else {
						if ( isset($id) && $id != '' ) {
							$wp_dp_value = get_the_author_meta('wp_dp_' . $id, $user->ID);
						}
					}
				}
			} else {
				$wp_dp_value = $std;
			}

			if ( isset($wp_dp_value) && $wp_dp_value != '' ) {
				$value = $wp_dp_value;
				if ( isset($dp) && $dp == true ) {
					$value = wp_dp_get_img_url($wp_dp_value, 'wp_dp_media_5');
				} else {
					$value = $wp_dp_value;
				}
			} else {
				$std = ( isset($std) ) ? $std : '';
				$value = $std;
			}

			if ( isset($force_std) && $force_std == true ) {
				$value = $std;
			}

			if ( isset($array) && $array == true ) {
				$wp_dp_random_id = rand(12345678, 98765432);
				$html_id = ' id="wp_dp_' . sanitize_html_class($id) . $wp_dp_random_id . '"';
			}

			$btn_name = ' name="wp_dp_' . sanitize_html_class($id) . '_rand"';
			$html_id = ' id="wp_dp_' . sanitize_html_class($id) . '_rand"';
			$html_name = ' name="wp_dp_' . sanitize_html_class($id) . '"';

			if ( isset($array) && $array == true ) {
				$btn_name = ' name="wp_dp_' . sanitize_html_class($id) . $wp_dp_random_id . '"';
				$html_id = ' id="wp_dp_' . sanitize_html_class($id) . $wp_dp_random_id . '"';
				$html_name = ' name="wp_dp_' . sanitize_html_class($id) . '_array[]"';
			} else if ( isset($dp) && $dp == true ) {
				$html_name = ' name="' . sanitize_html_class($id) . '"';
			}

			if ( isset($cust_name) ) {
				$html_name = ' name="' . $cust_name . '"';
			}

			if ( isset($value) && $value != '' ) {
				$display_btn = ' style="display:none !important;"';
			} else {
				$display_btn = ' style="display:block !important;"';
			}

			$allowd_extensions_attr = '';
			if ( isset($allowd_extensions) && $allowd_extensions != '' ) {
				$allowd_extensions_attr = ' allowd_extensions="' . $allowd_extensions . '" ';
			}

			$wp_dp_output .= '<input' . $html_id . $html_name . 'type="hidden" class="" value="' . $value . '"/>';

			$wp_dp_output .= '<label ' . $display_btn . ' class="browse-icon"><input' . $btn_name . $allowd_extensions_attr . ' type="button" class="cs-attachment-uploadMedia left" value=' . wp_dp_plugin_text_srt( 'wp_dp_form_fields_browse' ) . ' /></label>';

			if ( isset($return) && $return == true ) {
				return force_balance_tags($wp_dp_output);
			} else {
				echo force_balance_tags($wp_dp_output);
			}
		}

	}

	global $wp_dp_form_fields;
	$wp_dp_form_fields = new wp_dp_form_fields();
}
