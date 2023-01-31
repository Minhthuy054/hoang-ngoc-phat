<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Claims_Meta_Boxes' ) ) {

	class Claims_Meta_Boxes {

		public function __construct() {
//			exit;
			add_action( 'add_meta_boxes', array( $this, 'claims_meta_box_callback' ) );
		}

		public function claims_meta_box_callback() {
			add_meta_box( 'claims_meta_box', wp_dp_plugin_text_srt( 'wp_dp_claims_name' ), array( $this, 'claims_meta_box_callback_func' ), 'wp_dp_claims', 'normal', 'high' );
		}

		public function claims_meta_box_callback_func() {
			global $post, $wp_dp_html_fields, $wp_dp_form_fields;

			$post_id = get_the_id();
			
			$wp_dp_claimer_on     = get_post_meta( $post_id, 'wp_dp_claimer_on', true );
			$wp_dp_claimer_name   = get_post_meta( $post_id, 'wp_dp_claimer_name', true );
			$wp_dp_claimer_email  = get_post_meta( $post_id, 'wp_dp_claimer_email', true );
			$wp_dp_claimer_reason = get_post_meta( $post_id, 'wp_dp_claimer_reason', true );
			$wp_dp_claim_type     = get_post_meta( $post_id, 'wp_dp_claim_type', true );
			$wp_dp_claim_action   = get_post_meta( $post_id, 'wp_dp_claim_action', true );
			
			if( $wp_dp_claim_type == 'claim' ){
				$claim_id_label = wp_dp_plugin_text_srt( 'wp_dp_claim_id' );
			}else{
				$claim_id_label = wp_dp_plugin_text_srt( 'wp_dp_flag_id' );
			}
			
			$wp_dp_opt_array = array(
				'name' => esc_html($claim_id_label),
				'desc' => '',
				'hint_text' => '',
				'echo' => true,
				'field_params' => array(
					'std' => $post_id,
					'id' => 'wp_dp_claimecase_id',
					'cust_name' => 'wp_dp_claimecase_id',
					'return' => true,
				),
			);
			$wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );
			
			
			
			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_claim_on' ),
				'desc' => '',
				'hint_text' => wp_dp_plugin_text_srt( 'wp_dp_claim_on_desc' ),
				'echo' => true,
				'field_params' => array(
					'std' => $wp_dp_claimer_on,
					'id' => 'wp_dp_claim_on',
					'cust_name' => 'wp_dp_claimer_on',
					'return' => true,
				),
			);
			$wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );
			
			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_claim_user_name' ),
				'desc' => '',
				'hint_text' => wp_dp_plugin_text_srt( 'wp_dp_claim_user_name_desc' ),
				'echo' => true,
				'field_params' => array(
					'std' => $wp_dp_claimer_name,
					'id' => 'wp_dp_claimer_name',
					'cust_name' => 'wp_dp_claimer_name',
					'return' => true,
				),
			);

			$wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );
			
			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_claim_user_email' ),
				'desc' => '',
				'hint_text' => wp_dp_plugin_text_srt( 'wp_dp_claim_user_email_desc' ),
				'echo' => true,
				'field_params' => array(
					'std' => $wp_dp_claimer_email,
					'id' => 'wp_dp_claimer_email',
					'cust_name' => 'wp_dp_claimer_email',
					'return' => true,
				),
			);

			$wp_dp_html_fields->wp_dp_text_field( $wp_dp_opt_array );
			
			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_claim_reason' ),
				'desc' => '',
				'hint_text' => wp_dp_plugin_text_srt( 'wp_dp_claim_reason_hint' ),
				'echo' => true,
				'field_params' => array(
					'std' => $wp_dp_claimer_reason,
					'id' => 'wp_dp_claimer_reason',
					'cust_name' => 'wp_dp_claimer_reason',
					'return' => true,
				),
			);

			$wp_dp_html_fields->wp_dp_textarea_field( $wp_dp_opt_array );
			
			$type = array( 'flag' => wp_dp_plugin_text_srt( 'wp_dp_claim_flag_listing' ), 'claim' => wp_dp_plugin_text_srt( 'wp_dp_claim_listing' ) );
			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_claim_Type' ),
				'desc' => '',
				'hint_text' => wp_dp_plugin_text_srt( 'wp_dp_claim_Type_hint' ),
				'echo' => true,
				'field_params' => array(
					'std' => $wp_dp_claim_type,
					'id' => 'wp_dp_claim_type',
					'cust_name' => 'wp_dp_claim_type',
					'return' => true,
					'options' => $type,
				),
			);
			$wp_dp_html_fields->wp_dp_select_field( $wp_dp_opt_array );
			
			$actions = array( 'pending' => wp_dp_plugin_text_srt( 'wp_dp_claim_status_pending' ), 'resolved' => wp_dp_plugin_text_srt( 'wp_dp_claim_status_resolved' ), );
			$wp_dp_opt_array = array(
				'name' => wp_dp_plugin_text_srt( 'wp_dp_claim_action' ),
				'desc' => '',
				'hint_text' => wp_dp_plugin_text_srt( 'wp_dp_claim_action_hint' ),
				'echo' => true,
				'field_params' => array(
					'std' => $wp_dp_claim_action,
					'id' => 'wp_dp_claim_action',
					'cust_name' => 'wp_dp_claim_action',
					'return' => true,
					'options' => $actions,
				),
			);
			$wp_dp_html_fields->wp_dp_select_field( $wp_dp_opt_array );
		}

	}

	global $Claims_Meta_Boxes;
	$Claims_Meta_Boxes = new Claims_Meta_Boxes();
}