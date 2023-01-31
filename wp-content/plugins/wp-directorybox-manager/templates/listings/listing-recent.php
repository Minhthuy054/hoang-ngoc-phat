<?php
/**
 * Listing search box
 *
 */
global $wp_dp_post_listing_types, $wp_dp_plugin_options;

$listings_title_alignment = isset($atts['listings_title_alignment']) ? $atts['listings_title_alignment'] : '';
$listing_location_options = isset($atts['listing_location']) ? $atts['listing_location'] : '';
if ( $listing_location_options != '' ) {
    $listing_location_options = explode(',', $listing_location_options);
}
$main_class = 'recent-listing';
$columns_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
$recent_listing_loop_obj = wp_dp_get_cached_obj('recent_listing_result_cached_loop_obj', $recent_listing_args, 12, false, 'wp_query');

$http_request = wp_dp_server_protocol();

if ( $recent_listing_loop_obj->have_posts() ) {
    $flag = 1;
    ?>
    <div class="directorybox-recent-listings">
        <div class="row">
            <div class="<?php echo esc_html($columns_class); ?>">
                <div class="element-title <?php echo esc_html($listings_title_alignment); ?>">
                    <h2><?php echo wp_dp_plugin_text_srt('wp_dp_listing_recent_heading') ?></h2> 
                </div>
            </div>
            <?php
            while ( $recent_listing_loop_obj->have_posts() ) : $recent_listing_loop_obj->the_post();
                global $post, $wp_dp_member_profile;
                $listing_id = $post;
                $Wp_dp_Locations = new Wp_dp_Locations();
                $get_listing_location = $Wp_dp_Locations->get_element_listing_location($listing_id, $listing_location_options);
                $wp_dp_listing_price_options = get_post_meta($listing_id, 'wp_dp_listing_price_options', true);
                $wp_dp_listing_type = get_post_meta($listing_id, 'wp_dp_listing_type', true);
                // checking review in on in listing type
                $wp_dp_listing_type = isset($wp_dp_listing_type) ? $wp_dp_listing_type : '';
                if ( $listing_type_post = get_page_by_path($wp_dp_listing_type, OBJECT, 'listing-type') )
                    $listing_type_id = $listing_type_post->ID;
                $listing_type_id = isset($listing_type_id) ? $listing_type_id : '';
				$listing_type_id = wp_dp_wpml_lang_page_id( $listing_type_id, 'listing-type' );
                $wp_dp_listing_type_price_switch = get_post_meta($listing_type_id, 'wp_dp_listing_type_price', true);
                $wp_dp_listing_price = '';
                if ( $wp_dp_listing_price_options == 'price' ) {
                    $wp_dp_listing_price = get_post_meta($listing_id, 'wp_dp_listing_price', true);
                } else if ( $wp_dp_listing_price_options == 'on-call' ) {
                    $wp_dp_listing_price = wp_dp_plugin_text_srt('wp_dp_listings_price_on_request');
                } 
                ?>
                <div class="<?php echo esc_html($columns_class); ?>">
                    <div class="listing-medium <?php echo esc_html($main_class); ?> " itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Product">
                        <div class="text-holder">
                            <div class="post-title">
                                <h4 itemprop="name"><a href="<?php echo esc_url(get_permalink($listing_id)); ?>"><?php echo esc_html(get_the_title($listing_id)); ?></a></h4>
                            </div>
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
                            if ( ! empty($get_listing_location) ) {
                                ?>
                                <ul class="listing-location">
                                    <li><i class="icon-location-pin2"></i><span><?php echo esc_html(implode(', ', $get_listing_location)); ?></span></li>
                                </ul>
                                <?php
                            }
                            if ( $wp_dp_listing_type_price_switch == 'on' && $wp_dp_listing_price != '' ) {
                                ?>
                                <span class="listing-price" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Offer">
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
                            <?php } ?>
                        </div> 
                    </div>
                </div>
                <?php
            endwhile;
            ?>
        </div>
    </div>
<?php }
?>
<!--Wp-dp recent Element End-->