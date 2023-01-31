<?php

/**
 * File Type: Listing Enquiries Post Type Metas
 */
if (!class_exists('listing_enquiries_post_type_meta')) {

    class listing_enquiries_post_type_meta {

        /**
         * Start Contructer Function
         */
        public function __construct() {
            add_action('add_meta_boxes', array(&$this, 'listing_enquiries_add_meta_boxes_callback'));
        }

        /**
         * Add meta boxes Callback Function
         */
        public function listing_enquiries_add_meta_boxes_callback() {
            add_meta_box('wp_dp_meta_listing_enquiries', esc_html(wp_dp_plugin_text_srt('wp_dp_listing_enquiries_options')), array($this, 'wp_dp_meta_listing_enquiries'), 'listing_enquiries', 'normal', 'high');
        }

        public function wp_dp_meta_listing_enquiries() {
            global $post, $wp_dp_plugin_options;
            $post_id = $post->ID;
            $wp_dp_users_list = array();

            $wp_dp_seller_members_list = array();
            $enquiry_meta = array();

            $enquiry_meta['order_id'] = array(
                'name' => 'order_id',
                'type' => 'hidden_label',
                'title' => wp_dp_plugin_text_srt('wp_dp_enquiry_id'),
                'description' => '',
                'std' => $post_id,
            );

            $enquiry_meta['listing_member'] = array(
                'name' => 'listing_member',
                'type' => 'members_select',
                'classes' => 'chosen-select',
                'title' => wp_dp_plugin_text_srt('wp_dp_enquiry_listing_member'),
                'options' => $wp_dp_seller_members_list,
                'description' => '',
            );

            $enquiry_meta['enquiry_member'] = array(
                'name' => 'enquiry_member',
                'type' => 'members_select',
                'classes' => 'chosen-select',
                'title' => wp_dp_plugin_text_srt('wp_dp_enquiry_enquiry_member'),
                'options' => $wp_dp_seller_members_list,
                'description' => '',
            );
            $orders_status = isset($wp_dp_plugin_options['orders_status']) ? $wp_dp_plugin_options['orders_status'] : '';
            if (is_array($orders_status) && sizeof($orders_status) > 0) {
                foreach ($orders_status as $key => $label) {
                    $drop_down_options[$label] = $label;
                }
            } else {
                $drop_down_options = array(
                    'Processing' => wp_dp_plugin_text_srt('wp_dp_enquiry_detail_procceing'),
                    'Completed' => wp_dp_plugin_text_srt('wp_dp_enquiry_detail_completed'),
                );
            }

            $html = '<div class="page-wrap">
                        <div class="option-sec" style="margin-bottom:0;">
                                <div class="opt-conts">
                                        <div class="wp-dp-review-wrap">';
            foreach ($enquiry_meta as $key => $params) {
                $html .= $this->wp_dp_create_listing_enquiries_fields($key, $params);
            }
            $html .=                    '</div>
                                    </div>
                            </div>
                            <div class="clear"></div>
                    </div>';
            echo force_balance_tags($html);
        }

        public function wp_dp_create_listing_enquiries_fields($key, $param) {
            global $post, $wp_dp_html_fields, $wp_dp_form_fields, $wp_dp_plugin_options;
            $wp_dp_currency_sign = wp_dp_get_currency_sign();

            $wp_dp_value = $param['title'];
            $html = '';
            switch ($param['type']) {
                case 'text' :
                    // prepare
                    $wp_dp_value = get_post_meta($post->ID, $key, true);

                    if (isset($wp_dp_value) && $wp_dp_value != '') {
                        if ($key == 'wp_dp_order_date') {
                            $wp_dp_value = date_i18n('d-m-Y', $wp_dp_value);
                        } else {
                            $wp_dp_value = $wp_dp_value;
                        }
                    } else {
                        $wp_dp_value = isset($param['std']) ? $param['std'] : '';
                    }

                    $wp_dp_opt_array = array(
                        'name' => $param['title'],
                        'desc' => '',
                        'hint_text' => '',
                        'field_params' => array(
                            'std' => $wp_dp_value,
                            'cust_id' => $key,
                            'cust_name' => $key,
                            'classes' => 'wp-dp-form-text wp-dp-input',
                            'force_std' => true,
                            'return' => true,
                            'active' => $param['active'],
                        ),
                    );
                    $output = '';
                    $output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                    $output .= '<span class="wp-dp-form-desc">' . $param['description'] . '</span>' . "\n";


                    $html .= $output;
                    break;
                case 'checkbox' :
                    // prepare
                    $wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $key, true);

                    $wp_dp_opt_array = array(
                        'name' => $param['title'],
                        'desc' => '',
                        'hint_text' => '',
                        'field_params' => array(
                            'std' => $wp_dp_value,
                            'id' => $key,
                            'classes' => 'wp-dp-form-text wp-dp-input',
                            'force_std' => true,
                            'return' => true,
                        ),
                    );
                    $output = '';
                    $output .= $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

                    $html .= $output;
                    break;
                case 'textarea' :
                    // prepare
                    $wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $key, true);
                    if (isset($wp_dp_value) && $wp_dp_value != '') {
                        $wp_dp_value = $wp_dp_value;
                    } else {
                        $wp_dp_value = '';
                    }

                    $wp_dp_opt_array = array(
                        'name' => $param['title'],
                        'desc' => '',
                        'hint_text' => '',
                        'field_params' => array(
                            'std' => '',
                            'id' => $key,
                            'return' => true,
                        ),
                    );

                    $output = $wp_dp_html_fields->wp_dp_textarea_field($wp_dp_opt_array);
                    $html .= $output;
                    break;
                    
                case 'members_select' :
                    $wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $key, true);
                    $wp_dp_members_list    = array();
                    if (isset($wp_dp_value) && $wp_dp_value != '') {
                        $wp_dp_value = $wp_dp_value;
                        $wp_dp_members_list = array( $wp_dp_value => get_the_title($wp_dp_value) );
                    } else {
                        $wp_dp_value = '';
                    }
                    $wp_dp_classes = '';
                    if (isset($param['classes']) && $param['classes'] != "") {
                        $wp_dp_classes = $param['classes'];
                    }
                    $output = $wp_dp_html_fields->wp_dp_opening_field(array(
					'id' => 'listing_member',
					'name' => $param['title'],
					'label_desc' => '',
				)
			);
                    $output .= '<div class="listing_members_holder ' . $key . '_holder" onclick="wp_dp_load_all_members(\'' . $key . '_holder\', \''. $wp_dp_value .'\');">';
                            $wp_dp_opt_array = array(
                                    'std' => $wp_dp_value,
                                    'force_std' => true,
                                    'id' => $key,
                                    'extra_atr' => 'onchange="wp_dp_show_company_users(this.value, \''.admin_url('admin-ajax.php').'\', \''.wp_dp::plugin_url().'\');"',
                                    'classes' => $wp_dp_classes,
                                    'options' => $wp_dp_members_list,
                                    'markup' => '<span class="members-loader"></span>',
                                    'return' => true,
                            );
                            $output .= $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);
                    $output .= '</div>';
                    
                    $output .= $wp_dp_html_fields->wp_dp_closing_field(array('desc' => ''));
                    // append
                    $html .= $output;
                    break;
                case 'select' :
                    $wp_dp_value = get_post_meta($post->ID, 'wp_dp_' . $key, true);
                    if (isset($wp_dp_value) && $wp_dp_value != '') {
                        $wp_dp_value = $wp_dp_value;
                    } else {
                        $wp_dp_value = '';
                    }
                    $wp_dp_classes = '';
                    if (isset($param['classes']) && $param['classes'] != "") {
                        $wp_dp_classes = $param['classes'];
                    }
                    $wp_dp_opt_array = array(
                        'name' => $param['title'],
                        'desc' => '',
                        'hint_text' => '',
                        'field_params' => array(
                            'std' => '',
                            'id' => $key,
                            'classes' => $wp_dp_classes,
                            'options' => $param['options'],
                            'return' => true,
                        ),
                    );

                    $output = $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                    // append
                    $html .= $output;
                    break;
                case 'hidden_label' :
                    // prepare
                    $wp_dp_opt_array = array(
                        'name' => $param['title'],
                        'hint_text' => '',
                    ); 
                    $output = $wp_dp_html_fields->wp_dp_opening_field($wp_dp_opt_array);

                    $output .= '<span>#' . $param['std'] . '</span>';

                    $output .= $wp_dp_form_fields->wp_dp_form_hidden_render(
                            array(
                                'name' => '',
                                'id' => $key,
                                'return' => true,
                                'classes' => '',
                                'std' => $param['std'],
                                'description' => '',
                                'hint' => ''
                            )
                    );

                    $wp_dp_opt_array = array(
                        'desc' => '',
                    );
                    $output .= $wp_dp_html_fields->wp_dp_closing_field($wp_dp_opt_array);
                    $html .= $output;
                    break;
                default :
                    break;
            }
            return $html;
        }

    }

    // Initialize Object
    $listing_enquiries_meta_object = new listing_enquiries_post_type_meta();
}