<?php
/*
 * listinf featured slider
 */

global $wp_dp_post_listing_types, $wp_dp_plugin_options;
$user_id = $user_company = '';
if ( is_user_logged_in() ) {
    $user_id = get_current_user_id();
    $user_company = get_user_meta($user_id, 'wp_dp_company', true);
}

$wp_dp_custom_title_length = isset($wp_dp_plugin_options['wp_dp_custom_title_length']) ? $wp_dp_plugin_options['wp_dp_custom_title_length'] : 5;
$wp_dp_custom_content_length = isset($wp_dp_plugin_options['wp_dp_custom_content_length']) ? $wp_dp_plugin_options['wp_dp_custom_content_length'] : 20;



$default_listing_no_custom_fields = isset($wp_dp_plugin_options['wp_dp_listing_no_custom_fields']) ? $wp_dp_plugin_options['wp_dp_listing_no_custom_fields'] : '';
$compare_listing_switch = isset($atts['compare_listing_switch']) ? $atts['compare_listing_switch'] : 'no';
if ( false === ( $listing_view = wp_dp_get_transient_obj('wp_dp_listing_view' . $listing_short_counter) ) ) {
    $listing_view = isset($atts['listing_view']) ? $atts['listing_view'] : '';
}
$listing_no_custom_fields = isset($atts['listing_no_custom_fields']) ? $atts['listing_no_custom_fields'] : $default_listing_no_custom_fields;
if ( $listing_no_custom_fields == '' || ! is_numeric($listing_no_custom_fields) ) {
    $listing_no_custom_fields = 3;
}
$listing_enquiry_switch = isset($atts['listing_enquiry_switch']) ? $atts['listing_enquiry_switch'] : 'no';
$listing_hide_switch = isset($atts['listing_hide_switch']) ? $atts['listing_hide_switch'] : 'no';
$listing_notes_switch = isset($atts['listing_notes_switch']) ? $atts['listing_notes_switch'] : 'no';
wp_enqueue_script('wp-dp-prettyPhoto');
wp_enqueue_style('wp-dp-prettyPhoto');
$wp_dp_cs_inline_script = '
        jQuery(document).ready(function () {
             jQuery("a.listing-video-btn[data-rel^=\'prettyPhoto\']").prettyPhoto({animation_speed:"fast",slideshow:10000, hideflash: true,autoplay:true,autoplay_slideshow:false});
        });';
wp_dp_cs_inline_enqueue_script($wp_dp_cs_inline_script, 'wp-dp-custom-inline');


$search_box = isset($atts['search_box']) ? $atts['search_box'] : '';
$main_class = 'listing-medium';
$wp_dp_listings_title_limit = isset($atts['featured_listings_title_limit']) ? $atts['featured_listings_title_limit'] : '20';
// start ads script
$listing_ads_switch = isset($atts['listing_ads_switch']) ? $atts['listing_ads_switch'] : 'no';
if ( $listing_ads_switch == 'yes' ) {
    $listing_ads_after_list_series = isset($atts['listing_ads_after_list_count']) ? $atts['listing_ads_after_list_count'] : '5';
    if ( $listing_ads_after_list_series != '' ) {
        $listing_ads_list_array = explode(",", $listing_ads_after_list_series);
    }
    $listing_ads_after_list_array_count = sizeof($listing_ads_list_array);
    $listing_ads_after_list_flag = 0;
    $i = 0;
    $array_i = 0;
    $listing_ads_after_list_array_final = '';
    while ( $listing_ads_after_list_array_count > $array_i ) {
        if ( isset($listing_ads_list_array[$array_i]) && $listing_ads_list_array[$array_i] != '' ) {
            $listing_ads_after_list_array[$i] = $listing_ads_list_array[$array_i];
            $i ++;
        }
        $array_i ++;
    }
    // new count 
    $listing_ads_after_list_array_count = sizeof($listing_ads_after_list_array);
}
$listings_ads_array = array();
if ( $listing_ads_switch == 'yes' && $listing_ads_after_list_array_count > 0 ) {
    $list_count = 0;
    for ( $i = 0; $i <= $listing_loop_obj->found_posts; $i ++ ) {
        if ( $list_count == $listing_ads_after_list_array[$listing_ads_after_list_flag] ) {
            $list_count = 1;
            $listings_ads_array[] = $i;
            $listing_ads_after_list_flag ++;
            if ( $listing_ads_after_list_flag >= $listing_ads_after_list_array_count ) {
                $listing_ads_after_list_flag = $listing_ads_after_list_array_count - 1;
            }
        } else {
            $list_count ++;
        }
    }
}
$listing_page = isset($_REQUEST['listing_page']) && $_REQUEST['listing_page'] != '' ? $_REQUEST['listing_page'] : 1;
$posts_per_page = isset($atts['posts_per_page']) ? $atts['posts_per_page'] : '';
$counter = 1;
if ( $listing_page >= 2 ) {
    $counter = ( ($listing_page - 1) * $posts_per_page ) + 1;
}
// end ads script
$main_class = 'listing-grid default';
$listing_location_options = isset($atts['listing_location']) ? $atts['listing_location'] : '';
if ( $listing_location_options != '' ) {
    $listing_location_options = explode(',', $listing_location_options);
}
$http_request = wp_dp_server_protocol();


if ( $listing_loop_obj->have_posts() ) {
    $flag_number = 1;
    $element_listing_top_category = 'no';
    if ( $element_listing_top_category == 'yes' ) {
        while ( $listing_loop_obj->have_posts() ) : $listing_loop_obj->the_post();
            global $post, $wp_dp_member_profile;
            $listing_id = $post;
            $pro_is_compare = apply_filters('wp_dp_is_compare', $listing_id, $compare_listing_switch);
            $Wp_dp_Locations = new Wp_dp_Locations();
            $listing_random_id = rand(1111111, 9999999);
            $get_listing_location = $Wp_dp_Locations->get_element_listing_location($listing_id, $listing_location_options);
            $wp_dp_listing_username = get_post_meta($listing_id, 'wp_dp_listing_username', true);
            $wp_dp_listing_member = get_post_meta($listing_id, 'wp_dp_listing_member', true);
            $wp_dp_profile_image = $wp_dp_member_profile->member_get_profile_image($wp_dp_listing_username);
            $wp_dp_listing_price_options = get_post_meta($listing_id, 'wp_dp_listing_price_options', true);
            $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
            $wp_dp_listing_posted = get_post_meta($listing_id, 'wp_dp_listing_posted', true);
            $wp_dp_listing_posted = wp_dp_time_elapsed_string($wp_dp_listing_posted);
            $number_of_gallery_items = get_post_meta($listing_id, 'wp_dp_detail_page_gallery_ids', true);
            $wp_dp_listing_gallery_ids = get_post_meta($listing_id, 'wp_dp_detail_page_gallery_ids', true);
            $gallery_image_count = count($wp_dp_listing_gallery_ids);
            $gallery_pics_allowed = get_post_meta($listing_id, 'wp_dp_transaction_listing_pic_num', true);
            $count_all = ( isset($number_of_gallery_items) && is_array($number_of_gallery_items) && sizeof($number_of_gallery_items) > 0 ) ? count($number_of_gallery_items) : 0;
            if ( $count_all > $gallery_pics_allowed ) {
                $count_all = $gallery_pics_allowed;
            }



            $listings_excerpt_length = $wp_dp_custom_content_length;

            $wp_dp_listing_is_urgent = wp_dp_check_promotion_status($listing_id, 'urgent');
            // get listing type name by slug
            $listing_type_name = '';
            if ( isset($wp_dp_listing_type) && ! empty($wp_dp_listing_type) ) {
                $post_type_data = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type');
                $post_type_id = $post_type_data->ID;
                $post_type_id = wp_dp_wpml_lang_page_id($post_type_id, 'listing-type');
                $listing_type_name = get_the_title($post_type_id);
            }



            // checking review in on in listing type
            $wp_dp_listing_type = isset($wp_dp_listing_type) ? $wp_dp_listing_type : '';
            if ( $listing_type_post = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type') )
                $listing_type_id = $listing_type_post->ID;
            $listing_type_id = isset($listing_type_id) ? $listing_type_id : '';
            $listing_type_id = wp_dp_wpml_lang_page_id($listing_type_id, 'listing-type');
            $wp_dp_user_reviews = get_post_meta($listing_type_id, 'wp_dp_user_reviews', true);

            $wp_dp_listing_type_price_switch = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);

            // end checking review on in listing type


            /*
             * Video and gallery from type 
             */
            $wp_dp_video_element = get_post_meta($listing_type_id, 'wp_dp_video_element', true);
            $wp_dp_image_gallery_element = get_post_meta($listing_type_id, 'wp_dp_image_gallery_element', true);
            $wp_dp_video_element = isset($wp_dp_video_element) ? $wp_dp_video_element : '';
            $wp_dp_image_gallery_element = isset($wp_dp_image_gallery_element) ? $wp_dp_image_gallery_element : '';
            /*
             * End Video and gallery 
             */

            $wp_dp_listing_price = '';
            if ( $wp_dp_listing_price_options == 'price' ) {
                $wp_dp_listing_price = get_post_meta($listing_id, 'wp_dp_listing_price', true);
            } else if ( $wp_dp_listing_price_options == 'on-call' ) {
                $wp_dp_listing_price = wp_dp_plugin_text_srt('wp_dp_listings_price_on_request');
            }
            $wp_dp_price_type = get_post_meta($listing_id, 'wp_dp_price_type', true);
            // get all categories
            $wp_dp_cate = '';

            $wp_dp_post_loc_address_listing = get_post_meta($listing_id, 'wp_dp_post_loc_address_listing', true);

            $wp_dp_cate_str = '';
            $wp_dp_listing_category = get_post_meta($listing_id, 'wp_dp_listing_category', true);
            if ( ! empty($wp_dp_listing_category) && is_array($wp_dp_listing_category) ) {
                $comma_flag = 0;
                foreach ( $wp_dp_listing_category as $cate_slug => $cat_val ) {
                    $wp_dp_cate = get_term_by('slug', $cat_val, 'listing-category');
                    if ( ! empty($wp_dp_cate) ) {
                        $cate_link = wp_dp_listing_category_link($listing_type_id, $cat_val);
                        if ( $comma_flag != 0 ) {
                            $wp_dp_cate_str .= ', ';
                        }
                        $term_icon = get_term_meta($wp_dp_cate->term_id, 'wp_dp_listing_taxonomy_icon', true);
                        $term_icon_group = get_term_meta($wp_dp_cate->term_id, 'wp_dp_listing_taxonomy_icon_group', true);
                        $term__icon = '';
                        if ( $term_icon != '' ) {
                            wp_enqueue_style('cs_icons_data_css_' . $term_icon_group);
                            $term__icon = '<i class="' . $term_icon . '"></i> ';
                        }
                        $wp_dp_cate_str .= $term__icon . '<a href="' . $cate_link . '">' . $wp_dp_cate->name . '</a>';
                        $comma_flag ++;
                    }
                }
            }
            $grid_class = 'col-lg-4';
            if ( $slider_flag ) {
                $grid_class = 'swiper-slide';
            }
            ?>
            <div class="<?php echo ($grid_class); ?>" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Product">
                <div class="listing-grid advance-grid">
                    <div class="img-holder">
                        <figure>
                            <a href="<?php the_permalink(); ?>">
                                <?php
                                if ( function_exists('listing_gallery_first_image') ) {
                                    $gallery_image_args = array(
                                        'listing_id' => $listing_id,
                                        'size' => 'wp_dp_media_10',
                                        'class' => 'img-grid',
                                        'default_image_src' => esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg'),
                                        'img_extra_atr' => 'itemprop="image"',
                                    );
                                    $listing_gallery_first_image = listing_gallery_first_image($gallery_image_args);
                                    echo wp_dp_cs_allow_special_char($listing_gallery_first_image);
                                }
                                ?>
                            </a>
                            <figcaption>
                                <?php
                                wp_dp_listing_sold_html($listing_id);

                                if ( $wp_dp_listing_is_urgent == 'on' ) {
                                    ?>
                                    <span class="featured"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_urgent'); ?></span>
                                    <?php
                                }
                                ?>
                                <div class="caption-inner">
                                    <ul class="dp-listing-options">

                                        <?php
                                        $figcaption_div = true;
                                        $book_mark_args = array(
                                            'before_html' => '<li class="listing-like-opt"><div class="option-holder">',
                                            'after_html' => '</div></li>',
                                            'before_label' => '',
                                            'after_label' => '',
                                            'before_icon' => 'icon-heart-o',
                                            'after_icon' => 'icon-heart5',
                                            'show_tooltip' => 'no',
                                        );
                                        do_action('wp_dp_listing_favourite_button_frontend', $listing_id, $book_mark_args, $figcaption_div);
                                        ?> 
                                        <li class="listing-view-opt">
                                            <div class="quick-view">
                                                <a data-listings_excerpt_length="<?php echo absint($listings_excerpt_length) ?>" data-rand="<?php echo absint($listing_random_id) ?>" data-id="<?php echo absint($listing_id) ?>" class="wp-dp-quick-view-dev" data-toggle="modal" data-target="#quick-listing" href="javascript:void(0);">
                                                    <i class="icon-full-screen"></i>
                                                </a>
                                            </div>
                                        </li>
                                    </ul> 
                                </div>
                                <?php
                                // check today status
                                $today_status = false;
                                $today_status = apply_filters('wp_dp_today_status_element_html', $listing_id);
                                if( $today_status != 'opening_hours_off'){
                                if ( $today_status == true ) {
                                    ?>
                                    <span class="btn-open"><?php echo wp_dp_plugin_text_srt('wp_dp_member_open_now'); ?></span>
                                <?php } else {
                                    ?>
                                    <span class="btn-close"><?php echo wp_dp_plugin_text_srt('wp_dp_member_close_now'); ?></span>
                                <?php }}  ?>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="text-holder">
                        <?php
                        $member_image_id = get_post_meta($wp_dp_listing_member, 'wp_dp_profile_image', true);
                        $member_image = wp_get_attachment_image_src($member_image_id, 'thumbnail');
                        if ( $member_image == '' || FALSE == get_post_status($wp_dp_listing_member) ) {
                            $member_image[0] = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/member-no-image.jpg');
                        }
                        if ( $member_image != '' && get_post_status($wp_dp_listing_member) ) {
                            ?>
                            <div class="thumb-img">
                                <figure>
                                    <a href="<?php echo get_the_permalink($wp_dp_listing_member); ?>">
                                        <img src="<?php echo esc_url($member_image[0]); ?>" alt="" >
                                    </a>
                                </figure>
                            </div>
                            <?php
                        }

                        if ( get_the_title($listing_id) != '' ) {
                            ?>
                            <div class="post-title">
                                <h4 itemprop="name"> 
                                    <a href="<?php echo esc_url(get_permalink($listing_id)); ?>"><?php echo (wp_dp_limit_text(get_the_title($listing_id), $wp_dp_custom_title_length)); ?><i class="icon-verified-circle"></i></a>
                                </h4>
                            </div>
                            <?php
                        }

                        if ( $wp_dp_listing_type_price_switch == 'on' && $wp_dp_listing_price != '' ) {
                            ?>
                            <span class="listing-price" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Offer">
                                <?php
                                if ( $wp_dp_listing_price_options == 'on-call' ) {
                                    echo force_balance_tags($wp_dp_listing_price);
                                } else {
                                    $listing_info_price = wp_dp_listing_price($listing_id, $wp_dp_listing_price, '<span class="price from-price" content="' . $wp_dp_listing_price . '" itemprop="price">', '<em>' . wp_dp_plugin_text_srt('wp_dp_search_fields_date_from') . '</em></span>');
                                    $wp_dp_get_currency_sign = wp_dp_get_currency_sign('code');
                                    echo '<span itemprop="priceCurrency" style="display:none;" content="' . $wp_dp_get_currency_sign . '"></span>';
                                    echo force_balance_tags($listing_info_price);
                                }
                                ?>
                            </span>
                            <?php
                        }
                        ?>
                        <?php if ( isset($get_listing_location) && ! empty($get_listing_location) ) { ?>
                            <div class="grid-location"><span><?php echo esc_html(implode(', ', $get_listing_location)); ?></span></div>
                            <?php
                        } $list_content = get_post_field('post_content', $listing_id);

                        if ( isset($list_content) && ! empty($list_content) ) {
                            ?>
                            <p><?php
                                echo wp_dp_limit_text($list_content, $listings_excerpt_length);
                                ?></p>
                        <?php } ?>
                        <div class="grid-rating">
                            <?php
                            if ( $wp_dp_cate_str != '' ) {
                                ?>
                                <ul class="post-category"><li><?php echo wp_dp_cs_allow_special_char($wp_dp_cate_str); ?></li></ul>    
                                <?php
                            }
                            ?>
                            <div class="rating-star">
                                <?php do_action('wp_rem_reviews_listing_ui', $listing_id, 'rat-num'); ?> 
                            </div>

                        </div> 

                    </div>
                </div>
            </div>
            <?php if ( sizeof($listings_ads_array) > 0 && in_array($counter, $listings_ads_array) ) { ?>
                <div class="swiper-slide col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php do_action('wp_dp_random_ads', 'listing_banner'); ?>
                </div>
                <?php
            }
            $counter ++;
            $flag_number ++;
        endwhile;
    }
    if ( sizeof($listings_ads_array) > 0 && in_array(0, $listings_ads_array) && ($listing_page == 1 || $listing_page == '') ) {
        ?>
        <div class="swiper-slide col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php do_action('wp_dp_random_ads', 'listing_banner'); ?>
        </div>
        <?php
    }
    $flag_number = 1;
    while ( $listing_loop_obj->have_posts() ) : $listing_loop_obj->the_post();
        global $post, $wp_dp_member_profile;
        $listing_id = $post;
        $pro_is_compare = apply_filters('wp_dp_is_compare', $listing_id, $compare_listing_switch);
        $Wp_dp_Locations = new Wp_dp_Locations();
        $listing_random_id = rand(1111111, 9999999);
        $get_listing_location = $Wp_dp_Locations->get_element_listing_location($listing_id, $listing_location_options);
        $wp_dp_listing_username = get_post_meta($listing_id, 'wp_dp_listing_username', true);
        $wp_dp_listing_member = get_post_meta($listing_id, 'wp_dp_listing_member', true);
        $wp_dp_profile_image = $wp_dp_member_profile->member_get_profile_image($wp_dp_listing_username);
        $wp_dp_listing_price_options = get_post_meta($listing_id, 'wp_dp_listing_price_options', true);
        $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
        $wp_dp_listing_posted = get_post_meta($listing_id, 'wp_dp_listing_posted', true);
        $wp_dp_listing_posted = wp_dp_time_elapsed_string($wp_dp_listing_posted);
        $number_of_gallery_items = get_post_meta($listing_id, 'wp_dp_detail_page_gallery_ids', true);
        $wp_dp_listing_gallery_ids = get_post_meta($listing_id, 'wp_dp_detail_page_gallery_ids', true);
        $gallery_image_count = (isset($wp_dp_listing_gallery_ids) && is_array($wp_dp_listing_gallery_ids) && count($wp_dp_listing_gallery_ids) > 0) ? count($wp_dp_listing_gallery_ids) : 0;
        $gallery_pics_allowed = get_post_meta($listing_id, 'wp_dp_transaction_listing_pic_num', true);
        $count_all = ( isset($number_of_gallery_items) && is_array($number_of_gallery_items) && sizeof($number_of_gallery_items) > 0 ) ? count($number_of_gallery_items) : 0;
        if ( $count_all > $gallery_pics_allowed ) {
            $count_all = $gallery_pics_allowed;
        }

        $listings_excerpt_length = $wp_dp_custom_content_length;

        $wp_dp_listing_is_urgent = wp_dp_check_promotion_status($listing_id, 'urgent');
        // get listing type name by slug
        $listing_type_name = '';
        if ( isset($wp_dp_listing_type) && ! empty($wp_dp_listing_type) ) {
            $post_type_data = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type');

            $post_type_id = isset($post_type_data->ID) ? $post_type_data->ID : '';
            if ( $post_type_id != '' ) {
                $post_type_id = wp_dp_wpml_lang_page_id($post_type_id, 'listing-type');
                $listing_type_name = get_the_title($post_type_id);
            }
        }



        // checking review in on in listing type
        $wp_dp_listing_type = isset($wp_dp_listing_type) ? $wp_dp_listing_type : '';
        if ( $listing_type_post = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type') )
            $listing_type_id = $listing_type_post->ID;
        $listing_type_id = isset($listing_type_id) ? $listing_type_id : '';
        $listing_type_id = wp_dp_wpml_lang_page_id($listing_type_id, 'listing-type');
        $wp_dp_user_reviews = get_post_meta($listing_type_id, 'wp_dp_user_reviews', true);

        $wp_dp_listing_type_price_switch = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);

        // end checking review on in listing type


        /*
         * Video and gallery from type 
         */
        $wp_dp_video_element = get_post_meta($listing_type_id, 'wp_dp_video_element', true);
        $wp_dp_image_gallery_element = get_post_meta($listing_type_id, 'wp_dp_image_gallery_element', true);
        $wp_dp_video_element = isset($wp_dp_video_element) ? $wp_dp_video_element : '';
        $wp_dp_image_gallery_element = isset($wp_dp_image_gallery_element) ? $wp_dp_image_gallery_element : '';
        /*
         * End Video and gallery 
         */

        $wp_dp_listing_price = '';
        if ( $wp_dp_listing_price_options == 'price' ) {
            $wp_dp_listing_price = get_post_meta($listing_id, 'wp_dp_listing_price', true);
        } else if ( $wp_dp_listing_price_options == 'on-call' ) {
            $wp_dp_listing_price = wp_dp_plugin_text_srt('wp_dp_listings_price_on_request');
        }
        $wp_dp_price_type = get_post_meta($listing_id, 'wp_dp_price_type', true);
        // get all categories
        $wp_dp_cate = '';

        $wp_dp_post_loc_address_listing = get_post_meta($listing_id, 'wp_dp_post_loc_address_listing', true);

        $wp_dp_cate_str = '';
        $wp_dp_listing_category = get_post_meta($listing_id, 'wp_dp_listing_category', true);
        if ( ! empty($wp_dp_listing_category) && is_array($wp_dp_listing_category) ) {
            $comma_flag = 0;
            foreach ( $wp_dp_listing_category as $cate_slug => $cat_val ) {
                $wp_dp_cate = get_term_by('slug', $cat_val, 'listing-category');
                if ( ! empty($wp_dp_cate) ) {
                    $cate_link = wp_dp_listing_category_link($listing_type_id, $cat_val);
                    if ( $comma_flag != 0 ) {
                        $wp_dp_cate_str .= ', ';
                    }
                    $term_icon = get_term_meta($wp_dp_cate->term_id, 'wp_dp_listing_taxonomy_icon', true);
                    $term_icon_group = get_term_meta($wp_dp_cate->term_id, 'wp_dp_listing_taxonomy_icon_group', true);
                    $term__icon = '';
                    if ( $term_icon != '' ) {
                        wp_enqueue_style('cs_icons_data_css_' . $term_icon_group);
                        $term__icon = '<i class="' . $term_icon . '"></i> ';
                    }
                    $wp_dp_cate_str .= $term__icon . '<a href="' . $cate_link . '">' . $wp_dp_cate->name . '</a>';
                    $comma_flag ++;
                }
            }
        }
        $grid_class = 'col-lg-4 col-md-4 col-sm-6 col-xs-12';
        if ( $slider_flag ) {
            $grid_class = 'swiper-slide';
        }
        ?>
        <div class="<?php echo ($grid_class); ?>" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Product">
            <div class="listing-grid advance-grid">
                <div class="img-holder">
                    <figure>
                        <a href="<?php the_permalink(); ?>">
                            <?php
                            if ( function_exists('listing_gallery_first_image') ) {
                                $gallery_image_args = array(
                                    'listing_id' => $listing_id,
                                    'size' => 'wp_dp_media_10',
                                    'class' => 'img-grid',
                                    'default_image_src' => esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg'),
                                    'img_extra_atr' => 'itemprop="image"',
                                );
                                $listing_gallery_first_image = listing_gallery_first_image($gallery_image_args);
                                echo wp_dp_cs_allow_special_char($listing_gallery_first_image);
                            }
                            ?>
                        </a>
                        
                        <figcaption>
                            <?php
                            wp_dp_listing_sold_html($listing_id);
                            if ( $wp_dp_listing_is_urgent == 'on' ) {
                                ?>
                                <span class="featured"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_urgent'); ?></span>
                                <?php
                            }
                            ?>
                            <div class="caption-inner">
                                <ul class="dp-listing-options">

                                    <?php
                                    $figcaption_div = true;
                                    $book_mark_args = array(
                                        'before_html' => '<li class="listing-like-opt"><div class="option-holder">',
                                        'after_html' => '</div></li>',
                                        'before_label' => '',
                                        'after_label' => '',
                                        'before_icon' => 'icon-heart-o',
                                        'after_icon' => 'icon-heart5',
                                        'show_tooltip' => 'no',
                                    );
                                    do_action('wp_dp_listing_favourite_button_frontend', $listing_id, $book_mark_args, $figcaption_div);
                                    ?> 
                                    <li class="listing-view-opt">
                                        <div class="quick-view">
                                            <a data-listings_excerpt_length="<?php echo absint($listings_excerpt_length) ?>" data-rand="<?php echo absint($listing_random_id) ?>" data-id="<?php echo absint($listing_id) ?>" class="wp-dp-quick-view-dev" data-toggle="modal" data-target="#quick-listing" href="javascript:void(0);">
                                                <i class="icon-full-screen"></i>
                                            </a>
                                        </div>
                                    </li>
                                </ul> 
                                <?php
                                // check today status
                                $today_status = false;
                                $today_status = apply_filters('wp_dp_today_status_element_html', $listing_id);
                                if( $today_status != 'opening_hours_off'){
                                if ( $today_status == true ) {
                                    ?>
                                    <span class="btn-open"><?php echo wp_dp_plugin_text_srt('wp_dp_member_open_now'); ?></span>
                                <?php } else {
                                    ?>
                                    <span class="btn-close"><?php echo wp_dp_plugin_text_srt('wp_dp_member_close_now'); ?></span>
                                <?php }} ?>
                            </div>

                        </figcaption>
                    </figure>
                </div>
                <div class="text-holder">
                    <?php
                    $member_image_id = get_post_meta($wp_dp_listing_member, 'wp_dp_profile_image', true);
                    $member_image = wp_get_attachment_image_src($member_image_id, 'thumbnail');
                    if ( $member_image == '' || FALSE == get_post_status($wp_dp_listing_member) ) {
                        $member_image[0] = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/member-no-image.jpg');
                    }
                    if ( $member_image != '' && get_post_status($wp_dp_listing_member) ) {
                        ?>
                        <div class="thumb-img">
                            <figure>
                                <a href="<?php echo get_the_permalink($wp_dp_listing_member); ?>">
                                    <img src="<?php echo esc_url($member_image[0]); ?>" alt="" >
                                </a>
                            </figure>
                        </div>
                        <?php
                    }

                    if ( get_the_title($listing_id) != '' ) {
                        ?>
                        <div class="post-title">
                            <h4 itemprop="name"> 
                                <a href="<?php echo esc_url(get_permalink($listing_id)); ?>"><?php echo (wp_dp_limit_text(get_the_title($listing_id), $wp_dp_custom_title_length)); ?><i class="icon-verified-circle"></i></a>
                            </h4>
                        </div>
                        <?php
                    }

                    if ( $wp_dp_listing_type_price_switch == 'on' && $wp_dp_listing_price != '' ) {
                        ?>
                        <span class="listing-price" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Offer">
                            <?php
                            if ( $wp_dp_listing_price_options == 'on-call' ) {
                                echo force_balance_tags($wp_dp_listing_price);
                            } else {

                                $listing_info_price = wp_dp_listing_price($listing_id, $wp_dp_listing_price, '<span class="price from-price" content="' . $wp_dp_listing_price . '" itemprop="price">', '<em>' . wp_dp_plugin_text_srt('wp_dp_search_fields_date_from') . '</em></span>');
                                $wp_dp_get_currency_sign = wp_dp_get_currency_sign('code');
                                echo '<span itemprop="priceCurrency" style="display:none;" content="' . $wp_dp_get_currency_sign . '"></span>';
                                echo force_balance_tags($listing_info_price);
                            }
                            ?>
                        </span>
                        <?php
                    }
                    ?>
                    <?php if ( isset($get_listing_location) && ! empty($get_listing_location) ) { ?>
                        <div class="grid-location"><span><?php echo esc_html(implode(', ', $get_listing_location)); ?></span></div>
                        <?php
                    } $list_content = get_post_field('post_content', $listing_id);

                    if ( isset($list_content) && ! empty($list_content) ) {
                        ?>
                        <p><?php
                            echo wp_dp_limit_text($list_content, $listings_excerpt_length);
                            ?></p>
                    <?php } ?>
                    <div class="grid-rating">
                        <?php
                        if ( $wp_dp_cate_str != '' ) {
                            ?>
                            <ul class="post-category"><li><?php echo wp_dp_cs_allow_special_char($wp_dp_cate_str); ?></li></ul>    
                            <?php
                        }
                        ?>
                        <div class="rating-star">
                            <?php do_action('wp_rem_reviews_listing_ui', $listing_id, 'rat-num'); ?> 
                        </div>

                    </div> 
                </div>
            </div>
        </div>
        <?php if ( sizeof($listings_ads_array) > 0 && in_array($counter, $listings_ads_array) ) { ?>
            <div class="swiper-slide col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php do_action('wp_dp_random_ads', 'listing_banner'); ?>
            </div>
            <?php
        }
        $counter ++;
        $flag_number ++;
    endwhile;
    ?>

    <?php
} else {
    echo '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-listing-match-error"><h6><i class="icon-warning"></i><strong> ' . wp_dp_plugin_text_srt('wp_dp_listing_slider_sorry') . '</strong>&nbsp; ' . wp_dp_plugin_text_srt('wp_dp_listing_slider_doesn_match') . ' </h6></div></div>';
}
?>