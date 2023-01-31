<?php
/**
 * File Type: Listing Sidebar Gallery Page Element
 */
if ( ! class_exists('wp_dp_sidebar_gallery_element') ) {

    class wp_dp_sidebar_gallery_element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_dp_sidebar_gallery_html', array( $this, 'wp_dp_sidebar_gallery_html_callback' ), 11, 1);
            add_action('wp_dp_sidebar_gallery_map_html', array( $this, 'wp_dp_sidebar_gallery_map_html_callback' ), 11, 1);
        }

        public function wp_dp_sidebar_gallery_html_callback($listing_id = '') {
            global $post, $wp_dp_plugin_options;
            $sidebar_gallery = wp_dp_element_hide_show($listing_id, 'sidebar_gallery');
            if ( $sidebar_gallery != 'on' ) {
                return;
            }
            wp_enqueue_style('swiper');
            wp_enqueue_script('swiper');
            if ( $listing_id == '' ) {
                $listing_id = $post->ID;
            }
            if ( $listing_id != '' ) {
                $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                $wp_dp_listing_type = isset($wp_dp_listing_type) ? $wp_dp_listing_type : '';
                if ( $listing_type_post = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type') )
                    $listing_type_id = $listing_type_post->ID;
                $listing_type_id = isset($listing_type_id) ? $listing_type_id : '';
                $listing_type_id = wp_dp_wpml_lang_page_id($listing_type_id, 'listing-type');
                $gallery_pics_allowed = get_post_meta($listing_id, 'wp_dp_transaction_listing_pic_num', true);
                $wp_dp_image_gallery_element = get_post_meta($listing_type_id, 'wp_dp_image_gallery_element', true);

                if ( $gallery_pics_allowed > 0 && is_numeric($gallery_pics_allowed) ) {
                    $gallery_ids_list = get_post_meta($listing_id, 'wp_dp_detail_page_gallery_ids', true);
                    if ( is_array($gallery_ids_list) && sizeof($gallery_ids_list) > 0 ) {
                        $count_all = count($gallery_ids_list);
                        if ( $count_all > $gallery_pics_allowed ) {
                            $count_all = $gallery_pics_allowed;
                        }
                        ?>
                        <div class="flickr-gallery-slider photo-gallery gallery ">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    <?php
                                    $counter = 1;
                                    foreach ( $gallery_ids_list as $gallery_idd ) {


                                        if ( isset($wp_dp_image_gallery_element) && $wp_dp_image_gallery_element == 'on' ) {
                                            $image = wp_get_attachment_image_src($gallery_idd, 'wp_dp_media_8');
                                            $img_url = (wp_get_attachment_url($gallery_idd));
                                            $image = isset($image[0]) ? $image[0] : '';
                                        } else {
                                            $image = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg');
                                            $img_url = (wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg');
                                        }
                                        if ( isset($image) ) {
                                            if ( $counter <= $gallery_pics_allowed ) {
                                                $first_class = ( $counter == 1) ? 'gallery-first-img' : '';
                                                ?>
                                                <div class="swiper-slide"><a class="pretty-photo-img <?php echo esc_attr($first_class); ?>" data-rel="prettyPhoto[gallery]" href="<?php echo esc_url($img_url); ?>"><img src="<?php echo esc_url($image); ?>" alt="<?php echo wp_dp_plugin_text_srt('wp_dp_slider_image'); ?>" /></a></div>
                                                <?php
                                            }
                                            $counter ++;
                                        }
                                    }
                                    ?>
                                </div>
                                 <?php if(isset($count_all) && $count_all > 1){?>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                                 <?php }?>
                            </div>
                            <?php if(isset($count_all) && $count_all > 1){?>
                            <span><a href="javascript:;" class="pretty-photo-slider"><?php echo wp_dp_plugin_text_srt('wp_dp_slider_view_all_photos'); ?> (<?php echo intval($count_all); ?>)</a></span>
                            <?php } ?>
                        </div>
                        <?php
                        $wp_dp_cs_inline_script = '
                        jQuery(document).ready(function () {
                            jQuery(document).on("click", ".pretty-photo-slider", function() {
                                "use strict";
                                jQuery(".gallery-first-img").click();
                            });
                        });';
                        wp_dp_cs_inline_enqueue_script($wp_dp_cs_inline_script, 'wp-dp-custom-inline');
                    }
                }
            }
        }

        public function wp_dp_sidebar_gallery_map_html_callback($listing_id = '') {
            global $wp_dp_plugin_options, $post, $wp_dp_plugin_options;
            $top_gallery_map = wp_dp_element_hide_show($listing_id, 'top_gallery_map');
            if ( $top_gallery_map != 'on' ) {
                return;
            }
            wp_enqueue_style('wp-dp-prettyPhoto');
            wp_enqueue_script('wp-dp-prettyPhoto');

            if ( $listing_id == '' ) {
                $listing_id = $post->ID;
            }
            if ( $listing_id != '' ) {
                $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                $wp_dp_listing_type = isset($wp_dp_listing_type) ? $wp_dp_listing_type : '';
                if ( $listing_type_post = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type') ) {
                    $listing_type_id = $listing_type_post->ID;
                }
                $listing_type_id = isset($listing_type_id) ? $listing_type_id : '';
                $listing_type_id = wp_dp_wpml_lang_page_id($listing_type_id, 'listing-type');

                $top_gal_map = true;

                $top_gal_with_map_set = isset($wp_dp_plugin_options['wp_dp_detail_view5_top_gallery_map']) ? $wp_dp_plugin_options['wp_dp_detail_view5_top_gallery_map'] : '';
                if ( $top_gal_with_map_set == 'on' ) {
                    $top_gal_map = true;
                } else {
                    $top_gal_map = false;
                }

                $top_gal_with_map = get_post_meta($listing_type_id, 'wp_dp_detail_view5_top_gallery_map', true);

                if ( $top_gal_with_map == 'on' ) {
                    $top_gal_map = true;
                } else {
                    $top_gal_map = false;
                }

                if ( $top_gal_map === false ) {
                    return false;
                }

                $gallery_pics_allowed = get_post_meta($listing_id, 'wp_dp_transaction_listing_pic_num', true);
                if ( $gallery_pics_allowed > 0 && is_numeric($gallery_pics_allowed) ) {
                    $gallery_ids_list = get_post_meta($listing_id, 'wp_dp_detail_page_gallery_ids', true);
                    if ( is_array($gallery_ids_list) && sizeof($gallery_ids_list) > 0 ) {
                        $count_all = count($gallery_ids_list);
                        $slider_flag = false;
                        $slider_class = '';
                        $slider_wrapper = '';
                        $slider_slide = '';
                        if ( $count_all > 3 ) {
                            $slider_class = ' map-gallery-slider';
                            $slider_wrapper = ' swiper-wrapper ';
                            $slider_slide = ' swiper-slide';
                            $slider_flag = true;
                        }
                        ?>
                        <div class="map-gallery-container<?php echo ($slider_class) ?>">
                            <?php if ( $slider_flag ) { ?>
                                <div class="swiper-container">
                                <?php } ?>
                                <ul class="gallery photo-gallery<?php echo ($slider_wrapper) ?>" style="min-height: 480px;">
                                    <?php
                                    $counter = 1;
                                    foreach ( $gallery_ids_list as $gallery_idd ) {

                                        $gal_style = '';
                                        if ( $counter > 3 && $slider_flag == false ) {
                                            $gal_style = ' style="display:none;"';
                                        }
                                        echo '<li class="first-big-image gallery' . $slider_slide . '"' . $gal_style . '>';
                                        $image = wp_get_attachment_image_src($gallery_idd, 'wp_dp_media_12');
                                        if ( isset($image[0]) ) {
                                            ?>
                                            <a data-id="gal-img-<?php echo absint($counter) ?>"><img src="<?php echo esc_url($image[0]); ?>" alt=""></a>
                                            <?php
                                        }
                                        if ( $counter == 1 ) {
                                            echo '<div id="gallery-expander" data-id="' . $listing_id . '"><i class="icon-fullscreen"></i><span>' . intval($count_all) . '</span>' . wp_dp_plugin_text_srt('wp_dp_single_prop_gallery_count_photos') . '<strong class="loader-img"></strong></div>';
                                            echo '<div id="gallery-appender-' . $listing_id . '"></div>';
                                        }
                                        echo '</li>';
                                        $counter ++;
                                    }
                                    ?>
                                </ul>
                                <?php if ( $slider_flag ) { ?>
                                    <div class="swiper-button-prev"> 
                                        <i class="icon-arrow_back"></i>
                                    </div>
                                    <div class="swiper-button-next">
                                        <i class="icon-arrow_forward"></i>
                                    </div>

                                </div>
                            <?php } ?>
                            <?php if ( $slider_flag ) { ?>
                                <script>
                                    jQuery(document).ready(function ($) {
                                        "use strict";
                                        var swiper = new Swiper(".map-gallery-slider .swiper-container", {
                                            slidesPerView: 3,
                                            slidesPerColumn: 1,
                                            loop: false,
                                            paginationClickable: true,
                                            grabCursor: true,
                                              preloadImages: false,
                                         // Enable lazy loading
                                             lazyLoading: true,
                                            autoplay: false,
                                            spaceBetween: 0,
                                            nextButton: ".map-gallery-slider .swiper-button-next",
                                            prevButton: ".map-gallery-slider .swiper-button-prev",
                                            breakpoints: {
                                                1024: {
                                                    slidesPerView: 3,
                                                    spaceBetween: 0
                                                },
                                                991: {
                                                    slidesPerView: 2,
                                                    spaceBetween: 0
                                                },
                                                600: {
                                                    slidesPerView: 1,
                                                    spaceBetween: 0
                                                }
                                            }
                                        });

                                    });

                                </script>
                            <?php } ?>
                        </div>
                        <?php
                    }
                }
            }
        }

    }

    global $wp_dp_sidebar_gallery;
    $wp_dp_sidebar_gallery = new wp_dp_sidebar_gallery_element();
}