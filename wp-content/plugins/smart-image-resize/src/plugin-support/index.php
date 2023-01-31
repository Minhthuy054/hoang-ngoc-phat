<?php

/**
 * ---------------------------------------------------------------------------------------------
 * Add support for other plugins.
 * ---------------------------------------------------------------------------------------------
 */

// Prevent overriding generated metadata.
remove_filter('wp_generate_attachment_metadata','dt_generate_attachment_metadata');
remove_filter('wp_generate_attachment_metadata','bt_generate_attachment_metadata');

include_once WP_SIR_DIR . 'src/plugin-support/wpml.php';
include_once WP_SIR_DIR . 'src/plugin-support/woocommerce.php';
include_once WP_SIR_DIR . 'src/plugin-support/jetpack.php';
include_once WP_SIR_DIR . 'src/plugin-support/regenerate-thumbnails.php';
