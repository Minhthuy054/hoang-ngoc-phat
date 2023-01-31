<?php
/**
 * Listings Slider
 *
 */
wp_enqueue_style('swiper');
wp_enqueue_script('swiper');
global $wp_dp_post_listing_types;

$wp_dp_all_compare_buttons = isset($wp_dp_plugin_options['wp_dp_all_compare_buttons']) ? $wp_dp_plugin_options['wp_dp_all_compare_buttons'] : '';
$listing_location_options = isset($atts['listing_location']) ? $atts['listing_location'] : '';
$listings_title = isset($atts['slider_listings_title']) ? $atts['slider_listings_title'] : '';
$listings_subtitle = isset($atts['listings_subtitle']) ? $atts['listings_subtitle'] : '';

$wp_dp_listing_slider_element_title_color = isset($atts['wp_dp_listing_slider_element_title_color']) ? $atts['wp_dp_listing_slider_element_title_color'] : '';
$wp_dp_listing_slider_element_subtitle_color = isset($atts['wp_dp_listing_slider_element_subtitle_color']) ? $atts['wp_dp_listing_slider_element_subtitle_color'] : '';
$wp_dp_listings_slider_seperator_style = isset($atts['wp_dp_listings_slider_seperator_style']) ? $atts['wp_dp_listings_slider_seperator_style'] : '';
$wp_dp_listings_title_limit = isset($atts['listings_title_limit']) ? $atts['listings_title_limit'] : '20';
$listing_no_custom_fields = isset($atts['listing_no_custom_fields']) ? $atts['listing_no_custom_fields'] : $default_listing_no_custom_fields;
if ( $listing_no_custom_fields == '' || ! is_numeric($listing_no_custom_fields) ) {
    $listing_no_custom_fields = 3;
}
$listing_enquiry_switch = isset($atts['listing_enquiry_switch']) ? $atts['listing_enquiry_switch'] : 'no';
$listing_notes_switch = isset($atts['listing_notes_switch']) ? $atts['listing_notes_switch'] : 'no';
$compare_listing_switch = isset($atts['compare_listing_switch']) ? $atts['compare_listing_switch'] : 'no';
$listings_slider_alignment = isset($atts['listings_slider_alignment']) ? $atts['listings_slider_alignment'] : '';
if ( $listing_location_options != '' ) {
    $listing_location_options = explode(',', $listing_location_options);
}
if ( $listings_title == '' ) {
    $padding_class = 'swiper-padding-top';
}
$rand_num = rand(12345, 54321);
wp_enqueue_script('wp-dp-prettyPhoto');
wp_enqueue_style('wp-dp-prettyPhoto');
$wp_dp_cs_inline_script = '
        jQuery(document).ready(function () {
             jQuery("a.listing-video-btn[data-rel^=\'prettyPhoto\']").prettyPhoto({animation_speed:"fast",slideshow:10000, hideflash: true,autoplay:true,autoplay_slideshow:false});
        });';
wp_dp_cs_inline_enqueue_script($wp_dp_cs_inline_script, 'wp-dp-custom-inline');
$flag = 1;
$http_request = wp_dp_server_protocol();
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php
        $wp_dp_element_structure = '';
        $wp_dp_element_structure .= wp_dp_plugin_title_sub_align($listings_title, $listings_subtitle, $listings_slider_alignment, $wp_dp_listing_slider_element_title_color, $wp_dp_listings_slider_seperator_style, $wp_dp_listing_slider_element_subtitle_color);
        echo force_balance_tags($wp_dp_element_structure);
        ?>
    </div>
    <?php if ( $listing_loop_obj->have_posts() ) { ?>
        <div id="listing-grid-slider-<?php echo intval($rand_num); ?>" class="listing-grid-slider v2">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php
                    while ( $listing_loop_obj->have_posts() ) : $listing_loop_obj->the_post();
                        global $post, $wp_dp_member_profile;
                        $listing_id = $post;
                        $pro_is_compare = apply_filters('wp_dp_is_compare', $listing_id, $compare_listing_switch);
                        $listing_random_id = rand(1111111, 9999999);
                        $Wp_dp_Locations = new Wp_dp_Locations();
                        $get_listing_location = $Wp_dp_Locations->get_element_listing_location($listing_id, $listing_location_options);

                        $wp_dp_listing_username = get_post_meta($listing_id, 'wp_dp_listing_username', true);
                        $wp_dp_listing_is_urgent = wp_dp_check_promotion_status($listing_id, 'urgent');
                        $wp_dp_listing_price_options = get_post_meta($listing_id, 'wp_dp_listing_price_options', true);
                        $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                        $number_of_gallery_items = get_post_meta($listing_id, 'wp_dp_detail_page_gallery_ids', true);
                        $wp_dp_listing_price = '';

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
                        // end checking review on in listing type

                        /*
                         * Video and gallery from type 
                         */
                        $wp_dp_video_element_switch = get_post_meta($listing_type_id, 'wp_dp_video_element', true);
                        $wp_dp_image_gallery_switch = get_post_meta($listing_type_id, 'wp_dp_image_gallery_element', true);
                        $wp_dp_video_element_switch = isset($wp_dp_video_element_switch) ? $wp_dp_video_element_switch : '';
                        $wp_dp_image_gallery_switch = isset($wp_dp_image_gallery_switch) ? $wp_dp_image_gallery_switch : '';
                        /*
                         * End Video and gallery 
                         */

                        $wp_dp_listing_type_price_switch = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
                        if ( $wp_dp_listing_price_options == 'price' ) {
                            $wp_dp_listing_price = get_post_meta($listing_id, 'wp_dp_listing_price', true);
                        } else if ( $wp_dp_listing_price_options == 'on-call' ) {
                            $wp_dp_listing_price = wp_dp_plugin_text_srt('wp_dp_listings_price_on_request');
                        }
                        $wp_dp_profile_image = $wp_dp_member_profile->member_get_profile_image($wp_dp_listing_username);
                        $featured = '';
                        if ( $wp_dp_listing_is_urgent == 'on' ) {
                            $featured = 'featured';
                        }
                        ?>
                        <div class="swiper-slide col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="listing-grid modern v3 <?php echo esc_html($pro_is_compare); ?>" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Product">
                                <div class="img-holder">
                                    <figure>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php
                                            if ( function_exists('listing_gallery_first_image') ) {
                                                if ( $listing_view == 'grid-medern' ) {
                                                    $size = 'wp_dp_cs_media_5';
                                                } else if ( $listing_view == 'grid-classic' ) {
                                                    $size = 'wp_dp_cs_media_5';
                                                } else {
                                                    $size = 'wp_dp_cs_media_6';
                                                }
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
                                                <span class="featured"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_featrd'); ?></span>
                                            <?php } ?>
                                            <div class="caption-inner">
                                                <?php
                                                $listing_video_url = get_post_meta($listing_id, 'wp_dp_listing_video', true);
                                                $listing_video_url = isset($listing_video_url) ? $listing_video_url : '';
                                                ?>
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
                                                        do_action('wp_dp_notes_frontend_button', $listing_id, $prop_notes_args);
                                                    }
                                                    //
                                                    ?>
                                                    <?php do_action('wp_dp_listing_compare', $listing_id, $compare_listing_switch, 'no', '<li class="listing-compare-opt"><div class="option-holder">', '</div></li>'); ?>
                                                    <?php
                                                    $figcaption_div = true;
                                                    $book_mark_args = array(
                                                        'before_html' => '<li class="listing-like-opt"><div class="option-holder">',
                                                        'after_html' => '</div></li>',
                                                        'before_label' => wp_dp_plugin_text_srt('wp_dp_listing_save_to_favourite'),
                                                        'after_label' => wp_dp_plugin_text_srt('wp_dp_listing_remove_to_favourite'),
                                                        'before_icon' => 'icon-heart-o',
                                                        'after_icon' => 'icon-heart5',
                                                        'show_tooltip' => 'no',
                                                    );
                                                    do_action('wp_dp_listing_favourite_button_frontend', $listing_id, $book_mark_args, $figcaption_div);
                                                    ?>
                                                    <?php if ( $listing_video_url != '' && $wp_dp_video_element_switch == 'on' ) { ?>
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
                                                    <?php if ( $count_all > 0 && $wp_dp_image_gallery_switch == 'on' ) { ?>
                                                        <li class="listing-photo-opt">
                                                            <div id="galley-img<?php echo absint($listing_random_id) ?>" class="option-holder">
                                                                <a href="javascript:void(0)" class="dp-pretty-photos" data-id="<?php echo absint($listing_id) ?>" data-rand="<?php echo absint($listing_random_id) ?>">
                                                                    <i class="icon-camera6"></i><span class="capture-count"><?php echo absint($count_all); ?></span>
                                                                    <div class="option-content">
                                                                        <span><?php echo wp_dp_plugin_text_srt('wp_dp_element_tooltip_icon_camera'); ?></span>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </figcaption>
                                    </figure>
                                </div>
                                <?php ?>
                                <div class="text-holder">
                                    <?php if ( ! empty($get_listing_location) ) { ?>
                                        <ul class="listing-location">
                                            <li><i class="icon-location-pin2"></i><span><?php echo esc_html(implode(' / ', $get_listing_location)); ?></span></li>
                                        </ul>
                                    <?php } ?>
                                    <div class="post-title">
                                        <h4 itemprop="name"><a href="<?php echo esc_url(get_permalink($listing_id)); ?>"><?php echo (wp_dp_limit_text(get_the_title($listing_id), $wp_dp_listings_title_limit)) ?></a></h4>
                                    </div>
                                    <?php
                                    // All custom fields with value
                                    $cus_fields = array( 'content' => '' );
                                    $cus_fields = apply_filters('wp_dp_custom_fields', $listing_id, $cus_fields, $listing_no_custom_fields, true, false);
                                    if ( isset($cus_fields['content']) && $cus_fields['content'] != '' ) {
                                        ?>
                                        <ul class="post-category-list" itemprop="category">
                                            <?php echo wp_dp_allow_special_char($cus_fields['content']); ?>
                                        </ul>
                                    <?php } ?>
  <div class="post-listing-footer">
                                    <div class="price-holder">

                                        <?php if ( $wp_dp_listing_type_price_switch == 'on' && $wp_dp_listing_price != '' ) { ?>
                                            <span class="listing-price" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Offer">
                                                <?php
                                                if ( $wp_dp_listing_price_options == 'on-call' ) {
                                                    echo force_balance_tags($wp_dp_listing_price);
                                                } else {
                                                    $listing_info_price = wp_dp_listing_price($listing_id, $wp_dp_listing_price, '<span class="price" content="' . $wp_dp_listing_price . '" itemprop="price">', '</span>', '<span class="special-price" content="" itemprop="price">', '</span>');
                                                    $wp_dp_get_currency_sign = wp_dp_get_currency_sign('code');
                                                    echo '<span itemprop="priceCurrency" style="display:none;" content="' . $wp_dp_get_currency_sign . '"></span>';
                                                    echo force_balance_tags($listing_info_price);
                                                }
                                                ?>
                                            </span>
                                            <?php
                                        }
                                        if ( $listing_enquiry_switch == 'yes' ) {
                                            $prop_enquir_args = array(
                                                'enquiry_label' => wp_dp_plugin_text_srt('wp_dp_enquiry_detail_enquiry'),
                                            );
                                            do_action('wp_dp_enquiry_check_frontend_button', $listing_id, $prop_enquir_args);
                                        }
                                        ?>
                                    </div>
                                </div>
                                </div>
                              
                            </div>
                        </div>
                        <?php
                    endwhile;
                    ?>
                </div>
            </div>
            <!-- Add Arrows -->
            <div class="swiper-button-next"><i class="icon-angle-right"></i> </div>
            <div class="swiper-button-prev"><i class="icon-angle-left"></i></div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                if (jQuery("#listing-grid-slider-<?php echo intval($rand_num); ?> .swiper-container").length != "") {
                    "use strict";
                    var mySlider= new Swiper("#listing-grid-slider-<?php echo intval($rand_num); ?> .swiper-container", {
                        nextButton: "#listing-grid-slider-<?php echo intval($rand_num); ?> .swiper-button-next",
                        prevButton: "#listing-grid-slider-<?php echo intval($rand_num); ?> .swiper-button-prev",
                        paginationClickable: !0,
                        slidesPerView: 3,
                        slidesPer: 1,
                        loop: !0,
                        onInit: function (swiper) {
                            $(".listing-grid.modern.v3 .text-holder").matchHeight();

                        },
                        breakpoints: {
                            991: {
                                slidesPerView: 3
                            },
                            600: {
                                slidesPerView: 1
                            }
                        }
                    });
                    var elementWidth = $(".wp-dp-listing-content").width();
                    if (elementWidth<992 && elementWidth>600) mySlider.params.slidesPerView = 3;
                    //if (elementWidth<600) mySlider.params.slidesPerView = 1;
                    mySlider.update();
                    $(window).trigger('resize');
                }
            });
        </script>
        <?php
    } else {
        echo '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-listing-match-error"><h6><i class="icon-warning"></i><strong> ' . wp_dp_plugin_text_srt('wp_dp_listing_slider_sorry') . '</strong>&nbsp; ' . wp_dp_plugin_text_srt('wp_dp_listing_slider_doesn_match') . ' </h6></div></div>';
    }
    ?>
</div>
