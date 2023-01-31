<?php
/**
 * The template for displaying single member
 *
 */
global $post, $wp_dp_plugin_options, $wp_dp_theme_options, $Wp_dp_Captcha, $wp_dp_form_fields_frontend, $wp_dp_post_listing_types, $wp_dp_author_info;
$post_id = $post->ID;
$wp_dp_user_status = get_post_meta($post_id, 'wp_dp_user_status', true);
$wp_dp_captcha_switch = '';
$wp_dp_captcha_switch = isset($wp_dp_plugin_options['wp_dp_captcha_switch']) ? $wp_dp_plugin_options['wp_dp_captcha_switch'] : '';
$wp_dp_sitekey = isset($wp_dp_plugin_options['wp_dp_sitekey']) ? $wp_dp_plugin_options['wp_dp_sitekey'] : '';
$wp_dp_secretkey = isset($wp_dp_plugin_options['wp_dp_secretkey']) ? $wp_dp_plugin_options['wp_dp_secretkey'] : '';
$default_listing_no_custom_fields = isset($wp_dp_plugin_options['wp_dp_listing_no_custom_fields']) ? $wp_dp_plugin_options['wp_dp_listing_no_custom_fields'] : '';
$wp_dp_phone_number = get_post_meta($post_id, 'wp_dp_phone_number', true);
$wp_dp_email_address = get_post_meta($post_id, 'wp_dp_email_address', true);
$wp_dp_email_address = isset($wp_dp_email_address) ? $wp_dp_email_address : '';
$wp_dp_biography = get_post_meta($post_id, 'wp_dp_biography', true);
$wp_dp_post_loc_address_member = get_post_meta($post_id, 'wp_dp_post_loc_address_member', true);
$wp_dp_facebook = get_post_meta($post_id, 'wp_dp_facebook', true);
$wp_dp_google_plus = get_post_meta($post_id, 'wp_dp_google_plus', true);
$wp_dp_twitter = get_post_meta($post_id, 'wp_dp_twitter', true);
$wp_dp_linkedIn = get_post_meta($post_id, 'wp_dp_linkedIn', true);
$wp_dp_post_loc_latitude_member = get_post_meta($post_id, 'wp_dp_post_loc_latitude_member', true);
$wp_dp_post_loc_longitude_member = get_post_meta($post_id, 'wp_dp_post_loc_longitude_member', true);
$default_zoom_level = ( isset($wp_dp_plugin_options['wp_dp_map_zoom_level']) && $wp_dp_plugin_options['wp_dp_map_zoom_level'] != '' ) ? $wp_dp_plugin_options['wp_dp_map_zoom_level'] : 10;
$wp_dp_listing_zoom = get_post_meta($post_id, 'wp_dp_post_loc_zoom_member', true);
if ( $wp_dp_listing_zoom == '' || $wp_dp_listing_zoom == 0 ) {
    $wp_dp_listing_zoom = $default_zoom_level;
}
$member_image_id = get_post_meta($post_id, 'wp_dp_profile_image', true);
$member_image = wp_get_attachment_url($member_image_id);
if ( $member_image == '' ) {
    $member_image = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/member-no-image.jpg');
}
$member_title = '';
$member_title = get_the_title($post_id);
$member_link = '';
$member_link = get_the_permalink($post_id);
wp_enqueue_script('wp-dp-prettyPhoto');
wp_enqueue_style('wp-dp-prettyPhoto');
$wp_dp_cs_inline_script = '
                jQuery(document).ready(function () {
                     jQuery("a.listing-video-btn[data-rel^=\'prettyPhoto\']").prettyPhoto({animation_speed:"fast",slideshow:10000, hideflash: true,autoplay:true,autoplay_slideshow:false});
                });';
wp_dp_cs_inline_enqueue_script($wp_dp_cs_inline_script, 'wp-dp-custom-inline');


$wp_dp_custom_title_length = isset($wp_dp_plugin_options['wp_dp_custom_title_length']) ? $wp_dp_plugin_options['wp_dp_custom_title_length'] : 5;
$wp_dp_custom_content_length = isset($wp_dp_plugin_options['wp_dp_custom_content_length']) ? $wp_dp_plugin_options['wp_dp_custom_content_length'] : 10;






if ( isset($wp_dp_user_status) && $wp_dp_user_status == 'active' ) {

    $http_request = wp_dp_server_protocol();
    ?>
    <div class="page-content col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div itemprop="performer" itemscope="" itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Person"  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div id="member-map-collapse" style="display:none;">

                    <?php if ( ( isset($wp_dp_post_loc_latitude_member) && $wp_dp_post_loc_latitude_member != '' ) && ( isset($wp_dp_post_loc_longitude_member) && $wp_dp_post_loc_longitude_member != '' ) ) { ?>
                        <div class="widget widget-map-sec">
                            <?php
                            $marker_info = '';
                            $marker_info .= '<div id="listing-info-' . $post_id . '-' . '" class="listing-info-inner">';
                            $marker_info .= '<div class="info-main-container">';
                            if ( $member_image != '' ) {
                                $marker_info .= '<figure style="text-align: center;"><a class="info-title" href="' . $member_link . '"><img src="' . $member_image . '" style="width: 100px;"></a></figure>';
                            }
                            $marker_info .= '<div class="info-txt-holder">';
                            $marker_info .= '<a class="info-title" href="' . $member_link . '"><b>' . $member_title . '</b></a>';

                            $marker_info .= '<ul class="info-list">';
                            if ( isset($wp_dp_post_loc_address_member) && $wp_dp_post_loc_address_member != '' ) {
                                $marker_info .= '<li><i class="icon-map-pin"></i> ' . esc_html($wp_dp_post_loc_address_member) . '</li>';
                            }

                            if ( isset($wp_dp_phone_number) && $wp_dp_phone_number != '' ) {
                                $wp_dp_phone_number = str_replace(" ", "-", $wp_dp_phone_number);
                                $marker_info .= '<li><i class="icon-phone2"></i> <a href="tel:' . esc_html($wp_dp_phone_number) . '">' . esc_html($wp_dp_phone_number) . '</a> </li>';
                            }

                            if ( isset($wp_dp_email_address) && $wp_dp_email_address != '' ) {
                                $marker_info .= '<li><i class="icon-mail6"></i> <a href="mailto:' . esc_html($wp_dp_email_address) . '">' . esc_html($wp_dp_email_address) . '</a></li>';
                            }
                            $marker_info .= '</ul>';
                            $marker_info .= '</div>';
                            $marker_info .= '</div>';
                            $marker_info .= '</div>';
                            $map_atts = array(
                                'map_height' => '350',
                                'map_lat' => $wp_dp_post_loc_latitude_member,
                                'map_lon' => $wp_dp_post_loc_longitude_member,
                                'map_zoom' => $wp_dp_listing_zoom,
                                'map_type' => '',
                                'map_info' => $marker_info,
                                'map_info_width' => '230',
                                'map_info_height' => '350',
                                'map_marker_icon' => '',
                                'map_show_marker' => 'true',
                                'map_controls' => 'true',
                                'map_draggable' => 'true',
                                'map_scrollwheel' => 'false',
                                'map_border' => '',
                                'map_border_color' => '',
                                'wp_dp_map_style' => '',
                                'wp_dp_map_class' => '',
                                'wp_dp_map_directions' => 'off',
                                'wp_dp_map_circle' => '',
                                'wp_dp_branches_map' => true,
                                'quick_view_controls' => true,
                                'hide_map_btn' => true
                            );
                            if ( isset($branches) ) {
                                $map_atts['wp_dp_branches_markers'] = isset($branches_markers) ? $branches_markers : '';
                            }
                            if ( function_exists('wp_dp_map_content') ) {
                                wp_dp_map_content($map_atts);
                                ?>
                                <script type="text/javascript">
                                    jQuery(function () {
                                        jQuery("#member_map_collapsee").on('click', function () {
                                            if (jQuery(this).hasClass("collapsed")) {
                                                jQuery('#member-map-collapse').slideDown('slow');
                                                jQuery('#member_map_collapsee').text('<?php echo wp_dp_plugin_text_srt('wp_dp_agent_review_hide_map'); ?>');
                                            } else {
                                                jQuery('#member-map-collapse').slideUp('slow');
                                                jQuery('#member_map_collapsee').text('<?php echo wp_dp_plugin_text_srt('wp_dp_agent_review_show_map'); ?>');
                                            }
                                            if (!window.is_address_link_clicked) {
                                                google.maps.event.trigger(map, 'resize');
                                                var center1 = new google.maps.LatLng("<?php echo wp_dp_cs_allow_special_char($wp_dp_post_loc_latitude_member); ?>", "<?php echo wp_dp_cs_allow_special_char($wp_dp_post_loc_longitude_member); ?>");
                                                map.panTo(center1);

                                            }
                                            window.is_address_link_clicked = false;
                                        });
                                        jQuery(".cs-map-hide a").on('click', function () {
                                            jQuery('#member-map-collapse').slideUp('slow');
                                            //jQuery('#member-map-collapse').removeClass( "in" );
                                            jQuery("#member_map_collapsee").addClass("collapsed");
                                            jQuery('#member_map_collapsee').text('<?php echo wp_dp_plugin_text_srt('wp_dp_agent_review_show_map'); ?>');
                                        });
                                    });
                                </script>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <!-- Modal -->
                </div>
                <?php if ( isset($wp_dp_biography) && $wp_dp_biography != '' ) { ?>
                    <div class="member-description">
                        <p itemprop="disambiguatingDescription"><?php echo force_balance_tags(str_replace("<br/>", '</p><p>', str_replace("<br />", '</p><p>', nl2br($wp_dp_biography)))); ?></p>
                    </div>
                <?php } ?>

                <!--Tabs End-->
            </div>
            <div id="listings"></div>
            <?php
            if ( $post_count > 0 ) {
                ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="element-title">
                        <h2><?php
                            echo get_the_title($post_id) . ' ';
                            echo wp_dp_plugin_text_srt('wp_dp_member_listings');
                            ?></h2>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="directorybox-listing">
                        <div class="row  listing-append">
                            <?php
                            $listing_location_options = 'city,country';
                            if ( $listing_location_options != '' ) {
                                $listing_location_options = explode(',', $listing_location_options);
                            }
                            $wp_dp_listings_title_limit = $wp_dp_custom_title_length;
                            while ( $custom_query->have_posts() ) : $custom_query->the_post();
                                global $post;
                                $listing_id = $post->ID;
                                $Wp_dp_Locations = new Wp_dp_Locations();
                                $get_listing_location = $Wp_dp_Locations->get_element_listing_location($listing_id, $listing_location_options);
                                $listing_random_id = rand(1111111, 9999999);
                                $wp_dp_listing_member = get_post_meta($listing_id, 'wp_dp_listing_member', true);
                                $wp_dp_cover_image_id = get_post_meta($listing_id, 'wp_dp_cover_image', true);
                                $wp_dp_cover_image = wp_get_attachment_url($wp_dp_cover_image_id);
                                $wp_dp_post_loc_address_listing = get_post_meta($listing_id, 'wp_dp_post_loc_address_listing', true);
                                $wp_dp_listing_price_options = get_post_meta($listing_id, 'wp_dp_listing_price_options', true);
                                $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                                $wp_dp_listing_type_cus_fields = $wp_dp_post_listing_types->wp_dp_types_custom_fields_array($wp_dp_listing_type);
                                $wp_dp_listing_is_urgent = wp_dp_check_promotion_status($listing_id, 'urgent');
                                $wp_dp_listing_is_top_cat = wp_dp_check_promotion_status($listing_id, 'top-categories');
                                $wp_dp_listing_gallery_ids = get_post_meta($listing_id, 'wp_dp_detail_page_gallery_ids', true);
                                $gallery_pics_allowed = get_post_meta($listing_id, 'wp_dp_transaction_listing_pic_num', true);

                                $wp_dp_listing_price = '';
                                if ( $wp_dp_listing_price_options == 'price' ) {
                                    $wp_dp_listing_price = get_post_meta($listing_id, 'wp_dp_listing_price', true);
                                } else if ( $wp_dp_listing_price_options == 'on-call' ) {
                                    $wp_dp_listing_price = wp_dp_plugin_text_srt('wp_dp_listings_price_on_request');
                                }
                                $count_all = ( isset($wp_dp_listing_gallery_ids) && is_array($wp_dp_listing_gallery_ids) && sizeof($wp_dp_listing_gallery_ids) > 0 ) ? count($wp_dp_listing_gallery_ids) : 0;
                                if ( $count_all > $gallery_pics_allowed ) {
                                    $count_all = $gallery_pics_allowed;
                                }
                                $gallery_image_count = $count_all;
                                // checking review in on in listing type
                                $wp_dp_listing_type = isset($wp_dp_listing_type) ? $wp_dp_listing_type : '';
                                if ( $listing_type_post = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type') )
                                    $listing_type_id = $listing_type_post->ID;
                                $listing_type_id = isset($listing_type_id) ? $listing_type_id : '';
                                $listing_type_id = wp_dp_wpml_lang_page_id($listing_type_id, 'listing-type');

                                $wp_dp_listing_type_price_switch = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);

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
                                // get all categories
                                $wp_dp_cate = '';
                                $wp_dp_cate_str = '';

                                $wp_dp_listing_category = get_post_meta($listing_id, 'wp_dp_listing_category', true);


                                $wp_dp_post_loc_address_listing = get_post_meta($listing_id, 'wp_dp_post_loc_address_listing', true);
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

                                //echo $wp_dp_cate_str.'=====';;

                                $columns_class = 'col-lg-4 col-md-4 col-sm-6 col-xs-12';
                                $main_class = 'listing-grid';
                                $listings_excerpt_length = $wp_dp_custom_content_length;
                                ?>

                                <div class="listing-row <?php echo esc_html($columns_class); ?>">
                                    <div class="<?php echo esc_html($main_class); ?> <?php echo isset($pro_is_compare) && ! empty($pro_is_compare) ? esc_html($pro_is_compare) : ''; ?> list-top-category advance-grid" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Product">
                                        <div class="listing-inner">
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

                                                $title__ = get_the_title($listing_id);
                                                if ( isset($title__) && ! empty($title__) ) {
                                                    ?>
                                                    <div class="post-title">
                                                        <h4 itemprop="name">

                                                            <?php if ( $wp_dp_listing_is_top_cat == 'on' ) {
                                                                ?>
                                                                <a href="javascript:void(0)" class="wp-google-add" ><?php echo wp_dp_plugin_text_srt('wp_dp_listing_top_category'); ?></a> 
                                                            <?php } ?>

                                                            <a href="<?php echo esc_url(get_permalink($listing_id)); ?>">
                                                                <?php echo wp_dp_limit_text($title__, $wp_dp_listings_title_limit); ?><i class="icon-verified-circle"></i></a></h4>
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
                                                }
                                                $list_content = get_post_field('post_content', $listing_id);

                                                if ( isset($list_content) && ! empty($list_content) ) {
                                                    ?>
                                                    <p><?php
                                    echo wp_dp_limit_text($list_content, $listings_excerpt_length);
                                                    ?>
                                                    </p>
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
                                            <?php ?>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                wp_reset_postdata();
                            endwhile;
                            ?>
                        </div>
                        <?php
                        $wp_dp_opt_array = array(
                            'std' => 1,
                            'cust_id' => 'listing_current_page',
                            'cust_name' => 'listing_current_page',
                            'cust_type' => 'hidden',
                            'classes' => '',
                        );
                        $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                        $max_page = ceil($post_count / $paging_var_perpage);
                        $wp_dp_opt_array = array(
                            'std' => $max_page,
                            'cust_id' => 'listing_max_num_pages',
                            'cust_name' => 'listing_max_num_pages',
                            'cust_type' => 'hidden',
                            'classes' => '',
                        );
                        $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                        $wp_dp_opt_array = array(
                            'std' => $member_id,
                            'cust_id' => 'listing_member_id',
                            'cust_name' => 'listing_member_id',
                            'cust_type' => 'hidden',
                            'classes' => '',
                        );
                        $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                        $wp_dp_opt_array = array(
                            'std' => $paging_var_perpage,
                            'cust_id' => 'listing_per_page',
                            'cust_name' => 'listing_per_page',
                            'cust_type' => 'hidden',
                            'classes' => '',
                        );
                        $wp_dp_form_fields_frontend->wp_dp_form_text_render($wp_dp_opt_array);
                        ?>
                    </div>
                    <?php
                    if ( isset($post_count) && $post_count > $paging_var_perpage ) {
                        ?>
                        <div id="meme-listing-more-btn-holder" class="btn-more-holder">
                            <a href="javascript:void(0);" class="btn-load-more member-listing-load"><i class="icon-circular-button"></i><div class="load-more-member-listing input-button-loader"><?php echo wp_dp_plugin_text_srt('wp_dp_load_more_listings_members'); ?></div></a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?> 
        </div>
    </div>
<?php } else {
    ?> 
    <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="member-inactive">
            <i class="icon-warning"></i>
            <span> <?php echo wp_dp_plugin_text_srt('wp_dp_user_profile_not_active'); ?></span>
        </div>
    </div>
<?php }
?>