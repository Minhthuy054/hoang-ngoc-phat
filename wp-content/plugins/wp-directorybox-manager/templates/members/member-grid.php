<?php
/**
 * Listing Member search box
 *
 */
?>
<!--Element Section Start-->
<!--Wp-dp Element Start-->
<?php
if ( false === ( $member_view = wp_dp_get_transient_obj('wp_dp_member_view' . $member_short_counter) ) ) {
    $member_view = isset($atts['member_view']) ? $atts['member_view'] : '';
}
$search_box = isset($atts['search_box']) ? $atts['search_box'] : '';
$main_class = 'member-medium';
// start ads script
$member_ads_switch = isset($atts['member_ads_switch']) ? $atts['member_ads_switch'] : 'no';
if ( $member_ads_switch == 'yes' ) {
    $member_ads_after_list_series = isset($atts['member_ads_after_list_count']) ? $atts['member_ads_after_list_count'] : '5';
    if ( $member_ads_after_list_series != '' ) {
        $member_ads_list_array = explode(",", $member_ads_after_list_series);
    }
    $member_ads_after_list_array_count = sizeof($member_ads_list_array);
    $member_ads_after_list_flag = 0;

    $i = 0;
    $array_i = 0;
    $member_ads_after_list_array_final = '';
    while ( $member_ads_after_list_array_count > $array_i ) {
        if ( isset($member_ads_list_array[$array_i]) && $member_ads_list_array[$array_i] != '' ) {
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
if ( $member_page >= 2 ) {
    $counter = ( ($member_page - 1) * $posts_per_page );
}
$member_ads_number_counter = 1;
$member_ads_flag_counter = 0;
$member_ads_last_number = 0;
if ( isset($member_ads_after_list_array) && ! empty($member_ads_after_list_array) ) {
    foreach ( $member_ads_after_list_array as $key => $member_ads_number ) {
        $member_ads_last_number = $member_ads_number;
    }
    foreach ( $member_ads_after_list_array as $key => $member_ads_number ) {
        if ( $member_page == 1 || $member_page == '' ) {
            $member_ads_flag_counter = $key;
            break;
        } elseif ( $counter < $member_ads_number ) {
            $member_ads_flag_counter = $key;
            break;
        } elseif ( $member_ads_number_counter == $member_ads_after_list_array_count ) {
            $member_ads_flag_counter = $key;
            break;
        }
        $member_ads_number_counter ++;
    }
}
// end ads script
$columns_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
if ( $member_view == 'grid' ) {
    $columns_class = 'col-lg-4 col-md-4 col-sm-12 col-xs-12';
    if ( $search_box == 'yes' && $member_view != 'map' ) {
        $columns_class = 'col-lg-6 col-md-6 col-sm-12 col-xs-12';
    }
    $main_class = 'member-grid';
}
$member_location_options = isset($atts['member_location']) ? $atts['member_location'] : '';
if ( $member_location_options != '' ) {
    $member_location_options = explode(',', $member_location_options);
}
$http_request = wp_dp_server_protocol();
if ( $member_loop_obj->have_posts() ) {
    $flag = 1;
    ?>
    <div class="member-listing member-grid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <?php
                if ( $member_ads_switch == 'yes' ) {
                    if ( $member_ads_after_list_array_count > 0 && ( $member_page == 1 || $member_page == '') ) {
                        if ( $counter == $member_ads_after_list_array[$member_ads_flag_counter] && $member_ads_after_list_array[$member_ads_flag_counter] == 0 ) {
                            ?>
                            <div class="member-post row">
                                <?php do_action('wp_dp_random_ads', 'member_banner'); ?>
                            </div>

                            <?php
                            if ( $member_ads_flag_counter < $member_ads_after_list_array_count ) {
                                $member_ads_flag_counter ++;
                            }
                        }
                    }
                }
                echo '<div class="member-post row">';
                while ( $member_loop_obj->have_posts() ) : $member_loop_obj->the_post();
                    global $wp_dp_author_info,$post, $wp_dp_member_profile;
                    $post_id = $post;
                    $member_is_featured = get_post_meta($post_id, 'wp_dp_member_is_featured', true);
                    $member_is_trusted = get_post_meta($post_id, 'wp_dp_member_is_trusted', true);
                    $member_id = get_post_meta($post_id, 'wp_dp_listing_member', true);
                    $Wp_dp_Locations = new Wp_dp_Locations();
                    $get_member_location = $Wp_dp_Locations->get_element_member_location($post_id, $member_location_options);

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
                    $num_of_listings = $custom_query->found_posts;
                    $member_image_id = get_post_meta($post_id, 'wp_dp_profile_image', true);
                    $member_image = wp_get_attachment_image_src($member_image_id, 'wp_dp_cs_media_4');
                    $wp_dp_phone_number = get_post_meta($post_id, 'wp_dp_phone_number', true);
                    ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <div class="post-inner-member" itemprop="performer" itemscope="" itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Person">
                            <div class="img-holder">
                                <?php if ( isset($member_is_featured) && $member_is_featured == 'on' ) {
                                    ?><span class="post-featured"><?php echo wp_dp_plugin_text_srt('wp_dp_member_featured'); ?></span>
                                    <?php
                                }
                                if ( isset($member_is_trusted) && $member_is_trusted == 'on' ) {
                                    ?><span class="trusted-member"><i class="icon-verified_user"></i></span>
                                <?php }
                                ?>
                                <figure>
                                    <a title = "<?php echo esc_html(get_the_title($post_id)); ?>" href = "<?php the_permalink(); ?>">
                                        <?php
                                        if ( $member_image != '' && is_array($member_image) ) {
                                            $no_image = '<img itemprop="image" src="' . esc_url($member_image[0]) . '" alt="" />';
                                            echo force_balance_tags($no_image);
                                        } else {
                                            $no_image_url = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image4x3.jpg');
                                            $no_image = '<img itemprop="image" class="img-grid" src="' . esc_url($no_image_url) . '" alt="" />';
                                            echo force_balance_tags($no_image);
                                        }
                                        ?>
                                    </a>
                                </figure>
                            </div>
                            <div class="text-holder">
                                <div class="post-title">
                                    <h4 itemprop="name">
                                        <a title="<?php echo esc_html(get_the_title($post_id)); ?>" href="<?php the_permalink(); ?>"><?php echo esc_html(get_the_title($post_id)); ?></a>
                                    </h4>
                                </div>  
                                <ul class="member-info">
                                    <?php
                                    if ( $wp_dp_phone_number != '' ) {
                                        $all_data = '';
                                        $all_data = $wp_dp_author_info->wp_dp_member_member_phone_num($wp_dp_phone_number,'icon-phone2','<li>', '</li>', 'itemprop="telephone"');
                                        echo force_balance_tags($all_data);
                                        }
                                    ?> 
                                    <li><?php do_action('wp_dp_contact_form_element_html', $post_id); ?></li>
                                    <?php if ( !empty($get_member_location) ) { ?>
                                        <span itemprop="address" class="member-address"><i class="icon-location-pin2"></i><?php echo esc_html(implode(', ', $get_member_location)); ?> </span>
                                    <?php } ?> 
                                </ul>
                                <?php
                                $listings_link_start = '';
                                $listings_link_end = '';
                                if ( $num_of_listings > 0 ) {
                                    $listings_link_start = '<a href="' . get_the_permalink($member_id) . '#listings">';
                                    $listings_link_end = '</a>';
                                }
                                ?>
                                <span class="listings-count"><?php echo wp_dp_allow_special_char($listings_link_start); ?> <span> <?php echo absint($num_of_listings); ?> </span><?php echo wp_dp_plugin_text_srt('wp_dp_member_listings2'); ?>  <?php echo wp_dp_allow_special_char($listings_link_end); ?></span>
                            </div> 
                        </div>
                    </div>
                    <?php
                    if ( $member_ads_switch == 'yes' ) {
                        if ( $member_ads_after_list_array_count > 0 ) {
                            $new_counter = $counter + 1;
                            $member_ads_value = $member_ads_after_list_array[$member_ads_flag_counter];
                            if ( $new_counter == $member_ads_after_list_array[$member_ads_flag_counter] ) {
                                // close listing UL before start ad HTML\
                                echo '</div>';
                                ?>
                                <div class="member-post row">
                                    <?php do_action('wp_dp_random_ads', 'member_banner'); ?>
                                </div>
                                <?php
                                // start listing UL after end ad HTML\
                                echo '<div class="member-post row">';
                                if ( $member_ads_flag_counter < ($member_ads_after_list_array_count - 1) ) {
                                    $member_ads_flag_counter ++;
                                }
                            } elseif ( $new_counter % $member_ads_value == 0 && $new_counter > $member_ads_last_number && $new_counter != 1 ) {
                                // close listing UL before start ad HTML\
                                echo '</div>';
                                ?>
                                <div class="member-post row">
                                    <?php do_action('wp_dp_random_ads', 'member_banner'); ?>
                                </div>
                                <?php
                                // start listing UL after end ad HTML\
                                echo '</div>';
                            }
                        }
                    }
                    $counter ++;
                    ?>
                    <?php
                endwhile;
                ?>
            </div>
        </div>
    </div>
    <?php
} else {
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-member-match-error"><h6><i class="icon-warning"></i><strong> ' . wp_dp_plugin_text_srt('wp_dp_member_sorry') . '</strong>&nbsp; ' . wp_dp_plugin_text_srt('wp_dp_member_no_results') . ' </h6></div>';
}