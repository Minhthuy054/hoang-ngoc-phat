<?php
/**
 * Listing Member search box
 *
 */
$search_box = isset($atts['search_box']) ? $atts['search_box'] : '';
// start ads script
$member_ads_switch = isset($atts['member_ads_switch']) ? $atts['member_ads_switch'] : 'no';
$member_excerpt_length = isset($atts['member_excerpt_length']) ? $atts['member_excerpt_length'] : '10';
if ($member_ads_switch == 'yes') {
    $member_ads_after_list_series = isset($atts['member_ads_after_list_count']) ? $atts['member_ads_after_list_count'] : '5';
    if ($member_ads_after_list_series != '') {
        $member_ads_list_array = explode(",", $member_ads_after_list_series);
    }
    $member_ads_after_list_array_count = sizeof($member_ads_list_array);
    $member_ads_after_list_flag = 0;

    $i = 0;
    $array_i = 0;
    $member_ads_after_list_array_final = '';
    while ($member_ads_after_list_array_count > $array_i) {
        if (isset($member_ads_list_array[$array_i]) && $member_ads_list_array[$array_i] != '') {
            $member_ads_after_list_array[$i] = $member_ads_list_array[$array_i];
            $i ++;
        }
        $array_i ++;
    }
    // new count 
    $member_ads_after_list_array_count = sizeof($member_ads_after_list_array);
}
$member_page = isset($_REQUEST['member_page']) ? $_REQUEST['member_page'] : '';
$posts_per_page = isset($atts['posts_per_page']) ? $atts['posts_per_page'] : '';
$counter = 0;
if ($member_page >= 2) {
    $counter = ( ($member_page - 1) * $posts_per_page );
}
$member_ads_number_counter = 1;
$member_ads_flag_counter = 0;
$member_ads_last_number = 0;
if (isset($member_ads_after_list_array) && !empty($member_ads_after_list_array)) {
    foreach ($member_ads_after_list_array as $key => $member_ads_number) {
        $member_ads_last_number = $member_ads_number;
    }
    foreach ($member_ads_after_list_array as $key => $member_ads_number) {
        if ($member_page == 1 || $member_page == '') {
            $member_ads_flag_counter = $key;
            break;
        } elseif ($counter < $member_ads_number) {
            $member_ads_flag_counter = $key;
            break;
        } elseif ($member_ads_number_counter == $member_ads_after_list_array_count) {
            $member_ads_flag_counter = $key;
            break;
        }
        $member_ads_number_counter ++;
    }
}
// end ads script 
$member_location_options = isset($atts['member_location']) ? $atts['member_location'] : '';
if ($member_location_options != '') {
    $member_location_options = explode(',', $member_location_options);
}

$http_request = wp_dp_server_protocol();
if ($member_loop_obj->have_posts()) {
    $flag = 1;
    ?>
    <div class="member-listing member-medium">
        <?php
        if ($member_ads_switch == 'yes') {
            if ($member_ads_after_list_array_count > 0 && ( $member_page == 1 || $member_page == '')) {
                if ($counter == $member_ads_after_list_array[$member_ads_flag_counter] && $member_ads_after_list_array[$member_ads_flag_counter] == 0) {
                    ?>
                    <div class="member-post">
                        <?php do_action('wp_dp_random_ads', 'member_banner'); ?>
                    </div>
                    <?php
                    if ($member_ads_flag_counter < $member_ads_after_list_array_count) {
                        $member_ads_flag_counter ++;
                    }
                }
            }
        }
        while ($member_loop_obj->have_posts()) : $member_loop_obj->the_post();
            global $post, $wp_dp_member_profile, $wp_dp_author_info;
            $post_id = $post;
            $Wp_dp_Locations = new Wp_dp_Locations();
            $get_member_location = $Wp_dp_Locations->get_element_member_location($post_id, $member_location_options);
            $member_id = get_post_meta($post_id, 'wp_dp_listing_member', true);
            $member_is_featured = get_post_meta($post_id, 'wp_dp_member_is_featured', true);
            $member_is_trusted = get_post_meta($post_id, 'wp_dp_member_is_trusted', true);
            $member_image_id = get_post_meta($post_id, 'wp_dp_profile_image', true);
            $member_image = wp_get_attachment_image_src($member_image_id, 'thumbnail');
            
            $wp_dp_phone_number = get_post_meta($post_id, 'wp_dp_phone_number', true);
            $wp_dp_biography = get_post_meta($post_id, 'wp_dp_biography', true);
            $wp_dp_post_loc_address_member = get_post_meta($post_id, 'wp_dp_post_loc_address_member', true);
            $review_percentage = get_post_meta($post_id, 'wp_dp_total_percentage', true);
            $review_count = get_post_meta($post_id, 'wp_dp_total_review_count', true);
            
             $member_num_of_listings = get_post_meta($post_id, 'wp_dp_num_of_listings', true);

            
            $list_args = array(
                'posts_per_page' => "1",
                'post_type' => 'listings',
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_listing_member',
                        'value' => $post_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_listing_expired',
                        'value' => strtotime(date("d-m-Y")),
                        'compare' => '>=',
                    ),
                    array(
                        'key' => 'wp_dp_listing_status',
                        'value' => 'delete',
                        'compare' => '!=',
                    ),
                ),
            );
            $custom_query = new WP_Query($list_args);
            $num_of_listings = $member_num_of_listings;
            
            $num_of_branshes = apply_filters('wp_dp_member_branches_count', $post_id);
            ?>
            <div class="member-post" itemprop="performer" itemscope="" itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Person"> 
                <div class="img-holder">
                    <figure> 
                        <a title="<?php echo esc_html(get_the_title($member_id)); ?>" href="<?php the_permalink(); ?>">
                            <?php
                            if (isset($member_image[0]) && !empty($member_image[0])) {
                                $no_image = '<img itemprop="image" src="' . $member_image[0] . '" alt=""/>';
                                echo force_balance_tags($no_image);
                            } else {
                                $no_image_url = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg');
                                $no_image = '<img itemprop="image" class="img-grid" src="' . $no_image_url . '" alt=""/>';
                                echo force_balance_tags($no_image);
                            }
                            ?>
                        </a>
                    </figure>
                </div>
                <div class="text-holder">
                    <div class="post-title">
                        <h4 itemprop="name"> 
                            <?php if (isset($member_is_featured) && $member_is_featured == 'on') {
                                ?>
                                <span class="sponsored"><?php echo wp_dp_plugin_text_srt('wp_dp_listing_top_category'); ?></span> 
                            <?php } ?>
                            <a title="<?php echo esc_html(get_the_title($member_id)); ?>" href="<?php the_permalink(); ?>"><?php echo esc_html(get_the_title($member_id)); ?></a>

                            <?php if (isset($member_is_trusted) && $member_is_trusted == 'on') {
                                ?>
                                <span class="trusted-member"><i class="icon-verified_user"></i></span>
                                <?php
                            }

                            // check today status
                            $today_status = false;
                            $today_status = apply_filters('wp_dp_today_status_element_html', $post_id);
                            if( $today_status != 'opening_hours_off'){
                            if ($today_status == true) {
                                ?>
                                <span class="member-status open"><?php echo wp_dp_plugin_text_srt('wp_dp_member_open_now'); ?></span>
                            <?php } else {
                                ?>
                                <span class="member-status"><?php echo wp_dp_plugin_text_srt('wp_dp_member_close_now'); ?></span>
                            <?php }} ?>
                        </h4>
                    </div>
                    <?php if (!empty($get_member_location)) { ?>
                        <span itemprop="address" class="member-address"><i class="icon-map-marker"></i><?php echo esc_html(implode(', ', $get_member_location)); ?> </span>
                        <?php
                    }
                    ?> 
                    <div class="rating-list-holder rating-holder"> 
                        <?php if (isset($review_count) && $review_count > 0) { ?>
                            <span class="reviews-count"><?php echo absint($review_count); ?> <?php echo wp_dp_plugin_text_srt('wp_dp_agent_review_reviews'); ?></span>
                            <div class="rating-star" data-toggle="popover_html"> <span style="width: <?php echo absint($review_percentage); ?>%;" class="rating-box"></span> </div>
                        <?php } ?>
                        <ul class="member-info">
                            <li><?php
                        $listings_link_start = '';
                        $listings_link_end = '';
                        if ($num_of_listings > 0) {
                            $listings_link_start = '<a href="' . get_the_permalink($member_id) . '#listings">';
                            $listings_link_end = '</a>';
                        }
                        ?>

                                <span class="listings-count"><i class="icon-format_list_bulleted"></i><?php echo wp_dp_allow_special_char($listings_link_start); ?> 
                                    <span> <?php echo absint($num_of_listings); ?> <?php echo wp_dp_plugin_text_srt('wp_dp_member_listings2'); ?>  </span>
                                    <?php echo wp_dp_allow_special_char($listings_link_end); ?></span>
                            </li> 

                        </ul>
                    </div>


                    <?php if ($wp_dp_biography != '') { ?>
                        <p itemprop="disambiguatingDescription"><strong><?php echo wp_dp_plugin_text_srt('wp_dp_listing_about'); ?></strong> <?php echo (wp_dp_limit_text($wp_dp_biography, $member_excerpt_length)); ?></p>
                        <?php
                    }
                    ?>
                    <!--   temperory commented  --> 

                </div>
            </div>
            <?php
            if ($member_ads_switch == 'yes') {
                if ($member_ads_after_list_array_count > 0) {
                    $new_counter = $counter + 1;
                    $member_ads_value = $member_ads_after_list_array[$member_ads_flag_counter];
                    if ($new_counter == $member_ads_after_list_array[$member_ads_flag_counter]) {
                        ?>   
                        <div class="member-post">   <?php do_action('wp_dp_random_ads', 'member_banner'); ?></div>
                        <?php
                        if ($member_ads_flag_counter < ($member_ads_after_list_array_count - 1)) {
                            $member_ads_flag_counter ++;
                        }
                    } elseif ($new_counter % $member_ads_value == 0 && $new_counter > $member_ads_last_number && $new_counter != 1) {
                        ?>  
                        <div class="member-post">  <?php do_action('wp_dp_random_ads', 'member_banner'); ?></div>
                        <?php
                    }
                }
            }
            $counter ++;
            ?>
            <?php
        endwhile;
        ?>
    </div>
    <?php
} else {
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-member-match-error"><h6><i class="icon-warning"></i><strong> ' . wp_dp_plugin_text_srt('wp_dp_member_sorry') . '</strong>&nbsp; ' . wp_dp_plugin_text_srt('wp_dp_member_no_results') . ' </h6></div>';
}