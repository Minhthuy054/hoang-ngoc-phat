<?php
/**
 * Listing search box
 * default variable which is getting from ajax request or shotcode
 * $listing_short_counter, $listing_arg
 */
global $post, $wp_dp_plugin_options, $wp_dp_form_fields_frontend, $wp_dp_post_listing_types, $wp_dp_shortcode_listings_frontend;
wp_enqueue_script( 'bootstrap-datepicker' );
wp_enqueue_style( 'datetimepicker' );
wp_enqueue_script( 'datetimepicker' );
// passing from shortcode main function
$save_search_box = isset( $atts['save_search_box'] ) ? $atts['save_search_box'] : '';
$notifications_box = isset( $atts['notifications_box'] ) ? $atts['notifications_box'] : '';
$draw_on_map_url = isset( $atts['draw_on_map_url'] ) ? $atts['draw_on_map_url'] : '';
$page_id = isset( $post->ID )? $post->ID : 0;
$page_id = isset( $_REQUEST['page_id'] ) ? $_REQUEST['page_id'] : $page_id;
$element_listing_search_keyword = isset( $atts['listing_search_keyword'] ) ? $atts['listing_search_keyword'] : 'no';
$left_filter_count_switch = isset( $atts['left_filter_count'] ) ? $atts['left_filter_count'] : '';
$wp_dp_listing_sidebar = isset( $atts['wp_dp_listing_sidebar'] ) ? $atts['wp_dp_listing_sidebar'] : '';
$search_box_sidebar = isset( $atts['search_box'] ) ? $atts['search_box'] : '';
$filter_search_box = isset( $atts['filter_search_box'] ) ? $atts['filter_search_box'] : 'yes';
$listing_filter_location = isset( $atts['filter_location_box'] ) ? $atts['filter_location_box'] : 'yes';
if ( isset( $listing_right_sidebar_content ) && $listing_right_sidebar_content != '' ) {
	$sidebar_filter_columns = 'col-lg-2 col-md-2 col-sm-12 col-xs-12';
} else {
	$sidebar_filter_columns = 'col-lg-3 col-md-3 col-sm-12 col-xs-12';
}
$listing_price_filter = isset( $atts['listing_price_filter'] ) ? $atts['listing_price_filter'] : '';
$filters_accordion = isset( $atts['listing_filter_accordion'] ) ? $atts['listing_filter_accordion'] : 'no';
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="listing-breadcrumb">
		<?php
		wp_dp_page_breadcrumb( $page_id );
		$listing_type_slug = isset( $_REQUEST['listing_type'] ) ? $_REQUEST['listing_type'] : $atts['listing_type'];
		$listing_type_text = $listing_type_slug;
		if ( isset( $listing_type_slug ) && ! empty( $listing_type_slug ) && $listing_type_slug != 'all' ) {
			if ( $post = get_page_by_path( $listing_type_slug, OBJECT, 'listing-type' ) ) {
				$id = $post->ID;
				$listing_type_text = get_the_title( $id );
			}
		}
		?>
        <h5>
            <span class="result-clr">
				<?php printf( wp_dp_plugin_text_srt( 'wp_dp_split_map_filter_heading_list' ), ($listing_type_text ) ); ?>
            </span>
        </h5>
    </div>
</div>

<aside class="filters-sidebar <?php echo esc_html( $sidebar_filter_columns ); ?>">

    <div class="wp-dp-filters">
		<?php
		$search_title = isset( $_REQUEST['search_title'] ) ? $_REQUEST['search_title'] : '';

		$location = isset( $_REQUEST['location'] ) ? $_REQUEST['location'] : '';
		$radius = isset( $_REQUEST['radius'] ) ? $_REQUEST['radius'] : '';
		$listing_price = isset( $_REQUEST['listing_price'] ) ? $_REQUEST['listing_price'] : '';
		$features = isset( $_REQUEST['features'] ) ? $_REQUEST['features'] : '';

		$counter = isset( $atts['listing_counter'] ) && $atts['listing_counter'] != '' ? $atts['listing_counter'] : '';
		$transient_view = wp_dp_get_transient_obj( 'wp_dp_listing_view' . $counter );
		$view = isset( $transient_view ) && $transient_view != '' ? $transient_view : $listing_view;

		$wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
				array(
					'simple' => true,
					'cust_id' => "",
					'cust_name' => 'listing_price',
					'std' => esc_html( $listing_price ),
				)
		);

		$wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
				array(
					'simple' => true,
					'cust_id' => "",
					'cust_name' => 'features',
					'std' => esc_html( $features ),
				)
		);
		$reset_var = 0;
		if ( isset( $_REQUEST ) ) {
			foreach ( $_REQUEST as $qry_var => $qry_val ) {
				if ( 'ajax_filter' == $qry_var || 'advanced_search' == $qry_var || 'listing_arg' == $qry_var || 'action' == $qry_var || 'alert-frequency' == $qry_var || 'alerts-name' == $qry_var || 'loc_polygon' == $qry_var || 'alerts-email' == $qry_var )
					continue;
				if ( $qry_val != '' ) {
					$reset_var ++;
				}
			}
		}
		if ( isset( $reset_var ) && $reset_var > 0 && $search_box_sidebar == 'yes' ) {
			listing_search_keywords( $listing_totnum, $element_listing_search_keyword, $_REQUEST, $atts, $page_url );
		}


		if ( $notifications_box == 'yes' && $search_box_sidebar == 'yes' ) {

			//if ( isset( $reset_var ) && $reset_var > 0 ) {
			?>    
			<div class="search-options">
				<div class="reset-holder">
					<?php
					do_action( 'pre_wp_dp_listings_listing' );
					// temperory commented please dont remove
					//do_action( 'wp_dp_save_search_element' );
					//do_action( 'wp_dp_draw_search_element', $draw_on_map_url );
					?>
				</div>
			</div>
			<?php
			//}
		}
		if ( $filter_search_box == 'yes' && $search_box_sidebar == 'yes' ) {
			if ( $filters_accordion == 'yes' ) {
				echo '<div class="filters-options wp-dp-filters-accordion" id="filters-accordion">';
			} else {
				echo '<div class="filters-options">';
			}
			echo wp_dp_wpml_lang_code_field( 'text' );
			$listing_type_name = 'listing_type';
			$wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
					array(
						'simple' => true,
						'cust_id' => "hidden_input-" . $listing_type_name,
						'cust_name' => $listing_type_name,
						'std' => $listing_type,
						'classes' => $listing_type_name,
						//'extra_atr' => 'onchange="wp_dp_listing_content(\'' . $listing_short_counter . '\');"',
					)
			);
			$wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
					array(
						'simple' => true,
						'cust_id' => "current_page_id",
						'cust_name' => 'page_id',
						'std' => esc_html( $page_id ),
					)
			);

			$listing_type_category_name = 'wp_dp_listing_category';
			$listing_category = isset( $_REQUEST['listing_category'] ) ? $_REQUEST['listing_category'] : '';
			$wp_dp_post_listing_types = new Wp_dp_Post_Listing_Types();
			$listing_types_array = $wp_dp_post_listing_types->wp_dp_types_array_callback( 'NULL' );
			$wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
					array(
						'simple' => true,
						'cust_id' => "hidden_input-" . $listing_type_category_name,
						'cust_name' => 'listing_category',
						'std' => $listing_category,
						'classes' => $listing_type_category_name,
						//'extra_atr' => 'onchange="wp_dp_listing_content(\'' . $listing_short_counter . '\');"',
					)
			);
			?>
			<script>
				jQuery(function () {
					'use strict'
					var $type_checkboxes = jQuery("input[type=checkbox].<?php echo esc_html( $listing_type_name ); ?>");
					$type_checkboxes.on('change', function () {
						var val = this.value;
						jQuery('#frm_listing_arg<?php echo esc_html( $listing_short_counter ); ?>').find("input[type=hidden]").val("");
						jQuery('#frm_listing_arg<?php echo esc_html( $listing_short_counter ); ?>').find("input[name=open_house]").val("");
						jQuery('#hidden_input-<?php echo esc_html( $listing_type_category_name ); ?>').val('');
						jQuery('#hidden_input-<?php echo esc_html( $listing_type_name ); ?>').val(val);
						if (jQuery('form[name="wp-dp-top-map-form"]').length > 0) {
							jQuery("form[name='wp-dp-top-map-form'] #wp_dp_listing_category").val('');
							jQuery("form[name='wp-dp-top-map-form'] input[type='radio'][value='" + val + "']").attr('checked', true);
						}
						
						wp_dp_listing_content('<?php echo esc_html( $listing_short_counter ); ?>');
					});

					var $checkboxes = jQuery("input[type=checkbox].<?php echo esc_html( $listing_type_category_name ); ?>");
					$checkboxes.on('change', function () {
						var ids = $checkboxes.filter(':checked').map(function () {
							return this.value;
						}).get().join(',');
						jQuery('#hidden_input-<?php echo esc_html( $listing_type_category_name ); ?>').val(ids);
						wp_dp_listing_content('<?php echo esc_html( $listing_short_counter ); ?>');
					});

					var $parent_checkboxes = jQuery("input[type=checkbox].parent_checked_cat");
					$parent_checkboxes.on('change', function () {
						var current_val = this.value;
						var ids = $checkboxes.filter(':checked').map(function () {
							return this.value;
						}).get().join(',');
						var res = ids.split(",");
						var new_res = new Array();
						var flag = false;
						for (var i = 0; i < res.length; ++i) {
							if (flag == false) {
								new_res.push(res[i]);
							}
							if (res[i] == current_val) {
								flag = true;
							}
						}
						var new_val = new_res.join(",");
						jQuery('#hidden_input-<?php echo esc_html( $listing_type_category_name ); ?>').val(new_val);
						wp_dp_listing_content('<?php echo esc_html( $listing_short_counter ); ?>');
					});
				});
			</script>

			<?php if ( isset( $listing_filter_location ) && $listing_filter_location == 'yes' ) { ?>

				<div class="sidebar-default-fields">
					<div class="field-holder title-search-input">
						<?php wp_get_listing_autocomplete_field( $listing_short_counter, 'sidebar' ); ?>
					</div>


					</label>
					<script type="text/javascript">
						var timer = 0;
						function wp_dp_submit_sidebar_search_form(listing_short_counter) {
							clearTimeout(timer);
							timer = setTimeout(function () {
								wp_dp_listing_content(listing_short_counter);
							}, 1000);
						}
					</script>
				</div>
				<div class="field-holder location-search-input">
					<?php
					$wp_dp_select_display = 1;
					wp_dp_get_custom_locations_listing_filter( '<div id="wp-dp-top-select-holder" class="search-country" style="display:' . wp_dp_allow_special_char( $wp_dp_select_display ) . '"><div class="select-holder">', '</div></div>', false, $listing_short_counter );
					?>
					<script type="text/javascript">
						jQuery(document).ready(function ($) {
							$(".location-field-text<?php echo esc_html( $listing_short_counter ); ?>").on('change', function () {
								wp_dp_submit_sidebar_search_form('<?php echo esc_html( $listing_short_counter ); ?>');
							});
						});
						var timer = 0;
						function wp_dp_submit_sidebar_search_form(listing_short_counter) {
							clearTimeout(timer);
							timer = setTimeout(function () {
								wp_dp_listing_content(listing_short_counter);
							}, 1000);
						}

					</script>
				</div>
			</div>

		<?php } ?>

		<div class="select-categories">
			<h6><?php echo wp_dp_plugin_text_srt( 'wp_dp_listing_leftflter_listing_type' ); ?></h6>
			<ul class="cs-parent-checkbox-list">
				<?php
				if ( $listing_type != '' && $listing_type != 'all' ) {
					if ( $post = get_page_by_path( $listing_type, OBJECT, 'listing-type' ) ) {
						$listing_type_id = $post->ID;
					} else {
						$listing_type_id = 0;
					}
					$listing_type_id = wp_dp_wpml_lang_page_id( $listing_type_id, 'listing-type' );
					?>
					<li>
						<div class="checkbox">
							<?php
							$wp_dp_form_fields_frontend->wp_dp_form_checkbox_render(
									array(
										'simple' => true,
										'cust_id' => 'all_listing_type',
										'cust_name' => '',
										'std' => 'all',
										'classes' => $listing_type_name,
										'extra_atr' => ' onchange="wp_dp_listing_content(\'' . $listing_short_counter . '\');"',
									)
							);
							?>
							<label for="<?php echo force_balance_tags( 'all_listing_type' ) ?>"><?php echo wp_dp_plugin_text_srt( 'wp_dp_listing_leftflter_all_listings' ); ?></label>
						</div>
					</li>
					<?php
					if ( $listing_category == '' ) {
						$type_checkbox_display = 'none';
						$type_label_display = 'block';
					} else {
						$type_checkbox_display = 'block';
						$type_label_display = 'none';
					}
					?>
					<li style="display:none;">
						<div class="checkbox">
							<?php
							$checked = ' checked="checked"';
							$wp_dp_form_fields_frontend->wp_dp_form_checkbox_render(
									array(
										'simple' => true,
										'cust_id' => 'check_listing_type_' . $listing_type_id,
										'cust_name' => '',
										'std' => $listing_type,
										'classes' => $listing_type_name,
										'extra_atr' => $checked,
									)
							);
							?>
							<label for="<?php echo force_balance_tags( 'check_listing_type_' . $listing_type_id ) ?>"><?php echo esc_html( get_the_title( $listing_type_id ) ); ?></label>
						</div>
					</li>
					<li style="display:<?php echo esc_html( $type_checkbox_display ); ?>;">
						<div class="checkbox">
							<?php
							$checked = ' checked="checked"';
							$wp_dp_form_fields_frontend->wp_dp_form_checkbox_render(
									array(
										'simple' => true,
										'cust_id' => 'parent_check_listing_type_' . $listing_type_id,
										'cust_name' => '',
										'std' => $listing_type,
										'classes' => $listing_type_name,
									)
							);
							?>
							<label for="<?php echo force_balance_tags( 'parent_check_listing_type_' . $listing_type_id ) ?>"><?php echo esc_html( get_the_title( $listing_type_id ) ); ?></label>
						</div>
					</li>
					<li style="display:<?php echo esc_html( $type_label_display ); ?>;">
						<strong><?php echo esc_html( get_the_title( $listing_type_id ) ); ?><span class="checked"><i class="icon-check"></i></span></strong>
					</li>
				<?php } ?>
				<?php
				if ( $listing_category != '' ) {
					$listing_type_cats = explode( ",", $listing_category );
					$category_list_flag = 1;
					$checked_cats_counts = count( $listing_type_cats );
					foreach ( $listing_type_cats as $listing_type_cat ) {
						$term = get_term_by( 'slug', $listing_type_cat, 'listing-category' );
						if ( $checked_cats_counts == $category_list_flag ) {
							$cat_checkbox_display = 'none';
							$cat_label_display = 'block';
						} else {
							$cat_checkbox_display = 'block';
							$cat_label_display = 'none';
						}
						?>
						<li style="display:none;">
							<div class="checkbox">
								<?php
								$checked = ' checked="checked"';
								$wp_dp_form_fields_frontend->wp_dp_form_checkbox_render(
										array(
											'simple' => true,
											'cust_id' => 'check_' . $listing_type_category_name . '_' . $category_list_flag,
											'cust_name' => '',
											'std' => $listing_type_cat,
											'classes' => $listing_type_category_name,
											'extra_atr' => $checked,
										)
								);
								?>
								<label for="<?php echo force_balance_tags( 'check_' . $listing_type_category_name . '_' . $category_list_flag ); ?>"><?php echo esc_html( $term->name ); ?></label>
							</div>
						</li>
						<li style="display:<?php echo esc_html( $cat_checkbox_display ); ?>;">
							<div class="checkbox">
								<?php
								$wp_dp_form_fields_frontend->wp_dp_form_checkbox_render(
										array(
											'simple' => true,
											'cust_id' => 'parent_check_' . $listing_type_category_name . '_' . $category_list_flag,
											'cust_name' => '',
											'std' => $listing_type_cat,
											'classes' => 'parent_checked_cat',
										)
								);
								?>
								<label for="<?php echo force_balance_tags( 'parent_check_' . $listing_type_category_name . '_' . $category_list_flag ); ?>"><?php echo esc_html( $term->name ); ?></label>
							</div>
						</li>
						<li style="display:<?php echo esc_html( $cat_label_display ); ?>;">
							<strong><?php echo esc_html( $term->name ); ?><span class="checked"><i class="icon-check"></i></span></strong>
						</li>
						<?php
						$category_list_flag ++;
					}
				}
				?>
			</ul>

			<?php if ( $listing_type == '' || $listing_type == 'all' ) { ?>
				<ul class="cs-checkbox-list">
					<?php
					$listing_type_flag = 1;
					foreach ( $listing_types_array as $key => $value ) {
						$type_totnum = wp_dp_get_listing_type_item_count( $left_filter_count_switch, $key, 'wp_dp_listing_type', $args_count );
						?>
						<li>
							<div class="checkbox">
								<?php
								$wp_dp_form_fields_frontend->wp_dp_form_checkbox_render(
										array(
											'simple' => true,
											'cust_id' => 'listing_type_' . $listing_type_flag,
											'cust_name' => '',
											'std' => $key,
											'classes' => $listing_type_name,
										)
								);
								?>
								<label for="<?php echo force_balance_tags( 'listing_type_' . $listing_type_flag ) ?>"><?php echo force_balance_tags( $value ); ?></label>
								<?php if ( $left_filter_count_switch == 'yes' ) { ?><span>(<?php echo esc_html( $type_totnum ); ?>)</span><?php } ?>
							</div>
						</li>
						<?php $listing_type_flag ++; ?>
					<?php } ?>
				</ul>
				<?php
			}
			$listing_type_cats = array();
			if ( $listing_type != '' && $listing_type != 'all' && $listing_category == '' ) {
				if ( $post = get_page_by_path( $listing_type, OBJECT, 'listing-type' ) ) {
					$listing_type_id = $post->ID;
				} else {
					$listing_type_id = 0;
				}
				$listing_type_id = wp_dp_wpml_lang_page_id( $listing_type_id, 'listing-type' );
				$listing_type_cats = get_post_meta( $listing_type_id, 'wp_dp_listing_type_cats', true );
			} else if ( $listing_type != '' && $listing_type != 'all' && $listing_category != '' ) {
				$category_request_val_arr = explode( ",", $listing_category );
				$last_checked_cat = end( $category_request_val_arr );
				$term = get_term_by( 'slug', $last_checked_cat, 'listing-category' );
				$term_childrens = get_term_children( $term->term_id, 'listing-category' );
				if ( ! empty( $term_childrens ) ) {
					foreach ( $term_childrens as $term_children_id ) {
						$child_term = get_term_by( 'id', $term_children_id, 'listing-category' );
						$listing_type_cats[] = $child_term->slug;
					}
				}
			}

			if ( ! empty( $listing_type_cats ) ) {
				?>
				<ul class="cs-checkbox-list">
					<?php
					$category_list_flag = 1;
					foreach ( $listing_type_cats as $listing_type_cat ) {
						$term = get_term_by( 'slug', $listing_type_cat, 'listing-category' );

						// extra condidation
						$cate_count_arr = array(
							'key' => $listing_type_category_name,
							'value' => serialize( $term->slug ),
							'compare' => 'LIKE',
						);
						// main query array $args_count
						$cate_totnum = wp_dp_get_item_count( $left_filter_count_switch, $args_count, $cate_count_arr, $listing_type, $listing_short_counter, $atts, $listing_type_category_name );
						?>
						<li>
							<div class="checkbox">
								<?php
								$wp_dp_form_fields_frontend->wp_dp_form_checkbox_render(
										array(
											'simple' => true,
											'cust_id' => $listing_type_category_name . '_' . $category_list_flag,
											'cust_name' => '',
											'std' => $listing_type_cat,
											'classes' => $listing_type_category_name,
										)
								);
								?>
								<label for="<?php echo force_balance_tags( $listing_type_category_name . '_' . $category_list_flag ); ?>"><?php echo esc_html( $term->name ); ?></label>
								<?php if ( $left_filter_count_switch == 'yes' ) { ?><span>(<?php echo esc_html( $cate_totnum ); ?>)</span><?php } ?>
							</div>
						</li>
						<?php $category_list_flag ++; ?>
						<?php
					}
					?>
				</ul>
			<?php } ?>
		</div>
		<?php
		// Listing Type Price Filter
		$listing_type_obj = get_page_by_path( $listing_type, OBJECT, 'listing-type' );
		$listing_type_id = isset( $listing_type_obj->ID ) ? $listing_type_obj->ID : '';
		$listing_type_id = wp_dp_wpml_lang_page_id( $listing_type_id, 'listing-type' );
		$wp_dp_listing_type_price = get_post_meta( $listing_type_id, 'wp_dp_listing_type_price', true );
		if ( ( $wp_dp_listing_type_price == 'on' && $listing_type_id != '' ) || $listing_type_id == '' ) {

			$wp_dp_listing_type_price_search_style = get_post_meta( $listing_type_id, 'wp_dp_listing_type_price_search_style', true );
			$wp_dp_price_minimum_options = get_post_meta( $listing_type_id, 'wp_dp_price_minimum_options', true );
			$wp_dp_price_minimum_options = ( ! empty( $wp_dp_price_minimum_options ) ) ? $wp_dp_price_minimum_options : 1;
			$wp_dp_price_max_options = get_post_meta( $listing_type_id, 'wp_dp_price_max_options', true );
			$wp_dp_price_max_options = ( ! empty( $wp_dp_price_max_options ) ) ? $wp_dp_price_max_options : 50; //50000;
			$wp_dp_price_interval = get_post_meta( $listing_type_id, 'wp_dp_price_interval', true );
			$wp_dp_price_interval = ( ! empty( $wp_dp_price_interval ) ) ? $wp_dp_price_interval : 50;
			$wp_dp_price_interval = ( int ) $wp_dp_price_interval;
			$price_counter = $wp_dp_price_minimum_options;
			$price_min = array();
			$price_max = array();
			// gettting all values of price
			$price_min = wp_dp_listing_price_options( $wp_dp_price_minimum_options, $wp_dp_price_max_options, $wp_dp_price_interval, wp_dp_plugin_text_srt( 'wp_dp_listing_leftflter_min' ) );
			$price_max = wp_dp_listing_price_options( $wp_dp_price_minimum_options, $wp_dp_price_max_options, $wp_dp_price_interval, wp_dp_plugin_text_srt( 'wp_dp_listing_leftflter_max' ) );

			if ( $listing_type_id == '' ) {
				$wp_dp_listing_type_price_search_style = 'slider';
			}
			if ( isset( $listing_price_filter ) && $listing_price_filter == 'yes' ) {
				if ( $wp_dp_listing_type_price_search_style != 'dropdown' ) {
					$rand_id = rand( 12345, 54321 );
					$min_val = $wp_dp_price_minimum_options;
					$max_val = end( $price_max );

					if ( $listing_type_id == '' ) {
						$min_val = 1;
						$max_val = 19999;
						$wp_dp_price_interval = 50;
					}

					$query_str_var_name = 'listing_price_range';
					if ( (isset( $min_val ) && $min_val != '') && (isset( $max_val ) && $max_val != '' ) ) {

						$selected_min_val = '';
						$selected_max_val = '';
						$range_complete_str_first = $min_val;
						$range_complete_str_second = $max_val;
						$range_complete_str = '';
						if ( isset( $_REQUEST['price_minimum'] ) && $_REQUEST['price_minimum'] != '' ) {
							$range_complete_str_first = $_REQUEST['price_minimum'];
							$selected_min_val = $_REQUEST['price_minimum'];
						}
						if ( isset( $_REQUEST['price_maximum'] ) && $_REQUEST['price_maximum'] != '' ) {
							$range_complete_str_second = $_REQUEST['price_maximum'];
							$selected_max_val = $_REQUEST['price_maximum'];
						}
						$range_complete_str = $range_complete_str_first . ',' . $range_complete_str_second;

						$wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
								array(
									'simple' => true,
									'cust_id' => "range-hidden-price-minimum-" . $listing_short_counter,
									'cust_name' => 'price_minimum',
									'std' => esc_html( $selected_min_val ),
									'classes' => 'price-minimum',
								)
						);
						$wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
								array(
									'simple' => true,
									'cust_id' => "range-hidden-price-maximum-" . $listing_short_counter,
									'cust_name' => 'price_maximum',
									'std' => esc_html( $selected_max_val ),
									'classes' => 'price-maximum',
								)
						);
						?>
						<div class="select-categories">
							<h6><?php echo wp_dp_plugin_text_srt( 'wp_dp_advance_search_select_price_range' ); ?></h6>
							<div class="price-per-person">
								<?php
								$wp_dp_form_fields_frontend->wp_dp_form_text_render(
										array(
											'cust_name' => '',
											'cust_id' => 'ex16b1' . esc_html( $rand_id . $query_str_var_name ),
											'std' => '',
										)
								);
								?> 
								<span class="rang-text"><?php echo esc_html( $range_complete_str_first ); ?> &nbsp; - &nbsp; <?php echo esc_html( $range_complete_str_second ); ?></span>
							</div>
							<?php
							$increment_step = isset( $wp_dp_price_interval ) ? $wp_dp_price_interval : 1;
							if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
								echo '<script type="text/javascript">
									if (jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").length > 0) {
										jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").slider({
											step : ' . esc_html( $increment_step ) . ',
											min: ' . esc_html( $min_val ) . ',
											max: ' . esc_html( $max_val ) . ',
											value: [ ' . esc_html( $range_complete_str ) . '],
										});
										jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").on("slideStop", function () {
											var rang_slider_val = jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").val();
											var slider_val = rang_slider_val.split(",");
											var price_minimum = slider_val[0], price_maximum = slider_val[1];
											jQuery("#range-hidden-price-minimum-' . $listing_short_counter . '").val(price_minimum);
											jQuery("#range-hidden-price-maximum-' . $listing_short_counter . '").val(price_maximum);
											wp_dp_listing_content("' . esc_html( $listing_short_counter ) . '");
										});
									}
								</script>';
							} else {
								echo '<script type="text/javascript">
									jQuery(window).load(function(){
										if (jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").length > 0) {
											jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").slider({
												step : ' . esc_html( $increment_step ) . ',
												min: ' . esc_html( $min_val ) . ',
												max: ' . esc_html( $max_val ) . ',
												value: [ ' . esc_html( $range_complete_str ) . '],
											});
											jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").on("slideStop", function () {
												var rang_slider_val = jQuery("#ex16b1' . $rand_id . $query_str_var_name . '").val();
												var slider_val = rang_slider_val.split(",");
												var price_minimum = slider_val[0], price_maximum = slider_val[1];
												jQuery("#range-hidden-price-minimum-' . $listing_short_counter . '").val(price_minimum);
												jQuery("#range-hidden-price-maximum-' . $listing_short_counter . '").val(price_maximum);
												wp_dp_listing_content("' . esc_html( $listing_short_counter ) . '");
											});
										}
									});
								</script>';
							}
							?>
						</div>
						<?php
					}
				} else {
					?>
					<div class="wp-dp-min-max-price">
						<div class="select-categories">
							<h6><?php echo wp_dp_plugin_text_srt( 'wp_dp_listing_leftflter_price' ); ?></h6>
							<ul>
								<li>
									<?php
									$price_min_checked = ( isset( $_REQUEST['price_minimum'] ) && $_REQUEST['price_minimum'] ) ? $_REQUEST['price_minimum'] : '';
									$wp_dp_form_fields_frontend->wp_dp_form_select_render(
											array(
												'simple' => true,
												'cust_name' => 'price_minimum',
												'std' => $price_min_checked,
												'classes' => 'chosen-select-no-single',
												'options' => $price_min,
												'extra_atr' => 'onchange="wp_dp_listing_content(\'' . $listing_short_counter . '\');"',
											)
									);
									?>
								</li>
							</ul>
						</div>
						<div class="select-categories">
							<h6>&nbsp;</h6>
							<ul>
								<li>
									<?php
									$price_max_checked = ( isset( $_REQUEST['price_maximum'] ) && $_REQUEST['price_maximum'] ) ? $_REQUEST['price_maximum'] : '';
									$wp_dp_form_fields_frontend->wp_dp_form_select_render(
											array(
												'simple' => true,
												'cust_name' => 'price_maximum',
												'std' => $price_max_checked,
												'classes' => 'chosen-select-no-single',
												'options' => $price_max,
												'extra_atr' => 'onchange="wp_dp_listing_content(\'' . $listing_short_counter . '\');"',
											)
									);
									?>
								</li>
							</ul>
						</div>
					</div>
					<?php
				}
			}
		}

		// $listing_type getting from shortcode backend element
		if ( isset( $listing_type ) && $listing_type != '' ) {
			$wp_dp_listing_type_cus_fields = $wp_dp_post_listing_types->wp_dp_types_custom_fields_array( $listing_type );
			$wp_dp_fields_output = '';
			if ( is_array( $wp_dp_listing_type_cus_fields ) && sizeof( $wp_dp_listing_type_cus_fields ) > 0 ) {
				$custom_field_flag = 1;
				foreach ( $wp_dp_listing_type_cus_fields as $cus_fieldvar => $cus_field ) {

					$all_item_empty = 0;
					$query_str_var_name = '';
					if ( isset( $cus_field['options']['value'] ) && is_array( $cus_field['options']['value'] ) ) {

						foreach ( $cus_field['options']['value'] as $cus_field_options_value ) {

							if ( $cus_field_options_value != '' ) {
								$all_item_empty = 0;
								break;
							} else {
								$all_item_empty = 1;
							}
						}
					}
					if ( isset( $cus_field['enable_srch'] ) && $cus_field['enable_srch'] == 'yes' && ($all_item_empty == 0) ) {
						$query_str_var_name = $cus_field['meta_key'];
						$active_tab = ( isset( $_REQUEST[$query_str_var_name] ) && $_REQUEST[$query_str_var_name] != '' ) ? ' in' : '';
						$accordtion_tab = ( $filters_accordion == 'yes' ) ? ' accordion-categories' : '';
						?> 
						<div class="select-categories<?php echo esc_attr( $accordtion_tab ); ?>">
							<?php if ( $filters_accordion == 'yes' ) { ?>
								<h6 data-toggle="collapse" class="collapsed" data-parent = "#filters-accordion" href = "#collapse-<?php echo esc_attr( $query_str_var_name ); ?>"><?php echo esc_html( $cus_field['label'] ); ?></h6>
							<?php } else { ?>
								<h6><?php echo esc_html( $cus_field['label'] ); ?></h6>
								<?php
							}
							if ( $cus_field['type'] == 'dropdown' && $cus_field['multi'] != 'yes' ) {
								?>
								<?php wp_dp_listing_search_reset_field( $_REQUEST, $page_url, $query_str_var_name ); ?>
							<?php } ?>
							<?php
							if ( $cus_field['type'] == 'dropdown' ) {
								$number_option_flag = 1;
								$cut_field_flag = 0;
								$request_val = isset( $_REQUEST[$query_str_var_name] ) ? $_REQUEST[$query_str_var_name] : '';
								$request_val_arr = explode( ",", $request_val );
								if ( $cus_field['multi'] == 'yes' ) { // if multi select then use hidden for submittion
									$wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
											array(
												'simple' => true,
												'cust_id' => "hidden_input-" . $query_str_var_name,
												'cust_name' => $query_str_var_name,
												'std' => $request_val,
												'classes' => $query_str_var_name,
												'extra_atr' => 'onchange="wp_dp_listing_content(\'' . $listing_short_counter . '\');"',
											)
									);
									?>

									<script>
										jQuery(function () {
											'use strict'
											var $checkboxes = jQuery("input[type=checkbox].<?php echo esc_html( $query_str_var_name ); ?>");
											$checkboxes.on('change', function () {
												var ids = $checkboxes.filter(':checked').map(function () {
													return this.value;
												}).get().join(',');
												jQuery('#hidden_input-<?php echo esc_html( $query_str_var_name ); ?>').val(ids);
												wp_dp_listing_content('<?php echo esc_html( $listing_short_counter ); ?>');
											});

										});
									</script>
									<?php
								}
								?>
								<?php if ( $filters_accordion == 'yes' ) { ?>
									<div id="collapse-<?php echo esc_attr( $query_str_var_name ); ?>" class="panel-collapse collapse<?php echo esc_attr( $active_tab ); ?>">
									<?php } ?>
									<ul class="cs-checkbox-list"><?php
									foreach ( $cus_field['options']['value'] as $cus_field_options_value ) {
										if ( $cus_field['options']['value'][$cut_field_flag] == '' || $cus_field['options']['label'][$cut_field_flag] == '' ) {
											$cut_field_flag ++;
											continue;
										}

										// get count of each item
										// extra condidation
										if ( $cus_field['post_multi'] == 'yes' ) {

											$dropdown_count_arr = array(
												'key' => $query_str_var_name,
												'value' => serialize( $cus_field_options_value ),
												'compare' => 'Like',
											);
										} else {
											$dropdown_count_arr = array(
												'key' => $query_str_var_name,
												'value' => $cus_field_options_value,
												'compare' => '=',
											);
										}
										// main query array $args_count 
										$dropdown_totnum = wp_dp_get_item_count( $left_filter_count_switch, $args_count, $dropdown_count_arr, $listing_type, $listing_short_counter, $atts, $query_str_var_name );
										if ( $cus_field_options_value != '' ) {
											if ( $cus_field['multi'] == 'yes' ) {
												?>
													<li>
														<div class="checkbox">
															<?php
															$checked = '';
															if ( ! empty( $request_val_arr ) && in_array( $cus_field_options_value, $request_val_arr ) ) {
																$checked = ' checked="checked"';
															}
															$wp_dp_form_fields_frontend->wp_dp_form_checkbox_render(
																	array(
																		'simple' => true,
																		'cust_id' => $query_str_var_name . '_' . $number_option_flag,
																		'cust_name' => '',
																		'std' => $cus_field_options_value,
																		'classes' => $query_str_var_name,
																		'extra_atr' => $checked . ' onchange=""',
																	)
															);
															?>

															<label for="<?php echo force_balance_tags( $query_str_var_name . '_' . $number_option_flag ) ?>"><?php echo force_balance_tags( $cus_field['options']['label'][$cut_field_flag] ); ?></label>
															<?php if ( $left_filter_count_switch == 'yes' ) { ?><span>(<?php echo esc_html( $dropdown_totnum ); ?>)</span><?php } ?>
														</div>
													</li>

													<?php
												} else {
													?>
													<li style="">
														<div class="checkbox">
															<?php
															$checked = '';
															if ( ! empty( $request_val ) && $cus_field_options_value == $request_val ) {
																$checked = ' checked="checked" ';
															}
															$wp_dp_form_fields_frontend->wp_dp_form_radio_render(
																	array(
																		'simple' => true,
																		'cust_id' => $query_str_var_name . '_' . $number_option_flag,
																		'cust_name' => $query_str_var_name,
																		'std' => $cus_field_options_value,
																		'extra_atr' => $checked . ' onchange="wp_dp_listing_content(\'' . esc_html( $listing_short_counter ) . '\');"',
																	)
															);
															?>
															<label for="<?php echo force_balance_tags( $query_str_var_name . '_' . $number_option_flag ) ?>"><?php echo force_balance_tags( $cus_field['options']['label'][$cut_field_flag] ); ?></label>
															<?php if ( $left_filter_count_switch == 'yes' ) { ?><span>(<?php echo esc_html( $dropdown_totnum ); ?>)</span><?php } ?>
														</div>
													</li>
													<?php
												}
											}
											$number_option_flag ++;
											$cut_field_flag ++;
										}
										?>
									</ul>
									<?php if ( $filters_accordion == 'yes' ) { ?>
									</div>
								<?php } ?>
								<?php
							} else if ( $cus_field['type'] == 'text' || $cus_field['type'] == 'email' || $cus_field['type'] == 'url' ) {
								?>
								<div class="select-categories">
									<?php
									$wp_dp_form_fields_frontend->wp_dp_form_text_render(
											array(
												'id' => $query_str_var_name,
												'cust_name' => $query_str_var_name,
												'classes' => 'form-control',
												'std' => isset( $_REQUEST[$query_str_var_name] ) ? $_REQUEST[$query_str_var_name] : '',
												'extra_atr' => 'onchange="wp_dp_listing_content(\'' . $listing_short_counter . '\');"',
											)
									);
									?>
								</div>   
								<?php
							} else if ( $cus_field['type'] == 'number' ) {

								$value = isset( $_REQUEST[$query_str_var_name] ) ? $_REQUEST[$query_str_var_name] : '';
								$wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
										array(
											'simple' => true,
											'cust_id' => "number-hidden-" . $query_str_var_name,
											'cust_name' => wp_dp_allow_special_char( $query_str_var_name ),
											'std' => esc_html( $value ),
										)
								);
								?>
								<div class="select-categories">
									<ul class="minimum-loading-list">
										<li>
											<div class="spinner-btn input-group spinner">
												<?php
												if ( isset( $cus_field['fontawsome_icon'] ) && $cus_field['fontawsome_icon'] != '' ) {
													wp_enqueue_style( 'cs_icons_data_css_' . $cus_field['fontawsome_icon_group'] );
													?>
													<span><i class="<?php echo esc_html( $cus_field['fontawsome_icon'] ); ?>"></i></span>
													<?php
												}
												$value = isset( $_REQUEST[$query_str_var_name] ) ? $_REQUEST[$query_str_var_name] : $cus_field['default_value'];

												$wp_dp_form_fields_frontend->wp_dp_form_text_render(
														array(
															'id' => 'wp_dp_' . wp_dp_allow_special_char( $query_str_var_name ),
															'cust_name' => '',
															'classes' => "num-input" . esc_html( $query_str_var_name ) . " form-control",
															'std' => isset( $value ) && $value != '' ? $value : 0,
														)
												);
												?>
												<span class="list-text"><?php echo esc_html( $cus_field['label'] ); ?></span>
												<div class="input-group-btn-vertical">
													<button class="btn-decrement<?php echo esc_html( $query_str_var_name ); ?> caret-btn btn-default " type="button"><i class="icon-minus-circle"></i></button>
													<button class="btn-increment<?php echo esc_html( $query_str_var_name ); ?> caret-btn btn-default" type="button"><i class="icon-plus-circle"></i></button>
												</div>
											</div>
										</li>
									</ul>
									<script type="text/javascript">
										jQuery(document).ready(function ($) {
											$(".num-input<?php echo esc_html( $query_str_var_name ); ?>").keypress(function (e) {
												if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
													return false;
												}
											});
											$('.select-categories .spinner .btn-increment<?php echo esc_html( $query_str_var_name ); ?>').on('click', function () {
												var field_value = $('.select-categories .spinner .num-input<?php echo esc_html( $query_str_var_name ); ?>').val();

												field_value = field_value || 0;

												$('.select-categories .spinner .num-input<?php echo esc_html( $query_str_var_name ); ?>').val(parseInt(field_value, 10) + 1);
												var selected_num = parseInt(field_value, 10) + 1;
												$('.select-categories #number-hidden-<?php echo esc_html( $query_str_var_name ); ?>').val(selected_num);

												submit_left_sidebar_form();
											});
											$('.select-categories .spinner .btn-decrement<?php echo esc_html( $query_str_var_name ); ?>').on('click', function () {
												var field_value = $('.select-categories .spinner .num-input<?php echo esc_html( $query_str_var_name ); ?>').val();
												field_value = field_value || 0;
												var val = parseInt(field_value, 10);
												if (val < 1) {
													//return;
												}
												var minus_val = val - 1;
												if (minus_val < 0) {
													minus_val = 0;
												}
												$('.select-categories .spinner .num-input<?php echo esc_html( $query_str_var_name ); ?>').val(minus_val);
												var selected_num = minus_val;
												$('.select-categories #number-hidden-<?php echo esc_html( $query_str_var_name ); ?>').val(selected_num);
												submit_left_sidebar_form();
											});
											$(".select-categories .num-input<?php echo esc_html( $query_str_var_name ); ?>").on('change keydown', function () {
												var field_value = $('.spinner .num-input<?php echo esc_html( $query_str_var_name ); ?>').val();
												field_value = field_value || 0;
												var selected_num = field_value;
												$('.select-categories #number-hidden-<?php echo esc_html( $query_str_var_name ); ?>').val(selected_num);
												submit_left_sidebar_form();
											});
											var timer = 0;
											function submit_left_sidebar_form() {
												clearTimeout(timer);
												timer = setTimeout(function () {
													wp_dp_listing_content('<?php echo wp_dp_allow_special_char( $listing_short_counter ); ?>');
												}, 1000);
											}
										});
									</script>
									<?php ?>
								</div>
								<?php
							} else if ( $cus_field['type'] == 'date' ) {
								wp_enqueue_script( 'bootstrap-datepicker' );
								wp_enqueue_style( 'datetimepicker' );
								wp_enqueue_style( 'datepicker' );
								wp_enqueue_script( 'datetimepicker' );
								?>
								<div class="select-categories">
									<div class="cs-datepicker">
										<div class="datepicker-text-bottom"><i class="<?php echo wp_dp_cs_allow_special_char( $cus_field['fontawsome_icon'] ); ?>"></i> </div>
										<label id="Deadline" class="cs-calendar-from">
											<?php
											$wp_dp_form_fields_frontend->wp_dp_form_text_render(
													array(
														'id' => 'from' . $query_str_var_name,
														'cust_name' => 'from' . $query_str_var_name,
														'classes' => 'form-control',
														'std' => isset( $_REQUEST['from' . $query_str_var_name] ) ? $_REQUEST['from' . $query_str_var_name] : '',
														'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_leftflter_fron_date' ) . '" onchange="wp_dp_listing_content(\'' . $listing_short_counter . '\');"',
													)
											);
											?>

										</label>
									</div>
									<div class="cs-datepicker">
										<div class="datepicker-text-bottom"><i class="<?php echo wp_dp_cs_allow_special_char( $cus_field['fontawsome_icon'] ); ?>"></i> </div>
										<label id="Deadline" class="cs-calendar-to">
											<?php
											$wp_dp_form_fields_frontend->wp_dp_form_text_render(
													array(
														'id' => 'to' . $query_str_var_name,
														'cust_name' => 'to' . $query_str_var_name,
														'classes' => 'form-control',
														'std' => isset( $_REQUEST['to' . $query_str_var_name] ) ? $_REQUEST['to' . $query_str_var_name] : '',
														'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_leftflter_to_date' ) . '" onchange="wp_dp_listing_content(\'' . $listing_short_counter . '\');"',
													)
											);
											?>

										</label>
									</div>
								</div>
								<?php
								echo '<script>
                                        jQuery( document ).ready(function() {
                                                if (jQuery(".cs-calendar-from input").length != "") {
                                                jQuery(".cs-calendar-from input").datetimepicker({
                                                    timepicker:false,
                                                    format:	"Y/m/d",
													scrollInput: false
                                                });
                                            }
                                            if (jQuery(".cs-calendar-to input").length != "") {
                                                jQuery(".cs-calendar-to input").datetimepicker({
                                                    timepicker:false,
                                                    format:	"Y/m/d",
													scrollInput: false
                                                });
                                            }
                                        });
                                        </script>';
							} elseif ( $cus_field['type'] == 'range' ) {
								$range_random_id = rand( 123, 32 );
								$range_min = $cus_field['min'];
								$range_max = $cus_field['max'];
								$range_increment = $cus_field['increment'];
								$filed_type = $cus_field['srch_style']; //input, slider, input_slider
								if ( strpos( $filed_type, '-' ) !== FALSE ) {
									$filed_type_arr = explode( "_", $filed_type );
								} else {
									$filed_type_arr[0] = $filed_type;
								}
								$range_flag = 0;
								while ( count( $filed_type_arr ) > $range_flag ) {
									if ( $filed_type_arr[$range_flag] == 'input' ) {
//                                                            }
									} elseif ( $filed_type_arr[$range_flag] == 'slider' ) { // if slider style
										if ( (isset( $cus_field['min'] ) && $cus_field['min'] != '') && (isset( $cus_field['max'] ) && $cus_field['max'] != '' ) ) {
											$range_complete_str_first = "";
											$range_complete_str_second = "";
											$range_complete_str = '';
											if ( isset( $_REQUEST[$query_str_var_name] ) ) {
												$range_complete_str = $_REQUEST[$query_str_var_name];
												$range_complete_str_val = $cus_field['min'] . ',' . $cus_field['max'];
												$range_complete_str_arr = explode( ",", $range_complete_str );
												$range_complete_str_first = isset( $range_complete_str_arr[0] ) ? $range_complete_str_arr[0] : '';
												$range_complete_str_second = isset( $range_complete_str_arr[1] ) ? $range_complete_str_arr[1] : '';
											} else {
												$range_complete_str = $cus_field['min'] . ',' . $cus_field['max'];
												$range_complete_str_val = '';
												$range_complete_str_first = $cus_field['min'];
												$range_complete_str_second = $cus_field['max'];
											}

											$wp_dp_form_fields_frontend->wp_dp_form_hidden_render(
													array(
														'simple' => true,
														'cust_id' => "range-hidden-" . $query_str_var_name . $range_random_id,
														'cust_name' => $query_str_var_name,
														'std' => esc_html( $range_complete_str_val ),
														'classes' => $query_str_var_name,
														'extra_atr' => 'onchange="wp_dp_listing_content(\'' . $listing_short_counter . '\');"',
													)
											);
											?>
											<div class="price-per-person"> 
												<?php
												$wp_dp_form_fields_frontend->wp_dp_form_text_render(
														array(
															'cust_id' => 'ex16b2' . esc_html( $query_str_var_name . $range_random_id ),
															'cust_name' => '',
															'classes' => '',
															'std' => '',
														)
												);
												?>
												<span class="rang-text"><?php echo esc_html( $range_complete_str_first ); ?> &nbsp; - &nbsp; <?php echo esc_html( $range_complete_str_second ); ?></span>
											</div>
											<?php
											$increment_step = isset( $cus_field['increment'] ) ? $cus_field['increment'] : 1;
											if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
												echo '<script>
														if (jQuery("#ex16b2' . $query_str_var_name . $range_random_id . '").length > 0) {
															jQuery("#ex16b2' . $query_str_var_name . $range_random_id . '").slider({
																step : ' . esc_html( $increment_step ) . ',
																min: ' . esc_html( $cus_field['min'] ) . ',
																max: ' . esc_html( $cus_field['max'] ) . ',
																value: [ ' . esc_html( $range_complete_str ) . '],


															});
															jQuery("#ex16b2' . $query_str_var_name . $range_random_id . '").on("slideStop", function () {
																var rang_slider_val = jQuery("#ex16b2' . $query_str_var_name . $range_random_id . '").val();
																jQuery("#range-hidden-' . $query_str_var_name . $range_random_id . '").val(rang_slider_val); 
																wp_dp_listing_content("' . esc_html( $listing_short_counter ) . '");
															});
														}
													</script>';
											} else {
												echo '<script>
														jQuery( document ).ready(function() {
															if (jQuery("#ex16b2' . $query_str_var_name . $range_random_id . '").length > 0) {
																jQuery("#ex16b2' . $query_str_var_name . $range_random_id . '").slider({
																	step : ' . esc_html( $increment_step ) . ',
																	min: ' . esc_html( $cus_field['min'] ) . ',
																	max: ' . esc_html( $cus_field['max'] ) . ',
																	value: [ ' . esc_html( $range_complete_str ) . '],


																});
																jQuery("#ex16b2' . $query_str_var_name . $range_random_id . '").on("slideStop", function () {
																	var rang_slider_val = jQuery("#ex16b2' . $query_str_var_name . $range_random_id . '").val();
																	jQuery("#range-hidden-' . $query_str_var_name . $range_random_id . '").val(rang_slider_val); 
																	wp_dp_listing_content("' . esc_html( $listing_short_counter ) . '");
																});
															}
														});
													</script>';
											}
										}
									}
									$range_flag ++;
								}
							} else {
								echo esc_html( $cus_field['type'] );
							}
							?>

						</div>
						<?php
					}
					$custom_field_flag ++;
				}
				echo force_balance_tags( $wp_dp_fields_output );
			}
		}
		?>


		<!--</div>-->
		<!--</div>-->
	</div>
	<?php
}
?>
</div><!-- end of filters-->
<div class="listing-filters-ads">
	<?php do_action( 'wp_dp_random_ads', 'listing_banner_leftfilter' );
	?>
</div>
<?php
if ( is_active_sidebar( $wp_dp_listing_sidebar ) ) {
	if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $wp_dp_listing_sidebar ) ) :
		echo '';
	endif;
}
?>
</aside>