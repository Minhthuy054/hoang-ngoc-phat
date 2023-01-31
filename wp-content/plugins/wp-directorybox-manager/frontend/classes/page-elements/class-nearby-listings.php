<?php
/**
 * File Type: Nearby Listings Page Element
 */
if ( ! class_exists('wp_dp_nearby_listings_element') ) {

    class wp_dp_nearby_listings_element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_dp_nearby_listings_element_html', array( $this, 'wp_dp_nearby_listings_element_html_callback' ), 11, 1);
        }

        public function wp_dp_nearby_listings_element_html_callback($listing_id = '') {

            global $post, $wp_dp_plugin_options, $wp_dp_post_listing_types;
            wp_enqueue_script('wp-dp-prettyPhoto');
            wp_enqueue_style('wp-dp-prettyPhoto');
            wp_enqueue_style('swiper');
            wp_enqueue_script('swiper');

            wp_enqueue_script('wp-dp-split-map');
            wp_enqueue_script('flexslider');
            wp_enqueue_script('flexslider-mousewheel');
            wp_enqueue_script('wp-dp-bootstrap-slider');
            wp_enqueue_script('wp-dp-matchHeight-script');
            wp_enqueue_script('wp-dp-listing-functions');
            wp_enqueue_style('flexslider');
            wp_enqueue_script('map-infobox');



            // wp_dp_similar_listings_switch
            $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
            $wp_dp_listing_type = isset($wp_dp_listing_type) ? $wp_dp_listing_type : '';
            $listing_type_idd = '';
            if ( $listing_type_post = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type') ) {
                $listing_type_idd = $listing_type_post->ID;
            }
            $similar_listings_switch = get_post_meta($listing_type_idd, 'wp_dp_similar_listings_switch', true);
            if ( isset($similar_listings_switch) && $similar_listings_switch != 'on' ) {
                return;
            }

            $http_request = wp_dp_server_protocol();
            $wp_dp_cs_inline_script = '
                jQuery(document).ready(function () {
                     jQuery("a.listing-video-btn[data-rel^=\'prettyPhoto\']").prettyPhoto({animation_speed:"fast",slideshow:10000, hideflash: true,autoplay:true,autoplay_slideshow:false});
                });';
            wp_dp_cs_inline_enqueue_script($wp_dp_cs_inline_script, 'wp-dp-custom-inline');
            $default_listing_no_custom_fields = isset($wp_dp_plugin_options['wp_dp_listing_no_custom_fields']) ? $wp_dp_plugin_options['wp_dp_listing_no_custom_fields'] : '';

            $wp_dp_custom_title_length = isset($wp_dp_plugin_options['wp_dp_custom_title_length']) ? $wp_dp_plugin_options['wp_dp_custom_title_length'] : 5;
            $wp_dp_custom_content_length = isset($wp_dp_plugin_options['wp_dp_custom_content_length']) ? $wp_dp_plugin_options['wp_dp_custom_content_length'] : 20;



            if ( $listing_id != '' ) {

                $main_dp_tags = get_post_meta($listing_id, 'wp_dp_tags', true);
                $main_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);

                if ( $main_listing_type != '' ) {
                    $wp_dp_base_query_args = '';
                    if ( function_exists('wp_dp_base_query_args') ) {
                        $wp_dp_base_query_args = wp_dp_base_query_args();
                    }
                    if ( function_exists('wp_dp_listing_visibility_query_args') ) {
                        $wp_dp_base_query_args = wp_dp_listing_visibility_query_args($wp_dp_base_query_args);
                    }
                    $element_filter_arr = '';
                    if ( $main_listing_type != '' && $main_listing_type != 'all' ) {
                        $element_filter_arr = array();
                        $element_filter_arr[] = array(
                            'key' => 'wp_dp_listing_type',
                            'value' => $main_listing_type,
                            'compare' => '=',
                        );
                    }
                    $element_filter_tag_arr = '';
                    if ( isset($main_dp_tags) && is_array($main_dp_tags) && ! empty($main_dp_tags) ) {
                        $element_filter_tag_arr = array( 'relation' => 'OR' );
                        foreach ( $main_dp_tags as $main_dp_tag ) {
                            $element_filter_tag_arr[] = array(
                                'key' => 'wp_dp_tags',
                                'value' => $main_dp_tag,
                                'compare' => 'Like',
                            );
                        }
                    }
                    $args = array(
                        'post_type' => 'listings',
                        'posts_per_page' => 10,
                        'post__not_in' => array( $listing_id ),
                        'meta_query' => array(
                            'relation' => 'AND',
                            $wp_dp_base_query_args,
                            $element_filter_arr,
                            $element_filter_tag_arr,
                        ),
                    );
					
                    $rel_qry = new WP_Query($args);
                    if ( $rel_qry->have_posts() ) {
                        $flag = 1;
                        ?>

                        <div class="page-section detail-nearby-listings">

                            <div class="container">
                                <div class="row">

                                    <!-- start quick view popup -->
                                    <div class="modal fade quick-view-listing" id="quick-listing" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="icon-close"></i></span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="quick-view-content"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end quick view popup -->

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="element-title">
                                            <h3><?php echo wp_dp_plugin_text_srt('wp_dp_similar_listings_heading'); ?></h3>
                                        </div>
                                        <div class="listing-grid-slider directorybox-listing">
                                            <div class="swiper-container">
                                                <div class="swiper-wrapper">
                                                    <?php
                                                    $list_count = 1;
                                                    while ( $rel_qry->have_posts() ) : $rel_qry->the_post();
                                                        global $post, $wp_dp_member_profile;
                                                        $listing_id = $post->ID;
                                                        $post_id = $post->ID;
                                                        $gallery_image_count = '';
                                                        $listing_random_id = rand(1111111, 9999999);
                                                        $Wp_dp_Locations = new Wp_dp_Locations();
                                                        $listing_location = $Wp_dp_Locations->get_element_listing_location($listing_id, array( 'city', 'country' ));
                                                        $wp_dp_listing_username = get_post_meta($listing_id, 'wp_dp_listing_username', true);
                                                        $wp_dp_listing_is_urgent = wp_dp_check_promotion_status($listing_id, 'urgent');
                                                        $wp_dp_profile_image = $wp_dp_member_profile->member_get_profile_image($wp_dp_listing_username);
                                                        $wp_dp_listing_price_options = get_post_meta($listing_id, 'wp_dp_listing_price_options', true);
                                                        $wp_dp_listing_member = get_post_meta($listing_id, 'wp_dp_listing_member', true);
                                                        $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                                                        // checking review in on in listing type
                                                        $wp_dp_listing_type = isset($wp_dp_listing_type) ? $wp_dp_listing_type : '';
                                                        if ( $listing_type_post = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type') )
                                                            $listing_type_id = $listing_type_post->ID;
                                                        $listing_type_id = isset($listing_type_id) ? $listing_type_id : '';
                                                        $listing_type_id = wp_dp_wpml_lang_page_id($listing_type_id, 'listing-type');
                                                        $wp_dp_user_reviews = get_post_meta($listing_type_id, 'wp_dp_user_reviews', true);
                                                        $wp_dp_listing_type_price_switch = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
                                                        // end checking review on in listing type
                                                        $wp_dp_listing_price = '';
                                                        if ( $wp_dp_listing_price_options == 'price' ) {
                                                            $wp_dp_listing_price = get_post_meta($listing_id, 'wp_dp_listing_price', true);
                                                        } else if ( $wp_dp_listing_price_options == 'on-call' ) {
                                                            $wp_dp_listing_price = wp_dp_plugin_text_srt('wp_dp_nearby_listings_price_on_request');
                                                        }
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
                                                                    wp_enqueue_style('cs_icons_data_css_' . $term_icon_group);
                                                                    $term__icon = '';
                                                                    if ( $term_icon != '' ) {
                                                                        $term__icon = '<i class="' . $term_icon . '"></i> ';
                                                                    }
                                                                    $wp_dp_cate_str .= $term__icon . '<a href="' . $cate_link . '">' . $wp_dp_cate->name . '</a>';
                                                                    $comma_flag ++;
                                                                }
                                                            }
                                                        }
                                                        //die();
                                                        $nearby_listing_id = $post->ID;
                                                        $wp_dp_listing_nearby_price_options = get_post_meta($nearby_listing_id, 'wp_dp_listing_price_options', true);
                                                        $wp_dp_listing_nearby_price = '';
                                                        $wp_dp_listing_price = '';
                                                        if ( $wp_dp_listing_nearby_price_options == 'price' ) {
                                                            $wp_dp_listing_nearby_price = get_post_meta($nearby_listing_id, 'wp_dp_listing_price', true);
                                                        } else if ( $wp_dp_listing_nearby_price_options == 'on-call' ) {
                                                            $wp_dp_listing_nearby_price = wp_dp_plugin_text_srt('wp_dp_nearby_listings_price_on_request');
                                                        }
                                                        $wp_dp_listing_gallery_ids = get_post_meta($nearby_listing_id, 'wp_dp_detail_page_gallery_ids', true);
                                                        $wp_dp_listing_gallery_ids = is_array( $wp_dp_listing_gallery_ids )? $wp_dp_listing_gallery_ids : array();
                                                        $gallery_image_count = count($wp_dp_listing_gallery_ids);
                                                        $wp_dp_listing_type = get_post_meta($nearby_listing_id, 'wp_dp_listing_type', true);
                                                        $wp_dp_listing_type = isset($wp_dp_listing_type) ? $wp_dp_listing_type : '';
                                                        if ( $listing_type_post = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type') )
                                                            $listing_type_nearby_id = $listing_type_post->ID;
                                                        $listing_type_nearby_id = wp_dp_wpml_lang_page_id($listing_type_nearby_id, 'listing-type');
                                                        $wp_dp_listing_type_price_nearby_switch = get_post_meta($listing_type_nearby_id, 'wp_dp_listing_type_price', true);
                                                        $wp_dp_listing_is_urgent = wp_dp_check_promotion_status($nearby_listing_id, 'urgent');
                                                        $wp_dp_listing_is_top_cat = wp_dp_check_promotion_status($nearby_listing_id, 'top-categories');

                                                        /*
                                                         * Video and gallery from type 
                                                         */
                                                        $wp_dp_video_element = get_post_meta($listing_type_nearby_id, 'wp_dp_video_element', true);
                                                        $wp_dp_image_gallery_element = get_post_meta($listing_type_nearby_id, 'wp_dp_image_gallery_element', true);
                                                        $wp_dp_video_element = isset($wp_dp_video_element) ? $wp_dp_video_element : '';
                                                        $wp_dp_image_gallery_element = isset($wp_dp_image_gallery_element) ? $wp_dp_image_gallery_element : '';
                                                        /*
                                                         * End Video and gallery 
                                                         */
                                                        $listings_excerpt_length = $wp_dp_custom_content_length;
                                                        ?>
                                                        <div class="swiper-slide" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Product" >
                                                            <div class="listing-grid advance-grid">
                                                                <div class="img-holder">
                                                                    <figure>
                                                                        <a href="<?php the_permalink(); ?>">
                                                                            <?php
                                                                            if ( function_exists('listing_gallery_first_image') ) {
                                                                                $gallery_image_args = array(
                                                                                    'listing_id' => $nearby_listing_id,
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
                                                                            wp_dp_listing_sold_html($nearby_listing_id);
                                                                            
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
                                                                                    do_action('wp_dp_listing_favourite_button_frontend', $nearby_listing_id, $book_mark_args, $figcaption_div);
                                                                                    ?>
                                                                                    <li class="listing-view-opt">
                                                                                        <div class="quick-view">
                                                                                            <a data-listings_excerpt_length="<?php echo absint($listings_excerpt_length) ?>" data-rand="<?php echo absint($listing_random_id) ?>" data-id="<?php echo absint($nearby_listing_id) ?>" class="wp-dp-quick-view-dev" data-toggle="modal" data-target="#quick-listing" href="javascript:void(0);">
                                                                                                <i class="icon-full-screen"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </li>
                                                                                </ul>
                                                                                <?php
                                                                                // check today status
                                                                                $today_status = false;
                                                                                $today_status = apply_filters('wp_dp_today_status_element_html', $nearby_listing_id);
                                                                                if( $today_status != 'opening_hours_off'){
                                                                                if ( $today_status == true ) {
                                                                                    ?>
                                                                                    <span class="btn-open"><?php echo wp_dp_plugin_text_srt('wp_dp_member_open_now'); ?></span>
                                                                                <?php } else {
                                                                                    ?>
                                                                                    <span class="btn-close"><?php echo wp_dp_plugin_text_srt('wp_dp_member_close_now'); ?></span>
                                                                                <?php } } ?>
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
                                                                      if ( $wp_dp_listing_type_price_nearby_switch == 'on' && $wp_dp_listing_nearby_price_options != 'none' ) {
                                                                        ?>
                                                                        <span class="listing-price" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Offer">
                                                                            <?Php
                                                                            if ( $wp_dp_listing_nearby_price_options == 'on-call' ) {
                                                                                echo '<span class="listing-price">' . force_balance_tags($wp_dp_listing_nearby_price) . '</span>';
                                                                            } else {
                                                                                $listing_info_price = wp_dp_listing_price($nearby_listing_id, $wp_dp_listing_nearby_price, '<span class="price from-price" content="' . $wp_dp_listing_nearby_price . '" itemprop="price">', '<em>' . wp_dp_plugin_text_srt('wp_dp_search_fields_date_from') . '</em></span>');
                                                                                $wp_dp_get_currency_sign = wp_dp_get_currency_sign('code');
                                                                                echo '<span itemprop="priceCurrency" style="display:none;" content="' . $wp_dp_get_currency_sign . '"></span>';
                                                                                echo force_balance_tags($listing_info_price);
                                                                            }
                                                                            ?>
                                                                        </span>
                                                                        <?php
                                                                    }

                                                                    if ( get_the_title($nearby_listing_id) != '' ) {
                                                                        ?>
                                                                        <div class="post-title">
                                                                            <h4 itemprop="name">
                                                                                <?php if ( $wp_dp_listing_is_top_cat == 'on' ) {
                                                                                    ?>
                                                                                    <a href="javascript:void(0)" class="wp-google-add" ><?php echo wp_dp_plugin_text_srt('wp_dp_listing_top_category'); ?></a> 
                                                                                <?php } ?>

                                                                                <a href="<?php echo esc_url(get_permalink($listing_id)); ?>"><?php echo (wp_dp_limit_text(get_the_title($listing_id), $wp_dp_custom_title_length)); ?><i class="icon-verified-circle"></i></a></h4>
                                                                        </div>
                                                                        <?php
                                                                    }

                                                                  
                                                                    if ( ! empty($listing_location) ) {
                                                                        ?>
                                                                        <div class="grid-location"><span><?php echo esc_html(implode(', ', $listing_location)); ?></span></div>
                                                                        <?php
                                                                    }

                                                                    $list_content = get_post_field('post_content', $listing_id);
                                                                    if ( isset($list_content) && ! empty($list_content) ) {
                                                                        ?>
                                                                        <p><?php echo wp_dp_limit_text($list_content, $listings_excerpt_length); ?></p>
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
                                                        <?php
                                                        $list_count ++;
                                                    endwhile;
                                                    wp_reset_postdata();
                                                    ?>
                                                </div>
                                            </div>
                                            <?php if ( $list_count > 5 ) { ?>
                                                <div class="swiper-button-prev"> <i class="icon-chevron-thin-left"></i></div>
                                                <div class="swiper-button-next"><i class="icon-chevron-thin-right"></i></div>
                                                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            jQuery(document).ready(function ($) {
                                if (jQuery(".listing-grid-slider.directorybox-listing .swiper-container").length != "") {
                                    "use strict";
                                    var swiper = new Swiper(".listing-grid-slider.directorybox-listing .swiper-container", {
                                        slidesPerView: 3,
                                        slidesPerColumn: 1,
                                        loop: false,
                                        paginationClickable: true,
                                        grabCursor: false,
                                        autoplay: false,
                                        spaceBetween: 30,
                                        nextButton: ".listing-grid-slider.directorybox-listing .swiper-button-next",
                                        prevButton: ".listing-grid-slider.directorybox-listing .swiper-button-prev",
                                        breakpoints: {
                                            1024: {
                                                slidesPerView: 3,
                                                spaceBetween: 40
                                            },
                                            991: {
                                                slidesPerView: 2,
                                                spaceBetween: 30
                                            },
                                            600: {
                                                slidesPerView: 1,
                                                spaceBetween: 15
                                            }
                                        }
                                    });
                                    var elementWidth = $(".listing-grid-slider.directorybox-listing").width();
                                    if (elementWidth < 992 && elementWidth > 600)
                                        swiper.params.slidesPerView = 2;
                                    if (elementWidth < 600)
                                        swiper.params.slidesPerView = 1;
                                    swiper.update();
                                    $(window).trigger('resize');
                                }
                            });
                        </script>
                        <?php
                    }
                }
            }
        }

        public function listing_nearby_filter($location_slug, $radius, $lat = '', $lng = '', $current_listing_id = '') {
            global $wp_dp_plugin_options;
            $distance_symbol = isset($wp_dp_plugin_options['wp_dp_distance_measure_by']) ? $wp_dp_plugin_options['wp_dp_distance_measure_by'] : 'km';
            if ( $distance_symbol == 'km' ) {
                $radius = $radius / 1.60934; // 1.60934 == 1 Mile
            }
            if ( (isset($location_slug) && $location_slug != '') || ($lat != '' && $lng != '') ) {
                if ( $lat == '' || $lng == '' ) {
                    $Wp_dp_Locations = new Wp_dp_Locations();
                    $location_response = $Wp_dp_Locations->wp_dp_get_geolocation_latlng_callback($location_slug);
                    $lat = isset($location_response->lat) ? $location_response->lat : '';
                    $lng = isset($location_response->lng) ? $location_response->lng : '';
                }

                $radiusCheck = new RadiusCheck($lat, $lng, $radius);
                $minLat = $radiusCheck->MinLatitude();
                $maxLat = $radiusCheck->MaxLatitude();
                $minLong = $radiusCheck->MinLongitude();
                $maxLong = $radiusCheck->MaxLongitude();
                $wp_dp_compare_type = 'CHAR';
                if ( $radius > 0 ) {
                    $wp_dp_compare_type = 'DECIMAL(10,6)';
                }
                $location_condition_arr = array(
                    'relation' => 'OR',
                    array(
                        'key' => 'wp_dp_post_loc_latitude_listing',
                        'value' => array( $minLat, $maxLat ),
                        'compare' => 'BETWEEN',
                        'type' => $wp_dp_compare_type
                    ),
                    array(
                        'key' => 'wp_dp_post_loc_longitude_listing',
                        'value' => array( $minLong, $maxLong ),
                        'compare' => 'BETWEEN',
                        'type' => $wp_dp_compare_type
                    ),
                );
                $args_count = array(
                    'posts_per_page' => "-1",
                    'post_type' => 'listings',
                    'post_status' => 'publish',
                    'fields' => 'ids', // only load ids
                    'meta_query' => array(
                        $location_condition_arr,
                    ),
                );
                if ( isset($current_listing_id) && $current_listing_id != '' ) {
                    $args_count['post__not_in'] = array( $current_listing_id );
                }

                $location_rslt = get_posts($args_count);
                return $location_rslt;
                $rslt = '';
            }
        }

    }

    global $wp_dp_nearby_listings;
    $wp_dp_nearby_listings = new wp_dp_nearby_listings_element();
}