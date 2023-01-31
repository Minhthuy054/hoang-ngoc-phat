<?php

/**
 * File Type: Searchs Shortcode Frontend
 */
if ( ! class_exists( 'Wp_dp_Shortcode_Listing_Categories_front' ) ) {

	class Wp_dp_Shortcode_Listing_Categories_front {

		/**
		 * Constant variables
		 */
		var $PREFIX = 'listing_categories';

		/**
		 * Start construct Functions
		 */
		public function __construct() {
			add_shortcode( $this->PREFIX, array( $this, 'wp_dp_listing_categories_shortcode_callback' ) );
		}

		/*
		 * Shortcode View on Frontend
		 */

		function combine_pt_section( $keys, $values ) {
			$result = array();
			foreach ( $keys as $i => $k ) {
				$result[$k][] = $values[$i];
			}
			array_walk( $result, function($v){ $v = (count($v) == 1)? array_pop($v): $v; });
			return $result;
		}

		public function wp_dp_listing_categories_shortcode_callback( $atts, $content = "" ) {
			global $current_user, $wp_dp_plugin_options;
			wp_enqueue_script( 'wp-dp-bootstrap-slider' );
			wp_enqueue_script( 'wp-dp-matchHeight-script' );
			$icons_groups = get_option( 'cs_icons_groups' );
			if ( ! empty( $icons_groups ) ) {
				foreach ( $icons_groups as $icon_key => $icon_obj ) {
					if ( isset( $icon_obj['status'] ) && $icon_obj['status'] == 'on' ) {
						wp_enqueue_style( 'cs_icons_data_css_' . $icon_key, $icon_obj['url'] . '/style.css' );
					}
				}
			}
			$listing_categories_title = isset( $atts['listing_categories_title'] ) ? $atts['listing_categories_title'] : '';

			$listing_categories_title_align = isset( $atts['listing_categories_title_align'] ) ? $atts['listing_categories_title_align'] : '';
			$pricing_tabl_subtitle = isset( $atts['listing_categories_subtitle'] ) ? $atts['listing_categories_subtitle'] : '';
			$listing_categories_moreless = isset( $atts['listing_categories_more_less'] ) ? $atts['listing_categories_more_less'] : 'yes';
			$class = "class=class-" . $listing_categories_moreless . "";
			$listing_categories = isset( $atts['listing_categories'] ) ? $atts['listing_categories'] : '';
			ob_start();
			$page_element_size = isset( $atts['listing_categories_element_size'] ) ? $atts['listing_categories_element_size'] : 100;
			if ( function_exists( 'wp_dp_cs_var_page_builder_element_sizes' ) ) {
				echo '<div class="' . wp_dp_cs_var_page_builder_element_sizes( $page_element_size ) . ' ">';
			}
			$all_types = explode( ",", $listing_categories );
			$wp_dp_element_structure = '';
			$wp_dp_element_structure = wp_dp_plugin_title_sub_align( $listing_categories_title, $pricing_tabl_subtitle, $listing_categories_title_align );
			echo force_balance_tags( $wp_dp_element_structure );



			if ( is_array( $all_types ) && count( $all_types) > 0 ) {
				echo '<div class="main-categories">';
				echo '<ul class="row">';
				foreach ( $all_types as $type ) {

					if ( $type != '' && (FALSE !== get_post_status( $type ) && 'publish' == get_post_status( $type )) ) {
						$wp_dp_search_result_page = get_post_meta( $type, 'wp_dp_search_result_page', true );
						$type_post = get_post( $type );
						$type_post_slug = isset( $type_post->post_name ) ? $type_post->post_name : '';
						$type_url = 0;
						if ( $wp_dp_search_result_page != '' ) {
							$type_url = 1;
							$wp_dp_search_result_page = get_permalink( $wp_dp_search_result_page );
						} else {
							$type_url = 0;
							$wp_dp_search_result_page = isset( $wp_dp_plugin_options['wp_dp_search_result_page'] ) ? $wp_dp_plugin_options['wp_dp_search_result_page'] : '';
							$wp_dp_search_result_page = get_permalink( $wp_dp_search_result_page );
						}

						$wp_dp_search_result_page = isset( $wp_dp_search_result_page ) && $wp_dp_search_result_page != '' ? add_query_arg( array( 'listing_type' => $type_post_slug, 'ajax_filter' => 'true' ), $wp_dp_search_result_page ) : '';


						$listing_type_tite = get_the_title( $type );
						$listing_type_icon = get_post_meta( $type, 'wp_dp_listing_type_icon', true );
						$listing_image = get_post_meta( $type, 'wp_dp_listing_type_image', true );

						$listing_image = $listing_image != '' ? wp_get_attachment_url( $listing_image ) : '';

						$type_terms = get_post_meta( ( int ) $type, 'wp_dp_listing_type_cats', true );
						echo '<li class="col-lg-3 col-md-4 col-sm-6 col-xs-6">';
						echo '<div class="categories-holder">';
						echo '<div class="img-holder">';
						if ( $listing_image != '' ) {
							echo '<figure><a href="' . $wp_dp_search_result_page . '"><img src="' . $listing_image . '" alt="' . $listing_type_tite . '"></a></figure>';
						} else {
							if ( $listing_type_icon[0] != '' ) {
								echo '<figure><a href="' . $wp_dp_search_result_page . '"><i class="' . $listing_type_icon[0] . '"></i></a></figure>';
							}
						}
						echo '<a  href="' . $wp_dp_search_result_page . '">' . $listing_type_tite . '</a> ';
						echo '</div>';
						$listing_type_cats = array();
						$wp_dp_listing_type_cats = get_post_meta( $type, 'wp_dp_listing_type_cats', true );
						if ( isset( $wp_dp_listing_type_cats ) && ! empty( $wp_dp_listing_type_cats ) ) {
							foreach ( $wp_dp_listing_type_cats as $wp_dp_listing_type_cat ) {
								$term = get_term_by( 'slug', $wp_dp_listing_type_cat, 'listing-category' );
								$listing_type_cats[$term->slug] = $term->name;
							}
						}
						if ( is_array( $listing_type_cats ) && count( $listing_type_cats ) > 0 ) {
							echo '<div class="text-holder">
                                    <ul data-showmore="' . $listing_categories_moreless . '" ' . $class . '>';
							foreach ( $listing_type_cats as $key => $value ) {
								$default_date_time_formate = 'd-m-Y H:i:s';

								$element_filter_arr = array();
								$element_filter_arr[] = array(
									'key' => 'wp_dp_listing_posted',
									'value' => strtotime( date( $default_date_time_formate ) ),
									'compare' => '<=',
								);
								$element_filter_arr[] = array(
									'key' => 'wp_dp_listing_expired',
									'value' => strtotime( date( $default_date_time_formate ) ),
									'compare' => '>=',
								);
								$element_filter_arr[] = array(
									'key' => 'wp_dp_listing_status',
									'value' => 'active',
									'compare' => '=',
								);

								$element_filter_arr[] = array(
									'key' => 'wp_dp_listing_type',
									'value' => $type_post_slug,
									'compare' => '=',
								);

								$args = array(
									'post_status' => 'publish',
									'post_type' => 'listings',
									'posts_per_page' => "1",
									'fields' => 'ids', // only load ids 
									'meta_query' => array(
										$element_filter_arr,
									)
								);
								$args['tax_query'] = array(
									array(
										'taxonomy' => 'listing-category',
										'field' => 'slug',
										'terms' => $key
									)
								);

								$the_query = new WP_Query( $args ); //
								$post_count = $the_query->found_posts;

								wp_reset_postdata();

								$child_type_search_result_page = isset( $wp_dp_search_result_page ) && $wp_dp_search_result_page != '' ? add_query_arg( array( 'listing_type' => $type_post_slug, 'listing_category' => $key, 'ajax_filter' => 'true' ), $wp_dp_search_result_page ) : '';

								echo '<li>';
								echo '<a href="' . esc_url( $child_type_search_result_page ) . '">' . esc_html( $value ) . '</a>';
								echo '<span>' . $post_count . '</span>';
								echo '</li>';
							}
							echo '</ul></div>';
						}
						echo '</div>';

						echo '</li>';
					}
				}
				echo '</ul>';
				echo '</div>';
			}

			if ( function_exists( 'wp_dp_cs_var_page_builder_element_sizes' ) ) {
				echo '</div>';
			}
			$html = ob_get_clean();
			return $html;
		}

	}

	global $wp_dp_shortcode_listing_categories_front;
	$wp_dp_shortcode_listing_categories_front = new Wp_dp_Shortcode_Listing_Categories_front();
}