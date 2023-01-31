<?php

/**
 * File Type: Opening Hours
 */
if ( ! class_exists('wp_dp_page_elements') ) {

	class wp_dp_page_elements {

		/**
		 * Start construct Functions
		 */
		public function __construct() {
			add_filter('wp_dp_page_elements_admin_fields', array( $this, 'wp_dp_page_elements_admin_fields_callback' ), 11, 2);
		}

		public function wp_dp_page_elements_admin_fields_callback($post_id, $listing_type_slug) {
			global $wp_dp_html_fields, $post;

			$post_id = ( isset($post_id) && $post_id != '' ) ? $post_id : $post->ID;
			$listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish' ));
			$listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
			$wp_dp_full_data = get_post_meta($listing_type_id, 'wp_dp_full_data', true);
			$html = '';

			$html .= $wp_dp_html_fields->wp_dp_heading_render(
					array(
						'name' => wp_dp_plugin_text_srt('wp_dp_listing_page_elements'),
						'cust_name' => 'page_elements',
						'classes' => '',
						'std' => '',
						'description' => '',
						'hint' => '',
						'echo' => false,
					)
			);

			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt('wp_dp_listing_page_inquire_form'),
				'desc' => '',
				'hint_text' => '',
				'echo' => false,
				'field_params' => array(
					'std' => '',
					'id' => 'inquiry_form',
					'return' => true,
				),
			);
			$html .= $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt('wp_dp_listing_page_financing_calculator'),
				'desc' => '',
				'hint_text' => '',
				'echo' => false,
				'field_params' => array(
					'std' => '',
					'id' => 'financing_calculator',
					'return' => true,
				),
			);
			$html .= $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

			if ( isset($wp_dp_full_data['wp_dp_report_spams_element']) && $wp_dp_full_data['wp_dp_report_spams_element'] == 'on' ) {

				$wp_dp_opt_array = array(
					'name' => wp_dp_plugin_text_srt('wp_dp_listing_page_report_spams'),
					'desc' => '',
					'hint_text' => '',
					'echo' => false,
					'field_params' => array(
						'std' => '',
						'id' => 'report_spams',
						'return' => true,
					),
				);
				$html .= $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
			}
			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt('wp_dp_listing_page_similar_posts'),
				'desc' => '',
				'hint_text' => '',
				'echo' => false,
				'field_params' => array(
					'std' => '',
					'id' => 'similar_posts',
					'return' => true,
				),
			);
			$html .= $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt('wp_dp_listing_page_featured_listing_image'),
				'desc' => '',
				'hint_text' => '',
				'echo' => false,
				'field_params' => array(
					'std' => '',
					'id' => 'featured_listing_image',
					'return' => true,
				),
			);
			$html .= $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

			if ( isset($wp_dp_full_data['wp_dp_claim_listing_element']) && $wp_dp_full_data['wp_dp_claim_listing_element'] == 'on' ) {

				$wp_dp_opt_array = array(
					'name' => wp_dp_plugin_text_srt('wp_dp_listing_page_claim_listing'),
					'desc' => '',
					'hint_text' => '',
					'echo' => false,
					'field_params' => array(
						'std' => '',
						'id' => 'claim_listing',
						'return' => true,
					),
				);
				$html .= $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
			}

			if ( isset($wp_dp_full_data['wp_dp_social_share_element']) && $wp_dp_full_data['wp_dp_social_share_element'] == 'on' ) {

				$wp_dp_opt_array = array(
					'name' => wp_dp_plugin_text_srt('wp_dp_listing_page_social_share'),
					'desc' => '',
					'hint_text' => '',
					'echo' => false,
					'field_params' => array(
						'std' => '',
						'id' => 'social_share',
						'return' => true,
					),
				);
				$html .= $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
			}

			if ( isset($wp_dp_full_data['wp_dp_user_reviews']) && $wp_dp_full_data['wp_dp_user_reviews'] == 'on' ) {

				$wp_dp_opt_array = array(
					'name' => wp_dp_plugin_text_srt('wp_dp_listing_page_review_ratings'),
					'desc' => '',
					'hint_text' => '',
					'echo' => false,
					'field_params' => array(
						'std' => '',
						'id' => 'reivew_ratings',
						'return' => true,
					),
				);
				$html .= $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
			}

			return $html;
		}

	}

	global $wp_dp_page_elements;
	$wp_dp_page_elements = new wp_dp_page_elements();
}