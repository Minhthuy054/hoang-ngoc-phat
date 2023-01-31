<?php
/**
 * Listing search box
 *
 */
?>
<!--Element Section Start-->
<!--Wp-dp Element Start-->
<?php
global $wp_dp_post_listing_types, $wp_dp_plugin_options;
$user_id = $user_company = '';
if ( is_user_logged_in() ) {
    $user_id = get_current_user_id();
    $user_company = get_user_meta($user_id, 'wp_dp_company', true);
}
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
$wp_dp_listings_title_limit = isset($atts['listings_title_limit']) ? $atts['listings_title_limit'] : '20';
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
$columns_class = 'col-lg-4 col-md-4 col-sm-6 col-xs-12';
$main_class = 'listing-grid modern v1';
$listing_location_options = isset($atts['listing_location']) ? $atts['listing_location'] : '';
if ( $listing_location_options != '' ) {
    $listing_location_options = explode(',', $listing_location_options);
}

//    echo '<pre>';
//    print_r($listing_loop_obj);
//    echo '</pre>';


$http_request = wp_dp_server_protocol();
if ( $listing_loop_obj->have_posts() ) {
    $flag = 1;
    ?>
    <div class="row">
        <?php if ( sizeof($listings_ads_array) > 0 && in_array(0, $listings_ads_array) && ($listing_page == 1 || $listing_page == '') ) { ?>
            <div class="portfolio grid-fading animated col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php do_action('wp_dp_random_ads', 'listing_banner'); ?>
            </div>
            <?php
        }
        while ( $listing_loop_obj->have_posts() ) : $listing_loop_obj->the_post();
            global $post, $wp_dp_member_profile;
            $listing_id = $post;
            $pro_is_compare = apply_filters('wp_dp_is_compare', $listing_id, $compare_listing_switch);
            $listing_random_id = rand(1111111, 9999999);
            $Wp_dp_Locations = new Wp_dp_Locations();

            $get_listing_location = $Wp_dp_Locations->get_element_listing_location($listing_id, $listing_location_options);
            $wp_dp_listing_username = get_post_meta($listing_id, 'wp_dp_listing_username', true);
            $wp_dp_listing_member = get_post_meta($listing_id, 'wp_dp_listing_member', true);
            $wp_dp_listing_is_urgent = wp_dp_check_promotion_status($listing_id, 'urgent');
            $wp_dp_listing_is_top_cat = wp_dp_check_promotion_status($listing_id, 'top-categories');

            $wp_dp_profile_image = $wp_dp_member_profile->member_get_profile_image($wp_dp_listing_username);
            $wp_dp_listing_price_options = get_post_meta($listing_id, 'wp_dp_listing_price_options', true);
            $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
            $wp_dp_listing_posted = get_post_meta($listing_id, 'wp_dp_listing_posted', true);
            $wp_dp_listing_posted = wp_dp_time_elapsed_string($wp_dp_listing_posted);
            $number_of_gallery_items = get_post_meta($listing_id, 'wp_dp_detail_page_gallery_ids', true);

            $gallery_pics_allowed = get_post_meta($listing_id, 'wp_dp_transaction_listing_pic_num', true);
            $count_all = ( isset($number_of_gallery_items) && is_array($number_of_gallery_items) && sizeof($number_of_gallery_items) > 0 ) ? count($number_of_gallery_items) : 0;
            if ( $count_all > $gallery_pics_allowed ) {
                $count_all = $gallery_pics_allowed;
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
            // get all categories
            $wp_dp_cate = '';
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

            $featured = $urgent = '';
            if ( $wp_dp_listing_is_urgent == 'on' ) {
                $featured = 'featured';
            } if ( $wp_dp_listing_is_top_cat == 'on' ) {
                $urgent = 'urgent';
            }

            $user_id = '';
            if ( is_user_logged_in() ) {
                $user_id = get_current_user_id();
            }
            ?>



            <div class="portfolio grid-fading animated <?php echo esc_html($columns_class); ?>">
                <div class="<?php echo esc_html($main_class); ?><?php echo esc_html($pro_is_compare); ?>" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Product">
                    <div class="img-holder">
                        <figure>
                            <a href="<?php the_permalink(); ?>">
                                <?php
                                if ( function_exists('listing_gallery_first_image') ) {
                                    
                                    $size = 'wp_dp_media_10';
                                    $gallery_image_args = array(
                                        'listing_id' => $listing_id,
                                        'size' => $size,
                                        'class' => 'img-grid',
                                        'default_image_src' => esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image9x6.jpg'),
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
                                <?php } ?>
                                <div class="caption-inner">
                                    <?php
                                    $listing_video_url = get_post_meta($listing_id, 'wp_dp_listing_video', true);
                                    $listing_video_url = isset($listing_video_url) ? $listing_video_url : '';
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
                                    <?php } } ?>


                                    <ul class="dp-listing-options">
                                        <?php
                                        if ( isset($listing_notes_switch) && $listing_notes_switch == 'yes' ) {
                                            // Listing Notes Button
                                            $prop_notes_args = array(
                                                'listing_notes_switch' => $listing_notes_switch,
                                                'before_html' => '<li class="listing-note-opt"><div class="option-holder">',
                                                'after_html' => '</div></li>',
                                                'before_label' => wp_dp_plugin_text_srt('wp_dp_listing_notes'),
                                                'after_label' => wp_dp_plugin_text_srt('wp_dp_listing_notes_added'),
                                                'before_icon' => 'icon-book',
                                                'after_icon' => 'icon-book2',
                                                'notes_rand_id' => $listing_random_id,
                                            );
                                            // do_action('wp_dp_notes_frontend_button', $listing_id, $prop_notes_args);
                                        }
                                        //
                                        ?>
                                        <?php do_action('wp_dp_listing_compare', $listing_id, $compare_listing_switch, 'no', '<li class="listing-compare-opt"><div class="option-holder">', '</div></li>'); ?>
                                        <?php if ( $listing_video_url != '' && $wp_dp_video_element == 'on' ) { ?>
                                            <?php $listing_video_url = str_replace("player.vimeo.com/video", "vimeo.com", $listing_video_url); ?>
                                            <li class="listing-video-opt">
                                                <div class="option-holder">
                                                    <a class="listing-video-btn" data-rel="prettyPhoto" href="<?php echo esc_url($listing_video_url); ?>">
                                                        <i class="icon-film3"></i>
                                                        <div class="option-content"><span><?php echo wp_dp_plugin_text_srt('wp_dp_subnav_item_3'); ?></span></div>
                                                    </a>
                                                </div>
                                            </li>
                                        <?php } ?>
                                        <?php if ( $count_all > 0 && $wp_dp_image_gallery_element == 'on' ) { ?>
                                            <li class="listing-photo-opt">
                                                <div id="galley-img<?php echo absint($listing_random_id) ?>" class="option-holder">
                                                    <a href="javascript:void(0)" class="dp-pretty-photos" data-id="<?php echo absint($listing_id) ?>" data-rand="<?php echo absint($listing_random_id) ?>">
                                                        <i class="icon-camera6"></i>
                                                        <div class="option-content">
                                                            <span><?php echo wp_dp_plugin_text_srt('wp_dp_element_tooltip_icon_camera'); ?></span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </li>
                                        <?php } ?>
                                    </ul>

                                    <?php
                                    $favourite_label = '';
                                    $favourite_label = '';
                                    $figcaption_div = true;
                                    $book_mark_args = array(
                                        'before_label' => $favourite_label,
                                        'after_label' => $favourite_label,
                                        'before_icon' => '<i class="icon-heart-o"></i>',
                                        'after_icon' => '<i class="icon-heart5"></i>',
                                    );
                                    do_action('wp_dp_favourites_frontend_button', $listing_id, $book_mark_args, $figcaption_div);
                                    ?>
                                </div>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="text-holder">
                        <?php if ( ! empty($get_listing_location) ) { ?>
                            <ul class="listing-location">
                                <li><i class="icon-location-pin2"></i><span><?php echo esc_html(implode(' / ', $get_listing_location)); ?></span></li>
                            </ul>
                          <?php } ?>
                            <div class="price-holder">
                                <?php if ( $wp_dp_listing_type_price_switch == 'on' && $wp_dp_listing_price != '' ) { ?>
                                    <span class="listing-price<?php echo wp_dp_allow_special_char($has_thumb_class); ?>" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Offer">
                                        <?php
                                        if ( $wp_dp_listing_price_options == 'on-call' ) {
                                            echo '<em>' . force_balance_tags($wp_dp_listing_price) . '</em>';
                                        } else {
                                            echo '<small>' . wp_dp_plugin_text_srt('wp_dp_listing_price_start_from') . '</small>';
                                            $listing_info_price = wp_dp_listing_price($listing_id, $wp_dp_listing_price, '<span class="price" content="' . $wp_dp_listing_price . '" itemprop="price">', '</span>', '<span class="special-price" content="" itemprop="price">', '</span>');
                                            $wp_dp_get_currency_sign = wp_dp_get_currency_sign('code');
                                            echo '<span itemprop="priceCurrency" style="display:none;" content="' . $wp_dp_get_currency_sign . '"></span>';
                                            echo force_balance_tags($listing_info_price);
                                        }
                                        ?>
                                    </span>
                                <?php } ?>
                                 </div>
                                <?php
                                if ( $listing_enquiry_switch == 'yes' ) {
                                    $prop_enquir_args = array(
                                        'enquiry_label' => wp_dp_plugin_text_srt('wp_dp_enquiry_detail_enquiry'),
                                    );
                                    //do_action('wp_dp_enquiry_check_frontend_button', $listing_id, $prop_enquir_args);
                                }
                                ?>

                          

                            <div class="post-title">
                                <h4 itemprop="name"><a href="<?php echo esc_url(get_permalink($listing_id)); ?>"><?php echo (wp_dp_limit_text(get_the_title($listing_id), $wp_dp_listings_title_limit)) ?></a></h4>
                            </div>
                            <?php
                            // All custom fields with value
                            $cus_fields = array( 'content' => '' );
                            $cus_fields = apply_filters('wp_dp_custom_fields', $listing_id, $cus_fields, $listing_no_custom_fields);
                            if ( isset($cus_fields['content']) && $cus_fields['content'] != '' ) {
                                ?>
                                <ul class="post-category-list" itemprop="category">
                                    <?php echo wp_dp_allow_special_char($cus_fields['content']); ?>
                                </ul>
                            <?php } ?>
                            
                       
                        <div class="post-listing-footer">
                                
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
            </div>
            <?php if ( sizeof($listings_ads_array) > 0 && in_array($counter, $listings_ads_array) ) { ?>
                <div class="portfolio grid-fading animated col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php do_action('wp_dp_random_ads', 'listing_banner'); ?>
                </div>
                <?php
            }
            $counter ++;
        endwhile;
        ?>
    </div>
    <?php
} else {
    echo '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-listing-match-error"><h6><i class="icon-warning"></i><strong> ' . wp_dp_plugin_text_srt('wp_dp_listing_slider_sorry') . '</strong>&nbsp; ' . wp_dp_plugin_text_srt('wp_dp_listing_slider_doesn_match') . ' </h6></div></div>';
}
?>