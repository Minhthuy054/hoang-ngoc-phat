<?php

/**
 * User Approved Email Template
 *
 * @since 1.0
 * @package	Directory Box
 */
if ( ! class_exists('Wp_dp_approved_member_profile_template') ) {

    class Wp_dp_approved_member_profile_template {

        public $email_template_type;
        public $email_default_template;
        public $email_template_variables;
        public $template_type;
        public $email_template_index;
        public $template_group;
        public $member_id;
		public $user_id;
		public $is_email_sent;

        public function __construct() {

            $this->email_template_type = 'Approved Profile Notification to User';
            $this->email_default_template = $this->get_template_content();

            $this->email_template_variables = array(
				array(
                    'tag' => 'MEMBER_NAME',
                    'display_text' => 'Member Name',
                    'value_callback' => array( $this, 'get_approved_member_username' ),
                ),
                array(
                    'tag' => 'MEMBER_EMAIL',
                    'display_text' => 'Member Email',
                    'value_callback' => array( $this, 'get_approved_member_email' ),
                )
                
            );
            $this->template_group = 'User';
            $this->email_template_index = 'approved-member-profile-template';

            add_filter('wp_dp_email_template_settings', array( $this, 'template_settings_callback' ), 12, 1);

            // Add action user status callback
            add_action('wp_dp_profile_status_changed', array( $this, 'member_profile_status_changed' ), 10, 2);

            add_action('init', array( $this, 'add_email_template' ), 13);
        }

        public function template_settings_callback($email_template_options) {

            $email_template_options["types"][] = $this->email_template_type;

            $email_template_options["templates"][$this->email_template_type] = $this->email_default_template;

            $email_template_options["variables"][$this->email_template_type] = $this->email_template_variables;

            return $email_template_options;
        }

        public function get_template() {
            return wp_dp::get_template($this->email_template_index, $this->email_template_variables, $this->email_default_template);
        }

        function get_approved_member_email() {
            if ( isset( $this->member_id ) && $this->member_id != '' ) {
				$member_email = get_post_meta( $this->member_id, 'wp_dp_email_address', true );
			}
			if ( isset( $this->user_id ) && $this->user_id != '' ) {
				$member_info = get_user_by( 'id', $this->user_id );
				$user_email = $member_info->user_email;
			}
			$profile_member_email = $member_email != '' ? $member_email : $user_email;
			return $profile_member_email;
        }

        function get_approved_member_username() {
            $member_name = esc_html(get_the_title( $this->member_id ));
			return $member_name;
        }

        public function member_profile_status_changed($member_id, $member_old_status) {
			
            if ( $member_id != '' ) {

                $this->member_id = $member_id;
				$this->user_id = wp_dp_user_id_form_company_id( $member_id );
                $member = new WP_User($this->user_id);
                $role = array_shift($member->roles);
				
                // checking member role
                if ( $role == 'wp_dp_member' ) {
                    // getting pulbisher status
                    $member_status = get_post_meta($member_id, 'wp_dp_user_status', true);
					
                    // checking user status
                    if ( $member_status == 'active' && ($member_status != $member_old_status || $member_old_status == '') ) {
						
                        $template = $this->get_template();
						// checking email notification is enable/disable
                        if ( isset($template['email_notification']) && $template['email_notification'] == 1 ) {

                            $blogname = get_option('blogname');
                            $admin_email = get_option('admin_email');
                            // getting template fields
                            $subject = (isset($template['subject']) && $template['subject'] != '' ) ? $template['subject'] : wp_dp_plugin_text_srt('wp_dp_approved_member_email_subject');
                            $from = (isset($template['from']) && $template['from'] != '') ? $template['from'] : esc_attr($blogname) . ' <' . $admin_email . '>';
                            $recipients = (isset($template['recipients']) && $template['recipients'] != '') ? $template['recipients'] : $this->get_approved_member_email();
                            $email_type = (isset($template['email_type']) && $template['email_type'] != '') ? $template['email_type'] : 'html';

                            $args = array(
                                'to' => $recipients,
                                'subject' => $subject,
                                'from' => $from,
                                'message' => $template['email_template'],
                                'email_type' => $email_type,
                            );

                            do_action('wp_dp_send_mail', $args);
                        }
                    }
                }
            }
        }

        public function add_email_template() {
			
			$email_templates = array();
            $email_templates[$this->template_group] = array();
            $email_templates[$this->template_group][$this->email_template_index] = array(
                'title' => $this->email_template_type,
                'template' => $this->email_default_template,
                'email_template_type' => $this->email_template_type,
                'is_recipients_enabled' => false,
                'description' => wp_dp_plugin_text_srt('wp_dp_approved_member_email'),
				'subject' => wp_dp_plugin_text_srt('wp_dp_approved_member_email_subject'),
				'jh_email_type' => 'html',
            );
            do_action('wp_dp_load_email_templates', $email_templates);
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
										<h1 style="color: #fff;">Approved Profile Notification to User</h1>
									</td>
								</tr>
								<tr>
									<td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
										<table border="0" cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td width="260" valign="top">
													<table border="0" cellpadding="0" cellspacing="0" width="100%">
														<tr>
															<td>Dear [MEMBER_NAME]</td>
														</tr>
														<tr>
															<td style="padding-bottom: 8px;">Thank you for registering with [SITE_NAME]. Your profile has been approved. You can enjoy and explore the website.</td>
														</tr>
														<tr>
															<td style="padding-bottom: 8px;">Thank you for registering with us.</td>
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

    new wp_dp_approved_member_profile_template();
}