<?php

/**
 * Review Reply Email Template.
 *
 * @since 1.0
 * @package	Directory Box
 */
if ( ! class_exists( 'Wp_dp_review_reply_email_template' ) ) {

	class Wp_dp_review_reply_email_template {

		public $email_template_type;
		public $email_default_template;
		public $email_template_variables;
		public $template_type;
		public $email_template_index;
		public $user;
		public $review_id;
		public $is_email_sent;
		public static $is_email_sent1;
		public $template_group;

		public function __construct() {
			$this->user = array();
			$this->email_template_type = 'Review Reply Notification';

			$this->email_default_template = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0"/></head><body style="margin: 0; padding: 0;"><div style="background-color: #eeeeef; padding: 50px 0;"><table style="max-width: 640px;" border="0" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td style="padding: 40px 30px 30px 30px;" align="center" bgcolor="#33333e"><h1 style="color: #fff;">Review Reply Notification</h1></td></tr><tr><td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td width="260" valign="top"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td style="padding-bottom: 8px;">Dear [REVIEW_USER_NAME]</td></tr><tr><td style="padding-bottom: 8px;">Thank you for working with [SITE_NAME]. [LISTING_OWNER_NAME] has replied against your given review. Below is detail of review;</td></tr><tr><td style="padding-bottom: 8px;">[REVIEW_LISTING_TITLE]</td></tr></table></td></tr></table></td></tr><tr><td style="background-color: #ffffff; padding: 30px 30px 30px 30px;"><table border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="font-family: Arial, sans-serif; font-size: 14px;">&reg; [SITE_NAME], 2017</td></tr></tbody></table></td></tr></tbody></table></div></body></html>';

			$this->email_template_variables = array(
				array(
					'tag' => 'REVIEW_USER_NAME',
					'display_text' => 'Review User',
					'value_callback' => array( $this, 'get_review_added_user_name' ),
				),
				array(
					'tag' => 'REVIEW_USER_EMAIL',
					'display_text' => 'Review User Email',
					'value_callback' => array( $this, 'get_review_added_email' ),
				),
				array(
					'tag' => 'REVIEW_DESCRIPTION',
					'display_text' => 'Review Description',
					'value_callback' => array( $this, 'get_review_added_description' ),
				),
				array(
					'tag' => 'REVIEW_RATING_SUMMARY',
					'display_text' => 'Review Rating Summary',
					'value_callback' => array( $this, 'get_review_added_rating_summary' ),
				),
				array(
					'tag' => 'REVIEW_OVERALL_RATING',
					'display_text' => 'Review Overall Rating',
					'value_callback' => array( $this, 'get_review_added_overall_rating' ),
				),
				array(
					'tag' => 'REVIEW_LISTING_TITLE',
					'display_text' => 'Review Listing Title',
					'value_callback' => array( $this, 'get_review_listing_title' ),
				),
				array(
					'tag' => 'LISTING_OWNER_NAME',
					'display_text' => 'Listing Owner Name',
					'value_callback' => array( $this, 'get_listing_owner_name' ),
				),
				array(
					'tag' => 'LISTING_OWNER_EMAIL',
					'display_text' => 'Listing Owner Email',
					'value_callback' => array( $this, 'get_listing_owner_email' ),
				),
			);
			$this->template_group = 'Review';

			$this->email_template_index = 'review-reply-template';
			add_action( 'init', array( $this, 'add_email_template' ), 13 );
			add_filter( 'wp_dp_email_template_settings', array( $this, 'template_settings_callback' ), 12, 1 );
			add_action( 'wp_dp_review_reply_email', array( $this, 'wp_dp_review_reply_email_callback' ), 10, 2 );
		}

		public function wp_dp_review_reply_email_callback( $user = '', $review_id = '' ) {
			
			$this->user = $user;
			$this->review_id = $review_id;
			$reciever_email = '';
			$template = $this->get_template();
			$member_email = '';
			
			$wp_dp_parent_review = get_post_meta( $review_id, 'wp_dp_parent_review', true );
			$member_id = get_post_meta( $wp_dp_parent_review, 'company_id', true ); 
			$user_id = get_post_meta( $wp_dp_parent_review, 'user_id', true );

			if ( isset( $member_id ) && $member_id != '' ) {
				$member_email = get_post_meta( $member_id, 'wp_dp_email_address', true );
			}
			if ( isset( $user_id ) && $user_id != '' ) {
				$user_info = get_userdata( $user_id );
				$user_email = $user_info->user_email;
			}

			$reciever_email = ( $member_email != '') ? $member_email : $user_email;
			// checking email notification is enable/disable
			if ( isset( $template['email_notification'] ) && $template['email_notification'] == 1 ) {

				$blogname = get_option( 'blogname' );
				$admin_email = get_option( 'admin_email' );
				// getting template fields
				$subject = (isset( $template['subject'] ) && $template['subject'] != '' ) ? $template['subject'] : wp_dp_plugin_text_srt( 'wp_dp_review_reply_notification' );
				$from = (isset( $template['from'] ) && $template['from'] != '') ? $template['from'] : $this->get_listing_owner_name() . ' <' . $this->get_listing_owner_email() . '>';
				$recipients = (isset( $template['recipients'] ) && $template['recipients'] != '') ? $template['recipients'] . ',' . $reciever_email : $reciever_email;
				$email_type = (isset( $template['email_type'] ) && $template['email_type'] != '') ? $template['email_type'] : 'html';

				$args = array(
					'to' => $recipients,
					'subject' => $subject,
					'from' => $from,
					'message' => $template['email_template'],
					'email_type' => $email_type,
					'class_obj' => $this,
				);

				do_action( 'wp_dp_send_mail', $args );
				Wp_dp_review_reply_email_template::$is_email_sent1 = $this->is_email_sent;
			}
		}

		public function add_email_template() {
			$email_templates = array();
			$email_templates[$this->template_group] = array();
			$email_templates[$this->template_group][$this->email_template_index] = array(
				'title' => $this->email_template_type,
				'template' => $this->email_default_template,
				'email_template_type' => $this->email_template_type,
				'is_recipients_enabled' => TRUE,
				'description' => wp_dp_plugin_text_srt( 'wp_dp_review_reply_notification_desc' ),
				'jh_email_type' => 'html',
			);
			do_action( 'wp_dp_load_email_templates', $email_templates );
		}

		public function template_settings_callback( $email_template_options ) {

			$email_template_options["types"][] = $this->email_template_type;

			$email_template_options["templates"][$this->email_template_type] = $this->email_default_template;

			$email_template_options["variables"][$this->email_template_type] = $this->email_template_variables;

			return $email_template_options;
		}

		public function get_template() {
			return wp_dp::get_template( $this->email_template_index, $this->email_template_variables, $this->email_default_template );
		}
		
		function get_review_added_user_name() {
			$review_user_name = '';
			
			$wp_dp_parent_review = get_post_meta( $this->review_id, 'wp_dp_parent_review', true );
			$member_id = get_post_meta( $wp_dp_parent_review, 'company_id', true );
			$review_user_name = esc_html(get_the_title( $member_id ));
			return $review_user_name;
		}

		function get_review_added_email() {
			$review_user_email = '';
			
			$wp_dp_parent_review = get_post_meta( $this->review_id, 'wp_dp_parent_review', true );
			$member_id = get_post_meta( $wp_dp_parent_review, 'company_id', true );
			if ( isset( $member_id ) && $member_id != '' ) {
				$member_email = get_post_meta( $member_id, 'wp_dp_email_address', true );
			}
			if ( isset( $this->user->user_email ) && $this->user->user_email != '' ) {
				$user_email = $this->user->user_email;
			}
			$review_user_email = $member_email != '' ? $member_email : $user_email;
			return $review_user_email;
		}

		function get_review_added_description() {
			$description = get_post_field( 'post_content', $this->review_id );
			return $description;
		}

		function get_review_listing_title() {
			$output = '';

			$listing_id = $this->get_review_listing_id();
			// If listing found.
			if ( $listing_id != '' ) {
				$output = '<a href="' . get_permalink( $listing_id ) . '">' . get_the_title($listing_id) . '</a>';
			}
			return $output;
		}

		function get_review_added_overall_rating() {
			$overall_rting = get_post_meta( $this->review_id, 'overall_rating', true );
			return ( $overall_rting . '/5' );
		}

		function get_review_added_rating_summary() {
			$output = ' ';
			$ratings = get_post_meta( $this->review_id, 'ratings', true );
			if ( is_array( $ratings ) && $ratings != '' ) {
				foreach ( $ratings as $key => $rating ) {
					$output .= $key . ': ' . $rating . '/5<br>';
				}
			}
			return $output;
		}
		
		public function get_listing_owner_name(){
			$listing_id = $this->get_review_listing_id();
			$member_id = get_post_meta( $listing_id, 'wp_dp_listing_member', true );
			return esc_html(get_the_title( $member_id ));
		}
		
		public function get_listing_owner_email(){
			$listing_user_email = '';
			
			$listing_id = $this->get_review_listing_id();
			$member_id = get_post_meta( $listing_id, 'wp_dp_listing_member', true );
			$user_id   = get_post_meta( $listing_id, 'wp_dp_listing_username', true );

			if ( isset( $member_id ) && $member_id != '' ) {
				$member_email = get_post_meta( $member_id, 'wp_dp_email_address', true );
			}
			if ( isset( $user_id ) && $user_id != '' ) {
				$user_info = get_userdata( $user_id );
				$user_email = $user_info->user_email;
			}
			$listing_user_email = $member_email != '' ? $member_email : $user_email;
			return $listing_user_email;
		}
		
		
		
		public function get_review_listing_id(){
			$post_slug = get_post_meta( $this->review_id, 'post_id', true );
			$post_slug = (is_array( $post_slug ) && isset( $post_slug[0] )) ? $post_slug[0] : $post_slug;
			
			if ( $post = get_page_by_path( $post_slug, OBJECT, 'listings' ) ){
				$listing_id = $post->ID;
			}else{
				$listing_id = 0;
			}
			return $listing_id;
		}

	}

	new Wp_dp_review_reply_email_template();
}
