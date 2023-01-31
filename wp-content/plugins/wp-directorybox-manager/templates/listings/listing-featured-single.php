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
if (is_user_logged_in()) {
	$user_id = get_current_user_id();
	$user_company = get_user_meta($user_id, 'wp_dp_company', true);
}
$wp_dp_listing_title_limit = isset($atts['listings_title_limit']) ? $atts['listings_title_limit'] : '20';
$listings_content_limit = isset($atts['listings_content_limit']) ? $atts['listings_content_limit'] : '100';

$default_listing_no_custom_fields = isset($wp_dp_plugin_options['wp_dp_listing_no_custom_fields']) ? $wp_dp_plugin_options['wp_dp_listing_no_custom_fields'] : '';
$listing_no_custom_fields = isset($atts['listing_no_custom_fields']) ? $atts['listing_no_custom_fields'] : $default_listing_no_custom_fields;
if ( $listing_no_custom_fields == '' || ! is_numeric($listing_no_custom_fields) ) {
    $listing_no_custom_fields = 3;
}
$http_request = wp_dp_server_protocol();
if ( $listing_loop_obj->have_posts() ) {
	wp_enqueue_style('swiper');
            wp_enqueue_script('swiper');
    ?>
    <div class="featured-slider">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                while ( $listing_loop_obj->have_posts() ) : $listing_loop_obj->the_post();
                    $listing_id = $post;
                    ?>
                    <div class="swiper-slide" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Product">
                        <?php
                        $default_image = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/no-image9x6.jpg');
                        if ( function_exists('listing_gallery_first_image') ) {
                            $gallery_image_args = array(
                                'listing_id' => $listing_id,
                                'size' => 'wp_dp_media_10',
                                'class' => 'img-grid',
                                'default_image_src' => $default_image,
                                'img_extra_atr' =>'itemprop="image"',
                            );
                            $listing_gallery_first_image = listing_gallery_first_image($gallery_image_args); 
                        }
                        $col_class = 'col-lg-6 col-md-6 col-sm-12 col-xs-12';
                        if ( $listing_gallery_first_image == $default_image ) {
                            $listing_gallery_first_image = '';
                            $col_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
                        }
                        ?>
                        <?php if ( $listing_gallery_first_image != '' ) { ?>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="img-frame classic has-border has-shadow">
                                    <figure>
                                        <?php echo wp_dp_allow_special_char($listing_gallery_first_image); ?>
                                    </figure>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="<?php echo esc_html($col_class); ?>">
                            <div class="column-text classic">
                                <h2 itemprop="name"><a href="<?php echo esc_url(get_permalink($listing_id)); ?>"><?php echo (wp_dp_limit_text(get_the_title($listing_id), $wp_dp_listing_title_limit)) ?></a></h2>
                                <p itemprop="description" class="description"><?php echo wp_dp_limit_text(get_the_content($listing_id), $listings_content_limit, ' ...'); ?></p>
                                <?php
                                // All custom fields with value
                                $cus_fields = array( 'content' => '' );
                                $cus_fields = apply_filters('wp_dp_featured_custom_fields', $listing_id, $cus_fields, $listing_no_custom_fields);
                                if ( isset($cus_fields['content']) && $cus_fields['content'] != '' ) {
                                    ?>
                                    <ul class="categories-holder classic" itemprop="category">
                                        <?php echo wp_dp_allow_special_char($cus_fields['content']); ?>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <?php
} else {
    echo '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-listing-match-error"><h6><i class="icon-warning"></i><strong> ' . wp_dp_plugin_text_srt('wp_dp_listing_slider_sorry') . '</strong>&nbsp; ' . wp_dp_plugin_text_srt('wp_dp_listing_slider_doesn_match') . ' </h6></div></div>';
}
?>