<?php
/**
 * Member Listings
 *
 */
if ( ! class_exists('Wp_dp_Member_Packages') ) {

    class Wp_dp_Member_Packages {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_ajax_wp_dp_member_packages', array( $this, 'wp_dp_member_packages_callback' ), 11, 1);
        }

        /**
         * Member Listings
         * @ filter the listings based on member id
         */
        public function wp_dp_member_packages_callback($member_id = '') {
            global $current_user;
            $member_id = wp_dp_company_id_form_user_id($current_user->ID);

            $wp_dp_current_date = strtotime(date('d-m-Y'));
            $args = array(
                'posts_per_page' => "-1",
                'post_type' => 'package-orders',
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_dp_transaction_user',
                        'value' => $member_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_dp_transaction_status',
                        'value' => 'approved',
                        'compare' => '=',
                    ),
                ),
            );

            $pkg_query = new WP_Query($args);
            ?>
            <ul class="breadcrumbs">
                <li class="user_dashboard_ajax" id="wp_dp_member_suggested" data-queryvar="dashboard=suggested"><a href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_Dashboard'); ?></a></li>
                <li class="active"><?php echo wp_dp_plugin_text_srt('wp_dp_member_dashboard_packages'); ?></li>
            </ul>
            <div class="user-dashboard-background">
                <?php
                echo force_balance_tags($this->render_view($pkg_query));
                ?>
            </div>
            <?php
            wp_reset_postdata();
            wp_die();
        }

        public function purchase_package_info_field_show($value = '', $label = '', $value_plus = '') {

            if ( $value != '' && $value != 'on' ) {
                $html = '<li><label>' . $label . '</label><span>' . $value . ' ' . $value_plus . '</span></li>';
            } else if ( $value != '' && $value == 'on' ) {
                $html = '<li><label>' . $label . '</label><span><i class="icon-check"></i></span></li>';
            } else {
                $html = '<li><label>' . $label . '</label><span><i class="icon-minus"></i></span></li>';
            }

            return $html;
        }

        public function render_view($pkg_query) {
            global $wp_dp_plugin_options;
            $wp_dp_currency_sign = wp_dp_get_currency_sign();

            $has_border = ' has-border';
            if ( isset($pkg_query) && $pkg_query != '' && $pkg_query->have_posts() ) :
                $has_border = '';
            endif;
            ?>
            <div class="user-packages">
                <div class="element-title<?php echo wp_dp_allow_special_char($has_border); ?>">
                    <h4><?php echo wp_dp_plugin_text_srt('wp_dp_member_pkg_pkgs'); ?></h4>
                </div>
            </div>
            <div class="user-packages-list dashboard-package-list">
                <?php if ( isset($pkg_query) && $pkg_query != '' && $pkg_query->have_posts() ) : ?>
                    <div class="all-pckgs-sec">
                        <div class="wp-dp-pkg-header top-pkg-header">
                            <div class="pkg-title-price pull-left">
                                <label class="pkg-title"><?php echo wp_dp_plugin_text_srt('wp_dp_member_pkg_pkgs'); ?></label>
                                <span class="pkg-price"><strong><?php echo wp_dp_plugin_text_srt('wp_dp_member_price_s'); ?></strong>  </span>
                                <span class="pkg-listings"><strong><?php echo wp_dp_plugin_text_srt('wp_dp_member_listings'); ?></strong></span>
                                <span class="pkg-listings pkg-listings-duration"><strong><?php echo wp_dp_plugin_text_srt('wp_dp_member_listings_duration'); ?></strong></span>
                                <span class="pkg-listings pkg-pic"><strong><?php echo wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_pictures'); ?></strong></span>
                                <span class="pkg-listings pkg-video"><strong><?php echo wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_videos'); ?></strong></span>
                               <span class="pkg-listings pkg-status"><strong><?php echo wp_dp_plugin_text_srt('wp_dp_member_viewings_status'); ?></strong></span>
                            </div>
                            <div class="pkg-detail-btn pull-right">
                                <a class="wp-dp-dev-dash-detail-pkg" href="javascript:void(0);"></a>
                            </div>
                        </div>
                        <div class="wp-dp-pkg-holder">
                            <?php
                            while ( $pkg_query->have_posts() ) : $pkg_query->the_post();
                                $transaction_package = get_post_meta(get_the_ID(), 'wp_dp_transaction_package', true);
                                $transaction_listings = get_post_meta(get_the_ID(), 'wp_dp_transaction_listings', true);
                                $transaction_feature_list = get_post_meta(get_the_ID(), 'wp_dp_transaction_listing_feature_list', true);
                                $transaction_top_cat_list = get_post_meta(get_the_ID(), 'wp_dp_transaction_listing_top_cat_list', true);
                                $wp_dp_currency_sign = get_post_meta(get_the_ID(), 'wp_dp_currency', true);
                                $wp_dp_currency_sign = ( $wp_dp_currency_sign != '' ) ? $wp_dp_currency_sign : '$';
                                $currency_position = get_post_meta(get_the_ID(), 'wp_dp_currency_position', true);
                                $package_id = get_the_ID();
                                $transaction_listings = isset($transaction_listings) ? $transaction_listings : 0;
                                $transaction_feature_list = isset($transaction_feature_list) ? $transaction_feature_list : 0;
                                $transaction_top_cat_list = isset($transaction_top_cat_list) ? $transaction_top_cat_list : 0;
                                
                                $package_price = get_post_meta($package_id, 'wp_dp_transaction_amount', true);

                                $wp_dp_listing_idss = get_post_meta(get_the_ID(), 'wp_dp_listing_ids', true);

                                if ( empty($wp_dp_listing_idss) ) {
                                    $wp_dp_listing_usedd = 0;
                                } else {
                                    $wp_dp_listing_usedd = absint(sizeof($wp_dp_listing_idss));
                                }
                                $html = '';
                                
                                  $trans_packg_list_expire = get_post_meta($package_id, 'wp_dp_transaction_listing_expiry', true);
                                   $trans_pics_num = get_post_meta($package_id, 'wp_dp_transaction_listing_pic_num', true);
                                   $trans_video_on_off = get_post_meta($package_id, 'wp_dp_transaction_listing_video', true);
                                   $wp_dp_transaction_status = get_post_meta($package_id, 'wp_dp_transaction_status', true);
                                   
                                   $trans_video_text = wp_dp_plugin_text_srt('wp_dp_plugin_seetings_package_feature_no');
                                   if( isset($trans_video_on_off) && $trans_video_on_off == 'on' ){
                                       $trans_video_text = wp_dp_plugin_text_srt('wp_dp_plugin_seetings_package_feature_yes');
                                       
                                   }
                                ?>
                                <div class="wp-dp-pkg-header">
                                    <div class="pkg-title-price pull-left">
                                        <label class="pkg-title"><?php echo get_the_title($transaction_package); ?></label>
                                        <span class="pkg-price"><span><?php echo wp_dp_get_order_currency($package_price, $wp_dp_currency_sign, $currency_position); ?></span> </span>
                                        <span class="pkg-listings"> <span> <?php echo esc_html($wp_dp_listing_usedd); ?>/<?php echo esc_html($transaction_listings); ?></span></span>
                                        
                                        <span class="pkg-listings pkg-listings-duration"><span><?php echo esc_html($trans_packg_list_expire).wp_dp_plugin_text_srt('wp_dp_member_days'); ?></span></span>
                                        <span class="pkg-listings pkg-pic"><span><?php echo esc_html($trans_pics_num); ?></span></span>
                                        <span class="pkg-listings pkg-video"><span><?php echo esc_html($trans_video_text); ?></span></span>
                                        <span class="pkg-listings pkg-status"><span><?php echo esc_html($wp_dp_transaction_status); ?></span></span>
                                        
                                        
                                    </div>
                                    <div class="pkg-detail-btn pull-right">
                                        <a data-id="<?php echo absint($package_id) ?>" class="wp-dp-dev-dash-detail-pkg" href="javascript:void(0);"><?php echo wp_dp_plugin_text_srt('wp_dp_member_pkg_detail'); ?></a>
                                    </div>
                                </div>
                                <div class="package-info-sec listing-info-sec" style="display:none;" id="package-detail-<?php echo absint($package_id) ?>">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <ul class="listing-pkg-points">
                                                <?php
                                                $trans_packg_list_num = get_post_meta($package_id, 'wp_dp_transaction_listings', true);
                                              
                                                $wp_dp_listing_ids = get_post_meta($package_id, 'wp_dp_listing_ids', true);

                                                if ( empty($wp_dp_listing_ids) ) {
                                                    $wp_dp_listing_used = 0;
                                                } else {
                                                    $wp_dp_listing_used = absint(sizeof($wp_dp_listing_ids));
                                                }

                                                $wp_dp_listing_dpain = '0';
                                                if ( (int) $trans_packg_list_num > (int) $wp_dp_listing_used ) {
                                                    $wp_dp_listing_dpain = (int) $trans_packg_list_num - (int) $wp_dp_listing_used;
                                                }
                                                $trans_featured_num = get_post_meta($package_id, 'wp_dp_transaction_listing_feature_list', true);
                                                $wp_dp_featured_ids = get_post_meta($package_id, 'wp_dp_featured_ids', true);
                                                if ( empty($wp_dp_featured_ids) ) {
                                                    $wp_dp_featured_used = 0;
                                                } else {
                                                    $wp_dp_featured_used = absint(sizeof($wp_dp_featured_ids));
                                                }
                                                $wp_dp_featured_dpain = '0';
                                                if ( (int) $trans_featured_num > (int) $wp_dp_featured_used ) {
                                                    $wp_dp_featured_dpain = (int) $trans_featured_num - (int) $wp_dp_featured_used;
                                                }

                                                $trans_top_cat_num = get_post_meta($package_id, 'wp_dp_transaction_listing_top_cat_list', true);
                                                $wp_dp_top_cat_ids = get_post_meta($package_id, 'wp_dp_top_cat_ids', true);

                                                if ( empty($wp_dp_top_cat_ids) ) {
                                                    $wp_dp_top_cat_used = 0;
                                                } else {
                                                    $wp_dp_top_cat_used = absint(sizeof($wp_dp_top_cat_ids));
                                                }

                                                $wp_dp_top_cat_dpain = '0';
                                                if ( (int) $trans_top_cat_num > (int) $wp_dp_top_cat_used ) {
                                                    $wp_dp_top_cat_dpain = (int) $trans_top_cat_num - (int) $wp_dp_top_cat_used;
                                                }
                                               
                                                $trans_docs_num = get_post_meta($package_id, 'wp_dp_transaction_listing_doc_num', true);
                                                $trans_tags_num = get_post_meta($package_id, 'wp_dp_transaction_listing_tags_num', true);
                                                $trans_reviews = get_post_meta($package_id, 'wp_dp_transaction_listing_reviews', true);

                                                $trans_phone_website = get_post_meta($package_id, 'wp_dp_transaction_listing_phone_website', true);
                                                $trans_social = get_post_meta($package_id, 'wp_dp_transaction_listing_social', true);
                                                $trans_ror = get_post_meta($package_id, 'wp_dp_transaction_listing_ror', true);
                                                $trans_dynamic_f = get_post_meta($package_id, 'wp_dp_transaction_dynamic', true);

                                                $html .= '<li><label>' . wp_dp_plugin_text_srt('wp_dp_member_listings') . '</label><span>' . absint($wp_dp_listing_used) . '/' . absint($trans_packg_list_num) . '</span></li>';
                                                $html .= $this->purchase_package_info_field_show($trans_packg_list_expire, wp_dp_plugin_text_srt('wp_dp_member_listings_duration'), wp_dp_plugin_text_srt('wp_dp_member_days'));


                                                $html .= $this->purchase_package_info_field_show($trans_pics_num, wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_pictures'));
                                                $html .= $this->purchase_package_info_field_show($trans_docs_num, wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_docs'));
                                                $html .= $this->purchase_package_info_field_show($trans_tags_num, wp_dp_plugin_text_srt('wp_dp_member_add_list_no_of_tags'));
                                                $html .= $this->purchase_package_info_field_show($trans_phone_website, wp_dp_plugin_text_srt('wp_dp_member_add_list_phone_number'));
                                                $html .= $this->purchase_package_info_field_show($trans_social, wp_dp_plugin_text_srt('wp_dp_member_add_list_social_reach'));
                                                
                                                $dyn_fields_html = '';
                                                if ( is_array($trans_dynamic_f) && sizeof($trans_dynamic_f) > 0 ) {
                                                    foreach ( $trans_dynamic_f as $trans_dynamic ) {
                                                        if ( isset($trans_dynamic['field_type']) && isset($trans_dynamic['field_label']) && isset($trans_dynamic['field_value']) ) {
                                                            $d_type = $trans_dynamic['field_type'];
                                                            $d_label = $trans_dynamic['field_label'];
                                                            $d_value = $trans_dynamic['field_value'];

                                                            if ( $d_value == 'on' && $d_type == 'single-choice' ) {
                                                                $html .= '<li><label>' . $d_label . '</label><span><i class="icon-check"></i></span></li>';
                                                            } else if ( $d_value != '' && $d_type != 'single-choice' ) {
                                                                $html .= '<li><label>' . $d_label . '</label><span>' . $d_value . '</span></li>';
                                                            } else {
                                                                $html .= '<li><label>' . $d_label . '</label><span><i class="icon-minus"></i></span></li>';
                                                            }
                                                        }
                                                    }
                                                    // end foreach
                                                }
                                                // emd of Dynamic fields
                                                // other Features
                                                echo force_balance_tags($html);
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <?php
                            endwhile;
                            ?>
                        </div>
                    </div>
                    <?php
                else:
                    echo wp_dp_plugin_text_srt('wp_dp_member_pkg_sorry_no_pkg');
                endif;
                ?>
            </div>
            <?php
        }

        public function render_list_item_view($pkg_query) {
            
        }

    }

    global $wp_dp_member_packages;
    $wp_dp_member_packages = new Wp_dp_Member_Packages();
}
