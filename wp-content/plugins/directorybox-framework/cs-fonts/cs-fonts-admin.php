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

if(!class_exists('wp_dp_google_fonts_admin')){
	class wp_dp_google_fonts_admin{
		function __construct(){
			add_action('wp_ajax_wp_dp_google_fonts_refresh', array($this, 'wp_dp_refresh_google_fonts_list'));
			add_action('wp_ajax_wp_dp_get_google_fonts', array($this, 'wp_dp_get_google_fonts_list'));
			add_action('wp_ajax_wp_dp_add_selected_google_font', array($this, 'wp_dp_add_selected_google_font'));
			add_action('wp_ajax_wp_dp_delete_google_font', array($this, 'wp_dp_delete_selected_google_font'));
			add_action('wp_ajax_wp_dp_update_google_font', array($this, 'wp_dp_update_selected_google_font'));
			add_action('wp_ajax_wp_dp_selected_google_font_att_field', array($this, 'wp_dp_selected_google_font_att_field'));
			add_action( 'wp_ajax_wp_dp_add_custom_fonts_list', array($this, 'wp_dp_add_custom_fonts_list') );
			add_action( 'wp_ajax_wp_dp_delete_custom_font_list', array($this, 'wp_dp_delete_custom_font_list') );
			add_action( 'wp_ajax_wp_dp_add_selected_custom_font', array($this, 'wp_dp_add_selected_custom_font') );
			add_action( 'wp_ajax_wp_dp_delete_selected_custom_font', array($this, 'wp_dp_delete_selected_custom_font') );
			add_action( 'wp_ajax_wp_dp_export_fonts', array($this, 'wp_dp_export_fonts_callback') );
			
		}
		
		public function wp_dp_refresh_google_fonts_list(){
			$fonts = array();
			$wp_dp_google_fonts_count = 0;
			$error = false;
			$wp_dp_google_fonts = get_option('wp_dp_google_fonts');
			if(!empty($wp_dp_google_fonts)) {
				$wp_dp_google_fonts_count = count($wp_dp_google_fonts);
			}
			try{
				$fonts = file_get_contents($filename = 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyD_6TR2RyX2VRf8bABDRXCcVqdMXB5FQvs');
				$fonts = json_decode($fonts);
				
			}catch(Exception $e) {
				$error = true;
			}
			if($error == true || count($fonts) == 0){
				$error = false;
				try{
					$fonts =	wp_remote_get($filename = 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyD_6TR2RyX2VRf8bABDRXCcVqdMXB5FQvs');
					$fonts =	json_decode($fonts['body']);
					
				}catch(Exception $e) {
					$error = true;
				}
			}
			if($error != true || count($fonts) == 0){
				$google_fonts = $fonts->items;
				$google_font_count = count($google_fonts);
				update_option('wp_dp_google_fonts',$google_fonts);
				$response['count'] = ($google_font_count - $wp_dp_google_fonts_count);
				$response['message'] = sprintf(wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_new_fonts_added'),($google_font_count - $wp_dp_google_fonts_count));
			}else{
				$response['count'] = 0;
				$response['message'] = wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_fonts_added_error');
			}
			echo json_encode($response);
			die();
		}
		
		public function wp_dp_get_google_fonts_list(){
			$google_fonts = get_option('wp_dp_google_fonts');
			$response = $fonts = array();
            $search = '';
			if(!empty($google_fonts)) :
				$selected_google_fonts = get_option('wp_dp_added_google_fonts');
				$temp_selected = array();
				if(!empty($selected_google_fonts ))
				{
					foreach($selected_google_fonts as $selected_font)
						array_push($temp_selected, $selected_font['font_name']);
				}
				$start_count = $_POST['start'];
				$fetch_count = $_POST['fetch'];
				$search = trim($_POST['search']);
				$font_slice_array = array();
				if($search != '') {
					$temp = array();
					foreach($google_fonts as $tkey => $tfont){
						if(stripos($tfont->family, $search) !== false){
							array_push($temp, $google_fonts[$tkey]);
						}
					}
					$font_slice_array = $temp;
				}
				else {
					$font_slice_array = array_slice($google_fonts, $start_count, $fetch_count);
				}
				$count = count($font_slice_array);
				foreach($font_slice_array as $key => $tempfont){
					$fontinfo = array();
					$already_selected = 'false';
					if(in_array($tempfont->family, $temp_selected)){
						$already_selected = 'true';
					}
					$font_call = str_replace(' ', '+', $tempfont->family);
					$variants = $tempfont->variants;
					$subsets = $tempfont->subsets;
					$fontinfo = array(
						'font_name' => $tempfont->family,
						'font_call'	=>	$font_call,
						'variants' => $variants,
						'subsets' => $subsets,
						'selected' => $already_selected
					);
					array_push($fonts, $fontinfo);
					//google_font_list_item_ui($fontinfo);
				}
			endif;
			$response['fonts'] = $fonts;
			$response['fonts_count'] = count($google_fonts);
			if($search != '')
				$response['is_search'] = 'true';
			else
				$response['is_search'] = 'false';
			echo json_encode($response);
			die();
		}
				
		function wp_dp_add_selected_google_font(){
		
			$font_family        = $_POST['font_family'];
			$font_name          = $_POST['font_name'];
			$variants           = $_POST['variants'];
			$subsets            = $_POST['subsets'];
			$added_google_fonts = get_option('wp_dp_added_google_fonts');
			if( empty($added_google_fonts)){
				$added_google_fonts = array();
			}
			$added_google_fonts[$font_name] = array(
				'font_family' => $font_family,
				'font_name'   => $font_name,
				'variants'    => $variants,
				'subsets'     => $subsets
			);
			update_option('wp_dp_added_google_fonts', $added_google_fonts);
			echo wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_google_fonts_is_added');
			die();
		}
		function wp_dp_delete_selected_google_font(){
			$font_name = $_POST['font_name'];
			$fonts = get_option('wp_dp_added_google_fonts');
			foreach($fonts as $key => $font)
			{
				if($font['font_name'] == $font_name)
				{
					unset($fonts[$key]);
				}
			}
			$fonts = array_values($fonts);
			update_option('wp_dp_added_google_fonts', $fonts);
			echo wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_google_fonts_is_deleted');
			die();
		}
		function wp_dp_update_selected_google_font() {
			$font_name = $_POST['font_name'];
			$variants  = $_POST['variants'];
			$subsets   = $_POST['subsets'];
			$fonts     = get_option('wp_dp_added_google_fonts');
			foreach($fonts as $key => $font)
			{
				if($font['font_name'] == $font_name)
				{
					$fonts[$key]['variants'] = $variants;
					$fonts[$key]['subsets'] = $subsets;
					$x = $key;
				}
			}
			update_option('wp_dp_added_google_fonts', $fonts);
			echo wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_google_fonts_is_updated');
			die();
		}
		
		function wp_dp_selected_google_font_att_field() {

			global $fonts, $wp_dp_cs_var_static_text;
			$wp_dp_cs_var_select_attribute = isset($wp_dp_cs_var_static_text['wp_dp_cs_var_select_attribute']) ? $wp_dp_cs_var_static_text['wp_dp_cs_var_select_attribute'] : '';
			if ( isset($_POST['index']) && $_POST['index'] <> '' ) {
				$index = $_POST['index'];
			} else {
				$index = '';
			}
			if ( $index != 'default' ) {
				$items = wp_dp_google_fonts_admin_frontend::wp_dp_selected_google_fonts_attributes( $index );
				$html = '<select class="chosen-select" id="' . $_POST['id'] . '" name="' . $_POST['id'] . '"><option value="">' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_select_font_attributes') . '</option>';
				foreach ( $items as $key => $value ) {
					$html .= '<option value="' . $value . '">' . $value . '</option>';
				}
				$html .='</select>';
			} else {
				$html = '<select class="chosen-select" id="' . $_POST['id'] . '" name="' . $_POST['id'] . '"><option value="">' . wp_dp_cs_var_frame_text_srt('wp_dp_cs_var_select_font_attributes') . '</option></select>';
			}

			echo '<script>
				jQuery(document).ready(function ($) {
					chosen_selectionbox();
				});
			</script>';

			echo balanceTags($html, false);
			die();
		}
		
		function wp_dp_add_custom_fonts_list() {
			$custom_font_name = isset( $_POST['custom_font_name'] ) ? $_POST['custom_font_name'] : '';
			$custom_font_woff = isset( $_POST['custom_font_woff'] ) ? $_POST['custom_font_woff'] : '';
			$custom_font_ttf  = isset( $_POST['custom_font_ttf'] ) ? $_POST['custom_font_ttf'] : '';
			$custom_font_svg  = isset( $_POST['custom_font_svg'] ) ? $_POST['custom_font_svg'] : '';
			$custom_font_eot  = isset( $_POST['custom_font_eot'] ) ? $_POST['custom_font_eot'] : '';
			
			$wp_dp_custom_fonts_list = get_option('wp_dp_custom_fonts_list');
			if( empty($wp_dp_custom_fonts_list)){
				$wp_dp_custom_fonts_list = array();
			}
			
			$wp_dp_custom_fonts_list['name'][] = $custom_font_name;
			$wp_dp_custom_fonts_list['woff'][] = $custom_font_woff;
			$wp_dp_custom_fonts_list['ttf'][] = $custom_font_ttf;
			$wp_dp_custom_fonts_list['svg'][] = $custom_font_svg;
			$wp_dp_custom_fonts_list['eot'][] = $custom_font_eot;
			
			update_option("wp_dp_custom_fonts_list", $wp_dp_custom_fonts_list);
			$last_key = end(array_keys($wp_dp_custom_fonts_list['name']));
			ob_start();
			if (( isset( $custom_font_woff ) && $custom_font_woff != '' ) && ( isset( $custom_font_ttf ) && $custom_font_ttf != '' ) && ( isset( $custom_font_svg ) && $custom_font_svg != '' ) && ( isset( $custom_font_eot ) && $custom_font_eot != '' ) ){
				$font_face_html = "<style type=\"text/css\">
				@font-face {
				font-family: '". $custom_font_name ."';
				src: url('" . $custom_font_eot . "');
				src:
				url('" . $custom_font_eot . "?#iefix') format('eot'),
				url('" . $custom_font_woff . "') format('woff'),
				url('" . $custom_font_ttf . "') format('truetype'),
				url('" . $custom_font_svg . "#wp_dp_cs_var_custom_font') format('svg');
				font-weight: 400 !important;
				font-style: normal;
				}</style>";
			}
			echo $font_face_html;
			?>
				<div class="custom-font">
					<div class="custom-font-header font-header" style="font-family:'<?php echo esc_html($custom_font_name); ?>'"><?php echo esc_html($custom_font_name); ?></div>
					<input class="add-custom-font alignright" data-font_key="<?php echo intval( $last_key ); ?>" data-font_name="<?php echo esc_html($custom_font_name); ?>" data-woff_font="<?php echo esc_html( $custom_font_woff );?>" data-ttf_font="<?php echo esc_html( $custom_font_ttf );?>" data-svg_font="<?php echo esc_html( $custom_font_svg );?>" data-eot_font="<?php echo esc_html( $custom_font_eot );?>" value="<?php echo wp_dp_cs_var_frame_text_srt('wp_dp_add_to_selected_custom_fonts'); ?>" type="button">
					<span class="custom-font-delete alignright" data-font_name="<?php echo esc_html( $custom_font_name ); ?>" data-font_key="<?php echo intval( $last_key ); ?>"><i class="dashicons dashicons-no-alt"></i></span>
					<span class="spinner" style="float: right; visibility: visible; display: none;"></span>
					<div class="clear"></div>
				</div>
			<?php
			echo $content = ob_get_clean();
			die();
		}
		
		function wp_dp_delete_custom_font_list(){
			
			$wp_dp_custom_fonts_list = get_option('wp_dp_custom_fonts_list');
			$font_key = isset( $_POST['font_key'] ) ? $_POST['font_key'] : '';
			
			if( empty($wp_dp_custom_fonts_list)){
				$wp_dp_custom_fonts_list = array();
			}
			
			unset($wp_dp_custom_fonts_list['name'][$font_key]);
			unset($wp_dp_custom_fonts_list['woff'][$font_key]);
			unset($wp_dp_custom_fonts_list['ttf'][$font_key]);
			unset($wp_dp_custom_fonts_list['svg'][$font_key]);
			unset($wp_dp_custom_fonts_list['eot'][$font_key]);
			
			$wp_dp_custom_fonts_list['name'] = array_values($wp_dp_custom_fonts_list['name']);
			$wp_dp_custom_fonts_list['woff'] = array_values($wp_dp_custom_fonts_list['woff']);
			$wp_dp_custom_fonts_list['ttf'] = array_values($wp_dp_custom_fonts_list['ttf']);
			$wp_dp_custom_fonts_list['svg'] = array_values($wp_dp_custom_fonts_list['svg']);
			$wp_dp_custom_fonts_list['eot'] = array_values($wp_dp_custom_fonts_list['eot']);
			
			update_option('wp_dp_custom_fonts_list', $wp_dp_custom_fonts_list);
			echo wp_dp_cs_var_frame_text_srt('wp_dp_custom_fonts_is_deleted');
			die();
		}
		
		function wp_dp_add_selected_custom_font(){
			
			$font_name = isset( $_POST['font_name'] ) ? $_POST['font_name'] : '';
			$woff_font = isset( $_POST['woff_font'] ) ? $_POST['woff_font'] : '';
			$ttf_font  = isset( $_POST['ttf_font'] ) ? $_POST['ttf_font'] : '';
			$svg_font  = isset( $_POST['svg_font'] ) ? $_POST['svg_font'] : '';
			$eot_font  = isset( $_POST['eot_font'] ) ? $_POST['eot_font'] : '';
			
			$wp_dp_selected_custom_fonts = get_option('wp_dp_selected_custom_fonts');
			if( empty($wp_dp_selected_custom_fonts)){
				$wp_dp_selected_custom_fonts = array();
			}
			
			$wp_dp_selected_custom_fonts['name'][] = $font_name;
			$wp_dp_selected_custom_fonts['woff'][] = $woff_font;
			$wp_dp_selected_custom_fonts['ttf'][] = $ttf_font;
			$wp_dp_selected_custom_fonts['svg'][] = $svg_font;
			$wp_dp_selected_custom_fonts['eot'][] = $eot_font;
			update_option('wp_dp_selected_custom_fonts', $wp_dp_selected_custom_fonts);
			$last_key = end(array_keys($wp_dp_selected_custom_fonts['name']));
			
			ob_start();
			
			if (( isset( $woff_font ) && $woff_font != '' ) && ( isset( $ttf_font ) && $ttf_font != '' ) && ( isset( $svg_font ) && $svg_font != '' ) && ( isset( $eot_font ) && $eot_font != '' ) ){
				$font_face_html = "<style type=\"text/css\">
				@font-face {
				font-family: '". $font_name ."';
				src: url('" . $eot_font . "');
				src:
				url('" . $eot_font . "?#iefix') format('eot'),
				url('" . $woff_font . "') format('woff'),
				url('" . $ttf_font . "') format('truetype'),
				url('" . $svg_font . "#wp_dp_cs_var_custom_font') format('svg');
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
				<span class="selected-custom-font-delete" data-font_name="<?php echo esc_html( $font_name ); ?>" data-font_key="<?php echo intval( $last_key ); ?>"><i class="dashicons dashicons-no-alt"></i></span>
			</div>
			<?php
			echo $content = ob_get_clean();
			die();
		}
		
		function wp_dp_delete_selected_custom_font(){
			
			$wp_dp_selected_custom_fonts = get_option('wp_dp_selected_custom_fonts');
			$font_key = isset( $_POST['font_key'] ) ? $_POST['font_key'] : '';
			if( empty($wp_dp_selected_custom_fonts)){
				$wp_dp_selected_custom_fonts = array();
			}
			
			unset($wp_dp_selected_custom_fonts['name'][$font_key]);
			unset($wp_dp_selected_custom_fonts['woff'][$font_key]);
			unset($wp_dp_selected_custom_fonts['ttf'][$font_key]);
			unset($wp_dp_selected_custom_fonts['svg'][$font_key]);
			unset($wp_dp_selected_custom_fonts['eot'][$font_key]);
			
			$wp_dp_selected_custom_fonts['name'] = array_values($wp_dp_selected_custom_fonts['name']);
			$wp_dp_selected_custom_fonts['woff'] = array_values($wp_dp_selected_custom_fonts['woff']);
			$wp_dp_selected_custom_fonts['ttf'] = array_values($wp_dp_selected_custom_fonts['ttf']);
			$wp_dp_selected_custom_fonts['svg'] = array_values($wp_dp_selected_custom_fonts['svg']);
			$wp_dp_selected_custom_fonts['eot'] = array_values($wp_dp_selected_custom_fonts['eot']);
			
			update_option('wp_dp_selected_custom_fonts', $wp_dp_selected_custom_fonts);
			echo wp_dp_cs_var_frame_text_srt('wp_dp_selected_custom_fonts_is_deleted');
			die();
		}
		
		public function wp_dp_selected_custom_fonts_list() {
			$wp_dp_selected_custom_fonts = get_option('wp_dp_selected_custom_fonts');
			$custom_fonts = array();
			if ( is_array($wp_dp_selected_custom_fonts) && !empty($wp_dp_selected_custom_fonts['name'])) {
				foreach ( $wp_dp_selected_custom_fonts['name'] as $key => $wp_dp_custom_font  ) {
					$font_name = isset($wp_dp_selected_custom_fonts['name'][$key]) ? $wp_dp_selected_custom_fonts['name'][$key] : '';
					if( $font_name != '' ){
						$custom_fonts[$key] = $font_name;
					}

				}
			}
			return $custom_fonts;
		}
		
		
		public function wp_dp_export_fonts_callback() {
			global $wp_filesystem;
			
			require_once ABSPATH . '/wp-admin/includes/file.php';
			
			$wp_dp_google_fonts = get_option('wp_dp_google_fonts');
			$added_google_fonts = get_option('wp_dp_added_google_fonts');
			$wp_dp_custom_fonts_list = get_option('wp_dp_custom_fonts_list');
			$wp_dp_selected_custom_fonts = get_option('wp_dp_selected_custom_fonts');
			$wp_dp_fonts = array();
			$wp_dp_fonts['wp_dp_google_fonts'] = $wp_dp_google_fonts;
			$wp_dp_fonts['wp_dp_added_google_fonts'] = $added_google_fonts;
			$wp_dp_fonts['wp_dp_custom_fonts_list'] = $wp_dp_custom_fonts_list;
			$wp_dp_fonts['wp_dp_selected_custom_fonts'] = $wp_dp_selected_custom_fonts;
			
			$wp_dp_fonts_fields = json_encode($wp_dp_fonts, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
			
			$wp_dp_upload_dir = FRAMEWORK_CORE_DIR . '/assets/backups/';
			$name = 'fonts.json';
			$wp_dp_filename = trailingslashit($wp_dp_upload_dir) . $name;
			$wp_dp_fileurl = FRAMEWORK_PLUGIN_URL. '/assets/backups/'. $name;
			if ( ! $wp_filesystem->put_contents($wp_dp_filename, $wp_dp_fonts_fields, FS_CHMOD_FILE) ) {
				echo json_encode( array( 'type' => 'error', 'name' => $name, 'url' => $wp_dp_fileurl ) );
			} else {
				echo json_encode( array( 'type' => 'success', 'name' => $name, 'url' => $wp_dp_fileurl ));
			}
			die();
		}
		
	}
	
	new wp_dp_google_fonts_admin;
}