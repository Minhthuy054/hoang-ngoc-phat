<?php
/**
 * File Type: Listing Sidebar Map Page Element
 */
if ( ! class_exists('wp_dp_navbar_element') ) {

	class wp_dp_navbar_element {

		/**
		 * Start construct Functions
		 */
		public function __construct() {
			add_filter('wp_dp_navbar_html', array( $this, 'wp_dp_navbar_html_callback' ), 11, 2);
		}

		public function wp_dp_navbar_html_callback($menu_content, $listing_id = '') {
			global $post, $wp_dp_plugin_options, $wp_dp_yelp_list_results;
			
			$sticky_navigation = wp_dp_element_hide_show($listing_id, 'sticky_navigation');
			if( $sticky_navigation != 'on' ){
				return;
			}
			
			if ( $listing_id == '' ) {
				$listing_id = $post->ID;
			}
			$wp_dp_listing_type_slug = get_post_meta($listing_id, 'wp_dp_listing_type', true);
			$listing_type_id = 0;
			if ( $get_post = get_page_by_path($wp_dp_listing_type_slug, OBJECT, 'listing-type') ) {
				$listing_type_id = $get_post->ID;
			}
			$listing_type_id = wp_dp_wpml_lang_page_id( $listing_type_id, 'listing-type' );

			$content_post = get_post($listing_id);
			$listing_content = $content_post->post_content;
			$listing_content = apply_filters('the_content', $listing_content);
			$listing_content = str_replace(']]>', ']]&gt;', $listing_content);
			$floor_plans = get_post_meta($listing_id, 'wp_dp_floor_plans', true);
			$floor_plans = empty($floor_plans) ? array() : $floor_plans;
			$wp_dp_attachments = get_post_meta($listing_id, 'wp_dp_attachments', true);
			$wp_dp_attachments_options = get_post_meta($listing_type_id, 'wp_dp_attachments_options_element', true);
			$wp_dp_listing_video = get_post_meta($listing_id, 'wp_dp_listing_video', true);
			$wp_dp_apartments = get_post_meta($listing_id, 'wp_dp_apartment', true);
			$features_list = get_post_meta($listing_id, 'wp_dp_listing_feature_list', true);
			$type_features_not_selected = get_post_meta($listing_type_id, 'wp_dp_enable_not_selected', true);
			$access_token = isset($wp_dp_plugin_options['wp_dp_yelp_access_token']) ? $wp_dp_plugin_options['wp_dp_yelp_access_token'] : '';
			$terms = get_post_meta($listing_id, 'wp_dp_listing_places', true);
			$lat = get_post_meta($listing_id, 'wp_dp_post_loc_latitude_listing', true);
			$long = get_post_meta($listing_id, 'wp_dp_post_loc_longitude_listing', true);


			/*
			 * Listing Elements Settings
			 */
			$wp_dp_enable_features_element = get_post_meta($listing_id, 'wp_dp_enable_features_element', true);
			$wp_dp_enable_video_element = get_post_meta($listing_id, 'wp_dp_enable_video_element', true);
			$wp_dp_enable_yelp_places_element = get_post_meta($listing_id, 'wp_dp_enable_yelp_places_element', true);
			$wp_dp_enable_appartment_for_sale_element = get_post_meta($listing_id, 'wp_dp_enable_appartment_for_sale_element', true);
			$wp_dp_enable_file_attachments_element = get_post_meta($listing_id, 'wp_dp_enable_file_attachments_element', true);
			$wp_dp_enable_floot_plan_element = get_post_meta($listing_id, 'wp_dp_enable_floot_plan_element', true);



			ob_start();
			?>
			<ul>
				<?php if ( $listing_content != '' ) { ?>
					<li><a href="#listing-detail"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_listing_key_detail'); ?></a></li>
				<?php } ?>
				<?php if ( ( ! empty($features_list) || $type_features_not_selected == 'on') && $wp_dp_enable_features_element != 'off' ) { ?>
					<li><a href="#features"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_listing_amenities'); ?></a></li>
				<?php } ?>
				<?php if ( (isset($wp_dp_listing_video) && $wp_dp_listing_video != '') && $wp_dp_enable_video_element != 'off' ) { ?>
					<li><a href="#video"><?php echo wp_dp_plugin_text_srt('wp_dp_subnav_item_3'); ?></a></li>
				<?php } ?>
				<?php if ( is_array($terms) && $access_token != '' && $lat != '' && $long != '' && $wp_dp_enable_yelp_places_element != 'off' ) { ?>
					<li><a href="#best-of-yelp-module"><?php echo wp_dp_plugin_text_srt('wp_dp_subnav_item_4'); ?></a></li>
				<?php } ?>
				<?php if ( (is_array($wp_dp_apartments) && $wp_dp_apartments != '') && $wp_dp_enable_appartment_for_sale_element != 'off' ) { ?>
					<li><a href="#apartments"><?php echo wp_dp_plugin_text_srt('wp_dp_subnav_item_5'); ?></a></li>
				<?php } ?>
				<?php if ( (isset($wp_dp_attachments) && ! empty($wp_dp_attachments) && $wp_dp_attachments_options == 'on') && $wp_dp_enable_file_attachments_element != 'off' ) { ?>
					<li><a href="#attachments"><?php echo wp_dp_plugin_text_srt('wp_dp_subnav_item_6'); ?></a></li>
				<?php } ?>
				<?php if ( count($floor_plans) > 0 && $wp_dp_enable_floot_plan_element != 'off' ) { ?>
					<li><a href="#floor-plans"><?php echo wp_dp_plugin_text_srt('wp_dp_subnav_item_7'); ?></a></li>
						<?php } ?>
			</ul>
			<?php
			$content = ob_get_clean();
			$menu_content['content'] = $content;
			return $menu_content;
		}

	}

	global $wp_dp_navbar;
	$wp_dp_navbar = new wp_dp_navbar_element();
}