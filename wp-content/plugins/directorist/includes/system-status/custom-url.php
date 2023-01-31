<?php
/**
 * @author AazzTech
 */
class ATBDP_Custom_Url
{
    public function __construct() {
		add_action( 'wp_ajax_generate_url', array( $this, 'generate_url' ) );
		add_action( 'wp_ajax_revoke_url', array( $this, 'revoke_url' ) );
		add_action( 'template_redirect', array( $this, 'view' ) );
    }

    public function generate_url() {
		if ( isset( $_POST['_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_nonce'] ) ), '_generate_custom_url' ) ) {
            die( 'huh!' );
        }
		$token   = wp_rand();
		$expires = apply_filters( 'atbdp_system_info_remote_token_expire', DAY_IN_SECONDS * 3 );
		set_transient( 'system_info_remote_token', $token, $expires );
		$url = home_url() . '/?atbdp-system-info=' . $token;
		wp_send_json_success(
			array(
				'url' => $url,
				'message' => __( 'Secret URL has been created.', 'directorist' ),
			)
		);
    }

    public function revoke_url() {
		if ( isset( $_POST['_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_nonce'] ) ), '_revoke_custom_url' ) ) {
            die( 'huh!' );
        }
		delete_transient( 'system_info_remote_token' );
		wp_send_json_success( __( 'Secret URL has been revoked.', 'directorist' ) );
    }
    
    public function view() {

		if ( ! isset( $_GET['atbdp-system-info'] ) || empty( $_GET['atbdp-system-info'] ) ) {
			return;
		}

		$queryValue = sanitize_text_field( wp_unslash( $_GET['atbdp-system-info'] ) );
		$token      = get_transient( 'system_info_remote_token' );

		if ( $queryValue == $token ) {

			/** WordPress Plugin Administration API */
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
			require_once ABSPATH . 'wp-admin/includes/update.php';
			
			echo '<pre>';
			echo wp_kses_post( $this->system_info() );
			echo '</pre>';
			exit;

		} else {

			wp_safe_redirect( home_url() );
			exit;
		}

	}
	
    public function system_info() {
		include ATBDP_INC_DIR . '/system-status/system-info.php';
		ob_start();
		new ATBDP_System_Info_Email_Link();
		return ob_get_clean();
	}

    public function custom_link() {
		$token = get_transient( 'system_info_remote_token' );
		$url   = $token ? home_url() . '/?atbdp-system-info=' . $token : '';
		?>
		<div class="card atbds_card">
			<div class="card-head">
				<h4><?php esc_html_e( 'Remote Viewing', 'directorist' ); ?></h4>
			</div>
			<div class="card-body">
				<div id="atbdp-remote-response"></div>
				<div class="atbds_content__tab">
					<div class="atbds_remoteViewingForm">
						<p><?php esc_html_e( 'Create a secret URL to show system information with others. If you face any technical problem to your site then you can share this URL with support agent for faster debugging.', 'directorist' ); ?></p>
						<p><?php esc_html_e( 'This secret URL expires after 72 hours, but you can revoke it anytime.', 'directorist' ); ?></p>
						<form action="#">
							<div class="atbds_form-row">
								<input type="url" id="system-info-url" onclick="this.focus();this.select()" value="<?php echo esc_url( $url ? $url : '' ); ?>">
								<a class="button-secondary" href="<?php echo esc_url( $url ? $url : '#' ); ?>" target="_blank"
												id="system-info-url-text-link" style="display: <?php echo $url ? 'display-inline' : 'none'; ?>"><?php esc_html_e( 'View', 'directorist' ); ?></a>
							
							</div>
							<div class="atbds_form-row">
								<div class="atbds_buttonGroup">
									<button id='generate-url' name="generate-url" class="atbds_btn atbds_btnDark" data-nonce="<?php echo esc_attr( wp_create_nonce( '_generate_custom_url' ) ); ?>" ><?php esc_html_e( 'Create URL', 'directorist' ); ?></button>
									<button id='revoke-url' name="revoke-url" class="atbds_btn atbds_btnGray" data-nonce="<?php echo esc_attr( wp_create_nonce( '_revoke_custom_url' ) ); ?>" ><?php esc_html_e( 'Revoke URL', 'directorist' ); ?></button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

}