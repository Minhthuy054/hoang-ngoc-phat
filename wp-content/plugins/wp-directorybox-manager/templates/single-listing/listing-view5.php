<?php
/**
 * The template for displaying single listing
 *
 */
global $post, $wp_dp_plugin_options, $wp_dp_theme_options, $wp_dp_post_listing_types, $Wp_dp_Captcha, $wp_dp_form_fields_frontend;
$post_id = $post->ID;
wp_dp_listing_views_count($post_id);
$default_listing_no_custom_fields = isset($wp_dp_plugin_options['wp_dp_listing_no_custom_fields']) ? $wp_dp_plugin_options['wp_dp_listing_no_custom_fields'] : '';

$wp_dp_listing_sidebar_switch = isset($wp_dp_plugin_options['wp_dp_listing_sidebar_switch']) ? $wp_dp_plugin_options['wp_dp_listing_sidebar_switch'] : '';
$wp_dp_listing_detail_select_sidebar = isset($wp_dp_plugin_options['wp_dp_listing_detail_select_sidebar']) ? $wp_dp_plugin_options['wp_dp_listing_detail_select_sidebar'] : '';




$wp_dp_social_network = isset($wp_dp_plugin_options['wp_dp_listing_detail_page_social_network']) ? $wp_dp_plugin_options['wp_dp_listing_detail_page_social_network'] : '';

$listing_limits = get_post_meta($post_id, 'wp_dp_trans_all_meta', true);
$wp_dp_listing_price_options = get_post_meta($post_id, 'wp_dp_listing_price_options', true);
$wp_dp_listing_price = '';
if ( $wp_dp_listing_price_options == 'price' ) {
    $wp_dp_listing_price = get_post_meta($post_id, 'wp_dp_listing_price', true);
} else if ( $wp_dp_listing_price_options == 'on-call' ) {
    $wp_dp_listing_price = wp_dp_plugin_text_srt('wp_dp_nearby_listings_price_on_request');
}

$wp_dp_var_post_social_sharing = $wp_dp_plugin_options['wp_dp_social_share'];
wp_enqueue_script('wp-dp-prettyPhoto');
wp_enqueue_script('wp-dp-reservation-functions');
wp_enqueue_style('wp-dp-prettyPhoto');
wp_enqueue_script('wp-dp-listing-map');

wp_enqueue_style('cs_icons_data_css_File-Icons');

// checking review in on in listing type
$wp_dp_listing_type = get_post_meta($post_id, 'wp_dp_listing_type', true);
/*
 * member data
 */
$wp_dp_listing_member_id = get_post_meta($post_id, 'wp_dp_listing_member', true);
$wp_dp_listing_member_id = isset($wp_dp_listing_member_id) ? $wp_dp_listing_member_id : '';
$wp_dp_post_loc_address_member = get_post_meta($wp_dp_listing_member_id, 'wp_dp_post_loc_address_member', true);
$wp_dp_member_title = '';
if ( isset($wp_dp_listing_member_id) && $wp_dp_listing_member_id <> '' ) {
    $wp_dp_member_title = get_the_title($wp_dp_listing_member_id);
}
$wp_dp_member_link = 'javascript:void(0)';
if ( isset($wp_dp_listing_member_id) && $wp_dp_listing_member_id <> '' ) {
    $wp_dp_member_link = get_the_permalink($wp_dp_listing_member_id);
}
$member_image_id = get_post_meta($wp_dp_listing_member_id, 'wp_dp_profile_image', true);
$member_image = wp_get_attachment_url($member_image_id);
$wp_dp_member_phone_num = get_post_meta($wp_dp_listing_member_id, 'wp_dp_phone_number', true);
$wp_dp_member_email_address = get_post_meta($wp_dp_listing_member_id, 'wp_dp_email_address', true);
$wp_dp_member_email_address = isset($wp_dp_member_email_address) ? $wp_dp_member_email_address : '';
/*
 * member data end 
 */

$wp_dp_post_loc_address_listing = get_post_meta($post_id, 'wp_dp_post_loc_address_listing', true);
$wp_dp_post_loc_latitude = get_post_meta($post_id, 'wp_dp_post_loc_latitude_listing', true);
$wp_dp_post_loc_longitude = get_post_meta($post_id, 'wp_dp_post_loc_longitude_listing', true);
$wp_dp_listing_type = isset($wp_dp_listing_type) ? $wp_dp_listing_type : '';
if ( $listing_type_post = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type') ) {
    $listing_type_id = $listing_type_post->ID;
}
$listing_type_id = isset($listing_type_id) ? $listing_type_id : '';
$listing_type_id = wp_dp_wpml_lang_page_id($listing_type_id, 'listing-type');
$wp_dp_listing_type_feature_img_switch = get_post_meta($listing_type_id, 'wp_dp_social_share_element', true);
$wp_dp_listing_type_price_switch = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
$wp_dp_listing_type_cover_img_switch = get_post_meta($post_id, 'wp_dp_transaction_listing_cimage', true);
$wp_dp_listing_type_claim_list_switch = get_post_meta($listing_type_id, 'wp_dp_claim_listing_element', true);
$wp_dp_listing_type_flag_list_switch = get_post_meta($listing_type_id, 'wp_dp_report_spams_element', true);
$wp_dp_listing_type_social_share_switch = get_post_meta($listing_type_id, 'wp_dp_social_share_element', true);
$wp_dp_print_switch = get_post_meta($listing_type_id, 'wp_dp_print_switch', true);
$wp_dp_claim_switch = get_post_meta($listing_type_id, 'wp_dp_claim_switch', true);
$wp_dp_flag_switch = get_post_meta($listing_type_id, 'wp_dp_flag_switch', true);
$wp_dp_listing_type_det_desc_switch = get_post_meta($listing_type_id, 'wp_dp_listing_detail_length_switch', true);
$wp_dp_listing_type_det_desc_length = get_post_meta($listing_type_id, 'wp_dp_listing_desc_detail_length', true);
$wp_dp_listing_type_walkscores_switch = get_post_meta($listing_type_id, 'wp_dp_walkscores_options_element', true);
$wp_dp_listing_type_vieww = get_post_meta($listing_type_id, 'wp_dp_listing_detail_page', true);
$wp_dp_listing_type_vieww = isset($wp_dp_listing_type_vieww) ? $wp_dp_listing_type_vieww : '';
$wp_dp_env_res_all_lists = get_post_meta($post_id, 'wp_dp_env_res', true);
$wp_dp_env_res_title = get_post_meta($post_id, 'wp_dp_env_res_heading', true);
$wp_dp_env_res_description = get_post_meta($post_id, 'wp_dp_env_res_description', true);

$wp_dp_detail_view5_sticky_navigation = get_post_meta($listing_type_id, 'wp_dp_detail_view5_sticky_navigation', true);




/*
 * Banner slider data
 */
$gallery_ids_list = get_post_meta($post_id, 'wp_dp_detail_page_gallery_ids', true);
$gallery_pics_allowed = get_post_meta($post_id, 'wp_dp_transaction_listing_pic_num', true);
$count_all = ( isset($gallery_ids_list) && is_array($gallery_ids_list) && sizeof($gallery_ids_list) > 0 ) ? count($gallery_ids_list) : 0;
if ( $count_all > $gallery_pics_allowed ) {
    $count_all = $gallery_pics_allowed;
}

/*
 * Listing Elements Settings
 */
$wp_dp_enable_features_element = get_post_meta($post_id, 'wp_dp_enable_features_element', true);
$wp_dp_enable_video_element = get_post_meta($post_id, 'wp_dp_enable_video_element', true);
$wp_dp_enable_yelp_places_element = get_post_meta($post_id, 'wp_dp_enable_yelp_places_element', true);
$wp_dp_enable_appartment_for_sale_element = get_post_meta($post_id, 'wp_dp_enable_appartment_for_sale_element', true);
$wp_dp_enable_file_attachments_element = get_post_meta($post_id, 'wp_dp_enable_file_attachments_element', true);
$wp_dp_enable_floot_plan_element = get_post_meta($post_id, 'wp_dp_enable_floot_plan_element', true);
$wp_dp_listing_is_urgent = wp_dp_check_promotion_status($post_id, 'urgent');

/*
 * Banner slider data end 
 */

if ( $wp_dp_listing_type_det_desc_length < 0 ) {
    $wp_dp_listing_type_det_desc_length = 50;
}

if ( isset($_GET['price']) && $_GET['price'] == 'yes' ) {
    echo wp_dp_all_currencies();
    echo wp_dp_get_currency(100, true);
}

$no_image_class = '';
if ( ! has_post_thumbnail() ) {
    $no_image_class = ' no-image';
}

//get custom fields
$cus_field_arr = array();
$listing_type = '';
$listing_type = get_post_meta($post_id, 'wp_dp_listing_type', true);
$wp_dp_listing_zoom = get_post_meta($post_id, 'wp_dp_post_loc_zoom_listing', true);

$member_profile_status = get_post_meta($post_id, 'listing_member_status', true);

$wp_dp_listing_type_cus_fields = $wp_dp_post_listing_types->wp_dp_types_custom_fields_array($listing_type);

$listing_type_id = '';
if ( $listing_type != '' ) {
    $listing_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'listing-type', 'name' => "$listing_type", 'post_status' => 'publish' ));
    $listing_type_id = isset($listing_type_post[0]->ID) ? $listing_type_post[0]->ID : 0;
}

$map_topper = 'on';

$default_zoom_level = ( isset($wp_dp_plugin_options['wp_dp_map_zoom_level']) && $wp_dp_plugin_options['wp_dp_map_zoom_level'] != '' ) ? $wp_dp_plugin_options['wp_dp_map_zoom_level'] : 10;
if ( $wp_dp_listing_zoom == '' || $wp_dp_listing_zoom == 0 ) {
    $wp_dp_listing_zoom = $default_zoom_level;
}

// get all categories
$wp_dp_cate = '';
$wp_dp_cate_str = '';
$wp_dp_listing_category = get_post_meta($post_id, 'wp_dp_listing_category', true);

if ( ! empty($wp_dp_listing_category) && is_array($wp_dp_listing_category) ) {
    $comma_flag = 0;
    foreach ( $wp_dp_listing_category as $cate_slug => $cat_val ) {
        $wp_dp_cate = get_term_by('slug', $cat_val, 'listing-category');

        if ( ! empty($wp_dp_cate) ) {
            $cate_link = wp_dp_listing_category_link($listing_type_id, $cat_val);
            if ( $comma_flag != 0 ) {
                $wp_dp_cate_str .= ', ';
            }
            $wp_dp_cate_str = '<a href="' . $cate_link . '">' . $wp_dp_cate->name . '</a>';
            $comma_flag ++;
        }
    }
}

$top_map = isset($wp_dp_plugin_options['wp_dp_listing_detail_page_top_map']) ? $wp_dp_plugin_options['wp_dp_listing_detail_page_top_map'] : '';
$top_slider = isset($wp_dp_plugin_options['wp_dp_listing_detail_page_top_slider']) ? $wp_dp_plugin_options['wp_dp_listing_detail_page_top_slider'] : '';
$wp_dp_single_view = 'detail_view5';

$map_view_class = '';
$banner_view_class = 'hidden';

do_action('wp_dp_sidebar_gallery_map_html', $post_id);
$http_request = wp_dp_server_protocol();


$walk_score_class = ' no-walk-score';
if ( ! empty($wp_dp_listing_type_walkscores_switch) && $wp_dp_listing_type_walkscores_switch == 'on' ) {
    $walk_score_class = '';
}


$sticky_navigation_class = ' detail-nav-non-sticky';
if ( isset($wp_dp_detail_view5_sticky_navigation) && $wp_dp_detail_view5_sticky_navigation == 'on' ) {
    $sticky_navigation_class = '';
}



do_action('wp_dp_enquire_arrange_buttons_element_html',$post_id,'view-5');

?>

<div id="main">
    <div class="page-section">
        <div class="listing-detail detail-v5" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Product">
            <div class="listing-detail-title-area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                            <div class="list-detail-options<?php echo esc_html($walk_score_class); ?>">
                                <div class="title-area">
                                    <?php
                                    echo '<div class="title-with-price">';
                                    if ( get_the_title($post_id) != '' ) {
                                        ?>

                                        <h2 itemprop="name"><?php the_title(); ?></h2>
                                        <?php
                                    }

                                    $is_sold = wp_dp_is_listing_sold($post_id);
                                    if ( $wp_dp_listing_type_price_switch == 'on' && (( $wp_dp_listing_price_options != 'none' && $is_sold != true ) || $wp_dp_listing_price_options == 'on-call') ) {
                                        ?>
                                        <div class="price-holder">
                                            <span class="listing-price" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Offer">
                                                <?php
                                                if ( $wp_dp_listing_price_options == 'on-call' ) {
                                                    echo '<span class="new-price text-color">' . force_balance_tags($wp_dp_listing_price) . '</span>';
                                                } else {
                                                    $listing_info_price = wp_dp_listing_price($post_id, $wp_dp_listing_price, '<span class="price" content="' . $wp_dp_listing_price . '" itemprop="price"><em>' . wp_dp_plugin_text_srt('wp_dp_trans_pop_price') . ':</em>', '</span>', '<span class="special-price" content="" itemprop="price">', '</span>');
                                                    $wp_dp_get_currency_sign = wp_dp_get_currency_sign('code');
                                                    echo '<span itemprop="priceCurrency" style="display:none;" content="' . $wp_dp_get_currency_sign . '"></span>';
                                                    echo '<span class="new-price text-color">' . force_balance_tags($listing_info_price) . '</span>';
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    <?php } else if ( $wp_dp_listing_type_price_switch == 'on' && $wp_dp_listing_price_options == 'price' && $is_sold == true ) {
                                        ?>
                                        <div class="price-holder">
                                            <span  class="listing-price" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Offer">
                                                <?php echo'<span class="new-price text-color" itemprop="price">' . wp_dp_plugin_text_srt('wp_dp_listing_sold_out_txt') . '</span>'; ?>
                                            </span>
                                        </div>
                                        <?php
                                    }
                                    echo '</div>';
                                    echo '<div class="reviews-with-hours">';
                                        do_action('wp_rem_reviews_listing_ui', $post_id);
                                        echo '<div class="opening-hours-block">';
                                            do_action( 'wp_dp_opening_hours_element_html', $post_id, 'listings_hours' );
                                        echo '</div>';
                                    echo '</div>';
                                    if ( isset($wp_dp_post_loc_address_listing) && $wp_dp_post_loc_address_listing != '' ) {
                                        ?>
                                        <address><i class="icon-location-pin2"></i><?php echo esc_html($wp_dp_post_loc_address_listing); ?></address>
                                    <?php } ?>
                                    <?php if ( $wp_dp_claim_switch != 'off' || $wp_dp_flag_switch != 'off' ) { ?>
                                        <div class="claims-holder">
                                            <?php
                                            if ( $wp_dp_claim_switch != 'off' ) {
                                                do_action('claim_listing_from', $post_id, '<div class="claim-listing">', '</div>');
                                            }
                                            if ( $wp_dp_flag_switch != 'off' ) {
                                                do_action('flag_listing_from', $post_id, '<div class="flag-listing">', '</div>');
                                            }
                                            ?>

                                            <?php
                                            // wrap in this this due to enquire arrange button style.
                                            $before_label = wp_dp_plugin_text_srt('wp_dp_listing_v5_save_to_favourite');
                                            $after_label = wp_dp_plugin_text_srt('wp_dp_listing_detail_five_favourited');
                                            $figcaption_div = true;
                                            $book_mark_args = array(
                                                'before_label' => $before_label,
                                                'after_label' => $after_label,
                                                'before_icon' => '<i class="icon-heart2"></i>',
                                                'after_icon' => '<i class="icon-heart2"></i>',
                                            );
                                            do_action('wp_dp_favourites_frontend_button', $post_id, $book_mark_args, $figcaption_div);
                                            ?>
                                        </div>
                                    <?php } ?>

                                </div>

                            </div>
                            <?php
                            if ( ! empty($wp_dp_listing_type_walkscores_switch) && $wp_dp_listing_type_walkscores_switch == 'on' ) {
                                do_action('wp_dp_listing_walk_score_results_html', $post_id, 'fancy');
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <?php
                    if ( $member_profile_status == 'active' ) {
                        ?>
                        <div class="page-content col-lg-8 col-md-8 col-sm-12 col-xs-12">

                            <?php
                            do_action('wp_dp_images_gallery_element_html', $post_id, 'view-5');

                            // DESCRIPTION AND FEATURE CONTENT START
                            $my_postid = $post_id; //This is page id or post id
                            $content_post = get_post($my_postid);
                            $content = $content_post->post_content;
                            $content = apply_filters('the_content', $content);
                            $content = str_replace(']]>', ']]&gt;', $content);
                            // DESCRIPTION AND FEATURE CONTENT END   

                            /*
                             * Key details start
                             */
                            $li_col_class = ' class="col-lg-4 col-md-4 col-sm-6 col-xs-12"';
                            do_action('wp_dp_custom_fields_html', $post_id, 'view-5', $li_col_class);

                            /*
                             * Key details end
                             */


                            if ( $wp_dp_enable_features_element != 'off' ) {
                                do_action('wp_dp_features_element_html', $post_id);
                            }

                            $element_title = get_post_meta($listing_type_id, 'wp_dp_listing_type_title_listing_desc', true);
                            if ( $content != '' ) {
                                ?>
                                <div class="description-holder" itemprop="description">
                                    <div class="listing-dsec">
                                        <div class="element-title">
                                            <h3><?php echo esc_html($element_title); ?></h3>
                                        </div>
                                        <?php echo force_balance_tags($content); ?>
                                    </div> 
                                </div>
                                <?php
                            }

                            if ( $wp_dp_enable_appartment_for_sale_element != 'off' ) {
                                do_action('wp_dp_listing_apartment_html', $post_id);
                            }

                            $type_floor_plans = get_post_meta($listing_type_id, 'wp_dp_floor_plans_options_element', true);
                            if ( $type_floor_plans == 'on' && $wp_dp_enable_floot_plan_element != 'off' ) {
                                $floor_plans = get_post_meta($post_id, 'wp_dp_floor_plans', true);
                                $floor_plans = empty($floor_plans) ? array() : $floor_plans;
                                $element_title = get_post_meta($listing_type_id, 'wp_dp_listing_type_title_floor_plan', true);

                                if ( count($floor_plans) > 0 ) :
                                    ?>
                                    <div class="architecture-holder">
                                        <div class="element-title">
                                            <h3><?php echo esc_html($element_title); ?></h3>
                                        </div>
                                        <?php $active = 'active'; ?>
                                        <ul class="nav nav-tabs">
                                            <?php
                                            $counter = 1;
                                            foreach ( $floor_plans as $key => $floor_plan ) :
                                                ?>
                                                <?php
                                                if ( $key == 1 ) {
                                                    $active = '';
                                                }
                                                $tab_id = 'floor-img' . $counter;
                                                ?>
                                                <li class="<?php echo esc_html($active); ?>"><a data-toggle="tab" href="#<?php echo sanitize_title($tab_id); ?>"><?php echo esc_html($floor_plan['floor_plan_title']); ?></a></li>
                                                <?php
                                                $counter ++;
                                            endforeach;
                                            ?>
                                        </ul>
                                        <div class="tab-content">
                                            <?php $active = 'active'; ?>
                                            <?php
                                            $counter = 1;
                                            foreach ( $floor_plans as $key => $floor_plan ) :
                                                ?>
                                                <?php
                                                if ( $key == 1 ) {
                                                    $active = '';
                                                }
                                                $tab_id = 'floor-img' . $counter;
                                                $floor_plan['floor_plan_desc'] = isset($floor_plan['floor_plan_desc']) ? $floor_plan['floor_plan_desc'] : '';
                                                $floor_id = '';
                                                if ( isset($floor_plan['floor_plan_title']) && $floor_plan['floor_plan_title'] != '' ) {
                                                    $floor_id = 'id="' . sanitize_title($tab_id) . '"';
                                                }
                                                $counter ++;
                                                ?>
                                                <div <?php echo ($floor_id); ?> class="tab-pane fade in <?php echo esc_html($active); ?>">
                                                    <p><?php echo esc_html($floor_plan['floor_plan_desc']); ?></p>
                                                    <img src="<?php echo wp_get_attachment_url($floor_plan['floor_plan_image']); ?>" alt=""/>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php
                                endif;
                            }

                            $map_in_content = get_post_meta($listing_type_id, 'wp_dp_location_element', true);
                            $element_title = get_post_meta($listing_type_id, 'wp_dp_listing_type_title_nearby_places', true);
                            if ( $map_in_content == 'on' ) {
                                echo '<div class="element-title">
                                            <h3>' . $element_title . '</h3>
                                      </div>';
                                do_action('wp_dp_listing_sidebar_map_html', $post_id, 'view-5');
                            }

                            if ( $wp_dp_enable_file_attachments_element != 'off' ) {
                                do_action('wp_dp_attachments_html', $post_id);
                            }

                            if ( $wp_dp_enable_video_element != 'off' ) {
                                do_action('wp_dp_listing_video_html', $post_id);
                            }
                            do_action('wp_dp_listing_vitual_tour_html', $post_id);
                            if ( $wp_dp_enable_yelp_places_element != 'off' ) {
                                do_action('wp_dp_listing_yelp_results_html', $post_id);
                            }

                            do_action('listing_type_faq_frontend', $post_id);


                            if ( isset($wp_dp_plugin_options['wp_dp_listing_static_text_block']) && $wp_dp_plugin_options['wp_dp_listing_static_text_block'] != '' ) {

                                $environmental_text = isset($wp_dp_plugin_options['wp_dp_listing_static_envior_text']) ? $wp_dp_plugin_options['wp_dp_listing_static_envior_text'] : '';
                                ?>
                                <div id="listing-static-envior-text" class="listing_static_envior_text">
                                    <div class="element-title">
                                        <h3><?php echo esc_html($environmental_text) ?></h3>
                                    </div>
                                    <div class="listing-static-text">
                                        <?php echo htmlspecialchars_decode($wp_dp_plugin_options['wp_dp_listing_static_text_block']); ?>
                                    </div>
                                </div>
                                <?php
                            }

                            do_action('wp_dp_author_info_html', $post_id, 'view-1');
                            ?>
                            <?php do_action('wp_dp_reviews_ui', $post_id) ?>
                        </div>
                        <?php
                        $sidebar_mortgage_calculator = wp_dp_element_hide_show($post_id, 'sidebar_mortgage_calculator');
                        $pre_mort_calc_class = 'no-mortgage-calc';
                        if ( $sidebar_mortgage_calculator == 'on' ) {
                            $pre_mort_calc_class = 'has-mortgage-calc';
                        }
                        ?>
                        <div class="sidebar sticky-sidebar <?php echo sanitize_html_class($pre_mort_calc_class) ?> col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <?php do_action('wp_dp_sidebar_gallery_html', $post_id, 'detail_view5'); ?>
                            <?php do_action('wp_dp_sidebar_map_html', $post_id, 'detail_view5'); ?>
                            <?php do_action('wp_dp_enquiry_agent_contact_form_html', $post_id, 'detail_view5'); ?>
                            <?php do_action('wp_dp_sidebar_member_info_html', $post_id, 'detail_view5'); ?>
                            <?php do_action('wp_dp_payment_calculator_html', $post_id, 'detail_view5'); ?>
                            <?php do_action('wp_dp_listing_opening_hours_element_html', $post_id, 'detail_view5'); ?>
                            <?php do_action('wp_dp_off_days_element_html', $post_id, 'detail_view5'); ?>
                            <?php
                            if ( isset($wp_dp_listing_sidebar_switch) && $wp_dp_listing_sidebar_switch == 'on' ) {
                                if ( is_active_sidebar($wp_dp_listing_detail_select_sidebar) ) {
                                    dynamic_sidebar($wp_dp_listing_detail_select_sidebar);
                                }
                            }
                            ?>
                        </div>
                        <?php
                    } else {
                        ?> 
                        <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="member-inactive">
                                <i class="icon-warning"></i>
                                <span><?php echo wp_dp_plugin_text_srt('wp_dp_user_profile_not_active'); ?></span>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php do_action('wp_dp_nearby_listings_element_html', $post_id); ?>
</div>