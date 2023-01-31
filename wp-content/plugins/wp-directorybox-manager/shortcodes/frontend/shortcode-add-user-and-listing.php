<?php
/**
 * Directory Box Register User and Add Listing Shortcode Frontend
 * 
 */
if ( ! class_exists('Wp_dp_Member_Register_User_And_Listing') ) {

    class Wp_dp_Member_Register_User_And_Listing {

        /**
         * Start construct Functions.
         */
        public function __construct() {
            add_shortcode('wp_dp_register_user_and_add_listing', array( $this, 'wp_dp_register_user_and_add_listing_shortcode' ));

            add_action('wp_dp_listing_custom_fields_cf', array( $this, 'listing_custom_fields' ), 10);

            add_action('wp_dp_assign_free_package_to_member', array( $this, 'assign_free_package_to_member' ), 10, 1);

            add_action('wp_ajax_user_and_listing_meta_save', array( $this, 'user_and_listing_meta_save_callback' ));
            add_action('wp_ajax_nopriv_user_and_listing_meta_save', array( $this, 'user_and_listing_meta_save_callback' ));

            add_action('wp_ajax_wp_dp_register_user_and_listing_load_cf', array( $this, 'wp_dp_register_user_and_listing_load_cf_callback' ));
            add_action('wp_ajax_nopriv_wp_dp_register_user_and_listing_load_cf', array( $this, 'wp_dp_register_user_and_listing_load_cf_callback' ));

            add_action('wp_ajax_wp_dp_listing_load_cf_cats', array( $this, 'custom_fields_features' ));
            add_action('wp_ajax_nopriv_wp_dp_listing_load_cf_cats', array( $this, 'custom_fields_features' ));

            add_action('wp_ajax_wp_dp_payment_gateways_package_selected', array( $this, 'wp_dp_payment_gateways_package_selected_callback' ));
            add_action('wp_ajax_nopriv_wp_dp_payment_gateways_package_selected', array( $this, 'wp_dp_payment_gateways_package_selected_callback' ));

            add_action('wp_ajax_wp_dp_create_listing_login', array( $this, 'create_listing_login_action' ));
            add_action('wp_ajax_nopriv_wp_dp_create_listing_login', array( $this, 'create_listing_login_action' ));

            add_action('wp_ajax_wp_dp_show_listing_pkg_info', array( $this, 'show_listing_pkg_info' ));
            add_action('wp_ajax_nopriv_wp_dp_show_listing_pkg_info', array( $this, 'show_listing_pkg_info' ));

            add_action('wp_ajax_wp_dp_show_type_packgs', array( $this, 'listing_packages' ));
            add_action('wp_ajax_nopriv_wp_dp_show_type_packgs', array( $this, 'listing_packages' ));

            add_action('wp_ajax_wp_dp_show_pkg_activation_msg', array( $this, 'listing_show_pkg_activation_msg' ));
            add_action('wp_ajax_nopriv_wp_dp_show_pkg_activation_msg', array( $this, 'listing_show_pkg_activation_msg' ));

            add_action('wp_ajax_wp_dp_check_user_avail', array( $this, 'wp_dp_check_user_avail' ));
            add_action('wp_ajax_nopriv_wp_dp_check_user_avail', array( $this, 'wp_dp_check_user_avail' ));

            add_filter('wp_dp_user_login_redirect_url', array( $this, 'user_login_redirect_url' ));
            add_filter('social_login_redirect_to', array( $this, 'user_login_redirect_url' ), 10, 1);
        }

        /**
         * Start Function for checking the Availability and Registration for User
         */
        public function wp_dp_check_user_avail() {
            $cs_json = array();
            $user_email = isset($_POST['user_email']) ? $_POST['user_email'] : '';
            $username = isset($_POST['user_username']) ? $_POST['user_username'] : '';
            $field_type = isset($_POST['field_type']) ? $_POST['field_type'] : '';
            $cs_error = false;
            $cs_json['type'] = 'success';

            if ( $field_type == 'email' ) {
                if ( email_exists($user_email) ) {
                    $cs_json['msg'] = 'Email Already in use.';
                    $cs_json['type'] = 'error';
                    $cs_error = true;
                } else {
                    $cs_json['msg'] = 'Email address is available.';
                    $cs_json['type'] = 'success';
                    $cs_error = false;
                }
            }

            if ( $field_type == 'username' ) {
                if ( username_exists($username) ) {
                    $cs_json['msg'] = 'Username Already in use.';
                    $cs_json['type'] = 'error';
                    $cs_error = true;
                } else {
                    $cs_json['msg'] = 'Username is available.';
                    $cs_json['type'] = 'success';
                    $cs_error = false;
                }
            }

            echo json_encode($cs_json);
            die;
        }

        /**
         * Save wp_dp listing
         * @return
         */
        public function user_and_listing_meta_save_callback() {
            global $current_user, $listing_add_counter;

            $get_listing_id = wp_dp_get_input('get_listing_id', 0);

            if ( $get_listing_id == '' || $get_listing_id == 0 ) {
                wp_dp_verify_term_condition_form_field('term_policy');
            }
            $response = array( 'status' => false, 'msg' => wp_dp_plugin_text_srt('wp_dp_listing_error_occured') );

            if ( $this->is_form_submit() ) {

                if ( is_user_logged_in() ) {
                    $is_updating = false;
                    if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                        $listing_id = $get_listing_id;
                        $is_updating = true;
                        $member_id = get_post_meta($listing_id, 'wp_dp_listing_member', true);
                        
                       
                        $wp_dp_listing_title = wp_dp_get_input('wp_dp_listing_title', '', 'STRING');
                        $wp_dp_listing_content = isset($_POST['wp_dp_listing_desc']) ? $_POST['wp_dp_listing_desc'] : '';
                        $listing_post = array(
                            'ID' => $listing_id,
                            'post_title' => $wp_dp_listing_title,
                            'post_content' => $wp_dp_listing_content,
                        );
                        wp_update_post($listing_post);
                    } else {
                        $company_id = wp_dp_company_id_form_user_id($current_user->ID);
                        $member_id = $company_id;
                        $listing_id = $this->listing_insert($member_id);
                        $listing_post = array(
                            'ID' => $listing_id,
                            'post_date' => current_time('Y/m/d H:i:s', 1),
                            'post_date_gmt' => current_time('Y/m/d H:i:s', 1),
                        );
                        wp_update_post($listing_post);
                    }

                    $publish_user_id = $current_user->ID;
                } else {
                    $is_updating = false;
                    $member_id = '';
                    $listing_id = '';
                    $get_username = wp_dp_get_input('wp_dp_user', '', 'STRING');
                    $get_useremail = wp_dp_get_input('wp_dp_user_email', '', 'STRING');
                    $reg_array = array(
                        'username' => $get_username,
                        'display_name' => $get_username,
                        'company_name' => $get_username,
                        'email' => $get_useremail,
                        'id' => $listing_add_counter,
                        'wp_dp_user_role_type' => 'member',
                        'key' => '',
                    );
                    $member_data = wp_dp_registration_validation('', $reg_array);

                    if ( isset($member_data['type']) && $member_data['type'] == 'error' ) {
                        $response['status'] = false;
                        $response['msg'] = $member_data['msg'];
                    } else {
                        $member_id = isset($member_data[0]) ? $member_data[0] : '';
                        $publish_user_id = isset($member_data[1]) ? $member_data[1] : '';

                        ajax_login(array( 'user_login' => $get_username ));

                        $listing_id = $this->listing_insert($member_id);
                        $listing_post = array(
                            'ID' => $listing_id,
                            'post_date' => current_time('Y/m/d H:i:s', 1),
                            'post_date_gmt' => current_time('Y/m/d H:i:s', 1),
                        );
                        wp_update_post($listing_post);
                    }
                }

                if ( $is_updating == false ) {
                    update_post_meta($listing_id, 'wp_dp_listing_status', 'awaiting-activation');
                }

                if ( $listing_id != '' && $publish_user_id != '' && $member_id != '' ) {

                    if ( ! $is_updating ) {
                        // saving Listing posted date
                        update_post_meta($listing_id, 'wp_dp_listing_posted', strtotime(current_time('Y/m/d H:i:s', 1)));
                    }

                    // Saving Listing Member
                    update_post_meta($listing_id, 'wp_dp_listing_member', $member_id);
                    update_post_meta($listing_id, 'wp_dp_listing_username', $publish_user_id);

                    // updating company id
                    $company_id = get_user_meta($member_id, 'wp_dp_company', true);
                    update_post_meta($listing_id, 'wp_dp_listing_company', $company_id);

                    $listing_featured_image_id = '';
                    $wp_dp_listing_featured_image_id = isset($_POST['wp_dp_listing_featured_image_id']) ? $_POST['wp_dp_listing_featured_image_id'] : '';
                    $listing_featured_image = isset($_FILES['wp_dp_listing_featured_image']) ? $_FILES['wp_dp_listing_featured_image'] : '';
                    if ( $wp_dp_listing_featured_image_id != '' ) {
                        $listing_featured_image_id = $wp_dp_listing_featured_image_id;
                    } else if ( $listing_featured_image != '' && ! is_numeric($listing_featured_image) && ! empty($listing_featured_image) ) {
                        $gallery_media_upload = $this->listing_gallery_upload('wp_dp_listing_featured_image', $listing_featured_image);
                        $listing_featured_image_id = isset($gallery_media_upload[0]) ? $gallery_media_upload[0] : '';
                    }
                    if ( $listing_featured_image_id != '' && is_numeric($listing_featured_image_id) ) {
                        set_post_thumbnail($listing_id, $listing_featured_image_id);
                        $img_url = wp_get_attachment_url($listing_featured_image_id);
                        update_post_meta($listing_id, 'wp_dp_cover_image', $img_url);
                    } else {
                        delete_post_thumbnail($listing_id);
                        update_post_meta($listing_id, 'wp_dp_cover_image', '');
                    }

                    // member status
                    update_post_meta($listing_id, 'listing_member_status', 'active');
                    // Saving video cover image
                    $listing_featured_image_id = '';
                    $wp_dp_listing_featured_image_id = isset($_POST['wp_dp_listing_image_id']) ? $_POST['wp_dp_listing_image_id'] : '';
                    $listing_featured_image = isset($_FILES['wp_dp_listing_image']) ? $_FILES['wp_dp_listing_image'] : '';
                    if ( $wp_dp_listing_featured_image_id != '' ) {
                        $listing_featured_image_id = $wp_dp_listing_featured_image_id;
                    } else if ( $listing_featured_image != '' && ! is_numeric($listing_featured_image) && ! empty($listing_featured_image) ) {
                        $gallery_media_upload = $this->listing_gallery_upload('wp_dp_listing_image', $listing_featured_image);
                        $listing_featured_image_id = isset($gallery_media_upload[0]) ? $gallery_media_upload[0] : '';
                    }
                    if ( $listing_featured_image_id != '' && is_numeric($listing_featured_image_id) ) {
                        update_post_meta($listing_id, 'wp_dp_listing_image', $listing_featured_image_id);
                    } else {
                        delete_post_thumbnail($listing_id);
                        update_post_meta($listing_id, 'wp_dp_listing_image', '');
                    }

                    //  Saving Listing Gallery 
                    $listing_gal_array = array();

                    if ( isset($_FILES['wp_dp_listing_gallery_images']) && ! empty($_FILES['wp_dp_listing_gallery_images']) ) {
                        $gallery_media_upload = $this->listing_gallery_upload('wp_dp_listing_gallery_images');
                        if ( is_array($gallery_media_upload) ) {
                            $listing_gal_array = array_merge($listing_gal_array, $gallery_media_upload);
                        }
                    }
                    $wp_dp_listing_gallery_items = wp_dp_get_input('wp_dp_listing_gallery_item', '', 'ARRAY');
                    if ( is_array($wp_dp_listing_gallery_items) && sizeof($wp_dp_listing_gallery_items) > 0 ) {
                        $listing_gal_array = array_merge($listing_gal_array, $wp_dp_listing_gallery_items);
                    }
                    update_post_meta($listing_id, 'wp_dp_detail_page_gallery_ids', $listing_gal_array);
                    // saving floor plans
                    $listing_floor_array = array();
                    if ( isset($_FILES['wp_dp_listing_floor_images']) && ! empty($_FILES['wp_dp_listing_floor_images']) ) {
                        $floor_media_upload = $this->listing_gallery_upload('wp_dp_listing_floor_images');
                        if ( is_array($floor_media_upload) ) {
                            $listing_floor_array = array_merge($listing_floor_array, $floor_media_upload);
                        }
                    }
                    $wp_dp_listing_floor_plan_title = wp_dp_get_input('wp_dp_listing_floor_plan_title', '', 'ARRAY');
                    $wp_dp_listing_floor_plan_image = wp_dp_get_input('wp_dp_listing_floor_plan_image', '', 'ARRAY');
                    if ( is_array($wp_dp_listing_floor_plan_image) && sizeof($wp_dp_listing_floor_plan_image) > 0 ) {
                        $listing_floor_array = array_merge($listing_floor_array, $wp_dp_listing_floor_plan_image);
                    }
                    $wp_dp_listing_floor_plan_desc = wp_dp_get_input('wp_dp_listing_floor_plan_desc', '', 'ARRAY');
                    if ( is_array($listing_floor_array) && sizeof($listing_floor_array) > 0 ) {
                        $floor_plans_array = array();
                        foreach ( $listing_floor_array as $key => $floor_plan ) {

                            if ( count($floor_plan) > 0 ) {
                                $floor_plans_array[] = array(
                                    'floor_plan_title' => isset($wp_dp_listing_floor_plan_title[$key]) ? $wp_dp_listing_floor_plan_title[$key] : '',
                                    'floor_plan_description' => isset($wp_dp_listing_floor_plan_desc[$key]) ? $wp_dp_listing_floor_plan_desc[$key] : '',
                                    'floor_plan_image' => $floor_plan,
                                );
                            }
                        }
                        update_post_meta($listing_id, 'wp_dp_floor_plans', $floor_plans_array);
                    }

                    // saving attachments.
                    $listing_attach_array = array();
                    if ( isset($_FILES['wp_dp_listing_attachment_images']) && ! empty($_FILES['wp_dp_listing_attachment_images']) ) {
                        $attach_media_upload = $this->listing_attach_file_upload('wp_dp_listing_attachment_images');
                        if ( is_array($attach_media_upload) ) {
                            $listing_attach_array = array_merge($listing_attach_array, $attach_media_upload);
                        }
                    }
                    $wp_dp_listing_attachment_title = wp_dp_get_input('wp_dp_listing_attachment_title', '', 'ARRAY');
                    $wp_dp_listing_attachment_file = wp_dp_get_input('wp_dp_listing_attachment_file', '', 'ARRAY');
                    if ( is_array($wp_dp_listing_attachment_file) && sizeof($wp_dp_listing_attachment_file) > 0 ) {
                        $listing_attach_array = array_merge($listing_attach_array, $wp_dp_listing_attachment_file);
                    }
                    if ( is_array($listing_attach_array) && sizeof($listing_attach_array) > 0 ) {
                        $attachments_array = array();
                        foreach ( $listing_attach_array as $key => $attachment ) {
                            if (is_array($attachment) && count($attachment) > 0 ) {
                                $attachments_array[] = array(
                                    'attachment_title' => isset($wp_dp_listing_attachment_title[$key]) ? $wp_dp_listing_attachment_title[$key] : '',
                                    'attachment_file' => $attachment,
                                );
                            }
                        }
                        update_post_meta($listing_id, 'wp_dp_attachments', $attachments_array);
                    }
                    // end saving attachments.
                    // saving apartment by
                    $wp_dp_listing_apartment_plot = wp_dp_get_input('wp_dp_listing_apartment_plot', '', 'ARRAY');
                    $wp_dp_listing_apartment_beds = wp_dp_get_input('wp_dp_listing_apartment_beds', '', 'ARRAY');
                    $wp_dp_listing_apartment_price_from = wp_dp_get_input('wp_dp_listing_apartment_price_from', '', 'ARRAY');
                    $wp_dp_listing_apartment_floor = wp_dp_get_input('wp_dp_listing_apartment_floor', '', 'ARRAY');
                    $wp_dp_listing_apartment_address = wp_dp_get_input('wp_dp_listing_apartment_address', '', 'ARRAY');
                    $wp_dp_listing_apartment_availability = wp_dp_get_input('wp_dp_listing_apartment_availability', '', 'ARRAY');
                    $wp_dp_listing_apartment_link = wp_dp_get_input('wp_dp_listing_apartment_link', '', 'ARRAY');

                    if ( is_array($wp_dp_listing_apartment_plot) && sizeof($wp_dp_listing_apartment_plot) > 0 ) {
                        $apartment_array = array();
                        foreach ( $wp_dp_listing_apartment_plot as $key => $apartment ) {
                            if ( count($apartment) > 0 ) {
                                $apartment_array[] = array(
                                    'apartment_plot' => $apartment,
                                    'apartment_beds' => isset($wp_dp_listing_apartment_beds[$key]) ? $wp_dp_listing_apartment_beds[$key] : '',
                                    'apartment_price_from' => isset($wp_dp_listing_apartment_price_from[$key]) ? $wp_dp_listing_apartment_price_from[$key] : '',
                                    'apartment_floor' => isset($wp_dp_listing_apartment_floor[$key]) ? $wp_dp_listing_apartment_floor[$key] : '',
                                    'apartment_address' => isset($wp_dp_listing_apartment_address[$key]) ? $wp_dp_listing_apartment_address[$key] : '',
                                    'apartment_availability' => isset($wp_dp_listing_apartment_availability[$key]) ? $wp_dp_listing_apartment_availability[$key] : '',
                                    'apartment_link' => isset($wp_dp_listing_apartment_link[$key]) ? $wp_dp_listing_apartment_link[$key] : '',
                                );
                            }
                        }
                        update_post_meta($listing_id, 'wp_dp_apartment', $apartment_array);
                    }

                    // updating company id
                    $company_id = get_user_meta($member_id, 'wp_dp_company', true);
                    update_post_meta($listing_id, 'wp_dp_listing_company', $company_id);
                    // saving Listing Type
                    $wp_dp_listing_type = isset($_REQUEST['wp_dp_listing_type']) ? $_REQUEST['wp_dp_listing_type'] : '';
                    update_post_meta($listing_id, 'wp_dp_listing_type', $wp_dp_listing_type);
                    // saving Listing video
                    $wp_dp_listing_video = wp_dp_get_input('wp_dp_listing_video', '', 'RAW');
                    update_post_meta($listing_id, 'wp_dp_listing_video', $wp_dp_listing_video);
                    // saving Listing virtual tour
                    $wp_dp_listing_virtual_tour = wp_dp_get_input('wp_dp_listing_virtual_tour', '', 'STRING');
                    update_post_meta($listing_id, 'wp_dp_listing_virtual_tour', $wp_dp_listing_virtual_tour);


                    do_action('wp_dp_photos_epc_tab_save', $listing_id, $_POST);

                    // saving Custom Fields
                    // all dynamic fields
                    $wp_dp_cus_fields = wp_dp_get_input('wp_dp_cus_field', '', 'ARRAY');
                    if ( is_array($wp_dp_cus_fields) && sizeof($wp_dp_cus_fields) > 0 ) {
                        foreach ( $wp_dp_cus_fields as $c_key => $c_val ) {
                            update_post_meta($listing_id, $c_key, $c_val);
                        }
                    }

                    // price save

                    $listing_type_post = get_posts(array( 'fields' => 'ids', 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$wp_dp_listing_type", 'post_status' => 'publish', 'suppress_filters' => '0' ));
                    $listing_type_id = isset($listing_type_post[0]) && $listing_type_post[0] != '' ? $listing_type_post[0] : 0;
                    $wp_dp_listing_type_price = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
                    $wp_dp_listing_type_price = isset($wp_dp_listing_type_price) && $wp_dp_listing_type_price != '' ? $wp_dp_listing_type_price : 'off';

                    $html = '';
                    $wp_dp_listing_price_options = wp_dp_get_input('wp_dp_listing_price_options', 'STRING');
                    $wp_dp_listing_price = wp_dp_get_input('wp_dp_listing_price', 'STRING');


                    $price_type = wp_dp_get_input('wp_dp_price_type', 'STRING');
                    update_post_meta($listing_id, 'wp_dp_price_type', $price_type);

                    update_post_meta($listing_id, 'wp_dp_listing_price_options', $wp_dp_listing_price_options);
                    update_post_meta($listing_id, 'wp_dp_listing_price', $wp_dp_listing_price);

                    $wp_dp_listing_special_price = wp_dp_get_input('wp_dp_listing_special_price', 'STRING');
                    update_post_meta($listing_id, 'wp_dp_listing_special_price', $wp_dp_listing_special_price);



                    $wp_dp_listing_price_oncall_num = isset($_REQUEST['wp_dp_listing_oncall_number']) ? $_REQUEST['wp_dp_listing_oncall_number'] : '';
                    if ( $wp_dp_listing_price_oncall_num != '' ) {
                        update_post_meta($member_id, 'wp_dp_phone_number', $wp_dp_listing_price_oncall_num);
                    }
                    // end price save

                    $listing_cats_formate = 'single';

                    $wp_dp_listing_category_array = wp_dp_get_input('wp_dp_listing_category', '', 'ARRAY');
                    if ( ! empty($wp_dp_listing_category_array) && is_array($wp_dp_listing_category_array) ) {
                        foreach ( $wp_dp_listing_category_array as $cate_slug => $cat_val ) {

                            if ( $cat_val ) {
                                $term = get_term_by('slug', $cat_val, 'listing-category');

                                if ( isset($term->term_id) ) {
                                    $cat_ids = array();
                                    $cat_ids[] = $term->term_id;
                                    $cat_slugs = $term->slug;
                                    wp_set_post_terms($listing_id, $cat_ids, 'listing-category', FALSE);
                                }
                            }
                        }

                        update_post_meta($listing_id, 'wp_dp_listing_category', $wp_dp_listing_category_array);
                    }

                    // adding listing tags
                    $new_pkg_check = wp_dp_get_input('wp_dp_listing_new_package_used', '');
                    if ( $new_pkg_check == 'on' ) {
                        $get_package_id = wp_dp_get_input('wp_dp_listing_package', '');
                    } else {
                        $active_package_key = wp_dp_get_input('wp_dp_listing_active_package', '');
                        $active_package_key = explode('pt_', $active_package_key);
                        $get_package_id = isset($active_package_key[0]) ? $active_package_key[0] : '';
                    }

                    if ( $get_package_id == '' ) {
                        $get_package_id = get_post_meta($listing_id, 'wp_dp_listing_package', true);
                    }
                    $trans_id = $this->listing_trans_id($listing_id);
                    if ( $trans_id > 0 && $this->wp_dp_is_pkg_subscribed($get_package_id, $trans_id) ) {
                        $tags_limit = get_post_meta($trans_id, 'wp_dp_transaction_listing_tags_num', true);
                    } else {
                        $wp_dp_pckg_data = get_post_meta($get_package_id, 'wp_dp_package_data', true);
                        $tags_limit = isset($wp_dp_pckg_data['number_of_tags']['value']) ? $wp_dp_pckg_data['number_of_tags']['value'] : '';
                    }

                    $wp_dp_listing_tags = wp_dp_get_input('listing_tags', '', 'ARRAY');
                    if ( ! empty($wp_dp_listing_tags) && is_array($wp_dp_listing_tags) ) {
//                        if ( $tags_limit && $tags_limit > 0 ) {
//                            $wp_dp_listing_tags = array_slice($wp_dp_listing_tags, 0, $tags_limit, true);
//                        }
                        wp_set_post_terms($listing_id, $wp_dp_listing_tags, 'listing-tag', FALSE);
						
						
						$listing_tags_terms = array();
						$terms = wp_get_post_terms( $listing_id, 'listing-tag' );
						if(isset( $terms )){
							foreach( $terms as $term ){
								$listing_tags_terms[] = $term->slug;
							}
						}
                        update_post_meta($listing_id, 'wp_dp_listing_tags', $listing_tags_terms);
                    }

                    // saving listing features
                    $wp_dp_listing_features = wp_dp_get_input('wp_dp_listing_feature', '', 'ARRAY');
                    update_post_meta($listing_id, 'wp_dp_listing_feature_list', $wp_dp_listing_features);

                    // saving location fields
                    $wp_dp_listing_country = wp_dp_get_input('wp_dp_post_loc_country_listing', '', 'STRING');
                    $wp_dp_listing_state = wp_dp_get_input('wp_dp_post_loc_state_listing', '', 'STRING');
                    $wp_dp_listing_city = wp_dp_get_input('wp_dp_post_loc_city_listing', '', 'STRING');
                    $wp_dp_listing_town = wp_dp_get_input('wp_dp_post_loc_town_listing', '', 'STRING');
                    $wp_dp_listing_loc_addr = wp_dp_get_input('wp_dp_post_loc_address_listing', '', 'STRING');
                    $wp_dp_listing_loc_lat = wp_dp_get_input('wp_dp_post_loc_latitude_listing', '', 'STRING');
                    $wp_dp_listing_loc_long = wp_dp_get_input('wp_dp_post_loc_longitude_listing', '', 'STRING');
                    $wp_dp_listing_loc_zoom = wp_dp_get_input('wp_dp_post_loc_zoom_listing', '', 'STRING');
                    $wp_dp_listing_loc_radius = wp_dp_get_input('wp_dp_loc_radius_listing', '', 'STRING');
                    $wp_dp_add_new_loc = wp_dp_get_input('wp_dp_add_new_loc_listing', '', 'STRING');
                    $wp_dp_loc_bounds_rest = wp_dp_get_input('wp_dp_loc_bounds_rest_listing', '', 'STRING');

                    update_post_meta($listing_id, 'wp_dp_post_loc_country_listing', $wp_dp_listing_country);
                    update_post_meta($listing_id, 'wp_dp_post_loc_state_listing', $wp_dp_listing_state);
                    update_post_meta($listing_id, 'wp_dp_post_loc_city_listing', $wp_dp_listing_city);
                    update_post_meta($listing_id, 'wp_dp_post_loc_town_listing', $wp_dp_listing_town);
                    update_post_meta($listing_id, 'wp_dp_post_loc_address_listing', $wp_dp_listing_loc_addr);
                    update_post_meta($listing_id, 'wp_dp_post_loc_latitude_listing', $wp_dp_listing_loc_lat);
                    update_post_meta($listing_id, 'wp_dp_post_loc_longitude_listing', $wp_dp_listing_loc_long);
                    update_post_meta($listing_id, 'wp_dp_post_loc_zoom_listing', $wp_dp_listing_loc_zoom);
                    update_post_meta($listing_id, 'wp_dp_loc_radius_listing', $wp_dp_listing_loc_radius);
                    update_post_meta($listing_id, 'wp_dp_add_new_loc_listing', $wp_dp_add_new_loc);
                    update_post_meta($listing_id, 'wp_dp_loc_bounds_rest_listing', $wp_dp_loc_bounds_rest);

                    $wp_dp_data = array();
                    $wp_dp_data['wp_dp_post_loc_country_listing'] = $wp_dp_listing_country;
                    $wp_dp_data['wp_dp_post_loc_state_listing'] = $wp_dp_listing_state;
                    $wp_dp_data['wp_dp_post_loc_city_listing'] = $wp_dp_listing_city;
                    $wp_dp_data['wp_dp_post_loc_town_listing'] = $wp_dp_listing_town;
                    update_post_meta($listing_id, 'wp_dp_array_data', $wp_dp_data);

                    // saving opening hours
                    $wp_dp_opening_hours = wp_dp_get_input('wp_dp_opening_hour', '', 'ARRAY');
                    update_post_meta($listing_id, 'wp_dp_opening_hours', $wp_dp_opening_hours);

                    // saving book off days
                    $wp_dp_off_days = wp_dp_get_input('wp_dp_listing_off_days', '', 'ARRAY');
                    update_post_meta($listing_id, 'wp_dp_calendar', $wp_dp_off_days);

                    $is_listing_expired = get_post_meta($listing_id, 'wp_dp_listing_expired', true);
                    if ( $is_updating == false || ($is_updating == true && $is_listing_expired < strtotime(date('Y-m-d'))) ) {
                        $response1 = $this->listing_save_assignments($listing_id, $member_id);
                        if ( $response1['status'] == true ) {
                            $response['status'] = true;
                            $response['msg'] = wp_dp_plugin_text_srt('wp_dp_user_listing_success');
                        }
                    } else {
                        $response['status'] = true;
                        $response['msg'] = wp_dp_plugin_text_srt('wp_dp_user_listing_success');
                    }
                }

                if ( $is_updating == false ) {
                    $user_data = wp_get_current_user();
                    do_action('wp_dp_listing_add_email', $user_data, $listing_id);
                } else {
                    do_action('wp_dp_listing_updated_on_front', $listing_id);
                }
            }

            echo json_encode($response);
            wp_die();
        }

        public function create_listing_login_action() {

            $listing_type = isset($_POST['login_listing_type']) && $_POST['login_listing_type'] != 'undefined' ? $_POST['login_listing_type'] : '';
            $listing_categ = isset($_POST['login_listing_categ']) && $_POST['login_listing_categ'] != 'undefined' ? $_POST['login_listing_categ'] : '';
            $listing_subcateg = isset($_POST['login_listing_sub_categ']) && $_POST['login_listing_sub_categ'] != 'undefined' ? $_POST['login_listing_sub_categ'] : '';
            $listing_pckge = isset($_POST['login_listing_pkge']) && $_POST['login_listing_pkge'] != 'undefined' ? $_POST['login_listing_pkge'] : '';
            $final_value = array(
                'create_listing' => 'yes',
                'type' => $listing_type,
                'category' => $listing_categ,
                'sub_category' => $listing_subcateg,
                'package' => $listing_pckge,
            );
            $final_value = json_encode($final_value);
            if ( isset($_COOKIE['wp_dp_was_create_listing']) ) {
                unset($_COOKIE['wp_dp_was_create_listing']);
                setcookie('wp_dp_was_create_listing', null, -1, '/');
                setcookie('wp_dp_was_create_listing', $final_value, time() + 1800, '/');
            } else {
                setcookie('wp_dp_was_create_listing', $final_value, time() + 1800, '/');
            }
            die;
        }

        public function user_login_redirect_url($redierct_url = '') {
            if ( isset($_COOKIE['wp_dp_was_create_listing']) && ! empty($_COOKIE['wp_dp_was_create_listing']) ) {
                global $wp_dp_plugin_options;
                $wp_dp_create_listing_page = isset($wp_dp_plugin_options['wp_dp_create_listing_page']) ? $wp_dp_plugin_options['wp_dp_create_listing_page'] : '';
                $redierct_url = esc_url(get_permalink($wp_dp_create_listing_page));
            }
            return $redierct_url;
        }

        public function wp_dp_register_user_and_add_listing_shortcode($atts, $content = "") {

            $html = $this->render_shortcode_ui(array( 'wp_dp_add_listing_seperator_style' => '', 'wp_dp_add_listing_element_title_color' => '', 'wp_dp_add_listing_element_subtitle_color' => '', 'listing_title' => '', 'wp_dp_element_logo' => '', 'listing_subtitle' => '', 'listing_title_alignment' => '', ), $atts);

            return $html;
        }

        public function render_shortcode_ui($defaults, $atts) {
            global $wp_dp_plugin_options, $listing_add_counter, $post, $wp_dp_form_fields_frontend;
            extract(shortcode_atts($defaults, $atts));

            $page_id = isset( $post->ID )? $post->ID : 0;

            ob_start();
            $page_element_size = isset($atts['wp_dp_register_user_and_add_listing_element_size']) ? $atts['wp_dp_register_user_and_add_listing_element_size'] : 100;
            if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
                echo '<div class="' . wp_dp_cs_var_page_builder_element_sizes($page_element_size, $atts) . ' ">';
            }

            $listing_add_counter = rand(10000000, 99999999);
            $wp_dp_id = wp_dp_get_input('listing_id', 0);
            $selected_package_id = wp_dp_get_input('package_id', 0);

            wp_enqueue_script('wp-dp-listing-user-add');
            //editor
            wp_enqueue_style('jquery-te');
            wp_enqueue_script('jquery-te');
            wp_enqueue_script('jquery-ui');
            //iconpicker
            wp_enqueue_style('fonticonpicker');
            wp_enqueue_script('fonticonpicker');
            wp_enqueue_style('datetimepicker');
            wp_enqueue_script('datetimepicker');

            $icons_groups = get_option('cs_icons_groups');
            if ( ! empty($icons_groups) ) {
                foreach ( $icons_groups as $icon_key => $icon_obj ) {
                    if ( isset($icon_obj['status']) && $icon_obj['status'] == 'on' ) {
                        wp_enqueue_style('cs_icons_data_css_' . $icon_key, $icon_obj['url'] . '/style.css');
                    }
                }
            }

            $get_listing_id = wp_dp_get_input('listing_id', 0);
            $is_updating = false;
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
            }
            ?>
            <div class="user-account-holder loader-holder">
                <?php
                if ( $wp_dp_element_logo != '' ) {
                    $wp_dp_element_logo = wp_get_attachment_url($wp_dp_element_logo);
                    echo '<div class="listing-add-elem-logo"><figure><a href="' . home_url('/') . '"><img src="' . $wp_dp_element_logo . '" alt=""></a></figure></div>';
                }
                ?>

                <?php if ( $wp_dp_id == 0 && $selected_package_id == 0 ) { ?>
                    <div class="alert-message"><?php echo wp_dp_plugin_text_srt('wp_dp_cant_direct_access'); ?></div>
                <?php } else { ?>
                    <div id="wp-dp-dev-posting-main-<?php echo absint($listing_add_counter); ?>" class="user-holder create-listing-holder" data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" data-plugin-url="<?php echo esc_url(wp_dp::plugin_url()); ?>">
                        <?php
                        ob_start();
                        // Check if it is form save request.
                        $output = ob_get_clean();

                        $wp_dp_free_listings_switch = isset($wp_dp_plugin_options['wp_dp_free_listings_switch']) ? $wp_dp_plugin_options['wp_dp_free_listings_switch'] : 'off';
                        $wp_dp_opening_hours_switch = isset($wp_dp_plugin_options['wp_dp_opening_hours_switch']) ? $wp_dp_plugin_options['wp_dp_opening_hours_switch'] : 'on';

                        $activation_process = ( isset($_GET['tab']) && isset($_GET['tab']) == 'activation' ) ? ' processing' : '';
                        $active_class = ( isset($_GET['tab']) && isset($_GET['tab']) == 'activation' ) ? ' class="active' . $activation_process . '"' : '';
                        $active_processing_class = ( isset($_GET['tab']) && isset($_GET['tab']) == 'activation' ) ? ' class="active"' : '';
                        $processing = ( isset($_GET['tab']) && isset($_GET['tab']) != 'activation' ) ? ' processing' : '';

                        $activeation_tab_active = ( isset($_GET['tab']) && isset($_GET['tab']) == 'activation' ) ? 'true' : 'false';
                        

                        if ( $activeation_tab_active != 'true' ) {
                            ?>
                            <ul class="listing-settings-nav progressbar-nav" data-listing="<?php echo absint($wp_dp_id) ?>" data-mcounter="<?php echo absint($listing_add_counter) ?>" style="display: none;">
                                <li class="active <?php echo wp_dp_cs_allow_special_char($processing); ?>" data-act="listing-information"><a href="javascript:void(0);" class="cond-listing-settings1" data-act="listing-information"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_listing_type'); ?></a></li>
                                <li<?php echo (isset($_COOKIE['wp_dp_was_create_listing']) && $_COOKIE['wp_dp_was_create_listing'] != '' && is_user_logged_in() ? ' class="active processing"' : $active_processing_class); ?> data-act="listing-detail-info"><a href="javascript:void(0);" class="cond-listing-settings1" data-act="listing-detail-info"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_listing_detail'); ?></a></li>
                                <li<?php echo wp_dp_cs_allow_special_char($active_processing_class); ?> data-act="advance-options"><a href="javascript:void(0);" class="cond-listing-settings1" data-act="advance-options"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_dec_and_price'); ?></a></li>
                                <li<?php echo wp_dp_cs_allow_special_char($active_processing_class); ?> data-act="loc-address"><a href="javascript:void(0);" class="cond-listing-settings1" data-act="loc-address"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_location'); ?></a></li>
                                <li<?php echo wp_dp_cs_allow_special_char($active_processing_class); ?> data-act="listing-photos"><a href="javascript:void(0);" class="cond-listing-settings1" data-act="listing-photos"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_photos_and_epc'); ?></a></li>
                                <?php if ( $wp_dp_free_listings_switch != 'on' && $is_updating === false ) { ?>
                                    <li<?php echo wp_dp_cs_allow_special_char($active_processing_class); ?> data-act="package"><a href="javascript:void(0);" class="cond-listing-settings1" data-act="package"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_review'); ?></a></li>
                                    <?php
                                } else {
                                    ?>
                                    <li<?php echo wp_dp_cs_allow_special_char($active_processing_class); ?> data-act="package"><a href="javascript:void(0);" class="cond-listing-settings1" data-act="package"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_update'); ?></a></li>
                                    <?php
                                }
                                ?>
                            </ul>

                            <?php
                        }
                        $listing_tab = isset($_GET['listing_tab']) ? $_GET['listing_tab'] : '';
                        ?>
                        <div id="listing-sets-holder" class="user-add-listing" data-doing="<?php echo ($is_updating === true ? 'updating' : 'creating') ?>">

                            <form id="wp-dp-dev-listing-form-<?php echo absint($listing_add_counter); ?>" name="wp-dp-dev-listing-form" class="form-fields-set wp-dp-dev-listing-form" data-id="<?php echo absint($listing_add_counter); ?>" method="post" enctype="multipart/form-data">
                                <?php
                                $tab_display = 'none';
                                $package_tab_display = 'none';
                                if ( isset($_COOKIE['wp_dp_was_create_listing']) && $_COOKIE['wp_dp_was_create_listing'] != '' && is_user_logged_in() ) {
                                    $tab_display = 'block';
                                }

                                if ( isset($_GET['package_id']) && $_GET['package_id'] > 0 ) {
                                    $tab_display = 'block';
                                }
                                if ( $is_updating === true ) {
                                    $tab_display = 'block';
                                }

                                if ( $activeation_tab_active != 'true' ) {

                                    if ( $is_updating == false ) {
                                        
                                    }

                                    $this->listing_add_tag_before('listing-detail-info-tab-container', $tab_display);
                                    $this->listing_show_advance_options(1, $atts);
                                    $this->listing_show_loc_address(1);
                                    if( $wp_dp_opening_hours_switch == 'on'){
                                        $this->listing_show_working_days(1);
                                    }

                                    if ( $is_updating == true ) {
                                        $this->listing_user_register_fields(1);
                                    } else {
                                        $this->listing_user_register_fields(1);
                                    }
                                    $this->listing_add_tag_after();

                                    if ( $wp_dp_free_listings_switch != 'on' ) {
                                        $this->listing_add_tag_before('listing-package-info-tab-container', $package_tab_display);
                                        $this->listing_show_set_membership(1);
                                        $this->listing_add_tag_after();
                                    }
                                }
                                $wp_dp_opt_array = array(
                                    'std' => 'user_and_listing_meta_save',
                                    'cust_id' => 'action',
                                    'cust_name' => 'action',
                                    'cust_type' => 'hidden',
                                    'classes' => '',
                                );
                                $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                ?>
                            </form>
                            <div class="payment-redirecting-process"><div class="payment-process-form-container"></div></div>
                            <?php
                            if ( $wp_dp_free_listings_switch != 'on' ) {
                                
                            }

                            if ( isset($_COOKIE['wp_dp_was_create_listing']) && is_user_logged_in() ) {
                                unset($_COOKIE['wp_dp_was_create_listing']);
                                setcookie('wp_dp_was_create_listing', null, -1, '/');
                            }

                            if ( $activeation_tab_active == 'true' ) {
                                $this->listing_show_activation_tab();
                            }
                            ?>
                        </div>
                        <script type="text/javascript">
                            jQuery(document).ready(function ($) {
                                add_event_listners({
                                    'package_required_error': '<?php echo wp_dp_plugin_text_srt('wp_dp_listing_select_package'); ?>',
                                    'processing_request': '<?php echo wp_dp_plugin_text_srt('wp_dp_listing_processing'); ?>',
                                    'is_listing_posting_free': '<?php echo ($wp_dp_free_listings_switch); ?>',
                                }, $);
                            });
                        </script>
                    </div>
                <?php } ?>
            </div>
            <?php
            if ( function_exists('wp_dp_cs_var_page_builder_element_sizes') ) {
                echo '</div>';
            }
            return force_balance_tags(ob_get_clean());
        }

        public function listing_show_pkg_activation_msg() {
            ob_start();
            $this->listing_show_activation_tab();
            $html = ob_get_clean();

            echo json_encode(array( 'html' => $html ));
            die;
        }

        public function expired_listing_show_set_settings($die_ret = '') {
            global $listing_add_counter, $wp_dp_plugin_options, $wp_dp_form_fields_frontend;

            $listing_add_counter = isset($_POST['_main_counter']) ? $_POST['_main_counter'] : $listing_add_counter;

            $get_listing_id = wp_dp_get_input('listing_id', 0);
            $is_updating = false;
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
            }



            ob_start();
            $listing_status = get_post_meta($get_listing_id, 'wp_dp_listing_status', true);
            $listing_post_on = get_post_meta($get_listing_id, 'wp_dp_listing_posted', true);
            $listing_post_expiry = get_post_meta($get_listing_id, 'wp_dp_listing_expired', true);
            $listing_post_expiry_date = date('d-m-Y', $listing_post_expiry);
            $listing_post_on_date = date('d-m-Y', $listing_post_on);

            if ( $listing_status == '' ) {
                $listing_status = 'pending';
            }

            $tab_display = 'none';
            if ( strtotime($listing_post_expiry_date) >= strtotime($listing_post_on_date) && strtotime($listing_post_expiry_date) <= strtotime(current_time('d-m-Y', 1)) ) {
                //set display block
            }
            if ( $listing_status == 'pending' ) {
                //set display block
            }


            if ( $is_updating === true ) {
                $submit_title = wp_dp_plugin_text_srt('wp_dp_listing_update');
            } else {
                if ( isset($package_info['display']) && $package_info['display'] == false ) {
                    $submit_title = wp_dp_plugin_text_srt('wp_dp_dp_listing_save_preview');
                }
            }
            ?>
            <li class="user-register-fields">
                <ul id="listing-membership-info-main" class="membership-info-main">
                    <?php $this->purchased_listing_packages(); ?>
                    <li>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="payment-holder">
                                    <div class="dashboard-left-btns">
                                        <div class="next-btn-field wp-dp-listing-submit-process">
                                            <div class="wp-dp-listing-submit-loader">
                                                <?php
                                                $wp_dp_opt_array = array(
                                                    'std' => $submit_title,
                                                    'cust_id' => 'register-listing-order',
                                                    'cust_name' => 'next-btn',
                                                    'cust_type' => 'submit',
                                                    'classes' => 'next-btn',
                                                );
                                                $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </li>
            <?php
            //$this->listing_add_tag_after();

            $html = ob_get_clean();

            if ( $die_ret == 1 ) {
                echo force_balance_tags($html);
            } else {
                echo json_encode(array( 'html' => $html ));
                die;
            }
        }

        /**
         * Load Purchased Packages and Payment
         * @return markup
         */
        public function purchased_listing_packages() {
            global $wp_dp_plugin_options, $listing_add_counter, $wp_dp_form_fields_frontend;

            $html = '';
            $selected_type_id = 0;
            $is_updating = false;
            $listing_up_visi = 'none';

            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
                $listing_up_visi = 'block';
                $selected_type = get_post_meta($get_listing_id, 'wp_dp_listing_type', true);
                $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$selected_type", 'post_status' => 'publish', 'suppress_filters' => '0' ));
                $selected_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;

                $listing_status = get_post_meta($get_listing_id, 'wp_dp_listing_status', true);
                $listing_post_on = get_post_meta($get_listing_id, 'wp_dp_listing_posted', true);
                $listing_post_expiry = get_post_meta($get_listing_id, 'wp_dp_listing_expired', true);
                $listing_post_expiry_date = date('d-m-Y', $listing_post_expiry);
                $listing_post_on_date = date('d-m-Y', $listing_post_on);
            }

            $wp_dp_free_listings_switch = isset($wp_dp_plugin_options['wp_dp_free_listings_switch']) ? $wp_dp_plugin_options['wp_dp_free_listings_switch'] : '';


            if ( $wp_dp_free_listings_switch != 'on' ) {

                // subscribed packages list
                $subscribed_active_pkgs = $this->listing_user_subscribed_packages();

                // Packages
                $packages_list = '';

                $cust_query = get_post_meta($selected_type_id, 'wp_dp_listing_type_packages', true);
                if ( empty($cust_query) ) {
                    $args = array(
                        'post_type' => 'packages',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'fields' => 'ids',
                        'orderby' => 'title',
                        'order' => 'ASC',
                    );
                    $over_query = new WP_Query($args);
                    $cust_query = $over_query->posts;
                }
                if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {


                    $packages_list .= '<div class="packages-main-holder">';

                    if ( $subscribed_active_pkgs ) {
                        $packages_list .= '
						<div id="purchased-package-head-' . $listing_add_counter . '" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
							<div class="dashboard-element-title">
								<strong>' . wp_dp_plugin_text_srt('wp_dp_listing_purchased_packages') . '</strong>
							</div>
						</div>';
                    }


                    if ( ! is_user_logged_in() ) {
                        $packages_list .= '<input type="checkbox" checked="checked" style="display:none;" name="wp_dp_listing_new_package_used">';
                    }
                    if ( true === Wp_dp_Member_Permissions::check_permissions('packages') ) {
                        if ( $subscribed_active_pkgs ) {
                            $packages_list .= '
							<div class="buy-new-pakg-actions">
								<input type="checkbox" style="display:none;" id="wp-dp-dev-new-pkg-checkbox-' . $listing_add_counter . '" name="wp_dp_listing_new_package_used">
							</div>';
                        } else {
                            $packages_list .= '<input type="checkbox" checked="checked" style="display:none;" name="wp_dp_listing_new_package_used">';
                        }
                    }

                    $packages_list .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                    if ( $subscribed_active_pkgs ) {

                        $packages_list .= '<div id="purchased-packages-' . $listing_add_counter . '" class="dir-purchased-packages">' . $subscribed_active_pkgs . '</div>';
                    }
                    $packages_list .= '</div>';
                    $packages_list .= '</div>';
                }
            }

            $html .= '
			<li id="listing-info-sec-' . $listing_add_counter . '">
			<div class="row">';
            // pending post
            if ( $listing_status == 'pending' ) {
                $html .= '
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="listing-info-sec">
						<div class="field-holder">
							<p>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_listing_pending_mode') . '</p>
						</div>
					</div>
				</div>';
                $wp_dp_opt_array = array(
                    'std' => 'true',
                    'cust_id' => '',
                    'cust_name' => 'expired_listing',
                    'cust_type' => 'hidden',
                    'classes' => '',
                    'return' => true,
                );
                $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
            }
            // expired post
            else if ( strtotime($listing_post_expiry_date) >= strtotime($listing_post_on_date) && strtotime($listing_post_expiry_date) <= strtotime(current_time('d-m-Y', 1)) && $packages_list ) {
                $html .= '
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="listing-info-sec">
					<div class="field-holder">
						<p>' . wp_dp_plugin_text_srt('wp_dp_member_listing_expired_renew_purchased_package') . '</p>
					</div>
					</div>
				</div>';
                $wp_dp_opt_array = array(
                    'std' => 'true',
                    'cust_id' => '',
                    'cust_name' => 'expired_listing',
                    'cust_type' => 'hidden',
                    'classes' => '',
                    'return' => true,
                );
                $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
            } else if ( strtotime($listing_post_expiry_date) >= strtotime($listing_post_on_date) && strtotime($listing_post_expiry_date) <= strtotime(current_time('d-m-Y', 1)) ) {
                $html .= '
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="listing-info-sec">
					<div class="field-holder">
						<p>' . wp_dp_plugin_text_srt('wp_dp_member_listing_expired_renew_new_package') . '</p>
					</div>
					</div>
				</div>';
                $wp_dp_opt_array = array(
                    'std' => 'true',
                    'cust_id' => '',
                    'cust_name' => 'expired_listing',
                    'cust_type' => 'hidden',
                    'classes' => '',
                    'return' => true,
                );
                $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
            }
            $html .= '
			</div>
			</li>';

            if ( $packages_list ) {
                $html .= '
				<li id="listing-packages-sec-' . $listing_add_counter . '" style="display: ' . $listing_up_visi . ';">
					<div class="row">
						' . $packages_list . '
					</div>
				</li>';
            }
            echo force_balance_tags($html);
        }

        public function listing_show_set_settings($die_ret = '') {
            global $listing_add_counter, $wp_dp_plugin_options, $wp_dp_form_fields_frontend;

            $listing_add_counter = isset($_POST['_main_counter']) ? $_POST['_main_counter'] : $listing_add_counter;

            $get_listing_id = wp_dp_get_input('listing_id', 0);
            $is_updating = false;
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
            }

            ob_start();

            $tab_display = 'block';
            if ( isset($_COOKIE['wp_dp_was_create_listing']) && $_COOKIE['wp_dp_was_create_listing'] != '' && is_user_logged_in() ) {
                $tab_display = 'none';
            }
            if ( isset($_GET['package_id']) && $_GET['package_id'] > 0 ) {
                $tab_display = 'none';
            }

            if ( $is_updating === true ) {
                //set display to none
            }

            $this->listing_add_tag_before('listing-information-tab-container', $tab_display);
            ?>
            <li>
                <ul class="membership-info-main">
                    <?php
                    do_action('wp_dp_listing_add_info', '');
                    ?>
                </ul>
                <ul id="listing-membership-info-main" class="membership-info-main">
                    <?php
                    $this->listing_packages();
                    ?>
                </ul>
            </li>

            <li>
                <?php
                $check_box = '';
                $get_listing_id = wp_dp_get_input('listing_id', 0);

                $check_box = wp_dp::get_terms_and_conditions_field('', 'terms-' . $listing_add_counter);

                $back_dash_btn = '';
                $update_dash_btn = '';
                if ( $is_updating === true ) {
                    $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
                    $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';
                    $user_listings_list = add_query_arg(array( 'dashboard' => 'listings' ), $wp_dp_dashboard_link);
                    $back_dash_btn = '';
                    $update_dash_btn = '<div class="listing-update-dashboard">';
                    $wp_dp_opt_array = array(
                        'std' => wp_dp_plugin_text_srt('wp_dp_listing_update'),
                        'cust_id' => '',
                        'cust_name' => 'do_updating_btn',
                        'cust_type' => 'submit',
                        'classes' => 'do_updating_btn',
                        'return' => true,
                    );
                    $update_dash_btn .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                    $update_dash_btn .= '</div>';
                }

                $html = '
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="btns-section">
							<div class="field-holder">
								<div class="payment-holder">
									' . $check_box . '
									<div class="dashboard-left-btns">
										' . $back_dash_btn . '
										' . $update_dash_btn . '
										<div class="next-btn-field">';
                $wp_dp_opt_array = array(
                    'std' => wp_dp_plugin_text_srt('wp_dp_listing_next'),
                    'cust_id' => ( is_user_logged_in() ? 'btn-next-listing-information' : 'btn-next-listing-information' ),
                    'cust_name' => 'next-btn',
                    'cust_type' => 'button',
                    'classes' => 'next-btn',
                    'extra_atr' => 'data-id="' . $listing_add_counter . '"',
                    'return' => true,
                );
                $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                $html .= '</div>
									</div>
								</div>
							</div> 
						</div> 
					</div>
				</div>';
                echo force_balance_tags($html);

                $this->after_listing();
                ?>
            </li>
            <?php
            $this->listing_add_tag_after();

            $html = ob_get_clean();

            if ( $die_ret == 1 ) {
                echo force_balance_tags($html);
            } else {
                echo json_encode(array( 'html' => $html ));
                die;
            }
        }

        public function listing_user_register_fields() {
            global $wp_dp_form_fields_frontend;

            $get_listing_id = wp_dp_get_input('listing_id', 0);
            $is_updating = false;
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
            }

            $package_info = $this->show_selected_listing_pkg_info();
            $submit_title = wp_dp_plugin_text_srt('wp_dp_dp_listing_save_preview');
            if ( $is_updating === true ) {
                $submit_title = wp_dp_plugin_text_srt('wp_dp_listing_update');
            } else {
                if ( isset($package_info['display']) && $package_info['display'] == false ) {
                    $submit_title = wp_dp_plugin_text_srt('wp_dp_dp_listing_save_preview');
                }
            }

            if ( $is_updating === true ) {
                ?>
                <li class="user-register-fields">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="payment-holder">
                                <div class="dashboard-left-btns">
                                    <div class="next-btn-field wp-dp-listing-submit-process">
                                        <div class="wp-dp-listing-submit-loader">
                                            <?php
                                            $wp_dp_opt_array = array(
                                                'std' => $submit_title,
                                                'cust_id' => 'register-listing-order',
                                                'cust_name' => 'next-btn',
                                                'cust_type' => 'submit',
                                                'classes' => 'next-btn',
                                            );
                                            $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <?php
            } else {
                ?>
                <li class="user-register-fields">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 add-listing-content">
                            <div class="row">
                                <?php if ( ! is_user_logged_in() ) { ?>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="field-holder">
                                            <label><?php echo wp_dp_plugin_text_srt('listing_contact_email'); ?>:</label>
                                            <div id="wp-dp-email-field-holder" class="user-field-inner">
                                                <?php
                                                $cs_opt_array = array(
                                                    'id' => 'user_email',
                                                    'std' => '',
                                                    'desc' => '',
                                                    'classes' => 'wp-dp-dev-req-field',
                                                    'type' => 'email',
                                                    'extra_atr' => 'onchange="wp_dp_user_avail(\'email\')" placeholder="example@abc.com"',
                                                    'hint_text' => '',
                                                );

                                                $wp_dp_form_fields_frontend->wp_dp_form_text_render($cs_opt_array);
                                                ?>
                                                <span class="checking-loader"></span>
                                            </div>
                                            <span class="wp-dp-email-validation" id="wp_dp_user_email_validation"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="field-holder">
                                            <label><?php echo wp_dp_plugin_text_srt('wp_dp_register_username'); ?>:</label>
                                            <div id="wp-dp-username-field-holder" class="user-field-inner">
                                                <?php
                                                $cs_opt_array = array(
                                                    'id' => 'user',
                                                    'std' => '',
                                                    'desc' => '',
                                                    'classes' => 'wp-dp-dev-req-field',
                                                    'type' => 'text',
                                                    'extra_atr' => 'onchange="wp_dp_user_avail(\'username\')" placeholder="' . wp_dp_plugin_text_srt('wp_dp_register_username') . '"',
                                                    'hint_text' => '',
                                                );
                                                $wp_dp_form_fields_frontend->wp_dp_form_text_render($cs_opt_array);
                                                ?>
                                                <span class="checking-loader"></span>
                                            </div>
                                            <span class="wp-dp-email-validation" id="wp_dp_user_name_validation"></span>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <?php
                                    if ( $is_updating == false ) {
                                        echo wp_dp_term_condition_form_field('term_policy', 'term_policy');
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 add-listing-sidebar">
                            <div class="payment-holder">
                                <div class="dashboard-left-btns">
                                    <div class="next-btn-field wp-dp-listing-submit-process">
                                        <div class="wp-dp-listing-submit-loader">
                                            <button type="submit" id="register-listing-order" name="next-btn" class="next-btn"><span><?php echo esc_html($submit_title); ?></span> <i class="icon-keyboard_arrow_right"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <?php
            }
        }

        public function listing_show_detail_settings($die_ret = '') {
            global $listing_add_counter, $wp_dp_plugin_options, $wp_dp_form_fields_frontend;

            $selected_type = '';
			
            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
                $selected_type = get_post_meta($get_listing_id, 'wp_dp_listing_type', true);

                if ( $selected_type != '' ) {
                    $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$selected_type", 'post_status' => 'publish', 'suppress_filters' => '0' ));
                    $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
                }
            } else {
                $is_updating = false;
                $selected_type = '';
                $listing_type_id = 0;
            }

            if ( isset($_COOKIE['wp_dp_was_create_listing']) && is_user_logged_in() && $is_updating === false ) {
                $pre_cookie_val = stripslashes($_COOKIE['wp_dp_was_create_listing']);
                $pre_cookie_val = json_decode($pre_cookie_val, true);
                $selected_type = isset($pre_cookie_val['type']) ? $pre_cookie_val['type'] : '';
            }

            $listing_add_counter = isset($_POST['_main_counter']) ? $_POST['_main_counter'] : $listing_add_counter;

            $member_add_listing_obj = new wp_dp_member_listing_actions();

            $back_dash_btn = '';
            $update_dash_btn = '';
            if ( $is_updating === true ) {
                $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
                $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';
                $user_listings_list = add_query_arg(array( 'dashboard' => 'listings' ), $wp_dp_dashboard_link);
                $back_dash_btn = '<div class="listing-back-dashboard"><a href="' . $user_listings_list . '">' . wp_dp_plugin_text_srt('wp_dp_listing_back_dashboard') . '</a></div>';
                $back_dash_btn = '';
                $update_dash_btn = '<div class="listing-update-dashboard">';
                $wp_dp_opt_array = array(
                    'std' => wp_dp_plugin_text_srt('wp_dp_listing_update'),
                    'cust_id' => '',
                    'cust_name' => 'do_updating_btn',
                    'cust_type' => 'submit',
                    'classes' => 'do_updating_btn',
                    'return' => true,
                );
                $update_dash_btn .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                $update_dash_btn .= '</div>';
            }

            ob_start();

            $tab_display = 'none';
            if ( isset($_COOKIE['wp_dp_was_create_listing']) && $_COOKIE['wp_dp_was_create_listing'] != '' && is_user_logged_in() ) {
                $tab_display = 'block';
            }

            $this->select_listing_type();
            ?>

            <li>
                <div class="wp-dp-dev-appended-cats"><?php echo ($this->listing_categories($selected_type, $get_listing_id)) ?></div>
            </li>

            <li>
                <?php
                $html = '';
                $html .= '
				<div id="wp-dp-dev-cf-con">';
                ob_start();
                do_action('wp_dp_listing_custom_fields_cf');
                $html .= ob_get_clean();
                $html .= '
				</div>';
                echo force_balance_tags($html);
                ?>
            </li>
            <?php echo $this->listing_features_list($listing_type_id, $get_listing_id); ?>
            
            <?php
            $this->after_listing();
            $html = ob_get_clean();

            if ( $die_ret == 1 ) {
                echo force_balance_tags($html);
            } else {
                echo json_encode(array( 'html' => $html ));
                die;
            }
        }

        /**
         * Listing Tags
         * @return markup
         */
        public function listing_tags($type_slug = '', $listing_id = '') {
            global $listing_add_counter;
            $html = '';
            $num_tags_allows = '';

            if ( $listing_id != '' && $listing_id != 0 && $this->is_member_listing($listing_id) ) {
                $is_updating = true;
            } else {
                $is_updating = false;
            }

            // enqueue required script
            wp_enqueue_script('jquery-ui');
            wp_enqueue_script('wp-dp-tags-it');
			$select_listing_type = wp_dp_get_input('select_type', '');
            if ( $select_listing_type != '' ) {
                $listing_type_post = get_page_by_path($select_listing_type, OBJECT, 'listing-type');
                $type_id = $listing_type_post->ID;
            } else {
                $listing_type_post = get_page_by_path($type_slug, OBJECT, 'listing-type');
                $type_id = isset($listing_type_post->ID) ? $listing_type_post->ID : '';
            }

            $wp_dp_tags_element = get_post_meta($type_id, 'wp_dp_tags_element', true);


           $selected_package_id = wp_dp_get_input('package_id', 0);
	   if($selected_package_id == ''){
	       $selected_package_id = wp_dp_get_input('_pkgg_id', 0);
	   }
            if ( $is_updating === true ) {
                 $num_tags_allows = get_post_meta($listing_id, 'wp_dp_transaction_listing_tags_num', true);
            } elseif ( $selected_package_id != 0 ) {
                $package_fields = get_post_meta($selected_package_id, 'wp_dp_package_data', true);
               $num_tags_allows = isset($package_fields['number_of_tags']['value']) ? $package_fields['number_of_tags']['value'] : 0;
            }
            $wp_dp_listing_type_tags = get_post_meta($type_id, 'wp_dp_listing_type_tags', true);
            $listing_tags_list = '';

            $wp_dp_listing_tags = get_post_meta($listing_id, 'wp_dp_listing_tags', true);
            if ( is_array($wp_dp_listing_tags) && ! empty($wp_dp_listing_tags) ) {
                $listing_tags_list = '';
                foreach ( $wp_dp_listing_tags as $wp_dp_listing_tag ) {
                    $listing_tags_list .= '<li>' . $wp_dp_listing_tag . '</li>';
                }
            }
            $html .= '<div class="row">';
            $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            $html .= '<div class="dashboard-element-title">';
            $html .= '<label>' . wp_dp_plugin_text_srt('wp_dp_add_listing_key_tags') . '</label>';
            $html .= '<span class="info-text">(' . sprintf(wp_dp_plugin_text_srt('wp_dp_add_listing_key_tags_maximum'), $num_tags_allows) . ')</span>';
            $html .= '</div>';
            $html .= '<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery(\'#listing-tags\').tagit({
						allowSpaces: true,
						tagLimit: \'' . $num_tags_allows . '\',
						placeholderText: \'' . wp_dp_plugin_text_srt('wp_dp_add_listing_key_tags_placeholder') . '\',
						fieldName : \'listing_tags[]\',
						onTagLimitExceeded: function(event, ui) {
							jQuery(".tagit-new input").val("");
							var resp = {
								type: "error",
								msg: \'' . sprintf(wp_dp_plugin_text_srt('wp_dp_add_listing_key_tags_limit'), $num_tags_allows) . '\'
							};
							wp_dp_show_response(resp);
						}
					});
				});
			</script>';
            $html .= '<ul id="listing-tags">';
            $html .= $listing_tags_list;
            $html .= '</ul>';
            if ( is_array($wp_dp_listing_type_tags) && ! empty($wp_dp_listing_type_tags) ) {
                $html .= '<div class="dashboard-element-title suggested-tags-head">';
                $html .= '<strong>' . wp_dp_plugin_text_srt('wp_dp_add_listing_suggested_tags') . '</strong>';
                $html .= '</div>';
                $html .= '<ul class="tag-cloud-container" id="tag-cloud">';
                foreach ( $wp_dp_listing_type_tags as $wp_dp_listing_type_tag ) {
                    $term = get_term_by('slug', $wp_dp_listing_type_tag, 'listing-tag');
                    if ( is_object($term) ) {
                        $html .= '<li class="tag-cloud" onclick="jQuery(\'#listing-tags\').tagit(\'createTag\', \'' . $term->name . '\');return false;">' . $term->name . '</li>';
                    }
                }
                $html .= '</ul>';
            }
            $html .= '</div>';
            $html .= '</div>';

            return apply_filters('wp_dp_front_listing_add_tags', $html, $type_id, $listing_id);
            // usage :: add_filter('wp_dp_front_listing_add_tags', 'my_callback_function', 10, 3);
        }

        public function listing_show_advance_options($die_ret = '', $atts = array()) {
            global $listing_add_counter, $wp_dp_plugin_options, $wp_dp_form_fields_frontend;

            $listing_add_counter = isset($_POST['_main_counter']) ? $_POST['_main_counter'] : $listing_add_counter;

            $get_listing_id = '';
            $html = '';
            $selected_type = '';
            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
                $selected_type = get_post_meta($get_listing_id, 'wp_dp_listing_type', true);
            } else {
                $is_updating = false;
                $selected_type = '';
            }

            if ( isset($_COOKIE['wp_dp_was_create_listing']) && is_user_logged_in() && $is_updating === false ) {
                $pre_cookie_val = stripslashes($_COOKIE['wp_dp_was_create_listing']);
                $pre_cookie_val = json_decode($pre_cookie_val, true);
                $selected_type = isset($pre_cookie_val['type']) ? $pre_cookie_val['type'] : '';
            }

            $types_args = array( 'posts_per_page' => '-1', 'post_type' => 'listing-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => '0' );
            $cust_query = get_posts($types_args);
            $listing_type_id = isset($cust_query[0]->ID) ? $cust_query[0]->ID : '';

            $wp_dp_listing_type_price = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
            $wp_dp_listing_type_price = isset($wp_dp_listing_type_price) && $wp_dp_listing_type_price != '' ? $wp_dp_listing_type_price : 'off';

            $member_add_listing_obj = new wp_dp_member_listing_actions();

            $back_dash_btn = '';
            $update_dash_btn = '';
            if ( $is_updating === true ) {
                $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
                $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';
                $user_listings_list = add_query_arg(array( 'dashboard' => 'listings' ), $wp_dp_dashboard_link);
                $back_dash_btn = '<div class="listing-back-dashboard"><a href="' . $user_listings_list . '">' . wp_dp_plugin_text_srt('wp_dp_listing_back_dashboard') . '</a></div>';
                $back_dash_btn = '';
                $update_dash_btn = '<div class="listing-update-dashboard">';
                $wp_dp_opt_array = array(
                    'std' => wp_dp_plugin_text_srt('wp_dp_listing_update'),
                    'cust_id' => '',
                    'cust_name' => 'do_updating_btn',
                    'cust_type' => 'submit',
                    'classes' => 'do_updating_btn',
                    'return' => true,
                );
                $update_dash_btn .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                $update_dash_btn .= '</div>';
            }

            ob_start();

            echo '<li class="listing-detail-holder">';

            if ( ! is_user_logged_in() ) {
                $html .= '
				<div class="user-info-message">	
					<div class="user-message"> 
						<strong class="message-title">' . wp_dp_plugin_text_srt('wp_dp_add_listing_login_dec_note') . ':</strong>
						<p>' . wp_dp_plugin_text_srt('wp_dp_add_listing_login_dec') . '</p>
					</div>
				</div>';
                echo force_balance_tags($html);
            }


            $listing_subtitle = isset($atts['listing_subtitle']) ? $atts['listing_subtitle'] : '';
            $listing_title_alignment = isset($atts['listing_title_alignment']) ? $atts['listing_title_alignment'] : '';
            $wp_dp_add_listing_element_title_color = isset($atts['wp_dp_add_listing_element_title_color']) ? $atts['wp_dp_add_listing_element_title_color'] : '';
            $wp_dp_add_listing_element_subtitle_color = isset($atts['wp_dp_add_listing_element_subtitle_color']) ? $atts['wp_dp_add_listing_element_subtitle_color'] : '';
            $wp_dp_add_listing_seperator_style = isset($atts['wp_dp_add_listing_seperator_style']) ? $atts['wp_dp_add_listing_seperator_style'] : '';

            $element_title_color = '';
            if ( isset($wp_dp_add_listing_element_title_color) && $wp_dp_add_listing_element_title_color != '' ) {
                $element_title_color = ' style="color:' . $wp_dp_add_listing_element_title_color . ' ! important"';
            }
            $element_subtitle_color = '';
            if ( isset($wp_dp_add_listing_element_subtitle_color) && $wp_dp_add_listing_element_subtitle_color != '' ) {
                $element_subtitle_color = ' style="color:' . $wp_dp_add_listing_element_subtitle_color . ' ! important"';
            }

            echo '<div class="element-title ' . $listing_title_alignment . '">';

            if ( $is_updating === true ) {
                ?>
                <h3<?php echo wp_dp_allow_special_char($element_title_color); ?>><?php echo wp_dp_plugin_text_srt('wp_dp_listing_update_listing') ?></h3>
                <?php
            } else {
                ?>
                <h3<?php echo wp_dp_allow_special_char($element_title_color); ?>><?php echo wp_dp_plugin_text_srt('wp_dp_listing_about_your_listing') ?></h3>
                <?php
            }
            if ( $listing_subtitle != '' ) {
                ?>
                <p<?php echo wp_dp_allow_special_char($element_subtitle_color); ?>><?php echo esc_html($listing_subtitle) ?></p>
                <?php
            }

            if ( isset($wp_dp_add_listing_seperator_style) && ! empty($wp_dp_add_listing_seperator_style) ) {
                $wp_dp_add_listings_seperator_html = '';
                if ( $wp_dp_add_listing_seperator_style == 'classic' ) {
                    $wp_dp_add_listings_seperator_html .='<div class="classic-separator ' . $listing_title_alignment . '"><span></span></div>';
                }
                if ( $wp_dp_add_listing_seperator_style == 'zigzag' ) {
                    $wp_dp_add_listings_seperator_html .='<div class="separator-zigzag ' . $listing_title_alignment . '">
										<figure><img src="' . trailingslashit(wp_dp::plugin_url()) . 'assets/images/zigzag-img1.png" alt=""/></figure>
									</div>';
                }
                echo force_balance_tags($wp_dp_add_listings_seperator_html);
            }

            echo '</div>';

            echo '<div class="row">';
            echo '<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 add-listing-content">' . "\n";
            echo '<ul class="listing-detail-list">' . "\n";
            $this->title_description();


            $faq_switch = get_post_meta($listing_type_id, 'wp_dp_faqs_options_element', true);

            if ( isset($faq_switch) && $faq_switch == 'on' ) {
                $photos_epc_tab = array( 'content' => '' );
                $photos_epc_tab_content = apply_filters('wp_dp_photos_epc_tab', $get_listing_id, $listing_type_id, $photos_epc_tab);
                if ( isset($photos_epc_tab_content['content']) && $photos_epc_tab_content['content'] != '' ) {
                    echo ($photos_epc_tab_content['content']);
                }
            }



            echo '</ul>' . "\n";
            echo '</div>' . "\n";
            echo '<div class="col-lg-1 col-md-1 col-sm-12 col-xs-12"></div>' . "\n";
            echo '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 add-listing-sidebar">' . "\n";
            echo '<div class="fancy-bdr-holder">' . "\n";
            echo '<div class="fancy-bdr-header">' . "\n";
            echo '<img src="' . wp_dp::plugin_url() . 'assets/frontend/images/add-listing-header-img.png" alt="">';
            echo '</div>' . "\n";
            echo '<div class="fancy-bdr-body">' . "\n";
            echo '<div class="text-holder">' . "\n";
            echo '<strong>' . wp_dp_plugin_text_srt('wp_dp_add_new_listing_quick_tip') . '</strong>' . "\n";
            echo '<p>' . wp_dp_plugin_text_srt('wp_dp_add_new_listing_quick_tip_text') . '</p>';
            echo '</div>' . "\n";
            echo '</div>' . "\n";
            echo '<div class="bdr-footer">' . "\n";
            echo '<img src="' . wp_dp::plugin_url() . 'assets/frontend/images/add-listing-footer-img.png" alt="">';
            echo '</div>' . "\n";
            echo '</div>' . "\n";
            echo '<div class="wp-dp-dev-appended-price listing-price-holder" style="display: ' . ($wp_dp_listing_type_price == 'on' ? 'block' : 'none') . ';">' . $this->listing_price($selected_type, $get_listing_id) . '</div>' . "\n";
            echo '</div>' . "\n";
            echo '</div>' . "\n";
            echo '</li>';

            $html = ob_get_clean();
            if ( $die_ret == 1 ) {
                echo force_balance_tags($html);
            } else {
                echo json_encode(array( 'html' => $html ));
                die;
            }
        }

        public function listing_show_working_days($die_ret = '') {
            $html = '';
            $selected_type = 0;
            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
                $selected_type = get_post_meta($get_listing_id, 'wp_dp_listing_type', true);
                if ( $post = get_page_by_path($selected_type, OBJECT, 'listing-type') ) {
                    $selected_type = $post->ID;
                } else {
                    $selected_type = 0;
                }
            } else {
                $is_updating = false;
                $selected_type = 0;
            }

            $opening_hours_element = get_post_meta($selected_type, 'wp_dp_opening_hours_element', true);

            $html .= '<li id="wp-dp-listing-workings-days-holder" class="workings-days-holder" style="display: ' . ($opening_hours_element == 'on' ? 'block' : 'block') . ';">';
            $html .= '<div class="row">';
            $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="dashboard-element-title">
					<strong>' . wp_dp_plugin_text_srt('wp_dp_member_add_list_working_days') . '</strong>
				</div>
			</div>';
            $html .= '<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 add-listing-content">' . "\n";
            $html .= $this->listing_opening_hours($selected_type, $get_listing_id);
            $html .= '</div>' . "\n";
            $html .= '<div class="col-lg-1 col-md-1 col-sm-12 col-xs-12"></div>' . "\n";
            $html .= '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 add-listing-sidebar">' . "\n";
            $html .= $this->listing_book_days_off($selected_type, $get_listing_id);
            $html .= '</div>' . "\n";
            $html .= '</div>' . "\n";
            $html .= '</li>';


            if ( $die_ret == 1 ) {
                echo force_balance_tags($html);
            } else {
                echo json_encode(array( 'html' => $html ));
                die;
            }
        }

        public function listing_show_loc_address($die_ret = '') {
            global $listing_add_counter, $wp_dp_plugin_options, $wp_dp_form_fields_frontend;

            $listing_add_counter = isset($_POST['_main_counter']) ? $_POST['_main_counter'] : $listing_add_counter;

            $get_listing_id = wp_dp_get_input('listing_id', 0);
            $is_updating = false;
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
            }

            $types_args = array( 'posts_per_page' => '-1', 'post_type' => 'listing-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => '0' );
            $cust_query = get_posts($types_args);
            $listing_type_id = isset($cust_query[0]->ID) ? $cust_query[0]->ID : '';

            $member_add_listing_obj = new wp_dp_member_listing_actions();

            $back_dash_btn = '';
            $update_dash_btn = '';
            if ( $is_updating === true ) {
                $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
                $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';
                $user_listings_list = add_query_arg(array( 'dashboard' => 'listings' ), $wp_dp_dashboard_link);
                $back_dash_btn = '<div class="listing-back-dashboard"><a href="' . $user_listings_list . '">' . wp_dp_plugin_text_srt('wp_dp_listing_back_dashboard') . '</a></div>';
                $back_dash_btn = '';
                $update_dash_btn = '<div class="listing-update-dashboard">';
                $wp_dp_opt_array = array(
                    'std' => wp_dp_plugin_text_srt('wp_dp_listing_update'),
                    'cust_id' => '',
                    'cust_name' => 'do_updating_btn',
                    'cust_type' => 'submit',
                    'classes' => 'do_updating_btn',
                    'return' => true,
                );
                $update_dash_btn .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                $update_dash_btn .= '</div>';
            }

            ob_start();

            echo ($member_add_listing_obj->listing_location($listing_type_id, $get_listing_id));
            $this->after_listing();
            $html = ob_get_clean();

            if ( $die_ret == 1 ) {
                echo force_balance_tags($html);
            } else {
                echo json_encode(array( 'html' => $html ));
                die;
            }
        }

        public function listing_show_listing_photos($die_ret = '') {
            global $listing_add_counter, $wp_dp_plugin_options, $wp_dp_form_fields_frontend;

            $listing_add_counter = isset($_POST['_main_counter']) ? $_POST['_main_counter'] : $listing_add_counter;

            $get_listing_id = wp_dp_get_input('listing_id', 0);
            $is_updating = false;
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
                $selected_type = get_post_meta($get_listing_id, 'wp_dp_listing_type', true);

                if ( $selected_type != '' ) {
                    $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$selected_type", 'post_status' => 'publish', 'suppress_filters' => '0' ));
                    $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
                }
            } else {
                $is_updating = false;
                $selected_type = '';
                $listing_type_id = 0;
            }

            $video_url = get_post_meta($get_listing_id, 'wp_dp_listing_video', true);
            $virtual_tour = get_post_meta($get_listing_id, 'wp_dp_listing_virtual_tour', true);

            $member_add_listing_obj = new wp_dp_member_listing_actions();

            $back_dash_btn = '';
            $update_dash_btn = '';
            if ( $is_updating === true ) {
                $wp_dp_dashboard_page = isset($wp_dp_plugin_options['wp_dp_member_dashboard']) ? $wp_dp_plugin_options['wp_dp_member_dashboard'] : '';
                $wp_dp_dashboard_link = $wp_dp_dashboard_page != '' ? wp_dp_wpml_lang_page_permalink($wp_dp_dashboard_page, 'page') : '';
                $user_listings_list = add_query_arg(array( 'dashboard' => 'listings' ), $wp_dp_dashboard_link);
                $back_dash_btn = '<div class="listing-back-dashboard"><a href="' . $user_listings_list . '">' . wp_dp_plugin_text_srt('wp_dp_listing_back_dashboard') . '</a></div>';
                $back_dash_btn = '';
                $update_dash_btn = '<div class="listing-update-dashboard">';
                $wp_dp_opt_array = array(
                    'std' => wp_dp_plugin_text_srt('wp_dp_listing_update'),
                    'cust_id' => '',
                    'cust_name' => 'do_updating_btn',
                    'cust_type' => 'submit',
                    'classes' => 'do_updating_btn',
                    'return' => true,
                );
                $update_dash_btn .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                $update_dash_btn .= '</div>';
            }

            ob_start();

            echo ($this->listing_gallery($listing_type_id, $get_listing_id));
            echo ($this->listing_attachments($listing_type_id, $get_listing_id));
            echo ($this->listing_floor_plans($listing_type_id, $get_listing_id));

            $type_video = get_post_meta($listing_type_id, 'wp_dp_video_element', true);
            $type_virtual_tour = get_post_meta($listing_type_id, 'wp_dp_virtual_tour_element', true);

            $selected_package_id = wp_dp_get_input('package_id', 0);
            $wp_dp_id  = isset( $wp_dp_id )? $wp_dp_id :'';
            if ( $is_updating === true ) {
                $listing_video = get_post_meta($wp_dp_id, 'wp_dp_transaction_listing_video', true);
            } elseif ( $selected_package_id != 0 ) {
                $package_fields = get_post_meta($selected_package_id, 'wp_dp_package_data', true);
                $listing_video = isset($package_fields['listing_video']['value']) ? $package_fields['listing_video']['value'] : 'off';
            }

            $html = '
			<li id="wp-dp-listing-video-holder" style="display: ' . ($listing_video == 'on' ? 'block' : 'none') . ';">
			<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="field-holder">';
            $html .= '<label>' . wp_dp_plugin_text_srt('wp_dp_listing_listing_video') . ' <span class="info-text">(' . wp_dp_plugin_text_srt('wp_dp_video_url_sites_example') . ')</span></label>';
            $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                    array(
                        'id' => 'video_url_' . $listing_add_counter,
                        'cust_name' => 'wp_dp_listing_video',
                        'std' => $video_url,
                        'desc' => '',
                        'classes' => '',
                        'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_listing_video_url') . '" autocomplete="off"',
                        'return' => true,
                        'force_std' => true,
                        'hint_text' => '',
                    )
            );
            $html .= '
			</div>
			</div>
			</div>
			</li>
			<li id="wp-dp-listing-virtual-tour-holder" class="listing-virtual-tour-holder" style="display: ' . ($type_virtual_tour == 'on' ? 'block' : 'none') . ';">
			<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="dashboard-element-title">
				<label>' . wp_dp_plugin_text_srt('wp_dp_add_listing_virtual_tour') . '</label>
			</div>
			<div class="field-holder">';
            $html .= $wp_dp_form_fields_frontend->wp_dp_form_textarea_render(
                    array(
                        'name' => '',
                        'id' => 'virtual_tour_' . $listing_add_counter,
                        'cust_name' => 'wp_dp_listing_virtual_tour',
                        'std' => $virtual_tour,
                        'desc' => '',
                        'classes' => '',
                        'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_add_listing_virtual_tour_desc') . '"',
                        'return' => true,
                        'force_std' => true,
                        'hint_text' => '',
                    )
            );
            $html .= '
			</div>
			</div>
			</div>
			</li>';
            echo force_balance_tags($html);

            $this->after_listing();
            $html = ob_get_clean();

            if ( $die_ret == 1 ) {
                echo force_balance_tags($html);
            } else {
                echo json_encode(array( 'html' => $html ));
                die;
            }
        }

        public function listing_show_set_membership($die_ret = '') {
            global $listing_add_counter, $wp_dp_plugin_options, $wp_dp_form_fields_frontend;

            $listing_add_counter = isset($_POST['_main_counter']) ? $_POST['_main_counter'] : $listing_add_counter;
            $get_listing_id = wp_dp_get_input('listing_id', 0);
            $is_updating = false;
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
            }

            $back_dash_btn = '';
            $update_dash_btn = '';
            ob_start();

            $package_info = $this->show_selected_listing_pkg_info();
            ?>
            <li class="package-review-holder"<?php echo (isset($package_info['display']) && $package_info['display'] == false) ? ' style="display: none;"' : ''; ?>>
                <div class="wp-dp-dev-listing-pckg-info">
                    <?php
                    if ( isset($package_info['html']) && $package_info['html'] != '' ) {
                        echo json_decode($package_info['html']);
                    }
                    ?>
                </div>
            </li>

            <li class="register-payment-gw-holder"<?php echo (isset($package_info['display']) && $package_info['display'] == false) ? ' style="display: none;"' : ''; ?>>

                <div class="dashboard-element-title">
                    <strong><?php echo wp_dp_plugin_text_srt('wp_dp_add_user_payment_info'); ?></strong>
                </div>
                <?php
                ob_start();
                $_REQUEST['trans_id'] = 0;
                $_REQUEST['action'] = 'listing-package';
                $_GET['trans_id'] = 0;
                $_GET['action'] = 'listing-package';
                $trans_fields = array(
                    'trans_id' => 0,
                    'action' => 'listing-package',
                    'back_button' => true,
                    'creating' => true,
                );
                do_action('wp_dp_payment_gateways', $trans_fields);
                $output = ob_get_clean();
                echo str_replace('col-lg-8 col-md-8', 'col-lg-12 col-md-12', $output);
                ?>
            </li>

            <li class="user-register-fields">
                <?php
                $submit_title = wp_dp_plugin_text_srt('wp_dp_dp_sbmit_order');
                if ( $is_updating === true ) {
                    $submit_title = wp_dp_plugin_text_srt('wp_dp_dp_sbmit_order');
                } else {
                    if ( isset($package_info['display']) && $package_info['display'] == false ) {
                        $submit_title = wp_dp_plugin_text_srt('wp_dp_dp_sbmit_order');
                    }
                }
                $html = '
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="payment-holder">';
                $html .= '<div class="dashboard-left-btns">
								' . $back_dash_btn . '
								<div class="next-btn-field wp-dp-listing-submit-process">
									<div class="wp-dp-listing-package-submit-loader">';
                $wp_dp_opt_array = array(
                    'std' => $submit_title,
                    'cust_id' => 'register-listing-package-order',
                    'cust_name' => 'next-btn',
                    'cust_type' => 'submit',
                    'classes' => 'next-btn',
                    'return' => true,
                );
                $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                $html .= '</div>
								</div>
							</div> 
						</div> 
					</div>
				</div>';
                echo force_balance_tags($html);
                ?>
            </li>
            <?php
            $html = ob_get_clean();
            if ( $die_ret == 1 ) {
                echo force_balance_tags($html);
            } else {
                echo json_encode(array( 'html' => $html ));
                die;
            }
        }

        public function listing_show_payment_information() {

            $this->listing_add_tag_before('payment-information-tab-container');
            ?>
            <li>
                <?php
                ob_start();
                $_REQUEST['trans_id'] = 0;
                $_REQUEST['action'] = 'listing-package';
                $_GET['trans_id'] = 0;
                $_GET['action'] = 'listing-package';
                $trans_fields = array(
                    'trans_id' => 0,
                    'action' => 'listing-package',
                    'back_button' => true,
                    'creating' => true,
                );
                do_action('wp_dp_payment_gateways', $trans_fields);
                $output = ob_get_clean();
                echo str_replace('col-lg-8 col-md-8', 'col-lg-12 col-md-12', $output);
                ?>

            </li>
            <li><div class="payment-process-form-container"></div></li>

            <?php
            $this->listing_add_tag_after();
        }

        public function listing_show_activation_tab() {
            global $wp_dp_plugin_options;
            $img_id = isset($wp_dp_plugin_options['wp_dp_listing_success_image']) ? $wp_dp_plugin_options['wp_dp_listing_success_image'] : '';
            $success_message = isset($wp_dp_plugin_options['wp_dp_listing_success_message']) ? $wp_dp_plugin_options['wp_dp_listing_success_message'] : '';
            $success_phone = isset($wp_dp_plugin_options['wp_dp_listing_success_phone']) ? $wp_dp_plugin_options['wp_dp_listing_success_phone'] : '';
            $success_fax = isset($wp_dp_plugin_options['wp_dp_listing_success_fax']) ? $wp_dp_plugin_options['wp_dp_listing_success_fax'] : '';
            $success_email = isset($wp_dp_plugin_options['wp_dp_listing_success_email']) ? $wp_dp_plugin_options['wp_dp_listing_success_email'] : '';

            $review_message = isset($wp_dp_plugin_options['wp_dp_listing_approval_message']) ? $wp_dp_plugin_options['wp_dp_listing_approval_message'] : '';

            $admin_review = isset($wp_dp_plugin_options['wp_dp_listings_review_option']) ? $wp_dp_plugin_options['wp_dp_listings_review_option'] : '';
            ?>
            <ul class="register-add-listing-tab-container activation-tab-container">
                <li>
                    <div class="activation-tab-message">
                        <div class="media-holder">
                            <figure>
                                <?php if ( $img_id != '' ) : ?>
                                    <img src="<?php echo wp_get_attachment_url($img_id); ?>" alt="<?php echo wp_dp_plugin_text_srt('wp_dp_listing_thank_you'); ?>">
                                <?php endif; ?>
                            </figure>
                        </div>
                        <div class="text-holder">
                            <strong><?php echo wp_dp_plugin_text_srt('wp_dp_listing_thank_you'); ?></strong>
                            <?php
                            if ( $admin_review == 'on' ) {
                                if ( $review_message != '' ) :
                                    ?>
                                    <span><?php echo esc_html($review_message); ?></span>
                                    <?php
                                endif;
                            } else {
                                if ( $success_message != '' ) :
                                    ?>
                                    <span><?php echo esc_html($success_message); ?></span>
                                    <?php
                                endif;
                            }
                            ?>
                        </div> 

                        <?php if ( $success_phone != '' || $success_fax != '' || $success_email != '' ) : ?>
                            <div class="thankyou-contacts">
                                <p><?php echo wp_dp_plugin_text_srt('wp_dp_listing_for_cancellation'); ?></p>
                                <ul class="list-inline clearfix">
                                    <?php if ( $success_phone != '' ) : ?>
                                        <li><i class="icon-phone4"></i><?php echo esc_html($success_phone); ?></li>
                                    <?php endif; ?>
                                    <?php if ( $success_fax != '' ) : ?>
                                        <li><i class="icon-fax"></i><?php echo esc_html($success_fax); ?></li>
                                    <?php endif; ?>
                                    <?php if ( $success_email != '' ) : ?>
                                        <li><i class="icon-envelope-o"></i><?php echo esc_html($success_email); ?></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                    </div>
                </li>
            </ul>
            <?php
            $this->listing_add_tag_after();
        }

        /**
         * Gallery Photos
         * @return markup
         */
        public function listing_gallery($type_id = '', $wp_dp_id = '') {
            global $listing_add_counter, $wp_dp_form_fields_frontend, $package_id;

            $html = '';
            $wp_dp_listing_gallery = get_post_meta($type_id, 'wp_dp_image_gallery_element', true);

            if ( $wp_dp_id != '' && $wp_dp_id != 0 && $this->is_member_listing($wp_dp_id) ) {
                $is_updating = true;
            } else {
                $is_updating = false;
            }

            // for remove depandancy at listing type
            if ( $type_id == 0 ) {
                $wp_dp_listing_gallery = 'on';
            }
            $wp_dp_listing_gallery_ids = get_post_meta($wp_dp_id, 'wp_dp_detail_page_gallery_ids', true);
            $attacment_placeholder = '';
            $attacment_sec_items = '';

            $selected_package_id = wp_dp_get_input('package_id', 0);
            if ( $is_updating === true ) {
                $num_pic_allows = get_post_meta($wp_dp_id, 'wp_dp_transaction_listing_pic_num', true);
            } elseif ( $selected_package_id != 0 ) {
                $package_fields = get_post_meta($selected_package_id, 'wp_dp_package_data', true);
                $num_pic_allows = isset($package_fields['number_of_pictures']['value']) ? $package_fields['number_of_pictures']['value'] : 0;
            }

            if ( is_array($wp_dp_listing_gallery_ids) && sizeof($wp_dp_listing_gallery_ids) > 0 ) {
                foreach ( $wp_dp_listing_gallery_ids as $img_item ) {
                    $img_url_arr = wp_get_attachment_image_src($img_item, 'wp_dp_media_3');
                    $img_url = isset($img_url_arr[0]) ? $img_url_arr[0] : '';
                    $attacment_sec_items .= '
					<li class="gal-img">
					<div class="drag-list">
					<div class="item-thumb"><img class="thumbnail" src="' . $img_url . '" alt=""/></div>
					<div class="item-assts">
					<div class="list-inline pull-right">
					<div class="close-btn" data-id="' . $listing_add_counter . '"><a href="javascript:void(0);"><i class="icon-cross"></i></a></div>
					</div>';
                    $wp_dp_opt_array = array(
                        'std' => esc_html($img_item),
                        'cust_id' => '',
                        'cust_name' => 'wp_dp_listing_gallery_item[]',
                        'cust_type' => 'hidden',
                        'classes' => '',
                        'return' => true,
                    );
                    $attacment_sec_items .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                    $attacment_sec_items .= '</div>
					</div>
					</li>';
                }
            }

            $html .= '
			<li class="wp-dp-dev-appended" style="display: ' . ($wp_dp_listing_gallery == 'on' ? 'block' : 'none') . ';">
			<div class="row" style="display:' . ($type_id > 0 ? 'block' : 'none') . ';">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="dashboard-title">
						<strong>
							' . wp_dp_plugin_text_srt('wp_dp_listing_step_5') . '
						</strong>
					</div>
				</div>
			</div>
			<div id="wp-dp-listing-gallery-holder" class="row" style="display:' . ($wp_dp_listing_gallery == 'on' ? 'block' : 'none') . ';">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="dashboard-element-title">
						<label>
							' . wp_dp_plugin_text_srt('wp_dp_listing_photo_gallery') . '
							<span class="info-text">(' . sprintf(wp_dp_plugin_text_srt('wp_dp_upload_gallery_images_placeholder'), $num_pic_allows) . ')</span>
						</label>
					</div>
					<div class="field-holder upload-area">
						<ul id="wp-dp-dev-gal-attach-sec-' . $listing_add_counter . '" class="wp-dp-gallery-holder">
							' . $attacment_sec_items . '
						</ul>
						<div class="gal-img-add">
							<div id="upload-gallery-' . $listing_add_counter . '" class="upload-gallery">
								<a href="javascript:void(0);" class="upload-btn wp-dp-dev-gallery-upload-btn" data-id="' . $listing_add_counter . '"><span><i class="icon-upload-gallery"></i> ' . wp_dp_plugin_text_srt('wp_dp_listing_upload_image') . '</span></a>
							</div>
						</div>';
            $wp_dp_opt_array = array(
                'std' => '',
                'cust_id' => 'image-uploader-' . $listing_add_counter,
                'cust_name' => 'wp_dp_listing_gallery_images[]',
                'cust_type' => 'file',
                'classes' => 'wp-dp-dev-gallery-uploader wp_dp_dev_listing_gallery_images',
                'return' => true,
                'extra_atr' => 'style="display:none;" data-id="' . $listing_add_counter . '" data-test="' . $wp_dp_id . '" data-count="' . $num_pic_allows . '" multiple="multiple" onchange="wp_dp_handle_file_select(event, \'' . $listing_add_counter . '\');"',
            );
            $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
            $html .= '</div>
				</div>
				<script>
				jQuery(document).ready(function ($) {
					$("#wp-dp-dev-gal-attach-sec-' . $listing_add_counter . '").sortable({
                        handle: \'.drag-list\',
                        cursor: \'move\',
                        items : \'.gal-img\',
                    });
					//document.getElementById(\'image-uploader-' . $listing_add_counter . '\').addEventListener(\'change\', function(){wp_dp_handle_file_select(\'' . $listing_add_counter . '\');}, false);
				});
				</script>
			</div>
			</li>';

            return apply_filters('wp_dp_front_listing_add_gallery_plugin', $html, $type_id, $wp_dp_id);
            // usage :: add_filter('wp_dp_front_listing_add_gallery_plugin', 'my_callback_function', 10, 3);
        }

        /**
         * Basic Info
         * @return markup
         */
        public function title_description($html = '') {
            global $wp_dp_form_fields_frontend, $listing_add_counter, $wp_dp_plugin_options;
            $wp_dp_listing_title = '';
            $wp_dp_listing_desc = '';
            $is_updating = false;
            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
                $wp_dp_listing_title = get_the_title($get_listing_id);
                $wp_dp_listing_desc = $this->listing_post_content($get_listing_id);
                $selected_type = get_post_meta($get_listing_id, 'wp_dp_listing_type', true);
            } else {
                $is_updating = false;
                $selected_type = '';
            }

            $html .= '
			<li class="listing-title-holder">
			<div class="row">';
            $wp_dp_listing_announce_title = isset($wp_dp_plugin_options['wp_dp_listing_announce_title']) ? $wp_dp_plugin_options['wp_dp_listing_announce_title'] : '';
            $wp_dp_listing_announce_description = isset($wp_dp_plugin_options['wp_dp_listing_announce_description']) ? $wp_dp_plugin_options['wp_dp_listing_announce_description'] : '';
            ob_start();
            if ( (isset($wp_dp_listing_announce_title) && $wp_dp_listing_announce_title <> '') || (isset($wp_dp_listing_announce_description) && $wp_dp_listing_announce_description <> '') ) {
                
            }
            $html .= ob_get_clean();
            $html .= '
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="listing-title">
					<div class="field-holder">
						<label>' . wp_dp_plugin_text_srt('wp_dp_listing_listing_title') . '</label>';
            $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render(
                    array(
                        'id' => 'listing_title_' . $listing_add_counter,
                        'cust_name' => 'wp_dp_listing_title',
                        'std' => $wp_dp_listing_title,
                        'desc' => '',
                        'classes' => 'wp-dp-dev-req-field',
                        'extra_atr' => ' placeholder="' . wp_dp_plugin_text_srt('wp_dp_listing_listing_title') . '"',
                        'return' => true,
                        'force_std' => true,
                        'hint_text' => '',
                    )
            );
            $html .= '
					</div>
					</div>
					</div>
					</div>
					</li>';

            $html .= '
			<li class="listing-content-holder">
			<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            $richeditor_place = wp_dp_plugin_text_srt('wp_dp_listing_listing_desc');

            $html .= '
			<div class="listing-desc">
			<div class="field-holder">
				<label>' . wp_dp_plugin_text_srt('wp_dp_listing_description') . '</label>';
            $html .= $wp_dp_form_fields_frontend->wp_dp_form_textarea_render(
                    array(
                        'name' => '',
                        'id' => 'listing_desc_' . $listing_add_counter,
                        'cust_name' => 'wp_dp_listing_desc',
                        'classes' => 'wp-dp-dev-req-field ad-wp-dp-editor',
                        'std' => $wp_dp_listing_desc,
                        'description' => '',
                        'return' => true,
                        'wp_dp_editor' => true,
                        'wp_dp_editor_placeholder' => ($is_updating === false ? $richeditor_place : ''),
                        'force_std' => true,
                        'hint' => ''
                    )
            );
            $html .= '
			</div>
			</div>';

            $html .= '
			</div>
			</div>
			</li>
';

            echo force_balance_tags($html);

            $this->listing_show_detail_settings(1);
            $this->listing_show_listing_photos(1);
        }

        /**
         * User Register Fields
         * @return markup
         */
        public function user_register_fields($html = '') {
            global $listing_add_counter, $wp_dp_form_fields_frontend;

            if ( ! is_user_logged_in() ) {
                $html .= '
				<li id="wp-dp-dev-user-signup-' . $listing_add_counter . '">
				<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="dashboard-element-title">
						<strong>' . wp_dp_plugin_text_srt('wp_dp_listing_signup_fields') . '</strong>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="field-holder">
						<label>' . wp_dp_plugin_text_srt('wp_dp_listing_user_name') . '</label>';
                $wp_dp_opt_array = array(
                    'std' => '',
                    'cust_id' => 'wp_dp_listing_username',
                    'cust_name' => 'wp_dp_listing_username',
                    'cust_type' => 'text',
                    'classes' => 'wp-dp-dev-username wp-dp-dev-req-field',
                    'return' => true,
                    'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_listing_username') . '" data-id="' . $listing_add_counter . '" data-type="username"',
                );
                $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                $html .= '<span class="field-info wp-dp-dev-username-check"></span>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="field-holder">
						<label>' . wp_dp_plugin_text_srt('wp_dp_listing_email') . '</label>';
                $wp_dp_opt_array = array(
                    'std' => '',
                    'cust_id' => 'wp_dp_listing_user_email',
                    'cust_name' => 'wp_dp_listing_user_email',
                    'cust_type' => 'text',
                    'classes' => 'wp-dp-dev-user-email wp-dp-dev-req-field',
                    'return' => true,
                    'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_listing_email_address') . '" data-id="' . $listing_add_counter . '" data-type="useremail"',
                );
                $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                $html .= '<span class="field-info wp-dp-dev-useremail-check"></span>
					</div>
				</div>
				</div>
				</li>';
            }
            echo force_balance_tags($html);
        }

        /**
         * Select Listing Type
         * @return markup
         */
        public function select_listing_type($html = '') {
            global $listing_add_counter, $wp_dp_form_fields_frontend;

            $selected_type = '';
            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
                $selected_type = get_post_meta($get_listing_id, 'wp_dp_listing_type', true);
            } else {
                $is_updating = false;
                $selected_type = '';
            }

            if ( isset($_COOKIE['wp_dp_was_create_listing']) && is_user_logged_in() && $is_updating === false ) {
                $pre_cookie_val = stripslashes($_COOKIE['wp_dp_was_create_listing']);
                $pre_cookie_val = json_decode($pre_cookie_val, true);
                $selected_type = isset($pre_cookie_val['type']) ? $pre_cookie_val['type'] : '';
            }

            $types_options = '';
            $types_args = array( 'posts_per_page' => '-1', 'post_type' => 'listing-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => '0' );
            $cust_query = get_posts($types_args);
            $types_options .= '<option value="">' . wp_dp_plugin_text_srt('wp_dp_listing_select_type_desc') . '</option>';
            if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                $type_counter = 1;
                $select_first_type = '';
                foreach ( $cust_query as $type_post ) {
                    if ( $type_counter == 1 ) {
                        $select_first_type = $type_post->post_name;
                    }
                    $option_selected = '';
                    if ( $selected_type != '' && $selected_type == $type_post->post_name ) {
                        $option_selected = ' selected="selected"';
                    }
                    $types_data[$type_post->post_name] = get_the_title($type_post->ID);
                    $types_options .= '<option' . $option_selected . ' value="' . $type_post->post_name . '">' . get_the_title($type_post->ID) . '</option>' . "\n";
                    $type_counter ++;
                }
            }
            $html .= '
			<li id="wp-dp-type-sec-' . $listing_add_counter . '">
			<div class="row">

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="listing-types-holder">
			<div class="field-holder">
				<label>' . wp_dp_plugin_text_srt('wp_dp_listing_add_listing_category_label') . '</label>';

            if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                $html .='<div class="field-holder-loader">';
                $wp_dp_opt_array = array(
                    'std' => $get_listing_id,
                    'cust_id' => 'listing-type-' . $listing_add_counter,
                    'cust_name' => 'wp_dp_listing_type',
                    'classes' => 'dropdown chosen-select-no-single wp-dp-dev-select-type',
                    'extra_atr' => 'data-id="' . $listing_add_counter . '" data-pkgg-id="'.isset( $_REQUEST['package_id'] )? $_REQUEST['package_id'] : ''.'"',
                    'options' => $types_options,
                    'return' => true,
                    'options_markup' => true,
                );
                $html .= $wp_dp_form_fields_frontend->wp_dp_form_select_render($wp_dp_opt_array);
                $html .='<div id="cat-loader-' . $listing_add_counter . '"></div>';
                $html .= '</div>';
                $html .= '<script type="text/javascript">
							//jQuery( document ).ready(function() {
								//jQuery(".wp-dp-dev-select-type").val("' . $select_first_type . '").change();
							//});
                            jQuery(".listing-types-holder .wp-dp-dev-select-type").chosen({disable_search: true});
                    </script>';

                $wp_dp_opt_array = array(
                    'std' => $get_listing_id,
                    'cust_id' => '',
                    'cust_name' => 'get_listing_id',
                    'cust_type' => 'hidden',
                    'classes' => '',
                    'return' => true,
                );
                $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
            }

            $html .= '
			</div>
			</div>
			</div>

			</div>
			</li>';

            echo force_balance_tags($html);
        }

        /**
         * Ajax Loader
         * @return markup
         */
        public function ajax_loader($echo = true) {
            global $listing_add_counter;
            $html = '
				<div id="wp-dp-dev-loader-' . absint($listing_add_counter) . '" class="wp-dp-loader"></div>
				<div id="wp-dp-dev-act-msg-' . absint($listing_add_counter) . '" class="wp-dp-loader"></div>';
            if ( $echo ) {
                echo force_balance_tags($html);
            } else {
                return force_balance_tags($html);
            }
        }

        /**
         * Listing Price
         * @return markup
         */
        public function listing_price($select_type = '', $wp_dp_id = 0) {
            global $listing_add_counter, $wp_dp_form_fields_frontend, $wp_dp_plugin_options, $current_user;
            $company_id = wp_dp_company_id_form_user_id($current_user->ID);
            $listing_type_id = 0;
            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
            } else {
                $is_updating = false;
            }
            if ( $select_type != '' ) {
                $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => $select_type, 'post_status' => 'publish', 'suppress_filters' => '0' ));
                $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
            }

            $wp_dp_listing_type_price = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
            $wp_dp_listing_type_price = isset($wp_dp_listing_type_price) && $wp_dp_listing_type_price != '' ? $wp_dp_listing_type_price : 'off';

            $wp_dp_listing_type_special_price = get_post_meta($listing_type_id, 'wp_dp_listing_type_special_price', true);
            $wp_dp_listing_type_special_price = isset($wp_dp_listing_type_special_price) && $wp_dp_listing_type_special_price != '' ? $wp_dp_listing_type_special_price : 'off';

            $wp_dp_listing_price_options = get_post_meta($wp_dp_id, 'wp_dp_listing_price_options', true);
            $wp_dp_listing_price = get_post_meta($wp_dp_id, 'wp_dp_listing_price', true);
            $wp_dp_listing_special_price = get_post_meta($wp_dp_id, 'wp_dp_listing_special_price', true);
            $wp_dp_price_type = get_post_meta($wp_dp_id, 'wp_dp_price_type', true);
            $phone_number = get_post_meta($company_id, 'wp_dp_phone_number', true);
            $phone_number = isset($phone_number) && $phone_number != '' ? $phone_number : '';
            $currency_sign = wp_dp_get_currency_sign();

            $html = '';

            if ( $wp_dp_listing_type_price == 'on' ) {
                $html .= '<div class="row">';

                $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                $html .= '<div class="field-holder">';
                $html .= '<label>' . wp_dp_plugin_text_srt('wp_dp_listing_price_options') . '</label>';
                $wp_dp_opt_array = array(
                    'std' => $wp_dp_listing_price_options,
                    'id' => 'listing_price_options',
                    'classes' => '',
                    'extra_atr' => 'onchange="wp_dp_listing_price_change_frontend(this.value)"',
                    'options' => array( 'price' => wp_dp_plugin_text_srt('wp_dp_listing_listing_price'), 'on-call' => wp_dp_plugin_text_srt('wp_dp_listing_price_on_call'), ),
                    'return' => true,
                );
                $html .= $wp_dp_form_fields_frontend->wp_dp_form_select_render($wp_dp_opt_array);
                $html .= '</div>';
                $html .= "
					<script>
						function wp_dp_listing_price_change_frontend(price_selection) {
							if ( price_selection == 'on-call') {
								jQuery('#wp_dp_listing_price_toggle').hide();
								if( jQuery('#wp_dp_listing_special_price_toggle').length > 0 ){
									jQuery('#wp_dp_listing_special_price_toggle').hide();
								}
								if (price_selection == 'on-call') {
									jQuery('#wp-dp-listing-oncall-number').show();
									jQuery('#wp-dp-listing-oncall-number').html('<div class=\"field-holder has-bg\"><label>" . wp_dp_plugin_text_srt('wp_dp_phone') . "</label><input type=\"text\" placeholder=\"" . $phone_number . "\" value=\"" . $phone_number . "\" class=\"wp-dp-dev-req-field\" name=\"wp_dp_listing_oncall_number\"></div>');
								} else {
									jQuery('#wp-dp-listing-oncall-number').hide();
									jQuery('#wp-dp-listing-oncall-number').html('');
								}
							} else {
								jQuery('#wp_dp_listing_price_toggle').show();
								if( jQuery('#wp_dp_listing_special_price_toggle').length > 0 ){
									jQuery('#wp_dp_listing_special_price_toggle').show();
								}
								jQuery('#wp-dp-listing-oncall-number').hide();
								jQuery('#wp-dp-listing-oncall-number').html('');
							}
						}
						jQuery(\".chosen-select, .chosen-select-no-single\").chosen();
						$(\"#wp_dp_listing_price_options\").chosen({
							\"disable_search\": true
						}); 
					</script>";
                $html .= '</div>';

                $hide_div = '';
                if ( $wp_dp_listing_price_options == 'none' || $wp_dp_listing_price_options == 'on-call' ) {
                    $hide_div = 'style="display:none;"';
                }

                $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 price-option-field" id="wp_dp_listing_price_toggle" ' . $hide_div . '>';
                $html .= '<div class="field-holder has-bg">';
                $html .= '<span class="price-currency-sign">' . $currency_sign . '</span>';
                $wp_dp_opt_array = array(
                    'std' => $wp_dp_listing_price,
                    'id' => 'listing_price',
                    'extra_atr' => 'autocomplete="off" placeholder="12000"',
                    'return' => true,
                );
                $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                $html .= '<label>' . wp_dp_plugin_text_srt('wp_dp_listing_price') . '</label>';
                $html .= '</div>';
                $html .= '</div>' . "\n";

                $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="wp-dp-listing-special-price" style="display: ' . ($wp_dp_listing_type_special_price == 'on' ? 'block' : 'none') . ';">';
                $html .= '<div class="price-option-field" id="wp_dp_listing_special_price_toggle" ' . $hide_div . '>';
                $html .= '<div class="field-holder has-bg">';
                $html .= '<span class="price-currency-sign">' . $currency_sign . '</span>';
                $wp_dp_opt_array = array(
                    'std' => $wp_dp_listing_special_price,
                    'id' => 'listing_special_price',
                    'extra_atr' => 'autocomplete="off" placeholder="11500"',
                    'return' => true,
                );
                $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                $html .= '<label>' . wp_dp_plugin_text_srt('wp_dp_listing_special_price') . '</label>';
                $html .= '</div>';
                $html .= '</div>' . "\n";
                $html .= '</div>' . "\n";


                $html .= '<div id="wp-dp-listing-oncall-number" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 price-option-field" style="display: none;"></div>';

                $html .= '</div>';
            }

            return $html;
        }

        /**
         * Listing Categories
         * @return markup
         */
        public function listing_categories($type_slug = '', $wp_dp_id = '') {
            global $listing_add_counter, $wp_dp_form_fields, $wp_dp_listing_meta;

            $html = '';
		//$html .= '	<li class="listing-tags-holder">';
		$html .= '	<div id="wp-dp-proprty-tags-holder" class="wp-dp-proprty-tags-holder">';
		$html .=     $this->listing_tags($type_slug, $wp_dp_id);
		//$html .= 	'</div></li>';
            $html .= '<div class="create-listings-cats">';
            $html .= $wp_dp_listing_meta->listing_categories($type_slug, $wp_dp_id, false);
            $html .= '</div>';
            return apply_filters('wp_dp_front_listing_add_categories', $html, $type_slug, $wp_dp_id);
            // usage :: add_filter('wp_dp_front_listing_add_categories', 'my_callback_function', 10, 3);
        }

        /**
         * Load wp_dp Meta Data
         * @return markup
         */
        public function wp_dp_register_user_and_listing_load_cf_callback() {
            global $listing_add_counter;
            $listing_add_counter = wp_dp_get_input('listing_add_counter', '');
            $listing_type = wp_dp_get_input('select_type', '');
            $html = '';
            if ( $listing_type != '' ) {
                $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type", 'post_status' => 'publish', 'suppress_filters' => '0' ));
                $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
                $html = $this->listing_categories($listing_type_id, $get_listing_id);
                $html .= $this->listing_price($listing_type_id, $get_listing_id);
            }
            if ( isset($_REQUEST['action']) && $_REQUEST['action'] != 'editpost' ) {
                ob_start();
                ?>
                <script type="text/javascript">
                    var listingCategoryFilterAjax;
                    function wp_dp_load_category_models(selected_val, post_id, main_container, load_saved_value) {
                        "use strict";
                        var data_vals = '';
                        if (typeof (listingCategoryFilterAjax) != "undefined") {
                            listingCategoryFilterAjax.abort();
                        }
                        var wp_dp_listing_category = jQuery("#wp_dp_listing_category").val();
                        listingCategoryFilterAjax = jQuery.ajax({
                            type: "POST",
                            dataType: "JSON",
                            url: wp_dp_globals.ajax_url,
                            data: data_vals + "&action=wp_dp_meta_listing_categories&selected_val=" + selected_val + "&post_id=" + post_id + "&wp_dp_listing_category=" + wp_dp_listing_category + "&load_saved_value=" + load_saved_value,
                            success: function (response) {
                                jQuery("." + main_container).html(response.html);
                                jQuery(".chosen-select").chosen();
                            }
                        });
                    }
                </script>
                <?php
                $html = ob_get_clean() . $html;

                ob_start();
                ?>
                <script type="text/javascript">
                    (function ($) {
                        var container = $("li.wp-dp-dev-appended");
                        $(".chosen-select", container).chosen({width: "100%"});
                    })(jQuery);
                </script>
                <?php
                $html .= ob_get_clean();
                echo json_encode(array( 'main_html' => $html ));
                wp_die();
            } else {
                echo force_balance_tags($html);
            }
        }

        /**
         * Features List
         * @return markup
         */
        public function listing_features_list($type_id = '', $wp_dp_id = 0) {
            global $listing_add_counter, $wp_dp_form_fields_frontend;

            $html = '';
            $wp_dp_listing_features = get_post_meta($wp_dp_id, 'wp_dp_listing_feature_list', true);
            $wp_dp_get_features = get_post_meta($type_id, 'feature_lables', true);
            $wp_dp_feature_icons = get_post_meta($type_id, 'wp_dp_feature_icon', true);
            $wp_dp_feature_icon_group = get_post_meta($type_id, 'wp_dp_feature_icon_group', true);
			
            $type_features_element = get_post_meta($type_id, 'wp_dp_features_element', true);
			
			$features_display = 'none';
			if( $type_features_element == 'on' && is_array($wp_dp_get_features) && !empty($wp_dp_get_features)  ){
				$features_display = 'block';
			}
			
			$html .= '
			<li id="wp-dp-listing-features-holder" class="listing-features-holder wp-dp-listing-features-holder" style="display: ' . $features_display . ';">
			<div class="wp-dp-append-features-check-list">
			<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="dashboard-element-title">
					<strong>' . wp_dp_plugin_text_srt('wp_dp_listing_listing_features') . '<span class="sub-title">' . wp_dp_plugin_text_srt('wp_dp_listing_list_listing_features') . '</span></strong>
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="field-holder">';

			$feature_counter = 1;
			$features_list = array();
			if( isset( $wp_dp_get_features ) && !empty($wp_dp_get_features) ){
				foreach ( $wp_dp_get_features as $feat_key => $features ) {
					if ( isset($features) && ! empty($features) ) {
						$wp_dp_feature_name = isset($features) ? $features : '';
						$wp_dp_feature_icon = isset($wp_dp_feature_icons[$feat_key]) ? $wp_dp_feature_icons[$feat_key] : '';
						$icon_group = isset($wp_dp_feature_icon_group[$feat_key]) ? $wp_dp_feature_icon_group[$feat_key] : '';

						$features_list[$wp_dp_feature_name . "_icon" . $wp_dp_feature_icon . '_icon' . $icon_group] = $wp_dp_feature_name;
					}
				}
			}

			$wp_dp_opt_array = array(
				'std' => $wp_dp_listing_features,
				'id' => 'feature-list-check-' . $wp_dp_id,
				'cust_id' => 'feature-list-check-' . $wp_dp_id,
				'cust_name' => 'wp_dp_listing_feature[]',
				'description' => '',
				'classes' => 'chosen-select',
				'options' => $features_list,
				'return' => true,
			);
			$html .= $wp_dp_form_fields_frontend->wp_dp_form_multiselect_render($wp_dp_opt_array);

			$html .= '
			</div>
			</div>
			</div>
			</div>
			</li>';
            
            return apply_filters('wp_dp_front_listing_add_features_list', $html, $type_id, $wp_dp_id);
        }

        /**
         * Load Subscribed Packages
         * @return markup
         */
        public function listing_user_subscribed_packages() {
            global $listing_add_counter, $wp_dp_plugin_options, $wp_dp_form_fields_frontend;
            $html = '';
            $pkg_options = '';
            $wp_dp_currency_sign = isset($wp_dp_plugin_options['wp_dp_currency_sign']) ? $wp_dp_plugin_options['wp_dp_currency_sign'] : '$';

            $atcive_pkgs = $this->user_all_active_pkgs();
            if ( is_array($atcive_pkgs) && sizeof($atcive_pkgs) > 0 ) {
                $pkgs_counter = 1;
                $html .= '<div class="all-pckgs-sec">';
                foreach ( $atcive_pkgs as $atcive_pkg ) {

                    $package_id = get_post_meta($atcive_pkg, 'wp_dp_transaction_package', true);
                    $package_type = get_post_meta($package_id, 'wp_dp_package_type', true);

                    $package_price = get_post_meta($atcive_pkg, 'wp_dp_transaction_amount', true);
                    $package_title = $package_id != '' ? get_the_title($package_id) : '';
                    $pkg_options .= '<div class="wp-dp-pkg-holder">';
                    $pkg_options .= '<div class="wp-dp-pkg-header field-holder">';
                    $pkg_options .= '
					<div class="pkg-title-price pull-left">
						<label class="pkg-title">' . $package_title . '</label>
						<span class="pkg-price">' . sprintf(wp_dp_plugin_text_srt('wp_dp_listing_price_s'), wp_dp_get_currency($package_price, true)) . '</span>
					</div>
					<div class="pkg-detail-btn pull-right">';

                    $package_image_nums = get_post_meta($atcive_pkg, 'wp_dp_transaction_listing_pic_num', true);
                    $package_doc_nums = get_post_meta($atcive_pkg, 'wp_dp_transaction_listing_doc_num', true);

                    $wp_dp_opt_array = array(
                        'std' => $package_id . 'pt_' . $atcive_pkg,
                        'cust_id' => 'package-' . $package_id . 'pt_' . $atcive_pkg,
                        'cust_name' => 'wp_dp_listing_active_package',
                        'cust_type' => 'radio',
                        'classes' => 'wp-dp-dev-req-field',
                        'return' => true,
                        'extra_atr' => 'style="display:none;" data-picnum="' . $package_image_nums . '" data-docnum="' . $package_doc_nums . '" data-main-id="' . $listing_add_counter . '" data-id="' . $package_id . 'pt_' . $atcive_pkg . '" data-ptype="purchased" data-ppric="free"',
                    );
                    $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                    $pkg_options .= '<a href="javascript:void(0);" class="wp-dp-dev-detail-pkg" data-id="' . $package_id . 'pt_' . $atcive_pkg . '">' . wp_dp_plugin_text_srt('wp_dp_listing_detail') . '</a>
						<span class="check-select dev-listing-pakcge-step" data-picnum="' . $package_image_nums . '" data-docnum="' . $package_doc_nums . '" data-main-id="' . $listing_add_counter . '" data-id="' . $package_id . 'pt_' . $atcive_pkg . '" data-ptype="purchased" data-ppric="free"><i class="icon-check-circle-o"></i></span>
					</div>';
                    $pkg_options .= '</div>';
                    $pkg_options .= $this->subs_package_info($package_id, $atcive_pkg);
                    $pkg_options .= '</div>';
                    $pkgs_counter ++;
                }

                $html .= $pkg_options;
                $html .= '</div>';
            }

            return apply_filters('wp_dp_listing_add_subscribed_packages', $html);
        }

        /**
         * Load Packages and Payment
         * @return markup
         */
        public function listing_packages() {
            global $wp_dp_plugin_options, $listing_add_counter;

            $html = '';

            $listing_up_visi = 'block';
            $listing_hide_btn = 'none';

            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $listing_up_visi = 'none';
                $listing_hide_btn = 'inline-block';
            }

            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $is_updating = true;
                $selected_type = get_post_meta($get_listing_id, 'wp_dp_listing_type', true);
                $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$selected_type", 'post_status' => 'publish', 'suppress_filters' => '0' ));
                $selected_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
            } else {
                $is_updating = false;
                $selected_type_id = 0;
            }

            if ( isset($_POST['p_listing_typ']) && $_POST['p_listing_typ'] != '' ) {
                $selected_type = $_POST['p_listing_typ'];
                $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$selected_type", 'post_status' => 'publish', 'suppress_filters' => '0' ));
                $selected_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
            }

            $show_li = false;
            $show_pgt = false;

            $wp_dp_free_listings_switch = isset($wp_dp_plugin_options['wp_dp_free_listings_switch']) ? $wp_dp_plugin_options['wp_dp_free_listings_switch'] : '';
            $wp_dp_currency_sign = isset($wp_dp_plugin_options['wp_dp_currency_sign']) ? $wp_dp_plugin_options['wp_dp_currency_sign'] : '$';

            if ( $wp_dp_free_listings_switch != 'on' ) {

                $all_pkgs_arr = array();
                // subscribed packages list
                $subscribed_active_pkgs = $this->listing_user_subscribed_packages();

                if ( isset($_GET['package_id']) && $_GET['package_id'] != '' ) {
                    $buying_pkg_id = $_GET['package_id'];
                }

                $new_pkg_btn_visibility = 'none';
                $new_pkgs_visibility = 'block';
                if ( $subscribed_active_pkgs ) {
                    $new_pkg_btn_visibility = 'block';
                    $new_pkgs_visibility = 'none';
                }

                if ( isset($_COOKIE['wp_dp_was_create_listing']) && is_user_logged_in() ) {
                    $pre_cookie_val = stripslashes($_COOKIE['wp_dp_was_create_listing']);
                    $pre_cookie_val = json_decode($pre_cookie_val, true);
                    $buying_pkg_id = isset($pre_cookie_val['package']) ? $pre_cookie_val['package'] : '';
                }

                // Packages
                $packages_list = '';

                $cust_query = get_post_meta($selected_type_id, 'wp_dp_listing_type_packages', true);
                if ( empty($cust_query) ) {
                    $args = array(
                        'post_type' => 'packages',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'fields' => 'ids',
                        'orderby' => 'title',
                        'order' => 'ASC',
                    );
                    $over_query = new WP_Query($args);
                    $cust_query = $over_query->posts;
                }
                if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
                    $opts_counter = 0;
                    $packages_list_opts = '<div class="all-pckgs-sec table-responsive">';

                    $all_dyn_array = array();
                    foreach ( $cust_query as $package_post ) {
                        if ( isset($package_post) ) {
                            $dynamic_package_data = get_post_meta($package_post, 'wp_dp_package_fields', true);
                            if ( is_array($dynamic_package_data) && sizeof($dynamic_package_data) > 0 ) {
                                foreach ( $dynamic_package_data as $dynamic_data ) {
                                    if ( isset($dynamic_data['field_type']) && isset($dynamic_data['field_label']) && isset($dynamic_data['field_value']) ) {
                                        $d_type = $dynamic_data['field_type'];
                                        $d_label = $dynamic_data['field_label'];
                                        $d_value = $dynamic_data['field_value'];
                                        $all_dyn_array[] = array( 'field_type' => $d_type, 'field_label' => $d_label, 'field_value' => $d_value );
                                    }
                                }
                            }
                        }
                    }

                    if ( ! empty($all_dyn_array) ) {
                        $all_dyn_array = $all_dyn_array;
                    }

                    foreach ( $cust_query as $package_post ) {
                        if ( isset($package_post) ) {
                            // Package Fields
                            $pakge_feature_fields = $this->listing_pckage_meta_fields($package_post);
                            $show_li = true;
                            $packg_title = $package_post != '' ? get_the_title($package_post) : '';
                            $package_type = get_post_meta($package_post, 'wp_dp_package_type', true);
                            $package_price = get_post_meta($package_post, 'wp_dp_package_price', true);
                            $_package_data = get_post_meta($package_post, 'wp_dp_package_data', true);

                            $dynamic_package_data = get_post_meta($package_post, 'wp_dp_package_fields', true);

                            $package_listing_duration = isset($_package_data['listing_duration']['value']) ? $_package_data['listing_duration']['value'] : 0;
                            $package_total_listings = isset($_package_data['number_of_listing_allowed']['value']) ? $_package_data['number_of_listing_allowed']['value'] : 0;

                            $package_is_feature = isset($_package_data['number_of_featured_listings']['value']) ? $_package_data['number_of_featured_listings']['value'] : '';
                            $package_is_top_cat = isset($_package_data['number_of_top_cat_listings']['value']) ? $_package_data['number_of_top_cat_listings']['value'] : '';

                            $all_pkgs_arr['package_id'][] = $package_post;
                            $all_pkgs_arr['package_title'][] = $packg_title;
                            $all_pkgs_arr['package_price'][] = $package_price;
                            $all_pkgs_arr['package_type'][] = $package_type;
                            $all_pkgs_arr['total_listings'][] = $package_total_listings;
                            $all_pkgs_arr['listing_duration'][] = $package_listing_duration;
                            $all_pkgs_arr['featured'][] = $package_is_feature;
                            $all_pkgs_arr['top_category'][] = $package_is_top_cat;
                            foreach ( $pakge_feature_fields as $pakge_feat ) {
                                $all_pkgs_arr['feature_fields'][$pakge_feat['key']][] = array( 'title' => $pakge_feat['label'], 'value' => $pakge_feat['value'] );
                            }

                            $opts_counter ++;
                        }
                    }

                    $all_pkgs_d_arr = array();
                    if ( is_array($all_dyn_array) && sizeof($all_dyn_array) > 0 ) {
                        $all_pkgs_d_contr = 0;
                        foreach ( $all_dyn_array as $dynamic_data ) {
                            if ( isset($dynamic_data['field_type']) && isset($dynamic_data['field_label']) && isset($dynamic_data['field_value']) ) {
                                $d_type = $dynamic_data['field_type'];
                                $d_label = $dynamic_data['field_label'];
                                $d_value = '';
                                foreach ( $cust_query as $package_post ) {
                                    if ( isset($package_post) ) {
                                        $dynamic_package_data = get_post_meta($package_post, 'wp_dp_package_fields', true);
                                        if ( is_array($dynamic_package_data) && sizeof($dynamic_package_data) > 0 ) {
                                            foreach ( $dynamic_package_data as $dynamic_data2 ) {
                                                if ( isset($dynamic_data2['field_label']) && isset($dynamic_data2['field_value']) && $dynamic_data2['field_label'] == $d_label ) {
                                                    $d_value = $dynamic_data2['field_value'];
                                                }
                                            }
                                        }
                                    }
                                    $all_pkgs_d_arr[$all_pkgs_d_contr][] = array( 'field_type' => $d_type, 'field_label' => $d_label, 'field_value' => $d_value );
                                }
                            }
                            $all_pkgs_d_contr ++;
                        }
                    }

                    if ( is_array($all_pkgs_arr) && sizeof($all_pkgs_arr) > 0 ) {
                        $package_table = '<table class="pckgs-table">';
                        $package_table .= '<thead>';
                        $package_table .= '<tr>';
                        $package_table .= '<td>&nbsp;</td>';
                        $pakgs_size = sizeof($all_pkgs_arr['package_title']);
                        foreach ( $all_pkgs_arr['package_title'] as $all_pkgs_title ) {
                            $package_table .= '<td>' . $all_pkgs_title . '</td>';
                        }
                        $package_table .= '</tr>';
                        $package_table .= '</thead>';
                        $package_table .= '<tbody>';
                        $package_table .= '<tr class="price-row">';
                        $package_table .= '<td><span>' . wp_dp_plugin_text_srt('wp_dp_listing_price') . '</span></td>';
                        $pkgs_price_contr = 0;
                        foreach ( $all_pkgs_arr['package_price'] as $all_pkgs_price ) {
                            $pkgs_type = isset($all_pkgs_arr['package_type'][$pkgs_price_contr]) ? $all_pkgs_arr['package_type'][$pkgs_price_contr] : '';
                            if ( $pkgs_type == 'paid' ) {
                                $package_table .= '<td><strong>' . wp_dp_get_currency($all_pkgs_price, true) . '</strong></td>';
                            } else {
                                $package_table .= '<td><strong>' . wp_dp_plugin_text_srt('wp_dp_listing_free') . '</strong></td>';
                            }
                            $pkgs_price_contr ++;
                        }
                        $package_table .= '</tr>';
                        $package_table .= '<tr class="has-bg">';
                        $package_table .= '<td colspan="' . ($pakgs_size + 1) . '"><label class="pkg-inner-title">' . wp_dp_plugin_text_srt('wp_dp_listing_packages') . '</td>';
                        $package_table .= '</tr>';
                        $package_table .= '<tr>';
                        $package_table .= '</tr>';
                        $package_table .= '<tr>';
                        $package_table .= '<td><span>' . wp_dp_plugin_text_srt('wp_dp_listing_total_listings') . '</span></td>';
                        foreach ( $all_pkgs_arr['total_listings'] as $all_pkgs_lists ) {
                            $package_table .= '<td><span>' . absint($all_pkgs_lists) . '</span></td>';
                        }
                        $package_table .= '</tr>';
                        $package_table .= '<tr class="has-bg">';
                        $package_table .= '<td colspan="' . ($pakgs_size + 1) . '"><label class="pkg-inner-title">' . wp_dp_plugin_text_srt('wp_dp_listing_listing_listings') . '</td>';
                        $package_table .= '</tr>';
                        $package_table .= '<tr>';
                        $package_table .= '<td><span>' . wp_dp_plugin_text_srt('wp_dp_listing_listing_duration') . '</span></td>';
                        foreach ( $all_pkgs_arr['listing_duration'] as $all_pkgs_list_dur ) {
                            $package_table .= '<td><span>' . absint($all_pkgs_list_dur) . ' ' . wp_dp_plugin_text_srt('wp_dp_listing_days') . '</span></td>';
                        }
                        $package_table .= '</tr>';
                        $package_table .= '<tr>';
                        $package_table .= '<td><span>' . wp_dp_plugin_text_srt('wp_dp_listing_feature_listings') . '</span></td>';
                        foreach ( $all_pkgs_arr['featured'] as $all_pkgs_feat ) {
                            $package_table .= '<td>' . ($all_pkgs_feat == 'on' ? '<i class="icon-check2"></i>' : '<i class="icon-minus"></i>') . '</td>';
                        }
                        $package_table .= '</tr>';
                        $package_table .= '<tr>';
                        $package_table .= '<td><span>' . wp_dp_plugin_text_srt('wp_dp_listing_top_categories') . '</span></td>';
                        foreach ( $all_pkgs_arr['top_category'] as $all_pkgs_top_cat ) {
                            $package_table .= '<td>' . ($all_pkgs_top_cat == 'on' ? '<i class="icon-check2"></i>' : '<i class="icon-minus"></i>') . '</td>';
                        }
                        $package_table .= '</tr>';
                        $package_table .= '<tr class="has-bg">';
                        $package_table .= '<td colspan="' . ($pakgs_size + 1) . '"><label class="pkg-inner-title">' . wp_dp_plugin_text_srt('wp_dp_listing_features') . '</td>';
                        $package_table .= '</tr>';
                        $package_table .= '<tr>';

                        foreach ( $all_pkgs_arr['feature_fields'] as $all_pkgs_fields ) {
                            $package_table .= '<tr>';
                            $pckg_field_contr = 0;
                            foreach ( $all_pkgs_fields as $pckg_field ) {
                                if ( $pckg_field_contr == 0 ) {
                                    $package_table .= '<td><span>' . $pckg_field['title'] . '</span></td>';
                                }
                                if ( $pckg_field['value'] == 'on' ) {
                                    $package_table .= '<td><i class="icon-check2"></i></td>';
                                } else if ( $pckg_field['value'] != '' && $pckg_field['value'] != 'on' && $pckg_field['value'] != 'off' ) {
                                    $package_table .= '<td><span>' . $pckg_field['value'] . '</span></td>';
                                } else {
                                    $package_table .= '<td><i class="icon-minus"></i></td>';
                                }
                                $pckg_field_contr ++;
                            }
                            $package_table .= '</tr>';
                        }
                        if ( is_array($all_pkgs_d_arr) && sizeof($all_pkgs_d_arr) > 0 ) {

                            foreach ( $all_pkgs_d_arr as $dynamic_data_d ) {
                                $pckg_field_contr = 0;
                                $package_table .= '<tr>';
                                foreach ( $dynamic_data_d as $dyna_data_d ) {
                                    if ( isset($dyna_data_d['field_type']) && isset($dyna_data_d['field_label']) && isset($dyna_data_d['field_value']) ) {
                                        $d_type = $dyna_data_d['field_type'];
                                        $d_label = $dyna_data_d['field_label'];
                                        $d_value = $dyna_data_d['field_value'];

                                        if ( $pckg_field_contr == 0 ) {
                                            $package_table .= '<td><span>' . $d_label . '</span></td>';
                                        }

                                        if ( $d_value == 'on' && $d_type == 'single-choice' ) {
                                            $package_table .= '<td><span><i class="icon-check2"></i></span></td>';
                                        } else if ( $d_value != '' && $d_type != 'single-choice' ) {
                                            $package_table .= '<td><span>' . $d_value . '</span></td>';
                                        } else {
                                            $package_table .= '<td><span><i class="icon-minus"></i></span></td>';
                                        }
                                        $pckg_field_contr ++;
                                    }
                                }
                                $package_table .= '</tr>';
                            }
                        }
                        $package_table .= '</tbody>';
                        $package_table .= '<tfoot>';
                        $package_table .= '<tr>';
                        $package_table .= '<td>&nbsp;</td>';
                        foreach ( $all_pkgs_arr['package_id'] as $all_pkgs_id ) {
                            $package_type = get_post_meta($all_pkgs_id, 'wp_dp_package_type', true);

                            $package_data_all = get_post_meta($all_pkgs_id, 'wp_dp_package_data', true);
                            $package_image_nums = isset($package_data_all['number_of_pictures']['value']) ? $package_data_all['number_of_pictures']['value'] : 0;
                            $package_doc_nums = isset($package_data_all['number_of_documents']['value']) ? $package_data_all['number_of_documents']['value'] : 0;
                            $package_table .= '
							<td>
								<input type="radio" class="table-pckges" id="package-' . $all_pkgs_id . '" style="display: none;" name="wp_dp_listing_package"' . (isset($buying_pkg_id) && $buying_pkg_id == $all_pkgs_id ? ' checked="checked"' : '') . ' value="' . $all_pkgs_id . '" >
								<a href="javascript:void(0)" class="listing-pkg-select ' . (is_user_logged_in() ? 'dev-listing-pakcge-step' : 'dev-listing-pakcge-step') . '" data-picnum="' . $package_image_nums . '" data-docnum="' . $package_doc_nums . '" data-main-id="' . $listing_add_counter . '" data-id="' . $all_pkgs_id . '" data-ptype="buy" data-ppric="' . $package_type . '">' . wp_dp_plugin_text_srt('wp_dp_listing_select') . '</a>
								<span id="pkg-selected-' . $all_pkgs_id . '" class="pkg-selected" style="display: ' . (isset($buying_pkg_id) && $buying_pkg_id == $all_pkgs_id ? 'block' : 'none') . ';"><i class="icon-check_circle"></i></span>
							</td>';
                        }
                        $package_table .= '</tr>';
                        $package_table .= '</tfoot>';
                        $package_table .= '</table>';
                    }

                    $packages_list_opts .= $package_table;

                    $packages_list_opts .= '</div>';

                    $packages_list .= '<div class="packages-main-holder">';

                    if ( $subscribed_active_pkgs ) {
                        $packages_list .= '
						<div id="purchased-package-head-' . $listing_add_counter . '" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
							<div class="dashboard-element-title">
								<strong>' . wp_dp_plugin_text_srt('wp_dp_listing_purchased_packages') . '</strong>
							</div>
						</div>';
                    }

                    $pckage_title = get_the_title($selected_type_id);

                    $packages_list .= '
					<div id="buy-package-head-' . $listing_add_counter . '" style="display:' . $new_pkgs_visibility . ';" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
						<div class="dashboard-element-title">
							<strong>' . wp_dp_plugin_text_srt('wp_dp_listing_buy_package') . '</strong>
						</div>
					</div>';
                    if ( ! is_user_logged_in() ) {
                        $packages_list .= '<input type="checkbox" checked="checked" style="display:none;" name="wp_dp_listing_new_package_used">';
                    }
                    if ( true === Wp_dp_Member_Permissions::check_permissions('packages') ) {
                        if ( $subscribed_active_pkgs ) {
                            $packages_list .= '
							<div class="buy-new-pakg-actions">
								<input type="checkbox" style="display:none;" id="wp-dp-dev-new-pkg-checkbox-' . $listing_add_counter . '" name="wp_dp_listing_new_package_used">
								<label for="new-pkg-btn-' . $listing_add_counter . '">
									<a id="wp-dp-dev-new-pkg-btn-' . $listing_add_counter . '" class="dir-switch-packges-btn" data-id="' . $listing_add_counter . '" href="javascript:void(0);">' . wp_dp_plugin_text_srt('wp_dp_listing_buy_new_package') . '</a>
								</label>
								<a data-id="' . $listing_add_counter . '" style="display:' . $listing_hide_btn . ';" href="javascript:void(0);" class="wp-dp-dev-cancel-pkg"><i class="icon-cross"></i></a>
							</div>';
                        } else {
                            $packages_list .= '<input type="checkbox" checked="checked" style="display:none;" name="wp_dp_listing_new_package_used">';
                            $packages_list .= '
							<div class="buy-new-pakg-actions" style="display:' . $listing_hide_btn . ';">
								<a data-id="' . $listing_add_counter . '" href="javascript:void(0);" class="wp-dp-dev-cancel-pkg"><i class="icon-cross"></i></a>
							</div>';
                        }
                    }

                    $packages_list .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                    if ( $subscribed_active_pkgs ) {
                        $packages_list .= '<div id="purchased-packages-' . $listing_add_counter . '" class="dir-purchased-packages">' . $subscribed_active_pkgs . '</div>';
                    }
                    $packages_list .= '<div id="new-packages-' . $listing_add_counter . '" style="display:' . $new_pkgs_visibility . ';" class="dir-new-packages">' . $packages_list_opts . '</div>';
                    $packages_list .= '</div>';
                    $packages_list .= '</div>';
                }
            }

            if ( $show_li ) {
                $html .= '
				<li id="listing-packages-sec-' . $listing_add_counter . '" style="display: ' . $listing_up_visi . ';">
					<div class="row">
						' . $packages_list . '
					</div>
				</li>';
            }
            if ( isset($_POST['p_listing_typ']) && $_POST['p_listing_typ'] != '' ) {
                return $html;
            } else {
                echo force_balance_tags($html);
            }
        }

        /**
         * Listing Floor_plans
         * @return markup
         */
        public function listing_floor_plans($type_id = '', $wp_dp_id = '') {
            global $listing_add_counter, $wp_dp_plugin_options, $wp_dp_form_fields_frontend;
            $currency_sign = isset($wp_dp_plugin_options['wp_dp_currency_sign']) ? $wp_dp_plugin_options['wp_dp_currency_sign'] : '$';
            $html = '';
            $wp_dp_listing_floor_plans = get_post_meta($type_id, 'wp_dp_floor_plans_options_element', true);
            $rand_id = rand(100000000, 999999999);
            $floor_plans_list = '';
            $floor_placeholder_display = 'block';

            $get_listing_floor_plans = get_post_meta($wp_dp_id, 'wp_dp_floor_plans', true);
            if ( is_array($get_listing_floor_plans) && sizeof($get_listing_floor_plans) > 0 ) {
                foreach ( $get_listing_floor_plans as $img_item ) {
                    $file_attachm = isset($img_item['floor_plan_image']) ? $img_item['floor_plan_image'] : '';
                    $floor_plan_title = isset($img_item['floor_plan_title']) ? $img_item['floor_plan_title'] : '';
                    $floor_plan_desc = isset($img_item['floor_plan_description']) ? $img_item['floor_plan_description'] : '';

                    $img_url_arr = wp_get_attachment_image_src($file_attachm, 'wp_dp_media_3');
                    $img_url = isset($img_url_arr[0]) ? $img_url_arr[0] : '';
                    $rand_img_id = rand(100000000, 999999999);
                    ob_start();
                    ?>
                    <div class="modal fade modal-form" id="add-floor-image-data-<?php echo esc_html($rand_img_id); ?>" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close close-faq" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="faqModalLabel"><?php echo wp_dp_plugin_text_srt('wp_dp_edit_details'); ?></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="field-holder">
                    <?php
                    $wp_dp_opt_array = array(
                        'id' => 'listing_floor_plan_title',
                        'cust_name' => 'wp_dp_listing_floor_plan_title[]',
                        'classes' => 'form-control',
                        'std' => $floor_plan_title,
                        'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_title') . ' *"',
                    );
                    $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                    ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="field-holder">
                    <?php
                    $wp_dp_opt_array = array(
                        'id' => 'wp_dp_listing_floor_plan_desc',
                        'cust_name' => 'wp_dp_listing_floor_plan_desc[]',
                        'classes' => 'form-control',
                        'std' => $floor_plan_desc,
                        'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_floor_description') . ' *"',
                    );
                    $wp_dp_form_fields_frontend->wp_dp_form_textarea_render($wp_dp_opt_array);
                    ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="field-holder faq-request-holder input-button-loader">
                    <?php
                    $wp_dp_opt_array = array(
                        'std' => wp_dp_plugin_text_srt('wp_dp_edit_details_update'),
                        'id' => 'add_floor_plan_data',
                        'cust_name' => 'add_floor_plan_data',
                        'return' => false,
                        'classes' => 'bgcolor wp_dp_add_floor_plan_data',
                        'cust_type' => 'button',
                        'extra_atr' => 'data-id="' . $rand_img_id . '"',
                        'force_std' => true,
                    );
                    $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                    ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $poup_html = ob_get_clean();


                    $floor_plans_list .= '
					<li class="gal-img">
						<div class="drag-list">
							<div class="item-thumb">
								<a data-target="#add-floor-image-data-' . $rand_img_id . '" data-toggle="modal" class="edit-floor-data-btn edit-btn-link" href="javascript:void(0);"><i class="icon-mode_edit"></i></a>
								<img class="thumbnail" src="' . $img_url . '" alt=""/>
								<div class="add-floor-data-link-' . $rand_img_id . ' block-popup-data">' . $floor_plan_title . '</div>
							</div>
							<div class="item-assts">
								<div class="list-inline pull-right">
									<div class="close-btn" data-id="' . $listing_add_counter . '"><a href="javascript:void(0);"><i class="icon-cross"></i></a></div>
								</div>';
                    $wp_dp_opt_array = array(
                        'std' => $file_attachm,
                        'cust_id' => '',
                        'cust_name' => 'wp_dp_listing_floor_plan_image[]',
                        'cust_type' => 'hidden',
                        'classes' => '',
                        'return' => true,
                    );
                    $floor_plans_list .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                    $floor_plans_list .= '</div>
						</div>
					' . $poup_html . '</li>';
                }
                $floor_placeholder_display = 'none';
            }

            $html .= '
			<li id="wp-dp-listing-floor-plans-holder" class="wp-dp-dev-appended" style="display: ' . ($wp_dp_listing_floor_plans == 'on' ? 'block' : 'none') . ';">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="dashboard-element-title">
						<label>' . wp_dp_plugin_text_srt('wp_dp_design_skeches_element') . '</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="field-holder upload-area">
						<ul id="wp-dp-dev-floor-attach-sec-' . $listing_add_counter . '" class="wp-dp-gallery-holder">
							' . $floor_plans_list . '
						</ul>
						<div class="gal-img-add">
							<div id="upload-floor-' . $listing_add_counter . '" class="upload-gallery">
								<a href="javascript:void(0);" class="upload-btn wp-dp-dev-floor-upload-btn" data-id="' . $listing_add_counter . '"><span><i class="icon-upload-gallery"></i> ' . wp_dp_plugin_text_srt('wp_dp_listing_upload_image') . '</span></a>
							</div>
						</div>';
            $wp_dp_opt_array = array(
                'std' => '',
                'cust_id' => 'floor-uploader-' . $listing_add_counter,
                'cust_name' => 'wp_dp_listing_floor_images[]',
                'cust_type' => 'file',
                'classes' => 'wp-dp-dev-floor-uploader wp_dp_listing_floor_images',
                'return' => true,
                'extra_atr' => 'style="display:none;" multiple="multiple" onchange="wp_dp_handle_floor_file_select(event, \'' . $listing_add_counter . '\')"',
            );
            $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
            $html .= '</div>
				</div>
				<script>
				jQuery(document).ready(function ($) {
					$("#wp-dp-dev-floor-attach-sec-' . $listing_add_counter . '").sortable({
						handle: \'.drag-list\',
						cursor: \'move\',
						items : \'.gal-img\',

					});
				});
				</script>
			</div>
			</li>';
            return apply_filters('wp_dp_front_listing_add_floor_plans', $html, $type_id, $wp_dp_id);
            // usage :: add_filter('wp_dp_front_listing_add_floor_plans', 'my_callback_function', 10, 3);
        }

        /**
         * Opening Hours
         * @return markup
         */
        public function listing_opening_hours($type_id = '', $wp_dp_id = '') {
            global $listing_add_counter;
            $html = '';
            $wp_dp_listing_opening_hours = get_post_meta($type_id, 'wp_dp_opening_hours_element', true);

            if ( $wp_dp_id != '' && $wp_dp_id != 0 && $this->is_member_listing($wp_dp_id) ) {
                $is_updating = true;
            } else {
                $is_updating = false;
            }

            $time_list = $this->listing_time_list($type_id);

            $week_days = $this->listing_week_days();

            $time_from_html = '';
            $time_to_html = '';
            $off_days_list = '';
            // In case of changing wp_dp type ajax
            // it will load the pre filled data
            $get_listing_form_select_type = wp_dp_get_input('select_type', '', 'STRING');
            if ( $get_listing_form_select_type != '' ) {
                $get_opening_hours = wp_dp_get_input('wp_dp_opening_hour', '', 'ARRAY');
            }
            // end ajax loading

            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $get_opening_hours = get_post_meta($get_listing_id, 'wp_dp_opening_hours', true);
            } else {
                if ( is_array($time_list) && sizeof($time_list) > 0 ) {
                    foreach ( $time_list as $time_key => $time_val ) {
                        $time_from_html .= '<option value="' . $time_key . '" ' . ('09:00 am' == $time_key ? ' selected="selected"' : '') . '>' . $time_val . '</option>' . "\n";
                        $time_to_html .= '<option value="' . $time_key . '" ' . ('06:00 pm' == $time_key ? ' selected="selected"' : '') . '>' . $time_val . '</option>' . "\n";
                    }
                }
            }



            $wp_dp_off_days = get_post_meta($type_id, 'wp_dp_off_days', true);

            // In case of changing wp_dp type ajax
            // it will load the pre filled data
            $get_listing_form_select_type = wp_dp_get_input('select_type', '', 'STRING');
            if ( $get_listing_form_select_type != '' ) {
                $get_listing_form_days_off = wp_dp_get_input('wp_dp_listing_off_days', '', 'ARRAY');
                if ( is_array($get_listing_form_days_off) && sizeof($get_listing_form_days_off) ) {
                    foreach ( $get_listing_form_days_off as $get_off_day ) {
                        $off_days_list .= $this->append_to_book_days_off($get_off_day);
                    }
                }
            }
            // end ajax loading

            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $get_listing_off_days = get_post_meta($get_listing_id, 'wp_dp_calendar', true);
                if ( is_array($get_listing_off_days) && sizeof($get_listing_off_days) ) {
                    foreach ( $get_listing_off_days as $get_off_day ) {
                        $off_days_list .= $this->append_to_book_days_off($get_off_day);
                    }
                }
            }
            if ( $off_days_list == '' && $is_updating != true ) {
                $current_year = date('Y');
                $get_listing_off_days = array( $current_year . '-12-25', ($current_year + 1) . '-01-01' );
                if ( is_array($get_listing_off_days) && sizeof($get_listing_off_days) ) {
                    foreach ( $get_listing_off_days as $get_off_day ) {
                        $off_days_list .= $this->append_to_book_days_off($get_off_day);
                    }
                }
            }

            $days_html = '';
            if ( is_array($week_days) && sizeof($week_days) > 0 ) {
                $days_html .= '<li class="opening-hours-heading">
						<div class="open-close-time opening-time">
							<div class="day-sec">
								<span>' . wp_dp_plugin_text_srt('wp_dp_listing_days') . '</span>
							</div>
							<div class="time-sec">
								<span>' . wp_dp_plugin_text_srt('wp_dp_times') . '</span>
							</div>
						</div>
					</li>';
                foreach ( $week_days as $day_key => $week_day ) {

                    $day_status = isset($get_opening_hours[$day_key]['day_status']) ? $get_opening_hours[$day_key]['day_status'] : '';
                    if ( isset($get_opening_hours) && is_array($get_opening_hours) && sizeof($get_opening_hours) > 0 ) {

                        $opening_time = isset($get_opening_hours[$day_key]['opening_time']) ? $get_opening_hours[$day_key]['opening_time'] : '';
                        $closing_time = isset($get_opening_hours[$day_key]['closing_time']) ? $get_opening_hours[$day_key]['closing_time'] : '';

                        if ( is_array($time_list) && sizeof($time_list) > 0 ) {
                            $time_from_html = '';
                            $time_to_html = '';
                            foreach ( $time_list as $time_key => $time_val ) {
                                $time_from_html .= '<option value="' . $time_key . '"' . ($opening_time == $time_key ? ' selected="selected"' : '') . '>' . date_i18n('g:i a', strtotime($time_val)) . '</option>' . "\n";
                                $time_to_html .= '<option value="' . $time_key . '"' . ($closing_time == $time_key ? ' selected="selected"' : '') . '>' . date_i18n('g:i a', strtotime($time_val)) . '</option>' . "\n";
                            }
                        }
                    }

                    if ( $is_updating != true && $day_key != 'saturday' && $day_key != 'sunday' ) {
                        $day_status = 'on';
                    }

                    $days_html .= '
						<li>
							<div id="open-close-con-' . $day_key . '-' . $listing_add_counter . '" class="open-close-time' . (isset($day_status) && ($day_status == 'on') ? ' opening-time' : '') . '">
								<div class="day-sec">
									<span>' . $week_day . '</span>
								</div>
								<div class="time-sec">
									<select class="chosen-select " name="wp_dp_opening_hour[' . $day_key . '][opening_time]">
										' . $time_from_html . '
									</select>
									<span class="option-label">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_to') . '</span>
									<select class="chosen-select " name="wp_dp_opening_hour[' . $day_key . '][closing_time]">
										' . $time_to_html . '
									</select>
									<a id="wp-dp-dev-close-time-' . $day_key . '-' . $listing_add_counter . '" href="javascript:void(0);" data-id="' . $listing_add_counter . '" data-day="' . $day_key . '" title="' . wp_dp_plugin_text_srt('wp_dp_services_remove') . '">' . wp_dp_plugin_text_srt('wp_dp_services_remove') . '</a>
								</div>
								<div class="close-time">
									<a id="wp-dp-dev-open-time-' . $day_key . '-' . $listing_add_counter . '" href="javascript:void(0);" data-id="' . $listing_add_counter . '" data-day="' . $day_key . '">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_closed') . ' <span>(' . wp_dp_plugin_text_srt('wp_dp_member_add_list_click_open_hours') . ')</span></a>
									<input id="wp-dp-dev-open-day-' . $day_key . '-' . $listing_add_counter . '" type="hidden" name="wp_dp_opening_hour[' . $day_key . '][day_status]"' . (isset($day_status) && ($day_status == 'on') ? ' value="on"' : '') . '>
								</div>
							</div>
						</li>';
                }
            }
            $holiday_heading = '';
            if(isset($off_days_list) && $off_days_list!= ''){
                $holiday_heading = wp_dp_plugin_text_srt('wp_dp_member_add_list_holidays');
            }
            $html .= '
				<div class="wp-dp-dev-appended opening-hours-holder">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="field-holder">
								<div class="time-list">
									<ul>
										' . $days_html . '
									</ul>
								</div>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="field-holder">
								<div class="book-list">
                                                                <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="dashboard-element-title">
                                                                                <strong>'.$holiday_heading.'</strong>
                                                                        </div>
                                                                </div>
                                                                </div>
									<ul id="wp-dp-dev-add-off-day-app-' . $listing_add_counter . '">
                                                                                ' . $off_days_list . '
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>';

            return apply_filters('wp_dp_front_listing_add_open_hours', $html, $type_id, $wp_dp_id);
            // usage :: add_filter('wp_dp_front_listing_add_open_hours', 'my_callback_function', 10, 3);
        }

        /**
         * Set Book Days off
         * @return markup
         */
        public function listing_book_days_off($type_id = '', $wp_dp_id = '') {
            global $listing_add_counter;
            $html = '';
            wp_enqueue_script('responsive-calendar');
            $html .= '<div class="fancy-bdr-holder">' . "\n";
            $html .= '<div class="fancy-bdr-body">' . "\n";
            $html .= '<div class="listing-holidays">' . "\n" . '
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="image-holder"><figure><img src="' . wp_dp::plugin_url() . 'assets/frontend/images/holiday-clock-img.png" alt=""></figure></div>
						<strong>' . wp_dp_plugin_text_srt('wp_dp_add_new_listing_holiday') . '</strong>
						<p>' . wp_dp_plugin_text_srt('wp_dp_add_new_listing_holiday_text') . '</p>
						<div class="dashboard-element-title">
							<div id="dev-off-day-loader-' . $listing_add_counter . '" class="listing-loader"></div>
							<a class="book-btn" href="javascript:void(0);"><i class="icon-plus"></i>' . wp_dp_plugin_text_srt('wp_dp_add_listing_holidays') . '</a>
							<div id="wp-dp-dev-cal-holder-' . $listing_add_counter . '" class="calendar-holder">
								<div data-id="' . $listing_add_counter . '" class="wp-dp-dev-insert-off-days responsive-calendar">
									<span class="availability">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_availability') . '</span>
									<div class="controls">
										<a data-go="prev"><div class="btn btn-primary"><i class="icon-angle-left"></i></div></a>
										<strong><span data-head-month></span> <span data-head-year></span></strong>
										<a data-go="next"><div class="btn btn-primary"><i class="icon-angle-right"></i></div></a>
									</div>
									<div class="day-headers">
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_mon') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_tue') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_wed') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_thu') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_fri') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_sat') . '</div>
										<div class="day header">' . wp_dp_plugin_text_srt('wp_dp_member_add_list_sun') . '</div>
									</div>
									<div class="days wp-dp-dev-calendar-days" data-group="days"></div>
								</div>
							</div>
						</div>
						<script type="text/javascript">
						jQuery( document ).ready(function() {
							jQuery(".responsive-calendar").responsiveCalendar({
								time: "' . date('Y-m') . '",
								monthChangeAnimation: false,
								"' . date('Y-m-d') . '": {
									number: 5,
									url: ""
								}
							});
						});
						</script>
					</div>
				</div>
				</div>';
            $html .= '</div>' . "\n";
            $html .= '<div class="bdr-footer">' . "\n";
            $html .= '<img src="' . wp_dp::plugin_url() . 'assets/frontend/images/add-listing-footer-img.png" alt="">';
            $html .= '</div>' . "\n";
            $html .= '</div>' . "\n";
            return apply_filters('wp_dp_front_listing_add_book_off_days', $html, $type_id, $wp_dp_id);
            // usage :: add_filter('wp_dp_front_listing_add_book_off_days', 'my_callback_function', 10, 3);
        }

        /**
         * Appending off days to list via Ajax
         * @return markup
         */
        public function append_to_book_days_off($get_off_day = '') {

            if ( $get_off_day != '' ) {
                $book_off_date = $get_off_day;
            } else {
                $day = wp_dp_get_input('off_day_day', date('d'), 'STRING');
                $month = wp_dp_get_input('off_day_month', date('m'), 'STRING');
                $year = wp_dp_get_input('off_day_year', date('Y'), 'STRING');
                $book_off_date = $year . '-' . $month . '-' . $day;
            }
            $formated_off_date_day = date_i18n("l", strtotime($book_off_date));
            $formated_off_date = date_i18n(get_option('date_format'), strtotime($book_off_date));

            $rand_numb = rand(100000000, 999999999);

            $html = '<li id="day-dpove-' . $rand_numb . '">
				<div class="open-close-time opening-time">
					<div class="date-sec">
						<span>' . $formated_off_date_day . '</span>
						<input type="hidden" value="' . $book_off_date . '" name="wp_dp_listing_off_days[]">
					</div>
					<div class="time-sec"><em>' . $formated_off_date . '</em>
						<a id="wp-dp-dev-day-off-dp-' . $rand_numb . '" data-id="' . $rand_numb . '" href="javascript:void(0);">' . wp_dp_plugin_text_srt('wp_dp_services_remove') . '</a>
					</div>
				</div>
			</li>';

            if ( $get_off_day != '' ) {
                return apply_filters('wp_dp_front_listing_add_single_off_day', $html, $get_off_day);
                // usage :: add_filter('wp_dp_front_listing_add_single_off_day', 'my_callback_function', 10, 2);
            } else {
                echo json_encode(array( 'html' => $html ));
                die;
            }
        }

        public function listing_attachments($type_id = '', $wp_dp_id = '') {
            global $listing_add_counter, $wp_dp_plugin_options, $wp_dp_form_fields_frontend;

            if ( $wp_dp_id != '' && $wp_dp_id != 0 && $this->is_member_listing($wp_dp_id) ) {
                $is_updating = true;
            } else {
                $is_updating = false;
            }

            $html = '';
            $wp_dp_attachments_options = get_post_meta($type_id, 'wp_dp_attachments_options_element', true);
            $trans_all_meta = get_post_meta($wp_dp_id, 'wp_dp_trans_all_meta', true);
            $num_doc_allows = isset($trans_all_meta[1]['value']) ? $trans_all_meta[1]['value'] : 0;

            $attacment_sec_items = '';

            $allowd_attachment_extensions = get_post_meta($type_id, 'wp_dp_listing_allowd_attachment_extensions', true);
            $allowd_attachment_extensions = isset($allowd_attachment_extensions) ? $allowd_attachment_extensions : '';
            if ( isset($allowd_attachment_extensions) && $allowd_attachment_extensions != '' ) {
                $allowd_attachment_extensions = implode(',', $allowd_attachment_extensions);
            }

            if ( $allowd_attachment_extensions == '' ) {
                $allowd_attachment_extensions = 'jpg,jpeg,pdf,doc,docx';
            }

            $selected_package_id = wp_dp_get_input('package_id', 0);
            if ( $is_updating === true ) {
                $num_doc_allows = get_post_meta($wp_dp_id, 'wp_dp_transaction_listing_doc_num', true);
            } elseif ( $selected_package_id != 0 ) {
                $package_fields = get_post_meta($selected_package_id, 'wp_dp_package_data', true);
                $num_doc_allows = isset($package_fields['number_of_documents']['value']) ? $package_fields['number_of_documents']['value'] : 0;
            }

            $rand_id = rand(100000000, 999999999);
            $attachments_list = '';
            $attachments_placeholder_display = 'block';

            $get_listing_attachments = get_post_meta($wp_dp_id, 'wp_dp_attachments', true);
            if ( is_array($get_listing_attachments) && sizeof($get_listing_attachments) > 0 ) {
                foreach ( $get_listing_attachments as $img_item ) {
                    $file_attachm = isset($img_item['attachment_file']) ? $img_item['attachment_file'] : '';
                    $attachment_title = isset($img_item['attachment_title']) ? $img_item['attachment_title'] : '';
                    $file_attachm_url = wp_get_attachment_url($file_attachm);
                    $dot_array = explode('.', $file_attachm_url);
                    $file_attachm_url_ext = end($dot_array);
                    $img_url = wp_dp::plugin_url() . '/assets/common/attachment-images/attach-' . $file_attachm_url_ext . '.png';

                    $rand_img_id = rand(100000000, 999999999);
                    ob_start();
                    ?>
                    <div class="modal fade modal-form" id="add-attachment-data-<?php echo esc_html($rand_img_id); ?>" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close close-faq" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="faqModalLabel"><?php echo wp_dp_plugin_text_srt('wp_dp_edit_details'); ?></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="field-holder">
                    <?php
                    $wp_dp_opt_array = array(
                        'id' => 'listing_attachment_title',
                        'cust_name' => 'wp_dp_listing_attachment_title[]',
                        'classes' => 'form-control',
                        'std' => $attachment_title,
                        'extra_atr' => 'placeholder="' . wp_dp_plugin_text_srt('wp_dp_title') . ' *"',
                    );
                    $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                    ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="field-holder faq-request-holder input-button-loader">
                    <?php
                    $wp_dp_opt_array = array(
                        'std' => wp_dp_plugin_text_srt('wp_dp_edit_details_update'),
                        'id' => 'add_attachment_data',
                        'cust_name' => 'add_attachment_data',
                        'return' => false,
                        'classes' => 'bgcolor wp_dp_add_attachment_data',
                        'cust_type' => 'button',
                        'extra_atr' => 'data-id="' . $rand_img_id . '"',
                        'force_std' => true,
                    );
                    $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                    ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $poup_html = ob_get_clean();

                    $attacment_sec_items .= '
					<li class="gal-img">
						<div class="drag-list">
							<div class="item-thumb"><a data-target="#add-attachment-data-' . $rand_img_id . '" data-toggle="modal" class="edit-attachment-btn edit-btn-link" href="javascript:void(0);"><i class="icon-mode_edit"></i></a><img class="thumbnail" src="' . $img_url . '" alt=""/>
                                                            <div class="attachment-data-link-' . $rand_img_id . ' block-popup-data">' . $attachment_title . '</div>
                                                        </div>
							<div class="item-assts">
								<div class="list-inline pull-right">
									<div class="close-btn" data-id="' . $listing_add_counter . '"><a href="javascript:void(0);"><i class="icon-cross"></i></a></div>
								</div>';
                    $wp_dp_opt_array = array(
                        'std' => $file_attachm,
                        'cust_id' => '',
                        'cust_name' => 'wp_dp_listing_attachment_file[]',
                        'cust_type' => 'hidden',
                        'classes' => '',
                        'return' => true,
                    );
                    $attacment_sec_items .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                    $attacment_sec_items .= '</div>
						</div>
					' . $poup_html . '</li>';
                }
                $attachments_placeholder_display = 'none';
            }

            $html .= '
			<li id="wp-dp-listing-attachments-holder" class="wp-dp-dev-appended" style="display: ' . ($wp_dp_attachments_options == 'on' ? 'block' : 'none') . ';">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="dashboard-element-title">
						<label>
							' . wp_dp_plugin_text_srt('wp_dp_listing_file_documents') . '
							<span class="info-text">(' . sprintf(wp_dp_plugin_text_srt('wp_dp_upload_gallery_images_placeholder'), $num_doc_allows) . ')</span>
						</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="field-holder upload-area">
						<ul id="wp-dp-dev-docs-attach-sec-' . $listing_add_counter . '" class="wp-dp-gallery-holder" data-allow-ext="' . $allowd_attachment_extensions . '" data-ext-error="' . sprintf(wp_dp_plugin_text_srt('wp_dp_listing_extention_error'), str_replace(',', ', ', $allowd_attachment_extensions)) . '">
							' . $attacment_sec_items . '
						</ul>
						<div class="gal-img-add">
							<div id="upload-attachment-' . $listing_add_counter . '" class="upload-gallery">
								<a href="javascript:void(0);" class="upload-btn wp-dp-dev-attachment-upload-btn" data-id="' . $listing_add_counter . '"><span><i class="icon-upload-gallery"></i> ' . wp_dp_plugin_text_srt('wp_dp_listing_upload_file') . '</span></a>
							</div>
						</div>';
            $wp_dp_opt_array = array(
                'std' => '',
                'cust_id' => 'attachment-uploader-' . $listing_add_counter,
                'cust_name' => 'wp_dp_listing_attachment_images[]',
                'cust_type' => 'file',
                'classes' => 'wp-dp-dev-gallery-uploader wp_dp_listing_attachment_images',
                'return' => true,
                'extra_atr' => 'style="display:none;" data-count="' . $num_doc_allows . '" multiple="multiple" onchange="wp_dp_handle_attach_file_select(event, \'' . $listing_add_counter . '\')"',
            );
            $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
            $html .= '</div>
				</div>
				<script>
				jQuery(document).ready(function ($) {
					$("#wp-dp-dev-docs-attach-sec-' . $listing_add_counter . '").sortable({
						handle: \'.drag-list\',
						cursor: \'move\',
						items : \'.gal-img\',
					});
				});
				</script>
			</div>
			</li>';

            return apply_filters('wp_dp_front_listing_add_attachments', $html, $type_id, $wp_dp_id);
            // usage :: add_filter('wp_dp_front_listing_add_attachments', 'my_callback_function', 10, 3);
        }

        /**
         * Select Directory Box Featured
         * and Top Category
         * @return markup
         */
        public function listing_featured_top_cat($pckg_id = '', $trans_id = '') {
            global $listing_add_counter;

            $html = '';
            $listing_featured = '';
            $listing_top_cat = '';

            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $listing_top_cat = wp_dp_check_promotion_status($get_listing_id, 'top-categories');
            }

            $featured_num = '';
            $top_cat_num = '';
            if ( $pckg_id != '' && $trans_id == '' ) {
                $packg_data = get_post_meta($pckg_id, 'wp_dp_package_data', true);
                $featured_num = isset($packg_data['number_of_featured_listings']['value']) ? $packg_data['number_of_featured_listings']['value'] : '';
                $top_cat_num = isset($packg_data['number_of_top_cat_listings']['value']) ? $packg_data['number_of_top_cat_listings']['value'] : '';
            } else if ( $pckg_id != '' && $trans_id != '' ) {
                if ( $user_package = $this->get_user_package_trans($pckg_id, $trans_id) ) {

                    $featured_num = get_post_meta($trans_id, 'wp_dp_transaction_listing_feature_list', true);

                    $top_cat_num = get_post_meta($trans_id, 'wp_dp_transaction_listing_top_cat_list', true);
                }
            }

            if ( $featured_num != 'on' && $top_cat_num != 'on' ) {
                return apply_filters('wp_dp_listing_add_featured_top_cat', $html, $pckg_id, $trans_id);
            }

            $html .= '
				</div>
			</div>';

            return apply_filters('wp_dp_listing_add_featured_top_cat', $html, $pckg_id, $trans_id);
        }

        /**
         * Terms and Conditions
         * and Submit Button
         * @return markup
         */
        public function listing_submit_button() {
            global $listing_add_counter, $wp_dp_form_fields_frontend;
            $check_box = '';
            $get_listing_id = wp_dp_get_input('listing_id', 0);
            $btn_text = wp_dp_plugin_text_srt('wp_dp_listing_proceed');
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $btn_text = wp_dp_plugin_text_srt('wp_dp_listing_update_ad');
            } else {
                $check_box = '
					<div class="checkbox-area">';
                $wp_dp_opt_array = array(
                    'std' => '',
                    'cust_id' => 'terms-' . $listing_add_counter,
                    'cust_name' => 'terms-' . $listing_add_counter,
                    'cust_type' => 'checkbox',
                    'classes' => 'wp-dp-dev-req-field',
                    'return' => true,
                );
                $check_box .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                $check_box .= '<label for="terms-' . $listing_add_counter . '">' . wp_dp_plugin_text_srt('wp_dp_listing_terms') . '</label>
					</div>';
            }
            $html = '
				<li>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="btns-section">
								<div class="field-holder">
									<div class="payment-holder">
										' . $check_box . '
										<div class="next-btn-field">';
            $wp_dp_opt_array = array(
                'std' => $btn_text,
                'cust_id' => '',
                'cust_name' => '',
                'cust_type' => 'submit',
                'classes' => 'next-btn',
                'return' => true,
            );
            $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
            $html .= '</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</li>';
            echo force_balance_tags($html);
        }

        /**
         * Time List
         * @return array
         */
        public function listing_time_list($type_id = '') {

            $lapse = 15;

            $date = date("Y/m/d 12:00");
            $time = strtotime('12:00 am');
            $start_time = strtotime($date . ' am');
            $endtime = strtotime(date("Y/m/d h:i a", strtotime('1440 minutes', $start_time)));

            while ( $start_time < $endtime ) {
                $time = date("h:i a", strtotime('+' . $lapse . ' minutes', $time));
                $hours[$time] = $time;
                $time = strtotime($time);
                $start_time = strtotime(date("Y/m/d h:i a", strtotime('+' . $lapse . ' minutes', $start_time)));
            }

            return apply_filters('wp_dp_front_listing_add_time_list', $hours, $type_id);
        }

        /**
         * Week Days
         * @return array
         */
        public function listing_week_days() {

            $week_days = array(
                'monday' => wp_dp_plugin_text_srt('wp_dp_member_add_list_monday'),
                'tuesday' => wp_dp_plugin_text_srt('wp_dp_member_add_list_tuesday'),
                'wednesday' => wp_dp_plugin_text_srt('wp_dp_member_add_list_wednesday'),
                'thursday' => wp_dp_plugin_text_srt('wp_dp_member_add_list_thursday'),
                'friday' => wp_dp_plugin_text_srt('wp_dp_member_add_list_friday'),
                'saturday' => wp_dp_plugin_text_srt('wp_dp_member_add_list_saturday'),
                'sunday' => wp_dp_plugin_text_srt('wp_dp_member_add_list_sunday')
            );

            return apply_filters('wp_dp_front_listing_add_week_days', $week_days);
        }

        /**
         * Creating wp_dp listing
         * @return listing id
         */
        public function listing_insert($member_id = '') {
            global $wp_dp_plugin_options, $listing_add_counter;

            $wp_dp_free_listings_switch = isset($wp_dp_plugin_options['wp_dp_free_listings_switch']) ? $wp_dp_plugin_options['wp_dp_free_listings_switch'] : '';

            $listing_id = 0;
            $listing_title = isset($_POST['wp_dp_listing_title']) ? $_POST['wp_dp_listing_title'] : '';
            $listing_desc = isset($_POST['wp_dp_listing_desc']) ? $_POST['wp_dp_listing_desc'] : '';

            if ( $listing_title != '' && $listing_desc != '' && $member_id != '' ) {

                $form_rand_numb = isset($_POST['form_rand_id']) ? $_POST['form_rand_id'] : '';

                $listing_post = array(
                    'post_title' => wp_strip_all_tags($listing_title),
                    'post_content' => $listing_desc,
                    'post_status' => 'publish',
                    'post_type' => 'listings',
                    'post_date' => current_time('Y/m/d H:i:s', 1)
                );

                //insert post
                $listing_id = wp_insert_post($listing_post);

                $user_data = wp_get_current_user();
                update_post_meta($listing_id, 'wp_dp_listing_visibility', 'public');
            }

            return apply_filters('wp_dp_front_listing_add_create', $listing_id);
            // usage :: add_filter('wp_dp_front_listing_add_create', 'my_callback_function', 10, 1);
        }

        /**
         * Save wp_dp listing
         * @return
         */
        public function listing_meta_save() {
            global $current_user, $listing_add_counter;

            // Inser Listing.
            if ( is_user_logged_in() ) {
                $company_id = wp_dp_company_id_form_user_id($current_user->ID);
                $member_id = $company_id;
                $publish_user_id = $current_user->ID;
                $listing_id = $this->listing_insert($member_id);
            } else {
                $member_id = '';
                $listing_id = '';
                $get_username = wp_dp_get_input('wp_dp_listing_username', '', 'STRING');
                $get_useremail = wp_dp_get_input('wp_dp_listing_user_email', '', 'STRING');
                $reg_array = array(
                    'username' => $get_username,
                    'display_name' => $get_username,
                    'email' => $get_useremail,
                    'id' => $listing_add_counter,
                    'wp_dp_user_role_type' => 'member',
                    'key' => '',
                );
                if ( $this->is_form_submit() ) {
                    $member_data = wp_dp_registration_validation('', $reg_array);

                    if ( isset($member_data['type']) && $member_data['type'] == 'error' ) {
                        echo '<li><div class="row">' . $member_data['msg'] . '</div></li>';
                        return;
                    } else {
                        $member_id = isset($member_data[0]) ? $member_data[0] : '';
                        $publish_user_id = isset($member_data[1]) ? $member_data[1] : '';
                        $listing_id = $this->listing_insert($member_id);
                    }
                }
            }

            if ( $listing_id != '' && $listing_id != 0 && $this->is_form_submit() ) {

                // saving Listing posted date
                update_post_meta($listing_id, 'wp_dp_listing_posted', strtotime(current_time('Y/m/d H:i:s', 1)));

                // saving Listing Member
                update_post_meta($listing_id, 'wp_dp_listing_member', $member_id);
                if ( isset($publish_user_id) ) {
                    update_post_meta($listing_id, 'wp_dp_listing_username', $publish_user_id);
                }

                // updating company id
                $company_id = get_user_meta($member_id, 'wp_dp_company', true);
                update_post_meta($listing_id, 'wp_dp_listing_company', $company_id);

                // saving Listing Type
                $wp_dp_listing_type = wp_dp_get_input('wp_dp_listing_type', '');
                update_post_meta($listing_id, 'wp_dp_listing_type', $wp_dp_listing_type);

                // price save

                $html = '';
                $wp_dp_listing_price_options = wp_dp_get_input('wp_dp_listing_price_options', 'STRING');
                $wp_dp_listing_price = wp_dp_get_input('wp_dp_listing_price', 'STRING');

                update_post_meta($listing_id, 'wp_dp_listing_price_options', $wp_dp_listing_price_options);
                update_post_meta($listing_id, 'wp_dp_listing_price', $wp_dp_listing_price);

                $wp_dp_listing_special_price = wp_dp_get_input('wp_dp_listing_special_price', 'STRING');
                update_post_meta($listing_id, 'wp_dp_listing_special_price', $wp_dp_listing_special_price);


                // end price save

                $listing_cats_formate = 'single';

                $wp_dp_listing_category_array = wp_dp_get_input('wp_dp_listing_category', '', 'ARRAY');
                if ( ! empty($wp_dp_listing_category_array) && is_array($wp_dp_listing_category_array) ) {
                    foreach ( $wp_dp_listing_category_array as $cate_slug => $cat_val ) {
                        if ( $cat_val ) {
                            $term = get_term_by('slug', $cat_val, 'listing-category');

                            if ( isset($term->term_id) ) {
                                $cat_ids = array();
                                $cat_ids[] = $term->term_id;
                                $cat_slugs = $term->slug;
                                wp_set_post_terms($listing_id, $cat_ids, 'listing-category', FALSE);
                            }
                        }
                    }

                    update_post_meta($listing_id, 'wp_dp_listing_category', $wp_dp_listing_category_array);
                }

                // Check Free or Paid listing
                // Assign Package in case of paid
                // Assign Status of listing
                $this->listing_save_assignments($listing_id, $member_id);
            }
        }

        public function custom_fields_features() {
            global $listing_add_counter;
            $cus_fields_html = '';
            $main_append_html = '';
            $price_append_html = '';
            $tags_append_html = '';
            $listing_add_counter = isset($_POST['listing_add_counter']) ? $_POST['listing_add_counter'] : '';
            $select_type = isset($_POST['select_type']) ? $_POST['select_type'] : '';
            if ( $select_type != '' ) {
                $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$select_type", 'post_status' => 'publish', 'suppress_filters' => '0' ));
                $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;

                $member_add_listing_obj = new wp_dp_member_listing_actions();
                $cus_fields_html = '<div class="listing-cf-fields"><div class="row">' . $this->custom_fields($listing_type_id) . '</div></div>';
                $cats_append_html = $this->listing_categories($select_type);
                $price_append_html = $this->listing_price($select_type);
				$features_append_html = $this->listing_features_list($listing_type_id);
            }

            $listing_type = get_page_by_path($select_type, OBJECT, 'listing-type');
            $listing_type_id = isset($listing_type->ID) ? $listing_type->ID : 0;

            $type_gallery = get_post_meta($listing_type_id, 'wp_dp_image_gallery_element', true);
            $type_floor_plans = get_post_meta($listing_type_id, 'wp_dp_floor_plans_options_element', true);
            $type_appartments = get_post_meta($listing_type_id, 'wp_dp_appartments_options_element', true);
            $type_yelp_places = get_post_meta($listing_type_id, 'wp_dp_yelp_places_element', true);
            $type_attachments = get_post_meta($listing_type_id, 'wp_dp_attachments_options_element', true);
            $type_video = get_post_meta($listing_type_id, 'wp_dp_video_element', true);
            $type_virtual_tour = get_post_meta($listing_type_id, 'wp_dp_virtual_tour_element', true);
            $type_features = get_post_meta($listing_type_id, 'wp_dp_features_element', true);
            $type_faqs = get_post_meta($listing_type_id, 'wp_dp_faqs_options_element', true);
            $type_price = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
            $type_special_price = get_post_meta($listing_type_id, 'wp_dp_listing_type_special_price', true);
            $type_allowd_attachment_extensions = get_post_meta($listing_type_id, 'wp_dp_listing_allowd_attachment_extensions', true);
            if ( ! empty($type_allowd_attachment_extensions) && is_array($type_allowd_attachment_extensions) ) {
                $type_allowd_attachment_extensions = implode(',', $type_allowd_attachment_extensions);
            }

            $detail_page_options = array(
                'gallery' => $type_gallery,
                'floor_plans' => $type_floor_plans,
                'appartments' => $type_appartments,
                'yelp_places' => $type_yelp_places,
                'attachments' => $type_attachments,
                'video' => $type_video,
                'virtual_tour' => $type_virtual_tour,
                'features' => $type_features,
                'faqs' => $type_faqs,
                'price' => $type_price,
                'special_price' => $type_special_price,
                'allow_attachment_extensions' => $type_allowd_attachment_extensions,
            );

            echo json_encode(array( 'cf_html' => $cus_fields_html, 'cats_html' => $cats_append_html, 'price_html' => $price_append_html, 'tags_html' => $tags_append_html, 'features_html' => $features_append_html, 'detail_options' => $detail_page_options ));
            die;
        }

        /**
         * Assigning Status for Listing
         * @return
         */
        public function listing_update_status($listing_id = '') {
            global $wp_dp_plugin_options;
            $wp_dp_listings_review_option = isset($wp_dp_plugin_options['wp_dp_listings_review_option']) ? $wp_dp_plugin_options['wp_dp_listings_review_option'] : '';

            $user_data = wp_get_current_user();
            if ( $wp_dp_listings_review_option == 'on' ) {
                update_post_meta($listing_id, 'wp_dp_listing_status', 'awaiting-activation');
                // Listing not approved
                do_action('wp_dp_listing_pending_email', $listing_id);
            } else {
                update_post_meta($listing_id, 'wp_dp_listing_status', 'active');
                $listing_member_id = get_post_meta($listing_id, 'wp_dp_listing_member', true);
                if ( $listing_member_id != '' ) {
                    do_action('wp_dp_plublisher_listings_increment', $listing_member_id);
                }
                // Listing approved
                do_action('wp_dp_listing_approved_email', $listing_id);
                // social sharing
                $get_social_reach = get_post_meta($listing_id, 'wp_dp_transaction_listing_social', true);
                if ( $get_social_reach == 'on' ) {
                    $this->social_post_after_activation($listing_id);
                }
            }

            $wp_dp_free_listings_switch = isset($wp_dp_plugin_options['wp_dp_free_listings_switch']) ? $wp_dp_plugin_options['wp_dp_free_listings_switch'] : '';

            if ( $wp_dp_free_listings_switch != 'on' ) {
                $wp_dp_package_id = get_post_meta($listing_id, 'wp_dp_listing_package', true);
                if ( $wp_dp_package_id ) {
                    $wp_dp_package_data = get_post_meta($wp_dp_package_id, 'wp_dp_package_data', true);
                    $listing_duration = isset($wp_dp_package_data['listing_duration']['value']) ? $wp_dp_package_data['listing_duration']['value'] : 0;
                    // calculating listing expiry date
                    $wp_dp_trans_listing_expiry = $this->date_conv($listing_duration, 'days');
                    update_post_meta($listing_id, 'wp_dp_listing_expired', strtotime($wp_dp_trans_listing_expiry));
                }
            }
        }

        /**
         * checking member own post
         * @return boolean
         */
        public function is_member_listing($listing_id = '') {
            global $current_user;
            $company_id = wp_dp_company_id_form_user_id($current_user->ID);
            $wp_dp_member_id = get_post_meta($listing_id, 'wp_dp_listing_member', true);
            return ( is_user_logged_in() && $company_id == $wp_dp_member_id );
        }

        /**
         * checking package
         * @return boolean
         */
        public function is_package($id = '') {
            $package = get_post($id);
            return ( isset($package->post_type) && $package->post_type == 'packages' );
        }

        /**
         * Checking is form submit
         * @return boolean
         */
        public function is_form_submit() {
            return isset($_POST['wp_dp_listing_title']);
        }

        /**
         * Get Listing Content
         * @return markup
         */
        public function listing_post_content($id = '') {

            $content = get_post($id);
            $content = $content->post_content;
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
            return apply_filters('wp_dp_front_listing_post_content', $content, $id);
            // usage :: add_filter('wp_dp_front_listing_post_content', 'my_callback_function', 10, 2);
        }

        /**
         * Get Listing Transaction id
         * @return id
         */
        public function listing_trans_id($listing_id = '') {

            $get_subscripton_data = get_post_meta($listing_id, "package_subscripton_data", true);
            if ( is_array($get_subscripton_data) ) {
                $last_subs = end($get_subscripton_data);
                $trans_id = isset($last_subs['transaction_id']) ? $last_subs['transaction_id'] : false;
                return $trans_id;
            }
        }

        /**
         * Check Free or Paid listing
         * Assign Package in case of paid
         * Assign Status of listing
         * @return
         */
        public function listing_save_assignments($listing_id = '', $member_id = '') {
            global $wp_dp_plugin_options;
            $wp_dp_free_listings_switch = isset($wp_dp_plugin_options['wp_dp_free_listings_switch']) ? $wp_dp_plugin_options['wp_dp_free_listings_switch'] : '';
            $wp_dp_listing_default_expiry = isset($wp_dp_plugin_options['wp_dp_listing_default_expiry']) ? $wp_dp_plugin_options['wp_dp_listing_default_expiry'] : '';
            if ( $wp_dp_free_listings_switch == 'on' ) {
                // Free Posting without any Package
                // Assign expire date
                $wp_dp_ins_exp = strtotime(current_time('Y/m/d H:i:s', 1));
                if ( $wp_dp_listing_default_expiry != '' && is_numeric($wp_dp_listing_default_expiry) && $wp_dp_listing_default_expiry > 0 ) {
                    $wp_dp_ins_exp = $this->date_conv($wp_dp_listing_default_expiry, 'days');
                }
                update_post_meta($listing_id, 'wp_dp_listing_expired', strtotime($wp_dp_ins_exp));

                // Assign without package true
                update_post_meta($listing_id, 'wp_dp_listing_without_package', '1');

                // Assign Status of listing
                $this->listing_update_status($listing_id);

                $response['status'] = true;
                $response['msg'] = wp_dp_plugin_text_srt('wp_dp_listing_listing_added');
                return $response;
            } else {

                $new_pkg_check = wp_dp_get_input('wp_dp_listing_new_package_used', '');
                if ( $new_pkg_check == 'on' ) {
                    $package_id = wp_dp_get_input('wp_dp_listing_package', 0);
                    if ( $this->is_package($package_id) ) {
                        // package subscribe
                        // add transaction
                        $transaction_detail = $this->wp_dp_listing_add_transaction('add-listing', $listing_id, $package_id, $member_id);
                        $response['status'] = true;
                        $response['msg'] = $transaction_detail;
                        return $response;
                    }
                    // end of using new package
                } else {

                    $active_package_key = wp_dp_get_input('wp_dp_listing_active_package', 0);
                    $active_package_key = explode('pt_', $active_package_key);
                    $active_pckg_id = isset($active_package_key[0]) ? $active_package_key[0] : '';
                    $active_pckg_trans_id = isset($active_package_key[1]) ? $active_package_key[1] : '';
                    if ( $this->is_package($active_pckg_id) ) {
                        $t_package_feature_list = get_post_meta($active_pckg_trans_id, 'wp_dp_transaction_listing_feature_list', true);
                        $t_package_top_cat_list = get_post_meta($active_pckg_trans_id, 'wp_dp_transaction_listing_top_cat_list', true);
                        // if package subscribe
                        if ( $this->wp_dp_is_pkg_subscribed($active_pckg_id, $active_pckg_trans_id) ) {
                            // Get Transaction Listings array
                            // Merge new Listing in Array
                            $get_trans_listings = get_post_meta($active_pckg_trans_id, "wp_dp_listing_ids", true);
                            $updated_trans_listings = $this->merge_in_array($get_trans_listings, $listing_id);
                            update_post_meta($active_pckg_trans_id, "wp_dp_listing_ids", $updated_trans_listings);

                            $active_pckg_trans_title = $active_pckg_trans_id != '' ? str_replace('#', '', get_the_title($active_pckg_trans_id)) : '';
                            // updating package id in listing
                            update_post_meta($listing_id, "wp_dp_listing_package", $active_pckg_id);

                            // updating transaction title id in listing
                            update_post_meta($listing_id, "wp_dp_trans_id", $active_pckg_trans_title);

                            // update listing subscription renew
                            $get_subscripton_data = get_post_meta($listing_id, "package_subscripton_data", true);

                            if ( empty($get_subscripton_data) ) {
                                $package_subscripton_data = array(
                                    array(
                                        'type' => 'update_package',
                                        'transaction_id' => $active_pckg_trans_id,
                                        'title_id' => $active_pckg_trans_title,
                                        'package_id' => $active_pckg_id,
                                        'subscribe_date' => strtotime(current_time('Y/m/d H:i:s', 1)),
                                    )
                                );
                            } else {
                                $package_subscripton_data = array(
                                    'type' => 'update_package',
                                    'transaction_id' => $active_pckg_trans_id,
                                    'title_id' => $active_pckg_trans_title,
                                    'package_id' => $active_pckg_id,
                                    'renew_date' => strtotime(current_time('Y/m/d H:i:s', 1)),
                                );
                            }
                            $merged_subscripton_data = $this->merge_in_array($get_subscripton_data, $package_subscripton_data, false);
                            update_post_meta($listing_id, "package_subscripton_data", $merged_subscripton_data);


                            // updating listing meta
                            // as per transaction meta
                            // do_action('wp_dp_listing_assign_trans_meta', $listing_id, $active_pckg_trans_id);
                            $this->listing_assign_meta($listing_id, $active_pckg_trans_id);

                            // Assign Status of listing
                            $this->listing_update_status($listing_id);
                        } else {
                            
                        }
                    }
                    // end of using existing package
                }
                // end assigning packages
                // and payment processs
                $response['status'] = true;
            }

            return $response;
        }

        public function wp_dp_payment_gateways_package_selected_callback() {
            $response = array(
                'status' => false,
                'msg' => wp_dp_plugin_text_srt('wp_dp_listing_payment_error'),
            );
            $buy_order_action = wp_dp_get_input('wp_dp_buy_order_flag', 0);

            $get_trans_id = wp_dp_get_input('trans_id', 0);
            $transaction_return_url = wp_dp_get_input('transaction_return_url', site_url(), 'HTML');

            $order_type = get_post_meta($get_trans_id, 'wp_dp_order_type', true);
            $order_menu_list = get_post_meta($get_trans_id, 'menu_items_list', true);

            if ( $buy_order_action == '1' ) {
                if ( wp_dp_is_package_order($get_trans_id) ) {

                    $trans_user_id = get_post_meta($get_trans_id, 'wp_dp_transaction_user', true);
                    $wp_dp_trans_pkg = get_post_meta($get_trans_id, 'wp_dp_transaction_package', true);
                    $wp_dp_trans_amount = get_post_meta($get_trans_id, 'wp_dp_transaction_amount', true);

                    $wp_dp_trans_pay_method = wp_dp_get_input('wp_dp_listing_gateway', '', 'STRING');

                    $wp_dp_trans_array = array(
                        'transaction_id' => $get_trans_id, // order id
                        'transaction_user' => $trans_user_id,
                        'transaction_package' => $wp_dp_trans_pkg,
                        'transaction_amount' => $wp_dp_trans_amount,
                        'transaction_order_type' => 'package-order',
                        'transaction_pay_method' => $wp_dp_trans_pay_method,
                        'transaction_return_url' => $transaction_return_url,
                        'exit' => false,
                    );

                    ob_start();
                    $transaction_detail = wp_dp_payment_process($wp_dp_trans_array);
                    $output = ob_get_clean();

                    if ( ! empty($output) ) {
                        $response = array(
                            'payment_gateway' => 'wooCommerce',
                            'status' => true,
                            'msg' => $output,
                        );
                        echo json_encode($response);
                        wp_die();
                    }

                    $response = array(
                        'payment_gateway' => $wp_dp_trans_pay_method,
                        'status' => true,
                        'msg' => force_balance_tags($transaction_detail),
                    );
                }
            }
            echo json_encode($response);
            wp_die();
        }

        /**
         * Adding Transaction
         * @return id
         */
        public function wp_dp_listing_add_transaction($type = '', $listing_id = 0, $package_id = 0, $member_id = '') {
            global $wp_dp_plugin_options;
            $wp_dp_vat_switch = isset($wp_dp_plugin_options['wp_dp_vat_switch']) ? $wp_dp_plugin_options['wp_dp_vat_switch'] : '';
            $wp_dp_pay_vat = isset($wp_dp_plugin_options['wp_dp_payment_vat']) ? $wp_dp_plugin_options['wp_dp_payment_vat'] : '';
            $woocommerce_enabled = isset($wp_dp_plugin_options['wp_dp_use_woocommerce_gateway']) ? $wp_dp_plugin_options['wp_dp_use_woocommerce_gateway'] : '';
            $wp_dp_trans_id = rand(10000000, 99999999);
            $transaction_detail = '';
            $transaction_post = array(
                'post_title' => '#' . $wp_dp_trans_id,
                'post_status' => 'publish',
                'post_type' => 'package-orders',
                'post_date' => current_time('Y/m/d H:i:s', 1)
            );
            //insert the transaction
            if ( $member_id != '' ) {
                $trans_id = wp_insert_post($transaction_post);
            }

            if ( isset($trans_id) && $type != '' && $trans_id > 0 ) {

                $packge_order_post = array(
                    'ID' => $trans_id,
                    'post_date' => current_time('Y/m/d H:i:s', 1),
                    'post_date_gmt' => current_time('Y/m/d H:i:s', 1),
                );
                wp_update_post($packge_order_post);

                $pay_process = true;

                $wp_dp_trans_pkg = '';
                $package_listing_allowed = 0;
                $package_listing_duration = 0;

                $wp_dp_trans_amount = 0;
                $wp_dp_vat_amount = 0;

                if ( $package_id != '' && $package_id != 0 ) {
                    $wp_dp_trans_pkg = $package_id;

                    $wp_dp_package_data = get_post_meta($package_id, 'wp_dp_package_data', true);

                    $package_listing_duration = isset($wp_dp_package_data['listing_duration']['value']) ? $wp_dp_package_data['listing_duration']['value'] : 0;
                    $package_listing_allowed = isset($wp_dp_package_data['number_of_listing_allowed']['value']) ? $wp_dp_package_data['number_of_listing_allowed']['value'] : 0;

                    $package_amount = get_post_meta($package_id, 'wp_dp_package_price', true);

                    // calculating package expiry date
                    // calculating_amount
                    $wp_dp_trans_origional_price = isset( $wp_dp_trans_origional_price )? $wp_dp_trans_origional_price : 0;
                    $wp_dp_trans_amount += ( wp_dp_get_currency($package_amount, false) > 0)? wp_dp_get_currency($package_amount, false) : 0;
                    $wp_dp_trans_origional_price += ( wp_dp_get_currency($package_amount, false) > 0)? wp_dp_get_currency($package_amount, false) : 0;

                    if ( $woocommerce_enabled != 'on' ) {
                        if ( $wp_dp_vat_switch == 'on' && $wp_dp_pay_vat > 0 && $wp_dp_trans_amount > 0 ) {
                            $wp_dp_vat_amount = $wp_dp_trans_amount * ( $wp_dp_pay_vat / 100 );
                            $wp_dp_vat_amount = wp_dp_get_currency($wp_dp_vat_amount, false);
                            $wp_dp_trans_amount += wp_dp_get_currency($wp_dp_vat_amount, false);
                        }
                    }

                    // transaction offer fields 
                    $t_package_pic_num = isset($wp_dp_package_data['number_of_pictures']['value']) ? $wp_dp_package_data['number_of_pictures']['value'] : 0;
                    $t_package_doc_num = isset($wp_dp_package_data['number_of_documents']['value']) ? $wp_dp_package_data['number_of_documents']['value'] : 0;
                    $t_package_tags_num = isset($wp_dp_package_data['number_of_tags']['value']) ? $wp_dp_package_data['number_of_tags']['value'] : 0;
                    $t_package_feature_list = isset($wp_dp_package_data['number_of_featured_listings']['value']) ? $wp_dp_package_data['number_of_featured_listings']['value'] : '';
                    $t_package_top_cat_list = isset($wp_dp_package_data['number_of_top_cat_listings']['value']) ? $wp_dp_package_data['number_of_top_cat_listings']['value'] : '';
                    $t_package_phone_website = isset($wp_dp_package_data['phone_number_website']['value']) ? $wp_dp_package_data['phone_number_website']['value'] : '';
		    $t_listing_video = isset($wp_dp_package_data['listing_video']['value']) ? $wp_dp_package_data['listing_video']['value'] : '';
                    $t_package_social = isset($wp_dp_package_data['social_impressions_reach']['value']) ? $wp_dp_package_data['social_impressions_reach']['value'] : '';
                    $t_package_reviews = isset($wp_dp_package_data['reviews']['value']) ? $wp_dp_package_data['reviews']['value'] : '';
                    $t_package_ror = isset($wp_dp_package_data['respond_to_reviews']['value']) ? $wp_dp_package_data['respond_to_reviews']['value'] : '';
                    $t_package_dynamic_values = get_post_meta($package_id, 'wp_dp_package_fields', true);
                }

                $wp_dp_trans_array = array(
                    'transaction_id' => $trans_id,
                    'transaction_user' => $member_id,
                    'transaction_package' => $wp_dp_trans_pkg,
                    'transaction_amount' => $wp_dp_trans_amount,
                    'transaction_origional_price' => $wp_dp_trans_origional_price,
                    'transaction_vat_price' => $wp_dp_vat_amount,
                    'transaction_vat_tax' => $wp_dp_pay_vat,
                    'transaction_listings' => $package_listing_allowed,
                    'transaction_listing_expiry' => $package_listing_duration,
                    'transaction_listing_pic_num' => isset($t_package_pic_num) ? $t_package_pic_num : '',
                    'transaction_listing_doc_num' => isset($t_package_doc_num) ? $t_package_doc_num : '',
                    'transaction_listing_tags_num' => isset($t_package_tags_num) ? $t_package_tags_num : '',
                    'transaction_listing_feature_list' => isset($t_package_feature_list) ? $t_package_feature_list : '',
                    'transaction_listing_top_cat_list' => isset($t_package_top_cat_list) ? $t_package_top_cat_list : '',
                    'transaction_listing_phone_website' => isset($t_package_phone_website) ? $t_package_phone_website : '',
		    'transaction_listing_video' => isset($t_listing_video) ? $t_listing_video : '',
                    'transaction_listing_social' => isset($t_package_social) ? $t_package_social : '',
                    'transaction_listing_reviews' => isset($t_package_reviews) ? $t_package_reviews : '',
                    'transaction_listing_ror' => isset($t_package_ror) ? $t_package_ror : '',
                    'transaction_dynamic' => isset($t_package_dynamic_values) ? $t_package_dynamic_values : '',
                    'transaction_ptype' => $type,
                );

                if ( $package_id != '' && $package_id != 0 ) {
                    if ( $wp_dp_trans_amount <= 0 ) {
                        $wp_dp_trans_array['transaction_pay_method'] = '-';
                        $wp_dp_trans_array['transaction_status'] = 'approved';
                        $pay_process = false;
                    }
                    $package_type = get_post_meta($package_id, 'wp_dp_package_type', true);
                    $package_price = get_post_meta($package_id, 'wp_dp_package_price', true);
                    if ( $package_type == 'free' ) {
                        $wp_dp_trans_array['transaction_pay_method'] = '-';
                        $wp_dp_trans_array['transaction_status'] = 'approved';
                        $pay_process = false;
                    }
                }

                if ( ($type == 'add-listing' || $type == 'update-listing') && $listing_id != '' && $listing_id != 0 ) {

                    // update listing expiry, featured, top category
                    // this change will be temporary
                    update_post_meta($listing_id, "wp_dp_listing_expired", strtotime(current_time('Y/m/d H:i:s', 1)));
                    update_post_meta($listing_id, "wp_dp_listing_is_top_cat", '');

                    // updating listing ids in transaction
                    $wp_dp_trans_array['listing_ids'] = array( $listing_id );
                    // updating transaction id in listing
                    update_post_meta($listing_id, "wp_dp_trans_id", $wp_dp_trans_id);

                    // updating package id in listing
                    update_post_meta($listing_id, "wp_dp_listing_package", $package_id);

                    // update listing subscription
                    if ( $type == 'add-listing' ) {
                        $package_subscripton_data = array(
                            array(
                                'type' => ($type == 'add-listing' ? 'add_package' : 'update_package'),
                                'transaction_id' => $trans_id,
                                'title_id' => $wp_dp_trans_id,
                                'package_id' => $package_id,
                                'subscribe_date' => strtotime(current_time('Y/m/d H:i:s', 1)),
                            )
                        );
                    } else {
                        $package_subscripton_data = array(
                            'type' => ($type == 'add-listing' ? 'add_package' : 'update_package'),
                            'transaction_id' => $trans_id,
                            'title_id' => $wp_dp_trans_id,
                            'package_id' => $package_id,
                            'subscribe_date' => strtotime(current_time('Y/m/d H:i:s', 1)),
                        );
                    }
                    $get_subscripton_data = get_post_meta($listing_id, "package_subscripton_data", true);
                    $merged_subscripton_data = $this->merge_in_array($get_subscripton_data, $package_subscripton_data, false);
                    update_post_meta($listing_id, "package_subscripton_data", $merged_subscripton_data);

                    // update listing featured
                    if ( isset($wp_dp_package_data) && ! empty($wp_dp_package_data) ) {
                        // Top Cat from form
                        $get_listing_featured = wp_dp_get_input('wp_dp_listing_featured', '');
                        if ( $t_package_feature_list == 'on' ) {
                            $wp_dp_trans_array['featured_ids'] = array( $listing_id );
                        }
                    }

                    // update listing top category
                    if ( isset($wp_dp_package_data) && ! empty($wp_dp_package_data) ) {
                        // Top Cat from form
                        $get_listing_top_cat = wp_dp_get_input('wp_dp_listing_top_cat', '');
                        if ( $t_package_top_cat_list == 'on' ) {
                            update_post_meta($listing_id, "wp_dp_listing_is_top_cat", 'on');
                            $wp_dp_trans_array['top_cat_ids'] = array( $listing_id );
                        }
                    }
                }

                // update package dynamic fields in transaction
                $wp_dp_package_dynamic = get_post_meta($package_id, 'wp_dp_package_fields', true);
                $wp_dp_trans_array['transaction_dynamic'] = $wp_dp_package_dynamic;

                // updating all fields of transaction
                foreach ( $wp_dp_trans_array as $trans_key => $trans_val ) {
                    update_post_meta($trans_id, "wp_dp_{$trans_key}", $trans_val);
                }

                // Inserting VAT amount in array
                if ( isset($wp_dp_vat_amount) && $wp_dp_vat_amount > 0 ) {
                    $wp_dp_trans_array['vat_amount'] = $wp_dp_vat_amount;
                }

                // Inserting random id in array
                $wp_dp_trans_array['trans_rand_id'] = $wp_dp_trans_id;

                // Inserting item id in array
                if ( $listing_id != '' && $listing_id != 0 ) {
                    $wp_dp_trans_array['trans_item_id'] = $listing_id;
                    update_post_meta($trans_id, "order_item_id", $listing_id);
                } else {
                    $wp_dp_trans_array['trans_item_id'] = $wp_dp_trans_id;
                }

                if ( ($type == 'add-listing' || $type == 'update-listing') && $listing_id != '' && $listing_id != 0 ) {
                    // updating listing meta
                    // as per transaction meta
                    $this->listing_assign_meta($listing_id, $trans_id);
                    if ( $package_type == 'free' || $package_price <= 0 ) {
                        $this->listing_update_status($listing_id);
                    }
                }

                // Payment Process
                if ( $pay_process ) {
                    $response = array(
                        'status' => true,
                        'msg' => $trans_id,
                    );
                    echo json_encode($response);
                    wp_die();
                }
            }
            return apply_filters('wp_dp_listing_add_transaction', $transaction_detail, $type, $listing_id, $package_id, $member_id);
        }

        /**
         * Check user package subscription
         * @return id
         */
        public function wp_dp_is_pkg_subscribed($wp_dp_package_id = 0, $trans_id = 0) {
            global $post, $current_user;

            $company_id = wp_dp_company_id_form_user_id($current_user->ID);

            if ( $trans_id == '' ) {
                $trans_id = 0;
            }
            $transaction_id = false;
            $wp_dp_current_date = strtotime(date('d-m-Y'));
            $args = array(
                'posts_per_page' => "-1",
                'post_type' => 'package-orders',
                'post_status' => 'publish',
                'post__in' => array( $trans_id ),
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_transaction_package',
                        'value' => $wp_dp_package_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_transaction_user',
                        'value' => $company_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_transaction_status',
                        'value' => 'approved',
                        'compare' => '=',
                    ),
                ),
            );

            $custom_query = new WP_Query($args);
            $wp_dp_trans_count = $custom_query->post_count;

            if ( $wp_dp_trans_count > 0 ) {
                while ( $custom_query->have_posts() ) : $custom_query->the_post();
                    $wp_dp_pkg_list_num = get_post_meta($post->ID, 'wp_dp_transaction_listings', true);
                    $wp_dp_listing_ids = get_post_meta($post->ID, 'wp_dp_listing_ids', true);

                    if ( empty($wp_dp_listing_ids) ) {
                        $wp_dp_listing_ids_size = 0;
                    } else {
                        $wp_dp_listing_ids_size = absint(sizeof($wp_dp_listing_ids));
                    }
                    $wp_dp_ids_num = $wp_dp_listing_ids_size;
                    if ( (int) $wp_dp_ids_num < (int) $wp_dp_pkg_list_num ) {
                        $wp_dp_trnasaction_id = $post->ID;
                    }
                endwhile;
                wp_reset_postdata();
            }

            if ( isset($wp_dp_trnasaction_id) && $wp_dp_trnasaction_id > 0 ) {
                $transaction_id = $wp_dp_trnasaction_id;
            }
            return apply_filters('wp_dp_listing_is_package_subscribe', $transaction_id, $wp_dp_package_id, $trans_id);
        }

        /**
         * Get all active packages of current user
         * @return array
         */
        public function user_all_active_pkgs() {
            global $post, $current_user;

            $company_id = wp_dp_company_id_form_user_id($current_user->ID);

            $trans_ids = array();
            $wp_dp_current_date = strtotime(date('d-m-Y'));
            $args = array(
                'posts_per_page' => "-1",
                'post_type' => 'package-orders',
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_transaction_user',
                        'value' => $company_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_transaction_status',
                        'value' => 'approved',
                        'compare' => '=',
                    ),
                ),
            );

            $custom_query = new WP_Query($args);
            $wp_dp_trans_count = $custom_query->post_count;

            if ( $wp_dp_trans_count > 0 ) {
                while ( $custom_query->have_posts() ) : $custom_query->the_post();
                    $wp_dp_pkg_list_num = get_post_meta($post->ID, 'wp_dp_transaction_listings', true);
                    $wp_dp_listing_ids = get_post_meta($post->ID, 'wp_dp_listing_ids', true);

                    if ( empty($wp_dp_listing_ids) ) {
                        $wp_dp_listing_ids_size = 0;
                    } else {
                        $wp_dp_listing_ids_size = absint(sizeof($wp_dp_listing_ids));
                    }

                    $wp_dp_ids_num = $wp_dp_listing_ids_size;
                    if ( (int) $wp_dp_ids_num < (int) $wp_dp_pkg_list_num ) {
                        $trans_ids[] = $post->ID;
                    }
                endwhile;
                wp_reset_postdata();
            }

            return apply_filters('wp_dp_listing_user_active_packages', $trans_ids);
        }

        /**
         * Get User Package Trans
         * @return id
         */
        public function get_user_package_trans($wp_dp_package_id = 0, $trans_id = 0) {
            global $post, $current_user;

            $company_id = wp_dp_company_id_form_user_id($current_user->ID);

            if ( $trans_id == '' ) {
                $trans_id = 0;
            }
            $transaction_id = false;
            $args = array(
                'posts_per_page' => "1",
                'post_type' => 'package-orders',
                'post_status' => 'publish',
                'post__in' => array( $trans_id ),
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_transaction_package',
                        'value' => $wp_dp_package_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_transaction_user',
                        'value' => $company_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_transaction_status',
                        'value' => 'approved',
                        'compare' => '=',
                    ),
                ),
            );

            $custom_query = new WP_Query($args);
            $wp_dp_trans_count = $custom_query->post_count;

            if ( $wp_dp_trans_count > 0 ) {
                while ( $custom_query->have_posts() ) : $custom_query->the_post();
                    $wp_dp_trnasaction_id = $post->ID;
                endwhile;
                wp_reset_postdata();
            }

            if ( isset($wp_dp_trnasaction_id) && $wp_dp_trnasaction_id > 0 ) {
                $transaction_id = $wp_dp_trnasaction_id;
            }
            return apply_filters('wp_dp_listing_user_package_trans', $transaction_id, $wp_dp_package_id, $trans_id);
        }

        /**
         * field container size
         * @return class
         */
        public function field_size_class($size = '') {
            switch ( $size ) {
                case('large'):
                    $class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
                    break;
                case('medium'):
                    $class = 'col-lg-6 col-md-6 col-sm-12 col-xs-12';
                    break;
                default :
                    $class = 'col-lg-6 col-md-6 col-sm-12 col-xs-12';
                    break;
            }
            return $class;
        }

        /**
         * Custom Fields
         * @return markup
         */
        public function custom_fields($type_id = '', $wp_dp_id = '') {
            global $wp_dp_form_fields, $wp_dp_form_fields_frontend, $icon_groups;
            $html = '';
            $wp_dp_cus_fields = get_post_meta($type_id, "wp_dp_listing_type_cus_fields", true);
            if ( is_array($wp_dp_cus_fields) && sizeof($wp_dp_cus_fields) > 0 ) {
                foreach ( $wp_dp_cus_fields as $cus_field ) {
                    $cus_type = isset($cus_field['type']) ? $cus_field['type'] : '';
                    $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                    if ( $cus_font_icon != '' ) {
                        $cus_font_icon_group = isset($cus_field['fontawsome_icon_group']) ? $cus_field['fontawsome_icon_group'] : 'default';
                        if ( isset($icon_groups[$cus_font_icon_group]) ) {
                            wp_enqueue_style('cs_icons_data_css_' . $cus_font_icon_group);
                        }
                    }
                    switch ( $cus_type ) {
                        case('text'):
                            $cus_label = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_meta_key = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
                            $cus_default_val = isset($cus_field['default_value']) ? $cus_field['default_value'] : '';
                            $cus_required = isset($cus_field['required']) && $cus_field['required'] == 'yes' ? ' wp-dp-dev-req-field' : '';
                            $cus_help_txt = isset($cus_field['help']) ? $cus_field['help'] : '';
                            $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            $cus_size = isset($cus_field['field_size']) ? $cus_field['field_size'] : '';
                            if ( $wp_dp_id != '' ) {
                                $cus_default_val = get_post_meta((int) $wp_dp_id, "$cus_meta_key", true);
                            }

                            if ( $cus_meta_key != '' ) {
                                $html .= '
                                    <div class="' . $this->field_size_class($cus_size) . '">
                                    <div class="field-holder">
                                    <label>' . esc_attr($cus_label) . '</label>';
                                if ( $cus_font_icon != '' ) {
                                    $html .= '<div class="has-icon"><i class="' . $cus_font_icon . '"></i>';
                                }
                                $cus_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'classes' => $cus_required,
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'std' => $cus_default_val,
                                    'id' => isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '',
                                    'cus_field' => true,
                                    'return' => true,
                                );

                                if ( isset($cus_field['placeholder']) && $cus_field['placeholder'] != '' ) {
                                    $cus_opt_array['extra_atr'] = ' placeholder="' . $cus_field['placeholder'] . '"';
                                }

                                if ( isset($cus_field['required']) && $cus_field['required'] == 'yes' ) {
                                    $cus_opt_array['classes'] = 'wp-dp-dev-req-field';
                                }
                                $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($cus_opt_array);

                                if ( $cus_help_txt <> '' ) {
                                    $html .= '<span class="cs-caption">' . $cus_help_txt . '</span>';
                                }
                                if ( $cus_font_icon != '' ) {
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                $html .= '</div>';
                            }
                            break;
                        case('number'):
                            $cus_label = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_meta_key = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
                            $cus_default_val = isset($cus_field['default_value']) ? $cus_field['default_value'] : '';
                            $cus_required = isset($cus_field['required']) && $cus_field['required'] == 'yes' ? ' wp-dp-dev-req-field' : '';
                            $cus_help_txt = isset($cus_field['help']) ? $cus_field['help'] : '';
                            $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            $cus_size = isset($cus_field['field_size']) ? $cus_field['field_size'] : '';
                            if ( $wp_dp_id != '' ) {
                                $cus_default_val = get_post_meta((int) $wp_dp_id, "$cus_meta_key", true);
                            }

                            if ( $cus_meta_key != '' ) {
                                $html .= '
								<div class="' . $this->field_size_class($cus_size) . '">
								<div class="field-holder">
								<div class="cus-num-field">
								<label>' . esc_attr($cus_label) . '</label>';
                                $cus_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'classes' => $cus_required,
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'std' => isset($cus_default_val) && $cus_default_val != '' ? $cus_default_val : 0,
                                    'id' => isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '',
                                    'cus_field' => true,
                                    'return' => true,
                                );

                                if ( isset($cus_field['placeholder']) && $cus_field['placeholder'] != '' ) {
                                    
                                }

                                if ( isset($cus_field['required']) && $cus_field['required'] == 'yes' ) {
                                    $cus_opt_array['classes'] = 'wp-dp-dev-req-field';
                                }

                                if ( $cus_help_txt <> '' ) {
                                    $html .= '<span class="cs-caption">' . $cus_help_txt . '</span>';
                                }
                                $html .= '
								<div class="select-categories">
									<ul class="minimum-loading-list">
										<li>
											<div class="spinner-btn input-group spinner">
												<span><i class="' . $cus_font_icon . '"></i></span>
												' . $wp_dp_form_fields_frontend->wp_dp_form_text_render($cus_opt_array) . '
												<span class="list-text">' . esc_attr($cus_label) . '</span>
												<div class="input-group-btn-vertical">
													<button class="btn-decrementmin-num caret-btn btn-default " type="button"><i class="icon-minus-circle"></i></button>
													<button class="btn-incrementmin-num caret-btn btn-default" type="button"><i class="icon-plus-circle"></i></button>
												</div>
											</div>
										</li>
									</ul>
                                </div>';
                                $html .= '</div>';
                                $html .= '
								<script>
									jQuery(document).ready(function ($) {
										$("#wp_dp_' . $cus_field['meta_key'] . '").keypress(function (e) {
											if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
												return false;
											}
										});
									});
								</script>';


                                $html .= '</div>';
                                $html .= '</div>';
                            }
                            break;
                        case('textarea'):
                            $cus_label = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_rows = isset($cus_field['rows']) ? $cus_field['rows'] : '';
                            $cus_cols = isset($cus_field['cols']) ? $cus_field['cols'] : '';
                            $cus_meta_key = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
                            $cus_default_val = isset($cus_field['default_value']) ? $cus_field['default_value'] : '';
                            $cus_required = isset($cus_field['required']) && $cus_field['required'] == 'yes' ? ' wp-dp-dev-req-field' : '';
                            $cus_help_txt = isset($cus_field['help']) ? $cus_field['help'] : '';
                            $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            $cus_size = isset($cus_field['field_size']) ? $cus_field['field_size'] : '';
                            if ( $wp_dp_id != '' ) {
                                $cus_default_val = get_post_meta((int) $wp_dp_id, "$cus_meta_key", true);
                            }
                            if ( $cus_meta_key != '' ) {
                                $html .= '
								<div class="' . $this->field_size_class($cus_size) . '">
								<div class="field-holder">
									<label>' . esc_attr($cus_label) . '</label>';
                                if ( $cus_font_icon != '' ) {
                                    $html .= '<div class="has-icon"><i class="' . $cus_font_icon . '"></i>';
                                }

                                $cus_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'classes' => $cus_required,
                                    'extra_atr' => 'rows="' . $cus_rows . '" cols="' . $cus_cols . '"',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'std' => $cus_default_val,
                                    'id' => isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '',
                                    'cus_field' => true,
                                    'return' => true,
                                );

                                if ( isset($cus_field['required']) && $cus_field['required'] == 'yes' ) {
                                    $cus_opt_array['classes'] = 'wp-dp-dev-req-field';
                                }

                                $html .= $wp_dp_form_fields_frontend->wp_dp_form_textarea_render($cus_opt_array);

                                if ( $cus_help_txt <> '' ) {
                                    $html .= '<span class="cs-caption">' . $cus_help_txt . '</span>';
                                }
                                if ( $cus_font_icon != '' ) {
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                $html .= '</div>';
                            }
                            break;
                        case('dropdown'):
                            $cus_label = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_meta_key = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
                            $cus_default_val = isset($cus_field['default_value']) ? $cus_field['default_value'] : '';
                            $cus_required = isset($cus_field['required']) && $cus_field['required'] == 'yes' ? ' wp-dp-dev-req-field' : '';
                            $cus_help_txt = isset($cus_field['help']) ? $cus_field['help'] : '';
                            $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            $fontawsome_icon_group = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon_group'] : '';
                            $cus_size = isset($cus_field['field_size']) ? $cus_field['field_size'] : '';

                            if ( $wp_dp_id != '' ) {
                                $cus_default_val = get_post_meta((int) $wp_dp_id, "$cus_meta_key", true);
                            }
                            $cus_dr_name = ' name="wp_dp_cus_field[' . sanitize_html_class($cus_meta_key) . ']"';
                            $cus_dr_mult = '';
                            if ( isset($cus_field['post_multi']) && $cus_field['post_multi'] == 'yes' ) {
                                $cus_dr_name = ' name="wp_dp_cus_field[' . sanitize_html_class($cus_meta_key) . '][]"';
                                $cus_dr_mult = ' multiple="multiple"';
                            }

                            $a_options = array();

                            $cus_options_mark = '';

                            if ( isset($cus_field['options']['value']) && is_array($cus_field['options']['value']) && sizeof($cus_field['options']['value']) > 0 ) {
                                if ( isset($cus_field['first_value']) && $cus_field['first_value'] != '' ) {
                                    $cus_options_mark .= '<option value="">' . $cus_field['first_value'] . '</option>';
                                }
                                $cus_opt_counter = 0;
                                foreach ( $cus_field['options']['value'] as $cus_option ) {

                                    if ( isset($cus_field['post_multi']) && $cus_field['post_multi'] == 'yes' ) {

                                        $cus_checkd = '';
                                        if ( is_array($cus_default_val) && in_array($cus_option, $cus_default_val) ) {
                                            $cus_checkd = ' selected="selected"';
                                        }
                                    } else {
                                        $cus_checkd = $cus_option == $cus_default_val ? ' selected="selected"' : '';
                                    }

                                    $cus_opt_label = $cus_field['options']['label'][$cus_opt_counter];
                                    $cus_options_mark .= '<option value="' . $cus_option . '"' . $cus_checkd . '>' . $cus_opt_label . '</option>';
                                    $cus_opt_counter ++;
                                }
                            }

                            if ( $cus_meta_key != '' ) {
                                $html .= '
								<div class="' . $this->field_size_class($cus_size) . '">
								<div class="field-holder">
								<label>' . esc_attr($cus_label) . '</label>';
                                if ( $cus_font_icon != '' ) {
                                    $html .= '<div class="has-icon"><i class="' . $cus_font_icon . '"></i>';
                                }

                                $cus_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'classes' => 'chosen-select' . $cus_required,
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'std' => isset($cus_field['default_value']) ? $cus_field['default_value'] : '',
                                    'id' => isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '',
                                    'options' => $cus_options_mark,
                                    'options_markup' => true,
                                    'cus_field' => true,
                                    'description' => '',
                                    'return' => true,
                                );

                                if ( isset($cus_field['first_value']) && $cus_field['first_value'] != '' ) {
                                    $cus_opt_array['extra_atr'] = ' data-placeholder="' . $cus_field['first_value'] . '"';
                                }

                                if ( isset($cus_field['required']) && $cus_field['required'] == 'yes' ) {
                                    $cus_opt_array['classes'] = 'chosen-select form-control wp-dp-dev-req-field';
                                }
                                if ( isset($cus_field['post_multi']) && $cus_field['post_multi'] == 'yes' ) {
                                    $html .= $wp_dp_form_fields_frontend->wp_dp_custom_form_multiselect_render($cus_opt_array);
                                } else {
                                    $html .= $wp_dp_form_fields_frontend->wp_dp_form_select_render($cus_opt_array);
                                }

                                if ( $cus_help_txt <> '' ) {
                                    $html .= '<span class="cs-caption">' . $cus_help_txt . '</span>';
                                }

                                if ( $cus_font_icon != '' ) {
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                $html .= '</div>';
                            }
                            break;
                        case('date'):
                            $cus_label = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_meta_key = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
                            $cus_default_val = isset($cus_field['default_value']) ? $cus_field['default_value'] : '';
                            $cus_required = isset($cus_field['required']) && $cus_field['required'] == 'yes' ? ' wp-dp-dev-req-field' : '';
                            $cus_format = isset($cus_field['date_format']) ? $cus_field['date_format'] : 'd-m-Y';
                            $cus_help_txt = isset($cus_field['help']) ? $cus_field['help'] : '';
                            $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            $cus_size = isset($cus_field['field_size']) ? $cus_field['field_size'] : '';
                            if ( $wp_dp_id != '' ) {
                                $cus_default_val = get_post_meta((int) $wp_dp_id, "$cus_meta_key", true);
                            }

                            if ( $cus_meta_key != '' ) {
                                $html .= '
								<div class="' . $this->field_size_class($cus_size) . '">
								<div class="field-holder">
								<label>' . esc_attr($cus_label) . '</label>';
                                if ( $cus_font_icon != '' ) {
                                    $html .= '<div class="has-icon"><i class="' . $cus_font_icon . '"></i>';
                                }

                                $cus_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'classes' => $cus_required . ' wp-dp-date-field',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'std' => $cus_default_val,
                                    'id' => isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '',
                                    'cus_field' => true,
                                    'format' => $cus_format,
                                    'return' => true,
                                );

                                if ( isset($cus_field['placeholder']) && $cus_field['placeholder'] != '' ) {
                                    $cus_opt_array['extra_atr'] = ' placeholder="' . $cus_field['placeholder'] . '"';
                                }

                                $html .= $wp_dp_form_fields_frontend->wp_dp_form_date_render($cus_opt_array);

                                if ( $cus_help_txt <> '' ) {
                                    $html .= '<span class="cs-caption">' . $cus_help_txt . '</span>';
                                }
                                if ( $cus_font_icon != '' ) {
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                $html .= '</div>';
                            }
                            break;
                        case('email'):
                            $cus_label = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_meta_key = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
                            $cus_default_val = isset($cus_field['default_value']) ? $cus_field['default_value'] : '';
                            $cus_required = isset($cus_field['required']) && $cus_field['required'] == 'yes' ? ' wp-dp-dev-req-field' : '';
                            $cus_help_txt = isset($cus_field['help']) ? $cus_field['help'] : '';
                            $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            $cus_size = isset($cus_field['field_size']) ? $cus_field['field_size'] : '';
                            if ( $wp_dp_id != '' ) {
                                $cus_default_val = get_post_meta((int) $wp_dp_id, "$cus_meta_key", true);
                            }

                            if ( $cus_meta_key != '' ) {
                                $html .= '
								<div class="' . $this->field_size_class($cus_size) . '">
								<div class="field-holder">
								<label>' . esc_attr($cus_label) . '</label>';
                                if ( $cus_font_icon != '' ) {
                                    $html .= '<div class="has-icon"><i class="' . $cus_font_icon . '"></i>';
                                }
                                $cus_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'classes' => $cus_required . ' wp-dp-email-field',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'std' => $cus_default_val,
                                    'id' => isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '',
                                    'cus_field' => true,
                                    'return' => true,
                                );

                                if ( isset($cus_field['placeholder']) && $cus_field['placeholder'] != '' ) {
                                    $cus_opt_array['extra_atr'] = ' placeholder="' . $cus_field['placeholder'] . '"';
                                }

                                $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($cus_opt_array);
                                if ( $cus_help_txt <> '' ) {
                                    $html .= '<span class="cs-caption">' . $cus_help_txt . '</span>';
                                }
                                if ( $cus_font_icon != '' ) {
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                $html .= '</div>';
                            }
                            break;
                        case('url'):
                            $cus_label = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_meta_key = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
                            $cus_default_val = isset($cus_field['default_value']) ? $cus_field['default_value'] : '';
                            $cus_required = isset($cus_field['required']) && $cus_field['required'] == 'yes' ? ' wp-dp-dev-req-field' : '';
                            $cus_help_txt = isset($cus_field['help']) ? $cus_field['help'] : '';
                            $cus_size = isset($cus_field['field_size']) ? $cus_field['field_size'] : '';
                            $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            if ( $wp_dp_id != '' ) {
                                $cus_default_val = get_post_meta((int) $wp_dp_id, "$cus_meta_key", true);
                            }

                            if ( $cus_meta_key != '' ) {
                                $html .= '
								<div class="' . $this->field_size_class($cus_size) . '">
									<div class="field-holder">
									<label>' . esc_attr($cus_label) . '</label>';
                                if ( $cus_font_icon != '' ) {
                                    $html .= '<div class="has-icon"><i class="' . $cus_font_icon . '"></i>';
                                }

                                $cus_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'classes' => $cus_required . ' wp-dp-url-field',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'std' => $cus_default_val,
                                    'id' => isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '',
                                    'cus_field' => true,
                                    'return' => true,
                                );

                                if ( isset($cus_field['placeholder']) && $cus_field['placeholder'] != '' ) {
                                    $cus_opt_array['extra_atr'] = ' placeholder="' . $cus_field['placeholder'] . '"';
                                }

                                $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($cus_opt_array);

                                if ( $cus_help_txt <> '' ) {
                                    $html .= '<span class="cs-caption">' . $cus_help_txt . '</span>';
                                }
                                if ( $cus_font_icon != '' ) {
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                $html .= '</div>';
                                break;
                            }
                        case('range'):
                            $cus_label = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_meta_key = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
                            $cus_default_val = isset($cus_field['default_value']) ? $cus_field['default_value'] : '';
                            $cus_required = isset($cus_field['required']) && $cus_field['required'] == 'yes' ? ' wp-dp-dev-req-field' : '';
                            $cus_help_txt = isset($cus_field['help']) ? $cus_field['help'] : '';
                            $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            $cus_size = isset($cus_field['field_size']) ? $cus_field['field_size'] : '';
                            if ( $wp_dp_id != '' ) {
                                $cus_default_val = get_post_meta((int) $wp_dp_id, "$cus_meta_key", true);
                            }

                            if ( $cus_meta_key != '' ) {
                                $html .= '
										<div class="' . $this->field_size_class($cus_size) . '">
											<div class="field-holder">
												<label>' . esc_attr($cus_label) . '</label>';
                                if ( $cus_font_icon != '' ) {
                                    $html .= '<div class="has-icon"><i class="' . $cus_font_icon . '"></i>';
                                }

                                $cus_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'classes' => $cus_required . ' wp-dp-range-field',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'std' => $cus_default_val,
                                    'id' => isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '',
                                    'cus_field' => true,
                                    'extra_atr' => 'data-min="' . $cus_field['min'] . '" data-max="' . $cus_field['max'] . '"',
                                    'return' => true,
                                );

                                if ( isset($cus_field['placeholder']) && $cus_field['placeholder'] != '' ) {
                                    $cus_opt_array['extra_atr'] .= ' placeholder="' . $cus_field['placeholder'] . '"';
                                }

                                $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($cus_opt_array);

                                if ( $cus_help_txt <> '' ) {
                                    $html .= '<span class="cs-caption">' . $cus_help_txt . '</span>';
                                }
                                if ( $cus_font_icon != '' ) {
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                $html .= '</div>';
                            }
                            break;
                    }
                }
            }

            return $html;
        }

        /**
         * Load wp_dp Custom Fields
         * @return markup
         */
        public function listing_custom_fields() {
            $get_listing_id = wp_dp_get_input('listing_id', 0);
            if ( $get_listing_id != '' && $get_listing_id != 0 && $this->is_member_listing($get_listing_id) ) {
                $listing_type = get_post_meta($get_listing_id, 'wp_dp_listing_type', true);
                $is_updating = true;
            } else {
                $is_updating = false;
                $listing_type = '';
            }

            if ( $listing_type != '' ) {
                $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type", 'post_status' => 'publish', 'suppress_filters' => '0' ));
                $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
                $html = '<div class="listing-cf-fields"><div class="row">' . $this->custom_fields($listing_type_id, $get_listing_id) . '</div></div>';
                echo force_balance_tags($html);
            }
        }

        /**
         * Purchased Package Info Field Create
         * @return markup
         */
        public function purchase_package_info_field_show($value = '', $label = '', $value_plus = '') {

            if ( $value != '' && $value != 'on' && $value != 'off' ) {
                $html = '<li><label>' . $label . '</label><span>' . $value . ' ' . $value_plus . '</span></li>';
            } else if ( $value != '' && $value == 'on' ) {
                $html = '<li><label>' . $label . '</label><span><i class="icon-check2"></i></span></li>';
            } else {
                $html = '<li><label>' . $label . '</label><span><i class="icon-minus"></i></span></li>';
            }

            return $html;
        }

        /**
         * Get Subscribe Package info
         * @return html
         */
        public function subs_package_info($package_id = 0, $trans_id = 0, $in_update = '') {
            global $listing_add_counter;
            $html = '';
            $inner_html = '';

            if ( $user_package = $this->get_user_package_trans($package_id, $trans_id) ) {
                $title_id = $user_package != '' ? get_the_title($user_package) : '';
                $trans_packg_id = get_post_meta($trans_id, 'wp_dp_transaction_package', true);
                $packg_title = $trans_packg_id != '' ? get_the_title($trans_packg_id) : '';

                $trans_packg_expiry = get_post_meta($trans_id, 'wp_dp_transaction_expiry_date', true);
                $trans_packg_list_num = get_post_meta($trans_id, 'wp_dp_transaction_listings', true);
                $trans_packg_list_expire = get_post_meta($trans_id, 'wp_dp_transaction_listing_expiry', true);
                $wp_dp_listing_ids = get_post_meta($trans_id, 'wp_dp_listing_ids', true);

                if ( empty($wp_dp_listing_ids) ) {
                    $wp_dp_listing_used = 0;
                } else {
                    $wp_dp_listing_used = absint(sizeof($wp_dp_listing_ids));
                }

                $wp_dp_listing_dpain = '0';
                if ( (int) $trans_packg_list_num > (int) $wp_dp_listing_used ) {
                    $wp_dp_listing_dpain = (int) $trans_packg_list_num - (int) $wp_dp_listing_used;
                }

                $trans_featured = get_post_meta($trans_id, 'wp_dp_transaction_listing_feature_list', true);

                $trans_top_cat = get_post_meta($trans_id, 'wp_dp_transaction_listing_top_cat_list', true);
                $trans_pics_num = get_post_meta($trans_id, 'wp_dp_transaction_listing_pic_num', true);
                $trans_docs_num = get_post_meta($trans_id, 'wp_dp_transaction_listing_doc_num', true);
                $trans_tags_num = get_post_meta($trans_id, 'wp_dp_transaction_listing_tags_num', true);

                $trans_phone_website = get_post_meta($trans_id, 'wp_dp_transaction_listing_phone_website', true);

                $trans_social = get_post_meta($trans_id, 'wp_dp_transaction_listing_social', true);
                $listing_video = get_post_meta($trans_id, 'wp_dp_transaction_listing_video', true);
                $trans_reviews = get_post_meta($trans_id, 'wp_dp_transaction_listing_reviews', true);
                $trans_ror = get_post_meta($trans_id, 'wp_dp_transaction_listing_ror', true);
                $trans_dynamic_f = get_post_meta($trans_id, 'wp_dp_transaction_dynamic', true);
                $pkg_expire_date = date_i18n(get_option('date_format'), $trans_packg_expiry);

                if ( $in_update == 'in_update' ) {
                    $html .= '<div class="dashboard-element-title"><strong>' . wp_dp_plugin_text_srt('wp_dp_listing_package_info') . '</strong></div>';
                }

                $html .= '<div id="package-detail-' . $package_id . 'pt_' . $trans_id . '" style="display:' . ($in_update == 'in_update' ? 'block' : 'none') . ';" class="package-info-sec listing-info-sec">';
                $html .= '<div class="row">';
                $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                $html .= '<ul class="listing-pkg-points">';

                $html .= $this->purchase_package_info_field_show($pkg_expire_date, wp_dp_plugin_text_srt('wp_dp_listing_expiry_date'));
                $html .= '<li><label>' . wp_dp_plugin_text_srt('wp_dp_listing_listings') . '</label><span>' . absint($wp_dp_listing_used) . '/' . absint($trans_packg_list_num) . '</span></li>';
                $html .= $this->purchase_package_info_field_show($trans_packg_list_expire, wp_dp_plugin_text_srt('wp_dp_listing_listing_duration'), wp_dp_plugin_text_srt('wp_dp_listing_days'));

                $html .= $this->purchase_package_info_field_show($trans_pics_num, wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_pictures'));
                $html .= $this->purchase_package_info_field_show($trans_docs_num, wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_docs'));
                $html .= $this->purchase_package_info_field_show($trans_tags_num, wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_tags'));
                $html .= $this->purchase_package_info_field_show($trans_phone_website, wp_dp_plugin_text_srt('wp_dp_listing_phone_num_web_str'));
                $html .= $this->purchase_package_info_field_show($trans_social, wp_dp_plugin_text_srt('wp_dp_member_add_list_social_reach'));
                $html .= $this->purchase_package_info_field_show($listing_video, wp_dp_plugin_text_srt('wp_dp_listing_video'));

                $dyn_fields_html = '';
                if ( is_array($trans_dynamic_f) && sizeof($trans_dynamic_f) > 0 ) {
                    foreach ( $trans_dynamic_f as $trans_dynamic ) {
                        if ( isset($trans_dynamic['field_type']) && isset($trans_dynamic['field_label']) && isset($trans_dynamic['field_value']) ) {
                            $d_type = $trans_dynamic['field_type'];
                            $d_label = $trans_dynamic['field_label'];
                            $d_value = $trans_dynamic['field_value'];

                            if ( $d_value == 'on' && $d_type == 'single-choice' ) {
                                $html .= '<li><label>' . $d_label . '</label><span><i class="icon-check2"></i></span></li>';
                            } else if ( $d_value != '' && $d_type != 'single-choice' ) {
                                $html .= '<li><label>' . $d_label . '</label><span>' . $d_value . '</span></li>';
                            } else {
                                $html .= '<li><label>' . $d_label . '</label><span><i class="icon-minus"></i></span></li>';
                            }
                        }
                    }
                    // end foreach
                }
                // emd of Dynamic fields
                // other Features
                $html .= '
				</ul>
				</div>';

                $html .= '
				</div>
				</div>';
            }

            return apply_filters('wp_dp_listing_user_subs_package_info', $html, $package_id, $trans_id);
        }

        /**
         * Package Info Field Create
         * @return markup
         */
        public function package_info_field_show($info_meta = '', $index = '', $label = '', $value_plus = '', $absint = '') {
            if ( isset($info_meta[$index]['value']) ) {
                $value = $info_meta[$index]['value'];

                if ( true === $absint ) {
                    $value = absint($info_meta[$index]['value']);
                }

                if ( $value == 0 || ($value != '' && $value != 'on') ) {
                    $html = '<li><label>' . $label . '</label><span>' . $value . ' ' . $value_plus . '</span></li>';
                } else if ( $value != '' && $value == 'on' ) {
                    $html = '<li><label>' . $label . '</label><span><i class="icon-check2"></i></span></li>';
                } else {
                    $html = '<li><label>' . $label . '</label><span><i class="icon-minus"></i></span></li>';
                }

                return $html;
            }
        }

        /**
         * Get New Package info
         * @return html
         */
        public function new_package_info($package_id = 0) {
            global $listing_add_counter;
            $html = '';

            $packg_title = $package_id != '' ? get_the_title($package_id) : '';
            $packg_price = $package_id != '' ? get_post_meta($package_id, 'wp_dp_package_price', true) : '';

            $trans_all_meta = get_post_meta($package_id, 'wp_dp_package_data', true);

            $html .= '<div class="dashboard-element-title"><strong>'. wp_dp_plugin_text_srt('wp_dp_listing_package_info') . '</strong></div>';
            $html .= '<div id="package-detail-' . $package_id . '" class="package-info-sec listing-info-sec">';
//            $html .= '<div class="row">';
//            $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
//            $html .= '<h4>'. $packg_title .' <span>'. wp_dp_get_currency($packg_price, true) .'</span></h4>';
//            $html .= '</div>';
//            $html .= '</div>';  
            $html .= '<div class="row">';
            $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';

            $html .= '<ul class="listing-pkg-points">';
            $html .= '<li><label>'. wp_dp_plugin_text_srt('wp_dp_add_listing_package_name') .'</label><span>'. $packg_title .'</span></li>';
            $html .= $this->package_info_field_show($trans_all_meta, 'number_of_listing_allowed', wp_dp_plugin_text_srt('wp_dp_listing_total_listings'), '', true);
            $html .= $this->package_info_field_show($trans_all_meta, 'listing_duration', wp_dp_plugin_text_srt('wp_dp_listing_listing_duration'), wp_dp_plugin_text_srt('wp_dp_listing_days'), true);
            $html .= $this->package_info_field_show($trans_all_meta, 'number_of_pictures', wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_pictures'));
            $html .= $this->package_info_field_show($trans_all_meta, 'number_of_documents', wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_docs'));
            $html .= $this->package_info_field_show($trans_all_meta, 'number_of_tags', wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_tags'));
            $html .= '<li><label>'. wp_dp_plugin_text_srt('wp_dp_add_listing_package_price') .'</label><span>'. wp_dp_get_currency($packg_price, true) .'</span></li>';
            $html .= $this->package_info_field_show($trans_all_meta, 'phone_number_website', wp_dp_plugin_text_srt('wp_dp_listing_phone_num_web_str'));
            $html .= $this->package_info_field_show($trans_all_meta, 'social_impressions_reach', wp_dp_plugin_text_srt('wp_dp_member_add_list_social_reach'));
            $html .= $this->package_info_field_show($trans_all_meta, 'listing_video', wp_dp_plugin_text_srt('wp_dp_listing_video'));

            $trans_dynamic_f = get_post_meta($package_id, 'wp_dp_package_fields', true);

            if ( is_array($trans_dynamic_f) && sizeof($trans_dynamic_f) > 0 ) {
                foreach ( $trans_dynamic_f as $trans_dynamic ) {
                    if ( isset($trans_dynamic['field_type']) && isset($trans_dynamic['field_label']) && isset($trans_dynamic['field_value']) ) {
                        $d_type = $trans_dynamic['field_type'];
                        $d_label = $trans_dynamic['field_label'];
                        $d_value = $trans_dynamic['field_value'];

                        if ( $d_value == 'on' && $d_type == 'single-choice' ) {
                            $html .= '<li><label>' . $d_label . '</label><span><i class="icon-check2"></i></span></li>';
                        } else if ( $d_value != '' && $d_type != 'single-choice' ) {
                            $html .= '<li><label>' . $d_label . '</label><span>' . $d_value . '</span></li>';
                        } else {
                            $html .= '<li><label>' . $d_label . '</label><span><i class="icon-minus"></i></span></li>';
                        }
                    }
                }
                // end foreach
            }
            // end of Dynamic fields
            // other Features
            $html .= '
			</ul>
			</div>';

            $html .= '
			</div>
			</div>';

            return apply_filters('wp_dp_listing_user_new_package_info', $html, $package_id);
        }

        public function listing_gallery_upload($Fieldname = 'media_upload', $listing_id = '') {
            $img_resized_name = '';
            $listing_gallery = array();
            $count = 0;

            if ( isset($_FILES[$Fieldname]) && $_FILES[$Fieldname] != '' ) {

                $multi_files = isset($_FILES[$Fieldname]) ? $_FILES[$Fieldname] : '';

                if ( isset($multi_files['name']) && is_array($multi_files['name']) ) {
                    $img_name_array = array();
                    foreach ( $multi_files['name'] as $multi_key => $multi_value ) {
                        if ( $multi_files['name'][$multi_key] ) {
                            $loop_file = array(
                                'name' => $multi_files['name'][$multi_key],
                                'type' => $multi_files['type'][$multi_key],
                                'tmp_name' => $multi_files['tmp_name'][$multi_key],
                                'error' => $multi_files['error'][$multi_key],
                                'size' => $multi_files['size'][$multi_key]
                            );

                            $json = array();
                            require_once ABSPATH . 'wp-admin/includes/image.php';
                            require_once ABSPATH . 'wp-admin/includes/file.php';
                            require_once ABSPATH . 'wp-admin/includes/media.php';
                            $allowed_image_types = array(
                                'jpg|jpeg|jpe' => 'image/jpeg',
                                'png' => 'image/png',
                                'gif' => 'image/gif',
                            );

                            $status = wp_handle_upload($loop_file, array( 'test_form' => false, 'mimes' => $allowed_image_types ));

                            if ( empty($status['error']) ) {

                                $image = wp_get_image_editor($status['file']);
                                $img_resized_name = $status['file'];

                                if ( is_wp_error($image) ) {

                                    echo '<span class="error-msg">' . $image->get_error_message() . '</span>';
                                } else {
                                    $wp_upload_dir = wp_upload_dir();
                                    $img_name_array[] = isset($status['url']) ? $status['url'] : '';
                                    $filename = $img_name_array[$count];
                                    $filetype = wp_check_filetype(basename($filename), null);

                                    if ( $filename != '' ) {
                                        // Prepare an array of post data for the attachment.

                                        $attachment = array(
                                            'guid' => ($filename),
                                            'post_mime_type' => $filetype['type'],
                                            'post_title' => preg_replace('/\.[^.]+$/', '', ($loop_file['name'])),
                                            'post_content' => '',
                                            'post_status' => 'inherit'
                                        );
                                        require_once( ABSPATH . 'wp-admin/includes/image.php' );
                                        // Insert the attachment.
                                        $attach_id = wp_insert_attachment($attachment, $status['file']);
                                        if ( $listing_id != '' ) {
                                            wp_update_post(
                                                    array(
                                                        'ID' => $attach_id,
                                                        'post_parent' => $listing_id
                                                    )
                                            );
                                        }
                                        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                                        $attach_data = wp_generate_attachment_metadata($attach_id, $status['file']);
                                        wp_update_attachment_metadata($attach_id, $attach_data);
                                        $listing_gallery[] = $attach_id;
                                        $count ++;
                                    }
                                }
                            }
                        }
                    }

                    $img_resized_name = $listing_gallery;
                } else {
                    $img_resized_name = '';
                }
            }

            return $img_resized_name;
        }

        public function listing_attach_file_upload($Fieldname = 'media_upload', $listing_id = '') {
            $img_resized_name = '';
            $listing_gallery = array();
            $count = 0;

            if ( isset($_FILES[$Fieldname]) && $_FILES[$Fieldname] != '' ) {

                $multi_files = isset($_FILES[$Fieldname]) ? $_FILES[$Fieldname] : '';

                if ( isset($multi_files['name']) && is_array($multi_files['name']) ) {
                    $img_name_array = array();
                    foreach ( $multi_files['name'] as $multi_key => $multi_value ) {
                        if ( $multi_files['name'][$multi_key] ) {
                            $loop_file = array(
                                'name' => $multi_files['name'][$multi_key],
                                'type' => $multi_files['type'][$multi_key],
                                'tmp_name' => $multi_files['tmp_name'][$multi_key],
                                'error' => $multi_files['error'][$multi_key],
                                'size' => $multi_files['size'][$multi_key]
                            );

                            $json = array();
                            require_once ABSPATH . 'wp-admin/includes/image.php';
                            require_once ABSPATH . 'wp-admin/includes/file.php';
                            require_once ABSPATH . 'wp-admin/includes/media.php';

                            $status = wp_handle_upload($loop_file, array( 'test_form' => false ));

                            if ( empty($status['error']) ) {


                                $wp_upload_dir = wp_upload_dir();
                                $img_name_array[] = isset($status['url']) ? $status['url'] : '';
                                $filename = $img_name_array[$count];
                                $filetype = wp_check_filetype(basename($filename), null);

                                if ( $filename != '' ) {
                                    // Prepare an array of post data for the attachment.

                                    $attachment = array(
                                        'guid' => ($filename),
                                        'post_mime_type' => $filetype['type'],
                                        'post_title' => preg_replace('/\.[^.]+$/', '', ($loop_file['name'])),
                                        'post_content' => '',
                                        'post_status' => 'inherit'
                                    );
                                    require_once( ABSPATH . 'wp-admin/includes/image.php' );
                                    // Insert the attachment.
                                    $attach_id = wp_insert_attachment($attachment, $status['file']);
                                    if ( $listing_id != '' ) {
                                        wp_update_post(
                                                array(
                                                    'ID' => $attach_id,
                                                    'post_parent' => $listing_id
                                                )
                                        );
                                    }
                                    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                                    $attach_data = wp_generate_attachment_metadata($attach_id, $status['file']);
                                    wp_update_attachment_metadata($attach_id, $attach_data);
                                    $listing_gallery[] = $attach_id;
                                    $count ++;
                                }
                            }
                        }
                    }

                    $img_resized_name = $listing_gallery;
                } else {
                    $img_resized_name = '';
                }
            }

            return $img_resized_name;
        }

        public function show_listing_pkg_info() {

            $package_id = isset($_POST['pkg_id']) ? $_POST['pkg_id'] : '';

            $package_pric = isset($_POST['p_price']) ? $_POST['p_price'] : '';
            $package_type = isset($_POST['p_type']) ? $_POST['p_type'] : '';

            if ( $package_type == 'purchased' ) {
                $pkg_ids = explode('pt_', $package_id);
                $pkg_id = $pkg_ids[0];
                $t_pkg_id = $pkg_ids[1];
                $html = $this->subs_package_info($pkg_id, $t_pkg_id, 'in_update');
                $show_pay = 'hide';
            } else {
                $html = $this->new_package_info($package_id);
                if ( $package_pric == 'free' ) {
                    $show_pay = 'hide';
                } else {
                    $show_pay = 'show';
                }
            }

            echo json_encode(array( 'html' => $html, 'show_pay' => $show_pay ));
            die;
        }

        public function show_selected_listing_pkg_info($selected_package_id = '') {
            global $wp_dp_form_fields_frontend;
            $pkg_info = array();

            if ( $selected_package_id == '' ) {
                $selected_package_id = wp_dp_get_input('package_id', 0);
            }

            if ( $selected_package_id != '' ) {
                $pkg_info['display'] = false;
                $selected_package_type = get_post_meta($selected_package_id, 'wp_dp_package_type', true);
                $selected_package_price = get_post_meta($selected_package_id, 'wp_dp_package_price', true);

                $package_pric = 'paid';
                $package_type = 'buy';
                $package_id = $selected_package_id;
                $atcive_pkgs = $this->user_all_active_pkgs();
                $package_ids = $paied_package_ids = array();
                if ( ! empty($atcive_pkgs) && is_array($atcive_pkgs) ) {
                    foreach ( $atcive_pkgs as $atcive_pkg ) {
                        $buy_package_id = get_post_meta($atcive_pkg, 'wp_dp_transaction_package', true);
                        if ( $selected_package_id == $buy_package_id ) {
                            $package_type = 'purchased';
                            $package_pric = 'free';
                            $package_id = $buy_package_id . 'pt_' . $atcive_pkg;
                        }
                    }
                }

                $html = '';
                if ( $package_type == 'buy' && $selected_package_type != 'free' ) {
                    if ( $package_type == 'purchased' ) {
                        $pkg_ids = explode('pt_', $package_id);
                        $pkg_id = $pkg_ids[0];
                        $t_pkg_id = $pkg_ids[1];
                        $html = $this->subs_package_info($pkg_id, $t_pkg_id, 'in_update');
                        $show_pay = 'hide';
                    } else {
                        $html = $this->new_package_info($package_id);
                        if ( $package_pric == 'free' ) {
                            $show_pay = 'hide';
                        } else {
                            $show_pay = 'show';
                        }
                    }

                    $package_price = 'paid';
                    if ( $selected_package_price <= 0 ) {
                        $package_price = 'free';
                    }

                    $wp_dp_opt_array = array(
                        'std' => 'on',
                        'cust_id' => 'package-' . $package_id,
                        'cust_name' => 'wp_dp_listing_new_package_used',
                        'cust_type' => 'radio',
                        'classes' => 'wp-dp-dev-req-field',
                        'return' => true,
                        'extra_atr' => 'style="display:none;" checked="checked" data-id="' . $package_id . '" data-ptype="buy" data-ppric="' . $package_price . '"',
                    );
                    $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                    $wp_dp_opt_array = array(
                        'std' => $selected_package_id,
                        'cust_id' => 'wp_dp_listing_package',
                        'cust_name' => 'wp_dp_listing_package',
                        'cust_type' => 'hidden',
                        'classes' => '',
                        'return' => true,
                    );
                    $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);

                    $pkg_info['display'] = true;
                    $pkg_info['html'] = json_encode($html);
                } elseif ( $package_type == 'buy' && $selected_package_type == 'free' ) {
                    $wp_dp_opt_array = array(
                        'std' => 'on',
                        'cust_id' => 'package-' . $package_id,
                        'cust_name' => 'wp_dp_listing_new_package_used',
                        'cust_type' => 'radio',
                        'classes' => 'wp-dp-dev-req-field',
                        'return' => true,
                        'extra_atr' => 'style="display:none;" checked="checked" data-id="' . $package_id . '" data-ptype="buy" data-ppric="free"',
                    );
                    $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                    $wp_dp_opt_array = array(
                        'std' => $selected_package_id,
                        'cust_id' => 'wp_dp_listing_package',
                        'cust_name' => 'wp_dp_listing_package',
                        'cust_type' => 'hidden',
                        'classes' => '',
                        'return' => true,
                    );
                    $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                    $pkg_info['html'] = json_encode($html);
                } elseif ( $package_type == 'purchased' ) {
                    $wp_dp_opt_array = array(
                        'std' => $package_id,
                        'cust_id' => 'package-' . $package_id,
                        'cust_name' => 'wp_dp_listing_active_package',
                        'cust_type' => 'radio',
                        'classes' => 'wp-dp-dev-req-field',
                        'return' => true,
                        'extra_atr' => 'style="display:none;" checked="checked" data-id="' . $package_id . '" data-ptype="purchased" data-ppric="free"',
                    );
                    $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                    $wp_dp_opt_array = array(
                        'std' => $selected_package_id,
                        'cust_id' => 'wp_dp_listing_package',
                        'cust_name' => 'wp_dp_listing_package',
                        'cust_type' => 'hidden',
                        'classes' => '',
                        'return' => true,
                    );
                    $html .= $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                    $pkg_info['html'] = json_encode($html);
                }
            }

            return $pkg_info;
        }

        /**
         * Updating transaction meta into listing meta
         * @return
         */
        public function listing_assign_meta($listing_id = '', $trans_id = '') {
            $assign_array = array();

            $trans_get_value = get_post_meta($trans_id, 'wp_dp_transaction_listing_pic_num', true);
            $assign_array[] = array(
                'key' => 'wp_dp_transaction_listing_pic_num',
                'label' => wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_pictures'),
                'value' => $trans_get_value,
            );
            $trans_get_value = get_post_meta($trans_id, 'wp_dp_transaction_listing_doc_num', true);
            $assign_array[] = array(
                'key' => 'wp_dp_transaction_listing_doc_num',
                'label' => wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_docs'),
                'value' => $trans_get_value,
            );
            $trans_get_value = get_post_meta($trans_id, 'wp_dp_transaction_listing_tags_num', true);
            $assign_array[] = array(
                'key' => 'wp_dp_transaction_listing_tags_num',
                'label' => wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_tags'),
                'value' => $trans_get_value,
            );

            $trans_get_value = get_post_meta($trans_id, 'wp_dp_transaction_listing_phone_website', true);
            $assign_array[] = array(
                'key' => 'wp_dp_transaction_listing_phone_website',
                'label' => wp_dp_plugin_text_srt('wp_dp_listing_phone_num_web_str'),
                'value' => $trans_get_value,
            );


            $trans_get_value = get_post_meta($trans_id, 'wp_dp_transaction_listing_video', true);
            $assign_array[] = array(
                'key' => 'wp_dp_transaction_listing_video',
                'label' => wp_dp_plugin_text_srt('wp_dp_listing_video'),
                'value' => $trans_get_value,
            );

            $trans_get_value = get_post_meta($trans_id, 'wp_dp_transaction_listing_social', true);
            $assign_array[] = array(
                'key' => 'wp_dp_transaction_listing_social',
                'label' => wp_dp_plugin_text_srt('wp_dp_member_add_list_social_reach'),
                'value' => $trans_get_value,
            );

            $trans_get_value = get_post_meta($trans_id, 'wp_dp_transaction_dynamic', true);
            $assign_array[] = array(
                'key' => 'wp_dp_transaction_dynamic',
                'label' => wp_dp_plugin_text_srt('wp_dp_listing_other_features'),
                'value' => $trans_get_value,
            );

            if ( $listing_id != '' && $trans_id != '' ) {
                foreach ( $assign_array as $assign ) {
                    update_post_meta($listing_id, $assign['key'], $assign['value']);
                }
                update_post_meta($listing_id, 'wp_dp_trans_all_meta', $assign_array);
            }

            return $assign_array;
        }

        /**
         * Package Fields List
         * @return
         */
        public function listing_pckage_meta_fields($package_id = '') {
            $assign_array = array();
            $_package_data = get_post_meta($package_id, 'wp_dp_package_data', true);

            $trans_get_value = isset($_package_data['phone_number_website']['value']) ? $_package_data['phone_number_website']['value'] : '';
            $assign_array[] = array(
                'key' => 'phone_number_website',
                'label' => wp_dp_plugin_text_srt('wp_dp_member_add_list_phone_number_website'),
                'value' => $trans_get_value,
            );

            $trans_get_value = isset($_package_data['number_of_pictures']['value']) ? $_package_data['number_of_pictures']['value'] : '';
            $assign_array[] = array(
                'key' => 'number_of_pictures',
                'label' => wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_pictures'),
                'value' => $trans_get_value,
            );
            $trans_get_value = isset($_package_data['number_of_documents']['value']) ? $_package_data['number_of_documents']['value'] : '';
            $assign_array[] = array(
                'key' => 'number_of_documents',
                'label' => wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_docs'),
                'value' => $trans_get_value,
            );
            $trans_get_value = isset($_package_data['social_impressions_reach']['value']) ? $_package_data['social_impressions_reach']['value'] : '';
            $assign_array[] = array(
                'key' => 'social_impressions_reach',
                'label' => wp_dp_plugin_text_srt('wp_dp_member_add_list_social_reach'),
                'value' => $trans_get_value,
            );

            $trans_get_value = isset($_package_data['number_of_tags']['value']) ? $_package_data['number_of_tags']['value'] : '';
            $assign_array[] = array(
                'key' => 'number_of_tags',
                'label' => wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_tags'),
                'value' => $trans_get_value,
            );

            $trans_get_value = isset($_package_data['listing_video']['value']) ? $_package_data['listing_video']['value'] : '';
            $assign_array[] = array(
                'key' => 'listing_video',
                'label' => wp_dp_plugin_text_srt('wp_dp_listing_video'),
                'value' => $trans_get_value,
            );

            return $assign_array;
        }

        /**
         * Date plus period
         * @return date
         */
        public function date_conv($duration, $format = 'days') {
            if ( $format == "months" ) {
                $adexp = date('Y/m/d H:i:s', strtotime("+" . absint($duration) . " months"));
            } else if ( $format == "years" ) {
                $adexp = date('Y/m/d H:i:s', strtotime("+" . absint($duration) . " years"));
            } else {
                $adexp = date('Y/m/d H:i:s', strtotime("+" . absint($duration) . " days"));
            }
            return $adexp;
        }

        /**
         * Array merge
         * @return Array
         */
        public function merge_in_array($array, $value = '', $with_array = true) {
            $ret_array = '';
            if ( is_array($array) && sizeof($array) > 0 && $value != '' ) {
                $array[] = $value;
                $ret_array = $array;
            } else if ( ! is_array($array) && $value != '' ) {
                $ret_array = $with_array ? array( $value ) : $value;
            }
            return $ret_array;
        }

        /**
         * Listing Tag Open
         * @return markup
         */
        public function listing_add_tag_before($class = '', $display = 'none') {
            global $listing_add_counter;
            $listing_add_counter = rand(10000000, 99999999);
            echo '<ul id="wp-dp-dev-main-con-' . $listing_add_counter . '" class="register-add-listing-tab-container ' . $class . '" style="display: ' . $display . ';">';
        }

        /**
         * Listing Tag Close
         * @return markup
         */
        public function listing_add_tag_after() {
            echo '</ul>';
        }

        /**
         * Steps before
         * @return markup
         */
        public function before_listing($html = '') {
            global $wp_dp_plugin_options, $Payment_Processing;
            $wp_dp_listing_announce_title = isset($wp_dp_plugin_options['wp_dp_listing_announce_title']) ? $wp_dp_plugin_options['wp_dp_listing_announce_title'] : '';
            $wp_dp_listing_announce_description = isset($wp_dp_plugin_options['wp_dp_listing_announce_description']) ? $wp_dp_plugin_options['wp_dp_listing_announce_description'] : '';
            $wp_dp_announce_bg_color = isset($wp_dp_plugin_options['wp_dp_announce_bg_color']) ? $wp_dp_plugin_options['wp_dp_announce_bg_color'] : '#2b8dc4';
            $listing_color = 'style="background-color:' . $wp_dp_announce_bg_color . '"';
            update_option('wooCommerce_current_page', wp_dp_server_protocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

            $wp_dp_order_data = $Payment_Processing->custom_order_status_display();
            if ( isset($wp_dp_order_data) && ! empty($wp_dp_order_data) ) {
                ?>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="field-holder">
                        <div class="user-message alert" <?php echo esc_html($listing_color); ?>>
                            <a href="#" data-dismiss="alert" class="close"><i class="icon-cross"></i></a>
                <?php
                global $woocommerce;
                if ( class_exists('WooCommerce') ) {
                    WC()->payment_gateways();
                    echo '<h2>' . $wp_dp_order_data['status_message'] . '</h2>';
                    do_action('woocommerce_thankyou_' . $wp_dp_order_data['payment_method'], $wp_dp_order_data['order_id']);
                    $Payment_Processing->remove_raw_data($wp_dp_order_data['order_id']);
                }
                ?>
                        </div>
                    </div>
                </div>
                <?php
                $active = '';
            }

            $html .= '
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="field-holder">
						<div class="user-message alert" ' . $listing_color . '>
							<a href="#" data-dismiss="alert" class="close"><i class="icon-cross"></i></a>
							<h2>' . $wp_dp_listing_announce_title . '</h2>
							<p>' . htmlspecialchars_decode($wp_dp_listing_announce_description) . '</p>
						</div>				
					</div>
				</div>';
            echo force_balance_tags($html);
        }

        /**
         * Listing Submit Msg
         * @return markup
         */
        public function listing_submit_msg($msg = '') {

            $html = '';
            if ( $msg != '' ) {
                $msg_arr = array( 'msg' => $msg, 'type' => 'success' );
                $msg_arr = json_encode($msg_arr);
                $html = '
				<script>
				jQuery(document).ready(function () {
					wp_dp_show_response(' . $msg_arr . ');
				});
				</script>';
            }
            echo force_balance_tags($html);
        }

        /**
         * Steps after
         * @return markup
         */
        public function after_listing($html = '') {
            global $listing_add_counter;
            echo force_balance_tags($html);
        }

        /**
         * Social Post
         * @return
         */
        public function social_post_after_activation($listing_id) {

            global $wp_dp_plugin_options;

            if ( $listing_id == '' ) {
                return;
            }
            $listing_post = get_post($listing_id);

            if ( is_object($listing_post) ) {
                $name = $listing_post->post_title;
                $name = apply_filters('the_title', $name);
                $name = html_entity_decode($name, ENT_QUOTES, get_bloginfo('charset'));
                $name = strip_tags($name);
                $name = strip_shortcodes($name);
                $content = $listing_post->post_content;
                $content = apply_filters('the_content', $content);
                $content = wp_kses($content, array());
                $description = $content;
                $excerpt = '';
                $caption = '';
                $user_nicename = '';

                $attachmenturl = '';

                if ( function_exists('listing_gallery_first_image') ) {
                    $gallery_image_args = array(
                        'listing_id' => $listing_id,
                        'size' => 'full',
                        'class' => '',
                        'return_type' => 'url',
                        'default_image_src' => '',
                        'img_extra_atr' => '',
                    );
                    $listing_gallery_first_image = listing_gallery_first_image($gallery_image_args);
                    $attachmenturl = ($listing_gallery_first_image);
                }
                $link = get_permalink($listing_post->ID);
            } else {
                return;
            }
            
            // Twitter Posting Start
            $wp_dp_twitter_posting_switch = isset($wp_dp_plugin_options['wp_dp_twitter_autopost_switch']) ? $wp_dp_plugin_options['wp_dp_twitter_autopost_switch'] : '';
            //$wp_dp_twitter_posting_switch = 'off';
            if ( $wp_dp_twitter_posting_switch == 'on' ) {

                //if ( ! class_exists('SMAPTwitterOAuth') ) {
                    require_once( wp_dp::plugin_path() . '/frontend/templates/dashboards/member/social-api/twitteroauth.php');
                //}
                    
                $tappid = isset($wp_dp_plugin_options['wp_dp_consumer_key']) ? $wp_dp_plugin_options['wp_dp_consumer_key'] : '';
                $tappsecret = isset($wp_dp_plugin_options['wp_dp_consumer_secret']) ? $wp_dp_plugin_options['wp_dp_consumer_secret'] : '';
                $taccess_token = isset($wp_dp_plugin_options['wp_dp_access_token']) ? $wp_dp_plugin_options['wp_dp_access_token'] : '';
                $taccess_token_secret = isset($wp_dp_plugin_options['wp_dp_access_token_secret']) ? $wp_dp_plugin_options['wp_dp_access_token_secret'] : '';

                $post_twitter_image_permission = 1;

                $messagetopost = '{POST_TITLE} - {PERMALINK}{POST_CONTENT}';

                $img_status = "";
                if ( $post_twitter_image_permission == 1 ) {

                    $wp_remote_get_args = array(
                        'timeout' => 50,
                        'compress' => false,
                        'decompress' => true,
                    );

                    $img = array();
                    if ( $attachmenturl != "" )
                        $img = wp_remote_get($attachmenturl, $wp_remote_get_args);

                    if ( is_array($img) ) {
                        if ( isset($img['body']) && trim($img['body']) != '' ) {
                            $image_found = 1;
                            if ( ($img['headers']['content-length']) && trim($img['headers']['content-length']) != '' ) {
                                $img_size = $img['headers']['content-length'] / (1024 * 1024);
                                if ( $img_size > 3 ) {
                                    $image_found = 0;
                                    $img_status = "Image skipped(greater than 3MB)";
                                }
                            }

                            $img = $img['body'];
                        } else
                            $image_found = 0;
                    }
                }
                ///Twitter upload image end/////

                $messagetopost = str_replace("&nbsp;", "", $messagetopost);
                preg_match_all("/{(.+?)}/i", $messagetopost, $matches);
                $matches1 = $matches[1];
                $substring = "";
                $islink = 0;
                $issubstr = 0;
                $len = 118;
                if ( $image_found == 1 )
                    $len = $len - 24;

                foreach ( $matches1 as $key => $val ) {
                    $val = "{" . $val . "}";
                    if ( $val == "{POST_TITLE}" ) {
                        $replace = $name;
                    }
                    if ( $val == "{POST_CONTENT}" ) {
                        $replace = $description;
                    }
                    if ( $val == "{PERMALINK}" ) {
                        $replace = "{PERMALINK}";
                        $islink = 1;
                    }
                    if ( $val == "{POST_EXCERPT}" ) {
                        $replace = $excerpt;
                    }
                    if ( $val == "{BLOG_TITLE}" )
                        $replace = $caption;

                    if ( $val == "{USER_NICENAME}" )
                        $replace = $user_nicename;

                    $append = mb_substr($messagetopost, 0, mb_strpos($messagetopost, $val));

                    if ( mb_strlen($append) < ($len - mb_strlen($substring)) ) {
                        $substring.=$append;
                    } else if ( $issubstr == 0 ) {
                        $avl = $len - mb_strlen($substring) - 4;
                        if ( $avl > 0 )
                            $substring .= mb_substr($append, 0, $avl) . "...";

                        $issubstr = 1;
                    }
                    if ( $replace == "{PERMALINK}" ) {
                        $chkstr = mb_substr($substring, 0, -1);
                        if ( $chkstr != " " ) {
                            $substring.=" " . $replace;
                            $len = $len + 12;
                        } else {
                            $substring.=$replace;
                            $len = $len + 11;
                        }
                    } else {

                        if ( mb_strlen($replace) < ($len - mb_strlen($substring)) ) {
                            $substring.=$replace;
                        } else if ( $issubstr == 0 ) {

                            $avl = $len - mb_strlen($substring) - 4;
                            if ( $avl > 0 )
                                $substring .= mb_substr($replace, 0, $avl) . "...";

                            $issubstr = 1;
                        }
                    }
                    $messagetopost = mb_substr($messagetopost, mb_strpos($messagetopost, $val) + strlen($val));
                }

                if ( $islink == 1 ) {
                    $substring = str_replace('{PERMALINK}', $link, $substring);
                }
                
                $twobj = new tmhOAuth(array( 'consumer_key' => $tappid, 'consumer_secret' => $tappsecret, 'user_token' => $taccess_token, 'user_secret' => $taccess_token_secret, 'curl_ssl_verifypeer' => false ));
                    
                if ( $image_found == 1 && $post_twitter_image_permission == 1 ) {
                    $resultfrtw = $twobj->request('POST', 'https://api.twitter.com/1.1/statuses/update_with_media.json', array( 'media[]' => $img, 'status' => $substring ), true, true);
                    if ( $resultfrtw != 200 ) {
                        if ( $twobj->response['response'] != "" ) {
                            $tw_publish_status["statuses/update_with_media"] = print_r($twobj->response['response'], true);
                        } else {
                            $tw_publish_status["statuses/update_with_media"] = $resultfrtw;
                        }
                    }
                } else {
                    $resultfrtw = $twobj->request('POST', $twobj->url('1.1/statuses/update'), array( 'status' => $substring ));
                    if ( $resultfrtw != 200 ) {
                        if ( $twobj->response['response'] != "" ) {
                            $tw_publish_status["statuses/update"] = print_r($twobj->response['response'], true);
                        } else {
                            $tw_publish_status["statuses/update"] = $resultfrtw;
                        }
                    } else if ( $img_status != "" ) {
                        $tw_publish_status["statuses/update_with_media"] = $img_status;
                    }
                }
            }

            // Linkedin
            $lk_client_id = isset($wp_dp_plugin_options['wp_dp_linkedin_app_id']) ? $wp_dp_plugin_options['wp_dp_linkedin_app_id'] : '';
            $lk_secret_id = isset($wp_dp_plugin_options['wp_dp_linkedin_secret']) ? $wp_dp_plugin_options['wp_dp_linkedin_secret'] : '';
            $lk_posting_switch = isset($wp_dp_plugin_options['wp_dp_linkedin_autopost_switch']) ? $wp_dp_plugin_options['wp_dp_linkedin_autopost_switch'] : '';

            $lnpost_permission = 1;

            if ( $lk_posting_switch == 'on' && $lk_client_id != "" && $lk_secret_id != "" && $lnpost_permission == 1 ) {
                if ( ! class_exists('SMAPLinkedInOAuth2') ) {
                     require_once( wp_dp::plugin_path() . '/frontend/templates/dashboards/member/social-api/linkedin.php');
                }

                $authorized_access_token = isset($wp_dp_plugin_options['wp_dp_linkedin_access_token']) ? $wp_dp_plugin_options['wp_dp_linkedin_access_token'] : '';

                $lmessagetopost = '{POST_TITLE} - {PERMALINK}{POST_CONTENT}';

                $contentln = array();

                $description_li = wp_dp_listing_string_limit($description, 362);
                $caption_li = wp_dp_listing_string_limit($caption, 200);
                $name_li = wp_dp_listing_string_limit($name, 200);

                $message1 = str_replace('{POST_TITLE}', $name, $lmessagetopost);
                $message2 = str_replace('{BLOG_TITLE}', $caption, $message1);
                $message3 = str_replace('{PERMALINK}', $link, $message2);
                $message4 = str_replace('{POST_EXCERPT}', $excerpt, $message3);
                $message5 = str_replace('{POST_CONTENT}', $description, $message4);
                $message5 = str_replace('{USER_NICENAME}', $user_nicename, $message5);

                $message5 = str_replace("&nbsp;", "", $message5);

                $contentln['comment'] = $message5;
                $contentln['content']['title'] = $name_li;
                $contentln['content']['submitted-url'] = $link;
                if ( $attachmenturl != "" ) {
                    $contentln['content']['submitted-image-url'] = $attachmenturl;
                }
                $contentln['content']['description'] = $description_li;

                $contentln['visibility']['code'] = 'anyone';

                $ln_publish_status = array();

                $ObjLinkedin = new SMAPLinkedInOAuth2($authorized_access_token);
                $contentln = wp_dp_linkedin_attachment_metas($contentln, $link);

                $arrResponse = $ObjLinkedin->shareStatus($contentln);
            }

            // Facebook
            $fb_posting_switch = isset($wp_dp_plugin_options['wp_dp_facebook_autopost_switch']) ? $wp_dp_plugin_options['wp_dp_facebook_autopost_switch'] : '';

            $fb_app_id = isset($wp_dp_plugin_options['wp_dp_facebook_app_id']) ? $wp_dp_plugin_options['wp_dp_facebook_app_id'] : '';
            $fb_secret = isset($wp_dp_plugin_options['wp_dp_facebook_secret']) ? $wp_dp_plugin_options['wp_dp_facebook_secret'] : '';
            $fb_access_token = isset($wp_dp_plugin_options['wp_dp_facebook_access_token']) ? $wp_dp_plugin_options['wp_dp_facebook_access_token'] : '';

            if ( $fb_posting_switch == 'on' && $fb_app_id != "" && $fb_secret != "" && $fb_access_token != "" ) {
                $descriptionfb_li = wp_dp_listing_string_limit($description, 10000);

                if ( ! class_exists('SMAPFacebook') ) {
                     require_once( wp_dp::plugin_path() . '/frontend/templates/dashboards/member/social-api/facebook.php');
                }
                $disp_type = 'feed';

                $lmessagetopost = '{POST_TITLE} - {PERMALINK}  {POST_CONTENT}';

                $wp_dp_listing_pages_ids = get_option('wp_dp_fb_pages_ids');
                if ( $wp_dp_listing_pages_ids == "" ) {
                    $wp_dp_listing_pages_ids = -1;
                }

                $wp_dp_listing_pages_ids1 = explode(",", $wp_dp_listing_pages_ids);

                foreach ( $wp_dp_listing_pages_ids1 as $key => $value ) {
                    if ( $value != -1 ) {
                        $value1 = explode("-", $value);
                        $acces_token = $value1[1];
                        $page_id = $value1[0];

                        $fb = new Facebook(array(
                            'appId' => $fb_app_id,
                            'secret' => $fb_secret,
                            'cookie' => true
                        ));
                        $message1 = str_replace('{POST_TITLE}', $name, $lmessagetopost);
                        $message2 = str_replace('{BLOG_TITLE}', $caption, $message1);
                        $message3 = str_replace('{PERMALINK}', $link, $message2);
                        $message4 = str_replace('{POST_EXCERPT}', $excerpt, $message3);
                        $message5 = str_replace('{POST_CONTENT}', $description, $message4);
                        $message5 = str_replace('{USER_NICENAME}', $user_nicename, $message5);

                        $message5 = str_replace("&nbsp;", "", $message5);

                        $attachment = array(
                            'message' => $message5,
                            'access_token' => $acces_token,
                            'link' => $link,
                            'name' => $name,
                            'caption' => $caption,
                            'description' => $descriptionfb_li,
                            'actions' => array(
                                array(
                                    'name' => $name,
                                    'link' => $link
                                )
                            ),
                            'picture' => $attachmenturl
                        );

                        $attachment = wp_dp_fbapp_attachment_metas($attachment, $link);

                        $result = $fb->api('/' . $page_id . '/' . $disp_type . '/', 'post', $attachment);
                    }
                }
            }
        }

        /**
         * Assign free package to member
         * @return
         */
        public function assign_free_package_to_member($member_id) {
            global $wp_dp_plugin_options;

            $free_package_switch = isset($wp_dp_plugin_options['wp_dp_member_register_package']) ? $wp_dp_plugin_options['wp_dp_member_register_package'] : '';
            $package_id = isset($wp_dp_plugin_options['wp_dp_member_assign_package']) ? $wp_dp_plugin_options['wp_dp_member_assign_package'] : '';

            if ( $free_package_switch == 'on' && $package_id > 0 ) {
                return $this->wp_dp_listing_add_transaction('assign-package', 0, $package_id, $member_id);
            }
        }

        public function user_all_active_pkgs_ids() {
            // get all buy packages 
            $user_active_transections = '';
            $user_active_pkgs = '';
            if ( is_user_logged_in() ) {
                $user_active_transections = array();
                $user_active_transections = $this->user_all_active_pkgs();
            }
            $html = '';
            if ( is_array($user_active_transections) && sizeof($user_active_transections) > 0 ) {
                $user_active_pkgs = array();
                $pkgs_counter = 1;
                $html .= '<div class="all-pckgs-sec">';
                foreach ( $user_active_transections as $atcive_pkg ) {
                    $user_active_pkgs[] = get_post_meta($atcive_pkg, 'wp_dp_transaction_package', true);
                }
            }
            return $user_active_pkgs;
        }

    }

}

new Wp_dp_Member_Register_User_And_Listing();
