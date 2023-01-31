<?php
/**
 * File Type: Packages Post Type Metas
 */
if ( ! class_exists('packages_post_type_meta') ) {

    class packages_post_type_meta {

        /**
         * Start Contructer Function
         */
        public function __construct() {
            add_action('add_meta_boxes', array( &$this, 'packages_add_meta_boxes_callback' ));
            add_action('save_post', array( $this, 'wp_dp_insert_package_metas' ), 18);
            add_action('wp_ajax_add_package_field', array( $this, 'add_package_field_callback' ));
        }

        /**
         * Add meta boxes Callback Function
         */
        public function packages_add_meta_boxes_callback() {
            add_meta_box('wp_dp_meta_packages', esc_html(wp_dp_plugin_text_srt('wp_dp_listing_packages_options')), array( $this, 'wp_dp_meta_packages' ), 'packages', 'normal', 'high');
        }

        /**
         * Creating an array for meta fields
         */
        public function wp_dp_meta_packages() {
            global $post, $wp_dp_html_fields;
            $wp_dp_packages_fields = array();

            $package_data = get_post_meta($post->ID, 'wp_dp_package_data', true);
            $package_icon = get_post_meta($post->ID, 'wp_dp_package_icon', true);
            $package_icon = ( isset($package_icon[0]) ) ? $package_icon[0] : '';

            echo '
			<div class = "form-elements">

			<div class = "col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<label>' . wp_dp_plugin_text_srt('wp_dp_package_tile') . '</label>
			</div>

			<div class = "col-lg-8 col-md-8 col-sm-12 col-xs-12">
			<div id="wp_dp_title_move"></div>
			</div>

			</div>';
            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_package_type'),
                'desc' => '',
                'hint_text' => wp_dp_plugin_text_srt('wp_dp_package_type_hint'),
                'echo' => true,
                'field_params' => array(
                    'std' => '',
                    'id' => 'package_type',
                    'classes' => 'function-class',
                    'return' => true,
                    'options' => array(
                        'free' => wp_dp_plugin_text_srt('wp_dp_package_type_free'),
                        'paid' => wp_dp_plugin_text_srt('wp_dp_package_type_paid')
                    ),
                ),
            );
            $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
            // show/hide package price field.
            $package_value = isset($package_value) ? $package_value : '';
            $package_value = get_post_meta($post->ID, 'wp_dp_package_type', true);
            $display = 'none';
            if ( $package_value == 'paid' ) {
                $display = 'block';
            } else {
                $display = 'none';
            }
            ?>

            <div class="package-price-area" id="package-price-area" style="display:<?php echo esc_html($display); ?>;">
                <?php
                $wp_dp_opt_array = array(
                    'name' => wp_dp_plugin_text_srt('wp_dp_package_price'),
                    'desc' => '',
                    'hint_text' => wp_dp_plugin_text_srt('wp_dp_package_price_hint'),
                    'echo' => true,
                    'field_params' => array(
                        'std' => '',
                        'id' => 'package_price',
                        'classes' => 'wp-dp-dev-req-field-admin',
                        'extra_atr' => 'data-visible="package-price-area"',
                        'return' => true,
                    ),
                );

                $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                ?>
            </div>

            <?php		
            /*
             * Package Features Array
             */
			
                 $wp_dp_packages_fields[] = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_packages_phone_num_web'),
                'id' => 'radius_fields',
                'desc' => '',
                'hint_text' => '',
                'type' => 'multi_fields',
                'echo' => true,
                'fields_list' => array(
                    array(
                        'type' => 'checkbox', 'field_params' => array(
                            'std' => ( isset($package_data['phone_number_website']['value']) ) ? $package_data['phone_number_website']['value'] : 'on',
                            'force_std' => true,
                            'cust_name' => 'package_field[phone_number_website][value]',
                            'id' => 'package_field[phone_number_website][value]',
                            'return' => true,
                            'classes' => '',
                        ),
                    ),
                ),
            );

            $wp_dp_packages_fields[] = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_packages_num_listing_allowed'),
                'id' => 'radius_fields',
                'desc' => '',
                'type' => 'multi_fields',
                'echo' => true,
                'fields_list' => array(
                    array(
                        'type' => 'text', 'field_params' => array(
                            'std' => ( isset($package_data['number_of_listing_allowed']['value']) ) ? $package_data['number_of_listing_allowed']['value'] : '1',
                            'cust_name' => 'package_field[number_of_listing_allowed][value]',
                            'id' => 'package_field[number_of_listing_allowed][value]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_listing_packages_num_listing_allowed') . '"',
                            'return' => true,
                            'classes' => '',
                        ),
                    ),
                ),
            );
            $wp_dp_packages_fields[] = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_packages_listing_duration'),
                'id' => 'radius_fields',
                'desc' => '',
                'type' => 'multi_fields',
                'echo' => true,
                'fields_list' => array(
                    array(
                        'type' => 'text', 'field_params' => array(
                            'std' => ( isset($package_data['listing_duration']['value']) ) ? $package_data['listing_duration']['value'] : '15',
                            'cust_name' => 'package_field[listing_duration][value]',
                            'id' => 'package_field[listing_duration][value]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_listing_packages_days') . '"',
                            'return' => true,
                            'classes' => '',
                        ),
                    ),
                ),
            );

            $wp_dp_packages_fields[] = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_video'),
                'id' => 'top_cat_listings',
                'desc' => '',
                'type' => 'multi_fields',
                'echo' => true,
                'fields_list' => array(
                    array(
                        'type' => 'checkbox', 'field_params' => array(
                            'std' => ( isset($package_data['listing_video']['value']) ) ? $package_data['listing_video']['value'] : 'on',
                            'cust_name' => 'package_field[listing_video][value]',
                            'id' => 'package_field[listing_video][value]',
                            'extra_atr' => '',
                            'return' => true,
                            'classes' => '',
                        ),
                    ),
                ),
            );
            $wp_dp_packages_fields[] = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_packages_num_pictures'),
                'id' => 'radius_fields',
                'desc' => '',
                'type' => 'multi_fields',
                'echo' => true,
                'fields_list' => array(
                    array(
                        'type' => 'text', 'field_params' => array(
                            'std' => ( isset($package_data['number_of_pictures']['value']) ) ? $package_data['number_of_pictures']['value'] : '6',
                            'cust_name' => 'package_field[number_of_pictures][value]',
                            'id' => 'package_field[number_of_pictures][value]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_listing_packages_num_pictures') . '"',
                            'return' => true,
                            'classes' => '',
                        ),
                    ),
                ),
            );

            $wp_dp_packages_fields[] = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_packages_num_documents'),
                'id' => 'radius_fields',
                'desc' => '',
                'hint_text' => '',
                'type' => 'multi_fields',
                'echo' => true,
                'fields_list' => array(
                    array(
                        'type' => 'text', 'field_params' => array(
                            'std' => ( isset($package_data['number_of_documents']['value']) ) ? $package_data['number_of_documents']['value'] : '6',
                            'cust_name' => 'package_field[number_of_documents][value]',
                            'id' => 'package_field[number_of_documents][value]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_listing_packages_num_documents') . '"',
                            'return' => true,
                            'classes' => '',
                        ),
                    ),
                ),
            );
            $social_link = '<a href="'.admin_url( 'page=wp_dp_settings' ).'" target="_blank">' . wp_dp_plugin_text_srt('wp_dp_listing_packages_social_impressions_hint_social_network') . '</a>';
            $wp_dp_packages_fields[] = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_packages_social_impressions'),
                'id' => 'radius_fields',
                'desc' => '',
                'label_desc' => sprintf(wp_dp_plugin_text_srt('wp_dp_listing_packages_social_impressions_hint_text'), $social_link),
                'type' => 'multi_fields',
                'echo' => true,
                'fields_list' => array(
                    array(
                        'type' => 'checkbox', 'field_params' => array(
                            'std' => ( isset($package_data['social_impressions_reach']['value']) ) ? $package_data['social_impressions_reach']['value'] : 'on',
                            'force_std' => true,
                            'cust_name' => 'package_field[social_impressions_reach][value]',
                            'id' => 'package_field[social_impressions_reach][value]',
                            'return' => true,
                            'classes' => '',
                        ),
                    ),
                ),
            );

            $wp_dp_packages_fields[] = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_listing_packages_num_tags'),
                'id' => 'radius_fields',
                'desc' => '',
                'type' => 'multi_fields',
                'echo' => true,
                'fields_list' => array(
                    array(
                        'type' => 'text', 'field_params' => array(
                            'std' => ( isset($package_data['number_of_tags']['value']) ) ? $package_data['number_of_tags']['value'] : '6',
                            'cust_name' => 'package_field[number_of_tags][value]',
                            'id' => 'package_field[number_of_tags][value]',
                            'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_listing_packages_num_tags') . '"',
                            'return' => true,
                            'classes' => '',
                        ),
                    ),
                ),
            );

            $wp_dp_packages_fields = apply_filters('package_meta_fields', $wp_dp_packages_fields);
            ?>
            <div class="package_options">
                <?php
                foreach ( $wp_dp_packages_fields as $field_array ) {
                    $this->wp_dp_meta_packages_fields($field_array);
                }
                $this->wp_dp_add_package_field();
                ?>

            </div>
            <div class="clear"></div>
            <script type="text/javascript">
                jQuery('.function-class').change(function ($) {
                    var value = jQuery(this).val();
                    var parentNode = jQuery(this).parent().parent().parent();
                    if (value == 'free') {
                        parentNode.find(".package-price-area").hide();

                    } else {
                        parentNode.find(".package-price-area").show();
                    }
                });
            </script>
            <?php
        }

        /**
         * Creating Meta fields from array
         */
        function wp_dp_meta_packages_fields($field_array) {
			global $wp_dp_html_fields;
			$field_array['type'] = ( isset($field_array['type']) ) ? $field_array['type'] : '';

            switch ( $field_array['type'] ) {

                case "checkbox":
                    $wp_dp_html_fields->wp_dp_checkbox_field($field_array);
                    break;

                case "text":
                    $wp_dp_html_fields->wp_dp_text_field($field_array);
                    break;

                case "select":
                    $wp_dp_html_fields->wp_dp_select_field($field_array);
                    break;

                case "heading":
                    $wp_dp_html_fields->wp_dp_heading_render($field_array);
                    break;

                case "multi_fields":
                    $wp_dp_html_fields->wp_dp_multi_fields($field_array);
                    break;
            }
        }

        public function wp_dp_insert_package_metas($post_id) {

            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
                return;
            }
            if ( isset($_POST['package_field']) ) {

                update_post_meta($post_id, 'wp_dp_package_data', $_POST['package_field']);
            }
            $fields_array = array();
            if ( isset($_POST['wp_dp_package_fields']) && is_array($_POST['wp_dp_package_fields']) ) {
                $field_counter = 0;
                foreach ( $_POST['wp_dp_package_fields'] as $field ) {
                    $field_label = isset($_POST['wp_dp_package_field_label'][$field_counter]) ? $_POST['wp_dp_package_field_label'][$field_counter] : '';
                    $field_value = isset($_POST['wp_dp_package_field_value'][$field_counter]) ? $_POST['wp_dp_package_field_value'][$field_counter] : '';
                    $field_type = isset($_POST['wp_dp_package_field_type'][$field_counter]) ? $_POST['wp_dp_package_field_type'][$field_counter] : '';
                    $fields_array[$field] = array( 'key' => 'field_' . $field, 'field_label' => $field_label, 'field_value' => $field_value, 'field_type' => $field_type );
                    $field_counter ++;
                }
                update_post_meta($post_id, 'wp_dp_package_fields', $fields_array);
            }else{
                update_post_meta($post_id, 'wp_dp_package_fields', $fields_array);
            }
        }

        public function wp_dp_add_package_field() {

            global $post, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_static_text;
            $wp_dp_package_fields = get_post_meta($post->ID, 'wp_dp_package_fields', true);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_package_additional_fields'),
                'id' => 'package_additional_fields',
                'classes' => '',
                'std' => '',
                'description' => '',
                'hint' => '',
            );
            $html = $wp_dp_html_fields->wp_dp_heading_render($wp_dp_opt_array);



            $html .= '<div class="wp-dp-list-wrap wp-dp-packages-list-wrap">
                    <ul class="wp-dp-list-layout">
                        <li class="wp-dp-list-label">
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="element-label">
                                    <label>' . wp_dp_plugin_text_srt('wp_dp_package_label') . '</label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="element-label">
                                    <label>' . wp_dp_plugin_text_srt('wp_dp_package_type') . '</label>
                                </div>
                            </div>
                        </li>';


            if ( is_array($wp_dp_package_fields) && sizeof($wp_dp_package_fields) > 0 ) {
                foreach ( $wp_dp_package_fields as $field_key => $fields ) {
                    if ( isset($fields) && $fields != '' ) {
                        $counter_feature = $field_id = $field_key;
                        $wp_dp_field_label = isset($fields['field_label']) ? $fields['field_label'] : '';
                        $wp_dp_field_value = isset($fields['field_value']) ? $fields['field_value'] : '';
                        $wp_dp_field_type = isset($fields['field_type']) ? $fields['field_type'] : '';

                        $wp_dp_fields_array = array(
                            'counter_field' => $counter_feature,
                            'field_id' => $field_id,
                            'wp_dp_field_label' => $wp_dp_field_label,
                            'wp_dp_field_value' => $wp_dp_field_value,
                            'wp_dp_field_type' => $wp_dp_field_type,
                        );
                        $html .= $this->add_package_field_callback($wp_dp_fields_array);
                    }
                }
            }

            $html .= '</ul>
                    <ul class="wp-dp-list-button-ul">
                        <li class="wp-dp-list-button">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="input-element">
                                    <a href="javascript:wp_dp_createpop(\'add_field_title\',\'filter\')" id="click-more" class="wp-dp-add-more cntrl-add-new-row">' . wp_dp_plugin_text_srt('wp_dp_options_add_more') . '</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>';



            $html .= '
            <div id="add_field_title" style="display: none;">
                <div class="cs-heading-area">
                  <h5><i class="icon-plus-circle"></i> ' . wp_dp_plugin_text_srt('wp_dp_package_field') . '</h5>
                  <span class="cs-btnclose" onClick="javascript:wp_dp_removeoverlay(\'add_field_title\',\'append\')"> <i class="icon-times"></i></span> 	
                </div>';

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_add_field_label'),
                'desc' => '',
                'hint_text' => '',
                'echo' => false,
                'field_params' => array(
                    'std' => '',
                    'id' => 'field_label',
                    'extra_atr' => 'title="' . wp_dp_plugin_text_srt('wp_dp_add_field_label') . '"',
                    'return' => true,
                ),
            );

            $html .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'name' => wp_dp_plugin_text_srt('wp_dp_add_field_type'),
                'desc' => '',
                'hint_text' => wp_dp_plugin_text_srt('wp_dp_add_field_type_hint'),
                'echo' => false,
                'field_params' => array(
                    'std' => '',
                    'id' => 'field_type',
                    'classes' => 'chosen-select select-medium',
                    'options' => array(
                        'single-line' => wp_dp_plugin_text_srt('wp_dp_add_field_single_line'),
                        'single-choice' => wp_dp_plugin_text_srt('wp_dp_add_field_single_choice'),
                    ),
                    'return' => true,
                ),
            );

            $html .= $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
            
            
            
             $html .= '<ul class="wp-dp-list-button-ul">
                        <li class="wp-dp-list-button">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="input-element">
                                    <a href="javascript:;" onclick="add_package_field(\'' . esc_js(admin_url('admin-ajax.php')) . '\')" id="click-more" class="wp-dp-package-button wp-dp-add-more cntrl-add-new-row">' . wp_dp_plugin_text_srt('wp_dp_add_field') . '</a>
                                </div>
                            </div>
                        </li>
                    </ul>';
            $html .= '</div>';

            echo force_balance_tags($html, true);
        }

        public function add_package_field_callback($wp_dp_atts = array()) {

            global $post, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_static_text;
            $wp_dp_defaults = array(
                'counter_field' => '',
                'field_id' => '',
                'wp_dp_field_label' => '',
                'wp_dp_field_type' => '',
                'wp_dp_field_value' => '',
            );
            extract(shortcode_atts($wp_dp_defaults, $wp_dp_atts));

            foreach ( $_POST as $keys => $values ) {
                $$keys = $values;
            }

            if ( isset($_POST['wp_dp_field_label']) && $_POST['wp_dp_field_label'] != '' ) {
                $wp_dp_field_label = $_POST['wp_dp_field_label'];
            }

            if ( isset($_POST['wp_dp_field_type']) && $_POST['wp_dp_field_type'] != '' ) {
                $wp_dp_field_type = $_POST['wp_dp_field_type'];
            }

            if ( $field_id == '' && $counter_field == '' ) {
                $counter_field = $field_id = rand(1000000000, 9999999999);
            }

            $html = '';



            $html .= '<li class="wp-dp-list-item">';

            $wp_dp_opt_array = array(
                'std' => absint($field_id),
                'id' => '',
                'cust_name' => 'wp_dp_package_fields[]',
                'return' => true,
                'force_std' => true,
            );
            $html .= $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'std' => esc_html($wp_dp_field_label),
                'id' => '',
                'cust_name' => 'wp_dp_package_field_label[]',
                'return' => true,
                'force_std' => true,
            );
            $html .= $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_array);

            $wp_dp_opt_array = array(
                'std' => esc_html($wp_dp_field_type),
                'id' => '',
                'cust_name' => 'wp_dp_package_field_type[]',
                'return' => true,
                'force_std' => true,
            );
            $html .= $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_array);
            $html .= '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="input-element">
                                            <div class="input-holder">
                                                ' . esc_html($wp_dp_field_label) . '
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-element">
                                            <div class="input-holder">';
            $wp_dp_opt_array = array(
                'std' => esc_html($wp_dp_field_value),
                'id' => 'package_field_value' . absint($counter_field),
                'cust_name' => 'wp_dp_package_field_value[]',
                'return' => true,
                'force_std' => true,
                'classes' => 'input-field package-field',
            );
            if ( 'single-line' == $wp_dp_field_type ) {
                $html .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
            } else {
                $html .= $wp_dp_form_fields->wp_dp_form_checkbox_render($wp_dp_opt_array);
            }

            $html .= '</div>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" class="wp-dp-dpove wp-dp-parent-li-dpove"><i class="icon-close2"></i></a>
                                </li>';
            if ( isset($_POST['wp_dp_field_label']) ) {
                echo force_balance_tags($html);
            } else {
                return $html;
            }

            if ( isset($_POST['wp_dp_field_label']) ) {
                die();
            }
        }

    }

    // Initialize Object
    $packages_meta_object = new packages_post_type_meta();
}