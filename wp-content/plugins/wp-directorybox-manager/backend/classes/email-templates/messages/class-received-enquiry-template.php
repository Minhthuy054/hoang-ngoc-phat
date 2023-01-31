<?php
/**
 * Received Enquiry Email Template
 *
 * @since 1.0
 * @package	Directory Box
 */
if ( ! class_exists( 'Wp_dp_received_enquiry_email_template' ) ) {

	class Wp_dp_received_enquiry_email_template {

		public $email_template_type;
		public $email_default_template;
		public $email_template_variables;
		public $template_type;
		public $email_template_index;
		public $form_fields;
		public $is_email_sent;
		public static $is_email_sent1;
		public $template_group;

		public function __construct() {

			$this->email_template_type = 'Inquiry Message Received Notification for listing Owner';

			$this->email_default_template = $this->get_template_content();

			$this->email_template_variables = array(
				array(
					'tag' => 'ENQUIRY_USER_NAME',
					'display_text' => 'Enquiry User Name',
					'value_callback' => array( $this, 'get_enquiry_user_name' ),
				),
				array(
					'tag' => 'ENQUIRY_USER_PHONE',
					'display_text' => 'Enquiry User Phone',
					'value_callback' => array( $this, 'get_enquiry_user_phone' ),
				),
				array(
					'tag' => 'ENQUIRY_USER_EMAIL',
					'display_text' => 'Enquiry User Email',
					'value_callback' => array( $this, 'get_enquiry_user_email' ),
				),
				array(
					'tag' => 'ENQUIRY_USER_MESSAGE',
					'display_text' => 'Enquiry User Message',
					'value_callback' => array( $this, 'get_enquiry_user_message' ),
				),
				array(
					'tag' => 'LISTING_USER_NAME',
					'display_text' => 'Restaurant User Name',
					'value_callback' => array( $this, 'get_listing_user_name' ),
				),
				array(
					'tag' => 'LISTING_USER_EMAIL',
					'display_text' => 'Restaurant User Email',
					'value_callback' => array( $this, 'get_listing_user_email' ),
				),
				array(
					'tag' => 'LISTING_TITLE',
					'display_text' => 'Restaurant Title',
					'value_callback' => array( $this, 'get_listing_title' ),
				),
				array(
					'tag' => 'LISTING_LINK',
					'display_text' => 'Restaurant Link',
					'value_callback' => array( $this, 'get_listing_link' ),
				),
			);
			$this->template_group = 'Messages';
			$this->email_template_index = 'received-enquiry-template';
			add_action( 'init', array( $this, 'add_email_template' ), 13 );
			add_filter( 'wp_dp_email_template_settings', array( $this, 'template_settings_callback' ), 12, 1 );
			add_action( 'wp_dp_received_enquiry_email', array( $this, 'wp_dp_received_enquiry_email_callback' ), 10, 4 );
		}

		public function wp_dp_received_enquiry_email_callback( $form_fields = array() ) {
			$this->form_fields = $template = $args = array();

			$this->form_fields = $form_fields;
			$template = $this->get_template();
			// checking email notification is enable/disable
			if ( isset( $template['email_notification'] ) && $template['email_notification'] == 1 ) {

				$blogname = get_option( 'blogname' );
				$admin_email = get_option( 'admin_email' );
				// getting template fields
				$subject = (isset( $template['subject'] ) && $template['subject'] != '' ) ? $template['subject'] : wp_dp_plugin_text_srt( 'wp_dp_received_enquiry_email_subject' );
				$from = (isset( $template['from'] ) && $template['from'] != '') ? $template['from'] : esc_attr( $this->get_enquiry_user_name() ) . ' <' . $this->get_enquiry_user_email() . '>';
				$recipients = (isset( $template['recipients'] ) && $template['recipients'] != '') ? $template['recipients'] : $this->get_listing_user_email();
				$email_type = (isset( $template['email_type'] ) && $template['email_type'] != '') ? $template['email_type'] : 'html';

				$args = array(
					'to' => $recipients,
					'from' => $from,
					'subject' => $subject,
					'message' => $template['email_template'],
					'email_type' => $email_type,
					'class_obj' => $this,
				);

				do_action( 'wp_dp_send_mail', $args );
				Wp_dp_received_enquiry_email_template::$is_email_sent1 = $this->is_email_sent;
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
				'description' => wp_dp_plugin_text_srt( 'wp_dp_received_enquiry_email' ),
				'subject' => wp_dp_plugin_text_srt( 'wp_dp_received_enquiry_email_subject' ),
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

		function get_enquiry_user_name() {
			return isset( $this->form_fields['user_name'] ) ? $this->form_fields['user_name'] : '';
		}

		function get_enquiry_user_email() {
			return isset( $this->form_fields['user_email'] ) ? $this->form_fields['user_email'] : '';
		}

		function get_enquiry_user_phone() {
			return isset( $this->form_fields['user_phone'] ) ? $this->form_fields['user_phone'] : '';
		}

		function get_enquiry_user_message() {
			return isset( $this->form_fields['user_message'] ) ? $this->form_fields['user_message'] : '';
		}

		function get_listing_user_name() {
			$listing_member = isset( $this->form_fields['wp_dp_listing_member'] ) ? $this->form_fields['wp_dp_listing_member'] : '';
			$listing_member_name = esc_html( get_the_title( $listing_member ) );
			return $listing_member_name;
		}

		function get_listing_user_email() {
			$listing_member = isset( $this->form_fields['wp_dp_listing_member'] ) ? $this->form_fields['wp_dp_listing_member'] : '';
			$listing_member_email = get_post_meta( $listing_member, 'wp_dp_email_address', true );
			return $listing_member_email;
		}

		function get_listing_title() {
			$listing_id = isset( $this->form_fields['wp_dp_listing_id'] ) ? $this->form_fields['wp_dp_listing_id'] : '';
			return esc_html( get_the_title( $listing_id ) );
		}

		function get_listing_link() {
			$listing_id = isset( $this->form_fields['wp_dp_listing_id'] ) ? $this->form_fields['wp_dp_listing_id'] : '';
			return esc_url( get_permalink( $listing_id ) );
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
										<h1 style="color: #fff;">Inquiry Message Received Notification for listing Owner</h1>
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
															<td style="padding-bottom: 8px;">Thank you for working with [SITE_NAME]. A new inquiry has been received against your listing. Below is the detail of inquiry; </td>
														</tr>
														<tr>
															<td style="padding-bottom: 8px;">Listing Title: <a href="[LISTING_LINK]">[LISTING_TITLE]</a></td>
														</tr>
														<tr>
															<td style="padding-bottom: 8px;">Name: [ENQUIRY_USER_NAME]</td>
														</tr>
														<tr>
															<td style="padding-bottom: 8px;">Phone: [ENQUIRY_USER_PHONE]</td>
														</tr>
														<tr>
															<td style="padding-bottom: 8px;">Email: [ENQUIRY_USER_EMAIL]</td>
														</tr>
														<tr>
															<td style="padding-bottom: 8px;">Message: [ENQUIRY_USER_MESSAGE]</td>
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

	new Wp_dp_received_enquiry_email_template();
}
