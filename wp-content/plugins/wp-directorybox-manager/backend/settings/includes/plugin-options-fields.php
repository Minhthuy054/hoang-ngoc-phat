<?php
/**
 * File Type: wp_dp option fields file
 */
if ( ! class_exists('wp_dp_options_fields') ) {

    class wp_dp_options_fields {

        public function __construct() {
            add_action('wp_ajax_add_review_type_opt', array( $this, 'add_review_type_opt_callback' ));
            add_action('wp_ajax_wp_dp_nearby_add_row_icon', array( $this, 'wp_dp_nearby_add_row_icon' ));
        }

        public function wp_dp_nearby_add_row_icon($cats = '') {
            $icon_rand_id = isset($_POST['icon_num']) ? $_POST['icon_num'] : rand(1000000, 90000000);
            $html = apply_filters('cs_icons_fields', '', 'wp_dp_map_markers_data_icon_' . $icon_rand_id, 'wp_dp_map_markers_data[icon]', 'default', 'wp_dp_map_markers_data[icon_group][]');
            $html .= '
			<script>
			chosen_selectionbox();
			</script>';
            echo json_encode(array( 'html' => $html ));
            die;
        }

        public function yelp_categories($cats = '') {
            return apply_filters('wp_dp_yelp_categories', $cats);
        }

        /**
         * Start Function  how to create Fields Settings
         */
        public function wp_dp_fields($wp_dp_setting_options) {
            global $wp_dp_plugin_options, $wp_dp_form_fields, $wp_dp_html_fields, $help_text, $col_heading;


            $counter = 0;
            $wp_dp_counter = 0;
            $menu = '';
            $output = '';
            $parent_heading = '';
            $style = '';
            $wp_dp_countries_list = '';
            foreach ( $wp_dp_setting_options as $value ) {
                $counter ++;
                $val = '';

                $select_value = '';
                if ( isset($value['help_text']) && $value['help_text'] <> '' ) {
                    $help_text = $value['help_text'];
                } else {
                    $help_text = '';
                }
                if ( isset($value['col_heading']) && $value['col_heading'] <> '' ) {
                    $col_heading = $value['col_heading'];
                } else {
                    $col_heading = '';
                }
                $wp_dp_classes = '';
                if ( isset($value['classes']) && $value['classes'] != "" ) {
                    $wp_dp_classes = $value['classes'];
                }
                switch ( $value['type'] ) {
                    case "heading":
                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'fontawesome' => $value['fontawesome'],
                            'options' => $value['options'],
                        );

                        $menu .= $wp_dp_html_fields->wp_dp_set_heading($wp_dp_opt_array);
                        break;

                    case "main-heading":
                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'fontawesome' => $value['fontawesome'],
                            'id' => $value['id'],
                        );
                        $menu .= $wp_dp_html_fields->wp_dp_set_main_heading($wp_dp_opt_array);
                        break;

                    case "sub-heading":
                        $wp_dp_counter ++;
                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'counter' => $wp_dp_counter,
                            'id' => $value['id'],
                            'extra' => isset($value['extra']) ? $value['extra'] : '',
                        );
                        $output .= $wp_dp_html_fields->wp_dp_set_sub_heading($wp_dp_opt_array);
                        break;
                    case "col-right-text":
                        $wp_dp_opt_array = array(
                            'col_heading' => $col_heading,
                            'help_text' => $help_text,
                            'extra' => isset($value['extra']) ? $value['extra'] : '',
                        );
                        $output .= $wp_dp_html_fields->wp_dp_set_col_right($wp_dp_opt_array);
                        break;
                    case "announcement":
                        $wp_dp_counter ++;
                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'std' => $value['std'],
                            'id' => $value['id'],
                        );
                        $output .= $wp_dp_html_fields->wp_dp_set_announcement($wp_dp_opt_array);
                        break;
                    case "division":
                        $extra_atts = isset($value['extra_atts']) ? $value['extra_atts'] : '';
                        $auto_enable = isset($value['auto_enable']) ? $value['auto_enable'] : true;
                        $multi_val = isset($value['multi_val']) ? $value['multi_val'] : true;
                        $d_enable = '';
                        if ( isset($value['enable_val']) ) {
                            $enable_id = isset($value['enable_id']) ? $value['enable_id'] : '';
                            $enable_val = isset($value['enable_val']) ? $value['enable_val'] : '';
                            if ( $multi_val == true ) {
                                $enable_val = explode(',', $enable_val);
                            }
                            $d_val = '';
                            if ( isset($wp_dp_plugin_options) ) {
                                if ( isset($wp_dp_plugin_options[$enable_id]) ) {
                                    $d_val = $wp_dp_plugin_options[$enable_id];
                                }
                            }
                            if ( $auto_enable != false ) {
                                $d_enable = ' style="display:none;"';
                                if ( $multi_val == true ) {
                                    if ( in_array($d_val, $enable_val) ) {
                                        $d_enable = ' style="display:block;"';
                                    } else {
                                        $d_enable = ' style="display:none;"';
                                    }
                                } else {
                                    $d_enable = $d_val == $enable_val ? ' style="display:block;"' : ' style="display:none;"';
                                }
                            }
                        }
                        $output .= '<div' . $d_enable . ' ' . $extra_atts . '>';
                        break;

                    case "custom_div":
                        $attss = '';
                        if ( isset($value['class']) && $value['class'] != '' ) {
                            $attss .= ' class="' . $value['class'] . '"';
                        }
                        if ( isset($value['id']) && $value['id'] != '' ) {
                            $attss .= ' id="' . $value['id'] . '"';
                        }
                        $output .= '<div' . $attss . '>';
                        break;
                    case "division_close":
                        $output .= '</div>';
                        break;
                    case "section":

                        $wp_dp_opt_array = array(
                            'id' => $value['id'],
                            'std' => $value['std'],
                        );

                        if ( isset($value['accordion']) && $value['accordion'] <> '' ) {
                            $wp_dp_opt_array['accordion'] = $value['accordion'];
                        }

                        if ( isset($value['active']) && $value['active'] <> '' ) {
                            $wp_dp_opt_array['active'] = $value['active'];
                        }

                        if ( isset($value['parrent_id']) && $value['parrent_id'] <> '' ) {
                            $wp_dp_opt_array['parrent_id'] = $value['parrent_id'];
                        }

                        $output .= $wp_dp_html_fields->wp_dp_set_section($wp_dp_opt_array);
                        break;

                    case "paragraph":
                        $name = isset($value['name']) ? $value['name'] : '';
                        $std = isset($value['std']) ? $value['std'] : '';
                        $label_desc = isset($value['label_desc']) ? $value['label_desc'] : '';
                        $output .= '<div class="form-elements">';
                        $output .= '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">';
                        $output .= '<label>' . esc_html($name) . '</label>';
                        if ( $label_desc != '' ) {
                            $output .= '<p class="label-desc">' . $label_desc . '</p>';
                        }

                        $output .= '</div>';
                        $output .= '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">';
                        $output .= '<p>' . esc_html($std) . '</p>';
                        $output .= '</div>';
                        $output .= '</div>';

                        break;
                    case 'password' :
                        if ( isset($wp_dp_plugin_options) ) {
                            if ( isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) ) {
                                $val = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                            } else {
                                $val = $value['std'];
                            }
                        } else {
                            $val = $value['std'];
                        }
                        $cust_type = 'password';
                        $extra_atr = '';
                        $value['cust_type'] = isset($value['cust_type']) ? $value['cust_type'] : '';
                        if ( $value['cust_type'] != '' ) {
                            $cust_type = $value['cust_type'];
                            $extra_atr = 'onClick="send_test_mail(\'' . esc_js(admin_url('admin-ajax.php')) . '\', \'' . esc_js(wp_dp::plugin_url()) . '\')" value = "' . $value["std"] . '"';
                        }

                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'id' => $value['id'],
                            'desc' => $value['desc'],
                            'hint_text' => $value['hint_text'],
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                            'field_params' => array(
                                'std' => $val,
                                'cust_type' => $cust_type,
                                'extra_att' => $extra_atr,
                                'id' => $value['id'],
                                'return' => true,
                            ),
                        );

                        if ( isset($value['classes']) && $value['classes'] <> '' ) {
                            $wp_dp_opt_array['field_params']['classes'] = $value['classes'];
                        }

                        if ( isset($value['split']) && $value['split'] <> '' ) {
                            $wp_dp_opt_array['split'] = $value['split'];
                        }

                        $output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

                        break;
                    case 'text' :
                        if ( isset($wp_dp_plugin_options) ) {
                            if ( isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) ) {
                                $val = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                            } else {
                                $val = $value['std'];
                            }
                        } else {
                            $val = $value['std'];
                        }
                        $active = '';
                        if ( isset($value['active']) && $value['active'] !== '' ) {
                            $active = $value['active'];
                        }
                        $cust_type = '';
                        $extra_atr = '';
                        $value['cust_type'] = isset($value['cust_type']) ? $value['cust_type'] : '';
                        if ( $value['cust_type'] != '' ) {
                            $cust_type = $value['cust_type'];
                            $extra_atr = 'onclick="javascript:send_smtp_mail(\'' . esc_js(admin_url('admin-ajax.php')) . '\');" ';
                        }

                        $extra_attr_html = '';
                        if ( isset($value['extra_attr']) ) {
                            $extra_attr_html = $value['extra_attr'];
                        }

                        if ( isset($value['force_std']) && $value['force_std'] == true ) {
                            $val = $value['std'];
                        }

                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'id' => $value['id'],
                            'desc' => $value['desc'],
                            'hint_text' => isset($value['hint_text']) ? $value['hint_text'] : '',
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                            'field_params' => array(
                                'std' => $val,
                                'cust_type' => $cust_type,
                                'extra_atr' => $extra_atr . ' ' . $extra_attr_html,
                                'id' => $value['id'],
                                'active' => $active,
                                'return' => true,
                            ),
                        );

                        if ( isset($value['classes']) && $value['classes'] <> '' ) {
                            $wp_dp_opt_array['field_params']['classes'] = $value['classes'];
                        }

                        if ( isset($value['split']) && $value['split'] <> '' ) {
                            $wp_dp_opt_array['split'] = $value['split'];
                        }
                        $output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

                        break;

                    case 'yelp_access_token' :

                        $yelp_client_id = isset($wp_dp_plugin_options['wp_dp_yelp_app_id']) ? $wp_dp_plugin_options['wp_dp_yelp_app_id'] : '';
                        $yelp_secret_id = isset($wp_dp_plugin_options['wp_dp_yelp_secret']) ? $wp_dp_plugin_options['wp_dp_yelp_secret'] : '';
                        $yelp_access_token = isset($wp_dp_plugin_options['wp_dp_yelp_access_token']) ? $wp_dp_plugin_options['wp_dp_yelp_access_token'] : '';

                        $rand_numb = rand(10000, 99999);
                        $val = '';
                        if ( isset($wp_dp_plugin_options) ) {
                            if ( isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) ) {
                                $val = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                            } else {
                                $val = $value['std'];
                            }
                        } else {
                            $val = $value['std'];
                        }

                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'hint_text' => '',
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                        );
                        $output .= $wp_dp_html_fields->wp_dp_opening_field($wp_dp_opt_array);

                        $wp_dp_opt_array = array(
                            'std' => $val,
                            'extra_atr' => '',
                            'id' => $value['id'],
                            'return' => true,
                        );

                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);

                        if ( $yelp_client_id != '' && $yelp_secret_id != '' && $yelp_access_token == '' ) {
                            $output .= '
							<a id="token-btn-' . $rand_numb . '" href="javascript:void(0)" onclick="get_yelp_access_token()">' . wp_dp_plugin_text_srt('wp_dp_options_access_token') . '</a>
							<span id="yelp-' . $rand_numb . '"></span>';
                        }

                        $wp_dp_opt_array = array(
                            'desc' => '',
                        );

                        $output .= '
						<script>
						function get_yelp_access_token() {
                                                        var getting_yelp_token,
								this_loader;

							this_loader = jQuery("#yelp-' . $rand_numb . '");
							jQuery("#token-btn-' . $rand_numb . '").hide();
							this_loader.html(\'<img src="' . wp_dp::plugin_url() . 'assets/backend/images/ajax-loader.gif" alt="">\');
							getting_yelp_token = jQuery.ajax({
								url: "' . admin_url('admin-ajax.php') . '",
								method: "POST",
								data: {
									type: "access_token",
									action: "wp_dp_get_yelp_access_token"
								},
								dataType: "json"
							}).done(function (response) {
								jQuery("#wp_dp_' . $value['id'] . '").val(response.token);
								this_loader.html("");
								jQuery("#token-btn-' . $rand_numb . '").show();
							}).fail(function () {
								this_loader.html("");
								jQuery("#token-btn-' . $rand_numb . '").show();
							});
						}
						</script>';

                        $output .= $wp_dp_html_fields->wp_dp_closing_field($wp_dp_opt_array);

                        break;

                    case 'linkedin_access_token' :

                        $lk_client_id = isset($wp_dp_plugin_options['wp_dp_linkedin_app_id']) ? $wp_dp_plugin_options['wp_dp_linkedin_app_id'] : '';
                        $lk_secret_id = isset($wp_dp_plugin_options['wp_dp_linkedin_secret']) ? $wp_dp_plugin_options['wp_dp_linkedin_secret'] : '';
                        $lk_access_token = isset($wp_dp_plugin_options['wp_dp_linkedin_access_token']) ? $wp_dp_plugin_options['wp_dp_linkedin_access_token'] : '';

                        $state = md5(get_home_url());
                        $redirecturl = urlencode(admin_url('admin.php?page=wp_dp_settings'));
                        if ( $lk_client_id != '' && $lk_secret_id != '' && $lk_access_token == '' ) {
                            $linked_url = 'https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=' . $lk_client_id . '&redirect_uri=' . $redirecturl . '&auth%2Flinkedin&state=' . $state . '&scope=w_share+rw_company_admin';
                        }

                        $val = '';
                        if ( isset($_GET['code']) && isset($_GET['state']) && $_GET['state'] == $state && $lk_client_id != '' && $lk_secret_id != '' && $lk_access_token == '' ) {
                            $fields = 'grant_type=authorization_code&code=' . $_GET['code'] . '&redirect_uri=' . $redirecturl . '&client_id=' . $lk_client_id . '&client_secret=' . $lk_secret_id;
                            $ln_acc_tok_json = wp_dp_get_lk_page('https://www.linkedin.com/uas/oauth2/accessToken', '', false, $fields);
                            $ln_acc_tok_json = $ln_acc_tok_json['content'];

                            if ( $ln_acc_tok_json ) {
                                $ln_acc_tok = json_decode($ln_acc_tok_json, true);

                                if ( isset($ln_acc_tok['access_token']) ) {
                                    $val = $ln_acc_tok['access_token'];
                                }
                            }
                        } else if ( isset($wp_dp_plugin_options) ) {
                            if ( isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) ) {
                                $val = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                            } else {
                                $val = $value['std'];
                            }
                        } else {
                            $val = $value['std'];
                        }

                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'hint_text' => '',
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                        );
                        $output .= $wp_dp_html_fields->wp_dp_opening_field($wp_dp_opt_array);

                        $wp_dp_opt_array = array(
                            'std' => $val,
                            'extra_atr' => '',
                            'id' => $value['id'],
                            'return' => true,
                        );

                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);

                        if ( $lk_client_id != '' && $lk_secret_id != '' && $lk_access_token == '' ) {
                            $output .= '<a class="get-access-btn" href="' . $linked_url . '">' . wp_dp_plugin_text_srt('wp_dp_options_access_token') . '</a>';
                        }

                        $wp_dp_opt_array = array(
                            'desc' => '',
                        );
                        $output .= $wp_dp_html_fields->wp_dp_closing_field($wp_dp_opt_array);

                        break;

                    case 'fb_access_token' :

                        $fb_app_id = isset($wp_dp_plugin_options['wp_dp_facebook_app_id']) ? $wp_dp_plugin_options['wp_dp_facebook_app_id'] : '';
                        $fb_secret = isset($wp_dp_plugin_options['wp_dp_facebook_secret']) ? $wp_dp_plugin_options['wp_dp_facebook_secret'] : '';
                        $fb_access_token = isset($wp_dp_plugin_options['wp_dp_facebook_access_token']) ? $wp_dp_plugin_options['wp_dp_facebook_access_token'] : '';

                        $state = md5(get_home_url());
                        $redirecturl = urlencode(admin_url('admin.php?page=wp_dp_settings#tab-api-setting-show'));
                        if ( $fb_app_id != '' && $fb_secret != '' && $fb_access_token == '' ) {

                            $fb_session_state = md5(uniqid(rand(), TRUE));
                            if ( ! get_transient('wp_dp_fb_session_state') ) {
                                set_transient('wp_dp_fb_session_state', $fb_session_state, 60 * 60 * 24 * 30);
                            } else {
                                $fb_session_state = get_transient('wp_dp_fb_session_state');
                            }

                            $fb_access_url = "https://www.facebook.com/v2.6/dialog/oauth?client_id=" . $fb_app_id . "&redirect_uri=" . $redirecturl . "&state=" . $fb_session_state . "&scope=email,public_profile,publish_pages,user_posts,publish_actions,manage_pages";

                            if ( isset($_REQUEST['state']) && ($fb_session_state === $_REQUEST['state']) ) {

                                $code = "";
                                if ( isset($_REQUEST['code']) ) {
                                    $code = $_REQUEST["code"];
                                }
                                $token_url = "https://graph.facebook.com/v2.6/oauth/access_token?client_id=" . $fb_app_id . "&redirect_uri=" . $redirecturl . "&client_secret=" . $fb_secret . "&code=" . $code;

                                $params = null;
                                $get_fb_access_token = "";

                                $wp_remote_get_args = array(
                                    'timeout' => 50,
                                    'compress' => false,
                                    'decompress' => true,
                                );
                                $response = wp_remote_get($token_url, $wp_remote_get_args);

                                if ( is_array($response) ) {
                                    if ( isset($response['body']) ) {
                                        $decode_body = json_decode($response['body'], true);
                                        $params = $decode_body;
                                        if ( isset($params['access_token']) ) {
                                            $get_fb_access_token = $params['access_token'];
                                        }
                                    }
                                }
                                
                                

                                if ( $get_fb_access_token != "" ) {

                                    $offset = 0;
                                    $limit = 100;
                                    $data = array();

                                    do {
                                        $result1 = "";
                                        $pagearray1 = "";
                                        $wp_remote_get_args = array(
                                            'timeout' => 50,
                                            'compress' => false,
                                            'decompress' => true,
                                        );
                                        $pp = wp_remote_get("https://graph.facebook.com/v2.6/me/accounts?access_token=$get_fb_access_token&limit=$limit&offset=$offset", $wp_remote_get_args);
                                        if ( is_array($pp) ) {
                                            $result1 = $pp['body'];
                                            $pagearray1 = json_decode($result1);
                                            if ( is_array($pagearray1->data) )
                                                $data = array_merge($data, $pagearray1->data);
                                        } else
                                            break;
                                        $offset += $limit;
                                    }
                                    while ( isset($pagearray1->paging->next) );

                                    $newpgs = '';

                                    $count = count($data);

                                    $all_pages_names = array();
                                    if ( $count > 0 ) {
                                        for ( $i = 0; $i < $count; $i ++ ) {
                                            if ( isset($data[$i]->id) ) {
                                                $newpgs .= $data[$i]->id . "-" . $data[$i]->access_token . ",";
                                                $all_pages_names[$data[$i]->id] = $data[$i]->name;
                                            }
                                        }
                                        $newpgs = rtrim($newpgs, ",");
                                        if ( $newpgs != "" ) {
                                            $newpgs = $newpgs . ",-1";
                                        } else {
                                            $newpgs = -1;
                                        }
                                    }

                                    update_option('wp_dp_fb_pages_ids', $newpgs);
                                    update_option('wp_dp_fb_pages_names', $all_pages_names);
                                }
                            }
                        }

                        $val = '';
                        if ( isset($get_fb_access_token) && $get_fb_access_token != '' ) {
                            $val = $get_fb_access_token;
                        } else if ( isset($wp_dp_plugin_options) ) {
                            if ( isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) ) {
                                $val = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                            } else {
                                $val = $value['std'];
                            }
                        } else {
                            $val = $value['std'];
                        }

                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'hint_text' => '',
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                        );
                        $output .= $wp_dp_html_fields->wp_dp_opening_field($wp_dp_opt_array);

                        $wp_dp_opt_array = array(
                            'std' => $val,
                            'extra_atr' => '',
                            'id' => $value['id'],
                            'return' => true,
                        );

                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);

                        if ( $fb_app_id != '' && $fb_secret != '' && $fb_access_token == '' ) {
                            $output .= '<a href="' . $fb_access_url . '">' . wp_dp_plugin_text_srt('wp_dp_options_access_token') . '</a>';
                        }

                        $wp_dp_opt_array = array(
                            'desc' => '',
                        );
                        $output .= $wp_dp_html_fields->wp_dp_closing_field($wp_dp_opt_array);

                        $all_fb_pages = get_option('wp_dp_fb_pages_names');
                        if ( is_array($all_fb_pages) ) {
                            $fb_sharing_page = isset($wp_dp_plugin_options['wp_dp_fb_sharing_page']) ? $wp_dp_plugin_options['wp_dp_fb_sharing_page'] : '';

                            $wp_dp_opt_array = array(
                                'name' => wp_dp_plugin_text_srt('wp_dp_options_page_sharing'),
                                'hint_text' => '',
                                'label_desc' => '',
                            );
                            $output .= $wp_dp_html_fields->wp_dp_opening_field($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'std' => $fb_sharing_page,
                                'extra_atr' => '',
                                'id' => 'fb_sharing_page',
                                'return' => true,
                                'options' => $all_fb_pages
                            );
                            $output .= $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);

                            $wp_dp_opt_array = array(
                                'desc' => '',
                            );
                            $output .= $wp_dp_html_fields->wp_dp_closing_field($wp_dp_opt_array);
                        }

                        break;

                    case 'text3' :
                        if ( isset($wp_dp_plugin_options) ) {
                            if ( isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) ) {
                                $val = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                                $val2 = $wp_dp_plugin_options['wp_dp_' . $value['id2']];
                                $val3 = $wp_dp_plugin_options['wp_dp_' . $value['id3']];
                            } else {
                                $val = $value['std'];
                                $val2 = $value['std2'];
                                $val3 = $value['std3'];
                            }
                        } else {
                            $val = $value['std'];
                            $val2 = $value['std2'];
                            $val3 = $value['std3'];
                        }

                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'id' => 'radius_fields',
                            'desc' => '',
                            'hint_text' => $value['hint_text'],
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                            'fields_list' => array(
                                array(
                                    'type' => 'text', 'field_params' => array(
                                        'std' => $val,
                                        'id' => $value['id'],
                                        'extra_atr' => ' placeholder="' . $value['placeholder'] . '"',
                                        'return' => true,
                                        'classes' => 'input-small',
                                    ),
                                ),
                                array(
                                    'type' => 'text', 'field_params' => array(
                                        'std' => $val2,
                                        'id' => $value['id2'],
                                        'extra_atr' => ' placeholder="' . $value['placeholder2'] . '"',
                                        'return' => true,
                                        'classes' => 'input-small',
                                    ),
                                ),
                                array(
                                    'type' => 'text', 'field_params' => array(
                                        'std' => $val3,
                                        'id' => $value['id3'],
                                        'extra_atr' => ' placeholder="' . $value['placeholder3'] . '"',
                                        'return' => true,
                                        'classes' => 'input-small',
                                    ),
                                )
                            ),
                        );

                        $output .= $wp_dp_html_fields->wp_dp_multi_fields($wp_dp_opt_array);

                        break;

                    case 'yelp_cats_list' :
                        if ( isset($wp_dp_plugin_options) ) {
                            if ( isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) ) {
                                $val = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                            } else {
                                $val = array();
                            }
                        } else {
                            $val = array();
                        }

                        $yelp_cats = $this->yelp_categories();

                        $wp_dp_get_places = $val;

                        $output .= $wp_dp_html_fields->wp_dp_opening_field(
                                array(
                                    'name' => wp_dp_plugin_text_srt('wp_dp_options_yelp_places'),
                                    'label_desc' =>wp_dp_plugin_text_srt('wp_dp_options_yelp_places_hint'),
                        ));

                        if ( is_array($yelp_cats) && sizeof($yelp_cats) > 0 ) {

                            $output .= '<ul class="checkbox-list">';
                            foreach ( $yelp_cats as $feat_key => $features ) {
                                $feat_rand = rand(1000000, 99999999);
                                if ( isset($features) && $features <> '' ) {
                                    $output .= '<li class="col-lg-6 col-md-6 col-sm-12 col-xs-12">';
                                    $checked = (is_array($wp_dp_get_places) && in_array($feat_key, $wp_dp_get_places) ? ' checked="checked"' : '');
                                    $output .= $wp_dp_form_fields->wp_dp_form_checkbox_render(
                                            array( 'name' => '',
                                                'cust_id' => 'feat-' . $feat_rand . '',
                                                'cust_name' => 'wp_dp_' . $value['id'] . '[]',
                                                'classes' => '',
                                                'std' => $feat_key,
                                                'description' => '',
                                                'simple' => true,
                                                'return' => true,
                                                'extra_atr' => $checked,
                                            )
                                    );
                                    $output .= '<label for="feat-' . $feat_rand . '"> ' . $features . '</label>';
                                    $output .= '</li>';
                                }
                            }
                            $output .= '</ul>';
                        }

                        $output .= $wp_dp_html_fields->wp_dp_closing_field(array());

                        break;

                    case 'range' :
                        if ( isset($wp_dp_plugin_options) ) {
                            if ( isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) ) {
                                $val = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                            } else {
                                $val = $value['std'];
                            }
                        } else {
                            $val = $value['std'];
                        }

                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'id' => $value['id'],
                            'desc' => $value['desc'],
                            'hint_text' => $value['hint_text'],
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                            'field_params' => array(
                                'std' => $val,
                                'id' => $value['id'],
                                'range' => true,
                                'min' => $value['min'],
                                'max' => $value['max'],
                                'return' => true,
                            ),
                        );

                        if ( isset($value['split']) && $value['split'] <> '' ) {
                            $wp_dp_opt_array['split'] = $value['split'];
                        }

                        $output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

                        break;
                    case 'textarea':
                        $val = $value['std'];
                        $std = get_option($value['id']);
                        if ( isset($wp_dp_plugin_options) ) {
                            if ( isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) ) {
                                $val = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                            } else {
                                $val = $value['std'];
                            }
                        } else {
                            $val = $value['std'];
                        }
                        if ( ! isset($value['wp_dp_editor']) ) {
                            $value['wp_dp_editor'] = false;
                        }
                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'id' => $value['id'],
                            'desc' => $value['desc'],
                            'hint_text' => $value['hint_text'],
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                            'field_params' => array(
                                'std' => $val,
                                'id' => $value['id'],
                                'return' => true,
                                'wp_dp_editor' => $value['wp_dp_editor'],
                            ),
                        );

                        if ( isset($value['split']) && $value['split'] <> '' ) {
                            $wp_dp_opt_array['split'] = $value['split'];
                        }

                        $output .= $wp_dp_html_fields->wp_dp_textarea_field($wp_dp_opt_array);
                        break;
                    case "radio":
                        if ( isset($wp_dp_plugin_options) ) {
                            if ( isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) ) {
                                $select_value = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                            }
                        } else {
                            
                        }
                        $output .= '<div id="mail_from_name" class="form-elements">';
                        $output .= '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><label>' . $value['name'] . '</label></div>';
                        $output .= '<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">';
                        foreach ( $value['options'] as $key => $option ) {
                            $checked = '';
                            if ( $select_value != '' ) {
                                if ( $select_value == $option ) {
                                    $checked = ' checked';
                                }
                            } else {
                                if ( $value['std'] == $option ) {
                                    $checked = ' checked';
                                }
                            }

                            $output .= $wp_dp_html_fields->wp_dp_radio_field(
                                    array(
                                        'name' => $value['name'],
                                        'id' => $value['id'],
                                        'classes' => '',
                                        'std' => '',
                                        'description' => $option,
                                        'hint' => '',
                                        'prefix_on' => false,
                                        'extra_atr' => $checked,
                                        'field_params' => array(
                                            'std' => $option,
                                            'id' => $value['id'],
                                            'return' => true,
                                        ),
                                    )
                            );
                        }
                        $output .= '</div></div>';
                        break;
                    case 'select':
                        if ( isset($wp_dp_plugin_options) and $wp_dp_plugin_options <> '' ) {
                            if ( isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) and $wp_dp_plugin_options['wp_dp_' . $value['id']] <> '' ) {
                                $select_value = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                            } else {
                                $select_value = $value['std'];
                            }
                        } else {
                            $select_value = $value['std'];
                        }
                        if ( $select_value == 'absolute' ) {
                            if ( $wp_dp_plugin_options['wp_dp_headerbg_options'] == 'wp_dp_rev_slider' ) {
                                $output .= '<style>
                                                    #wp_dp_headerbg_image_upload,#wp_dp_headerbg_color_color,#wp_dp_headerbg_image_box{ display:none;}
                                                    #tab-header-options ul#wp_dp_headerbg_slider_1,#tab-header-options ul#wp_dp_headerbg_options_header{ display:block;}
                                            </style>';
                            } else if ( $wp_dp_plugin_options['wp_dp_headerbg_options'] == 'wp_dp_bg_image_color' ) {
                                $output .= '<style>
                                                    #wp_dp_headerbg_image_upload,#wp_dp_headerbg_color_color,#wp_dp_headerbg_image_box{ display:block;}
                                                    #tab-header-options ul#wp_dp_headerbg_slider_1{ display:none; }
                                            </style>';
                            } else {
                                $output .= '<style>
                                                    #wp_dp_headerbg_options_header{display:block;}
                                                    #wp_dp_headerbg_image_upload,#wp_dp_headerbg_color_color,#wp_dp_headerbg_image_box{ display:none;}
                                                    #tab-header-options ul#wp_dp_headerbg_slider_1{ display:none; }
                                            </style>';
                            }
                        } elseif ( $select_value == 'relative' ) {
                            $output .='<style>
                                                    #tab-header-options ul#wp_dp_headerbg_slider_1,#tab-header-options ul#wp_dp_headerbg_options_header,#tab-header-options ul#wp_dp_headerbg_image_upload,#tab-header-options ul#wp_dp_headerbg_color_color,#tab-header-options #wp_dp_headerbg_image_box{ display:none;}
                                      </style>';
                        }
                        $output .= ($value['id'] == 'wp_dp_bgimage_position') ? '<div class="main_tab">' : '';
                        $select_header_bg = ($value['id'] == 'wp_dp_header_position') ? 'onchange=javascript:wp_dp_set_headerbg(this.value)' : '';
                        $value_multiple = isset($value['multiple']) ? $value['multiple'] : false;
                        $value_hint_text = isset($value['hint_text']) ? $value['hint_text'] : false;

                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'id' => $value['id'],
                            'desc' => isset($value['desc']) ? $value['desc'] : '',
                            'multi' => $value_multiple,
                            'hint_text' => $value_hint_text,
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                            'field_params' => array(
                                'std' => $select_value,
                                'id' => $value['id'],
                                'options' => $value['options'],
                                'classes' => $wp_dp_classes,
                                'return' => true,
                            ),
                        );

                        if ( isset($value['change']) && $value['change'] == 'yes' ) {
                            $wp_dp_opt_array['field_params']['onclick'] = $value['id'] . '_change(this.value)';
                        }

                        if ( isset($value['split']) && $value['split'] <> '' ) {
                            $wp_dp_opt_array['split'] = $value['split'];
                        }

                        $output .= $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                        $output .=($value['id'] == 'wp_dp_bgimage_position') ? '</div>' : '';
                        break;
                    case 'custom_select':
                        if ( isset($wp_dp_plugin_options) and $wp_dp_plugin_options <> '' ) {
                            if ( isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) and $wp_dp_plugin_options['wp_dp_' . $value['id']] <> '' ) {
                                $select_value = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                                $user_info = get_userdata($select_value);
                                if ( ! empty($user_info) ) {
                                    $value['options'] = array( $select_value => $user_info->display_name );
                                }
                            } else {
                                $select_value = $value['std'];
                            }
                        } else {
                            $select_value = $value['std'];
                        }

                        $output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                            'id' => isset($value['id']) ? $value['id'] : '',
                            'name' => isset($value['name']) ? $value['name'] : '',
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                                )
                        );

                        $main_wraper = isset($value['main_wraper']) ? $value['main_wraper'] : false;
                        $main_wraper_class = isset($value['main_wraper_class']) ? $value['main_wraper_class'] : '';
                        $main_wraper_extra = isset($value['main_wraper_extra']) ? $value['main_wraper_extra'] : '';

                        if ( isset($main_wraper) && $main_wraper == true ) {
                            $main_wraper_class_str = '';
                            if ( isset($main_wraper_class) && $main_wraper_class != '' ) {
                                $main_wraper_class_str = $main_wraper_class;
                            }
                            $main_wraper_extra_str = '';
                            if ( isset($main_wraper_extra) && $main_wraper_extra != '' ) {
                                $main_wraper_extra_str = $main_wraper_extra;
                            }
                            $main_wraper_start = '<div class="' . $main_wraper_class_str . '" ' . $main_wraper_extra_str . '>';
                            $main_wraper_end = '</div>';
                        }
                        $output .= $main_wraper_start;
                        $wp_dp_opt_array = array(
                            'std' => $select_value,
                            'id' => $value['id'],
                            'options' => $value['options'],
                            'classes' => $wp_dp_classes,
                            'markup' => isset($value['markup']) ? $value['markup'] : '',
                            'return' => true,
                        );
                        $output .= $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);
                        $output .= $main_wraper_end;

                        $output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                            'desc' => '',
                        ));

                        break;
                    case 'select_values' :
                        if ( isset($wp_dp_plugin_options) and $wp_dp_plugin_options <> '' ) {
                            if ( isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) and $wp_dp_plugin_options['wp_dp_' . $value['id']] <> '' ) {
                                $select_value = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                            } else {
                                $select_value = $value['std'];
                            }
                        } else {
                            $select_value = $value['std'];
                        }
                        $output .= ($value['id'] == 'wp_dp_bgimage_position') ? '<div class="main_tab">' : '';
                        $select_header_bg = ($value['id'] == 'wp_dp_header_position') ? 'onchange=javascript:wp_dp_set_headerbg(this.value)' : '';
                        $wp_dp_search_display = '';
                        if ( $value['id'] == 'wp_dp_search_by_location' ) {
                            $wp_dp_search_display = 'none';
                        }
                        if ( $value['id'] == 'wp_dp_search_by_location_city' ) {
                            $wp_dp_search_by_location = isset($wp_dp_plugin_options['wp_dp_search_by_location']) ? $wp_dp_plugin_options['wp_dp_search_by_location'] : '';
                            $wp_dp_search_display = $wp_dp_search_by_location == 'single_city' ? 'block' : 'none';
                        }
                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'id' => $value['id'],
                            'desc' => $value['desc'],
                            'hint_text' => $value['hint_text'],
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                            'field_params' => array(
                                'std' => $select_value,
                                'id' => $value['id'],
                                'options' => $value['options'],
                                'classes' => $wp_dp_classes,
                                'return' => true,
                            ),
                        );

                        if ( isset($value['change']) && $value['change'] == 'yes' ) {
                            $wp_dp_opt_array['field_params']['onclick'] = $value['id'] . '_change(this.value)';
                        }

                        if ( isset($value['extra_atts']) && $value['extra_atts'] != '' ) {
                            $wp_dp_opt_array['field_params']['extra_atr'] = $value['extra_atts'];
                        }

                        if ( isset($value['split']) && $value['split'] <> '' ) {
                            $wp_dp_opt_array['split'] = $value['split'];
                        }

                        $output .= $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                        break;
                    case 'ad_select':
                        if ( isset($wp_dp_plugin_options) and $wp_dp_plugin_options <> '' ) {
                            if ( isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) and $wp_dp_plugin_options['wp_dp_' . $value['id']] <> '' ) {
                                $select_value = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                            } else {
                                $select_value = $value['std'];
                            }
                        } else {
                            $select_value = $value['std'];
                        }
                        if ( $select_value == 'absolute' ) {
                            if ( $wp_dp_plugin_options['wp_dp_headerbg_options'] == 'wp_dp_rev_slider' ) {
                                $output .='<style>
                                                    #wp_dp_headerbg_image_upload,#wp_dp_headerbg_color_color,#wp_dp_headerbg_image_box{ display:none;}
                                                    #tab-header-options ul#wp_dp_headerbg_slider_1,#tab-header-options ul#wp_dp_headerbg_options_header{ display:block;}
                                            </style>';
                            } else if ( $wp_dp_plugin_options['wp_dp_headerbg_options'] == 'wp_dp_bg_image_color' ) {
                                $output .='<style>
                                                    #wp_dp_headerbg_image_upload,#wp_dp_headerbg_color_color,#wp_dp_headerbg_image_box{ display:block;}
                                                    #tab-header-options ul#wp_dp_headerbg_slider_1{ display:none; }
                                            </style>';
                            } else {
                                $output .='<style>
                                                    #wp_dp_headerbg_options_header{display:block;}
                                                    #wp_dp_headerbg_image_upload,#wp_dp_headerbg_color_color,#wp_dp_headerbg_image_box{ display:none;}
                                                    #tab-header-options ul#wp_dp_headerbg_slider_1{ display:none; }
                                            </style>';
                            }
                        } elseif ( $select_value == 'relative' ) {
                            $output .='<style>
                                            #tab-header-options ul#wp_dp_headerbg_slider_1,#tab-header-options ul#wp_dp_headerbg_options_header,#tab-header-options ul#wp_dp_headerbg_image_upload,#tab-header-options ul#wp_dp_headerbg_color_color,#tab-header-options #wp_dp_headerbg_image_box{ display:none;}
                                     </style>';
                        }
                        $output .= ($value['id'] == 'wp_dp_bgimage_position') ? '<div class="main_tab">' : '';
                        $select_header_bg = ($value['id'] == 'wp_dp_header_position') ? 'onchange=javascript:wp_dp_set_headerbg(this.value)' : '';
                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'id' => $value['id'],
                            'desc' => $value['desc'],
                            'hint_text' => $value['hint_text'],
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                            'field_params' => array(
                                'std' => $select_value,
                                'id' => $value['id'],
                                'options' => $value['options'],
                                'return' => true,
                            ),
                        );

                        if ( isset($value['change']) && $value['change'] == 'yes' ) {
                            $wp_dp_opt_array['field_params']['onclick'] = $value['id'] . '_change(this.value)';
                        }

                        if ( isset($value['split']) && $value['split'] <> '' ) {
                            $wp_dp_opt_array['split'] = $value['split'];
                        }

                        $output .= $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);

                        break;

                    case "checkbox":
                        $std = '';
                        if ( isset($wp_dp_plugin_options) ) {
                            if ( isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) && $wp_dp_plugin_options['wp_dp_' . $value['id']] != '' ) {
                                $std = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                            } else {
                                $std = $value['std'];
                            }
                        } else {
                            $std = $value['std'];
                        }

                        $simple = false;
                        if ( isset($value['simple']) ) {
                            $simple = $value['simple'];
                        }
                        $field_hint_text = false;
                        if ( isset($value['hint_text']) ) {
                            $field_hint_text = $value['hint_text'];
                        }

                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'id' => $value['id'],
                            'desc' => $value['desc'],
                            'hint_text' => $field_hint_text,
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                            'field_params' => array(
                                'std' => $std,
                                'id' => $value['id'],
                                'extra_atr' => isset($value['onchange']) ? 'onchange=' . $value['onchange'] : '',
                                'return' => true,
                                'simple' => $simple,
                            ),
                        );

                        if ( isset($value['onchange']) && $value['onchange'] <> '' ) {
                            $wp_dp_opt_array['field_params']['extra_atr'] = ' onchange=' . $value['onchange'];
                        }

                        if ( isset($value['split']) && $value['split'] <> '' ) {
                            $wp_dp_opt_array['split'] = $value['split'];
                        }

                        $output .= $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);

                        break;
                    case "color":
                        $val = $value['std'];
                        if ( isset($wp_dp_plugin_options) ) {
                            if ( isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) ) {
                                $val = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                            }
                        } else {
                            $std = $value['std'];
                            if ( $std != '' ) {
                                $val = $std;
                            }
                        }
                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'id' => $value['id'],
                            'desc' => $value['desc'],
                            'hint_text' => $value['hint_text'],
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                            'field_params' => array(
                                'std' => $val,
                                'classes' => 'bg_color',
                                'id' => $value['id'],
                                'return' => true,
                            ),
                        );

                        if ( isset($value['split']) && $value['split'] <> '' ) {
                            $wp_dp_opt_array['split'] = $value['split'];
                        }

                        $output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                        break;
                    case "packages":
                        $obj = new wp_dp_plugin_options();
                        $output .= $obj->wp_dp_packages_section();
                        break;
                    case "cv_pkgs":
                        $obj = new wp_dp_plugin_options();
                        $output .= $obj->wp_dp_cv_pkgs_section();
                        break;
                    case "gateways":
                        global $gateways;
                        $general_settings = new WP_DP_PAYMENTS();
                        $wp_dp_counter = '';
                        foreach ( $gateways as $key => $value ) {
                            $output .='<div class="theme-help">';
                            $output .='<h4>' . $value . '</h4>';
                            $output .='<div class="clear"></div>';
                            $output .='</div>';
                            if ( class_exists($key) ) {
                                $settings = new $key();
                                $wp_dp_settings = $settings->settings();
                                $html = '';
                                foreach ( $wp_dp_settings as $key => $params ) {
                                    ob_start();
                                    wp_dp_settings_fields($key, $params);
                                    $post_data = ob_get_clean();
                                    $output .= $post_data;
                                }
                            }
                        }
                        break;

                    case "upload":
                        $wp_dp_counter ++;
                        if ( isset($wp_dp_plugin_options) and $wp_dp_plugin_options <> '' && isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) ) {
                            $val = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                        } else {
                            $val = $value['std'];
                        }
                        $display = ($val <> '' ? 'display' : 'none');
                        if ( isset($value['tab']) ) {
                            $output .= '<div class="main_tab"><div class="horizontal_tab" style="display:' . $value['display'] . '" id="' . $value['tab'] . '">';
                        }
                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'id' => $value['id'],
                            'std' => $val,
                            'desc' => $value['desc'],
                            'hint_text' => $value['hint_text'],
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                            'field_params' => array(
                                'std' => $val,
                                'id' => $value['id'],
                                'return' => true,
                            ),
                        );

                        if ( isset($value['split']) && $value['split'] <> '' ) {
                            $wp_dp_opt_array['split'] = $value['split'];
                        }

                        $output .= $wp_dp_html_fields->wp_dp_upload_file_field($wp_dp_opt_array);

                        if ( isset($value['tab']) ) {
                            $output.='</div></div>';
                        }
                        break;
                    case "upload logo":
                        $wp_dp_counter ++;

                        if ( isset($wp_dp_plugin_options) and $wp_dp_plugin_options <> '' && isset($wp_dp_plugin_options['wp_dp_' . $value['id']]) ) {
                            $val = $wp_dp_plugin_options['wp_dp_' . $value['id']];
                        } else {
                            $val = $value['std'];
                        }

                        $display = ($val <> '' ? 'display' : 'none');
                        if ( isset($value['tab']) ) {
                            $output .='<div class="main_tab"><div class="horizontal_tab" id="' . $value['tab'] . '">';
                        }
                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'id' => $value['id'],
                            'std' => $val,
                            'desc' => $value['desc'],
                            'hint_text' => $value['hint_text'],
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                            'field_params' => array(
                                'std' => $val,
                                'id' => $value['id'],
                                'return' => true,
                            ),
                        );

                        if ( isset($value['split']) && $value['split'] <> '' ) {
                            $wp_dp_opt_array['split'] = $value['split'];
                        }

                        $output .= $wp_dp_html_fields->wp_dp_upload_file_field($wp_dp_opt_array);

                        if ( isset($value['tab']) ) {
                            $output.='</div></div>';
                        }
                        break;
                    case 'select_dashboard':
                        if ( isset($wp_dp_plugin_options) and $wp_dp_plugin_options <> '' ) {
                            if ( isset($wp_dp_plugin_options[$value['id']]) ) {
                                $select_value = $wp_dp_plugin_options[$value['id']];
                            }
                        } else {
                            $select_value = $value['std'];
                        }

                        $select_value = wp_dp_wpml_lang_page_id($select_value, 'page');

                        $field_args = array(
                            'depth' => 0,
                            'child_of' => 0,
                            'class' => 'chosen-select',
                            'sort_order' => 'ASC',
                            'sort_column' => 'post_title',
                            'show_option_none' => wp_dp_plugin_text_srt('wp_dp_options_select_a_page'),
                            'hierarchical' => '1',
                            'exclude' => '',
                            'include' => '',
                            'meta_key' => '',
                            'meta_value' => '',
                            'authors' => '',
                            'exclude_tree' => '',
                            'selected' => $select_value,
                            'echo' => 0,
                            'name' => $value['id'],
                            'post_type' => 'page'
                        );
                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'id' => $value['id'],
                            'desc' => $value['desc'],
                            'hint_text' => $value['hint_text'],
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                            'std' => $select_value,
                            'args' => $field_args,
                            'return' => true,
                        );

                        if ( isset($value['split']) && $value['split'] <> '' ) {
                            $wp_dp_opt_array['split'] = $value['split'];
                        }
                        if ( isset($value['custom_page_select']) && $value['custom_page_select'] == true ) {
                            $output .= $wp_dp_html_fields->wp_dp_custom_select_page_field($wp_dp_opt_array);
                        } else {
                            $output .= $wp_dp_html_fields->wp_dp_select_page_field($wp_dp_opt_array);
                        }

                        break;
                    case 'map_nearby_places':
                        $wp_dp_get_features = array();
                        $ratings = array();
                        $post_id = 0;
                        $featured_lables = array();
                        $wp_dp_feature_icon = array();
                        $wp_dp_enable_not_selected = array();
                        ob_start();
                        ?>
                        <div id="tab-features_settings">
                            <?php
                            $post_meta = get_post_meta(get_the_id());
                            $features_data = array();
                            if ( isset($post_meta['wp_dp_listing_type_features']) && isset($post_meta['wp_dp_listing_type_features'][0]) ) {
                                $features_data = json_decode($post_meta['wp_dp_listing_type_features'][0], true);
                            }
                            if ( count($featured_lables) > 0 ) {
                                $wp_dp_opt_array = array(
                                    'name' => wp_dp_plugin_text_srt('wp_dp_options_unchecked_show'),
                                    'desc' => '',
                                    'hint_text' => '',
                                    'label_desc' => '',
                                    'echo' => true,
                                    'field_params' => array(
                                        'std' => $wp_dp_enable_not_selected,
                                        'id' => 'enable_not_selected',
                                        'return' => true,
                                    ),
                                );
                                $wp_dp_html_fields->wp_dp_checkbox_field($wp_dp_opt_array);
                            }
                            ?>
                            <?php
                            $icon_rand_id = rand(10000000, 99999999);

                            ob_start();
                            $wp_dp_map_markers_data = isset($wp_dp_plugin_options['wp_dp_map_markers_data']) ? $wp_dp_plugin_options['wp_dp_map_markers_data'] : array();
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
                                            <div class="nearby-img-icon-pare">
                                                <div class="nearby-img-f">
                                                    <?php
                                                    $wp_dp_opt_array = array(
                                                        'std' => '',
                                                        'cust_id' => 'wp_dp_map_marker_img_###COUNTER###',
                                                        'cust_name' => 'wp_dp_map_markers_data[image][]',
                                                        'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_options_currency_name') . '"',
                                                        'cust_type' => 'hidden',
                                                        'classes' => 'input-field',
                                                    );
                                                    $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                                    ?>
                                                    <a class="image-holder wp_dp_map_marker_img_###COUNTER###_box" title="<?php echo wp_dp_plugin_text_srt('wp_dp_options_select_image'); ?>">
                                                        <img src="" id="wp_dp_map_marker_img_###COUNTER###_img" class="cs-uploadMedia" alt="" name="wp_dp_map_marker_img_###COUNTER###" onerror="this.src='<?php echo plugins_url('wp-directorybox-manager/assets/frontend/images/upload_icon.png'); ?>'" style="display: block; width: 100%; height: 100%;" />
                                                    </a>
                                                </div>
                                                <div id="nearby-icon-apender-<?php echo absint($icon_rand_id) ?>" class="nearby-icon-f" style="display: none;"></div>
                                                <a href="javascript:void(0)" class="nearby-sel-img-icon-btn" data-id="<?php echo absint($icon_rand_id) ?>" data-img-txt="<?php echo wp_dp_plugin_text_srt('wp_dp_marker_opions_select_an_imag'); ?>" data-icon-txt="<?php echo wp_dp_plugin_text_srt('wp_dp_marker_opions_select_an_icon'); ?>"><?php echo wp_dp_plugin_text_srt('wp_dp_marker_opions_select_an_icon') ?></a>
                                                <?php
                                                $wp_dp_opt_array = array(
                                                    'std' => '',
                                                    'cust_id' => 'wp_dp_nearby_map_icon_type_' . absint($icon_rand_id),
                                                    'cust_name' => 'wp_dp_map_markers_data[icon_type][]',
                                                    'cust_type' => 'hidden',
                                                );
                                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                    <!--For Simple Input Element-->
                                    <div class="input-element">
                                        <div class="input-holder">
                                            <?php
                                            $wp_dp_opt_array = array(
                                                'std' => '',
                                                'cust_id' => 'wp_dp_map_marker_map_img_###COUNTER###',
                                                'cust_name' => 'wp_dp_map_markers_data[map_image][]',
                                                'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_options_currency_name') . '"',
                                                'cust_type' => 'hidden',
                                                'classes' => 'input-field',
                                            );
                                            $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                            ?>
                                            <a class="image-holder wp_dp_map_marker_map_img_###COUNTER###_box" title="<?php echo wp_dp_plugin_text_srt('wp_dp_options_select_map_image'); ?>">
                                                <img src="" id="wp_dp_map_marker_map_img_###COUNTER###_img" class="cs-uploadMedia" alt="" name="wp_dp_map_marker_map_img_###COUNTER###" onerror="this.src='<?php echo plugins_url('wp-directorybox-manager/assets/frontend/images/upload_icon.png'); ?>'" style="display: block; width: 100%; height: 100%;" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <!--For Simple Input Element-->
                                    <div class="input-element">
                                        <div class="input-holder">
                                            <?php
                                            $wp_dp_opt_array = array(
                                                'std' => '',
                                                'cust_name' => 'wp_dp_map_markers_data[label][]',
                                                'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_marker_opions_title') . '"',
                                                'classes' => 'input-field',
                                            );
                                            $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                    <!--For Simple Input Element-->
                                    <div class="input-element">
                                        <div class="input-holder">
                                            <?php
                                            $near_locations = array(
                                                'accounting' => wp_dp_plugin_text_srt('wp_dp_marker_opions_accounting'),
                                                'airport' => wp_dp_plugin_text_srt('wp_dp_marker_opions_airport'),
                                                'amusement_park' => wp_dp_plugin_text_srt('wp_dp_marker_opions_amusement_park'),
                                                'aquarium' => wp_dp_plugin_text_srt('wp_dp_marker_opions_aquarium'),
                                                'art_gallery' => wp_dp_plugin_text_srt('wp_dp_marker_opions_art_gallery'),
                                                'atm' => wp_dp_plugin_text_srt('wp_dp_marker_opions_atm'),
                                                'bakery' => wp_dp_plugin_text_srt('wp_dp_marker_opions_bakery'),
                                                'bank' => wp_dp_plugin_text_srt('wp_dp_marker_opions_bank'),
                                                'bar' => wp_dp_plugin_text_srt('wp_dp_marker_opions_bar'),
                                                'beauty_salon' => wp_dp_plugin_text_srt('wp_dp_marker_opions_beauty_salon'),
                                                'bicycle_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_bicycle_store'),
                                                'book_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_book_store'),
                                                'bowling_alley' => wp_dp_plugin_text_srt('wp_dp_marker_opions_bowling_alley'),
                                                'bus_station' => wp_dp_plugin_text_srt('wp_dp_marker_opions_bus_station'),
                                                'cafe' => wp_dp_plugin_text_srt('wp_dp_marker_opions_cafe'),
                                                'campground' => wp_dp_plugin_text_srt('wp_dp_marker_opions_campground'),
                                                'car_dealer' => wp_dp_plugin_text_srt('wp_dp_marker_opions_car_dealer'),
                                                'car_rental' => wp_dp_plugin_text_srt('wp_dp_marker_opions_car_rental'),
                                                'car_repair' => wp_dp_plugin_text_srt('wp_dp_marker_opions_car_repair'),
                                                'car_wash' => wp_dp_plugin_text_srt('wp_dp_marker_opions_car_wash'),
                                                'casino' => wp_dp_plugin_text_srt('wp_dp_marker_opions_casino'),
                                                'cemetery' => wp_dp_plugin_text_srt('wp_dp_marker_opions_cemetery'),
                                                'church' => wp_dp_plugin_text_srt('wp_dp_marker_opions_church'),
                                                'city_hall' => wp_dp_plugin_text_srt('wp_dp_marker_opions_city_hall'),
                                                'clothing_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_clothing_store'),
                                                'convenience_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_convenience_store'),
                                                'courthouse' => wp_dp_plugin_text_srt('wp_dp_marker_opions_courthouse'),
                                                'dentist' => wp_dp_plugin_text_srt('wp_dp_marker_opions_dentist'),
                                                'department_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_department_store'),
                                                'doctor' => wp_dp_plugin_text_srt('wp_dp_marker_opions_doctor'),
                                                'electrician' => wp_dp_plugin_text_srt('wp_dp_marker_opions_electrician'),
                                                'electronics_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_electronics_store'),
                                                'embassy' => wp_dp_plugin_text_srt('wp_dp_marker_opions_embassy'),
                                                'establishment (deprecated)' => wp_dp_plugin_text_srt('wp_dp_marker_opions_establishment_deprecated'),
                                                'finance (deprecated)' => wp_dp_plugin_text_srt('wp_dp_marker_opions_finance_deprecated'),
                                                'fire_station' => wp_dp_plugin_text_srt('wp_dp_marker_opions_fire_station'),
                                                'florist' => wp_dp_plugin_text_srt('wp_dp_marker_opions_florist'),
                                                'food (deprecated)' => wp_dp_plugin_text_srt('wp_dp_marker_opions_food_deprecated'),
                                                'funeral_home' => wp_dp_plugin_text_srt('wp_dp_marker_opions_funeral_home'),
                                                'furniture_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_furniture_store'),
                                                'gas_station' => wp_dp_plugin_text_srt('wp_dp_marker_opions_gas_station'),
                                                'general_contractor (deprecated)' => wp_dp_plugin_text_srt('wp_dp_marker_opions_general_contractor_deprecated'),
                                                'grocery_or_supermarket (deprecated)' => wp_dp_plugin_text_srt('wp_dp_marker_opions_grocery_or_supermarket_deprecated'),
                                                'gym' => wp_dp_plugin_text_srt('wp_dp_marker_opions_gym'),
                                                'hair_care' => wp_dp_plugin_text_srt('wp_dp_marker_opions_hair_care'),
                                                'hardware_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_hardware_store'),
                                                'health (deprecated)' => wp_dp_plugin_text_srt('wp_dp_marker_opions_health_deprecated'),
                                                'hindu_temple' => wp_dp_plugin_text_srt('wp_dp_marker_opions_hindu_temple'),
                                                'home_goods_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_home_goods_store'),
                                                'hospital' => wp_dp_plugin_text_srt('wp_dp_marker_opions_hospital'),
                                                'insurance_agency' => wp_dp_plugin_text_srt('wp_dp_marker_opions_insurance_agency'),
                                                'jewelry_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_jewelry_store'),
                                                'laundry' => wp_dp_plugin_text_srt('wp_dp_marker_opions_laundry'),
                                                'lawyer' => wp_dp_plugin_text_srt('wp_dp_marker_opions_lawyer'),
                                                'library' => wp_dp_plugin_text_srt('wp_dp_marker_opions_library'),
                                                'liquor_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_liquor_store'),
                                                'local_government_office' => wp_dp_plugin_text_srt('wp_dp_marker_opions_local_government_office'),
                                                'locksmith' => wp_dp_plugin_text_srt('wp_dp_marker_opions_locksmith'),
                                                'lodging' => wp_dp_plugin_text_srt('wp_dp_marker_opions_lodging'),
                                                'meal_delivery' => wp_dp_plugin_text_srt('wp_dp_marker_opions_meal_delivery'),
                                                'meal_takeaway' => wp_dp_plugin_text_srt('wp_dp_marker_opions_meal_takeaway'),
                                                'mosque' => wp_dp_plugin_text_srt('wp_dp_marker_opions_mosque'),
                                                'movie_rental' => wp_dp_plugin_text_srt('wp_dp_marker_opions_movie_rental'),
                                                'movie_theater' => wp_dp_plugin_text_srt('wp_dp_marker_opions_movie_theater'),
                                                'moving_company' => wp_dp_plugin_text_srt('wp_dp_marker_opions_moving_company'),
                                                'museum' => wp_dp_plugin_text_srt('wp_dp_marker_opions_museum'),
                                                'night_club' => wp_dp_plugin_text_srt('wp_dp_marker_opions_night_club'),
                                                'painter' => wp_dp_plugin_text_srt('wp_dp_marker_opions_painter'),
                                                'park' => wp_dp_plugin_text_srt('wp_dp_marker_opions_park'),
                                                'parking' => wp_dp_plugin_text_srt('wp_dp_marker_opions_parking'),
                                                'pet_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_pet_store'),
                                                'pharmacy' => wp_dp_plugin_text_srt('wp_dp_marker_opions_pharmacy'),
                                                'physiotherapist' => wp_dp_plugin_text_srt('wp_dp_marker_opions_physiotherapist'),
                                                'place_of_worship (deprecated)' => wp_dp_plugin_text_srt('wp_dp_marker_opions_place_of_worship_deprecated'),
                                                'plumber' => wp_dp_plugin_text_srt('wp_dp_marker_opions_plumber'),
                                                'police' => wp_dp_plugin_text_srt('wp_dp_marker_opions_police'),
                                                'post_office' => wp_dp_plugin_text_srt('wp_dp_marker_opions_post_office'),
                                                'real_estate_agency' => wp_dp_plugin_text_srt('wp_dp_marker_opions_real_estate_agency'),
                                                'restaurant' => wp_dp_plugin_text_srt('wp_dp_marker_opions_restaurant'),
                                                'roofing_contractor' => wp_dp_plugin_text_srt('wp_dp_marker_opions_roofing_contractor'),
                                                'rv_park' => wp_dp_plugin_text_srt('wp_dp_marker_opions_rv_park'),
                                                'school' => wp_dp_plugin_text_srt('wp_dp_marker_opions_school'),
                                                'shoe_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_shoe_store'),
                                                'shopping_mall' => wp_dp_plugin_text_srt('wp_dp_marker_opions_shopping_mall'),
                                                'spa' => wp_dp_plugin_text_srt('wp_dp_marker_opions_spa'),
                                                'stadium' => wp_dp_plugin_text_srt('wp_dp_marker_opions_stadium'),
                                                'storage' => wp_dp_plugin_text_srt('wp_dp_marker_opions_storage'),
                                                'store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_store'),
                                                'subway_station' => wp_dp_plugin_text_srt('wp_dp_marker_opions_subway_station'),
                                                'synagogue' => wp_dp_plugin_text_srt('wp_dp_marker_opions_synagogue'),
                                                'taxi_stand' => wp_dp_plugin_text_srt('wp_dp_marker_opions_taxi_stand'),
                                                'train_station' => wp_dp_plugin_text_srt('wp_dp_marker_opions_train_station'),
                                                'transit_station' => wp_dp_plugin_text_srt('wp_dp_marker_opions_transit_station'),
                                                'travel_agency' => wp_dp_plugin_text_srt('wp_dp_marker_opions_travel_agency'),
                                                'university' => wp_dp_plugin_text_srt('wp_dp_marker_opions_university'),
                                                'veterinary_care' => wp_dp_plugin_text_srt('wp_dp_marker_opions_veterinary_care'),
                                                'zoo' => wp_dp_plugin_text_srt('wp_dp_marker_opions_zoo')
                                            );

                                            $near_locations_array = array();
                                            foreach ( $near_locations as $near_locations_single_key => $near_locations_single_value ) :
                                                $near_locations_array[$near_locations_single_key] = $near_locations_single_value;
                                            endforeach;

                                            $wp_dp_opt_array = array(
                                                'std' => '',
                                                'cust_name' => 'wp_dp_map_markers_data[type][]',
                                                'extra_atr' => '',
                                                'classes' => 'input-field chosen-select',
                                                'options' => $near_locations_array
                                            );
                                            $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <a href="javascript:void(0);" class="wp-dp-dpove wp-dp-parent-li-dpove"><i class="icon-close2"></i></a> 
                            </li>
                            <?php $empty_row = ob_get_clean(); ?>

                            <div class="wp-dp-list-wrap nearby-places-list-wraper">
                                <ul class="wp-dp-list-layout">
                                    <li class="wp-dp-list-label">

                                        <div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">
                                            <div class="element-label">
                                                <label></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <div class="element-label">
                                                <label><?php echo wp_dp_plugin_text_srt('wp_dp_marker_opions_image'); ?></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                            <div class="element-label">
                                                <label><?php echo wp_dp_plugin_text_srt('wp_dp_marker_opions_map_iamge'); ?> </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <div class="element-label">
                                                <label><?php echo wp_dp_plugin_text_srt('wp_dp_marker_opions_title'); ?> </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                            <div class="element-label">
                                                <label><?php echo wp_dp_plugin_text_srt('wp_dp_marker_opions_marker_type'); ?> </label>
                                            </div>
                                        </div>
                                    </li>

                                    <?php
                                    $counter = 0;
                                    if ( is_array($wp_dp_map_markers_data) && sizeof($wp_dp_map_markers_data) > 0 && isset($wp_dp_map_markers_data['image']) ) {

                                        foreach ( $wp_dp_map_markers_data['image'] as $key => $row ) {
                                            $rand_id = rand(123234, 9000000);
                                            $image = isset($wp_dp_map_markers_data['image'][$key]) ? $wp_dp_map_markers_data['image'][$key] : '';
                                            $map_image = isset($wp_dp_map_markers_data['map_image'][$key]) ? $wp_dp_map_markers_data['map_image'][$key] : '';
                                            $title = isset($wp_dp_map_markers_data['label'][$key]) ? $wp_dp_map_markers_data['label'][$key] : '';
                                            $type = isset($wp_dp_map_markers_data['type'][$key]) ? $wp_dp_map_markers_data['type'][$key] : '';
                                            $icon_value = isset($wp_dp_map_markers_data['icon'][$key]) ? $wp_dp_map_markers_data['icon'][$key] : '';
                                            $icon_group_value = isset($wp_dp_map_markers_data['icon_group'][$key]) ? $wp_dp_map_markers_data['icon_group'][$key] : 'default';
                                            $map_icon_type = isset($wp_dp_map_markers_data['icon_type'][$key]) && $wp_dp_map_markers_data['icon_type'][$key] != '' ? $wp_dp_map_markers_data['icon_type'][$key] : 'image';
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
                                                            <div class="nearby-img-icon-pare">
                                                                <div class="nearby-img-f" style="display: <?php echo ($map_icon_type == 'image') ? 'block' : 'none' ?>;">
                                                                    <?php
                                                                    $wp_dp_opt_array = array(
                                                                        'std' => wp_dp_cs_allow_special_char($image),
                                                                        'cust_id' => 'wp_dp_map_marker_img_' . absint($counter),
                                                                        'cust_name' => 'wp_dp_map_markers_data[image][]',
                                                                        'cust_type' => 'hidden',
                                                                        'classes' => 'input-field',
                                                                    );
                                                                    $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                                                    ?>
                                                                    <a class="image-holder wp_dp_map_marker_img_<?php echo absint($counter); ?>_box" title="<?php echo wp_dp_plugin_text_srt('wp_dp_options_select_image'); ?>">
                                                                        <img src="<?php echo wp_get_attachment_url($image); ?>" id="wp_dp_map_marker_img_<?php echo absint($counter); ?>_img" class="cs-uploadMedia" alt="" name="wp_dp_map_marker_img_<?php echo absint($counter); ?>" onerror="this.src='<?php echo plugins_url('wp-directorybox-manager/assets/frontend/images/upload_icon.png'); ?>'" style="display: block; width: 100%; height: 100%;" />
                                                                    </a>
                                                                </div>
                                                                <div class="nearby-icon-f" style="display: <?php echo ($map_icon_type == 'icon') ? 'block' : 'none' ?>;">
                                                                    <?php echo apply_filters('cs_icons_fields', $icon_value, 'wp_dp_map_markers_data_icon_' . $rand_id, 'wp_dp_map_markers_data[icon]', $icon_group_value, 'wp_dp_map_markers_data[icon_group][]'); ?>
                                                                </div>
                                                                <a href="javascript:void(0)" class="nearby-sel-img-icon-btn" data-id="<?php echo absint($rand_id) ?>" data-img-txt="<?php echo wp_dp_plugin_text_srt('wp_dp_marker_opions_select_an_imag'); ?>" data-icon-txt="<?php echo wp_dp_plugin_text_srt('wp_dp_marker_opions_select_an_icon'); ?>"><?php echo ($map_icon_type == 'icon' ? wp_dp_plugin_text_srt('wp_dp_marker_opions_select_an_imag') : wp_dp_plugin_text_srt('wp_dp_marker_opions_select_an_icon')) ?></a>
                                                                <?php
                                                                $wp_dp_opt_array = array(
                                                                    'std' => $map_icon_type,
                                                                    'cust_id' => 'wp_dp_nearby_map_icon_type_' . absint($rand_id),
                                                                    'cust_name' => 'wp_dp_map_markers_data[icon_type][]',
                                                                    'cust_type' => 'hidden',
                                                                );
                                                                $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                                    <!--For Simple Input Element-->
                                                    <div class="input-element">
                                                        <div class="input-holder">
                                                            <?php
                                                            $wp_dp_opt_array = array(
                                                                'std' => isset($image) ? wp_dp_cs_allow_special_char($map_image) : '',
                                                                'cust_id' => 'wp_dp_map_marker_map_img_' . absint($counter),
                                                                'cust_name' => 'wp_dp_map_markers_data[map_image][]',
                                                                'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_options_currency_name') . '"',
                                                                'cust_type' => 'hidden',
                                                                'classes' => 'input-field',
                                                            );
                                                            $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                                            ?>
                                                            <a class="image-holder wp_dp_map_marker_map_img_<?php echo absint($counter); ?>_box" title="<?php echo wp_dp_plugin_text_srt('wp_dp_options_select_map_image'); ?>">
                                                                <img src="<?php echo wp_get_attachment_url($map_image); ?>" id="wp_dp_map_marker_map_img_<?php echo absint($counter); ?>_img" class="cs-uploadMedia" alt="" name="wp_dp_map_marker_map_img_<?php echo absint($counter); ?>" onerror="this.src='<?php echo plugins_url('wp-directorybox-manager/assets/frontend/images/upload_icon.png'); ?>'" style="display: block; width: 100%; height: 100%;" />
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                                    <!--For Simple Input Element-->
                                                    <div class="input-element">
                                                        <div class="input-holder">
                                                            <?php
                                                            $wp_dp_opt_array = array(
                                                                'std' => isset($image) ? esc_html($title) : '',
                                                                'cust_name' => 'wp_dp_map_markers_data[label][]',
                                                                'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_marker_opions_title') . '"',
                                                                'classes' => 'input-field',
                                                            );
                                                            $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                                    <!--For Simple Input Element-->
                                                    <div class="input-element">
                                                        <div class="input-holder">
                                                            <?php
                                                            $near_locations = array(
                                                                'accounting' => wp_dp_plugin_text_srt('wp_dp_marker_opions_accounting'),
                                                                'airport' => wp_dp_plugin_text_srt('wp_dp_marker_opions_airport'),
                                                                'amusement_park' => wp_dp_plugin_text_srt('wp_dp_marker_opions_amusement_park'),
                                                                'aquarium' => wp_dp_plugin_text_srt('wp_dp_marker_opions_aquarium'),
                                                                'art_gallery' => wp_dp_plugin_text_srt('wp_dp_marker_opions_art_gallery'),
                                                                'atm' => wp_dp_plugin_text_srt('wp_dp_marker_opions_atm'),
                                                                'bakery' => wp_dp_plugin_text_srt('wp_dp_marker_opions_bakery'),
                                                                'bank' => wp_dp_plugin_text_srt('wp_dp_marker_opions_bank'),
                                                                'bar' => wp_dp_plugin_text_srt('wp_dp_marker_opions_bar'),
                                                                'beauty_salon' => wp_dp_plugin_text_srt('wp_dp_marker_opions_beauty_salon'),
                                                                'bicycle_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_bicycle_store'),
                                                                'book_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_book_store'),
                                                                'bowling_alley' => wp_dp_plugin_text_srt('wp_dp_marker_opions_bowling_alley'),
                                                                'bus_station' => wp_dp_plugin_text_srt('wp_dp_marker_opions_bus_station'),
                                                                'cafe' => wp_dp_plugin_text_srt('wp_dp_marker_opions_cafe'),
                                                                'campground' => wp_dp_plugin_text_srt('wp_dp_marker_opions_campground'),
                                                                'car_dealer' => wp_dp_plugin_text_srt('wp_dp_marker_opions_car_dealer'),
                                                                'car_rental' => wp_dp_plugin_text_srt('wp_dp_marker_opions_car_rental'),
                                                                'car_repair' => wp_dp_plugin_text_srt('wp_dp_marker_opions_car_repair'),
                                                                'car_wash' => wp_dp_plugin_text_srt('wp_dp_marker_opions_car_wash'),
                                                                'casino' => wp_dp_plugin_text_srt('wp_dp_marker_opions_casino'),
                                                                'cemetery' => wp_dp_plugin_text_srt('wp_dp_marker_opions_cemetery'),
                                                                'church' => wp_dp_plugin_text_srt('wp_dp_marker_opions_church'),
                                                                'city_hall' => wp_dp_plugin_text_srt('wp_dp_marker_opions_city_hall'),
                                                                'clothing_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_clothing_store'),
                                                                'convenience_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_convenience_store'),
                                                                'courthouse' => wp_dp_plugin_text_srt('wp_dp_marker_opions_courthouse'),
                                                                'dentist' => wp_dp_plugin_text_srt('wp_dp_marker_opions_dentist'),
                                                                'department_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_department_store'),
                                                                'doctor' => wp_dp_plugin_text_srt('wp_dp_marker_opions_doctor'),
                                                                'electrician' => wp_dp_plugin_text_srt('wp_dp_marker_opions_electrician'),
                                                                'electronics_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_electronics_store'),
                                                                'embassy' => wp_dp_plugin_text_srt('wp_dp_marker_opions_embassy'),
                                                                'establishment (deprecated)' => wp_dp_plugin_text_srt('wp_dp_marker_opions_establishment_deprecated'),
                                                                'finance (deprecated)' => wp_dp_plugin_text_srt('wp_dp_marker_opions_finance_deprecated'),
                                                                'fire_station' => wp_dp_plugin_text_srt('wp_dp_marker_opions_fire_station'),
                                                                'florist' => wp_dp_plugin_text_srt('wp_dp_marker_opions_florist'),
                                                                'food (deprecated)' => wp_dp_plugin_text_srt('wp_dp_marker_opions_food_deprecated'),
                                                                'funeral_home' => wp_dp_plugin_text_srt('wp_dp_marker_opions_funeral_home'),
                                                                'furniture_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_furniture_store'),
                                                                'gas_station' => wp_dp_plugin_text_srt('wp_dp_marker_opions_gas_station'),
                                                                'general_contractor (deprecated)' => wp_dp_plugin_text_srt('wp_dp_marker_opions_general_contractor_deprecated'),
                                                                'grocery_or_supermarket (deprecated)' => wp_dp_plugin_text_srt('wp_dp_marker_opions_grocery_or_supermarket_deprecated'),
                                                                'gym' => wp_dp_plugin_text_srt('wp_dp_marker_opions_gym'),
                                                                'hair_care' => wp_dp_plugin_text_srt('wp_dp_marker_opions_hair_care'),
                                                                'hardware_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_hardware_store'),
                                                                'health (deprecated)' => wp_dp_plugin_text_srt('wp_dp_marker_opions_health_deprecated'),
                                                                'hindu_temple' => wp_dp_plugin_text_srt('wp_dp_marker_opions_hindu_temple'),
                                                                'home_goods_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_home_goods_store'),
                                                                'hospital' => wp_dp_plugin_text_srt('wp_dp_marker_opions_hospital'),
                                                                'insurance_agency' => wp_dp_plugin_text_srt('wp_dp_marker_opions_insurance_agency'),
                                                                'jewelry_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_jewelry_store'),
                                                                'laundry' => wp_dp_plugin_text_srt('wp_dp_marker_opions_laundry'),
                                                                'lawyer' => wp_dp_plugin_text_srt('wp_dp_marker_opions_lawyer'),
                                                                'library' => wp_dp_plugin_text_srt('wp_dp_marker_opions_library'),
                                                                'liquor_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_liquor_store'),
                                                                'local_government_office' => wp_dp_plugin_text_srt('wp_dp_marker_opions_local_government_office'),
                                                                'locksmith' => wp_dp_plugin_text_srt('wp_dp_marker_opions_locksmith'),
                                                                'lodging' => wp_dp_plugin_text_srt('wp_dp_marker_opions_lodging'),
                                                                'meal_delivery' => wp_dp_plugin_text_srt('wp_dp_marker_opions_meal_delivery'),
                                                                'meal_takeaway' => wp_dp_plugin_text_srt('wp_dp_marker_opions_meal_takeaway'),
                                                                'mosque' => wp_dp_plugin_text_srt('wp_dp_marker_opions_mosque'),
                                                                'movie_rental' => wp_dp_plugin_text_srt('wp_dp_marker_opions_movie_rental'),
                                                                'movie_theater' => wp_dp_plugin_text_srt('wp_dp_marker_opions_movie_theater'),
                                                                'moving_company' => wp_dp_plugin_text_srt('wp_dp_marker_opions_moving_company'),
                                                                'museum' => wp_dp_plugin_text_srt('wp_dp_marker_opions_museum'),
                                                                'night_club' => wp_dp_plugin_text_srt('wp_dp_marker_opions_night_club'),
                                                                'painter' => wp_dp_plugin_text_srt('wp_dp_marker_opions_painter'),
                                                                'park' => wp_dp_plugin_text_srt('wp_dp_marker_opions_park'),
                                                                'parking' => wp_dp_plugin_text_srt('wp_dp_marker_opions_parking'),
                                                                'pet_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_pet_store'),
                                                                'pharmacy' => wp_dp_plugin_text_srt('wp_dp_marker_opions_pharmacy'),
                                                                'physiotherapist' => wp_dp_plugin_text_srt('wp_dp_marker_opions_physiotherapist'),
                                                                'place_of_worship (deprecated)' => wp_dp_plugin_text_srt('wp_dp_marker_opions_place_of_worship_deprecated'),
                                                                'plumber' => wp_dp_plugin_text_srt('wp_dp_marker_opions_plumber'),
                                                                'police' => wp_dp_plugin_text_srt('wp_dp_marker_opions_police'),
                                                                'post_office' => wp_dp_plugin_text_srt('wp_dp_marker_opions_post_office'),
                                                                'real_estate_agency' => wp_dp_plugin_text_srt('wp_dp_marker_opions_real_estate_agency'),
                                                                'restaurant' => wp_dp_plugin_text_srt('wp_dp_marker_opions_restaurant'),
                                                                'roofing_contractor' => wp_dp_plugin_text_srt('wp_dp_marker_opions_roofing_contractor'),
                                                                'rv_park' => wp_dp_plugin_text_srt('wp_dp_marker_opions_rv_park'),
                                                                'school' => wp_dp_plugin_text_srt('wp_dp_marker_opions_school'),
                                                                'shoe_store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_shoe_store'),
                                                                'shopping_mall' => wp_dp_plugin_text_srt('wp_dp_marker_opions_shopping_mall'),
                                                                'spa' => wp_dp_plugin_text_srt('wp_dp_marker_opions_spa'),
                                                                'stadium' => wp_dp_plugin_text_srt('wp_dp_marker_opions_stadium'),
                                                                'storage' => wp_dp_plugin_text_srt('wp_dp_marker_opions_storage'),
                                                                'store' => wp_dp_plugin_text_srt('wp_dp_marker_opions_store'),
                                                                'subway_station' => wp_dp_plugin_text_srt('wp_dp_marker_opions_subway_station'),
                                                                'synagogue' => wp_dp_plugin_text_srt('wp_dp_marker_opions_synagogue'),
                                                                'taxi_stand' => wp_dp_plugin_text_srt('wp_dp_marker_opions_taxi_stand'),
                                                                'train_station' => wp_dp_plugin_text_srt('wp_dp_marker_opions_train_station'),
                                                                'transit_station' => wp_dp_plugin_text_srt('wp_dp_marker_opions_transit_station'),
                                                                'travel_agency' => wp_dp_plugin_text_srt('wp_dp_marker_opions_travel_agency'),
                                                                'university' => wp_dp_plugin_text_srt('wp_dp_marker_opions_university'),
                                                                'veterinary_care' => wp_dp_plugin_text_srt('wp_dp_marker_opions_veterinary_care'),
                                                                'zoo' => wp_dp_plugin_text_srt('wp_dp_marker_opions_zoo')
                                                            );

                                                            $near_locations_array = array();
                                                            foreach ( $near_locations as $near_locations_single_key => $near_locations_single_value ) :
                                                                $near_locations_array[$near_locations_single_key] = $near_locations_single_value;
                                                            endforeach;
                                                            $wp_dp_opt_array = array(
                                                                'std' => $type,
                                                                'cust_name' => 'wp_dp_map_markers_data[type][]',
                                                                'extra_atr' => '',
                                                                'classes' => 'input-field chosen-select',
                                                                'options' => $near_locations_array
                                                            );
                                                            $wp_dp_form_fields->wp_dp_form_select_render($wp_dp_opt_array);
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="javascript:void(0);" class="wp-dp-dpove wp-dp-parent-li-dpove"><i class="icon-close2"></i></a> 
                                            </li>

                                            <?php
                                            $counter ++;
                                        }
                                    } else {
                                        $new_row = str_replace('###COUNTER###', $counter, $empty_row);
                                        echo wp_dp_allow_special_char($new_row);
                                        $counter ++;
                                    }
                                    ?>


                                </ul>
                                <ul class="wp-dp-list-button-ul">
                                    <li class="wp-dp-list-button">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <!--For Simple Input Element-->
                                            <div class="input-element">
                                                <a href="javascript:void(0);" id="click-more" class="wp-dp-add-more cntrl-add-new-row" onclick="duplicate1('<?php echo absint($icon_rand_id) ?>')"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_type_meta_feature_add_row'); ?></a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>   
                        </div>
                        <script type="text/javascript">
                            var table_class = ".features-templates-wrapper1";

                            jQuery(document).ready(function () {
                                var table_class = ".nearby-places-list-wraper .wp-dp-list-layout";
                                jQuery(table_class).sortable({
                                    cancel: "input, .wp-dp-list-label"
                                });
                            });

                            var counter = '<?php echo absint($counter); ?>';
                            var row_str = '<?php echo addslashes(trim(preg_replace('/\s\s+/', ' ', $empty_row))); ?>';
                            function duplicate1(icon_num) {
                                counter++;
                                var new_str = row_str.replace(/###COUNTER###/g, counter);
                                $(".nearby-places-list-wraper .wp-dp-list-layout").append(new_str);

                                var new_icon_num = icon_num + counter;

                                $('#nearby-icon-apender-' + icon_num).attr('id', 'nearby-icon-apender-' + new_icon_num);
                                $('#nearby-icon-apender-' + new_icon_num).html('<img src="<?php echo wp_dp::plugin_url() . 'assets/backend/images/ajax-loader.gif' ?>" alt="" />');
                                $.ajax({
                                    url: '<?php echo admin_url('admin-ajax.php') ?>',
                                    method: "POST",
                                    data: {
                                        icon_num: new_icon_num,
                                        action: 'wp_dp_nearby_add_row_icon'
                                    },
                                    dataType: "json"
                                }).done(function (response) {
                                    $('#nearby-icon-apender-' + new_icon_num).html(response.html);
                                    $('#nearby-icon-apender-' + new_icon_num).removeAttr('id');
                                }).fail(function () {
                                    $('#nearby-icon-apender-' + new_icon_num).html('');
                                    $('#nearby-icon-apender-' + new_icon_num).removeAttr('id');
                                });
                            }

                            jQuery(document).on('click', '.features-templates-wrapper1 .cntrl-delete-rows', function () {
                                delete_row_top(this);
                                return false;
                            });

                            function delete_row_top1(delete_link) {
                                $(delete_link).parent().parent().remove();
                            }
                        </script>
                        <?php
                        $output .= ob_get_clean();
                        break;
                    case 'default_dynamic_location_fields':
                        $output .= $GLOBALS['Wp_dp_Plugin_Functions']->wp_dp_location_fields('', 'default', false);
                        break;
                    case 'default_location_fields':
                        global $wp_dp_plugin_options, $post;
                        $wp_dp_map_latitude = isset($wp_dp_plugin_options['wp_dp_post_loc_latitude']) ? $wp_dp_plugin_options['wp_dp_post_loc_latitude'] : '';
                        $wp_dp_map_longitude = isset($wp_dp_plugin_options['wp_dp_post_loc_longitude']) ? $wp_dp_plugin_options['wp_dp_post_loc_longitude'] : '';
                        $wp_dp_map_zoom = isset($wp_dp_plugin_options['wp_dp_map_zoom_level']) ? $wp_dp_plugin_options['wp_dp_map_zoom_level'] : '';
                        if ( $wp_dp_map_latitude == '' ) {
                            $wp_dp_map_latitude = '51.5';
                        }
                        if ( $wp_dp_map_longitude == '' ) {
                            $wp_dp_map_longitude = '-0.2';
                        }
                        if ( $wp_dp_map_zoom == '' ) {
                            $wp_dp_map_zoom = '9';
                        }

                        $wp_dp_map_address = isset($wp_dp_plugin_options['wp_dp_post_loc_address']) ? $wp_dp_plugin_options['wp_dp_post_loc_address'] : '';

                        $wp_dp_obj = new wp_dp();
                        $wp_dp_obj->wp_dp_location_gmap_script();
                        $wp_dp_obj->wp_dp_google_place_scripts();
                        $wp_dp_obj->wp_dp_autocomplete_scripts();

                        $wp_dp_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_address'),
                            'id' => 'post_loc_address',
                            'desc' => '',
                             "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_street_address_desc'),
                            'field_params' => array(
                                'std' => $wp_dp_map_address,
                                'id' => 'post_loc_address',
                                'classes' => 'wp-dp-search-location',
                                'extra_atr' => ' onkeypress="wp_dp_gl_search_map(this.value)" placeholder="Enter a location" autocomplete="off"',
                                'cust_id' => 'loc_address',
                                'return' => true,
                            ),
                        );

                        if ( isset($value['address_hint']) && $value['address_hint'] != '' ) {
                            $wp_dp_opt_array['hint_text'] = $value['address_hint'];
                            $wp_dp_opt_array['label_desc'] = isset($value['label_desc']) ? $value['label_desc'] : '';
                        }

                        if ( isset($value['split']) && $value['split'] <> '' ) {
                            $wp_dp_opt_array['split'] = $value['split'];
                        }

                        $output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

                        $wp_dp_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_latitude'),
                            'id' => 'post_loc_latitude',
                            'desc' => '',
                             "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_street_lat_desc'), 
                            'field_params' => array(
                                'std' => $wp_dp_map_latitude,
                                'id' => 'post_loc_latitude',
                                'classes' => 'gllpLatitude',
                                'return' => true,
                            ),
                        );

                        if ( isset($value['split']) && $value['split'] <> '' ) {
                            $wp_dp_opt_array['split'] = $value['split'];
                        }
                        $output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                        $wp_dp_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_longitude'),
                            'id' => 'post_loc_longitude',
                            'desc' => '',
                              "label_desc" => wp_dp_plugin_text_srt('wp_dp_plugin_options_street_long_desc'),
                            'field_params' => array(
                                'std' => $wp_dp_map_longitude,
                                'id' => 'post_loc_longitude',
                                'classes' => 'gllpLongitude',
                                'return' => true,
                            ),
                        );

                        if ( isset($value['split']) && $value['split'] <> '' ) {
                            $wp_dp_opt_array['split'] = $value['split'];
                        }

                        $output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);

                        $wp_dp_opt_array = array(
                            'name' => '',
                            'id' => 'map_search_btn',
                            'desc' => '',
                            'field_params' => array(
                                'std' => wp_dp_plugin_text_srt('wp_dp_options_location_on_map'),
                                'id' => 'map_t_op_search',
                                'cust_type' => 'button',
                                'classes' => 'gllpSearchButton',
                                'return' => true,
                            ),
                        );

                        if ( isset($value['split']) && $value['split'] <> '' ) {
                            $wp_dp_opt_array['split'] = $value['split'];
                        }
                        $output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                        $output .= $wp_dp_html_fields->wp_dp_full_opening_field(array());
                        $output .= '
                        <div class="clear"></div>';

                        $output .= '
						<div class="clear"></div>
						<div class="cs-map-section" style="float:left; width:100%; height:300px;">
							<div class="gllpMap" id="cs-map-location-id"></div>
						</div>';

                        $output .= $wp_dp_html_fields->wp_dp_closing_field(array( 'desc' => '' ));

                        $output .= '
								</div>
							</div>
						</div>
						</fieldset>';
                        $output .= '
						<script type="text/javascript">
							jQuery(document).ready(function() {
								function markerDragHandleEvent(event) {
									document.getElementById(\'wp_dp_post_loc_latitude\').value = event.latLng.lat();
									document.getElementById(\'wp_dp_post_loc_longitude\').value = event.latLng.lng();
								}
								function new_map_cred(newLat, newLng, map){
									newLat = parseFloat(newLat);
									newLng = parseFloat(newLng);
									var latlng = new google.maps.LatLng(newLat, newLng);
									var mapOptions = {
										zoom: 9,
										center: latlng,
										mapTypeId: google.maps.MapTypeId.ROADMAP
									}
									map = new google.maps.Map(document.getElementById(\'cs-map-location-id\'), mapOptions);
									var marker = new google.maps.Marker({
										position: new google.maps.LatLng(newLat, newLng),
										map: map,
										draggable: true
									});
									marker.addListener(\'drag\', markerDragHandleEvent);
									marker.addListener(\'dragend\', markerDragHandleEvent);
								}
								function map_initialize() {
									var mapCanvas = document.getElementById("cs-map-location-id");
									var mapOptions = {
										center: new google.maps.LatLng(' . $wp_dp_map_latitude . ', ' . $wp_dp_map_longitude . '), 
										zoom: ' . $wp_dp_map_zoom . '
									}
									var map = new google.maps.Map(mapCanvas, mapOptions);
									var marker = new google.maps.Marker({
										position: new google.maps.LatLng(' . $wp_dp_map_latitude . ', ' . $wp_dp_map_longitude . '),
										map: map,
										draggable: true,
										title: \'' . $wp_dp_map_address . '\'
									});
									marker.addListener(\'drag\', markerDragHandleEvent);
									marker.addListener(\'dragend\', markerDragHandleEvent);
									
									google.maps.event.addDomListener(document.getElementById(\'wp_dp_map_t_op_search\'), \'click\', function () {
										geocoder = new google.maps.Geocoder();
										var address = document.getElementById(\'loc_address\').value;
										if(address != ""){
											geocoder.geocode( { \'address\': address}, function(results, status) {
												if (status == google.maps.GeocoderStatus.OK) {
													var newLat = results[0].geometry.location.lat();
													var newLng = results[0].geometry.location.lng();
													document.getElementById(\'wp_dp_post_loc_latitude\').value = newLat;
													document.getElementById(\'wp_dp_post_loc_longitude\').value = newLng;
													new_map_cred(newLat, newLng, map);
												} else {
													console.log("Address is not correct.");
													return false;
												}
											});
										} else {
											var newLat = document.getElementById(\'wp_dp_post_loc_latitude\').value;
											var newLng = document.getElementById(\'wp_dp_post_loc_longitude\').value;
											new_map_cred(newLat, newLng, map);
										}
									});
								}
								google.maps.event.addDomListener(window, \'load\', map_initialize);
								
								var autocomplete;
								 (function ($) {
									$(function () {
										autocomplete = new google.maps.places.Autocomplete(document.getElementById(\'loc_address\'));
									});
								})(jQuery);
							});	
                        </script>';
                        break;

                    case "banner_fields":
                        $wp_dp_banner_rand_id = rand(23789, 534578930);
                        if ( isset($wp_dp_plugin_options) && $wp_dp_plugin_options <> '' ) {
                            if ( ! isset($wp_dp_plugin_options['wp_dp_banner_title']) ) {
                                $network_list = '';
                                $display = 'none';
                            } else {
                                $network_list = isset($wp_dp_plugin_options['wp_dp_banner_title']) ? $wp_dp_plugin_options['wp_dp_banner_title'] : '';
                                $banner_style = isset($wp_dp_plugin_options['wp_dp_banner_style']) ? $wp_dp_plugin_options['wp_dp_banner_style'] : '';
                                $banner_type = isset($wp_dp_plugin_options['wp_dp_banner_type']) ? $wp_dp_plugin_options['wp_dp_banner_type'] : '';
                                $banner_image = isset($wp_dp_plugin_options['wp_dp_banner_image_array']) ? $wp_dp_plugin_options['wp_dp_banner_image_array'] : '';
                                $banner_field_url = isset($wp_dp_plugin_options['wp_dp_banner_field_url']) ? $wp_dp_plugin_options['wp_dp_banner_field_url'] : '';
                                $banner_target = isset($wp_dp_plugin_options['wp_dp_banner_target']) ? $wp_dp_plugin_options['wp_dp_banner_target'] : '';
                                $adsense_code = isset($wp_dp_plugin_options['wp_dp_banner_adsense_code']) ? $wp_dp_plugin_options['wp_dp_banner_adsense_code'] : '';
                                $code_no = isset($wp_dp_plugin_options['wp_dp_banner_field_code_no']) ? $wp_dp_plugin_options['wp_dp_banner_field_code_no'] : '';
                                $display = 'block';
                            }
                        } else {
                            $val = isset($wp_dp_plugin_options['options']) ? $value['options'] : '';
                            $std = isset($wp_dp_plugin_options['id']) ? $value['id'] : '';
                            $display = 'block';
                            $network_list = isset($wp_dp_plugin_options['wp_dp_banner_title']) ? $wp_dp_plugin_options['wp_dp_banner_title'] : '';
                            $banner_style = isset($wp_dp_plugin_options['wp_dp_banner_style']) ? $wp_dp_plugin_options['wp_dp_banner_style'] : '';
                            $banner_type = isset($wp_dp_plugin_options['wp_dp_banner_type']) ? $wp_dp_plugin_options['wp_dp_banner_type'] : '';
                            $banner_image = isset($wp_dp_plugin_options['wp_dp_banner_image_array']) ? $wp_dp_plugin_options['wp_dp_banner_image_array'] : '';
                            $banner_field_url = isset($wp_dp_plugin_options['wp_dp_banner_field_url']) ? $wp_dp_plugin_options['wp_dp_banner_field_url'] : '';
                            $banner_target = isset($wp_dp_plugin_options['wp_dp_banner_target']) ? $wp_dp_plugin_options['wp_dp_banner_target'] : '';
                            $adsense_code = isset($wp_dp_plugin_options['wp_dp_banner_adsense_code']) ? $wp_dp_plugin_options['wp_dp_banner_adsense_code'] : '';
                            $code_no = isset($wp_dp_plugin_options['wp_dp_banner_field_code_no']) ? $wp_dp_plugin_options['wp_dp_banner_field_code_no'] : '';
                        }
                        $wp_dp_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_banner_title_field'),
                            'desc' => '',
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_banner_title_field_hint'),
                            'field_params' => array(
                                'std' => '',
                                'cust_id' => 'banner_title_input',
                                'cust_name' => 'banner_title_input',
                                'classes' => '',
                                'return' => true,
                            ),
                        );
                        $output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                        $wp_dp_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_banner_style'),
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_banner_style_hint'),
                            'field_params' => array(
                                'std' => '',
                                'desc' => '',
                                'cust_id' => "banner_style_input",
                                'cust_name' => 'banner_style_input',
                                'classes' => 'input-small chosen-select',
                                'options' =>
                                array(
                                    'top_banner' => wp_dp_plugin_text_srt('wp_dp_banner_type_top'),
                                    'bottom_banner' => wp_dp_plugin_text_srt('wp_dp_banner_type_bottom'),
                                    'sidebar_banner' => wp_dp_plugin_text_srt('wp_dp_banner_type_sidebar'),
                                    'vertical_banner' => wp_dp_plugin_text_srt('wp_dp_banner_type_vertical'),
                                    'listing_detail_banner' => wp_dp_plugin_text_srt('wp_dp_banner_type_listing_detail'),
                                    'listing_banner' => wp_dp_plugin_text_srt('wp_dp_banner_type_listing'),
                                    'listing_banner_leftfilter' => wp_dp_plugin_text_srt('wp_dp_banner_type_listing_leftfilter'),
                                    'member_banner' => wp_dp_plugin_text_srt('wp_dp_banner_type_member'),
                                ),
                                'return' => true,
                            ),
                        );
                        $output .= $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                        $wp_dp_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_banner_type'),
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_banner_type_hint'),
                            'field_params' => array(
                                'std' => '',
                                'desc' => '',
                                'cust_id' => "banner_type_input",
                                'cust_name' => 'banner_type_input',
                                'classes' => 'input-small chosen-select',
                                'extra_atr' => 'onchange="javascript:wp_dp_banner_type_toggle(this.value , \'' . $wp_dp_banner_rand_id . '\')"',
                                'options' =>
                                array(
                                    'image' => wp_dp_plugin_text_srt('wp_dp_banner_image'),
                                    'code' => wp_dp_plugin_text_srt('wp_dp_banner_adsense_code'),
                                ),
                                'return' => true,
                            ),
                        );
                        $output .= $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                        $output .='<div id="ads_image' . absint($wp_dp_banner_rand_id) . '">';
                        $wp_dp_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_banner_image'),
                            'id' => 'banner_field_image',
                            'std' => '',
                            'desc' => '',
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_banner_image_hint'),
                            'prefix' => '',
                            'field_params' => array(
                                'std' => '',
                                'id' => 'banner_field_image',
                                'prefix' => '',
                                'return' => true,
                            ),
                        );
                        $output .= $wp_dp_html_fields->wp_dp_upload_file_field($wp_dp_opt_array);
                        $wp_dp_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_banner_url_field'),
                            'desc' => '',
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_banner_url_hint'),
                            'field_params' => array(
                                'std' => '',
                                'cust_id' => 'banner_field_url_input',
                                'cust_name' => 'banner_field_url_input',
                                'classes' => '',
                                'return' => true,
                            ),
                        );
                        $output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                        $wp_dp_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_banner_target'),
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_banner_target_hint'),
                            'field_params' => array(
                                'std' => '',
                                'desc' => '',
                                'cust_id' => "banner_target_input",
                                'cust_name' => 'banner_target_input',
                                'classes' => 'input-small chosen-select',
                                'options' =>
                                array(
                                    '_self' => wp_dp_plugin_text_srt('wp_dp_banner_target_self'),
                                    '_blank' => wp_dp_plugin_text_srt('wp_dp_banner_target_blank'),
                                ),
                                'return' => true,
                            ),
                        );
                        $output .= $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                        $output .='</div>';

                        $output .='<div id="ads_code' . absint($wp_dp_banner_rand_id) . '" style="display:none">';
                        $wp_dp_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_banner_adsense_code'),
                            'desc' => '',
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_banner_adsense_code_hint'),
                            'field_params' => array(
                                'std' => '',
                                'cust_id' => 'adsense_code_input',
                                'cust_name' => 'adsense_code_input[]',
                                'classes' => '',
                                'return' => true,
                            ),
                        );
                        $output .= $wp_dp_html_fields->wp_dp_textarea_field($wp_dp_opt_array);
                        $output .='</div>';
                        $wp_dp_opt_array = array(
                            'name' => '&nbsp;',
                            'desc' => '',
                            'hint_text' => '',
                            'field_params' => array(
                                'std' => wp_dp_plugin_text_srt('wp_dp_options_add_banner'),
                                'id' => 'wp_dp_banner_add_banner',
                                'classes' => '',
                                'cust_type' => 'button',
                                'extra_atr' => 'onclick="javascript:wp_dp_banner_add_banner(\'' . admin_url("admin-ajax.php") . '\')"',
                                'return' => true,
                            ),
                        );

                        $output .= '<ul class="wp-dp-list-button-ul">
                        <li class="wp-dp-list-button">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"></div>
                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                <!--For Simple Input Element-->
                                <div class="input-element">
                                    <a href="javascript:void(0);" id="click-more" class="wp-dp-add-more cntrl-add-new-row add-banner-button" onclick="javascript:wp_dp_banner_add_banner(\'' . admin_url("admin-ajax.php") . '\')">' . wp_dp_plugin_text_srt('wp_dp_options_add_banner') . '</a>
                                </div>
                            </div>
                        </li>
                    </ul>';
                        $output .= '<div class="wp-dp-list-wrap banners-list-wrap-area wp-dp-parent-edit-wraper">
                            <ul class="wp-dp-list-layout">
                                <li class="wp-dp-list-label">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="element-label">
                                            <label>' . wp_dp_plugin_text_srt('wp_dp_banner_title_field') . '</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <div class="element-label">
                                            <label>' . wp_dp_plugin_text_srt('wp_dp_banner_style') . '</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <div class="element-label">
                                            <label>' . wp_dp_plugin_text_srt('wp_dp_banner_table_image') . '</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <div class="element-label">
                                            <label>' . wp_dp_plugin_text_srt('wp_dp_banner_table_clicks') . '</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="element-label">
                                            <label>' . wp_dp_plugin_text_srt('wp_dp_banner_table_shortcode') . '</label>
                                        </div>
                                    </div>
                                </li>';

                        $i = 0;
                        if ( is_array($network_list) ) {
                            foreach ( $network_list as $network ) {
                                $wp_dp_rand_num = rand(123456, 987654);
                                if ( isset($network_list[$i]) || isset($network_list[$i]) ) {
                                    $output .='<li class="wp-dp-list-item">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <!--For Simple Input Element-->
                                        <div class="input-element">
                                            <div class="input-holder">
                                                ' . esc_html($network_list[$i]) . '
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <!--For Simple Input Element-->
                                        <div class="input-element">
                                            <div class="input-holder">
                                                ' . esc_html($banner_style[$i]) . '
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <!--For Simple Input Element-->
                                        <div class="input-element">
                                            <div class="input-holder">';
                                    if ( isset($banner_image[$i]) && ! empty($banner_image[$i]) && $banner_type[$i] == 'image' ) {
                                        $img_url = wp_get_attachment_image_src($banner_image[$i]);
                                        $output .= '<div class="img-holder"><figure><img src="' . esc_url($img_url[0]) . '" alt="" height="70" /></figure></div>';
                                    } else {
                                        $output .= wp_dp_plugin_text_srt('wp_dp_banner_custom_code');
                                    }
                                    $output .='</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <!--For Simple Input Element-->
                                        <div class="input-element">
                                            <div class="input-holder">';
                                    if ( $banner_type[$i] == 'image' ) {
                                        $banner_click_count = get_option("banner_clicks_" . $code_no[$i]);
                                        $banner_click_count = $banner_click_count <> '' ? $banner_click_count : '0';
                                        $output .= $banner_click_count;
                                    } else {
                                        $output .= '&nbsp;';
                                    }
                                    $output .= '</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <!--For Simple Input Element-->
                                        <div class="input-element">
                                            <div class="input-holder">
                                                [wp_dp_ads id="' . $code_no[$i] . '"]
                                            </div>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);" class="wp-dp-dpove wp-dp-parent-li-dpove"><i class="icon-close2"></i></a>
                                    <a href="javascript:void(0);" class="wp-dp-edit wp-dp-parent-li-edit"><i class="icon-edit2"></i></a>';


                                    $output .= '<div class="parent-li-edit-div banner-style" id="' . absint($wp_dp_rand_num) . '" style="display:none">';
                                    $wp_dp_opt_array = array(
                                        'name' => wp_dp_plugin_text_srt('wp_dp_banner_title_field'),
                                        'desc' => '',
                                        'label_desc' => wp_dp_plugin_text_srt('wp_dp_banner_title_field_hint'),
                                        'field_params' => array(
                                            'std' => isset($network_list[$i]) ? $network_list[$i] : '',
                                            'cust_id' => 'banner_title',
                                            'cust_name' => 'wp_dp_banner_title[]',
                                            'classes' => '',
                                            'return' => true,
                                        ),
                                    );
                                    $output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                                    $wp_dp_opt_array = array(
                                        'name' => wp_dp_plugin_text_srt('wp_dp_banner_style'),
                                        'label_desc' => wp_dp_plugin_text_srt('wp_dp_banner_style_hint'),
                                        'field_params' => array(
                                            'std' => isset($banner_style[$i]) ? $banner_style[$i] : '',
                                            'cust_id' => 'banner_style',
                                            'cust_name' => 'wp_dp_banner_style[]',
                                            'desc' => '',
                                            'classes' => 'input-small chosen-select',
                                            'options' =>
                                            array(
                                                'top_banner' => wp_dp_plugin_text_srt('wp_dp_banner_type_top'),
                                                'bottom_banner' => wp_dp_plugin_text_srt('wp_dp_banner_type_bottom'),
                                                'sidebar_banner' => wp_dp_plugin_text_srt('wp_dp_banner_type_sidebar'),
                                                'vertical_banner' => wp_dp_plugin_text_srt('wp_dp_banner_type_vertical'),
                                                'listing_detail_banner' => wp_dp_plugin_text_srt('wp_dp_banner_type_listing_detail'),
                                                'listing_banner' => wp_dp_plugin_text_srt('wp_dp_banner_type_listing'),
                                                'listing_banner_leftfilter' => wp_dp_plugin_text_srt('wp_dp_banner_type_listing_leftfilter'),
                                                'member_banner' => wp_dp_plugin_text_srt('wp_dp_banner_type_member'),
                                            ),
                                            'return' => true,
                                        ),
                                    );
                                    $output .= $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                                    $wp_dp_opt_array = array(
                                        'name' => wp_dp_plugin_text_srt('wp_dp_banner_type'),
                                        'label_desc' => wp_dp_plugin_text_srt('wp_dp_banner_type_hint'),
                                        'field_params' => array(
                                            'std' => isset($banner_type[$i]) ? $banner_type[$i] : '',
                                            'cust_id' => 'banner_type',
                                            'cust_name' => 'wp_dp_banner_type[]',
                                            'desc' => '',
                                            'extra_atr' => 'onchange="javascript:wp_dp_banner_type_toggle(this.value , \'' . $wp_dp_rand_num . '\')"',
                                            'classes' => 'input-small chosen-select',
                                            'options' =>
                                            array(
                                                'image' => wp_dp_plugin_text_srt('wp_dp_banner_image'),
                                                'code' => wp_dp_plugin_text_srt('wp_dp_banner_adsense_code'),
                                            ),
                                            'return' => true,
                                        ),
                                    );
                                    $output .= $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                                    $display_ads = 'none';
                                    if ( $banner_type[$i] == 'image' ) {
                                        $display_ads = 'block';
                                    } elseif ( $banner_type[$i] == 'code' ) {
                                        $display_ads = 'none';
                                    }
                                    $output .='<div class="form-elements" id="ads_image' . absint($wp_dp_rand_num) . '" style="display:' . esc_html($display_ads) . '">';
                                    $wp_dp_opt_array = array(
                                        'name' => wp_dp_plugin_text_srt('wp_dp_banner_image'),
                                        'id' => 'banner_image',
                                        'std' => isset($banner_image[$i]) ? $banner_image[$i] : '',
                                        'desc' => '',
                                        'label_desc' => wp_dp_plugin_text_srt('wp_dp_banner_image_hint'),
                                        'prefix' => '',
                                        'form_element_wrapper' => false,
                                        'array' => true,
                                        'field_params' => array(
                                            'std' => isset($banner_image[$i]) ? $banner_image[$i] : '',
                                            'id' => 'banner_image',
                                            'prefix' => '',
                                            'array' => true,
                                            'return' => true,
                                        ),
                                    );

                                    $output .= $wp_dp_html_fields->wp_dp_upload_file_field($wp_dp_opt_array);
                                    $wp_dp_opt_array = array(
                                        'name' => wp_dp_plugin_text_srt('wp_dp_banner_url_field'),
                                        'desc' => '',
                                        'label_desc' => wp_dp_plugin_text_srt('wp_dp_banner_url_hint'),
                                        'field_params' => array(
                                            'std' => isset($banner_field_url[$i]) ? $banner_field_url[$i] : '',
                                            'cust_id' => 'banner_field_url',
                                            'cust_name' => 'wp_dp_banner_field_url[]',
                                            'classes' => '',
                                            'return' => true,
                                        ),
                                    );
                                    $output .= $wp_dp_html_fields->wp_dp_text_field($wp_dp_opt_array);
                                    $wp_dp_opt_array = array(
                                        'name' => wp_dp_plugin_text_srt('wp_dp_banner_target'),
                                        'label_desc' => wp_dp_plugin_text_srt('wp_dp_banner_target_hint'),
                                        'field_params' => array(
                                            'desc' => '',
                                            'std' => isset($banner_target[$i]) ? $banner_target[$i] : '',
                                            'cust_id' => 'banner_target',
                                            'cust_name' => 'wp_dp_banner_target[]',
                                            'classes' => 'input-small chosen-select',
                                            'options' =>
                                            array(
                                                '_self' => wp_dp_plugin_text_srt('wp_dp_banner_target_self'),
                                                '_blank' => wp_dp_plugin_text_srt('wp_dp_banner_target_blank'),
                                            ),
                                            'return' => true,
                                        ),
                                    );
                                    $output .= $wp_dp_html_fields->wp_dp_select_field($wp_dp_opt_array);
                                    $output .='</div>';
                                    $display_ads = 'none';
                                    if ( $banner_type[$i] == 'image' ) {
                                        $display_ads = 'none';
                                    } elseif ( $banner_type[$i] == 'code' ) {
                                        $display_ads = 'block';
                                    }
                                    $output .='<div id="ads_code' . absint($wp_dp_rand_num) . '" style="display:' . esc_html($display_ads) . '">';
                                    $wp_dp_opt_array = array(
                                        'name' => wp_dp_plugin_text_srt('wp_dp_banner_adsense_code'),
                                        'desc' => '',
                                        'label_desc' => wp_dp_plugin_text_srt('wp_dp_banner_adsense_code_hint'),
                                        'field_params' => array(
                                            'std' => isset($adsense_code[$i]) ? $adsense_code[$i] : '',
                                            'cust_id' => 'adsense_code',
                                            'cust_name' => 'wp_dp_banner_adsense_code[]',
                                            'classes' => '',
                                            'return' => true,
                                        ),
                                    );
                                    $output .= $wp_dp_html_fields->wp_dp_textarea_field($wp_dp_opt_array);
                                    $output .='</div>';

                                    $wp_dp_opt_array = array(
                                        'std' => isset($code_no[$i]) ? $code_no[$i] : '',
                                        'id' => 'banner_field_code_no',
                                        'cust_name' => 'wp_dp_banner_field_code_no[]',
                                        'return' => true,
                                    );
                                    $output .= $wp_dp_form_fields->wp_dp_form_hidden_render($wp_dp_opt_array);

                                    $output .= '
					    </div>
                                </li>';
                                }
                                $i ++;
                            }
                        }
                        $output .= '</ul></div>';
                        break;
                    case 'generate_backup':
                        global $wp_filesystem;

                        require_once ABSPATH . '/wp-admin/includes/file.php';

                        $backup_url = wp_nonce_url('edit.php?post_type=vehicles&page=wp_dp_settings');
                        if ( false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) ) ) {
                            return true;
                        }
                        if ( ! WP_Filesystem($creds) ) {
                            request_filesystem_credentials($backup_url, '', true, false, array());
                            return true;
                        }
                        $wp_dp_upload_dir = wp_dp::plugin_dir() . 'backend/settings/backups/';
                        $wp_dp_upload_dir_path = wp_dp::plugin_url() . 'backend/settings/backups/';
                        $wp_dp_all_list = $wp_filesystem->dirlist($wp_dp_upload_dir);
                        $output .= '<div class="backup_generates_area" data-ajaxurl="' . esc_url(admin_url('admin-ajax.php')) . '">';
                        $output .= '
                                    <div class="theme-help">
                                            <h4>' . wp_dp_plugin_text_srt('wp_dp_options_import_options') . '</h4>
                                    </div>';

                        $output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_file_url'),
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_options_file_url_hint'),
                                )
                        );
                        $output .= '<div  class="external_backup_areas">';
                        $wp_dp_opt_array = array(
                            'std' => '',
                            'cust_id' => "bkup_import_url",
                            'cust_name' => '',
                            'classes' => 'input-medium',
                            'return' => true,
                        );
                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                        $wp_dp_opt_array = array(
                            'std' => wp_dp_plugin_text_srt('wp_dp_options_import'),
                            'cust_id' => "cs-p-backup-url-restore",
                            'cust_name' => '',
                            'cust_type' => 'button',
                            'return' => true,
                        );
                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                        $output .= '</div>';
                        $output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                            'desc' => '',
                                )
                        );
                        $output .= '<div class="theme-help">
                                            <h4>' . wp_dp_plugin_text_srt('wp_dp_options_export_options') . '</h4>
                                    </div>';
                        $output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_generated_files'),
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_options_generated_files_hint'),
                                )
                        );
                        if ( is_array($wp_dp_all_list) && sizeof($wp_dp_all_list) > 0 ) {
                            $wp_dp_list_count = 1;
                            $bk_options = '';
                            $selected_file = '';
                            foreach ( $wp_dp_all_list as $file_key => $file_val ) {
                                if ( isset($file_val['name']) && strpos($file_val['name'], '.json') ) {
                                    $wp_dp_slected = sizeof($wp_dp_all_list) == $wp_dp_list_count ? ' selected="selected"' : '';
                                    $selected_file = $file_val['name'];
                                    $bk_options .= '<option' . $wp_dp_slected . '>' . $file_val['name'] . '</option>';
                                }
                                $wp_dp_list_count ++;
                            }
                            $wp_dp_opt_array = array(
                                'std' => wp_dp_plugin_text_srt('wp_dp_options_import'),
                                'cust_id' => "",
                                'cust_name' => '',
                                'classes' => 'input-medium chosen-select-no-single',
                                'extra_atr' => ' onchange="wp_dp_set_p_filename(this.value, \'' . esc_url($wp_dp_upload_dir_path) . '\')"',
                                'options_markup' => true,
                                'options' => $bk_options,
                                'return' => true,
                            );
                            $output .= $wp_dp_html_fields->wp_dp_form_select_render($wp_dp_opt_array);
                            $output .= '<div class="backup_action_btns">';
                            if ( $selected_file != '' ) {
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_options_restore'),
                                    'cust_id' => "cs-p-backup-restore",
                                    'cust_name' => '',
                                    'extra_atr' => ' data-file="' . $file_val['name'] . '"',
                                    'cust_type' => 'button',
                                    'return' => true,
                                );
                                $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                $output .= '<a download="' . $selected_file . '" href="' . esc_url($wp_dp_upload_dir_path . $selected_file) . '">' . wp_dp_plugin_text_srt('wp_dp_plugin_options_fields_download') . '</a>';
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_options_delete'),
                                    'cust_id' => "cs-p-backup-delte",
                                    'cust_name' => '',
                                    'extra_atr' => ' data-file="' . $selected_file . '"',
                                    'cust_type' => 'button',
                                    'return' => true,
                                );
                                $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                            }
                            $output .= '</div>';
                            $output .= '<div>&nbsp;</div>';
                        }
                        $wp_dp_opt_array = array(
                            'std' => wp_dp_plugin_text_srt('wp_dp_options_generated_backup'),
                            'cust_id' => "cs-p-bkp",
                            'cust_name' => '',
                            'extra_atr' => ' onclick="javascript:wp_dp_pl_opt_backup_generate(\'' . esc_js(admin_url('admin-ajax.php')) . '\');"',
                            'cust_type' => 'button',
                            'return' => true,
                        );
                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                        $output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                            'desc' => '',
                                )
                        );
                        $output .= '</div>';
                        break;
                    case 'backup_locations':
                        global $wp_filesystem;

                        require_once ABSPATH . '/wp-admin/includes/file.php';

                        $backup_url = wp_nonce_url('edit.php?post_type=vehicles&page=wp_dp_settings');
                        if ( false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) ) ) {
                            return true;
                        }
                        if ( ! WP_Filesystem($creds) ) {
                            request_filesystem_credentials($backup_url, '', true, false, array());
                            return true;
                        }
                        $wp_dp_upload_dir = wp_dp::plugin_dir() . 'backend/settings/backups/locations/';
                        $wp_dp_upload_dir_path = wp_dp::plugin_url() . 'backend/settings/backups/locations/';
                        $wp_dp_all_list = $wp_filesystem->dirlist($wp_dp_upload_dir);
                        $output .= '<div class="backup_locations_generates_area" data-ajaxurl="' . esc_url(admin_url('admin-ajax.php')) . '">';
                        $output .= '
                                    <div class="theme-help">
                                            <h4>' . wp_dp_plugin_text_srt('wp_dp_options_imp_exp_locations') . '</h4>
                                    </div>';

                        $output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_file_url'),
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_options_imp_exp_hint'),
                        ));

                        $output .= '<div  class="external_backup_areas">';
//                        $wp_dp_opt_array = array(
//                            'std' => '',
//                            'cust_id' => "bkup_locations_import_url",
//                            'cust_name' => '',
//                            'classes' => 'input-medium',
//                            'return' => true,
//                        );
//                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
						
						$wp_dp_opt_array = array(
                            'std' => wp_dp_plugin_text_srt('wp_dp_options_import_locations'),
                            'cust_id' => "choose_file",
                            'cust_name' => '',
                            'cust_type' => 'file',
                            'return' => true,
                        );
                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
						
						
																
						$wp_dp_opt_array = array(
//                            'std' => wp_dp_plugin_text_srt('wp_dp_options_import_locations'),
							'std' =>wp_dp_plugin_text_srt('wp_dp_options_choose_file_to_import_locations'),
//                            'cust_id' => "btn_import_locations_from_url",
                            'cust_id' => "btn_import_locations_from_url",							
                            'cust_name' => '',
                            'cust_type' => 'button',
                            'return' => true,
                        );
                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
						
						
                        $output .= '</div>';
                        $output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                            'desc' => '',
                        ));
                        $output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_generated_files'),
                            'hint_text' => '',
                        ));
                        if ( is_array($wp_dp_all_list) && count($wp_dp_all_list) > 0 ) {
                            $wp_dp_list_count = 1;
                            $bk_options = '';
                            foreach ( $wp_dp_all_list as $file_key => $file_val ) {
                                if ( isset($file_val['name']) ) {
                                    $wp_dp_slected = sizeof($wp_dp_all_list) == $wp_dp_list_count ? ' selected="selected"' : '';
                                    $bk_options .= '<option' . $wp_dp_slected . '>' . $file_val['name'] . '</option>';
                                }
                                $wp_dp_list_count ++;
                            }
                            $wp_dp_opt_array = array(
                                'std' => wp_dp_plugin_text_srt('wp_dp_options_import'),
                                'cust_id' => "slct_locations_backups",
                                'cust_name' => '',
                                'name' => '',
                                'classes' => 'input-medium chosen-select-no-single',
                                'extra_atr' => ' onchange="set_locations_backup_filename(this.value, \'' . esc_url($wp_dp_upload_dir_path) . '\')"',
                                'options_markup' => true,
                                'options' => $bk_options,
                                'return' => true,
                            );
                            $output .= $wp_dp_html_fields->wp_dp_form_select_render($wp_dp_opt_array);
                            $output .= '<div class="backup_action_btns">';
                            if ( isset($file_val['name']) ) {
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_options_restore'),
                                    'cust_id' => "btn_restore_locations_backup",
                                    'cust_name' => '',
                                    'extra_atr' => ' data-file="' . $file_val['name'] . '"',
                                    'cust_type' => 'button',
                                    'return' => true,
                                );
                                $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                $output .= '<a download="' . $file_val['name'] . '" href="' . esc_url($wp_dp_upload_dir_path . $file_val['name']) . '">' . wp_dp_plugin_text_srt('wp_dp_options_download') . '</a>';
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_options_delete'),
                                    'cust_id' => "btn_delete_locations_backup",
                                    'cust_name' => '',
                                    'extra_atr' => ' data-file="' . $file_val['name'] . '"',
                                    'cust_type' => 'button',
                                    'return' => true,
                                );
                                $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                            }
                            $output .= '</div>';
                            $output .= '<div>&nbsp;</div>';
                        }
                        $wp_dp_opt_array = array(
                            'std' => wp_dp_plugin_text_srt('wp_dp_options_generated_backup'),
                            'cust_id' => "btn_generate_locations_backup",
                            'cust_name' => '',
                            'extra_atr' => ' onclick="javascript:generate_locations_backup(\'' . esc_js(admin_url('admin-ajax.php')) . '\');"',
                            'cust_type' => 'button',
                            'return' => true,
                        );
                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                        $output .='<form method="post" id="wp_dp_import_form" action="" enctype="multipart/form-data">';
                        $wp_dp_opt_array = array(
                            'std' => wp_dp_plugin_text_srt('wp_dp_options_browse_file'),
                            'cust_id' => "btn_browse_locations_file",
                            'cust_name' => '',
                            'id' => "btn_browse_locations_file",
                            'cust_type' => 'file',
                            'return' => true,
                        );
                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                        $wp_dp_opt_array = array(
                            'std' => wp_dp_plugin_text_srt('wp_dp_options_import'),
                            'cust_id' => "btn_import_file",
                            'cust_name' => 'btn_import_file',
                            'cust_type' => 'button',
                            'return' => true,
                        );
                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                        $output .= '</form>';
                        $output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                            'desc' => '',
                        ));
                        $output .= '</div>';

                        break;
                    case 'backup_listing_type_categories':
                        global $wp_filesystem;

                        require_once ABSPATH . '/wp-admin/includes/file.php';

                        $backup_url = wp_nonce_url('edit.php?post_type=vehicles&page=wp_dp_settings');
                        if ( false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) ) ) {
                            return true;
                        }
                        if ( ! WP_Filesystem($creds) ) {
                            request_filesystem_credentials($backup_url, '', true, false, array());
                            return true;
                        }
                        $wp_dp_upload_dir = wp_dp::plugin_dir() . 'backend/settings/backups/listing-type-categories/';
                        $wp_dp_upload_dir_path = wp_dp::plugin_url() . 'backend/settings/backups/listing-type-categories/';
                        $wp_dp_all_list = $wp_filesystem->dirlist($wp_dp_upload_dir);
                        $output .= '<div class="backup_listing_type_categories_generates_area" data-ajaxurl="' . esc_url(admin_url('admin-ajax.php')) . '">';
                        $output .= '
                                    <div class="theme-help">
                                            <h4>' . wp_dp_plugin_text_srt('wp_dp_options_imp_exp_categories') . '</h4>
                                    </div>';

                        $output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_file_url'),
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_options_imp_exp_cat_hint'),
                        ));
                        $output .= '<div  class="external_backup_areas">';
                        $wp_dp_opt_array = array(
                            'std' => '',
                            'cust_id' => "bkup_listing_type_categories_import_url",
                            'cust_name' => '',
                            'classes' => 'input-medium',
                            'return' => true,
                        );
                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                        $wp_dp_opt_array = array(
                            'std' => wp_dp_plugin_text_srt('wp_dp_options_import_categories'),
                            'cust_id' => "btn_import_listing_type_categories_from_url",
                            'cust_name' => '',
                            'cust_type' => 'button',
                            'return' => true,
                        );
                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                        $output .= '</div>';
                        $output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                            'desc' => '',
                        ));
                        $output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_generated_files'),
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_options_backup_hint'),
                        ));
                        if ( is_array($wp_dp_all_list) && count($wp_dp_all_list) > 0 ) {
                            $wp_dp_list_count = 1;
                            $bk_options = '';
                            foreach ( $wp_dp_all_list as $file_key => $file_val ) {
                                if ( isset($file_val['name']) ) {
                                    $wp_dp_slected = sizeof($wp_dp_all_list) == $wp_dp_list_count ? ' selected="selected"' : '';
                                    $bk_options .= '<option' . $wp_dp_slected . '>' . $file_val['name'] . '</option>';
                                }
                                $wp_dp_list_count ++;
                            }
                            $wp_dp_opt_array = array(
                                'std' => wp_dp_plugin_text_srt('wp_dp_options_import'),
                                'cust_id' => "slct_listing_type_categories_backups",
                                'cust_name' => '',
                                'name' => '',
                                'classes' => 'input-medium chosen-select-no-single',
                                'extra_atr' => ' onchange="set_listing_type_categories_backup_filename(this.value, \'' . esc_url($wp_dp_upload_dir_path) . '\')"',
                                'options_markup' => true,
                                'options' => $bk_options,
                                'return' => true,
                            );
                            $output .= $wp_dp_html_fields->wp_dp_form_select_render($wp_dp_opt_array);
                            $output .= '<div class="backup_action_btns">';
                            if ( isset($file_val['name']) ) {
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_options_restore'),
                                    'cust_id' => "btn_restore_listing_type_categories_backup",
                                    'cust_name' => '',
                                    'extra_atr' => ' data-file="' . $file_val['name'] . '"',
                                    'cust_type' => 'button',
                                    'return' => true,
                                );
                                $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                $output .= '<a download="' . $file_val['name'] . '" href="' . esc_url($wp_dp_upload_dir_path . $file_val['name']) . '">' . wp_dp_plugin_text_srt('wp_dp_options_download') . '</a>';
                                $wp_dp_opt_array = array(
                                    'std' => wp_dp_plugin_text_srt('wp_dp_options_delete'),
                                    'cust_id' => "btn_delete_listing_type_categories_backup",
                                    'cust_name' => '',
                                    'extra_atr' => ' data-file="' . $file_val['name'] . '"',
                                    'cust_type' => 'button',
                                    'return' => true,
                                );
                                $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                            }
                            $output .= '</div>';
                            $output .= '<div>&nbsp;</div>';
                        }
                        $wp_dp_opt_array = array(
                            'std' => wp_dp_plugin_text_srt('wp_dp_options_generated_backup'),
                            'cust_id' => "btn_generate_listing_type_categories_backup",
                            'cust_name' => '',
                            'extra_atr' => ' onclick="javascript:generate_listing_type_categories_backup(\'' . esc_js(admin_url('admin-ajax.php')) . '\');"',
                            'cust_type' => 'button',
                            'return' => true,
                        );
                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                        $output .='<form method="post" id="wp_dp_import_categort_form" action="" enctype="multipart/form-data">';
                        $wp_dp_opt_array = array(
                            'std' => wp_dp_plugin_text_srt('wp_dp_options_browse_file'),
                            'cust_id' => "btn_browse_category_file",
                            'cust_name' => '',
                            'id' => "btn_browse_category_file",
                            'cust_type' => 'file',
                            'return' => true,
                        );
                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                        $wp_dp_opt_array = array(
                            'std' => wp_dp_plugin_text_srt('wp_dp_options_import'),
                            'cust_id' => "btn_import_cat_file",
                            'cust_name' => 'btn_import_cat_file',
                            'cust_type' => 'button',
                            'return' => true,
                        );
                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                        $output .= '</form>';
                        $output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                            'desc' => '',
                        ));
                        $output .= '</div>';
                        break;
                    case 'locations_level_selector':
                        $wp_dp_opt_array = array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_warning'),
                            'std' => wp_dp_plugin_text_srt('wp_dp_options_modfiying_warning'),
                            'id' => 'wp_dp_locations_levels_warning',
                        );
                        $output .= $wp_dp_html_fields->wp_dp_set_announcement($wp_dp_opt_array);
                        $output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_locations_levels'),
                            'hint_text' => '',
                        ));
                        $wp_dp_opt_array = array( 'name' => wp_dp_plugin_text_srt('wp_dp_options_locations_levels'),
                            'desc' => '',
                            'id' => 'locations_levels',
                            'cust_name' => 'wp_dp_locations_levels[]',
                            'std' => '',
                            'type' => 'select_values',
                            'classes' => 'chosen-select-no-single',
                            'extra_atr' => ' multiple disabled',
                            'options' => array(
                                'country' => wp_dp_plugin_text_srt('wp_dp_options_country'),
                                'state' => wp_dp_plugin_text_srt('wp_dp_options_state'),
                                'city' => wp_dp_plugin_text_srt('wp_dp_options_city'),
                                'town' => wp_dp_plugin_text_srt('wp_dp_options_town'),
                            ),
                            'return' => true,
                        );
                        $output .= $wp_dp_html_fields->wp_dp_form_select_render($wp_dp_opt_array);
                        $wp_dp_opt_array = array(
                            'std' => wp_dp_plugin_text_srt('wp_dp_options_edit_levels'),
                            'cust_id' => "wp_dp_edit_locations_levels",
                            'cust_name' => '',
                            'cust_type' => 'button',
                            'extra_atr' => ' style="margin-top: 10px;"',
                            'return' => true,
                        );
                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                        $output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                            'desc' => '',
                        ));
                        $locations_levels = array();
                        if ( isset($wp_dp_plugin_options['wp_dp_locations_levels']) ) {
                            $locations_levels = $wp_dp_plugin_options['wp_dp_locations_levels'];
                        }
                        ob_start();
                        ?>
                        <script type="text/javascript">
                            "use strict";
                            (function ($) {
                                $(function () {
                                    var locations_levels = <?php echo json_encode($locations_levels); ?>;
                                    var selecter_id = "#wp_dp_locations_levels";
                                    // Select locations levels.
                                    $(selecter_id + " option").each(function (key, elem) {
                                        var val = $(this).val();
                                        if ($.inArray(val, locations_levels) > -1) {
                                            $(this).prop('selected', true);
                                        } else {
                                            $(this).prop('selected', false);
                                        }
                                    });
                                    $(selecter_id).trigger("chosen:updated");

                                    $(selecter_id).prop('disabled', true).change(function () {
                                        // This is done for sorting of items in an order.
                                        $(selecter_id).trigger("chosen:updated");
                                    });
                                    $("#wp_dp_edit_locations_levels").click(function () {
                                        if (window.confirm("Warning!!!\nBy modifying location levels your existing locations data may get useless as you change levels. So, it is recommended to backup and delete existing locations. Do you still want to edit levels?")) {
                                            $(selecter_id).prop('disabled', false).trigger("chosen:updated");
                                        }
                                    });

                                    $("#plugin-options").on("submit", function () {
                                        $(selecter_id).prop('disabled', false);
                                        return true;
                                    });
                                });
                            })(jQuery);
                        </script>
                        <?php
                        $output .= ob_get_clean();
                        break;
                    case 'locations_fields_selector':
                        $output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_locations_selector'),
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_options_selector_hint'),
                        ));
                        $locations_levels = array();
                        $options = $tempOptions = array(
                            'country' => wp_dp_plugin_text_srt('wp_dp_options_country'),
                            'state' => wp_dp_plugin_text_srt('wp_dp_options_state'),
                            'city' => wp_dp_plugin_text_srt('wp_dp_options_city'),
                            'town' => wp_dp_plugin_text_srt('wp_dp_options_town'),
                        );
                        $locations_levels = array();
                        if ( isset($wp_dp_plugin_options['wp_dp_locations_levels']) ) {
                            $options = array();
                            $locations_levels = $wp_dp_plugin_options['wp_dp_locations_levels'];
                            foreach ( $locations_levels as $key => $val ) {
                                if ( isset($tempOptions[$val]) ) {
                                    $options[$val] = ucfirst($tempOptions[$val]);
                                }
                            }
                        }
                        $wp_dp_opt_array = array(
                            'name' => '',
                            'desc' => '',
                            'id' => $value['id'],
                            'cust_name' => $value['cust_name'],
                            'std' => '',
                            'type' => 'select_values',
                            'classes' => 'chosen-select-no-single',
                            'extra_atr' => ' multiple',
                            'options' => $options,
                            'return' => true,
                        );
                        $output .= $wp_dp_html_fields->wp_dp_form_select_render($wp_dp_opt_array);
                        $output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                            'desc' => '',
                        ));
                        /*
                         * Get data from plugin options to populate on frontend.
                         */
                        $frontend_location_parts = array();
                        if ( isset($wp_dp_plugin_options[$value['id']]) ) {
                            $frontend_location_parts = $wp_dp_plugin_options[$value['id']];
                        }
                        ob_start();
                        ?>
                        <script type="text/javascript">
                            "use strict";
                            (function ($) {
                                $(function () {
                                    var <?php echo ($value['id']); ?> = <?php echo json_encode($frontend_location_parts); ?>;
                                    $("#wp_dp_<?php echo ($value['id']); ?>").change(function () {
                                        // For sorting items in an order.
                                        $("#wp_dp_<?php echo ($value['id']); ?>").trigger("chosen:updated");
                                    });
                                    $("#wp_dp_<?php echo ($value['id']); ?> option").each(function (key, elem) {
                                        var val = $(this).val();
                                        if ($.inArray(val, <?php echo ($value['id']); ?>) > -1) {
                                            $(this).prop('selected', true);
                                        } else {
                                            $(this).prop('selected', false);
                                        }
                                    });
                                    $("#wp_dp_<?php echo ($value['id']); ?>").trigger("chosen:updated");
                                });
                            })(jQuery);
                        </script>
                        <?php
                        $output .= ob_get_clean();
                        break;
                    case 'locations_fields_for_search':
                        $output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_location_parts'),
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_options_location_parts_hint'),
                        ));
                        $locations_levels = array();
                        $options = $tempOptions = array(
                            'country' => wp_dp_plugin_text_srt('wp_dp_options_country'),
                            'state' => wp_dp_plugin_text_srt('wp_dp_options_state'),
                            'city' => wp_dp_plugin_text_srt('wp_dp_options_city'),
                            'town' => wp_dp_plugin_text_srt('wp_dp_options_town'),
                        );
                        $locations_levels = array();
                        if ( isset($wp_dp_plugin_options['wp_dp_locations_levels']) ) {
                            $options = array();
                            $locations_levels = $wp_dp_plugin_options['wp_dp_locations_levels'];
                            foreach ( $locations_levels as $key => $val ) {
                                if ( isset($tempOptions[$val]) ) {
                                    $options[$val] = ucfirst($tempOptions[$val]);
                                }
                            }
                        }
                        $wp_dp_opt_array = array(
                            'name' => '',
                            'desc' => '',
                            'id' => $value['id'],
                            'cust_name' => $value['cust_name'],
                            'std' => '',
                            'type' => 'select_values',
                            'classes' => 'chosen-select-no-single',
                            'extra_atr' => ' multiple',
                            'options' => $options,
                            'return' => true,
                        );
                        $output .= $wp_dp_html_fields->wp_dp_form_select_render($wp_dp_opt_array);
                        $output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                            'desc' => '',
                        ));
                        $output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_country'),
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_options_country_hint'),
                            'id' => $value['id'] . '_country_container',
                        ));
                        $output .= '<span style="display: none;" class="ajax-loader"><img src="' . wp_dp::plugin_url() . '/assets/images/ajax-loader.gif" /></span>';
                        $wp_dp_opt_array = array( 'name' => wp_dp_plugin_text_srt('wp_dp_options_country'),
                            'desc' => '',
                            'id' => $value['id'] . '_country',
                            'std' => '',
                            'type' => 'select_values',
                            'classes' => 'chosen-select-no-single',
                            'options' => array(),
                            'return' => true,
                        );
                        $output .= $wp_dp_html_fields->wp_dp_form_select_render($wp_dp_opt_array);
                        $output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                            'desc' => '',
                        ));
                        $output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_state'),
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_options_state_hint'),
                            'id' => $value['id'] . '_state_container',
                        ));
                        $output .= '<span style="display: none;" class="ajax-loader"><img src="' . wp_dp::plugin_url() . '/assets/images/ajax-loader.gif" /></span>';
                        $wp_dp_opt_array = array( 'name' => wp_dp_plugin_text_srt('wp_dp_options_state'),
                            'desc' => '',
                            'id' => $value['id'] . '_state',
                            'std' => '',
                            'type' => 'select_values',
                            'classes' => 'chosen-select-no-single',
                            'options' => array(),
                            'return' => true,
                        );
                        $output .= $wp_dp_html_fields->wp_dp_form_select_render($wp_dp_opt_array);
                        $output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                            'desc' => '',
                        ));
                        $output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_city'),
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_options_city_hint'),
                            'id' => $value['id'] . '_city_container',
                        ));
                        $output .= '<span style="display: none;" class="ajax-loader"><img src="' . wp_dp::plugin_url() . '/assets/images/ajax-loader.gif" /></span>';
                        $wp_dp_opt_array = array( 'name' => wp_dp_plugin_text_srt('wp_dp_options_city'),
                            'desc' => '',
                            'id' => $value['id'] . '_city',
                            'std' => '',
                            'type' => 'select_values',
                            'classes' => 'chosen-select-no-single',
                            'options' => array(),
                            'return' => true,
                        );
                        $output .= $wp_dp_html_fields->wp_dp_form_select_render($wp_dp_opt_array);
                        $output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                            'desc' => '',
                        ));
                        $output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_town'),
                            'label_desc' => wp_dp_plugin_text_srt('wp_dp_options_town_hint'),
                            'id' => $value['id'] . '_town_container',
                        ));
                        $output .= '<span style="display: none;" class="ajax-loader"><img src="' . wp_dp::plugin_url() . '/assets/images/ajax-loader.gif" /></span>';
                        $wp_dp_opt_array = array( 'name' => wp_dp_plugin_text_srt('wp_dp_options_town'),
                            'desc' => '',
                            'id' => $value['id'] . '_town',
                            'std' => '',
                            'type' => 'select_values',
                            'classes' => 'chosen-select-no-single',
                            'options' => array(),
                            'return' => true,
                        );
                        $output .= $wp_dp_html_fields->wp_dp_form_select_render($wp_dp_opt_array);
                        $output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                            'desc' => '',
                        ));

                        /*
                         * Get data from plugin options to populate on frontend.
                         */
                        $frontend_location_parts = array();
                        if ( isset($wp_dp_plugin_options[$value['id']]) ) {
                            $frontend_location_parts = $wp_dp_plugin_options[$value['id']];
                        }
                        $var_name_country = 'wp_dp_' . $value['id'] . '_country';
                        $$var_name_country = '';
                        if ( isset($wp_dp_plugin_options[$var_name_country]) ) {
                            $$var_name_country = $wp_dp_plugin_options[$var_name_country];
                        }
                        $var_name_state = 'wp_dp_' . $value['id'] . '_state';
                        $$var_name_state = '';
                        if ( isset($wp_dp_plugin_options[$var_name_state]) ) {
                            $$var_name_state = $wp_dp_plugin_options[$var_name_state];
                        }

                        $var_name_city = 'wp_dp_' . $value['id'] . '_city';
                        $$var_name_city = '';
                        if ( isset($wp_dp_plugin_options[$var_name_city]) ) {
                            $$var_name_city = $wp_dp_plugin_options[$var_name_city];
                        }
                        $var_name_town = 'wp_dp_' . $value['id'] . '_town';
                        $$var_name_town = '';
                        if ( isset($wp_dp_plugin_options[$var_name_town]) ) {
                            $$var_name_town = $wp_dp_plugin_options[$var_name_town];
                        }
                        ob_start();
                        ?>
                        <script type="text/javascript">
                            "use strict";
                            (function ($) {
                                $(function () {
                                    var <?php echo absint($value['id']); ?> = <?php echo json_encode($frontend_location_parts); ?>;
                                    var locations_levels = <?php echo json_encode($locations_levels); ?>;
                                    var pre_data = {
                        <?php echo esc_html($var_name_country); ?>_id: '<?php echo esc_html($$var_name_country); ?>',
                        <?php echo esc_html($var_name_state); ?>_id: '<?php echo esc_html($$var_name_state); ?>',
                        <?php echo esc_html($var_name_city); ?>_id: '<?php echo esc_html($$var_name_city); ?>',
                        <?php echo esc_html($var_name_town); ?>_id: '<?php echo esc_html($$var_name_town); ?>',
                                    };
                                    var loading_countries = false;

                                    var all_ids = "#<?php echo absint($value['id']); ?>_country_container, #<?php echo absint($value['id']); ?>_state_container, #<?php echo absint($value['id']); ?>_city_container, #<?php echo absint($value['id']); ?>_town_container";
                                    var select_ids = "#wp_dp_<?php echo absint($value['id']); ?>_country option[value='-'], #wp_dp_<?php echo absint($value['id']); ?>_state option[value='-'], #wp_dp_<?php echo absint($value['id']); ?>_city option[value='-'], #wp_dp_<?php echo absint($value['id']); ?>_town option[value='-']";

                                    /*
                                     * Following ugly logic show and hide (country, state, city, town 
                                     * containers) when '#wp_dp_frontend_location_parts changes.
                                     * 
                                     * If Town is selected then all four will be shown
                                     * If City is selected then City, State and Country will be shown
                                     * If State is selected then State and Country will be shown
                                     * If Country is selected then Country will be shown
                                     */
                                    var ids = {
                                        "country": {"country": "#<?php echo absint($value['id']); ?>_country_container"},
                                        "state": {"country": "#<?php echo absint($value['id']); ?>_country_container", "state": "#<?php echo absint($value['id']); ?>_state_container"},
                                        "city": {"country": "#<?php echo absint($value['id']); ?>_country_container", "state": "#<?php echo absint($value['id']); ?>_state_container", "city": "#<?php echo absint($value['id']); ?>_city_container"},
                                        "town": {"country": "#<?php echo absint($value['id']); ?>_country_container", "state": "#<?php echo absint($value['id']); ?>_state_container", "city": "#<?php echo absint($value['id']); ?>_city_container", "town": "#<?php echo absint($value['id']); ?>_town_container"},
                                    };
                                    // Hide all location parts selectors.
                                    $(join_obj(ids["town"])).hide();

                                    /*
                                     * Remove all those options which are not in location levels so that they does not shown.
                                     */
                                    if ($.inArray("country", locations_levels) < 0) {
                                        delete ids["country"]["country"];
                                        delete ids["state"]["country"];
                                        delete ids["city"]["country"];
                                        delete ids["town"]["country"];
                                    }
                                    if ($.inArray("state", locations_levels) < 0) {
                                        delete ids["state"]["state"];
                                        delete ids["city"]["state"];
                                        delete ids["town"]["state"];
                                    }
                                    if ($.inArray("city", locations_levels) < 0) {
                                        delete ids["city"]["city"];
                                        delete ids["town"]["city"];
                                    }
                                    if ($.inArray("town", locations_levels) < 0) {
                                        delete ids["town"]["town"];
                                    }

                                    /**
                                     * Make a call to load_locations_list() with respective data.
                                     *
                                     * @param string type
                                     * @param [string|int] selector
                                     */
                                    function load_locations_<?php echo absint($value['id']); ?>(type, selector) {
                                        "use strict";
                                        var item = <?php echo absint($value['id']); ?>[ <?php echo absint($value['id']); ?>.indexOf(type) + 1 ];
                                        if (locations_levels_indexes_<?php echo absint($value['id']); ?>[ item ] > -1 && selector != '-') {
                                            load_locations_list_<?php echo absint($value['id']); ?>(item, locations_levels_indexes_<?php echo absint($value['id']); ?>[ item ], selector);
                                        }
                                    }

                                    /*
                                     * Get a list of location parts from server and pass data to provided callback.
                                     *
                                     * @param string location_type
                                     * @param integer location_level
                                     * @param {string|integer} selector
                                     * @param function callback
                                     */
                                    function load_locations_list_<?php echo absint($value['id']); ?>(location_type, location_level, selector) {
                                        "use strict";
                                        $("#<?php echo absint($value['id']); ?>_" + location_type + "_container .ajax-loader").show();
                                        $.ajax({
                                            "url": "<?php echo admin_url('admin-ajax.php'); ?>",
                                            "data": {
                                                "action": "get_locations_list",
                                                "security": "<?php echo wp_create_nonce('get_locations_list'); ?>",
                                                "location_type": location_type,
                                                "location_level": location_level,
                                                "selector": selector,
                                            },
                                            "dataType": "json",
                                            "method": "post",
                                            "success": function (data) {
                                                populate_select_data_<?php echo absint($value['id']); ?>(data, {"location_type": location_type, "location_level": location_level, "selector": selector});
                                                $("#<?php echo absint($value['id']); ?>_" + location_type + "_container .ajax-loader").hide();
                                            }
                                        });
                                    }
                                    function populate_select_data_<?php echo absint($value['id']); ?>(data, params) {
                                        "use strict";
                                        if (data.error == true) {
                                            return;
                                        }
                                        var control_selector = "#wp_dp_<?php echo absint($value['id']); ?>_" + params.location_type;
                                        data = data.data;
                                        $(control_selector + ' option').remove();
                                        $(control_selector).append($("<option></option>").attr("value", '-').text('<?php echo wp_dp_plugin_text_srt('wp_dp_options_choose'); ?>'));
                                        $(control_selector).append($("<option></option>").attr("value", 'all').text('<?php echo wp_dp_plugin_text_srt('wp_dp_options_all'); ?>'));
                                        $.each(data, function (key, term) {
                                            $(control_selector).append($("<option></option>").attr("value", term.slug).text(term.name));
                                        });
                                        var selected_option_value = '-';
                                        if (params.selector != 'all') {
                                            selected_option_value = pre_data[ "<?php echo 'wp_dp_' . $value['id'] . '_'; ?>" + params.location_type + "_id" ];
                                        }
                                        $(control_selector + " option[value='" + selected_option_value + "']").prop("selected", true);
                                        $(control_selector).trigger("chosen:updated");
                                    }

                                    /*
                                     * Show/Hide locations parts selectors.
                                     *
                                     * @param {type} that
                                     */
                                    function handle_show_location_parts_selectors(that) {
                                        "use strict";
                                        $(join_obj(ids["town"])).hide();
                                        var idss = '';
                                        if ($("option[value='town']", that).is(":selected")) {
                                            idss = ids["town"];
                                        } else if ($("option[value='city']", that).is(":selected")) {
                                            idss = ids["city"];
                                        } else if ($("option[value='state']", that).is(":selected")) {
                                            idss = ids["state"];
                                        } else if ($("option[value='country']", that).is(":selected")) {
                                            idss = ids["country"];
                                        }
                                        console.log(join_obj(idss));
                                        if (idss != '') {
                                            $(join_obj(idss)).show();
                                            // Keep track of selected items and also show location parts selectors.
                        <?php echo absint($value['id']); ?> = $.map(idss, function (v, i) {
                                                return i;
                                            });
                                        }
                                    }

                                    function join_obj(obj) {
                                        "use strict";
                                        var arr = [];
                                        $.each(obj, function (key, val) {
                                            arr.push(val);
                                        });
                                        return arr.join(", ");
                                    }

                                    $("#wp_dp_<?php echo absint($value['id']); ?>").change(function () {
                                        // For sorting items in an order.
                                        $("#wp_dp_<?php echo absint($value['id']); ?>").trigger("chosen:updated");

                                        handle_show_location_parts_selectors($(this));
                                    });

                                    /*
                                     * Calculate locations levels indexes for backend.
                                     */
                                    var locations_levels_indexes_<?php echo absint($value['id']); ?> = {"country": -1, "state": -1, "city": -1, "town": -1};
                                    var locations_levels_index_counter = 0;
                                    $.each(locations_levels_indexes_<?php echo absint($value['id']); ?>, function (key, val) {
                                        if ($("#wp_dp_<?php echo absint($value['id']); ?> option[value='" + key + "']").length > 0) {
                                            locations_levels_indexes_<?php echo absint($value['id']); ?>[ key ] = locations_levels_index_counter;
                                            locations_levels_index_counter++;
                                        }
                                    });

                                    /*
                                     * Make already selected locations parts selected and update them in choosen.
                                     */
                                    $(all_ids).hide();
                                    $("#wp_dp_<?php echo absint($value['id']); ?> option").each(function (key, elem) {
                                        var val = $(this).val();
                                        if ($.inArray(val, <?php echo absint($value['id']); ?>) > -1) {
                                            $("#<?php echo absint($value['id']); ?>_" + val + "_container").show();

                                            if ("country" == val) {
                                                loading_countries = true;
                                            }

                                            var type = 'country';
                                            if (<?php echo absint($value['id']); ?>.indexOf(val) - 1 > -1) {
                                                type = <?php echo absint($value['id']); ?>[ <?php echo absint($value['id']); ?>.indexOf(val) - 1 ];
                                            }
                                            var variable_selector = "<?php echo 'wp_dp_' . $value['id'] . '_'; ?>" + type + "_id";
                                            var id = pre_data[ variable_selector ];
                                            load_locations_list_<?php echo absint($value['id']); ?>(val, locations_levels_indexes_<?php echo absint($value['id']); ?>[$(elem).val()], id);
                                            $(this).prop('selected', true);
                                        } else {
                                            $(this).prop('selected', false);
                                        }
                                    });
                                    $("#wp_dp_<?php echo absint($value['id']); ?>").trigger("chosen:updated");
                                    handle_show_location_parts_selectors($("#wp_dp_<?php echo absint($value['id']); ?>"));

                                    /*
                                     * Triggers for country, state, city or town change
                                     */
                                    $("#wp_dp_<?php echo absint($value['id']); ?>_country").change(function () {
                                        load_locations_<?php echo absint($value['id']); ?>('country', $(this).val());
                                    });
                                    $("#wp_dp_<?php echo absint($value['id']); ?>_state").change(function () {
                                        load_locations_<?php echo absint($value['id']); ?>('state', $(this).val());
                                    });
                                    $("#wp_dp_<?php echo absint($value['id']); ?>_city").change(function () {
                                        load_locations_<?php echo absint($value['id']); ?>('city', $(this).val());
                                    });
                                    $("#wp_dp_<?php echo absint($value['id']); ?>_town").change(function () {
                                        load_locations_<?php echo absint($value['id']); ?>('town', $(this).val());
                                    });

                                    // Preload data for countries.
                                    if (!loading_countries) {
                                        load_locations_list_<?php echo absint($value['id']); ?>('country', locations_levels_indexes_<?php echo absint($value['id']); ?>['country'], 'all');
                                    }
                                });
                            })(jQuery);
                        </script>
                        <?php
                        $output .= ob_get_clean();
                        break;
                    case 'user_import_export':
                        global $wp_filesystem;

                        require_once ABSPATH . '/wp-admin/includes/file.php';

                        if ( class_exists('wp_dp_user_import') ) {
                            $user_imp_exp = new wp_dp_user_import();
                            ob_start();
                            $user_imp_exp->wp_dp_import_user_form();
                            $output .= ob_get_clean();
                        }
						
						
                        $output .= '';

                        $output .= $wp_dp_html_fields->wp_dp_opening_field(array(
                            'name' => wp_dp_plugin_text_srt('wp_dp_options_file_url'),
                            'hint_text' => '',
                                )
                        );

                        $output .= '<div class="external_backup_areas">';
                        $wp_dp_opt_array = array(
                            'std' => '',
                            'cust_id' => "user_import_url",
                            'cust_name' => '',
                            'classes' => 'input-medium',
                            'return' => true,
                        );
                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                        $wp_dp_opt_array = array(
                            'std' => wp_dp_plugin_text_srt('wp_dp_options_import_users'),
                            'cust_id' => "cs-p-backup-url-restore",
                            'cust_name' => '',
                            'cust_type' => 'button',
                            'return' => true,
                        );
                        $output .= $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                        $output .= '</div>';

                        $output .= $wp_dp_html_fields->wp_dp_closing_field(array(
                            'desc' => '',
                                )
                        );
                        break;
						
						
                    case 'gallery_upload':
                        $wp_dp_opt_array = array(
                            'name' => $value['name'],
                            'desc' => $value['desc'],
                            'hint_text' => $value['hint_text'],
                            'label_desc' => isset($value['label_desc']) ? $value['label_desc'] : '',
                            'echo' => $value['echo'],
                            'id' => $value['id'],
                            'std' => '',
                            'field_params' => array(
                                'id' => $value['id'],
                                'return' => true,
                            ),
                        );
                        $output .= $wp_dp_html_fields->wp_dp_gallery_render_plugin_option($wp_dp_opt_array);
                        break;
                    case 'orders_inquiries_status':
                        global $post, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_static_text, $wp_dp_plugin_options;
                        ob_start();
                        $orders_status = isset($wp_dp_plugin_options['orders_status']) ? $wp_dp_plugin_options['orders_status'] : '';
                        $orders_color = isset($wp_dp_plugin_options['orders_color']) ? $wp_dp_plugin_options['orders_color'] : '';

                        if ( (is_array($orders_status) && sizeof($orders_status) <= 0) || $orders_status == '' ) {
                            $orders_status = array( 0 => 'Processing', 1 => 'Completed' );
                            $orders_color = array( 0 => '#ffba00', 1 => '#7ad03a' );
                        }
                        ?>

                        <div class="wp-dp-list-wrap order-statuse-layout-list">
                            <ul class="wp-dp-list-layout">
                                <li class="wp-dp-list-label">
                                    <div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">
                                        <div class="element-label">
                                            <label></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="element-label">
                                            <label><?php echo wp_dp_plugin_text_srt('wp_dp_options_status_color'); ?> </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                        <div class="element-label">
                                            <label><?php echo wp_dp_plugin_text_srt('wp_dp_options_status_label'); ?> </label>
                                        </div>
                                    </div>
                                </li>
                                <?php
                                $counter = 0;
                                if ( is_array($orders_status) && sizeof($orders_status) > 0 ) {
                                    foreach ( $orders_status as $key => $lable ) {
                                        if ( $lable == 'Processing' || $lable == 'Completed' ) {
                                            $readonly = 'readonly';
                                        } else {
                                            $readonly = '';
                                        }
                                        $order_color = isset($orders_color[$key]) ? $orders_color[$key] : '';
                                        if ( $lable == 'Processing' ) {
                                            $lable = wp_dp_plugin_text_srt('wp_dp_processing');
                                        }
                                        if ( $lable == 'Completed' ) {
                                            $lable = wp_dp_plugin_text_srt('wp_dp_completed');
                                        }
                                        ?>
                                        <li class="wp-dp-list-item tr_clone" id="repeat_element<?php echo absint($counter); ?>">
                                            <div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">
                                                <!--For Simple Input Element-->
                                                <div class="input-element">
                                                    <span class="cntrl-drag-and-drop"><i class="icon-menu2"></i></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                                <!--For Simple Input Element-->
                                                <div class="input-element">
                                                    <div class="input-holder">
                                                        <?php
                                                        $wp_dp_opt_array = array(
                                                            'std' => isset($order_color) ? esc_html($order_color) : '',
                                                            'cust_name' => 'orders_color[]',
                                                            'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_options_status_color') . '"',
                                                            'classes' => 'input-field bg_color',
                                                        );
                                                        $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                                <!--For Simple Input Element-->
                                                <div class="input-element">
                                                    <div class="input-holder">
                                                        <?php
                                                        $wp_dp_opt_array = array(
                                                            'std' => isset($lable) ? esc_html($lable) : '',
                                                            'cust_name' => 'orders_status[]',
                                                            'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_options_status_label') . '" ' . $readonly,
                                                            'classes' => 'input-field review_label',
                                                        );
                                                        $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if ( $lable != 'Processing' && $lable != 'Completed' ) { ?>
                                                <a href="javascript:void(0);" class="wp-dp-dpove wp-dp-parent-li-dpove"><i class="icon-close2"></i></a>
                                            <?php } ?>
                                        </li>
                                        <?php
                                        $counter ++;
                                    }
                                }
                                ?>
                            </ul>
                            <ul class="wp-dp-list-button-ul">
                                <li class="wp-dp-list-button">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <!--For Simple Input Element-->
                                        <div class="input-element">
                                            <a href="javascript:void(0);" id="click-more" class="wp-dp-add-more cntrl-add-new-row" onclick="duplicate()"><?php echo wp_dp_plugin_text_srt('wp_dp_orders_inquiries_add_status'); ?></a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <script type="text/javascript">
                            jQuery(document).ready(function () {
                                var table_class = ".order-statuse-layout-list .wp-dp-list-layout";
                                jQuery(table_class).sortable({
                                    cancel: "input, .wp-dp-list-label"
                                });
                            });
                            var counter_val = 1;
                            function duplicate() {
                                counter_val;
                                jQuery(".order-statuse-layout-list .wp-dp-list-layout").append(
                                        '<li class="wp-dp-list-item tr_clone" id="repeat_element49748535' + counter_val + '"><div class="col-lg-1 col-md-1 col-sm-6 col-xs-12"><div class="input-element"><span class="cntrl-drag-and-drop"><i class="icon-menu2"></i></span></div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="input-element"><div class="input-holder"><input type="text" class="input-field bg_color" value="#000000" name="orders_color[]"></div></div></div><div class="col-lg-8 col-md-8 col-sm-6 col-xs-12"><div class="input-element"><div class="input-holder"><input type="text" value="" name="orders_status[]" class="review_label input-field" placeholder="<?php echo wp_dp_plugin_text_srt('wp_dp_options_status_label'); ?>"></div></div></div><a href="javascript:void(0);" class="wp-dp-dpove wp-dp-parent-li-dpove"><i class="icon-close2"></i></a></li>'
                                        );


                                jQuery('.bg_color').wpColorPicker();
                                counter_val++;
                            }
                            jQuery(document).on('click', '.cntrl-delete-rows', function () {
                                delete_row_top(this);
                                return false;
                            });
                            function delete_row_top(delete_link) {
                                jQuery(delete_link).parent().parent().remove();

                            }
                        </script>
                        <?php
                        $content = ob_get_clean();
                        $output .= $content;
                        break;

                    case 'review_flags':
                        global $post, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_static_text, $wp_dp_plugin_options;
                        $review_flag_opts = isset($wp_dp_plugin_options['review_flag_opts']) ? $wp_dp_plugin_options['review_flag_opts'] : '';
                        ob_start();
                        ?>
                        <div class="wp-dp-list-wrap">
                            <ul class="wp-dp-list-layout">
                                <?php
                                $counter = 0;
                                if ( is_array($review_flag_opts) && sizeof($review_flag_opts) > 0 ) {
                                    foreach ( $review_flag_opts as $key => $label ) {
                                        $value = isset($values_array[$key]) ? $values_array[$key] : '';
                                        ?>
                                        <li class="wp-dp-list-item">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <!--For Simple Input Element-->
                                                <div class="input-element">
                                                    <div class="input-holder">
                                                        <?php
                                                        $wp_dp_opt_array = array(
                                                            'std' => esc_html($label),
                                                            'cust_name' => 'review_flag_opts[]',
                                                            'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_options_label') . '"',
                                                            'classes' => 'input-field wp-dp-dev-req-field-admin',
                                                        );
                                                        $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                                                        ?>
                                                    </div>
                                                </div>
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
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <!--For Simple Input Element-->
                                        <div class="input-element">
                                            <a href="javascript:void(0);" id="click-more" class="wp-dp-add-more cntrl-add-new-row add-review-type-opt"><?php echo wp_dp_plugin_text_srt('wp_dp_options_add_more'); ?></a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <?php
                        $output_content = ob_get_clean();
                        $output .= $output_content;
                        break;
                        $output .= '</div>';
                        $output .= '</tbody>
					</table></div></div>';
                }
                $output .= apply_filters('wp_dp_plugin_options_fields', $value);
                $output .= apply_filters('wp_dp_plugin_options_fields_promotions', $value);
            }
            $output .= '</div>';
            return array( $output, $menu );
        }

        /**
         * End Function  how to create Fields Settings
         */
        public function add_review_type_opt_callback() {
            global $post, $wp_dp_form_fields, $wp_dp_html_fields, $wp_dp_plugin_static_text, $wp_dp_plugin_options;
            ?>
            <li class="wp-dp-list-item">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <!--For Simple Input Element-->
                    <div class="input-element">
                        <div class="input-holder">
                            <?php
                            $wp_dp_opt_array = array(
                                'std' => '',
                                'cust_name' => 'review_flag_opts[]',
                                'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_options_label') . '"',
                                'classes' => 'input-field wp-dp-dev-req-field-admin',
                            );
                            $wp_dp_form_fields->wp_dp_form_text_render($wp_dp_opt_array);
                            ?>
                        </div>
                    </div>
                </div>
                <a href="javascript:void(0);" class="wp-dp-dpove wp-dp-parent-li-dpove"><i class="icon-close2"></i></a>
            </li>
            <?php
            wp_die();
        }

    }

    new wp_dp_options_fields();
}
