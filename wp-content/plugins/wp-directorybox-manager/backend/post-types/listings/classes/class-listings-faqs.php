<?php
/**
 * File Type: Opening Hours
 */
if ( ! class_exists( 'Wp_dp_faqs' ) ) {

	class Wp_dp_faqs {

		public function __construct() {
			add_action( 'listing_options_sidebar_tab', array( $this, 'wp_dp_faqs_admin_sidebar_tab' ), 11 );
			add_action( 'listing_options_tab_container', array( $this, 'wp_dp_faqs_admin_tab_container' ), 11 );
			add_action( 'listing_type_faq_frontend', array( $this, 'listing_type_faq_frontend_callback' ), 11, 1 );
			add_action( 'wp_dp_listing_type_detail_options', array( $this, 'wp_dp_listing_type_detail_options' ), 10, 1 );
			add_action( 'save_post', array( $this, 'wp_dp_save_post_faqs' ));

			/**/
		}
		
		public function wp_dp_listing_type_detail_options($listing_type_id = 0) {
			global $wp_dp_html_fields;
			
			$wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_type_backend_faqs'),
                'desc' =>'',
                'label_desc' => wp_dp_plugin_text_srt('wp_dp_faq_listings_switch_hint'),            
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => 'on',
                    'id' => 'faqs_options_element',
                    'return' => true,
                ),
            );

            $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
		}
		
		public function wp_dp_faqs_admin_sidebar_tab() {
			global $post;
			
			$listing_type_slug = get_post_meta($post->ID, 'wp_dp_listing_type', true);
            $listing_type = get_page_by_path($listing_type_slug, OBJECT, 'listing-type');
            $listing_type_id = isset($listing_type->ID) ? $listing_type->ID : 0;
			
			$listing_type_faqs = get_post_meta( $listing_type_id, 'wp_dp_faqs_options_element', true );
			
			?>
			<li id="listing-types-faqs-side-tab" style="display: <?php echo ($listing_type_faqs == 'on' ? 'block' : 'none') ?>;"><a href="javascript:void(0);" name="#tab-listing_types-settings-faqs"><i class="icon-question_answer"></i><?php echo wp_dp_plugin_text_srt( 'wp_dp_listing_type_backend_faqs' ); ?></a></li>
			<?php
		}

		public function wp_dp_faqs_admin_tab_container() {
			global $post;
			?>
			<div id="tab-listing_types-settings-faqs" class="wp_dp_tab_block" data-title="<?php echo wp_dp_plugin_text_srt( 'wp_dp_listing_type_backend_faqs' ); ?>">
				<?php $this->wp_dp_faqs_items( $post ); ?>
			</div>
			<?php
		}

		public function listing_type_faq_frontend_callback( $listing_id = '' ) {

            $listing_type = get_post_meta( $listing_id, 'wp_dp_listing_type', true );
            $listing_type_obj = get_page_by_path($listing_type, OBJECT, 'listing-type');
            $listing_type_id = isset($listing_type_obj->ID) ? $listing_type_obj->ID : '';
			$wp_dp_faqs_switch = get_post_meta( $listing_type_id, 'wp_dp_faqs_options_element', true );

			$listing_type_slug = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                        $listing_type = get_page_by_path($listing_type_slug, OBJECT, 'listing-type');
                        $listing_type_id = isset($listing_type->ID) ? $listing_type->ID : 0;
			
			$listing_type_faqs = get_post_meta( $listing_type_id, 'wp_dp_faqs_options_element', true );
                        
                        $element_title = get_post_meta( $listing_type_id, 'wp_dp_listing_type_title_faq', true);


			if ($listing_type_faqs != 'on') {
				return;
			}

			$faqs_data = get_post_meta( $listing_id, 'faqs_label', true );

			if ( $wp_dp_faqs_switch != 'on' || (is_array( $faqs_data ) && sizeof( $faqs_data ) > 0) ) {
				return;
			}
			$faqs_data = get_post_meta( $listing_id, 'faqs_label', true );

			$faq_html = '';
			if ( is_array( $faqs_data ) && sizeof( $faqs_data ) > 0 ) {
				foreach ( $faqs_data as $key => $faq ) {


					$faq_html .= '<div class="panel">
                        <div class="panel-heading">
                            <strong class="panel-title">
                                <a data-toggle="collapse" class="collapsed" href="#collapse' . $key . '">' . $faq['faq_title'] . '</a>
                            </strong>
                        </div>
                        <div id="collapse' . $key . '" class="panel-collapse collapse">
                            <div class="panel-body">
								<p>' . force_balance_tags( str_replace( "<br/>", '</p><p>', str_replace( "<br />", '</p><p>', nl2br( $faq['faq_description'] ) ) ) ) . '</p>
                            </div>
                        </div>
                    </div>';
				}
			}
			?>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="listing-detail-faqs">
						<div class="faq panel-group">
							<div class="element-title">
								<h3><?php echo esc_html( $element_title ); ?></h3>
							</div>
							<?php echo force_balance_tags( $faq_html ); ?>
						</div>
					</div>
				</div>
			</div>

			<?php
		}

		public function wp_dp_faqs_items( $post ) {
			global $post, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_static_text;

			$post_id = $post->ID;
			$faqsd_lables = get_post_meta( $post_id, 'faqs_label', true );
			$wp_dp_faqs_switch = get_post_meta( $post_id, 'wp_dp_faqs_switch', true );
			?>
			<div id="tab-faqs_settings">
				<?php
				$post_meta = get_post_meta( get_the_id() );
				$faqs_data = array();
				if ( isset( $post_meta['wp_dp_listing_type_faqs'] ) && isset( $post_meta['wp_dp_listing_type_faqs'][0] ) ) {
					$faqs_data = json_decode( $post_meta['wp_dp_listing_type_faqs'][0], true );
				}
				if ( is_array($faqsd_lables) && count( $faqsd_lables ) > 0 ) {
					$wp_dp_opt_array = array(
						'name' => wp_dp_plugin_text_srt( 'wp_dp_show_all_faqs_switch' ),
						'desc' => '',
						'hint_text' => wp_dp_plugin_text_srt( 'wp_dp_show_all_faqs_switch_desc' ),
						'echo' => true,
						'field_params' => array(
							'std' => $wp_dp_faqs_switch,
							'id' => 'faqs_switch',
							'return' => true,
						),
					);
					$wp_dp_html_fields->wp_dp_checkbox_field( $wp_dp_opt_array );
				}
				?>

				<div class="wp-dp-list-wrap wp-dp-faqs-list-wrap">
					<ul class="wp-dp-list-layout">
						<li class="wp-dp-list-label">
							<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">
								<div class="element-label">
									<label></label>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
								<div class="element-label">
									<label><?php echo wp_dp_plugin_text_srt( 'wp_dp_listing_type_faqs_help_title' ); ?></label>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
								<div class="element-label">
									<label><?php echo wp_dp_plugin_text_srt( 'wp_dp_listing_type_faqs_help_descripion' ); ?> </label>
								</div>
							</div>
						</li>


						<?php
						$counter = 0;
						if ( is_array( $faqsd_lables ) && sizeof( $faqsd_lables ) > 0 ) {
							foreach ( $faqsd_lables as $key => $lable ) {
								?>
								<li class="wp-dp-list-item">
									<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">
										<!--For Simple Input Element-->
										<div class="input-element">
											<div class="input-holder">
												<span class="cntrl-drag-and-drop"><i class="icon-menu2"></i></span>
											</div>
										</div>
									</div>

									<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
										<!--For Simple Input Element-->
										<div class="input-element">
											<div class="input-holder">
												<?php
												$wp_dp_opt_array = array(
													'std' => isset( $lable['faq_title'] ) ? esc_html( $lable['faq_title'] ) : '',
													'cust_name' => 'faqs_label[title][]',
													'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_type_faqs_label' ) . '"',
													'classes' => 'input-field',
												);
												$wp_dp_form_fields->wp_dp_form_text_render( $wp_dp_opt_array );
												?>
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
										<?php
										$wp_dp_opt_array = array(
											'std' => isset( $lable['faq_description'] ) ? esc_html( $lable['faq_description'] ) : '',
											'cust_name' => 'faqs_label[description][]',
											'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_listing_type_faqs_description' ) . '"',
											'classes' => '',
										);
										$wp_dp_form_fields->wp_dp_form_textarea_render( $wp_dp_opt_array );
										?>
									</div>


									<a href="javascript:void(0);" class="wp-dp-dpove wp-dp-parent-li-dpove"><i class="icon-close2"></i></a>
								</li>
								<?php
								$counter ++;
							}
						}
						?>
					</ul>        
					<ul class="wp-dp-list-button-ul">
						<li class="wp-dp-list-button">
							<div class="input-element">
								<a href="javascript:void(0);" id="click-more" class="wp-dp-add-more cntrl-add-new-row" onclick="duplicate_faq()"><?php echo wp_dp_plugin_text_srt( 'wp_dp_listing_type_meta_faqs_add_row' ); ?></a>
							</div>
						</li>
					</ul>
				</div>

			</div>

			<script type="text/javascript">
				jQuery(document).ready(function () {
					var table_class = ".wp-dp-faqs-list-wrap .wp-dp-list-layout";
					jQuery(table_class).sortable({
						cancel: "input,textarea, .wp-dp-list-label"
					});


				});
				function duplicate_faq() {
					$(".wp-dp-faqs-list-wrap .wp-dp-list-layout").append('<li class="wp-dp-list-item"><div class="col-lg-1 col-md-1 col-sm-6 col-xs-12"><div class="input-element"><div class="input-holder"><span class="cntrl-drag-and-drop"><i class="icon-menu2"></i></span></div></div></div><div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"><div class="input-element"><div class="input-holder"><input type="text" placeholder="<?php echo wp_dp_plugin_text_srt( 'wp_dp_listing_type_faqs_label' ); ?>" class="input-field" name="faqs_label[title][]" value=""></div></div></div><div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"><textarea placeholder="<?php echo wp_dp_plugin_text_srt( 'wp_dp_listing_type_faqs_description' ); ?>" name="faqs_label[description][]" value=""></textarea></div><a href="javascript:void(0);" class="wp-dp-dpove wp-dp-parent-li-dpove"><i class="icon-close2"></i></a></li>');
				}
				jQuery(document).on('click', '.cntrl-delete-rows', function () {
					delete_row_top_faq(this);
					return false;
				});
				function delete_row_top_faq(delete_link) {
					$(delete_link).parent().parent().remove();
				}

			</script>
			<?php
		}

		public function wp_dp_save_post_faqs( $post_id ) {
			
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			if ( get_post_type() == 'listings' ) {

				if ( ! isset( $_POST['faqs_label']['title'] ) || count( $_POST['faqs_label']['title'] ) < 1 ) {
					delete_post_meta( $post_id, 'faqs_label' );
				}
				if ( isset( $_POST['faqs_label']['title'] ) && count( $_POST['faqs_label']['title'] ) > 0 ) {
					foreach ( $_POST['faqs_label']['title'] as $key => $lablel ) {

						if ( $lablel ) {
							$faqs_array[] = array(
								'faq_title' => $lablel,
								'faq_description' => $_POST['faqs_label']['description'][$key],
							);
						}
					}
					update_post_meta( $post_id, 'faqs_label', $faqs_array );
				}
			}
		}

	}

	global $wp_dp_faqs;
	$wp_dp_faqs = new Wp_dp_faqs();
}