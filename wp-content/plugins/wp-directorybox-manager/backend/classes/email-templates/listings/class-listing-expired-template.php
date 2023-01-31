<?php

/**
 * Listing Expired Email Template.
 *
 * @since 1.0
 * @package	Directory Box
 */
if ( ! class_exists( 'Wp_dp_listing_expired_email_template' ) ) {

	class Wp_dp_listing_expired_email_template {

		public $email_template_type;
		public $email_default_template;
		public $email_template_variables;
		public $template_type;
		public $email_template_index;
		public $user;
		public $listing_id;
		public $is_email_sent;
		public static $is_email_sent1;
		public $template_group;

		public function __construct() {
			$this->user = array();
			$this->email_template_type = 'Listing Expired Notification to Listing Owner';

			$this->email_default_template = $this->get_template_content();

			$this->email_template_variables = array(
				array(
					'tag' => 'LISTING_USER_NAME',
					'display_text' => 'Listing Member Name',
					'value_callback' => array( $this, 'get_listing_member_name' ),
				),
				array(
					'tag' => 'LISTING_USER_EMAIL',
					'display_text' => 'Listing Member Email',
					'value_callback' => array( $this, 'get_listing_member_email' ),
				),
				array(
					'tag' => 'LISTING_TITLE',
					'display_text' => 'Listing Title',
					'value_callback' => array( $this, 'get_listing_title' ),
				),
				array(
					'tag' => 'LISTING_LINK',
					'display_text' => 'Listing Link',
					'value_callback' => array( $this, 'get_listing_link' ),
				),
				array(
					'tag' => 'LISTING_POSTED_ON',
					'display_text' => 'Listing Posted On',
					'value_callback' => array( $this, 'get_listing_posted_on' ),
				),
				array(
					'tag' => 'LISTING_PACKAGE_NAME',
					'display_text' => 'Listing Package Name',
					'value_callback' => array( $this, 'get_listing_package_name' ),
				),
			);
			$this->template_group = 'Listing';

			$this->email_template_index = 'listing-expired-template';
			add_action( 'init', array( $this, 'add_email_template' ), 13 );
			add_filter( 'wp_dp_email_template_settings', array( $this, 'template_settings_callback' ), 12, 1 );
			add_action( 'wp_dp_listing_expired_email', array( $this, 'wp_dp_listing_expired_email_callback' ), 10, 2 );
		}

		public function wp_dp_listing_expired_email_callback( $user = '', $listing_id = '' ) {
			$this->user = $user;
			$this->listing_id = $listing_id;
			$template = $this->get_template();
			// checking email notification is enable/disable
			if ( isset( $template['email_notification'] ) && $template['email_notification'] == 1 ) {

				$blogname = get_option( 'blogname' );
				$admin_email = get_option( 'admin_email' );
				// getting template fields
				$subject = (isset( $template['subject'] ) && $template['subject'] != '' ) ? $template['subject'] : wp_dp_plugin_text_srt( 'wp_dp_listing_expired_email_subject' );
				$from = (isset( $template['from'] ) && $template['from'] != '') ? $template['from'] : esc_attr( $blogname ) . ' <' . $admin_email . '>';
				$recipients = (isset( $template['recipients'] ) && $template['recipients'] != '') ? $template['recipients'] : $this->get_listing_member_email();
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
				Wp_dp_listing_expired_email_template::$is_email_sent1 = $this->is_email_sent;
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
				'description' => wp_dp_plugin_text_srt( 'wp_dp_listing_expired_email' ),
				'subject' => wp_dp_plugin_text_srt( 'wp_dp_listing_expired_email_subject' ),
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

		function get_listing_member_name() {
			$member_id = get_post_meta( $this->listing_id, 'wp_dp_listing_member', true );
			$member_name = esc_html(get_the_title($member_id));
			return ( $member_name != '' ) ? $member_name : '-';
		}

		function get_listing_member_email() {
			$member_id = get_post_meta( $this->listing_id, 'wp_dp_listing_member', true );
			$user_id = get_post_meta( $this->listing_id, 'wp_dp_listing_username', true );
			
			
			if ( isset( $member_id ) && $member_id != '' ) {
				$member_email = get_post_meta( $member_id, 'wp_dp_email_address', true );
			}
			
			if ( isset( $user_id ) && $user_id != '' ) {
				$member_info = get_user_by( 'id', $user_id );
				$user_email = isset($member_info->user_email) ? $member_info->user_email : '';
			}
			$listing_member_email = $member_email != '' ? $member_email : $user_email;
			return $listing_member_email;
		}

		function get_listing_title() {
			$listing_title = esc_html(get_the_title( $this->listing_id ));
			return ( $listing_title != '' ) ? $listing_title : '-';
		}
		
		function get_listing_link() {
			$listing_link = esc_url(get_permalink( $this->listing_id ));
			return ( $listing_link != '' ) ? $listing_link : '#';
		}

		function get_listing_posted_on() {
			$date_format = get_option( 'date_format' );
			$wp_dp_listing_posted = get_post_meta( $this->listing_id, 'wp_dp_listing_posted', true );
			$wp_dp_listing_posted_date = isset( $wp_dp_listing_posted ) && $wp_dp_listing_posted != '' ? date_i18n( $date_format, ($wp_dp_listing_posted ) ) : '-';
			return $wp_dp_listing_posted_date;
		}

		function get_listing_package_name() {
			$wp_dp_listing_package = get_post_meta( $this->listing_id, 'wp_dp_listing_package', true );
			$response = get_the_title( $wp_dp_listing_package );
			return ( isset( $response ) && $response != '' ) ? $response : '-';
		}
		
		public function get_template_content() {
			$html = '';
			ob_start();
			?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
				<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0"/></head>
				<body style="margin: 0; padding: 0;">
					<div style="background-color: #eeeeef; padding: 50px 0;">
						<table style="max-width: 640px;" border="0" cellspacing="0" cellpadding="0" align="center">
							<tbody>
								<tr>
									<td style="padding: 40px 30px 30px 30px;" align="center" bgcolor="#33333e">
										<h1 style="color: #fff;">Listing Expired Notification to Listing Owner</h1>
									</td>
								</tr>
								<tr>
									<td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
										<table border="0" cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td width="260" valign="top">
													<table border="0" cellpadding="0" cellspacing="0" width="100%">
														<tr>
															<td>Dear [LISTING_USER_NAME]</td>
														</tr>
														<tr>
															<td style="padding-bottom: 8px;">Thank you for working with [SITE_NAME]. One of your listing is near expiring. Please renew the listing otherwise it will no longer be available. Listing detail is as follows;</td>
														</tr>
														<tr>
															<td style="padding-bottom: 8px;">Listing Title: <a href="[LISTING_LINK]">[LISTING_TITLE]</a></td>
														</tr>
														<tr>
															<td style="padding-bottom: 8px;">Listing Posted On: <a href="[LISTING_LINK]">[LISTING_POSTED_ON]</a></td>
														</tr>
														<tr>
															<td style="padding-bottom: 8px;">Listing Package Name: <a href="[LISTING_LINK]">[LISTING_PACKAGE_NAME]</a></td>
														</tr>
														<tr>
															<td style="padding-bottom: 8px;">Thank you</td>
														</tr>
														<tr>
															<td style="padding-bottom: 8px;">[SITE_NAME]</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td style="background-color: #ffffff; padding: 30px 30px 30px 30px;">
										<table border="0" width="100%" cellspacing="0" cellpadding="0">
											<tbody>
												<tr>
													<td style="font-family: Arial, sans-serif; font-size: 14px;">&reg; [SITE_NAME], 2017</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</body>
			</html>
			<?php
			$html = ob_get_clean();
			return $html;
		}

	}

	new Wp_dp_listing_expired_email_template();
}
