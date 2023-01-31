<?php

/**
 * Add a work-around to fix a bug in Regenerate Thumbnails that interferes with WPML.
 */

add_action( 'admin_enqueue_scripts', function () {
	$screen = get_current_screen();

	if ( ! $screen || $screen->id !== 'tools_page_regenerate-thumbnails' ) {
		return;
	}

	$root = remove_query_arg( 'lang', esc_url_raw( get_rest_url() ) );
	$script = 'if (wpApiSettings) { wpApiSettings.root = "' . $root . '"; }';
	wp_add_inline_script( 'wp-api-request', $script );
} );
