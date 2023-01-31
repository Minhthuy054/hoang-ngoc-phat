<?php
/**
 * File Type: FAQ'S
 */
if ( ! class_exists( 'Wp_dp_faqs_frontend' ) ) {

	class Wp_dp_faqs_frontend {

		public function __construct() {
			add_action('wp_enqueue_scripts', array( $this, 'wp_dp_faq_popup_style'), 5);
			add_filter( 'wp_dp_photos_epc_tab', array( $this, 'wp_dp_photos_epc_tab_callback' ), 11, 3 );
			add_action( 'wp_ajax_add_faq_to_list', array( $this, 'wp_dp_add_faq_to_list_callback' ), 11 );
			add_action( 'wp_ajax_nopriv_add_faq_to_list', array( $this, 'wp_dp_add_faq_to_list_callback' ), 11 );
			add_action( 'wp_ajax_edit_faq_to_list', array( $this, 'wp_dp_edit_faq_to_list_callback' ), 11 );
			add_action( 'wp_ajax_nopriv_edit_faq_to_list', array( $this, 'wp_dp_edit_faq_to_list_callback' ), 11 );
			add_action( 'wp_dp_photos_epc_tab_save', array( $this, 'wp_dp_photos_epc_tab_save_callback' ), 11, 2 );
		}
		
		function wp_dp_faq_popup_style() {
			wp_enqueue_style('custom-member-style-inline', plugins_url('../../assets/frontend/css/custom_script.css', __FILE__));
			$cs_plugin_options = get_option('cs_plugin_options');
			$cs_custom_css = '#id_confrmdiv
			{
				display: none;
				background-color: #eee;
				border-radius: 5px;
				border: 1px solid #aaa;
				position: fixed;
				width: 300px;
				left: 50%;
				margin-left: -150px;
				padding: 6px 8px 8px;
				box-sizing: border-box;
				text-align: center;
			}
			#id_confrmdiv .button {
				background-color: #ccc;
				display: inline-block;
				border-radius: 3px;
				border: 1px solid #aaa;
				padding: 2px;
				text-align: center;
				width: 80px;
				cursor: pointer;
			}
			#id_confrmdiv .button:hover
			{
				background-color: #ddd;
			}
			#confirmBox .message
			{
				text-align: left;
				margin-bottom: 8px;
			}';
			wp_add_inline_style('custom-member-style-inline', $cs_custom_css);
		}

		public function wp_dp_photos_epc_tab_callback( $listing_id = '', $listing_type_id = '', $photos_epc_tab = array() ) {
			global $wp_dp_form_fields_frontend;
			ob_start();
			
			if ($listing_id > 0) {
				$listing_type_slug = get_post_meta($listing_id, 'wp_dp_listing_type', true);
				$listing_type = get_page_by_path($listing_type_slug, OBJECT, 'listing-type');
				$listing_type_id = isset($listing_type->ID) ? $listing_type->ID : 0;
			}
			
			$listing_type_faqs = get_post_meta( $listing_type_id, 'wp_dp_faqs_options_element', true );
			
			$faqs_data = get_post_meta( $listing_id, 'faqs_label', true );
			$faq_title = isset( $faq_title ) ? $faq_title : '';
			$faq_desc = isset( $faq_desc ) ? $faq_desc : '';
			?>
			<li id="wp-dp-listing-faqs-holder" class="listing-faqs-holder" style="display: <?php echo ($listing_type_faqs == 'on' || $listing_type_id == 0 ? 'block' : 'block') ?>;">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="dashboard-element-title">
							<div class="faq-btn-holder">
								<a data-target="#add-faq" data-toggle="modal" class="add-new-faq-btn" href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt( 'wp_dp_listing_type_meta_faqs_add_row' ); ?></a>
							</div>
						</div>
						<div class="field-holder">
							<ul class="listing-faq-list">
								<?php
								if ( is_array( $faqs_data ) && sizeof( $faqs_data ) > 0 ) {
									foreach ( $faqs_data as $key => $faq ) {
										$faq_title = isset( $faq['faq_title'] ) ? $faq['faq_title'] : '';
										$faq_description = isset( $faq['faq_description'] ) ? $faq['faq_description'] : '';
										$counter = rand( 123456789, 987654321 );
										?>
										<li class="faq faq-<?php echo wp_dp_cs_allow_special_char($counter); ?>">
											<?php
											$wp_dp_opt_array = array(
												'id' => 'faq_title',
												'cust_name' => 'faqs_label[title][]',
												'classes' => 'form-control',
												'std' => esc_html( $faq_title ),
											);
											$wp_dp_form_fields_frontend->wp_dp_form_hidden_render( $wp_dp_opt_array );
											$wp_dp_opt_array = array(
												'id' => 'faq_desc',
												'cust_name' => 'faqs_label[description][]',
												'classes' => 'form-control',
												'std' => esc_html( $faq_description ),
											);
											$wp_dp_form_fields_frontend->wp_dp_form_hidden_render( $wp_dp_opt_array );
											$wp_dp_opt_array = array(
												'id' => 'faq_counter',
												'cust_name' => 'faq_counter',
												'classes' => 'form-control',
												'std' => esc_html( $counter ),
											);
											$wp_dp_form_fields_frontend->wp_dp_form_hidden_render( $wp_dp_opt_array );
											?>
											<div class="faq-drag"><span class="cntrl-drag-and-drop"><i class="icon-menu2"></i></span></div>
											<div class="faq-title"><?php echo esc_html( $faq_title ); ?></div>
											<div class="edit-faq"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt( 'wp_dp_memberlist_edit' ); ?></a></div>
											<div class="remove-faq"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_services_remove'); ?></a></div>
										</li>
										<?php
									}
								}
								?>
							</ul>
						</div>
						<div id="id_confrmdiv">
							<div class="cs-confirm-container">
								<i class="icon-sad"></i>
								<div class="message"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_want_to_profile'); ?></div>
								<a href="javascript:void(0);" id="id_truebtn"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_delete_yes'); ?></a>
								<a href="javascript:void(0);" id="id_falsebtn"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_delete_no'); ?></a>
							</div>
						</div>
						<script type="text/javascript">
							jQuery(document).ready(function () {
								var table_class = ".listing-faq-list";
								jQuery(table_class).sortable({
									cancel: ".faq-title, .edit-faq, .remove-faq"
								});
							});
						</script>
						<div class="modal fade modal-form" id="add-faq" tabindex="-1" role="dialog">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close close-faq" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="faqModalLabel"><?php echo wp_dp_plugin_text_srt( 'wp_dp_add_listing_add_new_faq' ); ?></h4>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="field-holder">
													<?php
													$wp_dp_opt_array = array(
														'id' => 'faq_title',
														'cust_name' => 'faq_title',
														'classes' => 'form-control',
														'std' => '',
														'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_title' ) . ' *"',
													);
													$wp_dp_form_fields_frontend->wp_dp_form_text_render( $wp_dp_opt_array );
													?>
												</div>
											</div>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="field-holder">
													<?php
													$wp_dp_opt_array = array(
														'id' => 'faq_desc',
														'cust_name' => 'faq_desc',
														'classes' => 'form-control',
														'std' => '',
														'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_floor_description' ) . ' *"',
													);
													$wp_dp_form_fields_frontend->wp_dp_form_textarea_render( $wp_dp_opt_array );
													?>
												</div>
											</div>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="field-holder faq-request-holder input-button-loader">
													<?php
													$wp_dp_opt_array = array(
														'std' => wp_dp_plugin_text_srt( 'wp_dp_faq_create_to_list' ),
														'id' => 'add_faq_to_list',
														'cust_name' => 'add_faq_to_list',
														'return' => false,
														'classes' => 'bgcolor',
														'cust_type' => 'submit',
														'force_std' => true,
													);
													$wp_dp_form_fields_frontend->wp_dp_form_text_render( $wp_dp_opt_array );
													?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal fade modal-form" id="edit-faq" tabindex="-1" role="dialog">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close close-faq" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="faqModalLabel"><?php echo wp_dp_plugin_text_srt( 'wp_dp_faq_update_faq' ); ?></h4>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="field-holder">
													<?php
													$wp_dp_opt_array = array(
														'id' => 'faq_title',
														'cust_name' => 'faq_title',
														'classes' => 'form-control',
														'std' => '',
														'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_title' ) . ' *"',
													);
													$wp_dp_form_fields_frontend->wp_dp_form_text_render( $wp_dp_opt_array );
													?>
												</div>
											</div>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="field-holder">
													<?php
													$wp_dp_opt_array = array(
														'id' => 'faq_desc',
														'cust_name' => 'faq_desc',
														'classes' => 'form-control',
														'std' => '',
														'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt( 'wp_dp_floor_description' ) . ' *"',
													);
													$wp_dp_form_fields_frontend->wp_dp_form_textarea_render( $wp_dp_opt_array );
													?>
												</div>
											</div>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="field-holder edit-faq-request-holder input-button-loader">
													<?php
													$wp_dp_opt_array = array(
														'id' => 'faq_counter',
														'cust_name' => 'faq_counter',
														'classes' => 'form-control',
														'std' => '',
													);
													$wp_dp_form_fields_frontend->wp_dp_form_hidden_render( $wp_dp_opt_array );
													$wp_dp_opt_array = array(
														'std' => wp_dp_plugin_text_srt( 'wp_dp_faq_update_to_list' ),
														'id' => 'edit_faq_to_list',
														'cust_name' => 'edit_faq_to_list',
														'return' => false,
														'classes' => 'bgcolor',
														'cust_type' => 'submit',
														'force_std' => true,
													);
													$wp_dp_form_fields_frontend->wp_dp_form_text_render( $wp_dp_opt_array );
													?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</li>
			<?php
			$content = ob_get_clean();
			$photos_epc_tab['content'] = $content;
			return $photos_epc_tab;
		}

		public function wp_dp_edit_faq_to_list_callback() {
			global $wp_dp_form_fields_frontend;
			ob_start();
			$faq_title = isset( $_POST['faq_title'] ) ? $_POST['faq_title'] : '';
			$faq_desc = isset( $_POST['faq_desc'] ) ? $_POST['faq_desc'] : '';
			$faq_counter = isset( $_POST['faq_counter'] ) ? $_POST['faq_counter'] : '';

			if ( $faq_title == '' ) {
				$response_array = array(
					'type' => 'error',
					'msg' => '<p>' . wp_dp_plugin_text_srt( 'wp_dp_faq_title_empty' ) . '</p>',
				);
				echo json_encode( $response_array );
				wp_die();
			}
			if ( $faq_desc == '' ) {
				$response_array = array(
					'type' => 'error',
					'msg' => '<p>' . wp_dp_plugin_text_srt( 'wp_dp_faq_desc_empty' ) . '</p>',
				);
				echo json_encode( $response_array );
				wp_die();
			}

			$counter = rand( 123456789, 987654321 );
			$wp_dp_opt_array = array(
				'id' => 'faq_title',
				'cust_name' => 'faqs_label[title][]',
				'classes' => 'form-control',
				'std' => esc_html( $faq_title ),
			);
			$wp_dp_form_fields_frontend->wp_dp_form_hidden_render( $wp_dp_opt_array );
			$wp_dp_opt_array = array(
				'id' => 'faq_desc',
				'cust_name' => 'faqs_label[description][]',
				'classes' => 'form-control',
				'std' => esc_html( $faq_desc ),
			);
			$wp_dp_form_fields_frontend->wp_dp_form_hidden_render( $wp_dp_opt_array );
			$wp_dp_opt_array = array(
				'id' => 'faq_counter',
				'cust_name' => 'faq_counter',
				'classes' => 'form-control',
				'std' => esc_html( $faq_counter ),
			);
			$wp_dp_form_fields_frontend->wp_dp_form_hidden_render( $wp_dp_opt_array );
			?>
			<div class="faq-drag"><span class="cntrl-drag-and-drop"><i class="icon-menu2"></i></span></div>
			<div class="faq-title"><?php echo esc_html( $faq_title ); ?></div>
			<div class="edit-faq"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt( 'wp_dp_memberlist_edit' ); ?></a></div>
			<div class="remove-faq"><a href="javascript:void(0);"><i class="icon-close"></i></a></div>
			<?php
			$content = ob_get_clean();
			$response_array = array(
				'type' => 'success',
				'msg' => '<p>' . wp_dp_plugin_text_srt( 'wp_dp_faq_updated_to_list' ) . '</p>',
				'html' => $content,
			);
			echo json_encode( $response_array );
			wp_die();
		}

		public function wp_dp_add_faq_to_list_callback() {
			global $wp_dp_form_fields_frontend;

			ob_start();
			$faq_title = isset( $_POST['faq_title'] ) ? $_POST['faq_title'] : '';
			$faq_desc = isset( $_POST['faq_desc'] ) ? $_POST['faq_desc'] : '';

			if ( $faq_title == '' ) {
				$response_array = array(
					'type' => 'error',
					'msg' => '<p>' . wp_dp_plugin_text_srt( 'wp_dp_faq_title_empty' ) . '</p>',
				);
				echo json_encode( $response_array );
				wp_die();
			}
			if ( $faq_desc == '' ) {
				$response_array = array(
					'type' => 'error',
					'msg' => '<p>' . wp_dp_plugin_text_srt( 'wp_dp_faq_desc_empty' ) . '</p>',
				);
				echo json_encode( $response_array );
				wp_die();
			}
			$counter = rand( 123456789, 987654321 );
			?>
			<li class="faq faq-<?php echo wp_dp_cs_allow_special_char($counter); ?>">
				<?php
				$wp_dp_opt_array = array(
					'id' => 'faq_title',
					'cust_name' => 'faqs_label[title][]',
					'classes' => 'form-control',
					'std' => esc_html( $faq_title ),
				);
				$wp_dp_form_fields_frontend->wp_dp_form_hidden_render( $wp_dp_opt_array );
				$wp_dp_opt_array = array(
					'id' => 'faq_desc',
					'cust_name' => 'faqs_label[description][]',
					'classes' => 'form-control',
					'std' => esc_html( $faq_desc ),
				);
				$wp_dp_form_fields_frontend->wp_dp_form_hidden_render( $wp_dp_opt_array );
				$wp_dp_opt_array = array(
					'id' => 'faq_counter',
					'cust_name' => 'faq_counter',
					'classes' => 'form-control',
					'std' => esc_html( $counter ),
				);
				$wp_dp_form_fields_frontend->wp_dp_form_hidden_render( $wp_dp_opt_array );
				?>
				<div class="faq-drag"><span class="cntrl-drag-and-drop"><i class="icon-menu2"></i></span></div>
				<div class="faq-title"><?php echo esc_html( $faq_title ); ?></div>
				<div class="edit-faq"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt( 'wp_dp_memberlist_edit' ); ?></a></div>
				<div class="remove-faq"><a href="javascript:void(0);"><i class="icon-close"></i></a></div>
			</li>
			<?php
			$content = ob_get_clean();
			$response_array = array(
				'type' => 'success',
				'msg' => '<p>' . wp_dp_plugin_text_srt( 'wp_dp_faq_added_to_list' ) . '</p>',
				'html' => $content,
			);
			echo json_encode( $response_array );
			wp_die();
		}

		public function wp_dp_photos_epc_tab_save_callback( $listing_id = '', $data = array() ) {

			if ( $listing_id != '' ) {
				if ( ! isset( $data['faqs_label']['title'] ) || count( $data['faqs_label']['title'] ) < 1 ) {
					delete_post_meta( $listing_id, 'faqs_label' );
				}

                $faqs_array = array();

				if ( isset( $data['faqs_label']['title'] ) && count( $data['faqs_label']['title'] ) > 0 ) {
					foreach ( $data['faqs_label']['title'] as $key => $lablel ) {
							$faqs_array[$key] = array(
								'faq_title' => $lablel,
								'faq_description' => $data['faqs_label']['description'][$key],
							);

					}
					update_post_meta( $listing_id, 'faqs_label', $faqs_array );
				}
			}
		}

	}

	global $wp_dp_faqs_frontend;
	$wp_dp_faqs_frontend = new Wp_dp_faqs_frontend();
}