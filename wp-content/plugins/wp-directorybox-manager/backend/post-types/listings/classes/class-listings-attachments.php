<?php

/**
 * File Type: Near By
 */
if ( ! class_exists( 'wp_dp_attachments' ) ) {

	class wp_dp_attachments {

		/**
		 * Start construct Functions
		 */
		public function __construct() {
			add_filter( 'wp_dp_attachemnts_admin_fields', array( $this, 'wp_dp_attachemnts_admin_fields_callback' ), 11, 2 );
			add_action( 'wp_ajax_wp_dp_files_attachments_repeating_fields', array( $this, 'wp_dp_files_attachments_repeating_fields_callback' ), 11 );
			add_action( 'save_post', array( $this, 'wp_dp_insert_file_attachments' ), 17 );
		}

		public function wp_dp_attachemnts_admin_fields_callback( $post_id, $listing_type_slug ) {
			global $wp_dp_html_fields, $post, $wp_dp_form_fields;

			$post_id = ( isset( $post_id ) && $post_id != '' ) ? $post_id : $post->ID;
			$listing_type_post = get_posts( array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type_slug", 'post_status' => 'publish' ) );
			$listing_type_id = isset( $listing_type_post[0]->ID ) ? $listing_type_post[0]->ID : 0;
			$wp_dp_full_data = get_post_meta( $listing_type_id, 'wp_dp_full_data', true );
			$html = '';
			
			$wp_dp_attachments = get_post_meta( $post_id, 'wp_dp_attachments', true );

			if ( ! isset( $wp_dp_full_data['wp_dp_attachments_options_element'] ) || $wp_dp_full_data['wp_dp_attachments_options_element'] != 'on' ) {
				return $html = '';
			}

			$html .= $wp_dp_html_fields->wp_dp_heading_render(
					array(
						'name' => wp_dp_plugin_text_srt( 'wp_dp_files_attachments' ),
						'cust_name' => 'files_attachments',
						'classes' => '',
						'std' => '',
						'description' => '',
						'hint' => '',
						'echo' => false,
					)
			);
			
			$html .= '<div id="form-elements">';
					$html .= '<div id="attachments_repeater_fields">';
					if ( isset( $wp_dp_attachments ) && is_array( $wp_dp_attachments ) ) {
						foreach ( $wp_dp_attachments as $attachments ) {
							$html .= $this->wp_dp_files_attachments_repeating_fields_callback( $attachments, $listing_type_id );
						}
					}
					$html .= '</div>';
                                        $html .= '<div class="form-elements input-element wp-dp-form-button"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><a href="javascript:void(0);" id="click-more" class="attachments_repeater_btn wp-dp-add-more cntrl-add-new-row" data-id="attachments_repeater" listing_type_id="'. $listing_type_id .'">' . wp_dp_plugin_text_srt( 'wp_dp_add_attachment' ) . '</a></div></div>';
			$html .= '</div>';

			return $html;
		}

		public function wp_dp_files_attachments_repeating_fields_callback( $data = array( '' ), $listing_type_id = '' ) {
			global $wp_dp_html_fields;
			if ( isset( $data ) && count( $data ) > 0 ) {
				extract( $data );
			}
			
			$listing_type_id = isset($_POST['listing_type_id']) ? $_POST['listing_type_id'] : $listing_type_id;
			$allowd_attachment_extensions = get_post_meta($listing_type_id, 'wp_dp_listing_allowd_attachment_extensions', true);
			$allowd_attachment_extensions = isset($allowd_attachment_extensions) ? $allowd_attachment_extensions : '';
			if (isset($allowd_attachment_extensions) && $allowd_attachment_extensions != '') {
				$allowd_attachment_extensions = implode(',', $allowd_attachment_extensions);
			}

			$html = '';
			$rand = mt_rand( 10, 200 );

			$html .= '<div id="attachments_repeater" style="display:block;" class="wp-dp-repeater-form">';
                        
                        $html .= '<a href="javascript:void(0);" class="wp-dp-element-dpove"><i class="icon-close2"></i></a>';
			
			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_attachment_title' ),
				'desc' => '',
				'hint_text' => '',
                             'label_desc' => wp_dp_plugin_text_srt('wp_dp_attachment_title_hint'),
				'echo' => false,
				'field_params' => array(
					'usermeta' => true,
					'std' => ( isset( $attachment_title ) ) ? $attachment_title : '',
					'id' => 'attachment_title' . $rand,
					'cust_name' => 'wp_dp_attachments[title][]',
					'classes' => 'repeating_field wp-dp-dev-req-field-admin',
					'return' => true,
				),
			);

			$html .= $wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );
			
			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_attachment_file' ),
				'hint_text' => '',
                                 'label_desc' => wp_dp_plugin_text_srt('wp_dp_attachment_file_hint'),
				'id' => 'attachment_file' . $rand,
				'force_std' => true,
				'std' => ( isset( $attachment_file ) ? $attachment_file : '' ),
				'field_params' => array(
					'id' => 'attachment_file' . $rand,
					'cust_name' => 'wp_dp_attachments[file][]',
					'return' => true,
					'force_std' => true,
					'allowd_extensions' => $allowd_attachment_extensions,
					'std' => ( isset( $attachment_file ) ? $attachment_file : '' ),
				),
			);
			$html .= $wp_dp_html_fields->wp_dp_upload_attachment_file_field( $wp_dp_opt_array );
                        
			$html .= '</div>';
			if ( NULL != wp_dp_get_input( 'ajax', NULL ) && wp_dp_get_input( 'ajax' ) == 'true' ) {
				echo force_balance_tags( $html );
			} else {
				return $html;
			}

			if ( NULL != wp_dp_get_input( 'die', NULL ) && wp_dp_get_input( 'die' ) == 'true' ) {
				die();
			}
		}

		public function wp_dp_insert_file_attachments( $post_id ) {
			if ( get_post_type( $post_id ) == 'listings' ) {
				if ( ! isset( $_POST['wp_dp_attachments']['file'] ) || count( $_POST['wp_dp_attachments']['file'] ) < 1 ) {
					delete_post_meta( $post_id, 'wp_dp_attachments' );
				}
			}
			if ( isset( $_POST['wp_dp_attachments']['file'] ) && count( $_POST['wp_dp_attachments']['file'] ) > 0 ) {
				foreach ( $_POST['wp_dp_attachments']['file'] as $key => $attachment ) {
					if ( count( $attachment ) > 0 ) {
						$attachment_array[] = array(
							'attachment_title' => $_POST['wp_dp_attachments']['title'][$key],
							'attachment_file' => $attachment
						);
					}
				}
				update_post_meta( $post_id, 'wp_dp_attachments', $attachment_array );
			}
		}

	}

	global $wp_dp_attachments;
	$wp_dp_attachments = new wp_dp_attachments();
}