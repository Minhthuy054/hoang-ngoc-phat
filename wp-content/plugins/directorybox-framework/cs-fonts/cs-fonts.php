<?php
/**
 * Google Fonts
 *
 * @return
 * @package wp_dp_cs-framework
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'wp_dp_google_fonts' ) ) {

	class wp_dp_google_fonts {

		function __construct() {
			define( 'FRAMEWORK_PLUGIN_URL', WP_PLUGIN_URL . '/directorybox-framework/cs-fonts' );
			define( 'FRAMEWORK_CORE_DIR', WP_PLUGIN_DIR . '/directorybox-framework/cs-fonts' );
			add_action( 'admin_menu', array( $this, 'wp_dp_fonts_menu' ), 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'wp_dp_fonts_scripts' ) );
		}

		public function wp_dp_fonts_menu() {
			global $wp_dp_cs_var_frame_static_text;
			
				$wp_dp_cs_var_fonts = wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_fonts_menu_label' );
				$fonts_icon = FRAMEWORK_PLUGIN_URL.'/assets/images/fonts-admin.png';
				//add_menu_page( $wp_dp_cs_var_fonts, $wp_dp_cs_var_fonts, 'manage_options', 'wp_dp_fonts', array( $this, 'wp_dp_fonts' ), $fonts_icon, 45 );
				add_submenu_page( 'wp_dp_directory', $wp_dp_cs_var_fonts, $wp_dp_cs_var_fonts, 'manage_options', 'wp_dp_fonts', array( $this, 'wp_dp_fonts' ) );
			
		}
		
		public function wp_dp_fonts_scripts() {
			$screen = get_current_screen();
                        $page = isset( $_GET['page'] )? $_GET['page'] : '';
			//echo $screen->base;
			//if ( $screen->base == 'toplevel_page_wp_dp_fonts' || $screen->base == 'toplevel_page_wp_dp_fonts' ) {
			if ( $screen->base == 'directory_page_wp_dp_fonts' || $screen->base == 'directory_page_wp_dp_fonts' || $page == 'wp_dp_fonts' ){
				// google fonts style
				wp_enqueue_style( 'wp-dp-google-fonts', FRAMEWORK_PLUGIN_URL . '/assets/css/fonts.css' );
				// google fonts script
				wp_enqueue_script( 'wp-dp-google-fonts', FRAMEWORK_PLUGIN_URL . '/assets/js/google-fonts.js', '', '', true );
				$wp_dp_pt_array = array(
					'page_loading' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_google_fonts_page_is_loading' ),
					'add_to' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_google_fonts_add_to_list' ),
					'added_in' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_google_fonts_added_in_list' ),
					'are_you_sure_remove_font' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_are_you_sure_remove_font' ),
					'yes' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_yes' ),
					'no' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_no' ),
					'seems_dont_have_font' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_seems_dont_have_font' ),
					'font_just_click' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_google_font_just_click' ),
					'not_font_search' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_not_font_search' ),
					'select_all' => wp_dp_cs_var_frame_text_srt( 'wp_dp_google_font_attr_select_all' ),
					'unselect_all' => wp_dp_cs_var_frame_text_srt( 'wp_dp_google_font_attr_unselect_all' ),
					
				);
				wp_localize_script( 'wp-dp-google-fonts', 'wp_dp_google_fonts', $wp_dp_pt_array );
				// custom fonts script
				wp_enqueue_script( 'wp-dp-custom-fonts', FRAMEWORK_PLUGIN_URL . '/assets/js/custom-fonts.js', '', '', true );
				$wp_dp_pt_array = array(
					'fields_error' => wp_dp_cs_var_frame_text_srt( 'wp_dp_custom_fonts_fields_empty' ),
					'added_to' => wp_dp_cs_var_frame_text_srt( 'wp_dp_add_to_selected_custom_fonts' ),
					'added_in' => wp_dp_cs_var_frame_text_srt( 'wp_dp_add_in_selected_custom_fonts' ),
					'yes' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_yes' ),
					'no' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_no' ),
					'are_you_sure_remove_font' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_are_you_sure_remove_font' ),
				);
				wp_localize_script( 'wp-dp-custom-fonts', 'wp_dp_custom_fonts', $wp_dp_pt_array );
			}
			wp_enqueue_script( 'wp-dp-google-fonts-options', FRAMEWORK_PLUGIN_URL . '/assets/js/google-fonts-options.js', '', '', true );
		}

		public function wp_dp_fonts() {
			?>
			<div class="wrap wp-dp-fonts">
				<h2>
					<?php echo wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_fonts_menu_label' ); ?>
				</h2>
			</div>
			<div class="fonts-wrapper">
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#google-fonts"><?php echo wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_google_fonts_label' ); ?></a></li>
					<li><a data-toggle="tab" href="#custom-fonts"><?php echo wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_custom_fonts_label' ); ?></a></li>

				</ul>
				<?php if( isset($_REQUEST['export']) && $_REQUEST['export'] == '1' ){ ?>
					<div class="export-fonts-wrapper">
						<div class="export-btn">
							<a class="export-fonts-btn" href="javascript:void(0);"><?php echo wp_dp_cs_var_frame_text_srt( 'wp_dp_fonts_export_btn_label' ); ?></a>
							<a id="export-fonts" class="export-fonts" href="javascript:void(0);" style="display:none;" download=""><?php echo wp_dp_cs_var_frame_text_srt( 'wp_dp_fonts_export_btn_label' ); ?></a>
						</div>
					</div>
				<?php } ?>
				<div class="clear"></div>
				<div class="tab-content">
					<div id="google-fonts" class="tab-pane fade in active">
						<?php $this->wp_dp_google_fonts_list(); ?>
					</div>
					<div id="custom-fonts" class="tab-pane fade ">
						<?php $this->wp_dp_custom_fonts_list(); ?>
					</div>
				</div>
			</div>
			<?php
		}

		public function wp_dp_google_fonts_list() {
			global $wp_dp_cs_var_form_fields;
			$added_google_fonts = get_option('wp_dp_added_google_fonts');
			?>
			<div class="wp-dp-fonts wp-dp-google-fonts">
				<h2>
					<?php
					$wp_dp_opt_array = array(
						'std' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_refresh_fonts_list' ),
						'cust_id' => 'get-new-google-fonts',
						'cust_name' => 'get-new-google-fonts',
						'cust_type' => 'button',
						'classes' => 'get-new-google-fonts',
						'force_std' => true,
						'extra_atr' => 'style="cursor:pointer"',
					);
					$wp_dp_cs_var_form_fields->wp_dp_cs_var_form_text_render($wp_dp_opt_array);
					?>
					&nbsp;<span class="spinner"></span>
				</h2>
				<div id="wp-dp-fonts-msg" class="wp-dp-fonts-msg"></div>
				<div class="search-fonts-field">
					<?php
					$wp_dp_opt_array = array(
						'std' => '',
						'cust_id' => 'search_google_font',
						'cust_name' => 'search_google_font',
						'classes' => '',
						'force_std' => true,
						'extra_atr' => 'placeholder="'. wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_search_font' ) .'"',
					);
					$wp_dp_cs_var_form_fields->wp_dp_cs_var_form_text_render($wp_dp_opt_array);
					?>
				</div>

				<div class="google-fonts-list-holder">
					<div id="google-fonts-list" style="overflow:auto" data-gstart="0" data-gfetch="20"></div>
					<div id="load-more" class="spinner" style="float:left"></div>
				</div>

				<div class="added-google-fonts-list">
					<h3><?php echo wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_your_selected_google_fonts' ); ?></h3>
					<div id="fonts-selected-wrapper">
						<?php
						$added_google_fonts = get_option( 'wp_dp_added_google_fonts' );
						if ( ! empty( $added_google_fonts ) ) {
							foreach ( $added_google_fonts as $key => $val ) {
								$font_slug = strtolower(str_replace(' ', '-', $val['font_name']));
								?>
								<div class="selected-font">
									<div class="selected-font-top <?php echo ( ! empty( $val['variants'] ) || ! empty( $val['subsets'] )) ? 'have-variants' : ''; ?>">
										<div class="font-header" style="font-family:'<?php echo $val['font_name'] ?>'"><?php echo $val['font_name'] ?></div>
										<?php if ( ! empty( $val['variants'] ) || ! empty( $val['subsets'] ) ) : ?>
											<i class="dashicons dashicons-arrow-down"></i>
										<?php endif; ?>
										<div class="clear"></div>
									</div>
									<span class="font-delete" data-font_name="<?php echo $val['font_name'] ?>"><i class="dashicons dashicons-no-alt"></i></span>
									<?php
									$is_varients = false;
									if ( ! empty( $val['variants'] ) || ! empty( $val['subsets'] ) ) :
										?>
										<div class="selected-font-content">
											<?php
											$lid = str_replace( ' ', '-', $val['font_name'] );
											$variant_font = 'font-family:\'' . $val['font_name'] . '\';';
											if ( ! empty( $val['variants'] ) ) :
												$is_varients = true;
												?>
												<div class="<?php echo esc_html($font_slug); ?> selected-font-varient-wrapper">
													<?php
													foreach ( $val['variants'] as $svkey => $svariants ) {
														$variant_style = $variant_font;
														if ( preg_match( '/italic/i', $svariants['variant_value'] ) )
															$variant_style .= 'font-style:italic;';
														$weight = 'normal';
														if ( $weight = preg_replace( '/\D/', '', $svariants['variant_value'] ) )
															$variant_style .= 'font-weight:' . $weight . ';';
														$tlid = $lid . '-' . $svkey;
														?>
														<span class="font-variant">
															<input type="checkbox" id="<?php echo $tlid ?>" value="<?php echo $svariants['variant_value'] ?>" class="selected-variant-checkbox" <?php echo ($svariants['variant_selected'] == 'true') ? 'checked' : ''; ?> />
															<label style="<?php echo $variant_style; ?>" for="<?php echo $tlid ?>"><?php echo $svariants['variant_value'] ?></label>
														</span>
														<?php
													}
													?>
												</div>
												<?php
											endif;
											if ( ! empty( $val['subsets'] ) ) :
												?>
												<div class="<?php echo esc_html($font_slug); ?> <?php echo ($is_varients) ? 'selected-font-subset-wrapper' : '' ?>">
													<?php
													foreach ( $val['subsets'] as $sbkey => $ssubset ) {
														$slid = $lid . '-subset-' . $sbkey;
														?>
														<span class="font-subset">
															<input type="checkbox" id="<?php echo $slid ?>" value="<?php echo $ssubset['subset_value'] ?>" class="selected-subset-checkbox" <?php echo ($ssubset['subset_selected'] == 'true') ? 'checked' : '' ?> />
															<label style="" for="<?php echo $slid ?>"><?php echo $ssubset['subset_value'] ?></label>
														</span>
														<?php
													}
													?>
												</div>
												<?php
											endif;
											?>
											<div class="font-select-holder">
												<span class="select-butns">
													<a class="select-all" date-font_slug="<?php echo esc_html($font_slug); ?>"><?php echo wp_dp_cs_var_frame_text_srt( 'wp_dp_google_font_attr_select_all' ); ?></a>
													<a class="unselect-all" date-font_slug="<?php echo esc_html($font_slug); ?>"><?php echo wp_dp_cs_var_frame_text_srt( 'wp_dp_google_font_attr_unselect_all' ); ?></a>
												</span>
											</div>
											<input type="button" class="button alignleft update-google-font-button" value="<?php echo __( 'Update font', 'ultimate_vc' ) ?>" data-font_name="<?php echo $val['font_name'] ?>" />
											<span class="spinner fspinner"></span>
											<div class="clear"></div>
										</div>
										<?php
									endif;
									?>
								</div>
								<?php
							}
						}
						?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<?php
		}

		public function wp_dp_custom_fonts_list() {
			global $wp_dp_cs_var_html_fields, $wp_dp_cs_var_form_fields, $wp_dp_cs_var_options;
			?>
			<div class="wp-dp-fonts wp-dp-custom-fonts">
				<a href="javascript:void(0);" id="add_custom_font" class="button add-new-custom-font"><?php echo wp_dp_cs_var_frame_text_srt( 'wp_dp_add_new_custom_font' ); ?></a>
				<div class="clear"></div>
				<?php
				$output = '';
				$output .= '<div id="new-custom-font-outerlay" style="display: none;"><div id="custom-fonts-popup">';
				$output .= '<div class="custom-fonts-heading-area">';
					$output .= '<h5>' . wp_dp_cs_var_frame_text_srt( 'wp_dp_add_new_custom_font' ) . '</h5>';
					$output .= '<a id="close_div" class="btn-close"><i class="icon-cross"></i></a>';
				$output .= '</div>';
				$output .= '<div class="custom-fonts-content">';
				$wp_dp_cs_opt_array = array(
					'name' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_theme_option_custom_font_name' ) . ' *',
					'desc' => '',
					'field_params' => array(
						'std' => '',
						'cust_id' => 'custom_font_name',
						'cust_name' => 'wp_dp_custom_font_name',
						'classes' => '',
						'return' => true,
					),
				);
				$output .= $wp_dp_cs_var_html_fields->wp_dp_cs_var_text_field( $wp_dp_cs_opt_array );

				// woff Font Field
				$wp_dp_cs_opt_array = array(
					'name' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_theme_option_custom_font_woff' ) . ' *',
					'id' => 'custom_font_upload',
					'hint_text' => '',
					'label_desc' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_theme_option_custom_font_woff_hint' ),
				);
				$output .= $wp_dp_cs_var_html_fields->wp_dp_cs_var_opening_field( $wp_dp_cs_opt_array );
				$wp_dp_cs_opt_array = array(
					'std' => '',
					'cust_id' => 'custom_fonts_woff',
					'cust_name' => 'wp_dp_custom_fonts_woff',
					'classes' => 'input-medium',
					'return' => true,
				);
				$output .= $wp_dp_cs_var_form_fields->wp_dp_cs_var_form_text_render( $wp_dp_cs_opt_array );
				$output .= '
							<label class="browse-icon">';
				$wp_dp_cs_opt_array = array(
					'std' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_browse' ),
					'cust_id' => 'wp_dp_custom_fonts_woff',
					'cust_name' => 'custom_fonts_woff',
					'cust_type' => 'button',
					'classes' => 'dp-custom-font left ',
					'return' => true,
				);
				$output .= $wp_dp_cs_var_form_fields->wp_dp_cs_var_form_text_render( $wp_dp_cs_opt_array );
				$output .= '
							</label>';
				$output .= $wp_dp_cs_var_html_fields->wp_dp_cs_var_closing_field( array() );
				// End woff Font Field
				// ttf Font Field
				$wp_dp_cs_opt_array = array(
					'name' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_theme_option_custom_font_ttf' ) . ' *',
					'id' => 'custom_font_upload',
					'hint_text' => '',
					'label_desc' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_theme_option_custom_font_ttf_hint' ),
				);
				$output .= $wp_dp_cs_var_html_fields->wp_dp_cs_var_opening_field( $wp_dp_cs_opt_array );
				$wp_dp_cs_opt_array = array(
					'std' => '',
					'cust_id' => 'custom_fonts_ttf',
					'cust_name' => 'wp_dp_custom_fonts_ttf',
					'classes' => 'input-medium',
					'return' => true,
				);
				$output .= $wp_dp_cs_var_form_fields->wp_dp_cs_var_form_text_render( $wp_dp_cs_opt_array );
				$output .= '
							<label class="browse-icon">';
				$wp_dp_cs_opt_array = array(
					'std' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_browse' ),
					'cust_id' => 'wp_dp_custom_fonts_ttf',
					'cust_name' => 'custom_fonts_ttf',
					'cust_type' => 'button',
					'classes' => 'dp-custom-font left ',
					'return' => true,
				);
				$output .= $wp_dp_cs_var_form_fields->wp_dp_cs_var_form_text_render( $wp_dp_cs_opt_array );
				$output .= '
							</label>';
				$output .= $wp_dp_cs_var_html_fields->wp_dp_cs_var_closing_field( array() );
				// End ttf Font Field
				// svg Font Field
				$wp_dp_cs_opt_array = array(
					'name' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_theme_option_custom_font_svg' ) . ' *',
					'id' => 'custom_font_upload',
					'hint_text' => '',
					'label_desc' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_theme_option_custom_font_svg_hint' ),
				);
				$output .= $wp_dp_cs_var_html_fields->wp_dp_cs_var_opening_field( $wp_dp_cs_opt_array );
				$wp_dp_cs_opt_array = array(
					'std' => '',
					'cust_id' => 'custom_fonts_svg',
					'cust_name' => 'wp_dp_custom_fonts_svg',
					'classes' => 'input-medium',
					'return' => true,
				);
				$output .= $wp_dp_cs_var_form_fields->wp_dp_cs_var_form_text_render( $wp_dp_cs_opt_array );
				$output .= '
							<label class="browse-icon">';
				$wp_dp_cs_opt_array = array(
					'std' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_browse' ),
					'cust_id' => 'wp_dp_custom_fonts_svg',
					'cust_name' => 'custom_fonts_svg',
					'cust_type' => 'button',
					'classes' => 'dp-custom-font left ',
					'return' => true,
				);
				$output .= $wp_dp_cs_var_form_fields->wp_dp_cs_var_form_text_render( $wp_dp_cs_opt_array );
				$output .= '
							</label>';
				$output .= $wp_dp_cs_var_html_fields->wp_dp_cs_var_closing_field( array() );
				// End svg Font Field
				// eot Font Field
				$wp_dp_cs_opt_array = array(
					'name' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_theme_option_custom_font_eot' ) . ' *',
					'id' => 'custom_font_upload',
					'hint_text' => '',
					'label_desc' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_theme_option_custom_font_eot_hint' ),
				);
				$output .= $wp_dp_cs_var_html_fields->wp_dp_cs_var_opening_field( $wp_dp_cs_opt_array );
				$wp_dp_cs_opt_array = array(
					'std' => '',
					'cust_id' => 'custom_fonts_eot',
					'cust_name' => 'wp_dp_custom_fonts_eot',
					'classes' => 'input-medium',
					'return' => true,
				);
				$output .= $wp_dp_cs_var_form_fields->wp_dp_cs_var_form_text_render( $wp_dp_cs_opt_array );
				$output .= '
							<label class="browse-icon">';
				$wp_dp_cs_opt_array = array(
					'std' => wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_browse' ),
					'cust_id' => 'wp_dp_custom_fonts_eot',
					'cust_name' => 'custom_fonts_eot',
					'cust_type' => 'button',
					'classes' => 'dp-custom-font left ',
					'return' => true,
				);
				$output .= $wp_dp_cs_var_form_fields->wp_dp_cs_var_form_text_render( $wp_dp_cs_opt_array );
				$output .= '
							</label>';
				$output .= $wp_dp_cs_var_html_fields->wp_dp_cs_var_closing_field( array() );
				// End eot Font Field

				$output .= '<ul class="wp-dp-list-button-ul">
								<li class="wp-dp-list-button">
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"></div>
									<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
										<div class="input-element">
											<a href="javascript:void(0);" id="click-more" class="wp-dp-add-more cntrl-add-new-row add-custom-font-button" onclick="javascript:wp_dp_add_custom_fonts_list();">' . wp_dp_cs_var_frame_text_srt( 'wp_dp_cs_var_add_custom_fonts_list' ) . '</a>
										</div>
									</div>
								</li>
							</ul>';

				$output .= '</div>';
				$output .= '</div>';
				echo force_balance_tags( $output );
				?>
				<div class="custom-fonts-list-holder">
					<?php
					$wp_dp_custom_fonts_list = get_option( 'wp_dp_custom_fonts_list' );
					$wp_dp_selected_custom_fonts = get_option('wp_dp_selected_custom_fonts');

					if ( isset( $wp_dp_custom_fonts_list['name'] ) && is_array( $wp_dp_custom_fonts_list ) && ! empty( $wp_dp_custom_fonts_list['name'] ) ) {
						foreach ( $wp_dp_custom_fonts_list['name'] as $key => $wp_dp_custom_font ) {
							$font_name = isset( $wp_dp_custom_fonts_list['name'][$key] ) ? $wp_dp_custom_fonts_list['name'][$key] : '';
							$font_woff = isset( $wp_dp_custom_fonts_list['woff'][$key] ) ? $wp_dp_custom_fonts_list['woff'][$key] : '';
							$font_ttf = isset( $wp_dp_custom_fonts_list['ttf'][$key] ) ? $wp_dp_custom_fonts_list['ttf'][$key] : '';
							$font_svg = isset( $wp_dp_custom_fonts_list['svg'][$key] ) ? $wp_dp_custom_fonts_list['svg'][$key] : '';
							$font_eot = isset( $wp_dp_custom_fonts_list['eot'][$key] ) ? $wp_dp_custom_fonts_list['eot'][$key] : '';

							$wp_dp_cs_rand_num = rand( 123456, 987654 );
							$font_added = '';
							$add_fontbtn_label = '';
							$add_fontbtn_label = wp_dp_cs_var_frame_text_srt( 'wp_dp_add_to_selected_custom_fonts' );
							if ( isset( $wp_dp_selected_custom_fonts['name'] ) && ! empty( $wp_dp_selected_custom_fonts['name'] ) && in_array( $font_name, $wp_dp_selected_custom_fonts['name'] ) ) {
								$font_added = 'font-added';
								$add_fontbtn_label = wp_dp_cs_var_frame_text_srt( 'wp_dp_add_in_selected_custom_fonts' );
							}
							if (( isset( $font_woff ) && $font_woff != '' ) && ( isset( $font_woff ) && $font_woff != '' ) && ( isset( $font_woff ) && $font_woff != '' ) && ( isset( $font_woff ) && $font_woff != '' ) ){
								$font_face_html = "<style type=\"text/css\">
								@font-face {
								font-family: '". $font_name ."';
								src: url('" . $font_eot . "');
								src:
								url('" . $font_eot . "?#iefix') format('eot'),
								url('" . $font_woff . "') format('woff'),
								url('" . $font_ttf . "') format('truetype'),
								url('" . $font_svg . "#wp_dp_cs_var_custom_font') format('svg');
								font-weight: 400 !important;
								font-style: normal;
								}</style>";
							}
							echo $font_face_html;
							?>
							<div class="custom-font">
								<div class="custom-font-header font-header" style="font-family:'<?php echo esc_html( $font_name ); ?>'"><?php echo esc_html( $font_name ); ?></div>
								<input class="add-custom-font alignright <?php echo esc_html( $font_added ); ?>" data-font_key="<?php echo esc_html( $key ); ?>" data-font_name="<?php echo esc_html( $font_name ); ?>" data-woff_font="<?php echo esc_html( $font_woff ); ?>" data-ttf_font="<?php echo esc_html( $font_ttf ); ?>" data-svg_font="<?php echo esc_html( $font_svg ); ?>" data-eot_font="<?php echo esc_html( $font_eot ); ?>" value="<?php echo esc_html( $add_fontbtn_label ); ?>" type="button">
								<span class="custom-font-delete alignright" data-font_name="<?php echo esc_html( $font_name ); ?>" data-font_key="<?php echo intval( $key ); ?>"><i class="dashicons dashicons-no-alt"></i></span>
								<span class="spinner" style="float: right; visibility: visible; display: none;"></span>
								<div class="clear"></div>
							</div>
							<?php
						}
					}
					?>
				</div>
				<div class="added-custom-fonts-list">
					<h3><?php echo wp_dp_cs_var_frame_text_srt( 'wp_dp_your_selected_custom_fonts' ); ?></h3>
					<div id="custom-fonts-selected-wrapper">
						<?php 
						if ( isset( $wp_dp_selected_custom_fonts['name'] ) && is_array( $wp_dp_selected_custom_fonts ) && ! empty( $wp_dp_selected_custom_fonts['name'] ) ) {
							foreach ( $wp_dp_selected_custom_fonts['name'] as $key => $wp_dp_custom_font ) {
								$font_name = isset( $wp_dp_selected_custom_fonts['name'][$key] ) ? $wp_dp_selected_custom_fonts['name'][$key] : '';
								$font_woff = isset( $wp_dp_selected_custom_fonts['woff'][$key] ) ? $wp_dp_selected_custom_fonts['woff'][$key] : '';
								$font_ttf = isset( $wp_dp_selected_custom_fonts['ttf'][$key] ) ? $wp_dp_selected_custom_fonts['ttf'][$key] : '';
								$font_svg = isset( $wp_dp_selected_custom_fonts['svg'][$key] ) ? $wp_dp_selected_custom_fonts['svg'][$key] : '';
								$font_eot = isset( $wp_dp_selected_custom_fonts['eot'][$key] ) ? $wp_dp_selected_custom_fonts['eot'][$key] : '';
								if (( isset( $font_woff ) && $font_woff != '' ) && ( isset( $font_woff ) && $font_woff != '' ) && ( isset( $font_woff ) && $font_woff != '' ) && ( isset( $font_woff ) && $font_woff != '' ) ){
								$font_face_html = "<style type=\"text/css\">
									@font-face {
									font-family: '". $font_name ."';
									src: url('" . $font_eot . "');
									src:
									url('" . $font_eot . "?#iefix') format('eot'),
									url('" . $font_woff . "') format('woff'),
									url('" . $font_ttf . "') format('truetype'),
									url('" . $font_svg . "#wp_dp_cs_var_custom_font') format('svg');
									font-weight: 400 !important;
									font-style: normal;
									}</style>";
								}
								echo $font_face_html;
								?>
								<div class="selected-custom-font">
									<div class="selected-font-top ">
										<div class="font-header" style="font-family:'<?php echo esc_html( $font_name ); ?>'"><?php echo esc_html( $font_name ); ?></div>
										<div class="clear"></div>
									</div>
									<span class="selected-custom-font-delete" data-font_name="<?php echo esc_html( $font_name ); ?>" data-font_key="<?php echo intval( $key ); ?>"><i class="dashicons dashicons-no-alt"></i></span>
								</div>
								<?php
							}
						}
						?>
					</div>
				</div>

				<div class="clear"></div>
			</div>
			<?php
		}

	}

	new wp_dp_google_fonts;
}