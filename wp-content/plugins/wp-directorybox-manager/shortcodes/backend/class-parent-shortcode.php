<?php

/**
 * File Type: About Shortcode
 */
if ( ! class_exists('Wp_dp_Shortcodes') ) {

	class Wp_dp_Shortcodes {

		protected $title = 'title';
		protected $sub_title = 'Sub Title';
		protected $save_text = 'save';

		public function __construct() {
			add_action('directyory_common_title', array( $this, 'directyory_common_title_call_back' ));
			add_action('directyory_common_save_btn', array( $this, 'directyory_common_save_btn_call_back' ));
		}

		protected function directyory_common_title_call_back($title) {
			global $post, $wp_dp_html_fields, $wp_dp_form_fields;
			$this->title = $title;
			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt('wp_dp_element_title'),
				'desc' => '',
				'label_desc' => wp_dp_plugin_text_srt('wp_dp_element_title_hint'),
				'echo' => true,
				'field_params' => array(
					'std' => '',
					'id' => $this->title,
					'cust_name' => $this->title . '[]',
					'return' => true,
				),
			);
			$wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
		}

		protected function directyory_common_subtitle_call_back($sub_title) {
			global $post, $wp_dp_html_fields, $wp_dp_form_fields;
			$this->sub_title = $sub_title;
			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt('wp_dp_element_sub_title'),
				'desc' => '',
				'label_desc' => wp_dp_plugin_text_srt('wp_dp_element_sub_title_hint'),
				'echo' => true,
				'field_params' => array(
					'std' => '',
					'id' => $this->sub_title,
					'cust_name' => $this->sub_title . '[]',
					'return' => true,
				),
			);
			$wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
		}

		protected function directyory_common_save_btn_call_back($ave_text) {
			$this->save_text = $ave_text;
			$wp_dp_opt_array = array(
				'name' => '',
				'desc' => '',
				'label_desc' => '',
				'echo' => true,
				'field_params' => array(
					'std' => wp_dp_plugin_text_srt('wp_dp_save'),
					'cust_id' => '',
					'cust_type' => 'button',
					'classes' => 'cs-admin-btn',
					'cust_name' => '',
					'extra_atr' => 'onclick="javascript:_removerlay(jQuery(this))"',
					'return' => true,
				),
			);

			$wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
		}

	}

}
