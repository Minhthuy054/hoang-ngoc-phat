<?php

/**
 * The template for displaying single listing
 *
 */
get_header();
global $post, $wp_dp_plugin_options, $wp_dp_theme_options, $wp_dp_post_listing_types, $wp_dp_plugin_options;
$post_id = $post->ID;


wp_enqueue_script('wp-dp-listing-detail-scripts');
wp_enqueue_script('html2canvas');
wp_enqueue_script('fitvids');

$iconmoon_css = '';
$icons_groups = get_option('cs_icons_groups');
if ( ! empty($icons_groups) ) {
	foreach ( $icons_groups as $icon_key => $icon_obj ) {
		if ( isset($icon_obj['status']) && $icon_obj['status'] == 'on' ) {
			$iconmoon_css = '<link href="'. $icon_obj['url'] .'/style.css" rel="stylesheet" type="text/css">'."\n";
		}
	}
}


do_action('wp_dp_notes_frontend_modal_popup');
do_action('wp_dp_listing_compare_sidebar');


wp_dp_get_template_part('listing', 'view5', 'single-listing');


get_footer();
