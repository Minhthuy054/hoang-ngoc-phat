<?php
/**
 * @author AazzTech
 */
class ATBDP_Send_Mail {

    public function __construct() {
        add_action( 'wp_ajax_send_system_info', array( $this, 'send_system_info' ) );
    }

    public function send_system_info() {
        if ( isset( $_POST['_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_nonce'] ) ), '_debugger_email_nonce' ) ) {
            die( 'huh!' );
        }
		$user = wp_get_current_user();
        $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
        $sender_email = isset( $_POST['sender_email'] ) ? sanitize_email( wp_unslash( $_POST['sender_email'] ) ) : '';
		$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
		$system_info_url = isset( $_POST['system_info_url'] ) ? sanitize_text_field( wp_unslash( $_POST['system_info_url'] ) )  : '';
		$to = ! empty( $email ) ? $email : '';
		$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
		if( ! empty( $system_info_url ) ) {
            $message .= '<div><a href="' . $system_info_url . '">';
            $message .=   $system_info_url;
            $message .= '</a></div>';
		}
		$message  = atbdp_email_html( $subject, $message );
		$headers = "From: {$user->display_name} <{$sender_email}>\r\n";
		$headers .= "Reply-To: {$sender_email}\r\n";

		// return true or false, based on the result
		$send_email = ATBDP()->email->send_mail( $to, $subject, $message, $headers ) ? true : false;

		if ( $send_email ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}

    public function system_info() {
        include ATBDP_INC_DIR . '/system-status/system-info.php';
		ob_start();
		new ATBDP_System_Info_Email_Link();
		return ob_get_clean();
	}

    public function send_email_to() {
        if ( ! current_user_can( 'manage_options' ) ) {
                return;
        }
        $token = get_transient( 'system_info_remote_token' );
		$url   = $token ? home_url() . '/?atbdp-system-info=' . $token : '';
        $user = wp_get_current_user();
		?>
        <div class="card atbds_card">
            <div class="card-head">
                <h4><?php esc_html_e( 'Support', 'directorist' ); ?></h4>
            </div>
            <div class="card-body">
                <div class="atbds_content__tab">
                    <div class="atbds_supportForm">
                         <form id="atbdp-send-system-info" method="post" enctype="multipart/form-data" action="<?php echo esc_url( self_admin_url( 'admin-ajax.php' ) ); ?>">
                            <div class="atbds_form-row">
                                <label><?php esc_html_e( 'Sender Email Address', 'directorist' ); ?></label>
                                <input type="email" name="sender_email" id="atbdp-sender-address" placeholder="<?php esc_html_e( 'user@email.com', 'directorist' ); ?>" value="<?php echo esc_html( $user->user_email ); ?>">
                            </div>
                            <div class="atbds_form-row">
                                <label><?php esc_html_e( 'Receiver Email Address', 'directorist' ); ?></label>
                                <input type="email" name="email" id="atbdp-email-address" placeholder="<?php esc_html_e( 'user@email.com', 'directorist' ); ?>" value="support@aazztech.com">
                            </div>
                            <div class="atbds_form-row">
                                <label><?php esc_html_e( 'Subject', 'directorist' ); ?></label>
                                <input type="text" name="subject" id="atbdp-email-subject" placeholder="<?php esc_html_e( 'Subject', 'directorist' ); ?>"/>
                            </div>
                            <div class="atbds_form-row">
                                <label><?php esc_html_e( 'Additional Message', 'directorist' ); ?></label>
                                <textarea name="message" id="atbdp-email-message"></textarea>
                            </div>
                            <div class="atbds_form-row">
                                <label><?php esc_html_e( 'Remote Viewing Url', 'directorist' ); ?></label>
                                <input type="text" name="system-info" id="atbdp-system-info-url" placeholder="" value="<?php echo ! empty( $url ) ? esc_url( $url ) : ''; ?>">
                            </div>
                            <div class="atbds_form-row">
                            <p class='system_info_success'></p>
                            <input type="hidden" name='_email_nonce' id='atbdp_email_nonce' value='<?php echo esc_attr( wp_create_nonce( '_debugger_email_nonce' ) ); ?>' />
                                <button class="atbds_btn atbds_btnPrimary" id="atbdp-send-system-info-submit"><?php esc_html_e( 'Send Mail', 'directorist' ); ?></button>
                            </div>
                        </form>
                    </div><!-- ends: .atbds_supportForm -->
                </div>
            </div>
        </div>
		<?php
		do_action( 'atbdp_tools_email_system_info_after' );
    
    }
}