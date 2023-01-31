<?php
/**
 * The template for displaying single listing
 *
 */
get_header();
global $post, $wp_dp_plugin_options, $wp_dp_theme_options, $Wp_dp_Captcha, $wp_dp_form_fields, $wp_dp_post_listing_types;

$post_id = $post->ID;
if (!wp_script_is( 'wp-dp-google-map-api', 'enqueued' )) {
    wp_enqueue_script('wp-dp-google-map-api');
}
wp_enqueue_script('wp-dp-prettyPhoto');
wp_enqueue_style('wp-dp-prettyPhoto');
wp_enqueue_script('map-infobox');
wp_enqueue_script('map-clusterer');
wp_enqueue_script('wp-dp-validation-script');


$wp_dp_base_query_args = '';
if ( function_exists('wp_dp_base_query_args') ) {
    $wp_dp_base_query_args = wp_dp_base_query_args();
}

if ( function_exists('wp_dp_listing_visibility_query_args') ) {
    $wp_dp_base_query_args = wp_dp_listing_visibility_query_args($wp_dp_base_query_args);
}

$paging_var = 'paged_id';
if ( ! isset($_REQUEST[$paging_var]) ) {
    $_REQUEST[$paging_var] = '';
}

$posts_per_page = 6;
// custom listing

$list_args = array(
    'posts_per_page' => $posts_per_page,
    'post_type' => 'listings',
    'post_status' => 'publish',
    'paged' => $_REQUEST[$paging_var],
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
        $wp_dp_base_query_args,
    ),
);
$custom_query = new WP_Query($list_args);
$post_count = $custom_query->found_posts;
// get branches for this agency.
$args = array(
    'post_type' => 'branches',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'fields' => 'ids',
    'meta_query' =>
    array(
        array(
            'relation' => 'AND',
            array(
                'key' => 'wp_dp_branch_member',
                'value' => $post_id,
                'compare' => '=',
            ),
        )
    )
);
$branches = get_posts($args);

$team_args = array(
    'role' => 'wp_dp_member',
    'meta_query' => array(
        array(
            'key' => 'wp_dp_company',
            'value' => $post_id,
            'compare' => '='
        ),
        array(
            'relation' => 'OR',
            array(
                'key' => 'wp_dp_user_status',
                'compare' => 'NOT EXISTS',
                'value' => 'completely'
            ),
            array(
                'key' => 'wp_dp_user_status',
                'value' => 'deleted',
                'compare' => '!='
            ),
        ),
        array(
            'relation' => 'OR',
            array(
                'key' => 'wp_dp_public_profile',
                'compare' => 'NOT EXISTS',
                'value' => 'completely'
            ),
            array(
                'key' => 'wp_dp_public_profile',
                'value' => 'yes',
                'compare' => '='
            ),
        ),
    ),
);

$team_members = get_users($team_args);

/*
 * featured count query
 */

$args_featured = array(
    'posts_per_page' => '1',
    'order' => 'DESC',
    'orderby' => 'date',
    'post_type' => 'listings',
    'paged' => $_REQUEST[$paging_var],
    'post_status' => 'publish',
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key' => 'wp_dp_listing_member',
            'value' => $post_id,
            'compare' => '=',
        ),
        $wp_dp_base_query_args,
    ),
);
$custom_query_featured = new WP_Query($args_featured);
$featured_count = $custom_query_featured->post_count;

$wp_dp_email_address = get_post_meta($post_id, 'wp_dp_email_address', true);
$wp_dp_email_address = isset($wp_dp_email_address) ? $wp_dp_email_address : '';
$wp_dp_cs_email_counter = rand(3433, 7865);
$success = 'Email sent successfully';
$wp_dp_cs_inline_script = '
    
function wp_dp_contact_form_valid_press() {
                            
                                var returnType = wp_dp_validation_process(jQuery(".contactform_name"));
                                if (returnType == false) {
                                    return false;
                                }

                            }


function wp_dp_contact_send_message(form_id) {
    var returnType = wp_dp_validation_process(jQuery(".contactform_name"));
    if (returnType == false) {
        return false;
    }else{
    var wp_dp_cs_mail_id = \'' . esc_js($wp_dp_cs_email_counter) . '\';
    var thisObj = jQuery(".contact-message-submit");
    wp_dp_show_loader(".contact-message-submit", "", "button_loader", thisObj);
    if (form_id == wp_dp_cs_mail_id) {
        var $ = jQuery;
        var datastring = $("#contactfrm' . esc_js($wp_dp_cs_email_counter) . '").serialize() + "&wp_dp_member_email=' . esc_html($wp_dp_email_address) . '&wp_dp_cs_contact_succ_msg=' . esc_js($success) . '&wp_dp_cs_contact_error_msg=' . esc_js($error) . '&action=wp_dp_contact_message_send";
        $.ajax({
            type: \'POST\',
            url: \'' . esc_js(esc_url(admin_url('admin-ajax.php'))) . '\',
            data: datastring,
            dataType: "json",
            success: function (response) {
                    wp_dp_show_response( response, "", thisObj);
            }
        });
    }
    }
    return false;
}';
wp_dp_cs_inline_enqueue_script($wp_dp_cs_inline_script, 'wp-dp-custom-inline');





$member_is_trusted = get_post_meta($post_id, 'wp_dp_member_is_trusted', true);
$wp_dp_phone_number = get_post_meta($post_id, 'wp_dp_phone_number', true);
$wp_dp_email_address = get_post_meta($post_id, 'wp_dp_email_address', true);
$wp_dp_email_address = isset($wp_dp_email_address) ? $wp_dp_email_address : '';
$wp_dp_post_loc_address_member = get_post_meta($post_id, 'wp_dp_post_loc_address_member', true);
$wp_dp_facebook = get_post_meta($post_id, 'wp_dp_facebook', true);
//$wp_dp_google_plus = get_post_meta($post_id, 'wp_dp_google_plus', true);
$wp_dp_twitter = get_post_meta($post_id, 'wp_dp_twitter', true);
$wp_dp_linkedIn = get_post_meta($post_id, 'wp_dp_linkedIn', true);
$wp_dp_post_loc_latitude_member = get_post_meta($post_id, 'wp_dp_post_loc_latitude_member', true);
$wp_dp_post_loc_longitude_member = get_post_meta($post_id, 'wp_dp_post_loc_longitude_member', true);
$default_zoom_level = ( isset($wp_dp_plugin_options['wp_dp_map_zoom_level']) && $wp_dp_plugin_options['wp_dp_map_zoom_level'] != '' ) ? $wp_dp_plugin_options['wp_dp_map_zoom_level'] : 10;
$wp_dp_listing_zoom = get_post_meta($post_id, 'wp_dp_post_loc_zoom_member', true);
if ( $wp_dp_listing_zoom == '' || $wp_dp_listing_zoom == 0 ) {
    $wp_dp_listing_zoom = $default_zoom_level;
}
/*
 * set location static
 */
$member_location_options = 'city,country';
if ( $member_location_options != '' ) {
    $member_location_options = explode(',', $member_location_options);
}
$Wp_dp_Locations = new Wp_dp_Locations();
$get_member_location = $Wp_dp_Locations->get_element_member_location($post_id, $member_location_options);

$member_title = '';
$member_title = get_the_title($post_id);
$member_link = '';
$member_link = get_the_permalink($post_id);
$member_image_id = get_post_meta($post_id, 'wp_dp_profile_image', true);
$member_image = wp_get_attachment_image_src($member_image_id, 'thumbnail');
if ( isset($member_image) && ! empty($member_image) && is_array($member_image) ) {
    $member_image = isset($member_image[0]) ? $member_image[0] : '';
}
if ( $member_image == '' ) {
    $member_image = esc_url(wp_dp::plugin_url() . 'assets/frontend/images/member-no-image.jpg');
}

wp_enqueue_script('wp-dp-split-map');
wp_enqueue_script('flexslider');
wp_enqueue_script('flexslider-mousewheel');
wp_enqueue_script('wp-dp-bootstrap-slider');
wp_enqueue_script('wp-dp-matchHeight-script');
wp_enqueue_script('wp-dp-listing-functions');
wp_enqueue_style('flexslider');
?>




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




<div class="main-section member-detail">
    <div class="page-section" >
        <div class="member-info-bg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="member-info">
                            <div class="img-holder">
                                <figure>
                                    <?php
                                    if ( isset($member_image) && $member_image != '' ) {
                                        ?>
                                        <img itemprop="image" src="<?php echo esc_url($member_image); ?>" alt="" />
                                        <?php
                                    }
                                    ?>
                                </figure>
                            </div>
                            <div class="text-holder">
                                <div class="title-area">
                                    <strong itemprop="name"><?php the_title(); ?></strong>
                                </div> 
                                <?php if ( $wp_dp_facebook != '' || $wp_dp_twitter != '' || $wp_dp_linkedIn != '' ) { ?>
                                    <div class="social-area"> 
                                        <ul class="social-media">
                                            <?php if ( $wp_dp_facebook != '' ) { ?>
                                                <li><a href="<?php echo esc_url($wp_dp_facebook); ?>" title="<?php echo wp_dp_plugin_text_srt('wp_dp_user_meta_facebook'); ?>" target="_blank"><i class="icon-facebook5"></i></a></li>
                                            <?php } ?>
                                            <?php if ( $wp_dp_twitter != '' ) { ?>
                                                <li><a href="<?php echo esc_url($wp_dp_twitter); ?>" title="<?php echo wp_dp_plugin_text_srt('wp_dp_user_meta_twitter'); ?>" target="_blank"><i class="icon-twitter"></i></a></li>
                                            <?php } if ( $wp_dp_linkedIn != '' ) { ?>
                                                <li><a href="<?php echo esc_url($wp_dp_linkedIn); ?>" title="<?php echo wp_dp_plugin_text_srt('wp_dp_user_meta_linkedIn'); ?>" target="_blank"><i class="icon-linkedin4"></i></a></li>
                                            <?php } ?>
                                        </ul>

                                    </div>
                                <?php } ?>
                                <ul class="info-list">
                                    <?php if ( isset($get_member_location) && ! empty($get_member_location) ) { ?>
                                        <li class="member-location"><em><i class="icon-map-marker"></i></em><a itemprop="address" href="#" class="branch-address-link" data-lat="<?php echo wp_dp_cs_allow_special_char($wp_dp_post_loc_latitude_member); ?>" data-lng="<?php echo wp_dp_cs_allow_special_char($wp_dp_post_loc_longitude_member); ?>"><?php echo esc_html(implode(', ', $get_member_location)); ?></a>
                                            <button id="member_map_collapsee" type="button" class="member-map-btn collapsed" data-toggle="collapse" data-target="#member-map-collapse"><?php echo wp_dp_plugin_text_srt('wp_dp_agent_review_show_map'); ?></button>
                                        </li>
                                        <?php
                                    }
                                    if ( isset($member_is_trusted) && $member_is_trusted == 'on' ) {
                                        ?><li class="member-trust"><em><i class="icon-verified_user"></i></em><span><?php echo wp_dp_plugin_text_srt('wp_dp_agent_review_verified'); ?></span></li>
                                    <?php }
                                    ?>
                                    <?php
                                    if ( isset($wp_dp_phone_number) && $wp_dp_phone_number != '' ) {
                                        $all_data = '';
                                        $all_data = $wp_dp_author_info->wp_dp_member_member_phone_num($wp_dp_phone_number, 'icon-technology', '<li  class="member-phone">', '</li>', 'itemprop="telephone"', true);
                                        echo force_balance_tags($all_data);
                                        $wp_dp_phone_number = str_replace(" ", "-", $wp_dp_phone_number);
                                    }
                                    ?>
                                    <?php if ( isset($wp_dp_email_address) && $wp_dp_email_address != '' ) { ?>
                                        <li class="member-email"><em><i class="icon-mail-note"></i></em> <a itemprop="address" href="#" class="branch-address-link" ><?php echo esc_html($wp_dp_email_address); ?></a></li>
                                    <?php } ?>
                                    <?php
                                    $class_reviews_object = new Wp_dp_Reviews();
                                    $wp_dp_review = $class_reviews_object->get_user_reviews_count($post_id, false, false, true);
                                    $reviews = $class_reviews_object->get_user_reviews_for_post($post_id, 0, 10, 'newest', false, true);
                                    foreach ( $reviews as $key => $review ) :
                                        if ( isset($review['is_reply']) && $review['is_reply'] == true ) {
                                            $wp_dp_review --;
                                        }
                                    endforeach;

                                    $wp_dp_review = isset($wp_dp_review) ? $wp_dp_review : 0;

                                    if ( isset($wp_dp_review) && $wp_dp_review != '' ) {
                                        ?>
                                        <li  class="member-review"><em><?php echo absint($wp_dp_review); ?></em><span><?php echo wp_dp_plugin_text_srt('wp_dp_agent_review_reviews'); ?></span></li>
                                    <?php } ?>
                                </ul>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <?php
                // load Member template
                set_query_var('custom_query_featured', $custom_query_featured);
                set_query_var('featured_count', $featured_count);
                set_query_var('paging_var', $paging_var);
                set_query_var('member_id', $post_id);
                set_query_var('paging_var_perpage', $posts_per_page);
                set_query_var('custom_query', $custom_query);
                set_query_var('post_count', $post_count);
                set_query_var('branches', $branches);
                set_query_var('team_members', $team_members);
                set_query_var('wp_dp_cs_email_counter', $wp_dp_cs_email_counter);
                wp_dp_get_template_part('member', 'view', 'single-member');
                ?>
            </div>
        </div>
    </div>

    <?php if ( isset($wp_dp_review) && $wp_dp_review != '' ) { ?>
        <div class="member-review-bg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="member-review">
                            <?php do_action('agent_reviews_ui', $post_id); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<script>
    jQuery(document).ready(function () {
        if (window.location.hash != "") {
            var hash_str = window.location.hash;
            jQuery('.nav.nav-tabs a[href="' + hash_str + '"]').tab('show');

        }
    });
</script>
<!-- Main End -->
<?php
get_footer();
