<?php
/**
 * Listing search box
 *
 */
global $wp_dp_post_listing_types, $wp_dp_cs_var_options;

if ( false === ( $listing_view = wp_dp_get_transient_obj('wp_dp_listing_view' . $listing_short_counter) ) ) {
    $listing_view = isset($atts['listing_view']) ? $atts['listing_view'] : '';
}
$search_box = isset($atts['search_box']) ? $atts['search_box'] : '';
$main_class = 'listing-medium';
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
$columns_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
if ( $listing_view == 'grid' ) {
    $columns_class = 'col-lg-4 col-md-4 col-sm-12 col-xs-12';
    if ( $search_box == 'yes' && $listing_view != 'map' ) {
        $columns_class = 'col-lg-6 col-md-6 col-sm-12 col-xs-12';
    }
    $main_class = 'listing-grid';
}
if ( isset($wp_dp_cs_var_options['wp_dp_cs_var_excerpt_length']) && $wp_dp_cs_var_options['wp_dp_cs_var_excerpt_length'] <> '' ) {
    $default_excerpt_length = $wp_dp_cs_var_options['wp_dp_cs_var_excerpt_length'];
} else {
    $default_excerpt_length = '18';
}

$listing_location_options = isset($atts['listing_location']) ? $atts['listing_location'] : '';
if ( $listing_location_options != '' ) {
    $listing_location_options = explode(',', $listing_location_options);
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

$http_request = wp_dp_server_protocol();

if ( $listing_loop_obj->have_posts() ) {
    $flag = 1;
    ?>
    <div class="wp-dp-listing">
        <div class="row">
            <?php
            // start top categories
            if ( $element_listing_top_category == 'yes' ) {
                while ( $listing_top_categries_loop_obj->have_posts() ) : $listing_top_categries_loop_obj->the_post();
                    global $post, $wp_dp_member_profile;
                    $listing_id = $post;
					$pro_is_compare = apply_filters('wp_dp_is_compare', $listing_id, $compare_listing_switch);

                    $Wp_dp_Locations = new Wp_dp_Locations();
                    $get_listing_location = $Wp_dp_Locations->get_element_listing_location($listing_id, $listing_location_options);

                    $wp_dp_listing_username = get_post_meta($listing_id, 'wp_dp_listing_username', true);
                    $wp_dp_listing_is_urgent = wp_dp_check_promotion_status($listing_id, 'urgent');
                    $wp_dp_profile_image = $wp_dp_member_profile->member_get_profile_image($wp_dp_listing_username);
                    $wp_dp_listing_price_options = get_post_meta($listing_id, 'wp_dp_listing_price_options', true);
                    $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                    $wp_dp_listing_posted = get_post_meta($listing_id, 'wp_dp_listing_posted', true);
                    $wp_dp_listing_posted = wp_dp_time_elapsed_string($wp_dp_listing_posted);
                    // checking review in on in listing type
                    $wp_dp_listing_type = isset($wp_dp_listing_type) ? $wp_dp_listing_type : '';
                    if ( $listing_type_post = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type') )
                        $listing_type_id = $listing_type_post->ID;
                    $listing_type_id = isset($listing_type_id) ? $listing_type_id : '';
					$listing_type_id = wp_dp_wpml_lang_page_id( $listing_type_id, 'listing-type' );
                    $wp_dp_user_reviews = get_post_meta($listing_type_id, 'wp_dp_user_reviews', true);
                    $wp_dp_listing_type_price_switch = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
                    // end checking review on in listing type
                    $wp_dp_listing_price = '';
                    if ( $wp_dp_listing_price_options == 'price' ) {
                        $wp_dp_listing_price = get_post_meta($listing_id, 'wp_dp_listing_price', true);
                    } else if ( $wp_dp_listing_price_options == 'on-call' ) {
                        $wp_dp_listing_price = wp_dp_plugin_text_srt('wp_dp_listings_price_on_request');
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
                                $wp_dp_cate_str = '<a href="' . $cate_link . '">' . $wp_dp_cate->name . '</a>';
                                $comma_flag ++;
                            }
                        }
                    }
                    ?>
                    <div class="<?php echo esc_html($columns_class); ?>">
                        <div class="<?php echo esc_html($main_class); ?> <?php echo esc_html( $pro_is_compare ); ?>" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Product">
                            <div class="img-holder">
                                <figure>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php
                                        if ( function_exists('listing_gallery_first_image') ) {
                                            $gallery_image_args = array(
                                                'listing_id' => $listing_id,
                                                'size' => 'full',
                                                'class' => 'img-list',
                                                'default_image_src' => esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg'),
                                                'img_extra_atr' =>'itemprop="image"',
                                            );
                                             $listing_gallery_first_image = listing_gallery_first_image($gallery_image_args);
                                            echo wp_dp_cs_allow_special_char($listing_gallery_first_image);
                                        }
                                        ?>
                                    </a>
                                    <figcaption>
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
                                    </figcaption>
                                </figure>
                            </div>

                            <div class="text-holder">
                                <?php if ( $wp_dp_listing_type_price_switch == 'on' && $wp_dp_listing_price != '' ) { ?>
                                    <span class="listing-price">
                                        <span class="new-price text-color" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Offer">
                                            <?php
                                            if ( $wp_dp_listing_price_options == 'on-call' ) {
                                                echo force_balance_tags($wp_dp_listing_price);
                                            } else {
                                                
						$listing_info_price = wp_dp_listing_price($listing_id, $wp_dp_listing_price, '<span class="price" content="' . $wp_dp_listing_price . '" itemprop="price">', '</span>', '<span class="special-price" content="" itemprop="price">', '</span>');
						$wp_dp_get_currency_sign = wp_dp_get_currency_sign('code');
						echo '<span itemprop="priceCurrency" style="display:none;" content="'.$wp_dp_get_currency_sign.'"></span>';
                                                echo force_balance_tags($listing_info_price);
                                            }
                                            ?>
                                        </span>
                                    </span>
                                    <?php
                                }
                                if ( $wp_dp_cate_str != '' || $wp_dp_listing_is_urgent == 'on' ) {
                                    ?>
                                    <div class="post-category-options">
                                        <ul>
                                            <?php if ( $wp_dp_listing_is_urgent == 'on' ) { ?>
                                                <li class="featured-listing">
                                                    <span class="bgcolor"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_urgent'); ?></span>
                                                </li>
                                                <?php
                                            }
                                            if ( $wp_dp_cate_str != '' ) {
                                                ?>
                                                <li><a href="javascript:void(0);"><?php echo ($wp_dp_cate_str); ?></a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                                <?php if ( $wp_dp_profile_image != '' ) {
                                    ?>
                                    <div class="post-meta">
                                        <div class="post-by">
                                            <figure>
                                                <img src="<?php echo esc_url($wp_dp_profile_image); ?>" alt="" width="41" height="41">
                                            </figure>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="post-title">
                                    <h4 itemprop="name"><a href="<?php echo esc_url(get_permalink($listing_id)); ?>"><?php echo esc_html(get_the_title($listing_id)); ?></a></h4>
                                    <?php if ( $wp_dp_listing_is_urgent == 'on' ) { ?><div class="feature-check"><i class="icon-check2"></i></div><?php } ?>
                                </div>
                                <div class="post-time">
                                    <i class="icon-clock3"></i><span><?php echo esc_html($wp_dp_listing_posted); ?></span>
                                </div>
                                <?php
                                $ratings_data = array(
                                    'overall_rating' => 0.0,
                                    'count' => 0,
                                );
                                $ratings_data = apply_filters('reviews_ratings_data', $ratings_data, $listing_id);
                                ?>
                                <?php if ( ! empty($get_listing_location) ) { ?>
                                    <ul class="listing-location">
                                        <li><i class="icon-location-pin2"></i><span><?php echo esc_html(implode(', ', $get_listing_location)); ?></span></li>
                                    </ul>
                                    <?php
                                }
                                ?>
                                <p itemprop="description" class="description"><?php echo wp_dp_limit_text(get_the_content($listing_id), $default_excerpt_length); ?></p>
                            </div>

                        </div>
                    </div>
                    <?php
                endwhile;
            }
            // end top categories
            if ( sizeof($listings_ads_array) > 0 && in_array(0, $listings_ads_array) && ($listing_page == 1 || $listing_page == '') ) {
                ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php do_action('wp_dp_random_ads', 'listing_banner'); ?>
                </div>
                <?php
            }
            while ( $listing_loop_obj->have_posts() ) : $listing_loop_obj->the_post();
                global $post, $wp_dp_member_profile;
                $listing_id = $post;
				$pro_is_compare = apply_filters('wp_dp_is_compare', $listing_id, $compare_listing_switch);

                $Wp_dp_Locations = new Wp_dp_Locations();
                $get_listing_location = $Wp_dp_Locations->get_element_listing_location($listing_id, $listing_location_options);

                $wp_dp_listing_username = get_post_meta($listing_id, 'wp_dp_listing_username', true);
                $wp_dp_listing_is_urgent = wp_dp_check_promotion_status($listing_id, 'urgent');
                $wp_dp_profile_image = $wp_dp_member_profile->member_get_profile_image($wp_dp_listing_username);
                $wp_dp_listing_price_options = get_post_meta($listing_id, 'wp_dp_listing_price_options', true);
                $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                $wp_dp_listing_posted = get_post_meta($listing_id, 'wp_dp_listing_posted', true);
                $wp_dp_listing_posted = wp_dp_time_elapsed_string($wp_dp_listing_posted);
                // checking review in on in listing type
                $wp_dp_listing_type = isset($wp_dp_listing_type) ? $wp_dp_listing_type : '';
                if ( $listing_type_post = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type') )
                    $listing_type_id = $listing_type_post->ID;
                $listing_type_id = isset($listing_type_id) ? $listing_type_id : '';
				$listing_type_id = wp_dp_wpml_lang_page_id( $listing_type_id, 'listing-type' );
                $wp_dp_user_reviews = get_post_meta($listing_type_id, 'wp_dp_user_reviews', true);
                $wp_dp_listing_type_price_switch = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
                // end checking review on in listing type
                $wp_dp_listing_price = '';
                if ( $wp_dp_listing_price_options == 'price' ) {
                    $wp_dp_listing_price = get_post_meta($listing_id, 'wp_dp_listing_price', true);
                } else if ( $wp_dp_listing_price_options == 'on-call' ) {
                    $wp_dp_listing_price = wp_dp_plugin_text_srt('wp_dp_listings_price_on_request');
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
                            $wp_dp_cate_str = '<a href="' . $cate_link . '">' . $wp_dp_cate->name . '</a>';
                            $comma_flag ++;
                        }
                    }
                }
                ?>
                <div class="<?php echo esc_html($columns_class); ?>">
                    <div class="<?php echo esc_html($main_class); ?> <?php echo esc_html( $pro_is_compare ); ?>" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Product">
                        <div class="img-holder">
                            <figure>
                                <a href="<?php the_permalink(); ?>">
                                    <?php
                                    if ( function_exists('listing_gallery_first_image') ) {
                                        $gallery_image_args = array(
                                            'listing_id' => $listing_id,
                                            'size' => 'full',
                                            'class' => 'img-list',
                                            'default_image_src' => esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg'),
                                            'img_extra_atr' =>'itemprop="image"',
                                        );
                                        $listing_gallery_first_image = listing_gallery_first_image($gallery_image_args);
                                        echo wp_dp_cs_allow_special_char($listing_gallery_first_image);
                                    }
                                    ?>
                                </a>
                                <figcaption>
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
                                </figcaption>
                            </figure>
                        </div>

                        <div class="text-holder">
                            <?php if ( $wp_dp_listing_type_price_switch == 'on' && $wp_dp_listing_price != '' ) { ?>
                                <span class="listing-price">
                                    <span class="new-price text-color" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Offer">
                                        <?php
                                        if ( $wp_dp_listing_price_options == 'on-call' ) {
                                            echo force_balance_tags($wp_dp_listing_price);
                                        } else {
                                          
											$listing_info_price = wp_dp_listing_price($listing_id, $wp_dp_listing_price, '<span class="price" content="' . $wp_dp_listing_price . '" itemprop="price">', '</span>', '<span class="special-price" content="" itemprop="price">', '</span>');
											$wp_dp_get_currency_sign = wp_dp_get_currency_sign('code');
																		echo '<span itemprop="priceCurrency" style="display:none;" content="'.$wp_dp_get_currency_sign.'"></span>';
                                            echo force_balance_tags($listing_info_price);
                                        }
                                        ?>
                                    </span>
                                </span>
                                <?php
                            }
                            if ( $wp_dp_cate_str != '' || $wp_dp_listing_is_urgent == 'on' ) {
                                ?>
                                <div class="post-category-options">
                                    <ul>
                                        <?php if ( $wp_dp_listing_is_urgent == 'on' ) { ?>
                                            <li class="featured-listing">
                                                <span class="bgcolor"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_urgent'); ?></span>
                                            </li>
                                            <?php
                                        }
                                        if ( $wp_dp_cate_str != '' ) {
                                            ?>
                                            <li><a href="javascript:void(0);"><?php echo ($wp_dp_cate_str); ?></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                            <?php if ( $wp_dp_profile_image != '' ) {
                                ?>
                                <div class="post-meta">
                                    <div class="post-by">
                                        <figure>
                                            <img src="<?php echo esc_url($wp_dp_profile_image); ?>" alt="" width="41" height="41">
                                        </figure>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="post-title">
                                <h4 itemprop="name"><a href="<?php echo esc_url(get_permalink($listing_id)); ?>"><?php echo esc_html(get_the_title($listing_id)); ?></a></h4>
                                <?php if ( $wp_dp_listing_is_urgent == 'on' ) { ?><div class="feature-check"><i class="icon-check2"></i></div><?php } ?>
                            </div>
                            <div class="post-time">
                                <i class="icon-clock3"></i><span><?php echo esc_html($wp_dp_listing_posted); ?></span>
                            </div>
                            <?php
                            $ratings_data = array(
                                'overall_rating' => 0.0,
                                'count' => 0,
                            );
                            $ratings_data = apply_filters('reviews_ratings_data', $ratings_data, $listing_id);
                            ?>
                            <?php if ( ! empty($get_listing_location) ) { ?>
                                <ul class="listing-location">
                                    <li><i class="icon-location-pin2"></i><span><?php echo esc_html(implode(', ', $get_listing_location)); ?></span></li>
                                </ul>
                                <?php
                            }
                            ?>
                            <p itemprop="description" class="description"><?php echo wp_dp_limit_text(get_the_content($listing_id), $default_excerpt_length); ?></p>
                        </div>

                    </div>
                </div>
                <?php
                if ( sizeof($listings_ads_array) > 0 && in_array($counter, $listings_ads_array) ) {
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php do_action('wp_dp_random_ads', 'listing_banner'); ?>
                    </div>
                    <?php
                }
                $counter ++;
            endwhile;
            ?>
        </div>
    </div>
    <?php
} else {
    echo '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-listing-match-error"><h6><i class="icon-warning"></i><strong> ' . wp_dp_plugin_text_srt('wp_dp_listing_slider_sorry') . '</strong>&nbsp; ' . wp_dp_plugin_text_srt('wp_dp_listing_slider_doesn_match') . ' </h6></div></div>';
}