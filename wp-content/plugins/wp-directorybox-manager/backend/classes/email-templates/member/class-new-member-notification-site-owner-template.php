<?php

/**
 * New Member Notification Site Owner Email Template.
 *
 * @since 1.0
 * @package Directory Box
 */
if ( ! class_exists( 'Wp_dp_new_member_notification_site_owner_email_template' ) ) {

	class Wp_dp_new_member_notification_site_owner_email_template {

		public $email_template_type;
		public $email_default_template;
		public $email_template_variables;
		public $template_type;
		public $email_template_index;
		public $new_member_name;
		public $new_member_email;
		public $template_group;
		public $is_email_sent;

		public function __construct() {

			$this->email_template_type = 'New Member Registration Notification to Administrator';

			$this->email_default_template = $this->get_template_content();

			$this->email_template_variables = array(
				array(
					'tag' => 'NEW_MEMBER_NAME',
					'display_text' => 'New Member Name',
					'value_callback' => array( $this, 'get_new_member_name' ),
				),
				array(
					'tag' => 'NEW_MEMBER_EMAIL',
					'display_text' => 'New Member Email',
					'value_callback' => array( $this, 'get_new_member_email' ),
				),
				array(
					'tag' => 'USER_PROFILE_STATUS',
					'display_text' => 'New Member Profile Status',
					'value_callback' => array( $this, 'get_new_member_profile_status' ),
				)
			);
			$this->template_group = 'User';

			$this->email_template_index = 'new-member-notification-site-owner-template';
			add_action( 'init', array( $this, 'add_email_template' ), 13 );
			add_action( 'wp_dp_new_user_notification_site_owner', array( $this, 'new_member_notification_site_owner_callback' ), 10, 2 );
			add_filter( 'wp_dp_email_template_settings', array( $this, 'template_settings_callback' ), 12, 1 );
		}

		public function new_member_notification_site_owner_callback( $new_member_name = '', $new_member_email = '' ) {
			$this->new_member_name = $new_member_name;
			$this->new_member_email = $new_member_email;

			$template = $this->get_template();
			// checking email notification is enable/disable
			if ( isset( $template['email_notification'] ) && $template['email_notification'] == 1 ) {

				$blogname = get_option( 'blogname' );
				$admin_email = get_option( 'admin_email' );
				// getting template fields
				$subject = (isset( $template['subject'] ) && $template['subject'] != '' ) ? $template['subject'] : sprintf( wp_dp_plugin_text_srt( 'wp_dp_user_registration_email_subject' ), $blogname );
				$from = (isset( $template['from'] ) && $template['from'] != '') ? $template['from'] : esc_attr( $blogname ) . ' <' . $admin_email . '>';
				$recipients = (isset( $template['recipients'] ) && $template['recipients'] != '') ? $template['recipients'] : $admin_email;
				$email_type = (isset( $template['email_type'] ) && $template['email_type'] != '') ? $template['email_type'] : 'html';


				$args = array(
					'to' => $recipients,
					'subject' => $subject,
					'from' => $from,
					'message' => $template['email_template'],
					'email_type' => $email_type,
				);

				do_action( 'wp_dp_send_mail', $args );
			}
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

		function get_new_member_name() {
			return $this->new_member_name;
		}

		function get_new_member_email() {
			return $this->new_member_email;
		}

		public function get_new_member_profile_status() {
			$user = get_user_by( 'email', $this->new_member_email );
			$user_company = get_user_meta( $user->ID, 'wp_dp_company', true );
			$profile_status = get_post_meta( $user_company, 'wp_dp_user_status', true );
			return ( $profile_status != '' ) ? $profile_status : 'active';
		}

		public function add_email_template() {
			$email_templates = array();
			$email_templates[$this->template_group] = array();
			$email_templates[$this->template_group][$this->email_template_index] = array(
				'title' => $this->email_template_type,
				'template' => $this->email_default_template,
				'email_template_type' => $this->email_template_type,
				'is_recipients_enabled' => true,
				'description' => wp_dp_plugin_text_srt( 'wp_dp_user_registration_email' ),
				'subject' => wp_dp_plugin_text_srt( 'wp_dp_user_registration_email_subject' ),
				'jh_email_type' => 'html',
			);
			do_action( 'wp_dp_load_email_templates', $email_templates );
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
										<h1 style="color: #fff;">New Member Registration Notification to Administrator</h1>
									</td>
								</tr>
								<tr>
									<td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
										<table border="0" cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td width="260" valign="top">
													<table border="0" cellpadding="0" cellspacing="0" width="100%">
														<tr>
															<td style="padding-bottom: 8px;">A new member has been registered. Below are the details of newly registered member;</td>
														</tr>
														<tr>
															<td style="padding-bottom: 8px;">Member Name: [NEW_MEMBER_NAME]</td>
														</tr>
														<tr>
															<td style="padding-bottom: 8px;">Member Email: [NEW_MEMBER_EMAIL]</td>
														</tr>
														<tr>
															<td style="padding-bottom: 8px;">Member Profile Status: [USER_PROFILE_STATUS]</td>
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

	new Wp_dp_new_member_notification_site_owner_email_template( '', '' );
}
