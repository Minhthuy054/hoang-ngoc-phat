<?php
/**
 * Importing Email Templates Data
 *
 * @since 1.0
 * @package	Directory Box
 */

// Direct access not allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Email Templates Data Class.
 */
if(!class_exists('Wp_dp_Email_Templates_Data')){
class Wp_dp_Email_Templates_Data {

	/**
	 * Put hooks in place and activate.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ), 12 );
	}

	public function init() {
		if ( true != get_option( 'templates_already_created' ) ) {
			add_action( 'wp_dp_load_email_templates', array( $this, 'wp_dp_load_email_templates_data' ), 10, 1 );
		}
	}

	public function wp_dp_load_email_templates_data( $email_templates ) {
		if ( ! empty( $email_templates ) ) {
			
			foreach ( $email_templates as $group => $group_array ) {
				
				$group_id = $this->wp_dp_register_email_group( $group );
				$this->wp_dp_add_email_template_post( $group_array, $group_id );
			}
			update_option( 'templates_already_created', true );
		}
	}

	public function wp_dp_register_email_group( $group_slug ) {
		$group_name = str_replace( "_", " ", $group_slug );
		$group_id = 0;
		
		$return_data = wp_insert_term(
				$group_name, // the term 
				'email_template_group', // the taxonomy
				array(
					'slug' => $group_name,
				)
		);
		
		if ( ( ! isset( $return_data->error_data )) && isset( $return_data['term_id'] ) && $return_data['term_id'] != '' ) {
			$group_id = $return_data['term_id'];
		} else {
			if ( isset( $return_data->error_data ) ) {
				$group_id = $return_data->error_data['term_exists'];
			}
		}
		return $group_id;
	}

	public function wp_dp_add_email_template_post( $group_array, $group_id ) {
		global $wpdb;
		foreach ( $group_array as $slug => $post_data ) {
			$check = wp_dp_check_if_template_exists( $slug, 'dp-templates' );
			
			if ( false == $check ) {
				$new_template = array(
					'post_title' => wp_strip_all_tags( $post_data['title'] ),
					'post_name' => $slug,
					'post_content' => $post_data['template'],
					'post_type' => 'dp-templates',
					'post_status' => 'publish',
					'post_author' => get_current_user_id(),
				);
				$post_id = wp_insert_post( $new_template );
				update_post_meta( $post_id, 'jh_email_template_type', $post_data['email_template_type'] );
				update_post_meta( $post_id, 'jh_email_notification', 1 );
				$output = wp_set_object_terms( $post_id, $group_id, 'email_template_group' );

				update_post_meta( $post_id, 'jh_email_type', $post_data['jh_email_type'] );
				update_post_meta( $post_id, 'is_recipients_enabled', $post_data['is_recipients_enabled'] );
				update_post_meta( $post_id, 'description', $post_data['description'] );
				
				if( isset($post_data['subject']) && $post_data['subject'] != '' ){
					update_post_meta( $post_id, 'jh_subject', $post_data['subject'] );
				}
			}
		}
	}

}

$wp_dp_email_templates_data_instance = new Wp_dp_Email_Templates_Data();
}