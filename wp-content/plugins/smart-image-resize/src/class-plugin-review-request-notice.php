<?php

namespace WP_Smart_Image_Resize;

use WP_Smart_Image_Resize\Singleton_Trait;

class Plugin_Review_Request_Notice {

	use Singleton_Trait;

	const NONCE = 'wp-sir-review-request-notice';

	public function load() {
		add_action('wp_ajax_wp_sir/dismiss_review_request_notice', [$this, 'dismiss']);
		add_action('wp_ajax_wp_sir/remindme_review_request_notice', [$this, 'remindme']);
		add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
		add_action('admin_notices', [$this, 'render']);
	}

	public function enqueue_scripts() {

		if (!$this->can_show()) {
			return;
		}
		$file    = 'js/review_request_notice.js';
		$version = defined('WP_DEBUG') && WP_DEBUG ? filemtime(WP_SIR_DIR . $file) : WP_SIR_VERSION;
		wp_enqueue_script('wp-sir-review-request-notice', WP_SIR_URL . $file, ['jquery'], $version, true);

		wp_localize_script('wp-sir-review-request-notice', 'wp_sir_review_request_params', array(
			'nonce'    => wp_create_nonce(self::NONCE),
			'ajax_url' => admin_url('admin-ajax.php'),
		));
	}

	public function remindme() {
		check_ajax_referer(self::NONCE, 'nonce');
		set_transient('wp_sir_review_request_notice_remindme', '1', DAY_IN_SECONDS);
	}

	public function dismiss() {
		check_ajax_referer(self::NONCE, 'nonce');
		add_option('wp_sir_review_request_notice_dismissed', '1');
	}

	/**
	 * @throws \Exception
	 */
	private function can_show() {

		if (!apply_filters('wp_sir_show_plugin_review_request_notice', true)) {
			return false;
		}

		// Don't show again if the user already dismissed the notice.
		if (get_transient('wp_sir_review_request_notice_remindme')) {
			return false;
		}
		if (get_option('wp_sir_review_request_notice_dismissed')) {
			return false;
		}
		if (isset($_COOKIE['wp_sir_review_request_notice_remindme'])) {
			return false;
		}
		if (isset($_COOKIE['wp_sir_review_request_notice_dismissed'])) {
			return false;
		}

		$processed_images = get_posts([
			'post_type' 	=> 'attachment',
			'fields'	 	=> 'ids',
			'meta_query'	=> [['key' => '_processed_at']]
		]);

		// Plugin not used yet.
		if (empty($processed_images)) {
			return false;
		}

		// Show only after being used for two days.
		$installed_at = get_option('wp_sir_plugin_installed_at');

		if (!$installed_at) {
			$installed_at = current_time('mysql');
			update_option('wp_sir_plugin_installed_at', $installed_at);
		}

		$installed_at = new \DateTime($installed_at);
		$now          = new \DateTime(current_time('mysql'));
		$diff         = $installed_at->diff($now);

		return $diff && $diff->d >= 2;
	}

	public function render() {
		if (!$this->can_show()) {
			return;
		}
?>

		<div class="notice notice-info is-dismissible">
			<h3>Support the development of Smart Image Resize plugin!</h3>
			<p>Thank you for choosing <b>Smart Image Resize</b>. If you liked the plugin, kindly leave us a 5-star
				review on <a href="https://wordpress.org/support/plugin/smart-image-resize/reviews/?filter=5" target="_blank">WordPress.org</a>.
				We really appreciate your support!</p>

			<p><a target="_blank" href="https://wordpress.org/support/plugin/smart-image-resize/reviews/?filter=5#new-post" class="button-primary">Leave a review</a>
				&nbsp;<button type="button" class="button button-default" id="wp-sir-review-request-notice-remindme">
					Remind me later
				</button>
				<button type="button" class="button-link" id="wp-sir-review-request-notice-dismiss" style="margin-left:10px">
					Already done!
				</button>
			</p>
		</div>
<?php
	}
}


Plugin_Review_Request_Notice::instance()->load();
